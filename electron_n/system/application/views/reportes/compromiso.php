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
						$empresa = 'COOPERATIVA ELECTRON 465 RL.';
						$Rif = ' J-31207419-1';
					}else{
						$empresa = 'GRUPO ELECTRON 465 C.A';
						$Rif = ' J-29837846-8';
					}
					if($factura == ''){
						$referencia = $contrato;
						$mrefe = " al contrato ";
					}else{
						$referencia = $factura;
						$mrefe = " a la factura ";
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
					echo '<font size="3"><b>N&#186;:' . $referencia . '</b><br></font>';
				?>
				</td></tr></table>	
				<center>	
				<h2>CONSTANCIA DE ENTREGA</h2></center>
				
					Yo, <?php echo $nombre;?>, de nacionalidad  VENEZOLANO, domiciliado en 
					<?php echo $direccion;?>, titular de la c&eacute;dula de identidad 
					<?php echo $cedula;?>;, doy constancia de recibido los Voucher de todas las 
					cuotas(<?php echo $cant;?>) asociadas <?php echo $mrefe;?> N° <?php echo $referencia;?>; 
					con la fechas y montos establecidos de pago y enumerados con seriales que se provee a continuación
					<br><br>
					<?php echo $lista;?>
					
					<br><br><br>
					Yo, <?php echo $nombre;?>, C.I: <?php echo $cedula;?>. Acepto las condiciones 
					de <b></b> establecidas por <?php echo $empresa;?>.Me comprometo a pagar las cuotas dentro del periodo establecido. 
					De igual manera me comprometo a pagar los intereses correspondientes, generados de mi deuda. <br><br><br><br><br>
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
