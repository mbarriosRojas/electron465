<?php
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
?>
<html>
<head>
	<title>Constancia de Entrega</title>
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
	</style>
	
</head>
<body>
				<?php
				
					$empresa = 'GRUPO ELECTRON 465 C.A';
					$Rif = ' J-29837846-8';
					$empresa = 'COOPERATIVA ELECTRON 465 RL.';
					$Rif = ' J-31207419-1';
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
				</td></tr></table>	
				<center>	
				<h2>Constancia de Entrega</h2></center><br><br>
				
					Por medio de la presente Yo, _____________________________, Venezolano (a) mayor de edad, titular de la Cédula de Identidad N° ____________________________ 
					 hago entrega de <b><u><?php echo $entrega.' '.$modelo;?></b></u> a <b><u><?php echo $a_quien;?> </b>, titular de la Cédula de Identidad N°<?php echo $cedula;?></u>, firmando para dar  valides a la entrega.<br>
					A partir de este momento me deslindo de su mal uso, extravio o cualquier situación con la que se relacione el bien.
					<?php echo $tabla; ?>
					
					<br><br>En la Ciudad de <b><u><?php echo lugar_letras($ciudad);?></b></u> a los <b><u><?php echo $dia;?></b></u> días del mes de <b><u><?php echo $mes;?></b></u> del año <b><u><?php echo $ano;?></b></u>.<br><br><br><br><br><br>
					
					<br><br><br><br><br><br><table border=0><tr><td style="width:500px">
					_______________________________________<br>
					Firma Quien Recibe .- <br>
					</td><td style="width:100px" >
					_______________________________________<br>
					Firma Quien Entrega .- <br>
					
					
					</td></tr><tr><td>
					Nombre: <?php echo $a_quien?><br><br>
					</td><td style="width:100px" ></td>
					</tr></table>
					USUARIO: <?php echo strtoupper($usuario);?> 
				</div>
			</td>
		</tr>
	</table>
	</center>
	
	
</body>
</html>
