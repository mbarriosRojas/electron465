
<html>
<head>
  <?php $this->load->view("incluir/cabezera.php");?>
  <link href="<?php echo __CSS__ ?>tgrid/TGrid.css" rel="stylesheet" type="text/css" media="screen"/>
	<link href="<?php echo __CSS__ ?>tgrid/TGridPrint.css" rel="stylesheet" type="text/css" media="print"/>
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>view/procesar.js"></script>	
	
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>tgrid/func.js"></script>
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>tgrid/tgrid.js"></script>
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>tgrid/paginador.js"></script>
	
	<script type="text/javascript">
		function Actualizar(){
			<?php
			if(isset($cedula)){
			?>
				CFactura(<?php echo $cedula;?>);
			<?php
			}
			?>
			<?php
			if(isset($voucher)){
			?>
				CVoucher('<?php echo $voucher;?>');
			<?php
			}
			?>
			
		}
	</script>
	
	<style type="text/css">
		#dialog_link {padding: .1em 1em .20em 6px;text-decoration: none;position: relative;}
		#dialog_link span.ui-icon {margin: 5px 0 0;position: absolute;center: 1em;top: 1%;margin-top: 10px;}
		ul#icons {margin: 0; padding: 0;}
		ul#icons li {margin: .3px; position: relative; padding: .3px 0; cursor: pointer; float: left;  list-style: none;}
		ul#icons span.ui-icon {float: left; margin: .5px;}
	</style>	 
	
  </head>
<body onload="$('#carga_busqueda').dialog('close');Actualizar()">
	
	<div id="cabecera"><br>
		<?php $this->load->view("menu/buscar.php");?>
		<?php echo $Menu;?>
	</div>		
  <div id="medio" style='width:  65%;'>
  	<div id='msj_alertas'></div><div id='tabla_cobros'></div><div id='respaldo_eliminacion'><?php $this->load->view("procesar/resp_procesar.php");?></div>
  	<div id="dialog" title="Detalles del credito (Estado de Cuenta)"><?php  	
  		$this->load->view("procesar/lista_cobros.php");
  	
  	?></div>
    <h2>Historial de Clientes en el Sistema</h2><br>
    <h3>Usuario Conectado: <?php echo $this->session->userdata('usuario'); ?> Fecha: <?php echo date("Y/m/d"); ?></h3>
    <br><br><br>
    <?php
    	if($lista==''){
    		echo '
    		
    		<div id="tabs2" style="width:100%;Height:100%;"> 
			    <ul>
			    <li><a href="#persona">Datos Cliente</a></li>
		        <li><a href="#Procesar">Facturas Activas</a></li>
		        <li><a href="#contado">Facturas Contado</a></li>
		        <li><a href="#contado">Notas de Creditos</a></li>
		        </ul>
    		<div id="persona"></div><div id=\'contado\' style="Height:150px;overflow:auto;"></div><br><br><div id=\'Procesar\' style="Height:400px"></div>
    		</div>'; 
    	}else{
    		echo $lista;
    	}
    ?>
    
      
    	
  <div>
</body>
</html>
