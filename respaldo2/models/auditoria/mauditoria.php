<?php
/**
 *  @author Judelvis Rivas
 *  @package cooperativa.system.application.model.cliente
 *  @version 1.0.0
 */

class MAuditoria extends Model {

	var $tabla = 't_lista_voucher';

	public function __construct() {
		parent::__construct();uct();
	}

	public function __destruct() {
		unset($this -> MVoucher);
	}
	
	public function listar(){
		$consulta = $this -> db ->  query ("SELECT *  FROM t_auditar_txt where estatus = 0");
		$iCantidad = $consulta -> num_rows();
		$Conexion = $consulta -> result();

		$oCabezera[1] = array("titulo" => "oid", "oculto"=>TRUE);
		$oCabezera[2] = array("titulo" => "Descripcion", "atributos" => "width:50px", "buscar" => 0);
		$oCabezera[3] = array("titulo" => "Banco", "atributos" => "width:80px", "buscar" => 0);
		$oCabezera[4] = array("titulo" => "Fecha Archivo", "atributos" => "width:100px",'buscar'=>0);
		$oCabezera[5] = array("titulo" => "Monto Enviado", "atributos" => "width:100px", "buscar" => 0);
		$oCabezera[6] = array("titulo" => "Monto Revision");
		$oCabezera[7] = array("titulo" => "Fecha Recibido", "atributos" => "width:100px", "buscar" => 0);
		$oCabezera[8] = array("titulo" => "Dias", "atributos" => "width:100px");
		$oCabezera[9] = array("titulo" => "A", "tipo" => "bimagen", "funcion" => 'archivoAuditoriaProcesado', "parametro" => "1", "ruta" => __IMG__ . "botones/aceptar1.png", "atributos" => "width:10px");
		
		if ($iCantidad > 0) {
			$i = 0;
			foreach ($Conexion as $row) {
				++$i;
				$oFil[$i] = array("1" => $row -> oid, "2" => $row -> descripcion, "3" => $row -> banco, "4" => $row -> fecha_archivo, "5" => $row -> monto_enviado, "6" => $row -> monto_revision, "7" => $row -> fecha_recepcion, "8" => $row -> tiempo_dias , "9" => '');
				
			}

			$oTable = array("Cabezera" => $oCabezera, "Cuerpo" => $oFil, "Paginador" => 50, "Origen" => "json","msj" => TRUE);

		} else {
			$oTable = array("msj" => FALSE);
		}
		return json_encode($oTable);
	}

	public function listarNotasCreditos($id = ''){
		$oFil = array();
		$consulta = $this -> db ->  query ("SELECT id, fecha,motivo,peticion FROM _th_sistema WHERE referencia = '$id' AND tipo=99");
		$iCantidad = $consulta -> num_rows();
		$Conexion = $consulta -> result();
		if ($iCantidad > 0) {
			$i = 0;
			foreach ($Conexion as $row) {
				++$i;
				if($row->peticion == '999') {
					$oFil[$i] = array(
						"oid" => $row->id,
						"motivo" => $row->motivo
					);	
				}
							
			}
			

		} else {
			
		}
		return json_encode($oFil);
	} 
	
	public function notasCreditos($id = ''){
		$consulta = $this -> db ->  query ("SELECT id, fecha,motivo,peticion FROM _th_sistema WHERE referencia = '$id' AND tipo=99");
		$iCantidad = $consulta -> num_rows();
		$Conexion = $consulta -> result();
		$oCabezera[1] = array("titulo" => "oid", "oculto"=>TRUE);
		$oCabezera[2] = array("titulo" => "fecha", "atributos" => "width:50px", "buscar" => 0);
		$oCabezera[3] = array("titulo" => "Motivo", "atributos" => "width:300px", "buscar" => 0);
		$oCabezera[4] = array("titulo" => "Estatus", "atributos" => "width:50px", "buscar" => 0);
		if ($iCantidad > 0) {
			$i = 0;
			foreach ($Conexion as $row) {
				++$i;
				$estatus = 'Pendiente';
				if($row->peticion != '999') $estatus = 'Cancelado';
				$oFil[$i] = array(
					"1" => $row->id, 
					"2" => $row->fecha,
					"3" => $row->motivo,
					"4" => $estatus
				);				
			}
			$oTable = array("Cabezera" => $oCabezera, "Cuerpo" => $oFil,  "Origen" => "json","msj" => TRUE);

		} else {
			$oTable = array("msj" => FALSE);
		}
		return json_encode($oTable);
	} 

	function procesar($oid){
		$this -> db -> query("UPDATE t_auditar_txt set estatus = 1 WHERE oid=".$oid);
		return "Se proceso con exito";
	}
		
}