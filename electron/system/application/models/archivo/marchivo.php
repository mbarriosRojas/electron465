<?php
/**
 *  @author
 *  @package SaGem.system.application.model
 *  @version 1.0.0
 */
class MArchivo extends Model {

	/**
	 * Numero del archivo
	 * @var int
	 */
	var $numero;

	/**
	 * Cedula cedula
	 * @var int
	 */
	var $cedula;

	/**
	 * Nombre banco
	 * @var string
	 */
	var $banco;

	/**
	 * nomina
	 * @var string
	 */
	var $nomina;

	/**
	 * contratos
	 * @var string
	 */
	var $contratos;

	/**
	 * tipo (cancelado o activo)
	 * @var int
	 */
	var $tipo;

	/**
	 * empresa (cooperativa o grupo)
	 * @var int
	 */
	var $empresa;
	
	/**
	 * ubicacion
	 * @var string
	 */
	var $ubicacion;

	function __construct() {
		parent::__construct();

	}

	function Guardar($arr) {
		$msj = '';
		if ($this -> db -> insert('t_archivo', $arr)) {
			$msj = "Se ha registrado con exito...";
		} else {
			$msj = "No se pudo registrar con exito";
		}
		return $msj;

	}

	function Historial_Cliente($ced) {
		$archivo = "SELECT numero,cedula,CONCAT(primer_nombre,' ',segundo_nombre,' ',primer_apellido,' ',segundo_apellido)as nombre,
		banco,nomina,contratos,observa,entregado,recibido,fecha,t_archivo.ubicacion,perfil,
		IF(tipo=0,'Cancelado','Activo')as Tipo,IF(empresa=1,'Grupo','Cooperativa')as Empresa 
		FROM t_archivo
		LEFT JOIN t_personas ON t_archivo.cedula = t_personas.documento_id 
		WHERE cedula=" . $ced;
		$arc = $this -> db -> query($archivo);
		$can = $arc -> num_rows();
		if ($can > 0) {
			$Object = array("Cabezera" => $arc -> list_fields(), "Cuerpo" => $arc -> result(), "Origen" => "Mysql", "msj" => 1);
		}else{
			$Object = array("msj" => 0);
		}
		return json_encode($Object);
	}
	
	function Historial_Cliente_Json($ced) {
		$archivo = "SELECT numero,cedula,CONCAT(primer_nombre,' ',segundo_nombre,' ',primer_apellido,' ',segundo_apellido)as nombre,
		banco,nomina,contratos,observa,entregado,recibido,fecha,t_archivo.ubicacion,perfil,
		IF(tipo=0,'Cancelado','Activo')as tipo,IF(empresa=1,'Grupo','Cooperativa')as empresa 
		FROM t_archivo
		LEFT JOIN t_personas ON t_archivo.cedula = t_personas.documento_id 
		WHERE cedula=" . $ced;
		$filas = array();
		$oCabezera[1] = array("titulo" => "Numero", "atributos" => "width:80px");
		$oCabezera[2] = array("titulo" => "Cedula", "atributos" => "width:100px", "buscar" => 0);
		$oCabezera[3] = array("titulo" => "Nombre", "atributos" => "width:250px");
		$oCabezera[4] = array("titulo" => "Banco", "atributos" => "width:100px");
		$oCabezera[5] = array("titulo" => "Nomina", "atributos" => "width:120px");
		$oCabezera[6] = array("titulo" => "Contratos");
		$oCabezera[7] = array("titulo" => "Observacion", "tipo" => "textArea", "atributos" => "width:120px");
		$oCabezera[8] = array("titulo" => "Entregado","tipo" => "texto");
		$oCabezera[9] = array("titulo" => "Recibido","tipo" => "texto");
		$oCabezera[10] = array("titulo" => "Fecha","tipo" => "calendario");
		$oCabezera[11] = array("titulo" => "Ubicacion","tipo" => "combo");
		$oCabezera[12] = array("titulo" => "Perfil","tipo" => "combo");
		$oCabezera[13] = array("titulo" => "Empresa");
		$oCabezera[14] = array("titulo" => "Tipo");
		$oCabezera[15] = array("titulo" => "#", "tipo" => "bimagen", "funcion" => "Modificar_Carchivo", "parametro" => "1,2,7,8,9,10,11,12", "ruta" => __IMG__ . "botones/aceptar1.png","mantiene"=>1);
		
		$arc = $this -> db -> query($archivo);
		$can = $arc -> num_rows();
		$i=0;
		if ($can > 0) {
			$valores = array('' => 'SELECCIONE Ubiciacion');
			$ubica = $this -> db -> query("SELECT descripcion FROM t_ubicacion");
			foreach ($ubica -> result() as $ubc) {
				$valores[$ubc -> descripcion] = $ubc -> descripcion;
			}
			$valores1 = array('' => 'SELECCIONE');
			$perfil = $this -> db -> query("SELECT nombre FROM t_perfil");
			foreach ($perfil -> result() as $per) {
				$valores1[$per -> nombre] = $per -> nombre;
			}
			foreach ($arc -> result() as $row) {
				$i++;
				$fila[$i] = array("1" => $row -> numero,"2" => $row -> cedula, "3"=> $row -> nombre,"4" => $row -> banco,"5" => $row -> nomina,
									"6" => $row -> contratos,"7" => $row -> observa,"8" => $row -> entregado,"9" => $row -> recibido,"10" => $row -> fecha,
									"11" => $row -> ubicacion,"12" => $row -> perfil,"13" => $row -> empresa,"14" => $row -> tipo,"15"=>"");	
			}
			$objetos = array("11" => $valores,"12"=> $valores1);
			$Object = array("Cabezera" => $oCabezera, "Cuerpo" => $fila, "Origen" => "json", "Objetos" => $objetos,"msj"=>1);
		}else{
			$Object = array("msj" => 0);
		}
		return json_encode($Object);
	}

}
?>