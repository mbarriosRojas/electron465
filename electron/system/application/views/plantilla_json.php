<html>
<head>
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>jquery/jquery-1.7.2.min.js"></script>
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>gvistas/plantilla.js"></script>	
</head>
<body>
	<div id="medio" >	
		<div id="msj_alertas"></div>
			<h2 class="demoHeaders">	Panel de Control </h2><br>
			<h3>Usuario Conectado: <?php echo $this->session->userdata('usuario'); ?> Fecha: <?php echo date("Y/m/d"); ?></h3><br>
				
			<?php $this->load->view("plantilla/crear.php");?>	
				
			<center>
			</center>
		</div>
	</body>
</html>
