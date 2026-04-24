<html>
<head>
	<?php $this->load->view("incluir/cabezera.php");?>
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>view/descargas.js"></script>	
</head>
<body>
	<div id="cabecera"><br>
		<?php $this->load->view("menu/buscar.php");?>
		<?php echo $Menu;?>
	</div>	
	<div id="medio" >	
		<div id="msj_alertas"></div>
			<h2 class="demoHeaders">Descargas Electron </h2><br>
			<h3>Usuario Conectado: <?php echo $this->session->userdata('usuario'); ?> Fecha: <?php echo date("Y/m/d"); ?></h3><br>
				
			<?php $this->load->view("reportes/tbldescargas");?>	
				
			<center>
			</center>
		</div>
	</body>
</html>
