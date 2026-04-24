<br>
<form name="frmInventario" method="POST" enctype="multipart/form-data" action='<?php echo base_url(); ?>index.php/cooperativa/Guardar_Inventario' onSubmit="return valida(this);">
	<table width="680" border="0" cellspacing="3" cellpadding="0">	
		<tr>
			<td>Modelo:</td>
			<td>
				<input type="text" 	name="txtModelo" id="txtModelo" onblur="BModelo();" style="width: 188px;"value='<?php echo $Modelo ?>' />
			</td>
			<td bgcolor="#000"rowspan="4"><a href="#" Onclick="carga_imagen()"><img src="<?php echo __IMG__; ?>nodisponible.jpg" style="width:100%;height: 100%;" id ="foto" name="foto"/></a></td>
		</tr>
		<tr>
			<td style="width: 140px;">Proveedor :</td>
			<td>	
				<input type="text" name="txtProveedores" id="txtProveedores" style="width:188px" value='<?php echo $Proveedor ?>'readonly='true' />
				<input type="hidden" name="txtProveedores1" id="txtProveedores1" style="width:188px" value='<?php echo $Proveedor ?>'readonly='true' />
			</td>
		</tr>	
		<tr>
			<td>Equipo :</td>
			<td>	
				<!--<input type="text" name="txtEquipos" id="txtEquipos" style="width:188px" value='<?php echo $Equipo ?>'/>-->
				<input type="hidden" name="txtEquipos1" id="txtEquipos1" style="width:500px" value='<?php echo $Equipo ?>'/>
				<select id='txtEquipos' name='txtEquipos' style="width:188px" readonly='true'><?php echo $lista_art;?></select>
			</td>
		</tr>
		<tr>
			<td>Marca:</td>
			<td>
				<input type="text" name="txtMarca" id="txtMarca" style="width: 188px;" value='<?php echo $Marca ?>'readonly='true' />
			</td>
		</tr>
		<tr>
			<td>Porcentaje Contado: </td>
			<td>
				<input name="txtPorcentaje" type="text"  id="txtPorcentaje" style="width: 55px;"value='<?php echo $Porcentaje ?>'readonly='true' />&nbsp;%
			</td>
			<td>Catalogo: </td>
			<td>
				<select id="catalogo" name="catalogo">
					<option value = 0 >NO</option>
					<option value=1 >SI</option>
				</select>
			</td>	
		</tr>	
		<tr>
			<td>Precio de Compra: </td>
			<td>
				<input name="txtprecioc" type="text" id="txtprecioc" style="width: 100px;"value='<?php echo $Precio_C ?>'readonly='true' />
			</td> 
			<td>Precio de Credito: </td>
			<td align="right">
				<input name="txtpreciov" type="text" id="txtpreciov"  style="width: 120px;"	value='<?php echo $Precio_V ?>' readonly='true'/><br>
				<label id='mod'>Modificado</label> 
			</td>
		</tr>
		<tr>
			<td>Precio Credi Compra: </td>
			<td colspan="3">
				<input name="txtCrediCompra" type="text" id="txtCrediCompra" style="width: 100px;"value='<?php echo $Credi_Compra ?>'readonly='true'/>
			</td> 
			
		</tr>
		<tr>
			<td valign="top"> Detalle Modelo:</td>
			<td colspan="3" valign="top">
				<textarea name="txtDetalle"  style="width: 500px;" id="txtDetalle"class="inputxt" rows="2" cols="5"readonly='true' ></textarea>
			</td>
		</tr>
		<tr>
			<td valign="top"> Descripci&oacute;n:</td>
			<td colspan="3" valign="top">
				<textarea name="txtDescripcion"  style="width: 500px;" id="txtDescripcion"class="inputxt" rows="2" cols="5"></textarea>
			</td>
		</tr>	
		</tr>	
			<td style="width: 140px;" >Fecha:</td>
			<td style="width: 120px;" align="left" colspan ="3">
				<select name="txtdia" id="txtdia" style="width: 50px;" >
				<?php for($i = 1; $i <= 31; $i++){	?>
					<option value='<?php echo $i ?>'><?php echo $i ?></option>
				<?php 	} ?>
				</select>		
				<select	name="txtmes" id="txtmes" style="width: 80px;" >
				<?php	for($i = 1; $i <= 12; $i++){		?>	
					<option value='<?php echo $i ?>'><?php echo $i ?></option>
				<?php 	} ?>
				</select>
				<select	name="txtano" id="txtano" style="width: 120px;" >
				<?php 	for($i = 2013; $i <= 2014; $i++){		?>
					<option value='<?php echo $i ?>'><?php echo $i ?></option>
				<?php 	} ?>			
				</select>
				
			</td>
		<tr>		
		<tr>	
			<td valign="top">Garantia: </td>
			<td align="left" valign="top">
				<input name="txtCanGarantia" type="text"  id="txtCanGarantia" style="width: 55px;"value='<?php echo $CanGar ?>'/>	
				<select	name="txtgarantia"  id="txtgarantia" style="width: 120px;" >
					<option><?php echo $Garantia ?></option>
					<option>DIA </option>
					<option>SEMANAS </option>
					<option>MESES </option>
					<option>TRIMESTRALES </option>
					<option>SEMESTRAL </option>
					<option>ANUAL </option>
				</select>
			</td>
			<td valign="top" colspan="2">Lista de Seriales : </td>
		</tr>
		<tr>
			<td valign="top">Seriales : </td>
			<td valign="top">				
				<table>
					<tr>
						<td valign="top"><input type="text" name="txtSeriales"  id="txtSeriales" style="width: 180px" /></td>
						<td valign="top"><input type="button" onClick="IncSeriales();" id="Incluir_Serial" value="+ Incluir" /></td>				
					</tr>				
				</table>				
			</td>
			<td valign="top"  align="right" colspan="2">
				<select multiple name="lstSeriales[]" id="lstSeriales"  style="width: 230px; height:75px" ondblclick="EliSeriales();" >
					<option value='NINGUNO'>NINGUNO</option>
				</select>				
			</td>
		</tr>
	</table>
	<br>
	<center><button onclick="SelSeriales();" id="Enviar">Guardar Información</button></center>
</form>
<br><br>
<div id='resultado' style="height: 300px;" ></div>