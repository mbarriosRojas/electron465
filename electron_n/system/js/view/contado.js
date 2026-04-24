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
		dateFormat : 'yy-mm-dd',
		firstDay : 1,
		isRTL : false,
		showMonthAfterYear : false,
		yearSuffix : ''
	};
	$.datepicker.setDefaults($.datepicker.regional['es']);
});

function consultar_clientes() {
	strUrl_Proceso = sUrlP + "DataSource_Cliente";
	var id = $("#cedula").val();
	if(id == ''){
		alert('Debe ingresar una cedula');
		return 0;
	}
	
	$.ajax({
		url : strUrl_Proceso,
		type : 'POST',
		data : 'id=' + id,
		dataType : 'json',
		success : function(json) {
			cedula = json["documento_id"];
			celular = json["celular"];
			pnom = json["primer_nombre"];
			snom = json["segundo_nombre"];
			pape = json["primer_apellido"];
			sape = json["segundo_apellido"];
			telf = json["telefono"];
			nombre = pnom + ' ' + snom + ' ' + pape + ' ' +sape;
			$('#cliente').val(nombre);
			$('#tel').val(telf);
			
			
		}
	});
	return true;
}

function Guardar() {
	var ced = $("#cedula").val();
	var fecha = $("#fecha").val();
	var fact = $("#factura").val();
	var nombre = $("#cliente").val();
	var dir = $("#dom").val();
	var tel = $("#tel").val();
	var mon = $("#monto").val();
	var det = $("#detalle").val();
	var fp = $("#fp").val();
	if (ced == '' || fecha == '' || nombre == '' || fact == '' || dir == '' || tel == '' || mon == '' || det == '' || fp == '') {
		alert("Debe ingresar todos los datos");
	} else {
		$.ajax({
			url : sUrlP + "Guarda_Contado",
			data : "cedula=" + ced + "&fecha=" + fecha + "&factura=" + fact + "&nombre=" + nombre + "&direc=" + dir + "&telf=" + tel + "&monto=" + mon + "&descrip="+det + "&fp=" + fp,
			type : "POST",
			success : function(html) {
				$('#msj_alertas').html(html);
				$('#msj_alertas').dialog('open');
				$("#cedula").val('');
				$("#fecha").val('');
				$("#cliente").val('');
				$("#dom").val('');
				$("#factura").val('');
				$("#monto").val('');
				$("#detalle").val('');
				$("#tel").val('');
				$("#fp").val('');
				
			}
		});
	}
}


