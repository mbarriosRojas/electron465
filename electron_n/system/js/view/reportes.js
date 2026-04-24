$(function() {
	Filtro();
	$("#mbuzon").removeClass('active');
	$("#mreporte").addClass('active');
	Listar();
	Listar_Alvaro();
	var dates = $("#fecha_desde, #fecha_hasta").datepicker({
		showOn : "button",
		buttonImage : sImg + "calendar.gif",
		buttonImageOnly : true,
		onSelect : function(selectedDate) {
			var option = this.id == "fecha_desde" ? "minDate" : "maxDate", instance = $(this).data("datepicker"), date = $.datepicker.parseDate(instance.settings.dateFormat || $.datepicker._defaults.dateFormat, selectedDate, instance.settings);
			dates.not(this).datepicker("option", option, date);
		}
	});

	var dates1 = $("#fecha_desde_cuadre, #fecha_hasta_cuadre").datepicker({
		showOn : "button",
		buttonImage : sImg + "calendar.gif",
		buttonImageOnly : true,
		onSelect : function(selectedDate) {
			var option1 = this.id == "fecha_desde_cuadre" ? "minDate" : "maxDate", instance = $(this).data("datepicker"), date1 = $.datepicker.parseDate(instance.settings.dateFormat || $.datepicker._defaults.dateFormat, selectedDate, instance.settings);
			dates1.not(this).datepicker("option", option1, date1);
		}
	});
	var dates2 = $("#fecha_desde_cu, #fecha_hasta_cu").datepicker({
		showOn : "button",
		buttonImage : sImg + "calendar.gif",
		buttonImageOnly : true,
		onSelect : function(selectedDate) {
			var option = this.id == "fecha_desde_cu" ? "minDate" : "maxDate", instance = $(this).data("datepicker"), date = $.datepicker.parseDate(instance.settings.dateFormat || $.datepicker._defaults.dateFormat, selectedDate, instance.settings);
			dates2.not(this).datepicker("option", option, date);
		}
	});
	var dates3 = $("#fecha_desde_v, #fecha_hasta_v").datepicker({
		showOn : "button",
		buttonImage : sImg + "calendar.gif",
		buttonImageOnly : true,
		onSelect : function(selectedDate) {
			var option = this.id == "fecha_desde_v" ? "minDate" : "maxDate", instance = $(this).data("datepicker"), date = $.datepicker.parseDate(instance.settings.dateFormat || $.datepicker._defaults.dateFormat, selectedDate, instance.settings);
			dates3.not(this).datepicker("option", option, date);
		}
	});

	var dates4 = $("#fecha_desde_sp, #fecha_hasta_sp").datepicker({
		showOn : "button",
		buttonImage : sImg + "calendar.gif",
		buttonImageOnly : true,
		onSelect : function(selectedDate) {
			var option = this.id == "fecha_desde_sp" ? "minDate" : "maxDate", instance = $(this).data("datepicker"), date = $.datepicker.parseDate(instance.settings.dateFormat || $.datepicker._defaults.dateFormat, selectedDate, instance.settings);
			dates4.not(this).datepicker("option", option, date);
		}
	});
	
	var dates5 = $("#desde_contado, #hasta_contado").datepicker({
		showOn : "button",
		buttonImage : sImg + "calendar.gif",
		buttonImageOnly : true,
		onSelect : function(selectedDate) {
			var option = this.id == "desde_contado" ? "minDate" : "maxDate", instance = $(this).data("datepicker"), date = $.datepicker.parseDate(instance.settings.dateFormat || $.datepicker._defaults.dateFormat, selectedDate, instance.settings);
			dates5.not(this).datepicker("option", option, date);
		}
	});
	
	var dates6 = $("#desde_fpresu, #hasta_fpresu").datepicker({
		showOn : "button",
		buttonImage : sImg + "calendar.gif",
		buttonImageOnly : true,
		onSelect : function(selectedDate) {
			var option = this.id == "desde_fpresu" ? "minDate" : "maxDate", instance = $(this).data("datepicker"), date = $.datepicker.parseDate(instance.settings.dateFormat || $.datepicker._defaults.dateFormat, selectedDate, instance.settings);
			dates6.not(this).datepicker("option", option, date);
		}
	});
	
	var dates7 = $("#desde_entregas, #hasta_entregas").datepicker({
		showOn : "button",
		buttonImage : sImg + "calendar.gif",
		buttonImageOnly : true,
		onSelect : function(selectedDate) {
			var option = this.id == "desde_entregas" ? "minDate" : "maxDate", instance = $(this).data("datepicker"), date = $.datepicker.parseDate(instance.settings.dateFormat || $.datepicker._defaults.dateFormat, selectedDate, instance.settings);
			dates7.not(this).datepicker("option", option, date);
		}
	});
	
	var dates8 = $("#desde_ccargasd, #hasta_ccargasd").datepicker({
		showOn : "button",
		buttonImage : sImg + "calendar.gif",
		buttonImageOnly : true,
		onSelect : function(selectedDate) {
			var option = this.id == "desde_ccargasd" ? "minDate" : "maxDate", instance = $(this).data("datepicker"), date = $.datepicker.parseDate(instance.settings.dateFormat || $.datepicker._defaults.dateFormat, selectedDate, instance.settings);
			dates8.not(this).datepicker("option", option, date);
		}
	});
	
	var dates9 = $("#desde_ccargasv, #hasta_ccargasv").datepicker({
		showOn : "button",
		buttonImage : sImg + "calendar.gif",
		buttonImageOnly : true,
		onSelect : function(selectedDate) {
			var option = this.id == "desde_ccargasv" ? "minDate" : "maxDate", instance = $(this).data("datepicker"), date = $.datepicker.parseDate(instance.settings.dateFormat || $.datepicker._defaults.dateFormat, selectedDate, instance.settings);
			dates9.not(this).datepicker("option", option, date);
		}
	});


  var dates10 = $("#fecha_desde_controlmensual, #fecha_hasta_controlmensual").datepicker({
    showOn : "button",
    buttonImage : sImg + "calendar.gif",
    buttonImageOnly : true,
    onSelect : function(selectedDate) {
      var option10 = this.id == "fecha_desde_controlmensual" ? "minDate" : "maxDate", instance = $(this).data("datepicker"), date1 = $.datepicker.parseDate(instance.settings.dateFormat || $.datepicker._defaults.dateFormat, selectedDate, instance.settings);
      dates1.not(this).datepicker("option", option1, date1);
    }
  });

	var dates11 = $("#desde_fcontrol, #hasta_fcontrol").datepicker({
		showOn : "button",
		buttonImage : sImg + "calendar.gif",
		buttonImageOnly : true,
		onSelect : function(selectedDate) {
			var option = this.id == "desde_fcontrol" ? "minDate" : "maxDate", instance = $(this).data("datepicker"), date = $.datepicker.parseDate(instance.settings.dateFormat || $.datepicker._defaults.dateFormat, selectedDate, instance.settings);
			dates11.not(this).datepicker("option", option, date);
		}
	});
  
  var dates_sol = $("#desde_sol, #hasta_sol").datepicker({
	    showOn : "button",
	    buttonImage : sImg + "calendar.gif",
	    buttonImageOnly : true,
	    onSelect : function(selectedDate) {
	      var option_sol = this.id == "desde_sol" ? "minDate" : "maxDate", instance = $(this).data("datepicker"), date_sol = $.datepicker.parseDate(instance.settings.dateFormat || $.datepicker._defaults.dateFormat, selectedDate, instance.settings);
	      dates_sol.not(this).datepicker("option", option_sol, date_sol);
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
	$("#fecha_desde").datepicker("option", "dateFormat", "yy-mm-dd");
	$("#fecha_hasta").datepicker("option", "dateFormat", "yy-mm-dd");
	$("#desde_sol").datepicker("option", "dateFormat", "yy-mm-dd");
	$("#hasta_sol").datepicker("option", "dateFormat", "yy-mm-dd");
	$("#fecha_desde_sp").datepicker("option", "dateFormat", "yy-mm-dd");
	$("#fecha_hasta_sp").datepicker("option", "dateFormat", "yy-mm-dd");
	$("#fecha_desde_cuadre").datepicker("option", "dateFormat", "yy-mm-dd");
	$("#fecha_hasta_cuadre").datepicker("option", "dateFormat", "yy-mm-dd");
	$("#desde_contado").datepicker("option", "dateFormat", "yy-mm-dd");
	$("#hasta_contado").datepicker("option", "dateFormat", "yy-mm-dd");
	$("#desde_fpresu").datepicker("option", "dateFormat", "yy-mm-dd");
	$("#hasta_fpresu").datepicker("option", "dateFormat", "yy-mm-dd");
	$("#desde_fcontrol").datepicker("option", "dateFormat", "yy-mm-dd");
	$("#hasta_fcontrol").datepicker("option", "dateFormat", "yy-mm-dd");
	$("#desde_entregas").datepicker("option", "dateFormat", "yy-mm-dd");
	$("#hasta_entregas").datepicker("option", "dateFormat", "yy-mm-dd");
	$("#desde_ccargasv").datepicker("option", "dateFormat", "yy-mm-dd");
	$("#desde_ccargasd").datepicker("option", "dateFormat", "yy-mm-dd");
	$("#hasta_ccargasv").datepicker("option", "dateFormat", "yy-mm-dd");
	$("#hasta_ccargasd").datepicker("option", "dateFormat", "yy-mm-dd");
	
	
  $("#fecha_desde_controlmensual").datepicker("option", "dateFormat", "yy-mm-dd");
  $("#fecha_hasta_controlmensual").datepicker("option", "dateFormat", "yy-mm-dd");	

	$(".dialogo").dialog({
		modal : true,
		autoOpen : false,
		position : 'top',
		hide : 'explode',
		show : 'slide',
		width : 600,
		height : 340
	});
	$("#msj_alertas").dialog({
		modal : true,
		autoOpen : false,
		position : 'top',
		hide : 'explode',
		show : 'slide',
		width : 600,
		height : 400,
		buttons : {
			"Cerrar" : function() {
				$(this).html('');
				$(this).dialog("close");
			}
		}
	});
	$("#facturas_pendientes").dialog({
		buttons : {
			"Generar" : function() {
				CFactura();
				$(this).dialog("close");
			},
			"Cerrar" : function() {
				$(this).dialog("close");
			}
		}
	});
	$("#muestra_historial_coversaciones").dialog({
		buttons : {
			"Consultar" : function() {
				HConversaciones();
				$(this).dialog("close");
			},
			"Cerrar" : function() {
				$(this).dialog("close");
			}
		}
	});
	$("#muestra_cuadre").dialog({
		buttons : {
			"Generar" : function() {
				GCuadre();
				$(this).dialog("close");
			},
			"Cerrar" : function() {
				$(this).dialog("close");
			}
		}
	});
	$("#busca_contrato").dialog({
		buttons : {
			"Generar" : function() {
				BContrato();
				$(this).dialog("close");
			},
			"Cerrar" : function() {
				$(this).dialog("close");
			}
		}
	});
	$("#muestra_cheque").dialog({
		buttons : {
			"Generar" : function() {
				Listar_cheques();
				$(this).dialog("close");
			},
			"Cerrar" : function() {
				$(this).dialog("close");
			}
		}
	});
	$("#voucher").dialog({
		buttons : {
			"Generar" : function() {
				Listar_Voucher();
				$(this).dialog("close");
			},
			"Cerrar" : function() {
				$(this).dialog("close");
			}
		}
	});

	$("#depositos_pendientes").dialog({
		buttons : {
			"Generar" : function() {
				Listar_Depositos();
				$(this).dialog("close");
			},
			"Cerrar" : function() {
				$(this).dialog("close");
			}
		}
	});
	$("#usuarios_contratos").dialog({
		buttons : {
			"Generar" : function() {
				Listar_Usuarios_Contratos();
				$(this).dialog("close");
			},
			"Cerrar" : function() {
				$(this).dialog("close");
			}
		}
	});

	$("#rep_sinpago").dialog({
		buttons : {
			"Generar" : function() {
				Pagos_Factura();
				$(this).dialog("close");
			},
			"Cerrar" : function() {
				$(this).dialog("close");
			}
		}
	});
	$("#rep_inven").dialog({
		buttons : {
			"Generar" : function() {
				Listar_Inventario();
				$(this).dialog("close");
			},
			"Cerrar" : function() {
				$(this).dialog("close");
			}
		}
	});
	$("#rep_contado").dialog({
		buttons : {
			"Generar" : function() {
				Listar_Contado();
				$(this).dialog("close");
			},
			"Cerrar" : function() {
				$(this).dialog("close");
			}
		}
	});
	
	$("#rep_fpresu").dialog({
		buttons : {
			"Generar" : function() {
				Listar_Fpresu();
				$(this).dialog("close");
			},
			"Cerrar" : function() {
				$(this).dialog("close");
			}
		}
	});

	$("#rep_fcontrol").dialog({
		buttons : {
			"Generar" : function() {
				Listar_Fcontrol();
				$(this).dialog("close");
			},
			"Cerrar" : function() {
				$(this).dialog("close");
			}
		}
	});
	
	$("#rep_ncliente").dialog({
		buttons : {
			"Generar" : function() {
				Listar_Ncliente();
				$(this).dialog("close");
			},
			"Cerrar" : function() {
				$(this).dialog("close");
			}
		}
	});
	
	$("#filtro").dialog({
		buttons : {
			"Generar" : function() {
				buscar_sol();
				$(this).dialog("close");
			},
			"Cerrar" : function() {
				$(this).dialog("close");
			}
		}
	});
	
	$("#rep_entregas").dialog({
		buttons : {
			"Generar" : function() {
				Listar_Entregas();
				$(this).dialog("close");
			},
			"Cerrar" : function() {
				$(this).dialog("close");
			}
		}
	});
	
	$("#ccargas_domi").dialog({
		buttons : {
			"Generar" : function() {
				Listar_Pagos_Domi();
				$(this).dialog("close");
			},
			"Cerrar" : function() {
				$(this).dialog("close");
			}
		}
	});
	
	$("#ccargas_vou").dialog({
		buttons : {
			"Generar" : function() {
				Listar_Pagos_Vou();
				$(this).dialog("close");
			},
			"Cerrar" : function() {
				$(this).dialog("close");
			}
		}
	});
	
	$("#control_vendedores").dialog({
		buttons : {
			"Generar" : function() {
				Listar_Vendedores();
				$(this).dialog("close");
			},
			"Cerrar" : function() {
				$(this).dialog("close");
			}
		}
	});
	
	
	
	$("#ControlMensual").dialog({
    buttons : {
      "Generar" : function() {
        GControlMensual();
        $(this).dialog("close");
      },
      "Cerrar" : function() {
        $(this).dialog("close");
      }
    }
  });
	 
});
function muestra_div(elemento) {
	$("#" + elemento).dialog('open');
}

function Filtro(){
	strUrl_Proceso = sUrlP + "GV_FSoli";
	vis2 = new GVista(strUrl_Proceso, 'filtro', 'Filtro de Solicitud','');
	vis2.Obtener_Json();
    vis2.AsignarCeldas(2);
    vis2.AsignarBotones(1);
    vis2.Generar();
}

//Listar Linajes
function Listar() {
	$("#txtCobrado").find('option').remove().end();
	$("#txtCobradoC").find('option').remove().end();
	$("#txtCobradoCuadre").find('option').remove().end();
	$("#txtDependencia").find('option').remove().end();
	$("#txtDependenciaDep").find('option').remove().end();
	$("#txtDependencia_sp").find('option').remove().end();
	$("#txtDependencia_inv").find('option').remove().end();
	$("#txtUbica_Ncli").find('option').remove().end();
	$.ajax({
		url : sUrlP + "Listar",
		dataType : "json",
		success : function(data) {
			$.each(data['linaje'], function(item, valor) {
				$("#txtCobrado").append(new Option(valor['valor'], valor['id']));
				$("#txtCobradoC").append(new Option(valor['valor'], valor['id']));
				$("#txtCobradoCuadre").append(new Option(valor['valor'], valor['id']));
				$("#txtCobradocontrolmensual").append(new Option(valor['valor'], valor['id']));
			});
			$("#txtCobrado").append(new Option("TODOS", "TODOS"));
			$("#txtCobradoC").append(new Option("TODOS", "TODOS"));
			$("#txtCobradoCuadre").append(new Option("TODOS", "TODOS"));

			$.each(data['dependientes'], function(item, valor) {
				$("#txtDependencia").append(new Option(valor['valor'], valor['seudonimo']));
				$("#txtDependenciaDep").append(new Option(valor['valor'], valor['seudonimo']));
				$("#txtDependencia_sp").append(new Option(valor['valor'], valor['seudonimo']));
				$("#txtDependencia_inv").append(new Option(valor['valor'], valor['seudonimo']));
				$("#txtDependencia_contado").append(new Option(valor['valor'], valor['seudonimo']));
				$("#txtDependencia_fpresu").append(new Option(valor['valor'], valor['seudonimo']));
				$("#txtDependencia_fcontrol").append(new Option(valor['valor'], valor['seudonimo']));
				$("#txtDependencia_entregas").append(new Option(valor['valor'], valor['seudonimo']));
				$("#txtUsuario").append(new Option(valor['valor'], valor['seudonimo']));
				$("#txtUsuarioContrato").append(new Option(valor['valor'], valor['seudonimo']));
				$("#txtUbica_Ncli").append(new Option(valor['valor'], valor['seudonimo']));
				
			});
			$("#txtUsuario").append(new Option(data['valor'], data['seudonimo']));
			$("#txtDependencia").append(new Option("TODOS", "TODOS"));
			$("#txtDependenciaDep").append(new Option("TODOS", "TODOS"));
			$("#txtDependencia_inv").append(new Option("TODOS", "TODOS"));
			$("#txtDependencia_fpresu").append(new Option("TODOS", "TODOS"));
			$("#txtDependencia_fcontrol").append(new Option("TODOS", "TODOS"));
			$("#txtDependencia_entregas").append(new Option("TODOS", "TODOS"));
			$("#txtDependencia_contado").append(new Option("TODOS", "TODOS"));
			$("#txtDependencia_sp").append(new Option("TODOS", "TODOS"));
			$("#txtUbica_Ncli").append(new Option("TODOS", "TODOS"));
		}
	});
}

function Listar_Alvaro() {
	$("#txtCobradoDep").find('option').remove().end();
	$.ajax({
		url : sUrlP + "Listar_Alvaro",
		dataType : "json",
		success : function(data) {
			$("#txtLinaje_sp").append(new Option("TODOS", "TODOS"));
			$("#txtLinaje_sp").append(new Option("NOMINA", "NOMINA"));
			$.each(data['linaje'], function(item, valor) {
				$("#txtCobradoDep").append(new Option(valor, item));
				$("#txtLinaje_sp").append(new Option(valor, item));
			});

			$("#txtCobradoDep").append(new Option("TODOS", "TODOS"));
			$("#txtCobradoDep").append(new Option("NOMINA", "NOMINA"));
			
		}
	});
}

function CFactura() {
	var dependencia = $("#txtDependencia").val();
	var nomina_procedencia = $("#txtNominaProcedencia option:selected").val();
	var cobrado_en = $("#txtCobrado option:selected").text();
	var credito = $("#txtCreditos option:selected").val();
	var desde = $("#fecha_desde").val();
	var hasta = $("#fecha_hasta").val();
	var titulo = $("#txtCreditos option:selected").text() + " : " + $("#txtDependencia option:selected").text()+"<br>Linaje:"+cobrado_en+" | Desde:"+desde+" | Hasta:"+hasta;
	var sData = "desde=" + desde + "&hasta=" + hasta + "&dependencia=" + dependencia + "&nomina_procedencia=" + nomina_procedencia + "&credito=" + credito + "&cobrado_en=" + cobrado_en;
	$("#carga_busqueda").dialog('open');

	$.ajax({
		url : sUrlP + "TG_Cedula",
		type : "POST",
		data : sData,
		dataType : "json",
		success : function(oEsq) {
			//alert(oEsq.query);
			Grid = new TGrid(oEsq, 'Reportes', titulo);
			Grid.SetXls(true);
			Grid.SetNumeracion(true);
			Grid.SetName("Reportes");
			Grid.SetDetalle();
			Grid.Generar();
			$("#carga_busqueda").dialog('close');
		}
	});
}



function Listar_Vendedores() {
	$("#carga_busqueda").dialog('open');
	$.ajax({
		url : sUrlP + "Listar_Vendedores",
		type : "POST",
		data : "vend=" + $('#txtVendedor').val(),
		dataType : "json",
		success : function(oEsq) {
			Grid = new TGrid(oEsq, 'Reportes', 'Contratos por vendedores ');
			Grid.SetName("ReportesC");
			Grid.SetNumeracion(true);
			//Grid.SetXls(true);
			Grid.Generar();
			$("#carga_busqueda").dialog('close');
		}
	});
}


function HConversaciones() {
	$("#carga_busqueda").dialog('open');
	$.ajax({
		url : sUrlP + "HConversaciones",
		type : "POST",
		data : "usuario=" + $('#txtUsuario').val(),
		dataType : "json",
		success : function(oEsq) {
			Grid = new TGrid(oEsq, 'Reportes', 'Conversaciones Realizadas: ' + $('#txtUsuario option:selected').text());
			Grid.SetName("ReportesC");
			Grid.SetNumeracion(true);
			//Grid.SetXls(true);
			Grid.Generar();
			$("#carga_busqueda").dialog('close');
		}
	});
}

function BContrato() {

	var nomina = $("#txtNominaProcedenciaC option:selected").val();
	var cobrado_en = $("#txtCobradoC option:selected").text();
	var empresa = $("#txtEmpresa option:selected").val();
	var tipo = $("#txtFormaContrato option:selected").val();
	var tipoContrato = $("#txtTipoContrato option:selected").val();
	var dia = $("#txtDia option:selected").val();
	var Mes = $("#txtMes option:selected").val();
	var ano = $("#txtAno option:selected").val();
	var perio = $("#txtPeriocidad_Contratos option:selected").val();
	var titulo = 'EMPRESA: ' + $("#txtEmpresa option:selected").text() + '<br>NOMINA: ' + nomina + ' <br> BANCO: ' + cobrado_en + ' | <b>TIPO:</b> ' + $("#txtFormaContrato option:selected").text();
	$("#carga_busqueda").dialog('open');
	$.ajax({
		url : sUrlP + "ReporteBuscaContrato",
		type : "POST",
		data : "nomina=" + nomina + "&banco=" + cobrado_en + "&empresa=" + empresa + "&tipo=" + tipo + "&dia=" + dia + "&mes=" + Mes + "&ano=" + ano + "&tipoContrato=" + tipoContrato+"&perio="+perio,
		dataType : "json",
		success : function(oEsq) {
			//alert(oEsq);
			
			Grid = new TGrid(oEsq, 'Reportes', titulo);
			Grid.SetName("Contratos");
			Grid.SetNumeracion(true);
			Grid.SetXls(true);
			Grid.Generar();
			$("#carga_busqueda").dialog('close');
			
		}
	});
}

function GCuadre() {

	var nomina = $("#txtNominaProcedenciaCuadre option:selected").val();
	var cobrado_en = $("#txtCobradoCuadre option:selected").text();
	var empresa = $("#txtEmpresaCuadre option:selected").val();
	var tipo = $("#txtFormaContratoCuadre option:selected").val();
	var desde = $("#fecha_desde_cuadre").val();
	var hasta = $("#fecha_hasta_cuadre").val();
	if (desde == '' || hasta == '') {
		alert("Debe especificar desde que fecha y hasta que fecha desea hacer el cuadre...");
		return 0;
	}
	var titulo = 'EMPRESA: ' + $("#txtEmpresaCuadre option:selected").text() + '<br>NOMINA: ' + nomina + ' <br> BANCO: ' + cobrado_en + ' <br> DESDE: ' + desde + ' | <b>HASTA:</b> ' + hasta;
	$("#carga_busqueda").dialog('open');
	$.ajax({
		url : sUrlP + "CuadreCaja",
		type : "POST",
		data : "nomina=" + nomina + "&banco=" + cobrado_en + "&empresa=" + empresa + "&tipo=" + tipo + "&desde=" + desde + "&hasta=" + hasta,
		dataType : "json",
		success : function(oEsq) {
			Grid = new TGrid(oEsq, 'Reportes', titulo);
			Grid.SetName("Contratos");
			Grid.SetNumeracion(true);
			Grid.SetXls(true);
			Grid.Generar();
			$("#carga_busqueda").dialog('close');
		}
	});
}


function GControlMensual() {


 var cobrado_en = $("#txtCobradocontrolmensual option:selected").text();
  var empresa = $("#txtEmpresacontrolmensual option:selected").val();
  var forma = $("#txtFormacontrolmensual option:selected").val();
  var desde = $("#fecha_desde_controlmensual").val();
  var mesp = $("#mespcontrolmensual option:selected").val();
  var anop = $("#anopcontrolmensual option:selected").val();
  var perio = $("#diapcontrolmensual option:selected").val();
  if (desde == '' ) {
    alert("Debe especificar desde que fecha desea hacer el cuadre...");
    return 0;
  }
  var titulo = 'EMPRESA: ' + $("#txtEmpresacontrolmensual option:selected").text()  
  + ' <br> BANCO: ' + cobrado_en + ' <br> FECHA DEL ARCHIVO: ' + desde  + ' <br> PARA EL MES: ' 
  +  $("#mespcontrolmensual option:selected").text()
  + ' <br> LEYENDA : AZUL (MODIFICACION), ROJO (CANCELO EL CONTRATO), VERDE (PAGO CUOTA MES)';
  $("#carga_busqueda").dialog('open');
  $.ajax({
    url : sUrlP + "GenerarControlPagos",
    type : "POST",
    data : "banco=" + cobrado_en + "&empresa=" + empresa + "&forma=" + forma + "&desde=" + desde + "&mesp=" + mesp  + "&anop=" + anop + "&perio="+perio,
    dataType : "json",
    success : function(oEsq) {
      //$("#msj_alertas").html(oEsq);
      //$("#msj_alertas").dialog('open');
      
      Grid = new TGrid(oEsq, 'Reportes', titulo);
      Grid.SetName("ControlMensual");
      Grid.SetNumeracion(true);
      Grid.SetXls(true);
      Grid.Generar();
      $("#carga_busqueda").dialog('close');
     
    }
  });
}


function Cargar_Numeros_Cuenta() {
	$("#lstNBanco").find('option').remove().end();
	valor = $("#lstBanco").val();
	$.ajax({
		url : sUrlP + "Cargar_Numeros_Cuenta",
		type : "POST",
		data : "valor=" + valor,
		dataType : "json",
		success : function(data) {
			$.each(data, function(item, valor) {
				$("#lstNBanco").append(new Option(valor['valor'], valor['valor']));
			});

		}
	});

}

function Listar_cheques() {
	var banco = $("#lstBanco option:selected").val();
	var numero = $("#lstNBanco option:selected").val();
	var ubicacion = $("#lstUbicacion option:selected").val();
	var estatus = $("#lstEstatus option:selected").val();
	var nchequera = $("#txtnchequera option:selected").val();

	var titulo = '';
	$("#carga_busqueda").dialog('open');
	$.ajax({
		url : sUrlP + "Listar_Cheques",
		type : "POST",
		data : "banco=" + banco + "&numero=" + numero + "&ubicacion=" + ubicacion + "&estatus=" + estatus + "&nchequera=" + nchequera,
		dataType : "json",
		success : function(oEsq) {
			if (oEsq == null) {
				alert("No se encontraron cheques para la busqueda seleccionada");
			} else {
				Grid = new TGrid(oEsq, 'Reportes', titulo);
				Grid.SetName("Cheques");
				Grid.SetNumeracion(true);
				Grid.SetXls(true);
				Grid.Generar();
			}

			$("#carga_busqueda").dialog('close');
		}
	});
}

function Listar_Voucher() {
	var moda = $("#lstModa option:selected").val();
	var estatus = $("#lstEstatus_v option:selected").val();
	var estatus_t = $("#lstEstatus_v option:selected").text();
	var banco = $("#lstBanco_v option:selected").val();
	var banco_t = $("#lstBanco_v option:selected").text();
	var desde = $("#fecha_desde_v").val();
	var hasta = $("#fecha_hasta_v").val();
	var titulo = $("#lstModa option:selected").text() + " | " + estatus_t + '| Banco ' + banco_t;

	$("#carga_busqueda").dialog('open');
	$.ajax({
		url : sUrlP + "Listar_Voucher",
		type : "POST",
		data : "estatus=" + estatus + "&desde=" + desde + "&hasta=" + hasta + "&banco=" + banco + "&moda=" + moda,
		dataType : "json",
		success : function(oEsq) {
			if (oEsq.msj == "SI") {
				Grid = new TGrid(oEsq, 'Reportes', titulo);
				Grid.SetName("Voucher");
				Grid.SetNumeracion(true);
				Grid.SetXls(true);
				Grid.Generar();
				$("#carga_busqueda").dialog('close');
			} else {
				$("#carga_busqueda").dialog('close');
				alert("No se encontraton resgistros....");
			}

		}
	});
}

function Listar_Usuarios_Contratos() {
	var usuario = $("#txtUsuarioContrato option:selected").val();
	var estatus = $("#txtUsuarioEstatus option:selected").val();
	var desde = $("#fecha_desde_cu").val();
	var hasta = $("#fecha_hasta_cu").val();

	var titulo = 'Usuario(s) ' + usuario;
	$("#carga_busqueda").dialog('open');
	$.ajax({
		url : sUrlP + "Listar_Contratos_Usuarios",
		type : "POST",
		data : "usuario=" + usuario + "&desde=" + desde + "&hasta=" + hasta + "&estatus=" + estatus,
		dataType : "json",
		success : function(oEsq) {//alert(oEsq);
			Grid = new TGrid(oEsq, 'Reportes', titulo);
			Grid.SetName("usuariocontratos");
			Grid.SetNumeracion(true);
			Grid.SetXls(true);
			Grid.Generar();
			$("#carga_busqueda").dialog('close');
		}
	});
}

function Ver_Cheque(cedula, factura) {
	strUrl_Proceso = sUrlP + "Busca_Img_Cheque";
	var sImg = sUrl + '/system/img/';
	var sImgC = sUrl + '/system/repository/expedientes/';
	$("#msj_alertas").html('');
	msj = '';
	if (cedula != '') {
		$.ajax({
			url : strUrl_Proceso,
			type : 'POST',
			data : 'id=' + cedula + '&factura=' + factura,
			dataType : 'json',
			success : function(json) {
				if (json['imgCheque'] != '' && json['imgCheque'] != undefined) {
					msj = '<h2>LISTA DE CHEQUES CARGADOS AL SISTEMA</h2><br><br><div class="imageRow">';
					$.each(json['imgCheque'], function(k, v) {
						msj += '<div class="single"><a href="' + sImgC + 'bancos/' + v + '" rel="lightbox[roadtrip]" title="CHEQUES"><img src="' + sImgC + 'bancos/' + v + '" alt="BANCO" style="width:150px;height:150px;" /></a></div>';

					});
					msj += '</div>';
					$("#msj_alertas").html(msj);
					$("#msj_alertas").dialog('open');
					//alert(lightbox);
				} else {
					alert('NO SE ENCONTRO REGISTROS ASOCIADOS A LA CEDULA...');
				}
			},
			error : function(err) {
				alert("NO SE PUDO ENCONTRAR LA CEDULA" + err);
			}
		});
	} else {
		alert('DEBE INGRESAR UNA CEDULA');
	}

}

function Grafica_Porcentaje(usu, cant, mod) {
	$("#torta").html("");
	$("#graf").show();
	buenos = parseFloat(cant) - parseFloat(mod);

	plot2 = jQuery.jqplot('torta', [[['BUENOS', parseFloat(buenos)], ['MODIFICADOS', parseFloat(mod)]]], {
		title : '<h2>' + usu + '</h2>',
		seriesDefaults : {
			shadow : false,
			renderer : jQuery.jqplot.PieRenderer,
			rendererOptions : {
				startAngle : 180,
				sliceMargin : 4,
				showDataLabels : true
			}
		},
		legend : {
			show : true,
			location : 's',
			rendererOptions : {
				numberRows : 1
			}
		}
	});
}

function Listar_Depositos() {
	banco = $("#txtCobradoDep option:selected").text();
	ubica = $("#txtDependenciaDep option:selected").text();

	$("#carga_busqueda").dialog('open');
	$.ajax({
		url : sUrlP + "PendientesDepositoImprimir",
		type : "POST",
		data : "banco=" + banco + "&ubicacion=" + ubica,
		dataType : "json",
		success : function(oEsq) {
			Grid = new TGrid(oEsq, 'Reportes', "Depositos Pendientes");
			Grid.SetName("deppen");
			Grid.SetNumeracion(true);
			Grid.SetXls(true);
			Grid.Generar();
			$("#carga_busqueda").dialog('close');
		}
	});

}

function Listar_Inventario() {
	var ubica = $("#txtDependencia_inv option:selected").text();
	var estatus = $("#esta_inven option:selected").val();

	$("#carga_busqueda").dialog('open');
	$.ajax({
		url : sUrlP + "ListarGrid_Inventario",
		type : "POST",
		data : "estatus=" + estatus + "&ubicacion=" + ubica,
		dataType : "json",
		success : function(oEsq) {
			if(oEsq.msj == 'si'){
				Grid = new TGrid(oEsq, 'Reportes', "Inventario");
				Grid.SetName("inven");
				Grid.SetNumeracion(true);
				Grid.SetXls(true);
				Grid.Generar();
				$("#carga_busqueda").dialog('close');	
			}else{
				$("#carga_busqueda").dialog('close');
				alert('No se encontraron productos para la busqueda solicitada');
			}
			
		}
	});

}

function Pagos_Factura() {
	var tipo = $("#SPEsta option:selected").val();
	var ubica = $("#txtDependencia_sp option:selected").text();
	//var fecha_d = $("#fecha_desde_sp").val();
	//var fecha_h = $("#fecha_hasta_sp").val();
	var ano = $("#txtAno_sp option:selected").val();
	var mes = $("#txtMes_sp option:selected").val();
	var linaje = $("#txtLinaje_sp option:selected").text();
	if(tipo == '' || ano == '' || mes == '' || linaje == '') {
		alert("Debe Ingresar Todos los datos");
	}else {
		$("#Reportes").html('');
		$("#carga_busqueda").dialog('open');
		$.ajax({
			url : sUrlP + "Pagos_Factura",
			type : "POST",
			data : "tipo=" + tipo + "&ubicacion=" + ubica + "&ano=" + ano + "&mes=" + mes + "&linaje=" + linaje,
			dataType : "json",
			success : function(oEsq) {
				$("#carga_busqueda").dialog('close');
				//alert(oEsq);
				if (oEsq.msj == 1) {
					Grid = new TGrid(oEsq, 'Reportes', "Pagos Facturas");
					Grid.SetName("pfact");
					Grid.SetNumeracion(true);
					Grid.SetXls(true);
					Grid.Generar();
				} else {
					alert("No se encontraton registros");
				}

			}
		});
	}

}

function Listar_Contado() {
	
	var ubica = $("#txtDependencia_contado option:selected").text();
	var fecha_d = $("#desde_contado").val();
	var fecha_h = $("#hasta_contado").val();
	if(fecha_d == '' || fecha_h == '') {
		alert("Debe Ingresar Todos los datos");
	}else {
		$("#Reportes").html('');
		$("#carga_busqueda").dialog('open');
		$.ajax({
			url : sUrlP + "Listar_Contado",
			type : "POST",
			data : "ubicacion=" + ubica + "&desde=" + fecha_d + "&hasta=" + fecha_h,
			dataType : "json",
			success : function(oEsq) {
				$("#carga_busqueda").dialog('close');
				//alert(oEsq);
				if (oEsq.msj == 1) {
					Grid = new TGrid(oEsq, 'Reportes', "Ventas De Contado");
					Grid.SetName("contado");
					Grid.SetNumeracion(true);
					Grid.SetXls(true);
					Grid.Generar();
				} else {
					alert("No se encontraton registros");
				}

			}
		});
	}

}

function Listar_Fpresu() {
	
	var ubica = $("#txtDependencia_fpresu option:selected").text();
	var fecha_d = $("#desde_fpresu").val();
	var fecha_h = $("#hasta_fpresu").val();
	if(fecha_d == '' || fecha_h == '') {
		alert("Debe Ingresar Todos los datos");
	}else {
		$("#Reportes").html('');
		$("#carga_busqueda").dialog('open');
		$.ajax({
			url : sUrlP + "Listar_FPresupuesto",
			type : "POST",
			data : "ubicacion=" + ubica + "&desde=" + fecha_d + "&hasta=" + fecha_h,
			dataType : "json",
			success : function(oEsq) {
				$("#carga_busqueda").dialog('close');
				alert('PAUSE...');
				if (oEsq.msj == 1) {
					Grid = new TGrid(oEsq, 'Reportes', "Facturas Presupuesto");
					Grid.SetName("fpresu");
					Grid.SetNumeracion(true);
					Grid.SetXls(true);
					Grid.Generar();
				} else {
					alert("No se encontraton registros");
				}

			}
		});
	}

}

function Listar_Fcontrol() {

	var ubica = $("#txtDependencia_fcontrol option:selected").text();
	var fecha_d = $("#desde_fcontrol").val();
	var fecha_h = $("#hasta_fcontrol").val();
	if(fecha_d == '' || fecha_h == '') {
		alert("Debe Ingresar Todos los datos");
	}else {
		$("#Reportes").html('');
		$("#carga_busqueda").dialog('open');
		$.ajax({
			url : sUrlP + "Listar_Fcontrol",
			type : "POST",
			data : "ubicacion=" + ubica + "&desde=" + fecha_d + "&hasta=" + fecha_h,
			dataType : "json",
			success : function(oEsq) {
				$("#carga_busqueda").dialog('close');
				//alert(oEsq);
				if (oEsq.msj == 1) {
					Grid = new TGrid(oEsq, 'Reportes', "Facturas Control");
					Grid.SetName("fcontrol");
					Grid.SetNumeracion(true);
					Grid.SetXls(true);
					Grid.Generar();
				} else {
					alert("No se encontraton registros");
				}

			}
		});
	}

}

function Listar_Ncliente(){
	var ubica = $("#txtUbica_Ncli option:selected").text();
	var ano = $("#txtAno_Ncli").val();
	var mes = $("#txtMes_Ncli").val();
	$.ajax({
		url : sUrlP + "Listar_Ncliente",
		type : "POST",
		data : "ubica=" + ubica + "&ano=" + ano + "&mes=" + mes,
		dataType : "json",
		success : function(oEsq) {
			$("#carga_busqueda").dialog('close');
			if (oEsq.msj == 1) {
				Grid = new TGrid(oEsq, 'Reportes', "Nuevos Clientes");
				Grid.SetName("ncli");
				Grid.SetNumeracion(true);
				Grid.SetXls(true);
				Grid.Generar();
			} else {
				alert("No se encontraton registros");
			}

		}
	});
}

function buscar_sol(){
	var ruta2 = sUrlP + "Listar_Soli_Ubi";
	var desde = $("#desde_sol").val();
	var hasta = $("#hasta_sol").val();
	var estatus = $("#estatus_sol option:selected").val();
	if(desde == '' || hasta == ''){
		alert("Debe ingresar fecha desde hasta");
		return 0;
	}
	var datos = "banco="+$("#fbanco option:selected").val() + "&nomina=" +$("#fnomina option:selected").val() +"&desde="+desde+"&hasta="+hasta+"&estatus="+estatus; 
	$("#reporte").html('');
	$.ajax({
		url : ruta2,
		type : 'POST',
		data : datos,
		dataType : 'json',
		success : function(json) {//alert(json);
			//alert(json['sql']);
			if(json['resp']==1){
				Grid = new TGrid(json, 'Reportes', 'Lista de Solicitud');
				Grid.SetXls(true);
				Grid.SetNumeracion(true);
				Grid.SetName("Reportes");
				Grid.SetDetalle();
				Grid.Generar();
			}else{
				alert("la busqueda no dio resultados..");
			}
		}
	});
	return false;
}

/*
 * lista Entregas a clientes
 */
function Listar_Entregas() {
	
	var ubica = $("#txtDependencia_entregas option:selected").text();
	var fecha_d = $("#desde_entregas").val();
	var fecha_h = $("#hasta_entregas").val();
	
	$("#Reportes").html('');
	$("#carga_busqueda").dialog('open');
	
	$.ajax({
		url : sUrlP + "Listar_Entregas2",
		type : "POST",
		data : "ubicacion=" + ubica + "&desde=" + fecha_d + "&hasta=" + fecha_h,
		dataType : "json",
		success : function(oEsq) {//alert(oEsq);
			$("#carga_busqueda").dialog('close');
			if (oEsq.msj == 1) {
				Grid = new TGrid(oEsq, 'Reportes', "Constancias de Entregas");
				Grid.SetName("entregas");
				Grid.SetNumeracion(true);
				Grid.SetXls(true);
				Grid.Generar();
			} else {
				alert("No se encontraton registros");
			}
		}
	});
}

/*
 * Listar Carga de cuotas por domiciliacion
 */
function Listar_Pagos_Domi () {
  var desde = $("#desde_ccargasd").val();
  var hasta = $("#hasta_ccargasd").val();
  if(desde == '' || hasta == ''){
  	alert("DEBE INGRESAR LAS FECHAS PARA LA CONSULTA");
  	return false;
  }
  $("#carga_busqueda").dialog('open');
  $.ajax({
		url : sUrlP + "ccargas_domi",
		type : "POST",
		data : "desde=" + desde + "&hasta=" + hasta,
		dataType : "json",
		success : function(oEsq) {//alert(oEsq);
			
			if (oEsq.msj == 1) {
				Grid = new TGrid(oEsq, 'Reportes', "Cuotas Cargadas");
				Grid.SetName("ccargadasd");
				Grid.SetNumeracion(true);
				Grid.SetXls(true);
				Grid.Generar();
			} else {
				alert("No se encontraton registros");
			}
			$("#carga_busqueda").dialog('close');
		}
	});
  
}

/*
 * Listar Carga de cuotas por domiciliacion
 */
function Listar_Pagos_Vou () {
  var desde = $("#desde_ccargasv").val();
  var hasta = $("#hasta_ccargasv").val();
  if(desde == '' || hasta == ''){
  	alert("DEBE INGRESAR LAS FECHAS PARA LA CONSULTA");
  	return false;
  }
  $("#carga_busqueda").dialog('open');
  $.ajax({
		url : sUrlP + "ccargas_vou",
		type : "POST",
		data : "desde=" + desde + "&hasta=" + hasta,
		dataType : "json",
		success : function(oEsq) {//alert(oEsq);
			
			if (oEsq.msj == 1) {
				Grid = new TGrid(oEsq, 'Reportes', "Voucher Pagados");
				Grid.SetName("ccargadasv");
				Grid.SetNumeracion(true);
				Grid.SetXls(true);
				Grid.Generar();
			} else {
				alert("No se encontraton registros");
			}
			$("#carga_busqueda").dialog('close');
		}
	});
  
}