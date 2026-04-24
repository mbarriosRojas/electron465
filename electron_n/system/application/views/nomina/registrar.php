
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	
  <title><?php echo __TITLE__ ?></title>
	<link href="<?php echo __CSS__ ?>__style.css.php?url=<?php echo __LOCALWWW__ ?>" rel="stylesheet" type="text/css">
	<link href="<?php echo __CSS__ ?>humanity/jquery-ui-1.8.20.custom.css" rel="stylesheet"  type="text/css">
	<link href="<?php echo __CSS__ ?>chat.css" type="text/css" rel="stylesheet" />
	<link href="<?php echo __CSS__ ?>screen.css" type="text/css" rel="stylesheet"/>
	<link href="<?php echo __CSS__ ?>menu_assets/styles.css" rel="stylesheet" type="text/css">
	
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>jquery/jquery-1.4.2.min.js"></script>
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>jquery/jquery-ui-1.8.6.custom.min.js"></script>
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>chat/chat.js"></script>
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>general/Global.js"></script>
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>view/registrar.js"></script>
	
	<style type="text/css">
		#dialog_link {padding: .1em 1em .20em 6px;text-decoration: none;position: relative;}
		#dialog_link span.ui-icon {margin: 5px 0 0;position: absolute;center: 1em;top: 1%;margin-top: 10px;}
	</style>	
	
</head>
<body >
	<div id="cabecera"><br>
		<?php $this->load->view(__NFORMULARIOS__ . "frmbuscar.php");?>
		<?php echo $Menu;?>
	</div>
	<div id="medio" >	
<div id="msj_alertas"></div>
					<h2 class="demoHeaders">	Planilla General de Clientes </h2><br>
					<h3>Usuario Connectado: <?php echo $this->session->userdata('usuario'); ?> Fecha: <?php echo date("Y/m/d"); ?></h3><br>					
							<div id="dialog-modal" title="Mensaje de Error"></div>														
							<div class="ui-widget" id="DivLista-Verificados"></div>
							<div id="divMuestraListaVerificados" title="Estado de Consultas">
								<center>
									<div id="divMuestraListaVerificadosInterno"></div>
								</center>
							</div>
							
							<div class="ui-widget" style="display:none;" id='divGuardar'>
								<div class="ui-state-highlight ui-corner-all" style="margin-top: 10px; padding: .8em;" id='divGuardarInformacion'>
									<p><a href="#" onClick="Effect.Fade('divGuardar')"> <span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span>
									</a>
									<strong>Su informaci&oacute;n esta siendo evaluada en estos momentos por favor espere...</strong></p>
								</div>
							</div>
							<div id="divGuardarC"><img alt="" src="<?php echo __IMG__?>esperar.gif" border=0></div>							
							<div id="tabs" style="width:700px"> 
						    <ul>
					        <li><a href="#tabs-1">Datos B&aacute;sico</a></li>
					        <li><a href="#tabs-2">Datos del Cr&eacute;dito</a></li>
					        <li><a href="#tabs-3">Solicitar Garant&iacute;a</a></li>
					        <li><a href="#tabs-4">Control de Historial</a></li>
						    </ul>						    							    	
								<div id='tabs-1' >																		
										<?php $this->load->view(__NFORMULARIOS__ . "datos_personales.php");?>
								</div>
								<div id='tabs-2'>
									<?php $this->load->view(__NFORMULARIOS__ . "cliente_nomina.php");?>									
								</div>
								<div id='tabs-3'>									
										<?php $this->load->view(__NFORMULARIOS__ . "agregar_garantia.php");?>									
								</div>
								<div id='tabs-4'>									
									<div class="ui-widget" style="display:none;" id='divListaCreditos'>
										<div class="ui-state-highlight ui-corner-all" style="margin-top: 10px; padding: .8em;" id='divListaCreditosInformacion'></div>
									</div>								
								</div>
							</div>						
							
		
							<div class="demo" style="padding: 10px; text-align: center;">
								<a href="#" onclick="Validar();" id="btnGuardar">	Guardar</a>	
								<a href="#" onclick="btnCancel();" id="btnCancelar">Limpiar</a>
								<a href="#" onclick="btnDBaja();" id="btnGuardar">Suspender</a>
							</div>							

		
	</div>

</center>
</body>
</html>
