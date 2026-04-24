<?php
/**
 * YAHOO USER INTERFACE YUI
 *
 * @author Carlos Enrique Peña Albarrán
 * @package SaGem.system.application
 * @version 1.0.0
 */

class CYui extends Model {

	/* implementacion de Yui */

	/**
	 * URL: Direccion de ubicacion
	 * @var string
	 */
	public $yui_url;
	/**
	 * Tipo de componente
	 * @var string
	 */

	public $yui_tipo;


	/**
	 * Base ruta para YUI
	 * @param $strUrl
	 */
	public function setYui_Url($strUrl){
		$this->yui_url = $strUrl;
	}

	public function getYui_Url(){
		return $this->yui_url();
	}

	/**
	 * Base o tipo de componentes
	 * @param tabla|tab
	 */

	public function setYui_Tipo($strTipo){
		$this->yui_tipo = $strTipo;
	}

	/**
	 * Base o tipo de componentes
	 * @return string
	 */
	public function getYui_Tipo(){
		return $this->yui_tipo();
	}

	/* ---------------------------------------------------------------------------
	 *  Metodos de la clase
	 * ---------------------------------------------------------------------------
	 */

	/**
	 * Generando Css Necesarios para YAHOO
	 * @return  string Header Css HTML
	 */
	public function getYui_Css(){
		/* Componetes necesarios */
		$yui_param = "";
		switch ($this->yui_tipo){
			case "tabla":
				/* Datatable*/
				$yui_param = '
			      <link rel="stylesheet" type="text/css" href="' . $this->yui_url . '/build/paginator/assets/skins/sam/paginator-skin.css" />			   
			      <link rel="stylesheet" type="text/css" href="' . $this->yui_url . '/build/datatable/assets/skins/sam/datatable.css" />';		
				break;
					
			case "tab":
				/* Tabview */
				$yui_param = '
					<link rel="stylesheet" type="text/css" href="' . $this->yui_url . '/build/tabview/assets/skins/sam/tabview.css" />
					<link rel="stylesheet" type="text/css" href="' . $this->yui_url . '/build/datatable/assets/skins/sam/datatable.css" />
					';
				break;
					
		}

		/* Objetos comunes para ambos */
		//		$yui = '<link rel="stylesheet" type="text/css" href="' . $this->yui_url . '/build/fonts/fonts-min.css" />
		//		<link rel="stylesheet" type="text/css" href="' . $this->yui_url . '/build/button/assets/skins/sam/button.css" />
		//		<link rel="stylesheet" type="text/css" href="' . $this->yui_url . '/build/container/assets/skins/sam/container.css" />';
		//		$yui .=$yui_param;
		$yui = '<link rel="stylesheet" type="text/css" href="' . $this->yui_url . '/build/fonts/fonts-min.css" />
		<link rel="stylesheet" type="text/css" href="' . $this->yui_url . '/build/button/assets/skins/sam/button.css" />
		<link rel="stylesheet" type="text/css" href="' . $this->yui_url . '/build/container/assets/skins/sam/container.css" />
		<link rel="stylesheet" type="text/css" href="' . $this->yui_url . '/build/paginator/assets/skins/sam/paginator-skin.css" />			   
		<link rel="stylesheet" type="text/css" href="' . $this->yui_url . '/build/datatable/assets/skins/sam/datatable.css" />
		<link rel="stylesheet" type="text/css" href="' . $this->yui_url . '/build/tabview/assets/skins/sam/tabview.css" />
		<link rel="stylesheet" type="text/css" href="' . $this->yui_url . '/build/datatable/assets/skins/sam/datatable.css" />	      
		';

			
		return $yui;

	}

	/**
	 * Generando Js Necesarios
	 * @return string Header JavaScripts HTML
	 */
	public function getYui_Js(){
		$yui_param = "";
		switch ($this->yui_tipo){
			case "tabla":
				/* Datatable */
				//<script type="text/javascript" src="' . $this->yui_url . '/build/dom/dom-min.js"></script>
				$yui_param = '
			      <script type="text/javascript" src="' . $this->yui_url . '/build/paginator/paginator-min.js"></script>			  
			      <script type="text/javascript" src="' . $this->yui_url . '/build/datasource/datasource-min.js"></script>
			      <script type="text/javascript" src="' . $this->yui_url . '/build/datatable/datatable-min.js"></script>';
				break;
					
			case "tab":
				/* Tabview */
				$yui_param = '
						<script type="text/javascript" src="' . $this->yui_url . '/build/tabview/tabview-min.js"></script>
						<script type="text/javascript" src="' . $this->yui_url . '/build/datasource/datasource-min.js"></script>
			      <script type="text/javascript" src="' . $this->yui_url . '/build/datatable/datatable-min.js"></script>';
				break;
		}
			
		/* Componentes comunes para ambos */
			
		//		$yui = '
		//			<script type="text/javascript" src="' . $this->yui_url . '/build/yahoo-dom-event/yahoo-dom-event.js"></script>
		//			<script type="text/javascript" src="' . $this->yui_url . '/build/connection/connection-min.js"></script>
		//			<script type="text/javascript" src="' . $this->yui_url . '/build/element/element-min.js"></script>
		//
		//			<script type="text/javascript" src="' . $this->yui_url . '/build/button/button-min.js"></script>
		//			<script type="text/javascript" src="' . $this->yui_url . '/build/dragdrop/dragdrop-min.js"></script>
		//			<script type="text/javascript" src="' . $this->yui_url . '/build/container/container-min.js"></script>
		//			';
		//
		//		$yui .= $yui_param;

		$yui = '
			<script type="text/javascript" src="' . $this->yui_url . '/build/yahoo-dom-event/yahoo-dom-event.js"></script>
			<script type="text/javascript" src="' . $this->yui_url . '/build/connection/connection-min.js"></script>						
			<script type="text/javascript" src="' . $this->yui_url . '/build/element/element-min.js"></script>
			
			<script type="text/javascript" src="' . $this->yui_url . '/build/button/button-min.js"></script>
			<script type="text/javascript" src="' . $this->yui_url . '/build/dragdrop/dragdrop-min.js"></script>
			<script type="text/javascript" src="' . $this->yui_url . '/build/container/container-min.js"></script>
			
			<script type="text/javascript" src="' . $this->yui_url . '/build/paginator/paginator-min.js"></script>			  
			<script type="text/javascript" src="' . $this->yui_url . '/build/tabview/tabview-min.js"></script>
			<script type="text/javascript" src="' . $this->yui_url . '/build/datasource/datasource-min.js"></script>
			<script type="text/javascript" src="' . $this->yui_url . '/build/datatable/datatable-min.js"></script>			
			';

		return $yui;
			
	}

	/**
	 * Modelo de CodeIgniter
	 * Constructor
	 *
	 */
	public function __construct(){
		parent::__construct();
	}

}

?>