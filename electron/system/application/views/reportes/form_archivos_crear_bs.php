<table>
	<tr>
		<td style='width:120px;'>Empresa: <font color='#B70000'>(*)</font></td>
		<td align="left" colspan="5">
			<select name="txtBsEmpresa" id="txtBsEmpresa">
				<option value="0" disabled>COOPERATIVA ELECTRON 465 RL. (inactivo)</option>
				<option value="1" selected>GRUPO ELECTRON 465 C.A</option>
				<option value="2" disabled>AMBAS (inactivo)</option>
			</select>
		</td>
	</tr>
	<tr>
		<td>Linaje: <font color='#B70000'>(*)</font></td>
		<td>
			<select name="txtBsCobrado" id="txtBsCobrado" style="width: 400px;" onchange="muestra_nomina_bs(); togglePanelVenezuelaBs(); toggleBancoNominaBs();"></select>
		</td>
	</tr>
	<tr id="filaBancoNominaBs" style="display:none;">
		<td>Banco (N&oacute;mina): <font color='#B70000'>(*)</font></td>
		<td align="left" class="formulario">
			<select name="txtBsBancoNomina" id="txtBsBancoNomina" style="width: 200px;">
				<option value="VENEZUELA">VENEZUELA</option>
				<option value="BICENTENARIO">BICENTENARIO</option>
				<option value="BANFAN">BANFAN</option>
			</select>
			<br><small style="color:#666;">Cotero agrupa clientes de los 3 bancos — elija a cu&aacute;l le va a enviar este archivo.</small>
		</td>
	</tr>
	<tr>
		<td>N&oacute;mina: <font color='#B70000'>(*)</font></td>
		<td>
			<select name="txtBsNomina" id="txtBsNomina" style="width: 400px;"></select>
		</td>
	</tr>
	<tr>
		<td>Forma Contrato:</td>
		<td align="left" class="formulario">
			<select name="txtBsFormaContrato" id="txtBsFormaContrato" style="width: 200px;">
				<option value="0">UNICO</option>
				<option value="7">COSECHAEXPRESS</option>
				<option value="1">AGUINALDOS - CUOTA ESPECIAL</option>
				<option value="2">VACACIONES - CUOTA ESPECIAL</option>
				<option value="3">EXTRA</option>
				<option value="4">UNICO - EXTRA</option>
				<option value="5">ESPECIAL - EXTRA</option>
				<option value="6">UNICO - PRONTO PAGO</option>
			</select>
		</td>
	</tr>
	<tr>
		<td>Periodicidad:</td>
		<td align="left" class="formulario">
			<select name="txtBsPeriodicidad" id="txtBsPeriodicidad" style="width: 200px;">
				<option value="4">MENSUAL</option>
				<option value="5">MENSUAL (día 15)</option>
				<option value="9">MENSUAL (día 25)</option>
				<option value="6">MENSUAL (día 30)</option>
				<option value="0">SEMANAL</option>
				<option value="1">CATORCENAL</option>
				<option value="2">QUINCENAL 15 - 30</option>
				<option value="3">QUINCENAL 10 - 25</option>
				<option value="7">ANUAL</option>
				<option value="8">MENSUAL-10</option>
			</select>
			<br><small id="notaPeriodicidadMorosos" style="display:none;color:#856404;">
				<b>Morosos</b> siempre cobra juntas: Mensual, Mensual (día 25), Mensual-15 y Mensual-30 — esta selección no aplica.
			</small>
		</td>
	</tr>
	<tr>
		<td style="width:150px">Hasta La Fecha <font color='#B70000'>(*)</font> :</td>
		<td align="left" style="width:210px" colspan=3>
			<select name="txtBsDia" id="txtBsDia" style="width: 140px;">
				<option value='00'>SEMANAL</option>
				<option value='01'>MENSUAL</option>
				<option value='10'>QUINCENAL 10</option>
				<option value='15'>QUINCENAL 15</option>
				<option value='25'>QUINCENAL 25</option>
				<option value='30'>QUINCENAL 30</option>
			</select>
			<select name="txtBsMes" id="txtBsMes" style="width: 55px;">
				<option value="0">Mes:</option>
				<?php
				$mesActual = date('n');
				for ($i = 1; $i <= 9; $i++) {
					$val = '0' . $i;
					$sel = ($i == $mesActual) ? ' selected' : '';
					echo "<option value='{$val}'{$sel}>{$i}</option>";
				}
				for ($i = 10; $i <= 12; $i++) {
					$sel = ($i == $mesActual) ? ' selected' : '';
					echo "<option value='{$i}'{$sel}>{$i}</option>";
				}
				?>
			</select>
			<select name="txtBsAno" id="txtBsAno" style="width: 60px;">
				<option value="0">Año:</option>
				<?php
				$anoActual = (int) date('Y');
				for ($i = 2014; $i <= $anoActual + 1; $i++) {
					$sel = ($i == $anoActual) ? ' selected' : '';
					echo "<option value='{$i}'{$sel}>{$i}</option>";
				}
				?>
			</select>
			<br><small style="color:#666;">Se cobran todas las cuotas vencidas hasta esta fecha (no solo la última).</small>
		</td>
	</tr>
	<tr>
		<td>Tipo de Cliente: <font color='#B70000'>(*)</font></td>
		<td align="left" class="formulario">
			<select name="txtBsFiltroCliente" id="txtBsFiltroCliente" style="width: 200px;" onchange="toggleBloqueoPeriodicidadBs();">
				<option value="ACTIVOS">Activos (solo cuota del mes)</option>
				<option value="MOROSOS">Morosos (deuda de meses anteriores)</option>
			</select>
			<br><small style="color:#666;">
				<b>Activos:</b> cobra solo la cuota del mes elegido arriba, sin importar atrasos.<br>
				<b>Morosos:</b> cobra solo lo vencido de meses <u>anteriores</u> al elegido (no incluye la cuota de ese mes).
			</small>
		</td>
	</tr>
	<tr>
		<td>Método de Picar:</td>
		<td align="left" class="formulario">
			<select name="txtBsPicar" id="txtBsPicar" style="width: 140px;">
				<option value="0">Sin picar (monto completo)</option>
				<option value="50">50 Bs.</option>
				<option value="100" selected>100 Bs.</option>
				<option value="150">150 Bs.</option>
				<option value="200">200 Bs.</option>
				<option value="250">250 Bs.</option>
				<option value="300">300 Bs.</option>
				<option value="500">500 Bs.</option>
				<option value="1000">1000 Bs.</option>
				<option value="1500">1500 Bs.</option>
				<option value="2000">2000 Bs.</option>
				<option value="2500">2500 Bs.</option>
				<option value="3000">3000 Bs.</option>
				<option value="5000">5000 Bs.</option>
				<option value="10000">10000 Bs.</option>
				<option value="15000">15000 Bs.</option>
				<option value="20000">20000 Bs.</option>
			</select>
			<br><small style="color:#666;">Fracciona cada cobro en varias líneas de este monto — aumenta la probabilidad de que el banco alcance a debitar algo si el cliente no tiene el saldo completo.</small>
		</td>
	</tr>
</table>

<div id="panelVenezuelaBs" style="display:none;margin-top:12px;padding:12px;background:#f3f9ff;border:1px solid #64b5f6;border-radius:6px;">
	<p style="margin:0 0 10px;font-size:13px;color:#1565c0;">
		<strong>Banco de Venezuela</strong> &mdash; datos de cabecera para la plantilla macro (hoja <em>Cobranza</em>).
		El detalle se genera desde la <strong>fila 23</strong>.
	</p>
	<table>
		<tr>
			<td style="width:140px;">RIF Empresa:</td>
			<td>
				<select id="txtBsRifLetra" style="width:50px;">
					<option value="J">J</option>
					<option value="G">G</option>
					<option value="V">V</option>
				</select>
				<input type="text" id="txtBsRifNumero" style="width:120px;" maxlength="9" placeholder="Sin guiones"/>
			</td>
		</tr>
		<tr>
			<td>N&uacute;mero de Lote:</td>
			<td><input type="text" id="txtBsLote" style="width:120px;" maxlength="10"/></td>
		</tr>
		<tr>
			<td>Ref. Cr&eacute;dito Cuentas:</td>
			<td><input type="text" id="txtBsRefCuentas" style="width:200px;" maxlength="20"/></td>
		</tr>
		<tr>
			<td>Ref. Cr&eacute;dito TDC BDV:</td>
			<td><input type="text" id="txtBsRefTdcBdv" style="width:200px;" maxlength="20"/></td>
		</tr>
		<tr>
			<td>Ref. Cr&eacute;dito TDC Otros:</td>
			<td><input type="text" id="txtBsRefTdcOtros" style="width:200px;" maxlength="20"/></td>
		</tr>
		<tr>
			<td>Referencia del D&eacute;bito:</td>
			<td><input type="text" id="txtBsReferenciaDebito" style="width:120px;" maxlength="20" placeholder="465"/>
			<br><small style="color:#666;">C&oacute;digo del cliente recaudador ante BDV — igual en todas las l&iacute;neas del archivo. Vac&iacute;o = usa el valor por defecto (465).</small>
			</td>
		</tr>
		<tr>
			<td>Fecha Env&iacute;o:</td>
			<td><input type="text" id="txtBsFechaEnvio" class="fecha-bdv" style="width:100px;"/></td>
		</tr>
		<tr>
			<td>Fecha Valor Lote:</td>
			<td><input type="text" id="txtBsFechaValor" class="fecha-bdv" style="width:100px;"/></td>
		</tr>
		<tr>
			<td>Fecha Culminaci&oacute;n:</td>
			<td><input type="text" id="txtBsFechaCulminacion" class="fecha-bdv" style="width:100px;"/></td>
		</tr>
	</table>
</div>

<div id="txtBsPrecioInfo" style="margin-top:14px;padding:10px 12px;background:#e8f4fd;border:1px solid #90caf9;border-radius:6px;font-size:13px;">
	Cargando precio del d&oacute;lar vigente...
</div>

<p style="font-size:12px;color:#666;margin-top:10px;">
	Los contratos est&aacute;n en d&oacute;lares. Se suman <strong>todas las cuotas vencidas</strong> hasta la fecha indicada
	(no solo la &uacute;ltima) y se convierten a bol&iacute;vares con la tasa <strong>D&oacute;lar Cobranza</strong> del Panel de Control
	(<strong>adeudado USD &times; D&oacute;lar Cobranza</strong>). Solo incluye contratos activos con saldo pendiente y cuenta de 20 d&iacute;gitos.
</p>
<p id="txtBsAyudaOtros" style="font-size:12px;color:#856404;margin-top:8px;padding:8px;background:#fff8e1;border:1px solid #ffc107;border-radius:4px;">
	<strong>Otros bancos:</strong> Banfan, Bicentenario, Provincial, Fondo Com&uacute;n, Mercantil, Banesco, Bod, Industrial, Sofitasa, Del Sur, Caron&iacute;
	(formato 51+54 caracteres).
</p>
<p id="txtBsAyudaVenezuela" style="display:none;font-size:12px;color:#1565c0;margin-top:8px;padding:8px;background:#e3f2fd;border:1px solid #64b5f6;border-radius:4px;">
	<strong>Banco de Venezuela:</strong> al <em>Generar TXT Bs.</em> se entregan ambos archivos: el TXT para el banco y la plantilla XLS (copiar/pegar en su macro desde fila 23).
</p>
