<html>
<head>
	<?php $this->load->view("incluir/cabezera.php"); ?>
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>view/panelDolar.js?v=<?php echo time(); ?>"></script>
	<style>
		.box-dolar { border:1px solid #ccc; border-radius:8px; padding:16px; margin:12px 0; background:#fafafa; max-width:900px; }
		.box-dolar.ok { border-color:#2e7d32; background:#f1f8e9; }
		.box-dolar.warn { border-color:#ef6c00; background:#fff3e0; }
		.tabla-dolar { width:100%; max-width:900px; border-collapse:collapse; margin-top:8px; }
		.tabla-dolar th, .tabla-dolar td { border:1px solid #ddd; padding:6px 8px; font-size:13px; text-align:left; }
		.tabla-dolar th { background:#eee; }
		.tabla-dolar tr.fila-activa { background:#e8f5e9; }
		.tabla-dolar td.num { text-align:right; }
		.tabs-dolar { margin-top:20px; max-width:900px; }
		.tabs-dolar button { margin-right:6px; padding:6px 12px; cursor:pointer; }
		.tabs-dolar button.activo { background:#1976d2; color:#fff; border:1px solid #1565c0; }
	</style>
</head>
<body>
<div id="cabecera"><br>
	<?php $this->load->view("menu/buscar.php"); ?>
	<?php echo $Menu; ?>
</div>
<div id="medio">
	<div id="msj_alertas"></div>
	<h2 class="demoHeaders">Precio del Dólar</h2>
	<h3>Usuario: <?php echo $this->session->userdata('usuario'); ?> — <?php echo date('Y/m/d H:i'); ?></h3>

	<div class="box-dolar" style="max-width:900px;">
		<table class="formulario">
			<tr>
				<td style="width:160px;">Banco:</td>
				<td>
					<select id="cmbBancoDolar" class="inputxt" style="width:200px;" onchange="cambiarBancoDolar();">
						<option value="">General</option>
						<option value="VENEZUELA">Venezuela</option>
						<option value="BICENTENARIO">Bicentenario</option>
						<option value="BANFAN">Banfan</option>
					</select>
					<span style="font-size:11px;color:#555;margin-left:8px;">
						Cada banco negocia su propia tasa Cobranza. "General" es la tasa de respaldo:
						se usa si un banco no tiene registrada la suya propia ese día.
					</span>
				</td>
			</tr>
		</table>
	</div>

	<div id="estado_dolar_bcv" class="box-dolar warn">
		<p>Cargando estado BCV...</p>
	</div>
	<div id="estado_dolar_cobranza" class="box-dolar warn">
		<p>Cargando estado Cobranza...</p>
	</div>

	<div class="box-dolar" style="max-width:900px;">
		<table class="formulario">
			<tr>
				<td style="width:160px;">Dólar BCV (Bs.) (*)</td>
				<td>
					<input type="text" id="txtPrecioDolarBcv" class="inputxt" style="width:180px;"
						placeholder="Ej: 36.50" onkeypress="return soloDecimal(event);" />
				</td>
			</tr>
			<tr>
				<td>Dólar Cobranza (Bs.) (*)</td>
				<td>
					<input type="text" id="txtPrecioDolarCobranza" class="inputxt" style="width:180px;"
						placeholder="Ej: 36.50" onkeypress="return soloDecimal(event);" />
				</td>
			</tr>
			<tr>
				<td>Fecha a registrar</td>
				<td>
					<input type="text" id="txtFechaDolar" class="inputxt fecha-dolar" style="width:120px;" readonly="readonly" />
					<small id="lbl_fecha_hoy" style="color:#666;">(hoy: <?php echo date('Y-m-d'); ?>)</small>
				</td>
			</tr>
		</table>
		<p style="font-size:12px;color:#666;">(*) Ambas tasas son obligatorias — no se guarda ninguna si falta la otra.</p>
		<p style="font-size:12px;color:#856404;background:#fff8e1;border:1px solid #ffc107;border-radius:4px;padding:6px 8px;max-width:700px;">
			Si eliges una fecha <strong>distinta a hoy</strong>, el precio se guarda como <strong>histórico</strong>
			(queda en la bitácora, pero no cambia el precio activo vigente). Si ya existe un registro para esa
			fecha, se <strong>actualiza</strong> en vez de duplicarse.
		</p>
		<br>
		<button type="button" id="btnGuardarDolar" onclick="guardarPrecioDolar();">Guardar precio</button>
	</div>

	<div class="tabs-dolar">
		<button type="button" id="tab_resumen" class="activo" onclick="mostrarTabHistorial('resumen');">Precio por día</button>
		<button type="button" id="tab_bitacora" onclick="mostrarTabHistorial('bitacora');">Bitácora completa</button>
	</div>

	<h3 id="titulo_historial" style="margin-top:16px;">Precio activo por día</h3>
	<div id="contenedor_historial"></div>

	<p style="font-size:12px;color:#666;max-width:900px;margin-top:12px;">
		Cada vez que guarda un precio se agrega un registro en la <strong>bitácora</strong> (no se sobrescribe).
		A las <strong>7:00 a.m.</strong> (Venezuela) el precio del día anterior se inactiva y debe registrarse el del día actual.
	</p>
</div>
</body>
</html>
