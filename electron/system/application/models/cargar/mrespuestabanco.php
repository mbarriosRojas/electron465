<?php

/**
 * Carga respuesta de banco (Banfan/Bicentenario, formato "Listado de Debito de
 * Nomina") y aplicación de cobros en USD, convertidos con Dólar BCV.
 *
 * Funcionalidad NUEVA e independiente de cooperativa.php/mprocesar.php — no
 * toca cpp_archivo/t_cargar_txt ni el flujo viejo de Leer_Txt.
 */
class Mrespuestabanco extends Model {

    const TBL_ARCHIVO = 't_archivos_respuesta_banco';
    const TBL_DETALLE = 't_cargar_respuesta_banco';
    /** Centinela: cliente sin ningún crédito con saldo pendiente — se excluye
     *  del lote (no bloquea las demás filas), no es un contrato real. */
    const SIN_SALDO = 'SIN_SALDO';

    /** Máximo de tuplas por INSERT masivo (archivos de miles de líneas no
     *  deben depender del `max_allowed_packet` del hosting). */
    const LOTE_INSERT_MAX = 500;

    /**
     * Inserta $tuplas (cada una ya como "(...)" listo para VALUES) en bloques
     * de self::LOTE_INSERT_MAX, en vez de un solo INSERT gigante (archivos de
     * miles de líneas no deben depender del `max_allowed_packet` del hosting).
     * Corre todos los bloques en una transacción: si uno falla a la mitad, se
     * revierten los bloques ya insertados — nunca queda una carga parcial.
     */
    private function insertarEnBloques($sqlPrefijo, array $tuplas) {
        $this->db->trans_start();
        foreach (array_chunk($tuplas, self::LOTE_INSERT_MAX) as $bloque) {
            $this->db->query($sqlPrefijo . implode(',', $bloque));
        }
        $this->db->trans_complete();
        return $this->db->trans_status() !== FALSE;
    }

    public function __construct() {
        parent::__construct();
        $this->ensureSchema();
        $this->ensureHashColumna();
        $this->ensureTipoCobranzaColumna();
        $this->ensureTasaColumnas();
    }

    /**
     * Regla de oro (confirmada por el usuario): dentro de un mismo archivo,
     * una cédula puede venir fragmentada en varias líneas (el banco "picó" el
     * cobro). Si al menos una de esas líneas quedó rechazada por falta de
     * disponibilidad ("(POR DISPONIBLE)"), las líneas de esa MISMA cédula que
     * sí pasaron se convierten con Dólar BCV (como ya funcionaba). Si la
     * cédula no tuvo ningún rechazo en el archivo, sus líneas aplicadas se
     * convierten con Dólar Cobranza — antes se usaba BCV para todos por
     * igual. Se necesita guardar CUÁL tasa/tipo se usó por fila (no un solo
     * valor por archivo), porque ya no es uniforme.
     */
    private function ensureTasaColumnas() {
        if ($this->db->table_exists(self::TBL_DETALLE)) {
            if (!$this->db->field_exists('tipo_tasa', self::TBL_DETALLE)) {
                $this->db->query(
                    'ALTER TABLE `' . self::TBL_DETALLE . '` ADD COLUMN `tipo_tasa` VARCHAR(16) NOT NULL DEFAULT \'\' AFTER `monto_usd`'
                );
            }
            if (!$this->db->field_exists('tasa_usada', self::TBL_DETALLE)) {
                $this->db->query(
                    'ALTER TABLE `' . self::TBL_DETALLE . '` ADD COLUMN `tasa_usada` DECIMAL(18,4) NOT NULL DEFAULT 0 AFTER `tipo_tasa`'
                );
            }
        }
        if (self::TBL_ARCHIVO && $this->db->table_exists(self::TBL_ARCHIVO) && !$this->db->field_exists('precio_cobranza', self::TBL_ARCHIVO)) {
            $this->db->query(
                'ALTER TABLE `' . self::TBL_ARCHIVO . '` ADD COLUMN `precio_cobranza` DECIMAL(18,4) NOT NULL DEFAULT 0 AFTER `precio_bcv`'
            );
        }
    }

    /**
     * Columna auto-migrable para detectar contenido duplicado (mismo archivo
     * subido con otro nombre) — no rompe instalaciones existentes.
     */
    private function ensureHashColumna() {
        if ($this->db->table_exists(self::TBL_ARCHIVO) && !$this->db->field_exists('hash_contenido', self::TBL_ARCHIVO)) {
            $this->db->query(
                'ALTER TABLE `' . self::TBL_ARCHIVO . '` ADD COLUMN `hash_contenido` VARCHAR(40) NOT NULL DEFAULT \'\' AFTER `archivo`'
            );
        }
    }

    /**
     * Regla de oro (confirmada por el usuario): un archivo de "Respuesta
     * Banco" puede ser cobranza REGULAR (usa el día real de cobro del
     * contrato para la FECHA CUOTA) o MOROSO (fuerza la FECHA CUOTA al día
     * 01 del mes de cobro, sin importar el día real del contrato). Se elige
     * al subir el archivo — aplica a todas las filas de ese archivo.
     */
    private function ensureTipoCobranzaColumna() {
        if ($this->db->table_exists(self::TBL_ARCHIVO) && !$this->db->field_exists('tipo_cobranza', self::TBL_ARCHIVO)) {
            $this->db->query(
                'ALTER TABLE `' . self::TBL_ARCHIVO . '` ADD COLUMN `tipo_cobranza` VARCHAR(16) NOT NULL DEFAULT \'REGULAR\' AFTER `banco`'
            );
        }
    }

    public function ensureSchema() {
        $this->db->query(
            "CREATE TABLE IF NOT EXISTS `" . self::TBL_ARCHIVO . "` (
                `oid` INT(11) NOT NULL AUTO_INCREMENT,
                `archivo` VARCHAR(255) NOT NULL,
                `banco` VARCHAR(32) NOT NULL,
                `fecha_nomina` DATE NULL,
                `precio_bcv` DECIMAL(18,4) NOT NULL DEFAULT 0,
                `monto` DECIMAL(18,2) NOT NULL DEFAULT 0,
                `estatus` TINYINT(1) NOT NULL DEFAULT 0,
                `usuario` VARCHAR(64) NOT NULL DEFAULT '',
                `fecha_registro` DATETIME NOT NULL,
                PRIMARY KEY (`oid`),
                UNIQUE KEY `uk_archivo_banco` (`archivo`, `banco`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8"
        );

        $this->db->query(
            "CREATE TABLE IF NOT EXISTS `" . self::TBL_DETALLE . "` (
                `oid` INT(11) NOT NULL AUTO_INCREMENT,
                `oida` INT(11) NOT NULL,
                `cedula` INT(11) NOT NULL,
                `nombre` VARCHAR(255) NOT NULL DEFAULT '',
                `cuenta` VARCHAR(32) NOT NULL DEFAULT '',
                `seccion` CHAR(1) NOT NULL DEFAULT 'A',
                `monto_bs` DECIMAL(18,2) NOT NULL DEFAULT 0,
                `monto_usd` DECIMAL(18,4) NOT NULL DEFAULT 0,
                `contrato` VARCHAR(20) NOT NULL DEFAULT '',
                `motivo` VARCHAR(255) NOT NULL DEFAULT '',
                PRIMARY KEY (`oid`),
                KEY `idx_oida` (`oida`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8"
        );
    }

    public function getArchivo($oida) {
        $q = $this->db->get_where(self::TBL_ARCHIVO, array('oid' => (int) $oida));
        return $q->num_rows() > 0 ? $q->row() : null;
    }

    /**
     * @return array ['ok'=>bool,'msg'=>string,'id'=>int,'reemplazo'=>bool]
     */
    public function subirArchivo($banco, $nombreArchivo, $tmpPath, $usuario, $tipoCobranza = 'REGULAR') {
        $banco = strtoupper(trim($banco));
        $nombreArchivo = trim($nombreArchivo);
        $tipoCobranza = strtoupper(trim((string) $tipoCobranza));
        if (!in_array($tipoCobranza, array('REGULAR', 'MOROSO'), true)) {
            $tipoCobranza = 'REGULAR';
        }
        if ($banco === '' || $nombreArchivo === '') {
            return array('ok' => false, 'msg' => 'Parámetros inválidos.', 'id' => 0, 'reemplazo' => false);
        }

        $existente = $this->db->query(
            'SELECT oid, estatus FROM ' . self::TBL_ARCHIVO
            . ' WHERE archivo=' . $this->db->escape($nombreArchivo)
            . ' AND banco=' . $this->db->escape($banco)
        );
        if ($existente->num_rows() > 0) {
            $row = $existente->row();
            if ((int) $row->estatus === 1) {
                return array(
                    'ok' => false,
                    'msg' => 'No Se Pudo Procesar. El Archivo Ya Fue Procesado',
                    'id' => 0,
                    'reemplazo' => false,
                );
            }
        }

        // Detección de contenido duplicado (mismo archivo subido con OTRO
        // nombre): se calcula ANTES de mover el archivo, mientras aún está en
        // la ruta temporal. No bloquea la carga — solo advierte, ya que puede
        // ser una prueba intencional con el mismo archivo de muestra.
        $hash = is_readable($tmpPath) ? sha1_file($tmpPath) : '';
        $duplicadoAdvertencia = '';
        if ($hash !== '') {
            $previos = $this->db->query(
                'SELECT archivo, estatus, fecha_registro FROM ' . self::TBL_ARCHIVO
                . ' WHERE hash_contenido=' . $this->db->escape($hash)
                . ' AND banco=' . $this->db->escape($banco)
                . ' AND archivo != ' . $this->db->escape($nombreArchivo)
                . ' ORDER BY fecha_registro DESC LIMIT 1'
            );
            if ($previos->num_rows() > 0) {
                $p = $previos->row();
                $duplicadoAdvertencia = 'Este mismo contenido ya fue cargado antes como "' . $p->archivo . '" el '
                    . date('d/m/Y H:i', strtotime($p->fecha_registro))
                    . ((int) $p->estatus === 1 ? ' (ya procesado/guardado)' : ' (aún pendiente)')
                    . '. Si continúa, podría duplicar los cobros aplicados.';
            }
        }

        $directorio = BASEPATH . 'repository/txt/respuesta_banco/' . strtolower($banco);
        if (!is_dir($directorio)) {
            @mkdir($directorio, 0775, true);
        }
        $destino = $directorio . '/' . $nombreArchivo;
        if (!move_uploaded_file($tmpPath, $destino)) {
            return array('ok' => false, 'msg' => 'No se pudo guardar el archivo.', 'id' => 0, 'reemplazo' => false);
        }

        if ($existente->num_rows() > 0) {
            $oid = (int) $existente->row()->oid;
            $this->db->where('oid', $oid);
            $this->db->update(self::TBL_ARCHIVO, array(
                'estatus' => 0,
                'tipo_cobranza' => $tipoCobranza,
                'fecha_nomina' => null,
                'precio_bcv' => 0,
                'monto' => 0,
                'hash_contenido' => $hash,
                'usuario' => $usuario,
                'fecha_registro' => date('Y-m-d H:i:s'),
            ));
            $this->db->query('DELETE FROM ' . self::TBL_DETALLE . ' WHERE oida=' . $oid);
            return array(
                'ok' => true, 'msg' => '', 'id' => $oid, 'reemplazo' => true,
                'duplicado_advertencia' => $duplicadoAdvertencia,
            );
        }

        $this->db->insert(self::TBL_ARCHIVO, array(
            'archivo' => $nombreArchivo,
            'banco' => $banco,
            'tipo_cobranza' => $tipoCobranza,
            'hash_contenido' => $hash,
            'usuario' => $usuario,
            'fecha_registro' => date('Y-m-d H:i:s'),
        ));
        return array(
            'ok' => true, 'msg' => '', 'id' => $this->db->insert_id(), 'reemplazo' => false,
            'duplicado_advertencia' => $duplicadoAdvertencia,
        );
    }

    public function crearCombo($banco = null) {
        $where = 'estatus = 0';
        if ($banco !== null && $banco !== '') {
            $where .= ' AND banco=' . $this->db->escape(strtoupper(trim($banco)));
        }
        $con = $this->db->query('SELECT oid, archivo, banco FROM ' . self::TBL_ARCHIVO . ' WHERE ' . $where . ' ORDER BY oid DESC');
        $item = array();
        foreach ($con->result() as $fila) {
            $item[] = array('id' => $fila->oid, 'valor' => $fila->archivo . ' (' . $fila->banco . ')', 'banco' => $fila->banco);
        }
        return $item;
    }

    /**
     * @return array ['ok'=>bool,'msg'=>string,'aplicadas'=>int,'rechazadas'=>int]
     */
    public function leerArchivoDetalle($oida, $ruta) {
        date_default_timezone_set('America/Caracas');
        $oida = (int) $oida;
        $arch = $this->getArchivo($oida);
        if (!$arch) {
            return array('ok' => false, 'msg' => 'Archivo no encontrado.', 'aplicadas' => 0, 'rechazadas' => 0);
        }

        $directorio = BASEPATH . 'repository/txt/respuesta_banco/' . strtolower($arch->banco) . '/' . $ruta;
        if (!is_readable($directorio)) {
            return array('ok' => false, 'msg' => 'No se encontró el archivo en el repositorio.', 'aplicadas' => 0, 'rechazadas' => 0);
        }

        $contenidoRaw = file_get_contents($directorio);
        if ($contenidoRaw === false) {
            return array('ok' => false, 'msg' => 'No se pudo leer el archivo.', 'aplicadas' => 0, 'rechazadas' => 0);
        }
        if (!mb_check_encoding($contenidoRaw, 'UTF-8')) {
            $contenidoRaw = mb_convert_encoding($contenidoRaw, 'UTF-8', 'ISO-8859-1');
        }

        $fechaNomina = $this->extraerFechaNomina($contenidoRaw);
        if ($fechaNomina === '') {
            return array(
                'ok' => false,
                'msg' => 'No se encontró la "FECHA NOMINA" en el archivo. Verifique que sea el formato correcto.',
                'aplicadas' => 0,
                'rechazadas' => 0,
            );
        }

        $tasaBcv = $this->resolverTasaPorFecha($fechaNomina, 'BCV', $arch->banco);
        if (!$tasaBcv) {
            return array(
                'ok' => false,
                'msg' => 'No hay tasa BCV registrada para la fecha de nómina (' . $fechaNomina . '). '
                    . 'Regístrela en Panel → Precio del Dólar antes de continuar.',
                'aplicadas' => 0,
                'rechazadas' => 0,
            );
        }

        $tasaCobranza = $this->resolverTasaPorFecha($fechaNomina, 'COBRANZA', $arch->banco);
        if (!$tasaCobranza) {
            return array(
                'ok' => false,
                'msg' => 'No hay tasa Dólar Cobranza registrada para la fecha de nómina (' . $fechaNomina . '). '
                    . 'Regístrela en Panel → Precio del Dólar antes de continuar.',
                'aplicadas' => 0,
                'rechazadas' => 0,
            );
        }

        list($aplicadas, $rechazadas) = $this->parsearListadoDebito($contenidoRaw);
        if (empty($aplicadas) && empty($rechazadas)) {
            return array(
                'ok' => false,
                'msg' => 'No se encontraron registros válidos en el archivo.',
                'aplicadas' => 0,
                'rechazadas' => 0,
            );
        }

        // Regla de oro: cédula con AL MENOS una línea rechazada por falta de
        // disponibilidad en ESTE archivo (fragmentada/"picada") → sus líneas
        // aplicadas se convierten con Dólar BCV. Cédula sin ningún rechazo de
        // ese tipo → Dólar Cobranza. El motivo se compara tal cual lo reporta
        // el banco en la columna "Observación" del listado.
        $cedulasPorDisponible = array();
        foreach ($rechazadas as $cedula => $fila) {
            if (strtoupper(trim($fila['motivo'])) === '(POR DISPONIBLE)') {
                $cedulasPorDisponible[$cedula] = true;
            }
        }

        $this->db->query('DELETE FROM ' . self::TBL_DETALLE . ' WHERE oida=' . $oida);

        $montoTotalAplicado = 0.0;
        $bloques = array();
        foreach ($aplicadas as $cedula => $fila) {
            $esPorDisponible = isset($cedulasPorDisponible[$cedula]);
            $tasaFila = $esPorDisponible ? $tasaBcv : $tasaCobranza;
            $tipoFila = $esPorDisponible ? 'BCV' : 'COBRANZA';
            $montoUsd = round($fila['monto_bs'] / $tasaFila['precio'], 4);
            $montoTotalAplicado += $fila['monto_bs'];
            $bloques[] = '(' . $oida . ',' . (int) $cedula . ','
                . $this->db->escape($fila['nombre']) . ',' . $this->db->escape($fila['cuenta']) . ','
                . "'A'," . $this->db->escape($fila['monto_bs']) . ',' . $this->db->escape($montoUsd) . ','
                . $this->db->escape($tipoFila) . ',' . $this->db->escape($tasaFila['precio']) . ",'','')";
        }
        foreach ($rechazadas as $cedula => $fila) {
            $bloques[] = '(' . $oida . ',' . (int) $cedula . ','
                . $this->db->escape($fila['nombre']) . ',' . $this->db->escape($fila['cuenta']) . ','
                . "'R'," . $this->db->escape($fila['monto_bs']) . ",0,'',0,''," . $this->db->escape($fila['motivo']) . ')';
        }

        if (!empty($bloques)) {
            $okInsert = $this->insertarEnBloques(
                'INSERT INTO ' . self::TBL_DETALLE
                . ' (oida,cedula,nombre,cuenta,seccion,monto_bs,monto_usd,tipo_tasa,tasa_usada,contrato,motivo) VALUES ',
                $bloques
            );
            if (!$okInsert) {
                return array(
                    'ok' => false,
                    'msg' => 'No se pudo guardar el detalle del archivo (error de base de datos).',
                    'aplicadas' => 0,
                    'rechazadas' => 0,
                );
            }
        }

        $this->db->where('oid', $oida);
        $this->db->update(self::TBL_ARCHIVO, array(
            'fecha_nomina' => $fechaNomina,
            'precio_bcv' => $tasaBcv['precio'],
            'precio_cobranza' => $tasaCobranza['precio'],
            'monto' => $montoTotalAplicado,
        ));

        $this->autoAsignarContratosUnicos($oida, $arch->banco);
        $this->distribuirEntreContratosMultiples($oida);
        $this->marcarSinSaldo($oida);

        // Rechazadas: se les resuelve el contrato igual que a las aplicadas,
        // pero SIN reparto en cascada entre contratos múltiples (HU: si es
        // ambiguo, no se le puede adivinar el contrato a un cobro que ni
        // siquiera se aplicó — queda sin asignar para revisión manual).
        $this->autoAsignarContratosUnicos($oida, $arch->banco, 'R');
        $this->marcarSinSaldo($oida, 'R');

        return array(
            'ok' => true,
            'msg' => 'Archivo leído correctamente.',
            'aplicadas' => count($aplicadas),
            'rechazadas' => count($rechazadas),
        );
    }

    /**
     * Extrae "FECHA NOMINA.: dd/mm/yyyy" del encabezado del reporte.
     */
    /**
     * Regla de oro (confirmada por el usuario): la fecha de referencia para
     * buscar la tasa BCV — y la que queda guardada como "Fecha Nómina" del
     * archivo — es la "FECHA" del encabezado (fecha real en que el banco
     * ejecutó/generó el reporte), NO la "FECHA NOMINA" que trae el detalle
     * del control. Con archivos reales, usar "FECHA NOMINA" traía la tasa de
     * un día distinto y los montos en Bs. no cuadraban.
     *
     * Encabezado real (Banfan/Bicentenario):
     *   Bicentenario, Banco Universal ...   FECHA  10/07/2026        <- esta
     *   CONTROL No.: 2607000843  FECHA NOMINA.: 08/07/2026           <- NO esta
     *
     * El negative lookahead `(?!\s*NOMINA)` evita que el regex "muerda" la
     * segunda línea por accidente si algún banco cambia el orden del texto.
     */
    private function extraerFechaNomina($contenido) {
        if (!preg_match('/FECHA(?!\s*NOMINA)\s+(\d{2})\/(\d{2})\/(\d{4})/i', $contenido, $m)) {
            return '';
        }
        return sprintf('%04d-%02d-%02d', (int) $m[3], (int) $m[2], (int) $m[1]);
    }

    /**
     * Parsea el "Listado de Debito de Nomina" (Banfan/Bicentenario): dos
     * secciones (CUENTAS CON RECHAZOS / CUENTAS APLICADAS), agregando por
     * cédula (una cuota "picada" en varias líneas debe sumar como un solo abono).
     *
     * @return array [aplicadas, rechazadas] cada uno: [cedula => ['nombre','cuenta','monto_bs','motivo']]
     */
    private function parsearListadoDebito($contenido) {
        $lineas = preg_split('/\r\n|\n|\r/', $contenido);
        $aplicadas = array();
        $rechazadas = array();
        $seccion = null;

        foreach ($lineas as $linea) {
            $linea = rtrim($linea);
            if ($linea === '') {
                continue;
            }
            if (stripos($linea, 'CUENTAS CON RECHAZOS') !== false) {
                $seccion = 'R';
                continue;
            }
            if (stripos($linea, 'CUENTAS APLICADAS') !== false) {
                $seccion = 'A';
                continue;
            }
            if ($seccion === null) {
                continue;
            }
            if (strpos($linea, '=') !== false || stripos(trim($linea), 'Total') === 0
                || stripos($linea, 'C u e n t a s') !== false) {
                continue;
            }

            $partes = preg_split('/\s{2,}/', trim($linea));
            if (count($partes) < 4 || !ctype_digit($partes[0])) {
                continue;
            }

            $cuenta = $partes[0];
            $montoBs = $this->parseMontoVe($partes[1]);

            // La cédula es el primer token puramente numérico desde el índice 2
            // en adelante — NO se asume una posición fija, porque algunos
            // nombres compuestos traen doble espacio interno (ej. "SAAVEDRA
            // VILERA, JESUS A") y eso desplazaría la cédula si se tomara por
            // posición. Todo lo anterior a la cédula es el nombre; lo posterior
            // (si existe) es el motivo del rechazo.
            $idxCedula = null;
            for ($k = 2; $k < count($partes); $k++) {
                if (ctype_digit($partes[$k])) {
                    $idxCedula = $k;
                    break;
                }
            }
            if ($idxCedula === null) {
                continue;
            }

            $nombre = trim(implode(' ', array_slice($partes, 2, $idxCedula - 2)));
            $cedula = (int) $partes[$idxCedula];
            if ($cedula <= 0 || $montoBs <= 0) {
                continue;
            }

            if ($seccion === 'A') {
                if (!isset($aplicadas[$cedula])) {
                    $aplicadas[$cedula] = array('nombre' => $nombre, 'cuenta' => $cuenta, 'monto_bs' => 0.0);
                }
                $aplicadas[$cedula]['monto_bs'] += $montoBs;
            } else {
                $motivo = trim(implode(' ', array_slice($partes, $idxCedula + 1)));
                if (!isset($rechazadas[$cedula])) {
                    $rechazadas[$cedula] = array('nombre' => $nombre, 'cuenta' => $cuenta, 'monto_bs' => 0.0, 'motivo' => $motivo);
                }
                $rechazadas[$cedula]['monto_bs'] += $montoBs;
                if ($motivo !== '') {
                    $rechazadas[$cedula]['motivo'] = $motivo;
                }
            }
        }

        return array($aplicadas, $rechazadas);
    }

    private function parseMontoVe($valor) {
        $valor = trim((string) $valor);
        if ($valor === '') {
            return 0.0;
        }
        $normalizado = str_replace('.', '', $valor);
        $normalizado = str_replace(',', '.', $normalizado);
        return (float) $normalizado;
    }

    /**
     * @param string $tipo 'BCV' | 'COBRANZA' (MPrecio_dolar::TIPO_BCV | TIPO_COBRANZA)
     * @param string $banco Banco propio de la tasa (BANFAN/BICENTENARIO/...). Si no
     *   tiene tasa propia para esa fecha, MPrecio_dolar cae en General como respaldo.
     * @return array|null ['precio'=>float,'fecha'=>'Y-m-d']
     */
    public function resolverTasaPorFecha($fecha, $tipo = null, $banco = '') {
        $CI =& get_instance();
        $CI->load->model('mprecio_dolar', 'MPrecio_dolar');
        $tipo = $tipo !== null ? $tipo : 'BCV';
        $row = $CI->MPrecio_dolar->getPrecioPorFecha($fecha, $tipo, $banco);
        if (!$row || (float) $row->precio <= 0) {
            return null;
        }
        return array('precio' => (float) $row->precio, 'fecha' => $row->fecha);
    }

    /**
     * Si la cédula tiene un solo contrato elegible para este banco, se asigna automáticamente.
     *
     * @param string $seccion 'A' (aplicadas) o 'R' (rechazadas) — mismas reglas para ambas.
     */
    private function autoAsignarContratosUnicos($oida, $banco, $seccion = 'A') {
        $seccionEsc = $this->db->escape($seccion);
        $bancoEsc = $this->db->escape(strtoupper(trim($banco)));

        // Pasada 1: preferir el contrato registrado bajo ESTE banco exacto
        // (más confiable cuando el cliente tiene contratos en varios bancos).
        $sqlBanco = 'UPDATE ' . self::TBL_DETALLE . ' d
            INNER JOIN (
                SELECT documento_id, MIN(contrato_id) AS contrato_id
                FROM (
                    SELECT c.documento_id, c.contrato_id,
                        (c.monto_total - IFNULL(SUM(lc.monto), 0)) AS resta
                    FROM t_clientes_creditos c
                    LEFT JOIN t_lista_cobros lc ON lc.credito_id = c.contrato_id
                    WHERE UPPER(TRIM(c.cobrado_en)) = ' . $bancoEsc . '
                    AND c.marca_consulta != 6
                    AND c.cantidad != 0
                    AND c.estatus != 3
                    GROUP BY c.documento_id, c.contrato_id, c.monto_total
                    HAVING resta > 0
                ) activos
                GROUP BY documento_id
                HAVING COUNT(*) = 1
            ) u ON u.documento_id = d.cedula
            SET d.contrato = u.contrato_id
            WHERE d.oida = ' . (int) $oida . ' AND d.seccion = ' . $seccionEsc
            . ' AND (d.contrato IS NULL OR d.contrato = \'\')';
        $this->db->query($sqlBanco);

        // Pasada 2: para los que sigan sin asignar (el cliente no tiene ningún
        // contrato bajo ESTE banco — su crédito puede seguir marcado con otro
        // banco, ej. COTERO), buscar en TODOS sus contratos de cualquier banco
        // y asignar solo si tiene exactamente uno elegible.
        $sqlCualquiera = 'UPDATE ' . self::TBL_DETALLE . ' d
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
                    GROUP BY c.documento_id, c.contrato_id, c.monto_total
                    HAVING resta > 0
                ) activos
                GROUP BY documento_id
                HAVING COUNT(*) = 1
            ) u ON u.documento_id = d.cedula
            SET d.contrato = u.contrato_id
            WHERE d.oida = ' . (int) $oida . ' AND d.seccion = ' . $seccionEsc
            . ' AND (d.contrato IS NULL OR d.contrato = \'\')';
        $this->db->query($sqlCualquiera);
    }

    /**
     * Marca con el centinela SIN_SALDO las filas que sigan sin asignar y cuyo
     * cliente NO tenga ningún crédito con saldo pendiente (ya pagados o
     * sobrepagados) — no se pueden asignar a nada, se excluyen del lote sin
     * bloquear el resto (decisión confirmada por el usuario).
     */
    private function marcarSinSaldo($oida, $seccion = 'A') {
        $seccionEsc = $this->db->escape($seccion);
        $sqlSinSaldo = 'UPDATE ' . self::TBL_DETALLE . ' d
            LEFT JOIN (
                SELECT DISTINCT c.documento_id
                FROM t_clientes_creditos c
                LEFT JOIN t_lista_cobros lc ON lc.credito_id = c.contrato_id
                WHERE c.marca_consulta != 6 AND c.cantidad != 0 AND c.estatus != 3
                GROUP BY c.documento_id, c.contrato_id, c.monto_total
                HAVING (c.monto_total - IFNULL(SUM(lc.monto), 0)) > 0
            ) con_saldo ON con_saldo.documento_id = d.cedula
            SET d.contrato = \'' . self::SIN_SALDO . '\'
            WHERE d.oida = ' . (int) $oida . ' AND d.seccion = ' . $seccionEsc
            . ' AND (d.contrato IS NULL OR d.contrato = \'\')'
            . ' AND con_saldo.documento_id IS NULL';
        $this->db->query($sqlSinSaldo);
    }

    /**
     * Para clientes con 2+ contratos con saldo pendiente (quedaron "Por
     * Asignar" tras autoAsignarContratosUnicos), reparte el cobro en cascada:
     * se abona primero al contrato MÁS VIEJO hasta cubrir su saldo, y el
     * excedente pasa al siguiente contrato (más viejo → más nuevo), y así
     * sucesivamente. Regla confirmada por el usuario. Si el monto excede la
     * deuda total conocida, el sobrante se abona de más al último contrato.
     *
     * La fila original se actualiza con la primera porción; las porciones
     * adicionales se insertan como filas nuevas (mismo archivo/cliente) para
     * que cada una se procese como un cobro independiente a su contrato.
     */
    private function distribuirEntreContratosMultiples($oida) {
        $oida = (int) $oida;
        $filas = $this->db->query('SELECT oid, cedula, nombre, cuenta, monto_bs, tipo_tasa, tasa_usada
            FROM ' . self::TBL_DETALLE . " WHERE oida={$oida} AND seccion='A'"
            . " AND (contrato IS NULL OR contrato = '')
            ORDER BY cedula, oid")->result();

        // Agrupa por cédula: un mismo cliente puede tener más de una fila
        // pendiente en este mismo archivo (ej. fragmentos de "picar" que no
        // se agregaron), y todas deben repartirse contra el MISMO saldo
        // disponible, en el orden en que aparecen.
        $porCedula = array();
        foreach ($filas as $fila) {
            $porCedula[$fila->cedula][] = $fila;
        }

        foreach ($porCedula as $cedula => $filasCliente) {
            $contratos = $this->contratosConSaldoOrdenados($cedula);
            if (count($contratos) < 2) {
                // Único contrato ya lo resolvió autoAsignarContratosUnicos;
                // cero contratos lo resolverá marcarSinSaldo.
                continue;
            }

            // Todas las filas de una misma cédula comparten la misma tasa
            // (se decide por cédula, no por línea, en leerArchivoDetalle).
            $tasa = (float) $filasCliente[0]->tasa_usada;
            if ($tasa <= 0) {
                continue;
            }

            // Ledger en memoria: saldo disponible por contrato, se va
            // descontando a medida que se reparte cada fila del cliente, para
            // que la segunda fila (si existe) no reutilice saldo ya asignado
            // a la primera en esta misma corrida.
            $saldoDisponibleBs = array();
            foreach ($contratos as $c) {
                $saldoDisponibleBs[$c->contrato_id] = round((float) $c->saldo_usd * $tasa, 2);
            }

            foreach ($filasCliente as $fila) {
                $restanteBs = (float) $fila->monto_bs;
                $asignaciones = array();
                foreach ($contratos as $c) {
                    if ($restanteBs <= 0) {
                        break;
                    }
                    $disponible = $saldoDisponibleBs[$c->contrato_id];
                    if ($disponible <= 0) {
                        continue;
                    }
                    $porcion = min($restanteBs, $disponible);
                    $asignaciones[] = array('contrato' => $c->contrato_id, 'monto_bs' => $porcion);
                    $restanteBs -= $porcion;
                    $saldoDisponibleBs[$c->contrato_id] -= $porcion;
                }
                if (empty($asignaciones)) {
                    continue;
                }
                if ($restanteBs > 0) {
                    // Sobrepago más allá de toda la deuda conocida (incluido
                    // lo ya repartido a filas anteriores del mismo cliente):
                    // se abona de más al último (más reciente) contrato.
                    $ultimo = count($asignaciones) - 1;
                    $asignaciones[$ultimo]['monto_bs'] += $restanteBs;
                    $saldoDisponibleBs[$asignaciones[$ultimo]['contrato']] -= $restanteBs;
                }

                $primero = $asignaciones[0];
                $this->db->query('UPDATE ' . self::TBL_DETALLE
                    . ' SET contrato=' . $this->db->escape($primero['contrato'])
                    . ', monto_bs=' . $this->db->escape($primero['monto_bs'])
                    . ', monto_usd=' . $this->db->escape(round($primero['monto_bs'] / $tasa, 4))
                    . ' WHERE oid=' . (int) $fila->oid);

                for ($i = 1; $i < count($asignaciones); $i++) {
                    $porcion = $asignaciones[$i];
                    $this->db->query('INSERT INTO ' . self::TBL_DETALLE
                        . ' (oida,cedula,nombre,cuenta,seccion,monto_bs,monto_usd,tipo_tasa,tasa_usada,contrato,motivo) VALUES ('
                        . $oida . ',' . (int) $fila->cedula . ','
                        . $this->db->escape($fila->nombre) . ',' . $this->db->escape($fila->cuenta) . ","
                        . "'A'," . $this->db->escape($porcion['monto_bs']) . ','
                        . $this->db->escape(round($porcion['monto_bs'] / $tasa, 4)) . ','
                        . $this->db->escape($fila->tipo_tasa) . ',' . $this->db->escape($tasa) . ','
                        . $this->db->escape($porcion['contrato']) . ",'')");
                }
            }
        }
    }

    /**
     * Contratos con saldo pendiente de un cliente, ordenados del MÁS VIEJO
     * (fecha_solicitud ascendente) al más nuevo — sin filtrar por banco,
     * igual que la asignación manual (crearComboContrato).
     */
    private function contratosConSaldoOrdenados($cedula) {
        $cedula = (int) $cedula;
        $query = "SELECT c.contrato_id,
                (c.monto_total - IFNULL(SUM(lc.monto), 0)) AS saldo_usd
            FROM t_clientes_creditos c
            LEFT JOIN t_lista_cobros lc ON lc.credito_id = c.contrato_id
            WHERE c.documento_id = {$cedula}
            AND c.marca_consulta != 6 AND c.cantidad != 0 AND c.estatus != 3
            GROUP BY c.contrato_id, c.monto_total, c.fecha_solicitud
            HAVING saldo_usd > 0
            ORDER BY c.fecha_solicitud ASC, c.contrato_id ASC";
        return $this->db->query($query)->result();
    }

    public function crearComboContrato($cedula, $banco) {
        $cedula = $this->db->escape($cedula);
        // No se filtra por cobrado_en (igual que en cargaVenezuela/mcargavenezuela.php):
        // un cliente registrado bajo otro banco (ej. COTERO) puede estar siendo
        // cobrado en la práctica por este banco sin que ese campo se actualice
        // en su crédito original. Esta combo es para asignación MANUAL, así que
        // se muestran todos sus créditos con saldo, sin restringir por banco.
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

    public function modCuota($oid, $contrato) {
        $this->db->where('oid', (int) $oid);
        return $this->db->update(self::TBL_DETALLE, array('contrato' => $contrato));
    }

    /**
     * Quita una fila del lote antes de "Guardar Lote" — ese cliente no se
     * guarda con esta carga. Solo tiene sentido mientras el archivo sigue
     * pendiente (estatus=0); una vez guardado el lote la fila ya no vive en
     * esta tabla de staging.
     */
    public function eliminarFila($oid) {
        $this->db->query('DELETE FROM ' . self::TBL_DETALLE . ' WHERE oid=' . (int) $oid);
        return $this->db->affected_rows() > 0;
    }

    /**
     * Grilla de previsualización: solo filas seccion='A' (aplicadas / cobradas).
     */
    public function grid($oida) {
        $oida = (int) $oida;
        $arch = $this->getArchivo($oida);
        $tasaBcv = $arch ? (float) $arch->precio_bcv : 0;
        $tasaCobranza = $arch ? (float) $arch->precio_cobranza : 0;

        $query = 'SELECT oid, cedula, nombre, contrato, monto_bs, monto_usd, tipo_tasa, tasa_usada, oida
            FROM ' . self::TBL_DETALLE . "
            WHERE oida={$oida} AND seccion='A'
            ORDER BY nombre";

        $oCabezera[1] = array('titulo' => 'oid', 'oculto' => 1);
        $oCabezera[2] = array('titulo' => 'Cédula', 'atributos' => 'width:70px', 'buscar' => 1);
        $oCabezera[3] = array('titulo' => 'Cliente', 'atributos' => 'width:180px', 'buscar' => 1);
        $oCabezera[4] = array('titulo' => 'Contrato', 'atributos' => 'width:90px');
        $oCabezera[5] = array('titulo' => 'Monto Bs.', 'atributos' => 'width:80px;text-align:center', 'total' => 1);
        $oCabezera[6] = array('titulo' => 'Monto USD', 'atributos' => 'width:80px;text-align:center', 'total' => 1);
        $oCabezera[7] = array('titulo' => 'Tasa', 'atributos' => 'width:90px;text-align:center');
        $oCabezera[8] = array('titulo' => 'Descripción', 'atributos' => 'width:220px');
        $oCabezera[9] = array('titulo' => 'oida', 'oculto' => 1);
        $oCabezera[10] = array('titulo' => 'monto_bs', 'oculto' => 1);
        $oCabezera[11] = array('titulo' => '#', 'tipo' => 'enlace', 'metodo' => 1, 'mantiene' => 1,
            'ruta' => __IMG__ . 'botones/agregar.png', 'funcion' => 'cargarCuotaRespuesta', 'parametro' => '1,2', 'atributos' => 'width:15px');
        // Quita la fila del lote antes de Guardar — el cliente no se guarda
        // con esta carga.
        $oCabezera[12] = array('titulo' => 'Quitar', 'tipo' => 'enlace', 'metodo' => 1, 'mantiene' => 1,
            'ruta' => __IMG__ . 'botones/quitar.png', 'funcion' => 'eliminarFilaRespuestaBanco', 'parametro' => '1', 'atributos' => 'width:15px');

        $consulta = $this->db->query($query);
        $i = 0;
        $sumaBs = 0.0;
        $sumaUsd = 0.0;
        $ban = 0;
        $excluidas = 0;
        $oFila = array();

        foreach ($consulta->result() as $fila) {
            $color = '';
            $cerrar = '';
            $montoBsReal = (float) $fila->monto_bs;
            $montoUsd = (float) $fila->monto_usd;
            $contrato = $fila->contrato;
            $tipoTasaFila = $fila->tipo_tasa !== '' ? $fila->tipo_tasa : 'BCV';
            $tasaFila = (float) $fila->tasa_usada;
            $seExcluye = false;

            if ($contrato === self::SIN_SALDO) {
                // No hay ningún crédito con saldo pendiente al cual abonarle —
                // se excluye del lote (no bloquea al resto), queda para
                // revisión manual aparte. Se sigue mostrando su monto real en
                // la fila (informativo), pero no se suma al total a procesar.
                $seExcluye = true;
                $excluidas++;
                $color = '<font color=\'#b7791a\' size="2"><b>';
                $contrato = 'Sin saldo pendiente (excluida)';
                $cerrar = '</b></font>';
                $montoUsd = 0.0;
            } elseif ($contrato === '' || $contrato === null) {
                $ban++;
                $color = '<font color=\'#1964B5\' size="2"><b>';
                $contrato = 'Por Asignar';
                $cerrar = '</b></font>';
            }

            $descripcion = $this->construirDescripcion($montoBsReal, $tasaFila, $tipoTasaFila);
            if (!$seExcluye) {
                $sumaBs += $montoBsReal;
                $sumaUsd += $montoUsd;
            }
            $oFila[++$i] = array(
                '1' => $fila->oid,
                '2' => $fila->cedula,
                '3' => htmlspecialchars($fila->nombre),
                '4' => $color . $contrato . $cerrar,
                '5' => number_format($montoBsReal, 2, '.', ''),
                '6' => number_format($montoUsd, 4, '.', ''),
                '7' => $tipoTasaFila,
                '8' => htmlspecialchars($descripcion),
                '9' => $fila->oida,
                '10' => number_format($montoBsReal, 4, '.', ''),
                '11' => 'Cargar',
                '12' => 'Quitar',
            );
        }

        $leyendaTasa = ($tasaBcv > 0 && $tasaCobranza > 0)
            ? 'Fecha de nómina (' . htmlspecialchars($arch->fecha_nomina) . '): '
                . 'Dólar BCV <b>' . number_format($tasaBcv, 4, ',', '.') . '</b> &nbsp;|&nbsp; '
                . 'Dólar Cobranza <b>' . number_format($tasaCobranza, 4, ',', '.') . '</b> Bs./USD'
            : '<span style="color:#b70000;">Faltan tasas registradas (BCV y/o Cobranza) para este archivo.</span>';

        $leyendaExcluidas = $excluidas > 0
            ? '<br><span style="color:#b7791a;">' . $excluidas . ' fila(s) excluida(s) por no tener saldo '
                . 'pendiente en ningún crédito — no se procesarán, revíselas aparte.</span>'
            : '';

        $leyenda = '<br><center><h3 style="line-height:1.4;margin:8px 0;text-transform:none;letter-spacing:normal;">' . $leyendaTasa . '</h3>'
            . '<h2 style="margin:8px 0;">Total Bs.: ' . number_format($sumaBs, 2, ',', '.')
            . ' &nbsp;|&nbsp; Total USD: ' . number_format($sumaUsd, 4, ',', '.') . '</h2>'
            . $leyendaExcluidas . '</center>';

        if ($ban === 0 && $tasaBcv > 0 && $tasaCobranza > 0 && $arch && (int) $arch->estatus === 0) {
            $oTable = array(
                'Cabezera' => $oCabezera,
                'Cuerpo' => $oFila,
                'Origen' => 'json',
                'Editable' => 'GuardarLoteRespuestaBanco',
                'Parametros' => '1,2,4,8,10,9',
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
     * Grilla de solo lectura: cuentas rechazadas (informativo, no se procesa).
     */
    public function gridRechazos($oida) {
        $oida = (int) $oida;
        $query = 'SELECT oid, cedula, nombre, monto_bs, motivo
            FROM ' . self::TBL_DETALLE . "
            WHERE oida={$oida} AND seccion='R'
            ORDER BY nombre";

        $oCabezera[1] = array('titulo' => 'oid', 'oculto' => 1);
        $oCabezera[2] = array('titulo' => 'Cédula', 'atributos' => 'width:70px', 'buscar' => 1);
        $oCabezera[3] = array('titulo' => 'Cliente', 'atributos' => 'width:200px', 'buscar' => 1);
        $oCabezera[4] = array('titulo' => 'Monto Bs. (no cobrado)', 'atributos' => 'width:100px;text-align:center', 'total' => 1);
        $oCabezera[5] = array('titulo' => 'Motivo del banco', 'atributos' => 'width:200px');

        $consulta = $this->db->query($query);
        $i = 0;
        $oFila = array();
        foreach ($consulta->result() as $fila) {
            $oFila[++$i] = array(
                '1' => $fila->oid,
                '2' => $fila->cedula,
                '3' => htmlspecialchars($fila->nombre),
                '4' => number_format((float) $fila->monto_bs, 2, '.', ''),
                '5' => htmlspecialchars($fila->motivo),
            );
        }

        return json_encode(array(
            'Cabezera' => $oCabezera,
            'Cuerpo' => $oFila,
            'Origen' => 'json',
            'leyenda' => '<br><center><h3>Cuentas rechazadas por el banco (no se aplicó cobro)</h3></center>',
        ));
    }

    /**
     * Histórico/trazabilidad de TODAS las cargas de respuesta bancaria
     * (procesadas o no) — solo informativo, no permite editar ni guardar.
     */
    public function gridHistoricoArchivos() {
        // Solo archivos que se lograron LEER con éxito al menos una vez
        // (leerArchivoDetalle solo llena fecha_nomina cuando pasa la
        // validación de tasa BCV) — un intento fallido (ej. sin tasa BCV
        // registrada) no debe aparecer aquí hasta que se reintente y sí lea.
        $query = 'SELECT oid, archivo, banco, tipo_cobranza, fecha_nomina, monto, estatus, usuario, fecha_registro
            FROM ' . self::TBL_ARCHIVO . ' WHERE fecha_nomina IS NOT NULL ORDER BY fecha_registro DESC';
        $con = $this->db->query($query);

        $oCabezera[1] = array('titulo' => 'oid', 'oculto' => 1);
        $oCabezera[2] = array('titulo' => 'Fecha de Carga', 'atributos' => 'width:140px');
        $oCabezera[3] = array('titulo' => 'Archivo', 'atributos' => 'width:220px', 'buscar' => 1);
        $oCabezera[4] = array('titulo' => 'Banco', 'atributos' => 'width:100px', 'buscar' => 1);
        $oCabezera[5] = array('titulo' => 'Fecha Nómina', 'atributos' => 'width:100px');
        $oCabezera[6] = array('titulo' => 'Monto Aplicado (Bs.)', 'atributos' => 'width:130px;text-align:center', 'total' => 1);
        $oCabezera[7] = array('titulo' => 'Usuario', 'atributos' => 'width:90px');
        $oCabezera[8] = array('titulo' => 'Estatus', 'atributos' => 'width:100px');
        $oCabezera[9] = array('titulo' => 'Tipo', 'atributos' => 'width:90px');
        // Parámetros 1,4 (oid + banco): los oid de Venezuela vienen de otra
        // tabla y pueden coincidir con los de Banfan/Bicentenario, así que el
        // JS necesita el banco para consultar el detalle correcto.
        $oCabezera[10] = array('titulo' => 'Ver', 'tipo' => 'enlace', 'metodo' => 1, 'mantiene' => 1,
            'ruta' => __IMG__ . 'botones/agregar.png', 'funcion' => 'verHistoricoDetalleRB', 'parametro' => '1,4', 'atributos' => 'width:15px');

        $registros = array();
        foreach ($con->result() as $fila) {
            $registros[] = array(
                'oid' => $fila->oid,
                'fecha_registro' => $fila->fecha_registro,
                'archivo' => $fila->archivo,
                'banco' => $fila->banco,
                'fecha_nomina' => $fila->fecha_nomina,
                'monto' => $fila->monto,
                'usuario' => $fila->usuario,
                'estatus' => $fila->estatus,
                'tipo_cobranza' => $fila->tipo_cobranza,
            );
        }

        // Banco de Venezuela se carga por su propio flujo (t_archivos_venezuela,
        // "Archivos Pendientes (Venezuela)") — aquí solo se listan los ya
        // guardados (estatus=1). Esa tabla no registra usuario ni fecha de
        // nómina; "modificado" es la última actualización (el guardado del lote).
        if ($this->db->table_exists('t_archivos_venezuela')) {
            $conVe = $this->db->query(
                'SELECT oid, archivo, tipo_cobranza, monto, estatus, modificado
                FROM t_archivos_venezuela WHERE estatus=1'
            );
            foreach ($conVe->result() as $fila) {
                $registros[] = array(
                    'oid' => $fila->oid,
                    'fecha_registro' => $fila->modificado,
                    'archivo' => $fila->archivo,
                    'banco' => 'VENEZUELA',
                    'fecha_nomina' => null,
                    'monto' => $fila->monto,
                    'usuario' => '',
                    'estatus' => $fila->estatus,
                    'tipo_cobranza' => $fila->tipo_cobranza,
                );
            }
        }

        usort($registros, function ($a, $b) {
            return strcmp($b['fecha_registro'], $a['fecha_registro']);
        });

        $i = 0;
        $oFila = array();
        foreach ($registros as $fila) {
            $estatusTxt = ((int) $fila['estatus'] === 1)
                ? '<font color=\'#2e7d32\' size="2"><b>Procesado</b></font>'
                : '<font color=\'#b7791a\' size="2"><b>Pendiente</b></font>';
            $tipoTxt = (strtoupper(trim($fila['tipo_cobranza'])) === 'MOROSO')
                ? '<font color=\'#9a6a1f\' size="2"><b>Moroso</b></font>'
                : 'Regular';
            $oFila[++$i] = array(
                '1' => $fila['oid'],
                '2' => date('d/m/Y H:i:s', strtotime($fila['fecha_registro'])),
                '3' => htmlspecialchars($fila['archivo']),
                '4' => htmlspecialchars($fila['banco']),
                '5' => $fila['fecha_nomina'] ? date('d/m/Y', strtotime($fila['fecha_nomina'])) : '',
                '6' => number_format((float) $fila['monto'], 2, '.', ''),
                '7' => htmlspecialchars($fila['usuario']),
                '8' => $estatusTxt,
                '9' => $tipoTxt,
                '10' => 'Ver',
            );
        }

        return json_encode(array(
            'Cabezera' => $oCabezera,
            'Cuerpo' => $oFila,
            'Origen' => 'json',
            'leyenda' => '<br><center><h3>Histórico de cargas de respuesta bancaria (informativo)</h3></center>',
        ));
    }

    /**
     * Detalle de una carga específica — SOLO LECTURA (sin botón Guardar ni
     * enlace de asignar contrato), pensado para el histórico/trazabilidad.
     */
    public function gridHistoricoDetalle($oida) {
        $oida = (int) $oida;
        $arch = $this->getArchivo($oida);
        $tasaBcv = $arch ? (float) $arch->precio_bcv : 0;
        $tasaCobranza = $arch ? (float) $arch->precio_cobranza : 0;

        $query = 'SELECT oid, cedula, nombre, contrato, monto_bs, monto_usd, tipo_tasa
            FROM ' . self::TBL_DETALLE . "
            WHERE oida={$oida} AND seccion='A'
            ORDER BY nombre";

        $oCabezera[1] = array('titulo' => 'oid', 'oculto' => 1);
        $oCabezera[2] = array('titulo' => 'Cédula', 'atributos' => 'width:70px', 'buscar' => 1);
        $oCabezera[3] = array('titulo' => 'Cliente', 'atributos' => 'width:200px', 'buscar' => 1);
        $oCabezera[4] = array('titulo' => 'Contrato', 'atributos' => 'width:90px');
        $oCabezera[5] = array('titulo' => 'Monto Bs.', 'atributos' => 'width:80px;text-align:center', 'total' => 1);
        $oCabezera[6] = array('titulo' => 'Monto USD', 'atributos' => 'width:80px;text-align:center', 'total' => 1);
        $oCabezera[7] = array('titulo' => 'Tasa', 'atributos' => 'width:90px;text-align:center');

        $consulta = $this->db->query($query);
        $i = 0;
        $oFila = array();
        foreach ($consulta->result() as $fila) {
            $contrato = $fila->contrato;
            $color = '';
            $cerrar = '';
            if ($contrato === self::SIN_SALDO) {
                $color = '<font color=\'#b7791a\' size="2"><b>';
                $contrato = 'Sin saldo pendiente (excluida)';
                $cerrar = '</b></font>';
            } elseif ($contrato === '' || $contrato === null) {
                $color = '<font color=\'#1964B5\' size="2"><b>';
                $contrato = 'No asignado';
                $cerrar = '</b></font>';
            }
            $oFila[++$i] = array(
                '1' => $fila->oid,
                '2' => $fila->cedula,
                '3' => htmlspecialchars($fila->nombre),
                '4' => $color . $contrato . $cerrar,
                '5' => number_format((float) $fila->monto_bs, 2, '.', ''),
                '6' => number_format((float) $fila->monto_usd, 4, '.', ''),
                '7' => $fila->tipo_tasa !== '' ? $fila->tipo_tasa : 'BCV',
            );
        }

        $leyendaTasa = ($tasaBcv > 0 || $tasaCobranza > 0)
            ? 'Fecha de nómina (' . ($arch ? htmlspecialchars($arch->fecha_nomina) : '') . '): '
                . 'Dólar BCV <b>' . number_format($tasaBcv, 4, ',', '.') . '</b> &nbsp;|&nbsp; '
                . 'Dólar Cobranza <b>' . number_format($tasaCobranza, 4, ',', '.') . '</b> Bs./USD'
            : '';

        return json_encode(array(
            'Cabezera' => $oCabezera,
            'Cuerpo' => $oFila,
            'Origen' => 'json',
            'leyenda' => '<br><center><h3>' . $leyendaTasa . '</h3></center>',
        ));
    }

    /**
     * Busca una cédula en TODAS las cargas de respuesta bancaria (aplicadas
     * y rechazadas, de cualquier archivo/banco) — solo informativo.
     */
    public function gridBuscarCedula($cedula) {
        $cedula = (int) $cedula;

        $oCabezera[1] = array('titulo' => 'oid', 'oculto' => 1);
        $oCabezera[2] = array('titulo' => 'Fecha de Carga', 'atributos' => 'width:130px');
        $oCabezera[3] = array('titulo' => 'Archivo', 'atributos' => 'width:200px');
        $oCabezera[4] = array('titulo' => 'Banco', 'atributos' => 'width:90px');
        $oCabezera[5] = array('titulo' => 'Cliente', 'atributos' => 'width:180px');
        $oCabezera[6] = array('titulo' => 'Resultado', 'atributos' => 'width:220px');
        $oCabezera[7] = array('titulo' => 'Monto Bs.', 'atributos' => 'width:90px;text-align:center', 'total' => 1);

        $oFila = array();
        if ($cedula > 0) {
            $query = 'SELECT d.oid, d.cedula, d.nombre, d.contrato, d.monto_bs, d.seccion, d.motivo,
                    a.archivo, a.banco, a.fecha_registro
                FROM ' . self::TBL_DETALLE . ' d
                INNER JOIN ' . self::TBL_ARCHIVO . " a ON a.oid = d.oida
                WHERE d.cedula = {$cedula}
                ORDER BY a.fecha_registro DESC";
            $con = $this->db->query($query);

            $i = 0;
            foreach ($con->result() as $fila) {
                if ($fila->seccion === 'R') {
                    $resultado = '<font color=\'#b70000\' size="2"><b>Rechazado: '
                        . htmlspecialchars($fila->motivo) . '</b></font>';
                } elseif ($fila->contrato === self::SIN_SALDO) {
                    $resultado = '<font color=\'#b7791a\' size="2"><b>Sin saldo pendiente (excluido)</b></font>';
                } elseif ($fila->contrato === '' || $fila->contrato === null) {
                    $resultado = '<font color=\'#1964B5\' size="2"><b>Pendiente de asignar</b></font>';
                } else {
                    $resultado = '<font color=\'#2e7d32\' size="2"><b>Aplicado a '
                        . htmlspecialchars($fila->contrato) . '</b></font>';
                }
                $oFila[++$i] = array(
                    '1' => $fila->oid,
                    '2' => date('d/m/Y H:i', strtotime($fila->fecha_registro)),
                    '3' => htmlspecialchars($fila->archivo),
                    '4' => htmlspecialchars($fila->banco),
                    '5' => htmlspecialchars($fila->nombre),
                    '6' => $resultado,
                    '7' => number_format((float) $fila->monto_bs, 2, '.', ''),
                );
            }
        }

        return json_encode(array(
            'Cabezera' => $oCabezera,
            'Cuerpo' => $oFila,
            'Origen' => 'json',
            'leyenda' => '<br><center><h3>' . count($oFila) . ' resultado(s) para la cédula ' . $cedula . '</h3></center>',
        ));
    }

    /**
     * Resuelve el "mes de cobro" real a partir de la FECHA NOMINA del banco.
     * Si el día es 1, la nómina corresponde al cierre del mes ANTERIOR (ej.
     * "01/07/2026" = nómina de junio, pagada el primer día de julio) — mismo
     * criterio que ya usa `GtxtGenerator::normalizarFechaCorte()` para
     * "MENSUAL". Cualquier otro día se toma tal cual (mes/año de la fecha).
     *
     * @return array [anio, mes]
     */
    private function resolverMesDeCobro($fechaNomina) {
        $ts = strtotime($fechaNomina);
        if ($ts === false) {
            return array(0, 0);
        }
        $dia = (int) date('j', $ts);
        if ($dia <= 1) {
            $ts = strtotime('-1 month', mktime(0, 0, 0, (int) date('n', $ts), 1, (int) date('Y', $ts)));
        }
        return array((int) date('Y', $ts), (int) date('n', $ts));
    }

    private function construirDescripcion($montoBs, $tasa, $tipoTasa = 'BCV') {
        $tipoTasa = $tipoTasa !== '' ? $tipoTasa : 'BCV';
        return number_format($montoBs, 2, ',', '.') . ' BS REF ' . number_format($tasa, 2, ',', '.') . ' ' . $tipoTasa;
    }

    /**
     * Día de cobro (día de `fecha_inicio_cobro`) por cada contrato de la
     * lista dada, en una sola consulta — para construir la FECHA CUOTA real.
     *
     * @param array $creditoIds contrato_id (string), ya filtrados/únicos
     * @return array contrato_id => int (día 1-31)
     */
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

    /**
     * Rechazados de este archivo con contrato ya resuelto (no ambiguos, no
     * SIN_SALDO) — candidatos a generar un registro en 0 en t_lista_cobros
     * (HU: dejar constancia de que el banco no tenía disponibilidad ese día).
     *
     * Regla de oro: si esa misma cédula SÍ tiene al menos una línea aplicada
     * ('A') en este mismo archivo (le cobraron algo, aunque sea 1 Bs.), NO se
     * genera el registro en 0 — el banco puede reportar la misma cédula tanto
     * en aplicadas como en rechazos dentro de un mismo archivo (ej. picada en
     * varias líneas, unas sí debitaron y otras no). Solo se registra en 0
     * cuando esa cédula no tuvo NINGÚN cobro real ese día.
     *
     * @return array [['cedula'=>string,'contrato'=>string,'motivo'=>string], ...]
     */
    private function obtenerRechazadosConContrato($oida) {
        $con = $this->db->query(
            'SELECT cedula, contrato, motivo FROM ' . self::TBL_DETALLE
            . ' WHERE oida=' . (int) $oida . " AND seccion='R'"
            . ' AND contrato IS NOT NULL AND contrato != \'\''
            . ' AND contrato != ' . $this->db->escape(self::SIN_SALDO)
            . ' AND cedula NOT IN (SELECT cedula FROM ' . self::TBL_DETALLE
            . ' WHERE oida=' . (int) $oida . " AND seccion='A')"
        );
        $out = array();
        foreach ($con->result() as $fila) {
            $out[] = array('cedula' => $fila->cedula, 'contrato' => $fila->contrato, 'motivo' => trim((string) $fila->motivo));
        }
        return $out;
    }

    /**
     * Combina el día real de cobro del contrato con el mes/año que se está
     * cobrando (según la nómina del banco), respetando el último día válido
     * del mes si el día no existe (ej. día 31 en un mes de 30 días).
     * Si no hay día de contrato disponible, usa la fecha del banco (respaldo).
     */
    private function construirFechaCuota($diaCobro, $anio, $mes, $fechaRespaldo) {
        if (!$diaCobro || $anio <= 0 || $mes <= 0) {
            return $fechaRespaldo;
        }
        $ultimoDia = (int) date('t', mktime(0, 0, 0, $mes, 1, $anio));
        $dia = min($diaCobro, $ultimoDia);
        return sprintf('%04d-%02d-%02d', $anio, $mes, $dia);
    }

    /**
     * Aplica los cobros en USD a t_lista_cobros, en una sola operación masiva.
     *
     * @param array $filas filas del TGrid: [oid, cedula, contrato, descripcion, monto_bs, oida]
     *   El `oid` (fila 1) es la fila real de t_cargar_respuesta_banco — se usa
     *   para consultar la tasa/tipo YA resuelta en la lectura (regla de oro:
     *   BCV para cédulas con rechazo por disponibilidad en este archivo,
     *   Cobranza para el resto), en vez de confiar en un valor enviado por el
     *   navegador o de aplicar una sola tasa uniforme a todo el archivo.
     * @return array ['ok'=>bool,'msg'=>string]
     */
    public function guardarLote($filas, $usuarioId) {
        if (empty($filas) || !is_array($filas)) {
            return array('ok' => false, 'msg' => 'No hay filas para procesar.');
        }

        $oida = (int) $filas[0][5];
        $arch = $this->getArchivo($oida);
        if (!$arch) {
            return array('ok' => false, 'msg' => 'Archivo no encontrado.');
        }
        if ((int) $arch->estatus === 1) {
            return array('ok' => false, 'msg' => 'Este archivo ya fue procesado anteriormente. No se volvió a cargar.');
        }

        if ((float) $arch->precio_bcv <= 0 || (float) $arch->precio_cobranza <= 0) {
            return array('ok' => false, 'msg' => 'Faltan tasas registradas (BCV y/o Cobranza) para este archivo.');
        }

        // Tasa/tipo real por fila, tal como se resolvió en la lectura del
        // archivo — autoritativo desde la BD, no desde lo que llegó del grid.
        $oidsFilas = array();
        foreach ($filas as $fila) {
            $oidsFilas[] = (int) $fila[0];
        }
        $tasaPorOid = array();
        if (!empty($oidsFilas)) {
            $con = $this->db->query(
                'SELECT oid, tipo_tasa, tasa_usada FROM ' . self::TBL_DETALLE
                . ' WHERE oid IN (' . implode(',', array_unique($oidsFilas)) . ')'
            );
            foreach ($con->result() as $r) {
                $tasaPorOid[(int) $r->oid] = array(
                    'tipo' => $r->tipo_tasa !== '' ? $r->tipo_tasa : 'BCV',
                    'precio' => (float) $r->tasa_usada,
                );
            }
        }

        foreach ($filas as $fila) {
            $creditoId = trim(strip_tags($fila[2]));
            if (stripos($creditoId, 'Sin saldo pendiente') !== false) {
                continue;
            }
            if ($creditoId === '' || stripos($creditoId, 'Por Asignar') !== false) {
                return array('ok' => false, 'msg' => 'Hay filas sin contrato asignado.');
            }
        }

        // FECHA DESCUENTO (farc): la fecha literal que reporta el banco, sin
        // ajustar — esa sí ocurrió exactamente ese día.
        $fechaDescuento = $arch->fecha_nomina;

        // Mes de cobro (mesp/anop, usado para construir la FECHA CUOTA y para
        // otros reportes que filtran "mes de cobro"): cuando la FECHA NOMINA
        // cae el día 1, representa el cierre del ciclo del mes ANTERIOR (ej.
        // "FECHA NOMINA: 01/07/2026" es la nómina de junio, pagada el primer
        // día de julio) — mismo criterio que "MENSUAL" ya usa el generador de
        // TXT (normalizarFechaCorte: día <= 1 se resuelve al mes anterior).
        list($anop, $mesp) = $this->resolverMesDeCobro($fechaDescuento);
        $banco = strtoupper(trim($arch->banco));

        // Regla de oro: archivo MOROSO fuerza la FECHA CUOTA al día 01 del mes
        // de cobro (ignora el día real de cobro del contrato). Un archivo
        // REGULAR mantiene el comportamiento de siempre.
        $esMoroso = isset($arch->tipo_cobranza) && strtoupper(trim($arch->tipo_cobranza)) === 'MOROSO';

        $rechazadosConContrato = $this->obtenerRechazadosConContrato($oida);

        $creditoIds = array();
        foreach ($filas as $fila) {
            $creditoId = trim(strip_tags($fila[2]));
            if ($creditoId !== '' && stripos($creditoId, 'Sin saldo pendiente') === false) {
                $creditoIds[] = $creditoId;
            }
        }
        foreach ($rechazadosConContrato as $rechazo) {
            $creditoIds[] = $rechazo['contrato'];
        }
        $diasCobroPorContrato = $this->obtenerDiasCobroPorContrato($creditoIds);

        // Motor compartido (también usado por Venezuela, mcargavenezuela.php)
        // que reparte el pago entre TODOS los contratos activos de la
        // cédula, ordenados del más antiguo al más reciente — no solo el
        // contrato asignado en pantalla. Modo MOROSO/REGULAR = el mismo
        // "Tipo de Cobranza" ya elegido al subir el archivo.
        // Loader::model() solo engancha el modelo cargado al objeto super de CI
        // (get_instance()->MCascada), no a $this de este modelo — por eso se
        // accede vía get_instance() y no vía $this->MCascada.
        $this->load->model('cargar/mcascadapagos', 'MCascada');
        $CI =& get_instance();
        $modoCascada = $esMoroso ? 'MOROSO' : 'REGULAR';

        $values = array();
        $montoTotalBs = 0.0;
        $montoTotalUsd = 0.0;
        $usoBcv = false;
        $usoCobranza = false;

        foreach ($filas as $fila) {
            $oidFila = (int) $fila[0];
            $documentoId = trim($fila[1]);
            $creditoId = trim(strip_tags($fila[2]));
            if (stripos($creditoId, 'Sin saldo pendiente') !== false) {
                // Excluida del lote: cliente sin ningún crédito con saldo
                // pendiente al momento de asignar (ya pagado/sobrepagado).
                // No se procesa ni bloquea el resto del lote.
                continue;
            }
            $montoBs = (float) $fila[4];

            // Tasa/tipo autoritativa desde la BD (resuelta en la lectura),
            // nunca desde lo que el navegador envió. Si por algún motivo no
            // se encuentra (no debería pasar), se usa BCV del archivo como
            // respaldo para no bloquear el guardado.
            if (isset($tasaPorOid[$oidFila]) && $tasaPorOid[$oidFila]['precio'] > 0) {
                $tipoTasaFila = $tasaPorOid[$oidFila]['tipo'];
                $tasaFila = $tasaPorOid[$oidFila]['precio'];
            } else {
                $tipoTasaFila = 'BCV';
                $tasaFila = (float) $arch->precio_bcv;
            }
            $tipoTasaFila === 'BCV' ? ($usoBcv = true) : ($usoCobranza = true);

            $montoUsd = round($montoBs / $tasaFila, 4);
            $montoTotalBs += $montoBs;
            $montoTotalUsd += $montoUsd;

            // FECHA CUOTA: día real de cobro del contrato (fecha_inicio_cobro)
            // combinado con el mes/año que se está cobrando según la nómina del
            // banco — NO la fecha del banco. FECHA DESCUENTO (farc) sí es la
            // fecha del banco (fecha_nomina), sin cambios. Si el archivo es
            // MOROSO, se fuerza al día 01 (regla de oro).
            $diaCobro = $esMoroso ? 1 : (isset($diasCobroPorContrato[$creditoId]) ? $diasCobroPorContrato[$creditoId] : null);
            $fechaCuota = $this->construirFechaCuota($diaCobro, $anop, $mesp, $fechaDescuento);

            $reparto = $CI->MCascada->distribuirPago($documentoId, $montoUsd, $modoCascada, $fechaCuota, $banco);
            $asignaciones = !empty($reparto['asignaciones']) ? $reparto['asignaciones'] : array(
                array('contrato_id' => $creditoId, 'monto_usd' => $montoUsd),
            );
            // La cascada topa cada contrato en su objetivo (vencido/cuota del
            // mes) y devuelve lo que no pudo ubicar — ese dinero el banco SÍ
            // lo cobró, no se puede perder. Se abona de más al último contrato
            // del reparto (mismo criterio que distribuirEntreContratosMultiples).
            $asignaciones = $CI->MCascada->abonarSobranteAlUltimo($asignaciones, $montoUsd);

            foreach ($asignaciones as $asig) {
                $mes = 'RESPUESTA ' . $banco . ':' . $asig['contrato_id'];
                $desc = $this->construirDescripcion($montoBs, $tasaFila, $tipoTasaFila);
                if ($asig['contrato_id'] !== $creditoId) {
                    $desc .= ' | Repartido por antigüedad (contrato asignado en pantalla: ' . $creditoId . ')';
                }

                $values[] = '(' . $this->db->escape($documentoId) . ','
                    . $this->db->escape($asig['contrato_id']) . ','
                    . $this->db->escape($mes) . ','
                    . $this->db->escape($desc) . ','
                    . $this->db->escape($fechaCuota) . ','
                    . $this->db->escape($asig['monto_usd']) . ','
                    . $this->db->escape($fechaDescuento) . ','
                    . (int) $mesp . ','
                    . (int) $anop . ',25,'
                    . (int) $usuarioId . ')';
            }
        }

        // Rechazados con contrato resuelto: registro en 0 para dejar
        // constancia en la bitácora del cliente de que el banco intentó
        // cobrarle ese día pero no tenía disponibilidad.
        $countRechazados = 0;
        foreach ($rechazadosConContrato as $rechazo) {
            $creditoId = $rechazo['contrato'];
            $motivo = trim($rechazo['motivo']);
            $desc = '(POR DISPONIBLE)' . ($motivo !== '' ? ' - ' . $motivo : '');
            $mes = 'RESPUESTA ' . $banco . ':' . $creditoId;

            $diaCobro = $esMoroso ? 1 : (isset($diasCobroPorContrato[$creditoId]) ? $diasCobroPorContrato[$creditoId] : null);
            $fechaCuota = $this->construirFechaCuota($diaCobro, $anop, $mesp, $fechaDescuento);

            $values[] = '(' . $this->db->escape($rechazo['cedula']) . ','
                . $this->db->escape($creditoId) . ','
                . $this->db->escape($mes) . ','
                . $this->db->escape($desc) . ','
                . $this->db->escape($fechaCuota) . ','
                . $this->db->escape(0) . ','
                . $this->db->escape($fechaDescuento) . ','
                . (int) $mesp . ','
                . (int) $anop . ',25,'
                . (int) $usuarioId . ')';
            $countRechazados++;
        }

        if (empty($values)) {
            return array('ok' => false, 'msg' => 'No hay filas para registrar.');
        }

        // Marca el archivo como procesado ANTES de insertar, condicionado a que
        // siga en estatus=0: evita doble-procesamiento por doble clic/reintento.
        $this->db->query('UPDATE ' . self::TBL_ARCHIVO . ' SET estatus=1 WHERE oid=' . $oida . ' AND estatus=0');
        if ($this->db->affected_rows() !== 1) {
            return array('ok' => false, 'msg' => 'Este archivo ya fue procesado anteriormente. No se volvió a cargar.');
        }

        $okInsert = $this->insertarEnBloques(
            'INSERT INTO t_lista_cobros (documento_id,credito_id,mes,descripcion,fecha,monto,farc,mesp,anop,moda,usua) VALUES ',
            $values
        );
        if (!$okInsert) {
            $this->db->query('UPDATE ' . self::TBL_ARCHIVO . ' SET estatus=0 WHERE oid=' . $oida);
            return array('ok' => false, 'msg' => 'No se pudo cargar datos en lista de cobros.');
        }

        $countAplicados = count($values) - $countRechazados;
        $msjRechazados = $countRechazados > 0
            ? ' y ' . $countRechazados . ' rechazado(s) en 0 (sin disponibilidad)'
            : '';

        $tasasUsadas = array();
        if ($usoBcv) {
            $tasasUsadas[] = 'BCV ' . number_format((float) $arch->precio_bcv, 4, ',', '.');
        }
        if ($usoCobranza) {
            $tasasUsadas[] = 'Cobranza ' . number_format((float) $arch->precio_cobranza, 4, ',', '.');
        }

        return array(
            'ok' => true,
            'msg' => 'Se registraron ' . $countAplicados . ' cobro(s) aplicado(s)' . $msjRechazados . '. Total Bs.: '
                . number_format($montoTotalBs, 2, ',', '.') . ' — Total USD: '
                . number_format($montoTotalUsd, 4, ',', '.') . ' (tasa(s) '
                . implode(' / ', $tasasUsadas) . ').',
        );
    }
}
