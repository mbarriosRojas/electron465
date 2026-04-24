$(function() {
	Crear();
	$("#fecha").datepicker({
		showOn : "button",
		buttonImage : sImg + "calendar.gif",
		buttonImageOnly : true
	});

});
function Crear() {
	strUrl_Proceso = sUrlP + "GV_Asistente1";
	$.ajax({
		url : strUrl_Proceso,
		dataType : "json",
		success : function(oBj) {//alert(oBj);
			vis = new GVista(oBj, 'formulario', 'Datos Basicos', 'botones');
			vis.SetNombre("datos");
			vis.SetCeldas(2);
			vis.SetBotones(1);
			vis.Generar();
		}
	});
}

function guardar() {
	if($('#btnGuardar').is(':hidden')){
		alert("No insista no se le puede montar contrato. Llamar a oficina principal");
		return false;
	}
	var naci = $("#nacionalidad option:selected").val();
	var pnom = $("#primer_nombre").val();
	var cedula = $("#documento_id").val();
	var snom = $("#segundo_nombre").val();
	var pape = $("#primer_apellido").val();
	var sape = $("#segundo_apellido").val();
	var sexo = $("#sexo option:selected").val();
	
	if (sexo == 0) {
		alert("Debe ingresar todos los datos");
		return false;
	}
	strUrl_Proceso = sUrlP + "Guarda_Asistente1";
	$.ajax({
		url : strUrl_Proceso,
		data : 'documento_id=' + cedula + '&primer_nombre=' + pnom + '&segundo_nombre=' + snom + '&primer_apellido=' + pape + '&segundo_apellido=' + sape + '&sexo=' + sexo + "&nacionalidad=" + naci,
		type : 'POST',
		success : function(msj) {

			$("#msj_alertas").html(msj);
			$("#msj_alertas").dialog('open');
			limpiar();
		}
	});
	return true;

}

function limpiar() {
	$("#documento_id").val('');
	$("#primer_nombre").val('');
	$("#segundo_nombre").val('');
	$("#primer_apellido").val('');
	$("#segundo_apellido").val('');
	$("#sexo > option[value='']").attr("selected", "selected");
}

function limpiar2() {
	$("#primer_nombre").val('');
	$("#segundo_nombre").val('');
	$("#primer_apellido").val('');
	$("#segundo_apellido").val('');
	$("#sexo > option[value='']").attr("selected", "selected");
}

function consulta_cliente() {

	var id = $("#documento_id").val();
	if (id == '') {
		alert('Debe ingresar una cedula');
		return 0;
	}

	/*var strUrl_Proceso = sUrlP + "Contratos_Buzon";
	$.ajax({
		url : strUrl_Proceso,
		type : "POST",
		success : function(data) {
			if (data > 0) {
				$("#btnGuardar").hide();
				$("#msj_alertas").html("Existen " + data + " contratos por aceptar en el buzon de la oficina..<br>No podra guardar contratos mientras estos esten");
				$("#msj_alertas").dialog("open");

			}
		}
	});*/
	
	
	strUrl_Proceso = sUrlP + "wservicio";
	/**
	$.ajax({
		url : strUrl_Proceso,
		type : 'POST',
		data : 'cedula=' +id,
		success : function(resp) {
			if(resp == 1){
				alert("El cliente SI esta certificado a traves de la pagina www.electron465.com");
			}else{
				$("#btnGuardar").hide();
				alert("El cliente NO esta certificado. Dirijase a la pagina www.electron465.com para completar la certificacion");
				return false;
			}
		}
	});
	**/

	strUrl_Proceso = sUrlP + "DataSource_Cliente";
	$.ajax({
		url : strUrl_Proceso,
		type : 'POST',
		data : 'id=' + id,
		dataType : 'json',
		success : function(json) {
			limpiar2();
			if (json['primer_nombre'] != null) {
				cedula = json["documento_id"];
				pnom = json["primer_nombre"];
				snom = json["segundo_nombre"];
				pape = json["primer_apellido"];
				sape = json["segundo_apellido"];
				sexo = json["sexo"];
				naci = json["nacionalidad"];

				$('#documento_id').val(cedula);
				$('#primer_nombre').val(pnom);
				$('#segundo_nombre').val(snom);
				$('#primer_apellido').val(pape);
				$('#segundo_apellido').val(sape);
				$("#sexo > option[value='" + sexo + "']").attr("selected", "selected");
				$("#nacionalidad > option[value='" + naci + "']").attr("selected", "selected");
				disponibilidad = json["disponibilidad"];
				if (disponibilidad != 0 && disponibilidad != undefined) {
					$("#btnGuardar").hide();
					if (disponibilidad == 1)
						mensaje = "El cliente esta actualmente suspendido:";
					if (disponibilidad == 2)
						mensaje = "El cliente esta actualmente BLOQUEADO del sistema<br>No se le consedera ningun credito<br>Para mayor informacion Comunicarse con la oficina principal :";
					var suspencion = json["suspendido"];
					if (suspencion == 'null') {
						mensaje += 'Sin observacion (Metodo Antiguo)';
					} else {
						$.each(suspencion, function(sPos, sCampos) {
							mensaje += "<br>Peticion: " + sCampos['peticion'] + "<br>Motivo: " + sCampos['motivo'] + '<br>Fecha: ' + sCampos['fecha'] + "<br>";
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
			}else{
				$("#btnGuardar").hide();
				alert("El cliente debe estar registrado..");
			}

		}
	});
	
	/*strUrl_Proceso = sUrlP + "Deuda_Cliente";
	$.ajax({
		url : strUrl_Proceso,
		type : 'POST',
		data : 'id=' + id,
		dataType : 'json',
		success : function(jSon) {
			if(jSon['resultado'] != 'Aceptado'){
				$("#msj_alertas").html(jSon['resultado']);	
				$("#msj_alertas").dialog({
					buttons : {
						"Aceptar" : function() {
							$(this).dialog("close");
						}
					}
				});
				$("#msj_alertas").dialog("open");
				$("#btnGuardar").hide();	
			}
		}
	});*/
	return true;
}