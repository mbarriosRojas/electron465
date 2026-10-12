<?php
/**
 * Servicio de envío de emails
 */

class EmailService {
    
    /**
     * Envía un código de verificación por email
     */
    public static function sendVerificationCode($email, $codigo, $nombre = '') {
        $asunto = 'Código de Verificación - Portal de Usuarios Grupo Electrón';
        
        $mensaje = "
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background-color: #1c94c4; color: white; padding: 20px; text-align: center; }
                .content { padding: 20px; background-color: #f9f9f9; }
                .code { font-size: 32px; font-weight: bold; color: #1c94c4; text-align: center; padding: 20px; background-color: white; margin: 20px 0; border: 2px dashed #1c94c4; }
                .footer { padding: 20px; text-align: center; font-size: 12px; color: #666; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h2>Portal de Usuarios - Grupo Electrón</h2>
                </div>
                <div class='content'>
                    <p>Estimado/a" . ($nombre ? " $nombre" : '') . ",</p>
                    <p>Gracias por registrarte en nuestro portal de usuarios. Para completar tu registro, necesitamos verificar tu identidad.</p>
                    <p>Por favor, utiliza el siguiente código de verificación:</p>
                    <div class='code'>$codigo</div>
                    <p>Este código expirará en " . CODE_EXPIRATION_MINUTES . " minutos.</p>
                    <p>Si no solicitaste este código, puedes ignorar este mensaje.</p>
                </div>
                <div class='footer'>
                    <p>Grupo Electrón 465</p>
                    <p>Este es un mensaje automático, por favor no respondas a este correo.</p>
                </div>
            </div>
        </body>
        </html>
        ";
        
        $result = self::sendEmail($email, $asunto, $mensaje);
        
        // Log para debugging (solo en desarrollo)
        if (!$result) {
            error_log("Error: No se pudo enviar email de verificación a $email");
        }
        
        return $result;
    }
    
    /**
     * Envía un email genérico usando SMTP
     */
    private static function sendEmail($to, $subject, $message) {
        try {
            // Intentar usar SMTP primero
            $result = self::sendEmailSMTP($to, $subject, $message);
            if ($result) {
                return true;
            }
            
            // Fallback a mail() si SMTP falla
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
            $headers .= "From: " . SMTP_FROM_NAME . " <" . SMTP_FROM . ">" . "\r\n";
            
            if (function_exists('mail')) {
                return @mail($to, $subject, $message, $headers);
            }
            
            return false;
        } catch (Exception $e) {
            error_log("Error enviando email: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Envía email usando SMTP directamente
     */
    private static function sendEmailSMTP($to, $subject, $message) {
        $host = SMTP_HOST;
        $port = SMTP_PORT;
        $username = SMTP_USER;
        $password = SMTP_PASS;
        $from = SMTP_FROM;
        $fromName = SMTP_FROM_NAME;
        
        // Crear conexión
        $socket = @fsockopen($host, $port, $errno, $errstr, 30);
        if (!$socket) {
            error_log("Error SMTP: No se pudo conectar a $host:$port - $errstr ($errno)");
            return false;
        }
        
        // Leer respuesta inicial
        $response = fgets($socket, 515);
        if (substr($response, 0, 3) != '220') {
            error_log("Error SMTP: Respuesta inicial inválida: $response");
            fclose($socket);
            return false;
        }
        
        // EHLO
        fputs($socket, "EHLO " . $host . "\r\n");
        $response = '';
        while ($line = fgets($socket, 515)) {
            $response .= $line;
            if (substr($line, 3, 1) == ' ') break;
        }
        
        // STARTTLS si está disponible
        if (strpos($response, 'STARTTLS') !== false && $port == 587) {
            fputs($socket, "STARTTLS\r\n");
            $response = fgets($socket, 515);
            if (substr($response, 0, 3) == '220') {
                stream_socket_enable_crypto($socket, true, STREAM_CRYPTO_METHOD_TLS_CLIENT);
                fputs($socket, "EHLO " . $host . "\r\n");
                $response = '';
                while ($line = fgets($socket, 515)) {
                    $response .= $line;
                    if (substr($line, 3, 1) == ' ') break;
                }
            }
        }
        
        // Autenticación
        fputs($socket, "AUTH LOGIN\r\n");
        $response = fgets($socket, 515);
        if (substr($response, 0, 3) != '334') {
            error_log("Error SMTP: AUTH LOGIN falló: $response");
            fclose($socket);
            return false;
        }
        
        fputs($socket, base64_encode($username) . "\r\n");
        $response = fgets($socket, 515);
        if (substr($response, 0, 3) != '334') {
            error_log("Error SMTP: Usuario inválido: $response");
            fclose($socket);
            return false;
        }
        
        fputs($socket, base64_encode($password) . "\r\n");
        $response = fgets($socket, 515);
        if (substr($response, 0, 3) != '235') {
            error_log("Error SMTP: Autenticación falló: $response");
            fclose($socket);
            return false;
        }
        
        // FROM
        fputs($socket, "MAIL FROM: <$from>\r\n");
        $response = fgets($socket, 515);
        if (substr($response, 0, 3) != '250') {
            error_log("Error SMTP: MAIL FROM falló: $response");
            fclose($socket);
            return false;
        }
        
        // TO
        fputs($socket, "RCPT TO: <$to>\r\n");
        $response = fgets($socket, 515);
        if (substr($response, 0, 3) != '250') {
            error_log("Error SMTP: RCPT TO falló: $response");
            fclose($socket);
            return false;
        }
        
        // DATA
        fputs($socket, "DATA\r\n");
        $response = fgets($socket, 515);
        if (substr($response, 0, 3) != '354') {
            error_log("Error SMTP: DATA falló: $response");
            fclose($socket);
            return false;
        }
        
        // Headers y mensaje
        $headers = "From: $fromName <$from>\r\n";
        $headers .= "To: <$to>\r\n";
        $headers .= "Subject: $subject\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=UTF-8\r\n";
        $headers .= "\r\n";
        
        fputs($socket, $headers . $message . "\r\n.\r\n");
        $response = fgets($socket, 515);
        if (substr($response, 0, 3) != '250') {
            error_log("Error SMTP: Envío de mensaje falló: $response");
            fclose($socket);
            return false;
        }
        
        // QUIT
        fputs($socket, "QUIT\r\n");
        fclose($socket);
        
        return true;
    }
}

