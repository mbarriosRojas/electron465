<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Generación TXT cobranza en bolívares (PHP, independiente del binario Gtxt).
 *
 * Acumula todas las cuotas vencidas hasta la fecha de corte ("Hasta la Fecha")
 * y convierte a Bs con la tasa Dólar Cobranza (no BCV) del día.
 */
class Mgtxt_bolivares extends Model {

    protected $tbl = 'cpp_banco';

    /**
     * Bancos habilitados para este flujo por ahora (VENEZUELA no llega aquí,
     * se maneja aparte en cobranza.php::GtxtBs). Ver
     * docs/HUs-generar-txt-banfan-bicentenario-dolar-cobranza.md.
     */
    private $bancosActivos = array('BANFAN', 'BICENTENARIO', 'COTERO');

    /**
     * Construye los enlaces de descarga a partir de una lista de rutas de
     * archivo físicas. Si hay más de un archivo (lote dividido por el límite
     * de 9999 registros por TXT), cada enlace se numera "Parte X de Y"; si
     * hay uno solo, se muestra igual que antes de la partición.
     */
    private function construirEnlacesDescarga($paths) {
        $total = count($paths);
        $html = '';
        foreach ($paths as $indice => $path) {
            $nombre = basename($path);
            $etiqueta = ($total > 1) ? ('+ Descargar TXT — Parte ' . ($indice + 1) . ' de ' . $total) : '+ Descargar TXT';
            $html .= '<a href="' . __LOCALWWW__ . '/tmp/cobranza_bs/' . $nombre . '" download="' . $nombre . '">' . $etiqueta . '</a><br><br>';
        }
        return $html;
    }

    /**
     * Localiza en disco los archivos físicos ya generados para un $md5Nom
     * (uno solo, o varias partes "_parteN.txt" si el lote se dividió).
     */
    private function localizarArchivosGenerados($baseDir, $md5Nom) {
        $unico = $baseDir . 'tmp/cobranza_bs/' . $md5Nom . '.txt';
        if (is_file($unico)) {
            return array($unico);
        }
        $partes = glob($baseDir . 'tmp/cobranza_bs/' . $md5Nom . '_parte*.txt');
        if (!$partes) {
            return array($unico);
        }
        natsort($partes);
        return array_values($partes);
    }

    public function GtxtBs($param = array()) {
        // Lotes grandes (miles de líneas, ahora divididos en varios TXT de
        // máx. 9999) pueden superar el memory_limit default (128M) mientras
        // se acumulan los registros en memoria antes de escribir a disco.
        @ini_set('memory_limit', '512M');

        $sBnc = isset($param['banco']) ? trim($param['banco']) : '';
        $sEmp = isset($param['empresa']) ? (int) $param['empresa'] : 0;
        $sPer = isset($param['periodicidad']) ? $param['periodicidad'] : '4';
        $sFco = isset($param['fcontrato']) ? (int) $param['fcontrato'] : 0;
        $sNom = isset($param['nomina']) ? trim($param['nomina']) : '';
        $sFec = isset($param['fecha']) ? trim($param['fecha']) : '';
        $sTip = isset($param['tipo_archivo']) ? strtoupper(trim($param['tipo_archivo'])) : 'C';
        $sPic = isset($param['picar']) ? (float) $param['picar'] : 10000;
        $sFil = isset($param['filtro_cliente']) ? strtoupper(trim($param['filtro_cliente'])) : 'ACTIVOS';
        $sBco1 = isset($param['banco_nomina']) ? strtoupper(trim($param['banco_nomina'])) : '';
        $sFac = date('Ymd');

        if ($sBnc === '' || $sNom === '') {
            return '<br><br><center><h2>Complete Linaje y Nómina antes de generar.</h2></center>';
        }

        if (!in_array($sFil, array('ACTIVOS', 'MOROSOS'), true)) {
            return '<br><br><center><h2>Debe indicar el filtro de cliente (Activos o Morosos).</h2></center>';
        }

        if (!in_array(strtoupper($sBnc), $this->bancosActivos, true)) {
            return '<br><br><center><h2>Banco no habilitado.</h2>'
                . '<p>Por ahora solo se puede generar TXT Bolívares para: '
                . implode(', ', $this->bancosActivos) . ' (y Venezuela por su propio flujo).</p></center>';
        }

        // Cotero agrupa clientes de Venezuela/Bicentenario/Banfan por igual —
        // hay que elegir a cuál de los 3 se le va a enviar este archivo.
        if (strtoupper($sBnc) === 'COTERO' && !in_array($sBco1, array('VENEZUELA', 'BICENTENARIO', 'BANFAN'), true)) {
            return '<br><br><center><h2>Para Cotero debe seleccionar el Banco (Nómina): Venezuela, Bicentenario o Banfan.</h2></center>';
        }

        // Regla de oro Morosos: la periodicidad elegida en el formulario se
        // ignora (el motor siempre cobra las 4 variantes "mensual" juntas) —
        // se normaliza aquí también para que la clave de caché (md5) no
        // dependa de un valor de periodicidad que ya no aplica.
        if ($sFil === 'MOROSOS') {
            $sPer = 'MENSUALES';
        }

        if ($sFec === '') {
            return '<br><br><center><h2>Indique la fecha "Hasta la Fecha" antes de generar.</h2></center>';
        }

        // Cada banco negocia su propia tasa Cobranza — Cotero agrupa clientes
        // de varios bancos, así que ahí la tasa que aplica es la del banco de
        // nómina elegido ($sBco1), no una genérica de "Cotero".
        $bancoTasa = (strtoupper($sBnc) === 'COTERO') ? $sBco1 : strtoupper($sBnc);

        $CI =& get_instance();
        $CI->load->model('mprecio_dolar', 'MPrecio_dolar');
        $precioRow = $CI->MPrecio_dolar->getPrecioActivo(MPrecio_dolar::TIPO_COBRANZA, $bancoTasa);
        if (!$precioRow || (float) $precioRow->precio <= 0) {
            $precioRow = $CI->MPrecio_dolar->getUltimoRegistroVigente(MPrecio_dolar::TIPO_COBRANZA, $bancoTasa);
        }
        if (!$precioRow || (float) $precioRow->precio <= 0) {
            return '<br><br><center><h2>No hay tasa de Dólar Cobranza vigente.</h2>'
                . '<p>Regístrela en <strong>Panel de Control → Precio del Dólar</strong> antes de generar el TXT en bolívares.</p></center>';
        }

        $precioDolar = (float) $precioRow->precio;
        $baseDir = realpath(dirname(__FILE__) . '/../../../..') . '/';
        @mkdir($baseDir . 'tmp/cobranza_bs', 0775, true);

        $sPar = strtoupper('BS "' . $sBnc . '" ' . $sEmp . ' ' . $sPer . ' ' . $sFco . ' ' . $sFec . ' ' . $sFac
            . ' "' . $sNom . '" @' . number_format($precioDolar, 4, '.', '') . ' P' . $sPic . ' F' . $sFil . ' N' . $sBco1);
        $md5Nom = md5($sPar);
        $sUsu = isset($_SESSION['usuario']) ? $_SESSION['usuario'] : '';

        $this->db->query('DELETE FROM ' . $this->tbl . ' WHERE nmbr=' . $this->db->escape($sPar)
            . ' AND esta = 1 AND tarc = \'B\' LIMIT 1');

        $Consulta = $this->db->query('SELECT * FROM ' . $this->tbl . ' WHERE nmbr=' . $this->db->escape($sPar)
            . ' AND esta = 1 AND tarc = \'B\'');

        if ($Consulta->num_rows() == 0) {
            require_once APPPATH . 'models/cobranza/gtxt_generator.php';
            $gen = new GtxtGenerator($this->db);
            $genResult = $gen->generarBolivaresConCorte(array(
                'banco' => $sBnc,
                'empresa' => $sEmp,
                'periodicidad' => $sPer,
                'fcontrato' => $sFco,
                'nomina' => $sNom,
                'fecha' => $sFec,
                'tipo_archivo' => $sTip,
                'filtro_cliente' => $sFil,
                'banco_nomina' => $sBco1,
                'fact' => $sFac,
                'precio_dolar' => $precioDolar,
                'picar' => $sPic,
                'md5_nom' => $md5Nom,
                'base_dir' => $baseDir,
            ));

            if (empty($genResult['ok'])) {
                $detalle = htmlspecialchars(isset($genResult['error']) ? $genResult['error'] : 'Error desconocido');
                return '<br><br><center><h2>No se pudo generar el archivo TXT en bolívares.</h2>'
                    . '<p>' . $detalle . '</p></center>';
            }

            $archivosGenerados = isset($genResult['archivos']) ? $genResult['archivos'] : array(array('path' => $genResult['path']));
            $txtPath = $genResult['path'];
            $hashArchivo = md5_file($txtPath);
            $ejec = array('OK', $genResult['cant'], $genResult['mont']);

            $mesRef = date('Y-m');
            $sInsert = 'INSERT INTO ' . $this->tbl . ' (`nmbr`,`desc`,`esta`,`auto`,`lina`,`tama`,`empr`,`mes`,
                `peri`,`fcon`,`fact`,`nomi`,`cant`,`mont`,`tarc`,`proc`)
                VALUES (' . $this->db->escape($sPar) . ',' . $this->db->escape($md5Nom) . ',1,'
                . $this->db->escape($sUsu) . ',' . $this->db->escape(strtoupper($sBnc)) . ','
                . $this->db->escape($hashArchivo) . ',' . (int) $sEmp . ',' . $this->db->escape($mesRef) . ','
                . (int) $sPer . ',' . (int) $sFco . ',' . $this->db->escape($sFac) . ','
                . $this->db->escape($sNom) . ',' . (int) $ejec[1] . ',' . $this->db->escape($ejec[2]) . ',\'B\',' . $this->db->escape(date('Y-m-d H:i:s')) . ')';
            $this->db->query($sInsert);

            $etiquetaFiltro = ($sFil === 'MOROSOS')
                ? 'Morosos — deuda de meses anteriores a ' . htmlspecialchars($sFec)
                : 'Activos — solo la cuota del mes de ' . htmlspecialchars($sFec);
            $rutasArchivos = array();
            foreach ($archivosGenerados as $archivo) {
                $rutasArchivos[] = $archivo['path'];
            }
            $avisoLote = (count($rutasArchivos) > 1)
                ? '<h3>Se generaron <b>' . count($rutasArchivos) . ' archivos TXT</b> (máx. 9999 registros por archivo, restricción bancaria).</h3><br>'
                : '';
            $sEnl = '<br><br><center><h2>TXT en Bolívares generado con éxito</h2></center><br><br>'
                . '<h3>Modo: <b>' . $etiquetaFiltro . '</b></h3><br>'
                . '<h3>Tasa Dólar Cobranza aplicada: <b>' . number_format($precioDolar, 4, ',', '.') . ' Bs./USD</b>'
                . ' (fecha ' . htmlspecialchars($precioRow->fecha) . ')</h3><br>'
                . '<h3>Cuotas vencidas hasta: <b>' . htmlspecialchars($sFec) . '</b></h3><br>'
                . '<h3>Método de picar: <b>' . ($sPic > 0 ? number_format($sPic, 2, ',', '.') . ' Bs. por línea' : 'Sin picar (monto completo)') . '</b></h3><br>'
                . '<h3>Registros (líneas del archivo): <b>' . $ejec[1] . '</b></h3><br>'
                . '<h3>Monto total del archivo: <b>' . number_format($ejec[2], 2, ',', '.') . ' Bs.</b></h3><br><br>'
                . $avisoLote
                . $this->construirEnlacesDescarga($rutasArchivos) . '</center>';
        } else {
            $rutasArchivos = $this->localizarArchivosGenerados($baseDir, $md5Nom);
            $sEnl = '<br><br><center><h2>El archivo en bolívares ya fue generado</h2></center><br><br>'
                . $this->construirEnlacesDescarga($rutasArchivos) . '</center>';
        }

        return $sEnl;
    }
}
