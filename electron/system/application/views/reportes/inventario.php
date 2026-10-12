<table cellpadding="3" cellspacing="3" border=0>
	<tr>
		<td >Estatus :</td>
		<td>
		<select name="esta_inven"	id="esta_inven"  style="width: 400px;">
			<option value=''>TODOS</option>
			<option value=0>ALMACEN</option>
			<option value=1>OFICINA</option>
			<option value=2>ENTREGADO</option>
		</select></td>
	</tr>
	<?php if($Nivel != 5){ ?>
	<tr>
		<td style="width: 140px;">Ubicación: </td>
		<td colspan="2" ><select name="txtDependencia_inv"	id="txtDependencia_inv" style="width: 400px;">

		</select></td>
	</tr>
	<?php } ?>
</table>