<html>
<head>
	<link href="<?php echo __CSS__ ?>tgrid/TGrid.css" rel="stylesheet" type="text/css" media="screen"/>
	<link href="<?php echo __CSS__ ?>tgrid/TGridPrint.css" rel="stylesheet" type="text/css" media="print"/>
	<?php $this->load->view("incluir/cabezera.php");?>
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>view/entregas.js"></script>
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>tgrid/func.js"></script>
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>tgrid/tgrid.js"></script>
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>tgrid/paginador.js"></script>
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>tgrid/xls.js"></script>	
</head>
<body>
	<?php
	$tab1 = "Mercancia";
	$titulo = "Control De Mercancia";
	if($tipo != 0){
		$tab1 = "Material De Oficina";
		$titulo = "Control De Material de Oficina";
	} 
	?>
	<div id="cabecera"><br>
		<?php $this->load->view("menu/buscar.php");?>
		<?php echo $Menu;?>
	</div>		
	<div id="medio" >
		<input type="hidden" name="tipoV" id="tipoV" value=<?php echo $tipo; ?> />
		<div id="r_entrega_mercancia"><?php //$this->load->view("panel/formularios/respaldo_merc.php");?></div>	
		<div id="msj_alertas"></div>
			<h2 class="demoHeaders"><?php echo $titulo;?></h2><br>
			<h3>Usuario Conectado: <?php echo $this->session->userdata('usuario'); ?> Fecha: <?php echo date("Y/m/d"); ?></h3><br>
				<div id="tabs" style="width:720px;height: 480px;"> 
			    <ul>
		        <li><a href="#tabs-1"><?php echo $tab1;?></a></li>
		        
			    </ul>						    							    	
					<div id='tabs-1' >																		
							<?php $this->load->view("mercancia/f_entrega_mercancia.php");?>
					</div>
					
				</div>
		</div>
	</body>
</html>