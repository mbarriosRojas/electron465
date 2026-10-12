<?php
/**
 * Script de prueba para verificar el detalle de pagos
 * Uso: php test-pagos.php
 */

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/classes/Database.php';
require_once __DIR__ . '/classes/Factura.php';

$documentoId = '19997832';
$contratoId = 'N-00000074';

echo "🧪 Probando detalle de pagos...\n";
echo "📄 Documento: $documentoId\n";
echo "📋 Contrato: $contratoId\n\n";

try {
    $db = new Database();
    $factura = new Factura($db);
    
    echo "🔍 Ejecutando getDetallePagos()...\n";
    $detalle = $factura->getDetallePagos($documentoId, $contratoId);
    
    if ($detalle) {
        echo "✅ Detalle obtenido correctamente\n\n";
        echo "📊 Resumen:\n";
        echo "   Monto Total: " . $detalle['resumen']['monto_total'] . "\n";
        echo "   Monto Acumulado: " . $detalle['resumen']['monto_acumulado'] . "\n";
        echo "   Monto Restante: " . $detalle['resumen']['monto_restante'] . "\n";
        echo "   Número de Cuotas: " . $detalle['resumen']['numero_cuotas'] . "\n";
        echo "   Cuotas Pagadas: " . $detalle['resumen']['cuotas_pagadas'] . "\n";
        echo "   Cuotas Pendientes: " . $detalle['resumen']['cuotas_pendientes'] . "\n";
        echo "   Total de Pagos: " . count($detalle['pagos']) . "\n";
    } else {
        echo "❌ No se encontraron datos (retornó null)\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "📝 Trace: " . $e->getTraceAsString() . "\n";
}

