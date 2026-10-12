$(function () {
    $("#mbuzon").removeClass('active');
    $("#mreporte").addClass('active');

    $(".dialogo").dialog({
        modal: true,
        autoOpen: false,
        position: 'top',
        hide: 'explode',
        show: 'slide',
        width: 620,
        height: 320
    });
    $("#msj_alertas").dialog({
        modal: true,
        autoOpen: false,
        position: 'top',
        hide: 'explode',
        show: 'slide',
        width: 600,
        height: 400,
        buttons: {
            "Cerrar": function () {
                $(this).html('');
                $(this).dialog("close");
            }
        }
    });
    $("#filtro_respuesta").dialog({
        open: function () {
            cambiarBancoRB();
        },
        buttons: {
            "Cargar": function () {
                if (cargarRespuesta() !== false) {
                    $(this).dialog("close");
                }
            },
            "Cerrar": function () {
                $(this).dialog("close");
            }
        }
    });
    $("#frmConsultarRB").dialog({
        buttons: {
            "Ver": function () {
                consultarRespuesta();
                $(this).dialog("close");
            },
            "Cerrar": function () {
                $(this).dialog("close");
            }
        }
    });
    $("#asignarContratoRB").dialog({
        modal: true,
        autoOpen: false,
        position: 'top',
        appendTo: 'body',
        hide: 'fade',
        title: 'Asignar Contrato',
        buttons: {
            "Guardar": function () {
                var dlg = $(this);
                guardarCambioRB(function () {
                    dlg.dialog("close");
                });
            },
            "Cerrar": function () {
                $(this).dialog("close");
            }
        }
    });

    // --- Bloque Venezuela: mismo patrón, endpoints propios (ver
    // respuestaBanco.js arriba: cargarRespuestaVenezuela, consultarTxtVE, etc.) ---
    $("#mesPagar").datepicker({ dateFormat: 'yy-mm-dd' });
    $("#frmConsultarVE").dialog({
        buttons: {
            "Ver": function () {
                consultarTxtVE();
                $(this).dialog("close");
            },
            "Cerrar": function () {
                $(this).dialog("close");
            }
        }
    });
    $("#asignarContrato").dialog({
        modal: true,
        autoOpen: false,
        position: 'top',
        appendTo: 'body',
        hide: 'fade',
        title: 'Asignar Contrato (Venezuela)',
        buttons: {
            "Guardar": function () {
                var dlg = $(this);
                guardarCambioVE(function () {
                    dlg.dialog("close");
                });
            },
            "Cerrar": function () {
                $(this).dialog("close");
            }
        }
    });
    listaArchivosVE(0);
    $("#historicoRB").dialog({
        modal: true,
        autoOpen: false,
        position: 'top',
        appendTo: 'body',
        hide: 'fade',
        resizable: true,
        width: Math.min(1500, $(window).width() - 60),
        height: Math.min(900, $(window).height() - 60),
        open: function () {
            cargarHistoricoRB();
        },
        buttons: {
            "Cerrar": function () {
                $(this).dialog("close");
            }
        }
    });
    // Detalle en su propia ventana, encima del histórico: con cientos de
    // archivos en la lista, pintarlo debajo obligaba a bajar hasta el final.
    $("#historicoDetalleRB").dialog({
        modal: true,
        autoOpen: false,
        position: 'top',
        appendTo: 'body',
        hide: 'fade',
        resizable: true,
        width: Math.min(1400, $(window).width() - 40),
        height: Math.min(850, $(window).height() - 40),
        close: function () {
            $("#HistoricoDetalleAviso").html('');
            $("#HistoricoDetalle").html('');
            $("#HistoricoDetalleRechazos").html('');
        },
        buttons: {
            "Cerrar": function () {
                $(this).dialog("close");
            }
        }
    });
    $("#buscarCedulaRB").dialog({
        modal: true,
        autoOpen: false,
        position: 'top',
        appendTo: 'body',
        hide: 'fade',
        width: 800,
        height: 550,
        buttons: {
            "Cerrar": function () {
                $(this).dialog("close");
            }
        }
    });

    var oidaRecordado = sessionStorage.getItem('respuestaBanco_oida');
    sessionStorage.removeItem('respuestaBanco_oida');
    listaArchivosRB(oidaRecordado);

    window.onGridGuardadoOk = function (funcion) {
        if (funcion === 'GuardarLoteRespuestaBanco') {
            reiniciarPantallaRB();
        } else if (funcion === 'Guardar_Lote_Venezuela') {
            reiniciarPantallaVE();
        }
    };

    // El botón "Guardar" de la grilla (TGrid Editable) limpia la tabla del
    // DOM apenas se hace clic, sin esperar la respuesta del servidor — sin
    // este aviso el usuario no tiene forma de saber si quedó procesando o
    // si falló. Se engancha por URL (en vez de tocar el TGrid genérico,
    // compartido por toda la aplicación) para mostrar el spinner mientras
    // dura el guardado y cerrarlo siempre al terminar (éxito, rechazo o error).
    // Cubre ambos lotes (genérico y Venezuela) — mismo patrón, dos endpoints.
    var urlsLoteConSpinner = ['GuardarLoteRespuestaBanco', 'Guardar_Lote_Venezuela'];
    function esUrlDeLote(url) {
        return url && urlsLoteConSpinner.some(function (u) { return url.indexOf(u) !== -1; });
    }
    $(document).on('ajaxSend', function (event, jqXHR, settings) {
        if (esUrlDeLote(settings.url)) {
            $("#carga_busqueda").dialog('open');
        }
    });
    $(document).on('ajaxComplete', function (event, jqXHR, settings) {
        if (esUrlDeLote(settings.url)) {
            $("#carga_busqueda").dialog('close');
        }
    });
    $(document).on('ajaxError', function (event, jqXHR, settings) {
        if (esUrlDeLote(settings.url)) {
            $("#msj_alertas").html('<h2>No se pudo guardar el lote (error de conexión o del servidor). '
                + 'Verifique e intente de nuevo antes de asumir que se procesó.</h2>');
            $("#msj_alertas").dialog('open');
        }
    });
});

function apiCobranza() {
    return (typeof sUrlCobranza !== 'undefined') ? sUrlCobranza : sUrlP;
}

function MostrarDivRB(div) {
    $("#" + div).dialog('open');
}

function cambiarBancoRB() {
    var banco = $("#txtRBBanco").val();
    var esVenezuela = (banco === 'VENEZUELA');
    // "Tipo de Cobranza" (Regular/Moroso) aplica a los 3 bancos, siempre visible.
    $("#filaArchivoRB").toggle(!esVenezuela);
    $("#filaVenezuelaPeriodicidadRB, #filaVenezuelaArchivoRB").toggle(esVenezuela);
    // Limpiar el input que queda oculto para no dejar un archivo "fantasma"
    // seleccionado en el campo que no corresponde al banco actual.
    if (esVenezuela) {
        $("#archivoRB").val('');
    } else {
        $("#archivoVeRB").val('');
    }
}

function listaArchivosRB(seleccionar) {
    $("#cmbArchivosRB").html('');
    $.ajax({
        url: apiCobranza() + "cmbArchivosRespuestaBanco",
        type: "POST",
        dataType: "json",
        success: function (data) {
            $.each(data, function (i, item) {
                var opt = new Option(item.valor, item.id);
                $(opt).data('banco', item.banco);
                $("#cmbArchivosRB").append(opt);
            });
            $("#cmbArchivosRB").append(new Option("Seleccione Archivo", "0"));
            // "0" es el valor de "Seleccione Archivo" (sin selección real) —
            // reiniciarPantallaRB() llama listaArchivosRB(0) solo para
            // refrescar el combo tras guardar, no para forzar una consulta.
            if (seleccionar !== undefined && seleccionar !== null && seleccionar != 0) {
                $("#cmbArchivosRB").val(String(seleccionar));
                consultarRespuesta();
            }
        }
    });
}

function reiniciarPantallaRB() {
    $("#Respuesta").html('');
    $("#RespuestaRechazos").html('');
    listaArchivosRB(0);
}

function cargarRespuesta() {
    var banco = $("#txtRBBanco").val();
    if (!banco) {
        alert("Debe seleccionar un banco.");
        return false;
    }
    if (banco === 'VENEZUELA') {
        return cargarRespuestaVenezuela();
    }

    var inputFile = document.getElementById("archivoRB");
    var file = inputFile.files[0];
    if (!file) {
        alert("Debe seleccionar un archivo.");
        return false;
    }
    var nombre = $("#archivoRB").val();
    var extension = (nombre.substring(nombre.lastIndexOf("."))).toLowerCase();
    if (extension !== '.txt') {
        alert("Formato de archivo inválido. Debe ser .txt");
        return false;
    }
    var tipoCobranza = $("#txtRBTipoCobranza").val();

    $("#carga_busqueda").dialog('open');
    var datos = new FormData();
    datos.append('archivo', file);
    datos.append('banco', banco);
    datos.append('tipo_cobranza', tipoCobranza);

    $.ajax({
        url: apiCobranza() + "subirRespuestaBanco",
        type: "POST",
        data: datos,
        contentType: false,
        processData: false,
        cache: false,
        dataType: "json",
        success: function (respuesta) {
            if (respuesta.ok != 'si') {
                $("#carga_busqueda").dialog('close');
                $("#msj_alertas").html("<h2>" + respuesta.msg + "</h2>");
                $("#msj_alertas").dialog('open');
                return;
            }
            $("#carga_busqueda").dialog('close');
            if (respuesta.duplicado_advertencia
                && !confirm("⚠️ " + respuesta.duplicado_advertencia + "\n\n¿Desea continuar de todos modos?")) {
                return;
            }
            $("#carga_busqueda").dialog('open');
            leerRespuestaBancoAjax(respuesta.msg.id, respuesta.msg.archivo);
        },
        error: function () {
            $("#carga_busqueda").dialog('close');
            $("#msj_alertas").html("<h2>Error al subir el archivo.</h2>");
            $("#msj_alertas").dialog('open');
        }
    });
    return true;
}

function leerRespuestaBancoAjax(id, archivo) {
    $.ajax({
        url: apiCobranza() + 'leerRespuestaBanco',
        type: "POST",
        data: 'id=' + id + '&archivo=' + encodeURIComponent(archivo),
        timeout: 120000,
        success: function (respuesta) {
            $("#carga_busqueda").dialog('close');
            $("#msj_alertas").html("<h2>" + respuesta + "</h2>");
            $("#msj_alertas").dialog('open');
            listaArchivosRB(id);
        },
        error: function (xhr, status) {
            $("#carga_busqueda").dialog('close');
            var msg = status === 'timeout'
                ? 'La lectura del archivo tardó demasiado. Intente de nuevo.'
                : 'Error al leer el archivo (código ' + (xhr.status || '?') + '). Revise la consola/Network para más detalle.';
            $("#msj_alertas").html('<h2>' + msg + '</h2>');
            $("#msj_alertas").dialog('open');
        }
    });
}

/**
 * --- Bloque Banco de Venezuela (mismo diálogo "Cargar Respuesta", flujo
 * propio) --- Independiente del genérico de arriba: su propio parser XLSX,
 * su propia tabla de staging, y conversión Bs.<->USD por fila (tasa BCV o
 * Cobranza, según corresponda, de la fecha de cobro de CADA registro, no
 * una sola tasa para todo el archivo).
 */
function cargarRespuestaVenezuela() {
    var inputFile = document.getElementById("archivoVeRB");
    var file = inputFile.files[0];
    if (!file) {
        alert("Debe seleccionar un archivo.");
        return false;
    }
    var nombre = $("#archivoVeRB").val();
    var extension = (nombre.substring(nombre.lastIndexOf("."))).toLowerCase();
    if (extension !== '.xlsx') {
        alert("Formato de archivo inválido. Debe ser .xlsx");
        return false;
    }

    var tipoCobranza = $("#txtRBTipoCobranza").val();
    var periocidad = $("#txtVePeriodicidad").val();

    $("#carga_busqueda").dialog('open');
    var datos = new FormData();
    datos.append('archivo', file);
    datos.append('banco', 'venezuela');
    datos.append('tipo_cobranza', tipoCobranza);
    datos.append('periocidad', periocidad);

    $.ajax({
        url: apiCobranza() + "subirVenezuela",
        type: "POST",
        data: datos,
        contentType: false,
        processData: false,
        cache: false,
        dataType: "json",
        success: function (respuesta) {
            if (respuesta.ok != 'si') {
                $("#carga_busqueda").dialog('close');
                $("#msj_alertas").html("<h2>" + (respuesta.msg || 'Error al subir el archivo.') + "</h2>");
                $("#msj_alertas").dialog('open');
                return;
            }
            // Ya no se muestra "tasa aplicada" aquí: esa tasa es solo un
            // metadato de la fecha de subida, nunca la que realmente se usa
            // para calcular — cada fila resuelve la suya (BCV o Cobranza)
            // con su propia fecha de cobro real (ver grid()).
            leerArchivoVenezuelaAjax(respuesta.msg.id, respuesta.msg.archivo, '');
        },
        error: function () {
            $("#carga_busqueda").dialog('close');
            $("#msj_alertas").html("<h2>Error al subir el archivo.</h2>");
            $("#msj_alertas").dialog('open');
        }
    });
    return true;
}

function leerArchivoVenezuelaAjax(id, archivo, extraTasa) {
    $.ajax({
        url: apiCobranza() + 'leerArchivoVenezuela',
        type: "POST",
        data: 'id=' + id + '&archivo=' + encodeURIComponent(archivo),
        timeout: 120000,
        success: function (respuesta) {
            validarTasasVenezuela(id, function (avisoTasas) {
                $("#carga_busqueda").dialog('close');
                $("#msj_alertas").html("<h2>" + respuesta + "</h2>" + (extraTasa || '') + avisoTasas);
                $("#msj_alertas").dialog('open');
                listaArchivosVE(id);
            });
        },
        error: function (xhr, status) {
            $("#carga_busqueda").dialog('close');
            var msg = status === 'timeout'
                ? 'La lectura del archivo tardó demasiado. Intente de nuevo.'
                : 'Error al leer el archivo (código ' + (xhr.status || '?') + ').';
            $("#msj_alertas").html('<h2>' + msg + '</h2>');
            $("#msj_alertas").dialog('open');
        }
    });
}

/**
 * Pre-chequeo ANTES de que el operador asigne contratos: revisa todas las
 * fechas de cobro distintas que trajo el archivo y advierte de una vez si a
 * alguna le falta la tasa BCV — para no descubrirlo recién al final (al
 * "Guardar Lote"), después de ya haber asignado contratos a mano.
 */
function validarTasasVenezuela(oida, callback) {
    $.ajax({
        url: apiCobranza() + 'validarTasasVenezuela',
        type: "POST",
        data: 'oida=' + oida,
        dataType: "json",
        success: function (data) {
            var aviso = '';
            if (data.ok && data.faltantes && data.faltantes.length > 0) {
                aviso = '<div style="margin-top:10px;padding:8px;background:#fff3cd;border:1px solid #ffc107;'
                    + 'border-radius:4px;color:#7a5b00;">'
                    + '<strong>⚠ Atención:</strong> este archivo tiene fechas de cobro sin tasa BCV registrada — '
                    + 'no podrá "Guardar Lote" hasta registrarlas en Panel &rarr; Precio del Dólar:<br>'
                    + '<b>' + data.faltantes.join(', ') + '</b></div>';
            }
            callback(aviso);
        },
        error: function () {
            callback('');
        }
    });
}

function listaArchivosVE(seleccionar) {
    $("#cmbArchivosVE").html('');
    $.ajax({
        url: apiCobranza() + "cmbArchivosVenezuela",
        dataType: "json",
        success: function (data) {
            $.each(data, function (item, valor) {
                $("#cmbArchivosVE").append(new Option(valor, item));
            });
            $("#cmbArchivosVE").append(new Option("Seleccione Archivo", "0"));
            if (seleccionar !== undefined && seleccionar !== null && seleccionar != 0) {
                $("#cmbArchivosVE").val(String(seleccionar));
                consultarTxtVE();
            }
        }
    });
}

function reiniciarPantallaVE() {
    $("#Respuesta").html('');
    listaArchivosVE(0);
}

function consultarTxtVE() {
    var oida = $("#cmbArchivosVE").val();
    var arch = $("#cmbArchivosVE option:selected").text();
    if (!oida || oida == 0) {
        alert('Debe seleccionar un archivo');
        return false;
    }
    $("#Respuesta").html('');
    $.ajax({
        url: apiCobranza() + "listarArchivoVenezuela",
        type: "POST",
        data: "oida=" + oida,
        dataType: "json",
        success: function (oEsq) {
            Grid = new TGrid(oEsq, 'Respuesta', 'Archivo: ' + arch);
            Grid.SetName("Archivo");
            Grid.SetNumeracion(true);
            Grid.Generar();
        }
    });
}

// Llamado por el propio TGrid (server-side, mcargavenezuela.php::grid()) por
// nombre literal — no renombrar.
function cargarCuota(id, cedula) {
    $("#idcuota").val(id);
    $("#cedulacuota").val(cedula);
    cargarContratosVE(cedula);
}

// Llamado por el propio TGrid (server-side, mcargavenezuela.php::grid()) por
// nombre literal — no renombrar. Quita la fila del lote antes de Guardar.
function eliminarFilaVenezuela(oid) {
    if (!confirm('¿Quitar este cliente del lote? No se le aplicará ningún cobro con esta carga.')) {
        return;
    }
    $.ajax({
        url: apiCobranza() + "eliminarFilaVenezuela",
        type: "POST",
        data: "oid=" + oid,
        dataType: "json",
        success: function (data) {
            if (data.ok) {
                consultarTxtVE();
            } else {
                alert('No se pudo quitar la fila.');
            }
        },
        error: function () {
            alert('Error al quitar la fila.');
        }
    });
}

function cargarContratosVE(ced) {
    $("#cmbContratos").html('');
    $.ajax({
        url: apiCobranza() + "cmbContratosVenezuela",
        type: "post",
        data: "cedula=" + ced,
        dataType: "json",
        success: function (data) {
            $.each(data, function (item, valor) {
                $("#cmbContratos").append(new Option(valor, item));
            });
            $("#cmbContratos").append(new Option("Seleccione Contrato", "0"));
            $("#asignarContrato").dialog('open');
        }
    });
}

function guardarCambioVE(onSuccess) {
    var oid = $("#idcuota").val();
    var contrato = $("#cmbContratos").val();
    var frec = $("#mesPagar").val();
    var obser = $("#obser").val();
    if (contrato == 0) {
        alert("Debe seleccionar un contrato");
        return false;
    }
    $.ajax({
        url: apiCobranza() + 'modCuotaVenezuela',
        type: "POST",
        data: 'oid=' + oid + '&contrato=' + contrato + "&frec=" + encodeURIComponent(frec) + "&obser=" + encodeURIComponent(obser),
        success: function (respuesta) {
            alert(respuesta);
            if (typeof onSuccess === 'function') {
                onSuccess();
            }
            consultarTxtVE();
        }
    });
    return true;
}

function consultarRespuesta() {
    var oida = $("#cmbArchivosRB").val();
    var arch = $("#cmbArchivosRB option:selected").text();
    if (!oida || oida == 0) {
        alert('Debe seleccionar un archivo');
        return false;
    }
    $("#Respuesta").html('');
    $("#RespuestaRechazos").html('');
    $.ajax({
        url: apiCobranza() + "listarRespuestaBanco",
        type: "POST",
        data: "oida=" + oida,
        dataType: "json",
        success: function (oEsq) {
            Grid = new TGrid(oEsq, 'Respuesta', 'Cobros aplicados — Archivo: ' + arch);
            Grid.SetName("Respuesta");
            Grid.SetNumeracion(true);
            Grid.Generar();

            // TGrid asigna el clic de "Guardar" como propiedad .onclick directa
            // (no vía jQuery), y ahí mismo borra la tabla del DOM apenas se
            // hace clic, sin esperar la respuesta del servidor — se ve como
            // si la pantalla quedara en blanco. Se reasigna esa misma
            // propiedad (reemplazándola por completo, no agregando un
            // handler adicional) solo en esta grilla, sin tocar tgrid.js
            // (compartido por toda la app), para que la tabla siga visible
            // mientras se guarda y solo se limpie al confirmarse el
            // resultado (ver window.onGridGuardadoOk).
            if (oEsq.Editable) {
                $("#Respuesta button.boton_adelante").each(function () {
                    this.onclick = function () {
                        Editar_Tabla('Respuesta', oEsq.Editable, oEsq.Parametros);
                    };
                });
            }
        }
    });
    $.ajax({
        url: apiCobranza() + "listarRespuestaBancoRechazos",
        type: "POST",
        data: "oida=" + oida,
        dataType: "json",
        success: function (oEsq) {
            GridR = new TGrid(oEsq, 'RespuestaRechazos', 'Rechazados por el banco (informativo)');
            GridR.SetName("Rechazos");
            GridR.SetNumeracion(true);
            GridR.Generar();
        }
    });
}

function cargarCuotaRespuesta(id, cedula) {
    var banco = $("#cmbArchivosRB option:selected").data('banco');
    if (!banco) {
        // Respaldo: extraer del texto "archivo (BANCO)"
        var texto = $("#cmbArchivosRB option:selected").text();
        var m = texto.match(/\(([^)]+)\)\s*$/);
        banco = m ? m[1] : '';
    }
    $("#idcuotaRB").val(id);
    $("#cedulacuotaRB").val(cedula);
    $("#bancocuotaRB").val(banco);
    cargarContratosRB(cedula, banco);
}

// Llamado por el propio TGrid (server-side, mrespuestabanco.php::grid()) por
// nombre literal — no renombrar. Quita la fila del lote antes de Guardar.
function eliminarFilaRespuestaBanco(oid) {
    if (!confirm('¿Quitar este cliente del lote? No se le aplicará ningún cobro con esta carga.')) {
        return;
    }
    $.ajax({
        url: apiCobranza() + "eliminarFilaRespuestaBanco",
        type: "POST",
        data: "oid=" + oid,
        dataType: "json",
        success: function (data) {
            if (data.ok) {
                consultarRespuesta();
            } else {
                alert('No se pudo quitar la fila.');
            }
        },
        error: function () {
            alert('Error al quitar la fila.');
        }
    });
}

function cargarContratosRB(ced, banco) {
    $("#cmbContratosRB").html('');
    $.ajax({
        url: apiCobranza() + "cmbContratosRespuestaBanco",
        type: "post",
        data: "cedula=" + ced + "&banco=" + encodeURIComponent(banco),
        dataType: "json",
        success: function (data) {
            $.each(data, function (item, valor) {
                $("#cmbContratosRB").append(new Option(valor, item));
            });
            $("#cmbContratosRB").append(new Option("Seleccione Contrato", "0"));
            $("#asignarContratoRB").dialog('open');
        }
    });
}

function guardarCambioRB(onSuccess) {
    var oid = $("#idcuotaRB").val();
    var contrato = $("#cmbContratosRB").val();
    if (contrato == 0) {
        alert("Debe seleccionar un contrato");
        return false;
    }
    $.ajax({
        url: apiCobranza() + 'modContratoRespuestaBanco',
        type: "POST",
        data: 'oid=' + oid + '&contrato=' + contrato,
        success: function (respuesta) {
            alert(respuesta);
            sessionStorage.setItem('respuestaBanco_oida', $("#cmbArchivosRB").val());
            if (typeof onSuccess === 'function') {
                onSuccess();
            }
            location.reload();
        }
    });
    return true;
}

function cargarHistoricoRB() {
    $.ajax({
        url: apiCobranza() + "gridHistoricoRespuestaBanco",
        type: "POST",
        dataType: "json",
        success: function (oEsq) {
            HistGrid = new TGrid(oEsq, 'HistoricoLista', 'Histórico de Cargas');
            HistGrid.SetName("HistoricoLista");
            HistGrid.SetNumeracion(true);
            HistGrid.Generar();
        }
    });
}

function verHistoricoDetalleRB(id, banco) {
    var esVenezuela = String(banco || '').toUpperCase() === 'VENEZUELA';
    var dlg = $("#historicoDetalleRB");
    $("#HistoricoDetalleAviso").html('');
    $("#txtFiltroDetalleRB").val('');
    $("#lblFiltroDetalleRB").text('');
    $("#HistoricoDetalleBuscar").show();
    $("#HistoricoDetalle").html('<p style="padding:12px;">Cargando detalle...</p>');
    $("#HistoricoDetalleRechazos").html('');
    dlg.dialog("option", "title", "Detalle del Archivo #" + id + " — " + (banco || ''));
    dlg.dialog("open");
    $.ajax({
        url: apiCobranza() + "listarHistoricoDetalleRB",
        type: "POST",
        data: { oida: id, banco: banco || '' },
        dataType: "json",
        success: function (oEsq) {
            if (oEsq && oEsq.sin_detalle) {
                $("#HistoricoDetalleBuscar").hide();
                $("#HistoricoDetalle").html('');
                $("#HistoricoDetalleAviso").html(
                    '<div style="margin:12px;padding:14px;border:1px solid #e0c080;background:#fff8e6;color:#7a5a10;font-size:13px;">'
                    + oEsq.sin_detalle + '</div>');
                return;
            }
            HistDetGrid = new TGrid(oEsq, 'HistoricoDetalle', 'Cobros Aplicados — Archivo #' + id + (esVenezuela ? ' (Venezuela)' : ''));
            HistDetGrid.SetName("HistoricoDetalle");
            HistDetGrid.SetNumeracion(true);
            HistDetGrid.Generar();
        },
        error: function () {
            $("#HistoricoDetalle").html('<p style="padding:12px;color:#b00;">No se pudo cargar el detalle.</p>');
        }
    });
    // Venezuela no separa rechazos en otra tabla: el resultado del banco va
    // en la columna Observaciones del detalle.
    if (esVenezuela) {
        return;
    }
    $.ajax({
        url: apiCobranza() + "listarHistoricoRechazosRB",
        type: "POST",
        data: "oida=" + id,
        dataType: "json",
        success: function (oEsq) {
            HistRecGrid = new TGrid(oEsq, 'HistoricoDetalleRechazos', 'Rechazados por el banco (informativo)');
            HistRecGrid.SetName("HistoricoDetalleRechazos");
            HistRecGrid.SetNumeracion(true);
            HistRecGrid.Generar();
        }
    });
}

/**
 * Filtra en pantalla las filas del detalle (cobros aplicados y rechazados)
 * por cualquier texto de la fila — cédula, nombre o contrato. Sin
 * paginador en estos grids, así que basta con ocultar/mostrar filas.
 */
function filtrarDetalleRB() {
    var txt = $.trim($("#txtFiltroDetalleRB").val() || '').toUpperCase();
    var visibles = 0;
    var total = 0;
    $("#HistoricoDetalle tbody tr, #HistoricoDetalleRechazos tbody tr").each(function () {
        if (/_Detalle$/.test(this.id || '')) {
            return;
        }
        total++;
        var coincide = txt === '' || $(this).text().toUpperCase().indexOf(txt) !== -1;
        this.style.display = coincide ? '' : 'none';
        if (coincide) {
            visibles++;
        }
    });
    $("#lblFiltroDetalleRB").text(txt === '' ? '' : visibles + ' de ' + total + ' fila(s)');
}

function buscarCedulaRB() {
    var ced = $("#txtBuscarCedulaRB").val();
    if (!ced) {
        alert("Debe indicar una cédula.");
        return false;
    }
    $.ajax({
        url: apiCobranza() + "buscarCedulaRespuestaBanco",
        type: "POST",
        data: "cedula=" + encodeURIComponent(ced),
        dataType: "json",
        success: function (oEsq) {
            BuscGrid = new TGrid(oEsq, 'BusquedaCedula', 'Resultados para cédula ' + ced);
            BuscGrid.SetName("BusquedaCedula");
            BuscGrid.SetNumeracion(true);
            BuscGrid.Generar();
        }
    });
    return true;
}
