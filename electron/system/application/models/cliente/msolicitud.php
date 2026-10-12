<?php
class MSolicitud extends Model {


	public function __construct() {
		parent::__construct();
	}
	
	function _setCodigoSRand($sCod) {
		$sConsulta = "SELECT cod FROM t_solisrand WHERE cod='" . $sCod . "' LIMIT 1";
		$rsC = $this -> db -> query($sConsulta);
		if ($rsC -> num_rows() != 0) {
			$aux = rand(1, 99999);
			$C = $this -> _setCodigoSRand($aux);
			return $C;
		} else {
			$this -> db -> query('INSERT INTO t_solisrand (oid, cod) VALUES (NULL, \'' . $sCod . '\');');
			return $sCod;
		}
	}
	
	function Registrar($dt){
		$ubi = $this -> session -> userdata('ubicacion');
		$insertar = array("cedula"=>$dt['cedula'],"codigo"=>$dt['codigo'],'nombre'=>$dt['nombre'],'nomina'=>$dt['nomina'],'banco'=>$dt['banco'],'solicitud'=>$dt['solicitud'],'estatus'=>0,'ubicacion'=>$ubi);
		//print_R($insertar);
		$this -> db -> insert('t_solicitud',$insertar);
	}
	
	function Grid_Ubicacion($arr){
		$banco = '';$nomi = '';$est = '';
		$ubi = ' ubicacion="'.$this -> session -> userdata('ubicacion').'" AND ';
		$usu = $this -> session -> userdata('usuario');
		
		if(isset($arr['cubica'])) {
			if($arr['cubica'] == 'Todos') $ubi ='';
			else $ubi = ' ubicacion="'.$arr['cubica'].'" AND ';
			
		}
		if(isset($arr['estatus'])) $est = ' AND estatus='.$arr['estatus'];
		$grid = array();
		if($arr['banco'] != '0') $banco = ' AND banco="'.$arr['banco'].'" ';
		if($arr['nomina']!= '0') $nomi = ' AND nomina="'.$arr['nomina'].'" ';
		$query = "SELECT *, CASE estatus WHEN 0 then 'Carga Inicial' WHEN 1 then 'Revisando' WHEN 2 then 'Aceptado' WHEN 3 then 'Rechazado'	END AS est " .
				 "FROM t_solicitud WHERE ".$ubi."  creado BETWEEN '".$arr['desde']."' AND '".$arr['hasta']."' ".$banco.$nomi.$est;
		$lista = $this -> db -> query($query);
		$cant = $lista -> num_rows();
		if($cant > 0){
			$rsLista = $lista->result();
			$cab[1]=array("titulo"=>"Codigo","atributos"=>"width:80px;","buscar"=>1);
			$cab[2]=array("titulo"=>"Cedula","atributos"=>"width:80px;","buscar"=>1);
			$cab[3]=array("titulo"=>"Nombre","atributos"=>"width:280px;","buscar"=>1);
			$cab[4]=array("titulo"=>"Banco","atributos"=>"width:80px;","buscar"=>1);
			$cab[5]=array("titulo"=>"Nomina","atributos"=>"width:80px;","buscar"=>1);
			$cab[6]=array("titulo"=>"Solicitud","atributos"=>"width:280px;","buscar"=>1);
			$cab[7]=array("titulo"=>"Ubicacion","atributos"=>"width:80px;","buscar"=>1);
			$cab[8]=array("titulo"=>"Fecha","atributos"=>"width:80px;","buscar"=>1);
			if($arr['estatus'] < 2  && isset($arr['cubica'])){
				$cab[9] = array("titulo" => "AC", "tipo" => "bimagen", "funcion" => 'Aceptar_Soli', "parametro" => "11,12", "ruta" => __IMG__ . "botones/aceptar1.png", "atributos" => "width:10px");
				$cab[10] = array("titulo" => "AN", "tipo" => "bimagen", "funcion" => 'Anular_Soli', "parametro" => "11", "ruta" => __IMG__ . "botones/quitar.png", "atributos" => "width:10px");
				//$cab[10] = array("titulo" => "R", "tipo" => "bimagen", "funcion" => 'Rechazar_Soli', "parametro" => "11", "ruta" => __IMG__ . "botones/quitar.png", "atributos" => "width:10px");
				$cab[11]=array("titulo"=>"oid","atributos"=>"width:80px;","oculto"=>1);
				$cab[12]=array("titulo"=>"est","atributos"=>"width:80px;","oculto"=>1);
			}
			$i=0;
			$cuerpo = array();
			foreach($rsLista as $row){
				$i++;
				
				if($arr['estatus'] <2 && isset($arr['cubica'])){
					$cuerpo[$i] = array("1"=>$row->codigo,"2"=>$row -> cedula,"3"=>$row->nombre,"4"=>$row->banco,"5"=>$row->nomina,
										"6"=>$row->solicitud,"7"=>$row->ubicacion,"8"=>$row->creado,"9"=>"","10"=>"","11"=>$row->oid,"12"=>$row->estatus);
				}else{
					$cuerpo[$i] = array("1"=>$row->codigo,"2"=>$row -> cedula,"3"=>$row->nombre,"4"=>$row->banco,"5"=>$row->nomina,"6"=>$row->solicitud,"7"=>$row->est,"8"=>$row->creado);
				}
			}
			$grid=array("resp"=>1,"Cabezera"=>$cab,"Cuerpo"=>$cuerpo,"Paginador"=>10,"Origen"=>"json","sql"=>$query);
			$grid['sql'] = $query;
		}else{
			$grid['resp']=0;
			$grid['sql'] = $query;
		}
		return json_encode($grid);
	}
	
	function Modifica_Estatus($oid,$est){
		$this -> db -> query("UPDATE t_solicitud SET estatus=".$est." WHERE oid=".$oid);
		return "Se actualizo Con Exito";
	}
}
?>
