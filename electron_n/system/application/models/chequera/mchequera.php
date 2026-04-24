<?php
/*
 *  @author Carlos Enrique Peña Albarrán
 *  @package cooperativa.system.application.model.cliente
 *  @version 1.0.0
 */

class MChequera extends Model {

	public $oid = null;

	/**
	 * Codigo de Banco
	 */
	public $nombre;
	
	/**
	 * Numero de Cuenta
	 */
	public $cuenta;

	/**
	 * Descripcion del banco
	 */
	public $descripcion;

	/**
	 * Ubicacion
	 */
	public $oidu;

	/**
	 * Fecha del banco
	 */
	public $fecha;

	/**
	 * Cantidad de Cheques para la chequera
	 */
	public $cantidad;
	
	public $nchequera;
	
	public $lista_cuenta = array();

	public function __construct() {
		parent::__construct();
	}

	function jsCheques($oCheque){
		$j = 0;
		$jsP = array();
		$jsC = array();
		$nchequera = '';
		$nchequeraS = '';
		if($oCheque['nchequera'] != 'TODOS'){
			$nchequera = ' AND t_chequera.nchequera=\'' . $oCheque['nchequera'] . '\'';
			$nchequeraS = ' AND t_serialescheques.nchequera=\'' . $oCheque['nchequera'] . '\'';
			
		}
		$Consulta = $this -> db -> query('SELECT t_chequera.nombre ,t_chequera.cuenta, t_banco.nombre as A, t_chequera.oidu FROM t_banco JOIN t_chequera ON t_banco.banco_id=t_chequera.nombre 
		WHERE t_chequera.nombre=\'' . $oCheque['banco'] . '\' AND t_chequera.cuenta=\'' . $oCheque['numero'] . '\'  AND 
		t_chequera.oidu=\'' . $oCheque['ubicacion'] . '\' '. $nchequera .'
		GROUP BY t_chequera.cuenta,fecha' );
		$Conexion = $Consulta -> result();
		$cantidad = $Consulta -> num_rows();
		if($cantidad > 0){
			$oCabezeraR[1] = array("titulo" => "N&uacute;mero de Cheque");
			$oCabezeraR[2] = array("titulo" => "Fecha de Entrega");
			$oCabezeraR[3] = array("titulo" => "Monto ");
			$oCabezeraR[4] = array("titulo" => "Factura");
			$oCabezeraR[5] = array("titulo" => "Observacion", "tipo" => "texto");
			if($oCheque['estatus']  < 2 &&  $oCheque['estatus']  != 'TODOS'){
				$oCabezeraR[6] = array("titulo" => " ", "tipo" => "bimagen", "atributos" => "width:20px", "funcion" => "AceptarCheque", "parametro" => "1,4,5", "ruta" => __IMG__ . "botones/agregar.png");
				$oCabezeraR[7] = array("titulo" => " ", "tipo" => "bimagen", "atributos" => "width:20px", "funcion" => "RechazarCheque", "parametro" => "1,4,5", "ruta" => __IMG__ . "botones/quitar.png");
			}
			$titulo = "NOMBRE BANCO : (" . $Conexion[0]->A . ")<br>
			NUMERO CUENTA : (" . $Conexion[0]->cuenta . ")<br>";
			
			$valor = '';
			
			if($oCheque['estatus']  != 'TODOS'){
				$valor = ' AND t_serialescheques.estatus=' . $oCheque['estatus'];
			}
			$sQuery = '
			SELECT DISTINCT(numero_factura), t_serialescheques.serie,t_serialescheques.oid, t_clientes_creditos.numero_factura AS factura,
			t_serialescheques.observacion, t_clientes_creditos.fecha_operacion, t_clientes_creditos.monto_operacion, t_clientes_creditos.fecha_solicitud 
			FROM t_serialescheques 
			LEFT JOIN t_clientes_creditos ON (CAST(t_serialescheques.oid AS CHAR) = t_clientes_creditos.num_operacion OR CAST(t_serialescheques.oid AS CHAR)  = t_clientes_creditos.ncheque OR  t_serialescheques.serie = t_clientes_creditos.num_operacion OR t_serialescheques.serie = t_clientes_creditos.ncheque) AND t_clientes_creditos.fecha_solicitud > \'2012-10-21\' 		 
			WHERE oidc=\'' . $Conexion[0]->nombre . '-' . $Conexion[0]->oidu .'-' . $Conexion[0]->cuenta . '\'' . $valor . $nchequeraS  ;
			
			$Consulta2 = $this -> db -> query($sQuery);
			
			
			$i = 0;
			$Conexion2 = $Consulta2 -> result();
			if ($Consulta2 -> num_rows() > 0) {
				foreach ($Conexion2 as $row2) {
					++$i;
					$arr["1"] =  $row2 -> serie;
					$arr["2"] =  $row2 -> fecha_operacion;
					$arr["3"] =  $row2 -> monto_operacion;
					$arr["4"] =  $row2 -> factura;
					$arr["5"] =  $row2 -> observacion;
					if($oCheque['estatus']  < 2 &&  $oCheque['estatus']  != 'TODOS'){
						$arr["6"] =  "";
						$arr["7"] =  "";
					}
					$oFil2[$i] = $arr;
				}
				$jsP = array("Cabezera" => $oCabezeraR, "Cuerpo" => $oFil2, "titulo" => $titulo, "Origen" => "json");
			} else {
				$jsP = NULL;
			}
		}else {
			$jsP = NULL;
		}
		return json_encode($jsP);  
	}
	
	function Listar_Cuenta($sCod){
		$sQuery = "SELECT t_chequera.nombre,t_chequera.cuenta FROM t_banco LEFT JOIN t_chequera ON t_banco.banco_id=t_chequera.nombre 
		WHERE t_chequera.nombre='$sCod' GROUP BY t_chequera.cuenta";
		$rsCon = $this -> db -> query($sQuery);
		if ($rsCon -> num_rows() > 0) {
			$rs = $rsCon -> result();
			foreach ($rs as $rw) {
					$this -> lista_cuenta[] = array('oid' => $rw -> nombre, 'valor' => $rw -> cuenta);
			}
		}
		return $this->lista_cuenta;
		
	}
	
	function Actualizar($arrCodigo){
		
		$this->db->query('UPDATE t_serialescheques SET observacion=\'' .  $arrCodigo[2] . '\', estatus=' .  $arrCodigo[3] . ' WHERE serie=\'' . $arrCodigo[0] . '\' LIMIT 1;');
		
	}
	
	function Disponibles($cheque = '', $ubicacion){
		$lugar = $this -> db -> query("SELECT oid FROM t_ubicacion WHERE descripcion='".$ubicacion."'");
		$intLugar = 0;
		
		foreach ($lugar -> result() as $item) {
			$intLugar = $item -> oid;
		}
		$query = "SELECT * FROM t_serialescheques WHERE oidc LIKE '%-". $intLugar ."-%' AND serie like'%".$cheque."%' AND estatus = 0";
		
		$cheques = $this -> db -> query($query);
		foreach ($cheques -> result() as $row) {
			$lst[$row -> oid] =$row -> serie."|".$row -> oidc."|".$row -> oid;
		}
		return $lst;
		//return $query;
	}
	
	function Cmb_Cheques($ubicacion){
		$lugar = $this -> db -> query("SELECT oid FROM t_ubicacion WHERE descripcion='".$ubicacion."'");
		$intLugar = 0;
	
		foreach ($lugar -> result() as $item) {
			$intLugar = $item -> oid;
		}
		$query = "SELECT * FROM t_serialescheques WHERE oidc LIKE '%-". $intLugar ."-%' AND estatus = 0";
	
		$cheques = $this -> db -> query($query);
		$html = '';
		foreach ($cheques -> result() as $row) {
			$html .= '<option value="'.$row -> serie.'|'.$row -> oidc.'|'.$row -> oid.'">'.$row -> serie.'</option>';
		}
		return $html;
		//return $query;
	}
	
	
}
?>