<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title><?php echo __TITLE__ ?></title>
	<link href="<?php echo __CSS__ ?>__style.css.php?url=<?php echo __LOCALWWW__ ?>" rel="stylesheet" type="text/css">
	<link href="<?php echo __CSS__ ?>TGrid.css" rel="stylesheet" type="text/css">
	<link href="<?php echo __CSS__ ?>humanity/jquery-ui-1.8.20.custom.css" rel="stylesheet"  type="text/css">
	<link href="<?php echo __CSS__ ?>chat.css" type="text/css" rel="stylesheet" />
	<link href="<?php echo __CSS__ ?>screen.css" type="text/css" rel="stylesheet"/>
	<link href="<?php echo __CSS__ ?>menu_assets/styles.css" rel="stylesheet" type="text/css">
		
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>grid/TGrid.js"></script>
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>jquery/jquery-1.7.2.min.js"></script>
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>jquery/jquery-ui-1.8.20.custom.min.js"></script>
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>chat/chat.js"></script>
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>general/Global.js"></script>
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>view/buzon.js"></script>
	<style type="text/css">
		ul#icons {margin: 0; padding: 0;}
		ul#icons li {margin: 2px; position: relative; padding: 4px 0; cursor: pointer; float: left;  list-style: none;}
		ul#icons span.ui-icon {float: left; margin: 0 4px;}
	
	</style>
</head>
<body>
	<div id="cabecera"><br>
		<?php $this->load->view(__NFORMULARIOS__ . "frmbuscar.php");?>
		<?php echo $Menu;?>
	</div>
	
	<div id="medio" >				
				<h2> Buzon de Actividades Generales</h2><br>
				<h3>Usuario Connectado: <?php echo $this->session->userdata('usuario'); ?> Fecha: <?php echo date("Y/m/d"); ?></h3><br>					
				<div id="tabs" style="width:700px">						    <ul>
			        	<li><a href="#tabs-1">Historial de Pendientes</a></li>
			        	<li><a href="#tabs-2">Contratos Por Liberar</a></li>
			        	<li><a href="#tabs-2">Envio de Correspondencia</a></li>
				    </ul>
				    <div id='tabs-1'>
							<div style="overflow:auto;">
								<?php
									print('<pre>'); 
									print_r($relacion);
									//$this->load->view("nomina/liberar_nuevo.php");
									print('</pre>'); 
								?>
							</div>
					</div>
					<div id="tabs-2">
						
					</div>							    	
				</div>
	</div>

</body>
</html>
