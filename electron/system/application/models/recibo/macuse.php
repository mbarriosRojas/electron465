<?php
/*
 *  @author Carlos Enrique Peña Albarrán
 *  @package cooperativa.system.application.model.cliente
 *  @version 1.0.0
 */

class macuse extends Model {

	public $oid = null;

	var $acuse = "";

	var $cedula_rif = "";

	var $nombre_razon = "";

	var $monto = 0;

	var $concepto = "";

	var $banco = '';

	var $chequera = '';

	var $cheque = '';

	var $fecha = '';

	public function __construct() {
		parent::__construct();
	}

	public function Guarda_Acuse($arr = null) {
		$data['acuse'] = $arr['acuse'];
		$data['cedula_rif'] = $arr['tipo'] . $arr['cedula'];
		$data['nombre_razon'] = $arr['nombre'];
		$data['monto'] = $arr['monto'];
		$data['concepto'] = $arr['concepto'];
		$data['banco'] = $arr['banco'];
		$data['chequera'] = $arr['chequera'];
		$data['cheque'] = $arr['cheque'];
		$data['fecha'] = $arr['fecha'];
		$data['tipo'] = $arr['pago'];

		

		$respuesta = $this -> db -> insert('t_acuse', $data);
		if ($respuesta){
			if($arr['pago'] == 1){
				$sConsulta = 'UPDATE _th_sistema SET peticion=998 WHERE id=' .$arr['pendiente'] . ' LIMIT 1;';
				$this -> db ->query($sConsulta);
				//echo $sConsulta;
			}
			return "Se registro acuse " . $arr['acuse'];
		}else{
			return "No se pudo registrar acuse " . $arr['acuse'];
		}

	}

	public function Listar() {
		$rConsulta = "SELECT * FROM t_acuse WHERE modificado > '2016-10-01'";
		
		$oCabezera[1] = array("titulo" => "Acuse", "atributos" => "width:40px", "buscar" => 1);
		$oCabezera[2] = array("titulo" => "Fecha", "atributos" => "width:40px", "buscar" => 1);
		$oCabezera[3] = array("titulo" => "Cedula/Rif", "atributos" => "width:50px");
		$oCabezera[4] = array("titulo" => "Nombre/Razon", "atributos" => "width:100px", "buscar" => 1);
		$oCabezera[5] = array("titulo" => "Monto", "atributos" => "width:25px");
		$oCabezera[6] = array("titulo" => "Banco", "atributos" => "width:25px");
		$oCabezera[7] = array("titulo" => "Chequera", "atributos" => "width:25px");
		$oCabezera[8] = array("titulo" => "Cheque", "buscar" => 1);
		$oCabezera[9] = array("titulo" => "Modificado");
		$oCabezera[10] = array("titulo" => "#", "tipo" => "enlace", "metodo" => 2, "funcion" => "Imprimir_Acuse", "parametro" => "1", "ruta" => __IMG__ . "botones/print.png", "atributos" => "width:12px", "target" => "_blank");

		$rs = $this -> db -> query($rConsulta);
		$rsC = $rs -> result();
		$oFil = array();
		if ($rs -> num_rows() != 0) {
			$i = 1;
			foreach ($rsC as $row) {
				$oFil[$i++] = array('1' => $row -> acuse, //
				'2' => $row -> fecha, //
				'3' => $row -> cedula_rif, //
				'4' => $row -> nombre_razon, //
				'5' => $row -> monto, //
				'6' => $row -> banco, '7' => $row -> chequera, '8' => $row -> cheque, '9' => $row -> modificado, '10' => '');
			}
			$oTable = array("Cabezera" => $oCabezera, "Cuerpo" => $oFil, "Origen" => 'json', "Paginador" => 10, "sql" => $rConsulta,"msj" => 'SI');
		}else{
			$oTable = array("msj" => 'NO');
		}

		$oValor = json_encode($oTable);
		return $oValor;
	}

}
?>