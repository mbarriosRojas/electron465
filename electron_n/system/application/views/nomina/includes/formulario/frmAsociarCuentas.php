

<table>
<tr>
	<td>
		<div>
			<table style="width:650px">
				<tr>
					<td class="formulario">&nbsp;Nombre del Banco (*) </td>
					<td class="formulario">&nbsp;Tipo Banco</td>
					<td class="formulario">&nbsp;# de Cuenta Bancaria</td>
					<td></td>
				</tr>
				<tr>
					<td>
						<select name="txtBanco" id="txtBanco" class="inputxt" style="width:250px">
							<option>----------</option>
							<option>SOFITASA</option>
							<option>BICENTENARIO</option>
							<option>BOD</option>
							<option>PROVINCIAL</option>
							<option>VENEZUELA</option>
							<option>BANESCO</option> 
							<option>INDUSTRIAL</option>
							<option>MERCANTIL</option>
							<option>EL EXTERIOR</option>
							<option>FONDO COMUN</option>
							<option>DEL SUR</option>
							<option>FEDERAL</option>
							<option>CANARIAS</option>
							<option>CARONI</option>
							<option>CARIBE</option>
							<option>PLAZA</option>
							<option>CENTRAL</option>
							<option>NACIONAL DE CREDITO</option>
							<option>DEL SUR</option>
							<option>COMERCIO EXTERIOR</option>
							<option>OCCIDENTAL DE DESCUENTO</option>
						</select>
					</td>
					<td>
						<select name="txtTipoBanco" id="txtTipoBanco" class="inputxt" style="width:200px">
								<option value='2'>----------</option>
								<option value='0'>AHORRO</option>
								<option value='1'>CORRIENTE</option>
						</select>
					</td>
					<td>
						<input type="text" name="txtCuentaBancaria" id="txtCuentaBancaria" class="inputxt" style="width:180px">
					</td>
								
				</tr>
				<tr>
					<td colspan="3" class="formulario" style="height:5px"></td>
				</tr>
				<tr>
					<td colspan="3" class="formulario">&nbsp;Usuario (*)</td>
				</tr>
				<tr>
					<td colspan="2">
						<select name="txtDependencia"	id="txtDependencia" style="width: 450px;">
			    		<?php echo $Listar_Usuarios_Combo; ?>
						</select>						
					</td>
					<td valign="top">
						<table cellpadding="0" cellspacing="0" border="0" style="width:100%">
							<tr>
								<td>
										
									<input type="button" name="txtCuentaBancaria" id="txtCuentaBancaria" style="width:110px" value="Asignar Cuenta" 
									onclick="Asociar_Cuentas_Guardar('<?php echo __LOCALWWW__?>');">
								
								</td>
								<td valign="middle" align="center" style="width:100px">
									
									<div id="divConsultaId" style="display:none;">
										<img src='<?php echo __IMG__?>cargando_tab.gif' style="margin: 0px 0 0px 0; padding: 0px 0px 0px 0px;">
										
									</div>
									
								</td>
							</tr>
						</table>
						
						
					</td>						
				</tr>
			</table>
		</div>
	</td>
	</tr><tr>
		<td>
			<br><br>
			<div id="divListarCuentas">
				<?php echo $Listar_Usuarios_Tabla; ?>
			</div>
			<br><br>
		</td>
	</tr>
</table>