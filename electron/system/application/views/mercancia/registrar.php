<br>
	<table width="680" border="0" cellspacing="3" cellpadding="0">
		<tr>
		
			<td>Codigo:</td>
			<td>
				<input type="text" 	name="txtCodigo" id="txtCodigo" style="width: 188px;" disabled="true"/>
			</td>
			<td>
				<button onclick="GCodigo();">Generar</button>
			</td>			
		</tr>			
		<tr>
			<td valign="top"> Descripci&oacute;n:</td>
			<td colspan="3" valign="top">
				<textarea name="txtDescripcion"  style="width: 500px;" id="txtDescripcion"class="inputxt" rows="2" cols="5"></textarea>
			</td>
		</tr>
		<tr>
			<td>Marca:</td>
			<td>
				<input type="text" 	name="txtMarca" id="txtMarca" style="width: 188px;" />
			</td>
			<td>Modelo:</td>
			<td>
				<input type="text" 	name="txtModelo" id="txtModelo" onblur="BModelo();" style="width: 188px;" />
			</td>
		</tr>
		<tr>
			<td style="width:80px;">Proveedor :</td>
			<td>	
				<input type="text" name="txtProveedor" id="txtProveedor" style="width:188px" />
				
			</td>
			<td style="width: 80px;" >Almacen</td>
			<td >	
				<select name="lstUbicacion" id="lstUbicacion"  style="width: 188px;" " >
				<?php echo $Listar_Ubicacion;?>
				</select>
			</td>
		</tr>		
		<tr>
			<td>Precio Compra: </td>
			<td>
				<input name="txtPrecioCompra" type="text" id="txtPrecioCompra" style="width: 100px;"/>
			</td> 
			<td>Precio Venta: </td>
			<td>
				<input name="txtPrecioVenta" type="text" id="txtPrecioVenta" style="width: 100px;"/>
			</td> 
		</tr
		<tr>
			<td>Serial:</td>
			<td>
				<input type="text" 	name="txtSerial" id="txtSerial" align="left" style="width: 188px;" />
			</td>		
			<td>Cantidad: </td>
			<td>
				<input name="txtCantidad" type="text" id="txtCantidad"  style="width: 100px;"/>
			</td>
		</tr>	
	</table>
	<br>
	<center><button onclick="Guardar_Mercancia();" id="Enviar">Guardar Información</button></center>
</form>