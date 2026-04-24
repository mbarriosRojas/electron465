
<br>
<div id="datos" style="width: 100%;"></div>
<div id="formulario"  >
	<br />
	<br>
	<h2>Datos Factura</h2>
	<table style="width: 100%;" border=0>
		<tr>
			<td width="50">Cedula/Rif:</td><td>
			<input type="text" id="cedula" onblur="consultar_clientes();" style="width: 100%;"/>
			</td>
			<td width="50">Factura:</td><td>
			<input type="text" id="factura" style="width: 100%;" />
			</td>	
		</tr>
		<tr>
			<td width="100%">Entregado A:</td><td>
			<input type="text" id="cliente" style="width: 100%;" disabled="disabled"/>
			</td>
			<td>Fecha:</td>
			<td>
			<input type="text" id='fecha' style="width: 90%;"/>
			</td>
		</tr>
		<tr>
			<td width="100%">Domicilio Fiscal:</td><td>
			<input type="text" id="dom" style="width: 100%;" />
			</td>
			<td>Telefono:</td>
			<td>
			<input type="text" id='tel' style="width: 90%;"/>
			</td>
		</tr>
		<tr>
			<td width="100%">Monto:</td><td>
			<input type="text" id="monto" style="width: 100%;" />
			</td>
			
		</tr>
		<tr>
			<td  valign="top" width="100"> Detalle: </td>
			<td align="left" colspan="5"><textarea id='detalle' style = 'height: 150px;width: 100%'></textarea>
		</tr>
		<tr>
			<td  valign="top" width="100"> Forma De Pago: </td>
			<td align="left" colspan="5"><textarea id='fp' style = 'height: 50px;width: 100%'></textarea>
		</tr>
	</table>

	<table style="width: 100%;" border=0>
		
		
		<tr>
			<td colspan="5" align="center">
			<input type="button" onclick="Guardar()" value="Guardar"/>
			</td>
		</tr>
	</table>
<hr>
</div>
