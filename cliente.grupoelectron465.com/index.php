<?php
/**
 * Portal de Usuarios - Grupo Electrón
 * Sistema unificado PHP (Backend + Frontend)
 */

session_start();

// Constantes
define('BASE_PATH', __DIR__);
define('API_PATH', BASE_PATH . '/api');
define('VIEWS_PATH', BASE_PATH . '/views');
define('ASSETS_PATH', BASE_PATH . '/assets');

// Autoload de clases
require_once BASE_PATH . '/config/config.php';
require_once BASE_PATH . '/classes/Database.php';
require_once BASE_PATH . '/classes/Auth.php';
require_once BASE_PATH . '/classes/Usuario.php';
require_once BASE_PATH . '/classes/Factura.php';
require_once BASE_PATH . '/classes/EmailService.php';

// Routing simple
$request = $_SERVER['REQUEST_URI'];
$path = parse_url($request, PHP_URL_PATH);
// Remover /portal-usuarios si existe (solo para desarrollo local)
$path = preg_replace('#^/portal-usuarios#', '', $path);
$path = trim($path, '/');

// Si es una petición a la API
if (strpos($path, 'api/') === 0 || $path === 'api') {
    require_once BASE_PATH . '/api/index.php';
    exit;
}

// Si es un archivo estático (CSS, JS, imágenes)
if (preg_match('/\.(css|js|jpg|jpeg|png|gif|ico|svg|woff|woff2|ttf|eot)$/', $path)) {
    $file = BASE_PATH . '/' . $path;
    if (file_exists($file)) {
        $mimeTypes = [
            'css' => 'text/css',
            'js' => 'application/javascript',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'ico' => 'image/x-icon',
            'svg' => 'image/svg+xml'
        ];
        $ext = pathinfo($file, PATHINFO_EXTENSION);
        header('Content-Type: ' . ($mimeTypes[$ext] ?? 'application/octet-stream'));
        readfile($file);
        exit;
    }
}

// Routing de páginas
$db = new Database();
$auth = new Auth($db);

// Verificar si el usuario está autenticado
$isAuthenticated = isset($_SESSION['user_id']) && isset($_SESSION['documento_id']);

// Determinar qué página mostrar
$page = $path ?: 'dashboard';

// Manejar ruta de factura (factura/NUMERO)
if (strpos($path, 'factura/') === 0) {
    $page = 'factura';
}

// Páginas públicas
$publicPages = ['login', 'register', 'verify-code', 'api', 'logout'];

// Redirigir a login si no está autenticado y no es página pública
if (!$isAuthenticated && !in_array($page, $publicPages)) {
    header('Location: ' . url('login'));
    exit;
}

// Redirigir a dashboard si está autenticado y está en login/register
if ($isAuthenticated && in_array($page, ['login', 'register'])) {
    header('Location: ' . url('dashboard'));
    exit;
}

// Incluir la página correspondiente
$pageFile = VIEWS_PATH . '/' . $page . '.php';
if (file_exists($pageFile)) {
    // Pasar variables a las vistas
    $userData = null;
    if ($isAuthenticated) {
        $usuario = new Usuario($db);
        $userData = $usuario->getDatosBasicosSession($_SESSION['documento_id']);
    }
    include $pageFile;
} else {
    http_response_code(404);
    include VIEWS_PATH . '/404.php';
}

