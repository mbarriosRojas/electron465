<?php
/**
 *  @author Carlos Enrique Penaa Albarran
 *  @package SaGem.system.application.model
 *  @version 1.0.0
 */
class CCreditose extends Model {

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
	var $estatus;

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

	/**
	 * Lista de Seriales
	 * @var string
	 */
	var $fecha_verificado = null;
	
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
	 * Lista de Seriales
	 * @var string
	 */
	var $marca_consulta = 0;
	
	
	function __construct() {
		parent::__construct();

	}

	
	public function Estado_Cuenta($strCedula) {
		$strQuery = "SELECT * FROM t_clientes_creditos WHERE documento_id='$strCedula'";

		$Consulta = $this -> db -> query($strQuery);
		$tabla = '<table border=1  ><tr><th bgcolor="#CCCCC" colspan=2>RESUMEN</th></tr> <hr>';
		$msj = "";
		$estado_monto=0;
		if ($Consulta -> num_rows() > 0) {
			foreach ($Consulta->result() as $row) {
				$estado_monto=(float)$row->monto_total;
				$msj='<td><table border=1><tr><th>';;
				$tabla1 = '<tr><td><table frame="hsides">';
				$fs = $row -> fecha_solicitud;//date('d-m-Y', strtotime($row -> fecha_solicitud));
				$fi = $row -> fecha_inicio_cobro;//date('d-m-Y', strtotime($row -> fecha_inicio_cobro));
				$cant = 0;
				$tabla1 .= '<tr>
							<td bgcolor="#CCCCC"><b>CONTRATO:<b></td><td>' . $row -> contrato_id . '</td>
							<td bgcolor="#CCCCC"><b>F. SOLICITUD:<b></td><td>' . $fs . '</td>
							<td bgcolor="#CCCCC"><b>F. INICIO COBRO:</b></td><td>' . $fi . '</td>
							<td bgcolor="#CCCCC"><b>MONTO TOTAL:</b></td><td>' . number_format($row -> monto_total, 2, ",", ".") . '</td>
							<td bgcolor="#CCCCC"><b>EMPRESA:</b></td><td>' . $this->getEmpresa($row -> empresa) . '</td>
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
				$cant = $Consulta2->num_rows();
				$div = $cant / 3;
				if ($cant > 0) {
					$cont = 0;
					$contAux = 0;
					foreach ($Consulta2->result() as $row2) {
						if ($cont == 0) {
							$tabla2 .= '<td ALIGN=center valign=top><table border=0 width=100% rules="rows"><tr><th bgcolor="#CCCCC">FECHA</th><th bgcolor="#CCCCC">MONTO</th><th bgcolor="#CCCCC">SALDO</th><th bgcolor="#CCCCC">DESCRIPCION</th></tr>';
						}
						$cont++;
						$fecha = $row2 -> fecha;//date('d-m-Y', strtotime($row2 -> fecha));
						$estado_monto=$estado_monto-$row2->monto;
						$tabla2 .= '<tr><td>' . $fecha . '</td><td align=right>' . number_format($row2 -> monto, 2, ",", ".") . '</td><td align=right>' . number_format($estado_monto, 2, ",", ".") . '</td><td>' .$row2 -> descripcion .'.</td></tr>';
						
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

				
				if((int)$estado_monto < 0){
					$msj .= " Nota de Devoluci&oacute;n :". number_format($estado_monto, 2, ",", ".") . " BS. </th></tr></table></td>";
				}
				if((int)$estado_monto > 0){
					$msj .= " Nota de Cr&eacute;dito :". number_format($estado_monto, 2, ",", ".") . " BS. </th></tr></table></td>";
				}
				if((int)$estado_monto == 0){
					$msj .= " Este contrato esta PAGADO  </th></tr></table></td>";
				}
				$tabla2 .= '</tr></table></td></tr>';
				$tabla .= $tabla2.$msj;
			}
			
		}
		$tabla .= '</table>';

		return $tabla;
	}

	public function Estado_Cuenta_Contrato($strCedula,$strContrato) {
		$strQuery = "SELECT * FROM t_clientes_creditos WHERE documento_id='$strCedula' AND contrato_id='$strContrato'";

		$Consulta = $this -> db -> query($strQuery);
		$tabla = '<table border=0 ><tr><th bgcolor="#CCCCC" colspan=2>RESUMEN</th></tr> <hr>';
		$estado_monto=0;
		if ($Consulta -> num_rows() > 0) {
			foreach ($Consulta->result() as $row) {
				$estado_monto=(float)$row->monto_total;
				$tabla1 = '<tr><td><table>';
				$fs = $row -> fecha_solicitud;//date('d-m-Y', strtotime($row -> fecha_solicitud));
				$fi = $row -> fecha_inicio_cobro;//date('d-m-Y', strtotime($row -> fecha_inicio_cobro));
				$cant = 0;
				$tabla1 .= '<tr>
							<td bgcolor="#CCCCC"><b>CONTRATO:<b></td><td>' . $row -> contrato_id . '</td>
							<td bgcolor="#CCCCC"><b>F. SOLICITUD:<b></td><td>' . $fs . '</td>
							<td bgcolor="#CCCCC"><b>F. INICIO COBRO:</b></td><td>' . $fi . '</td>
							<td bgcolor="#CCCCC"><b>MONTO TOTAL:</b></td><td>' . number_format($row -> monto_total, 2, ",", ".") . '</td>
							<td bgcolor="#CCCCC"><b>EMPRESA:</b></td><td>' . $this->getEmpresa($row -> empresa) . '</td>
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
				$cant = $Consulta2->num_rows();
				$div = $cant / 3;
				if ($cant > 0) {
					$cont = 0;
					$contAux = 0;
					foreach ($Consulta2->result() as $row2) {
						if ($cont == 0) {
							$tabla2 .= '<td ALIGN=center valign=top><table border=1 width=100%><tr><th bgcolor="#CCCCC">FECHA</th><th bgcolor="#CCCCC">MONTO</th><th bgcolor="#CCCCC">SALDO</th><th bgcolor="#CCCCC">DESCRIPCION</th></tr>';
						}
						$cont++;
						$estado_monto=$estado_monto-$row2->monto;
						$fecha = $row2 -> fecha;//date('d-m-Y', strtotime($row2 -> fecha));
						$tabla2 .= '<tr><td>' . $fecha . '</td><td align=right>' . number_format($row2 -> monto, 2, ",", ".") . '</td><td align=right>' . number_format($estado_monto, 2, ",", ".") . '</td><td>' .$row2 -> descripcion. '.</td></tr>';

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

	
	public function getEmpresa($intEmpresa = null){
		switch ($intEmpresa){
			case 0:
				return "COOPERATIVA ELECTRON 465 RL.";
				break;
			case 1:
				return "GRUPO ELECTRON 465 C.A";
				break;
			default:
				return "COOPERATIVA ELECTRON 465 RL.";
				break;
		}
		
	}
	
	public function Cabezera_Estado_Cuenta($strCedula) {
		$strQuery = "SELECT * FROM t_personas WHERE documento_id='$strCedula'";

		$strConsulta = $this -> db -> query($strQuery);
		$strTabla = '<table border=0 >';

		if ($strConsulta -> num_rows() > 0) {
			foreach ($strConsulta->result() as $row) {
				$strDireccion = 'AV. ' . $row -> avenida . ' CALLE ' . $row -> calle . ' MUNICIPIO ' . $row -> municipio . ' SECTOR ' . $row -> sector . ' CASA # ' . $row -> direccion;
				$strTabla .= '<tr><th bgcolor="#CCCCC" colspan=10>DATOS PERSONALES</th></tr><tr>
					<td bgcolor="#CCCCC"><b>C&Eacute;DULA:</b></td><td>' . $row -> documento_id . '</td>
					<td bgcolor="#CCCCC"><b>NOMBRE:</b></td><td>' . $row -> primer_nombre . ' ' . $row -> segundo_nombre . ' ' . $row -> primer_apellido . ' ' . $row -> segundo_apellido . '</td>	
					<td bgcolor="#CCCCC"><b>CARGO:</b></td><td>' . $row -> cargo_actual . '</td>
				</tr>
				<tr>
					<td bgcolor="#CCCCC"><b>SEXO:</b></td><td>' . $row -> sexo . '</td>
					<td bgcolor="#CCCCC"><b>EST. CIVIL:</b></td><td>' . $row -> estado_civil . '</td>
					<td bgcolor="#CCCCC"><b>TEL&Eacute;FONO:</b></td><td>' . $row -> telefono . '</td>
				</tr>
				<tr>
					<td bgcolor="#CCCCC"><b>BANCO:</td><td>' . $row -> banco_1 . '</td>
					<td bgcolor="#CCCCC"><b>N. CUENTA:</td><td>' . $row -> cuenta_1 . '</td>
					<td bgcolor="#CCCCC"><b>T. CUENTA:</td><td>' . $row -> tipo_cuenta_1 . '</td>
				</tr>
				<tr>
					<td bgcolor="#CCCCC"><b>DIRECCI&Oacute;N:</td><td colspan=5>' . $strDireccion . '</td>
				</tr>
				<tr>
					<td bgcolor="#CCCCC"><b>D. TRABAJO:</td><td colspan=5>' . $row -> direccion_trabajo . '</td>
				</tr>';
			}
		}
		
		return $strTabla . '</table>';
	}
}
?>
