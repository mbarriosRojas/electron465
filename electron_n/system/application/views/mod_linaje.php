<html>
<head>
	<?php $this->load->view("incluir/cabezera.php");?>
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>view/mod_linaje.js"></script>	
</head>
<body>
	<div id="cabecera"><br>
		<?php $this->load->view("menu/buscar.php");?>
		<?php echo $Menu;?>
	</div>	
	<div id="medio" >	
		<div id="r_contrato"><?php $this->load->view("panel/formularios/respaldo_cont.php");?></div
		
		<div id="msj_alertas"></div>
			<h2 class="demoHeaders">	Modificar Linaje </h2><br>
			<h3>Usuario Conectado: <?php echo $this->session->userdata('usuario'); ?> Fecha: <?php echo date("Y/m/d"); ?></h3><br>
				<br>
<br>
<table>
	<tr>
		<td><h2> MODIFICAR LINAJE DEL CONTRATO <br></h2>Recuerde que debe estar seguro de cambiar el LINAJE ya que influye en los reportes.<br><br>
		<table style="width:500px">
			<tr>
				<td class="formulario">&nbsp;N&uacute;mero de Contrato (*)</td>
				<td class="formulario">Linaje De Contrato</td>
				<td class="formulario"><label id="etiquetal2" name="etiquetal2">&nbsp;</label></td>
				<td></td>
			</tr>
			<tr>
				<td class="formulario">
				<input type="text" class="inputxt" style="width:200px" name="txtContrato_Ln" id="txtContrato_Ln" >
				</td>
				<td class="formulario">
				<select class="inputxt" style="width:200px" name="cmbContrato_Ln" id="cmbContrato_Ln">
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
					<option>CARONI</option>
					<option disabled="true">CARIBE</option>
					<option>MERCANTIL</option>
					<option disabled="true">INTER-BANCARIO</option>
				</select>
				</td>
				<td class="formulario">
				<button onclick="Respaldo_Modificar_Linaje();" >Modificar</button>
				</td>
			</tr>
		</table> </div> </td>
	</tr>
</table>
<br>
				
				
				
			<center>
			</center>
		</div>
	</body>
</html>
