<table style="width:100%" border="0" cellspacing="0" cellpadding="0" class="formulario">
	<tr>
	<td colspan="6"></td>
	</tr>
	<tr>
		<td style="width:100px">Descripci&oacute;n:</td>
		<td align="left" class="formulario" colspan=5>
			<input  name="txtDescripcion" id="txtDescripcion" style="width: 100%;" >
		</td>
	</tr>
	<tr>
		<td class="formulario" >Fecha Mes Cobro:</td>
		<td align="left" class="formulario"  style="width:250px" colspan=2>
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
					for($i = 2022; $i <= 2030; $i++){
			?>
				<option value='<?php echo $i ?>' <?php if($i==2022) echo "SELECTED=SELECTED" ?> ><?php echo $i ?></option>
				
			<?php
					}
			?>
		</select>
		</td>
		<td class="formulario" >Fecha Descuento:</td>
		<td align="left" class="formulario"  style="width:250px" colspan=2>
		<select name="txtDiaDes" id="txtDiaDes" style="width: 60px;">
			<?php 
				for($i = 1; $i <= 31; $i++){
			?>
				<option value='<?php echo $i ?>'><?php echo $i ?></option>
			<?php
					}
			?>
		</select>
		<select name="txtMesDes"	id="txtMesDes"  style="width: 60px;">
			<?php 
					for($i = 1; $i <= 12; $i++){
			?>
				<option value='<?php echo $i ?>'><?php echo $i ?></option>
			<?php
					}
			?>
		</select>
		<select name="txtAnoDes"	id="txtAnoDes"  style="width: 70px;">
			<?php 
					for($i = 2022; $i <= 2030; $i++){
			?>
				<option value='<?php echo $i ?>' <?php if($i==2022) echo "SELECTED=SELECTED" ?> ><?php echo $i ?></option>
				
			<?php
					}
			?>
		</select>
		</td>
	</tr>
	<tr>
		<td class="formulario">Monto</td>
		<td colspan="2">
			<input name="txtmontocobrado" type="text" style="width: 200px;" id="txtmontocobrado" onkeypress="Solo_Numero();">
		</td>
		<td class="formulario">Modalidad</td>
		<td>
			<select id='moda'></select>
		</td>
	</tr>	
</table>
<br><br>

<div id="tabs" style="width:100%"> 
  <ul>
  	<li><a href="#tabs-3">Control Mensual</a></li>
  	<li><a href="#tabs-2">Control de Pagos</a></li>
    <li><a href="#tabs-1">Historia de Cuotas</a></li>
    
    
  </ul>						    							    	
	<div id='tabs-1' >																		
			<div id="tabla_creditos" style="overflow:auto;height: 70%;"></div>
			<input type="hidden" id="txtDocumento_Id" name="txtDocumento_Id" >
			<input type="hidden" id="txtNumero_Contrato" name="txtNumero_Contrato" >
	</div>
	<div id='tabs-2'>
			<div id="DivPlanPagos" style="height: 65%;"></div>	
			<br><br>					
	</div>
	<div id='tabs-3'>
			<div id="DivMensual" style="height: 65%;"></div>	
			<br><br>					
	</div>
	
</div>



