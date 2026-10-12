$(function() {
	Crear();
});
function Crear() {
	strUrl_Proceso = sUrlP + "GV_Archivo";
	$.ajax({
		url : strUrl_Proceso,
		dataType : "json",
		success : function(oBj) {//alert(oBj);
			vis = new GVista(oBj, 'formulario', 'Archivo General', 'botones');
			vis.SetNombre("Carchivo");
			vis.SetCeldas(3);
			vis.SetBotones(1);
			vis.Generar();
		}
	});
}

function guardar() {
	var cedula = $("#cedula").val();
	var banco = $("#banco option:selected").val();
	var nomina = $("#nomina option:selected").val();
	var narchivo = $("#narchivo").val();
	var tipo = $("#tipo option:selected").val();
	var empresa = $("#empresa option:selected").val();
	var contratos = $("#contratos").val();
	var ubc = $("#ubicacion option:selected").val();
	strUrl_Proceso = sUrlP + "Guarda_Carchivo";
	$.ajax({
		url : strUrl_Proceso,
		data : 'cedula=' + cedula + '&banco=' + banco + '&nomina=' + nomina + '&numero=' + narchivo + '&tipo=' + tipo + '&empresa=' + empresa + '&contratos=' + contratos+ '&ubicacion=' + ubc,
		type : 'POST',
		success : function(msj) {
			$("#msj_alertas").html(msj);
			$("#msj_alertas").dialog('open');
			limpiar();
		}
	});
	return false;

}

function consulta_cliente() {
	var cedula = $("#cedula").val();
	strUrl_Proceso = sUrlP + "Consulta_Carchivo";
	$.ajax({
		url : strUrl_Proceso,
		data : 'cedula=' + cedula,
		type : 'POST',
		dataType : "json",
		success : function(oEsq) {
			$("#historial").html("");
			if (oEsq.msj == 1) {
				Grid = new TGrid(oEsq, 'historial', 'Historial Cliente '+cedula);
				Grid.SetName("Historial");
				Grid.Generar();
			} else {
				$("#historial").html("No posee registros en el historial");
			}

		}
	});
	return false;

}

function limpiar() {
	$("#cedula").val('');
	$("#banco > option[value='']").attr("selected", "selected");
	$("#nomina > option[value='']").attr("selected", "selected");
	$("#narchivo").val('');
	$("#tipo > option[value='']").attr("selected", "selected");
	$("#empresa > option[value='']").attr("selected", "selected");
	$("#contratos").val('');
}