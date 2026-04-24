<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title><?php echo __TITLE__ ?></title>
	<meta name="keywords" content="Cooperativa Electron, Consultar Pagos, Creditos y Prestamos, Prestamo de dinero" >
	<meta name="description" content="Cooperativa Electron, te da la oportunidad de: Consultar historial de pagos, Imprimir Constancias."/> 
	<link rel='stylesheet' href='<?php echo __CSS__ ?>login.css'>
 	
	</head>
	<body>
<div class="capa">

<input type="hidden" id="dest_uri" value="/" />

<div id="login-wrapper" class="login-whisp">
    <div id="notify">
        <div id='login-status' class="error-notice" style="visibility: hidden">
            <span class='login-status-icon'></span>
            <div id="login-status-message">Ha finalizado la sesión</div>
        </div>
    </div>

    <div style="display:none">
        <div id="locale-container" style="visibility:hidden">
            <div id="locale-inner-container">
                <div id="locale-map">
                    <div class="scroller clear">
                        
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="content-container">
        <div id="login-container">
            <div id="login-sub-container">
                <div id="login-sub-header">
                    <img src="<?php echo __IMG__;?>whm.png" alt="logo" />
                </div>
                <div id="login-sub">
                    <div id="forms">
                        <form id="login_form" action="<?php echo __LOCALWWW__ ?>/index.php/cooperativa/verificacion " method="post" target="_top">
                            <div class="input-req-login"><label for="user"><img src="<?php echo __IMG__;?>usuario.png"></img>&nbsp;Nombre de Usuario</label></div>
                            <div class="input-field-login icon username-container">
                                <input name="txtUsuario" id="user" autofocus="autofocus" value="" placeholder="Introduzca su nombre de usuario." class="std_textbox" type="text"  tabindex="1" required>
                            </div>
                            <div style="margin-top:30px;" class="input-req-login"><label for="pass"><img src="<?php echo __IMG__;?>clave.png"></img>&nbsp;Contraseña</label></div>
                            <div class="input-field-login icon password-container">
                                <input name="txtClave" id="pass" placeholder="Ingrese su contraseña de la cuenta." class="std_textbox" type="password" tabindex="2"  required>
                            </div>
                            <div style="width: 285px;">
                                <div class="login-btn">
                                    <button name="login" type="submit" id="login_submit" tabindex="3">Acceder</button>
                                </div>

                             </div>
                            <div class="clear" id="push"></div>
                        </form>

                    <!--CLOSE forms -->
                    </div>

                <!--CLOSE login-sub -->
                </div>
            <!--CLOSE login-sub-container -->
            </div>
        <!--CLOSE login-container -->
        </div>

    </div>
<!--Close login-wrapper -->
</div>
<div class="copyright">Cooperativa Electron | Grupo Electron</div>
</div>
</body>

</html>