$(function () {
	ListarBs();
	cargarPrecioDolarBs();
	inicializarFechasBdv();

	$("#filtro_txt_bs").dialog({
		modal: true,
		autoOpen: false,
		position: 'top',
		hide: 'explode',
		show: 'slide',
		width: 680,
		height: 620,
		open: function () {
			cargarPrecioDolarBs();
			inicializarFechasBdv();
			togglePanelVenezuelaBs();
			actualizarBotonesVenezuelaBs();
			toggleBloqueoPeriodicidadBs();
			toggleBancoNominaBs();
		},
		buttons: {
			"Generar TXT Bs.": function () {
				if (crear_txt_bs() !== false) {
					$(this).dialog("close");
				}
			},
			"Cerrar": function () {
				$(this).dialog("close");
			}
		}
	});

	$(".fecha-bdv").datepicker({
		dateFormat: 'yy-mm-dd',
		changeMonth: true,
		changeYear: true
	});
});

function MostrarDivBs(div) {
	$("#" + div).dialog('open');
}

function esBancoVenezuelaBs() {
	var banco = $("#txtBsCobrado option:selected").text();
	return banco && banco.toUpperCase().indexOf('VENEZUELA') !== -1;
}

/**
 * Cotero agrupa clientes de Venezuela/Bicentenario/Banfan por igual (mismo
 * linaje, distinto banco real de la cuenta) — sin elegir a cuál de los 3 se
 * envía, un solo archivo mezclaría cuentas de los 3 bancos.
 */
function esBancoCoteroBs() {
	var banco = $("#txtBsCobrado option:selected").text();
	return banco && banco.toUpperCase().indexOf('COTERO') !== -1;
}

function toggleBancoNominaBs() {
	$("#filaBancoNominaBs").toggle(esBancoCoteroBs());
}

function togglePanelVenezuelaBs() {
	var esVzla = esBancoVenezuelaBs();
	if (esVzla) {
		$("#panelVenezuelaBs").show();
		$("#txtBsAyudaVenezuela").show();
		$("#txtBsAyudaOtros").hide();
	} else {
		$("#panelVenezuelaBs").hide();
		$("#txtBsAyudaVenezuela").hide();
		$("#txtBsAyudaOtros").show();
	}
	actualizarBotonesVenezuelaBs();
}

function actualizarBotonesVenezuelaBs() {
	var dlg = $("#filtro_txt_bs");
	var esVzla = esBancoVenezuelaBs();
	dlg.dialog("option", "title", esVzla
		? "TXT Bolívares — Banco de Venezuela"
		: "Generar TXT de Cobranza en Bolívares");
}

function inicializarFechasBdv() {
	var hoy = $.datepicker.formatDate('yy-mm-dd', new Date());
	if (!$("#txtBsFechaEnvio").val()) {
		$("#txtBsFechaEnvio").val(hoy);
	}
	if (!$("#txtBsFechaValor").val()) {
		$("#txtBsFechaValor").val(hoy);
	}
	if (!$("#txtBsFechaCulminacion").val()) {
		var d = new Date();
		d.setDate(d.getDate() + 3);
		$("#txtBsFechaCulminacion").val($.datepicker.formatDate('yy-mm-dd', d));
	}
	if (!$("#txtBsLote").val()) {
		$("#txtBsLote").val($.datepicker.formatDate('yymmdd', new Date()));
	}
}

function datosComunesBs() {
	var $form = $("#filtro_txt_bs");
	var fecha = $form.find("#txtBsAno").val() + "-" + $form.find("#txtBsMes").val() + "-" + $form.find("#txtBsDia").val();
	return {
		empresa: $form.find("#txtBsEmpresa").val(),
		banco: $form.find("#txtBsCobrado option:selected").text(),
		nomina: $form.find("#txtBsNomina option:selected").text(),
		fcontrato: $form.find("#txtBsFormaContrato").val(),
		periodicidad: $form.find("#txtBsPeriodicidad").val(),
		fecha: fecha,
		ano: $form.find("#txtBsAno").val(),
		mes: $form.find("#txtBsMes").val(),
		picar: $form.find("#txtBsPicar").val(),
		filtroCliente: $form.find("#txtBsFiltroCliente").val(),
		bancoNomina: esBancoCoteroBs() ? $form.find("#txtBsBancoNomina").val() : ''
	};
}

function datosCabeceraVenezuela() {
	return {
		rif_letra: $("#txtBsRifLetra").val(),
		rif_numero: $("#txtBsRifNumero").val(),
		lote: $("#txtBsLote").val(),
		ref_credito_cuentas: $("#txtBsRefCuentas").val(),
		ref_credito_tdc_bdv: $("#txtBsRefTdcBdv").val(),
		ref_credito_tdc_otros: $("#txtBsRefTdcOtros").val(),
		referencia_debito: $("#txtBsReferenciaDebito").val(),
		fecha_envio: $("#txtBsFechaEnvio").val(),
		fecha_valor: $("#txtBsFechaValor").val(),
		fecha_culminacion: $("#txtBsFechaCulminacion").val()
	};
}

function validarFormularioBs(esVzla) {
	var datos = datosComunesBs();
	if (!datos.banco || datos.banco === '') {
		alert("Debe seleccionar Linaje (banco).");
		return false;
	}
	if (!datos.nomina || datos.nomina === '') {
		alert("Debe seleccionar Nómina.");
		return false;
	}
	if (esBancoCoteroBs() && !datos.bancoNomina) {
		alert("Para Cotero debe seleccionar el Banco (Nómina): Venezuela, Bicentenario o Banfan.");
		return false;
	}
	if (!esVzla && (!datos.ano || datos.ano === '0' || !datos.mes || datos.mes === '0')) {
		alert('Debe indicar "Hasta la Fecha" (mes y año).');
		return false;
	}
	if (esVzla) {
		var cab = datosCabeceraVenezuela();
		if (!cab.rif_numero) {
			alert("Indique el número de RIF de la empresa.");
			return false;
		}
		if (!cab.lote) {
			alert("Indique el número de lote.");
			return false;
		}
		if (!cab.ref_credito_cuentas && !cab.ref_credito_tdc_bdv && !cab.ref_credito_tdc_otros) {
			alert("Indique al menos una referencia de crédito.");
			return false;
		}
		if (!cab.fecha_envio || !cab.fecha_valor || !cab.fecha_culminacion) {
			alert("Complete las fechas de envío, valor y culminación.");
			return false;
		}
	}
	return true;
}

function serializarDatosBs(esVzla) {
	var datos = datosComunesBs();
	var sData = "empresa=" + datos.empresa
		+ "&banco=" + encodeURIComponent(datos.banco)
		+ "&fcontrato=" + datos.fcontrato
		+ "&periodicidad=" + datos.periodicidad
		+ "&nomina=" + encodeURIComponent(datos.nomina)
		+ "&fecha=" + encodeURIComponent(datos.fecha)
		+ "&tipo_archivo=C"
		+ "&picar=" + datos.picar
		+ "&filtro_cliente=" + encodeURIComponent(datos.filtroCliente)
		+ "&banco_nomina=" + encodeURIComponent(datos.bancoNomina);
	if (esVzla) {
		var cab = datosCabeceraVenezuela();
		sData += "&rif_letra=" + encodeURIComponent(cab.rif_letra)
			+ "&rif_numero=" + encodeURIComponent(cab.rif_numero)
			+ "&lote=" + encodeURIComponent(cab.lote)
			+ "&ref_credito_cuentas=" + encodeURIComponent(cab.ref_credito_cuentas)
			+ "&ref_credito_tdc_bdv=" + encodeURIComponent(cab.ref_credito_tdc_bdv)
			+ "&ref_credito_tdc_otros=" + encodeURIComponent(cab.ref_credito_tdc_otros)
			+ "&referencia_debito=" + encodeURIComponent(cab.referencia_debito)
			+ "&fecha_envio=" + encodeURIComponent(cab.fecha_envio)
			+ "&fecha_valor=" + encodeURIComponent(cab.fecha_valor)
			+ "&fecha_culminacion=" + encodeURIComponent(cab.fecha_culminacion);
	}
	return sData;
}

// Bancos habilitados para "TXT Bolívares" por ahora — el resto se lista pero
// queda inactivo (sin datos reales/config todavía). Ver docs/HUs-generar-txt-banfan-bicentenario-dolar-cobranza.md.
var BANCOS_ACTIVOS_BS = ['BANFAN', 'BICENTENARIO', 'VENEZUELA', 'COTERO'];

/**
 * Regla de oro Morosos: la Periodicidad no se elige — el motor siempre cobra
 * juntas Mensual, Mensual-25, Mensual-15 y Mensual-30, sin importar el linaje.
 * Aquí solo se bloquea visualmente el select para que el usuario no piense
 * que su elección aplica; el valor real ya se ignora en el backend.
 */
function toggleBloqueoPeriodicidadBs() {
	var esMorosos = $("#txtBsFiltroCliente").val() === 'MOROSOS';
	$("#txtBsPeriodicidad").prop('disabled', esMorosos);
	$("#notaPeriodicidadMorosos").toggle(esMorosos);
}

function ListarBs() {
	$("#txtBsCobrado").find('option').remove().end();
	$.ajax({
		url: sUrlP + "Listar",
		dataType: "json",
		success: function (data) {
			$("#txtBsCobrado").append(new Option('', ''));
			$.each(data['linaje'], function (item, valor) {
				var nombre = (valor['valor'] || '').toUpperCase().trim();
				var activo = BANCOS_ACTIVOS_BS.indexOf(nombre) !== -1;
				var etiqueta = activo ? valor['valor'] : valor['valor'] + ' (inactivo)';
				var opt = new Option(etiqueta, valor['id']);
				if (!activo) {
					opt.disabled = true;
				}
				$("#txtBsCobrado").append(opt);
			});
		}
	});
}

/**
 * Habilita/deshabilita el botón "Generar TXT Bs." — se deshabilita mientras
 * no haya un Dólar Cobranza vigente confirmado, para no dejar generar un
 * archivo con montos en Bs. calculados con una tasa inexistente o vieja.
 */
function habilitarBotonGenerarBs(habilitar) {
	var $boton = $("#filtro_txt_bs").parent().find("button:contains('Generar TXT Bs.')");
	$boton.prop('disabled', !habilitar);
	$boton.toggleClass('ui-state-disabled', !habilitar);
}

function cargarPrecioDolarBs() {
	habilitarBotonGenerarBs(false);
	$.ajax({
		url: sUrlPanel + "getPrecioDolarVigente",
		type: "POST",
		data: { tipo: "COBRANZA" },
		dataType: "json",
		success: function (data) {
			if (data.ok && parseFloat(data.precio) > 0) {
				$("#txtBsPrecioInfo").html(
					'<strong>D&oacute;lar Cobranza vigente:</strong> '
					+ parseFloat(data.precio).toLocaleString('es-VE', { minimumFractionDigits: 4, maximumFractionDigits: 4 })
					+ ' Bs./USD &nbsp;|&nbsp; Fecha: <strong>' + data.fecha + '</strong>'
				);
				habilitarBotonGenerarBs(true);
			} else {
				$("#txtBsPrecioInfo").html(
					'<span style="color:#b70000;"><strong>Sin D&oacute;lar Cobranza vigente.</strong> '
					+ 'Reg&iacute;strelo en Panel de Control &rarr; Precio del D&oacute;lar.</span>'
				);
				habilitarBotonGenerarBs(false);
			}
		},
		error: function () {
			$("#txtBsPrecioInfo").html('<span style="color:#b70000;">No se pudo consultar el D&oacute;lar Cobranza.</span>');
			habilitarBotonGenerarBs(false);
		}
	});
}

function crear_txt_bs() {
	var esVzla = esBancoVenezuelaBs();
	if (!validarFormularioBs(esVzla)) {
		return false;
	}

	$("#carga_busqueda").dialog('open');
	$.ajax({
		url: sUrlCobranza + "GtxtBs",
		type: "POST",
		data: serializarDatosBs(esVzla),
		timeout: 180000,
		success: function (respuesta) {
			$("#carga_busqueda").dialog('close');
			$("#msj_alertas").html(respuesta);
			$("#msj_alertas").dialog('open');
		},
		error: function (xhr, status) {
			$("#carga_busqueda").dialog('close');
			var msg = status === 'timeout'
				? 'La generación tardó demasiado. Intente con filtros más específicos.'
				: 'Error al generar el TXT en bolívares.';
			$("#msj_alertas").html('<h2>' + msg + '</h2>');
			$("#msj_alertas").dialog('open');
		}
	});
	return true;
}

function muestra_nomina_bs() {
	var banco = $("#txtBsCobrado option:selected").text();
	$("#txtBsNomina").find('option').remove().end();
	$.ajax({
		url: sUrlP + "Nomina_Banco",
		type: "POST",
		data: "banco=" + encodeURIComponent(banco),
		dataType: "json",
		success: function (data) {
			$.each(data, function (item, valor) {
				$("#txtBsNomina").append(new Option(valor, valor));
			});
			$("#txtBsNomina").append(new Option('VARIAS', 'VARIAS'));
		}
	});
}
