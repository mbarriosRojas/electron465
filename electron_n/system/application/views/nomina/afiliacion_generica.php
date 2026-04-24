<?php
    
?>
<html>
<head>
	<title>Datos Personales</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	
	<title><?php echo __TITLE__ ?></title>
	

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
	<center><table width="800px">
		<tr>
			<td>
				<div style="text-align:justify">
					<?php 
						$empresa = 'GRUPO ELECTRON C.A, COOPERATIVA ELECTRON 465 R.L';
						$strContenido = 'AFILIACION';
					?>
				<center>	
				<h2>CONTRATO DE <?php echo $strContenido;?>.</h2></center>
				
					Yo, <?php echo $nombre;?>, de nacionalidad  VENEZOLANO, domiciliado en 
					<?php echo $direccion;?>, titular de la c&eacute;dula de identidad 
					<?php echo $cedula;?>; autorizo expresamente a GRUPO ELECTRON C.A, Rif: J-29837846-8 
					y COOPERATIVA ELECTRON 465 R.L, Rif: J-31207419-1,	para que conforme a sus instrucciones, y 
					en la fechas indicadas por &eacute;sta, efectu&eacute; los tramites y	procesos de cobro por las cuotas indicadas en las <b>LETRAS DE CAMBIOS</b> 
					correspondiente  a los servicios de FINACIMIENTO; mismo que podr&aacute;n ser 
					debitados de las cuentas bancaria que esten mi nombre en las diversas entidades bancarias.
					<br>
					
					<br><br>
					
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
					<table><tr><td style="width:500px">
					_______________________________________<br>
					Firma Recibi Conforme.- <br>
					C.I: <?php echo $cedula;?><br>
					Nombre: <?php echo $nombre;?><br><br>
					</td><td style="width:100px" align="center">
						Huella Dactilar
					</td>
					</tr></table>
					
				</div>
			</td>
		</tr>
	</table>
	</center>
	
	
</body>
</html>
