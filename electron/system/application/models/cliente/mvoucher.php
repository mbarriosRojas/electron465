<?php
/**
 *  @author Judelvis Rivas
 *  @package cooperativa.system.application.model.cliente
 *  @version 1.0.0
 */

class MVoucher extends Model {

	var $tabla = 't_lista_voucher';

	public function __construct() {
		parent::__construct();
	}

	public function __destruct() {
		unset($this -> MVoucher);
	}

	/**
	 * Funcion para buscar voucher
	 */
	function Busca_Voucher($voucher) {
		$strQuery = "select t_personas.documento_id as Cedula,concat(primer_nombre,' ',segundo_nombre,' ',primer_apellido,' ',segundo_apellido)as Nombre,
		cid as Factura,ndep as Voucher,
		case t_lista_voucher.estatus
		when 0 then 'Entregado al Cliente'
		when 1 then 'Pagado'
		when 2 then 'Trasladado'
		when 3 then 'Pagado Por Domiciliacion'
		when 5 then 'No aplica'
		when 6 then 'Exonerado'
		end as Estatus,fecha as Fecha,monto as Monto,banco as Banco
		from t_lista_voucher
		join (SELECT documento_id,numero_factura from t_clientes_creditos group by numero_factura)as a ON a.numero_factura=t_lista_voucher.cid
		join t_personas on t_personas.documento_id = a.documento_id
		WHERE ndep='$voucher'";

		$Consulta = $this -> db -> query($strQuery);
		$cantidad = $Consulta -> num_rows();
		if ($cantidad > 0) {
			$Object = array("Cabezera" => $Consulta -> list_fields(), "Cuerpo" => $Consulta -> result(), "Origen" => "Mysql", "Paginador" => 10, "msj" => "Cantidad de Voucher asociados al numero:" . $cantidad);
		} else {
			$Object = array("msj" => $cantidad);
		}

		return json_encode($Object);
	}

	/**
	 * Voucher Pendientes por procesar
	 */
	function Voucher_Pendientes($arr) {
		/**
		 *  Ejemplo para llenar arreglo de valores para los combos
		 *  "Objetos" => array("7"=>array("texto"=>"value","texto2" => "value2"))
		 *
		 */
		/*
		 * para aceptar voucher (voucher,factura,observacion,t. voucher)
		* para trasladar voucher (voucher,factura,monto,fecha,origen,observacion)
		*
		*/

		$donde = " WHERE ";
		$c_donde = 0;
		$tipo_vocher = array("0" =>"VOUCHER","1"=> "TRANSFERENCIA","2"=> "DEPOSITO","3"=>"DOMICILIACION","4"=> "T.DEBITO","5"=>"T.CREDITO");
		$query = "select a.documento_id as cedula, ndep,cid,monto,fecha,a.cobrado_en as linaje,pronto,
		case t_lista_voucher.estatus
		when 0 then 'Entregado al Cliente'
		when 1 then 'Pagado'
		when 2 then 'Trasladado'
		when 3 then 'Pagado Por Domiciliacion'
		when 5 then 'No aplica'
		when 6 then 'No Aplica (Exonerado Pronto Pago)'
		end as Estatus_v,
		case a.cantidad
		when 0 then 'NULO'
		else 'ACTIVO'
		end as Estatus_fac,
		case tipo
		when 1 then 'CrediCompra'
		else 'Oficina'
		end as tipo,
		case tipo_voucher
		when 0 then 'VOUCHER'
		when 1 then 'TRANSFERENCIA'
		when 2 then 'DEPOSITO'
		when 3 then 'DOMICILIACION'
		end as tipo_voucher,
		banco, t_lista_voucher.observacion,a.codigo_n
		from t_lista_voucher
		join (SELECT documento_id,numero_factura,sum(cantidad) as cantidad,codigo_n,cobrado_en
		from t_clientes_creditos group by  numero_factura)as a on a.numero_factura = t_lista_voucher.cid
		";
		if ($arr['estatus'] != '') {
			$donde .= " estatus =" . $arr['estatus'];
			$c_donde++;
		}
		if ($arr['banco'] != '') {
			if ($c_donde > 0)
				$donde .= " AND ";
			$donde .= " banco ='" . $arr['banco'] . "'";
			$c_donde++;
		}
		if ($arr['desde'] != '' && $arr['hasta'] != '') {
			$adesde = explode('/', $arr['desde']);
			$ahasta = explode('/', $arr['hasta']);
			$desde = $adesde[2] . '-' . $adesde[1] . '-' . $adesde[0];
			$hasta = $ahasta[2] . '-' . $ahasta[1] . '-' . $ahasta[0];
			if ($c_donde > 0)
				$donde .= " AND ";
			$donde .= "fecha BETWEEN '" . $desde . "' AND '" . $hasta . "'";
			$c_donde++;
		}
		$donde .= " and modo=".$arr['moda'];
		$query .= $donde;
		$query .= ' order by fecha,cid';
		$Consulta = $this -> db -> query($query);
		$iCantidad = $Consulta -> num_rows();
		$Conexion = $Consulta -> result();

		$oCabezera[1] = array("titulo" => "Cedula", "atributos" => "width:80px", "buscar" => 0);
		$oCabezera[2] = array("titulo" => "Factura", "atributos" => "width:50px", "buscar" => 0);
		$oCabezera[3] = array("titulo" => "Voucher", "atributos" => "width:80px", "buscar" => 0);
		$oCabezera[4] = array("titulo" => "Monto", "atributos" => "width:100px", "total" => 1,'buscar'=>0);
		$oCabezera[5] = array("titulo" => "Fecha", "atributos" => "width:100px", "buscar" => 0);
		$oCabezera[6] = array("titulo" => "Estatus");
		$oCabezera[7] = array("titulo" => "Origen", "atributos" => "width:100px", "buscar" => 0);
		$oCabezera[8] = array("titulo" => "E.Factura", "atributos" => "width:100px");
		$oCabezera[9] = array("titulo" => "Ofic.", "atributos" => "width:100px", "buscar" => 0);
		$oCabezera[10] = array("titulo" => "Banco", "atributos" => "width:50px", "buscar" => 0);
		$oCabezera[11] = array("titulo" => "Linaje", "atributos" => "width:100px", "buscar" => 0);
		$oCabezera[12] = array("titulo" => "Pronto", "atributos" => "width:10px", "buscar" => 0);
		if ($arr['estatus'] == '0') {
			$oCabezera[13] = array("titulo" => "Observacion", "tipo" => "texto");
			$oCabezera[14] = array("titulo" => "T.Voucher", "tipo" => "combo", "atributos" => "width:100px");
			$oCabezera[15] = array("titulo" => "A", "tipo" => "bimagen", "funcion" => 'Aceptar_Voucher', "parametro" => "3,2,13,14", "ruta" => __IMG__ . "botones/aceptar1.png", "atributos" => "width:10px");
			//$oCabezera[15] = array("titulo" => "R", "tipo" => "bimagen", "funcion" => 'Rechazar_Voucher', "parametro" => "3,2,4,5,7,12", "ruta" => __IMG__ . "botones/cancelar1.png", "atributos" => "width:10px");
		} else {
			$oCabezera[13] = array("titulo" => "Observacion");
			$oCabezera[14] = array("titulo" => "T.Voucher", "atributos" => "width:100px");
		}
		if ($iCantidad > 0) {
			$i = 0;

			foreach ($Conexion as $row) {
				++$i;
				$tipo = 'Oficina';
				$estado_voucher = '**';

				if ($arr['estatus'] == '0') {
					$oFil[$i] = array("1" => $row -> cedula, "2" => $row -> cid, "3" => $row -> ndep, "4" => $row -> monto, "5" => $row -> fecha, "6" => $row -> Estatus_v, "7" => $row -> tipo, "8" => $row -> Estatus_fac, "9" => $row -> codigo_n, "10" => $row -> banco, "11" => $row -> linaje, "12" => $row -> pronto, "13" => $row -> observacion, "14" => $row -> tipo_voucher, "15" => '');//, "15" => '');
				} else {
					$oFil[$i] = array("1" => $row -> cedula, "2" => $row -> cid, "3" => $row -> ndep, "4" => $row -> monto, "5" => $row -> fecha, "6" => $row -> Estatus_v, "7" => $row -> tipo, "8" => $row -> Estatus_fac, "9" => $row -> codigo_n, "10" => $row -> banco, "11" => $row -> linaje, "12" => $row -> pronto, "13" => $row -> observacion, "14" => $row -> tipo_voucher);
				}
			}
			$objetos = array("14" => $tipo_vocher);

			$oTable = array("Cabezera" => $oCabezera, "Cuerpo" => $oFil, "Paginador" => 50, "Origen" => "json", "Objetos" => $objetos, "msj" => "SI");

		} else {
			$oTable = array("msj" => "NO");
		}
		return json_encode($oTable);
	}

	function CProgramadas($linajes) {
		$rConsulta = "SELECT a.documento_id,factura, voucher, t_cuotas_programadas.observacion,monto,t_cuotas_programadas.estatus,linaje,creacion,
		IFNULL (SUM(t_lista_programadas.cuota),0 ) AS pagado
		FROM t_cuotas_programadas
		LEFT JOIN t_lista_programadas ON  t_lista_programadas.oidvoucher = t_cuotas_programadas.voucher and t_lista_programadas.fact = t_cuotas_programadas.factura
		join (SELECT documento_id,numero_factura,sum(cantidad)as cantidad from t_clientes_creditos where cantidad != 0 group by numero_factura)as a
		on a.numero_factura = t_cuotas_programadas.factura
		WHERE t_cuotas_programadas.estatus =0 ";
		$i = 0;
		$lin = "";
		if (count($linajes) > 0) {
			$lin = " AND (";
			$sOr = "";
			foreach ($linajes as $sC => $sV) {
				if (trim($sV['valor']) != '') {
					++$i;
					if ($i > 1) {
						$sOr = ' OR ';
					}
					$lin .= $sOr . "linaje = '" . $sV['valor'] . "'";
				}
			}
		}
		$lin .= " ) ";
		$rConsulta .= $lin . ' GROUP BY voucher,factura ORDER BY creacion';
		$oCabezera[1] = array("titulo" => " ", "tipo" => "enlace", "funcion" => "Cargar_Monto", "parametro" => "4,3", "metodo" => 1, "ruta" => __IMG__ . "botones/add.png", "atributos" => "width:18px");
		$oCabezera[2] = array("titulo" => "CEDULA", "atributos" => "width:40px", "buscar" => 1);
		$oCabezera[3] = array("titulo" => "FACTURA", "atributos" => "width:40px", "buscar" => 1);
		$oCabezera[4] = array("titulo" => "VOUCHER", "atributos" => "width:60px", "buscar" => 1);
		$oCabezera[5] = array("titulo" => "OBSERVACION", "atributos" => "width:180px");
		$oCabezera[6] = array("titulo" => "MONTO", "atributos" => "width:25px");
		$oCabezera[7] = array("titulo" => "PAGADO", "atributos" => "width:25px");
		$oCabezera[8] = array("titulo" => "ESTATUS", "atributos" => "width:25px");
		$oCabezera[9] = array("titulo" => "LINAJE", "buscar" => 1);
		$oCabezera[10] = array("titulo" => "CREADO");

		$rs = $this -> db -> query($rConsulta);
		$rsC = $rs -> result();
		$oFil = array();
		if ($rs -> num_rows() != 0) {
			$i = 1;
			foreach ($rsC as $row) {
				$oFil[$i++] = array('1' => '', //
						'2' => $row -> documento_id, //
						'3' => $row -> factura, //
						'4' => $row -> voucher, //
						'5' => $row -> observacion, //
						'6' => $row -> monto, '7' => $row -> pagado, '8' => $row -> estatus, '9' => $row -> linaje, '10' => $row -> creacion);
			}
		}

		$oTable = array("Cabezera" => $oCabezera, "Cuerpo" => $oFil, "Origen" => 'json', "Paginador" => 10, "sql" => $rConsulta);
		$oValor['php'] = $oTable;
		$oValor['json'] = json_encode($oTable);
		return $oValor;
	}

	public function Lista_Cobros_Programadas($voucher = '', $factura = '') {
		$estatus = '';
		$estado = "SELECT t_lista_voucher.estatus,t_lista_voucher.observacion ,t_cuotas_programadas.monto
		FROM t_lista_voucher
		left join t_cuotas_programadas on t_cuotas_programadas.voucher = t_lista_voucher.ndep and t_cuotas_programadas.factura = t_lista_voucher.cid
		WHERE ndep='" . $voucher . "' and cid='" . $factura . "'";
		$est = $this -> db -> query($estado);
		foreach ($est -> result() as $row) {
			$estatus = $this -> Estatus_Voucher($row -> estatus);
			$estatus .= '<br>' . $row -> observacion . '<br>MONTO CUOTA TRASLADADA:' . $row -> monto . '<br>Factura:' . $factura;
		}
		$strQuery = "SELECT oidvoucher AS VOUCHER, factura,cuota,fecha,referencia,usuario,modificado,observaciones
		FROM t_lista_programadas
		join t_cuotas_programadas on t_cuotas_programadas.voucher = t_lista_programadas.oidvoucher and t_cuotas_programadas.factura = t_lista_programadas.fact
		WHERE oidvoucher ='" . $voucher . "' and factura='" . $factura . "'";
		$Consulta = $this -> db -> query($strQuery);
		$Object = array("Cabezera" => $Consulta -> list_fields(), "Cuerpo" => $Consulta -> result(), "Origen" => "Mysql", "Paginador" => 10, "msj" => $estatus, "leyenda" => $estatus);
		return json_encode($Object);

	}

	/*
	 * Funciones de Traslado Masivo
	*/

	public function Lista_Por_Trasladar($fecha){
		$consulta = $this -> db -> query("SELECT * FROM t_lista_voucher WHERE estatus = 0 AND pronto=0 AND fecha ='".$fecha."'");
		$cant = $consulta -> num_rows();
		$resultado = $consulta -> result();
		if($cant > 0){
			if($this -> Generar_Lista($resultado) ){
				return $this -> Grid_Lista_Traslados();
			}else{
				$Object = array("msj" => "NO3");
			}
		}else{
			$Object = array("msj" => "NO2");
		}
		return json_encode($Object);
	}
	
	public function Generar_Lista($resultado){
		$insert = "INSERT INTO t_traslados (voucher,factura,monto_t,linaje,modalidad) VALUES";
		$coma = "";
		$mod = 1;
		foreach($resultado as $row){
			$interes = $this -> Monto_Traslado($row -> cid,$row -> monto);
			//$mod = $this -> Verifica_Modalidad($row -> cid);
			$insert .= $coma."('".$row -> ndep ."','".$row -> cid ."',".$interes['monto_t'].",'".$interes['linaje']."',".$mod.")";
			$coma=',';
		}
		$this -> db -> query("TRUNCATE t_traslados");
		$this -> db -> query($insert);
		return true;
	}
	
	public function Monto_Traslado($factura,$monto_v){
		$query = "SELECT (SUM(cantidad)*0.03)/12 AS cant,cobrado_en FROM t_clientes_creditos WHERE numero_factura='" . trim($factura) . "' GROUP BY numero_factura";
		$consulta = $this -> db -> query($query);
		$interes = $consulta -> row();
		$monto = $interes -> cant + floatval($monto_v);
		$linaje = $interes -> cobrado_en;
		$arreglo=array("monto_t"=>$monto,"linaje"=> $linaje);
		return $arreglo;
	}
	
	public function Verifica_Modalidad($factura){
		$strVerifica = "SELECT * FROM t_lista_voucher WHERE (estatus = 2 OR estatus = 3) and cid='" . trim($factura) . "'";
		$verifica = $this -> db -> query($strVerifica);
		$mod = 0;
		if ($verifica -> num_rows() >= 2) {
			$mod = 1;
		}
		return $mod;
	}
	
	public function Grid_Lista_Traslados(){
		$strQuery = "SELECT * FROM t_traslados";
		$Consulta = $this -> db -> query($strQuery);
		$cantidad = $Consulta -> num_rows();
		if ($cantidad > 0) {
			$Object = array("Cabezera" => $Consulta -> list_fields(), "Cuerpo" => $Consulta -> result(), "Origen" => "Mysql", "Paginador" => 50, "msj" => "SI");
		} else {
			$Object = array("msj" => "NO1");
		}	
		return json_encode($Object);
	}
	
	public function Procesa_Traslado_Masivo(){
		$Consulta = $this -> db -> query("SELECT * FROM t_traslados");
		foreach ($Consulta->result() as $row){
			$query = "UPDATE t_lista_voucher SET estatus=2 ,observacion='TRASLADO POR LOTE' WHERE ndep='" . trim($row->voucher) . "' and cid='" . trim($row -> factura) . "'";
			$this -> db -> query($query);
			$data = array("factura" => trim($row -> factura), "voucher" => trim($row -> voucher), "monto" => $row -> monto_t, "tipo" => 0, "estatus" => 0, "linaje" => $row -> linaje);
			$this -> db -> insert("t_cuotas_programadas", $data);
			if($row -> modalidad == 1){
				$msj = $this -> Modificar_Modalidad_Pago(trim($row -> factura), $this -> session -> userdata('usuario'), "FUERON TRASLADADOS 3 O MAS VOUCHER");
			}
		}
		return "SE REALIZO TRASLADO MASIVO CON EXITO...";		
	}

	/*
	 * Fin traslado masivo
	*/


	/*
	 * FUNCIONES PARA MODIFICAR MODALIDAD DE PAGO
	*/

	public function Modificar_Modalidad_Pago($factura, $strPeticion, $strMotivo) {
		$this -> Actualiza_Factura($factura);
		$data = array('referencia' => $factura, 'tipo' => 20, 'usuario' => $strPeticion, 'motivo' => $strMotivo . '(' . $factura . ')', 'peticion' => $strPeticion);
		$this -> db -> insert('_th_sistema', $data);
		return "Se Cambio modalidad De pago Con exito.<br>Factura:".$factura."<br>";
	}

	public function Actualiza_Factura($factura){
		$this -> db -> query("update t_clientes_creditos set marca_consulta=5 where numero_factura='" . $factura . "' and marca_consulta=6");
		$this -> db -> query("update t_lista_voucher set estatus = 5 where cid = '" . $factura . "' and estatus=0 and pronto=0");
		$this -> Carga_Pagos_Voucher($factura);
		$this -> Carga_Pagos_VDomiciliacion($factura);
	}

	public function Carga_Pagos_Voucher($factura){
		$voucher = $this -> db -> query("SELECT * FROM t_lista_voucher WHERE estatus = 1 AND cid='" . $factura . "'");
		$vpagados = $voucher -> num_rows();
		if ($vpagados > 0) {
			foreach ($voucher -> result() as $row) {
				$auxQuery = 'SELECT documento_id,contrato_id,monto_cuota FROM t_clientes_creditos WHERE numero_factura="' . $factura . '" AND monto_cuota=' . $row -> monto . ' AND cantidad!= 0 limit 1';
				$aux = $this -> db -> query($auxQuery);
				if ($aux -> num_rows() > 0) {
					$resultado = $aux -> result();
					$this -> Carga_Lista_Cobros_Voucher($resultado,$row);
				}
			}
		}
	}

	public function Carga_Lista_Cobros_Voucher($arr,$row){
		foreach ($arr as $row2) {
			$des = 'Monto correspondiente al pago del voucher ' . $row -> ndep;
			if($row -> evento < '2013-07-23') {
				$fecha[0] = $row -> fecha;
			}else {
				$fecha = explode(' ', $row -> evento);
			}
			$fechap = explode('-', $row -> fecha);
			$usua = $this -> session -> userdata('oidu');
			$cuota = array('documento_id' => $row2 -> documento_id, 'credito_id' => $row2 -> contrato_id, 
			'mes' => 'Cambio De Modalidad', 'descripcion' => $des, 'monto' => $row -> monto, 
			'fecha' => $fecha[0],'mesp'=>$fechap[1],'anop'=>$fechap[0],'moda'=>11,'farc'=>$fecha[0],'usua'=>$usua);
			$this -> db -> insert('t_lista_cobros', $cuota);
			$cuota = null;
		}
	}

	public function Carga_Pagos_VDomiciliacion($factura){
		$consulta = "SELECT ndep,evento,t_lista_voucher.monto as monto,max(t_lista_programadas.fecha) AS fp,t_lista_voucher.fecha as fv  FROM t_lista_voucher
		JOIN t_lista_programadas ON t_lista_programadas.oidvoucher = t_lista_voucher.ndep WHERE t_lista_voucher.estatus = 3 AND cid='" . $factura . "' GROUP BY ndep";
		$voucher2 = $this -> db -> query($consulta);
		$vpagados2 = $voucher2 -> num_rows();
		if ($vpagados2 > 0) {
			foreach ($voucher2 -> result() as $row) {
				$auxQuery = 'SELECT documento_id,contrato_id,monto_cuota FROM t_clientes_creditos WHERE numero_factura="' . $factura . '" AND monto_cuota=' . $row -> monto . ' AND cantidad!= 0 limit 1';
				$aux = $this -> db -> query($auxQuery);
				if ($aux -> num_rows() > 0) {
					$resultado = $aux -> result();
					$this -> Carga_Lista_Cobros_VDomiciliacion($resultado,$row);
				}
			}
		}
	}

	public function Carga_Lista_Cobros_VDomiciliacion($arr,$row){
		foreach ($arr as $row2) {
			$fechap = explode('-', $row -> fv);
			$usua = $this -> session -> userdata('oidu');
			$des = 'Monto correspondiente al pago del voucher ' . $row -> ndep .'|Pagado por Domiciliacion.';
			$cuota = array('documento_id' => $row2 -> documento_id, 'credito_id' => $row2 -> contrato_id, 
			'mes' => 'Cambio De Modalidad', 'descripcion' => $des, 'monto' => $row -> monto, 'fecha' => $row -> fp
			,'mesp'=>$fechap[1],'anop'=>$fechap[0],'moda'=>11,'farc'=>$row -> fp,'usua'=>$usua);
			$this -> db -> insert('t_lista_cobros', $cuota);
			$cuota = null;
		}

	}

	/*
	 * FIN CAMBIO MODALIDAD
	*/

	public function Estatus_Voucher($est){
		switch ($est) {
			case 0 :
				$estatus = 'ENTREGADO AL CLIENTE. NO SE HA PAGADO';
				break;
			case 1 :
				$estatus = 'PAGADO POR VOUCHER ELECTRON';
				break;
			case 2 :
				$estatus = 'TRASLADADO. NO SE HA PAGADO';
				break;
			case 3 :
				$estatus = 'PAGADO A TRAVES DE TRASLADO.';
				break;
			case 5 :
				$estatus = 'ENTREGADO AL CLIENTE. NO APLICA PARA COBRANZA';
				break;
			case 6 :
				$estatus = 'ENTREGADO AL CLIENTE. (EXONERADO PRONTO PAGO)';
				break;
			default :
				break;
		}
		return $estatus;
	}
	
	public function Eliminar_Voucher_Factura($arr){
		$elimina = "DELETE FROM t_lista_voucher WHERE cid='".$arr['factura']."' and estatus=0"; 
		$this -> db -> query($elimina);
		$data = array('referencia' => $arr['factura'], 
						'tipo' => 23, 
						'usuario' => $_SESSION['usuario'], 
						'motivo' => $arr['motivo'], 
						'peticion' => $arr['peticion']);
		$this -> db -> insert('_th_sistema', $data);
	}
	
	public function Eliminar_Voucher_Deposito($arr){
		$elimina = "DELETE FROM t_lista_voucher WHERE cid='".$arr['factura']."' and ndep='".$arr['ndep']."'"; 
		$this -> db -> query($elimina);
		$data = array('referencia' => $arr['ndep'], 
						'tipo' => 22, 
						'usuario' => $_SESSION['usuario'], 
						'motivo' => $arr['motivo'] . '(' .$arr['factura']."||".$arr['ndep'] . ')' , 
						'peticion' => $arr['peticion']);
		$this -> db -> insert('_th_sistema', $data);	
	}
}
?>