<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title><?php echo __TITLE__ ?></title>
  <link href="<?php echo __CSS__ ?>__style.css.php?url=<?php echo __LOCALWWW__ ?>" rel="stylesheet" type="text/css" />
  
  <link type="text/css" href="<?php echo __CSS__ ?>ui-lightness/jquery-ui-1.8.6.custom.css" rel="stylesheet" />
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>jquery/jquery-1.4.2.min.js"></script>
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>jquery/jquery-ui-1.8.6.custom.min.js"></script>
<script>	
	function Imprimir (){
		$("#lblPresupuesto").hide();
		$("#lblAbono").hide();
		$("#txtModelo").hide();
		$("#txtAbono").hide();
		$("#btnImprimir").hide();
		$("#btnCalcular").hide();
		$("#btnRecalcular").hide();
		window.print();	
	}
	$(function() {
		/**
		 * Lista de Modelos
		 */	
		 
		if($("#txtMuestra").val() == '1' ){
			$('#plan_financiamiento').show();
		}else{
			$('#plan_financiamiento').hide();
		}
		
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
		abono = 0;	
		var cantidad =0;
		if(document.getElementById("txtAbono").value != ''){ abono = document.getElementById("txtAbono").value;}	
		sUrl = sUrl + "/index.php/cooperativa/BModelo";		
			$.ajax({
				url: sUrl,
				type: "POST",
				data: "modelo=" + modelo,
				dataType : "json",
				success: function(data){
					if(data['equipo'] != ''){
						document.getElementById("txtCalculo").value = data['credi_compra']  - abono;						
						document.getElementById("txtDes").value = data['equipo'] + ', MARCA: ' + data['marca'] + ', MODELO: ' + modelo;
						var porcen = document.getElementById("txtPorcentaje").value = data['porcentaje'];
						//alert(porcen);
						
						cantidad = data['cant_ser'];
						alert("POSEE EN EXISTENCIA "+data['cant_ser'] + ' SERIALES....');
					}	else{
						if(document.getElementById("txtCalculo").value == ''){	document.getElementById("txtDes").value = 'INTENTEN OTRO MODELO';}
					}					
				},
				error: function(html){
					alert('FALLO AL CARGAR DATOS');			
				},		
			});
			alert('Recuerde usar el boton Imprimir para realizar el proceso adecuadamente... ');
	}
	function limpiar(item){

		item.value = "";
		
	}

	function soloNumeros(evt){
		if("which" in evt){
			keynum = evt.keyCode;
		}else{
			keynum = evt.which;
		}
		if((keynum>47 && keynum<58) || (keynum>95 && keynum<106) ||(keynum==13) || (keynum==8) || (keynum==9) ){
			return true;
		}else{
			return false;
		}
	}
</script>
</head>
<body style="background-color: #FFFACD;">
<center><br />
	<form action="<?php echo __LOCALWWW__?>/index.php/cooperativa/Credi_Compra" method="POST">
		<table> 
			<tr>				
			 	<td><label id="lblPresupuesto"> Modelo: &nbsp;</label></td>
			 	<td>
					<div class="ui-widget">
						<input type="text" 	name="txtModelo" id="txtModelo" class="inputxt" style="width: 220px;"	value=''>						
					</div>
				</td>
			 </tr>	 
			 <tr>
			 	<td colspan="2">
			 		<input  type="hidden" value="<?php echo $venta?>" name="txtCalculo" id="txtCalculo" />			 	
					<input type="hidden" value="<?php echo $descripcion?>" name="txtDes" id="txtDes" class="inputxt"   style="width: 220px;"/>
					<input type="hidden" value="<?php echo $porcentaje?>" name="txtPorcentaje" id="txtPorcentaje" class="inputxt"   style="width: 220px;"/>
					<input type="hidden" value="<?php echo $muestra?>" name="txtMuestra" id="txtMuestra" class="inputxt"   style="width: 220px;"/>
			 	</td>			 	
			 </tr>
			 <tr>
			 	<td colspan="2" align="center">
			 		<input type="submit" class="inputxt" value='Realizar Calculo' id='btnCalcular' onclick="BModelo('<?php echo __LOCALWWW__?>');"/>
			 	</td>
			 </tr>
		</table>
<br><br>
<font style='font-size:20px; '><b>CREDICOMPRA</b></font><BR>
	<?php
	echo $info; 
?>
			<table> 			 
			<tr>				
			 	<td><label id="lblAbono"> Abono Inicial: &nbsp;</label></td>
			 	<td>
					<div class="ui-widget">
						<input type="text" 	name="txtAbono" id="txtAbono" class="inputxt" onkeydown="return soloNumeros(event);" style="width: 220px;"	value=''>						
					</div>
				</td>
			 </tr>
			 	<td colspan="2" align="center">
			 		<input type="submit" class="inputxt" value='Recalcular' id='btnRecalcular' />
			 	</td>
			 </tr>
</table><BR>
<div id='plan_financiamiento'>
PLANES DE FINANCIAMIENTO
<?php
	echo $lista; 
?>
</div>
	<input type="button" class="inputxt" value='Imprimir Calculos'  id='btnImprimir' onclick="Imprimir();"/>	<BR><BR>
	<table><tr><td style="width:500px" valign="bottom">
					<br><br>_______________________________________<br>
					FIRMA RECIBI CONFORME.- <br>
					C.I: <br>
					APELLIDOS Y NOMBRE: <br><br>
					</td><td style="width:100px" align="center">
						_______________________________________<br>
					FIRMA ANALISTA DE VENTAS.- <br>
					</td>
					</tr>
			<tr><td style="width:500px" valign="bottom">
					<BR><div style="width:80px; height: 90px; border: solid 1px #000000"></div>
						HUELLA DACTILAR
					
					</td><td style="width:100px" align="center">
						<BR>_______________________________________<br>
						FIRMA SUPERVISOR DE VENTAS.- <br>
					</td>
					</tr>
					
			</table>
</form>
</body>
</html>

