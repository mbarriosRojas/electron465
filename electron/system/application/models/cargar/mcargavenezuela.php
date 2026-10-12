<?php

/**
 * Carga respuesta Banco Venezuela (CSV) y aplicación de cobros en USD.
 */
class Mcargavenezuela extends Model {

    public function __construct() {
        parent::__construct();
        $this->ensureSchema();
    }

    public function ensureSchema() {
        if (!$this->db->table_exists('t_archivos_venezuela')) {
            return;
        }
        if (!$this->db->field_exists('precio_dolar', 't_archivos_venezuela')) {
            $this->db->query(
                'ALTER TABLE `t_archivos_venezuela` ADD COLUMN `precio_dolar` DECIMAL(18,4) NOT NULL DEFAULT 0 AFTER `frec`'
            );
        }
        if (!$this->db->field_exists('fecha_tasa', 't_archivos_venezuela')) {
            $this->db->query(
                'ALTER TABLE `t_archivos_venezuela` ADD COLUMN `fecha_tasa` DATE NULL AFTER `precio_dolar`'
            );
        }
        // Regla de oro (igual que Banfan/Bicentenario): Regular solo abona la
        // cuota del mes de cobro actual; Moroso busca primero cuotas vencidas
        // (meses anteriores sin pagar) antes de caer en la del mes actual. Se
        // elige al subir el archivo, aplica a todas sus filas.
        if (!$this->db->field_exists('tipo_cobranza', 't_archivos_venezuela')) {
            $this->db->query(
                'ALTER TABLE `t_archivos_venezuela` ADD COLUMN `tipo_cobranza` VARCHAR(16) NOT NULL DEFAULT \'REGULAR\' AFTER `perio`'
            );
        }
        if ($this->db->table_exists('t_cargar_txt_venezuela')
            && !$this->db->field_exists('monto_bs', 't_cargar_txt_venezuela')) {
            $this->db->query(
                'ALTER TABLE `t_cargar_txt_venezuela` ADD COLUMN `monto_bs` DECIMAL(18,4) NOT NULL DEFAULT 0 AFTER `monto`'
            );
        }
        // BCV si el banco no cobró el 100% de lo enviado (columna "montoTotal"
        // del XLSX vs. "montoCobrado"), Cobranza si sí se cobró completo —
        // misma regla ya usada en Banfan/Bicentenario, aplicada por fila.
        if ($this->db->table_exists('t_cargar_txt_venezuela')
            && !$this->db->field_exists('tipo_tasa', 't_cargar_txt_venezuela')) {
            $this->db->query(
                'ALTER TABLE `t_cargar_txt_venezuela` ADD COLUMN `tipo_tasa` VARCHAR(16) NOT NULL DEFAULT \'BCV\' AFTER `monto_bs`'
            );
        }
        // Fecha a usar para BUSCAR la tasa: si es Cobranza, la fecha de
        // ENVÍO del lote (fenv) — para que todos los que pagaron completo
        // en un mismo envío queden alineados a una sola tasa, sin importar
        // el día en que el banco procesó a cada uno. Si es BCV, la fecha
        // real de cobro (fpro). Distinta de "fpro", que sigue mostrando el
        // día real de proceso de cada cliente (columna F.Pro en pantalla).
        if ($this->db->table_exists('t_cargar_txt_venezuela')
            && !$this->db->field_exists('fecha_tasa', 't_cargar_txt_venezuela')) {
            $this->db->query(
                'ALTER TABLE `t_cargar_txt_venezuela` ADD COLUMN `fecha_tasa` DATE NULL AFTER `tipo_tasa`'
            );
        }
    }

    /**
     * @param array $datos archivo, empresa, fcontrato, perio, fenv, frec, precio_dolar?, fecha_tasa?
     */
    public function registrar($datos) {
        // precio_dolar/fecha_tasa aquí son solo metadato informativo del
        // registro del archivo (fenv ya no lo elige el usuario, es la fecha
        // de subida) — la tasa real que importa se resuelve por FILA con su
        // propia fecha de cobro, así que no hay que bloquear la subida si
        // hoy no tiene tasa BCV registrada.
        if (!isset($datos['precio_dolar']) || (float) $datos['precio_dolar'] <= 0) {
            $tasa = $this->resolverTasaPorFecha(isset($datos['fenv']) ? $datos['fenv'] : '');
            $datos['precio_dolar'] = $tasa ? $tasa['precio'] : 0;
            $datos['fecha_tasa'] = $tasa ? $tasa['fecha'] : null;
        }
        $res = $this->db->insert('t_archivos_venezuela', $datos);
        if (!$res) {
            return false;
        }
        return $this->db->insert_id();
    }

    /**
     * Registra un archivo nuevo o reemplaza uno pendiente (estatus=0) con el mismo nombre.
     *
     * @param array $datos
     * @return array ['ok'=>bool,'id'=>int,'reemplazo'=>bool,'msg'=>string]
     */
    public function registrarOReemplazar($datos) {
        $nombre = isset($datos['archivo']) ? trim($datos['archivo']) : '';
        if ($nombre === '') {
            return array('ok' => false, 'id' => 0, 'reemplazo' => false, 'msg' => 'Nombre de archivo inválido.');
        }

        $existente = $this->db->query(
            'SELECT oid, estatus FROM t_archivos_venezuela WHERE archivo='
            . $this->db->escape($nombre) . ' ORDER BY oid DESC LIMIT 1'
        );
        if ($existente->num_rows() > 0) {
            $row = $existente->row();
            if ((int) $row->estatus === 1) {
                return array(
                    'ok' => false,
                    'id' => 0,
                    'reemplazo' => false,
                    'msg' => 'No Se Pudo Procesar. El Archivo Ya Fue Procesado',
                );
            }

            if (!isset($datos['precio_dolar']) || (float) $datos['precio_dolar'] <= 0) {
                $tasa = $this->resolverTasaPorFecha(isset($datos['fenv']) ? $datos['fenv'] : '');
                $datos['precio_dolar'] = $tasa ? $tasa['precio'] : 0;
                $datos['fecha_tasa'] = $tasa ? $tasa['fecha'] : null;
            }

            $oid = (int) $row->oid;
            unset($datos['archivo']);
            $datos['monto'] = 0;
            $datos['estatus'] = 0;
            $this->db->where('oid', $oid);
            $ok = $this->db->update('t_archivos_venezuela', $datos);
            if (!$ok) {
                return array('ok' => false, 'id' => 0, 'reemplazo' => false, 'msg' => 'No se pudo actualizar el archivo pendiente.');
            }
            $this->db->query('DELETE FROM t_cargar_txt_venezuela WHERE oida=' . $oid);

            return array('ok' => true, 'id' => $oid, 'reemplazo' => true, 'msg' => '');
        }

        $id = $this->registrar($datos);
        if (!$id) {
            return array('ok' => false, 'id' => 0, 'reemplazo' => false, 'msg' => 'No se pudo registrar el archivo.');
        }

        return array('ok' => true, 'id' => (int) $id, 'reemplazo' => false, 'msg' => '');
    }

    /**
     * @param string $tipo 'BCV' | 'COBRANZA' (MPrecio_dolar::TIPO_BCV | TIPO_COBRANZA)
     * @return array|null ['precio'=>float,'fecha'=>'Y-m-d']
     */
    public function resolverTasaPorFecha($fecha, $tipo = 'BCV') {
        $CI =& get_instance();
        $CI->load->model('mprecio_dolar', 'MPrecio_dolar');
        // Este archivo es exclusivo del flujo Venezuela — su propia tasa,
        // con respaldo automático a General si no la tiene registrada.
        $row = $CI->MPrecio_dolar->getPrecioPorFecha($fecha, $tipo, 'VENEZUELA');
        if (!$row || (float) $row->precio <= 0) {
            return null;
        }
        return array(
            'precio' => (float) $row->precio,
            'fecha' => $row->fecha,
        );
    }

    public function getArchivo($oida) {
        $q = $this->db->get_where('t_archivos_venezuela', array('oid' => (int) $oida));
        return $q->num_rows() > 0 ? $q->row() : null;
    }

    public function leerArchivo($oida, $ruta) {
        $detalle = $this->leerArchivoDetalle($oida, $ruta);
        return $detalle['ok'];
    }

    /**
     * Pre-chequeo de tasas BCV para un archivo ya leído (staging en
     * t_cargar_txt_venezuela): cada fila puede convertir Bs.<->USD con la
     * tasa de SU PROPIA fecha de cobro (fpro), a diferencia de Banfan/
     * Bicentenario que usan una sola tasa para todo el archivo. Se llama
     * apenas se lee el archivo — antes de que el operador invierta tiempo
     * asignando contratos a mano — para no descubrir la falta de tasa recién
     * al "Guardar Lote".
     *
     * @return array de "fecha (tipo)" sin esa tasa registrada, sin duplicados
     */
    public function tasasFaltantes($oida) {
        $rs = $this->db->query(
            'SELECT DISTINCT fpro, tipo_tasa, fecha_tasa FROM t_cargar_txt_venezuela WHERE oida=' . (int) $oida
        );
        if (!$rs || $rs->num_rows() === 0) {
            return array();
        }

        $faltantes = array();
        foreach ($rs->result() as $row) {
            $tipo = $row->tipo_tasa !== '' ? $row->tipo_tasa : 'BCV';
            $fecha = !empty($row->fecha_tasa) ? $row->fecha_tasa : $row->fpro;
            if (!$this->resolverTasaPorFecha($fecha, $tipo)) {
                $faltantes[] = $fecha . ' (' . $tipo . ')';
            }
        }
        $faltantes = array_unique($faltantes);
        sort($faltantes);
        return $faltantes;
    }

    /**
     * @return array ['ok'=>bool,'msg'=>string,'filas'=>int]
     */
    public function leerArchivoDetalle($oida, $ruta) {
        date_default_timezone_set('America/Caracas');
        $oida = (int) $oida;
        $ruta = trim((string) $ruta);
        if ($oida <= 0 || $ruta === '') {
            return array('ok' => false, 'msg' => 'Parámetros inválidos para leer el archivo.', 'filas' => 0);
        }

        $directorio = BASEPATH . 'repository/txt/venezuela/' . $ruta;
        if (!is_readable($directorio)) {
            return array('ok' => false, 'msg' => 'No se encontró el archivo en el repositorio.', 'filas' => 0);
        }

        $ext = strtolower(pathinfo($ruta, PATHINFO_EXTENSION));
        if (in_array($ext, array('xlsx', 'xls'), true)) {
            $filas = $this->parsearXlsxRespuesta($directorio);
            $formato = 'XLSX';
        } elseif ($ext === 'txt') {
            $filas = $this->parsearTxtRespuesta($directorio);
            $formato = 'TXT';
        } else {
            $filas = $this->parsearCsvLegacy($directorio);
            $formato = 'CSV';
        }

        if (empty($filas)) {
            return array(
                'ok' => false,
                'msg' => 'No se encontraron registros válidos en el archivo ' . strtoupper($formato) . '.',
                'filas' => 0,
            );
        }

        if (!$this->persistirFilasLectura($oida, $filas)) {
            $dbError = $this->db->_error_message();
            $msg = 'No se pudo guardar los registros leídos.';
            if ($dbError !== '') {
                $msg .= ' ' . $dbError;
            }
            return array('ok' => false, 'msg' => $msg, 'filas' => 0);
        }

        return array(
            'ok' => true,
            'msg' => 'Archivo leído correctamente.',
            'filas' => count($filas),
        );
    }

    /**
     * Formato antiguo CSV: líneas 02 con RG010/RG012.
     *
     * @return array
     */
    private function parsearCsvLegacy($directorio) {
        $archivo = fopen($directorio, 'r');
        if (!$archivo) {
            return array();
        }

        $filas = array();
        while (!feof($archivo)) {
            $linea = fgets($archivo);
            if (substr($linea, 0, 2) !== '02') {
                continue;
            }
            $picar = explode(';', $linea);
            if (count($picar) < 25) {
                continue;
            }
            if ($picar[21] !== 'RG010' && $picar[21] !== 'RG012') {
                continue;
            }

            $ced = substr($picar[1], 1, -1);
            $montoBs = $this->parseMontoCentavos($picar[24]);
            $filas[] = array(
                'cedula' => (int) $ced,
                'fenv' => $this->parseFechaVe($picar[17]),
                'fpro' => $this->parseFechaVe($picar[23]),
                'monto_bs' => $montoBs,
                'obser' => trim($picar[21]),
            );
        }
        fclose($archivo);
        return $filas;
    }

    /**
     * Formato respuesta TXT (campos separados por espacios múltiples).
     *
     * @return array
     */
    private function parsearTxtRespuesta($directorio) {
        $contenido = file_get_contents($directorio);
        if ($contenido === false) {
            return array();
        }
        $contenido = preg_replace('/^\xEF\xBB\xBF/', '', $contenido);
        $lineas = preg_split('/\r\n|\n|\r/', trim($contenido));
        $filas = array();

        foreach ($lineas as $linea) {
            $linea = trim($linea);
            if ($linea === '') {
                continue;
            }
            $partes = preg_split('/\s{2,}/', $linea);
            if (count($partes) < 6) {
                continue;
            }

            $cedula = $this->extraerCedulaDesdeBloque(isset($partes[1]) ? $partes[1] : '');
            if ($cedula <= 0) {
                continue;
            }

            $montoCobrado = $this->parseMontoVe(isset($partes[5]) ? $partes[5] : '0');
            $fechaValor = $this->parseFechaVe(isset($partes[3]) ? $partes[3] : '');
            $fechaCobro = $this->parseFechaVe(isset($partes[4]) ? $partes[4] : '');
            $obser = $this->construirObser(
                isset($partes[7]) ? $partes[7] : '',
                '',
                '',
                isset($partes[6]) ? $partes[6] : ''
            );

            $filas[] = array(
                'cedula' => $cedula,
                'fenv' => $fechaValor,
                'fpro' => $fechaCobro !== '' ? $fechaCobro : $fechaValor,
                'monto_bs' => $montoCobrado,
                'obser' => $obser,
            );
        }

        return $filas;
    }

    /**
     * Formato respuesta XLSX: hoja 2 con encabezados del portal BDV.
     *
     * @return array
     */
    private function parsearXlsxRespuesta($directorio) {
        $this->cargarPhpExcel();
        try {
            $ext = strtolower(pathinfo($directorio, PATHINFO_EXTENSION));
            $tipo = ($ext === 'xls') ? 'Excel5' : 'Excel2007';
            $lector = PHPExcel_IOFactory::createReader($tipo);
            $lector->setReadDataOnly(true);
            $libro = $lector->load($directorio);
        } catch (Exception $e) {
            error_log('[VE XLSX] error al abrir archivo: ' . $e->getMessage());
            return array();
        }

        $hoja = $libro->getSheetCount() > 1 ? $libro->getSheet(1) : $libro->getActiveSheet();
        $ultimaFila = (int) $hoja->getHighestRow();
        $ultimaCol = $hoja->getHighestColumn();
        if ($ultimaFila < 2) {
            error_log('[VE XLSX] hojas=' . $libro->getSheetCount() . ' ultimaFila=' . $ultimaFila . ' ultimaCol=' . $ultimaCol);
            return array();
        }

        $mapa = array();
        foreach (range('A', $ultimaCol) as $col) {
            $nombre = strtolower(trim((string) $hoja->getCell($col . '1')->getValue()));
            if ($nombre !== '') {
                $mapa[$nombre] = $col;
            }
        }

        if (!isset($mapa['codcliente']) && !isset($mapa['cedularif'])) {
            error_log('[VE XLSX] hojas=' . $libro->getSheetCount() . ' ultimaFila=' . $ultimaFila
                . ' ultimaCol=' . $ultimaCol . ' encabezados=' . implode(',', array_keys($mapa)));
            return array();
        }

        $filas = array();
        for ($fila = 2; $fila <= $ultimaFila; $fila++) {
            $cedulaRif = isset($mapa['cedularif'])
                ? (string) $hoja->getCell($mapa['cedularif'] . $fila)->getValue()
                : '';
            $cedula = $this->resolverCedulaFilaXlsx($hoja, $mapa, $fila, $cedulaRif);
            if ($cedula <= 0) {
                continue;
            }

            $colMonto = isset($mapa['montocobrado']) ? $mapa['montocobrado'] : null;
            $montoCobrado = $colMonto
                ? $this->parseMontoVe((string) $hoja->getCell($colMonto . $fila)->getValue())
                : 0.0;

            // Columna "montoTotal": lo que se ENVIÓ a cobrar. Si el banco
            // cobró exactamente eso, se aplica Dólar Cobranza; si cobró
            // menos (o nada), Dólar BCV — misma regla de Banfan/Bicentenario
            // (allá el criterio es "(POR DISPONIBLE)"; aquí es esta
            // comparación porque el archivo de Venezuela no trae ese motivo).
            $colMontoTotal = isset($mapa['montototal']) ? $mapa['montototal'] : null;
            $montoTotal = $colMontoTotal
                ? $this->parseMontoVe((string) $hoja->getCell($colMontoTotal . $fila)->getValue())
                : 0.0;
            $tipoTasa = ($montoTotal > 0 && round($montoCobrado, 2) >= round($montoTotal, 2))
                ? 'COBRANZA' : 'BCV';

            $fechaValor = isset($mapa['fechavalor'])
                ? $this->parseFechaVe((string) $hoja->getCell($mapa['fechavalor'] . $fila)->getValue())
                : '';
            $fechaCobro = isset($mapa['fechacobro'])
                ? $this->parseFechaVe((string) $hoja->getCell($mapa['fechacobro'] . $fila)->getValue())
                : '';

            // Fecha a usar para BUSCAR la tasa (distinta de F.Pro que se
            // muestra en pantalla): si se cobró completo (Cobranza), se
            // busca con la fecha de ENVÍO del lote (fechaValor) — así todos
            // los clientes de un mismo envío que sí pagaron completo quedan
            // alineados a la misma tasa, sin importar qué día procesó el
            // banco a cada uno. Si fue parcial (BCV), se busca con la fecha
            // real en que el banco cobró/procesó (fechaCobro).
            $fechaParaTasa = ($tipoTasa === 'COBRANZA')
                ? $fechaValor
                : ($fechaCobro !== '' ? $fechaCobro : $fechaValor);

            $status = isset($mapa['status'])
                ? trim((string) $hoja->getCell($mapa['status'] . $fila)->getValue())
                : '';
            $statusDetail = isset($mapa['statusdetail'])
                ? trim((string) $hoja->getCell($mapa['statusdetail'] . $fila)->getValue())
                : '';
            $mensaje = isset($mapa['msensajesdetail'])
                ? trim((string) $hoja->getCell($mapa['msensajesdetail'] . $fila)->getValue())
                : '';
            $montoPendiente = isset($mapa['montopendiente'])
                ? trim((string) $hoja->getCell($mapa['montopendiente'] . $fila)->getValue())
                : '';

            $filas[] = array(
                'cedula' => $cedula,
                'fenv' => $fechaValor,
                'fpro' => $fechaCobro !== '' ? $fechaCobro : $fechaValor,
                'monto_bs' => $montoCobrado,
                'tipo_tasa' => $tipoTasa,
                'fecha_tasa' => $fechaParaTasa,
                'obser' => $this->construirObser($status, $statusDetail, $mensaje, $montoPendiente),
            );
        }

        return $filas;
    }

    /**
     * @param array $filas
     * @return bool
     */
    private function persistirFilasLectura($oida, $filas) {
        if (empty($filas)) {
            return false;
        }

        $arch = $this->getArchivo($oida);
        $frecArchivo = ($arch && !empty($arch->frec) && $arch->frec !== '0000-00-00')
            ? $arch->frec : '';
        $fenvArchivo = ($arch && !empty($arch->fenv) && $arch->fenv !== '0000-00-00')
            ? $arch->fenv : '';

        $this->db->query('DELETE FROM t_cargar_txt_venezuela WHERE oida=' . (int) $oida);

        $montoTotalBs = 0.0;
        $bloques = array();

        foreach ($filas as $fila) {
            $montoBs = (float) $fila['monto_bs'];
            $montoTotalBs += $montoBs;
            $fenv = !empty($fila['fenv']) ? $fila['fenv'] : $fenvArchivo;
            $fpro = !empty($fila['fpro']) ? $fila['fpro'] : $fenv;
            // "Mes a Cargar" (frec) debe ser la fecha real de cobro de ESTA
            // fila (fpro) — nunca la fecha del archivo (fenv/frec a nivel de
            // archivo ya no la elige el usuario, es solo la fecha de subida,
            // y no tiene relación con el mes que realmente se cobró).
            $frec = $fpro !== '' ? $fpro : $frecArchivo;
            if ($fenv === '' || $fpro === '' || $frec === '') {
                return false;
            }

            // Los parsers legados (TXT/CSV) no calculan tipo_tasa (esos
            // formatos no traen "montoTotal" para comparar); se quedan en
            // BCV, el comportamiento de siempre para ellos.
            $tipoTasa = isset($fila['tipo_tasa']) && $fila['tipo_tasa'] === 'COBRANZA' ? 'COBRANZA' : 'BCV';
            $fechaTasa = !empty($fila['fecha_tasa']) ? $fila['fecha_tasa'] : $fpro;

            $bloques[] = '(' . (int) $oida . ','
                . (int) $fila['cedula'] . ','
                . $this->db->escape('') . ','
                . $this->db->escape($fenv) . ','
                . $this->db->escape($frec) . ','
                . $this->db->escape($fpro) . ','
                . $this->db->escape($montoBs) . ','
                . $this->db->escape($montoBs) . ','
                . $this->db->escape($tipoTasa) . ','
                . $this->db->escape($fechaTasa) . ','
                . $this->db->escape(isset($fila['obser']) ? $fila['obser'] : '') . ')';
        }

        $consulta = 'INSERT INTO t_cargar_txt_venezuela(oida,cedula,contrato,fenv,frec,fpro,monto,monto_bs,tipo_tasa,fecha_tasa,obser) VALUES '
            . implode(',', $bloques);

        $resp = $this->db->query($consulta);
        if ($resp) {
            $this->db->query(
                'UPDATE t_archivos_venezuela SET monto=' . (float) $montoTotalBs
                . ' WHERE oid=' . (int) $oida
            );
            $this->autoAsignarContratosUnicos($oida);
            $this->distribuirEntreContratosMultiples($oida);
        }
        return $resp;
    }

    private function cargarPhpExcel() {
        if (!class_exists('PHPExcel_IOFactory', false)) {
            require_once BASEPATH . 'application/libraries/include.xls.inc.php';
        }
    }

    /**
     * Trunca a 2 decimales sin redondear (38.493 => 38.49, 38.499 => 38.49).
     * El round() intermedio solo corrige el error de precisión de punto
     * flotante (ej. 38.495*100 podría dar 3849.4999999...) antes de truncar.
     */
    private function truncar2($valor) {
        return floor(round((float) $valor * 100, 6)) / 100;
    }

    private function parseMontoCentavos($valor) {
        $valor = trim((string) $valor);
        if ($valor === '') {
            return 0.0;
        }
        $monto1 = intval(substr($valor, 0, -2));
        $decimal = substr($valor, -2);
        return (float) ($monto1 . '.' . $decimal);
    }

    private function parseMontoVe($valor) {
        $valor = trim((string) $valor);
        if ($valor === '') {
            return 0.0;
        }
        if (preg_match('/^\d+$/', $valor)) {
            $centavos = (int) $valor;
            return round($centavos / 100, 2);
        }
        $normalizado = str_replace('.', '', $valor);
        $normalizado = str_replace(',', '.', $normalizado);
        return (float) $normalizado;
    }

    private function parseFechaVe($valor) {
        $valor = trim(str_replace('/', '-', (string) $valor));
        if ($valor === '') {
            return '';
        }
        $partes = explode('-', $valor);
        if (count($partes) === 3 && strlen($partes[2]) === 4) {
            return sprintf('%04d-%02d-%02d', (int) $partes[2], (int) $partes[1], (int) $partes[0]);
        }
        $ts = strtotime($valor);
        return $ts ? date('Y-m-d', $ts) : '';
    }

    private function extraerCedulaDesdeBloque($bloque) {
        $bloque = trim((string) $bloque);
        if ($bloque === '') {
            return 0;
        }

        // Formato BDV respuesta: V27919877 + referencia + codCliente interno del banco
        $cedula = $this->extraerCedulaRif($bloque);
        if ($cedula > 0) {
            return $cedula;
        }

        if (preg_match('/(\d{6,9})/', $bloque, $m)) {
            return (int) $m[1];
        }
        return 0;
    }

    /**
     * Extrae documento_id desde cedulaRif del banco.
     * V02993003 → 2993003 (sin cero inicial, como en t_clientes_creditos).
     * V27919877 → 27919877
     */
    private function extraerCedulaRif($valor) {
        $valor = trim((string) $valor);
        if ($valor === '') {
            return 0;
        }
        if (!preg_match('/^[VEJPGvejpg][\-]?(.+)$/i', $valor, $prefix)) {
            return 0;
        }

        $resto = $prefix[1];

        // Bloque largo BDV: V + 8 dígitos (cédula con ceros) + referencia + codCliente...
        if (strlen($resto) >= 20 && preg_match('/^(\d{8})/', $resto, $m)) {
            return $this->normalizarDocumentoId($m[1]);
        }

        // Campo corto cedulaRif: V02993003, V27919877
        if (preg_match('/^(\d+)/', $resto, $m)) {
            return $this->normalizarDocumentoId($m[1]);
        }

        return 0;
    }

    /**
     * Convierte dígitos del banco al documento_id del sistema (sin ceros a la izquierda).
     */
    private function normalizarDocumentoId($digitos) {
        $digitos = preg_replace('/\D/', '', (string) $digitos);
        if ($digitos === '') {
            return 0;
        }
        $cedula = (int) $digitos;
        if ($cedula <= 0) {
            return 0;
        }
        return ($cedula >= 10000 && $cedula <= 999999999) ? $cedula : 0;
    }

    private function resolverCedulaFilaXlsx($hoja, $mapa, $fila, $cedulaRif) {
        $cedula = $this->extraerCedulaRif($cedulaRif);
        if ($cedula > 0) {
            return $cedula;
        }

        if (isset($mapa['cedularif'])) {
            $valor = (string) $hoja->getCell($mapa['cedularif'] . $fila)->getValue();
            $cedula = $this->extraerCedulaRif($valor);
            if ($cedula > 0) {
                return $cedula;
            }
        }

        return 0;
    }

    private function extraerCedulaCodCliente($codCliente) {
        $codCliente = trim((string) $codCliente);
        if ($codCliente === '') {
            return 0;
        }
        if ($codCliente[0] === 'V' || $codCliente[0] === 'E' || $codCliente[0] === 'J') {
            return 0;
        }
        $codCliente = preg_replace('/\D/', '', $codCliente);
        $codCliente = preg_replace('/0+$/', '', $codCliente);
        if ($codCliente === '') {
            return 0;
        }
        return (int) $codCliente;
    }

    /**
     * FECHA CUOTA: día registrado en el contrato (fecha_inicio_cobro),
     * combinado con el mes/año que se está cobrando — NO el día real en que
     * el banco procesó el cobro (eso es FECHA DESCUENTO/farc, que sí queda
     * tal cual). Puerto idéntico de
     * MRespuestaBanco::construirFechaCuota()/obtenerDiasCobroPorContrato()
     * (Banfan/Bicentenario), sin tocar ese archivo, para que Venezuela
     * también guarde siempre el mismo día de cuota mes a mes, sin importar
     * qué día exacto cobró el banco.
     */
    private function construirFechaCuota($diaCobro, $anio, $mes, $fechaRespaldo) {
        if (!$diaCobro || $anio <= 0 || $mes <= 0) {
            return $fechaRespaldo;
        }
        $ultimoDia = (int) date('t', mktime(0, 0, 0, $mes, 1, $anio));
        $dia = min($diaCobro, $ultimoDia);
        return sprintf('%04d-%02d-%02d', $anio, $mes, $dia);
    }

    private function obtenerDiasCobroPorContrato(array $creditoIds) {
        if (empty($creditoIds)) {
            return array();
        }

        $lista = array();
        foreach (array_unique($creditoIds) as $c) {
            $lista[] = $this->db->escape($c);
        }
        $con = $this->db->query(
            'SELECT contrato_id, fecha_inicio_cobro FROM t_clientes_creditos'
            . ' WHERE contrato_id IN (' . implode(',', $lista) . ')'
        );

        $dias = array();
        foreach ($con->result() as $fila) {
            $fic = trim((string) $fila->fecha_inicio_cobro);
            if ($fic !== '' && $fic !== '0000-00-00') {
                $dias[$fila->contrato_id] = (int) date('j', strtotime($fic));
            }
        }
        return $dias;
    }

    private function construirDescripcionCobro($montoBs, $montoUsd, $tasa, $obser) {
        if ($montoBs > 0) {
            $desc = 'COBRO Bs ' . number_format($montoBs, 2, '.', '')
                . ' @ ' . number_format($tasa, 4, '.', '') . ' USD';
            if ($obser !== '') {
                $desc .= ' | ' . $obser;
            }
            return $desc;
        }

        $desc = $obser !== '' ? $obser : 'SIN COBRO BDV';
        if (strlen($desc) > 255) {
            $desc = substr($desc, 0, 252) . '...';
        }
        return $desc;
    }

    private function construirObser($status, $statusDetail, $mensaje, $montoPendiente) {
        $partes = array();
        if (trim((string) $status) !== '') {
            $partes[] = trim($status);
        }
        if (trim((string) $statusDetail) !== '') {
            $partes[] = trim($statusDetail);
        }
        if (trim((string) $mensaje) !== '') {
            $partes[] = trim($mensaje);
        }
        if (trim((string) $montoPendiente) !== '' && $this->parseMontoVe($montoPendiente) > 0) {
            $partes[] = 'Pendiente: ' . trim($montoPendiente);
        }
        return implode(' | ', $partes);
    }

    /**
     * Si la cédula tiene un solo contrato VENEZUELA, asignarlo automáticamente.
     */
    /**
     * Dos pasadas — mismo criterio ya validado en
     * MRespuestaBanco::autoAsignarContratosUnicos() (Banfan/Bicentenario),
     * portado aquí sin tocar ese archivo:
     *   1) preferir el contrato registrado bajo VENEZUELA si la cédula tiene
     *      exactamente uno con saldo pendiente.
     *   2) para las que sigan sin asignar (el cliente no tiene ningún
     *      contrato bajo VENEZUELA — puede seguir marcado con otro banco,
     *      ej. COTERO, y estar cobrándose en la práctica por domiciliación
     *      Venezuela sin que ese campo se actualice), buscar en TODOS sus
     *      contratos de cualquier banco y asignar solo si tiene exactamente
     *      uno elegible.
     */
    private function autoAsignarContratosUnicos($oida) {
        $arch = $this->getArchivo($oida);
        $periocidad = $arch && isset($arch->perio) ? $arch->perio : 4;
        $condPeriocidad = $this->condicionPeriocidad($periocidad);

        $sqlVenezuela = 'UPDATE t_cargar_txt_venezuela v
            INNER JOIN (
                SELECT documento_id, MIN(contrato_id) AS contrato_id
                FROM (
                    SELECT c.documento_id, c.contrato_id,
                        (c.monto_total - IFNULL(SUM(lc.monto), 0)) AS resta
                    FROM t_clientes_creditos c
                    LEFT JOIN t_lista_cobros lc ON lc.credito_id = c.contrato_id
                    WHERE c.cobrado_en = \'VENEZUELA\'
                    AND c.marca_consulta != 6
                    AND c.cantidad != 0
                    AND c.estatus != 3
                    AND ' . $condPeriocidad . '
                    GROUP BY c.documento_id, c.contrato_id, c.monto_total
                    HAVING resta > 0
                ) activos
                GROUP BY documento_id
                HAVING COUNT(*) = 1
            ) u ON u.documento_id = v.cedula
            SET v.contrato = u.contrato_id
            WHERE v.oida = ' . (int) $oida . '
            AND (v.contrato IS NULL OR v.contrato = \'\')';
        $this->db->query($sqlVenezuela);

        $sqlCualquiera = 'UPDATE t_cargar_txt_venezuela v
            INNER JOIN (
                SELECT documento_id, MIN(contrato_id) AS contrato_id
                FROM (
                    SELECT c.documento_id, c.contrato_id,
                        (c.monto_total - IFNULL(SUM(lc.monto), 0)) AS resta
                    FROM t_clientes_creditos c
                    LEFT JOIN t_lista_cobros lc ON lc.credito_id = c.contrato_id
                    WHERE c.marca_consulta != 6
                    AND c.cantidad != 0
                    AND c.estatus != 3
                    AND ' . $condPeriocidad . '
                    GROUP BY c.documento_id, c.contrato_id, c.monto_total
                    HAVING resta > 0
                ) activos
                GROUP BY documento_id
                HAVING COUNT(*) = 1
            ) u ON u.documento_id = v.cedula
            SET v.contrato = u.contrato_id
            WHERE v.oida = ' . (int) $oida . '
            AND (v.contrato IS NULL OR v.contrato = \'\')';
        $this->db->query($sqlCualquiera);
    }

    /**
     * Condición WHERE para `c.periocidad`. Puerto idéntico de
     * GtxtGenerator::condicionPeriocidad() (Crear_Txt), sin tocar ese
     * archivo: casi ningún contrato usa el código específico "Mensual -
     * día 15/25/30" (5/9/6) al crearse — el operador elige el genérico
     * "MENSUAL" (4) sin importar el día real de cobro, que solo queda
     * registrado en fecha_inicio_cobro. Se resuelve también por ese día
     * real para no dejar fuera esos contratos.
     */
    private function condicionPeriocidad($periodicidad) {
        if ((string) $periodicidad === '5') {
            return '((c.periocidad = 5) OR (c.periocidad = 4 AND DAY(c.fecha_inicio_cobro) = 15))';
        }
        if ((string) $periodicidad === '9') {
            return '((c.periocidad = 9) OR (c.periocidad = 4 AND DAY(c.fecha_inicio_cobro) = 25))';
        }
        if ((string) $periodicidad === '6') {
            return '((c.periocidad = 6) OR (c.periocidad = 4 AND DAY(c.fecha_inicio_cobro) = 30))';
        }
        if ((string) $periodicidad === '4') {
            return '(c.periocidad = 4 AND DAY(c.fecha_inicio_cobro) NOT IN (15, 25, 30))';
        }
        return 'c.periocidad = ' . (int) $periodicidad;
    }

    /**
     * Para clientes con 2+ contratos con saldo pendiente (quedaron sin
     * asignar tras autoAsignarContratosUnicos), reparte el cobro en cascada
     * usando el mismo motor y la misma regla que "Guardar Lote"
     * (MCascada::distribuirPagoMultiple() — REGULAR: solo la cuota del mes
     * que se está cobrando; MOROSO: primero lo vencido de meses anteriores,
     * cayendo a la cuota actual si no debe nada atrasado — siempre contrato
     * más viejo y ACTIVO primero, el sobrante pasa al siguiente). Antes esta
     * función usaba el SALDO TOTAL de cada contrato como "capacidad", lo que
     * hacía que un solo pago llenara de más un contrato (varias cuotas de
     * una vez) antes de saltar al siguiente, en vez de una cuota a la vez.
     *
     * Se usa distribuirPagoMultiple() (no distribuirPago() por fila) porque
     * varias filas de esta MISMA cédula, en esta MISMA lectura, deben
     * compartir un solo objetivo por contrato: la BD (t_lista_cobros) no se
     * actualiza sino hasta "Guardar Lote", así que si cada fila consultara
     * por su cuenta, la segunda no vería lo que la primera ya le asignó al
     * contrato más viejo y se lo volvería a llenar en vez de pasar al
     * siguiente.
     *
     * La fila original se actualiza con la primera porción; las porciones
     * adicionales se insertan como filas nuevas del mismo archivo/cliente.
     */
    private function distribuirEntreContratosMultiples($oida) {
        $oida = (int) $oida;
        $arch = $this->getArchivo($oida);
        $periocidad = $arch && isset($arch->perio) ? $arch->perio : 4;
        $esMoroso = $arch && isset($arch->tipo_cobranza) && strtoupper(trim($arch->tipo_cobranza)) === 'MOROSO';
        $modoCascada = $esMoroso ? 'MOROSO' : 'REGULAR';

        $filas = $this->db->query('SELECT oid, cedula, fenv, frec, fpro, monto_bs, tipo_tasa, fecha_tasa, obser
            FROM t_cargar_txt_venezuela
            WHERE oida=' . $oida . " AND (contrato IS NULL OR contrato = '')
            ORDER BY cedula, oid")->result();

        $porCedula = array();
        foreach ($filas as $fila) {
            $porCedula[$fila->cedula][] = $fila;
        }

        $this->load->model('cargar/mcascadapagos', 'MCascada');
        $CI =& get_instance();

        foreach ($porCedula as $cedula => $filasCliente) {
            // Universo de contratos "con saldo" de la cédula, para decidir si
            // hay 2+ elegibles (igual que antes) y para filtrar los que NO
            // corresponden a la periocidad de este archivo (un contrato que
            // se cobra el día 25, por ejemplo, no debe recibir dinero de un
            // archivo armado para el grupo del día 15).
            $todos = $this->contratosConSaldoOrdenados($cedula, null);
            $elegibles = $this->contratosConSaldoOrdenados($cedula, $periocidad);
            if (count($elegibles) < 2) {
                // Único contrato ya lo resolvió autoAsignarContratosUnicos;
                // cero contratos se queda "Por Asignar" (no hay a quién).
                continue;
            }
            $idsElegibles = array();
            foreach ($elegibles as $c) {
                $idsElegibles[$c->contrato_id] = true;
            }
            $excluir = array();
            foreach ($todos as $c) {
                if (!isset($idsElegibles[$c->contrato_id])) {
                    $excluir[] = $c->contrato_id;
                }
            }

            // Primero se resuelve la tasa de cada fila (necesaria para
            // convertir su monto Bs. a USD, la moneda en la que trabaja
            // MCascada) y se arma la lista de pagos en el ORDEN en que deben
            // repartirse, compartiendo un solo objetivo por contrato.
            $pagos = array();
            $filasValidas = array();
            foreach ($filasCliente as $fila) {
                $tipoTasaFila = $fila->tipo_tasa !== '' ? $fila->tipo_tasa : 'BCV';
                $fechaTasaFila = !empty($fila->fecha_tasa) ? $fila->fecha_tasa : $fila->fpro;
                $tasaFila = $this->resolverTasaPorFecha($fechaTasaFila, $tipoTasaFila);
                if (!$tasaFila) {
                    continue;
                }
                $tasa = $tasaFila['precio'];
                $montoUsd = $this->truncar2((float) $fila->monto_bs / $tasa);

                $filasValidas[] = array('fila' => $fila, 'tasa' => $tasa);
                $pagos[] = array('monto_usd' => $montoUsd, 'fecha' => $fila->fpro);
            }
            if (empty($pagos)) {
                continue;
            }

            $resultados = $CI->MCascada->distribuirPagoMultiple(
                $cedula, $pagos, $modoCascada, '', $excluir
            );

            foreach ($filasValidas as $idx => $item) {
                $fila = $item['fila'];
                $tasa = $item['tasa'];
                $asigMCascada = isset($resultados[$idx]) ? $resultados[$idx]['asignaciones'] : array();
                if (empty($asigMCascada)) {
                    continue;
                }
                $sobranteUsd = isset($resultados[$idx]['sobrante']) ? (float) $resultados[$idx]['sobrante'] : 0.0;

                // Convierte de vuelta las asignaciones (en USD) a Bs., para
                // conservar el mismo formato de columnas que usa esta tabla
                // de staging (monto/monto_bs en Bs.).
                $asignaciones = array();
                foreach ($asigMCascada as $asig) {
                    $asignaciones[] = array(
                        'contrato' => $asig['contrato_id'],
                        'monto_bs' => round($asig['monto_usd'] * $tasa, 2),
                        'esSobrante' => false,
                    );
                }
                // Lo que la cascada no pudo aplicar a ningún contrato (todos
                // los elegibles ya cubrieron su objetivo del mes) NO debe
                // desaparecer: se deja como fila "Por Asignar" con el sobrante,
                // para que quede visible y se pueda asignar manualmente.
                if ($sobranteUsd > 0) {
                    $asignaciones[] = array(
                        'contrato' => '',
                        'monto_bs' => round($sobranteUsd * $tasa, 2),
                        'esSobrante' => true,
                    );
                }

                $tipoTasaFila = $fila->tipo_tasa !== '' ? $fila->tipo_tasa : 'BCV';
                $fechaTasaFila = !empty($fila->fecha_tasa) ? $fila->fecha_tasa : $fila->fpro;

                $primero = $asignaciones[0];
                $this->db->query('UPDATE t_cargar_txt_venezuela'
                    . ' SET contrato=' . $this->db->escape($primero['contrato'])
                    . ', monto_bs=' . $this->db->escape($primero['monto_bs'])
                    . ', monto=' . $this->db->escape($primero['monto_bs'])
                    . ' WHERE oid=' . (int) $fila->oid);

                for ($i = 1; $i < count($asignaciones); $i++) {
                    $porcion = $asignaciones[$i];
                    $obserPorcion = $fila->obser;
                    if (!empty($porcion['esSobrante'])) {
                        $obserPorcion = trim($obserPorcion . ' | Sobrante sin aplicar a contrato (excede saldo pendiente)');
                    }
                    $this->db->query('INSERT INTO t_cargar_txt_venezuela'
                        . ' (oida,cedula,contrato,fenv,frec,fpro,monto,monto_bs,tipo_tasa,fecha_tasa,obser) VALUES ('
                        . $oida . ',' . (int) $fila->cedula . ','
                        . $this->db->escape($porcion['contrato']) . ','
                        . $this->db->escape($fila->fenv) . ','
                        . $this->db->escape($fila->frec) . ','
                        . $this->db->escape($fila->fpro) . ','
                        . $this->db->escape($porcion['monto_bs']) . ','
                        . $this->db->escape($porcion['monto_bs']) . ','
                        . $this->db->escape($tipoTasaFila) . ','
                        . $this->db->escape($fechaTasaFila) . ','
                        . $this->db->escape($obserPorcion) . ')');
                }
            }
        }
    }

    /**
     * Contratos con saldo pendiente de un cliente, ordenados del MÁS VIEJO
     * (fecha_solicitud ascendente) al más nuevo — sin filtrar por banco,
     * igual que la asignación manual (crearComboContrato) y que el mismo
     * método en MRespuestaBanco.
     */
    private function contratosConSaldoOrdenados($cedula, $periocidad = null) {
        $cedula = (int) $cedula;
        $condPeriocidad = $periocidad !== null ? (' AND ' . $this->condicionPeriocidad($periocidad)) : '';
        $query = "SELECT c.contrato_id,
                (c.monto_total - IFNULL(SUM(lc.monto), 0)) AS saldo_usd
            FROM t_clientes_creditos c
            LEFT JOIN t_lista_cobros lc ON lc.credito_id = c.contrato_id
            WHERE c.documento_id = {$cedula}
            AND c.marca_consulta != 6 AND c.cantidad != 0 AND c.estatus != 3
            {$condPeriocidad}
            GROUP BY c.contrato_id, c.monto_total, c.fecha_solicitud
            HAVING saldo_usd > 0
            ORDER BY c.fecha_solicitud ASC, c.contrato_id ASC";
        return $this->db->query($query)->result();
    }

    /**
     * Detalle de solo lectura de un archivo Venezuela ya guardado, para
     * "Archivos Procesados" en /cobranza/respuestaBanco. Monto USD se
     * recalcula con la misma tasa por fila que grid() (fecha_tasa + tipo).
     * Los archivos del flujo anterior no conservan filas en
     * t_cargar_txt_venezuela, así que su detalle sale vacío.
     */
    public function gridHistoricoDetalle($oida) {
        $oida = (int) $oida;
        $query = "SELECT v.oid, v.cedula, v.contrato, IF(v.monto_bs > 0, v.monto_bs, v.monto) AS monto_bs,
            v.fenv, v.fpro, v.tipo_tasa, v.fecha_tasa, v.obser,
            TRIM(CONCAT_WS(' ', p.primer_apellido, p.segundo_apellido, p.primer_nombre, p.segundo_nombre)) AS nombre
            FROM t_cargar_txt_venezuela v
            LEFT JOIN t_personas p ON p.documento_id = v.cedula
            WHERE v.oida=" . $oida . ' ORDER BY nombre, v.cedula';

        $oCabezera[1] = array('titulo' => 'oid', 'oculto' => 1);
        $oCabezera[2] = array('titulo' => 'Cédula', 'atributos' => 'width:70px', 'buscar' => 1);
        $oCabezera[3] = array('titulo' => 'Cliente', 'atributos' => 'width:200px', 'buscar' => 1);
        $oCabezera[4] = array('titulo' => 'Contrato', 'atributos' => 'width:90px', 'buscar' => 1);
        $oCabezera[5] = array('titulo' => 'Monto Bs.', 'atributos' => 'width:80px;text-align:center', 'total' => 1);
        $oCabezera[6] = array('titulo' => 'Monto USD', 'atributos' => 'width:80px;text-align:center', 'total' => 1);
        $oCabezera[7] = array('titulo' => 'Tasa', 'atributos' => 'width:90px;text-align:center');
        $oCabezera[8] = array('titulo' => 'F.Env', 'atributos' => 'width:80px');
        $oCabezera[9] = array('titulo' => 'F.Pro', 'atributos' => 'width:80px');
        $oCabezera[10] = array('titulo' => 'Observaciones', 'atributos' => 'width:220px');

        $consulta = $this->db->query($query);
        $i = 0;
        $oFila = array();
        foreach ($consulta->result() as $fila) {
            $montoBs = (float) $fila->monto_bs;
            $tipoTasa = $fila->tipo_tasa !== '' ? $fila->tipo_tasa : 'BCV';
            $fechaTasa = !empty($fila->fecha_tasa) ? $fila->fecha_tasa : $fila->fpro;
            $tasaFila = $this->resolverTasaPorFecha($fechaTasa, $tipoTasa);
            $montoUsd = ($tasaFila && $tasaFila['precio'] > 0) ? $this->truncar2($montoBs / $tasaFila['precio']) : 0;

            $contrato = $fila->contrato;
            if ($contrato === '' || $contrato === null) {
                $contrato = '<font color=\'#1964B5\' size="2"><b>No asignado</b></font>';
            }

            $oFila[++$i] = array(
                '1' => $fila->oid,
                '2' => $fila->cedula,
                '3' => htmlspecialchars((string) $fila->nombre),
                '4' => $contrato,
                '5' => number_format($montoBs, 2, '.', ''),
                '6' => number_format($montoUsd, 2, '.', ''),
                '7' => $tipoTasa . ($tasaFila ? ' ' . number_format($tasaFila['precio'], 4, ',', '.') : ''),
                '8' => $fila->fenv,
                '9' => $fila->fpro,
                '10' => htmlspecialchars($fila->obser),
            );
        }

        if ($i === 0) {
            return json_encode(array(
                'sin_detalle' => 'Este archivo se procesó con el flujo anterior de carga Venezuela, que no guardaba '
                    . 'el detalle por cliente. Los cobros sí se aplicaron, pero no quedó registro de qué filas '
                    . 'vinieron en este archivo.',
            ));
        }

        return json_encode(array(
            'Cabezera' => $oCabezera,
            'Cuerpo' => $oFila,
            'Origen' => 'json',
            'leyenda' => '<br><center><h3>Detalle guardado del archivo Banco de Venezuela</h3></center>',
        ));
    }

    public function crearCombo() {
        $con = $this->db->query('SELECT oid,archivo FROM t_archivos_venezuela WHERE estatus = 0');
        $item = array();
        foreach ($con->result() as $fila) {
            $item[$fila->oid] = $fila->archivo;
        }
        return $item;
    }

    public function crearComboContrato($cedula) {
        $cedula = $this->db->escape($cedula);
        // No se filtra por cobrado_en: un cliente registrado como COTERO (paga
        // personalmente) puede pasar a cobrarse por domiciliación (Venezuela)
        // al caer en mora, sin que ese campo se actualice en su crédito original.
        $query = "SELECT c.contrato_id, c.monto_cuota, c.cobrado_en,
            CASE c.empresa WHEN 0 THEN 'C' WHEN 1 THEN 'G' END AS emp,
            CASE c.forma_contrato WHEN 0 THEN 'U' WHEN 1 THEN 'A' WHEN 2 THEN 'V' ELSE 'O' END AS forma,
            (c.monto_total - IFNULL(SUM(lc.monto), 0)) AS resta
            FROM t_clientes_creditos c
            LEFT JOIN t_lista_cobros lc ON lc.credito_id = c.contrato_id
            WHERE c.documento_id = {$cedula}
            AND c.marca_consulta != 6
            AND c.cantidad != 0
            AND c.estatus != 3
            GROUP BY c.contrato_id, c.monto_cuota, c.empresa, c.forma_contrato, c.cobrado_en, c.monto_total
            HAVING resta > 0
            ORDER BY forma, emp";
        $con = $this->db->query($query);
        $item = array();
        foreach ($con->result() as $fila) {
            $item[$fila->contrato_id] = $fila->contrato_id . '|' . $fila->forma . '|' . $fila->emp . '|'
                . $fila->monto_cuota . '|' . $fila->cobrado_en;
        }
        return json_encode($item);
    }

    public function modCuota($oid, $contrato, $frec, $obser) {
        $this->db->where('oid', (int) $oid);
        return $this->db->update('t_cargar_txt_venezuela', array(
            'contrato' => $contrato,
            'frec' => $frec,
            'obser' => $obser,
        ));
    }

    /**
     * Quita una fila del lote antes de "Guardar Lote" — el cliente
     * simplemente no se guarda con esta carga (no se le aplica ningún
     * cobro). Solo permitido mientras el archivo sigue pendiente
     * (estatus=0); una vez guardado el lote, la fila ya no existe en esta
     * tabla de staging.
     */
    public function eliminarFila($oid) {
        $this->db->query('DELETE FROM t_cargar_txt_venezuela WHERE oid=' . (int) $oid);
        return $this->db->affected_rows() > 0;
    }

    public function grid($oida) {
        $oida = (int) $oida;
        $arch = $this->getArchivo($oida);

        $query = 'SELECT a.oid AS oid, archivo, cedula, contrato,
            IF(a.monto_bs > 0, a.monto_bs, a.monto) AS monto_bs,
            a.fenv AS fenv, a.frec AS frec, a.fpro AS fpro, a.tipo_tasa AS tipo_tasa,
            a.fecha_tasa AS fecha_tasa, obser, oida
            FROM t_cargar_txt_venezuela AS a
            JOIN t_archivos_venezuela AS b ON b.oid = a.oida
            WHERE oida=' . $oida . ' ORDER BY cedula';

        $oCabezera[1] = array('titulo' => 'oid', 'oculto' => 1);
        $oCabezera[2] = array('titulo' => 'Cedula', 'atributos' => 'width:50px', 'buscar' => 1);
        $oCabezera[3] = array('titulo' => 'Contrato', 'atributos' => 'width:80px');
        $oCabezera[4] = array('titulo' => 'Monto Bs.', 'atributos' => 'width:80px;text-align:center', 'total' => 1);
        $oCabezera[5] = array('titulo' => 'Monto USD', 'atributos' => 'width:80px;text-align:center', 'total' => 1);
        $oCabezera[6] = array('titulo' => 'F.Env', 'atributos' => 'width:80px');
        $oCabezera[7] = array('titulo' => 'F.Pro', 'atributos' => 'width:80px');
        $oCabezera[8] = array('titulo' => 'Mes a Cargar', 'atributos' => 'width:80px');
        $oCabezera[9] = array('titulo' => 'Observaciones', 'atributos' => 'width:80px');
        $oCabezera[10] = array('titulo' => 'oida', 'oculto' => 1);
        $oCabezera[11] = array('titulo' => 'archivo', 'oculto' => 1);
        $oCabezera[12] = array('titulo' => 'monto_bs', 'oculto' => 1);
        $oCabezera[13] = array('titulo' => '#', 'tipo' => 'enlace', 'metodo' => 1, 'mantiene' => 1,
            'ruta' => __IMG__ . 'botones/agregar.png', 'funcion' => 'cargarCuota', 'parametro' => '1,2', 'atributos' => 'width:15px');
        // Dólar BCV (no se cobró el 100% enviado) o Dólar Cobranza (se cobró
        // completo) — se resuelve por fila al leer el archivo (montoCobrado
        // vs. montoTotal del XLSX) y se muestra aquí para transparencia.
        $oCabezera[14] = array('titulo' => 'Tasa', 'atributos' => 'width:70px;text-align:center');
        // fecha_tasa (oculta): la fecha REAL usada para buscar esa tasa —
        // F.Env si es Cobranza (alinea a todos los que pagaron completo en
        // el mismo envío), F.Pro si es BCV. guardarLote() la necesita para
        // resolver la misma tasa que se mostró aquí, no otra.
        $oCabezera[15] = array('titulo' => 'fecha_tasa', 'oculto' => 1);
        // Quita la fila del lote antes de Guardar — el cliente no se guarda
        // con esta carga.
        $oCabezera[16] = array('titulo' => 'Quitar', 'tipo' => 'enlace', 'metodo' => 1, 'mantiene' => 1,
            'ruta' => __IMG__ . 'botones/quitar.png', 'funcion' => 'eliminarFilaVenezuela', 'parametro' => '1', 'atributos' => 'width:15px');

        $consulta = $this->db->query($query);
        $i = 0;
        $sumaBs = 0.0;
        $sumaUsd = 0.0;
        $ban = 0;
        $sinTasa = 0;
        $tasasUsadas = array();
        $oFila = array();

        foreach ($consulta->result() as $fila) {
            $color = '';
            $cerrar = '';
            $montoBs = (float) $fila->monto_bs;

            // Tipo (BCV o Cobranza) ya decidido al leer el archivo, según si
            // se cobró el 100% de lo enviado (montoCobrado vs. montoTotal).
            // La fecha para BUSCAR la tasa es fecha_tasa, no fpro: si es
            // Cobranza, la fecha de ENVÍO del lote (alinea a todos los que
            // pagaron completo a una sola tasa); si es BCV, la fecha real de
            // cobro. F.Pro (columna 7) siempre muestra el día real de
            // proceso de cada cliente, sin cambios.
            $tipoTasa = $fila->tipo_tasa !== '' ? $fila->tipo_tasa : 'BCV';
            $fechaTasa = !empty($fila->fecha_tasa) ? $fila->fecha_tasa : $fila->fpro;
            $tasaFila = $this->resolverTasaPorFecha($fechaTasa, $tipoTasa);
            $contrato = $fila->contrato;

            if ($contrato === '' || $contrato === null) {
                $ban++;
                $color = '<font color=\'#1964B5\' size="2"><b>';
                $contrato = 'Por Asignar';
                $cerrar = '</b></font>';
            }

            if ($tasaFila) {
                // Se trunca a 2 decimales (no se redondea) para no alterar el
                // monto real que resulta de la conversión.
                $montoUsd = $this->truncar2($montoBs / $tasaFila['precio']);
                $tasasUsadas[$fechaTasa . '|' . $tipoTasa] = array(
                    'fecha' => $fechaTasa, 'tipo' => $tipoTasa, 'precio' => $tasaFila['precio'],
                );
                $montoUsdTxt = number_format($montoUsd, 2, '.', '');
            } else {
                $sinTasa++;
                $montoUsd = 0;
                $montoUsdTxt = '<font color=\'#b70000\'><b>Sin tasa ' . $tipoTasa . ' (' . htmlspecialchars($fechaTasa) . ')</b></font>';
            }

            $sumaBs += $montoBs;
            $sumaUsd += $montoUsd;
            $oFila[++$i] = array(
                '1' => $fila->oid,
                '2' => $fila->cedula,
                '3' => $color . $contrato . $cerrar,
                '4' => number_format($montoBs, 2, '.', ''),
                '5' => $montoUsdTxt,
                '6' => $fila->fenv,
                '7' => $fila->fpro,
                '8' => $fila->frec,
                // Nunca vacío: si el <td> queda con innerHTML="" no genera
                // ningún nodo hijo, y editar.js (al guardar el lote) asume
                // que siempre hay al menos uno — con la celda vacía revienta
                // con "Cannot read properties of null (reading 'type')". Un
                // espacio simple (no una entidad) genera el nodo de texto
                // necesario y el trim() de guardarLote() lo limpia solo.
                '9' => $fila->obser !== '' ? $fila->obser : ' ',
                '10' => $fila->oida,
                '11' => $fila->archivo,
                '12' => number_format($montoBs, 4, '.', ''),
                '13' => 'Cargar',
                '14' => $tipoTasa,
                '15' => $fechaTasa,
                '16' => 'Quitar',
            );
        }

        if ($sinTasa > 0) {
            $leyendaTasa = '<span style="color:#b70000;">Faltan ' . $sinTasa . ' fila(s) sin tasa (BCV o Cobranza, según '
                . 'corresponda) registrada para su fecha de cobro. Regístrelas en Panel → Precio del Dólar antes de continuar.</span>';
        } elseif (count($tasasUsadas) === 1) {
            $unica = reset($tasasUsadas);
            $leyendaTasa = 'Tasa ' . $unica['tipo'] . ' de la fecha de cobro (' . htmlspecialchars($unica['fecha']) . '): <b>'
                . number_format($unica['precio'], 4, ',', '.') . ' Bs./USD</b>';
        } else {
            $leyendaTasa = 'Tasa BCV o Cobranza aplicada por fila según su fecha de cobro y si se cobró completo '
                . 'lo enviado (' . count($tasasUsadas) . ' combinación(es) fecha/tipo distintas).';
        }

        $leyendaAsignar = '';
        if ($ban > 0) {
            $leyendaAsignar = '<h3 style="color:#1964B5;line-height:1.4;margin:8px 0;text-transform:none;letter-spacing:normal;">Faltan ' . $ban . ' fila(s) sin contrato asignado '
                . '("Por Asignar") — haga clic en el ícono de cada una para asignarle su contrato. '
                . 'El botón "Cargar" para guardar todo el lote solo aparece cuando <b>todas</b> las filas '
                . 'ya tienen contrato y tasa BCV.</h3>';
        }

        $leyenda = '<br><center><h3 style="line-height:1.4;margin:8px 0;text-transform:none;letter-spacing:normal;">' . $leyendaTasa . '</h3>' . $leyendaAsignar
            . '<h2 style="margin:8px 0;">Total Bs.: ' . number_format($sumaBs, 2, ',', '.')
            . ' &nbsp;|&nbsp; Total USD: ' . number_format($sumaUsd, 2, ',', '.') . '</h2></center>';

        if ($ban === 0 && $sinTasa === 0) {
            $oTable = array(
                'Cabezera' => $oCabezera,
                'Cuerpo' => $oFila,
                'Origen' => 'json',
                'Editable' => 'Guardar_Lote_Venezuela',
                'Parametros' => '2,3,11,9,8,12,7,10,14,15',
                'leyenda' => $leyenda,
            );
        } else {
            $oTable = array(
                'Cabezera' => $oCabezera,
                'Cuerpo' => $oFila,
                'Origen' => 'json',
                'leyenda' => $leyenda,
            );
        }

        return json_encode($oTable);
    }

    /**
     * Aplica cobros en USD a t_lista_cobros (monto Bs / tasa del archivo).
     *
     * @param array $filas filas del TGrid
     * @param int $usuarioId
     * @return array ['ok'=>bool,'msg'=>string]
     */
    public function guardarLote($filas, $usuarioId) {
        if (empty($filas) || !is_array($filas)) {
            return array('ok' => false, 'msg' => 'No hay filas para procesar.');
        }

        $oida = (int) $filas[0][7];
        $arch = $this->getArchivo($oida);
        if (!$arch) {
            return array('ok' => false, 'msg' => 'Archivo no encontrado.');
        }
        if ((int) $arch->estatus === 1) {
            return array('ok' => false, 'msg' => 'Este archivo ya fue procesado anteriormente. No se volvió a cargar.');
        }

        // Primera pasada: validar contrato asignado y resolver la tasa (BCV o
        // Cobranza, según se haya cobrado o no el 100% de lo enviado, decidido
        // al leer el archivo) de la fecha_tasa de CADA fila — F.Env si es
        // Cobranza (alinea a todos los que pagaron completo en el mismo
        // envío a una sola tasa), F.Pro si es BCV. Si falta cualquiera, se
        // bloquea el lote COMPLETO — no se guarda nada hasta que todas las
        // filas tengan su tasa.
        $tasasPorFila = array();
        $faltantes = array();
        foreach ($filas as $idx => $fila) {
            $creditoId = trim(strip_tags($fila[1]));
            if ($creditoId === '' || stripos($creditoId, 'Por Asignar') !== false) {
                return array('ok' => false, 'msg' => 'Hay filas sin contrato asignado.');
            }

            $fpro = trim($fila[6]);
            $tipoTasa = isset($fila[8]) ? trim(strip_tags($fila[8])) : 'BCV';
            $tipoTasa = $tipoTasa === 'COBRANZA' ? 'COBRANZA' : 'BCV';
            $fechaTasa = isset($fila[9]) ? trim(strip_tags($fila[9])) : '';
            if ($fechaTasa === '') {
                $fechaTasa = $fpro;
            }
            $tasaFila = $fechaTasa !== '' ? $this->resolverTasaPorFecha($fechaTasa, $tipoTasa) : null;
            if (!$tasaFila) {
                $faltantes[] = trim($fila[0]) . ' (fecha ' . ($fechaTasa !== '' ? $fechaTasa : 's/f') . ', ' . $tipoTasa . ')';
            } else {
                $tasasPorFila[$idx] = $tasaFila['precio'];
            }
        }

        if (!empty($faltantes)) {
            return array(
                'ok' => false,
                'msg' => 'No se guardó nada: falta tasa para la fecha de cobro de estas filas ('
                    . 'cédula / fecha / tipo): ' . implode(', ', $faltantes)
                    . '. Regístrela en Panel → Precio del Dólar y vuelva a intentar.',
            );
        }

        // Motor compartido (también usado por el flujo de Banfan/Bicentenario,
        // mrespuestabanco.php) que reparte el pago entre TODOS los contratos
        // activos de la cédula, ordenados del más antiguo al más reciente —
        // no solo el contrato asignado en esta fila. "Tipo de Cobranza" del
        // archivo (elegido al subirlo, igual que en Banfan/Bicentenario):
        // Regular = solo abona la cuota del mes de cobro actual; Moroso =
        // busca primero cuotas vencidas (meses anteriores sin pagar) antes de
        // caer en la del mes actual. Esto es aparte de la FECHA CUOTA (ver
        // más abajo), que usa el día registrado del contrato, no la fecha
        // real de cobro del banco.
        // Loader::model() solo engancha el modelo cargado al objeto super de CI
        // (get_instance()->MCascada), no a $this de este modelo — por eso se
        // accede vía get_instance() y no vía $this->MCascada.
        $this->load->model('cargar/mcascadapagos', 'MCascada');
        $CI =& get_instance();

        $creditoIds = array();
        foreach ($filas as $fila) {
            $c = trim(strip_tags($fila[1]));
            if ($c !== '') {
                $creditoIds[] = $c;
            }
        }
        $diasCobroPorContrato = $this->obtenerDiasCobroPorContrato($creditoIds);

        $esMoroso = isset($arch->tipo_cobranza) && strtoupper(trim($arch->tipo_cobranza)) === 'MOROSO';
        $modoCascada = $esMoroso ? 'MOROSO' : 'REGULAR';

        $values = array();
        $conCobro = 0;
        $sinCobro = 0;

        foreach ($filas as $idx => $fila) {
            $documentoId = trim($fila[0]);
            $creditoId = trim(strip_tags($fila[1]));
            $montoBs = (float) $fila[5];
            $fechaCobro = trim($fila[4]);
            $fpro = trim($fila[6]);
            $obser = isset($fila[3]) ? trim(strip_tags($fila[3])) : '';
            $tasa = $tasasPorFila[$idx];

            if ($fechaCobro === '') {
                $fechaCobro = $fpro;
            }
            if ($fechaCobro === '') {
                $fechaCobro = !empty($arch->fenv) ? $arch->fenv : date('Y-m-d');
            }
            if ($fpro === '') {
                $fpro = $fechaCobro;
            }

            // Truncado a 2 decimales (no redondeado) — mismo criterio que en
            // grid() para que el monto que se guarda coincida con lo que se
            // mostró en pantalla.
            $montoUsd = $montoBs > 0 ? $this->truncar2($montoBs / $tasa) : 0.0;

            $parts = explode('-', $fechaCobro);
            $mesp = isset($parts[1]) ? (int) $parts[1] : 0;
            $anop = isset($parts[0]) ? (int) $parts[0] : 0;

            // FECHA CUOTA: día registrado del contrato (fecha_inicio_cobro),
            // combinado con el mes/año real de cobro (mesp/anop, derivados
            // de la fecha del banco) — NO el día real en que procesó el
            // banco. FECHA DESCUENTO (farc) sigue siendo $fpro, sin cambios.
            // Único caso especial: Moroso siempre usa el día 01, sin importar
            // el día registrado del contrato.
            $diaCobro = $esMoroso ? 1 : (isset($diasCobroPorContrato[$creditoId]) ? $diasCobroPorContrato[$creditoId] : null);
            $fechaCuota = $this->construirFechaCuota($diaCobro, $anop, $mesp, $fechaCobro);

            if ($montoUsd <= 0) {
                $sinCobro++;
                $mes = 'CARGA VENEZUELA:' . $creditoId;
                $desc = $this->construirDescripcionCobro($montoBs, $montoUsd, $tasa, $obser);
                $values[] = '(' . $this->db->escape($documentoId) . ','
                    . $this->db->escape($creditoId) . ','
                    . $this->db->escape($mes) . ','
                    . $this->db->escape($desc) . ','
                    . $this->db->escape($fechaCuota) . ','
                    . $this->db->escape($montoUsd) . ','
                    . $this->db->escape($fpro) . ','
                    . (int) $mesp . ','
                    . (int) $anop . ',25,'
                    . (int) $usuarioId . ')';
                continue;
            }

            // Cada contrato de Venezuela tiene su propia domiciliación
            // bancaria independiente — esta fila del archivo YA es el pago
            // específico del contrato asignado en pantalla, no un monto
            // combinado de todos los contratos del cliente (a diferencia de
            // Banfan/Bicentenario, donde sí llega un monto agregado por
            // cédula). Por eso primero se cubre lo que le corresponde al
            // contrato asignado, y la cascada hacia otros contratos del
            // mismo cliente SOLO entra si sobra dinero después de eso.
            $objetivoAsignado = $CI->MCascada->montoObjetivoContrato($creditoId, $modoCascada, $fpro);
            $aplicadoAsignado = round(min($montoUsd, $objetivoAsignado), 2);
            $sobrante = round($montoUsd - $aplicadoAsignado, 2);

            $asignaciones = array();
            if ($aplicadoAsignado > 0) {
                $asignaciones[] = array('contrato_id' => $creditoId, 'monto_usd' => $aplicadoAsignado);
            }
            if ($sobrante > 0) {
                $reparto = $CI->MCascada->distribuirPago(
                    $documentoId, $sobrante, $modoCascada, $fpro, 'VENEZUELA', array($creditoId)
                );
                foreach ($reparto['asignaciones'] as $asig) {
                    $asignaciones[] = $asig;
                }
            }
            if (empty($asignaciones)) {
                $asignaciones[] = array('contrato_id' => $creditoId, 'monto_usd' => $montoUsd);
            }
            // Lo que la cascada no pudo ubicar (todos los contratos topados en
            // su objetivo) igual fue cobrado por el banco: no se descarta.
            $asignaciones = $CI->MCascada->abonarSobranteAlUltimo($asignaciones, $montoUsd);

            foreach ($asignaciones as $asig) {
                $conCobro++;
                $mes = 'CARGA VENEZUELA:' . $asig['contrato_id'];
                $descAsig = $this->construirDescripcionCobro($montoBs, $asig['monto_usd'], $tasa, $obser);
                if ($asig['contrato_id'] !== $creditoId) {
                    $descAsig .= ' | Repartido por antigüedad (contrato asignado en pantalla: ' . $creditoId . ')';
                }
                $values[] = '(' . $this->db->escape($documentoId) . ','
                    . $this->db->escape($asig['contrato_id']) . ','
                    . $this->db->escape($mes) . ','
                    . $this->db->escape($descAsig) . ','
                    . $this->db->escape($fechaCuota) . ','
                    . $this->db->escape($asig['monto_usd']) . ','
                    . $this->db->escape($fpro) . ','
                    . (int) $mesp . ','
                    . (int) $anop . ',25,'
                    . (int) $usuarioId . ')';
            }
        }

        if (empty($values)) {
            return array('ok' => false, 'msg' => 'No hay filas para registrar.');
        }

        // Marca el archivo como procesado ANTES de insertar, condicionado a que
        // siga en estatus=0: si dos solicitudes llegan casi simultáneas (doble
        // clic, reintento de red), solo una gana esta carrera y continúa.
        $this->db->query('UPDATE t_archivos_venezuela SET estatus=1 WHERE oid=' . $oida . ' AND estatus=0');
        if ($this->db->affected_rows() !== 1) {
            return array('ok' => false, 'msg' => 'Este archivo ya fue procesado anteriormente. No se volvió a cargar.');
        }

        $sql = 'INSERT INTO t_lista_cobros (documento_id,credito_id,mes,descripcion,fecha,monto,farc,mesp,anop,moda,usua) VALUES '
            . implode(',', $values);
        $res = $this->db->query($sql);
        if (!$res) {
            // No se pudo insertar: revertir la marca de procesado para no dejar el archivo "atascado".
            $this->db->query('UPDATE t_archivos_venezuela SET estatus=0 WHERE oid=' . $oida);
            return array('ok' => false, 'msg' => 'No se pudo cargar datos en lista de cobros.');
        }

        $detalle = array();
        if ($conCobro > 0) {
            $tasasDistintas = count(array_unique($tasasPorFila));
            $detalle[] = $conCobro . ' con cobro (tasa BCV por fecha de cobro de cada fila, '
                . $tasasDistintas . ' tasa(s) distinta(s) aplicada(s))';
        }
        if ($sinCobro > 0) {
            $detalle[] = $sinCobro . ' sin cobro (bitácora)';
        }
        $msg = 'Se registraron ' . count($values) . ' movimiento(s) en lista de cobros';
        if (!empty($detalle)) {
            $msg .= ': ' . implode(', ', $detalle);
        }
        $msg .= '.';

        return array('ok' => true, 'msg' => $msg);
    }
}
