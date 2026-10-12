<table cellpadding="3" cellspacing="3" border=0>
 
 	<tr>
		<td class="formulario">Cr&eacute;ditos :</td>
		<td>
			<select name="txtCreditos"	id="txtCreditos"  style="width: 400px;" class="inputxt">
				<option value='0'>POR ACEPTAR (EN PROCESO)</option>
				<option value='1'>ACEPTADOS (EN COBRANZA)</option>
				<option value='2'>RECHAZADOS (EN PROCESO)</option>
				
			</select>
			<input type="hidden" name="txtDependencia"	id="txtDependencia" style="width: 400px;" class="inputxt" value='<?php echo $iPosicion;?>'>
		</td>
		<td rowspan="3" class="formulario" valign="bottom">
			<input type="button" onclick="Reportes('<?php echo __LOCALWWW__ ?>');" value="Generar Consulta" style="width:120px; height:43px" class="inputxt">
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
	</tr>
	<tr>
		<td class="formulario">Cobrando Por:</td>
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
</table>
