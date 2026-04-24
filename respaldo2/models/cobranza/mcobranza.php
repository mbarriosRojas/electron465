<?php

/**
 * Generar Control de Cuotas Programadas
 * Crear listado del plan de pago toma en cuenta un arreglo
 * de funciones en: Mensual | Quincenal 15-30 y 10-25
 *
 * Creado Por: Carlos Pena
 * Elaborado : 05/05/2013
 *
 * @copyright GenProg (Generacion de Programadores)
 * @author Crash
 *
 */
class MCobranza extends Model {

  protected $tbl = 't_lista_cobros';

  public $empresa;

  public $nomina;

  public $periocidad;

  public $fecha;

  public $desde;
  //Desde donde arranca el mes

  public $hasta;
  //Hasta donde arranca el mes

  public $formaContrato;

  //Mes del Pago a fines de las cuotas
  public $mesp;

  //Año de la cuota
  public $anop;

  /**
   * Generar Plan de Cobro, implementar cronograma de pagos
   *
   * @param Fecha de Inicio de Descuento
   * @param Cantidad Cuotas Programandas
   * @param Periodo Control
   *
   * @return array() Plan
   */
  private function GPlanCobro($sFec, $iCuo, $iPer) {
    $arr = array();
    $fechap = explode('-', $sFec);
    $dia_inicio = $fechap[2];
    $mes_inicio = $fechap[1];
    $ano_inicio = $fechap[0];
    for ($i = 1; $i <= $iCuo; $i++) {      
      $base_mes = 0;
      $tiempo = 0;
      switch($iPer) {
        case '0' :
          $base_mes = 1 / 4;
          break;
        case '1' :
          $base_mes = 1 / 2;
          break;
        case '2' :
          $base_mes = 1 / 2;
          break;
        case '3' :
          $base_mes = 1 / 2;
          break;
        case '4' :
          $base_mes = 1;
          break;
        case '5' :
          $base_mes = 3;
          break;
        case '6' :
          $base_mes = 6;
          break;
        case '7' :
          $base_mes = 12;
          break;
        case '8' :
          $base_mes = 1;
          break;
        case '9' :
          $base_mes = 1;
          break;
      }
      $tiempo = ($i - 1) * $base_mes;
      $tiempo_picado = explode('.', $tiempo);
      $ano_t = (int)($tiempo_picado[0] / 12);
      $ano_t += $ano_inicio;
      $mes_t = $tiempo_picado[0] % 12;
      $dia_t = $dia_inicio;
      if (isset($tiempo_picado[1])) {
        switch($tiempo_picado[1]) {
          case 25 :
            $dia_t += 7;
            break;
          case 5 :
            $dia_t += 15;
            break;
          case 75 :
            $dia_t += 21;
            break;
        }
      }
      if ($dia_t > 30) {
        $mes_t += 1;
        //$diferencia = $dia_t - 30;
        $dia_t -= 30;
      }
      $suma_meses = $mes_t + $mes_inicio;
      if ($suma_meses > 12) {
        $ano_t += 1;
        $mes_t = $suma_meses - 12;
      } else {
        $mes_t = $suma_meses;
      }
      $m = '';
      if ($mes_t < 10) {
        $m = 0;
      }
      $arr[] = array("cuota" => $i, "fecha" => $dia_t . "-" . $m . $mes_t . "-" . $ano_t);
    }
    return $arr;
  }

  /**
   * Control de Pagos busca un contrato returna fecha_inicio_cobro,
   * numero_cuotas, periocidad invoca al metodos privados del GPlanCobro
   *
   * @param Contrato
   *
   * @return array()
   */
  private function CPagos($sCon = '') {
    $arr = array();
    $arr_aux = array();

    $sQuery = 'SELECT  fecha_inicio_cobro, monto_cuota, numero_cuotas, 
		periocidad, cantidad,  SUM(monto) AS pago FROM t_clientes_creditos LEFT JOIN t_lista_cobros ON 
		t_clientes_creditos.contrato_id=t_lista_cobros.credito_id  WHERE 
		t_clientes_creditos.contrato_id=\'' . $sCon . '\' GROUP BY contrato_id';

    $Consulta = $this -> db -> query($sQuery);
    $dMonto = 0;
    $dCuota = 0;
    $sFec = '';
    $iCuo = 0;
    $iPer = 0;
    $dPago = 0;
    $dCuota = 0;
    if ($Consulta -> num_rows() > 0) {
      foreach ($Consulta->result() as $row) {
        $dMonto = $row -> pago;
        $dCuota = $row -> monto_cuota;
        $sFec = $row -> fecha_inicio_cobro;
        $iCuo = $row -> numero_cuotas;
        $iPer = $row -> periocidad;
      }
    }
    $arr = $this -> GPlanCobro($sFec, $iCuo, $iPer);
    //Metodo de Invocacion
    foreach ($arr as $sC => $sV) {
      $dTotal = 0;
      if ($dMonto > 0) {
        $dPago = $dMonto - $dCuota;
        if ($dPago <= 0) {
          $dTotal = $dMonto;
          $dMonto = 0;
        } else {
          $dTotal = $dCuota;
          $dMonto -= $dCuota;
        }
      }
      $sVal = 'Pendiente';
      if ($dTotal == $dCuota) {
        $sVal = 'Pagada';
      } elseif ($dTotal > 0) {
        $sVal = 'Abonada';
      }
      $arr_aux[] = array('cuota' => $sV['cuota'], 'fecha' => $sV['fecha'], 'monto' => $dTotal, 'estatus' => $sVal);
    }
    return $arr_aux;
  }

  /**
   * Control de Pagos busca un grupo de facturas returna lista de
   * contratos invoca al metodos privados del CPagos
   *
   * @param Factura
   *
   * @return array()
   */

  function GListaFactura($sFac) {
    $arr = array();
    $sQuery = 'SELECT * FROM t_clientes_creditos WHERE numero_factura=\'' . $sFac . '\'';
    $Consulta = $this -> db -> query($sQuery);
    $empresa = '';
    if ($Consulta -> num_rows() > 0) {
      foreach ($Consulta->result() as $row) {
        $arr['empresa'] = $row -> empresa;
        $arr[$row -> forma_contrato] = $this -> CPagos($row -> contrato_id);
      }
    }
    return $arr;
  }

  /**
   * Consulta y verifica que se ha pagao a la fecha actual de la consulta
   * Genera un listado basado en TGrid
   *
   * @param Numero de Contratos
   *
   * @return Php|Json Formato Grid
   */
  function GListaPagos($sCon = '') {
    $oFil = array();

    $oCabezera[1] = array("titulo" => "COUTA", "atributos" => "width:40px", "buscar" => 0, "oculto" => 1);
    $oCabezera[2] = array("titulo" => "FECHA", "atributos" => "width:40px;text-align: left;", "buscar" => 0);
    $oCabezera[3] = array("titulo" => "MONTO", "atributos" => "width:50px", "total" => 1);
    $oCabezera[4] = array("titulo" => "ESTATUS", "atributos" => "width:250px", "buscar" => 0);
    $i = 0;
    $arr = $this -> CPagos($sCon);
    //Metodo de Invocacion
    foreach ($arr as $sC => $sV) {
      ++$i;
      $oFil[$i] = array("1" => $sV['cuota'], //
      "2" => $sV['fecha'], //
      "3" => $sV['monto'], //
      "4" => $sV['estatus'] //
      );
    }
    $oTable = array("Cabezera" => $oCabezera, "Cuerpo" => $oFil, "Origen" => "json");
    $tabla['php'] = $oTable;
    $tabla['json'] = json_encode($oTable);
    return $tabla;
  }

  private function Limpiar() {

    $empresa = 'empresa = ' . $this -> empresa;
    if ($this -> empresa == '9') {
      $empresa = 'empresa LIKE \'%\'';
    }

    $periocidad = 'periocidad = ' . $this -> periocidad;
    if ($this -> periocidad == '9') {
      $periocidad = 'periocidad LIKE \'%\'';
    }

    $Limpiar = 'DELETE FROM t_control_pagos WHERE  ' . $empresa . ' 
    AND cobrado_en=\'' . $this -> nomina . '\' AND ' . $periocidad . ' AND forma_contrato=' . $this -> formaContrato . ' AND  nomina_procedencia != \'-------------\' ;';

    $this -> db -> query($Limpiar);
    //echo $Limpiar;
  }

  private function Insertar($fecha) {

    $empresa = 'empresa = ' . $this -> empresa;
    if ($this -> empresa == '9') {
      $empresa = 'empresa LIKE \'%\'';
    }

    $periocidad = 'periocidad = ' . $this -> periocidad;
    if ($this -> periocidad == '9') {
      $periocidad = 'periocidad LIKE \'%\'';
    }

    $insertar = 'INSERT INTO `t_control_pagos`(`documento_id`, `contrato_id`, `fecha_inicio_cobro`, `banco_1`, `cuenta_1`, `numero_cuotas`, `monto_cuota`, `monto`, `resta`, 
        `cobrado_en`, `empresa`, `periocidad`,forma_contrato, nomina_procedencia, nombre_completo, credito_id)
      SELECT t_personas.documento_id, B.contrato_id, B.fecha_inicio_cobro, banco_1,cuenta_1, B.numero_cuotas,B.monto_cuota,B.monto,B.resta,B.cobrado_en,B.empresa,B.periocidad,B.forma_contrato,B.nomina_procedencia ,
      CONCAT(primer_apellido,\' \', segundo_apellido,\' \',primer_nombre,\' \', segundo_nombre) AS nombre_completo, B.ID   FROM t_personas
      JOIN (SELECT * FROM (SELECT t_clientes_creditos.documento_id, contrato_id,t_clientes_creditos.fecha_inicio_cobro,
      t_clientes_creditos.numero_cuotas, t_clientes_creditos.monto_cuota,t_clientes_creditos.monto_total,
      IFNULL(sum( t_lista_cobros.monto ),0) AS monto, ( t_clientes_creditos.monto_total - IFNULL(sum( t_lista_cobros.monto ),0) ) AS resta,
      t_clientes_creditos.cobrado_en, t_clientes_creditos.empresa, periocidad, forma_contrato, nomina_procedencia, t_clientes_creditos.credito_id AS ID
      FROM t_clientes_creditos LEFT JOIN t_lista_cobros ON t_clientes_creditos.contrato_id = t_lista_cobros.credito_id
      WHERE t_clientes_creditos.cobrado_en = \'' . $this -> nomina . '\' AND ' . $empresa . '  AND ' . $periocidad . ' AND marca_consulta != 6 AND cantidad != 0
      GROUP BY t_clientes_creditos.contrato_id ) AS A
      WHERE A.fecha_inicio_cobro <= \'' . $this -> desde . '\'
      ORDER BY A.fecha_inicio_cobro) AS B ON t_personas.documento_id=B.documento_id;';

    $this -> db -> query($insertar);
    //echo $insertar;

    return true;

  }

  private function Listar() {
    $empresa = 'empresa = ' . $this -> empresa;
    if ($this -> empresa == '9') {
      $empresa = 'empresa LIKE \'%\'';
    }

    $periocidad = 'periocidad = ' . $this -> periocidad;
    if ($this -> periocidad == '9') {
      $periocidad = 'periocidad LIKE \'%\'';
    }

    /*
     $Consulta = 'SELECT t_control_pagos.documento_id AS cedula, contrato_id, fecha_inicio_cobro,banco_1, cuenta_1, numero_cuotas, monto_cuota, t_control_pagos.monto,resta, cobrado_en,
     empresa, forma_contrato, periocidad, IFNULL(A.Abono,0) AS solo_abono, A.fecha, nombre_completo, t_control_pagos.credito_id
     FROM t_control_pagos LEFT JOIN (SELECT credito_id, fecha, sum( monto ) AS Abono FROM t_lista_cobros
     WHERE t_lista_cobros.fecha BETWEEN \'' . $this -> desde . '\' AND \'' . $this -> hasta . '\' GROUP BY t_lista_cobros.credito_id )
     AS A ON t_control_pagos.contrato_id = A.credito_id  LEFT JOIN t_estadoejecucion ON t_control_pagos.contrato_id=t_estadoejecucion.oidc
     WHERE (t_estadoejecucion.oide > 3 OR ISNULL(t_estadoejecucion.oide) ) AND
     cobrado_en=\'' . $this -> nomina . '\' AND  empresa = ' . $this -> empresa . ' AND periocidad=' . $this -> periocidad . ' AND forma_contrato=' . $this -> formaContrato . '
     AND  nomina_procedencia != \'-------------\' ;';
     */

    $Consulta = 'SELECT t_control_pagos.documento_id AS cedula, contrato_id, fecha_inicio_cobro,banco_1, cuenta_1, numero_cuotas, monto_cuota, t_control_pagos.monto,resta, cobrado_en, 
      empresa, forma_contrato, periocidad, IFNULL(A.Abono,0) AS solo_abono, A.fecha, nombre_completo, t_control_pagos.credito_id 
      FROM t_control_pagos LEFT JOIN (SELECT credito_id, fecha, sum( monto ) AS Abono FROM t_lista_cobros 
      WHERE t_lista_cobros.fecha BETWEEN \'' . $this -> desde . '\' AND \'' . $this -> hasta . '\' GROUP BY t_lista_cobros.credito_id ) 
      AS A ON t_control_pagos.contrato_id = A.credito_id  LEFT JOIN t_estadoejecucion ON t_control_pagos.contrato_id=t_estadoejecucion.oidc 
      WHERE (t_estadoejecucion.oide > 3 OR ISNULL(t_estadoejecucion.oide) ) AND cobrado_en=\'' . $this -> nomina . '\' AND
      ' . $empresa . ' 
      AND ' . $periocidad . ' AND forma_contrato=' . $this -> formaContrato . ' AND (resta > 0 OR fecha !=\'NULL\');';

    //echo $Consulta;
    $Lista = $this -> db -> query($Consulta);
    return $Lista -> result();
  }

  function lstModalidad() {
    $lst = array();
    $sConsulta = 'SELECT oid,nomb FROM t_modalidad';
    $rs = $this -> db -> query($sConsulta);
    foreach ($rs -> result() as $row) {
      $lst[$row -> oid] = $row -> nomb;
    }
    return $lst;
  }

  function CrearGrid() {

    $oFila = array();

    $oCabezera[1] = array("titulo" => "Cédula", "atributos" => "width:80px", "buscar" => 1);
    $oCabezera[2] = array("titulo" => "Nombre Completo", "atributos" => "width:350px");
    $oCabezera[3] = array("titulo" => "Contrato", "atributos" => "width:80px");
    $oCabezera[4] = array("titulo" => "Cuota", "atributos" => "width:80px;text-align:center", "total" => 1);
    $oCabezera[5] = array("titulo" => "Modalidad", 'tipo' => 'combo_fijo', "atributos" => "width:80px");
    $oCabezera[6] = array("titulo" => "Abono", "atributos" => "width:80px;text-align:center", "total" => 1);
    $oCabezera[7] = array("titulo" => "Pago", 'tipo' => 'texto_fijo', "atributos" => "width:80px");
    $oCabezera[8] = array("titulo" => "Resta", "atributos" => "width:80px;text-align:center", "total" => 1);
    $oCabezera[9] = array("titulo" => "FECHA ARCHIVO", 'tipo' => 'calendario', "atributos" => "width:60px");
    $oCabezera[10] = array("titulo" => "Observacion", 'tipo' => 'texto_fijo', "atributos" => "width:60px");
    $oCabezera[11] = array("titulo" => "MES", 'oculto' => 1);
    $oCabezera[12] = array("titulo" => "AÑO", 'oculto' => 1);

    $this -> Limpiar();
    //echo '<br><br>';

    $this -> Insertar(date("Y/m/d"));
    //echo '\n\n<br><br>';

    $rs = $this -> Listar();

    $i = 0;
    $suma_total = 0;
    $cuota = 0;
    foreach ($rs as $fila) {
      $color = ''; // NEGRO
      $cerrar = '';
      $cuota =  $fila -> monto_cuota;
      if ($fila -> resta < $fila -> monto_cuota) {        
        $color = '<font color=\'#1964B5\' size="2"><b>'; // AZUL
        $cuota =  $fila -> resta  ;
        $cerrar = '</b></font>';
      }
      if($cuota == $fila->solo_abono){
        $cuota = '0.00';  
        $color = '<font color=\'#1D5A01\' size="2"><b>'; // VERDE
        $cerrar = '</b></font>';
      }
      if($fila -> resta == 0){
        $color = '<font color=\'#DC2500\'size="2"><b>'; // ROJO
        $cerrar = '</b></font>';
      }      
      
      $suma_total += $fila -> monto_cuota; 
      $oFila[++$i] = array(//
      '1' => $fila -> cedula, //
      '2' => $color . $fila -> nombre_completo . $cerrar, //
      '3' => $fila -> contrato_id, //
      '4' => $cuota, //
      '5' => 9, //
      '6' => $fila -> solo_abono, //
      '7' => '', //
      '8' => $fila -> resta, //
      '9' => $this -> fecha, //
      '10' => '', //
      '11' => $this -> mesp, //
      '12' => $this -> anop);
    }
    $leyenda = '<br><center><h2>
      Monto Total de cuotas  ( ' . number_format($suma_total, 2, ".", ",") . ' ) <br>' ;
    
    $objetos = array("5" => $this -> lstModalidad());
    $intNivel = $this -> session -> userdata('nivel');
    if($intNivel == 0 || $intNivel == 8 || $intNivel == 9  || $intNivel == 3 ){      
      $oTable = array("Cabezera" => $oCabezera, "Cuerpo" => $oFila, "Origen" => "json", "Objetos" => $objetos, 'Editable' => 'Guardar_Lote', 'Parametros' => '1,3,5,7,9,10,11,12', 'leyenda' => $leyenda);
    }else{
      $oTable = array("Cabezera" => $oCabezera, "Cuerpo" => $oFila, "Origen" => "json", "Objetos" => $objetos, 'leyenda' => $leyenda);
    }
    return json_encode($oTable);

  }

  function Guardar($arr, $oid) {
    $insertar = array();
    $insert = "INSERT INTO t_lista_cobros (documento_id,credito_id,mes,descripcion,fecha,monto,mesp,anop,moda,farc,usua) VALUES ";
    $linea = '';
    foreach ($arr as $sC) {

      if ($sC[3] != '') {
        $mnt = $sC[3];
        $monto = explode(' ', $sC[3]);
        if (count($monto) > 1) {
          foreach ($monto as $k) {
            $insertar[] = array(//
            'documento_id' => $sC[0], //
            'credito_id' => $sC[1], //
            'mes' => '', //
            'descripcion' => $sC[5], //
            'fecha' =>  $sC[7] . '-' . $sC[6] . '-01', //
            'monto' => $k, // 
            'mesp' => $sC[6], //
            'anop' => $sC[7], //
            'moda' => $sC[2], //
            'farc' => $sC[4], //
            'usua' => $oid, //
            );            
            
          }

        } else {
          $insertar[] = array(//
          'documento_id' => $sC[0], //
          'credito_id' => $sC[1], //
          'mes' => '', //
          'descripcion' => $sC[5], //
          'fecha' => $sC[7] . '-' . $sC[6] . '-01', //
          'monto' => $mnt, //
          'mesp' => $sC[6], //
          'anop' => $sC[7], //
          'moda' => $sC[2], //
          'farc' => $sC[4], //
          'usua' => $oid, //
          );
          
        }

      }
    }
    $iCant = count($insertar);
    $cant = 0; 
    foreach ($insertar as $cla => $val) {
      $cant++;
      $linea .= "('" . $val['documento_id'] . "','" . $val['credito_id'] . "','','" . $val['descripcion'] . "','" . $val['fecha'] . 
                  "'," . $val['monto'] . ",'" . $val['mesp'] . "','" . $val['anop'] . "'," . $val['moda'] . ",'" . $val['farc'] . "'," . $val['usua'] . ")";
      if($cant != $iCant){
       $linea .= ',';  
      }
    }
    
    $insert .= $linea;
    //print('<pre>');
    $this->db->query($insert); 
    //print_r($insert);
    return 'Proceso exitoso...';

  }

  /**
   *
   *
   * ALTER TABLE `t_lista_cobros` ADD `mesp` INT( 2 ) NOT NULL COMMENT 'Mes de Aplicacion',
   ADD `anop` INT( 2 ) NOT NULL COMMENT 'Año de Aplicacion',
   ADD `moda` TINYINT( 1 ) NOT NULL COMMENT 'Modalidad'
   *
   *
   */

}
?>

