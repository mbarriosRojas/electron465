<html>
<head>

    <link href="<?php echo __CSS__ ?>imprimir.css" rel="stylesheet" type="text/css" media="print"/>
    <?php $this -> load -> view("incluir/cabezera.php"); ?>
    <script type="text/javascript" src="<?php echo __JSVIEW__ ?>view/presucorreo.js"></script>
</head>
<body style="background-color: #FFFACD;">
<div class="cajaexterna">
    <div id="cargando" class="cajacentrada"><img src="<?php echo __IMG__?>loading.gif"/></div>
    <div id="resp" class="cajacentrada"></div>
</div>
<center>
    <br><h2>PRESUPUESTO</h2><br>

    <center>
        <form action="#" method="POST" onsubmit="return enviar();">
            <table>
                <tr>
                    <td align="left"><label>Cedula</label></td>
                    <td align="left" colspan="3"><label> Nombre</label></td>

                </tr>
                <tr>
                    <td >
                        <div class="ui-widget">
                            <input  type="text" value="" name="txtCed" id="txtCed" class="inputxt" style="width: 150px;" onblur="buscaCed();" />
                        </div>
                    </td>

                    <td colspan="3">
                        <div class="ui-widget">
                            <input  type="text" value="" name="txtNom" id="txtNom" class="inputxt" style="width: 90%;"  />
                            <laber id="encontrado"></laber>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td align="left"><label id="lblPresupuesto"> Monto total</label></td>
                </tr>
                <tr>
                    <td>
                        <div class="ui-widget">
                            <input  type="text" value="" name="txtCalculo" id="txtCalculo" class="inputxt" style="width: 150px;"  />
                        </div>
                    </td>
                    <td>
                        <input type="button" class="inputxt" value='Calcular' id='btnCalcular' onclick="verificar_montos();"/>
                    </td>
                </tr>
                <tr>
                    <td align="left"><label id="lblProforma"> Correo &nbsp;</label></td>

                </tr>
                <tr>
                    <td>
                        <div class="ui-widget">
                            <input  type="text" value="" name="txtCorreo" id="txtCorreo" class="inputxt" style="width: 150px;"  />
                        </div>
                    </td>
                    <td>
                        <input type="submit" class="inputxt" value='Enviar'  id='btnImprimir' />
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
                        <select onchange="crea_combos(this.value);Calcular_Total();" id="cmbMeses" name="cmbMeses">

                            <?php
                            for ($i = 6; $i <= 36; $i++) {
                                echo '<option value=' . $i . '>' . $i . ' MESES</option>';
                            }
                            ?>
                        </select>
                    </td>
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
                        <option value=2015>2015</option>
                        <option value=2016>2016</option>
                        <option value=2017>2017</option>
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
                        <option value=2015>2015</option>
                        <option value=2016>2016</option>
                        <option value=2017>2017</option>
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
                        <option value=2015>2015</option>
                        <option value=2016>2016</option>
                        <option value=2017>2017</option>
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
            <tr><td>
                    <select name="txtNominaPeriocidad5"	id="txtNominaPeriocidad5" class="inputxt" style="width: 220px;" onchange="valida_meses(5);">
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
                    <select name="txtAno5"	id="txtAno5" class="inputxt" style="width: 70px;" onchange="valida_meses(5);">
                        <option value=2015>2015</option>
                        <option value=2016>2016</option>
                        <option value=2017>2017</option>
                    </select>
                </td>
                <td>
                    <input  type="text" value="" name="txtMT5" id="txtMT5" class="inputxt"  onkeydown="return soloNumeros(event);" onclick="limpiar(this);" onchange="Calcular_Total();"/>
                </td>
            </tr>
            <tr><td>
                    <select name="txtNominaPeriocidad6"	id="txtNominaPeriocidad6" class="inputxt" style="width: 220px;" onchange="valida_meses(6);">
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
                    <select name="txtAno6"	id="txtAno6" class="inputxt" style="width: 70px;" onchange="valida_meses(6);">
                        <option value=2015>2015</option>
                        <option value=2016>2016</option>
                        <option value=2017>2017</option>
                    </select>
                </td>
                <td>
                    <input  type="text" value="" name="txtMT6" id="txtMT6" class="inputxt"  onkeydown="return soloNumeros(event);" onclick="limpiar(this);" onchange="Calcular_Total();"/>
                </td>
            </tr>

            <tr><td>
                    <select name="txtNominaPeriocidad7"	id="txtNominaPeriocidad7" class="inputxt" style="width: 220px;" onchange="valida_meses(7);">
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
                    <select name="txtAno7"	id="txtAno7" class="inputxt" style="width: 70px;" onchange="valida_meses(7);">
                        <option value=2015>2015</option>
                        <option value=2016>2016</option>
                        <option value=2017>2017</option>
                    </select>
                </td>
                <td>
                    <input  type="text" value="" name="txtMT7" id="txtMT7" class="inputxt"  onkeydown="return soloNumeros(event);" onclick="limpiar(this);" onchange="Calcular_Total();"/>
                </td>
            </tr>
            <tr><td>
                    <select name="txtNominaPeriocidad8"	id="txtNominaPeriocidad8" class="inputxt" style="width: 220px;" onchange="valida_meses(8);">
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
                    <select name="txtAno8"	id="txtAno8" class="inputxt" style="width: 70px;" onchange="valida_meses(8);">
                        <option value=2015>2015</option>
                        <option value=2016>2016</option>
                        <option value=2017>2017</option>
                    </select>
                </td>
                <td>
                    <input  type="text" value="" name="txtMT8" id="txtMT8" class="inputxt"  onkeydown="return soloNumeros(event);" onclick="limpiar(this);" onchange="Calcular_Total();"/>
                </td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <input  type="text" value="" name="txtMU" id="txtMU" class="inputxt"  disabled="disabled" style="width: 70px; " />
                </td>
                <td>
                    <input  type="text" value="" name="txtMTU" id="txtMTU" class="inputxt" disabled="disabled" />
                </td>
            </tr>
        </table>
        <style>
            .cajacentrada {
                margin: 280px auto;
                text-align: center;
                padding: 1em;
            }

            .cajaexterna {
                background-color: rgba(0, 0, 0, 0.5);
                bottom: 0;
                display: none;
                left: 0;
                overflow: auto;
                position: fixed;
                right: 0;
                top: 0;
                z-index: 1000;
            }
            .cerrar {
                text-align:center;
                color: green;
                padding: 9px 24px;
                font-size: 16px;
                cursor: pointer;
                font-weight: 700;
                border-radius: 5px;
                -webkit-border-radius: 5px;
                -moz-border-radius: 5px;
                -o-border-radius: 5px;
                -ms-border-radius: 5px;
                border: 2px solid #282828;
                background: #fff;
                margin-left: 35%;
                margin-right:35%;
                width: auto;
            }
            .banner1 {
                background: url(../images/banner.png) no-repeat;
                background-size: 100% 100%;
                -webkit-background-size: 100% 100%;
                -moz-background-size: 100% 100%;
                -o-background-size: 100% 100%;
                -ms-background-size: 100% 100%;
                min-height: 350px;
            }

        </style>
</body>
</html>