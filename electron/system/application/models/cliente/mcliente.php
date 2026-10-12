<?php
/**
 *  @author Carlos Enrique Peña Albarrán
 *  @package cooperativa.system.application.model.cliente
 *  @version 1.0.0
 */

class MCliente extends Model {

	var $tabla = 't_clientes_creditos';

	public function __construct() {
		parent::__construct();
	}

	/**
	 *
	 *
	 * @return boolean
	 */
	function registrar($MCredito) {

	}

	function jsCredito($documento_id = "", $contrato_id = "", $objCredito = null) {
		$jsC = array();
		$Consulta = $this -> db -> query("SELECT * FROM t_clientes_creditos WHERE contrato_id='$contrato_id' LIMIT 1");
		if ($Consulta -> num_rows() > 0) {
			foreach ($Consulta->result() as $row) {
				if ($row -> documento_id != $documento_id) {
					foreach (get_object_vars($objCredito) as $NombreCampo => $ValorCampo) {
						$jsC[$NombreCampo] = 1;
					}
				} else {// En caso de que exista el credito y sea del mismo cliente
					foreach (get_object_vars($row) as $NombreCampo => $ValorCampo) {
						if ($ValorCampo == "") {
							$ValorCampo_aux = " ";
						} else {
							$ValorCampo_aux = $ValorCampo;
						}
						$jsC[$NombreCampo] = $ValorCampo_aux;
					}
					if ($row -> marca_consulta == '6') {
						$arrVoucher = array();
						$tipo_voucher = '';
						$query_voucher = $this -> db -> query("SELECT * FROM t_lista_voucher WHERE cid='" . $row -> numero_factura . "'");
						if ($query_voucher -> num_rows() > 0) {
							foreach ($query_voucher -> result() as $row2) {
								$item = $row2 -> fecha . '|' . $row2 -> ndep . '|' . $row2 -> monto;
								$tipo_voucher = $row2 -> banco;
								$arrVoucher[] = $item;
							}
						}
						$jsC['lstVoucher'] = $arrVoucher;
						$jsC['tvoucher'] = $tipo_voucher;
					}
				}
			}
		} else {//No existe el Contrato
			foreach (get_object_vars($objCredito) as $NombreCampo => $ValorCampo) {
				$jsC[$NombreCampo] = 0;
			}
		}
		return json_encode($jsC);
	}

	/**
	 * Generar Array de los Contratos Generales
	 */
	function jsCExpediente($iCedula) {
		$directorio = BASEPATH . 'repository/expedientes/';
		$obCExpediente = array();
		$sConsulta = "SELECT t_personas.documento_id, CONCAT(primer_apellido,' ',LEFT(segundo_apellido,1),\". \",primer_nombre,\" \", LEFT(segundo_nombre,1),\". \") AS Nombre,
		 banco_1, banco_2, numero_factura, nomina_procedencia 
		 FROM t_personas 
		 LEFT JOIN t_clientes_creditos ON t_personas.documento_id = t_clientes_creditos.documento_id 
		 WHERE t_personas.documento_id=$iCedula GROUP BY numero_factura";
		$rsC = $this -> db -> query($sConsulta);
		$rs = $rsC -> result();
		$obCExpediente['cedula'] = $rs[0] -> documento_id;
		$obCExpediente['nombre'] = $rs[0] -> Nombre;
		$obCExpediente['banco_1'] = $rs[0] -> banco_1;
		$obCExpediente['banco_2'] = $rs[0] -> banco_2;
		$i = 0;
		$aux = array();
		if ($rsC -> num_rows() > 0) {
			foreach ($rs as $sC => $sV) {
				$obCExpediente['factura'][$i] = $sV -> numero_factura;
				$aux[$i] = $sV -> nomina_procedencia;
				$i++;
			}
		}
		$obCExpediente['nomina_procedencia'] = array_unique($aux);

		$imgCedula = "SELECT *,DATE_FORMAT(fecha,'%d-%m-%Y %h:%i:%S %p')as fecha2 FROM t_expcedula WHERE cedula=$iCedula limit 1";
		$rsImgCedula = $this -> db -> query($imgCedula);
		if ($rsImgCedula -> num_rows() > 0) {
			$rsImgCed = $rsImgCedula -> result();
			$ruta = $directorio.'personales/'.$rsImgCed[0] -> nombre;
			if(file_exists($ruta)){
				$obCExpediente['imgCedula'] = $rsImgCed[0] -> nombre;
				$obCExpediente['fecCedula'] = $rsImgCed[0] -> fecha2;	
			}else{
				$obCExpediente['imgCedula'] = '';
				$obCExpediente['fecCedula'] = '';
			}
			
		} else {
			$obCExpediente['imgCedula'] = '';
			$obCExpediente['fecCedula'] = '';
		}

		$imgFacturas = "SELECT *,DATE_FORMAT(fecha,'%d-%m-%Y %h:%i:%S %p')as fecha2 FROM t_expfactura WHERE cedula=$iCedula order by fecha desc";
		$rsImgFacturas = $this -> db -> query($imgFacturas);
		if ($rsImgFacturas -> num_rows() > 0) {
			$rsImgFac = $rsImgFacturas -> result();
			$aux2 = array();
			$aux2_1 = array();
			$i = 0;
			$ruta = $directorio.'facturas/';
			foreach ($rsImgFac as $rowFact) {
				$nombre = $ruta.$rowFact -> nombre;
				if(file_exists($nombre)){
					$aux2[$i] = $rowFact -> nombre;
					$aux2_1[$i] = $rowFact -> fecha2;
					$i++;	
				}
			}
			$obCExpediente['imgFacturas'] = $aux2;
			$obCExpediente['fecFacturas'] = $aux2_1;
		} else {
			$obCExpediente['imgFacturas'] = '';
			$obCExpediente['fecFacturas'] = '';
		}

		$imgBancos = "SELECT *,DATE_FORMAT(fecha,'%d-%m-%Y %h:%i:%S %p')as fecha2 FROM t_expbanco WHERE cedula=$iCedula order by fecha desc";
		$rsImgBancos = $this -> db -> query($imgBancos);
		if ($rsImgBancos -> num_rows() > 0) {
			$rsImgBan = $rsImgBancos -> result();
			$aux3 = array();
			$aux3_1 = array();
			$i = 0;
			$ruta = $directorio.'bancos/';
			foreach ($rsImgBan as $rowBan) {
				$nombre = $ruta.$rowBan -> nombre;
				if(file_exists($nombre)){
					$aux3[$i] = $rowBan -> nombre;
					$aux3_1[$i] = $rowBan -> fecha2;
					$i++;	
				}
				
			}
			$obCExpediente['imgBancos'] = $aux3;
			$obCExpediente['fecBancos'] = $aux3_1;
		} else {
			$obCExpediente['imgBancos'] = '';
			$obCExpediente['fecBancos'] = '';
		}

		$imgCartas = "SELECT *,DATE_FORMAT(fecha,'%d-%m-%Y %h:%i:%S%p')as fecha2 FROM t_expcartas WHERE cedula=$iCedula order by fecha desc";
		$rsImgCartas = $this -> db -> query($imgCartas);
		if ($rsImgCartas -> num_rows() > 0) {
			$rsImgCar = $rsImgCartas -> result();
			$aux4 = array();
			$aux4_1 = array();
			$i = 0;
			$ruta = $directorio.'cartas/';
			foreach ($rsImgCar as $rowCar) {
				if(file_exists($ruta.$rowCar -> nombre)){
					$aux4[$i] = $rowCar -> nombre;
					$aux4_1[$i] = $rowCar -> fecha2;
					$i++;	
				}
			}
			$obCExpediente['imgCartas'] = $aux4;
			$obCExpediente['fecCartas'] = $aux4_1;
		} else {
			$obCExpediente['imgCartas'] = '';
			$obCExpediente['fecCartas'] = '';
		}
		
		$imgFiador = "SELECT *,DATE_FORMAT(fecha,'%d-%m-%Y %h:%i:%S %p')as fecha2 FROM t_expfiador WHERE cedula=$iCedula order by fecha desc" ;
		$rsImgFiador = $this -> db -> query($imgFiador);
		if ($rsImgFiador -> num_rows() > 0) {
			$rsImgFia = $rsImgFiador -> result();
			$aux6 = array();
			$aux6_1 = array();
			$i = 0;
			$ruta = $directorio.'fiador/';
			foreach ($rsImgFia as $rowFia) {
				if(file_exists($ruta.$rowFia -> nombre)){
					$aux6[$i] = $rowFia -> nombre;
					$aux6_1[$i] = $rowFia -> fecha2;
					$i++;	
				}
				
			}
			$obCExpediente['imgFiador'] = $aux6;
			$obCExpediente['fecFiador'] = $aux6_1;
		} else {
			$obCExpediente['imgFiador'] = '';
			$obCExpediente['fecFiador'] = '';
		}
		
		$imgRif = "SELECT *,DATE_FORMAT(fecha,'%d-%m-%Y %h:%i:%S %p')as fecha2 FROM t_exprif WHERE cedula=$iCedula order by fecha desc" ;
		$rsImgRif = $this -> db -> query($imgRif);
		if ($rsImgRif -> num_rows() > 0) {
			$rsImgFia = $rsImgRif -> result();
			$aux7 = array();
			$aux7_1 = array();
			$i = 0;
			$ruta = $directorio.'rif/';
			foreach ($rsImgFia as $rowFia) {
				if(file_exists($ruta.$rowFia -> nombre)){
					$aux7[$i] = $rowFia -> nombre;
					$aux7_1[$i] = $rowFia -> fecha2;
					$i++;	
				}
				
			}
			$obCExpediente['imgRif'] = $aux7;
			$obCExpediente['fecRif'] = $aux7_1;
		} else {
			$obCExpediente['imgRif'] = '';
			$obCExpediente['fecRif'] = '';
		}

		$imgGarantia = "SELECT *,DATE_FORMAT(fecha,'%d-%m-%Y %h:%i:%S %p')as fecha2 FROM t_cheque_garantia WHERE cedula=$iCedula order by fecha desc";
		$rsImgGarantia = $this -> db -> query($imgGarantia);
		if ($rsImgGarantia -> num_rows() > 0) {
			$rsImgGar = $rsImgGarantia -> result();
			$aux5 = array();
			$aux5_1 = array();
			$i = 0;
			$ruta = $directorio.'garantia/';
			foreach ($rsImgGar as $rowGar) {
				if(file_exists($ruta.$rowGar -> nombre)){
					$aux5[$i] = $rowGar -> nombre;
					$aux5_1[$i] = $rowGar -> fecha2;
					$i++;	
				}
				
			}
			$obCExpediente['imgGarantia'] = $aux5;
			$obCExpediente['fecGarantia'] = $aux5_1;
		} else {
			$obCExpediente['imgGarantia'] = '';
			$obCExpediente['fecGarantia'] = '';
		}

		return json_encode($obCExpediente);
	}
	

	/**
	 * Ejectutar Disparos
	 * llevar un contrato a otro estado.
	 */
	function EjecutarDisparo($oidc, $idestino, $sObservacion, $iTipo) {
		$sQuery = "SELECT * FROM t_clientes_creditos WHERE contrato_id='" . $oidc . "' AND condicion!=0 LIMIT 1;";
		$sConsulta = $this -> db -> query($sQuery);
		if ($sConsulta -> num_rows() > 0 && $idestino == 4) {
			$idestino++;
		}
		$sCon = 'UPDATE t_estadoejecucion SET oide =' . $idestino . ', observacion=\'' . $sObservacion . '\', estatus=\'' . $iTipo . '\' WHERE oidc = \'' . $oidc . '\' LIMIT 1';
		$this -> db -> query($sCon);

	}

	/**
	 * En caso de los Artefactos
	 * @param Factura
	 * @param Destino Verdadero
	 * @param Observacion
	 *
	 */
	function EjecutarDisparoreRevision($oidf, $idestino, $sObservacion) {
		$sQuery = "SELECT * FROM t_clientes_creditos WHERE numero_factura='" . $oidf . "';";
		$sConsulta = $this -> db -> query($sQuery);
		if ($sConsulta -> num_rows() > 0) {
			foreach ($sConsulta -> result() as $row) {
				$sCon = 'UPDATE t_estadoejecucion SET oide =' . $idestino . ', observacion=\'' . $sObservacion . '\', estatus=1 WHERE oidc = \'' . $row -> contrato_id . '\' LIMIT 1';
				$this -> db -> query($sCon);
			}
		}
		$msj = 'Se realizo la operacion con exito...';
	}

	function EjecutarDisparoF($oidf, $idestino, $banco, $deposito,$cond) {

		if ($deposito != '' AND $banco != '') {
			$fecha = date("Y-m-d");
			$query = "SELECT contrato_id FROM t_clientes_creditos WHERE numero_factura = '" . $oidf . "'";
			$resultado = $this -> db -> query($query);
			foreach ($resultado -> result() as $row) {
				$dep = $banco . '-' . $deposito;
				$sCon = 'UPDATE t_estadoejecucion SET oide =' . $idestino . ', observacion=\'Aceptado,Depositado(' . $dep . ')\' WHERE oidc = \'' . $row -> contrato_id . '\' LIMIT 1';
				$this -> db -> query($sCon);
				$sCon2 = "UPDATE t_clientes_creditos SET num_operacion='" . $dep . "' , condicion=".$cond.", fecha_operacion='" . $fecha . "' WHERE contrato_id='" . $row -> contrato_id . "'";
				$this -> db -> query($sCon2);
			}
			$msj = 'Se realizo la operacion con exito...';
		} else {
			$msj = 'Debe Ingresar el numero de deposito antes de aceptar. Presiones F5..';
		}

		return $msj;

	}

	/**
	 *
	 *
	 */
	public function Listar_Contratos_Usuarios($arr = '', $nivel) {
		$fechas = '';
		$estatus = '';
		if ($arr['estatus'] == 1) {
			$estatus = " AND t_usuario.estatus = 1";
		}
		$usuarios = " t_clientes_creditos.expediente_c = '" . $arr['usuario'] . "' ";
		if ($arr['desde'] != '' && $arr['hasta'] != '') {
			$adesde = explode('/', $arr['desde']);
			$ahasta = explode('/', $arr['hasta']);
			$desde = $adesde[2] . '-' . $adesde[1] . '-' . $adesde[0];
			$hasta = $ahasta[2] . '-' . $ahasta[1] . '-' . $ahasta[0];
			$fechas .= " AND fecha_solicitud BETWEEN '" . $desde . "' AND '" . $hasta . "' ";
		}
		$strQuery = "SELECT t_clientes_creditos.contrato_id, t_personas.documento_id AS cedula,
						CONCAT(primer_nombre,' ',segundo_nombre,' ',primer_apellido,' ',segundo_apellido) AS nombre,
						t_clientes_creditos.fecha_solicitud, t_clientes_creditos.fecha_inicio_cobro,
						t_clientes_creditos.cobrado_en, t_clientes_creditos.nomina_procedencia,
						t_clientes_creditos.monto_total, t_clientes_creditos.monto_cuota,
						t_usuario.descripcion AS usuario_creador,
						IF(t_clientes_creditos.codigo_n_a != t_clientes_creditos.expediente_c,'SI','NO') AS Modificado,
						CASE
							WHEN t_clientes_creditos.estatus = 3 THEN 'Nulo'
							WHEN t_lista_cobros_u.monto IS NULL THEN 'Pendiente'
							WHEN t_lista_cobros_u.monto >= t_clientes_creditos.monto_total THEN 'Pagado'
							ELSE 'Pendiente'
						END AS Estatus
						FROM t_clientes_creditos
						LEFT JOIN t_personas ON t_personas.documento_id = t_clientes_creditos.documento_id
						LEFT JOIN t_lista_cobros_u ON t_lista_cobros_u.contrato_id = t_clientes_creditos.contrato_id
						LEFT JOIN t_usuario ON t_usuario.seudonimo = t_clientes_creditos.expediente_c
						WHERE " . $usuarios . $estatus . $fechas . "
						ORDER BY t_clientes_creditos.contrato_id DESC
		";

		$Consulta = $this -> db -> query($strQuery);
		$Conexion = $Consulta -> result();
		$i = 0;
		$filas = array();

		foreach ($Conexion as $row) {
			++$i;
			$filas[$i] = array("1" => $row -> cedula, "2" => $row -> nombre, "3" => $row -> contrato_id, "4" => $row -> fecha_solicitud, "5" => $row -> fecha_inicio_cobro, "6" => $row -> cobrado_en, "7" => $row -> nomina_procedencia, "8" => $row -> monto_total, "9" => $row -> monto_cuota, "10" => $row -> usuario_creador, "11" => $row -> Modificado, "12" => $row -> Estatus);
		}
		$oCabezera[1] = array("titulo" => "Cedula", "buscar" => 1, "atributos" => "width:80px");
		$oCabezera[2] = array("titulo" => "Nombre", "atributos" => "width:220px");
		$oCabezera[3] = array("titulo" => "Contrato", "buscar" => 1, "atributos" => "width:100px");
		$oCabezera[4] = array("titulo" => "Fecha Solicitud", "atributos" => "width:90px");
		$oCabezera[5] = array("titulo" => "Fecha Inicio Cobro", "atributos" => "width:90px");
		$oCabezera[6] = array("titulo" => "Cobrado En", "buscar" => 1, "atributos" => "width:150px");
		$oCabezera[7] = array("titulo" => "Nomina", "atributos" => "width:100px");
		$oCabezera[8] = array("titulo" => "Monto Total", "total" => 1, "atributos" => "width:90px;text-align:right");
		$oCabezera[9] = array("titulo" => "Monto Cuota", "total" => 1, "atributos" => "width:90px;text-align:right");
		$oCabezera[10] = array("titulo" => "Usuario", "buscar" => 1, "atributos" => "width:150px;text-align:center;padding-left:10px;");
		$oCabezera[11] = array("titulo" => "Modificado", "buscar" => 1, "atributos" => "width:70px");
		$oCabezera[12] = array("titulo" => "Estatus", "buscar" => 1, "atributos" => "width:70px");

		$Object = array("Cabezera" => $oCabezera, "Cuerpo" => $filas, "Origen" => "json", "Paginador" => 20);
		return json_encode($Object);

	}

	function PendientesDeposito($arr) {
		$ofil = array();
		$banco = array('EF' => "EFECTIVO", 'BI'=> "BICENTENARIO",
		"BA" => "BANESCO" ,"PR"=> "PROVINCIAL","VE"=> "VENEZUELA","BO"=> "BOD","FC"=> "BFC","DS"=> "DEL SUR",
		"MC"=> "MERCANTIL","IN"=> "INDUSTRIAL","CR"=> "CARONI","SO"=> "SOFITASA","CA"=>"CARIBE","EX"=>"EXTERIOR");
		$cond = array('0'=>'DEPOSITO','1' => 'TRANSFERENCIA', '2' => 'CHEQUE' , '3' => 'EFECTIVO');
		$oCabezera[1] = array("titulo" => "Cedula", "atributos" => "width:80px", "buscar" => 0);
		$oCabezera[2] = array("titulo" => "Datos Basicos", "atributos" => "width:250px", "buscar" => 0);
		$oCabezera[3] = array("titulo" => "Solicitud", "atributos" => "width:100px");
		$oCabezera[4] = array("titulo" => "Factura", "atributos" => "width:100px");
		$oCabezera[5] = array("titulo" => "Total F.", "atributos" => "width:100px");
		$oCabezera[6] = array("titulo" => "Total D.", "atributos" => "width:100px");
		$oCabezera[7] = array("titulo" => "Banco", "atributos" => "width:120px", "tipo" => "combo");
		$oCabezera[8] = array("titulo" => "N. Operacion", "tipo" => "texto", "atributos" => "width:120px");
		$oCabezera[9] = array("titulo" => "Condicion", "atributos" => "width:60px", "tipo" => "combo");
		if ($arr['perfil'] == 13 || $arr['perfil'] == 0) {
			$oCabezera[10] = array("titulo" => " ", "tipo" => "bimagen", "funcion" => $arr['acc_v'], "parametro" => "1,4,7,8,9", "ruta" => __IMG__ . "botones/aceptar1.png");
		} else {
			$oCabezera[10] = array("titulo" => " ", "atributos" => "width:10px");
		}

		$query = "SELECT t_personas.documento_id as ced,CONCAT(primer_nombre,' ',segundo_nombre,' ',primer_apellido,' ',segundo_apellido)as nombre,monto_operacion,
				numero_factura,fecha_solicitud,SUM(monto_total) as total 
				FROM t_clientes_creditos
				JOIN t_personas ON t_personas.documento_id = t_clientes_creditos.documento_id 
				JOIN t_estadoejecucion ON t_estadoejecucion.oidc = t_clientes_creditos.contrato_id 
				WHERE t_estadoejecucion.oide = 4 AND t_clientes_creditos.condicion=0 AND t_clientes_creditos.cantidad!=0
				GROUP BY numero_factura";

		$resultado = $this -> db -> query($query);
		$i = 0;
		foreach ($resultado -> result() as $row) {
			++$i;
			$ofil[$i] = array("1" => $row -> ced, "2" => $row -> nombre, "3" => $row -> fecha_solicitud, 
								"4" => $row -> numero_factura, "5" => $row -> total, "6" => $row -> monto_operacion, 
								"7" => "", "8" => "", "9" => "","10" => "");
		}
		$objetos = array("7" => $banco,"9" => $cond);
		$oTable = array("Cabezera" => $oCabezera, "Cuerpo" => $ofil, "Paginador" => 10, "Origen" => "json", "Objetos" => $objetos);
		return json_encode($oTable);

	}

	/**
	 * Departamento de Revisiones
	 * Antes de Enviar a Cobranza
	 */
	function PendientesRevision($arr) {
		$ofil = array();

		$oCabezera[1] = array("titulo" => "Cedula", "atributos" => "width:80px", "buscar" => 0);
		$oCabezera[2] = array("titulo" => "Datos Basicos", "atributos" => "width:250px", "buscar" => 0);
		$oCabezera[3] = array("titulo" => "Solicitud", "atributos" => "width:100px");
		$oCabezera[4] = array("titulo" => "Factura", "atributos" => "width:100px");
		$oCabezera[5] = array("titulo" => "Total F.", "atributos" => "width:100px");
		$oCabezera[6] = array("titulo" => "Total D.", "atributos" => "width:100px");
		$oCabezera[7] = array("titulo" => "Observacion", "tipo" => "texto", "atributos" => "width:100px");

		if ($arr['perfil'] == 15 || $arr['perfil'] == 0) {
			$oCabezera[8] = array("titulo" => " ", "tipo" => "bimagen", "funcion" => $arr['acc_v'], "parametro" => "1,4,7", "ruta" => __IMG__ . "botones/aceptar1.png");
		} else {
			$oCabezera[8] = array("titulo" => " ", "atributos" => "width:10px");
		}

		$query = "SELECT t_personas.documento_id as ced,CONCAT(primer_nombre,' ',segundo_nombre,' ',primer_apellido,' ',segundo_apellido)as nombre,monto_operacion,
				numero_factura,fecha_solicitud,SUM(monto_total) as total 
				FROM t_clientes_creditos
				JOIN t_personas ON t_personas.documento_id = t_clientes_creditos.documento_id 
				JOIN t_estadoejecucion ON t_estadoejecucion.oidc = t_clientes_creditos.contrato_id 
				WHERE t_estadoejecucion.oide = 2 
				GROUP BY numero_factura";

		$resultado = $this -> db -> query($query);
		$i = 0;
		foreach ($resultado -> result() as $row) {
			++$i;
			$ofil[$i] = array(//
			"1" => $row -> ced, //
			"2" => $row -> nombre, //
			"3" => $row -> fecha_solicitud, //
			"4" => $row -> numero_factura, //
			"5" => $row -> total, //
			"6" => $row -> monto_operacion, //
			"7" => "AUTORIZADO PARA VENTA", //
			"8" => "");
		}

		$oTable = array("Cabezera" => $oCabezera, "Cuerpo" => $ofil, "Origen" => "json");
		return json_encode($oTable);
	}

	function PendientesRev($arr, $iVal) {
		$ofil = array();

		$oCabezera[1] = array("titulo" => "Codigo", "atributos" => "width:80px", "buscar" => 0);
		$oCabezera[2] = array("titulo" => "Cedula", "atributos" => "width:80px", "buscar" => 0);
		$oCabezera[3] = array("titulo" => "Descripcion", "atributos" => "width:250px", "buscar" => 0);
		$oCabezera[4] = array("titulo" => "Monto Solicitud", "atributos" => "width:100px");
		$oCabezera[5] = array("titulo" => "Abono", "atributos" => "width:100px");
		$oCabezera[6] = array("titulo" => "Fecha Desde", "atributos" => "width:100px");
		$oCabezera[7] = array("titulo" => "Fecha Hasta", "atributos" => "width:100px");
		$oCabezera[8] = array("titulo" => "Ubicacion", "tipo" => "texto", "atributos" => "width:100px");
		$oCabezera[9] = array("titulo" => "Usuario", "tipo" => "texto", "atributos" => "width:100px");
		$oCabezera[10] = array("titulo" => "Observacion", "tipo" => "texto", "atributos" => "width:100px");
		$oCabezera[11] = array("titulo" => "Creado", "tipo" => "texto", "atributos" => "width:100px");

		if ($arr['perfil'] == 15 || $arr['perfil'] == 0) {
			$oCabezera[12] = array("titulo" => " ", "tipo" => "bimagen", "funcion" => "AceptarRevision", "parametro" => "1,4,7,2", "ruta" => __IMG__ . "botones/aceptar1.png");
			$oCabezera[13] = array("titulo" => " ", "tipo" => "bimagen", "funcion" => "RechazarRevision", "parametro" => "1", "ruta" => __IMG__ . "botones/cancelar1.png");
		} else {
			$oCabezera[12] = array("titulo" => " ", "tipo" => "enlace", "metodo" => 2, "funcion" => "factura_presupuesto", "parametro" => "1,2,13,4", "ruta" => __IMG__ . "botones/aceptar1.png", "target" => "_top");
			$oCabezera[13] = array("titulo" => "", "tipo" => "texto", "oculto" => 1);
		}
		$sC = '';
		if ($iVal == 1) {
			$sC = " AND ubic = '" . $this -> session -> userdata('ubicacion') . "' ";
		}
		$query = "SELECT *, SUM(A.total) AS todo FROM (SELECT *, cuot*mont AS total FROM t_snegociacion WHERE est=" . $iVal . $sC . ") AS A
		JOIN t_scapacidad ON t_scapacidad.cert = A.cod	WHERE A.est=" . $iVal . $sC . "  GROUP BY A.cod";

		//echo $query;

		$resultado = $this -> db -> query($query);
		$i = 0;
		foreach ($resultado -> result() as $row) {
			++$i;
			$ofil[$i] = array(//
			"1" => $row -> cod, //
			"2" => $row -> ced, //
			"3" => $row -> dscr, //
			"4" => $row -> todo, //
			"5" => $row -> abon, //
			"6" => $row -> desd, //
			"7" => $row -> hast, //
			"8" => $row -> ubic, //
			"9" => $row -> usua, //
			"10" => "AUTORIZADO PARA VENTA", //
			"11" => $row -> modificado, //
			"12" => "", //
			"13" => $row -> empr, //
			);
		}

		$oTable = array("Cabezera" => $oCabezera, "Cuerpo" => $ofil, "Origen" => "json");
		return json_encode($oTable);
	}

	function ListarContratoPendientes($Fact = '') {
		$ofil = array();
		$forma = array('5-DOM'=> 'DOMICILIACION' ,'6-TRF'=> 'TRANSFERENCIA','6-PRV'=> 'VOUCHER - PROVINCIAL','6-BIC'=> 'VOUCHER - BICENTENARIO','6-COT'=> 'COTERO','5-VBI'=>'DOMICILIACION VOUCHER - BICENTENARIO');
		
		$nomina = array();
		$linaje = array();

		$query = 'SELECT nombre FROM t_nominas GROUP BY nombre';
		$consulta = $this -> db -> query($query);
		foreach ($consulta->result() as $row) {
			$nomina[$row -> nombre] = $row -> nombre;
		}

		$query = 'SELECT cobrado_en FROM t_clientes_creditos WHERE cobrado_en!=\'\' GROUP BY cobrado_en';
		$consult = $this -> db -> query($query);
		foreach ($consult->result() as $row) {
			$linaje[$row -> cobrado_en] = $row -> cobrado_en;
		}

		$oCabezera[1] = array("titulo" => "Contrato", "atributos" => "width:80px", "buscar" => 0);
		$oCabezera[2] = array("titulo" => "Factura", "atributos" => "width:80px", "buscar" => 0);
		$oCabezera[3] = array("titulo" => "Cuota", "atributos" => "width:50px");
		$oCabezera[4] = array("titulo" => "Monto Cuota", "atributos" => "width:100px");
		$oCabezera[5] = array("titulo" => "Solicitud", "atributos" => "width:100px", "oculto" => 1);
		$oCabezera[6] = array("titulo" => "Inicio Cobro", "atributos" => "width:100px");
		$oCabezera[7] = array("titulo" => "Motivo", "atributos" => "width:100px");
		$oCabezera[8] = array("titulo" => "Condicion", "atributos" => "width:100px");
		$oCabezera[9] = array("titulo" => "Linaje", "tipo" => "combo", "atributos" => "width:180px");
		$oCabezera[10] = array("titulo" => "Nomina", "tipo" => "combo", "atributos" => "width:180px");
		$oCabezera[11] = array("titulo" => "Contrato", "atributos" => "width:180px");
		$oCabezera[12] = array("titulo" => "Forma de Pago", "tipo" => "combo", "atributos" => "width:100px");
		//$oCabezera[13] = array("titulo" => "Factura Control", "tipo" => "texto_fijo", "atributos" => "width:100px");
		$oCabezera[13] = array("titulo" => "Observacion", "tipo" => "texto", "atributos" => "width:100px", "oculto" => 1);
		$oCabezera[14] = array("titulo" => "#", "tipo" => "bimagen", "funcion" => "AceptarCambiosContratos", "parametro" => "1,2,9,10,12,3,4,6,11", "ruta" => __IMG__ . "botones/aceptar1.png","atributos" => "width:10px");

		$sC = '';

		$query = "SELECT * FROM t_clientes_creditos WHERE numero_factura='" . $Fact . "'";

		$resultado = $this -> db -> query($query);
		$i = 0;
		foreach ($resultado -> result() as $row) {
			
			
			++$i;
			$ofil[$i] = array(//
			"1" => $row -> contrato_id, //
			"2" => $row -> numero_factura, //
			"3" => $row -> numero_cuotas, //
			"4" => $row -> monto_cuota, //
			"5" => $row -> fecha_solicitud, //
			"6" => $row -> fecha_inicio_cobro, //
			"7" => $this -> T_Motivo($row -> motivo), //
			"8" => $this -> Codicion($row -> condicion), //
			"9" => $row -> cobrado_en, //
			"10" => $row -> nomina_procedencia, //
			"11" => $this -> Tipo_Contrato($row -> forma_contrato), //
			"12" => 'DOMICILIACION', //
			"13" => "", //
			"14" => "");
		}
		
		$objetos = array("9" => $linaje, "10" => $nomina, "12" => $forma);
		$oTable = array("Cabezera" => $oCabezera, "Cuerpo" => $ofil, "Origen" => "json", "Objetos" => $objetos);
		return json_encode($oTable);

	}

	function PendientesDepositoImprimir($arr, $banco, $ubicacion) {
		$ofil = array();
		$ban = "";
		$ubi = "";
		if ($banco != "TODOS") {
			$ban = " AND t_clientes_creditos.cobrado_en ='" . $banco . "' ";
		}
		if ($ubicacion != "TODOS") {
			$ubi = "AND codigo_n ='" . $ubicacion . "' ";
		}

		$oCabezera[1] = array("titulo" => "Cedula", "atributos" => "width:80px", "buscar" => 0);
		$oCabezera[2] = array("titulo" => "Datos Basicos", "atributos" => "width:250px", "buscar" => 0);
		$oCabezera[3] = array("titulo" => "Solicitud", "atributos" => "width:100px");
		$oCabezera[4] = array("titulo" => "Factura", "atributos" => "width:100px");
		$oCabezera[5] = array("titulo" => "Total Factura", "atributos" => "width:100px");
		$oCabezera[6] = array("titulo" => "Total Depositar", "atributos" => "width:100px");
		$oCabezera[7] = array("titulo" => "N.Cuenta", "atributos" => "width:100px");
		$oCabezera[8] = array("titulo" => "Banco", "atributos" => "width:100px");

		$query = "SELECT t_personas.documento_id as ced,CONCAT(primer_nombre,' ',segundo_nombre,' ',primer_apellido,' ',segundo_apellido)as nombre,
				numero_factura,fecha_solicitud,SUM(monto_total) as total, monto_operacion, cuenta_1,cobrado_en,condicion
				FROM t_clientes_creditos
				JOIN t_personas ON t_personas.documento_id = t_clientes_creditos.documento_id
				JOIN t_estadoejecucion ON t_estadoejecucion.oidc = t_clientes_creditos.contrato_id
				WHERE t_estadoejecucion.oide = 4 " . $ban . " " . $ubi . "  AND condicion=0
				GROUP BY numero_factura order by fecha_solicitud";

		$resultado = $this -> db -> query($query);
		$i = 0;
		foreach ($resultado -> result() as $row) {
			++$i;
			$ofil[$i] = array("1" => $row -> ced, "2" => $row -> nombre, "3" => $row -> fecha_solicitud, "4" => $row -> numero_factura, "5" => $row -> total, "6" => $row -> monto_operacion, "7" => $row -> cuenta_1, "8" => $row -> cobrado_en, );
		}
		$oTable = array("Cabezera" => $oCabezera, "Cuerpo" => $ofil, "Paginador" => 40, "Origen" => "json");

		return json_encode($oTable);
	}

	/**
	 * Archivos Pendientes por procesar
	 * @string sUsr MUsuario
	 */
	function Pendientes($Arr) {
		$oFil = array();
		$i = 0;
		$sCon = '(';
		$sDependiente = '(';
		$lst = $Arr['linaje'];
		$usr = $Arr['dependiente'];
		$sOr = '';
		$sOrdependiente = '';
		foreach ($usr as $sC => $sV) {
			++$i;
			if ($i > 1) {$sOrdependiente = 'OR ';
			}
			$sDependiente .= $sOrdependiente . $this -> tabla . '.expediente_c = \'' . $sV['seudonimo'] . '\' ';
		}

		if ($i > 0) {
			$sDependiente .= ' OR t_clientes_creditos.expediente_c=\'' . $Arr['usr'] . '\') ';
		} else {
			$sDependiente = ' t_clientes_creditos.expediente_c=\'' . $Arr['usr'] . '\' ';
		}

		$i = 0;
		//Validar Linaje
		$usu = strtolower($this -> session -> userdata('usuario'));
		if($usu != 'alvaroz'){
			foreach ($lst as $sC => $sV) {
				if (trim($sV['valor']) != '') {
					++$i;
					if ($i > 1) {$sOr = 'OR ';
					}
					$sCon .= $sOr . $this -> tabla . '.cobrado_en = \'' . $sV['valor'] . '\' ';
				}
			}
		}
		if ($i > 0) {
			$sCon .= ' OR ' . $sDependiente . ') AND ';
			$sDependiente = '';
		} else {
			$sDependiente .= ' AND ';
			$sCon = '';
		}

		$t_origen = 't_estadoejecucion.oide=' . $Arr['org'];

		if ($Arr['org'] == 3) { $t_origen = '( t_estadoejecucion.oide=1 OR t_estadoejecucion.oide=3 )';
		}
		if ($Arr['org'] == 5) { $t_origen = 't_estadoejecucion.oide=' . $Arr['org'] . ' AND t_clientes_creditos.marca_consulta!=6 ';
		}
		if ($Arr['perfil'] == 11) {
			$t_origen = 't_estadoejecucion.oide=' . $Arr['org'] . ' AND t_clientes_creditos.marca_consulta=6 ';
			$sCon = '';
			$sDependiente = '';
		}

		$sCon = 'SELECT t_personas.documento_id,
		CONCAT(primer_apellido,\' \',LEFT(segundo_apellido,1),\'. \',primer_nombre,\' \', LEFT(segundo_nombre,1),\'. \') AS Nombre,
		cobrado_en,fecha_solicitud,t_clientes_creditos.contrato_id,monto_total,t_clientes_creditos.numero_factura, t_estadoejecucion.observacion
		FROM t_personas JOIN t_clientes_creditos ON t_personas.documento_id=t_clientes_creditos.documento_id
		JOIN t_estadoejecucion ON t_clientes_creditos.contrato_id=t_estadoejecucion.oidc
		WHERE ' . $sCon . $sDependiente . $t_origen . ' AND t_estadoejecucion.estatus=1 and t_clientes_creditos.cantidad != 0 order by documento_id';

		$Consulta = $this -> db -> query($sCon);
		$iCantidad = $Consulta -> num_rows();
		$Conexion = $Consulta -> result();
		if ($Arr['itipo'] > 0) {
			return $iCantidad;
		}
		$oCabezera[1] = array("titulo" => "Cedula", "atributos" => "width:80px", "buscar" => 0);
		$oCabezera[2] = array("titulo" => "Datos Basicos", "atributos" => "width:250px", "buscar" => 0);
		$oCabezera[3] = array("titulo" => "Solicitud", "atributos" => "width:100px");
		$oCabezera[4] = array("titulo" => "Contrato", "atributos" => "width:100px");
		$oCabezera[5] = array("titulo" => "Total", "atributos" => "width:100px");
		$oCabezera[6] = array("titulo" => "Banco", "atributos" => "width:120px");
		$oCabezera[7] = array("titulo" => "Factura", "atributos" => "width:120px", "oculto" => 0);
		$oCabezera[8] = array("titulo" => "Observacion", "tipo" => "texto", "atributos" => "width:120px");
		$oCabezera[9] = array("titulo" => " ", "tipo" => "bimagen", "funcion" => $Arr['acc_v'], "parametro" => "1,4,7,8,6", "ruta" => __IMG__ . "botones/aceptar1.png");
		if ($Arr['perfil'] > 3) {
			$oCabezera[10] = array("titulo" => " ", "tipo" => "bimagen", "funcion" => $Arr['acc_f'], "parametro" => "1,4,7,8,6", "ruta" => __IMG__ . "botones/cancelar1.png");
		} else {
			$oCabezera[10] = array("titulo" => " ", "oculto" => 0);
		}

		if ($iCantidad > 0) {
			$i = 0;
			foreach ($Conexion as $row) {
				++$i;
				$oFil[$i] = array("1" => $row -> documento_id, "2" => $row -> Nombre, "3" => $row -> fecha_solicitud, "4" => $row -> contrato_id, "5" => $row -> monto_total, "6" => $row -> cobrado_en, "7" => $row -> numero_factura, "8" => $row -> observacion, "9" => "", "10" => "");
			}
		}
		$oTable = array("Cabezera" => $oCabezera, "Cuerpo" => $oFil, "Paginador" => 10, "Origen" => "json");
		return json_encode($oTable);

	}

	/**
	 * Archivos Pendientes por procesar
	 * @string sUsr MUsuario
	 */
	function Rechazos($Arr) {
		$oFil = array();
		$i = 0;
		$sCon = '(';
		$sDependiente = '(';
		$lst = $Arr['linaje'];
		$usr = $Arr['dependiente'];
		$sOr = '';
		$Origen = 'json';
		$sOrdependiente = '';
		$linaje = false;
		foreach ($usr as $sC => $sV) {
			++$i;
			if ($i > 1) {$sOrdependiente = 'OR ';
			}
			$sDependiente .= $sOrdependiente . $this -> tabla . '.expediente_c = \'' . $sV['seudonimo'] . '\' ';
		}

		if ($i > 0) {
			$sDependiente .= ' OR t_clientes_creditos.expediente_c=\'' . $Arr['usr'] . '\') ';
		} else {
			$sDependiente = ' t_clientes_creditos.expediente_c=\'' . $Arr['usr'] . '\' ';
		}

		$i = 0;
		//Validar Linaje
		foreach ($lst as $sC => $sV) {
			if (trim($sV['valor']) != '') {
				++$i;
				if ($i > 1) {$sOr = 'OR ';
				}
				$sCon .= $sOr . $this -> tabla . '.cobrado_en = \'' . $sV['valor'] . '\' ';
			}
		}
		if ($i > 0) {
			$sCon .= ' OR ' . $sDependiente . ') AND ';
			$sDependiente = '';
			$linaje = true;
		} else {
			$sDependiente .= ' AND t_estadoejecucion.oide<=' . $Arr['org'] . ' AND ';
			$sCon = '';
		}

		$sCon = 'SELECT t_personas.documento_id AS Cedula,
		CONCAT(primer_apellido,\' \',LEFT(segundo_apellido,1),\'. \',primer_nombre,\' \', LEFT(segundo_nombre,1),\'. \') AS Nombre,
		t_clientes_creditos.contrato_id AS Contrato,cobrado_en AS Linaje,fecha_solicitud AS Solicitud,monto_total AS Total,
		t_clientes_creditos.numero_factura AS Factura, t_estadoejecucion.observacion AS Detalle,
		t_estadoejecucion.modificacion AS Modicado,t_clientes_creditos.cantidad as cantidad
		FROM t_personas JOIN t_clientes_creditos ON t_personas.documento_id=t_clientes_creditos.documento_id
		JOIN t_estadoejecucion ON t_clientes_creditos.contrato_id=t_estadoejecucion.oidc
		WHERE ' . $sCon . $sDependiente . ' t_estadoejecucion.estatus=0 and t_clientes_creditos.cantidad != 0';
		$Consulta = $this -> db -> query($sCon);
		$Cabezera = $Consulta -> list_fields();
		$iCantidad = $Consulta -> num_rows();
		$Conexion = $Consulta -> result();
		if ($linaje) {
			$oCabezera = $Cabezera;
			$oFil = $Conexion;
			$Origen = 'Mysql';
		} else {
			$oCabezera[1] = array("titulo" => "Cedula", "atributos" => "width:80px");
			$oCabezera[2] = array("titulo" => "Datos Basicos", "atributos" => "width:250px", "buscar" => 0);
			$oCabezera[3] = array("titulo" => "Contrato", "atributos" => "width:100px");
			$oCabezera[4] = array("titulo" => "Rechazo", "atributos" => "width:100px");
			$oCabezera[5] = array("titulo" => "Linaje", "atributos" => "width:120px");
			$oCabezera[6] = array("titulo" => "Factura", "oculto" => 0);
			$oCabezera[7] = array("titulo" => "Observacion", "tipo" => "textarea", "atributos" => "width:120px");
			$oCabezera[8] = array("titulo" => "Estatus");

			if ($iCantidad > 0) {
				$i = 0;
				foreach ($Conexion as $row) {
					$estatus = "A";
					++$i;
					if ($row -> cantidad == 0) {
						$estatus = 'N';
					}
					$oFil[$i] = array("1" => $row -> Cedula, "2" => $row -> Nombre, "3" => $row -> Contrato, "4" => $row -> Modicado, "5" => $row -> Linaje, "6" => $row -> Factura, "7" => $row -> Detalle, "8" => $estatus);
				}
			}
		}
		$oTable = array("Cabezera" => $oCabezera, "Cuerpo" => $oFil, "Paginador" => 10, "Origen" => $Origen);
		return json_encode($oTable);

	}

	/**
	 * TGrid Cliente Reportes Facturas Pendientes Por Fechas
	 */
	function TG_CFacturas($strDependencia = null, $dtd_nomina = null, $cobrado_en = null, $iEstado = null, $Arr) {
		$signo = "=";
		$banco = "";
		$deposito = "";
		$nom = '';
		if($dtd_nomina != 'TODOS'){
			$nom = ' AND nomina_procedencia="'.$dtd_nomina.'" ';
		}
		if ($cobrado_en != "TODOS") {
			$banco = " AND cobrado_en = '" . strtoupper($cobrado_en) . "' ";
		}
		if ($iEstado == 9) {
			$signo = "<";
		}
		if ($iEstado == 8) {
			$iEstado = 4;
			$signo = ">";
			$deposito = " AND t_clientes_creditos.condicion = 0 ";
		}

		$oFil = array();
		$set = "t_personas.documento_id AS cedula ,CONCAT(primer_apellido,' ' , primer_nombre) AS nombre,
		numero_factura AS factura, SUM(monto_total) AS total, fecha_solicitud AS solicitud, cobrado_en AS banco,
		SUM(t_clientes_creditos.cantidad) as cantidad, marca_consulta,t_clientes_creditos.condicion,monto_operacion,cuenta_1,
		banco_1,num_operacion,t_clientes_creditos.codigo_n,
		case cobrado_en
			when 'NOMINA' then CONCAT(banco_1,'-',cuenta_1)
			when banco_1 then cuenta_1
			else cuenta_2 
			end as cuenta";
		//$oCabezera[1] = array("titulo" => " ", "tipo" => "detallePre", "atributos" => "width:40px", "parametro" => "4", "ruta" => __IMG__ . "botones/add.png");
		$oCabezera[1] = array("titulo" => " ", "tipo" => "detallePost", "atributos" => "width:40px", "funcion" => "MuestraDetalleReporte", "parametro" => "4,10,2");
		$oCabezera[2] = array("titulo" => "Cedula", "buscar" => 1);
		$oCabezera[3] = array("titulo" => "Datos Basicos", "atributos" => "width:150px");
		$oCabezera[4] = array("titulo" => "Factura");
		$oCabezera[5] = array("titulo" => "Total", "total" => 1);
		$oCabezera[6] = array("titulo" => "Solicitud");
		$oCabezera[7] = array("titulo" => "Banco");
		$oCabezera[8] = array("titulo" => "Cuenta");
		$oCabezera[9] = array("titulo" => "Monto Op.");
		$oCabezera[10] = array("titulo" => "Forma Pago");
		$oCabezera[11] = array("titulo" => "Condicion", "buscar" => 1);
		$oCabezera[12] = array("titulo" => "N.Operacion", "buscar" => 1);
		$oCabezera[13] = array("titulo" => "Oficina", "buscar" => 1);
		$oCabezera[14] = array("titulo" => "Cheque", "tipo" => "enlace", "funcion" => "Ver_Cheque", "parametro" => "2,4", "metodo" => 1, "ruta" => __IMG__ . "botones/cheque_4.jpg", "atributos" => "width:12px", "target" => "_blank");

		$sCam = 'expediente_c';

		if ($Arr['perfil'] == 0 || $this -> session -> userdata('seudonimo') == 'Carlos' || $Arr['perfil'] == 9 || $Arr['perfil'] == 19 ) {
			$sCam = 'codigo_n';
		}
		if ($Arr['perfil'] == 5 && $iEstado < 4) {
			$oCabezera[15] = array("titulo" => " ", "tipo" => "bimagen", "atributos" => "width:40px", "funcion" => "AceptarFactura", "parametro" => "4", "ruta" => __IMG__ . "botones/aceptar1.png");
		} else {
			$oCabezera[15] = array("titulo" => " ", "oculto" => 1);
		}

		if ($strDependencia == "TODOS") {
			$strQuery = "SELECT $set FROM t_estadoejecucion
			JOIN t_clientes_creditos ON t_estadoejecucion.oidc=t_clientes_creditos.contrato_id
			JOIN t_personas ON t_clientes_creditos.documento_id=t_personas.documento_id
			WHERE " . $Arr['lista'] . "
			fecha_solicitud >= '" . $Arr['desde'] . "' AND fecha_solicitud <= '" . $Arr['hasta'] . "' 
			AND t_estadoejecucion.oide" . $signo . $iEstado . $banco . $deposito . $nom." 
			GROUP BY t_clientes_creditos.numero_factura ";
		} else {
			$strQuery = "SELECT $set FROM t_estadoejecucion
			JOIN t_clientes_creditos ON t_estadoejecucion.oidc=t_clientes_creditos.contrato_id
			JOIN t_personas ON t_personas.documento_id=t_clientes_creditos.documento_id
			WHERE t_clientes_creditos.$sCam='" . $strDependencia . "' 
			AND fecha_solicitud >= '" . $Arr['desde'] . "' AND fecha_solicitud <= '" . $Arr['hasta'] . "' 
			AND t_estadoejecucion.oide" . $signo . $iEstado . $deposito . $banco . $nom." 
			GROUP BY t_clientes_creditos.numero_factura " ;
			if ($iEstado == 4) {
				$strQuery = "SELECT $set FROM t_estadoejecucion
				JOIN t_clientes_creditos ON t_estadoejecucion.oidc=t_clientes_creditos.contrato_id
				JOIN t_personas ON t_personas.documento_id=t_clientes_creditos.documento_id
				WHERE t_clientes_creditos.$sCam='" . $strDependencia . "' AND fecha_solicitud >= '" . $Arr['desde'] . "' AND fecha_solicitud <= '" . $Arr['hasta'] . "' AND t_estadoejecucion.oide" . $signo . $iEstado . $banco . $nom." AND t_clientes_creditos.condicion=0 GROUP BY t_clientes_creditos.numero_factura ";
			}
		}

		$strQuery .= " order by fecha_solicitud";

		$Consulta = $this -> db -> query($strQuery);
		$i = 0;
		$Conexion = $Consulta -> result();
		foreach ($Conexion as $row) {
			$etiqueta1 = "";
			$etiqueta2 = "";
			if ($row -> cantidad == 0) {
				$etiqueta1 = "<s><font color=red>";
				$etiqueta2 = "</font></s>";
			}
			$sqlA = "SELECT COUNT(*), case marca_consulta
					when '5' then 'Domiciliacion'
					when '6' then 'Voucher'
					when '7' then 'Cotero'  
					end as marca_consulta,numero_factura,IFNULL(a.banco,'')as banco,modo
					FROM t_clientes_creditos 
					LEFT JOIN(SELECT cid,banco,modo FROM t_lista_voucher 
						WHERE cid='" . $row -> factura . "' GROUP BY cid)AS a ON a.cid = t_clientes_creditos.numero_factura
					WHERE numero_factura ='" . $row -> factura . "'
					group by marca_consulta	";
			
			$sBoucher = '';
			$Consulta2 = $this -> db -> query($sqlA);
			$cantidad = $Consulta2 -> num_rows();
			$ban_vou = '';
			$mc = '';
			foreach ($Consulta2 -> result() as $row2) {
				$ban_vou = $row2 -> banco;
				$mc = $row2 -> marca_consulta;
				if($row2 -> modo == 1) $mc = "TRANSFERENCIA";
			}
			if ($cantidad == 1) {
				$sBoucher = $mc . '||' . $ban_vou;
			} else {
				$sBoucher = 'Mixto-'.$mc[0].'||' . $ban_vou;
			}

			$con = $this -> Codicion($row -> condicion);
			++$i;
			$oFil[$i] = array("1" => "", "2" => $etiqueta1 . $row -> cedula . $etiqueta2, "3" => $etiqueta1 . $row -> nombre . $etiqueta2, 
			"4" => $etiqueta1 . $row -> factura . $etiqueta2, "5" => $row -> cantidad, "6" => $etiqueta1 . $row -> solicitud . $etiqueta2, 
			"7" => $etiqueta1 . $row -> banco . $etiqueta2, "8" => $etiqueta1 . $row -> cuenta. $etiqueta2, "9" => $etiqueta1 . $row -> monto_operacion . $etiqueta2, 
			"10" => $sBoucher, "11" => $con, "12" => $etiqueta1 . $row -> num_operacion . $etiqueta2, "13" => $row -> codigo_n, "14" => "", "15" => "");
		}
		$Object = array("Cabezera" => $oCabezera, "Cuerpo" => $oFil, "Origen" => "json", "Paginador" => 50, "query" => $strQuery);
		return json_encode($Object);
	}

	/**
	 * Evaluar condicion
	 */
	function Codicion($iCon) {
		$sCon = '';
		switch ($iCon) {
			case 0 :
				$sCon = 'Dep';
				break;
			case 1 :
				$sCon = 'Tran';
				break;
			case 3 :
				$sCon = 'Cheq';
				break;
			case 5 :
				$sCon = 'Arte';
				break;
			case 6 :
				$sCon = 'Auto';
				break;
			case 7 :
				$sCon = 'Moto';
				break;
			default :
				break;
		}
		return $sCon;
	}
	
	function T_Motivo($iMot) {
		$sMot = '';
		switch ($iMot) {
			case 1 :
				$sMot = 'Credito Propio (Moto o Artefacto)';
				break;
			case 2 :
				$sMot = 'Prestamo';
				break;
			default :
				break;
		}
		return $sMot;
	}

	function detalles_reporte_factura($factura) {
		$sqlDetalle = "SELECT contrato_id, fecha_solicitud, codigo_n_a, expediente_c, fecha_verificado FROM t_clientes_creditos WHERE numero_factura = '" . $factura . "'";
		$resultDetalle = $this -> db -> query($sqlDetalle);
		$detalles = $resultDetalle -> result();
		$detalle = "<br><center><table>
			<tr><th>Contrato</th><th>Solicitud</th><th>Creador</th><th>Modificador</th><th>Elaboracion</th></tr>";
		$j = 0;
		foreach ($detalles as $rowDetalle) {
			$detalle .= "<tr><td>" . $rowDetalle -> contrato_id . "</td><td>" . $rowDetalle -> fecha_solicitud . "</td>
			<td>" . $rowDetalle -> codigo_n_a . "</td><td>" . $rowDetalle -> expediente_c . "</td><td>" . $rowDetalle -> fecha_verificado . "</td></tr>";
			$j++;
		}
		$detalle .= "</table></center><br>";
		return $detalle;
	}

	/**
	 * Historial de Conversaciones
	 */
	function HConversaciones($sUsr) {
		$strQuery = "SELECT `from` AS De, `to` AS Para, `message` AS Mensaje, `sent` AS Tiempo FROM `chat`
		WHERE `from` ='$sUsr' OR `to`='$sUsr' ORDER BY sent ASC";
		$Consulta = $this -> db -> query($strQuery);
		$Object = array("Cabezera" => $Consulta -> list_fields(), "Cuerpo" => $Consulta -> result(), "Origen" => "Mysql", "Paginador" => 50);
		return json_encode($Object);
	}
	
	function Listar_Vendedores($sUsr) {
		$strQuery = "SELECT cedula,pnombre,papellido, numero_factura, fecha_solicitud, sum(cantidad) AS Monto
		 FROM `afiliacion` INNER JOIN t_clientes_creditos on afiliacion.cedula=t_clientes_creditos.documento_id WHERE codigovendedor='$sUsr' AND fecha_solicitud> '2015-01-01'
		 AND cantidad > 0
		GROUP BY documento_id, numero_factura";
		$Consulta = $this -> db -> query($strQuery);
		$Object = array("Cabezera" => $Consulta -> list_fields(), "Cuerpo" => $Consulta -> result(), "Origen" => "Mysql", "Paginador" => 50);
		return json_encode($Object);
	}
	
	

	function Pagos_Factura($_arr) {
		$lin = '';
		$ubica = '';
		if ($_arr['linaje'] != 'TODOS') {
			$lin = " AND cobrado_en='" . $_arr['linaje'] . "' ";
		}
		if($_arr['ubicacion']!='TODOS')$ubica="t_clientes_creditos.codigo_n='" . $_arr['ubicacion'] . "' AND ";

		$query = "SELECT t_personas.documento_id AS cedula, CONCAT( primer_apellido, ' ', segundo_apellido, ' ', primer_nombre, ' ', segundo_nombre ) AS nombre, 
			tbl.numero_factura AS factura, fecha_solicitud AS solicitud, fecha_inicio_cobro,cobrado_en AS banco, 
			b.mt AS total, 
			b.mt2 as total2,
			pagado as pagado_d,IFNULL(pg_voucher,0)as pagado_v,(pagado+IFNULL(pg_voucher,0)) as total_pagado,
			b.mt2 - pagado - IFNULL(pg_voucher,0) AS resta, 
			IF(b.mt2=0,'N','A') AS activo,count(*),telefono,codigo_n
		FROM t_clientes_creditos
		JOIN t_personas ON t_personas.documento_id = t_clientes_creditos.documento_id
		JOIN (SELECT numero_factura,sum(monto_total)as mt,sum(cantidad)as mt2 
		from t_clientes_creditos 
		where " . $ubica. " t_clientes_creditos.fecha_solicitud LIKE '%" . $_arr['ano'] . "-" . $_arr['mes'] . "%' " . $lin . "   
		group by numero_factura
		)as b on b.numero_factura = t_clientes_creditos.numero_factura 
		JOIN(SELECT t_clientes_creditos.numero_factura, monto_total, IFNULL( SUM( monto ) , 0 ) AS pagado
		FROM t_clientes_creditos
		LEFT JOIN t_lista_cobros ON t_clientes_creditos.contrato_id = t_lista_cobros.credito_id 
			AND t_clientes_creditos.documento_id = t_lista_cobros.documento_id 
		where " . $ubica . " t_clientes_creditos.fecha_solicitud LIKE '%" . $_arr['ano'] . "-" . $_arr['mes'] . "%' " . $lin . "
		GROUP BY numero_factura)
		AS tbl ON tbl.numero_factura=t_clientes_creditos.numero_factura
		LEFT JOIN 
		(SELECT t_clientes_creditos.numero_factura, monto_total as mtov, 
		IFNULL(tot_voucher,0) as pg_voucher
		FROM t_clientes_creditos
		LEFT JOIN (SELECT cid,sum(monto) AS tot_voucher 
					FROM t_lista_voucher 
					WHERE t_lista_voucher.estatus = 3 OR t_lista_voucher.estatus = 1 OR t_lista_voucher.estatus = 6
					GROUP BY cid
			)AS auxt on auxt.cid = t_clientes_creditos.numero_factura
			WHERE " . $ubica . "
			t_clientes_creditos.marca_consulta = 6 and t_clientes_creditos.fecha_solicitud LIKE '%" . $_arr['ano'] . "-" . $_arr['mes'] . "%' " . $lin . "
		GROUP BY numero_factura) 
		AS tblV ON tblV.numero_factura=t_clientes_creditos.numero_factura
		
		where " . $ubica . " t_clientes_creditos.fecha_solicitud LIKE '%" . $_arr['ano'] . "-" . $_arr['mes'] . "%' " . $lin;
		if ($_arr['tipo'] == 1)
			$query .= "and (pagado+IFNULL(pg_voucher,0)) = 0 ";
		$query .= " GROUP BY t_clientes_creditos.numero_factura ORDER BY fecha_solicitud, total_pagado";
		//return $query;
		$oCabezera[1] = array("titulo" => "Cedula", "atributos" => "width:80px", "buscar" => 1);
		$oCabezera[2] = array("titulo" => "Nombre", "atributos" => "width:250px");
		$oCabezera[3] = array("titulo" => "Factura", "atributos" => "width:100px", "buscar" => 1);
		$oCabezera[4] = array("titulo" => "Solicitud", "atributos" => "width:100px", "buscar" => 0);
		$oCabezera[5] = array("titulo" => "Banco", "atributos" => "width:100px", "buscar" => 0);
		$oCabezera[6] = array("titulo" => "Total", "atributos" => "width:50px","total"=>1);
		$oCabezera[7] = array("titulo" => "Pagado", "atributos" => "width:50px","total"=>1);
		$oCabezera[8] = array("titulo" => "Resta", "atributos" => "width:50px","total"=>1);
		$oCabezera[9] = array("titulo" => "Ubicacion", "atributos" => "width:100px", "buscar" => 0);
		//$oCabezera[9] = array("titulo" => "Estatus", "atributos" => "width:50px");

		$respuesta = $this -> db -> query($query);
		$oFil = array();
		if ($respuesta -> num_rows() > 0) {
			$i = 0;

			foreach ($respuesta -> result() as $row) {
				$eti2 = '';
				$eti = '';
				if ($row -> activo == 'N') {
					$eti = '<font color=red>';
					$eti2 = '</font>';
				}
				$i++;
				$oFil[$i] = array("1" => $eti .$row -> cedula. $eti2, "2" => $row -> nombre, "3" => $row -> factura, 
				"4" => $row -> solicitud, "5" => $row -> banco, "6" =>  $row -> total2, "7" =>  $row -> total_pagado , "8" =>  $row -> resta,
				"9"=>$row -> codigo_n
				//"9" => $eti.$row -> activo.$eti2
				);
			}
			$Object = array("Cabezera" => $oCabezera, "Cuerpo" => $oFil, "Origen" => "json", "msj" => 1,"query"=>$query);
		} else {
			$Object = array("msj" => 0);
		}
		return json_encode($Object);
	}

	/*
	 * TGrid Cliente Reportes Facturas Pendientes Por Fechas
	 */
	function TG_Cedula($sId = null) {

		$oFil = array();

		$ruta = BASEPATH . 'repository/expedientes/fotos/' . $sId . '.jpg';
		if (file_exists($ruta)) {
			$foto = __LOCALWWW__ . "/system/repository/expedientes/fotos/" . $sId . ".jpg";
		} else {
			$foto = __IMG__ . "sinfoto.png";
		}
		$sTitulo = '<table border = 0 style="width:100%;"><tr><td style="height: 100px;width: 110px;"><img id="foto" name="foto" src="' . $foto . '"  style="height: 100px;width: 110px;"/></td><td style="width:100px;"></td>';
		//$sTitulo = '';
		$sLeyenda = '';
		$ttgrid = '';

		$set = "t_personas.documento_id AS cedula, t_personas.disponibilidad, CONCAT( primer_apellido, ' ', segundo_apellido, ' ', primer_nombre, ' ', segundo_nombre ) AS nombre, 
		t_personas.expediente_caja, t_personas.codigo_nomina_aux, tbl.numero_factura AS factura, fecha_solicitud AS solicitud, cobrado_en AS banco, 
		SUM(t_clientes_creditos.monto_total) AS total, 
		SUM(t_clientes_creditos.cantidad) as total2,
		SUM(t_clientes_creditos.cantidad) - pagado - IFNULL(pg_voucher,0) AS resta, 
		IF(SUM(t_clientes_creditos.cantidad)=0,'N','A') AS activo,t_clientes_creditos.marca_consulta,nro_documento";
		$oCabezera[1] = array("titulo" => " ", "tipo" => "detallePost", "atributos" => "width:40px", "funcion" => "MuestraDetalleReporte", "parametro" => "3,11,13", "atributos" => "width:12px");
		$oCabezera[2] = array("titulo" => "Datos Basicos", "atributos" => "width:250px");
		$oCabezera[3] = array("titulo" => "Factura", "atributos" => "width:100px;text-align:center;");
		$oCabezera[4] = array("titulo" => "Total", "total" => 1, "atributos" => "width:100px;text-align:right;");
		$oCabezera[5] = array("titulo" => "Resta", "atributos" => "width:100px;text-align:right;");
		$oCabezera[6] = array("titulo" => "Solicitud", "atributos" => "width:100px;text-align:center;");
		$oCabezera[7] = array("titulo" => "Linaje");
		$oCabezera[8] = array("titulo" => "Comp. BFC", "tipo" => "enlace", "funcion" => "Imprimir_Compromiso", "parametro" => "3,13,8", "metodo" => 2, "atributos" => "width:50px;text-align:center;");
		$oCabezera[9] = array("titulo" => "Comp. PRO", "tipo" => "enlace", "funcion" => "Imprimir_Compromiso", "parametro" => "3,13,9", "metodo" => 2, "atributos" => "width:50px;text-align:center;");
		$oCabezera[10] = array("titulo" => "Comp. BIC", "tipo" => "enlace", "funcion" => "Imprimir_Compromiso", "parametro" => "3,13,10", "metodo" => 2, "atributos" => "width:50px;text-align:center;");
		$oCabezera[11] = array("titulo" => "Forma De Pago", "tipo" => "enlace", "funcion" => "Imprimir_Compromiso_Factura", "parametro" => "3", "metodo" => 2, "atributos" => "width:50px");
		//,"operacion"=>"(c[4]+c[5]+100)/100");
		$oCabezera[12] = array("titulo" => "#", "tipo" => "enlace", "metodo" => 2, "funcion" => "Imprimir_Facturas", "parametro" => "13,3", "ruta" => __IMG__ . "botones/print.png", "atributos" => "width:12px", "target" => "_blank");

		$oCabezera[13] = array("titulo" => "Cedula", "oculto" => 1);
		//LEFT JOIN t_responsable ON t_clientes_creditos.documento_id=t_responsable.cedula OR t_clientes_creditos.numero_factura = t_responsable.factura
		$strQuery = 'SELECT ' . $set . ' FROM t_personas
		JOIN t_clientes_creditos ON t_personas.documento_id = t_clientes_creditos.documento_id 
		JOIN(SELECT t_clientes_creditos.numero_factura, monto_total, IFNULL( SUM( monto ) , 0 ) AS pagado
			FROM t_clientes_creditos
			LEFT JOIN t_lista_cobros ON t_clientes_creditos.contrato_id = t_lista_cobros.credito_id AND t_clientes_creditos.documento_id = t_lista_cobros.documento_id 
			where t_clientes_creditos.documento_id=' . $sId . ' 
			GROUP BY numero_factura)AS tbl ON tbl.numero_factura=t_clientes_creditos.numero_factura
		LEFT JOIN 
		(SELECT t_clientes_creditos.numero_factura, monto_total as mtov, 
			IFNULL(tot_voucher,0) as pg_voucher
			FROM t_clientes_creditos
			LEFT JOIN (SELECT cid,sum(monto) AS tot_voucher 
				FROM t_lista_voucher 
				WHERE t_lista_voucher.estatus = 3 OR t_lista_voucher.estatus = 1 OR t_lista_voucher.estatus = 6
				GROUP BY cid
			)AS auxt on auxt.cid = t_clientes_creditos.numero_factura
			WHERE t_clientes_creditos.documento_id=' . $sId . '
			AND t_clientes_creditos.marca_consulta = 6
		GROUP BY numero_factura) 
		AS tblV ON tblV.numero_factura=t_clientes_creditos.numero_factura
		
		 where t_clientes_creditos.documento_id=' . $sId . ' GROUP BY t_clientes_creditos.numero_factura';

		$sLeyenda = "<br><center><a href='" . base_url() . "index.php/cooperativa/Imprimir_Estado_Cuenta/" . $sId . "' target='_blank'>
				<h2><font color='#1c94c4'>[ Estado De Cuenta ]</font></h2></a></center>";
		$Consulta = $this -> db -> query($strQuery);
		$i = 0;
		$iSuspendido = 0;
		$sfactura = '';
		$sBoucher = '';
		$Conexion = $Consulta -> result();
		$cantidad = $Consulta -> num_rows();
		if ($cantidad > 0) {
			foreach ($Conexion as $row) {
				$cedula = $row -> cedula;
				++$i;
				$sEtiquetaA = '';
				$sEtiquetaC = '';
				if ($row -> activo == 'N') {
					$sEtiquetaA = '<s><font color=red>';
					$sEtiquetaC = '</font></s>';
				}

				$sqlA = "SELECT marca_consulta,numero_control FROM t_clientes_creditos WHERE numero_factura='" . $row -> factura . "'";
				$Consulta2 = $this -> db -> query($sqlA);
				$Conexion2 = $Consulta2 -> result();
				$forma_contrato = array();
				foreach ($Conexion2 as $row2) {
					if ($row2 -> marca_consulta == 6) {
						$forma_contrato[] = 'Voucher';
					} else {
						$forma_contrato[] = 'Domiciliacion';
					}
					
				}
				
				$arreglo = array_unique($forma_contrato);
				$cantidad = count($arreglo);
				if ($cantidad == 1) {
					$sBoucher = $arreglo[0];
				} else {
					$sBoucher = 'Mixto';
				}

				/*if($row->marca_consulta == 6){
				 $sBoucher = 'Voucher';
				 }else{
				 $sBoucher = 'Domiciliacion';
				 }*/
				$oFil[$i] = array("1" => "", "2" => $sEtiquetaA . $row -> nombre . $sEtiquetaC, "3" => $sEtiquetaA . $row -> factura . $sEtiquetaC, "4" => $row -> total2,
				//"5" => number_format($row->resta,2,".",","),
				"5" => $row -> resta, "6" => $sEtiquetaA . $row -> solicitud . $sEtiquetaC, "7" => $sEtiquetaA . $row -> banco . $sEtiquetaC, "8" => "BFC", "9" => "PRV", "10" => "BIC", "11" => $sBoucher, "12" => "Ver Factura", "13" => $cedula);

				$iSuspendido = $row -> disponibilidad;
				$sNom = 'Cedula: V-' . $row -> cedula . '<br> Apellidos y Nombres: ' . $row -> nombre . '<br>Codigo Cliente:' . $row -> nro_documento;

			}
			$sCliente = ' ACTIVO';
			$alerta = '';

			$sCont = '';
			$his = '';
			if ($iSuspendido == 1) {
				$textoAux = 'SIN REGISTRO';
				$sCliente = '<font color=red>SUSPENDIDO Verifique el historial</font>';
				$alerta = $sCliente;
			}
			if ($iSuspendido == 2) {
				$textoAux = 'SIN REGISTRO';
				$sCliente = '<font color=red>ESTA BLOQUEADO DEL SISTEMA NO SE LE PUEDE INGRESAR NINGUN CONTRATO NUEVO. Verifique el historial</font>';
				$alerta = $sCliente;
			}
			$CMotivo = $this -> db -> query("SELECT * FROM _th_sistema 
			join t_estatus_sistema on _th_sistema.tipo = t_estatus_sistema.oid
			WHERE referencia='" . $sId . "'");
			if ($CMotivo -> num_rows() > 0) {
				$tablaH = "<div style='display:none;'id='divHistorial'><table width=100% class='tgrid'><tr><th>TIPO</th><th>PETICION</th><th>MOTIVO</th><th>FECHA</th></tr>";
				foreach ($CMotivo -> result() as $auxRow) {
					$tablaH .= "<tr><td>" . $auxRow -> nombre . "</td><td>" . $auxRow -> peticion . "</td><td>" . $auxRow -> motivo . "</td><td>" . $auxRow -> fecha . "</td></tr>";
					//$textoAux = '<br>Motivo: '.$auxRow -> motivo . '<br>Petici&oacute;n:' . $auxRow -> peticion . ' | ' . $auxRow -> fecha;
				}
				$tablaH .= "</table><a href='#' onclick='$(\"#divHistorial\").hide();'>Ocultar</a></div>";
				$his = "<br><font color=red><a href='#' onclick='$(\"#divHistorial\").show();'>HISTORIAL</a></font>" . $tablaH;
				$sCliente .= $his;
			}
			$sTitulo .= '<td><h2>' . $sNom . '</h2><br>ESTATUS DEL CLIENTE: ' . $sCliente . '<br>CREADO:  ' . $row -> expediente_caja . '<br>MODIFICADO:  ' . $row -> codigo_nomina_aux . '<br>&nbsp;</td>';
			$sTitulo .= '</tr></table>';
			$ttgrig = '<br><h2>Facturas De Credito y/o financiamiento</h2>';
			$Object = array("Cabezera" => $oCabezera, "Cuerpo" => $oFil, "Origen" => "json", "Paginador" => 0, "titulo" => $ttgrig, "leyenda" => $sLeyenda, "alerta" => $alerta);
		} else {
			$Object = array("msj" => "No Se encontraron datos asociados a la cedula ingresada....");
		}

		///////*
		$queryVC = "select * from t_venta_contado where cedula = '" . $sId . "'";
		$ConsultaVC = $this -> db -> query($queryVC);
		$iCantidadVC = $ConsultaVC -> num_rows();
		$ConexionVC = $ConsultaVC -> result();

		$oCabezera2[1] = array("titulo" => "Cedula", "atributos" => "width:80px");
		$oCabezera2[2] = array("titulo" => "Nombre", "atributos" => "width:250px");
		$oCabezera2[3] = array("titulo" => "Factura", "atributos" => "width:50px");
		$oCabezera2[4] = array("titulo" => "Monto", "atributos" => "width:80px");
		$oCabezera2[5] = array("titulo" => "Fecha", "atributos" => "width:50px");
		$oCabezera2[6] = array("titulo" => "Detalle", "atributos" => "width:200px");

		$sTituloV = '';
		$txtEstatusV = '';

		if ($iCantidadVC > 0) {
			$i = 0;
			foreach ($ConexionVC as $rowV) {
				++$i;
				$oFil2[$i] = array("1" => $rowV -> cedula, "2" => $rowV -> nombre, "3" => $rowV -> factura, "4" => $rowV -> monto, "5" => $rowV -> fecha, "6" => $rowV -> descrip);
			}
			if ($cantidad == 0) {
				$consul_cliente = $this -> db -> query("SELECT documento_id,CONCAT( primer_apellido, ' ', segundo_apellido, ' ', primer_nombre, ' ', segundo_nombre ) AS nombre,
				expediente_caja,codigo_nomina_aux,nro_documento
				 FROM t_personas WHERE documento_id='" . $sId . "'");
				foreach ($consul_cliente -> result() as $cliente) {
					$sNom = 'Cedula: V-' . $cliente -> documento_id . '<br> Apellidos y Nombres: ' . $cliente -> nombre . '<br>Codigo Cliente:' . $cliente -> nro_documento;
					$sTitulo .= '<td><h2>' . $sNom . '</h2><br>CREADO:  ' . $cliente -> expediente_caja . '<br>MODIFICADO:  ' . $cliente -> codigo_nomina_aux . '<br>&nbsp;</td>';
					$sTitulo .= '</tr></table>';
				}
			}
			$sTituloV = "<br><br>Facturas De Contado<br>";
			$sLeyendaV = '<br><center><h2>';

			$oTable = array("Cabezera" => $oCabezera2, "Cuerpo" => $oFil2, "Origen" => "json", "titulo" => $sTituloV, "leyenda" => $sLeyendaV);

		} else {

			$oTable = array("msj" => "<br >No Se encontraron datos al contado asociados a la cedula ingresada....");
		}
		$compuesto = array("obj1" => $Object, "obj2" => $oTable, 'persona' => $sTitulo);
		return json_encode($compuesto);
		/////////

		//return json_encode($Object);
	}

	function Detalles_Facturas_Contratos($Arr) {
		//variable que me determin CUANTOS GRID SE DEBEN MOSTRAR
		$com = 1;
		$final = array();
		$voucher1 = array();
		$voucher2 = array();
		$variable2 = array();
		/*
		 * Grid Contratos
		 **/
		$contratos = $this -> Grid_Domi($Arr['factura'], $Arr['cedula'], 1,"","DOMICILIACION");
		$variable2[] = 'contratos';
		/*
		 * Grid Voucher no aplica en caso de que existen
		 */
		$voucher1 = $this -> Grid_Voucher($Arr['factura'], 0);
		if ($voucher1 != 0) {
			$com++;
			$variable2[] = 'voucher1';
		}
		/*
		 * Grid Voucher pronto pago no aplica en caso de que existen
		 */
		$voucher2 = $this -> Grid_Voucher($Arr['factura'], 1);
		if ($voucher2 != 0) {
			$com++;
			$variable2[] = 'voucher2';
		}
		/*
		 * Valida cuantos grid debe generar
		 */
		if ($com == 1) {
			$final = $contratos;
		} else {
			$variable = array("obj1", "obj2", "obj3");
			for ($j = 0; $j < $com; $j++) {
				$ele = $variable2[$j];
				$pos = $variable[$j];
				$elementos[$pos] = $$ele;
			}
			$final = array("compuesto" => $com, "objetos" => $elementos);
		}

		/* print("<pre>");
		 print_r($final);
		 return "conta:".$com;*/
		return json_encode($final);

	}

	function Detalles_Facturas_Voucher($Arr) {
		$com = 1;
		$final = array();
		$voucher1 = array();
		$voucher2 = array();
		$variable2 = array();
		/*
		 * Grid Contratos
		 **/
		$contratos = $this -> Grid_Domi($Arr['factura'], $Arr['cedula'], 0,"","VOUCHER");
		$variable2[] = 'contratos';
		/*
		 * Grid Voucher no aplica en caso de que existen
		 */
		$voucher1 = $this -> Grid_Voucher($Arr['factura'], 0);
		if ($voucher1 != 0) {
			$com++;
			$variable2[] = 'voucher1';
		}
		/*
		 * Valida cuantos grid debe generar
		 */
		if ($com == 1) {
			$final = $contratos;
		} else {
			$variable = array("obj1", "obj2", "obj3");
			for ($j = 0; $j < $com; $j++) {
				$ele = $variable2[$j];
				$pos = $variable[$j];
				$elementos[$pos] = $$ele;
			}
			$final = array("compuesto" => $com, "objetos" => $elementos);
		}

		/* print("<pre>");
		 print_r($final);
		 return "conta:".$com;*/
		return json_encode($final);
	}

	function Detalles_Facturas_Mixto($Arr) {
		//variable que me determin CUANTOS GRID SE DEBEN MOSTRAR
		$com = 2;
		$final = array();
		$voucher1 = array();
		$voucher2 = array();
		$variable2 = array();
		/*
		 * Grid Contratos
		 **/
		$contratos = $this -> Grid_Domi($Arr['factura'], $Arr['cedula'], 1,5,"DOMICILIACION");
		$contratos2 = $this -> Grid_Domi($Arr['factura'], $Arr['cedula'], 0,6,"VOUCHER");
		$variable2[] = 'contratos';
		$variable2[] = 'contratos2';
		/*
		 * Grid Voucher no aplica en caso de que existen
		 */
		$voucher1 = $this -> Grid_Voucher($Arr['factura'], 0);
		if ($voucher1 != 0) {
			$com++;
			$variable2[] = 'voucher1';
		}
		/*
		 * Grid Voucher pronto pago no aplica en caso de que existen
		 */
		$voucher2 = $this -> Grid_Voucher($Arr['factura'], 1);
		if ($voucher2 != 0) {
			$com++;
			$variable2[] = 'voucher2';
		}
		/*
		 * Valida cuantos grid debe generar
		 */
		if ($com == 1) {
			$final = $contratos;
		} else {
			$variable = array("obj1", "obj2", "obj3","obj4");
			for ($j = 0; $j < $com; $j++) {
				$ele = $variable2[$j];
				$pos = $variable[$j];
				$elementos[$pos] = $$ele;
			}
			$final = array("compuesto" => $com, "objetos" => $elementos);
		}

		/* print("<pre>");
		 print_r($final);
		 return "conta:".$com;*/
		return json_encode($final);
		
	}

	function montos_contrato($contrato, $cedula) {
		$montos = array();
		$montos['pagado'] = null;
		$montos['resta'] = null;
		$strQuery = "select sum(t_lista_cobros.monto) as pagado,t_clientes_creditos.monto_total,t_clientes_creditos.monto_total - sum(t_lista_cobros.monto)  as resta
			from t_clientes_creditos
			join t_lista_cobros ON t_clientes_creditos.contrato_id = t_lista_cobros.credito_id
			where t_clientes_creditos.contrato_id = '" . $contrato . "' AND t_clientes_creditos.estatus!=3 AND t_lista_cobros.documento_id = '" . $cedula . "'
			group by t_lista_cobros.credito_id";
		$Consulta = $this -> db -> query($strQuery);
		$Conexion = $Consulta -> result();
		foreach ($Conexion as $row) {
			$montos['pagado'] = $row -> pagado;
			$montos['resta'] = $row -> resta;
		}
		return $montos;
	}

	/*
	 * Funcion para crear los grid de los contratos por domiciliacion dependiendo del pronto
	 */
	function Grid_Domi($fac, $ced,$carga,$marca = '',$tit) {

		$marca_consulta = "";
		if($marca != '') $marca_consulta = " AND marca_consulta = ".$marca; 
		$sqlDetalle = "SELECT documento_id, contrato_id, fecha_solicitud, codigo_n, codigo_n_a, expediente_c, fecha_verificado, monto_total, numero_control,
						nomina_procedencia, estatus, forma_contrato, cobrado_en, empresa, fecha_inicio_cobro, monto_cuota, periocidad, numero_cuotas, marca_consulta
						FROM t_clientes_creditos WHERE numero_factura = '" . $fac . "' AND documento_id ='" . $ced . "' ".$marca_consulta ;
		$Consulta = $this -> db -> query($sqlDetalle);
		$sTitulo = '';
		$sLeyenda = '';
		$oCabezera[1] = array("titulo" => " ", "tipo" => "detallePre", "atributos" => "width:18px", "ruta" => __IMG__ . "botones/abrir.png");
		$oCabezera[2] = array("titulo" => " ", "tipo" => "enlace", "funcion" => "Consultar", "parametro" => "3,4", "metodo" => 1, "ruta" => __IMG__ . "botones/add.png", "atributos" => "width:10px");
		if($carga == 0) $oCabezera[2] = array("titulo" => " ", "oculto"=>1);
		$oCabezera[3] = array("titulo" => "Cedula", "atributos" => "width:70px", "oculto"=>1);
		//$oCabezera[4] = array("titulo" => "Contrato", "tipo" => "enlace", "funcion" => "Imprimir_Contratos", "parametro" => "3,4", "metodo" => 2, "target" => '_blank',"atributos" => "width:40px");version anterior
		$oCabezera[4] = array("titulo" => "Contrato", "tipo" => "enlace", "funcion" => "Imprimir_Contrato_Nuevo", "parametro" => "3,4", "metodo" => 2, "target" => '_blank',"atributos" => "width:40px");
		$oCabezera[5] = array("titulo" => "Monto Cuota", "atributos" => "width:70px");
		$oCabezera[6] = array("titulo" => "Numero Cuota", "atributos" => "width:70px");
		$oCabezera[7] = array("titulo" => "Tipo <br>Credito", "atributos" => "width:80px");
		$oCabezera[8] = array("titulo" => "Estatus", "atributos" => "width:80px");
		$oCabezera[9] = array("titulo" => "Fecha Solicitud", "atributos" => "width:80px");
		$oCabezera[10] = array("titulo" => "Monto Total", "atributos" => "width:60px");
		$oCabezera[11] = array("titulo" => "Monto Pagado", "atributos" => "width:60px");
		$oCabezera[12] = array("titulo" => "Monto Restante", "atributos" => "width:60px");
		$oCabezera[13] = array("titulo" => "", "atributos" => "width:5px","oculto" => 1);
		$sCedula = '';
		$sContrato = '';
		$i = 0;
		$Conexion = $Consulta -> result();
		$sC = '';
		$total_factura = 0;
		$total_resta = 0;
		$pagado = 0;
		$resta = 0;
		foreach ($Conexion as $row) {
			$mAnulado = '';
			$iAnulado = 0;
			$etiqueta1 = '';
			$etiqueta2 = '';
			++$i;
			$montos = $this -> montos_contrato($row -> contrato_id, $row -> documento_id);
			if ($montos['resta'] != null) {
				$resta = $montos['resta'];
			} else {
				$resta = $row -> monto_total;
			}
			if ($montos['pagado'] != null) {
				$pagado = $montos['pagado'];
			} else {
				$pagado = 0;
			}
			$sC = $row -> documento_id;
			if ($row -> estatus != 3) {
				$total_factura = $total_factura + $row -> monto_total;
				$total_resta = $total_resta + $resta;
			}

			$sCedula = $row -> documento_id;
			$sContrato = $row -> contrato_id;

			$html = '<br><p><font color="#1c94c4">Nomina:</font> <b>' . $row -> nomina_procedencia . '</b> <br> <font color="#1c94c4">Linaje:</font><b>' . $row -> cobrado_en . '</b> <br> <font color="#1c94c4">Empresa: </font><b>' . $this -> Empresa($row -> empresa) . '</b><br><font color="#1c94c4">F.Inicio Cobro:</font><b>' . $row -> fecha_inicio_cobro . ' </b><br><font color="#1c94c4">Periocidad:</font><b>' . $this -> Periocidad($row -> periocidad) . '</b>';

			$html .= '</p><br><i><b>Ubicaci&oacute;n:</b> <font color="#1c94c4">' . $row -> codigo_n . '</font><br><b> Creado Por: </b>' . $row -> expediente_c . '.<br><b>Modificado Por</b> : ' . $row -> codigo_n_a . '<br><p align=right>Fecha de la Ultima Revisi&oacute;n: ' . $row -> fecha_verificado . '</p>';
			$html .= '<br><p><a href=\'' . __LOCALWWW__ . '/index.php/cooperativa/ipdf/' . $row -> cobrado_en .'/'. $row -> contrato_id . '\' border=0 target=\'top\'><center><img src=\'' . __IMG__ . 'pdf.png\'><br>	Imprimir Formato</a></p>';

			$queryAnulados = "SELECT * FROM _th_sistema WHERE referencia ='" . $sContrato . "' AND tipo=9 LIMIT 1";
			$consultaAnulado = $this -> db -> query($queryAnulados);
			if ($consultaAnulado -> num_rows() > 0) {
				$iAnulado = 1;
				foreach ($consultaAnulado -> result() as $rowAnulado) {
					$mAnulado .= "<br>Motivo Anulacion:<font color=red>" . $rowAnulado -> motivo . " </font>| <br>Usuario:<font color=red>" . $rowAnulado -> usuario . " </font>| 
					Peticion:<font color=red>" . $rowAnulado -> peticion . " </font>| fecha:<font color=red>" . $rowAnulado -> fecha . " </font>| ";
				}
			}

			$queryAnulados2 = "SELECT * FROM _th_sistema WHERE referencia ='" . $fac . "' AND tipo=12 LIMIT 1";
			$consultaAnulado2 = $this -> db -> query($queryAnulados2);
			$mAnulado2 = '';
			if ($consultaAnulado2 -> num_rows() > 0) {
				$iAnulado = 1;
				foreach ($consultaAnulado2 -> result() as $rowAnulado2) {
					$mAnulado2 .= "<br>Motivo Anulacion:<font color=red>" . $rowAnulado2 -> motivo . " </font>| <br>Usuario:<font color=red>" . $rowAnulado2 -> usuario . " </font>| 
					Peticion:<font color=red>" . $rowAnulado2 -> peticion . " </font>| fecha:<font color=red>" . $rowAnulado2 -> fecha . " </font>| ";
				}
			}

			if ($iAnulado == 1) {
				$etiqueta1 = "<s><font color=red>";
				$etiqueta2 = "</font></s>";
			}
			
			$oFil[$i] = array("1" => $html . $mAnulado, //
			"2" => "Ct.", //
			"3" => $etiqueta1 . $row -> documento_id . $etiqueta2, //
			"4" => $etiqueta1 . $row -> contrato_id . $etiqueta2, //
			"5" => $etiqueta1 . number_format($row -> monto_cuota, 2, ".", ",") . $etiqueta2, //
			"6" => $etiqueta1 . $row -> numero_cuotas . $etiqueta2, //
			"7" => $etiqueta1 . $this -> Tipo_Contrato($row -> forma_contrato) . $etiqueta2, //
			"8" => $etiqueta1 . $this -> Estatus_Creditos($row -> estatus) . $etiqueta2, //
			"9" => $etiqueta1 . $row -> fecha_solicitud . $etiqueta2, //
			"10" => $etiqueta1 . number_format($row -> monto_total, 2, ".", ",") . $etiqueta2, //
			"11" => $etiqueta1 . number_format($pagado, 2, ".", ",") . $etiqueta2, //
			"12" => $etiqueta1 . number_format($resta, 2, ".", ",") . $etiqueta2,
			"13" => $fac, //
			 );
		}

		$sLeyenda = '<br><center><h2>
			Monto Total de Factura ( ' . number_format($total_factura, 2, ".", ",") . ' ) <br>
			Monto Resta de Factura ( ' . number_format($total_resta, 2, ".", ",") . ' ) <br>';
		$sLeyenda .= "<br><center><a href='" . base_url() . "index.php/cooperativa/Estado_Factura/" . $fac . "' target='_blank'>
				<h2><font color='#1c94c4'>[ Estado De Cuenta Factura]</font></h2></a></center>";//.$tcontrol;

		$contratos = array("Cabezera" => $oCabezera, "Cuerpo" => $oFil, "Origen" => "json", "titulo" => "<br>", "titulo" => $tit.":<br>" . $mAnulado2, "leyenda" => $sLeyenda);
		return $contratos;
	}

	/*
	 * Funcion para crear los grid de los voucher dependiendo del pronto
	 */
	function Grid_Voucher($fac, $pr) {
		$queryV = "select * from t_lista_voucher where cid = '" . $fac . "' and pronto=" . $pr . " order by fecha";
		$ConsultaV = $this -> db -> query($queryV);
		$iCantidadV = $ConsultaV -> num_rows();
		$ConexionV = $ConsultaV -> result();

		$oCabezera2[1] = array("titulo" => "Voucher", "atributos" => "width:80px", "buscar" => 0);
		$oCabezera2[2] = array("titulo" => "Factura", "atributos" => "width:250px", "buscar" => 0);
		$oCabezera2[3] = array("titulo" => "Monto", "atributos" => "width:100px");
		$oCabezera2[4] = array("titulo" => "Fecha", "atributos" => "width:100px");
		$oCabezera2[5] = array("titulo" => "Estado", "tipo" => "enlace", "atributos" => "width:100px", "funcion" => "Pago_Voucher", "metodo" => 1, "parametro" => "1,2");
		$sTituloV = '';
		$txtEstatusV = '';
		$voucher1 = null;
		if ($iCantidadV > 0) {
			$i = 0;
			$totalV = 0;
			$pagadoV = 0;
			$sTituloV = "<br>Voucher<br>";
			foreach ($ConexionV as $rowV) {
				++$i;
				$totalV += $rowV -> monto;
				$txtEstatusV = $this -> Estatus_Voucher($rowV -> estatus, $pagadoV, $rowV -> monto);
				$oFil2[$i] = array("1" => $rowV -> ndep, "2" => $rowV -> cid, "3" => $rowV -> monto, "4" => $rowV -> fecha, "5" => $txtEstatusV);
			}

			$sTituloV = "<br>Voucher<br>";
			if ($pr == 1)
				$sTituloV = "<br>Voucher Pronto Pago<br>";
			$sLeyendaV = '<br><center><h2>
			Monto Total En Voucher ( ' . number_format($totalV, 2, ".", ",") . ' ) <br>
			Monto Pagado En Voucher ( ' . number_format($pagadoV, 2, ".", ",") . ' ) <br>
			Monto Resta En Voucher ( ' . number_format($totalV - $pagadoV, 2, ".", ",") . ' ) <br>';
			$voucher1 = array("Cabezera" => $oCabezera2, "Cuerpo" => $oFil2, "Origen" => "json", "titulo" => $sTituloV, "leyenda" => $sLeyendaV);
		} else {
			$voucher1 = 0;
		}
		return $voucher1;
	}

	function BContratos($banco, $nomina, $tipo, $empresa, $fecha, $tcontrato,$perio) {

		$query = "
		SELECT t_personas.documento_id AS cedula, CONCAT( primer_apellido, ' ', segundo_apellido, ' ', primer_nombre, ' ', segundo_nombre ) AS nombre, 
		t_clientes_creditos.contrato_id, cuenta_1, nomina_procedencia, cobrado_en, nomina_procedencia, fecha_inicio_cobro, t_clientes_creditos.monto_total, t_lista_cobros_u.monto,marca_consulta,monto_cuota,
		CASE
			WHEN t_estadoejecucion.oide=1
			THEN 'Creado (En Ventas)' 
			WHEN t_estadoejecucion.oide=2
			THEN 'Revision de Artefacto'
			WHEN t_estadoejecucion.oide=3
			THEN 'Por Aceptar en Ventas'
			WHEN t_estadoejecucion.oide=4
			THEN 'Por Depositar'
			WHEN t_estadoejecucion.oide=5
			THEN 'Buzon de Cobranza'
			WHEN t_estadoejecucion.oide=6
			THEN 'Aceptado Por Cobrar '
			WHEN t_estadoejecucion.oide=7
			THEN 'Cobrando Actualmente '
			WHEN t_estadoejecucion.oide=0
			THEN 'Rechado en Ventas '
		END as Estado,
		CASE
			WHEN t_clientes_creditos.estatus = 3
			THEN 'Nulo' 
			WHEN t_lista_cobros_u.monto IS NULL
			THEN 'Revisar'
			WHEN t_lista_cobros_u.monto > t_clientes_creditos.monto_total
			THEN 'Excede'
			WHEN t_lista_cobros_u.monto = t_clientes_creditos.monto_total
			THEN 'Pagado'
			WHEN t_lista_cobros_u.monto < t_clientes_creditos.monto_total
			THEN 'Pendiente' 
		END AS Estatus
		FROM t_clientes_creditos
		LEFT JOIN t_estadoejecucion ON t_estadoejecucion.oidc = t_clientes_creditos.contrato_id
		LEFT JOIN t_personas ON t_personas.documento_id = t_clientes_creditos.documento_id
		LEFT JOIN t_lista_cobros_u ON t_clientes_creditos.contrato_id = t_lista_cobros_u.contrato_id";
		$donde = " WHERE t_clientes_creditos.forma_contrato=$tipo "; //AND t_lista_cobros_u.monto > t_clientes_creditos.monto_total ";
		if ($banco != "TODOS")
			$donde .= " AND cobrado_en='$banco' ";
		if ($nomina != "TODOS")
			$donde .= " AND nomina_procedencia='$nomina'";
		if ($empresa != 9)
			$donde .= " AND empresa=$empresa";
		if ($perio != 99)
			$donde .= " AND periocidad=$perio";
		if ($fecha != '')
			$donde .= " AND fecha_inicio_cobro like '$fecha%'";
			//$donde .= " AND fecha_inicio_cobro <= '$fecha'"; //Reclamando todos menor o igual a la fecha
		if ($tcontrato == 6) {
			$donde .= " AND marca_consulta=$tcontrato ";
		} else {
			$donde .= " AND marca_consulta!=6 ";
		}
		$query .= $donde . " order by t_clientes_creditos.contrato_id";
		//echo $query;
		$oCabezera[1] = array("titulo" => "Cedula", "buscar" => 1);
		$oCabezera[2] = array("titulo" => "Nombre");
		$oCabezera[3] = array("titulo" => "Contrato");
		$oCabezera[4] = array("titulo" => "Cuenta");
		$oCabezera[5] = array("titulo" => "Nomina");
		$oCabezera[6] = array("titulo" => "Cobrado Por");
		$oCabezera[7] = array("titulo" => "Fecha D.");
		$oCabezera[8] = array("titulo" => "Monto T.","total"=>1);
		$oCabezera[9] = array("titulo" => "Monto","total"=>1);
		$oCabezera[10] = array("titulo" => "Cuota","total"=>1);
		$oCabezera[11] = array("titulo" => "Estatus", "buscar" => 1);
		$oCabezera[12] = array("titulo" => "Estado", "buscar" => 1);
		$oCabezera[13] = array("titulo" => "TC");

		$consulta = $this -> db -> query($query);

		$resultado = $consulta -> result();
		$Cuerpo = array();
		$i = 0;
		foreach ($resultado as $row) {
			$i++;
			if (is_null($row -> monto)) {
				$monto = 0;
			} else {
				$monto = $row -> monto;
			}
			$marca = "D";
			if ($row -> marca_consulta == 6) {
				$marca = "V";
			}
			$Cuerpo[$i] = array("1" => $row -> cedula, "2" => $row -> nombre, "3" => $row -> contrato_id, "4" => $row -> cuenta_1, 
			"5" => $row -> nomina_procedencia, "6" => $row -> cobrado_en, "7" => $row -> fecha_inicio_cobro, "8" => $row -> monto_total, 
			"9" => $monto, "10" => $row -> monto_cuota, "11" => $row -> Estatus, "12" => $row -> Estado, "13" => $marca);
		}
		$objeto = array("Cabezera" => $oCabezera, "Cuerpo" => $Cuerpo, "Origen" => "json", "Paginador" => 50,"sql"=>$query );
		return json_encode($objeto);
	}

	function Listar_Contado($arr = null) {
		if ($arr != null) {
			if ($arr['ubicacion'] == 'TODOS')
				$ubi = '';
			else
				$ubi = " AND ubicacion ='" . $arr['ubicacion'] . "'";
			$consulta = "SELECT * FROM t_venta_contado WHERE fecha BETWEEN '" . $arr['desde'] . "' AND '" . $arr['hasta'] . "' " . $ubi;
		} else {
			$consulta = "SELECT * FROM t_venta_contado";
		}
		$busqueda = $this -> db -> query($consulta);

		$cant = $busqueda -> num_rows();
		if ($cant > 0) {
			$Object = array("Cabezera" => $busqueda -> list_fields(), "Cuerpo" => $busqueda -> result(), "Origen" => "Mysql", "msj" => 1);
		} else {
			$Object = array("msj" => 0);
		}
		return json_encode($Object);
	}
	
	function Listar_Ncliente($arr = null) {
		if ($arr != null) {
			if ($arr['ubica'] == 'TODOS')
				$ubi = '';
			else
				$ubi = " codigo_n ='" . $arr['ubica'] . "'";
				$consulta = "select t_clientes_creditos.documento_id,numero_factura,fecha_solicitud,codigo_n,a.fact
					from t_clientes_creditos 
					left join (select documento_id,numero_factura as fact
						from t_clientes_creditos 
						group by numero_factura)as a on a.documento_id=t_clientes_creditos.documento_id
					where fecha_solicitud like '%".$arr['ano']."-".$arr['mes']."%' ".$ubi."
					group by numero_factura
					order by documento_id";
		}
		$busqueda = $this -> db -> query($consulta);
		$cant = $busqueda -> num_rows();
		if ($cant > 0) {
			$oCabezera[1] = array("titulo" => "Cedula");
			$oCabezera[2] = array("titulo" => "Factura");
			$oCabezera[3] = array("titulo" => "Solicitud");
			$oCabezera[4] = array("titulo" => "Ubicacion");
			$resultado = $busqueda -> result();
			$Cuerpo = array();
			$i = 0;
			foreach($resultado as $fila){
				if($fila -> numero_factura == $fila -> fact){
					$i++;
					$cuerpo[$i] = array("1" => $fila -> documento_id,"2" => $fila -> numero_factura,"3" => $fila -> fecha_solicitud,"4" => $fila -> codigo_n);
				}
				
			}
			$Object = array("Cabezera" => $oCabezera, "Cuerpo" => $cuerpo, "Origen" => "json", "msj" => 1);
		} else {
			$Object = array("msj" => 0);
		}
		return json_encode($Object);
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
		}
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

	/**
	 * Crear Codigo Aleatorio unico
	 */
	function _setCodigoSRand($sCod) {
		$sConsulta = "SELECT cod FROM t_srand WHERE cod='" . $sCod . "' LIMIT 1";
		$rsC = $this -> db -> query($sConsulta);
		if ($rsC -> num_rows() != 0) {
			$aux = rand(1, 99999);
			$C = $this -> _setCodigoSRand($aux);
			return $C;
		} else {
			$this -> db -> query('INSERT INTO t_srand (oid, cod) VALUES (NULL, \'' . $sCod . '\');');
			return $sCod;
		}
	}

	/**
	 * Completar con ceros a la izquierda...
	 *
	 */
	public function Completar($strCadena = '', $intLongitud = '') {
		$strContenido = '';
		$strAux = '';
		$intLen = strlen($strCadena);
		if ($intLen != $intLongitud) {
			$intCount = $intLongitud - $intLen;
			for ($i = 0; $i < $intCount; $i++) {
				$strAux .= '0';
			}
			$strContenido = $strAux . $strCadena;
		}
		return $strContenido;
	}
	
	/*
	 * funciones para los reportes generales
	 */
	function RFactura_General($arr) {
		$lin = '';
		$estatus = '';
		$condicion = '';
		if($arr['banco']!='0')$lin = " AND t_clientes_creditos.cobrado_en='".$arr['banco']."' "; 
		if($arr['estatus']=='1')$estatus = " AND (pagado+IFNULL(pg_voucher,0)) = 0";
		switch ($arr['condicion']){
			case 1:
				$condicion = ' AND fecha_inicio_cobro < "'.date("Y-m-d").'"';
				break;
			case 2:
				$condicion = ' AND fecha_inicio_cobro >"'. date("Y-m-d").'"';
				break;
			default:
				$condicion = '';
				break;
		}
	
		$query = "SELECT t_personas.documento_id AS cedula, CONCAT( primer_apellido, ' ', segundo_apellido, ' ', primer_nombre, ' ', segundo_nombre ) AS nombre, 
			tbl.numero_factura AS factura, fecha_solicitud AS solicitud, fecha_inicio_cobro,cobrado_en AS banco, 
			b.mt AS total, 
			b.mt2 AS total2,
			pagado AS pagado_d,IFNULL(pg_voucher,0)AS pagado_v,(pagado+IFNULL(pg_voucher,0)) AS total_pagado,
			b.mt2 - pagado - IFNULL(pg_voucher,0) AS resta, 
			IF(b.mt2=0,'N','A') AS activo,count(*),telefono
		FROM t_clientes_creditos
		JOIN t_personas ON t_personas.documento_id = t_clientes_creditos.documento_id
		JOIN(
			SELECT numero_factura,sum(monto_total)as mt,sum(cantidad)as mt2 
				FROM t_clientes_creditos 
				WHERE cantidad>0 ".$lin." 
				GROUP BY numero_factura
				)AS b on b.numero_factura = t_clientes_creditos.numero_factura 
		JOIN(
			SELECT t_clientes_creditos.numero_factura, monto_total, IFNULL( SUM( monto ) , 0 ) AS pagado
				FROM t_clientes_creditos
				LEFT JOIN t_lista_cobros_u ON t_clientes_creditos.contrato_id = t_lista_cobros_u.contrato_id 
				WHERE cantidad>0 ".$lin."
				GROUP BY numero_factura
				)AS tbl ON tbl.numero_factura=t_clientes_creditos.numero_factura
		LEFT JOIN(
			SELECT t_clientes_creditos.numero_factura, monto_total as mtov, 
				IFNULL(tot_voucher,0) as pg_voucher
				FROM t_clientes_creditos
				LEFT JOIN(
					SELECT cid,sum(monto) AS tot_voucher 
						FROM t_lista_voucher 
						WHERE t_lista_voucher.estatus = 3 OR t_lista_voucher.estatus = 1 OR t_lista_voucher.estatus = 6
						GROUP BY cid
						)AS auxt ON auxt.cid = t_clientes_creditos.numero_factura
						WHERE t_clientes_creditos.marca_consulta = 6 and cantidad>0 ".$lin."
				GROUP BY numero_factura
				) AS tblV ON tblV.numero_factura=t_clientes_creditos.numero_factura
		WHERE b.mt2>0 ".$lin.$estatus.$condicion." GROUP BY t_clientes_creditos.numero_factura ORDER BY fecha_solicitud, total_pagado";
		
		$oCabezera[1] = array("titulo" => "Cedula", "atributos" => "width:80px", "buscar" => 1);
		$oCabezera[2] = array("titulo" => "Nombre");
		$oCabezera[3] = array("titulo" => "Factura", "atributos" => "width:50px", "buscar" => 1);
		$oCabezera[4] = array("titulo" => "Solicitud", "atributos" => "width:50px", "buscar" => 0);
		$oCabezera[5] = array("titulo" => "Banco", "atributos" => "width:50px", "buscar" => 0);
		$oCabezera[6] = array("titulo" => "Total", "atributos" => "width:90px;text-align:right","total"=>1);
		$oCabezera[7] = array("titulo" => "Pagado", "atributos" => "width:90px;text-align:right","total"=>1);
		$oCabezera[8] = array("titulo" => "Resta", "atributos" => "width:90px;text-align:right","total"=>1);
		//$oCabezera[9] = array("titulo" => "Estatus", "atributos" => "width:50px");
		//return $query;
		$respuesta = $this -> db -> query($query);
		$oFil = array();
		if ($respuesta -> num_rows() > 0) {
			$i = 0;
	
			foreach ($respuesta -> result() as $row) {
				$eti2 = '';
				$eti = '';
				if ($row -> activo == 'N') {
					$eti = '<font color=red>';
					$eti2 = '</font>';
				}
				$i++;
				$oFil[$i] = array("1" => $row -> cedula, "2" => $row -> nombre, "3" => $row -> factura, "4" => $row -> solicitud, "5" => $row -> banco, "6" => $eti . $row -> total2 . $eti2, "7" => $eti . $row -> total_pagado . $eti2, "8" => $eti . $row -> resta . $eti2,
						//"9" => $eti.$row -> activo.$eti2
				);
			}
			$Object = array("Cabezera" => $oCabezera, "Cuerpo" => $oFil, "Origen" => "json", "msj" => 1,"Paginador"=>100);
		} else {
			$Object = array("msj" => 0);
		}
		return json_encode($Object);
	}
	
	function RContrato_General($arr) {
		$lin = '';
		$condicion = '';
		$empresa = '';
		$peri = '';
		if($arr['peri'] != ""){
			$peri = ' AND t_clientes_creditos.periocidad = '.$arr['peri']. ' ';
		}
		if($arr['banco']!='0')$lin = " AND t_clientes_creditos.cobrado_en='".$arr['banco']."' ";
		switch ($arr['condicion']){
			case 1:
				$condicion = ' AND fecha_inicio_cobro < "'.date("Y-m-d").'"';
				break;
			case 2:
				$condicion = ' AND fecha_inicio_cobro >"'. date("Y-m-d").'"';
				break;
			default:
				$condicion = '';
				break;
		}
		switch ($arr['empr']){
			case 0:
				$empresa = ' AND empresa = 0';
				break;
			case 1:
				$empresa = ' AND empresa = 1';
				break;
			default:
				$empresa = '';
				break;
		}
		
		
		$query = "
		SELECT t_personas.documento_id AS cedula, CONCAT( primer_apellido, ' ', segundo_apellido, ' ', primer_nombre, ' ', segundo_nombre ) AS nombre,
		t_clientes_creditos.contrato_id, cuenta_1, nomina_procedencia, cobrado_en, nomina_procedencia, fecha_inicio_cobro, t_clientes_creditos.monto_total, t_lista_cobros_u.monto,marca_consulta,
		(t_clientes_creditos.monto_total - t_lista_cobros_u.monto) as resta,
		CASE
		WHEN t_estadoejecucion.oide=1
		THEN 'Creado (En Ventas)'
		WHEN t_estadoejecucion.oide=2
		THEN 'Revision de Artefacto'
		WHEN t_estadoejecucion.oide=3
		THEN 'Por Aceptar en Ventas'
		WHEN t_estadoejecucion.oide=4
		THEN 'Por Depositar'
		WHEN t_estadoejecucion.oide=5
		THEN 'Buzon de Cobranza'
		WHEN t_estadoejecucion.oide=6
		THEN 'Aceptado Por Cobrar '
		WHEN t_estadoejecucion.oide=7
		THEN 'Cobrando Actualmente '
		WHEN t_estadoejecucion.oide=0
		THEN 'Rechado en Ventas '
		END as Estado,
		CASE
		WHEN t_clientes_creditos.cantidad = 0
		THEN 'Nulo'
		WHEN t_lista_cobros_u.monto IS NULL
		THEN 'Sin Pago'
		WHEN t_lista_cobros_u.monto > t_clientes_creditos.monto_total
		THEN 'Excede'
		WHEN t_lista_cobros_u.monto = t_clientes_creditos.monto_total
		THEN 'Pagado'
		WHEN t_lista_cobros_u.monto < t_clientes_creditos.monto_total
		THEN 'Pendiente'
		END AS Estatus
		FROM t_clientes_creditos
		LEFT JOIN t_estadoejecucion ON t_estadoejecucion.oidc = t_clientes_creditos.contrato_id
		LEFT JOIN t_personas ON t_personas.documento_id = t_clientes_creditos.documento_id
		LEFT JOIN t_lista_cobros_u ON t_clientes_creditos.contrato_id = t_lista_cobros_u.contrato_id";
		$donde = " WHERE t_clientes_creditos.marca_consulta != 6 AND t_clientes_creditos.cantidad!=0 AND t_clientes_creditos.forma_contrato=".$arr['tipo'].$lin.$empresa.$condicion.$peri;
		
		$query .= $donde . " order by t_clientes_creditos.contrato_id";
		//return $query;
		$oCabezera[1] = array("titulo" => "Cedula", "buscar" => 1,"atributos" => "width:15px;");
		$oCabezera[2] = array("titulo" => "Nombre","atributos" => "width:90px;");
		$oCabezera[3] = array("titulo" => "Contrato", "buscar" => 1,"atributos" => "width:15px;");
		$oCabezera[4] = array("titulo" => "Cuenta","atributos" => "width:60px;");
		$oCabezera[5] = array("titulo" => "Nomina");
		$oCabezera[6] = array("titulo" => "Cobrado Por", "buscar" => 1,"atributos" => "width:150px;");
		$oCabezera[7] = array("titulo" => "Fecha Inicio Des.","atributos" => "width:30px;");
		$oCabezera[8] = array("titulo" => "Monto T.","total"=>1,"atributos" => "width:90px;text-align:right");
		$oCabezera[9] = array("titulo" => "Pagado","total"=>1,"atributos" => "width:90px;text-align:right");
		$oCabezera[10] = array("titulo" => "Resta","total"=>1,"atributos" => "width:90px;text-align:right");
		$oCabezera[11] = array("titulo" => "Estatus", "buscar" => 1,"atributos" => "width:50px;");
		$oCabezera[12] = array("titulo" => "Estado", "buscar" => 1,"atributos" => "width:50px;");
		$oCabezera[13] = array("titulo" => "TC","atributos" => "width:10px;");
	
		$consulta = $this -> db -> query($query);
	
		$resultado = $consulta -> result();
		$Cuerpo = array();
		$i = 0;
		foreach ($resultado as $row) {
			$i++;
			if (is_null($row -> monto)) {
				$monto = 0;
			} else {
				$monto = $row -> monto;
			}
			$marca = "D";
			if ($row -> marca_consulta == 6) {
				$marca = "V";
			}
			$Cuerpo[$i] = array("1" => $row -> cedula, "2" => $row -> nombre, "3" => $row -> contrato_id, "4" => $row -> cuenta_1, 
								"5" => '|'. $row -> nomina_procedencia, "6" => $row -> cobrado_en, "7" => $row -> fecha_inicio_cobro, 
								"8" => $row -> monto_total, "9" => $monto,"10" => $row -> resta, "11" => '|'.$row -> Estatus, "12" => $row -> Estado, "13" => $marca);
		}
		$objeto = array("Cabezera" => $oCabezera, "Cuerpo" => $Cuerpo, "Origen" => "json", "Paginador" => 50, );
		return json_encode($objeto);
	}
	
	/**
	 * Reportes Especiales - Nuevas funcionalidades
	 */
	
	// Función principal para generar reportes especiales
	public function Reporte_Especial($filtros) {
		$where_conditions = array();
		$join_conditions = "";
		// Construir condiciones WHERE basadas en los filtros
		if (!empty($filtros['banco'])) {
			$where_conditions[] = "t_clientes_creditos.cobrado_en = '" . $this->db->escape_str($filtros['banco']) . "'";
		}
		// Construir la consulta base
		$query = "
			SELECT 
				t_personas.documento_id as cedula,
				CONCAT(t_personas.primer_apellido, ' ', t_personas.segundo_apellido, ' ', t_personas.primer_nombre, ' ', t_personas.segundo_nombre) as nombre,
				t_clientes_creditos.contrato_id,
				t_clientes_creditos.numero_factura,
				t_clientes_creditos.cobrado_en as banco,
				t_clientes_creditos.nomina_procedencia as nomina,
				t_clientes_creditos.monto_total,
				COALESCE(pagos_contrato.pagado, 0) + COALESCE(vouchers_contrato.pg_voucher, 0) as monto_pagado,
				(t_clientes_creditos.monto_total - (COALESCE(pagos_contrato.pagado, 0) + COALESCE(vouchers_contrato.pg_voucher, 0))) as saldo,
				CASE 
					WHEN t_clientes_creditos.forma_contrato = 0 THEN 'Único'
					WHEN t_clientes_creditos.forma_contrato = 1 THEN 'Aguinaldo'
					WHEN t_clientes_creditos.forma_contrato = 2 THEN 'Vacaciones'
					WHEN t_clientes_creditos.forma_contrato = 3 THEN 'Extra'
					WHEN t_clientes_creditos.forma_contrato = 4 THEN 'Único Extra'
					WHEN t_clientes_creditos.forma_contrato = 5 THEN 'Especial Extra'
					ELSE 'Desconocido'
				END as tipo_contrato,
				t_clientes_creditos.fecha_solicitud,
				t_clientes_creditos.fecha_inicio_cobro,
				DATE_ADD(t_clientes_creditos.fecha_inicio_cobro, INTERVAL t_clientes_creditos.numero_cuotas MONTH) as fecha_finalizacion_cobro,
				t_clientes_creditos.numero_cuotas,
				t_clientes_creditos.monto_cuota,
				t_clientes_creditos.estatus
			FROM t_clientes_creditos
			LEFT JOIN t_personas ON t_personas.documento_id = t_clientes_creditos.documento_id
			/* Pagos por contrato (domiciliación) */
			LEFT JOIN (
				SELECT credito_id, SUM(monto) AS pagado
				FROM t_lista_cobros
				GROUP BY credito_id
			) AS pagos_contrato ON pagos_contrato.credito_id = t_clientes_creditos.contrato_id
			/* Pagos por voucher/transferencia asociados a la factura */
			LEFT JOIN (
				SELECT cid, SUM(monto) AS pg_voucher
				FROM t_lista_voucher
				WHERE (estatus IN (1,3,6))
				GROUP BY cid
			) AS vouchers_contrato ON vouchers_contrato.cid = t_clientes_creditos.numero_factura
		";
		
		// Agregar condiciones WHERE
		if (!empty($where_conditions)) {
			$query .= " WHERE " . implode(" AND ", $where_conditions);
		}
		
		// Agregar condición para excluir registros nulos y cédulas específicas
		$exclude_conditions = array(
			"t_clientes_creditos.marca_consulta != 6",
			"t_clientes_creditos.cantidad != 0"
		);
		
		// Cédulas a excluir del reporte
		$exclude_cedulas = array('19997832');
		
		if (!empty($exclude_cedulas)) {
			$exclude_conditions[] = "t_personas.documento_id NOT IN ('" . implode("', '", $exclude_cedulas) . "')";
		}
		
		if (!empty($where_conditions)) {
			$query .= " AND " . implode(" AND ", $exclude_conditions);
		} else {
			$query .= " WHERE " . implode(" AND ", $exclude_conditions);
		}
		
		$query .= " ORDER BY t_clientes_creditos.contrato_id";
		
		// Ejecutar consulta
		$result = $this->db->query($query);
		$registros = array();
		
		foreach ($result->result() as $row) {
			// Obtener información de periodicidad
			$periodicidad_query = $this->db->query("SELECT periocidad FROM t_clientes_creditos WHERE contrato_id = ?", array($row->contrato_id));
			$periodicidad = '4'; // Mensual por defecto
			if ($periodicidad_query && $periodicidad_query->num_rows() > 0) {
				$periodicidad = $periodicidad_query->row()->periocidad;
			}
			
			// Calcular cuotas vencidas y monto para estar al día
			$info_cuotas = $this->calcularCuotasVencidas($row->contrato_id, $row->fecha_inicio_cobro, $row->numero_cuotas, $periodicidad, $row->monto_cuota, $row->monto_pagado);
			
			// Determinar estado basado en el plan de pagos
			$estado = $this->determinarEstadoContrato($row->contrato_id, $row->monto_total, $row->monto_pagado, $row->fecha_inicio_cobro, $row->numero_cuotas, $periodicidad);
			
			// Aplicar filtro de estado si está especificado
			if (!empty($filtros['estado']) && $estado != $filtros['estado']) {
				continue;
			}
			
			$registros[] = array(
				'cedula' => $row->cedula,
				'nombre' => $row->nombre,
				'contrato_id' => $row->contrato_id,
				'banco' => $row->banco,
				'nomina' => $row->nomina,
				'monto_total' => number_format($row->monto_total, 2, ',', '.'),
				'monto_pagado' => number_format($row->monto_pagado, 2, ',', '.'),
				'saldo' => number_format($row->saldo, 2, ',', '.'),
				'cuotas_vencidas' => $info_cuotas['cuotas_vencidas'],
				'monto_para_al_dia' => number_format($info_cuotas['monto_para_al_dia'], 2, ',', '.'),
				'tipo_contrato' => $row->tipo_contrato,
				'estado' => $estado,
				'fecha_solicitud' => $row->fecha_solicitud,
				'fecha_inicio_cobro' => $row->fecha_inicio_cobro,
				'fecha_finalizacion_cobro' => $row->fecha_finalizacion_cobro,
				'numero_cuotas' => $row->numero_cuotas,
				'monto_cuota' => $row->monto_cuota
			);
		}
		
		return array(
			'total' => count($registros),
			'registros' => $registros
		);
	}
	
	/**
	 * Generar plan de cobro basado en periodicidad (copiado de MCobranza)
	 */
	private function generarPlanCobro($fecha_inicio, $numero_cuotas, $periodicidad) {
		$plan = array();
		$fecha_base = new DateTime($fecha_inicio);
		
		// Determinar la base de tiempo según la periodicidad
		switch($periodicidad) {
			case '0': $base_mes = 1/4; break; // Semanal
			case '1': 
			case '2': 
			case '3': $base_mes = 1/2; break; // Quincenal
			case '4': 
			case '8': 
			case '9': $base_mes = 1; break; // Mensual
			case '5': $base_mes = 3; break; // Trimestral
			case '6': $base_mes = 6; break; // Semestral
			case '7': $base_mes = 12; break; // Anual
			default: $base_mes = 1; break; // Mensual por defecto
		}
		
		for ($i = 1; $i <= $numero_cuotas; $i++) {
			$tiempo = ($i - 1) * $base_mes;
			$fecha_cuota = clone $fecha_base;
			
			// Calcular la fecha de la cuota
			if ($tiempo >= 1) {
				$meses = floor($tiempo);
				$dias_extra = ($tiempo - $meses) * 30;
				$fecha_cuota->add(new DateInterval('P' . $meses . 'M'));
				if ($dias_extra > 0) {
					$fecha_cuota->add(new DateInterval('P' . floor($dias_extra) . 'D'));
				}
			} else {
				$dias = $tiempo * 30;
				$fecha_cuota->add(new DateInterval('P' . floor($dias) . 'D'));
			}
			
			$plan[] = array(
				'cuota' => $i,
				'fecha' => $fecha_cuota->format('Y-m-d')
			);
		}
		
		return $plan;
	}
	
	/**
	 * Calcular cuotas vencidas y monto para estar al día
	 */
	private function calcularCuotasVencidas($contrato_id, $fecha_inicio, $numero_cuotas, $periodicidad, $monto_cuota, $monto_pagado) {
		$fecha_actual = date('Y-m-d');
		$plan_cobro = $this->generarPlanCobro($fecha_inicio, $numero_cuotas, $periodicidad);
		
		$cuotas_vencidas = 0;
		$monto_para_al_dia = 0;
		$monto_restante = $monto_pagado;
		
		foreach ($plan_cobro as $cuota) {
			$fecha_vencimiento = $cuota['fecha'];
			
			// Si la cuota ya venció
			if ($fecha_vencimiento < $fecha_actual) {
				// Verificar si está pagada completamente
				if ($monto_restante >= $monto_cuota) {
					// Cuota pagada completamente
					$monto_restante -= $monto_cuota;
				} else {
					// Cuota vencida (parcialmente pagada o no pagada)
					$cuotas_vencidas++;
					
					// Calcular cuánto falta por pagar de esta cuota
					$monto_faltante_cuota = $monto_cuota - $monto_restante;
					$monto_para_al_dia += $monto_faltante_cuota;
					
					// El monto restante se agota en esta cuota
					$monto_restante = 0;
				}
			} else {
				// Cuota futura, no cuenta como vencida
				break;
			}
		}
		
		return array(
			'cuotas_vencidas' => $cuotas_vencidas,
			'monto_para_al_dia' => $monto_para_al_dia
		);
	}
	
	// Función para determinar el estado del contrato basado en el plan de pagos
	private function determinarEstadoContrato($contrato_id, $monto_total, $monto_pagado, $fecha_inicio, $numero_cuotas) {
		// Si está completamente pagado
		if ($monto_pagado >= $monto_total) {
			return 'terminados';
		}
		
		// Calcular cuotas esperadas hasta la fecha actual
		$fecha_actual = date('Y-m-d');
		$fecha_inicio_obj = new DateTime($fecha_inicio);
		$fecha_actual_obj = new DateTime($fecha_actual);
		
		// Calcular meses transcurridos
		$meses_transcurridos = $fecha_inicio_obj->diff($fecha_actual_obj)->m + ($fecha_inicio_obj->diff($fecha_actual_obj)->y * 12);
		
		// Calcular monto esperado hasta la fecha
		$monto_esperado = min($meses_transcurridos * ($monto_total / $numero_cuotas), $monto_total);
		
		// Si el monto pagado es menor al esperado, está moroso
		if ($monto_pagado < $monto_esperado) {
			return 'morosos';
		}
		
		// Si está al día
		return 'al_dia';
	}
	
	// Función para exportar reportes especiales
	public function Exportar_Reporte_Especial($filtros) {
		$resultado = $this->Reporte_Especial($filtros);
		
		// Configurar headers para descarga de Excel
		header('Content-Type: application/vnd.ms-excel; charset=utf-8');
		header('Content-Disposition: attachment; filename="reporte_especial_' . date('Y-m-d_H-i-s') . '.xls"');
		header('Pragma: no-cache');
		header('Expires: 0');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		
		// Iniciar salida con BOM para UTF-8
		echo "\xEF\xBB\xBF";
		
		// Crear contenido Excel con mejor formato
		echo "<html xmlns:o='urn:schemas-microsoft-com:office:office' xmlns:x='urn:schemas-microsoft-com:office:excel' xmlns='http://www.w3.org/TR/REC-html40'>";
		echo "<head>";
		echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
		echo "<!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>Reporte Especial</x:Name><x:WorksheetOptions><x:DefaultRowHeight>285</x:DefaultRowHeight></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]-->";
		echo "</head>";
		echo "<body>";
		
		// Información del reporte
		echo "<h2>REPORTE ESPECIAL - SISTEMA DE CONTROL DE PRÉSTAMOS</h2>";
		echo "<p><strong>Fecha de generación:</strong> " . date('d/m/Y H:i:s') . "</p>";
		echo "<p><strong>Total de registros:</strong> " . $resultado['total'] . "</p>";
		echo "<br>";
		
		// Crear tabla con formato Excel
		echo "<table border='1' cellpadding='5' cellspacing='0' style='border-collapse: collapse; width: 100%;'>";
		
		// Encabezados con formato
		echo "<tr style='background-color: #4CAF50; color: white; font-weight: bold;'>";
		echo "<th style='border: 1px solid #000; padding: 8px;'>Cédula</th>";
		echo "<th style='border: 1px solid #000; padding: 8px;'>Nombre</th>";
		echo "<th style='border: 1px solid #000; padding: 8px;'>Contrato</th>";
		echo "<th style='border: 1px solid #000; padding: 8px;'>Banco</th>";
		echo "<th style='border: 1px solid #000; padding: 8px;'>Nómina</th>";
		echo "<th style='border: 1px solid #000; padding: 8px;'>Estado</th>";
		echo "<th style='border: 1px solid #000; padding: 8px;'>Monto Total</th>";
		echo "<th style='border: 1px solid #000; padding: 8px;'>Monto Pagado</th>";
		echo "<th style='border: 1px solid #000; padding: 8px;'>Saldo Total</th>";
		echo "<th style='border: 1px solid #000; padding: 8px;'>Cuotas Vencidas</th>";
		echo "<th style='border: 1px solid #000; padding: 8px;'>Monto para Al Día</th>";
		echo "<th style='border: 1px solid #000; padding: 8px;'>Tipo Contrato</th>";
		echo "<th style='border: 1px solid #000; padding: 8px;'>Fecha Solicitud</th>";
		echo "<th style='border: 1px solid #000; padding: 8px;'>Fecha Inicio Cobro</th>";
		echo "<th style='border: 1px solid #000; padding: 8px;'>Fecha Finalización</th>";
		echo "<th style='border: 1px solid #000; padding: 8px;'>Número Cuotas</th>";
		echo "<th style='border: 1px solid #000; padding: 8px;'>Monto Cuota</th>";
		echo "</tr>";
		
		// Datos
		foreach ($resultado['registros'] as $index => $registro) {
			$bgColor = ($index % 2 == 0) ? '#f9f9f9' : '#ffffff';
			$estadoColor = '';
			
			// Colorear según el estado
			switch(strtolower($registro['estado'])) {
				case 'morosos':
					$estadoColor = 'color: red; font-weight: bold;';
					break;
				case 'al_dia':
					$estadoColor = 'color: green; font-weight: bold;';
					break;
				case 'terminados':
					$estadoColor = 'color: blue; font-weight: bold;';
					break;
			}
			
			echo "<tr style='background-color: " . $bgColor . ";'>";
			echo "<td style='border: 1px solid #000; padding: 5px;'>" . htmlspecialchars($registro['cedula']) . "</td>";
			echo "<td style='border: 1px solid #000; padding: 5px;'>" . htmlspecialchars($registro['nombre']) . "</td>";
			echo "<td style='border: 1px solid #000; padding: 5px;'>" . htmlspecialchars($registro['contrato_id']) . "</td>";
			echo "<td style='border: 1px solid #000; padding: 5px;'>" . htmlspecialchars($registro['banco']) . "</td>";
			echo "<td style='border: 1px solid #000; padding: 5px;'>" . htmlspecialchars($registro['nomina']) . "</td>";
			echo "<td style='border: 1px solid #000; padding: 5px; " . $estadoColor . "'>" . htmlspecialchars(ucfirst($registro['estado'])) . "</td>";
			echo "<td style='border: 1px solid #000; padding: 5px; text-align: right;'>" . $registro['monto_total'] . "</td>";
			echo "<td style='border: 1px solid #000; padding: 5px; text-align: right;'>" . $registro['monto_pagado'] . "</td>";
			echo "<td style='border: 1px solid #000; padding: 5px; text-align: right;'>" . $registro['saldo'] . "</td>";
			echo "<td style='border: 1px solid #000; padding: 5px; text-align: center;'>" . $registro['cuotas_vencidas'] . "</td>";
			echo "<td style='border: 1px solid #000; padding: 5px; text-align: right; color: red; font-weight: bold;'>" . $registro['monto_para_al_dia'] . "</td>";
			echo "<td style='border: 1px solid #000; padding: 5px;'>" . htmlspecialchars($registro['tipo_contrato']) . "</td>";
			echo "<td style='border: 1px solid #000; padding: 5px;'>" . $registro['fecha_solicitud'] . "</td>";
			echo "<td style='border: 1px solid #000; padding: 5px;'>" . $registro['fecha_inicio_cobro'] . "</td>";
			echo "<td style='border: 1px solid #000; padding: 5px;'>" . $registro['fecha_finalizacion_cobro'] . "</td>";
			echo "<td style='border: 1px solid #000; padding: 5px; text-align: center;'>" . $registro['numero_cuotas'] . "</td>";
			echo "<td style='border: 1px solid #000; padding: 5px; text-align: right;'>" . $registro['monto_cuota'] . "</td>";
			echo "</tr>";
		}
		
		echo "</table>";
		echo "</body>";
		echo "</html>";
	}

}
?>
