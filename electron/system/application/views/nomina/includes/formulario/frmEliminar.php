
<table>
	<tr>
		<td>
			<div>
				<h4> Recuerde que debe estar seguro que el n&uacute;mero no esté asignado a ningun contrato ya que seran eliminados del sistema.</h4>
				<br><br>
				<table style="width:300px">
					<tr>
						<td class="formulario">&nbsp;N&uacute;mero de C&eacute;dula (*)</td>
						<td class="formulario">&nbsp;</td>
						<td></td>
					</tr>
					<tr>
						<td class="formulario"><input type="text" class="inputxt" style="width:200px" name="txtCedula" id="txtCedula"></td>
						<td class="formulario"> 
							<input type="button" style="height:19px; width:100px" value="Eliminar" 
											onclick="Eliminar_Cedula('<?php echo __LOCALWWW__?>');">
						</td>
						<td>
								
								<div id="divProcesarCedula">
										<img src='<?php echo __IMG__?>cargando_tab.gif' style="margin: 0px 0 0px 0; padding: 0px 0px 0px 0px;">
									</div>
						</td>
					</tr>
				</table>
				<div id="divEliminarCedula"></div>
			</div>
		</td>
	</tr>
</table>


<br /><br />
<hr >
<br /><br />

<table>
	<tr>
		<td>
			<div>
				<h4> Recuerde que debe estar seguro ya que seran eliminados del sistema.</h4>
				<br><br>
				<table style="width:300px">
					<tr>
						<td class="formulario">&nbsp;N&uacute;mero de Contrato (*)</td>
						<td class="formulario">&nbsp;</td>
						<td></td>
					</tr>
					<tr>
						<td class="formulario"><input type="text" class="inputxt" style="width:200px" name="txtContrato" id="txtContrato"></td>
						<td class="formulario"> 
							<input type="button" style="height:19px; width:100px" value="Eliminar" 
											onclick="Eliminar_Contrato_C('<?php echo __LOCALWWW__?>');">
						</td>
						<td>
								
								<div id="divProcesarContrato">
										<img src='<?php echo __IMG__?>cargando_tab.gif' style="margin: 0px 0 0px 0; padding: 0px 0px 0px 0px;">
									</div>
						</td>
					</tr>
				</table>
				<div id="divEliminarContrato"></div>
			</div>
		</td>
	</tr>
</table>


<br /><br />
<hr >
<br /><br />

<table>
	<tr>
		<td>
			<div>
				<h4> Recuerde que debe estar seguro de eliminar factura.</h4>
				<br><br>
				<table style="width:300px">
					<tr>
						<td class="formulario">&nbsp;N&uacute;mero de Factura (*)</td>
						<td class="formulario">&nbsp;</td>
						<td></td>
					</tr>
					<tr>
						<td class="formulario"><input type="text" class="inputxt" style="width:200px" name="txtFactura" id="txtFactura"></td>
						<td class="formulario"> 
							<input type="button" style="height:19px; width:100px" value="Eliminar" 
											onclick="Eliminar_Factura_C('<?php echo __LOCALWWW__?>');">
						</td>
						<td>
								
								<div id="divProcesarFactura">
										<img src='<?php echo __IMG__?>cargando_tab.gif' style="margin: 0px 0 0px 0; padding: 0px 0px 0px 0px;">
									</div>
						</td>
					</tr>
				</table>
				<div id="divEliminarFactura"></div>
			</div>
		</td>
	</tr>
</table>


<br /><br />
<hr >
<br /><br />



<br /><br />
<hr>
<br /><br />

<table>
	<tr>
		<td>
			<div>
				<h4> Eliminar N&oacute;mina.</h4>
				<br><br>
				<table style="width:300px">
					<tr>
						<td class="formulario" colspan="3">Seleccione Nomina(*)</td>
					</tr>
					<tr>
						<td class="formulario">
							<select name="cmbNomina"	id="cmbNomina" class="inputxt" style="width: 260px;">
								<?php echo $lista;?>
							</select>
						</td>
						<td><input type="button" style="height:19px; width:100px" value="Eliminar" 
											onclick="Eliminar_Nomina('<?php echo __LOCALWWW__?>');"></td>
						<td>
								
								<div id="divProcesarNomina">
										<img src='<?php echo __IMG__?>cargando_tab.gif' style="margin: 0px 0 0px 0; padding: 0px 0px 0px 0px;">
									</div>
						</td>
					</tr>
				</table>
				<div id="divEliminarNomina"></div>
			</div>
		</td>
	</tr>
</table>

<br /><br />
<hr>
<br /><br />

