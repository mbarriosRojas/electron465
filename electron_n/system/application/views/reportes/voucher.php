<table>
	<tr>
		<td style="width: 140px;">Modalidad:</td>
		<td>
		<select name="lstModa" id="lstModa"  style="width: 400px;" >
			<option value=0>VOUCHER</option>
			<option value=1>TRANSFERENCIA</option>
		</select></td>
	</tr>
	<tr>
		<td style="width: 140px;">Estatus:</td>
		<td>
		<select name="lstEstatus_v" id="lstEstatus_v"  style="width: 400px;" >
			<option value=''>TODOS</option>
			<option value=0>Entregado Al Cliente</option>
			<option value=1>Pagados</option>
			<option value=2>Trasladados</option>
			<option value=3>Pago Por Domiciliacion</option>
			<option value=5>No Aplica</option>
			<option value=6>No Aplica(EXONERADO)</option>
		</select></td>
	</tr>
	<tr>
		<td><label for="from">Desde: </label></td><td colspan="3">
		<div class="demo">
			<input type="text" id="fecha_desde_v" name="fecha_desde" />
			&nbsp;&nbsp;<label for="to">Hasta: </label>&nbsp;&nbsp;
			<input type="text" id="fecha_hasta_v" name="fecha_hasta" />
		</div></td>
	</tr>
	<tr>
		<td style="width: 140px;">Banco Voucher:</td>
		<td>
		<select name="lstBanco_v" id="lstBanco_v"  style="width: 400px;" >
			<option value=''>TODOS</option>
			<option value='BFC'>BFC</option>
			<option value='BIC'>BICENTENARIO</option>
			<option value='PRV'>PROVINCIAL</option>
		</select></td>
	</tr>
	
</table>