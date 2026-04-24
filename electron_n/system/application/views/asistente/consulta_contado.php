<html>
	<head>
		<?php $this -> load -> view("incluir/cabezera.php"); ?>
		<link href="<?php echo __CSS__ ?>imprimir.css" rel="stylesheet" type="text/css" media="print"/>
		<script>
			function Imprimir() {

				$("#lblPresupuesto").hide();
				$("#lblAbono").hide();
				$("#txtModelo").hide();
				$("#txtAbono").hide();
				$("#btnImprimir").hide();
				$("#btnCalcular").hide();
				$("#btnRecalcular").hide();
				window.print();
				return true;
			}

			$(function() {
				/**
				 * Lista de Modelos
				 */

				if ($("#txtMuestra").val() == '1') {
					$('#plan_financiamiento').show();
				} else {
					$('#plan_financiamiento').hide();
				}
				
				$("#txtModelo").autocomplete({
					source : function(request, response) {
						$.ajax({
							type : "POST",
							url : sUrlP + "M_json/inventario/modelo",
							data : "nombre=" + $("#txtModelo").val(),
							dataType : "json",
							success : function(data) {
								response($.map(data.nombres, function(item) {
									return {
										label : item,
										value : item
									}
								}));
							},
						});
					}
				});
				//Fin de Modelo

			});

			function BModelo() {
				modelo = document.getElementById("txtModelo").value;
				abono = 0;
				monto = 0;
				var cantidad = 0;
				sUrl2 = sUrlP + "BModelo/1";
				var ruta = sUrl + '/system/application/libraries/tcpdf/images/catalogo/';
				$.ajax({
					url : sUrl2,
					type : "POST",
					data : "modelo=" + modelo,
					dataType : "json",
					success : function(data) {

						if (data['equipo'] != '') {
							if (data['precio_oficina'] == 0) {
								$("#txtCalculo").val(data['venta']);
							} else {
								$("#txtCalculo").val(data['precio_oficina']);
							}
							aux = modelo.substring(0, 4);

							if (aux == 'MOTO') {
								var texto = 'MODELO: ' + modelo + ', DETALLE:' + data['detalle'];
								$("#txtDes").val(texto);
							} else {
								var texto = data['equipoD'] + ', MARCA: ' + data['marca'] + ', MODELO: ' + modelo + ', DETALLE:' + data['detalle'];
								$("#txtDes").val(texto);
							}
							var porcen = $("#txtPorcentaje").val(data['porcentaje']);

							cantidad = data['cant_ser'];
							alert("POSEE EN EXISTENCIA " + data['cant_ser'] + ' SERIALES....');
							if (data['foto'] != '') {
								$("#foto").attr("src", ruta + data['foto']);
								$('#foto').attr('src', $('#foto').attr('src') + '?' + Math.random());
								$("#mod").val(data['foto']);
							} else {
								$("#foto").attr("src", sImg + "nodisponible.jpg");
								$('#foto').attr('src', $('#foto').attr('src') + '?' + Math.random());
								$("#mod").val("");
							}
						} else {
							if (document.getElementById("txtCalculo").value == '') {
								document.getElementById("txtDes").value = 'INTENTEN OTRO MODELO';
							}
						}
						$("#txtAbonoInicial").val($('#txtCalculo').val());
						monto = ($('#txtCalculo').val() * 3) / 100;
						$('#txtCuotas').val(monto); 
						var preciosm = Formato($('#txtCalculo').val(),'BS');
						$("#info").html('<h2>' + $('#txtDes').val() + ' <BR>	PRECIO:  <b>' + preciosm +' </b></h2>');
						$('#txtMonto').val($('#txtCalculo').val());
						$('#lista').show();

					},
					error : function(html) {
						alert('FALLO AL CARGAR DATOS');
					},
				});
				alert('Recuerde usar el boton Imprimir para realizar el proceso adecuadamente... ');
			}

			function limpiar(item) {

				item.value = "";

			}

			function Formato(num, prefix) {
				num = Math.round(parseFloat(num) * Math.pow(10, 2)) / Math.pow(10, 2)
				prefix = prefix || '';
				num += '';
				var splitStr = num.split('.');
				var splitLeft = splitStr[0];
				var splitRight = splitStr.length > 1 ? '.' + splitStr[1] : '.00';
				splitRight = splitRight + '00';
				splitRight = splitRight.substr(0, 3);
				var regx = /(\d+)(\d{3})/;
				while (regx.test(splitLeft)) {
					splitLeft = splitLeft.replace(regx, '$1' + ',' + '$2');
				}
				return splitLeft + splitRight + '  ' + prefix;
			}

			function soloNumeros(evt) {
				if ("which" in evt) {
					keynum = evt.keyCode;
				} else {
					keynum = evt.which;
				}
				if ((keynum > 47 && keynum < 58) || (keynum > 95 && keynum < 106) || (keynum == 13) || (keynum == 8) || (keynum == 9)) {
					return true;
				} else {
					return false;
				}
			}

		</script>
	</head>
	<body style="background-color: #FFFACD;text-align: center;" >
		<center>
			<br>
			<h2>Presupuesto De Contado</h2>
			<br>
			<form action="<?php echo __LOCALWWW__?>/index.php/cooperativa/Artefacto" method="POST" onsubmit="return Imprimir();">

				<table>

					<tr>
						<td><label id="lblPresupuesto"> Modelo: &nbsp;</label></td>
						<td>
						<div class="ui-widget">
							<input type="text" 	name="txtModelo" id="txtModelo" class="inputxt" style="width: 460px;"	value=''>
						</div></td>
					</tr>
					<tr>
						<td colspan="2">
						<input  type="hidden" value="" name="txtCalculo" id="txtCalculo" />
						<input  type="hidden" value="" name="txtAbonoP" id="txtAbonoP" />
						
						<input type="hidden" value="" name="txtDes" id="txtDes" class="inputxt"   style="width: 220px;"/>
						<input type="hidden" value="" name="txtPorcentaje" id="txtPorcentaje" class="inputxt"   style="width: 220px;"/>
						<input type="hidden" value="1" name="txtMuestra" id="txtMuestra" class="inputxt"   style="width: 220px;"/>
						<input type="hidden" value="" name="mod" id="mod" class="inputxt"   style="width: 220px;"/>
						</td>
					</tr>
					<tr>
						<td colspan="2" align="center">
						<input type="button" class="inputxt" value='Realizar Calculo' id='btnCalcular' onclick="BModelo();"/>
						</td>
					</tr>
				</table>
				<br>
				<br>
				
				<BR>
					<br>
				
				<BR>
				<div>
					<img src="<?php echo __IMG__; ?>nodisponible.jpg" style="width:200px;height: 200px;" id ="foto" name="foto"/>
				</div>
				<br>
				<br>
				
				<BR>
					<br>
				
				<BR>
				<div id='info'>

				</div>
				<BR>
				<div id='plan_financiamiento' >
					<div id='lista'>
						
					</div>

					<BR>
					<BR>
					
					<BR>
					<input type=hidden value='' name="txtMonto" id="txtMonto" />
					<input type=hidden value='' name="txtAbonoInicial" id="txtAbonoInicial" />
					<input type=hidden value=12 name="txtValor" id="txtValor"/>
					<input type=hidden value='' name="txtCuotas" id="txtCuotas"/>

					<BR>
					<BR>
						<BR>
						<BR>
				</div>
				<input type="submit" class="inputxt" value='Imprimir'  id='btnImprimir'/>
				<BR>
				<BR>
				<table>
					<tr>
						<td style="width:500px" valign="bottom" >
						
						<br>
						_______________________________________
						<br>
						FIRMA SUPERVISOR DE VENTAS
						<br>
						<br>
						<br>
						</td><td style="width:100px" align="center"> _______________________________________
						<br>
						FIRMA ANALISTA DE VENTAS.-
						<br>
						ELABORADO EL: <?php echo date("Y/m/d"); ?>
						</td>
					</tr>

				</table>
				<center>
					<table border="0" style="width:70%; height: 100px">
						<tr>
							<td style="width:100px; height: 80px" > &nbsp; </td>
							<td align="center">
							<br>
							<br>
							_______________________________________
							<br>
							FIRMA RECIBI CONFORME.-
							<br>
							C.I:
							<br>
							APELLIDOS Y NOMBRE:
							<br>
							<br>
							<td style="width:100px; height: 80px" > &nbsp; </td>
						</tr>
						<tr>
							<td style="text-align: left;font-size: 10px;"><b>Pulgar Izquierdo</b></td>
							<td style="text-align: center">&nbsp;</td>
							<td style="text-align: right;font-size: 10px;"><b>Pulgar Derecho</b></td>
						</tr>
					</table>
				</center>

			</form>

	</body>
</html>