$(function() {
	$("#divGuardarC").hide();
	$("#img_busca_factura").hide();
	
	$("#Salvar").button({
		icons : {
			primary : 'ui-icon ui-icon-folder-collapsed'
		}
	});
	$("#mbuzon").removeClass('active');
	$("#mcliente").addClass('active');
	
});
//Fin del Function Maestro

function Validar() {
	var i = 0;
	var sErr = new Array();
	
	var bVal = true;
	
	sErr['ncontrato'] = '';
	
	if ($("#txtNumero_Contrato").val() != "" && $("#txtNumero_Factura").val() == "") {
		bVal = false;
		sErr['ncontrato'] = "Inserte un n&uacuten;mero de Factura";
	}
	if (sErr['ncontrato'] != '') {
		$('#msj_alertas').html( '<br>' + sErr['ncontrato']);
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

/**
 * Funciones de Registrar Cliente Consultar
 *
 */
function btnGuardar() {
	strUrlR = sUrlP + "Guardar_Credi_Compra";
	var strCedula = $("#txtCedula").val();
	var strFactura = $("#txtNumero_Factura").val();
	var n_boucher = $("#txtCantBoucher").val();
	var strPNombre = $("#txtNomre1").val();
	var strSNombre = $("#txtApellido1").val();
	l_boucher = new Array();
	i=0;
	$("#lstBoucher option").each(function(){
       aux = $(this).text();
       l_boucher[i] = aux;
       i++;
    });
	
	$("#msj_alertas").dialog({
		buttons : {
			"Aceptar" : function() {
				$(this).dialog("close");
			}
		}
	});
	if (strCedula == "" || strFactura == "" || i != parseInt(n_boucher) || strPNombre == '' || strSNombre == '') {
		$("#msj_alertas").html("(-) Debe intruducir los campos marcados con (*), son obligatorios...Y verificar si ingreso todos los Voucher");
		$("#msj_alertas").dialog("open");
	} else {
		var sCadena = 'cedula=' + $("#txtCedula").val() + '&nombre=' + $("#txtNombre1").val() + '&nombre2=' + $("#txtNombre2").val() + '&apellido=' + $("#txtApellido1").val() + '&apellido2=' + $("#txtApellido2").val()+ '&lstBoucher=' + l_boucher+ '&nacionalidad=' + $("#txtNacionalidad").val() + '&factura=' + strFactura;

		$("#msj_alertas").html("(+) POR FAVOR ESPERE MIENTRAS SE COMPLETA EL PROCESO...");
		$("#msj_alertas").dialog("open");
		
		$.ajax({
			url : strUrlR,
			type : 'POST',
			data : sCadena,
			success : function(htm) {
				$("#msj_alertas").dialog("close");
				
				Limpiar_Cliente();
				Limpiar_Factura();
				$("#msj_alertas").html("(+) Felicidades el proceso finalizo correctamente...");
				$("#msj_alertas").dialog("open");

			}
		});
	}
}

function Limpiar_Cliente() {
	$("#txtCedula").val('');
	$("#txtNombre1").val('');
	$("#txtNombre2").val('');
	$("#txtApellido1").val('');
	$("#txtApellido2").val('');
}

function Limpiar_Factura() {
	$("#txtCantBoucher").html('');
	$("#txtNumero_Factura").val('');
	$("#lstFechaBoucher").html('');
	$("#lstBoucher").html('');
	
}

function consultar_clientes() {
	Limpiar_Factura();
	strUrl_Proceso = sUrlP + "DataSource_Cliente_CC";
	var id = $("#txtCedula").val();
	$("#carga_busqueda").dialog('open');
	$.ajax({
		url : strUrl_Proceso,
		type : 'POST',
		data : 'id=' + id,
		dataType : 'json',
		success : function(json) {
			$("#Salvar").show();
			dcumento_id = json["documento_id"];
			//disponibilidad = json["disponibilidad"];
			if (json['documento_id'] == "NULL" || json['documento_id'] == "0") {
				Limpiar_Cliente();
				Limpiar_Factura();
			} else {
				$("#txtNombre1").val(json["primer_nombre"]);
				$("#txtNombre2").val(json["segundo_nombre"]);
				$("#txtApellido1").val(json["primer_apellido"]);
				$("#txtApellido2").val(json["segundo_apellido"]);		
				/*if (disponibilidad == 1) {
					$("#Salvar").hide();
					$("#msj_alertas").html("El cliente esta actualmente suspendido");
					$("#msj_alertas").dialog({
						buttons : {
							"Aceptar" : function() {
								$(this).dialog("close");
							}
						}
					});
					$("#msj_alertas").dialog("open");
				}*/
			}
			$("#carga_busqueda").dialog('close');
		}
	});
	return true;
}


/*
 * Buscar Factura
 */
function BFactura() {
	var cedula = $("#txtCedula").val();
	var num_factura = $("#txtNumero_Factura").val();
	if (num_factura != '' && cedula != '') {
		$("#msj_alertas").html('');
		$("#carga_busqueda").dialog('open');
		$.ajax({
			url : sUrlP + "BFactura_CC",
			type : "POST",
			data : "factura=" + num_factura,
			dataType : "json",
			success : function(json) {
				var cedula_c = json["cedula"];
				if(cedula_c == ""){
					$("#msj_alertas").html("Factura Disponible....");
					$("#msj_alertas").dialog("open");
				}else{
					if(cedula == cedula_c){
						Boucher_Factura(num_factura);
					}else{
						$("#msj_alertas").html("Factura Esta Asociada Al Cliente " + cedula_c);
						$("#msj_alertas").dialog("open");
					}
				}
				$("#carga_busqueda").dialog('close');
			}
		});
		
	} else {
		$("#msj_alertas").html("<span class=\"ui-icon ui-icon-alert\" style=\"float: left; margin-right: .3em;\"></span><strong><font color='#B70000'>Debe ingresar un numero de factura y cedula...</font> </strong>");
		$("#msj_alertas").dialog("open");
		$("#txtNumero_Factura").val("");
		$('#txtNumero_Factura').focus();
	}
}

function Boucher_Factura(factura){
	$.ajax({
		url : sUrlP + "Boucher_CC",
		type : "POST",
		data : "factura=" + factura,
		dataType : "json",
		success : function(json) {
			$("#lstBoucher").html();
			items = String(json).split(',');
			tam = items.length;
			$("#txtCantBoucher").val(tam);
			Fecha_Boucher();
			for(k = 0; k<tam;k++){
				cargar = items[k];
				aux = items[k].split('|');
				$("#lstBoucher").append(new Option(cargar, aux[0]));
			}
		}
	});
	
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
	cargar = $("#lstFechaBoucher option:selected").val() + ' | ' + nboucher + ' | ' + mboucher;
	
	
	if(nboucher == '' || cargar == undefined || cargar == 0 || fecha == 0){
		alert("Debe ingresar la fecha y el numero de Boucher que se le va a cargar al contrato");
	}else{
		$("#lstBoucher").append(new Option(cargar, original));
		//$("#lstFechaBoucher option:selected").attr("disabled", true);
		$("#lstFechaBoucher > option[value=0]").attr("selected","selected");
		
	}
	$("#txtBoucher").val('');
	$("#txtMontoBoucher").val('');	
}

function quitar(){
	elemento = $("#lstBoucher option:selected").val();
	/*$("#lstFechaBoucher option").each(function(){
		if($(this).text() == elemento){
			$(this).attr("disabled", false);
		}
	});*/
	$("#lstBoucher option:selected").remove();
}

function Fecha_Boucher(){
	$("#lstFechaBoucher").html('');
	$("#lstBoucher").html('');
	$("#lstFechaBoucher").append('<option value=0>----------</option>');
	fila1 = document.getElementById("fila_boucher");
	fila2 = document.getElementById("fila_boucher2");
	
	fecha_actual = new Date();	
	dia = fecha_actual.getDate();
	mes = fecha_actual.getMonth() + 1;
	anio = fecha_actual.getFullYear();
	var day = 1;
	var cantidad = $("#txtCantBoucher").val();
	if(parseInt(cantidad)){
		//$("#txtEmpresa > option[value=1]").attr("selected","selected");
		for(i = 0 ; i < parseInt(cantidad);i++){
			ano = parseInt(anio);
			diaA = day;
			mesA = parseInt(mes) + i + 1;
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
