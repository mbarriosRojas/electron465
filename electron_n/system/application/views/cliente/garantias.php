<script>
	
function BSerial(strRuta){
	var cedula = $('#txtCedula').val();
	var serial = $('#txtNumero_Serial').val();
	alert(cedula);
}
</script>
<form name="frmGarantia" id='frmGarantia'>	
<table style="width:620px" border="0" cellspacing="3" cellpadding="0" class="formulario">
	
	<tr>
		<td><strong>N&uacute;m. Serial <font color='#B70000'>(*)</font> : </strong></td>
		<td align="left" >
			<input  name="txtNumero_Serial" id="txtNumero_Serial" class="inputxt" style="width: 200px;" onblur="BSerial('<?php echo __LOCALWWW__ ?>');" />
		</td>
		
	</tr>
	<tr>
		<td align="left" >Motivo: </td>
		<td align="left" colspan="3" valign="top"><textarea name="txtSugerencia" id="txtSugerencia" class="inputxt" rows="5" style="width: 445px; height: 60px"  ></textarea></td>
	</tr>	
	<tr>
		<td style="width:150px" >Fecha<font color='#B70000'>(*)</font>:</td>
		<td align="left"  style="width:260px" colspan=3 >
		<select name="txtDiaG" id="txtDiaG" class="inputxt" style="width: 55px;" >
				<option value=0>Dia:</option>
				<?php 	for($i = 1; $i <= 31; $i++){		?>
					<option value='<?php echo $i ?>'><?php echo $i ?></option>
				<?php	}	?>
			</select>
			<select name="txtMesG"	id="txtMesG" class="inputxt" style="width: 55px;" >
				<option value=0>Mes:</option>
				<?php 	for($i = 1; $i <= 12; $i++){		?>
					<option value='<?php echo $i ?>'><?php echo $i ?></option>
				<?php	}	?>
			</select>
			<select name="txtAnoG"	id="txtAnoG" class="inputxt" style="width: 60px;" >
				<option value=0>A&ntilde;o:</option>
				<?php 	for($i = 2004; $i <= 2012; $i++){		?>
					<option value='<?php echo $i ?>'><?php echo $i ?></option>
				<?php	}	?>
			</select>
		</td> 
	</tr>
	<tr>
		<td rowspan="3" colspan="2" class="formulario" valign="top" align="right">
			<input type="button" onclick="alert('hello');" value="Solicitar" style="width:120px; height:43px" class="inputxt">
		</td>
	</tr>
</table>
</form>