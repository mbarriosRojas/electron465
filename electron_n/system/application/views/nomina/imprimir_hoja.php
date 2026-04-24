<html>
<head>
	<title>Datos Personales</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	
	<title><?php echo __TITLE__ ?></title>
	
	<link type="text/css" href="<?php echo __CSS__ ?>ui-lightness/jquery-ui-1.8.6.custom.css" rel="stylesheet" />	
	
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>prototype.js"></script>
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>jquery-1.4.2.min.js"></script>
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>jquery-ui-1.8.6.custom.min.js"></script>
	
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>nomina/botones.js"></script>
	<style type="text/css">
		/*demo page css*/
			body{ font: 90.5% "Trebuchet MS", sans-serif; margin: 0px;}
			table{
			font: 95.5% "Trebuchet MS", sans-serif; margin: 0px;
			}
	
		 	* html .ui-autocomplete {
				height: 100px;
			}
	</style>
	
</head>	
<body onload="consultar_clientes('<?php echo __LOCALWWW__?>'); ">
	<center><h2>Datos Personales</h2>
	<?php $this->load->view(__NFORMULARIOS__ . "datos_personales.php");?>
	<script type="text/javascript">
		document.getElementById("txtCedula").value = "<?php echo $cedula?>";
	</script>
	</center>
</body>
</html>
