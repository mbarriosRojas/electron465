<?php

/**
 *  @author Carlos Enrique Peña Albarrán
 *  @package SaGem.system.application
 *  @version 1.0.0
 */

class CBancos extends Model {

	/**
	 * @var string
	 */
	private $txtBicentenario;

	/**
	 * @var string
	 */
	private $txtDelSur;

	/**
	 * @var string
	 */
	private $txtVenezuela;

	/**
	 * @var string
	 */
	private $txtSofitasa;

	/**
	 * @var string
	 */
	private $txtProvincial;

	/**
	 * @var string
	 */
	private $txtBfc;

	public function __construct() {
		parent::__construct();
	}

	/**
	 *
	 * @return string
	 */
	public function Sofitasa($LstBanco = '') {
		if ($LstBanco != '') {

		}

	}

	public function BFC($intContrato = null, $name = null) {
		$suma = 0;
		$dtFecha = date('Ymd');
		$Hora ='080100';
		$i = 0;
		$str_nombre = BASEPATH . 'repository/' . $name . 'NOM000000201020111.txt';
		$strCabeza = '000000' . $dtFecha . $Hora . $dtFecha . $Hora . 
		'00000000000000000000000001 CC0001510138528138022782 CC0001510138528138022782000000000000000000000000000000000000000000000000000000000000' . PHP_EOL;		
		if (file_exists($str_nombre)) {
			$Archivo = fopen($str_nombre, "w+");
		} else {
			$Archivo = fopen($str_nombre, "xwa+");
		}
		fwrite($Archivo, $strCabeza);
		$set = 'nacionalidad,t_personas.documento_id,monto_cuota,banco_1,cuenta_1';
		$this -> db -> select('*');
		$this -> db -> from('t_personas');
		$this -> db -> join('t_clientes_creditos', 't_personas.documento_id=t_clientes_creditos.documento_id');
		$this -> db -> where('t_clientes_creditos.cobrado_en', 'FONDO COMUN');
		$this -> db -> where('t_clientes_creditos.forma_contrato', $intContrato);
		$this -> db -> like('t_clientes_creditos.fecha_inicio_cobro', '201');
		$this -> db -> group_by('t_clientes_creditos.documento_id');

		$Consulta = $this -> db -> get();
		if ($Consulta -> num_rows() > 0) {
			foreach ($Consulta->result() as $row) {
				++$i;
				$monto_aux = $row->monto_cuota . '00'; //Uniendo con la parte decimal sin putos
			 	$monto = $this->Completar($monto_aux, 15);
				$suma += $row->monto_cuota;
				$strCuerpo = $this->Completar($i,6) . ' LS' . $row->cuenta_1 . 'V' . $this -> Completar($row -> documento_id, 10) . 
				'00001000000000000000' . $monto .
				'D0ABONO A EMPRESA ELECTRON                0000                                        000000000 ' . PHP_EOL;
				fwrite($Archivo, $strCuerpo);
			}
		}		
		$total_aux = $suma . '00'; //Uniendo con la parte decimal sin putos
		$total = $this->Completar($total_aux, 15);
		$strPie = '999999GRUPO ELECTRON CA                       ' . 
		$this->Completar($i,6) . $total .'000000000000000'. 
		$this->Completar($i,6) .'0000000000000000000000000000000000000000000000000000000000000000000000000000000000';		
		fwrite($Archivo, $strPie);
		fclose($Archivo);
	}

	/**
	 * Completar con ceros a la izquierda...
	 *
	 */
	public function Completar($strCadena = '', $intLongitud = '') {
		$strContenido = '';
		$strAux = '';
		$intLen = strlen($strCadena);
		if ($intLen != $intLongitud) {
			$intCount = $intLongitud - $intLen;
			for ($i = 0; $i < $intCount; $i++) {
				$strAux .= '0';
			}
			$strContenido = $strAux . $strCadena;
		}
		return $strContenido;

	}

}
?>