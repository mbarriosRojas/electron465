$(function() {

	$('#msj_alertas').dialog({
		modal : true,
		autoOpen : false,
		width : 450,
		height : 300,
		buttons : {
			"Cerrar" : function() {
				$(this).dialog("close");
			}
		}
	});
	$("#txtDescripcion").autocomplete({
		source : function(request, response) {
			$.ajax({
				type : "POST",
				url : sUrlP + "M_json/inventario/modelo",
				data : "nombre=" + $("#txtDescripcion").val() + "&ubica=0",
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

	/*$("#fecha").datepicker({
		showOn : "button",
		buttonImage : sImg + "calendar.gif",
		buttonImageOnly : true
	});*/
	$.datepicker.regional['es'] = {
		closeText : 'Cerrar',
		prevText : '&#x3c;Ant',
		nextText : 'Sig&#x3e;',
		currentText : 'Hoy',
		monthNames : ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
		monthNamesShort : ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
		dayNames : ['Domingo', 'Lunes', 'Martes', 'Mi&eacute;rcoles', 'Jueves', 'Viernes', 'S&aacute;bado'],
		dayNamesShort : ['Dom', 'Lun', 'Mar', 'Mi&eacute;', 'Juv', 'Vie', 'S&aacute;b'],
		dayNamesMin : ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'S&aacute;'],
		weekHeader : 'Sm',
		dateFormat : 'dd/mm/yy',
		firstDay : 1,
		isRTL : false,
		showMonthAfterYear : false,
		yearSuffix : ''
	};
	$.datepicker.setDefaults($.datepicker.regional['es']);
});

function BFactura() {

	var factura = $("#factura").val();
	$.ajax({
		url : sUrlP + "BFactura_Inventario",
		data : "factura=" + factura,
		type : 'post',
		dataType : 'json',
		success : function(json) {//alert(json);
			var msj = '';
			if (json.credito['esta'] != 0) {
				$("#cedula").val(json.credito['cedula']);
				$("#cliente").val(json.credito['nombre']);
				msj = 'Factura para cancelar a credito';
				$("#t_venta").val('credito');
			} else {
				if (json.contado['esta'] != 0) {
					$("#cedula").val(json.contado['cedula']);
					$("#cliente").val(json.contado['nombre']);
					msj = 'Factura para cancelar a contado';
					$("#t_venta").val('contado');
				} else {
					msj = "No se encontro la factura";
					$("#cedula").val('');
					$("#cliente").val('');
					$("#factura").val('');
				}
				
			}
			alert(msj);
		}
	});

}

function Consultar_Inventario() {
	$.ajax({
		url : sUrlP + "Consulta_Inventario",
		data : "nombre=" + $("#txtDescripcion").val() + "&tipo=1",
		type : 'post',
		dataType : 'json',
		success : function(json) {
			$("#txtDescripcion").val('');

			if (json['modelo'] != undefined) {
				var tabla = '<br><p><font color="#1c94c4">Modelo:</font> <b>' + json['modelo'] + '</b> <br> <font color="#1c94c4">Artefacto: </font><b>' + json['nom_art'];
				tabla += '<br><p><font color="#1c94c4">Marca:</font> <b>' + json['marca'] + '</b>';
				tabla += '<br> <font color="#1c94c4">Proveedor: </font><b>' + json['nom_prov'] + '<br> <font color="#1c94c4">Precio: </font><b>' + json['precio_venta'];
				tabla += '<br><p><font color="#1c94c4">Cantidad:</font> <b>' + json['cantidad_a'] + '</b>';

				$('#datos').html(tabla);
				var observa = json['modelo'] + ' / ' + json['marca'] + ' / ' + json['nom_art'];
				$("#observa").val(observa);
				$("#txtSeriales").html("");
				$.each(json['seriales'], function(clave, valor) {
					items = String(valor).split(' | ');
					$("#txtSeriales").append(new Option(valor, items[0]));
				});
				$("#modelo").val(json['modelo']);
				$("#nombre").val(observa);

			} else {
				$("#txtSeriales").html("");
				$('#datos').html('<p><font color="#1c94c4">No se encontro....</font>');
				$("#observa").val('');
			}
		}
	});
}

function Ag_Seriales() {

	original = $("#txtSeriales option:selected").val();
	if (original != 0) {
		cargar = $("#txtSeriales option:selected").val();
		$("#lstSeriales").append(new Option(cargar, original));
		$("#txtSeriales option:selected").attr("disabled", true);
		$("#txtSeriales").val(0);
	} else {
		alert("Debe Seleccionar un Serial...");
	}

}

function Eli_Seriales() {
	elemento = $("#lstSeriales option:selected").val();
	$("#txtSeriales option").each(function() {
		if ($(this).val() == elemento) {
			$(this).attr("disabled", false);
		}
	});
	$("#lstSeriales option:selected").remove();
}

function Entregar() {
	l_seriales = new Array();
	i = 0;
	$("#lstSeriales option").each(function() {
		aux = $(this).text();
		l_seriales[i] = aux;
		i++;
	});

	var modelo = $("#modelo").val();
	var fecha = $("#fecha").val();
	var descrip = $("#nombre").val();
	var cedula = $("#cedula").val();
	var cliente = $("#cliente").val();
	var factura = $("#factura").val();
	var t_venta = $("#t_venta").val();
	if (modelo == '' || fecha == '' || nombre == '' || cedula == '' || factura == '' || i == 0 || cliente == '') {
		alert("Debe ingresar todos los datos y al menos un serial...");
	} else {

		$.ajax({
			url : sUrlP + "Guarda_Entregar_Cliente",
			data : "modelo=" + modelo + "&fecha=" + fecha + "&descrip=" + descrip + "&cedula=" + cedula + "&cliente=" + cliente + "&factura=" + factura + "&seriales=" + l_seriales + "&t_venta=" + t_venta,
			type : "POST",
			success : function(html) {
				$('#msj_alertas').html(html);
				$('#msj_alertas').dialog('open');
				$("#txtSeriales option").each(function() {
					$(this).remove();
				});
				$("#lstSeriales option").each(function() {
					$(this).remove();
				});
				$("#modelo").val('');
				$("#fecha").val('');
				$("#nombre").val('');
				$("#cedula").val('');
				$("#factura").val('');
				$("#cliente").val('');
				$("#observa").val('');
				$("#datos").html('');

				//CEntregas();
			}
		});
	}
}

