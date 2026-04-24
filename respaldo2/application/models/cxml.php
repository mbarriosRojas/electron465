<?php
/*
 *  @author Carlos Enrique Peña Albarrán
 *  @package SaGem.system.application
 *  @version 1.0.0
 */

class CXml extends Model {


	public $data_xml = array();



	public function Cliente($documento_id = null, $objPersona = null){

		if($documento_id == ""){
			$documento_id = 0;
		}
		$Consulta = $this->db->query("SELECT * FROM t_personas WHERE documento_id=$documento_id LIMIT 1");
		if ($Consulta->num_rows() == 0){
			echo "<?xml version='1.0' encoding='ISO-8859-1' standalone='yes'?>\n";
			echo "<lista_clientes>";
			echo "<cliente>";

			foreach(get_object_vars($objPersona) as $NombreCampo=>$ValorCampo){
				if ($NombreCampo != "_parent_name"	&& $NombreCampo != "_ci_scaffolding"	 && $NombreCampo != "_ci_scaff_table"	&& $NombreCampo != "input"){
					echo "<" . $NombreCampo . ">NULL</" . $NombreCampo . ">";
				}
			}
			echo "</cliente>";
			echo "</lista_clientes>";
		}else{
			echo "<?xml version='1.0' encoding='ISO-8859-1' standalone='yes'?>";
			echo "<lista_clientes>";
			echo "<cliente>";

			foreach ($Consulta->result() as $row)
			{
				$arreglo = get_object_vars($row);

				foreach(get_object_vars($row) as $NombreCampo=>$ValorCampo){
					if (substr($NombreCampo, 1,1) != "_"	){
						if($ValorCampo == ""){
							$ValorCampo_aux = " ";
						}else{
							$ValorCampo_aux = $ValorCampo;
						}
						echo "<" . $NombreCampo . ">" . utf8_decode($ValorCampo_aux) . "</" . $NombreCampo . ">";
					}
				}
			}



			echo "</cliente>";
			echo "</lista_clientes>";
		}
	}

	public function Credito($documento_id = "", $contrato_id = "", $objCredito = null){
		echo "<?xml version='1.0' encoding='ISO-8859-1' standalone='yes'?>\n";
		echo "<lista_creditos>";
		echo "<credito>";

		$Consulta = $this->db->query("SELECT * FROM t_clientes_creditos WHERE contrato_id='$contrato_id' LIMIT 1");
		if ($Consulta->num_rows() > 0){
			foreach ($Consulta->result() as $row)
			{
				if($row->documento_id != $documento_id){
					foreach(get_object_vars($objCredito) as $NombreCampo=>$ValorCampo){
						echo "<" . $NombreCampo . ">1</" . $NombreCampo . ">";
					}
				}else{ // En caso de que exista el credito y sea del mismo cliente
					foreach(get_object_vars($row) as $NombreCampo=>$ValorCampo){
						if($ValorCampo == ""){
							$ValorCampo_aux = " ";
						}else{
							$ValorCampo_aux = $ValorCampo;
						}
						echo "<" . $NombreCampo . ">" . utf8_decode($ValorCampo_aux) . "</" . $NombreCampo . ">";
					}
				}
			}
		}else{ //No existe el Contrato
			foreach(get_object_vars($objCredito) as $NombreCampo=>$ValorCampo){
				echo "<" . $NombreCampo . ">0</" . $NombreCampo . ">";
			}
		}
		echo "</credito>";
		echo "</lista_creditos>";
	}
	
	public function jsCliente($id = null, $objPersona = null){
			
		$jsP = array(); //Una Persona Json
		

		$Consulta = $this->db->query("SELECT * FROM t_personas WHERE documento_id=$id LIMIT 1");
		if ($Consulta->num_rows() == 0){
			foreach(get_object_vars($objPersona) as $NombreCampo=>$ValorCampo){
				if ($NombreCampo != "_parent_name"	&& $NombreCampo != "_ci_scaffolding"	 && $NombreCampo != "_ci_scaff_table"	&& $NombreCampo != "input"){
					$jsP[$NombreCampo] ="";
				}
			}
		}else{
			foreach ($Consulta->result() as $row){
				$arreglo = get_object_vars($row);
				foreach(get_object_vars($row) as $NombreCampo=>$ValorCampo){
					if (substr($NombreCampo, 1,1) != "_"	){
						if($ValorCampo == ""){
							$ValorCampo_aux = " ";
						}else{
							$ValorCampo_aux = $ValorCampo;
						}
						$jsP[$NombreCampo] = utf8_decode($ValorCampo_aux);
					}
				}
			}
		

		}
		return json_encode($jsP);
		
	}
	
	public function jsCredito($documento_id = "", $contrato_id = "", $objCredito = null){
		$jsC = array();
		$Consulta = $this->db->query("SELECT * FROM t_clientes_creditos WHERE contrato_id='$contrato_id' LIMIT 1");
		if ($Consulta->num_rows() > 0){
			foreach ($Consulta->result() as $row)
			{
				if($row->documento_id != $documento_id){
					foreach(get_object_vars($objCredito) as $NombreCampo=>$ValorCampo){
						$jsC[$NombreCampo] = "";
					}
				}else{ // En caso de que exista el credito y sea del mismo cliente
					foreach(get_object_vars($row) as $NombreCampo=>$ValorCampo){
						if($ValorCampo == ""){
							$ValorCampo_aux = " ";
						}else{
							$ValorCampo_aux = $ValorCampo;
						}
						$jsC[$NombreCampo] = utf8_decode($ValorCampo_aux);
					}
				}
			}
		}else{ //No existe el Contrato
			foreach(get_object_vars($objCredito) as $NombreCampo=>$ValorCampo){
				$jsC[$NombreCampo] = 0;
			}
		}	
		return json_encode($jsC);	
	}

}

?>