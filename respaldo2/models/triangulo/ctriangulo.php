<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 *
 * @author Judelvis Rivas
 * @since Version 1.0
 *
 */
class CTriangulo extends Model
{

    function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    function guardar($datos)
    {
        $sql= $this->db->query("insert into t_triangulo (estatus) values(0)");
        if ($sql) {
            $id = $this->db->insert_id();
            $sql2= $this->db->query("insert into t_triangulo_a (id_g,cedula,factura) values({$id},'{$datos['ced1']}','{$datos['fact1']}')");
            $sql3= $this->db->query("insert into t_triangulo_a (id_g,cedula,factura) values({$id},'{$datos['ced2']}','{$datos['fact2']}')");
            $sql4= $this->db->query("insert into t_triangulo_a (id_g,cedula,factura) values({$id},'{$datos['ced3']}','{$datos['fact3']}')");
            return 'Se registro con exito';
        }
    }

    function factura($fact = null)
    {
        $datos = array("filas" => 0, "datos" => "No se encontro factura", "parametro" => $fact);
        $contrato = $this->db->query("select contrato_id, cantidad,nomina_procedencia,t_personas.documento_id as ced,
                      primer_nombre,primer_apellido,banco_1,cuenta_1,banco_2,cuenta_2
                      from t_clientes_creditos 
                      join t_personas on t_personas.documento_id = t_clientes_creditos.documento_id
                      where numero_factura ='" . $fact . "' ");


        if ($contrato->num_rows() > 0) {

            foreach ($contrato->result() as $con){
                $nomi = $con->nomina_procedencia;
                $qnomi = "select * FROM t_nominas WHERE nombre='{$nomi}' and triangulo=1";
                $rsnomi = $this->db->query($qnomi);
                if($rsnomi->num_rows() == 0) return array("filas" => 0, "datos" => "La nomina asociada a este cliente no se encuentra activa para la modalidad triangulo", "parametro" => $fact);
            }

            $ver1 = "select * from t_triangulo_a 
                          join t_triangulo on t_triangulo.id = t_triangulo_a.id_g
                          where factura='{$fact}' and estatus = 0";
            $rs1 = $this->db->query($ver1);

            if($rs1->num_rows() > 0) return array("filas" => 0, "datos" => "La factura se encuentra asociada a otro grupo de pago", "parametro" => $fact);
            else{
                $datos = array("filas" => $contrato->num_rows(), "datos" => $contrato->result());
            }

        }
        return $datos;
    }

    public function lista_triangulo($tipo){
        $est = $tipo;
        //if($tipo == 1) $est = 0;
        $query = "select * FROM t_triangulo where estatus={$est} ";
        $dat = $this->db->query($query);
        $datos=array("filas"=>0,"datos"=>"No se encontraron registros");
        if($dat->num_rows()>0){
            $rs = $dat->result();
            $html="<table border='1' class='table table-bordered'><thead><tr><td>Grupo</td><td>Creado</td><td>Estatus</td>
                <td>Accion</td>
                </tr></thead><tbody>";
            foreach($rs as $fact){
                
                $estatus = $est;
                $boton = '';
                $clase = '';
                switch ($est){
                    case 0:
                        $estatus = "Triangulo Activo";
                        break;
                    case 1:
                        $estatus = "Triangulo Finalizado";
                        break;
                    default : break;
                }
                $boton ="<a href='detalle/".$fact->id."' class='btn btn-info fa fa-print' >Ver</button>";
                
                $html.="
                <tr ".$clase.">
                <td>".$fact->id."</td><td>".$fact->creado."</td>
                <td>".$estatus."</td>
                <td>".$boton."</td>
                </tr>
                ";
            }
            $html .= "</tbody></table>";
            $datos=array("filas"=>$dat->num_rows(),"datos"=>$html);
        }
        return $datos;
    }

   

    function detalle($id){
        $query="SELECT * FROM t_triangulo_a 
        join t_personas on t_personas.documento_id = t_triangulo_a.cedula
        join t_clientes_creditos on t_clientes_creditos.numero_factura = t_triangulo_a.factura
        WHERE id_g=".$id;
        $dat = $this->db->query($query);
        $per = array();
		$rsQ = $dat->result();
		foreach ($rsQ as $pers) {
			$html = "
			<b>Cedula:</b>".$pers->cedula."<br>
			<b>Nombre:</b>".$pers->primer_nombre." ".$pers->primer_apellido."<br>
			<b>Nomina:</b>".$pers->nomina_procedencia."<br>
			<b>Banco 1:</b>".$pers->banco_1."<br>
			<b>Cuenta1:</b>".$pers->cuenta_1."<br>
			<b>Banco 2:</b>".$pers->banco_2."<br>
			<b>Cuenta2:</b>".$pers->cuenta_2."<br>
			<b>Factura:</b>".$pers->factura."<br>
			
			
			
			";
			$per[] = $html;
		}
		return $per;
    }
	
	


}
