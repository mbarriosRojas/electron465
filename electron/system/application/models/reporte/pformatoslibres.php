<?php

/*
 *
 */

class PFormatosLibres extends Model {

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

	function Formato_Libre($sformato, $sCedula = null, $emp) {
		switch ($sformato) {
			case 'SOFITASA' :
				$this -> f_sofitasa($sCedula);
			case 'GOBERNACION' :
				$this -> f_autoriza_gob($sCedula);
				break;
			case 'CREDINFO' :
				$this -> f_autoriza_credinfo($sCedula);
			case 'ULA' :
				$this -> f_autoriza_ula($sCedula);
			case 'VENEZUELA' :
				$this -> f_venezuela($sCedula, $emp);
				//G=grupo , C=cooperativa
				break;
			case 'UNIVERSAL' :
				$this -> f_autoriza_universal($sCedula,$emp);
				//G=grupo , C=cooperativa
				break;
			case 'INTERBANCARIO' :
				$this -> f_autoriza_universal($sCedula);
				//G=grupo , C=cooperativa
				break;
			case 'DOMI' :
				$this -> domi($sCedula);
				//G=grupo , C=cooperativa
				break;
			case 'CONVE' :
				$this -> conve($sCedula);
				//G=grupo , C=cooperativa
				break;
			case 'CONTRA' :
				$this -> contra($sCedula);
				//G=grupo , C=cooperativa
				break;
			default :
				$this -> f_autoriza_universal($sCedula);
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

	public function f_autoriza_gob($cedula) {
		$pagina1 = array();
		$nombre='';
		if($cedula != ''){
			$query = "SELECT CONCAT(primer_nombre,' ',segundo_nombre,' ',primer_apellido,' ',segundo_apellido)AS nombre  FROM t_personas  WHERE documento_id='$cedula'";
			$Consulta = $this -> db -> query($query);
			foreach ($Consulta->result() as $row) {
				$nombre = $row -> nombre;
			}
		}


		$img = '';

		$absisas = NULL;
		$ordenadas = NULL;


		$absisas[2] = 18;//yo
		$absisas[3] = 45;//cedula
		$absisas[21] = 53;//dia
		$absisas[22] = 117;//mes
		$absisas[23] = 175;//ano

		$ordenadas[2] = 82;//yo
		$ordenadas[3] = 89;//cedula, com_de
		$ordenadas[17] = 219;//lugar,dia,mes,ano
		$img = 'gobernacion.jpg';


		$pagina1['yo'] = array('texto' => $nombre, 'ancho' => 165, 'x' => $absisas[2], 'y' => $ordenadas[2], 'estilo' => '');
		$pagina1['cedula'] = array('texto' => $cedula, 'ancho' => 43, 'x' => $absisas[3], 'y' => $ordenadas[3], 'estilo' => '');

		$fecha_actual = date('d-m-Y');
		$fecha_actual = explode('-', $fecha_actual);
		$mes_L = $this -> mes_letras($fecha_actual[1]);
		if($cedula != ''){
			$pagina1['dia'] = array('texto' => strtoupper($this -> ValorEnLetras($fecha_actual[0], '')), 'ancho' => 40, 'x' => $absisas[21], 'y' => $ordenadas[17], 'estilo' => 'B');
			$pagina1['mes'] = array('texto' => $mes_L, 'ancho' => 52, 'x' => $absisas[22], 'y' => $ordenadas[17], 'estilo' => 'B');
			$pagina1['ano'] = array('texto' => $fecha_actual[2], 'ancho' => 25, 'x' => $absisas[23], 'y' => $ordenadas[17], 'estilo' => 'B');
		}

		$img = 'gobernacion.jpg';
		$elemento = array($pagina1);
		$this -> generar_formato($elemento, 'Gobernacion', 10, $img);
	}

	public function f_autoriza_universal($ced , $e) {
		$pagina1 = array();
		$pagina2 = array();
		$Consulta = $this -> db -> query("SELECT * FROM t_personas WHERE documento_id='$ced'");
		$img = '';
		$img2 = '';
		$y = 0;
		if ($e == 'C') {
			$img = 'universalcf.jpg';
			$img2 = 'universalct.jpg';
		} else {
			$img = 'universalgf.jpg';
			$img2 = 'universalgt.jpg';
		}
		if ($Consulta -> num_rows() > 0) {
			foreach ($Consulta->result() as $row) {
				$nombre_c = $row -> primer_apellido . ' ' . $row -> segundo_apellido . ' ' . $row -> primer_nombre . ' ' . $row -> segundo_nombre;
				if ($e == 'C') {
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
				}

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


				if ($e == 'C') {
					$y = 232;
				} else {
					$y = 229;
				}

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

			}


		}

		$elemento = array($pagina1, $pagina2);
		$this -> generar_formato($elemento, 'universal', 10, $img, $img2);
	}

	/*maURICIO*/
	public function conve($ced=null ,  $e=null) {
		$pagina1 = array();
		$pagina2 = array();
		$Consulta = $this -> db -> query("SELECT * FROM t_personas WHERE documento_id='$ced'");
		$img = 'conveniopago.jpg';
		$img2 = 'conveniopago.jpg';
		$y = 0;

		/*if ($Consulta -> num_rows() > 0) {
			foreach ($Consulta->result() as $row) {
				$nombre_c = $row -> primer_apellido . ' ' . $row -> segundo_apellido . ' ' . $row -> primer_nombre . ' ' . $row -> segundo_nombre;
				if ($e == 'C') {
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
				}

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


				if ($e == 'C') {
					$y = 232;
				} else {
					$y = 229;
				}

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

			}


		}*/

		$elemento = array($pagina1);
		$this -> generar_formato($elemento, 'conve', 10, $img, $img2);
	}

	public function contra($ced=null ,  $e=null) {
		$pagina1 = array();
		$pagina2 = array();
		$Consulta = $this -> db -> query("SELECT * FROM t_personas WHERE documento_id='$ced'");
		$img = 'contrato.jpeg';
		$img2 = 'contratodos.jpeg';
		$y = 0;

		/*if ($Consulta -> num_rows() > 0) {
			foreach ($Consulta->result() as $row) {
				$nombre_c = $row -> primer_apellido . ' ' . $row -> segundo_apellido . ' ' . $row -> primer_nombre . ' ' . $row -> segundo_nombre;
				if ($e == 'C') {
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
				}

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


				if ($e == 'C') {
					$y = 232;
				} else {
					$y = 229;
				}

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

			}


		}*/

		$elemento = array($pagina1,$pagina2);
		$this -> generar_formato($elemento, 'conve', 10, $img, $img2);
	}


	public function domi($ced , $e=null) {
		$pagina1 = array();
		
		$Consulta = $this -> db -> query("SELECT * FROM t_personas WHERE documento_id='$ced'");
		$img = '';
		$img2 = '';
		$y = 0;
		if ($e == 'C') {
			$img = 'domi.jpg';
		} else {
			$img = 'domi.jpg';
		}
		if ($Consulta -> num_rows() > 0) {
			foreach ($Consulta->result() as $row) {
				$nombre_c = $row -> primer_apellido . ' ' . $row -> segundo_apellido . ' ' . $row -> primer_nombre . ' ' . $row -> segundo_nombre;
				if ($e == 'C') {
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
				}

				//nombre
				$pagina1['nombre'] = array('texto' => $nombre_c, 'ancho' => 148, 'x' => 17, 'y' =>90, 'estilo' => '');
				//nombre
				$pagina1['cedula'] = array('texto' => $row -> documento_id, 'ancho' => 60, 'x' => 66, 'y' => 96 + $y1, 'estilo' => '');
				//nacionalidad
				//$nacion = 'E-';
				if ($row -> nacionalidad == 'E-') {
					//if ($nacion== 'E-') {
					$x = 57;
				} else {
					$x = 47;
				}
				//sector
				$pagina1['cuenta_1'] = array('texto' => $row -> cuenta_1, 'ancho' => 25, 'x' => 60, 'y' => 103 + $y1, 'estilo' => '');
				//calle
				$pagina1['banco_1'] = array('texto' => $row -> banco_1, 'ancho' => 63, 'x' => 60, 'y' => 108 + $y2, 'estilo' => '');

				$pagina1['banco_2'] = array('texto' => $row -> banco_1, 'ancho' => 63, 'x' => 62, 'y' => 122 + $y2, 'estilo' => '');

				$pagina1['banco_3'] = array('texto' => $row -> banco_1, 'ancho' => 63, 'x' => 65, 'y' => 130 + $y2, 'estilo' => '');
				$pagina1['banco_4'] = array('texto' => $row -> banco_1, 'ancho' => 63, 'x' => 65, 'y' => 136 + $y2, 'estilo' => '');
				$pagina1['banco_5'] = array('texto' => $row -> banco_1, 'ancho' => 63, 'x' => 100, 'y' => 222 + $y2, 'estilo' => '');
				

//avenida
				//edif.
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

				//urb.
				//estado
				//municipio
				//zona

				//telf.
				//cel
				//email
				//pin
				//persona


				if ($e == 'C') {
					$y = 232;
				} else {
					$y = 229;
				}

				//a los
				$fecha_actual = date('d-m-Y');
				$fecha_actual = explode('-', $fecha_actual);
				$mes_L = $this -> mes_letras($fecha_actual[1]);
				$pagina1['lugar'] = array('texto' => 'Merida', 'ancho' => 50, 'x' => 95, 'y' => 81, 'estilo' => 'B');

				$pagina1['dia'] = array('texto' => $fecha_actual[0], 'ancho' => 50, 'x' => 125, 'y' => 81, 'estilo' => 'B');
				//mes
				$pagina1['mes'] = array('texto' => $mes_L, 'ancho' => 50, 'x' => 140, 'y' => 81, 'estilo' => 'B');
				//en
				$pagina1['ano'] = array('texto' => $fecha_actual[2], 'ancho' => 12, 'x' => 180, 'y' => 81, 'estilo' => 'B');

			}


		}

		$elemento = array($pagina1);
		$this -> generar_formato($elemento, 'domi', 10, $img, $img2);
	}
	public function f_venezuela($ced, $e = 'C') {
		$pagina1 = array();
		$Consulta = $this -> db -> query("SELECT * FROM t_personas WHERE documento_id='$ced'");
		$img = '';
		if ($e == 'C') $img = 'venezuelac.jpg';
		else $img = 'venezuelag.jpg';
		if ($Consulta -> num_rows() > 0) {
			foreach ($Consulta->result() as $row) {
				$nombre_c = $row -> primer_apellido . ' ' . $row -> segundo_apellido . ' ' . $row -> primer_nombre . ' ' . $row -> segundo_nombre;
				if ($e == 'C') {
					$yt = 70;
					$y1 = 82;
					$y2 = 92;
					$y3 = 100;
					$y4 = 107;
					$y5 = 144;
					$y6 = 152;
				} else {
					$yt = 64;
					$y1 = 79;
					$y2 = 90;
					$y3 = 97;
					$y4 = 104;
					$y5 = 142;
					$y6 = 149;
				}
				
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
			}
		}
		$elemento = array($pagina1);
		$this -> generar_formato($elemento, 'Venezuela', 10, $img);
	}

	public function f_autoriza_ula($ced) {
		$concepto = "FINANCIAMIENTO DE LINEA BLANCA";
		$pagina1 = array();
		$Consulta = $this -> db -> query("SELECT * FROM t_personas WHERE documento_id='$ced'");
		$img = 'ula.jpg';
		if ($Consulta -> num_rows() > 0) {
			foreach ($Consulta->result() as $row) {
				$nombre_c = $row -> primer_apellido . ' ' . $row -> segundo_apellido . ' ' . $row -> primer_nombre . ' ' . $row -> segundo_nombre;
				
				//Cedula
				$pagina1['cedula'] = array('texto' => $row -> documento_id, 'ancho' => 68, 'x' => 42, 'y' => 74, 'estilo' => '');
				//Nombre
				$pagina1['nombre'] = array('texto' => $nombre_c, 'ancho' => 145, 'x' => 23, 'y' => 64, 'estilo' => '');
				//nomina
				$pagina1['nomina'] = array('texto' => $row -> direccion_trabajo, 'ancho' => 152, 'x' => 45, 'y' => 83, 'estilo' => '');
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

			}
		}
		$elemento = array($pagina1);
		$this -> generar_formato($elemento, 'ula', 12, $img);
	}

	public function f_autoriza_credinfo($ced) {
		$pagina1 = array();
		$Consulta = $this -> db -> query("SELECT * FROM t_personas WHERE documento_id='$ced'");
		$img = 'credinfo.jpg';
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
				//lugar y fecha
				$fecha = date('d-m-Y');
				$fecha = explode('-', $fecha);
				$lugar =  $fecha[0] . '  DE  ' . $this -> mes_letras($fecha[1]) . '  DEL  ' . $fecha[2];
				$pagina1['lugar_fe'] = array('texto' => $lugar, 'ancho' => 90, 'x' => 53, 'y' => 211, 'estilo' => 'B');
			}
		}
		$elemento = array($pagina1);
		$this -> generar_formato($elemento, 'CREDINFO', 10, $img);
	}

	public function f_sofitasa($ced) {
		$pagina1 = array();
		$pagina2 = array();
		$Consulta = $this -> db -> query("SELECT * FROM t_personas WHERE documento_id='$ced'");
		$img = 'sofitasa.jpg';
		$img2 = 'sofitasa2.jpg';
		if ($Consulta -> num_rows() > 0) {
			foreach ($Consulta->result() as $row) {
				$nombre_c = $row -> primer_apellido . ' ' . $row -> segundo_apellido . ' ' . $row -> primer_nombre . ' ' . $row -> segundo_nombre;
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
				$pagina1['domicilia'] = array('texto' => 'X', 'ancho' => 6, 'x' => 159, 'y' => 41, 'estilo' => 'B');
				//convenii
				$pagina1['convenio'] = array('texto' => 'X', 'ancho' => 6, 'x' => 81, 'y' => 145, 'estilo' => 'B');
				//fin de la primera pagina
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
			}

			
		}
		$elemento = array($pagina1, $pagina2);
		$this -> generar_formato($elemento, 'SOFITASA', 10, $img, $img2);
	}

	public function presupuesto($factura, $e = 'C', $forma = 'P') {
		$pagina1 = array();

		//$Consulta = $this -> db -> query("SELECT * FROM t_personas JOIN t_clientes_creditos ON t_personas.documento_id = t_clientes_creditos.documento_id WHERE t_clientes_creditos.contrato_id='$contrato'");

		if ($Consulta -> num_rows() > 0) {
			foreach ($Consulta->result() as $row) {
			}
		}
		if ($forma != 'P') {

		} else {
			if ($e == 'C') {
				$y = 2;
			} else {
				$y = 0;
			}
			//en
			$pagina1['lugar'] = array('texto' => 'pos1', 'ancho' => 30, 'x' => 10, 'y' => 47 + $y, 'estilo' => 'B');

			//a los
			$fecha_actual = date('d-m-Y');
			$fecha_actual = explode('-', $fecha_actual);
			$mes_L = $this -> mes_letras($fecha_actual[1]);

			//dia
			$pagina2['dia'] = array('texto' => $fecha_actual[0], 'ancho' => 10, 'x' => 40, 'y' => 47 + $y, 'estilo' => 'B');
			//mes
			$pagina2['mes'] = array('texto' => $fecha_actual[1], 'ancho' => 10, 'x' => 51, 'y' => 47 + $y, 'estilo' => 'B');
			//en
			$pagina2['ano'] = array('texto' => $fecha_actual[2], 'ancho' => 15, 'x' => 62, 'y' => 47 + $y, 'estilo' => 'B');
			//Nombre
			$pagina1['nombre'] = array('texto' => 'NOMBRE', 'ancho' => 145, 'x' => 55, 'y' => 56 + $y, 'estilo' => '');
			//direccion
			$concepto = "aca va la cadena a escribor la direciopn de la persona que hjace el contrat. esto sirve para saber largo de carateres ";
			$pagina1['direcion'] = array('texto' => $concepto, 'ancho' => 190, 'x' => 11, 'y' => 64 + $y, 'estilo' => '', 'multi' => 2, 'alto' => 6);
			//Cedula
			$pagina1['cedula'] = array('texto' => 'V-17456121', 'ancho' => 45, 'x' => 82, 'y' => 82 + $y, 'estilo' => '');
			//telefono
			$pagina1['telf'] = array('texto' => '0274-2215686', 'ancho' => 50, 'x' => 146, 'y' => 82 + $y, 'estilo' => '');

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
