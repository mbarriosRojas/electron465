<?php
/**
 * Panel de control (módulos nuevos, sin tocar cooperativa.php).
 */
if (!isset($_SESSION)) {
    session_start();
}
date_default_timezone_set('America/Caracas');

class panel extends Controller {

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->helper('url');
        $this->load->model('CMenu');
        $this->load->library('session');
    }

    private function login() {
        redirect('cooperativa/login');
    }

    function panelDolar() {
        if ($this->session->userdata('usuario')) {
            $data['Menu'] = $this->CMenu->getHtml_Menu($this->session->userdata('nivel'));
            $data['Nivel'] = $this->session->userdata('nivel');
            $this->load->view('panel/panelDolar', $data);
        } else {
            $this->login();
        }
    }

    function getPrecioDolarEstado() {
        if (!$this->session->userdata('usuario')) {
            echo json_encode(array('ok' => false, 'msg' => 'Sesión expirada'));
            return;
        }
        $this->load->model('mprecio_dolar', 'MPrecio_dolar');
        $banco = isset($_POST['banco']) ? $_POST['banco'] : '';
        $estado = $this->MPrecio_dolar->getEstadoPanel($banco);
        $estado['ok'] = true;
        echo json_encode($estado);
    }

    /**
     * Registro obligatorio de ambas tasas (BCV y Cobranza) en una sola operación.
     */
    function guardarPrecioDolar() {
        if (!$this->session->userdata('usuario')) {
            echo json_encode(array('ok' => false, 'msg' => 'Sesión expirada'));
            return;
        }
        $this->load->model('mprecio_dolar', 'MPrecio_dolar');
        $precioBcv = isset($_POST['precio_bcv']) ? $_POST['precio_bcv'] : '';
        $precioCobranza = isset($_POST['precio_cobranza']) ? $_POST['precio_cobranza'] : '';
        $fecha = isset($_POST['fecha']) ? $_POST['fecha'] : '';
        $banco = isset($_POST['banco']) ? $_POST['banco'] : '';
        $usuario = $this->session->userdata('usuario');
        echo json_encode($this->MPrecio_dolar->guardarAmbos($precioBcv, $precioCobranza, $usuario, $fecha, $banco));
    }

    /**
     * Precio del dólar vigente (consumido por cobranza TXT Bolívares y otros módulos).
     * Por defecto BCV; se puede pedir 'cobranza' vía POST 'tipo', y el banco propio vía
     * POST 'banco' (si no se indica, o no tiene tasa propia, cae en General).
     */
    function getPrecioDolarVigente() {
        $this->load->model('mprecio_dolar', 'MPrecio_dolar');
        $tipo = isset($_POST['tipo']) ? $_POST['tipo'] : MPrecio_dolar::TIPO_BCV;
        $banco = isset($_POST['banco']) ? $_POST['banco'] : '';
        $row = $this->MPrecio_dolar->getPrecioActivo($tipo, $banco);
        if ($row) {
            echo json_encode(array('ok' => true, 'precio' => (float) $row->precio, 'fecha' => $row->fecha));
        } else {
            echo json_encode(array('ok' => false, 'precio' => 0, 'fecha' => ''));
        }
    }
}
