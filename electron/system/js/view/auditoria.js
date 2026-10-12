$(function() {
	Crear();
	listar();
});

function Crear() {
	strUrl_Proceso = sUrlP + "GV_archivosAuditoria";
	$.ajax({
		url : strUrl_Proceso,
		dataType : "json",
		success : function(oBj) {//alert(oBj);
			vis = new GVista(oBj, 'formulario', 'Asociar TXT');
			vis.AsignarNombre("auditoria");
			vis.AsignarCeldas(3);
			vis.AsignarBotones(1);
			vis.Generar();
		}
	});
	return 0;
}

function listar(){
	strUrl_Proceso = sUrlP + "listar_archivosAuditoria";
	$.ajax({
		url : strUrl_Proceso,
		dataType : "json",
		success : function(oBj) {//alert(oBj);
			if(oBj.msj){
				Grid = new TGrid(oBj, 'lista', 'Lista de descripciones');
				Grid.SetXls(true);
				Grid.SetNumeracion(true);
				Grid.SetName("lista");
				Grid.Generar();
			}else{
				alert("No existen archivos pendientes por procesar");
			}
		}
	});
	return 0;
}