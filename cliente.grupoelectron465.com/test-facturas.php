<?php
/**
 * Script de prueba para verificar la consulta de facturas
 * Uso: php test-facturas.php
 */

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/classes/Database.php';
require_once __DIR__ . '/classes/Factura.php';

$documentoId = '19997832';

echo "🧪 Probando consulta de facturas activas...\n";
echo "📄 Documento: $documentoId\n\n";

try {
    $db = new Database();
    $factura = new Factura($db);
    
    echo "🔍 Ejecutando getFacturasActivas()...\n";
    $facturas = $factura->getFacturasActivas($documentoId);
    
    echo "📊 Resultados:\n";
    echo "   Total de facturas encontradas: " . count($facturas) . "\n\n";
    
    if (count($facturas) > 0) {
        foreach ($facturas as $fact) {
            echo "✅ Factura #{$fact['numero_factura']}\n";
            echo "   Fecha: {$fact['fecha_solicitud']}\n";
            echo "   Total: {$fact['total']}\n";
            echo "   Pagado: {$fact['pagado']}\n";
            echo "   Resta: {$fact['resta']}\n";
            echo "   Modalidad: {$fact['modalidad']}\n";
            echo "\n";
        }
    } else {
        echo "❌ No se encontraron facturas activas\n";
        echo "\n";
        echo "🔍 Verificando datos en la base de datos...\n";
        
        // Consulta directa para ver qué hay
        $query = "SELECT numero_factura, cantidad, monto_total, marca_consulta 
                  FROM t_clientes_creditos 
                  WHERE documento_id = '$documentoId' 
                  LIMIT 5";
        $result = $db->query($query);
        
        if ($result->num_rows > 0) {
            echo "📋 Registros encontrados en t_clientes_creditos:\n";
            while ($row = $result->fetch_assoc()) {
                echo "   Factura: {$row['numero_factura']}, Cantidad: {$row['cantidad']}, Monto: {$row['monto_total']}\n";
            }
        } else {
            echo "❌ No hay registros en t_clientes_creditos para este documento\n";
        }
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "📝 Trace: " . $e->getTraceAsString() . "\n";
}

