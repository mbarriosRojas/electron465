<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title><?php echo __TITLE__ ?></title>
	
	<meta name="keywords" content="Cooperativa Electron, Consultar Pagos, Creditos y Prestamos, Prestamo de dinero" >
	<meta name="description" content="Cooperativa Electron, te da la oportunidad de: Consultar historial de pagos, Imprimir Constancias."/> 
	<link href="<?php echo __CSS__ ?>__style.css.php?url=<?php echo __LOCALWWW__ ?>" rel="stylesheet" type="text/css" />

	
	<link type="text/css" href="<?php echo __CSS__ ?>ui-lightness/jquery-ui-1.8.6.custom.css" rel="stylesheet" />
	
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>jquery-1.4.2.min.js"></script>
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>jquery-ui-1.8.6.custom.min.js"></script>

	<script type="text/javascript">
		$(function() {

		});

		/*Funcion para que el formulario solo acepte numeros*/
		function ValNumero(elemento) {
			if (!/^([0-9])*$/.test(elemento.value)){
      			elemento.value = "";
			}
		}

		
	</script>
	<style type="text/css">
		/*
		 *  @author Mauricio Barrios
		 *  @revisado por: Judelvis Rivas & Carlos Pe񡍊		 *  @version 2.0.0
		 */
		body {
			height: 100%;
			background: #1E6381;
			font-family: helvetica, arial, sans-serif;
		}
		.clear {
			clear: both;
		}
		#login-container {
			border: 1px solid #0431B2;
			top: 130px;
			border-radius: 8px;
			-moz-border-radius: 8px;
			-webkit-border-radius: 8px;
			height: 330px;
			margin: 0 auto;
			position: relative;
			width: 246px;
		}
		#login-sub-container {
			border: 1px solid #333;
			border-color: rgba(0,0,0,0.2);
			border-radius: 10px;
			-moz-border-radius: 10px;
			-webkit-border-radius: 10px;
			-khtml-border-radius: 10px;
			box-shadow: 0 0 10px #000 inset;
			height: 318px;
			left: 5px;
			position: absolute;
			top: 5px;
			width: 235px;
		}
		#login-sub-header {
			background-color: #0431B2;
			background: -webkit-gradient(linear, 0 0, 0 100%, from(#472710),  to(#966D42));
			filter: progid :DXImageTransform.Microsoft.gradient(startColorstr=#c002435d,endColorstr=#c002435d);
			border-bottom: 1px solid #0431B2;
			border-radius: 5px 5px 0 0;
			-moz-border-radius: 5px 5px 0 0;
			-webkit-border-radius: 5px 5px 0 0;
			-khtml-border-radius: 5px 5px 0 0;
			height: 65px;
			padding: 5px 0 0;
			text-align: center;
		}
		#login-sub, #security-sub {
			background: #fff;
			filter: progid :DXImageTransform.Microsoft.gradient(startColorstr=#c0035271,endColorstr=#c0035271);
			border-radius: 0 0 5px 5px;
			-moz-border-radius: 0 0 5px 5px;
			-webkit-border-radius: 0 0 5px 5px;
			-khtml-border-radius: 0 0 5px 5px;
			border-top: 1px solid #442602;
			height: 212px;
			padding-left: 30px;
			padding-top: 30px;
			color: white;
			font-size: 12px;32
			padding-right: 5px;
		}
		.textocam {
			color: #646464;
			font-size: 12px;
		}
		.nota {
			color: #646464;
			font-size: 10px;
		}

		button, button[disabled]:active, input[type=submit], input[type=submit][disabled]:active, input[type=button], input[type=button][disabled]:active, .login-btn a.loginbtn {
			background: #0431B2;
			behavior: url(/cPanel_magic_revision_1338323778/unprotected/cjt/ie9gradients.htc);
			background-image: -webkit-gradient(linear,left top,left bottom,from(#1090b0),to(#096479));
			filter: progid :DXImageTransform.Microsoft.Gradient(StartColorStr='#1090b0',EndColorStr='#096479',GradientType=0);
			border: 1px solid #966D42;
			-moz-box-shadow: 0 0 2px 2px #fff;
			-webkit-box-shadow: 0 0 2px 2px #fff;
			text-shadow: 0 1px 0 #063A46;
			border-radius: 3px;
			-moz-border-radius: 3px;
			-webkit-border-radius: 3px;
			-khtml-border-radius: 3px;
			border-radius: 3px;
			color: #FFF;
			cursor: pointer;
			font-size: 12px;
			font-weight: bold;
			padding: 4px 10px;
			width: auto;
			overflow: visible;
		}
		.login-btn, .reset-pass-btn {
			float: left;
			padding-top: 10px;
		}
		resetpass .login-btn {
			padding-top: 0;
		}
	</style>	
</head>
<body>
    <div id="content-containers">
        <div id="login-container">
            <div id="login-sub-container">
                <div id="login-sub-header">
                    <img src="<?php echo __IMG__; ?>logo.png" alt="logo" />
                </div>
                <div id="login-sub">
                	<div id="forms">
                        <form action="<?php echo base_url() . "index.php/cooperativa/Verifica_Cliente"; ?>" method="POST">           
                            <div class="textocam"><img src="<?php echo __IMG__; ?>usuario.png"><label for="user">&nbsp;C&eacute;dula</label></div>
                            <div><input name="txtCliente" id = "txtCliente" type="text" placeholder="C&eacute;dula" onkeyUp="ValNumero(this);" maxlength=8 alt="CEDULA"></div>
                            <br>
                            <div class="textocam"><img src="<?php echo __IMG__; ?>clave.png"><label for="user"></label>&nbsp;Clave</div>
                            <div><input name="txtCuenta"  type="text" alt="Ingrese clave" id="txtCuenta" onkeyUp="ValNumero(this);" maxlength=4 placeholder="* * * *"></div>
                            <br>
                            <div style="width: 285px;">
                                <div class="login-btn">
                                    <button name="login" type="submit" id="login_submit" tabindex="3">Consultar</button>
                                </div>

                             </div>
                            <div class="clear" id="push"></div>
                            <br>
                            <label class="nota">Nota: Recuerde que su clave para el ingreso del sistema, son los cuatro (4) ultimos digitos de su n&uacute;cuenta.</label>
                        </form>
                    </div>
                </div>
            </div>
        </div>
	</div>
</body>
