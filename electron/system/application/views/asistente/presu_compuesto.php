<html>
<head>	
	<?php $this -> load -> view("incluir/cabezera.php"); ?>
	<link href="<?php echo __CSS__ ?>imprimir.css" rel="stylesheet" type="text/css" media="print"/>
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>view/presu_compuesto.js"></script>
</head>
<body>
	
	<div id="medio" >	
		<div id="msj_alertas"></div>
		<h2 class="demoHeaders">Asistente para elaboracion de contratos</h2><br>
		<h3>Usuario Conectado: <?php echo $this -> session -> userdata('descripcion'); ?> Fecha: <?php echo date("Y/m/d"); ?></h3>
		<img src="<?php echo __IMG__ ?>menu/plan.png">		
		<div>
	<center>
	<div id='NumeroSerie'> 
		<font style='font-size:20px;'><b>CODIGO DE CERTIFICADO : <label id='certificado'><?php echo $cert;?></label></b></font><BR>
	</div>
	<div id='Cliente'> 
		<font style='font-size:14px; '><b>CEDULA DEL CLIENTE: <?php echo $id;?><br> NOMBRE : <?php echo $nom?> </b></font><BR>	
	</div>
	
	<form action="<?php echo __LOCALWWW__?>/index.php/cooperativa/buzon" method="POST" onsubmit="return Imprimir();">
		
		
		<table> 
				<tr>
				<td class="formulario">Tipo <font color='#B70000'>(*)</font>:</td>
				<td style="width: 120px;" align="left" colspan="5">
					<select name="txtTipo"	id="txtTipo" style="width: 460px;" >
						<option value="">Artefactos</option>
						<option value="Moto">Motos</option>
						<option value="Cel">Celular</option>
						
					</select>
				</td>
				
			</tr>
			<tr>				
			 	<td><label id="lblPresupuesto"> Modelo: &nbsp;</label></td>
			 	<td>
					<div class="ui-widget">
						<input type="text" 	name="txtModelo" id="txtModelo" class="inputxt" style="width: 460px;"	value=''>						
					</div>
				</td>
			 </tr>
			 <tr>
			 	<td><label id="lblAbono"> Abono Inicial: &nbsp;</label></td>
			 	<td>
					<div class="ui-widget">
						<input type="text" 	name="txtAbono" id="txtAbono" class="inputxt" onkeydown="return soloNumeros(event);"	value=0>						
					</div>
				</td>
			 </tr>	 
			 <tr>
			 	<td colspan="2">
			 		<input  type="hidden" value="" name="txtCalculo" id="txtCalculo" />			 	
					<input type="hidden" value="" name="txtDes" id="txtDes" class="inputxt"   style="width: 220px;"/>
					<input type="hidden" value="" name="txtPorcentaje" id="txtPorcentaje" class="inputxt"   style="width: 220px;"/>
					<input type="hidden" value="1" name="txtMuestra" id="txtMuestra" class="inputxt"   style="width: 220px;"/>
					<input type="hidden" value="<?php echo $id;?>" name="txtCed" id="txtCed" class="inputxt"   style="width: 220px;"/>
			 	</td>			 	
			 </tr>
			 <tr>
			 	<td colspan="2" align="center">
			 		<input type="button" class="inputxt" value='Realizar Calculo' id='btnCalcular' onclick="BModelo();"/>
			 	</td>
			 </tr>
		</table>
<br><br>
<font style='font-size:20px; '><b>PRESUPUESTO DEL CREDITO</b></font><BR>
	<div>
		<img src="<?php echo __IMG__; ?>nodisponible.jpg" style="width:100px;height: 100px;" id ="foto" name="foto"/>
	</div>
	<div id='info'>
		
		
	</div>
<BR>
<div id='plan_financiamiento' >
<div id='lista' style="display: none">
	<table border=1 cellspacing=0 celladding=0 style="	color: #333333;	width: 90%; border: 1px solid #CCCCCC;"><tr><td>PLAN DE CREDITO</td>
		<td>Credito = PSC + 1%BCV + 1%DOMIC + 1%GA</td><td>MONTO DEL CREDITO</td></tr></b>';
		<tr bgcolor="#CCCCCC"><td style="font-size: 14px; width:250px">
		<select onchange="Seleccion_Mes(this.value);crea_combos(this.value);Asignar(this.value);Validar(this);Calcular_Compuesto(this);" 
		id="cmbMeses" name="cmbMeses" style="width:220px">
			<option value=0>Seleccione</option>	
			<option value="12">12 MESES</option>
			<option value="18">18 MESES</option>
			<option value="19">19 MESES</option>
			<option value="20">20 MESES</option>
			<option value="21">21 MESES</option>
			<option value="22">22 MESES</option>
			<option value="23">23 MESES</option>			
			<option value="24">24 MESES</option>
			<option value="30">30 MESES</option>
			<option value="36">36 MESES</option>
		</select></td><td style="font-size: 14px;"><div id="monto_aux" name="monto_aux">0.00 Bs.</div></td><td style="font-size: 14px;">
			<div  id="monto_aux2" name="monto_aux2">0.00 Bs.</div>
		</td></tr></table>
	</table>
		
</div>


<table style='width: 80%;display: none;' border=0> 			 
			<tr>				
			 	
				<td style="font-size: 14px;">
					<div id="abono_aux" name="abono_aux"><label id='label_abono'></label></div>
				</td>
				<td style="font-size: 14px;"><div  id="abono_aux2" name="abono_aux2"><label id='label_abono2'></label></div>
				</td>
			 </tr>
</table>
<BR><BR>
	<font style='font-size:20px; '><b><label id='lblPlan'>PLAN DE PAGO</label></b></font><BR>
	<input type=hidden value='' name="txtMonto" id="txtMonto" />
	<input type=hidden value='' name="txtAbonoInicial" id="txtAbonoInicial" />
	<input type=hidden value=12 name="txtValor" id="txtValor"/>
	<input type=hidden value='' name="txtCuotas" id="txtCuotas"/>
	<table>
		<tr><td style="width: 240px">DESCRIPCI&Oacute;N</td><td style="width: 100px">CUOTAS</td><td style="width: 150px">MONTO</td></tr>
		<tr><td> 
			<select name="txtNominaPeriocidad1"	id="txtNominaPeriocidad1" class="inputxt" style="width: 220px;" onchange="valida_meses(1);">
				<option value=0>----------------------------</option>
				<option value=1>PAGARE - ENERO</option>
				<option value=2>PAGARE - FEBRERO</option>
				<option value=3>PAGARE - MARZO </option>
				<option value=4>PAGARE - ABRIL </option>
				<option value=5>PAGARE - MAYO </option>
				<option value=6>PAGARE - JUNIO</option>
				<option value=7>PAGARE - JULIO</option>
				<option value=8>PAGARE - AGOSTO</option>
				<option value=9>PAGARE - SEPTIEMBRE</option>
				<option value=10>PAGARE - OCTUBRE</option>
				<option value=11>PAGARE - NOVIEMBRE</option>
				<option value=12>PAGARE - DICIEMBRE</option>
			</select>
			</td>
			<td>
			<select name="txtAno1"	id="txtAno1" class="inputxt" style="width: 70px;" onchange="valida_meses(1);">
				<option value=2013>2013</option>
				
			</select>
			</td>
			<td>
				<input  type="text" value="" name="txtMT1" id="txtMT1" class="inputxt"  onkeydown="return soloNumeros(event);" onclick="limpiar(this);" onchange="Validar(this);Calcular_Compuesto(this)"/>
			</td>
		</tr>
		<tr><td> 
			<select name="txtNominaPeriocidad2"	id="txtNominaPeriocidad2" class="inputxt" style="width: 220px;" onchange="valida_meses(2);">
				<option value=0>----------------------------</option>
				<option value=1>PAGARE - ENERO</option>
				<option value=2>PAGARE - FEBRERO</option>
				<option value=3>PAGARE - MARZO </option>
				<option value=4>PAGARE - ABRIL </option>
				<option value=5>PAGARE - MAYO </option>
				<option value=6>PAGARE - JUNIO</option>
				<option value=7>PAGARE - JULIO</option>
				<option value=8>PAGARE - AGOSTO</option>
				<option value=9>PAGARE - SEPTIEMBRE</option>
				<option value=10>PAGARE - OCTUBRE</option>
				<option value=11>PAGARE - NOVIEMBRE</option>
				<option value=12>PAGARE - DICIEMBRE</option>
			</select>
			</td>
			<td>
			<select name="txtAno2"	id="txtAno2" class="inputxt" style="width: 70px;" onchange="valida_meses(2);">
				<option value=2013>2013</option>
				
			</select>
			</td>
			<td>
				<input  type="text" value="" name="txtMT2" id="txtMT2" class="inputxt"  onclick="limpiar(this);" onkeydown="return soloNumeros(event);" onchange="Calcular_Compuesto(this)"/>
			</td>
		</tr>			
		<tr><td> 
			<select name="txtNominaPeriocidad3"	id="txtNominaPeriocidad3" class="inputxt" style="width: 220px;" onchange="valida_meses(3);">
				<option value=0>----------------------------</option>
				<option value=1>PAGARE - ENERO</option>
				<option value=2>PAGARE - FEBRERO</option>
				<option value=3>PAGARE - MARZO </option>
				<option value=4>PAGARE - ABRIL </option>
				<option value=5>PAGARE - MAYO </option>
				<option value=6>PAGARE - JUNIO</option>
				<option value=7>PAGARE - JULIO</option>
				<option value=8>PAGARE - AGOSTO</option>
				<option value=9>PAGARE - SEPTIEMBRE</option>
				<option value=10>PAGARE - OCTUBRE</option>
				<option value=11>PAGARE - NOVIEMBRE</option>
				<option value=12>PAGARE - DICIEMBRE</option>
			</select>
			</td>
			<td>
			<select name="txtAno3"	id="txtAno3" class="inputxt" style="width: 70px;" onchange="valida_meses(3);">
				<option value=2013>2013</option>
				
			</select>
			</td>
			<td>
				<input  type="text" value="" name="txtMT3" id="txtMT3" class="inputxt"  onclick="limpiar(this);" onkeydown="return soloNumeros(event);" onchange="Calcular_Compuesto(this)"/>
			</td>
		</tr>
		<tr><td> 
			<select name="txtNominaPeriocidad4"	id="txtNominaPeriocidad4" class="inputxt" style="width: 220px;" onchange="valida_meses(4);">
				<option value=0>----------------------------</option>
				<option value=1>PAGARE - ENERO</option>
				<option value=2>PAGARE - FEBRERO</option>
				<option value=3>PAGARE - MARZO </option>
				<option value=4>PAGARE - ABRIL </option>
				<option value=5>PAGARE - MAYO </option>
				<option value=6>PAGARE - JUNIO</option>
				<option value=7>PAGARE - JULIO</option>
				<option value=8>PAGARE - AGOSTO</option>
				<option value=9>PAGARE - SEPTIEMBRE</option>
				<option value=10>PAGARE - OCTUBRE</option>
				<option value=11>PAGARE - NOVIEMBRE</option>
				<option value=12>PAGARE - DICIEMBRE</option>
			</select>
			</td>
			<td>
			<select name="txtAno4"	id="txtAno4" class="inputxt" style="width: 70px;" onchange="valida_meses(4);">
				<option value=2013>2013</option>
				
			</select>
			</td>
			<td>
				<input  type="text" value="" name="txtMT4" id="txtMT4" class="inputxt" onclick="limpiar(this);" onkeydown="return soloNumeros(event);" onchange="Calcular_Compuesto(this)"/>
			</td>
		</tr>
		<tr><td> 
			<select name="txtNominaPeriocidad5"	id="txtNominaPeriocidad5" class="inputxt" style="width: 220px;" onchange="valida_meses(5);">
				<option value=0>----------------------------</option>
				<option value=1>PAGARE - ENERO</option>
				<option value=2>PAGARE - FEBRERO</option>
				<option value=3>PAGARE - MARZO </option>
				<option value=4>PAGARE - ABRIL </option>
				<option value=5>PAGARE - MAYO </option>
				<option value=6>PAGARE - JUNIO</option>
				<option value=7>PAGARE - JULIO</option>
				<option value=8>PAGARE - AGOSTO</option>
				<option value=9>PAGARE - SEPTIEMBRE</option>
				<option value=10>PAGARE - OCTUBRE</option>
				<option value=11>PAGARE - NOVIEMBRE</option>
				<option value=12>PAGARE - DICIEMBRE</option>
			</select>
			</td>
			<td>
			<select name="txtAno5"	id="txtAno5" class="inputxt" style="width: 70px;" onchange="valida_meses(5);" >
				<option value=2013>2013</option>
				
			</select>
			</td>
			<td>
				<input  type="text" value="" name="txtMT10" id="txtMT10" class="inputxt" onclick="limpiar(this);" onkeydown="return soloNumeros(event);" onchange="Calcular_Compuesto(this)"/>
			</td>
		</tr>
		<tr><td> 
			<select name="txtNominaPeriocidad6"	id="txtNominaPeriocidad6" class="inputxt" style="width: 220px;" onchange="valida_meses(6);">
				<option value=0>----------------------------</option>
				<option value=1>PAGARE - ENERO</option>
				<option value=2>PAGARE - FEBRERO</option>
				<option value=3>PAGARE - MARZO </option>
				<option value=4>PAGARE - ABRIL </option>
				<option value=5>PAGARE - MAYO </option>
				<option value=6>PAGARE - JUNIO</option>
				<option value=7>PAGARE - JULIO</option>
				<option value=8>PAGARE - AGOSTO</option>
				<option value=9>PAGARE - SEPTIEMBRE</option>
				<option value=10>PAGARE - OCTUBRE</option>
				<option value=11>PAGARE - NOVIEMBRE</option>
				<option value=12>PAGARE - DICIEMBRE</option>
			</select>
			</td>
			<td>
			<select name="txtAno6"	id="txtAno6" class="inputxt" style="width: 70px;" onchange="valida_meses(6);">
				<option value=2013>2013</option>
				
			</select>
			</td>
			<td>
				<input  type="text" value="" name="txtMT11" id="txtMT11" class="inputxt" onclick="limpiar(this);" onkeydown="return soloNumeros(event);" onchange="Calcular_Compuesto(this)"/>
			</td>
		</tr>
		<tr><td> 
			<select name="txtNominaPeriocidad7"	id="txtNominaPeriocidad7" class="inputxt" style="width: 220px;" onchange="valida_meses(7);">
				<option value=0>----------------------------</option>
				<option value=1>PAGARE - ENERO</option>
				<option value=2>PAGARE - FEBRERO</option>
				<option value=3>PAGARE - MARZO </option>
				<option value=4>PAGARE - ABRIL </option>
				<option value=5>PAGARE - MAYO </option>
				<option value=6>PAGARE - JUNIO</option>
				<option value=7>PAGARE - JULIO</option>
				<option value=8>PAGARE - AGOSTO</option>
				<option value=9>PAGARE - SEPTIEMBRE</option>
				<option value=10>PAGARE - OCTUBRE</option>
				<option value=11>PAGARE - NOVIEMBRE</option>
				<option value=12>PAGARE - DICIEMBRE</option>
			</select>
			</td>
			<td>
			<select name="txtAno7"	id="txtAno7" class="inputxt" style="width: 70px;" onchange="valida_meses(7);">
				<option value=2013>2013</option>
				
			</select>
			</td>
			<td>
				<input  type="text" value="" name="txtMT12" id="txtMT12" class="inputxt" onclick="limpiar(this);" onkeydown="return soloNumeros(event);" onchange="Calcular_Compuesto(this)"/>
			</td>
		</tr>
		<tr><td> 
			<select name="txtNominaPeriocidad8"	id="txtNominaPeriocidad8" class="inputxt" style="width: 220px;" onchange="valida_meses(8);">
				<option value=0>----------------------------</option>
				<option value=1>PAGARE - ENERO</option>
				<option value=2>PAGARE - FEBRERO</option>
				<option value=3>PAGARE - MARZO </option>
				<option value=4>PAGARE - ABRIL </option>
				<option value=5>PAGARE - MAYO </option>
				<option value=6>PAGARE - JUNIO</option>
				<option value=7>PAGARE - JULIO</option>
				<option value=8>PAGARE - AGOSTO</option>
				<option value=9>PAGARE - SEPTIEMBRE</option>
				<option value=10>PAGARE - OCTUBRE</option>
				<option value=11>PAGARE - NOVIEMBRE</option>
				<option value=12>PAGARE - DICIEMBRE</option>
			</select>
			</td>
			<td>
			<select name="txtAno8"	id="txtAno8" class="inputxt" style="width: 70px;" onchange="valida_meses(8);">
				<option value=2013>2013</option>
				
			</select>
			</td>
			<td>
				<input  type="text" value="" name="txtMT13" id="txtMT13" class="inputxt" onclick="limpiar(this);" onkeydown="return soloNumeros(event);" onchange="Calcular_Compuesto(this)"/>
			</td>
		</tr>
		
		
		<tr style='display: none;'><td style="font-size: 14px;"><br><br>OPCION 1</td></tr>
		<tr style='display: none;'><td>			
				<input type="checkbox" value="1" onclick="Validar(this);Calcular_Total(this);" id="1" />&nbsp;&nbsp; PAGARE MENSUAL
			</td>
			<td>
				<input  type="text" value="" name="txtM1" id="txtM1" class="inputxt" disabled="disabled" style="width: 70px; " onchange="Calcular_Total(this);" onkeydown="return soloNumeros(event);"/>
			</td>
			<td>
				<input  type="text" value="" name="txtMT5" id="txtMT5" class="inputxt" disabled="disabled" />
			</td>
	|	</tr>
		<tr><td style="font-size: 14px;"><br><br></td></tr>
		<tr><td>			
				<select id='cmp1' onchange="Validar(this);Calcular_Compuesto(this);"style="width: 100px; ">
					<option value=0>Seleccione</option>
					<option value="6">6 MESES</option>	
					<option value="12">12 MESES</option>
					<option value="18">18 MESES</option>
					<option value="19">19 MESES</option>
					<option value="20">20 MESES</option>
					<option value="21">21 MESES</option>
					<option value="22">22 MESES</option>
					<option value="23">23 MESES</option>			
					<option value="24">24 MESES</option>
				</select>
			</td>
			<td>
				<input  type="text" value="" name="txtCmp1" id="txtCmp1" class="inputxt" style="width: 70px; " onchange="Validar(this);Calcular_Compuesto(this);" onkeydown="return soloNumeros(event);"/>
			</td>
			<td>
				<input  type="text" value="" name="txtMTCmp1" id="txtMTCmp1" class="inputxt" disabled="disabled" />
			</td>
	</tr>
	<tr><td>			
				<input type="text" id='cmp2' name="cmp2" disabled="disabled" value=0 style="width: 100px; "/>
			</td>
			<td>
				<input  type="text" value="" name="txtCmp2" id="txtCmp2" class="inputxt" style="width: 70px; " disabled="disabled"/>
			</td>
			<td>
				<input  type="text" value="" name="txtMTCmp2" id="txtMTCmp2" class="inputxt" disabled="disabled" />
			</td>
	</tr>
	</table><BR><BR>
</div>
	<input type="submit" class="inputxt" value='Imprimir y Enviar A Revision'  id='btnImprimir'/>	<BR><BR>
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
			
			
			
			
</form>
		
	</div>
</body>
</html>