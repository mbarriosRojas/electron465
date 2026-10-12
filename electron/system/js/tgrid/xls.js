function Genera_Xls_Json(nombre_aux,det) {//alert(det);
	var json = '{ ';
	var cabezera = '{ ';
	var inicio = 0;
	var inicio2 = 0;
	var fecha = new Date();
	var detalle = "Detalle";
	if(det) detalle = '';

	$("#tabla_tgrid" + nombre_aux + " tbody tr").each(function(index) {
		var i = 0;
		var ubica_radio = 0;
		arr_id = this.id.split('_');
		if (arr_id[arr_id.length - 1] != detalle && arr_id[arr_id.length - 1] != '' ) {
			if (inicio != 0) {
				json += ',';
			}
			inicio++;
			json += ' " ' + this.id + ' " : { ';
			inicio2 = 0;
			$(this).children("td").each(function(index2) {
				if (inicio2 != 0) {
					json += ",";
				}
				inicio2++;
				if (this.id.charAt(0) == 'R') {//PARA OBTENER EL VALOR EN CASO DE OPTION BUTTON
					if (ubica_radio == 0) {
						ubica_radio = 1;
						var rad = 'radio_' + this.id.substring(1);
						var cont = $("input[name='" + rad + "']:checked").val();
						json += '"' + i + '" : "' + cont + '"';
						i++;
					}
				} else {//OBTENER EL VALOR TEXT DE LA CELDA
					if (this.childNodes.length > 1) {
						var algo = this.childNodes.item(0);
						json += '"' + i + '" : "' + algo.innerHTML + '"';
						i++;
					} else {
						json += '"' + i + '" : "' + $(this).text() + '"';
						i++;
					}
				}
			});
			json += '} ';
		}

	});
	json += '} ';
	inicio = 0;
	$("#tabla_tgrid" + nombre_aux + " thead th").each(function(index) {
		arr_id = this.id.split('_');
		if (arr_id[arr_id.length - 1] != detalle) {
			if (this.id != '') {
				if (inicio != 0) {
					cabezera += ',';
				}
				if ($(this).text() != '') {
					cabezera += ' " ' + inicio + ' " :  " ' + $(this).text() + ' "';
					inicio++;
				}
			}
		}

	});

	cabezera += '} ';

	$('#dialog_pie' + nombre_aux).dialog({
		modal : true,
		title : 'BAJAR ARCHIVO',
		hide : 'explode',
		show : 'slide',
		width : 350,
		height : 300,
		buttons : {
			"Cerrar" : function() {
				$(this).dialog("close");
			}
		}

	});

	$("#dialog_pie" + nombre_aux).html("<br><br><br><p><center>Cargando por favor espere un momento<br><img src='" + sImg + "cargando.gif'></center></p>");
	$("#dialog_pie" + nombre_aux).show();
	//$("#dialog_pie" + nombre_aux).html(json);
	$.ajax({
		url : sUrlP + 'Exporta_Exel',
		type : "POST",
		data : "cuerpo=" + json + "&cabezera=" + cabezera,
		success : function(data_r) {
			$("#dialog_pie" + nombre_aux).html(data_r);
		}
	});

}