<?php
/*
 *  @author Carlos Enrique Peña Albarrán
 *  @package cooperativa.system.application.model.cliente
 *  @version 1.0.0
 */

class MContrato extends Model {

	var $tabla = 't_estadoejecucion';

	public function __construct() {
		parent::__construct();
	}

	/**
	 * Cambiar un grupo de Contrato a otro estado...
	 */
	function AceptarFactura($sFactura) {
		$sConsulta = "SELECT numero_factura, contrato_id, condicion FROM t_clientes_creditos WHERE numero_factura ='" . $sFactura . "'";
		$Exec = $this -> db -> query($sConsulta);
		$rs = $Exec -> result();
		foreach ($rs as $row) {
			$destino = 4;
			if($row -> condicion != 0)$destino = 5;
			
			/*$sQuery = "SELECT * FROM t_clientes_creditos INNER JOIN t_cheque_garantia ON
			t_cheque_garantia.factura=t_clientes_creditos.numero_factura
			WHERE contrato_id='" . $row -> contrato_id . "' LIMIT 1";
			$Consulta = $this -> db -> query($sQuery);
	
			if ($Consulta -> num_rows() > 0) {
				$sSql = 'UPDATE t_estadoejecucion SET oide ='.$destino.', observacion=\'Aceptacion Por Factura\', estatus=1 WHERE oidc = \'' . $row -> contrato_id . '\' LIMIT 1';
				$this -> db -> query($sSql);
			} else {
				return "Debe Ingresar Cheque por Garantia en el Modulo de Expediente Digital";
				
			}*/
			$sSql = 'UPDATE t_estadoejecucion SET oide ='.$destino.', observacion=\'Aceptacion Por Factura\', estatus=1 WHERE oidc = \'' . $row -> contrato_id . '\' LIMIT 1';
			$this -> db -> query($sSql);
		}
		return "Se acepto la Factura";
	}

}
?>