<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  		<title><?php echo __TITLE__ ?></title>
  		<link href="<?php echo __CSS__ ?>__style.css.php?url=<?php echo __LOCALWWW__ ?>" rel="stylesheet" type="text/css" />
  <script type="text/javascript" src="<?php echo __JSVIEW__ ?>general/Global.js"></script>
  		<link type="text/css" href="<?php echo __CSS__ ?>ui-lightness/jquery-ui-1.8.6.custom.css" rel="stylesheet" />
		<script type="text/javascript" src="<?php echo __JSVIEW__ ?>jquery/jquery-1.4.2.min.js"></script>
		<script type="text/javascript" src="<?php echo __JSVIEW__ ?>jquery/jquery-ui-1.8.6.custom.min.js"></script>
		<script>
		
			 function isNumberKey(evt){
				var charCode = (evt.which) ? evt.which : event.keyCode
				if (charCode > 31 && (charCode < 48 || charCode > 57))	return false;
				return true;
			}
			
			function calcular(){
				forma = $("#forma option:selected").text();
				nomina = $("#txtNomina option:selected").text();
				monto = $("#txtMonto").val();
				montoD = $("#txtMontoD").val();
				montoV = $("#txtMontoV").val();
				montoA = $("#txtMontoA").val();
				montoUU = $("#txtMontoUU").val();
				ced = $("#txtCed").val();
				nom = $("#txtNom").val();
				if(monto == '' || montoD == '' || montoV == '' || montoA == '' || ced == '' || nom == '' || nomina == ''){
					alert("Debe ingresar los montos y datos Correspondientes");
				}else{
					montoT1 = (monto - montoD)/2;
					montoT2 = (montoV)/2;
					montoT3 = (montoA)/2;
					mensaje = "<h2>La capacidad de endeudamiento mensual del cliente es:<br>"+ montoT1 +" BS.</h2>";
					if(tipo != 0){
						mensaje += "<br><h2>La capacidad de endeudamiento en Bono Recreacional Julio  del cliente es:<br>"+ montoT2 +" BS.</h2>"
						mensaje += "<br><h2>La capacidad de endeudamiento en Noviembre del cliente es:<br>"+ montoT3 +" BS.</h2>"
						
					}else{
						mensaje += "<br><h2>La capacidad de endeudamiento en vacaciones del cliente es:<br>"+ montoT2 +" BS.</h2>"
						mensaje += "<br><h2>La capacidad de endeudamiento en aguinaldos del cliente es:<br>"+ montoT3 +" BS.</h2>"
						
					}
					
					if((nomina == 'MINISTERIO DEL PODER POPULAR PARA LA EDUCACION' || nomina == 'MINISTERIO DEL PODER POPULAR PARA LA EDUCACION - JUBILADO') && forma != 'Nomina'){
						montoT4 = montoUU/2;
						mensaje += "<br><h2>La capacidad de endeudamiento por bonos de utiles y uniformes del cliente es:<br>"+ montoT4 +" BS.</h2>"
					}else{
						$("#f5").hide();
					}
					$("#mensaje").html(mensaje);
					$("#mensaje").show();
					$("#btnImprimir").show();
					$("#firmas").show();	
				}
				
			}
			
			function Imprimir (){
				
				var ced = $('#txtCed').val();; //Cedula del Cliente
				var nom = $('#txtNom').val(); //Nombre de la Persona
				
				var empr = $('#txtEmpresa').val(); //Empresa
				var nomi = $('#txtNomina').val(); //Nomina 
				
				var cobr = $('#txtCobrado').val(); //Descripcion del Produto
				
				
			    cade = "ced=" + ced + "&nomb=" + nom + "&empr=" + empr + "&nomi=" + nomi + "&lina=" + cobr;
			    
			   	$.ajax({
					url : sUrlP + 'Guardar_SolicitudCE',
					type : 'POST',
					data : cade,
					success : function(msj) {
						alert('Proceso Exitoso');
					}
				});
				
				
				$("#btnCalcular").hide();
				$("#btnImprimir").hide();
				$("#lbltipo").hide();
				$("#forma").hide();
				window.print();	
				
				
			}
			
			function verifica_fpago(){
				forma = $("#forma option:selected").text();
				nomina = $("#txtNomina option:selected").text();
				tipo = $("input[name='tc']:checked").val();
				$("#mensaje").html("");
				$("#firmas").show();
				$("#btnImprimir").hide();
				$("#f5").hide();
				if(forma != 'Seleccionar'){
					$("#mensual").show();
					$("#f1").show();
					$("#f3").show();
					$("#f4").show();
					$("#txtMontoV").val('');
					$("#txtMontoA").val('');
					if(forma == 'Nomina'){
						if(nomina == 'MINISTERIO DEL PODER POPULAR PARA LA EDUCACION' || nomina == 'MINISTERIO DEL PODER POPULAR PARA LA EDUCACION - JUBILADO' ){
							if(tipo == 1){
								$("#f1").hide();
								alert("Personal Del M.P.P.P.E. Credinfo No posee Jubilados");
							}else{
								$("#f1").show();	
							}
							$("#f3").hide();
							$("#f4").hide();
							$("#f5").hide();
							$("#txtMontoV").val(0);
							$("#txtMontoA").val(0);
						}
						$("#f2").hide();
						$("#txtMontoD").val(0);
						$("#txtMontoUU").val(0);
					}else{
						if((nomina == 'MINISTERIO DEL PODER POPULAR PARA LA EDUCACION' || nomina == 'MINISTERIO DEL PODER POPULAR PARA LA EDUCACION - JUBILADO') && tipo == 0 ){
							$("#f5").show();
							$("#txtMontoUU").val('');
						}else{
							$("#f5").hide();
							$("#txtMontoUU").val(0);
						}
						$("#f2").show();
						$("#txtMontoD").val('');
						
					}
					
				}else{
					$("#mensual").hide();
					$("#firmas").hide();
					
				}
			}
			
			function tipo_cliente(){
				tipo = $("input[name='tc']:checked").val(); 
				forma = $("#forma option:selected").text();
				nomina = $("#txtNomina option:selected").text();
				if(tipo == 0){
					$("#lblMontoV").html("Total Vacaciones:");
					if((nomina == 'MINISTERIO DEL PODER POPULAR PARA LA EDUCACION' || nomina == 'MINISTERIO DEL PODER POPULAR PARA LA EDUCACION - JUBILADO') && forma != 'Nomina') $("#f5").show();
				}else{
					$("#lblMontoV").html("Total Bono Recreacional Julio:");
					$("#f5").hide();
				}
				verifica_fpago(); 
			}
			
			function tipo_nomina(){
				tipo = $("input[name='tc']:checked").val();
				forma = $("#forma option:selected").text();
				nomina = $("#txtNomina option:selected").text();
				$("#mensaje").html("");
				$("#firmas").show();
				$("#btnImprimir").hide();
				if((nomina == 'MINISTERIO DEL PODER POPULAR PARA LA EDUCACION' || nomina == 'MINISTERIO DEL PODER POPULAR PARA LA EDUCACION - JUBILADO') && forma != 'Nomina' && tipo == 0){
					$("#f5").show();
				}else{
					$("#f5").hide();
				}
				verifica_fpago();
			}
			
		</script>
	</head>
	<body style="background-color: #FFFACD;"><center><br><br><h2>Calculo de Capacidad de Endeudamiento</h2><br><br><br>
			
			<table>
				<tr>
				<td>Cedula: </td>
				<td>
				<input type="text" 	name="txtCed" id="txtCed" class="inputxt" style="width: 220px;"	value='' onkeypress="return isNumberKey(event)">
				</td>
				</tr>
				<tr>
				<td>Nombre: </td>
				<td>
				<input type="text" 	name="txtNom" id="txtNom" class="inputxt" style="width: 220px;"	value='' >
				</td>
				</tr>
			<tr>
				<td class="formulario">Nomina <font color='#B70000'>(*)</font>:</td>
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
			<select name="txtCobrado"	id="txtCobrado" style="width: 460px;" onchange="verifica_credinfo();">
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
				<option>MERCANTIL</option> 
				<option disabled="true" SUR</option>
				<option>CARONI</option>
				<option disabled="true">CARIBE</option>
			</select>
		</td>
		</tr>
				
				
			</table>
			<br><br>
			<label id ="lbltipo">Forma Del Credito: </label>
			<select id="forma" style="width: 120px;" onchange="verifica_fpago();">
				<option>Seleccionar</option>
				<option>Nomina</option>
				<option>Banco</option>
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
					<input type="submit" class="inputxt" value='Realizar Calculo' id='btnCalcular' onclick="calcular()"/>
					</td>
				</tr>
			</table>
			<br><br><br>
			<div id="mensaje"></div>
			<br>
			<br>
			<input type="button" class="inputxt" style="display: none;" value='Imprimir Calculos'  id='btnImprimir' onclick="Imprimir();"/>
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

	</body>
</html>