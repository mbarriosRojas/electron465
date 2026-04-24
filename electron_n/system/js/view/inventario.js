/**
 * @author Carlos Peña
 *
 */
var iPosicion = 0;
$(function() {
	$("input:button").button();
	$("#Enviar").button({
		icons : {
			primary : 'ui-icon ui-icon-circle-check'
		}
	});
	$("#mbuzon").removeClass('active');
	$("#minventario").addClass('active');
	$("#el_serial").hide();
	$("#el_modelo").hide();
	//BModelo();
	/**
	 * Lista de Proveedores
	 */
	$("#txtProveedores").autocomplete({
		source : function(request, response) {
			var proveedores = $("#txtProveedores").val();
			$.ajax({
				type : "POST",
				url : sUrlP + "P_json/Proveedores",
				data : "nombre=" + $("#txtProveedores").val(),
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
	

	/**
	 * Lista de Marcas
	 */
	$("#txtMarca").autocomplete({
		source : function(request, response) {
			$.ajax({
				type : "POST",
				url : sUrlP + "M_json/inventario/marca",
				data : "nombre=" + $("#txtMarca").val(),
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
	//Fin de Marca
	/**
	 * Lista de Modelos
	 */
	$("#txtModelo").autocomplete({
		source : function(request, response) {
			$.ajax({
				type : "POST",
				url : sUrlP + "M_json/inventario/modelo",
				data : "nombre=" + $("#txtModelo").val(),
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
	//Fin de Modelo

	$("#fechaChequera").datepicker({
		showOn : "button",
		buttonImage : sImg + "calendar.gif",
		buttonImageOnly : true
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
	$("#fechaChequera").datepicker("option", "dateFormat", "yy-mm-dd");

	$('#r_inventario').dialog({
		modal : true,
		autoOpen : false,
		width : 450,
		height : 300,
	});
	$('#msj_alertas').dialog({
		modal : true,
		autoOpen : false,
		width : 250,
		height : 150,
	});

	$("#img_eliminar_serial").hide();
	$("#img_eliminar_modelo").hide();
	$("#img_mod_modelo").hide();
	$("#img_mod_art").hide();
});
function BModelo() {
	modelo = $("#txtModelo").val();
	surl2 = sUrlP + "BModelo";
	var ruta = sUrl + '/system/application/libraries/tcpdf/images/catalogo/';
	$.ajax({
		url : surl2,
		type : "POST",
		data : "modelo=" + modelo,
		dataType : "json",
		success : function(data) {
			$("#txtProveedores").val(data['proveedor']);
			//$("#txtEquipos").val(data['equipo']);
			$("#txtEquipos > option[value="+ data['equipo'] +"]").attr("selected","selected");
			$("#txtEquipos1").val(data['equipo']);
			$("#txtProveedores1").val(data['proveedor']);
			$("#txtEquipos1").val(data['equipo']);
			$("#txtMarca").val(data['marca']);
			$("#txtprecioc").val(data['compra']);
			$("#txtpreciov").val(data['venta']);
			$("#txtCrediCompra").val(data['credi_compra']);
			$("#txtDescripcion").val(data['descripcion']);
			$("#txtdia").val(data['dia']);
			$("#txtmes").val(data['mes']);
			$("#txtano").val(data['ano']);
			$("#txtCanGarantia").val(data['cant']);
			$("#txtgarantia").val(data['tipo']);
			$("#txtPorcentaje").val(data['porcentaje']);
			$("#txtDetalle").val(data['detalle']);
			$("#mod").html("Modificaco "+data['modifi']);
			if (data['foto'] != '') {

				$("#foto").attr("src", ruta + data['foto']);
				$('#foto').attr('src', $('#foto').attr('src') + '?' + Math.random());
			}
			//alert(1);
			if (data['seriales'] != 'no') {//alert(1.1);
				Grid = new TGrid(data['seriales'], 'resultado', 'Busqueda de Seriales');
				Grid.SetName("Pendientes");
				Grid.SetNumeracion(true);
				Grid.Generar();
			} else {//alert(1.2);
				$("#resultado").html("<h2>NO SE ENCONTRARON SERIALES</h2>");
			}
			//alert(2);
		}
	});
}

function IncSeriales() {	
	var sSerial = document.frmInventario.txtSeriales.value;
	var Cargos = document.frmInventario.lstSeriales.options;
	
	$.ajax({
		url : sUrlP + "BSerial",
		type : "POST",
		data : "serial=" + sSerial,
		//dataType : "json",
		success : function(data){
			if (data == 0) {
				var Optiones_Cargo = new Option(sSerial, sSerial, "selected");
				Cargos[iPosicion] = Optiones_Cargo;
				iPosicion++;
			}else{
				alert("El Serial ya existe para otro producto");
			}

		}
	});

}

function EliSeriales() {
	var pos = document.frmInventario.lstSeriales.selectedIndex;
	document.frmInventario.lstSeriales.options[pos] = null;

	iPosicion--;
	SelSeriales();
}

function SelSeriales() {
	for ( i = 0; i <= iPosicion; i++) {
		document.frmInventario.lstSeriales.options[i].selected = true;
	}
}

//inventario
function Respaldo_Eliminar_Serial() {
	if ($("#txtSerial_E").val() != '') {
		$("#r_inventario").dialog({
			buttons : {
				"Aceptar" : function() {
					Eliminar_Serial();
				},
				"Cerrar" : function() {
					$(this).dialog("close");
				}
			}
		});
		$("#r_inventario").dialog("open");
	} else {
		$("#msj_alertas").html("DEBE INGRESAR EL SERIAL A ELIMINAR");
		$("#msj_alertas").dialog('open');
	}
}

function Eliminar_Serial() {
	var serial = $("#txtSerial_E").val();
	var peticion = $("#txtRPeticion_Inv").val();
	var motivo = $("#txtRMotivo_Inv").val();
	if (motivo == '' || peticion == '') {
		$("#msj_alertas").html("<h2>DEBE INGRESAR<BR>-MOTIVO POR EL CUAL SE VA A ELIMINAR EL SERIAL<BR>-NOMBRE DE LA PERSONA QUE SOLICITO LA ELIMINACION</h2> ");
		$("#msj_alertas").dialog({
			width : 500,
			height : 200,
		});
		$("#msj_alertas").dialog('open');
	} else {
		$("#img_eliminar_serial").show();
		$("#txtRMotivo_Inv").val('');
		$("#txtRPeticion_Inv").val('');
		$("#txtSerial_E").val("");

		$.ajax({
			url : sUrlP + "Eliminar_Serial",
			type : "POST",
			data : "serial=" + serial + "&peticion=" + peticion + "&motivo=" + motivo,
			success : function(html) {
				$("#img_eliminar_serial").hide();
				$("#msj_alertas").html(html);
				$("#r_inventario").dialog("close");
				$("#msj_alertas").dialog('open');

			}
		});
	}

}

function Mod_Modelo() {
	var modelo = $("#cmbModeloM").val();
	var nmod = $("#txtNMod").val();
	//alert(modelo+'//'+nmod);
	if (nmod == '') {
		$("#msj_alertas").html("<h2>DEBE INGRESAR<BR>-Nuevo Modelos</h2> ");
		$("#msj_alertas").dialog({
			width : 500,
			height : 200,
		});
		$("#msj_alertas").dialog('open');
	} else {
		$("#img_mod_modelo").show();
		$("#txtNMod").val("");

		$.ajax({
			url : sUrlP + "Mod_Modelo",
			type : "POST",
			data : "id=" + modelo + "&nmod=" + nmod,
			success : function(html) {
				$("#img_mod_modelo").hide();
				$("#msj_alertas").html(html);
				$("#msj_alertas").dialog('open');

			}
		});
	}

}

function Mod_Artefacto() {
	var art = $("#cmbArt").val();
	var nart = $("#txtNArt").val();
	//alert(modelo+'//'+nmod);
	if (nart == '') {
		$("#msj_alertas").html("<h2>DEBE INGRESAR<BR>-Nuevo Artefacto</h2> ");
		$("#msj_alertas").dialog({
			width : 500,
			height : 200,
		});
		$("#msj_alertas").dialog('open');
	} else {
		$("#img_mod_art").show();
		$("#txtNArt").val("");

		$.ajax({
			url : sUrlP + "Mod_Artefacto",
			type : "POST",
			data : "id=" + art + "&nart=" + nart,
			success : function(html) {
				$("#img_mod_art").hide();
				$("#msj_alertas").html(html);
				$("#msj_alertas").dialog('open');

			}
		});
	}

}

/**
 * @UPDATE
 * Pulir Esta funcion Entera
 */
/*function Eliminar_Modelo(strUrl) {

 var strUrl_Proceso = sUrlP + "Eliminar_Modelo";
 var cantidad = $("#cmbModelo").val();
 var indice = $("cmbModelo").selectedIndex;
 var mod = $("cmbModelo").options[indice].text;
 $("#divProcesarModelo").show("blind");

 var entrar = confirm("¿De verdad desea eliminar el modelo " + mod + " ?\n Tiene asociado " + cantidad + " productos");

 if(cantidad > 0) {
 if(entrar) {
 new Ajax.Request(strUrl_Proceso, {
 method : "post",
 postBody : "modelo=" + mod,
 onSuccess : function(transport) {
 $("#divProcesarModelo").hide();
 $("#divEliminarModelo").show("blind");
 document.getElementById("cmbModelo").options[indice] = null;
 document.getElementById("divEliminarModelo").innerHTML = transport.responseText;
 },
 onFailure : function(transport) {
 document.getElementById("divEliminarModelo").innerHTML = transport.responseText;
 }
 });
 } else {
 $("#divProcesarModelo").hide();
 return 0;
 }

 } else {

 new Ajax.Request(strUrl_Proceso, {
 method : "post",
 postBody : "modelo=" + mod,
 onSuccess : function(transport) {
 $("#divProcesarModelo").hide();
 $("#divEliminarModelo").show("blind");
 document.getElementById("cmbModelo").options[indice] = null;
 document.getElementById("divEliminarModelo").innerHTML = transport.responseText;
 },
 onFailure : function(transport) {
 document.getElementById("divEliminarModelo").innerHTML = transport.responseText;
 }
 });
 }
 }*/

function valida(formulario) {
	if ($("#txtModelo").val() == '' || $("#txtModelo").val() == null) {
		alert('DEBE INGRESAR UN MODELO....');
		return false;
	}
	return true;
}

function carga_imagen() {
	var modelo = $("#txtModelo").val();
	if (modelo != '') {
		N_Ventana('subir_catalogo/' + modelo);
	} else {
		alert("Debe ingresar un modelo");
	}

}

function AgregarCheque() {
	cargar = $("#txtCheque").val();
	if (cargar != '') {
		$("#lstCheques").append(new Option(cargar, cargar));
		$("#txtCheque").val('');
	}
}

function AgregarSerie() {
	cargar = $("#txtCheque").val();
	objeto = cargar.split('-');
	cantidad = $("#txtCantidad_Chequera").val();
	inicio = parseInt(objeto[1]);
	fin = inicio + cantidad;
	for ( i = 1; i <= cantidad; i++) {

		elemento = objeto[0] + inicio;
		if (cargar != '') {
			$("#lstCheques").append(new Option(elemento, elemento));

		}
		inicio++;
	}
	$("#txtCheque").val('');
}

function Eliminar_Cheque() {
	$("#lstCheques option:selected").remove();
}

function Respaldo_Eliminar_Modelo() {
	if ($("#cmbModelo option:selected").text() != '') {
		$("#r_inventario").dialog({
			buttons : {
				"Aceptar" : function() {
					Eliminar_Modelo();
				},
				"Cerrar" : function() {
					$(this).dialog("close");
				}
			}
		});
		$("#r_inventario").dialog("open");
	} else {
		$("#msj_alertas").html("DEBE SELECCIONAR EL MODELO A ELIMINAR");
		$("#msj_alertas").dialog('open');
	}
}

function Eliminar_Modelo() {
	var cantidad = $("#cmbModelo option:selected").val();
	var indice = $("#cmbModelo option:selected").text();
	var mod = $("#cmbModelo option:selected").text();
	var peticion = $("#txtRPeticion_Inv").val();
	var motivo = $("#txtRMotivo_Inv").val();
	if (motivo == '' || peticion == '') {
		$("#msj_alertas").html("<h2>DEBE INGRESAR<BR>-MOTIVO POR EL CUAL SE VA A ELIMINAR EL MODELO<BR>-NOMBRE DE LA PERSONA QUE SOLICITO LA ELIMINACION</h2> ");
		$("#msj_alertas").dialog({
			width : 500,
			height : 200,
		});
		$("#msj_alertas").dialog('open');
	} else {
		var entrar = confirm("¿De verdad desea eliminar el modelo " + mod + " ?\n Tiene asociado " + cantidad + " productos");
		$("#img_eliminar_modelo").show();
		$("#txtRMotivo_Inv").val('');
		$("#txtRPeticion_Inv").val('');

		if (cantidad > 0) {
			if (entrar) {
				$.ajax({
					url : sUrlP + "Eliminar_Modelo",
					type : "POST",
					data : "modelo=" + mod + "&peticion=" + peticion + "&motivo=" + motivo,
					success : function(html) {
						$("#cmbModelo option:selected").remove();
						$("#img_eliminar_modelo").hide();
						$("#msj_alertas").html(html);
						$("#r_inventario").dialog("close");
						$("#msj_alertas").dialog('open');
					}
				});
			} else {
				$("#msj_alertas").html("SE CANCELO LA ELIMINACION DEL MODELO");
				$("#msj_alertas").dialog('open');
			}
		} else {
			$.ajax({
				url : sUrlP + "Eliminar_Modelo",
				type : "POST",
				data : "modelo=" + mod + "&peticion=" + peticion + "&motivo=" + motivo,
				success : function(html) {
					$("#cmbModelo option:selected").remove();
					$("#img_eliminar_modelo").hide();
					$("#msj_alertas").html(html);
					$("#r_inventario").dialog("close");
					$("#msj_alertas").dialog('open');
				}
			});
		}
	}
}

function Guarda_Chequera() {
	strUrl_Proceso = sUrlP + "Guarda_Chequera";
	banco = $("#lstBanco option:selected").val();
	ubicacion = $("#lstUbicacion option:selected").val();
	fecha = $("#fechaChequera").val();
	cantidad = $("#txtCantidad_Chequera").val();
	descripcion = $("#txtdescripcion_Chequera").val();
	cuenta = $("#txtNumeroCuenta").val();
	nchequera = $("#txtnchequera").val();
	cheques = new Array();
	i = 0;
	$("#lstCheques option").each(function() {
		aux = $(this).text();
		cheques[i] = aux;
		i++;
	});
	if (fecha != '' && cantidad != '' && cuenta != '') {
		if (i != parseInt(cantidad)) {
			alert('El numero de cheques registrados no coincide con la cantidad ingresada..');
		} else {
			$.ajax({
				url : strUrl_Proceso,
				type : 'POST',
				data : 'banco=' + banco + '&ubicacion=' + ubicacion + '&fecha=' + fecha + '&cantidad=' + cantidad + '&descripcion=' + descripcion + '&cheques=' + cheques + '&cuenta=' + cuenta + '&nchequera=' + nchequera,
				//dataType: 'json',
				success : function(html) {
					alert(html);
					limpiar_chequera();
				}
			});
		}
	} else {
		alert('Debe ingresar la fecha, numero de cuenta y la cantidad...');
	}

}

function limpiar_chequera() {
	$("#lstBanco > option[value=1]").attr("selected", "selected");
	$("#fechaChequera").val('');
	$("#txtCantidad_Chequera").val('');
	$("#txtdescripcion_Chequera").val('');
	$("#lstCheques").html('');
}

function Consultar_Cheques() {
	strUrl_Proceso = sUrlP + "DataSource_Cheques";
	var banco = $("#lstBanco").val();
	var ubicacion = $("#lstUbicacion").val();
	if (banco > 0) {
		$.ajax({
			url : strUrl_Proceso,
			type : 'POST',
			data : 'ban=' + banco + '&ubi=' + ubicacion,
			dataType : 'json',
			success : function(oEsq) {
				Grid = new TGrid(oEsq, 'Cheques', 'Historial de Cheques');
				Grid.SetName("Cheques");
				Grid.Generar();
			}
		});
	}
}
