<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title><?php echo __TITLE__ ?></title>
  <link href="<?php echo __CSS__ ?>__style.css.php?url=<?php echo __LOCALWWW__ ?>" rel="stylesheet" type="text/css" />
  <link type="text/css" href="<?php echo __CSS__ ?>ui-lightness/jquery-ui-1.8.6.custom.css" rel="stylesheet" />
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>jquery/jquery-1.4.2.min.js"></script>
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>jquery/jquery-ui-1.8.6.custom.min.js"></script>
<script>
	function seleciona_tipo() {
		if (document.getElementById(txtModelo)) {
			valor_tipo = $("#txtModelo").val();
			var seleccion = document.getElementById("txtTipo");
			seleccion.selectedIndex = valor_tipo;
		}
	}

	function limpiar(item) {
		item.value = "";
	}

	function Validar(item) {
		//var meses = $("input[name='3M']:checked").val();//alert(meses);
		var meses = $("#cmbMeses").val();
		$("#txtValor").val(meses);
		if($("#txtMT1").val() == ""){$("#txtMT1").val(0)}
		A = parseInt($("#txtMT1").val());
		if($("#txtMT2").val() == ""){$("#txtMT2").val(0)}	
		B = parseInt($("#txtMT2").val());
		if($("#txtMT3").val() == ""){$("#txtMT3").val(0)}
		C = parseInt($("#txtMT3").val());
		if($("#txtMT4").val() == ""){$("#txtMT4").val(0)}
		D = parseInt($("#txtMT4").val());
		valor =  parseInt($("#txtValor").val());
		cuotas =  parseFloat($("#txtCuotas").val());				
		monto_solicitado =  parseInt(document.getElementById("txtMonto").value);	
		monto_credito = Math.round(valor * cuotas) ;				
		abono_especial = A + B + C + D ;
		monto_final = parseInt(monto_credito) + parseInt(monto_solicitado) - parseInt(abono_especial);
		ct = Math.round(monto_final / valor);
		switch (item.value) {
			case "1":		
				if(item.checked  == true){
					$('#txtM1').val(ct)
					$('#txtMT5').val(ct*valor)  
					//HABILITA CAJA DE CUOTA MENSUAL
					$('#txtM1').attr("disabled", false);
					//DESABILTA LAS DE MAS CHECK BOX
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

	function bloquear_cajas() {
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

	function Calcular_Total(item) {
		//var meses = $("input[name='3M']:checked").val();//alert(meses);
		var meses = $("#cmbMeses").val();
		//alert(meses);
		$("#txtValor").val(meses); 
		if ($("#txtMT1").val() == "") {
			$("#txtMT1").val(0)
		}
		A = parseInt($("#txtMT1").val());
		if ($("#txtMT2").val() == "") {
			$("#txtMT2").val(0)
		}
		B = parseInt($("#txtMT2").val());
		if ($("#txtMT3").val() == "") {
			$("#txtMT3").val(0)
		}
		C = parseInt($("#txtMT3").val());
		if ($("#txtMT4").val() == "") {
			$("#txtMT4").val(0)
		}
		D = parseInt($("#txtMT4").val());
		valor = parseInt($("#txtValor").val());
		cuotas = parseFloat($("#txtCuotas").val());
		monto_solicitado = parseInt($("#txtMonto").val());
		monto_credito = Math.round(valor * cuotas);
		abono_especial = A + B + C + D;
		monto_final = parseInt(monto_credito) + parseInt(monto_solicitado) - parseInt(abono_especial);
		var resta = 0;
		var monto_n = 0;
		tiene = $("#txtMT1").val();
		
		switch(item.id) {
			case '1'  :
				if (item.checked == true) {
					monto_n = $("#txtM1").val() * valor;
					$("#txtMT5").val(monto_n);
					resta = monto_final - monto_n;
					if (resta > 0) {
						agrega_cuota = parseInt(resta) + parseInt(tiene);
						$("#txtMT1").val(agrega_cuota);
					} else {
						if (resta < 0) {
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
				if (resta > 0) {
					agrega_cuota = parseInt(resta) + parseInt(tiene);
					$("#txtMT1").val(agrega_cuota);
				} else {
					if (resta < 0) {
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
				if (resta > 0) {
					agrega_cuota = parseInt(resta) + parseInt(tiene);
					$("#txtMT1").val(agrega_cuota);
				} else {
					if (resta < 0) {
						quitar_cuota = parseInt(tiene) + parseInt(resta);
						$("#txtMT1").val(quitar_cuota);
					}
				}
				break;
			case '2'  :
				if (item.checked == true) {
					monto_n = $("#txtM2").val() * valor;
					$("#txtMT6").val(monto_n);
					monto_n_2 = $("#txtMT8").val();
					resta = monto_final - monto_n - monto_n_2;
					if (resta > 0) {
						agrega_cuota = parseInt(resta) + parseInt(tiene);
						$("#txtMT1").val(agrega_cuota);
					} else {
						if (resta < 0) {
							quitar_cuota = parseInt(tiene) + parseInt(resta);
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
				if (resta > 0) {
					agrega_cuota = parseInt(resta) + parseInt(tiene);
					$("#txtMT1").val(agrega_cuota);
				} else {
					if (resta < 0) {
						quitar_cuota = parseInt(tiene) + parseInt(resta);
						$("#txtMT1").val(quitar_cuota);
					}
				}
				break;
			case '4'  :
				if (item.checked == true) {
					monto_n = $("#txtM4").val() * valor;
					$("#txtMT8").val(monto_n);
					monto_n_2 = $("#txtMT6").val();
					resta = monto_final - monto_n - monto_n_2;
					if (resta > 0) {
						agrega_cuota = parseInt(resta) + parseInt(tiene);
						$("#txtMT1").val(agrega_cuota);
					} else {
						if (resta < 0) {
							quitar_cuota = parseInt(tiene) + parseInt(resta);
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
				if (resta > 0) {
					agrega_cuota = parseInt(resta) + parseInt(tiene);
					$("#txtMT1").val(agrega_cuota);
				} else {
					if (resta < 0) {
						quitar_cuota = parseInt(tiene) + parseInt(resta);
						$("#txtMT1").val(quitar_cuota);
					}
				}
				break;
			case '3'  :
				if (item.checked == true) {
					monto_n = $("#txtM3").val() * valor;
					$("#txtMT7").val(monto_n);
					monto_n_2 = $("#txtMT9").val();
					resta = monto_final - monto_n - monto_n_2;
					if (resta > 0) {
						agrega_cuota = parseInt(resta) + parseInt(tiene);
						$("#txtMT1").val(agrega_cuota);
					} else {
						if (resta < 0) {
							quitar_cuota = parseInt(tiene) + parseInt(resta);
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
				if (resta > 0) {
					agrega_cuota = parseInt(resta) + parseInt(tiene);
					$("#txtMT1").val(agrega_cuota);
				} else {
					if (resta < 0) {
						quitar_cuota = parseInt(tiene) + parseInt(resta);
						$("#txtMT1").val(quitar_cuota);
					}
				}
				break;
			case '5'  :
				if (item.checked == true) {
					monto_n = $("#txtM5").val() * valor;
					$("#txtMT9").val(monto_n);
					monto_n_2 = $("#txtMT7").val();
					resta = monto_final - monto_n - monto_n_2;
					if (resta > 0) {
						agrega_cuota = parseInt(resta) + parseInt(tiene);
						$("#txtMT1").val(agrega_cuota);
					} else {
						if (resta < 0) {
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
	
	function Asignar(meses_a){
		//alert(meses_a);
		cuota = $("#txtCuotas").val();
		monto_a = parseInt(cuota*meses_a);
		monto_l = Formato(monto_a,"BS.");
		$("#monto_aux").html(monto_l);
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
		/*switch (parseInt(meses_c)){
			case 3:
				mes_f = mes_f + 3;
				break;
			case 6:
				mes_f = mes_f+6;
				break;
			case 12:
				anio_f = anio_f + 1;
				break;
			case 18:
				anio_f = anio_f + 1;
				mes_f = mes_f+6;
				break;
		}*/
		mes_f = mes_f + parseInt(meses_c);
		if(parseInt(mes_f) > 12 && mes_f <= 24){
			anio_f = anio_f + 1;
			mes_f = mes_f-12;
		}
		if(parseInt(mes_f) > 24){
			anio_f = anio_f + 2;
			mes_f = mes_f-24;
		}
		//alert(anio_f);
		
		for(var j = 1 ;j<=4;j++){
			$("#txtAno"+j).html('');
			$('#txtAno'+j).append(new Option('----------------------',0, true, true));
			for(var i = parseInt(anio) ; i <= anio_f ; i++){
				$('#txtAno'+j).append(new Option(i, i, true, true));
			}
			$("#txtAno"+j +" option[value=0]").attr("selected","selected");
		}
		
		
		
	}
	
	function valida_meses(item){
		
		if($("#txtModelo").val() == 0){
			anioA = $("#txtAno"+item +" option:selected").val();
			//meses_c = $('[name="3M"]:checked').val();
			meses_c = $("#cmbMeses").val();
			//alert(meses_c);
			fecha_actual = new Date();
			dia = fecha_actual.getDate();
			mes = fecha_actual.getMonth() + 1;
			anio = fecha_actual.getFullYear();
			anio_f = anio;
			mes_f= mes;
			
			/*switch (parseInt(meses_c)){
				case 3:
					mes_f = mes_f + 3;
					break;
				case 6:
					mes_f = mes_f+6;
					break;
				case 12:
					anio_f = anio_f + 1;
					break;
				case 18:
					anio_f = anio_f + 1;
					mes_f = mes_f+6;
					break;
			}*/
			//alert(mes_f);
			mes_f = mes_f+parseInt(meses_c);
			//alert(mes_f);
			if(parseInt(mes_f) > 12 && parseInt(mes_f) < 24){
				anio_f = anio_f + 1;
				mes_f = mes_f-12;
			}
			if(parseInt(mes_f) >= 24){
				anio_f = anio_f + 2;
				mes_f = mes_f-24;
			}
			valor_mes = $("#txtNominaPeriocidad"+item +" option:selected").val();
			if(anioA == anio){
				if(valor_mes <= mes){
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
		
	}

	function Seleccion_Mes(item) {
		//alert(item);
		$("#txtValor").val(item);//+alert($("#txtValor").val());
		Calcular_Total(item);
	}

	function Imprimir() {
		$("#lblTipo").hide();
		$("#txtTipo").hide();
		$("#lblRif").hide();
		$("#txtRif").hide();
		$("#lblMotivo").hide();
		$("#txtMotivo").hide();
		$("#lblPresupuesto").hide();
		$("#txtCalculo").hide();
		$("#btnImprimir").hide();
		$("#btnCalcular").hide();
		$("#lblProforma").hide();
		$("#txtProforma").hide();
		if ($("#txtModelo").val() == '1') {
			$("#txtM1").hide();
			$("#txtMT5").hide();
			$("#txtNominaPeriocidad2").hide();
			$("#txtNominaPeriocidad3").hide();
			$("#txtNominaPeriocidad4").hide();
			$("#txtAno2").hide();
			$("#txtAno3").hide();
			$("#txtAno4").hide();
			$("#txtMT2").hide();
			$("#txtMT3").hide();
			$("#txtMT4").hide();
			$("#1").hide();
			$("#2").hide();
			$("#3").hide();
			$("#4").hide();
			$("#5").hide();
			$("#l1").hide();
			$("#l2").hide();
			$("#l5").hide();
			$("#l3").hide();
			$("#l4").hide();
			$("#l5").hide();
			$("#txtM2").hide();
			$("#txtMT6").hide();
			$("#txtM3").hide();
			$("#txtMT7").hide();
			$("#txtM4").hide();
			$("#txtMT8").hide();
			$("#txtM5").hide();
			$("#txtMT9").hide();
		}
		window.print();
	}
	
	function verificar_montos(){
		if(parseInt($('#txtProforma').val()) < parseInt($('#txtCalculo').val()) ){
			
			alert('El monto aprobado no puede ser mayor al monto de la proforma');
			$('#txtProforma').val(0);
			$('#txtCalculo').val(0)
			return true;
		}else{
			return true;
		}
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
<body style="background-color: #FFFACD;" onload="seleciona_tipo();">
<center>
	<form onsubmit="return verificar_montos();" action="<?php echo __LOCALWWW__?>/index.php/cooperativa/Calculo" method="POST" >
		<table> 
			<tr>				
			 	<td align="left"><label id="lblTipo"> Tipo Proforma: &nbsp;</label></td>
			 	<td>
					<div class="ui-widget">				
						<select name="txtTipo"	id="txtTipo" class="inputxt" style="width: 150px;" >
							<option value=0>CONTRATO UNICO</option>
							<option value=1>CONTRATO EXTRA</option>
						</select>
					</div>
				</td>
			</tr>
			<tr>				
			 	<td align="left"><label id="lblRif"> Rif: &nbsp;</label></td>
			 	<td>
					<div class="ui-widget">					
						<input  type="text" value="" name="txtRif" id="txtRif" class="inputxt" style="width: 150px;" />
					</div>
				</td>				
			 	<td align="left"><label id="lblMotivo"> Motivo : &nbsp;</label></td>
			 	<td>
						<input  type="text" value="" name="txtMotivo" id="txtMotivo" class="inputxt" style="width: 150px;"  />
				</td>
			</tr>
			<tr>				
			 	<td align="left"><label id="lblProforma"> Monto Proforma: &nbsp;</label></td>
			 	<td>
					<div class="ui-widget">					
						<input  type="text" value="" name="txtProforma" id="txtProforma" class="inputxt" style="width: 150px;" />
					</div>
				</td>				
			 	<td align="left"><label id="lblPresupuesto"> Monto Aprobado : &nbsp;</label></td>
			 	<td>
					<div class="ui-widget">					
						<input  type="text" value="" name="txtCalculo" id="txtCalculo" class="inputxt" style="width: 150px;"  />
					</div>
				</td>
			 	<td>
			 		<input type="submit" class="inputxt" value='Calcular' id='btnCalcular'/>
			 		<input type="button" class="inputxt" value='Imprimir'  id='btnImprimir' onclick="Imprimir();"/>			 		
			 	</td>
			</tr>
		</table>
</form>
<font style='font-size:20px; '><b>PRESUPUESTO DE CR&Eacute;DITO</b></font><BR><BR>
	
	<?php echo $lista; ?><BR><BR>
	<font style='font-size:20px; '><b><label id='lblPlan'>PLAN DE PAGO</label></b></font><BR>
	<input type=hidden value=<?php echo $calculo; ?> name="txtMonto" id="txtMonto" />
	<input type=hidden value=6 name="txtValor" id="txtValor"/>
	<input type=hidden value=<?php echo $monto; ?> name="txtCuotas" id="txtCuotas"/>
	<?php echo $mensuales; ?><BR>
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
				
				<option value=1>PAGARE EXTRA - ENERO</option>
				<option value=2>PAGARE EXTRA - FEBRERO</option>
				<option value=3>PAGARE EXTRA - MARZO </option>
				<option value=4>PAGARE EXTRA - ABRIL </option>
				<option value=5>PAGARE EXTRA - MAYO </option>
				<option value=6>PAGARE EXTRA - JUNIO</option>
				<option value=7>PAGARE EXTRA - JULIO</option>
				<option value=8>PAGARE EXTRA - AGOSTO</option>
				<option value=9>PAGARE EXTRA - SEPTIEMBRE</option>
				<option value=10>PAGARE EXTRA - OCTUBRE</option>
				<option value=11>PAGARE EXTRA - NOVIEMBRE</option>
				<option value=12>PAGARE EXTRA - DICIEMBRE</option>
				
			</select>
			</td>
			<td>
			<select name="txtAno1"	id="txtAno1" class="inputxt" style="width: 70px;" onchange="valida_meses(1);">
				<option value=2013>2013</option>
				<option value=2014>2014</option>
				<option value=2015>2015</option>
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
				<option value=2014>2014</option>
				<option value=2015>2015</option>
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
				<option value=2014>2014</option>
				<option value=2015>2015</option>
				
			</select>
			</td>
			<td>
				<input  type="text" value="" name="txtMT3" id="txtMT3" class="inputxt"  onclick="limpiar(this);" onkeydown="return soloNumeros(event);" onchange="Calcular_Total(this)"/>
			</td>
		</tr>
		<tr><td> 
			<select name="txtNominaPeriocidad4"	id="txtNominaPeriocidad4" class="inputxt" style="width: 220px;" onchange="valida_meses(4);" >
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
				<option value=2014>2014</option>
				<option value=2015>2015</option>
			</select>
			
			</td>
			<td>
				<input  type="text" value="" name="txtMT4" id="txtMT4" class="inputxt" onclick="limpiar(this);" onkeydown="return soloNumeros(event);" onchange="Calcular_Total(this)"/>
			</td>
		</tr>
		<tr><td>			
				<input type="checkbox" value="1" onclick="Validar(this);Calcular_Total(this);" id="1" /><label id='l1'>&nbsp;&nbsp;PAGARE MENSUAL</label>
			</td>
			<td>
				<input  type="text" value="" name="txtM1" id="txtM1" class="inputxt" disabled="disabled" style="width: 70px; " onchange="Calcular_Total(this);" onkeydown="return soloNumeros(event);"/>
			</td>
			<td>
				<input  type="text" value="" name="txtMT5" id="txtMT5" class="inputxt" disabled="disabled" />
			</td>
		</tr>
		<tr style="display:none; "><td> 
				<input type="checkbox" value="2" onclick="Validar(this);Calcular_Total(this);" id="2" /><label id='l2'>&nbsp;&nbsp; MENSUAL LOS 10</label>
			</td>
			<td>
				<input  type="text" value="" name="txtM2" id="txtM2" class="inputxt"  style="width: 70px;"disabled="disabled" onchange="Calcular_Total(this);" onkeydown="return soloNumeros(event);"/>
			</td>
			<td>
				<input  type="text" value="" name="txtMT6" id="txtMT6" class="inputxt"disabled="disabled" />
			</td>
		</tr>
		<tr style="display:none; "><td> 
				<input type="checkbox" value="3" onclick="Validar(this);Calcular_Total(this);" id="3"  /><label id='l3'>&nbsp;&nbsp; MENSUAL LOS 15</label>
			</td>
			<td>
				<input  type="text" value="" name="txtM3" id="txtM3" class="inputxt"  style="width: 70px;"disabled="disabled" onchange="Calcular_Total(this);" onkeydown="return soloNumeros(event);"/>
			</td>
			<td>
				<input  type="text" value="" name="txtMT7" id="txtMT7" class="inputxt"disabled="disabled" />
			</td>
		</tr>
		<tr style="display:none; "><td> 
				<input type="checkbox" value="4" onclick="Validar(this);Calcular_Total(this);" id="4" /><label id='l4'>&nbsp;&nbsp; MENSUAL LOS 25</label>
			</td>
			<td>
				<input  type="text" value="" name="txtM4" id="txtM4" class="inputxt" disabled="disabled" style="width: 70px;"onchange="Calcular_Total(this);" onkeydown="return soloNumeros(event);"/>
			</td>
			<td>
				<input  type="text" value="" name="txtMT8" id="txtMT8" class="inputxt"disabled="disabled" />
			</td>
		</tr>
		<tr style="display:none; "><td> 
				<input type="checkbox" value="5" onclick="Validar(this);Calcular_Total(this);" id="5"/><label id='l5'>&nbsp;&nbsp; MENSUAL LOS 30</label>
			</td>
			<td>
				<input  type="text" value="" name="txtM5" id="txtM5" class="inputxt" disabled="disabled" style="width: 70px;" onchange="Calcular_Total(this);" onkeydown="return soloNumeros(event);"/>
			</td>
			<td>
				<input  type="text" value="" name="txtMT9" id="txtMT9" class="inputxt"disabled="disabled" />
			</td>
		</tr>
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
</body>
</html>