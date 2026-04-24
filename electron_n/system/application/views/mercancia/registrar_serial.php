<br>
	<table width="680" border="0" cellspacing="3" cellpadding="0">
		<tr>
		
			<td valign="top"> Descripci&oacute;n u Codigo:</td>
			<td colspan="3" valign="top"><textarea name="txtDescripcionS"  style="width: 500px;" id="txtDescripcionS"class="inputxt" rows="2" cols="5"></textarea><input type="hidden" id='cantidad' name="cantidad" /></td>
			<td><button onclick="Consultar_Mercancia();" id="Buscar">Buscar</button></td>
		</tr>
		<tr>
			<td>Codigo:</td>
			<td>
				<input type="text" 	name="txtCodigoS" id="txtCodigoS" style="width: 188px;" disabled="true"/>
			</td>			
		</tr>
		<tr>
			<td>Marca:</td>
			<td>
				<input type="text" 	name="txtMarcaS" id="txtMarcaS" style="width: 188px;" disabled="true"/>
				
			</td>
			<td>Modelo:</td>
			<td>
				<input type="text" 	name="txtModeloS" id="txtModeloS" style="width: 188px;" disabled="true" />
			</td>
		</tr>
		<tr>
			<td style="width:80px;">Proveedor :</td>
			<td>	
				<input type="text" name="txtProveedorS" id="txtProveedorS" style="width:188px" />
				
			</td>
			<td style="width: 80px;" >Almacen</td>
			<td >	
				<select name="lstUbicacionS" id="lstUbicacionS"  style="width: 188px;" " >
				<?php echo $Listar_Ubicacion;?>
				</select>
			</td>
		</tr>		
		<tr>
			<td>Precio Compra: </td>
			<td>
				<input name="txtPrecioCompraS" type="text" id="txtPrecioCompraS" style="width: 100px;"/>
			</td> 
			<td>Precio Venta: </td>
			<td>
				<input name="txtPrecioVentaS" type="text" id="txtPrecioVentaS" style="width: 100px;"/>
			</td> 
		</tr
		<tr>
			<td>Serial:</td>
			<td>
				<input type="text" 	name="txtSerialS" id="txtSerialS" align="left" style="width: 188px;" />
			</td>		
			<td>Cantidad: </td>
			<td>
				<input name="txtCantidadS" type="text" id="txtCantidadS"  style="width: 100px;"/>
			</td>
		</tr>	
	</table>
	<br>
	<center><button onclick="Guardar_Serial();" id="Enviar">Guardar Información</button></center>
</form>