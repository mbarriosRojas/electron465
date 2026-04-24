$(function() {
	CMercancia();
	CEntregas();
	tipo = $("#tipoV").val();
	$('#msj_alertas').dialog({
		modal : true,
		autoOpen : false,
		width : 240,
		height : 240,
		buttons : {
			"Cerrar" : function() {
				$(this).dialog("close");
			}
		}
	});
	$("#txtDescripcion").autocomplete({
		source : function(request, response) {
			$.ajax({
				type : "POST",
				url : sUrlP + "/M_Json_Mercancia",
				data : "nombre=" + $("#txtDescripcion").val() + "&tipo=" + tipo,
				dataType : "json",
				success : function(data) {
					response($.map(data.nombres, function(item) {
						return {
							label : item,
							value : item
						}
					}));
				},
			});
		}
	});
	$("#txtDescripcionS").autocomplete({
		source : function(request, response) {
			$.ajax({
				type : "POST",
				url : sUrlP + "/M_Json_Mercancia",
				data : "nombre=" + $("#txtDescripcionS").val() + "&tipo=" + tipo,
				dataType : "json",
				success : function(data) {
					response($.map(data.nombres, function(item) {
						return {
							label : item,
							value : item
						}
					}));
				},
			});
		}
	});
	
});

function GCodigo() {
	$.ajax({
		url : sUrlP + "GCodigo_Mercancia",
		type : 'post',
		success : function(html) {
			$('#txtCodigo').val(html);
			$('#txtCodigo').attr("disabled", true);
		}
	});
}

function Guardar_Mercancia() {
	codigo = $("#txtCodigo").val();
	serial = $("#txtSerial").val();
	descrip = $("#txtDescripcion").val();
	marca = $("#txtMarca").val();
	modelo = $("#txtModelo").val();
	proveedor = $("#txtProveedor").val();
	ubica = $("#lstUbicacion > option[value=1]").val();
	preciov = $("#txtPrecioVenta").val();
	precioc = $("#txtPrecioCompra").val();
	cant = $("#txtCantidad").val();
	tipo = $("#tipoV").val();
	//alert(codigo + "//" + descrip + "//" + marca + "//" + modelo + "//" + proveedor + "//" + ubica + "//" + costouni);
	if (codigo == '' || descrip == '' || marca == '' || modelo == '' || proveedor == '' || preciov == '' || cant == '' || serial == '' || precioc == '') {
		alert("Debe ingresar todos los datos para poder Guardar....");
	} else {
		//alert(1);return 0;
		$.ajax({
			url : sUrlP + "Guardar_Mercancia",
			data : "codigo=" + codigo + "&descrip=" + descrip + "&marca=" + marca + "&modelo=" + modelo + "&proveedor=" + proveedor + "&ubica=" + ubica + "&precioc=" + precioc + "&preciov=" + preciov +"&cantidad=" + cant + "&serial=" + serial + "&tipo=" + tipo ,
			type : 'post',
			success : function(html) {
				Limpiar();
				CMercancia();
				$("#msj_alertas").html(html);
				$("#msj_alertas").dialog('open');
				$('#txtCodigo').attr("enabled", true);
				
			}
		});
	}
}

function Limpiar(){
	$("#txtDescripcion").val('');
	$("#txtMarca").val('');
	$("#txtModelo").val('');
	$("#txtProveedor").val('');
	$("#lstUbicacion").val('');
	$("#txtPrecioCompra").val('');
	$("#txtPrecioVenta").val('');
	$("#txtSerial").val('');
	$("#txtCantidad").val('');
	$("#txtCodigo").val('');
}

function Limpiar2(){
	$("#txtDescripcionS").val('');
	$("#txtMarcaS").val('');
	$("#txtModeloS").val('');
	$("#txtProveedorS").val('');
	$("#lstUbicacionS").val('');
	$("#txtPrecioCompraS").val('');
	$("#txtPrecioVentaS").val('');
	$("#txtSerialS").val('');
	$("#txtCantidadS").val('');
	$("#txtCodigoS").val('');
}

function CEntregas() {
	tipo = $("#tipoV").val();
	$.ajax({
		url : sUrlP + "Listar_Entregas",
		data : "tipo=" + tipo,
		type : "POST",
		dataType : "json",
		success : function(oEsq) {//alert(oEsq);
			Grid = new TGrid(oEsq,'Reporte_Entregas','Mercancia Entregada');
			Grid.SetXls(true);
			Grid.SetNumeracion(true);
			Grid.SetName("Entregas");
			Grid.SetDetalle();
			Grid.Generar();
		}
	});
}


function CMercancia() {
	tipo = $("#tipoV").val();
	$.ajax({
		url : sUrlP + "ListarGrid_Mercancia",
		type : "POST",
		data : "tipo=" + tipo,
		dataType : "json",
		success : function(oEsq) {
			Grid = new TGrid(oEsq,'Actualizar','Mercancia En Existencia');
			Grid.SetXls(true);
			Grid.SetNumeracion(true);
			Grid.SetName("Actualizar");
			Grid.SetDetalle();
			Grid.Generar();
		}
	});
}

function Consultar_Mercancia(){
	$.ajax({
		url : sUrlP + "Consulta_Mercancia",
		data: "nombre=" + $("#txtDescripcionS").val(),
		type : 'post',
		dataType : 'json',
		success : function(json) {
			
			if(json['codigo'] != undefined){
				$("#txtCodigoS").val(json['codigo']);
				$("#txtModeloS").val(json['modelo']);
				$("#txtMarcaS").val(json['marca']);
				$("#txtProveedorS").val(json['marca']);
				$("#txtPrecioCompraS").val(json['precioc']);
				$("#txtPrecioVentaS").val(json['preciov']);
			}else{
				$("#msj_alertas").html('<h2>No existe mercancia con esta descrici&oacute;n....</h2>');
				$("#txtDescripcionS").val('');
				$("#msj_alertas").dialog('open');
			}
		}
	
	});
}

function Guardar_Serial() {
	codigo = $("#txtCodigoS").val();
	serial = $("#txtSerialS").val();
	descrip = $("#txtDescripcionS").val();
	marca = $("#txtMarcaS").val();
	modelo = $("#txtModeloS").val();
	proveedor = $("#txtProveedorS").val();
	ubica = $("#lstUbicacionS > option[value=1]").val();
	preciov = $("#txtPrecioVentaS").val();
	precioc = $("#txtPrecioCompraS").val();
	cant = $("#txtCantidadS").val();
	tipo = $("#tipoV").val();
	//alert(codigo + "//" + descrip + "//" + marca + "//" + modelo + "//" + proveedor + "//" + ubica + "//" + costouni);
	if (codigo == '' || descrip == '' || marca == '' || modelo == '' || proveedor == '' || preciov == '' || cant == '' || serial == '' || precioc == '') {
		alert("Debe ingresar todos los datos para poder Guardar....");
	} else {
		$.ajax({
			url : sUrlP + "Guardar_Mercancia_Serial",
			data : "codigo=" + codigo + "&descrip=" + descrip + "&marca=" + marca + "&modelo=" + modelo + "&proveedor=" + proveedor + "&ubica=" + ubica + "&precioc=" + precioc + "&preciov=" + preciov +"&cantidad=" + cant + "&serial=" + serial + "&tipo=" + tipo,
			type : 'post',
			success : function(html) {alert(html);
				Limpiar2();
				CMercancia();
				$("#msj_alertas").html(html);
				$("#msj_alertas").dialog('open');
				$('#txtCodigo').attr("enabled", true);
			}
		});
	}
}
