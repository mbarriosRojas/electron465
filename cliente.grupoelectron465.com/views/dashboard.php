<?php
$pageTitle = 'Dashboard';
$showHeader = true;

if (!isset($userData) || !$userData) {
    header('Location: ' . url('login'));
    exit;
}

$factura = new Factura($db);
$facturas = $factura->getFacturasActivas($userData['documento_id']);

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

    <div class="section-heading">
        <h2>Mis Facturas Activas</h2>
        <?php if (!empty($facturas)): ?>
        <span class="count"><?php echo count($facturas); ?> factura<?php echo count($facturas) === 1 ? '' : 's'; ?> con saldo pendiente</span>
        <?php endif; ?>
    </div>

    <?php if (empty($facturas)): ?>
    <div class="no-facturas">
        <p>No tienes facturas activas en este momento.</p>
    </div>
    <?php else: ?>
    <div class="facturas-grid">
        <?php foreach ($facturas as $fact):
            $avance = $fact['total'] > 0 ? min(100, ($fact['pagado'] / $fact['total']) * 100) : 0;
        ?>
        <div class="factura-card" onclick="window.location.href='<?php echo url('factura/' . $fact['numero_factura']); ?>'">
            <div class="factura-card-head">
                <span class="factura-card-title">Factura #<?php echo htmlspecialchars($fact['numero_factura']); ?></span>
                <span class="chip chip-neutral"><?php echo htmlspecialchars($fact['modalidad']); ?></span>
            </div>
            <div class="factura-card-meta">
                <span>📅 <?php echo date('d/m/Y', strtotime($fact['fecha_solicitud'])); ?></span>
                <span>🏦 <?php echo htmlspecialchars($fact['banco'] ?: 'N/A'); ?></span>
            </div>
            <div>
                <div class="factura-progress-track">
                    <div class="factura-progress-fill" style="width: <?php echo number_format($avance, 1); ?>%;"></div>
                </div>
                <div class="factura-progress-label">
                    <span>Pagado $USD <?php echo number_format($fact['pagado'], 2, ',', '.'); ?></span>
                    <span><?php echo number_format($avance, 0); ?>%</span>
                </div>
            </div>
            <div class="factura-card-amounts">
                <span class="amount-label">Resta por pagar</span>
                <span class="amount-value mono">$<?php echo number_format($fact['resta'], 2, ',', '.'); ?></span>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>
<?php
$content = ob_get_clean();
include VIEWS_PATH . '/layout.php';
?>
