$( function () {
	$("#mbuzon").removeClass('active');
	$("#mreporte").addClass('active');
	Listar();
	$(".dialogo").dialog({
		modal: true,
		autoOpen: false,
		position: 'top',
		hide: 'explode',
		show: 'slide',
		width: 600,
		height: 340
	});
	$("#msj_alertas").dialog({
		modal: true,
		autoOpen: false,
		position: 'top',
		hide: 'explode',
		show: 'slide',
		width: 600,
		height: 400, 
		buttons: {
			"Cerrar": function() {
				$(this).html('');
				$(this).dialog("close");
			}
		}
	});
	$("#filtro_txt").dialog({buttons: {
			"Generar": function() {
				crear_txt();
				$(this).dialog("close");
			},
			"Cerrar": function() {
				$(this).dialog("close");
			}
		}
	});
});

function MostrarDiv(div) {
	$("#"+div).dialog('open');
}

//Listar Linajes
function Listar() {
	$("#txtCobrado").find('option').remove().end();
	$.ajax({
		url :  sUrlP + "Listar",
		dataType : "json",
		success : function(data) {			
			$("#txtCobrado").append(new Option('', ''));
			$.each(data['linaje'], function(item, valor) {						
				$("#txtCobrado").append(new Option(valor['valor'], valor['id']));
			});	
		}
	});
}

function crear_txt(){
	$("#carga_busqueda").dialog('open');
	var empresa = $("#txtEmpresa option:selected").val();
	var banco = $("#txtCobrado option:selected").text();
	var nomina = $("#txtNomina option:selected").text();
	var fcontrato = $("#txtFormaContrato option:selected").val();
	var tipoa = $("#txtTipoA option:selected").val();
	var periodicidad = $("#txtPeriodicidad option:selected").val();
	var picar = $("#txtPicar option:selected").val();
	
	var fecha = $("#txtAno").val() + "-" + $("#txtMes").val() + "-" + $("#txtDia").val();
	var sData = "empresa=" + empresa + "&banco=" + banco + "&fcontrato=" + fcontrato + "&periodicidad=" + periodicidad + "&fecha=" + fecha + "&nomina=" + nomina + '&tipo_archivo=' + tipoa + "&picar=" + picar;
	if(tipoa == ''){
		alert("Debe Seleccionar Tipo");
		return false;
	}
	$.ajax({
		url : sUrlP + "Gtxt",
		type : "POST",
		data : sData,
		success : function(respuesta) {
			$("#carga_busqueda").dialog('close');
			$("#msj_alertas").html(respuesta);
			$("#msj_alertas").dialog('open');
		}
	});
}

function muestra_nomina(){
	var banco = $("#txtCobrado option:selected").text();
	$("#txtNomina").find('option').remove().end();
	$.ajax({
		url : sUrlP + "Nomina_Banco",
		type : "POST",
		data : "banco="+banco,
		dataType : "json",
		success : function(data) {
			$.each(data, function(item, valor) {						
				$("#txtNomina").append(new Option(valor, valor));
			});
			$("#txtNomina").append(new Option('VARIAS', 'VARIAS'));
		}
	});
}