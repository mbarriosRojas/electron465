function Imprimir() {
	//Crear Insert del Sistema
	var cade = '';
	//Certificado
	var cert = $('#certificado').html();
	//Abono
	var abon = $('#txtAbono').val();
	//Moto Total
	var mont = $('#txtCalculo').val();
	//Descripcion del Produto
	var desc = $('#txtDes').val();
	//Total de Coutas
	var cuot = '1';
	//Cedula de Identidad del Responsable
	var ced = $('#txtCed').val();
	//Moto, Artefacto, Celular
	var tipo = $('#txtTipo').val();

	var fech = new Array();

	fecha_actual = new Date();
	fdia = fecha_actual.getDate();
	fmes = fecha_actual.getMonth() + 1;
	fano = fecha_actual.getFullYear();
	fechaSoli = fano + '-' + fmes + '-' + fdia;
	/*
	var vanio = array("txtAno1", "txtAno2", "txtAno3", "txtAno4", "txtAno5", "txtAno6", "txtAno7", "txtAno8");
	var vmes = array("txtNominaPeriocidad1", "txtNominaPeriocidad2", "txtNominaPeriocidad3", "txtNominaPeriocidad4", "txtNominaPeriocidad5", "txtNominaPeriocidad6", "txtNominaPeriocidad7", "txtNominaPeriocidad8");
	var vmcuo = array("txtMT1", "txtMT2", "txtMT3", "txtMT4", "txtMT10", "txtMT11", "txtMT12", "txtMT13");
	/*
	 * Cuotas Especiales
	 *
	var tam = vanio.length;
	var pos = 0;
	for (var i = 0; i < tam; i++) {
		pos = parseInt(i)+1;
		alert(i);
		if ($("#txtMT"+pos).val() != 0) {
			alert("entra"+i);
			// Año de Seleccion
			ano = $("#txtAno"+pos).val();
			//Mes de Seleccion
			mes = $("#txtNominaPeriocidad"+pos).val();
			// Monto de la Cuota
			mcuo = $("#"+vmcuo[i]).val();
			// Fecha Desde - Hasta
			fech = fechadh(mes, ano);

			cade = "cod=" + cert + "&ced=" + ced + "&dscr=" + desc + "&cont=" + mont + "&abon=" + abon + "&cuot=" + cuot + "&mont=" + mcuo + "&desd=" + fech['desd'] + "&hast=" + fech['hast'] + "&tip=1&soli=" + fechaSoli + "&cond=" + tipo;
			alert(cade);
			/*$.ajax({
				url : sUrlP + 'Guardar_SolicitudP',
				type : 'POST',
				data : cade,
				success : function(msj) {

				}
			});
		}
	}
	return false;*/
	if ($("#txtMT1").val() != 0) {
		// Año de Seleccion
		ano = $("#txtAno1").val();
		//Mes de Seleccion
		mes = $("#txtNominaPeriocidad1").val();
		// Monto de la Cuota
		mcuo = $("#txtMT1").val();
		// Fecha Desde - Hasta
		fech = fechadh(mes, ano);

		cade = "cod=" + cert + "&ced=" + ced + "&dscr=" + desc + "&cont=" + mont + "&abon=" + abon + "&cuot=" + cuot + "&mont=" + mcuo + "&desd=" + fech['desd'] + "&hast=" + fech['hast'] + "&tip=1&soli=" + fechaSoli + "&cond=" + tipo;
		$.ajax({
			url : sUrlP + 'Guardar_SolicitudP',
			type : 'POST',
			data : cade,
			success : function(msj) {

			}
		});
	}

	if ($("#txtMT2").val() != 0) {
		ano = $("#txtAno2").val();
		// Año de Seleccion
		mes = $("#txtNominaPeriocidad2").val();
		//Mes de Seleccion
		mcuo = $("#txtMT2").val();
		// Monto de la Cuota
		fech = fechadh(mes, ano);
		// Fecha Desde - Hasta
		cade = "cod=" + cert + "&ced=" + ced + "&dscr=" + desc + "&cont=" + mont + "&abon=" + abon + "&cuot=" + cuot + "&mont=" + mcuo + "&desd=" + fech['desd'] + "&hast=" + fech['hast'] + "&tip=1&soli=" + fechaSoli + "&cond=" + tipo;
		$.ajax({
			url : sUrlP + 'Guardar_SolicitudP',
			type : 'POST',
			data : cade,
			success : function(msj) {

			}
		});
	}

	if ($("#txtMT3").val() != 0) {
		ano = $("#txtAno3").val();
		// Año de Seleccion
		mes = $("#txtNominaPeriocidad3").val();
		//Mes de Seleccion
		mcuo = $("#txtMT3").val();
		// Monto de la Cuota
		fech = fechadh(mes, ano);
		// Fecha Desde - Hasta
		cade = "cod=" + cert + "&ced=" + ced + "&dscr=" + desc + "&cont=" + mont + "&abon=" + abon + "&cuot=" + cuot + "&mont=" + mcuo + "&desd=" + fech['desd'] + "&hast=" + fech['hast'] + "&tip=1&soli=" + fechaSoli + "&cond=" + tipo;
		$.ajax({
			url : sUrlP + 'Guardar_SolicitudP',
			type : 'POST',
			data : cade,
			success : function(msj) {

			}
		});
	}
	
	if ($("#txtMT4").val() != 0) {
		ano = $("#txtAno4").val();
		// Año de Seleccion
		mes = $("#txtNominaPeriocidad4").val();
		//Mes de Seleccion
		mcuo = $("#txtMT4").val();
		// Monto de la Cuota
		fech = fechadh(mes, ano);
		// Fecha Desde - Hasta
		cade = "cod=" + cert + "&ced=" + ced + "&dscr=" + desc + "&cont=" + mont + "&abon=" + abon + "&cuot=" + cuot + "&mont=" + mcuo + "&desd=" + fech['desd'] + "&hast=" + fech['hast'] + "&tip=1&soli=" + fechaSoli + "&cond=" + tipo;
		$.ajax({
			url : sUrlP + 'Guardar_SolicitudP',
			type : 'POST',
			data : cade,
			success : function(msj) {

			}
		});
	}
	
	if ($("#txtMT10").val() != 0) {
		ano = $("#txtAno5").val();
		// Año de Seleccion
		mes = $("#txtNominaPeriocidad5").val();
		//Mes de Seleccion
		mcuo = $("#txtMT10").val();
		// Monto de la Cuota
		fech = fechadh(mes, ano);
		// Fecha Desde - Hasta
		cade = "cod=" + cert + "&ced=" + ced + "&dscr=" + desc + "&cont=" + mont + "&abon=" + abon + "&cuot=" + cuot + "&mont=" + mcuo + "&desd=" + fech['desd'] + "&hast=" + fech['hast'] + "&tip=1&soli=" + fechaSoli + "&cond=" + tipo;
		$.ajax({
			url : sUrlP + 'Guardar_SolicitudP',
			type : 'POST',
			data : cade,
			success : function(msj) {

			}
		});
	}
	
	if ($("#txtMT11").val() != 0) {
		ano = $("#txtAno6").val();
		// Año de Seleccion
		mes = $("#txtNominaPeriocidad6").val();
		//Mes de Seleccion
		mcuo = $("#txtMT11").val();
		// Monto de la Cuota
		fech = fechadh(mes, ano);
		// Fecha Desde - Hasta
		cade = "cod=" + cert + "&ced=" + ced + "&dscr=" + desc + "&cont=" + mont + "&abon=" + abon + "&cuot=" + cuot + "&mont=" + mcuo + "&desd=" + fech['desd'] + "&hast=" + fech['hast'] + "&tip=1&soli=" + fechaSoli + "&cond=" + tipo;
		$.ajax({
			url : sUrlP + 'Guardar_SolicitudP',
			type : 'POST',
			data : cade,
			success : function(msj) {

			}
		});
	}
	
	if ($("#txtMT12").val() != 0) {
		ano = $("#txtAno6").val();
		// Año de Seleccion
		mes = $("#txtNominaPeriocidad6").val();
		//Mes de Seleccion
		mcuo = $("#txtMT12").val();
		// Monto de la Cuota
		fech = fechadh(mes, ano);
		// Fecha Desde - Hasta
		cade = "cod=" + cert + "&ced=" + ced + "&dscr=" + desc + "&cont=" + mont + "&abon=" + abon + "&cuot=" + cuot + "&mont=" + mcuo + "&desd=" + fech['desd'] + "&hast=" + fech['hast'] + "&tip=1&soli=" + fechaSoli + "&cond=" + tipo;
		$.ajax({
			url : sUrlP + 'Guardar_SolicitudP',
			type : 'POST',
			data : cade,
			success : function(msj) {

			}
		});
	}
	/*
	 * Cuota Unica
	 */
	if ($("#txtMT5").val() != 0) {
		ano = '2013';
		// Año de Seleccion
		mes = 11;
		//Mes de Seleccion
		mcuo = $("#txtM1").val();
		// Monto de la Cuota
		cuot = $("#cmbMeses").val();
		fmes = fmes + 1;
		
		fechaHasta = Calcular_Fin_Descuento(cuot, '4', fdia, fmes + 1, fano);
		// Fecha Desde - Hasta
		if(fmes > 12){
			fmes = 1;
			fano++;
		}
		if(fmes < 10){
			fmes = '0' + fmes;
		}
		fechaDesde = fano + '-' + fmes + '-' + '01';
		//alert(fechaDesde+'**'+fechaHasta);
		cade = "cod=" + cert + "&ced=" + ced + "&dscr=" + desc + "&cont=" + mont + "&abon=" + abon + "&cuot=" + cuot + "&mont=" + mcuo + "&desd=" + fechaDesde + "&hast=" + fechaHasta + "&tip=1&soli=" + fechaSoli + "&cond=" + tipo;
		$.ajax({
			url : sUrlP + 'Guardar_SolicitudP',
			type : 'POST',
			data : cade,
			success : function(msj) {
				//alert('Proceso Exitoso y Enviado a Revision');
			}
		});
	}

	$("#lblPresupuesto").hide();
	//$("#lblAbono").hide();
	$("#txtModelo").hide();
	//$("#txtAbono").hide();
	$("#btnImprimir").hide();
	$("#btnCalcular").hide();
	$("#btnRecalcular").hide();

	if(confirm("Presione aceptar para continuar")){
		window.print();
		return true;
	}
	return false;
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
	if (suma_meses > 24) {
		ano_t += 2;
		mes_t = suma_meses - 24;
	} else {
		if (suma_meses > 12) {
			ano_t += 1;
			mes_t = suma_meses - 12;
		} else {
			mes_t = suma_meses;
		}	
	}

	return ano_t + '-' + mes_t + '-' + dia_t;

}

/**
 * 	Fecha desde Hasta
 *  @oaram Valor del Mes por combos
 *  @return array
 */
function fechadh(id, ano) {
	var sMes = new Array();
	switch (id) {
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

	if ($("#txtMuestra").val() == '1') {
		$('#plan_financiamiento').show();
	} else {
		$('#plan_financiamiento').hide();
	}

	$("#txtModelo").autocomplete({
		source : function(request, response) {
			$.ajax({
				type : "POST",
				url : sUrlP + "M_json/inventario/modelo/" + $("#txtTipo option:selected").val(),
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
				}else{
					$("#foto").attr("src", sImg + "nodisponible.jpg");
					$('#foto').attr('src', $('#foto').attr('src') + '?' + Math.random());
				}
			} else {
				if (document.getElementById("txtCalculo").value == '') {
					document.getElementById("txtDes").value = 'INTENTEN OTRO MODELO';
				}
			}
			
			/*
			 * para abono post o pre monto
			 */
			//var t_credito = parseInt($('#txtCalculo').val() - $('#txtAbono').val());
			var t_credito = parseInt($('#txtCalculo').val());
			/*
			 * fin
			 */
			monto = (t_credito * 3) / 100;
			$("#txtAbonoInicial").val(t_credito);
			$('#txtCuotas').val(monto);
			
			var nuevo_det = $('#txtDes').val() + ' <BR>	PRECIO DE CONTADO:  <b>' + $('#txtCalculo').val() + ' Bs.</b><br>';
			nuevo_det += '<table><tr><td>PSC</td><td style="padding-left:10px;">Precio sugerido por concesionario</td></tr>'; 
			nuevo_det +='<tr><td>1%BCV</td><td style="padding-left:10px;">Interes mensual según tabladel BCV</td></tr>';
			nuevo_det +='<tr><td>1%DOMIC</td><td style="padding-left:10px;">Cobro mensual por cobranza domiciliada</td></tr>';
			nuevo_det +='<tr><td>1%GA</td><td style="padding-left:10px;">Cobro mensual por gastos administrativos, operativos y análisis de aprobacion de las referidas solicitudes</td></tr></table><br>';
			$("#info").html(nuevo_det);
			$('#txtMonto').val($('#txtCalculo').val());
			$('#lista').show();
			Asignar(0);
			$("#cmbMeses > option[value='0']").attr("selected", "selected");
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

function Validar(item) {
	//Cuotas Especiales
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
	if ($("#txtMT10").val() == "") {
		$("#txtMT10").val(0)
	}
	E = parseInt($("#txtMT10").val());
	if ($("#txtMT11").val() == "") {
		$("#txtMT11").val(0)
	}
	F = parseInt($("#txtMT11").val());
	if ($("#txtMT12").val() == "") {
		$("#txtMT12").val(0)
	}
	G = parseInt($("#txtMT12").val());
	if ($("#txtMT13").val() == "") {
		$("#txtMT13").val(0)
	}
	H = parseInt($("#txtMT13").val());

	valor = parseInt($("#txtValor").val());
	cuotas = parseFloat($("#txtCuotas").val());
	monto_solicitado = parseInt($("#txtMonto").val());
	monto_credito = Math.round(valor * cuotas);
	/*
	 * abono pre o post
	 */
	//abono_nuevo = 0;
	abono_nuevo = $("#txtAbono").val();
	/*
	 * fin
	 */
	abono_especial = A + B + C + D + E + F + G + H;
	monto_inicial = $("#txtAbonoInicial").val();
	monto_final = parseInt(monto_credito) + parseInt(monto_inicial) - parseInt(abono_especial) - parseInt(abono_nuevo);

	ct = Math.round(monto_final / valor);
	if (item.checked == true) {
		$('#txtM1').val(ct)
		$('#txtMT5').val(ct * valor)
		//HABILITA CAJA DE CUOTA MENSUAL
		$('#txtM1').attr("disabled", false);
	} else {
		//IGUALA TODAS LAS VARIABLES A 0
		$('#txtM1').val(0);
		$('#txtMT5').val(0);
		//DESABILITA LA CAJA DE CUOTAS
		bloquear_cajas();
	}

}

function bloquear_cajas() {
	$('#txtM1').attr("disabled", true);
	$('#txtMT5').attr("disabled", true);
}

function Calcular_Total(item) {
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
	if ($("#txtMT10").val() == "") {
		$("#txtMT10").val(0)
	}
	E = parseInt($("#txtMT10").val());
	if ($("#txtMT11").val() == "") {
		$("#txtMT11").val(0)
	}
	F = parseInt($("#txtMT11").val());
	if ($("#txtMT12").val() == "") {
		$("#txtMT12").val(0)
	}
	G = parseInt($("#txtMT12").val());
	if ($("#txtMT13").val() == "") {
		$("#txtMT13").val(0)
	}
	H = parseInt($("#txtMT13").val());

	//abono_nuevo = 0;
	abono_nuevo = $("#txtAbono").val();
	valor = parseInt($("#txtValor").val());

	cuotas = parseFloat($("#txtCuotas").val());

	monto_solicitado = parseInt($("#txtMonto").val());
	monto_credito = valor * cuotas;
	monto_credito = Math.round(valor * cuotas);
	abono_especial = A + B + C + D + E + F + G + H;
	monto_inicial = parseInt($("#txtAbonoInicial").val());
	monto_final = parseInt(monto_credito) + parseInt(monto_inicial) - parseInt(abono_especial) - parseInt(abono_nuevo);

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
		default:
			$('#1').attr("disabled", false);
			$('#1').attr("checked", false);
			$('#txtM1').val(0);
			$('#txtMT5').val(0);
			bloquear_cajas();
			break;
	}
}

function Asignar(meses_a) {
	//alert(meses_a);
	cuota = $("#txtCuotas").val();
	monto_a = Math.round(cuota * meses_a);
	calculo = parseInt($("#txtCalculo").val());
	Inicial = parseInt($("#txtAbonoInicial").val());

	monto_l = Formato(monto_a, "BS.");
	monto_t = parseInt(monto_a);
	monto_t2 = Formato(monto_t + Inicial, "BS.");
	$("#monto_aux").html(monto_l);
	$("#monto_aux2").html(monto_t2);
	$("#abono_aux").html(Formato($("#txtAbono").val()));
	$("#abono_aux2").html(Formato(parseInt(monto_t + Inicial) - parseInt($("#txtAbono").val())));

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

function crea_combos(meses_c) {
	fecha_actual = new Date();
	dia = fecha_actual.getDate();
	mes = fecha_actual.getMonth() + 1;
	anio = fecha_actual.getFullYear();
	anio_f = anio;
	mes_f = mes;
	switch (parseInt(meses_c)) {
		case 12:
			anio_f = anio_f + 1;
			break;
		case 18:
			anio_f = anio_f + 1;
			mes_f = mes_f + 6;
			break;
		case 19:
			anio_f = anio_f + 1;
			mes_f = mes_f + 7;
			break;
		case 20:
			anio_f = anio_f + 1;
			mes_f = mes_f + 8;
			break;
		case 21:
			anio_f = anio_f + 1;
			mes_f = mes_f + 9;
			break;
		case 22:
			anio_f = anio_f + 1;
			mes_f = mes_f + 10;
			break;
		case 23:
			anio_f = anio_f + 1;
			mes_f = mes_f + 11;
			break;
		case 24:
			anio_f = anio_f + 2;
			break;
		case 30:
			anio_f = anio_f + 2;
			mes_f = mes_f + 6;
			break;
		case 36:
			anio_f = anio_f + 3;
			break;
	}

	if (parseInt(mes_f) > 12) {
		anio_f = anio_f + 1;
		mes_f = mes_f - 12;
	}
	//alert(anio_f);

	for (var j = 1; j <= 8; j++) {
		$("#txtAno" + j).html('');
		$('#txtAno' + j).append(new Option('----------------------', 0, true, true));
		for (var i = parseInt(anio); i <= anio_f; i++) {
			$('#txtAno' + j).append(new Option(i, i, true, true));
		}
		$("#txtAno" + j + " option[value=0]").attr("selected", "selected");
	}
}

function valida_meses(item) {

	anioA = $("#txtAno" + item + " option:selected").val();
	meses_c = $('#cmbMeses option:selected').val();
	fecha_actual = new Date();
	dia = fecha_actual.getDate();
	mes = fecha_actual.getMonth() + 2;
	anio = fecha_actual.getFullYear();
	anio_f = anio;
	mes_f = mes;
	//alert(anioA+"//"+meses_c+"//"+fecha_actual);
	switch (parseInt(meses_c)) {
		case 12:
			anio_f = anio_f + 1;
			break;
		case 18:
			anio_f = anio_f + 1;
			mes_f = mes_f + 6;
			break;
		case 19:
			anio_f = anio_f + 1;
			mes_f = mes_f + 7;
			break;
		case 20:
			anio_f = anio_f + 1;
			mes_f = mes_f + 8;
			break;
		case 21:
			anio_f = anio_f + 1;
			mes_f = mes_f + 9;
			break;
		case 22:
			anio_f = anio_f + 1;
			mes_f = mes_f + 10;
			break;
		case 23:
			anio_f = anio_f + 1;
			mes_f = mes_f + 11;
			break;
		case 24:
			anio_f = anio_f + 2;
			break;
		case 30:
			anio_f = anio_f + 2;
			mes_f = mes_f + 6;
			break;
		case 36:
			anio_f = anio_f + 3;
			break;
	}

	if (parseInt(mes_f) > 12) {
		anio_f = anio_f + 1;
		mes_f = mes_f - 12;
	}
	valor_mes = $("#txtNominaPeriocidad" + item + " option:selected").val();
	if (anioA == anio) {
		if (valor_mes < mes) {
			$("#txtNominaPeriocidad" + item + " option[value=0]").attr("selected", "selected");
			$("#txtAno" + item + " option[value=0]").attr("selected", "selected");
		}
	}
	if (anioA == anio_f) {
		if (valor_mes > mes_f) {
			$("#txtNominaPeriocidad" + item + " option[value=0]").attr("selected", "selected");
			$("#txtAno" + item + " option[value=0]").attr("selected", "selected");
		}
	}

}

function Seleccion_Mes(item) {
	$("#txtValor").val(item);
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