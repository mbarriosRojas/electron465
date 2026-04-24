<table style="width:630px" border="0" cellspacing="3" cellpadding="0" >
	<tr>
		<td style="width: 220px;" >C&eacute;dula (*):</td>
		<td style="width: 185px;" align="left" colspan="5">
		<select name="txtNacionalidad" id="txtNacionalidad" style="width: 50px;">
			<option>V-</option>
			<option>E-</option>
		</select>
		<input name="txtCedula" type="text" style="width: 125px;" id="txtCedula" maxlength="10" onblur="consultar_clientes();">
		</td>
	</tr>
	<tr>
		<td> Nombres: (*)</td>
		<td align="left"  colspan=5 >
		<input name="txtNombre1"	type="text" id="txtNombre1" style="width: 180px;">
		<input name="txtNombre2"	type="text" id="txtNombre2" style="width: 180px;">
		</td>
	</tr>
	<tr>
		<td > Apellidos:(*)</td>
		<td align="left" colspan=5 >
		<input name="txtApellido1" type="text" id="txtApellido1" style="width: 180px;">
		<input name="txtApellido2" type="text" id="txtApellido2" style="width: 180px;">
		</td>
	</tr>
	<tr>
		<td colspan="6"><h2>
		<br>
		<center>
			Datos de la factura...
		</center>
		<br>
		</h2></td>
	</tr>
	<tr>
		<td><strong>N&uacute;m. Factura <font color='#B70000'>(*)</font> : </strong></td>
		<td align="left" colspan=3>
		<input  name="txtNumero_Factura" id="txtNumero_Factura" style="width: 180px;"  value="" onblur="BFactura();">
		<img src='<?php echo __IMG__?>cargando_tab.gif' style="margin: 0px 0 0px 0; padding: 0px 0px 0px 0px; " id="img_busca_factura" ></td>
	</tr>
	<tr id='fila_boucher'>
		<td >Cantidad V.: <font color='#B70000'>(*)</td>
		<td colspan="3" style="width: 180px;" >
		<input type="text" name="txtCantBoucher"	id="txtCantBoucher"style="width: 180px;" onchange="Fecha_Boucher();">
		</td>
		<td >Fecha V.: <font color='#B70000'>(*)</td>
		<td colspan="0"><select name="lstFechaBoucher"	id="lstFechaBoucher"style="width: 100%;" ondblclick="quitar();">

		</select></td>
	</tr>
	<tr id='fila_boucher3'>
		<td>N&uacute;mero Voucher: <font color='#B70000'>(*)</td>
		<td colspan="3">
		<input type="text" name="txtBoucher"	id="txtBoucher"style="width: 180px;">
		<td style='width:120px;'>Monto V.: <font color='#B70000'>(*)</td>
		<td colspan="">
		<input type="text" name="txtMontoBoucher"	id="txtMontoBoucher"style="width: 60px;">
		<a href="#" onclick="Agrega_Boucher();" id="btnAgregarBoucher">Agregar</a></td>
	</tr>
	<tr id='fila_boucher2'>
		<td >Agregados: <font color='#B70000'>(*)</td>
		<td colspan="5"><select name="lstBoucher"	id="lstBoucher"style="width: 460;height: 100px;" multiple="multiple" ondblclick="quitar();">

		</select></td>

	</tr>

</table>

