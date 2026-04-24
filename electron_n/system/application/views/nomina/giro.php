<html>
<head>
	<title>Control de Giros</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	
	<title><?php echo __TITLE__ ?></title>
	

	<style type="text/css">

		/*demo page css*/
			body{ font: 10px sans-serif; margin: 0px;}

		 	* html .ui-autocomplete {
				height: 100px;
			}
			table, tr, td{
				 font: 9px sans-serif, verdana;
			}
			.tabla {
				font: 12px sans-serif, verdana;
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
				?>
	<center><table width="800px">
		<tr>
			<td>
				<div style="text-align:justify">
				<table><tr><td style="width:700px"><center><font size="3"><b>
				<?php echo $empresa;?><br>
				Domicilio Fiscal M&eacute;rida<br>
				
				Rif: <?php echo $Rif?>
				<br>
				</B></font></center>	
				</td><td style="width:100px">
				<?php 
						echo '<font size="3"><b>N&#186;:' . $contrato . '</b></font>';
							 
						
				?>
				</td></tr></table>
			<br><center><h2>CONTROL DE GIROS</h2></center>	
	<center><table width="600px" >
		
		<tr>
			<td class="tabla">
				
			Se servir&aacute;n ustedes mandar a pagar por estos giros a la orden de <?php echo '<b>' . $empresa . '</b>';?> de la siguiente manera:<BR><BR>
			<?php echo $unico;?>
			<?php echo $especial;?><BR>
			Librada para ser pagada, sin aviso y sin protesto:<BR><BR>
				 A: <?php echo $nombre;?>, Titular de la C&eacute;dula: <?php echo $cedula;?>
				 <br><br>
				 Aceptada para ser pagada a su vencimiento sin aviso y sin protesto:<br><br><br><br>
				</td>
			</tr>
		</table>
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
					</table></center>
	</center>
	</body>
	</html>
	