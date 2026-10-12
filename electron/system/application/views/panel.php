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
		        <li><a href="#tabs-2">Nominas</a></li>
		        <li><a href="#tabs-3">Contratos</a></li>
		        <li><a href="#tabs-4">Facturas</a></li>
		         <?php if($Nivel == 0 || $Nivel == 3){?>
		          <li><a href="#tabs-5">Inventario</a></li>
            <?php }?>
		        <li><a href="#tabs-6">Voucher</a></li>
					<li><a href="#tabs-insti">Instituciones</a></li>
		        <?php if($Nivel == 0 || $Nivel == 3){?>
		        <li><a href="#tabs-7">Bloqueo</a></li>
		        <?php }?>
			    </ul>						    							    	
					<div id='tabs-1' >																		
						<?php $this->load->view("panel/cliente.php");?>
					</div>
					<div id='tabs-2' >																		
						<?php $this->load->view("panel/nomina.php");?>
					</div>
					<div id='tabs-3' >																		
						<?php $this->load->view("panel/contrato.php");?>
					</div>
					<div id='tabs-4' >																		
						<?php $this->load->view("panel/factura.php");?>
					</div>					
					 <?php if($Nivel == 0 || $Nivel == 3){?>
          <div id='tabs-5' >                                    
            <?php $this->load->view("panel/inventario.php");?>  
          </div>
            <?php }?>			
					
					<div id='tabs-6' >																		
							<?php $this->load->view("panel/boucher.php");?>
					</div>
					<?php if($Nivel == 0 || $Nivel == 3 || $Nivel == 18){?>
					<div id='tabs-7' >																		
						<?php $this->load->view("panel/bloqueo.php");?>	
					</div>
					<?php } ?>
					<div id='tabs-insti' >
						<?php $this->load->view("panel/insti.php");?>
					</div>
				</div>
				
				
				
			<center>
			</center>
		</div>
	</body>
</html>
