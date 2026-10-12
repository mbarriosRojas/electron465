<?php
$pageTitle = 'Página No Encontrada';
$showHeader = false;

ob_start();
?>
<div class="login-container">
    <div class="login-card">
        <h2>404 - Página No Encontrada</h2>
        <p>La página que buscas no existe.</p>
        <a href="<?php echo url('dashboard'); ?>" class="btn btn-primary">Volver al Dashboard</a>
    </div>
</div>
<?php
$content = ob_get_clean();
include VIEWS_PATH . '/layout.php';
?>

