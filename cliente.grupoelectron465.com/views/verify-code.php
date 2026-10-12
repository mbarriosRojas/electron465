<?php
$pageTitle = 'Verificar Correo Electrónico';
$showHeader = false;

$error = '';
$success = '';
$documentoId = isset($_GET['documento_id']) ? $_GET['documento_id'] : '';
$emailHint = isset($_GET['email_hint']) ? $_GET['email_hint'] : '';

if (empty($documentoId)) {
    header('Location: ' . url('register'));
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email']) && isset($_POST['password'])) {
    // Validar que las contraseñas coincidan
    if (!isset($_POST['confirm_password']) || $_POST['password'] !== $_POST['confirm_password']) {
        $error = 'Las contraseñas no coinciden';
        $documentoId = $_POST['documento_id'];
        $emailHint = $_POST['email_hint'] ?? '';
    } else {
        $result = $auth->verifyEmailAndPassword($_POST['documento_id'], $_POST['email'], $_POST['password']);
        
        if ($result['success']) {
            $success = $result['message'];
            header('Location: ' . url('login') . '?message=' . urlencode('Cuenta verificada exitosamente. Ya puedes iniciar sesión.'));
            exit;
        } else {
            $error = $result['error'];
            $documentoId = $_POST['documento_id'];
            $emailHint = $_POST['email_hint'] ?? '';
        }
    }
}

ob_start();
?>
<div class="login-container">
    <div class="login-card">
        <h2>Verificar Correo Electrónico</h2>
        <p class="subtitle">
            Por favor, completa tu correo electrónico para continuar con el registro
            <?php if ($emailHint): ?>
            <br><small style="color: #666; font-size: 0.9em;">Pista: <?php echo htmlspecialchars($emailHint); ?></small>
            <?php endif; ?>
        </p>
        <?php if ($error): ?>
        <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>
        <form method="POST" action="" id="verifyForm">
            <input type="hidden" name="documento_id" value="<?php echo htmlspecialchars($documentoId); ?>">
            <input type="hidden" name="email_hint" value="<?php echo htmlspecialchars($emailHint); ?>">
            <div class="form-group">
                <label for="email">Correo Electrónico Completo</label>
                <input type="email" id="email" name="email" class="form-control" required
                       placeholder="ejemplo@correo.com" autocomplete="email">
                <small class="form-text">
                    Ingresa el correo electrónico completo con el que te registraste
                    &middot;
                    <button type="button" class="link-help-alert" onclick="document.getElementById('modalNoRecuerdoCorreo').classList.add('is-open');">¿No lo recuerdas?</button>
                </small>
            </div>
            <div class="form-group">
                <label for="password">Nueva Contraseña</label>
                <input type="password" id="password" name="password" class="form-control" required 
                       placeholder="Mínimo 6 caracteres" minlength="6" autocomplete="new-password">
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirmar Contraseña</label>
                <input type="password" id="confirm_password" name="confirm_password" class="form-control" required 
                       placeholder="Repite la contraseña" minlength="6" autocomplete="new-password">
            </div>
            <button type="submit" class="btn btn-primary btn-block">Verificar y Crear Contraseña</button>
        </form>
        <div class="form-footer">
            <p><a href="<?php echo url('login'); ?>">Volver al login</a></p>
        </div>
    </div>
</div>

<div class="modal-overlay" id="modalNoRecuerdoCorreo">
    <div class="modal-box">
        <h3>¿No recuerdas tu correo?</h3>
        <p>
            Tranquilo, esto pasa con frecuencia. Nuestro equipo de <strong>soporte</strong> puede ayudarte
            a verificar tu identidad y actualizar el correo asociado a tu cuenta para que puedas continuar
            con el registro.
        </p>
        <p>
            Escríbenos por WhatsApp indicando tu número de cédula y con gusto te ayudamos a resolverlo.
        </p>
        <div class="modal-actions">
            <a class="btn btn-whatsapp btn-block" id="btnWhatsappSoporte" href="#" target="_blank" rel="noopener">
                Contactar por WhatsApp
            </a>
        </div>
        <div class="modal-actions">
            <button type="button" class="btn btn-secondary btn-block" onclick="document.getElementById('modalNoRecuerdoCorreo').classList.remove('is-open');">Cerrar</button>
        </div>
    </div>
</div>

<script>
(function () {
    var telefonoSoporte = '584121391323';
    var documentoId = <?php echo json_encode($documentoId); ?>;
    var mensaje = 'Hola, no recuerdo el correo con el que me registré en el Portal de Clientes de Grupo Electrón. '
        + 'Mi número de cédula es: ' + documentoId + '. ¿Me pueden ayudar a actualizarlo?';
    var enlace = document.getElementById('btnWhatsappSoporte');
    if (enlace) {
        enlace.href = 'https://wa.me/' + telefonoSoporte + '?text=' + encodeURIComponent(mensaje);
    }
})();
</script>
<?php
$content = ob_get_clean();
include VIEWS_PATH . '/layout.php';
?>

