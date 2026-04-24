<?php

/**
 * @author Judelvis Antonio Rivas
 * @package Cooperativa.system.application.model.ubicacion
 * @version 1.0.0
 * @orm t_ubicacion
 */
class Mcargavenezuela extends Model
{


    function registrar($datos)
    {
        $res = $this->db->insert("t_archivos_venezuela", $datos);
        if (!$res) return false;
        return $this->db->insert_id();
    }

    function leerArchivo($oida, $ruta)
    {
        date_default_timezone_set('America/Caracas');
        $directorio = BASEPATH . 'repository/txt/venezuela/' . $ruta;
        //return $directorio;
        $archivo = fopen($directorio, "r");
        //return $directorio.$archivo;
        $ban = 0;
        $monto = 0;
        $consulta = 'INSERT into t_cargar_txt_venezuela(oida,cedula,fenv,fpro,monto) Values';
        while (!feof($archivo)) {
            $linea = fgets($archivo);
            //echo  $linea. "<br />";
            if (substr($linea, 0, 2) == '02') {
                $picar = explode(';', $linea);
                $ced = substr($picar[1],1,-1);
                $mtc = $picar[24];
                $monto1 = intval(substr($mtc, 0, -2));
                $decimal = substr($mtc, -2);
                $monto += floatval($monto1 . '.' . $decimal);
                $picar[17] = str_replace('/', '-', $picar[17]);
                $picar[23] = str_replace('/', '-', $picar[23]);

                if ($picar[21] == 'RG010' || $picar[21] == 'RG012') {
                    $ban++;
                    //echo 'llega';
                    if ($ban > 1) $consulta .= ',';
                    $consulta .= '(' . $oida . ',' . intval($ced) . ',"' . date("Y-m-d", strtotime($picar[17])) . '","' . date("Y-m-d", strtotime($picar[23])) . '",' . $monto1 . '.' . $decimal . ')';
                    //echo 2;
                }

            }
        }
        fclose($archivo);
        //echo $consulta;
        $resp = $this->db->query($consulta);
        if ($resp) $this->db->query('UPDATE t_archivos_venezuela set monto=' . $monto . ' where oid=' . $oida);
        return $resp;
    }

    function Listar()
    {
        $oCabezera[1] = array("titulo" => "oid", "atributos" => "width:80px", "oculto" => 1);
        $oCabezera[2] = array("titulo" => "Nombre", "atributos" => "width:150px", "buscar" => 0);
        $oCabezera[3] = array("titulo" => "Direccion", "atributos" => "width:250px", "buscar" => 0, "tipo" => "texto");
        $oCabezera[4] = array("titulo" => "Sucursal", "atributos" => "width:50px", "buscar" => 1, "tipo" => "texto");
        $oCabezera[5] = array("titulo" => "A", "tipo" => "bimagen", "funcion" => 'Modifica_Direccion', "parametro" => "1,3,4", "ruta" => __IMG__ . "botones/aceptar1.png", "atributos" => "width:10px", "mantiene" => 0);

        $query = "SELECT * FROM t_ubicacion";
        $Conexion = $this->db->query($query);
        $cant = $Conexion->num_rows();
        $filas = $Conexion->result();
        if ($cant > 0) {
            $i = 0;
            foreach ($filas as $row) {
                ++$i;
                $oFil[$i] = array("1" => $row->oid, "2" => $row->descripcion, "3" => $row->direccion, "4" => $row->sucursal, "5" => "");
            }
            $oTable = array("Cabezera" => $oCabezera, "Cuerpo" => $oFil, "Origen" => "json", "msj" => "SI");
        } else {
            $oTable = array("msj" => "NO");
        }

        return json_encode($oTable);
    }

    function crearCombo()
    {
        $con = $this->db->query('SELECT oid,archivo FROM t_archivos_venezuela WHERE estatus =0');
        $item = array();
        $rc = $con->result();
        foreach ($rc as $fila) {
            $item[$fila->oid] = $fila->archivo;
        }
        return $item;
    }

    function crearComboContrato($cedula)
    {
        $query = "SELECT contrato_id,monto_cuota,
case empresa when 0 then 'C' when 1 then 'G' end as emp,
case forma_contrato  when 0 then 'U'  when 1 then 'A'  when 2 then 'V' else 'O' end as forma
        FROM t_clientes_creditos WHERE documento_id='".$cedula."'
                and cobrado_en='VENEZUELA' order by forma,emp";
        $con = $this->db->query($query);
        $item = array();
        $rc = $con->result();
        foreach ($rc as $fila) {
            $item[$fila->contrato_id] = $fila->contrato_id.'|'.$fila->forma.'|'.$fila->emp.'|'.$fila->monto_cuota;
        }
        return json_encode($item);
    }

    function grid($oida){
        $query = 'Select a.oid as oid,archivo,cedula,contrato,a.monto as monto,a.fenv as fenv,a.frec as frec,fpro,obser,oida from t_cargar_txt_venezuela as a
join t_archivos_venezuela as b on b.oid = a.oida
where oida='.$oida.' order by cedula';
        //return $query;
        $oFila = array();

        $oCabezera[1] = array("titulo" => "oid", "oculto" => 1);
        $oCabezera[2] = array("titulo" => "Cedula", "atributos" => "width:50px", "buscar" => 1);
        $oCabezera[3] = array("titulo" => "Contrato", "atributos" => "width:80px");
        $oCabezera[4] = array("titulo" => "Monto", "atributos" => "width:80px;text-align:center", "total" => 1);
        $oCabezera[5] = array("titulo" => "F.Env", "atributos" => "width:80px");
        $oCabezera[6] = array("titulo" => "F.Pro", "atributos" => "width:80px");
        $oCabezera[7] = array("titulo" => "Mes a Cargar", "atributos" => "width:80px");
        $oCabezera[8] = array("titulo" => "Observaciones", "atributos" => "width:80px");
        $oCabezera[9] = array("titulo" => "oida", "oculto" => 1);
        $oCabezera[10] = array("titulo" => "archivo", "oculto" => 1);
        $oCabezera[11] = array("titulo" => "#", 'tipo' => 'enlace', "metodo" => 1,
            "ruta" => __IMG__ . "botones/agregar.png", "funcion" => "cargarCuota", "parametro" => "1,2","atributos" => "width:15px");

        $consulta = $this->db->query($query);
        $rs = $consulta->result();
        $i = 0;
        $suma_total = 0;
        $cuota = 0;
        $ban = 0;
        foreach ($rs as $fila) {
            $color = ''; // NEGRO
            $cerrar = '';
            $cuota = $fila->monto;
            $contrato = $fila->contrato;
            if ($contrato == '') {
                $ban ++;
                $color = '<font color=\'#1964B5\' size="2"><b>'; // AZUL
                $contrato = "Por Asignar";
                $cerrar = '</b></font>';
            }

            $suma_total += $fila->monto;
            $oFila[++$i] = array(//
                '1' => $fila->oid, //
                '2' => $fila->cedula, //
                '3' => $color . $contrato . $cerrar, //
                '4' => $fila->monto, //
                '5' => $fila->fenv, //
                '6' => $fila->fpro, //
                '7' => $fila->frec, //
                '8' => $fila->obser, //
                '9' => $fila->oida, //
                '10' => $fila->archivo, //
                '11' => 'Cargar'); //
        }
        $leyenda = '<br><center><h2> Monto Total de cuotas  ( ' . number_format($suma_total, 2, ".", ",") . ' ) <br>';

        if($ban == 0) $oTable = array("Cabezera" => $oCabezera, "Cuerpo" => $oFila, "Origen" => "json",
            'Editable' => 'Guardar_Lote_Venezuela', 'Parametros' => '2,3,10,8,7,4,6,9', 'leyenda' => $leyenda);
        else $oTable = array("Cabezera" => $oCabezera, "Cuerpo" => $oFila, "Origen" => "json", 'leyenda' => $leyenda);
        return json_encode($oTable);
    }
}

?>