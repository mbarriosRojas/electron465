<?php
/**
 *  @author Carlos Enrique Penaa Albarran
 *  @package SaGem.system.application.model
 *  @version 1.0.0
 */
class CCreditos extends Model {

	/**
	 * Documento de Identificacion
	 * @var integer
	 */
	var $documento_id;

	/**
	 * Numero de Contrato
	 * @var string
	 */
	var $contrato_id;

	/**
	 * Fecha de Solicitud
	 * @var date
	 */
	var $fecha_solicitud;

	/**
	 * Inicio de Cobro
	 * @var date
	 */
	var $fecha_inicio_cobro;

	/**
	 * Numero de Contrato
	 * @var string
	 */
	var $motivo;

	/**
	 * Numero de Contrato
	 * @var string
	 */
	var $condicion;

	/**
	 * Numero de Contrato
	 * @var string
	 */
	var $num_operacion;

	/**
	 * Inicio de Cobro
	 * @var date
	 */
	var $fecha_operacion;

	/**
	 * Numero de Contrato
	 * @var double
	 */
	var $cantidad;

	/**
	 * Numero de Contrato
	 * @var double
	 */
	var $monto_total;

	/**
	 * Numero de Contrato
	 * @var integer
	 */
	var $numero_cuotas;

	/**
	 * Numero de Contrato
	 * @var integer
	 */
	var $periocidad;

	/**
	 * Numero de Contrato
	 * @var string
	 */
	var $nomina_procedencia;

	/**
	 * Numero de Contrato
	 * @var double
	 */
	var $monto_cuota;

	/**
	 * Numero de Contrato
	 * @var integer
	 */
	var $forma_contrato;

	/**
	 * Numero de Contrato
	 * @var string
	 */
	var $empresa;

	/**
	 * Numero de Contrato
	 * @var string
	 */
	var $cobrado_en;

	/**
	 * Numero de Factura
	 * @var string
	 */
	var $numero_factura;

	/**
	 * Numero de Factura
	 * @var double
	 */
	var $monto_operacion;

	/**
	 * Estatus
	 * @var integer
	 */
	var $estatus = 1;

	/**
	 * Lista de Seriales
	 * @var string
	 */
	var $serial;

	/**
	 * Lista de Seriales
	 * @var string
	 */
	var $estado_verificado = 0;


	/************************************************/

	/**
	 * Codigo De La Nomina
	 *
	 * var string
	 */
	var $codigo_n;

	/**
	 * Nuevo Codigo Del Empleado
	 *
	 * @var string
	 */
	var $codigo_n_a = "";

	/**
	 * Numero de Caja Del Contenedor (Ubicacion)
	 *
	 * @var string
	 */
	var $expediente_c = "";

	/**
	 * especifica tipo de pago 5=por domiciliacion 6= por voucher
	 * @var string
	 */
	var $marca_consulta = 0;

	/**
	 * Numero de Cheque2
	 *
	 * @var string
	 */
	var $ncheque = "";

	/**
	 * Monto de Cheque2
	 *
	 * @var double
	 */
	var $mcheque = 0;

	function __construct() {
		parent::__construct();

	}

	/**
	 * Modificar un Contrato...
	 * @param string $strContratoActual
	 * @param string $strNuevoContrato
	 */
	public function Modificar($strContratoActual, $strNuevoContrato, $strPeticion, $strMotivo) {

		$this -> db -> from("t_clientes_creditos");
		$this -> db -> where("contrato_id", $strNuevoContrato);
		$rsTC = $this -> db -> get();

		if ($rsTC -> num_rows() == 0) {
			$query = $this -> db -> query('SELECT * FROM t_clientes_creditos WHERE contrato_id="' . $strContratoActual . '"');
			$cant = $query -> num_rows();
			$data = array("contrato_id" => $strNuevoContrato);
			$this -> db -> where("contrato_id", $strContratoActual);
			$this -> db -> update("t_clientes_creditos", $data);

			$data1 = array("credito_id" => $strNuevoContrato);
			$this -> db -> where("credito_id", $strContratoActual);
			$this -> db -> update("t_lista_cobros", $data1);

			$data2 = array("oidc" => $strNuevoContrato);
			$this -> db -> where("oidc", $strContratoActual);
			$this -> db -> update("t_estadoejecucion", $data2);

			$data = array(
			//'id' => null,
			'referencia' => $strContratoActual, 'tipo' => 5, 'usuario' => $_SESSION['usuario'], 'motivo' => $strMotivo . '(' . $strNuevoContrato . ')', 'peticion' => $strPeticion);
			if ($cant > 0) {
				$this -> db -> insert('_th_sistema', $data);
			}
			return "<strong>&nbsp;  Proceso Finalizado Satisfactoriamente </strong>....";
		} else {
			return "<strong>&nbsp; ERROR .EL nuevo numero de contrato ya esta asignado a otro cliente... </strong>....";
		}

	}

	/**
	 * Modificar una factura...
	 * @param string $strFactrtuActual
	 * @param string $strNuevaFactura
	 */
	public function Modificar_Factura($strFacturaActual, $strNuevaFactura, $strPeticion, $strMotivo) {

		$this -> db -> from("t_clientes_creditos");
		$this -> db -> where("numero_factura", $strNuevaFactura);
		$rsTC = $this -> db -> get();

		if ($rsTC -> num_rows() == 0) {
			$query = $this -> db -> query('SELECT * FROM t_clientes_creditos WHERE numero_factura="' . $strFacturaActual . '"');
			$cant = $query -> num_rows();
			$data = array("numero_factura" => $strNuevaFactura);
			$this -> db -> where("numero_factura", $strFacturaActual);
			$this -> db -> update("t_clientes_creditos", $data);
			$this -> db -> query("update t_lista_voucher set cid='" . $strNuevaFactura . "' WHERE cid ='" . $strFacturaActual . "'");

			$data = array(
			//'id' => null,
			'referencia' => $strFacturaActual, 'tipo' => 6, 'usuario' => $_SESSION['usuario'], 'motivo' => $strMotivo . '(' . $strNuevaFactura . ')', 'peticion' => $strPeticion);
			if ($cant > 0) {
				$this -> db -> insert('_th_sistema', $data);
			}
		}

	}

	public function Modificar_Datos_Factura($strFactura, $intMotivo, $intCondicion, $strDeposito, $intMonto, $strFecha_o) {
		$data = array("motivo" => $intMotivo, "condicion" => $intCondicion, "num_operacion" => $strDeposito, "fecha_operacion" => $strFecha_o, "monto_operacion" => $intMonto);
		$this -> db -> where("numero_factura", $strFactura);
		$res = $this -> db -> update("t_clientes_creditos", $data);
		if ($res)
			return "LOS DATOS DE LA FACTURA SE MODIFICARON CON EXITO.....";
		else
			return "NO SE PUDO REALIZAR LOS CAMBIOS....";

	}

	public function Modificar_Cedula($strCedulaActual, $strCedulaNueva, $strPeticion, $strMotivo) {

		$this -> db -> from("t_clientes_creditos");
		$this -> db -> where("documento_id", $strCedulaNueva);
		$rsTC = $this -> db -> get();

		if ($rsTC -> num_rows() == 0) {
			$data = array("documento_id" => $strCedulaNueva);
			$this -> db -> where("documento_id", $strCedulaActual);
			$this -> db -> update("t_clientes_creditos", $data);
		}
		// *******************************************

		$this -> db -> from("t_personas");
		$this -> db -> where("documento_id", $strCedulaNueva);
		$rsP = $this -> db -> get();

		if ($rsP -> num_rows() == 0) {
			$query = $this -> db -> query('SELECT * FROM t_personas WHERE documento_id="' . $strCedulaActual . '"');
			$cant = $query -> num_rows();
			$data = array("documento_id" => $strCedulaNueva);
			$this -> db -> where("documento_id", $strCedulaActual);
			$this -> db -> update("t_personas", $data);

			$data = array(
			//'id' => null,
			'referencia' => $strCedulaActual, 'tipo' => 4, 'usuario' => $_SESSION['usuario'], 'motivo' => $strMotivo . '(' . $strCedulaNueva . ')', 'peticion' => $strPeticion);
			if ($cant > 0) {
				$this -> db -> insert('_th_sistema', $data);
			}
		}
		// *******************************************

		$this -> db -> from("t_lista_cobros");
		$this -> db -> where("documento_id", $strCedulaNueva);
		$rsC = $this -> db -> get();

		if ($rsC -> num_rows() == 0) {
			$data = array("documento_id" => $strCedulaNueva);
			$this -> db -> where("documento_id", $strCedulaActual);
			$this -> db -> update("t_lista_cobros", $data);
		}

	}

	public function Modificar_Boucher($strBoucherActual, $strBoucherNuevo, $strPeticion, $strMotivo, $strCausa) {
		$data = array("ndep" => $strBoucherNuevo);
		$this -> db -> where("ndep", $strBoucherActual);
		$this -> db -> update("t_lista_voucher", $data);

		$data = array('referencia' => $strBoucherActual, 'tipo' => 12, 'usuario' => $_SESSION['usuario'], 'motivo' => $strMotivo . '(' . $strBoucherNuevo . ')' . $strCausa, 'peticion' => $strPeticion);

		$this -> db -> insert('_th_sistema', $data);

	}


	/**
	 * Porcesar Aceptacion o Rechazos.
	 *
	 * @param array (Codigo Contrato | Aceptacion: 1, 2)
	 * @return boolean
	 */
	public function Procesar_Contratos($lstCreditos = array(), $sUsuario = "") {
		$sConsulta = "";
		$bValor = FALSE;
		//print_r($lstCreditos);
		foreach ($lstCreditos as $lista => $valor) {
			foreach ($valor as $lst => $val) {
				$data = array("estatus" => $val);
				$this -> db -> where("contrato_id", $lst);
				$this -> db -> update("t_clientes_creditos", $data);

			}
			$bValor = TRUE;
		}
		return $bValor;
	}

	/**
	 * Porcesar Aceptacion o Rechazos.
	 *
	 * @param array (Codigo Contrato | Aceptacion: 1, 2)
	 * @return boolean
	 */
	public function Procesar_Contratos_Facturas($lstCreditos = array(), $sUsuario = "") {
		$sConsulta = "";
		$bValor = FALSE;
		//print_r($lstCreditos);
		foreach ($lstCreditos as $lista => $valor) {
			foreach ($valor as $lst => $val) {
				$data = array("estatus" => $val);
				$this -> db -> where("numero_factura", $lst);
				$this -> db -> update("t_clientes_creditos", $data);

			}
			$bValor = TRUE;
		}
		return $bValor;
	}

	public function Estado_Cuenta($strCedula) {
		$strQuery = "SELECT * FROM t_clientes_creditos WHERE documento_id='$strCedula'";

		$Consulta = $this -> db -> query($strQuery);
		$tabla = '<table border=0 ><tr><th bgcolor="#CCCCC" colspan=2>RESUMEN</th></tr> <hr>';
		$msj = "";
		$estado_monto = 0;
		if ($Consulta -> num_rows() > 0) {
			foreach ($Consulta->result() as $row) {
				$estado_monto = (float)$row -> monto_total;
				$msj = '<td><table border=1><tr><th>';
				;
				$tabla1 = '<tr><td><table frame="hsides">';
				$fs = $row -> fecha_solicitud;
				//date('d-m-Y', strtotime($row -> fecha_solicitud));
				$fi = $row -> fecha_inicio_cobro;
				//date('d-m-Y', strtotime($row -> fecha_inicio_cobro));
				$cant = 0;
				$tabla1 .= '<tr>
							<td bgcolor="#CCCCC"><b>CONTRATO:<b></td><td>' . $row -> contrato_id . '</td>
							<td bgcolor="#CCCCC"><b>F. SOLICITUD:<b></td><td>' . $fs . '</td>
							<td bgcolor="#CCCCC"><b>F. INICIO COBRO:</b></td><td>' . $fi . '</td>
							<td bgcolor="#CCCCC"><b>MONTO TOTAL:</b></td><td>' . number_format($row -> monto_total, 2, ",", ".") . '</td>
							<td bgcolor="#CCCCC"><b>EMPRESA:</b></td><td>' . $this -> getEmpresa($row -> empresa) . '</td>
					</tr>
					<tr>
							<td bgcolor="#CCCCC"><b>MOTIVO:<b></td><td colspan=5>' . $row -> motivo . '</td>
							<td bgcolor="#CCCCC"><b>LINAJE:<b></td><td colspan=5>' . $row -> cobrado_en . '</td>
					</tr>
					<tr>
						<td bgcolor="#CCCCC"><b>NOMINA:<b></td><td colspan=9>' . $row -> nomina_procedencia . '</td>
					</tr>
					</table></td></tr>';
				$tabla .= $tabla1;
				$tabla2 = '<tr><td><table  width="100%" frame="hsides" ><tr>';

				$strQuery2 = "SELECT * FROM t_lista_cobros WHERE documento_id='$strCedula' AND credito_id='$row->contrato_id' order by fecha";
				$Consulta2 = $this -> db -> query($strQuery2);
				$cant = $Consulta2 -> num_rows();
				$div = $cant / 3;
				if ($cant > 0) {
					$cont = 0;
					$contAux = 0;
					foreach ($Consulta2->result() as $row2) {
						if ($cont == 0) {
							$tabla2 .= '<td ALIGN=center valign=top><table border=0 width=100% rules="rows"><tr><th bgcolor="#CCCCC">FECHA</th><th bgcolor="#CCCCC">MONTO</th><th bgcolor="#CCCCC">SALDO</th><th bgcolor="#CCCCC">DESCRIPCION</th></tr>';
						}
						$cont++;
						$fecha = $row2 -> fecha;
						//date('d-m-Y', strtotime($row2 -> fecha));
						$estado_monto = $estado_monto - $row2 -> monto;
						$tabla2 .= '<tr><td>' . $fecha . '</td><td align=right>' . number_format($row2 -> monto, 2, ",", ".") . '</td><td align=right>' . number_format($estado_monto, 2, ",", ".") . '</td><td>' . $row2 -> descripcion . '.</td></tr>';

						/*if ($cont >= $div) {
						 $cont = 0;
						 $tabla2 .= '</table></td>';
						 }*/
					}
					//if($cont != 0)
					$tabla2 .= '</table></td>';

				} else {
					$tabla2 .= '<td><table  frame="hsides"><tr><th><s>ESTE CONTRATO NO TIENE CUOTAS ASOCIADAS</s></th></tr></table></td>';
				}

				if ((int)$estado_monto < 0) {
					$msj .= " Nota de Devoluci&oacute;n :" . number_format($estado_monto, 2, ",", ".") . " BS. </th></tr></table></td>";
				}
				if ((int)$estado_monto > 0) {
					$msj .= " Nota de Cr&eacute;dito :" . number_format($estado_monto, 2, ",", ".") . " BS. </th></tr></table></td>";
				}
				if ((int)$estado_monto == 0) {
					$msj .= " Este contrato esta PAGADO  </th></tr></table></td>";
				}
				$tabla2 .= '</tr></table></td></tr>';
				$tabla .= $tabla2 . $msj;
			}

		}
		$tabla .= '</table>';

		return $tabla;
	}

	public function Estado_Cuenta_Contrato($strCedula, $strContrato) {
		$strQuery = "SELECT * FROM t_clientes_creditos WHERE documento_id='$strCedula' AND contrato_id='$strContrato'";

		$Consulta = $this -> db -> query($strQuery);
		$tabla = '<table border=0 ><tr><th bgcolor="#CCCCC" colspan=2>RESUMEN</th></tr> <hr>';
		$estado_monto = 0;
		if ($Consulta -> num_rows() > 0) {
			foreach ($Consulta->result() as $row) {
				$estado_monto = (float)$row -> monto_total;
				$tabla1 = '<tr><td><table>';
				$fs = $row -> fecha_solicitud;
				//date('d-m-Y', strtotime($row -> fecha_solicitud));
				$fi = $row -> fecha_inicio_cobro;
				//date('d-m-Y', strtotime($row -> fecha_inicio_cobro));
				$cant = 0;
				$tabla1 .= '<tr>
							<td bgcolor="#CCCCC"><b>CONTRATO:<b></td><td>' . $row -> contrato_id . '</td>
							<td bgcolor="#CCCCC"><b>F. SOLICITUD:<b></td><td>' . $fs . '</td>
							<td bgcolor="#CCCCC"><b>F. INICIO COBRO:</b></td><td>' . $fi . '</td>
							<td bgcolor="#CCCCC"><b>MONTO TOTAL:</b></td><td>' . number_format($row -> monto_total, 2, ",", ".") . '</td>
							<td bgcolor="#CCCCC"><b>EMPRESA:</b></td><td>' . $this -> getEmpresa($row -> empresa) . '</td>
					</tr>
					<tr>
							<td bgcolor="#CCCCC"><b>MOTIVO:<b></td><td colspan=5>' . $row -> motivo . '</td>
							<td bgcolor="#CCCCC"><b>LINAJE:<b></td><td colspan=5>' . $row -> cobrado_en . '</td>
					</tr>
					<tr>
						<td bgcolor="#CCCCC"><b>NOMINA:<b></td><td colspan=9>' . $row -> nomina_procedencia . '</td>
					</tr>
					</table></td></tr>';
				$tabla .= $tabla1;
				$tabla2 = '<tr><td><table border=0 width="100%"><tr>';

				$strQuery2 = "SELECT * FROM t_lista_cobros WHERE documento_id='$strCedula' AND credito_id='$row->contrato_id'  order by fecha";
				$Consulta2 = $this -> db -> query($strQuery2);
				$cant = $Consulta2 -> num_rows();
				$div = $cant / 3;
				if ($cant > 0) {
					$cont = 0;
					$contAux = 0;
					foreach ($Consulta2->result() as $row2) {
						if ($cont == 0) {
							$tabla2 .= '<td ALIGN=center valign=top><table border=1 width=100%><tr><th bgcolor="#CCCCC">FECHA</th><th bgcolor="#CCCCC">MONTO</th><th bgcolor="#CCCCC">SALDO</th><th bgcolor="#CCCCC">DESCRIPCION</th></tr>';
						}
						$cont++;
						$estado_monto = $estado_monto - $row2 -> monto;
						$fecha = $row2 -> fecha;
						//date('d-m-Y', strtotime($row2 -> fecha));
						$tabla2 .= '<tr><td>' . $fecha . '</td><td align=right>' . number_format($row2 -> monto, 2, ",", ".") . '</td><td align=right>' . number_format($estado_monto, 2, ",", ".") . '</td><td>' . $row2 -> descripcion . '.</td></tr>';

						/*if ($cont >= $div) {
						 $cont = 0;
						 $tabla2 .= '</table></td>';
						 }*/
					}
					//if($cont != 0)
					$tabla2 .= '</table></td>';

				} else {
					$tabla2 .= '<td><table border=1><tr><th><s>ESTE CONTRATO NO TIENE CUOTAS ASOCIADAS</s></th></tr></table></td>';
				}

				$tabla2 .= '</tr></table></td></tr>';
				$tabla .= $tabla2;
			}
		}
		$tabla .= '</table>';

		return $tabla;
	}

	public function getEmpresa($intEmpresa = null) {
		switch ($intEmpresa) {
			case 0 :
				return "COOPERATIVA ELECTRON 465 RL.";
				break;
			case 1 :
				return "GRUPO ELECTRON 465 C.A";
				break;
			default :
				return "COOPERATIVA ELECTRON 465 RL.";
				break;
		}

	}

	public function Eliminar_Contrato($strContrato = '', $strPeticion, $strMotivo) {
		$query = $this -> db -> query('SELECT * FROM t_clientes_creditos WHERE contrato_id="' . $strContrato . '"');
		$cant = $query -> num_rows();

		/*foreach ($query -> result() as $row) {
		 $this -> db -> query("DELETE from t_lista_voucher WHERE cid='". $row -> numero_factura . "'");
		 }*/

		$this -> db -> where("contrato_id", $strContrato);
		$this -> db -> delete("t_clientes_creditos");
		$this -> db -> where("credito_id", $strContrato);
		$this -> db -> delete("t_lista_cobros");

		$data = array(
		//'id' => null,
		'referencia' => $strContrato, 'tipo' => 2, 'usuario' => $_SESSION['usuario'], 'motivo' => $strMotivo, 'peticion' => $strPeticion);
		if ($cant > 0) {
			$this -> db -> insert('_th_sistema', $data);
		}

	}

	public function Inactivar_Contrato($strContrato = '', $strPeticion, $strMotivo) {
		$query = $this -> db -> query('SELECT * FROM t_clientes_creditos WHERE contrato_id="' . $strContrato . '"');
		$cant = $query -> num_rows();
		$dataU = array('estatus' => 3, 'cantidad' => 0, );

		$this -> db -> where("contrato_id", $strContrato);
		$this -> db -> update('t_clientes_creditos', $dataU);

		$data = array(
		//'id' => null,
		'referencia' => $strContrato, 'tipo' => 9, 'usuario' => $_SESSION['usuario'], 'motivo' => $strMotivo, 'peticion' => $strPeticion);
		if ($cant > 0) {
			$this -> db -> insert('_th_sistema', $data);
		}

	}

	public function Inactivar_Factura($strFactura = '', $strPeticion, $strMotivo) {
		$query = $this -> db -> query('SELECT * FROM t_clientes_creditos WHERE numero_factura="' . $strFactura . '"');
		$cant = $query -> num_rows();
		$dataU = array('estatus' => 3, 'cantidad' => 0, );

		$this -> db -> where("numero_factura", $strFactura);
		$this -> db -> update('t_clientes_creditos', $dataU);
		$this -> db -> query("update t_lista_voucher set estatus=5 WHERE cid ='" . $strFactura . "' and estatus=0");

		$data = array(
		//'id' => null,
		'referencia' => $strFactura, 'tipo' => 12, 'usuario' => $_SESSION['usuario'], 'motivo' => $strMotivo, 'peticion' => $strPeticion);
		if ($cant > 0) {
			$this -> db -> insert('_th_sistema', $data);
		}

	}

	public function Eliminar_Factura($strFactura = null, $strPeticion, $strMotivo) {
		$query = $this -> db -> query('SELECT * FROM t_clientes_creditos WHERE numero_factura="' . $strFactura . '"');
		$cant = $query -> num_rows();

		$this -> db -> query("DELETE from t_lista_voucher WHERE cid='" . $strFactura . "'");

		$this -> db -> where("numero_factura", $strFactura);
		$this -> db -> delete("t_clientes_creditos");
		$data = array(
		//'id' => null,
		'referencia' => $strFactura, 'tipo' => 3, 'usuario' => $_SESSION['usuario'], 'motivo' => $strMotivo, 'peticion' => $strPeticion);
		if ($cant > 0) {
			$this -> db -> insert('_th_sistema', $data);
		}
	}

	/**
	 *  LIBERACION DE CREDITOS POR FACTURAS
	 * 0: Nuevo | 1: Pendiente | 2: Liberado | 3: Bloqueado | 4: Entregados | 5: Antiguos
	 * 0: Completo | 1: Lista de Consultados
	 *  return json
	 * **/
	public function Liberacion_Json($intEstado = null, $intLista = null) {

		$oTable = array();
		$oCabezera = array();
		$oCuerpo = array();
		$oFil = array();
		$oCol = array();
		$oPie = array();
		$intCredito = 0;
		$Propiedades_C = array("atributo" => "'", "css" => "width:100px", "propiedades" => "");
		$strConsulta = '';
		$strSelect = 'SUM(monto_total) AS monto, t_personas.documento_id, banco_1,fecha_operacion, num_operacion, monto_operacion, estado_verificado, numero_factura, 
		primer_apellido,segundo_apellido,primer_nombre,segundo_nombre,codigo_n_a,monto_total,credito_id';
		$strJoin = 'INNER JOIN t_clientes_creditos ON t_personas.documento_id=t_clientes_creditos.documento_id ';

		/**$this->db->from("t_personas");
		 $this->db->select("SUM(monto_total) AS monto, t_personas.documento_id, banco_1,fecha_operacion, num_operacion, monto_operacion, estado_verificado, numero_factura,
		 primer_apellido,segundo_apellido,primer_nombre,segundo_nombre,codigo_n_a,monto_total,credito_id");

		 $this->db->where("estado_verificado", $intEstado);
		 $this->db->where("numero_factura !=", "");
		 $this->db->where("motivo !=", 1);*/
		$strWhere = 'WHERE estado_verificado =' . $intEstado . ' AND numero_factura != \'\' AND motivo = 2 ';
		if ($intLista != 0) {
			/** $this->db->where("credito_id >", $intLista);
			 $this->db->where("marca_consulta", 0); */
			$strWhere .= 'AND credito_id >' . $intLista . ' AND marca_consulta = 0 ';
		}
		/** $this->db->join("t_clientes_creditos","t_personas.documento_id=t_clientes_creditos.documento_id");
		 $this->db->group_by("numero_factura");
		 $this->db->limit(5);
		 $this->db->order_by("credito_id","ASC");*/
		//$Consulta = $this->db->get();

		$strGroup = 'GROUP BY numero_factura ';
		$strOrder = 'ORDER BY credito_id ASC';
		$strConsulta = 'SELECT ' . $strSelect . ' FROM t_personas ' . $strJoin . $strWhere . $strGroup . $strOrder;

		$oCabezera = array("DATOS BASICOS" => array("atributo" => "'", "css" => "width:200px", "propiedades" => ""), "FECHA" => array("atributo" => "'", "css" => "width:auto", "propiedades" => ""), "# DEPOSITO" => $Propiedades_C, "MONTO DEP" => $Propiedades_C, "MONTO" => $Propiedades_C, "BANCO" => $Propiedades_C, "# FACTURA" => $Propiedades_C, "RESPONSABLE" => $Propiedades_C, "L" => array("atributo" => "'", "css" => "width:20px", "propiedades" => ""), "B" => array("atributo" => "'", "css" => "width:20px", "propiedades" => ""));
		$i = 0;
		$intCredito = 0;
		$Consulta = $this -> db -> query($strConsulta);
		if ($Consulta -> num_rows() > 0) {
			foreach ($Consulta->result() as $row) {
				$Nombre = $row -> primer_apellido . ' ' . $row -> segundo_apellido . ' ' . $row -> primer_nombre . ' ' . $row -> segundo_nombre . ' ';
				$datos_personales = "V-" . $row -> documento_id . " | " . $Nombre;
				$oCol["Col1"] = array("texto" => $datos_personales, "atributos" => "");
				$oCol["_Col2"] = array("tipo" => "fecha", "valor" => $row -> fecha_operacion, "atributos" => "align=right", "parametros" => "class='inputxt' ");
				$oCol["_Col3"] = array("tipo" => "text", "valor" => $row -> num_operacion, "atributos" => "", "parametros" => "class='inputxt' style='width:100px'");
				$oCol["_Col4"] = array("tipo" => "text", "valor" => $row -> monto_operacion, "atributos" => "", "parametros" => "class='inputxt' style='width:100px'");
				$oCol["Col5"] = array("texto" => $row -> monto, "atributos" => "");
				$oCol["Col6"] = array("texto" => $row -> banco_1, "atributos" => "");
				$oCol["Col7"] = array("texto" => $row -> numero_factura, "atributos" => "");
				$oCol["Col8"] = array("texto" => strtoupper($row -> codigo_n_a), "atributos" => "");
				$oCol["_Col9"] = array("valor" => "L", "tipo" => "boton", "atributos" => "", "parametros" => "class='inputxt' onClick='Accion(\"" . __LOCALWWW__ . "\",\"_Col10\", this,\"Liberar_Cheques\");'");
				$oCol["_Col10"] = array("valor" => "B", "tipo" => "boton", "atributos" => "", "parametros" => "class='inputxt' onClick='Accion(\"" . __LOCALWWW__ . "\",\"_Col10\", this,\"Bloquear_Cheques\");'");
				$oCol["_Col11"] = array("valor" => $row -> numero_factura, "tipo" => "oculto");
				++$i;
				$data["marca_consulta"] = 1;
				//SE ENTREGARON AL CLIENTE
				//$this->db->where("numero_factura", $row->numero_factura);
				//$this->db->update("t_clientes_creditos", $data);
				$oFil["fila" . $i] = $oCol;
				unset($oCol);
				$intCredito = $row -> credito_id;
			}
			$oCuerpo = $oFil;
		}

		$oTable = array("Cabezera" => $oCabezera, "Css" => "ui-widget ui-widget-content", "Cuerpo" => $oCuerpo, "Extra" => $intCredito);

		return json_encode($oTable);
	}

	/**
	 *  LIBERACION DE CREDITOS POR FACTURAS
	 * 0: Nuevo | 1: Pendiente | 2: Liberado | 3: Bloqueado | 4: Antiguos | 5: Entregados
	 *  return json
	 * **/
	public function Actualizar_Liberacion_Estado_Chequera($data = '') {
		$this -> db -> where("numero_factura", $data["numero_factura"]);
		$this -> db -> update("t_clientes_creditos", $data);
	}

	public function Estatus_Consulta_Cheques($intEstado = null, $strUsuario = null) {
		$Estados = array();
		$Estados['nuevos'] = 0;
		$Estados['pendientes'] = 0;
		$Estados['liberados'] = 0;
		$Estados['anulados'] = 0;
		$strConsulta = "";
		$this -> db -> select("Count(DISTINCT(numero_factura)) AS cantidad, estado_verificado, numero_factura");

		$this -> db -> from("t_clientes_creditos");
		$this -> db -> where("expediente_c", $strUsuario);
		$this -> db -> where("estado_verificado !=", 4);
		$this -> db -> limit(200);
		$this -> db -> group_by("numero_factura,estado_verificado");
		$strConsulta = $this -> db -> get();
		if ($strConsulta -> num_rows() > 0) {
			foreach ($strConsulta->result() as $row) {
				switch ($row->estado_verificado) {
					case 0 :
						$Estados['nuevos'] += $row -> cantidad;
						break;
					case 1 :
						$Estados['pendientes'] += $row -> cantidad;
						break;
					case 2 :
						$Estados['liberados'] += $row -> cantidad;
						break;
					case 3 :
						$Estados['anulados'] += $row -> cantidad;
						break;

					default :
						break;
				}
			}
		}
		return $Estados;
	}

	public function Listar_Estados_Json($intEstado = null, $strUsuario = null) {

		$oTable = array();
		$oCabezera = array();
		$oCuerpo = array();
		$oFil = array();
		$oCol = array();
		$oPie = array();
		$intCredito = 0;

		$Propiedades_C = array("atributo" => "'", "css" => "width:100px", "propiedades" => "");
		$strConsulta = '';
		$this -> db -> select("Count(numero_factura) AS cantidad, estado_verificado, numero_factura, t_clientes_creditos.documento_id, primer_apellido,segundo_apellido,primer_nombre,segundo_nombre,codigo_n_a,monto_total,credito_id");
		$this -> db -> from("t_clientes_creditos");
		$this -> db -> where("expediente_c", $strUsuario);
		$this -> db -> where("estado_verificado ", $intEstado);
		$this -> db -> join("t_personas", "t_personas.documento_id=t_clientes_creditos.documento_id");
		$this -> db -> limit(200);
		$this -> db -> group_by("numero_factura,estado_verificado");
		$strConsulta = $this -> db -> get();
		$oCabezera = array("CEDULA" => $Propiedades_C, "APELLIDOS Y NOMBRE" => array("atributo" => "'", "css" => "width:300px", "propiedades" => ""), "FACTURA" => $Propiedades_C, "RESPONSABLE" => $Propiedades_C, );
		$i = 0;
		$intCredito = 0;
		if ($strConsulta -> num_rows() > 0) {
			foreach ($strConsulta->result() as $row) {
				$oCol["Col1"] = array("texto" => $row -> documento_id, "atributos" => "");
				$Nombre = $row -> primer_apellido . ' ' . $row -> segundo_apellido . ' ' . $row -> primer_nombre . ' ' . $row -> segundo_nombre;
				$oCol["Col2"] = array("texto" => $Nombre, "atributos" => "");
				$oCol["Col3"] = array("texto" => $row -> numero_factura, "atributos" => "");
				$oCol["Col5"] = array("texto" => strtoupper($row -> codigo_n_a), "atributos" => "");
				$oCol["_Col7"] = array("texto" => $row -> monto_total, "valor" => $row -> numero_factura, "tipo" => "oculto", "atributos" => "", "parametros" => "'");
				$oCol["_Col8"] = array("texto" => $row -> credito_id, "valor" => $row -> credito_id, "tipo" => "oculto", "atributos" => "", "parametros" => "'");
				++$i;
				$oFil["fila" . $i] = $oCol;
				unset($oCol);
				$intCredito = $row -> credito_id;
			}
			$oCuerpo = $oFil;
		}
		$oTable = array("Cabezera" => $oCabezera, "Css" => "ui-widget ui-widget-content", "Cuerpo" => $oCuerpo, "Extra" => $intCredito);

		return json_encode($oTable);
	}

	public function L_JsonDEMO($banco = null, $desde = null, $hasta = null, $intEstado = null, $intLista = null) {
		$i = 0;
		$intCredito = 0;

		$strSelect = 'SUM(monto_total) AS monto, t_personas.documento_id, banco_1,fecha_solicitud,fecha_operacion, num_operacion, monto_operacion, estado_verificado, numero_factura, 
		CONCAT(primer_apellido,\' \',LEFT(segundo_apellido,1),\'. \',primer_nombre,\' \', LEFT(segundo_nombre,1),\'. \') AS Nombre,codigo_n_a,monto_total,credito_id';
		$strJoin = 'INNER JOIN t_clientes_creditos ON t_personas.documento_id=t_clientes_creditos.documento_id ';
		$strWhere = 'WHERE estado_verificado =' . $intEstado . ' AND numero_factura != \'\' AND motivo = 2 ';

		if ($intLista != 0) {
			$strWhere .= 'AND credito_id >' . $intLista . ' AND marca_consulta = 0 ';
		}
		if ($banco != null && $banco != '') {
			$strWhere .= 'AND banco_1 =\'' . $banco . '\' ';
		}
		if ($desde != null && trim($desde) != '') {
			$strWhere .= 'AND fecha_solicitud >\'' . $desde . '\' ';
		}
		if ($hasta != null && trim($hasta) != '') {
			$strWhere .= 'AND fecha_solicitud <\'' . $hasta . '\' ';
		}

		$strGroup = 'GROUP BY numero_factura ';
		$strOrder = 'ORDER BY credito_id ASC ';
		$strConsulta = 'SELECT ' . $strSelect . ' FROM t_personas ' . $strJoin . $strWhere . $strGroup . $strOrder;
		//CONSULTA GENERAL

		//$strUpdate = 'UPDATE t_personas ' . $strJoin . ' SET marca_consulta=1 ' . $strWhere; //MARCAR TODOS LOS ELEMENTOS ENTREGADOS AL CLIENTE
		//$this->db->query($strUpdate);		//SE ENTREGARON AL CLIENTE

		$oCabezera[1] = array("titulo" => "CEDULA", "atributos" => "width:80px");
		$oCabezera[2] = array("titulo" => "DATOS BASICOS", "atributos" => "width:180px", "buscar" => 0);
		$oCabezera[3] = array("titulo" => "FECHA DEP.", "tipo" => "fecha", "atributos" => "width:180px");
		$oCabezera[4] = array("titulo" => "# DEPOSITO", "tipo" => "texto", "atributos" => "width:80px", "oculto" => 0);
		$oCabezera[5] = array("titulo" => "MONTO DEP", "tipo" => "texto", "atributos" => "width:100px");
		$oCabezera[6] = array("titulo" => "MONTO", "atributos" => "width:50px");
		$oCabezera[7] = array("titulo" => "BANCO", "atributos" => "width:80px", "oculto" => 0);
		$oCabezera[8] = array("titulo" => "# FACT", "atributos" => "width:70px");
		$oCabezera[9] = array("titulo" => "USUARIO", "atributos" => "width:50px", "buscar" => 0);
		$oCabezera[10] = array("titulo" => "DEP", "tipo" => "boton", "atributos" => "width:30px", "funcion" => "liberar", "parametro" => "3,2,8");
		$oCabezera[11] = array("titulo" => "BLO", "tipo" => "bimagen", "atributos" => "width:30px", "funcion" => "bloquear", "parametro" => null);

		$Consulta = $this -> db -> query($strConsulta);
		if ($Consulta -> num_rows() > 0) {
			$Conexion = $Consulta -> result();
			foreach ($Conexion as $row) {
				++$i;
				$oFil[$i] = array("1" => "V-" . $row -> documento_id, "2" => $row -> Nombre, "3" => $row -> fecha_operacion, "4" => $row -> num_operacion, "5" => $row -> monto_operacion, "6" => $row -> monto_total, "7" => $row -> banco_1, "8" => $row -> numero_factura, "9" => strtoupper($row -> codigo_n_a), "10" => "L", "11" => __IMG__ . "calendar.gif");
			}
		}
		$oTable = array("Cabezera" => $oCabezera, "Cuerpo" => $oFil, "Css" => "", "Obejtos" => Array(), "Paginador" => 10, "Enumerar" => 0, "Origen" => "json");
		return json_encode($oTable);
	}

}
?>
