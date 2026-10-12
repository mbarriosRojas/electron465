<?php
/**
 * Configuración de la aplicación
 */

// Configuración de base de datos
$isProduction = (getenv('APP_ENV') === 'production' ||
                 (isset($_SERVER['HTTP_HOST']) && strpos($_SERVER['HTTP_HOST'], 'grupoelectron465.com') !== false));

if ($isProduction) {
    define('DB_HOST', '204.13.239.170');
} else {
    define('DB_HOST', 'localhost');
}
define('DB_USER', 'grupoele_admin');
define('DB_PASS', 'Za63qj2p');
define('DB_NAME', 'grupoele_electron');
define('DB_CHARSET', 'utf8');
// Configuración de la aplicación
define('JWT_SECRET', 'tu_clave_secreta_jwt_cambiar_en_produccion_2024_grupoelectron');
define('CODE_EXPIRATION_MINUTES', 30);
define('TOKEN_EXPIRATION_HOURS', 24);

// Configuración de email
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USER', 'soporteelectron465@gmail.com');
define('SMTP_PASS', 'soporte8759');
define('SMTP_FROM', 'soporteelectron465@gmail.com');
define('SMTP_FROM_NAME', 'Grupo Electrón');

// Configuración de rutas
// Si estamos en producción (proyecto en la raíz), usar '/'
// Si estamos en desarrollo local (subcarpeta), usar '/portal-usuarios'
// Detectar si el proyecto está en la raíz verificando si la URL no contiene /portal-usuarios
$requestUri = $_SERVER['REQUEST_URI'] ?? '';
$scriptName = $_SERVER['SCRIPT_NAME'] ?? '';

// Si el script está en la raíz o estamos en producción, usar '/'
if ($isProduction || strpos($scriptName, '/portal-usuarios') === false) {
    define('BASE_URL', '/');
} else {
    define('BASE_URL', '/portal-usuarios');
}
// API_URL se genera usando la función url() para evitar dobles barras
define('API_URL', rtrim(BASE_URL, '/') . '/api');

// Función helper para generar URLs correctamente (evita dobles barras)
function url($path = '') {
    $base = rtrim(BASE_URL, '/');
    $path = ltrim($path, '/');
    if ($base === '' && $path === '') {
        return '/';
    }
    if ($base === '') {
        return '/' . $path;
    }
    if ($path === '') {
        return $base;
    }
    return $base . '/' . $path;
}

