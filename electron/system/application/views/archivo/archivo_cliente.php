<html>
<head>
	<link href="<?php echo __CSS__ ?>tgrid/TGrid.css" rel="stylesheet" type="text/css" media="screen"/>
	<link href="<?php echo __CSS__ ?>tgrid/TGridPrint.css" rel="stylesheet" type="text/css" media="print"/>
	
	<?php $this -> load -> view("incluir/cabezera.php"); ?>
	
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>tgrid/func.js"></script>
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>tgrid/tgrid.js"></script>
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>tgrid/paginador.js"></script>
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>tgrid/xls.js"></script>	
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>gvistas/gvista.js"></script>
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>view/archivo.js"></script>
</head>
<body>
	<div id="cabecera"><br>
		<?php $this -> load -> view("menu/buscar.php"); ?>
		<?php echo $Menu; ?>
	</div>	
	<div id="medio" >	
		<div id="msj_alertas"></div>
		<h2 class="demoHeaders">Archivo (Historial General de Clientes)</h2><br>
		<h3>Usuario Conectado: <?php echo $this -> session -> userdata('usuario'); ?> Fecha: <?php echo date("Y/m/d"); ?></h3><br><br>
		<br><br>
		<form class="iconos2"  onsubmit="return guardar();" method="post" action="#">
			<div id="formulario" style="width: 100%"></div>
			<div id='botones'></div>
		</form>
		<br><br><h2>Historial:</h2><br>
		<div id='historial'></div>
	</div>
</body>
</html>