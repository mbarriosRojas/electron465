<?php
/**
 *  @author Judelvis Rivas
 *  @package cooperativa.system.application.model.cliente
 *  @version 1.0.0
 */

class MRecepcion extends Model {

    public function __construct() {
        parent::__construct();
    }

    public function __destruct() {

    }

    public function listar($arr){
        $usu = $this->session->userdata ( 'oidu' );
        $q = $this->db->query("SELECT t_usuario.oid as uoid,descripcion from t_usuario join _tr_usuarioubicacion on t_usuario.oid =_tr_usuarioubicacion.oidu where oidb=13");
        $con = $q->result();
        $arrUb = array();
        foreach($con as $ub){
            $arrUb[$ub->uoid] = $ub->descripcion;
        }
        $obj[12] = $arrUb;
        $query = 'SELECT t_recepcion_documento.oid as oidrec,envia,recibe,t_recepcion_documento.fecha as fec,hora,cedula,nombre,observacion,descripcion,seudonimo,
        case t_recepcion_documento.tipo when 1 then "LIQUIDADOS" when 2 then  "SOLICITUD" end as tip,
        case t_recepcion_documento.estatus when 0 then "RECIBIDO" when 1 then  "ENT. ANALISTA" when 2 then "PROCESADO/ENTREGADO" when 3 then "RECHAZADO" when 4 then "ANULADO" when 5 then "PUBLICIDAD" end as est
        FROM t_recepcion_documento
                  left join t_usuario on t_usuario.oid = t_recepcion_documento.asignado where cedula!=""';
        if($arr['estatus'] != 9) $query .= ' and t_recepcion_documento.estatus='.$arr['estatus'];
        if($arr['desde'] != '' &&  $arr['hasta'] != '') $query .= ' and t_recepcion_documento.fecha Between "'.$arr['desde'].'" and "'.$arr['hasta'].'"';
        if($arr['desde'] != '' &&  $arr['hasta'] == '') $query .= ' and t_recepcion_documento.fecha >= "'.$arr['desde'].'" ';
        if($arr['desde'] == '' &&  $arr['hasta'] != '') $query .= ' and t_recepcion_documento.fecha <= "'.$arr['hasta'].'" ';
        if($arr['tipo'] != 9 ) $query .= ' and t_recepcion_documento.tipo = '.$arr['tipo'];
        if($usu != 0 && $usu !=27 && $usu != 28 && $arr['estatus'] != 9) $query .= ' and asignado='.$usu;
        //return $query;
        $consulta = $this -> db ->  query ($query);
        $iCantidad = $consulta -> num_rows();
        $Conexion = $consulta -> result();

        $oCabezera[1] = array("titulo" => "oid", "oculto"=>TRUE);
        $oCabezera[2] = array ("titulo" => " ","tipo" => "detallePost","atributos" => "width:40px","funcion" => "detalleRecepDocu","parametro" => "1", "atributos" => "width:12px");
        $oCabezera[3] = array("titulo" => "nivel", "oculto"=>TRUE);
        $oCabezera[4] = array("titulo" => "Enviado", "atributos" => "width:50px", "buscar" => 0);
        $oCabezera[5] = array("titulo" => "Recibido", "atributos" => "width:80px", "buscar" => 0);
        $oCabezera[6] = array("titulo" => "Tipo", "atributos" => "width:80px", "buscar" => 0);
        $oCabezera[7] = array("titulo" => "Cedula", "atributos" => "width:100px",'buscar'=>0);
        $oCabezera[8] = array("titulo" => "Cliente", "atributos" => "width:100px", "buscar" => 0);
        $oCabezera[9] = array("titulo" => "Fecha Rec.");
        $oCabezera[10] = array("titulo" => "Hora", "atributos" => "width:100px", "buscar" => 0);
        $oCabezera[11] = array("titulo" => "Observacion", "atributos" => "width:100px");
        $oCabezera[12] = array("titulo" => "Posesion", "atributos" => "width:100px");
        $oCabezera[13] = array("titulo" => "Estatus", "atributos" => "width:100px");
        if($arr['estatus'] == 0 && ($usu == 0 || $usu == 27 || $usu == 28)){
            $oCabezera[12]['tipo'] = "combo";
            $oCabezera[14] = array("titulo" => "AC", "tipo" => "bimagen", "funcion" => 'aceptarEstatusDocu', "parametro" => "1,3,12", "ruta" => __IMG__ . "botones/aceptar1.png", "atributos" => "width:10px");
            $oCabezera[15] = array("titulo" => "AN", "tipo" => "bimagen", "funcion" => 'anularEstatusDocu', "parametro" => "1", "ruta" => __IMG__ . "botones/quitar.png", "atributos" => "width:10px");
        }
        if($arr['estatus'] == 1){
            $oCabezera[11]['tipo'] = "textArea";
            $oCabezera[14] = array("titulo" => "AC", "tipo" => "bimagen", "funcion" => 'aceptarEstatusDocu', "parametro" => "1,3", "ruta" => __IMG__ . "botones/aceptar1.png", "atributos" => "width:10px");
            $oCabezera[15] = array("titulo" => "RC", "tipo" => "bimagen", "funcion" => 'rechazarEstatusDocu', "parametro" => "1", "ruta" => __IMG__ . "botones/quitar.png", "atributos" => "width:10px");
            $oCabezera[16] = array("titulo" => "MD", "tipo" => "bimagen", "funcion" => 'observaEstatusDocu', "parametro" => "1,11", "ruta" => __IMG__ . "botones/add.png", "atributos" => "width:10px");
            $oCabezera[17] = array("titulo" => "PB", "tipo" => "bimagen", "funcion" => 'publiEstatusDocu', "parametro" => "1", "ruta" => __IMG__ . "botones/sobre.png", "atributos" => "width:10px");
        }

        if ($iCantidad > 0) {
            $i = 0;
            foreach ($Conexion as $row) {
                ++$i;
                $oFil[$i] = array("1" => $row -> oidrec,"2"=>"","3"=>$arr['estatus'] ,"4" => $row -> envia, "5" => $row -> recibe, "6"=>$row->tip,"7" => $row -> cedula, "8" => $row -> nombre,
                    "9" => $row -> fec, "10" => $row -> hora, "11" => $row -> observacion , "12" => $row -> descripcion,"13"=>$row -> est);
                if($arr['estatus'] == 0 && ($usu == 0 || $usu == 27 || $usu == 28)){
                    $oFil[$i]["14"] = "";
                    $oFil[$i]["15"] = "";
                }
                if($arr['estatus'] == 1){
                    $oFil[$i]["14"] = "";
                    $oFil[$i]["15"] = "";
                    $oFil[$i]["16"] = "";
                    $oFil[$i]["17"] = "";
                }
            }

            $oTable = array("Cabezera" => $oCabezera, "Cuerpo" => $oFil, "Paginador" => 50, "Origen" => "json","msj" => TRUE,"Objetos"=>$obj);

        } else {
            $oTable = array("msj" => FALSE);
        }
        return json_encode($oTable);
    }

    function listarDetalle($oid){
        $query = $this -> db ->  query("SELECT * FROM t_it_recepcion_documentos where oidRec=".$oid);
        $res = $query->result();
        $cab[1] = array("titulo"=>"Documento");
        $cuerpo = array();
        $i=0;
        foreach($res as $row){
            $i++;
            $cuerpo[$i]=array("1"=>$row -> docu);
        }

        $query2 = $this -> db ->  query("SELECT * FROM t_recepcion_historial where oidRec=".$oid);
        $res2 = $query2->result();
        $cab2[1] = array("titulo"=>"OBSERVACION");
        $cab2[2] = array("titulo"=>"Creado");
        $cuerpo2 = array();
        $i=0;
        foreach($res2 as $row2){
            $i++;
            $cuerpo2[$i]=array("1"=>$row2 -> observacion,"2"=>$row2->modificado);
        }
        $oTable = array("Cabezera" => $cab, "Cuerpo" => $cuerpo, "Origen" => "json","msj" => TRUE,"titulo"=>"DOCUMENTOS RECIBIDOS");
        $oTable2 = array("Cabezera" => $cab2, "Cuerpo" => $cuerpo2, "Origen" => "json","msj" => TRUE,"titulo"=> "<br>HISTORIAL DE OBSERVACIONES");
        $elementos = array("0"=>$oTable,"1"=>$oTable2);
        $final = array (
            "compuesto" => 2,
            "objetos" => $elementos
        );
        return json_encode($final);

    }

    function aceptarEstatusDocu($arr){
        $esta = $arr[1] + 1;
        $query = "update t_recepcion_documento set estatus=".$esta;
        if(isset($arr[2])) $query .= " , asignado=".$arr[2];
        $query .= " WHERE oid=".$arr[0];
        //return $query;
        $this -> db -> query($query);
        return "Se proceso con Exito";
    }

    function anularEstatusDocu($arr){
        $query = "update t_recepcion_documento set estatus=4 where oid=".$arr[0];
        //return $query;
        $this -> db -> query($query);
        return "Se proceso con Exito";
    }

    function rechazarEstatusDocu($arr){
        $query = "update t_recepcion_documento set estatus=3 where oid=".$arr[0];
        //return $query;
        $this -> db -> query($query);
        return "Se proceso con Exito";
    }

    function observaEstatusDocu($arr){
        $datos = array("oidRec"=>$arr[0],"observacion"=>$arr[1]);
        $this -> db -> insert("t_recepcion_historial",$datos);
        return "Se proceso con Exito";
    }

    function publiEstatusDocu($arr){
        $query = "update t_recepcion_documento set estatus=5 where oid=".$arr[0];
        //return $query;
        $this -> db -> query($query);
        return "Se proceso con Exito";
    }

    function guardar($datos){
        $documentos = $datos['docu2'];
        unset($datos['docu2']);
        unset($datos['docu']);
        $datos['usua'] = $this->session->userdata ( 'oidu' );
        $this -> db -> insert("t_recepcion_documento",$datos);
        $id = $this -> db -> insert_id();
        foreach($documentos as $doc){
            $query ="insert into t_it_recepcion_documentos(oidRec,docu,usua) values(".$id.",'".$doc."',".$datos['usua'].")";
            $this -> db -> query($query);
        }
        return "Se registro Con Exito";
    }

}