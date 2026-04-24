<?php
/**
 * Controlador de Barra de menu
 *
 * @author Carlos Enrique Peña Albarrán
 * @package cooperativa.system.application.model
 * @version 2.0.0
 */
class CMenu extends Model {
	/**
	 * HTML
	 * @var string
	 */
	public $strHTML;

	public function getHtml_Menu($intNivel) {
		$Mnu = '';
		$TMnu = '';
		$sPendientes = '';
		$ubica = $this -> session -> userdata('ubicacion');
		$usu = strtolower($this -> session -> userdata('usuario'));
		if ($intNivel == 0 || $usu == "carlos" || $intNivel == 8 || $intNivel == 9) {
			$Mnu = '
			<li id="minventario"><a href="inventario"><span>Inventario</span></a>
				<ul><!--<li id="minventario" class="active"><a href="Listar_Inventario"><span>Lista Inventario</span></a></li>--!>
					<li id="minventario" class="active"><a href="Mercancia_Inventario"><span>Ver Mercancia</span></a></li>
					<li id="minventario" class="active"><a href="Entregar_Inventario_Oficina"><span>Orden de Entrega</span></a></li>
					<li id="minventario" class="active"><a href="Mercancia/1"><span>Material De Oficina</span></a></li>
				</ul>
			</li>
		   	<li id="mpanel"><a href="Panel"><span>Panel de Control</span></a>
		   		<ul>
					<li id="usu" class="active"><a href="Crear_Usuario"><span>Crear Usuario</span></a></li>
					<li id="ubi" class="active"><a href="Crear_Ubicacion"><span>Crear Ubicacion</span></a></li>
					<li class="active" ><a href="Cambiar_Ubicacion_Facturas"><span>C. Ubicacion</span></a></li>
					<li id="tra" class="active"><a href="Panel_Traslado"><span>Traslados Masivos</span></a></li>
					<li id="ssoli" class="active"><a href="subir_solicitud"><span>Expedientes Solicitud</span></a></li>
					<li id="ssoli" class="active"><a href="panelPlanes"><span>Configurar Plan Corporativo</span></a></li>
				</ul>
		   	</li>		';
		}

		if ($intNivel == 0 || $intNivel == 10 || $intNivel == 8 || $intNivel == 9 || $usu == 'carlos') {
			$Mnu .= '
			<li id="minventario"><a href="Acuse_Recibo_Administracion"><span>Acuse Administración</span></a>
				
			</li>';
		}

		if ($usu == 'poleeth') {
			$TMnu .= '<li id="mcobranza"><a href="Cobranza"><span>Cobranza BFC</span></a></li>';
		}
		if ($intNivel == 15) {
			$TMnu .= '
				<li id="ssoli"><a href="subir_solicitud"><span>Expedientes Solicitud</span></a>
				<ul><li class="active"><a href="Cambiar_Ubicacion_Facturas"><span>C. Ubicacion</span></a></li></ul></li>
			';
		}
		if ($usu == 'alvaroz') {
			$TMnu .= '
				<li id="minventario"><a href="inventario"><span>Inventario</span></a>
					<ul><li id="minventario" class="active"><a href="Mercancia_Inventario"><span>Ver Mercancia</span></a></li>
					<li id="minventario" class="active"><a href="Entregar_Inventario_Oficina"><span>Orden de Entrega</span></a></li>
					</ul>
				</li>
				<li id="minventario"><a href="Panel_Alvaro"><span>P.Alvaro</span></a>
				<ul><li class="active"><a href="Cambiar_Ubicacion_Facturas"><span>C. Ubicacion</span></a></li></ul></li>
				
			';
		}
		//<li class="active" ><a href="#" Onclick="N_Ventana(\'Moto\')"><span>Moto</span></a></li>
		$Menu_B = '
		<div class="cssmenu">
		<ul>
		   <li class="active" id="mbuzon"><span><a href="buzon"><span id="iContador">Lo Nuevo (0)</span></a>
		   	<ul>
		   		<li class="active" ><a href="'.__BASE2__.'" target="_blank"><span>Asociar Entrega</span></a></li>
		   		<li class="active" ><a href="Sugerencia"><span>Sugerencias</span></a></li>
		   		<li class="active" ><a href="Solicitud"><span>Solicitud</span></a></li>';
		if ($intNivel == 0 || $usu == 'carlos') {
			$Menu_B .= '<li class="active" id="mbuzon"><span><a href="Liquidacion"><span id="iContador">Liquidacion</span></a></li>';
		}
        if ($usu == 'alvaro' || $usu == 'carlos' || $usu == 'alvaroz' || $usu == 'judelvis' || $ubica == 'Merida (Principal)' ||$ubica == 'Merida (Interet)') {
            $Menu_B .= '<li class="active" ><a href="#" Onclick="N_Ventana(\'ccalculosCorreo\')"><span>Calculo Correo</span></a></li>
                        <li class="active" ><a href="#" Onclick="N_Ventana(\'presupuestoCorreo\')"><span>Presupuesto Correo</span></a></li>
                        <li class="active" ><a href="estatusDocumentosRecibidos"><span>Estatus Documentos</span></a></li>
			';
        }

		if($usu == "sbarinas"){
			$Menu_B .= '<li class="active" ><a href="#" Onclick="N_Ventana(\'ccalculosCorreo\')"><span>Calculo Correo</span></a></li>
                        <li class="active" ><a href="#" Onclick="N_Ventana(\'presupuestoCorreo\')"><span>Presupuesto Correo</span></a></li>
			';
		}
        if ($usu == 'alvaro' || $usu == 'carlos' || $usu == 'dmarisol' || $usu == 'judelvis' ) {
            $Menu_B .= '<li class="active" ><a href="recepcionDocumento"><span>Recep. Documentos</span></a></li>

			';
        }
		if($ubica == 'Merida (Principal)' ||$ubica == 'Merida (Internet)'){
		$Menu_B .= '</ul>		   			
		   </li>
		   <li  id="mcliente"><a href="#"><span>Cliente</span></a>
		   		<ul>
                	<li class="active" ><a href="registrar"><span>Registrar Usuario</span></a></li>
                    <li class="active" ><a href="Asistente1"><span>Asistente</span></a></li>
                    <li id="ssoli" class="active"><a href="subir_solicitud"><span>Expedientes Solicitud</span></a></li>
                    <li class="active" ><a href="Certificar_Cliente"><span>Enviar Requisitos</span></a></li>
                    <li class="active" ><a href="#" Onclick="N_Ventana(\'subir_archivo2\')"><span>Ver Expediente Digital</span></a></li>
                    <li class="active" ><a href="#" Onclick="N_Ventana(\'subir_archivo\')"><span>Cargar Expediente Digital</span></a></li>
                    <li class="active" ><a href="Pronto_Pago"><span>Verifica Pronto Pago</span></a></li>
                    <li class="active" ><a href="Venta_Contado"><span>Venta De Contado</span></a></li>
                    <li class="active" ><a href="#" Onclick="N_Ventana(\'ccalculos2\')"><span>Calculo</span></a></li>
                    <li class="active" ><a href="#" Onclick="N_Ventana(\'Artefacto_Contado\')"><span>Presupuesto</span></a></li>
                    <li class="active" ><a href="#" Onclick="N_Ventana(\'planCorp\')"><span>Plan Corporativo</span></a></li>
                    <li class="active" ><a href="#" Onclick="N_Ventana(\'Presupuesto_Compuesto\')"><span>Presupuesto Compuesto</span></a></li>			
                    <li id="mresponsable" class="active"><a href="archivo_cliente"><span>Archivo</span></a></li>';
		}else{
			$Menu_B .= '</ul>		   			
		   </li>
		   <li  id="mcliente"><a href="#"><span>Cliente</span></a>
		   		<ul>
                	<li class="active" ><a href="registrar"><span>Registrar Usuario</span></a></li>
                    <li class="active" ><a href="Asistente1"><span>Asistente</span></a></li>
                    <li class="active" ><a href="Certificar_Cliente"><span>Enviar Requisitos</span></a></li>
                    <li class="active" ><a href="#" Onclick="N_Ventana(\'subir_archivo2\')"><span>Ver Expediente Digital</span></a></li>
                    <li class="active" ><a href="#" Onclick="N_Ventana(\'subir_archivo\')"><span>Cargar Expediente Digital</span></a></li>';
		}
		
		if ($usu == 'alvaro' || $usu == 'carlos' || $usu == 'yuli' || $intNivel == 3 || $intNivel == 10 || $usu == 'alvaroz'|| $usu == 'poleeth'  || $usu == 'georly' ||  $usu == 'dmarisol') {
			$Menu_B .= '<li id="mreciboi" class="active"><a href="Recibo_Ingreso"><span>Recibos de Ingreso</span></a></li>
						
			';
		}


		/*if ($this -> session -> userdata('ubicacion')=='Merida (Principal)' && ($intNivel == 4 || $intNivel == 5)) {
			$Menu_B .= '<li class="active" ><a href="Asistente1"><span>Asistente</span></a></li>
						<li id="ssoli" class="active"><a href="subir_solicitud"><span>Expedientes Solicitud</span></a></li>
			';
			
		}*/
		
		if ($this -> session -> userdata('ubicacion')=='Merida (Principal)' && $intNivel==4 &&  $usu != 'dmarisol') {
			$Menu_B .= '<li class="active" ><a href="Cambiar_Ubicacion_Facturas"><span>C. Ubicacion</span></a></li>
			<li id="mreciboi" class="active"><a href="Recibo_Egreso"><span>Recibos de Egreso</span></a></li>
			';
		}
		if ($usu == 'yuli' || $usu=='alvaroz'|| $usu=='judelvis') {
			$Menu_B .= '<li class="active" ><a href="Cambiar_Ubicacion_Facturas"><span>C. Ubicacion</span></a></li>
			<li id="mreciboi" class="active"><a href="Recibo_Egreso"><span>Recibos de Egreso</span></a></li>
			';
		}


		if ($usu == 'alvaro' || $usu == 'carlos' || $usu == 'revision' || $usu == 'judelvis') {
			$Menu_B .= '<li id="sexpe" class="active"><a href="subir_expediente"><span>Expedientes Revision</span></a></li>
			<li id="sexpe" class="active"><a href="Liquidacion"><span>Liquidacion</span></a></li>';
		}
		if ($intNivel == 10 ||$this -> session -> userdata('nivel') == 0 || $this -> session -> userdata('nivel') == 3 || $this -> session -> userdata('nivel') == 5 || $this -> session -> userdata('nivel') == 9 || $this -> session -> userdata('nivel') == 8 ) {
			$Menu_B .= '<li class="active" ><a href="Entregar_Inventario_Cliente"><span>Entregar Mercancia</span></a></li>
			';
		}
		if ($this -> session -> userdata('nivel') <= 3) {
			$sPendientes = '
		   		<li class="active" ><a href="Crear_Txt"><span>Crear Txt</span></a></li>
		   		<li class="active" ><a href="Leer_Txt"><span>Pendientes Por Procesar</span></a></li>
		   		<li class="active" ><a href="Ver_Txt"><span>Archivos Procesados</span></a></li>
		   		<li class="active" ><a href="cargaVenezuela"><span>Cargar B. Venezuela</span></a></li>
		   		
		   	';
		}
		if ($intNivel == 0 || $intNivel == 2 || $intNivel == 3 || $intNivel == 8 || $intNivel == 9) {
			$sPendientes .= '<li class="active" ><a href="Reporte_General"><span>Reportes Generales</span></a></li>';
		}
        if ($intNivel == 0 || $usu='judelvis'|| $usu='yuli') {
            $sPendientes .= '<li class="active" ><a href="modificaciones"><span>Modificaciones</span></a></li>';
        }
		$Menu_B .= '	</ul>
		   </li>
		   <li id="mreporte"><a href="factura_control"><span>Factura Control</span></a>		     
		   <li id="mreporte"><a href="reportes"><span>Reportes</span></a>
		   <ul><li class="active" ><a href="Descargas"><span>Descargas</span></a></li>
		   	' . $sPendientes . '</ul>
		   </li>		   
		   <li id="mconfigurar"><a href="Configurar"><span>Configurar</span></a></li>
		   ' . $TMnu . $Mnu . '	   
		   <li id="msalir"><a href="logout"><span>Cerrar Sesion</span></a></li>
		</ul>
		</div>';
		if ($intNivel == 12) {
			$Menu_B = '
			<div class="cssmenu">
			<ul>
				<li class="active" ><a href="#" Onclick="N_Ventana(\'subir_archivo2\')"><span>Ver Expediente Digital</span></a></li>
				<li id="msalir"><a href="logout"><span>Cerrar Sesion</span></a></li>
			</ul>';
		}
		if ($intNivel == 13 || $intNivel == 14) {
			$Menu_B = '
			<div class="cssmenu">
			<ul>
			<li class="active" id="mbuzon"><span><a href="buzon"><span id="iContador">Principal</span></a></li>
			<li  id="mcliente"><a href="registrar"><span>Cliente</span></a></li>
			<li  ><a href="#" Onclick="N_Ventana(\'subir_archivo\')"><span>Cargar Expediente Digital</span></a></li>
			<li  ><a href="#" Onclick="N_Ventana(\'subir_archivo2\')"><span>Ver Expediente Digital</span></a></li>
			<li id="mconfigurar"><a href="Configurar"><span>Configurar</span></a></li>
			<li id="mreporte"><a href="reportes"><span>Reportes</span></a>
			<li id="msalir"><a href="logout"><span>Cerrar Sesi&oacute;n</span></a></li>
			</ul>';
		}

		if ($intNivel == 18) {
			$Menu_B = '
		      <div class="cssmenu">
		      <ul>
		      <li class="active" id="mbuzon"><span><a href="buzon"><span id="iContador">Principal</span></a></li>
		      <li  id="mcliente"><a href="registrar"><span>Cliente</span></a></li>
		      <li ><a href="#" Onclick="N_Ventana(\'subir_archivo\')"><span>Cargar Expediente Digital</span></a></li>
		      <li ><a href="#" Onclick="N_Ventana(\'subir_archivo2\')"><span>Ver Expediente Digital</span></a></li>
		      <li id="mconfigurar"><a href="Configurar"><span>Configurar</span></a></li>
		      <li id="mreporte"><a href="reportes"><span>Reportes</span></a>
		      <li id="msalir"><a href="logout"><span>Cerrar Sesi&oacute;n</span></a></li>
		      </ul>';
			}

		if ($intNivel == 19) {
			$Menu_B = '
		      <div class="cssmenu">
		      <ul>
		      <li class="active" id="mbuzon"><span><a href="buzon"><span id="iContador">Principal</span></a></li>
		      <li ><a href="#" Onclick="N_Ventana(\'subir_archivo\')"><span>Cargar Expediente Digital</span></a></li>
		      <li id="mconfigurar"><a href="Configurar"><span>Configurar</span></a></li>
		      <li id="mreporte"><a href="reportes"><span>Reportes</span></a>
		      <li id="msalir"><a href="logout"><span>Cerrar Sesi&oacute;n</span></a></li>
		      </ul>';
			}

		if ($intNivel == 20) {
			$Menu_B = '
			<div class="cssmenu">
			<ul>
			<li class="active" id="mbuzon"><span><a href="buzon"><span id="iContador">Principal</span></a></li>
			
			<li id="msalir"><a href="logout"><span>Cerrar Sesion</span></a></li>
			</ul>';
		}

		$this -> strHTML = $Menu_B;
		return $this -> strHTML;
	}

	public function __construct() {
		parent::__construct();
	}

}
?>
