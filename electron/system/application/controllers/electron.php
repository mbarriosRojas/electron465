<?php
/*
 *  @author Carlos Enrique Peña Albarrán // Judelvis Antonio Rivas // Mauricio Barrios
 *
 *  @package cooperativa-electron.system.application
 *  @version 2.0.0
 */
date_default_timezone_set('America/Caracas');
session_start();
class electron extends Controller {
	var $var_capcha = "";
	public function __construct() {
		parent::__construct();
		$this -> load -> database();
		$this -> load -> helper('url');
		$this -> load -> model('CCreditose');
		$this -> load -> library('session');
		$this -> load -> model('cliente/mclientee', 'MClientee');
		$this -> load -> model("CPersonas");
	}

	/*
	 -------------------------------------------------------
	 Iniciando Paginas
	 -------------------------------------------------------
	 */

	public function index() {
		$this -> Login();
	}
	
	public function Login() {
		$data['capcha'] = $this -> capcha();
			
		$this -> load -> view("electron/frmVerifica_Cliente",$data);
	}
	
	public function capcha(){
		//session_start();
		$ranStr = md5(microtime());
 
		$ranStr = substr($ranStr, 0, 6);
 
		$this -> var_capcha = $ranStr;
 
		$newImage = imagecreatefromjpeg(__IMG__."cap_bg.jpg");
 
		$txtColor = imagecolorallocate($newImage, 0, 0, 0);
 
		imagestring($newImage, 5, 5, 5, $ranStr, $txtColor);
 
		//header("Content-type: image/jpeg");
 		$ruta = "system/img/capcha/".$ranStr.".jpg";
		imagejpeg($newImage,$ruta);
		
		imagedestroy($newImage);
		$_SESSION['capcha'] = md5($ranStr);
		return $ranStr;
	}
	
	public function Verifica_Cliente() {
		$msj_c = '';
		$ced_cliente = $_POST['txtCliente'];
		$cuenta = $_POST['txtCuenta'];
		$capcha = $_POST['capcha'];
		$this -> db -> where('documento_id', trim($ced_cliente));
		$rs = $this -> db -> get('t_personas');
		if ($rs -> num_rows() != 0) {
			foreach ($rs->result() as $row) {
				if (substr($row -> cuenta_1,-4) === trim($cuenta) && $_SESSION['capcha'] == md5($capcha) ) {
					//$Sessions = array('cliente' => $ced_cliente);
					//$this -> session -> set_userdata($Sessions);
					$_SESSION['cliente'] = $ced_cliente;
					//$data['cedula'] = $this -> session -> userdata('cliente');
					$data['cedula'] = $_SESSION['cliente'];
					$data['primer_nombre'] = $row -> primer_nombre;
					$data['segundo_nombre'] = $row -> segundo_nombre; 
					$data['primer_apellido'] = $row -> primer_apellido;
					$data['segundo_apellido'] = $row -> segundo_apellido;
					$data['contenido'] = "Recuerde que al finalizar de realizar su consulta debe hacer click en el boton SALIR del menu izquierdo";
					$this -> load -> view("electron/principal",$data);
				} else {
					$this -> Login();
				}

			}
		} else {
			$this -> Login();
		}

	}
	
	public function Consulta(){
		//if($this -> session -> userdata('cliente') != '' && $this -> session -> userdata('cliente') != 'NULL'){
		if($_SESSION['cliente'] != '' && $_SESSION['cliente'] != 'NULL'){
			//$data['cedula'] = $this -> session -> userdata('cliente');
			$data['cedula'] = $_SESSION['cliente'];
			$this -> load -> view ("consulta",$data);
		}
	}
	
	public function Consultar_Cliente(){
		$cedula = $_POST['cedula'];
		$data = $this -> MClientee -> TG_Cedula($cedula);
		echo $data;
	}
	
	/*public function MuestraDetalleReporte_Electron() {
		$this -> load -> model('cliente/MClientee', 'MClientee');
		if (isset($_POST['objeto'])) {
			$json = json_decode($_POST['objeto'], true);
			//$Arr['perfil'] = $this -> session -> userdata('nivel');
			$Arr['factura'] = $json[0];
			switch ($json[1]) {
				case 'Voucher':
					$jsC = $this -> MClientee -> Detalles_Facturas_Voucher($Arr);	
					break;
				case 'Mixto':
					$jsC = $this -> MClientee -> Detalles_Facturas_Mixto($Arr);	
					break;
				default:
					$jsC = $this -> MClientee -> Detalles_Facturas_Contratos($Arr);
						
					break;
			}
			echo $jsC;

		} else {
			echo "llega2";
		}

	}*/
	
	
	public function Imprimir_Estado_Cuenta($strCedula = '') {
		$Credito = new $this->CCreditose;
		//$usuario= $this -> session -> userdata('cliente');
		//if($this -> session -> userdata('cliente')){
		if($_SESSION['cliente']){
			$data['cabezera'] = $Credito -> Cabezera_Estado_Cuenta($_SESSION['cliente']);
			$data['detalles'] = $Credito -> Estado_Cuenta($_SESSION['cliente']);
			$this -> load -> view("electron/estado_cuenta", $data);	
		}else{
			$this -> Login();
		}
		
	}
	
	public function Imprimir_Estado_Cuenta_Contrato($cedula = '', $contrato = '') {
		//$cedula = $_POST['cedula'];
		//$contrato = $_POST['contrato'];
		if ($_SESSION['cliente']) {
			$Persona = new $this->CPersonas;
			$Credito = new $this->CCreditose;
			$data['cabezera'] = $Persona -> Cabezera_Estado_Cuenta($cedula);
			$data['detalles'] = $Credito -> Estado_Cuenta_Contrato($cedula, $contrato);
			$this -> load -> view("estado_cuenta", $data);
		} else {
			$this -> Login();
		}
	}
	

	public function logout() {
		//$this -> session -> sess_destroy();
		session_destroy();
		$this -> index();
	}
	
	
}
?>
