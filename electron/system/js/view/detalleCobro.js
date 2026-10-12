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
	strUrl_Proceso = sUrlP + "DTControlMensual";
	$.ajax({
		url : strUrl_Proceso,
		type : "POST",
		data : 'id=' + contrato_id,
		dataType : "json",
		success : function(oEsq2) {//alert(oEsq2);

			Grid3 = new TGrid(oEsq2, 'DivMensual', "");
			Grid3.SetNumeracion(true);
			Grid3.SetName("DivMensual");
			Grid3.SetDetalle();
			Grid3.Generar();
		}
	});
	$('#dialog').dialog("open");

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
