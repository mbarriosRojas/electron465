<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Generador TXT / datos plantilla Banco de Venezuela.
 * Réplica del macro GenerarTxt del archivo "Upload cobros Masivo corregido.xls".
 */
class GtxtVenezuelaGenerator {

    /** @var object */
    public $db;

    public function __construct($db = null) {
        if ($db !== null) {
            $this->db = $db;
        } else {
            $CI =& get_instance();
            $this->db = $CI->db;
        }
    }

    /**
     * @param array $param filtros + cabecera + precio_dolar
     * @return array
     */
    public function generar($param) {
        $result = array(
            'ok' => false,
            'cant' => 0,
            'mont' => 0.0,
            'path' => '',
            'error' => '',
            'filas_xls' => array(),
            'cabecera_xls' => array(),
        );

        $precioDolar = isset($param['precio_dolar']) ? (float) $param['precio_dolar'] : 0;
        if ($precioDolar <= 0) {
            $result['error'] = 'No hay precio del dólar vigente. Regístrelo en Panel → Precio del Dólar.';
            return $result;
        }

        $cabecera = $this->normalizarCabecera(isset($param['cabecera']) ? $param['cabecera'] : array());
        $errCab = $this->validarCabecera($cabecera);
        if ($errCab !== '') {
            $result['error'] = $errCab;
            return $result;
        }

        $filtroCliente = isset($param['filtro_cliente']) ? strtoupper(trim($param['filtro_cliente'])) : 'ACTIVOS';
        if (!in_array($filtroCliente, array('ACTIVOS', 'MOROSOS'), true)) {
            $filtroCliente = 'ACTIVOS';
        }
        $fechaCorteRaw = isset($param['fecha']) ? trim((string) $param['fecha']) : '';
        if ($fechaCorteRaw === '') {
            $result['error'] = 'Indique "Hasta la Fecha" antes de generar.';
            return $result;
        }
        $cutoff = $this->normalizarFechaCorte($fechaCorteRaw);
        if ($cutoff === null) {
            $result['error'] = 'Fecha de corte inválida: ' . $fechaCorteRaw;
            return $result;
        }
        // "Activos": solo la cuota del mes/año de $cutoff. "Morosos": deuda de
        // meses ANTERIORES (nunca incluye el mes elegido) — mismo criterio que
        // el generador de los otros bancos.
        $cutoffConsulta = ($filtroCliente === 'MOROSOS') ? $this->finDeMesAnterior($cutoff) : $cutoff;

        $contratos = $this->consultarContratos($param, $cutoffConsulta);
        if (empty($contratos)) {
            $result['error'] = ($filtroCliente === 'MOROSOS')
                ? 'No se encontraron contratos morosos VENEZUELA (deuda de meses anteriores al seleccionado).'
                : 'No se encontraron contratos activos VENEZUELA con cuota en el mes seleccionado.';
            return $result;
        }

        // Referencia del débito: un único valor fijo para todo el lote (código
        // del cliente recaudador ante BDV, ej. "465"), NO por contrato —
        // verificado contra un archivo real aceptado por el banco donde las
        // 14 líneas traían la misma referencia.
        $refDebitoLote = isset($param['referencia_debito']) ? trim((string) $param['referencia_debito']) : '';

        $filasXls = array();
        $filasTxt = array();
        $montoTotal = 0.0;

        foreach ($contratos as $row) {
            $montoUsd = ($filtroCliente === 'MOROSOS')
                ? $this->calcularMontoMorosos($row, $cutoffConsulta)
                : $this->calcularMontoActivos($row, $cutoff);
            if ($montoUsd <= 0) {
                continue;
            }
            $montoBs = round($montoUsd * $precioDolar, 2);
            if ($montoBs <= 0) {
                continue;
            }

            $cuenta = $this->limpiarCuenta($row->cuenta_1);
            if (strlen($cuenta) !== 20) {
                continue;
            }

            $doc = $this->parseDocumento($row->documento_id, isset($row->nacionalidad) ? $row->nacionalidad : 'V');
            $tipoCuenta = $this->mapTipoCuenta(isset($row->tipo_cuenta_1) ? $row->tipo_cuenta_1 : '');
            $refDebito = ($refDebitoLote !== '') ? $refDebitoLote : $this->referenciaDebito($row->contrato_id, $row->documento_id);
            $codigoCliente = preg_replace('/\D/', '', (string) $row->documento_id);
            // El proceso manual histórico (aceptado por BDV) nunca envía el
            // correo del cliente — se deja siempre en ceros para igualar ese
            // comportamiento comprobado.
            $email = '';

            $fila = array(
                'letra' => $doc['letra'],
                'documento' => $doc['numero'],
                'codigo_cliente' => $codigoCliente,
                'tipo_pago' => 'C',
                'tipo_cuenta' => $tipoCuenta,
                'cuenta' => $cuenta,
                'tdc' => '',
                'cvv' => '',
                'expiracion' => '',
                'fecha_debito' => $cabecera['fecha_valor'],
                'monto' => $montoBs,
                'referencia' => $refDebito,
                'email' => $email,
            );

            $errFila = $this->validarFila($fila, $cabecera);
            if ($errFila !== '') {
                $result['error'] = $errFila;
                return $result;
            }

            $filasXls[] = $this->filaParaXls($fila);
            $filasTxt[] = $this->filaParaTxt($fila);
            $montoTotal += $montoBs;
        }

        if (empty($filasTxt)) {
            $result['error'] = 'Ningún contrato generó montos en bolívares (revise cuentas de 20 dígitos).';
            return $result;
        }

        $header = $this->armarEncabezadoTxt($cabecera, count($filasTxt), $montoTotal);
        $lineas = array($header);
        foreach ($filasTxt as $det) {
            $lineas[] = $det;
        }
        $contenido = implode("\r\n", $lineas) . "\r\n";

        $md5Nom = isset($param['md5_nom']) ? $param['md5_nom'] : md5(uniqid('bdv', true));
        $baseDir = isset($param['base_dir']) ? rtrim($param['base_dir'], '/') . '/' : '';
        $txtPath = $baseDir . 'tmp/cobranza_venezuela/' . $md5Nom . '.txt';

        if (!is_dir(dirname($txtPath))) {
            @mkdir(dirname($txtPath), 0775, true);
        }
        if (@file_put_contents($txtPath, $contenido) === false) {
            $result['error'] = 'No se pudo escribir el archivo TXT.';
            return $result;
        }

        $result['ok'] = true;
        $result['cant'] = count($filasTxt);
        $result['mont'] = $montoTotal;
        $result['path'] = $txtPath;
        $result['filas_xls'] = $filasXls;
        $result['cabecera_xls'] = $this->cabeceraParaXls($cabecera);
        return $result;
    }

    private function consultarContratos($param, $cutoffConsulta) {
        $banco = 'VENEZUELA';
        $empresa = isset($param['empresa']) ? (int) $param['empresa'] : 0;
        $periodicidad = isset($param['periodicidad']) ? (string) $param['periodicidad'] : '4';
        $formaContrato = isset($param['fcontrato']) ? (int) $param['fcontrato'] : 0;
        $nomina = isset($param['nomina']) ? trim($param['nomina']) : '';

        $bancoEsc = $this->db->escape(strtoupper(trim($banco)));
        $where = array();
        $where[] = 'UPPER(TRIM(c.cobrado_en)) = ' . $bancoEsc;
        $where[] = 'c.marca_consulta != 6';
        $where[] = 'c.cantidad != 0';
        $where[] = 'c.fecha_inicio_cobro <= ' . $this->db->escape($cutoffConsulta);
        $where[] = 'c.forma_contrato = ' . (int) $formaContrato;
        $where[] = "p.cuenta_1 IS NOT NULL";
        $where[] = "p.cuenta_1 != ''";
        $where[] = "p.cuenta_1 != '----------'";

        if ($empresa === 0 || $empresa === 1) {
            $where[] = 'c.empresa = ' . (int) $empresa;
        }
        $where[] = $this->condicionPeriocidadMensual($periodicidad);
        if ($nomina !== '' && $nomina !== 'VARIAS') {
            $where[] = 'c.nomina_procedencia = ' . $this->db->escape($nomina);
        }

        $sql = 'SELECT c.credito_id, c.contrato_id, c.documento_id, c.fecha_inicio_cobro, c.numero_cuotas,
            c.periocidad, c.monto_cuota, c.monto_total, c.empresa,
            p.cuenta_1, p.tipo_cuenta_1, p.nacionalidad, p.correo,
            IFNULL(SUM(lc.monto), 0) AS total_pagado,
            (c.monto_total - IFNULL(SUM(lc.monto), 0)) AS resta
            FROM t_clientes_creditos c
            INNER JOIN t_personas p ON p.documento_id = c.documento_id
            LEFT JOIN t_lista_cobros lc ON lc.credito_id = c.contrato_id
            WHERE ' . implode(' AND ', $where) . '
            GROUP BY c.contrato_id, c.credito_id, c.documento_id, c.fecha_inicio_cobro, c.numero_cuotas,
                c.periocidad, c.monto_cuota, c.monto_total, c.empresa,
                p.cuenta_1, p.tipo_cuenta_1, p.nacionalidad, p.correo
            HAVING resta > 0
            ORDER BY c.documento_id, c.contrato_id';

        $rs = $this->db->query($sql);
        if (!$rs || $rs->num_rows() === 0) {
            return array();
        }
        return $rs->result();
    }

    /**
     * Condición WHERE para la periodicidad mensual. En la práctica, al crear
     * el crédito casi nunca se usa el código específico "Mensual - 15" (5) —
     * el operador elige el genérico "MENSUAL" (4) sin importar el día real de
     * cobro, que solo queda registrado en fecha_inicio_cobro. Por eso:
     *   - "MENSUAL (día 15)" (5): periocidad 4 o 5 con día 15 de cobro.
     *   - "MENSUAL (día 25)" (9): periocidad 4 o 9 con día 25 de cobro.
     *   - "MENSUAL (día 30)" (6): periocidad 4 o 6 con día 30 de cobro.
     *   - "MENSUAL" (4): periocidad 4 EXCLUYENDO los días 15, 25 y 30 (que ya
     *     tienen su propia opción, para no duplicar contratos entre ambas).
     * El resto de periodicidades (semanal, quincenal, etc.) no tiene este
     * cruce código/día y se filtra por igualdad simple.
     */
    private function condicionPeriocidadMensual($periodicidad) {
        if ($periodicidad === '5') {
            return '((c.periocidad = 5) OR (c.periocidad = 4 AND DAY(c.fecha_inicio_cobro) = 15))';
        }
        if ($periodicidad === '9') {
            return '((c.periocidad = 9) OR (c.periocidad = 4 AND DAY(c.fecha_inicio_cobro) = 25))';
        }
        if ($periodicidad === '6') {
            return '((c.periocidad = 6) OR (c.periocidad = 4 AND DAY(c.fecha_inicio_cobro) = 30))';
        }
        if ($periodicidad === '4') {
            return '(c.periocidad = 4 AND DAY(c.fecha_inicio_cobro) NOT IN (15, 25, 30))';
        }
        return 'c.periocidad = ' . (int) $periodicidad;
    }

    /**
     * Modo "Activos": cobra ÚNICAMENTE la cuota programada dentro del mes/año
     * de $cutoff — nunca acumula atrasos de otros meses. Si esa cuota puntual
     * ya está cubierta por lo pagado hasta ese punto del cronograma, o el
     * contrato no tiene ninguna cuota programada ese mes (o ya venció), el
     * monto es 0 (se excluye del archivo). Portado 1:1 desde el criterio ya
     * validado en GtxtGenerator (Bicentenario/Banfan), sin depender de él.
     */
    private function calcularMontoActivos($row, $cutoff) {
        $montoCuota = (float) $row->monto_cuota;
        if ($montoCuota <= 0) {
            return 0;
        }

        $anoMesBuscado = substr($cutoff, 0, 7);
        $totalPagado = (float) $row->total_pagado;
        $plan = $this->gPlanCobro($row->fecha_inicio_cobro, (int) $row->numero_cuotas, (string) $row->periocidad);
        if (empty($plan)) {
            return 0;
        }

        $ultimaCuota = end($plan);
        $fechaFinContrato = $this->parseFechaPlan($ultimaCuota['fecha']);
        if ($fechaFinContrato !== null && date('Y-m', $fechaFinContrato) < $anoMesBuscado) {
            return 0;
        }

        $restantePago = $totalPagado;

        foreach ($plan as $cuota) {
            $fechaCuota = $this->parseFechaPlan($cuota['fecha']);
            if ($fechaCuota === null) {
                continue;
            }
            $anoMesCuota = date('Y-m', $fechaCuota);

            $aplicado = 0.0;
            if ($restantePago > 0) {
                if ($restantePago >= $montoCuota) {
                    $aplicado = $montoCuota;
                    $restantePago -= $montoCuota;
                } else {
                    $aplicado = $restantePago;
                    $restantePago = 0;
                }
            }

            if ($anoMesCuota === $anoMesBuscado) {
                return round(max($montoCuota - $aplicado, 0), 2);
            }
            if ($anoMesCuota > $anoMesBuscado) {
                break;
            }
        }

        return 0;
    }

    /**
     * Modo "Morosos": acumula lo vencido y no pagado ÚNICAMENTE hasta
     * $cutoffMesAnterior (fin del mes anterior al seleccionado — el llamador
     * nunca debe pasar el mes actual aquí).
     */
    private function calcularMontoMorosos($row, $cutoffMesAnterior) {
        $montoCuota = (float) $row->monto_cuota;
        if ($montoCuota <= 0) {
            return 0;
        }

        $totalPagado = (float) $row->total_pagado;
        $plan = $this->gPlanCobro($row->fecha_inicio_cobro, (int) $row->numero_cuotas, (string) $row->periocidad);
        $restantePago = $totalPagado;
        $adeudado = 0.0;
        $cutoffTs = strtotime($cutoffMesAnterior);

        foreach ($plan as $cuota) {
            $fechaCuota = $this->parseFechaPlan($cuota['fecha']);
            if ($fechaCuota === null || $fechaCuota > $cutoffTs) {
                break;
            }

            $aplicado = 0.0;
            if ($restantePago > 0) {
                if ($restantePago >= $montoCuota) {
                    $aplicado = $montoCuota;
                    $restantePago -= $montoCuota;
                } else {
                    $aplicado = $restantePago;
                    $restantePago = 0;
                }
            }

            $pendiente = $montoCuota - $aplicado;
            if ($pendiente > 0) {
                $adeudado += $pendiente;
            }
        }

        return round($adeudado, 2);
    }

    /** Último día del mes anterior al de $fecha (Y-m-d). */
    private function finDeMesAnterior($fecha) {
        $ts = strtotime($fecha);
        $primerDiaMesActual = date('Y-m-01', $ts);
        return date('Y-m-d', strtotime($primerDiaMesActual . ' -1 day'));
    }

    /** Plan de cuotas (fecha estimada de cada cuota) a partir del inicio de cobro y la periodicidad. */
    private function gPlanCobro($sFec, $iCuo, $iPer) {
        $arr = array();
        $fechap = explode('-', $sFec);
        if (count($fechap) < 3) {
            return $arr;
        }

        $dia_inicio = (int) $fechap[2];
        $mes_inicio = (int) $fechap[1];
        $ano_inicio = (int) $fechap[0];

        for ($i = 1; $i <= $iCuo; $i++) {
            $base_mes = 1;
            switch ($iPer) {
                case '0': $base_mes = 1 / 4; break;
                case '1':
                case '2':
                case '3': $base_mes = 1 / 2; break;
                case '4':
                case '5':
                case '6':
                case '8':
                case '9': $base_mes = 1; break;
                case '7': $base_mes = 12; break;
            }

            $tiempo = ($i - 1) * $base_mes;
            $tiempo_picado = explode('.', (string) $tiempo);
            $ano_t = (int) ($tiempo_picado[0] / 12) + $ano_inicio;
            $mes_t = $tiempo_picado[0] % 12;
            $dia_t = $dia_inicio;

            if (isset($tiempo_picado[1])) {
                switch ($tiempo_picado[1]) {
                    case '25': $dia_t += 7; break;
                    case '5': $dia_t += 15; break;
                    case '75': $dia_t += 21; break;
                }
            }

            if ($dia_t > 30) {
                $mes_t += 1;
                $dia_t -= 30;
            }

            $suma_meses = $mes_t + $mes_inicio;
            if ($suma_meses > 12) {
                $ano_t += 1;
                $mes_t = $suma_meses - 12;
            } else {
                $mes_t = $suma_meses;
            }

            $arr[] = array(
                'cuota' => $i,
                'fecha' => $dia_t . '-' . sprintf('%02d', $mes_t) . '-' . $ano_t,
            );
        }

        return $arr;
    }

    private function parseFechaPlan($fecha) {
        $partes = explode('-', $fecha);
        if (count($partes) !== 3) {
            return null;
        }
        $ts = mktime(0, 0, 0, (int) $partes[1], (int) $partes[0], (int) $partes[2]);
        return $ts ? $ts : null;
    }

    private function normalizarFechaCorte($fecha) {
        $partes = explode('-', $fecha);
        if (count($partes) !== 3) {
            return null;
        }

        $ano = (int) $partes[0];
        $mes = (int) $partes[1];
        $dia = (int) $partes[2];

        if ($ano < 2000 || $mes < 1 || $mes > 12) {
            return null;
        }

        // dia<=1 ("SEMANAL"/"MENSUAL", preset del selector "Hasta la Fecha")
        // representa "todo el mes" — la mayoría de las cuotas vencen cerca de
        // fin de mes, así que un corte literal en el día 1 casi nunca alcanza
        // a incluir la cuota del mes seleccionado.
        if ($dia <= 1) {
            $dia = (int) date('t', mktime(0, 0, 0, $mes, 1, $ano));
        }

        return sprintf('%04d-%02d-%02d', $ano, $mes, $dia);
    }

    private function normalizarCabecera($cab) {
        return array(
            'rif_letra' => strtoupper(trim(isset($cab['rif_letra']) ? $cab['rif_letra'] : 'J')),
            'rif_numero' => preg_replace('/\D/', '', isset($cab['rif_numero']) ? $cab['rif_numero'] : ''),
            'lote' => preg_replace('/\D/', '', isset($cab['lote']) ? $cab['lote'] : date('ymd')),
            'ref_credito_cuentas' => preg_replace('/\D/', '', isset($cab['ref_credito_cuentas']) ? $cab['ref_credito_cuentas'] : ''),
            'ref_credito_tdc_bdv' => preg_replace('/\D/', '', isset($cab['ref_credito_tdc_bdv']) ? $cab['ref_credito_tdc_bdv'] : ''),
            'ref_credito_tdc_otros' => preg_replace('/\D/', '', isset($cab['ref_credito_tdc_otros']) ? $cab['ref_credito_tdc_otros'] : ''),
            'fecha_envio' => $this->normalizarFecha(isset($cab['fecha_envio']) ? $cab['fecha_envio'] : date('Y-m-d')),
            'fecha_valor' => $this->normalizarFecha(isset($cab['fecha_valor']) ? $cab['fecha_valor'] : date('Y-m-d')),
            'fecha_culminacion' => $this->normalizarFecha(isset($cab['fecha_culminacion']) ? $cab['fecha_culminacion'] : date('Y-m-d', strtotime('+3 days'))),
        );
    }

    private function validarCabecera($cab) {
        if (!in_array($cab['rif_letra'], array('V', 'E', 'J', 'G', 'P'), true)) {
            return 'Indique la letra del RIF de la empresa (V, E, J, G o P).';
        }
        if (strlen($cab['rif_numero']) < 4 || strlen($cab['rif_numero']) > 9) {
            return 'El número de RIF de la empresa debe tener entre 4 y 9 dígitos.';
        }
        if ($cab['lote'] === '') {
            return 'Indique el número de lote.';
        }
        if (strlen($cab['lote']) > 10) {
            return 'El número de lote no puede tener más de 10 dígitos.';
        }
        if ($cab['ref_credito_cuentas'] === '' && $cab['ref_credito_tdc_bdv'] === '' && $cab['ref_credito_tdc_otros'] === '') {
            return 'Debe indicar al menos una referencia de crédito (cuentas, TDC BDV u otros bancos).';
        }
        if ($cab['fecha_envio'] === '' || $cab['fecha_valor'] === '' || $cab['fecha_culminacion'] === '') {
            return 'Complete las fechas de envío, valor del lote y culminación.';
        }
        if (strtotime($cab['fecha_valor']) < strtotime($cab['fecha_envio'])) {
            return 'La fecha valor del lote no puede ser menor a la fecha de envío.';
        }
        if (strtotime($cab['fecha_culminacion']) < strtotime($cab['fecha_valor'])) {
            return 'La fecha de culminación no puede ser menor a la fecha valor del lote.';
        }
        return '';
    }

    private function validarFila($fila, $cab) {
        if (strtotime($fila['fecha_debito']) < strtotime($cab['fecha_valor'])) {
            return 'La fecha valor del débito no puede ser menor a la fecha valor del lote (cédula ' . $fila['documento'] . ').';
        }
        if (strtotime($cab['fecha_culminacion']) < strtotime($fila['fecha_debito'])) {
            return 'La fecha de culminación no puede ser menor a la fecha valor del débito (cédula ' . $fila['documento'] . ').';
        }
        if ($fila['referencia'] === '') {
            return 'Falta referencia del débito para cédula ' . $fila['documento'];
        }
        return '';
    }

    private function parseDocumento($documentoId, $nacionalidad) {
        $letra = 'V';
        $n = strtoupper(trim((string) $nacionalidad));
        if ($n !== '') {
            $letra = str_replace('-', '', $n[0]);
        }
        $numero = preg_replace('/\D/', '', (string) $documentoId);
        return array('letra' => $letra, 'numero' => $numero);
    }

    private function mapTipoCuenta($tipo) {
        $tipo = strtoupper(trim((string) $tipo));
        if (strpos($tipo, 'CORRIENTE') !== false) {
            return '0';
        }
        return '1';
    }

    private function referenciaDebito($contratoId, $documentoId) {
        $ref = preg_replace('/\D/', '', (string) $contratoId);
        if ($ref === '') {
            $ref = preg_replace('/\D/', '', (string) $documentoId);
        }
        return $ref;
    }

    private function limpiarCuenta($cuenta) {
        return preg_replace('/\D/', '', trim((string) $cuenta));
    }

    private function normalizarFecha($fecha) {
        $fecha = trim((string) $fecha);
        if ($fecha === '') {
            return '';
        }
        $ts = strtotime(str_replace('/', '-', $fecha));
        return $ts ? date('Y-m-d', $ts) : '';
    }

    private function padNum($valor, $len) {
        $valor = preg_replace('/\D/', '', (string) $valor);
        if (strlen($valor) > $len) {
            $valor = substr($valor, -$len);
        }
        return str_pad($valor, $len, '0', STR_PAD_LEFT);
    }

    private function formatDocumentoTxt($letra, $numero, $esJuridico) {
        $numero = preg_replace('/\D/', '', $numero);
        if ($esJuridico) {
            return $letra . $this->padNum($numero, 9);
        }
        $n = strlen($numero) >= 8 ? substr($numero, 0, 8) : $numero;
        return $letra . $this->padNum($n, 8) . '0';
    }

    private function formatMontoTxt($monto) {
        $centimos = (int) round((float) $monto * 100);
        return '0' . str_pad((string) $centimos, 12, '0', STR_PAD_LEFT);
    }

    private function formatFechaTxt($fecha) {
        $ts = strtotime($fecha);
        return $ts ? date('d/m/Y', $ts) : '';
    }

    private function formatEmailTxt($email) {
        $email = strtoupper(trim((string) $email));
        if ($email === '') {
            return str_repeat('0', 50);
        }
        if (strlen($email) > 50) {
            $email = substr($email, 0, 50);
        }
        return str_pad($email, 50, '0', STR_PAD_LEFT);
    }

    private function armarEncabezadoTxt($cab, $cantidad, $montoTotal) {
        $esJuridico = in_array($cab['rif_letra'], array('J', 'G'), true);
        $header = '01' . $this->formatDocumentoTxt($cab['rif_letra'], $cab['rif_numero'], $esJuridico);
        $header .= $this->padNum($cantidad, 10);
        $header .= $this->formatMontoTxt($montoTotal);

        $header2 = $this->formatFechaTxt($cab['fecha_envio']);
        $header2 .= $this->padNum($cab['lote'], 10);
        $header2 .= $this->padNum($cab['ref_credito_cuentas'], 20);
        $header2 .= $this->padNum($cab['ref_credito_tdc_bdv'], 20);
        $header2 .= $this->padNum($cab['ref_credito_tdc_otros'], 20);
        $header2 .= $this->formatFechaTxt($cab['fecha_valor']);
        $header2 .= $this->formatFechaTxt($cab['fecha_culminacion']);

        return $header . $header2;
    }

    /**
     * Layout de detalle (225 caracteres) verificado byte a byte contra un
     * archivo real aceptado por BDV (docs/venezuela.txt): letra+cédula(9),
     * código cliente(21), tipo pago(1), tipo cuenta(2), cuenta(20),
     * reservado TDC/CVV/Expiración(77, sin uso — este sistema solo dominicilia
     * por Cuenta), fecha débito(10), monto(13), referencia(20), email(50).
     */
    private function filaParaTxt($fila) {
        $out = '02';
        $out .= $fila['letra'];
        $out .= $this->padNum($fila['documento'], 8);
        $out .= $this->padNum($fila['codigo_cliente'], 21);
        $out .= $fila['tipo_pago'];
        $out .= ($fila['tipo_cuenta'] === '0') ? '00' : '01';
        $out .= $fila['cuenta'];
        $out .= str_repeat('0', 77);
        $out .= $this->formatFechaTxt($fila['fecha_debito']);
        $out .= $this->formatMontoTxt($fila['monto']);
        $out .= $this->padNum($fila['referencia'], 20);
        // El correo nunca se envía a BDV (el proceso manual histórico,
        // aceptado por el banco, jamás lo incluyó) — se ignora cualquier
        // valor que traiga $fila['email'] y siempre se manda en ceros.
        $out .= $this->formatEmailTxt('');
        return $out;
    }

    private function filaParaXls($fila) {
        return array(
            $fila['letra'],
            $fila['documento'],
            $fila['codigo_cliente'],
            $fila['tipo_pago'],
            $fila['tipo_cuenta'],
            $fila['cuenta'],
            '',
            '',
            '',
            $this->formatFechaTxt($fila['fecha_debito']),
            number_format($fila['monto'], 2, ',', '.'),
            $fila['referencia'],
            $fila['email'],
        );
    }

    private function cabeceraParaXls($cab) {
        return array(
            'rif_letra' => $cab['rif_letra'],
            'rif_numero' => $cab['rif_numero'],
            'ref_credito_cuentas' => $cab['ref_credito_cuentas'],
            'ref_credito_tdc_bdv' => $cab['ref_credito_tdc_bdv'],
            'ref_credito_tdc_otros' => $cab['ref_credito_tdc_otros'],
            'lote' => $cab['lote'],
            'fecha_envio' => $this->formatFechaTxt($cab['fecha_envio']),
            'fecha_valor' => $this->formatFechaTxt($cab['fecha_valor']),
            'fecha_culminacion' => $this->formatFechaTxt($cab['fecha_culminacion']),
        );
    }
}
