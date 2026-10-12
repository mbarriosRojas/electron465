<center>
	<h2>Lista de Dependientes</h2>
	<table style="width: 100%" border=0>
		<tr>
			<td style="width: 140px;">Oficina: </td>
    	<td colspan="2" >
    		<select name="txtOficinaContrato"	id="txtOficinaContrato" style="width: 400px;">
    			<option value="">TODAS</option>
    			<?php echo $Listar_Ubicacion; ?>
			</select>
		</td>
		</tr>
		<tr>

			<td style="width: 140px;">Usuarios Dependiente: </td>
    	<td colspan="2" >
    		<select name="txtUsuarioContrato"	id="txtUsuarioContrato" style="width: 400px;">
			</select>
		</td>
		</tr>
		<tr>
			<td style="width: 140px;">Estatus Usuario: </td>
			<td><select name="txtUsuarioEstatus"	id="txtUsuarioEstatus" style="width: 400px;">
				<option value = 1>ACTIVOS</option>
				<option value = 0>TODOS</option>    			
			</select>
			</td>
		</tr>
		<tr>
		<td><label for="from">Desde: </label></td><td colspan="3">
		<div class="demo">
			<input type="text" id="fecha_desde_cu" name="fecha_desde" />
			&nbsp;&nbsp;<label for="to">Hasta: </label>&nbsp;&nbsp;
			<input type="text" id="fecha_hasta_cu" name="fecha_hasta" />
		</div></td>
	</tr>
	</table>
</center>