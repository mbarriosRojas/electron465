$(function() {
	$("#btnTras").hide();
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
	$("#fecha").datepicker({
		showOn : "button",
		buttonImage : sImg + "calendar.gif",
		buttonImageOnly : true,
	});
	$("#fecha").datepicker("option", "dateFormat", "yy-mm-dd");
});

function Procesar(){
	var fecha = $("#fecha").val();
	if(fecha ==''){
		alert('Debe ingresar fecha del mes a trasladar');
		return false;
	}
	$("#carga_busqueda").dialog('open');
	$.ajax({
		url : sUrlP + "Lista_Por_Trasladar",
		type : "POST",
		data : "fecha=" + fecha,
		dataType : "json",
		success : function(oEsq) {
			if(oEsq.msj == "SI"){
				Grid = new TGrid(oEsq, 'formulario', "Lista De voucher a trasladar");
				Grid.SetXls(true);
				Grid.SetNumeracion(true);
				Grid.SetName("traslados");
				Grid.Generar();
				$("#carga_busqueda").dialog('close');
				$("#btnTras").show();
			}else{
				$("#carga_busqueda").dialog('close');
				alert(oEsq.msj);
			}
			
		}
	});
}

function Traslada(){
	$("#carga_busqueda").dialog('open');
	$.ajax({
		url : sUrlP + "Procesa_Traslado_Masivo",
		type : "POST",
		success : function(resp) {alert(resp);
			$("#carga_busqueda").dialog('close');
			$("#msj_alerta").htm(resp);
			$("#msj_alertas").dialog('open');
		}
	});
}