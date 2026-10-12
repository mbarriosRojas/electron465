<html>
<head>
	<?php $this->load->view("incluir/cabezera.php");?>
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>view/clientes.js"></script>	
</head>
<body>
	<div id="cabecera"><br>
		<?php $this->load->view("menu/buscar.php");?>
		<?php echo $Menu;?>
	</div>		
	<div id="medio" >	
		<div id="msj_alertas"></div>
		<div id="r_suspender"><?php $this->load->view("panel/formularios/respaldo_ced.php");?></div>
			<h2 class="demoHeaders">	Planilla General de Clientes </h2><br>
			<h3>Usuario Conectado: <?php echo $this->session->userdata('usuario'); ?> Fecha: <?php echo date("Y/m/d"); ?></h3><br>					
				<div id="tabs" style="width:720px"> 
			    <ul>
		        <li><a href="#tabs-1">Datos B&aacute;sico</a></li>
		        <li><a href="#tabs-3">Datos de Refencia</a></li>
		        <li><a href="#tabs-2">Datos del Cr&eacute;dito</a></li>
		        <li><a href="#tabs-4">Control de Historial</a></li>
			    </ul>						    							    	
					<div id='tabs-1' >																		
							<?php $this->load->view("cliente/datosbasicos.php");?>
					</div>
					<div id='tabs-2'>
						<?php $this->load->view("cliente/datoscreditos.php");?>									
					</div>
					<div id='tabs-3'>									
							<?php $this->load->view("cliente/asociados.php");?>									
					</div>
					<div id='tabs-4'>									
						<div class="ui-widget" style="display:none;" id='divListaCreditos'>
							<div class="ui-state-highlight ui-corner-all" style="margin-top: 10px; padding: .8em;" id='divListaCreditosInformacion'></div>
						</div>
						<div class="ui-widget" style="display:none;" id='divCreditos'>
							<div class="ui-state-highlight ui-corner-all" style="margin-top: 10px; padding: .8em;" id='divCreditosInformacion'></div>
						</div>						
					</div>
				</div><br>						
			<center>
				<div id="BtnClientes"></div>
			</center>
		</div>
	</body>
</html>
