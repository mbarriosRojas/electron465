 	
	<?php $this -> load -> view("incluir/cabezera.php"); ?>
	<link href="<?php echo __CSS__ ?>tgrid/TGrid.css" rel="stylesheet" type="text/css" media="screen"/>
	<link href="<?php echo __CSS__ ?>tgrid/TGridPrint.css" rel="stylesheet" type="text/css" media="print"/>
	<link href="<?php echo __CSS__ ?>lightbox/lightbox.css" rel="stylesheet"  type="text/css">
	
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>gvistas/gvista2.js"></script>
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>view/reportes.js"></script>
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>tgrid/func.js"></script>
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>tgrid/editar.js"></script>
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>tgrid/tgrid.js"></script>
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>tgrid/paginador.js"></script>
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>tgrid/xls.js"></script>
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>lightbox/lightbox.js"></script>
	<script  type="text/javascript" src="<?php echo __JSVIEW__ ?>graf/jquery.jqplot.min.js"></script>
	<script  type="text/javascript" src="<?php echo __JSVIEW__ ?>graf/plugins/jqplot.pieRenderer.min.js"></script>
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>reportes_especiales.js"></script>
	<link rel="stylesheet" type="text/css" href="<?php echo __CSS__ ?>jquery.jqplot.min.css" />
  </head>
<body>
	<div id="cabecera"><br>
		<?php $this -> load -> view("menu/buscar.php"); ?>
		<?php echo $Menu; ?>
	</div>
		
  <div id="medio"  style="width:80%"> 
		<div id="msj_alertas"></div>
	  <h2>Reportes</h2><br>
	  <h3>Usuario Conectado: <?php echo $this -> session -> userdata('usuario'); ?> Fecha: <?php echo date("Y/m/d"); ?></h3><br>	
	  
	  	<br>
			<div id="mnu_Reportes" title="">
				<table >
  				<tr>
  					<td align="center"><img src="/system/img/reportes/factura.png" style='width:48px' onClick="muestra_div('facturas_pendientes');$('#Bancos').hide();$('#Direccion').hide();"/><br>Estatus de Facturas</td>
  					<td style="width: 50px"></td>
  					
  					
  					<?php if($Nivel == 3 || $Nivel == 0 || $Nivel == 9 || $Nivel == 2){?>
  						<td align="center"><img src="/system/img/reportes/cuadre.png" style='width:48px' onClick="muestra_div('muestra_cuadre');$('#Bancos').hide();$('#Direccion').hide();"/><br>Cuadre de Caja</td>
  						<td style="width: 50px"></td>
  					<?php } ?>
  					<td align="center"><img src="/system/img/reportes/bcontrato.png" style='width:48px' onClick="muestra_div('busca_contrato');$('#Bancos').hide();$('#Direccion').hide();"/><br>Buscar Contratos</td>
  					<td style="width: 50px"></td>
  					<td align="center"><img src="/system/img/reportes/ncuenta.png" style='width:48px' onClick="$('#Bancos').show();$('#Reportes').html('');$('#Direccion').hide();"/><br>N&uacute;mero de Cuentas</td>
  					<td style="width: 50px"></td>
  					<td align="center"><img src="/system/img/reportes/lista.png" style='width:48px' onClick="$('#Direccion').show();$('#Bancos').hide();$('#Reportes').html('');"/><br>Lista Direcciones</td>
  				</tr>
  				<tr style='height: 20px'></tr>
  				<tr> 
  					<?php if($Nivel == 0 || $Nivel == 9 || $Nivel == 3 ){?>
  						<td align="center"><img src="/system/img/reportes/cheque.png" style='width:48px' onClick="muestra_div('muestra_cheque');$('#Bancos').hide();$('#Direccion').hide();"/><br>Cheques</td>
  						<td style="width: 50px" ></td>
  					<?php } ?>
  					<?php if($Nivel == 0 || $Nivel == 9 || $Nivel == 11 ){?>
  						<td align="center"><img src="/system/img/reportes/voucher.jpg" style='width:48px' onClick="muestra_div('voucher');$('#Bancos').hide();$('#Direccion').hide();"/><br>Voucher y Transferencias</td>
  						<td style="width: 50px" ></td>
  					<?php } ?>
  					<?php if($Nivel == 0 || $Nivel == 3  || $Nivel == 5  || $Nivel == 9 ){?>
  						<td align="center"><img src="/system/img/reportes/cusuario.png" style='width:48px' onClick="muestra_div('usuarios_contratos');$('#Bancos').hide();$('#Direccion').hide();"/><br>Contratos Por Usuario</td>
  						<td style="width: 50px" ></td>
  					<?php } ?>
  					<?php if($Nivel == 0 || $Nivel == 13  || $Nivel == 3 || $Nivel == 8 || $Nivel == 9 ){?>
  						<td align="center"><img src="/system/img/reportes/dpendiente.png" style='width:48px' onClick="muestra_div('depositos_pendientes');$('#Bancos').hide();$('#Direccion').hide();"/><br>Depositos Pendientes</td>
  						<td style="width: 50px" ></td>
  					<?php } ?>
  					<?php if($Nivel == 0 || $Nivel == 8 || $Nivel == 9 || $Nivel == 5 || $Nivel == 3 || $Nivel == 18|| $this -> session -> userdata('usuario') == 'Carlos' ){?>
  						<td align="center"><img src="/system/img/reportes/inventario.png" style='width:48px' onClick="muestra_div('rep_inven');$('#Bancos').hide();$('#Direccion').hide();"/><br>Inventario</td>
  						<td style="width: 50px" ></td>
  						<td align="center"><img src="/system/img/reportes/cpago.png" style='width:48px' onClick="muestra_div('rep_sinpago');$('#Bancos').hide();$('#Direccion').hide();"/><br>Control De Pagos</td>
  						<td style="width: 50px" ></td>
  					<?php } ?>
  				</tr>
  				<tr style='height: 20px'></tr>
  				<tr>
  					<?php if($Nivel == 0 ||  $Nivel == 8 || $Nivel == 9 || $Nivel == 5 || $this -> session -> userdata('usuario') == 'Carlos'){?>
  						<td align="center"><img src="/system/img/reportes/vcontado.png" style='width:48px' onClick="muestra_div('rep_contado');$('#Bancos').hide();$('#Direccion').hide();"/><br>Ventas De Contado</td>
  						<td style="width: 50px" ></td>
  					<?php } ?>
  					<?php if($Nivel == 0 || $Nivel == 8 || $Nivel == 9 || $Nivel == 5 || $this -> session -> userdata('usuario') == 'Carlos'  || $Nivel == 18  || $this -> session -> userdata('usuario') == 'mvanalista11'){?>
  						<td align="center"><img src="/system/img/reportes/fcontado.png" style='width:48px' onClick="muestra_div('rep_fpresu');$('#Bancos').hide();$('#Direccion').hide();"/><br>Facturas Presupuesto</td>
  						<td style="width: 50px" ></td>
  					<?php } ?>
					<?php if($Nivel == 0 || $Nivel == 8 || $Nivel == 9 || $Nivel == 5 || $this -> session -> userdata('usuario') == 'Carlos'  || $Nivel == 18 ){?>
						<td align="center"><img src="/system/img/reportes/fcontado.png" style='width:48px' onClick="muestra_div('rep_fcontrol');$('#Bancos').hide();$('#Direccion').hide();"/><br>Facturas Formato Control</td>
						<td style="width: 50px" ></td>
					<?php } ?>
  					<?php if($Nivel == 0 || $Nivel == 8 || $Nivel == 9 || $Nivel == 5 || $this -> session -> userdata('usuario') == 'Carlos'  || $Nivel == 19 || $Nivel == 15 ){?>
  						<td align="center"><img src="/system/img/reportes/emercancia.png" style='width:48px' onClick="muestra_div('rep_entregas');$('#Bancos').hide();$('#Direccion').hide();"/><br>Entrega de Mercancia</td>
  						<td style="width: 50px" ></td>
  					<?php } ?>
  					<?php if($Nivel == 0 || $Nivel == 8 || $Nivel == 9 || $this -> session -> userdata('usuario') == 'Carlos'){?>
  						<td align="center"><img src="/system/img/reportes/ncliente.png" style='width:48px' onClick="muestra_div('rep_ncliente');$('#Bancos').hide();$('#Direccion').hide();"/><br>Nuevos Clientes</td>
  						<td style="width: 50px" ></td>
  					<?php } ?>
  					<?php if($Nivel == 5  || $Nivel == 9 || $Nivel == 8 || $this -> session -> userdata('usuario') == 'Carlos'){?>
  						<td align="center"><img src="/system/img/reportes/solicitud.png" style='width:48px' onClick="muestra_div('filtro');$('#Bancos').hide();$('#Direccion').hide();"/><br>Solicitudes</td>
  						<td style="width: 50px" ></td>
  					<?php } ?>
  					
  				</tr>
  				<tr style='height: 20px'></tr>
  				<tr>
            <?php if($Nivel == 0 || $Nivel == 8 || $Nivel == 9 || $Nivel == 3 || $Nivel == 2 || $this -> session -> userdata('usuario') == 'Carlos'){?>
              <td align="center"><img src="/system/img/reportes/cmensual.png" style='width:48px' onClick="muestra_div('ControlMensual');$('#Bancos').hide();$('#Direccion').hide();"/><br>Control Mensual</td>
              <td style="width: 50px"></td>
            <?php } ?>
  			<?php if($Nivel == 0 || $Nivel == 8  || $Nivel == 9  || $Nivel == 20 || $this -> session -> userdata('usuario') == 'Carlos'){?>
  						<td align="center"><img src="/system/img/reportes/ccarga.png" style='width:48px' onClick="muestra_div('ccargas_domi');$('#Bancos').hide();$('#Direccion').hide();"/><br>Control Cargas Domi</td>
  						<td style="width: 50px" ></td>
  						<td align="center"><img src="/system/img/reportes/cvoucher.png" style='width:48px' onClick="muestra_div('ccargas_vou');$('#Bancos').hide();$('#Direccion').hide();"/><br>Control Cargas Voucher</td>
  						<td style="width: 50px" ></td>
  					<?php } ?>
  			
          </tr>
          
          <tr>
  					<td align="center"><img src="/system/img/reportes/factura.png" style='width:48px' onClick="muestra_div('facturas_pendientes');$('#Bancos').hide();$('#Direccion').hide();"/><br>Estatus de Facturas</td>
  					<td style="width: 50px"></td>
  					
  					
  					<?php if($Nivel == 0 ||  $this -> session -> userdata('usuario') == 'Carlos' ){?>
  						<td align="center"><img src="/system/img/reportes/cuadre.png" style='width:48px' onClick="muestra_div('control_vendedores');$('#Bancos').hide();$('#Direccion').hide();"/><br>Cuadre de Vendedores</td>
  						<td style="width: 50px"></td>
  					<?php } ?>
  					
  					<!-- Nuevo Reporte Especial -->
  					<td align="center"><img src="/system/img/reportes/factura.png" style='width:48px; cursor: pointer;' onClick="mostrarReportesEspeciales();" title="Reportes Especiales"/><br>Reportes Especiales</td>
  					<td style="width: 50px"></td>
  					
  				</tr>
          
          
          
          
  				</table>	
				<div id="facturas_pendientes" class="dialogo"  title="Facturas Pendientes Por Aceptar">
					<br>
					<?php
					$this -> load -> view("reportes/facturaspendientes.php");
					?>
				</div>
				<div id="muestra_historial_coversaciones" class="dialogo" title="Historial de Conversaciones">
					<br>
					<?php $this -> load -> view("reportes/historialclientes.php"); ?>
				</div>

				<div id="muestra_cuadre" class="dialogo" title="Historial de Cuotas Cargadas">
					<br>
					<?php $this -> load -> view("reportes/cuadrecajas.php"); ?>									
				</div>
				<div id="busca_contrato" class="dialogo" title="Buscar Contratos">
					<br>
					<?php $this -> load -> view("reportes/buscacontrato.php"); ?>									
				</div>
				<div id="muestra_cheque" class="dialogo"  title="Consultar Cheques">
					<br>
					<?php
					$this -> load -> view("reportes/cheques.php");
					?>
				</div>
				<div id="voucher" class="dialogo"  title="Consultar Voucher">
					<br>
					<?php
					$this -> load -> view("reportes/voucher.php");
					?>
				</div>
				<div id="usuarios_contratos" class="dialogo" title="Historial de Contratos por Usuarios">
					<br>
					<?php $this -> load -> view("reportes/contratos_usuarios.php"); ?>
				</div>
				<div id="depositos_pendientes" class="dialogo" title="Filtrar Depositos Pendientes">
					<br>
					<?php $this -> load -> view("reportes/pendientesdeposito.php"); ?>
				</div>
				<div id="rep_inven" class="dialogo" title="Filtrar Inventario">
					<br>
					<?php $this -> load -> view("reportes/inventario.php"); ?>
				</div>
				<div id="rep_sinpago" class="dialogo" title="Filtro Mensual De Contol De Pagos">
					<br>
					<?php $this -> load -> view("reportes/sinpago.php"); ?>
				</div>
				<div id="rep_contado" class="dialogo" title="Filtro De Facturas De Contado">
					<br>
					<?php $this -> load -> view("reportes/contado.php"); ?>
				</div>
				<div id="rep_fpresu" class="dialogo" title="Filtro Facturas Presupuesto">
					<br>
					<?php $this -> load -> view("reportes/fpresupuesto.php"); ?>
				</div>
				<div id="rep_fcontrol" class="dialogo" title="Filtro Facturas Formato Control">
					<br>
					<?php $this -> load -> view("reportes/fcontrol.php"); ?>
				</div>
				<div id="rep_entregas" class="dialogo" title="Filtro Entrega Mercancia">
					<br>
					<?php $this -> load -> view("reportes/rep_entregas.php"); ?>
				</div>
				<div id="rep_ncliente" class="dialogo" title="Filtro Nuevos Clientes">
					<br>
					<?php $this -> load -> view("reportes/nclientes.php"); ?>
				</div>
				<div id="ccargas_vou" class="dialogo" title="Filtro Pagos Por Voucher">
					<br>
					<?php $this -> load -> view("reportes/ccargasv.php"); ?>
				</div>
				
				
				<div id="control_vendedores" class="dialogo" title="Filtro Control de Vendedores">
					<br>
					<?php $this -> load -> view("reportes/cvendedores.php"); ?>
				</div>
				 
				<div id='filtro' class="dialogo" title="Filtro Solicitudes"></div>
			</div>	
			
			
			<div id="ControlMensual" class="dialogo" title="Historial de Control de Pagos ">
          <br>
          <?php $this -> load -> view("reportes/controlmensual.php"); ?>                 
        </div>
			
			
			
			
				<br><br>
				
				
			<div id="Bancos" style="width:800px; Height:1000px;display:none">
				<h2>RAZON SOCIAL<BR> COOPERATIVA ELECTRON 465 RL. &nbsp; &nbsp;&nbsp; RIF: J-31207419-1<BR> GRUPO ELECTRON 465 C.A. &nbsp; &nbsp;&nbsp; RIF: J-29837846-8</h2>
				<center>
					
				<table class="TGrid" style="width: 100%;">
					<thead><th>BANCO</th><th>EMPRESA</th><th>CUENTA</th></thead>
					<tbody>
						<tr><td>BANCO SOFITASA</td><td>COOPERATIVA ELECTRON 465 RL</td><td>01370021440000099741</td></tr>
						<tr><td>BANCO INDUSTRIAL </td><td>COOPERATIVA ELECTRON 465 RL</td><td>00030064120001042759</td></tr>
						<tr><td>BANCO INDUSTRIAL </td><td>GRUPO ELECTRON 465 C.A</td><td>00030064170001058979</td></tr>
						<tr><td>BANCO PROVINCIAL </td><td>COOPERATIVA ELECTRON 465 RL</td><td>01080372150100025942</td></tr>
						<tr><td>BANCO PROVINCIAL </td><td>GRUPO ELECTRON 465 C.A</td><td>01080372160100063534</td></tr>
						<tr><td>BANCO DE VENEZUELA </td><td>COOPERATIVA ELECTRON 465 RL</td><td>01020151990000022525</td></tr>
						<tr><td>BANCO DE VENEZUELA </td><td>GRUPO ELECTRON 465 C.A</td><td>01020441100000071479</td></tr>
						<tr><td>BANCO MERCANTIL </td><td>COOPERATIVA ELECTRON 465 RL</td><td>01050092351092060936</td></tr>
						<tr><td>BANCO DEL SUR </td><td>COOPERATIVA ELECTRON 465 RL</td><td>01570076963776200986</td></tr>
						<tr><td>BANCO BANESCO</td><td>COOPERATIVA ELECTRON 465 RL</td><td>01340209472091008389</td></tr>
						<tr><td>BANCO BICENTENARIO</td><td>COOPERATIVA ELECTRON 465 RL</td><td>01750011250000034702</td></tr>
						<tr><td>BANCO BICENTENARIO</td><td>GRUPO ELECTRON 465 C.A</td><td>01750541650071150460</td></tr>
						<tr><td>BANCO BOD</td><td>COOPERATIVA ELECTRON 465 RL</td><td>01160183950006365779</td></tr>
						<tr><td>BANCO BFC</td><td>GRUPO ELECTRON 465 C.A</td><td>01510138528138022782</td></tr>
						<tr><td>BANCO CARONI</td><td>COOPERATIVA ELECTRON 465 RL</td><td>01280072047200668106</td></tr>
					</tbody>
				</table>
				</center>
			</div>
			<div id="Direccion" style="width:800px; Height:1000px;display:none">
				<h2>LISTA DE DIRECCIONES DE OFICINA Y SUCURSALES<BR> COOPERATIVA ELECTRON 465 RL. &nbsp; &nbsp;&nbsp; RIF: J-31207419-1<BR> GRUPO ELECTRON 465 C.A. &nbsp; &nbsp;&nbsp; RIF: J-29837846-8</h2>
				<center>
					
				<table class="TGrid" style="width: 100%;">
					<thead><th>CIUDAD</th><th>DIRECCIÓN</th><th>TELEFONO</th><th>CORREO</th><th>ENCARGADO</th></thead>
					<tbody>
						<tr><td>ADMINISTRACION/COBRANZA</td><td>Av. Don Tulio, C.C La Sevillana, Planta Baja Local S/N. Paseo La Feria.</td><td>0274-9358009</td><td>PENDIENTE</td><td>Por Asignar</td></tr>
						<tr><td>MERIDA VENTAS</td><td>Av. Don Tulio, C.C La Sevillana, Planta Baja Local S/N. Paseo La Feria.</td><td>0274-2512260</td><td>PENDIENTE</td><td>Alvaro Zambrano</td></tr>						
						<tr><td>MERIDA PLANIFICACION/ VOUCHER</td><td>Mérida</td><td>0274-6351037</td><td>PENDIENTE</td><td>Alvaro Zambrano</td></tr>
						<tr><td>SANTA BARBARA</td><td>Av. 7 con Av. Bolivar Frente al Banco Bicentenario C.C. EL DORADO Local N.1 </td><td>0275-9888520</td><td>PENDIENTE</td><td>Andreina Arismendi </td></tr>
						<tr><td>VIGIA</td><td>Av. Don Pepe Rojas, Frente Al Edificio El Triangulo al Lado de Tornicenter.</td><td>0275-8812778 </td><td>PENDIENTE</td><td>Nelly Davila</td></tr>
						<tr><td>NUEVA BOLIVIA</td><td> Calle San Benito con esquina de la av. 6 primer local una cuadra arriba de la plaza bolivar.</td><td>0424-7844140</td><td>PENDIENTE</td><td>Mayra Davila</td></tr>
						<tr><td>SAN CRISTOBAL</td><td>Centro; Calle 6, Carretera 8 C.C La Diversion Piso 2, Local 4, Diagonal a Guarauno.</td><td>0276-3440425 </td><td>PENDIENTE</td><td>Alexandra Montoya</td></tr>
						<tr><td>BARQUISIMETO</td><td>DIRECION: CARRERA 29 ENTRE CALLES 21 Y 22 MINI C.C. PASEO UNIVERSITARIO LAS AMERICAS PLATA BAJA LOCAL 2-A DIAGONAL AL COLEGIO UNIVERSITARIO FERMIN TORO</td><td>0251-9901163 </td><td>PENDIENTE</td><td>Yackelin D&aacute;vila</td></tr>
						<tr><td>VALENCIA</td><td>Municipio San Diego; Av. Don Julio Centeno, C.C San Diego (Antiguo fin de Siglo)  El Gran bazar, Planta Alta Local: M32-10 Valencia Carabobo.</td><td>0241-9224100</td><td>PENDIENTE</td><td>Por Asignar</td></tr>
						<tr><td>MARACAIBO</td><td>Av. 15 Las Delicias  Casco Central, C.C Gran Bazar Piso 2, Local 1524.</td><td>0261-2110014/ 04246993524</td><td>PENDIENTE</td><td>Andreina Uzcategui</td></tr>
						<tr><td>BARINAS</td><td> Centro Barinas Calle Camejo con Libertad. Edif. Lemiraje piso 2 local #3 frente a la plaza del estudiante al lado de farma todo.</td><td>0273-5521862</td><td>PENDIENTE</td><td>Daniela Dávila</td></tr>
						<tr><td>PUERTO ORDAZ</td><td> Torre Caura, local PB-1, al lado de emisoras FM Center, Puerto Ordaz, Mcpio Caron&iacute;, Edo Bolivar</td><td>0416-6732275 / 0286-9626355</td><td>PENDIENTE</td><td>Carlos Jimenez</td></tr>
						<tr><td>PUERTO LA CRUZ</td><td>Calle Maneiro con calle Honduras. Centro Comercial Puerto Plaza. 2do piso local 10. Puerto la Cruz Estado Anzoategui.</td><td>0286-9626355/0424-7650300/0281-5149724</td><td>PENDIENTE</td><td>Adriana Rondón</td></tr>
						<tr><td>EL TIGRE</td><td>Avenida Francisco de Miranda Calle 22 Sur, Edificio Maria Francis, Frente al Edificio El Coloso, Local No 4. El Tigre Estado Anzoátegui.</td><td>0283-9895290/0283-9895292/0283-2422533/0286-9626355/0424-7650300</td><td>PENDIENTE</td><td>Por Asignar</td></tr>
						<tr><td>MATURIN</td><td>Av. Bolivar calle 11 Nro. 49 entre calle 10 y 11,  Centro Comercial Plaza Guarapiche Nivel Mezzanina Local Nro 13 frente al Banco de Venezuela diagonal a la plaza gallego Maturin Edo. Monagas.</td><td>04149772479</td><td>PENDIENTE</td><td>Maribal Puente</td></tr>
						<tr><td>MARACAY</td><td>Av. Miranda #24 Frente a CANTV.Calle 5 de Julio y Lopez Aveledo, Edificio Gustabo, Oficina 110. Maracay Est. Aragua.Codigo Postal 2101</td><td>02432198759</td><td>PENDIENTE</td><td>Por Asignar</td></tr>	
					</tbody>
				</table>
				</center>
			</div>	
			<div id="Reportes" style="height: 500px"></div>
			<div id='graf' style="display:none;float: left;top:100px;left:50%;position:absolute;width:300px;"><div id='torta' ></div><div style="float:right;"><img src="/cooperativa-electron/system/img/botones/close.png" onclick="$('#graf').hide();"></img></div></div>
  </div>

  <!-- Modal para Reportes Especiales -->
  <div id="modal_reportes_especiales" title="Reportes Especiales - Filtros Avanzados" style="display: none;">
    <div style="display: flex; flex-wrap: wrap; gap: 20px; padding: 15px; background-color: #f9f9f9; border-radius: 8px;">
      <div style="flex: 1; min-width: 250px;">
        <label for="filtro_banco_especial">Banco:</label><br>
        <select id="filtro_banco_especial" style="width: 100%; padding: 8px; margin-bottom: 10px; border: 1px solid #ccc; border-radius: 4px;"></select>
      </div>
     
      <div style="flex: 1; min-width: 250px;">
        <label for="filtro_estado_especial">Estado del Contrato:</label><br>
        <select id="filtro_estado_especial" style="width: 100%; padding: 8px; margin-bottom: 10px; border: 1px solid #ccc; border-radius: 4px;">
          <option value="">TODOS LOS ESTADOS</option>
          <option value="morosos">MOROSOS</option>
          <option value="al_dia">AL DÍA</option>
          <option value="terminados">TERMINADOS</option>
        </select>
      </div>
    </div>
  </div>

</body>
</html>
