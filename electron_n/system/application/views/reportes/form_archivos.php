<table>
	<tr> 	
		<td style='width:80px;'>Tipo: <font color='#B70000'>(*)</td>
		<td style="width: 100px;" align="left" colspan="5">
			<select name="txtTipoA"	id="txtTipoA"style="">
				<OPTION VALUE ='' >Seleccione</option>
				<OPTION VALUE ='A' >Afiliacion</option>
				<OPTION VALUE ='C' >Cobranza</option>
				<OPTION VALUE ='M' >Manual</option>					
			</select>
		</td>		
	</tr>
	<tr> 	
		<td style='width:80px;'>Empresa: <font color='#B70000'>(*)</td>
		<td style="width: 100px;" align="left" colspan="5">
			<select name="txtEmpresa"	id="txtEmpresa"style="">
				<OPTION VALUE =0 >COOPERATIVA ELECTRON 465 RL.</option>
				<OPTION VALUE =1 >GRUPO ELECTRON 465 C.A</option>
				<OPTION VALUE =2 >AMBAS</option>					
			</select>
		</td>		
	</tr>
	<tr>
		<td >Linaje:</td>
		<td>
			<select name="txtCobrado"	id="txtCobrado"  style="width: 400px;" onchange="muestra_nomina()">
			</select>
		</td>
		
	</tr>
	<tr>
		<td >Nomina:</td>
		<td>
			<select name="txtNomina"	id="txtNomina"  style="width: 400px;" >
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
			</select>
		</td>
	</tr>
	<tr>
		<td >Periodicidad:</td>
		<td align="left" class="formulario">
			<select name="txtPeriodicidad"	id="txtPeriodicidad" style="width: 140px;" >
				<option value=4>MENSUAL</option>
				<option value=9>MENSUAL-BONOEXPRESS</option>
				<option value=0>SEMANAL</option>
				<option value=1>CATORCENAL</option>
				<option value=2>QUINCENAL 15 - 30</option>
				<option value=3>QUINCENAL 10 - 25</option>
				<option value=5>TRIMESTRAL</option>
				<option value=6>SEMESTRAL</option>
				<option value=7>ANUAL</option>
				<option value=8>MENSUAL-10</option>
				<option value=9>MENSUAL-25</option>
			</select>
		</td>
	</tr>
	<tr>
		<td style="width:150px">Hasta La Fecha <font color='#B70000'>(*)</font> :</td>
			<td align="left"  style="width:210px" colspan=3>
			<select name="txtDia"	id="txtDia" style="width: 140px;">
				<option value='00'>SEMANAL</option>
				<option value='01'>MENSUAL</option>
				<option value='10'>QUINCENAL 10</option>
				<option value='15'>QUINCENAL 15</option>
				<option value='25'>QUINCENAL 25</option>
				<option value='30'>QUINCENAL 30</option>
			</select>	
			<select name="txtMes"	id="txtMes" style="width: 55px;">
				<option value=0>Mes:</option>
				<?php 	for($i = 1; $i <= 9; $i++){		?>
				<option value='<?php echo '0'.$i ?>'><?php echo $i ?></option>
				<?php	}	?>
				<?php 	for($i = 10; $i <= 12; $i++){		?>
				<option value='<?php echo $i ?>'><?php echo $i ?></option>
				<?php	}	?>
			</select>
			<select name="txtAno"	id="txtAno" style="width: 60px;">
				<option value=0>A&ntilde;o:</option>
				<?php 	for($i = 2014; $i <= 2018; $i++){		?>
				<option value='<?php echo $i ?>'><?php echo $i ?></option>
				<?php	}	?>
			</select>
			</td>
	</tr>
	
	 <tr>
    <td >Metodo de Picar:</td>
    <td align="left" class="formulario">
      <select name="txtPicar"  id="txtPicar" style="width: 140px;" >
        <option value=10>10 Bs.</option>
        <option value=20>20 Bs.</option>
        <option value=30>30 Bs.</option>
        <option value=40>40 Bs.</option>
        <option value=50>50 Bs.</option>
        <option value=60>60 Bs.</option>
        <option value=70>70 Bs.</option>
        <option value=80>80 Bs.</option>
        <option value=90>90 Bs.</option>
        <option value=100>100 Bs.</option>
        <option value=150>150 Bs.</option>
        <option value=200>200 Bs.</option>
        <option value=250>250 Bs.</option>
        <option value=300>300 Bs.</option>
        <option value=350>350 Bs.</option>
        <option value=400>400 Bs.</option>
        <option value=450>450 Bs.</option>
        <option value=500>500 Bs.</option>
        <option value=750>750 Bs.</option>
        <option value=1000>1000 Bs.</option>
        <option value=1250>1250 Bs.</option>
        <option value=1500>1500 Bs.</option>
        <option value=1750>1750 Bs.</option>
        <option value=2000>2000 Bs.</option>
        <option value=2500>2500 Bs.</option>
        <option value=3000>3000 Bs.</option>
        <option value=3500>3500 Bs.</option>
        <option value=4000>4000 Bs.</option>
	<option value=4500>4500 Bs.</option>
	<option value=5000>5000 Bs.</option>
	<option value=5500>5500 Bs.</option>
	<option value=6000>6000 Bs.</option>
	<option value=6500>6500 Bs.</option>
	<option value=7000>7000 Bs.</option>
	<option value=7500>7500 Bs.</option>
	<option value=8000>8000 Bs.</option>
	<option value=8500>8500 Bs.</option>
	<option value=9000>9000 Bs.</option>
	<option value=9500>9500 Bs.</option>
	<option value=10000>10000 Bs.</option>
          <option value=15000>15000 Bs.</option>
          <option value=20000>20000 Bs.</option>
      </select>
    </td>
  </tr>
	
</table>

Los Archivos son dividos en cuotas según sea el metodo, ejemplo: una cuota de 302 Bs.<br><br>
1.- Usando 10 Bs. Crearía 30 Cuotas por 10 Bs., más una de 2 Bs.<br>
2.- Usando 100 Bs. Crearía 3 Cuotas por 100 Bs., más una de 2 Bs.<br>
3.- Usando 500 Bs. Crearía 1 Cuotas por el monto total.<br>
