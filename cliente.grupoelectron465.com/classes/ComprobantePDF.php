<?php
/**
 * Clase para generar comprobantes de pago.
 *
 * El comprobante es un documento HTML (con su propia hoja de estilos para
 * pantalla e impresión) que el navegador convierte a PDF vía "Imprimir →
 * Guardar como PDF". Es intencional NO depender de una librería como FPDF:
 * un solo documento controla tanto lo que el cliente ve en pantalla como lo
 * que termina en el PDF, así nunca se desincronizan (regla de oro pedida
 * por el usuario: "que se vea exactamente igual").
 */

class ComprobantePDF {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    /**
     * Generar comprobante de pago (documento HTML listo para imprimir/descargar).
     */
    public function generarComprobante($documentoId, $contratoId, $pagoId) {
        try {
            $pagoData = $this->obtenerDatosPago($documentoId, $contratoId, $pagoId);

            if (!$pagoData) {
                throw new Exception('No se encontraron datos del pago');
            }

            return $this->generarHTML($pagoData);

        } catch (Exception $e) {
            error_log("Error generando comprobante: " . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Obtener datos del pago desde la base de datos
     */
    private function obtenerDatosPago($documentoId, $contratoId, $pagoId) {
        $docEscaped = $this->db->escape($documentoId);
        $contratoEscaped = $this->db->escape($contratoId);
        
        // Obtener datos del pago
        $queryPago = "SELECT 
                        lc.*,
                        ifnull(m.nomb,'SIN MODALIDAD') as modalidad_nombre,
                        p.documento_id,
                        p.primer_nombre,
                        p.segundo_nombre,
                        p.primer_apellido,
                        p.segundo_apellido,
                        p.nacionalidad,
                        cc.numero_factura,
                        cc.monto_cuota,
                        cc.numero_cuotas,
                        cc.cobrado_en as banco
                      FROM t_lista_cobros lc
                      LEFT JOIN t_modalidad m ON m.oid = lc.moda
                      INNER JOIN t_personas p ON p.documento_id = lc.documento_id
                      INNER JOIN t_clientes_creditos cc ON cc.contrato_id = lc.credito_id 
                        AND cc.documento_id = lc.documento_id
                      WHERE lc.documento_id = '$docEscaped'
                        AND lc.credito_id = '$contratoEscaped'
                        AND lc.mes = '$pagoId'
                      LIMIT 1";
        
        $result = $this->db->query($queryPago);
        
        if ($result->num_rows === 0) {
            return null;
        }
        
        $row = $result->fetch_assoc();
        
        // Construir nombre completo
        $nombreCompleto = trim(
            $row['primer_nombre'] . ' ' . 
            ($row['segundo_nombre'] ? $row['segundo_nombre'] . ' ' : '') .
            $row['primer_apellido'] . ' ' .
            ($row['segundo_apellido'] ? $row['segundo_apellido'] : '')
        );
        
        // Formatear fecha
        $fechaPago = new DateTime($row['fecha']);
        $dia = $fechaPago->format('d');
        $mes = $fechaPago->format('m');
        $anio = $fechaPago->format('Y');
        
        // Generar número de referencia (usar el número del recibo sin R- y |)
        $ref = str_replace(['R-', '|'], '', $row['mes']);

        // Convertir monto a letras
        $montoLetras = $this->numeroALetras($row['monto']);
        
        // Formatear referencia (quitar caracteres especiales para mostrar)
        $refDisplay = str_replace(['R-', '|'], '', $ref);
        
        return [
            'nombre' => $nombreCompleto,
            'documento' => $row['documento_id'],
            'nacionalidad' => $row['nacionalidad'],
            'monto' => floatval($row['monto']),
            'monto_letras' => $montoLetras,
            'fecha' => $row['fecha'],
            'dia' => $dia,
            'mes' => $mes,
            'anio' => $anio,
            'referencia' => $refDisplay,
            'descripcion' => $row['descripcion'],
            'modalidad' => $row['modalidad_nombre'],
            'fecha_descuento' => $row['farc'],
            'numero_factura' => $row['numero_factura'],
            'monto_cuota' => floatval($row['monto_cuota']),
            'banco' => isset($row['banco']) ? $row['banco'] : ''
        ];
    }
    
    /**
     * Generar el comprobante como documento HTML autocontenido.
     * La MISMA hoja de estilos gobierna la vista en pantalla y la impresión
     * (@media print solo oculta la barra de acciones y ajusta el tamaño de
     * página) — así lo que el cliente ve es exactamente lo que descarga.
     */
    private function generarHTML($data) {
        header('Content-Type: text/html; charset=utf-8');

        $basePath = defined('BASE_PATH') ? BASE_PATH : dirname(__DIR__);
        $logoPaths = [
            $basePath . '/assets/images/logo.png',
            dirname($basePath) . '/respaldo_electron/system/img/logo.png',
        ];

        $logoUrl = '';
        foreach ($logoPaths as $logoPath) {
            if (file_exists($logoPath) && is_readable($logoPath)) {
                $imageData = base64_encode(file_get_contents($logoPath));
                $imageInfo = @getimagesize($logoPath);
                $mimeType = $imageInfo ? $imageInfo['mime'] : 'image/png';
                $logoUrl = 'data:' . $mimeType . ';base64,' . $imageData;
                break;
            }
        }

        $nombre = strtoupper(htmlspecialchars($data['nombre']));
        $documento = htmlspecialchars(rtrim($data['nacionalidad'] ?: 'V', '-') . '-' . $data['documento']);
        $montoFmt = number_format($data['monto'], 2, ',', '.');
        $montoLetras = strtoupper(htmlspecialchars($data['monto_letras']));
        $referencia = htmlspecialchars($data['referencia']);
        $descripcion = htmlspecialchars($data['descripcion']);
        $modalidad = htmlspecialchars($data['modalidad']);
        $banco = htmlspecialchars($data['banco'] ?: 'N/A');
        $fecha = htmlspecialchars($data['dia'] . '/' . $data['mes'] . '/' . $data['anio']);
        $numeroFactura = htmlspecialchars($data['numero_factura']);
        $nombreArchivo = 'Comprobante_' . preg_replace('/[^A-Za-z0-9]/', '', $referencia) . '_' . date('Ymd');

        $html = '<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>' . $nombreArchivo . '</title>
<style>
    :root {
        --ink: #121826;
        --ink-soft: #5c6675;
        --ink-faint: #8b95a5;
        --line: #e1e6ed;
        --accent: #0e7c86;
        --accent-2: #5b5fef;
        --good: #12946b;
        --good-soft: rgba(18, 148, 107, 0.1);
        --surface-sunken: #edf1f5;
    }
    * { box-sizing: border-box; }
    body {
        margin: 0;
        padding: 32px 16px;
        background: #f5f7fa;
        font-family: -apple-system, "Segoe UI", Roboto, Arial, sans-serif;
        color: var(--ink);
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    .toolbar {
        width: 100%;
        max-width: 480px;
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        margin-bottom: 14px;
    }
    .btn {
        padding: 10px 18px;
        border-radius: 10px;
        font-size: 13.5px;
        font-weight: 700;
        cursor: pointer;
        border: none;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .btn-primary {
        background: linear-gradient(135deg, var(--accent) 0%, var(--accent-2) 100%);
        color: white;
        box-shadow: 0 4px 14px rgba(14, 124, 134, 0.25);
    }
    .btn-secondary {
        background: white;
        color: var(--ink-soft);
        border: 1px solid var(--line);
    }
    .recibo {
        width: 100%;
        max-width: 480px;
        background: white;
        border-radius: 16px;
        border: 1px solid var(--line);
        box-shadow: 0 16px 48px rgba(18, 24, 38, 0.12);
        overflow: hidden;
    }
    .recibo-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        padding: 22px 26px;
        background: #ffffff;
        color: var(--ink);
        border-bottom: 1px solid var(--line);
    }
    .recibo-head img {
        height: 32px;
        width: auto;
        display: block;
    }
    .recibo-head-text {
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 0.04em;
        text-transform: uppercase;
        color: var(--ink-soft);
    }
    .recibo-ref {
        text-align: right;
    }
    .recibo-ref-label {
        font-size: 10px;
        font-weight: 700;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        color: var(--ink-faint);
    }
    .recibo-ref-value {
        font-family: ui-monospace, "SF Mono", Menlo, monospace;
        font-size: 15px;
        font-weight: 700;
        color: var(--accent);
    }
    .recibo-body {
        padding: 26px;
    }
    .recibo-monto {
        text-align: center;
        padding: 18px 10px 22px;
        border-bottom: 1px dashed var(--line);
        margin-bottom: 20px;
    }
    .recibo-monto-label {
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        color: var(--ink-faint);
    }
    .recibo-monto-valor {
        font-family: ui-monospace, "SF Mono", Menlo, monospace;
        font-size: 34px;
        font-weight: 700;
        color: var(--ink);
        margin: 4px 0 8px;
    }
    .recibo-monto-letras {
        font-size: 12px;
        color: var(--ink-soft);
        font-style: italic;
    }
    .recibo-status {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: var(--good-soft);
        color: var(--good);
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 0.02em;
        padding: 5px 14px;
        border-radius: 100px;
        margin-top: 10px;
    }
    .recibo-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 14px 18px;
    }
    .recibo-field {
        min-width: 0;
    }
    .recibo-field.full {
        grid-column: 1 / -1;
    }
    .recibo-field-label {
        font-size: 10.5px;
        font-weight: 700;
        letter-spacing: 0.05em;
        text-transform: uppercase;
        color: var(--ink-faint);
        margin-bottom: 3px;
    }
    .recibo-field-value {
        font-size: 13.5px;
        font-weight: 600;
        color: var(--ink);
        word-break: break-word;
    }
    .recibo-foot {
        margin-top: 22px;
        padding-top: 16px;
        border-top: 1px solid var(--line);
        font-size: 11px;
        color: var(--ink-faint);
        text-align: center;
    }
    @media print {
        @page { size: letter portrait; margin: 12mm; }
        body { background: white; padding: 0; }
        .toolbar { display: none; }
        .recibo { box-shadow: none; border: 1px solid var(--line); max-width: 100%; }
    }
</style>
</head>
<body>
    <div class="toolbar">
        <button type="button" class="btn btn-secondary" onclick="window.close();">Cerrar</button>
        <button type="button" class="btn btn-primary" onclick="window.print();">⬇ Descargar / Imprimir</button>
    </div>
    <div class="recibo">
        <div class="recibo-head">
            <div>
                ' . ($logoUrl ? '<img src="' . htmlspecialchars($logoUrl) . '" alt="Grupo Electrón">' : '<strong>GRUPO ELECTRÓN</strong>') . '
                <div class="recibo-head-text">Recibo de Pago</div>
            </div>
            <div class="recibo-ref">
                <div class="recibo-ref-label">Referencia</div>
                <div class="recibo-ref-value">' . $referencia . '</div>
            </div>
        </div>
        <div class="recibo-body">
            <div class="recibo-monto">
                <div class="recibo-monto-label">Monto recibido</div>
                <div class="recibo-monto-valor">$USD ' . $montoFmt . '</div>
                <div class="recibo-monto-letras">' . $montoLetras . '</div>
                <div class="recibo-status">✓ Pagado</div>
            </div>
            <div class="recibo-grid">
                <div class="recibo-field full">
                    <div class="recibo-field-label">Hemos recibido de</div>
                    <div class="recibo-field-value">' . $nombre . '</div>
                </div>
                <div class="recibo-field">
                    <div class="recibo-field-label">Cédula / RIF</div>
                    <div class="recibo-field-value">' . $documento . '</div>
                </div>
                <div class="recibo-field">
                    <div class="recibo-field-label">Fecha</div>
                    <div class="recibo-field-value">' . $fecha . '</div>
                </div>
                <div class="recibo-field">
                    <div class="recibo-field-label">Factura</div>
                    <div class="recibo-field-value">#' . $numeroFactura . '</div>
                </div>
                <div class="recibo-field">
                    <div class="recibo-field-label">Modalidad</div>
                    <div class="recibo-field-value">' . $modalidad . '</div>
                </div>
                <div class="recibo-field">
                    <div class="recibo-field-label">Banco</div>
                    <div class="recibo-field-value">' . $banco . '</div>
                </div>
                <div class="recibo-field full">
                    <div class="recibo-field-label">Concepto</div>
                    <div class="recibo-field-value">' . $descripcion . '</div>
                </div>
            </div>
            <div class="recibo-foot">Grupo Electrón 465 &middot; Comprobante generado el ' . date('d/m/Y H:i') . '</div>
        </div>
    </div>
</body>
</html>';

        echo $html;
        exit;
    }
    
    /**
     * Convertir número a letras (español)
     */
    private function numeroALetras($numero) {
        $numero = floatval($numero);
        $entero = intval($numero);
        $decimal = round(($numero - $entero) * 100);
        
        $unidades = ['', 'UN', 'DOS', 'TRES', 'CUATRO', 'CINCO', 'SEIS', 'SIETE', 'OCHO', 'NUEVE'];
        $decenas = ['', 'DIEZ', 'VEINTE', 'TREINTA', 'CUARENTA', 'CINCUENTA', 'SESENTA', 'SETENTA', 'OCHENTA', 'NOVENTA'];
        $especiales = ['DIEZ', 'ONCE', 'DOCE', 'TRECE', 'CATORCE', 'QUINCE', 'DIECISÉIS', 'DIECISIETE', 'DIECIOCHO', 'DIECINUEVE'];
        $centenas = ['', 'CIEN', 'DOSCIENTOS', 'TRESCIENTOS', 'CUATROCIENTOS', 'QUINIENTOS', 'SEISCIENTOS', 'SETECIENTOS', 'OCHOCIENTOS', 'NOVECIENTOS'];
        
        if ($entero == 0) {
            $texto = 'CERO';
        } elseif ($entero < 10) {
            $texto = $unidades[$entero];
        } elseif ($entero < 20) {
            $texto = $especiales[$entero - 10];
        } elseif ($entero < 100) {
            $decena = intval($entero / 10);
            $unidad = $entero % 10;
            if ($unidad == 0) {
                $texto = $decenas[$decena];
            } elseif ($decena == 2) {
                $texto = 'VEINTI' . strtolower($unidades[$unidad]);
            } else {
                $texto = $decenas[$decena] . ' Y ' . strtolower($unidades[$unidad]);
            }
        } elseif ($entero < 1000) {
            $centena = intval($entero / 100);
            $resto = $entero % 100;
            if ($centena == 1 && $resto > 0) {
                $texto = 'CIENTO';
            } else {
                $texto = $centenas[$centena];
            }
            if ($resto > 0) {
                $texto .= ' ' . $this->numeroALetras($resto);
            }
        } else {
            // Para números mayores, usar formato numérico con letras básicas
            $texto = number_format($entero, 0, ',', '.') . ' BOLÍVARES';
        }
        
        if ($decimal > 0) {
            $texto .= ' CON ' . str_pad($decimal, 2, '0', STR_PAD_LEFT) . '/100';
        } else {
            $texto .= ' CON 00/100';
        }
        
        return $texto;
    }
}

