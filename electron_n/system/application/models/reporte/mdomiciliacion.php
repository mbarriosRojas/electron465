<?php
/**
 * Controlador de Barra de menu
 *
 * @author Carlos Enrique Peña Albarrán
 * @package cooperativa.system.application.model.reporte
 * @version 2.0.0
 */
class MDomiciliacion extends Model {
	var $Void = "";

	var $SP = " ";

	var $Dot = ".";

	var $Zero = "0";

	var $Neg = "Menos";
	//

	var $cedula = '';

	var $nombre = '';

	var $descripcion = '';

	var $Banco = '';

	var $cuenta = '';

	var $direccion = '';

	var $empresa = '';

	var $lista = '';

	var $linaje = '';

	var $nomina = '';

	/**
	 * Usuario Responsable...
	 */
	var $usuario = '';

	var $total_suma = 0;

	var $detalles = '';

	public function __construct() {
		parent::__construct();

	}

	function Imprimir_Factura($strId = null, $strFactura = null,$strControl = null) {
		$strNombre = '';
		$strBanco = '';
		$strCuenta = '';
		$strDireccion = '';
		$strLista = '';
		$Empresa = "";
		$strNomina = '';
		$data["cedula"] = $strId;
		$strMes = '';
		$strUsuario = '';
		$strCobrado_Por = '';
		$dblSuma = 0;
		$descuento = 0;
		$descripcion = 'MONTO TOTAL ACREDITADO ';
		$descripcion1 = 'GASTOS POR COBRANZA: ';
		$Consulta = $this -> db -> query("SELECT * FROM t_personas WHERE documento_id='$strId'");
		$fecha_sol = '';
		$muestra_promo = 1;
		$banco_adm = '';
		$nomina_adm = '';
		$monto_opera = 0;
		$lugar ='';
		if ($Consulta -> num_rows() != 0) {
			foreach ($Consulta->result() as $row) {
				$Aceptado = '';
				$strNombre = $row -> primer_apellido . ' ' . $row -> segundo_apellido . ' ' . $row -> primer_nombre . ' ' . $row -> segundo_nombre;
				$strBanco = $row -> banco_1;
				$strCuenta = $row -> cuenta_1;
				$strDireccion = 'AV. ' . $row -> avenida . ' CALLE ' . $row -> calle . ' MUNICIPIO ' . $row -> municipio . ' SECTOR ' . $row -> sector . ' CASA # ' . $row -> direccion;
				$qcontro = '';
				if($strControl != null) $qcontro = ' AND numero_control = "'.$strControl.'"'; 
				$Con = $this -> db -> query("SELECT * FROM t_clientes_creditos WHERE estatus!=3 AND numero_factura ='$strFactura' ".$qcontro);
				$strUsuario = $row -> codigo_nomina;
				if ($Con -> num_rows() != 0) {

					$strLista = '<table style="width:100%" border=1><thead><tr><th colspan=7>TABLA CONTROL DE PAGOS</th></tr></thead>
					<tr style="background-color:#ccc"><td><b>REFERENCIA</b></td><td><b>CUOTA</b></td>
					<td><b>PERIOCIDAD</b></td><td><b>MONTO</b></td><td><b>LINAJE</b></td><td><b>NOMINA</b></td><td><b>MULTA</b></td> </tr>';
					foreach ($Con->result() as $rw) {
						$lugar = $rw -> codigo_n;
						$fecha_sol = $rw -> fecha_solicitud;
						/*if ($rw -> motivo == 1) {
						 $descuento = 0.15;

						 }
						 if ($rw -> motivo == 2) {
						 $descuento = 0.1;

						 }*/
						$Empresa = $rw -> empresa;
						if ($rw -> forma_contrato == 4) {
							$descripcion = 'REFERENCIA (' . $rw -> contrato_id . ') POR  MONTO MENSUAL DEL DESCUENTO ';
							if ($rw -> periocidad == 2) {
								$descripcion = 'REFERENCIA (' . $rw -> contrato_id . ') POR MONTO QUINCENAL DEL DESCUENTO ';
							}
						}
						$Aceptado = $rw -> estatus;
						$strNomina = $rw -> nomina_procedencia;
						$strCobrado_Por = $rw -> cobrado_en;
						$monto_opera = $rw -> monto_operacion;
						$monto_opera2 = $rw -> mcheque;
						if ($rw -> forma_contrato > 3 && $rw -> forma_contrato != 6 ) {
							//$muestra_promo = 0;
							$dblSuma = $rw -> monto_cuota;
							$strLista = '<table style="width:100%" border=1>';
						} else {

							$strContrato = 'UNICO';
							$ano = substr($rw -> fecha_inicio_cobro, 0, 4);
							$mes = substr($rw -> fecha_inicio_cobro, 5, 2);
							$div = 1;
							$strPericidad = '';
							switch ($rw->periocidad) {
								case 0 :
									$strPericidad = 'SEMANAL ';
									break;
								case 1 :
									$strPericidad = 'CATORCENAL ';
									break;
								case 2 :
									$strPericidad = 'QUINCENAL 15 - 30 ';
									$div = 2;
									break;
								case 3 :
									$strPericidad = 'QUINCENAL 10 - 25 ';
									$div = 2;
									break;
								case 4 :
									$dia_periodo = explode('-', $rw -> fecha_inicio_cobro);
									$datetime2 = new DateTime($fecha_sol);
									$datetime1 = new DateTime('2012-10-30');
									if ($datetime1 >= $datetime2) {
										$strPericidad = 'MENSUAL LOS ' . $dia_periodo[2];
									}else{
										$strPericidad = 'MENSUAL DEL 1 AL 30 DE CADA MES ';
									}
									break;
								case 8 :
									
									$strPericidad = 'MENSUAL LOS 10';
									
									break;
								case 9 :
									$strPericidad = 'MENSUAL LOS 25';
									break;
							}
							switch ($rw->forma_contrato) {
								case 0 :
									$strContrato = $strPericidad;
									$num = $rw -> numero_cuotas / $div;
									$vletras = strtoupper($this -> ValorEnLetras($rw -> numero_cuotas / $div,''));
									$strMes = ' A ' . $vletras .' ( ' . ($rw -> numero_cuotas) / $div . ' ) MESES';
									//$descripcion1 .= ' A ( ' . ($rw -> numero_cuotas) / $div . ' ) MESES ES DE: ' ;
									//$descripcion1 .= ' A ' .$rw -> numero_cuotas ;
									break;
								case 1 :
									$strContrato = 'DESDE 1 ' . $this -> MES_ACTIVO($mes) . '  DE HASTA  EL 30 ' . $this -> MES_ACTIVO($mes) . '  DEL ' . $ano;
									break;
								case 2 :
									$strContrato = 'DESDE 1 ' . $this -> MES_ACTIVO($mes) . '  DE HASTA  EL 30 ' . $this -> MES_ACTIVO($mes) . ' DEL ' . $ano;
									break;
								case 3 :
									$strContrato = 'EXTRA DESDE 1 ' . $this -> MES_ACTIVO($mes) . '  DE HASTA  EL 30 ' . $this -> MES_ACTIVO($mes) . ' DEL ' . $ano;
									break;
								default :
									$strContrato = 'DESDE 1 ' . $this -> MES_ACTIVO($mes) . ' DE HASTA  EL 30 ' . $this -> MES_ACTIVO($mes) . ' DEL ' . $ano;
							}

							$strLista .= '<tr><td>' . $rw -> contrato_id . '</td><td>' . $rw -> numero_cuotas . '</td>
							<td> ' . $strContrato . ' </td>
							<td><b>' . number_format($rw -> monto_cuota, 2, ".", ",") . ' Bs.</b></td>
							<td>' . $rw -> cobrado_en . '</td><td>' . $rw -> nomina_procedencia . '</td><td><b>' . number_format(($rw -> monto_cuota * 0.15), 2, ".", ",") . ' Bs.</b></td></tr>';
							$dblSuma += $rw -> monto_total;
							$nomina_adm = $rw -> nomina_procedencia;

						}
						if ($rw -> cobrado_en == $row -> banco_1 || $rw -> cobrado_en == 'NOMINA' || $rw -> cobrado_en == 'CREDINFO' || $rw -> cobrado_en == 'INVERCRESA') {
							$strBanco = $row -> banco_1;
							$strCuenta = $row -> cuenta_1;
						} else {
							$strBanco = $row -> banco_2;
							$strCuenta = $row -> cuenta_2;
						}

					}
				}

				$datetime2 = new DateTime($fecha_sol);
				$datetime1 = new DateTime('2012-07-17');
				if ($datetime1 <= $datetime2 && $muestra_promo == 1) {
					$strLista .= '<tr><td>GA-' . $strFactura . '</td><td>1</td><td>GASTOS POR COBRANZA</td><td >' . number_format((($monto_opera + $monto_opera2 )* 0.3), 2, ".", ",") . ' Bs.</td>
									<td>' . $strBanco . '</td><td>BANCO</td><td>N/A</td></tr></table><br>
									DETALLES: <b> ' . $descripcion . number_format($dblSuma, 2, ".", ",") . ' Bs.<br><br></b>';
					$strLista .= '<b>' . $descripcion1 . number_format(($monto_opera * 0.3), 2, ".", ",") . ' Bs.</b><br><br>';
				} else {
					$strLista .= '</table><br>
				DETALLES: <b> ' . $descripcion . number_format($dblSuma, 2, ".", ",") . ' Bs.<br><br></b>';
				}

			}
		} else {

		}
		$data["meses"] = $strMes;
		$data["nombre"] = $strNombre;
		$data["banco"] = $strBanco;
		$data["cuenta"] = $strCuenta;
		$data["direccion"] = $strDireccion;
		$data["empresa"] = $Empresa;
		$data["lista"] = $strLista;
		$data["factura"] = $strFactura;
		$data["nomina"] = '<b>NOMINA: </b>' . $strNomina;
		$data['usuario'] = $strUsuario;
		$data['lugar'] = $lugar;
		$data['Nivel'] = $this -> session -> userdata('nivel');
		$data['Aceptado'] = $Aceptado;
		$data['Conectado'] = $this -> session -> userdata('usuario');
		
		$this -> load -> view("reportes/afiliadesafilia", $data);

	}
	
	function Imprimir_Contrato_Nuevo($strId = null, $strCont = null,$strControl = null) {
		$strNombre = '';
		$strBanco = '';
		$strCuenta = '';
		$strDireccion = '';
		$strLista = '';
		$Empresa = "";
		$strNomina = '';
		$data["cedula"] = $strId;
		$strMes = '';
		$strUsuario = '';
		$strCobrado_Por = '';
		$dblSuma = 0;
		$descuento = 0;
		$descripcion = 'MONTO TOTAL ACREDITADO ';
		$descripcion1 = 'GASTOS POR COBRANZA: ';
		$Consulta = $this -> db -> query("SELECT * FROM t_personas WHERE documento_id='$strId'");
		$fecha_sol = '';
		$muestra_promo = 1;
		$banco_adm = '';
		$nomina_adm = '';
		$monto_opera = 0;
		$lugar ='';
		if ($Consulta -> num_rows() != 0) {
			foreach ($Consulta->result() as $row) {
				$Aceptado = '';
				$strNombre = $row -> primer_apellido . ' ' . $row -> segundo_apellido . ' ' . $row -> primer_nombre . ' ' . $row -> segundo_nombre;
				$strBanco = $row -> banco_1;
				$strCuenta = $row -> cuenta_1;
				$strDireccion = 'AV. ' . $row -> avenida . ' CALLE ' . $row -> calle . ' MUNICIPIO ' . $row -> municipio . ' SECTOR ' . $row -> sector .' EDIFICIO '. $row -> parroquia .' CASA o APARTAMENTO # ' . $row -> direccion;
				$qcontro = '';
				if($strControl != null) $qcontro = ' AND numero_control = "'.$strControl.'"'; 
				$Con = $this -> db -> query("SELECT * FROM t_clientes_creditos WHERE estatus!=3 AND contrato_id ='$strCont' ".$qcontro);
				$strUsuario = $row -> codigo_nomina;
				if ($Con -> num_rows() != 0) {

					$strLista = '<table style="width:100%" border=1><thead><tr><th colspan=7>TABLA CONTROL DE PAGOS</th></tr></thead>
					<tr style="background-color:#ccc"><td><b>REFERENCIA</b></td><td><b>CUOTA</b></td>
					<td><b>PERIOCIDAD</b></td><td><b>MONTO</b></td><td><b>LINAJE</b></td><td><b>NOMINA</b></td><td><b>MULTA</b></td> </tr>';
					foreach ($Con->result() as $rw) {
						$lugar = $rw -> codigo_n;
						$fecha_sol = $rw -> fecha_solicitud;
						$Empresa = $rw -> empresa;
						if ($rw -> forma_contrato == 4) {
							$descripcion = 'REFERENCIA (' . $rw -> contrato_id . ') POR  MONTO MENSUAL DEL DESCUENTO ';
							if ($rw -> periocidad == 2) {
								$descripcion = 'REFERENCIA (' . $rw -> contrato_id . ') POR MONTO QUINCENAL DEL DESCUENTO ';
							}
						}
						$Aceptado = $rw -> estatus;
						$strNomina = $rw -> nomina_procedencia;
						$strCobrado_Por = $rw -> cobrado_en;
						$monto_opera = $rw -> monto_operacion;
						$monto_opera2 = $rw -> mcheque;
						if ($rw -> forma_contrato > 3 && $rw -> forma_contrato != 6 ) {
							//$muestra_promo = 0;
							$dblSuma = $rw -> monto_cuota;
							$strLista = '<table style="width:100%" border=1>';
						} else {

							$strContrato = 'UNICO';
							$ano = substr($rw -> fecha_inicio_cobro, 0, 4);
							$mes = substr($rw -> fecha_inicio_cobro, 5, 2);
							$div = 1;
							$strPericidad = '';
							switch ($rw->periocidad) {
								case 0 :
									$strPericidad = 'SEMANAL ';
									break;
								case 1 :
									$strPericidad = 'CATORCENAL ';
									break;
								case 2 :
									$strPericidad = 'QUINCENAL 15 - 30 ';
									$div = 2;
									break;
								case 3 :
									$strPericidad = 'QUINCENAL 10 - 25 ';
									$div = 2;
									break;
								case 4 :
									$dia_periodo = explode('-', $rw -> fecha_inicio_cobro);
									$datetime2 = new DateTime($fecha_sol);
									$datetime1 = new DateTime('2012-10-30');
									if ($datetime1 >= $datetime2) {
										$strPericidad = 'MENSUAL LOS ' . $dia_periodo[2];
									}else{
										$strPericidad = 'MENSUAL DEL 1 AL 30 DE CADA MES ';
									}
									break;
								case 8 :
									
									$strPericidad = 'MENSUAL LOS 10';
									
									break;
								case 9 :
									$strPericidad = 'MENSUAL LOS 25';
									break;
							}
							switch ($rw->forma_contrato) {
								case 0 :
									$strContrato = $strPericidad;
									$num = $rw -> numero_cuotas / $div;
									$vletras = strtoupper($this -> ValorEnLetras($rw -> numero_cuotas / $div,''));
									$strMes = ' A ' . $vletras .' ( ' . ($rw -> numero_cuotas) / $div . ' ) MESES';
									//$descripcion1 .= ' A ( ' . ($rw -> numero_cuotas) / $div . ' ) MESES ES DE: ' ;
									//$descripcion1 .= ' A ' .$rw -> numero_cuotas ;
									break;
								case 1 :
									$strContrato = 'DESDE 1 ' . $this -> MES_ACTIVO($mes) . '  DE HASTA  EL 30 ' . $this -> MES_ACTIVO($mes) . '  DEL ' . $ano;
									break;
								case 2 :
									$strContrato = 'DESDE 1 ' . $this -> MES_ACTIVO($mes) . '  DE HASTA  EL 30 ' . $this -> MES_ACTIVO($mes) . ' DEL ' . $ano;
									break;
								case 3 :
									$strContrato = 'EXTRA DESDE 1 ' . $this -> MES_ACTIVO($mes) . '  DE HASTA  EL 30 ' . $this -> MES_ACTIVO($mes) . ' DEL ' . $ano;
									break;
								default :
									$strContrato = 'DESDE 1 ' . $this -> MES_ACTIVO($mes) . ' DE HASTA  EL 30 ' . $this -> MES_ACTIVO($mes) . ' DEL ' . $ano;
							}

							$strLista .= '<tr><td>' . $rw -> contrato_id . '</td><td>' . $rw -> numero_cuotas . '</td>
							<td> ' . $strContrato . ' </td>
							<td><b>' . number_format($rw -> monto_cuota, 2, ".", ",") . ' Bs.</b></td>
							<td>' . $rw -> cobrado_en . '</td><td>' . $rw -> nomina_procedencia . '</td><td><b>' . number_format(($rw -> monto_cuota * 0.15), 2, ".", ",") . ' Bs.</b></td></tr>';
							$dblSuma += $rw -> monto_total;
							$nomina_adm = $rw -> nomina_procedencia;

						}
						if ($rw -> cobrado_en == $row -> banco_1 || $rw -> cobrado_en == 'NOMINA' || $rw -> cobrado_en == 'CREDINFO' || $rw -> cobrado_en == 'INVERCRESA') {
							$strBanco = $row -> banco_1;
							$strCuenta = $row -> cuenta_1;
						} else {
							$strBanco = $row -> banco_2;
							$strCuenta = $row -> cuenta_2;
						}

					}
				}

				$datetime2 = new DateTime($fecha_sol);
				$datetime1 = new DateTime('2012-07-17');
				if ($datetime1 <= $datetime2 && $muestra_promo == 1) {
					$strLista .= '<tr><td>GA-' . $strContrato . '</td><td>1</td><td>GASTOS POR COBRANZA</td><td >' . number_format((($monto_opera + $monto_opera2 )* 0.3), 2, ".", ",") . ' Bs.</td>
									<td>' . $strBanco . '</td><td>BANCO</td><td>N/A</td></tr></table><br>
									DETALLES: <b> ' . $descripcion . number_format($dblSuma, 2, ".", ",") . ' Bs.<br><br></b>';
					$strLista .= '<b>' . $descripcion1 . number_format(($monto_opera * 0.3), 2, ".", ",") . ' Bs.</b><br><br>';
				} else {
					$strLista .= '</table><br>
				DETALLES: <b> ' . $descripcion . number_format($dblSuma, 2, ".", ",") . ' Bs.<br><br></b>';
				}

			}
		} else {

		}
		$data["meses"] = $strMes;
		$data["nombre"] = $strNombre;
		$data["banco"] = $strBanco;
		$data["cuenta"] = $strCuenta;
		$data["direccion"] = $strDireccion;
		$data["empresa"] = $Empresa;
		$data["lista"] = $strLista;
		$data["factura"] = $strCont;
		$data["nomina"] = '<b>NOMINA: </b>' . $strNomina;
		$data['usuario'] = $strUsuario;
		$data['lugar'] = $lugar;
		$data['Nivel'] = $this -> session -> userdata('nivel');
		$data['Aceptado'] = $Aceptado;
		$data['Conectado'] = $this -> session -> userdata('usuario');
		
		$this -> load -> view("reportes/afiliadesafilia", $data);

	}

	function Imprimir_Factura2($strId = null, $strFactura = null) {
		$strNombre = '';
		$strBanco = '';
		$strCuenta = '';
		$strDireccion = '';
		$strLista = '';
		$Empresa = "";
		$strNomina = '';
		$data["cedula"] = $strId;
		$strUsuario = '';
		$strCobrado_Por = '';
		$dblSuma = 0;

		$descripcion = 'GASTOS POR SERVICIOS ADMINISTRATIVOS DE COBRANZA ';
		$Consulta = $this -> db -> query("SELECT * FROM t_personas WHERE documento_id='$strId'");
		if ($Consulta -> num_rows() != 0) {
			foreach ($Consulta->result() as $row) {
				$strNombre = $row -> primer_apellido . ' ' . $row -> segundo_apellido . ' ' . $row -> primer_nombre . ' ' . $row -> segundo_nombre;
				$strBanco = $row -> banco_1;
				$strCuenta = $row -> cuenta_1;
				$strBanco2 = $row -> banco_2;
				$strCuenta2 = $row -> cuenta_2;
				$strDireccion = 'AV. ' . $row -> avenida . ' CALLE ' . $row -> calle . ' MUNICIPIO ' . $row -> municipio . ' SECTOR ' . $row -> sector . ' CASA # ' . $row -> direccion;
				$Con = $this -> db -> query("SELECT * FROM t_clientes_creditos WHERE numero_factura ='$strFactura'");
				$strUsuario = $row -> codigo_nomina;
				if ($Con -> num_rows() != 0) {

					$strLista = '<table style="width:100%" border=1>
					<tr style="background-color:#ccc"><td><b>REFERENCIA</b></td><td><b>CUOTA</b></td>
					<td><b>PERIOCIDAD</b></td><td><b>MONTO</b></td><td><b>LINAJE</b></td> </tr>';
					foreach ($Con->result() as $rw) {
						$Empresa = $rw -> empresa;
						if ($rw -> forma_contrato == 4) {
							$descripcion = 'REFERENCIA (' . $rw -> contrato_id . ') POR  MONTO MENSUAL DEL DESCUENTO ';
							if ($rw -> periocidad == 2) {
								$descripcion = 'REFERENCIA (' . $rw -> contrato_id . ') POR MONTO QUINCENAL DEL DESCUENTO ';
							}
						}
						$Aceptado = $rw -> estatus;
						$strNomina = $rw -> nomina_procedencia;
						$strCobrado_Por = $rw -> cobrado_en;
						$dblSuma = $rw -> monto_operacion;
						$strLista = '<table style="width:100%" border=1>';

						if ($rw -> cobrado_en == $row -> banco_1 || $rw -> cobrado_en == 'NOMINA' || $rw -> cobrado_en == 'CREDINFO' || $rw -> cobrado_en == 'INVERCRESA') {
							$strBanco = $row -> banco_1;
							$strCuenta = $row -> cuenta_1;
						} else {
							$strBanco = $row -> banco_2;
							$strCuenta = $row -> cuenta_2;
						}

					}
				}
				$otro_monto = $dblSuma * 0.3;
				$strLista .= '</table><br>
				DETALLES: <b> ' . $descripcion . number_format($otro_monto, 2, ".", ",") . ' Bs.</b><br><br>';
			}
		} else {

		}
		$data["nombre"] = $strNombre;
		$data["banco"] = $strBanco;
		$data["cuenta"] = $strCuenta;
		$data["direccion"] = $strDireccion;
		$data["empresa"] = $Empresa;
		$data["lista"] = $strLista;
		$data["factura"] = $strFactura;
		$data["nomina"] = '<b>NOMINA: </b>' . $strNomina;
		$data['usuario'] = $strUsuario;
		$data['Nivel'] = $this -> session -> userdata('nivel');
		$data['Aceptado'] = $Aceptado;

		$this -> load -> view("reportes/afiliadesafilia", $data);

	}

	function Imprimir_Contrato($strId = null, $strFactura = null) {
		$strNombre = '';
		$strBanco = '';
		$strCuenta = '';
		$strDireccion = '';
		$strLista = '';
		$Empresa = "";
		$strNomina = '';
		$data["cedula"] = $strId;
		$strUsuario = '';
		$strCobrado_Por = '';
		$dblSuma = 0;

		$descripcion = 'MONTO TOTAL ACREDITADO ';
		$Consulta = $this -> db -> query("SELECT * FROM t_personas WHERE documento_id='$strId'");
		if ($Consulta -> num_rows() != 0) {
			foreach ($Consulta->result() as $row) {
				$strNombre = $row -> primer_apellido . ' ' . $row -> segundo_apellido . ' ' . $row -> primer_nombre . ' ' . $row -> segundo_nombre;
				$strBanco = $row -> banco_1;
				$strCuenta = $row -> cuenta_1;
				$strDireccion = 'AV. ' . $row -> avenida . ' CALLE ' . $row -> calle . ' MUNICIPIO ' . $row -> municipio . ' SECTOR ' . $row -> sector . ' CASA # ' . $row -> direccion;
				$Con = $this -> db -> query("SELECT * FROM t_clientes_creditos WHERE contrato_id ='$strFactura'");
				$strUsuario = $row -> codigo_nomina;
				if ($Con -> num_rows() != 0) {

					$strLista = '<table style="width:100%" border=1>
					<tr style="background-color:#ccc"><td><b>REFERENCIA</b></td><td><b>CUOTA</b></td>
					<td><b>PERIOCIDAD</b></td><td><b>MONTO</b></td><td><b>LINAJE</b></td> </tr>';
					foreach ($Con->result() as $rw) {
						$Empresa = $rw -> empresa;
						if ($rw -> forma_contrato == 4) {
							$descripcion = 'REFERENCIA (' . $rw -> contrato_id . ') POR  MONTO MENSUAL DEL DESCUENTO ';
							if ($rw -> periocidad == 2) {
								$descripcion = 'REFERENCIA (' . $rw -> contrato_id . ') POR MONTO QUINCENAL DEL DESCUENTO ';
							}
						}
						$Aceptado = $rw -> estatus;
						$strNomina = $rw -> nomina_procedencia;
						$strCobrado_Por = $rw -> cobrado_en;
						if ($rw -> forma_contrato > 3) {

							$dblSuma = $rw -> monto_cuota;
							$strLista = '<table style="width:100%" border=1>';
						} else {
							$strContrato = 'UNICO';
							$ano = substr($rw -> fecha_inicio_cobro, 0, 4);
							$mes = substr($rw -> fecha_inicio_cobro, 5, 2);

							$strPericidad = '';
							switch ($rw->periocidad) {
								case 0 :
									$strPericidad = 'SEMANAL ';
									break;
								case 1 :
									$strPericidad = 'CATORCENAL ';
									break;
								case 2 :
									$strPericidad = 'QUINCENAL 15 - 30 ';
									break;
								case 3 :
									$strPericidad = 'QUINCENAL 10 - 25 ';
									break;
								case 4 :
									$strPericidad = 'MENSUAL ';
									break;
							}
							switch ($rw->forma_contrato) {
								case 0 :
									$strContrato = $strPericidad;
									break;
								case 1 :
									$strContrato = 'DESDE 1 ' . $this -> MES_ACTIVO($mes) . '  DE HASTA  EL 30 ' . $this -> MES_ACTIVO($mes) . '  DEL ' . $ano;
									break;
								case 2 :
									$strContrato = 'DESDE 1 ' . $this -> MES_ACTIVO($mes) . '  DE HASTA  EL 30 ' . $this -> MES_ACTIVO($mes) . ' DEL ' . $ano;
									break;
								case 3 :
									$strContrato = 'EXTRA DESDE 1 ' . $this -> MES_ACTIVO($mes) . '  DE HASTA  EL 30 ' . $this -> MES_ACTIVO($mes) . ' DEL ' . $ano;
									break;
								default :
									$strContrato = 'DESDE 1 ' . $this -> MES_ACTIVO($mes) . ' DE HASTA  EL 30 ' . $this -> MES_ACTIVO($mes) . ' DEL ' . $ano;
							}

							$strLista .= '<tr><td>' . $rw -> contrato_id . '</td><td>' . $rw -> numero_cuotas . '</td>
							<td> ' . $strContrato . ' </td>
							<td><b>' . number_format($rw -> monto_cuota, 2, ".", ",") . ' Bs.</b></td>
							<td>' . $rw -> cobrado_en . '</td></tr>';
							$dblSuma += $rw -> monto_total;

						}
					}

				}
				$strLista .= '</table><br>
				DETALLES: <b> ' . $descripcion . number_format($dblSuma, 2, ".", ",") . ' Bs.</b><br><br>';
			}
		} else {

		}
		$data["nombre"] = $strNombre;
		$data["banco"] = $strBanco;
		$data["cuenta"] = $strCuenta;
		$data["direccion"] = $strDireccion;
		$data["empresa"] = $Empresa;
		$data["lista"] = $strLista;
		$data["factura"] = $strFactura;
		$data["nomina"] = '<b>NOMINA: </b>' . $strNomina;
		$data['usuario'] = $strUsuario;
		$data['Nivel'] = $_SESSION['nivel'];
		$data['Aceptado'] = $Aceptado;

		$this -> load -> view("nomina/afiliadesafilia", $data);

	}

	function Imprimir_Compromiso($factura = null, $ced = null, $tipo_voucher = '') {
		$strLista = '';
		$empresa = '';
		$strCedula = '';
		$strNombre = '';
		$strDireccion = '';
		$mesI = 0;
		$anoI = 0;
		$mesF = 0;
		$anoF = 0;
		$strBanco = '';
		$strCuenta = '';
		$tipoCuenta = '';
		$strUbicacion = '';
		$dia= '';
		$mes = '';
		$ano='';
		$tipoPago ='';
		
		$banco1 = "";
		$cuenta1 = "";
		$tcbanco1 = "";
		$banco2 = "";
		$cuenta2 = "";
		$tcbanco2 = "";
		
		$ncuota = 0;
		$ncuota_u = 0;
		$nboucher = 0;
		$periocidad = "";
		
		
		$persona = "SELECT * FROM t_personas WHERE documento_id ='".$ced."' ";
		$Consulta = $this -> db -> query($persona);
		if ($Consulta -> num_rows() != 0) {
			foreach ($Consulta->result() as $row) {
				$strCedula = $row -> documento_id;
				$strNombre = $row -> primer_apellido . ' ' . $row -> segundo_apellido . ' ' . $row -> primer_nombre . ' ' . $row -> segundo_nombre;
				$banco1 = $row -> banco_1;
				$cuenta1 = $row -> cuenta_1;
				$tcbanco1 = $row -> tipo_cuenta_1;
				$banco2 = $row -> banco_2;
				$cuenta2 = $row -> cuenta_2;
				$tcbanco2 = $row -> tipo_cuenta_2;
			}
		}
		
		
		$query = "SELECT * FROM t_clientes_creditos	WHERE t_clientes_creditos.numero_factura='$factura'";
		$Consulta = $this -> db -> query($query);
		$monto = 0;
		$bandera = 0;
		$mes = '';
		if ($Consulta -> num_rows() != 0) {
			foreach ($Consulta->result() as $row) {
				$strUbicacion = $row -> codigo_n;
				if($row -> forma_contrato == 0){
					$periocidad = $row -> periocidad;
					$bandera++;
					$ncuota_u +=  $row -> numero_cuotas;
					$fecha = explode('-',$row->fecha_solicitud);
					$fecha_inicio = explode('-',$row->fecha_inicio_cobro);
					$dia = $fecha[2];
					$mes = $fecha[1];
					$ano = $fecha[0];
					$mesI = $fecha_inicio[1];
					$anoI = $fecha_inicio[0];
					$empresa = $row -> empresa;
					$tipoPago = $row -> marca_consulta;
					if($row -> marca_consulta == 6){
						$query2 = "SELECT * FROM t_lista_voucher	WHERE cid ='$factura'";
						$Con = $this -> db -> query($query2);
						if ($Con -> num_rows() != 0) {
							$nboucher = $Con -> num_rows(); 
						}
					}		
				}
				if($row -> forma_contrato == 4 && $bandera==0){
					$ncuota_u +=  $row -> numero_cuotas;
					$fecha = explode('-',$row->fecha_solicitud);
					$fecha_inicio = explode('-',$row->fecha_inicio_cobro);
					$dia = $fecha[2];
					$mes = $fecha[1];
					$ano = $fecha[0];
					$mesI = $fecha_inicio[1];
					$anoI = $fecha_inicio[0];
					$empresa = $row -> empresa;
					$tipoPago = $row -> marca_consulta;
					if($row -> marca_consulta == 6){
						$query2 = "SELECT * FROM t_lista_voucher	WHERE cid ='$factura'";
						$Con = $this -> db -> query($query2);
						if ($Con -> num_rows() != 0) {
							$nboucher = $Con -> num_rows(); 
						}
					}
				}
				$monto += $row -> monto_total;
				$ncuota +=  $row -> numero_cuotas;
				if ($row -> cobrado_en == $banco1 || $row -> cobrado_en == 'NOMINA' || $row -> cobrado_en == 'CREDINFO' || $row -> cobrado_en == 'INVERCRESA') {
					$strBanco = $banco1;
					$strCuenta = $cuenta1;
					$tipoCuenta = $tcbanco1;
				} else {
					$strBanco = $banco2;
					$strCuenta = $cuenta2;
					$tipoCuenta = $tcbanco2;
				}
				
				
			}
		}


		
		$montoL = strtoupper($this -> ValorEnLetras($monto, ''));
		$monto = number_format($monto, 2, ".", ",") ;
		$data['cant'] = $ncuota;
		$data['boucher'] = $nboucher;
		$data["nombre"] = $strNombre;
		$data["cedula"] = $strCedula;
		$data['monto'] = $monto;
		$data['montoLetras'] = $montoL;
		//$data['montoC'] = $montoC;
		//$data['montoCLetras'] = $montoCL;
		$data["empresa"] = $empresa;
		$data["factura"] = $factura;
		
		$data['usuario'] = $this -> session -> userdata('usuario');
		$data['Nivel'] = $this -> session -> userdata('nivel');
		$data['tipo_pago'] = $tipoPago;
		$data['periocidad'] = 'mensuales consecutivos';
		
		$div = 1;
		if($periocidad == 2 || $periocidad == 3){
			$data['periocidad'] = 'quincenales consecutivos';
			$div = 2;
		}
		if($tipoPago != 6){
			$mesF = $mesI + ($ncuota_u / $div) - 1;
			$anoF = $anoI;	
		}else{
			$mesF = $mesI + ($nboucher / $div) - 1;
			$anoF = $anoI;
		}
		
		if($mesF > 48){
			$anoF = $anoF + 4;
			$mesF = $mesF - 48;
		}
		if($mesF > 36){
			$anoF = $anoF + 3;
			$mesF = $mesF - 36;
		}
		if($mesF > 24){
			$anoF = $anoF + 2;
			$mesF = $mesF - 24;
		}
		if($mesF > 12){
			$anoF = $anoF + 1;
			$mesF = $mesF - 12;
		}
		
		
		
		$data['dia'] = $dia;
		$data['mes'] = $this -> mes_letras($mes);
		$data['ano'] = $ano;
		$data['ubicacion'] = $this -> lugar($strUbicacion);
		$data['mesI'] = $this -> mes_letras($mesI);
		$data['anoI']= $anoI;
		$data['mesF'] = $this -> mes_letras($mesF);
		$data['anoF']=$anoF;
		$data['banco'] = $strBanco;
		$data['cuenta'] = $strCuenta;
		$data['tipoC'] = $tipoCuenta;
		$data['tipo_voucher'] = $tipo_voucher;
		$this -> load -> view("reportes/acta_compromiso", $data);
	}

	function Imprimir_Compromiso_Factura($factura = null) {
		$strLista = '';
		$empresa = '';
		$strCedula = '';
		$strNombre = '';
		$strDireccion = '';
		$ncuota = 0;
		$monto = 0;
		$query = "
			SELECT * FROM t_personas 
			join t_clientes_creditos ON t_clientes_creditos.documento_id = t_personas.documento_id
			WHERE t_clientes_creditos.numero_factura='$factura' limit 1
		";
		$Consulta = $this -> db -> query($query);
		if ($Consulta -> num_rows() != 0) {
			foreach ($Consulta->result() as $row) {
				//$contrato = $row -> contrato_id;
				$strCedula = $row -> documento_id;
				$strNombre = $row -> primer_apellido . ' ' . $row -> segundo_apellido . ' ' . $row -> primer_nombre . ' ' . $row -> segundo_nombre;
				$empresa = $row -> empresa;
				//$monto = number_format($row -> monto_cuota, 2, ".", ",") . ' Bs.';
				
				$strDireccion = 'AV. ' . $row -> avenida . ' CALLE ' . $row -> calle . ' MUNICIPIO ' . $row -> municipio . ' SECTOR ' . $row -> sector . ' CASA # ' . $row -> direccion;
				$query2 = "SELECT * FROM t_lista_voucher	WHERE cid ='$factura'";
				$Con = $this -> db -> query($query2);
				$strUsuario = $row -> codigo_nomina;
				if ($Con -> num_rows() != 0) {
					$strLista .= '<h5>Factura ' . $factura . '</h5><table style="width:100%" border=1>
					<tr style="background-color:#ccc"><td><b>REFERENCIA</b></td><td><b>PERIODO</b></td><td><b>MONTO</b></td></tr>';
					foreach ($Con->result() as $row2) {
						$ncuota ++;
						$monto +=  $row2 -> monto;
						$fecha = explode('-', $row2 -> fecha);
						$periodo = $this -> FMes($fecha[1]);
						$strLista .= '<tr><td>' . $row2 -> ndep . '</td><td>DEL &nbsp;&nbsp;&nbsp;&nbsp;<b>01</b>&nbsp;&nbsp;&nbsp;&nbsp;AL&nbsp;&nbsp;&nbsp;&nbsp; <b>' . $periodo[0] . '</b>&nbsp;&nbsp;&nbsp;&nbsp;DEL MES ' . $periodo[1] . ' DEL A&Ntilde;O ' . $fecha[0] . '</td><td>' . $row2 -> monto . '</td></tr>';
					}
					$strLista .= '<tr><td colspan=2 align=right>MONTO TOTAL DE VOUCHERS</td><td>'. number_format($monto,2) . '</td></tr></table>';
				} else {
					$strLista .= '<h5>CONTRATO ' . $contrato . ' NO TIENE VOUCHER ASOCIADO</h5>';
				}

			}
		}
		$data['cant'] = $ncuota;
		$data["nombre"] = $strNombre;
		$data["cedula"] = $strCedula;
		$data["direccion"] = $strDireccion;
		$data["empresa"] = $empresa;
		$data["lista"] = $strLista;
		$data["contrato"] = '';
		$data["factura"] = $factura;
		$data['usuario'] = $strUsuario;
		$data['Nivel'] = $this -> session -> userdata('nivel');
		
		$this -> load -> view("reportes/compromiso", $data);
	}

	function FMes($mes) {
		$msj = '';
		switch ($mes) {
			case  1 :
				return array(0 => '31', 1 => 'ENERO');
			case  2 :
				return array(0 => '28', 1 => 'FEBRERO');
			case  3 :
				return array(0 => '31', 1 => 'MARZO');
			case  4 :
				return array(0 => '30', 1 => 'ABRIL');
			case  5 :
				return array(0 => '31', 1 => 'MAYO');
			case  6 :
				return array(0 => '30', 1 => 'JUNIO');
			case  7 :
				return array(0 => '31', 1 => 'JULIO');
			case  8 :
				return array(0 => '31', 1 => 'AGOSTO');
			case  9 :
				return array(0 => '30', 1 => 'SEPTIEMBRE');
			case 10 :
				return array(0 => '31', 1 => 'OCTUBRE');
			case 11 :
				return array(0 => '30', 1 => 'NOVIEMBRE');
			case 12 :
				return array(0 => '31', 1 => 'DICIEMBRE');
		}
	}
	
	public function lugar($lugar) {
		switch ($lugar) {
			case 'BARINAS' :
				$ente = 'BARINAS';
				break;
			case 'Barina (Principal)' :
				$ente = 'BARINAS';
				break;
			case 'EL VIGIA' :
				$ente = 'MERIDA';
				break;
			case 'El Vigia (Principal)' :
				$ente = 'MERIDA';
				break;
			case 'Merida (Principal)' :
				$ente = 'MERIDA';
				break;
			case 'Merida (Las Tejas)' :
				$ente = 'MERIDA';
				break;
			case 'CAJA SECA' :
				$ente = 'ZULIA';
				break;
			case 'SAN CRISTOBAL' :
				$ente = 'TACHIRA';
				break;
			case 'Sancristobal (Principal)' :
				$ente = 'TACHIRA';
				break;
			case 'Sancristobal (Palmira)' :
				$ente = 'TACHIRA';
				break;
			case 'SANTA BARBARA' :
				$ente = 'ZULIA';
				break;
			case 'Santa Barbara Del Zulia (Principal)' :
				$ente = 'ZULIA';
				break;
			case 'Zulia (Principal)' :
				$ente = 'ZULIA';
				break;
			default :
				$ente = $lugar;
				break;
		}
		return $ente;
	}

	public function mes_letras($mesN) {
		switch ($mesN) {
			case '1' :
				$mes = "ENERO";
				break;
			case '2' :
				$mes = "FEBRERO";
				break;
			case '3' :
				$mes = "MARZO";
				break;
			case '4' :
				$mes = "ABRIL";
				break;
			case '5' :
				$mes = "MAYO";
				break;
			case '6' :
				$mes = "JUNIO";
				break;
			case '7' :
				$mes = "JULIO";
				break;
			case '8' :
				$mes = "AGOSTO";
				break;
			case '9' :
				$mes = "SEPTIEMBRE";
				break;
			case '10' :
				$mes = "OCTUBRE";
				break;
			case '11' :
				$mes = "NOVIEMBRE";
				break;
			case '12' :
				$mes = "DICIEMBRE";
				break;
			default :
				$mes = '';
				break;
		}
		return $mes;
	}

	function ValorEnLetras($x, $Moneda) {
		$s = "";
		$Ent = "";
		$Frc = "";
		$Signo = "";

		if (floatVal($x) < 0)
			$Signo = $this -> Neg . " ";
		else
			$Signo = "";

		if (intval(number_format($x, 2, '.', '')) != $x)//<- averiguar si tiene decimales
			$s = number_format($x, 2, '.', '');
		else
			$s = number_format($x, 0, '.', '');

		$Pto = strpos($s, $this -> Dot);

		if ($Pto === false) {
			$Ent = $s;
			$Frc = $this -> Void;
		} else {
			$Ent = substr($s, 0, $Pto);
			$Frc = substr($s, $Pto + 1);
		}

		if ($Ent == $this -> Zero || $Ent == $this -> Void)
			$s = "Cero ";
		elseif (strlen($Ent) > 7) {
			$s = $this -> SubValLetra(intval(substr($Ent, 0, strlen($Ent) - 6))) . "Millones " . $this -> SubValLetra(intval(substr($Ent, -6, 6)));
		} else {
			$s = $this -> SubValLetra(intval($Ent));
		}

		if (substr($s, -9, 9) == "Millones " || substr($s, -7, 7) == "Millón ")
			$s = $s . "de ";

		$s = $s . $Moneda;

		if ($Frc != $this -> Void) {
			$s = $s . " Con " . $this -> SubValLetra(intval($Frc)) . "Centimos";
			//$s = $s . " " . $Frc . "/100";
		}
		return ($Signo . $s . "");

	}

	function SubValLetra($numero) {
		$Ptr = "";
		$n = 0;
		$i = 0;
		$x = "";
		$Rtn = "";
		$Tem = "";

		$x = trim("$numero");
		$n = strlen($x);

		$Tem = $this -> Void;
		$i = $n;

		while ($i > 0) {
			$Tem = $this -> Parte(intval(substr($x, $n - $i, 1) . str_repeat($this -> Zero, $i - 1)));
			If ($Tem != "Cero")
				$Rtn .= $Tem . $this -> SP;
			$i = $i - 1;
		}

		//--------------------- GoSub FiltroMil ------------------------------
		$Rtn = str_replace(" Mil Mil", " Un Mil", $Rtn);
		while (1) {
			$Ptr = strpos($Rtn, "Mil ");
			If (!($Ptr === false)) {
				If (!(strpos($Rtn, "Mil ", $Ptr + 1) === false))
					$this -> ReplaceStringFrom($Rtn, "Mil ", "", $Ptr);
				Else
					break;
			} else
				break;
		}

		//--------------------- GoSub FiltroCiento ------------------------------
		$Ptr = -1;
		do {
			$Ptr = strpos($Rtn, "Cien ", $Ptr + 1);
			if (!($Ptr === false)) {
				$Tem = substr($Rtn, $Ptr + 5, 1);
				if ($Tem == "M" || $Tem == $this -> Void)
					;
				else
					$this -> ReplaceStringFrom($Rtn, "Cien", "Ciento", $Ptr);
			}
		} while(!($Ptr === false));

		//--------------------- FiltroEspeciales ------------------------------
		$Rtn = str_replace("Diez Un", "Once", $Rtn);
		$Rtn = str_replace("Diez Dos", "Doce", $Rtn);
		$Rtn = str_replace("Diez Tres", "Trece", $Rtn);
		$Rtn = str_replace("Diez Cuatro", "Catorce", $Rtn);
		$Rtn = str_replace("Diez Cinco", "Quince", $Rtn);
		$Rtn = str_replace("Diez Seis", "Dieciseis", $Rtn);
		$Rtn = str_replace("Diez Siete", "Diecisiete", $Rtn);
		$Rtn = str_replace("Diez Ocho", "Dieciocho", $Rtn);
		$Rtn = str_replace("Diez Nueve", "Diecinueve", $Rtn);
		$Rtn = str_replace("Veinte Un", "Veintiun", $Rtn);
		$Rtn = str_replace("Veinte Dos", "Veintidos", $Rtn);
		$Rtn = str_replace("Veinte Tres", "Veintitres", $Rtn);
		$Rtn = str_replace("Veinte Cuatro", "Veinticuatro", $Rtn);
		$Rtn = str_replace("Veinte Cinco", "Veinticinco", $Rtn);
		$Rtn = str_replace("Veinte Seis", "Veintiseís", $Rtn);
		$Rtn = str_replace("Veinte Siete", "Veintisiete", $Rtn);
		$Rtn = str_replace("Veinte Ocho", "Veintiocho", $Rtn);
		$Rtn = str_replace("Veinte Nueve", "Veintinueve", $Rtn);

		//--------------------- FiltroUn ------------------------------
		If (substr($Rtn, 0, 1) == "M")
			$Rtn = "Un " . $Rtn;
		//--------------------- Adicionar Y ------------------------------
		for ($i = 65; $i <= 88; $i++) {
			If ($i != 77)
				$Rtn = str_replace("a " . Chr($i), "* y " . Chr($i), $Rtn);
		}
		$Rtn = str_replace("*", "a", $Rtn);
		return ($Rtn);
	}

	function ReplaceStringFrom(&$x, $OldWrd, $NewWrd, $Ptr) {
		$x = substr($x, 0, $Ptr) . $NewWrd . substr($x, strlen($OldWrd) + $Ptr);
	}

	function Parte($x) {
		$Rtn = '';
		$t = '';
		$i = '';
		Do {
			switch($x) {
				Case 0 :
					$t = "Cero";
					break;
				Case 1 :
					$t = "Un";
					break;
				Case 2 :
					$t = "Dos";
					break;
				Case 3 :
					$t = "Tres";
					break;
				Case 4 :
					$t = "Cuatro";
					break;
				Case 5 :
					$t = "Cinco";
					break;
				Case 6 :
					$t = "Seis";
					break;
				Case 7 :
					$t = "Siete";
					break;
				Case 8 :
					$t = "Ocho";
					break;
				Case 9 :
					$t = "Nueve";
					break;
				Case 10 :
					$t = "Diez";
					break;
				Case 20 :
					$t = "Veinte";
					break;
				Case 30 :
					$t = "Treinta";
					break;
				Case 40 :
					$t = "Cuarenta";
					break;
				Case 50 :
					$t = "Cincuenta";
					break;
				Case 60 :
					$t = "Sesenta";
					break;
				Case 70 :
					$t = "Setenta";
					break;
				Case 80 :
					$t = "Ochenta";
					break;
				Case 90 :
					$t = "Noventa";
					break;
				Case 100 :
					$t = "Cien";
					break;
				Case 200 :
					$t = "Doscientos";
					break;
				Case 300 :
					$t = "Trescientos";
					break;
				Case 400 :
					$t = "Cuatrocientos";
					break;
				Case 500 :
					$t = "Quinientos";
					break;
				Case 600 :
					$t = "Seiscientos";
					break;
				Case 700 :
					$t = "Setecientos";
					break;
				Case 800 :
					$t = "Ochocientos";
					break;
				Case 900 :
					$t = "Novecientos";
					break;
				Case 1000 :
					$t = "Mil";
					break;
				Case 1000000 :
					$t = "Millón";
					break;
			}

			If ($t == $this -> Void) {
				$i = $i + 1;
				$x = $x / 1000;
				If ($x == 0)
					$i = 0;
			} else
				break;

		} while($i != 0);

		$Rtn = $t;
		Switch($i) {
			Case 0 :
				$t = $this -> Void;
				break;
			Case 1 :
				$t = " Mil";
				break;
			Case 2 :
				$t = " Millones";
				break;
			Case 3 :
				$t = " Billones";
				break;
		}
		return ($Rtn . $t);
	}
	/**
	 * Generar Reportes de Domiciliacion Por Contrato
	 */

	function Imprimir($iCedula, $sContrato) {
		$this -> descripcion = 'MONTO TOTAL ACREDITADO ';
		$Consulta = $this -> db -> query('SELECT
		CONCAT(primer_apellido,\' \',LEFT(segundo_apellido,1),\'. \',primer_nombre,\' \', LEFT(segundo_nombre,1),\'. \') AS mom,
		CONCAT(\'AV.\', avenida,\' CALLE \', calle,\' MUNICIPIO \', municipio,\' SECTOR \', sector,\' CASA # \', direccion) AS dir,
		banco_1, cuenta_1,CONCAT(codigo_n,\' (\', expediente_c, \')\') AS Responsable, contrato_id, empresa, forma_contrato,estatus,
		nomina_procedencia, cobrado_en AS linaje,fecha_inicio_cobro, monto_cuota, numero_cuotas
		FROM t_personas JOIN t_clientes_creditos ON (t_personas.documento_id=t_clientes_creditos.documento_id)
		WHERE t_personas.documento_id=' . $iCedula . ' AND t_clientes_creditos.contrato_id = \'' . $sContrato . '\' LIMIT 1');
		if ($Consulta -> num_rows() != 0) {
			$oCabezera[1] = array("titulo" => "REFERENCIA");
			$oCabezera[2] = array("titulo" => "CUOTAS");
			$oCabezera[3] = array("titulo" => "PERIOCIDAD");
			$oCabezera[4] = array("titulo" => "MONTO");
			$oCabezera[5] = array("titulo" => "LINAJE");
			$rs = $Consulta -> result();
			$this -> nombre = $rs[0] -> nom;
			$this -> Banco = $rs[0] -> banco_1;
			$this -> cuenta = $rs[0] -> cuenta_1;
			$this -> direccion = $rs[0] -> dir;
			$this -> usuario = $rs[0] -> responsable;
			$Aceptado = $rs[0] -> estatus;
			$this -> nomina = $rs[0] -> nomina_procedencia;
			$this -> linaje = $rs[0] -> linaje;
			$i = 0;
			foreach ($rs as $rw) {
				++$i;
				if ($rw -> forma_contrato == 4) {
					$this -> descripcion = 'REFERENCIA (' . $rw -> contrato_id . ') POR  MONTO MENSUAL DEL DESCUENTO ';
					if ($row -> periocidad == 2) {
						$this -> descripcion = 'REFERENCIA (' . $rw -> contrato_id . ') POR MONTO QUINCENAL DEL DESCUENTO ';
					}
					if ($rw -> forma_contrato > 3) {
						$this -> total_suma = $rw -> monto_cuota;
					} else {
						$strContrato = 'UNICO ';
						$ano = substr($rw -> fecha_inicio_cobro, 0, 4);
						$mes = substr($rw -> fecha_inicio_cobro, 5, 2);
						$strPericidad = '';
						switch ($rw->periocidad) {
							case 0 :
								$strPericidad = 'SEMANAL ';
								break;
							case 1 :
								$strPericidad = 'CATORCENAL ';
								break;
							case 2 :
								$strPericidad = 'QUINCENAL 15 - 30 ';
								break;
							case 3 :
								$strPericidad = 'QUINCENAL 10 - 25 ';
								break;
							case 4 :
								$strPericidad = 'MENSUAL ';
								break;
						}
						switch ($rw->forma_contrato) {
							case 0 :
								$strContrato = $strPericidad;
								break;
							case 1 :
								$strContrato = 'DESDE 1 ' . $this -> Mes_Activo($mes) . '  DE HASTA  EL 30 ' . $this -> Mes_Activo($mes) . '  DEL ' . $ano;
								break;
							case 2 :
								$strContrato = 'DESDE 1 ' . $this -> Mes_Activo($mes) . '  DE HASTA  EL 30 ' . $this -> Mes_Activo($mes) . ' DEL ' . $ano;
								break;
							case 3 :
								$strContrato = 'EXTRA DESDE 1 ' . $this -> Mes_Activo($mes) . '  DE HASTA  EL 30 ' . $this -> Mes_Activo($mes) . ' DEL ' . $ano;
								break;
							default :
								$strContrato = 'DESDE 1 ' . $this -> Mes_Activo($mes) . ' DE HASTA  EL 30 ' . $this -> Mes_Activo($mes) . ' DEL ' . $ano;
						}
						$this -> total_suma += $rw -> monto_total;
						$oFil[$i] = array("1" => $rw -> contrato_id, "2" => $rw -> numero_cuotas, "3" => $strPericidad, "4" => number_format($rw -> monto_cuota, 2, ".", ","), "5" => $rw -> linaje);
					}
				}
			}
		}
		$oTable = array("Cabezera" => $oCabezera, "Cuerpo" => $oFil, "Css" => "", "Obejtos" => Array(), "Origen" => "json");
		$this -> lista = json_encode($oTable);
		$this -> detalles = ' DETALLES: ' . $this -> descripcion . number_format($this -> total_suma, 2, ".", ",") . ' Bs.';
	}

	public function Mes_Activo($iMes) {
		$mes = "";
		switch ($iMes) {
			case 1 :
				$mes = "ENERO";
				break;
			case 2 :
				$mes = "FEBRERO";
				break;
			case 3 :
				$mes = "MARZO";
				break;
			case 4 :
				$mes = "ABRIL";
				break;
			case 5 :
				$mes = "MAYO";
				break;
			case 6 :
				$mes = "JUNIO";
				break;
			case 7 :
				$mes = "JULIO";
				break;
			case 8 :
				$mes = "AGOSTO";
				break;
			case 9 :
				$mes = "SEPTIEMBRE";
				break;
			case 10 :
				$mes = "OCTUBRE";
				break;
			case 11 :
				$mes = "NOVIEMBRE";
				break;
			case 12 :
				$mes = "DICIEMBRE";
				break;
		}
		return $mes;
	}

}
?>