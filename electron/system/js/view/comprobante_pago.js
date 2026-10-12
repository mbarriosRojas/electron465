$(function() {
	$("#fecha" ).datepicker({
		showOn: "button",
		buttonImage: sImg + "calendar.gif",
		buttonImageOnly: true
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
	$( "#fecha" ).datepicker( "option", "dateFormat", "yy-mm-dd" );
});


function Guardar_Recibo(){
	strUrl_Proceso = sUrlP + "Guardar_ReciboCP";
	cedula = $("#txtCedula").val();
	monto = $("#txtmontorecibo").val();
	recibido = $("#txtRecibidoDe").val();
	fecharecibo$ = ("#txtFechare").val();
	facturapaga$ = ("#txtFactp").val();
	observaciones = $("#txtObservaciones").val();
	Ncheque = $("#txtCheque").val();
	$("#txtBanco").val();
	$("#txtNdep").val();
	$("#txtNtran").val();
	$("#txtNtar").val();
	
	fecha = $("#fecha").val();
	tipo = $("#txtTipoPago option:selected").val();
	creditos = new Array();
	i=0;
	$("#lstAgregados option").each(function(){
       aux = $(this).text();
       aux2 = aux.split('|');
       monto_a += parseFloat(aux2[2]);
       creditos[i] = aux;
       i++;
    });
    check = ($("#reintegro").is(':checked'));
    if ( check == true){
    	reintegro = 1;
    }
 	if(cedula != '' && monto != '' && recibido != '' && fecha != '' && tipo != ''  && concepto != '' && empresa != '' ){
	    if(monto_a != monto && reintegro == 1){
	    	alert('Los montos estan mal cargados');
	    	return 0;
	    }
	   
	   $.ajax({
			url: strUrl_Proceso, 
			type : 'POST',
			data : 'creditos=' + creditos + '&cedula=' + cedula + '&monto=' + monto + '&recibido=' + recibido + '&fecha=' + fecha 
			+ '&tipo=' + tipo + '&cheque=' + cheque + '&banco=' + banco + '&concepto=' + concepto + '&empresa=' + empresa + "&reintegro=" + reintegro,
			//dataType: 'json',
			success : function(html) {
				$("#msj_alertas").html(html);
				$("#msj_alertas").dialog('open');	
			}
		});
		limpiar();
    }else{
    	alert('DEBE INGRESAR TODOS LOS DATOS');
    }
    
}