
<html>
<head>
	<link href="<?php echo __CSS__ ?>tgrid/TGrid.css" rel="stylesheet" type="text/css" media="screen"/>
	<link href="<?php echo __CSS__ ?>tgrid/TGridPrint.css" rel="stylesheet" type="text/css" media="print"/>
	<link href="<?php echo __CSS__ ?>lightbox/lightbox.css" rel="stylesheet"  type="text/css">
 	<?php $this -> load -> view("incluir/cabezera.php"); ?>
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>gvistas/gvista2.js"></script>
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>view/rep_modificaciones.js"></script>
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>tgrid/func.js"></script>
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>tgrid/tgrid.js"></script>
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>tgrid/paginador.js"></script>
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>tgrid/xls.js"></script>

  </head>
<body>
	<div id="cabecera"><br>
		<?php $this -> load -> view("menu/buscar.php"); ?>
		<?php echo $Menu; ?>
	</div>
 	<div id="medio"  style="width:80%">
		<div id="msj_alertas"></div>
	  	<h2>Reportes De Modificaciones</h2><br>
	  	<h3>Usuario Conectado: <?php echo $this -> session -> userdata('usuario'); ?> Fecha: <?php echo date("Y/m/d"); ?></h3><br>
	  	<div id="mnu_Reportes" title="">
            <table class="table">
                <tr><td>Desde</td><td><input type="text" id="desde" name="desde"></td><td>Hasta</td><td><input type="text" id="hasta" name="hasta"></td></tr>
                <tr><td>Tipo De modificacion</td><td><select id="tipo" name="tipo"><?=$tipo?></select></td><td><button id="btn-buscar">Buscar</button></td></tr>
  			</table>
  		</div>
	  	<div id="Reportes" ></div>
	</div>
</body>
</html>
