/**
 * @pakacge system.js.views
 */
$( function() {
	$('#tabs').tabs();
	Pendientes();
	CProgramadas();
	Rechazos();
	Aceptaciones();
	var dates = $( "#fecha_desde, #fecha_hasta" ).datepicker({
		showOn: "button",
		buttonImage: sImg + "calendar.gif",
		buttonImageOnly: true,
		onSelect: function( selectedDate ) {
			var option = this.id == "fecha_desde" ? "minDate" : "maxDate",
			instance = $( this ).data( "datepicker" ),
			date = $.datepicker.parseDate(
			instance.settings.dateFormat ||
			$.datepicker._defaults.dateFormat,
			selectedDate, instance.settings );
			dates.not( this ).datepicker( "option", option, date );
		}
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
	$( "#fecha_desde" ).datepicker( "option", "dateFormat", "yy-mm-dd" );
	$( "#fecha_hasta" ).datepicker( "option", "dateFormat", "yy-mm-dd" );
	
	$('#cobros').dialog({
			autoOpen: false,
			position: 'top',
			width: "50%",
			height: 500,
			buttons: {
				"Aceptar": function() { 
					Enviar(); 
				},					
				"Cancelar": function() {
					$(this).dialog("close"); 
				}	
			}
		});
});


/**
 *Aceptaciones Formales 
 */
function Aceptaciones() {
	strUrl_Proceso = sUrlP + "PendientesRevision/1";
	$.ajax({
		url : strUrl_Proceso,
		dataType : "json",
		success : function(oEsq) {
			Grid = new TGrid(oEsq,'PendientesRevision','');
			Grid.SetName("PendientesRevision");			
			Grid.SetNumeracion(true);
			Grid.Generar();
		}
	});
}


function Pendientes() {
	strUrl_Proceso = sUrlP + "Pendientes";
	$.ajax({
		url : strUrl_Proceso,
		dataType : "json",
		success : function(oEsq) {
			Grid = new TGrid(oEsq,'Pendientes','');
			Grid.SetName("Pendientes");			
			Grid.SetNumeracion(true);
			Grid.Generar();
		}
	});
}

function CProgramadas() {
	strUrl_Proceso = sUrlP + "CProgramadas";
	$.ajax({
		url : strUrl_Proceso,
		dataType : "json",
		success : function(oEsq2) {//alert(oEsq2.sql);
			Grid3 = new TGrid(oEsq2,'C_Programadas','');
			Grid3.SetName("Programadas");			
			Grid3.SetNumeracion(true);
			Grid3.SetXls(true);
			Grid3.Generar();
		}
	});
}

function Rechazos() {
	 
	strUrl_Proceso = sUrlP + "Rechazos";
	$.ajax({
		url : strUrl_Proceso,
		dataType : "json",
		success : function(oEsq) {
			Grid2 = new TGrid(oEsq,'Rechazos','');
			Grid2.SetName("Rechazos");
			Grid2.SetNumeracion(true);
			Grid2.Generar();
		}
	});
}

function Cargar_Monto(voucher,factura){
	strUrl_Proceso = sUrlP + "DataSource_Cobros_Programadas";
	$("#txtVoucher").val(voucher);
	$("#txtFactura").val(factura);
	$.ajax({
			url: strUrl_Proceso,
			type: 'POST',
			data: 'voucher=' + voucher + '&factura=' + factura,
			dataType : "json",
			success: function(oEsqCobros) {
				GridC = new TGrid(oEsqCobros,'tabla_cobros','COBROS REALIZADOS');
				GridC.SetName("COBROS");
				GridC.SetNumeracion(true);
				GridC.Generar();
				$('#cobros').html(htm);				
		} 
	});
	$('#cobros').dialog("open");	
}

function Enviar(){
	strUrl_Proceso = sUrlP + "Agregar_Cobros_Programadas";
	var voucher = $("#txtVoucher").val();
	var factura = $("#txtFactura").val();
	var monto = $("#txtmontocobrado").val();		
	var diaC = $("#txtDiaCobro").val();
	var mesC = $("#txtMesCobro").val();
	var anoC = $("#txtAnoCobro").val();
	var fecha = anoC + "-" + mesC + "-" + diaC;
	var observa = $("#txtDescripcion").val();
	var referencia = $("#txtReferencia").val();
	$("#msj_alertas").html('POR FAVOR ESPERE MIENTRAS SE CARGA EL COBRO');
	$("#msj_alertas").dialog("open");
	$.ajax({
		url: strUrl_Proceso,
		type: 'POST',
		data: 'voucher=' + voucher + '&fecha=' + fecha + '&observa=' + observa + '&cuota=' + monto + '&referencia=' + referencia + '&factura=' + factura,
		success: function(htm) {
			$("#msj_alertas").html(htm);
			Cargar_Monto(voucher);		 	
		}					
	})
}
