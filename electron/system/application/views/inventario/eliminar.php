<br>
<center>

<table style="width:400px">
	<tr><td colspan="3">
		<h2> c&oacute;digo del serial.</h2>
	</td></tr>
	<tr>
		
		<td><input type="text" style="width:260px" name="txtSerial_E" id="txtSerial_E"></td>
		<td> 
			<button onclick="Eliminar_Serial();">Eliminar Seriales</button>
		</td>
		<td>
				<img src='<?php echo __IMG__?>cargando_tab.gif' style="margin: 0px 0 0px 0; padding: 0px 0px 0px 0px;" id="el_serial">								
		</td>
	</tr>
</table>
<div id="divEliminarSerial"></div>

	
<br><br><br><br>

<table style="width:400px">
	<tr><td colspan="3">
		<h2> seleccione el modelo </h2>
	</td></tr>
	<tr>
		<td class="formulario">
			<select name="cmbModelo"	id="cmbModelo" class="inputxt" style="width: 260px;">
				<?php echo $lista_artefactos;?>
			</select>
		</td>
		<td><button onclick="Eliminar_Modelo();"> Eliminar Modelo</button>						
		</td>
		<td>
			<img src='<?php echo __IMG__?>cargando_tab.gif' style="margin: 0px 0 0px 0; padding: 0px 0px 0px 0px;" id="el_modelo">								
		</td>
	</tr>
</table>
<div id="divEliminarModelo"></div>
</center>