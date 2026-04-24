<html>
<head>
	<?php $this->load->view("incluir/cabezera.php");?>
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>view/panel.js"></script>	
</head>
<body>
	<div id="cabecera"><br>
		<?php $this->load->view("menu/buscar.php");?>
		<?php echo $Menu;?>
	</div>
	<div id="medio" >	
		<div id="r_cedula"><?php $this->load->view("panel/formularios/respaldo_ced.php");?></div>
		<div id="r_boucher"><?php $this->load->view("panel/formularios/respaldo_boucher.php");?></div>
		<div id="r_contrato"><?php $this->load->view("panel/formularios/respaldo_cont.php");?></div>
		<div id="r_factura"><?php $this->load->view("panel/formularios/respaldo_fact.php");?></div>
		<div id="r_inventario"><?php $this->load->view("panel/formularios/respaldo_inv.php");?></div>
		<div id="r_exonerado"><?php $this->load->view("panel/formularios/r_exonerado.php");?></div>
		
		<div id="msj_alertas"></div>
			<h2 class="demoHeaders">	Panel de Control </h2><br>
			<h3>Usuario Conectado: <?php echo $this->session->userdata('usuario'); ?> Fecha: <?php echo date("Y/m/d"); ?></h3><br>
				<div id="tabs" style="width:780px"> 
			    <ul>
		        <li><a href="#tabs-1">Clientes</a></li>
			    </ul>						    							    	
					<div id='tabs-1' >																		
						<?php $this->load->view("panel/cliente.php");?>
					</div>
				</div>
				
				
				
			<center>
			</center>
		</div>
	</body>
</html>
