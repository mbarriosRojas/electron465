
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title><?php echo __TITLE__ ?></title>
<link href="<?php echo __CSS__ ?>__style.css" rel="stylesheet" type="text/css" />

<?php echo $Componentes_Yui;?>
<script type="text/javascript" src="<?php echo __JSVIEW__ ?>nomina/menu.js"></script>

<script type="text/javascript"> 
  if ((typeof YAHOO !== "undefined") && (YAHOO.util) && (YAHOO.util.Event)) {
  	YAHOO.util.Event.throwErrors = true;
	}
	
</script>

<style type="text/css">
.yui-navset div.loading div {
	background: url(<?php echo __IMG__;?>cargando_tab.gif) no-repeat center
		center;
	height: 8em; /* hold some space while loading */
}

.yui-navset div.loading div * {
	display: none;
}

#example-canvas h2 {
	padding: 0 0 .5em 0;
}
</style>


</head>
<body class="yui-skin-sam">

<!-- 
		Cuerpo del documento para el datatable 
		Componetes de YUI Loader
	-->
<div id="cabecera"><!-- 
		--------------------------------------------------------
			Inicio del Menu Superior			
		--------------------------------------------------------
  --> <?php echo $Menu;?></div>

<div id="medio" align="left"><br>
<table>
	<tr>
		<td style="width: 50px;">&nbsp;</td>
		<td width="100%" align="left"><img
			src="<?php echo __IMG__ ?>imgBuzonCorreos.png"></img> <br>
		<br>
		<center>




		<div id="correo" style="width: 800px" align="left"></div>
		<script type="text/javascript"
			src="<?php echo __JSVIEW__ ?>nomina/tabs.js"></script></center>
		</td>
	</tr>
</table>




<br>
<br>

</div>
<div id="bottom" align="left"><br>
<table>
	<tr>
		<td rowspan="4" style="width: 15px"></td>
		<td rowspan="4" style="width: 65px"><img
			src="<?php echo __IMG__ ?>btnBuzon.png"
			style="width: 55px; height: 55px"></td>
		<td style="width: 220px">Titulo:&nbsp;<label class="label"
			id="lblDescripcion"> Buz&oacute;n de n&oacute;minas</label></td>
		<td style="width: 200px">Creaci&oacute;n: <label class="label"
			id="lblFecha">11-07-2009</label></td>
		<td style="width: 200px">Modificaci&oacute;n:&nbsp;<label
			class="label" id="lblPasaporte">11-07-2009</label></td>

	</tr>
	<tr>
		<td style="width: 220px" colspan="3">Descripci&oacute;n:&nbsp;<label
			class="label" id="lblDescripcion"> Documento para la cesta ticket</label>
		</td>
	</tr>
</table>
<br>
</div>

</body>
</html>
