<?php

/*
 *
 */

class PFormatos extends Model {

	var $Void = "";

	var $SP = " ";

	var $Dot = ".";

	var $Zero = "0";

	var $Neg = "Menos";

	public $lstUsr;

	public $usuario;

	public function __construct() {
		parent::__construct();
	}

	/**
	 * Obtener el tipo de empresa desde un contrato
	 */
	private function getEmpresa($sC) {
		$sV[0] = "C";
		//Empresa
		$sV[2] = '';
		$sV[3] = 'RECURSOS HUMANOS';
		$sSql = "SELECT empresa, nomina_procedencia, numero_factura, cobrado_en FROM t_clientes_creditos WHERE contrato_id='" . $sC . "' LIMIT 1";
		$sConsulta = $this -> db -> query($sSql);
		$sV[1] = $sC;
		if ($sConsulta -> num_rows() > 0) {
			$rsC = $sConsulta -> result();
			if ($rsC[0] -> cobrado_en == 'NOMINA') {
				switch($rsC[0] -> nomina_procedencia) {
					case 'GOBERNACION DEL ESTADO MERIDA' :
						$sV[2] = 'GOBERNACION';
						$sV[1] = $rsC[0] -> numero_factura;
						$sV[3] = 'DE LA GOBERNACION';
						break;
					case 'DIRECCION GENERAL DEL PODER POPULAR POLICIA DEL ESTADO MERIDA' :
						$sV[2] = 'GOBERNACION';
						$sV[1] = $rsC[0] -> numero_factura;
						$sV[3] = 'DE LA POLICIA';
						break;
					case 'COORPOSALUD' :
						$sV[1] = $rsC[0] -> numero_factura;
						$sV[2] = 'GOBERNACION';
						$sV[3] = 'DE CORPOSALUD';
						break;
					case 'SAPAM' :
						$sV[1] = $rsC[0] -> numero_factura;
						$sV[2] = 'GOBERNACION';
						$sV[3] = 'DE SAPAM';
						break;
					case 'ALCALDIA DEL LIBERTADOR' :
						$sV[1] = $rsC[0] -> numero_factura;
						$sV[2] = 'GOBERNACION';
						$sV[3] = 'DE ALCALDIA LIBERTADOR';
						break;
					case 'FONVIM' :
						$sV[1] = $rsC[0] -> numero_factura;
						$sV[2] = 'GOBERNACION';
						$sV[3] = 'DE FONVIM';
						break;
					case 'FUNDACION DEL NIÑO SIMON' :
						$sV[1] = $rsC[0] -> numero_factura;
						$sV[2] = 'GOBERNACION';
						$sV[3] = 'DE FUNDACION DEL NIÑO SIMON';
						break;
					case 'IBIME' :
						$sV[1] = $rsC[0] -> numero_factura;
						$sV[2] = 'GOBERNACION';
						$sV[3] = 'DE IBIME';
						break;
					case 'POLICIA VIAL' :
						$sV[1] = $rsC[0] -> numero_factura;
						$sV[2] = 'GOBERNACION';
						$sV[3] = 'DE POLICIA VIAL';
						break;
					case 'DIRECCION GENERAL DEL PODER POPULAR BOMBEROS DEL ESTADO MERIDA' :
						$sV[1] = $rsC[0] -> numero_factura;
						$sV[2] = 'GOBERNACION';
						$sV[3] = 'DE BOMBEROS DEL ESTADO MERIDA';
						break;
					case 'DIRECCION GENERAL DEL PODER POPULAR PARA LA EDUCACION ESTADO MERIDA' :
						$sV[1] = $rsC[0] -> numero_factura;
						$sV[2] = 'GOBERNACION';
						$sV[3] = 'DE EDUCACION';
						break;
					case 'INMECA' :
						$sV[1] = $rsC[0] -> numero_factura;
						$sV[2] = 'GOBERNACION';
						$sV[3] = 'DE INMECA';
						break;

					case 'ULA' :
						$sV[2] = $rsC[0] -> nomina_procedencia;
						break;
				}
			}

			if ($rsC[0] -> empresa == 1) {
				$sV[0] = "G";
			}

		}
		return $sV;
	}

	/**
	 * Obtener el numero desde un contrato
	 */
	private function getFactura() {

	}

	function Fdomiciliacion($sformato, $sCedula = null, $sC, $sEmpresa = null) {

		$sV = $this -> getEmpresa($sC);
		$sTipo = "F";
		$sC = $sV[1];
		$sEmpresa = $sV[0];
		$recursos = $sV[3];
		if ($sV[2] != '') {
			$sformato = $sV[2];
		}
		//echo $sformato, $sCedula, $sC, $sEmpresa;
		switch ($sformato) {
			case 'SOFITASA' :
				$this -> f_sofitasa($sC, $sTipo);
			case 'GOBERNACION' :
				$this -> f_autoriza_gob($sC, $recursos, $sTipo);
				break;
			case 'CREDINFO' :
				$this -> f_autoriza_credinfo($sC, $sTipo);
			case 'ULA' :
				$this -> f_autoriza_ula($sC, $sTipo);
			case 'VENEZUELA' :
				$this -> f_venezuela($sC, $sEmpresa, $sTipo);
				//G=grupo , C=cooperativa
				break;
			case 'CUOTAS' :
				$this -> f_cuotas($sC, $sEmpresa, $sTipo);
				break;
			default :
				$this -> f_autoriza_universal($sC, $sEmpresa, $sTipo);
				//G=grupo , C=cooperativa
				break;
		}
	}

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

	function FInterbancaria($cedula, $cuenta) {
		$pagina1 = array();
		$query = "SELECT CONCAT(primer_nombre,' ',segundo_nombre,' ',primer_apellido,' ',segundo_apellido)AS nombre FROM t_personas  WHERE documento_id='$cedula'";
		$Consulta = $this -> db -> query($query);
		$nombre = '';
		$fecha = date("d-m-Y");
		$arr_fecha = explode('-', $fecha);
		$dia = $arr_fecha[0];
		$mes = $arr_fecha[1];
		$ano = $arr_fecha[2];
		foreach ($Consulta->result() as $row) {
			$nombre = $row -> nombre;
		}

		$inserta = "INSERT INTO t_finterbancaria (documento_id) VALUES(" . $cedula . ")";
		$this -> db -> query($inserta);

		$sCon = 'SELECT MAX(id) AS cantidad FROM t_finterbancaria';
		$rs = $this -> db -> query($sCon);
		$rCon = $rs -> result();
		if ($rCon[0] -> cantidad != null)
			$sVal = 'IN-' . $this -> Completar($rCon[0] -> cantidad + 1, 6);
		else
			$sVal = 'IN-' . $this -> Completar(1, 6);

		//interbancaria
		$pagina1['inter'] = array('texto' => $sVal, 'ancho' => 56, 'x' => 166, 'y' => 46, 'estilo' => '', 'alinea' => 'L');
		//fecha
		$pagina1['dia'] = array('texto' => $fecha, 'ancho' => 56, 'x' => 166, 'y' => 70, 'estilo' => '', 'alinea' => 'L');
		//$pagina1['mes'] = array('texto' => $mes, 'ancho' => 56, 'x' => 145, 'y' => 100, 'estilo' => '');
		//$pagina1['ano'] = array('texto' => $ano, 'ancho' => 56, 'x' => 145, 'y' => 100, 'estilo' => '');
		//yo
		$pagina1['nombre'] = array('texto' => $nombre, 'ancho' => 100, 'x' => 15, 'y' => 83, 'estilo' => '', 'alinea' => 'L');
		//cedula
		$pagina1['cedula'] = array('texto' => $cedula, 'ancho' => 70, 'x' => 135, 'y' => 83, 'estilo' => '', 'alinea' => 'L');
		//pertenece
		$pagina1['cuenta'] = array('texto' => $cuenta, 'ancho' => 100, 'x' => 15, 'y' => 93, 'estilo' => '', 'alinea' => 'L');

		$elemento = array($pagina1);
		$img = 'interbancario2.jpg';
		$this -> generar_formato($elemento, 'Interbancaria_' . $cedula, 12, $img);
	}

	public function f_cuotas($factura, $forma = 'P') {
		$pagina1 = array();
		$query = "SELECT * FROM t_personas JOIN t_clientes_creditos ON t_personas.documento_id = t_clientes_creditos.documento_id WHERE t_clientes_creditos.numero_factura='$factura'";
		$Consulta = $this -> db -> query($query);
		$aguinaldo = 0;
		$vaca = 0;
		$monto_factura = 0;
		if ($Consulta -> num_rows() > 0) {
			foreach ($Consulta->result() as $row) {
				$monto_factura += $row -> monto_total;

				if ($row -> forma_contrato == '0') {
					$nombre_c = $row -> primer_apellido . ' ' . $row -> segundo_apellido . ' ' . $row -> primer_nombre . ' ' . $row -> segundo_nombre;
					//yo
					$pagina1['yo'] = array('texto' => $nombre_c, 'ancho' => 112, 'x' => 44, 'y' => 11, 'estilo' => '', 'alinea' => 'L');
					//cedula
					$pagina1['cedula'] = array('texto' => $row -> documento_id, 'ancho' => 37, 'x' => 165, 'y' => 11, 'estilo' => '');
					//pertenece
					$pagina1['nomina'] = array('texto' => $row -> nomina_procedencia, 'ancho' => 110, 'x' => 23, 'y' => 20, 'estilo' => '');
					//banco
					$pagina1['banco'] = array('texto' => $row -> cobrado_en, 'ancho' => 56, 'x' => 145, 'y' => 20, 'estilo' => '');
					//monto_t
					$pagina1['monto_tu'] = array('texto' => number_format($row -> monto_total, 2) . ' BS.', 'ancho' => 48, 'x' => 45, 'y' => 37, 'estilo' => 'B');
					//contrato
					$pagina1['contrato'] = array('texto' => $row -> contrato_id, 'ancho' => 48, 'x' => 45, 'y' => 45, 'estilo' => 'B');
					//n_cuotas
					$pagina1['cuotas'] = array('texto' => $row -> numero_cuotas, 'ancho' => 48, 'x' => 45, 'y' => 54, 'estilo' => 'B');
					//monto_c
					$pagina1['monto_c'] = array('texto' => number_format($row -> monto_cuota, 2) . ' BS.', 'ancho' => 48, 'x' => 45, 'y' => 62, 'estilo' => 'B');

				}
				if ($row -> forma_contrato == '1') {
					//cuota especial A1
					if ($aguinaldo == 0) {
						$y = 92;
					} else {
						$y = 109;
					}

					if ($row -> fecha_inicio_cobro != '') {
						$desde = explode('-', $row -> fecha_inicio_cobro);
						$mes_L = $this -> mes_letras($desde[1]);
						$pagina1['mes_a' . $aguinaldo] = array('texto' => $mes_L, 'ancho' => 18, 'x' => 56, 'y' => $y, 'estilo' => 'B');
						$pagina1['ano_a' . $aguinaldo] = array('texto' => $desde[0], 'ancho' => 17, 'x' => 85, 'y' => $y, 'estilo' => 'B');
					}
					//monto cuota especial a1
					$pagina1['monto_a' . $aguinaldo] = array('texto' => number_format($row -> monto_total, 2) . ' BS.', 'ancho' => 24, 'x' => 115, 'y' => $y, 'estilo' => 'B');
					//contrato
					$pagina1['contrato' . $aguinaldo] = array('texto' => $row -> contrato_id, 'ancho' => 27, 'x' => 173, 'y' => $y, 'estilo' => 'B');
					$aguinaldo++;
				}
				if ($row -> forma_contrato == '2') {
					//cuota especial v1
					if ($vaca == 0) {
						$y = 83;
					} else {
						$y = 101;
					}

					if ($row -> fecha_inicio_cobro != '') {
						$desde = explode('-', $row -> fecha_inicio_cobro);
						$mes_L = $this -> mes_letras($desde[1]);
						$pagina1['mes_v' . $vaca] = array('texto' => $mes_L, 'ancho' => 18, 'x' => 56, 'y' => $y, 'estilo' => 'B');
						$pagina1['ano_v' . $vaca] = array('texto' => $desde[0], 'ancho' => 17, 'x' => 85, 'y' => $y, 'estilo' => 'B');
					}
					//monto cuota especial a1
					$pagina1['monto_v' . $vaca] = array('texto' => number_format($row -> monto_total, 2) . ' BS.', 'ancho' => 24, 'x' => 115, 'y' => $y, 'estilo' => 'B');
					//contrato
					$pagina1['contratoV' . $vaca] = array('texto' => $row -> contrato_id, 'ancho' => 27, 'x' => 173, 'y' => $y, 'estilo' => 'B');
					$vaca++;
				}

			}
			//monto_t
			$pagina1['monto_t'] = array('texto' => number_format($monto_factura, 2) . ' BS.', 'ancho' => 45, 'x' => 158, 'y' => 40, 'estilo' => 'B', 'tam' => 14);
		} else {
			echo 'No existe factrua ' . $factura;
			return 0;
		}
		$elemento = array($pagina1);
		$this -> generar_formato($elemento, 'Cuotas');
	}

	public function f_autoriza_gob($factura, $recursos, $forma = 'P') {
		$pagina1 = array();
		$query = "SELECT * FROM t_personas JOIN t_clientes_creditos ON t_personas.documento_id = t_clientes_creditos.documento_id WHERE t_clientes_creditos.numero_factura='$factura'";
		$Consulta = $this -> db -> query($query);
		$aguinaldo = 0;
		$vaca = 0;
		$monto_factura = 0;
		$img = '';
		$lugar = '';
		$absisas = NULL;
		$ordenadas = NULL;
		if ($forma != 'P') {
			$absisas[0] = 172;
			//factura
			$absisas[1] = 18;
			//lin_sup
			$absisas[2] = 18;
			//yo
			$absisas[3] = 45;
			//cedula
			$absisas[4] = 116;
			//com_de
			$absisas[5] = 36;
			//pertenece
			$absisas[6] = 120;
			//r_humanos
			$absisas[7] = 85;
			//periodo1
			$absisas[8] = 140;
			//periodo2
			$absisas[9] = 162;
			//periodo3
			$absisas[10] = 42;
			//desde
			$absisas[11] = 114;
			//hasta
			$absisas[12] = 34;
			//monto_1
			$absisas[13] = 158;
			//monto_2
			$absisas[14] = 62;
			//ano_agui
			$absisas[15] = 120;
			//monto_agui
			$absisas[16] = 13;
			//montivo >40
			$absisas[17] = 115;
			//montivo <40
			$absisas[18] = 46;
			//cantidad_t
			$absisas[19] = 18;
			//cantidad_t2
			$absisas[20] = 16;
			//lugar
			$absisas[21] = 53;
			//dia
			$absisas[22] = 117;
			//mes
			$absisas[23] = 175;
			//ano

			$ordenadas[0] = 50;
			//factura
			$ordenadas[1] = 66;
			//lin_sup
			$ordenadas[2] = 82;
			//yo
			$ordenadas[3] = 89;
			//cedula, com_de
			$ordenadas[4] = 97;
			//pertenece
			$ordenadas[5] = 103;
			//r_humanos
			$ordenadas[6] = 111;
			//periodo1,2,3
			$ordenadas[7] = 118;
			//desde, hasta
			$ordenadas[8] = 125;
			//monto_1, monto_2
			$ordenadas[9] = 139;
			//PRIMERA AGUINALDO
			$ordenadas[10] = 153;
			//SEGUNDA AGUINADO
			$ordenadas[11] = 132;
			//primera de vacacones
			$ordenadas[12] = 146;
			//segunda de vacacones
			$ordenadas[13] = 166;
			//motivo >40
			$ordenadas[14] = 160;
			//motivo < 40
			$ordenadas[15] = 182;
			//cantidad_t
			$ordenadas[16] = 189;
			//cantidad_t2
			$ordenadas[17] = 219;
			//lugar,dia,mes,ano
			$img = 'gobernacion.jpg';
		} else {
			$absisas[0] = 172;
			//factura
			$absisas[1] = 18;
			//lin_sup
			$absisas[2] = 18;
			//yo
			$absisas[3] = 45;
			//cedula
			$absisas[4] = 116;
			//com_de
			$absisas[5] = 36;
			//pertenece
			$absisas[6] = 120;
			//r_humanos
			$absisas[7] = 85;
			//periodo1
			$absisas[8] = 140;
			//periodo2
			$absisas[9] = 162;
			//periodo3
			$absisas[10] = 42;
			//desde
			$absisas[11] = 114;
			//hasta
			$absisas[12] = 34;
			//monto_1
			$absisas[13] = 158;
			//monto_2
			$absisas[14] = 62;
			//ano_agui
			$absisas[15] = 120;
			//monto_agui
			$absisas[16] = 13;
			//montivo >40
			$absisas[17] = 115;
			//montivo <40
			$absisas[18] = 46;
			//cantidad_t
			$absisas[19] = 18;
			//cantidad_t2
			$absisas[20] = 16;
			//lugar
			$absisas[21] = 53;
			//dia
			$absisas[22] = 117;
			//mes
			$absisas[23] = 175;
			//ano

			$ordenadas[0] = 50;
			//factura
			$ordenadas[1] = 71;
			//lin_sup
			$ordenadas[2] = 87;
			//yo
			$ordenadas[3] = 94;
			//cedula, com_de
			$ordenadas[4] = 101;
			//pertenece
			$ordenadas[5] = 107;
			//r_humanos
			$ordenadas[6] = 114;
			//periodo1,2,3
			$ordenadas[7] = 121;
			//desde, hasta
			$ordenadas[8] = 128;
			//monto_1, monto_2
			$ordenadas[9] = 141;
			//PRIMERA AGUINALDO
			$ordenadas[10] = 155;
			//SEGUNDA AGUINADO
			$ordenadas[11] = 134;
			//primera de vacacones
			$ordenadas[12] = 148;
			//segunda de vacacones
			$ordenadas[13] = 169;
			//motivo >40
			$ordenadas[14] = 161;
			//motivo < 40
			$ordenadas[15] = 182;
			//cantidad_t
			$ordenadas[16] = 189;
			//cantidad_t2
			$ordenadas[17] = 217;
			//lugar,dia,mes,ano
		}

		if ($Consulta -> num_rows() > 0) {
			$pagina1['factura'] = array('texto' => $factura, 'ancho' => 18, 'x' => $absisas[0], 'y' => $ordenadas[0], 'estilo' => 'B');
			$motivo = 'FIANCIAMIENTO DE LINEA BLANCA';
			foreach ($Consulta->result() as $row) {
				$monto_factura += $row -> monto_total;
				if ($row -> forma_contrato == '0') {
					$concep = explode("|", $row -> observaciones);
					$motivo = $concep[0];
					$nombre_c = $row -> primer_apellido . ' ' . $row -> segundo_apellido . ' ' . $row -> primer_nombre . ' ' . $row -> segundo_nombre;
					$pagina1['lin_sup'] = array('texto' => $row -> nomina_procedencia, 'ancho' => 180, 'x' => $absisas[1], 'y' => $ordenadas[1], 'estilo' => '');
					$pagina1['yo'] = array('texto' => $nombre_c, 'ancho' => 165, 'x' => $absisas[2], 'y' => $ordenadas[2], 'estilo' => '');
					$pagina1['cedula'] = array('texto' => $row -> documento_id, 'ancho' => 43, 'x' => $absisas[3], 'y' => $ordenadas[3], 'estilo' => '');
					$pagina1['com_de'] = array('texto' => $row -> cargo_actual, 'ancho' => 84, 'x' => $absisas[4], 'y' => $ordenadas[3], 'estilo' => '');
					$pagina1['pertenece'] = array('texto' => $row -> nomina_procedencia, 'ancho' => 90, 'x' => $absisas[5], 'y' => $ordenadas[4], 'estilo' => '');
					$pagina1['r_humanos'] = array('texto' => $recursos, 'ancho' => 80, 'x' => $absisas[6], 'y' => $ordenadas[5], 'estilo' => '');
					$pagina1['periodo1'] = array('texto' => strtoupper($this -> ValorEnLetras($row -> numero_cuotas, '')), 'ancho' => 55, 'x' => $absisas[7], 'y' => $ordenadas[6], 'estilo' => '');
					$pagina1['periodo2'] = array('texto' => $row -> numero_cuotas, 'ancho' => 17, 'x' => $absisas[8], 'y' => $ordenadas[6], 'estilo' => '');
					$pagina1['periodo3'] = array('texto' => 'MESES', 'ancho' => 35, 'x' => $absisas[9], 'y' => $ordenadas[6], 'estilo' => '');
					if ($row -> fecha_inicio_cobro != '') {
						$desde = explode('-', $row -> fecha_inicio_cobro);
						$pagina1['desde'] = array('texto' => $desde[2] . '-' . $desde[1] . '-' . $desde[0], 'ancho' => 60, 'x' => $absisas[10], 'y' => $ordenadas[7], 'estilo' => '');
					}
					$pagina1['hasta'] = array('texto' => '', 'ancho' => 85, 'x' => $absisas[11], 'y' => $ordenadas[7], 'estilo' => '');
					$pagina1['monto_l'] = array('texto' => strtoupper($this -> ValorEnLetras($row -> monto_cuota, '')), 'ancho' => 104, 'x' => $absisas[12], 'y' => $ordenadas[8], 'estilo' => '');
					$pagina1['monto_2'] = array('texto' => number_format($row -> monto_cuota, 2), 'ancho' => 35, 'x' => $absisas[13], 'y' => $ordenadas[8], 'estilo' => '');
				}
				if ($row -> forma_contrato == '1') {//cuota especial A1
					if ($aguinaldo == 0) {
						$y = $ordenadas[9];
					} else {
						$y = $ordenadas[10];
					}
					if ($row -> fecha_inicio_cobro != '') {
						$desde = explode('-', $row -> fecha_inicio_cobro);
						$pagina1['ano_a' . $aguinaldo] = array('texto' => $desde[0], 'ancho' => 48, 'x' => $absisas[14], 'y' => $y, 'estilo' => 'B');
					}
					$pagina1['monto_a' . $aguinaldo] = array('texto' => number_format($row -> monto_total, 2), 'ancho' => 74, 'x' => $absisas[15], 'y' => $y, 'estilo' => 'B');
					$aguinaldo++;
				}
				if ($row -> forma_contrato == '2') {//cuota especial v1
					if ($vaca == 0) {
						$y = $ordenadas[11];
					} else {
						$y = $ordenadas[12];
					}
					if ($row -> fecha_inicio_cobro != '') {
						$desde = explode('-', $row -> fecha_inicio_cobro);
						$pagina1['ano_v' . $vaca] = array('texto' => $desde[0], 'ancho' => 48, 'x' => $absisas[14], 'y' => $y, 'estilo' => 'B');
					}
					$pagina1['monto_v' . $vaca] = array('texto' => number_format($row -> monto_total, 2), 'ancho' => 74, 'x' => $absisas[15], 'y' => $y, 'estilo' => 'B');
					$vaca++;
				}
				if ($row -> codigo_n != '') {
					$lugar = $row -> codigo_n;
				}

			}

			if (strlen($motivo) > 40) {//motivo largo
				$pagina1['motivo_1'] = array('texto' => $motivo, 'ancho' => 186, 'x' => $absisas[16], 'y' => $ordenadas[13], 'estilo' => '', 'multi' => 2, 'alto' => 6.5);
			} else {
				$pagina1['motivo_1'] = array('texto' => $motivo, 'ancho' => 83, 'x' => $absisas[17], 'y' => $ordenadas[14], 'estilo' => '');
			}
			$pagina1['cantidad_t'] = array('texto' => strtoupper($this -> ValorEnLetras($monto_factura, '')), 'ancho' => 140, 'x' => $absisas[18], 'y' => $ordenadas[15], 'estilo' => '');
			$pagina1['cantidad_t2'] = array('texto' => number_format($monto_factura, 2), 'ancho' => 45, 'x' => $absisas[19], 'y' => $ordenadas[16], 'estilo' => '');
			$pagina1['lugar'] = array('texto' => $lugar, 'ancho' => 28, 'x' => $absisas[20], 'y' => $ordenadas[17], 'estilo' => 'B');
			$fecha_actual = date('d-m-Y');
			$fecha_actual = explode('-', $fecha_actual);
			$mes_L = $this -> mes_letras($fecha_actual[1]);
			$pagina1['dia'] = array('texto' => strtoupper($this -> ValorEnLetras($fecha_actual[0], '')), 'ancho' => 40, 'x' => $absisas[21], 'y' => $ordenadas[17], 'estilo' => 'B');
			$pagina1['mes'] = array('texto' => $mes_L, 'ancho' => 52, 'x' => $absisas[22], 'y' => $ordenadas[17], 'estilo' => 'B');
			$pagina1['ano'] = array('texto' => $fecha_actual[2], 'ancho' => 25, 'x' => $absisas[23], 'y' => $ordenadas[17], 'estilo' => 'B');
		} else {
			echo 'No existe factrua ' . $factura;
			return 0;
		}
		$elemento = array($pagina1);
		$this -> generar_formato($elemento, 'Gobernacion', 10, $img);
	}

	public function f_autoriza_universal($contrato, $e = 'C', $forma = 'P') {
		$pagina1 = array();
		$pagina2 = array();
		$Consulta = $this -> db -> query("SELECT * FROM t_personas JOIN t_clientes_creditos ON t_personas.documento_id = t_clientes_creditos.documento_id WHERE t_clientes_creditos.contrato_id='$contrato'");
		$img = '';
		$img2 = '';
		$y = 0;
		if ($Consulta -> num_rows() > 0) {
			foreach ($Consulta->result() as $row) {
				$nombre_c = $row -> primer_apellido . ' ' . $row -> segundo_apellido . ' ' . $row -> primer_nombre . ' ' . $row -> segundo_nombre;
				if ($forma != 'P') {
					if ($e == 'C') {
						$img = 'universalcf.jpg';
						$img2 = 'universalct.jpg';
						$y1 = 0;
						$y2 = 0;
						$y3 = 0;
						$x1 = 0;
						$x2 = 0;
					} else {
						$y1 = 1;
						$y2 = 2;
						$y3 = 3;
						$x1 = 1;
						$x2 = 2;
						$img = 'universalgf.jpg';
						$img2 = 'universalgt.jpg';
					}
					//nomina
					$pagina1['contrato'] = array('texto' => $contrato, 'ancho' => 30, 'x' => 170, 'y' => 63, 'estilo' => 'B', 'tam' => 12);
					//nomina
					$pagina1['nomina'] = array('texto' => $row -> nomina_procedencia, 'ancho' => 183, 'x' => 13, 'y' => 81, 'estilo' => '');
					//linaje
					$pagina1['linaje'] = array('texto' => $row -> cobrado_en, 'ancho' => 64, 'x' => 26, 'y' => 102, 'estilo' => '');
					//nombre
					$pagina1['nombre'] = array('texto' => $nombre_c, 'ancho' => 148, 'x' => 17, 'y' => 111, 'estilo' => '');
					//nombre
					$pagina1['cedula'] = array('texto' => $row -> documento_id, 'ancho' => 60, 'x' => 66, 'y' => 118 + $y1, 'estilo' => '');
					//nacionalidad
					//$nacion = 'E-';
					if ($row -> nacionalidad == 'E-') {
						//if ($nacion== 'E-') {
						$x = 57;
					} else {
						$x = 47;
					}
					$pagina1['nacionalidad'] = array('texto' => 'X', 'ancho' => 5, 'x' => $x + $x1, 'y' => 118 + $y1, 'estilo' => '');
					//sector
					$pagina1['sector'] = array('texto' => $row -> sector, 'ancho' => 25, 'x' => 174, 'y' => 118 + $y1, 'estilo' => '');
					//calle
					$pagina1['calle'] = array('texto' => $row -> calle, 'ancho' => 63, 'x' => 20, 'y' => 125 + $y2, 'estilo' => '');
					//avenida
					$pagina1['avenida'] = array('texto' => $row -> avenida, 'ancho' => 69, 'x' => 89, 'y' => 125 + $y2, 'estilo' => '');
					//edif.
					$pagina1['edificio'] = array('texto' => $row -> parroquia, 'ancho' => 33, 'x' => 165, 'y' => 125 + $y2, 'estilo' => '');
					//direccion
					if (strlen($row -> direccion) < 10) {
						$y = 133 + $y1;
						if ($row -> parroquia != '') {
							$x = 22 + $x1;
							$a = 13;
						} else {
							$x = 95;
							$a = 17;
						}
					} else {
						$y = 141 + $y1;
						$a = 97;
						$x = 100;
					}

					$pagina1['direccion'] = array('texto' => $row -> direccion, 'ancho' => $a, 'x' => $x, 'y' => $y, 'estilo' => '');
					//urb.
					$pagina1['urb'] = array('texto' => $row -> urbanizacion, 'ancho' => 39, 'x' => 41 + $x1, 'y' => 133 + $y1, 'estilo' => '');
					//estado
					$pagina1['estado'] = array('texto' => $row -> ubicacion, 'ancho' => 27, 'x' => 123 + $x1, 'y' => 133 + $y1, 'estilo' => '');
					//municipio
					$pagina1['municipio'] = array('texto' => $row -> municipio, 'ancho' => 35, 'x' => 165 + $x1, 'y' => 133 + $y1, 'estilo' => '');
					//zona
					$pagina1['zona'] = array('texto' => $row -> gaceta, 'ancho' => 28, 'x' => 29, 'y' => 141 + $y1, 'estilo' => '');
					//empleado de
					$pagina1['empleado'] = array('texto' => $row -> nomina_procedencia, 'ancho' => 143, 'x' => 53, 'y' => 149 + $y1, 'estilo' => '');
					//telf.
					$pagina1['telf'] = array('texto' => $row -> telefono, 'ancho' => 63, 'x' => 133, 'y' => 157, 'estilo' => '');
					//cel
					$pagina1['cel'] = array('texto' => $row -> celular, 'ancho' => 45, 'x' => 30, 'y' => 164, 'estilo' => '');
					//email
					$pagina1['email'] = array('texto' => $row -> correo, 'ancho' => 70, 'x' => 86, 'y' => 164, 'estilo' => '');
					//pin
					$pagina1['pin'] = array('texto' => $row -> pin, 'ancho' => 33, 'x' => 165, 'y' => 164, 'estilo' => '');
					//persona
					$pagina1['per'] = array('texto' => $nombre_c, 'ancho' => 128, 'x' => 48, 'y' => 172 + $y1, 'estilo' => '');
					//banco2
					$pagina1['ban2'] = array('texto' => $row -> cobrado_en, 'ancho' => 55, 'x' => 143, 'y' => 179 + $y2, 'estilo' => '');
					//tipo cuenta
					$tipo_cuenta = $row -> tipo_cuenta_1;
					//$tipo_cuenta = 'CORRIENTE';
					if ($tipo_cuenta == "AHORRO" OR $tipo_cuenta == "AHORRO NOMINA") {
						$x = 108;
					}
					if ($tipo_cuenta == "CORRIENTE" OR $tipo_cuenta == "CORRIENTE NOMINA") {
						$x = 148;
					}
					$pagina1['t_cuenta'] = array('texto' => 'X', 'ancho' => 5, 'x' => $x, 'y' => 204 + $y2, 'estilo' => 'B');
					//numero cuenta
					if($row -> cobrado_en == $row -> cuenta_1)$cuenta = $row -> cuenta_1;
					else $cuenta = $row -> cuenta_2;
					$inicio = 91 + $x1;
					for ($i = 0; $i < 20; $i++) {
						if (isset($cuenta[$i])) {
							$pagina1['c1_' . $i] = array('texto' => $cuenta[$i], 'ancho' => 5.5, 'x' => $inicio, 'y' => 212 + $y2, 'estilo' => 'B');
						} else {
							break;
						}
						$inicio += 5.6;
					}
					//Modalidad
					$periocidad = 2;
					if ($row -> forma_contrato != '1' && $row -> forma_contrato != '2') {
						switch ($row -> periocidad) {
							//switch ($periocidad) {
							case '0' :
								$x = 91 + $x1;
								break;
							case '4' :
								$x = 148 + $x1;
								break;
							case '2' :
								$x = 119 + $x1;
								break;
							case '3' :
								$x = 119 + $x1;
								break;

							default :
								break;
						}
					} else {
						$x = 177 + $x1;
					}
					$pagina1['periocidad'] = array('texto' => 'X', 'ancho' => 5, 'x' => $x, 'y' => 219 + $y3, 'estilo' => 'B');
					//inicio descuento
					if ($row -> fecha_inicio_cobro != '') {
						$desde = explode('-', $row -> fecha_inicio_cobro);
						$pagina1['fecha'] = array('texto' => $desde[2] . '-' . $desde[1] . '-' . $desde[0], 'ancho' => 110, 'x' => 88, 'y' => 247 + $y2, 'estilo' => 'B', 'tam' => 12);
					}
					//n.cuotas
					$pagina1['cuotas'] = array('texto' => $row -> numero_cuotas, 'ancho' => 110, 'x' => 88, 'y' => 233 + $y2, 'estilo' => 'B', 'tam' => 12);
					//m.cuotas
					$pagina1['m_cuotas'] = array('texto' => number_format($row -> monto_cuota, 2) . ' BS.', 'ancho' => 110, 'x' => 88, 'y' => 226 + $y2, 'estilo' => 'B', 'tam' => 12);
					//m.total
					$pagina1['total'] = array('texto' => number_format($row -> monto_total, 2) . ' BS.', 'ancho' => 110, 'x' => 88, 'y' => 240 + $y2, 'estilo' => 'B', 'tam' => 12);
					if ($e == 'G') {
						$y = 229;
					} else {
						$y = 232;
					}
					//lugar
					$pagina2['lugar'] = array('texto' => $row -> codigo_n, 'ancho' => 33, 'x' => 16, 'y' => $y, 'estilo' => 'B');

					//a los
					$fecha_actual = date('d-m-Y');
					$fecha_actual = explode('-', $fecha_actual);
					$mes_L = $this -> mes_letras($fecha_actual[1]);

					//dia
					$pagina2['dia'] = array('texto' => $fecha_actual[0], 'ancho' => 17, 'x' => 60, 'y' => $y, 'estilo' => 'B');
					//mes
					$pagina2['mes'] = array('texto' => $mes_L, 'ancho' => 50, 'x' => 99, 'y' => $y, 'estilo' => 'B');
					//en
					$pagina2['ano'] = array('texto' => $fecha_actual[2], 'ancho' => 12, 'x' => 157, 'y' => $y, 'estilo' => 'B');

				} else {
					//nomina
					$pagina1['nomina'] = array('texto' => $row -> nomina_procedencia, 'ancho' => 183, 'x' => 13, 'y' => 83, 'estilo' => '');
					//linaje
					$pagina1['linaje'] = array('texto' => $row -> cobrado_en, 'ancho' => 64, 'x' => 26, 'y' => 103, 'estilo' => '');
					//nombre
					$pagina1['nombre'] = array('texto' => $nombre_c, 'ancho' => 148, 'x' => 17, 'y' => 112, 'estilo' => '');
					//nombre
					$pagina1['cedula'] = array('texto' => $row -> documento_id, 'ancho' => 60, 'x' => 66, 'y' => 119, 'estilo' => '');
					//nacionalidad
					if ($row -> nacionalidad == 'E-') {
						$x = 58;
					} else {
						$x = 48;
					}
					$pagina1['nacionalidad'] = array('texto' => 'X', 'ancho' => 5, 'x' => $x, 'y' => 119, 'estilo' => '');
					//sector
					$pagina1['sector'] = array('texto' => $row -> sector, 'ancho' => 25, 'x' => 172, 'y' => 119, 'estilo' => '');
					//calle
					$pagina1['calle'] = array('texto' => $row -> calle, 'ancho' => 63, 'x' => 20, 'y' => 127, 'estilo' => '');
					//avenida
					$pagina1['avenida'] = array('texto' => $row -> avenida, 'ancho' => 69, 'x' => 89, 'y' => 127, 'estilo' => '');
					//edif.
					$pagina1['edificio'] = array('texto' => $row -> parroquia, 'ancho' => 33, 'x' => 165, 'y' => 127, 'estilo' => '');
					//direccion
					if (strlen($row -> direccion) < 10) {
						$y = 135;
						if ($row -> parroquia != '') {
							$x = 21;
							$a = 13;
						} else {
							$x = 94;
							$a = 17;
						}
					} else {
						$y = 142;
						$a = 97;
						$x = 100;
					}

					$pagina1['direccion'] = array('texto' => $row -> direccion, 'ancho' => $a, 'x' => $x, 'y' => $y, 'estilo' => '');
					//urb.
					$pagina1['urb'] = array('texto' => $row -> urbanizacion, 'ancho' => 39, 'x' => 40, 'y' => 135, 'estilo' => '');
					//estado
					$pagina1['estado'] = array('texto' => $row -> ubicacion, 'ancho' => 27, 'x' => 122, 'y' => 135, 'estilo' => '');
					//municipio
					$pagina1['municipio'] = array('texto' => $row -> municipio, 'ancho' => 35, 'x' => 164, 'y' => 135, 'estilo' => '');
					//zona
					$pagina1['zona'] = array('texto' => $row -> gaceta, 'ancho' => 28, 'x' => 29, 'y' => 142, 'estilo' => '');
					//empleado de
					$pagina1['empleado'] = array('texto' => $row -> nomina_procedencia, 'ancho' => 143, 'x' => 53, 'y' => 150, 'estilo' => '');
					//telf.
					$pagina1['telf'] = array('texto' => $row -> telefono, 'ancho' => 63, 'x' => 133, 'y' => 157, 'estilo' => '');
					//cel
					$pagina1['cel'] = array('texto' => $row -> celular, 'ancho' => 45, 'x' => 30, 'y' => 164, 'estilo' => '');
					//email
					$pagina1['email'] = array('texto' => $row -> correo, 'ancho' => 70, 'x' => 86, 'y' => 164, 'estilo' => '');
					//pin
					$pagina1['pin'] = array('texto' => $row -> pin, 'ancho' => 33, 'x' => 165, 'y' => 164, 'estilo' => '');
					//persona
					$pagina1['per'] = array('texto' => $nombre_c, 'ancho' => 128, 'x' => 48, 'y' => 172, 'estilo' => '');
					//banco2
					$pagina1['ban2'] = array('texto' => $row -> cobrado_en, 'ancho' => 55, 'x' => 143, 'y' => 179, 'estilo' => '');
					//tipo cuenta
					$tipo_cuenta = $row -> tipo_cuenta_1;
					if ($tipo_cuenta == "AHORRO" OR $tipo_cuenta == "AHORRO NOMINA") {
						$x = 106;
					}
					if ($tipo_cuenta == "CORRIENTE" OR $tipo_cuenta == "CORRIENTE NOMINA") {
						$x = 146;
					}
					$pagina1['t_cuenta'] = array('texto' => 'X', 'ancho' => 5, 'x' => $x, 'y' => 203, 'estilo' => 'B');
					//numero cuenta
					$cuenta = $row -> cuenta_1;
					$inicio = 90;
					for ($i = 0; $i < 20; $i++) {
						if (isset($cuenta[$i])) {
							$pagina1['c1_' . $i] = array('texto' => $cuenta[$i], 'ancho' => 5.5, 'x' => $inicio, 'y' => 212, 'estilo' => 'B');
						} else {
							break;
						}
						$inicio += 5.5;
					}
					//Modalidad
					if ($row -> forma_contrato != '1' && $row -> forma_contrato != '2') {
						switch ($row -> periocidad) {
							case '0' :
								$x = 91;
								break;
							case '4' :
								$x = 147;
								break;
							case '2' :
								$x = 118;
								break;
							case '3' :
								$x = 118;
								break;

							default :
								break;
						}
					} else {
						$x = 180;
					}
					$pagina1['periocidad'] = array('texto' => 'X', 'ancho' => 5, 'x' => $x, 'y' => 218, 'estilo' => 'B');
					//inicio descuento
					if ($row -> fecha_inicio_cobro != '') {
						$desde = explode('-', $row -> fecha_inicio_cobro);
						$pagina1['fecha'] = array('texto' => $desde[2] . '-' . $desde[1] . '-' . $desde[0], 'ancho' => 110, 'x' => 88, 'y' => 247, 'estilo' => 'B');
					}
					//n.cuotas
					$pagina1['cuotas'] = array('texto' => $row -> numero_cuotas, 'ancho' => 110, 'x' => 88, 'y' => 233, 'estilo' => 'B');
					//m.cuotas
					$pagina1['m_cuotas'] = array('texto' => number_format($row -> monto_cuota, 2) . ' BS.', 'ancho' => 110, 'x' => 88, 'y' => 226, 'estilo' => 'B');
					//m.total
					$pagina1['total'] = array('texto' => number_format($row -> monto_total, 2) . ' BS.', 'ancho' => 110, 'x' => 88, 'y' => 240, 'estilo' => 'B');
					if ($e == 'G') {
						$y = 227;
					} else {
						$y = 229;
					}
					//lugar
					$pagina2['lugar'] = array('texto' => $row -> codigo_n, 'ancho' => 33, 'x' => 16, 'y' => $y, 'estilo' => 'B');

					//a los
					$fecha_actual = date('d-m-Y');
					$fecha_actual = explode('-', $fecha_actual);
					$mes_L = $this -> mes_letras($fecha_actual[1]);

					//dia
					$pagina2['dia'] = array('texto' => $fecha_actual[0], 'ancho' => 15, 'x' => 60, 'y' => $y, 'estilo' => 'B');
					//mes
					$pagina2['mes'] = array('texto' => $mes_L, 'ancho' => 50, 'x' => 97, 'y' => $y, 'estilo' => 'B');
					//en
					$pagina2['ano'] = array('texto' => $fecha_actual[2], 'ancho' => 12, 'x' => 153, 'y' => $y, 'estilo' => 'B');

				}

			}
		} else {
			echo "NO EXISTE EL CONTRATO";
			return 0;
		}

		$elemento = array($pagina1, $pagina2);
		$this -> generar_formato($elemento, 'universal', 10, $img, $img2);
	}

	public function f_venezuela($contrato, $e = 'C', $forma = 'P') {
		$pagina1 = array();
		$Consulta = $this -> db -> query("SELECT * FROM t_personas JOIN t_clientes_creditos ON t_personas.documento_id = t_clientes_creditos.documento_id WHERE t_clientes_creditos.contrato_id='$contrato'");
		$img = '';
		if ($Consulta -> num_rows() > 0) {
			foreach ($Consulta->result() as $row) {
				$nombre_c = $row -> primer_apellido . ' ' . $row -> segundo_apellido . ' ' . $row -> primer_nombre . ' ' . $row -> segundo_nombre;
				if ($forma != 'P') {
					if ($e == 'C') {
						$img = 'venezuelac.jpg';
						$yt = 70;
						$y1 = 82;
						$y2 = 92;
						$y3 = 100;
						$y4 = 107;
						$y5 = 144;
						$y6 = 152;
					} else {
						$yt = 64;
						$img = 'venezuelag.jpg';
						$y1 = 79;
						$y2 = 90;
						$y3 = 97;
						$y4 = 104;
						$y5 = 142;
						$y6 = 149;
					}
					//cotrato
					$pagina1['contrato'] = array('texto' => $row -> contrato_id, 'ancho' => 40, 'x' => 145, 'y' => $yt, 'estilo' => 'B', 'tam' => 12);
					//lugar
					$pagina1['lugar'] = array('texto' => $row -> codigo_n, 'ancho' => 35, 'x' => 100, 'y' => $y1, 'estilo' => '');

					//a los
					$fecha_actual = date('d-m-Y');
					$fecha_actual = explode('-', $fecha_actual);
					$mes_L = $this -> mes_letras($fecha_actual[1]);

					//dia
					$pagina1['dia'] = array('texto' => $fecha_actual[0], 'ancho' => 15, 'x' => 136, 'y' => $y1, 'estilo' => '');
					//mes
					$pagina1['mes'] = array('texto' => $fecha_actual[1], 'ancho' => 15, 'x' => 155, 'y' => $y1, 'estilo' => '');
					//en
					$pagina1['ano'] = array('texto' => $fecha_actual[2], 'ancho' => 28, 'x' => 174, 'y' => $y1, 'estilo' => '');
					//Nombre
					$pagina1['nombre'] = array('texto' => $nombre_c, 'ancho' => 181, 'x' => 23, 'y' => $y2, 'estilo' => '', 'alinea' => 'L');
					//Cedula
					$pagina1['cedula'] = array('texto' => $row -> nacionalidad . $row -> documento_id, 'ancho' => 65, 'x' => 72, 'y' => $y3, 'estilo' => '');
					//n.cuenta
					$pagina1['n_cuenta'] = array('texto' => $row -> cuenta_1, 'ancho' => 53, 'x' => 15, 'y' => $y4, 'estilo' => '');
					//m.cupotas
					$pagina1['m_cuotas'] = array('texto' => strtoupper($this -> ValorEnLetras($row -> monto_cuota, '')), 'ancho' => 143, 'x' => 59, 'y' => $y5, 'estilo' => '');
					//m.cupotas.2
					$pagina1['m_cuotas2'] = array('texto' => number_format($row -> monto_cuota, 2), 'ancho' => 33, 'x' => 98, 'y' => $y6, 'estilo' => '');

				} else {
					if ($e == 'C') {
						$y1 = 86;
						$y2 = 96;
						$y3 = 104;
						$y4 = 112;
						$y5 = 149;
						$y6 = 157;
					} else {
						$y1 = 81;
						$y2 = 92;
						$y3 = 100;
						$y4 = 107;
						$y5 = 145;
						$y6 = 152;
					}

					//lugar
					$pagina1['lugar'] = array('texto' => $row -> codigo_n, 'ancho' => 35, 'x' => 100, 'y' => $y1, 'estilo' => '');

					//a los
					$fecha_actual = date('d-m-Y');
					$fecha_actual = explode('-', $fecha_actual);
					$mes_L = $this -> mes_letras($fecha_actual[1]);

					//dia
					$pagina1['dia'] = array('texto' => $fecha_actual[0], 'ancho' => 15, 'x' => 136, 'y' => $y1, 'estilo' => '');
					//mes
					$pagina1['mes'] = array('texto' => $fecha_actual[1], 'ancho' => 15, 'x' => 155, 'y' => $y1, 'estilo' => '');
					//en
					$pagina1['ano'] = array('texto' => $fecha_actual[2], 'ancho' => 28, 'x' => 174, 'y' => $y1, 'estilo' => '');
					//Nombre
					$pagina1['nombre'] = array('texto' => $nombre_c, 'ancho' => 181, 'x' => 21, 'y' => $y2, 'estilo' => '', 'alinea' => 'L');
					//Cedula
					$pagina1['cedula'] = array('texto' => $row -> nacionalidad . $row -> documento_id, 'ancho' => 65, 'x' => 72, 'y' => $y3, 'estilo' => '');
					//n.cuenta
					$pagina1['n_cuenta'] = array('texto' => $row -> cuenta_1, 'ancho' => 53, 'x' => 15, 'y' => $y4, 'estilo' => '');
					//m.cupotas
					$pagina1['m_cuotas'] = array('texto' => $this -> ValorEnLetras($row -> monto_cuota, ''), 'ancho' => 143, 'x' => 59, 'y' => $y5, 'estilo' => '');
					//m.cupotas.2
					$pagina1['m_cuotas2'] = array('texto' => number_format($row -> monto_cuota, 2), 'ancho' => 33, 'x' => 98, 'y' => $y6, 'estilo' => '');

				}

			}
		} else {
			echo "NO EXISTE CONTRATO";
			return 0;
		}
		$elemento = array($pagina1);
		$this -> generar_formato($elemento, 'Venezuela', 10, $img);
	}

	public function f_autoriza_ula($contrato, $forma = 'P', $concepto = "FINANCIAMIENTO DE LINEA BLANCA") {
		$pagina1 = array();
		$Consulta = $this -> db -> query("SELECT * FROM t_personas JOIN t_clientes_creditos ON t_personas.documento_id = t_clientes_creditos.documento_id WHERE t_clientes_creditos.contrato_id='$contrato'");

		if ($Consulta -> num_rows() > 0) {
			foreach ($Consulta->result() as $row) {
				$nombre_c = $row -> primer_apellido . ' ' . $row -> segundo_apellido . ' ' . $row -> primer_nombre . ' ' . $row -> segundo_nombre;
				if ($forma != 'P') {
					$img = 'ula.jpg';
					//Cedula
					$pagina1['cedula'] = array('texto' => $row -> documento_id, 'ancho' => 68, 'x' => 42, 'y' => 74, 'estilo' => '');
					//Nombre
					$pagina1['nombre'] = array('texto' => $nombre_c, 'ancho' => 145, 'x' => 23, 'y' => 64, 'estilo' => '');
					//nomina
					$pagina1['nomina'] = array('texto' => $row -> direccion_trabajo, 'ancho' => 152, 'x' => 45, 'y' => 83, 'estilo' => '');
					//cuotas
					$pagina1['cuotas'] = array('texto' => $row -> numero_cuotas, 'ancho' => 15, 'x' => 52, 'y' => 103, 'estilo' => '');
					//m.cupotas
					$pagina1['m_cuotas'] = array('texto' => strtoupper($this -> ValorEnLetras($row -> monto_cuota, '')), 'ancho' => 181, 'x' => 15, 'y' => 111, 'estilo' => '');
					//m.cupotas.2
					$pagina1['m_cuotas2'] = array('texto' => number_format($row -> monto_cuota, 2), 'ancho' => 28, 'x' => 169, 'y' => 121, 'estilo' => '');
					//concepto
					//$concepto = "FINANCIAMIENTO DE LINEA B";
					$concep = explode("|", $row -> observaciones);
					$pagina1['CONCEPTO'] = array('texto' => $concep[0], 'ancho' => 175, 'x' => 15, 'y' => 139, 'estilo' => '', 'multi' => 2, 'alto' => 9.5);

					$y = 181;

					//a los
					$fecha_actual = date('d-m-Y');
					$fecha_actual = explode('-', $fecha_actual);
					$mes_L = $this -> mes_letras($fecha_actual[1]);

					//dia
					$pagina1['dia'] = array('texto' => $fecha_actual[0], 'ancho' => 15, 'x' => 35, 'y' => $y, 'estilo' => '');
					//mes
					$pagina1['mes'] = array('texto' => $mes_L, 'ancho' => 80, 'x' => 75, 'y' => $y, 'estilo' => '');
					//en
					$pagina1['ano'] = array('texto' => $fecha_actual[2], 'ancho' => 25, 'x' => 171, 'y' => $y, 'estilo' => '');
				} else {
					$img = '';
					//Cedula
					$pagina1['cedula'] = array('texto' => $row -> documento_id, 'ancho' => 68, 'x' => 42, 'y' => 76, 'estilo' => '');
					//Nombre
					$pagina1['nombre'] = array('texto' => $nombre_c, 'ancho' => 145, 'x' => 23, 'y' => 67, 'estilo' => '');
					//nomina
					$pagina1['nomina'] = array('texto' => $row -> nomina_procedencia, 'ancho' => 152, 'x' => 45, 'y' => 85, 'estilo' => '');
					//cuotas
					$pagina1['cuotas'] = array('texto' => $row -> numero_cuotas, 'ancho' => 15, 'x' => 55, 'y' => 103, 'estilo' => '');
					//m.cupotas
					$pagina1['m_cuotas'] = array('texto' => strtoupper($this -> ValorEnLetras($row -> monto_cuota, '')), 'ancho' => 181, 'x' => 15, 'y' => 111, 'estilo' => '');
					//m.cupotas.2
					$pagina1['m_cuotas2'] = array('texto' => number_format($row -> monto_cuota, 2), 'ancho' => 28, 'x' => 166, 'y' => 120, 'estilo' => '');
					//concepto
					//$concepto = "ESTA ES UNA CADENA DE EJEMPLO PARA PROBAR QUE TANTO SE PUEDE ESCRIBIR EN ESTAS LINEAS PARA MANEJAR DE BUENA FORMA EL CONCETO DEL CONTRATO, PROBAR QUE TANTO SE PUEDE ESCRIBIR EN ESTAS LINEAS PARA ";
					$pagina1['CONCEPTO'] = array('texto' => $concepto, 'ancho' => 181, 'x' => 15, 'y' => 136, 'estilo' => '', 'multi' => 2, 'alto' => 9);

					$y = 176;

					//a los
					$fecha_actual = date('d-m-Y');
					$fecha_actual = explode('-', $fecha_actual);
					$mes_L = $this -> mes_letras($fecha_actual[1]);

					//dia
					$pagina1['dia'] = array('texto' => $fecha_actual[0], 'ancho' => 15, 'x' => 38, 'y' => $y, 'estilo' => '');
					//mes
					$pagina1['mes'] = array('texto' => $mes_L, 'ancho' => 80, 'x' => 75, 'y' => $y, 'estilo' => '');
					//en
					$pagina1['ano'] = array('texto' => $fecha_actual[2], 'ancho' => 25, 'x' => 170, 'y' => $y, 'estilo' => '');

				}

			}
		} else {
			echo "NO EXISTE CONTRATO";
			return 0;
		}
		$elemento = array($pagina1);
		$this -> generar_formato($elemento, 'ula', 12, $img);
	}

	public function f_autoriza_credinfo($contrato, $forma = 'P') {
		$pagina1 = array();

		$Consulta = $this -> db -> query("SELECT * FROM t_personas JOIN t_clientes_creditos ON t_personas.documento_id = t_clientes_creditos.documento_id WHERE t_clientes_creditos.contrato_id='$contrato'");

		if ($Consulta -> num_rows() > 0) {

			foreach ($Consulta->result() as $row) {
				$nombre_c = $row -> primer_apellido . ' ' . $row -> segundo_apellido . ' ' . $row -> primer_nombre . ' ' . $row -> segundo_nombre;
				$direccion = "";
				if ($row -> municipio != '') {
					$direccion .= 'MUNICIPIO ' . $row -> municipio . ', ';
				}
				if ($row -> parroquia != '') {
					$direccion .= 'EDIF. ' . $row -> parroquia . ', ';
				}
				if ($row -> sector != '') {
					$direccion .= ' SECTOR ' . $row -> sector . ', ';
				}
				if ($row -> avenida != '') {
					$direccion .= ' AV. ' . $row -> avenida . ', ';
				}
				if ($row -> urbanizacion != '') {
					$direccion .= ' URB. ' . $row -> urbanizacion . ', ';
				}
				if ($row -> calle != '') {
					$direccion .= ' CALLE ' . $row -> calle . ', ';
				}
				if ($row -> direccion != '') {
					if (strlen($row -> direccion > 8))
						$direccion .= ' ' . $row -> direccion . ' ';
					else
						$direccion .= ' N. ' . $row -> direccion . ' ';
				}

				if ($forma != 'P') {
					//contrato
					$pagina1['contrato'] = array('texto' => $contrato, 'ancho' => 50, 'x' => 145, 'y' => 87, 'estilo' => 'B', 'tam' => 14);
					$img = 'credinfo.jpg';
					//Nacionalidad
					$pagina1['nacionalidad'] = array('texto' => $row -> nacionalidad, 'ancho' => 15, 'x' => 15, 'y' => 123, 'estilo' => '');
					//Cedula
					$pagina1['cedula'] = array('texto' => $row -> documento_id, 'ancho' => 75, 'x' => 30, 'y' => 123, 'estilo' => '');
					//Nombre
					$pagina1['nombre'] = array('texto' => $nombre_c, 'ancho' => 92, 'x' => 106, 'y' => 123, 'estilo' => '');

					//Ubicacion trabajo
					$pagina1['ubicacion'] = array('texto' => $row -> direccion_trabajo, 'ancho' => 61, 'x' => 65, 'y' => 140, 'estilo' => '');
					//cargo
					$pagina1['cargo'] = array('texto' => $row -> cargo_actual, 'ancho' => 67, 'x' => 132, 'y' => 140, 'estilo' => '');

					//direccion
					$pagina1['direccion'] = array('texto' => $direccion, 'ancho' => 152, 'x' => 49, 'y' => 148, 'estilo' => '');
					//Ubicacion trabajo
					$pagina1['ubicacion2'] = array('texto' => $row -> direccion_trabajo, 'ancho' => 150, 'x' => 50, 'y' => 160, 'estilo' => '');
					//TELEFONO
					$pagina1['telf'] = array('texto' => $row -> telefono, 'ancho' => 57, 'x' => 143, 'y' => 154, 'estilo' => '');
					if ($row -> fecha_solicitud != '') {
						$desde = explode('-', $row -> fecha_solicitud);
						$pagina1['fecha'] = array('texto' => $desde[2] . '-' . $desde[1] . '-' . $desde[0], 'ancho' => 30, 'x' => 15, 'y' => 200, 'estilo' => 'B');
					}
					//n.cuotas
					$pagina1['cuotas'] = array('texto' => $row -> numero_cuotas, 'ancho' => 30, 'x' => 45, 'y' => 200, 'estilo' => 'B');
					//m.cuotas
					$pagina1['m_cuotas'] = array('texto' => number_format($row -> monto_cuota, 2) . ' BS.', 'ancho' => 30, 'x' => 75, 'y' => 200, 'estilo' => 'B');
					//m.total
					$pagina1['total'] = array('texto' => number_format($row -> monto_total, 2) . ' BS.', 'ancho' => 30, 'x' => 105, 'y' => 200, 'estilo' => 'B');

					//lugar y fecha
					$fecha = date('d-m-Y');
					$fecha = explode('-', $fecha);

					$ente = $this -> lugar($row -> codigo_n);
					$lugar = $row -> codigo_n . ' , ' . $fecha[0] . '  DE  ' . $this -> mes_letras($fecha[1]) . '  DEL  ' . $fecha[2];
					$pagina1['lugar_fe'] = array('texto' => $lugar, 'ancho' => 90, 'x' => 33, 'y' => 211, 'estilo' => 'B');
					$pagina1['entidad'] = array('texto' => $ente, 'ancho' => 43, 'x' => 155, 'y' => 211, 'estilo' => 'B');

				} else {
					$img = '';
					//Nacionalidad
					$pagina1['nacionalidad'] = array('texto' => $row -> nacionalidad, 'ancho' => 15, 'x' => 15, 'y' => 125, 'estilo' => '');
					//Cedula
					$pagina1['cedula'] = array('texto' => $row -> documento_id, 'ancho' => 75, 'x' => 30, 'y' => 125, 'estilo' => '');
					//Nombre
					$pagina1['nombre'] = array('texto' => $nombre_c, 'ancho' => 92, 'x' => 106, 'y' => 125, 'estilo' => '');

					//Ubicacion trabajo
					$pagina1['ubicacion'] = array('texto' => $row -> direccion_trabajo, 'ancho' => 61, 'x' => 65, 'y' => 145, 'estilo' => '');
					//cargo
					$pagina1['cargo'] = array('texto' => $row -> cargo_actual, 'ancho' => 67, 'x' => 132, 'y' => 145, 'estilo' => '');

					//direccion
					$pagina1['direccion'] = array('texto' => $direccion, 'ancho' => 152, 'x' => 49, 'y' => 151, 'estilo' => '');
					//Ubicacion trabajo
					$pagina1['ubicacion2'] = array('texto' => $row -> direccion_trabajo, 'ancho' => 150, 'x' => 50, 'y' => 164, 'estilo' => '');
					//TELEFONO
					$pagina1['telf'] = array('texto' => $row -> telefono, 'ancho' => 57, 'x' => 143, 'y' => 158, 'estilo' => '');
					if ($row -> fecha_solicitud != '') {
						$desde = explode('-', $row -> fecha_solicitud);
						$pagina1['fecha'] = array('texto' => $desde[2] . '-' . $desde[1] . '-' . $desde[0], 'ancho' => 30, 'x' => 15, 'y' => 205, 'estilo' => 'B');
					}
					//n.cuotas
					$pagina1['cuotas'] = array('texto' => $row -> numero_cuotas, 'ancho' => 30, 'x' => 45, 'y' => 205, 'estilo' => 'B');
					//m.cuotas
					$pagina1['m_cuotas'] = array('texto' => number_format($row -> monto_cuota, 2) . ' BS.', 'ancho' => 30, 'x' => 75, 'y' => 205, 'estilo' => 'B');
					//m.total
					$pagina1['total'] = array('texto' => number_format($row -> monto_total, 2) . ' BS.', 'ancho' => 30, 'x' => 105, 'y' => 205, 'estilo' => 'B');

					//lugar y fecha
					$fecha = date('d-m-Y');
					$fecha = explode('-', $fecha);

					$ente = $this -> lugar($row -> codigo_n);
					$lugar = $row -> codigo_n . ' , ' . $fecha[0] . '  DE  ' . $this -> mes_letras($fecha[1]) . '  DEL  ' . $fecha[2];
					$pagina1['lugar_fe'] = array('texto' => $lugar, 'ancho' => 90, 'x' => 33, 'y' => 218, 'estilo' => 'B');
					$pagina1['entidad'] = array('texto' => $ente, 'ancho' => 43, 'x' => 155, 'y' => 218, 'estilo' => 'B');

				}

				$elemento = array($pagina1);

				$this -> generar_formato($elemento, 'CREDINFO', 10, $img);

			}
		}
	}

	public function f_sofitasa($contrato, $forma = 'P') {
		$pagina1 = array();
		$pagina2 = array();
		$Consulta = $this -> db -> query("SELECT * FROM t_personas JOIN t_clientes_creditos ON t_personas.documento_id = t_clientes_creditos.documento_id WHERE t_clientes_creditos.contrato_id='$contrato'");
		$img = '';
		$img2 = '';
		if ($Consulta -> num_rows() > 0) {
			foreach ($Consulta->result() as $row) {
				$nombre_c = $row -> primer_apellido . ' ' . $row -> segundo_apellido . ' ' . $row -> primer_nombre . ' ' . $row -> segundo_nombre;

				if ($forma != 'P') {
					$img = 'sofitasa.jpg';
					$img2 = 'sofitasa2.jpg';
					$pagina1['contrato'] = array('texto' => $contrato, 'ancho' => 50, 'x' => 155, 'y' => 67, 'estilo' => 'B', 'tam' => 14);
					//Nombre
					$pagina1['nombre'] = array('texto' => $nombre_c, 'ancho' => 70, 'x' => 35, 'y' => 77, 'estilo' => '');
					//Cedula
					$pagina1['cedula'] = array('texto' => $row -> documento_id, 'ancho' => 45, 'x' => 52, 'y' => 84, 'estilo' => '');
					//estado_civil
					$pagina1['esta_c'] = array('texto' => $row -> estado_civil, 'ancho' => 45, 'x' => 105, 'y' => 84, 'estilo' => '');
					//en
					$pagina1['en'] = array('texto' => $row -> sector, 'ancho' => 35, 'x' => 20, 'y' => 91, 'estilo' => '');
					//calle
					$pagina1['calle'] = array('texto' => $row -> calle, 'ancho' => 27, 'x' => 65, 'y' => 91, 'estilo' => '');
					//Av.
					$pagina1['avenida'] = array('texto' => $row -> avenida, 'ancho' => 45, 'x' => 100, 'y' => 91, 'estilo' => '');
					//Edif.
					$pagina1['edif'] = array('texto' => $row -> parroquia, 'ancho' => 48, 'x' => 153, 'y' => 91, 'estilo' => '');
					//urb
					$pagina1['urb'] = array('texto' => $row -> urbanizacion, 'ancho' => 30, 'x' => 45, 'y' => 97, 'estilo' => '');
					//estado
					$pagina1['estado'] = array('texto' => $row -> ubicacion, 'ancho' => 27, 'x' => 120, 'y' => 97, 'estilo' => '');
					//Municipio
					$pagina1['municipio'] = array('texto' => $row -> municipio, 'ancho' => 38, 'x' => 162, 'y' => 97, 'estilo' => '');
					//zona postal
					$pagina1['zona_postal'] = array('texto' => $row -> gaceta, 'ancho' => 23, 'x' => 32, 'y' => 103, 'estilo' => '');
					//empleado de
					$pagina1['empleado_de'] = array('texto' => $row -> nomina_procedencia, 'ancho' => 130, 'x' => 55, 'y' => 110, 'estilo' => '');
					//////////////////
					$direc = $row -> direccion;
					if (strlen($direc) > 10) {
						//Punto
						$ancho = 100;
						$x = 97;
						$y = 103;
					} else {
						//casa
						$ancho = 18;
						$x = 90;
						$y = 97;
					}
					//punto o casa
					$pagina1['pto_casa'] = array('texto' => $row -> direccion, 'ancho' => $ancho, 'x' => $x, 'y' => $y, 'estilo' => '');
					//ofincina
					$pagina1['oficina'] = array('texto' => 'DE PERSONAL', 'ancho' => 70, 'x' => 25, 'y' => 117, 'estilo' => '');
					//TELEFONO
					$pagina1['telf'] = array('texto' => $row -> telefono, 'ancho' => 65, 'x' => 26, 'y' => 124, 'estilo' => '');
					//Banco
					$pagina1['banco'] = array('texto' => 'SOFITASA', 'ancho' => 53, 'x' => 15, 'y' => 198, 'estilo' => '');
					//tipo cuenta
					//$tipo_cuenta = 'CORRIENTE';
					$tipo_cuenta = $row -> tipo_cuenta_1;
					if ($tipo_cuenta == "AHORRO" OR $tipo_cuenta == "AHORRO NOMINA") {
						$x = 75;
					}
					if ($tipo_cuenta == "CORRIENTE" OR $tipo_cuenta == "CORRIENTE NOMINA") {
						$x = 112;
					}
					$pagina1['t_cuenta'] = array('texto' => 'X', 'ancho' => 6, 'x' => $x, 'y' => 216, 'estilo' => 'B');
					//domicilia
					$pagina1['domicilia'] = array('texto' => 'X', 'ancho' => 6, 'x' => 159, 'y' => 41, 'estilo' => 'B');
					//convenii
					$pagina1['convenio'] = array('texto' => 'X', 'ancho' => 6, 'x' => 81, 'y' => 145, 'estilo' => 'B');
					//numero cuenta
					$cuenta = $row -> cuenta_1;
					$inicio = 77;
					for ($i = 0; $i < 20; $i++) {
						if (isset($cuenta[$i])) {
							$pagina1['c1_' . $i] = array('texto' => $cuenta[$i], 'ancho' => 4, 'x' => $inicio, 'y' => 228, 'estilo' => 'B');
						} else {
							break;
						}
						$inicio += 6.5;
					}

					//numero cuenta2
					$cuenta = $row -> cuenta_2;
					$inicio = 77;
					if ($cuenta != '') {
						for ($i = 0; $i < 20; $i++) {
							if (isset($cuenta[$i])) {
								$pagina1['c2_' . $i] = array('texto' => $cuenta[$i], 'ancho' => 4, 'x' => $inicio, 'y' => 239, 'estilo' => 'B');
							} else {
								break;
							}
							$inicio += 6.5;
						}
					}
					//numero cuotas
					$pagina1['n_cuotas'] = array('texto' => $row -> numero_cuotas, 'ancho' => 15, 'x' => 80, 'y' => 252, 'estilo' => 'B');

					//fin de la primera pagina

					//Monto cuota
					$pagina2['monto_c'] = array('texto' => number_format($row -> monto_cuota, 2) . ' BS.', 'ancho' => 40, 'x' => 55, 'y' => 15, 'estilo' => 'B');
					//Monto Contrato
					$pagina2['monto_t'] = array('texto' => number_format($row -> monto_total, 2) . ' BS.', 'ancho' => 25, 'x' => 72, 'y' => 22, 'estilo' => 'B');
					//Modalidad
					//$periocidad = 4;
					if ($row -> forma_contrato != '1' && $row -> forma_contrato != '2') {
						switch ($row -> periocidad) {
							//switch ($periocidad) {
							case '0' :
								$x = 89;
								break;
							case '4' :
								$x = 160;
								break;
							case '2' :
								$x = 127;
								break;
							case '3' :
								$x = 127;
								break;

							default :
								break;
						}
					} else {
						$x = 185;
					}
					$pagina2['periocidad'] = array('texto' => 'X', 'ancho' => 5, 'x' => $x, 'y' => 29, 'estilo' => 'B');

					//fecha cobro
					if ($row -> fecha_inicio_cobro != '') {
						$fecha_cobro = explode('-', $row -> fecha_inicio_cobro);
						$pagina2['fecha_c_d'] = array('texto' => $fecha_cobro[2], 'ancho' => 15, 'x' => 67, 'y' => 37, 'estilo' => 'B');
						$pagina2['fecha_c_m'] = array('texto' => $fecha_cobro[1], 'ancho' => 15, 'x' => 87, 'y' => 37, 'estilo' => 'B');
						$pagina2['fecha_c_a'] = array('texto' => $fecha_cobro[0], 'ancho' => 15, 'x' => 107, 'y' => 37, 'estilo' => 'B');
					}
					//servicio
					$pagina2['servicio'] = array('texto' => 'PLAN DE FINANCIAMIENTO DE LINEA BLANCA', 'ancho' => 115, 'x' => 83, 'y' => 47, 'estilo' => 'B');

					//en
					$pagina2['lugar'] = array('texto' => $row -> codigo_n, 'ancho' => 33, 'x' => 18, 'y' => 211, 'estilo' => 'B');

					//a los
					$fecha_actual = date('d-m-Y');
					$fecha_actual = explode('-', $fecha_actual);
					$mes_L = $this -> mes_letras($fecha_actual[1]);

					//dia
					$pagina2['dia'] = array('texto' => $fecha_actual[0], 'ancho' => 15, 'x' => 62, 'y' => 211, 'estilo' => 'B');
					//mes
					$pagina2['mes'] = array('texto' => $mes_L, 'ancho' => 50, 'x' => 100, 'y' => 211, 'estilo' => 'B');
					//en
					$pagina2['ano'] = array('texto' => $fecha_actual[2], 'ancho' => 12, 'x' => 160, 'y' => 211, 'estilo' => 'B');

				} else {
					//Nombre
					$pagina1['nombre'] = array('texto' => $nombre_c, 'ancho' => 70, 'x' => 35, 'y' => 81, 'estilo' => '');
					//Cedula
					$pagina1['cedula'] = array('texto' => $row -> documento_id, 'ancho' => 45, 'x' => 52, 'y' => 88, 'estilo' => '');
					//estado_civil
					$pagina1['esta_c'] = array('texto' => $row -> estado_civil, 'ancho' => 45, 'x' => 105, 'y' => 88, 'estilo' => '');
					//en
					$pagina1['en'] = array('texto' => $row -> sector, 'ancho' => 35, 'x' => 20, 'y' => 95, 'estilo' => '');
					//calle
					$pagina1['calle'] = array('texto' => $row -> calle, 'ancho' => 27, 'x' => 65, 'y' => 95, 'estilo' => '');
					//Av.
					$pagina1['avenida'] = array('texto' => $row -> avenida, 'ancho' => 45, 'x' => 100, 'y' => 95, 'estilo' => '');
					//Edif.
					$pagina1['edif'] = array('texto' => $row -> parroquia, 'ancho' => 48, 'x' => 153, 'y' => 95, 'estilo' => '');
					//urb
					$pagina1['urb'] = array('texto' => $row -> urbanizacion, 'ancho' => 30, 'x' => 45, 'y' => 101, 'estilo' => '');
					//estado
					$pagina1['estado'] = array('texto' => $row -> ubicacion, 'ancho' => 27, 'x' => 120, 'y' => 101, 'estilo' => '');
					//Municipio
					$pagina1['municipio'] = array('texto' => $row -> municipio, 'ancho' => 38, 'x' => 162, 'y' => 101, 'estilo' => '');
					//zona postal
					$pagina1['zona_postal'] = array('texto' => $row -> gaceta, 'ancho' => 23, 'x' => 32, 'y' => 107, 'estilo' => '');
					//empleado de
					$pagina1['empleado_de'] = array('texto' => $row -> nomina_procedencia, 'ancho' => 130, 'x' => 55, 'y' => 113, 'estilo' => '');
					//////////////////
					$direc = $row -> direccion;
					if (strlen($direc) > 10) {
						//Punto
						$ancho = 100;
						$x = 97;
						$y = 107;
					} else {
						//casa
						$ancho = 18;
						$x = 90;
						$y = 101;
					}
					//punto o casa
					$pagina1['pto_casa'] = array('texto' => $row -> direccion, 'ancho' => $ancho, 'x' => $x, 'y' => $y, 'estilo' => '');
					//ofincina
					$pagina1['oficina'] = array('texto' => 'DE PERSONAL', 'ancho' => 70, 'x' => 25, 'y' => 120, 'estilo' => '');
					//TELEFONO
					$pagina1['telf'] = array('texto' => $row -> telefono, 'ancho' => 65, 'x' => 26, 'y' => 126, 'estilo' => '');
					//Banco
					$pagina1['banco'] = array('texto' => 'SOFITASA', 'ancho' => 53, 'x' => 15, 'y' => 197, 'estilo' => '');
					//tipo cuenta
					$tipo_cuenta = $row -> tipo_cuenta_1;
					if ($tipo_cuenta == "AHORRO" OR $tipo_cuenta == "AHORRO NOMINA") {
						$x = 75;
					}
					if ($tipo_cuenta == "CORRIENTE" OR $tipo_cuenta == "CORRIENTE NOMINA") {
						$x = 112;
					}
					$pagina1['t_cuenta'] = array('texto' => 'X', 'ancho' => 6, 'x' => $x, 'y' => 214, 'estilo' => 'B');
					//domicilia
					$pagina1['domicilia'] = array('texto' => 'X', 'ancho' => 6, 'x' => 155, 'y' => 48, 'estilo' => 'B');
					//convenii
					$pagina1['convenio'] = array('texto' => 'X', 'ancho' => 6, 'x' => 81, 'y' => 147, 'estilo' => 'B');
					//numero cuenta
					$cuenta = $row -> cuenta_1;
					$inicio = 79;
					for ($i = 0; $i < 20; $i++) {
						if (isset($cuenta[$i])) {
							$pagina1['c1_' . $i] = array('texto' => $cuenta[$i], 'ancho' => 4, 'x' => $inicio, 'y' => 226, 'estilo' => 'B');
						} else {
							break;
						}
						$inicio += 6.1;
					}

					//numero cuenta2
					$cuenta = $row -> cuenta_2;
					$inicio = 79;
					if ($cuenta != '') {
						for ($i = 0; $i < 20; $i++) {
							if (isset($cuenta[$i])) {
								$pagina1['c2_' . $i] = array('texto' => $cuenta[$i], 'ancho' => 4, 'x' => $inicio, 'y' => 237, 'estilo' => 'B');
							} else {
								break;
							}
							$inicio += 6.1;
						}
					}
					//numero cuotas
					$pagina1['n_cuotas'] = array('texto' => $row -> numero_cuotas, 'ancho' => 15, 'x' => 80, 'y' => 248, 'estilo' => 'B');

					//fin de la primera pagina

					//Monto cuota
					$pagina2['monto_c'] = array('texto' => number_format($row -> monto_cuota, 2) . ' BS.', 'ancho' => 40, 'x' => 55, 'y' => 22, 'estilo' => 'B');
					//Monto Contrato
					$pagina2['monto_t'] = array('texto' => number_format($row -> monto_total, 2) . ' BS.', 'ancho' => 25, 'x' => 72, 'y' => 29, 'estilo' => 'B');
					//Modalidad
					if ($row -> forma_contrato != '1' && $row -> forma_contrato != '2') {
						switch ($row -> periocidad) {
							case '0' :
								$x = 89;
								break;
							case '4' :
								$x = 155;
								break;
							case '2' :
								$x = 127;
								break;
							case '3' :
								$x = 127;
								break;

							default :
								break;
						}
					} else {
						$x = 185;
					}
					$pagina2['periocidad'] = array('texto' => 'X', 'ancho' => 5, 'x' => $x, 'y' => 35, 'estilo' => 'B');

					//fecha cobro
					if ($row -> fecha_inicio_cobro != '') {
						$fecha_cobro = explode('-', $row -> fecha_inicio_cobro);
						$pagina2['fecha_c_d'] = array('texto' => $fecha_cobro[2], 'ancho' => 15, 'x' => 65, 'y' => 42, 'estilo' => 'B');
						$pagina2['fecha_c_m'] = array('texto' => $fecha_cobro[1], 'ancho' => 15, 'x' => 85, 'y' => 42, 'estilo' => 'B');
						$pagina2['fecha_c_a'] = array('texto' => $fecha_cobro[0], 'ancho' => 15, 'x' => 105, 'y' => 42, 'estilo' => 'B');
					}
					//servicio
					$pagina2['servicio'] = array('texto' => 'PLAN DE FINANCIAMIENTO DE LINEA BLANCA', 'ancho' => 115, 'x' => 83, 'y' => 52, 'estilo' => 'B');

					//en
					$pagina2['lugar'] = array('texto' => $row -> codigo_n, 'ancho' => 33, 'x' => 18, 'y' => 208, 'estilo' => 'B');

					//a los
					$fecha_actual = date('d-m-Y');
					$fecha_actual = explode('-', $fecha_actual);
					$mes_L = $this -> mes_letras($fecha_actual[1]);

					//dia
					$pagina2['dia'] = array('texto' => $fecha_actual[0], 'ancho' => 15, 'x' => 62, 'y' => 208, 'estilo' => 'B');
					//mes
					$pagina2['mes'] = array('texto' => $mes_L, 'ancho' => 50, 'x' => 100, 'y' => 208, 'estilo' => 'B');
					//en
					$pagina2['ano'] = array('texto' => $fecha_actual[2], 'ancho' => 12, 'x' => 156, 'y' => 208, 'estilo' => 'B');

				}

			}

			$elemento = array($pagina1, $pagina2);
			$this -> generar_formato($elemento, 'SOFITASA', 10, $img, $img2);
		} else {
			echo "No existe la cedula";
		}

	}

	public function presupuesto($factura, $e = 0, $forma = 'P') {
		$pagina1 = array();
		$query = 'SELECT * FROM t_fpresupuesto WHERE factura="' . $factura . '"';
		$Consulta = $this -> db -> query($query);
		$cantidad = $Consulta -> num_rows();
		if ($cantidad > 0) {
			foreach ($Consulta -> result() as $fact) {
				$fecha = explode('-', $fact -> fecha);
				
				$x1 = 10;
				$x2 = 42;
				$x3 = 52;
				$x4 = 62;
				$xd = 45;
				$xt = 150;
				$e = 0;
				if ($e == 0) {
					$img = 'cooperativa.jpg';
					//linea fecha
					$y1 = 50;
					//linea nombre
					$xn = 57;
					$y2 = 60;
					//linea direccion
					$y3 = 66;
					//linea cedula telefono
					$xc = 85;
					
					$y4 = 86;
					//linea codigo
					$xcod = 160;
					$y5 = 42;
					//detalle
					$y6 = 103;
					
				} else {
					$img = 'grupo.jpg';
					//linea fecha
					$y1 = 45;
					//linea nombre
					$xn = 55;
					$y2 = 54;
					//linea direccion
					$y3 = 60;
					//linea cedula telefono
					$xc = 84;
					$y4 = 80;
					//linea codigo
					$xcod = 160;
					$y5 = 43;
					//detalle
					$y6 = 96;
				}

				//fecha
				$pagina1['lugar'] = array('texto' => 'MERIDA', 'ancho' => 28, 'x' => $x1, 'y' => $y1, 'estilo' => '');
				//dia
				$pagina1['dia'] = array('texto' => $fecha[2], 'ancho' => 10, 'x' => $x2, 'y' => $y1, 'estilo' => '');
				//mes
				$pagina1['mes'] = array('texto' => $fecha[1], 'ancho' => 10, 'x' => $x3, 'y' => $y1, 'estilo' => '');
				//mes
				$pagina1['ano'] = array('texto' => $fecha[0], 'ancho' => 15, 'x' => $x4, 'y' => $y1, 'estilo' => '');
				//nombre
				$pagina1['nombre'] = array('texto' => $fact -> nombre, 'ancho' => 145, 'x' => $xn, 'y' => $y2, 'estilo' => '', 'alinea' => 'L');
				//direccion
				$pagina1['direc'] = array('texto' => $fact -> direccion, 'ancho' => 160, 'x' => $xd, 'y' => $y3, 'estilo' => '', 'multi' => 3, 'alto' => 6, 'alinea' => 'L');
				//cedula
				$pagina1['cedula'] = array('texto' => $fact -> cedula, 'ancho' => 45, 'x' => $xc, 'y' => $y4, 'estilo' => '', 'alinea' => 'L');
				//telefono
				$pagina1['tlf'] = array('texto' => $fact -> telf, 'ancho' => 50, 'x' => $xt, 'y' => $y4, 'estilo' => '', 'alinea' => 'L');
				//codigo
				$pagina1['codigo'] = array('texto' => $fact -> factura, 'ancho' => 45, 'x' => $xcod, 'y' => $y5, 'estilo' => 'B', 'alinea' => 'L');
				//total
				$pagina1['total'] = array('texto' => number_format($fact -> total,2,',','.'), 'ancho' => 30, 'x' => 178, 'y' => 263, 'estilo' => 'B', 'alinea' => 'L');
				if($e != 0){
					//total
					$pagina1['total2'] = array('texto' => number_format($fact -> total,2,',','.'), 'ancho' => 30, 'x' => 178, 'y' => 245, 'estilo' => 'B', 'alinea' => 'L');
				}
				
				//Detalle factura
				$sqlDetalle = "SELECT * FROM t_it_fpresupuesto WHERE factura ='".$factura."'";
				$Detalle = $this -> db -> query($sqlDetalle);
				$i = 0;
				$xd1 = 10;
				$xd2 = 30;
				$xd3 = 138;
				$xd4 = 170;
				foreach ($Detalle -> result() as $det) {
					$i++;
					//cantidad
					$pagina1['cantidad'.$i] = array('texto' => $det -> cantidad, 'ancho' => 15, 'x' => $xd1, 'y' => $y6, 'estilo' => '');
					//precio
					$pagina1['precio'.$i] = array('texto' => number_format($det -> monto,2,',','.'), 'ancho' => 30, 'x' => $xd3, 'y' => $y6, 'estilo' => '');
					//precio total
					$total = number_format($det -> monto * $det -> cantidad,2,',','.');
					$pagina1['total'.$i] = array('texto' => $total, 'ancho' => 30, 'x' => $xd4, 'y' => $y6, 'estilo' => '');
					//des
					$pagina1['des'.$i] = array('texto' => $det -> descrip, 'ancho' => 110, 'x' => $xd2, 'y' => $y6, 'estilo' => '', 'multi' => 6, 'alto' => 6.7, 'alinea' => 'L');
					$y6 += (6.7*7);
				}
				$elemento = array($pagina1);
				$this -> generar_formato($elemento, 'presupuesto', 14, $img);

			}

		} else {
			echo "No existe la factura";
		}

	}

	public function fcontrol($factura, $e = 0, $forma = 'P') {
		$pagina1 = array();
		$query = 'SELECT * FROM t_fcontrol WHERE factura="' . $factura . '"';
		$Consulta = $this -> db -> query($query);
		$cantidad = $Consulta -> num_rows();
		if ($cantidad > 0) {
			foreach ($Consulta -> result() as $fact) {
				$fecha = explode('-', $fact -> fecha);
				switch ($e) {
					case 0:
						$x2 = 155;$x3 = 170;$x4 = 185;$xn = 55;	$xt = 122;$xc = 30;$xto = 170;$xiva=154;$ymiva=167;
						$y1 = 34;
						//linea nombre
						$y2 = 42;
						//linea cedula telefono
						$y3 = 58;
						//detalle
						$y4 = 80;
						//total
						$y5 = 238;
						$yiva = 231;
						$ac = 75;
						$at = 75;
						$atd = 22;
						$add = 115;
						$ato = 25;
						$xd1 = 10;
						$xd2 = 30;
						$xd3 = 146;
						$xd4 = 170;		
						break;
					case 1:
						$x2 = 155;$x3 = 170;$x4 = 185;$xn = 55;	$xt = 115;$xc = 35;$xto = 167;$xiva=155;$ymiva=231;
						$y1 = 41;
						//linea nombre
						$y2 = 50;
						//linea cedula telefono
						$y3 = 65;
						//detalle
						$y4 = 87;
						//total
						$y5 = 238;
						$yiva = 227;
						$ac = 50;
						$at = 50;
						$atd = 30;
						$add = 115;
						$ato = 35;
						$xd1 = 10;
						$xd2 = 27;
						$xd3 = 137;
						$xd4 = 167;
						break;
					case 2:
						$x2 = 155;$x3 = 170;$x4 = 185;$xn = 55;	$xt = 115;$xc = 35;$xto = 167;$xiva=154;
						$y1 = 34;
						//linea nombre
						$y2 = 41;
						//linea cedula telefono
						$y3 = 57;
						//detalle
						$y4 = 78;
						//total
						$y5 = 241;
						$yiva = 226;
						$ac = 50;
						$at = 50;
						$atd = 30;
						$add = 110;
						$ato = 30;
						$xd1 = 10;
						$xd2 = 27;
						$xd3 = 135;
						$xd4 = 165;
						break;
					default:
						
						break;
				}
					

				//dia
				$pagina1['dia'] = array('texto' => $fecha[2], 'ancho' => 10, 'x' => $x2, 'y' => $y1, 'estilo' => '');
				//mes
				$pagina1['mes'] = array('texto' => $fecha[1], 'ancho' => 10, 'x' => $x3, 'y' => $y1, 'estilo' => '');
				//mes
				$pagina1['ano'] = array('texto' => $fecha[0], 'ancho' => 15, 'x' => $x4, 'y' => $y1, 'estilo' => '');
				//nombre
				$pagina1['nombre'] = array('texto' => $fact -> nombre, 'ancho' => 145, 'x' => $xn, 'y' => $y2, 'estilo' => '', 'alinea' => 'L');
				//cedula
				$pagina1['cedula'] = array('texto' => $fact -> cedula, 'ancho' => $ac, 'x' => $xc, 'y' => $y3, 'estilo' => '', 'alinea' => 'L');
				//telefono
				$pagina1['tlf'] = array('texto' => $fact -> telf, 'ancho' => $at, 'x' => $xt, 'y' => $y3, 'estilo' => '', 'alinea' => 'L');
				//total
				$iva = $fact->total * 0.12;
				$pagina1['total'] = array('texto' => number_format($fact -> total+$iva,2,',','.'), 'ancho' => $ato, 'x' => $xto, 'y' => $y5, 'estilo' => 'B', 'alinea' => 'C');
				$pagina1['iva'] = array('texto' => '12', 'ancho' => 9, 'x' => $xiva, 'y' => $yiva, 'estilo' => 'B', 'alinea' => 'C');
				$pagina1['impo'] = array('texto' => number_format($fact->total,2,',','.'), 'ancho' => $ato, 'x' => $xto, 'y' => ($yiva-7), 'estilo' => 'B', 'alinea' => 'C');
				$pagina1['miva'] = array('texto' => number_format($iva,2,',','.'), 'ancho' => $ato, 'x' => $xto, 'y' => $yiva, 'estilo' => 'B', 'alinea' => 'C');
				//Detalle factura
				$sqlDetalle = "SELECT * FROM t_it_fcontrol WHERE factura ='".$factura."'";
				$Detalle = $this -> db -> query($sqlDetalle);
				$i = 0;

				foreach ($Detalle -> result() as $det) {
					$i++;
					//cantidad
					$pagina1['cantidad'.$i] = array('texto' => $det -> cantidad, 'ancho' => 15, 'x' => $xd1, 'y' => $y4, 'estilo' => '');
					//precio
					$pagina1['precio'.$i] = array('texto' => number_format($det -> monto,2,',','.'), 'ancho' => $atd, 'x' => $xd3, 'y' => $y4, 'estilo' => '');
					//precio total
					$total = number_format($det -> monto * $det -> cantidad,2);
					$pagina1['total'.$i] = array('texto' => $total, 'ancho' => 30, 'x' => $xd4, 'y' => $y4, 'estilo' => '');
					//des
					$pagina1['des'.$i] = array('texto' => $det -> descrip, 'ancho' => $add, 'x' => $xd2, 'y' => $y4, 'estilo' => '', 'multi' => 3, 'alto' => 6.7, 'alinea' => 'L','tam'=>10);
					$y4 += (6.7*3);
				}
				$elemento = array($pagina1);
				$this -> generar_formato($elemento, 'control', 12);

			}

		} else {
			echo "No existe la factura";
		}

	}

	public function generar_formato($elementos, $titulo, $tamFont = 10, $img = '', $img2 = '') {
		$this -> load -> library('pdf');
		$pdf = new $this->pdf();
		$font = 'times';
		$pdf -> SetHeaderMargin(0);
		$pdf -> SetFooterMargin(0);
		$pdf -> SetAutoPageBreak(false, 0);
		$pdf -> setImageScale(3.7);
		//$img_file = K_PATH_IMAGES . $img;

		$auxFont = 0;
		$pagina = 1;
		foreach ($elementos as $clave => $pag) {

			$pdf -> setPrintHeader(FALSE);
			$pdf -> setPrintFooter(FALSE);
			$pdf -> AddPage('', 'LETTER');
			if ($img != '' && $pagina == 1) {

				$img_file = K_PATH_IMAGES . $img;
				$pdf -> Image($img_file, 0, 0, 216, 279, '', '', '', false, 300, '', false, false, 0);
			}
			if ($img2 != '' && $pagina == 2) {
				$img_file = K_PATH_IMAGES . $img2;
				$pdf -> Image($img_file, 0, 0, 216, 279, '', '', '', false, 300, '', false, false, 0);
			}
			$pagina++;
			foreach ($pag as $campo => $arreglo) {
				$alinea = 'C';
				$tam = $tamFont;
				if (isset($arreglo['alinea'])) {
					$alinea = $arreglo['alinea'];
				}
				if (isset($arreglo['tam'])) {
					$tam = $arreglo['tam'];
				}
				//$pdf -> SetFont($font, $arreglo['estilo'], $tamFont);
				if (!isset($arreglo['multi'])) {
					$tamAux = $this -> get_tam_font($pdf, $tam, $font, $arreglo['texto'], $arreglo['estilo'], $arreglo['ancho']);
					$pdf -> SetFont($font, $arreglo['estilo'], $tamAux);
					$pdf -> SetXY($arreglo['x'], $arreglo['y']);
					$pdf -> Cell($arreglo['ancho'], 4, $arreglo['texto'], 0, 0, $alinea, 0);
				} else {
					$tamAux = $this -> get_tam_font($pdf, $tam, $font, $arreglo['texto'], $arreglo['estilo'], $arreglo['ancho'] * $arreglo['multi']);
					$pdf -> SetFont($font, $arreglo['estilo'], $tamAux);
					$pdf -> MultiCell($arreglo['ancho'], $arreglo['alto'], '');
					$pdf -> SetXY($arreglo['x'], $arreglo['y']);
					$pdf -> MultiCell($arreglo['ancho'], 0, $arreglo['texto'], 0, $alinea, 0, 0, '', '', 0);
				}

			}

		}
		$pdf -> Output($titulo . '.pdf', 'D');

	}

	public function get_tam_font($pdf, $tam, $font, $cad, $est, $ancho) {
		$tamAux = $tam;
		$auxFont = $pdf -> GetStringWidth($cad, $font, $est, $tamAux);

		while ($auxFont > $ancho) {
			$tamAux--;
			$auxFont = $pdf -> GetStringWidth($cad, $font, $est, $tamAux);
		}

		return $tamAux;
	}

	public function lugar($lugar) {
		switch ($lugar) {
			case 'BARINAS' :
				$ente = 'BARINAS';
				break;
			case 'Barina (Principal)' :
				$ente = 'BARINAS';
				break;
			case 'EL VIGIA' :
				$ente = 'MERIDA';
				break;
			case 'El Vigia (Principal)' :
				$ente = 'MERIDA';
				break;
			case 'Merida (Principal)' :
				$ente = 'MERIDA';
				break;
			case 'Merida (Las Tejas)' :
				$ente = 'MERIDA';
				break;
			case 'CAJA SECA' :
				$ente = 'ZULIA';
				break;
			case 'SAN CRISTOBAL' :
				$ente = 'TACHIRA';
				break;
			case 'Sancristobal (Principal)' :
				$ente = 'TACHIRA';
				break;
			case 'Sancristobal (Palmira)' :
				$ente = 'TACHIRA';
				break;
			case 'SANTA BARBARA' :
				$ente = 'ZULIA';
				break;
			case 'Santa Barbara Del Zulia (Principal)' :
				$ente = 'ZULIA';
				break;
			case 'Zulia (Principal)' :
				$ente = 'ZULIA';
				break;
			default :
				$ente = $lugar;
				break;
		}
		return $ente;
	}

	public function mes_letras($mesN) {
		switch ($mesN) {
			case '1' :
				$mes = "ENERO";
				break;
			case '2' :
				$mes = "FEBRERO";
				break;
			case '3' :
				$mes = "MARZO";
				break;
			case '4' :
				$mes = "ABRIL";
				break;
			case '5' :
				$mes = "MAYO";
				break;
			case '6' :
				$mes = "JUNIO";
				break;
			case '7' :
				$mes = "JULIO";
				break;
			case '8' :
				$mes = "AGOSTO";
				break;
			case '9' :
				$mes = "SEPTIEMBRE";
				break;
			case '10' :
				$mes = "OCTUBRE";
				break;
			case '11' :
				$mes = "NOVIEMBRE";
				break;
			case '12' :
				$mes = "DICIEMBRE";
				break;
			default :
				break;
		}
		return $mes;
	}

	function ValorEnLetras($x, $Moneda) {
		$s = "";
		$Ent = "";
		$Frc = "";
		$Signo = "";

		if (floatVal($x) < 0)
			$Signo = $this -> Neg . " ";
		else
			$Signo = "";

		if (intval(number_format($x, 2, '.', '')) != $x)//<- averiguar si tiene decimales
			$s = number_format($x, 2, '.', '');
		else
			$s = number_format($x, 0, '.', '');

		$Pto = strpos($s, $this -> Dot);

		if ($Pto === false) {
			$Ent = $s;
			$Frc = $this -> Void;
		} else {
			$Ent = substr($s, 0, $Pto);
			$Frc = substr($s, $Pto + 1);
		}

		if ($Ent == $this -> Zero || $Ent == $this -> Void)
			$s = "Cero ";
		elseif (strlen($Ent) > 7) {
			$s = $this -> SubValLetra(intval(substr($Ent, 0, strlen($Ent) - 6))) . "Millones " . $this -> SubValLetra(intval(substr($Ent, -6, 6)));
		} else {
			$s = $this -> SubValLetra(intval($Ent));
		}

		if (substr($s, -9, 9) == "Millones " || substr($s, -7, 7) == "Millón ")
			$s = $s . "de ";

		$s = $s . $Moneda;

		if ($Frc != $this -> Void) {
			$s = $s . " Con " . $this -> SubValLetra(intval($Frc)) . "Centimos";
			//$s = $s . " " . $Frc . "/100";
		}
		return ($Signo . $s . "");

	}

	function SubValLetra($numero) {
		$Ptr = "";
		$n = 0;
		$i = 0;
		$x = "";
		$Rtn = "";
		$Tem = "";

		$x = trim("$numero");
		$n = strlen($x);

		$Tem = $this -> Void;
		$i = $n;

		while ($i > 0) {
			$Tem = $this -> Parte(intval(substr($x, $n - $i, 1) . str_repeat($this -> Zero, $i - 1)));
			If ($Tem != "Cero")
				$Rtn .= $Tem . $this -> SP;
			$i = $i - 1;
		}

		//--------------------- GoSub FiltroMil ------------------------------
		$Rtn = str_replace(" Mil Mil", " Un Mil", $Rtn);
		while (1) {
			$Ptr = strpos($Rtn, "Mil ");
			If (!($Ptr === false)) {
				If (!(strpos($Rtn, "Mil ", $Ptr + 1) === false))
					$this -> ReplaceStringFrom($Rtn, "Mil ", "", $Ptr);
				Else
					break;
			} else
				break;
		}

		//--------------------- GoSub FiltroCiento ------------------------------
		$Ptr = -1;
		do {
			$Ptr = strpos($Rtn, "Cien ", $Ptr + 1);
			if (!($Ptr === false)) {
				$Tem = substr($Rtn, $Ptr + 5, 1);
				if ($Tem == "M" || $Tem == $this -> Void)
					;
				else
					$this -> ReplaceStringFrom($Rtn, "Cien", "Ciento", $Ptr);
			}
		} while(!($Ptr === false));

		//--------------------- FiltroEspeciales ------------------------------
		$Rtn = str_replace("Diez Un", "Once", $Rtn);
		$Rtn = str_replace("Diez Dos", "Doce", $Rtn);
		$Rtn = str_replace("Diez Tres", "Trece", $Rtn);
		$Rtn = str_replace("Diez Cuatro", "Catorce", $Rtn);
		$Rtn = str_replace("Diez Cinco", "Quince", $Rtn);
		$Rtn = str_replace("Diez Seis", "Dieciseis", $Rtn);
		$Rtn = str_replace("Diez Siete", "Diecisiete", $Rtn);
		$Rtn = str_replace("Diez Ocho", "Dieciocho", $Rtn);
		$Rtn = str_replace("Diez Nueve", "Diecinueve", $Rtn);
		$Rtn = str_replace("Veinte Un", "Veintiun", $Rtn);
		$Rtn = str_replace("Veinte Dos", "Veintidos", $Rtn);
		$Rtn = str_replace("Veinte Tres", "Veintitres", $Rtn);
		$Rtn = str_replace("Veinte Cuatro", "Veinticuatro", $Rtn);
		$Rtn = str_replace("Veinte Cinco", "Veinticinco", $Rtn);
		$Rtn = str_replace("Veinte Seis", "Veintiseís", $Rtn);
		$Rtn = str_replace("Veinte Siete", "Veintisiete", $Rtn);
		$Rtn = str_replace("Veinte Ocho", "Veintiocho", $Rtn);
		$Rtn = str_replace("Veinte Nueve", "Veintinueve", $Rtn);

		//--------------------- FiltroUn ------------------------------
		If (substr($Rtn, 0, 1) == "M")
			$Rtn = "Un " . $Rtn;
		//--------------------- Adicionar Y ------------------------------
		for ($i = 65; $i <= 88; $i++) {
			If ($i != 77)
				$Rtn = str_replace("a " . Chr($i), "* y " . Chr($i), $Rtn);
		}
		$Rtn = str_replace("*", "a", $Rtn);
		return ($Rtn);
	}

	function ReplaceStringFrom(&$x, $OldWrd, $NewWrd, $Ptr) {
		$x = substr($x, 0, $Ptr) . $NewWrd . substr($x, strlen($OldWrd) + $Ptr);
	}

	function Parte($x) {
		$Rtn = '';
		$t = '';
		$i = '';
		Do {
			switch($x) {
				Case 0 :
					$t = "Cero";
					break;
				Case 1 :
					$t = "Un";
					break;
				Case 2 :
					$t = "Dos";
					break;
				Case 3 :
					$t = "Tres";
					break;
				Case 4 :
					$t = "Cuatro";
					break;
				Case 5 :
					$t = "Cinco";
					break;
				Case 6 :
					$t = "Seis";
					break;
				Case 7 :
					$t = "Siete";
					break;
				Case 8 :
					$t = "Ocho";
					break;
				Case 9 :
					$t = "Nueve";
					break;
				Case 10 :
					$t = "Diez";
					break;
				Case 20 :
					$t = "Veinte";
					break;
				Case 30 :
					$t = "Treinta";
					break;
				Case 40 :
					$t = "Cuarenta";
					break;
				Case 50 :
					$t = "Cincuenta";
					break;
				Case 60 :
					$t = "Sesenta";
					break;
				Case 70 :
					$t = "Setenta";
					break;
				Case 80 :
					$t = "Ochenta";
					break;
				Case 90 :
					$t = "Noventa";
					break;
				Case 100 :
					$t = "Cien";
					break;
				Case 200 :
					$t = "Doscientos";
					break;
				Case 300 :
					$t = "Trescientos";
					break;
				Case 400 :
					$t = "Cuatrocientos";
					break;
				Case 500 :
					$t = "Quinientos";
					break;
				Case 600 :
					$t = "Seiscientos";
					break;
				Case 700 :
					$t = "Setecientos";
					break;
				Case 800 :
					$t = "Ochocientos";
					break;
				Case 900 :
					$t = "Novecientos";
					break;
				Case 1000 :
					$t = "Mil";
					break;
				Case 1000000 :
					$t = "Millón";
					break;
			}

			If ($t == $this -> Void) {
				$i = $i + 1;
				$x = $x / 1000;
				If ($x == 0)
					$i = 0;
			} else
				break;

		} while($i != 0);

		$Rtn = $t;
		Switch($i) {
			Case 0 :
				$t = $this -> Void;
				break;
			Case 1 :
				$t = " Mil";
				break;
			Case 2 :
				$t = " Millones";
				break;
			Case 3 :
				$t = " Billones";
				break;
		}
		return ($Rtn . $t);
	}

	/**function Calcular_Fin_Descuento() {
	 $cuotas = $txtNumeroCuotas;
	 $periodo = $txtNominaPeriocidad;
	 $dia_inicio = $txtDiaDescuento;
	 $mes_inicio = $txtMesDescuento;
	 $ano_inicio = $txtAnoDescuento;
	 $dia_fin = 0;
	 $mes_fin = 0;
	 $ano_fin = 0;
	 $base_mes = 0;
	 $tiempo = 0;
	 switch(periodo) {
	 case '0':
	 $base_mes = 1 / 4;
	 break;
	 case '1':
	 $base_mes = 1 / 2;
	 break;
	 case '2':
	 $base_mes = 1 / 2;
	 break;
	 case '3':
	 $base_mes = 1 / 2;
	 break;
	 case '4':
	 $base_mes = 1;
	 break;
	 case '5':
	 $base_mes = 3;
	 break;
	 case '6':
	 $base_mes = 6;
	 break;
	 case '7':
	 $base_mes = 12;
	 break;
	 }
	 $tiempo = ($cuotas - 1)  * $base_mes;
	 $tiempo_picado = String($tiempo).split('.');
	 $tiempo_picado = explode('.',$tiempo);
	 var ano_t =  parseInt(parseInt(tiempo_picado[0]) / 12);
	 ano_t += parseInt(ano_inicio);
	 mes_t = parseInt(tiempo_picado[0])%12;
	 dia_t = parseInt(dia_inicio);
	 if (tiempo_picado[1] != null){
	 switch(parseInt(tiempo_picado[1])){
	 case 25:
	 dia_t += 7;
	 break;
	 case 5:
	 dia_t += 15;
	 break;
	 case 75:
	 dia_t += 21;
	 break;
	 }
	 }
	 if (dia_t > 30){
	 mes_t +=1;
	 diferencia = dia_t - 30;
	 dia_t = diferencia;
	 }

	 var suma_meses = parseInt(mes_t) + parseInt(mes_inicio);
	 if(suma_meses > 12){
	 ano_t += 1;
	 mes_t = suma_meses-12;
	 }else{
	 mes_t = suma_meses;
	 }
	 $("#txtFinDiaDescuento").val(dia_t);
	 $("#txtFinMesDescuento").val(mes_t);
	 $("#txtFinAnoDescuento").val(ano_t);
	 }**/

}
?>