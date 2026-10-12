<?php
/*
 *  @author Carlos Enrique Peña Albarrán
 *  @package SaGem.system.application
 *  @version 1.0.0
 */

class MContactos extends Model {

	public function __construct() {
		parent::__construct();
	}

	var $oid;
	
	var $cedula;

	var $nombre ;

	var $descripcion;

	var $telefono;
	
	var $direccion;
	
	var $nomina;

	/**
	 * Estatus del Servidor
	 * 0.- Sin Accion | 1.- Atendio | 2.- No Atendio | 3.- Equivocado | 4.- No Existe
	 */
	var $estatus;

	/**
	 * Salvar Objecto
	 * @return true
	 */
	function Salvar($obj = null) {
		$consulta = $this -> db -> query("SELECT * FROM t_contactos WHERE telefono = '". $obj -> telefono."'");
		$cantidad = $consulta -> num_rows();
		if($cantidad == 0)	$this -> db -> insert("t_contactos", $obj);
		else{
			foreach ($consulta -> result() as $row){
				if($row -> cedula == $this -> cedula)
					$this -> db -> query("UPDATE t_contactos SET direccion ='".$obj -> direccion ."' , nomina='" . $obj -> nomina ."' WHERE telefono='". $obj -> telefono ."' AND cedula ='". $obj -> cedula ."'");
			}
		}
		return true;
	}
	
	function Listar($id=''){
		$query = "SELECT * FROM t_contactos WHERE cedula='".$id."'";
		$contactos = $this -> db -> query($query);
		$rsCon = $contactos -> result();
		$cant = $contactos -> num_rows();
		$objeto = array();
		if($cant >0){
			$Cabezera = $contactos -> list_fields();
			$objeto = array("Cabezera" => $Cabezera, "Cuerpo" => $rsCon, "Paginador" => 10, "Origen" => 'Mysql',"cant"=>1);		
		}else{
			$objeto = array("cant"=>0);
		}
		return json_encode($objeto);
	}
	
	

}
?>