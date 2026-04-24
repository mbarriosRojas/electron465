<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title><?php echo __TITLE__ ?></title>
  <link href="<?php echo __CSS__ ?>__style.css.php?url=<?php echo __LOCALWWW__ ?>" rel="stylesheet" type="text/css" />
  
  <link type="text/css" href="<?php echo __CSS__ ?>ui-lightness/jquery-ui-1.8.6.custom.css" rel="stylesheet" />
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>general/Global.js"></script>
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>jquery/jquery-1.4.2.min.js"></script>
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>jquery/jquery-ui-1.8.6.custom.min.js"></script>
<script>
	function Imprimir (){
		
		$("#lblPresupuesto").hide();
		$("#lblAbono").hide();
		$("#txtModelo").hide();
		$("#txtAbono").hide();
		$("#btnImprimir").hide();
		$("#btnCalcular").hide();
		$("#btnRecalcular").hide();
		window.print();	
		
	}
	
	/**
	 * Calcular fin de descuento
	 */
	
	function Calcular_Fin_Descuento(cuotas, periodo, dia_inicio, mes_inicio, ano_inicio) {
		
		
		var dia_fin = 0;
		var mes_fin = 0;
		var ano_fin = 0;
		var base_mes = 0;
		var tiempo = 0;
		switch(periodo) {
			case '0':
				base_mes = 1 / 4;
				break;
			case '1':
				base_mes = 1 / 2;
				break;
			case '2':
				base_mes = 1 / 2;
				break;
			case '3':
				base_mes = 1 / 2;
				break;
			case '4':
				base_mes = 1;
				break;
			case '5':
				base_mes = 3;
				break;
			case '6':
				base_mes = 6;
				break;
			case '7':
				base_mes = 12;
				break;
		}
		
		tiempo = (parseInt(cuotas) - 1) * base_mes;
		tiempo_picado = String(tiempo).split('.');
		var ano_t = parseInt(parseInt(tiempo_picado[0]) / 12);
		ano_t += parseInt(ano_inicio);
		mes_t = parseInt(tiempo_picado[0]) % 12;
		dia_t = parseInt(dia_inicio);
		if (tiempo_picado[1] != null) {
			switch(parseInt(tiempo_picado[1])) {
				case 25:
					dia_t += 7;
					break;
				case 5:
					dia_t += 15;
					break;
				case 75:
					dia_t += 21;
					break;
			}
		}
		
		if (dia_t > 30) {
			mes_t += 1;
			diferencia = dia_t - 30;
			dia_t = diferencia;
		}
	
		var suma_meses = parseInt(mes_t) + parseInt(mes_inicio);
		if (suma_meses > 12) {
			ano_t += 1;
			mes_t = suma_meses - 12;
		} else {
			mes_t = suma_meses;
		}
		
		return  ano_t + '-'  + mes_t + '-' + dia_t;
		
	}
		
	
	
	
	/**
	 * 	Fecha desde Hasta
	 *  @oaram Valor del Mes por combos
	 *  @return array
	 */
	function fechadh(id, ano){
		var sMes = new Array();
		switch (id){
			case "1":
				sMes['desd'] = ano + '-01-01';  
				sMes['hast'] = ano + '-01-31';
				break;
			case "2":
				sMes['desd'] = ano + '-02-01';  
				sMes['hast'] = ano + '-02-28';
				break;
			case "3":
				sMes['desd'] = ano + '-03-01';
				sMes['hast'] = ano + '-03-31';
				break;
			case "4":
				sMes['desd'] = ano + '-04-01'; 
				sMes['hast'] = ano + '-04-30';
				break;
			case "5":
				sMes['desd'] = ano + '-05-01';  
				sMes['hast'] = ano + '-05-31';
				break;
			case "6":
				sMes['desd'] = ano + '-06-01'; 
				sMes['hast'] = ano + '-06-30';
				break;
			case "7":
				sMes['desd'] = ano + '-07-01'; 
				sMes['hast'] = ano + '-07-31';
				break;
			case "8":
				sMes['desd'] = ano + '-08-01';  
				sMes['hast'] = ano + '-08-31';
				break;
			case "9":
				sMes['desd'] = ano + '-09-01';  
				sMes['hast'] = ano + '-09-30';
				break;
			case "10":
				sMes['desd'] = ano + '-10-01'; 
				sMes['hast'] = ano + '-10-31';
				break;
			case "11":
				sMes['desd'] = ano + '-11-01';  
				sMes['hast'] = ano + '-11-30';
				break;
			case "12":
				sMes['desd'] = ano + '-12-01';  
				sMes['hast'] = ano + '-12-31';
				break;
		}
		
		return sMes;
	}
	
	$(function() {
		/**
		 * Lista de Modelos
		 */	
		CodigoSRand();	 
		if($("#txtMuestra").val() == '1' ){
			$('#plan_financiamiento').show();
		}else{
			$('#plan_financiamiento').hide();
		}
		$("#txtModelo").autocomplete({			
			source: function( request, response ) {
				$.ajax({
					type: "POST",
					url: "<?php echo base_url(); ?>index.php/cooperativa/M_json/inventario/modelo/<?php echo $tipo; ?>",
					data: "nombre=" + $("#txtModelo").val(),
					dataType: "json",
					success: function(data) {
						response( $.map( data.nombres, function( item ) {
							return {
								label: item,
							value: item
							}
						}));
					},
				});
			}
		}); //Fin de Modelo
		var ruta = sUrl + '/system/application/libraries/tcpdf/images/catalogo/';
		
		//alert(ruta+$("#mod").val());
		if ($("#mod").val() != '') {
			$("#foto").attr("src", ruta + $("#mod").val());
			$('#foto').attr('src', $('#foto').attr('src') + '?' + Math.random());
		}else{
			$("#foto").attr("src", sImg + "nodisponible.jpg");
			$('#foto').attr('src', $('#foto').attr('src') + '?' + Math.random());
		}
	});

	function CodigoSRand(){
		$.ajax({
			url : sUrlP + 'Generar_SRand',
			type : "POST",
			success : function(html) {
				$("#NumeroSerie").html("<font style='font-size:20px;'><b>CODIGO DE CERTIFICADO : <label id='certificado'>" + html +  "</label></b></font><BR>");
			},
			error : function(html) {
				alert(html);
			},
		});
	};

	function BModelo(sUrl){
		modelo =  document.getElementById("txtModelo").value;
		abono = 0;
		var cantidad =0;
		//if(document.getElementById("txtAbono").value != ''){ abono = document.getElementById("txtAbono").value;}
		sUrl2 = sUrl + "/index.php/cooperativa/BModelo/1";
		$.ajax({
			url: sUrl2,
			type: "POST",
			data: "modelo=" + modelo,
			dataType : "json",
			success: function(data){
				if(data['equipo'] != ''){
					if(data['precio_oficina'] == 0){
						document.getElementById("txtCalculo").value = data['venta']  - abono;
					}else{
						document.getElementById("txtCalculo").value = data['precio_oficina']  - abono;
					}
					aux = modelo.substring(0,4);

					if(aux == 'MOTO'){
						var texto = 'MODELO: ' + modelo + ', DETALLE:' + data['detalle'];
						$("#txtDes").val(texto);
					}else{
						var texto = data['equipoD'] + ', MARCA: ' + data['marca'] + ', MODELO: ' + modelo + ', DETALLE:' + data['detalle'];
						$("#txtDes").val(texto);
					}
					var porcen = document.getElementById("txtPorcentaje").value = data['porcentaje'];
					//alert(porcen);
					cantidad = data['cant_ser'];
					alert("POSEE EN EXISTENCIA "+data['cant_ser'] + ' SERIALES....');
					if (data['foto'] != '') {
						$("#mod").val(data['foto']);
					}else{
						$("#mod").val("");
					}
				}	else{
					if(document.getElementById("txtCalculo").value == ''){	
						document.getElementById("txtDes").value = 'INTENTEN OTRO MODELO';
					}
				}
			},
			error: function(html){
				alert('FALLO AL CARGAR DATOS');
			},
		});
		alert('Recuerde usar el boton Imprimir para realizar el proceso adecuadamente... ');
	};
	
	function limpiar(item){
		item.value = "";
	}

	function Validar(item){
		//Cuotas Especiales
		if($("#txtMT1").val() == ""){$("#txtMT1").val(0)}
		A = parseInt($("#txtMT1").val());
		if($("#txtMT2").val() == ""){$("#txtMT2").val(0)}
		B = parseInt($("#txtMT2").val());
		if($("#txtMT3").val() == ""){$("#txtMT3").val(0)}
		C = parseInt($("#txtMT3").val());
		if($("#txtMT4").val() == ""){$("#txtMT4").val(0)}
		D = parseInt($("#txtMT4").val());
		if($("#txtMT10").val() == ""){$("#txtMT10").val(0)}
		E = parseInt($("#txtMT10").val());
		if($("#txtMT11").val() == ""){$("#txtMT11").val(0)}
		F = parseInt($("#txtMT11").val());
		if($("#txtMT12").val() == ""){$("#txtMT12").val(0)}
		G = parseInt($("#txtMT12").val());
		if($("#txtMT13").val() == ""){$("#txtMT13").val(0)}
		H = parseInt($("#txtMT13").val());
		valor =  parseInt($("#txtValor").val());
		cuotas =  parseFloat($("#txtCuotas").val());
		monto_solicitado =  parseInt($("#txtMonto").val());
		monto_credito = Math.round(valor * cuotas) ;
		abono_especial = A + B + C + D + E + F + G + H;
		calculo = parseInt($("#txtCalculo").val());
		abono = parseInt($("#abono").val());
		total = parseInt(calculo - abono);
		monto_final = parseInt(monto_credito) + parseInt(total) - parseInt(abono_especial);
		ct = Math.round(monto_final / valor);
		switch (item.value) {
			case "1":
				if(item.checked  == true){
					$('#txtM1').val(ct)
					$('#txtMT5').val(ct*valor)
					//HABILITA CAJA DE CUOTA MENSUAL
					$('#txtM1').attr("disabled", false);
					//DESABILITA LAS DEMAS CHECK BOX
					$('#2').attr("disabled", true);
					$('#3').attr("disabled", true);
					$('#4').attr("disabled", true);
					$('#5').attr("disabled", true);
					//IGUALO A 0 LAS DEMAS CAJAS
					$('#txtM2').val(0);
					$('#txtM3').val(0);
					$('#txtM4').val(0);
					$('#txtM5').val(0);
					$('#txtMT6').val(0);
					$('#txtMT7').val(0);
					$('#txtMT8').val(0);
					$('#txtMT9').val(0);
					//COLOCA EL VALOR POR DEFECTO EN LAS CHECK BOX EN FASE
					document.getElementById("2").checked = false;
					document.getElementById("3").checked = false;
					document.getElementById("4").checked = false;
					document.getElementById("5").checked = false;
				}else{
					//IGUALA TODAS LAS VARIABLES A 0
					$('#txtM1').val(0);
					$('#txtM2').val(0);
					$('#txtM3').val(0);
					$('#txtM4').val(0);
					$('#txtM5').val(0);
					$('#txtMT5').val(0);
					$('#txtMT6').val(0);
					$('#txtMT7').val(0);
					$('#txtMT8').val(0);
					$('#txtMT9').val(0);
					//HABILITA TODOS LOS CHECK BOX
					$('#2').attr("disabled", false);
					$('#3').attr("disabled", false);
					$('#4').attr("disabled", false);
					$('#5').attr("disabled", false);
					//DESABILITA LA CAJA DE CUOTAS
					bloquear_cajas();
				}
				break
			case "2":
				$('#txtM1').val(0);
				$('#txtM3').val(0);
				$('#txtM5').val(0);
				$('#txtMT5').val(0);
				$('#txtMT7').val(0);
				$('#txtMT9').val(0);
				if(item.checked  == true){
					$('#txtM2').attr("disabled", false);
					if($("#4").is(':checked')  == true){
						cuota_asignada=Math.round(ct / 2);
						$('#txtM2').val(cuota_asignada);
						$('#txtMT6').val( cuota_asignada*valor );
						$('#txtM4').val(cuota_asignada);
						$('#txtMT8').val(cuota_asignada*valor );
						$('#txtM4').attr("disabled", false);
					}else{
						$('#txtM2').val(ct);
						$('#txtMT6').val(ct*valor);
						$('#txtM4').val(0);
						$('#txtMT8').val(0);
					}
					$('#1').attr("disabled", true);
					$('#3').attr("disabled", true);
					$('#5').attr("disabled", true);
				}else{
					$('#txtM2').val(0);
					$('#txtMT6').val(0);
					$('#txtM2').attr("disabled", true);
					if($("#4").is(':checked')  == true){
						$('#txtM4').val(ct);
						$('#txtMT8').val(ct*valor );
					}else{
						$('#txtM4').val(0);
						$('#txtMT8').val(0);
						$('#1').attr("disabled", false);
						$('#3').attr("disabled", false);
						$('#5').attr("disabled", false);
						bloquear_cajas();
					}
				}
				break
			case "3":
				$('#txtM1').val(0);
				$('#txtM2').val(0);
				$('#txtM4').val(0);
				$('#txtMT5').val(0);
				$('#txtMT6').val(0);
				$('#txtMT8').val(0);
				if(item.checked  == true){
					$('#txtM3').attr("disabled", false);
					if($("#5").is(':checked')  == true){
						cuota_asignada=Math.round(ct / 2);
						$('#txtM3').val(cuota_asignada);
						$('#txtMT7').val( cuota_asignada*valor );
						$('#txtM5').val(cuota_asignada);
						$('#txtMT9').val( cuota_asignada*valor );
						$('#txtM5').attr("disabled", false);
					}else{
						$('#txtM3').val(ct);
						$('#txtMT7').val(ct*valor);
						$('#txtM5').val(0);
						$('#txtMT9').val(0);
					}
					$('#1').attr("disabled", true);
					$('#2').attr("disabled", true);
					$('#4').attr("disabled", true);
				}else{
					$('#txtM3').val(0);
					$('#txtMT7').val(0);
					$('#txtM3').attr("disabled", true);
					if($("#5").is(':checked')  == true){
						$('#txtM5').val(ct);
						$('#txtMT9').val(ct*valor );
					}else{
						$('#txtM5').val(0);
						$('#txtMT9').val(0);
						$('#1').attr("disabled", false);
						$('#2').attr("disabled", false);
						$('#4').attr("disabled", false);
						bloquear_cajas();
					}
				}
				break
			case "4":
				$('#txtM1').val(0);
				$('#txtM3').val(0);
				$('#txtM5').val(0);
				$('#txtMT5').val(0);
				$('#txtMT7').val(0);
				$('#txtMT9').val(0);
				if(item.checked  == true){
					$('#txtM4').attr("disabled", false);
					if($("#2").is(':checked')  == true){
						cuota_asignada=Math.round(ct / 2);
						$('#txtM2').val(cuota_asignada);
						$('#txtMT6').val( cuota_asignada*valor );
						$('#txtM4').val(cuota_asignada);
						$('#txtMT8').val(cuota_asignada*valor );
						$('#txtM2').attr("disabled", false);
					}else{
						$('#txtM4').val(ct);
						$('#txtMT8').val(ct*valor);
						$('#txtM2').val(0);
						$('#txtMT6').val(0);
					}
					$('#1').attr("disabled", true);
					$('#3').attr("disabled", true);
					$('#5').attr("disabled", true);
				}else{
					$('#txtM4').val(0);
					$('#txtMT8').val(0);
					$('#txtM4').attr("disabled", true);
					if($("#2").is(':checked')  == true){
						$('#txtM2').val(ct);
						$('#txtMT6').val(ct*valor );
					}else{
						$('#txtM2').val(0);
						$('#txtMT6').val(0);
						$('#1').attr("disabled", false);
						$('#3').attr("disabled", false);
						$('#5').attr("disabled", false);
						bloquear_cajas();
					}
				}
				break
			case "5":
				$('#txtM1').val(0);
				$('#txtM2').val(0);
				$('#txtM4').val(0);
				$('#txtMT5').val(0);
				$('#txtMT6').val(0);
				$('#txtMT8').val(0);
				if(item.checked  == true){
					$('#txtM5').attr("disabled", false);
					if($("#3").is(':checked')  == true){
						cuota_asignada=Math.round(ct / 2);
						$('#txtM3').val(cuota_asignada);
						$('#txtMT7').val( cuota_asignada*valor );
						$('#txtM5').val(cuota_asignada);
						$('#txtMT9').val( cuota_asignada*valor );
						$('#txtM3').attr("disabled", false);
					}else{
						$('#txtM5').val(ct);
						$('#txtMT9').val(ct*valor);
						$('#txtM3').val(0);
						$('#txtMT7').val(0);
					}
					$('#1').attr("disabled", true);
					$('#2').attr("disabled", true);
					$('#4').attr("disabled", true);
				}else{
					$('#txtM5').val(0);
					$('#txtMT9').val(0);
					$('#txtM5').attr("disabled", true);
					if($("#3").is(':checked')  == true){
						$('#txtM3').val(ct);
						$('#txtMT7').val(ct*valor );
					}else{
						$('#txtM3').val(0);
						$('#txtMT7').val(0);
						$('#1').attr("disabled", false);
						$('#2').attr("disabled", false);
						$('#4').attr("disabled", false);
						bloquear_cajas();
					}
				}
				break
		}
	};

	function bloquear_cajas(){
		$('#txtM1').attr("disabled", true);
		$('#txtM2').attr("disabled", true);
		$('#txtM3').attr("disabled", true);
		$('#txtM4').attr("disabled", true);
		$('#txtM5').attr("disabled", true);
		$('#txtMT5').attr("disabled", true);
		$('#txtMT6').attr("disabled", true);
		$('#txtMT7').attr("disabled", true);
		$('#txtMT8').attr("disabled", true);
		$('#txtMT9').attr("disabled", true);
	}

	function Calcular_Total(item){
		if($("#txtMT1").val() == ""){$("#txtMT1").val(0)}
		A = parseInt($("#txtMT1").val());
		if($("#txtMT2").val() == ""){$("#txtMT2").val(0)}
		B = parseInt($("#txtMT2").val());
		if($("#txtMT3").val() == ""){$("#txtMT3").val(0)}
		C = parseInt($("#txtMT3").val());
		if($("#txtMT4").val() == ""){$("#txtMT4").val(0)}
		D = parseInt($("#txtMT4").val());
		if($("#txtMT10").val() == ""){$("#txtMT10").val(0)}
		E = parseInt($("#txtMT10").val());
		if($("#txtMT11").val() == ""){$("#txtMT11").val(0)}
		F = parseInt($("#txtMT11").val());
		if($("#txtMT12").val() == ""){$("#txtMT12").val(0)}
		G = parseInt($("#txtMT12").val());
		if($("#txtMT13").val() == ""){$("#txtMT13").val(0)}
		H = parseInt($("#txtMT13").val());
		
		//abono_nuevo = $("#txtAbono").val();
		valor =  parseInt($("#txtValor").val());
		cuotas =  parseFloat($("#txtCuotas").val());
		monto_solicitado =  parseInt($("#txtMonto").val());
		monto_credito = Math.round(valor * cuotas) ;
		abono_especial = A + B + C + D + E + F + G + H;
		calculo = parseInt($("#txtCalculo").val());
		abono = parseInt($("#abono").val());
		total = parseInt(calculo - abono);
		monto_final = parseInt(monto_credito) + parseInt(total) - parseInt(abono_especial);
		
		var resta = 0;
		var monto_n = 0;
		tiene = $("#txtMT1").val();
		switch(item.id){
			case '1'  :
				if(item.checked  == true){
					monto_n = $("#txtM1").val() * valor;
					$("#txtMT5").val(monto_n);
					resta = monto_final - monto_n;
					if(resta > 0){
						agrega_cuota = parseInt(resta) + parseInt(tiene);
						$("#txtMT1").val(agrega_cuota);
					}else{
						if(resta < 0){
							quitar_cuota = parseInt(tiene) + parseInt(resta);
							$("#txtMT1").val(quitar_cuota);
						}
					}
				}
				break;
			case 'txtM1'  :
				monto_n = $("#txtM1").val() * valor;
				$("#txtMT5").val(monto_n);
				resta = monto_final - monto_n;
				if(resta > 0){
					agrega_cuota = parseInt(resta) + parseInt(tiene);
					$("#txtMT1").val(agrega_cuota);
				}else{
					if(resta < 0){
						quitar_cuota = parseInt(tiene) + parseInt(resta);
						$("#txtMT1").val(quitar_cuota);
					}
				}
				break;
			case 'txtM2'  :
				monto_n = $("#txtM2").val() * valor;
				$("#txtMT6").val(monto_n);
				monto_n_2 = $("#txtMT8").val();
				resta = monto_final - monto_n - monto_n_2;
				if(resta > 0){
					agrega_cuota = parseInt(resta) + parseInt(tiene);
					$("#txtMT1").val(agrega_cuota);
				}else{
					if(resta < 0){
						quitar_cuota = parseInt(tiene)+ parseInt(resta);
						$("#txtMT1").val(quitar_cuota);
					}
				}
				break;
			case '2'  :
				if(item.checked  == true){
					monto_n = $("#txtM2").val() * valor;
					$("#txtMT6").val(monto_n);
					monto_n_2 = $("#txtMT8").val();
					resta = monto_final - monto_n - monto_n_2;
					if(resta > 0){
						agrega_cuota = parseInt(resta) + parseInt(tiene);
						$("#txtMT1").val(agrega_cuota);
					}else{
						if(resta < 0){
							quitar_cuota = parseInt(tiene)+ parseInt(resta);
							$("#txtMT1").val(quitar_cuota);
						}
					}
				}
				break;
			case 'txtM4'  :
				monto_n = $("#txtM4").val() * valor;
				$("#txtMT8").val(monto_n);
				monto_n_2 = $("#txtMT6").val();
				resta = monto_final - monto_n - monto_n_2;
				if(resta > 0){
					agrega_cuota = parseInt(resta) + parseInt(tiene);
					$("#txtMT1").val(agrega_cuota);
				}else{
					if(resta < 0){
						quitar_cuota = parseInt(tiene) + parseInt(resta);
						$("#txtMT1").val(quitar_cuota);
					}
				}
				break;
			case '4'  :
				if(item.checked  == true){
					monto_n = $("#txtM4").val() * valor;
					$("#txtMT8").val(monto_n);
					monto_n_2 = $("#txtMT6").val();
					resta = monto_final - monto_n - monto_n_2;
					if(resta > 0){
						agrega_cuota = parseInt(resta) + parseInt(tiene);
						$("#txtMT1").val(agrega_cuota);
					}else{
						if(resta < 0){
							quitar_cuota = parseInt(tiene)+ parseInt(resta);
							$("#txtMT1").val(quitar_cuota);
						}
					}
				}
				break;
			case 'txtM3'  :
				monto_n = $("#txtM3").val() * valor;
				$("#txtMT7").val(monto_n);
				monto_n_2 = $("#txtMT9").val();
				resta = monto_final - monto_n - monto_n_2;
				if(resta > 0){
					agrega_cuota = parseInt(resta) + parseInt(tiene);
					$("#txtMT1").val(agrega_cuota);
				}else{
					if(resta < 0){
						quitar_cuota = parseInt(tiene) + parseInt(resta);
						$("#txtMT1").val(quitar_cuota);
					}
				}
				break;
			case '3'  :
				if(item.checked  == true){
					monto_n = $("#txtM3").val() * valor;
					$("#txtMT7").val(monto_n);
					monto_n_2 = $("#txtMT9").val();
					resta = monto_final - monto_n - monto_n_2;
					if(resta > 0){
						agrega_cuota = parseInt(resta) + parseInt(tiene);
						$("#txtMT1").val(agrega_cuota);
					}else{
						if(resta < 0){
							quitar_cuota = parseInt(tiene)  + parseInt(resta);
							$("#txtMT1").val(quitar_cuota);
						}
					}
				}
				break;
			case 'txtM5'  :
				monto_n = $("#txtM5").val() * valor;
				$("#txtMT9").val(monto_n);
				monto_n_2 = $("#txtMT7").val();
				resta = monto_final - monto_n - monto_n_2;
				if(resta > 0){
					agrega_cuota = parseInt(resta) + parseInt(tiene);
					$("#txtMT1").val(agrega_cuota);
				}else{
					if(resta < 0){
						quitar_cuota = parseInt(tiene) + parseInt(resta);
						$("#txtMT1").val(quitar_cuota);
					}
				}
				break;
			case '5'  :
				if(item.checked  == true){
					monto_n = $("#txtM5").val() * valor;
					$("#txtMT9").val(monto_n);
					monto_n_2 = $("#txtMT7").val();
					resta = monto_final - monto_n - monto_n_2;
					if(resta > 0){
						agrega_cuota = parseInt(resta) + parseInt(tiene);
						$("#txtMT1").val(agrega_cuota);
					}else{
						if(resta < 0){
							quitar_cuota = parseInt(tiene) + parseInt(resta);
							$("#txtMT1").val(quitar_cuota);
						}
					}
				}
				break;
			default:
				$('#1').attr("disabled", false);
				$('#2').attr("disabled", false);
				$('#3').attr("disabled", false);
				$('#4').attr("disabled", false);
				$('#5').attr("disabled", false);
				$('#1').attr("checked", false);
				$('#2').attr("checked", false);
				$('#3').attr("checked", false);
				$('#4').attr("checked", false);
				$('#5').attr("checked", false);
				$('#txtM1').val(0);
				$('#txtM2').val(0);
				$('#txtM3').val(0);
				$('#txtM4').val(0);
				$('#txtM5').val(0);
				$('#txtMT5').val(0);
				$('#txtMT6').val(0);
				$('#txtMT7').val(0);
				$('#txtMT8').val(0);
				$('#txtMT9').val(0);
				bloquear_cajas();
				break;
		}
	};

	function Asignar(meses_a){
	//alert(meses_a);
		cuota = $("#txtCuotas").val();
		monto_a = Math.round(cuota*meses_a);
		calculo = parseInt($("#txtCalculo").val());
		abono = parseInt($("#abono").val());
		total = parseInt(calculo - abono);

		monto_l = Formato(monto_a,"BS.");
		monto_t = parseInt(monto_a);
		monto_t2 = Formato(monto_t + total,"BS.");
		$("#monto_aux").html(monto_l);
		$("#monto_aux2").html(monto_t2);
		$("#abono_aux").html(Formato($("#txtAbono").val()));
		$("#abono_aux2").html( Formato(parseInt(monto_t + total) - parseInt($("#txtAbono").val())));
	}

	function Formato(num,prefix){
		num = Math.round(parseFloat(num)*Math.pow(10,2))/Math.pow(10,2)
		prefix = prefix || '';
		num += '';
		var splitStr = num.split('.');
		var splitLeft = splitStr[0];
		var splitRight = splitStr.length > 1 ? '.' + splitStr[1] : '.00';
		splitRight = splitRight + '00';
		splitRight = splitRight.substr(0,3);
		var regx = /(\d+)(\d{3})/;
		while (regx.test(splitLeft)) {
			splitLeft = splitLeft.replace(regx, '$1' + ',' + '$2');
		}
		return splitLeft + splitRight +'  ' +prefix;
	}

	function crea_combos(meses_c){
		fecha_actual = new Date();
		dia = fecha_actual.getDate();
		mes = fecha_actual.getMonth() + 1;
		anio = fecha_actual.getFullYear();
		anio_f = anio;
		mes_f= mes;
		switch (parseInt(meses_c)){
			case 12:
				anio_f = anio_f + 1;
				break;
			case 18:
				anio_f = anio_f + 1;
				mes_f = mes_f+6;
				break;
			case 19:
				anio_f = anio_f + 1;
				mes_f = mes_f+7;
				break;
			case 20:
				anio_f = anio_f + 1;
				mes_f = mes_f+8;
				break;
			case 21:
				anio_f = anio_f + 1;
				mes_f = mes_f+9;
				break;
			case 22:
				anio_f = anio_f + 1;
				mes_f = mes_f+10;
				break;
			case 23:
				anio_f = anio_f + 1;
				mes_f = mes_f+11;
				break;
			case 24:
				anio_f = anio_f+2;
				break;
			case 30:
				anio_f = anio_f + 2;
				mes_f = mes_f+6;
				break;
			case 36:
				anio_f = anio_f+3;
				break;
		}

		if(parseInt(mes_f) > 12){
			anio_f = anio_f + 1;
			mes_f = mes_f-12;
		}
		//alert(anio_f);

		for(var j = 1 ;j<=8;j++){
			$("#txtAno"+j).html('');
			$('#txtAno'+j).append(new Option('----------------------',0, true, true));
			for(var i = parseInt(anio) ; i <= anio_f ; i++){
				$('#txtAno'+j).append(new Option(i, i, true, true));
			}
			$("#txtAno"+j +" option[value=0]").attr("selected","selected");
		}

	}
	
	function valida_meses(item){
	
		anioA = $("#txtAno"+item +" option:selected").val();
		meses_c = $('#cmbMeses option:selected').val();
		fecha_actual = new Date();
		dia = fecha_actual.getDate();
		mes = fecha_actual.getMonth() + 2;
		anio = fecha_actual.getFullYear();
		anio_f = anio;
		mes_f= mes;
		switch (parseInt(meses_c)){
			case 12:
				anio_f = anio_f + 1;
			break;
			case 18:
				anio_f = anio_f + 1;
				mes_f = mes_f+6;
			break;
			case 19:
				anio_f = anio_f + 1;
				mes_f = mes_f+7;
				break;
			case 20:
				anio_f = anio_f + 1;
				mes_f = mes_f+8;
				break;
			case 21:
				anio_f = anio_f + 1;
				mes_f = mes_f+9;
				break;
			case 22:
				anio_f = anio_f + 1;
				mes_f = mes_f+10;
				break;
			case 23:
				anio_f = anio_f + 1;
				mes_f = mes_f+11;
				break;
			case 24:
				anio_f = anio_f+2;
			break;
			case 30:
				anio_f = anio_f + 2;
				mes_f = mes_f+6;
			break;
			case 36:
				anio_f = anio_f+3;
			break;
			}
			
			if(parseInt(mes_f) > 12){
			anio_f = anio_f + 1;
			mes_f = mes_f-12;
			}
			valor_mes = $("#txtNominaPeriocidad"+item +" option:selected").val();
			if(anioA == anio){
				if(valor_mes < mes){
					$("#txtNominaPeriocidad"+item +" option[value=0]").attr("selected","selected");
					$("#txtAno"+item +" option[value=0]").attr("selected","selected");
				}
			}
			if(anioA == anio_f){
				if(valor_mes > mes_f){
					$("#txtNominaPeriocidad"+item +" option[value=0]").attr("selected","selected");
					$("#txtAno"+item +" option[value=0]").attr("selected","selected");
				}
			}
	
	}
	
	function Seleccion_Mes(item){
	document.getElementById("txtValor").value = item;
	}
	function soloNumeros(evt){
	if("which" in evt){
	keynum = evt.keyCode;
	}else{
	keynum = evt.which;
	}
	if((keynum>47 && keynum<58) || (keynum>95 && keynum<106) ||(keynum==13) || (keynum==8) || (keynum==9) ){
	return true;
	}else{
	return false;
	}
	}
</script>
</head>
<body style="background-color: #FFFACD;">
<center><br />
	<div id='NumeroSerie'> 
		<font style='font-size:20px; '><b>#: </b></font><BR>	
	</div>
	
	<form action="<?php echo __LOCALWWW__?>/index.php/cooperativa/Artefacto" method="POST">
		<table> 
			<tr>				
			 	<td><label id="lblPresupuesto"> Modelo: &nbsp;</label></td>
			 	<td>
					<div class="ui-widget">
						<input type="text" 	name="txtModelo" id="txtModelo" class="inputxt" style="width: 220px;"	value=''>						
					</div>
				</td>
			 </tr>
			 <tr>				
			 	<td><label id="lblAbono"> Abono: &nbsp;</label></td>
			 	<td>
					<div class="ui-widget">
						<input type="text" 	name="txtAbonoP" id="txtAbonoP" class="inputxt" style="width: 220px;"	value=''>						
					</div>
				</td>
			 </tr>	 
			 <tr>
			 	<td colspan="2">
			 		<input  type="hidden" value="<?php echo $venta?>" name="txtCalculo" id="txtCalculo" />			 	
					<input type="hidden" value="<?php echo $descripcion?>" name="txtDes" id="txtDes" class="inputxt"   style="width: 220px;"/>
					<input type="hidden" value="<?php echo $porcentaje?>" name="txtPorcentaje" id="txtPorcentaje" class="inputxt"   style="width: 220px;"/>
					<input type="hidden" value="<?php echo $muestra?>" name="txtMuestra" id="txtMuestra" class="inputxt"   style="width: 220px;"/>
					<input type="hidden" value="<?php echo $abono?>" name="abono" id="abono" class="inputxt"   style="width: 220px;"/>
					<input type="hidden" value="<?php echo $modelo?>" name="mod" id="mod" class="inputxt"   style="width: 220px;"/>
			 	</td>			 	
			 </tr>
			 <tr>
			 	<td colspan="2" align="center">
			 		<input type="submit" class="inputxt" value='Realizar Calculo' id='btnCalcular' onclick="BModelo('<?php echo __LOCALWWW__?>');"/>
			 	</td>
			 </tr>
		</table>
<br><br>
<font style='font-size:20px; '><b>PRESUPUESTO DE CREDITO</b></font><BR>
	<div>
		<img src="<?php echo __IMG__; ?>nodisponible.jpg" style="width:100px;height: 100px;" id ="foto" name="foto"/>
	</div>
	<?php
	echo $info;
?>
<BR>
<div id='plan_financiamiento'>

<?php
echo $lista;
?>
<table style='width: 80%' border=1> 			 
			<tr style="display: none;">				
			 	<td><label id="lblAbono"> Abono Inicial: &nbsp;</label>
					<div class="ui-widget">
						<input type="text" 	name="txtAbono" id="txtAbono" class="inputxt" onkeydown="return soloNumeros(event);" onblur="Asignar($('#cmbMeses option:selected').val());Validar(this);Calcular_Total(this);" style="width: 220px;"	value=0>						
					</div>
				</td>
				<td style="font-size: 14px;">
					<div id="abono_aux" name="abono_aux"><label id='label_abono'></label></div>
				</td>
				<td style="font-size: 14px;"><div  id="abono_aux2" name="abono_aux2"><label id='label_abono2'></label></div>
				</td>
			 </tr>
			 <!--<tr>	
			 	<td colspan="2" align="center">
			 		<input type="submit" class="inputxt" value='Recalcular' id='btnRecalcular' />
			 	</td>
			 </tr>!-->
</table>
<BR><BR>
	<font style='font-size:20px; '><b><label id='lblPlan'>PLAN DE PAGO</label></b></font><BR>
	<input type=hidden value=<?php echo $calculo; ?> name="txtMonto" id="txtMonto" />
	<input type=hidden value=<?php echo $abono; ?> name="txtAbonoInicial" id="txtAbonoInicial" />
	<input type=hidden value=12 name="txtValor" id="txtValor"/>
	<input type=hidden value=<?php echo $monto; ?> name="txtCuotas" id="txtCuotas"/>
	<table>
		<tr><td style="width: 240px">DESCRIPCI&Oacute;N</td><td style="width: 100px">CUOTAS</td><td style="width: 150px">MONTO</td></tr>
		<tr><td> 
			<select name="txtNominaPeriocidad1"	id="txtNominaPeriocidad1" class="inputxt" style="width: 220px;" onchange="valida_meses(1);">
				<option value=0>----------------------------</option>
				<option value=1>PAGARE - ENERO</option>
				<option value=2>PAGARE - FEBRERO</option>
				<option value=3>PAGARE - MARZO </option>
				<option value=4>PAGARE - ABRIL </option>
				<option value=5>PAGARE - MAYO </option>
				<option value=6>PAGARE - JUNIO</option>
				<option value=7>PAGARE - JULIO</option>
				<option value=8>PAGARE - AGOSTO</option>
				<option value=9>PAGARE - SEPTIEMBRE</option>
				<option value=10>PAGARE - OCTUBRE</option>
				<option value=11>PAGARE - NOVIEMBRE</option>
				<option value=12>PAGARE - DICIEMBRE</option>
			</select>
			</td>
			<td>
			<select name="txtAno1"	id="txtAno1" class="inputxt" style="width: 70px;" onchange="valida_meses(1);">
				<option value=2013>2013</option>
				
			</select>
			</td>
			<td>
				<input  type="text" value="" name="txtMT1" id="txtMT1" class="inputxt"  onkeydown="return soloNumeros(event);" onclick="limpiar(this);" onchange="Calcular_Total(this)"/>
			</td>
		</tr>
		<tr><td> 
			<select name="txtNominaPeriocidad2"	id="txtNominaPeriocidad2" class="inputxt" style="width: 220px;" onchange="valida_meses(2);">
				<option value=0>----------------------------</option>
				<option value=1>PAGARE - ENERO</option>
				<option value=2>PAGARE - FEBRERO</option>
				<option value=3>PAGARE - MARZO </option>
				<option value=4>PAGARE - ABRIL </option>
				<option value=5>PAGARE - MAYO </option>
				<option value=6>PAGARE - JUNIO</option>
				<option value=7>PAGARE - JULIO</option>
				<option value=8>PAGARE - AGOSTO</option>
				<option value=9>PAGARE - SEPTIEMBRE</option>
				<option value=10>PAGARE - OCTUBRE</option>
				<option value=11>PAGARE - NOVIEMBRE</option>
				<option value=12>PAGARE - DICIEMBRE</option>
			</select>
			</td>
			<td>
			<select name="txtAno2"	id="txtAno2" class="inputxt" style="width: 70px;" onchange="valida_meses(2);">
				<option value=2013>2013</option>
				
			</select>
			</td>
			<td>
				<input  type="text" value="" name="txtMT2" id="txtMT2" class="inputxt"  onclick="limpiar(this);" onkeydown="return soloNumeros(event);" onchange="Calcular_Total(this)"/>
			</td>
		</tr>			
		<tr><td> 
			<select name="txtNominaPeriocidad3"	id="txtNominaPeriocidad3" class="inputxt" style="width: 220px;" onchange="valida_meses(3);">
				<option value=0>----------------------------</option>
				<option value=1>PAGARE - ENERO</option>
				<option value=2>PAGARE - FEBRERO</option>
				<option value=3>PAGARE - MARZO </option>
				<option value=4>PAGARE - ABRIL </option>
				<option value=5>PAGARE - MAYO </option>
				<option value=6>PAGARE - JUNIO</option>
				<option value=7>PAGARE - JULIO</option>
				<option value=8>PAGARE - AGOSTO</option>
				<option value=9>PAGARE - SEPTIEMBRE</option>
				<option value=10>PAGARE - OCTUBRE</option>
				<option value=11>PAGARE - NOVIEMBRE</option>
				<option value=12>PAGARE - DICIEMBRE</option>
			</select>
			</td>
			<td>
			<select name="txtAno3"	id="txtAno3" class="inputxt" style="width: 70px;" onchange="valida_meses(3);">
				<option value=2013>2013</option>
				
			</select>
			</td>
			<td>
				<input  type="text" value="" name="txtMT3" id="txtMT3" class="inputxt"  onclick="limpiar(this);" onkeydown="return soloNumeros(event);" onchange="Calcular_Total(this)"/>
			</td>
		</tr>
		<tr><td> 
			<select name="txtNominaPeriocidad4"	id="txtNominaPeriocidad4" class="inputxt" style="width: 220px;" onchange="valida_meses(4);">
				<option value=0>----------------------------</option>
				<option value=1>PAGARE - ENERO</option>
				<option value=2>PAGARE - FEBRERO</option>
				<option value=3>PAGARE - MARZO </option>
				<option value=4>PAGARE - ABRIL </option>
				<option value=5>PAGARE - MAYO </option>
				<option value=6>PAGARE - JUNIO</option>
				<option value=7>PAGARE - JULIO</option>
				<option value=8>PAGARE - AGOSTO</option>
				<option value=9>PAGARE - SEPTIEMBRE</option>
				<option value=10>PAGARE - OCTUBRE</option>
				<option value=11>PAGARE - NOVIEMBRE</option>
				<option value=12>PAGARE - DICIEMBRE</option>
			</select>
			</td>
			<td>
			<select name="txtAno4"	id="txtAno4" class="inputxt" style="width: 70px;" onchange="valida_meses(4);">
				<option value=2013>2013</option>
				
			</select>
			</td>
			<td>
				<input  type="text" value="" name="txtMT4" id="txtMT4" class="inputxt" onclick="limpiar(this);" onkeydown="return soloNumeros(event);" onchange="Calcular_Total(this)"/>
			</td>
		</tr>
		<tr><td> 
			<select name="txtNominaPeriocidad5"	id="txtNominaPeriocidad5" class="inputxt" style="width: 220px;" onchange="valida_meses(5);">
				<option value=0>----------------------------</option>
				<option value=1>PAGARE - ENERO</option>
				<option value=2>PAGARE - FEBRERO</option>
				<option value=3>PAGARE - MARZO </option>
				<option value=4>PAGARE - ABRIL </option>
				<option value=5>PAGARE - MAYO </option>
				<option value=6>PAGARE - JUNIO</option>
				<option value=7>PAGARE - JULIO</option>
				<option value=8>PAGARE - AGOSTO</option>
				<option value=9>PAGARE - SEPTIEMBRE</option>
				<option value=10>PAGARE - OCTUBRE</option>
				<option value=11>PAGARE - NOVIEMBRE</option>
				<option value=12>PAGARE - DICIEMBRE</option>
			</select>
			</td>
			<td>
			<select name="txtAno5"	id="txtAno5" class="inputxt" style="width: 70px;" onchange="valida_meses(5);" >
				<option value=2013>2013</option>
				
			</select>
			</td>
			<td>
				<input  type="text" value="" name="txtMT10" id="txtMT10" class="inputxt" onclick="limpiar(this);" onkeydown="return soloNumeros(event);" onchange="Calcular_Total(this)"/>
			</td>
		</tr>
		<tr><td> 
			<select name="txtNominaPeriocidad6"	id="txtNominaPeriocidad6" class="inputxt" style="width: 220px;" onchange="valida_meses(6);">
				<option value=0>----------------------------</option>
				<option value=1>PAGARE - ENERO</option>
				<option value=2>PAGARE - FEBRERO</option>
				<option value=3>PAGARE - MARZO </option>
				<option value=4>PAGARE - ABRIL </option>
				<option value=5>PAGARE - MAYO </option>
				<option value=6>PAGARE - JUNIO</option>
				<option value=7>PAGARE - JULIO</option>
				<option value=8>PAGARE - AGOSTO</option>
				<option value=9>PAGARE - SEPTIEMBRE</option>
				<option value=10>PAGARE - OCTUBRE</option>
				<option value=11>PAGARE - NOVIEMBRE</option>
				<option value=12>PAGARE - DICIEMBRE</option>
			</select>
			</td>
			<td>
			<select name="txtAno6"	id="txtAno6" class="inputxt" style="width: 70px;" onchange="valida_meses(6);">
				<option value=2013>2013</option>
				
			</select>
			</td>
			<td>
				<input  type="text" value="" name="txtMT11" id="txtMT11" class="inputxt" onclick="limpiar(this);" onkeydown="return soloNumeros(event);" onchange="Calcular_Total(this)"/>
			</td>
		</tr>
		<tr><td> 
			<select name="txtNominaPeriocidad7"	id="txtNominaPeriocidad7" class="inputxt" style="width: 220px;" onchange="valida_meses(7);">
				<option value=0>----------------------------</option>
				<option value=1>PAGARE - ENERO</option>
				<option value=2>PAGARE - FEBRERO</option>
				<option value=3>PAGARE - MARZO </option>
				<option value=4>PAGARE - ABRIL </option>
				<option value=5>PAGARE - MAYO </option>
				<option value=6>PAGARE - JUNIO</option>
				<option value=7>PAGARE - JULIO</option>
				<option value=8>PAGARE - AGOSTO</option>
				<option value=9>PAGARE - SEPTIEMBRE</option>
				<option value=10>PAGARE - OCTUBRE</option>
				<option value=11>PAGARE - NOVIEMBRE</option>
				<option value=12>PAGARE - DICIEMBRE</option>
			</select>
			</td>
			<td>
			<select name="txtAno7"	id="txtAno7" class="inputxt" style="width: 70px;" onchange="valida_meses(7);">
				<option value=2013>2013</option>
				
			</select>
			</td>
			<td>
				<input  type="text" value="" name="txtMT12" id="txtMT12" class="inputxt" onclick="limpiar(this);" onkeydown="return soloNumeros(event);" onchange="Calcular_Total(this)"/>
			</td>
		</tr>
		<tr><td> 
			<select name="txtNominaPeriocidad8"	id="txtNominaPeriocidad8" class="inputxt" style="width: 220px;" onchange="valida_meses(8);">
				<option value=0>----------------------------</option>
				<option value=1>PAGARE - ENERO</option>
				<option value=2>PAGARE - FEBRERO</option>
				<option value=3>PAGARE - MARZO </option>
				<option value=4>PAGARE - ABRIL </option>
				<option value=5>PAGARE - MAYO </option>
				<option value=6>PAGARE - JUNIO</option>
				<option value=7>PAGARE - JULIO</option>
				<option value=8>PAGARE - AGOSTO</option>
				<option value=9>PAGARE - SEPTIEMBRE</option>
				<option value=10>PAGARE - OCTUBRE</option>
				<option value=11>PAGARE - NOVIEMBRE</option>
				<option value=12>PAGARE - DICIEMBRE</option>
			</select>
			</td>
			<td>
			<select name="txtAno8"	id="txtAno8" class="inputxt" style="width: 70px;" onchange="valida_meses(8);">
				<option value=2013>2013</option>
				
			</select>
			</td>
			<td>
				<input  type="text" value="" name="txtMT13" id="txtMT13" class="inputxt" onclick="limpiar(this);" onkeydown="return soloNumeros(event);" onchange="Calcular_Total(this)"/>
			</td>
		</tr>
		
		
				
		<tr><td>			
				<input type="checkbox" value="1" onclick="Validar(this);Calcular_Total(this);" id="1" />&nbsp;&nbsp; PAGARE MENSUAL
			</td>
			<td>
				<input  type="text" value="" name="txtM1" id="txtM1" class="inputxt" disabled="disabled" style="width: 70px; " onchange="Calcular_Total(this);" onkeydown="return soloNumeros(event);"/>
			</td>
			<td>
				<input  type="text" value="" name="txtMT5" id="txtMT5" class="inputxt" disabled="disabled" />
			</td>
		</tr>
		<tr style="display:none; "><td> 
				<input type="checkbox" value="2" onclick="Validar(this);Calcular_Total(this);" id="2" />&nbsp;&nbsp; MENSUAL LOS 10
			</td>
			<td>
				<input  type="text" value="" name="txtM2" id="txtM2" class="inputxt"  style="width: 70px;"disabled="disabled" onchange="Calcular_Total(this);" onkeydown="return soloNumeros(event);"/>
			</td>
			<td>
				<input  type="text" value="" name="txtMT6" id="txtMT6" class="inputxt"disabled="disabled" />
			</td>
		</tr>
		
		<tr style="display:none;"><td> 
				<input type="checkbox" value="3" onclick="Validar(this);Calcular_Total(this);" id="3"  />&nbsp;&nbsp; MENSUAL LOS 15
			</td>
			<td>
				<input  type="text" value="" name="txtM3" id="txtM3" class="inputxt"  style="width: 70px;"disabled="disabled" onchange="Calcular_Total(this);" onkeydown="return soloNumeros(event);"/>
			</td>
			<td>
				<input  type="text" value="" name="txtMT7" id="txtMT7" class="inputxt"disabled="disabled" />
			</td>
		</tr>
		<tr style="display:none;"><td> 
				<input type="checkbox" value="4" onclick="Validar(this);Calcular_Total(this);" id="4" />&nbsp;&nbsp; MENSUAL LOS 25
			</td>
			<td>
				<input  type="text" value="" name="txtM4" id="txtM4" class="inputxt" disabled="disabled" style="width: 70px;"onchange="Calcular_Total(this);" onkeydown="return soloNumeros(event);"/>
			</td>
			<td>
				<input  type="text" value="" name="txtMT8" id="txtMT8" class="inputxt"disabled="disabled" />
			</td>
		</tr>
		<tr style="display:none;"><td> 
				<input type="checkbox" value="5" onclick="Validar(this);Calcular_Total(this);" id="5"/>&nbsp;&nbsp; MENSUAL LOS 30
			</td>
			<td>
				<input  type="text" value="" name="txtM5" id="txtM5" class="inputxt" disabled="disabled" style="width: 70px;" onchange="Calcular_Total(this);" onkeydown="return soloNumeros(event);"/>
			</td>
			<td>
				<input  type="text" value="" name="txtMT9" id="txtMT9" class="inputxt"disabled="disabled" />
			</td>
		</tr>	
	</table><BR><BR>
</div>
	<input type="button" class="inputxt" value='Imprimir Calculos'  id='btnImprimir' onclick="Imprimir();"/>	<BR><BR>
	<table><tr><td style="width:500px" valign="bottom">
					<br><br>_______________________________________<br>
					FIRMA SUPERVISOR DE VENTAS<br><br><br>
					</td><td style="width:100px" align="center">
						_______________________________________<br>
					FIRMA ANALISTA DE VENTAS.- <br>
					ELABORADO EL: <?php echo date("Y/m/d"); ?>
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
			
			
			
			
</form>
</body>
</html>

