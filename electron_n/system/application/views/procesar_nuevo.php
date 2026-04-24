
<html>
<head>
  <?php $this->load->view("incluir/cabezera.php");?>
  <link href="<?php echo __CSS__ ?>tgrid/TGrid.css" rel="stylesheet" type="text/css" media="screen"/>
	<link href="<?php echo __CSS__ ?>tgrid/TGridPrint.css" rel="stylesheet" type="text/css" media="print"/>
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>view/procesar_nuevo.js"></script>
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>view/detalleCobro.js"></script>	
	
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>tgrid/func.js"></script>
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>tgrid/tgrid.js"></script>
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>tgrid/paginador.js"></script>
	
	<script type="text/javascript">
		function Actualizar(){
			
			<?php
			if(isset($cedula)){
			?>
				consulta_cliente(<?php echo $cedula;?>);
				
			<?php
			}
			?>
			<?php
			if(isset($voucher)){
			?>
				CVoucher('<?php echo $voucher;?>');
			<?php
			}
			?>
			
		}
	</script>
	
	<style type="text/css">
		#dialog_link {padding: .1em 1em .20em 6px;text-decoration: none;position: relative;}
		#dialog_link span.ui-icon {margin: 5px 0 0;position: absolute;center: 1em;top: 1%;margin-top: 10px;}
		ul#icons {margin: 0; padding: 0;}
		ul#icons li {margin: .3px; position: relative; padding: .3px 0; cursor: pointer; float: left;  list-style: none;}
		ul#icons span.ui-icon {float: left; margin: .5px;}
	</style>	 
	
  </head>
<body onload="$('#carga_busqueda').dialog('close');Actualizar()">
	<div id="r_suspender"><h2>DEBE INGRESAR TODOS LOS DATOS</h2>
<table>
	<tr>
		<td class="formulario">Peticion:</td>
		<td class="formulario">
			<input type="text" class="inputxt" name="txtRPeticion_Ced" id="txtRPeticion_Ced">
		</td>
	</tr>
	<tr>
		<td class="formulario">Motivo:</td>
		<td class="formulario">
			<textarea name="txtRMotivo_Ced" id="txtRMotivo_Ced" class="inputxt" rows="5" style="width: 350px; height: 60px"  ></textarea>
		</td>
	</tr>
</table></div>
	<div id="cabecera"><br>
		<?php $this->load->view("menu/buscar.php");?>
		<?php echo $Menu;?>
	</div>
  <div id="medio" style='width:  65%;'>
  	<div id='msj_alertas'></div><div id='tabla_cobros'></div>
  	<div id='respaldo_eliminacion'><?php $this -> load -> view("procesar/resp_procesar.php"); ?></div>
  	<div id="dialog" title="Detalles del credito (Estado de Cuenta)"><?php  	
  		$this->load->view("procesar/lista_cobros.php");
  	
  	?></div>
    <h2>Historial de Clientes en el Sistema</h2><br>
    <h3>Usuario Conectado: <?php echo $this->session->userdata('usuario'); ?> Fecha: <?php echo date("Y/m/d"); ?></h3>
    <br><br><br>
    <div id='Datos_Basicos'></div>
    <?php
    	if($lista==''){
    		echo '
    		<div id="tabs2" style="width:100%;Height:100%;"> 
			    <ul>
			    <li><a href="#Persona">Datos Cliente</a></li>
		        <li><a href="#Procesar" onclick="activos('.$cedula.');">Facturas Activas</a></li>
		        <li><a href="#Procesar2" onclick="cancelado('.$cedula.');">Facturas Canceladas</a></li>
		        <li><a href="#Contado" onclick="facturas_contado('.$cedula.');">Facturas Contado</a></li>
		        <li><a href="#Notas" onclick="CargarNotaCredito('.$cedula.');">Notas de Crédito</a></li>
		        </ul>
    			<div id="Persona"><div id="basico"></div><div id="historial"></div><div id="contactos"></div></div>
    			<div id=\'Procesar\' style="Height:400px"><input type="hidden" id="p_activo" value=0></><div id="activos"></div></div>
    			<div id=\'Procesar2\' style="Height:400px"><input type="hidden" id="p_cancelado" value=0></><div id="cancelado"></div></div>
    			<div id=\'Contado\' style="Height:150px;overflow:auto;"></div>
    			<div id="Notas"><div id="notas_credito">Actualmente no posee una nota</div></div>
    		</div>'; 
    	}else{
    		echo $lista;
    	}
    ?>
    
      
    	
  <div>
</body>
</html>
