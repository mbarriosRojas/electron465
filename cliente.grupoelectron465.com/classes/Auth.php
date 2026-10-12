<?php
/**
 * Clase para manejo de autenticación
 */

class Auth {
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
    }
    
    /**
     * Login de usuario usando sesiones
     */
    public function loginSession($documentoId, $password) {
        try {
            $documentoId = $this->db->escape($documentoId);
            
            $query = "SELECT id, documento_id, password, email_verificado FROM usuarios_portal 
                     WHERE documento_id = '$documentoId'";
            
            $result = $this->db->query($query);
            
            if ($result->num_rows === 0) {
                return ['success' => false, 'error' => 'Usuario no encontrado. Por favor, regístrate primero.'];
            }
            
            $usuario = $result->fetch_assoc();
            
            if ($usuario['email_verificado'] == 0) {
                return ['success' => false, 'error' => 'Tu cuenta no está verificada. Por favor, verifica tu email primero.', 'needs_verification' => true];
            }
            
            if (!password_verify($password, $usuario['password'])) {
                return ['success' => false, 'error' => 'Contraseña incorrecta'];
            }
            
            // Crear sesión
            $_SESSION['user_id'] = $usuario['id'];
            $_SESSION['documento_id'] = $usuario['documento_id'];
            
            // Actualizar último login
            $queryUpdate = "UPDATE usuarios_portal SET ultimo_login = NOW() WHERE id = " . $usuario['id'];
            $this->db->query($queryUpdate);
            
            return ['success' => true, 'message' => 'Login exitoso'];
            
        } catch (Exception $e) {
            return ['success' => false, 'error' => 'Error al iniciar sesión: ' . $e->getMessage()];
        }
    }
    
    /**
     * Logout
     */
    public function logout() {
        session_destroy();
        return ['success' => true];
    }
    
    /**
     * Registro inicial - solo requiere documento de identidad
     */
    public function register($documentoId) {
        try {
            if (empty($documentoId)) {
                return ['success' => false, 'error' => 'El documento de identidad es requerido'];
            }
            
            $documentoIdEscaped = $this->db->escape($documentoId);
            
            // Verificar si el documento existe en la base de datos de clientes
            $query = "SELECT documento_id, correo, CONCAT(primer_nombre, ' ', primer_apellido) as nombre 
                      FROM t_personas 
                      WHERE documento_id = '$documentoIdEscaped' 
                      LIMIT 1";
            
            $result = $this->db->query($query);
            
            if ($result->num_rows === 0) {
                return ['success' => false, 'error' => 'Documento de identidad no encontrado en nuestro sistema'];
            }
            
            $cliente = $result->fetch_assoc();
            
            // Verificar si el correo existe
            if (empty($cliente['correo'])) {
                return ['success' => false, 'error' => 'No se encontró un correo electrónico asociado a este documento. Por favor, contacte a atención al cliente.'];
            }
            
            $email = $cliente['correo'];
            
            // Verificar si ya existe un usuario registrado
            $queryUsuario = "SELECT id, email_verificado FROM usuarios_portal WHERE documento_id = '$documentoIdEscaped'";
            $resultUsuario = $this->db->query($queryUsuario);
            
            if ($resultUsuario->num_rows > 0) {
                $usuario = $resultUsuario->fetch_assoc();
                if ($usuario['email_verificado'] == 1) {
                    return ['success' => false, 'error' => 'Ya existe una cuenta registrada para este documento. Por favor, inicie sesión.'];
                } else {
                    // Usuario existe pero no está verificado, actualizar email
                    $queryUpdate = "UPDATE usuarios_portal SET email = '$email' WHERE id = " . $usuario['id'];
                    $this->db->query($queryUpdate);
                }
            } else {
                // Crear nuevo registro de usuario (sin verificar aún)
                $queryInsert = "INSERT INTO usuarios_portal (documento_id, email, email_verificado, created_at) 
                               VALUES ('$documentoIdEscaped', '$email', 0, NOW())";
                
                $this->db->query($queryInsert);
            }
            
            // Ocultar email parcialmente para mostrar al usuario
            // Formato: primeros 2 caracteres + ***** + @ + ****
            $emailParts = explode('@', $email);
            $localPart = $emailParts[0];
            $domainPart = $emailParts[1];
            
            // Mostrar primeros 2 caracteres, luego asteriscos
            $localOculto = substr($localPart, 0, 2) . '*****';
            $emailOculto = $localOculto . '@****';
            
            return [
                'success' => true,
                'message' => 'Por favor, completa tu correo electrónico para continuar',
                'email_hint' => $emailOculto,
                'email_length' => strlen($email),
                'documento_id' => $documentoId
            ];
            
        } catch (Exception $e) {
            return ['success' => false, 'error' => 'Error al procesar el registro: ' . $e->getMessage()];
        }
    }
    
    /**
     * Verificar email completo y crear contraseña (nuevo método sin código)
     */
    public function verifyEmailAndPassword($documentoId, $emailCompleto, $password) {
        try {
            if (empty($documentoId) || empty($emailCompleto) || empty($password)) {
                return ['success' => false, 'error' => 'Datos incompletos'];
            }
            
            if (strlen($password) < 6) {
                return ['success' => false, 'error' => 'La contraseña debe tener al menos 6 caracteres'];
            }
            
            $documentoIdEscaped = $this->db->escape($documentoId);
            $emailEscaped = $this->db->escape($emailCompleto);
            
            // Verificar que el email coincida con el registrado
            $query = "SELECT id, email FROM usuarios_portal 
                     WHERE documento_id = '$documentoIdEscaped' AND email_verificado = 0";
            $result = $this->db->query($query);
            
            if ($result->num_rows === 0) {
                return ['success' => false, 'error' => 'No se encontró un registro pendiente de verificación'];
            }
            
            $usuario = $result->fetch_assoc();
            
            // Verificar que el email ingresado coincida exactamente
            if (strtolower(trim($emailCompleto)) !== strtolower(trim($usuario['email']))) {
                return ['success' => false, 'error' => 'El correo electrónico no coincide. Por favor, verifica e intenta nuevamente.'];
            }
            
            // Guardar contraseña y marcar como verificado
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            $queryUpdate = "UPDATE usuarios_portal 
                           SET password = '$passwordHash',
                               email_verificado = 1,
                               codigo_verificacion = NULL,
                               codigo_expira = NULL,
                               updated_at = NOW()
                           WHERE id = " . $usuario['id'];
            
            $this->db->query($queryUpdate);
            
            return ['success' => true, 'message' => 'Cuenta verificada exitosamente. Ya puedes iniciar sesión.'];
            
        } catch (Exception $e) {
            return ['success' => false, 'error' => 'Error al verificar el correo: ' . $e->getMessage()];
        }
    }
    
    /**
     * Verificar código y crear contraseña (método antiguo - mantener por compatibilidad)
     */
    public function verifyCode($documentoId, $codigo, $password) {
        try {
            if (empty($documentoId) || empty($codigo) || empty($password)) {
                return ['success' => false, 'error' => 'Datos incompletos'];
            }
            
            if (strlen($password) < 6) {
                return ['success' => false, 'error' => 'La contraseña debe tener al menos 6 caracteres'];
            }
            
            $documentoIdEscaped = $this->db->escape($documentoId);
            
            $query = "SELECT id, codigo_verificacion, codigo_expira FROM usuarios_portal 
                     WHERE documento_id = '$documentoIdEscaped' AND email_verificado = 0";
            $result = $this->db->query($query);
            
            if ($result->num_rows === 0) {
                return ['success' => false, 'error' => 'No se encontró un registro pendiente de verificación'];
            }
            
            $usuario = $result->fetch_assoc();
            
            // Verificar expiración
            if (strtotime($usuario['codigo_expira']) < time()) {
                return ['success' => false, 'error' => 'El código de verificación ha expirado. Por favor, solicita uno nuevo.'];
            }
            
            // Verificar código
            if (!password_verify($codigo, $usuario['codigo_verificacion'])) {
                return ['success' => false, 'error' => 'Código de verificación incorrecto'];
            }
            
            // Guardar contraseña y marcar como verificado
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            $queryUpdate = "UPDATE usuarios_portal 
                           SET password = '$passwordHash',
                               email_verificado = 1,
                               codigo_verificacion = NULL,
                               codigo_expira = NULL,
                               updated_at = NOW()
                           WHERE id = " . $usuario['id'];
            
            $this->db->query($queryUpdate);
            
            return ['success' => true, 'message' => 'Cuenta verificada exitosamente. Ya puedes iniciar sesión.'];
            
        } catch (Exception $e) {
            return ['success' => false, 'error' => 'Error al verificar el código: ' . $e->getMessage()];
        }
    }
    
    /**
     * Reenviar código de verificación
     */
    public function resendCode($documentoId) {
        try {
            if (empty($documentoId)) {
                return ['success' => false, 'error' => 'El documento de identidad es requerido'];
            }
            
            $documentoIdEscaped = $this->db->escape($documentoId);
            
            // Buscar cliente
            $query = "SELECT documento_id, correo, CONCAT(primer_nombre, ' ', primer_apellido) as nombre 
                      FROM t_personas 
                      WHERE documento_id = '$documentoIdEscaped' 
                      LIMIT 1";
            
            $result = $this->db->query($query);
            
            if ($result->num_rows === 0) {
                return ['success' => false, 'error' => 'Documento de identidad no encontrado'];
            }
            
            $cliente = $result->fetch_assoc();
            $email = $cliente['correo'];
            
            if (empty($email)) {
                return ['success' => false, 'error' => 'No se encontró un correo electrónico asociado'];
            }
            
            // Buscar o crear usuario
            $queryUsuario = "SELECT id FROM usuarios_portal WHERE documento_id = '$documentoIdEscaped'";
            $resultUsuario = $this->db->query($queryUsuario);
            
            if ($resultUsuario->num_rows > 0) {
                $usuario = $resultUsuario->fetch_assoc();
                $usuarioId = $usuario['id'];
            } else {
                // Crear nuevo registro
                $codigoVerificacion = $this->generateVerificationCode();
                $codigoHash = password_hash($codigoVerificacion, PASSWORD_DEFAULT);
                
                $queryInsert = "INSERT INTO usuarios_portal (documento_id, email, codigo_verificacion, codigo_expira, created_at) 
                               VALUES ('$documentoIdEscaped', '$email', '$codigoHash', DATE_ADD(NOW(), INTERVAL " . CODE_EXPIRATION_MINUTES . " MINUTE), NOW())";
                
                $this->db->query($queryInsert);
                $usuarioId = $this->db->getLastInsertId();
                
                EmailService::sendVerificationCode($email, $codigoVerificacion, $cliente['nombre']);
                
                return ['success' => true, 'message' => 'Código de verificación enviado'];
            }
            
            // Generar nuevo código
            $codigoVerificacion = $this->generateVerificationCode();
            $codigoHash = password_hash($codigoVerificacion, PASSWORD_DEFAULT);
            
            $queryUpdate = "UPDATE usuarios_portal 
                           SET codigo_verificacion = '$codigoHash',
                               codigo_expira = DATE_ADD(NOW(), INTERVAL " . CODE_EXPIRATION_MINUTES . " MINUTE)
                           WHERE id = $usuarioId";
            
            $this->db->query($queryUpdate);
            
            EmailService::sendVerificationCode($email, $codigoVerificacion, $cliente['nombre']);
            
            return ['success' => true, 'message' => 'Código de verificación reenviado'];
            
        } catch (Exception $e) {
            return ['success' => false, 'error' => 'Error al reenviar el código: ' . $e->getMessage()];
        }
    }
    
    /**
     * Cambiar contraseña desde el perfil (requiere la contraseña actual).
     * $documentoId se toma de la sesión en la vista, nunca de un input del
     * usuario, para que nadie pueda cambiar la clave de otra cédula.
     */
    public function cambiarPassword($documentoId, $passwordActual, $passwordNueva) {
        try {
            if (empty($passwordActual) || empty($passwordNueva)) {
                return ['success' => false, 'error' => 'Debes completar la contraseña actual y la nueva.'];
            }

            if (strlen($passwordNueva) < 6) {
                return ['success' => false, 'error' => 'La nueva contraseña debe tener al menos 6 caracteres.'];
            }

            $documentoIdEscaped = $this->db->escape($documentoId);

            $query = "SELECT id, password FROM usuarios_portal WHERE documento_id = '$documentoIdEscaped'";
            $result = $this->db->query($query);

            if ($result->num_rows === 0) {
                return ['success' => false, 'error' => 'Usuario no encontrado.'];
            }

            $usuario = $result->fetch_assoc();

            if (!password_verify($passwordActual, $usuario['password'])) {
                return ['success' => false, 'error' => 'La contraseña actual es incorrecta.'];
            }

            if (password_verify($passwordNueva, $usuario['password'])) {
                return ['success' => false, 'error' => 'La nueva contraseña debe ser distinta a la actual.'];
            }

            $passwordHash = password_hash($passwordNueva, PASSWORD_DEFAULT);
            $queryUpdate = "UPDATE usuarios_portal
                           SET password = '$passwordHash',
                               updated_at = NOW()
                           WHERE id = " . $usuario['id'];

            $this->db->query($queryUpdate);

            return ['success' => true, 'message' => 'Contraseña actualizada exitosamente.'];

        } catch (Exception $e) {
            return ['success' => false, 'error' => 'Error al cambiar la contraseña: ' . $e->getMessage()];
        }
    }

    /**
     * Generar código de verificación de 6 dígitos
     */
    private function generateVerificationCode() {
        return str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
    }
}

