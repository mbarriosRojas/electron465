$(function() {
	//CEntregas();
	tipo = $("#tipoV").val();
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
				url : sUrlP + "/M_Json_Mercancia",
				data : "nombre=" + $("#txtDescripcion").val() + "&tipo=" + tipo ,
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
	
	var dates = $( "#fecha" ).datepicker({
		showOn: "button",
		buttonImage: sImg + "calendar.gif",
		buttonImageOnly: true,
		onSelect: function( selectedDate ) {
			var option = this.id == "fecha_desde_cuadre" ? "minDate" : "maxDate",
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
});

function Consultar_Mercancia(){
	$.ajax({
		url : sUrlP + "Consulta_Mercancia",
		data: "nombre=" + $("#txtDescripcion").val(),
		type : 'post',
		dataType : 'json',
		success : function(json) {
			$("#txtDescripcion").val('');
			
			if(json['codigo'] != undefined){
				var tabla='<br><p><font color="#1c94c4">Codigo:</font> <b>' + json['codigo'] +'</b> <br> <font color="#1c94c4">Descripcion: </font><b>'+ json['descripcion'];
				tabla += '<br><p><font color="#1c94c4">Modelo:</font> <b>' + json['modelo'] +'</b> <br> <font color="#1c94c4">Marca: </font><b>'+ json['marca'];
				tabla += '<br><p><font color="#1c94c4">Ubicacion:</font> <b>' + json['almacen'] +'</b> <br> <font color="#1c94c4">Proveedor: </font><b>'+ json['proveedor'];
				tabla += '<br><p><font color="#1c94c4">Cantidad:</font> <b>' + json['cantidad'] +'</b>';
				$("#cantidad").val(json['cantidad']);
				$("#descrip").val(json['descripcion']);
				$("#modelo").val(json['modelo']);
				$('#datos').html(tabla);
				$("#formulario").show();//
				//alert(json['codigo']);
				$.ajax({
					url : sUrlP + "ListarGrid_Mercancia",
					type : "POST",
					data : "mercancia=" + json['codigo'],
					dataType : "json",
					success : function(oEsq) {
						Grid = new TGrid(oEsq,'datos','Mercancia En Existencia');
						Grid.SetXls(true);
						Grid.SetNumeracion(true);
						Grid.SetName("Actualizar");
						Grid.SetDetalle();
						Grid.Generar();
					}
				});
			}else{
				$('#datos').html('<p><font color="#1c94c4">No se encontro....</font>');
				$("#formulario").hide();
			}
		}
	});
}

function Consultar_Mercancia_Entrega(){
	$.ajax({
		url : sUrlP + "Consulta_Mercancia",
		data: "nombre=" + $("#txtDescripcion").val() + "&ubicacion=1",
		type : 'post',
		dataType : 'json',
		success : function(json) {
			$("#formulario").show();
			$("#txtDescripcion").val('');
			if(json['codigo'] != undefined){
				$("#txtSeriales").html("");
				var tabla='<br><p><font color="#1c94c4">Codigo:</font> <b>' + json['codigo'] +'</b> <br> <font color="#1c94c4">Descripcion: </font><b>'+ json['descripcion'];
				tabla += '<br><p><font color="#1c94c4">Modelo:</font> <b>' + json['modelo'] +'</b> <br> <font color="#1c94c4">Marca: </font><b>'+ json['marca'];
				tabla += '<br> <font color="#1c94c4">Proveedor: </font><b>'+ json['proveedor'];
				
				$("#descrip").val(json['descripcion']);
				$("#modelo").val(json['modelo']);
				$("#codigo").val(json['codigo']);
				$('#datos').html(tabla);
				$("#txtSeriales").append(new Option('----------', 0));
				$.each(json['seriales'], function(clave, valor) {
					items = String(valor).split(' | ');					
					$("#txtSeriales").append(new Option(valor, items[0]));
				});	
			}else{
				$('#datos').html('<p><font color="#1c94c4">No se encontro....</font>');
				$("#formulario").hide();
			}
		}
	});
}

function Ag_Seriales(){
	
	original = $("#txtSeriales option:selected").val();
	if(original != 0){
		cargar = $("#txtSeriales option:selected").val();
		$("#lstSeriales").append(new Option(cargar, original));
		$("#txtSeriales option:selected").attr("disabled", true);	
		$("#txtSeriales").val(0);
	}else{
		alert("Debe Seleccionar un Serial...");
	}
		
}

function Eli_Seriales(){
	elemento = $("#lstSeriales option:selected").val();
	$("#txtSeriales option").each(function(){
		if($(this).val() == elemento){
			$(this).attr("disabled", false);
		}
	});
	$("#lstSeriales option:selected").remove();
}



function Entregar(){
	l_seriales = new Array();
	i=0;
	$("#lstSeriales option").each(function(){
       aux = $(this).text();
       l_seriales[i] = aux;
       i++;
    });
	
	var entregado_a = $("#entregado_a").val();
	var fecha = $("#fecha").val();
	var descrip = $("#descrip").val();
	var modelo = $("#modelo").val();
	var codigo = $("#codigo").val();
	var tipo = $("#tipoV").val();
	if(entregado_a == '' || fecha == '' || descrip == '' || modelo == '' || codigo == '' || i == 0){
		alert("Debe ingresar todos los datos y al menos un serial...");
	}else{
		
		$.ajax({
			url : sUrlP + "Guarda_Entregar_Mercancia",
			data : "seriales=" + l_seriales + "&fecha=" + fecha + "&descripcion=" + descrip + "&modelo=" + modelo + "&codigo=" + codigo + "&entregado=" + entregado_a + "&tipo=" + tipo,
			type : "POST",
			success : function(html) {
				$('#msj_alertas').html(html);
				$('#msj_alertas').dialog('open');
				$('#txtCodigo').attr("disabled", true);
				$("#cantidad").val('');
				$("#entregado").val('');
				$("#entregado_a").val('');
				$("#fecha").val('');
				$("#descrip").val('');
				$("#modelo").val('');
				$("#formulario").hide();
				$("#datos").html('');
				$("lstSeriales").html('');
				
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
		success : function(oEsq) {//alert(oEsq);
			Grid = new TGrid(oEsq,'Reporte_Entregas','Mercancia Entregada');
			Grid.SetXls(true);
			Grid.SetNumeracion(true);
			Grid.SetName("Entregas");
			Grid.SetDetalle();
			Grid.Generar();
		}
	});
}


