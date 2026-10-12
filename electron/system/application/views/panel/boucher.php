<table>
	<tr>
		<td><h2>MODIFICAR N&Uacute;MERO DE VOUCHER </h2><BR>Recuerde que debe estar seguro que el Voucher a modificar no este asignado a otra factura.<BR>
		<br>
		<br>
		<table style="width:500px">
			<tr>
				<td class="formulario">&nbsp;N&uacute;mero de Voucher (*)</td>
				<td class="formulario">&nbsp;Modificaci&oacute;n de Voucher</td>
				<td class="formulario">&nbsp;</td>
				<td></td>
			</tr>
			<tr>
				<td class="formulario">
				<input type="text" class="inputxt" style="width:200px" name="txtBoucher_A" id="txtBoucher_A">
				</td>
				<td class="formulario">
				<input type="text" class="inputxt" style="width:200px" name="txtBoucher_N" id="txtBoucher_N">
				</td>
				<td class="formulario">
				<button onclick="Respaldo_Modificar_Boucher();" >Modificar</button>
				</td>
				<td>
					<img src='<?php echo __IMG__?>cargando_tab.gif' style="margin: 0px 0 0px 0; padding: 0px 0px 0px 0px; " id="img_modificar_boucher" >
				</td>
			</tr>
		</table></td>
	</tr>
</table>

<table>
	<tr>
		<td><h2>Desbloquear Voucher unico Bicentenario </h2><BR>Recuerde que se modificaran los contratos con forma de voucher a domiciliacion.<BR>
		<br>
		<br>
		<table style="width:500px">
			<tr>
				<td class="formulario">&nbsp;N&uacute;mero de Factura (*)</td>
				<td class="formulario">&nbsp;</td>
			</tr>
			<tr>
				<td class="formulario">
				<input type="text" class="inputxt" style="width:200px" name="txtFBoucher" id="txtFBoucher">
				</td>
				
				<td class="formulario">
				<button onclick="Respaldo_Modificar_Modalidad();" >Modificar</button>
				</td>
				<td>
					<img src='<?php echo __IMG__?>cargando_tab.gif' style="margin: 0px 0 0px 0; padding: 0px 0px 0px 0px; " id="img_modificar_boucher2" >
				</td>
			</tr>
		</table></td>
	</tr>
</table>

<table>
	<tr>
		<td><h2>Eliminar N&Uacute;MERO DE VOUCHER </h2><BR>Recuerde que debe ingresar numero de voucher y factura al cual pertenece.<BR>
		<br>
		<br>
		<table style="width:500px">
			<tr>
				<td class="formulario">&nbsp;N&uacute;mero de Voucher (*)</td>
				<td class="formulario">&nbsp;N&uacute;mero de Factura</td>
				<td class="formulario">&nbsp;</td>
				<td></td>
			</tr>
			<tr>
				<td class="formulario">
				<input type="text" class="inputxt" style="width:200px" name="txtVoucher_ED" id="txtVoucher_ED">
				</td>
				<td class="formulario">
				<input type="text" class="inputxt" style="width:200px" name="txtFactura_ED" id="txtFactura_ED">
				</td>
				<td class="formulario">
				<button onclick="Respaldo_Eliminar_Voucher_Deposito();" >Eliminar</button>
				</td>
				<td>
					<img src='<?php echo __IMG__?>cargando_tab.gif' style="margin: 0px 0 0px 0; padding: 0px 0px 0px 0px; " id="img_eliminar_voucher_deposito" >
				</td>
			</tr>
		</table></td>
	</tr>
</table>

<table>
	<tr>
		<td><h2>Eliminar GRUPO DE VOUCHER por FACTURA</h2><BR>Recuerde que debe ingresar numero factura a eliminar los voucher.<BR>
		<br>
		<br>
		<table style="width:500px">
			<tr>
				<td class="formulario">&nbsp;N&uacute;mero de Factura (*)</td>
				<td class="formulario">&nbsp;</td>
			</tr>
			<tr>
				<td class="formulario">
				<input type="text" class="inputxt" style="width:200px" name="txtFactura_EG" id="txtFactura_EG">
				</td>
				
				<td class="formulario">
				<button onclick="Respaldo_Eliminar_Voucher_Grupo();" >Modificar</button>
				</td>
				<td>
					<img src='<?php echo __IMG__?>cargando_tab.gif' style="margin: 0px 0 0px 0; padding: 0px 0px 0px 0px; " id="img_eliminar_voucher_grupo" >
				</td>
			</tr>
		</table></td>
	</tr>
</table>