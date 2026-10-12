<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	
	<title><?php echo __TITLE__ ?></title>
	<link href="<?php echo __CSS__ ?>__style.css.php?url=<?php echo __LOCALWWW__ ?>" rel="stylesheet" type="text/css" />
	
	<link type="text/css" href="<?php echo __CSS__ ?>ui-lightness/jquery-ui-1.8.6.custom.css" rel="stylesheet" />
	
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>prototype.js"></script>
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>nomina/botones.js"></script>
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>jquery-1.4.2.min.js"></script>
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>jquery-ui-1.8.6.custom.min.js"></script>

	<script>	
	/**
	 * 
	 *Posicion de los Seriales
	 * @var integer
	 */
	var iPosicion = 0;	
	
	
	function IncSeriales(){
		var sSerial = document.frmInventario.txtSeriales.value;		
		var Cargos = document.frmInventario.lstSeriales.options;

		var Optiones_Cargo = new Option(sSerial,sSerial,"selected");
		Cargos[iPosicion] = Optiones_Cargo;
		iPosicion++;
	}
			
	function EliSeriales(){
		var pos = document.frmInventario.lstSeriales.selectedIndex;
		document.frmInventario.lstSeriales.options[pos] = null;
		
		iPosicion--;
		SelSeriales();
	}
	
	function SelSeriales(){
		
		for(i=0;i<=iPosicion;i++){
			document.frmInventario.lstSeriales.options[i].selected = true;
			
		}
	}
			
	$(function() {
		$("input").keyup(function() {
				
				var value = $(this).val();
				
				$(this).val(value.toUpperCase());
			});
		
		$(".boton button:first").button({
            icons: {
                primary: "ui-icon-folder-open"
            },
						text: true,						
		});
		
		$(".demo button:first").button({
            icons: {
                primary: "ui-icon-pencil"
            },
						text: true,						
		});
		
		$('#tabs').tabs();
		$("#accordion").accordion({ header: "h3" });
		$("#divGuardar").hide();
		$("#divConsultaContrato").hide();
		$("#divConsultaCedula").hide();
		$("#divActualizarContrato").hide();
		$("#divConsultaFactura").hide();
		$("#divConsultaFactura2").hide();
		$("#divActualizarFactura").hide();
		$("#divActualizarFactura2").hide();

		$("#divProcesarCedula").hide();
		$("#divEliminarCedula").hide();
		$("#divProcesarContrato").hide();
		$("#divEliminarContrato").hide();
		$("#divProcesarFactura").hide();
		$("#divEliminarFactura").hide();
		$("#divProcesarAlta").hide();
		$("#divAltaCedula").hide();
		$("#divProcesarNomina").hide();

		$("#divProcesarModelo").hide();
		$("#divEliminarModelo").hide();
		$("#divConsultaSerial").hide();
		$("#divActualizarSerial").hide();
		$("#divProcesarSerial").hide();
		$("#divEliminarSerial").hide();
		$("#divEliminarNomina").hide();

		
		/**
		 * Lista de Proveedores
		 */
		$("#txtProveedores").autocomplete({
			source: function( request, response ) {
				var proveedores = $("#txtProveedores").val();
				$.ajax({
					type: "POST",
					url: "<?php echo base_url();?>index.php/cooperativa/P_json/Proveedores",					
					data: "nombre=" + $("#txtProveedores").val(),
					dataType: "json",
					success: function(data) {						      		
            	response( $.map( data.nombres, function( item ) {
            		return {
            			label: item,
            			value: item
            		}	
							}));				
					},					
				});
			}

		}); //Fin de lista Proveedores
	  

		/**
		 * Lista de Equipos
		 */
		$("#txtEquipos").autocomplete({			
			source: function( request, response ) {
				$.ajax({
					type: "POST",
					url: "<?php echo base_url();?>index.php/cooperativa/P_json/Artefactos",					
					data: "nombre=" + $("#txtEquipos").val(),
					dataType: "json",
					success: function(data) {						      		
            	response( $.map( data.nombres, function( item ) {
            		return {
            			label: item,
            			value: item
            		}	
							}));				
					},					
				});
			}
		}); //Fin de equipos
		
		
		/**
		 * Lista de Marcas
		 */
		$("#txtMarca").autocomplete({			
			source: function( request, response ) {
				$.ajax({
					type: "POST",
					url: "<?php echo base_url();?>index.php/cooperativa/M_json/inventario/marca",					
					data: "nombre=" + $("#txtMarca").val(),
					dataType: "json",
					success: function(data) {						      		
            	response( $.map( data.nombres, function( item ) {
            		return {
            			label: item,
            			value: item
            		}	
							}));				
					},					
				});
			}
		}); //Fin de Marca
		
		
		
		
		
		
		/**
		 * Lista de Modelos
		 */
		$("#txtModelo").autocomplete({			
			source: function( request, response ) {
				$.ajax({
					type: "POST",
					url: "<?php echo base_url();?>index.php/cooperativa/M_json/inventario/modelo",					
					data: "nombre=" + $("#txtModelo").val(),
					dataType: "json",
					success: function(data) {						      		
            	response( $.map( data.nombres, function( item ) {
            		return {
            			label: item,
            			value: item
            		}	
							}));				
					},					
				});
			}
		}); //Fin de Modelo
		
		
	});
	
		function BModelo(sUrl){
			modelo =  document.getElementById("txtModelo").value;	
			sUrl = sUrl + "/index.php/cooperativa/BModelo";
				
			$.ajax({
				url: sUrl,
				type: "POST",
				data: "modelo=" + modelo,
				dataType : "json",
				success: function(data){					
					document.getElementById("txtProveedores").value = data['proveedor'];					
					document.getElementById("txtEquipos").value = data['equipo'];
					
					document.getElementById("txtProveedores1").value = data['proveedor'];
					document.getElementById("txtEquipos1").value = data['equipo'];
					
					document.getElementById("txtMarca").value = data['marca'];
					document.getElementById("txtprecioc").value = data['compra'];
					document.getElementById("txtpreciov").value = data['venta'];
					document.getElementById("txtdia").value = data['dia'];
					document.getElementById("txtmes").value = data['mes'];
					document.getElementById("txtano").value = data['ano'];
					document.getElementById("txtCanGarantia").value = data['cant'];
					document.getElementById("txtgarantia").value = data['tipo'];
					document.getElementById("txtPorcentaje").value = data['porcentaje'];
										
				},
				error: function(html){					
					alert('');			
				},
			});
			
		}
</script>


	<style type="text/css">
		/*demo page css*/
			body{ font: 62.5% "Trebuchet MS", sans-serif; margin: 0px;}
			.a2 {
					border: none;
					color: #996633;
					font-family: Arial, verdana, sans-serif;
					text-decoration: none;
					font-size: 11px;
					line-height: normal;
					font-weight: bold;
					border-bottom: none;
					padding: 0px;
					margin: 0px 0px 0px 0px;
			}
			#dialog_link {padding: .1em 1em .6em 10px;text-decoration: none;position: relative;}
			#dialog_link span.ui-icon {margin: 0px 0px 0px 3px;position: absolute;center: 1em;top: .1em;margin-top: 2px;}

			
			.ui-autocomplete {
				max-height: 100px;
				overflow-y: auto;
			}
	
		 	* html .ui-autocomplete {
				height: 100px;
			}
	</style>
	

	
	
	</head>
<body  style="background-color: #FFFACD;">
<center>
	<!-- 
			Cuerpo del documento para el datatable 
			Componetes de YUI Loader
	-->
	<div id="cabecera">
		<!-- 
			--------------------------------------------------------
				Inicio del Menu Superior			
			--------------------------------------------------------
	  --> 
	<?php echo $Menu;?>
	</div>
	<div id="medio" >	
		<table style="width: 800px" border=0>
			<tr>
				<td style="width: 20px">		
				</td>
				<td>
					<table border=0 >
						<tr>
							<td style="width :750px" align="left">	
							<br>					
							<div class="ui-widget" id='divGuardar'>
								<div class="ui-state-highlight ui-corner-all" style="margin-top: 10px; padding: .8em;" id='divGuardarInformacion'>
									<p><a href="#" onClick="Effect.Fade('divGuardar')"> <span class="ui-icon ui-icon-info" 
										style="float: left; margin-right: .3em;"></span>
									</a>
									<strong>Su informaci&oacute;n esta siendo evaluada en estos momentos por favor espere...</strong></p>
								</div>
							</div><br>
							</td>
							</tr><tr>
							<td >	
								
								<h2>Panel de Control y/o Configuraciones Generales</h2><br>
								<h2><font color="RED"><?php echo $msj; ?></font></h2>
								
								<div id="tabs">
						    <ul>
					        <li><a href="#tabs-1">Inventario</a></li>
					        <li><a href="#tabs-2">Agregar</a></li>					        
					        <li><a href="#tabs-3">Crear Usuarios</a></li>
					        <li><a href="#tabs-4">Modificaciones</a></li>
					        <li><a href="#tabs-5">Eliminar</a></li>
					        <li><a href="#tabs-6">Cuentas Bancarias</a></li>
						    </ul>
						    							    	
								<div id='tabs-1'>
									<?php $this->load->view(__NFORMULARIOS__ . "frmInventario.php");?>
								</div>
								<div id="tabs-2">
									<?php $this->load->view(__NFORMULARIOS__ . "frmNomina.php");?>
								</div>							
								<div id="tabs-3">
									<?php $this->load->view(__NFORMULARIOS__ . "frmUsuarios.php");?>					
								</div>
								<div id='tabs-4'>
									<?php $this->load->view(__NFORMULARIOS__ . "frmContratos.php");?>
								</div>
								<div id="tabs-5">
									<?php $this->load->view(__NFORMULARIOS__ . "frmEliminar.php");?>
								</div>
								<div id='tabs-6'>
									<?php $this->load->view(__NFORMULARIOS__ . "frmAsociarCuentas.php");?>
								</div>
							</div>		
								
		<!--		<div id="accordion" style="width: 740px">
									<div>
										<h3><a href="#" class="a2">Control de Inventario</a></h3>
											<div>
												<br>
													<center>
														<!-- <?php $this->load->view(__NFORMULARIOS__ . "frmInventario.php");?> 
													</center>
												<br>
											</div>
									</div>									
									<div>
										<h3><a href="#" class="a2">Asociar Cuentas Bancarias a Usuarios</a></h3>										
										<div>
											<br>
											<!-- <?php $this->load->view(__NFORMULARIOS__ . "frmAsociarCuentas.php");?>
											<br>
										</div>																				
									</div>								
									<div>
										<h3><a href="#" class="a2">Modificar Contratos, Elimnar C&eacute;dulas, Activar Suspenciones, Modificar, Eliminar Seriales y Cambiar Cedulas</a></h3>
										<div>
											<br>
												<!-- <?php $this->load->view(__NFORMULARIOS__ . "frmContratos.php");?> 
											<br>
										</div>
									</div>									
									<div>
										<h3><a href="#" class="a2">Crear Usuarios</a></h3>
										<div>
											<br>
												<?php $this->load->view(__NFORMULARIOS__ . "frmUsuarios.php");?>
											<br>
										</div>
									</div>									
									<div>
										<h3><a href="#" class="a2">Estadisticas del Bono de Producci&oacute;n</a></h3>
										<div>Nam dui erat, auctor a, dignissim quis.</div>
									</div>									
								</div>
							</td>
						</tr>
					</table>			
				</td>
			</tr>
		</table>
		<br>	
		<br><br><br><br>
	</div> -->
		<!-- 
		--------------------------------------------------------
			Inicio Menu inferior			
		--------------------------------------------------------
  --> <br><br><br><br>
</center>
</body>
</html>
