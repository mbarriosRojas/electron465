<table cellpadding="3" cellspacing="3" border=0>
 	<tr>
		<td >Cr&eacute;ditos :</td>
		<td>
			<select name="txtCreditos"	id="txtCreditos"  style="width: 400px;">
				<option value=9>Facturas (Historial)</option>
				<option value=1>Creado (En Ventas)</option>
				<option value=2>Revision de Artefactos</option>
				<option value=3>Por Aceptar (En Ventas)</option>
				<option value=4>Por Depositar</option>
				<option value=8>Depositado</option>
				<option value=5>En Cobranza</option>
				<option value=6>Aceptados Por Cobrar</option>
				<option value=7>Actualmente Cobrando</option>
				<option value=0>Rechazados (En Ventas)</option>				
			</select>
		</td>
	</tr>
 
	<tr>
    	<td style="width: 140px;">Usuarios Dependendientes: </td>
    	<td colspan="2" >
    		<select name="txtDependencia"	id="txtDependencia" style="width: 400px;">
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
		<td>Nomina :</td>
		<td>
			<select name="txtNominaProcedencia"	id="txtNominaProcedencia" style="width: 400px;">
				<option>TODOS</option>
				<?php echo $lista;?>
			
			</select>
		</td>
			<td rowspan="2" valign="bottom">
				
		</td>
	</tr>
		<tr>
		<td >Linaje:</td>
		<td>
			<select name="txtCobrado"	id="txtCobrado"  style="width: 400px;">
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
					<input type="text" id="fecha_desde" name="fecha_desde" />
					&nbsp;&nbsp;<label for="to">Hasta: </label>&nbsp;&nbsp;
					<input type="text" id="fecha_hasta" name="fecha_hasta" />
				</div>				
			</td>			
		</tr>
</table>