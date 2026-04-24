<table width="680" border="0" cellspacing="3" cellpadding="0">	
	<tr>
		<td style="width: 140px;">Nombre:</td>
		<td>
			<select name="lstBanco" id="lstBanco"  style="width: 230px;" >
				<?php echo $Listar_Banco;?>
			</select>
		</td>
		<td style="width: 140px;" align="right">Ubicaci&oacute;n:</td>
		<td>	
			<select name="lstUbicacion" id="lstUbicacion"  style="width: 230px;" >
				<?php echo $Listar_Ubicacion;?>
			</select>
		</td>
	</tr>
	<tr>
		<td style="width: 140px;">Fecha :</td>
		<td>	
			<input type="text" name="fechaChequera" id="fechaChequera" style="width:188px" value=''/>
		</td>
		<td style="width: 140px;" align="right" >Cantidad :</td>
		<td>	
			<input type="text" name="txtCantidad_Chequera" id="txtCantidad_Chequera" style="width:230px" value=''/>
		</td>
	</tr>
	<tr>
		<td style="width: 140px;">Numero Cuenta:</td>
		<td >	
			<input type="text" name="txtNumeroCuenta" id="txtNumeroCuenta" style="width:230px" value=''/>
		</td>
		<td style="width: 140px;">Numero Chequera:</td>
		<td >	
			<input type="text" name="txtnchequera" id="txtnchequera" style="width:230px" value=''/>
		</td>
	</tr>
	<tr>
		<td valign="top"> Descripci&oacute;n:</td>
		<td colspan="3" valign="top">
			<textarea name="txtdescripcion_Chequera"  style="width: 100%;height:130px; " id="txtdescripcion_Chequera" rows="5"></textarea>
		</td>
	</tr>
	<tr>
		<td valign="top">Cheques : </td>
		<td valign="top">				
			<table>
				<tr>
					<td valign="top"><input type="text" name="txtCheque"  id="txtCheque" style="width: 180px" /></td>
					<td valign="top"><input type="button" onClick="AgregarCheque();" id="Incluir_Cheque" value="+ Incluir N&uacute;mero" style="width=40px;" /></td>
				</tr>
				<tr>
					<td></td>				
					<td valign="top"><input type="button" onClick="AgregarSerie();" id="Incluir_Serie" value="+ Incluir Serie" style="width=40px;" /></td>
				</tr>				
			</table>				
		</td>
		<td valign="top"  align="right" colspan="2">
			<select multiple name="lstCheques" id="lstCheques"  style="width: 230px; height:75px" ondblclick="Eliminar_Cheque();" ></select>				
		</td>
	</tr>
	<tr><td colspan="4" align="right"><button onclick="Guarda_Chequera();">Guardar Lista de Chueques</button></td></tr>
	<tr><td colspan="4" align="left"><div id="Cheques" style="width:100%; Height:300px"></div></td></tr>
</table>	