
<html>
<head>

	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>view/reportes.js"></script>
  </head>
<body>
	<div id="cabecera"><br>
		<?php $this->load->view("menu/buscar.php");?>
		<?php echo $Menu;?>
	</div>	
  <div id="medio" >	
	  <h2>Historial de Facturas Por Aceptar</h2><br>
	  <h3>Usuario Connectado: <?php echo $this->session->userdata('usuario'); ?> Fecha: <?php echo date("Y/m/d"); ?></h3><br>	
	  
	  		<br>
			<div id="reportes" title="Detalles del credito">
				<table >
  				<tr>
  					<td align="center"><img src="<?php echo __IMG__ ?>reportes/factura.png" style='width:64px' onClick="muestra_div('facturas_pendientes');"/><br>FACTURAS POR ACEPTAR</td>
  					<td style="width: 50px"></td>
  					<td align="center"><img src="<?php echo __IMG__ ?>reportes/historial.png" style='width:64px' onClick="muestra_div('muestra_historial_clientes');"/><br>HISTORIAL DE CLIENTES</td>
  					<td style="width: 50px"></td>
  					<td align="center"><img src="<?php echo __IMG__ ?>reportes/clientes.png" style='width:64px' onClick="muestra_div('muestra_clientes_atendidos');"/><br>CLIENTES ATENDIDOS</td>	
  				</tr>
  			</table>	
				<div id="facturas_pendientes" class="dialogo"  title="Facturas Pendientes Por Aceptar">
					<br>
					<?php
						if($Nivel == 0){
							$this->load->view(__NFORMULARIOS__ . "frmfacturas_pendientes.php");
						}else {
							//Usuario Sin Nivel de Acceso
							$this->load->view(__NFORMULARIOS__ . "frmReporteGenerico.php");
						}
					?>
				</div>
				<div id="muestra_historial_clientes" class="dialogo" title="Historial de Clientes en Facturas">
					<br>
					<?php $this->load->view(__NFORMULARIOS__ . "frmhistorial_cliente.php");?>
				</div>

				<div id="muestra_clientes_atendidos" class="dialogo" title="Historial de Cliente Atendidos">
					<br>
					<?php $this->load->view(__NFORMULARIOS__ . "frmclientes_atendidos.php");?>									
				</div>
			</div>	
				
			<div id="Listar_Reporte" >
						
		</div>	

  </div>
 
</body>
</html>
