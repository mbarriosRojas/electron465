<style>
	table{
		width: 700px;
		text-align: justify;
		font-size:11px;
		color:#444;
		font-family:Verdana, Geneva, Arial, Helvetica, sans-serif;
		border:1px solid #444;
	}
	
</style>
<center>
<table border="1" >
	<tr>
		<td colspan="3">
			<img src="<?php echo __IMG__ ?>cabecero.png" alt="solicita"/>
		</td>
	</tr>
	<tr>
		<td style="text-align: right;"colspan="2">
			<b>Creado: &nbsp;</b><?php echo $modificado ?>
		</td>
		<td style="text-align: right;">
			<b>Acuse Numero: &nbsp;</b><?php echo $acuse ?>
		</td>
	</tr>
	<tr>
		<td colspan="3" height="370px" style="border:1px solid #444;">
			<b>&nbsp;</b>
		</td>
	</tr>
	<tr>
		<td style="text-align: left">
			<b>Monto: &nbsp;</b><?php echo $monto ?>
		</td>
		<td style="text-align: left">
			<b>N. de Deposito: &nbsp;</b><?php echo $cheque ?>
		</td>
		<td style="text-align: left">
			<b>N. de Cuenta: &nbsp;</b> <?php echo $chequera?>
		</td>
	</tr>
	<tr>
		<td>
			<b>Banco: &nbsp;</b><?php echo $banco ?>
		</td>
		<td colspan="2">
			<b>Fecha de cobro: &nbsp;</b><?php echo $fecha ?>
		</td>
	</tr>
	<tr><td colspan="3"><b>Concepto:</b></td></tr>
	<tr>
		<td colspan="3" height="170" style="font-size: 15px;" valign="top">			
			<?php echo $concepto ?> 
		</td>
	</tr>
		<tr>
			<td colspan="3">
				<b>Pagado por:</b>
			</td>
		</tr>
		<td style="text-align: center" colspan="2">
			<br><br>_____________________
			<br>
			Alvaro Hernan Zambrano Roa
			<br>
			CI: 11.109.659
		</td>
		<td style="text-align: center">
			<br><br>_____________________
			<br>
			Maria Gabriela Esquivel Rámirez 
			<br>
			CI: 19.777.915
		</td>
	</tr>
	<tr><td colspan="3"><b>Entregado a: <?php echo $nombre?></b></td></tr>
	<tr>
		<td colspan="3">
			<br>
			<p style="text-align: center;">
			<br>_____________________
			<br>Recibi Conforme
			<br>
			Rif /CI.:  <?php echo $cedula ?></p>
		</td>
	</tr>
</table>
</center>