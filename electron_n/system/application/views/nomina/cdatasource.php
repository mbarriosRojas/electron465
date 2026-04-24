<?php
	/*
	*  @author Carlos Enrique Peña Albarrán
	*  @package SaGem.system.application
	*  @version 1.0.0
	*/
	
	class CDataSource extends Model {
		
		
		
		
		public function data_empleado($obj){
			$data = "SaGem.Empleado = {";
				foreach($obj as $empleado){
					$data .= "";
				}
				
			
			$data .= "}";
		}
		
		
		public function __construct(){
			parent::__construct();
		}	
		
	}

?>