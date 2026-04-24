<?php
	$ruta_imagen = BASEPATH."img/usuarios/".trim($this->session->userdata('usuario')).".png";
	if(file_exists($ruta_imagen)){
		$ruta_imagen = __IMG__."usuarios/".trim($this->session->userdata('usuario')).".png";
	}else{
		$ruta_imagen = __IMG__."usuarios/default.jpg";
	}
?>
<img src="<?php echo $ruta_imagen ?>" style="width:60px"><br><label id='lbl_usu'><?php echo $this->session->userdata('usuario');?></label>

	<br><br>	
	<a href="http://electron465.com/webmail" target="_blank"><img src="<?php echo __IMG__."botones/sobre.png" ?>" style="width:60px"><br>Correo.</a>
	<br><br>
	<div id="general" style="position: relative;width:160px;">
	<h1><a href="#">Noticias Informativas</a></h1>
		<div style="height: 350px;">
			<div style="height: 350px;">
			<marquee  behavior="scroll" direction="up" scrolldelay=250 style="height: 350px;">
				<blink>* SE NOTIFICA QUE </blink><p style="color:green;">A PARTIR DE HOY 13/02/2014 QUEDAN SUSPENDIDOS LOS CREDITOS PARA LA FUERZA ARMADA HASTA NUEVO AVISO.
				 
			</p><br><br>
				<p style="color:red;"> * A PARTIR DE HOY 18-09-2013 SE SUSPENDEN LAS VENTAS AL CONTADO HASTA NUEVO AVISO</p><BR>
			
				<br><br>
			<blink>* SE NOTIFICA QUE </blink><p style="color:red;">
				SE LE NOTIFICA QUE A PARTIR DEL 14 DE MARZO 2013, LAS VENTAS AL CONTADO PARA MOTOS ESTAN SUSPENDIDAS, SOLAMENTE LAS DISPONIBLES SERAN PARA CREDITOS POR FINANCIAMIENTO.
				<br><br>
				A PARTIR DEL 01-03-2013 QUEDAN SUSPENDIDOS LOS CREDITOS A CLIENTES PERTENECIENTES A LA NOMINA GOBERNACION DEL ESTADO ZULIA SECTOR SALUD, 
				YA QUE EXISTE DIFICULTAD AL MOMENTO DE EFECTUAR LOS COBROS 
				</p><br><br>
			<p style="color:red;">* POR FAVOR, ANTES DE OTORGAR CREDITOS AL PERSONAL DE : MINISTERIO DE SALUD JUBILADOS Y ACTIVOS DE LA GOBERNACION DEL ZULIA, BANCO BOD, COMUNICARSE CON LA OFICINA PRINCIPAL 
				</p><br><br>
			
			* Los Cr&eacute;ditos Para Los Contratados de la ULA y Coorposalud, Actualmente Suspendido, Att. Direcci&oacute;n de Cobranza, Yuli Marquez<br><br> 
			* Los cr&eacute;ditos por el Banco Banesco estan suspendidos para el ministerio de la Defensa <br><br> 
			</marquee>
			</div>
		</div>
	
	</div>
	
	<!-- <audio controls="false" hidden="true" id = 'sonido_alerta'>
  		<source src="<?php echo __LOCALWWW__.'/system/application/views/menu/msj.ogg';?>"  />
	 </audio> --> 