<table>
	<tr> 	
		<td style='width:80px;'>Por Empresa: <font color='#B70000'>(*)</td>
		<td style="width: 100px;" align="left" colspan="5">
			<select name="txtEmpresaCuadre"	id="txtEmpresaCuadre"style="">
				<OPTION VALUE =9 >TODAS</option>
				<OPTION VALUE =0 >COOPERATIVA ELECTRON 465 RL.</option>
				<OPTION VALUE =1 >GRUPO ELECTRON 465 C.A</option>					
			</select>
		</td>		
	</tr>
	<tr>
		<td>Nomina :</td>
		<td>
			<select name="txtNominaProcedenciaCuadre"	id="txtNominaProcedenciaCuadre" style="width: 400px;">
				<option>TODOS</option>
				<?php
				  echo $lista;
				?>
			</select>
		</td>
	</tr>
	<tr>
		<td >Linaje:</td>
		<td>
			<select name="txtCobradoCuadre"	id="txtCobradoCuadre"  style="width: 400px;">
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
	<!-- <tr>
		<td >Tipo:</td>
		<td align="left" class="formulario">
			<select name="txtFormaContratoCuadre"	id="txtFormaContratoCuadre" style="width: 140px;" >
				<option value=0>UNICO</option>
				<option value=1>AGUINALDOS - CUOTA ESPECIAL</option>
				<option value=2>VACACIONES - CUOTA ESPECIAL</option>
				<option value=3>EXTRA</option>
				<option value=4>UNICO - EXTRA</option>
				<option value=5>ESPECIAL - EXTRA</option>
				<option value=9>TODOS</option>
			</select>
		</td>
	 </tr>-->
	<tr>
		<td><label for="from">Desde: </label></td><td colspan="3">
				<div class="demo">					
					<input type="text" id="fecha_desde_cuadre" name="fecha_desde_cuadre" />
					&nbsp;&nbsp;<label for="to">Hasta: </label>&nbsp;&nbsp;
					<input type="text" id="fecha_hasta_cuadre" name="fecha_hasta_cuadre" />
				</div>				
			</td>
	</tr>
</table>