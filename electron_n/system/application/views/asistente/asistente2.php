<html>
<head>
	
	<?php $this -> load -> view("incluir/cabezera.php"); ?>
	<link href="<?php echo __CSS__ ?>imprimir.css" rel="stylesheet" type="text/css" media="print"/>
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>view/asistente2.js"></script>
</head>
<body>
	<div id="cabecera"><br>
		<?php $this -> load -> view("menu/buscar.php"); ?>
		<?php echo $Menu; ?>
	</div>
	<div id="medio" >	
		<div id="msj_alertas"></div>
		<h2 class="demoHeaders">Asistente para elaboracion de contratos</h2><br>
		<h3>Usuario Conectado: <?php echo $this -> session -> userdata('descripcion'); ?> Fecha: <?php echo date("Y/m/d"); ?></h3><br><br>
		<img src="<?php echo __IMG__ ?>menu/capacidad.png">
		<center>

		<div id='NumeroSerie'> 
			<font style='font-size:20px; '><b>#: </b></font>	
		</div>
		<div>
		
		<form class="iconos2"  onsubmit="return Imprimir();" method="post" id="login_form" action="<?php echo __LOCALWWW__ ?>/index.php/cooperativa/CArtefacto">
		
		<input type="hidden" value="" id="txtCertificado"  name="txtCertificado"/>		
		
		<table>
				<tr>
				<td>Cedula: </td>
				<td>
				<input type="text" 	name="txtCed" id="txtCed" class="inputxt" style="width: 220px;"	onkeypress="return isNumberKey(event)" value="<?php echo $id;?>">
				</td>
				</tr>
				<tr>
				<td>Nombre: </td>
				<td>
				<input type="text" 	name="txtNom" id="txtNom" class="inputxt" style="width: 460px;"	value='<?php echo $nom;?>' >
				</td>
				</tr>
			<tr>
				<td class="formulario">Empresa <font color='#B70000'>(*)</font>:</td>
				<td style="width: 120px;" align="left" colspan="5">
					<select name="txtEmpresa"	id="txtEmpresa" style="width: 460px;" >
						<option value="0">COOPERATIVA ELECTRON 465 R.L</option>
						<option value="1">GRUPO ELECTRON 465 C.A</option>
						
					</select>
				</td>
				
			</tr>
				
				<tr>
		<td class="formulario">Nomina <font color='#B70000'>(*)</font>:</td>
		<td style="width: 120px;" align="left" colspan="5">
			<select name="txtNomina"	id="txtNomina" style="width: 460px;" onchange="tipo_nomina();" >
				<option>----------</option>
				<option>CONTRALORIA DEL ESTADO TACHIRA</option>
				<option>INJUVEN</option>
				<option>COORPOSALUD</option>
				<option>IPOSTEL</option>
				<option>SAPAM</option>
				<option>IPOSTEL - JUBILADOS</option>
				<option>ALCALDIA DEL LIBERTADOR</option>
				<option>ALCALDIA DEL LIBERTADOR - CARACAS</option>
				<option>FONVIM</option>
				<option>FUNDACION DEL NI&Ntilde;O SIMON</option>
				<option>IBIME</option>
				<option>HIDROLAGOS</option>
				<option>MINISTERIO DE LA MUJER</option>
				<option>INSTITUTO NACIONAL DE NUTRICION</option>
				<option>GOBERNACION DE FALCON</option>
				<option>MINISTERIO DEL PODER POPULAR PARA LA DEFENSA - AVIACION</option>
				<option>MINISTERIO DEL PODER POPULAR PARA LA EDUCACION</option>
				<option>MINISTERIO DEL PODER POPULAR PARA LA EDUCACION - JUBILADO</option>
				<option>GOBERNACION DEL ESTADO MERIDA</option>
				<option>DIRECCION GENERAL DEL PODER POPULAR POLICIA DEL ESTADO MERIDA</option>
				<option>POLICIA CAMPO ELIAS</option>
				<option>POLICIA VIAL</option>
				<option>ALCALDIA CARDENAL QUINTERO</option>
				<option>DIRECCION GENERAL DEL PODER POPULAR BOMBEROS DEL ESTADO MERIDA</option>
				<option>DIRECCION GENERAL DEL PODER POPULAR PARA LA EDUCACION ESTADO MERIDA</option>
				<option>INCES</option>
				<option>IANEM</option>
				<option>ULA</option>
				<option>AEULA</option>
				<option>SAIME</option>
				<option>VENETUR</option>
				<option>EJERCITO ACTIVO</option>
				<option>MINISTERIO DE TRANSPORTE Y COMUNICACIONES</option>								
				<option>GOBERNACION DEL ESTADO TACHIRA</option>
				<option>COORPO ZULIA</option>
				<option>CARBO ZULIA</option>
				<option>INMECA</option>
				<option>VENEZUELA TELEFERICO</option>
				<option>JUBILADOS EJERCITO</option>
				<option>LACTEOS LOS ANDES</option>
				<option>LACTEOS SANTA BARBARA, C.A</option>
				<option>LACTEOS TORONDOY</option>
				<option>COORPORACION  DROLANCA</option>
				<option>MINISTERIO DEL PODER POPULAR PARA LA ENERGIA Y MINA</option>
				<option>AGUAS DE MERIDA</option>
				<option>COORPOELEC</option>
				<option>COORPOANDES</option>
				<option>CANTV</option>
				<option>CANTV - JUBILADOS</option>
				<option>PDVSA</option>
				<option>SAREM</option>
				<option>MINFRA</option>
				<option>PEQUIVEN</option>
				<option>GOBERNACION DEL ESTADO ZULIA</option>
				<option>PODER JUDICIAL</option>
				<option>GUARDIA NACIONAL</option>
				<option>DIRECCION DE PUERTOS Y AEROPUERTOS DE VENEZUELA</option>
				<option>SENIAT</option>
				<option>CATIVEN</option>
				<option>INMECA</option>
				<option>FUNDABIT</option>
				<option>PDVAL</option>
				<option>PDV COMUNAL S.A</option>
				<option>CORMETUR</option>
				<option>MERCAL</option>
				<option>INAM</option>
				<option>INVERCRESA</option>
				<option>MRW</option>
				<option>MINISTERIO DE TURISMO</option>
				<option>CONSEJO NACIONAL</option>
				<option>TRIBUNAL SUPREMO DE JUSTICIA</option>
				<option>INAM - JUBILADOS</option>
				<option>IVSS</option>
				<option>INVERCRESA</option>
				<option>IVSS - JUBILADOS</option>
				<option>IPASME</option>
				<option>IPASME - JUBILADOS</option>
				<option>MINISTERIO DE HABITAD Y VIVIENDA</option>
				<option>CNE</option>
				<option>MINISTERIO DE LA SALUD</option>
				<option>PUERTO DE MARACAIBO</option>
				<option>MINISTERIO DE INTERIOR Y JUSTICIA</option>
				<option>MINISTERIO DEL AMBIENTE</option>
				<option>BARRIO ADENTRO</option>
				<option>IMPRADEM</option>
				<option>UNESUR</option>
				<option>INSTITUTO AUTONOMO DE ALIMENTACION Y NUTRICION DEL ESTADO MERIDA</option>
				<option>UNIVERSIDAD DEL ZULIA</option>
				<option>INPSASEL</option>
				<option>PEPSI</option>
				<option>BIBLIOTECA NACIONAL</option>
				<option>FONTUR</option>
				<option>INSTITUTO AGRARIO NACIONAL</option>
				<option>ALCALDIA DEL MUNICIPIO PINTO SALINAS</option>
				<option>ALCALDIA DEL MUNICIPIO SUCRE</option>
				<option>ALCALDIA DEL MUNICIPIO CARDENAL QUINTERO</option>
				<option>ALCALDIA DEL MUNICIPIO CARDENAS</option>
				<option>ALCALDIA DEL MUNICIPIO GUASIMOS</option>
				<option>ALCALDIA DEL MUNICIPIO PEDRO MARIA UREÑA</option>
				<option>ALCALDIA DEL MUNICIPIO RAFAEL URDANETA</option>
				<option>ALCALDIA DEL MUNICIPIO CAMPO ELIAS</option>
				<option>INSTITUTO NACIONAL DE INVESTIGACIONES AGRICOLA</option>
				<option>HOTEL PARAMO LA CULATA</option>
				<option>MINISTERIO DE LA DEFENSA</option>
				<option>UPEL</option>
				<?php
				  echo $lista;
				?>
				<option>-- PARTICULAR --</option>			
			</select>
		</td>
	</tr>
	<tr>
		<td class="formulario">Banco: <font color='#B70000'>(*)</td>
		<td style="width: 120px;" align="left" colspan="5">
			<select name="txtCobrado"	id="txtCobrado" style="width: 460px;" onchange="verifica_bicentenario();">
				<option>----------</option>
				<option>NOMINA</option>
				<option>BICENTENARIO</option>
				<option>BOD</option>
				<option>PROVINCIAL</option>
				<option>VENEZUELA</option>
				<option>BANESCO</option> 
				<option>INDUSTRIAL</option>
				<option>CAMARA MERCANTIL</option>
				<option>CREDINFO</option>
				<option>INVERCRESA</option>
				<option>FONDO COMUN</option>
				<option>100% BANCO COMERCIAL</option>
				<option>DOMICILIACION POR OFICINA</option>
				<option >SOFITASA</option>
				<option>BANFAN</option>
				<option>MERCANTIL</option> 
				<option >DEL SUR</option>
				<option>CARONI</option>
				<option>BANCARIBE</option>
				<option disabled="true">CARIBE</option>
			</select>
		</td>
		</tr>
		<tr>
				<td class="formulario">Tipo <font color='#B70000'>(*)</font>:</td>
				<td style="width: 120px;" align="left" colspan="5">
					<select name="txtTipo"	id="txtTipo" style="width: 460px;" >
						<option value="CArtefacto">Artefactos</option>
						<option value="CCalculos" disabled='disabled'>Calculos Pronto Pago</option>
						<option value="CCalculos_Nuevo">Calculos</option>
						
					</select>
				</td>
				
			</tr>
				
				
			</table>
			<br><br>
			<label id ="lbltipo">Forma Del Credito: </label>
			<select id="forma" style="width: 200px;" onchange="verifica_fpago();">
				<option>SELECCIONAR</option>
				<option value="Nomina">NOMINA</option>
				<option value="Banco">BANCO</option>
				<option value="BONOEXPRESS">BONOEXPRESS</option>
			</select><br><br><br>
			<table id="mensual" style="display: none;">
				<tr>
					<td>Tipo Cliente</td>
					<td>
						<input type="radio" name="tc" id="tc" value=0 checked="true" onchange="tipo_cliente();">Activo
						<input type="radio" name="tc" id="tc" value=1 onchange="tipo_cliente();">Jubilado
					</td>
				</tr>
				<tr id="f1">
					<td><label id="lblMonto"> Sueldo Neto Mensual Depositado: &nbsp;</label></td>
					<td>
					<div class="ui-widget">
						<input type="text" 	name="txtMonto" id="txtMonto" class="inputxt" style="width: 220px;"	value='' onkeypress="return isNumberKey(event)">
					</div></td>
				</tr>
				<tr id="f2">
					<td><label id="lblMontoD"> Total De Descuentos Mensuales: &nbsp;</label></td>
					<td>
					<div class="ui-widget">
						<input type="text" 	name="txtMontoD" id="txtMontoD" class="inputxt" style="width: 220px;"	value='' onkeypress="return isNumberKey(event)"> 
					</div></td>
				</tr>
				<tr id="f3">
					<td><label id="lblMontoV"> Total Vacaciones: &nbsp;</label></td>
					<td>
					<div class="ui-widget">
						<input type="text" 	name="txtMontoV" id="txtMontoV" class="inputxt" style="width: 220px;"	value='' onkeypress="return isNumberKey(event)">
					</div></td>
				</tr>
				<tr id="f4">
					<td><label id="lblMontoA"> Total Noviembre: &nbsp;</label></td>
					<td>
					<div class="ui-widget">
						<input type="text" 	name="txtMontoA" id="txtMontoA" class="inputxt" style="width: 220px;"	value='' onkeypress="return isNumberKey(event)">
					</div></td>
				</tr>
				<tr id="f5">
					<td><label id="lblMontoA"> Total Bono Utiles y Uniformes: &nbsp;</label></td>
					<td>
					<div class="ui-widget">
						<input type="text" 	name="txtMontoUU" id="txtMontoUU" class="inputxt" style="width: 220px;"	value='' onkeypress="return isNumberKey(event)">
					</div></td>
				</tr>
				<tr id="f6">
					<td colspan="2" align="center">
					<input type="button" class="inputxt" value='Realizar Calculo' id='btnCalcular' onclick="calcular()"/>
					</td>
				</tr>
			</table>
			<br><br><br>
			<div id="mensaje"></div>
			<br>
			<br>
			<input type="submit" class="inputxt" style="display: none;" value='Imprimir y Continuar >'  id='btnImprimir' />
			<BR>
			<BR>
		<table id="firmas" style="display: none;"><tr><td style="width:600px" valign="bottom">
					<table><tr><td style="width:500px" valign="bottom">
					<br><br>_______________________________________<br>
					FIRMA SUPERVISOR DE VENTAS<br><br><br>
					</td><td style="width:100px" align="center">
						_______________________________________<br>
					FIRMA ANALISTA DE VENTAS.- <br>
					ELABORADO EL: <?php echo date("Y/m/d");?>
					</td>
					</tr>
					
			</table>
			<center>
					<table border="0" style="width:70%; height: 100px">
						<tr>
							<td style="width:100px; height: 80px" >
								&nbsp;
							</td>
							<td align="center"><br><br>_______________________________________<br>
					FIRMA RECIBI CONFORME.- <br>
					C.I: <br>
					APELLIDOS Y NOMBRE: <br><br>
							<td style="width:100px; height: 80px" >
								&nbsp;
							</td>
						</tr>
						<tr>
						<td style="text-align: left;font-size: 10px;"><b>Pulgar Izquierdo</b></td>
						<td style="text-align: center">&nbsp;</td>
						<td style="text-align: right;font-size: 10px;"><b>Pulgar Derecho</b></td>
						</tr>
					</table></center>
					
		</table>

		
		</form>
		
		</center>
		</div>
		
		
		
		
		
		
		
		
		
		
		
		
		
		
	</div>
</body>
</html>