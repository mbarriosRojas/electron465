var sImgFoto = sUrl + '/system/repository/expedientes/fotos/';
$(function() {
	$("#divGuardarC").hide();
	$("#img_busca_factura").hide();
	$.ajax({
		url : sUrlP + 'getClientesBtn',
		dataType : 'json',
		success : function(json) {
			var click = '';
			var DivBtn = $("#BtnClientes");
			$.each(json, function(sId, sVal) {
				var btn = document.createElement('button');
				btn.id = sVal.metodo;
				btn.innerHTML = sVal.des;
				btn.onclick = function() {
					Ejecutar(sVal.accion, sVal.clase, sVal.metodo);
				}
				$("#BtnClientes").append(btn);
			});
			$("button").button();
			$("#Salvar").button({
				icons : {
					primary : 'ui-icon ui-icon-folder-collapsed'
				}
			});
			$("#Borrar").button({
				icons : {
					primary : 'ui-icon ui-icon-circle-close'
				}
			});
			$("#Suspender").button({
				icons : {
					primary : 'ui-icon ui-icon-locked'
				}
			});
			$("#Imprimir").button({
				icons : {
					primary : 'ui-icon ui-icon-print'
				}
			});
			$("#Rechazar").button({
				icons : {
					primary : 'ui-icon ui-icon-contact'
				}
			});
			$("#Modificar").button({
				icons : {
					primary : 'ui-icon ui-icon-contact'
				}
			});
			$("#Afiliacion1").button({
				icons : {
					primary : 'ui-icon ui-icon-contact'
				}
			});
			$("#Afiliacion2").button({
				icons : {
					primary : 'ui-icon ui-icon-contact'
				}
			});
		}
	});
	

	$("#mbuzon").removeClass('active');
	$("#mcliente").addClass('active');

	//Fin de Modelo

	$('#divZonaPostal').dialog({
		modal : true,
		autoOpen : false,
		//position: 'top',
		hide : 'explode',
		show : 'slide',
		width : 400,
		height : 150,
		buttons : {
			"Aceptar" : function() {
				var zona = $("#cmbZonas").val();
				$("#txtZonaPostal").val(zona);
				$(this).dialog("close");
			},
		}
	});
	
	$('#divZonaPostal_Dep').dialog({
		modal : true,
		autoOpen : false,
		//position: 'top',
		hide : 'explode',
		show : 'slide',
		width : 400,
		height : 150,
		buttons : {
			"Aceptar" : function() {
				var zona = $("#cmbZonas_Dep").val();
				$("#txtZonaPostal_Dep").val(zona);
				$(this).dialog("close");
			},
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

});
//Fin del Function Maestro

/**
 *
 * @param accion function javascrip
 * @param clase load Cotrolador
 * @param metodo load metodo
 */
function Ejecutar(accion, clase, metodo) {
	eval(accion);
}

function busca_zona_postal() {
	var estado = $("#cmbEstados").val();
	if (estado != 0) {
		$.ajax({
			url : sUrlP + 'Lista_Zona_Postal',
			type : "POST",
			data : "estado=" + estado,
			success : function(html) {
				$("#cmbZonas").html(html);
			}
		});
	} else {
		$("#cmbZonas").html("");
	}
}


function muestra_zona_postal() {
	$("#divZonaPostal").dialog("open");
}


function Generar_Codigo() {

	$.ajax({
		url : sUrlP + "html_valor",
		type : 'post',
		success : function(html) {
			$('#txtNumero_Contrato').val(html);
			consultar_creditos();
			//$('#txtNumero_Contrato').hide();
		}
	});
}

function Cargar() {
	$.ajax({
		url : sUrlP + 'Lista_Estados_Verificados',
		type : "POST",
		success : function(html) {
			$("#DivLista-Verificados").html(html);
		}
	});
}

/**
 * Funciones de Registrar Cliente Consultar
 *
 */
function btnGuardar() {
	strUrl_Proceso = sUrlP + "Guardar_Cliente";
	var tipo_pago = $("#txtTipoPago").val();
	var artipo_pago = tipo_pago.split('-');
	var strCedula = $("#txtCedula").val();
	var sSerial = "";
	var n_boucher = $("#txtCantBoucher").val();
	var monto_cargado = $("#txtmontocredito").val();
	var ncuotas = $("#txtNumeroCuotas").val();
	var mcuota = $("#txtMontoCuota").val();
	var recalculo = monto_cargado/ncuotas;

	if($("#insti").val() == '0'){
		alert('Debe ingresar una institucion');
		return 0;
	}
	
	$("#msj_alertas").dialog({
		buttons : {
			"Aceptar" : function() {
				//$(this).dialog("close");
			}
		}
	});
	nom1 = $("#txtNombreAsociado1").val(); 
	nom2 = $("#txtNombreAsociado2").val();
	nom3 = $("#txtNombreAsociado3").val();
	tlf1 = $("#txtTlfAsociado1").val();
	tlf2 = $("#txtTlfAsociado2").val();
	tlf3 = $("#txtTlfAsociado3").val();
	dir1 = $("#txtdirecA1").val();
	dir2 = $("#txtdirecA2").val();
	dir3 = $("#txtdirecA3").val();
	nomi1 = $("#txtNomiA1").val();
	nomi2 = $("#txtNomiA2").val();
	nomi3 = $("#txtNomiA3").val();
	var maximo_cod='';
	if((nom1 == '' || nom2 == '' || tlf1 == '' || tlf2 == '' || dir1 == '' || dir2 == '' || nomi1 == '' || nomi2 == '') && $("#txtNumero_Contrato").val() != ''){
		alert("DEBE INGRESAR NOMBRE, DIRECCION, NOMINA Y TELEFONO DE TODOS LOS CONTACTOS");
		return 0;
	}
	
	l_boucher = new Array();
	i=0;
	$.ajax({
		url : sUrlP + 'maximo_valor_js',
		type : 'POST',
		success : function(cod_val) {
			//alert('viene:'+cod_val);
			aux = cod_val.split('-');
			maximo_cod= aux[1].substring(2);
			//alert('esta:'+maximo_cod);
		}
	});
	var estado_tipo_pago = $("#txtTipoPago").attr("disabled");
	//alert(estado_tipo_pago);
	alert("CODIGO DE VOUCHER GENERADO");
	var tipo_pago = $("#txtTipoPago :selected").text();
	$("#lstBoucher option").each(function(){
		if((tipo_pago != "VOUCHER - BFC" || tipo_pago != "DOMICILIACION") && estado_tipo_pago !='disabled'){
			var texto_picado =  $(this).text().split('|');
			//alert(maximo_cod);
			if(texto_picado[1] != maximo_cod){
				
				texto_picado[1] = maximo_cod;
			}
		   if(i < 9){
	       	aux = texto_picado[0]+ '|' +texto_picado[1] + "00" + (i+1) + '|' + texto_picado[2];
		   }else{
		   	aux = texto_picado[0]+ '|' +texto_picado[1] + "0" + (i+1) + '|' + texto_picado[2];
		   }			
		   
		}else{
			aux = $(this).text();
		}
       
       l_boucher[i] = aux;
       i++;
    });
    
	if (strCedula == "" ) {
		$("#msj_alertas").html("(-) Debe intruducir los campos marcados con (*), son obligatorios...Ademas el monto de contrato no puede ser cero (0) ");
		$("#msj_alertas").dialog("open");
	} else {
		if(recalculo != mcuota && recalculo > 0){
			
			alert("Se recalculo el monto de la cuota..Debe verificar antes de Resgistra el contrato..");
			$("#txtMontoCuota").val(recalculo);
			return 0;
		}
		if(artipo_pago[0] == '6' && i != parseInt(n_boucher)){
			$("#msj_alertas").html("(-) Debe verificar que todos los Boucher esten cargados/"+i+"/"+n_boucher);
			$("#msj_alertas").dialog("open");
		}else{
			sSerial = '';
            var sector = $("#txtSector").val()+'|'+$("#txtCiudad2").val()+'|'+$("#txtUbicacion2").val();
			var sCadena = 'cedula=' + $("#txtCedula").val()+ '&lstBoucher=' + l_boucher+ '&domiciliacionC=' 
			+ $("#txtDomiciliacionC").val()+ '&domiciliacionG=' + $("#txtDomiciliacionG").val() 
			+ '&domiciliacionI=' + $("#txtDomiciliacionI").val()+ '&tipopago=' + $("#txtTipoPago").val() 
			+ '&nombre=' + $("#txtNombre1").val() + '&nombre2=' + $("#txtNombre2").val() 
			+ '&apellido=' + $("#txtApellido1").val() + '&apellido2=' + $("#txtApellido2").val() 
			+ '&sexo=' + $("#txtSexo").val() + '&edocivil=' + $("#txtEdocivil").val() 
			+ '&direccionh=' + $("#txtDireccionH").val() + '&direcciont=' + $("#txtDireccionT").val() + '&direcciont2=' + $("#txtDireccionT2").val()
                + '&telefono=' + $("#txtTelefono").val() + '|'+$("#txtTelefonoh").val()+'&nacionalidad=' + $("#txtNacionalidad").val()
			+ '&ciudad=' + $("#txtCiudad").val() + '&cargo=' + $("#txtCargo").val() 
			+ '&dia=' + $("#txtDiaNacimiento").val() + '&mes=' + $("#txtMesNacimiento").val() + '&ano=' + $("#txtAnoNacimiento").val() 
			+ '&diaIng=' + $("#txtDiaI").val() + '&mesIng=' + $("#txtMesI").val() + '&anoIng=' + $("#txtAnoI").val() 
			+ '&ubicacionactual=' + $("#txtUbicacion").val() + '&banco_1=' + $("#txtbanco_1").val() 
			+ '&cuenta_1=' + $("#txtcuenta_1").val() + '&tipo_1=' + $("#txtTipo_1").val() 
			+ '&banco_2=' + $("#txtbanco_2").val() + '&cuenta_2=' + $("#txtcuenta_2").val() 
			+ '&numero_tarjeta=' + $("#txtnumero_tarjeta").val() + '&tipo_2=' + $("#txtTipo_2").val() 
			+ '&municipio=' + $("#txtMunicipio").val() + '&parroquia=' + $("#txtParroquia").val() 
			+ '&sector=' + sector + '&avenida=' + $("#txtAvenida").val() + '&calle=' + $("#txtCalles").val()
			+ '&urbanizacion=' + $("#txtUrbanizacion").val() + '&correo=' + $("#txtCorreo").val() + '&pin=' + $("#txtPin").val() 
			+ '&montocredito=' + $("#txtmontocredito").val() + '&numerocuotas=' + $("#txtNumeroCuotas").val() 
			+ '&solicitudDia=' + $("#txtDiaC").val() + '&solicitudMes=' + $("#txtMesC").val() 
			+ '&solicitudAno=' + $("#txtAnoC").val() + '&nominaperiocidad=' + $("#txtNominaPeriocidad").val()  
			+ '&nominaprocedencia=' + $("#txtNominaProcedencia").val() + '&mesvacaciones=' + $("#txtMesVacaciones").val() 
			+ '&anovacaciones=' + $("#txtAnoVacaciones").val() + '&contratovacaciones=' + $("#txtContratoVacaciones").val() 
			+ '&mesaguinaldos=' + $("#txtMesAguinaldos").val() + '&anoaguinaldos=' + $("#txtAnoAguinaldos").val() 
			+ '&contratoaguinaldos=' + $("#txtContratoAguinaldos").val() + '&condicion=' + $("#txtCondicion").val() 
			+ '&zonapostal=' + $("#txtZonaPostal").val() + '&diaO=' + $("#txtDiaO").val() + '&mesO=' + $("#txtMesO").val() 
			+ '&anoO=' + $("#txtAnoO").val() + '&numoperaciones=' + $("#txtNumeroCondicion").val() 
			+ '&montocuota=' + $("#txtMontoCuota").val() + '&numero_factura=' + $("#txtNumero_Factura").val() 
			+ '&diaD=' + $("#txtDiaDescuento").val() + '&mesD=' + $("#txtMesDescuento").val() 
			+ '&anoD=' + $("#txtAnoDescuento").val() + '&numero_contrato=' + $("#txtNumero_Contrato").val() 
			+ '&formacontrato=' + $("#txtFormaContrato").val() + '&empresa=' + $("#txtEmpresa").val() 
			+ '&vacaciones_mes=' + $("#txtMesA").val() + '&observaciones=' + $("#txtObservaciones").val() 
			+ '&cobradoen=' + $("#txtCobrado").val() + '&montooperaciones=' + $("#txtMontoCheque").val() 
			+ '&montocheque2=' + $("#txtMontoCheque2").val() + '&ncheque2=' + $("#txtNCheque2").val() 
			+ '&serial=' + sSerial + '&afiliado=' + $("#txtAfiliado").val() + '&personal=' + $("#txtPersonal").val() 
			+ '&motivo=' + $("#txtMotivo").val() + '&monto_vacaciones=' + $("#txtVacaciones").val() 
			+ '&monto_aguinaldos=' + $("#txtAguinaldos").val() + '&txtNombreAsociado1=' + $("#txtNombreAsociado1").val() 
			+ '&txtTlfAsociado1=' + $("#txtTlfAsociado1").val() + '&txtObserva1=' + $("#txtObserva1").val() 
			+ '&txtEstatus1=' + $("#txtEstatus1").val() + '&txtNombreAsociado2=' + $("#txtNombreAsociado2").val() 
			+ '&txtTlfAsociado2=' + $("#txtTlfAsociado2").val() + '&txtObserva2=' + $("#txtObserva2").val() 
			+ '&txtEstatus2=' + $("#txtEstatus2").val() + '&txtNombreAsociado3=' + $("#txtNombreAsociado3").val() 
			+ '&txtTlfAsociado3=' + $("#txtTlfAsociado3").val() + '&txtObserva3=' + $("#txtObserva3").val() 
			+ '&txtEstatus3=' + $("#txtEstatus3").val() + '&txtdirecA3=' + $("#txtdirecA3").val() + '&txtdirecA2=' + $("#txtdirecA2").val()
			+ '&txtdirecA1=' + $("#txtdirecA1").val() + '&txtNomiA3=' + $("#txtNomiA3").val() + '&txtNomiA2=' + $("#txtNomiA2").val() 
			+ '&txtNomiA1=' + $("#txtNomiA1").val()+'&insti='+$("#insti").val();
			
			$("#msj_alertas").html("(+) POR FAVOR ESPERE MIENTRAS SE COMPLETA EL PROCESO...");
			$("#msj_alertas").dialog("open");
            //alert(sCadena);
			$.ajax({
				url : strUrl_Proceso,
				type : 'POST',
				data : sCadena,
				success : function(htm) {
					Limpiar_Cliente();
					Limpiar_Credito();
					$("#msj_alertas").html("(+) Felicidades el proceso finalizo correctamente...");
					$("#msj_alertas").dialog({
						buttons : {
							"Cerrar" : function() {
							$(this).dialog("close");
							}
						}
					});
					$("#msj_alertas").dialog("open");
					$('#tabs').tabs("option", "selected", 0);
	
				}
			});
		}
	}
}


function Limpiar_Cliente() {
	$("#txtCedula").val('');
	$("#txtNacionalidad").val('V-');
	$("#txtNombre1").val('');
	$("#txtNombre2").val('');
	$("#txtApellido1").val('');
	$("#txtApellido2").val('');
	$("#txtDiaNacimiento").val('Dia:');
	$("#txtMesNacimiento").val('Mes:');
	$("#txtAnoNacimiento").val('A&ntilde;o:');
	$("#txtSexo").val('MASCULINO');
	$("#txtCargo").val('');
	$("#txtTelefono").val('');
	$("#txtDireccionH").val('');
	$("#txtDireccionT").val('');
    $("#txtDireccionT2").val('');
	$("#txtCiudad").val('');
	$("#txtUbicacion").val('');
	$("#txtcuenta_1").val('');
	$("#txtcuenta_2").val('');
	$("#txtnumero_tarjeta").val('');
	$("#txtMunicipio").val('');
	$("#txtParroquia").val('');
	$("#txtSector").val('');
    $("#txtCiudad2").val('');
    $("#txtUbicacion2").val('');
	$("#txtAvenida").val('');
	$("#txtCalles").val('');
	$("#txtUrbanizacion").val('');
	$("#txtCorreo").val('');
	$("#txtPin").val('');
	$("#txtVacaciones").val('');
	$("#txtAguinaldos").val('');
	$("#txtZonaPostal").val('');
	$("#txtbanco_1").val("----------");
	$("#txtTipo_1").val("----------");
	$("#txtbanco_2").val("----------");
	$("#txtTipo_2").val("----------");
	$("#txtNombreAsociado1").val("");
	$("#txtNombreAsociado2").val("");
	$("#txtNombreAsociado3").val("");
	$("#txtTlfAsociado1").val("");
	$("#txtTlfAsociado2").val("");
	$("#txtTlfAsociado3").val("");
	$("#txtObserva1").val("");
	$("#txtObserva2").val("");
	$("#txtObserva3").val("");
	$("#txtNomiA3").val("");
	$("#txtNomiA2").val("");
	$("#txtNomiA1").val("");
	$("#txtdirecA3").val("");
	$("#txtdirecA2").val("");
	$("#txtdirecA1").val("");
	$("#txtDomiciliacionG").val("0");
	$("#txtDomiciliacionC").val("0");
	$("#insti").val(0);

}

function Limpiar_Credito() {
	$("#txtNumero_Contrato").val('');
	$("#txtCantBoucher").val('');
	$("#txtmontocredito").val('');
	$("#txtNominaProcedencia").val('----------');
	$("#txtNumeroCuotas").val(1);
	$("#txtNominaPeriocidad").val(0);
	$("#txtMontoCuota").val('');
	$("#txtDiaDescuento").val(0);
	$("#txtMesDescuento").val(0);
	
	$("#txtAnoDescuento").val(0);
	$("#txtFinDiaDescuento").val(0);
	$("#txtFinMesDescuento").val(0);
	$("#txtFinAnoDescuento").val(0);
	$("#txtMotivo").val('----------');
	$("#txtCondicion").val('----------');
	$("#txtNumeroCondicion").val('');
	$("#txtMontoCheque").val('');
	$("#txtMontoCheque2").val('');
	$("#txtDiaO").val(0);
	$("#txtMesO").val(0);
	$("#txtAnoO").val(0);
	$("#txtFormaContrato").val(0);
	$("#txtEmpresa").val('COOPERATIVA ELECTRON 465 RL.');
	$("#txtCobrado").val('----------');
	$("#txtObservaciones").val('');
	$("#txtNumero_Factura").val('');
	$("#txtModelo").val('');
	$("#txtCondicion").val(4);
	$("#txtTipoPago > option[value=5]").attr("selected","selected");
	$("#lstFechaBoucher").html('');
	$("#lstBoucher").html('');
	$("#fila_boucher").hide();
	$("#fila_boucher2").hide();
	verifica_condicion();
}

function Monto_Debe() {
	strUrl_Proceso = sUrlP + "Deuda_Cliente";
	var id = $("#txtCedula").val();
	if(id == ''){
		alert('Debe ingresar una cedula');
		return 0;
	}
	$.ajax({
		url : strUrl_Proceso,
		type : 'POST',
		data : 'id=' + id,
		dataType : 'json',
		success : function(jSon) {
			if(jSon['activo'] != 'SI'){
				$("#msj_alertas").html(jSon['resultado']);	
				$("#msj_alertas").dialog({
					buttons : {
						"Aceptar" : function() {
							$(this).dialog("close");
						}
					}
				});
				$("#msj_alertas").dialog("open");
				$("#Salvar").hide();	
			}
		}
	});
}


function consultar_clientes() {
	strUrl_Proceso = sUrlP + "DataSource_Cliente";
	var id = $("#txtCedula").val();
	if(id == ''){
		alert('Debe ingresar una cedula');
		return 0;
	}

	$("#carga_busqueda").dialog('open');
	//alert(strUrl_Proceso);
	$.ajax({
		url : strUrl_Proceso,
		type : 'POST',
		data : 'id=' + id,
		dataType : 'json',
		success : function(json) {//alert(json);
			$("#Salvar").show();
			dcumento_id = json["documento_id"];
			apellido_casada = json["apellido_casada"];
			fecha_nacimiento = json["fecha_nacimiento"];
			var fechaN = new String(fecha_nacimiento);
			var fechaAux = fechaN.split("-");
			diaN = fechaAux[2] * 1;
			mesN = fechaAux[1] * 1;
			anoN = fechaAux[0] * 1;
			fecha_ingreso = json["fecha_ingreso"];
			var fechaI = new String(fecha_ingreso);
			var fechaIAux = fechaI.split("-");
			diaI = fechaIAux[2] * 1;
			mesI = fechaIAux[1] * 1;
			anoI = fechaIAux[0] * 1;
			sexo = json["sexo"];
			celular = json["celular"];
			banco_3 = json["banco_3"];
			cuenta_3 = json["cuenta_3"];
			tipo_cuenta_3 = json["tipo_cuenta_3"];
			ente_procedencia = json["ente_procedencia"];
			disponibilidad = json["disponibilidad"];
			if (json['documento_id'] == "NULL" || json['documento_id'] == "0") {
				Limpiar_Cliente();
				Limpiar_Credito();
                $("#txtCedula").val(id);
				$("#foto").attr('src',sImg+'sinfoto.png');
			} else {
				var foto = sImgFoto + id+'.jpg';
				if(json['fotoc'] == 1){
   					$("#foto").attr('src',foto);
				}else{
					$("#foto").attr('src',sImg+'sinfoto.png');
				}
				Limpiar_Credito();
				$("#txtDomiciliacionG").val(json["grupo"]);
				$("#txtDomiciliacionC").val(json["coopera"]);
				$("#txtDomiciliacionI").val(json["interbancaria"]);
				$("#txtNombre1").val(json["primer_nombre"]);
				$("#txtNombre2").val(json["segundo_nombre"]);
				$("#txtApellido1").val(json["primer_apellido"]);
				$("#txtApellido2").val(json["segundo_apellido"]);
				$("#txtEdocivil").val(json["estado_civil"]);
				$("#txtAfiliado").val(json["fe_vida"]);
				$("#txtPersonal").val(json["titular"]);
				$("#txtDiaNacimiento").val(diaN);
				$("#txtMesNacimiento").val(mesN);
				$("#txtAnoNacimiento").val(anoN);
				$("#txtDiaI").val(diaI);
				$("#txtMesI").val(mesI);
				$("#txtAnoI").val(anoI);
				$("#txtMesA").val(json["foto"]);
				sexo_aux = "FEMENINO";
				if (sexo == "M") {
					sexo_aux = "MASCULINO";
				}
				$("#txtSexo").val(sexo_aux);
				$("#txtCargo").val(json["cargo_actual"]);
				if(typeof json["telefono"] != 'undefined'){
					var telP = json["telefono"].split('|');
					$("#txtTelefono").val(telP[0]);
					$("#txtTelefonoh").val(telP[1]);
				}
				$("#txtNacionalidad").val(json["nacionalidad"]);
				$("#txtMunicipio").val(json["municipio"]);
				$("#txtParroquia").val(json["parroquia"]);
                /*
                Para consultar ciudad y estado que vienen en el campo sector en la posicion 2 y 3  separados por '|'
                 */
                var sector = json["sector"].split('|');
                //alert(sector[0]);
				$("#txtSector").val(sector[0]);
                $("#txtCiudad2").val(sector[1]);
                $("#txtUbicacion2").val(sector[2]);

				$("#txtAvenida").val(json["avenida"]);
				$("#txtCalles").val(json["calle"]);
				$("#txtUrbanizacion").val(json["urbanizacion"]);
				$("#txtCorreo").val(json["correo"]);
				$("#txtPin").val(json["pin"]);
				$("#insti").val(json["insti"]);
				$("#txtDireccionH").val(json["direccion"]);
				$("#txtDireccionT").val(json["direccion_trabajo"]);
                $("#txtDireccionT2").val(json["direccion_trabajo2"]);
				$("#txtCiudad").val(json["ciudad"]);
				$("#txtUbicacion").val(json["ubicacion"]);
				$("#txtbanco_1").val(json["banco_1"]);
				$("#txtcuenta_1").val(json["cuenta_1"]);
				$("#txtTipo_1").val(json["tipo_cuenta_1"]);
				$("#txtbanco_2").val(json["banco_2"]);
				$("#txtcuenta_2").val(json["cuenta_2"]);
				$("#txtnumero_tarjeta").val(json["numero_tarjeta"]);
				$("#txtTipo_2").val(json["tipo_cuenta_2"]);
				$("#txtZonaPostal").val(json["gaceta"]);
				$("#txtVacaciones").val(json["monto_vacaciones"]);
				$("#txtAguinaldos").val(json["monto_aguinaldos"]);
				$("#nexpediente").val(json["nexpediente"]);
				var contactos = json["contactos"];
				var numContac = 0;
				$.each(contactos, function(sPersona, sCampo) {
					numContac++;

					$("#txtNombreAsociado" + numContac).val(sCampo['nombre']);
					$("#txtTlfAsociado" + numContac).val(sCampo['telefono']);
					$("#txtObserva" + numContac).val(sCampo['descripcion']);
					$("#txtEstatus" + numContac).val(sCampo['estatus']);
					$("#txtNomiA" + numContac).val(sCampo['nomina']);
					$("#txtdirecA" + numContac).val(sCampo['direccion']);

				});
				if (disponibilidad != 0 && disponibilidad != undefined) {
					$("#Salvar").hide();
					if (disponibilidad == 1) mensaje = "El cliente esta actualmente suspendido:";
					if (disponibilidad == 2) mensaje = "El cliente esta actualmente BLOQUEADO del sistema<br>No se le consedera ningun credito<br>Para mayor informacion Comunicarse con la oficina principal :";
					var suspencion = json["suspendido"];
					if(suspencion == 'null'){
						mensaje += 'Sin observacion (Metodo Antiguo)';
					}else{
						$.each(suspencion, function(sPos, sCampos) {
							mensaje += "<br>Peticion: "+sCampos['peticion'] + "<br>Motivo: " + sCampos['motivo'] + '<br>Fecha: ' + sCampos['fecha'] + "<br>";
						});	
					}
					
					
					$("#msj_alertas").html(mensaje);
					
					$("#msj_alertas").dialog({
						buttons : {
							"Aceptar" : function() {
								$(this).dialog("close");
							}
						}
					});
					$("#msj_alertas").dialog("open");
				}
			}
			$("#carga_busqueda").dialog('close');
		}
	});

	strUrl_Proceso = sUrlP + "DataSource_Cliente_Creditos";
	$.ajax({
		url : strUrl_Proceso,
		type : 'POST',
		data : 'documento_id=' + id,
		success : function(htm) {
			$('#divCreditos').show("blind");
			$('#divCreditosInformacion').html(htm);
		}
	});
	//Comprobar_Certificado();
	return true;
}

function Comprobar_Certificado(){
	var ced = $("#txtCedula").val();
	strUrl_Proceso = sUrlP + "wservicio";
	$.ajax({
		url : strUrl_Proceso,
		type : 'POST',
		data : 'cedula=' +ced,
		success : function(resp) {
			if(resp == 1){
				alert("El cliente SI esta certificado a traves de la pagina www.electron465.com");
			}else{
				alert("El cliente NO esta certificado. Dirijase a la pagina www.electron465.com para completar la certificacion");
			}
		}
	});
}

function consultar_creditos() {
	strUrl_Proceso = sUrlP + "DataSource_Creditos";
	var id = $("#txtCedula").val();
	var idc = $("#txtNumero_Contrato").val();
	$.ajax({
		url : strUrl_Proceso,
		type : 'POST',
		data : 'documento_id=' + id + '&contrato_id=' + idc,
		dataType : 'json',
		success : function(json) {
			Limpiar_Credito();
			contrato_id = json["contrato_id"];
			fecha_solicitud = json["fecha_solicitud"];
			var fechaS = new String(fecha_solicitud);
			var fechaAux = fechaS.split("-");
			diaS = fechaAux[2] * 1;
			mesS = fechaAux[1] * 1;
			anoS = fechaAux[0] * 1;
			fecha_inicio_cobro = json["fecha_inicio_cobro"];
			var fechaI = new String(fecha_inicio_cobro);
			var fechaAuxi = fechaI.split("-");
			diaI = fechaAuxi[2] * 1;
			mesI = fechaAuxi[1] * 1;
			anoI = fechaAuxi[0] * 1;
			cantidad = json["cantidad"];
			fecha_operacion = json["fecha_operacion"];
			var fechaO = new String(fecha_operacion);
			var fechaAuxO = fechaO.split("-");
			diaO = fechaAuxO[2] * 1;
			mesO = fechaAuxO[1] * 1;
			anoO = fechaAuxO[0] * 1;
			
			forma_pago = json['marca_consulta'];
			if (contrato_id == 0) {
				$("#txtNumero_Contrato").val(idc);
			} else {
				if (contrato_id == 1) {
					$("#msj_alertas").html("<span class=\"ui-icon ui-icon-alert\" style=\"float: left; margin-right: .3em;\"></span><strong><font color='#B70000'>El numero de contrato esta asignado a otro cliente por favor verifique e intente de nuevo...</font> </strong>");
					$("#msj_alertas").dialog('open');
				} else {
					$("#txtDiaC").val(diaS);
					$("#txtMesC").val(mesS);
					$("#txtAnoC").val(anoS);
					$("#txtmontocredito").val(json["monto_total"]);
					$("#txtNominaProcedencia").val(json["nomina_procedencia"]);
					$("#txtNumeroCuotas").val(json["numero_cuotas"]);
					$("#txtNominaPeriocidad").val(json["periocidad"]);
					$("#txtMontoCuota").val(json["monto_cuota"]);
					$("#txtDiaDescuento").val(diaI);
					$("#txtMesDescuento").val(mesI);
					$("#txtAnoDescuento").val(anoI);
					$("#txtMotivo").val(json["motivo"]);
					$("#txtCondicion").val(json["condicion"]);
					$("#txtNumeroCondicion").val(json["num_operacion"]);
					$("#txtMontoCheque").val(json["monto_operacion"]);
					$("#txtDiaO").val(diaO);
					$("#txtMesO").val(mesO);
					$("#txtAnoO").val(anoO);
					$("#txtNumero_Factura").val(json["numero_factura"]);
					$("#txtFormaContrato").val(json["forma_contrato"]);
					$("#txtEmpresa").val(json["empresa"]);
					$("#txtCobrado").val(json["cobrado_en"]);
					$("#txtObservaciones").val(json["observaciones"]);
					$("#txtNumero_Contrato").val(idc);
					$("#lstBoucher").attr("disabled", false);
					$("#txtTipoPago").attr("disabled", false);
					$("#btnAgregarBoucher").show();
					$("#txtCantBoucher").attr("disabled", false);
					$("#txtNCheque2").val(json["ncheque"]);
					$("#txtMontoCheque2").val(json["mcheque"]);
					
					if(forma_pago == 6){
						var valor_combo = '6-'+json['tvoucher'];
						$("#txtTipoPago > option[value="+ valor_combo +"]").attr("selected","selected");
						$("#lstBoucher").html();
						verifica_tipo();
						items = String(json['lstVoucher']).split(',');
						tam = items.length;
						$("#txtCantBoucher").val(tam);
						Fecha_Boucher();
						for(k = 0; k<tam;k++){
							cargar = items[k];
							aux = items[k].split('|');
							$("#lstBoucher").append(new Option(cargar, aux[0]));
						}						
					}else{
						$("#txtTipoPago > option[value=5-DOM]").attr("selected","selected");
						$("#lstBoucher").html();
						verifica_tipo();
					}
					Calcular_Fin_Descuento();
				}
			}
		}
	});
}

function fecha_especial(){
	valor = $("#txtFormaContrato").val();
	if(valor == 5 ){
		$("#txtDiaDescuento").val('1');
		$("#txtMesDescuento").val('11');
		$("#txtAnoDescuento").val('2016');
		$("#txtFinDiaDescuento").val('30');
		$("#txtFinMesDescuento").val('11');
		$("#txtFinAnoDescuento").val('2016');
	}
	
}
function valida_motivo(){
	var mot = $("#txtMotivo option:selected").val();
	var fct = $("#txtFormaContrato option:selected").val();0
	var per = $("#txtNominaPeriocidad option:selected").val()
	var mul = 1;
	if(per == 2 || per == 3){
		mul = 2;
	}
	if(mot == 2){
		var cuotas = $("#txtNumeroCuotas option:selected").val();
		if((cuotas/mul) != 12 && fct == 0){
			alert("Recuerde que los creditos FINANCIAMIENTO (PRESTAMO) son a un lapso de 12 meses.");
			var cu = 12 * mul;
			$("#txtNumeroCuotas > option[value="+cu+"]").attr("selected","selected");
			Calcular_Monto();
		}
		
	}
}

function tipo_contrato(){
	var forma = $("#txtFormaContrato option:selected").val(); 
	if( forma == 1 || forma == 2){
		$("#txtTipoPago > option[value=5-DOM]").attr("selected","selected");	
		$("#txtTipoPago > option[value!=5-DOM]").attr("disabled","true");
	}else if(forma == 6){
		$("#txtTipoPago > option[value=5-VBI]").attr("selected","selected");	
		$("#txtTipoPago > option[value!=5-VBI]").attr("disabled","true");
	}else{	
		$("#txtTipoPago").find('option').attr("disabled",false);
	}
	verifica_tipo();
}


function Validar() {
	var i = 0;
	var cond = $("#txtCondicion option:selected").val();
	var motivo = $("#txtMotivo option:selected").val();
	
	var sErr = new Array();
	var tam = $("#txtcuenta_1").val();
	var bVal = true;
	sErr['ncuenta'] = '';
	sErr['ncontrato'] = '';
	if (tam.length != 20) {
		sErr['ncuenta'] = "Err en Numero de Cuenta 1";
	}
	if ($("#txtNumero_Contrato").val() != "" && $("#txtNumero_Factura").val() == "") {
		bVal = false;
		sErr['ncontrato'] = "Inserte un n&uacuten;mero de Factura";
	}
	if(cond == 4 && $("#txtNumero_Contrato").val() != ''){
		alert("Debe Seleccionar Condicion Para La Factura");
		return 0;
	}
	if(motivo == 0 && $("#txtNumero_Contrato").val() != ''){
		alert("Debe Seleccionar Motivo Para La Factura");
		return 0;
	}
	
	if (sErr['ncuenta'] != '' || sErr['ncontrato'] != '') {
		$('#msj_alertas').html('' + sErr['ncuenta'] + '<br>' + sErr['ncontrato']);
		$('#msj_alertas').dialog({
			buttons : {
				"Aceptar" : function() {
					$(this).dialog("close");
					if (bVal == true) {
						btnGuardar();
					}
				},
				"Cancelar" : function() {
					$(this).dialog("close");
				}
			}
		});
		$("#msj_alertas").dialog("open");
	} else {
		btnGuardar();
	}
}

function btnImprimir() {
	var strCedula = $("#txtCedula").val();
	strUrl_Proceso = sUrlP + "Imprimir/" + strCedula;
	var sSerial = "";
	window.open(strUrl_Proceso, "", "");
}

function btnAfiliacionI() {
	var strCedula = $("#txtCedula").val();
	if (strCedula != '') {
		strUrl_Proceso = sUrlP + "AfiliacionI/" + strCedula;
		var sSerial = "";
		window.open(strUrl_Proceso, "", "");
	} else {
		alert("Debe ingresar una cedula....");
	}
}

function btnAfiliacion() {
	var strCedula = $("#txtCedula").val();
	if (strCedula != '') {
		strUrl_Proceso = sUrlP + "Afiliacion/" + strCedula;
		var sSerial = "";
		window.open(strUrl_Proceso, "", "");
	} else {
		alert("Debe ingresar una cedula....");
	}
}

function btnAfiliaciones() {
	var strCedula = $("#txtCedula").val();
	if (strCedula != '') {
		strUrl_Proceso = sUrlP + "Afiliaciones/" + strCedula;
		var sSerial = "";
		window.open(strUrl_Proceso, "", "");
	} else {
		alert("Debe ingresar una cedula....");
	}
}

function btnInterbancaria(){
	strUrl_Proceso = sUrlP + "Imp_Interbancaria";
	var id = $("#txtCedula").val();
	var cuenta = $("#txtcuenta_1").val();
	$.ajax({
		url : strUrl_Proceso,
		type : 'POST',
		data : 'documento_id=' + id + '&cuenta=' + cuenta,
		//dataType : 'json',
		success : function(msj) {
			$("#msj_alertas").html(msj);
			$("#msj_alertas").dialog('open');
		}
	});
}

function btnFormatoLibre(){
	strUrl_Proceso = sUrlP + "Imp_Formato_Libre";
	var id = $("#txtCedula").val();
	$.ajax({
		url : strUrl_Proceso,
		type : 'POST',
		data : 'documento_id=' + id,
		//dataType : 'json',
		success : function(msj) {
			$('#msj_alertas').dialog({
				modal : true,
				autoOpen : false,
				//position: 'top',
				hide : 'explode',
				show : 'slide',
				width : 600,
				height : 300,
				buttons : {
					"Cerrar" : function() {
						$(this).dialog("close");
					},
				}
			});
			$("#msj_alertas").html(msj);
			$("#msj_alertas").dialog('open');
		}
	});
}

function Verificar_Cuenta(strCuenta) {
	if (strCuenta.length != 20) {

		$("#msj_alertas").html("<span class=\"ui-icon ui-icon-info\" style=\"float: left; margin-right: .3em;\"></span>" + "Debe verificar el n&uacute;mero de cuenta bancario, es de 20 digitos Exactos... ");
		$("#msj_alertas").dialog('open');
	}
}

function Verifica_Fecha2(formulario, status) {
	var diaC = parseInt($("#txtDiaC").val());
	var mesC = parseInt($("#txtMesC").val());
	var anioC = $("#txtAnoC").val();
	fecha_actual = new Date();	
	dia = fecha_actual.getDate();
	mes = fecha_actual.getMonth() + 1;
	anio = fecha_actual.getFullYear();
	
	if (formulario.txtAnoDescuento.value < anioC) {
		alert('No puede ingresar una fecha menor a la de creacion del contrato....');
		$("#txtAnoDescuento").val(anioC);
		$("#txtMesDescuento").val(mesC);
		$("#txtDiaDescuento").val(diaC);
	} else if (formulario.txtAnoDescuento.value == anioC) {
		if (formulario.txtMesDescuento.value < mesC) {
			alert('No puede ingresar una fecha menor a la del contrato....');
			$("#txtMesDescuento").val(mesC);
			$("#txtDiaDescuento").val(diaC);
		} else if (formulario.txtMesDescuento.value == mesC) {
			if (formulario.txtDiaDescuento.value < diaC) {
				alert('No puede ingresar una fecha menor a la del contrato....');
				$("#txtDiaDescuento").val(diaC);
			}
		}
	}
}

function Verificar_Numero(valor) {
	if (valor == "") {
		return 0;
	} else {
		return eval(valor);
	}
}

function btnCancelar() {
	Limpiar_Cliente();
	Limpiar_Credito()
	$('#tabs').tabs("option", "selected", 0);
	Effect.Fade('divGuardarC');
	$("#txtCedula").val();
	$("#txtNumero_Contrato").val();
	Effect.Fade('divCreditos');
}

function btnDBaja() {
	var cedula = $("#txtCedula").val();
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

function Calcular_Monto() {
	intCuotas = Verificar_Numero($("#txtNumeroCuotas").val());
	dblMonto_Total = Verificar_Numero($("#txtmontocredito").val());
	dblResiduo = eval((dblMonto_Total * 1));
	dblCuota = eval(dblResiduo / intCuotas);
	$("#txtMontoCuota").val(dblCuota);
}

function Calcular_Fin_Descuento() {
	var cuotas = $("#txtNumeroCuotas").val();
	var periodo = $("#txtNominaPeriocidad").val();
	var dia_inicio = $("#txtDiaDescuento").val();
	var mes_inicio = $("#txtMesDescuento").val();
	var ano_inicio = $("#txtAnoDescuento").val();
	var dia_fin = 0;
	var mes_fin = 0;
	var ano_fin = 0;
	var base_mes = 0;
	var tiempo = 0;
	switch(periodo) {
		case '0':
			base_mes = 1 / 4;
			break;
		case '1':
			base_mes = 1 / 2;
			break;
		case '2':
			base_mes = 1 / 2;
			break;
		case '3':
			base_mes = 1 / 2;
			break;
		case '4':
			base_mes = 1;
			break;
		case '5':
			base_mes = 3;
			break;
		case '6':
			base_mes = 6;
			break;
		case '7':
			base_mes = 12;
			break;
	}
	tiempo = (cuotas - 1) * base_mes;
	tiempo_picado = String(tiempo).split('.');
	var ano_t = parseInt(parseInt(tiempo_picado[0]) / 12);
	ano_t += parseInt(ano_inicio);
	mes_t = parseInt(tiempo_picado[0]) % 12;
	dia_t = parseInt(dia_inicio);
	if (tiempo_picado[1] != null) {
		switch(parseInt(tiempo_picado[1])) {
			case 25:
				dia_t += 7;
				break;
			case 5:
				dia_t += 15;
				break;
			case 75:
				dia_t += 21;
				break;
		}
	}
	if (dia_t > 30) {
		mes_t += 1;
		diferencia = dia_t - 30;
		dia_t = diferencia;
	}

	var suma_meses = parseInt(mes_t) + parseInt(mes_inicio);
	if (suma_meses > 12) {
		ano_t += 1;
		mes_t = suma_meses - 12;
	} else {
		mes_t = suma_meses;
	}
	$("#txtFinDiaDescuento").val(dia_t);
	$("#txtFinMesDescuento").val(mes_t);
	$("#txtFinAnoDescuento").val(ano_t);
}

function BFactura() {
	var strUrl_Proceso = sUrlP + "BFactura";
	var cedula = $("#txtCedula").val();
	var num_factura = $("#txtNumero_Factura").val();
	var dia = $("#txtDiaC").val();
	var mes = $("#txtMesC").val();
	if (parseInt($("#txtDiaC").val()) < 10) {
		dia = '0' + $("#txtDiaC").val();
	}
	if (parseInt($("#txtMesC").val()) < 10) {
		mes = '0' + $("#txtMesC").val();
	}
	var fecha = $("#txtAnoC").val() + '-' + mes + '-' + dia;
	if (num_factura != '') {
		$("#img_busca_factura").show();
		$.ajax({
			url : strUrl_Proceso,
			type : 'POST',
			data : 'cedula=' + cedula + '&num_factura=' + num_factura + '&fecha=' + fecha,
			dataType : "json",
			success : function(tipo) {
				var motivo = tipo["motivo"];
				var existe = tipo['existe'];
				$("#img_busca_factura").hide();
				switch (existe) {
				case 0:
					$("#txtCondicion").attr("disabled",false);
					$("#txtDiaO").attr("disabled",false);
					$("#txtMesO").attr("disabled",false);
					$("#txtAnoO").attr("disabled",false);
					$("#txtNumeroCondicion").attr("disabled",false);
					$("#txtMontoCheque").attr("disabled",false);
					$("#txtObservaciones").attr("disabled",false);
					break;
				case 1:
					var condicion = tipo["condicion"];
					var num_operacion = tipo["num_operacion"];
					var fecha_operacion = tipo["fecha_operacion"];
					var fechaAuxO = fecha_operacion.split("-");
					var dia = fechaAuxO[2] * 1;
					var mes = fechaAuxO[1] * 1;
					var ano = fechaAuxO[0] * 1;
					var monto_cheque = tipo["monto_operacion"];
					var monto_cheque2 = tipo["mcheque"];
					var ncheque = tipo["ncheque"];
					
					
					
					var empresa = tipo['empresa'];
					var cobrado_en = tipo['cobrado_en'];
					
					$("#txtCondicion").val(condicion);
					$("#txtCondicion").attr("disabled","true");
					$("#txtDiaO").val(dia);
					$("#txtDiaO").attr("disabled","true");
					$("#txtMesO").val(mes);
					$("#txtMesO").attr("disabled","true");
					$("#txtAnoO").val(ano);
					$("#txtAnoO").attr("disabled","true");
					$("#txtNumeroCondicion").val(num_operacion);
					$("#txtNumeroCondicion").attr("disabled","true");
					$("#txtMontoCheque").val(monto_cheque);
					$("#txtMontoCheque2").val(monto_cheque2);
					$("#txtNCheque2").val(ncheque);
					$("#txtMontoCheque").attr("disabled","true");
					$("#txtMotivo").val(motivo);
					$("#txtEmpresa").val(empresa);
					$("#txtCobrado").val(cobrado_en);
					$("#txtObservaciones").val(tipo['observaciones']);
					$("#txtObservaciones").attr("disabled","true");
					break;
				case 2:
					alert("LA FACTURA ESTA ASOCIADA A OTRO CLIENTE...");
					$("#txtNumero_Factura").val("");
					$('#txtNumero_Factura').focus();
					return 0;
					break;
				case 3:
					alert("LA FACTURA TIENE OTRA FECHA DE SOLICITUD CONSULTAR CON EL GERENTE...");
					$("#txtNumero_Factura").val("");
					$('#txtNumero_Factura').focus();
					$("#txtTipoPago > option[value=5]").attr("selected","selected");
					$("#lstBoucher").html();
					return 0;
					break;

				default:
					break;
				}
				if(tipo['tipo_pago'] == 6){
					var valor_combo='6-'+tipo['tvoucher'];
					$("#txtTipoPago > option[value="+valor_combo+"]").attr("selected","selected");
					$("#lstBoucher").html();
					verifica_tipo();
					items = String(tipo['lstVoucher']).split(',');
					tam = items.length;
					$("#txtCantBoucher").val(tam);
					Fecha_Boucher();
					for(k = 0; k<tam;k++){
						cargar = items[k];
						aux = items[k].split('|');
						$("#lstBoucher").append(new Option(cargar, aux[0]));
					}
					$("#lstBoucher").attr("disabled", true);
					$("#txtTipoPago").attr("disabled", true);
					$("#btnAgregarBoucher").hide();
					$("#txtCantBoucher").attr("disabled", true);
				}else{
					$("#lstBoucher").attr("disabled", false);
					$("#txtTipoPago").attr("disabled", false);
					$("#btnAgregarBoucher").show();
					$("#txtCantBoucher").attr("disabled", false);
				}
			}
		});
	} else {
		$("#msj_alertas").html("<span class=\"ui-icon ui-icon-alert\" style=\"float: left; margin-right: .3em;\"></span><strong><font color='#B70000'>Debe ingresar un numero de factura...</font> </strong>");
		$("#msj_alertas").dialog("open");
		$("#txtNumero_Factura").val("");
		$('#txtNumero_Factura').focus();
	}
}
function verifica_banco(obj) {
	var contenido = $("#" + obj.id).val();
	var caja = "";
	if (obj.id == "txtbanco_1") {
		caja = "txtcuenta_1";
	}
	if (obj.id == "txtbanco_2") {
		caja = "txtcuenta_2";
	}
	switch($.trim(contenido)) {
		case "SOFITASA":
			$("#" + caja).val('0137');
			break;
		case "BICENTENARIO":
			$("#" + caja).val('0175');
			break;
		case "BOD":
			$("#" + caja).val('0116');
			break;
		case "PROVINCIAL":
			$("#" + caja).val('0108');
			break;
		case "VENEZUELA":
			$("#" + caja).val('0102');
			break;
		case "BANESCO":
			$("#" + caja).val('0134');
			break;
		case "INDUSTRIAL":
			$("#" + caja).val('0003');
			break;
		case "MERCANTIL":
			$("#" + caja).val('0105');
			break;
		case "FONDO COMUN":
			$("#" + caja).val('0151');
			break;
		case "DEL SUR":
			$("#" + caja).val('0157');
			break;
		case "CARONI":
			$("#" + caja).val('0128');
			break;
		case "CARIBE":
			$("#" + caja).val('0114');
			break;
		default:
			break;
	}
}

//funciones aplicadas al boucher

function verifica_tipo(){
	var valor = $("#txtTipoPago :selected").val();
	var arvalor = valor.split('-');
	if(arvalor[0] == '6' || arvalor[1]=='VBI'){
		
		$("#fila_boucher").show();
		$("#fila_boucher2").show();
		$("#fila_boucher3").show();
		var tipoP = $("#txtTipoPago :selected").text(); 
		if( tipoP != "VOUCHER - BFC" || tipoP != "DOMICILIACIOM"){
			var cad = $("#txtNumero_Contrato").val().split("-");
			$("#txtBoucher").val(cad[1].substring(2));
			$("#txtBoucher").attr("disabled", true);
			$("#lstBoucher").html('');
			
		}else{
			$("#txtBoucher").val("");
			$("#txtBoucher").attr("disabled", false);
		}
		
	}else{
		$("#lstFechaBoucher").html('');
		$("#lstBoucher").html('');
		$("#fila_boucher").hide();
		$("#fila_boucher2").hide();
		$("#fila_boucher3").hide();
	}
}

function Fecha_Boucher(){
	$("#lstFechaBoucher").html('');
	$("#lstBoucher").html('');
	$("#lstFechaBoucher").append('<option value=0>----------</option>');
	fila1 = document.getElementById("fila_boucher");
	fila2 = document.getElementById("fila_boucher2");
	/*dia = $("#txtDiaDescuento").val();
	mes = $("#txtMesDescuento").val();
	anio = $("#txtAnoDescuento").val();
	*/
	var month = $("#txtMesDescuento").val();
	var year = $("#txtAnoDescuento").val();
	var day = 1;
	var cantidad = $("#txtCantBoucher").val();
	if(parseInt(cantidad)){
		//for(i = 0 ; i < parseInt(cantidad);i++){
		for(i = 0 ; i < 36;i++){
			ano = parseInt(year);
			diaA = day;
			mesA = parseInt(month) + i;
			if (mesA > 36){
				ano+=3;
				mesA = mesA-36;
			}else if (mesA > 24){
				ano+=2;
				mesA = mesA-24;
			}else if (mesA > 12){
				ano+=1;
				mesA = mesA-12;
			}
			if(mesA < 10){
				mesA = '0'+mesA;
			}
			fechaA = ano + '-' + mesA + '-' + '01'; 
			$("#lstFechaBoucher").append('<option value='+ fechaA + '>'+ fechaA +'</option>');
		}
		
	}else{
		alert("Debe ingresar un numero en cantidad de boucher");
		$("#txtCantBoucher").val('');
	}
}

function Agrega_Boucher(){
	nboucher = $("#txtBoucher").val();
	mboucher = $("#txtMontoBoucher").val();
	if(!parseInt(mboucher)){
		alert("Debe ingresar un valor numerico en monto del boucher.");
		$("#txtMontoBoucher").val('');
		return 0;
	}
	original = $("#lstFechaBoucher option:selected").val();
	fecha = $("#lstFechaBoucher option:selected").val();
	cargar = $("#lstFechaBoucher option:selected").val() + '|' + nboucher + '|' + mboucher;
	
	
	if(nboucher == '' || cargar == undefined || cargar == 0 || fecha == 0){
		alert("Debe ingresar la fecha y el numero de Boucher que se le va a cargar al contrato");
	}else{
		$("#lstBoucher").append(new Option(cargar, original));
		//$("#lstFechaBoucher option:selected").attr("disabled", true);
		$("#lstFechaBoucher > option[value=0]").attr("selected","selected");
		
	}
	if($("#txtTipoPago :selected").text() == "VOUCHER - BFC"){
		$("#txtBoucher").val('');
	}
	
	$("#txtMontoBoucher").val('');	
}

function quitar(){
	elemento = $("#lstBoucher option:selected").val();
	$("#lstBoucher option:selected").remove();
}

function verifica_credinfo(){
	cobrado_en = $("#txtCobrado").val();
	if(cobrado_en == "CREDINFO"){
		$("#txtNominaPeriocidad > option[value=3]").attr("selected","selected");
		$("#txtNominaPeriocidad").attr("disabled", true);
	}else{
		$("#txtNominaPeriocidad > option[value=4]").attr("selected","selected");
		$("#txtNominaPeriocidad").attr("disabled", false);
	}
	
}

function verifica_condicion(){
	valor = $("#txtCondicion option:selected").val();
	$("#txtNumeroCondicion").val('');
	$("#txtNCheque2").val('');
	if(valor == '3'){
		$("#optCheques").show();
		$("#optCheques2").show();
		//$("#txtNumeroCondicion").attr("disabled","true");
		$("#txtNumeroCondicion").hide();
	}else{
		$("#optCheques > option[value='0']").attr("selected","selected");
		$("#optCheques").hide();
		$("#optCheques2").hide();
		//$("#txtNumeroCondicion").attr("disabled",false);
		$("#txtNumeroCondicion").show();
	}
}

function Contratos_Buzon(){
	var strUrl_Proceso = sUrlP + "Contratos_Buzon";
	$.ajax({
		url : strUrl_Proceso,
		type : "POST",
		success : function(data) {
			if(data > 0){
				$("#Salvar").hide();
				$("#msj_alertas").html("Existen "+ data + " contratos por aceptar en el buzon de la oficina..<br>No podra guardar contratos mientras estos esten");
				$("#msj_alertas").dialog("open");
								
			}
		}
	});
}
function N_Ventana2 (URL){
	var cedula = $("#txtCedula").val();
	if(cedula == ''){
		alert('DEBE INGRESAR CEDULA');
		return 0;
	}
	//URL = 'foto/'+cedula;	
	$("#tomar_foto").show();
	$("#modal").show();
	//toogle('none','modal','ventana');

}

function btnCarnet(){
	var cedula = $("#txtCedula").val();
	if(cedula == ''){
		alert('Debe Ingresar Una Cedula');
		return 0;
	}
	URL = 'carnet/'+cedula;
	window.open(URL,"ventana1","toolbar=0,location=1,menubar=0,scrollbars=1,resizable=1,width=450,height=400")	
}

function buscaDir(){
	var strUrl_Proceso = sUrlP + "buscaDir";
	$.ajax({
		url : strUrl_Proceso,
		type : "POST",
		data:"id="+$("#insti").val(),
		success : function(dir) {
			$("#txtDireccionT").val(dir);
		}
	});
}

function buscaDir2(){
	var strUrl_Proceso = sUrlP + "buscaDir";
	$.ajax({
		url : strUrl_Proceso,
		type : "POST",
		data:"id="+$("#insti2").val(),
		success : function(dir) {
			$("#txtDireccionT2").val(dir);
		}
	});
}
