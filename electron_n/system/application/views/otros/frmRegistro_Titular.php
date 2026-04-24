<script>
function limpiar(){
	$("#txtNacionalidad1").val('');
	$("#txtCedulaTitular").val('');
	$("#txtNacionalidad2").val('');
	$("#txtCedulaDependiente").val('');
	$("#txtParentesco").val('');
	$("#txtNombre1").val('');
	$("#txtDiaNacimiento").val('');
	$("#txtMesNacimiento").val('');
	$("#txtAnoNacimiento").val('');
	$("#txtSexo").val('');
	$("#txtEdad").val('');
	$("#txtGrupo").val('');
	$("#txtCargo").val('');
	$("#txtEdocivil").val('');
	$("#txtCiudad").val('');
	$("#txtEstado").val('');
	$("#txtTlfHabitacion").val('');
	$("#txtTlfCelular").val('');
	$("#txtCoberturaD").val('');
	$("#txtCoberturaR").val('');
}
</script>
<table style="width:600px" border="0" cellspacing="3" cellpadding="0"  >
  <tr>
    <td style="width: 220px;" >C&eacute;dula Titular(*):</td>
    <td style="width: 185px;" align="left" >
      <select name="txtNacionalidad1" id="txtNacionalidad1" style="width: 50px;">
        <option>V-</option>
        <option>E-</option>
      </select>
      <input name="txtCedulaTitular" type="text" style="width: 125px;" id="txtCedulaTitular" maxlength="10" onKeyPress="Presionar(event)" value="">
    </td>

 
  </tr>
  <tr>
    <td style="width: 220px;" >C&eacute;dula Dependiente:(*):</td>
    <td style="width: 185px;" align="left" >
      <select name="txtNacionalidad2" id="txtNacionalidad2" style="width: 50px;">
        <option>V-</option>
        <option>E-</option>
      </select>
      <input name="txtCedulaDependiente" type="text" style="width: 125px;" id="txtCedulaDependiente" maxlength="10"  value="">
    </td>
	<td >Parentesco:</td>
    <td align="left" colspan="3">
      <select name="txtParentesco" id="txtParentesco" style="width: 150px;" >
      <option value="Conyuge">Conyuge</option>
      <option value="Hijo(A)">Hijo(A)</option>
      <option value="Padre">Padre</option>
      <option value="Madre">Madre</option>
      <option value="Abuelo(A)">Abuelo(A)</option>
      
    </select>
      
    </td>
 
  </tr>
  <tr>
    <td>Apellidos y Nombres: </td>
    <td align="left"  colspan=6 >
      <input name="txtNombre1"  type="text" id="txtNombre1" style="width: 460px;" value="">
    </td>
 </tr>
  <tr>
    <td >Fecha nacimiento:</td>
    <td align="left" ><select name="txtDiaNacimiento" id="txtDiaNacimiento" style="width: 55px;">
      <option>Dia:</option>
      <?php 
        for($i = 1; $i <= 31; $i++){
      ?>
        <option value='<?php echo $i ?>'><?php echo $i ?></option>
      <?php
			}
      ?>
    </select>
    <select name="txtMesNacimiento" id="txtMesNacimiento" style="width: 55px;">
      <option>Mes:</option>
      <?php 
          for($i = 1; $i <= 12; $i++){
      ?>
        <option value='<?php echo $i ?>'><?php echo $i ?></option>
      <?php
			}
      ?>
    </select>
    <select name="txtAnoNacimiento" id="txtAnoNacimiento" style="width: 65px;">
      <option>A&ntilde;o:</option>
        <?php 
          for($i = 1900; $i <= 2014; $i++){
      ?>
        <option value='<?php echo $i ?>'><?php echo $i ?></option>
      <?php
			}
      ?>
    </select></td>
    <td >Sexo:</td>
    <td align="right" ><select name="txtSexo" id="txtSexo" style="width: 150px;">
      <option selected="selected">Masculino</option>
      <option>Femenino</option>
    </select></td>
  </tr>
  <tr>
  	<td >Edad:</td>
    <td align="left" >
    	<input name="txtEdad" type="text" style="width: 180px;" id="txtEdad" value="">
      </td>
    <td >Grupo Sangu&iacute;neo:</td>
    <td align="left">
      <select name="txtGrupo" id="txtGrupo" style="width: 150px;" >
      <option value='O+'>O+</option>
      <option value='O-'>O-</option>
      <option value='A+'>A+</option>
      <option value='A-'>A-</option>
      <option value='B+'>B+</option>
      <option value='B-'>B-</option>
      <option value='AB+'>AB+</option>
      <option value='AB-'>AB-</option>
    </select>
    </td>   
  </tr>
  <tr>
    <td >Profesi&oacute;n:</td>
    <td align="left">
      <input name="txtCargo" type="text" style="width: 180px;" id="txtCargo" value="">
    </td>   
        
    <td style="width: 120px;">Estado Civil:</td>
    <td align="right">
      <select name="txtEdocivil" id="txtEdocivil" style="width: 150px;" >
      <option value=0>Soltero (A)</option>
      <option value=1>Casado (A)</option>
      <option value=2>Divorciado (A)</option>
      <option value=3>Viudo (A)</option>
      <option value=4>Concubino (A)</option>
    </select></td>
  </tr>
  
  <tr>
    <td >Ciudad:</td>
    <td align="left" >
      <input name="txtCiudad"  type="text" id="txtCiudad" style="width: 180px;" value="">
    </td>
    <td >Estado: </td>
    <td align="right" >
      <input name="txtEstado" id="txtEstado" type="text" style="width: 150px;" value="">
    </td>
  </tr>  
  <tr>
  	<td >Tel&eacute;fono de Habitaci&oacute;n:</td>
    <td align="left">
      <input name="txtTlfHabitacion" type="text" style="width: 180px;" id="txtTlfHabitacion" value="" />
    </td>
    <td >Tel&eacute;fono Celular:</td>
    <td align="left">
      <input name="txtTlfCelular" type="text" style="width: 150px;" id="txtTlfCelular" value="" />
    </td>
  </tr>
  	<td >Cobertura Disponible:</td>
    <td align="left">
      <input name="txtCoberturaD" type="text" style="width: 180px;" id="txtCoberturaD" value="" />
    </td>
    <td >Cobertura Retenida:</td>
    <td align="left">
      <input name="txtCoberturaR" type="text" style="width: 150px;" id="txtCoberturaR" value="" />
    </td>
  <tr>
    
  </tr>
  
  
</table>
