<table cellpadding="3" cellspacing="3" border=0>
	<tr>
		<td >Estatus :</td>
		<td>
		<select name="SPEsta"	id="SPEsta"  style="width: 400px;">
			<option value=0>Todas</option>
			<option value=1>Sin Pago</option>
		</select></td>
	</tr>
	<?php if($Nivel != 5){ ?>
	<tr>
		<td style="width: 140px;">Ubicación: </td>
		<td colspan="2" >
		<select name="txtDependencia_sp"	id="txtDependencia_sp" style="width: 400px;">
			
		</select></td>
	</tr>
	<?php } ?>
	<tr>
		<td style="width: 140px;">Linaje: </td>
		<td colspan="2" >
		<select name="txtLinaje_sp"	id="txtLinaje_sp" style="width: 400px;">
			
		</select></td>
	</tr>
	<tr>
		<td style="width: 140px;">Mes Solicitud: </td>
		<td colspan="2" >
		<select name="txtAno_sp"	id="txtAno_sp" style="width: 80px;">
			<?php
			for($i=2010;$i <= date("Y");$i++){
				echo "<option value=$i>$i</option>";
			} 
			
			?>
		</select>
		<select name="txtMes_sp"	id="txtMes_sp" style="width: 60px;">
			<?php
			for($i=1;$i <= 12;$i++){
				$m = $i;
				if($i<10) $m = '0'.$i; 
				echo "<option value=$m>$m</option>";
			} 
			
			?>
		</select></td>
	</tr>
</table>