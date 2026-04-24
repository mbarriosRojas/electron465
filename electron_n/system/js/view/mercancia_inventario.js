$(function() {
	CMercancia();
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
	
});

function CMercancia() {
	$.ajax({
		url : sUrlP + "ListarGrid_Productos",
		type : "POST",
		dataType : "json",
		success : function(oEsq) {
			//alert(oEsq);
			Grid = new TGrid(oEsq,'lista','Mercancia En Existencia');
			Grid.SetName("Productos");
			Grid.SetNumeracion(true);
			Grid.SetXls(true);
			Grid.SetXls_Detalle(true);
			Grid.Generar();
		}
	});
}