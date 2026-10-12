<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle . ' - ' : ''; ?>Portal de Usuarios - Grupo Electrón</title>
    <link rel="stylesheet" href="<?php echo url('assets/css/style.css'); ?>">
</head>
<body>
    <?php if (isset($showHeader) && $showHeader): ?>
    <div class="app-shell">
        <aside class="sidebar" id="appSidebar">
            <div class="sidebar-brand">
                <img src="<?php echo url('assets/images/logo.png'); ?>" alt="Grupo Electrón">
                <div class="sidebar-brand-text">
                    Grupo Electrón
                    <small>Portal de Clientes</small>
                </div>
            </div>
            <nav class="sidebar-nav">
                <span class="nav-section-label">Mi cuenta</span>
                <a href="<?php echo url('dashboard'); ?>" class="nav-item<?php echo ($pageTitle === 'Dashboard') ? ' is-active' : ''; ?>">
                    <span class="nav-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M4 13h6V4H4v9Zm0 7h6v-5H4v5Zm10 0h6V11h-6v9Zm0-16v5h6V4h-6Z" fill="currentColor"/></svg>
                    </span>
                    Mis Facturas
                </a>
                <a href="<?php echo url('perfil'); ?>" class="nav-item<?php echo ($pageTitle === 'Mi Perfil') ? ' is-active' : ''; ?>">
                    <span class="nav-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M12 12a5 5 0 1 0 0-10 5 5 0 0 0 0 10Zm0 2c-4.4 0-8 2.24-8 5v2h16v-2c0-2.76-3.6-5-8-5Z" fill="currentColor"/></svg>
                    </span>
                    Mi Perfil
                </a>
                <span class="nav-item is-disabled">
                    <span class="nav-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M4 4h16v12H7l-3 3V4Z" fill="currentColor"/></svg>
                    </span>
                    Solicitudes
                    <span class="nav-item-badge">Pronto</span>
                </span>
                <span class="nav-section-label">Ayuda</span>
                <span class="nav-item is-disabled">
                    <span class="nav-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M12 2a10 10 0 1 0 0 20 10 10 0 0 0 0-20Zm1 15h-2v-2h2v2Zm0-3.5V13h-2v-1a3 3 0 0 1 1-2.1l.6-.5A1.7 1.7 0 0 0 13.3 8a1.3 1.3 0 0 0-2.2-.9l-.9-1.4A3.3 3.3 0 0 1 12.9 5a3.2 3.2 0 0 1 3.2 3.2c0 1.05-.5 1.7-1.3 2.3l-.5.4A1.4 1.4 0 0 0 13.7 12" stroke="currentColor" stroke-width="1.4" fill="none"/></svg>
                    </span>
                    Soporte
                    <span class="nav-item-badge">Pronto</span>
                </span>
            </nav>
            <div class="sidebar-footer">
                <div class="sidebar-user">
                    <div class="avatar-chip"><?php echo isset($userData) && $userData ? strtoupper(substr($userData['primer_nombre'], 0, 1) . substr($userData['primer_apellido'], 0, 1)) : '?'; ?></div>
                    <div style="min-width:0;">
                        <div class="sidebar-user-name"><?php echo isset($userData) && $userData ? htmlspecialchars($userData['nombre_completo']) : ''; ?></div>
                        <div class="sidebar-user-doc"><?php echo isset($userData) && $userData ? htmlspecialchars(rtrim($userData['nacionalidad'] ?: 'V', '-') . '-' . $userData['documento_id']) : ''; ?></div>
                    </div>
                </div>
                <a href="<?php echo url('logout'); ?>" class="nav-item" style="margin-top:6px;">
                    <span class="nav-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M10 17v-2H3v-6h7V7l5 5-5 5Zm9 3H12v-2h7V6h-7V4h7a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2Z" fill="currentColor"/></svg>
                    </span>
                    Cerrar Sesión
                </a>
            </div>
        </aside>

        <div class="app-main">
            <header class="topbar">
                <div style="display:flex;align-items:center;gap:14px;">
                    <button type="button" class="sidebar-toggle" onclick="document.getElementById('appSidebar').classList.toggle('is-open');" aria-label="Abrir menú">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M4 6h16M4 12h16M4 18h16" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                    </button>
                    <span class="topbar-title"><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) : ''; ?></span>
                </div>
                <div class="topbar-actions">
                    <?php if (isset($userData) && $userData): ?>
                    <span class="user-name" style="color:var(--ink-soft);font-size:13px;font-weight:600;"><?php echo htmlspecialchars($userData['nombre_completo']); ?></span>
                    <?php endif; ?>
                </div>
            </header>
            <main class="main-content">
                <?php echo $content; ?>
            </main>
        </div>
    </div>
    <?php else: ?>
    <main class="main-content">
        <?php echo $content; ?>
    </main>
    <?php endif; ?>

    <script src="<?php echo url('assets/js/main.js'); ?>"></script>
</body>
</html>
