<br>
<table width="680" border="0" cellspacing="3" cellpadding="0">
	<tr>
		<td valign="top"> Descripci&oacute;n:</td>
		<td colspan="3" valign="top">		<textarea name="txtDescripcion"  style="width: 500px;" id="txtDescripcion"class="inputxt" rows="2" cols="5"></textarea>
<input type="hidden" id='cantidad' name="cantidad" />		</td>
		<td>
		<button onclick="Consultar_Mercancia_Entrega();" id="Buscar">
			Buscar
		</button></td>
	</tr>
</table>
<br>
<br>
<br>
<div id="datos" style=""></div>
<div id="formulario" align="center" style="display: none;">
	<br />
	<table>
		<tr>
			<td width="50">Entregado A</td><td>
			<input type="text" id="entregado_a" />
			</td>
		</tr>
		<tr>
			<td width="50">Fecha</td><td>
			<input type="text" id="fecha" />
			</td>
		</tr>
		<input type="hidden" id='modelo' name="modelo" />
		<input type="hidden" id='descrip' name="descrip" />
		<input type="hidden" id='codigo' name="codigo" />
		
		<tr style="">
		<td style='width:200px;'><div id="divSD">Seriales:</div></td>
		<td align="left" colspan="5" valign="bottom"><div id="divSC " style="float: left">			
			<select name="txtSeriales"	id="txtSeriales" style="width: 380px; height:22px">			
				<option value="----------">----------------------</option>			
			</select></div>
			<div style="height: 16px; padding-top: 5px; padding-left: 5px; background-color:#fff; text-align:center" id="divSA">
				<a name="agregar" onClick="Ag_Seriales();">Agregar</a></div>			
		</td>
		</tr>
		<tr style="">
		<td style='width:200px;' valign="top"><div id="divSMD" >Uno o M&oacute;s Seriales:</div></td>
		<td align="left" colspan="5">			
			<select multiple="multiple" name="lstSeriales"	id="lstSeriales" style="width: 460px;height:100px" ondblclick="Eli_Seriales()">						
						
			</select>
		</td>
		</tr>		
		
		<tr>
			<td colspan="4" align="center ">
			<input type="button" onclick="Entregar()" value="Entregar"/>
			</td>
		</tr>
	</table>

</div>
