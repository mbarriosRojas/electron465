$(function() {
	/**
	 * Lista de Modelos
	 */
	CodigoSRand();
});

function CodigoSRand() {
	$.ajax({
		url : sUrlP + 'Generar_SRand',
		data : "id=1",
		type : "POST",
		success : function(msj) {
			$("#NumeroSerie").html("<font style='font-size:20px;'><b>CODIGO DE CERTIFICADO : <label id='certificado'>" + msj + "</label></b></font><BR>");
			$("#txtCertificado").val(msj);
		}
	});

}


function isNumberKey(evt) {
	var charCode = (evt.which) ? evt.which : event.keyCode;
	if (charCode > 31 && (charCode < 48 || charCode > 57))
		return false;
	return true;
}

function calcular() {
	forma = $("#forma option:selected").text();
	nomina = $("#txtNomina option:selected").text();
	monto = $("#txtMonto").val();
	montoD = $("#txtMontoD").val();
	montoV = $("#txtMontoV").val();
	montoA = $("#txtMontoA").val();
	montoUU = $("#txtMontoUU").val();
	ced = $("#txtCed").val();
	nom = $("#txtNom").val();
	if (monto == '' || montoD == '' || montoV == '' || montoA == '' || ced == '' || nom == '' || nomina == '') {
		alert("Debe ingresar los montos y datos Correspondientes");
	} else {
		montoT1 = (monto - montoD) / 2;
		montoT2 = (montoV) / 2;
		montoT3 = (montoA) / 2;
		mensaje = "<h2>La capacidad de endeudamiento mensual del cliente es:<br>" + montoT1 + " BS.</h2>";
		if (tipo != 0) {
			mensaje += "<br><h2>La capacidad de endeudamiento en Bono Recreacional Julio  del cliente es:<br>" + montoT2 + " BS.</h2>";
			mensaje += "<br><h2>La capacidad de endeudamiento en Noviembre del cliente es:<br>" + montoT3 + " BS.</h2>";

		} else {
			mensaje += "<br><h2>La capacidad de endeudamiento en vacaciones del cliente es:<br>" + montoT2 + " BS.</h2>";
			mensaje += "<br><h2>La capacidad de endeudamiento en aguinaldos del cliente es:<br>" + montoT3 + " BS.</h2>";

		}

		if ((nomina == 'MINISTERIO DEL PODER POPULAR PARA LA EDUCACION' || nomina == 'MINISTERIO DEL PODER POPULAR PARA LA EDUCACION - JUBILADO') && forma != 'Nomina') {
			montoT4 = montoUU / 2;
			mensaje += "<br><h2>La capacidad de endeudamiento por bonos de utiles y uniformes del cliente es:<br>" + montoT4 + " BS.</h2>";
		} else {
			$("#f5").hide();
		}
		$("#mensaje").html(mensaje);
		$("#mensaje").show();
		$("#btnImprimir").show();
		$("#firmas").show();
	}

}

function Imprimir() {

	//Cedula del Cliente
	var ced = $('#txtCed').val();
	//Nombre de la Persona
	var nom = $('#txtNom').val();
	//Empresa
	var empr = $('#txtEmpresa').val();
	//Nomina
	var nomi = $('#txtNomina').val();
	//Descripcion del Produto
	var cobr = $('#txtCobrado').val();
	//Certificado de Seguridad
	var cert = $('#certificado').html();
	
	cade = "ced=" + ced + "&nomb=" + nom + "&empr=" + empr + "&nomi=" + nomi + "&lina=" + cobr + "&cert=" + cert;

	$.ajax({
		url : sUrlP + 'Guardar_SolicitudCE',
		type : 'POST',
		data : cade,
		success : function(msj) {

		}
	});

	$("#btnCalcular").hide();
	$("#btnImprimir").hide();
	$("#lbltipo").hide();
	$("#forma").hide();

	window.print();
	$("#login_form").attr("action", sUrlP + $("#txtTipo option:selected").val());
	return true;

}

function verifica_fpago() {
	forma = $("#forma option:selected").text();
	nomina = $("#txtNomina option:selected").text();
	tipo = $("input[name='tc']:checked").val();
	$("#mensaje").html("");
	$("#firmas").show();
	$("#btnImprimir").hide();
	$("#f5").hide();
	if (forma != 'Seleccionar') {
		$("#mensual").show();
		$("#f1").show();
		$("#f3").show();
		$("#f4").show();
		$("#txtMontoV").val('');
		$("#txtMontoA").val('');
		
		
		if (forma == 'BONOEXPRESS') {
			if (nomina != 'MINISTERIO DEL PODER POPULAR PARA LA EDUCACION'){
				alert("Esta función solo existe para ministerio de educación");
			}else{
				
				
			}
		}else{
			if (forma == 'Nomina') {
				if (nomina == 'MINISTERIO DEL PODER POPULAR PARA LA EDUCACION' || nomina == 'MINISTERIO DEL PODER POPULAR PARA LA EDUCACION - JUBILADO') {
					if (tipo == 1) {
						$("#f1").hide();
						alert("Personal Del M.P.P.P.E. Credinfo No posee Jubilados");
					} else {
						$("#f1").show();
					}
					$("#f3").hide();
					$("#f4").hide();
					$("#f5").hide();
					$("#txtMontoV").val(0);
					$("#txtMontoA").val(0);
				}
			$("#f2").hide();
			$("#txtMontoD").val(0);
			$("#txtMontoUU").val(0);
		} else {
			if ((nomina == 'MINISTERIO DEL PODER POPULAR PARA LA EDUCACION' || nomina == 'MINISTERIO DEL PODER POPULAR PARA LA EDUCACION - JUBILADO') && tipo == 0) {
				$("#f5").show();
				$("#txtMontoUU").val('');
			} else {
				$("#f5").hide();
				$("#txtMontoUU").val(0);
			}
			$("#f2").show();
			$("#txtMontoD").val('');

		}
			
		}
		

	} else {
		$("#mensual").hide();
		$("#firmas").hide();

	}
}

function tipo_cliente() {
	tipo = $("input[name='tc']:checked").val();
	forma = $("#forma option:selected").text();
	nomina = $("#txtNomina option:selected").text();
	if (tipo == 0) {
		$("#lblMontoV").html("Total Vacaciones:");
		if ((nomina == 'MINISTERIO DEL PODER POPULAR PARA LA EDUCACION' || nomina == 'MINISTERIO DEL PODER POPULAR PARA LA EDUCACION - JUBILADO') && forma != 'Nomina')
			$("#f5").show();
	} else {
		$("#lblMontoV").html("Total Bono Recreacional Julio:");
		$("#f5").hide();
	}
	verifica_fpago();
}

function tipo_nomina() {
	tipo = $("input[name='tc']:checked").val();
	forma = $("#forma option:selected").text();
	nomina = $("#txtNomina option:selected").text();
	$("#mensaje").html("");
	$("#firmas").show();
	$("#btnImprimir").hide();
	if ((nomina == 'MINISTERIO DEL PODER POPULAR PARA LA EDUCACION' || nomina == 'MINISTERIO DEL PODER POPULAR PARA LA EDUCACION - JUBILADO') && forma != 'Nomina' && tipo == 0) {
		$("#f5").show();
	} else {
		$("#f5").hide();
	}
	verifica_fpago();
}

function verifica_bicentenario(){
	cobra = $("#txtCobrado option:selected").text();
	empresa = $("#txtEmpresa option:selected").val();
	if(cobra == "BICENTENARIO" && empresa == 0){
		alert("Los Contratos del Banco Bicentenario, solo se realizaron por Grupo");
		$("#txtEmpresa > option[value='1']").attr("selected","selected");
		
	}
}
