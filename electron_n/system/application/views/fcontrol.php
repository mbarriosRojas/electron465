<html>
<head>	
	<?php $this -> load -> view("incluir/cabezera.php"); ?>	
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>gvistas/gvista.js"></script>
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>view/fcontrol.js"></script>
</head>
<body>
	<div id="cabecera"><br>
		<?php $this -> load -> view("menu/buscar.php"); ?>
		<?php echo $Menu; ?>
	</div>	
	<div id="medio" >
		<input type="hidden" value='0' id='tpe'/>	
		<div id="msj_alertas"></div>
		<h2 class="demoHeaders">Factura Control</h2><br>
		<h3>Usuario Conectado: <?php echo $this -> session -> userdata('descripcion'); ?> Fecha: <?php echo date("Y/m/d"); ?></h3><br><br>
		<form class="iconos2"  onsubmit="return guardar();" method="post" action="#">
			<div id="formulario" style="width: 100%"></div>
			<div id='botones'></div>
		</form>
	</div>
</body>
</html>