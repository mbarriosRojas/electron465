<html>
<head>
	<link href="<?php echo __CSS__ ?>tgrid/TGrid.css" rel="stylesheet" type="text/css" media="screen"/>
	<link href="<?php echo __CSS__ ?>tgrid/TGridPrint.css" rel="stylesheet" type="text/css" media="print"/>
	<?php $this->load->view("incluir/cabezera.php");?>
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>view/contado.js"></script>
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>tgrid/func.js"></script>
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>tgrid/tgrid.js"></script>
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>tgrid/paginador.js"></script>
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>tgrid/xls.js"></script>	
</head>
<body>
	
	<div id="cabecera"><br>
		<?php $this->load->view("menu/buscar.php");?>
		<?php echo $Menu;?>
	</div>	
	<div id="medio" >
		<div id="r_entrega_mercancia"></div>	
		<div id="msj_alertas"></div>
			<h2 class="demoHeaders">Venta De Contado</h2><br>
			<h3>Usuario Conectado: <?php echo $this->session->userdata('usuario'); ?> Fecha: <?php echo date("Y/m/d"); ?></h3><br>
				 
			    						    							    	
					<div>																		
							<?php $this->load->view("inventario/venta_contado");?>
					</div>
					
				
		</div>
	</body>
</html>