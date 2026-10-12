<?php
$pageTitle = 'Registrarse';
$showHeader = false;

$error = '';
$success = '';
$documentoId = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['documento_id'])) {
    $result = $auth->register($_POST['documento_id']);
    
    if ($result['success']) {
        header('Location: ' . url('verify-code') . '?documento_id=' . urlencode($result['documento_id']) . '&email_hint=' . urlencode($result['email_hint']));
        exit;
    } else {
        $error = $result['error'];
        $documentoId = $_POST['documento_id'];
    }
}

ob_start();
?>
<div class="login-container">
    <div class="login-card">
        <div class="logo-container">
            <img src="<?php echo url('assets/images/logo.png'); ?>" alt="Logo" class="login-logo">
        </div>
        <h2>Crear Cuenta</h2>
        <p class="subtitle">Ingresa tu documento de identidad para comenzar</p>
        <?php if ($error): ?>
        <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>
        <form method="POST" action="" id="registerForm">
            <div class="form-group">
                <label for="documento_id">Documento de Identidad</label>
                <input type="text" id="documento_id" name="documento_id" class="form-control" required 
                       placeholder="Ej: 800029300" value="<?php echo htmlspecialchars($documentoId); ?>">
                <small class="form-text">Verificaremos tu identidad y te pediremos que completes tu correo electrónico</small>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Registrarse</button>
        </form>
        <div class="form-footer">
            <p>¿Ya tienes una cuenta? <a href="<?php echo url('login'); ?>">Inicia sesión aquí</a></p>
        </div>
    </div>
</div>
<?php
$content = ob_get_clean();
include VIEWS_PATH . '/layout.php';
?>

