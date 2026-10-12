<table>
	<tr>
		<td>
			<h2> Modificar Modelos de Inventario.</h2>
				<br><br>
				<table style="width:300px">
					<tr>
						<td class="formulario" colspan="3">Seleccione Modelo(*)</td>
					</tr>
					<tr>
						<td class="formulario">
							<select name="cmbModeloM"	id="cmbModeloM" class="inputxt" style="width: 260px;">
								<?php echo $lista_artefactos2;?>
							</select>
						</td>
						<td class="formulario">
							<input type="text" name='txtNMod' id='txtNMod'/>
						</td>
						<td><button style="height:19px; width:100px" onclick="Mod_Modelo();">Modificar</td>
						<td>
							<img src='<?php echo __IMG__?>cargando_tab.gif' style="margin: 0px 0 0px 0; padding: 0px 0px 0px 0px;" id="img_mod_modelo">
						</td>
					</tr>
				</table>
			</div>
		</td>
	</tr>
	<tr>
		<td>
			<h2> Modificar Artefactos de Inventario.</h2>
				<br><br>
				<table style="width:300px">
					<tr>
						<td class="formulario" colspan="3">Seleccione artefacto(*)</td>
					</tr>
					<tr>
						<td class="formulario">
							<select name="cmbArt"	id="cmbArt" class="inputxt" style="width: 260px;">
								<?php echo $lista_art;?>
							</select>
						</td>
						<td class="formulario">
							<input type="text" name='txtNArt' id='txtNArt'/>
						</td>
						<td><button style="height:19px; width:100px" onclick="Mod_Artefacto();">Modificar</td>
						<td>
							<img src='<?php echo __IMG__?>cargando_tab.gif' style="margin: 0px 0 0px 0; padding: 0px 0px 0px 0px;" id="img_mod_art">
						</td>
					</tr>
				</table>
			</div>
		</td>
	</tr>
</table>