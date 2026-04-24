<html>
<head>
	<link href="<?php echo __CSS__ ?>tgrid/TGrid.css" rel="stylesheet" type="text/css" media="screen"/>
	<link href="<?php echo __CSS__ ?>tgrid/TGridPrint.css" rel="stylesheet" type="text/css" media="print"/>
	
	<?php $this -> load -> view("incluir/cabezera.php"); ?>
	
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>tgrid/func.js"></script>
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>tgrid/tgrid.js"></script>
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>tgrid/paginador.js"></script>
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>tgrid/xls.js"></script>	
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>gvistas/gvista2.js"></script>
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>estatico/gv_ubicacion.js"></script>
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>view/ubicacion.js"></script>
	
</head>
<body>
	<div id="cabecera"><br>
		<?php $this -> load -> view("menu/buscar.php"); ?>
		<?php echo $Menu; ?>
	</div>
	<div id="medio" >	
		<div id="msj_alertas"></div>
		<h2 class="demoHeaders">Panel De Ubicaciones</h2><br>
		<h3>Usuario Conectado: <?php echo $this -> session -> userdata('usuario'); ?> Fecha: <?php echo date("Y/m/d"); ?></h3><br><br>
		<br><br>
		
			<div id="formulario" style="width: 100%"></div>
			<br><br><br>
			<div id="lista" style="width: 100%"></div>
		
	</div>
</body>
</html>