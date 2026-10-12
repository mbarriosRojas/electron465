<?php

/**
 *  @author Judelvis Antonio Rivas Perdomo
 *  @package SaGem.system.application
 *  @version 1.0.0
 */

class CSugerencia extends Model {

	/**
	 * @var integer
	 */
	var $id = NULL;

	/**
	 * @var string
	 */
	var $nombre;

	/**
	 * @var string
	 */
	var $descripcion;

	/**
	 * @var integer
	 */
	var $estado;

	/**
	 * @var integer
	 */
	var $prioridad;

	/**
	 * @var string
	 */
	var $de_usuario;

	/**
	 * @var string
	 */
	var $para_usuario;

	/**
	 * @var integer
	 */
	var $nivel_usuario;

	public function __construct() {
		parent::__construct();
	}

	public function listar($strUsuario = '', $intNivel) {
		$strReporte = "<ul id=\"icons\" class=\"ui-widget ui-helper-clearfix\"><table style=\"height:18px;width:750px;\" border=0
		class=\"ui-widget ui-widget-content\" cellspacing=\"2\" cellpadding=\"0\">
		<thead><tr class=\"ui-widget-header\" style=\"height:20px;\"><th>A</th>
		<th>DE</th><th>PARA</th><th>TEMA</th><th>SUGERENCIA</th><th>ESTADO</th><th>PRIORIDAD</th>
		</tr></thead><tbody>";
		$this -> db -> order_by('id', 'desc');
		if ($intNivel == 0) {
			$this -> db -> where("estado !=", 3 );
			$rs = $this -> db -> get('t_sugerencias');

			$color = '#efe78e';
		} else {
			$where = "de_usuario = '$strUsuario' OR para_usuario = '$strUsuario'  AND estado != 3";
			$this -> db -> where($where);
			
			$rs = $this -> db -> get('t_sugerencias');
		}
		$strPara = '';
		$i = 0;
		if ($rs -> num_rows() != 0) {
			foreach ($rs->result() as $row) {
				$i++;
				$Mostrar = "<p><a href=\"#$i\" onClick=\"Mostrar_Respuesta('$i');\" id=\"dialog_link\" class=\"ui-state-default ui-corner-all\">
							<span class=\"ui-icon ui-icon-circle-triangle-s\"></span></a></p>";
				$Ocultar = "<p><a href=\"#$i\" onClick=\"Ocultar_Respuesta('$i');\" id=\"dialog_link\" class=\"ui-state-default ui-corner-all\">
						<span class=\"ui-icon ui-icon-circle-triangle-n\"></span></a></p>";
				$strReporte .= "<tr style='height:20px'>
					<td>$Mostrar</td>
					<td>" . strtoupper($row -> de_usuario) . "</td><td>" . strtoupper($row -> para_usuario) . "</td><td>" . $this -> Escribe_tema($row -> nombre) . "</td>
					<td>" . strtoupper(substr($row -> descripcion, 0, 30)) . "...</td><td>" . $this -> Escribe_Estado($row -> estado, $i) . "</td><td>" . $this -> Escribe_Prioridad($row -> prioridad) . "</td>
					</tr>
					<tr><td colspan=7>
						<div id='divRespuestas_" . $i . "' style='display:none'><br><br>
						<table style='width:100%' border=1 id='respuesta_$i'>";
				$this -> db -> where("id_sugerencia", $row -> id);
				$rsRes = $this -> db -> get('t_respuestas');
				$strPara = '';
				$j = 0;
				$strReporte .= "<tr><td><center>" . strtoupper($row -> descripcion) . "<br>CREADO ( " . $row -> fecha_creacion . " )</center></td></tr>";
				if ($rsRes -> num_rows() != 0) {
					foreach ($rsRes->result() as $row2) {
						if ($row2 -> respondio_usuario == $strUsuario) {
							$color = '#dbeaff';
						} else {
							$color = '#efe78e';
						}
						$j++;
						$strReporte .= "<TR ID='fila_" . $i . "_" . $j . "'><TD BGCOLOR='" . $color . "'>- DE " . strtoupper($row2->respondio_usuario) . ": " . 
						$row2->respuesta . "  <FONT STYLE=\"font: 82.5% Trebuchet MS, sans-serif; margin: 0px;\"> (<b><i>" .  $row2->fecha_modifica . "</i></b>) </FONT></TD></TR>	";
					}
				}
				$strReporte .= "</table>";
				if ($row -> estado != 3) {
					$strReporte .= "<table  style='width:100%' border=1>
									<tr><td>
										<TEXTAREA name='txtRespuesta_$i' id='txtRespuesta_$i' class='inputxt' style='width:100%;height:60px'></textarea>
										</td>
									</tr>
									<tr>
										<td>
											<center>
											<div class=\"demo\">
											<a href=\"#$i\" onClick=\"Ocultar_Respuesta('$i');\"  >OCULTAR MENSAJE EMERGENTE </a> 
											<a href='#' onclick='btnRespuesta(\"" . __LOCALWWW__ . "\",$row->id, $i, \"#dbeaff\");' id='btnRespuesta$i'>  ESCRIBIR</a> 
											<a href='#' onclick='btnCierraTema(\"" . __LOCALWWW__ . "\",$row->id, $i);' id='btnCierraTema$i'>  TERMINAR TEMA</a>
											</div>
											</center><br>
										</td>
									</tr>
									</table>
								</div>
								</td>
								</tr>
								";
				} else {
					$strReporte .= "<table style='width:100%' border=1>
									<tr><td colspan=7>
										<center><div class=\"demo\">
										<a href=\"#$i\" onClick=\"Ocultar_Respuesta('$i');\"  >OCULTAR MENSAJE EMERGENTE </a>";
					if ($intNivel == 0){
						$strReporte .="<a href='#' onclick='btnEliminaTema(\"" . __LOCALWWW__ . "\",$row->id, $i);' id='btnEliminaTema'> ELIMINAR TODO EL TEMA</a>";
					}
					$strReporte .="		</div>
										</center><br>
									</td>
								</tr>
								</table>
							</div>
							</td>
							</tr>";		
				}
			}
		}
		return $strReporte . "</table><br><br>";
	}

	public function Escribe_Estado($intEstado, $indice) {
		switch ($intEstado) {
			case 0 :
				return "<p><a href=\"#$indice\"  id=\"dialog_link\" class=\"ui-state-default ui-corner-all\"><span class=\"ui-icon ui-icon-document\"></span></a></p>";
				break;
			case 1 :
				return "REVISADO";
				break;
			case 2 :
				return "<p><a href=\"#$indice\"  id=\"dialog_link\" class=\"ui-state-default ui-corner-all\"><span class=\"ui-icon ui-icon-comment\"></span></a></p>";
				break;
			case 3 :
				return "<p><a href=\"#$indice\"  id=\"dialog_link\" class=\"ui-state-default ui-corner-all\"><span class=\"ui-icon ui-icon-locked\"></span></a></p>";
				break;
			default :
				return "NUEVO";
				break;
		}
	}

	public function Escribe_Prioridad($intPrioridad) {
		switch ($intPrioridad) {
			case 0 :
				return "NORMAL";
				break;
			case 1 :
				return "MUY ALTA";
				break;
			case 2 :
				return "ALTA";
				break;
			case 3 :
				return "BAJA";
				break;
			case 3 :
				return "MUY BAJA";
				break;
			default :
				return "NORMAL";
				break;
		}
	}

	public function Escribe_Tema($intTema) {
		switch ($intTema) {
			case 0 :
				return "MEJORAS";
				break;
			case 1 :
				return "DESARROLLO";
				break;
			case 2 :
				return "CONSULTA";
				break;
			case 3 :
				return "MODIFICAR";
				break;
			case 3 :
				return "MUY BAJA";
				break;
			default :
				return "NORMAL";
				break;
		}
	}

}
?>