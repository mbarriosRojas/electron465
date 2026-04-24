<?php

/**
 * CAPTCHA Prueba desafio-respuesta utilizada en computacion para determinar \n
 * cuando el usuario es o no humano
 *
 * @author Carlos Enrique Penaa Albarran
 * @package SaGem.system.application.model
 * @version 1.0.0
 */

class CImage extends Model {

	/**
	 * Imagen Captcha
	 *
	 * @var string
	 */
	public $Cap_Img;

	function __construct(){
		//
		parent::__construct();
	}

	/**
	 * Texto Aleatorio
	 *
	 * @param $intLongitud | Cantidad de Caracteres a Mostrar
	 * @return string
	 */
	private function Texto_Aleatorio($intLongitud){
		$strCadena = "";
		$strCaracteres = "1234567890abcdefghijklmnopqrstuvwxyz";
		for($i = 0; $i < $intLongitud; $i++) {
			$strCadena .= $strCaracteres{rand(0,35)};
		}
		return $strCadena;
	}

	/**
	 * Generar Imagen Aleatoria
	 * 
	 * @return image
	 */
	public function Generar_Captcha(){
		
		$strTexto = $this->Texto_Aleatorio(6);
		$captcha = imagecreatefrompng(__LOCALWWW__ . "/system/img/captcha.png");
		/*
		 * Configuramos los colores usados para generan las lineas (formato RGB)
		 */
		$color = imagecolorallocate($captcha, 153, 102, 51);
		$linea = imagecolorallocate($captcha, 233, 239, 239);
		/*
		 * Añadimos algunas lineas a nuestra imagen para dificultar la tarea a los robots
		 */
		imageline($captcha,0,0,39,29,$linea);
		imageline($captcha,40,0,64,29,$linea);
		
		imagestring($captcha, 5, 20, 2, $strTexto, $color);

		return $captcha;

	}

	function __destruct(){
		//
	}


}

?>