<?php
/**
 * Cobranza — endpoints nuevos (PHP), sin tocar cooperativa.php.
 */
if (!isset($_SESSION)) {
    session_start();
}
date_default_timezone_set('America/Caracas');

class cobranza extends Controller {

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->helper('url');
        $this->load->library('session');
    }

    private function jsonSessionRequired() {
        if (!$this->session->userdata('usuario')) {
            echo json_encode(array('ok' => false, 'msg' => 'Sesión expirada'));
            return false;
        }
        return true;
    }

    /**
     * Pantalla "Cargar Respuesta de Banco" (Banfan/Bicentenario/Venezuela) —
     * funcionalidad nueva, no reemplaza Leer_Txt. Ver
     * docs/HUs-leer-respuesta-bancaria-banfan-bicentenario-venezuela.md.
     */
    function respuestaBanco() {
        if (!$this->session->userdata('usuario')) {
            redirect('cooperativa/login');
            return;
        }
        $this->load->model('CMenu');
        $data['Menu'] = $this->CMenu->getHtml_Menu($this->session->userdata('nivel'));
        $data['Nivel'] = $this->session->userdata('nivel');
        $this->load->view('respuestaBanco', $data);
    }

    /** Generar TXT cobranza en bolívares (cuota USD × precio dólar BCV). */
    function GtxtBs() {
        $banco = isset($_POST['banco']) ? strtoupper(trim($_POST['banco'])) : '';
        if ($banco === 'VENEZUELA') {
            $this->load->model('cobranza/mgtxt_venezuela', 'Mgtxt_venezuela');
            echo $this->Mgtxt_venezuela->generar($_POST);
            return;
        }
        $this->load->model('cobranza/mgtxt_bolivares', 'Mgtxt_bolivares');
        echo $this->Mgtxt_bolivares->GtxtBs($_POST);
    }

    /** Exportar plantilla XLS Banco de Venezuela (fila 23+). */
    function ExportXlsVenezuela() {
        if (!$this->session->userdata('usuario')) {
            echo '<h2>Sesión expirada</h2>';
            return;
        }
        $this->load->model('cobranza/mgtxt_venezuela', 'Mgtxt_venezuela');
        echo $this->Mgtxt_venezuela->exportarXls($_POST);
    }

    /** Consultar tasa BCV por fecha (preview en carga Venezuela). */
    function getTasaPorFecha() {
        if (!$this->jsonSessionRequired()) {
            return;
        }
        $fecha = isset($_POST['fecha']) ? $_POST['fecha'] : '';
        $this->load->model('cargar/mcargavenezuela', 'MCargaV');
        $tasa = $this->MCargaV->resolverTasaPorFecha($fecha);
        if ($tasa) {
            echo json_encode(array(
                'ok' => true,
                'precio' => $tasa['precio'],
                'fecha' => $tasa['fecha'],
            ));
        } else {
            echo json_encode(array(
                'ok' => false,
                'msg' => 'No hay precio del dólar registrado para esa fecha.',
            ));
        }
    }

    function subirVenezuela() {
        if (!$this->jsonSessionRequired()) {
            return;
        }

        $banco = strtolower(isset($_POST['banco']) ? $_POST['banco'] : 'venezuela');
        $directorio = BASEPATH . 'repository/txt/' . $banco;
        if (!is_dir($directorio)) {
            @mkdir($directorio, 0775, true);
        }

        $nombre_archivo = $_FILES['archivo']['name'];
        $tmp_archivo = $_FILES['archivo']['tmp_name'];

        // Solo XLSX: la regla BCV/Cobranza por fila depende de comparar las
        // columnas montoTotal vs. montoCobrado del formato XLSX del banco;
        // los formatos viejos (TXT/CSV) no las traen.
        $extension = strtolower(pathinfo($nombre_archivo, PATHINFO_EXTENSION));
        if ($extension !== 'xlsx') {
            echo json_encode(array(
                'ok' => 'no',
                'msg' => 'Formato de archivo inválido. Solo se aceptan archivos .xlsx.',
            ));
            return;
        }

        $archivador = $directorio . '/' . $nombre_archivo;

        $this->load->model('cargar/mcargavenezuela', 'MCargaV');

        $existente = $this->db->query(
            'SELECT oid, estatus FROM t_archivos_venezuela WHERE archivo='
            . $this->db->escape($nombre_archivo) . ' ORDER BY oid DESC LIMIT 1'
        );
        if ($existente->num_rows() > 0 && (int) $existente->row()->estatus === 1) {
            echo json_encode(array(
                'ok' => 'no',
                'msg' => 'No Se Pudo Procesar. El Archivo Ya Fue Procesado',
            ));
            return;
        }

        if (!move_uploaded_file($tmp_archivo, $archivador)) {
            echo json_encode(array(
                'ok' => 'no',
                'msg' => 'Ocurrió un error al subir el archivo. No pudo guardarse.',
            ));
            return;
        }

        // fenv/frec ya no los pide el formulario (la fecha que importa es la
        // fechaCobro real de CADA cliente, dentro del propio XLSX) — se usan
        // solo como metadato informativo del registro del archivo.
        $hoy = date('Y-m-d');
        $tasa = $this->MCargaV->resolverTasaPorFecha($hoy);

        $tipoCobranza = isset($_POST['tipo_cobranza']) ? strtoupper(trim($_POST['tipo_cobranza'])) : 'REGULAR';
        if (!in_array($tipoCobranza, array('REGULAR', 'MOROSO'), true)) {
            $tipoCobranza = 'REGULAR';
        }

        // Periodicidad del archivo (mismas opciones que Crear_Txt): filtra
        // qué contratos de una cédula son elegibles al buscar/asignar — un
        // cliente puede tener contratos con distinta periodicidad, y este
        // archivo corresponde solo a la seleccionada aquí.
        $periocidadesValidas = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9);
        $periocidad = isset($_POST['periocidad']) ? (int) $_POST['periocidad'] : 4;
        if (!in_array($periocidad, $periocidadesValidas, true)) {
            $periocidad = 4;
        }

        $datos = array(
            'archivo' => $nombre_archivo,
            'empresa' => 0,
            'fcontrato' => 0,
            'perio' => $periocidad,
            'fenv' => $hoy,
            'frec' => $hoy,
            'tipo_cobranza' => $tipoCobranza,
            'precio_dolar' => $tasa ? $tasa['precio'] : 0,
            'fecha_tasa' => $tasa ? $tasa['fecha'] : null,
        );
        $reg = $this->MCargaV->registrarOReemplazar($datos);
        if (!$reg['ok']) {
            @unlink($archivador);
            echo json_encode(array('ok' => 'no', 'msg' => $reg['msg']));
            return;
        }

        echo json_encode(array(
            'ok' => 'si',
            'msg' => array('id' => $reg['id'], 'archivo' => $nombre_archivo),
            'tasa' => $tasa,
            'reemplazo' => !empty($reg['reemplazo']),
        ));
    }

    function leerArchivoVenezuela() {
        if (!$this->session->userdata('usuario')) {
            echo 'Sesión expirada';
            return;
        }
        $this->load->model('cargar/mcargavenezuela', 'MCargaV');
        $detalle = $this->MCargaV->leerArchivoDetalle(
            isset($_POST['id']) ? $_POST['id'] : 0,
            isset($_POST['archivo']) ? $_POST['archivo'] : ''
        );
        echo $detalle['ok']
            ? 'Se cargo con exito el archivo (' . (int) $detalle['filas'] . ' registro(s))'
            : $detalle['msg'];
    }

    function cmbArchivosVenezuela() {
        $this->load->model('cargar/mcargavenezuela', 'MCargaV');
        echo json_encode($this->MCargaV->crearCombo());
    }

    /**
     * Pre-chequeo de tasas BCV faltantes para un archivo Venezuela ya leído
     * (staging en t_cargar_txt_venezuela) — cada fila puede convertir con la
     * tasa de su propia fecha de cobro, a diferencia de Banfan/Bicentenario.
     * Se llama justo después de leer el archivo, antes de asignar contratos.
     */
    function validarTasasVenezuela() {
        $this->load->model('cargar/mcargavenezuela', 'MCargaV');
        $oida = isset($_POST['oida']) ? (int) $_POST['oida'] : 0;
        $faltantes = $this->MCargaV->tasasFaltantes($oida);
        echo json_encode(array('ok' => true, 'faltantes' => $faltantes));
    }

    function listarArchivoVenezuela() {
        $this->load->model('cargar/mcargavenezuela', 'MCargaV');
        echo $this->MCargaV->grid((int) $_POST['oida']);
    }

    function cmbContratosVenezuela() {
        $this->load->model('cargar/mcargavenezuela', 'MCargaV');
        echo $this->MCargaV->crearComboContrato($_POST['cedula']);
    }

    function modCuotaVenezuela() {
        $this->load->model('cargar/mcargavenezuela', 'MCargaV');
        $ok = $this->MCargaV->modCuota(
            $_POST['oid'],
            $_POST['contrato'],
            $_POST['frec'],
            $_POST['obser']
        );
        echo $ok ? 'Se proceso con exito' : 'Error al actualizar';
    }

    /**
     * Quita una fila del lote de Venezuela antes de Guardar Lote — ese
     * cliente no se guarda con esta carga.
     */
    function eliminarFilaVenezuela() {
        if (!$this->jsonSessionRequired()) {
            return;
        }
        $this->load->model('cargar/mcargavenezuela', 'MCargaV');
        $ok = $this->MCargaV->eliminarFila(isset($_POST['oid']) ? $_POST['oid'] : 0);
        echo json_encode(array('ok' => $ok));
    }

    function Guardar_Lote_Venezuela() {
        if (!$this->session->userdata('usuario')) {
            echo 'Sesión expirada';
            return;
        }
        $this->load->model('cargar/mcargavenezuela', 'MCargaV');
        $filas = json_decode($_POST['objeto'], true);
        $usu = (int) $this->session->userdata('oidu');
        $result = $this->MCargaV->guardarLote($filas, $usu);
        echo json_encode($result);
    }

    /**
     * Cargar respuesta de banco (Banfan/Bicentenario) — funcionalidad nueva,
     * independiente de Leer_Txt/mprocesar.php. Ver
     * docs/HUs-leer-respuesta-bancaria-banfan-bicentenario-venezuela.md.
     */
    function subirRespuestaBanco() {
        if (!$this->jsonSessionRequired()) {
            return;
        }
        $this->load->model('cargar/mrespuestabanco', 'MRespuestaBanco');

        $banco = isset($_POST['banco']) ? $_POST['banco'] : '';
        $nombreArchivo = isset($_FILES['archivo']['name']) ? $_FILES['archivo']['name'] : '';
        $tmpArchivo = isset($_FILES['archivo']['tmp_name']) ? $_FILES['archivo']['tmp_name'] : '';
        $usuario = $this->session->userdata('usuario');
        $tipoCobranza = isset($_POST['tipo_cobranza']) ? $_POST['tipo_cobranza'] : 'REGULAR';

        $res = $this->MRespuestaBanco->subirArchivo($banco, $nombreArchivo, $tmpArchivo, $usuario, $tipoCobranza);
        echo json_encode(array(
            'ok' => $res['ok'] ? 'si' : 'no',
            'msg' => $res['ok'] ? array('id' => $res['id'], 'archivo' => $nombreArchivo) : $res['msg'],
            'reemplazo' => !empty($res['reemplazo']),
            'duplicado_advertencia' => isset($res['duplicado_advertencia']) ? $res['duplicado_advertencia'] : '',
        ));
    }

    function leerRespuestaBanco() {
        if (!$this->session->userdata('usuario')) {
            echo 'Sesión expirada';
            return;
        }
        $this->load->model('cargar/mrespuestabanco', 'MRespuestaBanco');
        $detalle = $this->MRespuestaBanco->leerArchivoDetalle(
            isset($_POST['id']) ? $_POST['id'] : 0,
            isset($_POST['archivo']) ? $_POST['archivo'] : ''
        );
        echo $detalle['ok']
            ? 'Se cargó con éxito: ' . (int) $detalle['aplicadas'] . ' cobro(s) aplicado(s), '
                . (int) $detalle['rechazadas'] . ' rechazo(s)'
            : $detalle['msg'];
    }

    function cmbArchivosRespuestaBanco() {
        $this->load->model('cargar/mrespuestabanco', 'MRespuestaBanco');
        $banco = isset($_POST['banco']) ? $_POST['banco'] : null;
        echo json_encode($this->MRespuestaBanco->crearCombo($banco));
    }

    function listarRespuestaBanco() {
        $this->load->model('cargar/mrespuestabanco', 'MRespuestaBanco');
        echo $this->MRespuestaBanco->grid((int) $_POST['oida']);
    }

    function listarRespuestaBancoRechazos() {
        $this->load->model('cargar/mrespuestabanco', 'MRespuestaBanco');
        echo $this->MRespuestaBanco->gridRechazos((int) $_POST['oida']);
    }

    function cmbContratosRespuestaBanco() {
        $this->load->model('cargar/mrespuestabanco', 'MRespuestaBanco');
        echo $this->MRespuestaBanco->crearComboContrato($_POST['cedula'], $_POST['banco']);
    }

    function modContratoRespuestaBanco() {
        $this->load->model('cargar/mrespuestabanco', 'MRespuestaBanco');
        $ok = $this->MRespuestaBanco->modCuota($_POST['oid'], $_POST['contrato']);
        echo $ok ? 'Se proceso con exito' : 'Error al actualizar';
    }

    /**
     * Quita una fila del lote de Banfan/Bicentenario antes de Guardar Lote —
     * ese cliente no se guarda con esta carga.
     */
    function eliminarFilaRespuestaBanco() {
        if (!$this->jsonSessionRequired()) {
            return;
        }
        $this->load->model('cargar/mrespuestabanco', 'MRespuestaBanco');
        $ok = $this->MRespuestaBanco->eliminarFila(isset($_POST['oid']) ? $_POST['oid'] : 0);
        echo json_encode(array('ok' => $ok));
    }

    function GuardarLoteRespuestaBanco() {
        if (!$this->session->userdata('usuario')) {
            echo 'Sesión expirada';
            return;
        }
        $this->load->model('cargar/mrespuestabanco', 'MRespuestaBanco');
        $filas = json_decode($_POST['objeto'], true);
        $usu = (int) $this->session->userdata('oidu');
        $result = $this->MRespuestaBanco->guardarLote($filas, $usu);
        echo json_encode($result);
    }

    /**
     * Histórico/trazabilidad de cargas de respuesta bancaria — solo lectura,
     * no afecta el flujo de carga/asignación/guardado existente.
     */
    function gridHistoricoRespuestaBanco() {
        $this->load->model('cargar/mrespuestabanco', 'MRespuestaBanco');
        echo $this->MRespuestaBanco->gridHistoricoArchivos();
    }

    function listarHistoricoDetalleRB() {
        $banco = isset($_POST['banco']) ? strtoupper(trim($_POST['banco'])) : '';
        if ($banco === 'VENEZUELA') {
            $this->load->model('cargar/mcargavenezuela', 'MCargaV');
            echo $this->MCargaV->gridHistoricoDetalle((int) $_POST['oida']);
            return;
        }
        $this->load->model('cargar/mrespuestabanco', 'MRespuestaBanco');
        echo $this->MRespuestaBanco->gridHistoricoDetalle((int) $_POST['oida']);
    }

    function listarHistoricoRechazosRB() {
        $this->load->model('cargar/mrespuestabanco', 'MRespuestaBanco');
        echo $this->MRespuestaBanco->gridRechazos((int) $_POST['oida']);
    }

    function buscarCedulaRespuestaBanco() {
        $this->load->model('cargar/mrespuestabanco', 'MRespuestaBanco');
        echo $this->MRespuestaBanco->gridBuscarCedula(isset($_POST['cedula']) ? $_POST['cedula'] : '');
    }
}
