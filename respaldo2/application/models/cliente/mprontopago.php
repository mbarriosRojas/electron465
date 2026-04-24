<?php
/*
 *  @author Carlos Enrique Peña Albarrán
 *  @package SaGem.system.application
 *  @version 1.0.0
 */

class MProntopago extends Model {

	public function __construct() {
		parent::__construct();
	}


	/**
	 * Salvar Objecto
	 * @return true
	 */
	function Verifica($cedula = '',$factura='') {
		$query="SELECT documento_id,tbl.numero_factura AS factura, 
		b.mt AS total, 
		b.mt2 as total2,
		pagado as pagado_d,IFNULL(pg_voucher,0)as pagado_v,(pagado+IFNULL(pg_voucher,0)) as total_pagado,
		b.mt2 - pagado - IFNULL(pg_voucher,0) AS resta, 
		IF(b.mt2=0,'N','A') AS activo,count(*),monto_operacion
FROM t_clientes_creditos
JOIN (SELECT numero_factura,sum(monto_total)as mt,sum(cantidad)as mt2 
		from t_clientes_creditos 
		where t_clientes_creditos.documento_id='".$cedula."' and t_clientes_creditos.numero_factura ='".$factura."'  
		group by numero_factura
		)as b on b.numero_factura = t_clientes_creditos.numero_factura 
JOIN(SELECT t_clientes_creditos.numero_factura, monto_total, IFNULL( SUM( monto ) , 0 ) AS pagado
		FROM t_clientes_creditos
		LEFT JOIN t_lista_cobros ON t_clientes_creditos.contrato_id = t_lista_cobros.credito_id 
			AND t_clientes_creditos.documento_id = t_lista_cobros.documento_id 
		where t_clientes_creditos.documento_id='".$cedula."' and t_clientes_creditos.numero_factura ='".$factura."'
		GROUP BY numero_factura)
		AS tbl ON tbl.numero_factura=t_clientes_creditos.numero_factura
LEFT JOIN 
		(SELECT t_clientes_creditos.numero_factura, monto_total as mtov, 
		IFNULL(tot_voucher,0) as pg_voucher
		FROM t_clientes_creditos
		LEFT JOIN (SELECT cid,sum(monto) AS tot_voucher 
					FROM t_lista_voucher 
					WHERE t_lista_voucher.estatus = 3 OR t_lista_voucher.estatus = 1 OR t_lista_voucher.estatus = 6
					GROUP BY cid
			)AS auxt on auxt.cid = t_clientes_creditos.numero_factura
			where t_clientes_creditos.documento_id='".$cedula."' and t_clientes_creditos.numero_factura ='".$factura."' 
			AND t_clientes_creditos.marca_consulta = 6 
		GROUP BY numero_factura) 
		AS tblV ON tblV.numero_factura=t_clientes_creditos.numero_factura
where t_clientes_creditos.documento_id='".$cedula."' and t_clientes_creditos.numero_factura ='".$factura."'
		and t_clientes_creditos.motivo=2
		
GROUP BY t_clientes_creditos.numero_factura
ORDER BY fecha_solicitud, total_pagado";
		$consulta = $this -> db -> query($query);
		$cantidad = $consulta -> num_rows();
		$msj='';
		if($cantidad == 0){
			$msj = 'No aplica Pronto Pago.';
		}else{
			foreach ($consulta -> result() as $row){
				$contado = $row -> monto_operacion;
				if($contado == 0){
					$msj = 'No se puede calcular ya que el monto de operacion de la factura es 0.00';
				}else{
					$pagado = $row -> total_pagado;
					if($pagado >= $contado) $msj='SI aplica pronto pago';
					else $msj='NO aplica pronto pago';
				}
			}
		}
		return $msj;
	}

}
?>