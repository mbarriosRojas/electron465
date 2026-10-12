<?php
?>
		
<table cellpadding="3" cellspacing="3" border=0>
 
 		<tr>
		<td class="formulario">Cr&eacute;ditos :</td>
		<td>
			<select name="txtCreditos"	id="txtCreditos"  style="width: 400px;" class="inputxt">
				<option value='0'>POR ACEPTAR (EN PROCESO)</option>
				<option value='1'>ACEPTADOS (EN COBRANZA)</option>
				<option value='2'>RECHAZADOS (EN PROCESO)</option>
				
			</select>
		</td>
				<td rowspan="2" class="formulario" valign="bottom">
			<input type="button" onclick="Reportes('<?php echo __LOCALWWW__ ?>');" value="Generar Consulta" style="width:120px; height:43px" class="inputxt">
		</td>
		</tr>
 
	<tr>
    <td style="width: 140px;" class="formulario">Dependencia Adscrita: </td>
    <td colspan="2" >
    	<select name="txtDependencia"	id="txtDependencia" style="width: 400px;" class="inputxt">
    		
    		<option value=20>TODOS</option>
				<option value=4>MERIDA  - ESTADO MERIDA</option>
				<option value=7>MERIDA 2  - ESTADO MERIDA </option>
				<option value=9>MERIDA 3 - ESTADO MERIDA</option>
				<option value=10>LAS TEJAS - ESTADO MERIDA</option>
				
				<option value=1>EL VIGIA  - ESTADO MERIDA</option>
				<option value=2>SANTA BARBARA  - ESTADO ZULIA</option>
				
				<option value=0>SAN CRISTOBAL ESTADO TACHIRA</option>
				<option value=5>YOHANDER AGUILLON</option>
				<option value=6>ALVARO ZAMBRANO</option>
				<option value=3>NELSON CORDOBA - ESTADO BARINAS</option>
				
				<option value=11>BARINAS ESTADO BARINAS</option>
				<option value=12>MARACAIBO ESTADO ZULIA</option>
				<option value=23>ADRIANA  - ESTADO MERIDA</option>
				<option value=24>DIRIAN  - ESTADO MERIDA</option>
				<option value=26>ANYI  - SANTA BARBARA ESTADO ZULIA</option>
				<option value=27>MARLIN  - ESTADO MERIDA</option>
				<option value=29>JHON  - ESTADO MERIDA</option>
				<option value=30>AUXILIAR  - ESTADO MERIDA</option>
				<option value=32>MARIBAL  - ESTADO MERIDA</option>
				<option value=33>DHARMA  - ESTADO MERIDA</option>
				<option value=34>NELSON CORDOBA  - ESTADO TACHIRA</option>
				<option value=36>KARY  - ESTADO MERIDA</option>
				<option value=37>JELKA  - ESTADO MERIDA</option>
				<option value=41>CAJA SECA  - ESTADO MERIDA</option>
				
							
			</select>
		</td>

  </tr>
  
   <tr>
		<td class="formulario">Nomina :</td>
		<td>
			<select name="txtNominaProcedencia"	id="txtNominaProcedencia" style="width: 400px;" class="inputxt">
				<option>TODOS</option>

				<option>INJUVEN</option>
				<option>COORPOSALUD</option>
				<option>IPOSTEL</option>
				<option>IPOSTEL - JUBILADOS</option>
				<option>ALCALDIA DEL LIBERTADOR</option>
				
				<option>MINISTERIO DEL PODER POPULAR PARA LA EDUCACION</option>
				<option>MINISTERIO DEL PODER POPULAR PARA LA EDUCACION - JUBILADO</option>
				<option>GOBERNACION DEL ESTADO MERIDA</option>
				<option>DIRECCION GENERAL DEL PODER POPULAR POLICIA DEL ESTADO MERIDA</option>
				<option>POLICIA CAMPO ELIAS</option>
				<option>POLICIA VIAL</option>
				<option>DIRECCION GENERAL DEL PODER POPULAR BOMBEROS DEL ESTADO MERIDA</option>
				<option>DIRECCION GENERAL DEL PODER POPULAR PARA LA EDUCACION ESTADO MERIDA</option>
				
				<option>ULA</option>
				<option>AEULA</option>
				<option>GOBERNACION DEL ESTADO TACHIRA</option>
				<option>INMECA</option>				
				<option>AGUAS DE MERIDA</option>
				<option>COORPOELEC</option>
				<option>CANTV</option>
				<option>PDVSA</option>
				<option>PEQUIVEN</option>
				<option>GOBERNACION DEL ESTADO ZULIA</option>
				<option>PODER JUDICIAL</option>
				<option>GUARDIA NACIONAL</option>
				<option>DIRECCION DE PUERTOS Y AEROPUERTOS DE VENEZUELA</option>
				<option>SENIAT</option>
				<option>CATIVEN</option>
				<option>INMECA</option>
				<option>PDVAL</option>
				<option>MERCAL</option>
				<option>INAM</option>
				<option>INAM - JUBILADOS</option>
				<option>-- PARTICULAR --</option>
			
			</select>
		</td>
			<td rowspan="2" class="formulario" valign="bottom">
				<!-- 			<input type="button" onclick="Imprimir_Reportes('<?php echo __LOCALWWW__ ?>');" value="Imprimir Consulta" style="width:120px; height:43px" class="inputxt"> -->
		</td>
	</tr>
		<tr>
		<td class="formulario">Linaje:</td>
		<td>
			<select name="txtCobrado"	id="txtCobrado"  style="width: 400px;" class="inputxt">
				<option>TODOS</option>
				<option>NOMINA</option>
				<option>SOFITASA</option>
				<option>BICENTENARIO</option>
				<option>BOD</option>
				<option>PROVINCIAL</option>
				<option>VENEZUELA</option>
				<option>BANESCO</option> 
				<option>INDUSTRIAL</option>
				<option>MERCANTIL</option>
				<option>DEL SUR</option>			
				<option>CREDINFO</option>
			</select>
		</td>
		
		</tr>
		<tr>
			<td><label for="from">Desde: </label></td><td colspan="3">
				<div class="demo">					
					<input type="text" id="fecha_desde" name="fecha_desde"  class="inputxt"/>
					&nbsp;&nbsp;<label for="to">Hasta: </label>&nbsp;&nbsp;
					<input type="text" id="fecha_hasta" name="fecha_hasta"  class="inputxt"/>
				</div>				
			</td>			
		</tr>
</table>

	