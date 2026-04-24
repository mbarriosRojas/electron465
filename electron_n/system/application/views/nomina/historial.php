<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title><?php echo __TITLE__ ?></title>
  <link href="<?php echo __CSS__ ?>__style.css.php?url=<?php echo __LOCALWWW__ ?>" rel="stylesheet" type="text/css" />
  <link href="<?php echo __CSS__ ?>/ui-lightness/jquery-ui-1.8.6.custom.css" rel="stylesheet"  type="text/css">	
	<link type="text/css" href="<?php echo __CSS__ ?>/ui-lightness/jquery-ui-1.8.6.custom.css" rel="stylesheet" />
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>nomina/botones.js"></script>
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>jquery-1.4.2.min.js"></script>
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>jquery-ui-1.8.6.custom.min.js"></script>
	<script>
	$(function(){			
		//fechas desde hasta
		$.datepicker.regional['es'] = {
				closeText : 'Cerrar',
				prevText : '&#x3c;Ant',
				nextText : 'Sig&#x3e;',
				currentText : 'Hoy',
				monthNames : ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
				monthNamesShort :['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
				dayNames : ['Domingo', 'Lunes', 'Martes', 'Mi&eacute;rcoles', 'Jueves', 'Viernes', 'S&aacute;bado'],
				dayNamesShort : ['Dom', 'Lun', 'Mar', 'Mi&eacute;', 'Juv', 'Vie', 'S&aacute;b'],
				dayNamesMin : ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'S&aacute;'],
				weekHeader : 'Sm',
				dateFormat : 'dd/mm/yy',
				firstDay : 1,
				isRTL : false,
				showMonthAfterYear : false,
				yearSuffix : ''
			};
			$.datepicker.setDefaults($.datepicker.regional['es']);
		
		$("#fecha").datepicker({
		showOn: "button",
		buttonImage: "<?php echo __IMG__ ?>/calendar.gif",
		buttonImageOnly: true,
		});
		$( "#fecha" ).datepicker( "option", "dateFormat", "dd-mm-yy" );	
		
		
	});
	</script>
		<style type="text/css">
			/*demo page css*/
			body{ font: 62.5% "Trebuchet MS", sans-serif; margin: 0px;}
			ul#icons {margin: 0; padding: 0;}
			ul#icons li {margin: .3px; position: relative; padding: .3px 0; cursor: pointer; float: left;  list-style: none;}
			ul#icons span.ui-icon {float: left; margin: .5px;}
		</style>	
  
</head>
<body style="background-color: #FFFACD;">
<center>
  <div id="cabecera">
   <?php echo $Menu;?>
  </div>
  <div id="medio" >
    <table style="width: 800px" border=0>
      <tr>
        <td style="width: 20px">
        </td>
        <td>
          <table border=0  >
            <tr >
              <td style="width :700px" align="left">
              <br><br>
              <h2>Trabajo Actual del Sistema...</h2>
              <br><br>
              <?php 
              	$ruta = base_url() . "index.php/cooperativa/Historial_Contratos_Reportes";
              	if($Lista != ''){
              		$ruta = base_url() . "index.php/cooperativa/Historial_Contratos_Reportes/TODOS";	
              	}
              	
              
              ?>
              	<form action='<?php echo $ruta ?>' method='POST'>
		              
								<label for="Etiqueta_fecha">Seleccion&eacute; Fecha: </label>
								<input type="text" id="fecha" name="fecha"   class="inputxt"/>&nbsp;&nbsp;
								<select name="txtMotivo"	id="txtMotivo" class="inputxt" style="width: 260px;" onclick="Activar_Motivo(this.value)" onblur="Activar_Motivo(this.value)">
								<option value=3>-- TODOS --</option>
								<option value=2>-- PRESTAMO --</option>
								<option value=1>-- FINANCIAMIENTO --</option>
								
								</select>
													
								<br><br>Seleccionar Nomina:&nbsp;&nbsp;&nbsp;
								<select	 name="txtbanco_1" id="txtbanco_1" class="inputxt" style="width: 160px;"  >
									<option>----------</option>
									<option>SOFITASA</option>
									<option>BICENTENARIO</option>
									<option>BOD</option>
									<option>PROVINCIAL</option>
									<option>VENEZUELA</option>
									<option>BANESCO</option> 
									<option>INDUSTRIAL</option>
									<option>MERCANTIL</option>
									<option>CAMARA MERCANTIL</option>
									<option>EL EXTERIOR</option>
									<option>FONDO COMUN</option>
									<option>DEL SUR</option>
									<option>FEDERAL</option>
									<option>CANARIAS</option>
									<option>CARONI</option>
									<option>CARIBE</option>
									<option>PLAZA</option>
									<option>CENTRAL</option>
									<option>NACIONAL DE CREDITO</option>
									<option>COMERCIO EXTERIOR</option>
									<option>OCCIDENTAL DE DESCUENTO</option>
									<option>100% BANCO COMERCIAL</option>
								</select>		
								<select	name="txtPendiente" id="txtPendiente" class="inputxt" style="width: 110px;"  >
									<option value="4">TODOS</option>
									<option value="0">PENDIENTE</option>
									<option value="2">DEPOSITADOS</option>
									<option value="3">BLOQUEADOS</option>
								</select>
														
								<INPUT TYPE="submit" value="Consultar" style="width: 80px;" class="inputxt" />
							</form>
              <br><br>
              
              </td>
            </tr>
          </table>
        </td>
      </tr>
    </table>
    <br>
  </div>
</center>
</body>
</html>
