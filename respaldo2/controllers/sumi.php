<?php
/*
 * @author Carlos Enrique Peña Albarrán
 *
 * @package cooperativa-electron.system.application
 * @version 2.0.0
 */
date_default_timezone_set ( 'America/Caracas' );
session_start ();
class sumi extends Controller {
	
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
	
	public function index(){
		$this->entregar();
	}

    public function crear(){
        if ($this->session->userdata ( 'usuario' )) {
        	$this->load->model("sumi/ccontrato","CContrato");
			$data ['cmb'] = $this->CContrato->cmbBien();
            $data ['Nivel'] = $this->session->userdata ( 'nivel' );
            $data['vista'] = 'crear';
            $_SESSION ['usuario'] = $this->session->userdata ( 'usuario' );
            $this->load->view ( "sumi/incluir/cab", $data );
            $this->load->view ( "sumi/dashboart", $data );
            $js["vista"] = "crear.js";
            $this->load->view ( "sumi/incluir/script", $js );
        } else {
            $this->logout();
        }

    }
    public function entregar(){
        if ($this->session->userdata ( 'usuario' )) {
            $data ['Nivel'] = $this->session->userdata ( 'nivel' );
            $data['vista'] = 'entregar';
            $_SESSION ['usuario'] = $this->session->userdata ( 'usuario' );
            $this->load->view ( "sumi/incluir/cab", $data );
            $this->load->view ( "sumi/dashboart", $data );
            $js["vista"] = "entregar.js";
            $this->load->view ( "sumi/incluir/script", $js );
        } else {
            $this->logout();
        }

    }

    public function reporte(){
        if ($this->session->userdata ( 'usuario' )) {
            $data ['Nivel'] = $this->session->userdata ( 'nivel' );
            $data['vista'] = 'reporte';
            $_SESSION ['usuario'] = $this->session->userdata ( 'usuario' );
            $this->load->view ( "sumi/incluir/cab", $data );
            $this->load->view ( "sumi/dashboart", $data );
            $js["vista"] = "reporte.js";
            $this->load->view ( "sumi/incluir/script", $js );
        } else {
            $this->logout();
        }

    }

    public function BuscaContratos(){
        $this->load->model("sumi/ccontrato","CContrato");
        $html = "";
        if($_POST['tipo'] != ""){
            $html = $this->CContrato->ficha($_POST["tipo"]);
        }
        echo json_encode($html);
    }
    public function guardar(){
        $this->load->model("sumi/ccontrato","CContrato");
        $msj = $this->CContrato->guardar($_POST);
        echo $msj;
    }

    public function guardarEntrega(){
        $this->load->model("sumi/ccontrato","CContrato");
        $msj = $this->CContrato->guardarEntrega($_POST);
        echo $msj;
    }

    public function get(){
        $this->load->model("sumi/ccontrato","CContrato");
        $msj = $this->CContrato->factura($_POST["fact"]);
        echo json_encode($msj);
    }

    function listarep($est){
        $this->load->model("sumi/ccontrato","CContrato");
        $res = $this->CContrato->reporte($est);
        if ($res['filas'] != 0) {
            $data['data'] = $res['datos'];
        } else {
            $data['data'] = array();
        }
        //$data['data'] = array();
        echo json_encode($data);
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
	
	public function bien(){
        if ($this->session->userdata ( 'usuario' )) {
            $data ['Nivel'] = $this->session->userdata ( 'nivel' );
            $data['vista'] = 'bien';
            $_SESSION ['usuario'] = $this->session->userdata ( 'usuario' );
            $this->load->view ( "sumi/incluir/cab", $data );
            $this->load->view ( "sumi/dashboart", $data );
            $js["vista"] = "bien.js";
            $this->load->view ( "sumi/incluir/script", $js );
        } else {
            $this->logout();
        }

    }
	
	public function guardarBien(){
        $this->load->model("sumi/ccontrato","CContrato");
        $msj = $this->CContrato->guardarBien($_POST);
        echo $msj;
    }

}