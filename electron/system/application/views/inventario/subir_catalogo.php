<html>
	<head>
		<title>SUBIR ARCHIVO</title>
		<link href="<?php echo __CSS__ ?>humanity/jquery-ui-1.8.20.custom.css" rel="stylesheet"  type="text/css">
		<link href="<?php echo __CSS__ ?>lightbox/lightbox.css" rel="stylesheet"  type="text/css">	
		<script type="text/javascript" src="<?php echo __JSVIEW__ ?>jquery/jquery-1.7.2.min.js"></script>
		<script type="text/javascript" src="<?php echo __JSVIEW__ ?>jquery/jquery-ui-1.8.20.custom.min.js"></script>	
		<style type="text/css">
			* {
				font-family: sans-serif;
				font-size: 12px;
				color: #798e94;
			}
			body {
				margin: auto;
				background-color: #E2ECEE;
			}

			.formulario {
				width: auto;
				border: 1px solid #CED5D7;
				border-radius: 6px;
				padding: 45px 45px 20px;
				margin-top: 50px;
				background-color: white;
				box-shadow: 0px 5px 10px #B5C1C5, 0 0 0 10px #EEF5F7 inset;
			}
			.formulario label {
				display: block;
				font-weight: bold;
			}

			.formulario input[type='text']:focus, .formulario text:focus {
				outline: none;
				box-shadow: 0 0 0 3px #dde9ec;
			}
			.formulario input[type='submit'] {
				border: 1px solid #CED5D7;
				box-shadow: 0 0 0 3px #EEF5F7;
				padding: 8px 16px;
				border-radius: 10px;
				font-weight: bold;
				text-shadow: 1px 1px 0px white;
				background: #e4f1f6;
				background: -moz-linear-gradient(top, #e4f1f6 0%, #cfe6ef 100%);
			}

			.formulario input[type='submit']:hover {
				background: #edfcff;
				background: -moz-linear-gradient(top, #edfcff 0%, #cfe6ef 100%);
			}
			.formulario input[type='submit']:active {
				background: #cfe6ef;
				background: -moz-linear-gradient(top, #cfe6ef 0%, #edfcff 100%);
			}
		</style>

		<script type="text/javascript">
			var sUrl = 'http://' + window.location.hostname + '/cooperativa';
			var sUrlP = sUrl + '/index.php/cooperativa/';
			var sImg = sUrl + '/system/img/';
			var sImgC = sUrl + '/system/repository/expedientes/';
		</script>
	</head>
	<body>
		<center>
		<div class="formulario">
			<form id="form1" enctype="multipart/form-data" name="form1" method="post"  action=<?php echo __LOCALWWW__ . '/index.php/cooperativa/subir_catalogo_ejecuta'; ?> >				
				<div id='datos_cliente' ><?php
					if (isset($msj) ) {	echo $msj;}?></div>
					<?php if ($modelo != ''){ ?> 
					<div id='combos'>
						<table>
							<tr>
								<td><?php echo $modelo; ?></td>
								<td colspan="2"><input type="file" name="foto"id="foto"/><input type="hidden" value="<?php echo $modelo; ?>" id = "modelo" name='modelo'></td>
							</tr>							
						</table>					
						<div>
							<input type="submit" name="boton" id="boton" value="Subir" />
						</div>
					</div>
					<?php }else{
						echo "<script languaje='javascript' type='text/javascript'>opener.BModelo();setTimeout(window.close, 5000);</script>";
					}?>
				</div>
			</form>
			<br /><br /><br /><br />
		</center>
	</body>
</html>