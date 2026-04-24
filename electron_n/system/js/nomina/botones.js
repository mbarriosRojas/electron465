var strTiempo = 0;
var strUrl_Proceso = 0;
var iListaSerial = 0;
/**
 * Lamadas de Impresion Todos Los Documentos en (*.PDF)
 *
 * @param strUrl
 * @return true
 *
 */

















/**
 * Generar Reportes Del Sistema por Pantallas
 * @param string Ruta de la web...
 */
function Reportes(strUrl) {
	strUrl_Proceso = strUrl + "/index.php/cooperativa/DataSource_Reportes";

	var Dependencia = Dependencia_Valor(document.getElementById("txtDependencia").value);
	var nomina_procedencia = document.getElementById("txtNominaProcedencia").value;
	var cobrado_en = document.getElementById("txtCobrado").value;
	var credito = document.getElementById("txtCreditos").value;
	var desde = $("#fecha_desde").val();
	var hasta = $("#fecha_hasta").val();

	$("#divReportesMsg").show("Fade");
	document.getElementById("divReportes").innerHTML = "<br><br><br><p><center>Cargando por favor espere un momento<br><img src='" + strUrl + "/system/img/cargando.gif'></center></p>";

	new Ajax.Request(strUrl_Proceso, {
		method : "post",
		postBody : "desde="+ desde + "&hasta="+ hasta + 
		"&dependencia=" + Dependencia + 
		"&nomina_procedencia=" + nomina_procedencia + 
		"&credito=" + credito + 
		"&cobrado_en=" + cobrado_en,
		onSuccess : function(transport) {
			document.getElementById("divReportesMsgInformacion").innerHTML = "<span class=\"ui-icon ui-icon-info\" style=\"float: left; margin-right: .3em;\"></span>" + "<h3><strong>Responsable : " + Dependencia + "... </strong><h3>";
			document.getElementById("divReportes").innerHTML = transport.responseText;
		},
		onFailure : function(transport) {
			document.getElementById("divReportes").innerHTML = transport.responseText;
		}
	});
}

function Eliminar_Contrato(strUrl, codigo_contrato) {
	strUrl_Proceso = strUrl + "/index.php/cooperativa/Eliminar_Contrato";

	var Dependencia = Dependencia_Valor(document.getElementById("txtDependencia").value);

	$("divReportesMsg").show("blind");
	new Ajax.Request(strUrl_Proceso, {
		method : "post",
		postBody : "contrato=" + codigo_contrato + "&dependencia=" + Dependencia,
		onSuccess : function(transport) {
			document.getElementById("divReportesMsgInformacion").innerHTML = "<span class=\"ui-icon ui-icon-info\" style=\"float: left; margin-right: .3em;\">" + "</span>" + "<h3><strong>Se elimino el contrato : " + codigo_contrato + "... </strong><h3>";
			document.getElementById("divReportes").innerHTML = transport.responseText;
		},
		onFailure : function(transport) {
			document.getElementById("divReportes").innerHTML = transport.responseText;
		}
	});
}

function Eliminar_Reporte_Factura(strUrl, codigo_contrato) {
	strUrl_Proceso = strUrl + "/index.php/cooperativa/Eliminar_Reporte_Factura";

	var Dependencia = Dependencia_Valor(document.getElementById("txtDependencia").value);

	$("divReportesMsg").show("blind");
	new Ajax.Request(strUrl_Proceso, {
		method : "post",
		postBody : "contrato=" + codigo_contrato + "&dependencia=" + Dependencia,
		onSuccess : function(transport) {
			document.getElementById("divReportesMsgInformacion").innerHTML = "<span class=\"ui-icon ui-icon-info\" style=\"float: left; margin-right: .3em;\">" + "</span>" + "<h3><strong>Se elimino el contrato : " + codigo_contrato + "... </strong><h3>";
			document.getElementById("divReportes").innerHTML = transport.responseText;
		},
		onFailure : function(transport) {
			document.getElementById("divReportes").innerHTML = transport.responseText;
		}
	});
}

function Asociar_Cuentas_Guardar(strUrl) {
	var strUrl_Proceso = strUrl + "/index.php/cooperativa/Asociar_Cuentas_Guardar";
	var cedula = document.getElementById("txtDependencia").value;
	var banco = document.getElementById("txtBanco").value;
	var tipo = document.getElementById("txtTipoBanco").value;
	var cuenta = document.getElementById("txtCuentaBancaria").value;
	if(tipo != 2 && cuenta != "") {
		options = {
			percent : 100
		};
		$("#divConsultaId").show("blind");
		new Ajax.Request(strUrl_Proceso, {
			method : "post",
			postBody : "cedula=" + cedula + "&banco=" + banco + "&tipo=" + tipo + "&cuenta=" + cuenta,
			onSuccess : function(transport) {

				document.getElementById("divListarCuentas").innerHTML = transport.responseText;
				$("#divConsultaId").hide();
			},
			onFailure : function(transport) {
				document.getElementById("divListarCuentas").innerHTML = transport.responseText;
				$("#divConsultaId").hide();
			}
		});
	}

}

function Crear_Nomina(strUrl) {
	var strUrl_Proceso = strUrl + "/index.php/cooperativa/Inserta_Nomina";
	var nombre = document.getElementById("txtNombre").value;
	var desc = document.getElementById("txtDescrip").value;
	if(nombre != "") {
		new Ajax.Request(strUrl_Proceso, {
			method : "post",
			postBody : "&nombre=" + nombre + "&desc=" + desc,
			onSuccess : function(transport) {
				document.getElementById("txtDescrip").value = "";
				document.getElementById("txtNombre").value = "";
				alert("La Nomina se inserto con exito");
			},
			onFailure : function(transport) {
				alert("No se pudo insertar la Nomina");
			}
		});
	} else {
		document.getElementById("txtDescrip").value = "";
		alert("Debe ingresar un Nombre");
	}
}

function Crear_Zona(strUrl) {
	var strUrl_Proceso = strUrl + "/index.php/cooperativa/Inserta_Zona";
	var estado = $("#cmbEstados").val();
	var zona = $("#txtZona").val();
	var codigo = $("#txtCodigo").val();
	
	if(estado != "" && estado != "0" &&  zona != "" && codigo != "") {
		$.ajax({
			url : strUrl_Proceso,
			type : "POST",
			data : "estado=" + estado + "&zona=" + zona + "&codigo=" + codigo,
			success : function(html) {
				$("#cmbEstados").val("");
				$("#txtZona").val("");
				$("#txtCodigo").val("");
				alert(html);	
			}
		});
	} else {
		alert("Debe ingresar todos los datos...");
	}
}

function Asociar_Cuentas_Eliminar(strUrl, cedula) {
	var strUrl_Proceso = strUrl + "/index.php/cooperativa/Asociar_Cuentas_Eliminar";

	new Ajax.Request(strUrl_Proceso, {
		method : "post",
		postBody : "cedula=" + cedula,
		onSuccess : function(transport) {

			document.getElementById("divListarCuentas").innerHTML = transport.responseText;

		},
		onFailure : function(transport) {
			document.getElementById("divListarCuentas").innerHTML = transport.responseText;

		}
	});
}

function Modificar_Contratos(strUrl) {
	var strUrl_Proceso = strUrl + "/index.php/cooperativa/Modificar_Contratos";
	var contrato_a = document.getElementById("txtContrato_A").value;
	var contrato_n = document.getElementById("txtContrato_N").value;
	document.getElementById("txtContrato_A").value = "";
	document.getElementById("txtContrato_N").value = "";
	$("#divConsultaContrato").show("blind");

	new Ajax.Request(strUrl_Proceso, {
		method : "post",
		postBody : "contrato_a=" + contrato_a + "&contrato_n=" + contrato_n,
		onSuccess : function(transport) {
			$("#divConsultaContrato").hide();
			$("#divActualizarContrato").show("blind");
			document.getElementById("divActualizarContrato").innerHTML = transport.responseText;

		},
		onFailure : function(transport) {
			document.getElementById("divActualizarContrato").innerHTML = transport.responseText;

		}
	});
}

function Modificar_Facturas(strUrl) {
	var strUrl_Proceso = strUrl + "/index.php/cooperativa/Modificar_Facturas";
	var factura_a = document.getElementById("txtFactura_A").value;
	var factura_n = document.getElementById("txtFactura_N").value;
	document.getElementById("txtFactura_A").value = "";
	document.getElementById("txtFactura_N").value = "";
	$("#divConsultaFactura").show("blind");
	new Ajax.Request(strUrl_Proceso, {
		method : "post",
		postBody : "factura_a=" + factura_a + "&factura_n=" + factura_n,
		onSuccess : function(transport) {
			$("#divConsultaFactura").hide();
			$("#divActualizarFactura").show("blind");
			document.getElementById("divActualizarFactura").innerHTML = transport.responseText;

		},
		onFailure : function(transport) {
			document.getElementById("divActualizarFactura").innerHTML = transport.responseText;

		}
	});
}

function Modificar_Datos_Factura(strUrl) {
	var strUrl_Proceso = strUrl + "/index.php/cooperativa/Modificar_Datos_Factura";
	var factura = $("#txtNumero_Factura").val();
	var motivo = $("#txtMotivo").val();
	var condicion = $("#txtCondicion").val();
	var deposito = $("#txtDeposito").val();
	var monto = $("#txtMonto").val();
	var dia = $("#txtDiaO").val();
	var mes = $("#txtMesO").val();
	var ano = $("#txtAnoO").val();
	var fecha_o = ano + '-' + mes + '-' + dia; 
	$("#divConsultaFactura2").show("blind");
	$.ajax({
		url : strUrl_Proceso,
		type : "POST",
		data : "factura=" + factura + "&motivo=" + motivo + "&condicion=" + condicion + "&deposito=" + deposito + "&monto=" + monto + "&fecha_o=" + fecha_o,
		success : function(data) {
			
			$("#divConsultaFactura2").hide();
			$("#divActualizarFactura2").show("blind");
			$("#divActualizarFactura2").html(data);
			$("#txtNumero_Factura").val('');
			$("#txtCondicion").val('');
			$("#txtMotivo").val('');
			$("#txtMonto").val('');
			$("#txtDiaO").val('');
			$("#txtMesO").val('');
			$("#txtAnoO").val('');
			$("#txtDeposito").val('');
			
		}
	});
}

function Modificar_Cedula(strUrl) {
	var strUrl_Proceso = strUrl + "/index.php/cooperativa/Modificar_Cedula";
	var cedula_a = document.getElementById("txtCedula_A").value;
	var cedula_n = document.getElementById("txtCedula_N").value;
	document.getElementById("txtCedula_A").value = "";
	document.getElementById("txtCedula_N").value = "";
	$("#divConsultaCedula").show("blind");

	new Ajax.Request(strUrl_Proceso, {
		method : "post",
		postBody : "cedula_a=" + cedula_a + "&cedula_n=" + cedula_n,
		onSuccess : function(transport) {
			$("#divConsultaCedula").hide();
			$("#divActualizarCedula").show("blind");
			document.getElementById("divActualizarCedula").innerHTML = transport.responseText;

		},
		onFailure : function(transport) {
			document.getElementById("divActualizarCedula").innerHTML = transport.responseText;

		}
	});
}

function Modificar_Serial(strUrl) {
	var strUrl_Proceso = strUrl + "/index.php/cooperativa/Modificar_Serial";
	var serial_a = document.getElementById("txtSerial_A").value;
	var serial_n = document.getElementById("txtSerial_N").value;
	document.getElementById("txtSerial_A").value = "";
	document.getElementById("txtSerial_N").value = "";
	$("#divConsultaSerial").show("blind");

	new Ajax.Request(strUrl_Proceso, {
		method : "post",
		postBody : "serial_a=" + serial_a + "&serial_n=" + serial_n,
		onSuccess : function(transport) {
			$("#divConsultaSerial").hide();
			$("#divActualizarSerial").show("blind");
			document.getElementById("divActualizarSerial").innerHTML = transport.responseText;

		},
		onFailure : function(transport) {
			document.getElementById("divActualizarSerial").innerHTML = transport.responseText;

		}
	});
}

/**
 *
 * @param valor
 * @returns
 */

function Eliminar_Cedula(strUrl) {
	var strUrl_Proceso = strUrl + "/index.php/cooperativa/Eliminar_Cedula";
	var cedula = document.getElementById("txtCedula").value;
	document.getElementById("txtCedula").value = "";
	$("#divProcesarCedula").show("blind");

	new Ajax.Request(strUrl_Proceso, {
		method : "post",
		postBody : "cedula=" + cedula,
		onSuccess : function(transport) {
			$("#divProcesarCedula").hide();
			$("#divEliminarCedula").show("blind");
			document.getElementById("divEliminarCedula").innerHTML = transport.responseText;
		},
		onFailure : function(transport) {
			document.getElementById("divEliminarCedula").innerHTML = transport.responseText;
		}
	});
}



function Eliminar_Nomina(strUrl) {
	//alert("llega");
	var strUrl_Proceso = strUrl + "/index.php/cooperativa/Eliminar_Nomina";
	var nombre = document.getElementById("cmbNomina").value;
	$("#divProcesarNomina").show("blind");

	new Ajax.Request(strUrl_Proceso, {
		method : "post",
		postBody : "nombre=" + nombre,
		onSuccess : function(transport) {
			$("#divProcesarNomina").hide();
			$("#divEliminarNomina").show("blind");
			document.getElementById("divEliminarNomina").innerHTML = transport.responseText;
		},
		onFailure : function(transport) {
			document.getElementById("divEliminarNomina").innerHTML = transport.responseText;
		}
	});
}

function Eliminar_Contrato_C(strUrl) {
	var strUrl_Proceso = strUrl + "/index.php/cooperativa/Eliminar_Contrato_C";
	var contrato = document.getElementById("txtContrato").value;
	$("#divProcesarContrato").show("blind");

	new Ajax.Request(strUrl_Proceso, {
		method : "post",
		postBody : "contrato=" + contrato,
		onSuccess : function(transport) {
			$("#divProcesarContrato").hide();
			$("#divEliminarContrato").show("blind");
			$("#txtContrato").val('');
			document.getElementById("divEliminarContrato").innerHTML = transport.responseText;
			Construye_Tabla("divPrueba");
		},
		onFailure : function(transport) {
			document.getElementById("divEliminarContrato").innerHTML = transport.responseText;
		}
	});
}







function btnDAlta(strUrl) {

	var strUrl_Proceso = strUrl + "/index.php/cooperativa/DBaja";
	var cedula = document.getElementById("txtCedulaA").value;
	$("#divProcesarAlta").show("blind");
	document.getElementById("txtCedulaA").value = '';

	new Ajax.Request(strUrl_Proceso, {
		method : "post",
		postBody : "cedula=" + cedula + "&val=0",
		onSuccess : function(transport) {
			msg = '<p>&nbsp;&nbsp;&nbsp;&nbsp;Se ha dado de alta nuevamente el cliente...</strong></p>';
			$("#divProcesarAlta").hide();
			$("#divAltaCedula").show("blind");
			$('#divAltaCedula').html(msg);

		},
		onFailure : function(transport) {
			$("#divAltaCedula").html(transport.responseText);
		}
	});
}

function Dependencia_Valor(valor) {
	contenido = "";
	switch (eval(valor)) {
		case 0:
			contenido = "SANCRISTOBAL";
			break;
		case 1:
			contenido = "ELVIGIA";
			break;
		case 2:
			contenido = "SANTABARBARA";
			break;
		case 3:
			contenido = "cordoba";
			break;
		case 4:
			contenido = "MERIDA";
			break;
		case 5:
			contenido = "JOHANDER";
			break;
		case 6:
			contenido = "alvaro";
			break;
		case 7:
			contenido = "MERIDA2";
			break;
		case 9:
			contenido = "MERIDA3";
			break;
		case 10:
			contenido = "LASTEJAS";
			break;
		case 11:
			contenido = "barinas465";
			break;
		case 12:
			contenido = "maracaibo465";
			break;
		case 20:
			contenido = "TODOS";
			break;
		case 23:
			contenido = "ADRIANA";
			break;
		case 24:
			contenido = "DIRIAN";
			break;
		case 26:
			contenido = "anyi";
			break;
		case 27:
			contenido = "Marlin";
			break;
		case 29:
			contenido = "Jhon";
			break;
		case 30:
			contenido = "Auxiliar";
			break;
		case 32:
			contenido = "MARIBAL";
			break;
		case 33:
			contenido = "dharma";
			break;
		case 34:
			contenido = "nelson";
			break;
		case 36:
			contenido = "kary";
			break;
		case 37:
			contenido = "JELKA";
			break;
		case 41:
			contenido = "CAJASECA";
			break;

	}
	return contenido;
}




function BFactura_Modificar(strUrl) {
	var strUrl_Proceso = strUrl + "/index.php/cooperativa/BFactura_Modificar";
	var num_factura = $("#txtNumero_Factura").val();
	if(num_factura != '') {
		$.ajax({
		url : strUrl_Proceso,
		type : "POST",
		data : "factura=" + num_factura,
		success : function(data) {
			eval("var tipo = " + data);
			var motivo = tipo["motivo"];
			var condicion = tipo["condicion"];
			var monto = tipo["monto_operacion"];
			var deposito = tipo["num_operacion"];
			var fecha_operacion = tipo["fecha_operacion"];
			var fechaAuxO = fecha_operacion.split("-");
			var dia = fechaAuxO[2] * 1;
			var mes = fechaAuxO[1] * 1;
			var ano = fechaAuxO[0] * 1;
			document.getElementById("txtCondicion").value = condicion;
			document.getElementById("txtDeposito").value = deposito;
			document.getElementById("txtMonto").value = monto;
			document.getElementById("txtMotivo").value = motivo;
			document.getElementById("txtDiaO").value = dia;
			document.getElementById("txtMesO").value = mes;
			document.getElementById("txtAnoO").value = ano;
		}
		});
	}else{
		alert('DEBE INGRESAR NUMERO DE FACTURA');
	}
}




function Seleccion_Modelo(strUrl) {
	var strUrl_Proceso = strUrl + "index.php/cooperativa/SModelo";
	var valor = $("#txtModelo").val();
	alert(valor);
	// var cant = document.frmRegistrar.lstModelo.length + 1;
	// for( i = 0; i < cant; i++) {
		// document.frmRegistrar.lstModelo.options[i] = null;
	// }

	$.ajax({
		url : strUrl_Proceso,
		type : "POST",
		data : "valor=" + valor,
		dataType : "json",
		success : function(data) {
			i = 0;
			Modelos = document.frmRegistrar.lstModelo.options;
			$.each(data, function(item, valor) {
				//alert(valor);

				var Optiones_Modelos = new Option(valor, item);
				Modelos[i] = Optiones_Modelos;
				i++;
			});
		}
	});

	if(i == 0) {
		var Optiones_Modelos = new Option("NULL", "NULL");
		Modelos[1] = Optiones_Modelos;
	}
	document.frmRegistrar.lstModelo.options[0].selected = true;
}







function lst_Seriales() {
	var sSerial = document.frmRegistrar.txtSeriales.value;
	var tSerial = document.frmRegistrar.txtSeriales.options[document.frmRegistrar.txtSeriales.selectedIndex].text;
	var lst = document.frmRegistrar.lstSeriales.options;
	var Optiones_Seriales = new Option(tSerial, sSerial, "selected");
	lst[iListaSerial] = Optiones_Seriales;
	iListaSerial++;
	var pos = document.frmRegistrar.txtSeriales.selectedIndex;
	document.frmRegistrar.txtSeriales.options[pos] = null;

}

function Eli_lst_Seriales() {
	var sSerial = document.frmRegistrar.lstSeriales.value;
	var tSerial = document.frmRegistrar.lstSeriales.options[document.frmRegistrar.lstSeriales.selectedIndex].text;
	var lst = document.frmRegistrar.txtSeriales.options;
	var Optiones_Seriales = new Option(tSerial, sSerial, "selected");
	var pos = document.frmRegistrar.lstSeriales.selectedIndex;
	document.frmRegistrar.lstSeriales.options[pos] = null;
	iListaSerial--;
	Sel_lst_Seriales();
}

function Sel_lst_Seriales() {
	for( i = 0; i <= iListaSerial; i++) {
		document.frmRegistrar.lstSeriales.options[i].selected = true;
	}
}





function mes_del_ano(mes, ano) {
	di = 28
	f = new Date(ano, mes - 1, di);
	while(f.getMonth() == mes - 1) {
		di++;
		f = new Date(ano, mes - 1, di);
	}

	//tests

	//meses = new Date(ano || new Date().getFullYear(), mes, 0).getDate();

	//alert(meses);
	//alert(di-1);
	//return di-1;

	var fecha = new Date();
	var diames = fecha.getDate();
	var diasemana = fecha.getDay();
	var mes = fecha.getMonth() + 1;
	var ano = fecha.getFullYear();

	var textosemana = new Array(7);
	textosemana[0] = "Domingo";
	textosemana[1] = "Lunes";
	textosemana[2] = "Martes";
	textosemana[3] = "Miércoles";
	textosemana[4] = "Jueves";
	textosemana[5] = "Viernes";
	textosemana[6] = "Sábado";

	var textomes = new Array(12);
	textomes[1] = "Enero";
	textomes[2] = "Febrero";
	textomes[3] = "Marzo";
	textomes[4] = "Abril";
	textomes[5] = "Mayo";
	textomes[6] = "Junio";
	textomes[7] = "Julio";
	textomes[7] = "Agosto";
	textomes[9] = "Septiembre";
	textomes[10] = "Octubre";
	textomes[11] = "Noviembre";
	textomes[12] = "Diciembre";

	var msg;

}

function PInventarioAsociar(sUrl, sSerial) {
	var sUrlP = sUrl;
	var sCont = "";
	var factura = document.getElementById("txtnfactura").value;
	var iPos = 1;
	$.ajax({
		url : sUrlP,
		type : "POST",
		data : "serial=" + sSerial + "&factura=" + factura,
		success : function(html) {
			$("#divDetalles" + iPos).html(html);

		}
	});
}

function btnImprimirLetras(sUrl) {
	var sUrlP = sUrl + "/index.php/cooperativa/Giros/";
	var dFecha = '';

	if(document.getElementById("txtMontoCuota").value != '') {
		if(document.getElementById("txtDiaDescuento").value != 'Dia:') {
			dFecha = document.getElementById("txtDiaDescuento").value + '-' + document.getElementById("txtMesDescuento").value + '-' + document.getElementById("txtAnoDescuento").value;
			sUrlP += document.getElementById("txtCedula").value + '/' + document.getElementById("txtMontoCuota").value + '/' + document.getElementById("txtNumeroCuotas").value + '/' + dFecha;
			window.open(sUrlP, "Giros...");
		} else {
			alert("Debe seleccionar una fecha de inicio del descuento para generar letras.");
		}

	} else {
		alert("Debe seleccionar un credito para generar.");
	}
}



function btnSugerencia(strUrl) {
	var strUrl_Proceso = strUrl + "/index.php/cooperativa/Inserta_Sugerencia";
	var strTema = $("#txtTema").val();
	var strSugerencia = $("#txtSugerencia").val();
	var strPrioridad = $("#txtPrioridad").val();
	var strPara = $("#txtPara").val();
	$("#txtSugerencia").val("");
	if(strSugerencia != '') {
		$.ajax({
			url : strUrl_Proceso,
			type : "POST",
			data : "tema=" + strTema + "&sugerencia=" + strSugerencia + "&prioridad= " + strPrioridad + "&para=" + strPara,
			success : function(html) {
				
				$("#DivLista").html(html);
				//para cargar los estilo de la vista
				//$(function(){			
					$( "button, input:submit, a", ".demo, .agregar" ).button();	
					$( "a", ".demo" ).click(function() { return false;		});
					$("input").keyup(function() {
						var value = $(this).val();
						$(this).val(value.toUpperCase());
					});
					$("textarea").keyup(function() {
						var value = $(this).val();
						$(this).val(value.toUpperCase());
					});
				//});
			}
		});
	} else {
		alert('Debe ingresar Tema y Sugerencia....')
	}

}

function btnRespuesta(strUrl, id, indice,color) {
	var strUrl_Proceso = strUrl + "/index.php/cooperativa/Inserta_Respuesta";
	var strRespuesta = $("#txtRespuesta_" + indice).val();
	var strId = id;
	var colorF = color;
	var cant = $('#respuesta_' + indice + ' >tbody >tr').length;
	var nuevo = cant + 1;
	var auxHtml = "<tr id='fila_" + indice + "_" + nuevo + "'></tr>";
	if(strRespuesta != '') {
		$.ajax({
			url : strUrl_Proceso,
			type : "POST",
			data : "id=" + strId + "&respuesta=" + strRespuesta + "&color=" + colorF,
			success : function(html) {
				$("#txtRespuesta_" + indice).val("");
				//$("#DivLista").html(html);
				$("#respuesta_" + indice).append(auxHtml);
				$("#fila_" + indice + "_" + nuevo).html(html);
				$("#dialog_link" + indice).html("<ul><span class=\"ui-icon ui-icon-comment\"></ul>");
			}
		});

	} else {
		alert('Debe ingresar Una Respuesta....')
	}

}

function btnCierraTema(strUrl, id, indice) {
	var strUrl_Proceso = strUrl + "/index.php/cooperativa/Cierra_Tema";
	var strId = id;
	$.ajax({
		url : strUrl_Proceso,
		type : "POST",
		data : "id=" + strId,
		success : function(html) {
			$("#txtRespuesta_" + indice).hide();
			$("#btnRespuesta" + indice).hide();
			$("#btnCierraTema" + indice).hide();
			$("#btnEliminaTema" + indice).show();
			$("#dialog_link" + indice).html("<ul><span class=\"ui-icon ui-icon-locked\"></ul>");
		}
	});
}

function btnEliminaTema(strUrl, id, indice) {
	var strUrl_Proceso = strUrl + "/index.php/cooperativa/Elimina_Tema";
	var strId = id;
	$.ajax({
		url : strUrl_Proceso,
		type : "POST",
		data : "id=" + strId,
		success : function(html) {
			$("#DivLista").html(html);
			//para cargar los estilo de la vista
			//$(function(){			
				$( "button, input:submit, a", ".demo, .agregar" ).button();	
				$( "a", ".demo" ).click(function() { return false;		});
				$("input").keyup(function() {
					var value = $(this).val();
					$(this).val(value.toUpperCase());
				});
				$("textarea").keyup(function() {
					var value = $(this).val();
					$(this).val(value.toUpperCase());
				});
			//});
		}
	});
}

function verifica_banco(obj){
	var contenido=$("#"+obj.id).val();
	var caja ="";
	if(obj.id == "txtbanco_1"){
		caja = "txtcuenta_1";
	}
	if(obj.id == "txtbanco_2"){
		caja = "txtcuenta_2";
	}
	//alert($.trim(contenido));
	switch($.trim(contenido)){
		case "SOFITASA":
			$("#"+caja).val('0137');
			break;
		case "BICENTENARIO":
			$("#"+caja).val('0175');
			break;
		case "BOD":
			$("#"+caja).val('0116');
			break;
		case "PROVINCIAL":
			$("#"+caja).val('0108');
			break;
		case "VENEZUELA":
			$("#"+caja).val('0102');
			break;
		case "BANESCO":
			$("#"+caja).val('0134');
			break;
		case "INDUSTRIAL":
			$("#"+caja).val('0003');
			break;
		case "MERCANTIL":
			$("#"+caja).val('0105');
			break;
		case "FONDO COMUN":
			$("#"+caja).val('0151');
			break;
		case "DEL SUR":
			$("#"+caja).val('0157');
			break;
		case "CARONI":
			$("#"+caja).val('0128');
			break;
		case "CARIBE":
			$("#"+caja).val('0114');
			break;
		default:
			//alert("DEBE INGRESAR EL NUMERO DE CUENTA CORRESPIENTE....");
			break;
	}
}

function btnCargarListaVerificados(strUrl,estado){
	var strUrlAccion = strUrl+"/index.php/cooperativa/Tabla_Estados_Verificados/"+estado;
	//alert(strUrlAccion);
	 $( "#divMuestraListaVerificados" ).dialog( "destroy" );
	 $('#divMuestraListaVerificados').dialog({
				autoOpen: false,
				position: 'top',
				hide: 'explode',
				show: 'slide',
				width: 550,
				height: 400,
				modal: true,
				buttons: {				
					"Cerrar": function() {						
						$(this).dialog("close"); 
					}	
				}
			});
	
	$.ajax({
		url : strUrlAccion,
		type : "POST",
		dataType : "json",
		success : function(data) {
				oEsq = data;                		   
        _getTabla('divMuestraListaVerificadosInterno', oEsq);
				$('#divMuestraListaVerificados').dialog("open"); 
			}
	});
	
}

