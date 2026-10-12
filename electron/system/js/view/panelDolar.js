var tabHistorialActual = "resumen";
var datosHistorial = { resumen_dias: [], bitacora: [] };
var ETIQUETAS_TIPO = { BCV: "Dólar BCV", COBRANZA: "Dólar Cobranza" };

$(function() {
	$("#mpanel").addClass('active');
	$("#sdolar").addClass('active');
	$("#txtFechaDolar").datepicker({
		dateFormat: 'yy-mm-dd',
		changeMonth: true,
		changeYear: true,
		maxDate: 0
	});
	cargarEstadoDolar();
});

function soloDecimal(e) {
	var key = e.keyCode || e.which;
	var tecla = String.fromCharCode(key);
	if (key === 8 || key === 46 || key === 37 || key === 39) {
		return true;
	}
	return /[0-9.,]/.test(tecla);
}

function bancoDolarSeleccionado() {
	return $("#cmbBancoDolar").val() || "";
}

function cambiarBancoDolar() {
	cargarEstadoDolar();
}

function cargarEstadoDolar() {
	$.ajax({
		url: sUrlPanel + "getPrecioDolarEstado",
		type: "POST",
		data: { banco: bancoDolarSeleccionado() },
		dataType: "json",
		success: function(data) {
			if (!data || data.ok === false) {
				$("#estado_dolar_bcv").html("<p>Error al cargar el estado.</p>");
				return;
			}
			var etiquetaBanco = data.banco ? data.banco : "General";
			$("#titulo_historial").data("banco", etiquetaBanco);
			renderEstadoTipo("bcv", "estado_dolar_bcv", "txtPrecioDolarBcv", data.bcv, data.hora_servidor, etiquetaBanco);
			renderEstadoTipo("cobranza", "estado_dolar_cobranza", "txtPrecioDolarCobranza", data.cobranza, data.hora_servidor, etiquetaBanco);
			$("#lbl_fecha_hoy").text("(hoy: " + data.fecha_hoy + ")");
			if (!$("#txtFechaDolar").val()) {
				$("#txtFechaDolar").val(data.fecha_hoy);
			}
			datosHistorial.resumen_dias = data.resumen_dias || [];
			datosHistorial.bitacora = data.bitacora || [];
			mostrarTabHistorial(tabHistorialActual);
		},
		error: function() {
			$("#estado_dolar_bcv").html("<p>No se pudo conectar con el servidor.</p>");
		}
	});
}

function renderEstadoTipo(tipo, idContenedor, idInput, data, horaServidor, etiquetaBanco) {
	if (!data) {
		$("#" + idContenedor).html("<p>Sin datos.</p>");
		return;
	}
	var etiqueta = (ETIQUETAS_TIPO[data.tipo] || data.tipo) + " — " + (etiquetaBanco || "General");
	var html = "";
	var cls = "box-dolar ";

	if (data.requiere_actualizacion) {
		cls += "warn";
		html += "<strong>" + etiqueta + " — Pendiente de actualización</strong><br>";
		html += "Desde las 7:00 a.m. debe registrar el precio para hoy.<br>";
	} else if (data.activo_hoy === 1) {
		cls += "ok";
		html += "<strong>" + etiqueta + " — Precio activo para hoy</strong><br>";
		html += "Bs. <strong>" + formatoPrecio(data.precio_hoy) + "</strong>";
		if (data.usuario_hoy) {
			html += " — registrado por <em>" + data.usuario_hoy + "</em>";
		}
		if (data.actualizado_hoy) {
			html += "<br><small>Última activación: " + data.actualizado_hoy + "</small>";
		}
	} else {
		cls += "warn";
		html += "<strong>" + etiqueta + " — Sin precio activo para hoy</strong><br>";
		html += "Antes de las 7:00 a.m. puede seguir vigente el precio del día anterior.<br>";
		if (data.precio_activo > 0) {
			html += "Último precio activo: Bs. <strong>" + formatoPrecio(data.precio_activo) + "</strong>";
			html += " (fecha " + data.fecha_activo + ")";
		}
	}

	html += "<br><small>Hora servidor: " + horaServidor + "</small>";
	$("#" + idContenedor).attr("class", cls).html(html);

	if (data.precio_hoy > 0 && data.requiere_actualizacion) {
		$("#" + idInput).val(formatoPrecio(data.precio_hoy));
	}
}

function mostrarTabHistorial(tab) {
	tabHistorialActual = tab;
	$("#tab_resumen, #tab_bitacora").removeClass("activo");
	if (tab === "bitacora") {
		$("#tab_bitacora").addClass("activo");
		$("#titulo_historial").text("Bitácora completa (cada registro guardado)");
		renderBitacora(datosHistorial.bitacora);
	} else {
		$("#tab_resumen").addClass("activo");
		$("#titulo_historial").text("Precio activo por día");
		renderResumenDias(datosHistorial.resumen_dias);
	}
}

function etiquetaTipo(tipo) {
	return ETIQUETAS_TIPO[tipo] || tipo || "-";
}

function renderResumenDias(lista) {
	if (!lista || lista.length === 0) {
		$("#contenedor_historial").html("<p>Sin registros en bitácora aún.</p>");
		return;
	}
	var html = "<table class='tabla-dolar'><thead><tr>";
	html += "<th>Fecha del precio</th><th>Tipo</th><th>Precio (Bs.)</th><th>Estado</th><th>Usuario</th><th>Fecha activación</th>";
	html += "</tr></thead><tbody>";
	$.each(lista, function(i, row) {
		var activo = parseInt(row.activo, 10) === 1;
		html += "<tr" + (activo ? " class='fila-activa'" : "") + ">";
		html += "<td>" + row.fecha + "</td>";
		html += "<td>" + etiquetaTipo(row.tipo) + "</td>";
		html += "<td class='num'>" + formatoPrecio(row.precio) + "</td>";
		html += "<td>" + (activo ? "Activo" : "Inactivo") + "</td>";
		html += "<td>" + (row.usuario || "-") + "</td>";
		html += "<td>" + (row.fecha_registro || "-") + "</td>";
		html += "</tr>";
	});
	html += "</tbody></table>";
	html += "<p style='font-size:12px;color:#666;margin-top:6px;'>" + lista.length + " registro(s) (día + tipo).</p>";
	$("#contenedor_historial").html(html);
}

function renderBitacora(lista) {
	if (!lista || lista.length === 0) {
		$("#contenedor_historial").html("<p>Sin registros en bitácora aún.</p>");
		return;
	}
	var html = "<table class='tabla-dolar'><thead><tr>";
	html += "<th>#</th><th>Fecha del precio</th><th>Tipo</th><th>Precio (Bs.)</th><th>Estado</th><th>Usuario</th><th>Registrado el</th>";
	html += "</tr></thead><tbody>";
	$.each(lista, function(i, row) {
		var activo = parseInt(row.activo, 10) === 1;
		html += "<tr" + (activo ? " class='fila-activa'" : "") + ">";
		html += "<td>" + row.oid + "</td>";
		html += "<td>" + row.fecha + "</td>";
		html += "<td>" + etiquetaTipo(row.tipo) + "</td>";
		html += "<td class='num'>" + formatoPrecio(row.precio) + "</td>";
		html += "<td>" + (activo ? "Activo" : "Inactivo") + "</td>";
		html += "<td>" + (row.usuario || "-") + "</td>";
		html += "<td>" + (row.fecha_registro || "-") + "</td>";
		html += "</tr>";
	});
	html += "</tbody></table>";
	html += "<p style='font-size:12px;color:#666;margin-top:6px;'>" + lista.length + " registro(s) en bitácora.</p>";
	$("#contenedor_historial").html(html);
}

function formatoPrecio(val) {
	var n = parseFloat(val);
	if (isNaN(n)) {
		return "0.00";
	}
	return n.toFixed(2);
}

function guardarPrecioDolar() {
	var precioBcv = $("#txtPrecioDolarBcv").val();
	var precioCobranza = $("#txtPrecioDolarCobranza").val();
	var fecha = $("#txtFechaDolar").val();
	if (!fecha || fecha === "") {
		alert("Seleccione la fecha a registrar.");
		return;
	}
	if (!precioBcv || precioBcv === "") {
		alert("Ingrese el precio del Dólar BCV.");
		$("#txtPrecioDolarBcv").focus();
		return;
	}
	if (!precioCobranza || precioCobranza === "") {
		alert("Ingrese el precio del Dólar Cobranza.");
		$("#txtPrecioDolarCobranza").focus();
		return;
	}
	$("#carga_busqueda").dialog("open");
	$.ajax({
		url: sUrlPanel + "guardarPrecioDolar",
		type: "POST",
		data: { precio_bcv: precioBcv, precio_cobranza: precioCobranza, fecha: fecha, banco: bancoDolarSeleccionado() },
		dataType: "json",
		success: function(data) {
			$("#carga_busqueda").dialog("close");
			if (data.ok) {
				alert(data.msg);
				if (data.estado) {
					var etiquetaBanco = data.estado.banco ? data.estado.banco : "General";
					renderEstadoTipo("bcv", "estado_dolar_bcv", "txtPrecioDolarBcv", data.estado.bcv, data.estado.hora_servidor, etiquetaBanco);
					renderEstadoTipo("cobranza", "estado_dolar_cobranza", "txtPrecioDolarCobranza", data.estado.cobranza, data.estado.hora_servidor, etiquetaBanco);
					datosHistorial.resumen_dias = data.estado.resumen_dias || [];
					datosHistorial.bitacora = data.estado.bitacora || [];
					mostrarTabHistorial(tabHistorialActual);
				} else {
					cargarEstadoDolar();
				}
			} else {
				alert(data.msg || "No se pudo guardar.");
			}
		},
		error: function() {
			$("#carga_busqueda").dialog("close");
			alert("Error al guardar el precio.");
		}
	});
}
