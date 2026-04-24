<html>
	<head>
		<?php $this -> load -> view("incluir/cabezera.php"); ?>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title><?php echo __TITLE__
			?></title>
		<link href="<?php echo __CSS__ ?>__style.css.php?url=<?php echo __LOCALWWW__ ?>" rel="stylesheet" type="text/css" />
		<link type="text/css" href="<?php echo __CSS__ ?>/ui-lightness/jquery-ui-1.8.6.custom.css" rel="stylesheet" />
		<script type="text/javascript" src="<?php echo __JSVIEW__ ?>nomina/botones.js"></script>
		<script type="text/javascript" src="<?php echo __JSVIEW__ ?>jquery-1.4.2.min.js"></script>
		<script type="text/javascript" src="<?php echo __JSVIEW__ ?>jquery-ui-1.8.6.custom.min.js"></script>
		<script type="text/javascript">
			$(function() {
				$("button, input:submit, a", ".demo, .agregar").button();
				$("a", ".demo").click(function() {
					return false;
				});

				$("input").keyup(function() {

					var value = $(this).val();

					$(this).val(value.toUpperCase());
				});
				$("textarea").keyup(function() {
					var pos_act = $(this).scrollTop();
					var value = $(this).val();

					$(this).val(value.toUpperCase());
					$(this).scrollTop(pos_act);
				});
			});

			function Mostrar_Respuesta(id) {
				$('#divRespuestas_' + id).show("blind");
			}

			function Ocultar_Respuesta(id) {
				$('#divRespuestas_' + id).hide("blind");
			}
	</script>
				<style type="text/css">
					/*demo page css*/
					body {
						font: 62.5% "Trebuchet MS", sans-serif;
						margin: 0px;
					}
					#dialog_link {
						padding: .1em 1em .20em 6px;
						text-decoration: none;
						position: relative;
					}
					#dialog_link span.ui-icon {
						margin: 5px 0 0;
						position: absolute;
						center: 1em;
						top: 1%;
						margin-top: 10px;
					}
					ul#icons {
						margin: 0;
						padding: 0;
					}
					ul#icons li {
						margin: .3px;
						position: relative;
						padding: .3px 0;
						cursor: pointer;
						float: left;
						list-style: none;
					}
					ul#icons span.ui-icon {
						float: left;
						margin: .5px;
					}

		</style>	
	</head>
	<body>
	<div id="cabecera"><br>
		<?php $this -> load -> view("menu/buscar.php"); ?>
		<?php echo $Menu; ?>
	</div>	
	<div id="medio" >	
		<div id="msj_alertas"></div>
			<h2 class="demoHeaders">	Panel de Sugerencias </h2><br>
			<h3>Usuario Conectado: <?php echo $this -> session -> userdata('usuario'); ?> Fecha: <?php echo date("Y/m/d"); ?></h3>
				
			<table style="width: 600px" >
					<tr>
						<td style="width: 30px"></td>
						<td>
						
							<table>
								<br><br>
								<div class="ui-widget" style="" id='divGuardar'></div><br>
									<tr>
										<td align="center"  colspan="4"><h1>AGREGAR SUGERENCIA</h1><br><br></td>
									</tr>
									<tr>
										<td align="right"> Para: </td>
										<td colspan="3">
											<select name="txtPara" id="txtPara" class="inputxt"  style="width: 155px;" >
												<?php echo $para; ?>
											</select>
										</td>
									</tr>
									<tr>
										<td align="right">Tema: </td>
										<td align="left" >
										<select name="txtTema" id="txtTema" class="inputxt"  style="width: 155px;" >
											<option value='0'>MEJORAS</option>
											<option value='1'>DESARROLLO</option>
											<option value='2'>CONSULTA</option>
											<option value='3'>MODIFICAR</option>
										</select></td>
										<td align="right" >Prioridad: </td>
										<td align="right"   >
										<select name="txtPrioridad" id="txtPrioridad" class="inputxt"  style="width: 155px;" >
											<option value='0'>NORMAL</option>
											<option value='1'>MUY ALTA</option>
											<option value='2'>ALTA</option>
											<option value='3'>BAJA</option>
											<option value='4'>MUY BAJA</option>
										</select></td>
									</tr>
									
									<tr>
										<td align="right" valign="top"><br>Sugerencia: </td>
										<td align="left" colspan="3" valign="top"><br><textarea name="txtSugerencia" id="txtSugerencia" class="inputxt" rows="5" style="width: 445px; height: 60px"  ></textarea></td>
									</tr>
								<tr>
									
										<td colspan="4" align="right"><br><div class="demo"><a href="#" onclick="btnSugerencia('<?php echo __LOCALWWW__; ?>');" id="btnSugerencia"> Guardar</a></div></td>
									
								</tr>
							</table>
							
						</td></tr>
						<tr><td colspan="2">
							
							<div id="DivLista">	<?php echo $lista_sugerencias; ?>	</div><br>
						</td></tr>
					
				</table>
		</div>
	</body>
</html>
<!-- Construye_Tabla('divPrueba'); -->