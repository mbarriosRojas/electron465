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
				data : "nombre=" + $("#txtDescripcion").val(),
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

	$("#fecha").datepicker({
		showOn : "button",
		buttonImage : sImg + "calendar.gif",
		buttonImageOnly : true
	});
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

function Consultar_Inventario() {
	$.ajax({
		url : sUrlP + "Consulta_Inventario",
		data : "nombre=" + $("#txtDescripcion").val(),
		type : 'post',
		dataType : 'json',
		success : function(json) {
			$("#txtDescripcion").val('');

			if (json['modelo'] != undefined) {
				var tabla = '<br><p><font color="#1c94c4">Modelo:</font> <b>' + json['modelo'] + '</b> <br> <font color="#1c94c4">Artefacto: </font><b>' + json['nom_art'];
				tabla += '<br><p><font color="#1c94c4">Detalle:</font> <b>' + json['detalle'] + '</b>';
				tabla += '<br><p><font color="#1c94c4">Marca:</font> <b>' + json['marca'] + '</b>';
				tabla += '<br> <font color="#1c94c4">Proveedor: </font><b>' + json['nom_prov'] + '<br> <font color="#1c94c4">Precio: </font><b>' + json['precio_venta'];
				tabla += '<br><p><font color="#1c94c4">Cantidad Total:</font> <b>' + json['cantidad_a'] + '</b>';
				tabla += '<br><p><font color="#1c94c4">Cantidad En Almacen:</font> <b>' + json['cantidad_b'] + '</b>';
				tabla += '<br><p><font color="#1c94c4">Cantidad En Oficina:</font> <b>' + json['cantidad_c'] + '</b>';
				tabla += '<br><p><font color="#1c94c4">Cantidad Entregado:</font> <b>' + json['cantidad_d'] + '</b>';
				
				$('#datos').html(tabla);
				$("#precio_oficina").val(json['precio_venta']);
				$("#inventario_id").val(json['inventario_id']);
				var observa = json['modelo'] + ' / ' + json['marca'] + ' / ' + json['nom_art']+':'+json['detalle'];
				$("#observa").val(observa);
				$("#txtSeriales").html("");
				$.each(json['seriales'], function(clave, valor) {
					//items = String(valor).split(' | ');
					$("#txtSeriales").append(new Option(valor, valor));
				});

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
	precio = $("#precio_oficina").val();
	if (original != 0 && precio != '' && precio != 0) {
		cargar = $("#txtSeriales option:selected").val() + $("#observa").val() +' | ' + $("#precio_oficina").val();
		$("#lstSeriales").append(new Option(cargar, original));
		$("#txtSeriales option:selected").attr("disabled", true);
		$("#txtSeriales").val(0);
	} else {
		alert("Debe Seleccionar un Serial, y asiganar monto de precio de venta en la oficina...");
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

	var oficina = $("#oficina option:selected").text();
	var fecha = $("#fecha").val();
	var orden = $("#orden").val();
	var tlf = $("#tlf").val();
	var chofer = $("#chofer").val();
	var placa = $("#placa").val();
	var salida = $("#salida").val();
	var destino = $("#destino").text();
	var encargado = $("#encargado").val();
	var vehiculo = $("#vehiculo").val();
	var ubica = $("#lstUbicacion").val();

	if (oficina == '' || fecha == '' || orden == '' || tlf == '' || chofer == '' || i == 0 || placa == '' || salida == '' || destino == '' || encargado == '' || vehiculo == '' || ubica == '') {
		alert("Debe ingresar todos los datos y al menos un serial...");
	} else {

		$.ajax({
			url : sUrlP + "Guarda_Entregar_Inventario",
			data : "oficina=" + oficina + "&fecha=" + fecha + "&orden=" + orden + "&tlf=" + tlf + "&chofer=" + chofer + "&placa=" + placa + "&salida=" + salida + "&destino=" + destino + "&encargado=" + encargado + "&vehiculo=" + vehiculo + "&ubica=" + ubica + "&seriales=" + l_seriales,
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
				$("#oficina").val('');
				$("#fecha").val('');
				$("#orden").val('');
				$("#tlf").val('');
				$("#chofer").val('');
				$("#placa").val('');
				$("#salida").val('');
				$("#destino").val('');
				$("#encargado").val('');
				$("#vehiculo").val('');
				$("#observa").val('');
				$("#precio_oficina").val('');

				$("#datos").html('');

				//CEntregas();
			}
		});
	}
}

function CEntregas() {
	$.ajax({
		url : sUrlP + "Listar_Entregas",
		type : "POST",
		dataType : "json",
		success : function(oEsq) {alert(oEsq);
			Grid = new TGrid(oEsq, 'Reporte_Entregas', 'Mercancia Entregada');
			Grid.SetXls(true);
			Grid.SetNumeracion(true);
			Grid.SetName("Entregas");
			Grid.SetDetalle();
			Grid.Generar();
		}
	});
}

function sucursal(){
	var ubica = $("#oficina").val();
	var picado = ubica.split('|');
	$("#lstUbicacion").val(picado[0]);
	$("#destino").text(picado[1]);
}
