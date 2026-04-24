<script type="text/javascript">
function mostrar(id) {
    if (id == "cheque") {
        $("#cheque").show();
        $("#deposito").hide();
        $("#transferencia").hide();
        $("#debito").hide();
    }

    if (id == "deposito") {
        $("#cheque").hide();
        $("#deposito").show();
        $("#transferencia").hide();
        $("#debito").hide();
    }

    if (id == "transferencia") {
        $("#cheque").hide();
        $("#deposito").hide();
        $("#transferencia").show();
        $("#debito").hide();
    }

    if (id == "debito") {
        $("#cheque").hide();
        $("#deposito").hide();
        $("#transferencia").hide();
        $("#debito").show();
    }
}
</script>
	<table style="width:620px" border="0" cellspacing="3" cellpadding="0" class="formulario">
<!-- 		<tr> 
			<td><strong>N&uacute;m. Recibo <font color='#B70000'>(*)</font> : </strong></td>
			<td align="left" colspan=5>
				<input  name="txtNumero_Recibo" id="txtNumero_Recibo"  onblur="consultar_recibo();"style="width: 180px;" >
			</td>
		</tr>-->
		<tr>
			<td style="width: 120px;" >C&eacute;dula(*):</td>
			<td style="width: 180px;" align="left" >
			<input name="txtCedula" type="text" style="width: 180px;" id="txtCedula" maxlength="11" onblur="consultar_clientes();">
			</td>
			<td align="right"><strong>Monto<font color='#B70000'>(*)</font>: </strong></td>
			<td colspan="3" ><input name="txtmontorecibo" type="text" style="width: 100%;" id="txtmontorecibo" onkeypress="Solo_Numero();"></td>
		
		</tr>
		<tr> 	
			<td style='width:120px;'>Recibido De: <font color='#B70000'>(*)</td>
			<td style="width: 120px;" align="left" colspan="5">
				<input name="txtRecibidoDe" type="text" style="width: 100%;" id="txtRecibidoDe" disabled="true">
			</td>		
		</tr>	
		<tr>
			<td style="width:120px" >Fecha del Recibo <font color='#B70000'>(*)</font>:</td>
			<td align="left"   >
				<input type="text" id='txtFechare' name="txtFechare" disabled="true" style="width: 160px;" />
			</td> 
			<td style="width:120px" >Factura a pagar: <font color='#B70000'>(*)</font>:</td>
			<td align="left"   >
				<input type="text" id='txtFactp' name="txtFactp" disabled="true" style="width: 190px;" />
			</td> 
		</tr>
		<tr>
			<td style='width:120px;' valign="top">Observaciones:</td>
			<td style="width: 400px;" align="left" colspan="5">
				<textarea   rows="5" style="width: 100%; height: 60px" name="txtObservaciones"	id="txtObservaciones"></textarea>
			</td>
		</tr>	
		<tr> 	
			<td style='width:120px;'>Factura: <font color='#B70000'>(*)</td>
			<td style="width: 180px;" align="left" colspan=""><select name="txtNfactura"	id="txtNfactura"style="width: 180px;"></td>
		<td style='width:120px;'>Forma de pago: <font color='#B70000'>(*)</td>
			<td style="width: 180px;" align="left" colspan="">
				<select id="status" name="status" onChange="mostrar(this.value);">
        <option value="---">--------</option>
        <option value="cheque">Cheque</option>
        <option value="deposito">Deposito</option>
        <option value="transferencia">Transferencia</option>
        <option value="debito">Debito</option>
     </select>
			</td>
		</tr>
	</table>
	<div id="cheque" style="display: none;">
   	<h2>Forma de pago: Cheque</h2>
        <table style="width:620px" border="0" cellspacing="3" cellpadding="0" class="formulario">
        	<tr>
			<td style="width: 120px;" >N. de Cheque (*):</td>
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
				<?php if($nivel ==0 ){ ?>
					
					<option>SOFITASA</option>
					<option>CARONI</option>
					<option>CARIBE</option>
						
				<?php } ?> 
			</select>
			</td>
		
		</tr>
        </table><br>
</div>
<div id="deposito" style="display: none;">
        <h2>Forma de pago: Deposito</h2><br>
        <table  style="width:620px" border="0" cellspacing="3" cellpadding="0" class="formulario">
        	<tr>
			<td style="width: 120px;" >N. de Deposito (*):</td>
			<td style="width: 180px;" align="left" >
			<input name="txtNdep" type="text" style="width: 180px;" id="txtNdep">
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
				<?php if($nivel ==0 ){ ?>
					
					<option>SOFITASA</option>
					<option>CARONI</option>
					<option>CARIBE</option>
						
				<?php } ?> 
			</select>
			</td>
		
		</tr>
        </table><br>
</div>
<div id="transferencia" style="display: none;">
        <h2>Forma de pago: Transferencia</h2><br>
        <table style="width:620px" border="0" cellspacing="3" cellpadding="0" class="formulario">
        	<tr>
			<td style="width: 120px;" >N. de trasacc&iacute;n (*):</td>
			<td style="width: 180px;" align="left" >
			<input name="txtNtran" type="text" style="width: 180px;" id="txtNtran">
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
				<?php if($nivel ==0 ){ ?>
					
					<option>SOFITASA</option>
					<option>CARONI</option>
					<option>CARIBE</option>
						
				<?php } ?> 
			</select>
			</td>
		
		</tr>
        </table><br>
</div>
<div id="debito" style="display: none;">
        <h2>Forma de pago: Debito</h2><br>
        <table style="width:620px" border="0" cellspacing="3" cellpadding="0" class="formulario">        	<tr>
			<td style="width: 120px;" >N. Tarjeta (*):</td>
			<td style="width: 180px;" align="left" >
			<input name="txtNtar" type="text" style="width: 180px;" id="txtNtar">
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
				<?php if($nivel ==0 ){ ?>
					
					<option>SOFITASA</option>
					<option>CARONI</option>
					<option>CARIBE</option>
						
				<?php } ?> 
			</select>
			</td>
		
		</tr>
        </table><br>
</div>
	<div id="botones">
		<button onclick="GuardaReciboCP();">Guardar </button>
	</div>
	<div id="Recibos" style="width:100%; Height:300px"></div>
	