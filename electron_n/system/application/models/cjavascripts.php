<?php
/**
 *  @author Carlos Enrique Peña Albarrán
 *  @package SaGem.system.application.model
 *  @version 1.0.0
 */

class CJavascripts extends Model{

	/**
	 * Contenido Javascript
	 * @var string | array JavaScripts
	 */
	public $contenido;



	public function __Construct(){
		parent::__Construct();
	}




	/**
	 *
	 * @return DataGrid YUI();
	 */
	public function DataGrid_Datos($cedula = null){
		
		if($cedula == ""){
			$Js_Data = "var OCliente = function(){";
			$Js_Data .= "this.direccionh = 'UFFF';";
			$Js_Data .= "}";
		}else{
			$Js_Data = "var OCliente = function(){";
			$Js_Data .= "this.direccionh = 'EL MORAL SECTOR LA LAGUNITA';";
			$Js_Data .= "}";
		}
		return $Js_Data;
	}


	/**
	 * Returna el tipo de nomina
	 *
	 * @param $codigo
	 * @return string
	 */
	public function Tipo_Nomina($codigo = ""){
		$tiponomina = "";
		switch (trim($codigo)) {
			case "JO":
				$tiponomina = "JUB/PENS SINDICATO DE LA CONSTRUCCION";
				break;
			case "JM":
				$tiponomina = "JUBILADO Y PENSIONADOS MAESTRO";
				break;
			case "JU":
				$tiponomina = "JUBILADO Y PENSIONADOS SUODE";
				break;
			case "JP":
				$tiponomina = "JUBILADO Y PENSIONADOS POLICIA";
				break;
			case "JA":
				$tiponomina = "JUBILADO Y PENSIONADOS OBREROS";
				break;
			case "JB":
				$tiponomina = "JUBILADO Y PENSIONADOS BOMBEROS";
				break;
			case "PS":
				$tiponomina = "PENSIONADOS SOBREVIVIENTE";
				break;
			case "PE":
				$tiponomina = "PENSIONADOS ESPICIALES";
				break;
			case "PG":
				$tiponomina = "PENSIONADOS DE GRACIA";
				break;
			case "JI":
				$tiponomina = "JUBILADO Y PENSIONADOS IMPRENTA";
				break;
			case "JE":
				$tiponomina = "JUBILADO Y PENSIONADOS EMPLEADOS";
				break;
			case "JH":
				$tiponomina = "JUB/PEN HOSPITALES Y CLINICAS";
				break;
			case "EM":
				$tiponomina = "EMPLEADO FIJO";
				break;
			case "EC":
				$tiponomina = "EMPLEADO FIJO";
				break;
			case "EF":
				$tiponomina = "EMPLEADO FIJO";
				break;
			case "BO":
				$tiponomina = "BOMBERO";
				break;
			case "PO":
				$tiponomina = "POLICIA";
				break;
			case "CO":
				$tiponomina = "PERSONAL CONTRATADO";
				break;
			case "PC":
				$tiponomina = "PERSONAL CONTRATADO";
				break;
			case "MA":
				$tiponomina = "MAESTROS AUXILIARES";
				break;
			case "OS":
				$tiponomina = "OBRERO FIJO";
				break;
			case "BE":
				$tiponomina = "BEDELES CONTRATADOS";
				break;
			case "DE":
				$tiponomina = "DOCENTE DEL EJECUTIVO";
				break;
			default:
				break;
		}
		return $tiponomina;
			
	}

	/**
	 * DataGrid YUI
	 * Deficion de Columnas Campos
	 *
	 * @return string | Javascript Array
	 */
	public function DataGrid_Col_Def(){
		$Col_Def = "
		SaGem.definicion = [ {
			key : 'codigo',
			label : 'C&oacute;digo',
			sortable : true
		}, {
			key : 'cedula',
			label : 'C&eacute;dula',
			sortable : true
		}, {
			key : 'nombre',
			label : 'Primer nombre',
			width : 100,
			sortable : true
		}, {
			key : 'nombre2',
			label : 'Segundo nombre',
			width : 100,
			sortable : true
		}, {
			key : 'apellido',
			width : 100,
			label : 'Primer apellido',
			sortable : true
		}, {
			key : 'apellido2',
			width : 100,
			label : 'Segundo apellido',
			sortable : true
		}, {
			key : 'fechana',
			width : 100,
			label : 'Fecha Nacimiento',
			hidden : true
		},{
			key : 'edocivil',
			label : 'Estado Civil',
			sortable : true,
			hidden : true
		}, {
			key : 'cuenta',
			label : 'Cuenta',
			sortable : true,
			hidden : true
		}, {
			key : 'direccion',
			label : 'Direccion',
			sortable : true,
			hidden : true
		}, {
			key : 'telefono',
			label : 'Telefono',
			sortable : true,
			hidden : true
		}, {
			key : 'ciudad',
			label : 'Ciudad',
			sortable : true,
			hidden : true
		}, {
			key : 'tiponomina',
			label : 'Tipo Nomina',
			sortable : true,
			hidden : true
		}, {
			key : 'destiponomina',
			label : 'Des Nomina',
			sortable : true,
			hidden : true
		}, {
			key : 'nivelacademico',
			label : 'Nivel Academico',
			sortable : true,
			hidden : true
		}, {
			key : 'banco',
			label : 'Banco',
			sortable : true,
			hidden : true
		}, {
			key : 'sexo',
			label : 'Sexo',
			sortable : false
		}, {
			key : 'dia',
			label : 'Dia',
			hidden : true
		}, {
			key : 'mes',
			label : 'Mes',
			hidden : true
		}, {
			key : 'ano',
			label : 'A&ntilde;o',
			hidden : true
		},{
			key : 'diaing',
			label : 'Dia',
			hidden : true
		}, {
			key : 'mesing',
			label : 'Mes',
			hidden : true
		}, {
			key : 'anoing',
			label : 'A&ntilde;o',
			hidden : true
		}, {
			key : 'nacionalidad',
			label : 'Nacionalidad',
			hidden : true
		}, {
			key : 'cargo',
			label : 'Cargo',
			sortable : true,
			hidden : false
	
		} ];";

		return $Col_Def;
	}
	/**
	 * DataGrid YUI
	 * Esquema de Columnas
	 *
	 * @return string | JavaScript Array
	 */
	public function DataGrid_Col_Esq(){


		return $Col_Esq;
	}

	/**
	 *
	 * @return Tab YUI
	 */
	public function Tabs(){

		return $data;
	}

}

?>