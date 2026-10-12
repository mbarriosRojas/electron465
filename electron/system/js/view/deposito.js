/**
 * @pakacge system.js.views
 */
$( function() {
	$('#tabs').tabs();
	Pendientes();
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


function Pendientes() {
	strUrl_Proceso = sUrlP + "PendientesDeposito";
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
