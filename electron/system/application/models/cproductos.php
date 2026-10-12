<?php
/**
 *  @author Carlos Enrique Penaa Albarran
 *  @package SaGem.system.application.model.cinventario
 *  @version 1.0.0
 */
class CProductos extends Model {

	/**
	 * Lista Proveedores
	 * @var int
	 */
	var $serial;

	/**
	 * Fecha de Ingreso
	 * @var date
	 */
	var $fecha_ingreso;

	/**
	 * Inventario ID
	 * @var int
	 */
	var $inventario_id;

	/**
	 * Descripciones
	 * @var char
	 */
	var $descripcion;

	/**
	 * Estatus 0:REPARACION 1: ACTIVO 2: VENDIDO
	 * @var int
	 */
	var $estatus;

	/**
	 * Precio de Compra
	 * @var double
	 */
	var $compra;

	/**
	 * Precio de Venta
	 * @var double
	 */
	var $venta;

	/**
	 * Precio de Credi Compra
	 * @var double
	 */
	var $credi_compra;

	/**
	 * Ubicacion de Usuarios
	 * @var char
	 */
	var $ubicacion;

	/**
	 * Cantidad
	 * @var int
	 */
	var $cant_garantia;

	/**
	 * Tipo de Garantia Dia | Mes | Ano
	 * @var char
	 */
	var $tipo_garantia;

	/**
	 * Version de inventario | 0:viejos | 1:nuevos
	 * @var int
	 */
	var $version;

	/**
	 * precio asignado a oficina por serial
	 * @var int
	 */
	var $precio_oficina;

	/**
	 * Estatus de Mercancia | 0:Sede Principal | 1:Traslado a oficna | 2:Entregado a Cliente
	 * @var int
	 */
	var $estatus_mercancia;

	public function Listar_Productos($i = NULL, $marca = NULL, $modelo = NULL, $proveedor = NULL, $artefacto = NULL, $sUbica = NULL, $iEstatus = NULL, $iNivel = NULL, $sSerial = NULL) {

		$marca_d = '';
		$modelo_d = '';
		$equipo_d = '';
		$proveedor_d = '';
		$Precio_C = 0;
		$Precio_V = 0;
		$CanGar = '';
		$Garantia = '';

		$this -> load -> model("CInventario");
		$this -> load -> model("CListartareas");
		$combo = null;
		$combo = $this -> CListartareas -> Listar_Usuarios_Combo(); ;
		$this -> db -> select("t_proveedores.nombre AS n_p, t_artefactos.nombre AS n_a,
		serial, compra, venta, fecha_ingreso, cant_garantia, tipo_garantia, ubicacion, estatus");

		$this -> db -> from("t_inventario");
		$this -> db -> join("t_proveedores", "t_inventario.proveedor=t_proveedores.proveedor_id");
		$this -> db -> join("t_productos", "t_productos.inventario_id = t_inventario.inventario_id");
		$this -> db -> join("t_artefactos", "t_artefactos.artefacto_id=t_inventario.artefacto");

		$this -> db -> where("marca", $marca);
		$this -> db -> where("modelo", $modelo);
		$this -> db -> where("t_proveedores.proveedor_id", $proveedor);
		$this -> db -> where("t_artefactos.artefacto_id", $artefacto);
		if ($iEstatus != "") {	$this -> db -> where("t_productos.estatus", $iEstatus);
		}
		if ($sSerial != "") {	$this -> db -> where("t_productos.serial", $sSerial);
		}

		if ($sUbica == '') {
			$sUbicaCab = "<th style='width:130px'>UBICACION</th>";
			$strCompra = "<th>COMPRA</th>";

		} else {

			if ($iNivel != 2) {
				$sUbicaCab = "<th style='width:130px'>UBICACION</th>";
				$strCompra = "<th>COMPRA</th>";
			} else {
				$strCompra = "";
				$sUbicaCab = "";
			}
			if ($sUbica == 'TODOS') {
				$this -> db -> like("t_productos.ubicacion");
			} else {
				$this -> db -> where("t_productos.ubicacion", $sUbica);
			}

		}
		$sDestino = "# FACTURA";
		if ($sSerial == "") {$sDestino = "DESTINO";
		}
		$rsList = $this -> db -> get();
		$sCon = "";

		$sReporte = "";
		$sReporte .= "

		<form name='frmLista$i' id='frmLista$i' action='" . base_url() . "index.php/cooperativa/PInventario'>";

		$sReporte .= "<br><center>
		<table style=\"height:18px;width:720px;\" border=0
		class=\"ui-widget ui-widget-content\" cellspacing=\"2\" cellpadding=\"0\"
		name=\"tSeriales$i\" id=\"tSeriales$i\"	>
		<thead><tr class=\"ui-widget-header\" style=\"height:20px;\">";

		$sReporte .= "<th>#</th><th>E&nbsp;</th><th>SERIAL</th>$strCompra<th>VENTA</th><th>ESTATUS
		</th>$sUbicaCab<th style='width:150px'>$sDestino</th><th>GARANTIA</th></tr></thead><tbody>";
		$o = 0;
		$sCombo = "";
		foreach ($rsList->result() as $lst) {

			$o++;

			$usuario = $this -> Que_Usuario($lst -> ubicacion);
			if ($sUbica == "") {
				$sComprar = "<td align=right>" . number_format($lst -> compra, 2, ".", ",") . " Bs.&nbsp;&nbsp;</td>";
				$sCombo = "<td>
				<select id=c$i$o name=c$i$o class='inputxt' style='width:150px' >
				<option value='" . $lst -> ubicacion . "'>" . strtoupper($usuario["login"]) . "</option>
				" . $combo . "
				</select></td>";
			} else {
				if ($iNivel != 2) {
					$sComprar = "<td align=right>" . number_format($lst -> compra, 2, ".", ",") . " Bs.&nbsp;&nbsp;</td>";

					if ($sSerial == "") {
						$sCombo = "<td>
						<select id=c$i$o name=c$i$o class='inputxt' style='width:150px' >
						<option value='" . $lst -> ubicacion . "'>" . strtoupper($usuario["login"]) . "</option>
						" . $combo . "</select></td>";
					} else {
						$sCombo = "<td><input type='text' name='txtnfactura' id='txtnfactura'  class='inputxt' style='width:150px' /></td>";
					}

				} else {
					$sComprar = "";
					$sCombo = "";
				}

			}

			if ($lst -> estatus == 1) {
				$sEstatus = "DISPONIBLE";
			} else {
				$sEstatus = "VENDIDO";
			}

			/** $sCon = "<tr><td align='center'>$o</td>
			 <td align=left>
			 <input type='hidden' value='$lst->serial' id='s$i$o' name='s$i$o' />
			 &nbsp;&nbsp;$lst->serial</td>$sComprar
			 <td align=right>" . number_format($lst -> venta, 2, ".", ",") . " Bs.&nbsp;&nbsp;</td>
			 <td>" . $sEstatus . "</td>
			 <td align='center'>" . $usuario["login"] . "</td>" . $sCombo . "
			 <td align=center>" . $lst -> cant_garantia . " " . $lst -> tipo_garantia . "</td>
			 </tr>"; **/

			$sCon = "<tr><td align='center'>$o</td>";
			if ($this -> session -> userdata('usuario') == 'AlvaroZ') {
				$sCon .= "<td align='center'>
					<p>.</p>
				</td>";
			} else {
				$sCon .= "<td align='center'>
				<p><a href=\"#\" onClick=\"Eliminar_Serial_Principal('" . __LOCALWWW__ . "','" . $lst -> serial . "','" . $i . "');\" id=\"dialog_link\"
					class=\"ui-state-default ui-corner-all\"><span class=\"ui-icon ui-icon-circle-minus\">
					</span></a></p>
				</td>";
			}

			$sCon .= "<td align=left>
			<input type='hidden' value='$lst->serial' id='s$i$o' name='s$i$o' />
			&nbsp;&nbsp;$lst->serial</td>$sComprar
			<td align=right>" . number_format($lst -> venta, 2, ".", ",") . " Bs.&nbsp;&nbsp;</td>
			<td>" . $sEstatus . "</td>
			<td align='center'>" . $usuario["login"] . "</td>" . $sCombo . "
			<td align=center>" . $lst -> cant_garantia . " " . $lst -> tipo_garantia . "</td>
			</tr>";

			$sReporte .= $sCon;

			$equipo_d = $lst -> n_a;
			$proveedor_d = $lst -> n_p;
			$Precio_C = $lst -> compra;
			$Precio_V = $lst -> venta;
			$CanGar = $lst -> cant_garantia;
			$Garantia = $lst -> tipo_garantia;

		}

		if ($sUbica == "") {
			$boton = "<p>
			<input type='button' class='ui-button ui-widget ui-state-default ui-corner-all' value='Procesar Cambios'
			OnClick=\"PInventario('" . base_url() . "index.php/cooperativa/PInventario',$i,$o,'" . $marca . "','" . $modelo . "'," . $proveedor . "," . $artefacto . ");\">
			
			<a href='" . base_url() . "index.php/cooperativa/Configurar/x/" . $modelo . "/" . $proveedor_d . "/" . $equipo_d . "/" . $marca . "/" . $Precio_C . "/" . $Precio_V . "/x/" . $CanGar . "/" . $Garantia . "'  metod=POST>
			<input type='button' class='ui-button ui-widget ui-state-default ui-corner-all' value='Agregar Seriales'
			OnClick=\"document.forms['Seriales'].submit();\">
			</a>			</p>
			
			";

		} else {
			if ($iNivel != 2) {
				if ($sSerial != "") {
					$boton = "<p>
					<input type='button' class='ui-button ui-widget ui-state-default ui-corner-all' value='Asosiar Factura'
					OnClick=\"PInventarioAsociar('" . base_url() . "index.php/cooperativa/PInventarioAsociar','" . $sSerial . "');\">
					</p><a href='" . base_url() . "index.php/cooperativa/Inventario/x/" . $modelo . "/" . $proveedor_d . "/" . $equipo_d . "/" . $marca . "/" . $Precio_C . "/" . $Precio_V . "/x/" . $CanGar . "/" . $Garantia . "'  metod=POST>
			<input type='button' class='ui-button ui-widget ui-state-default ui-corner-all' value='Agregar Seriales'
			OnClick=\"document.forms['Seriales'].submit();\">
			</a>	";

				} else {
					$boton = "<p>
					<input type='button' class='ui-button ui-widget ui-state-default ui-corner-all' value='Procesar Cambios'
					OnClick=\"PInventario('" . base_url() . "index.php/cooperativa/PInventario',$i,$o,'" . $marca . "','" . $modelo . "'," . $proveedor . "," . $artefacto . ");\">
				
					<a href='" . base_url() . "index.php/cooperativa/Inventario/x/" . $modelo . "/" . $proveedor_d . "/" . $equipo_d . "/" . $marca . "/" . $Precio_C . "/" . $Precio_V . "/x/" . $CanGar . "/" . $Garantia . "'  metod=POST>
			<input type='button' class='ui-button ui-widget ui-state-default ui-corner-all' value='Agregar Seriales'
			OnClick=\"document.forms['Seriales'].submit();\">
			</a>	
					</p>";
				}
			} else {

				$boton = "";
			}

		}
		$sReporte .= "</tbody></table>";

		$sReporte .= "<br>$boton</center><br><br>
		</form>";

		return $sReporte;

	}

	/**
	 * Actualizar Producto del Inventario
	 * @param CProductos
	 * @return true
	 */
	public function Actualizar($Productos = null) {

		//$serial = $Productos["serial"];
		$this -> db -> where("serial", $Productos["serial"]);
		$this -> db -> update("t_productos", $Productos);
		return true;

	}

	public function Actualizarp($Productos = null) {

		//$serial = $Productos["serial"];
		$this -> db -> where("serial", $Productos["serial"]);
		$this -> db -> update("t_pproductos", $Productos);
		return true;

	}

	public function Modificar($sSerialA, $sSerialN) {

		$this -> db -> from("t_productos");
		$this -> db -> where("serial", $sSerialN);
		$rsTC = $this -> db -> get();

		if ($rsTC -> num_rows() == 0) {
			$data = array("serial" => $sSerialN);
			$this -> db -> where("serial", $sSerialA);
			$this -> db -> update("t_productos", $data);
		}

	}

	public function Eliminar_Serial($sSerial = null, $strPeticion, $strMotivo) {
		$query = $this -> db -> query('SELECT * FROM t_productos WHERE serial="' . $sSerial . '"');
		$cant = $query -> num_rows();

		if ($cant > 0) {
			$this -> db -> where("serial", $sSerial);
			$this -> db -> delete("t_productos");
			$data = array(
			//'id' => null,
			'referencia' => $sSerial, 'tipo' => 10, 'usuario' => $_SESSION['usuario'], 'motivo' => $strMotivo . "(" . $sSerial . ")", 'peticion' => $strPeticion);
			$this -> db -> insert('_th_sistema', $data);
		}

	}

	/**
	 * Usuario que tiene acesso a un inventario
	 */
	public function Que_Usuario($sCedula = "") {
		$this -> db -> select("ubicacion,login");
		$this -> db -> from("t_usuarios");
		$this -> db -> where("documento_id", $sCedula);
		$rs = $this -> db -> get();
		foreach ($rs->result() as $fila) {
			$sUsuario['ubicacion'] = $fila -> ubicacion;
			$sUsuario['login'] = $fila -> login;
		}
		return $sUsuario;
	}

	public function Activar_Ventas() {

		$sQuery = "SELECT motivo 	FROM `t_clientes_creditos`
		WHERE motivo != '-- PRESTAMO --' 	AND motivo != '-- PRESTAMO Y VARIOS--'
		AND motivo != 'VARIOS'  AND motivo != 'CELULAR'		AND motivo != 'TELEVISOR'
		AND motivo != 'NEVERA'";

		$rs = $this -> db -> query($sQuery);
		foreach ($rs->result() as $fila) {

			$Productos["estatus"] = 2;
			$this -> db -> where("serial", $fila -> motivo);
			$this -> db -> update("t_productos", $Productos);
		}
	}

	/**
	 * Retornar Modelos descripcion
	 **/
	public function Buscar_Modelo($modelo = '', $iTipo = null) {//busca modelo del modulo inventario
		$this -> load -> model('CInventario');
		$inven = new CInventario();
		$lst['estatus'] = 0;
		$lst['proveedor'] = '';
		$lst['equipo'] = '';
		$lst['equipoD'] = '';
		$lst['marca'] = '';
		$lst['compra'] = '';
		$lst['venta'] = '';
		$lst['descripcion'] = '';
		$lst['credi_compra'] = '';

		$lst['dia'] = 1;
		$lst['mes'] = 1;
		$lst['ano'] = 2011;
		$lst['tipo_garantia'] = '';
		$lst['cant'] = '';
		$lst['tipo'] = '';
		$lst['foto'] = '';
		$lst['detalle'] = '';
		$lst['modifi'] = '';
		$inventario_id = '';
		$this -> db -> select("t_proveedores.nombre AS n_p, t_artefactos.nombre AS n_a, t_artefactos.artefacto_id as art_id,foto, t_inventario.credi_compra, 
								marca,precio_compra,precio_venta,porcentaje,modelo,t_inventario.inventario_id AS id_inv,t_inventario.detalle,t_inventario.modificado as modifi");
		$this -> db -> from("t_inventario");
		$this -> db -> join("t_proveedores", "t_inventario.proveedor=t_proveedores.proveedor_id");
		//$this -> db -> join("t_productos", "t_productos.inventario_id = t_inventario.inventario_id");
		$this -> db -> join("t_artefactos", "t_artefactos.artefacto_id=t_inventario.artefacto");
		$this -> db -> where("modelo", $modelo);
		$this -> db -> limit(1);
		//$this -> db -> order_by("venta", "ASC");

		$rsList = $this -> db -> get();

		foreach ($rsList->result() as $List) {
			$lst['cant_ser'] = $inven -> Cant_Seriales2($List -> id_inv, $this -> session -> userdata('ubicacion'), '1');
			$lst['proveedor'] = $List -> n_p;
			$lst['equipo'] = $List -> art_id;
			$lst['equipoD'] = $List -> n_a;
			$lst['modifi'] = $List -> modifi;
			$lst['foto'] = $List -> foto;
			$lst['detalle'] = $List -> detalle;
			if ($List -> marca)
				$lst['marca'] = $List -> marca;

			$lst['compra'] = $List -> precio_compra;
			$lst['venta'] = $List -> precio_venta;
			$lst['precio_oficina'] = 0;
			$lst['credi_compra'] = $List -> credi_compra;

			$lst['porcentaje'] = $List -> porcentaje;
			$inventario_id = $List -> id_inv;

		}
		
		if(!isset($iTipo)){
			$strQuery = 'SELECT * FROM t_productos WHERE inventario_id=' . $inventario_id . ' AND version=1';
		}else{
			$strQuery = 'SELECT * FROM t_productos WHERE inventario_id=' . $inventario_id . ' AND version=1 AND estatus_mercancia !=2 AND  	ubicacion =\'' . $this -> session -> userdata('ubicacion') . '\' ORDER BY precio_oficina ASC';
		}
		
		$productos = $this -> db -> query($strQuery);
		$cant = $productos -> num_rows();
		if ($cant > 0) {
			$lst['cant_ser'] = $cant;
			$oCabezera[1] = array("titulo" => "Serial", "atributos" => "width:100px", "buscar" => 0);
			$oCabezera[2] = array("titulo" => "Estatus", "atributos" => "width:10px");
			$oCabezera[3] = array("titulo" => "Descripcion", "atributos" => "width:10px");
			$oCabezera[4] = array("titulo" => "Venta", "atributos" => "width:50px");
			$oCabezera[5] = array("titulo" => "Ubicacion", "atributos" => "width:150px", "buscar"=>0);
			$oCabezera[6] = array("titulo" => "Precio O.", "atributos" => "width:50px");
			$oCabezera[7] = array("titulo" => "Estatus M.", "atributos" => "width:12px");
			$i=0;
			foreach ($productos -> result() as $row) {
				++$i;
				$oFil[$i] = array(
					"1" => $row -> serial, //
					"2" => $row -> estatus, //
					"3" => $row -> descripcion, //
					"4" => number_format($row -> venta,2), //
					"5" => $row -> ubicacion,  //
					"6" => number_format($row -> precio_oficina,2),  //
					"7" => $this -> Estatus_Mercancia($row -> estatus_mercancia) //
				);
				$lst['precio_oficina'] = $row -> precio_oficina;
			}
			$lst['seriales'] = array("Cabezera" => $oCabezera, "Cuerpo" => $oFil, "Paginador" => 10, "Origen" => "json");
		} else {
			$lst['seriales'] = "no";
		}

		return json_encode($lst);
	}

	function Estatus_Mercancia($esta){
		$estus = '';
		switch ($esta) {
			case 0:
				$estus = "DEPOSITO";
				break;
			case 1:
				$estus = "ENTREGADO A OFICINA";
				break;
			case 2:
				$estus = "ENTREGADO A CLIENTE";
				break;
			default:
				
				break;
		}
		return $estus;
	}

	public function Listar_Productosp($i = NULL, $marca = NULL, $modelo = NULL, $proveedor = NULL, $artefacto = NULL, $sUbica = NULL, $iEstatus = NULL, $iNivel = NULL, $sSerial = NULL) {

		$marca_d = '';
		$modelo_d = '';
		$equipo_d = '';
		$proveedor_d = '';
		$Precio_C = 0;
		$Precio_V = 0;
		$CanGar = '';
		$Garantia = '';

		$this -> load -> model("CInventario");
		$this -> load -> model("CListartareas");
		$combo = null;
		$combo = $this -> CListartareas -> Listar_Usuarios_Combo(); ;
		$this -> db -> select("t_pproveedores.nombre AS n_p, t_partefactos.nombre AS n_a,
		serial, compra, venta, fecha_ingreso, cant_garantia, tipo_garantia, ubicacion, estatus");

		$this -> db -> from("t_pinventario");
		$this -> db -> join("t_pproveedores", "t_pinventario.proveedor=t_pproveedores.proveedor_id");
		$this -> db -> join("t_pproductos", "t_pproductos.inventario_id = t_pinventario.inventario_id");
		$this -> db -> join("t_partefactos", "t_partefactos.artefacto_id=t_pinventario.artefacto");

		$this -> db -> where("marca", $marca);
		$this -> db -> where("modelo", $modelo);
		$this -> db -> where("t_pproveedores.proveedor_id", $proveedor);
		$this -> db -> where("t_partefactos.artefacto_id", $artefacto);
		if ($iEstatus != "") {	$this -> db -> where("t_pproductos.estatus", $iEstatus);
		}
		if ($sSerial != "") {	$this -> db -> where("t_pproductos.serial", $sSerial);
		}

		if ($sUbica == '') {
			$sUbicaCab = "<th style='width:130px'>UBICACION</th>";
			$strCompra = "<th>COMPRA</th>";

		} else {

			if ($iNivel != 2) {
				$sUbicaCab = "<th style='width:130px'>UBICACION</th>";
				$strCompra = "<th>COMPRA</th>";
			} else {
				$strCompra = "";
				$sUbicaCab = "";
			}
			if ($sUbica == 'TODOS') {
				$this -> db -> like("t_pproductos.ubicacion");
			} else {
				$this -> db -> where("t_pproductos.ubicacion", $sUbica);
			}

		}
		$sDestino = "# FACTURA";
		if ($sSerial == "") {$sDestino = "DESTINO";
		}
		$rsList = $this -> db -> get();
		$sCon = "";

		$sReporte = "";
		$sReporte .= "

		<form name='frmLista$i' id='frmLista$i' action='" . base_url() . "index.php/cooperativa/PInventariop'>";

		$sReporte .= "<br><center>
		<table style=\"height:18px;width:720px;\" border=0
		class=\"ui-widget ui-widget-content\" cellspacing=\"2\" cellpadding=\"0\"
		name=\"tSeriales$i\" id=\"tSeriales$i\"	>
		<thead><tr class=\"ui-widget-header\" style=\"height:20px;\">";

		$sReporte .= "<th>#</th><th>E</th><th>SERIAL</th>$strCompra<th>VENTA</th><th>ESTATUS
		</th>$sUbicaCab<th style='width:150px'>$sDestino</th><th>GARANTIA</th></tr></thead><tbody>";
		$o = 0;
		$sCombo = "";
		foreach ($rsList->result() as $lst) {

			$o++;

			$usuario = $this -> Que_Usuario($lst -> ubicacion);
			if ($sUbica == "") {
				$sComprar = "<td align=right>" . number_format($lst -> compra, 2, ".", ",") . " Bs.&nbsp;&nbsp;</td>";
				$sCombo = "<td>
				<select id=c$i$o name=c$i$o class='inputxt' style='width:150px' >
				<option value='" . $lst -> ubicacion . "'>" . strtoupper($usuario["login"]) . "</option>
				" . $combo . "
				</select></td>";
			} else {
				if ($iNivel != 2) {
					$sComprar = "<td align=right>" . number_format($lst -> compra, 2, ".", ",") . " Bs.&nbsp;&nbsp;</td>";

					if ($sSerial == "") {
						$sCombo = "<td>
						<select id=c$i$o name=c$i$o class='inputxt' style='width:150px' >
						<option value='" . $lst -> ubicacion . "'>" . strtoupper($usuario["login"]) . "</option>
						" . $combo . "</select></td>";
					} else {
						$sCombo = "<td><input type='text' name='txtnfactura' id='txtnfactura'  class='inputxt' style='width:150px' /></td>";
					}

				} else {
					$sComprar = "";
					$sCombo = "";
				}

			}

			if ($lst -> estatus == 1) {
				$sEstatus = "DISPONIBLE";
			} else {
				$sEstatus = "VENDIDO";
			}

			$sCon = "<tr><td align='center'>$o</td>
			<td align='center'>
			<p><a href=\"#\" onClick=\"Eliminar_Serial_Principal('" . __LOCALWWW__ . "','" . $lst -> serial . "','" . $i . "');\" id=\"dialog_link\"
					class=\"ui-state-default ui-corner-all\"><span class=\"ui-icon ui-icon-circle-minus\">
					</span></a></p>
			</td>
			<td align=left>
			<input type='hidden' value='$lst->serial' id='s$i$o' name='s$i$o' />
			&nbsp;&nbsp;$lst->serial</td>$sComprar
			<td align=right>" . number_format($lst -> venta, 2, ".", ",") . " Bs.&nbsp;&nbsp;</td>
			<td>" . $sEstatus . "</td>
			<td align='center'>" . $usuario["login"] . "</td>" . $sCombo . "
			<td align=center>" . $lst -> cant_garantia . " " . $lst -> tipo_garantia . "</td>
			</tr>";

			$sReporte .= $sCon;

			$equipo_d = $lst -> n_a;
			$proveedor_d = $lst -> n_p;
			$Precio_C = $lst -> compra;
			$Precio_V = $lst -> venta;
			$CanGar = $lst -> cant_garantia;
			$Garantia = $lst -> tipo_garantia;

		}

		if ($sUbica == "") {
			$boton = "<p>
			<input type='button' class='ui-button ui-widget ui-state-default ui-corner-all' value='Procesar Cambios'
			OnClick=\"PInventario('" . base_url() . "index.php/cooperativa/PpInventario',$i,$o,'" . $marca . "','" . $modelo . "'," . $proveedor . "," . $artefacto . ");\">
			
			<a href='" . base_url() . "index.php/cooperativa/inventario_p/x/" . $modelo . "/" . $proveedor_d . "/" . $equipo_d . "/" . $marca . "/" . $Precio_C . "/" . $Precio_V . "/x/" . $CanGar . "/" . $Garantia . "'  metod=POST>
			<input type='button' class='ui-button ui-widget ui-state-default ui-corner-all' value='Agregar Seriales'
			OnClick=\"document.forms['Seriales'].submit();\">
			</a>			</p>
			
			";

		} else {
			if ($iNivel != 2) {
				if ($sSerial != "") {
					$boton = "<p>
					<input type='button' class='ui-button ui-widget ui-state-default ui-corner-all' value='Asosiar Factura'
					OnClick=\"PInventarioAsociar('" . base_url() . "index.php/cooperativa/PInventarioAsociar','" . $sSerial . "');\">
					</p><a href='" . base_url() . "index.php/cooperativa/inventario_p/x/" . $modelo . "/" . $proveedor_d . "/" . $equipo_d . "/" . $marca . "/" . $Precio_C . "/" . $Precio_V . "/x/" . $CanGar . "/" . $Garantia . "'  metod=POST>
			<input type='button' class='ui-button ui-widget ui-state-default ui-corner-all' value='Agregar Seriales'
			OnClick=\"document.forms['Seriales'].submit();\">
			</a>	";

				} else {
					$boton = "<p>
					<input type='button' class='ui-button ui-widget ui-state-default ui-corner-all' value='Procesar Cambios'
					OnClick=\"PInventario('" . base_url() . "index.php/cooperativa/PpInventario',$i,$o,'" . $marca . "','" . $modelo . "'," . $proveedor . "," . $artefacto . ");\">
				
					<a href='" . base_url() . "index.php/cooperativa/inventario_p/x/" . $modelo . "/" . $proveedor_d . "/" . $equipo_d . "/" . $marca . "/" . $Precio_C . "/" . $Precio_V . "/x/" . $CanGar . "/" . $Garantia . "'  metod=POST>
			<input type='button' class='ui-button ui-widget ui-state-default ui-corner-all' value='Agregar Seriales'
			OnClick=\"document.forms['Seriales'].submit();\">
			</a>	
					</p>";
				}
			} else {
				$boton = "";
			}

		}
		$sReporte .= "</tbody></table>";

		$sReporte .= "<br>$boton</center><br><br>
		</form>";

		return $sReporte;

	}

	public function Listar_Productoss($i = NULL, $marca = NULL, $modelo = NULL, $proveedor = NULL, $artefacto = NULL, $sUbica = NULL, $iEstatus = NULL, $iNivel = NULL, $sSerial = NULL) {

		$marca_d = '';
		$modelo_d = '';
		$equipo_d = '';
		$proveedor_d = '';
		$Precio_C = 0;
		$Precio_V = 0;
		$CanGar = '';
		$Garantia = '';

		$this -> load -> model("CInventario");
		$this -> load -> model("CListartareas");
		$combo = null;
		$combo = $this -> CListartareas -> Listar_Usuarios_Combo(); ;
		$this -> db -> select("t_sproveedores.nombre AS n_p, t_sartefactos.nombre AS n_a,
		serial, compra, venta, fecha_ingreso, cant_garantia, tipo_garantia, ubicacion, estatus");

		$this -> db -> from("t_sinventario");
		$this -> db -> join("t_sproveedores", "t_sinventario.proveedor=t_sproveedores.proveedor_id");
		$this -> db -> join("t_sproductos", "t_sproductos.inventario_id = t_sinventario.inventario_id");
		$this -> db -> join("t_sartefactos", "t_sartefactos.artefacto_id=t_sinventario.artefacto");

		$this -> db -> where("marca", $marca);
		$this -> db -> where("modelo", $modelo);
		$this -> db -> where("t_sproveedores.proveedor_id", $proveedor);
		$this -> db -> where("t_sartefactos.artefacto_id", $artefacto);
		if ($iEstatus != "") {	$this -> db -> where("t_sproductos.estatus", $iEstatus);
		}
		if ($sSerial != "") {	$this -> db -> where("t_sproductos.serial", $sSerial);
		}

		if ($sUbica == '') {
			$sUbicaCab = "<th style='width:130px'>UBICACION</th>";
			$strCompra = "<th>COMPRA</th>";

		} else {

			if ($iNivel != 2) {
				$sUbicaCab = "<th style='width:130px'>UBICACION</th>";
				$strCompra = "<th>COMPRA</th>";
			} else {
				$strCompra = "";
				$sUbicaCab = "";
			}
			if ($sUbica == 'TODOS') {
				$this -> db -> like("t_sproductos.ubicacion");
			} else {
				$this -> db -> where("t_sproductos.ubicacion", $sUbica);
			}

		}
		$sDestino = "# FACTURA";
		if ($sSerial == "") {$sDestino = "DESTINO";
		}
		$rsList = $this -> db -> get();
		$sCon = "";

		$sReporte = "";
		$sReporte .= "

		<form name='frmLista$i' id='frmLista$i' action='" . base_url() . "index.php/cooperativa/PInventariop'>";

		$sReporte .= "<br><center>
		<table style=\"height:18px;width:720px;\" border=0
		class=\"ui-widget ui-widget-content\" cellspacing=\"2\" cellpadding=\"0\"
		name=\"tSeriales$i\" id=\"tSeriales$i\"	>
		<thead><tr class=\"ui-widget-header\" style=\"height:20px;\">";

		$sReporte .= "<th>#</th><th>SERIAL</th>$strCompra<th>VENTA</th><th>ESTATUS
		</th>$sUbicaCab<th style='width:150px'>$sDestino</th><th>GARANTIA</th></tr></thead><tbody>";
		$o = 0;
		$sCombo = "";
		foreach ($rsList->result() as $lst) {

			$o++;

			$usuario = $this -> Que_Usuario($lst -> ubicacion);
			if ($sUbica == "") {
				$sComprar = "<td align=right>" . number_format($lst -> compra, 2, ".", ",") . " Bs.&nbsp;&nbsp;</td>";
				$sCombo = "<td>
				<select id=c$i$o name=c$i$o class='inputxt' style='width:150px' >
				<option value='" . $lst -> ubicacion . "'>" . strtoupper($usuario["login"]) . "</option>
				" . $combo . "
				</select></td>";
			} else {
				if ($iNivel != 2) {
					$sComprar = "<td align=right>" . number_format($lst -> compra, 2, ".", ",") . " Bs.&nbsp;&nbsp;</td>";

					if ($sSerial == "") {
						$sCombo = "<td>
						<select id=c$i$o name=c$i$o class='inputxt' style='width:150px' >
						<option value='" . $lst -> ubicacion . "'>" . strtoupper($usuario["login"]) . "</option>
						" . $combo . "</select></td>";
					} else {
						$sCombo = "<td><input type='text' name='txtnfactura' id='txtnfactura'  class='inputxt' style='width:150px' /></td>";
					}

				} else {
					$sComprar = "";
					$sCombo = "";
				}

			}

			if ($lst -> estatus == 1) {
				$sEstatus = "DISPONIBLE";
			} else {
				$sEstatus = "VENDIDO";
			}

			$sCon = "<tr><td align='center'>$o</td>
			<td align=left>
			<input type='hidden' value='$lst->serial' id='s$i$o' name='s$i$o' />
			&nbsp;&nbsp;$lst->serial</td>$sComprar
			<td align=right>" . number_format($lst -> venta, 2, ".", ",") . " Bs.&nbsp;&nbsp;</td>
			<td>" . $sEstatus . "</td>
			<td align='center'>" . $usuario["login"] . "</td>" . $sCombo . "
			<td align=center>" . $lst -> cant_garantia . " " . $lst -> tipo_garantia . "</td>
			</tr>";

			$sReporte .= $sCon;

			$equipo_d = $lst -> n_a;
			$proveedor_d = $lst -> n_p;
			$Precio_C = $lst -> compra;
			$Precio_V = $lst -> venta;
			$CanGar = $lst -> cant_garantia;
			$Garantia = $lst -> tipo_garantia;

		}

		if ($sUbica == "") {
			$boton = "<p>
			<input type='button' class='ui-button ui-widget ui-state-default ui-corner-all' value='Procesar Cambios'
			OnClick=\"PInventario('" . base_url() . "index.php/cooperativa/PInventario',$i,$o,'" . $marca . "','" . $modelo . "'," . $proveedor . "," . $artefacto . ");\">
			
			<a href='" . base_url() . "index.php/cooperativa/inventario_s/x/" . $modelo . "/" . $proveedor_d . "/" . $equipo_d . "/" . $marca . "/" . $Precio_C . "/" . $Precio_V . "/x/" . $CanGar . "/" . $Garantia . "'  metod=POST>
			<input type='button' class='ui-button ui-widget ui-state-default ui-corner-all' value='Agregar Seriales'
			OnClick=\"document.forms['Seriales'].submit();\">
			</a>			</p>
			
			";

		} else {
			if ($iNivel != 2) {
				if ($sSerial != "") {
					$boton = "<p>
					<input type='button' class='ui-button ui-widget ui-state-default ui-corner-all' value='Asosiar Factura'
					OnClick=\"PInventarioAsociar('" . base_url() . "index.php/cooperativa/PInventarioAsociar','" . $sSerial . "');\">
					</p><a href='" . base_url() . "index.php/cooperativa/inventario_s/x/" . $modelo . "/" . $proveedor_d . "/" . $equipo_d . "/" . $marca . "/" . $Precio_C . "/" . $Precio_V . "/x/" . $CanGar . "/" . $Garantia . "'  metod=POST>
			<input type='button' class='ui-button ui-widget ui-state-default ui-corner-all' value='Agregar Seriales'
			OnClick=\"document.forms['Seriales'].submit();\">
			</a>	";

				} else {
					$boton = "<p>
					<input type='button' class='ui-button ui-widget ui-state-default ui-corner-all' value='Procesar Cambios'
					OnClick=\"PInventario('" . base_url() . "index.php/cooperativa/PInventario',$i,$o,'" . $marca . "','" . $modelo . "'," . $proveedor . "," . $artefacto . ");\">
				
					<a href='" . base_url() . "index.php/cooperativa/inventario_s/x/" . $modelo . "/" . $proveedor_d . "/" . $equipo_d . "/" . $marca . "/" . $Precio_C . "/" . $Precio_V . "/x/" . $CanGar . "/" . $Garantia . "'  metod=POST>
			<input type='button' class='ui-button ui-widget ui-state-default ui-corner-all' value='Agregar Seriales'
			OnClick=\"document.forms['Seriales'].submit();\">
			</a>	
					</p>";
				}
			} else {
				$boton = "";
			}

		}
		$sReporte .= "</tbody></table>";

		$sReporte .= "<br>$boton</center><br><br>
		</form>";

		return $sReporte;

	}

}
?>