<?php
$pageTitle = 'Iniciar Sesión';
$showHeader = false;

// Procesar login
$error = '';
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['documento_id']) && isset($_POST['password'])) {
    $result = $auth->loginSession($_POST['documento_id'], $_POST['password']);
    
    if ($result['success']) {
        header('Location: ' . url('dashboard'));
        exit;
    } else {
        $error = $result['error'];
        if (isset($result['needs_verification'])) {
            header('Location: ' . url('verify-code') . '?documento_id=' . urlencode($_POST['documento_id']));
            exit;
        }
    }
}

if (isset($_GET['message'])) {
    $message = $_GET['message'];
}

ob_start();
?>
<div class="login-container">
    <div class="login-card">
        <div class="logo-container">
            <img src="<?php echo url('assets/images/logo.png'); ?>" alt="Logo" class="login-logo">
        </div>
        <h2>Iniciar Sesión</h2>
        <?php if ($error): ?>
        <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <?php if ($message): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
        <form method="POST" action="" id="loginForm">
            <div class="form-group">
                <label for="documento_id">Documento de Identidad</label>
                <input type="text" id="documento_id" name="documento_id" class="form-control" required 
                       placeholder="Ej: 800029300" value="<?php echo isset($_POST['documento_id']) ? htmlspecialchars($_POST['documento_id']) : ''; ?>">
            </div>
            <div class="form-group">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" class="form-control" required 
                       placeholder="Ingresa tu contraseña">
            </div>
            <button type="submit" class="btn btn-primary btn-block">Iniciar Sesión</button>
        </form>
        <div class="form-footer">
            <p>¿No tienes una cuenta? <a href="<?php echo url('register'); ?>">Regístrate aquí</a></p>
        </div>
    </div>
</div>
<?php
$content = ob_get_clean();
include VIEWS_PATH . '/layout.php';
?>

