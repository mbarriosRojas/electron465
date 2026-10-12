<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title><?php echo __TITLE__ ?></title>
  <link href="<?php echo __CSS__ ?>__style.css.php?url=<?php echo __LOCALWWW__ ?>" rel="stylesheet" type="text/css" />
  
  <link type="text/css" href="<?php echo __CSS__ ?>ui-lightness/jquery-ui-1.8.6.custom.css" rel="stylesheet" />
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
	$(function() {
		/**
		 * Lista de Modelos
		 */	
		 
		if($("#txtMuestra").val() == '1' ){
			$('#plan_financiamiento').show();
		}else{
			$('#plan_financiamiento').hide();
		}
		
		$("#txtModelo").autocomplete({			
			source: function( request, response ) {
				$.ajax({
					type: "POST",
					url: "<?php echo base_url();?>index.php/cooperativa/M_json/inventario/modelo/moto",					
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
		
	});		
	function BModelo(sUrl){
		modelo =  $("#txtModelo").val();
		abono = 0;	
		var cantidad =0;
		/*if($("#txtAbono").val() != ''){ 
			abono = $("#txtAbono").val();
		}*/	
		sUrl = sUrl + "/index.php/cooperativa/BModelo";		
		$.ajax({
			url: sUrl,
			type: "POST",
			data: "modelo=" + modelo,
			dataType : "json",
			success: function(data){
				if(data['equipo'] != ''){
					//$("#txtCalculo").val(data['venta']  - abono);						
					$("#txtCalculo").val(data['venta']);
					$("#txtDes").val(data['equipo'] + ', MARCA: ' + data['marca'] + ', MODELO: ' + modelo);
					var porcen = $("#txtPorcentaje").val(data['porcentaje']);
						//alert(porcen);
						
					cantidad = data['cant_ser'];
					alert("POSEE EN EXISTENCIA "+data['cant_ser'] + ' SERIALES....');
				}	else{
						if($("#txtCalculo").value() == ''){	$("#txtDes").val('INTENTEN OTRO MODELO');}
				}					
			},
			error: function(html){
				alert('FALLO AL CARGAR DATOS');			
			},		
		});
		alert('Recuerde usar el boton Imprimir para realizar el proceso adecuadamente... ');
	}
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
		monto_credito = valor * cuotas;
		monto_credito = Math.round(valor * cuotas) ;				
		abono_especial = A + B + C + D + E + F + G + H;
		monto_inicial = $("#txtAbonoInicial").val();
		monto_final = parseInt(monto_credito) + parseInt(monto_inicial) - parseInt(abono_especial);
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
	}
	
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
		valor =  parseInt($("#txtValor").val());
		cuotas =  parseFloat($("#txtCuotas").val());				
		monto_solicitado =  parseInt($("#txtMonto").val());	
		monto_credito = valor * cuotas;
		monto_credito = Math.round(valor * cuotas) ;				
		abono_especial = A + B + C + D + E + F + G + H;
		monto_inicial = $("#txtAbonoInicial").val();
		monto_final = parseInt(monto_credito) + parseInt(monto_inicial) - parseInt(abono_especial);
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
		meses_c = $('[name="3M"]:checked').val();
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
	<form action="<?php echo __LOCALWWW__?>/index.php/cooperativa/Moto" method="POST">
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
			 	<td colspan="2">
			 		<input  type="hidden" value="<?php echo $venta?>" name="txtCalculo" id="txtCalculo" />			 	
					<input type="hidden" value="<?php echo $descripcion?>" name="txtDes" id="txtDes" class="inputxt"   style="width: 220px;"/>
					<input type="hidden" value="<?php echo $porcentaje?>" name="txtPorcentaje" id="txtPorcentaje" class="inputxt"   style="width: 220px;"/>
					<input type="hidden" value="<?php echo $muestra?>" name="txtMuestra" id="txtMuestra" class="inputxt"   style="width: 220px;"/>
			 	</td>			 	
			 </tr>
			 <tr>
			 	<td colspan="2" align="center">
			 		<input type="submit" class="inputxt" value='Realizar Calculo' id='btnCalcular' onclick="BModelo('<?php echo __LOCALWWW__?>');"/>
			 	</td>
			 </tr>
		</table>
<br><br>
<font style='font-size:20px; '><b>PRESUPUESTO</b></font><BR>
	<?php
	echo $info; 
?>
			
<BR>
<div id='plan_financiamiento'>
PLANES DE FINANCIAMIENTO
<?php
	echo $lista; 
?>
<BR><BR>
	<font style='font-size:20px; '><b><label id='lblPlan'>PLAN DE PAGO</label></b></font><BR>
	<input type=hidden value=<?php echo $calculo;?> name="txtMonto" id="txtMonto" />
	<input type=hidden value=<?php echo $abono;?> name="txtAbonoInicial" id="txtAbonoInicial" />
	<input type=hidden value=12 name="txtValor" id="txtValor"/>
	<input type=hidden value=<?php echo $monto;?> name="txtCuotas" id="txtCuotas"/>
	<table>
		<tr><td style="width: 240px">DESCRIPCI&Oacute;N</td><td style="width: 100px">CUOTAS</td><td style="width: 150px">MONTO</td></tr>
		<tr><td> 
			<select name="txtNominaPeriocidad1"	id="txtNominaPeriocidad1" class="inputxt" style="width: 220px;" onchange="valida_meses(1);">
				<option value=0>----------------------------</option>
				<option value=1>CUOTA ESPECIAL - ENERO</option>
				<option value=2>CUOTA ESPECIAL - FEBRERO</option>
				<option value=3>CUOTA ESPECIAL - MARZO </option>
				<option value=4>CUOTA ESPECIAL - ABRIL </option>
				<option value=5>CUOTA ESPECIAL - MAYO </option>
				<option value=6>CUOTA ESPECIAL - JUNIO</option>
				<option value=7>CUOTA ESPECIAL - JULIO</option>
				<option value=8>CUOTA ESPECIAL - AGOSTO</option>
				<option value=9>CUOTA ESPECIAL - SEPTIEMBRE</option>
				<option value=10>CUOTA ESPECIAL - OCTUBRE</option>
				<option value=11>CUOTA ESPECIAL - NOVIEMBRE</option>
				<option value=12>CUOTA ESPECIAL - DICIEMBRE</option>
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
				<option value=1>CUOTA ESPECIAL - ENERO</option>
				<option value=2>CUOTA ESPECIAL - FEBRERO</option>
				<option value=3>CUOTA ESPECIAL - MARZO </option>
				<option value=4>CUOTA ESPECIAL - ABRIL </option>
				<option value=5>CUOTA ESPECIAL - MAYO </option>
				<option value=6>CUOTA ESPECIAL - JUNIO</option>
				<option value=7>CUOTA ESPECIAL - JULIO</option>
				<option value=8>CUOTA ESPECIAL - AGOSTO</option>
				<option value=9>CUOTA ESPECIAL - SEPTIEMBRE</option>
				<option value=10>CUOTA ESPECIAL - OCTUBRE</option>
				<option value=11>CUOTA ESPECIAL - NOVIEMBRE</option>
				<option value=12>CUOTA ESPECIAL - DICIEMBRE</option>
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
				<option value=1>CUOTA ESPECIAL - ENERO</option>
				<option value=2>CUOTA ESPECIAL - FEBRERO</option>
				<option value=3>CUOTA ESPECIAL - MARZO </option>
				<option value=4>CUOTA ESPECIAL - ABRIL </option>
				<option value=5>CUOTA ESPECIAL - MAYO </option>
				<option value=6>CUOTA ESPECIAL - JUNIO</option>
				<option value=7>CUOTA ESPECIAL - JULIO</option>
				<option value=8>CUOTA ESPECIAL - AGOSTO</option>
				<option value=9>CUOTA ESPECIAL - SEPTIEMBRE</option>
				<option value=10>CUOTA ESPECIAL - OCTUBRE</option>
				<option value=11>CUOTA ESPECIAL - NOVIEMBRE</option>
				<option value=12>CUOTA ESPECIAL - DICIEMBRE</option>
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
				<option value=1>CUOTA ESPECIAL - ENERO</option>
				<option value=2>CUOTA ESPECIAL - FEBRERO</option>
				<option value=3>CUOTA ESPECIAL - MARZO </option>
				<option value=4>CUOTA ESPECIAL - ABRIL </option>
				<option value=5>CUOTA ESPECIAL - MAYO </option>
				<option value=6>CUOTA ESPECIAL - JUNIO</option>
				<option value=7>CUOTA ESPECIAL - JULIO</option>
				<option value=8>CUOTA ESPECIAL - AGOSTO</option>
				<option value=9>CUOTA ESPECIAL - SEPTIEMBRE</option>
				<option value=10>CUOTA ESPECIAL - OCTUBRE</option>
				<option value=11>CUOTA ESPECIAL - NOVIEMBRE</option>
				<option value=12>CUOTA ESPECIAL - DICIEMBRE</option>
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
				<option value=1>CUOTA ESPECIAL - ENERO</option>
				<option value=2>CUOTA ESPECIAL - FEBRERO</option>
				<option value=3>CUOTA ESPECIAL - MARZO </option>
				<option value=4>CUOTA ESPECIAL - ABRIL </option>
				<option value=5>CUOTA ESPECIAL - MAYO </option>
				<option value=6>CUOTA ESPECIAL - JUNIO</option>
				<option value=7>CUOTA ESPECIAL - JULIO</option>
				<option value=8>CUOTA ESPECIAL - AGOSTO</option>
				<option value=9>CUOTA ESPECIAL - SEPTIEMBRE</option>
				<option value=10>CUOTA ESPECIAL - OCTUBRE</option>
				<option value=11>CUOTA ESPECIAL - NOVIEMBRE</option>
				<option value=12>CUOTA ESPECIAL - DICIEMBRE</option>
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
			<select name="txtNominaPeriocidad"	id="txtNominaPeriocidad" class="inputxt" style="width: 220px;" onchange="valida_meses(6);">
				<option value=0>----------------------------</option>
				<option value=1>CUOTA ESPECIAL - ENERO</option>
				<option value=2>CUOTA ESPECIAL - FEBRERO</option>
				<option value=3>CUOTA ESPECIAL - MARZO </option>
				<option value=4>CUOTA ESPECIAL - ABRIL </option>
				<option value=5>CUOTA ESPECIAL - MAYO </option>
				<option value=6>CUOTA ESPECIAL - JUNIO</option>
				<option value=7>CUOTA ESPECIAL - JULIO</option>
				<option value=8>CUOTA ESPECIAL - AGOSTO</option>
				<option value=9>CUOTA ESPECIAL - SEPTIEMBRE</option>
				<option value=10>CUOTA ESPECIAL - OCTUBRE</option>
				<option value=11>CUOTA ESPECIAL - NOVIEMBRE</option>
				<option value=12>CUOTA ESPECIAL - DICIEMBRE</option>
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
				<option value=1>CUOTA ESPECIAL - ENERO</option>
				<option value=2>CUOTA ESPECIAL - FEBRERO</option>
				<option value=3>CUOTA ESPECIAL - MARZO </option>
				<option value=4>CUOTA ESPECIAL - ABRIL </option>
				<option value=5>CUOTA ESPECIAL - MAYO </option>
				<option value=6>CUOTA ESPECIAL - JUNIO</option>
				<option value=7>CUOTA ESPECIAL - JULIO</option>
				<option value=8>CUOTA ESPECIAL - AGOSTO</option>
				<option value=9>CUOTA ESPECIAL - SEPTIEMBRE</option>
				<option value=10>CUOTA ESPECIAL - OCTUBRE</option>
				<option value=11>CUOTA ESPECIAL - NOVIEMBRE</option>
				<option value=12>CUOTA ESPECIAL - DICIEMBRE</option>
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
				<option value=1>CUOTA ESPECIAL - ENERO</option>
				<option value=2>CUOTA ESPECIAL - FEBRERO</option>
				<option value=3>CUOTA ESPECIAL - MARZO </option>
				<option value=4>CUOTA ESPECIAL - ABRIL </option>
				<option value=5>CUOTA ESPECIAL - MAYO </option>
				<option value=6>CUOTA ESPECIAL - JUNIO</option>
				<option value=7>CUOTA ESPECIAL - JULIO</option>
				<option value=8>CUOTA ESPECIAL - AGOSTO</option>
				<option value=9>CUOTA ESPECIAL - SEPTIEMBRE</option>
				<option value=10>CUOTA ESPECIAL - OCTUBRE</option>
				<option value=11>CUOTA ESPECIAL - NOVIEMBRE</option>
				<option value=12>CUOTA ESPECIAL - DICIEMBRE</option>
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
				<input type="checkbox" value="1" onclick="Validar(this);Calcular_Total(this);" id="1" />&nbsp;&nbsp; MENSUAL
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
					FIRMA RECIBI CONFORME.- <br>
					C.I: <br>
					APELLIDOS Y NOMBRE: <br><br>
					</td><td style="width:100px" align="center">
						_______________________________________<br>
					FIRMA ANALISTA DE VENTAS.- <br>
					</td>
					</tr>
			<tr><td style="width:500px" valign="bottom">
					<BR><div style="width:80px; height: 90px; border: solid 1px #000000"></div>
						HUELLA DACTILAR
					
					</td><td style="width:100px" align="center">
						<BR>_______________________________________<br>
						FIRMA SUPERVISOR DE VENTAS.- <br>
					</td>
					</tr>
					
			</table>
</form>
</body>
</html>

