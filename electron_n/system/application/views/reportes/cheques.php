	<table>
<tr>
		<td style="width: 140px;">Nombre Banco:</td>
		<td>
			<select name="lstBanco" id="lstBanco"  style="width: 400px;" onblur="Cargar_Numeros_Cuenta();">
				<?php echo $Listar_Banco;?>
			</select>
		</td>

	</tr>

		
	<tr>
		<td style="width: 140px;">Numero Banco:</td>
		<td>
			<select name="lstNBanco" id="lstNBanco"  style="width: 400px;" >
				
				<OPTION VALUE =9 >------------</option>
			</select>
		</td>

	</tr>
	<tr>
		<td style="width: 140px;" >Ubicaci&oacute;n (Oficina):</td>
		<td>	
			<select name="lstUbicacion" id="lstUbicacion"  style="width: 400px;" >
				<?php echo $Listar_Ubicacion;?>
			</select>
		</td>
	</tr>
		<tr>
		<td style="width: 140px;" >Estatus:</td>
		<td>	
			<select name="lstEstatus" id="lstEstatus"  style="width: 400px;" >
				<OPTION VALUE =0 >DISPONIBLES</option>
				<OPTION VALUE =1 >ENTREGADOS</option>
				<OPTION VALUE =2 >COBRADO</option>
				<OPTION VALUE =3 >ANULADOS</option>
				<OPTION VALUE ="TODOS" >TODOS</option>
			</select>
		</td>
	</tr>

		<tr>
		<td style="width: 140px;" >Numero Chequera:</td>
		<td>	
			<select name="txtnchequera" id="txtnchequera"  style="width: 200px;" >
				<OPTION VALUE ="TODOS" >TODOS</option>
				<?php
					for($i=1;$i<150 ; $i++){
						/*if($i<10){
							echo '<OPTION VALUE ="' . $i . '" >0' . $i . '</option>';
						}else{
							echo '<OPTION VALUE ="' . $i . '" >' . $i . '</option>';
						}*/
						echo '<OPTION VALUE ="' . $i . '" >' . $i . '</option>';	
					}
				?>
			</select>
		</td>
	</tr>

</table>