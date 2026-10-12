<html>
<head>
	<link href="<?php echo __CSS__ ?>tgrid/TGrid.css" rel="stylesheet" type="text/css" media="screen"/>
	<?php $this->load->view("incluir/cabezera.php");?>
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>view/inventario.js"></script>
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>tgrid/func.js"></script>
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>tgrid/tgrid.js"></script>
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>tgrid/paginador.js"></script>	
</head>
<body>
	<div id="cabecera"><br>
		<?php
			$usu = $this -> session -> userdata('usuario');
			if(strtolower($usu)!= 'mercancia')$this->load->view("menu/buscar.php");
		?>
		<?php echo $Menu;?>
	</div>
	<div id="medio" >
		<div id="r_inventario"><?php $this->load->view("panel/formularios/respaldo_inv.php");?></div>	
		<div id="msj_alertas"></div>
			<h2 class="demoHeaders">	Control de Inventario </h2><br>
			<h3>Usuario Conectado: <?php echo $this->session->userdata('usuario'); ?> Fecha: <?php echo date("Y/m/d"); ?></h3><br>
				<div id="tabs" style="width:720px"> 
			    <ul>
		        <li><a href="#tabs-1">Registrar</a></li>
		        <li><a href="#tabs-2">Eliminar</a></li>
		        <li><a href="#tabs-4">Modificar</a></li>
		        <li><a href="#tabs-3">Chequera</a></li>
			    </ul>						    							    	
					<div id='tabs-1' >
							<?php
							 
							if(strtolower($usu) == 'alvaro' || strtolower($usu) == 'alvaroz'|| strtolower($usu) == 'judelvis'){ 
								$this->load->view("inventario/principal.php");
							}
							if(strtolower($usu) == 'mercancia'){
								$this->load->view("inventario/principal_mercancia.php");
							}
							?>																		
							<?php //$this->load->view("inventario/principal.php");?>
					</div>
					<div id='tabs-2' >																		
							<?php
							if(strtolower($usu) == 'alvaro'){ 
								$this->load->view("panel/inventario.php");
							}
							?>
					</div>
					<div id='tabs-4' >																		
							<?php
							//$this->load->view("panel/inventario_mod.php");
							if(strtolower($usu)== 'alvaro'){ 
								$this->load->view("panel/inventario_mod.php");
							}
							?>
					</div>
					<div id='tabs-3' >																		
							<?php
							if(strtolower($usu)== 'alvaro'){ 
								$this->load->view("inventario/chequera.php");
							}
							?>
					</div>
					
				</div>
				
				
			<center>
			</center>
		</div>
	</body>
</html>
