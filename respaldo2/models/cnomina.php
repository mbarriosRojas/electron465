<?php

/**
 *  @author Judelvis Antonio Rivas Perdomo
 *  @package SaGem.system.application
 *  @version 1.0.0
 */

class CNomina extends Model {

	/**
	 * @var integer
	 */
	var $codigo ;

	/**
	 * @var string
	 */
	var $nombre;

	/**
	 * @var string
	 */
	var $descripcion;

	public function __construct() {
		parent::__construct();
	}

	public function Combo() {
		$strCombo='';
		$strQuery = $this->db->query("SELECT * FROM t_nominas order by nombre");
		if ($strQuery->num_rows() > 0)	{
			foreach ($strQuery->result() as $row){		
				$strCombo .= '<option value="' . $row->nombre.'" '. $row -> activo .'>'.$row->nombre.'</option>';
			}
		}
		return $strCombo;

	}
	public function Eliminar($strNombre) {
		$this -> db -> where("nombre", trim($strNombre));
		$this -> db -> delete("t_nominas");

	}

}
?>