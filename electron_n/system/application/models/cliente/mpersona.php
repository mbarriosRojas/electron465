<?php
/**
 *  @author Carlos Enrique Penaa Albarran
 *  @package SaGem.system.application.model
 *  @version 1.0.0
 */
class CPersonas extends Model {

	/**
 	* Documento de Identificacion
 	* @var integer
 	*/
	var $documento_id;

	/**
 	* Primer Nombre
 	* @var string
 	*/
	var $primer_nombre;

	/**
 	* Segundo Nombre
 	* @var string
 	*/
	var $segundo_nombre;

	/**
 	* Primer Apellido
 	* @var string
 	*/
	var $primer_apellido;

	/**
 	* Segundo Apellido
 	* @var string
 	*/
	var $segundo_apellido;

	/**
 	* Apellido de Casada
 	* @var string
 	*/
	var $apellido_casada = "";

	/**
 	* Numero de Documento
 	* @var string
 	*/
	var $nro_documento = "";

	/**
 	* Fecha de Nacimiento
 	* @var string
 	*/
	var $fecha_nacimiento;

	/**
 	* Nacionalidad
 	*
 	* Se utiliza el siguiente formato: Venezolano (V-), Extranjero (E-)
 	* @var string
 	*/
	var $nacionalidad;

	/**
 	* Ciudad
 	* @var string
 	*/
	var $ciudad;

	/**
 	* Cargo que Desempena actualmente
 	* @var string
 	*/
	var $cargo_actual;

	/**
 	* Sexo
 	*
 	* Se utiliza el siguiente formato: Masculino (M), Femenino (F)
 	* @var string
 	*/
	var $sexo;

	/**
 	* Estado Civil
 	*
 	* @var string
 	*/
	var $estado_civil;

	/**
 	* Tel�fono
 	* @var string
 	*/
	var $telefono;

	/**
 	* @var string
 	*/
	var $celular;

	/**
 	* @var string
 	*/
	var $municipio;

	/**
 	* @var string
 	*/
	var $parroquia;
	/**
 	* @var string
 	*/
	var $sector;

	/**
 	* @var string
 	*/
	var $avenida;
	/**
 	* @var string
 	*/
	var $calle;
	/**
 	* @var string
 	*/
	var $urbanizacion;

	/**
 	* @var string
 	*/
	var $direccion;

	/**
 	* Correo
 	* @var string
 	*/
	var $correo;

	/**
 	* Si aun esta vivo (FE DE VIDA)
 	* @var boolean
 	*/
	var $vive;

	/**
 	*
 	* @var string
 	*/
	var $observacion;

	/************************************************/

	/**
 	* Codigo De La Nomina
 	*
 	* var string
 	*/
	var $codigo_nomina;

	/**
 	* Nuevo Codigo Del Empleado
 	*
 	* @var string
 	*/
	var $codigo_nomina_aux = "";

	/**
 	* Numero de Caja Del Contenedor (Ubicacion)
 	*
 	* @var string
 	*/
	var $expediente_caja = "";

	/**
 	* Codigo aplica en el caso de los Jubilados Pensionados
 	*
 	* @var string
 	*/
	var $codigo_gaceta = "";

	/**
 	* Fecha Ingreso
 	*
 	* @var string
 	*/
	var $fecha_ingreso = "";

	/**
 	* Ubicacion departamental
 	*
 	* @var string
 	*/
	var $ubicacion;

	/**
 	* DEPENDECIA EN LA QUE LA LABORA
 	*
 	* @var string
 	*/
	var $direccion_trabajo;

	/**
 	* Consigno Copia de la Cedula de Identidad (FISICO)
 	*
 	* @var boolean
 	*/

	var $copia_ci = "";

	/**
 	* Consigno Copia del ultimo estado de banco (FISICO)
 	*
 	* @var boolean
 	*/
	var $copia_ba = "";

	/**
 	* Datos de Consignacion Usado solo en (PENSIONADOS-JUBILADOS)
 	*
 	* @var boolean
 	*/
	var $titular = "";

	/**
 	* Fe de vida (PENSIONADOS-JUBILADOS)
 	*
 	* @var boolean
 	*/
	var $fe_vida = 0;

	/**
 	* Banco - Descripcion
 	*
 	* @var string
 	*/
	var $banco_1;
	/**
 	* Banco - Cuenta Bancaria
 	*
 	* @var string
 	*/
	var $cuenta_1;
	/**
 	* Banco - Tipo: AHORRO | CORRIENTE | NOMINA UNICA
 	*
 	* @var string
 	*/
	var $tipo_cuenta_1;

	/**
 	* Banco - Descripcion
 	*
 	* @var string
 	*/
	var $banco_2;
	/**
 	* Banco - Cuenta Bancaria
 	*
 	* @var string
 	*/
	var $cuenta_2;
	/**
 	* Banco - Tipo: AHORRO | CORRIENTE | NOMINA UNICA
 	*
 	* @var string
 	*/
	var $tipo_cuenta_2;

	/**
 	* Banco - Descripcion
 	*
 	* @var string
 	*/
	var $banco_3;
	/**
 	* Banco - Cuenta Bancaria
 	*
 	* @var string
 	*/
	var $cuenta_3;
	/**
 	* Banco - Tipo: AHORRO | CORRIENTE | NOMINA UNICA
 	*
 	* @var string
 	*/
	var $tipo_cuenta_3;

	/**
 	* @orm boolean
 	*/
	var $disponibilidad = "";

	/**
 	* Registro de Informacion Fiscal (Seniat)
 	*
 	* @var string
 	*/
	var $rif = "";

	/**
 	* Consigno Foto (PENSIONADOS-JUBILADOS)
 	*
 	* @var boolean
 	* @orm bool
 	*/
	var $foto = "";

	/**
 	* Consigno Gaceta (PENSIONADOS-JUBILADOS)
 	*
 	* @var boolean
 	*/
	var $gaceta = "";

	/**
 	* ENTE DE PROCEDENCIA
 	*
 	* @var string
 	*/
	var $ente_procedencia = "";

	/**
 	* Fecha de Vacaciones
 	* @var string
 	*/
	var $fecha_vacaciones;

	/**
 	* Consigno Copia de la libreta (FISICO)
 	*
 	* @var boolean
 	* @orm bool
 	*/
	var $copia_libreta = "";

	/**
 	* Consigno Copia de la libreta (FISICO)
 	*
 	* @var boolean
 	* @orm bool
 	*/
	var $monto_vacaciones = "";

	/**
 	* Consigno Copia de la libreta (FISICO)
 	*
 	* @var boolean
 	* @orm bool
 	*/
	var $monto_aguinaldos = "";

	/**
 	* CARGO QUE OCUPABA ANTERIORMENTE
 	*
 	* @var string
 	*/
	var $cargo_ocupaba = "";

	function __construct() {
		parent::__construct();

	}

}
?>