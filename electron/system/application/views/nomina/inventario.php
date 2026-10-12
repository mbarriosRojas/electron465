<html>
	<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    
  <title><?php echo __TITLE__ ?></title>
	<?php $this->load->view("incluir/cabezera.php");?>
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>nomina/botones.js"></script>
	<script type="text/javascript">
	function Limpiar_Modelo(){
		var cant = document.frmInventario.lstModelo.length + 1;
			
			//alert(cant);
			for( i = 0; i < cant; i++) {
				document.frmInventario.lstModelo.options[i] = null;
			}
	}
	function Seleccion_Modelo(strUrl, val) {
		document.frmInventario.lstModelo.options.length=0;

			val = document.frmInventario.txtEquipos.value;
			var strUrl_Proceso = strUrl + "index.php/cooperativa/SModelo";		
			$.ajax({
				url : strUrl_Proceso,
				type : "POST",
				data : "valor=" + val,
				dataType : "json",
				success : function(data) {
					i = 0;
					Modelos = document.frmInventario.lstModelo.options;
					$.each(data, function(item, valor) {
						var Optiones_Modelos = new Option(valor, item);
						Modelos[i] = Optiones_Modelos;
						i++;
					});
				request.fail(function(jqXHR, textStatus) {
				  alert( "Request failed: " + textStatus );
				});	
					
				}
			});
			if(i == 0) {
				document.frmInventario.lstModelo.options.length=0;
			}
			document.frmInventario.lstModelo.options[0].selected = true;
		}
			
	
	
		function Mostrar_Detalles(id){
			$('#divDetalles'+id).show("blind");
		}

		function Ocultar_Detalles(id){
			$('#divDetalles'+id).hide("blind");
		}
		
		
		function PInventario(sUrl, iPos, iRegistro, marca, modelo, proveedor, artefacto){			
			
			var sUrlP = sUrl;
			var sCont = "";
			
			
			for( var i = 1; i <= iRegistro; i++){
				celem = "#c" + iPos + i;
				selem = "#s" + iPos + i;
				
				ubicacion = $(celem).val();
				serial = $(selem).val();
				
				sCont += "&c" + iPos + i + "=" + ubicacion + "&s" + iPos + i + "=" + serial;
			}
			
			$.ajax({
				url: sUrlP,
				type: "POST",
				data: "pos=" + iPos + "&registro=" + iRegistro 
				+ "&marca=" + marca 
				+ "&modelo=" + modelo 
				+ "&proveedor=" + proveedor 
				+ "&artefacto=" + artefacto 
				+ sCont,
				success: function(html){
					
					$("#divDetalles"+iPos).html(html);
					
				}
				
			});
		}
		
		

		
	function Eliminar_Serial_Principal(strUrl, strSerial, iPos) {
	
	var strUrl_Proceso = strUrl + "/index.php/cooperativa/Eliminar_Serial";
	
	$.ajax({
				url: strUrl_Proceso,
				type: "POST",
				data: "pos=" + iPos + "&serial=" + strSerial,
				success: function(html){					
					$("#divDetalles"+iPos).html(html);					
				}				
			});
}
		
		
		
		
		$(function() {
		
			var iRow = document.getElementById("tInventario").rows.length;
			var sVal = "input:button";	
			$(sVal).button({            
				text: true,						
			});
			
			
		});
		
	</script>

	<style type="text/css">
			/*demo page css*/
			body{ font: 62.5% "Trebuchet MS", sans-serif; margin: 0px;}
	
			#dialog_link {padding: .1em 1em .20em 6px;text-decoration: none;position: relative;}
			#dialog_link span.ui-icon {margin: 5px 0 0;position: absolute;center: 1em;top: 1%;margin-top: 10px;}
			
			#tipo_link {padding: 2px 4px 2px 4px;text-decoration: none;position: relative; }
			
			ul#icons {margin: 0; padding: 0;}
			ul#icons li {margin: .3px; position: relative; padding: .3px 0; cursor: pointer; float: left;  list-style: none;}
			ul#icons span.ui-icon {float: left; margin: .5px;}
		</style>	
  
  
  </head>
<body>
	<div id="cabecera"><br>
		<?php $this->load->view("menu/buscar.php");?>
		<?php echo $Menu;?>
	</div>
	<div id="medio" >
		<div id="msj_alertas"></div>	
		
			<h2>Historial de Inventario</h2>
              <br>
              		<div class="ui-widget" style="display:none;" id='divReportesMsg'>
										<div class="ui-state-highlight ui-corner-all" style="margin-top: 10px; padding: .8em;" id='divReportesMsgInformacion'>
											<p><a href="#" onClick="Effect.Fade('divReportesMsg')"> <span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span>
											<strong>Su informaci&oacute;n esta siendo evaluada en estos momentos por favor espere...</strong></a></p>
										</div>
									</div><br>
							    <!-- ui-dialog -->
							    <form name="frmInventario" 
							    action="<?php echo __LOCALWWW__ ?>/index.php/cooperativa/Listar_Inventario" method="post">
									<table cellpadding="3" cellspacing="3" border=0>
										
										<tr>
										  	<td>
										  		Equipos:											  		
										  	</td>
										  	<td>
										  		<div style=""  id="divEC">
										  			<select name="txtEquipos"	id="txtEquipos" class="inputxt" 
										  			style="width: 400px;" onclick="javascript:Seleccion_Modelo('<?php echo base_url(); ?>','')">
															<?php echo $lstEquipos;?>				
														</select>
													</div>
										  	</td>										  	
										  </tr>
										  
										  
									 		<tr>
											<td class="formulario">Estatus General :</td>
											<td>
												<select name="txtEstatus"	id="txtEstatus"  style="width: 400px;" class="inputxt">
													<option value='1'>EXISTENTE (DISPONIBLE)</option>
													<option value='2'>VENDIDO (AUTORIZADO)</option>													
												</select>
											</td>
											<td rowspan="2" class="formulario" valign="bottom">
												<input type="button" onclick="frmInventario.submit();" value="Generar Consulta" style="width:120px; height:43px">
											</td>
											</tr>
									 
											<tr>
										    <td style="width: 140px;" class="formulario">Dependencia Adscrita: </td>
										    <td colspan="2" >
										    	<select name="txtDependencia"	id="txtDependencia" style="width: 400px;" class="inputxt">
										    		<option value="987654321">CEDE PRINCIPAL, DEPOSITO</option>
										    		<option value="2307">CEDE PRINCIPAL, ESTADO MERIDA</option>
														<?php echo $Listar_Usuarios_Combo; ?>
														<option value="TODOS">TODOS - EN GENERAL</option>	
													</select>
												</td>

										  </tr>
										  

										  
										  <tr>
										  	<td>
										  		Modelos:
										  	</td>
										  	<td>										  		
																<select name="lstModelo"	id="lstModelo" class="inputxt" style="width: 400px;"
															>	
																</select>
												
										  	</td>
										  </tr>
									</table>
									<br>									
									</form>	
									<div id="divReportes" >
										
									</style>
												<?php echo $sContenido; ?>
									</div>	
			
		</div>
	</div>
</body>
</html>






 