<?php
/**
 *  @author Carlos Enrique Penaa Albarran
 *  @package Cooperativa.system.application.model.usuario
 *  @version 1.0.0
 *  @orm t_usuario
 */
class MUsuario extends Model {

	/**
	 * Clave de Referencia NULL
	 * @var integer
	 */
	var $tabla = "t_usuario";
	/**
	 * Clave de Referencia NULL
	 * @var integer
	 */
	var $oid = 'NULL';
	/**
	 * C.I
	 * @var int
	 * @orm char(32)
	 */
	var $documento_id;
	/**
	 * Nombre Completo
	 * @var int
	 * @orm char(32)
	 */
	var $descripcion;
	/**
	 * Sudonimo
	 * @var char(32)
	 * @orm char(32)
	 */
	var $seudonimo;
	/**
	 * Clave
	 * @var char(32)
	 * @orm char(32)
	 */
	var $clave;
	/**
	 * Correo
	 * @var char(64)
	 * @orm datetime
	 */
	var $correo;
	/**
	 * Fecha Creacion
	 * @var datetime
	 * @orm datetime
	 */
	var $fecha;

	/**
	 * Estatus de Conexcion (0: Activo 1: Eliminado)
	 * @var int
	 * @orm int
	 */
	var $estatus;
	/**
	 * Conectado (0: Desconectado 1: Conectado )
	 * @var int
	 * @orm int
	 */
	var $conectado;
	/**
	 * Origen de los Contratos (0: Desconectado 1: Conectado )
	 * @var int
	 * @orm int
	 */
	var $origen;
	/**
	 * Destino de los contratos (0: Desconectado 1: Conectado )
	 * @var int
	 * @orm int
	 */
	var $destino_verdadero;
	var $destino_falso;
	/**
	 * Ubicacion
	 * @var char(64)
	 * @orm uno a uno
	 */
	var $estado_nombre;
	var $estado_ejecucion;

	/**
	 * Ubicacion
	 * @var char(64)
	 * @orm uno a uno
	 */

	var $ubicacion = array();
	/**
	 * Perfil
	 * @var char(64)
	 * @orm un t_usuario tiene un t_privilegios
	 */
	var $perfil = array();
	/**
	 * La lista de los Privilegios
	 * @var char(64)
	 * @orm un t_perfil tiene muchos t_privilegios
	 */
	var $lista_privilegio = array();

	/**
	 * Los Bancos Asociados en Cobranza
	 * @var char(64)
	 * @orm un t_usuario tiene muchos t_linajes
	 */
	var $lista_linaje = array();

	/**
	 * Los Usuarios Tiene Muchos Usuarios Dependientes
	 * @var char(64)
	 * @orm un t_usuario tiene muchos t_usuarios
	 */
	var $lista_dependiente = array();

	/**
	 * lista de columnas
	 */
	var $union = array();

	/**
	 * lista de columnas
	 */
	var $columnas = array();

	/**
	 * lista de columnas
	 */
	protected $usuario = '';

	function __construct($usuario = null) {
		parent::__construct();
		$this -> columnas = $this -> _getVar();
		
		$this -> usuario = $usuario;
	}

	private function _getVar() {
		$sVars = array();
		$oVars = get_object_vars((object)get_class_vars(get_class()));
		
		
		$iVars = count($oVars);
		$i = 0;
		foreach ($oVars as $sCla => $sVal) {
			
			if ($sCla != '_parent_name' && $sCla != 'usuario' && $sCla != 'origen' && $sCla != 'destino_verdadero' && $sCla != 'destino_falso' && $sCla != 'estado_nombre' && $sCla != 'estado_ejecucion') {
				
				if (!(is_array($sVal))) {
					if ($sCla != 'tabla') {
						
						$sVars[$i] = trim($sCla);
					}
					++$i;
				}

			}
		}
		
		
		return $sVars;
	}

	private function _getSet($oEsq) {
		$sVars = '';
		$iVars = count($oEsq);
		$i = 0;
		
		foreach ($oEsq as $sCla => $sVal) {
			if ($i > 0 && $i != $iVars) { $sVars .= ',';
			}
			$sVars .= $this -> tabla . '.' . trim($sVal);
			$i++;
		}
		return $sVars;
	}

	/**
	 *
	 * @param string $Usr | Usuario
	 * @param string $Clv | Clave
	 * @return WUsuario
	 */
	public function Validar($Usr, $Clv) {
		$lstUsr = $this -> db -> query("SELECT * FROM t_usuario WHERE seudonimo='" . $Usr . "' AND clave='" . $Clv . "' LIMIT 1");
		if ($lstUsr -> num_rows() > 0) {
			foreach ($lstUsr->result() as $consulta_usuario) {
				$usuario = array('activo' => true, 'tipo' => 'Operador', 'valor' => $consulta_usuario -> seudonimo, 'id' => $consulta_usuario -> documento_id, 'posicion' => 20);
			}
		} else {
			$usuario = array('activo' => false, 'tipo' => 'Generico', 'valor' => 'NO EXISTE');
		}
		return (object)$usuario;
	}

	/**
	 * Buscar Usuario General
	 */
	function Buscar($strUsr = null) {

		$this -> _Cargar($strUsr);
		return true;
	}

	function getSQL($Arr) {
		$oFil = array();
		$i = 0;
		$sCon = '(';
		$sDependiente = '(';
		$lst = $Arr['lista_linaje'];
		$usr = $Arr['lista_dependiente'];
		$sOr = '';
		$sOrdependiente = '';
		$sCamp = 'expediente_c';
		if ($Arr['oidperfil'] == 0 || $Arr['oidperfil'] == 19) {
			$sCamp = 'codigo_n';
		}

		foreach ($usr as $sC => $sV) {
			++$i;
			if ($i > 1) {$sOrdependiente = 'OR ';
			}
			$sDependiente .= $sOrdependiente . 't_clientes_creditos.' . $sCamp . ' = \'' . $sV['seudonimo'] . '\' ';
		}
		if ($i > 0) {

			$sDependiente .= ' OR t_clientes_creditos.expediente_c=\'' . $Arr['seudonimo'] . '\') ';
		} else {
			$sDependiente = ' t_clientes_creditos.expediente_c=\'' . $Arr['seudonimo'] . '\' ';
		}
		$i = 0;
		//Validar Linaje
		foreach ($lst as $sC => $sV) {
			if (trim($sV['valor']) != '') {
				++$i;
				if ($i > 1) {$sOr = 'OR ';
				}
				$sCon .= $sOr . 't_clientes_creditos.cobrado_en = \'' . $sV['valor'] . '\' ';
			}
		}
		if ($i > 0) {
			$sCon .= ' OR ' . $sDependiente . ') AND ';
			$sDependiente = '';
		} else {
			$sDependiente .= ' AND ';
			$sCon = '';
		}

		$sSql['lista'] = $sDependiente;
		return $sSql;

	}

	public function getLinaje() {
		//A causa de las Cookies el limite maximo permitido es 12 registros por array ()
		$sSql = 'SELECT oid, nombre FROM  t_linaje';
		$rsC = $this -> db -> query($sSql);
		$rsL = $rsC -> result();
		foreach ($rsL as $r) {
			$this -> lista_linaje[] = array('id' => $r -> oid, 'valor' => $r -> nombre);
		}

	}

	/**
	 *
	 *
	 * @orm _tr_usuariolinaje uno a muchos
	 */
	private function _Cargar($sUsr) {
		$oidprivilegios = 0;
		$oidperfil = 4;
		
		$sConsulta = 'SELECT ' . $this -> _getSet($this -> columnas) . ',
		t_ubicacion.descripcion AS ubicacion, t_linaje.oid AS id,t_linaje.nombre,t_perfil.oid AS cperfil,t_perfil.descripcion AS dperfil, origen, verdadero, falso,
		t_estadodocumento.nombre AS estado_nombre,t_estadodocumento.clase AS clase,t_estadodocumento.denominacion AS denominacion,	t_estadodocumento.accion AS estado_ejecucion FROM t_usuario
		JOIN _tr_usuarioubicacion ON t_usuario.oid=_tr_usuarioubicacion.oidu
		JOIN t_ubicacion ON _tr_usuarioubicacion.oidb=t_ubicacion.oid
		LEFT JOIN _tr_usuariolinaje ON t_usuario.oid=_tr_usuariolinaje.oidu
		LEFT JOIN t_linaje ON _tr_usuariolinaje.oidl=t_linaje.oid
		JOIN _tr_usuarioperfil ON t_usuario.oid=_tr_usuarioperfil.oidu
		JOIN t_perfil ON _tr_usuarioperfil.oidp=t_perfil.oid
		JOIN t_estadodocumento ON t_perfil.origen=t_estadodocumento.oid	WHERE t_usuario.seudonimo=\'' . $sUsr . '\'';
		$rsCon = $this -> db -> query($sConsulta);
		if ($rsCon -> num_rows() > 0) {
			$rs = $rsCon -> result();
			//Pasar Valores Por Reflexion
			foreach ($this->columnas as $sC => $sV) {
				if (trim($this -> tabla . '.oid') == trim($sV)) {$sV = 'oid';
				}
				$this -> $sV = $rs[0] -> $sV;
			}
			$this -> ubicacion[] = $rs[0] -> ubicacion;
			$oidperfil = $rs[0] -> cperfil;
			$this -> perfil[] = $rs[0] -> dperfil;
			$this -> origen = $rs[0] -> origen;
			$this -> destino_verdadero = $rs[0] -> verdadero;
			$this -> destino_falso = $rs[0] -> falso;
			$this -> estado_nombre = $rs[0] -> estado_nombre;
			$this -> estado_ejecucion = $rs[0] -> estado_ejecucion;
			$this -> estado_clase = $rs[0] -> clase;
			$this -> estado_denominacion = $rs[0] -> denominacion;

			//LISTA DE LINAJE
			foreach ($rs as $rw) {
				//if(is_null($rw -> nombre)) {
				//$this -> getLinaje();
				//} else {
				$this -> lista_linaje[] = array('id' => $rw -> id, 'valor' => $rw -> nombre);
				//}
			}

			// LISTA DE PRIVILEGIOS (UN PERFIL TIENE MUCHOS PRIVILEGIOS)
			// $sConsulta = 'SELECT t_privilegios.nombre AS privilegio,t_privilegios.descripcion,t_privilegios.metodo,t_privilegios.clase,t_privilegios.accion
			// FROM t_perfil JOIN _tr_perfilprivilegios ON t_perfil.oid=_tr_perfilprivilegios.oidp
			// JOIN t_privilegios ON _tr_perfilprivilegios.oidb=t_privilegios.oid
			// WHERE t_perfil.oid=' . $oidperfil . ' AND t_privilegios.funcion = \'btn\' ';
			// $rsCon = $this -> db -> query($sConsulta);
			// $i = 0;
			// if ($rsCon -> num_rows() > 0) {
			// $rs = $rsCon -> result();
			// foreach ($rs as $rw) {
			// $this -> lista_privilegio['p_' . ++$i] = array('des' => $rw -> descripcion, 'clase' => $rw -> clase, 'metodo' => $rw -> metodo, 'accion' => $rw -> accion);
			// }
			// }

			// LISTA DE USUARIOS (UN USUARIO TIENE MUCHOS USUARIOS)
			$sConsulta = 'SELECT t_usuario.oid, t_usuario.descripcion AS dependiente,t_ubicacion.descripcion as ubicacion,
			t_usuario.documento_id as cedula, t_usuario.seudonimo
			FROM t_usuario	JOIN (SELECT _tr_usuariousuario.oidb, t_usuario.descripcion
			FROM t_usuario	JOIN _tr_usuariousuario ON t_usuario.oid = _tr_usuariousuario.oidu
			WHERE t_usuario.oid = ' . $this -> oid . ') AS A ON A.oidb = t_usuario.oid
			JOIN _tr_usuarioubicacion ON t_usuario.oid = _tr_usuarioubicacion.oidu
			JOIN t_ubicacion ON _tr_usuarioubicacion.oidb = t_ubicacion.oid';
			$rsCon = $this -> db -> query($sConsulta);
			if ($rsCon -> num_rows() > 0) {
				$rs = $rsCon -> result();
				foreach ($rs as $rw) {
					if (strtolower($sUsr) == "alvaro") {
						$this -> lista_dependiente[] = array('oid' => $rw -> oid, 'id' => $rw -> cedula, 'valor' => $rw -> dependiente, 'seudonimo' => $rw -> ubicacion);
					} else {
						$this -> lista_dependiente[] = array('oid' => $rw -> oid, 'id' => $rw -> cedula, 'valor' => $rw -> dependiente, 'seudonimo' => $rw -> seudonimo);
					}
				}
			}
			$nivel = $oidperfil;
			if (strtolower($sUsr) == "alvaro") {$nivel = 0;
			}
			$this -> union = array("oid" => $this -> oid, //
			"seudonimo" => $this -> seudonimo, //
			"usuario" => $sUsr, //
			"oidperfil" => $oidperfil, //
			"nivel" => $nivel, //
			"lista_linaje" => $this -> lista_linaje, //
			"ubicacion" => $this -> ubicacion[0], //
			"perfil" => $this -> perfil[0], //
			//"lista_privilegio" => $this -> lista_privilegio, //
			"lista_dependiente" => $this -> lista_dependiente);
			return 0;
		}

	}

	/**
	 *
	 */
	function getPrivilegios($oidperfil) {
		$sConsulta = 'SELECT t_privilegios.nombre AS privilegio,t_privilegios.descripcion,t_privilegios.metodo,t_privilegios.clase,t_privilegios.accion
			FROM t_perfil JOIN _tr_perfilprivilegios ON t_perfil.oid=_tr_perfilprivilegios.oidp
			JOIN t_privilegios ON _tr_perfilprivilegios.oidb=t_privilegios.oid
			WHERE t_perfil.oid=' . $oidperfil . ' AND t_privilegios.funcion = \'btn\' ';
		$rsCon = $this -> db -> query($sConsulta);
		$i = 0;
		if ($rsCon -> num_rows() > 0) {
			$rs = $rsCon -> result();
			foreach ($rs as $rw) {
				$this -> lista_privilegio['p_' . ++$i] = array('des' => $rw -> descripcion, 'clase' => $rw -> clase, 'metodo' => $rw -> metodo, 'accion' => $rw -> accion);
			}
		}
		return $this -> lista_privilegio;
	}

	/**
	 * @param string Usuario Id
	 * @return array
	 */
	function getDependientes($oidu) {
		$sConsulta = 'SELECT t_usuario.oid, t_usuario.descripcion AS dependiente,t_ubicacion.descripcion as ubicacion,
			t_usuario.documento_id as cedula, t_usuario.seudonimo,sd
			FROM t_usuario	JOIN (SELECT _tr_usuariousuario.oidb, t_usuario.descripcion,t_usuario.seudonimo AS sd 
			FROM t_usuario	JOIN _tr_usuariousuario ON t_usuario.oid = _tr_usuariousuario.oidu
			WHERE t_usuario.oid = ' . $oidu . ') AS A ON A.oidb = t_usuario.oid
			JOIN _tr_usuarioubicacion ON t_usuario.oid = _tr_usuarioubicacion.oidu
			JOIN t_ubicacion ON _tr_usuarioubicacion.oidb = t_ubicacion.oid';
		$rsCon = $this -> db -> query($sConsulta);
		if ($rsCon -> num_rows() > 0) {
			$rs = $rsCon -> result();
			foreach ($rs as $rw) {
				if (strtolower($rw -> sd) == "alvaro" || strtolower($rw -> sd) == "carlos" || strtolower($rw -> sd) == "cuadre" || strtolower($rw -> sd) == "planificacion"|| strtolower($rw -> sd) == "albeida") {
					$this -> lista_dependiente[] = array('oid' => $rw -> oid, 'id' => $rw -> cedula, 'valor' => $rw -> ubicacion, 'seudonimo' => $rw -> ubicacion);
				} else {
					$this -> lista_dependiente[] = array('oid' => $rw -> oid, 'id' => $rw -> cedula, 'valor' => $rw -> dependiente, 'seudonimo' => $rw -> seudonimo);
				}
			}
		}
		return $this -> lista_dependiente;
	}

	function getUsuariosContrato($oficina = '') {
		$sOficina = '';
		if ($oficina != '') {
			$sOficina = " JOIN _tr_usuarioubicacion ON _tr_usuarioubicacion.oidu = t_usuario.oid AND _tr_usuarioubicacion.oidb = " . (int) $oficina . " ";
		}
		$sConsulta = "SELECT DISTINCT t_usuario.seudonimo, t_usuario.descripcion
			FROM t_usuario
			JOIN _tr_usuarioperfil ON _tr_usuarioperfil.oidu = t_usuario.oid" . $sOficina . "
			WHERE _tr_usuarioperfil.oidp IN (4,5)
			ORDER BY t_usuario.descripcion";
		$rsCon = $this -> db -> query($sConsulta);
		$lista = array();
		foreach ($rsCon -> result() as $rw) {
			$lista[] = array('valor' => $rw -> descripcion, 'seudonimo' => $rw -> seudonimo);
		}
		return $lista;
	}

	function Modificar($nombre = '', $clave = '', $correo = '', $seudonimo) {
		if ($nombre != '')
			$data['descripcion'] = $nombre;
		if ($clave != '')
			$data['clave'] = md5($clave);
		if ($correo != '')
			$data['correo'] = $correo;
		//$data = array('descripcion' => $nombre, 'clave' => md5($clave), 'correo' => $correo);
		$this -> db -> where('seudonimo', $seudonimo);
		$this -> db -> update('t_usuario', $data);
	}

	function Guardar($arr,$per, $ubi) {
		$this -> db -> insert('t_usuario', $arr);
		$oid = $this -> db -> insert_id();
		$this -> db -> query('INSERT INTO _tr_usuarioperfil (oidu,oidp) VALUES(' . $oid . ',' . $per . ') ');
		$this -> db -> query('INSERT INTO _tr_usuarioubicacion (oidu,oidb) VALUES(' . $oid . ',' . $ubi . ') ');
		echo "Se registro Con Exito";
	}

}
?>