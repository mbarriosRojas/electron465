<html>
<head>
	<?php $this->load->view("incluir/cabezera.php");?>
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>view/configurar.js"></script>	
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
				
			<?php $this->load->view("usuario/principal.php");?>	
				
			<center>
			</center>
		</div>
		
	</body>
</html>
