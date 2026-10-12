<!DOCTYPE html>
<html>
<head>
	<link href="<?php echo __CSS__ ?>tgrid/TGrid.css" rel="stylesheet" type="text/css" media="screen"/>
	<link href="<?php echo __CSS__ ?>tgrid/TGridPrint.css" rel="stylesheet" type="text/css" media="print"/>
	<?php $this->load->view("incluir/cabezera.php");?>
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>view/deposito.js"></script>
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>tgrid/func.js"></script>
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>tgrid/tgrid.js"></script>
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>tgrid/paginador.js"></script>
</head>
<body >
	<div id="cabecera"><br>
		<?php $this->load->view("menu/buscar.php");?>
		<?php echo $Menu;?>
	</div>	
	<div id="medio" >
		<div id="msj_alertas"></div>	
			<h2> Buzon de Facturas Por Liquidar</h2><br>
			<h3>Usuario Conectado: <?php echo $this->session->userdata('descripcion'); ?> Fecha: <?php echo date("Y/m/d"); ?></h3><br>					
			<div id="tabs" style="width:750px;height:500px">
				 <ul>
		        	<li><a href="#tabs-1">Facturas Pendientes</a></li>
			    </ul>
			    <div id='tabs-1' style="padding: 0 0 0 0; background-color: #fff">							
					<div id="Pendientes" style="heigth:100%">  </div>
				</div>
				
			</div>
	</div>
</body>
</html>
