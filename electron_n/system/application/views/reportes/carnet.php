<HTML>
<HEAD>
<meta http-equiv="Content-Type" content="charset=utf-8" />
<title><?php echo __TITLE__ ?></title>
<style>
	.clase{
		
	}
</style>
<table border="1">
	<tr>
		<td valign="top"   style='width:185px; height:293px;background: url(<?php echo __IMG__ ?>carnet.png) ;background-repeat: no-repeat;background-size: 100%;'>
	
			
			<table border="0">
				<tr ><br></tr>

				<tr style="height: 150px"><td valign="top" align="center">
						<font style="font-size: 11px; font-family: Arial, Helvetica, sans-serif">
						<b>Usuario Titular</b><br>
						<?php 
							echo  'V-', $documento_id, '<br>'; 
							echo $nombre, '<br><br><br><br><br><br>';
							
							echo '<h1>', $nro_documento, '</h1>'
						?>	
            		
						</font>
					
				</td></tr>
			</table>
		</td>
		<td valign="top" style='width:185; height:293px;background: url(<?php echo __IMG__ ?>carnet2.png) ;background-repeat: no-repeat;background-size: 100%;'>

				<table border="0">
					<tr style="height: 60px"><td valign="top"></td></tr>
				
				<tr style="height: 83px">
					<td align="center" >
							<font style="font-size: 10px; font-family: Arial, Helvetica, sans-serif">
							El Uso de este carnet esta sujeto a las condiciones establecidas en el contrato
							celebrado entre las partes.
							
							</font><br><br><br><br>
							Visitanos en <br> www.electron465.com							
							<br><br><br><br><b>
							<font style="font-size: 11px; font-family: Arial, Helvetica, sans-serif" >
								Su Proveedor de Bienes y Servicios Por excelencia.
							</font></b>
					</td>
					
				</tr>
			</table>
			
		</td>
	</tr>
</table>