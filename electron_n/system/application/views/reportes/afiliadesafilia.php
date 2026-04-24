<?php
    function mes_letras($mesN) {
		switch ($mesN) {
			case '1' :
				$mes = "ENERO";
				break;
			case '2' :
				$mes = "FEBRERO";
				break;
			case '3' :
				$mes = "MARZO";
				break;
			case '4' :
				$mes = "ABRIL";
				break;
			case '5' :
				$mes = "MAYO";
				break;
			case '6' :
				$mes = "JUNIO";
				break;
			case '7' :
				$mes = "JULIO";
				break;
			case '8' :
				$mes = "AGOSTO";
				break;
			case '9' :
				$mes = "SEPTIEMBRE";
				break;
			case '10' :
				$mes = "OCTUBRE";
				break;
			case '11' :
				$mes = "NOVIEMBRE";
				break;
			case '12' :
				$mes = "DICIEMBRE";
				break;
			default :
				break;
		}
		return $mes;
	}
	
	function lugar_letras($lug) {
		switch ($lug) {
			case 'Barina (Principal)' :
				$l = " Barinas Estado Barinas";
				break;
			case 'BARINAS' :
				$l = " Barinas Estado Barinas";
				break;
			case 'Barquisimeto (Principal)' :
				$l = " Barquisimeto Estado Lara";
				break;
			case 'CAJA SECA' :
				$l = " Caja Seca Estado Zulia";
				break;
			case 'Carabobo (Principal)' :
				$l = " Carabobo Estado Valencia";
				break;
			case 'EL VIGIA' :
				$l = " El Vigia Estado Mérida";
				break;
			case 'El Vigia (Principal)' :
				$l = " El Vigia Estado Mérida";
				break;
			case 'MERIDA' :
				$l = " Mérida Estado Mérida";
				break;
			case 'Merida (Interet)' :
				$l = " Mérida Estado Mérida";
				break;
			case 'Merida (Principal)' :
				$l = " Mérida Estado Mérida";
				break;
			case 'Puerto La Cruz (Principal)' :
				$l = " Puerto La Cruz Estado Anzoategui";
				break;
			case 'Puerto Ordaz(Principal)' :
				$l = " Puerto Ordaz Estado Bolivar";
				break;
			case 'SAN CRISTOBAL' :
				$l = " Sancristobal Estado Tachira";
				break;
			case 'Sancristobal (Principal)' :
				$l = " Sancristobal Estado Tachira";
				break;
			case 'SANTA BARBARA' :
				$l = " Santa Barbara Estado Zulia";
				break;
			case 'Santa Barbara Del Zulia (Principal)' :
				$l = " Santa Barbara Estado Zulia";
				break;
			case 'Zona Panamericana/Valera' :
				$l = " Valera Estado Trujillo";
				break;
			case 'ZULIA' :
				$l = " Maracaibo Estado Zulia";
				break;
			case 'Zulia (Principal)' :
				$l = " Maracaibo Estado Zulia";
				break;
			default:
				$l = ' Mérida Estado Mérida';
				break;
		}
		echo $l;
	}
	
	function fecha_letras($tipo = 0) {
		$d = date('d');
		$m = date('m');
		$dl = '';
		$ml = mes_letras($m);
		$st = 'A LOS ';
		if ($tipo == 1){
		    $st = ' ';
		}
		
		switch ($d) {
			case '01' :
                $st = 'EL ';
				$dl = "PRIMER(1) DÍA";
				break;
			case '02' :
				$dl = "DOS (2) DÍAS";
				break;
			case '03' :
				$dl = "TRES (3) DÍAS";
				break;
			case '04' :
				$dl = "CUATRO (4) DÍAS";
				break;
			case '05' :
				$dl = "CINCO (5) DÍAS";
				break;
			case '06' :
				$dl = "SEIS (6) DÍAS";
				break;
			case '07' :
				$dl = "SIETE (7) DÍAS";
				break;
			case '08' :
				$dl = "OCHO (8) DÍAS";
				break;
			case '09' :
				$dl = "NUEVE (9) DÍAS";
				break;
			case '10' :
				$dl = "DIEZ (10) DÍAS";
				break;
			case '11' :
				$dl = "ONCE (11) DÍAS";
				break;
			case '12' :
				$dl = "DOCE (12) DÍAS";
				break;
			case '13' :
				$dl = "TRECE (13) DÍAS";
				break;
			case '14' :
				$dl = "CATORCE (14) DÍAS";
				break;
			case '15' :
				$dl = "QUINCE (15) DÍAS";
				break;
			case '16' :
				$dl = "DIEZ Y SEIS (16) DÍAS";
				break;
			case '17' :
				$dl = "DIEZ Y SIETE (17) DÍAS";
				break;
			case '18' :
				$dl = "DIEZ Y OCHO (18) DÍAS";
				break;
			case '19' :
				$dl = "DIEZ Y NUEVE (19) DÍAS";
				break;
			case '20' :
				$dl = "VEINTE (20) DÍAS";
				break;
			case '21' :
				$dl = "VEINTE Y UN (21) DÍAS";
				break;
			case '22' :
				$dl = "VEINTE Y DOS (22) DÍAS";
				break;
			case '23' :
				$dl = "VEINTE Y TRES (23) DÍAS";
				break;
			case '24' :
				$dl = "VEINTE Y CUATRO (24) DÍAS";
				break;
			case '25' :
				$dl = "VEINTE Y CINCO (25) DÍAS";
				break;
			case '26' :
				$dl = "VEINTE Y SEIS (26) DÍAS";
				break;
			case '27' :
				$dl = "VEINTE Y SIETE (27) DÍAS";
				break;
			case '28' :
				$dl = "VEINTE Y OCHO (28) DÍAS";
				break;
			case '29' :
				$dl = "VEINTE Y NUEVE (29) DÍAS";
				break;
			case '30' :
				$dl = "TREINTA (30) DÍAS";
				break;
			case '31' :
				$dl = "TREINTA Y UN (31) DÍAS";
				break;
			default :
				break;
		}
		echo $st . $dl . ' del mes de '. $ml. ' de '.date('Y');
	}
?>
<html>
<head>
	<title>Datos Personales</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	
	<title><?php echo __TITLE__ ?></title>
	
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>jquery/jquery-1.4.2.min.js"></script>
	 <script type="text/javascript">
  
  	function Imprimir ()
		{
			strUrl1 = "<?php echo __LOCALWWW__?>/index.php/cooperativa/Aceptar_Facturas/"+ document.getElementById('factura').value;
			$.ajax({
				url: strUrl1,
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
			#huella{
				 border: 1px solid #444444;
			}
	</style>
	
</head>
<body>
				<?php 
					if ($empresa == 0){
						$empresa = 'ASOCIACION COOPERATIVA ELECTRON 465 RL.';
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
				Domicilio Fiscal M&eacute;rida - Venezuela<br>
				
				Rif: <?php echo $Rif?>
				<br>
				</B></center>	
				</td><td style="width:110px">
				<?php 
						$strContenido = "";
						$boton = '';
						if(isset($Nivel) && isset($Aceptado)){
							if(($Nivel == 0)&& $Aceptado == 0){
								$boton = '<input type="button" value="Aceptar" onClick="Imprimir();" id="aceptar"  style="width:100px;height:30px">';
							}
						}
						if($factura!=''){
							echo '<font size="3"><b>N&#186;:' . $factura . '</b><br>'. $boton .'<input type=hidden value="' . $factura .'" id="factura"/></font>';
							$strContenido = "DOMICILIACI&Oacute;N" . $meses;  
						}else{
							$strContenido = "AFILIACI&Oacute;N";
						}
					 ?>
				</td></tr></table>	
				<center>	
				<h2>CONTRATO DE <?php echo $strContenido;?>.</h2></center>
				
					Yo, <?php echo $nombre;?>, de nacionalidad  VENEZOLANO, domiciliado en 
					<?php echo $direccion;?>, titular de la c&eacute;dula de identidad 
					<?php echo $cedula;?>; por medio  del presente documento autoriz&oacute; expresamente al BANCO <?php echo $banco;?> 
					para que conforme a las instrucciones que reciba de <?php echo $empresa;?>, inscrita en la oficina Subalterna de 
					Registro P&uacute;blico del Municipio Libertador del Estado M&eacute;rida en fecha 17 de Septiembre de 2004, bajo el No. 6, Folio 45 al 56, 
					Protocolo Primero, Tomo Trig&eacute;simo, Tercer Trimestre, y 
					en la fechas indicadas por &eacute;sta, efectu&eacute; los 
					procesos de cobro por las cuotas indicadas en las <b>LETRAS DE CAMBIOS</b> 
					correspondiente  a los servicios de FINANCIAMIENTO; mismo que podr&aacute;n ser 
					debitados de mi cuenta cuyos datos provee a continuaci&oacute;n:
					<br><br>
					<font style='font-size: 14pt'><b>BANCO: </b> <?php echo $banco;?> &nbsp;&nbsp;&nbsp; <b>N&Uacute;MERO DE CUENTA:</b> 
					<?php echo $cuenta;?><br>
					<?php echo $nomina;?></font>
					<br><br>
					<?php echo $lista;?>
					
					Reconozco expresamente que tanto la prestaci&oacute;n del servicio por parte de <?php echo $empresa;?>, 
					como los importes que autoriz&oacute; debitar de mi 
					cuenta, tienen como causa exclusiva la relaci&oacute;n contractual 
					existente entre <?php echo $empresa;?>, y mi persona, por el bien mueble descrito en acta de entrega de fecha <?php echo date("d/m/Y"); ?>.<br><br> 
					 
					Igualmente me comprometo a mantener en la cuenta el saldo disponible para cubrir los montos de los débitos autorizados, 
					considerando que estos son efectuados en las fechas establecidas.<br><br>

					Acepto que, si por mi incumplimiento en la fecha en que me comprometí a cancelar no hubiese disponible el monto respectivo a la 
					LETRA ó GIRO, <?php echo $empresa;?>, podrá hacer el cobro efectivo por domiciliación o pago por deposito de las mismas en la próxima fechas.<br><br>
					

					Es de mi conocimiento que por alg&uacute;n retraso en los pagos, efectuará <?php echo $empresa;?>  
					el cobro por las multas establecidas en 
					la tabla antes descrita del CONTROL DE PAGOS mes a mes.<br><br>
					
					Con la presente <b><?php echo $strContenido;?></b>, expresamente 
					liber&oacute; al BANCO <?php echo $banco;?>  de toda responsabilidad en el caso de 
					que no mantenga fondos disponibles suficientes como consecuencias 
					de este cargo autom&aacute;tico en cuenta o si alguna LETRA &oacute; GIRO fuera 
					emitido a mi nombre por la empresa <?php echo $empresa;?> y no resulta pagado por este mecanismo, por 
					causas extra&ntilde;as no 
					imputables al Banco.<br><br>
					
					Acepto que, cualquier reclamo administrativo con los d&eacute;bitos o garant&iacute;as que ocurra en ocasi&oacute;n a 
					la prestaci&oacute;n del servicio ser&aacute;n tramitados por <?php echo $empresa;?> y contar&aacute; con cinco (5) d&iacute;as habiles laborales para su respuesta, 
					as&iacute; mismo	deber&aacute; tramitar dicho proceso en forma escrita y firmada.<br><br>
					
					
					Acepto que, en lo que respecta a mi persona este contrato se considera intuito 
					personae&eacute;, y que el incumplimiento de una o cualquiera de las obligaciones 
					aqu&iacute; asumidas, dar&aacute; derecho a <?php echo $empresa;?>, 
					a efectuar los descuentos que falten en los tiempos que sean 
					posibles.<br><br>
					
					
					La presente <b><?php echo $strContenido;?></b>  estar&aacute; vigente 
					hasta tanto medie comunicaci&oacute;n fehaciente de m&iacute; solvencia para 
					revocarla<br><br>
					
					La <?php echo $empresa;?> conviene en cada una de las partes del presente contrato.<br><br>
					
					Yo, <?php echo $nombre;?>, C.I: <?php echo $cedula;?>. Declaro que acepto las condiciones 
					de <b><?php echo $strContenido;?></b> aqui señaladas.<br><br>					
					
					
					Asi lo digo, otorgo y firmo <?php fecha_letras();?>,<b> POR VIA PRIVADA</b> en la ciudad de <?php lugar_letras($lugar);?>.  
					<br><br><br><br>
					<center>
					<table border="0" style="width:100%; height: 100px">
						<tr>
							<td style="width:30%; height: 80px" >
								    _______________________________________<br>
                                    Firma Recibi Conforme.- <br>
                                    C.I: <?php echo $cedula;?><br>
                                    Nombre: <?php echo $nombre;?><br><br>
							</td>
							<td align="center" style="width:280px;"><br><br>
									&nbsp;
							</td>
							<td style="width:30%; height: 80px" >
								_______________________________________<br>
                                    Firma  <br>                                    
                                    <?php echo $empresa;?><br><br>
							</td>
						</tr>
						
					</table></center><br>
					 
				</div>
			</td>
		</tr>
	</table>
	</center>
	
	
</body>
</html>
<?php

?>
