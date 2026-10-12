<?php
/**
 * API REST para el portal de usuarios
 */

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

session_start();

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../classes/Database.php';
require_once __DIR__ . '/../classes/Auth.php';
require_once __DIR__ . '/../classes/Usuario.php';
require_once __DIR__ . '/../classes/Factura.php';
require_once __DIR__ . '/../classes/ComprobantePDF.php';

$db = new Database();
$auth = new Auth($db);
$factura = new Factura($db);
$comprobante = new ComprobantePDF($db);

// Obtener método y ruta
$method = $_SERVER['REQUEST_METHOD'];
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
// Remover /portal-usuarios/api (solo para desarrollo local) o /api
$path = preg_replace('#^/portal-usuarios/api#', '', $path);
$path = preg_replace('#^/api#', '', $path);
$path = trim($path, '/');
$segments = explode('/', $path);

try {
    switch ($segments[0]) {
        case 'auth':
            if ($segments[1] === 'login' && $method === 'POST') {
                $data = json_decode(file_get_contents('php://input'), true);
                $result = $auth->loginSession($data['documento_id'], $data['password']);
                echo json_encode($result);
            } elseif ($segments[1] === 'register' && $method === 'POST') {
                $data = json_decode(file_get_contents('php://input'), true);
                $result = $auth->register($data['documento_id']);
                echo json_encode($result);
            } elseif ($segments[1] === 'verify-code' && $method === 'POST') {
                $data = json_decode(file_get_contents('php://input'), true);
                $result = $auth->verifyCode($data['documento_id'], $data['codigo'], $data['password']);
                echo json_encode($result);
            } elseif ($segments[1] === 'resend-code' && $method === 'POST') {
                $data = json_decode(file_get_contents('php://input'), true);
                $result = $auth->resendCode($data['documento_id']);
                echo json_encode($result);
            }
            break;
            
        case 'facturas':
            if (!isset($_SESSION['documento_id'])) {
                http_response_code(401);
                echo json_encode(['success' => false, 'error' => 'No autorizado']);
                exit();
            }
            
            if ($segments[1] === 'activas' && $method === 'GET') {
                $facturas = $factura->getFacturasActivas($_SESSION['documento_id']);
                echo json_encode(['success' => true, 'facturas' => $facturas]);
            } elseif ($segments[1] === 'contratos' && $method === 'GET' && isset($_GET['factura'])) {
                $contratos = $factura->getContratosPorFactura($_SESSION['documento_id'], $_GET['factura']);
                echo json_encode(['success' => true, 'contratos' => $contratos]);
            }
            break;
            
        case 'pagos':
            if (!isset($_SESSION['documento_id'])) {
                http_response_code(401);
                echo json_encode(['success' => false, 'error' => 'No autorizado']);
                exit();
            }
            
            if ($segments[1] === 'detalle' && $method === 'POST') {
                try {
                    $data = json_decode(file_get_contents('php://input'), true);
                    
                    if (!isset($data['contrato_id'])) {
                        http_response_code(400);
                        echo json_encode(['success' => false, 'error' => 'contrato_id es requerido']);
                        exit();
                    }
                    
                    $detalle = $factura->getDetallePagos($_SESSION['documento_id'], $data['contrato_id']);
                    
                    if ($detalle) {
                        echo json_encode(['success' => true, 'data' => $detalle], JSON_UNESCAPED_UNICODE);
                    } else {
                        http_response_code(404);
                        echo json_encode(['success' => false, 'error' => 'No se encontraron datos para este contrato']);
                    }
                } catch (Exception $e) {
                    http_response_code(500);
                    error_log("Error en API pagos/detalle: " . $e->getMessage());
                    echo json_encode(['success' => false, 'error' => 'Error al obtener detalles: ' . $e->getMessage()]);
                }
            }
            break;
            
        case 'comprobante':
            if (!isset($_SESSION['documento_id'])) {
                http_response_code(401);
                echo json_encode(['success' => false, 'error' => 'No autorizado']);
                exit();
            }
            
            if ($segments[1] === 'download' && $method === 'GET') {
                try {
                    $contratoId = isset($_GET['contrato']) ? $_GET['contrato'] : '';
                    $pagoId = isset($_GET['pago']) ? $_GET['pago'] : '';
                    
                    if (empty($contratoId) || empty($pagoId)) {
                        http_response_code(400);
                        echo json_encode(['success' => false, 'error' => 'contrato y pago son requeridos']);
                        exit();
                    }
                    
                    $comprobante->generarComprobante($_SESSION['documento_id'], $contratoId, $pagoId);
                } catch (Exception $e) {
                    http_response_code(500);
                    error_log("Error generando comprobante: " . $e->getMessage());
                    echo json_encode(['success' => false, 'error' => 'Error al generar comprobante: ' . $e->getMessage()]);
                }
            }
            break;
            
        default:
            http_response_code(404);
            echo json_encode(['success' => false, 'error' => 'Endpoint no encontrado']);
            break;
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

