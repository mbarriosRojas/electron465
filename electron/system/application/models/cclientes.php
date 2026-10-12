<?php
/**
 *  @author Carlos Enrique Pena Albarran
 *  @package SaGem.system.application.model
 *  @version 1.0.0
 */
class CClientes extends Model {

	function __construct() {
		parent::__construct();
	}

	function getEmpresa($intEmpresa = null) {
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

	//MOTIVO DEL CONVENIO
	function getMotivo($intMotivo = null) {
		switch ($intMotivo) {
			case 0 :
				return "NO DEFINIDO";
				break;
			case 1 :
				return "FINANCIAMIENTO";
				break;
			case 2 :
				return "PRESTAMO";
				break;
			case 3 :
				return "FINANCIAMIENTO Y PRESTAMO";
				break;

			default :
				return "";
				break;
		}
	}

	/* Objeto empleado de CodeIgniter
	 * @param CI_Empleado
	 */
	public function CI_Clientes($valor, $tipo_busqueda,$iLimit=null) {
		$strNivelAcceso = $this -> session -> userdata('nivel');
		$Especial = 0;
		$this -> load -> library("pagination");
		$iPor_Pagina=15;
		if ((int)$iLimit < 0) {
			$limit = "LIMIT 0, " . $iPor_Pagina;
		} else {
			$limit = "LIMIT " . $iLimit . ", " . $iPor_Pagina;
		}
		$set = "SQL_CALC_FOUND_ROWS nacionalidad, t_personas.documento_id, contrato_id, 
		primer_apellido,segundo_apellido, primer_nombre, segundo_nombre, codigo_nomina, 
		codigo_nomina_aux,expediente_caja, codigo_n_a,
		monto_total, nomina_procedencia,fecha_inicio_cobro,fecha_solicitud,	
		numero_factura,empresa,numero_cuotas,monto_cuota,
		motivo,forma_contrato,cobrado_en,fecha_verificado,expediente_c,monto_operacion, t_clientes_creditos.estatus";

		$paginador = "";
		$dblFactura = 0;
		$dblMonto = 0;
		$dblMontoOpera = 0;
		$dblResta = 0;
		$strReporte = "";
		$i = 0;
		$strAgregarPagos = "";
		switch ($tipo_busqueda) {
			case 'serialv':
				$strQuery = "SELECT $set FROM t_personas INNER JOIN t_clientes_creditos ON 
					t_personas.documento_id=t_clientes_creditos.documento_id
					WHERE t_clientes_creditos.serial like '%" . $valor . "%' ";
				break;
			case 'cheque':
				$strQuery = "SELECT $set FROM t_personas INNER JOIN t_clientes_creditos ON 
					t_personas.documento_id=t_clientes_creditos.documento_id
					WHERE t_clientes_creditos.num_operacion = '" . $valor . "' Or t_clientes_creditos.ncheque ='". $valor . "'
					Or t_clientes_creditos.observaciones like '%|". $valor . "|%'";
				break;
				
			case 'cedula':
				$strQuery = "SELECT $set FROM t_personas INNER JOIN t_clientes_creditos ON 
					t_personas.documento_id=t_clientes_creditos.documento_id	
					WHERE t_personas.documento_id='$valor' $limit";
				break;
			case 'contrato':
				$strQuery = "SELECT $set FROM t_personas INNER JOIN t_clientes_creditos ON 
					t_personas.documento_id=t_clientes_creditos.documento_id
					WHERE t_clientes_creditos.contrato_id like '" . $valor . "' $limit";
				break;
			case 'factura':
				$strQuery = "SELECT $set FROM t_personas INNER JOIN t_clientes_creditos ON 
					t_personas.documento_id=t_clientes_creditos.documento_id
					WHERE t_clientes_creditos.numero_factura like '" . $valor . "'  $limit";
				break;
			case 'cuenta':
					$strQuery = "SELECT $set FROM t_personas INNER JOIN t_clientes_creditos ON 
						t_personas.documento_id=t_clientes_creditos.documento_id
						WHERE t_personas.cuenta_1 like '%" . $valor . "%' ";
				break;
			
			default:
				
				break;
		}
		
		
		$Cedula_Id = '';
		 //echo "<br>" . $strQuery . "<br>";
		 
		$Consulta = $this -> db -> query($strQuery);
		$sTotal = $this -> db -> query("SELECT FOUND_ROWS() AS total");
		//LIMITE TOTAL DE LAS CONSULATAS

		$strReporte = "<ul id=\"icons\" class=\"ui-widget ui-helper-clearfix\"><table style=\"height:18px;width:750px;\" border=0
		class=\"ui-widget ui-widget-content\" cellspacing=\"2\" cellpadding=\"0\">
		<thead><tr class=\"ui-widget-header\" style=\"height:20px;\">
		<th>A</th><th>D</th><th>CEDULA</th><th>APELLIDOS Y NOMBRES</th><th>CONTRATO</th><th>TOTAL</th><th>RESTA</th><th>ESTATUS</th><th>NOM</th><th>TIPO</th>
		<th>CREADO</th><th>MODIFICADO</th>
		</tr></thead><tbody>";
		if ($Consulta -> num_rows() > 0) {
			foreach ($Consulta->result() as $row) {
				$i++;
				$motivo_v = $this -> getMotivo($row -> motivo);
			
				$Cedula_Id = $row -> documento_id;
				$factura_Id = $valor;
				$Consultar = "<p><a href=\"#1\" onClick=\"Consultar('" . $row -> documento_id . "','" . $row -> contrato_id . "');\" id=\"dialog_link\" class=\"ui-state-default ui-corner-all\">
					<span class=\"ui-icon ui-icon-circle-plus\"></span></a>";
				$Mostrar = "<p><a href=\"#1\" onClick=\"Mostrar_Detalles('$i');\" id=\"dialog_link\" class=\"ui-state-default ui-corner-all\">
				<span class=\"ui-icon ui-icon-circle-triangle-s\"></span></a>";
				$Ocultar = "<p><a href=\"#1\" onClick=\"Ocultar_Detalles('$i');\" id=\"dialog_link\" class=\"ui-state-default ui-corner-all\">
				<span class=\"ui-icon ui-icon-circle-triangle-n\"></span></a>";
				$dblRestaPorPagar = $this -> CI_Lista_Cobros($row -> documento_id, $row -> contrato_id, 0);
				if($row -> estatus != 3){
					$dblFactura += $row -> monto_total;
				}
				//$dblFactura += $row -> monto_total;
				$dblMontoOpera = $row -> monto_operacion;
				$estatus_contrato = "NUEVO";
				if($row -> estatus != 0 && $row -> estatus != ""){
					$estatus_contrato = $this -> Estatus_Creditos($row -> estatus);
				}
				$strReporte .= "<tr style='height:20px'></p>";
				$strReporte .= "<td style='width:18px'>
				$Consultar </td><td style='width:18px'> $Mostrar
				</td><td>&nbsp;<strong>" . $row -> nacionalidad . "</strong>" . $row -> documento_id . "</td><td>" . $row -> primer_apellido . " " . 
				$row -> segundo_apellido . " " . $row -> primer_nombre . " " . $row -> segundo_nombre . "</td><td>
				<strong>" . $row -> contrato_id . "</strong></td>
				<td align=right><strong>
				<font color='#1c94c4'>" . number_format($row -> monto_total, 2, ".", ",") . " Bs. </font></strong></td>
				<td align=right><strong>
				<font color='#ec8e0c'>" . $dblRestaPorPagar["resta"] . " Bs.</font></strong></td>
				<td><center>$estatus_contrato</center></td>
				<td  align=center>" . $this -> Descripcion_Nominas($row -> nomina_procedencia) . "</td>
				<td align='center'><strong>" . $this -> Tipo_Cuenta($row -> forma_contrato) . "</strong></td>
				<td align='center'>" . strtoupper($row -> expediente_caja) . "</td><td align='center'>" . strtoupper($row -> codigo_nomina_aux) . "</td>";
				if($row -> estatus != 3){
					$dblMonto += $row -> monto_total;
					$dblResta += $dblRestaPorPagar["total"];
				}
				//$dblMonto += $row -> monto_total;
				//$dblResta += $dblRestaPorPagar["total"];
				if ($row -> forma_contrato > 3) { $Especial = 1;
				}
				$strReporte .= "</tr>";
				$strReporte .= "<tr><td colspan=11>
					<div id=\"divDetalles$i\" style='display:none'>
						<br><center>
						<table style='width:95%' border=0  bgcolor='#FFFFFF' cellspacing=0>
							<tr><td></td><td colspan=3><br></td><td width:10px></td></tr><tr>
							<tr style='height:20px'><td style='width:5px'></td><td><strong>
							DETALLES GENERALES DEL CONTRATO (" . $motivo_v . ")</strong></td>
							<td style='width:18px' valing='top'>$Ocultar</td><td>&nbsp;</td></tr>
							<tr>							
							<td></td><td colspan=3><strong><font color='#1c94c4'>NOMINA: </font> </strong> " . $row -> nomina_procedencia . "</td></tr><tr>
							<td></td><td colspan=3><strong><font color='#1c94c4'>FECHA INICIO DEL COBRO: </font></strong> " . $row -> fecha_inicio_cobro . "&nbsp;
							<strong><font color='#1c94c4'>FECHA DE LA SOLICITUD: </font></strong> " . $row -> fecha_solicitud . "
							<strong><font color='#1c94c4'>FACTURA N&Uacute;MERO: </font></strong> " . $row -> numero_factura . "</td></tr><tr>
							<td></td><td colspan=3><strong><font color='#1c94c4'>EMPRESA DOMICILIO: </font></strong> " . $this -> getEmpresa($row -> empresa) . "
							<strong><font color='#1c94c4'>&nbsp;&nbsp;COBRADO POR: </font></strong> " . $row -> cobrado_en . "</td>
							</tr><tr>
							<td></td><td colspan=3><font color='#1c94c4'><b>CUOTAS A CANCELAR: </font> </b> " . $row -> numero_cuotas . "
							<font color='#1c94c4'><b>MONTO POR CUOTAS: </b></font></strong> " . number_format($row -> monto_cuota, 2, ".", ",") . " Bs.
							</td></tr>
							<tr>
							<td></td><td colspan=3 style='text-align:right'><strong><font color='#996633' style='font-size:9px'>CONTRATO CREADO:  
							</strong><i> &nbsp;&nbsp; " . strtoupper($row -> codigo_n_a) . "
							</i>&nbsp;&nbsp;<strong>FECHA DE EDICION: </strong> <i> " . $row -> fecha_verificado . "
							</i></font></td></tr>
						</table>
						</center>
						<br>
					</div>
					</td></tr>";
			}
		}
		$strReporte .= "</tbody><table></ul>";
		
		switch ($tipo_busqueda) {
			case 'cedula':
				$strReporte .= "<center><br><h2>
				Total de monto en creditos : <font color='#1c94c4'>" . number_format($dblMonto, 2, ".", ",") . " Bs. </font>
				Total de monto restantes :		<font color='#1c94c4'>" . number_format($dblResta, 2, ".", ",") . " Bs. </font>
			
				<a><a href='" . base_url() . "index.php/cooperativa/Imprimir_Estado_Cuenta/" . $Cedula_Id . "' target='_blank'>
				<h2><font color='#1c94c4'>[ Estado De Cuenta ]</font></h2></a>	
			
				</h2></center>";
				break;
			case 'contrato':
				if($strNivelAcceso == 0 || $strNivelAcceso < 4){
			  		$strReporte .= "<center><br><a href='" . base_url() . "index.php/cooperativa/Imprimir_Contratos/" . $Cedula_Id . "/" . $valor . "'" . " target='_blank'>
        			<h2>[ Imprimir Contrato Individual ]</h2></a>";
				}  
				if ($Especial > 0) {
					$strReporte .= "<center><br><a href='" . base_url() . "index.php/cooperativa/Imprimir_Contratos/" . $Cedula_Id . "/" . $valor . "'" . " target='_blank'>
				<h2>[ Imprimir Domiciliaci&oacute;n ]</h2></a>";
				}
				break;
			case 'factura':
				$otro_monto = $dblMontoOpera*0.3;
				$strReporte .= "<center><br><a href='" . base_url() . "index.php/cooperativa/Imprimir_Facturas/" . $Cedula_Id . "/" . $valor . "'" . " target='_blank'>
				<h2>Total del monto, factura # $valor :	<font color='#1c94c4'>" . number_format($dblFactura, 2, ".", ",") . " Bs.</font>
				</h2></a>				
					<a><a href='" . base_url() . "index.php/cooperativa/Imprimir_Control_Pago/" . $Cedula_Id . "/" . $valor . "'" . " target='_blank'>
				<h2><font color='#1c94c4'>[ Control de Pago ]</font></h2></a>	
					<a><a href='" . base_url() . "index.php/cooperativa/Ticket/" . $Cedula_Id . "/" . $valor . "'" . " target='_blank'>
					<h2><font color='#1c94c4'>[ Tickets de Promoci&oacute;n ]</font></h2></a>
					<a><a href='" . base_url() . "index.php/cooperativa/Voucher_Provincial/" .  $valor . "'" . " target='_blank'>
					<h2><font color='#1c94c4'>[ Imprimir Voucher Provincial ]</font></h2></a>
					<a><a href='" . base_url() . "index.php/cooperativa/Voucher_Bicentenario/" .  $valor . "'" . " target='_blank'>
					<h2><font color='#1c94c4'>[ Imprimir Voucher Bicentenario ]</font></h2></a>
						</center>";
				break;
			
			default:
				
				break;
		}

		$iTotal = 0;
		foreach ($sTotal->result() as $lstTotal) {
			$iTotal = $lstTotal -> total;
		}

		$sConfigurar['base_url'] = base_url() . 'index.php/cooperativa/procesar/';
		$sConfigurar['total_rows'] = $iTotal;
		$sConfigurar['per_page'] = $iPor_Pagina;

		$this -> pagination -> initialize($sConfigurar);

		$paginador = "<br><center>" . $this -> pagination -> create_links() . "</center>";

		return $strReporte . $paginador;
	}

	public function CI_Lista_Cobros($documento_id = null, $contrato_id = null, $nivel = "") {

		$dblMonto_Total = 0;
		$dblMonto_Acumulado = 0;
		$dblResta = 0;
		$fila = 0;
		$iFila = 0;
		// CONTAR SOLO SI ES UN MONTO MAYOR QUE CERO
		$strQuien = 0;
		//CONTAR SI ESTA MOROSO
		$iTotalLetras = 0;
		$strReporte["contenido"] = "";
		$strReporte["residuo"] = "";
		// Detalle del Pago HTML
		$strReporte["resta"] = "";
		//Resta Por Pagar DBL
		$numero_cuotas = 0;
		$monto_cuota = 0;
		$advertencia = "";
		$forma_contrato = 0;

		$strQuery = "SELECT * FROM t_personas INNER JOIN t_clientes_creditos ON t_personas.documento_id=t_clientes_creditos.documento_id
		WHERE t_personas.documento_id='$documento_id' AND t_clientes_creditos.contrato_id='$contrato_id'";

		$Consulta = $this -> db -> query($strQuery);

		if ($Consulta -> num_rows() > 0) {
			foreach ($Consulta->result() as $row) {
				$dblMonto_Total += $row -> monto_total;
				$numero_cuotas = $row -> numero_cuotas;
				$monto_cuota = $row -> monto_cuota;
				$forma_contrato = $row -> forma_contrato;

			}
		}
		$dblResta = $dblMonto_Total;
		$strQuery = "SELECT * ,ifnull(nomb,'SIN MODALIDAD') as MODALIDAD
		FROM t_lista_cobros 
		left join t_modalidad on t_modalidad.oid = t_lista_cobros.moda
		WHERE documento_id='$documento_id' AND credito_id='$contrato_id' ORDER BY fecha";
		$Consulta = $this -> db -> query($strQuery);
		
		$strReporte["contenido"] .=  "<center></div>
		<ul><table style=\"height:15px;width:100%;\" border=0
		class=\"ui-widget ui-widget-content\" cellspacing=\"3\" cellpadding=\"0\">
		<thead><tr class=\"ui-widget-header\" style=\"height:10px;\">
		<th></th><th>#</th><th>MES CUOTA</th><th>FECHA CUOTA</th><th>CUOTA</th><th>RESTANTE</th><th>FECHA DESCUENTO</th><th>DESCRIPCION</th><th>FECHA CARGA</th><th>MODALIDAD</th><th></th></tr></thead><tbody>";
		if ($Consulta -> num_rows() > 0) {
			foreach ($Consulta->result() as $row) {
				$fila++;
				if (($row -> monto) > 0) {
					++$iFila;
				}
				$dblResta = $dblResta - $row -> monto;
				$dblMonto_Acumulado += $row -> monto;
				$mes = $this -> Descripcion_Mes(substr($row -> fecha, 5, 2));
				$eliminar = "";
				if (($nivel == 0 || strtoupper($this -> session -> userdata('usuario')) == 'CARLOS') && $row -> mes == '') {
					
					$eliminar = "<p><a href=\"#\" onClick=\"Respaldo_Eliminar_Cuota('" . $row -> documento_id . "','" . $row -> credito_id . "','" . $row -> fecha . "','" . $row -> monto . "');\" >
															<span class=\"ui-icon ui-icon-circle-minus\"></span></a></p>";
				}
				if ($row -> monto != $monto_cuota) {
					$advertencia = "<span class=\"ui-icon ui-icon-alert\"></span>";
				} else {
					$advertencia = "";
				}

				$strReporte["contenido"] .= "<tr style='height:20px'>";
				$strReporte["contenido"] .= "<td>";
				$strReporte["contenido"] .= $eliminar . "</td><td>$fila</td><td>$mes</td>
				<td align=center>$row->fecha</td><td align=right>" . number_format($row -> monto, 2, ".", ",") . "</td>
				<td align=right><strong><p>" . number_format($dblResta, 2, ".", ",") . "</strong>";
				$strReporte["contenido"] .= "</p></td><td align=center>" . $row->farc . "</td><td align=right>" . $row -> descripcion . "</td>
				<td align=right>" . $row->modificado . "</td><td>".$row -> MODALIDAD."</td><td>" . $advertencia . "</td></tr>";
				$strReporte["contenido"] .= "<tr><td colspan=6>
					<div id=\"divDetalles\">
						
					</div>
					</td></tr>";

			}
		}

		$strReporte["contenido"] .= "</tbody><table></center></ul>";

		if ($dblMonto_Acumulado == 0) {
			$dblMonto = $dblMonto_Total;
		} else {
			$dblMonto = $dblMonto_Total - $dblMonto_Acumulado;
		}
		if (($fila - $iFila) > 2) {
			$strQuien = "<strong><font color=#ff0000>ESTATUS DEL CLIENTE MOROSO</font></strong>";
		} else {
			//$strQuien = "ACTUALMENTE SOLVENTE";
		}
		$resta = $numero_cuotas - $iFila;
		$strReporte["resta"] = number_format($dblMonto, 2, ".", ",");
		$strReporte["total"] = $dblMonto;
		if ($forma_contrato == 4) {
			//$dblMonto = $dblMonto * (-1);
			$strReporte["residuo"] = "<br>LETRAS A CANCELAR: " . $numero_cuotas . " | PAGADAS : " . $iFila . "<br>
			MONTO TOTAL: -" . number_format($dblMonto_Total, 2, ".", ",") . "Bs.<BR>
			 <i>PENDIENTE: -" . number_format($dblMonto, 2, ".", ",") . " Bs. | ABONADO: " . number_format(($dblMonto_Total - $dblMonto), 2, ".", ",") . " Bs.</i>
			" . $strQuien;

		} else {
			$strReporte["residuo"] = "<br>LETRAS A CANCELAR: " . $numero_cuotas . " | PAGADAS : <strong>" . $iFila . "<br>
			MONTO TOTAL: -" . number_format($dblMonto_Total, 2, ".", ",") . "Bs.<BR>
			<i>ABONADO: " . number_format(($dblMonto_Total - $dblMonto), 2, ".", ",") . " Bs. | RESTA: " . number_format($dblMonto, 2, ".", ",") . " Bs.</i>
			" . $strQuien;
		}
			
		return $strReporte;
	}

	/* Objeto empleado de CodeIgniter
	 * @param string Responsable en la nómina
	 * @param string Descripcion de la nomina
	 * @param string
	 * @param integer Acceso 0: SuperUsuario
	 * @param integer 0: Por aceptar 1: Aceptados 2: Rechazados
	 *
	 */
	public function CI_Clientes_Reportes($strDependencia = null, $dtd_nomina = null, $cobrado_en = null, $Nivel_Acceso = null, $iCreditos = null, $iLimit = null, $iPor_Pagina = null) {
		$this -> load -> library("pagination");

		$strReporte = "";
		$strContenido = "";
		$sBotones = "";
		$dblTotal = 0;
		$intI = 0;
		$i = 0;

		if ((int)$iLimit < 0) {
			$limit = "LIMIT 0, " . $iPor_Pagina;
		} else {
			$limit = "LIMIT " . $iLimit . ", " . $iPor_Pagina;
		}

		$set = "SQL_CALC_FOUND_ROWS nacionalidad, t_personas.documento_id, contrato_id, primer_apellido,segundo_apellido, 
		primer_nombre, segundo_nombre,	monto_total, nomina_procedencia,fecha_inicio_cobro,fecha_solicitud,	numero_factura,
		empresa,numero_cuotas,monto_cuota,motivo,forma_contrato,num_operacion,
		fecha_operacion,monto_operacion,cobrado_en,periocidad";

		if ($dtd_nomina == "TODOS") {
			$dtd_nomina = "%";
		}
		if ($cobrado_en == "TODOS") {
			$cobrado_en = "%";
		}
		//echo $iCreditos, $Nivel_Acceso, $strDependencia, $dtd_nomina;
		if ($strDependencia == "") {
			$strQuery = "SELECT $set FROM t_personas INNER JOIN t_clientes_creditos ON 
				t_personas.documento_id=t_clientes_creditos.documento_id	WHERE estatus =" . $iCreditos . " AND  
				nomina_procedencia like '" . $dtd_nomina . "' " . $limit;
		} elseif ($strDependencia == "TODOS") {
			///$strQuery = "SELECT $set FROM t_personas INNER JOIN t_clientes_creditos ON
			//	t_personas.documento_id=t_clientes_creditos.documento_id WHERE nomina_procedencia like
			//	'" . $dtd_nomina . "' AND cobrado_en like '" . $cobrado_en . "' AND estatus = " . $iCreditos . " " . $limit;

			$strQuery = "SELECT $set FROM t_personas INNER JOIN t_clientes_creditos ON
				t_personas.documento_id=t_clientes_creditos.documento_id 
				WHERE nomina_procedencia like	'" . $dtd_nomina . "' AND 
				cobrado_en like '" . $cobrado_en . "' 
				AND estatus = " . $iCreditos . " GROUP BY t_clientes_creditos.numero_factura " . $limit;

		} else {
			//$strQuery = "SELECT $set FROM t_personas INNER JOIN t_clientes_creditos ON
			//t_personas.documento_id=t_clientes_creditos.documento_id WHERE t_personas.codigo_nomina_aux='" . $strDependencia . "' AND
			//nomina_procedencia like '" . $dtd_nomina . "'  AND cobrado_en like '" . $cobrado_en . "'
			//AND estatus =" . $iCreditos . " " . $limit;

			$strQuery = "SELECT $set FROM t_personas INNER JOIN t_clientes_creditos ON 
			t_personas.documento_id=t_clientes_creditos.documento_id WHERE t_personas.codigo_nomina_aux='" . $strDependencia . "' AND  
			nomina_procedencia like '" . $dtd_nomina . "'  AND cobrado_en like '" . $cobrado_en . "' 
			AND estatus =" . $iCreditos . " GROUP BY t_clientes_creditos.numero_factura " . $limit;

		}
		//echo $strQuery;
		$Consulta = $this -> db -> query($strQuery);
		$sTotal = $this -> db -> query("SELECT FOUND_ROWS() AS total");
		//LIMITE TOTAL DE LAS CONSULATAS

		$strEstatus = "<th>ESTATUS</th>";
		if ($Nivel_Acceso == 0) {
			$strEstatus = "<th>A</th><th>R</th>";

			$sBotones = "<br>
  		<center>
				<table><tr><td>	
				<a href=\"#\" id=\"dialog_boton\" class=\"ui-state-default ui-corner-all\" onclick=\"document.frmProcesar.submit()\" >
				<span class=\"ui-icon ui-icon-circle-check\"></span>Procesar Cambios</a></p>	
				</td><td>
				<a href=\"#\" id=\"dialog_boton\" class=\"ui-state-default ui-corner-all\" onclick=\"onSubmit()\" >
				<span class=\"ui-icon ui-icon-circle-close\"></span>Cancelar Cambios</a></p>
				</td></tr></table>
				<br><br>
  		</center>";
		}

		$strReporte .= "<form name=\"frmProcesar\" method=\"POST\" action=\"" . base_url() . "index.php/cooperativa/Procesar_Contratos\"><br>
		<ul id=\"icons\" class=\"ui-widget ui-helper-clearfix\"><table style=\"height:15px;width:750px;\" border=0
		class=\"ui-widget ui-widget-content\" cellspacing=\"3\" cellpadding=\"0\"   >
		<thead><tr class=\"ui-widget-header\" style=\"height:15px;\">
		<th>#</th><th>E</th><th>M</th><th>CEDULA</th><th>APELLIDOS Y NOMBRES</th><th># CONTRATO</th><th>TOTAL</th><th>SOLICITUD</th><th>NOM</th>
		<th>TIP</th><th>COB</th>$strEstatus</tr></thead><tbody>";
		if ($Consulta -> num_rows() > 0) {
			//<a href=\"#\" id=\"dialog_link\" class=\"ui-state-default ui-corner-all\"><span class=\"ui-icon ui-icon-circle-plus\"></span></a>
			foreach ($Consulta->result() as $row) {
				$motivo = "<span class=\"ui-icon ui-icon-wrench\"></span>";
				$motivo_v = $row -> motivo;
				if ($row -> motivo == "-- PRESTAMO --") {
					$motivo = "<span class=\"ui-icon ui-icon-cart\"></span>";
					$motivo_v = "PRESTAMO";
				}
				$i++;
				$strContenido = "";
				if ($Nivel_Acceso == 0) {
					$strContenido = "<p><a href=\"#\" onClick=\"Eliminar_Contrato('" . __LOCALWWW__ . "','" . $row -> contrato_id . "');\" id=\"dialog_link\"
					class=\"ui-state-default ui-corner-all\"><span class=\"ui-icon ui-icon-circle-minus\"></span></a></p>";

					switch($iCreditos) {
						case 0 :
							$strEstatus = "<td><input type='radio' name='C" . $intI . "' id='C" . $intI . "' value=1>
							</td><td><input type='radio' name='C" . $intI . "' id='C" . $intI . "' value=2>
							<input type='hidden' name='I" . $intI . "' id='I" . $intI . "' value='" . $row -> contrato_id . "' ></td>";
							break;
						case 1 :
							$strEstatus = "<td><input type='radio' name='C" . $intI . "' id='C" . $intI . "' value=1 checked>
							</td><td><input type='radio' name='C" . $intI . "' id='C" . $intI . "' value=2>
							<input type='hidden' name='I" . $intI . "' id='I" . $intI . "' value='" . $row -> contrato_id . "' ></td>";
							break;
						case 2 :
							$strEstatus = "<td><input type='radio' name='C" . $intI . "' id='C" . $intI . "' value=1>
							</td><td><input type='radio' name='C" . $intI . "' id='C" . $intI . "' value=2 checked>
							<input type='hidden' name='I" . $intI . "' id='I" . $intI . "' value='" . $row -> contrato_id . "' ></td>";
							break;
					}
				} else {
					switch($iCreditos) {
						case 0 :
							$strEstatus = "<td align='center'><strong>PROCESANDO</strong></td>";
							break;
						case 1 :
							$strEstatus = "<td align='center'><strong>ACEPTADO</strong></td>";
							break;
						case 2 :
							$strEstatus = "<td align='center'><strong>RECHAZADO</strong></td>";
							break;
					}
				}
				$Mostrar = "<p><a href=\"#$i\" onClick=\"Mostrar_Detalles('$i');\" id=\"dialog_link\" class=\"ui-state-default ui-corner-all\">
				<span class=\"ui-icon ui-icon-circle-triangle-s\"></span></a>";
				$Ocultar = "<p><a href=\"#$i\" onClick=\"Ocultar_Detalles('$i');\" id=\"dialog_link\" class=\"ui-state-default ui-corner-all\">
				<span class=\"ui-icon ui-icon-circle-triangle-n\"></span></a>";
				$strReporte .= "<tr style='height:15px'>";
				$strReporte .= "<td><a name='" . $i . "'><strong>" . ($intI + 1) . "</strong></td><td><p>" . $strContenido . "</p>
				</td><td>" . $Mostrar . "</td><td><strong>" . $row -> nacionalidad . "</strong>" . $row -> documento_id . "</td><td>" . $row -> primer_apellido . " " . $row -> segundo_apellido . " " . $row -> primer_nombre . " " . $row -> segundo_nombre . "</td><td>
					<a  id=\"dialog_link\" class=\"ui-state-default ui-corner-all\">" . $motivo . "</a>&nbsp;
					<strong>" . $row -> contrato_id . "</strong></td>
					<td align=right><strong>" . number_format($row -> monto_total, 2, ".", ",") . " Bs. </strong></td>
					<td align=center>" . $row -> fecha_solicitud . "</td><td  align=center>
					<a href=\"#\" title=\"" . $row -> nomina_procedencia . "\">
					" . $this -> Descripcion_Nominas($row -> nomina_procedencia) . "</a></td>
					<td align='center'><strong>" . $this -> Tipo_Cuenta($row -> forma_contrato) . "</strong></td>
					<td align='center'><strong>" . $this -> Cobrado_Por($row -> cobrado_en) . "</strong></td>";
				$strReporte .= "$strEstatus</tr>";
				$strReporte .= "<tr><td colspan=13>
					<div id=\"divDetalles$i\" style='display:none'>
						<br><center>
						<table style='width:95%' border=0  bgcolor='#FFFFFF' cellspacing=0>
							<tr><td></td><td colspan=3><br></td><td width:10px></td></tr>
							<tr style='height:20px'><td style='width:5px'></td>
							<td><strong>DETALLES GENERALES DEL CONTRATO (" . $motivo_v . ")</strong></td>
							<td style='width:18px' valing='top'>$Ocultar</td><td>&nbsp;</td></tr>
							<tr><td></td><td colspan=3><strong><font color='#1c94c4'>NOMINA: </font> 
							</strong> " . $row -> nomina_procedencia . "</td></tr><tr>
							<td></td><td colspan=3><strong><font color='#1c94c4'>FECHA INICIO DEL COBRO: </font>
							</strong> " . $row -> fecha_inicio_cobro . "&nbsp;
							<strong><font color='#1c94c4'>FECHA DE LA SOLICITUD: </font>
							</strong> " . $row -> fecha_solicitud . "
							<strong><font color='#1c94c4'>FACTURA N&Uacute;MERO: </font>
							</strong> " . $row -> numero_factura . "</td></tr><tr>
							<td></td><td colspan=3><strong><font color='#1c94c4'>EMPRESA DOMICILIO: </font>
							</strong> " . $row -> empresa . "
							<strong><font color='#1c94c4'>&nbsp;&nbsp;COBRADO POR: </font>
							</strong> " . $row -> cobrado_en . "</td></tr>
							<td></td><td colspan=3><strong><font color='#1c94c4'># CHEQUE: </font> </strong> " . $row -> num_operacion . "&nbsp;&nbsp;
							<strong><font color='#1c94c4'>MONTO DEL CHEQUE: </font></strong> " . number_format($row -> monto_operacion, 2, ".", ",") . " Bs.
							&nbsp;&nbsp;
							<strong><font color='#1c94c4'>FECHA DEL CHEQUE: </font></strong> " . $row -> fecha_operacion . " 
							</td></tr><tr>
							<td></td><td colspan=3><strong><font color='#1c94c4'>CUOTAS A CANCELAR: </font> </strong> " . $row -> numero_cuotas . $this -> Periocidad($row -> periocidad) . "
							<strong><font color='#1c94c4'>MONTO POR CUOTAS: </font></strong> " . number_format($row -> monto_cuota, 2, ".", ",") . " Bs.
							</td></tr>
							<tr><td></td><td colspan=3><br></td></tr>
						</table>
						</center>
						<br>
						
					</div>
					</td></tr>";
				$intI++;
			}

		}

		$iTotal = 0;
		foreach ($sTotal->result() as $lstTotal) {
			$iTotal = $lstTotal -> total;
		}

		$sConfigurar['base_url'] = base_url() . 'index.php/cooperativa/reportes/';
		$sConfigurar['total_rows'] = $iTotal;
		$sConfigurar['per_page'] = $iPor_Pagina;

		$this -> pagination -> initialize($sConfigurar);

		$paginador = "<br><center>" . $this -> pagination -> create_links() . "</center>";

		$strReporte .= "</tbody><table></ul><input type='hidden' value='" . $intI . "' name='txtMaximo'>
		" . $paginador . "<br></form>" . $sBotones;

		return $strReporte;
	}

	public function Periocidad($iValor) {
		$sValor = "";
		switch($iValor) {
			case 0 :
				$sValor = " SEMALES ";
				break;
			case 1 :
				$sValor = " CATORCENALES ";
				break;
			case 2 :
				$sValor = " QUINCENALES (15-30) ";
				break;
			case 3 :
				$sValor = " QUINCENALES (10 - 25) ";
				break;
			case 4 :
				$sValor = " MENSUALES ";
				break;
			case 5 :
				$sValor = " TRISMETRALES ";
				break;
			case 6 :
				$sValor = " SEMESTRALES ";
				break;
			case 7 :
				$sValor = " ANUALES ";
				break;
			case 8 :
				$sValor = " MENSUALES-10 ";
				break;
			case 9 :
				$sValor = " MENSUALES-25 ";
				break;
		}
		return $sValor;
	}

	public function Tipo_Cuenta($intCod) {
		switch ($intCod) {
			case 0 :
				return "<a title='UNICO' id=\"tipo_link\"><font color='#1c94c4'>U</font></a>";
				break;
			case 1 :
				return "<a title='CUOTA ESPECIAL - AGUINALDOS' >A</a>";
				break;
			case 2 :
				return "<a title='CUOTA ESPECIAL - VACACIONES'><font color='#ec8e0c'>V</font></a>";
				break;
			case 3 :
				return "<a title='EXTRAS'><font color='#ec8e0c'>E</font></a>";
				break;
			case 4 :
				return "<a title='UNICO EXTRA'><font color='#ec8e0c'>UE</font></a>";
				break;
			case 5 :
				return "<a title='ESPECIAL EXTRA'><font color='#ec8e0c'>EE</font></a>";
				break;
		}
	}

	public function Cobrado_Por($strCredito) {
		switch ($strCredito) {
			case "NOMINA" :
				return "<a title=\"NOMINA\"><font color='#ec8e0c'>NOM</font></a>";
				break;
			case "SOFITASA" :
				return "<a title=\"SOFITASA\"><font color='#1c94c4'>SOF</font></a>";
				break;
			case "BICENTENARIO" :
				return "<a title=\"BICENTENARIO\"><font color='#1c94c4'>BIC</font></a>";
				break;
			case "BOD" :
				return "<a title=\"BOD\"><font color='#1c94c4'>BOD</font></a>";
				break;
			case "PROVINCIAL" :
				return "<a title=\"PROVINCIAL\"><font color='#1c94c4'>PRO</font></a>";
				break;
			case "VENEZUELA" :
				return "<a title=\"VENEZUELA\"><font color='#1c94c4'>VEN</font></a>";
				break;
			case "BANESCO" :
				return "<a title=\"BANESCO\"><font color='#1c94c4'>BAN</font></a>";
				break;
			case "MERCANTIL" :
				return "<a title=\"MERCANTIL\"><font color='#1c94c4'>MER</font></a>";
				break;
			case "DEL SUR" :
				return "<a title=\"DEL SUR\"><font color='#1c94c4'>SUR</font></a>";
				break;
			case "INDUSTRIAL" :
				return "<a title=\"INDUSTRIAL\"><font color='#1c94c4'>IND</font></a>";
				break;
			case "CREDINFO" :
				return "<a title=\"CREDINFO\">CRE</a>";
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

	public function Eliminar_Contrato($intContrato = null) {
		$strQuery = "DELETE FROM t_clientes_creditos WHERE contrato_id ='" . $intContrato . "';";
		$this -> db -> query($strQuery);
	}

	public function Eliminar_Reporte_Factura($intContrato = null) {
		$strQuery = "DELETE FROM t_clientes_creditos WHERE numero_factura ='" . $intContrato . "';";
		$this -> db -> query($strQuery);
	}

	public function Descripcion_Nominas($strNomina) {
		switch ($strNomina) {
			case "ULA" :
				return "ULA";
			case "PDVSA" :
				return "PDV";
			case "COORPOSALUD" :
				return "CPS";
			case "IPOSTEL - JUBILADOS " :
				return "IPJ";
			case "DIRECCION GENERAL DEL PODER POPULAR BOMBEROS DEL ESTADO MERIDA" :
				return "MBO";
			case "MINISTERIO DEL PODER POPULAR PARA LA EDUCACION" :
				return "ME";
			case "MINISTERIO DEL PODER POPULAR PARA LA EDUCACION - JUBILADO" :
				return "MEJ";
			case "GOBERNACION DEL ESTADO MERIDA" :
				return "GEM";
			case "DIRECCION GENERAL DEL PODER POPULAR POLICIA DEL ESTADO MERIDA" :
				return "MPO";
			case "INJUVEN" :
				return "INJ";
			case "COORPOSALUD" :
				return "CPS";
			case "IPOSTEL" :
				return "IPO";
			case "GOBERNACION DEL ESTADO TACHIRA" :
				return "GET";
			case "CATIVEN" :
				return "CAT";
			case "PEQUIVEN" :
				return "PEQ";
			case "SENIAT" :
				return "SEN";

			case "GOBERNACION DEL ESTADO ZULIA" :
				return "GEZ";
			case "PDVSA" :
				return "PDV";
			case "PDVAL" :
				return "PVA";
			case "MERCAL" :
				return "MER";
			case "ALCALDIA DEL LIBERTADOR" :
				return "ALL";
			case "POLICIA VIAL" :
				return "POV";
			case "CANTV" :
				return "CAN";
			case "INMECA" :
				return "INM";
			case "INAM" :
				return "INA";
			case "INAM - JUBILADOS" :
				return "JIN";
			case "POLICIA CAMPO ELIAS" :
				return "PCE";
			case "AEULA" :
				return "AEU";
			case "AGUAS DE MERIDA" :
				return "ADM";
			case "COORPOELEC" :
				return "COO";
			case "PODER JUDICIAL" :
				return "POJ";
			case "GUARDIA NACIONAL" :
				return "GUN";
			case "CNE" :
				return "CNE";
			case "IVSS" :
				return "IVSS";
			case "IVSS - JUBILADOS" :
				return "IVJ";
			case "DIRECCION DE PUERTOS Y AEROPUERTOS DE VENEZUELA" :
				return "DPA";
			case "MINISTERIO DE LA SALUD" :
				return "MIS";
			case "PUERTO DE MARACAIBO" :
				return "PMA";
			case "MINISTERIO DE INTERIOR Y JUSTICIA" :
				return "MIJ";
			case "MINISTERIO DEL AMBIENTE" :
				return "MDA";
			case "BARRIO ADENTRO" :
				return "BAA";
			case "IMPRADEM" :
				return "IMP";
			case "UNESUR" :
				return "UNS";
			case "UNIVERSIDAD DEL ZULIA" :
				return "UDZ";
			case "INPSASEL" :
				return "INP";
		}

	}

	public function Generar_Codigo($intCod) {
		switch (strlen($intCod)) {
			case 1 :
				return "000" . $intCod;
			case 2 :
				return "00" . $intCod;
			case 3 :
				return "0" . $intCod;
			case 4 :
				return $intCod;
		}
	}

	public function Eliminar_Clientes($strCedula,$strPeticion,$strMotivo) {
		$query = $this->db->query('SELECT * FROM t_personas WHERE documento_id="'.$strCedula.'"');

		$cant = $query->num_rows(); 
		$this -> db -> where("documento_id", $strCedula);
		$this -> db -> delete("t_personas");
		$consulta = $this -> db -> query("select * from t_clientes_creditos where documento_id ='".$strCedula."'");
		foreach ($consulta -> result() as $row) {
			$this -> db -> query("DELETE from t_lista_voucher WHERE cid='". $row -> contrato_id . "'");
		}
		$this -> db -> where("documento_id", $strCedula);
		$this -> db -> delete("t_clientes_creditos");
		$this -> db -> where("documento_id", $strCedula);
		$this -> db -> delete("t_lista_cobros");
		$data = array(
			//'id' => null,
   			'referencia' => $strCedula,
   			'tipo' => 1,
   			'usuario' => $_SESSION['usuario'],
   			'motivo' => $strMotivo,
   			'peticion' => $strPeticion 
		);
		if($cant > 0){
			$this -> db -> insert('_th_sistema',$data);	
		}
		
	}

	/* Objeto empleado de CodeIgniter
	 * @param string Responsable en la nómina
	 * @param string Descripcion de la nomina
	 * @param string
	 * @param integer Acceso 0: SuperUsuario
	 * @param integer 0: Por aceptar 1: Aceptados 2: Rechazados
	 *
	 */
	public function CI_Clientes_Reportes_Factura($strDependencia = null, $dtd_nomina = null, $cobrado_en = null, $Nivel_Acceso = null, $iCreditos = null, $iLimit = null, $iPor_Pagina = null, $desde = null, $hasta = null) {

		$strReporte = "";
		$strContenido = "";
		$sBotones = "";
		$dblTotal = 0;
		$intI = 0;
		$i = 0;

		if ((int)$iLimit <= 0) {
			$limit = "LIMIT 0, " . $iPor_Pagina;
		} else {
			$limit = "LIMIT " . $iLimit . ", " . $iPor_Pagina;
		}

		$set = "nacionalidad, t_personas.documento_id, contrato_id, primer_apellido,segundo_apellido, primer_nombre, segundo_nombre,
			monto_total, nomina_procedencia,fecha_inicio_cobro,fecha_solicitud,	numero_factura,empresa,numero_cuotas,monto_cuota,
			motivo,forma_contrato,num_operacion,fecha_operacion,monto_operacion,cobrado_en,periocidad";

		if ($dtd_nomina == "TODOS") {
			$dtd_nomina = "%";
		}
		if ($cobrado_en == "TODOS") {
			$cobrado_en = "%";
		}

		$des = '';
		if ($desde != "") {
			$des = ' AND fecha_solicitud >= \'' . $desde . '\'';
		}
		if ($hasta != "") {
			$des .= ' AND fecha_solicitud <= \'' . $hasta . '\'';
		}

		if ($strDependencia == "") {
			$strQuery = "SELECT $set FROM t_personas INNER JOIN t_clientes_creditos ON 
				t_personas.documento_id=t_clientes_creditos.documento_id	WHERE estatus =" . $iCreditos . " AND  
				nomina_procedencia like '" . $dtd_nomina . "'" . $des;

		} elseif ($strDependencia == "TODOS") {
			$strQuery = "SELECT $set FROM t_personas INNER JOIN t_clientes_creditos ON
				t_personas.documento_id=t_clientes_creditos.documento_id 
				WHERE nomina_procedencia like	'" . $dtd_nomina . "' AND 
				cobrado_en like '" . $cobrado_en . "' 
				AND estatus = " . $iCreditos . $des . " GROUP BY t_clientes_creditos.numero_factura ";

		} else {

			$strQuery = "SELECT $set FROM t_personas INNER JOIN t_clientes_creditos ON 
			t_personas.documento_id=t_clientes_creditos.documento_id WHERE t_personas.codigo_nomina_aux='" . $strDependencia . "' AND  
			nomina_procedencia like '" . $dtd_nomina . "'  AND cobrado_en like '" . $cobrado_en . "' 
			AND estatus =" . $iCreditos . $des . " GROUP BY t_clientes_creditos.numero_factura ";

		}
		$Consulta = $this -> db -> query($strQuery);

		$strEstatus = "<th>ESTATUS</th>";
		if ($Nivel_Acceso == 0) {
			$strEstatus = "<th>A</th><th>R</th>";

			$sBotones = "<br>
  		<center>
				<button onclick=\"document.frmProcesar.submit()\">&nbsp;&nbsp;Procesar Cambios&nbsp;&nbsp;</button>
				<button onclick=\"onSubmit()\">&nbsp;&nbsp;Cancelar Cambios&nbsp;&nbsp;</button>
  		</center>";
		}

		$strReporte .= "<form name=\"frmProcesar\" method=\"POST\" 
		action=\"" . base_url() . "index.php/cooperativa/Procesar_Contratos_Facturas\"><br>
		<ul id=\"icons\" class=\"ui-widget ui-helper-clearfix\">
		<table style=\"height:15px;width:750px;\" border=0
		class=\"ui-widget ui-widget-content\" cellspacing=\"3\" cellpadding=\"0\"   >
		<thead><tr class=\"ui-widget-header\" style=\"height:15px;\">
		<th>#</th><th>E</th><th>CEDULA</th><th>APELLIDOS Y NOMBRES</th>
		<th># FACTURA</th><th>TOTAL</th><th>SOLICITUD</th><th>NOM</th>
		<th>TIP</th><th>COB</th>$strEstatus</tr></thead><tbody>";
		if ($Consulta -> num_rows() > 0) {
			foreach ($Consulta->result() as $row) {

				$motivo_v = $row -> motivo;

				$i++;
				$strContenido = "";
				if ($Nivel_Acceso == 0) {
					$strContenido = "<p><a href=\"#\" onClick=\"Eliminar_Reporte_Factura('" . __LOCALWWW__ . "','" . $row -> numero_factura . "');\" id=\"dialog_link\"
					class=\"ui-state-default ui-corner-all\"><span class=\"ui-icon ui-icon-circle-minus\">
					</span></a></p>";

					switch($iCreditos) {
						case 0 :
							$strEstatus = "<td><input type='radio' name='C" . $intI . "' id='C" . $intI . "' value=1>
							</td><td><input type='radio' name='C" . $intI . "' id='C" . $intI . "' value=2>
							<input type='hidden' name='I" . $intI . "' id='I" . $intI . "' value='" . $row -> numero_factura . "' ></td>";
							break;
						case 1 :
							$strEstatus = "<td><input type='radio' name='C" . $intI . "' id='C" . $intI . "' value=1 checked>
							</td><td><input type='radio' name='C" . $intI . "' id='C" . $intI . "' value=2>
							<input type='hidden' name='I" . $intI . "' id='I" . $intI . "' value='" . $row -> numero_factura . "' ></td>";
							break;
						case 2 :
							$strEstatus = "<td><input type='radio' name='C" . $intI . "' id='C" . $intI . "' value=1>
							</td><td><input type='radio' name='C" . $intI . "' id='C" . $intI . "' value=2 checked>
							<input type='hidden' name='I" . $intI . "' id='I" . $intI . "' value='" . $row -> numero_factura . "' ></td>";
							break;
					}
				} else {
					switch($iCreditos) {
						case 0 :
							$strEstatus = "<td align='center'><strong>PROCESANDO</strong></td>";
							break;
						case 1 :
							$strEstatus = "<td align='center'><strong>ACEPTADO</strong></td>";
							break;
						case 2 :
							$strEstatus = "<td align='center'><strong>RECHAZADO</strong></td>";
							break;
					}
				}

				$Ocultar = "<p><a href=\"#$i\" onClick=\"Ocultar_Detalles('$i');\" id=\"dialog_link\" class=\"ui-state-default ui-corner-all\">
				<span class=\"ui-icon ui-icon-circle-triangle-n\"></span></a>";
				$strReporte .= "<tr style='height:15px'>";
				$strReporte .= "<td><a name='" . $i . "'><strong>" . ($intI + 1) . "</strong></td>
				<td><p>" . $strContenido . "</p>
				</td><td><strong>" . $row -> nacionalidad . "</strong>" . $row -> documento_id . "</td><td>" . $row -> primer_apellido . " " . $row -> segundo_apellido . " " . $row -> primer_nombre . " " . $row -> segundo_nombre . "</td><td>&nbsp;
					<strong>" . $row -> numero_factura . "</strong></td>
					<td align=right><strong>" . number_format($row -> monto_total, 2, ".", ",") . " Bs. </strong></td>
					<td align=center>" . $row -> fecha_solicitud . "</td><td  align=center>
					<a href=\"#\" title=\"" . $row -> nomina_procedencia . "\">
					" . $this -> Descripcion_Nominas($row -> nomina_procedencia) . "</a></td>
					<td align='center'><strong>" . $this -> Tipo_Cuenta($row -> forma_contrato) . "</strong></td>
					<td align='center'><strong>" . $this -> Cobrado_Por($row -> cobrado_en) . "</strong></td>";
				$strReporte .= "$strEstatus</tr>";
				$intI++;
			}

		}

		$strReporte .= "</tbody></table></ul>
		<input type='hidden' value='" . $intI . "' name='txtMaximo'>
		<br></form>" . $sBotones;

		return $strReporte;
	}

	/*
	 *  Objeto empleado de CodeIgniter
	 * @param CI_Clientes_Json
	 */
	public function CI_Clientes_Reportes_Json($strDependencia = null, $dtd_nomina = null, $cobrado_en = null, $Nivel_Acceso = null, $iCreditos = null, $iLimit = null, $iPor_Pagina = null) {

		$strReporte = "Listo....";
		$limit = 'LIMIT 0, 1000';

		$set = "nacionalidad, t_personas.documento_id, contrato_id, primer_apellido,segundo_apellido, primer_nombre, 
		segundo_nombre,	monto_total, nomina_procedencia,fecha_inicio_cobro,fecha_solicitud,	numero_factura,
		empresa,numero_cuotas,monto_cuota,motivo,forma_contrato,num_operacion,fecha_operacion,
		monto_operacion,cobrado_en,periocidad,estatus";

		if ($dtd_nomina == "TODOS") {	$dtd_nomina = "%";
		}
		if ($cobrado_en == "TODOS") {$cobrado_en = "%";
		}

		if ($strDependencia == "") {
			$strQuery = "SELECT $set FROM t_personas INNER JOIN t_clientes_creditos ON 
			t_personas.documento_id=t_clientes_creditos.documento_id	WHERE estatus =" . $iCreditos . " AND  
			nomina_procedencia like '" . $dtd_nomina;
		} elseif ($strDependencia == "TODOS") {
			$strQuery = "SELECT $set FROM t_personas INNER JOIN t_clientes_creditos ON
			t_personas.documento_id=t_clientes_creditos.documento_id WHERE nomina_procedencia like	'" . $dtd_nomina . "' AND cobrado_en like '" . $cobrado_en . "'	AND estatus = " . $iCreditos . " 
			GROUP BY t_clientes_creditos.numero_factura ";
		} else {
			$strQuery = "SELECT $set FROM t_personas INNER JOIN t_clientes_creditos ON 
			t_personas.documento_id=t_clientes_creditos.documento_id WHERE 
			t_personas.codigo_nomina_aux='" . $strDependencia . "' AND 	nomina_procedencia like '" . $dtd_nomina . "'  
			AND cobrado_en like '" . $cobrado_en . "' AND estatus =" . $iCreditos . " GROUP BY 
			t_clientes_creditos.numero_factura ";
		}

		$RFacturas = array();
		$RFacturas['Esquemas'] = '';
		$Consulta = $this -> db -> query($strQuery);
		if ($Consulta -> num_rows() > 0) {
			foreach ($Consulta->result_array() as $row) {
				$RFacturas[] = $row;
			}
		}
		/**
		 print_r("<pre>");
		 print_r($RFacturas);
		 print_r("</pre>");**/
		$strReporte = json_encode($RFacturas);
		return $strReporte;
	}

	public function TG_Clientes_Reportes($strDependencia = null, $dtd_nomina = null, $cobrado_en = null, $Nivel_Acceso = null, $iCreditos = null, $Arr) {

		$set = "CONCAT(nacionalidad, t_personas.documento_id) AS cedula ,CONCAT(primer_apellido,' ' ,segundo_apellido,' ' , primer_nombre, ' ' ,segundo_nombre) AS nombre,
		numero_factura AS factura, monto_total AS total, fecha_solicitud AS solicitud, cobrado_en AS banco";
		if ($dtd_nomina == "TODOS") {	$dtd_nomina = "%"; }
		if ($cobrado_en == "TODOS") {	$cobrado_en = "%"; }

		if ($strDependencia == "TODOS") {
			$strQuery = "SELECT $set FROM t_estadoejecucion 
				JOIN t_clientes_creditos ON t_estadoejecucion.oidc=t_clientes_creditos.contrato_id
				JOIN t_personas ON t_clientes_creditos.documento_id=t_personas.documento_id
				WHERE " . $Arr['lista_dependiente']  . "  t_clientes_creditos.nomina_procedencia like	'" . $dtd_nomina . "' AND 
				t_clientes_creditos.cobrado_en like '" . $cobrado_en . "' 
				AND t_clientes_creditos.estatus = " . $iCreditos . " GROUP BY t_clientes_creditos.numero_factura ";
		} else {
			$strQuery = "SELECT $set FROM t_estadoejecucion 
			JOIN t_clientes_creditos ON t_estadoejecucion.oidc=t_clientes_creditos.contrato_id
			JOIN t_personas ON t_personas.documento_id=t_clientes_creditos.documento_id
			WHERE t_personas.expediente_c='" . $strDependencia . "' AND  
			t_clientes_creditos.nomina_procedencia like '" . $dtd_nomina . "'  AND 
			t_clientes_creditos.cobrado_en like '" . $cobrado_en . "' 
			AND t_clientes_creditos.estatus =" . $iCreditos . " GROUP BY t_clientes_creditos.numero_factura ";
		}
		
		$Consulta = $this -> db -> query($strQuery);
		$Object = array("Cabezera" => $Consulta -> list_fields(), "Cuerpo" => $Consulta -> result(), "Origen" => "Mysql", "Paginador" => 20, "Enumerar" => 0);
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
}
?>