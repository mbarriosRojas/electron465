<table cellpadding="3" cellspacing="3" border=0>
	<tr>
		<td style="width: 140px;">Vendedor: </td>
		<td colspan="2" >
		<select name="txtVendedor"	id="txtVendedor" style="width: 400px;">
			
		
			<option value="VE255151">Nacional - Nelson Cordoba</option>
			<option value="MER297156">Merida - Alirio Urbina</option>
			<option value="MER307157">Merida - Jesus Vivas</option>
			
			<option value="MAT64151">Maturin - Maribal</option>
						
			<option value="BPO65151">Puerto Ordaz - Carlos Gimenez</option>
			<option value="BL45151">Barquisimeto - Yakelin Davila</option>
			<option value="EVM204153">El Vigia - Auxiliadora Marquina</option>
			
			<option value="MEV45151">El Vigia - Nelly Davila</option>
			<option value="MEV45152">El Vigia - Karen Peña</option>
			<option value="MEV45153">El Vigia - Eleana Hernandez</option>
			<option value="NBM45151">Nueva Bolivia - Mayra Davila</option>
			<option value="SCT75151">San Cristobal - Marilin Hernandez</option>
			<option value="MEC225151">Canagua - Hernan Rivas</option>
			<option value="TRV178151">Valera - Evelin Coromoto Velasquez</option>
			
		</select></td>
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