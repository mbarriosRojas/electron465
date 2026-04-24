<html>
<head>
	<link href="<?php echo __CSS__ ?>tgrid/TGrid.css" rel="stylesheet" type="text/css" media="screen"/>
	<link href="<?php echo __CSS__ ?>tgrid/TGridPrint.css" rel="stylesheet" type="text/css" media="print"/>
	<?php $this->load->view("incluir/cabezera.php");?>
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>view/leer_txt.js"></script>
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>tgrid/func.js"></script>
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>tgrid/tgrid.js"></script>
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>tgrid/paginador.js"></script>
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>tgrid/xls.js"></script>
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>tgrid/editar.js"></script>
  </head>
<body>
	<div id="cabecera"><br>
		<?php $this->load->view("menu/buscar.php");?>
		<?php echo $Menu;?>
	</div>

  <div id="medio"  style="width:80%"> 
	<div id="msj_alertas"></div>
	  <h2>Listado de Archivos Pendientes Por Procesar</h2><br>
	  <h3>Usuario Conectado: <?php echo $this->session->userdata('usuario'); ?> Fecha: <?php echo date("Y/m/d"); ?></h3><br>	
	  	<input type="hidden" name="txtTxt" id="txtTxt" value="">
	  	<input type="hidden" name="txtCert" id="txtCert" value="">
	  
	  	<br>
			<div id="mnu_Reportes" title="Seleccione Banco">

				<div id="filtro_txt" class="dialogo" title="Opciones de Archivo">
					<br>
					<?php $this->load->view("reportes/form_archivos_c.php");?>
				</div>
				
				<div id="Procesar" title="Felicidades... Desea Ejecutar el archivo"></div>
			</div>
			

				
			<div id="Respuesta">
			
				
			</div>	
  </div>
</body>
</html>
