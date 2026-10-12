<table>
	<tr>
		<td>
			<div>
				<h4> Recuerde que debe estar seguro que el contrato por el cual va a cambiar no esta asignado a otro.</h4>
				<br><br>
				<table style="width:500px">
					<tr>
						<td class="formulario">&nbsp;N&uacute;mero de Contrato (*)</td>
						<td class="formulario">&nbsp;Modificaci&oacute;n de Contrato</td>
						<td class="formulario">&nbsp;</td>
						<td></td>
					</tr>
					<tr>
						<td class="formulario"><input type="text" class="inputxt" style="width:200px" name="txtContrato_A" id="txtContrato_A"></td>
						<td class="formulario"><input type="text" class="inputxt" style="width:200px" name="txtContrato_N" id="txtContrato_N"></td>
						<td class="formulario">
							<input type="button" style="height:19px; width:100px" value="Realizar Cambios" 
											onclick="Modificar_Contratos('<?php echo __LOCALWWW__?>');" >
						</td>
						<td>
								
								<div id="divConsultaContrato">
										<img src='<?php echo __IMG__?>cargando_tab.gif' style="margin: 0px 0 0px 0; padding: 0px 0px 0px 0px;" >
									</div>
						</td>
					</tr>
				</table>
				<div id="divActualizarContrato"></div>
			</div>
		</td>
	</tr>
</table>
<br><br>


<br /><br />
<hr >
<br /><br />
<table>
	<tr>
		<td>
			<div>
				<h4> Quitar suspenciones...</h4>
				<br><br>
				<table style="width:300px">
					<tr>
						<td class="formulario">&nbsp;N&uacute;mero de C&eacute;dula (*)</td>
						<td class="formulario">&nbsp;</td>
						<td></td>
					</tr>
					<tr>
						<td class="formulario"><input type="text" class="inputxt" style="width:200px" name="txtCedulaA" id="txtCedulaA"></td>
						<td class="formulario"> 
							<input type="button" style="height:19px; width:100px" value="Activar" 
											onclick="btnDAlta('<?php echo __LOCALWWW__?>');">
						</td>
						<td>
								
									<div id="divProcesarAlta">
										<img src='<?php echo __IMG__?>cargando_tab.gif' style="margin: 0px 0 0px 0; padding: 0px 0px 0px 0px;">
									</div>
						</td>
					</tr>
				</table>
				<div id="divAltaCedula"></div>
			</div>
		</td>
	</tr>
</table>

<br /><br />
<hr >
<br /><br />
<table>
	<tr>
		<td>
			<div>
				<h4> Recuerde que debe estar seguro que el serial existe para realizar dicho cambio.</h4>
				<br><br>
				<table style="width:500px">
					<tr>
						<td class="formulario">&nbsp;N&uacute;mero de Serial (*)</td>
						<td class="formulario">&nbsp;Modificaci&oacute;n del Serial</td>
						<td class="formulario">&nbsp;</td>
						<td></td>
					</tr>
					<tr>
						<td class="formulario"><input type="text" class="inputxt" style="width:200px" name="txtSerial_A" id="txtSerial_A"></td>
						<td class="formulario"><input type="text" class="inputxt" style="width:200px" name="txtSerial_N" id="txtSerial_N"></td>
						<td class="formulario">
							<input type="button" style="height:19px; width:100px" value="Realizar Cambios" 
											onclick="Modificar_Serial('<?php echo __LOCALWWW__?>');" >
						</td>
						<td>
								
								<div id="divConsultaSerial">
										<img src='<?php echo __IMG__?>cargando_tab.gif' style="margin: 0px 0 0px 0; padding: 0px 0px 0px 0px;" >
									</div>
						</td>
					</tr>
				</table>
				<div id="divActualizarSerial"></div>
			</div>
		</td>
	</tr>
</table>



<br /><br />
<hr >
<br /><br />
<table>
	<tr>
		<td>
			<div>
				<h4> Recuerde que debe estar seguro que la cedula por el cual va a cambiar no esta asignado a otro cliente.</h4>
				<br><br>
				<table style="width:500px">
					<tr>
						<td class="formulario">&nbsp;N&uacute;mero de Cedula (*)</td>
						<td class="formulario">&nbsp;Modificaci&oacute;n de Cedula</td>
						<td class="formulario">&nbsp;</td>
						<td></td>
					</tr>
					<tr>
						<td class="formulario"><input type="text" class="inputxt" style="width:200px" name="txtCedula_A" id="txtCedula_A"></td>
						<td class="formulario"><input type="text" class="inputxt" style="width:200px" name="txtCedula_N" id="txtCedula_N"></td>
						<td class="formulario">
							<input type="button" style="height:19px; width:100px" value="Realizar Cambios" 
											onclick="Modificar_Cedula('<?php echo __LOCALWWW__?>');" >
						</td>
						<td>
								
								<div id="divConsultaCedula">
										<img src='<?php echo __IMG__?>cargando_tab.gif' style="margin: 0px 0 0px 0; padding: 0px 0px 0px 0px;" >
									</div>
						</td>
					</tr>
				</table>
				<div id="divActualizarCedula"></div>
			</div>
		</td>
	</tr>
</table>

<br><br>
<hr>
<br><br>

<table>
	<tr>
		<td>
			<div>
				<h4> MODIFICA NUMERO DE FACTURA<br>Recuerde que debe estar seguro que la factura por el cual va a cambiar no esta asignado a otro.</h4>
				<br><br>
				<table style="width:500px">
					<tr>
						<td class="formulario">&nbsp;N&uacute;mero de Factura (*)</td>
						<td class="formulario">&nbsp;Modificaci&oacute;n de Factura</td>
						<td class="formulario">&nbsp;</td>
						<td></td>
					</tr>
					<tr>
						<td class="formulario"><input type="text" class="inputxt" style="width:200px" name="txtFactura_A" id="txtFactura_A"></td>
						<td class="formulario"><input type="text" class="inputxt" style="width:200px" name="txtFactura_N" id="txtFactura_N"></td>
						<td class="formulario">
							<input type="button" style="height:19px; width:100px" value="Realizar Cambios" 
											onclick="Modificar_Facturas('<?php echo __LOCALWWW__?>');" >
						</td>
						<td>
								
								<div id="divConsultaFactura">
										<img src='<?php echo __IMG__?>cargando_tab.gif' style="margin: 0px 0 0px 0; padding: 0px 0px 0px 0px;" >
									</div>
						</td>
					</tr>
				</table>
				<div id="divActualizarFactura"></div>
			</div>
		</td>
	</tr>
</table>
<br><br>


<table>
	<tr>
		<td>
			<div>
				<h4> MODIFICA DATOS DE LA FACTURA<br>Recuerde que SOLO LOS CAMPOS CON VALOR ASIGNADO seran modificados.</h4>
				<br><br>
				<table style="width:500px">
					<tr>
						<td class="formulario"style="width:200px">N&uacute;mero de Factura </td>
						<td class="formulario"><input type="text" class="inputxt" style="width:200px" name="txtNumero_Factura" id="txtNumero_Factura" onblur="BFactura_Modificar('<?php echo __LOCALWWW__ ?>');"></td>
					</tr>
					<tr>
						<td style='width:200px;'>Motivo o Concepto:</td>
						<td align="left" colspan="5">
							<select name="txtMotivo"	id="txtMotivo" class="inputxt" style="width: 200px;" >
							<option value=0>----------------------</option>
							<option value=1>-- FINANCIAMIENTO --</option>
							<option value=2>-- PRESTAMO --</option>
							<option value=3>-- PRESTAMO Y FINACIAMIENTO --</option>
							</select>
						</td>
					</tr>
					<tr>
						<td align="left">Condici&oacute;n</td>
						<td align="left" >
							<select name="txtCondicion"	id="txtCondicion" class="inputxt" style="width: 200px;" >
								<option value='4'>----------</option>
								<option value='0'>DEPOSITO</option>
								<option value='1'>TRANSFERENCIA</option>					
								<option value='3'>CHEQUE</option> 
								<option value='5'>EQUIPO O ARTEFACTO</option>
								<option value='6'>PENDIENTE POR AUTORIZAR</option>
								<option value='7'>MOTO</option>			
							</select>
						</td>
					</tr>
					<tr>
						<td># Deposito :</td>
						<td>
							<input  name="txtDeposito" id="txtDeposito" class="inputxt" style="width: 180px;" >
						</td>
					</tr>
					<tr>
						<td class="formulario">Fecha Operaci&oacute;n :</td>		
						<td align="left"  style="width:210px" colspan="3">
							<select name="txtDiaO" id="txtDiaO" class="inputxt" style="width: 50px;" >
								<option value=0>D&iacute;a</option>
								<?php for($i = 1; $i <= 31; $i++){	?>
				  					<option value='<?php echo $i ?>'><?php echo $i ?></option>
								<?php	}	?>
							</select>
							<select name="txtMesO"	id="txtMesO" class="inputxt" style="width: 50px;" >
								<option value=0>Mes</option>
								<?php 	for($i = 1; $i <= 12; $i++){		?>
									<option value='<?php echo $i ?>'><?php echo $i ?></option>
								<?php	}	?>
							</select>
							<select name="txtAnoO"	id="txtAnoO" class="inputxt" style="width: 60px;" >
								<option value=0>A&ntilde;o</option>
								<?php for($i = 2006; $i <= 2014; $i++){	?>
									<option value='<?php echo $i ?>'><?php echo $i ?></option>
								<?php	}	?>
							</select>
						</td>
					</tr>
					<tr>
					<td class="formulario">	Monto:</td>
						<td><input  name="txtMonto" id="txtMonto" class="inputxt" style="width: 180px;" ></td>
					</tr>
					<tr>
						<td class="formulario">
							<input type="button" style="height:19px; width:100px" value="Realizar Cambios" 
											onclick="Modificar_Datos_Factura('<?php echo __LOCALWWW__?>');" >
						</td>
						<td>
								
								<div id="divConsultaFactura2">
										<img src='<?php echo __IMG__?>cargando_tab.gif' style="margin: 0px 0 0px 0; padding: 0px 0px 0px 0px;" >
									</div>
						</td>
					</tr>
				</table>
				<div id="divActualizarFactura2"></div>
			</div>
		</td>
	</tr>
</table>


<br><br><br><br>