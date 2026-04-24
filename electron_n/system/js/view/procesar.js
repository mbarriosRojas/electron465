/**
 * 	Controlador system.js.view.procesar.js
 *
 */
if (document.addEventListener) {
	document.addEventListener("DOMContentLoaded", cargando, false);
}
function cargando() {
	//$("#carga_busqueda").dialog('open');
}

$(function() {
	Modalidad();
	$('#tabs2').tabs();
	$('#dialog').dialog({
		autoOpen : false,
		position : 'top',
		width : "60%",
		height : 600,
		buttons : {
			"Aceptar" : function() {
				Enviar();
			},
			"Imprimir" : function() {
				Imprime_Contrato();
			},
			"Cancelar" : function() {

				$(this).dialog("close");
			}
		}
	});
	$('#respaldo_eliminacion').dialog({
		modal : true,
		autoOpen : false,
		width : 450,
		height : 300,
	});
	$('#tabla_cobros').dialog({
		autoOpen : false,
		position : 'top',
		width : "40%",
		height : 300,
		buttons : {
			"Cerrar" : function() {

				$(this).dialog("close");
			}
		}
	});
	

});
$("#mbuzon").removeClass('active');
$("#mcliente").addClass('active');

function Respaldo_Eliminar_Cuota(documento_id, contrato_id, fecha, monto) {
	$('#respaldo_eliminacion').dialog({
		buttons : {
			"Cerrar" : function() {

				$(this).dialog("close");
			},
			"Eliminar" : function() {
				Eliminar_Contrato(documento_id, contrato_id, fecha, monto);
			}
		}
	});
	$('#respaldo_eliminacion').dialog('open');
}

function Eliminar_Contrato(documento_id, contrato_id, fecha, monto) {
	var peticion = $('#txtPeticion').val();
	var motivo = $('#txtRMotivo').val();
	if (peticion == '' || motivo == '') {
		alert('Debe Ingresar Todos los datos');
	} else {
		strUrl_Proceso = sUrlP + "Eliminar_Cobros";
		$.ajax({
			url : strUrl_Proceso,
			type : 'post',
			data : 'documento_id=' + documento_id + '&contrato_id=' + contrato_id + '&fecha=' + fecha + '&monto=' + monto + '&peticion=' + peticion + '&motivo=' + motivo,
			success : function(htm) {
				$('#tabla_creditos').html(htm);
				$('#respaldo_eliminacion').dialog('close');
			}
		});
		$('#DivPlanPagos').html('Actualizando...');
		strUrl_Proceso = sUrlP + "DataSource_Control";
		$.ajax({
			url : strUrl_Proceso,
			type : "POST",
			data : 'id=' + contrato_id,
			dataType : "json",
			success : function(oEsq) {
				Grid = new TGrid(oEsq, 'DivPlanPagos', "");
				Grid.SetNumeracion(true);
				Grid.SetName("DivPlanPagos");
				Grid.SetDetalle();
				Grid.Generar();
			}
		});
	}

}

function Mostrar_Detalles(id) {
	$('#divDetalles' + id).show("blind");
}

function Ocultar_Detalles(id) {
	$('#divDetalles' + id).hide("blind");
}

function Consultar(documento_id, contrato_id) {
	strUrl_Proceso = sUrlP + "DataSource_Cobros";
	document.getElementById("txtDocumento_Id").value = documento_id;
	document.getElementById("txtNumero_Contrato").value = contrato_id;
	$.ajax({
		url : strUrl_Proceso,
		type : 'POST',
		data : 'documento_id=' + documento_id + '&contrato_id=' + contrato_id,
		success : function(htm) {
			$('#tabla_creditos').html(htm);
		}
	});
	strUrl_Proceso = sUrlP + "DataSource_Control";
	$.ajax({
		url : strUrl_Proceso,
		type : "POST",
		data : 'id=' + contrato_id,
		dataType : "json",
		success : function(oEsq) {

			Grid = new TGrid(oEsq, 'DivPlanPagos', "");
			Grid.SetNumeracion(true);
			Grid.SetName("DivPlanPagos");
			Grid.SetDetalle();
			Grid.Generar();
		}
	});
	$('#dialog').dialog("open");

}

function Consultar_Asociado(cedula) {
	if (cedula != "No Aplica") {
		$("#txtBuscar").val(cedula);
		$("#frmBuscar").submit();
	}
}

function Enviar() {
	strUrl_Proceso = sUrlP + "Agregar_Cobros";
	var documento_id = $("#txtDocumento_Id").val();
	var contrato_id = $("#txtNumero_Contrato").val();
	var monto = $("#txtmontocobrado").val();
	if(monto == ''){
		alert("Debe ingresar el monto...");
		return false;
	}
	var diaC = $("#txtDiaCobro").val();
	var mesC = $("#txtMesCobro").val();
	var anoC = $("#txtAnoCobro").val();
	var fecha = anoC + "-" + mesC + "-" + diaC;
	var diaD = $("#txtDiaDes").val();
	var mesD = $("#txtMesDes").val();
	var anoD = $("#txtAnoDes").val();
	var fechaD = anoD + "-" + mesD + "-" + diaD;
	var moda = $("#moda").val();
	var descripcion = document.getElementById("txtDescripcion").value;
	$("#msj_alertas").html('POR FAVOR ESPERE MIENTRAS SE CARGA EL COBRO');
	$("#msj_alertas").dialog("open");
	
	var datos = new FormData();

	datos.append('documento_id', documento_id);
	datos.append('contrato_id', contrato_id);
	datos.append('fecha', fecha);
	datos.append('mes', '0');
	datos.append('descripcion', descripcion);
	datos.append('monto', monto);
	datos.append('fechad', fechaD);
	datos.append('moda', moda);
	datos.append('anop', anoC);
	datos.append('mesp', mesC);
	//alert(strUrl_Proceso);
	$.ajax({
		url : strUrl_Proceso,
		type : 'POST',
		data : datos,
		contentType : false,
		processData : false,
		cache : false,
		success : function(htm) {//alert(htm);
			$("#msj_alertas").html('Se proceso con exito. De no poseer privilegio de carga la cuota no sera procesada');
			$('#tabla_creditos').html(htm);
		}
	});
}

function Imprime_Contrato() {
	var cedula = $("#txtDocumento_Id").val();
	var contrato = $("#txtNumero_Contrato").val();
	strUrl_Proceso = sUrlP + "Imprimir_Estado_Cuenta_Contrato/" + cedula + "/" + contrato;
	window.open(strUrl_Proceso, "Width=450,Height=450,Location =No,Menubar =No,Status =No");
}

function Imprimir_Estado() {
	strUrl = sUrlP + "Imprimir_Estado/" + $("#documento_id").val();
	window.open(strUrl, "ventana1", "toolbar=0,location=1,menubar=0,scrollbars=1,resizable=1,width=800,height=800")
}

function PInventarioAsociar(sUrl, sSerial) {
	//var sUrlP = sUrl;
	var sCont = "";
	var factura = $("#txtnfactura").val();
	if (factura != '') {
		var iPos = 1;
		$.ajax({
			url : sUrl,
			type : "POST",
			data : "serial=" + sSerial + "&factura=" + factura,
			success : function(html) {
				alert('Se asocio serial');
				$("#divDetalles" + iPos).html(html);

			}
		});
	} else {
		alert('Debe ingresar una factura');
	}
}

function CFactura(cedula) {
	$('#carga_busqueda').dialog('open');
	$.ajax({
		url : sUrlP + "TG_Cedula",
		type : "POST",
		data : 'id=' + cedula,
		dataType : "json",
		success : function(oEsq) {
			$('#carga_busqueda').dialog('close');
			$("#persona").html(oEsq.persona);
			$('#foto').attr('src', $('#foto').attr('src') + '?' + Math.random());
			if (oEsq.obj1.msj != undefined) {
				$("#Procesar").html(oEsq.obj1.msj);
			} else {
				
				Grid = new TGrid(oEsq.obj1, 'Procesar', "titulo");
				Grid.SetNumeracion(true);
				Grid.SetName("Procesar");
				Grid.SetDetalle();
				Grid.Generar();
				if (oEsq.obj1.alerta != '') {
					$("#msj_alertas").html(oEsq.obj1.alerta);
					$("#msj_alertas").dialog("open");
				}
			}
			if (oEsq.obj2.msj != undefined) {
				$("#contado").html(oEsq.obj2.msj);
			} else {
				
				Grid2 = new TGrid(oEsq.obj2, 'contado', "titulo");
				Grid2.SetNumeracion(true);
				Grid2.SetName("contado");
				Grid2.SetDetalle();
				Grid2.Generar();
			}
			//alert(oEsq);
		}
	});
}

function CVoucher(voucher) {
	$('#carga_busqueda').dialog('open');
	$.ajax({
		url : sUrlP + "Busca_Voucher",
		type : "POST",
		data : 'id=' + voucher,
		dataType : "json",
		success : function(oEsq) {
			$('#carga_busqueda').dialog('close');
			if(oEsq.msj != 0){
				Grid = new TGrid(oEsq, 'Procesar', "Voucher "+ voucher);
				Grid.SetNumeracion(true);
				Grid.SetName("Procesar");
				Grid.Generar();
				if (oEsq.msj != '') {
					$("#msj_alertas").html(oEsq.msj);
					$("#msj_alertas").dialog("open");
				}	
			}else{
				$("#msj_alertas").html("No se encontraron voucher asociados al numero:"+voucher);
				$("#msj_alertas").dialog("open");
			}
		}
	});
}

function Pago_Voucher(voucher,factura) {
	strUrl_Proceso = sUrlP + "DataSource_Cobros_Programadas";
	$.ajax({
		url : strUrl_Proceso,
		type : 'POST',
		data : 'voucher=' + voucher + '&factura='+factura,
		dataType : "json",
		success : function(oEsqCobros) {
			GridCo = new TGrid(oEsqCobros, 'tabla_cobros', oEsqCobros.msj);
			//alert(oEsqCobros);
			GridCo.SetName("COBROS");
			GridCo.SetNumeracion(true);
			GridCo.Generar();
			$('#tabla_cobros').dialog("open");
		}
	});

}

function Modalidad(){
	strUrl_Proceso = sUrlP + "Combo_Modalidad";
	$.ajax({
		url : strUrl_Proceso,
		type : 'POST',
		dataType : 'json',
		success : function(json) {//alert(json);
			
			$.each(json, function(item, valor) {
				//alert(item+'//'+valor);
				$("#moda").append(new Option(valor, item));
			});
		}
	});
	
}