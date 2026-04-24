<form name="frmListar" method="POST" action='<?php echo base_url(); ?>index.php/cooperativa/Listar_Inventarios'>	
		<div class="boton">
			<button> Listar Inventario</button>
		</div>
</form>
<br>
<form name="frmInventario" method="POST" enctype="multipart/form-data"   
action='<?php echo base_url(); ?>index.php/cooperativa/Guardar_Inventario' >
<table width="680" border="0" cellspacing="3" cellpadding="0"
	class="formulario">
	<tr>
		<td>Modelo:</td>
		<td  colspan="3">
			<div class="ui-widget">
				<input type="text" 	name="txtModelo" id="txtModelo" onblur="BModelo('<?php echo __LOCALWWW__?>');" class="inputxt" style="width: 188px;" 
						value='<?php echo $Modelo ?>'
			</div>
		</td>	
	</tr>
	<tr>
		<td style="width: 140px;">
			Proveedor :
		</td>
		<td colspan="3">			
				<div class="ui-widget">
					<input name="txtProveedores" id="txtProveedores" class="inputxt" style="width:500px"
					value='<?php echo $Proveedor ?>'/>
				</div>	
		</td>
	</tr>	
		<tr>
		<td>
			Equipo : 
		</td>
		<td colspan="3">			
				<div class="ui-widget">
					<input name="txtEquipos" id="txtEquipos" class="inputxt" style="width:500px"
					value='<?php echo $Equipo ?>'/>
				</div>	
		</td>
	</tr>	
		<tr>
		<td>Marca:</td>
		<td colspan="3">
			<div class="ui-widget">
				<input type="text" name="txtMarca" id="txtMarca" class="inputxt" style="width: 188px;" 
				value='<?php echo $Marca ?>'/>
			</div>
		</td>	
	</tr>	
	<tr>
		<td>Precio de Compra: </td>
		<td>
			<input name="txtprecioc" type="text" id="txtprecioc" class="inputxt" style="width: 188px;"
			value='<?php echo $Precio_C ?>'/>
		</td> 
		<td>Precio de Venta: </td>
		<td align="right">
			<input name="txtpreciov" type="text" id="txtpreciov"  class="inputxt" style="width: 188px;"
			value='<?php echo $Precio_V ?>'/>
		</td>
	<tr>
			<td class="formulario">Deposito:</td>
			<td align="left" class="formulario" colspan="3">
				<select name="txtdeposito"  id="txtdeposito" class="inputxt" style="width: 500px;" >
					<option value="232261089">CEDE PRINCIPAL, ESTADO TACHIRA SAN CRISTOBAL</option>
				</select>
			</td>	
	</tr>		
		<tr>
		<td valign="top"> Descripci&oacute;n:</td>
		<td colspan="3" valign="top">
			<textarea name="txtcdescripcion"  style="width: 500px;" id="txtdescripcion" 
			class="inputxt" rows="2" cols="5"></textarea>
		</td>
	</tr>	
		</tr>	
		<td style="width: 140px;" class="formulario">Fecha:</td>
		<td style="width: 120px;" align="left" class="formulario" colspan ="3">
		<select name="txtdia" id="txtdia" class="inputxt" style="width: 50px;" >
				<?php for($i = 1; $i <= 31; $i++){	?>
			<option value='<?php echo $i ?>'><?php echo $i ?></option>
				<?php 	}	?>
		</select>		
		<select	name="txtmes" id="txtmes" class="inputxt" style="width: 80px;" >
				<?php	for($i = 1; $i <= 12; $i++){		?>	
			<option value='<?php echo $i ?>'><?php echo $i ?></option>
				<?php 	}	?>
		</select>
		<select	name="txtano" id="txtano" class="inputxt" style="width: 120px;" >
			<?php 	for($i = 2011; $i <= 2014; $i++){		?>
			<option value='<?php echo $i ?>'><?php echo $i ?></option>
			<?php 	}	?>			
		</select></td>
	<tr>		
		<tr>	
			<td class="formulario" valign="top">Garantia: </td>
			<td align="left" class="formulario" colspan="3" valign="top">
			<input name="txtCanGarantia" type="text"  id="txtCanGarantia" style="width: 55px;" class="inputxt"
			value='<?php echo $CanGar ?>'/>	
				<select	name="txtgarantia"  id="txtgarantia" class="inputxt" style="width: 120px;" >
					<option><?php echo $Garantia ?></option>
					<option>DIA </option>
					<option>SEMANAS </option>
					<option>MESES </option>
					<option>TRIMESTRALES </option>
					<option>SEMESTRAL </option>
					<option>ANUAL </option>
				</select>
			</td>	
	</tr>
	<tr>
			<td valign="top">Seriale : </td>
			<td valign="top">				
				<table>
					<tr>
						<td valign="top">
							<input type="text" name="txtSeriales"  id="txtSeriales" class="inputxt" style="width: 150px;" /></td>
						<td valign="top"  >
							<div class="demo">								
								<p><a href="#" onClick="IncSeriales();" 
									id="dialog_link" class="ui-state-default ui-corner-all">
									<span class="ui-icon ui-icon-circle-plus"></span></a>
							</div>
						</td>				
					</tr>				
				</table>				
			</td>
			<td valign="top">Lista de Seriales : </td>
			<td valign="top"  align="right">
				<select multiple name="lstSeriales[]" id="lstSeriales"  class="inputxt" style="width: 188px; height:75px" ondblclick="EliSeriales();" >
					<option value='NINGUNO'>NINGUNO</option>
				</select>				
			</td>
</table>
	<br>
		<div class="demo">			
			<button onclick="SelSeriales();"> Enviar Información</button>
		</div>
</form>