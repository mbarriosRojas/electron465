$(function() {
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
	
	$("#Suspender").button({
				icons : {
					primary : 'ui-icon ui-icon-locked'
				}
			});
			
	$('#r_suspender').dialog({
		modal : true,
		autoOpen : false,
		width : 450,
		height : 300,
		buttons : {
			"Aceptar" : function() {
				btnDBaja2();
			},
			"Cerrar" : function() {
				$(this).dialog("close");
			}
		}
	});
	Modalidad();
});


/*
 * Busqueda por voucher
 */
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

/*
 * Reescritura de funciones Busqueda de cliente
 */

function facturas_contado(id){
	//alert(id);
	if (id == '') {
		alert('Debe ingresar una cedula');
		return 0;
	}
	strUrl_Proceso = sUrlP + "ProcesarContado";
	$.ajax({
		url : strUrl_Proceso,
		type : 'POST',
		data : 'id=' + id,
		dataType : 'json',
		success : function(json) {//alert(json);
			if (json.msj != undefined) {
				$("#Contado").html(json.msj);
			} else {			
				Grid2 = new TGrid(json, 'Contado', "titulo");
				Grid2.SetNumeracion(true);
				Grid2.SetName("contado");
				Grid2.SetDetalle();
				Grid2.Generar();
			}		
		}
	});
	
}

function consulta_cliente(id) {
	
	if (id == '') {
		alert('Debe ingresar una cedula');
		return 0;
	}
	strUrl_Proceso = sUrlP + "ProcesarDatosBasicos";
	console.log(sUrlP);
	$.ajax({
		url : strUrl_Proceso,
		type : 'POST',
		data : 'id=' + id,
		dataType : 'json',
		success : function(json) {
			console.log(json);
			if (json['primer_nombre'] != null) {
				cedula = json["documento_id"];
				pnom = json["primer_nombre"];
				snom = json["segundo_nombre"];
				pape = json["primer_apellido"];
				sape = json["segundo_apellido"];
				nombre = pnom+' '+snom+' '+pape+' '+sape;
				foto = json['fotoc'];
				naci = json["nacionalidad"];
				var btnSusp = "<button class='btn' onclick='btnDBaja("+cedula+")'>Suspender</button><input type='hidden' value='"+cedula+"' id='txtCedula' />";
				var htmlDatos = '<table class="tgrid"><tr><td style="height: 100px;width: 110px;"rowspan="3"><img id="foto" name="foto" src="' + foto + '" style="height: 100px;width: 110px;"/></td>';
				htmlDatos += '<th>CEDULA</th><td><h2>'+naci+cedula+'</h2></td><td>'+btnSusp+'</td></tr><tr><th>NOMBRE Y APELLIDOS</th><td colspan=2>'+nombre+'</td></tr>';
				htmlDatos +='<tr><th>CODIGO CLIENTE</th><td colspan=2>'+json['nro_documento']+'</td></tr></table><br><br>';
				$('#Datos_Basicos').html(htmlDatos);
				$("button").button();
				var htmlPersona = '<h2>DATOS PERSONALES</h2><br><table class="tgrid"><tr>';
				htmlPersona += '<th>Telefonos</th><td>'+json['telefono']+'</td><th>Correo</th><td>'+json['correo']+'</td><th>Cargo</th><td>'+json['cargo_actual']+'</td></tr>';
				htmlPersona +='<tr><th>Banco1</th><td>'+json['banco_1']+'</td><th>Cuenta1</th><td>'+json['cuenta_1']+'</td><th>Tipo</th><td>'+json['tipo_cuenta_1']+'</td></tr>';
				htmlPersona +='<tr><th>Banco2</th><td>'+json['banco_2']+'</td><th>Cuenta2</th><td>'+json['cuenta_2']+'</td><th>Tipo</th><td>'+json['tipo_cuenta_2']+'</td></tr>';
				htmlPersona +='<tr><th>Direccion Trabajo</th><td colspan=5>'+json['direccion_trabajo']+'</td></tr>';
				htmlPersona +='<tr><th>Nomina(S)</th><td colspan=5>'+json['nomi']+'</td></tr>';
				htmlPersona +='<tr><th>Tipo Cliente</th><td>'+json['tipocliente']+'</td><th>Certificado</th><td colspan=3>'+json['certi']+'</td></tr></table><br><br>';
				$('#basico').html(htmlPersona);
				disponibilidad = json["disponibilidad"];
				if (disponibilidad != 0 && disponibilidad != undefined) {
					if (disponibilidad == 1) mensaje = "El cliente esta actualmente suspendido:";
					if (disponibilidad == 2) mensaje = "El cliente esta actualmente BLOQUEADO del sistema<br>No se le consedera ningun credito<br>Para mayor informacion Comunicarse con la oficina principal :";
					alert(mensaje);
				}
				verificar_deuda(id);
				lista_asociados(id);
				historial(id);
			}else{
				$('#Datos_Basicos').html("El cliente debe estar registrado..");
				alert("El cliente debe estar registrado..");
			}
		}
	});	
	return true;
}

function verificar_deuda(id){
	strUrl_Proceso = sUrlP + "Deuda_Cliente";
	$.ajax({
		url : strUrl_Proceso,
		type : 'POST',
		data : 'id=' + id,
		dataType : 'json',
		success : function(jSon) {
			$("#basico").append(jSon['resultado']);
		}
	});
}

function lista_asociados(id){//alert('entra');
	strUrl_Proceso = sUrlP + "ProcesarAsociados";
	$.ajax({
		url : strUrl_Proceso,
		type : 'POST',
		data : 'id=' + id,
		dataType : "json",
		success : function(oEsqAso) {//alert(oEsqAso);
			if(oEsqAso.cant > 0){
				GridAso = new TGrid(oEsqAso, 'contactos', 'Contactos');
				GridAso.SetName("Contactos");
				GridAso.SetNumeracion(true);
				GridAso.Generar();
			}else{
				$("#contactos").html("<br><h2>NO POSEE CONTACTOS REGISTRADOS</h2>");
			}
		}
	});
}

function historial(id){//alert('entra');
	strUrl_Proceso = sUrlP + "ProcesarHistorial";
	$.ajax({
		url : strUrl_Proceso,
		type : 'POST',
		data : 'id=' + id,
		dataType : "json",
		success : function(oEsqHis) {//alert(oEsqAso);
			if(oEsqHis.cant > 0){
				GridHis = new TGrid(oEsqHis, 'historial', 'Historial');
				GridHis.SetName("Historial");
				GridHis.SetNumeracion(true);
				GridHis.Generar();
			}else{
				$("#historial").html("<br><h2>NO POSEE HISTORIAL REGISTRADOS</h2>");
			}
		}
	});
}

function activos(id){
	var act = $("#p_activo").val();
	if(act == 0){
		$("#p_activo").val(1);
		strUrl_Proceso = sUrlP + "ProcesarActivos";
		$.ajax({
			url : strUrl_Proceso,
			type : 'POST',
			data : 'id=' + id,
			dataType : "json",
			success : function(oEsqAct) {//alert(oEsqAct);
				if(oEsqAct.cant > 0){
					GridAct = new TGrid(oEsqAct, 'activos', 'Facturas Activas');
					GridAct.SetName("Activos");
					GridAct.SetNumeracion(true);
					GridAct.Generar();
				}else{
					$("#activos").html("<br><h2>NO POSEE FACTURAS ACTIVAS</h2>");
				}
			}
		});
	}
}

function cancelado(id){
	var act = $("#p_cancelado").val();
	if(act == 0){
		$("#p_cancelado").val(1);
		strUrl_Proceso = sUrlP + "ProcesarCancelado";
		$.ajax({
			url : strUrl_Proceso,
			type : 'POST',
			data : 'id=' + id,
			dataType : "json",
			success : function(oEsqCan) {//alert(oEsqCan);
				if(oEsqCan.cant > 0){
					GridCan = new TGrid(oEsqCan, 'cancelado', 'Facturas Canceladas');
					GridCan.SetName("Cancelado");
					GridCan.SetNumeracion(true);
					GridCan.Generar();
				}else{
					$("#cancelado").html("<br><h2>NO POSEE FACTURAS CANCELADAS</h2>");
				}
			}
		});
	}
}

/*
 * Detalle pago de voucher
 */
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

function CargarNotaCredito(id){
		//alert(id);
	if (id == '') {
		alert('Debe ingresar una cedula');
		return 0;
	}
	strUrl_Proceso = sUrlP + "CargarNotaCredito/" + id;
	$.ajax({
		url : strUrl_Proceso,
		type : 'POST',
		data : 'id=' + id,
		dataType : 'json',
		success : function(json) {//alert(json);
					
				Grid2 = new TGrid(json, 'notas_credito', "NOTAS DE CREDITOS PENDIENTES");
				Grid2.SetNumeracion(false);
				Grid2.SetName("notas_credito");
				Grid2.SetDetalle();
				Grid2.Generar();
					
		}
	});
}

function btnDBaja(cedula) {
	if (cedula != '') {
		$("#r_suspender").dialog("open");
	} else {
		$("#msj_alertas").html("DEBE INGRESAR LA CEDULA");
		$("#msj_alertas").dialog('open');
	}
}

function btnDBaja2() {
	var strUrl_Proceso = sUrlP + "DBaja";
	var peticion = $("#txtRPeticion_Ced").val();
	var motivo = $("#txtRMotivo_Ced").val();
	var cedula = $("#txtCedula").val();

	if (motivo == '' || peticion == '') {
		$("#msj_alertas").html("<h2>DEBE INGRESAR<BR>-MOTIVO POR EL CUAL SE VA A SUSPENDER LA PERSONA<BR>-NOMBRE DE LA PERSONA QUE SOLICITO LA SUSPENCI&Oacute;N</h2> ");
		$("#msj_alertas").dialog({
			width : 500,
			height : 200,
		});
		$("#msj_alertas").dialog('open');
	} else {
		$("#txtRMotivo_Ced").val('');
		$("#txtRPeticion_Ced").val('');
		$.ajax({
			url : strUrl_Proceso,
			type : "POST",
			data : "cedula=" + cedula + "&val=1" + "&peticion=" + peticion + "&motivo=" + motivo,
			success : function(msg) {
				$("#r_suspender").dialog("close");
				//msg = 'El usuario ha sido suspendido';
				$('#msj_alertas').html(msg);
				$('#msj_alertas').dialog("open");
			}
		});
		Limpiar_Cliente();
		Limpiar_Credito();
	}
}