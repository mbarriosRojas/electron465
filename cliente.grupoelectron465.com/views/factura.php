<?php
$pageTitle = 'Detalle de Factura';
$showHeader = true;

if (!isset($userData) || !$userData) {
    header('Location: ' . url('login'));
    exit;
}

// Obtener número de factura de la URL
$pathParts = explode('/', trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/'));
$facturaNum = end($pathParts);

$factura = new Factura($db);
$facturas = $factura->getFacturasActivas($userData['documento_id']);
$facturaActual = null;

foreach ($facturas as $f) {
    if ($f['numero_factura'] === $facturaNum) {
        $facturaActual = $f;
        break;
    }
}

if (!$facturaActual) {
    header('Location: ' . url('dashboard'));
    exit;
}

$contratos = $factura->getContratosPorFactura($userData['documento_id'], $facturaNum);

ob_start();
?>
<div class="container">
    <div class="page-header">
        <a href="<?php echo url('dashboard'); ?>" class="btn btn-secondary">← Volver</a>
        <h2>Detalle de Factura #<?php echo htmlspecialchars($facturaActual['numero_factura']); ?></h2>
    </div>

    <div class="card">
        <h3>Información de la Factura</h3>
        <div class="factura-info-grid">
            <div>
                <strong>Número de Factura:</strong> <?php echo htmlspecialchars($facturaActual['numero_factura']); ?>
            </div>
            <div>
                <strong>Fecha de Solicitud:</strong> <?php echo date('d/m/Y', strtotime($facturaActual['fecha_solicitud'])); ?>
            </div>
            <div>
                <strong>Banco:</strong> <?php echo htmlspecialchars($facturaActual['banco'] ?: 'N/A'); ?>
            </div>
            <div>
                <strong>Modalidad de Pago:</strong> <?php echo htmlspecialchars($facturaActual['modalidad']); ?>
            </div>
            <div>
                <strong>Total:</strong> $USD <?php echo number_format($facturaActual['total'], 2, ',', '.'); ?>
            </div>
            <div>
                <strong>Pagado:</strong> $USD <?php echo number_format($facturaActual['pagado'], 2, ',', '.'); ?>
            </div>
            <div>
                <strong>Restante:</strong> 
                <span class="resta-amount">$USD <?php echo number_format($facturaActual['resta'], 2, ',', '.'); ?></span>
            </div>
        </div>
    </div>

    <?php if (!empty($contratos)): ?>
    <div class="card">
        <h3>Contratos</h3>
        <div class="contratos-list">
            <?php foreach ($contratos as $contrato): ?>
            <div class="contrato-item" data-contrato="<?php echo htmlspecialchars($contrato['contrato_id']); ?>">
                <strong><?php echo htmlspecialchars($contrato['contrato_id']); ?></strong>
                <small>Ver detalles de pagos</small>
            </div>
            <?php endforeach; ?>
        </div>
        
        <div id="pagos-container" style="display: none;">
            <div id="pagos-content"></div>
        </div>
    </div>
    <?php endif; ?>
</div>

<script>
// Variable global para el banco de la factura
const facturaBanco = '<?php echo htmlspecialchars($facturaActual['banco'] ?: '', ENT_QUOTES, 'UTF-8'); ?>';

document.addEventListener('DOMContentLoaded', function() {
    const contratoItems = document.querySelectorAll('.contrato-item');
    
    contratoItems.forEach(item => {
        item.addEventListener('click', function() {
            const contratoId = this.dataset.contrato;
            const container = document.getElementById('pagos-container');
            const content = document.getElementById('pagos-content');
            
            // Mostrar loading
            content.innerHTML = '<p>Cargando detalles de pagos...</p>';
            container.style.display = 'block';
            
            // Remover clase active de otros items
            contratoItems.forEach(i => i.classList.remove('active'));
            this.classList.add('active');
            
            // Guardar contratoId para usar en renderPagos
            window.currentContratoId = contratoId;
            
            // Cargar detalles de pagos
            const apiUrl = '<?php echo url("api/pagos/detalle"); ?>';
            
            fetch(apiUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                credentials: 'same-origin',
                body: JSON.stringify({
                    contrato_id: contratoId
                })
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => {
                        throw new Error(err.error || 'HTTP error! status: ' + response.status);
                    });
                }
                return response.json();
            })
            .then(data => {
                if (data.success && data.data) {
                    renderPagos(data.data);
                } else {
                    const errorMsg = data.error || 'Error desconocido';
                    console.error('Error en respuesta:', data);
                    content.innerHTML = '<p class="error">Error al cargar los detalles de pagos: ' + errorMsg + '</p>';
                }
            })
            .catch(error => {
                console.error('Error en fetch:', error);
                console.error('URL:', apiUrl);
                console.error('Contrato ID:', contratoId);
                content.innerHTML = '<p class="error">Error al cargar los detalles de pagos. Ver consola para más detalles.</p>';
            });
        });
    });
});

function renderPagos(data) {
    const content = document.getElementById('pagos-content');
    const resumen = data.resumen;
    const pagos = data.pagos;
    
    // Verificar si se debe mostrar la columna de Comprobantes
    const mostrarComprobantes = facturaBanco === 'TRANSFERENCIA' || facturaBanco === 'COTERO';
    
    // Contar pagos incompletos (color amarillo #fff3cd)
    let pagosIncompletos = 0;
    if (pagos && pagos.length > 0) {
        pagosIncompletos = pagos.filter(pago => !pago.es_cuota_completa).length;
    }
    
    // Determinar estatus: si hay más de 1 pago incompleto, es MOROSO, de lo contrario AL DÍA
    const esMoroso = pagosIncompletos > 1;
    
    let html = '<div class="pagos-resumen">';
    html += '<h4>Resumen</h4>';
    html += '<div class="resumen-grid">';
    html += '<div class="resumen-item"><span>Monto Total:</span><span>' + formatCurrency(resumen.monto_total) + '</span></div>';
    html += '<div class="resumen-item"><span>Monto Acumulado:</span><span>' + formatCurrency(resumen.monto_acumulado) + '</span></div>';
    html += '<div class="resumen-item"><span>Monto Restante:</span><span class="resta-amount">' + formatCurrency(resumen.monto_restante) + '</span></div>';
    html += '<div class="resumen-item"><span>Número de Cuotas:</span><span>' + resumen.numero_cuotas + '</span></div>';
    html += '<div class="resumen-item"><span>Cuotas Pagadas:</span><span>' + resumen.cuotas_pagadas + '</span></div>';
    html += '<div class="resumen-item"><span>Cuotas Pendientes:</span><span>' + resumen.cuotas_pendientes + '</span></div>';
    
    // Mostrar estatus basado en pagos incompletos
    if (esMoroso) {
        html += '<div class="resumen-item moroso"><span>⚠️ Estatus:</span><span>MOROSO</span></div>';
    } else {
        html += '<div class="resumen-item"><span>✅ Estatus:</span><span>AL DÍA</span></div>';
    }
    html += '</div></div>';
    
    if (pagos && pagos.length > 0) {
        html += '<div class="pagos-list"><h4>Historial de Pagos</h4>';
        
        // Construir el encabezado de la tabla
        html += '<table class="table"><thead><tr><th>#</th><th>Mes</th><th>Fecha Cuota</th><th>Monto</th><th>Restante</th><th>Fecha Descuento</th><th>Modalidad</th>';
        if (mostrarComprobantes) {
            html += '<th>Comprobantes</th>';
        }
        html += '</tr></thead><tbody>';
        
        pagos.forEach(pago => {
            const clase = pago.es_cuota_completa ? 'completo' : 'incompleto';
            html += '<tr class="' + clase + '">';
            html += '<td>' + pago.numero + '</td>';
            html += '<td>' + pago.mes + '</td>';
            html += '<td>' + formatDate(pago.fecha_cuota) + '</td>';
            html += '<td>' + formatCurrency(pago.monto) + '</td>';
            html += '<td>' + formatCurrency(pago.restante) + '</td>';
            html += '<td>' + formatDate(pago.fecha_descuento) + '</td>';
            html += '<td>' + pago.modalidad + '</td>';
            if (mostrarComprobantes) {
                html += '<td><a href="<?php echo url("api/comprobante/download"); ?>?contrato=' + encodeURIComponent(window.currentContratoId) + '&pago=' + encodeURIComponent(pago.pago_id) + '" target="_blank" class="btn-download" title="Descargar Comprobante" style="color: var(--accent); text-decoration: none; font-weight: 700;">📄 Comprobante</a></td>';
            }
            html += '</tr>';
        });
        
        html += '</tbody></table></div>';
    } else {
        html += '<p class="no-pagos">No hay pagos registrados para este contrato</p>';
    }
    
    content.innerHTML = html;
}

function formatCurrency(amount) {
    // Asegurar que amount sea un número
    const num = parseFloat(amount) || 0;
    const formatted = num.toFixed(2).replace('.', ',').replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    return '$USD ' + formatted;
}

function formatDate(dateString) {
    if (!dateString) return 'N/A';
    const date = new Date(dateString);
    return date.toLocaleDateString('es-VE');
}
</script>
<?php
$content = ob_get_clean();
include VIEWS_PATH . '/layout.php';
?>

