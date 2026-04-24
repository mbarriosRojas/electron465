<table style="background-color: #B6B6B6">
	<tr>
		<td><h2>INACTIVAR CONTRATO </h2>Recuerde que todos los datos asociados a este contrato NO SE REFLEJARAN EN LOS REPORTES.
		<br>
		<br>
		<table style="width:300px">
			<tr>
				<td class="formulario">&nbsp;N&uacute;mero de Contrato (*)</td>
				<td class="formulario">&nbsp;</td>
				
			</tr>
			<tr>
				<td class="formulario">
				<input type="text" class="inputxt" style="width:200px" name="txtContratoInactivar" id="txtContratoInactivar">
				</td>
				<td class="formulario">
				<button onclick="Respaldo_Inactivar_Contrato();">Inactivar</button>
				</td>
				<td><img src='<?php echo __IMG__?>cargando_tab.gif' style="margin: 0px 0 0px 0; padding: 0px 0px 0px 0px;" id = 'img_inactivar_contrato'></td>
			</tr>
		</table></td>
	</tr>
</table>
<table style="background-color: #C8E6C9">
	<tr>
		<td><h2> MODIFICAR N&Uacute;MERO DE CONTRATO <br></h2>Recuerde que debe estar seguro que el contrato por el cual va a cambiar no esta asignado a otro cliente.<br><br>
		<table style="width:500px">
			<tr>
				<td class="formulario">&nbsp;N&uacute;mero de Contrato (*)</td>
				<td class="formulario">&nbsp;Modificaci&oacute;n de Contrato</td>
				<td class="formulario">&nbsp;</td>
				<td></td>
			</tr>
			<tr>
				<td class="formulario">
				<input type="text" class="inputxt" style="width:200px" name="txtContrato_A" id="txtContrato_A">
				</td>
				<td class="formulario">
				<input type="text" class="inputxt" style="width:200px" name="txtContrato_N" id="txtContrato_N">
				</td>
				<td class="formulario">
				<button onclick="Respaldo_Modificar_Contrato();" >Modificar</button>
				</td>
				<td><img src='<?php echo __IMG__?>cargando_tab.gif' style="margin: 0px 0 0px 0; padding: 0px 0px 0px 0px;" id='img_modificar_contrato'></td>
			</tr>
		</table> </div> </td>
	</tr>
</table>
<br>
<br>
<table style="background-color: #B6B6B6">
	<tr>
		<td><h2>ELIMINAR CONTRATO </h2>Recuerde que todos los datos asociados a este contrato seran eliminados del sistema.
		<br>
		<br>
		<table style="width:300px">
			<tr>
				<td class="formulario">&nbsp;N&uacute;mero de Contrato (*)</td>
				<td class="formulario">&nbsp;</td>
				<td></td>
			</tr>
			<tr>
				<td class="formulario">
				<input type="text" class="inputxt" style="width:200px" name="txtContrato" id="txtContrato">
				</td>
				<td class="formulario">
				<button onclick="Respaldo_Eliminar_Contrato();">Eliminar</button>
				</td>
				<td><img src='<?php echo __IMG__?>cargando_tab.gif' style="margin: 0px 0 0px 0; padding: 0px 0px 0px 0px;" id = 'img_eliminar_contrato'></td>
			</tr>
		</table></td>
	</tr>
</table>
<br>
<br>
<table style="background-color: #C8E6C9">
	<tr>
		<td><h2> MODIFICAR POSESION DEL CONTRATO <br></h2>Recuerde de notificar al actual poseedor del contrato que este se cambiado.<br><br>
		<table style="width:500px">
			<tr>
				<td class="formulario">&nbsp;N&uacute;mero de Contrato (*)</td>
				<td class="formulario">Estado de posesion de Contrato</td>
				<td class="formulario"><label id="etiqueta" name="etiqueta">&nbsp;</label></td>
				<td></td>
			</tr>
			<tr>
				<td class="formulario">
				<input type="text" class="inputxt" style="width:200px" name="txtContrato_P" id="txtContrato_P" onblur="Ver_Posesion();">
				</td>
				<td class="formulario">
				<select class="inputxt" style="width:200px" name="cmbContrato_P" id="cmbContrato_P">
					<option value=0>SELECCIONE</option>
					<option value=1>ANALISTA DE VENTAS</option>
					<option value=2>REVISION</option>
					<option value=3>GERENTE DE VENTAS</option>
					<option value=4>POR DEPOSITAR</option>
					<option value=5>EN COBRANZA</option>
					<option value=6>ACEPTADOS COBRANDO</option>
				</select>
				</td>
				<td class="formulario">
				<button onclick="Respaldo_Modificar_Posesion();" >Modificar</button>
				</td>
			</tr>
		</table> </div> </td>
	</tr>
</table>
<br>
<br>
<table style="background-color: #B6B6B6">
	<tr>
		<td><h2> MODIFICAR UBICACION DEL CONTRATO <br></h2>Recuerde que debe estar seguro de cambiar la ubicacion ya que influye en los reportes.<br><br>
		<table style="width:500px">
			<tr>
				<td class="formulario">&nbsp;N&uacute;mero de Contrato (*)</td>
				<td class="formulario">Ubicaci&oacute;n De Contrato</td>
				<td class="formulario"><label id="etiqueta2" name="etiqueta2">&nbsp;</label></td>
				<td></td>
			</tr>
			<tr>
				<td class="formulario">
				<input type="text" class="inputxt" style="width:200px" name="txtContrato_Ub" id="txtContrato_Ub" onblur="Ver_Ubicacion();">
				</td>
				<td class="formulario">
				<select class="inputxt" style="width:200px" name="cmbContrato_Ub" id="cmbContrato_Ub">
					<option value=0>SELECCIONE</option>
					<?php echo $ubicacion;?>
				</select>
				</td>
				<td class="formulario">
				<button onclick="Respaldo_Modificar_Ubicacion();" >Modificar</button>
				</td>
			</tr>
		</table> </div> </td>
	</tr>
</table>
<br>
<br>
<table style="background-color: #C8E6C9">
	<tr>
		<td><h2> MODIFICAR LINAJE DEL CONTRATO <br></h2>Recuerde que debe estar seguro de cambiar el LINAJE ya que influye en los reportes.<br><br>
		<table style="width:500px">
			<tr>
				<td class="formulario">&nbsp;N&uacute;mero de Contrato (*)</td>
				<td class="formulario">Linaje De Contrato</td>
				<td class="formulario"><label id="etiquetal2" name="etiquetal2">&nbsp;</label></td>
				<td></td>
			</tr>
			<tr>
				<td class="formulario">
				<input type="text" class="inputxt" style="width:200px" name="txtContrato_Ln" id="txtContrato_Ln" >
				</td>
				<td class="formulario">
				<select class="inputxt" style="width:200px" name="cmbContrato_Ln" id="cmbContrato_Ln">
					<option value=0>SELECCIONE</option>
					<option>NOMINA</option>
					<option>BICENTENARIO</option>
					<option>BOD</option>
					<option>PROVINCIAL</option>
					<option>VENEZUELA</option>
					<option>BANESCO</option> 
					<option>INDUSTRIAL</option>
					<option>DOMICILIACION POR OFICINA</option>
					<option disabled="true">CAMARA MERCANTIL</option>
					<option>CREDINFO</option>
					<option disabled="true">INVERCRESA</option>
					<option>FONDO COMUN</option>
					<option disabled="true">100% BANCO COMERCIAL</option>
					<option disabled="true">DOMICILIACION POR OFICINA</option>
					<option disabled="true">SOFITASA</option> 
					<option>DEL SUR</option>		
					<option >CARONI</option>
					<option disabled="true">CARIBE</option>
					<option >MERCANTIL</option>
					<option disabled="true">INTER-BANCARIO</option>
				</select>
				</td>
				<td class="formulario">
				<button onclick="Respaldo_Modificar_Linaje();" >Modificar</button>
				</td>
			</tr>
		</table> </div> </td>
	</tr>
</table>
<br>
<br>
<table style="background-color: #B6B6B6">
	<tr>
		<td><h2> MODIFICAR PERIODICIDAD DEL CONTRATO <br></h2>Recuerde que debe estar seguro de cambiar la periodicida ya que influye en los reportes y cobros.<br><br>
		<table style="width:500px">
			<tr>
				<td class="formulario">&nbsp;N&uacute;mero de Contrato (*)</td>
				<td class="formulario">Periodicidad De Contrato</td>
				<td class="formulario"><label id="etiquetap" name="etiquetap">&nbsp;</label></td>
				<td></td>
			</tr>
			<tr>
				<td class="formulario">
				<input type="text" class="inputxt" style="width:200px" name="txtContrato_Pr" id="txtContrato_Pr" >
				</td>
				<td class="formulario">
				<select name="cmbContrato_Pr"	id="cmbContrato_Pr"style="width: 140px;" >
					<option value=4>MENSUAL</option>
					<option value=0 >SEMANAL</option>
					<option value=1 >CATORCENAL</option>
					<option value=2 >QUINCENAL 15 - 30</option>
					<option value=3 >QUINCENAL 10 - 25</option>
					<option value=5 >TRISMETRAL</option>
					<option value=6 >SEMESTRAL</option>
					<option value=7 >-- ANUAL --</option>
					<option value=8 >MENSUAL-10</option>
					<option value=9 >MENSUAL-25</option>
				</select>
				</td>
				<td class="formulario">
				<button onclick="Respaldo_Modificar_Periodicidad();" >Modificar</button>
				</td>
			</tr>
		</table> </div> </td>
	</tr>
</table>
<br>
<br>
<table style="background-color: #C8E6C9">
	<tr>
		<td><h2> MODIFICAR FORMA DEL CONTRATO <br></h2>Recuerde que debe estar seguro de cambiar la periodicida ya que influye en los reportes y cobros.<br><br>
		<table style="width:500px">
			<tr>
				<td class="formulario">&nbsp;N&uacute;mero de Contrato (*)</td>
				<td class="formulario">Forma De Contrato</td>
				<td class="formulario"><label id="etiquetaf" name="etiquetaf">&nbsp;</label></td>
				<td></td>
			</tr>
			<tr>
				<td class="formulario">
				<input type="text" class="inputxt" style="width:200px" name="txtContrato_Fr" id="txtContrato_Fr" >
				</td>
				<td class="formulario">
				<select name="cmbContrato_Fr"	id="cmbContrato_Fr"style="width: 140px;" >
					<option value=0>UNICO</option>
					<option value=1>AGUINALDOS - CUOTA ESPECIAL</option>
					<option value=2>VACACIONES - CUOTA ESPECIAL</option>
					
					<option value=3>CUOTA EXTRAORDINARIA</option>
					
					<option value=4 >UNICO - EXTRA</option>
					<option value=5 >ESPECIAL - EXTRA</option>
				</select>
				</td>
				<td class="formulario">
				<button onclick="Respaldo_Modificar_Forma_C();" >Modificar</button>
				</td>
			</tr>
		</table> </div> </td>
	</tr>
</table>
<br>
<br>
<table style="background-color: #B6B6B6">
    <tr>
        <td><h2> MODIFICAR EMPRESA DEL CONTRATO <br></h2>Recuerde que debe estar seguro de cambiar la Empresa ya que influye en los reportes y cobros.<br><br>
            <table style="width:500px">
                <tr>
                    <td class="formulario">&nbsp;N&uacute;mero de Contrato (*)</td>
                    <td class="formulario">Empresa De Contrato</td>
                    <td class="formulario"><label id="etiquetaf" name="etiquetaf">&nbsp;</label></td>
                    <td></td>
                </tr>
                <tr>
                    <td class="formulario">
                        <input type="text" class="inputxt" style="width:200px" name="txtContrato_Empresa" id="txtContrato_Empresa" >
                    </td>
                    <td class="formulario">
                        <select name="cmbContrato_Empresa"	id="cmbContrato_Empresa"style="width: 140px;" >
                            <option value=0>Cooperativa</option>
                            <option value=1>Grupo</option>
                        </select>
                    </td>
                    <td class="formulario">
                        <button onclick="Respaldo_Modificar_Empresa();" >Modificar</button>
                    </td>
                </tr>
            </table> </div> </td>
    </tr>
</table>

