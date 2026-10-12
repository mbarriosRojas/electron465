<?php
/**
 *  @author Carlos Enrique Penaa Albarran
 *  @package SaGem.system.application.model
 *  @version 1.0.0
 */
class MCredito extends Model {

	/**
 	* Documento de Identificacion
 	* @var integer
 	*/
	var $documento_id;

	/**
 	* Numero de Contrato
 	* @var string
 	*/
	var $contrato_id;

	/**
 	* Fecha de Solicitud
 	* @var date
 	*/
	var $fecha_solicitud;

	/**
 	* Inicio de Cobro
 	* @var date
 	*/
	var $fecha_inicio_cobro;

	/**
 	* Numero de Contrato
 	* @var string
 	*/
	var $motivo;

	/**
 	* Numero de Contrato
 	* @var string
 	*/
	var $condicion;

	/**
 	* Numero de Contrato
 	* @var string
 	*/
	var $num_operacion;

	/**
 	* Inicio de Cobro
 	* @var date
 	*/
	var $fecha_operacion;

	/**
 	* Numero de Contrato
 	* @var double
 	*/
	var $cantidad;

	/**
 	* Numero de Contrato
 	* @var double
 	*/
	var $monto_total;

	/**
 	* Numero de Contrato
 	* @var integer
 	*/
	var $numero_cuotas;

	/**
 	* Numero de Contrato
 	* @var integer
 	*/
	var $periocidad;

	/**
 	* Numero de Contrato
 	* @var string
 	*/
	var $nomina_procedencia;

	/**
 	* Numero de Contrato
 	* @var double
 	*/
	var $monto_cuota;

	/**
 	* Numero de Contrato
 	* @var integer
 	*/
	var $forma_contrato;

	/**
 	* Numero de Contrato
 	* @var string
 	*/
	var $empresa;

	/**
 	* Numero de Contrato
 	* @var string
 	*/
	var $cobrado_en;

	/**
 	* Numero de Factura
 	* @var string
 	*/
	var $numero_factura;

	/**
 	* Numero de Factura
 	* @var double
 	*/
	var $monto_operacion;

	/**
 	* Estatus
 	* @var integer
 	*/
	var $estatus;

	/**
 	* Lista de Seriales
 	* @var string
 	*/
	var $serial;

	/**
 	* Lista de Seriales
 	* @var string
 	*/
	var $estado_verificado = 0;

	/**
 	* Lista de Seriales
 	* @var string
 	*/
	var $fecha_verificado = null;

	/************************************************/

	/**
 	* Codigo De La Nomina
 	*
 	* var string
 	*/
	var $codigo_n;

	/**
 	* Nuevo Codigo Del Empleado
 	*
 	* @var string
 	*/
	var $codigo_n_a = "";

	/**
 	* Numero de Caja Del Contenedor (Ubicacion)
 	*
 	* @var string
 	*/
	var $expediente_c = "";

	/**
 	* Lista de Seriales
 	* @var string
 	*/
	var $marca_consulta = 0;

	function __construct() {
		parent::__construct();

	}

}

?>