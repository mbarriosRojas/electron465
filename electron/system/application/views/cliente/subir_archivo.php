<!DOCTYPE html>
<html>
	<head>
		<title>SUBIR ARCHIVO</title>
		<head>
			<title>Subir Archivos</title>
			<meta charset="UTF-8" />
			<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
			<meta name="viewport" content="width=device-width, initial-scale=1.0">
			<meta name="keywords" content="menu, navigation, animation, transition, transform, rotate, css3, web design, component, icon, slide" />
			<link rel="stylesheet" type="text/css" href="<?php echo __CSS__ ?>subir_archivo/demo.css" />
			<link rel="stylesheet" type="text/css" href="<?php echo __CSS__ ?>subir_archivo/style8.css" />

			<link href="<?php echo __CSS__ ?>lightbox/lightbox.css" rel="stylesheet"  type="text/css">
			<link rel="stylesheet" href="<?php echo __CSS__ ?>lightbox/screen1.css" type="text/css" media="screen" />

		</head>
		<body>
			<div class="container">
				<center>
					<br>
					<br>
					<div id='frm_buscar' class="formulario">
						<form onsubmit="return consultar_datos();">
							<label>INGRESE C&Eacute;DULA&nbsp;</label>
							<input type="text" id = 'cedula' name='cedula' />
							<br />
							<input type="submit" value="Buscar" class="">
							</input>
						</form>
					</div>
					<br />
					<div id='datos_cliente' ></div>
					<br />
					<div id='id_contenido'>
						<div class="imageRow">
							<div id='combos' class="formulario">
								<table>
									<tr>
										<td>C&eacute;dula Original:&nbsp;</td>
										<td colspan="2">
										<input type="file" name="d1"id="d1"/>
										</td>
									</tr>
									<tr>
										<td>Cartas Originales:&nbsp;</td>
										<td><select id='cartas' name='cartas' style="width: 100%;"></select></td>
										<td>
										<input type="file" name="d4"id="d4"/>
										</td>
									</tr>

									<tr>
										<td>Bancos  Originales:&nbsp;</td>
										<td><select id='bancos' name='bancos' style="width:100%"></select></td>
										<td>
										<input type="file" name="d2" id="d2" />
										</td>
									</tr>
									<tr>
										<td>Facturas Originales:&nbsp;</td>
										<td><select id='facturas' name='facturas' style="width: 100%;"></select></td>
										<td>
										<input type="file" name="d3"id="d3"/ >
										</td>
									</tr>
									<tr>
										<td>Cheques Originales:&nbsp;</td>
										<td><select id='cheques' name='cheques' style="width: 100%;"></select></td>
										<td>
										<input type="file" name="d5"id="d5"/ >
										</td>
									</tr>
									<tr>
										<td>Garantia Original:&nbsp;</td>
										<td><select id='garantia' name='garantia' style="width: 100%;"></select></td>
										<td>
										<input type="file" name="d6"id="d6"/ >
										</td>
									</tr>
									<tr>
										<td>Revisi&oacute;n Original:&nbsp;</td>
										<td>
										<select id='revision' name='revision' style="width: 100%;">
											<option value='Revision'>Revision</option>
										</select></td>
										<td>
										<input type="file" name="d7"id="d7"/>
										</td>
									</tr>
									<tr>
										<td>Fiador Originales:&nbsp;</td>
										<td>
										<select id='fiador' name='fiador' style="width: 100%;">
											<option value='fiador'>Fiador</option>
										</select></td>
										<td>
										<input type="file" name="d8"id="d8"/>
										</td>
									</tr>
									<tr>
										<td>Respaldo Rif:&nbsp;</td>
										<td>
										<select id='rrif' name='rrif' style="width: 100%;">
											<option value='rif'>Respaldo Rif</option>
										</select></td>
										<td>
										<input type="file" name="d9"id="d9"/>
										</td>
									</tr>

								</table>
								<br>
								<div>
									<input type="button" name="boton" id="boton" onclick="subir();" value="Subir" />
								</div>
							</div>
						</div>
					</div>
				</center>
			</div>
			<div id="carga" style="display: none;"><center><img src="<?php echo __IMG__ ?>loading.gif"></img><br><h2>SUBIENDO</h2><br></center></div>
			<div id='car'></div>
			<div id='resp'></div>
			<link href="<?php echo __CSS__ ?>humanity/jquery-ui-1.8.20.custom.css" rel="stylesheet"  type="text/css">
			<link href="<?php echo __CSS__ ?>lightbox/lightbox.css" rel="stylesheet"  type="text/css">
			<script type="text/javascript" src="<?php echo __JSVIEW__ ?>jquery/jquery-1.7.2.min.js"></script>
			<script type="text/javascript" src="<?php echo __JSVIEW__ ?>jquery/jquery-ui-1.8.20.custom.min.js"></script>
			<script type="text/javascript" src="<?php echo __JSVIEW__ ?>lightbox/lightbox.js"></script>
			<script type="text/javascript" src="<?php echo __JSVIEW__ ?>general/Global.js"></script>
			<script type="text/javascript" src="<?php echo __JSVIEW__ ?>view/subir_archivo.js"></script>
		</body>
</html>