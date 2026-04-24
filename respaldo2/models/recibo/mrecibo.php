<?php

/*
 *  @author Carlos Enrique Peña Albarrán
 *  @package cooperativa.system.application.model.cliente
 *  @version 1.0.0
 */

class MRecibo extends Model
{

    public $oid = null;

    var $Void = "";

    var $SP = " ";

    var $Dot = ".";

    var $Zero = "0";

    var $Neg = "Menos";

    public function __construct()
    {
        parent::__construct();
    }

    public function listaVoucher($factura)
    {
        $consulta = $this->db->query("SELECT * from t_lista_voucher where cid='" . $factura . "' and estatus=0 order by fecha");
        $filas = $consulta->result();
        $arr = array();
        $res = array();
        if ($consulta->num_rows() > 0) {
            foreach ($filas as $fila) {
                $arr[] = $fila->ndep . '|' . $fila->fecha;;
            }
            $res['msj'] = "si";
        } else {
            $res['msj'] = "no";
        }
        $res["filas"] = $arr;
        return json_encode($res);
    }

    public function jsReciboI($id = null)
    {
        $j = 0;
        $jsP = array();    //Una Persona Json
        $jsC = array(); //Lista de Contatocs
        $cmbCont = array();
        $cmbFact = array();
        $cmbFact2 = array();
        $Consulta = $this->db->query('SELECT * FROM t_personas WHERE documento_id=' . $id . ' LIMIT 1');
        foreach ($Consulta->result() as $row) {
            foreach ($row as $NombreCampo => $ValorCampo) {
                $jsP[$NombreCampo] = $ValorCampo;
            }
        }
        $sqlDetalle = "SELECT contrato_id, documento_id,fecha_solicitud, codigo_n_a, monto_total, monto_cuota, numero_factura  FROM t_clientes_creditos WHERE documento_id = '" . $id . "' order by numero_factura";
        $Consulta = $this->db->query($sqlDetalle);


        $i = 0;
        $Conexion = $Consulta->result();
        foreach ($Conexion as $row) {
            ++$i;
            $cmbFact[$i] = $row->numero_factura . ' | ' . $row->contrato_id;
        }

        $sqlDetalle = "SELECT numero_factura  FROM t_clientes_creditos WHERE documento_id = '" . $id . "' group by numero_factura";
        $Consulta = $this->db->query($sqlDetalle);


        $i = 0;
        $Conexion = $Consulta->result();
        foreach ($Conexion as $row) {
            ++$i;
            $cmbFact2[$i] = $row->numero_factura;
        }

        $sqlRecibos = "select * from t_recibo_ingreso where documento_id = '" . $id . "'";
        $Consulta2 = $this->db->query($sqlRecibos);
		$usu = strtolower($this -> session -> userdata('usuario'));
        if($usu == "alvaro" || $usu == "carlos" || $usu == "judelvis" || $usu == "alvaroz"){
        	$oCabezeraR[1] = array("titulo" => " ", "tipo" => "bimagen", "funcion" => "Eliminar_Recibo", 
        	"parametro" => "2", "ruta" => __IMG__ . "botones/cancelar1.png");	
        }else{
        	$oCabezeraR[1] = array("titulo" => " ", "tipo" => "bimagen", "funcion" => "Eliminar_Recibo", 
        	"parametro" => "2", "ruta" => __IMG__ . "botones/cancelar1.png", "oculto" => 1);
        }
        
        
        $oCabezeraR[2] = array("titulo" => "#Recibo");
        $oCabezeraR[3] = array("titulo" => "#Recibo-Pre");
        $oCabezeraR[4] = array("titulo" => "Fecha");
        $oCabezeraR[5] = array("titulo" => "Concepto");
        $oCabezeraR[6] = array("titulo" => "Monto");
        $oCabezeraR[7] = array("titulo" => "TP");
        $oCabezeraR[8] = array("titulo" => "Recibo Pre.");
        $oCabezeraR[9] = array("titulo" => "#", "tipo" => "enlace", "metodo" => 2, "funcion" => "Imprime_Recibo_Ingreso",
            "parametro" => "2", "ruta" => __IMG__ . "botones/print.png",
            "atributos" => "width:12px");
        $oCabezeraR[10] = array("titulo" => "#Pre", "tipo" => "enlace", "metodo" => 2, "funcion" => "Imprime_Recibo_Ingreso_Preimpreso",
            "parametro" => "2", "ruta" => __IMG__ . "botones/print.png",
            "atributos" => "width:12px");

        $i = 0;
        $Conexion2 = $Consulta2->result();
        if ($Consulta2->num_rows() > 0) {
            foreach ($Conexion2 as $row2) {
                ++$i;
                $oFil2[$i] = array(
                    "1" => '', //
                    "2" => $row2->numero_recibo, //
                    "3" => $row2->numero_recibo, //
                    "4" => $row2->fecha, //
                    "5" => $row2->concepto, //
                    "6" => $row2->monto, //
                    "7" => $row2->tipo_pago, //
                    "8" => $row2->nrecibo_pre,
                    "9" => '',//
                    "10" => ''
                );
            }
            $Object2 = array("Cabezera" => $oCabezeraR, "Cuerpo" => $oFil2, "Origen" => "json");
        } else {
            $Object2 = NULL;
        }
        $jsP['facturas'] = $cmbFact;
        $jsP['facturas2'] = $cmbFact2;
        $jsP['recibos'] = $Object2;

        return json_encode($jsP);
    }

    function Guardar_Recibo($arr)
    {
        $datos = array(
            'documento_id' => $arr['cedula'],
            'numero_recibo' => $arr['nrecibo'],
            'recibido' => $arr['recibido'],
            'fecha' => $arr['fecha'],
            'monto' => $arr['monto'],
            'concepto' => $arr['concepto'],
            'banco' => $arr['banco'],
            'tipo_pago' => $arr['tipo'],
            'numero_cheque' => $arr['cheque'],
            'usuario' => $arr['usuario'],
            'empresa' => $arr['empresa'], 'estatus' => 1,
            'nrecibo_pre' => $arr['reciboPre']);
        $msj = '';
        $query = $this->db->query('SELECT * FROM t_recibo_ingreso WHERE numero_recibo="' . $arr['nrecibo'] . '"');
        $cant = $query->num_rows();

        $rsPersona = $this->db->query("SELECT correo, documento_id,	CONCAT(primer_apellido,' ',LEFT(segundo_apellido,1),' ',primer_nombre,' ', LEFT(segundo_nombre,1),' ') AS Nombre FROM t_personas WHERE documento_id='" . $arr['cedula'] . "'");
        $correo = $rsPersona->row();
        $fecha_actual = explode("-", date("d-m-Y"));
        $mesAL = $this->mes_letras($fecha_actual[1]);

        if ($cant > 0) {
            $msj = 'El nuemro de recibo ya exite..';
        } else {
            $this->db->insert('t_recibo_ingreso', $datos);
            /*$cabeceras  = 'MIME-Version: 1.0' . "\r\n";
            $cabeceras .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
            $cabeceras .= 'FROM:ELECTRON465.com'."\r\n";
            $cuerpo = "<pre>
                            Electron 465

                            De la mano con el Ejecutivo Nacional

                            Buenas.
                            Sr.(a). ". $correo -> Nombre ."

                            Hoy ". $fecha_actual[0] ." de " . $mesAL ." del " . $fecha_actual[2] .", Electrón 465 promueve y automatiza el sistema de información en sus estados de pago.";*/
            /*$cuerpo = '
                    <table style="font-family: Trebuchet MS; font-size: 13px;" width="0">
                            <tr>
                                <td rowspan="2" width=180><img src="'. __IMG__ .'logoN.jpg" width=200></td>
                            </tr>

                            <tr>
                                <td colspan="3">Apreciado/a:  <br>'. $correo -> Nombre .'.<br>'.$arr['cedula'].'</td>
                            </tr>
                    ';*/
            if ($arr['cargar'] == 1) {
                $creditos = explode(',', $arr['creditos']);
                foreach ($creditos as $cadena) {
                    $itemCreditos = explode('|', $cadena);
                    $montoL = strtoupper($this->ValorEnLetras($itemCreditos[2], 'BS'));
                    $datosRecibo = array('id_recibo' => $arr['nrecibo'], 'factura' => trim($itemCreditos[0]), 'contrato' => trim($itemCreditos[1]), 'monto' => trim($itemCreditos[2]));
                    $this->db->insert('t_lista_recibo', $datosRecibo);
                    $fechap = explode('-', $itemCreditos[3]);
                    $usua = $this->session->userdata('oidu');
                    $datosCuota = array('mes' => $arr['nrecibo'] . '|' . $arr['reciboPre'], 'documento_id' => $arr['cedula'],
                        'fecha' => $itemCreditos[3], 'credito_id' => trim($itemCreditos[1]),
                        'descripcion' => 'RECIBO: ' . $arr['concepto'], 'monto' => trim($itemCreditos[2]),
                        'mesp' => $fechap[1], 'anop' => $fechap[0], 'moda' => 3, 'farc' => $arr['fecha'], 'usua' => $usua);
                    
                    
                    $this->db->insert('t_lista_cobros', $datosCuota);

                    /*$cuerpo .= '
                                <tr>
                                    <td colspan="4">Recibe un caluroso saludo de parte de Electr&oacute;n 465, mediante la presente te notificamos que ha sido descontada la cantidad de:
                                     '. $montoL .' BS ('.trim($itemCreditos[2]).') BS. Correspondientes al pago del '. $arr['fecha'] .'.<br><br></td>
                                </tr>
                                <tr>
                                    <td colspan="4">Si tienes alguna pregunta o si necesitas alguna asistencia con respecto a esta comunicaci&oacute;n, estamos aqu&iacute; a tu disposici&oacute;n puedes comunicarte con nuestro equipo de atenci&oacute;n al cliente a trav&eacute;s del n&uacute;mero telef&oacute;nico: 0274-9358009 en el siguiente horario: de 8.30am a 12.00m, y luego de 2.00pm a 5.00pm de lunes a viernes, igualmente puedes comunicarte los 365 d&iacute;as del a&ntilde;o las 24 horas del d&iacute;a a trav&eacute;s de nuestro correo electr&oacute;nico: soporte@electron465.com
                                    <br><br>
                                    Muchas gracias por ser parte de la comunidad Electr&oacute;n 465.
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="4"><hr></hr><small>
                                        Por favor, no responda este e-mail ya que ha sido enviado autom&aacute;ticamente. Usted ha recibido esta comunicaci&oacute;n por tener una relaci&oacute;n de pagos con Electr&oacute;n 465. Esta comunicaci&oacute;n forma parte b&aacute;sica de nuestro programa de atenci&oacute;n al cliente. Si no desea seguir recibiendo este tipo de comunicaciones, le rogamos cancele por escrito su afiliaci&oacute;n al mismo.
                                         <br>Electr&oacute;n 465 se compromete firmemente a respetar su privacidad. No compartimos su informaci&oacute;n con ning&uacute;n tercero sin su consentimiento.</small>

                                    </td>
                                </tr>
                            </table>
                            ';
                    if($correo -> correo != ''){
                        $correoE = $correo -> correo;
                        //mail("$correoE","Departamento De Cobros Electron 465",$cuerpo, $cabeceras);
                    }*/
                }
            }
            if ($arr['cargar1'] == 1) {
                $creditos = explode(',', $arr['voucher']);
                foreach ($creditos as $cadena) {
                    $itemCreditos = explode('|', $cadena);
                    $datosRecibo = array('id_recibo' => $arr['nrecibo'], 'factura' => trim($itemCreditos[0]), 'contrato' => trim($itemCreditos[1]), 'monto' => 'Monto Voucher');
                    $this->db->insert('t_lista_recibo', $datosRecibo);
                    $this->db->query("UPDATE t_lista_voucher SET estatus=1,observacion='RECIBO INGRESO: " . $arr['nrecibo']  . '| RECIBO PRE: ' . $arr['reciboPre'] .  "| CONCEPTO: " . $arr['concepto'] . "' where cid='" . trim($itemCreditos[0]) . "' and ndep='" . trim($itemCreditos[1]) . "'");
                }
            }
            $msj .= 'El recibo de ingreso se cargo con exito..';
        }

        return $msj;
    }

    /**
     * Esta Funcion Elimina: t_lista_cobros, t_recibo_ingreso,
     *
     * @var string Numero Recibo
     */
    function Eliminar_Recibo($sNumero){
    	
        $this->db->query("DELETE FROM t_lista_cobros WHERE mes like '" . $sNumero . "%' LIMIT 1");
        $this->db->query("DELETE FROM t_recibo_ingreso WHERE numero_recibo = '" . $sNumero . "' LIMIT 1");
        $this->db->query("DELETE FROM t_lista_recibo WHERE id_recibo = '" . $sNumero . "' LIMIT 1");
        echo "Proceso finalizo con exito....";
    }

    function ValorEnLetras($x, $Moneda)
    {
        $s = "";
        $Ent = "";
        $Frc = "";
        $Signo = "";

        if (floatVal($x) < 0)
            $Signo = $this->Neg . " ";
        else
            $Signo = "";

        if (intval(number_format(floatval($x), 2, '.', '')) != $x)//<- averiguar si tiene decimales
            $s = number_format($x, 2, '.', '');
        else
            $s = number_format(floatval($x), 0, '.', '');

        $Pto = strpos($s, $this->Dot);

        if ($Pto === false) {
            $Ent = $s;
            $Frc = $this->Void;
        } else {
            $Ent = substr($s, 0, $Pto);
            $Frc = substr($s, $Pto + 1);
        }

        if ($Ent == $this->Zero || $Ent == $this->Void)
            $s = "Cero ";
        elseif (strlen($Ent) > 7) {
            $s = $this->SubValLetra(intval(substr($Ent, 0, strlen($Ent) - 6))) . "Millones " . $this->SubValLetra(intval(substr($Ent, -6, 6)));
        } else {
            $s = $this->SubValLetra(intval($Ent));
        }

        if (substr($s, -9, 9) == "Millones " || substr($s, -7, 7) == "Millón ")
            $s = $s . "de ";

        $s = $s . $Moneda;

        if ($Frc != $this->Void) {
            $s = $s . " Con " . $this->SubValLetra(intval($Frc)) . "Centimos";
            //$s = $s . " " . $Frc . "/100";
        }
        return ($Signo . $s . "");

    }

    function SubValLetra($numero)
    {
        $Ptr = "";
        $n = 0;
        $i = 0;
        $x = "";
        $Rtn = "";
        $Tem = "";

        $x = trim("$numero");
        $n = strlen($x);

        $Tem = $this->Void;
        $i = $n;

        while ($i > 0) {
            $Tem = $this->Parte(intval(substr($x, $n - $i, 1) . str_repeat($this->Zero, $i - 1)));
            If ($Tem != "Cero")
                $Rtn .= $Tem . $this->SP;
            $i = $i - 1;
        }

        //--------------------- GoSub FiltroMil ------------------------------
        $Rtn = str_replace(" Mil Mil", " Un Mil", $Rtn);
        while (1) {
            $Ptr = strpos($Rtn, "Mil ");
            If (!($Ptr === false)) {
                If (!(strpos($Rtn, "Mil ", $Ptr + 1) === false))
                    $this->ReplaceStringFrom($Rtn, "Mil ", "", $Ptr);
                Else
                    break;
            } else
                break;
        }

        //--------------------- GoSub FiltroCiento ------------------------------
        $Ptr = -1;
        do {
            $Ptr = strpos($Rtn, "Cien ", $Ptr + 1);
            if (!($Ptr === false)) {
                $Tem = substr($Rtn, $Ptr + 5, 1);
                if ($Tem == "M" || $Tem == $this->Void)
                    ;
                else
                    $this->ReplaceStringFrom($Rtn, "Cien", "Ciento", $Ptr);
            }
        } while (!($Ptr === false));

        //--------------------- FiltroEspeciales ------------------------------
        $Rtn = str_replace("Diez Un", "Once", $Rtn);
        $Rtn = str_replace("Diez Dos", "Doce", $Rtn);
        $Rtn = str_replace("Diez Tres", "Trece", $Rtn);
        $Rtn = str_replace("Diez Cuatro", "Catorce", $Rtn);
        $Rtn = str_replace("Diez Cinco", "Quince", $Rtn);
        $Rtn = str_replace("Diez Seis", "Dieciseis", $Rtn);
        $Rtn = str_replace("Diez Siete", "Diecisiete", $Rtn);
        $Rtn = str_replace("Diez Ocho", "Dieciocho", $Rtn);
        $Rtn = str_replace("Diez Nueve", "Diecinueve", $Rtn);
        $Rtn = str_replace("Veinte Un", "Veintiun", $Rtn);
        $Rtn = str_replace("Veinte Dos", "Veintidos", $Rtn);
        $Rtn = str_replace("Veinte Tres", "Veintitres", $Rtn);
        $Rtn = str_replace("Veinte Cuatro", "Veinticuatro", $Rtn);
        $Rtn = str_replace("Veinte Cinco", "Veinticinco", $Rtn);
        $Rtn = str_replace("Veinte Seis", "Veintiseís", $Rtn);
        $Rtn = str_replace("Veinte Siete", "Veintisiete", $Rtn);
        $Rtn = str_replace("Veinte Ocho", "Veintiocho", $Rtn);
        $Rtn = str_replace("Veinte Nueve", "Veintinueve", $Rtn);

        //--------------------- FiltroUn ------------------------------
        If (substr($Rtn, 0, 1) == "M")
            $Rtn = "Un " . $Rtn;
        //--------------------- Adicionar Y ------------------------------
        for ($i = 65; $i <= 88; $i++) {
            If ($i != 77)
                $Rtn = str_replace("a " . Chr($i), "* y " . Chr($i), $Rtn);
        }
        $Rtn = str_replace("*", "a", $Rtn);
        return ($Rtn);
    }

    function ReplaceStringFrom(&$x, $OldWrd, $NewWrd, $Ptr)
    {
        $x = substr($x, 0, $Ptr) . $NewWrd . substr($x, strlen($OldWrd) + $Ptr);
    }

    function Parte($x)
    {
        $Rtn = '';
        $t = '';
        $i = '';
        Do {
            switch ($x) {
                Case 0 :
                    $t = "Cero";
                    break;
                Case 1 :
                    $t = "Un";
                    break;
                Case 2 :
                    $t = "Dos";
                    break;
                Case 3 :
                    $t = "Tres";
                    break;
                Case 4 :
                    $t = "Cuatro";
                    break;
                Case 5 :
                    $t = "Cinco";
                    break;
                Case 6 :
                    $t = "Seis";
                    break;
                Case 7 :
                    $t = "Siete";
                    break;
                Case 8 :
                    $t = "Ocho";
                    break;
                Case 9 :
                    $t = "Nueve";
                    break;
                Case 10 :
                    $t = "Diez";
                    break;
                Case 20 :
                    $t = "Veinte";
                    break;
                Case 30 :
                    $t = "Treinta";
                    break;
                Case 40 :
                    $t = "Cuarenta";
                    break;
                Case 50 :
                    $t = "Cincuenta";
                    break;
                Case 60 :
                    $t = "Sesenta";
                    break;
                Case 70 :
                    $t = "Setenta";
                    break;
                Case 80 :
                    $t = "Ochenta";
                    break;
                Case 90 :
                    $t = "Noventa";
                    break;
                Case 100 :
                    $t = "Cien";
                    break;
                Case 200 :
                    $t = "Doscientos";
                    break;
                Case 300 :
                    $t = "Trescientos";
                    break;
                Case 400 :
                    $t = "Cuatrocientos";
                    break;
                Case 500 :
                    $t = "Quinientos";
                    break;
                Case 600 :
                    $t = "Seiscientos";
                    break;
                Case 700 :
                    $t = "Setecientos";
                    break;
                Case 800 :
                    $t = "Ochocientos";
                    break;
                Case 900 :
                    $t = "Novecientos";
                    break;
                Case 1000 :
                    $t = "Mil";
                    break;
                Case 1000000 :
                    $t = "Millón";
                    break;
            }

            If ($t == $this->Void) {
                $i = $i + 1;
                $x = $x / 1000;
                If ($x == 0)
                    $i = 0;
            } else
                break;

        } while ($i != 0);

        $Rtn = $t;
        Switch ($i) {
            Case 0 :
                $t = $this->Void;
                break;
            Case 1 :
                $t = " Mil";
                break;
            Case 2 :
                $t = " Millones";
                break;
            Case 3 :
                $t = " Billones";
                break;
        }
        return ($Rtn . $t);
    }

    public function mes_letras($mesN)
    {
        switch ($mesN) {
            case '1' :
                $mes = "ENERO";
                break;
            case '2' :
                $mes = "FEBRERO";
                break;
            case '3' :
                $mes = "MARZO";
                break;
            case '4' :
                $mes = "ABRIL";
                break;
            case '5' :
                $mes = "MAYO";
                break;
            case '6' :
                $mes = "JUNIO";
                break;
            case '7' :
                $mes = "JULIO";
                break;
            case '8' :
                $mes = "AGOSTO";
                break;
            case '9' :
                $mes = "SEPTIEMBRE";
                break;
            case '10' :
                $mes = "OCTUBRE";
                break;
            case '11' :
                $mes = "NOVIEMBRE";
                break;
            case '12' :
                $mes = "DICIEMBRE";
                break;
            default :
                break;
        }
        return $mes;
    }

}

?>