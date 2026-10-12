$(function() {
	Crear();
	
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
	Generar();
});

function Crear() {
	strUrl_Proceso = sUrlP + "GV_Solicitud";
	vis = new GVista(strUrl_Proceso, 'vista', 'Registro de Solicitud','');
	vis.Obtener_Json();
    vis.AsignarCeldas(2);
    vis.AsignarBotones(1);
    vis.Generar();
}



function consulta_cliente() {
	var id = $("#cedula").val();
	if (id == '') {
		alert('Debe ingresar una cedula');
		return 0;
	}
	strUrl_Proceso = sUrlP + "DataSource_Cliente";
	$.ajax({
		url : strUrl_Proceso,
		type : 'POST',
		data : 'id=' + id,
		dataType : 'json',
		success : function(json) {//alert(2);
			if (json['primer_nombre'] != null) {
				cedula = json["documento_id"];
				pnom = json["primer_nombre"];
				snom = json["segundo_nombre"];
				pape = json["primer_apellido"];
				sape = json["segundo_apellido"];
				$('#nombre').val(pnom+' '+snom+' '+pape+' '+sape);
			}else{
				$("#btng").hide();
				limpiar();
				alert("El cliente debe estar registrado..");
			}

		}
	});
	
	return true;
}

function Generar(){
	var ruta = sUrlP + "Generar_soliSRand";
	$.post( ruta, function( data ) {
	  $("#codigo").val(data);
	});
}

function limpiar(){
	$("#cedula").val('');
	$("#nombre").val('');
	$("#codigo").val('');
}