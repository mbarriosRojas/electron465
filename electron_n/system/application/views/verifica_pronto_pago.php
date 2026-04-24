<html>
<head>
	<link href="<?php echo __CSS__ ?>tgrid/TGrid.css" rel="stylesheet" type="text/css" media="screen"/>
	<?php $this -> load -> view("incluir/cabezera.php"); ?>
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>view/pronto_pago.js"></script>	
</head>
<body>
	<div id="cabecera"><br>
		<?php $this -> load -> view("menu/buscar.php"); ?>
		<?php echo $Menu; ?>
	</div>		
	<div id="lateral_i"> <center><br>
		<?php $this -> load -> view("menu/lateral.php"); ?>
	</div>
	<div id="medio" >	
		<div id="msj_alertas"></div>
			<h2 class="demoHeaders">	Control de Inventario </h2><br>
			<h3>Usuario Conectado: <?php echo $this -> session -> userdata('usuario'); ?> Fecha: <?php echo date("Y/m/d"); ?></h3><br>
				<div id="tabs" style="width:720px"> 
			    <ul>
		        <li><a href="#tabs-1">Verificar</a></li>
		        
			    </ul>						    							    	
					<div id='tabs-1' >																		
							<table>
								<tr>
									<td>
										<h2> Verificar Pronto Pago.</h2>
											<br><br>
											<table style="width:300px">
												<tr>
													<td class="formulario" colspan="3">Ingrese Cedula(*)</td>
												</tr>
												<tr>
													<td class="formulario">
														<input id='txtCedula' name='txtCedula' type="text"/>
													</td>
													<td class="formulario">
														<input id='txtFactura' name='txtFactura' type="text"/>
													</td>
													<td><button style="height:19px; width:100px" onclick="Verifica();">Verificar</td>
													
												</tr>
											</table>
										</div>
									</td>
								</tr>
	
							</table>
					</div>
					
					
				</div>
				
				
			<center>
			</center>
		</div>
	</body>
</html>
