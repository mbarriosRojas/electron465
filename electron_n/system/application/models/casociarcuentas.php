<?php


/**
 *  @author Carlos Enrique Penaa Albarran
 *  @package SaGem.system.application.model
 *  @version 1.0.0
 *  t_asociar_cuentas
 */
class CAsociarCuentas extends Model{

	function __construct() {
		parent::__construct();
	}
	
	/**
	 * Identidficador indexado
	 * @var string
	 */
	public $usuario_id;
	/**
	 * Primer descripcion
	 * @var string
	 */	
	public $descripcion;
	/**
	 * Fecha
	 * @var string
	 */	
	public $fecha;
	/**
	 * Banco
	 * @var string
	 */	
	public $banco;
	/**
	 * Tipo
	 * @var integer
	 */	
	public $tipo;
	/**
	 * Cuenta
	 * @var string
	 */	
	public $cuenta;
	
	/**
	 * 
	 * @param $buscar string
	 * @return $data HTML
	 */
	function Consultar($buscar = null){
			
		$data = "<ul id=\"icons\" class=\"ui-widget ui-helper-clearfix\"><table style=\"height:18px;width:680px;\" border=0
		class=\"ui-widget ui-widget-content\" cellspacing=\"3\" cellpadding=\"0\">
		<thead><tr class=\"ui-widget-header\" style=\"height:20px;\">
		<th>E</th><th>M</th><th>CEDULA</th><th>APELLIDOS Y NOMBRES</th><th>BANCO</th><th>TIPO CUENTA</th><th># CUENTA</th>
		</tr></thead><tbody>";
		$this->db->select("*");
		$this->db->from("t_asociacion_usuarios");
//		$this->db->join("t_asociacion_usuarios", "t_asociacion_usuarios.usuario_id = t_usuarios.usuario_id");
		$this->db->join("t_personas", "t_asociacion_usuarios.usuario_id = t_personas.documento_id");
		
		$rsAC = $this->db->get();
		$strCuenta = "";
		
		if(count($rsAC->num_rows()) > 0){
			$i = 0;
			foreach ($rsAC->result() as $lstCuentas){
				$strCuenta = "CORRIENTE";
				if($lstCuentas->tipo == 0){
					$strCuenta = "AHORRO";
				}
				$i++;
				$sEliminar = "<p><a href=\"#\" onClick=\"Asociar_Cuentas_Eliminar('" . __LOCALWWW__ . "','" . $lstCuentas->documento_id . "');\" 
				id=\"dialog_link\" class=\"ui-state-default ui-corner-all\">
				<span class=\"ui-icon ui-icon-circle-minus\"></span></a></p>";
				$sMostrar = "<p><a href=\"#\" onClick=\"Desplegar('" . __LOCALWWW__ . "','" . $i . "');\" 
				id=\"dialog_link\" class=\"ui-state-default ui-corner-all\">
				<span class=\"ui-icon ui-icon-circle-plus\"></span></a></p>";
				$sOcultar = "";
				
				$data .= "<tr style='height:20px'>";
				$data .= "<td style='width:18px' valign='top'>" . $sEliminar . "</td><td style='width:18px' valign='top'>" . $sMostrar . "</td>";
				
				$data .= "<td><strong>" . $lstCuentas->nacionalidad . "</strong>" . $lstCuentas->documento_id . "</td><td>" . 
				$lstCuentas->primer_apellido .  " " . $lstCuentas->segundo_apellido . " " . $lstCuentas->primer_nombre  .  " " .
				$lstCuentas->segundo_nombre . "</td><td>" . $lstCuentas->banco ."</td><td>" . $strCuenta . "</td><td align='right'>" . $lstCuentas->cuenta ."</td>";
				$data .= "</tr>";
			}
		}
		$data .= "</tbody><table></ul>";
		return $data;
	}
	
	
	public function Eliminar($cedula){
		$this->db->where('usuario_id',$cedula);
		$this->db->delete('t_asociacion_usuarios');
		
	}
}
?>