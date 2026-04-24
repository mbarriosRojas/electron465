<html>
<head>
	<?php $this->load->view("incluir/cabezera.php");?>
	<link href="<?php echo __CSS__ ?>lightbox/lightbox.css" rel="stylesheet"  type="text/css">
	<link rel="stylesheet" href="<?php echo __CSS__ ?>lightbox/screen1.css" type="text/css" media="screen" /> 
	<link href="<?php echo __CSS__ ?>tgrid/TGrid.css" rel="stylesheet" type="text/css" media="screen"/>
	<link href="<?php echo __CSS__ ?>tgrid/TGridPrint.css" rel="stylesheet" type="text/css" media="print"/>
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>gvistas/gvista2.js"></script>
 	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>view/verificar.js"></script>		
</head>
<body>
	<div id="cabecera"><br>
		<?php $this->load->view("menu/buscar.php");?>
		<?php echo $Menu;?>
	</div>		
	<div id="medio" >	
		<div id="msj_alertas"></div>
			<h2 class="demoHeaders">	Solicitudes: Enviar Requisitos al Cliente</h2><br>
			<h3>Usuario Conectado: <?php echo $this->session->userdata('usuario'); ?> Fecha: <?php echo date("Y/m/d"); ?></h3><br>
			<center>
			
			<br><br>
		       <table style='width:600px'>
	        		<tr><td style='width:180px'>Seleccione Documentos</td>
					<td><input type="checkbox" id='ula' /></td><td>NOMINA DE LA ULA</td>
					<td><input type="checkbox" id='nomina' /></td><td>NOMINAS ESTADALES</td>
					</tr>
					
					<tr>
					<td style='width:180px'></td>
					<td><input type="checkbox" id='gvene' /></td><td>VENEZUELA GRUPO</td>
					<td><input type="checkbox" id='unive' /></td><td>NOMINA DEL UNIVERSAL</td>
					
					</tr>
	        		</table>
	        		<br><br>
	        		<hr>
	        		<br><br>
	        	<table style='width:600px'>
	        		<tr><td style='width:180px'>Ingrese Correo Electronico</td>
					<td><input type="text" id='correo' /></td><td align="left"><button onclick='enviar();'>Enviar Correo</button></td></tr>
		       </table>
			   
						
				
			</div>
			
			</center>
		</div>
		
	</body>
</html>
