<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 *
 * @author Judelvis Rivas
 * @since Version 1.0
 *
 */
class CContrato extends Model
{

    function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    function guardar($datos)
    {
        $sql= $this->db->query("select * from t_sumi where factura ='" . $datos["factura"] . "' ");
        if ($sql->num_rows() > 0) {
            return 'Factura ya se encuentra Registrada';
        }
        else{
            if($this -> db -> insert("t_sumi", $datos)) return 'Se Registro con Exito';
            else return "Error al insertar.";
        }

    }

    function factura($fact = null)
    {
        $datos = array("filas" => 0, "datos" => "No se encontraron resultados", "parametro" => $fact);
        $contrato = $this->db->query("select contrato_id, cantidad from t_clientes_creditos where numero_factura ='" . $fact . "' ");
        if ($contrato->num_rows() > 0) {
            $Mtotal = "";
            $Ccontratos = "";
            foreach ($contrato->result() as $cont) {
                $Mtotal += $cont->cantidad;
                $Ccontratos .= $cont->contrato_id . '|';
            }
            $datos = array("filas" => $contrato->num_rows(), "monto" => $Mtotal, "contratos" => $Ccontratos);
        }
        return $datos;
    }

    public function ficha($tipo){
        $est = $tipo;
        //if($tipo == 1) $est = 0;
        $query = "select * FROM t_sumi where estatus={$est} ";
        $dat = $this->db->query($query);
        $datos=array("filas"=>0,"datos"=>"No se encontraron registros");
        if($dat->num_rows()>0){
            $rs = $dat->result();
            $html="<table border='1' class='table table-bordered'><thead><tr><td>Factura</td><td>M.factura</td><td>M.Limite</td><td>M.Pagado</td><td>Estatus</td>
                <td>D.Entrega</td><td>Accion</td>
                </tr></thead><tbody>";
            foreach($rs as $fact){

                $cont = explode('|',$fact->contratos);
                $mntPag = 0;
                foreach ($cont as $con){
                    $q2 = $this->db->query("SELECT * FROM t_lista_cobros_u where contrato_id='{$con}'");
                    foreach ($q2->result() as $mmt){
                        $mntPag += $mmt->monto;
                    }
                }

                $estatus = $est;
                $boton = '';
                $clase = '';
                switch ($est){
                    case 0:
                        $estatus = "Pendiente de pagos";
                        if($fact->monto_limite <= $mntPag) {
                            $boton ="<button onclick='entregar(\"".$fact->factura."\")' class='btn btn-info' >Entregar</button>";
                            $estatus = "Pendiente de entrega";
                            $clase = "class='bg-warning'";
                        }
                        else $boton = "Pagos pendientes";
                        break;
                    case 1:
                        $estatus = "Entregado";
                        $boton ="<button onclick='detalle(\"".$fact->factura."\")' class='btn btn-info fa fa-print' >Entregado</button>";
                        break;
                    default : break;
                }
                $porc = ($mntPag*100)/$fact->monto_factura;
                $html.="
                <tr ".$clase.">
                <td>".$fact->factura."</td><td>".$fact->monto_factura."</td>
                <td>".$fact->monto_limite."</td><td>".$mntPag."</td><td>".$estatus."|".number_format($porc,2)."%</td>
                <td>".$fact->observacion."</td>
                <td>".$boton."</td>
                </tr>
                ";
            }
            $html .= "</tbody></table>";
            $datos=array("filas"=>$dat->num_rows(),"datos"=>$html);
        }
        return $datos;
    }

    function guardarEntrega($dat){
        if($this -> db -> query("UPDATE t_sumi set observacion='".$dat['obs']."',estatus=1 WHERE factura='".$dat['factura']."'")) return 'Se Registro Entrega con Exito';
        else return "Error al insertar.";
    }


    function reporte($tipo){
        $query="SELECT *,monto_limite as monto_pagado FROM t_sumi WHERE estatus=".$tipo;
        $dat = $this->db->query($query);
        $datos=array("filas"=>0,"datos"=>"No se encontraron datos para el dia seleccionado");
        if($dat->num_rows()>0){
            $datos=array("filas"=>$dat->num_rows(),"datos"=>$dat->result());
        }
        return $datos;

    }
	
	function guardarBien($datos)
    {
       
        if($this -> db -> insert("t_sumi_bien", $datos)) return 'Se Registro con Exito';
        else return "Error al insertar.";
        

    }
	
	function cmbBien(){
		$datos = $this -> db ->query("SELECT * FROM t_sumi_bien");
		$rs = $datos->result();
		$cmb='';
		foreach ($rs as $row) {
			$cmb .= '<option value="'.$row->nombre.'">'.$row->nombre.'</option>';
		}
		return $cmb;
	}


}
