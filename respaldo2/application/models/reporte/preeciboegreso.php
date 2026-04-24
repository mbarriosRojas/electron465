<?php
class PReeciboegreso extends Model {

	var $Void = "";

	var $SP = " ";

	var $Dot = ".";

	var $Zero = "0";

	var $Neg = "Menos";

	public $lstUsr;

	public $usuario;

	public function __construct() {
		parent::__construct();uct();
	}
	
	public function ReciboE($recibo) {
		$this -> load -> library('pdf');
		$pdf = new $this->pdf();
		$font = 'times';
		$pdf -> SetHeaderMargin(0);
		$pdf -> SetFooterMargin(0);
		$pdf -> SetAutoPageBreak(FALSE, 0);
		$pdf -> setImageScale(3.7);
		$query = "SELECT *
		FROM t_recibo_egreso
		WHERE numero_recibo='" .$recibo . "' LIMIT 1";
		$Consulta = $this -> db -> query($query);
		$cant = 2;
		if ($Consulta -> num_rows() > 0) {
			foreach ($Consulta->result() as $row) {
				$cedula = $row -> documento_id;
				$recibo = $row -> numero_recibo;
				$recibido = $row -> recibido;
				$fecha = explode('-', $row -> fecha);
				$monto = $row -> monto;
				$concepto = $row -> concepto;
				$banco = $row -> banco;
				$tipo = $row -> tipo_pago;
				$cheque = $row -> numero_cheque;
				$empresa = $row -> empresa;
			}
		}
		//$img_file = K_PATH_IMAGES . $img;
		$y = 13;
		$x1 = 165;
		$y1 = 23 ;
		$x2 = 152;
		$y2 = 38;
		$x3 = 25;
		$x4 = 39;
		$x5 = 53;
		
		$x6= 37;
		$y4 = 46;
		$y5 = 52;
		$y7 = 64;
		
		$x7 = 135;
		$y6 = 59;
		
		$y7= 66;
		
		$y8 =84;
		$xt = 25.5;
		if($tipo == 'C'){
			$xt = 47;
		}
		
		$y9 = 90;
		$xc = 33;
		$pdf -> setPrintHeader(FALSE);
		$pdf -> setPrintFooter(FALSE);
		$pdf -> AddPage('', 'LETTER');
		$pdf -> SetFont($font, 'B', 10);
		$img = 'recibo_egreso_grupo.jpg';
		if($empresa == 0){
			$img = 'recibo_egreso_cooperativa.jpg';	
		}
		
		$img_file = K_PATH_IMAGES . $img;
		for ($i = 1;$i <= $cant ;$i++){
			
			$pdf -> Image($img_file, 10, $y, 0, 90, '', '', '', false, 300, '', false, false, 0);
			//recibo
			$pdf -> SetFont($font, 'B', 14);
			$pdf -> SetXY($x1,$y1);
			$pdf -> Cell(30, 4, $recibo, 0, 0, 'C', 0);
			//monto
			$pdf -> SetXY($x2 , $y2);
			$pdf -> Cell(46, 4, number_format($monto,2), 0, 0, 'C', 0);
			//fecha
			$pdf -> SetXY($x3 , $y2);
			$pdf -> Cell(14, 4, $fecha[2], 0, 0, 'C', 0);
			$pdf -> SetXY($x4 , $y2);
			$pdf -> Cell(14, 4, $fecha[1], 0, 0, 'C', 0);
			$pdf -> SetXY($x5 , $y2);
			$pdf -> Cell(28, 4, $fecha[0], 0, 0, 'C', 0);
			//cliente
			$tam = 160;
			$pdf -> SetFont($font, 'B', 10);
			$pdf -> SetXY($x6 , $y4);
			$tamAux = $this -> get_tam_font($pdf, 10, $font, $recibido, 'B', $tam);
			$pdf -> SetFont($font, 'B', $tamAux);
			$pdf -> Cell($tam, 4, $recibido, 0, 0, 'C', 0);
			//monto_letras
			$ancho = 160;
			$pdf -> SetFont($font, 'B', 10);
			$letras = strtoupper($this -> ValorEnLetras($monto, ''));
			$tamAux = $this -> get_tam_font($pdf, 8, $font, $letras, 'B', 120);
			$pdf -> SetFont($font, 'B', $tamAux);
			$pdf -> SetXY($x6, $y5);
			$pdf -> MultiCell($ancho, 0, $letras, 0, 'C', 0, 0, '', '', 0);
			//cedula
			$pdf -> SetXY($x7 , $y6);
			$pdf -> SetFont($font, 'B', 10);
			$pdf -> Cell(63, 4, $cedula, 0, 0, 'C', 0);
			//motivo
			
			$pdf -> SetFont($font, 'B', 10);
			$pdf -> SetXY($x6 , $y7);
			$pdf -> MultiCell(160, 10, $concepto, 0, 'L', 0, 0, '', '', TRUE);
			//tipo
			$pdf -> SetXY($xt , $y8);
			$pdf -> SetFont($font, 'B', 10);
			$pdf -> Cell(5, 4, 'X', 0, 0, 'C', 0);
			//cheque
			$pdf -> SetXY($xc , $y9);
			$pdf -> SetFont($font, 'B', 10);
			$pdf -> Cell(55, 4, $cheque, 0, 0, 'C', 0);
			
			//banco
			$pdf -> SetXY($xc-10 , $y9+5);
			$pdf -> SetFont($font, 'B', 10);
			$pdf -> Cell(65, 4, $banco, 0, 0, 'C', 0);
						
			$y +=132.3;
			$y1+=132.3;
			$y2+=132.3;
			$y4+=132.3;
			$y5+=132.3;
			$y6+=132.3;
			$y7+=132.3;
			$y8+=132.3;
			$y9+=132.3;	
		}
		
		
		$pdf -> Output('RI_'.$recibo.'.pdf', 'D');

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


}
?>