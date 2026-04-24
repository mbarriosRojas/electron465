$(function() {
	Filtro_Factura();
	Filtro_Contrato();
	$(".dialogo").dialog({
		modal : true,
		autoOpen : false,
		position : 'top',
		hide : 'explode',
		show : 'slide',
		width : 600,
		height : 340
	});
	$('#carga_busqueda').dialog({
        modal: true,
        autoOpen: false,
        width: 260,
        height: 160,
        
	});
	$("#msj_alertas").dialog({
		modal : true,
		autoOpen : false,
		position : 'top',
		hide : 'explode',
		show : 'slide',
		width : 600,
		height : 400,
		buttons : {
			"Cerrar" : function() {
				$(this).html('');
				$(this).dialog("close");
			}
		}
	});
	$("#ffactura").dialog({
		buttons : {
			"Generar" : function() {
				CFactura();
				$(this).dialog("close");
			},
			"Cerrar" : function() {
				$(this).dialog("close");
			}
		}
	});
	
	$("#fcontrato").dialog({
		buttons : {
			"Generar" : function() {
				CContrato();
				$(this).dialog("close");
			},
			"Cerrar" : function() {
				$(this).dialog("close");
			}
		}
	});
		 
});

function muestra_div(elemento) {
	$("#" + elemento).dialog('open');
}

function Filtro_Factura() {
	strUrl_Proceso = sUrlP + "GV_Filtro_Rep_FacturaG";
	vis = new GVista(strUrl_Proceso, 'ffactura', 'Filtro Facturas','');
	vis.Obtener_Json();
    vis.AsignarCeldas(2);
    vis.Generar();
}

function Filtro_Contrato() {
	strUrl_Proceso = sUrlP + "GV_Filtro_Rep_ContratoG";
	vis = new GVista(strUrl_Proceso, 'fcontrato', 'Filtro Contratos','');
	vis.Obtener_Json();
    vis.AsignarCeldas(2);
    vis.Generar();
}

function CFactura(){
	var banco = $("#fbanco option:selected").val();
	var esta = $("#festatus option:selected").val();
	var cond = $("#fcond option:selected").val();
	var datos = "banco="+banco+"&estatus="+esta+"&condicion="+cond;
	//alert(datos)
	$("#carga_busqueda").dialog('open');
	$.ajax({
		url : sUrlP + "RFactura_General",
		type : "POST",
		data : datos,
		dataType : "json",
		success : function(oEsq) {
			//alert(oEsq['resp']);
			Grid = new TGrid(oEsq, 'Reportes', 'REPORTE GENERAL DE FACTURAS');
			Grid.SetXls(true);
			Grid.SetNumeracion(true);
			Grid.SetName("Facturas");
			Grid.SetDetalle();
			
			Grid.Generar();
			
			$("#carga_busqueda").dialog('close');
		}
	});
}

function CContrato(){
	var banco = $("#cbanco option:selected").val();
	var cond = $("#ccond option:selected").val();
	var tipo = $("#ctipo option:selected").val();
	var empr = $("#cempresa option:selected").val();
	var peri = $("#cperi option:selected").val();
	
	var datos = "banco="+banco+"&condicion="+cond+"&tipo="+tipo+"&empr="+empr+"&peri="+peri;
	//alert(datos)
	$("#carga_busqueda").dialog('open');
	$.ajax({
		url : sUrlP + "RContrato_General",
		type : "POST",
		data : datos,
		dataType : "json",
		success : function(oEsq) {
			//alert(oEsq);
			Grid1 = new TGrid(oEsq, 'Reportes', 'REPORTE GENERAL DE CONTRATOS');
			Grid1.SetXls(true);
			Grid1.SetNumeracion(true);
			Grid1.SetName("Facturas");
			Grid1.SetDetalle();
			
			Grid1.Generar();
			
			$("#carga_busqueda").dialog('close');
		}
	});
}
