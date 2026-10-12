<?php

/**
 *  @author Judelvis Antonio Rivas Perdomo
 *  @package SaGem.system.application
 *  @version 1.0.0
 */

class CZonapostal extends Model {

	/**
	 * @var integer
	 */
	var $id ;

	/**
	 * @var integer
	 */
	var $estado;

	/**
	 * @var string
	 */
	var $zona_postal;
	
	/**
	 * @var integer
	 */
	var $codigo;

	public function __construct() {
		parent::__construct();
	}

	public function insertar(){
		//$this->title   = $_POST['title']; // please read the below note
        //$this->content = $_POST['content'];
        //$this->date    = time();

        $res = $this->db->insert('t_zona_postal', $this);
		if($res){
			echo "Se inserto con exito la zona postal ". $this->zona_postal;
		}else{
			echo "No se pudo insertar.....";
		}
	}
	
	public function Zona_Postal($est) {
		$strCombo='';
		$strQuery = $this->db->query("SELECT * FROM t_zona_postal where estado=".$est);
		if ($strQuery->num_rows() > 0)	{
			foreach ($strQuery->result() as $row){		
				$strCombo .= '<option value="' . $row->codigo.'">'.$row->zona_postal.'</option>';
			}
		}
		return $strCombo;

	}
	
	public function Estados() {
		$strCombo='';
		$strQuery = $this->db->query("SELECT * FROM t_estados");
		$strCombo .= '<option value=0>ELIJA ESTADO</option>';
		if ($strQuery->num_rows() > 0)	{
			foreach ($strQuery->result() as $row){		
				$strCombo .= '<option value="' . $row->id.'">'.$row->nombre.'</option>';
			}
		}
		return $strCombo;

	}
	public function Eliminar($id) {
		$this -> db -> where("id", trim($id));
		$this -> db -> delete("t_zona_postal");

	}

}
?>