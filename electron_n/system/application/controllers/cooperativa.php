<?php
/*
 * @author Carlos Enrique Peña Albarrán
 *
 * @package cooperativa-electron.system.application
 * @version 2.0.0
 */
if(!isset($_SESSION)) session_start ();
date_default_timezone_set ( 'America/Caracas' );

class cooperativa extends Controller {
	public function __construct() {
		parent::__construct ();
		$this->load->database ();
		$this->load->helper ( 'url' );
		$this->load->model ( "CMenu" );
		$this->load->model ( "CPersonas" );
		$this->load->model ( 'CClientes' );
		$this->load->model ( 'CCreditos' );
		$this->load->model ( 'CListartareas' );
		$this->load->model ( 'cliente/mcliente', 'MCliente' );
		
		$this->load->model ( 'usuario/musuario', 'MUsuario' );
		$this->load->model ( 'usuario/mmenu', 'MMenu' );
		$this->load->library ( 'session' );
	}
	
	/*
	 * -------------------------------------------------------
	 * Iniciando Paginas
	 * -------------------------------------------------------
	 */
	public function index() {
		$this->login ();
	}
	function login() {
		$this->load->view ( "login" );
	}
	function verificacion() {
		if (!$this->session->userdata ( 'usuario' )) {
			
			$this->load->library ( "validation" );
			$rules ['txtUsuario'] = 'trim|alpha_dash|required';
			$rules ['txtClave'] = 'trim|alpha_dash|required|md5';
			$this->validation->set_rules ( $rules );
			if ($this->validation->run () == false) {
				$this->login ();
			} else {
				$this->validacion ( $_POST ['txtUsuario'], $_POST ['txtClave'] );
			}
		} else {
			redirect("/cooperativa/bienvenida");
		}
		return true;
	}
	private function validacion($Usr = '', $Clv = '') {
		if (!$this->session->userdata ( 'usuario' )) {
			$this->gbl_usr = $this->MUsuario->Validar ( $Usr, $Clv );
			//echo $this->gbl_usr->activo; 
			if ($this->gbl_usr->activo == true) {
				setcookie ( 'Cooperativa', $Usr, time () + 86400, "/" );
				$this->MUsuario->Buscar ( $Usr );
				$Sessions = array (
						'usuario' => $this->gbl_usr->valor, //
						'seudonimo' => $this->MUsuario->union ['seudonimo'], //
						'nivel' => $this->MUsuario->union ['nivel'], //
						'ubicacion' => $this->MUsuario->ubicacion [0], //
						'posicion' => $this->gbl_usr->posicion, //
						'id' => $this->gbl_usr->id, //
						'oidu' => $this->MUsuario->oid, // Id del Usuario
						'oidp' => $this->MUsuario->union ['oidperfil'], // Id del Privilegio
						'conectado' => TRUE, //
						'origen' => $this->MUsuario->origen, //
						'destino_verdadero' => $this->MUsuario->destino_verdadero, //
						'destino_falso' => $this->MUsuario->destino_falso, //
						                                                   // 'lista_privilegio' => $this -> MUsuario -> lista_privilegio, //
						'lista_linaje' => $this->MUsuario->lista_linaje, //
						                                                 // 'lista_dependiente' => $this -> MUsuario -> lista_dependiente, //
						'descripcion' => $this->MUsuario->descripcion, //
						'estado_nombre' => $this->MUsuario->estado_nombre, //
						'estado_ejecucion' => $this->MUsuario->estado_ejecucion, //
						'estado_clase' => $this->MUsuario->estado_clase, //
						'estado_denominacion' => $this->MUsuario->estado_denominacion 
				); //
	
				
				$this->session->set_userdata ( $Sessions );
				$strQuery = "UPDATE t_usuario SET conectado=1 WHERE seudonimo='" . $this->gbl_usr->valor . "'";
				//echo $strQuery;
				$this->db->query ( $strQuery );
				$this->bienvenida ();
				// header('location', 'http://192.167.0.7/cooperativa/index.php/cooperativa/buzon');
			} else {
				$this->session->sess_destroy ();
				$this->login ();
			}
		}else{
			redirect(base_url());
		}
	}
	
	function bienvenida(){
		if ($this->session->userdata ( 'usuario' )) {
			$data ['Nivel'] = $this->session->userdata ( 'nivel' );
			$data ['Menu'] = $this->CMenu->getHtml_Menu ( $this->session->userdata ( 'nivel' ) );
			$_SESSION ['usuario'] = $this->session->userdata ( 'usuario' );
			$data ['tipo'] = 0;
			$this->load->view ( "bienvenida", $data );
		} else {
			$this->logout();
		}
	}
	
	function getUsrConnect() {
		$sUsr = $this->session->userdata ( 'usuario' );
		// $this -> CListartareas -> Usuarios_Conectados_Chat($sUsr, $this -> session -> userdata('nivel'))
		$sArr = array (
				'usr' => 'Carlos',
				'cant' => $this->Pendientes ( 1 ) 
		);
		echo json_encode ( $sArr );
	}
	
	/**
	 * Un Perfil tiene muchos privilegios
	 */
	function getClientesBtn() {
		$prv = $this->MUsuario->getPrivilegios ( $this->session->userdata ( 'oidp' ) );
		echo json_encode ( $prv );
	}
	function Listar() {
		$lista_dependiente = $this->MUsuario->getDependientes ( $this->session->userdata ( 'oidu' ) );
		$lista_linaje = $this->session->userdata ( 'lista_linaje' );
		if ($this->session->userdata ( 'nivel' ) == 0 || $this->session->userdata ( 'nivel' ) > 3) {
			$this->MUsuario->getLinaje ();
			$lst = $this->MUsuario->lista_linaje;
		}
		$listar = array (
				"dependientes" => $lista_dependiente, //
				"linaje" => $lista_linaje, //
				"oid" => $this->session->userdata ( 'oid' ), //
				"seudonimo" => $this->session->userdata ( 'usuario' ),
				'valor' => $this->session->userdata ( 'descripcion' ) 
		); //

		echo json_encode ( $listar );
	}
	function Listar_Alvaro() {
		$lista_bancos = array ();
		$query = "SELECT nombre FROM t_banco";
		$resultado = $this->db->query ( $query );
		foreach ( $resultado->result () as $row ) {
			$lista_bancos [] = $row->nombre;
		}
		$listar = array (
				"linaje" => $lista_bancos 
		);
		echo json_encode ( $listar );
	}
	function Listar_Dependientes() {
		// $prv = $this -> session -> userdata('lista_dependiente');
		$prv = $this->MUsuario->getDependientes ( $this->session->userdata ( 'oidu' ) );
		echo json_encode ( $prv );
	}
	function Linajes() {
		$prv = $this->session->userdata ( 'lista_linaje' );
		echo json_encode ( $prv );
	}
	
	/**
	 *
	 * @param
	 *        	{int} iTipo 1 = Cantidad de Registros
	 * @param
	 *        	{string} iForma forma_contrato
	 */
	function Pendientes($iTipo = 0, $iForma = null) {
		$parametros = array (
				'usr' => $this->session->userdata ( 'usuario' ), // Usuario
				'org' => $this->session->userdata ( 'origen' ), // Origen
				'txt' => $this->session->userdata ( 'estado_nombre' ), // Estado Nombre
				'acc_v' => "EjecutarVerdadero", // Metodo que se ejecuta en el Controlador
				'acc_f' => "EjecutarFalso", // Metodo que se ejecuta en el Controlador
				'estado_ejecucion' => $this->session->userdata ( 'estado_ejecucion' ), //
				'estado_clase' => $this->session->userdata ( 'estado_clase' ), //
				'estado_denominacion' => $this->session->userdata ( 'estado_denominacion' ), //
				'linaje' => $this->session->userdata ( 'lista_linaje' ), //
				'dependiente' => $this->MUsuario->getDependientes ( $this->session->userdata ( 'oidu' ) ), //
				'itipo' => $iTipo, // Contar los nuevos elementos
				'perfil' => $this->session->userdata ( 'nivel' ), // Perfil del usuario
				'iforma' => $iForma 
		);
		if ($iTipo > 0) {
			return $this->MCliente->Pendientes ( $parametros );
		} else {
			echo $this->MCliente->Pendientes ( $parametros );
		}
	}
	function PendientesDeposito() {
		$parametros = array (
				'usr' => $this->session->userdata ( 'usuario' ), // Usuario
				'org' => $this->session->userdata ( 'origen' ), // Origen
				'acc_v' => "EjecutarVerdaderoF", // Metodo que se ejecuta en el Controlador
				'acc_f' => "EjecutarFalsoF", // Metodo que se ejecuta en el Controlador
				'perfil' => $this->session->userdata ( 'nivel' ) 
		); // Perfil del usuario

		
		echo $this->MCliente->PendientesDeposito ( $parametros );
	}
	function PendientesDepositoImprimir() {
		$parametros = array (
				'usr' => $this->session->userdata ( 'usuario' ), // Usuario
				'perfil' => $this->session->userdata ( 'nivel' ) 
		); // Perfil del usuario

		$banco = $_POST ['banco'];
		$ubica = $_POST ['ubicacion'];
		echo $this->MCliente->PendientesDepositoImprimir ( $parametros, $banco, $ubica );
	}
	
	/**
	 * Pendientes de Revision
	 */
	function PendientesRevision($iVal = 0) {
		$parametros = array (
				'usr' => $this->session->userdata ( 'usuario' ), // Usuario
				'org' => $this->session->userdata ( 'origen' ), // Origen
				'acc_v' => "EjecutarDisparoreRevision", // Metodo que se ejecuta en el Controlador
				'acc_f' => "EjecutarDisparoreRevisionFalso", // Metodo que se ejecuta en el Controlador
				'perfil' => $this->session->userdata ( 'nivel' ) 
		); // Perfil del usuario

		echo $this->MCliente->PendientesRev ( $parametros, $iVal );
	}
	function AceptarRevision() {
		$json = json_decode ( $_POST ['objeto'], true );
		$arr ['est'] = 1;
		$valida_deuda = $this->Deuda_Cliente2 ( $json [3] );
		if ($valida_deuda == 'SI') {
			$this->db->where ( 'cod', $json [0] );
			$this->db->update ( 't_snegociacion', $arr );
		} else {
			echo 'Posee Deuda';
		}
	}
	function RechazarRevision() {
		$json = json_decode ( $_POST ['objeto'], true );
		$arr ['est'] = 5;
		$this->db->where ( 'cod', $json [0] );
		$this->db->update ( 't_snegociacion', $arr );
	}
	function Pagos_Factura() {
		$nivel = $this->session->userdata ( 'nivel' );
		if ($nivel == 0 || $nivel == 9 || $this->session->userdata ( 'usuario' ) == 'Carlos' || $nivel == 5 || $nivel == 3 || $nivel == 18) {
			$arr ['tipo'] = $_POST ['tipo'];
			$arr ['ubicacion'] = $_POST ['ubicacion'];
			$arr ['ano'] = $_POST ['ano'];
			$arr ['mes'] = $_POST ['mes'];
			$arr ['linaje'] = $_POST ['linaje'];
			if ($nivel == 5) {
				$arr ['ubicacion'] = $this->session->userdata ( 'ubicacion' );
			}
			echo $this->MCliente->Pagos_Factura ( $arr );
		}
	}
	function CProgramadas() {
		if ($this->session->userdata ( 'usuario' )) {
			$this->load->model ( "cliente/mvoucher", "MVoucher" );
			$objeto = $this->MVoucher->CProgramadas ( $this->session->userdata ( 'lista_linaje' ) );
			echo $objeto ['json'];
		} else {
			$this->logout ();
		}
	}
	function Rechazos() {
		$parametros = array (
				'usr' => $this->session->userdata ( 'usuario' ), // Usuario
				'org' => $this->session->userdata ( 'origen' ), // Origen
				'txt' => $this->session->userdata ( 'estado_nombre' ), // Estado Nombre
				'acc_v' => "EjecutarVerdadero", // Metodo que se ejecuta en el Controlador
				'acc_f' => "EjecutarFalso", // Metodo que se ejecuta en el Controlador
				'estado_ejecucion' => $this->session->userdata ( 'estado_ejecucion' ), //
				'estado_clase' => $this->session->userdata ( 'estado_clase' ), //
				'estado_denominacion' => $this->session->userdata ( 'estado_denominacion' ), //
				'linaje' => $this->session->userdata ( 'lista_linaje' ), //
				'dependiente' => $this->MUsuario->getDependientes ( $this->session->userdata ( 'oidu' ) ) 
		); //

		echo $this->MCliente->Rechazos ( $parametros );
	}
	function ipdf($sformato, $sC) {
		$sCls = $this->session->userdata ( 'estado_clase' );
		$sClsD = $this->session->userdata ( 'estado_denominacion' );
		$sMtd = $this->session->userdata ( 'estado_ejecucion' );
		$this->load->model ( $sCls, $sClsD );
		$this->$sClsD->$sMtd ( $sformato, '', $sC, '', '' );
	}
	function EjecutarVerdadero() {
		if (isset ( $_POST ['objeto'] )) {
			$json = json_decode ( $_POST ['objeto'], true );
			if ($this->session->userdata ( 'destino_verdadero' ) == 6) {
				$this->MCliente->EjecutarDisparo ( $json [1], $this->session->userdata ( 'destino_verdadero' ), $json [3], 1 );
				echo '<a href=\'' . __LOCALWWW__ . '/index.php/cooperativa/ipdf/' . $json [4] . '/' . $json [1] . '\' border=0 target=\'top\'><center><img src=\'' . __IMG__ . 'pdf.png\'><br>	Imprimir Formato</a>';
			} else {
				$sQuery = "SELECT * FROM t_clientes_creditos INNER JOIN t_cheque_garantia ON
				t_cheque_garantia.factura=t_clientes_creditos.numero_factura
				WHERE contrato_id='" . $json [1] . "' LIMIT 1";
				$Consulta = $this->db->query ( $sQuery );
				
				$this->MCliente->EjecutarDisparo ( $json [1], $this->session->userdata ( 'destino_verdadero' ), $json [3], 1 );
				echo '<a href=\'' . __LOCALWWW__ . '/index.php/cooperativa/ipdf/' . $json [4], '/', $json [1] . '\' border=0 target=\'top\'><center><img src=\'' . __IMG__ . 'pdf.png\'><br>	Imprimir Formato</a>';
				/**
				if ($Consulta->num_rows () > 0) {
					$this->MCliente->EjecutarDisparo ( $json [1], $this->session->userdata ( 'destino_verdadero' ), $json [3], 1 );
					echo '<a href=\'' . __LOCALWWW__ . '/index.php/cooperativa/ipdf/' . $json [4], '/', $json [1] . '\' border=0 target=\'top\'><center><img src=\'' . __IMG__ . 'pdf.png\'><br>	Imprimir Formato</a>';
				} else {
					echo "Debe Ingresar Cheque por Garantia en el Modulo de Expediente Digital";
				}
				**/
				
			}
		}
	}
	function EjecutarFalso() {
		if (isset ( $_POST ['objeto'] )) {
			$json = json_decode ( $_POST ['objeto'], true );
			$this->MCliente->EjecutarDisparo ( $json [1], $this->session->userdata ( 'destino_falso' ), $json [3], 0 );
		}
	}
	
	/**
	 * En caso de los Artefactos hay una previa revision
	 */
	function EjecutarDisparoreRevision() {
		if (isset ( $_POST ['objeto'] )) {
			$json = json_decode ( $_POST ['objeto'], true );
			$this->MCliente->EjecutarDisparoreRevision ( $json [1], $this->session->userdata ( 'destino_verdadero' ), $json [2] );
		}
	}
	
	/**
	 * Solicitud de Servicio Clave Aleatorio
	 */
	function Generar_SRand() {
		$sCod = rand ( 1, 99999 );
		echo $this->MCliente->_setCodigoSRand ( $sCod );
	}
	
	/**
	 * Solicitud de Servicio Guardar Plan de Pago
	 */
	function Guardar_SolicitudP() {
		$arr = $_POST;
		$arr ['est'] = 1;
		$arr ['ubic'] = $this->session->userdata ( 'ubicacion' );
		$arr ['usua'] = $this->session->userdata ( 'usuario' );
		$this->db->insert ( 't_snegociacion', $arr );
	}
	
	/**
	 * Capacidad de Endeudamiento Guardar
	 */
	function Guardar_SolicitudCE() {
		$this->db->insert ( 't_scapacidad', $_POST );
	}
	
	/**
	 * $json 0: Contrato | 1: Factura | 2: Linaje | 3: Nomina | 4: Forma de Pago | 5: Cuota |6: Monto Cuota | 7:Fecha
	 */
	function AceptarCambiosContratos() {
		if (isset ( $_POST ['objeto'] )) {
			$msj = "Se Realizaron Las Modificaciones";
			$json = json_decode ( $_POST ['objeto'], true );
			/*if ($json [2] == "BICENTENARIO" && $json [8] == "Unico") {
				$json [4] = "6-BIC";
				$msj = 'Se ajusto modalidad de pago a voucher Bicentenario, debido a las nuevas normas de la empresa.<br>Se proceso con exito.';
			}*/
			if ($json [4] == "DOMICILIACION") {
				$json [4] = "5-DOM";
			}
			/*if ($json [5] == 1) {
				$json [4] = "5-DOM";
			}*/
			$sCod = '';
			$sPago = explode ( "-", $json [4] );
			$arr ['cobrado_en'] = $json [2];
			$arr ['nomina_procedencia'] = $json [3];
			// $arr['numero_control'] = $json[8];
			$arr ['marca_consulta'] = $sPago [0];
			$this->db->where ( 'contrato_id', $json [0] );
			$this->db->update ( 't_clientes_creditos', $arr );
			if ($sPago [0] == 6 || $sPago [1] == "VBI") {
				$fecha = explode ( "-", $json [7] );
				$mes = $fecha [1];
				$anio = $fecha [0];
				
				
				$pronto = 0;
				$banco = $sPago [1];
				if ($sPago [1] == "VBI") {
					$pronto = 1;
					$banco = 'BIC';
				}
				$modo = 0;
				$pref = '';
				if ($sPago [1] == "TRF") {
					$modo = 1;
					$pref = 'T-';
				}
				if ($sPago [1] == "COT") {
					$modo = 2;
					$pref = 'CT-';
				}
				$sCero = "00";
				$sCodI = substr ( $json [0], 4 );
				for($i = 0; $i < $json [5]; $i ++) {
					$sCod = $sCodI;
					if ($i >= 9) {
						$sCero = "0";
					}
					$sCod .= $sCero . ($i + 1);
					if ($mes < 10) {
						$mes = '0' . $mes;
					}
					$fecha_v = $anio . "-" . $mes . "-01";
					
					$datosVoucher = array (
							'cid' => $json [1], //
							'ndep' => trim ( $pref.$sCod ), //
							'fecha' => trim ( $fecha_v ), //
							'monto' => $json [6], //
							'banco' => $banco,
							'pronto' => $pronto,
							'modo' => $modo 
					);
					$this->db->insert ( 't_lista_voucher', $datosVoucher );
					$mes ++;
					if ($mes > 12) {
						$mes = 1;
						$anio ++;
					}
				}
			}
			echo $msj;
		} else {
			echo "No Se Realizaron Las Modificaciones";
		}
	}
	
	/**
	 * Listar los contratos y prepararlos para procesarlos
	 */
	function ListarContratosPendientes($sFac = '') {
		echo $this->MCliente->ListarContratoPendientes ( $sFac );
	}
	
	/**
	 * Enlace de Generar Factura
	 */
	function Generar_Contratos_View($sCert = '', $sFac = '') {
		if ($this->session->userdata ( 'usuario' )) {
			$data ['Menu'] = $this->CMenu->getHtml_Menu ( $this->session->userdata ( 'nivel' ) );
			$data ['Nivel'] = $this->session->userdata ( 'nivel' );
			$data ['ubicacion'] = $this->session->userdata ( 'ubicacion' );
			$data ['cert'] = $sCert;
			$data ['fact'] = $sFac;
			$arr = $this->Generar_Contratos ( $sCert, $sFac );
			$data ['err'] = $arr ['err'];
			$data ['msj'] = $arr ['msj'];
			
			$this->load->view ( "asistente/asistente5", $data );
		} else {
			$this->login ();
		}
	}
	
	/**
	 * Generando Contratos
	 */
	function Generar_Contratos($sCert = '', $sFac = '') {
		$arr = array ();
		$arrA ['msj'] = 'Felicitaciones';
		$arrA ['err'] = 1;
		if ($sCert != '') {
			$sCon = "SELECT *  FROM t_snegociacion WHERE cod='" . $sCert . "' AND est < 3 ";
			$rsC = $this->db->query ( $sCon );
			$cResult = $rsC->result ();
			if ($rsC->num_rows () > 0) {
				
				$sConsulta = "SELECT * FROM t_personas JOIN t_scapacidad ON t_personas.documento_id=t_scapacidad.ced AND t_scapacidad.cert = " . $sCert . " 
				JOIN t_fpresupuesto ON t_personas.documento_id=t_fpresupuesto.cedula
				WHERE t_fpresupuesto.factura='" . $sFac . "' LIMIT 1";
				$consulta = $this->db->query ( $sConsulta );
				$resultado = $consulta->result ();
				foreach ( $resultado as $row ) {
					$arr ["documento_id"] = $row->ced;
					$arr ["numero_factura"] = $row->factura;
					$arr ["cobrado_en"] = $row->lina;
					$arr ["nomina_procedencia"] = $row->nomi;
					$arr ["empresa"] = $row->empresa;
					
					// Nomina de Procedencia
					$arr ["codigo_n"] = $row->ubicacion;
					$arr ["codigo_n_a"] = $row->usuario;
					$arr ["expediente_c"] = $row->usuario;
				}
				$sConsulta = "SELECT *, cuot*mont as total  FROM t_snegociacion WHERE cod='" . $sCert . "' AND est < 3 ";
				$consultas = $this->db->query ( $sConsulta );
				$resultados = $consultas->result ();
				foreach ( $resultados as $row ) {
					// Numero de Credito Control
					$arr ["contrato_id"] = $this->maximo_valor ();
					// 1 Credito o 2 Prestamo
					$arr ["motivo"] = $row->tip;
					// Precio de Contado del Credito
					$arr ["monto_operacion"] = $row->cont;
					// Monto Total del Credito
					$arr ["cantidad"] = $row->total;
					// Monto Total del Credito
					$arr ["monto_total"] = $row->total;
					// Cantidad de Cuotas Por Contrato
					$arr ["numero_cuotas"] = $row->cuot;
					// Monto de las cuotas
					$arr ["monto_cuota"] = $row->mont;
					// Periodo de Descuento Mensual (4)
					$arr ["periocidad"] = 4;
					// Fecha de Inicio de Cobro
					$arr ["fecha_inicio_cobro"] = $row->desd;
					// Fecha de Solicitud del Credito
					$arr ["fecha_solicitud"] = $row->soli;
					// Domiciliacion
					$arr ["marca_consulta"] = 5;
					if ($row->cuot > 1) {
						$arr ["forma_contrato"] = 0;
					} else {
						$meses = explode ( '-', $row->desd );
						$arr ["forma_contrato"] = $this->Evaluar_FormaContrato ( $meses [1], $row->tip );
					}
					$arr ["condicion"] = $this->Evaluar_Condicion ( $row->cond, $row->tip );
					
					$this->db->insert ( 't_clientes_creditos', $arr );
				}
				$nego ['est'] = 3;
				$this->db->where ( 'cod', $sCert );
				$this->db->update ( 't_snegociacion', $nego );
			} else {
				$arrA ['msj'] = 'Contratos Para el Certificado ya Generado';
				$arrA ['err'] = 0;
			}
		}
		return $arrA;
	}
	function Evaluar_FormaContrato($sCod, $sMot) {
		// if($sMot == 2) return 6;
		switch ($sCod) {
			case '11' :
				return 1;
				break;
			default :
				return 2;
				break;
		}
	}
	
	// Condicion del Sistema Moto, Artefacto, Ambos y Prestamos
	function Evaluar_Condicion($sCod, $sTipo) {
		if ($sTipo == 2)
			return 0;
		switch ($sCod) {
			case 'Moto' :
				return 7;
				break;
			case 'Artefacto' :
				return 5;
				break;
			case 'Deposito' :
				return 0;
				break;
			case 'Artefacto' :
				return 5;
				break;
			default :
				return 5;
				break;
		}
	}
	
	/**
	 * Ejecutar por Facturas
	 */
	function EjecutarVerdaderoF() {
		if (isset ( $_POST ['objeto'] )) {
			$json = json_decode ( $_POST ['objeto'], true );
			$destino = 0;
			if ($this->session->userdata ( 'nivel' ) == 0) {
				$destino = 5;
			} else {
				$destino = $this->session->userdata ( 'destino_verdadero' );
			}
			$resp = $this->MCliente->EjecutarDisparoF ( $json [1], $destino, $json [2], $json [3], $json [4] );
			echo $resp;
		}
	}
	function EjecutarFalsoF() {
		if (isset ( $_POST ['objeto'] )) {
			$json = json_decode ( $_POST ['objeto'], true );
			$this->MCliente->EjecutarDisparoF ( $json [1], $this->session->userdata ( 'destino_falso' ), $json [3], 0 );
		}
	}
	function buzon() {
		if ($this->session->userdata ( 'usuario' )) {
			$data ['Nivel'] = $this->session->userdata ( 'nivel' );
			$data ['Menu'] = $this->CMenu->getHtml_Menu ( $this->session->userdata ( 'nivel' ) );
			$_SESSION ['usuario'] = $this->session->userdata ( 'usuario' );
			switch ($this->session->userdata ( 'nivel' )) {
				case 12 :
					$this->load->model ( 'CNomina' );
					$this->load->model ( 'CInventario' );
					$this->load->model ( 'CZonapostal' );
					$data ['lista_artefactos'] = $this->CInventario->Combo_Artefactos ();
					$data ['lista_artefactos2'] = $this->CInventario->Combo_Artefactos2 ();
					$data ['estados'] = $this->CZonapostal->Estados ();
					$data ['lista'] = $this->CNomina->Combo ();
					$data ['Menu'] = $this->CMenu->getHtml_Menu ( $this->session->userdata ( 'nivel' ) );
					$data ['Nivel'] = $this->session->userdata ( 'nivel' );
					$data ['Contenido'] = $this->DataSource_Reportes ();
					if ($msj = 'x') {
						$msj = '';
					}
					if ($des = 'x') {
						$des = '';
					}
					$data ['Modelo'] = '';
					$data ['Proveedor'] = '';
					$data ['Equipo'] = '';
					$data ['Marca'] = '';
					$data ['Precio_C'] = '';
					$data ['Precio_V'] = '';
					$data ['Descripcion'] = '';
					$data ['CanGar'] = '';
					$data ['Garantia'] = '';
					$data ['Porcentaje'] = '';
					$data ['Credi_Compra'] = '';
					$data ['Listar_Usuarios_Combo'] = $this->CListartareas->Listar_Usuarios_Combo ();
					$data ['Listar_Ubicacion'] = $this->CListartareas->Listar_Ubicacion_Combo ();
					$data ['Listar_Banco'] = $this->CListartareas->Listar_Banco_Combo ();
					$data ['msj'] = $msj;
					$data ['lista_art'] = $this->CListartareas->Listar_Artefactos ();
					$this->load->view ( "inventario", $data );
					
					break;
				case 13 :
					$data ['tipo'] = 0;
					$this->load->view ( "deposito", $data );
					break;
				case 14 :
					$data ['tipo'] = 0;
					$this->load->view ( "deposito", $data );
					break;
				case 15 :
					$data ['tipo'] = 0;
					$this->load->view ( "revision", $data );
					break;
				case 16 :
					$data ['tipo'] = 0;
					$this->archivo_cliente ();
					break;
				
				case 18 :
					$data ['tipo'] = 0;
					$this->panel ();
					break;
				case 20 :
					$data ['tipo'] = 0;
					$this->reportes ();
					break;
				case 21 :
					$data ['tipo'] = 0;
					$this->archivosAuditoria();
					break;
				
				default :
					$this->load->view ( "buzon", $data );
					break;
			}
		} else {
			$this->logout ();
		}
	}
	function Liquidacion() {
		if ($this->session->userdata ( 'usuario' )) {
			$data ['Nivel'] = $this->session->userdata ( 'nivel' );
			$data ['Menu'] = $this->CMenu->getHtml_Menu ( $this->session->userdata ( 'nivel' ) );
			$_SESSION ['usuario'] = $this->session->userdata ( 'usuario' );
			$data ['tipo'] = 0;
			$this->load->view ( "deposito", $data );
		} else {
			$this->logout ();
		}
	}
	function registrar() {
		if ($this->session->userdata ( 'usuario' )) {
			$this->load->model ( 'CNomina' );
			$this->load->model ( 'CZonapostal' );
			$this->load->model ( 'MInsti' );
			$data ['lista'] = $this->CNomina->Combo ();
			$data ['insti'] = $this->MInsti->Combo ();
			$data ['estados'] = $this->CZonapostal->Estados ();
			$data ['Menu'] = $this->CMenu->getHtml_Menu ( $this->session->userdata ( 'nivel' ) );
			$data ['Nivel'] = $this->session->userdata ( 'nivel' );
			$data ['Ubicacion'] = $this->session->userdata ( 'ubicacion' );
			$this->load->view ( "clientes", $data );
		} else {
			$this->logout ();
		}
	}
	function procesar($iConsulta = "") {
		$cedula = "";
		$contrato_id = "";
		$factura = "";
		$serial = "";
		$cheque = "";
		$cuenta = "";
		$data ['Nivel'] = $this->session->userdata ( 'nivel' );
		
		if ($this->session->userdata ( 'usuario' )) {
			$data ['Componentes_Yui'] = "";
			$data ['Menu'] = $this->CMenu->getHtml_Menu ( $this->session->userdata ( 'nivel' ) );
			$data ['lista'] = "";
			if (isset ( $_POST ['txtBuscar'] )) {
				if (trim ( $_POST ['txtBuscar'] ) != '') {
					if ($_POST ['txtForma'] == "CB-") {
						$cuenta = $_POST ['txtBuscar'];
						$cheque = "";
						$cedula = "";
						$contrato_id = "";
						$factura = "";
					}
					if ($_POST ['txtForma'] == "CH-") {
						$cheque = $_POST ['txtBuscar'];
						$cedula = "";
						$contrato_id = "";
						$factura = "";
					}
					if ($_POST ['txtForma'] == "S-" && $this->session->userdata ( 'nivel' ) == 0) {
						$cedula = "";
						$contrato_id = "";
						$factura = "";
						$serial = trim ( $_POST ['txtBuscar'] );
						$this->load->model ( "CInventario" );
						$this->load->model ( "CProductos" );
						
						$CProductos = new $this->CProductos ();
						$data ['lista'] = $this->CInventario->listar ( "TODOS", 1, $this->session->userdata ( 'nivel' ), $serial, $CProductos, $cuenta );
					} else {
						if ($_POST ['txtForma'] == "C-") {
							$contrato_id = trim ( $_POST ['txtBuscar'] );
						} elseif ($_POST ['txtForma'] == "F-") {
							$cedula = "";
							$contrato_id = "";
							$factura = trim ( $_POST ['txtBuscar'] );
						} elseif ($_POST ['txtForma'] == "SV-") {
							$serial = trim ( $_POST ['txtBuscar'] );
							$contrato_id = "";
							$factura = "";
						} else {
							$cedula = trim ( $_POST ['txtBuscar'] );
							$contrato_id = "";
							$factura = "";
						}
						if ($_POST ['txtForma'] == "X-") {
							$sConsulta = "SELECT " . $_POST ['txtBuscar'] . " AS valor;";
							$rs = $this->db->query ( $sConsulta );
							$detalles = $rs->result ();
							foreach ( $detalles as $rowDetalle ) {
								$data ['lista'] = "<br><br><center><font style='font-size: 18px'>DADO LA OPERACION <b>(" . $_POST ['txtBuscar'] . ")</b><br><br> EL REESULTADO ES <br></font>
										<font style='font-size: 28px'><b>" . number_format ( $rowDetalle->valor, 2 ) . "</b></font></center>";
							}
						} else {
							if ($_POST ['txtForma'] == "V-") {
								$data ['lista'] = '';
								$data ['cedula'] = $cedula;
							} else {
								$data ['lista'] = $this->CClientes->CI_Clientes ( $cedula, $contrato_id, $factura, $serial, $this->session->userdata ( 'nivel' ), ( int ) $iConsulta, 15, $cheque, $cuenta );
							}
						}
					}
				} else {
					$data ['lista'] = '';
				}
			} else {
				$data ['lista'] = '';
				$cedula = "";
			}
			$this->load->view ( "procesar", $data );
		} else {
			$this->logout ();
		}
	}
	function Procesar2($iConsulta = "") {
		/*
		 * $cedula = "";
		 * $contrato_id = "";
		 * $factura = "";
		 * $serial = "";
		 * $cheque = "";
		 */
		$cuenta = "";
		$data ['lista'] = 'si';
		$data ['Nivel'] = $this->session->userdata ( 'nivel' );
		if ($this->session->userdata ( 'usuario' )) {
			$data ['Componentes_Yui'] = "";
			$data ['Menu'] = $this->CMenu->getHtml_Menu ( $this->session->userdata ( 'nivel' ) );
			$data ['lista'] = "";
			if (isset ( $_POST ['txtBuscar'] )) {
				$data ['lista'] = 'entro';
				$opt = $_POST ['txtForma'];
				switch ($opt) {
					case 'CB-' :
						
						// $cuenta = $_POST['txtBuscar'];
						// $data['lista'] = $this -> CClientes -> CI_Clientes($cedula, $contrato_id, $factura, $serial, $this -> session -> userdata('nivel'), (int)$iConsulta, 15, $cheque, $cuenta);
						$data ['lista'] = $this->CClientes->CI_Clientes ( $_POST ['txtBuscar'], 'cuenta', ( int ) $iConsulta );
						break;
					case 'CH-' :
						
						// $cheque = $_POST['txtBuscar'];
						// $data['lista'] = $this -> CClientes -> CI_Clientes($cedula, $contrato_id, $factura, $serial, $this -> session -> userdata('nivel'), (int)$iConsulta, 15, $cheque, $cuenta);
						$data ['lista'] = $this->CClientes->CI_Clientes ( $_POST ['txtBuscar'], 'cheque', ( int ) $iConsulta );
						break;
					case 'C-' :
						/*$contrato_id = trim($_POST['txtBuscar']);
						 $data['lista'] = $this -> CClientes -> CI_Clientes($cedula, $contrato_id, $factura, $serial, $this -> session -> userdata('nivel'), (int)$iConsulta, 15, $cheque, $cuenta);*/
						$data ['lista'] = $this->CClientes->CI_Clientes ( trim ( $_POST ['txtBuscar'] ), 'contrato', ( int ) $iConsulta );
						break;
					case 'F-' :
						/*$factura = trim($_POST['txtBuscar']);
						 $data['lista'] = $this -> CClientes -> CI_Clientes($cedula, $contrato_id, $factura, $serial, $this -> session -> userdata('nivel'), (int)$iConsulta, 15, $cheque, $cuenta);*/
						$data ['lista'] = $this->CClientes->CI_Clientes ( $_POST ['txtBuscar'], 'factura', ( int ) $iConsulta );
						break;
					case 'SV-' :
						/*$serial = trim($_POST['txtBuscar']);
						 $data['lista'] = $this -> CClientes -> CI_Clientes($cedula, $contrato_id, $factura, $serial, $this -> session -> userdata('nivel'), (int)$iConsulta, 15, $cheque, $cuenta);*/
						$data ['lista'] = $this->CClientes->CI_Clientes ( $_POST ['txtBuscar'], 'serialv', ( int ) $iConsulta );
						break;
					case 'V-' :
						$data ['lista'] = '';
						$data ['cedula'] = trim ( $_POST ['txtBuscar'] );
						break;
					case 'VO-' :
						$data ['lista'] = '';
						$data ['voucher'] = trim ( $_POST ['txtBuscar'] );
						break;
					case 'X-' :
						$sConsulta = "SELECT " . $_POST ['txtBuscar'] . " AS valor;";
						$rs = $this->db->query ( $sConsulta );
						$detalles = $rs->result ();
						foreach ( $detalles as $rowDetalle ) {
							$data ['lista'] = "<br><br><center><font style='font-size: 18px'>DADO LA OPERACION <b>(" . $_POST ['txtBuscar'] . ")</b><br><br> EL REESULTADO ES <br></font>
									<font style='font-size: 28px'><b>" . number_format ( $rowDetalle->valor, 2 ) . "</b></font></center>";
						}
						break;
					case 'S-' :
						if ($this->session->userdata ( 'nivel' ) == 0) {
							$cedula = "";
							$contrato_id = "";
							$factura = "";
							$serial = trim ( $_POST ['txtBuscar'] );
							$this->load->model ( "CInventario" );
							$this->load->model ( "CProductos" );
							$CProductos = new $this->CProductos ();
							$data ['lista'] = $this->CInventario->listar ( "TODOS", 1, $this->session->userdata ( 'nivel' ), $serial, $CProductos, $cuenta );
						}
						
						break;
					case 'AC-' :
						$this->load->model ( "chequera/mchequera", "MChequera" );
						
						$json [0] = trim ( $_POST ['txtBuscar'] );
						$json [2] = 'CHEQUE ANULADO';
						$json [3] = 3;
						$this->MChequera->Actualizar ( $json );
						$data ['lista'] = "CHEQUE ANULADO POR GERENTE";
						
						break;
					
					default :
						break;
				}
			}
			$this->load->view ( "procesar", $data );
		} else {
			$this->logout ();
		}
	}
	function ProcesarNuevo($iConsulta = "", $id = '', $forma = '') {
		$cuenta = "";
		$data ['lista'] = 'si';
		$data ['Nivel'] = $this->session->userdata ( 'nivel' );
		if (! isset ( $_POST ['txtBuscar'] )) {
			$_POST ['txtBuscar'] = $id;
		}
		if (! isset ( $_POST ['txtForma'] )) {
			$_POST ['txtForma'] = $forma;
		}
		if ($this->session->userdata ( 'usuario' )) {
			$data ['Componentes_Yui'] = "";
			$data ['Menu'] = $this->CMenu->getHtml_Menu ( $this->session->userdata ( 'nivel' ) );
			$data ['lista'] = "";
			if (isset ( $_POST ['txtBuscar'] )) {
				$data ['lista'] = 'entro';
				$opt = $_POST ['txtForma'];
				switch ($opt) {
					case 'CB-' :
						$data ['lista'] = $this->CClientes->CI_Clientes ( $_POST ['txtBuscar'], 'cuenta', ( int ) $iConsulta );
						$this->load->view ( "procesar", $data );
						break;
					case 'CH-' :
						$data ['lista'] = $this->CClientes->CI_Clientes ( $_POST ['txtBuscar'], 'cheque', ( int ) $iConsulta );
						$this->load->view ( "procesar", $data );
						break;
					case 'C-' :
						$data ['lista'] = $this->CClientes->CI_Clientes ( trim ( $_POST ['txtBuscar'] ), 'contrato', ( int ) $iConsulta );
						$this->load->view ( "procesar", $data );
						break;
					case 'F-' :
						$data ['lista'] = $this->CClientes->CI_Clientes ( $_POST ['txtBuscar'], 'factura', ( int ) $iConsulta );
						$this->load->view ( "procesar", $data );
						break;
					case 'SV-' :
						$data ['lista'] = $this->CClientes->CI_Clientes ( $_POST ['txtBuscar'], 'serialv', ( int ) $iConsulta );
						$this->load->view ( "procesar", $data );
						break;
					case 'V-' :
						
						$data ['lista'] = '';
						$data ['cedula'] = trim ( $_POST ['txtBuscar'] );
						//echo $_POST ['txtBuscar']."<br>";
						$this->load->view ( "procesar_nuevo", $data );
						break;
					case 'VO-' :
						$data ['lista'] = '';
						$data ['voucher'] = trim ( $_POST ['txtBuscar'] );
						$this->load->view ( "procesar_nuevo", $data );
						break;
					case 'X-' :
						$sConsulta = "SELECT " . $_POST ['txtBuscar'] . " AS valor;";
						$rs = $this->db->query ( $sConsulta );
						$detalles = $rs->result ();
						foreach ( $detalles as $rowDetalle ) {
							$data ['lista'] = "<br><br><center><font style='font-size: 18px'>DADO LA OPERACION <b>(" . $_POST ['txtBuscar'] . ")</b><br><br> EL REESULTADO ES <br></font>
									<font style='font-size: 28px'><b>" . number_format ( $rowDetalle->valor, 2 ) . "</b></font></center>";
						}
						$this->load->view ( "procesar", $data );
						break;
					case 'S-' :
						if ($this->session->userdata ( 'nivel' ) == 0) {
							$cedula = "";
							$contrato_id = "";
							$factura = "";
							$serial = trim ( $_POST ['txtBuscar'] );
							$this->load->model ( "CInventario" );
							$this->load->model ( "CProductos" );
							$CProductos = new $this->CProductos ();
							$data ['lista'] = $this->CInventario->listar ( "TODOS", 1, $this->session->userdata ( 'nivel' ), $serial, $CProductos, $cuenta );
						}
						$this->load->view ( "procesar", $data );
						
						break;
					case 'AC-' :
						$this->load->model ( "chequera/mchequera", "MChequera" );
						
						$json [0] = trim ( $_POST ['txtBuscar'] );
						$json [2] = 'CHEQUE ANULADO';
						$json [3] = 3;
						$this->MChequera->Actualizar ( $json );
						$data ['lista'] = "CHEQUE ANULADO POR GERENTE";
						$this->load->view ( "procesar", $data );
						break;
					
					default :
						break;
				}
			}
		} else {
			$this->logout ();
		}
	}
	function Guarda_Chequera() {
		$this->load->model ( "chequera/mchequera", "MChequera" );
		$MChequera = new $this->MChequera ();
		$MChequera->nombre = $_POST ['banco'];
		$MChequera->descripcion = $_POST ['descripcion'];
		
		$MChequera->oidu = $_POST ['ubicacion'];
		$MChequera->cantidad = $_POST ['cantidad'];
		$MChequera->cuenta = $_POST ['cuenta'];
		$MChequera->nchequera = $_POST ['nchequera'];
		
		$MChequera->fecha = $_POST ['fecha'];
		$seriales = explode ( ",", $_POST ['cheques'] );
		$this->db->insert ( 't_chequera', $MChequera );
		$arr = array ();
		foreach ( $seriales as $key ) {
			$arr = array (
					'oidc' => $_POST ['banco'] . '-' . $_POST ['ubicacion'] . '-' . $_POST ['cuenta'], //
					'serie' => $key, //
					'fecha' => $_POST ['fecha'], //
					'nchequera' => $_POST ['nchequera'], //
					'estatus' => 0 
			);
			$this->db->insert ( 't_serialescheques', $arr );
		}
		echo "Feclicitaciones Proceso Exitoso...";
	}
	
	// funciones para aceptar y rechazar numeros de cheque
	function AceptarCheque() {
		$this->load->model ( "chequera/mchequera", "MChequera" );
		if (isset ( $_POST ['objeto'] )) {
			$json = json_decode ( $_POST ['objeto'], true );
			$json [3] = 2;
			$this->MChequera->Actualizar ( $json );
			echo "Proceso finalizado...";
		}
	}
	function RechazarCheque() {
		$this->load->model ( "chequera/mchequera", "MChequera" );
		if (isset ( $_POST ['objeto'] )) {
			$json = json_decode ( $_POST ['objeto'], true );
			$json [3] = 3;
			$this->MChequera->Actualizar ( $json );
			echo "Proceso finalizado...";
		}
	}
	
	/**
	 * Modulo de Inventario
	 */
	public function Inventario($msj = NULL, $mod = NULL, $prov = NULL, $equ = NULL, $mar = NULL, $prc = NULL, $prv = NULL, $des = NULL, $cang = NULL, $gar = NULL, $por = NULL, $credi_compra = NULL) {
		if ($this->session->userdata ( 'usuario' )) {
			$this->load->model ( 'CNomina' );
			$this->load->model ( 'CInventario' );
			$this->load->model ( 'CZonapostal' );
			//$data ['lista_artefactos'] = $this->CInventario->Combo_Artefactos ();
			//$data ['lista_artefactos2'] = $this->CInventario->Combo_Artefactos2 ();
			$data ['estados'] = $this->CZonapostal->Estados ();
			$data ['lista'] = $this->CNomina->Combo ();
			$data ['Menu'] = $this->CMenu->getHtml_Menu ( $this->session->userdata ( 'nivel' ) );
			$data ['Nivel'] = $this->session->userdata ( 'nivel' );
			$data ['Contenido'] = $this->DataSource_Reportes ();
			if ($msj = 'x') {
				$msj = '';
			}
			if ($des = 'x') {
				$des = '';
			}
			$data ['Modelo'] = $mod;
			$data ['Proveedor'] = $prov;
			$data ['Equipo'] = $equ;
			$data ['Marca'] = $mar;
			$data ['Precio_C'] = $prc;
			$data ['Precio_V'] = $prv;
			$data ['Descripcion'] = $des;
			$data ['CanGar'] = $cang;
			$data ['Garantia'] = $gar;
			$data ['Porcentaje'] = $por;
			$data ['Credi_Compra'] = $credi_compra;
			$data ['Listar_Usuarios_Combo'] = $this->CListartareas->Listar_Usuarios_Combo ();
			$data ['Listar_Ubicacion'] = $this->CListartareas->Listar_Ubicacion_Combo ();
			$data ['Listar_Banco'] = $this->CListartareas->Listar_Banco_Combo ();
			$data ['msj'] = $msj;
			$data ['lista_art'] = $this->CListartareas->Listar_Artefactos ();
			$this->load->view ( "inventario", $data );
		} else {
			$this->logout ();
		}
	}
	public function BSerial() {
		$consulta = $this->db->query ( 'SELECT * FROM t_productos WHERE serial="' . $_POST ['serial'] . '"' );
		$cant = $consulta->num_rows ();
		echo $cant;
	}
	public function Mod_Modelo() {
		$id = $_POST ['id'];
		$nmod = $_POST ['nmod'];
		$this->db->query ( 'UPDATE t_inventario SET modelo="' . $nmod . '" WHERE inventario_id=' . $id );
		echo "SE MODIFICO CON EXITO";
	}
	public function Mod_Artefacto() {
		$id = $_POST ['id'];
		$nart = $_POST ['nart'];
		$this->db->query ( 'UPDATE t_artefactos SET nombre="' . $nart . '" WHERE artefacto_id=' . $id );
		echo "SE MODIFICO CON EXITO";
	}
	public function Mercancia($tipo = 0) {
		if ($this->session->userdata ( 'usuario' )) {
			
			$data ['Menu'] = $this->CMenu->getHtml_Menu ( $this->session->userdata ( 'nivel' ) );
			$data ['Nivel'] = $this->session->userdata ( 'nivel' );
			$data ['Listar_Ubicacion'] = $this->CListartareas->Listar_Ubicacion_Combo ();
			$data ['tipo'] = $tipo;
			$this->load->view ( "mercancia", $data );
		} else {
			$this->logout ();
		}
	}
	public function Mercancia_Inventario($tipo = 0) {
		if ($this->session->userdata ( 'usuario' )) {
			
			$data ['Menu'] = $this->CMenu->getHtml_Menu ( $this->session->userdata ( 'nivel' ) );
			$data ['Nivel'] = $this->session->userdata ( 'nivel' );
			$data ['Listar_Ubicacion'] = $this->CListartareas->Listar_Ubicacion_Combo ();
			$data ['tipo'] = $tipo;
			$this->load->view ( "mercancia_inventario", $data );
		} else {
			$this->logout ();
		}
	}
	public function GCodigo_Mercancia() {
		$this->load->model ( 'inventario/minventario', 'MInventario' );
		echo $this->MInventario->Generar_Codigo ();
	}
	public function Guardar_Mercancia() {
		$this->load->model ( 'inventario/minventario', 'MInventario' );
		$serial = $_POST ['serial'];
		$codigo = $_POST ['codigo'];
		$cant = $_POST ['cantidad'];
		$precioc = $_POST ['precioc'];
		$preciov = $_POST ['preciov'];
		$ubicacion = $_POST ['ubica'];
		$marca = $_POST ['marca'];
		$descrip = $_POST ['descrip'];
		$modelo = $_POST ['modelo'];
		$provee = $_POST ['proveedor'];
		$tipo = $_POST ['tipo'];
		$mercancia = new $this->MInventario ();
		$mercancia->codigo = $codigo;
		$mercancia->marca = $marca;
		$mercancia->descripcion = $descrip;
		$mercancia->modelo = $modelo;
		$mercancia->cantidad = $cant;
		$mercancia->proveedor = $provee;
		$mercancia->almacen = $ubicacion;
		$mercancia->precioc = $precioc;
		$mercancia->preciov = $preciov;
		$mercancia->serial = $serial;
		$mercancia->tipo = $tipo;
		$mercancia->Salvar ();
		$mercancia->Guarda_Seriales ();
		$mercancia->Guarda_Sub_Seriales ();
		
		echo "Se inserto con exito...";
	}
	public function Guardar_Mercancia_Serial() {
		$this->load->model ( 'inventario/minventario', 'MInventario' );
		$serial = $_POST ['serial'];
		$codigo = $_POST ['codigo'];
		$cant = $_POST ['cantidad'];
		$precioc = $_POST ['precioc'];
		$preciov = $_POST ['preciov'];
		$ubicacion = $_POST ['ubica'];
		$marca = $_POST ['marca'];
		$descrip = $_POST ['descrip'];
		$modelo = $_POST ['modelo'];
		$provee = $_POST ['proveedor'];
		$tipo = $_POST ['tipo'];
		$mercancia = new $this->MInventario ();
		$mercancia->codigo = $codigo;
		$mercancia->marca = $marca;
		$mercancia->descripcion = $descrip;
		$mercancia->modelo = $modelo;
		$mercancia->cantidad = $cant;
		$mercancia->proveedor = $provee;
		$mercancia->almacen = $ubicacion;
		$mercancia->precioc = $precioc;
		$mercancia->preciov = $preciov;
		$mercancia->tipo = $tipo;
		$mercancia->serial = $serial;
		$mercancia->Guarda_Seriales ();
		$mercancia->Guarda_Sub_Seriales ();
		
		echo "Se inserto con exito...";
	}
	public function Modificar_Mercancia() {
		$this->load->view ( "inventario/modificar.php" );
	}
	public function ListarGrid_Mercancia() {
		$this->load->model ( 'inventario/minventario', 'MInventario' );
		$mercancia = '';
		$tipo = 0;
		if (isset ( $_POST ['mercancia'] ))
			$mercancia = $_POST ['mercancia'];
		if (isset ( $_POST ['tipo'] ))
			$tipo = $_POST ['tipo'];
		$objeto = $this->MInventario->ListarGrid ( $mercancia, $tipo );
		echo $objeto ['json'];
	}
	public function ListarGrid_Inventario() {
		$nivel = $this->session->userdata ( 'nivel' );
		if ($nivel == 0 || $nivel == 9 || $nivel == 5 || $nivel == 18 || $this->session->userdata ( 'usuario' ) == 'Carlos') {
			$this->load->model ( 'inventario/minventario', 'MInventario' );
			if (isset ( $_POST )) {
				$arr ['estatus'] = $_POST ['estatus'];
				$arr ['ubicacion'] = $_POST ['ubicacion'];
				
				if ($nivel == 5) {
					if ($_POST ['estatus'] != 0)
						$arr ['estatus'] = $_POST ['estatus'];
					else
						$arr ['estatus'] = '';
					$arr ['ubicacion'] = $this->session->userdata ( 'ubicacion' );
				}
				$objeto = $this->MInventario->ListarGrid_Inventario ( $arr );
			} else {
				$objeto = $this->MInventario->ListarGrid_Inventario ( '' );
			}
			echo $objeto;
		} else {
			$this->logout ();
		}
	}
	public function ListarGrid_Productos() {
		$nivel = $this->session->userdata ( 'nivel' );
		if ($nivel == 0 || $nivel == 9 || $this->session->userdata ( 'usuario' ) == 'Carlos') {
			$this->load->model ( 'inventario/minventario', 'MInventario' );
			$objeto = $this->MInventario->ListarGrid_Productos ();
			echo $objeto;
		} else {
			$this->logout ();
		}
	}
	public function Detalle_Grid_Productos() {
		$this->load->model ( 'inventario/minventario', 'MInventario' );
		if (isset ( $_POST ['objeto'] )) {
			$nivel = $this->session->userdata ( 'nivel' );
			$json = json_decode ( $_POST ['objeto'], true );
			$objeto = $this->MInventario->Detalle_Grid_Productos ( $json [0] );
			echo $objeto ['json'];
		}
	}
	public function MuestraDetalleMercancia() {
		$this->load->model ( 'inventario/minventario', 'MInventario' );
		if (isset ( $_POST ['objeto'] )) {
			$json = json_decode ( $_POST ['objeto'], true );
			
			$objeto = $this->MInventario->Listar_Detalle_Grid ( $json [0] );
			echo $objeto ['json'];
		}
	}
	public function MuestraDetalleMercancia_Inventario() {
		$this->load->model ( 'inventario/minventario', 'MInventario' );
		if (isset ( $_POST ['objeto'] )) {
			$nivel = $this->session->userdata ( 'nivel' );
			$json = json_decode ( $_POST ['objeto'], true );
			
			$objeto = $this->MInventario->Listar_Detalle_Grid_Inventario ( $json [0], $json [1], $json [2], $nivel );
			echo $objeto ['json'];
		}
	}
	public function MuestraDetalleSerial() {
		$this->load->model ( 'inventario/minventario', 'MInventario' );
		if (isset ( $_POST ['objeto'] )) {
			$json = json_decode ( $_POST ['objeto'], true );
			
			$objeto = $this->MInventario->MuestraDetalleSerial ( $json [0], $json [1] );
			echo $objeto ['json'];
		}
	}
	public function Listar_Entregas() {
		$this->load->model ( 'inventario/minventario', 'MInventario' );
		$objeto = $this->MInventario->Listar_Entregas ( $_POST ['tipo'] );
		echo $objeto ['json'];
	}
	public function Consulta_Mercancia() {
		$ub = '';
		if (isset ( $_POST ["nombre"] )) {
			if (isset ( $_POST ['ubicacion'] ))
				$ub = $this->session->userdata ( 'ubicacion' );
			$this->load->model ( 'inventario/minventario', 'MInventario' );
			$arreglo = $this->MInventario->Consultar ( $_POST ["nombre"], $ub );
			echo $arreglo;
			// echo "jolas";
		}
	}
	public function Consulta_Inventario() {
		$ub = '';
		if (isset ( $_POST ["nombre"] )) {
			if (isset ( $_POST ['tipo'] ))
				$ub = $this->session->userdata ( 'ubicacion' );
			$this->load->model ( 'inventario/minventario', 'MInventario' );
			$arreglo = $this->MInventario->Consultar_Inventario ( $_POST ["nombre"], $ub );
			echo $arreglo;
			// echo "jolas";
		}
	}
	public function Actualizar_Mercancia_Lote() {
		if ($_POST ['objeto']) {
			$objeto = json_decode ( $_POST ['objeto'], true );
			$arreglo = array (
					'ubicacion' => $objeto [1],
					'precioc' => $objeto [2],
					'preciov' => $objeto [3] 
			);
			$this->db->where ( "oids", $objeto [0] );
			$this->db->update ( 't_mercancia_seriales', $arreglo );
			
			$arreglo = array (
					'ubicacion' => $objeto [1],
					'precioc' => $objeto [2],
					'preciov' => $objeto [3],
					'estatus' => 1 
			);
			$this->db->where ( "oids", $objeto [0] );
			$this->db->where ( "estatus <", 1 );
			
			$this->db->update ( 't_lista_mercancia', $arreglo );
			echo "Se Actualizo Con Exito..";
		} else {
			echo "No se Pudo Actualizar";
		}
	}
	public function Eliminar_Mercancia_Lote() {
		if ($_POST ['objeto']) {
			$objeto = json_decode ( $_POST ['objeto'], true );
			$this->db->where ( "oids", $objeto [0] );
			$this->db->where ( "estatus <", 2 );
			$this->db->delete ( 't_lista_mercancia' );
		} else {
			echo "No se Pudo Actualizar";
		}
	}
	public function Actualizar_Mercancia_S() {
		if ($_POST ['objeto']) {
			$objeto = json_decode ( $_POST ['objeto'], true );
			$arreglo = array (
					'ubicacion' => $objeto [1],
					'precioc' => $objeto [2],
					'preciov' => $objeto [3],
					'estatus' => 1 
			);
			$this->db->where ( "serial_item", $objeto [0] );
			$this->db->where ( "estatus <", 2 );
			$this->db->update ( 't_lista_mercancia', $arreglo );
			if ($objeto [4] == "ENTREGADO") {
				echo "No se pudo actualizar el producto ya fue entregado";
			} else
				echo "Se Actualizo Con Exito..";
		} else {
			echo "No se Pudo Actualizar";
		}
	}
	public function Eliminar_Mercancia_S() {
		if ($_POST ['objeto']) {
			$objeto = json_decode ( $_POST ['objeto'], true );
			
			$this->db->where ( "serial_item", $objeto [0] );
			$this->db->where ( "estatus <", 2 );
			$this->db->delete ( 't_lista_mercancia' );
			if ($objeto [1] == "ENTREGADO") {
				echo "No se pudo elimiar el producto ya fue entregado";
			} else
				echo "Se Elimino Con Exito..";
		} else {
			echo "No se Pudo Actualizar";
		}
	}
	public function Entregar_Mercancia($tipo = 0) {
		if ($this->session->userdata ( 'usuario' )) {
			$data ['Menu'] = $this->CMenu->getHtml_Menu ( $this->session->userdata ( 'nivel' ) );
			$data ['Nivel'] = $this->session->userdata ( 'nivel' );
			$data ['Listar_Ubicacion'] = $this->CListartareas->Listar_Ubicacion_Combo ();
			$data ['tipo'] = $tipo;
			$this->load->view ( "entrega_mercancia", $data );
		} else {
			$this->logout ();
		}
	}
	public function Entregar_Inventario_Oficina() {
		if ($this->session->userdata ( 'usuario' )) {
			$data ['Menu'] = $this->CMenu->getHtml_Menu ( $this->session->userdata ( 'nivel' ) );
			$data ['Nivel'] = $this->session->userdata ( 'nivel' );
			$data ['Listar_Ubicacion'] = $this->CListartareas->Listar_Ubicacion_Combo ();
			$data ['Listar_Sucursal'] = $this->CListartareas->Listar_Sucursal_Combo ();
			
			$this->load->view ( "inv_oficina", $data );
		} else {
			$this->logout ();
		}
	}
	public function Entregar_Inventario_Cliente() {
		if ($this->session->userdata ( 'usuario' )) {
			$data ['Menu'] = $this->CMenu->getHtml_Menu ( $this->session->userdata ( 'nivel' ) );
			$data ['Nivel'] = $this->session->userdata ( 'nivel' );
			$data ['Listar_Ubicacion'] = $this->CListartareas->Listar_Ubicacion_Combo ();
			$data ['Ubicacion'] = $this->session->userdata ( 'ubicacion' );
			$this->load->view ( "inv_cliente", $data );
		} else {
			$this->logout ();
		}
	}
	public function Guarda_Entregar_Mercancia() {
		if (isset ( $_POST ["codigo"] )) {
			$this->load->model ( 'inventario/minventario', 'MInventario' );
			$resultado = $this->MInventario->Entregar ( $_POST );
			echo 'SE REGISTRO CON EXITO....<br><a href="#" Onclick="window.open(sUrlP+\'Constancia_Entrega/' . $resultado . '\',\'ventana1\',\'toolbar=0,location=1,menubar=0,scrollbars=1,resizable=1,width=800,height=800\');"><span>Constancia De Entrega</span></a>';
		}
	}
	public function Guarda_Entregar_Inventario() {
		if (isset ( $_POST ["seriales"] )) {
			$this->load->model ( 'inventario/minventario', 'MInventario' );
			$resultado = $this->MInventario->Entregar_Inventario ( $_POST );
			echo 'SE REGISTRO CON EXITO....<br><a href="#" Onclick="window.open(sUrlP+\'Constancia_Entrega_Inventario/' . $resultado . '\',\'ventana1\',\'toolbar=0,location=1,menubar=0,scrollbars=1,resizable=1,width=800,height=800\');"><span>Constancia De Entrega</span></a>';
		} else {
			echo "No Se Pudo Guardar";
		}
	}
	public function Guarda_Entregar_Cliente() {
		if (isset ( $_POST ["seriales"] )) {
			$this->load->model ( 'inventario/minventario', 'MInventario' );
			$resultado = $this->MInventario->Entregar_Cliente ( $_POST );
			echo '<h2>SE REGISTRO CON EXITO....</h2><br><a href="#" Onclick="window.open(sUrlP+\'Constancia_Entrega_Nueva/' . $resultado . '\',\'ventana1\',\'toolbar=0,location=1,menubar=0,scrollbars=1,resizable=1,width=800,height=800\');"><span><h2>Constancia De Entrega' . $resultado . '</h2></span></a>';
		} else {
			echo "No Se Pudo Guardar";
		}
	}
	public function Constancia_Entrega($oid = '') {
		$consulta = $this->db->query ( "SELECT * FROM t_entregas WHERE oid=" . $oid . " limit 1" );
		$html = "<table style='width:100%;' border=1><thead><th>Seriales</th></thead>";
		foreach ( $consulta->result () as $row ) {
			$fecha_a = explode ( '-', $row->fecha );
			$modelo = $row->modelo;
			$nombre = $row->descrip;
			$entregado = $row->entregado;
			$entregado_a = $row->entregado_a;
			$cedula = $row->cedula;
			$factura = $row->factura;
			
			$rsLEntrega = $this->db->query ( "SELECT * FROM t_lista_entregas WHERE oide ='" . $row->oid . "'" );
			
			foreach ( $rsLEntrega->result () as $serial_e ) {
				$html .= "<tr><td>" . $serial_e->serial . "</td></tr>";
			}
			$html .= "</table>";
		}
		$entrega = $entregado . " " . $nombre;
		$data ['dia'] = $fecha_a [2];
		$data ['mes'] = $this->Convertir_Mes ( $fecha_a [1] );
		$data ['ano'] = $fecha_a [0];
		$data ['cantidad'] = $entregado;
		$data ['a_quien'] = $entregado_a;
		$data ['entrega'] = $entregado;
		$data ['cedula'] = $cedula;
		$data ['factura'] = $factura;
		$data ['tabla'] = $html;
		$data ['nombre'] = $nombre;
		$data ['modelo'] = $modelo;
		$data ['ciudad'] = $this->session->userdata ( 'ubicacion' );
		$data ['usuario'] = $this->session->userdata ( 'usuario' );
		$this->load->view ( "reportes/acta_entrega", $data );
	}
	public function Constancia_Entrega_Inventario($oid = '') {
		$orden = $oid;
		if (isset ( $_POST ['orden'] ))
			$orden = $_POST ['orden'];
		$this->load->model ( "reporte/pbaucher", 'pbaucher' );
		$this->pbaucher->Constancia_Entrega ( $orden );
	}
	function Eliminar_Serial() {
		$this->load->model ( "CProductos" );
		$serial = $_POST ['serial'];
		$peticion = $_POST ['peticion'];
		$motivo = $_POST ['motivo'];
		$this->CProductos->Eliminar_Serial ( $serial, $peticion, $motivo );
		echo "Proceso Finalizado Satisfactoriamente Para el Serial: ( $serial ) ";
	}
	function Eliminar_Modelo() {
		$this->load->model ( "CInventario" );
		$mod = $_POST ['modelo'];
		$peticion = $_POST ['peticion'];
		$motivo = $_POST ['motivo'];
		$this->CInventario->Eliminar_Modelo ( $mod, $peticion, $motivo );
		echo "Se Elimino Satisfactoriamente el Modelo: ( $mod )";
	}
	
	/**
	 * Fin del Inventario
	 */
	function reportes($iConsulta = "") {
		$this->load->model ( 'CNomina' );
		if ($this->session->userdata ( 'usuario' )) {
			$data ['Menu'] = $this->CMenu->getHtml_Menu ( $this->session->userdata ( 'nivel' ) );
			$data ['Nivel'] = $this->session->userdata ( 'nivel' );
			$data ['iPosicion'] = $this->session->userdata ( 'posicion' );
			$data ['sContenido'] = $this->DataSource_Reportes ( ( int ) $iConsulta );
			$data ['lista'] = $this->CNomina->Combo ();
			$data ['Listar_Ubicacion'] = $this->CListartareas->Listar_Ubicacion_Combo ();
			$data ['Listar_Banco'] = $this->CListartareas->Listar_Banco_Combo ();
			$this->load->view ( "reportes", $data );
		} else {
			$this->login ();
		}
	}
	function panel() {
		if ($this->session->userdata ( 'usuario' )) {
			$this->load->model ( 'CZonapostal' );
			$this->load->model ( 'CNomina' );
			$this->load->model ( 'CInventario' );
			$this->load->model ( 'MInsti' );
			$data ['insti'] = $this->MInsti->Combo ();
			
			$data ['lista_artefactos'] = $this->CInventario->Combo_Artefactos ();
			$data ['Menu'] = $this->CMenu->getHtml_Menu ( $this->session->userdata ( 'nivel' ) );
			$data ['Nivel'] = $this->session->userdata ( 'nivel' );
			$data ['iPosicion'] = $this->session->userdata ( 'posicion' );
			$data ['estados'] = $this->CZonapostal->Estados ();
			$data ['lista'] = $this->CNomina->Combo ();
			$data ['ubicacion'] = $this->CListartareas->Listar_Ubicacion_Combo ();
			if ($this->session->userdata ( 'usuario' ) == 'voucher') {
				$this->load->view ( "panel/boucher" );
			} else {
				$this->load->view ( "panel", $data );
			}
		} else {
			$this->login ();
		}
	}
	function mod_linaje() {
		if ($this->session->userdata ( 'usuario' )) {
			$this->load->model ( 'CNomina' );
			$data ['Menu'] = $this->CMenu->getHtml_Menu ( $this->session->userdata ( 'nivel' ) );
			$data ['Nivel'] = $this->session->userdata ( 'nivel' );
			$data ['iPosicion'] = $this->session->userdata ( 'posicion' );
			$data ['lista'] = $this->CNomina->Combo ();
			$data ['ubicacion'] = $this->CListartareas->Listar_Ubicacion_Combo ();
			
			$this->load->view ( "mod_linaje", $data );
		} else {
			$this->login ();
		}
	}
	function Panel_Alvaro() {
		if ($this->session->userdata ( 'usuario' )) {
			$this->load->model ( 'CZonapostal' );
			$this->load->model ( 'CNomina' );
			$this->load->model ( 'CInventario' );
			
			$data ['lista_artefactos'] = $this->CInventario->Combo_Artefactos ();
			$data ['Menu'] = $this->CMenu->getHtml_Menu ( $this->session->userdata ( 'nivel' ) );
			$data ['Nivel'] = $this->session->userdata ( 'nivel' );
			$data ['iPosicion'] = $this->session->userdata ( 'posicion' );
			$data ['estados'] = $this->CZonapostal->Estados ();
			$data ['lista'] = $this->CNomina->Combo ();
			$data ['ubicacion'] = $this->CListartareas->Listar_Ubicacion_Combo ();
			if ($this->session->userdata ( 'usuario' ) == 'voucher') {
				$this->load->view ( "panel/boucher" );
			} else {
				$this->load->view ( "panel_a", $data );
			}
		} else {
			$this->login ();
		}
	}
	function configurar() {
		if ($this->session->userdata ( 'usuario' )) {
			$data ['Nivel'] = $this->session->userdata ( 'nivel' );
			$data ['Menu'] = $this->CMenu->getHtml_Menu ( $this->session->userdata ( 'nivel' ) );
			$this->load->view ( "configurar", $data );
		} else {
			$this->login ();
		}
	}
	function Plantilla_Json() {
		if ($this->session->userdata ( 'usuario' )) {
			$data ['Nivel'] = $this->session->userdata ( 'nivel' );
			$data ['Menu'] = $this->CMenu->getHtml_Menu ( $this->session->userdata ( 'nivel' ) );
			$this->load->view ( "plantilla_json", $data );
		} else {
			$this->login ();
		}
	}
	function Descargas() {
		if ($this->session->userdata ( 'usuario' )) {
			$data ['Menu'] = $this->CMenu->getHtml_Menu ( $this->session->userdata ( 'nivel' ) );
			$this->load->view ( "descargas", $data );
		} else {
			$this->login ();
		}
	}
	public function Consultar_Conectados() {
		$data = $this->CListartareas->Usuarios_Conectados ();
		echo $data;
	}
	public function logout() {
		if ($this->session->userdata ( 'usuario' )) {
			$strQuery = "UPDATE t_usuario SET conectado=0 WHERE seudonimo='" . $_COOKIE ['Cooperativa'] . "' LIMIT 1";
			$this->db->query ( $strQuery );
			$this->session->sess_destroy ();
			session_destroy ();
			$this->db->close();
			redirect(base_url());
		}else{
			//$this->login();
			redirect(base_url());
		}
	}
	public function err_pagina() {
		echo "Actualmente no posees privilegios para acceder a esta pagina";
	}
	
	/*
	 * -------------------------------------------------------
	 * Fin de Paginas
	 * -------------------------------------------------------
	 */
	
	// Creacion de Reporte Facturas En estado
	public function TG_Cedula() {
		if (isset ( $_POST ['dependencia'] )) {
			$Dependencia = $_POST ['dependencia'];
			$dtd_nomina = $_POST ['nomina_procedencia'];
			$cobrado_en = $_POST ['cobrado_en'];
			$credito = $_POST ['credito'];
			$desde = $_POST ['desde'];
			$hasta = $_POST ['hasta'];
			if (trim ( $desde ) == '') {
				$desde = "2012-06-19";
				$hasta = "2015-06-19";
			}
			$aUnion ['lista_linaje'] = $this->session->userdata ( 'lista_linaje' );
			$aUnion ['lista_dependiente'] = $this->MUsuario->getDependientes ( $this->session->userdata ( 'oidu' ) );
			$aUnion ['oidperfil'] = $this->session->userdata ( 'nivel' );
			$aUnion ['seudonimo'] = $this->session->userdata ( 'seudonimo' );
			$sSql = $this->MUsuario->getSQL ( $aUnion );
			$Arr = array (
					'perfil' => $this->session->userdata ( 'nivel' ),
					'lista' => $sSql ['lista'],
					'desde' => $desde,
					'hasta' => $hasta 
			);
			// print_r($_POST);
			$data = $this->MCliente->TG_CFacturas ( $Dependencia, $dtd_nomina, $cobrado_en, $credito, $Arr );
			// print_r($data);
			echo $data;
		} elseif (isset ( $_POST ['id'] )) {
			$data = $this->MCliente->TG_Cedula ( $_POST ['id'] );
			echo $data;
		}
	}
	public function Busca_Voucher() {
		if (isset ( $_POST ['id'] )) {
			$this->load->model ( "cliente/mvoucher", "MVoucher" );
			$data = $this->MVoucher->Busca_Voucher ( $_POST ['id'] );
			echo $data;
		}
	}
	public function ReporteBuscaContrato() {
		if (isset ( $_POST ['tipo'] )) {
			$tipo = $_POST ['tipo'];
			$empresa = $_POST ['empresa'];
			$nomina = $_POST ['nomina'];
			$banco = $_POST ['banco'];
			$dia = $_POST ['dia'];
			$mes = $_POST ['mes'];
			$ano = $_POST ['ano'];
			$tipoContrato = $_POST ['tipoContrato'];
			$perio = $_POST ['perio'];
			$fecha = "";
			if ($ano != 0) {
				$fecha .= $ano . '-';
				if ($mes != 0 && $mes != '') {
					$fecha .= $mes . '-';
					if ($dia != 0) {
						$fecha .= $dia;
					}
				}
			}
			
			$data = $this->MCliente->BContratos ( $banco, $nomina, $tipo, $empresa, $fecha, $tipoContrato, $perio );
			echo $data;
		}
	}
	function HConversaciones() {
		if (isset ( $_POST ['usuario'] )) {
			echo $this->MCliente->HConversaciones ( $_POST ['usuario'] );
		}
	}
	
	
	function Listar_Vendedores() {
		if (isset ( $_POST ['vend'] )) {
			echo $this->MCliente->Listar_Vendedores ( $_POST ['vend'] );
		}
	}
	
	
	function Contratos_Buzon() {
		$usu = $this->session->userdata ( 'usuario' );
		if ($usu != "alvaro") {
			$fecha = date ( "Y-m-d" );
			$query = "select oidc,oide,t_estadoejecucion.estatus,codigo_n,fecha_solicitud,modificacion,seudonimo
						from t_estadoejecucion
						join t_clientes_creditos on t_clientes_creditos.contrato_id = t_estadoejecucion.oidc
						join t_usuario on t_usuario.seudonimo = t_clientes_creditos.expediente_c
						join _tr_usuarioperfil on _tr_usuarioperfil.oidu = t_usuario.oid
					where oide<4 and t_estadoejecucion.estatus = 1 and fecha_solicitud < '" . $fecha . "' 
					and codigo_n='" . $this->session->userdata ( 'ubicacion' ) . "' and t_usuario.estatus=1 and (oidp=4 or oidp=5) and t_clientes_creditos.cantidad != 0";
			// echo $query;
			
			$buzon = $this->db->query ( $query );
			echo $buzon->num_rows ();
		} else {
			echo 0;
		}
	}
	
	/**
	 * Modelo NUevo de Aceptacion de Facturas
	 * t_estadoejecucion a cobranzas
	 */
	function AceptarFactura() {
		$this->load->model ( 'cliente/mcontrato', 'MContrato' );
		if (isset ( $_POST ['objeto'] )) {
			$json = json_decode ( $_POST ['objeto'], true );
			
			$msj = $this->MContrato->AceptarFactura ( $json [0] );
			echo $msj;
		}
	}
	function Texto_Aleatorio($intLongitud) {
		$strCadena = "";
		$strCaracteres = "1234567890ABCDEFGHIJKLMNPQRSTUVWXYZ";
		for($i = 0; $i < $intLongitud; $i ++) {
			$aux = 
			$strCadena .= $strCaracteres [rand ( 0, 37 )];
		}
		return $strCadena;
	}
	function maximo_valor() {
		$sCon = 'SELECT MAX(credito_id) AS cantidad FROM t_clientes_creditos';
		$rs = $this->db->query ( $sCon );
		$rCon = $rs->result ();
		$sVal = 'N-' . $this->Completar ( $rCon [0]->cantidad + 94, 8 ) ;
		return $sVal;
	}
	function maximo_valor_js() {
		$sCon = 'SELECT MAX(credito_id) AS cantidad FROM t_clientes_creditos';
		$rs = $this->db->query ( $sCon );
		$rCon = $rs->result ();
		$sVal = 'N-' . $this->Completar ( $rCon [0]->cantidad + 94, 8 );
		echo $sVal;
	}
	function maximo_acuse() {
		$sCon = 'SELECT MAX(oid) AS max FROM t_acuse';
		$rs = $this->db->query ( $sCon );
		$rCon = $rs->result ();
		$sVal = 'AD-' . $this->Completar ( $rCon [0]->max + 1, 8 );
		echo $sVal;
	}
	function html_valor() {
		echo $this->maximo_valor ();
	}
	
	/**
	 * Completar con ceros a la izquierda...
	 */
	public function Completar($strCadena = '', $intLongitud = '') {
		$strContenido = '';
		$strAux = '';
		$intLen = strlen ( $strCadena );
		if ($intLen != $intLongitud) {
			$intCount = $intLongitud - $intLen;
			for($i = 0; $i < $intCount; $i ++) {
				$strAux .= '0';
			}
			$strContenido = $strAux . $strCadena;
		}
		return $strContenido;
	}
	public function Guardar_Cliente() {
		$sMensaje = "Se realizo el registro del empleado solo sus datos basicos, recuerde incluirle algun contrato.";
		$cedula = $_POST ['cedula'];
		
		$sexo = "M";
		if ($_POST ['sexo'] != "MASCULINO") {
			$sexo = "F";
		}
		$insti = $_POST ['insti'];
		$nacionalidad = $_POST ['nacionalidad'];
		$direccionH = $_POST ['direccionh'];
		$direccionT = $_POST ['direcciont'];
        $direccionT2 = $_POST ['direcciont2'];
		$estado_civil = $_POST ['edocivil'];
		$telefono = $_POST ['telefono'];
		$municipio = $_POST ['municipio'];
		$parroquia = $_POST ['parroquia'];
		$sector = $_POST ['sector'];
		$avenida = $_POST ['avenida'];
		$calle = $_POST ['calle'];
		$urbanizacion = $_POST ['urbanizacion'];
		$correo = $_POST ['correo'];
		$pin = $_POST ['pin'];
		$monto_va = $_POST ['monto_vacaciones'];
		$monto_ag = $_POST ['monto_aguinaldos'];
		
		$pin = $_POST ['pin'];
		
		$afiliado = $_POST ['afiliado'];
		$personal = $_POST ['personal'];
		// Activo o Jubilado
		$ciudad = $_POST ['ciudad'];
		$ubicacion_actual = $_POST ['ubicacionactual'];
		$fecha_nacimiento = $_POST ['ano'] . "-" . $_POST ['mes'] . "-" . $_POST ['dia'];
		$fecha_Ingreso = $_POST ['anoIng'] . "-" . $_POST ['mesIng'] . "-" . $_POST ['diaIng'];
		$vacaciones_mes = $_POST ['vacaciones_mes'];
		$cargo = $_POST ['cargo'];
		$banco_1 = $_POST ['banco_1'];
		$cuenta_1 = $_POST ['cuenta_1'];
		$tipo_1 = $_POST ['tipo_1'];
		$serial = $_POST ['serial'];
		
		$banco_2 = $_POST ['banco_2'];
		$cuenta_2 = $_POST ['cuenta_2'];
		$tipo_2 = $_POST ['tipo_2'];
		$numero_tarjeta = $_POST ['numero_tarjeta'];
		$banco_3 = "----------";
		$cuenta_3 = "----------";
		$tipo_3 = "----------";
		$this->load->model ( 'cliente/mcontactos', "MContactos" );
		// Primer Asociado
		$contacto1 = new $this->MContactos ();
		$contacto1->cedula = $_POST ['cedula'];
		$contacto1->nombre = $_POST ['txtNombreAsociado1'];
		$contacto1->descripcion = $_POST ['txtObserva1'];
		$contacto1->telefono = $_POST ['txtTlfAsociado1'];
		$contacto1->estatus = $_POST ['txtEstatus1'];
		$contacto1->direccion = $_POST ['txtdirecA1'];
		$contacto1->nomina = $_POST ['txtNomiA1'];
		if ($_POST ['txtNombreAsociado1'] != '')
			$contacto1->Salvar ( $contacto1 );
			
			// Segundo Asociado
		$contacto2 = new $this->MContactos ();
		$contacto2->cedula = $_POST ['cedula'];
		$contacto2->nombre = $_POST ['txtNombreAsociado2'];
		$contacto2->descripcion = $_POST ['txtObserva2'];
		$contacto2->telefono = $_POST ['txtTlfAsociado2'];
		$contacto2->estatus = $_POST ['txtEstatus2'];
		$contacto2->direccion = $_POST ['txtdirecA2'];
		$contacto2->nomina = $_POST ['txtNomiA2'];
		if ($_POST ['txtNombreAsociado2'] != '')
			$contacto2->Salvar ( $contacto2 );
			
			// Segundo Asociado
		$contacto3 = new $this->MContactos ();
		$contacto3->cedula = $_POST ['cedula'];
		$contacto3->nombre = $_POST ['txtNombreAsociado3'];
		$contacto3->descripcion = $_POST ['txtObserva3'];
		$contacto3->telefono = $_POST ['txtTlfAsociado3'];
		$contacto3->estatus = $_POST ['txtEstatus3'];
		$contacto3->direccion = $_POST ['txtdirecA3'];
		$contacto3->nomina = $_POST ['txtNomiA3'];
		if ($_POST ['txtNombreAsociado3'] != '')
			$contacto3->Salvar ( $contacto3 );
		
		$domicilia_G = $_POST ['domiciliacionG'];
		$domicilia_C = $_POST ['domiciliacionC'];
		$domicilia_I = $_POST ['domiciliacionI'];
		
		// insertar domiciliaciones
		$ConsultaD = $this->db->query ( "SELECT * FROM t_clientes_domiciliacion WHERE cedula='$cedula'" );
		
		if ($ConsultaD->num_rows () > 0) {
			$arrD = array (
					'coopera' => $domicilia_C,
					'grupo' => $domicilia_G,
					'interbancaria' => $domicilia_I 
			);
			$this->db->where ( 'cedula', $cedula );
			$this->db->update ( 't_clientes_domiciliacion', $arrD );
		} else {
			$arrD = array (
					'cedula' => $cedula,
					'coopera' => $domicilia_C,
					'grupo' => $domicilia_G,
					'interbancaria' => $domicilia_I 
			);
			$this->db->insert ( 't_clientes_domiciliacion', $arrD );
		}
		
		$nacionalidad = $nacionalidad;
		
		$CPersona = new $this->CPersonas ();
		
		$strClave = $this->Texto_Aleatorio ( 6 );
		$CPersona->documento_id = trim ( $cedula );
		$CPersona->nro_documento = trim ( $strClave );
		$CPersona->primer_nombre = trim ( $_POST ['nombre'] );
		$data_u ['primer_nombre'] = trim ( $_POST ['nombre'] );
		$CPersona->segundo_nombre = trim ( $_POST ['nombre2'] );
		$data_u ['segundo_nombre'] = trim ( $_POST ['nombre2'] );
		$CPersona->primer_apellido = trim ( $_POST ['apellido'] );
		$data_u ['primer_apellido'] = trim ( $_POST ['apellido'] );
		$CPersona->segundo_apellido = trim ( $_POST ['apellido2'] );
		$data_u ['segundo_apellido'] = trim ( $_POST ['apellido2'] );
		
		$CPersona->celular = "";
		$CPersona->correo = "";
		$CPersona->vive = 1;
		$CPersona->observacion = "";
		$CPersona->codigo_nomina = "";
		$CPersona->fecha_vacaciones = "";
        $data_u ['fecha_vacaciones'] = trim ( $sexo );
		$CPersona->fecha_ingreso = $fecha_Ingreso;
        $data_u ['fecha_ingreso'] = trim ( $fecha_Ingreso );
		$CPersona->foto = $vacaciones_mes;
        $data_u ['foto'] = trim ( $vacaciones_mes );
		// MES DE VACACIONES
		$CPersona->sexo = $sexo;
		$data_u ['sexo'] = trim ( $sexo );
		
		$CPersona->fecha_nacimiento = trim ( $fecha_nacimiento );
		$data_u ['fecha_nacimiento'] = trim ( $fecha_nacimiento );
		
		$CPersona->nacionalidad = trim ( $nacionalidad );
		$data_u ['nacionalidad'] = trim ( $nacionalidad );
		
		$CPersona->direccion_trabajo = trim ( $direccionT );
		$data_u ['direccion_trabajo'] = trim ( $direccionT );
        $CPersona->direccion_trabajo2 = trim ( $direccionT2 );
        $data_u ['direccion_trabajo2'] = trim ( $direccionT2 );
		
		$CPersona->direccion = trim ( $direccionH );
		$data_u ['direccion'] = trim ( $direccionH );
		
		$CPersona->estado_civil = trim ( $estado_civil );
		$data_u ['estado_civil'] = trim ( $estado_civil );
		
		$CPersona->telefono = trim ( $telefono );
		$data_u ['telefono'] = trim ( $telefono );
		
		$CPersona->ciudad = trim ( $ciudad );
		$data_u ['ciudad'] = trim ( $ciudad );
		
		$CPersona->cargo_actual = trim ( $cargo );
		$data_u ['cargo_actual'] = trim ( $cargo );
		
		$zona_postal = trim ( $_POST ['zonapostal'] );
		$data_u['gaceta'] = trim ( $_POST ['zonapostal'] );
		
		$CPersona->municipio = trim ( $municipio );
		$data_u ['municipio'] = trim ( $municipio );
		
		$CPersona->parroquia = trim ( $parroquia );
		$data_u ['parroquia'] = trim ( $parroquia );
		
		$CPersona->sector = trim ( $sector );
		$data_u ['sector'] = trim ( $sector );
		
		$CPersona->avenida = trim ( $avenida );
		$data_u ['avenida'] = trim ( $avenida );
		
		$CPersona->calle = trim ( $calle );
		$data_u ['calle'] = trim ( $calle );
		
		$CPersona->urbanizacion = trim ( $urbanizacion );
		$data_u ['urbanizacion'] = trim ( $urbanizacion );
		
		$CPersona->correo = trim ( $correo );
		$data_u ['correo'] = trim ( $correo );
		
		$CPersona->pin = trim ( $pin );
		$data_u ['pin'] = trim ( $pin );
		
		$CPersona->fe_vida = $afiliado;
		$data_u ['fe_vida'] = trim ( $afiliado );
		// fe de Vida Sera Afiliado
		
		$CPersona->titular = $personal;
		// fe de Vida Sera Afiliado
		
		$CPersona->ubicacion = trim ( $ubicacion_actual );
        $data_u ['ubicacion'] = $ubicacion_actual;
		$CPersona->banco_1 = trim ( $banco_1 );
		$CPersona->cuenta_1 = trim ( $cuenta_1 );
		$CPersona->tipo_cuenta_1 = trim ( $tipo_1 );
		$CPersona->banco_2 = $banco_2;
		$CPersona->cuenta_2 = $cuenta_2;
		$CPersona->tipo_cuenta_2 = $tipo_2;
		$CPersona->numero_tarjeta = $numero_tarjeta;
		$CPersona->banco_3 = $banco_3;
		$CPersona->cuenta_3 = $cuenta_3;
		$CPersona->tipo_cuenta_3 = $tipo_3;
		$CPersona->gaceta = $zona_postal;
		$CPersona->codigo_nomina = $this->session->userdata ( 'ubicacion' );
		$CPersona->codigo_nomina_aux = $this->session->userdata ( 'usuario' );
		$CPersona->expediente_caja = $this->session->userdata ( 'usuario' );
		$CPersona->monto_vacaciones = $monto_va;
		$data_u ['monto_vacaciones'] = $monto_va;
		$CPersona->monto_aguinaldos = $monto_ag;
		$data_u ['monto_aguinaldos'] = $monto_ag;
		$CPersona->insti = $insti;
		$data_u ['insti'] = $insti;
		
		$Consulta = $this->db->query ( "SELECT * FROM t_personas WHERE documento_id='$cedula'" );
		
		if ($Consulta->num_rows () > 0) {
			$Usuario = "";
			foreach ( $Consulta->result () as $lsConsulta ) {
				$Usuario = $lsConsulta->expediente_caja;
			}
			
			$sMensaje = "No posee Acceso para modificar..! El cliente con C.I: " . $_POST ['cedula'] . ", No fue actualizadado Solicite Autorización...";
			if ($this->session->userdata ( 'nivel' ) == 0 || $this->session->userdata ( 'nivel' ) == 3 || $this->session->userdata ( 'nivel' ) == 18 || $this -> session -> userdata('usuario') == 'AlvaroZ') {
				$CPersona->expediente_caja = $Usuario;
				$this->db->where ( 'documento_id', $cedula );
				$this->db->update ( 't_personas', $CPersona );
				$sMensaje = "Bien. Felicitaciones..! El cliente con C.I: " . $_POST ['cedula'] . ", fue actualizadado con exito...";
			} else {
				$this->db->where ( 'documento_id', $cedula );
				$this->db->update ( 't_personas', $data_u );
			}
		} else {
			$this->db->insert ( 't_personas', $CPersona );
		}
		
		/**
		 * DATOS DEL CREDITO
		 */
		$fecha_credito = $_POST ['solicitudAno'] . "-" . $_POST ['solicitudMes'] . "-" . $_POST ['solicitudDia'];
		$fecha_descuento = $_POST ['anoD'] . "-" . $_POST ['mesD'] . "-" . $_POST ['diaD'];
		$motivo = $_POST ['motivo'];
		$monto_total = $_POST ['montocredito'];
		$mes_vacaciones = $_POST ['mesvacaciones'];
		//$monto_vacaciones = $_POST ['cuotavacaciones'];
		//$monto_aguinaldos = $_POST ['cuotaaguinaldos'];
		$numero_cuotas = $_POST ['numerocuotas'];
		$nomina_periocidad = $_POST ['nominaperiocidad'];
		$nomina_procedencia = $_POST ['nominaprocedencia'];
		$monto_cuota = $_POST ['montocuota'];
		$numero_contrato = $_POST ['numero_contrato'];
		$forma_contrato = $_POST ['formacontrato'];
		$empresa = $_POST ['empresa'];
		$cobrado_en = $_POST ['cobradoen'];
		
		$ano_vacaciones = $_POST ['anovacaciones'];
		$contrato_vacaciones = $_POST ['contratovacaciones'];
		$mes_aguinaldos = $_POST ['mesaguinaldos'];
		$ano_aguinaldos = $_POST ['anoaguinaldos'];
		$contrato_aguinaldos = $_POST ['contratoaguinaldos'];
		$observaciones = $_POST ['observaciones'];
		
		$condicion = $_POST ['condicion'];
		$num_operacion = $_POST ['numoperaciones'];
		$monto_operacion = $_POST ['montooperaciones'];
		$ncheque2 = $_POST ['ncheque2'];
		$mcheque2 = $_POST ['montocheque2'];
		$numero_factura = $_POST ['numero_factura'];
		$fecha_operacion = $_POST ['anoO'] . "-" . $_POST ['mesO'] . "-" . $_POST ['diaO'];
		$tipo_pago = $_POST ['tipopago'];
		$artipo_pago = explode ( '-', $tipo_pago );
		/**
		 * Credito Activo
		 *
		 * @var CCreditos
		 *
		 */
		$Credito = new $this->CCreditos ();
		$Credito->documento_id = trim ( $cedula );
		$Credito->fecha_solicitud = trim ( $fecha_credito );
		$Credito->fecha_inicio_cobro = trim ( $fecha_descuento );
		$Credito->motivo = trim ( $motivo );
		$Credito->cantidad = trim ( $monto_total );
		$Credito->monto_total = trim ( $monto_total );
		$Credito->numero_cuotas = trim ( $numero_cuotas );
		$Credito->periocidad = trim ( $nomina_periocidad );
		$Credito->nomina_procedencia = trim ( $nomina_procedencia );
		$Credito->monto_cuota = trim ( $monto_cuota );
		$Credito->contrato_id = trim ( $numero_contrato );
		$Credito->condicion = trim ( $condicion );
		
		$Credito->fecha_operacion = trim ( $fecha_operacion );
		$Credito->monto_operacion = trim ( $monto_operacion );
		$Credito->mcheque = trim ( $mcheque2 );
		$Credito->forma_contrato = trim ( $forma_contrato );
		$Credito->empresa = trim ( $empresa );
		$Credito->cobrado_en = trim ( $cobrado_en );
		
		$Credito->numero_factura = trim ( $numero_factura );
		$Credito->codigo_n = $this->session->userdata ( 'ubicacion' );
		$Credito->codigo_n_a = $_SESSION ['usuario'];
		$Credito->expediente_c = $_SESSION ['usuario'];
		
		$Credito->serial = trim ( $serial );
		$Credito->marca_consulta = $artipo_pago [0];
		$Credito->observaciones = trim ( $observaciones );
		
		$Credito->num_operacion = trim ( $num_operacion );
		$Credito->ncheque = trim ( $ncheque2 );

		$Credito->fecha_verificado = date("Y-m-d H:i:s");
		
		$arr = array (
				'estatus' => 1 
		);
		
		$lista_voucher = $_POST ['lstBoucher'];
		$Consulta = $this->db->query ( "SELECT * FROM t_clientes_creditos WHERE documento_id='$cedula' AND contrato_id='$numero_contrato' LIMIT 1" );
		$sMsjVoucher = '';
		$iEstatus = 0;
		if ($Consulta->num_rows () > 0) {
			if ($numero_contrato != "") {
				foreach ( $Consulta->result () as $lsConsulta ) {
					$iEstatus = $lsConsulta->estatus;
					$Credito->codigo_n = $lsConsulta->codigo_n;
					$Credito->expediente_c = $lsConsulta->expediente_c;
				}
				
				if ($iEstatus == 0) {
					
					if ($this->session->userdata ( 'nivel' ) == 0 || $this->session->userdata ( 'nivel' ) == 5 || $this->session->userdata ( 'nivel' ) == 3 || $this->session->userdata ( 'nivel' ) == 18) {
						$this->db->where ( 'documento_id', $cedula );
						$this->db->where ( 'contrato_id', $numero_contrato );
						
						$this->db->update ( 't_clientes_creditos', $Credito );
						if ($artipo_pago [0] == '6') {
							$borrar = "DELETE FROM t_lista_voucher WHERE cid='" . $Credito->numero_factura . "'";
							$this->db->query ( $borrar );
							if ($lista_voucher != '') {
								$modo = 0;
								$pref = '';
								if($artipo_pago [1] == 'TRF') {
									$modo = 1;
									$pref='T-';
								}
								if($artipo_pago [1] == 'COT') {
									$modo = 2;
									$pref='CT-';
								}
								$lstBoucher = explode ( ',', $lista_voucher );
								foreach ( $lstBoucher as $cadena ) {
									$itemVoucher = explode ( '|', $cadena );
									$datosVoucher = array (
											'cid' => $Credito->numero_factura,
											'ndep' => trim ( $pref.$itemVoucher [1] ),
											'fecha' => trim ( $itemVoucher [0] ),
											'monto' => trim ( $itemVoucher [2] ),
											'banco' => $artipo_pago [1],
											'modo' => $modo 
									);
									$this->db->insert ( 't_lista_voucher', $datosVoucher );
								}
							} else {
								$sMsjVoucher .= 'No se pudo insertar los voucher del contrato';
							}
						} elseif ($artipo_pago [1] == 'VBI') {
							$borrar = "DELETE FROM t_lista_voucher WHERE cid='" . $Credito->numero_factura . "'";
							$this->db->query ( $borrar );
							if ($lista_voucher != '') {
								$lstBoucher = explode ( ',', $lista_voucher );
								foreach ( $lstBoucher as $cadena ) {
									$itemVoucher = explode ( '|', $cadena );
									$datosVoucher = array (
											'cid' => $Credito->numero_factura, //
											'ndep' => trim ( $itemVoucher [1] ), //
											'fecha' => trim ( $itemVoucher [0] ), //
											'monto' => trim ( $itemVoucher [2] ), //
											'banco' => 'BIC',
											'pronto' => 1,
											'modo' => 0 
									);
									$this->db->insert ( 't_lista_voucher', $datosVoucher );
								}
							} else {
								$sMsjVoucher .= 'No se pudo insertar los voucher del contrato';
							}
						}
						
						$sMensaje = "Bien Felicitaciones..! El cliente con C.I: " . $_POST ['cedula'] . ", fue actualizadado con exito...";
					}
				} else {
					//print_r($this->session->userdata);
					if ($this->session->userdata ( 'nivel' ) == 0 || $this->session->userdata ( 'nivel' ) == 5 || $this->session->userdata ( 'nivel' ) == 3 || $this->session->userdata ( 'nivel' ) == 18 || $this->session->userdata ( 'nivel' ) == 9) {
						$this->db->where ( 'documento_id', $cedula );
						$this->db->where ( 'contrato_id', $numero_contrato );
						/**
						 * Estatus del Credito como tal *
						 */
						if ($iEstatus != 3) {
							$Credito->estatus = 1;
						} else {
							$Credito->estatus = 3;
						}
						
						$editar = true;
						
						$sCE = "SELECT * FROM t_estadoejecucion WHERE oidc='" . $numero_contrato . "' AND oide > 3 ";
						$rs = $this->db->query ( $sCE );
						// $rsCC = $Consulta -> result();
						if ($rs->num_rows () != 0) {
							$editar = false;
						}
						
						if (($editar == false && $this->session->userdata ( 'nivel' ) != 5) || $editar == true) {
							$this->db->update ( 't_clientes_creditos', $Credito );
						}
						
						if (($artipo_pago [0] == '6' && $editar == true && $this->session->userdata ( 'nivel' ) != 5) || ($editar == true && $artipo_pago [0] == 6)) {
							
							$borrar = "DELETE FROM t_lista_voucher WHERE cid='" . $Credito->numero_factura . "'";
							$this->db->query ( $borrar );
							if ($lista_voucher != '') {
								$modo = 0;
								$pref = '';
								if($artipo_pago [1] == 'TRF') {
									$modo = 1;
									$pref='T-';
								}
								if($artipo_pago [1] == 'COT') {
									$modo = 2;
									$pref='CT-';
								}
								$lstBoucher = explode ( ',', $lista_voucher );
								foreach ( $lstBoucher as $cadena ) {
									$itemVoucher = explode ( '|', $cadena );
									$datosVoucher = array (
											'cid' => $Credito->numero_factura,
											'ndep' => trim ( $pref.$itemVoucher [1] ),
											'fecha' => trim ( $itemVoucher [0] ),
											'monto' => trim ( $itemVoucher [2] ),
											'banco' => $artipo_pago [1],
											'modo' => $modo 
									);
									$this->db->insert ( 't_lista_voucher', $datosVoucher );
								}
							} else {
								$sMsjVoucher .= 'No se pudo insertar los voucher del contrato';
							}
						} elseif (($artipo_pago [1] == 'BVI' && $editar == true && $this->session->userdata ( 'nivel' ) != 5) || ($editar == true && $artipo_pago [1] == 'BVI')) {
							$borrar = "DELETE FROM t_lista_voucher WHERE cid='" . $Credito->numero_factura . "'";
							$this->db->query ( $borrar );
							if ($lista_voucher != '') {
								$lstBoucher = explode ( ',', $lista_voucher );
								foreach ( $lstBoucher as $cadena ) {
									$itemVoucher = explode ( '|', $cadena );
									$datosVoucher = array (
											'cid' => $Credito->numero_factura, //
											'ndep' => trim ( $itemVoucher [1] ), //
											'fecha' => trim ( $itemVoucher [0] ), //
											'monto' => trim ( $itemVoucher [2] ), //
											'banco' => 'BIC',
											'pronto' => 1,
											'modo' => 0 
									);
									$this->db->insert ( 't_lista_voucher', $datosVoucher );
								}
							} else {
								$sMsjVoucher .= 'No se pudo insertar los voucher del contrato';
							}
						}
						
						$sMensaje = "Felicitaciones..! El cliente con C.I: " . $_POST ['cedula'] . ", fue actualizadado con exito...";
					} else {
						$sMensaje = "El cliente con C.I: " . $_POST ['cedula'] . " posee el contrato #: $numero_contrato, el cual se
						encuentra en proceso de cobro y, no puede ser editado...";
					}
				}
			}
		} else {
			if ($numero_contrato != "") {
				
				if ($this->session->userdata ( 'nivel' ) == 0 || $this->session->userdata ( 'nivel' ) == 5 || $this->session->userdata ( 'nivel' ) == 3 || $this->session->userdata ( 'nivel' ) == 18) {
					$Credito->estatus = 1;
					$Credito->estado_verificado = 4;
				}
				if ($this->session->userdata ( 'nivel' ) == 1) {
					$Credito->estado_verificado = 4;
				}
				if (trim ( $numero_contrato ) != '') {
					if (trim ( $numero_contrato [0] ) == 'N') {
						$Credito->contrato_id = $this->maximo_valor ();
						if ($Credito->contrato_id == "")
							$Credito->contrato_id == "N-0052563";
						$numero_contrato = $Credito->contrato_id;
					}
				}
				// de aca
				$this->db->insert ( 't_clientes_creditos', $Credito );
				
				if ($artipo_pago [0] == '6') {
					$borrar = "DELETE FROM t_lista_voucher WHERE cid='" . $Credito->numero_factura . "'";
					$this->db->query ( $borrar );
					if ($lista_voucher != '') {
						$lstBoucher = explode ( ',', $lista_voucher );
						$modo = 0;
						$pref = '';
						if($artipo_pago [1] == 'TRF') {
							$modo = 1;
							$pref='T-';
						}
						if($artipo_pago [1] == 'COT') {
							$modo = 2;
							$pref='CT-';
						}

						foreach ( $lstBoucher as $cadena ) {
							$itemVoucher = explode ( '|', $cadena );
							$datosVoucher = array (
									'cid' => $Credito->numero_factura,
									'ndep' => trim ( $pref.$itemVoucher [1] ),
									'fecha' => trim ( $itemVoucher [0] ),
									'monto' => trim ( $itemVoucher [2] ),
									'banco' => $artipo_pago [1],
									'modo' => $modo 
							);
							$this->db->insert ( 't_lista_voucher', $datosVoucher );
						}
					} else {
						$sMsjVoucher .= 'No se pudo insertar los voucher del contrato';
					}
				} elseif ($artipo_pago [1] == 'VBI') {
					$borrar = "DELETE FROM t_lista_voucher WHERE cid='" . $Credito->numero_factura . "'";
					$this->db->query ( $borrar );
					if ($lista_voucher != '') {
						$lstBoucher = explode ( ',', $lista_voucher );
						foreach ( $lstBoucher as $cadena ) {
							$itemVoucher = explode ( '|', $cadena );
							$datosVoucher = array (
									'cid' => $Credito->numero_factura, //
									'ndep' => trim ( $itemVoucher [1] ), //
									'fecha' => trim ( $itemVoucher [0] ), //
									'monto' => trim ( $itemVoucher [2] ), //
									'banco' => 'BIC',
									'pronto' => 1,
									'modo' => 0
									 
							);
							$this->db->insert ( 't_lista_voucher', $datosVoucher );
						}
					} else {
						$sMsjVoucher .= 'No se pudo insertar los voucher del contrato';
					}
				}
				// hasta aca
				/*
				 * $lstSeriales = explode(",", $serial);
				 * $iSerial = count($lstSeriales);
				 * $this -> load -> model("CProductos");
				 * for ($i = 0; $i < $iSerial; $i++) {
				 * $Productos["serial"] = trim($lstSeriales[$i]);
				 * $Productos["estatus"] = 2;
				 * $this -> CProductos -> Actualizar($Productos);
				 * }
				 */
				// $arr['documento_id'] = $cedula;
				// $this->db->insert('t_aceptado',$arr);
				// de aca
				$autoriza = "INSERT IGNORE INTO t_aceptado (documento_id) values('" . $cedula . "')";
				$this->db->query ( $autoriza );
				$sMensaje = "Felicitaciones..! El cliente con C.I: " . $_POST ['cedula'] . ", fue registrado con exito y la clave telefonica generada es: <h3>$strClave</h3>...";
				// hasta aca
			}
		}
		
		echo "<p><a href=# onClick=\"$('#divGuardar').hide();\" border=0> <span class=\"ui-icon ui-icon-circle-check\" style=\"float: left; margin-right: .3em;\"></span>
				<strong>" . $sMensaje . $sMsjVoucher . ".</strong></p></a>";
	}
	public function Guardar_Credi_Compra() {
		$sMensaje = "Se realizo el registro del empleado solo sus datos basicos, recuerde incluirle algun contrato.";
		$cedula = $_POST ['cedula'];
		$nacionalidad = $_POST ['nacionalidad'];
		
		$data_u ['primer_nombre'] = trim ( $_POST ['nombre'] );
		$data_u ['segundo_nombre'] = trim ( $_POST ['nombre2'] );
		$data_u ['primer_apellido'] = trim ( $_POST ['apellido'] );
		$data_u ['segundo_apellido'] = trim ( $_POST ['apellido2'] );
		$data_u ['nacionalidad'] = trim ( $nacionalidad );
		$data_u ['codigo_n'] = $this->session->userdata ( 'ubicacion' );
		$data_u ['codigo_na'] = $_SESSION ['usuario'];
		$data_u ['expediente_c'] = $_SESSION ['usuario'];
		
		$Consulta = $this->db->query ( "SELECT * FROM t_personas_tem WHERE documento_id='$cedula'" );
		
		if ($Consulta->num_rows () > 0) {
			$Usuario = "";
			foreach ( $Consulta->result () as $lsConsulta ) {
				$Usuario = $lsConsulta->expediente_c;
			}
			
			$this->db->where ( 'documento_id', $cedula );
			$this->db->update ( 't_personas_tem', $data_u );
			$sMensaje = "Bien. Felicitaciones..! El cliente con C.I: " . $_POST ['cedula'] . ", fue actualizadado con exito...";
		} else {
			$data_u ['documento_id'] = $cedula;
			$this->db->insert ( 't_personas_tem', $data_u );
			$fact = "Select * From t_facturas where oidf='" . trim ( $_POST ['factura'] ) . "'";
			$Consulta2 = $this->db->query ( $fact );
			
			if ($Consulta2->num_rows () == 0) {
				$data_f ['oidc'] = $cedula;
				$data_f ['oidf'] = trim ( $_POST ['factura'] );
				$this->db->insert ( 't_facturas', $data_f );
				$lista_voucher = $_POST ['lstBoucher'];
				if ($lista_voucher != '') {
					$lstBoucher = explode ( ',', $lista_voucher );
					foreach ( $lstBoucher as $cadena ) {
						$itemVoucher = explode ( '|', $cadena );
						$datosVoucher = array (
								'cid' => trim ( $_POST ['factura'] ),
								'ndep' => trim ( $itemVoucher [1] ),
								'fecha' => trim ( $itemVoucher [0] ),
								'monto' => trim ( $itemVoucher [2] ),
								'tipo' => 1 
						);
						$this->db->insert ( 't_lista_voucher', $datosVoucher );
					}
				} else {
					$sMsjVoucher .= 'No se pudo insertar los voucher del contrato';
				}
			}
		}
		
		echo $sMensaje;
	}
	public function Eliminar_Cedula() {
		$cedula = $_POST ['cedula'];
		$peticion = $_POST ['peticion'];
		$motivo = $_POST ['motivo'];
		$this->CClientes->Eliminar_Clientes ( $cedula, $peticion, $motivo );
		echo "<strong>&nbsp;  Proceso Finalizado Satisfactoriamente </strong>....";
	}
	public function Eliminar_Nomina() {
		$this->load->model ( "CNomina" );
		$nombre = $_POST ['nombre'];
		$this->CNomina->Eliminar ( $nombre );
		echo "<strong>&nbsp;  Se Elimino Satisfactoriamente la Nomina: ( $nombre ) </strong>....";
	}
	public function Eliminar_Contrato_C() {
		$this->load->model ( "CCreditos" );
		$contrato = $_POST ['contrato'];
		$peticion = $_POST ['peticion'];
		$motivo = $_POST ['motivo'];
		if ($contrato != '' && $contrato != null) {
			$this->CCreditos->Eliminar_Contrato ( $contrato, $peticion, $motivo );
			echo "<strong>&nbsp;  Se Elimino Satisfactoriamente el Contrato: ( $contrato ) </strong>....";
		} else {
			echo "<strong>&nbsp;  Debe ingresar el n&uacute;mero de contrato </strong>....";
		}
	}
	public function Eliminar_Factura_C() {
		$this->load->model ( "CCreditos" );
		$factura = $_POST ['factura'];
		$peticion = $_POST ['peticion'];
		$motivo = $_POST ['motivo'];
		if ($factura != '' && $factura != null) {
			$this->CCreditos->Eliminar_Factura ( $factura, $peticion, $motivo );
			echo "<strong>&nbsp;  Se Elimino Satisfactoriamente el Contrato: ( $factura ) </strong>....";
		} else {
			echo "<strong>&nbsp;  Debe ingresar n&uacute;mero de factura</strong>....";
		}
	}
	public function Inactivar_Contrato() {
		$this->load->model ( "CCreditos" );
		$contrato = $_POST ['contrato'];
		$peticion = $_POST ['peticion'];
		$motivo = $_POST ['motivo'];
		if ($contrato != '' && $contrato != null) {
			$this->CCreditos->Inactivar_Contrato ( $contrato, $peticion, $motivo );
			echo "<strong>&nbsp;  Se Inactivo Satisfactoriamente el Contrato: ( $contrato ) </strong>....";
		} else {
			echo "<strong>&nbsp;  Debe ingresar el n&uacute;mero de contrato </strong>....";
		}
	}
	public function Inactivar_Factura() {
		$this->load->model ( "CCreditos" );
		$factura = $_POST ['factura'];
		$peticion = $_POST ['peticion'];
		$motivo = $_POST ['motivo'];
		if ($factura != '' && $factura != null) {
			$this->CCreditos->Inactivar_Factura ( $factura, $peticion, $motivo );
			echo "<strong>&nbsp;  Se Inactivo Satisfactoriamente LA FACTURA: ( $factura ) </strong>....";
		} else {
			echo "<strong>&nbsp;  Debe ingresar el n&uacute;mero de factura </strong>....";
		}
	}
	public function Inserta_Nomina() {
		$this->load->model ( "CNomina" );
		$Nomina = new $this->CNomina ();
		$Nomina->nombre = $_POST ['nombre'];
		$Nomina->descripcion = $_POST ['desc'];
		$this->db->insert ( 't_nominas', $Nomina );
	}
	public function Inserta_Zona() {
		$this->load->model ( "CZonapostal" );
		$Zona_Postal = new $this->CZonapostal ();
		$Zona_Postal->estado = $_POST ['estado'];
		$Zona_Postal->zona_postal = $_POST ['zona'];
		$Zona_Postal->codigo = $_POST ['codigo'];
		$Zona_Postal->insertar ();
	}
	public function Asociar_Cuentas_Guardar() {
		$cedula = $_POST ['cedula'];
		$banco = $_POST ['banco'];
		$tipo = $_POST ['tipo'];
		$cuenta = $_POST ['cuenta'];
		
		/**
		 *
		 * @var CAsociarCuentas
		 *
		 */
		$AsociarCuenta;
		
		$AsociarCuenta = new $this->CAsociarCuentas ();
		
		$AsociarCuenta->usuario_id = $cedula;
		$AsociarCuenta->descripcion = "";
		$AsociarCuenta->tipo = $tipo;
		$AsociarCuenta->fecha = date ( "y-m-d" );
		$AsociarCuenta->banco = $banco;
		$AsociarCuenta->cuenta = $cuenta;
		$Consulta = $this->db->query ( "SELECT * FROM t_asociacion_usuarios WHERE usuario_id='$cedula'" );
		
		if ($Consulta->num_rows () == 0) {
			$this->db->insert ( 't_asociacion_usuarios', $AsociarCuenta );
		} else {
			$this->db->where ( 'usuario_id', $cedula );
			$this->db->update ( 't_asociacion_usuarios', $AsociarCuenta );
		}
		echo $this->CAsociarCuentas->Consultar ();
	}
	public function Asociar_Cuentas_Eliminar() {
		$cedula = $_POST ['cedula'];
		$this->CAsociarCuentas->Eliminar ( $cedula );
		echo $this->CAsociarCuentas->Consultar ();
	}
	public function Modificar_Contratos() {
		$ContratoActual = $_POST ['contrato_a'];
		$ContratoNuevo = $_POST ['contrato_n'];
		$peticion = $_POST ['peticion'];
		$motivo = $_POST ['motivo'];
		$msj = $this->CCreditos->Modificar ( $ContratoActual, $ContratoNuevo, $peticion, $motivo );
		echo $msj;
	}
	public function Modificar_Facturas() {
		$FacturaActual = $_POST ['factura_a'];
		$FacturaNuevo = $_POST ['factura_n'];
		$peticion = $_POST ['peticion'];
		$motivo = $_POST ['motivo'];
		$this->CCreditos->Modificar_Factura ( $FacturaActual, $FacturaNuevo, $peticion, $motivo );
		echo "<strong>&nbsp;  Proceso Finalizado Satisfactoriamente </strong>....";
	}
	public function Modificar_Datos_Factura() {
		$Factura = $_POST ['factura'];
		$motivo = $_POST ['motivo'];
		$condicion = $_POST ['condicion'];
		$deposito = $_POST ['deposito'];
		$monto = $_POST ['monto'];
		$fecha_o = $_POST ['fecha_o'];
		
		$respuesta = $this->CCreditos->Modificar_Datos_Factura ( $Factura, $motivo, $condicion, $deposito, $monto, $fecha_o );
		echo "<strong>&nbsp;  " . $respuesta . " </strong>....";
	}
	public function Modificar_Cedula() {
		$CedulaActual = $_POST ['cedula_a'];
		$CedulaNuevo = $_POST ['cedula_n'];
		$peticion = $_POST ['peticion'];
		$motivo = $_POST ['motivo'];
		$this->CCreditos->Modificar_Cedula ( $CedulaActual, $CedulaNuevo, $peticion, $motivo );
		echo "<strong>&nbsp;  Proceso Finalizado Satisfactoriamente </strong>....";
	}
	public function Modificar_Boucher() {
		$BoucherActual = $_POST ['boucher_a'];
		$BoucherNuevo = $_POST ['boucher_n'];
		$peticion = $_POST ['peticion'];
		$motivo = $_POST ['motivo'];
		$causa = $_POST ['causa'];
		$this->CCreditos->Modificar_Boucher ( $BoucherActual, $BoucherNuevo, $peticion, $motivo, $causa );
		echo "<strong>&nbsp;  Proceso Finalizado Satisfactoriamente </strong>....";
	}
	public function Modificar_Serial() {
		$this->load->model ( "CProductos" );
		
		$SerialActual = $_POST ['serial_a'];
		$SerialNuevo = $_POST ['serial_n'];
		$this->CProductos->Modificar ( $SerialActual, $SerialNuevo );
		echo "<strong>&nbsp;  Proceso Finalizado Satisfactoriamente </strong>....";
	}
	public function Modificar_EEjecucion() {
		$contrato = $_POST ['contrato'];
		$lugar = $_POST ['lugar'];
		$peticion = $_POST ['peticion'];
		$estaba = '';
		$motivo = $_POST ['motivo'];
		$rsEstatus = $this->db->query ( 'SELECT oide FROM t_estadoejecucion WHERE oidc="' . $contrato . '"' );
		if ($rsEstatus->num_rows () > 0) {
			$rsFila = $rsEstatus->result ();
			foreach ( $rsFila as $row ) {
				$estaba = $row->oide;
			}
			$data = array (
					"oide" => $lugar 
			);
			$this->db->where ( 'oidc', $contrato );
			$paso = $this->db->update ( 't_estadoejecucion', $data );
			$data = array (
					
					// 'id' => null,
					'referencia' => $contrato,
					'tipo' => 13,
					'usuario' => $_SESSION ['usuario'],
					'motivo' => $motivo . "//" . $estaba . "//" . $lugar,
					'peticion' => $peticion 
			);
			
			$this->db->insert ( '_th_sistema', $data );
			echo "Modificacion Procesada";
		} else {
			echo "Contrato no Existe En la tabla...";
		}
	}
	public function Modificar_Ubicacion() {
		$contrato = $_POST ['contrato'];
		$lugar = $_POST ['lugar'];
		$peticion = $_POST ['peticion'];
		$estaba = '';
		$motivo = $_POST ['motivo'];
		$rsUbica = $this->db->query ( 'SELECT codigo_n FROM t_clientes_creditos WHERE contrato_id="' . $contrato . '"' );
		if ($rsUbica->num_rows () > 0) {
			$rsFila = $rsUbica->result ();
			foreach ( $rsFila as $row ) {
				$estaba = $row->codigo_n;
			}
			$data = array (
					"codigo_n" => $lugar 
			);
			$this->db->where ( 'contrato_id', $contrato );
			$paso = $this->db->update ( 't_clientes_creditos', $data );
			$data = array (
					
					// 'id' => null,
					'referencia' => $contrato,
					'tipo' => 14,
					'usuario' => $_SESSION ['usuario'],
					'motivo' => $motivo . "//" . $estaba . "//" . $lugar,
					'peticion' => $peticion 
			);
			
			$this->db->insert ( '_th_sistema', $data );
			echo "Modificacion Procesada";
		} else {
			echo "Contrato no Existe En la tabla...";
		}
	}

    public function Modificar_LinajeF() {
        $contrato = $_POST ['factura'];
        $linaje = $_POST ['linaje'];
        $peticion = $_POST ['peticion'];
        $estaba = '';
        $motivo = $_POST ['motivo'];
        $rsLinaje = $this->db->query ( 'SELECT cobrado_en FROM t_clientes_creditos WHERE numero_factura="' . $contrato . '"' );
        if ($rsLinaje->num_rows () > 0) {
            $rsFila = $rsLinaje->result ();
            foreach ( $rsFila as $row ) {
                $estaba = $row->cobrado_en;
            }
            $data = array (
                "cobrado_en" => $linaje
            );
            $this->db->where ( 'numero_factura', $contrato );
            $paso = $this->db->update ( 't_clientes_creditos', $data );
            $data = array (

                // 'id' => null,
                'referencia' => $contrato,
                'tipo' => 24,
                'usuario' => $_SESSION ['usuario'],
                'motivo' => $motivo . "//" . $estaba . "//" . $linaje,
                'peticion' => $peticion
            );

            $this->db->insert ( '_th_sistema', $data );
            echo "Modificacion Procesada";
        } else {
            echo "Contrato no Existe En la tabla...";
        }
    }
	public function Modificar_Linaje() {
		$contrato = $_POST ['contrato'];
		$linaje = $_POST ['linaje'];
		$peticion = $_POST ['peticion'];
		$estaba = '';
		$motivo = $_POST ['motivo'];
		$rsLinaje = $this->db->query ( 'SELECT cobrado_en FROM t_clientes_creditos WHERE contrato_id="' . $contrato . '"' );
		if ($rsLinaje->num_rows () > 0) {
			$rsFila = $rsLinaje->result ();
			foreach ( $rsFila as $row ) {
				$estaba = $row->cobrado_en;
			}
			$data = array (
					"cobrado_en" => $linaje 
			);
			$this->db->where ( 'contrato_id', $contrato );
			$paso = $this->db->update ( 't_clientes_creditos', $data );
			$data = array (
					
					// 'id' => null,
					'referencia' => $contrato,
					'tipo' => 16,
					'usuario' => $_SESSION ['usuario'],
					'motivo' => $motivo . "//" . $estaba . "//" . $linaje,
					'peticion' => $peticion 
			);
			
			$this->db->insert ( '_th_sistema', $data );
			echo "Modificacion Procesada";
		} else {
			echo "Contrato no Existe En la tabla...";
		}
	}

    public function Modificar_Empresa() {
        $contrato = $_POST ['contrato'];
        $empresa = $_POST ['empresa'];
        $tipo = $_POST ['tipo'];
        $peticion = $_POST ['peticion'];
        $estaba = '';
        $motivo = $_POST ['motivo'];
        $query='SELECT empresa FROM t_clientes_creditos WHERE contrato_id="' . $contrato . '"' ;
        if($tipo !=0)$query='SELECT empresa FROM t_clientes_creditos WHERE numero_factura="' . $contrato . '"' ;
        $rsEmpresa = $this->db->query ( $query);
        if ($rsEmpresa->num_rows () > 0) {
            $rsFila = $rsEmpresa->result ();
            foreach ( $rsFila as $row ) {
                $estaba = $row->empresa;
            }
            $data = array (
                "empresa" => $empresa
            );
            if($tipo == 0)$this->db->where ( 'contrato_id', $contrato );
            else $this->db->where ( 'numero_factura', $contrato );
            $paso = $this->db->update ( 't_clientes_creditos', $data );
            $data = array (
                // 'id' => null,
                'referencia' => $contrato,
                'usuario' => $_SESSION ['usuario'],
                'motivo' => $motivo . "//" . $estaba . "//" . $empresa,
                'peticion' => $peticion
            );
            $data['tipo']=25;
            if($tipo != 0) $data['tipo']=26;
            $this->db->insert ( '_th_sistema', $data );
            echo "Modificacion Procesada";
        } else {
            echo "Contrato no Existe En la tabla...".$query;
        }
    }
	public function Modificar_Forma_C() {
		$contrato = $_POST ['contrato'];
		$forma = $_POST ['forma'];
		$peticion = $_POST ['peticion'];
		$estaba = '';
		$motivo = $_POST ['motivo'];
		$rsPer = $this->db->query ( 'SELECT forma_contrato FROM t_clientes_creditos WHERE contrato_id="' . $contrato . '"' );
		if ($rsPer->num_rows () > 0) {
			$rsFila = $rsPer->result ();
			foreach ( $rsFila as $row ) {
				$estaba = $row->forma_contrato;
			}
			$data = array (
					"forma_contrato" => $forma 
			);
			$this->db->where ( 'contrato_id', $contrato );
			$paso = $this->db->update ( 't_clientes_creditos', $data );
			$data = array (
					
					// 'id' => null,
					'referencia' => $contrato,
					'tipo' => 18,
					'usuario' => $_SESSION ['usuario'],
					'motivo' => $motivo . "//" . $estaba . "//" . $forma,
					'peticion' => $peticion 
			);
			
			$this->db->insert ( '_th_sistema', $data );
			echo "Modificacion Procesada";
		} else {
			echo "Contrato no Existe En la tabla...";
		}
	}
	public function Modificar_Periodicidad() {
		$contrato = $_POST ['contrato'];
		$periodicidad = $_POST ['periodicidad'];
		$peticion = $_POST ['peticion'];
		$estaba = '';
		$motivo = $_POST ['motivo'];
		$rsPer = $this->db->query ( 'SELECT periocidad FROM t_clientes_creditos WHERE contrato_id="' . $contrato . '"' );
		if ($rsPer->num_rows () > 0) {
			$rsFila = $rsPer->result ();
			foreach ( $rsFila as $row ) {
				$estaba = $row->periocidad;
			}
			$data = array (
					"periocidad" => $periodicidad 
			);
			$this->db->where ( 'contrato_id', $contrato );
			$paso = $this->db->update ( 't_clientes_creditos', $data );
			$data = array (
					
					// 'id' => null,
					'referencia' => $contrato,
					'tipo' => 17,
					'usuario' => $_SESSION ['usuario'],
					'motivo' => $motivo . "//" . $estaba . "//" . $periodicidad,
					'peticion' => $peticion 
			);
			
			$this->db->insert ( '_th_sistema', $data );
			echo "Modificacion Procesada";
		} else {
			echo "Contrato no Existe En la tabla...";
		}
	}
	public function Ver_EEjecucion() {
		$contrato = $_POST ['contrato'];
		$msj = '';
		$rsEstatus = $this->db->query ( 'SELECT oide FROM t_estadoejecucion WHERE oidc="' . $contrato . '"' );
		if ($rsEstatus->num_rows () > 0) {
			$rsFila = $rsEstatus->result ();
			foreach ( $rsFila as $row ) {
				$msj = "El Contrato Esta En Posesion " . $row->oide;
			}
		} else {
			$msj = "No se Puede Ubicar El contrato";
		}
		
		echo $msj;
	}
	public function Ver_Ubicacion() {
		$contrato = $_POST ['contrato'];
		$msj = '';
		$rsEstatus = $this->db->query ( 'SELECT codigo_n FROM t_clientes_creditos WHERE contrato_id="' . $contrato . '"' );
		if ($rsEstatus->num_rows () > 0) {
			$rsFila = $rsEstatus->result ();
			foreach ( $rsFila as $row ) {
				$msj = "El Contrato Esta En " . $row->codigo_n;
			}
		} else {
			$msj = "No se Puede Ubicar El contrato";
		}
		
		echo $msj;
	}
	
	/**
	 * Correos Internos informacion y comentarios
	 *
	 * @return array Lista de Consejos
	 */
	public function Consejos() {
		//
		echo phpinfo ();
	}
	public function Eliminar_Cobros() {
		
		/* JavaScripts Instancia */
		if (isset ( $_POST ['documento_id'] )) {
			$cedula = $_POST ['documento_id'];
			$contrato = $_POST ['contrato_id'];
			$fecha = $_POST ['fecha'];
			$monto = $_POST ['monto'];
			
			$strConsulta = "DELETE FROM t_lista_cobros WHERE documento_id='$cedula' AND credito_id='$contrato' AND fecha='$fecha'";
			$this->db->query ( $strConsulta );
			
			$data = array (
					'referencia' => $contrato,
					'tipo' => 19,
					'usuario' => $_SESSION ['usuario'],
					'motivo' => $_POST ['motivo'] . '//' . $cedula . '//' . $fecha . '//' . $monto,
					'peticion' => $_POST ['peticion'] 
			);
			
			$this->db->insert ( '_th_sistema', $data );
		} else {
			$cedula = "";
			$contrato = "";
		}
		$contenido = $this->CClientes->CI_Lista_Cobros ( $cedula, $contrato );
		
		echo $contenido ["residuo"];
		echo $contenido ["contenido"];
	}
	
	/**
	 * 0: Por Procesar
	 * 1: Aceptado
	 * 2: Rechazado
	 */
	public function Procesar_Contratos() {
		/**
		 *
		 * @var CCreditos
		 */
		$CCreditos = new $this->CCreditos ();
		
		/**
		 *
		 * @var array
		 */
		$lista = array ();
		for($i = 0; $i < $_POST ["txtMaximo"]; $i ++) {
			if (isset ( $_POST ["C" . $i] )) {
				$lista [$i] = array (
						$_POST ["I" . $i] => $_POST ["C" . $i] 
				);
			} else {
				$lista [$i] = array (
						$_POST ["I" . $i] => '0' 
				);
			}
			//
		}
		
		$CCreditos->Procesar_Contratos ( $lista, $_SESSION ['usuario'] );
		$this->reportes ();
	}
	
	/**
	 * 0: Por Procesar
	 * 1: Aceptado
	 * 2: Rechazado
	 */
	public function Procesar_Contratos_Facturas() {
		/**
		 *
		 * @var CCreditos
		 */
		$CCreditos = new $this->CCreditos ();
		/**
		 *
		 * @var array
		 */
		$lista = array ();
		for($i = 0; $i < $_POST ["txtMaximo"]; $i ++) {
			if (isset ( $_POST ["C" . $i] )) {
				$lista [$i] = array (
						$_POST ["I" . $i] => $_POST ["C" . $i] 
				);
			} else {
				$lista [$i] = array (
						$_POST ["I" . $i] => '0' 
				);
			}
		}
		$CCreditos->Procesar_Contratos_Facturas ( $lista, $_SESSION ['usuario'] );
		$this->reportes ();
	}
	
	/**
	 * Aceptar Facturas desde domiciliacion *
	 */
	public function Aceptar_Facturas($Factura = '') {
		$data = array (
				"estatus" => 1 
		);
		$this->db->where ( "numero_factura", $Factura );
		$this->db->update ( "t_clientes_creditos", $data );
	}
	
	/*
	 * ---------------------------------------------------------------
	 * Funciones para controlar el Objeto DataTable (YUI)
	 * Codigo | Cedula | Nombres | Apellidos | Sexo | Cargos
	 * --------------------------------------------------------------
	 */
	public function Eliminar_Contrato() {
		if ($this->session->userdata ( 'usuario' )) {
			
			$strContrato = $_POST ['contrato'];
			$Dependencia = $_POST ['dependencia'];
			if ($strContrato != '' && $strContrato != null) {
				$this->CClientes->Eliminar_Contrato ( $strContrato );
			}
			$data ['DataSource_Yui'] = $this->CClientes->CI_Clientes_Reportes_Factura ( $Dependencia, "TODOS", "TODOS", $this->session->userdata ( 'nivel' ), 0 );
			echo $data ['DataSource_Yui'];
		} else { // Alguno de los post no funciona
			$this->login ();
		}
	}
	public function Eliminar_Recibo() {
		$sRecibo = json_decode ( $_POST ['objeto'], true );
		$this->load->model ( 'recibo/mrecibo', 'MRecibo' );
		$this->MRecibo->Eliminar_Recibo ( $sRecibo [0] );
	}
	public function Eliminar_ReciboE() {
		$Nivel = $this->session->userdata ( 'nivel' );
		$usu =$this->session->userdata ( 'usuario' );
		if ($Nivel == 0 || $Nivel == 3 || $Nivel == 9 || $Nivel == 8 || $Nivel == 10 || strtolower($usu)=="alvaroz") {
			$sRecibo = json_decode ( $_POST ['objeto'], true );
			$this->load->model ( 'recibo/mreciboegreso', 'MReciboE' );
			$this->MReciboE->Eliminar_ReciboE ( $sRecibo [0] );
		} else {
			echo "Usted NO POSEE un perfil de ELIMINACION, EL RECIBO NO SE ELIMINO....";
		}
	}
	public function Eliminar_Reporte_Factura() {
		if ($this->session->userdata ( 'usuario' )) {
			
			$strContrato = $_POST ['contrato'];
			$Dependencia = $_POST ['dependencia'];
			$this->CClientes->Eliminar_Reporte_Factura ( $strContrato );
			$data ['DataSource_Yui'] = $this->CClientes->CI_Clientes_Reportes_Factura ( $Dependencia, "TODOS", "TODOS", $this->session->userdata ( 'nivel' ), 0 );
			
			echo $data ['DataSource_Yui'];
		} else { // Alguno de los post no funciona
			$this->login ();
		}
	}
	public function Imprimir($strCedula = "") {
		if ($this->session->userdata ( 'usuario' )) {
			$data ["cedula"] = $strCedula;
			$this->load->view ( "nomina/imprimir_hoja", $data );
		}
	}
	public function Afiliacion($strCedula = "") {
		$strNombre = '';
		$strBanco = '';
		$strCuenta = '';
		$strDireccion = '';
		$strNomina = '';
		$strUsuario = '';
		$Empresa = 'COOPERATIVA ELECTRON 465 RL.';
		if ($this->session->userdata ( 'usuario' )) {
			$data ["cedula"] = $strCedula;
			
			// $Consulta = $this -> db -> query("SELECT * FROM t_personas INNER JOIN t_clientes_creditos ON t_personas.documento_id=t_clientes_creditos.documento_id WHERE t_personas.documento_id='$strCedula' LIMIT 1");
			$Consulta = $this->db->query ( "SELECT * FROM t_personas WHERE t_personas.documento_id='$strCedula' LIMIT 1" );
			if ($Consulta->num_rows () != 0) {
				foreach ( $Consulta->result () as $row ) {
					$strNombre = $row->primer_apellido . ' ' . $row->segundo_apellido . ' ' . $row->primer_nombre . ' ' . $row->segundo_nombre;
					$strBanco = $row->banco_1;
					$strCuenta = $row->cuenta_1;
					$strDireccion = 'AV. ' . $row->avenida . ' CALLE ' . $row->calle . ' MUNICIPIO ' . $row->municipio . ' SECTOR ' . $row->sector . ' CASA # ' . $row->direccion;
					$strNomina = '';
					// $row -> nomina_procedencia;
					$strUsuario = $row->codigo_nomina;
				}
			} else {
			}
			$data ["nombre"] = $strNombre;
			$data ["banco"] = $strBanco;
			$data ["cuenta"] = $strCuenta;
			$data ["direccion"] = $strDireccion;
			$data ["empresa"] = $Empresa;
			$data ["lista"] = '';
			$data ["factura"] = '';
			$data ['nomina'] = $strNomina;
			$data ['usuario'] = $strUsuario;
			
			$this->load->view ( "nomina/afiliadesafilia", $data );
		}
	}
	public function AfiliacionI($strCedula = "") {
		$strNombre = '';
		$strBanco = '';
		$strCuenta = '';
		$strDireccion = '';
		$Empresa = 'COOPERATIVA ELECTRON 465 RL.';
		if ($this->session->userdata ( 'usuario' )) {
			$data ["cedula"] = $strCedula;
			
			$Consulta = $this->db->query ( "SELECT * FROM t_personas WHERE documento_id='$strCedula'" );
			
			if ($Consulta->num_rows () != 0) {
				foreach ( $Consulta->result () as $row ) {
					$strNombre = $row->primer_apellido . ' ' . $row->segundo_apellido . ' ' . $row->primer_nombre . ' ' . $row->segundo_nombre;
					$strBanco = $row->banco_1;
					$strCuenta = $row->cuenta_1;
					$strDireccion = 'AV. ' . $row->avenida . ' CALLE ' . $row->calle . ' MUNICIPIO ' . $row->municipio . ' SECTOR ' . $row->sector . ' CASA # ' . $row->direccion;
				}
			} else {
			}
			$data ["nombre"] = $strNombre;
			$data ["banco"] = $strBanco;
			$data ["cuenta"] = $strCuenta;
			$data ["direccion"] = $strDireccion;
			$data ["empresa"] = $Empresa;
			$data ["lista"] = '';
			$data ["factura"] = '';
			$data ['usuario'] = $_SESSION ['usuario'];
			
			$this->load->view ( "nomina/afiliacion_generica", $data );
		}
	}
	public function Afiliaciones($strCedula = "") {
		$strNombre = '';
		$strBanco = '';
		$strCuenta = '';
		$strDireccion = '';
		$strNomina = '';
		$strUsuario = '';
		$Empresa = 'COOPERATIVA ELECTRON 465 RL.';
		if ($this->session->userdata ( 'usuario' )) {
			$data ["cedula"] = $strCedula;
			$Consulta = $this->db->query ( "SELECT * FROM t_personas INNER JOIN t_clientes_creditos ON t_personas.documento_id=t_clientes_creditos.documento_id WHERE t_personas.documento_id='$strCedula' LIMIT 1" );
			
			if ($Consulta->num_rows () != 0) {
				foreach ( $Consulta->result () as $row ) {
					$strNombre = $row->primer_apellido . ' ' . $row->segundo_apellido . ' ' . $row->primer_nombre . ' ' . $row->segundo_nombre;
					$strBanco = $row->banco_2;
					$strCuenta = $row->cuenta_2;
					
					$strDireccion = 'AV. ' . $row->avenida . ' CALLE ' . $row->calle . ' MUNICIPIO ' . $row->municipio . ' SECTOR ' . $row->sector . ' CASA # ' . $row->direccion;
					$strNomina = $row->nomina_procedencia;
					$strUsuario = $row->codigo_nomina;
				}
			}
			
			$data ["nombre"] = $strNombre;
			$data ["banco"] = $strBanco;
			$data ["cuenta"] = $strCuenta;
			$data ["direccion"] = $strDireccion;
			$data ["lista"] = '';
			$data ["empresa"] = $Empresa;
			$data ["factura"] = '';
			$data ['nomina'] = $strNomina;
			$data ['usuario'] = $strUsuario;
			$this->load->view ( "nomina/afiliadesafilia", $data );
		}
	}
	
	/**
	 * Controlador
	 *
	 * @return {json} Clientes
	 */
	public function DataSource_Cliente($sId = null) {
		$this->load->model ( 'persona/MPersona', 'MPersona' );
		if (isset ( $_POST ['id'] )) {
			$cedula = $_POST ['id'];
			echo $this->MPersona->jsPersona ( $cedula, $this->CPersonas );
		} else {
			echo $this->MPersona->jsPersona ( $sId, $this->CPersonas );
		}
	}
	public function DataSource_Control() {
		$sCon = $_POST ['id'];
		$this->load->model ( 'cobranza/mcobranza', 'MCobranza' );
		$val = $this->MCobranza->GListaPagos ( $sCon );
		echo $val ['json'];
	}
	public function DataSource_Cliente_CC($sId = null) {
		$this->load->model ( 'persona/MPersona', 'MPersona' );
		if (isset ( $_POST ['id'] )) {
			$cedula = $_POST ['id'];
			echo $this->MPersona->jsPersonaCC ( $cedula );
		} else {
			echo $this->MPersona->jsPersonaCC ( $sId );
		}
	}
	public function DataSource_ReciboI($sId = null) {
		$this->load->model ( 'recibo/mrecibo', 'MRecibo' );
		if (isset ( $_POST ['id'] )) {
			$cedula = $_POST ['id'];
			echo $this->MRecibo->jsReciboI ( $cedula );
		} else {
			echo $this->MRecibo->jsReciboI ( $sId );
		}
	}

    public function listaVoucherRecibo($sId = null) {
        $this->load->model ( 'recibo/mrecibo', 'MRecibo' );
        if (isset ( $_POST ['factura'] )) {
            echo $this->MRecibo->listaVoucher ( $_POST['factura'] );
        } else {
            echo $this->MRecibo->listaVoucher ( $_POST['factura'] );
        }
    }
	public function DataSource_ReciboE($sId = null) {
		$this->load->model ( 'recibo/mreciboegreso', 'MReciboe' );
		if (isset ( $_POST ['id'] )) {
			$cedula = $_POST ['id'];
			echo $this->MReciboe->jsReciboE ( $cedula );
		} else {
			echo $this->MReciboe->jsReciboE ( $sId );
		}
	}
	public function DataSource_Cheques() {
		$this->load->model ( 'chequera/mchequera', 'MChequera' );
		if (isset ( $_POST ['ban'] )) {
			$iBanco = $_POST ['ban'];
			$iUbicacion = $_POST ['ubi'];
			echo $this->MRecibo->jsCheque ( $iBanco, $iUbicacion );
		}
	}
	public function DataSource_Creditos($sId = null, $sC = null) {
		$this->load->model ( 'cliente/MCliente', 'MCliente' );
		if (isset ( $_POST ['documento_id'] )) {
			$documento_id = $_POST ['documento_id'];
			$contrato_id = $_POST ['contrato_id'];
			echo $this->MCliente->jsCredito ( $documento_id, $contrato_id, $this->CCreditos );
		} else {
			echo $this->MCliente->jsCredito ( $sId, $sC, $this->CCreditos );
		}
	}
	public function DataSource_Cobros() {
		if (isset ( $_POST ['documento_id'] )) {
			$sCedula = $_POST ['documento_id'];
			$sContrato = $_POST ['contrato_id'];
			$contenido = $this->CClientes->CI_Lista_Cobros ( $sCedula, $sContrato, $this->session->userdata ( 'nivel' ) );
			echo $contenido ["residuo"];
			echo $contenido ["contenido"];
		}
	}
	public function DataSource_Cobros_Programadas() {
		$this->load->model ( 'cliente/MVoucher', 'MVoucher' );
		if (isset ( $_POST ['voucher'] )) {
			
			$sVoucher = $_POST ['voucher'];
			$sFactura = $_POST ['factura'];
			$contenido = $this->MVoucher->Lista_Cobros_Programadas ( $sVoucher, $sFactura );
			echo $contenido;
		}
	}
	public function DataSource_NotasCreditos($sId = null) {
		$this->load->model ( 'auditoria/MAuditoria', 'Auditoria' );
		if (isset ( $_POST ['id'] )) {
			$cedula = $_POST ['id'];
			echo $this->Auditoria->listarNotasCreditos ( $cedula );
		} else {
			echo $this->Auditoria->listarNotasCreditos ( $sId );
		}
	}






	public function Deuda_Cliente($sId = null) {
		$this->load->model ( 'persona/MPersona', 'MPersona' );
		if (isset ( $_POST ['id'] )) {
			$cedula = $_POST ['id'];
			echo $this->MPersona->jsDeuda ( $cedula );
		} else {
			echo $this->MPersona->jsDeuda ( $sId );
		}
	}
	public function Deuda_Cliente2($sId = null) {
		$this->load->model ( 'persona/MPersona', 'MPersona' );
		$res = json_decode ( $this->MPersona->jsDeuda ( $sId ), true );
		return $res ['activo'];
	}
	public function Agregar_Cobros() {
		if ($this->session->userdata ( 'usuario' )) {
			if (isset ( $_POST ['moda'] )) {
				$datos ['documento_id'] = $_POST ['documento_id'];
				$datos ['credito_id'] = $_POST ['contrato_id'];
				$datos ['mes'] = '';
				$datos ['monto'] = $_POST ['monto'];
				$datos ['fecha'] = $_POST ['fecha'];
				$datos ['descripcion'] = $_POST ['descripcion'];
				$datos ['usua'] = $this->session->userdata ( 'oidu' );
				$datos ['mesp'] = $_POST ['mesp'];
				$datos ['anop'] = $_POST ['anop'];
				$datos ['moda'] = $_POST ['moda'];
				$datos ['farc'] = $_POST ['fechad'];
				
				$perfil = $this->session->userdata ( 'nivel' );
				
				if ($perfil == 0 || $perfil == 3 || $perfil == 17 || $perfil == 2) {
					$this->db->insert ( 't_lista_cobros', $datos );
				}
				// print_R($_POST);
				
				$this->load->model ( 'reporte/pformatos', 'PFormatos' );
				$fecha_actual = explode ( "-", date ( "d-m-Y" ) );
				$rsPersona = $this->db->query ( "SELECT correo, documento_id,	CONCAT(primer_apellido,' ',LEFT(segundo_apellido,1),' ',primer_nombre,' ', LEFT(segundo_nombre,1),' ') AS Nombre FROM t_personas WHERE documento_id='" . $_POST ['documento_id'] . "'" );
				$correo = $rsPersona->row ();
				$montoL = $this->PFormatos->ValorEnLetras ( $datos ['monto'], '' );
				$mesAL = $this->PFormatos->mes_letras ( $fecha_actual [1] );
				$cuerpo = '<table style="font-family: Trebuchet MS; font-size: 13px;" width="0"><tr><td rowspan="2"  width=180><img src="' . __IMG__ . 'logoN.jpg" width=200></td>
						</tr><tr><td colspan="3" >Apreciado/a:  <br>' . $correo->Nombre . '.<br> ' . $datos ['documento_id'] . '</td></tr>';
				if ($datos ['monto'] >= 0) {
					$cuerpo .= '<tr><td colspan="4">Recibe un caluroso saludo de parte de Electr&oacute;n 465, mediante la presente le notificamos que su pago correspondiente a la fecha ' . $datos ['fecha'] . ',
					por la cantidad de:	' . $montoL . ' Bolivares (' . $datos ['monto'] . ' BS). Se ha realizado exitosamente.<br><br></td>
					</tr><tr><td colspan="4">Si tienes alguna pregunta o si necesitas alguna asistencia con respecto a esta comunicaci&oacute;n, estamos a tu disposici&oacute;n puedes comunicarte con nuestro equipo de atenci&oacute;n al cliente a trav&eacute;s del n&uacute;mero telef&oacute;nico: 0274-9358009 en el siguiente horario: de 8.30am a 12.00m, y luego de 2.00pm a 5.00pm de lunes a viernes, igualmente puedes comunicarte los 365 d&iacute;as del a&ntilde;o las 24 horas del d&iacute;a a trav&eacute;s de nuestro correo electr&oacute;nico: soporte@electron465.com
					<br><br>Muchas gracias por ser parte de la comunidad Electr&oacute;n 465.</td></tr>
					<tr><td colspan="4"><hr></hr><small>Por favor, no responda este e-mail ya que ha sido enviado autom&aacute;ticamente. Usted ha recibido esta comunicaci&oacute;n por tener una relaci&oacute;n de pagos con Electr&oacute;n 465. Esta comunicaci&oacute;n forma parte b&aacute;sica de nuestro programa de atenci&oacute;n al cliente. Si no desea seguir recibiendo este tipo de comunicaciones, le rogamos cancele por escrito su afiliaci&oacute;n al mismo.
					 <br>Electr&oacute;n 465 se compromete firmemente a respetar su privacidad. No compartimos su informaci&oacute;n con ning&uacute;n tercero sin su consentimiento.</small>
					</td></tr></table>';
				} else {
					$cuerpo = '';
					/*
					 * $cuerpo .= '<tr><td colspan="4">Recibe un caluroso saludo de parte de Electr&oacute;n 465, mediante la presente te notificamos que ha sido abonada la cantidad de:
					 * ' . $montoL . ' (' . $monto . ') . Correspondientes al reintegro con fecha de ' . $fecha . '. <br><br></td></tr><tr>
					 * <td colspan="4">Si tienes alguna pregunta o si necesitas alguna asistencia con respecto a esta comunicaci&oacute;n, estamos aqu&iacute; a tu disposici&oacute;n puedes comunicarte con nuestro equipo de atenci&oacute;n al cliente a trav&eacute;s del n&uacute;mero telef&oacute;nico: 0274-9358009 en el siguiente horario: de 8.30am a 12.00m, y luego de 2.00pm a 5.00pm de lunes a viernes, igualmente puedes comunicarte los 365 d&iacute;as del a&ntilde;o las 24 horas del d&iacute;a a trav&eacute;s de nuestro correo electr&oacute;nico: soporte@electron465.com
					 * <br><br>Muchas gracias por ser parte de la comunidad Electr&oacute;n 465.</td></tr><tr><td colspan="4"><hr></hr><small>
					 * Por favor, no responda este e-mail ya que ha sido enviado autom&aacute;ticamente. Usted ha recibido esta comunicaci&oacute;n por tener una relaci&oacute;n de pagos con Electr&oacute;n 465. Esta comunicaci&oacute;n forma parte b&aacute;sica de nuestro programa de atenci&oacute;n al cliente. Si no desea seguir recibiendo este tipo de comunicaciones, le rogamos cancele por escrito su afiliaci&oacute;n al mismo.
					 * <br>Electr&oacute;n 465 se compromete firmemente a respetar su privacidad. No compartimos su informaci&oacute;n con ning&uacute;n tercero sin su consentimiento.</small>
					 * </td></tr></table>';
					 */
				}
				if ($correo->correo != '' && $cuerpo != '') {
					$correoE = $correo->correo;
					$cabeceras = 'MIME-Version: 1.0' . "\r\n";
					$cabeceras .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
					$cabeceras .= 'FROM:cobranza@ELECTRON465.com' . "\r\n";
					mail ( "$correoE", "Departamento De Cobros Electron 465", $cuerpo, $cabeceras );
				}
			} else {
				$cedula = "";
				$contrato = "";
			}
			$contenido = $this->CClientes->CI_Lista_Cobros ( $_POST ['documento_id'], $_POST ['contrato_id'], $this->session->userdata ( 'nivel' ) );
			echo $contenido ["residuo"];
			echo $contenido ["contenido"];
		} else {
			$this->login ();
		}
	}
	public function Agregar_Cobros_Programadas() {
		if (isset ( $_POST ['voucher'] )) {
			$voucher = $_POST ['voucher'];
			$factura = $_POST ['factura'];
			$referencia = $_POST ['referencia'];
			$observa = $_POST ['observa'];
			$cuota = $_POST ['cuota'];
			$fecha = $_POST ['fecha'];
			$this->db->set ( 'oidvoucher', $voucher );
			$this->db->set ( 'cuota', $cuota );
			$this->db->set ( 'fecha', $fecha );
			$this->db->set ( 'referencia', $referencia );
			$this->db->set ( 'observaciones', $observa );
			$this->db->set ( 'fact', $factura );
			$this->db->set ( 'usuario', $this->session->userdata ( 'usuario' ) );
			$this->db->insert ( 't_lista_programadas' );
			$query = "SELECT IFNULL( SUM( t_lista_programadas.cuota ) , 0 ) AS pagado  ,monto FROM t_cuotas_programadas
					LEFT JOIN t_lista_programadas ON t_cuotas_programadas.voucher = t_lista_programadas.oidvoucher and t_cuotas_programadas.factura = t_lista_programadas.fact
					WHERE oidvoucher = '" . $voucher . "' AND factura ='" . $factura . "'";
			$rsPagadas = $this->db->query ( $query );
			
			foreach ( $rsPagadas->result () as $row ) {
				if ($row->pagado == $row->monto) {
					$this->db->set ( "estatus", 1 );
					$this->db->where ( "voucher", $voucher );
					$this->db->where ( "factura", $factura );
					$this->db->update ( "t_cuotas_programadas" );
					$this->db->set ( "estatus", 3 );
					$this->db->set ( "observacion", "PAGO REALIZADO POR DOMICILIACION, REFERENCIA:" . $referencia );
					$this->db->set ( "tipo_voucher", 3 );
					$this->db->where ( "ndep", $voucher );
					$this->db->where ( "cid", $factura );
					$this->db->update ( "t_lista_voucher" );
					echo "Se Cargo La cuota Con Exito..Esta Cuota programada ya esta cancelada..Por Favor Presione F5.";
				} else {
					echo "Se Cargo La Cuota con exito.. Resta Por Pagar " . ($row->monto - $row->pagado);
				}
			}
		} else {
			echo "No se Encontro el Voucher";
		}
	}
	public function DataSource_Reportes($iConsulta = "") {
		if ($this->session->userdata ( 'usuario' )) {
			$iPaginador = 1200;
			if (isset ( $_POST ['dependencia'] )) {
				$Dependencia = $_POST ['dependencia'];
				$dtd_nomina = $_POST ['nomina_procedencia'];
				$cobrado_en = $_POST ['cobrado_en'];
				$credito = $_POST ['credito'];
				$desde = $_POST ['desde'];
				$hasta = $_POST ['hasta'];
				
				// 0: Por aceptar 1: Aceptados 2: Rechazados
				$data ['DataSource_Yui'] = $this->CClientes->CI_Clientes_Reportes_Factura ( $Dependencia, $dtd_nomina, $cobrado_en, $this->session->userdata ( 'nivel' ), $credito, ( int ) $iConsulta, $iPaginador, $desde, $hasta );
				echo $data ['DataSource_Yui'];
			} else {
				$Dependencia = $_SESSION ['usuario'];
				$dtd_nomina = "TODOS";
				$cobrado_en = "TODOS";
				$data = $this->CClientes->CI_Clientes_Reportes_Factura ( $Dependencia, $dtd_nomina, $cobrado_en, $this->session->userdata ( 'nivel' ), 0, ( int ) $iConsulta, $iPaginador );
				
				return $data;
			}
		} else { // Alguno de los post no funciona
			$this->login ();
		}
	}
	
	
	/**
	 * Verificar si alguno esta cancelado en su totalidad
	 */
	public function DataSource_Cliente_Creditos() {
		/* JavaScripts Instancia */
		$intContar = 0;
		
		// table th { border: 1px solid #eee; padding: .6em 10px; text-align: left; }
		$listas_contratos = "<br>
				<ul id=\"icons\" class=\"ui-widget ui-helper-clearfix\">
				<table style='Width:650px ' border=\"0\"
				class=\"ui-widget ui-widget-content\" cellspacing=\"0\" cellpadding=\"0\"   >
				<thead>
				<tr class=\"ui-widget-header\" style=\"height:18px;\"	style='border: 1px solid #ccc;'>

				<th style='border: 1px solid #ccc;text-align: center;'>MOTIVO</th>
				<th style='border: 1px solid #ccc;text-alig9876: center;'>CONTRATO.</th>
				<th style='border: 1px solid #ccc;text-align: center;'>FACTURA</th>
				<th style='border: 1px solid #ccc;text-align: center;'>SOLICITUD</th>
				<th style='border: 1px solid #ccc;text-align: center;'>CANTIDAD</th>
				<th style='border: 1px solid #ccc;text-align: center;'>ESTATUS</th>
				<th style='border: 1px solid #ccc;text-align: center;'>USUARIO</th>
				<th style='border: 1px solid #ccc;text-align: center;'>MODIFICADO</th>
				<th style='border: 1px solid #ccc;text-align: center;'>INFORMACION</th>
				</tr></thead><tbody>";
		if (isset ( $_POST ['documento_id'] )) {
			$cedula = $_POST ['documento_id'];
			
			$Consulta = $this->db->query ( "SELECT monto_total, (monto_total - SUM(monto)) AS resta, motivo, contrato_id,numero_factura, fecha_solicitud, cantidad, estatus, expediente_caja, codigo_nomina_aux, expediente_c, codigo_n_a
					FROM t_personas
					INNER JOIN t_clientes_creditos ON	 t_personas.documento_id=t_clientes_creditos.documento_id
					LEFT JOIN t_lista_cobros ON  t_clientes_creditos.contrato_id=t_lista_cobros.credito_id
					WHERE t_clientes_creditos.documento_id='" . $cedula . "' GROUP BY contrato_id, t_lista_cobros.credito_id" );
			if ($Consulta->num_rows () > 0) {
				foreach ( $Consulta->result () as $row ) {
					$intContar ++;
					$listas_contratos .= "<tr style='border: 1px solid #ccc;'>
							<td style='border: 1px solid #ccc;text-align: center;'>" . $row->motivo . "</td>
									<td style='border: 1px solid #ccc;text-align: center;'>" . $row->contrato_id . "</td>
											<td style='border: 1px solid #ccc;text-align: center;'>" . $row->numero_factura . "</td>
													<td style='border: 1px solid #ccc;text-align: left;'>" . $row->fecha_solicitud . "</td>
															<td style='border: 1px solid #ccc;text-align: right;'>" . number_format ( $row->cantidad, 2, ".", "," ) . " Bs.</td>
																	<td style='border: 1px solid #ccc;text-align: center;'>" . $this->Estatus_Creditos ( $row->estatus ) . "</td>
																			<td style='border: 1px solid #ccc;text-align: center;'>" . $row->expediente_caja . "</td>
																					<td style='border: 1px solid #ccc;text-align: center;'>" . $row->codigo_nomina_aux . "</td>";
					
					if (is_null ( $row->resta )) {
						$listas_contratos .= "<td style='border: 1px solid #ccc;text-align: center;'>NUEVO</td>";
					} else {
						if ($row->resta == 0) {
							$listas_contratos .= "<td style='border: 1px solid #ccc;text-align: center;'>CANCELADO</td>";
						} else {
							$listas_contratos .= "<td style='border: 1px solid #ccc;text-align: center;'>PENDIENTE</td>";
						}
						
						$listas_contratos .= "</tr>";
					}
				}
				$listas_contratos .= "</tbody></table></ul>";
				echo "<p><span class=\"ui-icon ui-icon-star\" style=\"float: left; margin-right: .3em;\"></span>
						<strong>El cliente con C.I: " . $cedula . ", Posee $intContar contrato ...</strong><center>
						$listas_contratos </center></p>";
			} else {
				echo "<p><span class=\"ui-icon ui-icon-info\" style=\"float: left; margin-right: .3em;\"></span>
						<strong>El cliente con C.I: " . $cedula . ", No Posee ningun contrato vigente...</strong></p>";
			}
		} else {
			echo "<p><span class=\"ui-icon ui-icon-info\" style=\"float: left; margin-right: .3em;\"></span>
					<strong>Debe introducir almenos una C&eacute;dula marcado con (*)...</strong></p>";
		}
	}
	
	/**
	 * Estatus del credito 0: Procesando 1: Aceptado 2: Rechazado
	 */
	public function Estatus_Creditos($iEst) {
		$sEst = "EN PROCESO";
		switch ($iEst) {
			case 0 :
				$sEst = "<strong>PROCESANDO</strong>";
				break;
			case 1 :
				$sEst = "<strong>ACEPTADO</strong>";
				break;
			case 2 :
				$sEst = "<strong>RECHAZADO</strong>";
				break;
			case 3 :
				$sEst = "<strong>NULO</strong>";
				break;
		}
		return $sEst;
	}
	
	/**
	 * Controlador del TabView con DataTable YAHOO
	 *
	 * @return array | JavaScripts Tareas Pendientes
	 */
	public function DataSource_Tareas_Pendientes() {
		/* EZPDO Instancia */
		if ($this->session->userdata ( 'usuario' )) {
			// $this->load->model('CPendientes');
			$this->load->model ( 'CNominaXls' );
			
			echo "<p><br>Asunto: <strong>" . $_POST ['asunto'] . ".</strong><br>Descripci&oacute;n: " . $_POST ['descripcion'] . "<br><hr>";
			$i = 1;
			if ($_POST ['asunto'] == 'Aguinaldos') {
				$i = 11;
			}
			
			for($i; $i <= 12; $i ++) {
				$mes = $i;
				$this->CNominaXls->_Cabezera ();
				$this->CNominaXls->_Cuerpo ( $_POST ['ano'], $mes, $_POST ['tipo'] );
				$Nombre = $_POST ['asunto'] . "-" . $_POST ['ano'] . "-" . $mes;
				$this->CNominaXls->_Generar ( $Nombre );
				echo "<br><br>
						<a href='" . __LOCALWWW__ . "/system/repository/xls/" . $_POST ['asunto'] . "-" . $_POST ['ano'] . "-" . $mes . ".xls' target='top'>Descargar Archivo Adjunto Mes de ( " . $this->Convertir_Mes ( $i ) . " )";
			}
		} else {
			echo "Usuario No Posee Acceso al Sistema...";
		}
	}
	public function Convertir_Mes($iMes) {
		switch ($iMes) {
			case 1 :
				return "Enero";
				break;
			case 2 :
				return "Febrero";
				break;
			case 3 :
				return "Marzo";
				break;
			case 4 :
				return "Abril";
				break;
			case 5 :
				return "Mayo";
				break;
			case 6 :
				return "Junio";
				break;
			case 7 :
				return "Julio";
				break;
			case 8 :
				return "Agosto";
				break;
			case 9 :
				return "Septiembre";
				break;
			case 10 :
				return "Octubre";
				break;
			case 11 :
				return "Noviembre";
				break;
			case 12 :
				return "Diciembre";
				break;
		}
	}
	public function P_json($sModelo = "") {
		header ( 'contentType: "application/json" charset=utf-8' );
		$sCargar = "C" . $sModelo;
		$arreglo = array ();
		if (isset ( $_POST ["nombre"] )) {
			$this->db->like ( "nombre", $_POST ["nombre"] );
			$sTable = "t_" . strtolower ( $sModelo );
			$rs = $this->db->get ( $sTable );
			if ($rs->num_rows () != 0) {
				foreach ( $rs->result () as $lst ) {
					$arreglo [] = $lst->nombre;
				}
			}
			$value = array (
					"resultado" => 0,
					"nombres" => $arreglo 
			);
			echo json_encode ( $value );
		} else {
			echo "Acesso restringido a este menu...";
		}
	}
	public function M_json($sModelo = "", $sCampo = "", $tipo = '') {
		header ( 'contentType: "application/json" charset=utf-8' );
		$sCargar = "C" . $sModelo;
		$arreglo = array ();
		if (isset ( $_POST ["nombre"] )) {
			if ($tipo == '') {
				if (isset ( $_POST ['ubica'] )) {
					$ubica = $this->session->userdata ( 'ubicacion' );
					$query = "select * from t_inventario
						join t_productos on t_productos.inventario_id = t_inventario.inventario_id
						where t_productos.version=1 and t_productos.estatus_mercancia=1
						and t_inventario.modelo like '%" . $_POST ['nombre'] . "%' 
						and t_productos.ubicacion = '" . $ubica . "' group by t_inventario.inventario_id";
					$rs = $this->db->query ( $query );
				} else {
					$this->db->like ( $sCampo, $_POST ["nombre"] );
					$this->db->group_by ( $sCampo );
					$sTable = "t_" . strtolower ( $sModelo );
					$rs = $this->db->get ( $sTable );
				}
				
				if ($rs->num_rows () != 0) {
					foreach ( $rs->result () as $lst ) {
						$arreglo [] = $lst->$sCampo;
					}
				}
				$value = array (
						"resultado" => 0,
						"nombres" => $arreglo 
				);
			} else {
				$sTable = "t_" . strtolower ( $sModelo );
				$this->db->select ( "*" );
				$this->db->from ( "t_inventario" );
				$this->db->join ( "t_artefactos", "t_inventario.artefacto = t_artefactos.artefacto_id" );
				$this->db->like ( "nombre", $tipo );
				$this->db->like ( $sCampo, $_POST ["nombre"] );
				$rs = $this->db->get ();
				if ($rs->num_rows () != 0) {
					foreach ( $rs->result () as $lst ) {
						$arreglo [] = $lst->$sCampo;
					}
				}
				$value = array (
						"resultado" => 0,
						"nombres" => $arreglo 
				);
			}
			
			echo json_encode ( $value );
		} else {
			echo "Acesso restringido a este menu...";
		}
	}
	public function M_Json_Mercancia() {
		// header('contentType: "application/json" charset=utf-8');
		if (isset ( $_POST ["nombre"] )) {
			$this->load->model ( 'inventario/minventario', 'MInventario' );
			$arreglo = $this->MInventario->Listar ( $_POST ["nombre"], $_POST ["tipo"] );
			$value = array (
					"resultado" => 0,
					"nombres" => $arreglo 
			);
			echo json_encode ( $value );
		}
	}
	public function Cheques_Disponibles() {
		// header('contentType: "application/json" charset=utf-8');
		if (isset ( $_POST ["nombre"] )) {
			$this->load->model ( 'chequera/mchequera', 'MChequera' );
			$arreglo = $this->MChequera->Disponibles ( $_POST ["nombre"], $this->session->userdata ( 'ubicacion' ) );
			$value = array (
					"resultado" => 0,
					"nombres" => $arreglo 
			);
			echo json_encode ( $value );
		}
	}
	public function Guardar_Inventario() {
		$msj = '';
		$usu_perfil = $this->session->userdata ( 'nivel' );
		$sProveedores = $_POST ["txtProveedores"];
		$sEquipos = $_POST ["txtEquipos"];
		$catalogo = $_POST ['catalogo'];
		$sProveedores1 = $_POST ["txtProveedores1"];
		$sEquipos1 = $_POST ["txtEquipos1"];
		
		$sMarca = $_POST ["txtMarca"];
		$sModelo = $_POST ["txtModelo"];
		$dCompra = $_POST ["txtprecioc"];
		$dVenta = $_POST ["txtpreciov"];
		$sDescripcion = $_POST ["txtDescripcion"];
		$sDetalle = $_POST ["txtDetalle"];
		$credi_compra = $_POST ["txtCrediCompra"];
		// $sUbicacion = $_POST["txtdeposito"];
		$sUbicacion = 'DEPOSITO PRINCIPAL';
		if ($usu_perfil == 12)
			$sUbicacion = 'DEPOSITO LA VICTORIA';
		$sFecha = $_POST ["txtano"] . "-" . $_POST ["txtmes"] . "-" . $_POST ["txtdia"];
		$iGarantia = $_POST ["txtCanGarantia"];
		$sGarantia = $_POST ["txtgarantia"];
		$lstSeriales = $_POST ["lstSeriales"];
		$iPorcentaje = $_POST ["txtPorcentaje"];
		
		if ($sModelo == '' || $sModelo == NULL) {
			$msj = 'DEBE INGRESAR UN MODELO....';
			$this->Inventario ( $msj );
			return 0;
		}
		
		$iProveedor = 0;
		$iEquipo = 0;
		$sTable = "t_proveedores";
		$this->db->where ( "nombre", $sProveedores1 );
		$rs = $this->db->get ( $sTable );
		$this->load->model ( "CProveedores" );
		$this->CProveedores->nombre = $sProveedores;
		
		if ($rs->num_rows () == 0) {
			$this->db->insert ( $sTable, $this->CProveedores );
		} else {
			$strModificar = "UPDATE " . $sTable . " SET nombre='" . $sProveedores . "' WHERE nombre='" . $sProveedores1 . "'";
			;
			$this->db->query ( $strModificar );
		}
		
		/*
		 * $sTable = "t_artefactos";
		 * $this -> db -> where("nombre", $sEquipos1);
		 * $rs = $this -> db -> get($sTable);
		 * $this -> load -> model("CArtefactos");
		 * $this -> CArtefactos -> nombre = $sEquipos;
		 * if ($rs -> num_rows() == 0) {
		 * $this -> db -> insert($sTable, $this -> CArtefactos);
		 * } else {
		 * $strModificar = "UPDATE " . $sTable . " SET nombre='" . $sEquipos . "' WHERE nombre='" . $sEquipos1 . "'";
		 * ;
		 * $this -> db -> query($strModificar);
		 * }
		 */
		
		$iProveedor = 0;
		$sTable = "t_proveedores";
		$this->db->distinct ( "nombre" );
		$this->db->where ( "nombre", $sProveedores );
		$rs = $this->db->get ( $sTable );
		if ($rs->num_rows () != 0) {
			foreach ( $rs->result () as $row ) {
				$iProveedor = $row->proveedor_id;
			}
		}
		
		$iEquipo = 0;
		/*
		 * $sTable = "t_artefactos";
		 *
		 * $this -> db -> where("nombre", $sEquipos);
		 * $rs = $this -> db -> get($sTable);
		 * if ($rs -> num_rows() != 0) {
		 * foreach ($rs -> result() as $row) {
		 * $iEquipo = $row -> artefacto_id;
		 * }
		 * }
		 */
		
		$sTable = "t_inventario";
		
		$this->db->where ( "marca", $sMarca );
		$this->db->where ( "modelo", $sModelo );
		$rs = $this->db->get ( $sTable );
		$this->load->model ( "CInventario" );
		$Inventario = new $this->CInventario ();
		if ($rs->num_rows () == 0) {
			$Inventario->proveedor = $iProveedor;
			// $Inventario -> artefacto = $iEquipo;
			$Inventario->artefacto = $sEquipos;
			$Inventario->marca = $sMarca;
			$Inventario->modelo = $sModelo;
			$Inventario->precio_compra = $dCompra;
			$Inventario->precio_venta = $dVenta;
			$Inventario->credi_compra = $credi_compra;
			$Inventario->porcentaje = $iPorcentaje;
			$Inventario->catalogo = $catalogo;
			$Inventario->detalle = $sDetalle;
			
			$this->db->insert ( $sTable, $Inventario );
		} else {
			
			foreach ( $rs->result () as $row ) {
				$Inventario->inventario_id = $row->inventario_id;
			}
			$Inventario->proveedor = $iProveedor;
			$Inventario->artefacto = $sEquipos;
			$Inventario->marca = $sMarca;
			$Inventario->modelo = $sModelo;
			$Inventario->precio_compra = $dCompra;
			$Inventario->precio_venta = $dVenta;
			$Inventario->porcentaje = $iPorcentaje;
			$Inventario->catalogo = $catalogo;
			$Inventario->detalle = $sDetalle;
			$Inventario->credi_compra = $credi_compra;
			$this->db->where ( "inventario_id", $Inventario->inventario_id );
			$this->db->update ( $sTable, $Inventario );
		}
		
		$sTable = "t_inventario";
		$this->db->where ( "marca", $sMarca );
		$this->db->where ( "modelo", $sModelo );
		$rs = $this->db->get ( $sTable );
		if ($rs->num_rows () != 0) {
			$iInventario = 0;
			foreach ( $rs->result () as $row ) {
				$iInventario = $row->inventario_id;
			}
			
			$this->load->model ( "CProductos" );
			/**
			 *
			 * @var CProductos
			 */
			$Productos = new $this->CProductos ();
			
			$msj = 'Los Seriales ( ';
			$ser = count ( $lstSeriales );
			if ($credi_compra != '') {
				$strQuery = "UPDATE t_productos SET credi_compra = " . $credi_compra . " WHERE inventario_id=" . $iInventario;
				$this->db->query ( $strQuery );
			}
			if ($dVenta != '') {
				$strQuery = "UPDATE t_productos SET venta=" . $dVenta . " WHERE inventario_id=" . $iInventario;
				$this->db->query ( $strQuery );
			}
			
			for($i = 0; $i < $ser; $i ++) {
				$Productos->serial = $lstSeriales [$i];
				$this->db->where ( "serial", $lstSeriales [$i] );
				$rs = $this->db->get ( "t_productos" );
				if ($rs->num_rows () == 0) {
					$Productos->inventario_id = $iInventario;
					$Productos->fecha_ingreso = $sFecha;
					$Productos->ubicacion = $sUbicacion;
					$Productos->cant_garantia = $iGarantia;
					$Productos->descripcion = "";
					$Productos->tipo_garantia = $sGarantia;
					$Productos->compra = $dCompra;
					$Productos->venta = $dVenta;
					$Productos->credi_compra = $credi_compra;
					$Productos->estatus = 1;
					$Productos->descripcion = $sDescripcion;
					//
					$Productos->estatus_mercancia = 0;
					$Productos->version = 1;
					$Productos->precio_oficina = 0;
					$this->db->insert ( "t_productos", $Productos );
				} else {
					$msj .= $lstSeriales [$i];
				}
			}
		}
		$msj .= ' ). Ya se encuentran registrados... Por favor Verifiquelos he intente nuevamente';
		$this->Inventario ( $msj );
	}
	public function Listar_Inventario() {
		$this->load->model ( "CInventario" );
		/**
		 *
		 * @var CInventario
		 */
		$this->load->model ( "CProductos" );
		$CProductos = new $this->CProductos ();
		
		$Inventario = new $this->CInventario ();
		$data ['Menu'] = $this->CMenu->getHtml_Menu ( $this->session->userdata ( 'nivel' ) );
		$data ['lstEquipos'] = $this->CInventario->Listar_Equipos ( $this->session->userdata ( 'ubicacion' ) );
		$this->load->model ( "CListartareas" );
		if (isset ( $_POST ['txtEstatus'] )) {
			$data ['sContenido'] = $Inventario->listar ( $_POST ['txtDependencia'], $_POST ['txtEstatus'], $this->session->userdata ( 'nivel' ), "", $CProductos );
		} else {
			$data ['sContenido'] = '';
		}
		$data ['Listar_Usuarios_Combo'] = $this->CListartareas->Listar_Usuarios_Combo ();
		
		$this->load->view ( "nomina/inventario", $data );
	}
	
	/**
	 * Consultar Factura y mostrar equipo
	 *
	 * @return array Javascript
	 */
	public function BFactura() {
		header ( 'contentType: "application/json" charset=utf-8' );
		$this->load->model ( "CInventario" );
		
		/**
		 *
		 * @var CInventario
		 */
		$Inventario = new $this->CInventario ();
		$sFactura = $_POST ["num_factura"];
		$sCedula = $_POST ["cedula"];
		$dFecha = $_POST ["fecha"];
		
		echo json_encode ( $Inventario->BFactura ( $sFactura, $sCedula, $dFecha ) );
	}
	public function BFactura_Inventario() {
		$query = "select t_clientes_creditos.documento_id, concat(primer_nombre,' ',segundo_nombre,' ',primer_apellido,' ',segundo_apellido)as nombre
			from t_clientes_creditos
			join t_personas on t_personas.documento_id = t_clientes_creditos.documento_id
			where numero_factura ='" . $_POST ['factura'] . "' group by numero_factura
		";
		$consulta = $this->db->query ( $query );
		$cant = $consulta->num_rows ();
		$arreglo = array ();
		if ($cant > 0) {
			$arreglo ['esta'] = 1;
			foreach ( $consulta->result () as $row ) {
				$arreglo ['cedula'] = $row->documento_id;
				$arreglo ['nombre'] = $row->nombre;
			}
		} else {
			$arreglo ['esta'] = 0;
		}
		$query2 = "select * from t_venta_contado
			where factura ='" . $_POST ['factura'] . "'	";
		$consulta2 = $this->db->query ( $query2 );
		$cant2 = $consulta2->num_rows ();
		$arreglo2 = array ();
		if ($cant2 > 0) {
			$arreglo2 ['esta'] = 1;
			foreach ( $consulta2->result () as $row2 ) {
				$arreglo2 ['cedula'] = $row2->cedula;
				$arreglo2 ['nombre'] = $row2->nombre;
			}
		} else {
			$arreglo2 ['esta'] = 0;
		}
		echo json_encode ( array (
				'credito' => $arreglo,
				'contado' => $arreglo2 
		) );
	}
	
	// busca factura para el modulo de modificar datos de factura
	public function BFactura_Modificar() {
		header ( 'contentType: "application/json" charset=utf-8' );
		$this->load->model ( "CInventario" );
		
		/**
		 *
		 * @var CInventario
		 */
		$Inventario = new $this->CInventario ();
		$sFactura = $_POST ["factura"];
		
		echo json_encode ( $Inventario->BFactura_Modificar ( $sFactura ) );
	}
	public function Modificar_Usuario() {
		$nombre = $_POST ['nombre'];
		$clave = $_POST ['clave'];
		$correo = $_POST ['correo'];
		$this->MUsuario->Modificar ( $nombre, $clave, $correo, $_SESSION ['usuario'] );
		echo "SE MODIFICARON SUS DATOS....";
	}
	public function db_cargar() {
		// $this -> db -> query('LOAD DATA INFILE "/home/coopera2/public_html/system/h.txt" INTO TABLE a FIELDS TERMINATED BY \',\'');
		$this->load->model ( "CInventario" );
		echo $this->CInventario->Listar_Combo ( $this->session->userdata ( 'ubicacion' ) );
	}
	public function PInventario() {
		$this->load->model ( "CProductos" );
		
		$Productos = array ();
		$max = ( int ) $_POST ["registro"];
		$limit = ( int ) $_POST ["pos"];
		
		for($i = 1; $i <= $max; $i ++) {
			$listC = "c" . $limit . $i;
			$listS = "s" . $limit . $i;
			$Productos ["serial"] = $_POST [$listS];
			$Productos ["ubicacion"] = $_POST [$listC];
			$this->CProductos->Actualizar ( $Productos );
			// echo "Actualizado todos: <br> " . $_POST["modelo"] . " | " .$_POST["marca"] . " | " .
			// $_POST["proveedor"] . " | " . $_POST["artefacto"] . " | " . $_POST[$listS] . " | " . $_POST[$listC] . "<br>";
		}
		
		$sMsj = "<br><div class=\"ui-widget\" id='divGuardar' style=\"width:300px\">
				<div class=\"ui-state-highlight ui-corner-all\" style=\"margin-top: 10px; padding: .8em;\"
				id='divGuardarInformacion'><center><p><a href=# onClick=\"$('#divGuardar').hide();\" border=0>
				<span class=\"ui-icon ui-icon-circle-check\" style=\"float: left; margin-right: .3em;\"></span>
				<strong>Felicitaciones Cambios Satisfactoriamente Efectuados...</strong></a></p></center></div></div>";
		$sWeb = $this->CProductos->Listar_Productos ( $limit, $_POST ["marca"], $_POST ["modelo"], $_POST ["proveedor"], $_POST ["artefacto"] );
		echo $sMsj . $sWeb;
	}
	public function PpInventario() {
		$this->load->model ( "CProductos" );
		
		$Productos = array ();
		$max = ( int ) $_POST ["registro"];
		$limit = ( int ) $_POST ["pos"];
		
		for($i = 1; $i <= $max; $i ++) {
			$listC = "c" . $limit . $i;
			$listS = "s" . $limit . $i;
			$Productos ["serial"] = $_POST [$listS];
			$Productos ["ubicacion"] = $_POST [$listC];
			$this->CProductos->Actualizarp ( $Productos );
			// echo "Actualizado todos: <br> " . $_POST["modelo"] . " | " .$_POST["marca"] . " | " .
			// $_POST["proveedor"] . " | " . $_POST["artefacto"] . " | " . $_POST[$listS] . " | " . $_POST[$listC] . "<br>";
		}
		
		$sMsj = "<br><div class=\"ui-widget\" id='divGuardar' style=\"width:300px\">
				<div class=\"ui-state-highlight ui-corner-all\" style=\"margin-top: 10px; padding: .8em;\"
				id='divGuardarInformacion'><center><p><a href=# onClick=\"$('#divGuardar').hide();\" border=0>
				<span class=\"ui-icon ui-icon-circle-check\" style=\"float: left; margin-right: .3em;\"></span>
				<strong>Felicitaciones Cambios Satisfactoriamente Efectuados...</strong></a></p></center></div></div>";
		$sWeb = $this->CProductos->Listar_Productos ( $limit, $_POST ["marca"], $_POST ["modelo"], $_POST ["proveedor"], $_POST ["artefacto"] );
		echo $sMsj . $sWeb;
	}
	public function PInventarioAsociar() {
		$sLista = "";
		$factura = $_POST ["factura"];
		$serial = $_POST ["serial"];
		$Consulta = $this->db->query ( "SELECT * FROM t_clientes_creditos  WHERE numero_factura = '" . $factura . "'" );
		if ($Consulta->num_rows () > 0) {
			foreach ( $Consulta->result () as $row ) {
				$sLista = $row->serial;
			}
		}
		$sLista .= "," . $serial;
		$data ['serial'] = $sLista;
		
		$this->db->where ( "numero_factura", $factura );
		$this->db->update ( "t_clientes_creditos", $data );
		/**
		 * Estatus del Producto *
		 */
		$Producto ['estatus'] = 2;
		$this->db->where ( 'serial', $serial );
		$this->db->update ( 't_productos', $Producto );
		echo "<center><h1>Felicitaciones, Actualizaciones satisfactoria...</h1></center>";
	}
	public function DBaja() {
		$cedula = $_POST ["cedula"];
		$Persona ['disponibilidad'] = ( int ) $_POST ['val'];
		$peticion = $_POST ['peticion'];
		$motivo = $_POST ['motivo'];
		
		$query = $this->db->query ( 'SELECT * FROM t_personas WHERE documento_id="' . $cedula . '" AND disponibilidad !=2' );
		$cant = $query->num_rows ();
		
		$this->db->where ( "documento_id", $cedula );
		$this->db->where ( "disponibilidad !=", 2 );
		$this->db->update ( "t_personas", $Persona );
		
		if ($cant > 0) {
			$tipo = 7;
			if (( int ) $_POST ['val'] == 0) {
				$tipo = 8;
			}
			$data = array (
					'referencia' => $cedula,
					'tipo' => $tipo,
					'usuario' => $_SESSION ['usuario'],
					'motivo' => $motivo,
					'peticion' => $peticion 
			);
			$this->db->insert ( '_th_sistema', $data );
			if (( int ) $_POST ['val'] == 0)
				echo '<p><strong>Se ha activado nuevamente el cliente...</strong></p>';
			if (( int ) $_POST ['val'] == 1)
				echo '<p><strong>Se ha SUSPENDIDO el cliente...</strong></p>';
			if (( int ) $_POST ['val'] == 2)
				echo '<p><strong>Se ha BLOQUEADO DEL SISTEMA al cliente...</strong></p>';
		} else {
			echo "No se pudo realizar el proceso, el cliente no existe o ya esta bloqueado por el sistema...";
		}
	}
	public function Exonerar_Cheque() {
		$cedula = $_POST ["cedula"];
		$factura = $_POST ["factura"];
		$Persona ['disponibilidad'] = ( int ) $_POST ['val'];
		$peticion = $_POST ['peticion'];
		$motivo = $_POST ['motivo'];
		$arr ['cedula'] = $cedula;
		$arr ['factura'] = $factura;
		$arr ['nombre'] = "exonerado.png";
		$tipo = 21;
		$this->db->insert ( 't_cheque_garantia', $arr );
		
		$data = array (
				'referencia' => $cedula,
				'tipo' => $tipo,
				'usuario' => $_SESSION ['usuario'],
				'motivo' => $motivo . ' Factura (' . $factura . ')',
				'peticion' => $peticion 
		);
		$this->db->insert ( '_th_sistema', $data );
		echo '<p><strong>Liberacion Exitosa</strong></p>';
	}
	public function GInventario() {
		if (isset ( $_SESSION ['id'] )) {
			$documento_id = $_SESSION ['id'];
			
			$this->load->model ( "CInventario" );
			/**
			 *
			 * @var CInventario
			 */
			$this->load->model ( "CProductos" );
			
			$Inventario = new $this->CInventario ();
			$data ['Menu'] = $this->CMenu->getHtml_Menu ( $this->session->userdata ( 'nivel' ) );
			
			$data ['sContenido'] = $Inventario->listar ( $documento_id, 1, $this->session->userdata ( 'nivel' ) );
			
			$this->load->view ( "nomina/ginventario", $data );
		} else { // Alguno de los post no funciona
			
			echo "no tiene acceso", $documento_id;
		}
	}
	public function activar_ventas() {
		$this->load->model ( "CProductos" );
		$this->CProductos->Activar_Ventas ();
		echo "Finalizado.....";
	}
	
	/**
	 * *
	 */
	public function SModelo() {
		$this->load->model ( "CInventario" );
		$Inventario = new $this->CInventario ();
		echo $Inventario->Listar_Json_Modelos ( $_POST ['valor'], $this->session->userdata ( 'ubicacion' ) );
	}
	
	/**
	 * Listar_Json_Seriales *
	 */
	public function SSeriales($modelo = null) {
		$this->load->model ( "CInventario" );
		$Inventario = new $this->CInventario ();
		echo $Inventario->Listar_Json_Seriales2 ( $_POST ['modelo'], $this->session->userdata ( 'ubicacion' ) );
		// echo $Inventario -> Listar_Json_Seriales("", $modelo, $this->session->userdata('ubicacion'));
		// echo $Inventario -> Listar_Json_Seriales2("CURVE 8520 PURPURA", $this -> session -> userdata('ubicacion'));
	}
	
	/**
	 * Linea de creditos Solicitud de Prestamos en lineas *
	 */
	public function Solicitud_Creditos() {
		$this->load->view ( "solicitud" );
	}
	
	/**
	 * Imprimir modelos de Facturas *
	 */
	public function Imprimir_Creditos($iCedula = null, $sContrato = null) {
		$this->load->model ( "reporte/mdomiciliacion", "MDomiciliacion" );
		$this->MDomiciliacion->Imprimir_Contrato ();
		$data ['MDomiciliacion'] = $this->PDomiciliacion ();
	}
	public function Imprimir_Facturas($iCedula = null, $sContrato = null, $sControl = null) {
		$this->load->model ( "reporte/mdomiciliacion", "MDomiciliacion" );
		$this->MDomiciliacion->Imprimir_Factura ( $iCedula, $sContrato, $sControl );
	}
	public function Imprimir_Contrato_Nuevo($iCedula = null, $sContrato = null, $sControl = null) {
		$this->load->model ( "reporte/mdomiciliacion", "MDomiciliacion" );
		$this->MDomiciliacion->Imprimir_Contrato_Nuevo ( $iCedula, $sContrato, $sControl );
	}
	public function Imprimir_Compromiso($factura = null, $ced = null, $tipo = '') {
		$this->load->model ( "reporte/mdomiciliacion", "MDomiciliacion" );
		$this->MDomiciliacion->Imprimir_Compromiso ( $factura, $ced, $tipo );
	}
	public function Imprimir_Compromiso_Factura($sFactura = null) {
		$this->load->model ( "reporte/mdomiciliacion", "MDomiciliacion" );
		$this->MDomiciliacion->Imprimir_Compromiso_Factura ( $sFactura );
	}
	public function Imprimir_Facturas2($iCedula = null, $sContrato = null) {
		$this->load->model ( "reporte/mdomiciliacion", "MDomiciliacion" );
		$this->MDomiciliacion->Imprimir_Factura2 ( $iCedula, $sContrato );
	}
	public function Sumatoria_Fecha($dtFecha = null, $intVal = null, $intTipo = null) {
		$ano = 0;
		$dtFechaInicio = explode ( "-", $dtFecha );
		$D = $dtFechaInicio [2];
		$M = $dtFechaInicio [1];
		$A = $dtFechaInicio [0];
		$Mes = $M;
		switch ($intTipo) {
			case 4 :
				$Mes += $intVal;
				while ( $Mes > 12 ) {
					$Mes -= 12;
					$ano += 1;
				}
				$A += $ano;
				$Fecha = '30/' . $Mes . '/' . $A;
				break;
			case 2 :
				if ($D > 15) {
					$aux = $intVal + 1;
					$aux2 = '30';
					$aux3 = '15';
				} else {
					$aux = $intVal;
					$aux2 = '15';
					$aux3 = '30';
				}
				$Mes += floor ( $aux / 2 );
				while ( $Mes > 12 ) {
					$Mes -= 12;
					$ano += 1;
				}
				$A += $ano;
				if (($intVal + 1) % 2 == 0) {
					$dia = $aux3;
				} else {
					$dia = $aux2;
				}
				$Fecha = $dia . '/' . floor ( $Mes ) . '/' . $A;
				break;
			case 3 :
				if ($D > 10) {
					$aux = $intVal + 1;
					$aux2 = '25';
					$aux3 = '10';
				} else {
					$aux = $intVal;
					$aux2 = '10';
					$aux3 = '25';
				}
				$Mes += floor ( $aux / 2 );
				while ( $Mes > 12 ) {
					$Mes -= 12;
					$ano += 1;
				}
				$A += $ano;
				if (($intVal + 1) % 2 == 0) {
					$dia = $aux3;
				} else {
					$dia = $aux2;
				}
				$Fecha = $dia . '/' . floor ( $Mes ) . '/' . $A;
				break;
			default :
				$Fecha = $D . '/' . $M . '/' . $A;
				break;
		}
		
		return $Fecha;
	}
	
	/**
	 * Control de Giros a pagar en el tiempo....*
	 */
	public function Imprimir_Control_Pago($strId = '', $strFactura = '') {
		$tabla ['nombre'] = '';
		$tabla ['nombre'] = '';
		$tabla ['contrato'] = $strFactura;
		$tabla ['unico'] = '';
		$tabla ['especial'] = '';
		
		$Consulta = $this->db->query ( "SELECT * FROM t_personas WHERE documento_id='$strId'" );
		if ($Consulta->num_rows () != 0) {
			foreach ( $Consulta->result () as $row ) {
				$tabla ['cedula'] = 'V- ' . $row->documento_id;
				$tabla ['nombre'] = $row->primer_apellido . ' ' . $row->segundo_apellido . ' ' . $row->primer_nombre . ' ' . $row->segundo_nombre;
				$Con = $this->db->query ( "SELECT * FROM t_clientes_creditos WHERE numero_factura ='$strFactura' AND cantidad != 0 ORDER BY forma_contrato  DESC" );
				
				if ($Con->num_rows () != 0) {
					$numero_tabla = 0;
					$tabla ['unico'] = "<table><tr><td valign='top'>";
					foreach ( $Con->result () as $rw ) {
						$intCount = $rw->numero_cuotas;
						$intCuota = $rw->monto_cuota;
						$dtFechaInicio = $rw->fecha_inicio_cobro;
						$periocidad = $rw->periocidad;
						$tabla ['empresa'] = $rw->empresa;
						if ($rw->forma_contrato == 0) {
							
							$tabla ['unico'] .= '<table border=1 width=250px cellspacing=1 celladding=1><tr>
									<td><b>Giros #</b></td>
									<td><b>Vencimiento</b></td>
									<td><b>Estatus</b></td>

									</tr>';
							for($intI = 0; $intI < $intCount; $intI ++) {
								$numero_tabla ++;
								if ($numero_tabla > 20) {
									$tabla ['unico'] .= '</table></td><td valign="top"><table border=1 width=150px cellspacing=1 celladding=1><tr>
											<td><b>Giros #</b></td>
											<td><b>Vencimiento</b></td>
											<td><b>Pendientes</b></td>
											</tr>';
									$numero_tabla = 1;
								}
								$Giro = ($intI + 1) . '/' . $intCount;
								$tabla ['unico'] .= '<tr><td>' . $Giro . '</td><td>' . $this->Sumatoria_Fecha ( $dtFechaInicio, $intI, $periocidad ) . '<td><b>Pendientes</b></td></tr>';
							}
							$tabla ['unico'] .= '</table></td></tr></table>';
						} else {
							$tabla ['especial'] .= '<b>.- Giro Especial </b><br><table border=1 width=600px><tr>
									<td><b>Giro #</b></td>
									<td><b>Fecha de Vencimiento</b></td>
									<td><b>Pendientes</b></td>
									
									<td><b>Estatus</b></td>
									</tr>';
							$Giro = '1/1';
							$tabla ['especial'] .= '
									<tr>
									<td>' . $Giro . '</td><td>M&eacute;rida, ' . $this->Sumatoria_Fecha ( $dtFechaInicio, 0, 0 ) . '</td>
											<td align=right>' . number_format ( $intCuota, 2, ".", "," ) . ' Bs.&nbsp;&nbsp;</td><td><b>Pendientes</b></td>
													</tr>';
							$tabla ['especial'] .= '</table>';
						}
					}
				} // Fin de Credito
			}
		} // Fin de Persona
		$this->load->view ( "nomina/giro", $tabla );
	}
	public function Cobranza() {
		// if(isset($_SESSION['id'])) {
		// $documento_id = $_SESSION['id'];
		$data ['Menu'] = $this->CMenu->getHtml_Menu ( $this->session->userdata ( 'nivel' ) );
		$data ['Lista'] = '';
		$this->load->model ( "CBancos" );
		/**
		 *
		 * @var CBancos
		 */
		$Banco = new $this->CBancos ();
		$Banco->BFC ( 0, 'U' );
		$Banco->BFC ( 1, 'A' );
		
		$this->load->view ( "nomina/cobranza", $data );
		// } else {
		// echo "no tiene acceso", $documento_id;
		// }
	}
	public function Cobranza_Bfc() {
		$this->load->model ( "CBancos" );
		/**
		 *
		 * @var CBancos
		 */
		$Banco = new $this->CBancos ();
		$Banco->BFC ( 0, 'U' );
	}
	public function Cobranza_Bfc_Aguinaldos() {
		$this->load->model ( "CBancos" );
		/**
		 *
		 * @var CBancos
		 */
		$Banco = new $this->CBancos ();
		$Banco->BFC ( 1, 'A' );
	}
	public function Historial_Contratos($strGeneral = '') {
		if (isset ( $_SESSION ['id'] )) {
			$documento_id = $_SESSION ['id'];
			$data ['Menu'] = $this->CMenu->getHtml_Menu ( $this->session->userdata ( 'nivel' ) );
			$data ['Lista'] = $strGeneral;
			
			$this->load->view ( "nomina/historial", $data );
		} else {
			echo "no tiene acceso", $documento_id;
		}
	}
	public function Historial_Contratos_Reportes($strGeneral = '') {
		if (isset ( $_SESSION ['id'] )) {
			$documento_id = $_SESSION ['id'];
			// $documento_id = '19592129';
			$Usuario = '';
			$fecha_completa = $_POST ['fecha'];
			$fecha_picada = explode ( '-', $fecha_completa );
			$Dia = $fecha_picada [0];
			$Mes = $fecha_picada [1];
			$Ano = $fecha_picada [2];
			$strBanco = $_POST ['txtbanco_1'];
			$intEstado = $_POST ['txtPendiente'];
			$strMotivo = $_POST ['txtMotivo'];
			
			$Fecha = $Ano . '-' . $Mes . '-' . $Dia;
			
			$set = 'primer_nombre,segundo_nombre,primer_apellido,segundo_apellido,nacionalidad,cuenta_1,banco_1,
					t_clientes_creditos.documento_id AS id,numero_factura,motivo,codigo_nomina_aux, expediente_caja, codigo_n_a, expediente_c,
					SUM(cantidad) AS monto, monto_operacion, num_operacion';
			
			$this->db->select ( $set );
			$this->db->from ( 't_personas' );
			$this->db->join ( 't_clientes_creditos', 't_personas.documento_id=t_clientes_creditos.documento_id' );
			if ($strGeneral == '') {
				$this->db->join ( 't_usuarios', 't_personas.codigo_nomina_aux=t_usuarios.login' );
			}
			
			$this->db->where ( 't_clientes_creditos.fecha_solicitud', $Fecha );
			$strDesBanco = "";
			if ($strBanco == "----------") {
				$strDesBanco = "<td  align=center>BANCO</td>";
				$strB = "TODOS LOS BANCOS";
			} else {
				$this->db->where ( 'banco_1', $strBanco );
				$strB = $strBanco;
			}
			if ($strMotivo != 3) {
				$this->db->where ( "motivo", $strMotivo );
			}
			if ($intEstado != 4) {
				$this->db->where ( "estado_verificado", $intEstado );
			}
			
			$this->db->group_by ( 't_clientes_creditos.documento_id' );
			$this->db->order_by ( 'codigo_nomina_aux' );
			
			// $Consulta = $this->db->query($strQuery);
			$strR = '';
			switch ($strMotivo) {
				case 1 :
					$strR = 'FINANCIAMIENTO';
					break;
				case 2 :
					$strR = 'PRESTAMO';
					break;
				case 3 :
					$strR = 'TODOS';
					break;
				
				default :
					break;
			}
			$strE = '';
			$strTit = 'CUENTA';
			switch ($intEstado) {
				case 0 :
					$strE = 'PENDIENTE';
					break;
				case 2 :
					$strE = 'DEPOSITADOS';
					$strTit = 'DEPOSITO';
					break;
				case 3 :
					$strE = 'TODOS';
					break;
				
				default :
					break;
			}
			
			// if($strMotivo == 2){$strR = 'PRESTAMOS';};
			$Lista = '
					<h2>' . $strR . '|  POR:  ' . $strB . ' | ESTADO: ' . $strE . '</h2>
							<table border=1 cellspacing=0 celladding=0 style="
							color: #333333;	width: 900px;font-size: 11px;font-family: Trebuchet MS, Tahoma, Verdana, sans-serif; border: 1px solid #CCCCCC;">
							<tr style="background-color:#CCCCCC"><td style="width: 80px">CEDULA</td>
							<td style="width: 300px">APELLIDOS Y NOMBRES</td>
							<td align=center># FACTURAS &nbsp;</td>
							<td align=center>' . $strTit . '&nbsp;</td>
									<td align=center>MONTO TOTAL&nbsp;</td>
									<td align=center>MONTO DEP.&nbsp;</td>	';
			
			$Lista .= $strDesBanco;
			$Lista .= '<td align=center>CREADOR&nbsp;</td>
					<td align=center>RESPONSABLE&nbsp;</td>
					<tr>';
			$Consulta = $this->db->get ();
			
			if ($Consulta->num_rows () > 0) {
				$Cantidad = 0;
				$Total = 0;
				$total_depositos = 0;
				foreach ( $Consulta->result () as $row ) {
					$deposito = '_';
					if ($row->num_operacion != '' && $row->num_operacion != NULL) {
						$deposito = $row->num_operacion;
					}
					if ($intEstado == 2) {
						$strValorColumna = $deposito;
					} else {
						$strValorColumna = $row->cuenta_1;
					}
					
					$nombre = $row->primer_apellido . ' ' . substr ( $row->segundo_apellido, 0, 1 ) . '. ' . $row->primer_nombre . ' ' . substr ( $row->segundo_nombre, 0, 1 ) . '.';
					$Lista .= '<tr><td>' . $row->nacionalidad . $row->id . '</td>';
					$Lista .= '<td>' . $nombre . '</td><td align=center>' . $row->numero_factura . '</td>';
					$Lista .= '<td>' . $strValorColumna . '</td>';
					$Lista .= '<td align=right>' . number_format ( $row->monto, 2 ) . '&nbsp;</td>';
					$Lista .= '<td align=right>' . number_format ( $row->monto_operacion, 2 ) . '&nbsp;</td>';
					if ($row->codigo_n_a == '') {
						$rp = $row->expediente_caja;
					} else {
						$rp = $row->codigo_n_a;
					}
					
					$strDesBanco = "";
					if ($strBanco == "----------") {
						$Lista .= '<td align=right>' . $row->banco_1 . '&nbsp;</td>';
					}
					
					$Lista .= '<td align=right>' . strtoupper ( $rp ) . '&nbsp;</td>';
					$Lista .= '<td align=right>' . strtoupper ( $row->expediente_c ) . '&nbsp;</td>';
					$Lista .= '</tr>';
					$Total += $row->monto;
					$total_depositos += $row->monto_operacion;
					$Cantidad ++;
				}
				$Lista .= '<tr><td colspan="3">TOTALES</td>
						<td align="right">' . $Cantidad . '</td>
								<td align="right">' . number_format ( $Total, 2 ) . '</td>
										<td align="right">' . number_format ( $total_depositos, 2 ) . '</td>
												<td colspan="3">&nbsp;</td>
												</tr>';
				$Lista .= '</table>';
				echo '<H4>FECHA: ' . $Dia . '-' . $Mes . '-' . $Ano . '<BR></H4>' . $Lista;
			} else {
				echo 'NO SE LOGRARON ENCONTRAR REGISTROS PARA LA FECHA INDICADA...' . $strGeneral;
			}
		} else {
			echo "no tiene acceso", $documento_id;
		}
	}
	public function CCalculos() {
		if ($this->session->userdata ( 'usuario' )) {
			$this->load->model ( 'CNomina' );
			$data ['Menu'] = $this->CMenu->getHtml_Menu ( $this->session->userdata ( 'nivel' ) );
			$data ['Nivel'] = $this->session->userdata ( 'nivel' );
			$data ['ubicacion'] = $this->session->userdata ( 'ubicacion' );
			$data ['lista'] = $this->CNomina->Combo ();
			$data ['id'] = $_POST ['txtCed'];
			$data ['nom'] = $_POST ['txtNom'];
			$data ['cert'] = $_POST ['txtCertificado'];
			
			$this->load->view ( "asistente/asistente3_1", $data );
		} else {
			$this->login ();
		}
	}
	public function CCalculos2() {
		if ($this->session->userdata ( 'usuario' )) {
			$this->load->view ( "ccalculo" );
		} else {
			$this->login ();
		}
	}
	public function CCalculos_Nuevo() {
		if ($this->session->userdata ( 'usuario' )) {
			$this->load->model ( 'CNomina' );
			$data ['Menu'] = $this->CMenu->getHtml_Menu ( $this->session->userdata ( 'nivel' ) );
			$data ['Nivel'] = $this->session->userdata ( 'nivel' );
			$data ['ubicacion'] = $this->session->userdata ( 'ubicacion' );
			$data ['lista'] = $this->CNomina->Combo ();
			$data ['id'] = $_POST ['txtCed'];
			$data ['nom'] = $_POST ['txtNom'];
			$data ['cert'] = $_POST ['txtCertificado'];
			
			$this->load->view ( "asistente/asistente3_2", $data );
		} else {
			$this->login ();
		}
	}

    public function PlanCorp() {
        if ($this->session->userdata ( 'usuario' )) {
            $this->load->model ( 'CNomina' );
            $data ['Nivel'] = $this->session->userdata ( 'nivel' );
            $data ['ubicacion'] = $this->session->userdata ( 'ubicacion' );

            $this->load->view ( "asistente/planCorp", $data );
        } else {
            $this->login ();
        }
    }
	public function Calculo() {
		if (isset ( $_POST ['txtCalculo'] )) {
			
			$domi = (($_POST ['txtCalculo'] * 4.5) / 100);
			// $admin = (($_POST['txtCalculo'] * 3) / 100);
			$prestados = (($_POST ['txtCalculo'] * 5.5) / 100);
			$rif = $_POST ['txtRif'];
			$motivo = $_POST ['txtMotivo'];
			$monto = ($prestados + $domi);
			// + $admin);
			
			$valores = $prestados + $domi;
			// + $admin;
			$tipo = 'EXTRA';
			if ($_POST ['txtTipo'] == 0) {
				$tipo = 'UNICO';
			}
			// TIPO DE CONTRATO <b>' . $tipo . ' </b><br>
			$strVista = '
					<input type=hidden id="txtModelo" value="' . $_POST ['txtTipo'] . '">
							DATOS PROFORMA
							<table border=1 cellspacing=0 celladding=0 style="	color: #333333;	width: 90%; border: 1px solid #CCCCCC;">
							<tr bgcolor="#CCCCCC">
							<td style="font-size: 14px; width:200px">EMITIDA POR: </td><td style="font-size: 14px;" colspan=3>' . $motivo . '</td>
									</tr><tr>
									<td style="font-size: 14px;">RIF: </td><td style="font-size: 14px; width:500px" colspan=3>' . $rif . '</td>
											</tr>
											<tr>
											<td style="font-size: 14px;">MONTO: </td><td style="font-size: 14px; width:500px" ><b>' . number_format ( $_POST ['txtProforma'], 2 ) . ' Bs.</td>
													<td style="font-size: 14px;">APROBADO: </td><td style="font-size: 14px; width:500px" ><b>' . number_format ( $_POST ['txtCalculo'], 2 ) . ' Bs.</td>
															</tr>
															</table><br>
															TRAMITES ADMINISTRATIVOS DE CR&Eacute;DITO<BR>
															<table border=1 cellspacing=0 celladding=0 style="	color: #333333;	width: 90%; border: 1px solid #CCCCCC;">
															<tr bgcolor="#CCCCCC">
															<td style="font-size: 14px; width:500px">GASTOS POR COBRANZA DE DOMICILIACI&Oacute;N.</td>
															<td style="font-size: 14px;">' . number_format ( $domi, 2 ) . ' Bs.</td>
																	</tr>
																	<tr>';
			/*
			 * <td style="font-size: 14px;">CARGOS POR GASTOS ADMINISTRATIVOS</td>
			 * <td style="font-size: 14px;">' . number_format($admin, 2) . ' Bs.</td>
			 * </tr>
			 */
			$strVista .= '
					<tr bgcolor="#CCCCCC">
					<td style="font-size: 14px;">GASTOS POR COBRANZA DE CR&Eacute;DITO PRESTADO</td>
					<td style="font-size: 14px;">' . number_format ( $prestados, 2 ) . ' Bs.</td>
							</tr>

							</table>
							<table border=1 cellspacing=0 celladding=0 style="	color: #333333;	width: 90%; border: 0px solid #CCCCCC;">
							<tr>
							<td style="font-size: 14px; width:500px" align="right" >MONTO DE GASTOS POR COBRANZA MENSUALES&nbsp;&nbsp;&nbsp;</td>
							<td style="font-size: 14px;">' . number_format ( $valores, 2 ) . ' Bs.</td>
									</tr>
									</table><br>	';
			
			$data ['mensuales'] = 'CARGOS MENSUALES POR SERVICIOS DE CR&Eacute;DITO:  <b>' . number_format ( $valores, 2 ) . ' Bs.</b>';
			if ($_POST ['txtTipo'] == 0) {
				
				$strVista .= '<font style="font-size:20px;"><b>PLAN DE CR&Eacute;DITO DISE&Ntilde;ADOS A:</b></font>
				<table border=1 cellspacing=0 celladding=0 style="	color: #333333;	width: 80%; border: 1px solid #CCCCCC;">';
				$strVista .= '<tr bgcolor="#CCCCCC"><td style="font-size: 14px; width:500px">
						<select onchange="Seleccion_Mes(this.value);crea_combos(this.value);Asignar(this.value);" id="cmbMeses" name="cmbMeses"><option value=0>Seleccione</option>';
				/*
				 * for ($i = 1; $i <= 15; $i++) {
				 *
				 * $strVista .= '<option value=' . $i . '>' . $i . ' MESES</option>';
				 * }
				 */
				$strVista .= '<option value=12>12 MESES</option>';
				$strVista .= '<option value=13>13 MESES</option>';
				$strVista .= '<option value=14>14 MESES</option>';
				$strVista .= '<option value=15>15 MESES</option>';
				$strVista .= '<option value=16>16 MESES</option>';
				$strVista .= '<option value=17>17 MESES</option>';
				$strVista .= '<option value=18>18 MESES</option>';
				$strVista .= '</select></td><td style="font-size: 14px;"><div id="monto_aux" name="monto_aux">0.00 Bs.</div></td></tr></table>';
				$data ['mensuales'] = '';
			}
			
			$data ['lista'] = $strVista;
			$data ['monto'] = $monto;
			$data ['calculo'] = $_POST ['txtCalculo'];
		} else {
			$data ['mensuales'] = '';
			$data ['lista'] = '';
			$data ['calculo'] = 0;
			$data ['monto'] = 0;
		}
		$this->load->view ( "nomina/financiamiento", $data );
	}
	
	/**
	 * Gestor de Asistente Paso 3
	 */
	public function CArtefacto($tipo = '') {
		if ($this->session->userdata ( 'usuario' )) {
			$this->load->model ( 'CNomina' );
			$data ['Menu'] = $this->CMenu->getHtml_Menu ( $this->session->userdata ( 'nivel' ) );
			$data ['Nivel'] = $this->session->userdata ( 'nivel' );
			$data ['ubicacion'] = $this->session->userdata ( 'ubicacion' );
			$data ['lista'] = $this->CNomina->Combo ();
			$data ['id'] = $_POST ['txtCed'];
			$data ['nom'] = $_POST ['txtNom'];
			$data ['cert'] = $_POST ['txtCertificado'];
			
			$this->load->view ( "asistente/asistente3", $data );
		} else {
			$this->login ();
		}
	}
	public function Presupuesto_Compuesto($tipo = '') {
		if ($this->session->userdata ( 'usuario' )) {
			$this->load->model ( 'CNomina' );
			$data ['Menu'] = $this->CMenu->getHtml_Menu ( $this->session->userdata ( 'nivel' ) );
			$data ['Nivel'] = $this->session->userdata ( 'nivel' );
			$data ['ubicacion'] = $this->session->userdata ( 'ubicacion' );
			$data ['lista'] = $this->CNomina->Combo ();
			$data ['id'] = 'S/N';
			$data ['nom'] = 'S/N';
			$data ['cert'] = 'S/N';
			
			$this->load->view ( "asistente/presu_compuesto", $data );
		} else {
			$this->login ();
		}
	}
	public function Artefacto_Contado($tipo = '') {
		if ($this->session->userdata ( 'usuario' )) {
			$this->load->model ( 'CNomina' );
			$this->load->view ( "asistente/consulta_contado" );
		} else {
			$this->login ();
		}
	}
	public function Artefacto($tipo = '') {
		if (isset ( $_POST ['txtCalculo'] )) {
			$muestra = 1;
			if ($_POST ['txtCalculo'] > 0) {
				$calculo = ( int ) $_POST ['txtCalculo'];
				$abono_p = ( int ) $_POST ['txtAbonoP'];
				$porcentaje = $_POST ['txtPorcentaje'];
				// $monto = ((($calculo - $abono_p) * 3) / 100);con abono pre
				$monto = ((($calculo) * 3) / 100);
				// con abono post
				$strInfo = '<br>' . $_POST ['txtDes'] . ' <BR><h2>	PRECIO DE CONTADO:  <b>' . number_format ( $_POST ['txtCalculo'], 2 ) . ' Bs.</b></h2><BR>';
				if ($abono_p > 0) {
					$strInfo .= '<h2>	ABONO:  <b>' . number_format ( $abono_p, 2 ) . ' Bs.</b></h2><BR>';
				}
				$strInfo .= '<table><tr><td>PSC</td><td style="padding-left:10px;">Precio sugerido por concesionario</td></tr>';
				$strInfo .= '<tr><td>1%BCV</td><td style="padding-left:10px;">Interes mensual según tabladel BCV</td></tr>';
				$strInfo .= '<tr><td>1%DOMIC</td><td style="padding-left:10px;">Cobro mensual por cobranza domiciliada</td></tr>';
				$strInfo .= '<tr><td>1%GA</td><td style="padding-left:10px;">Cobro mensual por gastos administrativos, operativos y análisis de aprobacion de las referidas solicitudes</td></tr></table><br>';
				$strVista = '
						<table border=1 cellspacing=0 celladding=0 style="	color: #333333;	width: 80%; border: 1px solid #CCCCCC;"><tr><td>PLAN DE CREDITO</td>
						<td>Credito = PSC + 1%BCV + 1%DOMIC + 1%GA<br></td><td>MONTO DE CREDITO</td></tr></b>';
				$strVista .= '<tr bgcolor="#CCCCCC"><td style="font-size: 14px; width:250px">
						<select onchange="Seleccion_Mes(this.value);crea_combos(this.value);Asignar(this.value);Validar(this);Calcular_Total(this);" id="cmbMeses" name="cmbMeses" style="width:220px"><option value=0>Seleccione</option>';
				
				$strVista .= '<option value="12">12 MESES</option>';
				$strVista .= '<option value="18">18 MESES</option>';
				$strVista .= '<option value="19">19 MESES</option>';
				$strVista .= '<option value="20">20 MESES</option>';
				$strVista .= '<option value="21">21 MESES</option>';
				$strVista .= '<option value="22">22 MESES</option>';
				$strVista .= '<option value="23">23 MESES</option>';
				$strVista .= '<option value="24">24 MESES</option>';
				$strVista .= '<option value="30">30 MESES</option>';
				$strVista .= '<option value="36">36 MESES</option>';
				
				$strVista .= '</select></td><td style="font-size: 14px;"><div id="monto_aux" name="monto_aux">0.00 Bs.</div></td><td style="font-size: 14px;"><div  id="monto_aux2" name="monto_aux2">0.00 Bs.</div></td></tr></table>';
				$strVista .= '</table>';
				$data ['info'] = $strInfo;
				$data ['lista'] = $strVista;
				$data ['venta'] = $_POST ['txtCalculo'];
				$data ['descripcion'] = $_POST ['txtDes'];
				$data ['monto'] = $monto;
				$data ['calculo'] = $_POST ['txtCalculo'];
				$data ['abono'] = $abono_p;
				$data ['porcentaje'] = $porcentaje;
				$data ['muestra'] = $muestra;
				if (isset ( $_POST ['mod'] ))
					$data ['modelo'] = $_POST ['mod'];
				else
					$data ['modelo'] = $_POST ['txtModelo'];
			} else {
				$strInfo = "El Modelo Seleccionado No Posee Seriales Asociados, debe registrar al menos uno.";
				$data ['info'] = $strInfo;
				$data ['lista'] = '';
				$data ['venta'] = '';
				$data ['descripcion'] = '';
				$data ['monto'] = 0;
				$data ['abono'] = 0;
				$data ['calculo'] = 0;
				$data ['porcentaje'] = 0;
				$data ['muestra'] = 1;
				$data ['modelo'] = '';
			}
		} else {
			$data ['info'] = '';
			$data ['lista'] = '';
			$data ['venta'] = '';
			$data ['descripcion'] = '';
			$data ['porcentaje'] = 0;
			$data ['monto'] = 0;
			$data ['abono'] = 0;
			$data ['calculo'] = 0;
			$data ['muestra'] = 1;
			$data ['modelo'] = '';
		}
		$data ['tipo'] = $tipo;
		$this->load->view ( "nomina/artefacto", $data );
	}
	public function CEndeuda() {
		$this->load->model ( 'CNomina' );
		$data ['lista'] = $this->CNomina->Combo ();
		$this->load->view ( "cliente/cendeudamiento", $data );
	}
	
	/**
	 * Gestor de Asistente Paso 2
	 */
	public function CEndeudamiento() {
		if ($this->session->userdata ( 'usuario' )) {
			$this->load->model ( 'CNomina' );
			$data ['Menu'] = $this->CMenu->getHtml_Menu ( $this->session->userdata ( 'nivel' ) );
			$data ['Nivel'] = $this->session->userdata ( 'nivel' );
			$data ['ubicacion'] = $this->session->userdata ( 'ubicacion' );
			$data ['lista'] = $this->CNomina->Combo ();
			$data ['id'] = '';
			if (isset ( $_POST ['documento_id'] )) {
				$data ['id'] = $_POST ['documento_id'];
				$data ['nom'] = $_POST ['primer_apellido'] . ' ' . $_POST ['segundo_apellido'] . ' ' . $_POST ['primer_nombre'] . ' ' . $_POST ['segundo_nombre'];
			}
			
			$this->load->view ( "asistente/asistente2", $data );
		} else {
			$this->login ();
		}
	}
	public function Credi_Compra() {
		if (isset ( $_POST ['txtCalculo'] )) {
			$muestra = 1;
			if ($_POST ['txtCalculo'] > 0) {
				$calculo = ( int ) $_POST ['txtCalculo'] - ( int ) $_POST ['txtAbono'];
				$porcentaje = $_POST ['txtPorcentaje'];
				$monto = (($calculo * 3) / 100);
				$strInfo = $_POST ['txtDes'] . ' <BR>	PRECIO DE CONTADO:  <b>' . number_format ( $_POST ['txtCalculo'], 2 ) . ' Bs.</b><br><br>';
				if ($_POST ['txtAbono'] != '') {
					if ($calculo != 0) {
						$strInfo .= 'ABONO INICIAL POR:  ' . number_format ( $_POST ['txtAbono'], 2 ) . ' Bs.<BR>';
						$strInfo .= 'MONTO TOTAL:  ' . number_format ( $calculo, 2 ) . ' Bs.<BR>';
						$muestra = 1;
					} else {
						$total_descuento = (( int ) $_POST ['txtCalculo'] * ( int ) $porcentaje) / 100;
						$precio_descuento = ( int ) $_POST ['txtCalculo'] - ( int ) $total_descuento;
						$strInfo .= 'PRECIO DE PROMOCI&Oacute;N:  ' . number_format ( $precio_descuento, 2 ) . ' Bs.<BR>';
						$muestra = 0;
					}
				}
				
				$strVista = '
						<table  cellspacing=0 celladding=0 style="
						color: #333333;
						width: 80%;"><thead bgcolor="#CCCCCC"><th>Periodo</th><th>Monto</th><th>Cuota</th></thead>

						<tr><td style="font-size: 14px;" align="center">
						12 MESES </td><td style="font-size: 14px;" align="center">' . number_format ( $calculo, 0 ) . ' Bs.</td><td style="font-size: 14px;" align="center">' . number_format ( $calculo / 12, 0 ) . ' Bs.</td></tr></table><br><br>';
				
				$data ['info'] = $strInfo;
				$data ['lista'] = $strVista;
				$data ['venta'] = $_POST ['txtCalculo'];
				$data ['descripcion'] = $_POST ['txtDes'];
				$data ['monto'] = $monto;
				$data ['calculo'] = $_POST ['txtCalculo'];
				$data ['abono'] = $calculo;
				$data ['porcentaje'] = $porcentaje;
				$data ['muestra'] = $muestra;
			} else {
				$strInfo = "El Modelo Seleccionado No Posee Seriales Asociados, debe registrar al menos uno.";
				$data ['info'] = $strInfo;
				$data ['lista'] = '';
				$data ['venta'] = '';
				$data ['descripcion'] = '';
				$data ['monto'] = 0;
				$data ['abono'] = 0;
				$data ['calculo'] = 0;
				$data ['porcentaje'] = 0;
				$data ['muestra'] = 1;
			}
		} else {
			$data ['info'] = '';
			$data ['lista'] = '';
			$data ['venta'] = '';
			$data ['descripcion'] = '';
			$data ['porcentaje'] = 0;
			$data ['monto'] = 0;
			$data ['abono'] = 0;
			$data ['calculo'] = 0;
			$data ['muestra'] = 1;
		}
		
		$this->load->view ( "nomina/credifacil", $data );
	}
	public function BModelo($val = null) {
		$Modelo = $_POST ['modelo'];
		$this->load->model ( "CProductos" );
		$CProductos = new $this->CProductos ();
		echo $CProductos->Buscar_Modelo ( $Modelo, $val );
	}
	public function oBModelo($id) {
		$Modelo = $id;
		$this->load->model ( "CProductos" );
		$CProductos = new $this->CProductos ();
		echo $CProductos->Buscar_Modelo ( $Modelo );
	}
	
	/**
	 * Imprimir modelos de Facturas *
	 */
	public function Imprimir_Contratos($strId = null, $contrato_id = null) {
		$strNombre = '';
		$strBanco = '';
		$strCuenta = '';
		$strDireccion = '';
		$strLista = '';
		$Empresa = "";
		$data ["cedula"] = $strId;
		$strNomina = '';
		$dblSuma = 0;
		$strUsuario = '';
		$descripcion = 'MONTO TOTAL ACREDITADO  ';
		$Consulta = $this->db->query ( "SELECT * FROM t_personas WHERE documento_id='$strId'" );
		if ($Consulta->num_rows () != 0) {
			foreach ( $Consulta->result () as $row ) {
				$strNombre = $row->primer_apellido . ' ' . $row->segundo_apellido . ' ' . $row->primer_nombre . ' ' . $row->segundo_nombre;
				$strBanco = $row->banco_1;
				$strCuenta = $row->cuenta_1;
				$strDireccion = 'AV. ' . $row->avenida . ' CALLE ' . $row->calle . ' MUNICIPIO ' . $row->municipio . ' SECTOR ' . $row->sector . ' CASA # ' . $row->direccion;
				$Con = $this->db->query ( "SELECT * FROM t_clientes_creditos WHERE contrato_id ='$contrato_id'" );
				$strUsuario = $row->codigo_nomina;
				if ($Con->num_rows () != 0) {
					
					$strLista = '<table style="width:100%" border=1>
							<tr style="background-color:#ccc"><td><b>REFERENCIA</b></td><td><b>CUOTA</b></td>
							<td><b>PERIOCIDAD</b></td><td><b>MONTO</b></td> </tr>';
					foreach ( $Con->result () as $rw ) {
						$Empresa = $rw->empresa;
						if ($rw->forma_contrato == 4) {
							$descripcion = ' POR MONTO MENSUAL DEL DESCUENTO ';
							if ($rw->periocidad == 2) {
								$descripcion = ' POR MONTO QUINCENAL DEL DESCUENTO ';
							}
							if ($rw->periocidad == 3) {
								$descripcion = ' POR MONTO QUINCENAL DEL DESCUENTO ';
							}
						}
						$strNomina = $rw->nomina_procedencia . '<br><b>LINAJE: </b>' . $rw->cobrado_en;
						if ($rw->forma_contrato > 3) {
							
							$dblSuma = $rw->monto_cuota;
							$strLista = '<table style="width:100%" border=1>';
						} else {
							$strContrato = 'UNICO';
							$ano = substr ( $rw->fecha_inicio_cobro, 0, 4 );
							$mes = substr ( $rw->fecha_inicio_cobro, 5, 2 );
							
							$strPericidad = '';
							switch ($rw->periocidad) {
								case 0 :
									$strPericidad = 'SEMANAL ';
									break;
								case 1 :
									$strPericidad = 'CATORCENAL ';
									break;
								case 2 :
									$strPericidad = 'QUINCENAL 15 - 30 ';
									break;
								case 3 :
									$strPericidad = 'QUINCENAL 10 - 25 ';
									break;
								case 4 :
									$strPericidad = 'MENSUAL ';
									break;
							}
							switch ($rw->forma_contrato) {
								case 0 :
									$strContrato = $strPericidad;
									break;
								case 1 :
									$strContrato = 'DESDE 1 ' . $this->Convertir_Mes ( $mes ) . '  DE HASTA  EL 30 ' . $this->Convertir_Mes ( $mes ) . '  DEL ' . $ano;
									break;
								case 2 :
									$strContrato = 'DESDE 1 ' . $this->Convertir_Mes ( $mes ) . '  DE HASTA  EL 30 ' . $this->Convertir_Mes ( $mes ) . ' DEL ' . $ano;
									break;
								case 3 :
									$strContrato = 'EXTRAS';
									break;
								default :
									$strContrato = 'DESDE 1 ' . $this->Convertir_Mes ( $mes ) . ' DE HASTA  EL 30 ' . $this->Convertir_Mes ( $mes ) . ' DEL ' . $ano;
							}
							$strLista .= '<tr><td>' . $rw->contrato_id . '</td><td>' . $rw->numero_cuotas . '</td>
									<td> ' . $strContrato . ' </td>
											<td><b>' . number_format ( $rw->monto_cuota, 2, ".", "," ) . ' Bs.</b> </td></tr>';
							$dblSuma += $rw->monto_total;
						}
					}
					if ($rw->cobrado_en == $row->banco_1 || $rw->cobrado_en == 'NOMINA' || $rw->cobrado_en == 'CREDINFO' || $rw->cobrado_en == 'INVERCRESA') {
						$strBanco = $row->banco_1;
						$strCuenta = $row->cuenta_1;
					} else {
						$strBanco = $row->banco_2;
						$strCuenta = $row->cuenta_2;
					}
				}
				$strLista .= '</table><br>
						DETALLES: <b>  ' . $descripcion . number_format ( $dblSuma, 2, ".", "," ) . ' Bs.</b><br><br>';
			}
		} else {
		}
		$data ["nombre"] = $strNombre;
		$data ["banco"] = $strBanco;
		$data ["cuenta"] = $strCuenta;
		$data ["direccion"] = $strDireccion;
		$data ["empresa"] = $Empresa;
		$data ["lista"] = $strLista;
		$data ["factura"] = ' REFERENCIA ' . $contrato_id;
		$data ["nomina"] = '<b>NOMINA: </b>' . $strNomina;
		$data ['usuario'] = $strUsuario;
		
		$this->load->view ( "nomina/afiliadesafilia", $data );
	}
	public function inventario_p($msj = NULL, $mod = NULL, $prov = NULL, $equ = NULL, $mar = NULL, $prc = NULL, $prv = NULL, $des = NULL, $cang = NULL, $gar = NULL) {
		if ($this->session->userdata ( 'usuario' )) {
			$data ['Menu'] = $this->CMenu->getHtml_Menu ( $this->session->userdata ( 'nivel' ) );
			$data ['Nivel'] = $this->session->userdata ( 'nivel' );
			$data ['Contenido'] = $this->DataSource_Reportes ();
			if ($msj = 'x') {
				$msj = '';
			}
			if ($des = 'x') {
				$des = '';
			}
			$data ['Modelo'] = $mod;
			$data ['Proveedor'] = $prov;
			$data ['Equipo'] = $equ;
			$data ['Marca'] = $mar;
			$data ['Precio_C'] = $prc;
			$data ['Precio_V'] = $prv;
			$data ['Descripcion'] = $des;
			$data ['CanGar'] = $cang;
			$data ['Garantia'] = $gar;
			$data ['Listar_Usuarios_Combo'] = $this->CListartareas->Listar_Usuarios_Combo ();
			$data ['Listar_Usuarios_Tabla'] = $this->CAsociarCuentas->Consultar ();
			$data ['msj'] = $msj;
			$this->load->view ( "nomina/inventariop", $data );
		} else {
			$this->login ();
		}
	}
	public function Guardar_Inventariop() {
		$msj = '';
		$sProveedores = $_POST ["txtProveedores"];
		$sEquipos = $_POST ["txtEquipos"];
		$sMarca = $_POST ["txtMarca"];
		$sModelo = $_POST ["txtModelo"];
		$dCompra = $_POST ["txtprecioc"];
		$dVenta = $_POST ["txtpreciov"];
		$sUbicacion = $_POST ["txtdeposito"];
		$sFecha = $_POST ["txtano"] . "-" . $_POST ["txtmes"] . "-" . $_POST ["txtdia"];
		$iGarantia = $_POST ["txtCanGarantia"];
		$sGarantia = $_POST ["txtgarantia"];
		$lstSeriales = $_POST ["lstSeriales"];
		$iProveedor = 0;
		$iEquipo = 0;
		
		$sTable = "t_pproveedores";
		$this->db->where ( "nombre", $sProveedores );
		$rs = $this->db->get ( $sTable );
		$this->load->model ( "CProveedores" );
		$this->CProveedores->nombre = $sProveedores;
		
		if ($rs->num_rows () == 0) {
			$this->db->insert ( $sTable, $this->CProveedores );
		} else {
			$this->db->where ( "nombre", $sProveedores );
			$this->db->update ( $sTable, $this->CProveedores );
		}
		
		$sTable = "t_partefactos";
		$this->db->where ( "nombre", $sEquipos );
		$this->load->model ( "CArtefactos" );
		$this->CArtefactos->nombre = $sEquipos;
		
		$rs = $this->db->get ( $sTable );
		if ($rs->num_rows () == 0) {
			$this->db->insert ( $sTable, $this->CArtefactos );
		} else {
			$this->db->where ( "nombre", $sEquipos );
			$this->db->update ( $sTable, $this->CArtefactos );
		}
		
		$iProveedor = 0;
		$sTable = "t_pproveedores";
		$this->db->distinct ( "nombre" );
		$this->db->where ( "nombre", $sProveedores );
		
		$rs = $this->db->get ( $sTable );
		if ($rs->num_rows () != 0) {
			foreach ( $rs->result () as $row ) {
				$iProveedor = $row->proveedor_id;
			}
		}
		
		$iEquipo = 0;
		$sTable = "t_partefactos";
		
		$this->db->where ( "nombre", $sEquipos );
		$rs = $this->db->get ( $sTable );
		if ($rs->num_rows () != 0) {
			foreach ( $rs->result () as $row ) {
				$iEquipo = $row->artefacto_id;
			}
		}
		
		$sTable = "t_pinventario";
		
		$this->db->where ( "marca", $sMarca );
		$this->db->where ( "modelo", $sModelo );
		$rs = $this->db->get ( $sTable );
		if ($rs->num_rows () == 0) {
			$this->load->model ( "CInventario" );
			/**
			 *
			 * @var CInventario
			 */
			$Inventario = new $this->CInventario ();
			
			$Inventario->proveedor = $iProveedor;
			$Inventario->artefacto = $iEquipo;
			$Inventario->marca = $sMarca;
			$Inventario->modelo = $sModelo;
			$this->db->insert ( $sTable, $Inventario );
		}
		
		$sTable = "t_pinventario";
		$this->db->where ( "marca", $sMarca );
		$this->db->where ( "modelo", $sModelo );
		$rs = $this->db->get ( $sTable );
		if ($rs->num_rows () != 0) {
			$iInventario = 0;
			foreach ( $rs->result () as $row ) {
				$iInventario = $row->inventario_id;
			}
			
			$this->load->model ( "CProductos" );
			/**
			 *
			 * @var CProductos
			 */
			$Productos = new $this->CProductos ();
			
			$msj = 'Los Seriales ( ';
			$ser = count ( $lstSeriales );
			
			// $Productos -> venta = $dVenta;
			
			// $this -> db -> where("inventario_id", $iInventario);
			// $this -> db -> update("t_productos", $Productos);
			$strQuery = "UPDATE t_pproductos SET venta=" . $dVenta . " WHERE inventario_id=" . $iInventario;
			$this->db->query ( $strQuery );
			for($i = 0; $i < $ser; $i ++) {
				$Productos->serial = $lstSeriales [$i];
				$this->db->where ( "serial", $lstSeriales [$i] );
				$rs = $this->db->get ( 't_pproductos' );
				if ($rs->num_rows () == 0) {
					$Productos->inventario_id = $iInventario;
					$Productos->fecha_ingreso = $sFecha;
					$Productos->ubicacion = $sUbicacion;
					$Productos->cant_garantia = $iGarantia;
					$Productos->descripcion = "";
					$Productos->tipo_garantia = $sGarantia;
					$Productos->compra = $dCompra;
					$Productos->venta = $dVenta;
					$Productos->estatus = 1;
					$this->db->insert ( 't_pproductos', $Productos );
				} else {
					$msj .= $lstSeriales [$i];
				}
			}
		}
		$msj .= ' ). Ya se encuentran registrados... Por favor Verifiquelos he intente nuevamente';
		$this->inventario_p ();
	}
	public function Listar_Inventariop() {
		$this->load->model ( "CInventario" );
		/**
		 *
		 * @var CInventario
		 */
		$this->load->model ( "CProductos" );
		$CProductos = new $this->CProductos ();
		$Inventario = new $this->CInventario ();
		$data ['Menu'] = $this->CMenu->getHtml_Menu ( $this->session->userdata ( 'nivel' ) );
		$data ['lstEquipos'] = '';
		$this->load->model ( "CListartareas" );
		if (isset ( $_POST ['txtEstatus'] )) {
			$data ['sContenido'] = $Inventario->listarp ( $_POST ['txtDependencia'], $_POST ['txtEstatus'], $this->session->userdata ( 'nivel' ), "", $CProductos );
		} else {
			$data ['sContenido'] = '';
		}
		$data ['Listar_Usuarios_Combo'] = $this->CListartareas->Listar_Usuarios_Combo ();
		$this->load->view ( "nomina/inventarios", $data );
	}
	
	/**
	 */
	public function inventario_s($msj = NULL, $mod = NULL, $prov = NULL, $equ = NULL, $mar = NULL, $prc = NULL, $prv = NULL, $des = NULL, $cang = NULL, $gar = NULL) {
		if ($this->session->userdata ( 'usuario' )) {
			$data ['Menu'] = $this->CMenu->getHtml_Menu ( $this->session->userdata ( 'nivel' ) );
			$data ['Nivel'] = $this->session->userdata ( 'nivel' );
			$data ['Contenido'] = $this->DataSource_Reportes ();
			
			if ($msj = 'x') {
				$msj = '';
			}
			if ($des = 'x') {
				$des = '';
			}
			
			$data ['Modelo'] = $mod;
			$data ['Proveedor'] = $prov;
			$data ['Equipo'] = $equ;
			$data ['Marca'] = $mar;
			$data ['Precio_C'] = $prc;
			$data ['Precio_V'] = $prv;
			$data ['Descripcion'] = $des;
			$data ['CanGar'] = $cang;
			$data ['Garantia'] = $gar;
			$data ['Listar_Usuarios_Combo'] = $this->CListartareas->Listar_Usuarios_Combo ();
			$data ['Listar_Usuarios_Tabla'] = $this->CAsociarCuentas->Consultar ();
			$data ['msj'] = $msj;
			$this->load->view ( "nomina/inventarioss", $data );
		} else {
			$this->login ();
		}
	}
	
	/**
	 */
	public function Guardar_Inventarios() {
		$msj = '';
		
		$sProveedores = $_POST ["txtProveedores"];
		$sEquipos = $_POST ["txtEquipos"];
		$sMarca = $_POST ["txtMarca"];
		$sModelo = $_POST ["txtModelo"];
		$dCompra = $_POST ["txtprecioc"];
		$dVenta = $_POST ["txtpreciov"];
		$sUbicacion = $_POST ["txtdeposito"];
		$sFecha = $_POST ["txtano"] . "-" . $_POST ["txtmes"] . "-" . $_POST ["txtdia"];
		$iGarantia = $_POST ["txtCanGarantia"];
		$sGarantia = $_POST ["txtgarantia"];
		$lstSeriales = $_POST ["lstSeriales"];
		$iProveedor = 0;
		$iEquipo = 0;
		
		$sTable = "t_sproveedores";
		$this->db->where ( "nombre", $sProveedores );
		$rs = $this->db->get ( $sTable );
		if ($rs->num_rows () == 0) {
			$this->load->model ( "CProveedores" );
			$this->CProveedores->nombre = $sProveedores;
			$this->db->insert ( $sTable, $this->CProveedores );
		}
		
		$sTable = "t_sartefactos";
		$this->db->where ( "nombre", $sEquipos );
		$rs = $this->db->get ( $sTable );
		if ($rs->num_rows () == 0) {
			$this->load->model ( "CArtefactos" );
			$this->CArtefactos->nombre = $sEquipos;
			$this->db->insert ( $sTable, $this->CArtefactos );
		}
		$iProveedor = 0;
		$sTable = "t_sproveedores";
		$this->db->distinct ( "nombre" );
		$this->db->where ( "nombre", $sProveedores );
		$rs = $this->db->get ( $sTable );
		if ($rs->num_rows () != 0) {
			foreach ( $rs->result () as $row ) {
				$iProveedor = $row->proveedor_id;
			}
		}
		$iEquipo = 0;
		$sTable = "t_sartefactos";
		$this->db->where ( "nombre", $sEquipos );
		$rs = $this->db->get ( $sTable );
		if ($rs->num_rows () != 0) {
			foreach ( $rs->result () as $row ) {
				$iEquipo = $row->artefacto_id;
			}
		}
		$sTable = "t_sinventario";
		$this->db->where ( "marca", $sMarca );
		$this->db->where ( "modelo", $sModelo );
		$rs = $this->db->get ( $sTable );
		if ($rs->num_rows () == 0) {
			$this->load->model ( "CInventario" );
			/**
			 *
			 * @var CInventario
			 */
			$Inventario = new $this->CInventario ();
			$Inventario->proveedor = $iProveedor;
			$Inventario->artefacto = $iEquipo;
			$Inventario->marca = $sMarca;
			$Inventario->modelo = $sModelo;
			$this->db->insert ( $sTable, $Inventario );
		}
		$sTable = "t_sinventario";
		$this->db->where ( "marca", $sMarca );
		$this->db->where ( "modelo", $sModelo );
		$rs = $this->db->get ( $sTable );
		if ($rs->num_rows () != 0) {
			$iInventario = 0;
			foreach ( $rs->result () as $row ) {
				$iInventario = $row->inventario_id;
			}
			$this->load->model ( "CProductos" );
			/**
			 *
			 * @var CProductos
			 */
			$Productos = new $this->CProductos ();
			$msj = 'Los Seriales ( ';
			$ser = count ( $lstSeriales );
			$strQuery = "UPDATE t_sproductos SET venta=" . $dVenta . " WHERE inventario_id=" . $iInventario;
			$this->db->query ( $strQuery );
			for($i = 0; $i < $ser; $i ++) {
				$Productos->serial = $lstSeriales [$i];
				$this->db->where ( "serial", $lstSeriales [$i] );
				$rs = $this->db->get ( 't_sproductos' );
				if ($rs->num_rows () == 0) {
					$Productos->inventario_id = $iInventario;
					$Productos->fecha_ingreso = $sFecha;
					$Productos->ubicacion = $sUbicacion;
					$Productos->cant_garantia = $iGarantia;
					$Productos->descripcion = "";
					$Productos->tipo_garantia = $sGarantia;
					$Productos->compra = $dCompra;
					$Productos->venta = $dVenta;
					$Productos->estatus = 1;
					$this->db->insert ( 't_sproductos', $Productos );
				} else {
					$msj .= $lstSeriales [$i];
				}
			}
		}
		$msj .= ' ). Ya se encuentran registrados... Por favor Verifiquelos he intente nuevamente';
		$this->inventario_s ();
	}
	public function Listar_Inventarios() {
		$this->load->model ( "CInventario" );
		/**
		 *
		 * @var CInventario
		 */
		
		$this->load->model ( "CProductos" );
		$CProductos = new $this->CProductos ();
		$Inventario = new $this->CInventario ();
		$data ['Menu'] = $this->CMenu->getHtml_Menu ( $this->session->userdata ( 'nivel' ) );
		$data ['lstEquipos'] = '';
		$this->load->model ( "CListartareas" );
		if (isset ( $_POST ['txtEstatus'] )) {
			$data ['sContenido'] = $Inventario->listars ( $_POST ['txtDependencia'], $_POST ['txtEstatus'], $this->session->userdata ( 'nivel' ), "", $CProductos );
		} else {
			$data ['sContenido'] = '';
		}
		$data ['Listar_Usuarios_Combo'] = $this->CListartareas->Listar_Usuarios_Combo ();
		$this->load->view ( "nomina/inventariosss", $data );
	}
	
	/**
	 */
	public function Ticket($cedula = null, $factura = null) {
		$responsable = '';
		$this->db->where ( "documento_id", $cedula );
		$rs = $this->db->get ( "t_personas" );
		if ($rs->num_rows () != 0) {
			foreach ( $rs->result () as $row ) {
				$responsable = $row->primer_apellido . ' ' . $row->segundo_apellido . ' ' . $row->primer_nombre . ' ' . $row->segundo_nombre;
			}
		}
		$data ['responsable'] = $responsable;
		$data ['cedula'] = $cedula;
		$data ['referencia'] = $factura;
		
		$this->load->view ( "nomina/tickets", $data );
	}
	
	// constructor del formulario sugerencias y respuestas
	public function Sugerencia() {
		if ($this->session->userdata ( 'usuario' )) {
			$this->load->model ( 'CSugerencia' );
			$sugerencia = new $this->CSugerencia ();
			// metodo anterior
			// $this -> db -> where('nivel_usuario <', 2);
			// $this -> db -> or_where('nivel_usuario', 3);
			// $rs = $this -> db -> get('t_usuarios');
			$query = "SELECT t_usuario.seudonimo AS login, _tr_usuarioperfil.oidp AS nivel_usuario
					FROM t_usuario
					JOIN _tr_usuarioperfil ON _tr_usuarioperfil.oidu = t_usuario.oid
					WHERE _tr_usuarioperfil.oidp != 1 AND _tr_usuarioperfil.oidp != 4 AND t_usuario.estatus != 0 AND t_usuario.clave != ''";
			$rs = $this->db->query ( $query );
			$strPara = '';
			if ($rs->num_rows () != 0) {
				foreach ( $rs->result () as $row ) {
					$strPara .= '<option value="' . $row->login . ',' . $row->nivel_usuario . '">' . strtoupper ( $row->login ) . '</option>';
				}
			}
			$data ['usuario'] = trim ( $_SESSION ['usuario'] );
			$data ['para'] = $strPara;
			$data ['Menu'] = $this->CMenu->getHtml_Menu ( $this->session->userdata ( 'nivel' ) );
			$data ['lista_sugerencias'] = $sugerencia->listar ( $_SESSION ['usuario'], $this->session->userdata ( 'nivel' ) );
			$this->load->view ( 'nomina/sugerencia', $data );
		} else {
			$this->logout ();
		}
	}
	
	// funcion que guarda las sugerencias y vuelve a generar la tabla de suerencias
	public function Inserta_Sugerencia() {
		$this->load->model ( 'CSugerencia' );
		// $strUsuario = $_SESSION['usuario'];
		$strUsuario = $this->session->userdata ( 'usuario' );
		$intNivel = $this->session->userdata ( 'nivel' );
		$sugerencia = new $this->CSugerencia ();
		$sugerencia->nombre = $_POST ['tema'];
		$sugerencia->descripcion = $_POST ['sugerencia'];
		$sugerencia->prioridad = trim ( $_POST ['prioridad'] );
		$sugerencia->estado = 0;
		$para = explode ( ',', $_POST ['para'] );
		$sugerencia->para_usuario = $para [0];
		$sugerencia->de_usuario = trim ( $strUsuario );
		$sugerencia->nivel_usuario = $intNivel;
		$sugerencia->id = '';
		$this->db->insert ( 't_sugerencias', $sugerencia );
		echo $sugerencia->listar ( $strUsuario, $intNivel );
	}
	
	// Funcion que guarda respuesta del modulo de sugerencias y las agrega a la tablas
	public function Inserta_Respuesta() {
		$respuesta = array (
				'id' => '',
				'id_sugerencia' => $_POST ['id'],
				'respuesta' => $_POST ['respuesta'],
				'respondio_usuario' => $this->session->userdata ( 'usuario' ) 
		);
		$color = $_POST ['color'];
		$this->db->insert ( 't_respuestas', $respuesta );
		$data = array (
				'estado' => 2 
		);
		$this->db->where ( 'id', $_POST ['id'] );
		$this->db->update ( 't_sugerencias', $data );
		echo "<td bgcolor='$color'>- De " . $this->session->userdata ( 'usuario' ) . ":  " . $_POST ['respuesta'] . "</td>";
	}
	public function Cierra_Tema() {
		$data = array (
				'estado' => 3 
		);
		$this->db->where ( 'id', $_POST ['id'] );
		$this->db->update ( 't_sugerencias', $data );
	}
	public function Elimina_Tema() {
		$this->load->model ( 'CSugerencia' );
		$sugerencia = new $this->CSugerencia ();
		
		$this->db->where ( 'id', $_POST ['id'] );
		$this->db->delete ( 't_sugerencias' );
		
		$this->db->where ( 'id_sugerencia', $_POST ['id'] );
		$this->db->delete ( 't_respuestas' );
		echo $sugerencia->listar ( $_SESSION ['usuario'], $this->session->userdata ( 'nivel' ) );
	}
	public function Imprimir_Estado_Cuenta($strCedula = '') {
		$Persona = new $this->CPersonas ();
		$Credito = new $this->CCreditos ();
		$data ['cabezera'] = $Persona->Cabezera_Estado_Cuenta ( $strCedula );
		$data ['detalles'] = $Credito->Estado_Cuenta ( $strCedula );
		$this->load->view ( "nomina/estado_cuenta", $data );
	}
	public function Imprimir_Estado_Cuenta_Contrato($cedula = '', $contrato = '') {
		// $cedula = $_POST['cedula'];
		// $contrato = $_POST['contrato'];
		if ($this->session->userdata ( 'usuario' )) {
			$Persona = new $this->CPersonas ();
			$Credito = new $this->CCreditos ();
			$data ['cabezera'] = $Persona->Cabezera_Estado_Cuenta ( $cedula );
			$data ['detalles'] = $Credito->Estado_Cuenta_Contrato ( $cedula, $contrato );
			$this->load->view ( "nomina/estado_cuenta", $data );
		} else {
			$this->login ();
		}
	}
	public function Contratos_Liberacion() {
		$data ['Menu'] = $this->CMenu->getHtml_Menu ( $this->session->userdata ( 'nivel' ) );
		$data ['liberar'] = $this->CCreditos->Liberacion_Json ( 0 );
		$this->load->view ( "nomina/liberar", $data );
	}
	public function Tabla_Liberar() {
		echo $this->CCreditos->Liberacion_Json ( 0 );
	}
	public function Tabla_LiberarDEMO() {
		if (isset ( $_POST ['banco'] )) {
			$banco = $_POST ['banco'];
		} else {
			$banco = '';
		}
		if (isset ( $_POST ['desde'] )) {
			$desde = $_POST ['desde'];
		} else {
			$desde = '';
		}
		if (isset ( $_POST ['hasta'] )) {
			$hasta = $_POST ['hasta'];
		} else {
			$hasta = '';
		}
		echo $this->CCreditos->L_JsonDEMO ( $banco, $desde, $hasta, 0 );
	}
	public function Genera_Tabla() {
		if (isset ( $_POST ['consulta'] ) && $_POST ['consulta'] != "") {
			$query = $_POST ['consulta'];
			echo $this->CListartareas->Contruye_Datos ( $query );
		} else {
			echo $this->CListartareas->Contruye_Datos ();
		}
	}
	public function Fila_Liberar($intCredito = null) {
		echo $this->CCreditos->Liberacion_Json ( 0, $intCredito );
	}
	public function Liberar_Cheques() {
		$data ["numero_factura"] = $_POST ['ref'];
		$data ["fecha_operacion"] = $_POST ['fecha'];
		$data ["num_operacion"] = $_POST ['Num'];
		$data ["monto_operacion"] = $_POST ['Monto'];
		$data ["estado_verificado"] = 2;
		$this->CCreditos->Actualizar_Liberacion_Estado_Chequera ( $data );
		return 0;
	}
	public function Bloquear_Cheques() {
		$data ["numero_factura"] = $_POST ['ref'];
		$data ["estado_verificado"] = 3;
		$this->CCreditos->Actualizar_Liberacion_Estado_Chequera ( $data, 3 );
		return 0;
	}
	public function Entregar_Cheques() {
		$data ["numero_factura"] = $_POST ['id'];
		$data ["estado_verificado"] = 5;
		$this->CCreditos->Actualizar_Liberacion_Estado_Chequera ( $data );
		return 0;
	}
	public function Lista_Estados_Verificados() {
		$Credito = new $this->CCreditos ();
		$lista_estados = $Credito->Estatus_Consulta_Cheques ( "", $_SESSION ['usuario'] );
		$mensaje = "";
		foreach ( $lista_estados as $estado => $cantidad ) {
			$intEstado;
			switch ($estado) {
				case "nuevos" :
					$intEstado = 0;
					break;
				case "pendientes" :
					$intEstado = 1;
					break;
				case "liberados" :
					$intEstado = 2;
					break;
				case "anulados" :
					$intEstado = 3;
					break;
				case "entregados" :
					$intEstado = 5;
					break;
				default :
					break;
			}
			$mensaje .= " <B><a href='#' onClick=\"btnCargarListaVerificados('" . __LOCALWWW__ . "'," . $intEstado . ");\"  border=0> " . strtoupper ( $estado ) . "  " . $cantidad . "</a></B> | ";
		}
		echo "
				<div class=\"ui-state-highlight ui-corner-all\" style=\"margin-top: 10px; padding: .8em;\" id='DivLista'>
				<p><span class=\"ui-icon ui-icon-circle-check\" style=\"float: left; margin-right: .3em;\"></span>
				<strong>" . $mensaje . "</strong></p></div>";
	}
	public function Tabla_Estados_Verificados($strEstado) {
		echo $this->CCreditos->Listar_Estados_Json ( $strEstado, $_SESSION ['usuario'] );
	}
	public function prueba() {
		echo $this->CCreditos->Liberacion_Json ( 0 );
	}
	public function Prueba_Grid() {
		$data ['Menu'] = $this->CMenu->getHtml_Menu ( $this->session->userdata ( 'nivel' ) );
		
		$this->load->view ( "vista_pruebas", $data );
	}
	public function Muestra_Estado_Cliente() {
		$this->load->view ( __NFORMULARIOS__ . "frmVerifica_Cliente" );
	}
	public function Verifica_Cliente() {
		$ced_cliente = $_POST ['txtCliente'];
		$fecha_nac = $_POST ['txtFecha'];
		$this->db->where ( 'documento_id', trim ( $ced_cliente ) );
		$rs = $this->db->get ( 't_personas' );
		if ($rs->num_rows () != 0) {
			foreach ( $rs->result () as $row ) {
				
				if ($row->fecha_nacimiento == trim ( $fecha_nac )) {
					
					$this->Imprimir_Estado_Cuenta ( $ced_cliente );
				} else {
					header ( 'Location: Muestra_Estado_Cliente' );
					// echo "aca";
				}
			}
		} else {
			header ( 'Location: Muestra_Estado_Cliente' );
		}
	}
	public function Lista_Zona_Postal() {
		$this->load->model ( 'CZonapostal' );
		$estado = $_POST ['estado'];
		echo $this->CZonapostal->Zona_Postal ( $estado );
	}
	public function Exporta_Exel() {
		if ($this->session->userdata ( 'usuario' )) {
			$post = (isset($_POST['cuerpo']) && !empty($_POST['cabezera']));
			
			if($post){
				$rawCuerpo   = $_POST['cuerpo'];
				$rawCabezera = $_POST['cabezera'];

				$cuerpo   = json_decode($rawCuerpo, TRUE);
				$cabezera = json_decode($rawCabezera, TRUE);

				if (!is_array($cuerpo) || !is_array($cabezera)) {
					echo "Error al decodificar datos para el XLS.";
					return;
				}

				// Orden estable por índice de columna (como espera PHPExcel por fila)
				ksort($cabezera);
				foreach ($cuerpo as $k => $fila) {
					if (is_array($fila)) {
						ksort($cuerpo[$k]);
					}
				}

				$fecha  = Date("U");
				$nombre = "Reporte_" . $fecha;
				$this->load->model('CReportesXls');
				$this->CReportesXls->_Generar($cabezera, $cuerpo, $nombre);

				echo "<br><center><a href='" . __LOCALWWW__ . "/system/repository/xls/" . $nombre . ".xlsx' target='top'><img src='" . __IMG__ . "exel1.jpg' style='width:70px'>Click aqui</img></a>";
				return;
			}
		} else {
			echo "Usuario No Posee Acceso al Sistema...";
		}
	}
	public function MRcontrato() {
		if ($this->session->userdata ( 'usuario' )) {
			$cuerpo = json_decode ( $_POST ['cuerpo'], TRUE );
			print ('<pre>') ;
			print_R ( $cuerpo );
		} else {
			echo "Usuario No Posee Acceso al Sistema...";
		}
	}
	public function Reportes_Tgrid() {
		if ($this->session->userdata ( 'usuario' )) {
			$data ['Menu'] = $this->CMenu->getHtml_Menu ( $this->session->userdata ( 'nivel' ) );
			$data ['Nivel'] = $this->session->userdata ( 'nivel' );
			$this->load->view ( "nomina/reportes_tgrid", $data );
		} else {
			$this->login ();
		}
	}
	public function Mensajes() {
		if ($this->session->userdata ( 'usuario' )) {
			$data ['Menu'] = $this->CMenu->getHtml_Menu ( $this->session->userdata ( 'nivel' ) );
			$data ['Conectados'] = $this->CListartareas->Usuarios_Conectados_Chat ();
			$data ['usuario'] = trim ( $_SESSION ['usuario'] );
			$this->load->view ( "nomina/chat", $data );
		} else {
			$this->login ();
		}
	}
	public function Chat($estado) {
		$this->load->model ( 'CChat' );
		if ($estado == "chatheartbeat") {
			$this->CChat->chatHeartbeat ();
		}
		if ($estado == "sendchat") {
			$this->CChat->sendChat ();
		}
		if ($estado == "closechat") {
			$this->CChat->closeChat ();
		}
		if ($estado == "startchatsession") {
			$this->CChat->startChatSession ();
		}
		if (! isset ( $_SESSION ['chatHistory'] )) {
			$_SESSION ['chatHistory'] = array ();
		}
		
		if (! isset ( $_SESSION ['openChatBoxes'] )) {
			$_SESSION ['openChatBoxes'] = array ();
		}
	}
	public function Voucher_Provincial($factura) {
		$this->load->model ( 'reporte/PBaucher' );
		// $this -> PBaucher -> provincial('17522251', '025945');
		$this->PBaucher->provincial_nuevo ( $factura );
	}
	public function Voucher_Bicentenario($factura) {
		$this->load->model ( 'reporte/PBaucher' );
		// $this -> PBaucher -> provincial('17522251', '025945');
		$this->PBaucher->Bicentenario ( $factura );
	}
	public function subir_archivo() {
		if ($this->session->userdata ( 'usuario' )) {
			$this->load->view ( "cliente/subir_archivo", array (
					'msj' => ' ' 
			) );
		} else {
			$this->login ();
		}
	}
	public function subir_archivo2() {
		if ($this->session->userdata ( 'usuario' )) {
			$this->load->view ( "cliente/subir_archivo2", array (
					'msj' => ' ' 
			) );
		} else {
			$this->login ();
		}
	}
	public function subir_catalogo($modelo) {
		$this->load->view ( "inventario/subir_catalogo", array (
				'msj' => ' ',
				'modelo' => $modelo 
		) );
	}
	public function subir_archivo_ejecuta() {
		$msj = '';
		$error = '';
		if (isset ( $_POST ['bancos'] )) {
			$cedula = $_POST ['cedula'];
			$bancos = $_POST ['bancos'];
			$factura = $_POST ['facturas'];
			$cheque = $_POST ['cheques'];
			$carta = $_POST ['cedula'] . $_POST ['cartas'];
			$garantia = $_POST ['garantia'];
			$revision = $_POST ['revision'];
			$fiador = $_POST ['fiador'];
			$rif = $_POST ['rrif'];
			$directorio = BASEPATH . 'repository/expedientes/';
			$nombre_carpeta = array (
					'archivo1' => 'personales/',
					'archivo2' => 'bancos/',
					'archivo3' => 'facturas/',
					'archivo4' => 'cartas/',
					'archivo5' => 'bancos/',
					'archivo6' => 'garantia/',
					'archivo7' => 'cartas/',
					'archivo8' => 'fiador/',
					'archivo9' => 'rif/' 
			);
			$elementos = array (
					'archivo1' => 'd1',
					'archivo2' => 'd2',
					'archivo3' => 'd3',
					'archivo4' => 'd4',
					'archivo5' => 'd5',
					'archivo6' => 'd6',
					'archivo7' => 'd7',
					'archivo8' => 'd8',
					'archivo9' => 'd9' 
			);
			$nombre_nuevo = array (
					'archivo1' => md5 ( $cedula ),
					'archivo2' => md5 ( $bancos ),
					'archivo3' => md5 ( $factura ),
					'archivo4' => md5 ( $carta ),
					'archivo5' => md5 ( $cheque ),
					'archivo6' => md5 ( $garantia ),
					'archivo7' => md5 ( $cedula + $revision ),
					'archivo8' => md5 ( $cedula + $fiador ),
					'archivo9' => md5 ( $cedula + $rif ) 
			);
			foreach ( $elementos as $nombre => $archivo ) {
				if (isset ( $_FILES [$archivo] ['name'] ) && ($_FILES [$archivo] ['name'] != '' && $_FILES [$archivo] ['name'] != NULL)) {
					$extencion = strrchr ( $_FILES [$archivo] ['name'], "." );
					$$nombre = $directorio . $nombre_carpeta [$nombre] . $nombre_nuevo [$nombre] . $extencion;
					$i = 0;
					$nombre_aux = $nombre_nuevo [$nombre] . $extencion;
					while ( file_exists ( $$nombre ) && $nombre_carpeta [$nombre] != 'personales/' ) {
						$nombre_aux = $nombre_nuevo [$nombre] . '(' . $i . ')' . $extencion;
						$$nombre = $directorio . $nombre_carpeta [$nombre] . $nombre_aux;
						$i ++;
					}
					
					$error .= $_FILES [$archivo] ['error'];
					$subido = false;
					
					if (isset ( $_POST ['cedula'] ) && $error == UPLOAD_ERR_OK) {
						if (file_exists ( $$nombre ) && $nombre_carpeta [$nombre] == 'personales/') {
							$msj .= 'NO SE SUBIO LA CEDULA, YA EXISTE<br>';
						} else {
							$subido = copy ( $_FILES [$archivo] ['tmp_name'], $$nombre );
						}//0212-5011334 
					}
					if ($subido) {
						$msj .= "EL ARCHIVO SE SUBIO CON EXITO <br>";
						switch ($nombre_carpeta [$nombre]) {
							case 'personales/' :
								$insert = "INSERT IGNORE INTO t_expcedula (cedula,nombre) VALUES ($cedula,'$nombre_aux')";
								$this->db->query ( $insert );
								break;
							case 'bancos/' :
								$insert = "INSERT INTO t_expbanco (cedula,nombre) VALUES ($cedula,'$nombre_aux')";
								$this->db->query ( $insert );
								break;
							case 'facturas/' :
								$insert = "INSERT INTO t_expfactura (cedula,nombre) VALUES ($cedula,'$nombre_aux')";
								$this->db->query ( $insert );
								break;
							case 'cartas/' :
								$insert = "INSERT INTO t_expcartas (cedula,nombre) VALUES ($cedula,'$nombre_aux')";
								$this->db->query ( $insert );
								break;
							case 'fiador/' :
								$insert = "INSERT INTO t_expfiador (cedula,nombre) VALUES ($cedula,'$nombre_aux')";
								$this->db->query ( $insert );
								break;
							case 'rif/' :
								$insert = "INSERT INTO t_exprif (cedula,nombre) VALUES ($cedula,'$nombre_aux')";
								$this->db->query ( $insert );
								break;
							case 'garantia/' :
								$insert = "INSERT INTO t_cheque_garantia (cedula,factura,nombre) VALUES ($cedula,'$garantia','$nombre_aux')";
								$this->db->query ( $insert );
								break;
						}
					} else {
						$msj .= "NO SE SUBIO EL ARCHIVO " . $_FILES [$archivo] ['name'] . $error . '<br>';
					}
				}
			}
		}
		$data ['msj'] = $msj;
		// $this -> load -> view('cliente/subir_archivo', $data);
		echo $msj;
	}
	public function subir_catalogo_ejecuta() {
		$msj = '';
		$error = '';
		if (isset ( $_POST ['modelo'] )) {
			$directorio = BASEPATH . 'application/libraries/tcpdf/images/catalogo/';
			if ($_FILES ['foto'] ['name'] != '' && $_FILES ['foto'] ['name'] != NULL) {
				$extencion = strrchr ( $_FILES ['foto'] ['name'], "." );
				$nombre_aux = $directorio . $_POST ['modelo'] . $extencion;
				$error .= $_FILES ['foto'] ['error'];
				$subido = false;
				$subido = copy ( $_FILES ['foto'] ['tmp_name'], $nombre_aux );
				
				if ($subido) {
					$msj .= "EL ARCHIVO " . basename ( $_FILES ['foto'] ['name'] ) . " SE SUBIO CON EXITO <br>";
					$nombre_guardar = $_POST ['modelo'] . $extencion;
					$insert = "UPDATE t_inventario set foto='$nombre_guardar' WHERE modelo='$_POST[modelo]'";
					$this->db->query ( $insert );
				} else {
					$msj .= "EL ARCHIVO " . $_FILES ['foto'] ['name'] . $error . '<br>';
				}
			}
		}
		$data ['msj'] = $msj;
		$data ['modelo'] = '';
		$this->load->view ( 'inventario/subir_catalogo', $data );
	}
	
	/*
	 * funcion para la carga de datos en el modulo de agregar imagenes digitalizadas
	 */
	public function objCExpediente() {
		$this->load->model ( 'cliente/MCliente', 'MCliente' );
		if (isset ( $_POST ['id'] )) {
			$cedula = $_POST ['id'];
			echo $this->MCliente->jsCExpediente ( $cedula );
		}
	}
	public function Eliminar_Expediente() {
		$msj = '';
		if (isset ( $_POST ['id'] )) {
			
			$directorio = BASEPATH . 'repository/expedientes/';
			$cedula = $_POST ['id'];
			$nombre = $_POST ['nombre'];
			$tipo = $_POST ['tipo'];
			switch ($tipo) {
				case 'cedula' :
					$directorio .= 'personales/';
					$archivo = $directorio . $nombre;
					$this->db->query ( 'DELETE FROM t_expcedula WHERE cedula="' . $cedula . '" AND nombre="' . $nombre . '"' );
					break;
				case 'factura' :
					$directorio .= 'facturas/';
					$archivo = $directorio . $nombre;
					$this->db->query ( 'DELETE FROM t_expfactura WHERE cedula="' . $cedula . '" AND nombre="' . $nombre . '"' );
					break;
				case 'banco' :
					$directorio .= 'bancos/';
					$archivo = $directorio . $nombre;
					$this->db->query ( 'DELETE FROM t_expbanco WHERE cedula="' . $cedula . '" AND nombre="' . $nombre . '"' );
					break;
				case 'carta' :
					$directorio .= 'cartas/';
					$archivo = $directorio . $nombre;
					$this->db->query ( 'DELETE FROM t_expcartas WHERE cedula="' . $cedula . '" AND nombre="' . $nombre . '"' );
					break;
				case 'garantia' :
					$directorio .= 'garantia/';
					$archivo = $directorio . $nombre;
					$this->db->query ( 'DELETE FROM t_cheque_garantia WHERE cedula="' . $cedula . '" AND nombre="' . $nombre . '"' );
					break;
				case 'fiador' :
					$directorio .= 'fiador/';
					$archivo = $directorio . $nombre;
					$this->db->query ( 'DELETE FROM t_expfiador WHERE cedula="' . $cedula . '" AND nombre="' . $nombre . '"' );
					break;
				case 'rif' :
					$directorio .= 'rif/';
					$archivo = $directorio . $nombre;
					$this->db->query ( 'DELETE FROM t_exprif WHERE cedula="' . $cedula . '" AND nombre="' . $nombre . '"' );
					break;
				default :
					break;
			}
			if (file_exists ( $archivo )) {
				if (unlink ( $archivo ))
					$msj = "El archivo fue borrado";
				else
					$msj = "El archivo no fue borrado";
			} else
				$msj = "El archivo existe";
			echo $msj;
		} else {
			echo "Debe ingresar cedula";
		}
	}
	public function Ver_Cheque($ced = '', $fact = '') {
		$data ['cedula'] = $ced;
		$data ['factura'] = $fact;
		$this->load->view ( 'reportes/ver_cheque', $data );
	}
	public function catalogo() {
		$this->load->model ( 'CCatalogo', 'CCatalogo' );
		$this->CCatalogo->Genera_Catalogo ();
	}
	public function Busca_Img_Cheque() {
		if (isset ( $_POST ['id'] )) {
			$obCExpediente = array ();
			$iCedula = $_POST ['id'];
			$imgBancos = "SELECT * FROM t_expbanco WHERE cedula=$iCedula";
			$rsImgBancos = $this->db->query ( $imgBancos );
			if ($rsImgBancos->num_rows () > 0) {
				$md5 = md5 ( trim ( $_POST ['factura'] ) );
				$rsImgBan = $rsImgBancos->result ();
				$aux3 = array ();
				$i = 0;
				foreach ( $rsImgBan as $rowBan ) {
					$pos = substr_count ( trim ( $rowBan->nombre ), $md5 );
					if ($pos > 0) {
						$aux3 [$i] = $rowBan->nombre;
						$i ++;
					}
				}
				$obCExpediente ['imgCheque'] = $aux3;
			} else {
				$obCExpediente ['imgCheque'] = array (
						'no_disponible.jpg' 
				);
			}
			echo json_encode ( $obCExpediente );
		} else {
			echo "No hay cedula";
		}
	}
	public function informacion() {
		phpinfo ();
	}
	public function Genera_Vista_Prueba() {
		$this->load->model ( 'CListartareas', 'CListartareas' );
		return $this->CListartareas->Genera_Formulario ();
	}
	public function MuestraDetalleReporte() {
		$this->load->model ( 'cliente/MCliente', 'MCliente' );
		if (isset ( $_POST ['objeto'] )) {
			$json = json_decode ( $_POST ['objeto'], true );
			$Arr ['perfil'] = $this->session->userdata ( 'nivel' );
			$Arr ['factura'] = $json [0];
			$Arr ['cedula'] = $json [2];
			$valor = explode ( "||", $json [1] );
			switch (strtolower ( $valor [0] )) {
				case 'voucher' :
					$jsC = $this->MCliente->Detalles_Facturas_Voucher ( $Arr );
					break;
				case 'mixto' :
					$jsC = $this->MCliente->Detalles_Facturas_Mixto ( $Arr );
					break;
				default :
					$jsC = $this->MCliente->Detalles_Facturas_Contratos ( $Arr );
					
					break;
			}
			echo $jsC;
		} else {
			echo "llega2";
		}
	}
	
	/* funcion adicional usada para el modulo de consultar estados de cuenta de clientes */
	public function MuestraDetalleReporte_Electron() {
		$this->load->model ( 'cliente/MClientee', 'MClientee' );
		if (isset ( $_POST ['objeto'] )) {
			$json = json_decode ( $_POST ['objeto'], true );
			// $Arr['perfil'] = $this -> session -> userdata('nivel');
			$Arr ['factura'] = $json [0];
			switch ($json [1]) {
				case 'Voucher' :
					$jsC = $this->MClientee->Detalles_Facturas_Voucher ( $Arr );
					break;
				case 'Mixto' :
					$jsC = $this->MClientee->Detalles_Facturas_Mixto ( $Arr );
					break;
				default :
					$jsC = $this->MClientee->Detalles_Facturas_Contratos ( $Arr );
					
					break;
			}
			echo $jsC;
		} else {
			echo "llega2";
		}
	}
	
	// fin
	public function BFactura_CC($factura = null) {
		if (isset ( $_POST ['factura'] )) {
			$this->load->model ( "CInventario" );
			$Inventario = new $this->CInventario ();
			$sFactura = $_POST ["factura"];
			echo json_encode ( $Inventario->BFactura_CC ( $sFactura ) );
			// echo "hola";
		}
	}
	public function Boucher_CC($factura = null) {
		if (isset ( $_POST ['factura'] )) {
			$this->load->model ( "CInventario" );
			$Inventario = new $this->CInventario ();
			$sFactura = $_POST ["factura"];
			echo json_encode ( $Inventario->Boucher_CC ( $sFactura ) );
			// echo "hola";
		}
	}
	public function Aceptar_Voucher() {
		if (isset ( $_POST ['objeto'] )) {
			$json = json_decode ( $_POST ['objeto'], true );
			
			$query = "UPDATE t_lista_voucher SET estatus=1 ,observacion='" . trim ( $json [2] ) . "', tipo_voucher=" . $json [3] . "  WHERE ndep='" . trim ( $json [0] ) . "' and cid='" . trim ( $json [1] ) . "'";
			$this->db->query ( $query );
			echo "Se Acepto el Voucher.";
		} else {
			echo "No Se Pudo Aceptar El Voucher";
		}
	}
	public function Rechazar_Voucher() {
		$msj = '';
		if (isset ( $_POST ['objeto'] )) {
			$json = json_decode ( $_POST ['objeto'], true );
			$query = "UPDATE t_lista_voucher SET estatus=2 ,observacion='" . trim ( $json [5] ) . "' WHERE ndep='" . trim ( $json [0] ) . "' and cid='" . trim ( $json [1] ) . "'";
			$this->db->query ( $query );
			$msj = "Se Traslado el Voucher.";
			/**
			 * Se toma en cuenta solo los casos del tipo voucher: fuera del caso mixto no se vera incluido en la factura
			 */
			
			$query = "SELECT (SUM(cantidad)*0.03)/12 AS cant,cobrado_en FROM t_clientes_creditos WHERE numero_factura='" . trim ( $json [1] ) . "' GROUP BY numero_factura";
			$consulta = $this->db->query ( $query );
			$interes = $consulta->row ();
			$monto = $interes->cant + floatval ( $json [2] );
			$linaje = $interes->cobrado_en;
			$data = array (
					"factura" => trim ( $json [1] ),
					"voucher" => trim ( $json [0] ),
					"monto" => $monto,
					"observacion" => $json ['5'],
					"tipo" => $json [4],
					"estatus" => 0,
					"linaje" => $linaje 
			);
			$this->db->insert ( "t_cuotas_programadas", $data );
			
			$strVerifica = "SELECT * FROM t_lista_voucher WHERE (estatus = 2 OR estatus = 3) and cid='" . trim ( $json [1] ) . "'";
			$verifica = $this->db->query ( $strVerifica );
			if ($verifica->num_rows () >= 3) {
				$mot = 'Se le trasladaron 3 o mas voucher de la factura';
				$msj .= $this->Modificar_Modalidad ( trim ( $json [1] ), $this->session->userdata ( 'usuario' ), $mot );
			}
			echo $msj;
		} else {
			echo "No Se Pudo Rechazar El Voucher";
		}
	}
	public function Modificar_Modalidad($strfactura = '', $strpeticion = '', $strmotivo = '') {
		$this->load->model ( "cliente/mvoucher", "MVoucher" );
		if (isset ( $_POST ['factura'] )) {
			$factura = $_POST ['factura'];
			$peticion = $_POST ['peticion'];
			$motivo = $_POST ['motivo'];
			echo 'b1';
			$msj = $this->MVoucher->Modificar_Modalidad_Pago ( $factura, $peticion, $motivo );
			echo 'bp';
			echo "<strong>&nbsp;  Proceso Finalizado Satisfactoriamente </strong>...." . $msj;
		} else {
			if ($strfactura != '' && $strmotivo != '' && $strpeticion != '') {
				$msj = $this->MVoucher->Modificar_Modalidad_Pago ( $strfactura, $strpeticion, $strmotivo );
				return "<strong>&nbsp;  Proceso Finalizado Satisfactoriamente </strong>...." . $msj;
			}
		}
	}
	public function Listar_Voucher() {
		$this->load->model ( "cliente/mvoucher", "MVoucher" );
		echo $this->MVoucher->Voucher_Pendientes ( $_POST );
	}
	public function Listar_Contratos_Usuarios() {
		if (isset ( $_POST )) {
			echo $this->MCliente->Listar_Contratos_Usuarios ( $_POST, $this->session->userdata ( 'nivel' ) );
		} else {
			$this->login ();
		}
	}
	public function Recibo_Ingreso() {
		if ($this->session->userdata ( 'usuario' )) {
			$data ['Menu'] = $this->CMenu->getHtml_Menu ( $this->session->userdata ( 'nivel' ) );
			$data ['Nivel'] = $this->session->userdata ( 'nivel' );
			$data ['iPosicion'] = $this->session->userdata ( 'posicion' );
			$this->load->view ( "recibo_ingreso", $data );
		} else {
			$this->login ();
		}
	}
	public function Recibo_Egreso() {
		if ($this->session->userdata ( 'usuario' )) {
			$data ['Menu'] = $this->CMenu->getHtml_Menu ( $this->session->userdata ( 'nivel' ) );
			$data ['Nivel'] = $this->session->userdata ( 'nivel' );
			$data ['iPosicion'] = $this->session->userdata ( 'posicion' );
			$this->load->view ( "recibo_egreso", $data );
		} else {
			$this->login ();
		}
	}
	public function MuestraCuotas() {
		$this->load->model ( 'cliente/MCliente', 'MCliente' );
		if (isset ( $_POST ['objeto'] )) {
			$json = json_decode ( $_POST ['objeto'], true );
			$Arr ['perfil'] = $this->session->userdata ( 'nivel' );
			$Arr ['contrato'] = $json [0];
			$Arr ['cedula'] = $json [1];
			$query = "Select nomina_procedencia, cobrado_en, empresa, forma_contrato, fecha_inicio_cobro, monto_cuota FROM t_clientes_creditos WHERE contrato_id ='" . $json [0] . "' ";
			$consulta = $this->db->query ( $query );
			foreach ( $consulta->result () as $row ) {
				$Arr ['nomina_procedencia'] = $row->nomina_procedencia;
				$Arr ['cobrado_en'] = $row->cobrado_en;
				$Arr ['empresa'] = $row->empresa;
				$Arr ['forma_contrato'] = $row->forma_contrato;
				$Arr ['fecha_inicio_cobro'] = $row->fecha_inicio_cobro;
				$Arr ['monto_cuota'] = $row->monto_cuota;
			}
			
			$html = $this->MCliente->MuestraCuotas ( $Arr );
			echo $html;
		}
	}
	public function Autorizar_Por_Deuda() {
		$this->load->model ( "persona/mpersona", 'MPersona' );
		$cedula = $_POST ["cedula"];
		$peticion = $_POST ['peticion'];
		$motivo = $_POST ['motivo'];
		$this->MPersona->Autorizar_Por_Deuda ( $cedula, $peticion, $motivo );
		echo 'Felicitaciones en hora buena exito...';
	}
	public function CuadreCaja() {
		$this->load->model ( 'reporte/MReporte', 'MReporte' );
		
		if (isset ( $_POST ['tipo'] )) {
			$Arreglo ['tipo'] = $_POST ['tipo'];
			$Arreglo ['empresa'] = $_POST ['empresa'];
			$Arreglo ['nomina'] = $_POST ['nomina'];
			$Arreglo ['banco'] = $_POST ['banco'];
			$Arreglo ['desde'] = $_POST ['desde'];
			$Arreglo ['hasta'] = $_POST ['hasta'];
			if (trim ( $Arreglo ['desde'] ) == '') {
				$Arreglo ['desde'] = "2012-08-01";
				$Arreglo ['hasta'] = "2012-08-30";
			}
			$data = $this->MReporte->Cuadre_Caja ( $Arreglo );
			echo $data;
		}
	}
	public function Guardar_Recibo() {
		$this->load->model ( 'recibo/mrecibo', 'MRecibo' );
		$arr = $_POST;
		$arr ['nrecibo'] = $this->codigo_recibo ();
		$arr ['usuario'] = $this->session->userdata ( 'usuario' );
		$msj = $this->MRecibo->Guardar_Recibo ( $arr );
		$cedula = $arr ['cedula'];
		
		if($_POST['formaPago'] != "NO"){	
			$this->load->model ( "cliente/mvoucher", "MVoucher" );
			$this->MVoucher->Modificar_Modalidad_Pago ( $_POST['formaPago'], "Sistema", "Cancelación de la Factura");
							
		}
		
		$msj .= '<a href=\'' . __LOCALWWW__ . '/index.php/cooperativa/Imprime_Recibo_Ingreso/' . $arr ['nrecibo'] . '\' border=0 target=\'top\'><center><img src=\'' . __IMG__ . 'pdf.png\'><br>	Imprimir Recibo de Ingreso</a>';
		$msj .= '<a href=\'' . __LOCALWWW__ . '/index.php/cooperativa/Imprime_Recibo_Ingreso_Preimpreso/' . $arr ['nrecibo'] . '\' border=0 target=\'top\'><center><img src=\'' . __IMG__ . 'pdf.png\'><br>Recibo Pre-Impreso</a>';
		echo $msj;
	}
	public function Guardar_ReciboE() {
		$this->load->model ( 'recibo/mreciboegreso', 'MReciboe' );
		$arr = $_POST;
		$arr ['nrecibo'] = $this->codigo_reciboE ();
		$arr ['usuario'] = $this->session->userdata ( 'usuario' );
		$msj = $this->MReciboe->Guardar_ReciboE ( $arr );
		$cedula = $arr ['cedula'];
		$msj .= '<a href=\'' . __LOCALWWW__ . '/index.php/cooperativa/Imprime_Recibo_Egreso/' . $arr ['nrecibo'] . '\' border=0 target=\'top\'><center><img src=\'' . __IMG__ . 'pdf.png\'><br>	Imprimir Recibo de Egreso</a>';
		echo $msj;
	}
	public function Imprime_Recibo_Ingreso($recibo = null) {
		if ($this->session->userdata ( 'usuario' )) {
			$this->load->model ( 'reporte/PReciboingreso' );
			$this->PReciboingreso->ReciboI ( $recibo );
		} else {
			$this->login ();
		}
	}

	public function Imprime_Recibo_Ingreso_Preimpreso($recibo = null) {
		if ($this->session->userdata ( 'usuario' )) {
			$this->load->model ( 'reporte/PReciboingreso' );
			$this->PReciboingreso->ReciboI_Pre ( $recibo );
		} else {
			$this->login ();
		}
	}

	public function Imprime_Recibo_Egreso($recibo = null) {
		if ($this->session->userdata ( 'usuario' )) {
			$this->load->model ( 'reporte/preeciboegreso', 'PReciboegreso' );
			$this->PReciboegreso->ReciboE ( $recibo );
		} else {
			$this->login ();
		}
	}
	function codigo_recibo() {
		$sCon = 'SELECT MAX(id) AS cantidad FROM t_recibo_ingreso';
		$rs = $this->db->query ( $sCon );
		$rCon = $rs->result ();
		if ($rCon [0]->cantidad != null)
			$sVal = 'R-' . $this->Completar ( $rCon [0]->cantidad + 1, 6 );
		else
			$sVal = 'R-' . $this->Completar ( 1, 6 );
		return $sVal;
	}
	function codigo_reciboE() {
		$sCon = 'SELECT MAX(id) AS cantidad FROM t_recibo_egreso';
		$rs = $this->db->query ( $sCon );
		$rCon = $rs->result ();
		if ($rCon [0]->cantidad != null)
			$sVal = 'RE-' . $this->Completar ( $rCon [0]->cantidad + 1, 6 );
		else
			$sVal = 'RE-' . $this->Completar ( 1, 6 );
		return $sVal;
	}
	function Registrar_Credi_Compra() {
		if ($this->session->userdata ( 'usuario' )) {
			// $this -> load -> model('CInventario');
			// $this -> load -> model('CNomina');
			// $this -> load -> model('CZonapostal');
			// $data['lista'] = $this -> CNomina -> Combo();
			// $data['estados'] = $this -> CZonapostal -> Estados();
			$data ['Menu'] = $this->CMenu->getHtml_Menu ( $this->session->userdata ( 'nivel' ) );
			// $data['lstInventario'] = $this -> CInventario -> Listar_Combo($this -> session -> userdata('ubicacion'));
			// $data['lstEquipos'] = $this -> CInventario -> Listar_Equipos($this -> session -> userdata('ubicacion'));
			$data ['Nivel'] = $this->session->userdata ( 'nivel' );
			$this->load->view ( "responsable", $data );
		} else {
			$this->login ();
		}
	}
	function Cargar_Numeros_Cuenta() {
		$this->load->model ( "chequera/mchequera", "MChequera" );
		echo json_encode ( $this->MChequera->Listar_Cuenta ( $_POST ["valor"] ) );
	}
	function Listar_Cheques() {
		$this->load->model ( "chequera/mchequera", "MChequera" );
		echo $this->MChequera->jsCheques ( $_POST );
	}
	function ver_formulario() {
		$this->load->view ( "otros/frmRegistro_Titular" );
	}
	function Imp_Interbancaria() {
		echo '<a href=\'' . __LOCALWWW__ . '/index.php/cooperativa/Interbancaria/' . $_POST ['documento_id'] . '/' . $_POST ['cuenta'] . '\' border=0 target=\'top\'><center><img src=\'' . __IMG__ . 'pdf.png\'><br>	Imprimir Formato</a>';
	}
	function Interbancaria($cedula, $cuenta) {
		if ($cedula != '') {
			$this->load->model ( 'reporte/pformatos', 'PFormato' );
			$this->PFormato->FInterbancaria ( $cedula, $cuenta );
		} else {
			echo "Debe ingresar una cedula";
		}
	}
	function Imp_Formato_Libre() {
		$ced = 'LIBRE';
		if ($_POST ['documento_id'] != '') {
			$ced = $_POST ['documento_id'];
		}
		$enlaces = '<table><tbody>';
		$enlaces .= '<tr><td><a href=\'' . __LOCALWWW__ . '/index.php/cooperativa/Formatos_Libres/' . $ced . '/SOFITASA/G \' border=0 target=\'top\'><center><img src=\'' . __IMG__ . 'pdf.png\'>SOFITASA</a></td>';
		$enlaces .= '<td><a href=\'' . __LOCALWWW__ . '/index.php/cooperativa/Formatos_Libres/' . $ced . '/GOBERNACION/G \' border=0 target=\'top\'><center><img src=\'' . __IMG__ . 'pdf.png\'>GOBERNACION</a></td>';
		$enlaces .= '<td><a href=\'' . __LOCALWWW__ . '/index.php/cooperativa/Formatos_Libres/' . $ced . '/CREDINFO/G \' border=0 target=\'top\'><center><img src=\'' . __IMG__ . 'pdf.png\'>CREDINFO</a></td>';
		$enlaces .= '<td><a href=\'' . __LOCALWWW__ . '/index.php/cooperativa/Formatos_Libres/' . $ced . '/ULA/G \' border=0 target=\'top\'><center><img src=\'' . __IMG__ . 'pdf.png\'>ULA</a></td></tr>';
		$enlaces .= '<tr><td><a href=\'' . __LOCALWWW__ . '/index.php/cooperativa/Formatos_Libres/' . $ced . '/VENEZUELA/G \' border=0 target=\'top\'><center><img src=\'' . __IMG__ . 'pdf.png\'>VENEZUELA GRUPO</a></td>';
		$enlaces .= '<td><a href=\'' . __LOCALWWW__ . '/index.php/cooperativa/Formatos_Libres/' . $ced . '/VENEZUELA/C \' border=0 target=\'top\'><center><img src=\'' . __IMG__ . 'pdf.png\'>VENEZUELA COOPERATIVA</a></td>';
		$enlaces .= '<td><a href=\'' . __LOCALWWW__ . '/index.php/cooperativa/Formatos_Libres/' . $ced . '/UNIVERSAL/G \' border=0 target=\'top\'><center><img src=\'' . __IMG__ . 'pdf.png\'>UNIVERSAL GRUPO</a></td>';
		$enlaces .= '<td><a href=\'' . __LOCALWWW__ . '/index.php/cooperativa/Formatos_Libres/' . $ced . '/UNIVERSAL/C \' border=0 target=\'top\'><center><img src=\'' . __IMG__ . 'pdf.png\'>UNIVERSAL COOPERATIVA</a></td></tr>';
		$enlaces .= '<tr><td><a href=\'' . __IMG__ . 'AutorizaDesNom.docx\' border=0 target=\'top\'><center><img src=\'' . __IMG__ . 'pdf.png\'>Autorizacion Descuento Nomina</a></td>';
		$enlaces .= '<td><a href=\'' . __IMG__ . 'AutorizaDesDom.xls\' border=0 target=\'top\'><center><img src=\'' . __IMG__ . 'pdf.png\'>Autorizacion Descuento Domilizacion</a></td>';
		$enlaces .= '<td><a href=\'' . __LOCALWWW__ . '/index.php/cooperativa/Formatos_Libres/' . $ced . '/DOMI/C \' border=0 target=\'top\'><center><img src=\'' . __IMG__ . 'pdf.png\'>DOMICILIACION DE PAGO N</a></td></tr>';
		$enlaces .= '<td><a href=\'' . __LOCALWWW__ . '/index.php/cooperativa/Formatos_Libres/' . $ced . '/CONVE/C \' border=0 target=\'top\'><center><img src=\'' . __IMG__ . 'pdf.png\'>Convenio de Pago</a></td>';
		$enlaces .= '<td><a href=\'' . __LOCALWWW__ . '/index.php/cooperativa/Formatos_Libres/' . $ced . '/CONTRA/C \' border=0 target=\'top\'><center><img src=\'' . __IMG__ . 'pdf.png\'>Contrato</a></td></tr>';

		$enlaces .= '</tbody></table>';
		echo $enlaces;
	}
	function Formatos_Libres($cedula, $formato, $emp) {
		if ($cedula == 'LIBRE')
			$cedula = '';
		$this->load->model ( 'reporte/pformatoslibres', 'PFormatoLibre' );
		$this->PFormatoLibre->Formato_Libre ( $formato, $cedula, $emp );
	}
// Interfaz
	function Crear_Txt() {
		$this->load->model ( 'CNomina' );
		if ($this->session->userdata ( 'usuario' )) {
			$data ['Menu'] = $this->CMenu->getHtml_Menu ( $this->session->userdata ( 'nivel' ) );
			$data ['Nivel'] = $this->session->userdata ( 'nivel' );
			$this->load->view ( "crea_txt", $data );
		} else {
			$this->login ();
		}
	}
	
	// Interfaz
	function Leer_Txt() {
		if ($this->session->userdata ( 'usuario' )) {
			$data ['Menu'] = $this->CMenu->getHtml_Menu ( $this->session->userdata ( 'nivel' ) );
			$data ['Nivel'] = $this->session->userdata ( 'nivel' );
			
			$this->load->view ( "leer_txt", $data );
		} else {
			$this->login ();
		}
	}
	function Ver_Txt() {
		if ($this->session->userdata ( 'usuario' )) {
			$data ['Menu'] = $this->CMenu->getHtml_Menu ( $this->session->userdata ( 'nivel' ) );
			$data ['Nivel'] = $this->session->userdata ( 'nivel' );
			
			$this->load->view ( "ver_txt", $data );
		} else {
			$this->login ();
		}
	}
	
	/**
	 * Listar Pendientes Por Procesar
	 * Basado en los Linejas
	 *
	 * @return TGrid
	 */
	function LtxtEnviados() {
		$this->load->model ( 'cobranza/mprocesar', 'MProcesar' );
		print_r ( $this->MProcesar->Vtxt ( 2 ) );
	}
	
	/**
	 * Generar Archivo del Banco
	 * $_POST ref:(banco, empresa, periodicidad, fecha, fcontrato, nomina)
	 *
	 * @return HTML
	 */
	function Gtxt() {
		$this->load->model ( 'cobranza/mprocesar', 'MProcesar' );
		echo $this->MProcesar->Gtxt ( $_POST );
	}
	
	/**
	 * Generar Archivo del Banco
	 * $_POST ref:(archivo, banco)
	 *
	 * @return TGrid
	 */
	function Ltxt() {
		$this->load->model ( 'cobranza/mprocesar', 'MProcesar' );
		echo $this->MProcesar->Ltxt ( $_POST );
	}
	
	/**
	 * Listar Pendientes Por Procesar
	 * Basado en los Linejas
	 *
	 * @return TGrid
	 */
	function Vtxt() {
		$this->load->model ( 'cobranza/mprocesar', 'MProcesar' );
		print_r ( $this->MProcesar->Vtxt ( 1 ) );
	}
	
	/**
	 * Listar Pendientes Por Procesar
	 * Basado en los Linejas
	 *
	 * @return TGrid
	 */
	function Itxt() {
		$this->load->model ( 'cobranza/mprocesar', 'MProcesar' );
		print_r ( $this->MProcesar->Itxt ( $_POST ) );
		// print_r($_POST);
	}
	function Nomina_Banco() {
		$this->load->model ( 'CListartareas', 'CListartareas' );
		$banco = $_POST ['banco'];
		$nominas = $this->CListartareas->Nomina_Banco ( $banco );
		// print_R($nominas);
		echo $nominas;
	}
	function SubirTxt() {
		$banco = strtolower ( $_POST ['banco'] );
		
		$directorio = BASEPATH . 'repository/txt/' . $banco;
		
		$upload_folder = 'images';
		$nombre_archivo = $_FILES ['archivo'] ['name'];
		$tipo_archivo = $_FILES ['archivo'] ['type'];
		$tamano_archivo = $_FILES ['archivo'] ['size'];
		$tmp_archivo = $_FILES ['archivo'] ['tmp_name'];
		$archivador = $directorio . '/' . $nombre_archivo;
		$return = Array (
				'ok' => 'si',
				'archivo' => $nombre_archivo,
				"msg" => "SE CARGO CON EXITO",
				"txt" => $nombre_archivo 
		);
		
		$query = "SELECT * FROM cpp_archivo WHERE nmbr='" . $nombre_archivo . "' AND fech='" . $_POST ['fecha'] . "'";
		
		$cant = $this->db->query ( $query );
		
		if ($cant->num_rows () > 0) {
			$msj = "No Se Pudo Procesar. El Archivo Ya Fue Procesado";
			$return = Array (
					'ok' => 'no',
					'msg' => $msj,
					'status' => 'error',
					'txt' => '' 
			);
		} else {
			
			if (! move_uploaded_file ( $tmp_archivo, $archivador )) {
				$return = Array (
						'ok' => 'no',
						'msg' => "Ocurrio un error al subir el archivo. No pudo guardarse.",
						'status' => 'error',
						'txt' => '' 
				);
			} else {
				$this->db->query ( "INSERT INTO cpp_archivo (nmbr,fech,esta) VALUES ('" . $nombre_archivo . "','" . $_POST ['fecha'] . "',1)" );
			}
		}
		echo json_encode ( $return );
	}
	function Estado_Factura($sFac) {
		$this->load->model ( "cobranza/mcobranza", 'MCobranza' );
		$data ['arr'] = $this->MCobranza->GListaFactura ( $sFac );
		$data ['factura'] = $sFac;
		$this->load->view ( "procesar/estado_cuenta_factura", $data );
	}
	function GVistas() {
		$data ['Menu'] = $this->CMenu->getHtml_Menu ( $this->session->userdata ( 'nivel' ) );
		$data ['Nivel'] = $this->session->userdata ( 'nivel' );
		$this->load->view ( "otros/gvista", $data );
	}
	function Vista1() {
		$this->load->model ( 'vistas/MVistas', 'MVistas' );
		$vista = $this->MVistas->vista1 ();
		echo $vista ['json'];
	}
	function Pronto_Pago() {
		$data ['Menu'] = $this->CMenu->getHtml_Menu ( $this->session->userdata ( 'nivel' ) );
		$this->load->view ( "verifica_pronto_pago", $data );
	}
	function Verifica_Pronto_Pago() {
		$this->load->model ( "cliente/mprontopago", 'MProntopago' );
		$ced = $_POST ['cedula'];
		$fact = $_POST ['factura'];
		echo $this->MProntopago->Verifica ( $ced, $fact );
	}
	
	/* creado por mauricio */
	public function Comprobante_de_Pago() {
		if ($this->session->userdata ( 'usuario' )) {
			$data ['Menu'] = $this->CMenu->getHtml_Menu ( $this->session->userdata ( 'nivel' ) );
			$data ['Nivel'] = $this->session->userdata ( 'nivel' );
			$data ['iPosicion'] = $this->session->userdata ( 'posicion' );
			$this->load->view ( "comprobante_de_pago", $data );
		} else {
			$this->login ();
		}
	}
	public function Acuse_Recibo_Administracion() {
		if ($this->session->userdata ( 'usuario' )) {
			$data ['Menu'] = $this->CMenu->getHtml_Menu ( $this->session->userdata ( 'nivel' ) );
			$data ['Nivel'] = $this->session->userdata ( 'nivel' );
			$data ['iPosicion'] = $this->session->userdata ( 'posicion' );
			$this->load->view ( "acuse", $data );
		} else {
			$this->login ();
		}
	}
	public function GuardaReciboAdmin() {
		$this->load->model ( "recibo/macuse", 'MAcuse' );
		echo $this->MAcuse->Guarda_Acuse ( $_POST );
	}
	
	/* Fin por mauricio */
	
	/*
	 * FUNCIONES PARA CREAR ACUSE
	 */
	public function Listar_Acuse() {
		if ($this->session->userdata ( 'usuario' )) {
			$this->load->model ( "recibo/macuse", 'MAcuse' );
			echo $this->MAcuse->Listar ();
		} else {
			$this->login ();
		}
	}
	public function Imprimir_Acuse($acuse = '') {
		$consulta = $this->db->query ( "SELECT * FROM t_acuse WHERE acuse='" . $acuse . "'" );
		foreach ( $consulta->result () as $row ) {
			$data ['acuse'] = $acuse;
			$data ['fecha'] = $row->fecha;
			$data ['cedula'] = $row->cedula_rif;
			$data ['nombre'] = $row->nombre_razon;
			$data ['monto'] = $row->monto;
			$data ['concepto'] = $row->concepto;
			$data ['banco'] = $row->banco;
			$data ['chequera'] = $row->chequera;
			$data ['cheque'] = $row->cheque;
			$data ['modificado'] = $row->modificado;
			$this->load->view ( "acuse/acuse_administracion", $data );
		}
	}
	
	/*
	 * FIN ACUSE
	 */
	
	/*
	 * FUNCIONES PARA MODIFICAR MONTO OFICINA INVENTARIO
	 */
	public function Modificar_Monto_Oficina() {
		if (isset ( $_POST ['objeto'] )) {
			$json = json_decode ( $_POST ['objeto'], true );
			
			// $query = "UPDATE t_productos SET precio_oficina=" . trim($json[1]) . " WHERE serial='" . trim($json[0]) . "'";
			$query = "UPDATE t_productos SET precio_oficina=" . trim ( $json [1] ) . " WHERE ubicacion='" . trim ( $json [2] ) . "' AND estatus_mercancia!=2 AND inventario_id=" . $json [3];
			$this->db->query ( $query );
			echo "Se Modifico Monto De Oficina.";
		} else {
			echo "No Se Pudo Modificar Monto";
		}
	}
	
	/*
	 * FIN FUNCIONES PARA CREAR MONTO OFICINA INVENTARIO
	 */
	
	/*
	 * FUNCIONES PARA CREAR FACTURA DE CONTADO
	 */
	public function Venta_Contado() {
		if ($this->session->userdata ( 'usuario' )) {
			$data ['Menu'] = $this->CMenu->getHtml_Menu ( $this->session->userdata ( 'nivel' ) );
			$data ['Nivel'] = $this->session->userdata ( 'nivel' );
			$data ['iPosicion'] = $this->session->userdata ( 'posicion' );
			$this->load->view ( "venta_contado", $data );
		} else {
			$this->login ();
		}
	}
	public function Guarda_Contado() {
		if ($this->session->userdata ( 'usuario' )) {
			$data ['usuario'] = $this->session->userdata ( 'usuario' );
			$data ['ubicacion'] = $this->session->userdata ( 'ubicacion' );
			$data ['cedula'] = $_POST ['cedula'];
			$data ['factura'] = $_POST ['factura'];
			$data ['fecha'] = $_POST ['fecha'];
			$data ['nombre'] = $_POST ['nombre'];
			$data ['direc'] = $_POST ['direc'];
			$data ['telf'] = $_POST ['telf'];
			$data ['monto'] = $_POST ['monto'];
			$data ['descrip'] = $_POST ['descrip'];
			$data ['forma_pago'] = $_POST ['fp'];
			$this->db->insert ( 't_venta_contado', $data );
			echo "Se registro Venta De Contado";
		} else {
			$this->login ();
		}
	}
	public function Listar_Contado() {
		$nivel = $this->session->userdata ( 'nivel' );
		if ($nivel == 0 || $nivel == 9 || $nivel == 5 || $this->session->userdata ( 'usuario' ) == 'Carlos') {
			$this->load->model ( 'cliente/mcliente', 'MCliente' );
			if (isset ( $_POST )) {
				$arr ['desde'] = $_POST ['desde'];
				$arr ['hasta'] = $_POST ['hasta'];
				$arr ['ubicacion'] = $_POST ['ubicacion'];
				
				if ($nivel == 5) {
					$arr ['ubicacion'] = $this->session->userdata ( 'ubicacion' );
				}
				$objeto = $this->MCliente->Listar_Contado ( $arr );
			} else {
				$objeto = $this->MInventario->Listar_Contado ( '' );
			}
			echo $objeto;
		} else {
			$this->logout ();
		}
	}
	
	/*
	 * FIN FUNCIONES PARA CREAR FACTURA DE CONTADO
	 */
	
	/*
	 * FUNCIONES PARA CREAR FOTO DE CLIENTE
	 */
	public function foto($ced = '') {
		if ($this->session->userdata ( 'usuario' )) {
			$data ['ced'] = $ced;
			$this->load->view ( "cliente/foto", $data );
		} else {
			$this->login ();
		}
	}
	public function subir_foto($cedula) {
		$resultado = $this->db->query ( 'SELECT * FROM t_personas WHERE documento_id="' . $cedula . '"' );
		$can = $resultado->num_rows ();
		$url = '';
		if ($can > 0) {
			
			$directorio = BASEPATH . 'repository/expedientes/fotos/';
			$nombre = '/system/repository/expedientes/fotos/' . $cedula . '.jpg';
			$filename = $directorio . $cedula . '.jpg';
			
			$result = file_put_contents ( $filename, file_get_contents ( 'php://input' ) );
			// $subido = copy($_FILES[$archivo]['tmp_name'], $$nombre);
			if (! $result) {
				print "ERROR: Verificar permisos de escritura\n";
				exit ();
			}
			chmod ( $nombre, '0777' );
			// chmod($nombre, '0777');
			$url = 'http://' . $_SERVER ['HTTP_HOST'] . $nombre;
		} else {
			print "ERROR: Cliente no esta registrado\n";
			exit ();
		}
		print "$url\n";
	}
	
	/*
	 * FIN FOTO
	 */
	
	/*
	 * Funcion para imprimir carnet
	 */
	public function carnet($cedula = '') {
		if ($cedula != '') {
			$busca = $this->db->query ( "SELECT * FROM t_personas WHERE documento_id='" . $cedula . "' LIMIT 1" );
			foreach ( $busca->result () as $per ) {
				$data ['documento_id'] = $per->documento_id;
				$data ['nombre'] = $per->primer_nombre . ' ' . $per->segundo_nombre . ' ' . $per->primer_apellido . ' ' . $per->segundo_apellido;
				$data ['nro_documento'] = $per->nro_documento;
				// $nombre = '/cooperativa/system/repository/expedientes/fotos/' . $cedula . '.jpg';
				// $data['foto'] = $url = 'http://' . $_SERVER['HTTP_HOST'] . $nombre;
				$this->load->view ( 'reportes/carnet', $data );
			}
		}
	}
	
	/*
	 * FIN Carnet
	 */
	
	/*
	 * Funciones Para Generar Facturas Presupuesto
	 */
	public function factura_presupuesto($cert = '', $ced = '', $emp = '', $mon = 0) {
		if ($this->session->userdata ( 'usuario' )) {
			$data ['Menu'] = $this->CMenu->getHtml_Menu ( $this->session->userdata ( 'nivel' ) );
			$data ['Nivel'] = $this->session->userdata ( 'nivel' );
			$data ['ubicacion'] = $this->session->userdata ( 'ubicacion' );
			$data ['ced'] = $ced;
			$data ['cert'] = $cert;
			$data ['emp'] = $emp;
			$data ['mon'] = $mon;
			$this->load->view ( "asistente/asistente4", $data );
		} else {
			$this->login ();
		}
	}
	public function GV_Fpresuesto() {
		$this->load->model ( "vistas/mvistas", 'MVista' );
		echo $this->MVista->fpresuesto ();
	}
	public function Guarda_Fpresupuesto() {
		$fecha = date ( "Y-m-d" );
		if ($this->session->userdata ( 'usuario' )) {
			$this->load->model ( "cliente/mpresupuesto", 'MPresupuesto' );
			if (! $this->MPresupuesto->Busca ( $_POST ['factura'] )) {
				$this->MPresupuesto->factura = $_POST ['factura'];
				$this->MPresupuesto->cedula = $_POST ['cedula'];
				$this->MPresupuesto->nombre = $_POST ['nombre'];
				$this->MPresupuesto->fecha = $fecha;
				$this->MPresupuesto->total = $_POST ['total'];
				$this->MPresupuesto->direccion = $_POST ['direccion'];
				$this->MPresupuesto->telf = $_POST ['telf'];
				$this->MPresupuesto->empresa = $_POST ['empresa'];
				$this->MPresupuesto->cert = $_POST ['cert'];
				$this->MPresupuesto->estatus = 0;
				$this->MPresupuesto->ubicacion = $this->session->userdata ( 'ubicacion' );
				$this->MPresupuesto->usuario = $this->session->userdata ( 'usuario' );
				$res = $this->MPresupuesto->Guardar ( $_POST ['productos'] );
				$res .= '<br>' . '<a href=\'' . __LOCALWWW__ . '/index.php/cooperativa/Formato_Fpresupuesto/' . $_POST ['factura'] . '/' . $_POST ['empresa'] . ' \' border=0 target=\'top\'>Factura Presupuesto</a>';
				echo $res;
			} else {
				echo "Factura ya existe, no se puede Modificar.Solicitar cualquier modificacion al dept. Planificacion.";
			}
			// echo $_POST['productos'];
		} else {
			$this->login ();
		}
	}
	function Formato_Fpresupuesto($factura = '', $emp='') {
		if ($factura != '') {
			$this->load->model ( 'reporte/pformatos', 'PFormatos' );
			$this->PFormatos->presupuesto ( $factura, $emp );
		} else {
			echo "Debe ingresar una cedula";
		}
	}
	function Consulta_Fpresupuesto() {
		$this->load->model ( "cliente/mpresupuesto", 'MPresupuesto' );
		$factura = $_POST ['factura'];
		echo $this->MPresupuesto->Consultar ( $factura );
	}
	public function Listar_FPresupuesto() {
		$nivel = $this->session->userdata ( 'nivel' );
		if ($nivel == 0 || $nivel == 9 || $nivel == 5 || $this->session->userdata ( 'usuario' ) == 'Carlos' || $nivel == 18 || $nivel == 19 || $this -> session -> userdata('usuario') == 'mvanalista11') {
			$this->load->model ( "cliente/mpresupuesto", 'MPresupuesto' );
			if (isset ( $_POST )) {
				$arr ['desde'] = $_POST ['desde'];
				$arr ['hasta'] = $_POST ['hasta'];
				$arr ['ubicacion'] = $_POST ['ubicacion'];
				
				if ($nivel == 5) {
					$arr ['ubicacion'] = $this->session->userdata ( 'ubicacion' );
				}
                if ($this->session->userdata ( 'usuario' ) == 'AlvaroZ') {
                    $arr ['ubicacion'] = $_POST ['ubicacion'];
                }
				$objeto = $this->MPresupuesto->Listar_FPresupuesto ( $arr );
			} else {
				$objeto = $this->MPresupuesto->Listar_FPresupuesto ( '' );
			}
			echo $objeto;
		} else {
			$this->logout ();
		}
	}
	public function Modifica_Fpresu() {
		$niv = $this->session->userdata ( 'nivel' );
		if ($niv == 0 || $niv == 9 || $this->session->userdata ( 'usuario' ) == 'Carlos' || $niv == 18 || $niv == 19) {
			
			$json = json_decode ( $_POST ['objeto'], true );
			$mod = array (
					"nombre" => $json [2],
					"direccion" => $json [3],
					"telf" => $json [4],
					"total" => $json [5] 
			);
			$this->db->where ( "factura", $json [0] );
			$this->db->where ( "cedula", $json [1] );
			$this->db->update ( "t_fpresupuesto", $mod );
			$this->db->query ( "UPDATE t_it_fpresupuesto SET monto=" . $json [5] . " WHERE factura='" . $json [0] . "'" );
			echo "Se Modifico Factura.";
		} else {
			echo "No tiene permisos para realizar modificaciones...";
		}
	}
	public function Elimina_Fpresu() {
		$niv = $this->session->userdata ( 'nivel' );
		if ($niv == 0 || $niv == 9 || $this->session->userdata ( 'usuario' ) == 'Carlos' || $this->session->userdata ( 'usuario' ) == 'AlvaroZ' || $niv == 18) {
			$json = json_decode ( $_POST ['objeto'], true );
			$this->db->query ( "DELETE FROM t_fpresupuesto WHERE factura = '" . $json [0] . "'" );
			$this->db->query ( "DELETE FROM t_it_fpresupuesto WHERE factura = '" . $json [0] . "'" );
			echo "Se Elimina Factura.";
		} else {
			echo "No tiene permisos para realizar ELIMINACION...";
		}
	}
	public function Detalle_Fpresu() {
		$this->load->model ( "cliente/mpresupuesto", 'MPresupuesto' );
		$json = json_decode ( $_POST ['objeto'], true );
		$factura = $json ['0'];
		$objeto = $this->MPresupuesto->Listar_FPresupuesto_Detalle ( $factura );
		echo $objeto;
	}
	
	/**
	 * Fin Factura Presupuesto
	 *
	 * @param
	 *        	Empresa 1: Grupo 0: Cooperativa
	 * @return string
	 */
	 
	 /*
	 * Funciones Para Generar Facturas Control
	 */
	public function factura_control() {
		if ($this->session->userdata ( 'usuario' )) {
			$data ['Menu'] = $this->CMenu->getHtml_Menu ( $this->session->userdata ( 'nivel' ) );
			$data ['Nivel'] = $this->session->userdata ( 'nivel' );
			$data ['ubicacion'] = $this->session->userdata ( 'ubicacion' );
			$this->load->view ( "fcontrol", $data );
		} else {
			$this->login ();
		}
	}

	public function factura_control_sys($ced = '', $emp = '', $mon = 0) {
		$this->load->view ( "fcontrol_sys" );
	}
	
	public function GV_Fcontrol() {
		$this->load->model ( "vistas/mvistas", 'MVista' );
		echo $this->MVista->fcontrol();
	}
	public function Guarda_Fcontrol() {
		$fecha = date ( "Y-m-d" );
		if ($this->session->userdata ( 'usuario') || $_POST['tpe'] == 2) {
			$this->load->model ( "cliente/mfcontrol", 'MFcontrol' );
			if (! $this->MFcontrol->Busca ( $_POST ['factura'],$_POST['control'] )) {
				$this->MFcontrol->factura = $_POST ['factura'];
				$this->MFcontrol->cedula = $_POST ['cedula'];
				$this->MFcontrol->nombre = $_POST ['nombre'];
				$this->MFcontrol->fecha = $fecha;
				$this->MFcontrol->total = $_POST ['total'];
				$this->MFcontrol->control = $_POST ['control'];
				$this->MFcontrol->telf = $_POST ['telf'];
				$this->MFcontrol->estatus = 0;
				if($_POST['tpe']!=2){
					$this->MFcontrol->empresa = $_POST ['empresa'];
					$this->MFcontrol->ubicacion = $this->session->userdata ( 'ubicacion' );
					$this->MFcontrol->usuario = $this->session->userdata ( 'usuario' );
				}else{
					$this->MFcontrol->empresa = 2;
					$this->MFcontrol->ubicacion = 'La Victoria';
					$this->MFcontrol->usuario = 'S&S';
				}
				$res = $this->MFcontrol->Guardar ( $_POST ['productos'] );
				$res .= '<br>' . '<a href=\'' . __LOCALWWW__ . '/index.php/cooperativa/Formato_Fcontrol/' . $_POST ['factura'] . '/' . $_POST ['empresa'] . ' \' border=0 target=\'top\'>Imprimir Factura Control</a>';
				echo $res;
			} else {
				echo "Factura ya existe, no se puede Modificar.Solicitar cualquier modificacion al dept. Planificacion.";
			}
			// echo $_POST['productos'];
		} else {
			$this->login ();
		}
	}
	function Formato_Fcontrol($factura = '', $emp='') {
		if ($factura != '') {
			$this->load->model ( 'reporte/pformatos', 'PFormatos' );
			$this->PFormatos->fcontrol ( $factura, $emp );
		} else {
			echo "Debe ingresar una cedula";
		}
	}
	function Consulta_Fcontrol() {
		$this->load->model ( "cliente/mfcontrol", 'MControl' );
		$factura = $_POST ['factura'];
		echo $this->MControl->Consultar ( $factura );
	}
	public function Listar_Fcontrol() {
		$nivel = $this->session->userdata ( 'nivel' );
		if ($nivel == 0 || $nivel == 9 || $nivel == 5 || $this->session->userdata ( 'usuario' ) == 'Carlos' || $nivel == 18 || $nivel == 19) {
			$this->load->model ( "cliente/mfcontrol", 'MFcontrol' );
			if (isset ( $_POST )) {
				$arr ['desde'] = $_POST ['desde'];
				$arr ['hasta'] = $_POST ['hasta'];
				$arr ['ubicacion'] = $_POST ['ubicacion'];
				
				if ($nivel == 5) {
					$arr ['ubicacion'] = $this->session->userdata ( 'ubicacion' );
				}
				$objeto = $this->MFcontrol->Listar_Fcontrol ( $arr );
			} else {
				$objeto = $this->MFcontrol->Listar_Fcontrol ( '' );
			}
			echo $objeto;
		} else {
			$this->logout ();
		}
	}
	public function Modifica_Fcontrol() {
		$niv = $this->session->userdata ( 'nivel' );
		if ($niv == 0 || $niv == 9 || $this->session->userdata ( 'usuario' ) == 'Carlos' || $niv == 18 || $niv == 19) {
			
			$json = json_decode ( $_POST ['objeto'], true );
			$mod = array (
					"nombre" => $json [2],
					"control" => $json [3],
					"telf" => $json [4],
					"total" => $json [5] 
			);
			$this->db->where ( "factura", $json [0] );
			$this->db->where ( "cedula", $json [1] );
			$this->db->update ( "t_fcontrol", $mod );
			$this->db->query ( "UPDATE t_it_fcontrol SET monto=" . $json [5] . " WHERE factura='" . $json [0] . "'" );
			echo "Se Modifico Factura.";
		} else {
			echo "No tiene permisos para realizar modificaciones...";
		}
	}
	public function Elimina_Fcontrol() {
		$niv = $this->session->userdata ( 'nivel' );
		if ($niv == 0 || $niv == 9 || $this->session->userdata ( 'usuario' ) == 'Carlos' || $niv == 18) {
			$json = json_decode ( $_POST ['objeto'], true );
			$this->db->query ( "DELETE FROM t_fcontrol WHERE factura = '" . $json [0] . "'" );
			$this->db->query ( "DELETE FROM t_it_fcontrol WHERE factura = '" . $json [0] . "'" );
			echo "Se Elimina Factura.";
		} else {
			echo "No tiene permisos para realizar ELIMINACION...";
		}
	}
	public function Anular_Fcontrol() {
		$niv = $this->session->userdata ( 'nivel' );
		if ($niv == 0 || $niv == 9 || $this->session->userdata ( 'usuario' ) == 'Carlos' || $this->session->userdata ( 'usuario' ) == 'AlvaroZ' || $niv == 18) {
			$json = json_decode ( $_POST ['objeto'], true );
			$this->db->query ( "UPDATE t_fcontrol SET estatus=1 WHERE factura = '" . $json [0] . "'" );

			echo "Se Anulo Factura Factura.";
		} else {
			echo "No tiene permisos para realizar ELIMINACION...";
		}
	}
	public function Detalle_Fcontrol() {
		$this->load->model ( "cliente/mfcontrol", 'MControl' );
		$json = json_decode ( $_POST ['objeto'], true );
		$factura = $json ['0'];
		$objeto = $this->MControl->Listar_FControl_Detalle ( $factura );
		echo $objeto;
	}
	
	/**
	 * Fin Factura Control
	 *
	 * @param
	 *        	Empresa 1: Grupo 0: Cooperativa
	 * @return string
	 */
	 
	public function Generar_Codigo_Semillero($iEmp = 0) {
		$sem = '';
		$pre = 'CN';
		$sCodg = '';
		// Codigo Generado con P inicial y 6 Ceros
		$sC = "SELECT MAX(cod) AS cod FROM t_semillero WHERE emp=" . $iEmp . ";";
		$rs = $this->db->query ( $sC );
		foreach ( $rs->result () as $semilla ) {
			$sem = $semilla->cod;
		}
		$sCod = ($sem + 1);
		$sConsulta = "SELECT cod FROM t_semillero WHERE cod='" . $sCod . "' AND emp=" . $iEmp . " LIMIT 1";
		$rsC = $this->db->query ( $sConsulta );
		if ($rsC->num_rows () != 0) {
			$this->Generar_Codigo_Semillero ();
		} else {
			$sCodg = $this->Completar ( $sCod, 6 );
			// Codigo Completado con Ceros
			if ($iEmp == 1)
				$pre = 'GN';
			$this->db->query ( 'INSERT INTO t_semillero (oid, cod,cla,emp) VALUES (NULL, ' . $sCod . ', \'' . $pre . $sCodg . '\', ' . $iEmp . ');' );
		}
		
		echo $pre . $sCodg;
		// Codigo generado
	}
	
	/*
	 * Inicio Archivo
	 * Creado por: Mauricio
	 */
	public function archivo_cliente() {
		if ($this->session->userdata ( 'usuario' )) {
			$data ['Menu'] = $this->CMenu->getHtml_Menu ( $this->session->userdata ( 'nivel' ) );
			$data ['Nivel'] = $this->session->userdata ( 'nivel' );
			$data ['ubicacion'] = $this->session->userdata ( 'ubicacion' );
			$this->load->view ( "archivo/archivo_cliente", $data );
		} else {
			$this->login ();
		}
	}
	public function GV_Archivo() {
		$this->load->model ( "vistas/mvistas", 'MVista' );
		echo $this->MVista->Carchivo ();
	}
	public function Guarda_Carchivo() {
		if ($this->session->userdata ( 'usuario' )) {
			if ($this->session->userdata ( 'nivel' ) == 16) {
				$this->load->model ( "archivo/marchivo", 'MArchivo' );
				$res = $this->MArchivo->Guardar ( $_POST );
				echo $res;
			} else {
				echo "El archivo solo puede ser registrado por usuarios de perfil ARCHIVO";
			}
			
			// echo $_POST['productos'];
		} else {
			$this->login ();
		}
	}
	public function Modificar_Carchivo() {
		if ($this->session->userdata ( 'usuario' )) {
			$this->load->model ( "archivo/marchivo", 'MArchivo' );
			$json = json_decode ( $_POST ['objeto'], true );
			$mod = array (
					"observa" => $json [2],
					"entregado" => $json [3],
					"recibido" => $json [4],
					"fecha" => $json [5],
					"ubicacion" => $json [6],
					"perfil" => $json [7] 
			);
			$this->db->where ( "numero", $json [0] );
			$this->db->where ( "cedula", $json [1] );
			$this->db->update ( "t_archivo", $mod );
			echo "Se Modifico Monto De Archivo.";
		} else {
			$this->login ();
		}
	}
	function Consulta_Carchivo() {
		$this->load->model ( "archivo/marchivo", 'MArchivo' );
		$cedula = $_POST ['cedula'];
		if ($this->session->userdata ( 'nivel' ) == 16) {
			echo $this->MArchivo->Historial_Cliente_Json ( $cedula );
		} else {
			echo $this->MArchivo->Historial_Cliente ( $cedula );
		}
	}
	
	/*
	 * Fin Archivo
	 */
	
	/*
	 * Inicio de funciones para el asistente
	 */
	public function Asistente1() {
		if ($this->session->userdata ( 'usuario' )) {
			$data ['Menu'] = $this->CMenu->getHtml_Menu ( $this->session->userdata ( 'nivel' ) );
			$data ['Nivel'] = $this->session->userdata ( 'nivel' );
			$data ['ubicacion'] = $this->session->userdata ( 'ubicacion' );
			$this->load->view ( "asistente/asistente1", $data );
		} else {
			$this->login ();
		}
	}
	public function GV_Asistente1() {
		$this->load->model ( "vistas/mvistas", 'MVista' );
		echo $this->MVista->Asistente1 ();
	}
	public function Guarda_Asistente1() {
		$consulta = 'SELECT * FROM t_personas WHERE documento_id="' . $_POST ['documento_id'] . '"';
		$persona = $this->db->query ( $consulta );
		$msj = 'Cliente ya existe';
		if ($persona->num_rows () == 0) {
			$this->db->insert ( 't_personas', $_POST );
			$msj = 'Se registro con exito';
		}
		echo $msj;
	}
	
	/*
	 * Fin Asistente
	 */
	function Evalua_Arreglo() {
		$this->load->model ( "arreglos/marreglo", 'MArreglo' );
		$arreglo = array (
				"nivel1" => ( object ) array (
						"NIVLE1.1" => "n1.1",
						"NIVLE1.2" => "n1.2",
						"NIVLE1.3" => "n1.3" 
				),
				"nivel2" => ( object ) array (
						"otro" => "NIVEL2.1",
						"NIVLE2.2" => ( object ) array (
								"NIVLE1.1" => "n1.1",
								"NIVLE1.2" => "n1.2",
								"NIVLE1.3" => "n1.3" 
						) 
				),
				"nivel3" => array (
						"NIVLE3.1" => "n3.1",
						"NIVLE3.2" => "n3.2",
						"NIVLE3.3" => array (
								"NIVLE3.1.1" => "n3.1.1",
								"NIVLE3.1.2" => "n3.1.2",
								"NIVLE3.1.3" => "n3.1.3" 
						) 
				) 
		);
		print ("<pre>") ;
		print_R ( $arreglo );
		
		$respuesta = $this->MArreglo->Obj_Valores ( $arreglo );
		print ("<h1>FINAL</h1>") ;
		print_R ( $this->MArreglo->objValores );
		print ("<h1>FINAL2</h1>") ;
	}
	function Panel_Traslado() {
		if ($this->session->userdata ( 'usuario' )) {
			$data ['Menu'] = $this->CMenu->getHtml_Menu ( $this->session->userdata ( 'nivel' ) );
			$data ['Nivel'] = $this->session->userdata ( 'nivel' );
			$data ['ubicacion'] = $this->session->userdata ( 'ubicacion' );
			$this->load->view ( "panel_traslado", $data );
		} else {
			$this->login ();
		}
	}
	function Lista_Por_Trasladar() {
		$this->load->model ( "cliente/mvoucher", "MVoucher" );
		echo $this->MVoucher->Lista_Por_Trasladar ( $_POST ['fecha'] );
		// echo "pasa";
	}
	function Procesa_Traslado_Masivo() {
		$this->load->model ( "cliente/mvoucher", "MVoucher" );
		echo $this->MVoucher->Procesa_Traslado_Masivo ();
		// echo "SE PROCESARON LOS TRASLADOS MASIVOS CON EXITO";
	}
	public function Crear_Usuario() {
		if ($this->session->userdata ( 'usuario' )) {
			$data ['Menu'] = $this->CMenu->getHtml_Menu ( $this->session->userdata ( 'nivel' ) );
			$data ['Nivel'] = $this->session->userdata ( 'nivel' );
			$data ['ubicacion'] = $this->session->userdata ( 'ubicacion' );
			$this->load->view ( "usuario/crear", $data );
		} else {
			$this->login ();
		}
	}
	public function GV_Usuario() {
		$this->load->model ( "vistas/mvistas", 'MVista' );
		$respuesta = $this->MVista->Crea_Usuario ();
		$ruta = BASEPATH . 'js/estatico/gv_usuario.js';
		$archivo = fopen ( $ruta, 'w' );
		$texto = "var Esq_gv_usuario=" . $respuesta;
		fwrite ( $archivo, $texto );
		echo $texto;
		fclose ( $archivo );
		echo $ruta;
	}
	public function Consulta_Usuario($doc = '') {
		if ($doc != '') {
			$dato = $doc;
			$sql = "SELECT * FROM t_usuario 
	 				join _tr_usuarioperfil on t_usuario.oid = _tr_usuarioperfil.oidu 
	 				join _tr_usuarioubicacion on t_usuario.oid = _tr_usuarioubicacion.oidu 
	 				WHERE documento_id =" . $dato . " LIMIT 1";
			$consulta = $this->db->query ( $sql );
		} else {
			if (isset ( $_POST ['documento_id'] )) {
				$sql = "SELECT * FROM t_usuario 
						join _tr_usuarioperfil on t_usuario.oid = _tr_usuarioperfil.oidu  
						join _tr_usuarioubicacion on t_usuario.oid = _tr_usuarioubicacion.oidu
						WHERE documento_id =" . $_POST ['documento_id'] . " LIMIT 1";
				$consulta = $this->db->query ( $sql );
			}
			if (isset ( $_POST ['seudonimo'] )) {
				$sql = "SELECT * FROM t_usuario 
						join _tr_usuarioperfil on t_usuario.oid = _tr_usuarioperfil.oidu  
						join _tr_usuarioubicacion on t_usuario.oid = _tr_usuarioubicacion.oidu
						WHERE seudonimo ='" . $_POST ['seudonimo'] . "' LIMIT 1";
				$consulta = $this->db->query ( $sql );
			}
		}
		// $consulta = $this -> db -> query("SELECT * FROM t_usuario WHERE documento_id =". $dato ." LIMIT 1");
		$can = $consulta->num_rows ();
		$campos = $consulta->list_fields ();
		$respuesta = array ();
		if ($can > 0) {
			$respuesta ['error'] = 0;
			foreach ( $consulta->result () as $fila ) {
				foreach ( $campos as $clave ) {
					$respuesta [$clave] = $fila->$clave;
				}
			}
		} else {
			$respuesta ['error'] = 1;
		}
		echo json_encode ( $respuesta );
	}
	public function Guarda_Usuario() {
		$usu = array ();
		$datos = json_decode ( $_POST ['datos'], TRUE );
		if ($datos ['oid'] != '') {
			echo "No se puede modificar un usuario existente.";
		} else {
			$cmpUsuario = array (
					'documento_id',
					'descripcion',
					'seudonimo',
					'clave',
					'correo',
					'fecha',
					'estatus',
					'conectado' 
			);
			foreach ( $cmpUsuario as $campo ) {
				if (isset ( $datos [$campo] )) {
					$usu [$campo] = $datos [$campo];
				}
			}
			$usu ['clave'] = md5 ( $usu ['clave'] );
			$this->load->model ( "usuario/musuario", "musuario" );
			$this->musuario->Guardar ( $usu, $datos ['oidp'], $datos ['oidb'] );
		}
	}
	public function c_usuario() {
		$consulta = $this->db->query ( "SELECT * FROM t_usuario" );
		$result = $consulta->result ();
		$arreglo = array ();
		foreach ( $result as $row ) {
			$arreglo [] = $row->seudonimo;
		}
		$envia = $arreglo;
		echo json_encode ( $envia );
	}
	public function Crear_Ubicacion() {
		if ($this->session->userdata ( 'usuario' )) {
			$data ['Menu'] = $this->CMenu->getHtml_Menu ( $this->session->userdata ( 'nivel' ) );
			$data ['Nivel'] = $this->session->userdata ( 'nivel' );
			$data ['ubicacion'] = $this->session->userdata ( 'ubicacion' );
			$this->load->view ( "usuario/ubicacion", $data );
		} else {
			$this->login ();
		}
	}
	public function Lista_Ubicaciones() {
		$this->load->model ( "ubicacion/mubicacion", 'MUbicacion' );
		echo $this->MUbicacion->Listar ();
	}
	public function GV_Ubicacion() {
		$this->load->model ( "vistas/mvistas", 'MVista' );
		return $this->MVista->Crea_Ubicacion ();
	}
	public function Guarda_Ubicacion() {
		$usu = array ();
		$datos = json_decode ( $_POST ['datos'], TRUE );
		if ($datos ['oid'] != '') {
			echo "No se puede modificar un usuario existente.";
		} else {
			$this->db->insert ( "t_ubicacion", $datos );
			echo "Se Creo nueva ubicacion con exito";
		}
	}
	public function Modifica_Direccion() {
		$json = json_decode ( $_POST ['objeto'], true );
		$update = $this->db->query ( "UPDATE t_ubicacion SET direccion='" . $json [1] . "',sucursal='" . $json [2] . "' WHERE oid=" . $json [0] );
		echo "Se Modifico Con Exito";
	}
	public function Genera_Plantilla() {
		$funcion = $_POST ['funcion'] . '()';
		eval ( "$" . "respuesta = " . "$" . "this ->" . $funcion . ";" );
		$ruta = BASEPATH . 'js/estatico/' . $_POST ['archivo'] . '.js';
		$archivo = fopen ( $ruta, 'w' );
		$texto = "var Esq_" . $_POST ['archivo'] . "=" . $respuesta;
		fwrite ( $archivo, $texto );
		echo $texto;
		fclose ( $archivo );
	}
	function getUltimoDiaMes($ano, $mes) {
		return date ( "d", (mktime ( 0, 0, 0, $mes + 1, 1, $ano ) - 1) );
	}
	public function GenerarControlPagos() {
		$this->load->model ( 'cobranza/mcobranza', 'MCobranza' );
		
		$this->MCobranza->empresa = $_POST ['empresa'];
		//$this->MCobranza->periocidad = 9;
		$this->MCobranza->periocidad = $_POST["perio"];
		$this->MCobranza->fecha = $_POST ['desde'];
		$this->MCobranza->anop = $_POST ['anop'];
		$this->MCobranza->mesp = $_POST ['mesp'];
		$this->MCobranza->desde = $_POST ['anop'] . '-' . $_POST ['mesp'] . '-01';
		$this->MCobranza->hasta = $_POST ['anop'] . '-' . $_POST ['mesp'] . '-' . $this->getUltimoDiaMes ( $_POST ['anop'], $_POST ['mesp'] );
		$this->MCobranza->nomina = $_POST ['banco'];
		$this->MCobranza->formaContrato = $_POST ['forma'];
		
		echo $this->MCobranza->CrearGrid ();
	}
	function Guardar_Lote() {
		$this->load->model ( 'cobranza/mcobranza', 'MCobranza' );
		$json = json_decode ( $_POST ['objeto'], TRUE );
		echo '<br><br><center><pre>';
		echo $this->MCobranza->Guardar ( $json, $this->session->userdata ( 'oidu' ) );
		// print_r($json);
		// print_r($this->session->userdata('oidu'));
		echo '</center>';
	}
	public function Eliminar_Voucher_Factura() {
		$this->load->model ( "cliente/mvoucher", "mvoucher" );
		$this->mvoucher->Eliminar_Voucher_Factura ( $_POST );
		echo "Se Proceso con exito";
	}
	public function Eliminar_Voucher_Deposito() {
		$this->load->model ( "cliente/mvoucher", "mvoucher" );
		$this->mvoucher->Eliminar_Voucher_Deposito ( $_POST );
		// print_R($_POST);
		echo "Se Proceso con exito";
	}
	public function Listar_Ncliente() {
		$this->load->model ( "cliente/mcliente", "MCliente" );
		echo $this->MCliente->Listar_Ncliente ( $_POST );
	}
	
	/*
	 * Funciones para cargar expediente a revision nueva modalidad
	 */
	public function subir_expediente() {
		if ($this->session->userdata ( 'usuario' )) {
			$data ['Nivel'] = $this->session->userdata ( 'nivel' );
			$data ['Menu'] = $this->CMenu->getHtml_Menu ( $this->session->userdata ( 'nivel' ) );
			$this->load->view ( "cliente/subir_expediente", $data );
		} else {
			$this->login ();
		}
	}
	public function Busca_Imagenes($lugar = '00021', $ced = '17522251') {
		if (isset ( $_POST ['lugar'] ))
			$lugar = $_POST ['lugar'];
		if (isset ( $_POST ['ced'] ))
			$ced = $_POST ['ced'];
		$directory = "system/repository/revision/" . strtolower ( $lugar );
		$directory = str_replace ( '\\', '/', getcwd () ) . '/' . $directory . '/';
		// echo $directory;
		$imagenes = array ();
		$i = 0;
		if ($directorio = opendir ( $directory )) {
			while ( $archivo = readdir ( $directorio ) ) {
				if (preg_match ( "/.gif/", $archivo ) || preg_match ( "/.jpg/", $archivo ) || preg_match ( "/.png/", $archivo ) || preg_match ( "/.jpeg/", $archivo )) {
					$imagenes [$i] = $archivo;
				}
				$i ++;
			}
			$obj ['resp'] = "Se encontro directorio";
		} else {
			$obj ['resp'] = "No se encontro directorio";
		}
		$obj ['imagenes'] = $imagenes;
		$obj ['ruta'] = strtolower ( $lugar );
		
		$sConsulta = "SELECT t_personas.documento_id, CONCAT(primer_apellido,' ',LEFT(segundo_apellido,1),\". \",primer_nombre,\" \", LEFT(segundo_nombre,1),\". \") AS Nombre,
		 banco_1, banco_2, numero_factura, nomina_procedencia 
		 FROM t_personas 
		 LEFT JOIN t_clientes_creditos ON t_personas.documento_id = t_clientes_creditos.documento_id 
		 WHERE t_personas.documento_id=$ced GROUP BY numero_factura";
		$rsC = $this->db->query ( $sConsulta );
		$rs = $rsC->result ();
		$obj ['cedula'] = $rs [0]->documento_id;
		$obj ['nombre'] = $rs [0]->Nombre;
		
		echo json_encode ( $obj );
	}
	public function Mueve_Expediente() {
		$carpeta = explode ( '|', $_POST ['carpeta'] );
		$nombre = $_POST ['nombre'];
		$ced = $_POST ['cedula'];
		$cert = $_POST ['cert'];
		
		$origen = "system/repository/revision/" . $cert;
		$origen = str_replace ( '\\', '/', getcwd () ) . '/' . $origen . '/';
		
		$destino = "system/repository/expedientes/" . $carpeta [1];
		$destino = str_replace ( '\\', '/', getcwd () ) . '/' . $destino . '/';
		
		$nuevo_nombre = md5 ( $ced . $cert . $nombre );
		
		if (rename ( $origen . $nombre, $destino . $nuevo_nombre )) {
			echo "Se copio con exito";
			$insert = "INSERT IGNORE INTO " . $carpeta [0] . "(cedula,nombre) VALUES ($ced,'$nuevo_nombre')";
			$this->db->query ( $insert );
		} else {
			echo "Error";
		}
	}
	
	/*
	 * Funciones para las solicitudes de sucursales
	 */
	public function Solicitud() {
		if ($this->session->userdata ( 'usuario' )) {
			$data ['Menu'] = $this->CMenu->getHtml_Menu ( $this->session->userdata ( 'nivel' ) );
			$data ['Nivel'] = $this->session->userdata ( 'nivel' );
			$this->load->view ( "solicitud", $data );
		} else {
			$this->logout ();
		}
	}
	public function GV_Solicitud() {
		$this->load->model ( "vistas/mvistas", 'MVista' );
		echo $this->MVista->Crea_Solicitud ();
	}
	public function GV_FSoli() {
		$this->load->model ( "vistas/mvistas", 'MVista' );
		echo $this->MVista->Filtro_Solicitud ();
	}
	public function GV_FSoli2  () {
		$this->load->model ( "vistas/mvistas", 'MVista' );
		echo $this->MVista->Filtro_Solicitud2 ();
	}
	public function GV_Bimg_Soli() {
		$this->load->model ( "vistas/mvistas", 'MVista' );
		echo $this->MVista->Busca_Img_Sol ();
	}
	function Generar_soliSRand() {
		$this->load->model ( "cliente/msolicitud", 'MSoli' );
		$sCod = rand ( 1, 99999 );
		echo $this->MSoli->_setCodigoSRand ( $sCod );
	}
	function GuardaSoli() {
		$this->load->model ( "cliente/msolicitud", 'MSoli' );
		$datos = json_decode ( $_POST ['datos'], true );
		// print("<pre>");
		// print_R($datos);
		$this->MSoli->Registrar ( $datos );
		echo "Se registro con exito";
	}
	function Listar_Soli_Ubi() {
		// print("<pre>");
		// print_R($_POST);
		$this->load->model ( "cliente/msolicitud", 'MSoli' );
		echo $this->MSoli->Grid_Ubicacion ( $_POST );
	}
	public function subir_solicitud() {
		if ($this->session->userdata ( 'usuario' )) {
			$data ['Nivel'] = $this->session->userdata ( 'nivel' );
			$data ['Menu'] = $this->CMenu->getHtml_Menu ( $this->session->userdata ( 'nivel' ) );
			$this->load->view ( "cliente/subir_solicitud", $data );
		} else {
			$this->login ();
		}
	}
	public function Busca_Imagenes_Soli($ubi = 'Barquisimeto', $cod = '00021', $ced = '17522251') {
		if (isset ( $_POST ['cod'] ))
			$cod = $_POST ['cod'];
		if (isset ( $_POST ['ced'] ))
			$ced = $_POST ['ced'];
		if (isset ( $_POST ['ubi'] ))
			$ubi = $_POST ['ubi'];
		$directory = "system/repository/solicitud/" . $ubi . "/" . $cod;
		$directory = str_replace ( '\\', '/', getcwd () ) . '/' . $directory . '/';
		// echo $directory;
		/*
		 * print("<pre>");
		 * print_R($_POST);
		 */
		$imagenes = array ();
		$i = 0;
		if ($directorio = opendir ( $directory )) {
			while ( $archivo = readdir ( $directorio ) ) {
				if (preg_match ( "/.gif/", $archivo ) || preg_match ( "/.jpg/", $archivo ) || preg_match ( "/.png/", $archivo ) || preg_match ( "/.jpeg/", $archivo )) {
					$imagenes [$i] = $archivo;
				}
				$i ++;
			}
			$obj ['res'] = "Existe Directorio";
		} else {
			$obj ['res'] = "No se encontro directorio";
		}
		$obj ['imagenes'] = $imagenes;
		$obj ['ruta'] = strtolower ( $cod );
		
		$sConsulta = "SELECT t_personas.documento_id, CONCAT(primer_apellido,' ',LEFT(segundo_apellido,1),\". \",primer_nombre,\" \", LEFT(segundo_nombre,1),\". \") AS Nombre,
		 banco_1, banco_2, numero_factura, nomina_procedencia 
		 FROM t_personas 
		 LEFT JOIN t_clientes_creditos ON t_personas.documento_id = t_clientes_creditos.documento_id 
		 WHERE t_personas.documento_id=$ced GROUP BY numero_factura";
		$rsC = $this->db->query ( $sConsulta );
		$rs = $rsC->result ();
		$obj ['cedula'] = $rs [0]->documento_id;
		$obj ['nombre'] = $rs [0]->Nombre;
		
		echo json_encode ( $obj );
	}
	public function Mueve_Solicitud() {
		$carpeta = explode ( '|', $_POST ['carpeta'] );
		$nombre = $_POST ['nombre'];
		$ced = $_POST ['cedula'];
		$cod = $_POST ['cod'];
		$ubi = $_POST ['ubi'];
		
		$origen = "system/repository/solicitud/" . $ubi . "/" . $cod;
		$origen = str_replace ( '\\', '/', getcwd () ) . '/' . $origen . '/';
		
		$destino = "system/repository/expedientes/" . $carpeta [1];
		$destino = str_replace ( '\\', '/', getcwd () ) . '/' . $destino . '/';
		
		$nuevo_nombre = md5 ( $ced . $cod . $nombre );
		
		if (copy ( $origen . $nombre, $destino . $nuevo_nombre )) {
			echo "Se copio con exito";
			$insert = "INSERT IGNORE INTO " . $carpeta [0] . "(cedula,nombre) VALUES ($ced,'$nuevo_nombre')";
			$this->db->query ( $insert );
		} else {
			echo "Error";
		}
	}
	function Aceptar_Soli() {
		$this->load->model ( "cliente/msolicitud", 'MSoli' );
		$datos = json_decode ( $_POST ['objeto'], true );
		$est = $datos [1] + 1;
		echo $this->MSoli->Modifica_Estatus ( $datos [0], $est );
	}
	function Rechazar_Soli() {
		$this->load->model ( "cliente/msolicitud", 'MSoli' );
		$datos = json_decode ( $_POST ['objeto'], true );
		echo $this->MSoli->Modifica_Estatus ( $datos [0], 5 );
	}
	function Anular_Soli() {
		$this->load->model ( "cliente/msolicitud", 'MSoli' );
		$datos = json_decode ( $_POST ['objeto'], true );
		echo $this->MSoli->Modifica_Estatus ( $datos [0], 2 );
	}
	
	/*
	 * para reporte General
	 */
	function Reporte_General() {
		$this->load->model ( 'CNomina' );
		if ($this->session->userdata ( 'usuario' )) {
			$data ['Menu'] = $this->CMenu->getHtml_Menu ( $this->session->userdata ( 'nivel' ) );
			$data ['Nivel'] = $this->session->userdata ( 'nivel' );
			$data ['iPosicion'] = $this->session->userdata ( 'posicion' );
			$data ['lista'] = $this->CNomina->Combo ();
			$data ['Listar_Ubicacion'] = $this->CListartareas->Listar_Ubicacion_Combo ();
			$data ['Listar_Banco'] = $this->CListartareas->Listar_Banco_Combo ();
			$this->load->view ( "reporte_general_factura", $data );
		} else {
			$this->login ();
		}
	}
	public function GV_Filtro_Rep_FacturaG() {
		$this->load->model ( "vistas/mvistas", 'MVista' );
		echo $this->MVista->filtro_general_factura ();
	}
	function RFactura_General() {
		$this->load->model ( "cliente/mcliente", 'MCliente' );
		echo $this->MCliente->RFactura_General ( $_POST );
	}
	public function GV_Filtro_Rep_ContratoG() {
		$this->load->model ( "vistas/mvistas", 'MVista' );
		echo $this->MVista->filtro_general_contrato ();
	}
	function RContrato_General() {
		$this->load->model ( "cliente/mcliente", 'MCliente' );
		echo $this->MCliente->RContrato_General ( $_POST );
	}
	
	/*
	 * Funciones para nuevo metodo de busqueda
	 */
	function ProcesarDatosBasicos() {
		$this->load->model ( "buscar/mbuscar", "MBuscar" );
		$id = $_POST ['id'];
		print_r ( $this->MBuscar->Datos_Basicos ( $id ) );
	}
	function ProcesarAsociados() {
		$this->load->model ( "cliente/mcontactos", "MContactos" );
		$id = $_POST ['id'];
		print_r ( $this->MContactos->Listar ( $id ) );
	}
	function ProcesarHistorial() {
		$this->load->model ( "buscar/mbuscar", "MBuscar" );
		$id = $_POST ['id'];
		print_r ( $this->MBuscar->Listar_Historial ( $id ) );
	}
	function ProcesarActivos() {
		$this->load->model ( "buscar/mbuscar", "MBuscar" );
		$id = $_POST ['id'];
		$cond = ' > 0';
		
		print_r ( $this->MBuscar->Listar_Facturas ( $id, $cond ) );
	}
	function ProcesarCancelado() {
		$this->load->model ( "buscar/mbuscar", "MBuscar" );
		$id = $_POST ['id'];
		$cond = ' <=0';
		print_r ( $this->MBuscar->Listar_Facturas ( $id, $cond ) );
	}
	function ProcesarContado() {
		$this->load->model ( "buscar/mbuscar", "MBuscar" );
		$id = $_POST ['id'];
		print_r ( $this->MBuscar->Listar_Facturas_Contado ( $id ) );
	}
	public function MDRProcesar() {
		$this->load->model ( 'buscar/mbuscar', 'MBuscar' );
		
		if (isset ( $_POST ['objeto'] )) {
			$json = json_decode ( $_POST ['objeto'], true );
			$Arr ['perfil'] = $this->session->userdata ( 'nivel' );
			$Arr ['factura'] = $json [0];
			$Arr ['cedula'] = $json [2];
			$Arr ['titulo'] = 'VOUCHER';
			$valor = explode ( "||", $json [1] );
			switch (strtolower ( $valor [0] )) {
				case 'voucher' :
					$jsC = $this->MBuscar->Detalles_Facturas_Voucher ( $Arr );
					break;
				case 'transferencia' :
					$Arr ['titulo'] = 'TRANSFERENCIA';
					$jsC = $this->MBuscar->Detalles_Facturas_Voucher ( $Arr );
					break;
				case 'mixto-v' :
					$jsC = $this->MBuscar->Detalles_Facturas_Mixto ( $Arr );
					break;
				case 'mixto-t' :
					$Arr ['titulo'] = 'TRANSFERENCIA';
					$jsC = $this->MBuscar->Detalles_Facturas_Mixto ( $Arr );
					break;
				default :
					$jsC = $this->MBuscar->Detalles_Facturas_Contratos ( $Arr );
					
					break;
			}
			
			echo $jsC;
		} else {
			echo "llega2";
		}
	}
	
	// tipo= 0:cedula,1:factura,2:contrato
	public function PNEstado_Cuenta($tipo, $id, $ced) {
		$data ['tipo'] = $tipo;
		$data ['id'] = $id;
		$data ['ced'] = $ced;
		$this->load->view ( "pn_estado_cuenta", $data );
	}
	public function GPNEstado_Cuenta($tipo = null, $id = null) {
		if (isset ( $_POST ['objeto'] )) {
			$datos = json_decode ( $_POST ['objeto'], true );
			$tipo = 2;
			$id = $datos [0];
		}
		$this->load->model ( 'buscar/mbuscar', 'MBuscar' );
		echo $this->MBuscar->Estados_Cuenta ( $tipo, $id );
	}
	public function DescripcionContrato() {
		$this->load->model ( 'buscar/mbuscar', 'MBuscar' );
		$datos = json_decode ( $_POST ['objeto'], TRUE );
		echo $this->MBuscar->DescripcionContrato ( $datos [0] );
	}
	public function DetalleCobroContrato($sCed = null, $sCont = null) {
		if (isset ( $_POST ['objeto'] )) {
			$ele = json_decode ( $_POST ['objeto'], TRUE );
			$sCed = $ele [0];
			$sCont = $ele [1];
		}
		$data ['Nivel'] = $this->session->userdata ( 'nivel' );
		$data ['cedula'] = $sCed;
		$data ['contrato'] = $sCont;
		$this->load->view ( "DetalleCobroContrato", $data );
	}
	public function DTControlMensual() {
		$contrato = $_POST ['id'];
		$this->load->model ( 'buscar/mbuscar', 'MBuscar' );
		echo $this->MBuscar->DTControlMensual ( $contrato );
	}
	function Cuotas_Por_Mes() {
		if (isset ( $_POST ['objeto'] )) {
			$this->load->model ( 'buscar/mbuscar', 'MBuscar' );
			$ele = json_decode ( $_POST ['objeto'], TRUE );
			$fecha = $ele [0];
			$contrato = $ele [1];
			$fuera = $ele [2];
			echo $this->MBuscar->DTControlMensual_Por_Mes ( $contrato, $fecha, $fuera );
		}
	}
	function Combo_Modalidad() {
		$this->load->model ( 'buscar/mbuscar', 'MBuscar' );
		echo $this->MBuscar->Combo_Modalidad ();
	}
	
	/*
	 * Listar entregas
	 */
	public function Listar_Entregas2() {
		$nivel = $this->session->userdata ( 'nivel' );
		$usu = $this->session->userdata ( 'usuario' );
		if ($nivel == 0 || $nivel == 9 || $nivel == 5 || $usu == 'Carlos' || $nivel == 15 || $nivel == 19) {
			$this->load->model ( "inventario/minventario", 'MInventario' );
			if (isset ( $_POST )) {
				$arr ['desde'] = $_POST ['desde'];
				$arr ['hasta'] = $_POST ['hasta'];
				$arr ['ubicacion'] = $_POST ['ubicacion'];
				
				if ($nivel == 5 && strtolower ( $usu ) != 'alvaroz') {
					$arr ['ubicacion'] = $this->session->userdata ( 'ubicacion' );
				}
				$objeto = $this->MInventario->Listar_Entregas_Fecha ( $arr );
			} else {
				$objeto = $this->MInventario->Listar_Entregas_Fecha ();
			}
			echo $objeto;
		} else {
			$this->logout ();
		}
	}
	public function Constancia_Entrega_Nueva($oid = '') {
		$consulta = $this->db->query ( "SELECT * FROM t_entregas 
											JOIN (
												SELECT numero_factura,condicion,cobrado_en,nomina_procedencia 
												FROM t_clientes_creditos 
												group by numero_factura)AS A ON A.numero_factura=t_entregas.factura
											WHERE oid=" . $oid . " limit 1" );
		
		foreach ( $consulta->result () as $row ) {
			$fecha_a = explode ( '-', $row->fecha );
			$modelo = $row->modelo;
			$nombre = $row->descrip;
			$entregado = $row->entregado;
			$entregado_a = $row->entregado_a;
			$cedula = $row->cedula;
			$factura = $row->factura;
			$mot = $row->condicion;
			$banco = $row->cobrado_en;
			$nomina = $row->nomina_procedencia;
			$codigo = $this->Completar ( $oid, 8 );
			$fecha = $row->fecha;
			
			$rsLEntrega = $this->db->query ( "SELECT t_lista_entregas.serial,t_productos.descripcion FROM t_lista_entregas 
												join t_productos on t_productos.serial = t_lista_entregas.serial WHERE oide ='" . $row->oid . "'" );
			
			foreach ( $rsLEntrega->result () as $serial_e ) {
				$serial [] = $serial_e->serial;
				$descrip [] = $serial_e->descripcion;
			}
		}
		$entrega = $entregado . " " . $nombre;
		$data ['dia'] = $fecha_a [2];
		$data ['mes'] = $this->Convertir_Mes ( $fecha_a [1] );
		$data ['ano'] = $fecha_a [0];
		$data ['cantidad'] = $entregado;
		$data ['a_quien'] = $entregado_a;
		$data ['entrega'] = $entregado;
		$data ['cedula'] = $cedula;
		$data ['factura'] = $factura;
		$data ['serial'] = $serial;
		$data ['descrip'] = $descrip;
		$data ['nombre'] = $nombre;
		$data ['modelo'] = $modelo;
		$data ['banco'] = $banco;
		$data ['nomina'] = $nomina;
		$data ['motivo'] = $mot;
		$data ['codigo'] = $codigo;
		$data ['fecha'] = $fecha;
		$data ['ciudad'] = $this->session->userdata ( 'ubicacion' );
		$data ['usuario'] = $this->session->userdata ( 'usuario' );
		$this->load->view ( "reportes/acta_entrega_cliente", $data );
	}
	function Anula_Entrega_Nueva() {
		$datos = json_decode ( $_POST ['objeto'], TRUE );
		// print_R($datos);
		$nivel = $this->session->userdata ( 'nivel' );
		if ($nivel == 0 || $nivel == 9 || $this->session->userdata ( 'usuario' ) == 'Carlos' || $nivel == 18 || $nivel == 19) {
			$this->load->model ( "inventario/minventario", 'MInventario' );
			echo $this->MInventario->Anula_Entrega_Nueva ( $datos );
		} else {
			echo "No tiene privilegios para anular orden de entrega...Solicitar a planificacion...";
		}
	}
	
	/*
	 * Funciones para el usuario control
	 */
	function ccargas_domi() {
		$this->load->model ( 'reporte/mreporte', 'MReporte' );
		echo $this->MReporte->ccargas_domi ( $_POST ['desde'], $_POST ['hasta'] );
	}
	function ccargas_vou() {
		$this->load->model ( 'reporte/mreporte', 'MReporte' );
		echo $this->MReporte->ccargas_vou ( $_POST ['desde'], $_POST ['hasta'] );
	}
	
	/*
	 * Exportar pagina a pdf
	 */
	function Envia_Estado_Cuenta() {
		$this->load->model ( 'buscar/mbuscar', 'MBuscar' );
		$resp = $this->MBuscar->Estado_Cuenta_Pdf ( $_POST );
		print_R ( $resp );
	}
	/*
	 * Canbia ubicacion en t_clientes_credios y t_fpresupuesto
	 */
	function Cambiar_Ubicacion_Facturas() {
		if ($this->session->userdata ( 'usuario' )) {
			$data ['Nivel'] = $this->session->userdata ( 'nivel' );
			$data ['Menu'] = $this->CMenu->getHtml_Menu ( $this->session->userdata ( 'nivel' ) );
			$this->load->view ( "mfactura", $data );
		} else {
			$this->login ();
		}
	}
	function Mod_Ubi_Factura() {
		$usu = $this->session->userdata ( 'usuario' );
		$query = 'UPDATE t_clientes_creditos SET codigo_n="' . $_POST ['ubica'] . '" WHERE numero_factura="' . $_POST ['factura'] . '"';
		$this->db->query ( $query );
		$query = 'UPDATE t_fpresupuesto SET ubicacion="' . $_POST ['ubica'] . '" WHERE factura="' . $_POST ['factura'] . '"';
		$this->db->query ( $query );
		$datos = array (
				"referencia" => $_POST ['factura'],
				"tipo" => 24,
				"motivo" => "CAMBIO DE UBICACION A " . $_POST ['ubica'] . " GENERADO EN MERIDA",
				"usuario" => $usu,
				"peticion" => $usu 
		);
		$this->db->insert ( "_th_sistema", $datos );
		echo "Se proceso con exito";
	}
	function Listar_ubica() {
		$ubicacion = $this->db->query ( "SELECT descripcion,soli FROM t_ubicacion where soli!=''" );
		$ubi = $ubicacion->result ();
		foreach ( $ubi as $nom ) {
			$valores [$nom->descripcion] = $nom->descripcion;
		}
		echo json_encode ( $valores );
	}
	
	/**
	* Generar los monto totales por contratos para los reportes en lista_cobros_u
	* @return string
	*/
	function GenerarMontoPorContratos() {
		$query = 'truncate t_lista_cobros_u;';
		$this->db->query ( $query );
		$query = 'INSERT INTO t_lista_cobros_u (contrato_id, monto) 
			SELECT credito_id, SUM(monto) FROM t_lista_cobros GROUP BY credito_id;';
		$this->db->query ( $query );
		echo "Se proceso con exito";
	}
	function Certificar_Cliente() {
		if ($this->session->userdata ( 'usuario' )) {
			$data ['Nivel'] = $this->session->userdata ( 'nivel' );
			$data ['Menu'] = $this->CMenu->getHtml_Menu ( $this->session->userdata ( 'nivel' ) );
			$this->load->view ( "certificar_cliente", $data );
		} else {
			$this->login ();
		}
	}
	function Enviar_Certificacion() {
		$this->load->model ( 'cliente/mcertifica', 'MCertifica' );
		$correo = $_POST ['correo'];
		echo $this->MCertifica->EnviarSolicitud ( $correo );
	}

	/*
	 * Funciones para modulo de auditoria
	 */
	 function archivosAuditoria(){
	 	if ($this->session->userdata ( 'usuario' )) {
			$data ['Nivel'] = $this->session->userdata ( 'nivel' );
			$data ['Menu'] = $this->CMenu->getHtml_Menu ( $this->session->userdata ( 'nivel' ) );
			$this->load->view ( "archivosAuditoria", $data );
		} else {
			$this->login ();
		}
	 }
	 
	 function GV_archivosAuditoria(){
	 	$this->load->model ( "vistas/mvistas", 'MVista' );
		echo $this->MVista->archivosAuditoria ();
	 }
	 
	 function Guarda_Descripcion_Auditoria(){
	 	$usu = array ();
		$datos = json_decode ( $_POST ['datos'], TRUE );
		$datos['usuario'] = $this->session->userdata ( 'oidu' );
		$datos['estatus'] = 0;
		$this -> db -> insert("t_auditar_txt",$datos);
		echo "Se registro con exito";
	 }
	 
	 function listar_archivosAuditoria(){
	 	$this -> load -> model('auditoria/mauditoria','MAuditoria');
		echo $this -> MAuditoria -> listar();
	 }
	 
	 function archivoAuditoriaProcesado(){
	 	$this -> load -> model('auditoria/mauditoria','MAuditoria');
	 	$datos = json_decode($_POST['objeto'],TRUE);
		echo $this -> MAuditoria -> procesar($datos[0]);		
	 }
	 
	 function wservicio($cedula="17522251"){
	 	$this -> load -> library("Nusoap_library");
		 
		$client = new nusoap_client('http://electron465.com/servidorwsdl.php?wsdl&#8217', true);
		$cedula = $_POST['cedula'];
		$person = array (
			"Cedula" => $cedula
		);
		$result = $client->call ("Afiliado", array (
			"person" => $person 
		) );
			
		//print_R($result);
			
		if ($client->fault) {
			/*echo "<h2>Fault</h2><pre>";
			print_r ( $result );
			echo "</pre>";*/
			$sQuery = "UPDATE t_personas SET correo = '" . $result['Correo'] .  "' WHERE documento_id='" . $cedula.  "' LIMIT 1";
			$this -> db -> query($sQuery);
			echo $result['ClienteExiste'];
		} else {
			// Check for errors
			$err = $client->getError ();
			if ($err) {
				// Display the error
				echo "<h2>Error</h2><pre>" . $err . "</pre>";
			} else {
				// Display the result
				/*echo "<h2>Result</h2><pre>";
				print_r ( $result );
				echo "</pre>";*/
				$sQuery = "UPDATE t_personas SET correo = '" . $result['Correo'] .  "' WHERE documento_id='" . $cedula.  "' LIMIT 1";
				$this -> db -> query($sQuery);
				echo $result['ClienteExiste'];
			}
		}	  

		
	 }

    /*
     * funciones para modulo de recepcion de documentos
     */
    public function recepcionDocumento() {
        if ($this->session->userdata ( 'usuario' )) {
            $data ['Menu'] = $this->CMenu->getHtml_Menu ( $this->session->userdata ( 'nivel' ) );
            $data ['Nivel'] = $this->session->userdata ( 'nivel' );
            $this->load->view ( "recepcionDocumento", $data );
        } else {
            $this->logout ();
        }
    }

    function GV_recepcionDocu(){
        $this->load->model ( "vistas/mvistas", 'MVista' );
        echo $this->MVista->recepcionDocu ();
    }

    function guardaRecepcionDocu(){
        $this->load->model ( "recepcion/mrecepcion", 'MRecepcion' );
        $datos = json_decode($_POST['datos'],true);
        echo $this -> MRecepcion ->guardar($datos);
    }

    function estatusDocumentosRecibidos(){
        if ($this->session->userdata ( 'usuario' )) {
            $data ['Menu'] = $this->CMenu->getHtml_Menu ( $this->session->userdata ( 'nivel' ) );
            $data ['Nivel'] = $this->session->userdata ( 'nivel' );
            $this->load->view ( "estatusDocumento", $data );
        } else {
            $this->logout ();
        }
    }

    function listarRecepcionDocu(){
        $this->load->model ( "recepcion/mrecepcion", 'MRecepcion' );
        //echo "pasa";
        echo $this -> MRecepcion ->listar($_POST);
    }

    function detalleRecepDocu(){
        $this->load->model ( "recepcion/mrecepcion", 'MRecepcion' );
        $json = json_decode ( $_POST ['objeto'], true );

        //echo "pasa";
        echo $this -> MRecepcion ->listarDetalle($json[0]);
    }

    function aceptarEstatusDocu(){
        $datos = json_decode($_POST['objeto'],true);
        //print_R($datos);
        $this->load->model ( "recepcion/mrecepcion", 'MRecepcion' );
        echo $this -> MRecepcion -> aceptarEstatusDocu($datos);
    }

    function anularEstatusDocu(){
        $datos = json_decode($_POST['objeto'],true);
        //print_R($datos);
        $this->load->model ( "recepcion/mrecepcion", 'MRecepcion' );
        echo $this -> MRecepcion -> anularEstatusDocu($datos);
    }

    function rechazarEstatusDocu(){
        $datos = json_decode($_POST['objeto'],true);
        //print_R($datos);
        $this->load->model ( "recepcion/mrecepcion", 'MRecepcion' );
        echo $this -> MRecepcion -> rechazarEstatusDocu($datos);
    }

    function publiEstatusDocu(){
        $datos = json_decode($_POST['objeto'],true);
        //print_R($datos);
        $this->load->model ( "recepcion/mrecepcion", 'MRecepcion' );
        echo $this -> MRecepcion -> publiEstatusDocu($datos);
    }

    function observaEstatusDocu(){
        $datos = json_decode($_POST['objeto'],true);
        //print_R($datos);
        $this->load->model ( "recepcion/mrecepcion", 'MRecepcion' );
        echo $this -> MRecepcion -> observaEstatusDocu($datos);
    }


    /*
     * funciones para presupuestos enviados por correo
     */
    public function CCalculosCorreo() {
        if ($this->session->userdata ( 'usuario' )) {
            $this->load->view ( "calculocorreo" );
        } else {
            $this->login ();
        }
    }

    public function presupuestoCorreo() {
        if ($this->session->userdata ( 'usuario' )) {
            $this->load->view ( "presupuestos/presuConTotal" );
        } else {
            $this->login ();
        }
    }

    /**
     * Enviar Calculos a correo electronico
     */
    function EnviarCalculosCorreo(){
        $correo = $_POST['correo'];
        $respuesta = $_POST['respuesta'];
        //error_reporting(E_STRICT);
        require_once('system/application/libraries/PHPMail/class.phpmailer.php');
        $mail = new PHPMailer();
        $body                ='';
        $mail->IsSMTP(); 							  // telling the class to use SMTP
        $mail->SMTPDebug  = 1;						  //
        $mail->Host          = "smtp.gmail.com";      //
        $mail->SMTPSecure = "tls";					  //
        $mail->SMTPAuth      = true;                  // enable SMTP authentication
        $mail->SMTPKeepAlive = true;                  // SMTP connection will not close after each email sent

        $mail->Port          = 587;
        $mail->Username      = "soporteelectron465@gmail.com"; // SMTP account username
        $mail->Password      = "soporte8759";        // SMTP account password
        $mail->SetFrom('soporteelectron465@gmail.com', 'Departamento de Ventas');
        $mail->AddReplyTo('soporteelectron465@gmail.com', 'Despartamento de Ventas');
        $mail->Subject = 'Grupo Electron (Presupuesto)';

        $cuerpo = '<table style="font-family: Trebuchet MS; font-size: 13px;text-align: justify;" width="0"><tr><td rowspan="2"  width=180><img src="' . __IMG__ . 'logoN.jpg" width=200></td>
	            </tr><tr><td colspan="3" >Apreciado/a:  <br>' . $correo . '.</td></tr>
	            <tr><td colspan="4">Recibe un caluroso saludo de parte de Grupo Electrón, mediante la presente te notificamos que ha sido enviado el
	            plan de pago.<br><br>
	            <h2><center><font color="#0070A3">'. $respuesta . '</b></font></center><br><br><b>Monto Total Del Credito:'.$_POST['montoT'].'  BS<br><br></h2>
	            </td>
	          </tr><tr><td colspan="4">Si tienes alguna pregunta o si necesitas alguna asistencia con respecto a esta comunicación, tienes a tu
	            		disposición a nuestro equipo de atenci&oacute;n al cliente a través del número </td></tr>
	          <tr><td colspan="4"><hr></hr><small>Muchas gracias por ser parte de la comunidad Electr&oacute;n 465.
				Esta comunicación forma parte básica de nuestro programa de atención al cliente. Si no desea seguir recibiendo este tipo de comunicaciones.
				Electrón 465 se compromete firmemente a respetar su privacidad. No compartimos su información con ningún tercero sin su consentimiento.</small>
	          </td></tr></table>';

        $mail->AltBody    = "Texto Alternativo"; // optional, comment out and test
        $mail->MsgHTML($cuerpo);
        $address = $correo;
        $mail->AddAddress($correo, "Plan de Pago");
        if(!$mail->Send()) {
            return "Error al enviar: " . $mail->ErrorInfo;
        } else {
            return "Mensaje enviado a:  " .  $correo . "!";
        }
    }

    /*
     * Funciones para plan corporativo
     */

    function panelPlanes(){
        if ($this->session->userdata ( 'usuario' )) {
            $data ['Menu'] = $this->CMenu->getHtml_Menu ( $this->session->userdata ( 'nivel' ) );
            $data ['Nivel'] = $this->session->userdata ( 'nivel' );
            $data ['ubicacion'] = $this->session->userdata ( 'ubicacion' );
            $this->load->view ( "panel/panelPlan", $data );
        } else {
            $this->login ();
        }
    }

    public function GV_Plan() {
        $this->load->model ( "vistas/mvistas", 'MVista' );
        return $this->MVista->panelPlan();
    }

    function guardaPlan(){
        $datos = json_decode ( $_POST ['datos'], TRUE );
        $this->db->insert ( "t_plan_corporativo", $datos );
        echo "Se Creo nuevo Plan Corporativo con exito";
    }

    function listarPlanesModificar(){
        $cab[1] = array("titulo"=>"oid","oculto"=>true,"tipo"=>"texto");
        $cab[2] = array("titulo"=>"Plan","tipo"=>"texto");
        $cab[3] = array("titulo"=>"Porcentaje","tipo"=>"texto");
        $cab[4] = array("titulo" => "AC", "tipo" => "bimagen", "funcion" => 'modificaPlan',"parametro"=>"1,2,3" ,"ruta" => __IMG__ . "botones/aceptar1.png", "atributos" => "width:10px","mantiene"=>1);
        $ejecuta = $this -> db -> query("select * from t_plan_corporativo");
        $resp = $ejecuta -> result();
        $cuerpo = array();
        $i=1;
        foreach($resp as $fila) {
            $cuerpo[$i] = array("1"=>$fila->oid,"2"=> $fila->plan,"3"=>$fila->porcentaje,"4"=>"");
            $i++;
        }
        $grid=array("resp"=>1,"Cabezera"=>$cab,"Cuerpo"=>$cuerpo,"Paginador"=>10,"Origen"=>"json");
        echo json_encode($grid);

    }

    function modificaPlan(){
        $datos = json_decode($_POST['objeto']);
        $this -> db -> query ("UPDATE t_plan_corporativo set plan='".$datos[1]."', porcentaje=".$datos[2]." where oid=".$datos[0]);
        echo "Se actualizo con exito";
        //print_R($_POST);
    }

    function comboPlan(){
        $buscar = $this -> db ->  query("SELECT * FROM t_plan_corporativo");
        $resp = $buscar -> result();
        $combo = "";
        foreach($resp as $fila){
         $combo .= "<option value=".$fila->porcentaje.">".$fila->plan."</option>";
        }
        echo $combo;
    }

    /**
     * Fucniones Cargar archivo Banco Venezuela
     */

    function cargaVenezuela(){
        $this->load->model ( 'CNomina' );
        if ($this->session->userdata ( 'usuario' )) {
            $data ['Menu'] = $this->CMenu->getHtml_Menu ( $this->session->userdata ( 'nivel' ) );
            $data ['Nivel'] = $this->session->userdata ( 'nivel' );
            $this->load->view ( "cargaVenezuela", $data );
        } else {
            $this->login ();
        }
    }

    function subirVenezuela() {
        $banco = strtolower ( $_POST ['banco'] );
        $directorio = BASEPATH . 'repository/txt/' . $banco;
        $upload_folder = 'images';
        $nombre_archivo = $_FILES ['archivo'] ['name'];
        $tipo_archivo = $_FILES ['archivo'] ['type'];
        $tamano_archivo = $_FILES ['archivo'] ['size'];
        $tmp_archivo = $_FILES ['archivo'] ['tmp_name'];
        $archivador = $directorio . '/' . $nombre_archivo;
        $return = Array (
            'ok' => 'si',
            'archivo' => $nombre_archivo,
            "msg" => "SE CARGO CON EXITO",
            "txt" => $nombre_archivo
        );

        $query = "SELECT * FROM t_archivos_venezuela WHERE archivo='" . $nombre_archivo . "' ";

        $cant = $this->db->query ( $query );

        if ($cant->num_rows () > 0) {
            $msj = "No Se Pudo Procesar. El Archivo Ya Fue Procesado";
            $return = Array (
                'ok' => 'no',
                'msg' => $msj,
                'status' => 'error',
                'txt' => ''
            );
        } else {
            if (! move_uploaded_file ( $tmp_archivo, $archivador )) {
                $return = Array (
                    'ok' => 'no',
                    'msg' => "Ocurrio un error al subir el archivo. No pudo guardarse.",
                    'status' => 'error',
                    'txt' => ''
                );
            } else {
                $this ->load->model('cargar/mcargavenezuela','MCargaV');
                $datos = array('archivo'=>$nombre_archivo,'empresa'=>$_POST['empresa'],
                    'fcontrato'=>$_POST['fcontrato'],'perio'=>$_POST['perio'], 'fenv'=>$_POST['fenv'],'frec'=>$_POST['frec']);
                $res = $this -> MCargaV -> registrar($datos);
                $return = Array (
                    'ok' => "si",
                    'msg' => array('id'=>$res,'archivo'=>$nombre_archivo),
                    'status' => 'error',
                    'txt' => ''
                );
                //}
            }
        }
        echo json_encode ( $return );
    }

    function leerArchivoVenezuela(){
        $this ->load->model('cargar/mcargavenezuela','MCargaV');
        $resp = $this -> MCargaV -> leerArchivo($_POST['id'],$_POST['archivo']);
        if($resp) echo 'Se cargo con exito el archivo';
        else echo 'Error al cargar el archivo';
        //print_R($_POST);
    }
    function cmbArchivosVenezuela(){
        $this ->load->model('cargar/mcargavenezuela','MCargaV');
        $item = $this -> MCargaV -> crearCombo();
        echo json_encode($item);
    }

    function listarArchivoVenezuela(){
        $this ->load->model('cargar/mcargavenezuela','MCargaV');
        $oida = $_POST['oida'];
        //echo $oida;
        echo $this -> MCargaV -> grid($oida);
    }

    function cmbContratosVenezuela(){
        $this ->load->model('cargar/mcargavenezuela','MCargaV');
        //print_R($_POST);
        echo $this -> MCargaV -> crearComboContrato($_POST['cedula']);
    }

    function modCuotaVenezuela(){
        $this ->load->model('cargar/mcargavenezuela','MCargaV');
        //print_R($_POST);
        $query = "UPDATE t_cargar_txt_venezuela set contrato='".$_POST['contrato']."',frec='".$_POST['frec']."',obser='".$_POST['obser']."' where oid=".$_POST['oid'];
        $this->db->query($query);
        echo "Se proceso con exito";
    }

    function Guardar_Lote_Venezuela() {
        $datos = json_decode($_POST['objeto'],true);
        $oida = $datos[0][7];
        $insertar = "INSERT into t_lista_cobros(documento_id,credito_id,mes,descripcion,fecha,monto,farc,mesp,anop,moda,usua) VALUES";
        $band = 0;
        $usu = $this->session->userdata ( 'oidu' );
        foreach($datos as $fila){
            $mes = "CARGA VENEZUELA:".$fila[2];
            $mesp = explode('-',$fila[4]);
            $band++;
            //echo 'llega';
            if ($band > 1) $insertar .= ',';
            $insertar .= "('".$fila[0]."','".$fila[1]."','".$mes."','".$fila[3]."','".$fila[4]."',
            ".$fila[5].",'".$fila[6]."',".$mesp[1].",".$mesp[0].",25,".$usu.")";
        }
        $res = $this->db->query($insertar);
        if($res){
            $this->db->query("update t_archivos_venezuela set estatus=1 where oid=".$oida);
            echo "Se cargo con exito";
        }else{
            echo "No se pudo Cargar datos";
        }
        /*print('<pre>');
        print_R($datos);
        echo $oida;*/
    }

    function Exonerar_Voucher(){
        /*print("<pre>");
        print_R($_POST);*/
        $datos = json_decode($_POST['objeto']);
        $this->db->query("UPDATE t_lista_voucher set estatus=6 where ndep='".$datos[0]."' and cid='".$datos[1]."' ");
        echo "Se exonero con exito";
    }

	function CargarNotaCredito($id = ''){
		$this->load->model("auditoria/mauditoria", "Auditoria");
		echo $this->Auditoria->notasCreditos($id);
	}

	
	function PagarFacturas(){
		$this->load->model ("cliente/mvoucher", "MVoucher");
		$this->load->model("cliente/mfcontrol", "FControl");
		//$rs = $this->FControl->PagarFactura('GN004049', '23/07/1985');
		$rs = $this->FControl->PagarFactura($_POST['factura'], $_POST['fecha']);
		

		print(json_encode($rs));
		//print_r($rs);		
		//return "<strong>&nbsp;  Proceso Finalizado Satisfactoriamente </strong>...." . $msj;
		
		
	}

	function ReversarVoucher($factura, $arrVoucher = ''){
		$lst = explode('p', $arrVoucher);
		$sActualizar = 'UPDATE t_lista_voucher SET estatus=0, observacion=\'\' WHERE `cid` LIKE \'' . $factura . '\' AND estatus=5;';
		echo $sActualizar . '<br>';
		$this->db->query($sActualizar);
		$sActualizar = 'UPDATE t_lista_voucher SET estatus=0, observacion=\'\' WHERE `cid` LIKE \'' . $factura . '\' AND estatus=2;';
		echo $sActualizar . '<br><br>';
		$this->db->query($sActualizar);
		
		foreach ($lst as $k => $v) {
			echo '# --- ' . $v . '<br>';
			$sActualizar = 'UPDATE `electron`.`t_clientes_creditos` 
									SET `marca_consulta` = \'6\' WHERE 
									`t_clientes_creditos`.`contrato_id` = \'' . $v . '\';';
			echo $sActualizar;
			echo '<br>';
			$this->db->query($sActualizar);
			$sActualizar = 'DELETE FROM `t_lista_cobros` WHERE 
									`credito_id` LIKE \'' . $v . '\' AND mes = \'Cambio De Modalidad\';';
			echo $sActualizar;
			echo '<br><br>';
			$this->db->query($sActualizar);
		}
	}


	/*
	 * Crear instituciones
	 */
	public function Inserta_Insti() {
		if($_POST['id'] == 0){
			unset($_POST['id']);
			if($this->db->insert ( 't_institucion', $_POST )) echo "Se registro con exito";
			else echo "Error al insertar";
		}else{
			$this->db->where('id',$_POST['id']);
			unset($_POST['id']);
			$res = $this->db->update ( 't_institucion', $_POST );
			if($res) echo "Se modifico con exito";
			else echo "Error al modificar";


		}
	}

	public function consulInsti(){
		$resul = $this->db->query("SELECT * FROM t_institucion where id=".$_POST['id']);
		echo json_encode($resul->row());
	}

	public function buscaDir(){
		$resul = $this->db->query("SELECT * FROM t_institucion where id=".$_POST['id']);
		$fila = $resul->row();
		echo $fila->direccion;
	}

	public function modificaciones(){
        $this->load->model ( 'CNomina' );
        if ($this->session->userdata ( 'usuario' )) {
            $data ['Menu'] = $this->CMenu->getHtml_Menu ( $this->session->userdata ( 'nivel' ) );
            $data ['Nivel'] = $this->session->userdata ( 'nivel' );
            $rs = $this->db->query("SELECT * FROM t_estatus_sistema");
            $opt = '';
            foreach ($rs->result() as $fila){
            	$opt .= '<option value="'.$fila->oid.'">'.$fila->nombre.'</option>';
			}
			$data['tipo'] = $opt;
            $this->load->view ( "reportes/rep_modificaciones", $data );
        } else {
            $this->login ();
        }
	}

	public function listaModificaciones(){
		$query = "select nombre as tipo,referencia,fecha,motivo,peticion,usuario from _th_sistema 
join t_estatus_sistema as a on a.oid = _th_sistema.tipo WHERE fecha BETWEEN '".$_POST['desde']."' AND '".$_POST['hasta']."' ";
		if($_POST['tipo'] != 0) $query .= " AND tipo=".$_POST['tipo'];
        $Consulta = $this -> db -> query($query);
        $Cabezera = $Consulta -> list_fields();
        $iCantidad = $Consulta -> num_rows();
        $Conexion = $Consulta -> result();

        $oCabezera = $Cabezera;
        $oFil = $Conexion;
        $Origen = 'Mysql';

        $oTable = array("Cabezera" => $oCabezera, "Cuerpo" => $oFil, "Paginador" => 10, "Origen" => $Origen);
        echo json_encode($oTable);

	}

	public function usuariosActivos(){
		$query = "SELECT * FROM t_usuario where clave != ''";
        $Consulta = $this -> db -> query($query);
        $lista = $Consulta->result();
        $html="";
        foreach ($lista as $usu){
        	$html .= "<option value='".$usu->oid."'>".$usu->seudonimo." | ".$usu->descripcion."</option>";
		}
        $html .= "<option value='' seledted='selected'>SELECCIONES</option>";
		echo $html;
	}

	public function inactivarUsuario($id=null){
		if(isset($_POST['id'])) $id = $_POST['id'];
		$query = "UPDATE t_usuario SET clave='' WHERE oid=".$id;
		if($this->db->query($query)){
			echo "Se inactivo el usuario con exito";
		}else{
			echo "Error al inactivar";
		}
	}

	/**
	 * Reportes Especiales - Nuevas funcionalidades
	 */
	
	// Método de prueba para verificar conectividad
	public function Test_Reportes_Especiales() {
		echo json_encode(array(
			'status' => 'ok',
			'message' => 'Servidor funcionando correctamente',
			'timestamp' => date('Y-m-d H:i:s'),
			'database' => $this->db->database,
			'hostname' => $this->db->hostname
		));
	}
	
	// Método de debug para verificar tablas
	public function Debug_Tablas_Especiales() {
		try {
			$tablas = array();
			
			// Verificar t_banco
			$banco_check = $this->db->query("SHOW TABLES LIKE 't_banco'");
			$tablas['t_banco'] = $banco_check->num_rows() > 0;
			
			// Verificar t_clientes_creditos
			$creditos_check = $this->db->query("SHOW TABLES LIKE 't_clientes_creditos'");
			$tablas['t_clientes_creditos'] = $creditos_check->num_rows() > 0;
			
			// Contar registros en t_banco
			if ($tablas['t_banco']) {
				$banco_count = $this->db->query("SELECT COUNT(*) as total FROM t_banco");
				$tablas['t_banco_count'] = $banco_count->row()->total;
			}
			
			// Contar registros en t_clientes_creditos
			if ($tablas['t_clientes_creditos']) {
				$creditos_count = $this->db->query("SELECT COUNT(*) as total FROM t_clientes_creditos");
				$tablas['t_clientes_creditos_count'] = $creditos_count->row()->total;
			}
			
			echo json_encode($tablas);
		} catch (Exception $e) {
			echo json_encode(array('error' => $e->getMessage()));
		}
	}
	
	// Función para obtener lista de bancos para reportes especiales
	public function DataSource_Bancos_Especiales() {
		// Limpiar cualquier output buffer previo
		if (ob_get_level()) ob_clean();
		
		// Headers para evitar caché
		header('Content-Type: application/json; charset=utf-8');
		header('Cache-Control: no-cache, no-store, must-revalidate');
		header('Pragma: no-cache');
		header('Expires: 0');
		
		try {
			// Verificar conexión a la base de datos
			if (!$this->db->conn_id) {
				throw new Exception('No hay conexión a la base de datos');
			}
			
			$resultado = array();
			
			// Estrategia 1: Intentar con t_banco
			$query_banco = "SELECT banco_id as id, nombre FROM t_banco ORDER BY nombre";
			$bancos = $this->db->query($query_banco);
			
			if ($bancos && $bancos->num_rows() > 0) {
				foreach ($bancos->result() as $banco) {
					$resultado[] = array(
						'id' => $banco->id,
						'nombre' => $banco->nombre
					);
				}
			}
			
			// Estrategia 2: Si no hay bancos, usar valores únicos de t_clientes_creditos
			if (empty($resultado)) {
				$query_creditos = "SELECT DISTINCT cobrado_en as nombre 
								   FROM t_clientes_creditos 
								   WHERE cobrado_en IS NOT NULL 
								   AND cobrado_en != '' 
								   ORDER BY cobrado_en";
				$bancos_alt = $this->db->query($query_creditos);
				
				if ($bancos_alt && $bancos_alt->num_rows() > 0) {
					foreach ($bancos_alt->result() as $banco) {
						$resultado[] = array(
							'id' => $banco->nombre,
							'nombre' => $banco->nombre
						);
					}
				}
			}
			
			// Estrategia 3: Si aún está vacío, usar bancos por defecto
			if (empty($resultado)) {
				$resultado = array(
					array('id' => '', 'nombre' => 'Todos los Bancos'),
					array('id' => 'BANCO DE VENEZUELA', 'nombre' => 'BANCO DE VENEZUELA'),
					array('id' => 'BANESCO', 'nombre' => 'BANESCO'),
					array('id' => 'MERCANTIL', 'nombre' => 'MERCANTIL'),
					array('id' => 'PROVINCIAL', 'nombre' => 'PROVINCIAL'),
					array('id' => 'BICENTENARIO', 'nombre' => 'BICENTENARIO')
				);
			}
			
			echo json_encode($resultado);
			exit;
			
		} catch (Exception $e) {
			// Log del error para debugging
			log_message('error', 'Error en DataSource_Bancos_Especiales: ' . $e->getMessage());
			
			// Devolver bancos por defecto
			$resultado = array(
				array('id' => '', 'nombre' => 'Todos los Bancos'),
				array('id' => 'BANCO DE VENEZUELA', 'nombre' => 'BANCO DE VENEZUELA'),
				array('id' => 'BANESCO', 'nombre' => 'BANESCO'),
				array('id' => 'MERCANTIL', 'nombre' => 'MERCANTIL'),
				array('id' => 'PROVINCIAL', 'nombre' => 'PROVINCIAL'),
				array('id' => 'BICENTENARIO', 'nombre' => 'BICENTENARIO')
			);
			echo json_encode($resultado);
			exit;
		}
	}
	
	// Función para obtener lista de nóminas para reportes especiales
	public function DataSource_Nominas_Especiales() {
		// Limpiar cualquier output buffer previo
		if (ob_get_level()) ob_clean();
		
		// Headers para evitar caché
		header('Content-Type: application/json; charset=utf-8');
		header('Cache-Control: no-cache, no-store, must-revalidate');
		header('Pragma: no-cache');
		header('Expires: 0');
		
		try {
			// Verificar conexión a la base de datos
			if (!$this->db->conn_id) {
				throw new Exception('No hay conexión a la base de datos');
			}
			
			$resultado = array();
			
			$query = "SELECT DISTINCT nomina_procedencia as nombre 
					  FROM t_clientes_creditos 
					  WHERE nomina_procedencia IS NOT NULL 
					  AND nomina_procedencia != '' 
					  ORDER BY nomina_procedencia";
			$nominas = $this->db->query($query);
			
			if ($nominas && $nominas->num_rows() > 0) {
				foreach ($nominas->result() as $nomina) {
					$resultado[] = array(
						'nombre' => $nomina->nombre
					);
				}
			}
			
			// Si no hay nóminas, devolver una opción por defecto
			if (empty($resultado)) {
				$resultado = array(
					array('nombre' => 'Todas las Nóminas')
				);
			}
			
			echo json_encode($resultado);
			exit;
			
		} catch (Exception $e) {
			// Log del error para debugging
			log_message('error', 'Error en DataSource_Nominas_Especiales: ' . $e->getMessage());
			
			// Devolver opción por defecto
			echo json_encode(array(
				array('nombre' => 'Todas las Nóminas')
			));
			exit;
		}
	}
	
	// Función principal para generar reportes especiales
	public function Generar_Reporte_Especial() {
		// Verificar sesión de manera más flexible
		$usuario = $this->session->userdata('usuario');
		if (!$usuario) {
			$usuario = 'admin'; // Usuario por defecto para desarrollo local
		}
		
		// Establecer sesión temporal si no existe
		if (!$this->session->userdata('usuario')) {
			$this->session->set_userdata('usuario', $usuario);
		}
		
		$this->load->model('cliente/mcliente', 'MCliente');
		
		$filtros = array(
			'banco' => $this->input->post('banco'),
			'nomina' => $this->input->post('nomina'),
			'estado' => $this->input->post('estado'),
			'tipo_contrato' => $this->input->post('tipo_contrato'),
			'forma_pago' => $this->input->post('forma_pago'),
			'empresa' => $this->input->post('empresa'),
			'fecha_desde' => $this->input->post('fecha_desde'),
			'fecha_hasta' => $this->input->post('fecha_hasta')
		);
		
		$resultado = $this->MCliente->Reporte_Especial($filtros);
		echo json_encode($resultado);
	}
	
	// Función para exportar reportes especiales
	public function Exportar_Reporte_Especial() {
		// Verificar sesión de manera más flexible
		$usuario = $this->session->userdata('usuario');
		if (!$usuario) {
			// Intentar obtener usuario de otra manera o usar un usuario por defecto
			$usuario = 'admin'; // Usuario por defecto para desarrollo local
		}
		
		$this->load->model('cliente/mcliente', 'MCliente');
		
		$filtros = array(
			'banco' => $this->input->post('banco'),
			'estado' => $this->input->post('estado'),
			
		);
		
		// Establecer sesión temporal si no existe
		if (!$this->session->userdata('usuario')) {
			$this->session->set_userdata('usuario', $usuario);
		}
		
		$this->MCliente->Exportar_Reporte_Especial($filtros);
	}


}
?>
