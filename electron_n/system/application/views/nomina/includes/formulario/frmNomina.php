<h4> Agregar nuevas nominas.</h4>
<br><br>
<table width="600" border="0" cellspacing="3" cellpadding="0"class="formulario">
	<tr>
		<td style="width: 140px;" class="formulario">Nombre (*) :</td>
		<td style="width: 120px;" align="left" class="formulario">
			<input name="txtNombre" type="text" class="inputxt" style="width: 370px;" id="txtNombre" />
		</td>
	</tr>
	<tr>
		<td style='width: 140px;' class='formulario'>Descripci&oacute;n:</td>
		<td style='width: 120px;' align='left' class='formulario'>
			<input name='txtDescrip' type='text' class='inputxt' style='width: 370px;' id='txtDescrip' />
		</td>
	</tr>
</table>
<table cellpadding='0' cellspacing='0' border='0' style='width:100%'>
	<tr>
		<td>
		<input type='button' name='btnCrear' id='btnCrear' style='width:110px' value='Agregar Nomina'
		onclick="Crear_Nomina('<?php echo __LOCALWWW__?>');">
		</td>
	</tr>
</table>

<br><br><br>
<h4> Agregar nueva zona postal.</h4>
<br>
<br>
<table width="600" border="0" cellspacing="3" cellpadding="0"class="formulario">
	<tr>
		<?php echo $estados;?>
	</tr>
	<tr>
		<td style='width: 140px;' class='formulario'>Zona:</td>
		<td style='width: 120px;' align='left' class='formulario'>
			<input name='txtZona' type='text' class='inputxt' style='width: 370px;' id='txtZona' />
		</td>
	</tr>
	<tr>
		<td style='width: 140px;' class='formulario'>C&oacute;digo:</td>
		<td style='width: 120px;' align='left' class='formulario'>
			<input name='txtCodigo' type='text' class='inputxt' style='width: 70px;' id='txtCodigo' />
		</td>
	</tr>

</table>
<table cellpadding='0' cellspacing='0' border='0' style='width:100%'>
	<tr>
		<td>
		<input type='button' name='btnCrearZona' id='btnCrearZona' style='width:110px' value='Agregar Zona'
		onclick="Crear_Zona('<?php echo __LOCALWWW__?>');">
		</td>
	</tr>
</table>