<?php
$pageTitle = 'Mi Perfil';
$showHeader = true;

if (!isset($userData) || !$userData) {
    header('Location: ' . url('login'));
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['password_actual'])) {
    $passwordActual = $_POST['password_actual'];
    $passwordNueva = $_POST['password_nueva'] ?? '';
    $passwordRepetir = $_POST['password_repetir'] ?? '';

    if ($passwordNueva !== $passwordRepetir) {
        $error = 'La nueva contraseña y su confirmación no coinciden.';
    } else {
        // documento_id SIEMPRE de la sesión, nunca de un input del formulario.
        $result = $auth->cambiarPassword($_SESSION['documento_id'], $passwordActual, $passwordNueva);
        if ($result['success']) {
            $success = $result['message'];
        } else {
            $error = $result['error'];
        }
    }
}

$iniciales = strtoupper(substr($userData['primer_nombre'], 0, 1) . substr($userData['primer_apellido'], 0, 1));
$documentoCompleto = rtrim($userData['nacionalidad'] ?: 'V', '-') . '-' . $userData['documento_id'];

ob_start();
?>
<div class="container">
    <div class="profile-strip">
        <div class="profile-avatar"><?php echo htmlspecialchars($iniciales); ?></div>
        <div class="profile-main">
            <div class="profile-name"><?php echo htmlspecialchars($userData['nombre_completo']); ?></div>
            <div class="profile-doc mono"><?php echo htmlspecialchars($documentoCompleto); ?></div>
        </div>
        <div class="profile-stats">
            <div class="profile-stat">
                <span class="profile-stat-label">Teléfono</span>
                <span class="profile-stat-value"><?php echo htmlspecialchars($userData['telefono'] ?: 'No registrado'); ?></span>
            </div>
            <div class="profile-stat">
                <span class="profile-stat-label">Correo</span>
                <span class="profile-stat-value"><?php echo htmlspecialchars($userData['correo'] ?: 'No registrado'); ?></span>
            </div>
            <div class="profile-stat">
                <span class="profile-stat-label">Institución</span>
                <span class="profile-stat-value"><?php echo htmlspecialchars($userData['cargo_actual'] ?: 'No registrada'); ?></span>
            </div>
        </div>
    </div>

    <p style="font-size:12.5px;color:var(--ink-faint);margin-bottom:20px;">
        Tus datos básicos (nombre, teléfono, correo, institución) los administra Grupo Electrón.
        Si necesitas corregir alguno, escríbenos por soporte. Desde aquí solo puedes cambiar tu contraseña.
    </p>

    <div class="card" style="max-width:460px;">
        <h3>Cambiar Contraseña</h3>
        <?php if ($error): ?>
        <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>
        <form method="POST" action="" id="cambiarPasswordForm">
            <div class="form-group">
                <label for="password_actual">Contraseña Actual</label>
                <input type="password" id="password_actual" name="password_actual" class="form-control" required
                       placeholder="Tu contraseña actual" autocomplete="current-password">
            </div>
            <div class="form-group">
                <label for="password_nueva">Nueva Contraseña</label>
                <input type="password" id="password_nueva" name="password_nueva" class="form-control" required
                       placeholder="Mínimo 6 caracteres" minlength="6" autocomplete="new-password">
            </div>
            <div class="form-group">
                <label for="password_repetir">Repetir Nueva Contraseña</label>
                <input type="password" id="password_repetir" name="password_repetir" class="form-control" required
                       placeholder="Repite la nueva contraseña" minlength="6" autocomplete="new-password">
            </div>
            <button type="submit" class="btn btn-primary btn-block">Actualizar Contraseña</button>
        </form>
    </div>
</div>
<script>
document.getElementById('cambiarPasswordForm').addEventListener('submit', function (e) {
    var nueva = document.getElementById('password_nueva').value;
    var repetir = document.getElementById('password_repetir').value;
    if (nueva !== repetir) {
        e.preventDefault();
        alert('La nueva contraseña y su confirmación no coinciden.');
        return false;
    }
    if (nueva.length < 6) {
        e.preventDefault();
        alert('La nueva contraseña debe tener al menos 6 caracteres.');
        return false;
    }
});
</script>
<?php
$content = ob_get_clean();
include VIEWS_PATH . '/layout.php';
?>
