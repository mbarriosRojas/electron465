<?php
/**
 * Clase para manejo de facturas y pagos
 */

class Factura {
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
    }
    
    /**
     * Obtener facturas activas del usuario
     */
    public function getFacturasActivas($documentoId) {
        try {
            $documentoIdEscaped = $this->db->escape($documentoId);
            
            // Consulta simplificada y corregida para facturas activas
            // Primero calculamos pagos por factura (sin vouchers por ahora para evitar errores)
            $query = "
                SELECT 
                    facturas.numero_factura AS factura,
                    facturas.fecha_solicitud AS solicitud,
                    facturas.cobrado_en AS banco,
                    facturas.total2,
                    IFNULL(pagos.pagado, 0) AS pagado,
                    0 AS pg_voucher,
                    facturas.total2 - IFNULL(pagos.pagado, 0) AS resta,
                    'A' AS activo,
                    facturas.marca_consulta
                FROM (
                    SELECT 
                        numero_factura,
                        MAX(fecha_solicitud) AS fecha_solicitud,
                        MAX(cobrado_en) AS cobrado_en,
                        SUM(cantidad) AS total2,
                        MAX(marca_consulta) AS marca_consulta
                    FROM t_clientes_creditos
                    WHERE documento_id = '$documentoIdEscaped'
                    GROUP BY numero_factura
                ) AS facturas
                LEFT JOIN (
                    SELECT 
                        t.numero_factura,
                        SUM(lc.monto) AS pagado
                    FROM t_clientes_creditos t
                    INNER JOIN t_lista_cobros lc 
                        ON t.contrato_id = lc.credito_id 
                        AND t.documento_id = lc.documento_id
                    WHERE t.documento_id = '$documentoIdEscaped'
                    GROUP BY t.numero_factura
                ) AS pagos ON pagos.numero_factura = facturas.numero_factura
                WHERE facturas.total2 - IFNULL(pagos.pagado, 0) > 0
                ORDER BY facturas.numero_factura DESC
            ";
            
            $result = $this->db->query($query);
            $facturas = [];
            
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    try {
                        $modalidad = $this->getModalidad($row['factura']);
                    } catch (Exception $e) {
                        $modalidad = 'N/A';
                        error_log("Error obteniendo modalidad para factura {$row['factura']}: " . $e->getMessage());
                    }
                    
                    $facturas[] = [
                        'numero_factura' => $row['factura'],
                        'fecha_solicitud' => $row['solicitud'],
                        'banco' => $row['banco'],
                        'total' => floatval($row['total2']),
                        'pagado' => floatval($row['pagado']),
                        'resta' => floatval($row['resta']),
                        'activo' => $row['activo'],
                        'modalidad' => $modalidad
                    ];
                }
            }
            
            return $facturas;
            
        } catch (Exception $e) {
            error_log("Error en getFacturasActivas: " . $e->getMessage());
            error_log("Query: " . $query);
            return [];
        }
    }
    
    /**
     * Obtener modalidad de pago de una factura
     */
    private function getModalidad($factura) {
        $facturaEscaped = $this->db->escape($factura);
        
        // Consulta corregida para evitar errores de GROUP BY
        $query = "SELECT DISTINCT marca_consulta, ifnull(modo,0) as modo 
                  FROM t_clientes_creditos tcc
                  LEFT JOIN (
                      SELECT cid, MAX(modo) as modo
                      FROM t_lista_voucher 
                      WHERE cid='$facturaEscaped'
                      GROUP BY cid
                  ) as a ON a.cid = tcc.numero_factura
                  WHERE tcc.numero_factura='$facturaEscaped'";
        
        $result = $this->db->query($query);
        $forma_contrato = [];
        
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                if ($row['marca_consulta'] == 6) {
                    switch($row['modo']) {
                        case 0: $forma_contrato[] = 'Voucher'; break;
                        case 1: $forma_contrato[] = 'Transferencia'; break;
                        case 2: $forma_contrato[] = 'Cotero'; break;
                    }
                } else {
                    $forma_contrato[] = 'Domiciliacion';
                }
            }
        }
        
        $arreglo = array_unique($forma_contrato);
        $cantidad = count($arreglo);
        
        if ($cantidad == 1) {
            return $arreglo[0];
        } else {
            if (in_array('Transferencia', $arreglo)) {
                return 'Mixto-T';
            }
            if (in_array('Cotero', $arreglo)) {
                return 'Mixto-CT';
            }
            return 'Mixto-V';
        }
    }
    
    /**
     * Obtener contratos de una factura
     */
    public function getContratosPorFactura($documentoId, $numeroFactura) {
        try {
            $documentoIdEscaped = $this->db->escape($documentoId);
            $facturaEscaped = $this->db->escape($numeroFactura);
            
            $query = "SELECT DISTINCT contrato_id, numero_factura, monto_total, numero_cuotas, monto_cuota
                      FROM t_clientes_creditos 
                      WHERE documento_id = '$documentoIdEscaped' 
                      AND numero_factura = '$facturaEscaped'
                      ORDER BY contrato_id";
            
            $result = $this->db->query($query);
            $contratos = [];
            
            while ($row = $result->fetch_assoc()) {
                $contratos[] = [
                    'contrato_id' => $row['contrato_id'],
                    'numero_factura' => $row['numero_factura'],
                    'monto_total' => floatval($row['monto_total']),
                    'numero_cuotas' => intval($row['numero_cuotas']),
                    'monto_cuota' => floatval($row['monto_cuota'])
                ];
            }
            
            return $contratos;
            
        } catch (Exception $e) {
            return [];
        }
    }
    
    /**
     * Obtener detalle de pagos de un contrato
     */
    public function getDetallePagos($documentoId, $contratoId) {
        try {
            $documentoIdEscaped = $this->db->escape($documentoId);
            $contratoIdEscaped = $this->db->escape($contratoId);
            
            // Obtener información del contrato
            $queryContrato = "SELECT monto_total, numero_cuotas, monto_cuota, forma_contrato
                             FROM t_clientes_creditos 
                             WHERE documento_id='$documentoIdEscaped' AND contrato_id='$contratoIdEscaped'
                             LIMIT 1";
            
            $resultContrato = $this->db->query($queryContrato);
            
            if ($resultContrato->num_rows === 0) {
                return null;
            }
            
            $contrato = $resultContrato->fetch_assoc();
            $montoTotal = floatval($contrato['monto_total']);
            $numeroCuotas = intval($contrato['numero_cuotas']);
            $montoCuota = floatval($contrato['monto_cuota']);
            
            // Obtener lista de pagos
            $queryPagos = "SELECT 
                              t_lista_cobros.*,
                              ifnull(t_modalidad.nomb,'SIN MODALIDAD') as MODALIDAD
                          FROM t_lista_cobros 
                          LEFT JOIN t_modalidad ON t_modalidad.oid = t_lista_cobros.moda
                          WHERE t_lista_cobros.documento_id='$documentoIdEscaped' 
                          AND t_lista_cobros.credito_id='$contratoIdEscaped' 
                          ORDER BY t_lista_cobros.fecha";
            
            $resultPagos = $this->db->query($queryPagos);
            
            $pagos = [];
            $montoAcumulado = 0;
            $resta = $montoTotal;
            $cuotasPagadas = 0;
            
            while ($row = $resultPagos->fetch_assoc()) {
                $montoPago = floatval($row['monto']);
                $resta = $resta - $montoPago;
                $montoAcumulado += $montoPago;
                
                if ($montoPago > 0) {
                    $cuotasPagadas++;
                }
                
                $mes = $this->getMesDescripcion(substr($row['fecha'], 5, 2));
                
                $pagos[] = [
                    'numero' => count($pagos) + 1,
                    'mes' => $mes,
                    'fecha_cuota' => $row['fecha'],
                    'monto' => $montoPago,
                    'restante' => $resta,
                    'fecha_descuento' => $row['farc'],
                    'descripcion' => $row['descripcion'],
                    'fecha_carga' => $row['modificado'],
                    'modalidad' => $row['MODALIDAD'],
                    'es_cuota_completa' => abs($montoPago - $montoCuota) < 0.01,
                    'pago_id' => $row['mes'] // ID del pago para generar comprobante
                ];
            }
            
            $totalRestante = $montoTotal - $montoAcumulado;
            $cuotasPendientes = $numeroCuotas - $cuotasPagadas;
            $estatusMoroso = ($cuotasPendientes > 2);
            
            return [
                'resumen' => [
                    'monto_total' => $montoTotal,
                    'monto_acumulado' => $montoAcumulado,
                    'monto_restante' => $totalRestante,
                    'numero_cuotas' => $numeroCuotas,
                    'cuotas_pagadas' => $cuotasPagadas,
                    'cuotas_pendientes' => $cuotasPendientes,
                    'monto_cuota' => $montoCuota,
                    'estatus_moroso' => $estatusMoroso
                ],
                'pagos' => $pagos
            ];
            
        } catch (Exception $e) {
            error_log("Error en getDetallePagos: " . $e->getMessage());
            error_log("Documento: $documentoId, Contrato: $contratoId");
            throw $e;
        }
    }
    
    /**
     * Obtener descripción del mes
     */
    private function getMesDescripcion($mes) {
        $meses = [
            '01' => 'ENERO', '02' => 'FEBRERO', '03' => 'MARZO',
            '04' => 'ABRIL', '05' => 'MAYO', '06' => 'JUNIO',
            '07' => 'JULIO', '08' => 'AGOSTO', '09' => 'SEPTIEMBRE',
            '10' => 'OCTUBRE', '11' => 'NOVIEMBRE', '12' => 'DICIEMBRE'
        ];
        
        return isset($meses[$mes]) ? $meses[$mes] : '';
    }
}

