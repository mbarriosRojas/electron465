<table style="width:620px" border="0" cellspacing="3" cellpadding="0" class="formulario">
	<!-- 		<tr>
	<td><strong>N&uacute;m. Recibo <font color='#B70000'>(*)</font> : </strong></td>
	<td align="left" colspan=5>
	<input  name="txtNumero_Recibo" id="txtNumero_Recibo"  onblur="consultar_recibo();"style="width: 180px;" >
	</td>
	</tr>-->
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
		<td style='width:120px;'>Nombre o Razon social:</td>
		<td style="width: 120px;" align="left" colspan="5">
		<input name="txtNombre" type="text" style="width: 100%;" id="txtNombre">
		</td>
	</tr>
	<tr>
		<td style='width:120px;'>C&eacute;dula o Rif:</td>
		<td>
		<select id="txtTipo" name="txtTipo" style="width: 50px;">
			<option value='V-'>V-</option>
			<option value='E-'>E-</option>
			<option value='J-'>J-</option>
		</select></td>
		<td style="width: 190px;" align="left" colspan="5">
		<input name="txtCedula" type="text" style="width: 100%;" id="txtCedula">
		</td>
	</tr>
	<tr>
		<td style='width:120px;' valign="top">Concepto de Pago:</td>
		<td style="width: 400px;" align="left" colspan="5">		<textarea   rows="5" style="width: 100%; height: 60px" name="txtConcepto"	id="txtConcepto"></textarea></td>
	</tr>
	<tr>
		<td style="width: 120px;" >Banco Cheque:</td>
		<td style="width: 180px;" align="left" >
		<select name="txtBanco"	id="txtBanco" style="width: 100%;">
			<option>BICENTENARIO</option>
			<option>BOD</option>
			<option>PROVINCIAL</option>
			<option>VENEZUELA</option>
			<option>BANESCO</option>
			<option>INDUSTRIAL</option>
			<option>MERCANTIL</option>
			<option>CAMARA MERCANTIL</option>
			<option>DEL SUR</option>
			<option>CREDINFO</option>
			<option>INVERCRESA</option>
			<option>FONDO COMUN</option>
			<option>100% BANCO COMERCIAL</option>
			<option>DOMICILIACION POR OFICINA</option>
		</td>
		<td style="width: 120px;text-align: right;" >Chequera Nº:</td>
		<td style="width: 180px;" align="left" >
		<input name="txtChequera" type="text" style="width: 180px;" id="txtChequera" maxlength="11">
		</td>
	</tr>
	<tr>
		<td style="width: 120px;" >Cheque Nº:</td>
		<td style="width: 180px;" align="left" >
		<input name="txtCheque" type="text" style="width: 180px;" id="txtCheque" maxlength="11">
		<td style="width: 120px;text-align: right;"><label for="from">Fecha: </label></td><td >
		<div class="demo">
			<input type="text" id="fecha" name="fecha" />
		</div></td>
	</tr>
	<tr>
		<td>
		<div id="botones">
			<button onclick="Guardar_Recibo();">
				Guardar
			</button>
		</div></td>
	</tr>
</table>
<br>
<div id="Recibos" style="width:100%; Height:300px"></div>
