<?php
/**
 *  @author Carlos Enrique Penaa Albarran
 *  @package SaGem.system.application.model
 *  @version 1.0.0
 */
class CPendientes extends Model{

	function __construct() {
		parent::__construct();
			
	}



	/* Objeto empleado de CodeIgniter
	 * @param CI_Empleado
	 */
	public function CI_Cobrar($strAno = null, $strTipo = null){


		$strReporte = "";

		if($strTipo == 1){
			$strQuery = "SELECT * FROM t_personas INNER JOIN t_clientes_creditos ON t_personas.documento_id=t_clientes_creditos.documento_id
			WHERE forma_contrato=" . $strTipo ." AND fecha_inicio_cobro like '2011-%'";
		}else{
			$strQuery = "SELECT * FROM t_personas INNER JOIN t_clientes_creditos ON t_personas.documento_id=t_clientes_creditos.documento_id
			WHERE forma_contrato=" . $strTipo ." AND fecha_inicio_cobro like '2011-%'";
		}
		$Consulta = $this->db->query($strQuery);

		$strReporte = "	
		<table style=\"height:70px;width:340px;\" border=0
		class=\"ui-widget ui-widget-content\" cellspacing=\"3\" cellpadding=\"0\"   >
		<thead><tr class=\"ui-widget-header\" style=\"height:15px;\">
		<th>
		<font style='font-family: Arial, verdana, sans-serif;text-decoration: none; font-size: 10px;'>CEDULA</th><th>
		<font style='font-family: Arial, verdana, sans-serif;text-decoration: none; font-size: 10px;'>APELLIDOS Y NOMBRES</th><th>
		<font style='font-family: Arial, verdana, sans-serif;text-decoration: none; font-size: 10px;'># CONT.</th><th>
		<font style='font-family: Arial, verdana, sans-serif;text-decoration: none; font-size: 10px;'>TOTAL</th><th></th>
		</tr></thead><tbody>
		";
		if ($Consulta->num_rows() > 0)
		{
			//<a href=\"#\" id=\"dialog_link\" class=\"ui-state-default ui-corner-all\"><span class=\"ui-icon ui-icon-circle-plus\"></span></a>
			foreach ($Consulta->result() as $row)
			{

				$strReporte .= "<tr style='height:15px'>";
				$strReporte .= "<td>
				<font style='font-family: Arial, verdana, sans-serif;text-decoration: none; font-size: 9px;'>
				<strong>" . $row->nacionalidad . "</strong>" . $row->documento_id . "</font></td><td>
				<font style='font-family: Arial, verdana, sans-serif;text-decoration: none; font-size: 9px;'>" .
				$row->primer_apellido . " " . $row->primer_nombre  . "</td>
				<td align=left<font style='font-family: Arial, verdana, sans-serif;text-decoration: none; font-size: 9px;'>
				<strong>" . $row->contrato_id . "</strong></td>
				<td align=center>
				<font style='font-family: Arial, verdana, sans-serif;text-decoration: none; font-size: 9px;'>
				" . number_format($row->monto_total,2,".",",") . "</td><td>
				<font style='font-family: Arial, verdana, sans-serif;text-decoration: none; font-size: 9px;'>
				<a title='" . $row->nomina_procedencia . "' style='font-family: Arial, verdana, sans-serif;text-decoration: none; font-size: 9px;'>" .
				$this->Descripcion_Nominas($row->nomina_procedencia) . "</a></td>
				<td align='center'></td>";
				$strReporte .= "</tr>";
			}


		}
		$strReporte .= "</tbody><table>";
		return  $strReporte;

	}

	public function CI_Cobrar_Vacaciones($strAno = null){

	}

	public function Descripcion_Nominas($strNomina){
		switch ($strNomina){
			case "SOFITASA": return "";
			case "PDVSA": return "PDV";
			case "COORPOSALUD": return "CPS";
			case "IPOSTEL - JUBILADOS ": return "IPJ";
			case "DIRECCION GENERAL DEL PODER POPULAR BOMBEROS DEL ESTADO MERIDA": return "MBO";
			case "MINISTERIO DEL PODER POPULAR PARA LA EDUCACION": return "ME";
			case "MINISTERIO DEL PODER POPULAR PARA LA EDUCACION - JUBILADO": return "MEJ";
		}
	}
}
?>