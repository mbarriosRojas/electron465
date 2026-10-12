<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * TXT / XLS Banco de Venezuela (domiciliación bolívares, formato macro BDV).
 */
class Mgtxt_venezuela extends Model {

    protected $tbl = 'cpp_banco';

    public function generar($param = array()) {
        $sBnc = isset($param['banco']) ? strtoupper(trim($param['banco'])) : '';
        if ($sBnc !== 'VENEZUELA') {
            return $this->respuestaHtml(false, 'Seleccione linaje <strong>VENEZUELA</strong> para este generador.');
        }

        $CI =& get_instance();
        $CI->load->model('mprecio_dolar', 'MPrecio_dolar');
        $precioRow = $CI->MPrecio_dolar->getPrecioActivo(MPrecio_dolar::TIPO_COBRANZA, 'VENEZUELA');
        if (!$precioRow || (float) $precioRow->precio <= 0) {
            $precioRow = $CI->MPrecio_dolar->getUltimoRegistroVigente(MPrecio_dolar::TIPO_COBRANZA, 'VENEZUELA');
        }
        if (!$precioRow || (float) $precioRow->precio <= 0) {
            return $this->respuestaHtml(false, 'No hay precio del dólar vigente. Regístrelo en Panel → Precio del Dólar.');
        }

        $precioDolar = (float) $precioRow->precio;
        $empresa = isset($param['empresa']) ? (int) $param['empresa'] : 0;
        $nomina = isset($param['nomina']) ? trim($param['nomina']) : '';
        if ($nomina === '') {
            return $this->respuestaHtml(false, 'Debe seleccionar nómina.');
        }

        $CI->config->load('gtxt_venezuela_config');
        $empresas = $CI->config->item('gtxt_venezuela_empresa');
        $empCfg = isset($empresas[$empresa]) ? $empresas[$empresa] : $empresas[0];

        $cabecera = array(
            'rif_letra' => isset($param['rif_letra']) ? $param['rif_letra'] : $empCfg['rif_letra'],
            'rif_numero' => isset($param['rif_numero']) ? $param['rif_numero'] : $empCfg['rif_numero'],
            'lote' => isset($param['lote']) ? $param['lote'] : date('ymd'),
            'ref_credito_cuentas' => isset($param['ref_credito_cuentas']) ? $param['ref_credito_cuentas'] : $empCfg['ref_credito_cuentas'],
            'ref_credito_tdc_bdv' => isset($param['ref_credito_tdc_bdv']) ? $param['ref_credito_tdc_bdv'] : $empCfg['ref_credito_tdc_bdv'],
            'ref_credito_tdc_otros' => isset($param['ref_credito_tdc_otros']) ? $param['ref_credito_tdc_otros'] : $empCfg['ref_credito_tdc_otros'],
            'fecha_envio' => isset($param['fecha_envio']) ? $param['fecha_envio'] : date('Y-m-d'),
            'fecha_valor' => isset($param['fecha_valor']) ? $param['fecha_valor'] : date('Y-m-d'),
            'fecha_culminacion' => isset($param['fecha_culminacion']) ? $param['fecha_culminacion'] : date('Y-m-d', strtotime('+3 days')),
        );

        $baseDir = str_replace('\\', '/', getcwd()) . '/';
        @mkdir($baseDir . 'tmp/cobranza_venezuela', 0775, true);

        $sPar = strtoupper('BDV "' . $sBnc . '" ' . $empresa . ' ' . $param['periodicidad'] . ' ' . $param['fcontrato']
            . ' "' . $nomina . '" @' . number_format($precioDolar, 4, '.', '') . ' L' . $cabecera['lote']);
        $md5Nom = md5($sPar);
        $sUsu = isset($_SESSION['usuario']) ? $_SESSION['usuario'] : '';

        $referenciaDebito = isset($param['referencia_debito']) && $param['referencia_debito'] !== ''
            ? $param['referencia_debito']
            : (isset($empCfg['referencia_debito']) ? $empCfg['referencia_debito'] : '');

        require_once APPPATH . 'models/cobranza/gtxt_venezuela_generator.php';
        $gen = new GtxtVenezuelaGenerator($this->db);
        $genResult = $gen->generar(array(
            'banco' => $sBnc,
            'empresa' => $empresa,
            'periodicidad' => $param['periodicidad'],
            'fcontrato' => $param['fcontrato'],
            'nomina' => $nomina,
            'fecha' => isset($param['fecha']) ? $param['fecha'] : '',
            'filtro_cliente' => isset($param['filtro_cliente']) ? $param['filtro_cliente'] : 'ACTIVOS',
            'precio_dolar' => $precioDolar,
            'cabecera' => $cabecera,
            'referencia_debito' => $referenciaDebito,
            'md5_nom' => $md5Nom,
            'base_dir' => $baseDir,
        ));

        if (empty($genResult['ok'])) {
            $detalle = htmlspecialchars(isset($genResult['error']) ? $genResult['error'] : 'Error desconocido');
            return $this->respuestaHtml(false, $detalle);
        }

        $txtPath = $genResult['path'];
        $val = exec('md5sum ' . escapeshellarg($txtPath));
        $ex = explode(' ', $val);
        $mesRef = date('Y-m');

        $this->db->query('DELETE FROM ' . $this->tbl . ' WHERE nmbr=' . $this->db->escape($sPar)
            . " AND esta = 1 AND tarc = 'V' LIMIT 1");

        $sInsert = 'INSERT INTO ' . $this->tbl . ' (`oid`,`nmbr`,`desc`,`esta`,`auto`,`lina`,`tama`,`empr`,`mes`,
            `peri`,`fcon`,`fact`,`nomi`,`cant`,`mont`,`tarc`,`proc`)
            VALUES (NULL,' . $this->db->escape($sPar) . ',' . $this->db->escape($md5Nom) . ',1,'
            . $this->db->escape($sUsu) . ',' . $this->db->escape($sBnc) . ','
            . $this->db->escape($ex[0]) . ',' . (int) $empresa . ',' . $this->db->escape($mesRef) . ','
            . (int) $param['periodicidad'] . ',' . (int) $param['fcontrato'] . ',' . $this->db->escape(date('Ymd')) . ','
            . $this->db->escape($nomina) . ',' . (int) $genResult['cant'] . ','
            . $this->db->escape(number_format($genResult['mont'], 2, '.', '')) . ",'V',"
            . $this->db->escape(date('Y-m-d H:i:s')) . ')';
        $this->db->query($sInsert);

        $xlsUrl = __LOCALWWW__ . '/tmp/cobranza_venezuela/' . $md5Nom . '_plantilla.xlsx';
        $this->generarXlsPlantilla($md5Nom, $genResult['cabecera_xls'], $genResult['filas_xls'], $baseDir);

        $html = '<br><br><center><h2>TXT Banco de Venezuela generado con éxito</h2></center><br>'
            . '<h3>Tasa Dólar Cobranza: <b>' . number_format($precioDolar, 4, ',', '.') . ' Bs./USD</b>'
            . ' (fecha ' . htmlspecialchars($precioRow->fecha) . ')</h3><br>'
            . '<h3>Registros: <b>' . (int) $genResult['cant'] . '</b></h3><br>'
            . '<h3>Total Bs.: <b>' . number_format($genResult['mont'], 2, ',', '.') . '</b></h3><br>'
            . '<a href="' . __LOCALWWW__ . '/tmp/cobranza_venezuela/' . $md5Nom . '.txt" download="' . $md5Nom . '.txt">+ Descargar TXT BDV</a><br><br>'
            . '<a href="' . $xlsUrl . '" download="' . $md5Nom . '_plantilla.xlsx">+ Descargar XLS plantilla (pegue desde fila 23 o use el macro)</a>'
            . '</center>';
        return $html;
    }

    /**
     * Solo exporta XLS con cabecera + detalle (sin generar TXT).
     */
    public function exportarXls($param = array()) {
        $sBnc = isset($param['banco']) ? strtoupper(trim($param['banco'])) : '';
        if ($sBnc !== 'VENEZUELA') {
            return $this->respuestaHtml(false, 'Seleccione linaje <strong>VENEZUELA</strong>.');
        }

        $CI =& get_instance();
        $CI->load->model('mprecio_dolar', 'MPrecio_dolar');
        $precioRow = $CI->MPrecio_dolar->getPrecioActivo(MPrecio_dolar::TIPO_COBRANZA, 'VENEZUELA');
        if (!$precioRow || (float) $precioRow->precio <= 0) {
            $precioRow = $CI->MPrecio_dolar->getUltimoRegistroVigente(MPrecio_dolar::TIPO_COBRANZA, 'VENEZUELA');
        }
        if (!$precioRow || (float) $precioRow->precio <= 0) {
            return $this->respuestaHtml(false, 'No hay precio del dólar vigente.');
        }

        $empresa = isset($param['empresa']) ? (int) $param['empresa'] : 0;
        $nomina = isset($param['nomina']) ? trim($param['nomina']) : '';
        if ($nomina === '') {
            return $this->respuestaHtml(false, 'Debe seleccionar nómina.');
        }

        $CI->config->load('gtxt_venezuela_config');
        $empresas = $CI->config->item('gtxt_venezuela_empresa');
        $empCfg = isset($empresas[$empresa]) ? $empresas[$empresa] : $empresas[0];

        $cabecera = array(
            'rif_letra' => isset($param['rif_letra']) ? $param['rif_letra'] : $empCfg['rif_letra'],
            'rif_numero' => isset($param['rif_numero']) ? $param['rif_numero'] : $empCfg['rif_numero'],
            'lote' => isset($param['lote']) ? $param['lote'] : date('ymd'),
            'ref_credito_cuentas' => isset($param['ref_credito_cuentas']) ? $param['ref_credito_cuentas'] : $empCfg['ref_credito_cuentas'],
            'ref_credito_tdc_bdv' => isset($param['ref_credito_tdc_bdv']) ? $param['ref_credito_tdc_bdv'] : $empCfg['ref_credito_tdc_bdv'],
            'ref_credito_tdc_otros' => isset($param['ref_credito_tdc_otros']) ? $param['ref_credito_tdc_otros'] : $empCfg['ref_credito_tdc_otros'],
            'fecha_envio' => isset($param['fecha_envio']) ? $param['fecha_envio'] : date('Y-m-d'),
            'fecha_valor' => isset($param['fecha_valor']) ? $param['fecha_valor'] : date('Y-m-d'),
            'fecha_culminacion' => isset($param['fecha_culminacion']) ? $param['fecha_culminacion'] : date('Y-m-d', strtotime('+3 days')),
        );

        $baseDir = str_replace('\\', '/', getcwd()) . '/';
        @mkdir($baseDir . 'tmp/cobranza_venezuela', 0775, true);
        $md5Nom = md5('xls_' . serialize($param) . microtime(true));

        $referenciaDebito = isset($param['referencia_debito']) && $param['referencia_debito'] !== ''
            ? $param['referencia_debito']
            : (isset($empCfg['referencia_debito']) ? $empCfg['referencia_debito'] : '');

        require_once APPPATH . 'models/cobranza/gtxt_venezuela_generator.php';
        $gen = new GtxtVenezuelaGenerator($this->db);
        $genResult = $gen->generar(array(
            'banco' => $sBnc,
            'empresa' => $empresa,
            'periodicidad' => $param['periodicidad'],
            'fcontrato' => $param['fcontrato'],
            'nomina' => $nomina,
            'fecha' => isset($param['fecha']) ? $param['fecha'] : '',
            'filtro_cliente' => isset($param['filtro_cliente']) ? $param['filtro_cliente'] : 'ACTIVOS',
            'precio_dolar' => (float) $precioRow->precio,
            'cabecera' => $cabecera,
            'referencia_debito' => $referenciaDebito,
            'md5_nom' => $md5Nom,
            'base_dir' => $baseDir,
        ));

        if (empty($genResult['ok'])) {
            $detalle = htmlspecialchars(isset($genResult['error']) ? $genResult['error'] : 'Error desconocido');
            return $this->respuestaHtml(false, $detalle);
        }

        @unlink($genResult['path']);
        $this->generarXlsPlantilla($md5Nom, $genResult['cabecera_xls'], $genResult['filas_xls'], $baseDir);

        $html = '<br><br><center><h2>Plantilla XLS Banco de Venezuela</h2>'
            . '<p>' . (int) $genResult['cant'] . ' filas listas desde la <b>fila 23</b> (hoja Cobranza).</p>'
            . '<p>Copie los datos a su archivo macro o use el XLS generado y ejecute <em>Generar Archivo TXT</em>.</p>'
            . '<a href="' . __LOCALWWW__ . '/tmp/cobranza_venezuela/' . $md5Nom . '_plantilla.xlsx" download="' . $md5Nom . '_plantilla.xlsx">+ Descargar XLS</a>'
            . '</center>';
        return $html;
    }

    private function generarXlsPlantilla($md5Nom, $cabecera, $filas, $baseDir) {
        if (!class_exists('PHPExcel', false)) {
            require_once APPPATH . 'libraries/include.xls.inc.php';
        }

        $book = new PHPExcel();
        $sheet = $book->getActiveSheet();
        $sheet->setTitle('Cobranza');

        $this->xlsCeldaTexto($sheet, 'E9', $cabecera['rif_letra']);
        $this->xlsCeldaTexto($sheet, 'E10', $cabecera['rif_numero']);
        $this->xlsCeldaTexto($sheet, 'E11', $cabecera['ref_credito_cuentas']);
        $this->xlsCeldaTexto($sheet, 'E12', $cabecera['ref_credito_tdc_bdv']);
        $this->xlsCeldaTexto($sheet, 'E13', $cabecera['ref_credito_tdc_otros']);
        $this->xlsCeldaTexto($sheet, 'I10', $cabecera['lote']);
        $this->xlsCeldaTexto($sheet, 'I11', $cabecera['fecha_envio']);
        $this->xlsCeldaTexto($sheet, 'I12', $cabecera['fecha_valor']);
        $this->xlsCeldaTexto($sheet, 'I13', $cabecera['fecha_culminacion']);

        $sheet->setCellValue('A9', 'Letra RIF Empresa');
        $sheet->setCellValue('A10', 'Numero RIF Empresa');
        $sheet->setCellValue('A11', 'Ref. Credito Cuentas');
        $sheet->setCellValue('A12', 'Ref. Credito TDC BDV');
        $sheet->setCellValue('A13', 'Ref. Credito TDC Otros');
        $sheet->setCellValue('G10', 'Numero de Lote');
        $sheet->setCellValue('G11', 'Fecha Envio');
        $sheet->setCellValue('G12', 'Fecha Valor Lote');
        $sheet->setCellValue('G13', 'Fecha Culminacion');

        $headers = array(
            'Letra CI/RIF', 'Numero CI/RIF', 'Codigo Cliente', 'Tipo Pago (C/T)',
            'Tipo Cuenta (0/1/2)', 'Nro Cuenta', 'Nro TDC', 'CVV', 'Expiracion',
            'Fecha Valor Debito', 'Monto Bs.', 'Referencia Debito', 'E-mail',
        );
        $col = 'A';
        foreach ($headers as $h) {
            $sheet->setCellValue($col . '22', $h);
            $col++;
        }

        $colsTexto = array(0, 1, 2, 5, 11);
        $row = 23;
        foreach ($filas as $fila) {
            $cIdx = 0;
            $c = 'A';
            foreach ($fila as $valor) {
                $celda = $c . $row;
                if (in_array($cIdx, $colsTexto, true)) {
                    $this->xlsCeldaTexto($sheet, $celda, $valor);
                } else {
                    $sheet->setCellValue($celda, $valor);
                }
                $c++;
                $cIdx++;
            }
            $row++;
        }

        $path = $baseDir . 'tmp/cobranza_venezuela/' . $md5Nom . '_plantilla.xlsx';
        $writer = PHPExcel_IOFactory::createWriter($book, 'Excel2007');
        $prevReporting = error_reporting();
        error_reporting($prevReporting & ~E_DEPRECATED & ~E_USER_DEPRECATED);
        try {
            $writer->save($path);
        } finally {
            error_reporting($prevReporting);
        }
    }

    private function xlsCeldaTexto($sheet, $celda, $valor) {
        $sheet->setCellValueExplicit(
            $celda,
            (string) $valor,
            PHPExcel_Cell_DataType::TYPE_STRING
        );
    }

    private function respuestaHtml($ok, $msg) {
        $titulo = $ok ? 'Operación completada' : 'No se pudo completar';
        return '<br><br><center><h2>' . $titulo . '</h2><p>' . $msg . '</p></center>';
    }
}
