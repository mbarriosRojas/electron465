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
	</style>
	
</head>
<body>
	<?php 		
					if ($arr['empresa'] == 0){
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
						echo '<font size="3"><b>N&#186;:' . $factura . '</b></font>';
							 
						
				?>
				</td></tr></table>
			<br><center><h2>CONTROL DE PAGOS</h2></center>	
	<center><table width="600px" >
		
		<tr>
			<td class="tabla">
			<?php 
			$unico = "<table><tr><td valign='top'>";
			$especial = "";
			foreach ($arr as $clave => $cuotas){
				if($clave == "0"){
					
					$unico .= '<table border=1 width=250px cellspacing=1 celladding=1><tr>
								<td><b>Giros #</b></td>
								<td><b>Fecha</b></td>
								<td><b>Pagado</b></td>
								<td><b>Estatus</b></td>
								</tr>';
					$numero_tabla = 0;
					$i = 0;
					$cant = count($cuotas);
					foreach ($cuotas as $datos){
						$numero_tabla++;
						if ($numero_tabla > 20) {
							$unico .= '</table></td><td valign="top"><table border=1 width=250px cellspacing=1 celladding=1><tr>
											<td><b>Giros #</b></td>
											<td><b>Fecha</b></td>
											<td><b>Pagado</b></td>
											<td><b>Estatus</b></td>
											</tr>';
							$numero_tabla = 1;
						}	
						$Giro = $datos['cuota'] . '/' . $cant;
						$unico .= '<tr><td>' . $Giro . '</td><td>' . $datos['fecha'] . '<td><b>'. $datos['monto'] .' Bs.</b></td><td><b>'. $datos['estatus'] .'</b></td></tr>';	
					}
					$unico .= '</table></td></tr></table>';
				}elseif ($clave != "empresa"){
					foreach ($cuotas as $datos){
					$especial .= '<b>.- Giro Especial </b><br><table border=1 width=600px><tr>
									<td><b>Giro #</b></td>
									<td><b>Fecha</b></td>
									<td><b>Pagado</b></td>
									<td><b>Estatus</b></td>
									</tr>';
						$Giro = '1/1';
						$especial .= '<tr><td>' . $datos['cuota'] . '/1 </td><td> ' . $datos['fecha'] . '</td>
							<td align=right>' . $datos['monto'] . ' Bs.&nbsp;&nbsp;</td>
							<td><b>' . $datos['estatus'] . '</b></td></tr>';
						$especial.= '</table>';	
					}
				}	
				
			}
			
			
			?>
				
			<?php echo $unico;?>
			<?php echo $especial;?><BR>
			<BR><BR><BR><BR>
				 
				 Firma Recibi Conforme.-
				 
				 
				</td>
			</tr>
		</table>
	</center>
	</body>
	</html>
	