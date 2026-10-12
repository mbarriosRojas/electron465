<table><tr><td><h2> Agregar nuevas nominas.</h2></td></tr></table>
<br>
<br>
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
		<button name='btnCrear' id='btnCrear' onclick="Crear_Nomina();">Agregar Nomina</button>
		</td>
	</tr>
</table>

<br>
<br>
<table><tr><td><h2> Agregar Zona Postal.</h2></td></tr></table>
<br>
<br>
<table width="600" border="0" cellspacing="3" cellpadding="0"class="formulario">
	<tr>
		<td style='width: 140px;' class='formulario'>Estado:</td>
		<td style='width: 120px;' align='left' class='formulario'>
			<select id="cmbEstados" name="cmbEstados">
		<?php echo $estados; ?>
			</select>
		</td>
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
		<button name='btnCrearZona' id='btnCrearZona'onclick="Crear_Zona('<?php echo __LOCALWWW__?>');">Agregar Zona</button>
		</td>
	</tr>
</table>
<br>
<br>
<br>
<table>
	<tr>
		<td><h2> Eliminar N&oacute;mina. </h2>
		<br>
		<br>
		<table style="width:300px">
			<tr>
				<td class="formulario" colspan="3">Seleccione Nomina(*)</td>
			</tr>
			<tr>
				<td class="formulario">
				<select name="cmbNomina"	id="cmbNomina" class="inputxt" style="width: 260px;">
					<?php echo $lista; ?>
				</select></td>
				<td>
				<button onclick="Eliminar_Nomina();">Eliminar</button>
				</td>
				<td>
					<img src='<?php echo __IMG__?>cargando_tab.gif' style="margin: 0px 0 0px 0; padding: 0px 0px 0px 0px;" id = "img_eliminar_nomina">
				</td>
			</tr>
		</table></td>
	</tr>
</table>