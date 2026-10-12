<?php
/**
 * Script de prueba para verificar el envío de emails
 * Uso: php test-email.php
 */

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/classes/EmailService.php';

// Email de prueba (cambiar por tu email)
$test_email = 'tu_email@ejemplo.com'; // CAMBIAR ESTO

echo "🧪 Probando envío de email...\n";
echo "📧 Enviando a: $test_email\n";
echo "📤 Desde: " . SMTP_FROM . "\n";
echo "\n";

$codigo = '123456';
$result = EmailService::sendVerificationCode($test_email, $codigo, 'Usuario de Prueba');

if ($result) {
    echo "✅ Email enviado exitosamente!\n";
    echo "📬 Revisa tu bandeja de entrada (y spam)\n";
} else {
    echo "❌ Error al enviar el email\n";
    echo "💡 Revisa los logs de PHP para más detalles\n";
    echo "💡 Verifica que las credenciales SMTP sean correctas\n";
    echo "💡 Si usas Gmail, asegúrate de usar una 'App Password' si tienes 2FA activado\n";
}

