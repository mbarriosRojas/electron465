<table cellpadding="3" cellspacing="3" border=0>
	<?php if($Nivel != 5){ ?>
	<tr>
		<td style="width: 140px;">Ubicación: </td>
		<td colspan="2" ><select name="txtDependencia_contado"	id="txtDependencia_contado" style="width: 400px;">

		</select></td>
	</tr>
	<?php } ?>
	<tr>
		<td><label for="from">Desde: </label></td><td colspan="3">
		<div class="demo">
			<input type="text" id="desde_contado" name="desde_contado" />
			&nbsp;&nbsp;<label for="to">Hasta: </label>&nbsp;&nbsp;
			<input type="text" id="hasta_contado" name="hasta_contado" />
		</div></td>
	</tr>
</table>