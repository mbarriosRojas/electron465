<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Generador TXT de cobranza / afiliación (reemplazo del binario Gtxt C++).
 *
 * Formato fijo según instructivos fanban.doc y bicentenario.PPTX:
 * - Encabezado: 51 caracteres
 * - Detalle:   54 caracteres por débito
 */
class GtxtGenerator {

    /** Máximo de líneas de detalle por archivo TXT (restricción bancaria). */
    const LIMITE_LINEAS_POR_ARCHIVO = 9999;

    /**
     * Regla de oro Morosos: periodicidades que se cobran siempre juntas,
     * sin importar cuál haya elegido el usuario en el formulario — Mensual(4),
     * Mensual-25(9), Mensual-15(5), Mensual-30(6).
     */
    const PERIOCIDADES_MENSUALES_MOROSOS = array(4, 9, 5, 6);

    /** @var object */
    public $db;

    private $cuentaMatriz = array();
    private $codigoEmpresaDefault = array();
    private $codigoReferencia = '01';
    /** @var array banco => código de empresa propio (ej. BANFAN => '0147') */
    private $codigoEmpresaPorBanco = array();
    /** @var array banco => código de referencia propio (ej. BANFAN => '21') */
    private $codigoReferenciaPorBanco = array();
    private $afiliarTxt = array();

    public function __construct($db = null) {
        if ($db !== null) {
            $this->db = $db;
            $this->cargarConfigManual();
        } else {
            $CI =& get_instance();
            $this->db = $CI->db;
            $CI->config->load('gtxt_config');
            $this->cuentaMatriz = $CI->config->item('gtxt_cuenta_matriz');
            $this->codigoEmpresaDefault = $CI->config->item('gtxt_codigo_empresa_default');
            $this->codigoReferencia = $CI->config->item('gtxt_codigo_referencia');
            $porBancoEmp = $CI->config->item('gtxt_codigo_empresa_por_banco');
            $this->codigoEmpresaPorBanco = $porBancoEmp ? $porBancoEmp : array();
            $porBancoRef = $CI->config->item('gtxt_codigo_referencia_por_banco');
            $this->codigoReferenciaPorBanco = $porBancoRef ? $porBancoRef : array();
        }
        $this->cargarAfiliarTxt();
    }

    private function cargarConfigManual() {
        $path = APPPATH . 'config/gtxt_config.php';
        if (is_file($path)) {
            include $path;
            if (isset($config)) {
                $this->cuentaMatriz = isset($config['gtxt_cuenta_matriz']) ? $config['gtxt_cuenta_matriz'] : array();
                $this->codigoEmpresaDefault = isset($config['gtxt_codigo_empresa_default']) ? $config['gtxt_codigo_empresa_default'] : array();
                $this->codigoReferencia = isset($config['gtxt_codigo_referencia']) ? $config['gtxt_codigo_referencia'] : '01';
                $this->codigoEmpresaPorBanco = isset($config['gtxt_codigo_empresa_por_banco']) ? $config['gtxt_codigo_empresa_por_banco'] : array();
                $this->codigoReferenciaPorBanco = isset($config['gtxt_codigo_referencia_por_banco']) ? $config['gtxt_codigo_referencia_por_banco'] : array();
            }
        }
    }

    /**
     * Punto de entrada principal. Retorna array con claves:
     * ok, cant, mont, path, stdout, error
     */
    public function generar($param) {
        $result = array(
            'ok' => false,
            'cant' => 0,
            'mont' => 0,
            'path' => '',
            'stdout' => '',
            'error' => '',
        );

        $tipoArchivo = isset($param['tipo_archivo']) ? strtoupper(trim($param['tipo_archivo'])) : 'C';
        $banco = isset($param['banco']) ? strtoupper(trim($param['banco'])) : '';
        $empresa = isset($param['empresa']) ? (int) $param['empresa'] : 0;
        $fechaCorte = isset($param['fecha']) ? trim($param['fecha']) : '';
        $periodicidad = isset($param['periodicidad']) ? (string) $param['periodicidad'] : '4';
        $formaContrato = isset($param['fcontrato']) ? (int) $param['fcontrato'] : 0;
        $nomina = isset($param['nomina']) ? trim($param['nomina']) : '';
        $fechaArchivo = isset($param['fact']) ? $param['fact'] : date('Ymd');
        $picar = isset($param['picar']) ? (float) $param['picar'] : 100;
        $md5Nom = isset($param['md5_nom']) ? $param['md5_nom'] : '';
        $baseDir = isset($param['base_dir']) ? rtrim($param['base_dir'], '/') . '/' : '';

        if ($banco === '' || $nomina === '' || $fechaCorte === '') {
            $result['error'] = 'Parámetros incompletos (banco, nómina o fecha).';
            return $result;
        }

        if (!in_array($tipoArchivo, array('A', 'C', 'M'), true)) {
            $result['error'] = 'Tipo de archivo no válido.';
            return $result;
        }

        $cutoff = $this->normalizarFechaCorte($fechaCorte);
        if ($cutoff === null) {
            $result['error'] = 'Fecha de corte inválida: ' . $fechaCorte;
            return $result;
        }

        $subDir = ($tipoArchivo === 'A') ? 'afiliacion/' : 'cobranza/';
        $txtPath = $baseDir . 'tmp/' . $subDir . $md5Nom . '.txt';

        $contratos = $this->consultarContratos($banco, $empresa, $periodicidad, $formaContrato, $nomina, $cutoff);
        if (empty($contratos)) {
            $result['error'] = 'No se encontraron contratos activos para los filtros seleccionados.';
            return $result;
        }

        $registros = array();
        $montoTotal = 0.0;

        foreach ($contratos as $row) {
            $monto = $this->calcularMonto($row, $cutoff, $tipoArchivo);
            if ($monto <= 0) {
                continue;
            }

            $cuenta = $this->limpiarCuenta($row->cuenta_1);
            if ($cuenta === '') {
                continue;
            }

            $partes = $this->picarMonto($monto, $picar);
            $codigoEmpresa = $this->resolverCodigoEmpresa((int) $row->empresa, (int) $row->credito_id, $banco);
            $cedula = $this->formatearCedula($row->documento_id);

            foreach ($partes as $parte) {
                $registros[] = array(
                    'codigo_empresa' => $codigoEmpresa,
                    'monto' => $parte,
                    'cuenta' => $cuenta,
                    'cedula' => $cedula,
                    'empresa' => (int) $row->empresa,
                );
                $montoTotal += $parte;
            }
        }

        if (empty($registros)) {
            $result['error'] = 'Ningún contrato generó montos mayores a cero para el TXT.';
            return $result;
        }

        $empresaHeader = ($empresa === 2) ? 0 : $empresa;
        $cuentaMatriz = $this->resolverCuentaMatriz($banco, $empresaHeader);
        if ($cuentaMatriz === '') {
            $result['error'] = 'No hay cuenta matriz configurada para ' . $banco . ' / empresa ' . $empresaHeader;
            return $result;
        }

        $contenido = $this->armarArchivo($cuentaMatriz, $fechaArchivo, $montoTotal, count($registros), $registros, $this->resolverCodigoReferencia($banco));

        if (!is_dir(dirname($txtPath))) {
            @mkdir(dirname($txtPath), 0775, true);
        }

        if (@file_put_contents($txtPath, $contenido) === false) {
            $result['error'] = 'No se pudo escribir el archivo: ' . $txtPath;
            return $result;
        }

        $cant = count($registros);
        $mont = number_format($montoTotal, 2, '.', '');
        $result['ok'] = true;
        $result['cant'] = $cant;
        $result['mont'] = $mont;
        $result['path'] = $txtPath;
        $result['stdout'] = 'OK;' . $cant . ';' . $mont;

        return $result;
    }

    /**
     * Genera TXT de cobranza en bolívares (cuota USD × precio del dólar vigente).
     * Filtros: empresa, linaje, nómina, forma contrato, periodicidad (sin fecha de corte).
     */
    public function generarBolivares($param) {
        $result = array(
            'ok' => false,
            'cant' => 0,
            'mont' => 0,
            'path' => '',
            'stdout' => '',
            'error' => '',
            'precio_dolar' => 0,
        );

        $banco = isset($param['banco']) ? strtoupper(trim($param['banco'])) : '';
        $empresa = isset($param['empresa']) ? (int) $param['empresa'] : 0;
        $periodicidad = isset($param['periodicidad']) ? (string) $param['periodicidad'] : '4';
        $formaContrato = isset($param['fcontrato']) ? (int) $param['fcontrato'] : 0;
        $nomina = isset($param['nomina']) ? trim($param['nomina']) : '';
        $precioDolar = isset($param['precio_dolar']) ? (float) $param['precio_dolar'] : 0;
        $fechaArchivo = isset($param['fact']) ? $param['fact'] : date('Ymd');
        $picar = isset($param['picar']) ? (float) $param['picar'] : 10000;
        $md5Nom = isset($param['md5_nom']) ? $param['md5_nom'] : '';
        $baseDir = isset($param['base_dir']) ? rtrim($param['base_dir'], '/') . '/' : '';

        if ($banco === '' || $nomina === '') {
            $result['error'] = 'Complete linaje y nómina.';
            return $result;
        }

        if ($precioDolar <= 0) {
            $result['error'] = 'No hay precio del dólar vigente. Regístrelo en Panel de Control → Precio del Dólar.';
            return $result;
        }

        $txtPath = $baseDir . 'tmp/cobranza_bs/' . $md5Nom . '.txt';
        $contratos = $this->consultarContratosActivos($banco, $empresa, $periodicidad, $formaContrato, $nomina);

        if (empty($contratos)) {
            $result['error'] = 'No se encontraron contratos con saldo pendiente para los filtros seleccionados.';
            return $result;
        }

        $registros = array();
        $montoTotal = 0.0;

        foreach ($contratos as $row) {
            $montoBs = $this->calcularMontoBolivares($row, $precioDolar);
            if ($montoBs <= 0) {
                continue;
            }

            $cuenta = $this->limpiarCuenta($row->cuenta_1);
            if ($cuenta === '') {
                continue;
            }

            $partes = $this->picarMonto($montoBs, $picar);
            $codigoEmpresa = $this->resolverCodigoEmpresa((int) $row->empresa, (int) $row->credito_id, $banco);
            $cedula = $this->formatearCedula($row->documento_id);

            foreach ($partes as $parte) {
                $registros[] = array(
                    'codigo_empresa' => $codigoEmpresa,
                    'monto' => $parte,
                    'cuenta' => $cuenta,
                    'cedula' => $cedula,
                    'empresa' => (int) $row->empresa,
                );
                $montoTotal += $parte;
            }
        }

        if (empty($registros)) {
            $result['error'] = 'Ningún contrato generó montos en bolívares (revise cuentas bancarias).';
            return $result;
        }

        $empresaHeader = ($empresa === 2) ? 0 : $empresa;
        $cuentaMatriz = $this->resolverCuentaMatriz($banco, $empresaHeader);
        if ($cuentaMatriz === '') {
            $result['error'] = 'No hay cuenta matriz configurada para ' . $banco . ' / empresa ' . $empresaHeader;
            return $result;
        }

        $contenido = $this->armarArchivo($cuentaMatriz, $fechaArchivo, $montoTotal, count($registros), $registros, $this->resolverCodigoReferencia($banco));

        if (!is_dir(dirname($txtPath))) {
            @mkdir(dirname($txtPath), 0775, true);
        }

        if (@file_put_contents($txtPath, $contenido) === false) {
            $result['error'] = 'No se pudo escribir el archivo: ' . $txtPath;
            return $result;
        }

        $cant = count($registros);
        $mont = number_format($montoTotal, 2, '.', '');
        $result['ok'] = true;
        $result['cant'] = $cant;
        $result['mont'] = $mont;
        $result['path'] = $txtPath;
        $result['precio_dolar'] = $precioDolar;
        $result['stdout'] = 'OK;' . $cant . ';' . $mont;

        return $result;
    }

    /**
     * Genera TXT de cobranza en bolívares ACUMULANDO todas las cuotas vencidas
     * hasta una fecha de corte "Hasta la Fecha" (no solo la última/saldo),
     * convertidas a Bs con la tasa recibida en $param['precio_dolar']
     * (el llamador decide qué tipo de tasa usar, ej. Dólar Cobranza).
     *
     * Reutiliza la misma consulta y el mismo cálculo de cuotas vencidas que
     * `generar()` usa para el archivo en USD (`consultarContratos()` +
     * `calcularMonto()`), solo que el resultado en USD se multiplica por la
     * tasa antes de escribirlo al archivo.
     */
    public function generarBolivaresConCorte($param) {
        $result = array(
            'ok' => false,
            'cant' => 0,
            'mont' => 0,
            'path' => '',
            'stdout' => '',
            'error' => '',
            'precio_dolar' => 0,
            'filtro_cliente' => '',
        );

        $banco = isset($param['banco']) ? strtoupper(trim($param['banco'])) : '';
        $empresa = isset($param['empresa']) ? (int) $param['empresa'] : 0;
        $periodicidad = isset($param['periodicidad']) ? (string) $param['periodicidad'] : '4';
        $formaContrato = isset($param['fcontrato']) ? (int) $param['fcontrato'] : 0;
        $nomina = isset($param['nomina']) ? trim($param['nomina']) : '';
        $fechaCorte = isset($param['fecha']) ? trim($param['fecha']) : '';
        $tipoArchivo = isset($param['tipo_archivo']) ? strtoupper(trim($param['tipo_archivo'])) : 'C';
        $filtroCliente = isset($param['filtro_cliente']) ? strtoupper(trim($param['filtro_cliente'])) : 'ACTIVOS';
        $bancoNomina = isset($param['banco_nomina']) ? strtoupper(trim($param['banco_nomina'])) : '';
        $precioDolar = isset($param['precio_dolar']) ? (float) $param['precio_dolar'] : 0;
        $fechaArchivo = isset($param['fact']) ? $param['fact'] : date('Ymd');
        $picar = isset($param['picar']) ? (float) $param['picar'] : 10000;
        $md5Nom = isset($param['md5_nom']) ? $param['md5_nom'] : '';
        $baseDir = isset($param['base_dir']) ? rtrim($param['base_dir'], '/') . '/' : '';

        if ($banco === '' || $nomina === '' || $fechaCorte === '') {
            $result['error'] = 'Complete linaje, nómina y fecha de corte (Hasta la Fecha).';
            return $result;
        }

        if (!in_array($filtroCliente, array('ACTIVOS', 'MOROSOS'), true)) {
            $result['error'] = 'Debe indicar el filtro de cliente (Activos o Morosos).';
            return $result;
        }

        // COTERO agrupa clientes de Venezuela/Bicentenario/Banfan por igual —
        // sin este filtro por el banco REAL de la cuenta (p.banco_1) un solo
        // envío mezclaría cuentas de los 3 bancos en un mismo archivo.
        if ($banco === 'COTERO' && $bancoNomina === '') {
            $result['error'] = 'Para Cotero debe seleccionar el Banco (Nómina): Venezuela, Bicentenario o Banfan.';
            return $result;
        }

        // Regla de oro (confirmada por el usuario): para Morosos, la
        // Periodicidad elegida en el formulario se ignora — siempre se
        // cobran juntas las 4 variantes "mensual" (Mensual, Mensual-25,
        // Mensual-15, Mensual-30), sin importar el linaje/banco.
        if ($filtroCliente === 'MOROSOS') {
            $periodicidad = self::PERIOCIDADES_MENSUALES_MOROSOS;
        }

        if ($precioDolar <= 0) {
            $result['error'] = 'No hay tasa de Dólar Cobranza registrada. Regístrela en Panel → Precio del Dólar.';
            return $result;
        }

        $cutoff = $this->normalizarFechaCorte($fechaCorte);
        if ($cutoff === null) {
            $result['error'] = 'Fecha de corte inválida: ' . $fechaCorte;
            return $result;
        }

        // "Activos": solo la cuota del mes/año de $cutoff (consulta hasta fin de
        // ese mes). "Morosos": solo deuda de meses ANTERIORES (consulta y
        // acumulación hasta fin del mes previo, nunca incluye el mes elegido).
        $cutoffConsulta = ($filtroCliente === 'MOROSOS') ? $this->finDeMesAnterior($cutoff) : $cutoff;

        // Regla de oro (confirmada por el usuario): el código de empresa de cada
        // línea lo decide ÚNICAMENTE el banco — Bicentenario=3275, Banfan=0147,
        // Venezuela=0000. Si el Linaje es Cotero, se usa el Banco (Nómina)
        // elegido en su lugar (Cotero agrupa clientes de los 3 bancos por
        // igual). El resto de las reglas de Cotero (cuenta matriz, código de
        // referencia) NO cambian, solo el código de empresa.
        $bancoParaCodigoEmpresa = ($banco === 'COTERO' && in_array($bancoNomina, array('BICENTENARIO', 'BANFAN', 'VENEZUELA'), true))
            ? $bancoNomina
            : $banco;

        $txtPath = $baseDir . 'tmp/cobranza_bs/' . $md5Nom . '.txt';
        $contratos = $this->consultarContratos($banco, $empresa, $periodicidad, $formaContrato, $nomina, $cutoffConsulta, $bancoNomina);

        if (empty($contratos)) {
            $result['error'] = ($filtroCliente === 'MOROSOS')
                ? 'No se encontraron contratos morosos (deuda de meses anteriores al seleccionado).'
                : 'No se encontraron contratos con cuota en el mes seleccionado.';
            return $result;
        }

        $registros = array();
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
            if ($cuenta === '') {
                continue;
            }

            $partes = $this->picarMonto($montoBs, $picar);
            $codigoEmpresa = $this->resolverCodigoEmpresa((int) $row->empresa, (int) $row->credito_id, $bancoParaCodigoEmpresa, true);
            $cedula = $this->formatearCedula($row->documento_id);

            foreach ($partes as $parte) {
                $registros[] = array(
                    'codigo_empresa' => $codigoEmpresa,
                    'monto' => $parte,
                    'cuenta' => $cuenta,
                    'cedula' => $cedula,
                    'empresa' => (int) $row->empresa,
                );
                $montoTotal += $parte;
            }
        }

        if (empty($registros)) {
            $result['error'] = ($filtroCliente === 'MOROSOS')
                ? 'Ningún contrato moroso generó montos en bolívares (revise cuentas bancarias o deuda anterior).'
                : 'Ningún contrato generó montos en bolívares (revise cuentas bancarias o cuota del mes).';
            return $result;
        }

        $empresaHeader = ($empresa === 2) ? 0 : $empresa;
        $cuentaMatriz = $this->resolverCuentaMatriz($banco, $empresaHeader);
        if ($cuentaMatriz === '') {
            $result['error'] = 'No hay cuenta matriz configurada para ' . $banco . ' / empresa ' . $empresaHeader;
            return $result;
        }

        $loteResult = $this->escribirArchivosPorLote($registros, $txtPath, $fechaArchivo, $cuentaMatriz, $this->resolverCodigoReferencia($banco));
        if (isset($loteResult['error'])) {
            $result['error'] = $loteResult['error'];
            return $result;
        }

        $cant = count($registros);
        $mont = number_format($montoTotal, 2, '.', '');
        $result['ok'] = true;
        $result['cant'] = $cant;
        $result['mont'] = $mont;
        $result['path'] = $loteResult['archivos'][0]['path'];
        $result['archivos'] = $loteResult['archivos'];
        $result['precio_dolar'] = $precioDolar;
        $result['stdout'] = 'OK;' . $cant . ';' . $mont;
        $result['filtro_cliente'] = $filtroCliente;

        return $result;
    }

    /**
     * Condición WHERE para `c.periocidad`. Acepta un valor único (comportamiento
     * normal: una sola periodicidad) o un array (regla de oro Morosos: varias
     * periodicidades juntas, ej. las 4 variantes "mensual").
     *
     * Para las variantes mensuales (4, 5, 9) se resuelve también por el día
     * real de `fecha_inicio_cobro`: en la práctica casi nunca se usa el
     * código específico "Mensual - 15" (5) al crear el crédito — el operador
     * elige el genérico "MENSUAL" (4) sin importar el día real de cobro, que
     * solo queda registrado en fecha_inicio_cobro. Mismo criterio ya
     * validado en GtxtVenezuelaGenerator::condicionPeriocidadMensual().
     *   - "MENSUAL (día 15)" (5): periocidad 4 o 5 con día 15 de cobro.
     *   - "MENSUAL (día 25)" (9): periocidad 4 o 9 con día 25 de cobro.
     *   - "MENSUAL (día 30)" (6): periocidad 4 o 6 con día 30 de cobro.
     *   - "MENSUAL" (4): periocidad 4 EXCLUYENDO los días 15, 25 y 30.
     */
    private function condicionPeriocidad($periodicidad) {
        if (is_array($periodicidad)) {
            $valores = array_map('intval', $periodicidad);
            return 'c.periocidad IN (' . implode(',', $valores) . ')';
        }
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

    private function consultarContratosActivos($banco, $empresa, $periodicidad, $formaContrato, $nomina) {
        $bancoEsc = $this->db->escape(strtoupper(trim($banco)));
        $where = array();
        $where[] = 'UPPER(TRIM(c.cobrado_en)) = ' . $bancoEsc;
        $where[] = 'c.marca_consulta != 6';
        $where[] = 'c.cantidad != 0';
        $where[] = 'c.forma_contrato = ' . (int) $formaContrato;
        $where[] = "p.cuenta_1 IS NOT NULL";
        $where[] = "p.cuenta_1 != ''";
        $where[] = "p.cuenta_1 != '----------'";

        if ($empresa === 0 || $empresa === 1) {
            $where[] = 'c.empresa = ' . (int) $empresa;
        }

        $where[] = $this->condicionPeriocidad($periodicidad);

        if ($nomina !== 'VARIAS') {
            $where[] = 'c.nomina_procedencia = ' . $this->db->escape($nomina);
        }

        $sql = 'SELECT c.credito_id, c.contrato_id, c.documento_id, c.monto_cuota, c.monto_total, c.empresa,
            p.cuenta_1,
            IFNULL(SUM(lc.monto), 0) AS total_pagado,
            (c.monto_total - IFNULL(SUM(lc.monto), 0)) AS resta
            FROM t_clientes_creditos c
            INNER JOIN t_personas p ON p.documento_id = c.documento_id
            LEFT JOIN t_lista_cobros lc ON lc.credito_id = c.contrato_id
            WHERE ' . implode(' AND ', $where) . '
            GROUP BY c.contrato_id, c.credito_id, c.documento_id, c.monto_cuota, c.monto_total, c.empresa, p.cuenta_1
            HAVING resta > 0
            ORDER BY c.contrato_id';

        $rs = $this->db->query($sql);
        if (!$rs || $rs->num_rows() === 0) {
            return array();
        }

        return $rs->result();
    }

    /**
     * Cuota en USD (o saldo restante si es menor) × precio del dólar = monto en Bs.
     */
    private function calcularMontoBolivares($row, $precioDolar) {
        $montoCuota = (float) $row->monto_cuota;
        $resta = (float) $row->resta;
        if ($montoCuota <= 0 || $resta <= 0 || $precioDolar <= 0) {
            return 0;
        }
        $montoUsd = ($resta < $montoCuota) ? $resta : $montoCuota;
        return round($montoUsd * $precioDolar, 2);
    }

    private function cargarAfiliarTxt() {
        $this->afiliarTxt = array();
        $rs = $this->db->query('SELECT credito_id, detalle, cuenta FROM t_afiliar_txt');
        if ($rs && $rs->num_rows() > 0) {
            foreach ($rs->result() as $row) {
                $this->afiliarTxt[(int) $row->credito_id] = array(
                    'detalle' => trim($row->detalle),
                    'cuenta' => trim($row->cuenta),
                );
            }
        }
    }

    /**
     * @param string $bancoNomina Filtro adicional por el banco REAL de la cuenta del
     *   cliente (`p.banco_1`), distinto del linaje (`c.cobrado_en`). Necesario para
     *   COTERO: ese linaje agrupa clientes de Venezuela/Bicentenario/Banfan por igual,
     *   así que sin este filtro un solo envío mezclaría cuentas de los 3 bancos.
     *   Cadena vacía = sin filtro (comportamiento normal para los demás linajes).
     */
    private function consultarContratos($banco, $empresa, $periodicidad, $formaContrato, $nomina, $cutoff, $bancoNomina = '') {
        $where = array();
        $where[] = "c.cobrado_en = " . $this->db->escape($banco);
        $where[] = "c.marca_consulta != 6";
        $where[] = "c.cantidad != 0";
        $where[] = "c.fecha_inicio_cobro <= " . $this->db->escape($cutoff);
        $where[] = "c.forma_contrato = " . (int) $formaContrato;
        $where[] = "p.cuenta_1 IS NOT NULL";
        $where[] = "p.cuenta_1 != ''";
        $where[] = "p.cuenta_1 != '----------'";

        if ($empresa === 0 || $empresa === 1) {
            $where[] = 'c.empresa = ' . (int) $empresa;
        }

        $where[] = $this->condicionPeriocidad($periodicidad);

        if ($nomina !== 'VARIAS') {
            $where[] = 'c.nomina_procedencia = ' . $this->db->escape($nomina);
        }

        if (trim((string) $bancoNomina) !== '') {
            $where[] = 'UPPER(TRIM(p.banco_1)) = ' . $this->db->escape(strtoupper(trim($bancoNomina)));
        }

        $sql = 'SELECT c.credito_id, c.contrato_id, c.documento_id, c.fecha_inicio_cobro,
            c.numero_cuotas, c.monto_cuota, c.monto_total, c.periocidad, c.empresa,
            c.forma_contrato, c.cobrado_en, c.nomina_procedencia,
            p.cuenta_1, p.banco_1,
            IFNULL(SUM(lc.monto), 0) AS total_pagado,
            (c.monto_total - IFNULL(SUM(lc.monto), 0)) AS resta
            FROM t_clientes_creditos c
            INNER JOIN t_personas p ON p.documento_id = c.documento_id
            LEFT JOIN t_lista_cobros lc ON lc.credito_id = c.contrato_id
            WHERE ' . implode(' AND ', $where) . '
            GROUP BY c.contrato_id, c.credito_id, c.documento_id, c.fecha_inicio_cobro,
                c.numero_cuotas, c.monto_cuota, c.monto_total, c.periocidad, c.empresa,
                c.forma_contrato, c.cobrado_en, c.nomina_procedencia, p.cuenta_1, p.banco_1
            HAVING resta > 0
            ORDER BY c.contrato_id';

        $rs = $this->db->query($sql);
        if (!$rs || $rs->num_rows() === 0) {
            return array();
        }

        return $rs->result();
    }

    /**
     * Monto a debitar: cuotas vencidas pendientes/abonadas hasta la fecha de corte.
     */
    private function calcularMonto($row, $cutoff, $tipoArchivo) {
        $montoCuota = (float) $row->monto_cuota;
        if ($montoCuota <= 0) {
            return 0;
        }

        if ($tipoArchivo === 'A') {
            return round($montoCuota, 2);
        }

        $totalPagado = (float) $row->total_pagado;
        $plan = $this->gPlanCobro($row->fecha_inicio_cobro, (int) $row->numero_cuotas, (string) $row->periocidad);
        $restantePago = $totalPagado;
        $adeudado = 0.0;
        $cutoffTs = strtotime($cutoff);

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

        // Si el cronograma dice que el cliente ya está al día (nada vencido)
        // hasta la fecha de corte, pero el contrato aún tiene saldo total
        // pendiente, se cobra igual la cuota de este ciclo (tope: lo que
        // realmente le queda por pagar) — decisión confirmada: se cobra la
        // cuota del mes a todos los contratos activos, no solo a los atrasados.
        if ($adeudado <= 0) {
            $resta = isset($row->resta) ? (float) $row->resta : 0.0;
            if ($resta > 0) {
                $adeudado = min($montoCuota, $resta);
            }
        }

        return round($adeudado, 2);
    }

    /**
     * Modo "Activos" (TXT Bolívares): cobra ÚNICAMENTE la cuota programada
     * dentro del mes/año de $cutoff — nunca acumula atrasos de otros meses.
     * Si esa cuota puntual ya está cubierta por lo pagado hasta ese punto del
     * cronograma, o el contrato no tiene ninguna cuota programada ese mes, el
     * monto es 0 (se excluye del archivo).
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

        // El contrato debe seguir vigente al mes seleccionado: si su ÚLTIMA
        // cuota programada quedó en un mes ANTERIOR al buscado, el contrato ya
        // venció (agotó su plazo) — con saldo pendiente eso es Moroso, no
        // Activo, sin importar si ese mes tenía o no una cuota propia.
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
                break; // el cronograma ya pasó el mes buscado sin encontrar cuota ahí
            }
        }

        return 0;
    }

    /**
     * Modo "Morosos" (TXT Bolívares): acumula lo vencido y no pagado
     * ÚNICAMENTE hasta $cutoffMesAnterior (fin del mes anterior al
     * seleccionado — el llamador nunca debe pasar el mes actual aquí). No
     * aplica el "cobro igual si está al día": si no hay atraso real antes de
     * ese corte, el monto es 0 y el contrato se excluye.
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

    /**
     * Último día del mes anterior al de $fecha (Y-m-d) — usado para acotar el
     * modo "Morosos" a deuda estrictamente anterior al mes seleccionado.
     */
    private function finDeMesAnterior($fecha) {
        $ts = strtotime($fecha);
        $primerDiaMesActual = date('Y-m-01', $ts);
        return date('Y-m-d', strtotime($primerDiaMesActual . ' -1 day'));
    }

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

        // dia<=0 ("SEMANAL") y dia==1 ("MENSUAL", preset del selector "Hasta la
        // Fecha") ambos representan "todo el mes" — la mayoría de las cuotas
        // vencen cerca de fin de mes (día 28-30), así que un corte literal en
        // el día 1 casi nunca alcanza a incluir la cuota del mes seleccionado.
        if ($dia <= 1) {
            $dia = (int) date('t', mktime(0, 0, 0, $mes, 1, $ano));
        }

        return sprintf('%04d-%02d-%02d', $ano, $mes, $dia);
    }

    private function picarMonto($monto, $picar) {
        $monto = round((float) $monto, 2);
        $picar = (float) $picar;
        if ($picar <= 0) {
            return array($monto);
        }

        $partes = array();
        $restante = $monto;
        while ($restante > $picar + 0.001) {
            $partes[] = round($picar, 2);
            $restante -= $picar;
        }
        if ($restante > 0.001) {
            $partes[] = round($restante, 2);
        }

        return $partes;
    }

    /**
     * @param string|null $banco Si el banco tiene un código de empresa propio
     *   configurado (`gtxt_codigo_empresa_por_banco`), ese tiene prioridad
     *   sobre el default genérico por empresa (Cooperativa/Grupo) — ej.
     *   BANFAN usa 0147 en vez de 0006/0403. Los overrides manuales de
     *   `t_afiliar_txt` (por crédito o por empresa) siguen mandando por
     *   encima de todo.
     */
    /**
     * @param bool $ignorarOverridePorCredito Regla de oro TXT Bolívares (confirmada
     *   por el usuario): el código de empresa lo decide ÚNICAMENTE el banco (Linaje,
     *   o Banco/Nómina si el Linaje es Cotero) — el override histórico por crédito
     *   específico de `t_afiliar_txt` (pensado para el flujo viejo de afiliación) NO
     *   debe aplicar aquí. `generar()`/afiliación (tipo_archivo A/M) no pasan este
     *   flag y mantienen el comportamiento preexistente sin cambios.
     */
    private function resolverCodigoEmpresa($empresa, $creditoId, $banco = null, $ignorarOverridePorCredito = false) {
        // Override real por crédito específico (t_afiliar_txt indexado por
        // credito_id de un préstamo puntual) — máxima prioridad (comportamiento
        // preexistente, sin cambios), salvo que el llamador la desactive.
        if (!$ignorarOverridePorCredito && isset($this->afiliarTxt[$creditoId]) && $this->afiliarTxt[$creditoId]['detalle'] !== '') {
            return str_pad($this->afiliarTxt[$creditoId]['detalle'], 4, '0', STR_PAD_LEFT);
        }
        // Override específico del banco (confirmado con archivo real, ej.
        // BANFAN=0147) — más confiable que el default genérico por empresa.
        if ($banco !== null && isset($this->codigoEmpresaPorBanco[strtoupper(trim($banco))])) {
            return str_pad($this->codigoEmpresaPorBanco[strtoupper(trim($banco))], 4, '0', STR_PAD_LEFT);
        }
        // Convención legacy: t_afiliar_txt con credito_id=0/1 usados como
        // "default por empresa" (Cooperativa/Grupo) para bancos sin código propio.
        if (!$ignorarOverridePorCredito && isset($this->afiliarTxt[$empresa]) && $this->afiliarTxt[$empresa]['detalle'] !== '') {
            return str_pad($this->afiliarTxt[$empresa]['detalle'], 4, '0', STR_PAD_LEFT);
        }
        if (isset($this->codigoEmpresaDefault[$empresa])) {
            return $this->codigoEmpresaDefault[$empresa];
        }
        return '0000';
    }

    private function resolverCuentaMatriz($banco, $empresa) {
        if (isset($this->afiliarTxt[$empresa]['cuenta']) && $this->afiliarTxt[$empresa]['cuenta'] !== '') {
            return $this->limpiarCuenta($this->afiliarTxt[$empresa]['cuenta']);
        }
        if (isset($this->cuentaMatriz[$banco][$empresa])) {
            return $this->limpiarCuenta($this->cuentaMatriz[$banco][$empresa]);
        }
        return '';
    }

    /**
     * Código de referencia de descuento: por defecto el genérico
     * (`gtxt_codigo_referencia`), salvo que el banco tenga uno propio en
     * `gtxt_codigo_referencia_por_banco` (ej. BANFAN usa '21' en vez de '01').
     */
    private function resolverCodigoReferencia($banco) {
        $bancoNorm = strtoupper(trim((string) $banco));
        if (isset($this->codigoReferenciaPorBanco[$bancoNorm])) {
            return $this->codigoReferenciaPorBanco[$bancoNorm];
        }
        return $this->codigoReferencia;
    }

    private function limpiarCuenta($cuenta) {
        return preg_replace('/\D/', '', trim($cuenta));
    }

    private function formatearCedula($documentoId) {
        return str_pad(preg_replace('/\D/', '', (string) $documentoId), 10, '0', STR_PAD_LEFT);
    }

    private function montoEntero($monto) {
        return str_pad((string) (int) round((float) $monto * 100), 12, '0', STR_PAD_LEFT);
    }

    private function montoEnteroLargo($monto) {
        return str_pad((string) (int) round((float) $monto * 100), 17, '0', STR_PAD_LEFT);
    }

    /**
     * Parte $registros en bloques de máximo self::LIMITE_LINEAS_POR_ARCHIVO y
     * escribe un archivo TXT por bloque, cada uno con su propia cabecera
     * (mismo formato/cuenta matriz/referencia, cantidad y monto propios de
     * ese bloque). Si hay un solo bloque, el archivo queda con el nombre
     * original (sin sufijo), igual que antes de esta restricción.
     *
     * @return array Lista de archivos: array('path' => .., 'cant' => .., 'mont' => ..)
     */
    private function escribirArchivosPorLote($registros, $txtPathBase, $fechaArchivo, $cuentaMatriz, $codigoReferencia) {
        $bloques = array_chunk($registros, self::LIMITE_LINEAS_POR_ARCHIVO);
        $totalBloques = count($bloques);
        $archivos = array();

        if (!is_dir(dirname($txtPathBase))) {
            @mkdir(dirname($txtPathBase), 0775, true);
        }

        foreach ($bloques as $indice => $bloque) {
            $montoBloque = 0.0;
            foreach ($bloque as $reg) {
                $montoBloque += $reg['monto'];
            }

            $txtPath = ($totalBloques > 1)
                ? preg_replace('/\.txt$/', '', $txtPathBase) . '_parte' . ($indice + 1) . '.txt'
                : $txtPathBase;

            $contenido = $this->armarArchivo($cuentaMatriz, $fechaArchivo, $montoBloque, count($bloque), $bloque, $codigoReferencia);

            if (@file_put_contents($txtPath, $contenido) === false) {
                return array('error' => 'No se pudo escribir el archivo: ' . $txtPath);
            }

            $archivos[] = array(
                'path' => $txtPath,
                'cant' => count($bloque),
                'mont' => number_format($montoBloque, 2, '.', ''),
            );
        }

        return array('archivos' => $archivos);
    }

    private function armarArchivo($cuentaMatriz, $fechaArchivo, $montoTotal, $cantidad, $registros, $codigoReferencia = null) {
        $referencia = $codigoReferencia !== null ? $codigoReferencia : $this->codigoReferencia;
        $lineas = array();
        $lineas[] = str_pad($this->limpiarCuenta($cuentaMatriz), 20, '0', STR_PAD_LEFT)
            . str_pad(preg_replace('/\D/', '', $fechaArchivo), 8, '0', STR_PAD_LEFT)
            . $this->montoEnteroLargo($montoTotal)
            . str_pad((string) $cantidad, 4, '0', STR_PAD_LEFT)
            . str_pad($referencia, 2, '0', STR_PAD_LEFT);

        foreach ($registros as $reg) {
            $lineas[] = str_pad($reg['codigo_empresa'], 4, '0', STR_PAD_LEFT)
                . $this->montoEntero($reg['monto'])
                . str_pad($reg['cuenta'], 20, '0', STR_PAD_LEFT)
                . $reg['cedula']
                . '00000'
                . '1'
                . '00';
        }

        return implode("\r\n", $lineas) . "\r\n";
    }
}
