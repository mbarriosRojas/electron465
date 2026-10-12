
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  
  <title><?php echo __TITLE__ ?></title>
	<link href="<?php echo __CSS__ ?>__style.css.php?url=<?php echo __LOCALWWW__ ?>" rel="stylesheet" type="text/css">
	<link href="<?php echo __CSS__ ?>TGrid.css" rel="stylesheet" type="text/css">
	<link href="<?php echo __CSS__ ?>ui-lightness/jquery-ui-1.8.6.custom.css" rel="stylesheet"  type="text/css">
	<link href="<?php echo __CSS__ ?>chat.css" type="text/css" rel="stylesheet" />
	<link href="<?php echo __CSS__ ?>screen.css" type="text/css" rel="stylesheet"/>
	
	
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>grid/TGrid.js"></script>
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>jquery/jquery-1.4.2.min.js"></script>
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>jquery/jquery-ui-1.8.6.custom.min.js"></script>
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>chat/chat.js"></script>
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>general/Global.js"></script>
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>view/procesar.js"></script>
	<style type="text/css">
		#dialog_link {padding: .1em 1em .20em 6px;text-decoration: none;position: relative;}
		#dialog_link span.ui-icon {margin: 5px 0 0;position: absolute;center: 1em;top: 1%;margin-top: 10px;}
		ul#icons {margin: 0; padding: 0;}
		ul#icons li {margin: .3px; position: relative; padding: .3px 0; cursor: pointer; float: left;  list-style: none;}
		ul#icons span.ui-icon {float: left; margin: .5px;}
	</style>	
  
  
  </head>
<body>
   <div id="cabecera">
   		<a name="1"></a>
			<?php echo $Menu;?>
  	</div>
	<div id="dialog" title="Detalles del credito (Estado de Cuenta)"><?php $this->load->view(__NFORMULARIOS__ . "lista_cobros.php");?></div>
	
		<div id="BrBuscar">
		<?php $this->load->view(__NFORMULARIOS__ . "frmbuscar.php");?>
	</div>
  <div id="medio" > 
    <h2>Historial de Clientes en el Sistema</h2><br>
    <h3>Usuario Connectado: <?php echo $this->session->userdata('usuario'); ?> Fecha: <?php echo date("Y/m/d"); ?></h3><br>	
    <?php echo $lista;?>  	
  <div>

</body>
</html>
