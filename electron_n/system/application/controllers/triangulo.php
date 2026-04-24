<?php
/*
 * @author Carlos Enrique Peña Albarrán
 *
 * @package cooperativa-electron.system.application
 * @version 2.0.0
 */
date_default_timezone_set ( 'America/Caracas' );
session_start ();
class triangulo extends Controller {

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
        $this->lista();
    }

    public function lista(){
        if ($this->session->userdata ( 'usuario' )) {

            $data ['Nivel'] = $this->session->userdata ( 'nivel' );
            $data['vista'] = 'lista';
            $_SESSION ['usuario'] = $this->session->userdata ( 'usuario' );
            $this->load->view ( "triangulo/incluir/cab", $data );
            $this->load->view ( "triangulo/dashboart", $data );
            $js["vista"] = "lista.js";
            $this->load->view ( "triangulo/incluir/script", $js );
        } else {
            $this->logout();
        }
    }

    public function crear(){
        if ($this->session->userdata ( 'usuario' )) {
            $this->load->model("triangulo/ctriangulo","CTriangulo");
            $data ['Nivel'] = $this->session->userdata ( 'nivel' );
            $data['vista'] = 'crear';
            $_SESSION ['usuario'] = $this->session->userdata ( 'usuario' );
            $this->load->view ( "triangulo/incluir/cab", $data );
            $this->load->view ( "triangulo/dashboart", $data );
            $js["vista"] = "crear.js";
            $this->load->view ( "triangulo/incluir/script", $js );
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

    public function detalle($id){
        if ($this->session->userdata ( 'usuario' )) {
            $data ['Nivel'] = $this->session->userdata ( 'nivel' );
            $data['vista'] = 'detalle';
            $this->load->model("triangulo/ctriangulo","CTriangulo");
			$data["datos"] = $this->CTriangulo->detalle($id);
            $_SESSION ['usuario'] = $this->session->userdata ( 'usuario' );
            $this->load->view ( "triangulo/incluir/cab", $data );
            $this->load->view ( "triangulo/dashboart", $data );
            $js["vista"] = "detalle.js";
            $this->load->view ( "triangulo/incluir/script", $js );
        } else {
            $this->logout();
        }

    }

    public function BuscaLista(){
        $this->load->model("triangulo/ctriangulo","CTriangulo");
        $html = "";
        if($_POST['tipo'] != ""){
            $html = $this->CTriangulo->lista_triangulo($_POST["tipo"]);
        }
        echo json_encode($html);
    }
	
    public function guardar(){
        $this->load->model("triangulo/ctriangulo","CTriangulo");
        $msj = $this->CTriangulo->guardar($_POST);
        echo $msj;
    }

    public function get(){
        $this->load->model("triangulo/ctriangulo","CTriangulo");
        $msj = $this->CTriangulo->factura($_POST["fact"]);
        echo json_encode($msj);
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

}