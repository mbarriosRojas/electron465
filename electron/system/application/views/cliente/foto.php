<?php ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">

<html lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<link rel="stylesheet" href="<?php echo __CSS__ ?>cam.css" type="text/css" media="screen" />
		<script type="text/javascript" src="<?php echo __JSVIEW__ ?>view/webcam.js"></script>
		<title>Foot Cliente</title>
		<script>
			var sUrl = 'http://' + window.location.hostname + '/cooperativa';
			var sUrlP = sUrl + '/index.php/cooperativa/';
			var sImg = sUrl + '/system/img/';
			var sView = sUrl + '/system/application/views/cliente/webcam.swf'
			var sImgC = sUrl + '/system/repository/expedientes/fotos/';
		</script>
	</head>
	<body>
		<center>
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
						<input type="text" id='cedula' name='cedula' value='<?php echo $ced;?>' placeholder="Cedula"/><br><br>
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
							var cedula = $("#cedula").val();
							alert(cedula);
							if(cedula == ''){
								alert('Ingrese Un numero De Cedula');
								return 0;
							}else{
								webcam.set_api_url(sUrlP + 'subir_foto/'+cedula);
							}
							webcam.snap();
							$("#cedula").val('');
						}

						function my_completion_handler(msg) {
							// extract URL out of PHP output
							if (msg.match(/(http\:\/\/\S+)/)) {
								var image_url = RegExp.$1;
								// show JPEG image in page
								document.getElementById('upload_results').innerHTML = '<h2>Foto capturada!</h2>' +
								//'<h3>JPEG URL: ' + image_url + '</h3>' +
								'<img src="' + image_url + '">';

								// reset camera for another shot
								webcam.reset();
							} else
								alert(msg);
								webcam.reset();
						}
					</script></td><td>&nbsp;</td><td valign=top><div id="upload_results" style="background-color:transparent; "></div></td>
				</tr>
			</table>
		</center>
	</body>
</html>
