<?php
    
?>
<html>
<head>
	<title>Datos Personales</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	
	<title><?php echo __TITLE__ ?></title>
	
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>jquery-1.4.2.min.js"></script>
	 <script type="text/javascript">
  
  	function Imprimir ()
		{
			strUrl = '<?php echo __LOCALWWW__?>/index.php/cooperativa/Aceptar_Facturas/' + document.getElementById('factura').value;
			document.getElementById('aceptar').style.visibility = "hidden";
			$.ajax({
				url: strUrl,
				context: document.body,
				success: function(){
					window.close();		
				}				
			});
			
		}		
	
	</script>
	
	
	<style type="text/css">
		/*demo page css*/
			body{ font: 10px sans-serif; margin: 0px;}

		 	* html .ui-autocomplete {
				height: 100px;
			}
			table, tr, td{
				 font: 12px sans-serif, verdana
			}
	</style>
	
</head>
<body>
				<?php 
					if ($empresa == 0){
						$empresa = 'COOPERATIVA ELECTRON 465 RL.';
						$Rif = ' J-31207419-1';
					}else{
						$empresa = 'GRUPO ELECTRON 465 C.A';
						$Rif = ' J-29837846-8';
					}
				?>
	<center><table width="800px">
		<tr>
			<td>
				<div style="text-align:justify">
				<table><tr><td style="width:700px"><center>
				<B><?php echo $empresa;?><br>
				Domicilio Fiscal M&eacute;rida<br>
				
				Rif: <?php echo $Rif?>
				<br>
				</B></center>	
				</td><td style="width:100px">
				<?php 
						$strContenido = "";
						$boton = '';
						if(isset($Nivel) && isset($Aceptado)){
							if($Nivel == 0 && $Aceptado == 0){
								$boton = '<input type="button" value="Aceptar" onClick="Imprimir();" id="aceptar"  style="width:100px;height:30px">';
							}
						}
						if($factura!=''){
							echo '<font size="3"><b>N&#186;:' . $factura . '</b><br>'. $boton .'<input type=hidden value="' . $factura .'" id="factura"/></font>';
							$strContenido = "DOMICILIACI&Oacute;N";  
						}else{
							$strContenido = "AFILIACI&Oacute;N";
						}
					 ?>
				</td></tr></table>	
				<center>	
				<h2>CONTRATO DE <?php echo $strContenido;?>.</h2></center>
				
					Yo, <?php echo $nombre;?>, de nacionalidad  VENEZOLANO, domiciliado en 
					<?php echo $direccion;?>, titular de la c&eacute;dula de identidad 
					<?php echo $cedula;?>; autorizo expresamente al BANCO <?php echo $banco;?> 
					para que conforme a las instrucciones que reciba de <?php echo $empresa;?>, y 
					en la fechas indicadas por &eacute;sta, efectu&eacute; los 
					procesos de cobro por las cuotas indicadas en las <b>LETRAS DE CAMBIOS</b> 
					correspondiente  a los servicios de FINACIMIENTO; mismo que podr&aacute;n ser 
					debitados de mi cuenta cuyos datos provee a continuaci&oacute;n.
					<br><br>
					<font style='font-size: 14pt'><b>BANCO: </b> <?php echo $banco;?> &nbsp;&nbsp;&nbsp; <b>N&Uacute;MERO DE CUENTA:</b> 
					<?php echo $cuenta;?><br>
					<?php echo $nomina;?></font>
					<br><br>
					<?php echo $lista;?>
					
					Reconozco expresamente que tanto la prestaci&oacute;n del servicio por parte de <?php echo $empresa;?>, 
					como los importes que autoriz&oacute; debitar de mi 
					cuenta, tienen como causa exclusiva la relaci&oacute;n contractual 
					existente entre <?php echo $empresa;?>, y mi persona.<br><br> 
					
					Es de mi conocimiento que el cargo se efectuar&aacute; por <b>LETRA &oacute; 
						GIRO</b> antes de su vencimiento.<br><br>
					
					Igualmente me comprometo a mantener en la cuenta el saldo disponible para 
					cubrir los montos de los d&eacute;bitos autorizados, considerando  que estos son 
					efectuados en las fechas fijas.<br><br>
					
					Acepto que, si por mi incumplimiento en la fecha en que me compromet&iacute; a 
					cancelar no hubiese disponible el monto respectivo a la <b>LETRA &oacute; GIRO</b>, 
					<?php echo $empresa;?>, podr&aacute; hacer el cobro efectivo 
					de las mismas en la pr&oacute;xima fecha, quedando entendido que los intereses de 
					mora ser&aacute;n calculados sobre la tasa actual de 
					financiamiento mas tres (3) puntos porcentuales adicionales<br><br>
					
					Con la presente <b><?php echo $strContenido;?></b>, expresamente 
					liber&oacute; al BANCO <?php echo $banco;?>  de toda responsabilidad en el caso de 
					que no mantenga fondos disponibles suficientes como consecuencias 
					de este cargo autom&aacute;tico en cuenta o si alguna LETRA &oacute; GIRO fuera 
					emitido a mi nombre por la empresa y no resulta pagado por este mecanismo, por 
					causas extra&ntilde;as no 
					imputables al Banco.<br><br>
					
					Acepto que, cualquier reclamo administrativo con los d&eacute;bitos o garant&iacute;as que ocurra en ocasi&oacute;n a 
					la prestaci&oacute;n del servicio ser&aacute;n tramitados por <?php echo $empresa;?> y contar&aacute; con cinco (5) d&iacute;as habiles laborales para su respuesta, 
					as&iacute; mismo	deber&aacute; tramitar dicho proceso en forma escrita y firmada.<br><br>
					
					
					Acepto que, en lo que respecta a mi persona este contrato se considera intuito 
					personae&eacute;. Y que el incumplimiento de una o cualquiera de las obligaciones 
					aqu&iacute; asumidas, dar&aacute; derecho a <?php echo $empresa;?>, 
					a proceder en autorizar los descuentos que falten en los tiempos que sean 
					posibles.<br><br>
					
					La presente <b><?php echo $strContenido;?></b>  estar&aacute; vigente 
					hasta tanto medie comunicaci&oacute;n fehaciente de m&iacute; solvencia para 
					revocarla<br><br>
					
					Yo, <?php echo $nombre;?>, C.I: <?php echo $cedula;?>. Acepto las condiciones 
					de <b><?php echo $strContenido;?></b> establecidas por <?php echo $empresa;?>.<br><br><br><br><br>
					<center>
					<table border="0" style="width:70%; height: 100px">
						<tr>
							<td style="width:100px; height: 80px" >
								&nbsp;
							</td>
							<td align="center"><br><br>
									_______________________________________<br>
									Firma Recibi Conforme.- <br>
									C.I: <?php echo $cedula;?><br>
									Nombre: <?php echo $nombre;?><br><br>
							</td>
							<td style="width:100px; height: 80px" >
								&nbsp;
							</td>
						</tr>
						<tr>
						<td style="text-align: left;font-size: 10px;"><b>Pulgar Izquierdo</b></td>
						<td style="text-align: center">&nbsp;</td>
						<td style="text-align: right;font-size: 10px;"><b>Pulgar Derecho</b></td>
						</tr>
					</table></center><br>
					USUARIO: <?php echo strtoupper($usuario);?> 
				</div>
			</td>
		</tr>
	</table>
	</center>
	
	
</body>
</html>
