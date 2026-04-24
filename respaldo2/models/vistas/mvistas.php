<?php
/*
 *  @author Carlos Enrique Peña Albarrán
 *  @package cooperativa.system.application.model.cliente
 *  @version 1.0.0
 */

class MVistas extends Model {

	public function __construct() {
		parent::__construct();
	}

	public function vista1() {
		$valores = array();
		$formulario[1] = array('etiqueta' => 'Codigo', 'id' => 'cod', 'tipo' => 'texto', 'clase' => 'icon5', 'requerido' => 1, 'onblur' => 'consulta()');
		$formulario[2] = array('etiqueta' => 'Estado', 'id' => 'oidc', 'tipo' => 'combo', 'clase' => 'icon2', 'elementos' => $valores);
		$formulario[3] = array('etiqueta' => 'Ciudad', 'id' => 'ciu', 'tipo' => 'texto', 'clase' => 'icon5', 'requerido' => 1);
		$formulario[4] = array('etiqueta' => 'Municipio', 'id' => 'mun', 'tipo' => 'texto', 'clase' => 'icon5', 'requerido' => 1);
		$formulario[5] = array('etiqueta' => 'Parroquia', 'id' => 'par', 'tipo' => 'texto', 'clase' => 'icon5', 'requerido' => 1);
		$formulario[6] = array('etiqueta' => 'Codigo Postal', 'id' => 'cop', 'tipo' => 'texto', 'clase' => 'icon5', 'requerido' => 1);
		$formulario[7] = array('etiqueta' => 'Sector', 'id' => 'sec', 'tipo' => 'texto', 'clase' => 'icon1', 'requerido' => 1);
		$formulario[8] = array('etiqueta' => 'Via Principal', 'id' => 'via', 'tipo' => 'texto', 'clase' => 'icon1', 'requerido' => 1);
		$formulario[9] = array('etiqueta' => 'Edificio/Casa', 'id' => 'edi', 'tipo' => 'texto', 'clase' => 'icon1', 'requerido' => 1);
		$formulario[10] = array('etiqueta' => 'Piso/Oficina', 'id' => 'pis', 'tipo' => 'texto', 'clase' => 'icon1', 'requerido' => 1);
		$formulario[11] = array('etiqueta' => 'Punto Referencia', 'id' => 'pre', 'tipo' => 'texto', 'clase' => 'icon1', 'requerido' => 1);
		$formulario[12] = array('etiqueta' => 'Telefono/Celular', 'id' => 'tel', 'tipo' => 'texto', 'clase' => 'icon1', 'requerido' => 1);
		$formulario[13] = array('etiqueta' => 'Correo Electronico', 'id' => 'cor', 'tipo' => 'texto', 'clase' => 'icon1', 'requerido' => 1);
		$formulario[14] = array('etiqueta' => 'Descripcion', 'id' => 'des', 'tipo' => 'texto', 'clase' => 'icon1');
		$botones[1] = array('etiqueta' => 'Guarda Cambios', 'metodo' => 1, 'onclick' => 'guardar()', 'tipo' => 'button');
		$objeto['php'] = array('campos' => $formulario, 'botones' => $botones);
		$objeto['json'] = json_encode($objeto['php']);

		return $objeto['json'];

	}

	public function fpresuesto() {
		$fecha = date("Y-m-d");
		$valores = array('' => 'SELECCIONE EMPRESA', '0' => 'COOPERATIVA', '1' => 'GRUPO');
		$formulario[1] = array('etiqueta' => 'Empresa', 'id' => 'empresa', 'tipo' => 'combo', 'elementos' => $valores, 'multicelda' => 1, 'desabilitado' => 1);
		$formulario[2] = array('etiqueta' => 'Generar Factura', 'id' => 'btnGenerar', 'tipo' => 'button', 'clase' => 'icon5', 'onclick' => 'generar()');
		$formulario[3] = array('etiqueta' => 'Factura', 'id' => 'factura', 'tipo' => 'texto', 'clase' => 'icon5', 'requerido' => 1);
		$formulario[4] = array('etiqueta' => 'Fecha', 'id' => 'fecha', 'tipo' => 'calendario', 'clase' => 'icon19', 'requerido' => 1,"etiqueta"=>$fecha,'desabilitado' => 1);
		$formulario[5] = array('etiqueta' => 'Cedula|Rif', 'id' => 'cedula', 'tipo' => 'texto', 'requerido' => 1, 'onblur' => 'consulta_cliente()', 'desabilitado' => 1);
		$formulario[6] = array('etiqueta' => 'Nombre|Razon Social', 'id' => 'nombre', 'tipo' => 'texto', 'requerido' => 1, 'desabilitado' => 'true', 'estilo' => 'width:100%;');
		$formulario[7] = array('etiqueta' => 'Telefono', 'id' => 'telf', 'tipo' => 'texto', 'requerido' => 1, 'desabilitado' => 'true');
		$formulario[8] = array('etiqueta' => 'Domcilio Fiscal', 'id' => 'direc', 'tipo' => 'textarea', 'requerido' => 1, 'estilo' => 'height:80px;width:100%;', 'multicelda' => 1, 'label' => 'Domicilio Fiscal');
		$formulario[9] = array('etiqueta' => 'Descripción', 'id' => 'des', 'tipo' => 'textarea', 'multicelda' => 1, 'estilo' => 'height:80px;width:100%;', 'label' => 'Descripción', 'onkeypress' => 'return sin(event);');
		$formulario[10] = array('etiqueta' => 'Cantidad', 'id' => 'cant', 'tipo' => 'texto', 'desabilitado' => 1);
		$formulario[11] = array('etiqueta' => 'Precio Unitario', 'id' => 'precio', 'tipo' => 'texto', 'desabilitado' => 1);
		$formulario[12] = array('etiqueta' => 'Agregar', 'id' => 'btnAgregar', 'tipo' => 'button', 'clase' => 'icon5', 'onclick' => 'agregar()');
		$formulario[13] = array('etiqueta' => 'Productos', 'id' => 'productos', 'tipo' => 'lista', 'clase' => 'icon5', 'ondblclick' => 'borrar()', 'estilo' => 'height:80px;width:100%;', 'multicelda' => 1);

		$botones[1] = array('etiqueta' => 'Guardar e Imprimir', 'metodo' => 1, 'tipo' => 'submit', 'clase' => 'icon8');
		$objeto = array('campos' => $formulario, 'botones' => $botones);
		return json_encode($objeto);
	}

	public function fcontrol(){
		$fecha = date("Y-m-d");
		$valores = array('0' => 'SELECCIONE EMPRESA', '0' => 'COOPERATIVA', '1' => 'GRUPO');//,'S&S' => '2');
		$formulario[1] = array('etiqueta' => 'Empresa', 'id' => 'empresa', 'tipo' => 'combo', 'elementos' => $valores, 'multicelda' => 1);
		$formulario[2] = array('etiqueta' => 'Factura', 'id' => 'factura', 'tipo' => 'texto', 'clase' => 'icon5', 'requerido' => 1);
		$formulario[3] = array('etiqueta' => 'Control', 'id' => 'control', 'tipo' => 'texto', 'clase' => 'icon5', 'requerido' => 1);
		$formulario[4] = array('etiqueta' => 'Fecha', 'id' => 'fecha', 'tipo' => 'calendario', 'clase' => 'icon19', 'requerido' => 1,"etiqueta"=>$fecha,'desabilitado' => 1);
		$formulario[5] = array('etiqueta' => 'Cedula|Rif', 'id' => 'cedula', 'tipo' => 'texto', 'requerido' => 1, 'onblur' => 'consulta_cliente()');
		$formulario[6] = array('etiqueta' => 'Nombre|Razon Social', 'id' => 'nombre', 'tipo' => 'texto', 'requerido' => 1,  'estilo' => 'width:100%;');
		$formulario[7] = array('etiqueta' => 'Telefono', 'id' => 'telf', 'tipo' => 'texto', 'requerido' => 1);
		$formulario[9] = array('etiqueta' => 'Descripción Del Producto o Servicio', 'id' => 'des', 'tipo' => 'textarea', 'multicelda' => 1, 'estilo' => 'height:80px;width:100%;', 'label' => 'Descripción Del Producto o Servicio', 'onkeypress' => 'return sin(event);');
		$formulario[10] = array('etiqueta' => 'Cantidad', 'id' => 'cant', 'tipo' => 'texto');
		$formulario[11] = array('etiqueta' => 'Precio Unitario', 'id' => 'precio', 'tipo' => 'texto');
		$formulario[12] = array('etiqueta' => 'Agregar', 'id' => 'btnAgregar', 'tipo' => 'button', 'clase' => 'icon5', 'onclick' => 'agregar()');
		$formulario[13] = array('etiqueta' => 'Productos', 'id' => 'productos', 'tipo' => 'lista', 'clase' => 'icon5', 'ondblclick' => 'borrar()', 'estilo' => 'height:80px;width:100%;', 'multicelda' => 1);

		$botones[1] = array('etiqueta' => 'Guardar e Imprimir', 'metodo' => 1, 'tipo' => 'submit', 'clase' => 'icon8');
		$objeto = array('campos' => $formulario, 'botones' => $botones);
		return json_encode($objeto);
	}

	public function Carchivo() {
		$valores = array('' => 'SELECCIONE EMPRESA', '0' => 'COOPERATIVA', '1' => 'GRUPO');
		$valores2 = array('0' => 'Banco');
		$banco = $this -> db -> query("SELECT cobrado_en FROM t_clientes_creditos GROUP BY cobrado_en");
		foreach ($banco -> result() as $ban) {
			$valores2[$ban -> cobrado_en] = $ban -> cobrado_en;
		}
		$valores3 = array('0' => 'Nomina');
		$nomina = $this -> db -> query("SELECT * FROM t_nominas order by nombre");
		foreach ($nomina -> result() as $nom) {
			$valores3[$nom -> nombre] = $nom -> nombre;
		}
		$valores4 = array('' => 'SELECCIONE TIPO', '0' => 'CANCELADO', '1' => 'ACTIVO');
		$valores5 = array('' => 'SELECCIONE Ubiciacion');
		$ubica = $this -> db -> query("SELECT descripcion FROM t_ubicacion");
		foreach ($ubica -> result() as $ubc) {
			$valores5[$ubc -> descripcion] = $ubc -> descripcion;
		}
		
		$formulario[1] = array('etiqueta' => 'Cedula|Rif', 'id' => 'cedula', 'tipo' => 'texto', 'requerido' => 1, 'onblur' => 'consulta_cliente()');
		$formulario[2] = array('etiqueta' => 'Numero Archivo', 'id' => 'narchivo', 'tipo' => 'texto', );
		$formulario[3] = array('etiqueta' => 'Banco', 'id' => 'banco', 'tipo' => 'combo', 'elementos' => $valores2,'multicelda' => 1);
		$formulario[4] = array('etiqueta' => 'Nomina', 'id' => 'nomina', 'tipo' => 'combo', 'elementos' => $valores3,'multicelda' => 1 );
		$formulario[5] = array('etiqueta' => 'Tipo', 'id' => 'tipo', 'tipo' => 'combo', 'elementos' => $valores4, );
		$formulario[6] = array('etiqueta' => 'Empresa', 'id' => 'empresa', 'tipo' => 'combo', 'elementos' => $valores, );
		$formulario[7] = array('etiqueta' => 'Contratos', 'id' => 'contratos', 'tipo' => 'texto', 'estilo' => 'width:100%;', 'multicelda' => 1);
		$formulario[8] = array('etiqueta' => 'Ubicacion', 'id' => 'ubicacion', 'tipo' => 'combo', 'estilo' => 'width:100%;', 'multicelda' => 1,'elementos' => $valores5);

		$botones[1] = array('etiqueta' => 'guardar', 'metodo' => 1, 'tipo' => 'submit', 'clase' => 'icon8');
		$objeto = array('campos' => $formulario, 'botones' => $botones);
		return json_encode($objeto);
	}

	public function Asistente1() {
		$valores = array('-' => 'SELECCIONE SEXO', 'F' => 'Femenino', 'M' => 'Maculino');
		$nacionalidad = array('V-' => 'Venezolano', 'E-' => 'Extranjero');
		$formulario[1] = array('etiqueta' => 'Nacionalidad', 'id' => 'nacionalidad', 'tipo' => 'combo', 'elementos' => $nacionalidad);
		$formulario[2] = array('etiqueta' => 'Cedula', 'id' => 'documento_id', 'tipo' => 'texto', 'requerido' => 1, 'onblur' => 'consulta_cliente()');
		$formulario[3] = array('etiqueta' => 'Primer Nombre', 'id' => 'primer_nombre', 'tipo' => 'texto', 'requerido' => 1);
		$formulario[4] = array('etiqueta' => 'Segundo Nombre', 'id' => 'segundo_nombre', 'tipo' => 'texto');
		$formulario[5] = array('etiqueta' => 'Primer Apellido', 'id' => 'primer_apellido', 'tipo' => 'texto', 'requerido' => 1);
		$formulario[6] = array('etiqueta' => 'Segundo Apellido', 'id' => 'segundo_apellido', 'tipo' => 'texto');
		$formulario[7] = array('etiqueta' => 'Sexo', 'id' => 'sexo', 'tipo' => 'combo', 'elementos' => $valores, 'multicelda' =>1);

		$botones[1] = array('etiqueta' => 'Siguiente > ', 'metodo' => 1, 'tipo' => 'submit', 'clase' => 'icon8','id'=>'btnGuardar');
		$objeto = array('campos' => $formulario, 'botones' => $botones);
		return json_encode($objeto);
	}
        
    public function Crea_Usuario() {
    	$this -> load -> library('gvista/GPesquema');
		$esquema = new GPesquema();
        $estatus = array('' => 'ESTATUS', '0' => 'Inactivo', '1' => 'Activo');
        $estatus2 = array('' => 'CONECTADO', '0' => 'Inactivo', '1' => 'Activo');
        /*$estados = $this -> db -> query("SELECT * FROM estados" );
        $aux = $estados -> result();
        $arr_estados = array(); 
        foreach ($aux as $est){
        	$pos = $est -> id_estado;
        	$arr_estados[$pos] = $est->estado;
        }*/
		
		$sqlPerfil = "SELECT * from t_perfil";
		$rsPerfil = $this -> db -> query($sqlPerfil);
		$perfil = array();
		foreach ($rsPerfil -> result() as $per) {
			$perfil[$per->oid] =  $per->descripcion; 
		}
		
		$sqlUbica = "SELECT * from t_ubicacion";
		$rsUbica = $this -> db -> query($sqlUbica);
		$ubica = array();
		foreach ($rsUbica -> result() as $ubi) {
			$ubica[$ubi->oid] =  $ubi->descripcion; 
		}
		
		$formulario[] = array('etiqueta' => 'OID', 'id' => 'oid', 'tipo' => 'texto','oculto'=>1,'clave' => 'index');
		$formulario[] = array('etiqueta' => 'Cedula', 'id' => 'documento_id', 'tipo' => 'texto', 'requerido' => 1, 'accion' => 'consulta','clave' => 'primary');
		$formulario[] = array('etiqueta' => 'Nombre', 'id' => 'descripcion', 'tipo' => 'texto', 'requerido' => 1);
		$formulario[] = array('etiqueta' => 'Seudonimo', 'id' => 'seudonimo', 'tipo' => 'texto');
		$formulario[] = array('etiqueta' => 'Clave', 'id' => 'clave', 'tipo' => 'texto');
		$formulario[] = array('etiqueta' => 'Correo', 'id' => 'correo', 'tipo' => 'textarea');
        $formulario[] = array('etiqueta' => 'Fecha', 'id' => 'fecha', 'tipo' => 'calendario');
        $formulario[] = array('etiqueta' => 'Estatus', 'id' => 'estatus', 'tipo' => 'combo', 'elementos' => $estatus);
		$formulario[] = array('etiqueta' => 'Numero', 'id' => 'num', 'tipo' => 'numero');
		$botones[] = array('etiqueta' => 'Siguiente > ', 'tipo' => 'submit', 'clase' => 'icon8','id'=>'btnGuardar');
		
		$esquema -> etiqueta = 'OID';
		$esquema -> id = 'oid';
		$esquema -> tipo = 'texto';
		$esquema -> clave = 'index';
		$esquema -> propiedades = array('oculto');
		$esquema -> Asignar_Campo();
		
		$esquema -> etiqueta = 'OID';
		$esquema -> id = 'oidu';
		$esquema -> tipo = 'texto';
		$esquema -> clave = 'index';
		$esquema -> propiedades = array('oculto');
		$esquema -> Asignar_Campo();
		
		$esquema -> etiqueta = 'Seudonimo';
		$esquema -> id = 'seudonimo';
		$esquema -> tipo = 'texto';
		$esquema -> clave = 'primary';
		$esquema -> accion = 'consulta';
		$esquema -> estilo = 'width:100%;';
		//$esquema -> propiedades = array('requerido');
		$esquema -> completar = "c_usuario";
		$esquema -> Asignar_Campo();
		
		$esquema -> etiqueta = 'Cedula';
		$esquema -> id = 'documento_id';
		$esquema -> tipo = 'texto';
		$esquema -> clave = 'primary';
		$esquema -> estilo = 'width:100%;';
		//$esquema -> accion = 'consulta';
		$esquema -> propiedades = array('requerido');
		//$esquema -> completar = "c_usuario";
		$esquema -> Asignar_Campo();
		
		$esquema -> etiqueta = 'Nombre';
		$esquema -> id = 'descripcion';
		$esquema -> estilo = 'width:100%;';
		$esquema -> tipo = 'texto';
		$esquema -> propiedades = array('requerido');
		$esquema -> Asignar_Campo();
		
		$esquema -> etiqueta = 'Clave';
		$esquema -> id = 'clave';
		$esquema -> tipo = 'texto';
		$esquema -> estilo = 'width:100%;';
		$esquema -> Asignar_Campo();
		
		$esquema -> etiqueta = 'Correo';
		$esquema -> id = 'correo';
		$esquema -> tipo = 'textarea';
		$esquema -> Asignar_Campo();
		
		$esquema -> etiqueta = 'Fecha';
		$esquema -> id = 'fecha';
		$esquema -> tipo = 'calendario';
		$esquema -> estilo = 'width:100%;';
		$esquema -> Asignar_Campo();
		
		$esquema -> etiqueta = 'Estatus';
		$esquema -> id = 'estatus';
		$esquema -> tipo = 'combo';
		$esquema -> propiedades = array('requerido');
		$esquema -> elementos = $estatus;
		$esquema -> Asignar_Campo();
		
		$esquema -> etiqueta = 'Estatus2';
		$esquema -> id = 'conectado';
		$esquema -> tipo = 'combo';
		$esquema -> propiedades = array('requerido');
		$esquema -> elementos = $estatus2;
		$esquema -> Asignar_Campo();
		
		$esquema -> etiqueta = 'Perfil';
		$esquema -> id = 'oidp';
		$esquema -> tipo = 'combo';
		$esquema -> propiedades = array('requerido');
		$esquema -> elementos = $perfil;
		$esquema -> Asignar_Campo();
		
		$esquema -> etiqueta = 'Ubica';
		$esquema -> id = 'oidb';
		$esquema -> tipo = 'combo';
		$esquema -> propiedades = array('requerido');
		$esquema -> elementos = $ubica;
		$esquema -> Asignar_Campo();
		
		/*$esquema -> etiqueta = 'Numero';
		$esquema -> id = 'numero';
		$esquema -> tipo = 'numero';
		$esquema -> Asignar_Campo();*/
		
		$esquema -> etiqueta = 'Guardar';
		$esquema -> id = 'btnGuardar';
		$esquema -> tipo = 'submit';
		$esquema -> clase = 'icon8';
		$esquema -> Asignar_Boton();
		
		/*$esquema -> etiqueta = 'Ciudad';
		$esquema -> id = 'ciu';
		$esquema -> tipo = 'texto';
		//$esquema -> propiedades = array('requerido');
		$esquema -> completar = "c_ciu";
		$esquema -> onblur = 'busca_ciu()';
		$esquema -> Asignar_Campo();
		
		$esquema -> etiqueta = 'Estado';
		$esquema -> id = 'estado';
		$esquema -> tipo = 'combo';
		$esquema -> elementos = $arr_estados;
		$esquema -> dependiente = array("muni","busca_muni");
		$esquema -> Asignar_Campo();
		
		$esquema -> etiqueta = 'Municipio';
		$esquema -> id = 'muni';
		$esquema -> tipo = 'combo';
		$esquema -> dependiente = array("parro","busca_parro");
		$esquema -> elementos = array("" => "SELECCIONE MUNICIPIO");
		$esquema -> Asignar_Campo();
		
		$esquema -> etiqueta = 'Parroquia';
		$esquema -> id = 'parro';
		$esquema -> tipo = 'combo';
		$esquema -> elementos = array("" => "SELECCIONE PARROQUIA");
		$esquema -> Asignar_Campo();*/
		
		//$esquema -> Agregar_Campos($formulario);
		//$esquema -> Agregar_Botones($botones);
		$esquema -> sTitulo = 'Crear o Modificar Usuario';
        $esquema -> sGuardar = 'Guarda_Usuario';
		$esquema -> sConsulta = 'Consulta_Usuario';
		//$esquema -> Ver();
		return $esquema -> Generar();
		//$objeto = array('campos' => $formulario, 'botones' => $botones,'fconsulta' => "Consulta_Usuario","fguardar" => "Guarda_Usuario");
		//return json_encode($objeto);
	}

	function Crea_Ubicacion(){
		$this -> load -> library('gvista/GPesquema');
		$esquema = new GPesquema();
		
		$esquema -> etiqueta = 'OID';
		$esquema -> id = 'oid';
		$esquema -> tipo = 'texto';
		$esquema -> clave = 'index';
		$esquema -> propiedades = array('oculto');
		$esquema -> Asignar_Campo();
		
		$esquema -> etiqueta = 'Alias';
		$esquema -> id = 'nombre';
		$esquema -> tipo = 'texto';
		$esquema -> estilo = 'width:100%;';
		$esquema -> propiedades = array('requerido');
		$esquema -> completar = "c_usuario";
		$esquema -> Asignar_Campo();
		
		$esquema -> etiqueta = 'Nombre';
		$esquema -> id = 'descripcion';
		$esquema -> tipo = 'texto';
		$esquema -> estilo = 'width:150%;';
		$esquema -> propiedades = array('requerido');
		$esquema -> Asignar_Campo();
		
		$esquema -> etiqueta = 'Direccion';
		$esquema -> id = 'direccion';
		$esquema -> tipo = 'textarea';
		$esquema -> estilo = 'width:100%;';
		$esquema -> propiedades = array('requerido');
		$esquema -> Asignar_Campo();
		
		$esquema -> etiqueta = 'Sucursal';
		$esquema -> id = 'sucursal';
		$esquema -> estilo = 'width:100%;';
		$esquema -> tipo = 'texto';
		$esquema -> propiedades = array('requerido');
		$esquema -> Asignar_Campo();
		
		$esquema -> etiqueta = 'Guardar';
		$esquema -> id = 'btnGuardar';
		$esquema -> tipo = 'submit';
		$esquema -> clase = 'icon8';
		$esquema -> Asignar_Boton();
		
		$esquema -> sTitulo = 'Crear Ubicacion';
        $esquema -> sGuardar = 'Guarda_Ubicacion';
		//$esquema -> Ver();
		return $esquema -> Generar();
	}
	
	function Crea_Usuario2(){
		$cad = '{"campos":[{"id":"PE_oid","tipo":"texto","etiqueta":"Identificador","oculto":1,"accion":"","clave":"index"},
					{"id":"PE_naci","tipo":"combo","etiqueta":"Identificacion del Genero","elementos":{"V":"Venezolano","E":"Extranjero","DC":"Documentos Especiales"},"accion":""},
					{"id":"PE_cedu","tipo":"texto","etiqueta":"Cedula de Identidad","accion":"consulta","clave":"index"},
					{"id":"PE_pnom","tipo":"texto","etiqueta":"Primer Nombre","accion":""},
					{"id":"PE_snom","tipo":"texto","etiqueta":"Segundo Nombre","accion":""},
					{"id":"PE_pape","tipo":"texto","etiqueta":"Primer Apellido","accion":""},
					{"id":"PE_sape","tipo":"texto","etiqueta":"Segundo Apellido","accion":""},
					{"id":"PE_sexo","tipo":"combo","etiqueta":"Identificacion del Genero","elementos":["Masculino","Femenino"],"accion":""},
					{"id":"PE_fnac","tipo":"calendario","etiqueta":"Fecha Nacimiento","accion":""},
					{"id":"PE_dir","tipo":"textarea","etiqueta":"Direccion","accion":""},
					{"id":"PE_telf","tipo":"texto","etiqueta":"Telefono","accion":""},
					{"id":"EM_oidFKP","tipo":"texto","etiqueta":"Identificador Persona","oculto":1,"accion":"","clave":"index"},
					{"id":"EM_prof","tipo":"texto","etiqueta":"Profesion","accion":""},
					{"id":"EM_fing","tipo":"calendario","etiqueta":"Fecha Ingreso","accion":""},
					{"id":"RDE_oid","tipo":"texto","etiqueta":"Identificador del Empleado","oculto":1,"accion":"","clave":"index"},
					{"id":"RDE_oidFKD","tipo":"combo","etiqueta":"Identificador del Departamento","elementos":{"1":"Informatica"},"accion":"","clave":"index"},
					{"id":"RDE_nume","tipo":"texto","etiqueta":"Cuenta Bancaria","accion":"","clave":"index"},
					{"id":"RDE_esta","tipo":"texto","etiqueta":"0: Activa 1: Inactiva","accion":""},
					{"id":"RDE_tipo","tipo":"texto","etiqueta":"0: Asignacion 1: Deducion","accion":""},
					{"id":"RCA_oid","tipo":"texto","etiqueta":"Identificador del Empleado","oculto":1,"accion":"","clave":"index"},
					{"id":"RCA_oidFKC","tipo":"combo","etiqueta":"Identificador del Cargo","elementos":{"1":"Programador"},"accion":"","clave":"index"},
					{"id":"RCA_nume","tipo":"texto","etiqueta":"Cuenta Bancaria","accion":"","clave":"index"},
					{"id":"RCA_esta","tipo":"texto","etiqueta":"0: Activa 1: Inactiva","accion":""},
					{"id":"RBC_oid","tipo":"texto","etiqueta":"Identificador del Empleado","oculto":1,"accion":"","clave":"index"},
					{"id":"RBC_oidFKB","tipo":"combo","etiqueta":"Identificador del Banco","elementos":{"1":"Bicentenario"},"accion":"","clave":"index"},
					{"id":"RBC_nume","tipo":"texto","etiqueta":"Cuenta Bancaria","accion":""},{"id":"RBC_esta","tipo":"texto","etiqueta":"0: Activa 1: Inactiva","accion":""},
					{"id":"RBC_tipo","tipo":"texto","etiqueta":"0: Asignacion 1: Deducion","accion":""}],
					"botones":{"1":{"etiqueta":"Guardar","metodo":1,"tipo":"submit"}},"fconsulta":"Buscar","fguardar":"SalvarOActualizar"}';
		
		$cad2= '{"campos":[{"id":"oid","tipo":"texto","clave":"index","etiqueta":"OID","oculto":true},
							{"id":"documento_id","tipo":"texto","clave":"primary","etiqueta":"Cedula","accion":"consulta","requerido":true},
							{"id":"descripcion","tipo":"texto","etiqueta":"Nombre","requerido":true},
							{"id":"seudonimo","tipo":"texto","etiqueta":"Seudonimo","requerido":true},
							{"id":"clave","tipo":"texto","etiqueta":"Clave","requerido":true},
							{"id":"correo","tipo":"textarea","etiqueta":"Correo","requerido":true},
							{"id":"fecha","tipo":"calendario","etiqueta":"Fecha","requerido":true},
							{"id":"estatus","tipo":"combo","etiqueta":"Estatus","elementos":{"":"SELECCIONE","0":"Inactivo","1":"Activo"},"requerido":true},
							{"id":"estatus2","tipo":"combo","etiqueta":"Estatus2","elementos":{"":"SELECCIONE","0":"Inactivo","1":"Activo"},"requerido":true},{"id":"numero","tipo":"numero","etiqueta":"Numero","requerido":true}],
				"botones":[{"id":"btnGuardar","tipo":"submit","clase":"icon8","etiqueta":"Guardar","requerido":true}],"fconsulta":"Consulta_Usuario","fguardar":"Guarda_Usuario"}';
		$cad3 ='{"campos":[{"etiqueta":"OID","id":"oid","tipo":"texto","oculto":1,"clave":"index"},
							{"etiqueta":"Cedula","id":"documento_id","tipo":"texto","requerido":1,"accion":"consulta","clave":"primary"},
							{"etiqueta":"Nombre","id":"descripcion","tipo":"texto","requerido":1},
							{"etiqueta":"Seudonimo","id":"seudonimo","tipo":"texto"},{"etiqueta":"Clave","id":"clave","tipo":"texto"},
							{"etiqueta":"Correo","id":"correo","tipo":"textarea"},
							{"etiqueta":"Fecha","id":"fecha","tipo":"calendario"},
							{"etiqueta":"Estatus","id":"estatus","tipo":"combo","elementos":{"":"SELECCIONE","0":"Inactivo","1":"Activo"}},
							{"etiqueta":"Numero","id":"num","tipo":"numero"}],
							"botones":[[{"etiqueta":"Siguiente > ","tipo":"submit","clase":"icon8","id":"btnGuardar"}]],"fconsulta":"Consulta_Usuario","fguardar":"Guarda_Usuario"}';
		return $cad;
	}
	//gvista2
	function Crea_Solicitud(){
		$valores2 = array('0' => 'Banco');
		$banco = $this -> db -> query("SELECT cobrado_en FROM t_clientes_creditos GROUP BY cobrado_en");
		foreach ($banco -> result() as $ban) {
			$valores2[$ban -> cobrado_en] = $ban -> cobrado_en;
		}
		$valores3 = array('0' => 'Nomina');
		$nomina = $this -> db -> query("SELECT * FROM t_nominas order by nombre");
		foreach ($nomina -> result() as $nom) {
			$valores3[$nom -> nombre] = $nom -> nombre;
		}
						
		$formulario[1] = array('etiqueta' => 'Cedula|Rif', 'id' => 'cedula', 'tipo' => 'texto', 'requerido' => 1, 'onblur' => 'consulta_cliente()','clave'=>'index');
		$formulario[2] = array('etiqueta' => 'Codigo', 'id' => 'codigo', 'tipo' => 'texto', 'desabilitado' => 1,'requerido' => 1);
		$formulario[3] = array('etiqueta' => 'Nombre', 'id' => 'nombre', 'tipo' => 'texto', 'estilo' => 'width:100%;', 'desabilitado' => 1,'multicelda' => 1 );
		$formulario[4] = array('etiqueta' => 'Banco', 'id' => 'banco', 'tipo' => 'combo', 'elementos' => $valores2,'multicelda' => 1);
		$formulario[5] = array('etiqueta' => 'Nomina', 'id' => 'nomina', 'tipo' => 'combo', 'elementos' => $valores3,'multicelda' => 1 );
		$formulario[6] = array('etiqueta' => 'Solicita', 'id' => 'solicitud', 'tipo' => 'textarea', 'estilo' => 'width:100%;', 'multicelda' => 1,'requerido' => 1,);
		

		$btn[1] = array('etiqueta' => 'Guardar', 'metodo' => 1, 'tipo' => 'submit' ,'id'=>'btng');
		$objeto = array('campos' => $formulario, 'botones' => $btn ,'fguardar'=> 'GuardaSoli','titulo'=>'Registro de Solicitud');
		return json_encode($objeto);
	}
	
	function Filtro_Solicitud(){
		$valores2 = array('0' => 'Todos los Bancos');
		$banco = $this -> db -> query("SELECT cobrado_en FROM t_clientes_creditos GROUP BY cobrado_en");
		foreach ($banco -> result() as $ban) {
			$valores2[$ban -> cobrado_en] = $ban -> cobrado_en;
		}
		$valores3 = array('0' => 'Todas Las Nominas');
		$nomina = $this -> db -> query("SELECT * FROM t_nominas order by nombre");
		foreach ($nomina -> result() as $nom) {
			$valores3[$nom -> nombre] = $nom -> nombre;
		}
		$estatus=array('0'=>'Carga Inicial','1'=>'Revisando','2'=>'Aceptados','3' => 'Rechazados');
				
		$formulario[1] = array('etiqueta' => 'Banco', 'id' => 'fbanco', 'tipo' => 'combo', 'elementos' => $valores2,'multicelda' => 1);
		$formulario[2] = array('etiqueta' => 'Nomina', 'id' => 'fnomina', 'tipo' => 'combo', 'elementos' => $valores3,'multicelda' => 1 );
		$formulario[3] = array('etiqueta' => 'Desde', 'id' => 'desde_sol', 'tipo' => 'texto' ,'requerido' => 1);
		$formulario[4] = array('etiqueta' => 'Hasta', 'id' => 'hasta_sol', 'tipo' => 'texto' ,'requerido' => 1);
		$formulario[5] = array('etiqueta' => 'Estatus', 'id' => 'estatus_sol', 'tipo' => 'combo', 'elementos' => $estatus,'multicelda' => 1 );

		$objeto = array('campos' => $formulario ,'titulo'=>'Filtro de Solicitud');
		return json_encode($objeto);
	}
	
	function Filtro_Solicitud2(){
		$ubicacion = $this -> db -> query("SELECT descripcion,soli FROM t_ubicacion where soli!=''");
		$ubi = $ubicacion -> result();
		foreach ($ubi as $nom) {
			$valores[$nom -> descripcion] = $nom -> descripcion;
		}
		$valores['Todos'] = 'Todos';
		$valores2 = array('0' => 'Todos los Bancos');
		$banco = $this -> db -> query("SELECT cobrado_en FROM t_clientes_creditos GROUP BY cobrado_en");
		foreach ($banco -> result() as $ban) {
			$valores2[$ban -> cobrado_en] = $ban -> cobrado_en;
		}
		$valores3 = array('0' => 'Todas Las Nominas');
		$nomina = $this -> db -> query("SELECT * FROM t_nominas order by nombre");
		foreach ($nomina -> result() as $nom) {
			$valores3[$nom -> nombre] = $nom -> nombre;
		}
		$estatus=array('0'=>'Carga Inicial','1'=>'Revisando','2'=>'Aceptados','3' => 'Rechazados');
				
		$formulario[1] = array('etiqueta' => 'Banco', 'id' => 'fbanco', 'tipo' => 'combo', 'elementos' => $valores2,'multicelda' => 1);
		$formulario[2] = array('etiqueta' => 'Nomina', 'id' => 'fnomina', 'tipo' => 'combo', 'elementos' => $valores3,'multicelda' => 1 );
		$formulario[3] = array('etiqueta' => 'Desde', 'id' => 'desde', 'tipo' => 'texto' ,'requerido' => 1);
		$formulario[4] = array('etiqueta' => 'Hasta', 'id' => 'hasta', 'tipo' => 'texto' ,'requerido' => 1);
		$formulario[5] = array('etiqueta' => 'Estatus', 'id' => 'estatus', 'tipo' => 'combo', 'elementos' => $estatus,'multicelda' => 1 );
		$formulario[6] = array('etiqueta' => 'Ubicacion', 'id' => 'cubica', 'tipo' => 'combo', 'elementos' => $valores,'multicelda' => 1 );
		$formulario[7] = array('etiqueta' => 'Buscar', 'metodo' => 1, 'tipo' => 'boton' ,'id'=>'btnb', 'onclick'=>'buscar();');

		$objeto = array('campos' => $formulario ,'titulo'=>'Filtro de Solicitud');
		return json_encode($objeto);
	}
	
	function Busca_Img_Sol(){
		$valores = array();
		$ubicacion = $this -> db -> query("SELECT descripcion,soli FROM t_ubicacion where soli!=''");
		$ubi = $ubicacion -> result();
		foreach ($ubi as $nom) {
			$valores[$nom -> soli] = $nom -> descripcion;
		}
				
		$formulario[1] = array('etiqueta' => 'Cedula', 'id' => 'ced', 'tipo' => 'texto','requerido' => 1);
		$formulario[2] = array('etiqueta' => 'Codigo', 'id' => 'cod', 'tipo' => 'texto' ,'requerido' => 1);
		$formulario[3] = array('etiqueta' => 'Ubicacion', 'id' => 'ubic', 'tipo' => 'combo', 'elementos' => $valores);
		$formulario[4] = array('etiqueta' => 'Buscar', 'metodo' => 1, 'tipo' => 'boton' ,'id'=>'btnb', 'onclick'=>'consultar_imagenes();');

		$objeto = array('campos' => $formulario ,'titulo'=>'Buscar Imagenes de Solicitud');
		return json_encode($objeto);
	}
	
	/*
	 * Generar Filto de busquedas para reportes generales
	 */
	function filtro_general_factura(){
		
		$valores2 = array('0' => 'Todos los Bancos');
		$banco = $this -> db -> query("SELECT cobrado_en FROM t_clientes_creditos GROUP BY cobrado_en");
		foreach ($banco -> result() as $ban) {
			$valores2[$ban -> cobrado_en] = $ban -> cobrado_en;
		}
		
		$estatus=array('0'=>'TODOS','1'=>'SIN PAGOS');
		$condicion=array('0'=>'TODOS','1'=>'EN COBRANZA','2'=>'POR INICIAR COBRO');
		$formulario[1] = array('etiqueta' => 'Banco','label'=>"Banco", 'id' => 'fbanco', 'tipo' => 'combo', 'elementos' => $valores2,'multicelda' => 1,"atributos"=>"width:50%;");
		$formulario[2] = array('etiqueta' => 'Estatus','label'=>'Estatus', 'id' => 'festatus', 'tipo' => 'combo', 'elementos' => $estatus,'multicelda' => 1 );
		$formulario[3] = array('etiqueta' => 'Condicion','label'=>'Condicion', 'id' => 'fcond', 'tipo' => 'combo' , 'elementos' => $condicion,'multicelda' => 1 );
		
		$objeto = array('campos' => $formulario ,'titulo'=>'Filtro General De Facturas');
		return json_encode($objeto);
	}
	
	function filtro_general_contrato(){
	
		$tipo_c = array('0' => 'Unico','1'=>'Aguinaldo','2'=>'Vacaciones','6'=>'Pronto Pago','3'=>'Cuota Extraordinaria','4'=>'Unico Extra','5'=>'Especial Extra');
		
		
		$banco = $this -> db -> query("SELECT cobrado_en FROM t_clientes_creditos GROUP BY cobrado_en");
		$valores2 = array('0' => 'Todos los Bancos');
		foreach ($banco -> result() as $ban) {
			$valores2[$ban -> cobrado_en] = $ban -> cobrado_en;
		}
		$peri = array(""=>"SELECCIONE","4"=>"MENSUAL","9"=>"BONO EXPRESS","0"=>"SEMANAL","1"=>"CATORCENAL","2"=>"QUINCENAL 15-30","3"=>"QUINCENAL 10-25","5"=>"TRIMESTRAL","6"=>"SEMESTRAL","8"=>"MENSUAL 10","9"=>"MESUAL 25");
		$condicion=array('0'=>'TODOS','1'=>'EN COBRANZA','2'=>'POR INICIAR COBRO');
		$empresa = array('3'=>'Todas','0'=>'Cooperativa','1'=>'Grupo');
		$formulario[1] = array('etiqueta' => 'Banco','label'=>"Banco", 'id' => 'cbanco', 'tipo' => 'combo', 'elementos' => $valores2,'multicelda' => 1,"atributos"=>"width:50%;");
		$formulario[2] = array('etiqueta' => 'Tipo Contrato','label'=>"Tipo Contrato", 'id' => 'ctipo', 'tipo' => 'combo', 'elementos' => $tipo_c,'multicelda' => 1,"atributos"=>"width:50%;");
		$formulario[3] = array('etiqueta' => 'Empresa','label'=>'Empresa', 'id' => 'cempresa', 'tipo' => 'combo', 'elementos' => $empresa,'multicelda' => 1 );
		$formulario[4] = array('etiqueta' => 'Condicion','label'=>'Condicion', 'id' => 'ccond', 'tipo' => 'combo' , 'elementos' => $condicion,'multicelda' => 1 );
		$formulario[5] = array('etiqueta' => 'Periodicidad','label'=>'Periodicidad', 'id' => 'cperi', 'tipo' => 'combo' , 'elementos' => $peri,'multicelda' => 1 );
	
		$objeto = array('campos' => $formulario ,'titulo'=>'Filtro General De Facturas');
		return json_encode($objeto);
	}

	public function archivosAuditoria() {
    	$this -> load -> library('gvista/GPesquema');
		$esquema = new GPesquema();
		
		$valores = array();
		$banco = $this -> db -> query("SELECT nombre FROM t_linaje");
		$ban = $banco -> result();
		foreach ($ban as $b) {
			$valores[$b -> nombre] = $b -> nombre;
		}
        
		
		$esquema -> etiqueta = 'OID';
		$esquema -> id = 'oid';
		$esquema -> tipo = 'texto';
		$esquema -> clave = 'index';
		$esquema -> propiedades = array('oculto');
		$esquema -> Asignar_Campo();
		
		
		$esquema -> etiqueta = 'Descripción';
		$esquema -> id = 'descripcion';
		$esquema -> tipo = 'texto';
		$esquema -> estilo = 'width:100%;';
		$esquema -> Asignar_Campo();
		
		$esquema -> etiqueta = 'Banco';
		$esquema -> id = 'banco';
		$esquema -> tipo = 'combo';
		$esquema -> elementos = $valores;
		$esquema -> estilo = 'width:100%;';
		$esquema -> propiedades = array('requerido');
		$esquema -> Asignar_Campo();
		
		$esquema -> etiqueta = 'Fecha Recepción';
		$esquema -> id = 'fecha_recepcion';
		$esquema -> estilo = 'width:100%;';
		$esquema -> tipo = 'calendario';
		$esquema -> Asignar_Campo();
		
		$esquema -> etiqueta = 'Monto Enviado';
		$esquema -> id = 'monto_enviado';
		$esquema -> tipo = 'texto';
		$esquema -> estilo = 'width:100%;';
		$esquema -> Asignar_Campo();
		
		$esquema -> etiqueta = 'Monto Revision';
		$esquema -> id = 'monto_revision';
		$esquema -> tipo = 'texto';
		$esquema -> Asignar_Campo();
		
		$esquema -> etiqueta = 'Fecha Archivo';
		$esquema -> id = 'fecha_archivo';
		$esquema -> tipo = 'calendario';
		$esquema -> estilo = 'width:100%;';
		$esquema -> Asignar_Campo();
		
		$esquema -> etiqueta = 'Tiempo Dias';
		$esquema -> id = 'tiempo_dias';
		$esquema -> tipo = 'texto';
		$esquema -> Asignar_Campo();
		
		$esquema -> etiqueta = 'Guardar';
		$esquema -> id = 'btnGuardar';
		$esquema -> tipo = 'submit';
		$esquema -> clase = 'icon8';
		$esquema -> Asignar_Boton();
		
		
		$esquema -> sTitulo = 'Crear descripcion de archivo';
        $esquema -> sGuardar = 'Guarda_Descripcion_Auditoria';
		$esquema -> sConsulta = 'Consulta_Usuario';
		return $esquema -> Generar();
	}

    public function recepcionDocu() {
        $this -> load -> library('gvista/GPesquema');
        $esquema = new GPesquema();

        $docu = array("Seleccione" => "Seleccione","Cedula"=>"Cedula","Rif"=>"Rif","Constancia de Trabajo"=>"Constancia de Trabajo"
                ,"Recibo de Pago"=>"Recibo de Pago","Estados de Cuenta"=>"Estados de Cuenta","Libreta Bancaria"=>"Libreta Bancaria"
                ,"Cheque"=>"Cheque","Letra De Cambio"=>"Letra De Cambio","Contrato de Servicio Firmado"=>"Contrato de Servicio Firmado"
                ,"Contrato de Domiciliacion"=>"Contrato de Domiciliacion","Autorizacion De Descuento Por Nomina"=>"Autorizacion De Descuento Por Nomina","Factura Presupuesto"=>"Factura Presupuesto");
        $tipo = array("2"=>"POR SOLICITUD","1"=>"LIQUIDADOS","0"=>"TIPO DE DOCUMENTACION");

        $esquema -> etiqueta = 'OID';
        $esquema -> id = 'oid';
        $esquema -> tipo = 'texto';
        $esquema -> clave = 'index';
        $esquema -> propiedades = array('oculto');
        $esquema -> Asignar_Campo();


        $esquema -> etiqueta = 'Quien Envia';
        $esquema -> id = 'envia';
        $esquema -> tipo = 'texto';
        $esquema -> estilo = 'width:100%;';
        $esquema -> propiedades = array('requerido');
        $esquema -> Asignar_Campo();

        $esquema -> etiqueta = 'Quien Recibe';
        $esquema -> id = 'recibe';
        $esquema -> tipo = 'texto';
        $esquema -> estilo = 'width:100%;';
        $esquema -> propiedades = array('requerido');
        $esquema -> Asignar_Campo();

        $esquema -> etiqueta = 'Cedula';
        $esquema -> id = 'cedula';
        $esquema -> tipo = 'texto';
        $esquema -> estilo = 'width:100%;';
        $esquema -> propiedades = array('requerido');
        $esquema -> Asignar_Campo();

        $esquema -> etiqueta = 'Nombre y Apellido';
        $esquema -> id = 'nombre';
        $esquema -> tipo = 'texto';
        $esquema -> estilo = 'width:100%;';
        $esquema -> propiedades = array('requerido');
        $esquema -> Asignar_Campo();

        $esquema -> etiqueta = 'Fecha';
        $esquema -> id = 'fecha';
        $esquema -> tipo = 'calendario';
        $esquema -> estilo = 'width:100%;';
        $esquema -> propiedades = array('requerido');
        $esquema -> Asignar_Campo();

        $esquema -> etiqueta = 'Hora';
        $esquema -> id = 'hora';
        $esquema -> tipo = 'texto';
        $esquema -> estilo = 'width:100%;';
        $esquema -> propiedades = array('requerido');
        $esquema -> Asignar_Campo();

        $esquema -> label ="Observaciones";
        $esquema -> id = 'observacion';
        $esquema -> tipo = 'textarea';
        $esquema -> estilo = 'width:100%;';
        $esquema -> Asignar_Campo();

        $esquema -> etiqueta = 'Tipo Documentacion';
        $esquema -> id = 'tipo';
        $esquema -> tipo = 'combo';
        $esquema -> elementos = $tipo;
        $esquema -> estilo = 'width:100%;';
        $esquema -> Asignar_Campo();

        $esquema -> etiqueta = 'Documentos';
        $esquema -> id = 'docu';
        $esquema -> tipo = 'combo';
        $esquema -> elementos = $docu;
        $esquema -> estilo = 'width:100%;';
        $esquema -> onchange = 'agregarDocu();aling:top;';
        $esquema -> Asignar_Campo();

        $esquema -> etiqueta = 'Documentos';
        $esquema -> id = 'docu2';
        $esquema -> tipo = 'lista';
        $esquema -> elementos = $docu;
        $esquema -> estilo = 'width:100%;height: 120px;overflow: auto;';
        $esquema -> ondblclick = "quitarDocu();";
        $esquema -> Asignar_Campo();

        $esquema -> etiqueta = 'Guardar';
        $esquema -> id = 'btnGuardar';
        $esquema -> tipo = 'submit';
        $esquema -> clase = 'icon8';
        $esquema -> Asignar_Boton();


        $esquema -> sTitulo = 'Registrar Documentos';
        $esquema -> sGuardar = 'guardaRecepcionDocu';
        return $esquema -> Generar();
    }

    function panelPlan(){
        $this -> load -> library('gvista/GPesquema');
        $esquema = new GPesquema();

        $esquema -> etiqueta = 'Plan';
        $esquema -> id = 'plan';
        $esquema -> tipo = 'texto';
        $esquema -> estilo = 'width:100%;';
        $esquema -> propiedades = array('requerido');
        $esquema -> Asignar_Campo();

        $esquema -> etiqueta = 'Porcentaje';
        $esquema -> id = 'porcentaje';
        $esquema -> tipo = 'texto';
        $esquema -> estilo = 'width:100%;';
        $esquema -> propiedades = array('requerido');
        $esquema -> Asignar_Campo();

        $esquema -> etiqueta = 'Guardar';
        $esquema -> id = 'btnGuardar';
        $esquema -> tipo = 'submit';
        $esquema -> clase = 'icon8';
        $esquema -> Asignar_Boton();


        $esquema -> sTitulo = 'Registrar Documentos';
        $esquema -> sGuardar = 'guardaPlan';
        return $esquema -> Generar();

    }
	
}
?>