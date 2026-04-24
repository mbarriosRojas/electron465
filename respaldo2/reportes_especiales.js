/**
 * Reportes Especiales - Sistema de Control de Préstamos
 * Archivo JavaScript independiente para reportes especiales
 * 
 * Desarrollado por: Sistema de Reportes Especiales
 * Fecha: 2024
 */

// Variables globales para reportes especiales
// Detectar si estamos en local o en producción
var isLocal = window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1';
var protocol = window.location.protocol.replace(':', ''); // Usar el protocolo actual
var port = isLocal ? ':8000' : '';

var sUrlEspeciales = protocol + '://' + window.location.hostname + port;
var sUrlPEspeciales = sUrlEspeciales + '/index.php/cooperativa/';

// Debug: mostrar las URLs generadas
console.log('URLs generadas para reportes especiales:');
console.log('sUrlEspeciales:', sUrlEspeciales);
console.log('sUrlPEspeciales:', sUrlPEspeciales);

// Función para mostrar el modal de reportes especiales
function mostrarReportesEspeciales() {
    // Cargar datos para los filtros
    cargarDatosFiltros();
    
    // Mostrar el modal usando jQuery UI
    $('#modal_reportes_especiales').dialog({
        modal: true,
        width: 800,
        height: 400,
        title: 'Reportes Especiales - Filtros Avanzados',
        resizable: true,
        buttons: {
            "Exportar a Excel": function() {
                exportarReporteEspecial();
            },
            "Limpiar Filtros": function() {
                limpiarFiltros();
            },
            "Cerrar": function() {
                $(this).dialog("close");
            }
        }
    });
}

// Función para probar conectividad del servidor
function probarConectividad() {
    $.ajax({
        url: sUrlPEspeciales + 'Test_Reportes_Especiales',
        type: 'POST',
        dataType: 'json',
        success: function(data) {
            console.log('Conectividad OK:', data);
        },
        error: function(xhr, status, error) {
            console.error('Error de conectividad:', error, xhr.responseText);
        }
    });
}

// Función para debug de tablas
function debugTablas() {
    $.ajax({
        url: sUrlPEspeciales + 'Debug_Tablas_Especiales',
        type: 'POST',
        dataType: 'json',
        success: function(data) {
            console.log('Debug tablas:', data);
        },
        error: function(xhr, status, error) {
            console.error('Error debug tablas:', error, xhr.responseText);
        }
    });
}

// Función para cargar datos de los filtros
function cargarDatosFiltros() {
    // Primero probar conectividad y debug
    probarConectividad();
    debugTablas();
    // Cargar bancos
    $.ajax({
        url: sUrlPEspeciales + 'DataSource_Bancos_Especiales',
        type: 'POST',
        dataType: 'json',
        success: function(data) {
            var bancoSelect = $('#filtro_banco_especial');
            bancoSelect.html('<option value="">Todos los Bancos</option>');
            if (data && data.length > 0) {
                $.each(data, function(index, banco) {
                    bancoSelect.append('<option value="' + banco.id + '">' + banco.nombre + '</option>');
                });
            }
        },
        error: function(xhr, status, error) {
            console.error('Error al cargar bancos:', error, xhr.responseText);
            $('#filtro_banco_especial').html('<option value="">ERROR AL CARGAR BANCOS</option>');
        }
    });
    
    // Cargar nóminas
    $.ajax({
        url: sUrlPEspeciales + 'DataSource_Nominas_Especiales',
        type: 'POST',
        dataType: 'json',
        success: function(data) {
            var nominaSelect = $('#filtro_nomina_especial');
            nominaSelect.html('<option value="">Todas las Nóminas</option>');
            if (data && data.length > 0) {
                $.each(data, function(index, nomina) {
                    nominaSelect.append('<option value="' + nomina.nombre + '">' + nomina.nombre + '</option>');
                });
            }
        },
        error: function(xhr, status, error) {
            console.error('Error al cargar nóminas:', error, xhr.responseText);
            $('#filtro_nomina_especial').html('<option value="">ERROR AL CARGAR NÓMINAS</option>');
        }
    });
}

// Función para mostrar los resultados del reporte especial
function mostrarResultadosReporteEspecial(data) {
    var html = '<div style="margin-bottom: 20px;">';
    html += '<h3>Resultados del Reporte Especial</h3>';
    html += '<p><strong>Total de registros encontrados:</strong> ' + (data.total || 0) + '</p>';
    html += '</div>';
    
    if (data.registros && data.registros.length > 0) {
        html += '<table class="TGrid" style="width: 100%; border-collapse: collapse; min-width: 1400px;">';
        html += '<thead>';
        html += '<tr style="background-color: #f0f0f0;">';
        html += '<th style="border: 1px solid #ccc; padding: 8px; width: 100px;">Cédula</th>';
        html += '<th style="border: 1px solid #ccc; padding: 8px; width: 200px;">Nombre</th>';
        html += '<th style="border: 1px solid #ccc; padding: 8px; width: 120px;">Contrato</th>';
        html += '<th style="border: 1px solid #ccc; padding: 8px; width: 100px;">Banco</th>';
        html += '<th style="border: 1px solid #ccc; padding: 8px; width: 250px;">Nómina</th>';
        html += '<th style="border: 1px solid #ccc; padding: 8px; width: 100px;">Estado</th>';
        html += '<th style="border: 1px solid #ccc; padding: 8px; width: 100px;">Monto Total</th>';
        html += '<th style="border: 1px solid #ccc; padding: 8px; width: 100px;">Monto Pagado</th>';
        html += '<th style="border: 1px solid #ccc; padding: 8px; width: 100px;">Saldo Total</th>';
        html += '<th style="border: 1px solid #ccc; padding: 8px; width: 80px;">Cuotas Vencidas</th>';
        html += '<th style="border: 1px solid #ccc; padding: 8px; width: 100px;">Monto para Al Día</th>';
        html += '<th style="border: 1px solid #ccc; padding: 8px; width: 120px;">Tipo Contrato</th>';
        html += '<th style="border: 1px solid #ccc; padding: 8px; width: 120px;">Fecha Solicitud</th>';
        html += '<th style="border: 1px solid #ccc; padding: 8px; width: 120px;">Fecha Inicio Cobro</th>';
        html += '<th style="border: 1px solid #ccc; padding: 8px; width: 120px;">Fecha Finalización</th>';
        html += '</tr>';
        html += '</thead>';
        html += '<tbody>';
        
        $.each(data.registros, function(index, registro) {
            var estadoClass = '';
            var estadoTexto = '';
            
            switch(registro.estado) {
                case 'morosos':
                    estadoClass = 'color: red; font-weight: bold;';
                    estadoTexto = 'Moroso';
                    break;
                case 'al_dia':
                    estadoClass = 'color: green; font-weight: bold;';
                    estadoTexto = 'Al Día';
                    break;
                case 'terminados':
                    estadoClass = 'color: blue; font-weight: bold;';
                    estadoTexto = 'Terminado';
                    break;
                default:
                    estadoTexto = registro.estado || 'Sin Estado';
            }
            
            html += '<tr>';
            html += '<td style="border: 1px solid #ccc; padding: 8px; width: 100px; white-space: nowrap;">' + (registro.cedula || '') + '</td>';
            html += '<td style="border: 1px solid #ccc; padding: 8px; width: 200px; word-wrap: break-word;">' + (registro.nombre || '') + '</td>';
            html += '<td style="border: 1px solid #ccc; padding: 8px; width: 120px; white-space: nowrap;">' + (registro.contrato_id || '') + '</td>';
            html += '<td style="border: 1px solid #ccc; padding: 8px; width: 100px; white-space: nowrap;">' + (registro.banco || '') + '</td>';
            html += '<td style="border: 1px solid #ccc; padding: 8px; width: 250px; word-wrap: break-word;">' + (registro.nomina || '') + '</td>';
            html += '<td style="border: 1px solid #ccc; padding: 8px; width: 100px; white-space: nowrap; ' + estadoClass + '">' + estadoTexto + '</td>';
            html += '<td style="border: 1px solid #ccc; padding: 8px; width: 100px; text-align: right; white-space: nowrap;">' + (registro.monto_total || '0.00') + '</td>';
            html += '<td style="border: 1px solid #ccc; padding: 8px; width: 100px; text-align: right; white-space: nowrap;">' + (registro.monto_pagado || '0.00') + '</td>';
            html += '<td style="border: 1px solid #ccc; padding: 8px; width: 100px; text-align: right; white-space: nowrap;">' + (registro.saldo || '0.00') + '</td>';
            html += '<td style="border: 1px solid #ccc; padding: 8px; width: 80px; text-align: center; white-space: nowrap;">' + (registro.cuotas_vencidas || '0') + '</td>';
            html += '<td style="border: 1px solid #ccc; padding: 8px; width: 100px; text-align: right; white-space: nowrap; color: red; font-weight: bold;">' + (registro.monto_para_al_dia || '0.00') + '</td>';
            html += '<td style="border: 1px solid #ccc; padding: 8px; width: 120px; white-space: nowrap;">' + (registro.tipo_contrato || '') + '</td>';
            html += '<td style="border: 1px solid #ccc; padding: 8px; width: 120px; white-space: nowrap;">' + (registro.fecha_solicitud || '') + '</td>';
            html += '<td style="border: 1px solid #ccc; padding: 8px; width: 120px; white-space: nowrap;">' + (registro.fecha_inicio_cobro || '') + '</td>';
            html += '<td style="border: 1px solid #ccc; padding: 8px; width: 120px; white-space: nowrap;">' + (registro.fecha_finalizacion_cobro || '') + '</td>';
            html += '</tr>';
        });
        
        html += '</tbody>';
        html += '</table>';
    } else {
        html += '<div style="text-align: center; padding: 20px; color: #666;">No se encontraron registros con los filtros seleccionados.</div>';
    }
    
    $('#resultados_reportes_especiales').html(html);
}

// Función para limpiar los filtros
function limpiarFiltros() {
    $('#filtro_banco_especial').val('');
    $('#filtro_estado_especial').val('');
    $('#resultados_reportes_especiales').html('');
}

// Función para exportar el reporte
function exportarReporteEspecial() {
    var filtros = {
        banco: $('#filtro_banco_especial').val(),
        estado: $('#filtro_estado_especial').val(),
        exportar: 'excel'
    };
    
    // Mostrar mensaje de carga
    alert('Generando archivo Excel... Por favor espere.');
    
    // Crear formulario temporal para descarga
    var form = $('<form>', {
        'method': 'POST',
        'action': sUrlPEspeciales + 'Exportar_Reporte_Especial',
        'target': '_blank',
        'style': 'display: none;'
    });
    
    $.each(filtros, function(key, value) {
        form.append($('<input>', {
            'type': 'hidden',
            'name': key,
            'value': value
        }));
    });
    
    $('body').append(form);
    form.submit();
    
    // Limpiar el formulario después de un breve delay
    setTimeout(function() {
        form.remove();
    }, 1000);
}

// Función para inicializar los reportes especiales
$(document).ready(function() {
    // Esta función se ejecutará cuando el documento esté listo
    console.log('Reportes Especiales - Sistema inicializado');
});