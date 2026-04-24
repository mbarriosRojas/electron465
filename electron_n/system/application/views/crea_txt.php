
<html>
<head>
	<link href="<?php echo __CSS__ ?>tgrid/TGrid.css" rel="stylesheet" type="text/css" media="screen"/>
	<link href="<?php echo __CSS__ ?>tgrid/TGridPrint.css" rel="stylesheet" type="text/css" media="print"/>
	<link href="<?php echo __CSS__ ?>lightbox/lightbox.css" rel="stylesheet"  type="text/css">
	<?php $this->load->view("incluir/cabezera.php");?>
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>view/crear_txt.js"></script>
  </head>
<body>
	<div id="cabecera"><br>
		<?php $this->load->view("menu/buscar.php");?>
		<?php echo $Menu;?>
	</div>
		
  <div id="medio"  style="width:800px"> 
	<div id="msj_alertas"></div>
	  <h2>Crear Archivos de Texto de Cobranza</h2><br>
	  <h3>Usuario Conectado: <?php echo $this->session->userdata('usuario'); ?> Fecha: <?php echo date("Y/m/d"); ?></h3><br>	
	  
	  	<br>
			<div id="mnu_Reportes" title="Detalles del credito">
				<table >
  				<tr>
  					
  					<?php if($Nivel == 3 || $Nivel == 0 || $Nivel == 9 || $Nivel == 2 || $Nivel == 8){?>
  						<td align="center"><img src="<?php echo __IMG__ ?>reportes/emercancia.png" style='width:48px' onClick="MostrarDiv('filtro_txt');"/><br>Generar TXT</td>
  						<td style="width: 50px"></td>
  					<?php }?>
  				</tr>
  				
  				</table>	
				<div id="filtro_txt" class="dialogo" title="Opciones de Archivo">
					
					<?php $this->load->view("reportes/form_archivos.php");?>
				</div>
			</div>		
  </div>
</body>
</html>
