<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Motor compartido de distribución de un pago (en USD) entre los VARIOS
 * contratos que puede tener una misma cédula — usado tanto por el flujo
 * genérico (Banfan/Bicentenario, mrespuestabanco.php) como por Venezuela
 * (mcargavenezuela.php), sin duplicar la lógica en cada uno.
 *
 * Regla de negocio (confirmada por el usuario):
 *   - Se ordenan los contratos de la cédula por fecha_inicio_cobro (el más
 *     antiguo primero).
 *   - Modo MOROSO: para cada contrato, primero se cubre su deuda VENCIDA
 *     (cuotas de meses anteriores al de referencia, sin pagar). Si el
 *     contrato no tiene nada vencido, se le abona a su cuota del mes
 *     ACTUAL/siguiente pendiente (igual que Activos). El sobrante de un
 *     contrato pasa siempre al SIGUIENTE contrato, nunca se re-aplica al
 *     mismo (ej.: contrato viejo debe 2 cuotas de $10 = $20, llega un pago
 *     de $30 → se le aplican $20 y quedan $10 para el próximo contrato).
 *   - Modo REGULAR: no acumula atrasos — cada contrato solo recibe lo que
 *     le corresponde de su cuota del mes actual, en el mismo orden.
 *   - Filtro por banco: la búsqueda de contratos se restringe al linaje
 *     (cobrado_en) del archivo que se está procesando. Un cliente puede
 *     tener un contrato en Venezuela y otro en Bicentenario — un pago que
 *     llega por el archivo de un banco NO debe repartirse contra el
 *     contrato registrado bajo el otro banco.
 */
class Mcascadapagos extends Model {

    public function __construct() {
        parent::__construct();
    }

    /**
     * @param string $cedula
     * @param float  $montoDisponibleUsd
     * @param string $modo 'MOROSO' o 'REGULAR'
     * @param string $fechaReferencia Y-m-d (fecha de cobro/nómina que dispara el pago)
     * @param string $banco Linaje del archivo que se está procesando (VENEZUELA,
     *   BICENTENARIO, BANFAN, ...). Si se indica, la cascada solo reparte entre
     *   los contratos de la cédula registrados bajo ese mismo banco (cobrado_en) —
     *   un contrato de otro linaje no debe verse afectado por este pago.
     * @param array $excluirContratos IDs de contrato a NO considerar en el
     *   reparto (ya se les aplicó su parte por fuera de esta llamada — ver
     *   montoObjetivoContrato() — para no volver a evaluarlos ni asignarles
     *   de más).
     * @return array ['asignaciones' => [['contrato_id'=>.., 'monto_usd'=>..], ...], 'sobrante' => float]
     */
    public function distribuirPago($cedula, $montoDisponibleUsd, $modo, $fechaReferencia, $banco = '', $excluirContratos = array()) {
        $modo = (strtoupper(trim($modo)) === 'MOROSO') ? 'MOROSO' : 'REGULAR';
        $disponible = round((float) $montoDisponibleUsd, 2);
        $asignaciones = array();

        if ($disponible <= 0) {
            return array('asignaciones' => $asignaciones, 'sobrante' => 0.0);
        }

        $cutoff = $this->normalizarFecha($fechaReferencia);
        if ($cutoff === null) {
            return array('asignaciones' => $asignaciones, 'sobrante' => $disponible);
        }
        $cutoffMesAnterior = $this->finDeMesAnterior($cutoff);

        $contratos = $this->contratosActivosOrdenados($cedula, $banco, $excluirContratos);

        foreach ($contratos as $row) {
            if ($disponible <= 0) {
                break;
            }

            $montoObjetivo = 0.0;
            if ($modo === 'MOROSO') {
                $montoObjetivo = $this->montoVencido($row, $cutoffMesAnterior);
                if ($montoObjetivo <= 0) {
                    $montoObjetivo = $this->montoCuotaActual($row, $cutoff);
                }
            } else {
                $montoObjetivo = $this->montoCuotaActual($row, $cutoff);
            }

            if ($montoObjetivo <= 0) {
                continue;
            }

            $aplicado = round(min($disponible, $montoObjetivo), 2);
            if ($aplicado <= 0) {
                continue;
            }

            $asignaciones[] = array(
                'contrato_id' => $row->contrato_id,
                'monto_usd' => $aplicado,
            );
            $disponible = round($disponible - $aplicado, 2);
        }

        return array('asignaciones' => $asignaciones, 'sobrante' => max($disponible, 0.0));
    }

    /**
     * Garantiza que la suma de las asignaciones sea igual al monto cobrado:
     * si queda una diferencia positiva (sobrante que la cascada no pudo
     * ubicar porque todos los contratos llegaron a su objetivo), se suma al
     * ÚLTIMO contrato del reparto. Sin esto, el dinero cobrado por el banco
     * en exceso del objetivo desaparecía al guardar (ej. archivo Regular y
     * Moroso del mismo día: el primero baja la deuda vencida y el segundo
     * quedaba topado en lo que restaba).
     *
     * @param array $asignaciones [['contrato_id'=>.., 'monto_usd'=>..], ...] (no vacío)
     * @param float $montoTotalUsd Monto total realmente cobrado
     * @return array Las mismas asignaciones, con el sobrante sumado al último.
     */
    public function abonarSobranteAlUltimo(array $asignaciones, $montoTotalUsd) {
        if (empty($asignaciones)) {
            return $asignaciones;
        }
        $asignado = 0.0;
        foreach ($asignaciones as $asig) {
            $asignado += (float) $asig['monto_usd'];
        }
        $sobrante = round((float) $montoTotalUsd - $asignado, 2);
        if ($sobrante > 0) {
            $ultimo = count($asignaciones) - 1;
            $asignaciones[$ultimo]['monto_usd'] = round((float) $asignaciones[$ultimo]['monto_usd'] + $sobrante, 2);
        }
        return $asignaciones;
    }

    /**
     * Igual que distribuirPago(), pero para VARIOS pagos seguidos de la
     * MISMA cédula que deben repartirse contra el mismo conjunto de
     * contratos, compartiendo un solo objetivo por contrato en vez de que
     * cada pago lo recalcule desde cero contra la BD (que no refleja los
     * pagos anteriores de este mismo lote, todavía no insertados en
     * t_lista_cobros). Sin esto, dos pagos de la misma cédula en el mismo
     * archivo pueden "pisarse": el segundo cree que la cuota del contrato
     * más viejo sigue libre y se la vuelve a llenar, dejando el excedente
     * real sin repartir al siguiente contrato.
     *
     * @param string $cedula
     * @param array $pagos Lista ordenada de ['monto_usd'=>float, 'fecha'=>'Y-m-d']
     *   (la fecha de referencia de CADA pago; normalmente la misma para
     *   todos los pagos de un mismo archivo, pero se respeta si difiere).
     * @param string $modo 'MOROSO' o 'REGULAR'
     * @param string $banco Linaje (cobrado_en) a restringir, igual que distribuirPago().
     * @param array $excluirContratos IDs de contrato a NO considerar (ej.:
     *   contratos de una periocidad/día de cobro distinta a la del archivo
     *   que se está procesando).
     * @return array Un ['asignaciones'=>[...], 'sobrante'=>float] por cada
     *   entrada de $pagos, en el mismo orden.
     */
    public function distribuirPagoMultiple($cedula, array $pagos, $modo, $banco = '', $excluirContratos = array()) {
        $modo = (strtoupper(trim($modo)) === 'MOROSO') ? 'MOROSO' : 'REGULAR';
        $contratos = $this->contratosActivosOrdenados($cedula, $banco, $excluirContratos);

        // Objetivo pendiente por contrato: se calcula UNA sola vez por
        // contrato (con la fecha del primer pago que lo toca) y luego se va
        // descontando a medida que los pagos de este mismo lote lo cubren —
        // ese descuento es lo que la BD todavía no puede reflejar.
        $objetivoRestante = array();

        $resultados = array();
        foreach ($pagos as $pago) {
            $disponible = round((float) $pago['monto_usd'], 2);
            $asignaciones = array();

            $cutoff = $this->normalizarFecha($pago['fecha']);
            $cutoffMesAnterior = $cutoff !== null ? $this->finDeMesAnterior($cutoff) : null;

            if ($disponible > 0 && $cutoff !== null) {
                foreach ($contratos as $row) {
                    if ($disponible <= 0) {
                        break;
                    }
                    $cid = $row->contrato_id;

                    if (!array_key_exists($cid, $objetivoRestante)) {
                        if ($modo === 'MOROSO') {
                            $obj = $this->montoVencido($row, $cutoffMesAnterior);
                            if ($obj <= 0) {
                                $obj = $this->montoCuotaActual($row, $cutoff);
                            }
                        } else {
                            $obj = $this->montoCuotaActual($row, $cutoff);
                        }
                        $objetivoRestante[$cid] = $obj;
                    }

                    if ($objetivoRestante[$cid] <= 0) {
                        continue;
                    }

                    $aplicado = round(min($disponible, $objetivoRestante[$cid]), 2);
                    if ($aplicado <= 0) {
                        continue;
                    }

                    $asignaciones[] = array('contrato_id' => $cid, 'monto_usd' => $aplicado);
                    $disponible = round($disponible - $aplicado, 2);
                    $objetivoRestante[$cid] = round($objetivoRestante[$cid] - $aplicado, 2);
                }
            }

            $resultados[] = array('asignaciones' => $asignaciones, 'sobrante' => max($disponible, 0.0));
        }

        return $resultados;
    }

    /**
     * Contratos de la cédula con saldo pendiente, ordenados del más antiguo
     * al más reciente por fecha_inicio_cobro. Si se indica $banco, se
     * restringe a los contratos registrados bajo ese mismo linaje
     * (cobrado_en) — un cliente puede tener un contrato en Venezuela y
     * otro en Bicentenario, y un pago que llega por el archivo de un
     * banco NO debe repartirse contra el contrato del otro banco.
     *
     * @param array $excluir IDs de contrato a excluir del resultado.
     */
    private function contratosActivosOrdenados($cedula, $banco = '', $excluir = array()) {
        $sql = 'SELECT c.contrato_id, c.fecha_inicio_cobro, c.numero_cuotas, c.periocidad, c.monto_cuota,
                (c.monto_total - IFNULL(SUM(lc.monto), 0)) AS resta,
                IFNULL(SUM(lc.monto), 0) AS total_pagado
            FROM t_clientes_creditos c
            LEFT JOIN t_lista_cobros lc ON lc.credito_id = c.contrato_id
            WHERE c.documento_id = ' . $this->db->escape($cedula) . '
            AND c.marca_consulta != 6
            AND c.cantidad != 0
            AND c.estatus != 3';
        $banco = strtoupper(trim($banco));
        if ($banco !== '') {
            $sql .= ' AND UPPER(TRIM(c.cobrado_en)) = ' . $this->db->escape($banco);
        }
        if (!empty($excluir)) {
            $idsEsc = array();
            foreach ($excluir as $id) {
                $idsEsc[] = $this->db->escape($id);
            }
            $sql .= ' AND c.contrato_id NOT IN (' . implode(',', $idsEsc) . ')';
        }
        $sql .= ' GROUP BY c.contrato_id, c.fecha_inicio_cobro, c.numero_cuotas, c.periocidad, c.monto_cuota, c.monto_total
            HAVING resta > 0
            ORDER BY c.fecha_inicio_cobro ASC, c.contrato_id ASC';
        $rs = $this->db->query($sql);
        if (!$rs || $rs->num_rows() === 0) {
            return array();
        }
        return $rs->result();
    }

    /**
     * Cuánto le corresponde a UN contrato específico (sin repartir nada, sin
     * tocar otros contratos de la cédula) — para cuando ya se sabe que un
     * pago pertenece a ese contrato puntual (ej.: domiciliación propia en
     * Venezuela, una por contrato) y solo el SOBRANTE, si lo hay, debe
     * pasar a la cascada general de la cédula (ver distribuirPago() con
     * $excluirContratos).
     *
     * @param string $contratoId
     * @param string $modo 'MOROSO' o 'REGULAR'
     * @param string $fechaReferencia Y-m-d
     * @return float 0 si el contrato no existe, no tiene saldo, o no aplica.
     */
    public function montoObjetivoContrato($contratoId, $modo, $fechaReferencia) {
        $modo = (strtoupper(trim($modo)) === 'MOROSO') ? 'MOROSO' : 'REGULAR';
        $cutoff = $this->normalizarFecha($fechaReferencia);
        if ($cutoff === null) {
            return 0.0;
        }
        $cutoffMesAnterior = $this->finDeMesAnterior($cutoff);

        $sql = 'SELECT c.contrato_id, c.fecha_inicio_cobro, c.numero_cuotas, c.periocidad, c.monto_cuota,
                (c.monto_total - IFNULL(SUM(lc.monto), 0)) AS resta,
                IFNULL(SUM(lc.monto), 0) AS total_pagado
            FROM t_clientes_creditos c
            LEFT JOIN t_lista_cobros lc ON lc.credito_id = c.contrato_id
            WHERE c.contrato_id = ' . $this->db->escape($contratoId) . '
            AND c.marca_consulta != 6
            AND c.cantidad != 0
            AND c.estatus != 3
            GROUP BY c.contrato_id, c.fecha_inicio_cobro, c.numero_cuotas, c.periocidad, c.monto_cuota, c.monto_total
            HAVING resta > 0';
        $rs = $this->db->query($sql);
        if (!$rs || $rs->num_rows() === 0) {
            return 0.0;
        }
        $row = $rs->row();

        if ($modo === 'MOROSO') {
            $objetivo = $this->montoVencido($row, $cutoffMesAnterior);
            if ($objetivo <= 0) {
                $objetivo = $this->montoCuotaActual($row, $cutoff);
            }
            return $objetivo;
        }
        return $this->montoCuotaActual($row, $cutoff);
    }

    /**
     * Deuda vencida: cuotas de meses ANTERIORES al de referencia, sin cubrir
     * por lo ya pagado — mismo criterio que calcularMontoMorosos() en los
     * generadores de TXT (portado aquí como copia independiente, no se
     * comparte código con esos archivos para no arriesgar tocarlos).
     */
    private function montoVencido($row, $cutoffMesAnterior) {
        $montoCuota = (float) $row->monto_cuota;
        if ($montoCuota <= 0) {
            return 0.0;
        }

        $totalPagado = (float) $row->total_pagado;
        $plan = $this->gPlanCobro($row->fecha_inicio_cobro, (int) $row->numero_cuotas, (string) $row->periocidad);
        $restantePago = $totalPagado;
        $adeudado = 0.0;
        $cutoffTs = strtotime($cutoffMesAnterior);

        foreach ($plan as $cuota) {
            $fechaCuota = $this->parseFechaPlan($cuota['fecha']);
            if ($fechaCuota === null || $fechaCuota > $cutoffTs) {
                break;
            }

            $aplicado = 0.0;
            if ($restantePago > 0) {
                if ($restantePago >= $montoCuota) {
                    $aplicado = $montoCuota;
                    $restantePago -= $montoCuota;
                } else {
                    $aplicado = $restantePago;
                    $restantePago = 0;
                }
            }

            $pendiente = $montoCuota - $aplicado;
            if ($pendiente > 0) {
                $adeudado += $pendiente;
            }
        }

        return round($adeudado, 2);
    }

    /**
     * Cuota del mes de referencia (ni antes ni después) — mismo criterio que
     * calcularMontoActivos() en los generadores de TXT.
     */
    private function montoCuotaActual($row, $cutoff) {
        $montoCuota = (float) $row->monto_cuota;
        if ($montoCuota <= 0) {
            return 0.0;
        }

        $anoMesBuscado = substr($cutoff, 0, 7);
        $totalPagado = (float) $row->total_pagado;
        $plan = $this->gPlanCobro($row->fecha_inicio_cobro, (int) $row->numero_cuotas, (string) $row->periocidad);
        if (empty($plan)) {
            return 0.0;
        }

        $ultimaCuota = end($plan);
        $fechaFinContrato = $this->parseFechaPlan($ultimaCuota['fecha']);
        if ($fechaFinContrato !== null && date('Y-m', $fechaFinContrato) < $anoMesBuscado) {
            return 0.0;
        }

        $restantePago = $totalPagado;

        foreach ($plan as $cuota) {
            $fechaCuota = $this->parseFechaPlan($cuota['fecha']);
            if ($fechaCuota === null) {
                continue;
            }
            $anoMesCuota = date('Y-m', $fechaCuota);

            $aplicado = 0.0;
            if ($restantePago > 0) {
                if ($restantePago >= $montoCuota) {
                    $aplicado = $montoCuota;
                    $restantePago -= $montoCuota;
                } else {
                    $aplicado = $restantePago;
                    $restantePago = 0;
                }
            }

            if ($anoMesCuota === $anoMesBuscado) {
                return round(max($montoCuota - $aplicado, 0), 2);
            }
            if ($anoMesCuota > $anoMesBuscado) {
                break;
            }
        }

        return 0.0;
    }

    /** Último día del mes anterior al de $fecha (Y-m-d). */
    private function finDeMesAnterior($fecha) {
        $ts = strtotime($fecha);
        $primerDiaMesActual = date('Y-m-01', $ts);
        return date('Y-m-d', strtotime($primerDiaMesActual . ' -1 day'));
    }

    private function normalizarFecha($fecha) {
        $fecha = trim((string) $fecha);
        if ($fecha === '') {
            return null;
        }
        $ts = strtotime($fecha);
        return $ts ? date('Y-m-d', $ts) : null;
    }

    /** Plan de cuotas (fecha estimada de cada cuota) — copia independiente. */
    private function gPlanCobro($sFec, $iCuo, $iPer) {
        $arr = array();
        $fechap = explode('-', $sFec);
        if (count($fechap) < 3) {
            return $arr;
        }

        $dia_inicio = (int) $fechap[2];
        $mes_inicio = (int) $fechap[1];
        $ano_inicio = (int) $fechap[0];

        for ($i = 1; $i <= $iCuo; $i++) {
            $base_mes = 1;
            switch ($iPer) {
                case '0': $base_mes = 1 / 4; break;
                case '1':
                case '2':
                case '3': $base_mes = 1 / 2; break;
                case '4':
                case '5':
                case '6':
                case '8':
                case '9': $base_mes = 1; break;
                case '7': $base_mes = 12; break;
            }

            $tiempo = ($i - 1) * $base_mes;
            $tiempo_picado = explode('.', (string) $tiempo);
            $ano_t = (int) ($tiempo_picado[0] / 12) + $ano_inicio;
            $mes_t = $tiempo_picado[0] % 12;
            $dia_t = $dia_inicio;

            if (isset($tiempo_picado[1])) {
                switch ($tiempo_picado[1]) {
                    case '25': $dia_t += 7; break;
                    case '5': $dia_t += 15; break;
                    case '75': $dia_t += 21; break;
                }
            }

            if ($dia_t > 30) {
                $mes_t += 1;
                $dia_t -= 30;
            }

            $suma_meses = $mes_t + $mes_inicio;
            if ($suma_meses > 12) {
                $ano_t += 1;
                $mes_t = $suma_meses - 12;
            } else {
                $mes_t = $suma_meses;
            }

            $arr[] = array(
                'cuota' => $i,
                'fecha' => $dia_t . '-' . sprintf('%02d', $mes_t) . '-' . $ano_t,
            );
        }

        return $arr;
    }

    private function parseFechaPlan($fecha) {
        $partes = explode('-', $fecha);
        if (count($partes) !== 3) {
            return null;
        }
        $ts = mktime(0, 0, 0, (int) $partes[1], (int) $partes[0], (int) $partes[2]);
        return $ts ? $ts : null;
    }
}
