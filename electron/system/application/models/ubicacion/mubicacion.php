<?php
/**
 *  @author Judelvis Antonio Rivas
 *  @package Cooperativa.system.application.model.ubicacion
 *  @version 1.0.0
 *  @orm t_ubicacion
 */
class MUbicacion extends Model {

	function __construct($usuario = null) {
		parent::__construct();
	}
	
	function Listar(){
		$oCabezera[1] = array("titulo" => "oid", "atributos" => "width:80px", "oculto" => 1);
		$oCabezera[2] = array("titulo" => "Nombre", "atributos" => "width:150px", "buscar" => 0);
		$oCabezera[3] = array("titulo" => "Direccion", "atributos" => "width:250px", "buscar" => 0,"tipo"=>"texto");
		$oCabezera[4] = array("titulo" => "Sucursal", "atributos" => "width:50px", "buscar" => 1,"tipo"=>"texto");
		$oCabezera[5] = array("titulo" => "A", "tipo" => "bimagen", "funcion" => 'Modifica_Direccion', "parametro" => "1,3,4", "ruta" => __IMG__ . "botones/aceptar1.png", "atributos" => "width:10px","mantiene"=>0);
		
		$query = "SELECT * FROM t_ubicacion";
		$Conexion = $this -> db -> query($query);
		$cant = $Conexion -> num_rows();
		$filas = $Conexion -> result();
		if ($cant > 0) {
			$i = 0;
			foreach ($filas as $row) {
				++$i;
				$oFil[$i] = array("1"=> $row -> oid,"2"=>$row->descripcion,"3"=>$row->direccion,"4"=>$row->sucursal,"5"=>"");
			}
			$oTable = array("Cabezera" => $oCabezera, "Cuerpo" => $oFil, "Origen" => "json","msj" => "SI");
		} else {
			$oTable = array("msj" => "NO");
		}
		
		return json_encode($oTable);
	}


}
?>