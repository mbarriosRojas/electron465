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
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>view/fpresupuesto.js"></script>
</head>
<body>
	<div id="cabecera"><br>
		<?php $this -> load -> view("menu/buscar.php"); ?>
		<?php echo $Menu; ?>
	</div>	
	<div id="medio" >	
		<div id="msj_alertas"></div>
		<h2 class="demoHeaders">Generar Factura Presupuesto</h2><br>
		<h3>Usuario Conectado: <?php echo $this -> session -> userdata('usuario'); ?> Fecha: <?php echo date("Y/m/d"); ?></h3><br><br>
		<input type="hidden" id="txtCed" value="<?php echo $ced?>"/>
		<input type="hidden" id="txtCert" value="<?php echo $cert?>"/>
		<input type="hidden" id="txtEmp" value="<?php echo $emp?>"/>
		<input type="hidden" id="txtMon" value="<?php echo $mon?>"/>
		
		<img src="<?php echo __IMG__ ?>menu/paso4.png">
		
		<form class="iconos2"  onsubmit="return guardar();" method="post" action="#">
			<div id="formulario" style="width: 100%"></div>
			<div id='botones'></div>
		</form>
	</div>
</body>
</html>