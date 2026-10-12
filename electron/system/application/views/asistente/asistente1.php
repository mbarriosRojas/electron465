<html>
<head>
	<?php $this -> load -> view("incluir/cabezera.php"); ?>
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>gvistas/gvista.js"></script>
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>view/asistente1.js"></script>
</head>
<body>
	<div id="cabecera"><br>
		<?php $this -> load -> view("menu/buscar.php"); ?>
		<?php echo $Menu; ?>
	</div>
	<div id="medio" >	
		<div id="msj_alertas"></div>
		<h2 class="demoHeaders">Asistente para elaboracion de contratos</h2><br>
		<h3>Usuario Conectado: <?php echo $this -> session -> userdata('usuario'); ?> Fecha: <?php echo date("Y/m/d"); ?></h3><br><br>
		<img src="<?php echo __IMG__ ?>menu/afiliar.png">
		<form class="iconos2"  onsubmit="return guardar();" method="post" id="login_form" action="<?php echo __LOCALWWW__ ?>/index.php/cooperativa/CEndeudamiento">
			<div id="formulario" style="width: 100%"></div>
			<div id='botones'></div>
		</form>
	</div>
</body>
</html>