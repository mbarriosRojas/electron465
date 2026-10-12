
<html>
<head>
	<link href="<?php echo __CSS__ ?>tgrid/TGrid.css" rel="stylesheet" type="text/css" media="screen"/>
	<link href="<?php echo __CSS__ ?>tgrid/TGridPrint.css" rel="stylesheet" type="text/css" media="print"/>
	<link href="<?php echo __CSS__ ?>lightbox/lightbox.css" rel="stylesheet"  type="text/css">
 	<?php $this -> load -> view("incluir/cabezera.php"); ?>
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>gvistas/gvista2.js"></script>
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>view/rep_gen.js"></script>
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>tgrid/func.js"></script>
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>tgrid/tgrid.js"></script>
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>tgrid/paginador.js"></script>
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>tgrid/xls.js"></script>
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>lightbox/lightbox.js"></script>
	<script  type="text/javascript" src="<?php echo __JSVIEW__ ?>graf/jquery.jqplot.min.js"></script>
	<script  type="text/javascript" src="<?php echo __JSVIEW__ ?>graf/plugins/jqplot.pieRenderer.min.js"></script>
	<link rel="stylesheet" type="text/css" href="<?php echo __CSS__ ?>jquery.jqplot.min.css" />
  </head>
<body>
	<div id="cabecera"><br>
		<?php $this -> load -> view("menu/buscar.php"); ?>
		<?php echo $Menu; ?>
	</div>
 	<div id="medio"  style="width:80%"> 
		<div id="msj_alertas"></div>
	  	<h2>Reportes</h2><br>
	  	<h3>Usuario Conectado: <?php echo $this -> session -> userdata('usuario'); ?> Fecha: <?php echo date("Y/m/d"); ?></h3><br>
	  	<div id="mnu_Reportes" title="">
				<table >
  				<tr>
  					<td align="center"><img src="<?php echo __IMG__ ?>reportes/factura.png" style='width:48px' onClick="muestra_div('ffactura');"/><br>POR FACTURAS</td>
  					<td style="width: 50px"></td>
  					<td align="center"><img src="<?php echo __IMG__ ?>reportes/busca_contrato.jpg" style='width:48px' onClick="muestra_div('fcontrato');"/><br>POR CONTRATOS</td>
  				</tr>
  			</table>
  		</div>
	  	<div id="ffactura" class="dialogo"></div>
	  	<div id="fcontrato" class='dialogo'></div>	
	  	<div id="Reportes" ></div>
		<div id='graf' style="display:none;float: left;top:100px;left:50%;position:absolute;width:300px;"><div id='torta' ></div><div style="float:right;"><img src="<?php echo __IMG__ ?>botones/close.png" onclick="$('#graf').hide();"></img></div></div>
	</div>
</body>
</html>
