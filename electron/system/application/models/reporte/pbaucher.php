<?php
class PBaucher extends Model {

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


	public function provincial($cedula, $contrato) {
		$this -> load -> library('pdf');
		$pdf = new $this->pdf();
		$font = 'times';
		$pdf -> SetHeaderMargin(0);
		$pdf -> SetFooterMargin(0);
		$pdf -> SetAutoPageBreak(FALSE, 0);
		$pdf -> setImageScale(3.7);
		$query = "SELECT CONCAT(primer_nombre,' ',segundo_nombre,' ',primer_apellido,' ',segundo_apellido)AS nombre, numero_cuotas, monto_cuota,t_personas.documento_id, numero_factura
		FROM t_clientes_creditos
		JOIN t_personas ON t_clientes_creditos.documento_id = t_personas.documento_id  
		WHERE t_clientes_creditos.contrato_id='" . $contrato . "' LIMIT 1";
		$Consulta = $this -> db -> query($query);
		$cant = 0;
		$monto = 0;
		$nombre = '';
		$factura = '';
		$numero_pag = 0;
		if ($Consulta -> num_rows() > 0) {
			foreach ($Consulta->result() as $row) {
				$cant = $row -> numero_cuotas;
				$monto = $row -> monto_cuota;
				$factura = $row -> numero_factura;
				$nombre = $row -> nombre;
			}
		}
		//$img_file = K_PATH_IMAGES . $img;
		$x1 = 198;
		$y1 = 13;
		$x2 = 97;
		$y2 = 23;
		$x3 = 199;
		$x4 = 21;
		$y4 = 29;
		$x5 = 67;
		$y5 = 84;
		$x6 = 85;
		$x7 = 184;
		$y7 = 46.5;
		$y8 = 72;
		$y = 5;

		$pdf -> setPrintHeader(FALSE);
		$pdf -> setPrintFooter(FALSE);
		$pdf -> AddPage('', 'LETTER');
		$pdf -> SetFont($font, 'B', 10);
		$img = 'b_provincial.jpg';
		$img_file = K_PATH_IMAGES . $img;
		for ($i = 1; $i <= $cant; $i++) {
			$numero_pag++;
			if ($numero_pag > 3) {
				$y = 5;
				$y1 = 13;
				$y2 = 23;
				$y4 = 29;
				$y5 = 84;
				$y7 = 46.5;
				$y8 = 72;
				$numero_pag = 1;
				$pdf -> AddPage('', 'LETTER');
			}
			$pdf -> Image($img_file, 5, $y, 0, 88, '', '', '', false, 300, '', false, false, 0);
			//factura
			$caracteres = strlen($factura);
			$pdf -> SetY($y1);
			for ($j = $caracteres; $j > 0; $j--) {
				$pdf -> SetX($x1);
				$pdf -> Cell(6.7, 4, $factura[$j - 1], 0, 0, 'C', 0);
				$x1 = $x1 - 6.7;
			}
			$x1 = 198;

			//nombre
			$pdf -> SetXY($x2, $y2);
			$tam = 68;
			$tamAux = $this -> get_tam_font($pdf, 10, $font, $nombre, 'B', $tam);
			$pdf -> SetFont($font, 'B', $tamAux);
			$pdf -> Cell($tam, 4, $nombre, 0, 0, 'C', 0);
			//contrato
			$ancho = 40;
			$pdf -> SetFont($font, 'B', 10);
			$tamAux = $this -> get_tam_font($pdf, 10, $font, $contrato, 'B', $ancho);
			$pdf -> SetFont($font, 'B', $tamAux);
			$pdf -> SetY($y2);
			$caracteres = strlen($cedula);
			for ($j = $caracteres; $j > 0; $j--) {
				$pdf -> SetX($x3);
				$pdf -> Cell(5, 4, $cedula[$j - 1], 0, 0, 'C', 0);
				$x3 = $x3 - 5;
			}
			$x3 = 199;

			//monto
			$pdf -> SetXY($x7, $y7);
			$ancho = 20;
			$pdf -> SetFont($font, 'B', 7);
			$monto_t = number_format($monto, 2);
			$tamAux = $this -> get_tam_font($pdf, 7, $font, $monto_t, 'B', $ancho);
			$pdf -> SetFont($font, 'B', $tamAux);
			$pdf -> Cell($ancho, 4, $monto_t, 0, 0, 'C', 0);
			//monto2
			$pdf -> SetXY($x7, $y8);
			$ancho = 20;
			$pdf -> SetFont($font, 'B', 7);
			$tamAux = $this -> get_tam_font($pdf, 7, $font, $monto_t, 'B', $ancho);
			$pdf -> SetFont($font, 'B', $tamAux);
			$pdf -> Cell($ancho, 4, $monto_t, 0, 0, 'C', 0);
			//monto_letras
			$ancho = 60;
			$pdf -> SetFont($font, 'B', 8);
			$letras = strtoupper($this -> ValorEnLetras($monto, ''));
			$tamAux = $this -> get_tam_font($pdf, 8, $font, $letras, 'B', 120);
			$pdf -> SetFont($font, 'B', $tamAux);
			$pdf -> MultiCell($ancho, 4, '');
			$pdf -> SetXY($x4, $y4);
			$pdf -> MultiCell($ancho, 0, $letras, 0, 'C', 0, 0, '', '', 0);
			//numero
			$pdf -> SetXY($x5, $y5);
			$ancho = 10;
			$pdf -> SetFont($font, 'B', 10);
			$tamAux = $this -> get_tam_font($pdf, 10, $font, $i, 'B', $ancho);
			$pdf -> SetFont($font, 'B', $tamAux);
			$pdf -> Cell($ancho, 4, $i, 0, 0, 'C', 0);
			//de
			$pdf -> SetXY($x6, $y5);
			$ancho = 10;
			$pdf -> SetFont($font, 'B', 10);
			$tamAux = $this -> get_tam_font($pdf, 10, $font, $cant, 'B', $ancho);
			$pdf -> SetFont($font, 'B', $tamAux);
			$pdf -> Cell($ancho, 4, $cant, 0, 0, 'C', 0);

			$y += 90;
			$y1 += 90;
			$y2 += 90;
			$y4 += 90;
			$y5 += 90;
			$y7 += 90;
			$y8 += 90;
		}

		$pdf -> Output('b_provincial.pdf', 'D');

	}

	public function provincial_nuevo($factura = '') {
		$query_tipo_voucher = $this -> db -> query("SELECT banco from t_lista_voucher WHERE cid = '$factura' AND banco='PRV' GROUP BY cid ");
		$banco_voucher = '';
		foreach ($query_tipo_voucher->result() as $tipo_v) {
			$banco_voucher = $tipo_v -> banco;
		}
		if ($banco_voucher == 'PRV') {

			$this -> load -> library('pdf');
			$pdf = new $this->pdf();
			$font = 'times';
			$pdf -> SetHeaderMargin(0);
			$pdf -> SetFooterMargin(0);
			$pdf -> SetAutoPageBreak(FALSE, 0);
			$pdf -> setImageScale(3.7);
			$query = "SELECT CONCAT(primer_nombre,' ',segundo_nombre,' ',primer_apellido,' ',segundo_apellido)AS nombre,  
		monto_cuota,numero_cuotas,t_personas.documento_id as cedula, numero_factura,cid,monto,ndep
		FROM t_clientes_creditos
		JOIN t_lista_voucher ON t_lista_voucher.cid = t_clientes_creditos.numero_factura
		JOIN t_personas on t_personas.documento_id = t_clientes_creditos.documento_id
		WHERE t_clientes_creditos.numero_factura='" . $factura . "' AND t_lista_voucher.banco='PRV' GROUP BY ndep";
			$Consulta = $this -> db -> query($query);
			$x1 = 194.5;
			$y1 = 37;
			$x2 = 72;
			$y2 = 46.5;
			$x3 = 194.5;
			$x4 = 21;
			$y4 = 29;
			$x5 = 67;
			$y5 = 84;
			$x6 = 85;
			$x7 = 184;
			$y7 = 70;
			$y8 = 74;
			$y = 5;
			$img = 'provincial.jpg';
			$img_file = K_PATH_IMAGES . $img;
			foreach ($Consulta -> result() as $cuota) {

				$pdf -> setPrintHeader(FALSE);
				$pdf -> setPrintFooter(FALSE);
				$pdf -> AddPage('', 'LETTER');
				$pdf -> SetFont($font, 'B', 10);
				$img = 'b_provincial.jpg';
				$pdf -> Image($img_file, 0, 0, 210, 297, '', '', '', false, 300, '', false, false, 0);
				//factura
				$caracteres = strlen($cuota -> ndep);

				$pdf -> SetY($y2);
				for ($j = $caracteres; $j > 0; $j--) {
					$pdf -> SetX($x1);
					$pdf -> Cell(6.2, 4, $cuota -> ndep[$j - 1], 0, 0, 'C', 0);
					$x1 = $x1 - 6.2;
				}
				$x1 = 194.5;

				//nombre
				$pdf -> SetXY($x2, $y2);
				$tam = 75;
				$tamAux = $this -> get_tam_font($pdf, 10, $font, $cuota -> nombre, 'B', $tam);
				$pdf -> SetFont($font, 'B', $tamAux);
				$pdf -> Cell($tam, 4, $cuota -> nombre, 0, 0, 'C', 0);
				//cedula
				$ancho = 90;
				$pdf -> SetFont($font, 'B', 10);
				$tamAux = $this -> get_tam_font($pdf, 10, $font, $cuota -> cedula, 'B', $ancho);
				$pdf -> SetFont($font, 'B', $tamAux);
				$pdf -> SetY($y1);
				$caracteres = strlen($cuota -> cedula);
				for ($j = $caracteres; $j > 0; $j--) {
					$pdf -> SetX($x3);
					$pdf -> Cell(6.2, 4, $cuota -> cedula[$j - 1], 0, 0, 'C', 0);
					$x3 = $x3 - 6.2;
				}
				$x3 = 194.5;

				//monto
				$monto = $cuota -> monto;
				$pdf -> SetXY($x7, $y7);
				$ancho = 20;
				$pdf -> SetFont($font, 'B', 7);
				$monto_t = number_format($monto, 2);
				$tamAux = $this -> get_tam_font($pdf, 7, $font, $monto_t, 'B', $ancho);
				$pdf -> SetFont($font, 'B', $tamAux);
				$pdf -> Cell($ancho, 4, $monto_t, 0, 0, 'C', 0);
				//monto2
				$pdf -> SetXY($x7, $y8);
				$ancho = 20;
				$pdf -> SetFont($font, 'B', 7);
				$tamAux = $this -> get_tam_font($pdf, 7, $font, $monto_t, 'B', $ancho);
				$pdf -> SetFont($font, 'B', $tamAux);
				$pdf -> Cell($ancho, 4, $monto_t, 0, 0, 'C', 0);
				//monto_letras
				$ancho = 60;
				$pdf -> SetFont($font, 'B', 8);
				$letras = strtoupper($this -> ValorEnLetras($monto, ''));
				$tamAux = $this -> get_tam_font($pdf, 8, $font, $letras, 'B', 120);
				$pdf -> SetFont($font, 'B', $tamAux);
				$pdf -> MultiCell($ancho, 4, '');
				$pdf -> SetXY($x4, $y8 - 1);
				$pdf -> MultiCell($ancho, 0, $letras, 0, 'L', 1, 0, '', '', 0);
			}

			$pdf -> Output('b_provincial.pdf', 'D');
		} else {
			echo "ESTA FACTURA NO FUE CREADA PARA VOUCHER PROVINCIAL";
		}

	}

	public function Bicentenario($factura = '') {
		$query_tipo_voucher = $this -> db -> query("SELECT banco from t_lista_voucher WHERE cid = '$factura' AND banco='BIC' GROUP BY cid ");
		$banco_voucher = '';
		foreach ($query_tipo_voucher->result() as $tipo_v) {
			$banco_voucher = $tipo_v -> banco;
		}
		if ($banco_voucher == 'BIC') {

			$this -> load -> library('pdf');
			$pdf = new $this->pdf();
			$font = 'times';
			$pdf -> SetHeaderMargin(0);
			$pdf -> SetFooterMargin(0);
			$pdf -> SetAutoPageBreak(FALSE, 0);
			$pdf -> setImageScale(3.7);
			$query = "SELECT CONCAT(primer_nombre,' ',primer_apellido)AS nombre,  
		monto_cuota,numero_cuotas,t_personas.documento_id as cedula, numero_factura,cid,monto,ndep
		FROM t_clientes_creditos
		JOIN t_lista_voucher ON t_lista_voucher.cid = t_clientes_creditos.numero_factura
		JOIN t_personas on t_personas.documento_id = t_clientes_creditos.documento_id
		WHERE t_clientes_creditos.numero_factura='" . $factura . "' AND t_lista_voucher.banco='BIC' GROUP BY ndep";
			$Consulta = $this -> db -> query($query);
			$x1 = 26;
			$y1 = 15;
			$ab = 80;
			$alb = 78;
			//Nombre Cliente
			$cx1 = 27;
			$cy1 = 61.5;
			$ac1 = 36;
			//Monto deposito
			$cx2 = 83;
			//valor de x
			$cy2 = 79;
			//valor de y
			$ac2 = 19;
			//valor del ancho
			//Monto deposito 2
			$cy3 = 84;
			//pagos
			$cx4 = 45;
			//valor de x
			$cy4 = 70;
			//valor de y
			$ac4 = 17;
			//valor del ancho
			//codigo cliente
			$cx5 = 98;
			//valor de x
			$cy5 = 70;
			//valor de y
			$ac5 = 5;
			//valor del ancho
			//
			//numero de cedula
			$cx6 = 98;
			//valor de x
			$cy6 = 61;
			//valor de y
			//
			$img = 'bicentenario.jpg';
			$img_file = K_PATH_IMAGES . $img;
			$pdf -> setPrintHeader(FALSE);
			$pdf -> setPrintFooter(FALSE);
			$pdf -> AddPage('', 'LETTER');
			$pdf -> SetFont($font, 'B', 10);
			$img = 'b_provincial.jpg';
			$ni = 0;
			$np = 6;
			$cp = 0;
			$i = 0;
			$cant = $Consulta -> num_rows();
			// Ojo: colocar para repetir en las paginas
			foreach ($Consulta -> result() as $cuota) {
				$i++;
				$cp++;

				if ($cp > $np) {
					$cp = 1;
					$pdf -> setPrintHeader(FALSE);
					$pdf -> setPrintFooter(FALSE);
					$pdf -> AddPage('', 'LETTER');
					$y1 = 15;
					$cy1 = 61;
					$cy2 = 79;
					$cy3 = 84;
					$cy4 = 70;
					$cy5 = 70;
					$cy6 = 61;
				}
				// Ojo: colocar para imprimir en hoja
				$pdf -> Image($img_file, $x1, $y1, $ab, $alb, '', '', '', false, 300, '', false, false, 0);
				//Nombre del cliente
				$pdf -> SetXY($cx1, $cy1);
				$pdf -> SetFont($font, 'B', 9);
				$tamAux = $this -> get_tam_font($pdf, 9, $font, $cuota -> nombre, 'B', $ac1);
				$pdf -> SetFont($font, 'B', $tamAux);
				$pdf -> Cell($ac1, 4, $cuota -> nombre, 0, 0, 'C', 0);

				//Monto deposito
				$pdf -> SetXY($cx2, $cy2);
				$pdf -> SetFont($font, 'B', 8);
				$pdf -> Cell($ac2, 4, number_format($cuota -> monto, 2), 0, 0, 'C', 0);
				//Monto deposito2
				$pdf -> SetXY($cx2, $cy3);
				$pdf -> SetFont($font, 'B', 8);
				$pdf -> Cell($ac2, 4, number_format($cuota -> monto, 2), 0, 0, 'C', 0);
				//pagos
				$pdf -> SetXY($cx4, $cy4);
				$pdf -> SetFont($font, 'B', 8);
				$pdf -> Cell($ac4, 4, $i . " DE " . $cant, 0, 0, 'C', 0);

				//numero factura
				$caracteres = strlen($cuota -> ndep);

				$pdf -> SetY($cy5);
				for ($j = $caracteres; $j > 0; $j--) {
					$pdf -> SetX($cx5);
					$pdf -> Cell($ac5, 4, $cuota -> ndep[$j - 1], 0, 0, 'C', 0);
					$cx5 -= $ac5;
				}
				$cx5 = 98;

				//codigo cliente
				$caracteres = strlen($cuota -> cedula);

				$pdf -> SetY($cy6);
				for ($j = $caracteres; $j > 0; $j--) {
					$pdf -> SetX($cx6);
					$pdf -> Cell($ac5, 4, $cuota -> cedula[$j - 1], 0, 0, 'C', 0);
					$cx6 -= $ac5;
				}
				$cx6 = 98;

				// Ojo: colocar para repetir en las celdas
				$ni++;
				if ($ni == 1) {
					$x1 += $ab;
					$cx1 += $ab;
					$cx2 += $ab;
					$cx4 += $ab;

					$cx5 += $ab;
					$cx6 += $ab;
				}
				if ($ni == 2) {
					$y1 = $y1 + $alb;
					$cy1 += $alb;
					$cy2 += $alb;
					$cy3 += $alb;
					$cy4 += $alb;
					$cy5 += $alb;
					$cy6 += $alb;
					$x1 = $x1 - $ab;
					$cx1 -= $ab;
					$cx2 -= $ab;
					$cx4 -= $ab;
					$ni = 0;
				}

			}

			$pdf -> Output('b_provincial.pdf', 'I');
		} else {
			echo "ESTA FACTURA NO FUE CREADA PARA VOUCHER BICENTENARIO";
		}

	}

	public function Constancia_Entrega($orden = '') {
		$query = "SELECT * from t_orden_entrega WHERE orden = '" . $orden . "' ";
		$this -> load -> library('pdf');
		$Consulta = $this -> db -> query($query);
		$cant = $Consulta -> num_rows();
		if ($cant > 0) {
			foreach ($Consulta -> result() as $row) {

				$pdf = new $this->pdf();
				$font = 'times';
				$pdf -> setPrintHeader(FALSE);
				$pdf -> setPrintFooter(FALSE);
				$pdf -> AddPage('', 'LEGAL');
				$pdf -> SetFont($font, 'B', 10);
				//Oficina
				$cx1 = 36;
				$cy1 = 44;
				$ac1 = 60;
				//Telefono
				$cy2 = 50;
				//valor de y
				//Chofer
				$cy3 = 56;
				//valor de y
				//Placa
				$cy4 = 62;
				//valor de y
				//Hora Salida
				$cy5 = 68;
				//valor de y
				//
				//Destino
				$cx6 = 132;
				$cy6 = 39;
				$ac6 = 77;
				//Encargado
				$cy7 = 50;
				//valor de y
				//
				//Vehiculo
				$cy8 = 56;
				//valor de y
				//
				//fecha
				$cy9 = 35;
				//valor de y
				//

				// Ojo: colocar para imprimir en hoja
				//Oficina
				$pdf -> SetXY($cx1, $cy1);

				$pdf -> SetFont($font, 'B', 10);
				//$tamAux = $this -> get_tam_font($pdf, 7, $font, $monto_t, 'B', $ancho);
				//$pdf -> SetFont($font, 'B', $tamAux);
				$pdf -> Cell($ac1, 4, $row -> oficina, 0, 0, 'L', 0);

				//telefono
				$pdf -> SetXY($cx1, $cy2);
				$pdf -> SetFont($font, 'B', 10);
				//$tamAux = $this -> get_tam_font($pdf, 7, $font, $monto_t, 'B', $ancho);
				//$pdf -> SetFont($font, 'B', $tamAux);
				$pdf -> Cell($ac1, 4, $row -> telefono, 0, 0, 'L', 0);

				//chofer
				$pdf -> SetXY($cx1, $cy3);
				$pdf -> SetFont($font, 'B', 10);
				//$tamAux = $this -> get_tam_font($pdf, 7, $font, $monto_t, 'B', $ancho);
				//$pdf -> SetFont($font, 'B', $tamAux);
				$pdf -> Cell($ac1, 4, $row -> chofer, 0, 0, 'L', 0);

				//placa
				$pdf -> SetXY($cx1, $cy4);
				$pdf -> SetFont($font, 'B', 10);
				//$tamAux = $this -> get_tam_font($pdf, 7, $font, $monto_t, 'B', $ancho);
				//$pdf -> SetFont($font, 'B', $tamAux);
				$pdf -> Cell($ac1, 4, $row -> placa, 0, 0, 'L', 0);

				//hora salida
				$pdf -> SetXY($cx1, $cy5);
				$pdf -> SetFont($font, 'B', 10);
				//$tamAux = $this -> get_tam_font($pdf, 7, $font, $monto_t, 'B', $ancho);
				//$pdf -> SetFont($font, 'B', $tamAux);
				$pdf -> Cell($ac1, 4, $row -> salida, 0, 0, 'L', 0);
				/**/
				// Destino
				$pdf -> SetXY($cx6, $cy6);
				$pdf -> SetFont($font, '', 7);
				//$tamAux = $this -> get_tam_font($pdf, 7, $font, $monto_t, 'B', $ancho);
				//$pdf -> SetFont($font, 'B', $tamAux);
				$pdf -> MultiCell($ac6, 0, $row -> destino, 0, 'L', 0,1);

				// Encargado
				$pdf -> SetXY($cx6, $cy7);
				$pdf -> SetFont($font, 'B', 10);
				//$tamAux = $this -> get_tam_font($pdf, 7, $font, $monto_t, 'B', $ancho);
				//$pdf -> SetFont($font, 'B', $tamAux);
				$pdf -> Cell($ac1, 4, $row -> encargado, 0, 0, 'L', 0);

				// Vehiculo
				$pdf -> SetXY($cx6, $cy8);
				$pdf -> SetFont($font, 'B', 8);
				//$tamAux = $this -> get_tam_font($pdf, 7, $font, $monto_t, 'B', $ancho);
				//$pdf -> SetFont($font, 'B', $tamAux);
				$pdf -> MultiCell($ac1, 0, $row -> vehiculo, 0, 'L', 0,1);

				// fecha
				$pdf -> SetXY($cx6, $cy9);
				$pdf -> SetFont($font, 'B', 10);
				//$tamAux = $this -> get_tam_font($pdf, 7, $font, $monto_t, 'B', $ancho);
				//$pdf -> SetFont($font, 'B', $tamAux);
				$pdf -> Cell($ac1, 4, $row -> fecha, 0, 0, 'L', 0);

				$xc = 12;
				$xs = 22;
				$xd = 75;
				$xp = 190;
				$yg = 83;
				$ancho = 115;
				$query2 = "SELECT t_lista_ordenentrega.serial as seri,t_lista_ordenentrega.descripcion as descrip,t_productos.precio_oficina,t_productos.venta FROM t_lista_ordenentrega 
							JOIN t_productos ON t_productos.serial = t_lista_ordenentrega.serial
							WHERE orden = '" . $orden . "'";
				$Consulta2 = $this -> db -> query($query2);
				$cant2 = $Consulta2 -> num_rows();
				if ($cant2 > 0) {
					$pdf -> setY($yg);
					$i = 0;
					foreach ($Consulta2 -> result() as $row2) {
						$i++;
						$pdf -> SetX($xc);
						$pdf -> SetFont($font, 'B', 10);
						$pdf -> Cell(10, 4, '1', 0, 0, 'L', 0);
						
						$pdf -> SetX($xs);
						$tamAux = $this -> get_tam_font($pdf, 10, $font, $row2 -> seri, 'B', 60);
						$pdf -> SetFont($font, 'B', $tamAux);
						$pdf -> Cell(60, 4, $row2 -> seri, 0, 0, 'L', 0);
						
						$pdf -> SetX($xp);
						$precio = $row2 -> precio_oficina;
						if($precio == 0 || $precio == ''){
							$precio = $row2 -> venta;
						}
						$tamAux = $this -> get_tam_font($pdf, 10, $font, $precio, 'B', 15);
						$pdf -> SetFont($font, 'B', $tamAux);
						$pdf -> Cell(15, 4, $precio, 0, 0, 'R', 0);
						
						$pdf -> SetX($xd);
						//$tamAux = $this -> get_tam_font($pdf, 10, $font, $row2 -> descripcion, 'B', $ancho);
						$pdf -> SetFont($font, 'B', 8);
						//$pdf -> Cell(115, 4, $row2 -> descripcion, 0, 0, 'L', 0);
						$pdf -> MultiCell(115, 0, $row2 -> descrip, 'B', 'L', 0,1);
						$aux = $pdf -> getY();
						$pdf -> line($xc,$aux,$xd,$aux);
						$pdf -> line($xp,$aux,$xp + 15,$aux);
						//$yg += 5;
					}
					
				}
				$pdf -> SetX($xc);
				$pdf -> SetFont($font, 'B', 12);
				$pdf -> Cell(10, 4, $i, 0, 0, 'L', 0);

				$pdf -> Output('orden_' . $orden . '.pdf', 'I');
			}

		} else {
			echo "No existe orden de entrega:" . $orden . $query;
		}

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