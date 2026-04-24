
/**
 |	Bandera que permite establecer si se cancela 
 |	Una factura en su totalidad 
 */
formaPago = 'NO';


$(function() {
	$("#fecha" ).datepicker({
		showOn: "button",
		buttonImage: sImg + "calendar.gif",
		buttonImageOnly: true
	});
	$("#fechaAbono" ).datepicker({
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
	$( "#fechaAbono" ).datepicker( "option", "dateFormat", "yy-mm-dd" );
	
	
});

function consultar_clientes() {
	strUrl_Proceso = sUrlP + "DataSource_ReciboI";
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
                $('#txtFacturaV').append(new Option('', 0, true, true));
				$.each(json["facturas"], function(item,sValor) {
					$('#txtFactura').append(new Option(sValor, sValor, true, true));
				});
                $.each(json["facturas2"], function(item,sValor) {
                    $('#txtFacturaV').append(new Option(sValor, sValor, true, true));
                    $('#txtFacturas').append(new Option(sValor, sValor, true, true));
                });
                $('#txtFacturasV').append(new Option("----------------------", '0', true, true));
                $('#txtFacturas').append(new Option("----------------------", '0', true, true));
                //
                //$.each(json["facturas2"], function(item,sValor) {
                //});
                
				$("#carga_busqueda").dialog('close');
				if(json["recibos"] != null){
					Grid2 = new TGrid(json["recibos"],'Recibos','Historial de Recibos Ingreso');
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
	cargar = $("#txtFactura option:selected").val() + ' | ' + montoC  + ' | ' + $("#fechaAbono").val();
	$("#txtFactura option:selected").attr("disabled", true);
	$("#txtFactura > option[value=0]").attr("selected","selected");
	if(montoC == '' || cargar == undefined || cargar == 0){
		alert("Debe ingresar el monto que se le va a cargar al contrato");
	}else{
		$("#lstAgregados").append(new Option(cargar, original));
	}
	$("#txtMontoCarga").val('');	
}

function GuardaRecibo(){
	strUrl_Proceso = sUrlP + "Guardar_Recibo";
	cedula = $("#txtCedula").val();
	reciboPre = $("#txtReciboPre").val();
	monto = $("#txtmontorecibo").val();
	recibido = $("#txtRecibidoDe").val();
	fecha = $("#fecha").val();
	tipo = $("#txtTipoPago option:selected").val();
	cheque = $("#txtCheque").val();
	banco = $("#txtBanco option:selected").val();
	concepto = $("#txtConcepto").val();
	empresa = $("#txtEmpresa option:selected").val();
	monto_a = 0;
	creditos = new Array();
    voucher = new Array();
	i=0;
	$("#lstAgregados option").each(function(){
       aux = $(this).text();
       aux2 = aux.split('|');
       monto_a += parseFloat(aux2[2]);
       creditos[i] = aux;
       i++;
    });
	check = ($("#reintegro").is(':checked'));
	cargar = 0;
    if ( check == true){
    	cargar = 1;
    }
    /*
    voucher
     */
    i=0;
    $("#lstVoucher option").each(function(){
        aux = $(this).text();
        voucher[i] = aux;
        i++;
    });
    check1 = ($("#voucher").is(':checked'));
    cargar1 = 0;
    if ( check1 == true){
        cargar1 = 1;
    }
    //alert(monto_a + "/" + monto);
    if(cedula != '' && monto != '' && recibido != '' && fecha != '' && tipo != ''  && concepto != '' && empresa != '' ){
	    if(monto_a != monto && cargar == 1){
	    	alert('Los montos estan mal cargados');
	    	return 0;
	    }
	   
	   
	    $.ajax({
			url: strUrl_Proceso, 
			type : 'POST',
			data : 'creditos=' + creditos + '&cedula=' + cedula + '&monto=' + monto + '&recibido=' + recibido + '&fecha=' + fecha 
			+ '&tipo=' + tipo + '&cheque=' + cheque + '&banco=' + banco + '&concepto=' + concepto + '&empresa=' + empresa+'&cargar='+cargar+'&cargar1='+cargar1
			+'&voucher='+voucher+'&reciboPre=' + reciboPre + '&formaPago=' + formaPago,
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
	$("#txtReciboPre").val('');
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

function quitarVoucher(){
    elemento = $("#lstVoucher option:selected").val();
    ele = elemento.split("|");
    $("#txtVoucher").append(new Option(ele[1], ele[1]));
    $("#lstVoucher option:selected").remove();
}


function Mostrar(nivel) {
	check = ($("#reintegro").is(':checked'));
    if ( check == true){
    	$("#datosR").show();
    }else{
    	$("#datosR").hide();
    }
}

function MostrarVoucher(nivel) {
	check = ($("#voucher").is(':checked'));
    if ( check == true){
    	$("#datosRV").show();
    }else{
    	$("#datosRV").hide();
    }
}

function Agrega_Voucher(){
	montoC = $("#txtVoucher option:selected").val();
    $("#txtVoucher option:selected").remove();
	original = $("#txtFacturaV option:selected").val();
	cargar = $("#txtFacturaV option:selected").val() + ' | ' + montoC;
	if(montoC == '' || cargar == undefined || cargar == 0){
		alert("Debe ingresar el monto que se le va a cargar al contrato");
	}else{
		$("#lstVoucher").append(new Option(cargar, cargar));
	}
}

function buscaVoucher(){
    strUrl_Proceso = sUrlP + "listaVoucherRecibo";
    var factura = $("#txtFacturaV option:selected").val();
    if(factura != ''){
        $("#carga_busqueda").dialog('open');
        $.ajax({
            url: strUrl_Proceso,
            type : 'POST',
            data : 'factura=' + factura,
            dataType: 'json',
            success : function(json) {//alert(json);
                if(json['msj']== "si"){
                    $("#txtVoucher").html('');
                    $.each(json['filas'], function(item,sValor) {
                        $('#txtVoucher').append(new Option(sValor, sValor, true, true));
                    });
                }else{
                    alert("no posee voucher activos en la factura seleccionada");
                }

                $("#carga_busqueda").dialog('close');
            }
        });
    }
}

function PagarFacturas(){
	
	fecha = $("#fechaAbono").val();
	strUrl_Proceso = sUrlP + "PagarFacturas";
    var factura = $("#txtFacturas option:selected").val();
    if(factura != '' || fecha != ''){
        $("#carga_busqueda").dialog('open');
        $.ajax({
            url: strUrl_Proceso,
            type : 'POST',
            data : 'factura=' + factura + '&fecha=' + fecha,
            dataType: 'json',
            success : function(json) {   			
                $.each(json, function(item,sValor) {                	
					$("#lstAgregados").append(new Option(sValor, sValor));					
                });
                formaPago = factura; //Activación variable global para el pago de una factura total
                $("#carga_busqueda").dialog('close');
                
            }
        });
    }
	
}
