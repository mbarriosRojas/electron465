<?php
/*
 *  @author Carlos Enrique Peña Albarrán
 *  @package cooperativa.system.application.model.cliente
 *  @version 1.0.0
 */

class MClientee extends Model {

	var $tabla = 't_clientes_creditos';

	public function __construct() {
		parent::__construct();
	}

	function TG_Cedula($sId = null) {

		$oFil = array();
		$sTitulo = '';
		$sLeyenda = '';
		
		$set = "t_personas.documento_id AS cedula, t_personas.disponibilidad, CONCAT( primer_apellido, ' ', segundo_apellido, ' ', primer_nombre, ' ', segundo_nombre ) AS nombre, 
		t_personas.expediente_caja, t_personas.codigo_nomina_aux, tbl.numero_factura AS factura, fecha_solicitud AS solicitud, cobrado_en AS banco, SUM(t_clientes_creditos.monto_total) AS total, 
		SUM(t_clientes_creditos.cantidad) as total2,
		SUM(t_clientes_creditos.cantidad) - pagado AS resta, IF(SUM(t_clientes_creditos.cantidad)=0,'N','A') AS activo,t_clientes_creditos.marca_consulta";
		$oCabezera[1] = array("titulo" => " ", "tipo" => "detallePost", "atributos" => "width:40px", "funcion" => "MuestraDetalleReporte_Electron", "parametro" => "3,8", "atributos" => "width:12px");		
		$oCabezera[2] = array("titulo" => "Datos Basicos", "atributos" => "width:250px");
		$oCabezera[3] = array("titulo" => "Factura", "atributos" => "width:100px");
		$oCabezera[4] = array("titulo" => "Total", "total" => 1, "atributos" => "width:100px");
		$oCabezera[5] = array("titulo" => "Resta", "atributos" => "width:100px");
		$oCabezera[6] = array("titulo" => "Solicitud", "atributos" => "width:100px");
		$oCabezera[7] = array("titulo" => "Linaje");
		$oCabezera[8] = array("titulo" => "Forma De Pago", "atributos" => "width:50px");
		$oCabezera[9] = array("titulo" => "Cedula", "oculto" => 1);
		//LEFT JOIN t_responsable ON t_clientes_creditos.documento_id=t_responsable.cedula OR t_clientes_creditos.numero_factura = t_responsable.factura
		$strQuery = 'SELECT ' . $set . ' FROM t_personas
		JOIN t_clientes_creditos ON t_personas.documento_id = t_clientes_creditos.documento_id JOIN 
		(SELECT t_clientes_creditos.numero_factura, monto_total, IFNULL( SUM( monto ) , 0 ) AS pagado
		FROM t_clientes_creditos
		LEFT JOIN t_lista_cobros ON t_clientes_creditos.contrato_id = t_lista_cobros.credito_id AND t_clientes_creditos.documento_id = t_lista_cobros.documento_id 
		
		 where t_clientes_creditos.documento_id=' . $sId . ' 
		GROUP BY numero_factura) AS tbl ON tbl.numero_factura=t_clientes_creditos.numero_factura
		 where t_clientes_creditos.documento_id=' . $sId . ' GROUP BY t_clientes_creditos.numero_factura';	
		
		$sLeyenda = "<br><center><a href='" . base_url() . "index.php/electron/Imprimir_Estado_Cuenta/" . $sId . "' target='_blank'>
				<h1><font color='#1c94c4'>[ Estado De Cuenta ]</font></h1></a></center>";		
		$Consulta = $this -> db -> query($strQuery);
		$i = 0;
		$iSuspendido = 0;
		$sfactura = '';
		$sBoucher = '';
		$Conexion = $Consulta -> result();
		foreach ($Conexion as $row) {
			++$i;
			$sEtiquetaA ='';
			$sEtiquetaC ='';
			if($row->activo == 'N'){
				$sEtiquetaA ='<s><font color=red>';
				$sEtiquetaC ='</font></s>';
			}
			
			$sqlA = "SELECT marca_consulta FROM t_clientes_creditos WHERE numero_factura='" . $row -> factura . "'";
			$Consulta2 = $this -> db -> query($sqlA);
			$Conexion2 = $Consulta2 -> result();
			$forma_contrato= array();
			foreach ($Conexion2 as $row2) {
				if($row2->marca_consulta == 6){
					$forma_contrato[] = 'Voucher';
				}else{
					$forma_contrato[] = 'Domiciliacion';
				}
			}
			$arreglo = array_unique($forma_contrato);
			$cantidad = count($arreglo);
			if($cantidad == 1){
				$sBoucher = $arreglo[0];
			}else{
				$sBoucher = 'Mixto';
			}
			
			/*if($row->marca_consulta == 6){
					$sBoucher = 'Voucher';
			}else{
				$sBoucher = 'Domiciliacion';
			}*/
			$oFil[$i] = array("1" => "", 
				"2" => $sEtiquetaA . $row -> nombre . $sEtiquetaC, 
				"3" => $sEtiquetaA . $row -> factura . $sEtiquetaC, 
				"4" => $row->total2, 
				"5" => number_format($row->resta,2,".",","),
				"6" => $sEtiquetaA . $row -> solicitud . $sEtiquetaC,  
				"7" => $sEtiquetaA . $row -> banco . $sEtiquetaC,
				"8" => $sBoucher,
				"9" => $row -> cedula );
			$iSuspendido = $row->disponibilidad;
			$sTitulo = 'Cedula: V-' . $row -> cedula . '<br> Apellidos y Nombres: '  . $row -> nombre;
			
		}
		$sCliente = ' ACTIVO';
		
		$sCont = '';
			
		if($iSuspendido == 1){$sCliente = '<font color=red>SUSPENDIDO</font>';}
		$sTitulo .= '<br>ESTATUS DEL CLIENTE: ' . $sCliente . '<br>CREADO:  '. $row -> expediente_caja .'<br>MODIFICADO:  '. $row -> codigo_nomina_aux .
		'<br>&nbsp;' ;
		;
		
		$Object = array("Cabezera" => $oCabezera, "Cuerpo" => $oFil, "Origen" => "json", "Paginador" => 0, "titulo" => $sTitulo, "leyenda" => $sLeyenda);
		return json_encode($Object);
	}

	
	function Detalles_Facturas_Contratos($Arr){
		
		$sqlDetalle = "SELECT documento_id, contrato_id, fecha_solicitud, codigo_n, codigo_n_a, expediente_c, fecha_verificado, monto_total, 
						nomina_procedencia, estatus, forma_contrato, cobrado_en, empresa, fecha_inicio_cobro, monto_cuota, periocidad, numero_cuotas, marca_consulta
						FROM t_clientes_creditos WHERE numero_factura = '" . $Arr['factura'] . "'";
		$Consulta = $this -> db -> query($sqlDetalle);
		$sTitulo = '';
		$sLeyenda = '';
		$oCabezera[1] = array("titulo" => " ", "tipo" => "detallePre", "atributos" => "width:18px", "ruta" => __IMG__ . "botones/abrir.png");		
		$oCabezera[2] = array("titulo" => "Cedula",  "atributos" => "width:70px");
		$oCabezera[3] = array("titulo" => "Contrato",  "atributos" => "width:70px");		
		$oCabezera[4] = array("titulo" => "Monto Cuota",  "atributos" => "width:70px");
		$oCabezera[5] = array("titulo" => "Numero Cuota",  "atributos" => "width:70px");
		$oCabezera[6] = array("titulo" => "Tipo <br>Credito",  "atributos" => "width:80px");
		$oCabezera[7] = array("titulo" => "Estatus",  "atributos" => "width:80px");
		$oCabezera[8] = array("titulo" => "Fecha Solicitud",  "atributos" => "width:80px");		
		$oCabezera[9] = array("titulo" => "Monto Total",  "atributos" => "width:60px");
		$oCabezera[10] = array("titulo" => "Monto Pagado",  "atributos" => "width:60px");
		$oCabezera[11] = array("titulo" => "Monto Restante",  "atributos" => "width:60px");
		
		$sCedula = '';
		$sContrato = '';
		$i = 0;
		$Conexion = $Consulta -> result();
		$sC = '';
		$total_factura=0;
		$total_resta=0;
		$pagado = 0;
		$resta = 0;
		foreach ($Conexion as $row) {
			$mAnulado = '';
			$iAnulado=0;
			$etiqueta1='';
			$etiqueta2='';
			++$i;
			$montos = $this -> montos_contrato($row -> contrato_id,$row->documento_id);
			if($montos['resta'] != null){
				$resta = $montos['resta'];
			}else{
				$resta = $row -> monto_total;
			}
			if($montos['pagado'] != null){
				$pagado = $montos['pagado'];
			}else{
				$pagado = 0;
			}
			$sC = $row ->documento_id;
			if($row -> estatus != 3){
				$total_factura = $total_factura + $row -> monto_total;
				$total_resta = $total_resta + $resta;	
			}
			
			$sCedula = $row -> documento_id;
			$sContrato = $row -> contrato_id;
			
			$html = '<br><p><font color="#1c94c4">Nomina:</font> <b>'.$row -> nomina_procedencia . '</b> <br> <font color="#1c94c4">Linaje:</font><b>'. 
				$row -> cobrado_en .'</b> <br> <font color="#1c94c4">Empresa: </font><b>' . $this -> Empresa($row -> empresa) .
				'</b><br><font color="#1c94c4">F.Inicio Cobro:</font><b>' . $row -> fecha_inicio_cobro .  
				' </b><br><font color="#1c94c4">Periocidad:</font><b>' . $this -> Periocidad($row -> periocidad) .'</b>'	;
				
			$html .= '</p><br><i><b>Ubicaci&oacute;n:</b> <font color="#1c94c4">' . $row->codigo_n . '</font><br><b> Creado Por: </b>' . $row->expediente_c . 
			'.<br><b>Modificado Por</b> : ' . $row->codigo_n_a . '<br><p align=right>Fecha de la Ultima Revisi&oacute;n: ' . $row->fecha_verificado . '</p>';
			
			/*$queryAnulados = "SELECT * FROM _th_sistema WHERE referencia ='". $sContrato ."' AND tipo=9 LIMIT 1";
			$consultaAnulado = $this -> db -> query($queryAnulados);
			if($consultaAnulado -> num_rows() > 0){
				$iAnulado=1;
				foreach($consultaAnulado -> result() as $rowAnulado){
					$mAnulado .= "<br>Motivo Anulacion:<font color=red>" . $rowAnulado -> motivo . " </font>| <br>Usuario:<font color=red>" . $rowAnulado -> usuario . " </font>| 
					Peticion:<font color=red>" . $rowAnulado -> peticion . " </font>| fecha:<font color=red>" . $rowAnulado -> fecha . " </font>| ";
				}
			}
			
			$queryAnulados2 = "SELECT * FROM _th_sistema WHERE referencia ='". $Arr['factura'] ."' AND tipo=12 LIMIT 1";
			$consultaAnulado2 = $this -> db -> query($queryAnulados2);
			$mAnulado2 ='';
			if($consultaAnulado2 -> num_rows() > 0){
				$iAnulado=1;
				foreach($consultaAnulado2 -> result() as $rowAnulado2){
					$mAnulado2 .= "<br>Motivo Anulacion:<font color=red>" . $rowAnulado2 -> motivo . " </font>| <br>Usuario:<font color=red>" . $rowAnulado2 -> usuario . " </font>| 
					Peticion:<font color=red>" . $rowAnulado2 -> peticion . " </font>| fecha:<font color=red>" . $rowAnulado2 -> fecha . " </font>| ";
				}
			}*/
			
			if($iAnulado == 1){
				$etiqueta1="<s><font color=red>";
				$etiqueta2="</font></s>";
			}
			//$boucher = 'Ver';
			
			$oFil[$i] = array(	
				"1" => $html.$mAnulado, //
				"2" => $etiqueta1 . $row -> documento_id . $etiqueta2, //
				"3" => $etiqueta1 . $row -> contrato_id . $etiqueta2, //
				"4" => $etiqueta1 . number_format($row -> monto_cuota,2,".",",") . $etiqueta2, //
				"5" => $etiqueta1 . $row -> numero_cuotas . $etiqueta2, //
				"6" => $etiqueta1 . $this -> Tipo_Contrato($row -> forma_contrato) . $etiqueta2, //
				"7" => $etiqueta1 . $this->Estatus_Creditos($row -> estatus) . $etiqueta2, //  
				"8" => $etiqueta1 . $row -> fecha_solicitud . $etiqueta2, // 				
				"9" => $etiqueta1.number_format($row -> monto_total,2,".",",") . $etiqueta2, // 
				"10" => $etiqueta1.number_format($pagado, 2, ".", ",") . $etiqueta2, // 
				"11" => $etiqueta1.number_format($resta, 2, ".", ",") . $etiqueta2,
			);
		}
		
		$sLeyenda = '<br><center><h3>
			Monto Total de Factura ( '. number_format($total_factura , 2, ".",",") .' ) <br>
			Monto Resta de Factura ( '. number_format($total_resta , 2, "." ,",") .' ) <br></h3>';
		$mAnulado2 = "";
		$Object = array("Cabezera" => $oCabezera, "Cuerpo" => $oFil, "Origen" => "json", "titulo"=>"<br>","titulo" => $mAnulado2, "leyenda" => $sLeyenda);
		return json_encode($Object);
	}
	
	function Detalles_Facturas_Voucher($Arr){
		$query = "select * from t_lista_voucher where cid = '". $Arr['factura']."' order by fecha";
		$Consulta = $this -> db -> query($query);
		$iCantidad = $Consulta -> num_rows();
		$Conexion = $Consulta -> result();
		
		$oCabezera[1] = array("titulo" => "Voucher", "atributos" => "width:80px", "buscar" => 0);
		$oCabezera[2] = array("titulo" => "Factura", "atributos" => "width:250px", "buscar" => 0);
		$oCabezera[3] = array("titulo" => "Monto", "atributos" => "width:100px");
		$oCabezera[4] = array("titulo" => "Fecha", "atributos" => "width:100px");
		$oCabezera[5] = array("titulo" => "Estado", "atributos" => "width:100px");
		$sTitulo = '';
		$txtEstatus = '';
		
		if ($iCantidad > 0) {
			$i = 0;
			$total = 0;
			$pagado = 0;
			foreach ($Conexion as $row) {
				++$i;
				$total += $row -> monto;
				$txtEstatus = '';
				switch ($row -> estatus) {
					case 0:
						$txtEstatus = 'Entregado al Cliente';		
						break;
					case 1:
						$pagado += $row -> monto;
						$txtEstatus = 'Pagado';		
						break;
					case 2:
						$txtEstatus = 'No Pagado';		
						break;
					default:
						
						break;
				}
				$oFil[$i] = array("1" => $row -> ndep, "2" => $row -> cid, "3" => $row -> monto, "4" => $row -> fecha , "5"=> $txtEstatus);
			}
			$sTitulo = "<br>Voucher<br>";
			$sLeyendaV = '<br><center><h2>
			Monto Total En Voucher ( '. number_format($total , 2, ".",",") .' ) <br>
			Monto Pagado En Voucher ( '. number_format($pagado , 2, "." ,",") .' ) <br>';
		}
		
		$oTable = array("Cabezera" => $oCabezera, "Cuerpo" => $oFil, "Origen" => "json","titulo"=>$sTitulo , "leyenda" => $sLeyendaV);
		//$oTable = array("Cabezera" => $Consulta -> list_fields(), "Cuerpo" => $Consulta -> result(), "Origen" => "Mysql");
		
		$sqlDetalle2 = "SELECT documento_id, contrato_id, fecha_solicitud, codigo_n, codigo_n_a, expediente_c, fecha_verificado, monto_total, 
						nomina_procedencia, estatus, forma_contrato, cobrado_en, empresa, fecha_inicio_cobro, monto_cuota, periocidad, numero_cuotas, marca_consulta
						FROM t_clientes_creditos WHERE numero_factura = '" . $Arr['factura'] . "'";
		$Consulta2 = $this -> db -> query($sqlDetalle2);
		$sTitulo = '';
		$sLeyenda = '';
		$oCabezera2[1] = array("titulo" => " ", "tipo" => "detallePre", "atributos" => "width:18px", "ruta" => __IMG__ . "botones/abrir.png");
		$oCabezera2[2] = array("titulo" => "Cedula",  "atributos" => "width:70px");
		$oCabezera2[3] = array("titulo" => "Contrato" ,"atributos" => "width:70px");		
		$oCabezera2[4] = array("titulo" => "Monto Cuota",  "atributos" => "width:70px");
		$oCabezera2[5] = array("titulo" => "Numero Cuota",  "atributos" => "width:70px");
		$oCabezera2[6] = array("titulo" => "Tipo <br>Credito",  "atributos" => "width:80px");
		$oCabezera2[7] = array("titulo" => "Estatus",  "atributos" => "width:80px");
		$oCabezera2[8] = array("titulo" => "Fecha Solicitud",  "atributos" => "width:80px");		
		$oCabezera2[9] = array("titulo" => "Monto Total",  "atributos" => "width:60px");
		$oCabezera2[10] = array("titulo" => "Monto Pagado",  "atributos" => "width:60px");
		$oCabezera2[11] = array("titulo" => "Monto Restante",  "atributos" => "width:60px");
		
		$sCedula = '';
		$sContrato = '';
		$i = 0;
		$Conexion2 = $Consulta2 -> result();
		$sC = '';
		$total_factura=0;
		$total_resta=0;
		$pagado = 0;
		$resta = 0;
		foreach ($Conexion2 as $row) {
			$mAnulado = '';
			$iAnulado=0;
			$etiqueta1='';
			$etiqueta2='';
			++$i;
			$montos = $this -> montos_contrato($row -> contrato_id,$row->documento_id);
			if($montos['resta'] != null){
				$resta = $montos['resta'];
			}else{
				$resta = $row -> monto_total;
			}
			if($montos['pagado'] != null){
				$pagado = $montos['pagado'];
			}else{
				$pagado = 0;
			}
			$sC = $row ->documento_id;
			if($row -> estatus != 3){
				$total_factura = $total_factura + $row -> monto_total;
				$total_resta = $total_resta + $resta;	
			}
			
			$sCedula = $row -> documento_id;
			$sContrato = $row -> contrato_id;
			
			$html = '<br><p><font color="#1c94c4">Nomina:</font> <b>'.$row -> nomina_procedencia . '</b> <br> <font color="#1c94c4">Linaje:</font><b>'. 
				$row -> cobrado_en .'</b> <br> <font color="#1c94c4">Empresa: </font><b>' . $this -> Empresa($row -> empresa) .
				'</b><br><font color="#1c94c4">F.Inicio Cobro:</font><b>' . $row -> fecha_inicio_cobro .  
				' </b><br><font color="#1c94c4">Periocidad:</font><b>' . $this -> Periocidad($row -> periocidad) .'</b>'	;
				
			$html .= '</p><br><i><b>Ubicaci&oacute;n:</b> <font color="#1c94c4">' . $row->codigo_n . '</font><br><b> Creado Por: </b>' . $row->expediente_c . 
			'.<br><b>Modificado Por</b> : ' . $row->codigo_n_a . '<br><p align=right>Fecha de la Ultima Revisi&oacute;n: ' . $row->fecha_verificado . '</p>';
			
			$queryAnulados = "SELECT * FROM _th_sistema WHERE referencia ='". $sContrato ."' AND tipo=9 LIMIT 1";
			$consultaAnulado = $this -> db -> query($queryAnulados);
			if($consultaAnulado -> num_rows() > 0){
				$iAnulado=1;
				foreach($consultaAnulado -> result() as $rowAnulado){
					$mAnulado .= "<br>Motivo Anulacion:<font color=red>" . $rowAnulado -> motivo . " </font>| <br>Usuario:<font color=red>" . $rowAnulado -> usuario . " </font>| 
					Peticion:<font color=red>" . $rowAnulado -> peticion . " </font>| fecha:<font color=red>" . $rowAnulado -> fecha . " </font>| ";
				}
			}
			
			$queryAnulados2 = "SELECT * FROM _th_sistema WHERE referencia ='". $Arr['factura'] ."' AND tipo=12 LIMIT 1";
			$consultaAnulado2 = $this -> db -> query($queryAnulados2);
			$mAnulado2 ='';
			if($consultaAnulado2 -> num_rows() > 0){
				$iAnulado=1;
				foreach($consultaAnulado2 -> result() as $rowAnulado2){
					$mAnulado2 .= "<br>Motivo Anulacion:<font color=red>" . $rowAnulado2 -> motivo . " </font>| <br>Usuario:<font color=red>" . $rowAnulado2 -> usuario . " </font>| 
					Peticion:<font color=red>" . $rowAnulado2 -> peticion . " </font>| fecha:<font color=red>" . $rowAnulado2 -> fecha . " </font>| ";
				}
			}
			
			
			if($iAnulado == 1){
				$etiqueta1="<s><font color=red>";
				$etiqueta2="</font></s>";
			}
			//$boucher = 'Ver';
			
			$oFil2[$i] = array(	
				"1" => $html.$mAnulado, //
				"2" => $etiqueta1 . $row -> documento_id . $etiqueta2, //
				"3" => $etiqueta1 . $row -> contrato_id . $etiqueta2, //
				"4" => $etiqueta1 . number_format($row -> monto_cuota,2,".",",") . $etiqueta2, //
				"5" => $etiqueta1 . $row -> numero_cuotas . $etiqueta2, //
				"6" => $etiqueta1 . $this -> Tipo_Contrato($row -> forma_contrato) . $etiqueta2, //
				"7" => $etiqueta1 . $this->Estatus_Creditos($row -> estatus) . $etiqueta2, //  
				"8" => $etiqueta1 . $row -> fecha_solicitud . $etiqueta2, // 				
				"9" => $etiqueta1.number_format($row -> monto_total,2,".",",") . $etiqueta2, // 
				"10" => $etiqueta1.number_format($pagado, 2, ".", ",") . $etiqueta2, // 
				"11" => $etiqueta1.number_format($resta, 2, ".", ",") . $etiqueta2,
			);
		}
		
		$sLeyenda = '<br><center><h2>
			Monto Total de Factura ( '. number_format($total_factura , 2, ".",",") .' ) <br>
			Monto Resta de Factura ( '. number_format($total_resta , 2, "." ,",") .' ) <br>';
			
		$domi = array("Cabezera" => $oCabezera2, "Cuerpo" => $oFil2, "Origen" => "json", "titulo"=>"<br>Domiciliación<br>".$mAnulado2, "leyenda" => $sLeyenda);
		
		
		$compuesto = array("compuesto" => 2, "objetos" => array( "obj1"=>$oTable , "obj2" => $domi));
		return json_encode($compuesto);
	}

	function Detalles_Facturas_Mixto($Arr){
		$query = "select * from t_lista_voucher where cid = '". $Arr['factura']."' order by fecha";
		$Consulta = $this -> db -> query($query);
		$iCantidad = $Consulta -> num_rows();
		$Conexion = $Consulta -> result();
		
		$oCabezera[1] = array("titulo" => "Voucher", "atributos" => "width:80px", "buscar" => 0);
		$oCabezera[2] = array("titulo" => "Factura", "atributos" => "width:250px", "buscar" => 0);
		$oCabezera[3] = array("titulo" => "Monto", "atributos" => "width:100px");
		$oCabezera[4] = array("titulo" => "Fecha", "atributos" => "width:100px");
		$oCabezera[5] = array("titulo" => "Estado", "atributos" => "width:100px");
		$sTitulo = '';
		$txtEstatus = '';
		$oFil = array();
		
		if ($iCantidad > 0) {
			$i = 0;
			$total = 0;
			$pagado = 0;
			foreach ($Conexion as $row) {
				++$i;
				$total += $row -> monto;
				$txtEstatus = '';
				switch ($row -> estatus) {
					case 0:
						$txtEstatus = 'Entregado al Cliente';		
						break;
					case 1:
						$pagado += $row -> monto;
						$txtEstatus = 'Pagado';		
						break;
					case 2:
						$txtEstatus = 'No Pagado';		
						break;
					default:
						
						break;
				}
				$oFil[$i] = array("1" => $row -> ndep, "2" => $row -> cid, "3" => $row -> monto, "4" => $row -> fecha , "5"=> $txtEstatus);
			}
			$sTitulo = "<br>Voucher<br>";
			$sLeyendaV = '<br><center><h2>
			Monto Total En Voucher ( '. number_format($total , 2, ".",",") .' ) <br>
			Monto Pagado En Voucher ( '. number_format($pagado , 2, "." ,",") .' ) <br>';
		}
		
		$oTable = array("Cabezera" => $oCabezera, "Cuerpo" => $oFil, "Origen" => "json","titulo"=>$sTitulo , "leyenda" => $sLeyendaV);
		//$oTable = array("Cabezera" => $Consulta -> list_fields(), "Cuerpo" => $Consulta -> result(), "Origen" => "Mysql");
		
		$sqlDetalle2 = "SELECT documento_id, contrato_id, fecha_solicitud, codigo_n, codigo_n_a, expediente_c, fecha_verificado, monto_total, 
						nomina_procedencia, estatus, forma_contrato, cobrado_en, empresa, fecha_inicio_cobro, monto_cuota, periocidad, numero_cuotas, marca_consulta
						FROM t_clientes_creditos WHERE numero_factura = '" . $Arr['factura'] . "' and marca_consulta != 6";
		$Consulta2 = $this -> db -> query($sqlDetalle2);
		$sTitulo = '';
		$sLeyenda = '';
		$oCabezera2[1] = array("titulo" => " ", "tipo" => "detallePre", "atributos" => "width:18px", "ruta" => __IMG__ . "botones/abrir.png");
		$oCabezera2[2] = array("titulo" => "Cedula",  "atributos" => "width:70px");
		$oCabezera2[3] = array("titulo" => "Contrato" ,"atributos" => "width:70px");		
		$oCabezera2[4] = array("titulo" => "Monto Cuota",  "atributos" => "width:70px");
		$oCabezera2[5] = array("titulo" => "Numero Cuota",  "atributos" => "width:70px");
		$oCabezera2[6] = array("titulo" => "Tipo <br>Credito",  "atributos" => "width:80px");
		$oCabezera2[7] = array("titulo" => "Estatus",  "atributos" => "width:80px");
		$oCabezera2[8] = array("titulo" => "Fecha Solicitud",  "atributos" => "width:80px");		
		$oCabezera2[9] = array("titulo" => "Monto Total",  "atributos" => "width:60px");
		$oCabezera2[10] = array("titulo" => "Monto Pagado",  "atributos" => "width:60px");
		$oCabezera2[11] = array("titulo" => "Monto Restante",  "atributos" => "width:60px");
		
		$sCedula = '';
		$sContrato = '';
		$i = 0;
		$Conexion2 = $Consulta2 -> result();
		$sC = '';
		$total_factura=0;
		$total_resta=0;
		$pagado = 0;
		$resta = 0;
		foreach ($Conexion2 as $row) {
			$mAnulado = '';
			$iAnulado=0;
			$etiqueta1='';
			$etiqueta2='';
			++$i;
			$montos = $this -> montos_contrato($row -> contrato_id,$row->documento_id);
			if($montos['resta'] != null){
				$resta = $montos['resta'];
			}else{
				$resta = $row -> monto_total;
			}
			if($montos['pagado'] != null){
				$pagado = $montos['pagado'];
			}else{
				$pagado = 0;
			}
			$sC = $row ->documento_id;
			if($row -> estatus != 3){
				$total_factura = $total_factura + $row -> monto_total;
				$total_resta = $total_resta + $resta;	
			}
			
			$sCedula = $row -> documento_id;
			$sContrato = $row -> contrato_id;
			
			$html = '<br><p><font color="#1c94c4">Nomina:</font> <b>'.$row -> nomina_procedencia . '</b> <br> <font color="#1c94c4">Linaje:</font><b>'. 
				$row -> cobrado_en .'</b> <br> <font color="#1c94c4">Empresa: </font><b>' . $this -> Empresa($row -> empresa) .
				'</b><br><font color="#1c94c4">F.Inicio Cobro:</font><b>' . $row -> fecha_inicio_cobro .  
				' </b><br><font color="#1c94c4">Periocidad:</font><b>' . $this -> Periocidad($row -> periocidad) .'</b>'	;
				
			$html .= '</p><br><i><b>Ubicaci&oacute;n:</b> <font color="#1c94c4">' . $row->codigo_n . '</font><br><b> Creado Por: </b>' . $row->expediente_c . 
			'.<br><b>Modificado Por</b> : ' . $row->codigo_n_a . '<br><p align=right>Fecha de la Ultima Revisi&oacute;n: ' . $row->fecha_verificado . '</p>';
			
			$queryAnulados = "SELECT * FROM _th_sistema WHERE referencia ='". $sContrato ."' AND tipo=9 LIMIT 1";
			$consultaAnulado = $this -> db -> query($queryAnulados);
			if($consultaAnulado -> num_rows() > 0){
				$iAnulado=1;
				foreach($consultaAnulado -> result() as $rowAnulado){
					$mAnulado .= "<br>Motivo Anulacion:<font color=red>" . $rowAnulado -> motivo . " </font>| <br>Usuario:<font color=red>" . $rowAnulado -> usuario . " </font>| 
					Peticion:<font color=red>" . $rowAnulado -> peticion . " </font>| fecha:<font color=red>" . $rowAnulado -> fecha . " </font>| ";
				}
			}
			
			$queryAnulados2 = "SELECT * FROM _th_sistema WHERE referencia ='". $Arr['factura'] ."' AND tipo=12 LIMIT 1";
			$consultaAnulado2 = $this -> db -> query($queryAnulados2);
			$mAnulado2 ='';
			if($consultaAnulado2 -> num_rows() > 0){
				$iAnulado=1;
				foreach($consultaAnulado2 -> result() as $rowAnulado2){
					$mAnulado2 .= "<br>Motivo Anulacion:<font color=red>" . $rowAnulado2 -> motivo . " </font>| <br>Usuario:<font color=red>" . $rowAnulado2 -> usuario . " </font>| 
					Peticion:<font color=red>" . $rowAnulado2 -> peticion . " </font>| fecha:<font color=red>" . $rowAnulado2 -> fecha . " </font>| ";
				}
			}
			
			
			if($iAnulado == 1){
				$etiqueta1="<s><font color=red>";
				$etiqueta2="</font></s>";
			}
			//$boucher = 'Ver';
			
			$oFil2[$i] = array(	
				"1" => $html.$mAnulado, //
				"2" => $etiqueta1 . $row -> documento_id . $etiqueta2, //
				"3" => $etiqueta1 . $row -> contrato_id . $etiqueta2, //
				"4" => $etiqueta1 . number_format($row -> monto_cuota,2,".",",") . $etiqueta2, //
				"5" => $etiqueta1 . $row -> numero_cuotas . $etiqueta2, //
				"6" => $etiqueta1 . $this -> Tipo_Contrato($row -> forma_contrato) . $etiqueta2, //
				"7" => $etiqueta1 . $this->Estatus_Creditos($row -> estatus) . $etiqueta2, //  
				"8" => $etiqueta1 . $row -> fecha_solicitud . $etiqueta2, // 				
				"9" => $etiqueta1.number_format($row -> monto_total,2,".",",") . $etiqueta2, // 
				"10" => $etiqueta1.number_format($pagado, 2, ".", ",") . $etiqueta2, // 
				"11" => $etiqueta1.number_format($resta, 2, ".", ",") . $etiqueta2,
			);
		}
		
		$sLeyenda = '<br><center><h2>
			Monto Total de Factura ( '. number_format($total_factura , 2, ".",",") .' ) <br>
			Monto Resta de Factura ( '. number_format($total_resta , 2, "." ,",") .' ) <br>';
			
		$domi = array("Cabezera" => $oCabezera2, "Cuerpo" => $oFil2, "Origen" => "json", "titulo"=>"<br>Domiciliación<br>".$mAnulado2, "leyenda" => $sLeyenda);
		
		
		$compuesto = array("compuesto" => 2, "objetos" => array( "obj1"=>$oTable , "obj2" => $domi));
		return json_encode($compuesto);
	}
	
	function montos_contrato($contrato, $cedula){
		$montos=array();
		$montos['pagado'] = null;
		$montos['resta'] = null;
		$strQuery = "select sum(t_lista_cobros.monto) as pagado,t_clientes_creditos.monto_total,t_clientes_creditos.monto_total - sum(t_lista_cobros.monto)  as resta
			from t_clientes_creditos
			join t_lista_cobros ON t_clientes_creditos.contrato_id = t_lista_cobros.credito_id
			where t_clientes_creditos.contrato_id = '". $contrato . "' AND t_clientes_creditos.estatus!=3 AND t_lista_cobros.documento_id = '" . $cedula ."'
			group by t_lista_cobros.credito_id" ;
		$Consulta = $this -> db -> query($strQuery);
		$Conexion = $Consulta -> result();
		foreach ($Conexion as $row) {
			$montos['pagado'] = $row -> pagado;
			$montos['resta'] = $row -> resta;
		}
		return $montos;
	}

		

	public function Estatus_Creditos($iEst) {
		$sEst = "EN PROCESO";
		switch ($iEst) {
			case 0 :
				$sEst = "<strong>PROCESANDO</strong>";
				break;
			case 1 :
				$sEst = "<strong>ACEPTADO</strong>";
				break;
			case 2 :
				$sEst = "<strong>RECHAZADO</strong>";
				break;
			case 3 :
				$sEst = "<strong>NULO</strong>";
				break;
		}
		return $sEst;
	}
	
	public function Tipo_Contrato($intCod) {
		switch ($intCod) {
			case 0 :
				return "<font color='#1c94c4'>Unico</font>";
				break;
			case 1 :
				return "<font color='#1c94c4'>Aguinaldo</font>";
				break;
			case 2 :
				return "<font color='#1c94c4'>Vacaciones</font>";
				break;
			case 3 :
				return "<font color='#1c94c4'>Extra</font>";
				break;
			case 4 :
				return "<font color='#1c94c4'>Unico Extra</font>";
				break;
			case 5 :
				return "<font color='#1c94c4'>Especial Extra</font>";
				break;
		}
	}
	
	public function empresa($empresa){
		switch ($empresa) {
			case 0 :
				return "Cooperativa";
				break;
			case 1 :
				return "Grupo";
				break;
			
		}
	}
	
	public function Periocidad($p){
		switch ($p) {
			case 0 :
				return "Semanal";
				break;
			case 1 :
				return "Catorcenal";
				break;
			case 2 :
				return "Quincenal 15-30";
				break;
			case 3 :
				return "Quincenal 10-25";
				break;
			case 4 :
				return "Mensual";
				break;
			case 5 :
				return "Trimestral";
				break;
			case 6 :
				return "Semestral";
				break;
			case 7 :
				return "Anual";
				break;
		}
	}
	
	
}
?>