<?php
/**
 * Controlador de Barra de menu
 *
 * @author Carlos Enrique Peña Albarrán
 * @package cooperativa.system.application.model.reporte
 * @version 2.0.0
 */
class PDomiciliacion extends Model {

	var $cedula = '';

	var $nombre = '';

	var $descripcion = '';

	var $Banco = '';

	var $cuenta = '';

	var $direccion = '';

	var $lista = '';

	var $linaje = '';

	var $empresa = '';

	var $nomina = '';

	/**
 	* Usuario Responsable...
 	*/
	var $usuario = '';

	var $total_suma = 0;

	var $detalles = '';

	public function __construct() {
		parent::__construct();

	}

	/**
 	* Generar Reportes de Domiciliacion Por Contrato
 	*/
	function Imprimir($sFormato = null, $iCedula = null, $sContrato = null) {
		$oCabezera = array();
		$oFil = array();
		
		$this -> descripcion = 'MONTO TOTAL ACREDITADO ';
		$sCon = 'SELECT
		CONCAT(primer_apellido,\' \',LEFT(segundo_apellido,1),\'. \',primer_nombre,\' \', LEFT(segundo_nombre,1),\'. \') AS nom,
		CONCAT(\'AV.\', avenida,\' CALLE \', calle,\' MUNICIPIO \', municipio,\' SECTOR \', sector,\' CASA # \', direccion) AS dir,
		banco_1, cuenta_1,CONCAT(codigo_n,\' (\', expediente_c, \')\') AS responsable, contrato_id, empresa, forma_contrato,estatus,
		nomina_procedencia, cobrado_en AS linaje,fecha_inicio_cobro, monto_cuota, numero_cuotas
		FROM t_personas JOIN t_clientes_creditos ON (t_personas.documento_id=t_clientes_creditos.documento_id)
		WHERE t_clientes_creditos.contrato_id = \'' . $sContrato . '\' LIMIT 1';
		$Consulta = $this -> db -> query($sCon);
		if($Consulta -> num_rows() != 0) {
			$oCabezera[1] = array("titulo" => "REFERENCIA");
			$oCabezera[2] = array("titulo" => "CUOTAS");
			$oCabezera[3] = array("titulo" => "PERIOCIDAD");
			$oCabezera[4] = array("titulo" => "MONTO");
			$oCabezera[5] = array("titulo" => "LINAJE");
			$rs = $Consulta -> result();
			$this -> nombre = $rs[0] -> nom;
			$this -> Banco = $rs[0] -> banco_1;
			$this -> cuenta = $rs[0] -> cuenta_1;
			$this -> direccion = $rs[0] -> dir;
			$this -> usuario = $rs[0] -> responsable;
			$Aceptado = $rs[0] -> estatus;
			$this -> nomina = $rs[0] -> nomina_procedencia;
			$this -> linaje = $rs[0] -> linaje;
			$i = 0;
			foreach($rs as $rw) {
				++$i;
				if($rw -> forma_contrato == 4) {
					$this -> descripcion = 'REFERENCIA (' . $rw -> contrato_id . ') POR  MONTO MENSUAL DEL DESCUENTO ';
					if($row -> periocidad == 2) {
						$this -> descripcion = 'REFERENCIA (' . $rw -> contrato_id . ') POR MONTO QUINCENAL DEL DESCUENTO ';
					}
					if($rw -> forma_contrato > 3) {
						$this -> total_suma = $rw -> monto_cuota;
					} else {
						$strContrato = 'UNICO ';
						$ano = substr($rw -> fecha_inicio_cobro, 0, 4);
						$mes = substr($rw -> fecha_inicio_cobro, 5, 2);
						$strPericidad = '';
						switch ($rw->periocidad) {
							case 0 :
								$strPericidad = 'SEMANAL ';
								break;
							case 1 :
								$strPericidad = 'CATORCENAL ';
								break;
							case 2 :
								$strPericidad = 'QUINCENAL 15 - 30 ';
								break;
							case 3 :
								$strPericidad = 'QUINCENAL 10 - 25 ';
								break;
							case 4 :
								$strPericidad = 'MENSUAL ';
								break;
						}
						switch ($rw->forma_contrato) {
							case 0 :
								$strContrato = $strPericidad;
								break;
							case 1 :
								$strContrato = 'DESDE 1 ' . $this -> Mes_Activo($mes) . '  DE HASTA  EL 30 ' . $this -> Mes_Activo($mes) . '  DEL ' . $ano;
								break;
							case 2 :
								$strContrato = 'DESDE 1 ' . $this -> Mes_Activo($mes) . '  DE HASTA  EL 30 ' . $this -> Mes_Activo($mes) . ' DEL ' . $ano;
								break;
							case 3 :
								$strContrato = 'EXTRA DESDE 1 ' . $this -> Mes_Activo($mes) . '  DE HASTA  EL 30 ' . $this -> Mes_Activo($mes) . ' DEL ' . $ano;
								break;
							default :
								$strContrato = 'DESDE 1 ' . $this -> Mes_Activo($mes) . ' DE HASTA  EL 30 ' . $this -> Mes_Activo($mes) . ' DEL ' . $ano;
						}
						$this -> total_suma += $rw -> monto_total;
						$oFil[$i] = array("1" => $rw -> contrato_id, "2" => $rw -> numero_cuotas, "3" => $strPericidad, "4" =>  number_format($rw -> monto_cuota, 2, ".", ","), "5" => $rw -> linaje);
					}
				}
			}
		}
		$oTable = array("Cabezera" => $oCabezera, "Cuerpo" => $oFil, "Css" => "", "Obejtos" => Array(), "Origen" => "json");
		$this -> lista = $oTable;
		$this -> detalles = ' DETALLES: ' . $this -> descripcion . number_format($this -> total_suma, 2, ".", ",") . ' Bs.';
		print_r($oTable);
		$this->load->view('reportes/domiciliacion', $oTable);
	}

  function ImprimirPreimpresos(){
  	return true;
  }

	private function Mes_Activo($iMes) {
		$mes = "";
		switch ($iMes) {
			case 1 :
				$mes = "ENERO";
				break;
			case 2 :
				$mes = "FEBRERO";
				break;
			case 3 :
				$mes = "MARZO";
				break;
			case 4 :
				$mes = "ABRIL";
				break;
			case 5 :
				$mes = "MAYO";
				break;
			case 6 :
				$mes = "JUNIO";
				break;
			case 7 :
				$mes = "JULIO";
				break;
			case 8 :
				$mes = "AGOSTO";
				break;
			case 9 :
				$mes = "SEPTIEMBRE";
				break;
			case 10 :
				$mes = "OCTUBRE";
				break;
			case 11 :
				$mes = "NOVIEMBRE";
				break;
			case 12 :
				$mes = "DICIEMBRE";
				break;
		}
		return $mes;
	}

}
?>