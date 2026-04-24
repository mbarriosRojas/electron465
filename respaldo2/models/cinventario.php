<?php
/**
 *  @author Carlos Enrique Penaa Albarran
 *  @package SaGem.system.application.model.cinventario
 *  @version 1.0.0
 */
class CInventario extends Model {

	/**
	 * Lista Proveedores
	 * @var int
	 */
	var $inventario_id = NULL;

	/**
	 * Lista Proveedores
	 * @var integer
	 */
	var $proveedor;

	/**
	 * Lista Proveedores
	 * @var integer
	 */
	var $artefacto;

	/**
	 * Lista Proveedores
	 * @var string
	 */
	var $marca;

	/**
	 * Lista Proveedores
	 * @var string
	 */
	var $modelo;

	/**
	 * Lista Proveedores
	 * @var double
	 */
	var $precio_compra;

	/**
	 * Lista Proveedores
	 * @var double
	 */
	var $precio_venta;
	
	/**
	 * Precio Credi Compra
	 * @var double
	 */
	var $credi_compra;

	/**
	 * Lista Proveedores
	 * @var int
	 */
	var $porcentaje;

	/**
	 * Catalogo
	 * @var int
	 */
	var $catalogo;
	
	/**
	 * Detalle
	 * @var string
	 */
	var $detalle;

	public function listar($sUbica = NULL, $iEstatus = NULL, $iNivel = NULL, $sSerial = NULL, $CProductos = NULL) {

		$this -> db -> select("t_productos.inventario_id, t_proveedores.proveedor_id, t_proveedores.nombre AS pro,
		t_artefactos.nombre AS art,t_artefactos.artefacto_id, serial,marca, modelo, ubicacion");
		$this -> db -> from("t_productos");

		//if($iEstatus != '') {
		//$this -> db -> where("t_productos.estatus", $iEstatus);
		//}
		if ($sSerial != '') {	$this -> db -> where("t_productos.serial", $sSerial);
		}
		$this -> db -> join("t_inventario", "t_productos.inventario_id=t_inventario.inventario_id");
		$this -> db -> join("t_artefactos", "t_inventario.artefacto=t_artefactos.artefacto_id");
		$this -> db -> join("t_proveedores", "t_inventario.proveedor=t_proveedores.proveedor_id");

		if ($sUbica != '') {
			if ($iNivel != 2) {
				$sProveedor = '<th>PROVEEDOR</th>';
			} else {
				$sProveedor = '';
			}
			if ($sUbica == 'TODOS') {
				$this -> db -> like("t_productos.ubicacion");
			} else {
				$this -> db -> where("t_productos.ubicacion", $sUbica);
			}

		} else {
			$sProveedor = '<th>PROVEEDOR</th>';
		}

		$this -> db -> group_by("t_inventario.modelo");

		$esquema = "<ul id=\"icons\" class=\"ui-widget ui-helper-clearfix\"><table style=\"height:18px;width:750px;\" border=0
		class=\"ui-widget ui-widget-content\" cellspacing=\"2\" cellpadding=\"0\" name=\"tInventario\" id=\"tInventario\">
		<thead><tr class=\"ui-widget-header\" style=\"height:20px;\">";

		$strReporte = $esquema . "<th>M</th><th>O</th>$sProveedor<th>ARTEFACTO</th><th>MARCA</th><th>MODELO</th>
		<th style='width:25px'>EX</th>
		</tr></thead><tbody>";

		$i = 0;
		$rsP = $this -> db -> get();
		//LISTANDO PROVEEDORES
		foreach ($rsP->result() as $fila) {
			//LISTANDO EQUIPOS Y/O ARTEFACTOS : CELULARES, TELEVISORES, OTROS
			$cantidad = $this -> Cant_Seriales($fila -> inventario_id, $sUbica, $iEstatus);
			$i++;
			if ($sUbica != '') {

				$sCol = "colspan=6";
				if ($iNivel != 2) {
					$strProveedor = '<td>' . $fila -> pro . '</td>';
					$sCol = "colspan=7";
				} else {
					$strProveedor = '';
				}
			} else {
				$sCol = "colspan=7";
				$strProveedor = '<td>' . $fila -> pro . '</td>';
			}
			$Mostrar = "<p><a href=\"#$i\" onClick=\"Mostrar_Detalles('$i');\" id=\"dialog_link\" class=\"ui-state-default ui-corner-all\"
			title='Mostrar Elementos'>
			<span class=\"ui-icon ui-icon-circle-triangle-s\"></span></a>";

			$Ocultar = "<p><a href=\"#$i\" onClick=\"Ocultar_Detalles('$i');\" id=\"dialog_link\" class=\"ui-state-default ui-corner-all\"
			title='Ocultar Elementos'>
			<span class=\"ui-icon ui-icon-circle-triangle-n\"></span></a>";

			$strReporte .= "<tr style=\"height:18px;\"><td>" . $Mostrar . "</td><td>" . $Ocultar . "</td>
			$strProveedor<td>$fila->art</td>
			<td>$fila->marca</td><td>$fila->modelo</td>
			<td align='center'><strong>" . $cantidad . "</strong></td></tr>
			<tr><td $sCol><div style=\"display:none;background-color:#FFFFFF\" id='divDetalles$i'><br>";

			$strReporte .= $CProductos -> Listar_Productos($i, $fila -> marca, $fila -> modelo, $fila -> proveedor_id, $fila -> artefacto_id, $sUbica, $iEstatus, $iNivel, $sSerial);

		}
		$strReporte .= "</div></td></tr></tbody></table></ul>";
		return $strReporte;
	}

	public function Cant_Seriales($iInventario = '', $sUbicacion = '', $iEstatus = '') {
		$iTotal = 0;

		$this -> db -> select("count(serial) AS cantidad");
		$this -> db -> from("t_productos");
		$this -> db -> join("t_inventario", "t_productos.inventario_id=t_inventario.inventario_id");
		$this -> db -> join("t_artefactos", "t_inventario.artefacto=t_artefactos.artefacto_id");
		$this -> db -> join("t_proveedores", "t_inventario.proveedor=t_proveedores.proveedor_id");
		$this -> db -> where("t_productos.inventario_id", $iInventario);
		if ($sUbicacion != '') {
			if ($sUbicacion == 'TODOS') {
				$this -> db -> like("t_productos.ubicacion");
			} else {
				$this -> db -> where("t_productos.ubicacion", $sUbicacion);
			}
		}
		if ($iEstatus != "") {	$this -> db -> where("t_productos.estatus", $iEstatus);
		}

		$this -> db -> group_by("t_inventario.modelo");

		$rs = $this -> db -> get();
		foreach ($rs->result() as $fila) {
			$iTotal = $fila -> cantidad;
		}

		return $iTotal;
	}

	public function Cant_Seriales2($iInventario = '', $sUbicacion = '', $iEstatus = '') {
		$iTotal = 0;
		$this -> db -> select("t_productos.ubicacion AS ubica_p");
		$this -> db -> from("t_productos");
		$this -> db -> join("t_inventario", "t_productos.inventario_id=t_inventario.inventario_id");
		$this -> db -> join("t_artefactos", "t_inventario.artefacto=t_artefactos.artefacto_id");
		$this -> db -> join("t_proveedores", "t_inventario.proveedor=t_proveedores.proveedor_id");
		$this -> db -> where("t_productos.inventario_id", $iInventario);
		if ($iEstatus != "") {	$this -> db -> where("t_productos.estatus", $iEstatus);
		}
		$rs = $this -> db -> get();
		foreach ($rs->result() as $fila) {
			if ($this -> session -> userdata('nivel') == 0) {
				$iTotal++;
			} else {
				$quien = $fila -> ubica_p;
				$query2 = $this -> db -> query("SELECT t_ubicacion.descripcion AS ubicacion	FROM t_usuario
				JOIN _tr_usuarioubicacion ON t_usuario.oid = _tr_usuarioubicacion.oidu
				JOIN t_ubicacion ON _tr_usuarioubicacion.oidb = t_ubicacion.oid
				WHERE documento_id = '" . $quien . "' LIMIT 1 ");
				foreach ($query2->result() as $persona) {
					if (trim($persona -> ubicacion) == trim($sUbicacion)) {
						$iTotal++;
					}
				}
			}
		}
		return $iTotal;
	}

	public function Cant_Serialesp($iInventario = '', $sUbicacion = '', $iEstatus = '') {
		$iTotal = 0;

		$this -> db -> select("count(serial) AS cantidad");
		$this -> db -> from("t_pproductos");
		$this -> db -> join("t_pinventario", "t_pproductos.inventario_id=t_pinventario.inventario_id");
		$this -> db -> join("t_partefactos", "t_pinventario.artefacto=t_partefactos.artefacto_id");
		$this -> db -> join("t_pproveedores", "t_pinventario.proveedor=t_pproveedores.proveedor_id");
		$this -> db -> where("t_pproductos.inventario_id", $iInventario);
		if ($sUbicacion != '') {
			if ($sUbicacion == 'TODOS') {
				$this -> db -> like("t_pproductos.ubicacion");
			} else {
				$this -> db -> where("t_pproductos.ubicacion", $sUbicacion);
			}
		}
		if ($iEstatus != "") {	$this -> db -> where("t_pproductos.estatus", $iEstatus);
		}

		$this -> db -> group_by("t_pinventario.modelo");

		$rs = $this -> db -> get();
		foreach ($rs->result() as $fila) {
			$iTotal = $fila -> cantidad;
		}

		return $iTotal;
	}

	public function Cant_Serialess($iInventario = '', $sUbicacion = '', $iEstatus = '') {
		$iTotal = 0;

		$this -> db -> select("count(serial) AS cantidad");
		$this -> db -> from("t_sproductos");
		$this -> db -> join("t_sinventario", "t_sproductos.inventario_id=t_sinventario.inventario_id");
		$this -> db -> join("t_sartefactos", "t_sinventario.artefacto=t_sartefactos.artefacto_id");
		$this -> db -> join("t_sproveedores", "t_sinventario.proveedor=t_sproveedores.proveedor_id");
		$this -> db -> where("t_sproductos.inventario_id", $iInventario);
		if ($sUbicacion != '') {
			if ($sUbicacion == 'TODOS') {
				$this -> db -> like("t_sproductos.ubicacion");
			} else {
				$this -> db -> where("t_sproductos.ubicacion", $sUbicacion);
			}
		}
		if ($iEstatus != "") {	$this -> db -> where("t_sproductos.estatus", $iEstatus);
		}

		$this -> db -> group_by("t_sinventario.modelo");

		$rs = $this -> db -> get();
		foreach ($rs->result() as $fila) {
			$iTotal = $fila -> cantidad;
		}

		return $iTotal;
	}

	public function Listar_Combo($sUsuario = "") {
		$sCon = "";
		$this -> db -> select("serial, modelo, marca, venta");
		$this -> db -> from("t_usuarios");
		$this -> db -> join("t_productos", "t_usuarios.documento_id = t_productos.ubicacion");
		$this -> db -> join("t_inventario", "t_inventario.inventario_id = t_productos.inventario_id");
		$this -> db -> where("t_usuarios.ubicacion", $sUsuario);
		$this -> db -> where("t_productos.estatus", '1');
		$rs = $this -> db -> get();

		foreach ($rs->result() as $fila) {
			$sCon .= "<option value='$fila->serial'>(" . $fila -> serial . ") MODELO: " . $fila -> modelo . ", PRECIO: " . number_format($fila -> venta, 2, ".", ",") . "Bs. , MARCA: " . $fila -> marca . "</option>";
		}

		return $sCon;
	}

	/**
	 * Buscar Factura que tenga un cliente
	 * @param string
	 * @param string
	 * @return array
	 */
	public function BFactura($sCFactura = "", $sCedula = "", $fecha = null) {
		$sFactura = array();
		$sProducto = array();
		$this -> db -> select("documento_id, fecha_solicitud, motivo,condicion,num_operacion,fecha_operacion,monto_operacion,serial, empresa, cobrado_en,marca_consulta,observaciones,ncheque,mcheque");
		$this -> db -> from("t_clientes_creditos");
		$this -> db -> where("numero_factura", trim($sCFactura));
		//$this -> db -> where("documento_id", $sCedula);
		//$this -> db -> limit(1);
		$rs = $this -> db -> get();
		if ($rs -> num_rows() > 0) {
			foreach ($rs->result() as $lst) {
				if ($lst -> documento_id == $sCedula) {
					//$sProducto = $this -> BSerial($lst -> motivo);
					if ($fecha == $lst -> fecha_solicitud) {
						$sFactura["existe"] = 1;
					} else {
						$sFactura["existe"] = 3;
					}
					$sFactura["motivo"] = $lst -> motivo;
					$sFactura["condicion"] = $lst -> condicion;
					$sFactura["num_operacion"] = $lst -> num_operacion;
					$sFactura["fecha_operacion"] = $lst -> fecha_operacion;
					$sFactura["monto_operacion"] = $lst -> monto_operacion;
					$sFactura["modelo"] = '';//$sProducto["modelo"];
					$sFactura["marca"] = '';//$sProducto["marca"];
					$sFactura["serial"] = $lst -> serial;
					$sFactura["empresa"] = $lst -> empresa;
					$sFactura["cobrado_en"] = $lst -> cobrado_en;
					$sFactura["tipo_pago"] = $lst -> marca_consulta;
					$sFactura["observaciones"] = $lst -> observaciones;
					$sFactura["ncheque"] = $lst -> ncheque;
					$sFactura["mcheque"] = $lst -> mcheque;
					if($lst -> marca_consulta == '6'){
						$arrVoucher = array();
						$tipo_voucher='';
						$query_voucher = $this->db->query("SELECT * FROM t_lista_voucher WHERE cid='".$sCFactura."'");
						if($query_voucher -> num_rows() > 0){
							foreach ($query_voucher -> result() as $row2) { 
								$item = $row2 -> fecha . '|'.$row2->ndep. '|'.$row2->monto;
								$arrVoucher[] = $item;
								$tipo_voucher = $row2 -> banco;
							}
						}
						$sFactura['tvoucher']=$tipo_voucher;
						$sFactura['lstVoucher'] = $arrVoucher;
						
					}

					//return $sFactura;
				} else {
					$sFactura["existe"] = 2;
					$sFactura["motivo"] = "NULL";
					$sFactura["condicion"] = "NULL";
					$sFactura["num_operacion"] = "NULL";
					$sFactura["fecha_operacion"] = "NULL";
					$sFactura["monto_operacion"] = "NULL";
					$sFactura["modelo"] = "NULL";
					$sFactura["marca"] = "NULL";
					$sFactura["serial"] = "NULL";
					$sFactura["empresa"] = "NULL";
					$sFactura["cobrado_en"] = "NULL";
					$sFactura["tipo_pago"] = "NULL";
					return $sFactura;
				}
			}
		} else {
			$sFactura["motivo"] = "NULL";
			$sFactura["condicion"] = "NULL";
			$sFactura["num_operacion"] = "NULL";
			$sFactura["fecha_operacion"] = "NULL";
			$sFactura["monto_operacion"] = "NULL";
			$sFactura["modelo"] = "NULL";
			$sFactura["marca"] = "NULL";
			$sFactura["serial"] = "NULL";
			$sFactura["empresa"] = "NULL";
			$sFactura["cobrado_en"] = "NULL";
			$sFactura["tipo_pago"] = "NULL";
			$sFactura["existe"] = 0;
			//return $sFactura;
		}
		return $sFactura;
	}

	public function BFactura_Modificar($sCFactura = "") {
		$sFactura = array();
		$sProducto = array();
		$this -> db -> select("documento_id, motivo,condicion,num_operacion,fecha_operacion,monto_operacion,serial, empresa, cobrado_en");
		$this -> db -> from("t_clientes_creditos");
		$this -> db -> where("numero_factura", trim($sCFactura));

		$rs = $this -> db -> get();
		if ($rs -> num_rows() > 0) {
			foreach ($rs->result() as $lst) {
				$sProducto = $this -> BSerial($lst -> motivo);
				$sFactura["motivo"] = $lst -> motivo;
				$sFactura["condicion"] = $lst -> condicion;
				$sFactura["num_operacion"] = $lst -> num_operacion;
				$sFactura["fecha_operacion"] = $lst -> fecha_operacion;
				$sFactura["monto_operacion"] = $lst -> monto_operacion;
				$sFactura["modelo"] = $sProducto["modelo"];
				$sFactura["marca"] = $sProducto["marca"];
				$sFactura["serial"] = $lst -> serial;
				$sFactura["empresa"] = $lst -> empresa;
				$sFactura["cobrado_en"] = $lst -> cobrado_en;
				$sFactura["existe"] = 1;
				return $sFactura;
			}
		} else {
			$sFactura["motivo"] = "NULL";
			$sFactura["condicion"] = "NULL";
			$sFactura["num_operacion"] = "NULL";
			$sFactura["fecha_operacion"] = "NULL";
			$sFactura["monto_operacion"] = "NULL";
			$sFactura["modelo"] = "NULL";
			$sFactura["marca"] = "NULL";
			$sFactura["serial"] = "NULL";
			$sFactura["empresa"] = "NULL";
			$sFactura["cobrado_en"] = "NULL";
			$sFactura["existe"] = 0;
			//return $sFactura;
		}
		return $sFactura;
	}

	public function BFactura_CC($factura = null){
		$sFactura = array("cedula"=>"","factura"=>"");
		$query = "SELECT * FROM t_facturas WHERE oidf='" . $factura . "'";
		$consulta = $this -> db -> query($query);
		foreach ($consulta -> result() as $row) {
			$sFactura['cedula'] = $row -> oidc;
			$sFactura['factura'] = $row -> oidf;
		}
		return $sFactura;
	}
	
	public function Boucher_CC($factura){
		$arrVoucher = array();
		$query_voucher = $this->db->query("SELECT * FROM t_lista_voucher WHERE cid='".$factura."'");
		if($query_voucher -> num_rows() > 0){
			foreach ($query_voucher -> result() as $row2) { 
				$item = $row2 -> fecha . '|'.$row2->ndep. '|'.$row2->monto;
				$arrVoucher[] = $item;
			}
		}
		return $arrVoucher;
	}

	/**
	 * Buscar un serial en una factura
	 * @param string
	 */
	private function BSerial($sSerial = "") {
		$sCon = array();
		$this -> db -> select("serial, modelo, marca");
		$this -> db -> from("t_inventario");
		$this -> db -> join("t_productos", "t_inventario.inventario_id = t_productos.inventario_id");
		$this -> db -> where("t_productos.serial", $sSerial);
		$rs = $this -> db -> get();
		if ($rs -> num_rows() > 0) {
			foreach ($rs->result() as $fila) {
				$sCon["modelo"] = $fila -> modelo;
				$sCon["marca"] = $fila -> marca;
			}

		} else {
			$sCon["modelo"] = "NULL";
			$sCon["marca"] = "NULL";
		}
		return $sCon;
	}

	public function Listar_Equipos($sNivel = '') {
		$sCon = "";

		$this -> db -> from("t_artefactos");

		$rs = $this -> db -> get();

		foreach ($rs->result() as $fila) {

			$sCon .= "<option value='" . $fila -> artefacto_id . "'>" . $fila -> nombre . "</option>";

		}

		return $sCon;
	}

	public function Listar_Json_Modelos($iArtefacto = "", $sUsuario = "") {
		$sCon = array();
		$this -> db -> select("t_inventario.inventario_id, serial, modelo, marca, venta");
		$this -> db -> from("t_usuarios");
		$this -> db -> join("t_productos", "t_usuarios.documento_id = t_productos.ubicacion");
		$this -> db -> join("t_inventario", "t_inventario.inventario_id = t_productos.inventario_id");
		$this -> db -> where("t_usuarios.ubicacion", $sUsuario);
		$this -> db -> where("t_inventario.artefacto", $iArtefacto);
		$this -> db -> where("t_productos.estatus1", '1');
		$rs = $this -> db -> get();

		foreach ($rs->result() as $fila) {
			$sCon[$fila -> inventario_id] = $fila -> modelo;
		}

		return json_encode($sCon);
	}

	public function Listar_Json_Seriales($iArtefacto = null, $sModelo = null, $sUsuario = null) {
		$sCon = array();
		$this -> db -> select("t_inventario.inventario_id, serial, modelo, marca, venta");
		$this -> db -> from("t_usuarios");
		$this -> db -> join("t_productos", "t_usuarios.documento_id = t_productos.ubicacion");
		$this -> db -> join("t_inventario", "t_inventario.inventario_id = t_productos.inventario_id");
		//$this -> db -> where("t_usuarios.ubicacion", $sUsuario);
		$this -> db -> where("t_inventario.modelo", $sModelo);
		//$this -> db -> where("t_productos.inventario_id", $sModelo);

		$this -> db -> where("t_productos.estatus", '1');
		$rs = $this -> db -> get();

		foreach ($rs->result() as $fila) {
			$sCon[$fila -> serial] = "(" . $fila -> serial . ") MARCA: " . $fila -> marca . " VENTA: " . $fila -> venta;
		}

		return json_encode($sCon);
	}

	public function Listar_Json_Seriales2($sModelo = '', $sUbicacion = '') {
		$this -> db -> select("t_productos.ubicacion AS ubica_p,t_inventario.inventario_id, serial, modelo, marca, venta");
		$this -> db -> from("t_productos");
		$this -> db -> join("t_inventario", "t_productos.inventario_id=t_inventario.inventario_id");
		$this -> db -> join("t_artefactos", "t_inventario.artefacto=t_artefactos.artefacto_id");
		$this -> db -> join("t_proveedores", "t_inventario.proveedor=t_proveedores.proveedor_id");
		$this -> db -> where("t_inventario.modelo", $sModelo);

		$this -> db -> where("t_productos.estatus", 1);

		//echo $sUbicacion;
		$rs = $this -> db -> get();
		$sCon = array();
		foreach ($rs->result() as $fila) {
			if ($this -> session -> userdata('nivel') == 0) {
				$sCon[$fila -> serial] = "(" . $fila -> serial . ") MARCA: " . $fila -> marca . " VENTA: " . $fila -> venta . " UBICACION:" . $sUbicacion;
			} else {
				$quien = $fila -> ubica_p;
				$query2 = $this -> db -> query("SELECT t_ubicacion.descripcion AS ubicacion	FROM t_usuario
				JOIN _tr_usuarioubicacion ON t_usuario.oid = _tr_usuarioubicacion.oidu
				JOIN t_ubicacion ON _tr_usuarioubicacion.oidb = t_ubicacion.oid
				WHERE documento_id = '" . $quien . "' LIMIT 1 ");
				foreach ($query2->result() as $persona) {
					if (trim($persona -> ubicacion) == trim($sUbicacion)) {
						$sCon[$fila -> serial] = "(" . $fila -> serial . ") MARCA: " . $fila -> marca . " VENTA: " . $fila -> venta . " UBICACION:" . $sUbicacion;
					}
				}
			}
		}

		return json_encode($sCon);
	}

	public function listarp($sUbica = NULL, $iEstatus = NULL, $iNivel = NULL, $sSerial = NULL, $CProductos = NULL) {

		$this -> db -> select("t_pproductos.inventario_id, t_pproveedores.proveedor_id, t_pproveedores.nombre AS pro,
		t_partefactos.nombre AS art,t_partefactos.artefacto_id, serial,marca, modelo, ubicacion");
		$this -> db -> from("t_pproductos");

		//if($iEstatus != '') {
		//$this -> db -> where("t_productos.estatus", $iEstatus);
		//}
		if ($sSerial != '') {	$this -> db -> where("t_pproductos.serial", $sSerial);
		}
		$this -> db -> join("t_pinventario", "t_pproductos.inventario_id=t_pinventario.inventario_id");
		$this -> db -> join("t_partefactos", "t_pinventario.artefacto=t_partefactos.artefacto_id");
		$this -> db -> join("t_pproveedores", "t_pinventario.proveedor=t_pproveedores.proveedor_id");

		if ($sUbica != '') {
			if ($iNivel != 2) {
				$sProveedor = '<th>PROVEEDOR</th>';
			} else {
				$sProveedor = '';
			}
			if ($sUbica == 'TODOS') {
				$this -> db -> like("t_pproductos.ubicacion");
			} else {
				$this -> db -> where("t_pproductos.ubicacion", $sUbica);
			}

		} else {
			$sProveedor = '<th>PROVEEDOR</th>';
		}

		$this -> db -> group_by("t_pinventario.modelo");

		$esquema = "<ul id=\"icons\" class=\"ui-widget ui-helper-clearfix\"><table style=\"height:18px;width:750px;\" border=0
		class=\"ui-widget ui-widget-content\" cellspacing=\"2\" cellpadding=\"0\" name=\"tInventario\" id=\"tInventario\">
		<thead><tr class=\"ui-widget-header\" style=\"height:20px;\">";

		$strReporte = $esquema . "<th>M</th><th>O</th>$sProveedor<th>ARTEFACTO</th><th>MARCA</th><th>MODELO</th>
		<th style='width:25px'>EX</th>
		</tr></thead><tbody>";

		$i = 0;
		$rsP = $this -> db -> get();
		//LISTANDO PROVEEDORES
		foreach ($rsP->result() as $fila) {
			//LISTANDO EQUIPOS Y/O ARTEFACTOS : CELULARES, TELEVISORES, OTROS
			$cantidad = $this -> Cant_Serialesp($fila -> inventario_id, $sUbica, $iEstatus);
			$i++;
			if ($sUbica != '') {

				$sCol = "colspan=6";
				if ($iNivel != 2) {
					$strProveedor = '<td>' . $fila -> pro . '</td>';
					$sCol = "colspan=7";
				} else {
					$strProveedor = '';
				}
			} else {
				$sCol = "colspan=7";
				$strProveedor = '<td>' . $fila -> pro . '</td>';
			}
			$Mostrar = "<p><a href=\"#$i\" onClick=\"Mostrar_Detalles('$i');\" id=\"dialog_link\" class=\"ui-state-default ui-corner-all\"
			title='Mostrar Elementos'>
			<span class=\"ui-icon ui-icon-circle-triangle-s\"></span></a>";

			$Ocultar = "<p><a href=\"#$i\" onClick=\"Ocultar_Detalles('$i');\" id=\"dialog_link\" class=\"ui-state-default ui-corner-all\"
			title='Ocultar Elementos'>
			<span class=\"ui-icon ui-icon-circle-triangle-n\"></span></a>";

			$strReporte .= "<tr style=\"height:18px;\"><td>" . $Mostrar . "</td><td>" . $Ocultar . "</td>
			$strProveedor<td>$fila->art</td>
			<td>$fila->marca</td><td>$fila->modelo</td>
			<td align='center'><strong>" . $cantidad . "</strong></td></tr>
			<tr><td $sCol><div style=\"display:none;background-color:#FFFFFF\" id='divDetalles$i'><br>";

			$strReporte .= $CProductos -> Listar_Productosp($i, $fila -> marca, $fila -> modelo, $fila -> proveedor_id, $fila -> artefacto_id, $sUbica, $iEstatus, $iNivel, $sSerial);

		}
		$strReporte .= "</div></td></tr></tbody></table></ul>";
		return $strReporte;
	}

	public function listars($sUbica = NULL, $iEstatus = NULL, $iNivel = NULL, $sSerial = NULL, $CProductos = NULL) {

		$this -> db -> select("t_sproductos.inventario_id, t_sproveedores.proveedor_id, t_sproveedores.nombre AS pro,
		t_sartefactos.nombre AS art,t_sartefactos.artefacto_id, serial,marca, modelo, ubicacion");
		$this -> db -> from("t_sproductos");

		//if($iEstatus != '') {
		//$this -> db -> where("t_productos.estatus", $iEstatus);
		//}
		if ($sSerial != '') {	$this -> db -> where("t_sproductos.serial", $sSerial);
		}
		$this -> db -> join("t_sinventario", "t_sproductos.inventario_id=t_sinventario.inventario_id");
		$this -> db -> join("t_sartefactos", "t_sinventario.artefacto=t_sartefactos.artefacto_id");
		$this -> db -> join("t_sproveedores", "t_sinventario.proveedor=t_sproveedores.proveedor_id");

		if ($sUbica != '') {
			if ($iNivel != 2) {
				$sProveedor = '<th>PROVEEDOR</th>';
			} else {
				$sProveedor = '';
			}
			if ($sUbica == 'TODOS') {
				$this -> db -> like("t_sproductos.ubicacion");
			} else {
				$this -> db -> where("t_sproductos.ubicacion", $sUbica);
			}

		} else {
			$sProveedor = '<th>PROVEEDOR</th>';
		}

		$this -> db -> group_by("t_sinventario.modelo");

		$esquema = "<ul id=\"icons\" class=\"ui-widget ui-helper-clearfix\"><table style=\"height:18px;width:750px;\" border=0
		class=\"ui-widget ui-widget-content\" cellspacing=\"2\" cellpadding=\"0\" name=\"tInventario\" id=\"tInventario\">
		<thead><tr class=\"ui-widget-header\" style=\"height:20px;\">";

		$strReporte = $esquema . "<th>M</th><th>O</th>$sProveedor<th>ARTEFACTO</th><th>MARCA</th><th>MODELO</th>
		<th style='width:25px'>EX</th>
		</tr></thead><tbody>";

		$i = 0;
		$rsP = $this -> db -> get();
		//LISTANDO PROVEEDORES
		foreach ($rsP->result() as $fila) {
			//LISTANDO EQUIPOS Y/O ARTEFACTOS : CELULARES, TELEVISORES, OTROS
			$cantidad = $this -> Cant_Serialess($fila -> inventario_id, $sUbica, $iEstatus);
			$i++;
			if ($sUbica != '') {

				$sCol = "colspan=6";
				if ($iNivel != 2) {
					$strProveedor = '<td>' . $fila -> pro . '</td>';
					$sCol = "colspan=7";
				} else {
					$strProveedor = '';
				}
			} else {
				$sCol = "colspan=7";
				$strProveedor = '<td>' . $fila -> pro . '</td>';
			}
			$Mostrar = "<p><a href=\"#$i\" onClick=\"Mostrar_Detalles('$i');\" id=\"dialog_link\" class=\"ui-state-default ui-corner-all\"
			title='Mostrar Elementos'>
			<span class=\"ui-icon ui-icon-circle-triangle-s\"></span></a>";

			$Ocultar = "<p><a href=\"#$i\" onClick=\"Ocultar_Detalles('$i');\" id=\"dialog_link\" class=\"ui-state-default ui-corner-all\"
			title='Ocultar Elementos'>
			<span class=\"ui-icon ui-icon-circle-triangle-n\"></span></a>";

			$strReporte .= "<tr style=\"height:18px;\"><td>" . $Mostrar . "</td><td>" . $Ocultar . "</td>
			$strProveedor<td>$fila->art</td>
			<td>$fila->marca</td><td>$fila->modelo</td>
			<td align='center'><strong>" . $cantidad . "</strong></td></tr>
			<tr><td $sCol><div style=\"display:none;background-color:#FFFFFF\" id='divDetalles$i'><br>";

			$strReporte .= $CProductos -> Listar_Productoss($i, $fila -> marca, $fila -> modelo, $fila -> proveedor_id, $fila -> artefacto_id, $sUbica, $iEstatus, $iNivel, $sSerial);

		}
		$strReporte .= "</div></td></tr></tbody></table></ul>";
		return $strReporte;
	}

	public function Combo_Artefactos() {
		$strCombo = '';
		$strQuery = $this -> db -> query("SELECT * FROM t_inventario ORDER BY modelo");
		if ($strQuery -> num_rows() > 0) {
			foreach ($strQuery->result() as $row) {
				$strCombo .= '<option value="' . $this -> Contar_Artefacto($row -> inventario_id) . '">' . $row -> modelo . '</option>';
			}
		}
		return $strCombo;

	}
	
	public function Combo_Artefactos2() {
		$strCombo = '';
		$strQuery = $this -> db -> query("SELECT * FROM t_inventario ORDER BY modelo");
		if ($strQuery -> num_rows() > 0) {
			foreach ($strQuery->result() as $row) {
				$strCombo .= '<option value="' . $row -> inventario_id . '">' . $row -> modelo . '</option>';
			}
		}
		return $strCombo;

	}

	/*
	 * Metoto parta contar modeloss de la tabla de inventario que apararecen en la tabla productos
	 */
	public function Contar_Artefacto($id) {
		$strQuery = $this -> db -> query('SELECT COUNT(*) AS contador FROM t_productos WHERE inventario_id=' . $id . '');
		if ($strQuery -> num_rows() > 0) {
			foreach ($strQuery->result() as $row) {
				return $row -> contador;
			}
		}
		return 0;
	}

	/*
	 * Metoto parta eliminar modeloss de la tabla de inventario
	 */
	public function Eliminar_Modelo($mod, $strPeticion, $strMotivo) {

		$query = $this -> db -> query('SELECT * FROM t_inventario WHERE modelo="' . $mod . '"');
		$cant = $query -> num_rows();

		if ($cant > 0) {
			$this -> db -> where("modelo", trim($mod));
			$this -> db -> delete("t_inventario");
			$data = array(
			//'id' => null,
			'referencia' => $mod, 'tipo' => 11, 'usuario' => $_SESSION['usuario'], 'motivo' => $strMotivo . "(" . $mod . ")", 'peticion' => $strPeticion);
			$this -> db -> insert('_th_sistema', $data);
		}
	}

}
?>