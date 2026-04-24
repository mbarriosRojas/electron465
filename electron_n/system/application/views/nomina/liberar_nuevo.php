
<table cellpadding="3" cellspacing="3" border=0>
	<tr>
		<td> BANCO: </td>
		<td align="left" style="width: 165px;">
		<select	name="txtbanco" id="txtbanco" class="inputxt" style="width: 180px;" >
			<option></option>
			<option>SOFITASA</option>
			<option>BICENTENARIO</option>
			<option>BOD</option>
			<option>PROVINCIAL</option>
			<option>VENEZUELA</option>
			<option>BANESCO</option>
			<option>INDUSTRIAL</option>
			<option>MERCANTIL</option>
			<option>CAMARA MERCANTIL</option>
			<option>EL EXTERIOR</option>
			<option>FONDO COMUN</option>
			<option>DEL SUR</option>
			<option>FEDERAL</option>
			<option>CANARIAS</option>
			<option>CARONI</option>
			<option>CARIBE</option>
			<option>PLAZA</option>
			<option>CENTRAL</option>
			<option>NACIONAL DE CREDITO</option>
			<option>COMERCIO EXTERIOR</option>
			<option>OCCIDENTAL DE DESCUENTO</option>
			<option>100% BANCO COMERCIAL</option>
		</select></td>
	</tr>
	<tr>
		<td colspan="2">
		<div class="demo">
			<label for="from">Desde</label>
			<input type="text" id="fecha_desde" name="fecha_desde" />
			<label for="to">Hasta</label>
			<input type="text" id="fecha_hasta" name="fecha_hasta" />
		</div></td>
	</tr>
	<tr>
		<td rowspan="2" class="formulario" valign="bottom" colspan="2">
		<input type="button" onclick="Consultar();" value="Generar Consulta" style="width:120px; height:43px">
		</td>
	</tr>
</table>
<div id="consulta" >
<table>
	<tr>
		<td><textarea id="txtConsulta" rows="4" cols="50"></textarea></td>
	</tr>
	<tr>
		<td rowspan="2" class="formulario" valign="bottom" >
			<input type="button" onclick="genera_xls('<?php echo __LOCALWWW__ ?>',datos_tabla);" value="Generar xls" style="width:120px; height:43px">
		</td>
	</tr>
</table>
</div>
<br>
<!-- <table id="tabla_principal" class="TGrid"></table><center>
<div id="paginador" class="" >
	
</div></center> -->
<div id="Lista_Resultado"></div>