$(function() {
	$("#mbuzon").removeClass('active');
	$('#r_contrato').dialog({
		modal : true,
		autoOpen : false,
		width : 450,
		height : 300,
	});


});

function Respaldo_Modificar_Linaje() {
	
	contrato = $("#txtContrato_Ln").val();
	linaje = $("#cmbContrato_Ln option:selected").text();
	alert("llega"+contrato+"//"+linaje);
	if (contrato != '' && linaje != '' && linaje != 'SELECCIONE') {
		$("#r_contrato").dialog({
			buttons : {
			"Aceptar" : function() {
				Modificar_Linaje();
			},
			"Cerrar" : function() {
				$(this).dialog("close");
			}
			}
		});
		$("#r_contrato").dialog("open");
	} else {
		alert("aca");
		$("#msj_alertas").html('Debe ingresar todos los datos...');
		$("#msj_alertas").dialog('open');
	}
}


function Modificar_Linaje(){
	contrato = $("#txtContrato_Ln").val();
	linaje = $("#cmbContrato_Ln option:selected").text();
	var peticion = $("#txtRPeticion_Cont").val();
	var motivo = $("#txtRMotivo_Cont").val();
	if(peticion == '' || motivo == ''){
		$("#msj_alertas").html("<h2>DEBE INGRESAR<BR>-MOTIVO POR EL CUAL SE VA A MODIFICAR LINAJE DEL CONTRATO<BR>-NOMBRE DE LA PERSONA QUE SOLICITO LA MODIFICACION</h2> ");
		$("#msj_alertas").dialog({
			width : 500,
			height : 200,
		});
		$("#msj_alertas").dialog('open');
	}else{
		$("#txtRMotivo_Cont").val('');
		$("#txtRPeticion_Cont").val('');
		$.ajax({
			url : sUrlP + 'Modificar_Linaje',
			type : "POST",
			data : "contrato=" + contrato + "&linaje=" + linaje + "&peticion=" + peticion + "&motivo=" + motivo,
			success : function(html) {
				$("#r_contrato").dialog("close");
				$("#msj_alertas").html(html);
				$("#msj_alertas").dialog('open');
				$("#txtContrato_Ln").val('');
				
			},
			error : function(html) {
				alert('FALLO LA OPERACION '+html);
			},
		});
	}
}