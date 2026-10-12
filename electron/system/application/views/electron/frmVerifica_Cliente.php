<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<!--
/*
* @author: Mauricio de Jesus Barrios Rojas (GrupOdin)
* @version 3.0.0
*/
-->
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW">

<title>Electron</title>
<style type="text/css">
	#contiene {
		width: 100%;
		height: 100%;
	}
	#caja2 {

		position: absolute;
		float: right;
		left: 8%;
		top: 30%;
		width: auto;
	}

	#caja3 {
		filter: alpha(opacity=95);
		-moz-opacity: .95;
		opacity: .95;
		position: absolute;
		float: right;
		left: 61%;
		top: 30%;
		-moz-border-radius: 5px;
		-webkit-border-radius: 5px;
		border-radius: 5px;
		width: auto;
		border: solid 1px #fff;
	}
	#caja4 {
		font-family: Arial, Trebuchet MS, Tahoma, Verdana, sans-serif;
		font-size: 14px;
		color: #FFFFFF;
		text-shadow: 1px 1px 1px #3B3B3B;
		position: absolute;
		float: right;
		left: 70%;
		top: 85%;
		width: auto;
	}
	#caja4 #registrate a {
		color: #fff;
		text-shadow: 1px 1px 1px #09387E;
		text-decoration: none;
	}
	#caja4 #registrate a:hover {
		color: #09387E;
		text-shadow: 1px 1px 1px #fff;
		text-decoration: none;
	}

	#registration {
		position: relative;
		float: right;
		top: 20%;
		color: #fff;
		background: #2d2d2d;
		background: -webkit-gradient(
		linear,
		left bottom,
		left top,
		color-stop(0, rgb(60,60,60)),
		color-stop(0.74, rgb(43,43,43)),
		color-stop(1, rgb(60,60,60))
		);
		background: -moz-linear-gradient(
		center bottom,
		rgb(60,60,60) 0%,
		rgb(43,43,43) 74%,
		rgb(60,60,60) 100%
		);
		-moz-border-radius: 5px;
		-webkit-border-radius: 5px;
		border-radius: 5px;
		margin: 10px;
		width: 275px;
	}

	#registration a {
		color: #8c910b;
		text-shadow: 0px -1px 0px #000;
	}

	#registration fieldset {
		padding: 20px;
	}

	input.text {
		-webkit-border-radius: 5px;
		-moz-border-radius: 5px;
		border-radius: 5px;
		font-size: 12px;
		width: 90%;
		padding: 7px 8px 7px 30px;
		-moz-box-shadow: 0px 1px 0px #777;
		-webkit-box-shadow: 0px 1px 0px #777;center bottom,
		rgb(225,225,225) 0%,
		rgb(215,215,215) 54%,
		rgb(173,173,173) 100%
		);
		color: #333;
		text-shadow: 0px 1px 0px #FFF;
	}
	input.login-btn {
		-webkit-border-radius: 5px;
		-moz-border-radius: 5px;
		border-radius: 5px;
		font-size: 12px;
		width: 90%;
		padding: 7px 8px 7px 30px;
		-moz-box-shadow: 0px 1px 0px #777;
		-webkit-box-shadow: 0px 1px 0px #777;center bottom,
		rgb(225,225,225) 0%,
		rgb(215,215,215) 54%,
		rgb(173,173,173) 100%
		);
		color: #333;
		text-shadow: 0px 1px 0px #FFF;
	}
	input#txtCuenta {
		background-position: 4px -20px;
		background-position: 4px -20px, 0px 0px;
	}

	input#txtCliente {
		background-position: 4px -46px;
		background-position: 4px -46px, 0px 0px;
	}

	#registration h2 {
		color: #fff;
		text-shadow: 0px -1px 0px #000;
		border-bottom: solid #181818 1px;
		-moz-box-shadow: 0px 1px 0px #3a3a3a;
		text-align: center;
		padding: 18px;
		margin: 0px;
		font-weight: normal;
		font-size: 24px;
		font-family: Lucida Grande, Helvetica, Arial, sans-serif;
	}

	#registration p {
		position: relative;
	}
	#registration #txtCuenta {
		width: 180px;
	}
	#registration #txtCliente {
		width: 180px;
	}
	#contiene {
		width: 100%;
		height: 100%;
	}
	nav {
		width: 220px;
		padding: 20px 20px 20px 20px;
	}

	#login_submit {
		background: linear-gradient(#7AB5F9, #1C4E97);
		color: #fff;
		width: 220px;
		height: 34px;
		float: right;
		text-shadow: 1px 1px 1px #09387E;margin 10px 50px 10px 50px;
		padding: 5px 8px 10px 5px;
	}
	h2 {
		font-family: Arial, Trebuchet MS, Tahoma, Verdana, sans-serif;
		color: #fff;
		padding: 1px 1px 1px 1px;
		text-shadow: 1px 1px 1px #000;#09387E
	}
	h4 {
		font-family: Arial, Trebuchet MS, Tahoma, Verdana, sans-serif;
		color: #fff;
		padding: 1px 1px 1px 1px;
		text-shadow: 1px 1px 1px #000;
	}
	h5 {
		font-family: Arial, Trebuchet MS, Tahoma, Verdana, sans-serif;
		color: #fff;
		font-style: italic;
		padding: 1px 1px 1px 1px;
		text-shadow: 1px 1px 1px #000;
	}
	#recuerde {
		font-size: 9px;
		color: #fff;
		text-align: justify;
	}

	#bg {
		position: fixed;
		z-index: -1;
		top: 0;
		left: 0;
		width: 100%;
	}
</style>
<script type="text/javascript" src="<?php echo __JSVIEW__ ?>jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="<?php echo __JSVIEW__ ?>jquery-ui-1.8.6.custom.min.js"></script>

<script>
function updateBackground() {
	screenWidth = $(window).width();
	screenHeight = $(window).height();
	var bg = jQuery("#bg");

	// Proporcion horizontal/vertical. En este caso la imagen es cuadrada
	ratio = 1;

	if (screenWidth/screenHeight > ratio) {
		$(bg).height("auto");
		$(bg).width("100%");
	} else {
		$(bg).width("auto");
		$(bg).height("100%");
	}

	// Si a la imagen le sobra anchura, la centramos a mano
	if ($(bg).width() > 0) {
		$(bg).css('left', (screenWidth - $(bg).width()) / 2);
	}
}

$(document).ready(function() {
	// Actualizamos el fondo al cargar la pagina
	updateBackground();
	$(window).bind("resize", function() {
	// Y tambien cada vez que se redimensione el navegador
	updateBackground();
	});
});

function mostrardiv() {
	$('#flotante').show();
}
function cerrar() {
	$('#flotante').hide();
}
</script>
</head>
<body>
	<img src="<?php echo __IMG__; ?>background.jpg" alt="Fondo" id="bg">
	<div id="contiene">

		<div id="caja2">
			<h2>Bienvenido</h2>
			<h4>Ahora Puedes Ver Tus Estados De Cuenta
			<br>
			y hacer tus Solitudes en linea fac&iacute;l y comodo.</h4>
		</div>
		<div id="caja3">
			<div id="registration">
				<form action="<?php echo base_url() . "index.php/electron/Verifica_Cliente"; ?>" method="POST">
					<nav>
						<p>
							<input id="txtCliente" name="txtCliente" class="text" type="text" placeholder="Usuario">
						</p>
						<p>
							<input id="txtCuenta" name="txtCuenta" class="text" type="password" placeholder="Contraseña">
						</p>
						<p><center><img width="100" src="<?php echo __IMG__ . "capcha/" . $capcha . ".jpg"; ?>" /></center></p>
						<p><input type="text" class="text" name="capcha" id="capcha" maxlength="6" size="6"/></p>
						<p>
							<input  id="login_submit" name="sumit" class="login-btn" type="submit" value="INGRESAR ">
						</p>
						
						<br>
						<br>

						
						<p id="recuerde">
							Recuerde Que El Usuario Es El N&uacute;mero De C&eacute;dula De Identidad
							Y La Contraseña Sus 4 Ultimos Digitos De Su Cuenta Bancaria
						</p>
					</nav>
				</form>
			</div>

		</div>
		
	</div>
</body>
</html>