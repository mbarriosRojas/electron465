$(function() {

	$("#mbuzon").removeClass('active');
	$("#mreporte").addClass('active');

	Vtxt();

	$(".dialogo").dialog({
		modal : true,
		autoOpen : false,
		position : 'center',
		hide : 'explode',
		show : 'slide',
		width : 600,
		height : 300
	});
	
	var dates = $( "#txtFechaP" ).datepicker({
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
    $( "#txtFechaC" ).datepicker({
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
	$( "#txtFechaP" ).datepicker( "option", "dateFormat", "yy-mm-dd" );
    $( "#txtFechaC" ).datepicker( "option", "dateFormat", "yy-mm-dd" );
	
	$("#msj_alertas").dialog({
		modal : true,
		autoOpen : false,
		position : 'center',
		hide : 'explode',
		show : 'slide',
		width : 300,
		height : 200,
		buttons : {
			"Cerrar" : function() {
				$(this).html('');
				$(this).dialog("close");
			}
		}
	});

	$("#filtro_txt").dialog({
		buttons : {
			"Generar" : function() {
				leer_txt();
				$(this).dialog("close");
			},
			"Cerrar" : function() {
				$(this).dialog("close");
			}
		}
	});

	$("#Procesar").dialog({
		modal : true,
		autoOpen : false,
		position : 'center',
		hide : 'explode',
		show : 'slide',
		buttons : {
			"Procesar" : function() {
				Itxt();
				$(this).dialog("close");
			},
			"Cerrar" : function() {
				$(this).dialog("close");
			}
		}
	});
});

function MDiv(sCert, sLina, sNom, sFech) {
	$("#txtCobrado").find('option').remove().end();
	$("#filtro_txt").dialog('open');
	$("#txtCertificado").val(sCert);
	$("#txtNomina").val(sNom);
	$("#txtFecha").val(sFech);
	$("#txtCobrado").append(new Option(sLina, sLina));
}

function Vtxt() {
	$("#Respuesta").html("NO EXISTEN ARCHIVOS PENDIENTES POR PROCESAR...<br>");
	$("#carga_busqueda").dialog('open');
	$.ajax({
		url : sUrlP + "Vtxt",
		type : "POST",
		dataType : "json",
		success : function(oEsq) {
			Grid = new TGrid(oEsq, 'Respuesta', '');
			Grid.SetXls(true);
			Grid.SetNumeracion(true);
			Grid.SetName("Respuesta");
			Grid.SetDetalle();
			Grid.Generar();			
		}
	});
	$("#carga_busqueda").dialog('close');
}

/**
 * Insertar Archivo de Texto al sistema de pagos
 * @param txt
 * @param certificado
 */
function Itxt() {
	var sCertificado = $("#txtCert").val();
	var sTxt = $("#txtTxt").val();
	var banco = $("#txtCobrado option:selected").text();
	if(banco == "Fondo Comun" || banco == "FONDO COMUN")
		banco = "bfc";
	var sFecha = $("#txtFechaP").val();
    var cFecha = $("#txtFechaC").val();
    $.ajax({
		url : sUrlP + "Itxt",
		type : "POST",
		data : "txt=" + sTxt + "&clv=" + sCertificado + "&banco=" + banco +"&fecha="+sFecha+"&mesCobro="+cFecha,
		//dataType : "json",
		success : function(oEsq) {
				$("#msj_alertas").html("<br><br><center><h3>" + oEsq + "</h3></center>");
				$("#msj_alertas").dialog('open');
				
		}
	});
}

function leer_txt() {
	$("#carga_busqueda").dialog('open');
	var banco = $("#txtCobrado option:selected").text();
	if(banco == "Fondo Comun" || banco == "FONDO COMUN")
		banco = "bfc";
	var tipoa = $("#txtTipoA option:selected").val();
	if(tipoa == ''){
		alert("Debe Seleccionar Tipo Archivo");
		return false;
	}
	var sFecha = $("#txtFechaP").val();	
	var sData = "banco=" + banco + "&fecha=" + sFecha;
	var sCertificado = $("#txtCertificado").val();
	var genera = true;

	var inputFileImage = document.getElementById("archivo");
	var file = inputFileImage.files[0];
	var datos = new FormData();
	datos.append('archivo', file);
	datos.append('banco', banco);
	datos.append('fecha', sFecha)
	var url = "SubirTxt";
	var pasa = Validar(file, banco);
	if(pasa == "OK") {
		$.ajax({
			url : sUrlP + url,
			type : "POST",
			data : datos,
			contentType : false,
			processData : false,
			cache : false,
			dataType : "json",
			success : function(respuesta) {//alert(respuesta.msg);
				if(respuesta.ok != 'si') {
					$("#carga_busqueda").dialog('close');
					$("#msj_alertas").html("<h2>" + respuesta.msg + "</h2>");
					$("#msj_alertas").dialog('open');
					genera = false;

				} else {
					sData += "&archivo=" + respuesta.archivo + "&tipo_archivo=" + tipoa;
					$("#txtCert").val(sCertificado);
					$("#txtTxt").val(respuesta.txt);
					$.ajax({
						url : sUrlP + "Ltxt",
						type : "POST",
						data : sData,
						dataType : "json",
						success : function(oEsq) {
							if(oEsq.Error != null) {
								$("#msj_alertas").html("<br><br>" + oEsq.Error );
								$("#msj_alertas").dialog('open');
							} else {
								$("#Procesar").html("<br><br><center><h3>en hora buena felicitaciones <br></h3></center>");
								$("#Procesar").dialog('open');
							}

							$("#carga_busqueda").dialog('close');
						}
					});
				}
			}
		});
	} else {
		$("#carga_busqueda").dialog('close');
		$("#msj_alertas").html("<h2>" + pasa + "</h2>");
		$("#msj_alertas").dialog('open');

	}
}

function Validar(archivo, banco) {//alert(banco);
	mensaje = 'OK';
	if(archivo && banco != '') {
		extensiones_permitidas = new Array(".txt", ".csv", ".dvr");
		nombre = $("#archivo").val();
		extension = (nombre.substring(nombre.lastIndexOf("."))).toLowerCase();
		switch(extension) {
			case ".txt":
				if(banco == "PROVINCIAL" || banco == "VENEZUELA") {
					mensaje = "Formato de Archivo Invalido Para El Banco provincial y venezueka";
				}
				break;
			case ".dvr":
				if(banco != "PROVINCIAL") {
					mensaje = "Formato de Archivo Invalido Para El Banco provincial";
				}
				break;
			case ".DVR":
				if(banco != "PROVINCIAL") mensaje = "Formato de Archivo Invalido Para El Banco PROVINCIAL2";
				break;
			case ".csv":
				if(banco != "VENEZUELA") {
					mensaje = "Formato de Archivo Invalido Para El Banco Seleccionado";
				}
				break;
			default:
				mensaje = "El formato de archivo seleccionado no se encuentra entre los permitidos";
		}
	} else {
		mensaje = "DEBE INGRESAR TODOS LOS CAMPOS";
	}
	return mensaje;
}

