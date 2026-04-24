<table style="width:620px" border="0" cellspacing="3" cellpadding="0" class="formulario">
	<!-- 		<tr>
	<td><strong>N&uacute;m. Recibo <font color='#B70000'>(*)</font> : </strong></td>
	<td align="left" colspan=5>
	<input  name="txtNumero_Recibo" id="txtNumero_Recibo"  onblur="consultar_recibo();"style="width: 180px;" >
	</td>
	</tr>-->
	<tr>
		<td style='width:120px;'>C&eacute;dula o rif:</td>
		<td>
			<select id="txtTipo" name="txtTipo" style="width: 180px;">
				<option value='V-'>VENEZOLANO</option>
				<option value='E-'>EXTRANJERO</option>
				<option value='J-'>JURIDICO</option>
				<option value='G-'>GUBERNAMENTAL</option>
			</select>
		</td>
		<td style="width: 190px;" align="left" colspan="5">
		<input name="txtCedula" type="text" style="width: 100%;" id="txtCedula" onblur="consultarClientes();" onkeypress="return isNumberKey(event)">
		</td>
	</tr>
	<tr>
		<td style='width:120px;'>Razón Social:</td>
		<td style="width: 120px;" align="left" colspan="5">
		<input name="txtNombre" type="text" style="width: 100%;" id="txtNombre">
		</td>
	</tr>

	<tr>
		<td style="width: 120px;" >
			<div id="opera0" style="display:none">
				Pendiente:
			</div>
		</td>
		<td colspan="3" style="width: 300px;" align="left" >
			<div id="opera1" style="display:none">
				<select name="cmbPendiente"	id="cmbPendiente" style="width: 100%;">
					<option value=0>SELECCIONAR OPERACIONES PENDIENTES</option>
				</select>
			</div>
		</td>		
	</tr>

	<tr>
		<td style="width: 120px;" >Acuse N&uacute;mero:</td>
		<td style="width: 180px;" align="left" >
		<input name="txtCodigo" type="text" style="width: 180px;" id="txtCodigo" disabled="disabled" >
		</td>
		<td align="right"><strong>Monto<font color='#B70000'>(*)</font>: </strong></td>
		<td colspan="3" >
		<input name="txtMonto" type="text" style="width: 100%;" id="txtMonto">
		</td>

	</tr>

	
	<tr>
		<td style='width:120px;' valign="top">Concepto de Pago:</td>
		<td style="width: 400px;" align="left" colspan="5">		<textarea   rows="5" style="width: 100%; height: 60px" name="txtConcepto"	id="txtConcepto"></textarea></td>
	</tr>
	<tr>
		<td style="width: 120px;" >Forma de Pago:</td>
		<td style="width: 180px;" align="left" >
			<select name="txtBanco"	id="txtBanco" style="width: 100%;">
				<option value="EFECTIVO">EFECTIVO</option>
				<option value="TRANSFERENCIA VENEZUELA">TRANSFERENCIA VENEZUELA</option>
				<option value="TRANSFERENCIA BICENTENARIO">TRANSFERENCIA BICENTENARIO</option>
				<option value="BICENTENARIO">BICENTENARIO</option>
				<option value="BOD">BOD</option>
				<option value="PROVINCIAL">PROVINCIAL</option>
				<option value="VENEZUELA">VENEZUELA</option>
				<option value="BANESCO">BANESCO</option>
				<option value="INDUSTRIAL">INDUSTRIAL</option>
				<option value="MERCANTIL">MERCANTIL</option>
				<option value="DEL SUR">DEL SUR</option>
			</select>
		</td>
		<td style="width: 120px;text-align: right;" >Cuenta Nº:</td>
		<td style="width: 180px;" align="left" >
		<input name="txtChequera" type="text" style="width: 180px;" id="txtChequera">
		</td>
	</tr>
	<tr>
		<td style="width: 120px;" >Deposito Nº:</td>
		<td style="width: 180px;" align="left" >
		<input name="txtCheque" type="text" style="width: 180px;" id="txtCheque">
		<td style="text-align: right;"><label for="from">Fecha: </label></td><td >
		<div class="demo">
			<input type="text" id="fecha" name="fecha" style="width: 160px;text-align: right;" />
		</div></td>
	</tr>
	<tr>
		<td style="width: 120px;" >Tipo de Pago:</td>
		<td colspan="3" style="width: 300px;" align="left" >
			<select name="txtPago"	id="txtPago" style="width: 100%;">
				<option value=0>GASTOS VARIOS</option>
				<option value=1>NOTA DE CREDITO</option>
				<option value=2>PAGO DE SERVICIOS</option>			
				<option value=3>PAGO DE PROVEEDORES</option>
				<option value=4>PAGO DE CONDOMINIOS</option>
				<option value=5>PAGO DE TELEFONO</option>
				<option value=6>PAGO DE INTERNET</option>
				<option value=7>PAGO DE HONORARIOS PROFESIONALES</option>
				<option value=8>PAGO DE MERCANCIA</option>
				<option value=9>PAGO DE PRODUCTOS O BIENES</option>
				<option value=10>PAGO DE OTRAS INVERSIONES</option>
			</select>
		</td>		
	</tr>



	<tr>
		<td style="width: 300px;" align="right" colspan="4"><br><br>
		<div id="botones">
			<button onclick="Guardar_Recibo();">
				Salvar Operación
			</button>
			<button onclick="">
				Cancelar Operación
			</button>
		</div></td>
	</tr>
</table>
<br>
<div id="acuse" style="width:100%; Height:300px"></div>
