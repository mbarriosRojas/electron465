<?php
    
?>
<html>
<head>
	<title>Datos Personales</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	
	<title><?php echo __TITLE__ ?></title>
	
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>jquery/jquery-1.4.2.min.js"></script>
	 <script type="text/javascript">
  	
	
	</script>
	
	
	<style type="text/css">
		/*demo page css*/
			body{ font: 10px sans-serif; margin: 0px;}

		 	* html .ui-autocomplete {
				height: 100px;
			}
			table, tr, td{
				 font: 16px sans-serif, verdana
			}
						#huella{
				 border: 1px solid #444444;
			}
	</style>
	
</head>
<body>
				
				<?php
				$cuenta_p='';
				$banco_voucher ='';
				$empresa_voucher = '';
				switch ($tipo_voucher) {
					case 'BFC':
						$cuenta_p = '01510138528138022782';
						$banco_voucher = 'BANCO FONDO COMUN';
						$empresa_voucher = 'GRUPO ELECTRON 465, C.A';	
						break;
					case 'PRV':
						$cuenta_p = '01080372150100025942';
						$banco_voucher = ' PROVINCIAL';
						$empresa_voucher = 'COOPERATIVA ELECTRON 465 RL ';	
						break;
					case 'BIC':
						$cuenta_p = '01750541650071150460';
						$banco_voucher = ' BICENTENARIO';
						$empresa_voucher = 'GRUPO ELECTRON 465, C.A ';
						break;
					default:
							
						break;
				}
				
				if($tipo_pago == 6){
					$empresa = 'GRUPO ELECTRON 465 C.A';
					$Rif = ' J-29837846-8';
					$text = 'GRUPO ELECTRON 465, C.A., Domiciliada en la Ciudad de Mérida e inscrita en el 
					Registro Mercantil Primero del Municipio Libertador del Estado Mérida bajo el N° 12, Tomo 161-A R1MERIDA, 
					del día 04 del mes de Noviembre del Año 2.009, representada en este acto por el Señor Álvaro Hernán Zambrano Roa,
					Venezolano Mayor de Edad, titular de la Cédula de Identidad N°. 11.109.659, soltero, en su carácter de Administrador General.';
					$text1='depósito(s) ';
					$text2 ='En la Cuenta Corriente N° '. $cuenta_p.' , del BANCO '. $banco_voucher.', a nombre de '. $empresa_voucher.'  en ';
					$num = $boucher;
				}else{
					if ($empresa == 0){
						$empresa = 'COOPERATIVA ELECTRON 465 RL.';
						$Rif = ' J-31207419-1';
					}else{
						$empresa = 'GRUPO ELECTRON 465 C.A';
						$Rif = ' J-29837846-8';
					}
					$text = 'ASOCIACION COOPERATIVA ELECTRON 465, R.L., Domiciliada en la Ciudad de Mérida e inscrita ante la  
					oficina subalterna del registro público del Municipio Libertador del Estado Mérida bajo el N° 6, folio 45-56 protocolo 1 tomo 30, trimestre tercero del año 2004
					del día 17 del mes de Septiembre del Año 2.004, representada en este acto por el Señor Álvaro Hernán Zambrano Roa,
					Venezolano Mayor de Edad, titular de la Cédula de Identidad N°. 11.109.659, soltero, en su carácter de Coordinador de Administración.';
					$text1='pago(s) '. $periocidad;
					$text2 ='En ';
					$num = $cant;
				} 
				$referencia = $factura;
					
					
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
					echo '<font size="3"><b>N&#186;:' . $referencia . '</b><br></font>';
				?>
				</td></tr></table>	
				<center>	
				<h2>ACTA COMPROMISO DE PAGO</h2></center><br><br>
				
					Entre, <b><u><?php echo $nombre;?></u></b>, Venezolano (a) mayor de edad, titular de la Cédula de Identidad N° 
					<b><u><?php echo $cedula;?></u></b>, hábil, y  <?php echo $text;?>
					 
					Por medio del presente Compromiso reconozco expresamente la relación contractual existente entre <?php echo $empresa;?>, 
					y mi persona; por tal motivo acepto el pago íntegro de Bolívares, <b><u><?php echo $montoLetras;?></b></u> (BS. <b><u><?php echo $monto;?></b></u>)
					, que me obligo a realizar en <b><u><?php echo $num;?></u></b> <?php echo $text1;?>  
					, durante el plazo establecido<?php if($mesI != ''){?>, desde el mes de <b><u><?php echo $mesI;?></b></u> del Año <b><u><?php echo $anoI;?></b></u> 
					y finalizando en el mes de <b><u><?php echo $mesF;?></b></u>, del Año <b><u><?php echo $anoF;?></b></u>.<?php } ?>
					<?php echo $text2;?> los días comprendidos entre el Primero (1ero) y el último día de cada mes.
					<br><br>Es de mi conocimiento que el <?php echo $text1;?> se efectuará antes de su vencimiento, quedando entendido que los intereses de mora serán calculados sobre la tasa actual de financiamiento, 
					en caso de incumplimiento (Del <?php echo $text1;?>), autorizo plenamente a <?php echo $empresa;?>, a que proceda a efectuar los descuentos de la cuenta <b><u><?php echo $tipoC;?></b></u>, 
					signada con el N°<b><u><?php echo $cuenta;?></b></u>, del banco <b><u><?php echo $banco;?></b></u>, en los tiempos que sean posibles.
					<br><br>El presente compromiso de pago permanecerá vigente hasta tanto se cumpla fielmente con los depósitos en las fechas indicadas durante el plazo establecido en esta Acta.
					<br><br>En la Ciudad de <b><u><?php echo $ubicacion;?></b></u> a los <b><u><?php echo $dia;?></b></u> días del mes de <b><u><?php echo $mes;?></b></u> del año <b><u><?php echo $ano;?></b></u>.<br><br><br><br><br><br>
					
					<br><br><br>
							<table border="0" style="width:100%; height: 100px">
						<tr>
							<td style="width:100px; height: 80px" id="huella">
								&nbsp;
							</td>
							<td align="center"><br><br>
									_______________________________________<br>
									Firma Recibi Conforme.- <br>
									C.I: <?php echo $cedula;?><br>
									Nombre: <?php echo $nombre;?><br><br>
							</td>
							<td style="width:100px; height: 80px" id="huella">
								&nbsp;
							</td>
						</tr>
						<tr>
						<td style="text-align: left;font-size: 10px;"><b>Pulgar Izquierdo</b></td>
						<td style="text-align: center">&nbsp;</td>
						<td style="text-align: right;font-size: 10px;"><b>Pulgar Derecho</b></td>
						</tr>
					</table>
					USUARIO: <?php echo strtoupper($usuario);?> 
				</div>
			</td>
		</tr>
	</table>
	</center>
	
	
</body>
</html>
