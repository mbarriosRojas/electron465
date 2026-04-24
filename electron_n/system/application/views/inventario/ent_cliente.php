<br>
<h2>Buscar Producto</h2>
<input type="hidden" id='t_venta' value="credito" />
<table style="width: 100%;" border="0" cellspacing="3" cellpadding="0">
	<tr>
		<td valign="top"> Descripci&oacute;n:</td>
		<td valign="top" style="width: 550px;"><textarea name="txtDescripcion"  style="width: 100%;" id="txtDescripcion"class="inputxt" rows="2" cols="5"></textarea>
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
			<td width="100">Modelo:</td><td width="150">
			<input type="text" id="modelo" style="width: 100%;" disabled="disabled"/>
			</td>
			
		</tr>
		<tr>
			<td width="100">Descripción:</td><td colspan="3" style="width: 100%;">
			<textarea id='nombre' rows="5" style="width: 100%"  disabled="disabled"></textarea>
			</td>
			
		</tr>
		<tr>
			<td width="50">Factura:</td><td>
			<input type="text" id="factura" style="width: 100%;" onblur="BFactura();"/>
			</td>
			<td width="50">Cedula:</td><td>
			<input type="text" id="cedula" disabled="disabled" style="width: 100%;"/>
			</td>
			
		</tr>
		<tr>
			<td width="100%">Entregado A:</td><td>
			<input type="text" id="cliente" style="width: 100%;" disabled="disabled"/>
			</td>
			<td>Fecha:</td>
			<td>
			<input type="text" id='fecha' style="width: 90%;" value="<?php echo date("Y-m-d") ?>" disabled=disabled />
			</td>
		</tr>
	</table>
	<br><br><br>
	<h2>Agregar Seriales</h2>
	<table style="width: 100%;" border=0>
		<tr style='width:100%;'>
			<td width="50">Seriales:</td>
			<td align="left" width="100">
			<select name="txtSeriales"	id="txtSeriales" style="width: 100%; ">
				<option value="----------">----------------------</option>
			</select></td>
			<td><a name="agregar" onClick="Ag_Seriales();">Agregar</a></td>
		</tr>
		<tr>
			<td  valign="top" width="100"> Uno o M&oacute;s Seriales: </td>
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
