<?php
/**
 *  @author Carlos Enrique Penaa Albarran
 *  @package SaGem.system.application.model
 *  @version 1.0.0
 */
class MFcontrol extends Model {

	/**
	 * Campo index
	 * @var integer
	 */
	var $oid = NULL;

	/**
	 * Numero de Factura
	 * @var string
	 */
	var $factura;
	
	/**
	 * Numero de Control
	 * @var string
	 */
	var $control;

	/**
	 * Cedula Cliente
	 * @var int
	 */
	var $cedula;


	/**
	 * Nombre Completo Cliente
	 * @var string
	 */
	var $nombre;

	/**
	 * Fecha Factura
	 * @var date
	 */
	var $fecha;

	/**
	 * Total Monto De Factura
	 * @var double
	 */
	var $total;


	/**
	 * Telefono
	 * @var string
	 */
	var $telf;

	/**
	 * Empresa
	 * 0:Cooperativa, 1:Grupo
	 * @var int
	 */
	var $empresa;

	/**
	 * Estatus de Factura
	 * 0:Creado , 1:Aceptado
	 * @var int
	 */
	var $estatus;

	/**
	 * Ubicacion donde se Creo la Factura
	 * @var string
	 */
	var $ubicacion;

	/**
	 * Usuario quien crea la factura
	 * @var string
	 */
	var $usuario;

	/**
	 * Modificado
	 * @var date
	 */
	var $modificado;

	function __construct() {
		parent::__construct();

	}

	function Guardar($item) {
		$fact = $this -> factura;
		$control = $this -> control;
		$msj = '';
		if ($this -> db -> insert('t_fcontrol', $this)) {
			$cadena = explode(',', $item);
			foreach ($cadena as $it) {
				$it_picado = explode('|', $it);
				$datos['factura'] = $fact;
				$datos['control'] = $control;
				$datos['cantidad'] = $it_picado[0];
				$datos['descrip'] = $it_picado[1];
				$datos['monto'] = $it_picado[2];
				$this -> db -> insert('t_it_fcontrol', $datos);

			}
			$msj = "Se registro factura con exito...";
		} else {
			$msj = "No se pudo registrar la factura";
		}
		return $msj;

	}

	function Busca($factura,$control) {
		$consulta = $this -> db -> query("SELECT * FROM t_fcontrol WHERE factura='" . $factura . "' and control='".$control."'");
		if ($consulta -> num_rows() > 0) {
			return TRUE;
		}
		return FALSE;
	}

	public function Consultar($factura = null,$control=null) {
		$j = 0;
		$jsP = array();
		$jsI = array();
		//Una Persona Json
		$Consulta = $this -> db -> query("SELECT * FROM t_fcontrol WHERE factura='" . $factura . "' and control='".$control."'");
		foreach ($Consulta->result() as $row) {
			foreach ($row as $NombreCampo => $ValorCampo) {
				$jsP[$NombreCampo] = $ValorCampo;
			}
		}
		$Consulta = $this -> db -> query('SELECT * FROM t_it_fcontrol WHERE factura="' . $factura . '" and control="'.$control.'"');
		foreach ($Consulta->result() as $row) {
			$j++;
			$jsI[$j] = array('cantidad' => $row -> cantidad, 'descrip' => $row -> descrip, 'monto' => $row -> monto);
		}
		$jsP['productos'] = $jsI;

		return json_encode($jsP);
	}

	function Listar_Fcontrol($arr = null) {
		if ($arr != null) {
			if ($arr['ubicacion'] == 'TODOS')
				$ubi = '';
			else
				$ubi = " AND ubicacion ='" . $arr['ubicacion'] . "'";
			$consulta = "SELECT *,if(estatus=0,\"Activa\",\"Nula\")as estatusT FROM t_fcontrol WHERE fecha BETWEEN '" . $arr['desde'] . "' AND '" . $arr['hasta'] . "' " . $ubi;
		} else {
			$consulta = "SELECT *,if(estatus=0,\"Activa\",\"Nula\")as estatusT FROM t_fcontrol";
		}
		$busqueda = $this -> db -> query($consulta);

		$cant = $busqueda -> num_rows();
		$filas = array();
		if ($cant > 0) {
			$oCabezera[1] = array("titulo" => " ", "tipo" => "detallePost", "atributos" => "width:40px", "funcion" => "Detalle_Fcontrol", "parametro" => "3", "atributos" => "width:12px");
			$oCabezera[2] = array("titulo" => "Cedula", "atributos" => "width:50px","buscar"=>1);
			$oCabezera[3] = array("titulo" => "Factura", "atributos" => "width:50px;text-align:center;","buscar"=>1);
			$oCabezera[4] = array("titulo" => "Nombre", "atributos" => "width:100px;text-align:right;","tipo"=>"texto");
			$oCabezera[5] = array("titulo" => "Control", "atributos" => "width:100px;text-align:right;","tipo"=>"texto");
			$oCabezera[6] = array("titulo" => "Telefono", "atributos" => "width:100px;text-align:center;","tipo"=>"texto");
			$oCabezera[7] = array("titulo" => "Fecha");
			$oCabezera[8] = array("titulo" => "Total","tipo"=>"texto");
			$oCabezera[9] = array("titulo" => "Empresa","buscar"=>1);
			$oCabezera[10] = array("titulo" => "Ubicacion");
			$oCabezera[11] = array("titulo" => "Estatus","buscar"=>1);
			$oCabezera[12] = array("titulo" => "#", "tipo" => "enlace", "metodo" => 2, "funcion" => "Formato_Fcontrol", "parametro" => "3,15", "ruta" => __IMG__ . "botones/print.png", "atributos" => "width:12px", "target" => "_blank");
			$oCabezera[13] = array("titulo" => "#", "tipo" => "bimagen", "funcion" => 'Modifica_Fcontrol', "parametro" => "3,2,4,5,6,8", "ruta" => __IMG__ . "botones/aceptar1.png", "atributos" => "width:10px","mantiene"=>1);
			$oCabezera[14] = array("titulo" => "#", "tipo" => "bimagen", "funcion" => 'Anular_Fcontrol', "parametro" => "3", "ruta" => __IMG__ . "botones/cancelar1.png", "atributos" => "width:10px","mantiene"=>1);
			$oCabezera[15] = array("titulo" => "","oculto" => 1);
			$i = 0;
			foreach ($busqueda -> result() as $fpresu) {
				$i++;
				$eti='';$eti2='';
				if($fpresu->estatus == 1){$eti='<font color=red>';$eti2='</font>';}
				$filas[$i] = array("1" => "",
							"2" => $fpresu -> cedula,
							"3" => $fpresu -> factura,
							"4" => $fpresu -> nombre,
							"5" => $fpresu -> control,
							"6" => $fpresu -> telf,
							"7" => $fpresu -> fecha,
							"8" => $fpresu -> total,
							"9" => $this -> Empresa($fpresu -> empresa),
							"10" => $fpresu -> ubicacion,
							"11" => $eti.$fpresu -> estatusT.$eti2,
							"12" => "",
							"13" => "",
							"14" => "",
							"15" => $fpresu -> empresa
							);
			}
			$Object = array("Cabezera" => $oCabezera, "Cuerpo" => $filas, "Origen" => "json", "msj" => 1,"Paginador"=>50);
		} else {
			$Object = array("msj" => 0);
		}
		return json_encode($Object);

	}

	function Listar_FControl_Detalle($factura){
		$sql = "SELECT cantidad AS Cantidad, descrip AS Descripcion, monto AS Precio_Unitario, (cantidad*monto) AS Total FROM t_it_fcontrol WHERE factura='". $factura ."'";
		$consulta = $this -> db -> query($sql);
		$cantidad = $consulta -> num_rows();
		if($cantidad > 0){
			$Object = array("Cabezera" => $consulta -> list_fields(), "Cuerpo" => $consulta -> result(), "Origen" => "Mysql","leyenda"=>"<br><br>","titulo"=>"<br><br>");
		}
		return json_encode($Object);
	}
	
	function Empresa($emp){
		switch($emp){
			case 0:
				return 'COOPERATIVA';
				break;
			case 1:
				return 'GRUPO';
				break;
		}
	}
	
	function PagarFactura($sfactura, $fecha){
		$sQuery = "SELECT * FROM t_clientes_creditos WHERE numero_factura='" . $sfactura . "';";
		$rs = $this -> db -> query($sQuery);
		$cont = array();
		foreach ($rs->result() as $clav) {
			if($clav->marca_consulta == 6){				
				$resta = $this->restaPorVoucher($sfactura, $clav->monto_total, $clav->monto_cuota);
				
			}else{
				$resta = $this->restaPorContrato($clav->contrato_id, $clav->monto_total);				
			}
			$cont[] = $sfactura . '|' . $clav->contrato_id . '|' . $resta . '|' . $fecha;
		} 
		return $cont;
		
		
	}
	
	/**
	 * @update 01/02/2016
	 * Consultar deuda restante por factura
	 * @author Carlos Peña
	 * @return double
	 */	
	function restaPorContrato($contrato, $monto){
		$sQuery = "SELECT SUM(monto) AS monto_total FROM t_lista_cobros WHERE credito_id='" . $contrato . "'";
		$rs = $this -> db -> query($sQuery);
		$cont = array();
		foreach ($rs->result() as $clav) {			
			$cont = $clav->monto_total;
		}
		$resta = $monto - $cont;  
		return $resta;
	}
	
/**
	 * @update 01/02/2016
	 * Consultar deuda restante por factura
	 * @author Carlos Peña
	 * @return double
	 */	
	function restaPorVoucher($contrato, $monto, $monto_cuota){
			//SELECT * FROM `t_lista_voucher` WHERE `cid` LIKE 'GN004049'
			
		$sQuery = "SELECT SUM(monto) AS monto_total, monto FROM t_lista_voucher WHERE cid='" . $contrato . "' AND (estatus = 1 OR estatus = 3)";
		$rs = $this -> db -> query($sQuery);
		$cont = 0;
		foreach ($rs->result() as $clav) {
			if($monto_cuota == $clav->monto){
				$cont = $clav->monto_total;
			}
			
		}
		$resta = $monto - $cont;  
		return $resta;
		
	}

}
?>