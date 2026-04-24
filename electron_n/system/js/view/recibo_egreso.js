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

function consultar_clientes() {
	strUrl_Proceso = sUrlP + "DataSource_ReciboE";
	var id = $("#txtCedula").val();
	if(id != ''){
		$("#carga_busqueda").dialog('open');
		$.ajax({
			url: strUrl_Proceso, 
			type : 'POST',
			data : 'id=' + id,
			dataType: 'json',
			success : function(json) {
				limpiar();
				$("#txtCedula").val(id);
				var recibido_de = json["primer_nombre"] + " " + json["segundo_nombre"] + " " + json["primer_apellido"] + " " + json["segundo_apellido"];
				$("#txtRecibidoDe").val(recibido_de);
				$('#txtFactura').append(new Option('', 0, true, true));
				$.each(json["facturas"], function(item,sValor) {
					$('#txtFactura').append(new Option(sValor, sValor, true, true));
				});
				$("#carga_busqueda").dialog('close');
				if(json["recibos"] != null){
					Grid2 = new TGrid(json["recibos"],'Recibos','Historial de Recibos Engreso');
					Grid2.SetName("recibos");
					Grid2.SetDetalle();
					Grid2.Generar();	
				}
				
			}
		});
	}else{
		alert("Debe ingresar la cedula del cliente...");
	}
}

function Agrega_Contrato(){
	montoC = $("#txtMontoCarga").val();
	original = $("#txtFactura option:selected").val();
	cargar = $("#txtFactura option:selected").val() + ' | ' + montoC;
	$("#txtFactura option:selected").attr("disabled", true);
	$("#txtFactura > option[value=0]").attr("selected","selected");
	if(montoC == '' || cargar == undefined || cargar == 0){
		alert("Debe ingresar el monto que se le va a cargar al contrato");
	}else{
		$("#lstAgregados").append(new Option(cargar, original));
	}
	$("#txtMontoCarga").val('');	
}
function GuardaReciboE(){
	strUrl_Proceso = sUrlP + "Guardar_ReciboE";
	cedula = $("#txtCedula").val();
	monto = $("#txtmontorecibo").val();
	recibido = $("#txtRecibidoDe").val();
	fecha = $("#fecha").val();
	tipo = $("#txtTipoPago option:selected").val();
	cheque = $("#txtCheque").val();
	banco = $("#txtBanco option:selected").val();
	concepto = $("#txtConcepto").val();
	empresa = $("#txtEmpresa option:selected").val();
	monto_a = 0;
	reintegro = 0;
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

function limpiar(){
	$("#txtCedula").val('');
	$("#txtmontorecibo").val('');
	$("#txtRecibidoDe").val('');
	$("#fecha").val('');
	$("#txtCheque").val('');
	$("#txtConcepto").val('');
	$("#txtFactura").html('');
	$("#Contratos").html('');
	$("#Recibos").html('');
	$("#lstAgregados").html('');
}

function quitar(){
	elemento = $("#lstAgregados option:selected").val();
	$("#txtFactura option").each(function(){
		if($(this).text() == elemento){
			$(this).attr("disabled", false);
		}
	});
	$("#lstAgregados option:selected").remove();
}

function Mostrar(nivel) {
	check = ($("#reintegro").is(':checked'));
    if ( check == true){
    	$("#datosR").show();
    }else{
    	$("#datosR").hide();
    }
}