<br>
<h2>Buscar Producto</h2>
<table style="width: 100%;" border="0" cellspacing="3" cellpadding="0">
	<tr>
		<td valign="top"> Descripci&oacute;n:</td>
		<td valign="top" style="width: 550px;"><textarea name="txtDescripcion"  style="width: 100%;" id="txtDescripcion"class="inputxt" rows="2" cols="5"></textarea>
		<input type="hidden" id='cantidad' name="cantidad" />
		<input type="hidden" id='inventario_id' name="inventario_id" />
		<input type="hidden" id='observa' name="observa" />
		</td>
		<td>
		<button onclick="Consultar_Inventario();" id="Buscar">
			Buscar
		</button></td>
	</tr>
</table>
<br>
<br>
<br>
<div id="datos" style="width: 100%;"></div>
<div id="formulario"  >
	<br />
	<br>
	<h2>Datos Entrega</h2>
	<table style="width: 100%;" border=0>
		<tr style="width='100%;'">
			<td width="100">Oficina</td><td width="150">
				<select name="oficina" id="oficina"  style="width: 100%;" onchange="sucursal();" >
				<?php echo $Listar_Sucursal; ?>
				</select>
			<input type="hidden" id="lstUbicacion" name="lstUbicacion" value="" style="width: 100%;"/>
			</td>
			<td width="100">Orden Entrega</td><td>
			<input type="text" id="orden"  style="width: 100%;"/>
			</td>
		</tr>
		<tr>
			<td width="50">Teléfono</td><td>
			<input type="text" id="tlf" style="width: 100%;"/>
			</td>
			<td width="50">Chofer</td><td>
			<input type="text" id="chofer" style="width: 100%;"/>
			</td>
		</tr>
		<tr>
			<td width="50">Placa</td><td>
			<input type="text" id="placa" style="width: 100%;"/>
			</td>
			<td width="50">Hora Salida</td><td>
			<input type="text" id="salida" style="width: 100%;"/>
			</td>
		</tr>
		<tr>
			<td width="50">Destino</td><td>
			<!--<input type="text" id="destino" style="width: 100%;" disabled="disabled"/> -->
			<textarea id='destino' rows="5" style="width: 100%;height: 100px;" disabled="disabled" wrap="hard"></textarea>
			</td>
			<td width="50">Encargado</td><td>
			<input type="text" id="encargado" style="width: 100%;"/>
			</td>
		</tr>
		<tr>
			<td width="50">Vehiculo</td><td>
			<input type="text" id="vehiculo" style="width: 100%;"/>
			</td>
		
			<td>Fecha</td>
			<td>
			<input type="text" id='fecha' style="width: 90%;"/>
			</td>
		</tr>
	</table>
	<br><br><br>
	<h2>Agregar Seriales</h2>
	<table style="width: 100%;" border=0>
		<tr style='width:100%;'>
			<td width="100">Seriales:</td>
			<td align="left" width="85">
			<select name="txtSeriales"	id="txtSeriales" style="width: 100%; ">
				<option value="----------">----------------------</option>
			</select></td>
			<td>Precio Oficina</td>
			<td>
			<input type="text" id='precio_oficina' style="width: 100%; "/>
			</td>
			<td><a name="agregar" onClick="Ag_Seriales();">Agregar</a></td>
		</tr>
		<tr style="">
			<td  valign="top"> Uno o M&oacute;s Seriales: </td>
			<td align="left" colspan="5"><select multiple="multiple" name="lstSeriales"	id="lstSeriales" style="width: 100%;height:100px" ondblclick="Eli_Seriales()">

			</select></td>
		</tr>
		<tr>
			<td colspan="5" align="center">
			<input type="button" onclick="Entregar()" value="Entregar"/>
			</td>
		</tr>
	</table>
<hr>
</div>
