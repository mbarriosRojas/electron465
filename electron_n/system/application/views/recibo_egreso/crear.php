
	<table style="width:620px" border="0" cellspacing="3" cellpadding="0" class="formulario">
<!-- 		<tr> 
			<td><strong>N&uacute;m. Recibo <font color='#B70000'>(*)</font> : </strong></td>
			<td align="left" colspan=5>
				<input  name="txtNumero_Recibo" id="txtNumero_Recibo"  onblur="consultar_recibo();"style="width: 180px;" >
			</td>
		</tr>-->
		<tr>
			<td style="width: 120px;" >C&eacute;dula (*):</td>
			<td style="width: 180px;" align="left" >
			<input name="txtCedula" type="text" style="width: 180px;" id="txtCedula" maxlength="11" onblur="consultar_clientes();">
			</td>
			<td align="right"><strong>Monto<font color='#B70000'>(*)</font>: </strong></td>
			<td colspan="3" ><input name="txtmontorecibo" type="text" style="width: 100%;" id="txtmontorecibo" onkeypress="Solo_Numero();"></td>
		
		</tr>
		<tr> 	
			<td style='width:120px;'>Pagado a: <font color='#B70000'>(*)</td>
			<td style="width: 120px;" align="left" colspan="5">
				<input name="txtRecibidoDe" type="text" style="width: 100%;" id="txtRecibidoDe" disabled="true">
			</td>		
		</tr>	
		<tr>
			<td style="width:120px" >Fecha del Recibo <font color='#B70000'>(*)</font>:</td>
			<td align="left"   >
				<input type="text" id='fecha' name="fecha" disabled="true" style="width: 160px;" />
			</td> 
			<td align="right">T.Pago:</td>
			<td>
				<select name="txtTipoPago" id="txtTipoPago" style="width: 100%;">
					<option value='E'>Efectivo</option>
					<option value='C'>Cheque</option>
					<option value='D'>Debito</option>
					<option value='T'>Transferencia</option>
					<option value='P'>Deposito</option>
				</select>
			</td>
		</tr>
		<tr>
			<td style="width: 120px;" >N. de Operacion (*):</td>
			<td style="width: 180px;" align="left" >
			<input name="txtCheque" type="text" style="width: 180px;" id="txtCheque">
			</td>
			<td align="right"><strong>Banco<font color='#B70000'>(*)</font>: </strong></td>
			<td colspan="3" >
				<select name="txtBanco"	id="txtBanco" style="width: 100%;">
				<option>----------</option>
				<option>NOMINA</option>
				
				<option>BICENTENARIO</option>
				<option>BOD</option>
				<option>PROVINCIAL</option>
				<option>VENEZUELA</option>
				<option>BANESCO</option> 
				<option>INDUSTRIAL</option>
				<option>MERCANTIL</option>
				<option>CAMARA MERCANTIL</option>
				<option>DEL SUR</option>			
				<option>CREDINFO</option>
				<option>INVERCRESA</option>
				<option>FONDO COMUN</option>
				<option>100% BANCO COMERCIAL</option>
				<option>DOMICILIACION POR OFICINA</option>
				<option>CARONI</option>
				<?php if($nivel ==0 ){ ?>
					
					<option>SOFITASA</option>
					<option>CARONI</option>
					<option>CARIBE</option>
						
				<?php } ?> 
			</select>
			</td>
		
		</tr>
		<tr>
			<td style='width:120px;' valign="top">Banco Movimiento:</td>
			<td style="width: 400px;" align="left" colspan="5">
				<select name="txtBancoM"	id="txtBancoM" style="width: 100%;">
				<option>----------</option>
				<option>NOMINA</option>
				
				<option>BICENTENARIO</option>
				<option>BOD</option>
				<option>PROVINCIAL</option>
				<option>VENEZUELA</option>
				<option>BANESCO</option> 
				<option>INDUSTRIAL</option>
				<option>MERCANTIL</option>
				<option>CAMARA MERCANTIL</option>
				<option>DEL SUR</option>			
				<option>CREDINFO</option>
				<option>INVERCRESA</option>
				<option>FONDO COMUN</option>
				<option>100% BANCO COMERCIAL</option>
				<option>CARONI</option>
				<option>DOMICILIACION POR OFICINA</option><option>SOFITASA</option>
				<?php if($Nivel ==0 ){ ?>
					
					
					<option>CARONI</option>
					<option>CARIBE</option>
						
				<?php } ?> 
			</select>
			</td>
		</tr>
		<tr>
			<td style='width:120px;' valign="top">Concepto:</td>
			<td style="width: 400px;" align="left" colspan="5">
				<textarea   rows="5" style="width: 100%; height: 60px" name="txtConcepto"	id="txtConcepto"></textarea>
			</td>
		</tr>
		<tr> 	
			<td style='width:120px;'>Formato de Imp.: <font color='#B70000'>(*)</td>
			<td style="width: 180px;" align="left" colspan="5">
				<select name="txtEmpresa"	id="txtEmpresa"style="width: 100%;">
					<OPTION VALUE =0 >COOPERATIVA ELECTRON 465 RL.</option>
					<OPTION VALUE =1 >GRUPO ELECTRON 465 C.A</option>					
				</select>
			</td>
					
		</tr>
		<?php 
		if($Nivel !=0 && $Nivel !=3 && $Nivel !=2 && $Nivel !=5 && $Nivel !=9 && $Nivel !=8 && $Nivel !=10) $propiedad = "disabled=disabled";
		else $propiedad = "";
		?>
		<tr>
			<td>Reintegro</td>
			<td><input type="checkbox" name="reintegro" id="reintegro" value="" onclick="Mostrar()" <?php echo $propiedad; ?>></td>
		</tr>		
	</table>
	
	<!-- muestra si es por reintegro  -->
		<table id="datosR" style="display:none"> 
		<tr> 
			<td style='width:120px;'>Factura: <font color='#B70000'>(*)</td>
			<td style="width: 180px;" align="left" colspan=""><select name="txtFactura"	id="txtFactura"style="width: 180px;"></td>
			<td align="right"><strong>Monto C.: <font color='#B70000'>(*)</font>: </strong></td>
			<td colspan="" ><input type="text" name="txtMontoCarga"	id="txtMontoCarga"style="width: 90px;"></td>
			<td><button onclick="Agrega_Contrato();">AGREGAR</button></td>		
		</tr>
		<tr> 	
			<td style='width:120px;'>Agregados: <font color='#B70000'>(*)</td>
			<td style="width: 180px;" align="left" colspan="5">
				<select name="lstAgregados"	id="lstAgregados"style="width: 100%;height: 100px;" multiple="multiple" ondblclick="quitar();">
										
				</select>
			</td>
					
		</tr>
		</table>
	<!-- fin muestra si es por reintegro  -->
	<div id="botones">
		<button onclick="GuardaReciboE();">Guardar </button>
	</div>
	<div id="Recibos" style="width:100%; Height:300px"></div>




