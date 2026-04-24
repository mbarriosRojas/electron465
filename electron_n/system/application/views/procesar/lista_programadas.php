<table style="width:100%" border="0" cellspacing="0" cellpadding="0" class="formulario">
	<tr>
		<td style="width:100px">Observaci&oacute;n:</td>
		<td align="left" class="formulario" colspan=5>
			<input  name="txtDescripcion" id="txtDescripcion" style="width: 100%;" >
		</td>
	</tr>
	<tr>
		<td class="formulario" >Fecha Cobro:</td>
		<td align="left" class="formulario"    style="width:200px;">
		<select name="txtDiaCobro" id="txtDiaCobro" style="width: 60px;">
			<?php 
				for($i = 1; $i <= 31; $i++){
			?>
				<option value='<?php echo $i ?>'><?php echo $i ?></option>	
			<?php 
				}
			?>
		</select>
		<select name="txtMesCobro"	id="txtMesCobro"  style="width: 60px;">
			<?php 
					for($i = 1; $i <= 12; $i++){
			?>
				<option value='<?php echo $i ?>'><?php echo $i ?></option>
			<?php 
				}
			?>
		</select>
		
		<select name="txtAnoCobro"	id="txtAnoCobro"  style="width: 70px;">
			<?php 
					for($i = 2015; $i <= 2016; $i++){
			?>
				<option value='<?php echo $i ?>'><?php echo $i ?></option>
			<?php 
				}
			?>
		</select>
		</td>

		<td class="formulario">Monto</td>
		<td >
			<input name="txtmontocobrado" type="text" style="width: 200px;" id="txtmontocobrado" onkeypress="Solo_Numero();">
		</td>
		<td class="formulario">Referencia</td>
		<td >
			<input name="txtReferencia" type="text" style="width: 200px;" id="txtReferencia" >
		</td>
	</tr>	
</table>
<br><br>
<center><h2>HISTORIAL DE COBRANZAS REALIZADAS</h2></center>
<div id="tabla_cobros" style="overflow:auto;height: 70%;"></div>
<input type="hidden" id="txtVoucher" name="txtVoucher" >
<input type="hidden" id="txtFactura" name="txtFactura" >
