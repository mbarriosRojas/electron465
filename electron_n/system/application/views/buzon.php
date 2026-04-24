<html>
<head>
	<link href="<?php echo __CSS__ ?>tgrid/TGrid.css" rel="stylesheet" type="text/css" media="screen"/>
	
	<?php $this->load->view("incluir/cabezera.php");?>
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>view/buzon.js"></script>
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
		<div id="cobros" title="Detalles del Cobro (Estado de Cuenta)"><?php  	
  			$this->load->view("procesar/lista_programadas.php");
  		?></div>
		<div id="msj_alertas"></div>	
			<h2> Buzon de Actividades Generales</h2><br>
			<h3>Usuario Conectado: <?php echo $this->session->userdata('descripcion'); ?> Fecha: <?php echo date("Y/m/d"); ?></h3><br>					
			<div id="tabs" style="width:750px;height:500px">
				
				  <ul>
		        	<li><a href="#tabs-1">Creditos Pendientes</a></li>
		        	<li><a href="#tabs-2">Cuotas Por Cobrar Programadas</a></li>
		        	<li><a href="#tabs-3">Rechazos Pendientes (X)</a></li>
		        	<li><a href="#tabs-4">Envio de Aceptaciones Por Revision</a></li>
			    </ul>
			    <div id='tabs-1' style="padding: 0 0 0 0; background-color: #fff">							
							<div id="Pendientes" style="width:100%;heigth:100%">  </div>
					</div>
				<div id="tabs-2" style="padding: 0 0 0 0; background-color: #fff">
							<div id="C_Programadas" style="width:100%;heigth:100%">  </div>
				</diV>
				<div id="tabs-3" style="padding: 0 0 0 0; background-color: #fff">
							<div id="Rechazos" style="width:100%;heigth:100%">  </div>			
				</diV>
				<div id="tabs-4">
						<div id="PendientesRevision" style="width:100%;heigth:100%">  </div>						
				</diV>
			</div>
	</div>
</body>
</html>
