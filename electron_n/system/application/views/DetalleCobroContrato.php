<html>
<head>
  <?php $this -> load -> view("incluir/cabezera.php"); ?>
  <link href="<?php echo __CSS__ ?>tgrid/TGrid.css" rel="stylesheet" type="text/css" media="screen"/>
	<link href="<?php echo __CSS__ ?>tgrid/TGridPrint.css" rel="stylesheet" type="text/css" media="print"/>
		
	
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>tgrid/func.js"></script>
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>tgrid/tgrid.js"></script>
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>tgrid/paginador.js"></script>
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>view/detalleCobro.js"></script>
	
	<script type="text/javascript">
		
	</script>
	
	<style type="text/css">
		
	</style>	 
	
  </head>
  <body onload="Consultar('<?php echo $cedula;?>','<?php echo $contrato;?>')">
  	<br><br><br>
  	<h2>Historial de Clientes <?php echo $cedula;?>,<?php echo $contrato;?> en el Sistema</h2><br>
    <h3>Usuario Conectado: <?php echo $this -> session -> userdata('usuario'); ?> Fecha: <?php echo date("Y/m/d"); ?></h3>
    <br><br><br>
  <div id="medio" style='width:  65%;'>
  	<div id='msj_alertas'></div><div id='respaldo_eliminacion'><?php $this -> load -> view("procesar/resp_procesar.php"); ?></div>
  	<div id="dialogo" title="Detalles del credito (Estado de Cuenta)"><?php
		$this -> load -> view("procesar/lista_cobros.php");
  	?></div>
    
  <div>
</body>
</html>
