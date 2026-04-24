<html>
<head>
	<?php $this->load->view("incluir/cabezera.php");?>
	<link href="<?php echo __CSS__ ?>lightbox/lightbox.css" rel="stylesheet"  type="text/css">
	<link rel="stylesheet" href="<?php echo __CSS__ ?>lightbox/screen1.css" type="text/css" media="screen" /> 
 	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>lightbox/lightbox.js"></script>
 	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>view/lightbox.js"></script>
 	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>view/subir.js"></script>		
</head>
<body>
	<div id="cabecera"><br>
		<?php $this->load->view("menu/buscar.php");?>
		<!-- <iframe src="https://kiwiirc.com/client/electron465.com/#Cobranza" style="border:0; width:100%; height:450px;"></iframe> -->
		<?php echo $Menu;?>
	</div>		
	<div id="medio" >	
		<div id="msj_alertas"></div>
			<h2 class="demoHeaders">	Panel de Control </h2><br>
			<h3>Usuario Conectado: <?php echo $this->session->userdata('usuario'); ?> Fecha: <?php echo date("Y/m/d"); ?></h3><br>
				
			<div>
				<label>INGRESE CEDULA</label>
				<input type="text" id = 'ced' name='ced' style="text-transform:lowercase;" />
				<label>INGRESE CERTIFICADO</label>
				<input type="text" id = 'lugar' name='lugar' style="text-transform:lowercase;" />
				<a href="#" onclick="consultar_imagenes();" >Consultar</a>
			</div>	
			<center>
			<div class="imageRow">
  				<div class="set" id='imagenes0' >
  					
					<br>						
  				</div>
  			</div>
			
			</center>
		</div>
		
	</body>
</html>
