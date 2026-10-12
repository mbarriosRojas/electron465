<!DOCTYPE html>
<html>
<head>
	<link href="<?php echo __CSS__ ?>tgrid/TGrid.css" rel="stylesheet" type="text/css" media="screen"/>
	<link href="<?php echo __CSS__ ?>vista.css" rel="stylesheet" type="text/css" media="screen"/>
	<link href="<?php echo __CSS__ ?>tgrid/TGridPrint.css" rel="stylesheet" type="text/css" media="print"/>
	<?php $this -> load -> view("incluir/cabezera.php"); ?>
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>view/vista.js"></script>
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>gvistas/gvista.js"></script>
</head>
<body >
	<div id="cabecera"><br>
		<?php $this -> load -> view("menu/buscar.php"); ?>
		<?php echo $Menu; ?>
	</div>		
	<div id="lateral_i"> <center><br>
		<?php $this -> load -> view("menu/lateral.php"); ?>
	</div>
	<div id="medio" >
		<div id="msj_alertas"></div>	
			<h2> Generador de vistas</h2><br>
			<h3>Usuario Conectado: <?php echo $this -> session -> userdata('descripcion'); ?> Fecha: <?php echo date("Y/m/d"); ?></h3><br>
			<form id='formulario1' method="post" class='iconos2'>					
			<div id="formulario">
				  
				  	
				  
			</div>
			
			<div id='botones'></div>
			</form>
	</div>
</body>
</html>
