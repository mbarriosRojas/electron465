$(function() {
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
	$("#fecha").datepicker("option", "dateFormat", "yy-mm-dd");

	$.ajax({
		url : sUrlP + "maximo_acuse",
		type : 'POST',
		success : function(html) {
			$("#txtCodigo").val(html);
		}
	});
	listar();
});

function Guardar_Recibo() {
	strUrl_Proceso = sUrlP + "GuardaReciboAdmin";
	var acuse = $("#txtCodigo").val();
	var monto = $("#txtMonto").val();
	var nombre = $("#txtNombre").val();
	var tipo = $("#txtTipo option:selected").val();
	var ced = $("#txtCedula").val();

	var concepto = $("#txtConcepto").val();
	var banco = $("#txtBanco option:selected").text();
	var chequera = $("#txtChequera").val();
	var cheque = $("#txtCheque").val();
	var fecha = $("#fecha").val();
	var pago = $("#txtPago").val();
	var pendiente = $("#cmbPendiente option:selected").val();


	if (ced == '' || monto == '' || nombre == '' || fecha == '' || tipo == '' || concepto == '' || banco == '' || chequera == '' || cheque == '') {
		alert( "Debe ingresar todos los datos");
		return 0;
	}

	var data = 'acuse=' + acuse + '&cedula=' + ced + '&monto=' + monto + 
		'&nombre=' + nombre + '&fecha=' + fecha + '&tipo=' + tipo + '&cheque=' + 
		cheque + '&banco=' + banco + '&concepto=' + concepto + '&chequera=' + chequera +
		'&pago=' + pago + '&pendiente=' + pendiente;
	
	$.ajax({
		url : strUrl_Proceso,
		type : 'POST',
		data : data,
		//dataType: 'json',
		success : function(html) {
			$("#msj_alertas").html(html);
			$("#msj_alertas").dialog('open');
		}
	});
	limpiar();
}

function limpiar(){
	
	$("#txtMonto").val('');
	$("#txtNombre").val('');
	
	$("#txtCedula").val('');
	$("#txtConcepto").val('');
	$("#txtChequera").val('');
	$("#txtCheque").val('');
	$("#fecha").val('');
	$("#opera0").hide();
	$("#opera1").hide();

	$.ajax({
		url : sUrlP + "maximo_acuse",
		type : 'POST',
		success : function(html) {
			$("#txtCodigo").val(html);
		}
	});
	listar();
}

function listar(){
	
	strUrl_Proceso = sUrlP + "Listar_Acuse";
	$.ajax({
		url : strUrl_Proceso,
		dataType : "json",
		success : function(oEsq) {
			Grid3 = new TGrid(oEsq,'acuse','acuse');
			Grid3.SetName("Lista");			
			Grid3.SetNumeracion(true);
			Grid3.Generar();
		}
	});
}

function isNumberKey(evt) {
	var charCode = (evt.which) ? evt.which : event.keyCode;
	if (charCode > 31 && (charCode < 48 || charCode > 57))
		return false;
	return true;
}

function consultarClientes() {

	var id = $("#txtCedula").val();
	if (id == '') {
		alert('Debe ingresar una cedula');
		return 0;
	}

	strUrl_Proceso = sUrlP + "DataSource_Cliente";
	$.ajax({
		url : strUrl_Proceso,
		type : 'POST',
		data : 'id=' + id,
		dataType : 'json',
		success : function(json) {
			limpiar();
			if (json['primer_nombre'] != null) {
				cedula = json["documento_id"];
				pnom = json["primer_nombre"];
				snom = json["segundo_nombre"];
				pape = json["primer_apellido"];
				sape = json["segundo_apellido"];
				sexo = json["sexo"];
				naci = json["nacionalidad"];

				$('#txtCedula').val(cedula);
				$('#txtNombre').val(pape + ' ' + sape + ' ' + pnom + ' ' + snom);

				//disponibilidad = json["disponibilidad"];
				
			}else{
				//No se encontro la persona es un cliente o un proveedor (EVALUAR)
			}

		}
	});
	cargarNotas(id);
	return true;
}

function cargarNotas(id) {

	if (id == '') {
		alert('Debe ingresar una cedula');
		return 0;
	}
	$("#cmbPendiente").empty();
	$("#cmbPendiente").append(new Option('SELECCIONAR OPERACIONES PENDIENTES', 0));
	$("#opera0").hide();
	$("#opera1").hide();

	strUrl_Proceso = sUrlP + "DataSource_NotasCreditos";
	$.ajax({
		url : strUrl_Proceso,
		type : 'POST',
		data : 'id=' + id,
		dataType : 'json',
		success : function(json) {

			val = false;
			$.each(json, function(sId, sVal) {
				$("#cmbPendiente").append(new Option(sVal.motivo, sVal.oid));
				val = true;
			});
			if(val == true) {
				$("#opera0").show();
				$("#opera1").show();
			}
		}
	});
	return true;
}
