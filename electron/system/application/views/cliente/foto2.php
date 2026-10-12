<script type="text/javascript" src="<?php echo __JSVIEW__ ?>view/webcam.js"></script>
<title>Foot Cliente</title>
<style>
	#modal {
		position: absolute;
		padding: 0;
		margin: 0;
		width: 100%;
		height: 100%;
		z-index: 50;
		filter: alpha(opacity=0);
		opacity: 0.9;
		-moz-opacity: 0.8;
		-webkit-opacity: 0.8;
		-o-opacity: 0.8;
		-ms-opacity: 0.8;
		background-color: #444;
		left: 0;
		top: 0;
		overflow: auto;
	}
	.contenedor {
		width: 500px;
		background: #ccc;
		position: relative;
		margin: 10% auto;
		padding: 30px;
		-moz-border-radius: 7px;
		border-radius: 7px;
		-webkit-box-shadow: 0 3px 20px rgba(0,0,0,0.9);
		-moz-box-shadow: 0 3px 20px rgba(0,0,0,0.9);
		box-shadow: 0 3px 20px rgba(0,0,0,0.9);
		background: -moz-linear-gradient(#fff);
		background: -webkit-gradient(linear, right bottom, right top, color-stop(1, rgb(255,255,255)), color-stop(0.57,       rgb(230,230,230)));
		text-shadow: 0 1px 0 #fff;
	}
	
	.contenedor a[href="#close"] {
		position: absolute;
		right: 0;
		top: 0;
		color: transparent;
	}
	.contenedor a[href="#close"]:focus {
		outline: none;
	}
	.contenedor a[href="#close"]:after {
		content: 'X';
		display: block;
		position: absolute;
		right: -10px;
		top: -10px;
		width: 1.5em;
		padding: 1px 1px 1px 2px;
		text-decoration: none;
		text-shadow: none;
		text-align: center;
		font-weight: bold;
		background: #000;
		color: #fff;
		border: 3px solid #fff;
		-moz-border-radius: 20px;
		border-radius: 20px;
		-webkit-box-shadow: 0 1px 3px rgba(0,0,0,0.5);
		-moz-box-shadow: 0 1px 3px rgba(0,0,0,0.5);
		box-shadow: 0 1px 3px rgba(0,0,0,0.5);
	}
	.contenedor a[href="#close"]:focus:after, .contenedor a[href="#close"]:hover:after {
		-webkit-transform: scale(1.1,1.1);
		-moz-transform: scale(1.1,1.1);
	}
</style>
<script>
	var sView = sUrl + '/system/application/views/cliente/webcam.swf'
	var sImgC = sUrl + '/system/repository/expedientes/fotos/';
	
</script>
<a href="#close" title="Cerrar" onclick='$("#tomar_foto").hide();$("#modal").hide();' ></a>
<table>
	<tr>
		<td valign=top><h2>Foto Cliente:</h2><!-- First, include the JPEGCam JavaScript Library -->
		<script language="JavaScript">
			webcam.set_api_url(sUrlP + 'subir_foto');
			webcam.set_quality(90);
			webcam.set_swf_url(sView);
			// JPEG quality (1 - 100)
			webcam.set_shutter_sound(false);
			// play shutter click sound
		</script><!-- Next, write the movie to the page at 320x240 -->
		<script language="JavaScript">
			document.write(webcam.get_html(220, 180));
		</script><!-- Some buttons for controlling things -->
		<br/>
		
		<form>
			<br>

			<input type='button' class='btn btnrefresh' value='Configurar Camara' onClick="webcam.configure()">
			<input type='button' class='btn btnadd' value='Tomar Fotografia' onClick="take_snapshot()">
			<br>
			<br>
			&nbsp;&nbsp;
		</form><!-- Code to handle the server response (see test.php) -->
		<script language="JavaScript">
			webcam.set_hook('onComplete', 'my_completion_handler');

			function take_snapshot() {
				// take snapshot and upload to server
				document.getElementById('upload_results').innerHTML = '<h2>Tomando foto....</h2>';
				var cedula = $("#txtCedula").val();
				if (cedula == '') {
					alert('Ingrese Un numero De Cedula');
					return 0;
				} else {
					webcam.set_api_url(sUrlP + 'subir_foto/' + cedula);
				}
				webcam.snap();
				$("#cedula").val('');
			}

			function my_completion_handler(msg) {
				// extract URL out of PHP output
				if (msg.match(/(http\:\/\/\S+)/)) {
					var cedula = $("#txtCedula").val();
					var image_url = RegExp.$1;
					// show JPEG image in page
					document.getElementById('upload_results').innerHTML = '<h2>Foto capturada!</h2>';
					//'<h3>JPEG URL: ' + image_url + '</h3>' +
					//'<img src="' + image_url + '">';

					// reset camera for another shot
					webcam.reset();
					$("#tomar_foto").hide();

					var foto = sImgFoto + cedula + '.jpg';
					$("#foto").attr('src', foto);
					$('#foto').attr('src', $('#foto').attr('src') + '?' + Math.random());
					$("#tomar_foto").hide();
					$("#modal").hide();

				} else
					alert(msg);
				webcam.reset();
				$("#tomar_foto").hide();
			}
		</script></td><td>&nbsp;</td><td valign=top><div id="upload_results" style="background-color:transparent; "></div></td>
	</tr>
</table>