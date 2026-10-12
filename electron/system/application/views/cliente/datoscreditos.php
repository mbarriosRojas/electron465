<?php
	$fecha_actual = date("d-m-Y");
	$fecha_actual= explode('-', $fecha_actual);
?>
<form name="frmRegistrar" id='frmRegistrar'>	
<table style="width:620px" border="0" cellspacing="3" cellpadding="0" class="formulario">
	<tr>
		<td style='width:150px;'>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>	
	</tr>
	<tr>
		<td><strong>N&uacute;m. Contrato <font color='#B70000'>(*)</font> : </strong></td>
		<td align="left" colspan=5>
			<input  name="txtNumero_Contrato" id="txtNumero_Contrato"  onblur="consultar_creditos();"style="width: 200px;" >
			<div style="height: 14px; padding-top: 5px; padding-left: 5px; background-color:#fff; text-align:center; width:100px; float: left; boder: 1px #CCC">
				<a href="#Generar" name="generar" onClick="Generar_Codigo();Monto_Debe();">Generar Codigo</a></div>
		</td>
	</tr>	
	
	<tr>
		<td style="width:150px" >Fecha del Cr&eacute;dito <font color='#B70000'>(*)</font>:</td>
		<td align="left"  style="width:260px" colspan=3 >
		<?php if($Nivel != 0 && $Nivel !=3 && $Nivel != 5 && $Nivel != 9){?>
			
			<input type="text" name="txtDiaC"   id="txtDiaC"style="width: 55px;" disabled='true' value =<?php echo (int)$fecha_actual[0]; ?> />
			<input type="text" name="txtMesC"	id="txtMesC"style="width: 55px;" disabled='true' value =<?php echo (int)$fecha_actual[1]; ?> />
			<input type="text" name="txtAnoC"	id="txtAnoC"style="width: 60px;" disabled='true' value =<?php echo (int)$fecha_actual[2]; ?> />
		<?php }else{?>
			<select name="txtDiaC" id="txtDiaC"style="width: 55px;">
				<option value=0>Dia:</option>
				<?php 	for($i = 1; $i <= 31; $i++){		?>
					<option value='<?php echo $i ?>'><?php echo $i ?></option>
				<?php	}	?>
			</select>
			<select name="txtMesC"	id="txtMesC"style="width: 55px;">
				<option value=0>Mes:</option>
				<?php 	for($i = 1; $i <= 12; $i++){		?>
					<option value='<?php echo $i ?>'><?php echo $i ?></option>
				<?php	}	?>
			</select>
			<select name="txtAnoC"	id="txtAnoC"style="width: 60px;">
				<option value=0>A&ntilde;o:</option>
				<?php 	for($i = 2022; $i <= 2028; $i++){		?>
					<option value='<?php echo $i ?>'><?php echo $i ?></option>
				<?php	}	?>
			</select>
		<?php }?>			
			
			
			
		</td> 
		<td align="right"><strong>Monto Total <font color='#B70000'>(*)</font>: </strong></td>
		<td >
			<input name="txtmontocredito" type="text" style="width: 140px;" id="txtmontocredito" onkeypress="Solo_Numero();">
		</td>
	</tr>	
	
	
	<tr> 	
			<td style='width:200px;'>Por Empresa: <font color='#B70000'>(*)</td>
			<td style="width: 120px;" align="left" colspan="5">
				<select name="txtEmpresa"	id="txtEmpresa"style="width: 460px;">
					<OPTION VALUE =0 >COOPERATIVA ELECTRON 465 RL.</option>
					<OPTION VALUE =1 >GRUPO ELECTRON 465 C.A</option>					
				</select>
			</td>		
	</tr>	
	<tr>
		<td class="formulario">Linaje: <font color='#B70000'>(*)</td>
		<td style="width: 120px;" align="left" colspan="5">
			<select name="txtCobrado"	id="txtCobrado" style="width: 460px;" onchange="verifica_credinfo();">
				<option>----------</option>
				<option>NOMINA</option>
				<option>BICENTENARIO</option>
				<option>BOD</option>
				<option>PROVINCIAL</option>
				<option>VENEZUELA</option>
				<option>BANESCO</option> 
				<option disable=disabled>INDUSTRIAL</option>
				<option>CAMARA MERCANTIL</option>
				<option>CREDINFO</option>
				<option>INVERCRESA</option>
				<option>FONDO COMUN</option>
				<option>BANFAN</option>
				<option>100% BANCO COMERCIAL</option>
				<option>DOMICILIACION POR OFICINA</option>
				<option >SOFITASA</option> 
				<option >DEL SUR</option>
				<option>BANCO CENTRAL DE VENEZUELA</option>
				
				<option >CARONI</option>
				<?php if($Nivel == 0 || $Nivel ==3 || $Nivel == 5 || $Nivel == 9){?>
					
					
					<option >CARIBE</option>
				<?php }?>		
				<option>MERCANTIL</option>
				<option>TRANSFERENCIA</option>
				<option>COTERO</option>
				<option disabled="true">INTER-BANCARIO</option>
			</select>
		</td>
		</tr>		
	
	
	
	<tr>
		<td class="formulario">Nomina <font color='#B70000'>(*)</font>:</td>
		<td style="width: 120px;" align="left" colspan="5">
			<select name="txtNominaProcedencia"	id="txtNominaProcedencia" style="width: 460px;" >
				<option>----------</option>
				<?php
				  echo $lista;
				?>		
			</select>
		</td>
	</tr>
	

	<tr>
		<td>N&uacute;m. de cuotas <font color='#B70000'>(*)</font>:</td>
		<td align="left" style="width:60px">
		<select name="txtNumeroCuotas"	id="txtNumeroCuotas" style="width: 50px;" onblur="Calcular_Monto();" onchange="//valida_motivo();">
			<?php 	for($i = 1; $i <= 100; $i++){	?>
			<option value='<?php echo $i ?>'><?php echo $i ?></option>
			<?php	}	?>
		</select>
		</td>
				<td colspan=3 align="right">Periocidad a efectuar los descuentos <font color='#B70000'>(*)</font>:</td>
		<td align="left" class="formulario">
			<select name="txtNominaPeriocidad"	id="txtNominaPeriocidad"style="width: 140px;" >
			<option value=4>MENSUAL</option>
			<option value=9>MENSUAL- BONOEXPRESS</option>
			<option value=0>SEMANAL</option>
			<option value=1 disabled=disabled>CATORCENAL</option>
			<option value=2>QUINCENAL 15 - 30</option>
			<option value=3 >QUINCENAL 10 - 25</option>
			<option value=5 >Mensual - 15</option>
			<option value=6 >Mensual - 30</option>
			<option value=7 disabled=disabled>-- ANUAL --</option>
			<option value=8 disabled=disabled>MENSUAL-10</option>
			<option value=9>MENSUAL-25</option>
			
			
			</select>
		</td>
	</tr>
			<tr>
		<td><strong>Monto Cuota <font color='#B70000'>(*)</font> : </strong></td>
		<td align="left" colspan="5">
			<input  name="txtMontoCuota" id="txtMontoCuota" style="width: 200px;" disabled="disabled">
		</td>
		</tr>			
		<tr>
			<td style="width:150px">Inicio del Descuento <font color='#B70000'>(*)</font> :</td>
			<td align="left"  style="width:210px" colspan=3>	
			<select name="txtDiaDescuento" id="txtDiaDescuento" style="width: 55px;"onchange="Verifica_Fecha2(frmRegistrar,<?php echo $Nivel?>);">
				<option value=0>Dia:</option>
				<?php 	for($i = 1; $i <= 31; $i++){		?>
				<option value='<?php echo $i ?>'><?php echo $i ?></option>
				<?php }	?>
			</select>
			<select name="txtMesDescuento"	id="txtMesDescuento" style="width: 55px;" onchange="Verifica_Fecha2(frmRegistrar,<?php echo $Nivel?>);">
				<option value=0>Mes:</option>
				<?php 	for($i = 1; $i <= 12; $i++){		?>
				<option value='<?php echo $i ?>'><?php echo $i ?></option>
				<?php	}	?>
			</select>
			<select name="txtAnoDescuento"	id="txtAnoDescuento" style="width: 60px;" onblur="Calcular_Fin_Descuento();" onchange="Verifica_Fecha2(frmRegistrar,<?php echo $Nivel?>);">
				<option value=0>A&ntilde;o:</option>
				<?php 	for($i = 2010; $i <= 2030; $i++){		?>
				<option value='<?php echo $i ?>'><?php echo $i ?></option>
				
				<?php	}	?>
			</select>
			</td>
			<td align="right">&nbsp;&nbsp;<strong>Contrato <font color='#B70000'>(*)</font></strong>:</td>
			<td align="left" class="formulario">
				<select name="txtFormaContrato"	id="txtFormaContrato" style="width: 140px;" onchange="fecha_especial();//tipo_contrato();">
				<option value=0>UNICO</option>
				<option value=7>COSECHAEXPRESS</option>
				<option value=6>UNICO - PRONTO PAGO</option>							
				<option value=1>AGUINALDOS - CUOTA ESPECIAL</option>
				<option value=2>VACACIONES - CUOTA ESPECIAL</option>
                    <option value=11>TARJETA-ALIMENTACION</option>
				<option value=3 disabled="disabled" >CUOTA EXTRAORDINARIA</option>
				<option value=4 disabled="disabled" >UNICO - EXTRA</option>
				<option value=5 disabled="disabled" >ESPECIAL - EXTRA</option>
				</select>
			</td>	
		</tr>
		<tr>
			<td style="width:150px">Fin del Descuento <font color='#B70000'>(*)</font> :</td>
			<td align="left"  style="width:210px" colspan=3>	
			<select name="txtFinDiaDescuento" id="txtFinDiaDescuento" style="width: 55px;">
				<option value=0>Dia:</option>
				<?php 	for($i = 1; $i <= 31; $i++){		?>
				<option value='<?php echo $i ?>'><?php echo $i ?></option>
				<?php	}	?>
			</select>
			<select name="txtFinMesDescuento"	id="txtFinMesDescuento" style="width: 55px;">
				<option value=0>Mes:</option>
				<?php 	for($i = 1; $i <= 12; $i++){		?>
				<option value='<?php echo $i ?>'><?php echo $i ?></option>
				<?php	}	?>
			</select>
			<select name="txtFinAnoDescuento"	id="txtFinAnoDescuento" style="width: 60px;">
				<option value=0>A&ntilde;o:</option>
				<?php 	for($i = 2022; $i <= 2028; $i++){ 	?>
				<option value='<?php echo $i ?>'><?php echo $i ?></option>
				<?php 	}	?>
			</select>
			</td>
			<td align="right">&nbsp;&nbsp;&nbsp;</td>
			<td align="left" class="formulario">&nbsp;
			</td>	
		</tr>		
		<tr>
			<td><strong>N&uacute;m. Factura <font color='#B70000'>(*)</font> : </strong></td>
		<td align="left" colspan=5>
			<input  name="txtNumero_Factura" id="txtNumero_Factura" style="width: 200px;"  value="" onblur="BFactura();">
			<img src='<?php echo __IMG__?>cargando_tab.gif' style="margin: 0px 0 0px 0; padding: 0px 0px 0px 0px; " id="img_busca_factura" >
		</td>
		</tr>
	</table>	
	
 		<h2>Datos del Convenio...</h2><br>
		<table  style="width:620px" border="0" cellspacing="3" cellpadding="0" >
		<tr>
		<td  align="left">Forma de Pago</td>
		<td align="left" colspan="5">
			<select name="txtTipoPago"	id="txtTipoPago" style="width: 200px;" onchange="verifica_tipo();">
					<option value='5-DOM'>DOMICILIACION</option>
					<option value='6-TRF'>TRANSFERENCIA</option>
					<option value='6-PRV'>VOUCHER - PROVINCIAL</option>
					<option value='6-BIC'>VOUCHER - BICENENARIO</option>
					<option value='6-BFC'>VOUCHER - BFC</option>
					<option value='5-VBI'>DOMICILIACION - VOUCHER BICENTENARIO</option>
					<option value='6-COT'>COTERO</option>
			</select>
		</td>
		
		</tr>
		 <tr style='display:none; ' id='fila_boucher'>
		 	<td >Cantidad V.: <font color='#B70000'>(*)</td>
		 	<td colspan="3" style="width: 200px;" >
				<input type="text" name="txtCantBoucher"	id="txtCantBoucher"style="width: 190px;" onchange="Fecha_Boucher();">
			</td>
		 	<td >Fecha V.: <font color='#B70000'>(*)</td>
		 	<td colspan="3">
				<select name="lstFechaBoucher"	id="lstFechaBoucher"style="width: 100%;" ondblclick="quitar();">
										
				</select>
			</td>
		</tr>
		
		<tr style='display:none; ' id='fila_boucher3'>
			<td>N&uacute;mero Voucher: <font color='#B70000'>(*)</td>
			<td colspan="3"><input type="text" name="txtBoucher"	id="txtBoucher"style="width: 190px;">
			<td style='width:120px;'>Monto V.: <font color='#B70000'>(*)</td>
			<td colspan=""><input type="text" name="txtMontoBoucher"	id="txtMontoBoucher"style="width: 60px;">
			
			<a href="#" onclick="Agrega_Boucher();" id="btnAgregarBoucher">Agregar</a></td>		
		</tr>
		<tr style='display:none; ' id='fila_boucher2'> 	
			<td >Agregados: <font color='#B70000'>(*)</td>
			<td colspan="5">
				<select name="lstBoucher"	id="lstBoucher"style="width: 460;height: 100px;" multiple="multiple" ondblclick="quitar();">
										
				</select>
			</td>
					
		</tr>
		<tr>
		<td style='width:200px;'>Motivo o Concepto:</td>
		<td align="left" colspan="5">
			<select name="txtMotivo"	id="txtMotivo"style="width: 460px;" onchange="">
			<option value=0>----------------------</option>
			<option value=1>-- CREDITO PROPIO (MOTO,ARTEFACTO....) --</option>
			<option value=2>-- PRESTAMO --</option>
			<option value=3>-- CREDITO PROPIO Y PRESTAMO ) --</option>
			<option value=4 disabled="disabled">-- POR FIRMA ( AUTORIZACION ) --</option>
			<option value=5 disabled="disabled">-- CREDITO ALIMENTICIO --</option>
			<option value=6 disabled="disabled">-- CREDICOMPRA --</option>			
			</select>
		</td>
		</tr>				
		<tr>
		<td  align="left">Condici&oacute;n</td>
		<td align="left" colspan="3">
			<select name="txtCondicion"	id="txtCondicion" style="width: 200px;" onchange="verifica_condicion();">
					<option value='4'>----------</option>
					<option value='0'>DEPOSITO</option>
					<option value='1' disabled="disabled">TRANSFERENCIA</option>			
					<option value='3' disabled="disabled">CHEQUE</option> 
					<option value='5' >EQUIPO O ARTEFACTO</option>
					<option value='6' disabled="disabled">PENDIENTE POR AUTORIZAR</option>
					<option value='7'>MOTO</option>			
			</select>
		</td>
		<td># Cheque :
		</td>
		<td>
				<input  name="txtNumeroCondicion" id="txtNumeroCondicion"style="width: 140px;" >
		</td>
		</tr>
		<tr>
			<td class="formulario">Fecha Cheque :</td>		
			<td align="left"  style="width:210px" colspan="3">
				<select name="txtDiaO" id="txtDiaO"style="width: 50px;" >
					<option value=0>D&iacute;a</option>
						<?php for($i = 1; $i <= 31; $i++){	?>
				  <option value='<?php echo $i ?>'><?php echo $i ?></option>
						<?php	}	?>
				</select>
				<select name="txtMesO"	id="txtMesO" style="width: 50px;" >
					<option value=0>Mes</option>
						<?php 	for($i = 1; $i <= 12; $i++){		?>
					<option value='<?php echo $i ?>'><?php echo $i ?></option>
						<?php	}	?>
				</select>
				<select name="txtAnoO"	id="txtAnoO" style="width: 60px;" >
					<option value=0>A&ntilde;o</option>
						<?php for($i = 2010; $i <= 2015; $i++){	?>
					<option value='<?php echo $i ?>'><?php echo $i ?></option>
						<?php	}	?>
				</select>
				
			</td>
			<td class="formulario">
				Monto: 
			</td>
			<td>
				<input  name="txtMontoCheque" id="txtMontoCheque" style="width: 140px;" >
			</td>
			</tr>	
			
	<tr>
		<td class="formulario">
			# Cheque 2: 
		</td>
		<td align="left" colspan="3">
			<input  name="txtNCheque2" id="txtNCheque2" style="width: 210px;" readonly = "true" >
		</td>
		<td class="formulario">
			Monto 2: 
		</td>
		<td>
			<input  name="txtMontoCheque2" id="txtMontoCheque2" style="width: 140px;" >
		</td>
	</tr>
	<tr>
		<td style='width:200px;' valign="top">Observaciones :</td>
		<td style="width: 120px;" align="left" colspan="5">
			<textarea   rows="5" style="width: 460px; height: 60px" name="txtObservaciones"	id="txtObservaciones"></textarea>
		</td>
	</tr>
			
	</table>
</form>
