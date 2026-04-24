<html>
	<head>
		<title>Consulta de Clientes</title>
		<link href="<?php echo __CSS__ ?>tgrid/TGrid.css" rel="stylesheet" type="text/css" media="screen"/>
		<link href="<?php echo __CSS__ ?>elctron.css" rel="stylesheet" type="text/css" media="screen"/>
		<link href="<?php echo __CSS__ ?>tgrid/TGridPrint.css" rel="stylesheet" type="text/css" media="print"/>
	
		<?php $this -> load -> view("incluir/cabezera.php"); ?>
	
		<script type="text/javascript" src="<?php echo __JSVIEW__ ?>tgrid/func.js"></script>
		<script type="text/javascript" src="<?php echo __JSVIEW__ ?>tgrid/tgrid.js"></script>
		<script type="text/javascript" src="<?php echo __JSVIEW__ ?>tgrid/paginador.js"></script>
		<script type="text/javascript" src="<?php echo __JSVIEW__ ?>electron/consultar.js"></script>
	</head>
	
	<body onload="Consultar('<?php echo $cedula; ?>');">
		<div id="contenedor">
			<div id="cabecera2">
				<div id="foto">
					<img src="<?php echo __IMG__ ?>logoF.png" alt="solicita"/>
				</div>
			</div>

			<div id="menu">
<ul id="button">
<li><a href="<?php echo base_url() . 'index.php/electron/logout' ?>">Cerrar Sesion</a></li>
<li style="font-weight:bold;"><a>USUARIO CONECTADO: <?php echo $cedula; ?></a></li>
</ul>
</div>
<div style="position:absolute; float:left; left:8%;top:220px;font-size: 13px;color:#2175bc;font-weight: bold;">
Estimado Cliente.<br><br>
Bienvenido a Electr&oacute;n en linea, recuerda que cerrar sesion para salir del sistema.
</div>

					<div id="tabla" style="position: relative;float:left;left:90px;"></div>

		</div>
	</body>
</html>