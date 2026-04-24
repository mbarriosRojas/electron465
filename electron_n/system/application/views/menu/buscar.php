<form method="POST" id="frmBuscar" action="<?php echo __LOCALWWW__ ?>/index.php/cooperativa/ProcesarNuevo" name="frmBuscar">
<table border="0" cellpadding="0" cellspacing="0" width="400px" ><tr>
	<td style="width:20px"></td>
	<td  valign="middel"><h1>Sistema de Control</h1></td><td style="width:20px"></td>
	<td valign="top">												
	<select name="txtForma" style="width:135px;height:27px">
		<option value="V-">Venezolano (a)</option>
		<option value="E-">Extranjero (a)</option>
		<option value="V-">Juridico</option>
		<option value="C-">Contrato</option>
		<option value="F-">Factura</option>
		<option value="S-">Serial Activo</option>
		<option value="SV-">Serial Vendido</option>
		<option value="CH-">Cheque</option>
		<option value="X-">Calculadora</option>
		<option value="CB-">Cuenta Bancaria</option>
		<?php  
			if($Nivel == 5 || $Nivel == 9 || $Nivel == 0){
			echo '<option value="AC-">Anular Cheques</option>';	
		 } ?>
		 <?php  
			if($Nivel == 11 || $Nivel == 9 || $Nivel == 0 || $Nivel==3 || $this -> session -> userdata('usuario') == 'Poleeth' ){
			echo '<option value="VO-">VOUCHER</option>';	
		 } ?>
	</select></td><td valign="top">
	<input name="txtBuscar" type="text" value="C&eacute;dula o Contrato" id="txtBuscar"
		onclick="this.value='';" style="width:280px;height:27px" \></td><td valign="top">								
	<button id="buscar"  style="height:27px" onclick="">Consultar</button>								
	</td></tr>
</table>
</form>
<div id="carga_busqueda" style="background-color: #fff;"><center><img src="<?php echo __IMG__ ?>loading.gif"></img><br><h2>CARGANDO</h2><br></center></div>