
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
		
		
		$("#accordion").accordion({ header: "h3" });
		$("#divGuardar").hide();
		$("#divConsultaContrato").hide();
		$("#divActualizarContrato").hide();
		$("#divProcesarCedula").hide();
		$("#divEliminarCedula").hide();
		$("#divProcesarAlta").hide();
		$("#divAltaCedula").hide();


		$("#divConsultaSerial").hide();
		$("#divActualizarSerial").hide();
		$("#divProcesarSerial").hide();
		$("#divEliminarSerial").hide();

		
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
					document.getElementById("txtMarca").value = data['marca'];
					document.getElementById("txtprecioc").value = data['compra'];
					document.getElementById("txtpreciov").value = data['venta'];
					document.getElementById("txtdia").value = data['dia'];
					document.getElementById("txtmes").value = data['mes'];
					document.getElementById("txtano").value = data['ano'];
					document.getElementById("txtCanGarantia").value = data['cant'];
					document.getElementById("txtgarantia").value = data['tipo'];
										
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
<body class="yui-skin-sam" style="background-color: #FFFACD;">
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
              	        
              <center><br><br><br>
              	<br><br>
							<?php $this->load->view(__NFORMULARIOS__ . "frmInventariop.php");?>
							</center>
              </td>
            </tr>
          </table>
        	
        
        
        </td>
      </tr>
    </table>
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
