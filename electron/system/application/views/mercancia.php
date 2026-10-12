<html>
<head>
	<link href="<?php echo __CSS__ ?>tgrid/TGrid.css" rel="stylesheet" type="text/css" media="screen"/>
	<link href="<?php echo __CSS__ ?>tgrid/TGridPrint.css" rel="stylesheet" type="text/css" media="print"/>
	<?php $this->load->view("incluir/cabezera.php");?>
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>view/mercancia.js"></script>
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>tgrid/func.js"></script>
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>tgrid/tgrid.js"></script>
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>tgrid/paginador.js"></script>
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>tgrid/xls.js"></script>	
</head>
<body>
	<?php
		$tab1 = 'Registrar Mercancia';
		$tab2 = 'Agregar Seriales a Mercancia';
		$tab3 = 'Modificar Mercancia';
		$tab4 = 'Reporte Entregas';
		if($tipo != 0){
			$tab1 = 'Registrar Material De Oficina';
			$tab2 = 'Agregar Seriales a M. De Oficina';
			$tab3 = 'Modificar M. De Oficina';
			$tab4 = 'Reporte Entregas M. De Oficina';
		}
		
	?>
	<input type="hidden" name="tipoV" id="tipoV" value=<?php echo $tipo; ?> />
	<div id="cabecera"><br>
		<?php $this->load->view("menu/buscar.php");?>
		<?php echo $Menu;?>
	</div>		
	<div id="medio" >
		<div id="r_mercancia"><?php //$this->load->view("panel/formularios/respaldo_merc.php");?></div>	
		<div id="msj_alertas"></div>
			<h2 class="demoHeaders">	Control de Mercancia </h2><br>
			<h3>Usuario Conectado: <?php echo $this->session->userdata('usuario'); ?> Fecha: <?php echo date("Y/m/d"); ?></h3><br>
				<div id="tabs" style="width:740px"> 
			    <ul>
		        <li><a href="#tabs-1"><?php echo $tab1;?></a></li>
		        <li><a href="#tabs-2"><?php echo $tab2;?></a></li>
		        <li><a href="#tabs-3"><?php echo $tab3;?></a></li>
		        <li><a href="#tabs-4"><?php echo $tab4;?></a></li>
			    </ul>						    							    	
					<div id='tabs-1' >																		
							<?php $this->load->view("mercancia/registrar.php");?>
					</div>
					<div id='tabs-2' >																		
							<?php $this->load->view("mercancia/registrar_serial.php");?>
					</div>
					<div id='tabs-3' >																		
							<div id="Actualizar" style="width:100%; Height:100%"></div>
					</div>
					<div id='tabs-4' >																		
						<div id="Reporte_Entregas" style="width:100%; Height:100%"></div>
					</div>
					
				</div>
		</div>
	</body>
</html>
