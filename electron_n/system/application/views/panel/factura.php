<table style="background-color: #B6B6B6">
	<tr>
		<td><h2> MODIFICA N&Uacute;MERO DE FACTURA</h2><br>Recuerde que debe estar seguro que la factura por el cual va a cambiar no esta asignado a otro.
		<br><br>
			<table style="width:500px">
				<tr>
					<td class="formulario">&nbsp;N&uacute;mero de Factura (*)</td>
					<td class="formulario">&nbsp;Modificaci&oacute;n de Factura</td>
					<td class="formulario">&nbsp;</td>
					<td></td>
				</tr>
				<tr>
					<td class="formulario"><input type="text" class="inputxt" style="width:200px" name="txtFactura_A" id="txtFactura_A"></td>
					<td class="formulario"><input type="text" class="inputxt" style="width:200px" name="txtFactura_N" id="txtFactura_N"></td>
					<td class="formulario">
						<button onclick="Respaldo_Modificar_Factura();" >Modificar</button>
					</td>
					<td>
						<img src='<?php echo __IMG__?>cargando_tab.gif' style="margin: 0px 0 0px 0; padding: 0px 0px 0px 0px;" id='img_modificar_factura'>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<br><br>
<table style="background-color: #C8E6C9">
	<tr>
		<td>
			<h2> MODIFICAR DATOS DE LA FACTURA</h2><br>Recuerde que SOLO LOS CAMPOS CON VALOR ASIGNADO seran modificados.
			<br><br>
			<table style="width:500px">
			<tr>
				<td class="formulario"style="width:200px">N&uacute;mero de Factura </td>
				<td class="formulario"><input type="text" class="inputxt" style="width:200px" name="txtNumero_Factura" id="txtNumero_Factura" onblur="BFactura_Modificar();"></td>
			</tr>
			<tr>
				<td style='width:200px;'>Motivo o Concepto:</td>
				<td align="left" colspan="5">
					<select name="txtMotivo"	id="txtMotivo" class="inputxt" style="width: 200px;" >
						<option value=0>----------------------</option>
						<option value=1>-- CREDITO --</option>
						<option value=2>-- FINANCIAMIENTO ( PRESTAMO ) --</option>
						<option value=3>-- CREDITO Y FINACIAMIENTO  ( PRESTAMO ) --</option>
					</select>
				</td>
			</tr>
			<tr>
				<td align="left">Condici&oacute;n</td>
				<td align="left" >
					<select name="txtCondicion"	id="txtCondicion" class="inputxt" style="width: 200px;" >
						<option value='4'>----------</option>
						<option value='0'>DEPOSITO</option>
						<option value='1'>TRANSFERENCIA</option>					
						<option value='3'>CHEQUE</option> 
						<option value='5'>EQUIPO O ARTEFACTO</option>
						<option value='6'>PENDIENTE POR AUTORIZAR</option>
						<option value='7'>MOTO</option>			
					</select>
				</td>
			</tr>
			<tr>
				<td># Deposito :</td>
				<td>
					<input  name="txtDeposito" id="txtDeposito" class="inputxt" style="width: 180px;" >
				</td>
			</tr>
			<tr>
				<td class="formulario">Fecha Operaci&oacute;n :</td>		
				<td align="left"  style="width:210px" colspan="3">
					<select name="txtDiaO" id="txtDiaO" class="inputxt" style="width: 50px;" >
						<option value=0>D&iacute;a</option>
						<?php for($i = 1; $i <= 31; $i++){	?>
						<option value='<?php echo $i ?>'><?php echo $i ?></option>
						<?php	} ?>
					</select>
					<select name="txtMesO"	id="txtMesO" class="inputxt" style="width: 50px;" >
						<option value=0>Mes</option>
						<?php 	for($i = 1; $i <= 12; $i++){		?>
						<option value='<?php echo $i ?>'><?php echo $i ?></option>
						<?php	} ?>
					</select>
					<select name="txtAnoO"	id="txtAnoO" class="inputxt" style="width: 60px;" >
						<option value=0>A&ntilde;o</option>
						<?php for($i = 2006; $i <= 2014; $i++){	?>
						<option value='<?php echo $i ?>'><?php echo $i ?></option>
						<?php	} ?>
					</select>
				</td>
			</tr>
			<tr>
				<td class="formulario">	Monto:</td>
				<td><input  name="txtMonto" id="txtMonto" class="inputxt" style="width: 180px;" ></td>
			</tr>
			<tr>
				<td class="formulario">
					<button onclick="Modificar_Datos_Factura();" >Realizar Cambios</button>
				</td>
				<td><img src='<?php echo __IMG__?>cargando_tab.gif' style="margin: 0px 0 0px 0; padding: 0px 0px 0px 0px;"id='img_modifica_datos_factura' ></td>
			</tr>
			</table>
				
		</td>
	</tr>
</table>
<br>
<table style="background-color: #B6B6B6">
	<tr>
		<td><h2>INACTIVAR FACTURA </h2>Recuerde que todos los datos asociados a la FACTURA NO SE REFLEJARAN EN LOS REPORTES.
		<br>
		<br>
		<table style="width:300px">
			<tr>
				<td class="formulario">&nbsp;N&uacute;mero de Factura (*)</td>
				<td class="formulario">&nbsp;</td>
				
			</tr>
			<tr>
				<td class="formulario">
				<input type="text" class="inputxt" style="width:200px" name="txtFacturaInactivar" id="txtFacturaInactivar">
				</td>
				<td class="formulario">
				<button onclick="Respaldo_Inactivar_Factura();">Inactivar</button>
				</td>
				<td><img src='<?php echo __IMG__?>cargando_tab.gif' style="margin: 0px 0 0px 0; padding: 0px 0px 0px 0px;" id = 'img_inactivar_factura'></td>
			</tr>
		</table></td>
	</tr>
</table>
<br>
<table style="background-color: #C8E6C9">
	<tr>
		<td>
		
				<h2> ELIMINAR FACTURA</h2>Recuerde que debe estar seguro de eliminar factura.<br>
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
							<button onclick="Respaldo_Eliminar_Factura();">Eliminar</button>
						</td>
						<td>
								
								
										<img src='<?php echo __IMG__?>cargando_tab.gif' style="margin: 0px 0 0px 0; padding: 0px 0px 0px 0px;" id='img_eliminar_factura'>
									
						</td>
					</tr>
				</table>
			
	
		</td>
	</tr>
</table>
<br><br>
<table style="background-color: #B6B6B6">
    <tr>
        <td><h2> MODIFICAR LINAJE DE FACTURA <br></h2>Recuerde que debe estar seguro de cambiar el LINAJE ya que influye en los reportes.<br><br>
            <table style="width:500px">
                <tr>
                    <td class="formulario">&nbsp;N&uacute;mero de Factura (*)</td>
                    <td class="formulario">Linaje De Contrato</td>
                    <td class="formulario"><label id="etiquetal2" name="etiquetal2">&nbsp;</label></td>
                    <td></td>
                </tr>
                <tr>
                    <td class="formulario">
                        <input type="text" class="inputxt" style="width:200px" name="txtFactura_Ln" id="txtFactura_Ln" >
                    </td>
                    <td class="formulario">
                        <select class="inputxt" style="width:200px" name="cmbFactura_Ln" id="cmbFactura_Ln">
                            <option value=0>SELECCIONE</option>
                            <option>NOMINA</option>
                            <option>BICENTENARIO</option>
                            <option>BOD</option>
                            <option>PROVINCIAL</option>
                            <option>VENEZUELA</option>
                            <option>BANESCO</option>
                            <option>INDUSTRIAL</option>
                            <option>DOMICILIACION POR OFICINA</option>
                            <option disabled="true">CAMARA MERCANTIL</option>
                            <option>CREDINFO</option>
                            <option disabled="true">INVERCRESA</option>
                            <option>FONDO COMUN</option>
                            <option disabled="true">100% BANCO COMERCIAL</option>
                            <option disabled="true">DOMICILIACION POR OFICINA</option>
                            <option disabled="true">SOFITASA</option>
                            <option>DEL SUR</option>
                            <option >CARONI</option>
                            <option disabled="true">CARIBE</option>
                            <option >MERCANTIL</option>
                            <option disabled="true">INTER-BANCARIO</option>
                        </select>
                    </td>
                    <td class="formulario">
                        <button onclick="Respaldo_Modificar_Linaje2();" >Modificar</button>
                    </td>
                </tr>
            </table> </div> </td>
    </tr>
</table>
<br><br>
<table style="background-color: #C8E6C9">
    <tr>
        <td><h2> MODIFICAR EMPRESA DE La FACTURA <br></h2>Recuerde que debe estar seguro de cambiar la Empresa ya que influye en los reportes y cobros.<br><br>
            <table style="width:500px">
                <tr>
                    <td class="formulario">&nbsp;N&uacute;mero de Factura (*)</td>
                    <td class="formulario">Empresa</td>
                    <td class="formulario"><label id="etiquetaf" name="etiquetaf">&nbsp;</label></td>
                    <td></td>
                </tr>
                <tr>
                    <td class="formulario">
                        <input type="text" class="inputxt" style="width:200px" name="txtFactura_Empresa" id="txtFactura_Empresa" >
                    </td>
                    <td class="formulario">
                        <select name="cmbFactura_Empresa"	id="cmbFactura_Empresa"style="width: 140px;" >
                            <option value=0>Cooperativa</option>
                            <option value=1>Grupo</option>
                        </select>
                    </td>
                    <td class="formulario">
                        <button onclick="Respaldo_Modificar_EmpresaF();" >Modificar</button>
                    </td>
                </tr>
            </table> </div> </td>
    </tr>
</table>