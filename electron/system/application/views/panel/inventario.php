<table>
	<tr>
		<td><h2>ELIMINAR SERIAL </h2> Recuerde que debe estar seguro que el n&uacute;mero de serial debe existir.
			<br><br>
			<table style="width:300px">
				<tr>
					<td class="formulario">&nbsp;N&uacute;mero de Serial (*)</td>
					<td class="formulario">&nbsp;</td>
					<td></td>
				</tr>
				<tr>
					<td class="formulario"><input type="text" class="inputxt" style="width:200px" name="txtSerial_E" id="txtSerial_E"></td>
					<td class="formulario"> 
						<button style="height:19px; width:100px" onclick="Respaldo_Eliminar_Serial();">Eliminar Serial</button>
					</td>
					<td>	
						<img src='<?php echo __IMG__?>cargando_tab.gif' style="margin: 0px 0 0px 0; padding: 0px 0px 0px 0px;" id = "img_eliminar_serial">
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<table>
	<tr>
		<td>
			<h2> Eliminar Modelos de Inventario.</h2>
				<br><br>
				<table style="width:300px">
					<tr>
						<td class="formulario" colspan="3">Seleccione Modelo(*)</td>
					</tr>
					<tr>
						<td class="formulario">
							<select name="cmbModelo"	id="cmbModelo" class="inputxt" style="width: 260px;">
								<?php echo $lista_artefactos;?>
							</select>
						</td>
						<td><button style="height:19px; width:100px" onclick="Respaldo_Eliminar_Modelo();">Eliminar Modelo</td>
						<td>
							<img src='<?php echo __IMG__?>cargando_tab.gif' style="margin: 0px 0 0px 0; padding: 0px 0px 0px 0px;" id="img_eliminar_modelo">
						</td>
					</tr>
				</table>
			</div>
		</td>
	</tr>
</table>