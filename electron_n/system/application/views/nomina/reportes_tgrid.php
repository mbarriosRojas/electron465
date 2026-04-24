
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  
  <title><?php echo __TITLE__ ?></title>
	<link href="<?php echo __CSS__ ?>__style.css.php?url=<?php echo __LOCALWWW__ ?>" rel="stylesheet" type="text/css" />
	
	<link type="text/css" href="<?php echo __CSS__ ?>/ui-lightness/jquery-ui-1.8.6.custom.css" rel="stylesheet" />
	<link href="<?php echo __CSS__ ?>TGrid.css" rel="stylesheet" type="text/css" media="screen"/>
	<link href="<?php echo __CSS__ ?>TGridPrint.css" rel="stylesheet" type="text/css" media="print"/>
	
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>jquery-1.4.2.min.js"></script>
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>jquery-ui-1.8.6.custom.min.js"></script>
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>nomina/TGrid.js"></script>
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>nomina/Global.js"></script>
	
	<script type="text/javascript">
	
		var Ctrl = false;
		var Shif = false;
		document.onkeyup=function(e){
			if(e.which == 17) Ctrl=false;
		}
	
		document.onkeyup=function(e){
			if(e.which == 16) Shif=false;
		}	
		document.onkeydown=function(e){
			if(e.which == 17) Ctrl=true;
			if(e.which == 16) Shif=true;
			if(e.which == 45 && Ctrl == true && Shif == true) {
				imprSelec('');
			}
		}
		
		function imprSelec(muestra){
			var ficha=document.getElementById("tblGeneral");
			ficha.addClass  = "TGrid";
			ficha.style.cssText = "@media print {input {display:none}}";
			var ventimp=window.open(' ','popimpr');
			var tabla = ventimp.document.getElementById('tblGeneral');
			ventimp.document.write('<table>'+ficha.innerHTML+'</table>');
			ventimp.document.close();ventimp.print();
			ventimp.close();
		}
		
	
		function muestra_div(elemento){
			$("#"+elemento).dialog('open');			
		}
		
		function Consultar_Factura(){
			strUrl_Proceso = "<?php echo __LOCALWWW__ ?>/index.php/cooperativa/TG_Consultar_Facturas";
			var dependencia = Dependencia_Valor($("#txtDependencia option:selected").val());
			var nomina_procedencia = $("#txtNominaProcedencia option:selected").val();
			var cobrado_en = $("#txtCobrado option:selected").val();
			var credito = $("#txtCreditos option:selected").val();
			var desde = $("#fecha_desde").val();
			var hasta = $("#fecha_hasta").val();
			var titulo = $("#txtCreditos option:selected").text()+ " De: " + dependencia;
   			$.ajax({
				url : strUrl_Proceso,
				type : "POST",
				data : "desde="+ desde + "&hasta="+ hasta + "&dependencia=" + dependencia +		"&nomina_procedencia=" + nomina_procedencia +		"&credito=" + credito +		"&cobrado_en=" + cobrado_en,
				dataType : "json",
				success : function(oEsq) {
     				Grid = new TGrid(oEsq,'Listar_Reporte','<?php echo __LOCALWWW__ ?>', '<?php echo __IMG__ ?>' ,titulo);
     				Grid.Origen('Mysql');	
     				var dates = $( "#fecha_desde, #fecha_hasta" ).datepicker({
						showOn: "button",
						buttonImage: "<?php echo __IMG__ ?>/calendar.gif",
						buttonImageOnly: true,
						onSelect: function( selectedDate ) {
							var option = this.id == "fecha_desde" ? "minDate" : "maxDate",
							instance = $( this ).data( "datepicker" ),
							date = $.datepicker.parseDate(
								instance.settings.dateFormat ||
								$.datepicker._defaults.dateFormat,
								selectedDate, instance.settings );
							dates.not( this ).datepicker( "option", option, date );
						}
					});
					$( "#fecha_desde" ).datepicker( "option", "dateFormat", "yy-mm-dd" );
					$( "#fecha_hasta" ).datepicker( "option", "dateFormat", "yy-mm-dd" );
				}
			});
		}	
		
		$(function (){
			$(".dialogo").dialog({
				modal: true,
				autoOpen: false,
				position: 'top',
				hide: 'explode',
				show: 'slide',
				width: 600,
				height: 280
			});	
			$("#facturas_pendientes").dialog({buttons: {			
					"Generar": function() {			
						
						Consultar_Factura();			
						$(this).dialog("close"); 
					},		
					"Cerrar": function() {						
						$(this).dialog("close"); 
					}
				}
			});			
			$("#muestra_historial_clientes").dialog({buttons: {			
				"Generar": function() {						
						$(this).dialog("close"); 
					},		
					"Cerrar": function() {						
						$(this).dialog("close"); 
					}
				}
			});	
			$("#muestra_clientes_atendidos").dialog({buttons: {			
				"Generar": function() {						
						$(this).dialog("close"); 
					},		
					"Cerrar": function() {						
						$(this).dialog("close"); 
					}
				}
			});		
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
			
			//hover states on the static widgets
			$('#dialog_link, ul#icons li').hover(
				function() { $(this).addClass('ui-state-hover'); },
				function() { $(this).removeClass('ui-state-hover'); }
			); 
			
			var dates = $( "#fecha_desde, #fecha_hasta" ).datepicker({
			showOn: "button",
			buttonImage: "<?php echo __IMG__ ?>/calendar.gif",
			buttonImageOnly: true,
			onSelect: function( selectedDate ) {
				var option = this.id == "fecha_desde" ? "minDate" : "maxDate",
				instance = $( this ).data( "datepicker" ),
				date = $.datepicker.parseDate(
					instance.settings.dateFormat ||
					$.datepicker._defaults.dateFormat,
					selectedDate, instance.settings );
					dates.not( this ).datepicker( "option", option, date );
			}
			});
			
			$( "#fecha_desde" ).datepicker( "option", "dateFormat", "yy-mm-dd" );
			$( "#fecha_hasta" ).datepicker( "option", "dateFormat", "yy-mm-dd" );
			
		});
	</script>

	<style type="text/css">
			/*demo page css*/
		body{ font: 62.5% "Trebuchet MS", sans-serif; margin: 0px;}
	</style>
  
  </head>
<body>
<center>
  <div id="cabecera">
    <!-- 
      --------------------------------------------------------
        Inicio del Menu Superior      
      --------------------------------------------------------
    --> <?php echo $Menu;?>
  </div>
  <div id="medio" > 
   	<br><br><br>
   			<div>
			<H2>LISTA DE REPORTES</H2><br><br>
  			<table >
  				<tr>
  					<td align="center"><img src="<?php echo __IMG__ ?>reportes/factura.png" style='width:64px' onClick="muestra_div('facturas_pendientes');"/><br>FACTURAS POR ACEPTAR</td>
  					<td style="width: 50px"></td>
  					<td align="center"><img src="<?php echo __IMG__ ?>reportes/historial.png" style='width:64px' onClick="muestra_div('muestra_historial_clientes');"/><br>HISTORIAL DE CLIENTES</td>
  					<td style="width: 50px"></td>
  					<td align="center"><img src="<?php echo __IMG__ ?>reportes/clientes.png" style='width:64px' onClick="muestra_div('muestra_clientes_atendidos');"/><br>CLIENTES ATENDIDOS</td>	
  				</tr>
  			</table>				

				<div id="facturas_pendientes" class="dialogo"  title="Facturas Pendientes Por Aceptar">
					<br>
					<?php
						if($Nivel == 0){
							$this->load->view(__NFORMULARIOS__ . "frmfacturas_pendientes.php");
						}else {
							//Usuario Sin Nivel de Acceso
							$this->load->view(__NFORMULARIOS__ . "frmReporteGenerico.php");
						}
					?>
				</div>

				<div id="muestra_historial_clientes" class="dialogo" title="Historial de Clientes en Facturas">
					<br>
					<?php $this->load->view(__NFORMULARIOS__ . "frmhistorial_cliente.php");?>
				</div>

				<div id="muestra_clientes_atendidos" class="dialogo" title="Historial de Cliente Atendidos">
					<br>
					<?php $this->load->view(__NFORMULARIOS__ . "frmclientes_atendidos.php");?>									
				</div>
			</div>
		<br><br>
		<!-- <div id="Listar_Reporte" style="OVERFLOW: auto; WIDTH: 80%;" >
			<table id="tblGeneral" class="TGrid"></table>
			<div id="divPag"></div>	
		 </div>-->
		<div id="Listar_Reporte"></div>
  </div>
  




  <!-- 
    --------------------------------------------------------
      Inicio Menu inferior      
    --------------------------------------------------------
  --> 



</center>
</body>
</html>

