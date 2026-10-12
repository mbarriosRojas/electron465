<table cellpadding="3" cellspacing="3" border=0>
	<tr>
		<td style="width: 140px;">Ubicación: </td>
		<td colspan="2" >
		<select name="txtUbica_Ncli"	id="txtUbica_Ncli" style="width: 400px;"></select></td>
	</tr>
	<tr>
		<td style="width: 140px;">Mes Solicitud: </td>
		<td colspan="2" >
		<select name="txtAno_Ncli"	id="txtAno_Ncli" style="width: 80px;">
			<?php
			for($i=2012;$i <= date("Y");$i++){
				echo "<option value=$i>$i</option>";
			} 
			
			?>
		</select>
		<select name="txtMes_Ncli"	id="txtMes_Ncli" style="width: 60px;">
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