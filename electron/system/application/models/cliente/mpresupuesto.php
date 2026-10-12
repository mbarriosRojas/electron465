<?php
/**
 *  @author Carlos Enrique Penaa Albarran
 *  @package SaGem.system.application.model
 *  @version 1.0.0
 */
class MPresupuesto extends Model {

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
	 * Cedula Cliente
	 * @var int
	 */
	var $cedula;

	/**
	 * Certificado de Verificacion
	 * @var int
	 */
	var $cert;

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
	 * Direccion
	 * @var string
	 */
	var $direccion;

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
		$msj = '';
		if ($this -> db -> insert('t_fpresupuesto', $this)) {
			$cadena = explode(',', $item);
			foreach ($cadena as $it) {
				$it_picado = explode('|', $it);
				$datos['factura'] = $fact;
				$datos['cantidad'] = $it_picado[0];
				$datos['descrip'] = $it_picado[1];
				$datos['monto'] = $it_picado[2];
				$this -> db -> insert('t_it_fpresupuesto', $datos);

			}
			$msj = "Se registro factura con exito...";
		} else {
			$msj = "No se pudo registrar la factura";
		}
		return $msj;

	}

	function Busca($factura) {
		$consulta = $this -> db -> query("SELECT * FROM t_fpresupuesto WHERE factura='" . $factura . "'");
		if ($consulta -> num_rows() > 0) {
			return TRUE;
		}
		return FALSE;
	}

	public function Consultar($factura = null) {
		$j = 0;
		$jsP = array();
		$jsI = array();
		//Una Persona Json
		$Consulta = $this -> db -> query('SELECT * FROM t_fpresupuesto WHERE factura="' . $factura . '" LIMIT 1');
		foreach ($Consulta->result() as $row) {
			foreach ($row as $NombreCampo => $ValorCampo) {
				$jsP[$NombreCampo] = $ValorCampo;
			}
		}
		$Consulta = $this -> db -> query('SELECT * FROM t_it_fpresupuesto WHERE factura="' . $factura . '"');
		foreach ($Consulta->result() as $row) {
			$j++;
			$jsI[$j] = array('cantidad' => $row -> cantidad, 'descrip' => $row -> descrip, 'monto' => $row -> monto);
		}
		$jsP['productos'] = $jsI;

		return json_encode($jsP);
	}

	function Listar_FPresupuesto($arr = null) {
		if ($arr != null) {
			if ($arr['ubicacion'] == 'TODOS')
				$ubi = '';
			else
				$ubi = " AND ubicacion ='" . $arr['ubicacion'] . "'";
			$consulta = "SELECT * FROM t_fpresupuesto WHERE fecha BETWEEN '" . $arr['desde'] . "' AND '" . $arr['hasta'] . "' " . $ubi;
		} else {
			$consulta = "SELECT * FROM t_fpresupuesto";
		}
		$busqueda = $this -> db -> query($consulta);

		$cant = $busqueda -> num_rows();
		$filas = array();
		if ($cant > 0) {
			$oCabezera[1] = array("titulo" => " ", "tipo" => "detallePost", "atributos" => "width:40px", "funcion" => "Detalle_Fpresu", "parametro" => "3", "atributos" => "width:12px");
			$oCabezera[2] = array("titulo" => "Cedula", "atributos" => "width:50px", "buscar" => 1);
			$oCabezera[3] = array("titulo" => "Factura", "atributos" => "width:50px;text-align:center;");
			$oCabezera[4] = array("titulo" => "Nombre", "atributos" => "width:100px;text-align:right;", "tipo" => "texto");
			$oCabezera[5] = array("titulo" => "Direccion", "atributos" => "width:100px;text-align:right;", "tipo" => "texto");
			$oCabezera[6] = array("titulo" => "Telefono", "atributos" => "width:100px;text-align:center;", "tipo" => "texto");
			$oCabezera[7] = array("titulo" => "Fecha");
			$oCabezera[8] = array("titulo" => "Total", "tipo" => "texto");
			$oCabezera[9] = array("titulo" => "Empresa");
			$oCabezera[10] = array("titulo" => "Ubicacion");
			$oCabezera[11] = array("titulo" => "#", "tipo" => "enlace", "metodo" => 2, "funcion" => "Formato_Fpresupuesto", "parametro" => "3,14", "ruta" => __IMG__ . "botones/print.png", "atributos" => "width:12px", "target" => "_blank");

			if ($this -> session -> userdata('usuario') == 'mvanalista11') {
				$oCabezera[12] = array("titulo" => "#", "tipo" => "bimagen", 
					"funcion" => 'Modifica_Fpresu', 
					"parametro" => "3,2,4,5,6,8", 
					"ruta" => __IMG__ . "botones/aceptar1.png", 
					"atributos" => "width:10px", 
					"mantiene" => 1,
					"oculto" => 1);
				$oCabezera[13] = array("titulo" => "#", "tipo" => "bimagen", 
					"funcion" => 'Elimina_Fpresu', 
					"parametro" => "3", 
					"ruta" => __IMG__ . "botones/cancelar1.png", 
					"atributos" => "width:10px", 
					"mantiene" => 1,
					"oculto" => 1);
				$oCabezera[14] = array("titulo" => "#", "oculto" => 1);
			} else {
				$oCabezera[12] = array("titulo" => "#", "tipo" => "bimagen", "funcion" => 'Modifica_Fpresu', "parametro" => "3,2,4,5,6,8", "ruta" => __IMG__ . "botones/aceptar1.png", "atributos" => "width:10px", "mantiene" => 1);
				$oCabezera[13] = array("titulo" => "#", "tipo" => "bimagen", "funcion" => 'Elimina_Fpresu', "parametro" => "3", "ruta" => __IMG__ . "botones/cancelar1.png", "atributos" => "width:10px", "mantiene" => 1);
				$oCabezera[14] = array("titulo" => "#", "oculto" => 1);
			}

			$i = 0;
			foreach ($busqueda -> result() as $fpresu) {
				$i++;
				$filas[$i] = array("1" => "", "2" => $fpresu -> cedula, "3" => $fpresu -> factura, "4" => $fpresu -> nombre, "5" => $fpresu -> direccion, "6" => $fpresu -> telf, "7" => $fpresu -> fecha, "8" => $fpresu -> total, "9" => $this -> Empresa($fpresu -> empresa), "10" => $fpresu -> ubicacion, "11" => "", "12" => "", "13" => "", "14" => $fpresu -> empresa);
			}
			$Object = array("Cabezera" => $oCabezera, "Cuerpo" => $filas, "Origen" => "json", "msj" => 1);
		} else {
			$Object = array("msj" => 0);
		}
		return json_encode($Object);

	}

	function Listar_FPresupuesto_Detalle($factura) {
		$sql = "SELECT cantidad AS Cantidad, descrip AS Descripcion, monto AS Precio_Unitario, (cantidad*monto) AS Total FROM t_it_fpresupuesto WHERE factura='" . $factura . "'";
		$consulta = $this -> db -> query($sql);
		$cantidad = $consulta -> num_rows();
		if ($cantidad > 0) {
			$Object = array("Cabezera" => $consulta -> list_fields(), "Cuerpo" => $consulta -> result(), "Origen" => "Mysql");
		}
		return json_encode($Object);
	}

	function Empresa($emp) {
		switch($emp) {
			case 0 :
				return 'COOPERATIVA';
				break;
			case 1 :
				return 'GRUPO';
				break;
		}
	}

}
?>