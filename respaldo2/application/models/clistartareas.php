<?php

class CListartareas extends Model {

	public $lstUsr;

	public $usuario;

	public function __construct() {
		//
		parent::__construct();
	}

	/**
	 * Listar Tareas Pendientes del Usuario
	 *
	 * @param $user | object WUsuario
	 * @return array | Lista de Tareas
	 */
	public function Tareas_Pendientes($user) {

	}

	/**
	 * Listar Tareas Pendientes del Usuario
	 *
	 * @param $user | object WUsuario
	 * @return array | Lista de Tareas
	 */
	public function Usuarios_Conectados() {
		$data = "";
		$this -> db -> where("conectado", 1);
		$this -> db -> from("t_usuario");
		$rsConsulta = $this -> db -> get();
		if ($rsConsulta -> num_rows() > 0) {
			foreach ($rsConsulta->result() as $Consulta_Usuario) {
				$data .= "+ " . $Consulta_Usuario -> login . " | ";
			}
		} else {
			$data = "<br>Ningun usuario conectado...";
		}
		return $data;
	}

	/**
	 * Listar Tareas Pendientes del Usuario
	 *
	 * @param $user | object WUsuario
	 * @return array | Lista de Tareas
	 */
	public function Usuarios_Conectados_Chat($sUsr, $sNivel) {
		$data0 = "";
		$data1 = "";
		$data2 = "";
		$data3 = "";
		$data4 = "";
		$data5 = "";
		$data6 = "";
		$data7 = "";
		$data8 = "";
		$data9 = "";
		$data10 = "";
		$data11 = "";
		$query = "SELECT * FROM t_usuario
		JOIN _tr_usuarioperfil ON _tr_usuarioperfil.oidu = t_usuario.oid
		WHERE t_usuario.conectado = 1 AND seudonimo != '$sUsr'";
		$rsConsulta = $this -> db -> query($query);
		if ($rsConsulta -> num_rows() > 0) {
			$data0 .= "<ul class='lista_chat'><h5>GERENTE GENERAL</h5>";
			$data1 .= "<ul class='lista_chat'><h5>DIRECCI&Oacute;N DE COBRANZA</h5>";
			$data2 .= "<ul class='lista_chat'><h5>DIRECCI&Oacute;N DE VENTAS</h5>";
			$data3 .= "<ul class='lista_chat'><h5>GERENTE DE COBRANZA</h5>";
			$data4 .= "<ul class='lista_chat'><h5>GERENTE DE VENTAS</h5>";
			$data5 .= "<ul class='lista_chat'><h5>ANALISTA DE COBRANZA</h5>";
			$data6 .= "<ul class='lista_chat'><h5>ANALISTA DE VENTAS</h5>";
			$data7 .= "<ul class='lista_chat'><h5>SOPORTE</h5>";
			$data8 .= "<ul class='lista_chat'><h5>ANALISTA ADMINISTRATIVO</h5>";
			$data9 .= "<ul class='lista_chat'><h5>SUPERVISOR VOUCHER</h5>";
			$data10 .= "<ul class='lista_chat'><h5>REVISION</h5>";
			$data11 .= "<ul class='lista_chat'><h5>ANALISTA LIQUIDACION</h5>";

			foreach ($rsConsulta->result() as $Consulta_Usuario) {
				$lista_linajes = '<table ><tr ><td style="color:red;">NOMINAS</td></tr>';
				$sql_linaje = "select * from t_linaje 
				join _tr_usuariolinaje on _tr_usuariolinaje.oidl = t_linaje.oid
				where _tr_usuariolinaje.oidu = " . $Consulta_Usuario -> oid;
				$rsLinaje = $this -> db -> query($sql_linaje);
				foreach ($rsLinaje -> result() as $bancos) {
					$lista_linajes .= "<tr><td>" . $bancos -> nombre . ":</td><td>" . $bancos -> descripcion . "</td></tr>";
				}
				if ($rsLinaje -> num_rows() == 0) {
					$lista_linajes .= "<tr><td>TODAS</td></tr>";
				}
				$lista_linajes .= '</table>';

				$sql_ubica = "SELECT * FROM t_ubicacion join _tr_usuarioubicacion on _tr_usuarioubicacion.oidb = t_ubicacion.oid
				where _tr_usuarioubicacion.oidu = " . $Consulta_Usuario -> oid . ' limit 1';
				$rsUbica = $this -> db -> query($sql_ubica);
				$ubicacion = '';
				foreach ($rsUbica -> result() as $rUbica) {
					$ubicacion = $rUbica -> descripcion;
				}

				$ruta_imagen = BASEPATH . "img/usuarios/" . trim($Consulta_Usuario -> seudonimo) . ".png";
				if (file_exists($ruta_imagen)) {
					$ruta_imagen = __IMG__ . "usuarios/" . trim($Consulta_Usuario -> seudonimo) . ".png";
				} else {
					$ruta_imagen = __IMG__ . "usuarios/default.jpg";
				}
				$usuario_chat = '<li><a href="javascript:void(0)" 
						ondblclick="javascript:chatWith(\'' . $Consulta_Usuario -> seudonimo . '\',\'' . __LOCALWWW__ . '\');">' . $Consulta_Usuario -> descripcion . '<br><b>'. $Consulta_Usuario -> seudonimo .'</b>
							<div><center>
    							<table width="350">
									<tr>
										<td align="right" colspan="2"><b>' . $Consulta_Usuario -> descripcion . '</b>
											<hr style="border:1px dotted #DFD9C3; width:auto" />
										</td>
									</tr>
									<tr>
										<td rowspan="2">
											<center><img src="' . $ruta_imagen . '" style="width:70px;border: 1px solid #fff;" /></center>
										</td>
									</tr>
									<tr><td>' . $lista_linajes . '</td></tr>
									<tr><td>CORREO:</td><td><h5>' . $Consulta_Usuario -> correo . '</h5></td></tr>
									<tr><td>UBICACION:</td><td>' . $ubicacion . '</td></tr>
								</table>
    							<br><br>
    							</center>   							
    						</div>
						</a></li>';
				switch ($Consulta_Usuario -> oidp) {
					case 1 :
						$data5 .= $usuario_chat;
						break;
					case 2 :
						$data3 .= $usuario_chat;
						break;
					case 3 :
						$data1 .= $usuario_chat;
						break;
					case 4 :
						$data6 .= $usuario_chat;
						break;
					case 5 :
						$data4 .= $usuario_chat;
						break;
					case 6 :
						$data2 .= $usuario_chat;
						break;
					case 7 :
						$data0 .= $usuario_chat;
						break;
					case 8 :
						$data7 .= $usuario_chat;
						break;
					case 9 :
						$data7 .= $usuario_chat;
						break;
					case 10 :
						$data8 .= $usuario_chat;
						break;
					case 11 :
						$data9 .= $usuario_chat;
						break;
					case 15 :
						$data10 .= $usuario_chat;
						break;
					case 14 :
						$data11 .= $usuario_chat;
						break;
				}
				$usuario_chat = '';

			}
			$data0 .= "</ul>";
			$data1 .= "</ul>";
			$data2 .= "</ul>";
			$data3 .= "</ul>";
			$data4 .= "</ul>";
			$data5 .= "</ul>";
			$data6 .= "</ul>";
			$data7 .= "</ul>";
			$data8 .= "</ul>";
			$data9 .= "</ul>";
			$data10 .= "</ul>";
			$data11 .= "</ul>";
		} else {
			$data0 = "<br>Ningun Usuario...";
		}
		$lista_usuarios = $data0. '<br>' . $data10 . '<br>' . $data1 . '<br>' . $data4 ;
		
		if($sNivel != 1 && $sNivel != 2 && $sNivel != 4 ){
			$lista_usuarios .= '<br>' . $data3 .'<br>' . $data5 . '<br>' . $data6;
		}
		$lista_usuarios .= '<br>' . $data7 . '<br>' . $data8. '<br>' . $data9 . 'br' . $data11;
		return $lista_usuarios;
	}

	/**
	 * Listar Tareas Pendientes del Usuario
	 *
	 * @param $user | object WUsuario
	 * @return array | Lista de Tareas
	 */
	public function Listar_Usuarios_Combo() {
		$data = "";

		$this -> db -> from("t_usuarios");
		$rsConsulta = $this -> db -> get();
		if ($rsConsulta -> num_rows() > 0) {
			foreach ($rsConsulta->result() as $Consulta_Usuario) {
				$data .= "<option value='" . $Consulta_Usuario -> documento_id . "'>" . strtoupper($Consulta_Usuario -> login) . "</option>";
			}
		} else {
			$data = "<option value='----------'>----------</option>";
		}
		return $data;
	}
	
	public function Listar_Artefactos() {
		$data = "";

		$this -> db -> from("t_artefactos");
		$rsConsulta = $this -> db -> get();
		if ($rsConsulta -> num_rows() > 0) {
			foreach ($rsConsulta->result() as $Consulta_art) {
				$data .= "<option value='" . $Consulta_art -> artefacto_id . "'>" . strtoupper($Consulta_art -> nombre) . "</option>";
			}
		} else {
			$data = "<option value='xxx' selected='true'>----------</option>";
		}
		return $data;
	}

	public function Listar_Ubicacion_Combo() {
		$data = "";

		$this -> db -> from("t_ubicacion");
		$rsConsulta = $this -> db -> get();
		if ($rsConsulta -> num_rows() > 0) {
			foreach ($rsConsulta->result() as $Consulta_Usuario) {
				$data .= "<option value='" . $Consulta_Usuario -> oid . "'>" . strtoupper($Consulta_Usuario -> descripcion) . "</option>";
			}
		} else {
			$data = "<option value=''>----------</option>";
		}
		return $data;
	}
	
	public function Listar_Sucursal_Combo() {
		$data = "";

		$this -> db -> from("t_ubicacion");
		$this -> db -> where("sucursal !=",'-');
		$rsConsulta = $this -> db -> get();
		if ($rsConsulta -> num_rows() > 0) {
			$data = "<option value=''>----------</option>";
			foreach ($rsConsulta->result() as $Consulta_Usuario) {
				$data .= "<option value='" . strtoupper($Consulta_Usuario -> descripcion) . "|". $Consulta_Usuario -> direccion ."'>" . strtoupper($Consulta_Usuario -> sucursal) . "</option>";
			}
		} else {
			$data = "<option value=''>----------</option>";
		}
		return $data;
	}

	public function Listar_Banco_Combo() {
		$data = "";

		$this -> db -> from("t_banco");
		$rsConsulta = $this -> db -> get();
		if ($rsConsulta -> num_rows() > 0) {
			foreach ($rsConsulta->result() as $Consulta_Usuario) {
				$data .= "<option value='" . $Consulta_Usuario -> banco_id . "'>" . strtoupper($Consulta_Usuario -> nombre) . "</option>";
			}
		} else {
			$data = "<option value=''>----------</option>";
		}
		return $data;
	}

	public function Dependencia_Valor_Entero($sValor) {
		$sContenido = "";
		switch ($sValor) {
			case "SANCRISTOBAL" :
				$sContenido = 0;
				break;
			case "ELVIGIA" :
				$sContenido = 1;
				break;
			case "SANTABARBARA" :
				$sContenido = 2;
				break;
			case "cordoba" :
				$sContenido = 3;
				break;
			case "MERIDA" :
				$sContenido = 4;
				break;
			case "JOHANDER" :
				$sContenido = 5;
				break;
			case "alvaro" :
				$sContenido = 6;
				break;
			case "MERIDA2" :
				$sContenido = 7;
				break;
			case "MERIDA3" :
				$sContenido = 9;
				break;
			case "LASTEJAS" :
				$sContenido = 10;
				break;
			case "barinas465" :
				$sContenido = 11;
				break;
			case "maracaibo465" :
				$sContenido = 12;
				break;
			case "TODOS" :
				$sContenido = 20;
				break;
		}
		return $sContenido;
	}

	public function Contruye_Datos($query = null) {
		$query = 'SELECT t_clientes_creditos.documento_id, 
			COUNT(t_lista_cobros.credito_id) AS Intentos,
			t_clientes_creditos.contrato_id, fecha_inicio_cobro, t_clientes_creditos.nomina_procedencia, 
			t_clientes_creditos.cobrado_en, t_clientes_creditos.monto_total, SUM( t_lista_cobros.monto ) AS monto, 
			IF( t_clientes_creditos.monto_total = SUM( t_lista_cobros.monto ) , \'PAGADA\', \'MOROSO\' ) AS Estatus
			FROM t_clientes_creditos
			INNER JOIN t_lista_cobros ON t_clientes_creditos.contrato_id = t_lista_cobros.credito_id
			WHERE t_clientes_creditos.forma_contrato =1
			AND t_clientes_creditos.fecha_solicitud LIKE \'2011%\'
			GROUP BY t_lista_cobros.credito_id ORDER BY Estatus,cobrado_en';
		$consulta = $this -> db -> query($query);
		$Cabezera = $consulta -> list_fields();
		$Cuerpo = $consulta -> result();
		$objeto = array("Cabezera" => $Cabezera, "Cuerpo" => $Cuerpo, "Origen" => "Mysql", "Paginador" => 10, "Enumerar" => 0);

		return json_encode($objeto);
	}

	public function Genera_Formualario() {
		$objeto[1] = array("label" => "Algo ", "tipo" => "", "atributos" => "width:40px", );
		$objeto[2] = array("label" => "Cedula", "ciudad" => "combo", "valor" => array("values" => "texto", "values2" => "texto2"));
		$objeto[3] = array("label" => "Datos Basicos", "tipo" => "", "atributos" => "width:250px");
		$objeto[4] = array("label" => "Factura", "tipo" => "");
		$objeto[5] = array("label" => "Total", "tipo" => "");
		$objeto[6] = array("label" => "Solicitud");
		$objeto[7] = array("label" => "Linaje");
		return json_encode($objeto);
	}
	
	public function Nomina_Banco($banco=''){
		$query = 'SELECT nomina_procedencia FROM t_clientes_creditos WHERE cobrado_en ="'.$banco.'" GROUP BY nomina_procedencia ORDER BY nomina_procedencia';
		$consulta = $this -> db -> query($query);
		$nominas= array();
		foreach ($consulta->result() as $row){
			$nominas[] = $row -> nomina_procedencia;
		}
		return json_encode($nominas);;
	}

}
?>