<?php
/**
 *  @author Judelvis Antonio Rivas
 *  @package cooperativa.system.application.model.cliente
 *  @version 2.0.0
 */
class MBuscar extends Model {
	var $tabla = 't_clientes_creditos';
	
	protected $DB;
	
	public function __construct() {
		parent::__construct ();
		$this->DB = $this->load->database ("consulta", TRUE );
	}

	function Datos_Basicos($id = null) {
		$j = 0;
		$jsP = array ();
		$jsC = array ();
		$jsH = array ();
		$Consulta = $this->DB->query ( 'SELECT * FROM t_personas WHERE documento_id=' . $id . ' LIMIT 1' );
		$rsConsulta = $Consulta->result ();
		foreach ( $rsConsulta as $row ) {
			foreach ( $row as $NombreCampo => $ValorCampo ) {
				$jsP [$NombreCampo] = $ValorCampo;
			}
		}
		
		$ruta = BASEPATH . 'repository/expedientes/fotos/' . $id . '.jpg';
		if (! file_exists ( $ruta )) {
			$jsP ['fotoc'] = __IMG__ . "sinfoto.png";
		} else {
			$jsP ['fotoc'] = __LOCALWWW__ . "/system/repository/expedientes/fotos/" . $id . ".jpg";
		}
		
		$cNomina = $this->DB->query ( "SELECT nomina_procedencia FROM t_clientes_creditos WHERE documento_id='" . $id . "' GROUP BY nomina_procedencia" );
		$rsNom = $cNomina->result ();
		$jsP ['nomi'] = '';
		foreach ( $rsNom as $nomi ) {
			$jsP ['nomi'] .= $nomi->nomina_procedencia . " | ";
		}
		$cCertifica = $this->DB->query ( "SELECT verificado,tipo FROM t_certificados WHERE cedula='" . $id . "'" );
		$rsCerti = $cCertifica->result ();
		$jsP ['certi'] = 'PENDIENTE';
		$jsP ['tipocliente'] = 'P';
		foreach ( $rsCerti as $cer ) {
			$vcer = $cer->verificado;
			$tcli = $cer->tipo;
			if ($vcer == 1)
				$tcer = 'APROBADO';
			else
				$tcer = 'ENVIADO AL CORREO';
			$jsP ['tipocliente'] = $tcli;
			$jsP ['certi'] = $tcer;
		}
		

		return json_encode ( $jsP );

	}
	function Listar_Historial($id = '') {
		$query = "SELECT nombre AS tipo,usuario,motivo,peticion,fecha from _th_sistema 
					JOIN t_estatus_sistema on _th_sistema.tipo = t_estatus_sistema.oid
					WHERE referencia='" . $id . "'";
		$his = $this->DB->query ( $query );
		$rsHis = $his->result ();
		$cant = $his->num_rows ();
		$objeto = array ();
		if ($cant > 0) {
			$Cabezera = $his->list_fields ();
			$objeto = array (
					"Cabezera" => $Cabezera,
					"Cuerpo" => $rsHis,
					"Paginador" => 10,
					"Origen" => 'Mysql',
					"cant" => 1 
			);
		} else {
			$objeto = array (
					"cant" => 0 
			);
		}
		return json_encode ( $objeto );
	}
	function Listar_Facturas_Contado($sId) {
		$queryVC = "select * from t_venta_contado where cedula = '" . $sId . "'";
		$ConsultaVC = $this->db->query ( $queryVC );
		$iCantidadVC = $ConsultaVC->num_rows ();
		$ConexionVC = $ConsultaVC->result ();
		
		$oCabezera2 [1] = array (
				"titulo" => "Cedula",
				"atributos" => "width:80px" 
		);
		$oCabezera2 [2] = array (
				"titulo" => "Nombre",
				"atributos" => "width:250px" 
		);
		$oCabezera2 [3] = array (
				"titulo" => "Factura",
				"atributos" => "width:50px" 
		);
		$oCabezera2 [4] = array (
				"titulo" => "Monto",
				"atributos" => "width:80px" 
		);
		$oCabezera2 [5] = array (
				"titulo" => "Fecha",
				"atributos" => "width:50px" 
		);
		$oCabezera2 [6] = array (
				"titulo" => "Detalle",
				"atributos" => "width:200px" 
		);
		
		$sTituloV = '';
		$txtEstatusV = '';
		
		if ($iCantidadVC > 0) {
			$i = 0;
			foreach ( $ConexionVC as $rowV ) {
				++ $i;
				$oFil2 [$i] = array (
						"1" => $rowV->cedula,
						"2" => $rowV->nombre,
						"3" => $rowV->factura,
						"4" => $rowV->monto,
						"5" => $rowV->fecha,
						"6" => $rowV->descrip 
				);
			}
			$sTituloV = "<br><br>Facturas De Contado<br>";
			$sLeyendaV = '<br><center><h2>';
			
			$oTable = array (
					"Cabezera" => $oCabezera2,
					"Cuerpo" => $oFil2,
					"Origen" => "json",
					"titulo" => $sTituloV,
					"leyenda" => $sLeyendaV 
			);
		} else {
			
			$oTable = array (
					"msj" => "<br >No Se encontraron datos al contado asociados a la cedula ingresada...." 
			);
		}
		return json_encode ( $oTable );
	}
	function Listar_Facturas($id, $cond) {
		$Consulta = $this->sqlCuerpo ( $id, $cond );
		// return $Consulta;
		$i = 0;
		$sfactura = '';
		$sBoucher = '';
		$Conexion = $Consulta->result ();
		$cantidad = $Consulta->num_rows ();
		
		$Object = array ();
		if ($cantidad > 0) {
			foreach ( $Conexion as $row ) {
				$cedula = $row->documento_id;
				++ $i;
				$sEtiquetaA = '';
				$sEtiquetaC = '';
				if ($row->activo == 'N') {
					$sEtiquetaA = '<s><font color=red>';
					$sEtiquetaC = '</font></s>';
				}
				$sBoucher = $this->Modalidad ( $row->factura );
				$oFil [$i] = array (
						"1" => "",
						"2" => $sEtiquetaA . $row->factura . $sEtiquetaC,
						"3" => $row->total2,
						"4" => $row->resta,
						"5" => $sEtiquetaA . $row->solicitud . $sEtiquetaC,
						"6" => $sEtiquetaA . $row->banco . $sEtiquetaC,
						"7" => "BFC",
						"8" => "PRV",
						"9" => "BIC",
						"10" => $sBoucher,
						"11" => "Ver Factura",
						"12" => $cedula 
				);
			}
			$cab = $this->cab ();
			$sLeyenda = "<br><center><a href='" . base_url () . "index.php/cooperativa/PNEstado_cuenta/0/" . $id . "/" . $id . "' target='_blank'><h2><font color='#1c94c4'>[ Control de Pagos ]</font></h2></a></center>";
			$Object = array (
					"Cabezera" => $cab,
					"Cuerpo" => $oFil,
					"Origen" => "json",
					"Paginador" => 0,
					"cant" => 1,
					"leyenda" => $sLeyenda 
			);
		} else {
			$Object = array (
					"cant" => 0 
			);
		}
		return json_encode ( $Object );
	}
	function Cab() {
		$oCabezera [1] = array (
				"titulo" => " ",
				"tipo" => "detallePost",
				"atributos" => "width:40px",
				"funcion" => "MDRProcesar",
				"parametro" => "2,10,12",
				"atributos" => "width:12px" 
		);
		$oCabezera [2] = array (
				"titulo" => "Factura",
				"atributos" => "width:100px;text-align:center;" 
		);
		$oCabezera [3] = array (
				"titulo" => "Total",
				"total" => 1,
				"atributos" => "width:100px;text-align:right;" 
		);
		$oCabezera [4] = array (
				"titulo" => "Resta",
				"total" => 1,
				"atributos" => "width:100px;text-align:right;" 
		);
		$oCabezera [5] = array (
				"titulo" => "Solicitud",
				"atributos" => "width:100px;text-align:center;" 
		);
		$oCabezera [6] = array (
				"titulo" => "Linaje" 
		);
		$oCabezera [7] = array (
				"titulo" => "Comp. BFC",
				"tipo" => "enlace",
				"funcion" => "Imprimir_Compromiso",
				"parametro" => "2,12,7",
				"metodo" => 2,
				"atributos" => "width:50px;text-align:center;" 
		);
		$oCabezera [8] = array (
				"titulo" => "Comp. PRO",
				"tipo" => "enlace",
				"funcion" => "Imprimir_Compromiso",
				"parametro" => "2,12,8",
				"metodo" => 2,
				"atributos" => "width:50px;text-align:center;" 
		);
		$oCabezera [9] = array (
				"titulo" => "Comp. BIC",
				"tipo" => "enlace",
				"funcion" => "Imprimir_Compromiso",
				"parametro" => "2,12,9",
				"metodo" => 2,
				"atributos" => "width:50px;text-align:center;" 
		);
		$oCabezera [10] = array (
				"titulo" => "Forma De Pago",
				"tipo" => "enlace",
				"funcion" => "Imprimir_Compromiso_Factura",
				"parametro" => "2",
				"metodo" => 2,
				"atributos" => "width:50px" 
		);
		$oCabezera [11] = array (
				"titulo" => "#",
				"tipo" => "enlace",
				"metodo" => 2,
				"funcion" => "Imprimir_Facturas",
				"parametro" => "12,2",
				"ruta" => __IMG__ . "botones/print.png",
				"atributos" => "width:12px",
				"target" => "_blank" 
		);
		$oCabezera [12] = array (
				"titulo" => "Cedula",
				"oculto" => 1 
		);
		return $oCabezera;
	}
	function sqlCuerpo($sId, $cond) {
		$query = "
		SELECT * FROM (
			SELECT tbl.numero_factura AS factura, fecha_solicitud AS solicitud, cobrado_en AS banco, 
			SUM(t_clientes_creditos.monto_total) AS total, 
			SUM(t_clientes_creditos.cantidad) as total2,pagado,pg_voucher,
			SUM(t_clientes_creditos.cantidad) - pagado - pg_voucher AS resta, 
			IF(SUM(t_clientes_creditos.cantidad)=0,'N','A') AS activo,
			t_clientes_creditos.marca_consulta,documento_id
			FROM t_clientes_creditos
			JOIN(SELECT t_clientes_creditos.numero_factura, IFNULL( SUM( monto ) , 0 ) AS pagado,
				IFNULL(tot_voucher,0) as pg_voucher
				FROM t_clientes_creditos
				LEFT JOIN t_lista_cobros ON t_clientes_creditos.contrato_id = t_lista_cobros.credito_id AND t_clientes_creditos.documento_id = t_lista_cobros.documento_id 
				LEFT JOIN (SELECT cid,sum(monto) AS tot_voucher 
							FROM t_lista_voucher
							JOIN (SELECT * FROM t_clientes_creditos 
									where marca_consulta=6 and documento_id='" . $sId . "' group by numero_factura)
									as tc on tc.numero_factura = t_lista_voucher.cid
							WHERE (t_lista_voucher.estatus = 3 OR t_lista_voucher.estatus = 1 OR t_lista_voucher.estatus = 6)
							GROUP BY cid
						)AS auxt on auxt.cid = t_clientes_creditos.numero_factura
			WHERE t_clientes_creditos.documento_id=" . $sId . " 
			GROUP BY numero_factura)AS tbl ON tbl.numero_factura=t_clientes_creditos.numero_factura 
			WHERE t_clientes_creditos.documento_id=" . $sId . " GROUP BY t_clientes_creditos.numero_factura) 
		AS A WHERE A.resta " . $cond;
		$resultado = $this->DB->query ( $query );
		
		return $resultado;
	}
	function Modalidad($fact) {
		//$sqlA = "SELECT marca_consulta,numero_control FROM t_clientes_creditos WHERE numero_factura='" . $fact . "'";
		$sqlA = "SELECT marca_consulta,numero_control,ifnull(modo,0)as modo FROM t_clientes_creditos 
					left join (SELECT * from t_lista_voucher where cid='" . $fact . "' group by cid) as a on a.cid = t_clientes_creditos.numero_factura
					WHERE numero_factura='" . $fact . "'";
		$Consulta2 = $this->db->query ( $sqlA );
		$Conexion2 = $Consulta2->result ();
		$forma_contrato = array ();
		$sBoucher = '';
		foreach ( $Conexion2 as $row2 ) {
			if ($row2->marca_consulta == 6){
				switch($row2->modo){
					case 0: $forma_contrato [] = 'Voucher';break;
					case 1: $forma_contrato [] = 'Transferencia';break;
					case 2: $forma_contrato [] = 'Cotero';break;
				}
			}else{
				$forma_contrato [] = 'Domiciliacion';
			}
		}
		$arreglo = array_unique ( $forma_contrato );
		$cantidad = count ( $arreglo );
		if ($cantidad == 1){
			$sBoucher = $arreglo [0];
		}else{
			$sBoucher = 'Mixto-V';
			if(in_array('Transferencia', $arreglo)){
				$sBoucher = 'Mixto-T';
			}
			if(in_array('Cotero', $arreglo)){
				$sBoucher = 'Mixto-CT';
			}
		}
		return $sBoucher;
	}
	function Detalles_Facturas_Contratos($Arr) {
		// variable que me determin CUANTOS GRID SE DEBEN MOSTRAR
		$com = 1;
		$final = array ();
		$voucher1 = array ();
		$voucher2 = array ();
		$variable2 = array ();
		
		/*
		 * Grid Contratos
		 */
		$contratos = $this->Grid_Domi ( $Arr ['factura'], $Arr ['cedula'], 1, "", "DOMICILIACION" );
		// return $contratos."hola";
		$variable2 [] = 'contratos';
		/*
		 * Grid Voucher no aplica en caso de que existen
		 */
		$voucher1 = $this->Grid_Voucher ( $Arr ['factura'], 0, "HISTORIAL DE VOUCHER Y/O TRANSFERENCIAS TRASLADADOS A DOMICILIACION" );
		if ($voucher1 != 0) {
			$com ++;
			$variable2 [] = 'voucher1';
		}
		/*
		 * Grid Voucher pronto pago no aplica en caso de que existen
		 */
		$voucher2 = $this->Grid_Voucher ( $Arr ['factura'], 1, '' );
		if ($voucher2 != 0) {
			$com ++;
			$variable2 [] = 'voucher2';
		}
		/*
		 * Valida cuantos grid debe generar
		 */
		if ($com == 1) {
			$final = $contratos;
		} else {
			$variable = array (
					"obj1",
					"obj2",
					"obj3" 
			);
			for($j = 0; $j < $com; $j ++) {
				$ele = $variable2 [$j];
				$pos = $variable [$j];
				$elementos [$pos] = $$ele;
			}
			$final = array (
					"compuesto" => $com,
					"objetos" => $elementos 
			);
		}
		
		/*
		 * print("<pre>");
		 * print_r($final);
		 * return "conta:".$com;
		 */
		return json_encode ( $final );
	}
	function Detalles_Facturas_Voucher($Arr) {
		$com = 1;
		$final = array ();
		$voucher1 = array ();
		$voucher2 = array ();
		$variable2 = array ();
		/*
		 * Grid Contratos
		 */
		$contratos = $this->Grid_Domi ( $Arr ['factura'], $Arr ['cedula'], 0, "", $Arr['titulo'] );
		$variable2 [] = 'contratos';
		/*
		 * Grid Voucher no aplica en caso de que existen
		 */
		$voucher1 = $this->Grid_Voucher ( $Arr ['factura'], 0, "HISTORIAL DE ".$Arr['titulo'] );
		if ($voucher1 != 0) {
			$com ++;
			$variable2 [] = 'voucher1';
		}
		/*
		 * Valida cuantos grid debe generar
		 */
		if ($com == 1) {
			$final = $contratos;
		} else {
			$variable = array (
					"obj1",
					"obj2",
					"obj3" 
			);
			for($j = 0; $j < $com; $j ++) {
				$ele = $variable2 [$j];
				$pos = $variable [$j];
				$elementos [$pos] = $$ele;
			}
			$final = array (
					"compuesto" => $com,
					"objetos" => $elementos 
			);
		}
		
		/*
		 * print("<pre>");
		 * print_r($final);
		 * return "conta:".$com;
		 */
		return json_encode ( $final );
	}
	function Detalles_Facturas_Mixto($Arr) {
		// variable que me determin CUANTOS GRID SE DEBEN MOSTRAR
		$com = 2;
		$final = array ();
		$voucher1 = array ();
		$voucher2 = array ();
		$variable2 = array ();
		/*
		 * Grid Contratos
		 */
		$contratos = $this->Grid_Domi ( $Arr ['factura'], $Arr ['cedula'], 1, 5, "DOMICILIACION" );
		$contratos2 = $this->Grid_Domi ( $Arr ['factura'], $Arr ['cedula'], 0, 6, $Arr['titulo'] );
		$variable2 [] = 'contratos';
		$variable2 [] = 'contratos2';
		/*
		 * Grid Voucher no aplica en caso de que existen
		 */
		$voucher1 = $this->Grid_Voucher ( $Arr ['factura'], 0, "HISTORIAL DE ".$Arr['titulo'] );
		if ($voucher1 != 0) {
			$com ++;
			$variable2 [] = 'voucher1';
		}
		/*
		 * Grid Voucher pronto pago no aplica en caso de que existen
		 */
		$voucher2 = $this->Grid_Voucher ( $Arr ['factura'], 1, '' );
		if ($voucher2 != 0) {
			$com ++;
			$variable2 [] = 'voucher2';
		}
		/*
		 * Valida cuantos grid debe generar
		 */
		if ($com == 1) {
			$final = $contratos;
		} else {
			$variable = array (
					"obj1",
					"obj2",
					"obj3",
					"obj4" 
			);
			for($j = 0; $j < $com; $j ++) {
				$ele = $variable2 [$j];
				$pos = $variable [$j];
				$elementos [$pos] = $$ele;
			}
			$final = array (
					"compuesto" => $com,
					"objetos" => $elementos 
			);
		}
		
		/*
		 * print("<pre>");
		 * print_r($final);
		 * return "conta:".$com;
		 */
		return json_encode ( $final );
	}
	function montos_contrato($contrato, $cedula) {
		$montos = array ();
		$montos ['pagado'] = null;
		$montos ['resta'] = null;
		$strQuery = "select sum(t_lista_cobros.monto) as pagado,t_clientes_creditos.monto_total,t_clientes_creditos.monto_total - sum(t_lista_cobros.monto)  as resta
			from t_clientes_creditos
			join t_lista_cobros ON t_clientes_creditos.contrato_id = t_lista_cobros.credito_id
			where t_clientes_creditos.contrato_id = '" . $contrato . "' AND t_clientes_creditos.estatus!=3 AND t_lista_cobros.documento_id = '" . $cedula . "'
			group by t_lista_cobros.credito_id";
		$Consulta = $this->db->query ( $strQuery );
		$Conexion = $Consulta->result ();
		foreach ( $Conexion as $row ) {
			$montos ['pagado'] = $row->pagado;
			$montos ['resta'] = $row->resta;
		}
		return $montos;
	}
	
	/*
	 * Funcion para crear los grid de los contratos por domiciliacion dependiendo del pronto
	 */
	function Grid_Domi($fac, $ced, $carga, $marca = '', $tit) {
		$marca_consulta = "";
		if ($marca != '')
			$marca_consulta = " AND marca_consulta = " . $marca;
		$sqlDetalle = "SELECT documento_id, contrato_id, fecha_solicitud, codigo_n, codigo_n_a, expediente_c, fecha_verificado, monto_total, numero_control,
						nomina_procedencia, estatus, forma_contrato, cobrado_en, empresa, fecha_inicio_cobro, monto_cuota, periocidad, numero_cuotas, marca_consulta
						FROM t_clientes_creditos WHERE numero_factura = '" . $fac . "' AND documento_id ='" . $ced . "' " . $marca_consulta;
		$Consulta = $this->db->query ( $sqlDetalle );
		
		$sTitulo = '';
		$sLeyenda = '';
		// $oCabezera[1] = array("titulo" => " ", "tipo" => "detallePre", "atributos" => "width:18px", "ruta" => __IMG__ . "botones/abrir.png");
		$oCabezera [1] = array (
				"titulo" => " ",
				"tipo" => "detallePost",
				"atributos" => "width:18px",
				"funcion" => "DescripcionContrato",
				"parametro" => "4",
				'tipo_detalle' => 'html' 
		);
		$oCabezera [2] = array (
				"titulo" => " ",
				"tipo" => "enlace",
				"funcion" => "Consultar",
				"parametro" => "3,4",
				"metodo" => 1,
                "mantiene"=>1,
				"ruta" => __IMG__ . "botones/add.png",
				"atributos" => "width:10px" 
		);
		// $oCabezera[2] = array("titulo" => " ", "tipo" => "enlace", "funcion" => "DetalleCobroContrato", "parametro" => "3,4", "metodo" => 2, "target" => '_blank', "ruta" => __IMG__ . "botones/add.png", "atributos" => "width:10px");
		if ($carga == 0)
			$oCabezera [2] = array (
					"titulo" => " ",
					"oculto" => 1 
			);
		$oCabezera [3] = array (
				"titulo" => "Cedula",
				"atributos" => "width:70px",
				"oculto" => 1 
		);
		$oCabezera [4] = array (
				"titulo" => "Contrato",
				"tipo" => "enlace",
				"funcion" => "Imprimir_Contrato_Nuevo",
				"parametro" => "3,4",
				"metodo" => 2,
				"target" => '_blank',
				"atributos" => "width:40px" 
		);
		$oCabezera [5] = array (
				"titulo" => "Monto Cuota",
				"atributos" => "width:70px" 
		);
		$oCabezera [6] = array (
				"titulo" => "Cuotas",
				"atributos" => "width:20px" 
		);
		$oCabezera [7] = array (
				"titulo" => "Tipo <br>Credito",
				"atributos" => "width:80px" 
		);
		$oCabezera [8] = array (
				"titulo" => "Estatus",
				"atributos" => "width:80px" 
		);
		$oCabezera [9] = array (
				"titulo" => "Fecha Inicio D.",
				"atributos" => "width:80px" 
		);
		$oCabezera [10] = array (
				"titulo" => "Fecha Fin D.",
				"atributos" => "width:80px" 
		);
		$oCabezera [11] = array (
				"titulo" => "Monto Total",
				"atributos" => "width:60px" 
		);
		$oCabezera [12] = array (
				"titulo" => "Monto Pagado",
				"atributos" => "width:60px" 
		);
		$oCabezera [13] = array (
				"titulo" => "Monto Restante",
				"atributos" => "width:60px" 
		);
		$oCabezera [14] = array (
				"titulo" => "",
				"atributos" => "width:5px",
				"oculto" => 1 
		);
		
		$sCedula = '';
		$sContrato = '';
		$i = 0;
		$Conexion = $Consulta->result ();
		$sC = '';
		$total_factura = 0;
		$total_resta = 0;
		$pagado = 0;
		$resta = 0;
		foreach ( $Conexion as $row ) {
			$mAnulado = '';
			$iAnulado = 0;
			$etiqueta1 = '';
			$etiqueta2 = '';
			++ $i;
			$montos = $this->montos_contrato ( $row->contrato_id, $row->documento_id );
			if ($montos ['resta'] != null) {
				$resta = $montos ['resta'];
			} else {
				$resta = $row->monto_total;
			}
			if ($montos ['pagado'] != null) {
				$pagado = $montos ['pagado'];
			} else {
				$pagado = 0;
			}
			$sC = $row->documento_id;
			if ($row->estatus != 3) {
				$total_factura = $total_factura + $row->monto_total;
				$total_resta = $total_resta + $resta;
			}
			
			$sCedula = $row->documento_id;
			$sContrato = $row->contrato_id;
			
			$queryAnulados = "SELECT * FROM _th_sistema WHERE referencia ='" . $sContrato . "' AND tipo=9 LIMIT 1";
			$consultaAnulado = $this->db->query ( $queryAnulados );
			
			if ($consultaAnulado->num_rows () > 0)
				$iAnulado = 1;
			
			$queryAnulados2 = "SELECT * FROM _th_sistema WHERE referencia ='" . $fac . "' AND tipo=12 LIMIT 1";
			$consultaAnulado2 = $this->db->query ( $queryAnulados2 );
			$mAnulado2 = '';
			if ($consultaAnulado2->num_rows () > 0)
				$iAnulado = 1;
			
			if ($iAnulado == 1) {
				$etiqueta1 = "<s><font color=red>";
				$etiqueta2 = "</font></s>";
			}
			
			$arr_ult = $this->GPlanCobro ( $row->fecha_inicio_cobro, $row->numero_cuotas, $row->periocidad, 1 );
			$fin_descuento = end ( $arr_ult );
			
			$oFil [$i] = array (
					"1" => '', //
					"2" => "Ct.", //
					"3" => $etiqueta1 . $row->documento_id . $etiqueta2, //
					"4" => $etiqueta1 . $row->contrato_id . $etiqueta2, //
					"5" => $etiqueta1 . number_format ( $row->monto_total /  $row->numero_cuotas, 2, ".", "," ) . $etiqueta2, //
					"6" => $etiqueta1 . $row->numero_cuotas . $etiqueta2, //
					"7" => $etiqueta1 . $this->Tipo_Contrato ( $row->forma_contrato ) . $etiqueta2, //
					"8" => $etiqueta1 . $this->Estatus_Creditos ( $row->estatus ) . $etiqueta2, //
					"9" => $etiqueta1 . date ( 'd-m-Y', strtotime ( $row->fecha_inicio_cobro ) ) . $etiqueta2, //
					"10" => $etiqueta1 . $fin_descuento ['fecha'] . $etiqueta2, //
					"11" => $etiqueta1 . number_format ( $row->monto_total, 2, ".", "," ) . $etiqueta2, //
					"12" => $etiqueta1 . number_format ( $pagado, 2, ".", "," ) . $etiqueta2, //
					"13" => $etiqueta1 . number_format ( $resta, 2, ".", "," ) . $etiqueta2,
					"14" => $fac 
			) //
;
		}
		$sLeyenda .= "<br><center><a href='" . base_url () . "index.php/cooperativa/PNEstado_cuenta/1/" . $fac . "/" . $ced . "' target='_blank'>
				<h2><font color='#1c94c4'>[ Estado De Cuenta Factura]</font></h2></a></center>";
		
		$contratos = array (
				"Cabezera" => $oCabezera,
				"Cuerpo" => $oFil,
				"Origen" => "json",
				"titulo" => "<br>",
				"titulo" => $tit,
				"leyenda" => $sLeyenda 
		);
		return $contratos;
	}
	function DescripcionContrato($cont) {
		$html = '';
		$query = "SELECT documento_id, contrato_id, fecha_solicitud, codigo_n, codigo_n_a, expediente_c, fecha_verificado, monto_total, numero_control,
					nomina_procedencia, estatus, forma_contrato, cobrado_en, empresa, fecha_inicio_cobro, monto_cuota, periocidad, numero_cuotas, marca_consulta,numero_factura
					FROM t_clientes_creditos WHERE contrato_id = '" . $cont . "'";
		
		$contrato = $this->db->query ( $query );
		$cant = $contrato->num_rows ();
		// return $query;
		if ($cant > 0) {
			$rs = $contrato->result ();
			foreach ( $rs as $row ) {
				$html = '<br><p><font color="#1c94c4">Nomina:</font> <b>' . $row->nomina_procedencia . '</b> 
					<br> <font color="#1c94c4">Linaje:</font><b>' . $row->cobrado_en . '</b>
					<br><p><a href=\'' . __LOCALWWW__ . '/index.php/cooperativa/ipdf/' . $row->cobrado_en . '/' . $row->contrato_id . '\' border=0 target=\'top\'>Imprimir Formato</a></p>
					<br> <font color="#1c94c4">Empresa: </font><b>' . $this->Empresa ( $row->empresa ) . '</b>
					<br><font color="#1c94c4">F.Inicio Cobro:</font><b>' . $row->fecha_inicio_cobro . ' </b>
					<br><font color="#1c94c4">Periocidad:</font><b>' . $this->Periocidad ( $row->periocidad ) . '</b>';
				
				$html .= '</p><br><i><b>Ubicaci&oacute;n:</b> <font color="#1c94c4">' . $row->codigo_n . '</font>
							<br><b> Creado Por: </b>' . $row->expediente_c . '.
							<br><b>Modificado Por</b> : ' . $row->codigo_n_a . '<br><p align=right>Fecha de la Ultima Revisi&oacute;n: ' . $row->fecha_verificado . '</p>';
				
				$queryAnulados = "SELECT * FROM _th_sistema WHERE referencia ='" . $cont . "' AND tipo=9 LIMIT 1";
				$consultaAnulado = $this->db->query ( $queryAnulados );
				
				if ($consultaAnulado->num_rows () > 0) {
					foreach ( $consultaAnulado->result () as $rowAnulado ) {
						$html .= "<br>Motivo Anulacion:<font color=red>" . $rowAnulado->motivo . " </font>| <br>Usuario:<font color=red>" . $rowAnulado->usuario . " </font>| 
						Peticion:<font color=red>" . $rowAnulado->peticion . " </font>| fecha:<font color=red>" . $rowAnulado->fecha . " </font>| ";
					}
				}
				
				$queryAnulados2 = "SELECT * FROM _th_sistema WHERE referencia ='" . $row->numero_factura . "' AND tipo=12 LIMIT 1";
				$consultaAnulado2 = $this->db->query ( $queryAnulados2 );
				$mAnulado2 = '';
				if ($consultaAnulado2->num_rows () > 0) {
					$iAnulado = 1;
					foreach ( $consultaAnulado2->result () as $rowAnulado2 ) {
						$html .= "<br>Motivo Anulacion:<font color=red>" . $rowAnulado2->motivo . " </font>| <br>Usuario:<font color=red>" . $rowAnulado2->usuario . " </font>| 
						Peticion:<font color=red>" . $rowAnulado2->peticion . " </font>| fecha:<font color=red>" . $rowAnulado2->fecha . " </font>| ";
					}
				}
			}
		}
		return $html;
	}
	
	/*
	 * Funcion para crear los grid de los voucher dependiendo del pronto
	 */
	function Grid_Voucher($fac, $pr, $tit) {
		$queryV = "select * from t_lista_voucher where cid = '" . $fac . "' and pronto=" . $pr . " order by fecha";
		$ConsultaV = $this->db->query ( $queryV );
		$iCantidadV = $ConsultaV->num_rows ();
		$ConexionV = $ConsultaV->result ();
		$oCabezera2 [1] = array (
				"titulo" => "Cuota",
				"atributos" => "" 
		);
		$oCabezera2 [2] = array (
				"titulo" => "Voucher",
				"atributos" => "" 
		);
		$oCabezera2 [3] = array (
				"titulo" => "Factura",
				"atributos" => "" 
		);
		$oCabezera2 [4] = array (
				"titulo" => "Monto",
				"atributos" => "" 
		);
		$oCabezera2 [5] = array (
				"titulo" => "Fecha",
				"atributos" => "" 
		);
		$oCabezera2 [6] = array (
				"titulo" => "Estado",
				"tipo" => "enlace",
				"atributos" => "",
				"funcion" => "Pago_Voucher",
				"metodo" => 1,
				"parametro" => "2,3",
                "mantiene" => 1
 		);
        $oCabezera2 [7] = array (
            "titulo" => "Exonerar",
            "tipo" => "bimagen",
            "atributos" => "width=15px;",
            "ruta" => __IMG__."botones/aceptar.png",
            "funcion" => "Exonerar_Voucher",
            "parametro" => "2,3"
        );
		$sTituloV = '';
		$txtEstatusV = '';
		$voucher1 = null;
		if ($iCantidadV > 0) {
			$i = 0;
			$totalV = 0;
			$pagadoV = 0;
			$sTituloV = "<br>Voucher<br>";
			foreach ( $ConexionV as $rowV ) {
				++ $i;
				$totalV += $rowV->monto;
				$txtEstatusV = $this->Estatus_Voucher ( $rowV->estatus, $pagadoV, $rowV->monto );
                $usu = $this->session->userdata ( 'oidu' );
                if($usu ==0 || $usu==27 || $usu == 28 ){
                    $oFil2 [$i] = array (
                        "1" => $i . " DE " . $iCantidadV,
                        "2" => $rowV->ndep,
                        "3" => $rowV->cid,
                        "4" => $rowV->monto,
                        "5" => $rowV->fecha,
                        "6" => $txtEstatusV,
                        "7" => ""
                    );
                }else{
                    $oFil2 [$i] = array (
                        "1" => $i . " DE " . $iCantidadV,
                        "2" => $rowV->ndep,
                        "3" => $rowV->cid,
                        "4" => $rowV->monto,
                        "5" => $rowV->fecha,
                        "6" => $txtEstatusV
                    );
                }


			}
			
			$sTituloV = "<br>" . $tit . "<br>";
			if ($pr == 1)
				$sTituloV = "<br>Voucher Pronto Pago<br>";
			
			$voucher1 = array (
					"Cabezera" => $oCabezera2,
					"Cuerpo" => $oFil2,
					"Origen" => "json",
					"titulo" => $sTituloV 
			);
		} else {
			$voucher1 = 0;
		}
		return $voucher1;
	}
	function Estados_Cuenta($tipo, $id) {
		switch ($tipo) {
			case 0 :
				$contratos = "SELECT contrato_id,numero_factura,marca_consulta,cantidad FROM t_clientes_creditos	WHERE documento_id='" . $id . "' AND cantidad!=0";
				$obj = $this->ECuenta ( $contratos );
				break;
			case 1 :
				$query = "SELECT contrato_id,marca_consulta,numero_factura,cantidad FROM t_clientes_creditos WHERE numero_factura='" . $id . "' AND cantidad!=0";
				$obj = $this->ECuenta ( $query );
				break;
			case 2 :
				$obj = $this->EC_Contrato ( $id );
				break;
			default :
				break;
		}
		return json_encode ( $obj );
	}
	function EC_Contrato($id) {
		$contrato = $this->db->query ( "SELECT * FROM t_clientes_creditos WHERE contrato_id='" . $id . "' AND cantidad!=0" );
		$rsCon = $contrato->result ();
		
		$i = 0;
		$html = "nada";
		$fecha_inici = 0;
		foreach ( $rsCon as $rs ) {
			$html = '<center><table><tr><th>FACTURA</th><td colspan=5>' . $rs->numero_factura . '</td></tr>
			<tr><th>CONTRATO</th><td>' . $rs->contrato_id . '</td><th>LINAJE</th><td>' . $rs->cobrado_en . '</td><th>MONTO</th><td>' . number_format ( $rs->cantidad, 2 ) . '</td></tr>
			<tr><th>FECHA INICIO COBRO</th><td>' . $rs->fecha_inicio_cobro . '</td><th>PERIOCIDAD</th><td>' . $this->Periocidad ( $rs->periocidad ) . '</td><th>NOMINA</th><td>' . $rs->nomina_procedencia . '</td></tr>
			</table></center>';
			$fecha_inici = $rs->fecha_inicio_cobro;
		}
		if ($fecha_inici >= '2014-08-01') {
			$aux = $this->DTControlMensualEstCuenta ( $id );
			$aux ['titulo'] = $html;
			// $aux['leyenda'] = '<center>***************************</center><br><br>';
			$obj = $aux;
		} else {
			$aux = $this->GListaPagos ( $id );
			$aux ['php'] ['titulo'] = $html;
			// $aux['php']['leyenda'] = '<center>***************************</center><br><br>';
			$obj = $aux ['php'];
		}
		return $obj;
	}
	function ECuenta($query) {
		$contratos = $this->db->query ( $query );
		$rsCon = $contratos->result ();
		$elementos = array ();
		$i = 0;
		foreach ( $rsCon as $rs ) {
			$i ++;
			if ($rs->marca_consulta != 6) {
				$aux = $this->EC_Contrato ( $rs->contrato_id );
				$elementos [] = array (
						"grid" => $aux 
				);
			} else {
				$html = '<center><table><tr><th>VOUCHER FACTURA</th><td colspan=5>' . $rs->numero_factura . '</td><th>MONTO</th><td>' . number_format ( $rs->cantidad, 2 ) . '</td></tr></table></center>';
				$aux = $this->Grid_Voucher ( $rs->numero_factura, 0, $html );
				$elementos [] = array (
						"grid" => $aux 
				);
			}
		}
		$obj = array (
				"compuesto" => $i,
				"objetos" => $elementos 
		);
		return $obj;
	}
	function Envia_Estado_Cuenta($correo = '', $archivo_adjunto = '', $ced = '', $nombre = '') {
		error_reporting ( E_STRICT );
		$cuerpo = '<table style="font-family: Trebuchet MS; font-size: 13px;text-align: justify;" width="0"><tr><td rowspan="2"  width=180><img src="' . __IMG__ . 'logoN.jpg" width=200></td>
	            </tr><tr><td colspan="3" >Apreciado/a:  <br>' . $nombre . '.<br> ' . $ced . '</td></tr>
	            <tr><td colspan="4">Recibe un caluroso saludo de parte de Grupo Electrón, mediante la presente te notificamos que ha sido enviado el 
	            el estado de cuenta de sus contratos en nuestro sistema.<br><br>
	            </td>
	          </tr><tr><td colspan="4">Si tienes alguna pregunta o si necesitas alguna asistencia con respecto a esta comunicación, tienes a tu disposición a nuestro equipo de atenci&oacute;n al cliente a través del número </td></tr>
	          <tr><td colspan="4"><hr></hr><small>Muchas gracias por ser parte de la comunidad Electr&oacute;n 465.
Por favor, no responda este e-mail ya que ha sido enviado automáticamente. Usted ha recibido esta comunicación por tener una relación con Electrón 465. Esta comunicación forma parte básica de nuestro programa de atención al cliente. Si no desea seguir recibiendo este tipo de comunicaciones, le rogamos cancele por escrito su afiliación al mismo. 
Electrón 465 se compromete firmemente a respetar su privacidad. No compartimos su información con ningún tercero sin su consentimiento.</small>
	          </td></tr></table>';
		require_once ('system/application/libraries/PHPMail/class.phpmailer.php');
		$mail = new PHPMailer ();
		$body = '<body style="margin: 10px;">Prueba de Envio Estado de Cuenta<br /></body>'; // file_get_contents('');
		$mail->IsSMTP (); // telling the class to use SMTP
		$mail->SMTPDebug = 1;
		$mail->Host = "smtp.gmail.com";
		$mail->SMTPSecure = "tls";
		$mail->SMTPAuth = true; // enable SMTP authentication
		$mail->SMTPKeepAlive = true; // SMTP connection will not close after each email sent
		                             // $mail->Host = "gmail.com"; // sets the SMTP server
		$mail->Port = 587;
		$mail->Username = "soporteelectron465@gmail.com"; // SMTP account username
		$mail->Password = "soporte8759"; // SMTP account password
		$mail->SetFrom ( 'soporteelectron465@gmail.com', 'Departamento de Ventas' );
		$mail->AddReplyTo ( 'soporteelectron465@gmail.com', 'Despartamento de Ventas' );
		$mail->Subject = "Grupo Electron (Estado de Cuenta)";
		$mail->AltBody = "Texto Alternativo"; // optional, comment out and test
		$mail->MsgHTML ( $cuerpo );
		$address = $correo;
		$mail->AddAddress ( $address, $nombre );
		$ejecutar = exec ( "ls /system/repository/estados_cuenta/" );
		$mail->AddAttachment ( $archivo_adjunto );
		
		if (! $mail->Send ()) {
			return "Error al enviar: " . $mail->ErrorInfo;
		} else {
			return "Mensaje enviado a:  " . $address . "!";
		}
	}
	public function Estado_Cuenta_Pdf($arr) {
		$this->load->library ( 'pdf' );
		$pdf = new $this->pdf ();
		$font = 'times';
		$tagvs = array (
				'p' => array (
						0 => array (
								'h' => 0,
								'n' => 0 
						),
						1 => array (
								'h' => 0,
								'n' => 0 
						) 
				) 
		);
		$pdf->setHtmlVSpace ( $tagvs );
		$pdf->setCellHeightRatio ( 1.25 );
		$pdf->SetHeaderMargin ( 0 );
		$pdf->SetFooterMargin ( 0 );
		$pdf->SetAutoPageBreak ( TRUE, 0 );
		$pdf->SetCellPadding ( 0 );
		$pdf->setPrintHeader ( FALSE );
		$pdf->setPrintFooter ( FALSE );
		$pdf->SetFont ( 'helvetica', '', 7 );
		$pdf->setImageScale ( 0.47 );
		$pdf->SetMargins ( 5, 5, 5 );
		$pdf->AddPage ( '', 'LETTER' );
		$img_file = K_PATH_IMAGES . 'logoN.jpg';
		$pdf->Image ( $img_file, 70, 10, 76, 49.4, '', '', 'N', false, 300, '', false, false, 0 );
		$pdf->writeHTML ( htmlspecialchars_decode ( $arr ['estado'] ), true, false, true, false, 'C' );
		$pdf->setHtmlVSpace ( $tagvs );
		$archivo_adjunto = strtolower ( "system/repository/estados_cuenta/" . $arr ['correo'] . ".pdf" );
		$pdf->Output ( $archivo_adjunto, 'F' );
		$env = $this->Envia_Estado_Cuenta ( $arr ['correo'], $archivo_adjunto, $arr ['ced'], $arr ['nombre'] );
		return $env;
		// return '<a href="'.__LOCALWWW__.'/'.$archivo_adjunto.'" target="blank" ><img src="'.__IMG__.'pdf.png" style="width:60px"><br>+ Pagare</a>';
	}
	
	/**
	 * Generar Plan de Cobro, implementar cronograma de pagos
	 *
	 * @param
	 *        	Fecha de Inicio de Descuento
	 * @param
	 *        	Cantidad Cuotas Programandas
	 * @param
	 *        	Periodo Control
	 *        	
	 * @return array() Plan
	 */
	public function GPlanCobro($sFec, $iCuo, $iPer, $tipo = null) {
		$arr = array ();
		$fechap = explode ( '-', $sFec );
		$dia_inicio = $fechap [2];
		$mes_inicio = $fechap [1];
		$ano_inicio = $fechap [0];
		for($i = 1; $i <= $iCuo; $i ++) {
			$dia_fin = 0;
			$mes_fin = 0;
			$ano_fin = 0;
			$base_mes = 0;
			$tiempo = 0;
			switch ($iPer) {
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
			$tiempo_picado = explode ( '.', $tiempo );
			$ano_t = ( int ) ($tiempo_picado [0] / 12);
			$ano_t += $ano_inicio;
			$mes_t = $tiempo_picado [0] % 12;
			$dia_t = $dia_inicio;
			if (isset ( $tiempo_picado [1] )) {
				switch ($tiempo_picado [1]) {
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
				$diferencia = $dia_t - 30;
				$dia_t = $diferencia;
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
			$fechaM = $dia_t . "-" . $m . $mes_t . "-" . $ano_t;
			$cuotaM = $ano_t . '-' . $m . $mes_t;
			if ($tipo != null)
				$arr [$cuotaM] = array (
						"cuota" => $i,
						"fecha" => $fechaM 
				);
			else
				$arr [] = array (
						"cuota" => $i,
						"fecha" => $fechaM 
				);
		}
		return $arr;
	}
	
	/**
	 * Control de Pagos busca un contrato returna fecha_inicio_cobro,
	 * numero_cuotas, periocidad invoca al metodos privados del GPlanCobro
	 *
	 * @param
	 *        	Contrato
	 *        	
	 * @return array()
	 */
	private function CPagos($sCon = '') {
		$arr = array ();
		$arr_aux = array ();
		
		$sQuery = 'SELECT  fecha_inicio_cobro, monto_cuota, numero_cuotas, numero_factura,
		periocidad, cantidad,  SUM(monto) AS pago FROM t_clientes_creditos LEFT JOIN t_lista_cobros ON 
		t_clientes_creditos.contrato_id=t_lista_cobros.credito_id  WHERE 
		t_clientes_creditos.contrato_id=\'' . $sCon . '\' GROUP BY contrato_id';
		
		$Consulta = $this->db->query ( $sQuery );
		$dMonto = 0;
		$dCuota = 0;
		$sFec = '';
		$iCuo = 0;
		$iPer = 0;
		$dPago = 0;
		$dCuota = 0;
		$fact = '';
		if ($Consulta->num_rows () > 0) {
			foreach ( $Consulta->result () as $row ) {
				$dMonto = $row->pago;
				$dCuota = $row->monto_cuota;
				$sFec = $row->fecha_inicio_cobro;
				$iCuo = $row->numero_cuotas;
				$iPer = $row->periocidad;
				$fact = $row->numero_factura;
			}
		}
		$arr = $this->GPlanCobro ( $sFec, $iCuo, $iPer );
		// Metodo de Invocacion
		foreach ( $arr as $sC => $sV ) {
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
			$arr_aux [] = array (
					'cuota' => $sV ['cuota'],
					'fecha' => $sV ['fecha'],
					'monto' => $dTotal,
					'estatus' => $sVal,
					'fact' => $fact 
			);
		}
		return $arr_aux;
	}
	function GListaPagos($sCon = '') {
		$oFil = array ();
		
		$oCabezera [1] = array (
				"titulo" => "CUOTA",
				"atributos" => "width:60px;;" 
		);
		$oCabezera [2] = array (
				"titulo" => "FECHA",
				"atributos" => "text-align: center;" 
		);
		$oCabezera [3] = array (
				"titulo" => "MONTO" 
		);
		$oCabezera [4] = array (
				"titulo" => "ESTATUS" 
		);
		$i = 0;
		$fact = '';
		$arr = $this->CPagos ( $sCon );
		// Metodo de Invocacion
		foreach ( $arr as $sC => $sV ) {
			++ $i;
			$fact = $sV ['fact'];
			$oFil [$i] = array (
					"1" => $i . ' DE ' . count ( $arr ), //
					"2" => $sV ['fecha'], //
					"3" => number_format ( $sV ['monto'], 2 ), //
					"4" => $sV ['estatus'] 
			) //
;
		}
		$titulo = "Numero de Factura:" . $fact;
		$titulo .= "<br>Numero de Contrato:" . $sCon;
		$oTable = array (
				"Cabezera" => $oCabezera,
				"Cuerpo" => $oFil,
				"Origen" => "json" 
		); // ,"titulo"=>$titulo);
		$tabla ['php'] = $oTable;
		$tabla ['json'] = json_encode ( $oTable );
		return $tabla;
	}
	public function DTControlMensualEstCuenta($contrato) {
		$q = "SELECT contrato_id,fecha_inicio_cobro,numero_cuotas,periocidad,monto_cuota FROM t_clientes_creditos WHERE contrato_id='" . $contrato . "' LIMIT 1";
		$busca = $this->db->query ( $q );
		$rsBusca = $busca->result ();
		
		foreach ( $rsBusca as $bus ) {
			
			$arr = $this->GPlanCobro ( $bus->fecha_inicio_cobro, $bus->numero_cuotas, $bus->periocidad, 1 );
			$oCabezera [1] = array (
					"titulo" => "MES",
					"atributos" => "text-align: left;" 
			);
			$oCabezera [2] = array (
					"titulo" => "MONTO",
					"atributos" => "text-align: left;" 
			);
			$oCabezera [3] = array (
					"titulo" => "PERIODO CUOTA",
					"atributos" => "text-align: left;" 
			);
			$oCabezera [4] = array (
					"titulo" => "#",
					"atributos" => "text-align: left;" 
			);
			$oCabezera [5] = array (
					"titulo" => "Detalle" 
			);
			$oCabezera [6] = array (
					"titulo" => "contrato",
					"oculto" => 1 
			);
			
			$cuerpo = array ();
			$i = 0;
			$faux = '';
			// Metodo de Invocacion
			foreach ( $arr as $sC => $sV ) {
				$i ++;
				$fp = explode ( '-', $sV ['fecha'] );
				$query = "select  YEAR(fecha) as ano,MONTH(fecha)as mes,sum(monto)as smonto,
							count(*)as cant 
							from t_lista_cobros 
							where t_lista_cobros.credito_id='" . $contrato . "' and month(fecha)=" . $fp [1] . " AND year(fecha)=" . $fp [2] . "
							GROUP BY YEAR(fecha), MONTH(fecha);";
				$cuota = $this->db->query ( $query );
				$cantCuota = $cuota->num_rows ();
				$monto = 0;
				$descuentos = 0;
				$detalle = "SIN CARGAS";
				$mes = $this->Descripcion_Mes ( $fp [1] );
				if ($cantCuota > 0) {
					$rsCuota = $cuota->result ();
					foreach ( $rsCuota as $cuo ) {
						$monto = $cuo->smonto;
						$descuentos = $cuo->cant;
					}
					$detQuery = "select fecha as FECHA_CUOTA,monto AS MONTO,descripcion as DESCRIPCION,farc AS FECHA_DESCUENTO,modificado AS FECHA_CARGA,
							ifnull(nomb,'SIN MODALIDAD') as MODALIDAD from t_lista_cobros 
							left join t_modalidad on t_modalidad.oid = t_lista_cobros.moda
							where credito_id='" . $contrato . "' AND fecha LIKE'" . $fp [2] . "-" . $fp [1] . "%'";
					$rsDetalle = $this->db->query ( $detQuery );
					$rsDet = $rsDetalle->result ();
					$detalle = '';
					foreach ( $rsDet as $det ) {
						$detalle .= "Descuento el dia " . $det->FECHA_DESCUENTO . ", monto " . number_format ( $det->MONTO, 2 ) . " BS. Modalidad:" . $det->MODALIDAD . "<br>";
					}
				}
				$cuerpo [$i] = array (
						"1" => $mes,
						"2" => $monto,
						"3" => $sV ['fecha'],
						"4" => $descuentos,
						"5" => $detalle,
						"6" => $contrato 
				);
				$faux = $sV ['fecha'];
			}
			$fila = $this->fuera_de_fecha ( $contrato, $faux, '1' );
			if ($fila != 0) {
				$i ++;
				$cuerpo [$i] = $fila;
			}
		}
		$obj = array (
				"Cabezera" => $oCabezera,
				"Cuerpo" => $cuerpo,
				"Origen" => "json",
				"msj" => 1 
		);
		return $obj;
	}
	public function DTControlMensual($contrato) {
		$q = "SELECT contrato_id,fecha_inicio_cobro,numero_cuotas,periocidad,monto_cuota FROM t_clientes_creditos WHERE contrato_id='" . $contrato . "' LIMIT 1";
		$busca = $this->db->query ( $q );
		$rsBusca = $busca->result ();
		
		foreach ( $rsBusca as $bus ) {
			
			$arr = $this->GPlanCobro ( $bus->fecha_inicio_cobro, $bus->numero_cuotas, $bus->periocidad, 1 );
			$oCabezera [1] = array (
					"titulo" => " ",
					"tipo" => "detallePost",
					"atributos" => "width:18px",
					"funcion" => "Cuotas_Por_Mes",
					"parametro" => "4,6,2" 
			);
			$oCabezera [2] = array (
					"titulo" => "MES",
					"atributos" => "text-align: left;width:100px;" 
			);
			$oCabezera [3] = array (
					"titulo" => "MONTO" 
			);
			$oCabezera [4] = array (
					"titulo" => "Peridodo CUOTA",
					"atributos" => "text-align: left;" 
			);
			$oCabezera [5] = array (
					"titulo" => "#",
					"atributos" => "text-align: left;width:20px;" 
			);
			$oCabezera [6] = array (
					"titulo" => "contrato",
					"oculto" => 1 
			);
			$cuerpo = array ();
			$i = 0;
			$faux = '';
			// Metodo de Invocacion
			foreach ( $arr as $sC => $sV ) {
				$i ++;
				$fp = explode ( '-', $sV ['fecha'] );
				$query = "select  YEAR(fecha) as ano,MONTH(fecha)as mes,sum(monto)as smonto,
							count(*)as cant 
							from t_lista_cobros 
							where t_lista_cobros.credito_id='" . $contrato . "' and month(fecha)=" . $fp [1] . " AND year(fecha)=" . $fp [2] . "
							GROUP BY YEAR(fecha), MONTH(fecha);";
				$cuota = $this->db->query ( $query );
				$cantCuota = $cuota->num_rows ();
				$monto = 0;
				$descuentos = 0;
				$mes = $this->Descripcion_Mes ( $fp [1] );
				if ($cantCuota > 0) {
					$rsCuota = $cuota->result ();
					foreach ( $rsCuota as $cuo ) {
						$monto = $cuo->smonto;
						$descuentos = $cuo->cant;
					}
				}
				$cuerpo [$i] = array (
						"1" => "",
						"2" => $mes,
						"3" => $monto,
						"4" => $sV ['fecha'],
						"5" => $descuentos,
						"6" => $contrato 
				);
				$faux = $sV ['fecha'];
			}
			$fila = $this->fuera_de_fecha ( $contrato, $faux );
			if ($fila != 0) {
				$i ++;
				$cuerpo [$i] = $fila;
			}
		}
		$obj = array (
				"Cabezera" => $oCabezera,
				"Cuerpo" => $cuerpo,
				"Origen" => "json",
				"msj" => 1 
		);
		return json_encode ( $obj );
	}
	public function DTControlMensual_Por_Mes($contrato, $fecha, $fuera) {
		$fecp = explode ( '-', $fecha );
		$fec = " AND fecha like '%" . $fecp [2] . "-" . $fecp [1] . "%'";
		if ($fuera == 'FUERA DE FECHA') {
			
			$fec = " AND fecha >='" . $fecp [2] . "-" . $fecp [1] . "-" . "01'";
		}
		
		$query = "select fecha as FECHA_CUOTA,monto AS MONTO,descripcion as DESCRIPCION,farc AS FECHA_DESCUENTO,modificado AS FECHA_CARGA,
					ifnull(nomb,'SIN MODALIDAD') as MODALIDAD from t_lista_cobros 
					left join t_modalidad on t_modalidad.oid = t_lista_cobros.moda
					where credito_id='" . $contrato . "' " . $fec;
		$his = $this->db->query ( $query );
		$rsHis = $his->result ();
		$cant = $his->num_rows ();
		$objeto = array ();
		if ($cant > 0) {
			$Cabezera = $his->list_fields ();
			$objeto = array (
					"Cabezera" => $Cabezera,
					"Cuerpo" => $rsHis,
					"Paginador" => 10,
					"Origen" => 'Mysql',
					"cant" => 1 
			);
		} else {
			$Cabezera [1] = array (
					"titulo" => "NO SE PROCESARON COBROS EN EL MES SELECCIONADO" 
			);
			
			$cu [1] = array (
					"1" => "SIN CUOTA" 
			);
			$objeto = array (
					"Cabezera" => $Cabezera,
					"Cuerpo" => $cu,
					"Origen" => 'json' 
			);
		}
		return json_encode ( $objeto );
	}
	public function fuera_de_fecha($contrato, $fecha, $tipo = null) {
		$pmes = $this->Mes_Siguiente ( $fecha );
		$fp = explode ( '-', $pmes );
		$query1 = "select sum(monto)as smonto,count(*)as cant_c 
			from t_lista_cobros 
			where t_lista_cobros.credito_id='" . $contrato . "' and fecha >= '" . $fp [2] . "-" . $fp [1] . "-01'";
		$cuot = $this->db->query ( $query1 );
		$cantCuota1 = $cuot->num_rows ();
		$monto = 0;
		$fila = 0;
		$detalle = "SIN CARGAS";
		if ($cantCuota1 > 0) {
			$rsCuota = $cuot->result ();
			foreach ( $rsCuota as $cuo ) {
				$monto = $cuo->smonto;
				$descuentos = $cuo->cant_c;
			}
			$detQuery = "select fecha as FECHA_CUOTA,monto AS MONTO,descripcion as DESCRIPCION,farc AS FECHA_DESCUENTO,modificado AS FECHA_CARGA,
					ifnull(nomb,'SIN MODALIDAD') as MODALIDAD from t_lista_cobros 
					left join t_modalidad on t_modalidad.oid = t_lista_cobros.moda
					where credito_id='" . $contrato . "' AND fecha LIKE'" . $fp [2] . "-" . $fp [1] . "%'";
			$rsDetalle = $this->db->query ( $detQuery );
			$rsDet = $rsDetalle->result ();
			if ($rsDetalle->num_rows () > 0) {
				$detalle = '';
				foreach ( $rsDet as $det ) {
					$detalle .= "Descuento el dia " . $det->FECHA_DESCUENTO . ", monto " . number_format ( $det->MONTO, 2 ) . " BS. Modalidad:" . $det->MODALIDAD . "<br>";
				}
			}
			$fila = array (
					"1" => "",
					"2" => "FUERA DE FECHA",
					"3" => $monto,
					"4" => $pmes,
					"5" => $descuentos,
					"6" => $contrato 
			);
			if ($tipo != null)
				$fila = array (
						"1" => "FUERA DE FECHA",
						"2" => $monto,
						"3" => $pmes,
						"4" => $descuentos,
						"5" => $detalle,
						"6" => $contrato 
				);
		}
		return $fila;
	}
	function Mes_Siguiente($fecha) {
		$fecha_p = explode ( '-', $fecha );
		if ($fecha_p [1] == '12') {
			$fecha_p [1] = 1;
			$fecha_p [2] ++;
		} else {
			$fecha_p [1] ++;
		}
		return '01-' . $fecha_p [1] . '-' . $fecha_p [2];
	}
	public function empresa($empresa) {
		switch ($empresa) {
			case 0 :
				return "Cooperativa";
				break;
			case 1 :
				return "Grupo";
				break;
		}
	}
	public function Periocidad($p) {
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
			case 8 :
				return "Mensual Los 10";
				break;
			case 9 :
				return "Mensual Los 25";
				break;
		}
	}
	function Estatus_Voucher($est, &$monto, $cuota) {
		$txtEstatusV = '';
		switch ($est) {
			case 0 :
				$txtEstatusV = 'Entregado al Cliente';
				break;
			case 1 :
				$monto += $cuota;
				$txtEstatusV = 'Pagado';
				break;
			case 2 :
				$txtEstatusV = 'Trasladado';
				break;
			case 3 :
				$txtEstatusV = 'Pagado Por Domiciliacion';
				$monto += $cuota;
				break;
			case 5 :
				$txtEstatusV = 'NO APLICA(D)';
				break;
			case 6 :
				$monto += $cuota;
				$txtEstatusV = 'NO APLICA(EXONERADO PRONTO PAGO)';
				break;
			default :
				break;
		}
		return $txtEstatusV;
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
			case 6 :
				return "<font color='#1c94c4'>Pronto Pago</font>";
				break;
			case 7 :
				return "<font color='#1c94c4'>COSECHAEXPRESS</font>";
				break;
		}
	}
	public function Descripcion_Mes($intCod) {
		switch ($intCod) {
			case 1 :
				return "ENERO";
			case 2 :
				return "FEBRERO";
			case 3 :
				return "MARZO";
			case 4 :
				return "ABRIL";
			case 5 :
				return "MAYO";
			case 6 :
				return "JUNIO";
			case 7 :
				return "JULIO";
			case 8 :
				return "AGOSTO";
			case 9 :
				return "SEPTIEMBRE";
			case 10 :
				return "OCTUBRE";
			case 11 :
				return "NOVIEMBRE";
			case 12 :
				return "DICIEMBRE";
		}
	}
	function Combo_Modalidad() {
		$mod = $this->db->query ( "SELECT * FROM t_modalidad" );
		$rsMod = $mod->result ();
		$valores = array ();
		foreach ( $rsMod as $moda ) {
			$valores [$moda->oid] = $moda->nomb;
		}
		return json_encode ( $valores );
	}

    function listaCobros(){

    }
}
?>