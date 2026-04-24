<?php
/**
 * Controlador de Barra de menu
 *
 * @author Carlos Enrique Peña Albarrán
 * @package cooperativa.system.application.model.reporte
 * @version 2.0.0
 */
class MReporte extends Model {



	public function __construct() {
		parent::__construct();

	}
	
	/**
	 * 
	 */
	function Cuadre_Caja($Arr){
		$empresa = '';
		$nomina = '';
		$banco = '';
		$oFil = array();
		if($Arr['empresa'] != 9){
			$empresa = ' AND t_clientes_creditos.empresa='.$Arr['empresa'];	
		}
		if($Arr['nomina'] != 'TODOS'){
			$nomina = ' AND nomina_procedencia="'.$Arr['nomina'].'"'; 
		}
		if($Arr['banco'] != 'TODOS'){
			$banco = ' AND cobrado_en = "' . $Arr['banco'] .'" '; 
		}else{
			$prv = $this -> session -> userdata('union');
			$banco .= 'AND (';
			$k=0;
			foreach($prv['lista_linaje'] as $linaje){
				if($k ==0){
					$banco .= 'cobrado_en = "'.$linaje['valor'].'"';
					$k++;
				}else{
					$banco .= ' OR cobrado_en = "'.$linaje['valor'].'"';
				}
			}
			$banco .= ') ';
		}
		$sConsulta = "
		SELECT nomina_procedencia, SUM( monto ) AS monto, t_lista_cobros.fecha
			FROM t_clientes_creditos
			JOIN t_lista_cobros ON t_clientes_creditos.contrato_id = t_lista_cobros.credito_id
			WHERE t_lista_cobros.farc BETWEEN '". $Arr['desde'] ."' AND '". $Arr['hasta'] ."' ".$banco .$empresa .$nomina."			
			GROUP BY nomina_procedencia ORDER BY t_lista_cobros.fecha
		";
		
		//$oCabezera[1] = array("titulo" => " ", "tipo" => "detallePost", "atributos" => "width:40px", "funcion" => "MuestraDetalleReporte", "parametro" => "3", "atributos" => "width:12px");		
		$oCabezera[1] = array("titulo" => "Nomina", "atributos" => "width:250px");
		//$oCabezera[2] = array("titulo" => "Fecha");
		$oCabezera[2] = array("titulo" => "Monto", "total" => 1);
		
		
		$Consulta = $this -> db -> query($sConsulta);
		$i=0;
		$Conexion = $Consulta -> result();
		foreach ($Conexion as $row) {
			++$i;
		
			$oFil[$i] = array(
				"1" => $row -> nomina_procedencia, 
				//"2" => $row -> fecha, 
				"2" => $row -> monto, 
			);
			
		}
		
		
		
		$Object = array("Cabezera" => $oCabezera, "Cuerpo" => $oFil, "Origen" => "json", "Paginador" => 0);
		return json_encode($Object);
	}
	
	function ccargas_domi($desde,$hasta){
		$query = "select t_lista_cobros.documento_id as cedula,t_lista_cobros.credito_id as contrato,
			mes,descripcion,fecha,monto,t_lista_cobros.modificado as procesado,
			mesp,anop,nomb,	cobrado_en,nomina_procedencia
		from t_lista_cobros 
		join t_clientes_creditos as A on A.contrato_id=t_lista_cobros.credito_id
		LEFT join t_modalidad as B on B.oid = t_lista_cobros.moda
		where modificado between '".$desde."' and '".$hasta."' order by t_lista_cobros.credito_id";
		//return $query;
		$his = $this -> db -> query($query);
		$rsHis = $his -> result();
		$cant = $his -> num_rows();
		$objeto = array();
		if ($cant > 0) {
			$Cabezera = $this -> Cab_Domi();
			$i=0;
			$cuerpo= array();
			foreach ($rsHis as $rs) {
				$i++;
				$cuerpo[$i] = array('1'=>$rs->cedula,'2'=>$rs->contrato,'3'=>$rs->mes,'4'=>$rs->descripcion,'5'=>$rs->fecha,
										'6'=>$rs->monto,'7'=>$rs->procesado,'8'=>$rs->mesp.'-'.$rs->anop,'9'=>$rs->nomb,
										'10'=>$rs->cobrado_en,'11'=>$rs->nomina_procedencia);
			}
			$objeto = array("Cabezera" => $Cabezera, "Cuerpo" => $cuerpo, "Paginador" => 100, "Origen" => 'json', "msj" => 1);
		} else {
			$objeto = array("msj" => 0);
		}
		return json_encode($objeto);
	}
	
	function Cab_Domi() {
		
		$oCab[1] = array("titulo" => "Cedula", "atributos" => "width:50px;text-align:center;");
		$oCab[2] = array("titulo" => "Contrato", "atributos" => "width:50px;text-align:right;");
		$oCab[3] = array("titulo" => "Caso", "atributos" => "width:100px;text-align:right;");
		$oCab[4] = array("titulo" => "Descripcion", "atributos" => "width:100px;text-align:center;");
		$oCab[5] = array("titulo" => "Fecha");
		$oCab[6] = array("titulo" => "Monto", "total" => 1, "atributos" => "width:50px;text-align:center;");
		$oCab[7] = array("titulo" => "F. Procesado", "atributos" => "width:50px;text-align:left;");
		$oCab[8] = array("titulo" => "Cuota Abona", "atributos" => "width:50px;text-align:center;");
		$oCab[9] = array("titulo" => "Modalidad");
		$oCab[10] = array("titulo" => "Linaje");
		$oCab[11] = array("titulo" => "Nomina");
		
		return $oCab;
	}
	
	function ccargas_vou($desde,$hasta){
		$query = "select * from t_lista_voucher 
							join (
								select numero_factura as factura, nomina_procedencia,cobrado_en,documento_id
								from t_clientes_creditos group by numero_factura
							)as A on A.factura = t_lista_voucher.cid
				where evento between '".$desde."' and '".$hasta."' and estatus = 1 order by evento";
		//return $query;
		$his = $this -> db -> query($query);
		$rsHis = $his -> result();
		$cant = $his -> num_rows();
		$objeto = array();
		if ($cant > 0) {
			$Cabezera = $this -> Cab_Vou();
			$i=0;
			$cuerpo= array();
			foreach ($rsHis as $rs) {
				$i++;
				$cuerpo[$i] = array('1'=>$rs->documento_id,'2'=>$rs->cid,'3'=>$rs->ndep,'4'=>$rs->observacion,'5'=>$rs->fecha,
										'6'=>$rs->monto,'7'=>$rs->evento,'8'=>$rs->banco,'9'=>$rs->cobrado_en,
										'10'=>$rs->nomina_procedencia);
			}
			$objeto = array("Cabezera" => $Cabezera, "Cuerpo" => $cuerpo, "Paginador" => 100, "Origen" => 'json', "msj" => 1);
		} else {
			$objeto = array("msj" => 0);
		}
		return json_encode($objeto);
	}
	
	function Cab_Vou() {
		
		$oCab[1] = array("titulo" => "Cedula", "atributos" => "width:50px;text-align:center;");
		$oCab[2] = array("titulo" => "Factura", "atributos" => "width:50px;text-align:right;");
		$oCab[3] = array("titulo" => "Deposito", "atributos" => "width:100px;text-align:right;");
		$oCab[4] = array("titulo" => "Descripcion", "atributos" => "width:250px;text-align:center;");
		$oCab[5] = array("titulo" => "Fecha","atributos" => "width:100px;text-align:center;");
		$oCab[6] = array("titulo" => "Monto", "total" => 1, "atributos" => "width:50px;text-align:center;");
		$oCab[7] = array("titulo" => "F. Procesado", "atributos" => "width:50px;text-align:left;");
		$oCab[8] = array("titulo" => "Banco Voucher","atributos" => "width:50px;text-align:center;");
		$oCab[9] = array("titulo" => "Linaje","atributos" => "width:50px;text-align:center;");
		$oCab[10] = array("titulo" => "Nomina","atributos" => "width:50px;text-align:center;");
		
		return $oCab;
	}
}
?>