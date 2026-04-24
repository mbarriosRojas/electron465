<?php
/*
 *  @author Carlos Enrique Peña Albarrán
 *  @package SaGem.system.application
 *  @version 1.0.0
 */
class MPersona extends Model {
    public function __construct() {
        parent::__construct();
    }
    public function jsPersona($id = null) {
        $j = 0;
        $jsP = array('documento_id'=>0);    //Una Persona Json
        $jsC = array(); //Lista de Contatocs
        $jsS = array(); //Lista de suspencion
        $Consulta = $this -> db -> query('SELECT * FROM t_personas
         LEFT JOIN t_clientes_domiciliacion ON t_personas.documento_id = t_clientes_domiciliacion.cedula  
		 WHERE documento_id=' . $id . ' LIMIT 1');
        foreach ($Consulta->result() as $row) {
            foreach ($row as $NombreCampo => $ValorCampo) {
                $jsP[$NombreCampo] = $ValorCampo;
            }
        }
        $Consulta = $this -> db -> query('SELECT * FROM t_contactos WHERE cedula=' . $id);       
        foreach ($Consulta->result() as $row) {
            $j++;
            $jsC[$j] = array('nombre' => $row -> nombre, 'telefono' => $row -> telefono, 'descripcion' => $row -> descripcion, 'estatus' => $row -> estatus , 'nomina' => $row -> nomina , 'direccion' => $row -> direccion);
        }
        $jsP['contactos'] = $jsC;
		$Consulta = $this -> db -> query('SELECT * FROM _th_sistema WHERE referencia="'. $id .'" AND tipo=7');
		$j = 0;
		foreach ($Consulta->result() as $row) {
            $j++;
            $jsS[$j] = array('usuario' => $row -> usuario, 'fecha' => $row -> fecha, 'motivo' => $row -> motivo, 'peticion' => $row -> peticion);
        }
		if(count($jsS) > 0){
			$jsP['suspendido'] = $jsS;	
		}else{
			$jsP['suspendido'] = 'null';
		}
		$ruta = BASEPATH.'repository/expedientes/fotos/'.$id.'.jpg';
		if(file_exists($ruta)){
			$jsP['fotoc'] = 1;
		}else{
			$jsP['fotoc'] = $ruta;
		} 
        return json_encode($jsP);
    }

	public function jsPersonaCC($id = null) {
        $j = 0;
        $jsP = array();    //Una Persona Json
        
        $Consulta = $this -> db -> query('SELECT * FROM t_personas_tem WHERE documento_id=' . $id . ' LIMIT 1');
        foreach ($Consulta->result() as $row) {
            foreach ($row as $NombreCampo => $ValorCampo) {
                $jsP[$NombreCampo] = $ValorCampo;
            }
        }	
        return json_encode($jsP);
    }
	
	public function jsDeuda($id=null){
		$msj = '';
		$deuda = '';
		$sCon = 'SELECT * FROM t_aceptado WHERE documento_id = \'' . $id . '\'';
		$rs = $this -> db -> query($sCon);
		$act = "NO";
		
		$restav = 0;			
		$voucher = 'select sum(resta)as resta,ifnull(max(evento),"SIN CARGA")as evento from(select numero_factura,sum(cantidad)-ifnull(monto,0)as resta,evento
from t_clientes_creditos
left join (
	select sum(monto)as monto,cid,max(evento)as evento 
	from t_lista_voucher 
	join (select documento_id,numero_factura from t_clientes_creditos group by numero_factura)as tc on tc.numero_factura=t_lista_voucher.cid
	where (t_lista_voucher.estatus=1 or t_lista_voucher.estatus = 3) and tc.documento_id="'.$id.'" group by cid) as a on a.cid=t_clientes_creditos.numero_factura
where documento_id="'.$id.'" and marca_consulta=6
group by numero_factura) as a';
		$rsV = $this -> db -> query($voucher);
		$rsCV = $rsV->result();
		$can_vou = $rsV -> num_rows();
		if($can_vou > 0){
			$restav = $rsCV[0]->resta;
		}
		$montod = 0;
		$montopd = 0;
		$pago = 'SELECT  ifnull(SUM(cantidad),0)as cantidad,(select ifnull(sum(monto),0) from t_lista_cobros where documento_id="'.$id.'")as pg_d,
		(select ifnull(max(modificado),"SIN CARGA") from t_lista_cobros where documento_id="'.$id.'")as ultima 
		from t_clientes_creditos
		where documento_id="'.$id.'" and marca_consulta != 6';
		$rsP = $this -> db -> query($pago);
		$rsCP = $rsP->result();
		$can_dom = $rsP -> num_rows();
		if($can_dom > 0){
			$montod = $rsCP[0]->cantidad;
			$montopd = $rsCP[0] -> pg_d;	
		}
		$montot = $montod + $restav;
		if(($montod != $montopd || $restav != 0) and $montot > 0){
			$msj .= '<h2>Deudas Generales:</h2>
			<br><font color="#1c94c4" size=4>POR DOMICILIACION: </font><font color="red" size=3>' . number_format($montod-$montopd,2) .'  BS</font>
			<br><font color="#1c94c4" size=4>ULTIMA ACTUALIZACION: </font><font color="red" size=3>' . $rsCP[0] -> ultima .'</font>
			<br><br><font color="#1c94c4" size=4>POR VOUCHER,TRANSFERENCIA O COTERO: <font color="red" size=3>'.number_format($restav,2).'  BS</font>
			<br><font color="#1c94c4" size=4>ULTIMA ACTUALIZACION: </font><font color="red" size=3>' . $rsCV[0] -> evento .'</font>';
			$act = "NO";
			if($rs->num_rows() > 0){
				$msj .= '<br>El cliente fue autorizado por liberado de deuda, para crear nuevo contrato ...<br>';
				$act = "SI";
			}
		}else{
			$msj .= '<h2>El cliente actualmente no posee deuda con la empresa...<br>
			<br><font color="#1c94c4" size=4>ULTIMA ACTUALIZACION DOMICILIACION: </font><font color="red" size=3>' . $rsCP[0] -> ultima .'</font>
			<br><font color="#1c94c4" size=4>ULTIMA ACTUALIZACION VOUCHER: </font><font color="red" size=3>' . $rsCV[0] -> evento .'</font>
			</h2>';
			$act = "SI";
		}
		
		
		$arr['resultado'] = $msj;
		$arr['activo'] = $act;
		return json_encode($arr);	
	}

	public function Autorizar_Por_Deuda($id = null, $strPeticion, $strMotivo){
		$arr['documento_id'] = $id;
		$this->db->insert('t_aceptado',$arr);
		
		$data = array(
	   			'referencia' => $id,
	   			'tipo' => 15,
	   			'usuario' => $_SESSION['usuario'],
	   			'motivo' => $strMotivo,
	   			'peticion' => $strPeticion 
			);
		$this -> db -> insert('_th_sistema',$data);	
	
	}

}
?>
