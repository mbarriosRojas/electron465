<html>
<head>
	<?php $this->load->view("incluir/cabezera.php");?>
	<link href="<?php echo __CSS__ ?>tgrid/TGrid.css" rel="stylesheet" type="text/css" media="screen"/>
	<link href="<?php echo __CSS__ ?>tgrid/TGridPrint.css" rel="stylesheet" type="text/css" media="print"/>
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>gvistas/gvista2.js"></script>
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>view/solicitud.js"></script>	
</head>
<body>
	<div id="cabecera"><br>
		<?php $this->load->view("menu/buscar.php");?>
		<!-- <iframe src="https://kiwiirc.com/client/electron465.com/#Cobranza" style="border:0; width:100%; height:450px;"></iframe> -->
		<?php echo $Menu;?>
	</div>		
	
	<div id="medio" >	
		<div id="msj_alertas"></div>
			<h3>Usuario Conectado: <?php echo $this->session->userdata('usuario'); ?> Fecha: <?php echo date("Y/m/d"); ?></h3><br>	
			<center>
			<h1>Solicitudes</h1>
			<div id="tabs" style="width:720px"> 
			    <ul>
		        <li><a href="#tabs-1">Registro</a></li>
			    </ul>						    							    	
				<div id='tabs-1' >																		
					<div id="vista"></div>				
				</div>
			</div>
			
			</center>
		</div>
		
	</body>
</html>
