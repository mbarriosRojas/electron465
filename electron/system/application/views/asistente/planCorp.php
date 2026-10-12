<html>
<head>
    <link href="<?php echo __CSS__ ?>tgrid/TGrid.css" rel="stylesheet" type="text/css" media="screen"/>
    <link href="<?php echo __CSS__ ?>imprimir.css" rel="stylesheet" type="text/css" media="print"/>

    <?php $this -> load -> view("incluir/cabezera.php"); ?>

    <script type="text/javascript" src="<?php echo __JSVIEW__ ?>view/planCorp.js"></script>
</head>
<body>
<div id="cabecera"><br>

</div>
<div id="medio" >
    <div id="msj_alertas"></div>
    <h2 class="demoHeaders">Planes Corporativos</h2><br>
    <h3>Usuario Conectado: <?php echo $this -> session -> userdata('descripcion'); ?> Fecha: <?php echo date("Y/m/d"); ?></h3>
    <br><br>

    <div>
        <center>
            <center>
                <form action="<?php echo __LOCALWWW__?>/index.php/cooperativa/buzon" method="POST" onsubmit="return Imprimir();">
                    <table>
                        <tr>
                            <td align="left"><label id="lblProforma"> Monto Proforma &nbsp;</label></td>
                            <td align="left"><label id="lblPresupuesto"> Monto Aprobado  &nbsp;</label></td>
                            <td align="left"><label id="lblTipoplan"> Plan De Credito &nbsp;</label></td>
                        </tr>
                        <tr>
                            <td>
                                <div class="ui-widget">
                                    <input  type="text" value="" name="txtProforma" id="txtProforma" class="inputxt" style="width: 150px;" />
                                </div>
                            </td>

                            <td>
                                <div class="ui-widget">
                                    <input  type="text" value="" name="txtCalculo" id="txtCalculo" class="inputxt" style="width: 150px;"  />
                                </div>
                            </td>
                            <td>
                                <select id='plan' name='plan' >

                                </select>
                            </td>
                            <td>
                                <input type="button" class="inputxt" value='Calcular' id='btnCalcular' onclick="verificar_montos();"/>
                                <input type="submit" class="inputxt" value='Imprimir'  id='btnImprimir' />
                            </td>
                        </tr>
                    </table>
                </form>
                <br>
                <font style='font-size:20px; '><b>PRESUPUESTO DE CR&Eacute;DITO</b></font><BR><BR>
                <div id='lista' style="display: none;">
                    <font style="font-size:20px;"><b>PLAN DE CR&Eacute;DITO DISE&Ntilde;ADOS A:</b></font>
                    <table border=1 cellspacing=0 celladding=0 style="	color: #333333;	width: 80%; border: 1px solid #CCCCCC;">;
                        <tr bgcolor="#CCCCCC">
                            <td style="font-size: 14px; width:500px">
                                <select onchange="crea_combos(this.value);Asignar(this.value);Calcular_Total();" id="cmbMeses" name="cmbMeses">

                                    <?php
                                    for ($i = 4; $i <= 24; $i++) {
                                        echo '<option value=' . $i . '>' . $i . ' MESES</option>';
                                    }
                                    ?>
                                </select>
                            </td>
                            <td style="font-size: 14px;"><div id="monto_aux" name="monto_aux">0.00 Bs.</div></td>
                        </tr>
                    </table>
                </div>
                <BR><BR>
                <font style='font-size:20px; '><b><label id='lblPlan'>PLAN DE PAGO</label></b></font><BR>
                <input type=hidden value="" name="txtMonto" id="txtMonto" />
                <input type=hidden value="" name="txtCuotas" id="txtCuotas"/>
                <table>
                    <tr><td style="width: 240px">DESCRIPCI&Oacute;N</td><td style="width: 100px">CUOTAS</td><td style="width: 150px">MONTO</td></tr>
                    <tr><td>
                            <select name="txtNominaPeriocidad1"	id="txtNominaPeriocidad1" class="inputxt" style="width: 220px;" onchange="valida_meses(1);">
                                <option value=0>----------------------------</option>
                                <option value=1>PAGARE EXTRA - ENERO</option>
                                <option value=2>PAGARE EXTRA - FEBRERO</option>
                                <option value=3>PAGARE EXTRA - MARZO </option>
                                <option value=4>PAGARE EXTRA - ABRIL </option>
                                <option value=5>PAGARE EXTRA - MAYO </option>
                                <option value=6>PAGARE EXTRA - JUNIO</option>
                                <option value=7>PAGARE EXTRA - JULIO</option>
                                <option value=8>PAGARE EXTRA - AGOSTO</option>
                                <option value=9>PAGARE EXTRA - SEPTIEMBRE</option>
                                <option value=10>PAGARE EXTRA - OCTUBRE</option>
                                <option value=11>PAGARE EXTRA - NOVIEMBRE</option>
                                <option value=12>PAGARE EXTRA - DICIEMBRE</option>

                            </select>
                        </td>
                        <td>
                            <select name="txtAno1"	id="txtAno1" class="inputxt" style="width: 70px;" onchange="valida_meses(1);">
                                <option value=2013>2013</option>
                                <option value=2014>2014</option>
                                <option value=2015>2015</option>
                            </select>
                        </td>
                        <td>
                            <input  type="text" value="" name="txtMT1" id="txtMT1" class="inputxt"  onkeydown="return soloNumeros(event);" onclick="limpiar(this);" onchange="Calcular_Total();" />
                        </td>
                    </tr>
                    <tr><td>
                            <select name="txtNominaPeriocidad2"	id="txtNominaPeriocidad2" class="inputxt" style="width: 220px;" onchange="valida_meses(2);">
                                <option value=0>----------------------------</option>
                                <option value=1>PAGARE EXTRA - ENERO</option>
                                <option value=2>PAGARE EXTRA - FEBRERO</option>
                                <option value=3>PAGARE EXTRA - MARZO </option>
                                <option value=4>PAGARE EXTRA - ABRIL </option>
                                <option value=5>PAGARE EXTRA - MAYO </option>
                                <option value=6>PAGARE EXTRA - JUNIO</option>
                                <option value=7>PAGARE EXTRA - JULIO</option>
                                <option value=8>PAGARE EXTRA - AGOSTO</option>
                                <option value=9>PAGARE EXTRA - SEPTIEMBRE</option>
                                <option value=10>PAGARE EXTRA - OCTUBRE</option>
                                <option value=11>PAGARE EXTRA - NOVIEMBRE</option>
                                <option value=12>PAGARE EXTRA - DICIEMBRE</option>

                            </select>
                        </td>
                        <td>
                            <select name="txtAno2"	id="txtAno2" class="inputxt" style="width: 70px;" onchange="valida_meses(2);">
                                <option value=2013>2013</option>
                                <option value=2014>2014</option>
                                <option value=2015>2015</option>
                            </select>
                        </td>
                        <td>
                            <input  type="text" value="" name="txtMT2" id="txtMT2" class="inputxt"  onkeydown="return soloNumeros(event);" onclick="limpiar(this);" onchange="Calcular_Total();"/>
                        </td>
                    </tr>
                    <tr><td>
                            <select name="txtNominaPeriocidad3"	id="txtNominaPeriocidad3" class="inputxt" style="width: 220px;" onchange="valida_meses(3);">
                                <option value=0>----------------------------</option>
                                <option value=1>PAGARE EXTRA - ENERO</option>
                                <option value=2>PAGARE EXTRA - FEBRERO</option>
                                <option value=3>PAGARE EXTRA - MARZO </option>
                                <option value=4>PAGARE EXTRA - ABRIL </option>
                                <option value=5>PAGARE EXTRA - MAYO </option>
                                <option value=6>PAGARE EXTRA - JUNIO</option>
                                <option value=7>PAGARE EXTRA - JULIO</option>
                                <option value=8>PAGARE EXTRA - AGOSTO</option>
                                <option value=9>PAGARE EXTRA - SEPTIEMBRE</option>
                                <option value=10>PAGARE EXTRA - OCTUBRE</option>
                                <option value=11>PAGARE EXTRA - NOVIEMBRE</option>
                                <option value=12>PAGARE EXTRA - DICIEMBRE</option>

                            </select>
                        </td>
                        <td>
                            <select name="txtAno3"	id="txtAno3" class="inputxt" style="width: 70px;" onchange="valida_meses(3);">
                                <option value=2013>2013</option>
                                <option value=2014>2014</option>
                                <option value=2015>2015</option>
                            </select>
                        </td>
                        <td>
                            <input  type="text" value="" name="txtMT3" id="txtMT3" class="inputxt"  onkeydown="return soloNumeros(event);" onclick="limpiar(this);" onchange="Calcular_Total();"/>
                        </td>
                    </tr>
                    <tr><td>
                            <select name="txtNominaPeriocidad4"	id="txtNominaPeriocidad4" class="inputxt" style="width: 220px;" onchange="valida_meses(4);">
                                <option value=0>----------------------------</option>
                                <option value=1>PAGARE EXTRA - ENERO</option>
                                <option value=2>PAGARE EXTRA - FEBRERO</option>
                                <option value=3>PAGARE EXTRA - MARZO </option>
                                <option value=4>PAGARE EXTRA - ABRIL </option>
                                <option value=5>PAGARE EXTRA - MAYO </option>
                                <option value=6>PAGARE EXTRA - JUNIO</option>
                                <option value=7>PAGARE EXTRA - JULIO</option>
                                <option value=8>PAGARE EXTRA - AGOSTO</option>
                                <option value=9>PAGARE EXTRA - SEPTIEMBRE</option>
                                <option value=10>PAGARE EXTRA - OCTUBRE</option>
                                <option value=11>PAGARE EXTRA - NOVIEMBRE</option>
                                <option value=12>PAGARE EXTRA - DICIEMBRE</option>

                            </select>
                        </td>
                        <td>
                            <select name="txtAno4"	id="txtAno4" class="inputxt" style="width: 70px;" onchange="valida_meses(4);">
                                <option value=2013>2013</option>
                                <option value=2014>2014</option>
                                <option value=2015>2015</option>
                            </select>
                        </td>
                        <td>
                            <input  type="text" value="" name="txtMT4" id="txtMT4" class="inputxt"  onkeydown="return soloNumeros(event);" onclick="limpiar(this);" onchange="Calcular_Total();"/>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>
                            <input  type="text" value="" name="txtMU" id="txtMU" class="inputxt" disabled="disabled" style="width: 70px; " />
                        </td>
                        <td>
                            <input  type="text" value="" name="txtMTU" id="txtMTU" class="inputxt" disabled="disabled" />
                        </td>
                    </tr>

                    <table><tr><td style="width:500px" valign="bottom">
                                <br><br>_______________________________________<br>
                                FIRMA SUPERVISOR DE VENTAS<br><br><br>
                            </td><td style="width:100px" align="center">
                                _______________________________________<br>
                                FIRMA ANALISTA DE VENTAS.- <br>
                                ELABORADO EL: <?php echo date("Y/m/d"); ?>
                            </td>
                        </tr>
                    </table>
                    <center>
                        <table border="0" style="width:70%; height: 100px">
                            <tr>
                                <td style="width:100px; height: 80px" >
                                    &nbsp;
                                </td>
                                <td align="center"><br><br>_______________________________________<br>
                                    FIRMA RECIBI CONFORME.- <br>
                                    C.I: <br>
                                    APELLIDOS Y NOMBRE: <br><br>
                                <td style="width:100px; height: 80px" >
                                    &nbsp;
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align: left;font-size: 10px;"><b>Pulgar Izquierdo</b></td>
                                <td style="text-align: center">&nbsp;</td>
                                <td style="text-align: right;font-size: 10px;"><b>Pulgar Derecho</b></td>
                            </tr>
                        </table></center>
</html>