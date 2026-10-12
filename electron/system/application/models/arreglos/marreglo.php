<?php
/**
 *  @author	Judelvis Rivas
 *  @package SaGem.system.application.model
 *  @version 1.0.0
 */
class MArreglo extends Model {

	/**
	 * ubicacion
	 * @var array string
	 */
	var $objValores = array();

	function __construct() {
		parent::__construct();

	}

	public function Obj_Valores($arr) {
		foreach ($arr as $clave => $valor) {
			if (!is_array($valor) && !is_object($valor)) $this -> objValores[$clave] = $valor;
			else array_push($this -> objValores,$this -> Obj_Valores($valor));
		}
	}

}
?>