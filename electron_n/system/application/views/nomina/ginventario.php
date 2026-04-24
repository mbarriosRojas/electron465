
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  
  <title><?php echo __TITLE__ ?></title>
	<link href="<?php echo __CSS__ ?>__style.css.php?url=<?php echo __LOCALWWW__ ?>" rel="stylesheet" type="text/css" />
	
	<link type="text/css" href="<?php echo __CSS__ ?>/ui-lightness/jquery-ui-1.8.6.custom.css" rel="stylesheet" />	
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>jquery-1.4.2.min.js"></script>
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>jquery-ui-1.8.6.custom.min.js"></script>

	<script type="text/javascript">
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
<body style="background-color: #FFFACD;">
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
    --> <?php echo $Menu;?>
  </div>


  <div id="medio" > 
    <table style="width: 800px" border=0>
      <tr>
        <td style="width: 20px">
    
        </td>
        <td>
          
          <table border=0 >
            <tr >
              <td style="width :700px" align="left">              
              <br>
              <h2>Historial de Inventario</h2>
              <br>
              		<div class="ui-widget" style="display:none;" id='divReportesMsg'>
										<div class="ui-state-highlight ui-corner-all" style="margin-top: 10px; padding: .8em;" id='divReportesMsgInformacion'>
											<p><a href="#" onClick="Effect.Fade('divReportesMsg')"> <span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span>
											<strong>Su informaci&oacute;n esta siendo evaluada en estos momentos por favor espere...</strong></a></p>
										</div>
									</div><br>
							    <!-- ui-dialog -->




										
									<div id="divReportes" >
												<?php echo $sContenido; ?>
									</div>	
								
              </td>
            </tr>
          </table>
        
        		
        </td>
      </tr>
    </table>
    <br>
    <br>
    <br>

  </div>
  




  <!-- 
    --------------------------------------------------------
      Inicio Menu inferior      
    --------------------------------------------------------
  --> 



</center>
</body>
</html>
