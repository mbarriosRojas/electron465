	<?php
	$tab1 = "Mercancia";
	$titulo = "Control De Mercancia";
	if ($tipo != 0) {
		$tab1 = "Material De Oficina";
		$titulo = "Control De Material de Oficina";
	}
	?>

	
<!DOCTYPE html>
<html>
	<head>
		<link href="<?php echo __CSS__ ?>tgrid/TGrid.css" rel="stylesheet" type="text/css" media="screen"/>
		<link href="<?php echo __CSS__ ?>tgrid/TGridPrint.css" rel="stylesheet" type="text/css" media="print"/>
		<?php $this -> load -> view("incluir/cabezera.php"); ?>
		<script type="text/javascript" src="<?php echo __JSVIEW__ ?>view/entregas.js"></script>
		<script type="text/javascript" src="<?php echo __JSVIEW__ ?>tgrid/incluir.js"></script>
		<script type="text/javascript" src="<?php echo __JSVIEW__ ?>tgrid/func.js"></script>
		<script type="text/javascript" src="<?php echo __JSVIEW__ ?>tgrid/tgrid.js"></script>
		<script type="text/javascript" src="<?php echo __JSVIEW__ ?>tgrid/paginador.js"></script>
	</head>
	<body >
		<div id="cabecera">
			<br>
			<?php $this -> load -> view("menu/buscar.php"); ?>
			<?php echo $Menu; ?>
		</div>

		<div id="medio" ><div id="msj_alertas"></div><br><br>
			<input type="hidden" name="tipoV" id="tipoV" value=<?php echo $tipo; ?> />
			<h3>Usuario Conectado: <?php echo $this -> session -> userdata('usuario'); ?> Fecha: <?php echo date("Y/m/d"); ?></h3><br>
			<h2>Entrega De Mercancia</h2>
			<div id="tabs" style="width:720px"> 
			    <ul>
		    	    <li><a href="#tabs-1">Mover Mercancia</a></li>
		        	<li><a href="#tabs-2">Reporte</a></li>
			    </ul>						    							    	
				<div id='tabs-1' >																		
					<br>
					<table width="680" border="0" cellspacing="3" cellpadding="0">
						<tr>
							<td valign="top"> Descripci&oacute;n:</td>
							<td colspan="3" valign="top"><input type="text" name="txtDescripcion"  style="width: 500px;" id="txtDescripcion"> 
								<input type="hidden" id='cantidad' name="cantidad" /></td>
							<td><button onclick="Consultar_Mercancia();" id="Buscar">Buscar</button></td>
						</tr>
					</table>
					<br><br><br>
					<div id="datos" ></div>
				</div>
				<div id='tabs-2' >																		
					<div id="Reporte_Entregas" style="width:100%; Height:100%"></div>
				</div>
					
			</div>
			
		</div>
	</body>
</html>