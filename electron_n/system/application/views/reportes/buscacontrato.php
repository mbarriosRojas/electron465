<table>
	<tr> 	
		<td style='width:80px;'>Por Empresa: <font color='#B70000'>(*)</td>
		<td style="width: 100px;" align="left" colspan="5">
			<select name="txtEmpresa"	id="txtEmpresa"style="">
				<OPTION VALUE =9 >TODAS</option>
				<OPTION VALUE =0 >COOPERATIVA ELECTRON 465 RL.</option>
				<OPTION VALUE =1 >GRUPO ELECTRON 465 C.A</option>					
			</select>
		</td>		
	</tr>
	<tr> 	
		<td style='width:80px;'>Tipo Contrato: <font color='#B70000'>(*)</td>
		<td style="width: 100px;" align="left" colspan="5">
			<select name="txtTipoContrato"	id="txtTipoContrato"style="">
				<OPTION VALUE =0 >DOMICILIACION</option>
				<OPTION VALUE =6 >VOUCHER</option>				
			</select>
		</td>		
	</tr>
	<tr>
		<td>Nomina :</td>
		<td>
			<select name="txtNominaProcedenciaC"	id="txtNominaProcedenciaC" style="width: 400px;">
				<option>TODOS</option>
				<option>MINISTERIO DEL PODER POPULAR PARA LA EDUCACION - JUBILADO</option>
				<?php
				  echo $lista;
				?>
			</select>
		</td>
	</tr>
	<tr>
		<td >Linaje:</td>
		<td>
			<select name="txtCobradoC"	id="txtCobradoC"  style="width: 400px;">
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
				<option>COTERO</option>
			</select>
		</td>
		
	</tr>
	<tr>
		<td >Tipo:</td>
		<td align="left" class="formulario">
			<select name="txtFormaContrato"	id="txtFormaContrato" style="width: 140px;" >
				<option value=0>UNICO</option>
				<option value=7>BONOEXPRESS</option>
				<option value=1>AGUINALDOS - CUOTA ESPECIAL</option>
				<option value=2>VACACIONES - CUOTA ESPECIAL</option>
				
				<option value=3>EXTRA</option>
				
				<option value=4>UNICO - EXTRA</option>
				<option value=5>ESPECIAL - EXTRA</option>
				<option value=6>UNICO - PRONTO PAGO</option>
                <option value=11>TARJETA-ALIMENTACION</option>
			</select>
		</td>
	</tr>
	<tr>
		<td >Periocidad:</td>
		<td>
			<select name="txtPeriocidad_Contratos"	id="txtPeriocidad_Contratos"style="width: 140px;" >
			<option value=99>TODOS</option>
			<option value=4>MENSUAL</option>
			<option value=9>MENSUAL-BONOEXPRESS</option>
			<option value=0>SEMANAL</option>
			<option value=1>CATORCENAL</option>
			<option value=2>QUINCENAL 15 - 30</option>
			<option value=3>QUINCENAL 10 - 25</option>
			<option value=5>Mensual - 15</option>
			<option value=6>Mensual - 30</option>
			<option value=7>-- ANUAL --</option>
			<option value=8>MENSUAL-10</option>
			<option value=9>MENSUAL-25</option>
			
			</select>
		</td>
	</tr>
	<tr>
		<td style="width:150px">Inicio del Descuento <font color='#B70000'>(*)</font> :</td>
			<td align="left"  style="width:210px" colspan=3>	
			<select name="txtDia" id="txtDia" style="width: 55px;">
                <option value=''>Seleccione</option>
				<?php 	for($i = 1; $i <= 9; $i++){		?>
				<option value='<?php echo '0'.$i ?>'><?php echo $i ?></option>
				<?php }	?>
				<?php 	for($i = 10; $i <= 31; $i++){		?>
				<option value='<?php echo $i ?>'><?php echo $i ?></option>
				<?php }	?>
			</select>
			<select name="txtMes"	id="txtMes" style="width: 55px;">
				<option value=''>Seleccione</option>
				<?php 	for($i = 1; $i <= 9; $i++){		?>
				<option value='<?php echo '0'.$i ?>'><?php echo $i ?></option>
				<?php	}	?>
				<?php 	for($i = 10; $i <= 12; $i++){		?>
				<option value='<?php echo $i ?>'><?php echo $i ?></option>
				<?php	}	?>
			</select>
			<select name="txtAno"	id="txtAno" style="width: 60px;">
				<?php 	for($i = 2022; $i <= 2032; $i++){		?>
				<option value='<?php echo $i ?>'><?php echo $i ?></option>
				<?php	}	?>
			</select>
			</td>
	</tr>
</table>
