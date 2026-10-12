<?php
/**
 * Controlador de Barra de menu
 *
 * @author Carlos Enrique Peña Albarrán
 * @package cooperativa.system.application.model
 * @version 2.0.0
 */
class MMenu extends Model {
	/**
	 * @var MUsuario
	 */
	var $MUsuario;
	
	function __construct() {
		parent::__construct();
	}
	/**
	 * @var MUsuario
	 * return json
	 */
	function setUsuario($MUsuario) {
		$this->MUsuario = $MUsuario;
	}
	

	function getMenu() {		
		$Menu_B = '';
		
	}


	/**
	 * Botones que pueden obtener los usuarios
	 * @require MUsuario 
	 * @return json() 
	 */
	function getClientesBtn(){
		
	}
	 
	

}
?>