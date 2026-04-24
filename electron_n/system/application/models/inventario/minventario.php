<?php
/**
 *  @author Carlos Enrique Penaa Albarran
 *  @package SaGem.system.application.model.cinventario
 *  @version 1.0.0
 */
class MInventario extends Model {

	/**
	 * Lista Proveedores NULL
	 * @var integer
	 */
	var $oid = NULL;
	var $codigo;
	var $descripcion;
	var $modelo;
	var $marca;
	var $cantidad;
	var $proveedor;
	var $almacen;
	var $precioc;
	var $preciov;
	var $serial;
	var $tipo;

	function Generar_Codigo() {

		$sCod = $this -> Completar(rand(), 7);
		$sConsulta = "SELECT codigo FROM t_semillero WHERE codigo='" . $sCod . "' LIMIT 1";
		$rsC = $this -> db -> query($sConsulta);
		if ($rsC -> num_rows() != 0) {
			$sCod = $this -> Completar(rand(), 7);
			$this -> Generar_Codigo($sCod);
		}
		return $sCod;
	}

	/**
	 * Completar con ceros a la izquierda...
	 *
	 */
	private function Completar($strCadena = '', $intLongitud = '') {
		$strContenido = '';
		$strAux = '';
		$intLen = strlen($strCadena);
		if ($intLen != $intLongitud) {
			$intCount = $intLongitud - $intLen;
			for ($i = 0; $i < $intCount; $i++) {
				$strAux .= '0';
			}
			$strContenido = $strAux . $strCadena;
		}
		return $strContenido;
	}

	function Buscar($sCod, $sDes) {
		$sConsulta = "SELECT * FROM t_mercancia WHERE codigo='" . $sCod . "' OR descripcion LIKE '%" . $sDes . "%' LIMIT 1";
		$rs = $this -> db -> query($sConsulta);
		$rsC = $rs -> result();
		if ($rs -> num_rows() != 0) {
			foreach ($rsC as $row) {
				foreach ($row as $sC => $sV) {
					$lst[$sC] = $sV;
				}
			}
		}
		$obj['php'] = $lst;
		$obj['json'] = json_encode($lst);
		return $obj;
	}

	function Listar($sDes, $tipo) {
		$sConsulta = "SELECT * FROM t_mercancia WHERE (descripcion LIKE '%" . $sDes . "%' || codigo like '" . $sDes . "%') AND tipo=$tipo";
		$rs = $this -> db -> query($sConsulta);
		$rsC = $rs -> result();
		if ($rs -> num_rows() != 0) {
			foreach ($rsC as $row) {
				$lst[$row -> codigo] = $row -> descripcion;
			}
		}
		return $lst;
	}

	function Consultar($sDes, $ubi) {
		$sConsulta = "SELECT * FROM t_mercancia WHERE descripcion='" . $sDes . "' ";
		$jsP = array();
		$rs = $this -> db -> query($sConsulta);
		$rsC = $rs -> result();
		$codigo = '';
		if ($rs -> num_rows() != 0) {
			foreach ($rsC as $row) {
				$codigo = $row -> codigo;
				foreach ($row as $NombreCampo => $ValorCampo) {
					$jsP[$NombreCampo] = $ValorCampo;
				}
			}
		}
		if ($ubi != '') {
			$lugar = $this -> Oculta_Ubicacion($ubi);

			$lstSeriales = "SELECT * FROM t_lista_mercancia WHERE estatus<2 AND oidc='" . $codigo . "' AND ubicacion=" . $lugar;
			$rsSeriales = $this -> db -> query($lstSeriales);
			$lista_seriales = array();
			foreach ($rsSeriales -> result() as $seriales) {
				$lista_seriales[] = $seriales -> serial_item . " | " . $this -> Muestra_Ubicacion($seriales -> ubicacion) . " | " . $this -> Muestra_Estatus($seriales -> estatus) . " | " . $seriales -> preciov;
			}
			$jsP['seriales'] = $lista_seriales;
		}
		return json_encode($jsP);
	}

	function Consultar_Inventario($sDes, $ubi) {
		$sConsulta = "select t_inventario.inventario_id,t_proveedores.nombre as nom_prov,t_artefactos.nombre as nom_art,marca,modelo,precio_venta,detalle,
		IFNULL(a.cantidad_a,0)as cantidad_a,
		IFNULL(b.cantidad_b,0)as cantidad_b,
		IFNULL(c.cantidad_c,0)as cantidad_c,
		IFNULL(d.cantidad_d,0)as cantidad_d
		from t_inventario
		join t_proveedores on t_proveedores.proveedor_id = t_inventario.proveedor
		join t_artefactos on t_artefactos.artefacto_id= t_inventario.artefacto
		left join (SELECT count(inventario_id) as cantidad_a , inventario_id,estatus_mercancia
			FROM t_productos 
			WHERE version=1 group by inventario_id)
			as a on a.inventario_id = t_inventario.inventario_id
		left join (SELECT count(inventario_id) as cantidad_b , inventario_id,estatus_mercancia
			FROM t_productos 
			WHERE version=1 and estatus_mercancia = 0 group by inventario_id)
			as b on b.inventario_id = t_inventario.inventario_id
		left join (SELECT count(inventario_id) as cantidad_c , inventario_id,estatus_mercancia
			FROM t_productos 
			WHERE version=1 and estatus_mercancia = 1 group by inventario_id)
			as c on c.inventario_id = t_inventario.inventario_id
		left join (SELECT count(inventario_id) as cantidad_d , inventario_id,estatus_mercancia
			FROM t_productos 
			WHERE version=1 and estatus_mercancia = 2 group by inventario_id)
			as d on d.inventario_id = t_inventario.inventario_id	
		
		 WHERE modelo='" . $sDes . "' ";

		if ($ubi != '') {
			$lstSeriales = "SELECT * FROM t_productos WHERE estatus_mercancia = 1 and version = 1 AND ubicacion='" . $ubi . "'";
		} else {
			$lstSeriales = "SELECT * FROM t_productos WHERE estatus_mercancia = 0 and version = 1 ";
		}

		$jsP = array();
		$rs = $this -> db -> query($sConsulta);
		$rsC = $rs -> result();
		$codigo = '';
		if ($rs -> num_rows() != 0) {
			foreach ($rsC as $row) {
				$inventario_id = $row -> inventario_id;
				foreach ($row as $NombreCampo => $ValorCampo) {
					$jsP[$NombreCampo] = $ValorCampo;
				}
			}
			$lstSeriales .= " AND inventario_id='" . $inventario_id . "'";
			$rsSeriales = $this -> db -> query($lstSeriales);
			$lista_seriales = array();
			foreach ($rsSeriales -> result() as $seriales) {
				$lista_seriales[] = $seriales -> serial . ' | ' . $seriales -> descripcion . '/';
			}
			$jsP['seriales'] = $lista_seriales;
		}

		return json_encode($jsP);
	}

	public function Entregar($arr) {
		//fecha
		$fecha_a = explode("/", $arr['fecha']);
		$fecha = $fecha_a[2] . '-' . $fecha_a[1] . '-' . $fecha_a[0];
		//existencia
		$cExistencia = $this -> db -> query("SELECT count(*) as existen FROM t_lista_mercancia WHERE oidc = '" . $arr['codigo'] . "' AND estatus < 2 ");
		$existen = $cExistencia -> row();
		//cantidad a entregar
		$seriales = explode(',', $arr['seriales']);
		$data = array("modelo" => $arr['modelo'], "nombre_mercancia" => $arr['descripcion'], "existente" => $existen -> existen, "entregado" => count($seriales), "entregado_a" => $arr['entregado'], "fecha" => $fecha, "tipo" => $arr['tipo']);
		$this -> db -> trans_start();
		$this -> db -> insert("t_entregas", $data);
		$id = $this -> db -> insert_id();
		foreach ($seriales as $serial) {
			$arreglo = array('estatus' => 2);
			$this -> db -> where("serial_item", trim($serial));
			$this -> db -> where("estatus <", 2);
			$this -> db -> update('t_lista_mercancia', $arreglo);

			$arreglo2 = array('serial' => trim($serial), 'oide' => $id);
			$this -> db -> insert('t_lista_entregas', $arreglo2);

		}
		$this -> db -> trans_complete();

		/*$n_cant = $arr['existen'] - $arr['cantidad'];

		 $data2 = array("cantidad" => $n_cant);
		 $this -> db -> update("t_mercancia",$data2);*/
		return $id;
	}

	public function Entregar_Inventario($arr) {

		$fecha_a = explode("/", $arr['fecha']);
		$fecha = $fecha_a[2] . '-' . $fecha_a[1] . '-' . $fecha_a[0];
		$seriales = explode(',', $arr['seriales']);

		$data = array("oficina" => $arr['oficina'], "telefono" => $arr['tlf'], "chofer" => $arr['chofer'], "placa" => $arr['placa'], "salida" => $arr['salida'], "fecha" => $fecha, "destino" => $arr['destino'], "encargado" => $arr['encargado'], "vehiculo" => $arr['vehiculo'], "orden" => $arr['orden']);
		$this -> db -> trans_start();
		$this -> db -> insert("t_orden_entrega", $data);

		foreach ($seriales as $serial) {
			$serial2 = explode('|', $serial);
			$arreglo = array('estatus_mercancia' => 1, "ubicacion" => $arr['ubica'], "precio_oficina" => $serial2['2']);
			$this -> db -> where("serial", trim($serial2['0']));
			$this -> db -> where("estatus_mercancia ", 0);
			$this -> db -> update('t_productos', $arreglo);

			$arreglo2 = array('descripcion' => trim($serial2[1]), 'serial' => trim($serial2[0]), 'orden' => $arr['orden']);
			$this -> db -> insert('t_lista_ordenentrega', $arreglo2);

		}
		$this -> db -> trans_complete();
		return $arr['orden'];
	}

	public function Entregar_Cliente($arr) {

		//$fecha_a = explode("/", $arr['fecha']);
		//$fecha = $fecha_a[2] . '-' . $fecha_a[1] . '-' . $fecha_a[0];
		$fecha = $arr['fecha'];
		$seriales = explode(',', $arr['seriales']);
		$cant_ser = count($seriales);
		$ubica = $this -> session -> userdata('ubicacion');
		$usu = $this -> session -> userdata('usuario');

		$data = array("modelo" => $arr['modelo'], "descrip" => $arr['descrip'], "entregado" => $cant_ser, "entregado_a" => $arr['cliente'], "tipo" => 0, "fecha" => $fecha, "ubicacion" => $ubica, "cedula" => $arr['cedula'], "factura" => $arr['factura'], "usuario" => $usu, "t_venta" => $arr['t_venta']);
		$this -> db -> trans_start();
		$this -> db -> insert("t_entregas", $data);

		$id = $this -> db -> insert_id();
		foreach ($seriales as $serial) {

			$arreglo = array('estatus_mercancia' => 2, 'estatus' => 2);
			$this -> db -> where("serial", trim($serial));
			$this -> db -> update('t_productos', $arreglo);

			$arreglo2 = array('serial' => trim($serial), 'oide' => $id);
			$this -> db -> insert('t_lista_entregas', $arreglo2);

		}
		$this -> db -> trans_complete();
		return $id;
	}

	function ListarGrid($cod, $tipo) {

		$oFil = array();
		if ($cod == '')
			$sConsulta = 'SELECT * FROM t_mercancia WHERE tipo=' . $tipo;
		else
			$sConsulta = 'SELECT * FROM t_mercancia WHERE codigo ="' . $cod . '" AND tipo =' . $tipo;

		$oCabezera[1] = array("titulo" => "OID", "atributos" => "width:10px", "oculto" => 1);
		$oCabezera[2] = array("titulo" => " ", "tipo" => "detallePost", "atributos" => "width:10px", "funcion" => "MuestraDetalleMercancia", "parametro" => "3");
		$oCabezera[3] = array("titulo" => "CODIGO", "atributos" => "width:80px");
		$oCabezera[4] = array("titulo" => "DESCRIPCION", "tipo" => "texto", "atributos" => "width:200px", "buscar" => 1);
		$oCabezera[5] = array("titulo" => "MODELO", "tipo" => "texto", "atributos" => "width:100px");
		$oCabezera[6] = array("titulo" => "MARCA", "tipo" => "texto", "atributos" => "width:200px");
		/*$oCabezera[6] = array("titulo" => "CANTIDAD", "tipo" => "texto", "atributos" => "width:25px");
		 $oCabezera[7] = array("titulo" => "PROVEEDOR", "tipo" => "texto", "atributos" => "width:80px");
		 $oCabezera[8] = array("titulo" => "ALMACEN", "tipo" => "texto", "atributos" => "width:80px");
		 $oCabezera[9] = array("titulo" => "PRECIO COMPRA", "tipo" => "texto", "atributos" => "width:25px");
		 $oCabezera[10] = array("titulo" => "PRECIO VENTA", "tipo" => "texto", "atributos" => "width:25px");
		 $oCabezera[11] = array("titulo" => "SERIAL", "tipo" => "texto", "atributos" => "width:25px");
		 $oCabezera[12] = array("titulo" => "ACC", "tipo" => "bimagen", "metodo" => "2", "ruta" => __IMG__ . "botones/add.png", "atributos" => "width:20px", "funcion" => "Actualizar_Mercancia", "mantiene" => 1, "parametro" => "");*/

		$rs = $this -> db -> query($sConsulta);
		$rsC = $rs -> result();
		if ($rs -> num_rows() != 0) {
			$i = 1;
			foreach ($rsC as $row) {
				$oFil[$i++] = array('1' => $row -> oid, //
				'2' => '', //
				'3' => $row -> codigo, //
				'4' => $row -> descripcion, //
				'5' => $row -> modelo, //
				'6' => $row -> marca, //
				);
			}
		}

		$oTable = array("Cabezera" => $oCabezera, "Cuerpo" => $oFil, "Origen" => 'json');
		$oValor['php'] = $oTable;
		$oValor['json'] = json_encode($oTable);
		return $oValor;

	}

	function ListarGrid_Inventario($arr = '') {
		/*$lstcombo = $this -> db -> query("SELECT * FROM t_ubicacion");
		 $rsCombo = $lstcombo -> result();
		 $arregloCombo = array();
		 foreach ($rsCombo as $ubicacion) {
		 $arregloCombo[$ubicacion -> descripcion] = $ubicacion -> descripcion;
		 }*/
		$lugar = ' and 1';
		$estatus = '';
		if (isset($arr['estatus'])) {
			if ($arr['estatus'] != '') {
				$estatus = " AND t_productos.estatus_mercancia=" . $arr['estatus'] . " ";
			}
		}
		if (isset($arr['ubicacion'])) {
			if ($arr['ubicacion'] != 'TODOS') {
				$lugar = " AND t_productos.ubicacion='" . $arr['ubicacion'] . "' ";
			}
		}
		$oFil = array();
		$sConsulta = 'select t_inventario.inventario_id,t_proveedores.nombre as nom_prov,t_artefactos.nombre as nom_art,marca,modelo,precio_venta,
		IFNULL(a.cantidad_a,0)as cantidad_a,
		IFNULL(b.cantidad_b,0)as cantidad_b,
		IFNULL(c.cantidad_c,0)as cantidad_c,
		IFNULL(d.cantidad_d,0)as cantidad_d
		from t_inventario
		join t_proveedores on t_proveedores.proveedor_id = t_inventario.proveedor
		join t_artefactos on t_artefactos.artefacto_id= t_inventario.artefacto
		left join (SELECT count(inventario_id) as cantidad_a , inventario_id,estatus_mercancia
			FROM t_productos 
			WHERE version=1 ' . $lugar . ' group by inventario_id)
			as a on a.inventario_id = t_inventario.inventario_id
		left join (SELECT count(inventario_id) as cantidad_b , inventario_id,estatus_mercancia
			FROM t_productos 
			WHERE version=1 and estatus_mercancia = 0 ' . $lugar . ' group by inventario_id)
			as b on b.inventario_id = t_inventario.inventario_id
		left join (SELECT count(inventario_id) as cantidad_c , inventario_id,estatus_mercancia
			FROM t_productos 
			WHERE version=1 and estatus_mercancia = 1 ' . $lugar . ' group by inventario_id)
			as c on c.inventario_id = t_inventario.inventario_id
		left join (SELECT count(inventario_id) as cantidad_d , inventario_id,estatus_mercancia
			FROM t_productos 
			WHERE version=1 and estatus_mercancia = 2 ' . $lugar . ' group by inventario_id)
			as d on d.inventario_id = t_inventario.inventario_id
		join t_productos on t_productos.inventario_id = t_inventario.inventario_id
		WHERE t_productos.version = 1 ' . $lugar . $estatus . ' group by inventario_id
		';
		//return $sConsulta;
		$oCabezera[1] = array("titulo" => " ", "tipo" => "detallePost", "atributos" => "width:10px", "funcion" => "MuestraDetalleMercancia_Inventario", "parametro" => "2,11,12");
		$oCabezera[2] = array("titulo" => "CODIGO", "atributos" => "width:10px", "buscar" => 1);
		$oCabezera[3] = array("titulo" => "MODELO", "atributos" => "width:80px", "buscar" => 1);
		$oCabezera[4] = array("titulo" => "MARCA", "atributos" => "width:40px", "buscar" => 1);
		$oCabezera[5] = array("titulo" => "PROVEEDOR", "atributos" => "width:50px");
		$oCabezera[6] = array("titulo" => "ARTEFACTO", "atributos" => "width:50px");
		$oCabezera[7] = array("titulo" => "CANT.", "atributos" => "width:10px");
		$oCabezera[8] = array("titulo" => "DEPO.", "atributos" => "width:10px");
		$oCabezera[9] = array("titulo" => "OFIC.", "atributos" => "width:10px");
		$oCabezera[10] = array("titulo" => "ENTR.", "atributos" => "width:10px");
		$oCabezera[11] = array("titulo" => "ubica", "oculto" => 1);
		$oCabezera[12] = array("titulo" => "estatus", "oculto" => 1);

		$rs = $this -> db -> query($sConsulta);
		$rsC = $rs -> result();
		if ($rs -> num_rows() != 0) {
			$i = 1;
			foreach ($rsC as $row) {
				$oFil[$i++] = array('1' => '', //
				'2' => $row -> inventario_id, //
				'3' => $row -> modelo, //
				'4' => $row -> marca, //
				'5' => $row -> nom_prov, //
				'6' => $row -> nom_art, //
				'7' => $row -> cantidad_a, //
				'8' => $row -> cantidad_b, //
				'9' => $row -> cantidad_c, //
				'10' => $row -> cantidad_d, '11' => $lugar, //
				'12' => $estatus, );
			}
			$oTable = array("Cabezera" => $oCabezera, "Cuerpo" => $oFil, "Origen" => 'json', "Paginador" => 50, "msj" => "si");
		} else {
			$oTable = array("msj" => "no");
		}
		//$objetos = array("10" => $arregloCombo);
		return json_encode($oTable);
	}

	function Listar_Detalle_Grid($oidc) {
		$lstcombo = $this -> db -> query("SELECT * FROM t_ubicacion");
		$rsCombo = $lstcombo -> result();
		$arregloCombo = array();
		foreach ($rsCombo as $ubicacion) {
			$arregloCombo[$ubicacion -> descripcion] = $ubicacion -> oid;
		}
		$oFil = array();
		$sConsulta = 'SELECT * FROM t_mercancia_seriales WHERE oidc="' . $oidc . '"';

		$oCabezera[1] = array("titulo" => "CODIGO", "atributos" => "width:10px", "oculto" => 1);
		$oCabezera[2] = array("titulo" => " ", "tipo" => "detallePost", "atributos" => "width:10px", "funcion" => "MuestraDetalleSerial", "parametro" => "1,3");
		$oCabezera[3] = array("titulo" => "SERIAL", "atributos" => "width:80px");
		$oCabezera[4] = array("titulo" => "CANTIDAD", "atributos" => "width:25px");
		$oCabezera[5] = array("titulo" => "PROVEEDOR", "atributos" => "width:80px");
		$oCabezera[6] = array("titulo" => "ALMACEN", "tipo" => "combo", "atributos" => "width:80px");
		$oCabezera[7] = array("titulo" => "PRECIO COMPRA", "tipo" => "texto", "atributos" => "width:25px");
		$oCabezera[8] = array("titulo" => "PRECIO VENTA", "tipo" => "texto", "atributos" => "width:25px");
		$oCabezera[9] = array("titulo" => "MOD", "tipo" => "bimagen", "metodo" => "2", "ruta" => __IMG__ . "botones/aceptar1.png", "atributos" => "width:20px", "funcion" => "Actualizar_Mercancia_Lote", "mantiene" => 1, "parametro" => "3,6,7,8");
		$oCabezera[10] = array("titulo" => "E", "tipo" => "bimagen", "metodo" => "2", "ruta" => __IMG__ . "botones/quitar.png", "atributos" => "width:10px", "funcion" => "Eliminar_Mercancia_Lote", "parametro" => "3");

		$rs = $this -> db -> query($sConsulta);
		$rsC = $rs -> result();
		if ($rs -> num_rows() != 0) {
			$i = 1;
			foreach ($rsC as $row) {
				$oFil[$i++] = array('1' => $row -> oidc, //
				'2' => '', //
				'3' => $row -> oids, //
				'4' => $row -> cantidad, //
				'5' => $row -> proveedor, //
				'6' => $this -> Muestra_Ubicacion($row -> ubicacion), //
				'7' => $row -> precioc, //
				'8' => $row -> preciov, //
				'9' => '', '10' => '', );
			}
		}

		$objetos = array("6" => $arregloCombo);

		$oTable = array("Cabezera" => $oCabezera, "Cuerpo" => $oFil, "Origen" => 'json', "Objetos" => $objetos);
		$oValor['php'] = $oTable;
		$oValor['json'] = json_encode($oTable);
		return $oValor;
	}

	function Listar_Detalle_Grid_Inventario($oid, $ubi, $est, $nivel) {
		/*$lstcombo = $this -> db -> query("SELECT * FROM t_ubicacion");
		 $rsCombo = $lstcombo -> result();
		 $arregloCombo = array();
		 foreach ($rsCombo as $ubicacion) {
		 $arregloCombo[$ubicacion -> descripcion] = $ubicacion -> descripcion;
		 }*/
		$oFil = array();
		$sConsulta = 'SELECT * FROM t_productos WHERE inventario_id="' . $oid . '" and version=1 ' . $ubi . $est;
		//return $sConsulta;
		$oCabezera[1] = array("titulo" => "SERIAL", "atributos" => "width:80px");
		$oCabezera[2] = array("titulo" => "ESTATUS", "atributos" => "width:50px");
		$oCabezera[3] = array("titulo" => "P.VENTA", "atributos" => "width:50px");
		$oCabezera[4] = array("titulo" => "MERC.", "atributos" => "width:25px");
		$oCabezera[5] = array("titulo" => "UBICACION", "atributos" => "width:80px");
		$oCabezera[6] = array("titulo" => "PRECIO VENTA OFICINA", "atributos" => "width:25px", "tipo" => "texto");
		if ($nivel != 5 && $nivel != 18) {
			$oCabezera[7] = array("titulo" => "Mod", "tipo" => "bimagen", "metodo" => "2", "ruta" => __IMG__ . "botones/aceptar.png", "atributos" => "width:10px", "funcion" => "Modificar_Monto_Oficina", "parametro" => "1,6,5,8");
			$oCabezera[8] = array("titulo" => "", "oculto" => 1);
		}

		$rs = $this -> db -> query($sConsulta);
		$rsC = $rs -> result();
		if ($rs -> num_rows() != 0) {
			$i = 1;
			foreach ($rsC as $row) {
				$esta = "Disponible";
				$est_mer = '';
				if ($row -> estatus == 2)
					$esta = "Vendido";
				switch($row -> estatus_mercancia) {
					case 0 :
						$est_mer = 'Deposito';
						break;
					case 1 :
						$est_mer = 'Oficina';
						break;
					case 2 :
						$est_mer = 'Entregado';
						break;
				}
				if ($nivel != 5 && $nivel != 18) {

					$oFil[$i++] = array('1' => $row -> serial, //
					'2' => $esta, //
					'3' => $row -> venta, //
					'4' => $est_mer, //
					'5' => $row -> ubicacion, //
					'6' => $row -> precio_oficina, //
					'7' => '', //
					'8' => $row -> inventario_id //
					);
				}else{
					$oFil[$i++] = array('1' => $row -> serial, //
					'2' => $esta, //
					'3' => $row -> venta, //
					'4' => $est_mer, //
					'5' => $row -> ubicacion, //
					'6' => $row -> precio_oficina
					
					);
				}
			}
		} else {
			$oFil[1] = array('1' => 'NO EXITE', '2' => 'NO EXITE', '3' => 'NO EXITE', '4' => 'NO EXITE', '5' => 'NO EXITE', '6' => 'NO EXITE', );
		}

		//$objetos = array("5" => $arregloCombo);

		$oTable = array("Cabezera" => $oCabezera, "Cuerpo" => $oFil, "Origen" => 'json');
		$oValor['php'] = $oTable;
		$oValor['json'] = json_encode($oTable);
		return $oValor;
	}

	function MuestraDetalleSerial($codigo, $serial) {
		$lstcombo = $this -> db -> query("SELECT * FROM t_ubicacion");
		$rsCombo = $lstcombo -> result();
		$arregloCombo = array();
		foreach ($rsCombo as $ubicacion) {
			$arregloCombo[$ubicacion -> descripcion] = $ubicacion -> oid;
		}
		$oFil = array();
		$sConsulta = 'SELECT * FROM t_lista_mercancia WHERE oids="' . $serial . '" AND oidc=' . $codigo;

		$oCabezera[1] = array("titulo" => "S.SERIAL", "atributos" => "width:60px");
		$oCabezera[2] = array("titulo" => "ESTATUS", "atributos" => "width:40px");
		$oCabezera[3] = array("titulo" => "ALMACEN", "tipo" => "combo", "atributos" => "width:80px");
		$oCabezera[4] = array("titulo" => "PRECIO COMPRA", "tipo" => "texto", "atributos" => "width:25px");
		$oCabezera[5] = array("titulo" => "PRECIO VENTA", "tipo" => "texto", "atributos" => "width:25px");
		$oCabezera[6] = array("titulo" => "M", "tipo" => "bimagen", "metodo" => "2", "ruta" => __IMG__ . "botones/aceptar1.png", "atributos" => "width:10px", "funcion" => "Actualizar_Mercancia_S", "mantiene" => 1, "parametro" => "1,3,4,5,2");
		$oCabezera[7] = array("titulo" => "E", "tipo" => "bimagen", "metodo" => "2", "ruta" => __IMG__ . "botones/quitar.png", "atributos" => "width:10px", "funcion" => "Eliminar_Mercancia_S", "parametro" => "1,2");

		$rs = $this -> db -> query($sConsulta);
		$rsC = $rs -> result();
		if ($rs -> num_rows() != 0) {
			$i = 1;
			foreach ($rsC as $row) {
				$oFil[$i++] = array('1' => $row -> serial_item, //
				'2' => $this -> Muestra_Estatus($row -> estatus), //
				'3' => $this -> Muestra_Ubicacion($row -> ubicacion), //
				'4' => $row -> precioc, '5' => $row -> preciov, '6' => '', '7' => '');
			}
		}

		$objetos = array("3" => $arregloCombo);

		$oTable = array("Cabezera" => $oCabezera, "Cuerpo" => $oFil, "Origen" => 'json', "Objetos" => $objetos);
		$oValor['php'] = $oTable;
		$oValor['json'] = json_encode($oTable);
		return $oValor;
	}

	function Listar_Entregas($tipo) {
		$oFil = array();
		$sConsulta = 'SELECT * FROM t_entregas WHERE tipo=' . $tipo;

		$oCabezera[1] = array("titulo" => "OID", "atributos" => "width:10px", "oculto" => 1);
		$oCabezera[2] = array("titulo" => "#", "atributos" => "width:10px", "tipo" => "detallePre");
		$oCabezera[3] = array("titulo" => "DESCRIPCION", "atributos" => "width:200px", "buscar" => 1);
		$oCabezera[4] = array("titulo" => "MODELO", "atributos" => "width:100px");
		$oCabezera[5] = array("titulo" => "EXISTENTES", "atributos" => "width:200px");
		$oCabezera[6] = array("titulo" => "CANTIDAD ENTREGADA", "atributos" => "width:25px");
		$oCabezera[7] = array("titulo" => "ENTREGADO A", "atributos" => "width:80px");
		$oCabezera[8] = array("titulo" => "FECHA", "atributos" => "width:80px");
		$oCabezera[9] = array("titulo" => "#", "tipo" => "enlace", "metodo" => 2, "funcion" => "Constancia_Entrega", "parametro" => "1", "ruta" => __IMG__ . "botones/print.png", "atributos" => "width:12px", "target" => "_blank");

		$rs = $this -> db -> query($sConsulta);
		$rsC = $rs -> result();
		if ($rs -> num_rows() != 0) {
			$i = 1;
			foreach ($rsC as $row) {
				$rsLEntrega = $this -> db -> query("SELECT * FROM t_lista_entregas WHERE oide ='" . $row -> oid . "'");
				$html = "<table style='width:100%;'><thead><th>Serial</th></thead>";
				foreach ($rsLEntrega -> result() as $serial_e) {
					$html .= "<tr><td>" . $serial_e -> serial . "</td></tr>";
				}
				$html .= "</table>";
				$oFil[$i++] = array('1' => $row -> oid, //
				'2' => $html, //
				'3' => $row -> nombre_mercancia, //
				'4' => $row -> modelo, //
				'5' => $row -> existente, //
				'6' => $row -> entregado, //
				'7' => $row -> entregado_a, //
				'8' => $row -> fecha, //
				'9' => '');
			}
		}

		$oTable = array("Cabezera" => $oCabezera, "Cuerpo" => $oFil, "Origen" => 'json');
		$oValor['php'] = $oTable;
		$oValor['json'] = json_encode($oTable);
		return $oValor;

	}
	
	function Listar_Entregas_Fecha($arr=null) {
		$ubi = '';
		$desde = '';
		if ($arr != null) {
			if ($arr['ubicacion'] == 'TODOS') $ubi = '';
			else $ubi = " AND ubicacion ='" . $arr['ubicacion'] . "'";
			if ($arr['desde'] == '') $desde = '';
			else $desde = " AND fecha_solicitud BETWEEN '" . $arr['desde'] . "' AND '" . $arr['hasta']."' ";
		}
		$oFil = array();
		$sConsulta = 'SELECT * FROM t_entregas
						JOIN (
							SELECT numero_factura,SUM(cantidad)AS total, fecha_solicitud FROM t_clientes_creditos GROUP BY numero_factura)
							AS A ON A.numero_factura=t_entregas.factura
						JOIN t_lista_entregas ON t_lista_entregas.oide = t_entregas.oid
						WHERE 1=1 '. $desde .' '. $ubi.'
						';
		//return $sConsulta;
		$oCabezera[1] = array("titulo" => "OID", "atributos" => "width:10px", "oculto" => 1);
		$oCabezera[2] = array("titulo" => "ORDEN", "atributos" => "width:10px");
		$oCabezera[3] = array("titulo" => "CEDULA", "atributos" => "width:40px","buscar"=>1);
		$oCabezera[4] = array("titulo" => "ENTREGADO A", "atributos" => "width:80px","buscar"=>1);
		$oCabezera[5] = array("titulo" => "FACTURA", "atributos" => "width:40px","buscar"=>1);
		$oCabezera[6] = array("titulo" => "MODELO", "atributos" => "width:100px","buscar"=>1);
		$oCabezera[7] = array("titulo" => "SERIAL", "atributos" => "width:40px","buscar"=>1);
		$oCabezera[8] = array("titulo" => "FECHA ENTREGA", "atributos" => "width:80px","buscar"=>1);
		$oCabezera[9] = array("titulo" => "FECHA FACTURA", "atributos" => "width:80px","buscar"=>1);
		$oCabezera[10] = array("titulo" => "UBICACION", "atributos" => "width:80px","buscar"=>1);
		$oCabezera[11] = array("titulo" => "USUARIO", "atributos" => "width:40px","buscar"=>1);
		$oCabezera[12] = array("titulo" => "IMP", "tipo" => "enlace", "metodo" => 2, "funcion" => "Constancia_Entrega_Nueva", 
								"parametro" => "1", "ruta" => __IMG__ . "botones/print.png", "atributos" => "width:12px", "target" => "_blank");
		
		$oCabezera[13] = array("titulo" => "ANU", "tipo" => "bimagen", "metodo" => 2, "funcion" => "Anula_Entrega_Nueva", "mantiene"=>1,
								"parametro" => "1,7", "ruta" => __IMG__ . "botones/error.png", "atributos" => "width:12px", "target" => "_blank");
		
		$rs = $this -> db -> query($sConsulta);
		$rsC = $rs -> result();
		
		if ($rs -> num_rows() != 0) {
			$i = 1;
			foreach ($rsC as $row) {
				$eti1 = '';$eti2 = '';
				if($row -> entregado == 0){
					$eti1 = '<font color=red>';$eti2 = '</font>';	
				}
				$cod = $row -> oid;
				$codigo = $this -> Completar($cod,8);
				$oFil[$i++] = array('1' => $cod, //
				'2' => $codigo, //
				'3' => $eti1.$row -> cedula.$eti2, //
				'4' => $eti1.$row -> entregado_a.$eti2, //
				'5' => $eti1.$row -> factura.$eti2, //
				'6' => $eti1.$row -> modelo.$eti2, //
				'7' => $row -> serial, //
				'8' => $row -> fecha, //
				'9' => $row -> fecha_solicitud, //
				'10' => $row -> ubicacion,
				'11' => $row -> usuario,
				'12' => '',
				'13' => ''
				);
			}
			$oTable = array("Cabezera" => $oCabezera, "Cuerpo" => $oFil, "Origen" => 'json',"query"=>$sConsulta,"msj"=>1);
		}else{
			$oTable = array("msj"=>0);
		}

		
		
		$oValor= json_encode($oTable);
		return $oValor;

	}
	
	function Anula_Entrega_Nueva($arr){
		$this -> db -> query("UPDATE t_entregas SET entregado=0 WHERE oid=".$arr[0]);
		$this -> db -> query("UPDATE t_productos SET estatus=1 ,estatus_mercancia=1 WHERE serial='".$arr[1]."'");
		return "Se proceso anulacion con exito. Verificar en inventario de oficina.";
	}

	/*function ListarGrid_Entrega($cod,$ubicacion) {
	 $lugar = $this -> Oculta_Ubicacion($ubicacion);
	 $oFil = array();
	 if($cod == '')	$sConsulta = 'SELECT * FROM t_mercancia';
	 else $sConsulta = 'SELECT * FROM t_mercancia WHERE codigo ="'.$cod.'"';

	 $oCabezera[1] = array("titulo" => "OID", "atributos" => "width:10px", "oculto" => 1);
	 $oCabezera[2] = array("titulo" => " ", "tipo" => "detallePost", "atributos" => "width:10px", "funcion" => "MuestraDetalleEntrega", "parametro" => "3,7");
	 $oCabezera[3] = array("titulo" => "CODIGO", "atributos" => "width:80px");
	 $oCabezera[4] = array("titulo" => "DESCRIPCION", "tipo" => "texto", "atributos" => "width:200px","buscar" => 1);
	 $oCabezera[5] = array("titulo" => "MODELO", "tipo" => "texto", "atributos" => "width:100px");
	 $oCabezera[6] = array("titulo" => "MARCA", "tipo" => "texto", "atributos" => "width:200px");
	 $oCabezera[7] = array("titulo" => "UBICACION", "atributos" => "width:10px", "oculto" => 1);
	 $rs = $this -> db -> query($sConsulta);
	 $rsC = $rs -> result();
	 if ($rs -> num_rows() != 0) {
	 $i = 1;
	 foreach ($rsC as $row) {
	 $oFil[$i++] = array(
	 '1' => $row -> oid, //
	 '2' => '', //
	 '3' => $row -> codigo, //
	 '4' => $row -> descripcion, //
	 '5' => $row -> modelo, //
	 '6' => $row -> marca, //
	 '7' => $lugar //
	 );
	 }
	 }

	 $oTable = array("Cabezera" => $oCabezera, "Cuerpo" => $oFil, "Origen" => 'json');
	 $oValor['php'] = $oTable;
	 $oValor['json'] = json_encode($oTable);
	 return $oValor;

	 }*/

	function Salvar() {
		$this -> db -> insert('t_mercancia', $this);
		$this -> db -> query('INSERT INTO t_semillero (oid, codigo) VALUES (NULL, \'' . $this -> codigo . '\');');
	}

	function Guarda_Seriales() {
		$datos = array("oidc" => $this -> codigo, "oids" => $this -> serial, "precioc" => $this -> precioc, "preciov" => $this -> preciov, "proveedor" => $this -> proveedor, "cantidad" => $this -> cantidad, "ubicacion" => $this -> almacen, "tipo" => $this -> tipo);
		$this -> db -> insert("t_mercancia_seriales", $datos);
	}

	function Guarda_Sub_Seriales() {
		for ($i = 1; $i <= $this -> cantidad; $i++) {
			$nuevo_serial = $this -> serial . '-' . $i;
			$datos = array("oidc" => $this -> codigo, "oids" => $this -> serial, "serial_item" => $nuevo_serial, "ubicacion" => $this -> almacen, "estatus" => 0, "precioc" => $this -> precioc, "preciov" => $this -> preciov, "tipo" => $this -> tipo);
			$this -> db -> insert("t_lista_mercancia", $datos);
		}
	}

	function Muestra_Ubicacion($oid) {
		$ubica = $this -> db -> query("SELECT descripcion FROM t_ubicacion WHERE oid=$oid");
		$ubicacion = $ubica -> row();
		return $ubicacion -> descripcion;
	}

	function Oculta_Ubicacion($oid) {
		$ubica = $this -> db -> query("SELECT oid FROM t_ubicacion WHERE descripcion='" . $oid . "'");
		$ubicacion = $ubica -> row();
		return $ubicacion -> oid;
	}

	function Muestra_Estatus($oid) {
		$estatus = '';
		switch ($oid) {
			case 0 :
				$estatus = "NUEVO";
				break;
			case 1 :
				$estatus = "SUCURSAL";
				break;
			case 2 :
				$estatus = "ENTREGADO";
				break;
			default :
				$estatus = "SN";
				break;
		}
		return $estatus;
	}
	
	function ListarGrid_Productos() {
		$oFil = array();
		$sConsulta = 'select t_inventario.inventario_id,t_proveedores.nombre as nom_prov,t_artefactos.nombre as nom_art,marca,modelo,precio_venta,precio_compra
		from t_inventario
		join t_proveedores on t_proveedores.proveedor_id = t_inventario.proveedor
		join t_artefactos on t_artefactos.artefacto_id= t_inventario.artefacto
		';
		//return $sConsulta;
		$oCabezera[1] = array("titulo" => " ", "tipo" => "detallePost", "atributos" => "width:10px", "funcion" => "Detalle_Grid_Productos", "parametro" => "2");
		$oCabezera[2] = array("titulo" => "CODIGO", "atributos" => "width:10px", "buscar" => 1);
		$oCabezera[3] = array("titulo" => "MODELO", "atributos" => "width:80px", "buscar" => 1);
		$oCabezera[4] = array("titulo" => "MARCA", "atributos" => "width:40px", "buscar" => 1);
		$oCabezera[5] = array("titulo" => "PROVEEDOR", "atributos" => "width:50px");
		$oCabezera[6] = array("titulo" => "ARTEFACTO", "atributos" => "width:50px");
		$oCabezera[7] = array("titulo" => "P.VENTA", "atributos" => "width:50px");
		$oCabezera[8] = array("titulo" => "P.COMPRA", "atributos" => "width:50px");
		
		

		$rs = $this -> db -> query($sConsulta);
		$rsC = $rs -> result();
		if ($rs -> num_rows() != 0) {
			$i = 1;
			foreach ($rsC as $row) {
				$oFil[$i++] = array('1' => '', //
				'2' => $row -> inventario_id, //
				'3' => $row -> modelo, //
				'4' => $row -> marca, //
				'5' => $row -> nom_prov, //
				'6' => $row -> nom_art, //
				'7' => number_format($row -> precio_venta,2), //
				'8' => number_format($row -> precio_compra,2), // 
				);
			}
			$oTable = array("Cabezera" => $oCabezera, "Cuerpo" => $oFil, "Origen" => 'json', "Paginador" => 50, "msj" => "si");
		} else {
			$oTable = array("msj" => "no");
		}
		//$objetos = array("10" => $arregloCombo);
		return json_encode($oTable);
	}

	function Detalle_Grid_Productos($oid) {
		$oFil = array();
		$sConsulta = 'SELECT * FROM t_productos WHERE inventario_id="' . $oid . '" and version=1 ';

		$oCabezera[1] = array("titulo" => "SERIAL", "atributos" => "width:80px");
		$oCabezera[2] = array("titulo" => "DESCRIPCION", "atributos" => "width:200px");
		$oCabezera[3] = array("titulo" => "ESTATUS", "atributos" => "width:50px");
		$oCabezera[4] = array("titulo" => "P.COMPRA", "atributos" => "width:50px");
		$oCabezera[5] = array("titulo" => "P.VENTA", "atributos" => "width:50px");
		$oCabezera[6] = array("titulo" => "MERC.", "atributos" => "width:25px");
		$oCabezera[7] = array("titulo" => "UBICACION", "atributos" => "width:80px");
		$oCabezera[8] = array("titulo" => "PRECIO VENTA OFICINA", "atributos" => "width:25px");
		$rs = $this -> db -> query($sConsulta);
		$rsC = $rs -> result();
		if ($rs -> num_rows() != 0) {
			$i = 1;
			foreach ($rsC as $row) {
				$esta = "Disponible";
				$est_mer = '';
				if ($row -> estatus == 2)
					$esta = "Vendido";
				switch($row -> estatus_mercancia) {
					case 0 :
						$est_mer = 'Deposito';
						break;
					case 1 :
						$est_mer = 'Oficina';
						break;
					case 2 :
						$est_mer = 'Entregado';
						break;
				}
				

					$oFil[$i++] = array('1' => $row -> serial, //
					'2' => $row -> descripcion, //
					'3' => $esta, //
					'4' => number_format($row -> compra,2), //
					'5' => number_format($row -> venta,2), //
					'6' => $est_mer, //
					'7' => $row -> ubicacion, //
					'8' => number_format($row -> precio_oficina,2),); //
			}
		} else {
			$oFil[1] = array('1' => 'NO EXITE', '2' => 'NO EXITE', '3' => 'NO EXITE', '4' => 'NO EXITE', '5' => 'NO EXITE', '6' => 'NO EXITE', );
		}

		//$objetos = array("5" => $arregloCombo);

		$oTable = array("Cabezera" => $oCabezera, "Cuerpo" => $oFil, "Origen" => 'json');
		$oValor['php'] = $oTable;
		$oValor['json'] = json_encode($oTable);
		return $oValor;
	}

}
?>