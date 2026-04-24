$(function() {
	$("#mbuzon").removeClass('active');
	$("#mreporte").addClass('active');
	Vtxt();
	$(".dialogo").dialog({
		modal : true,
		autoOpen : false,
		position : 'center',
		hide : 'explode',
		show : 'slide',
		width : 600,
		height : 300
	});
	
});



function Vtxt() {
	$("#Respuesta").html("NO EXISTEN ARCHIVOS PROCESADOS...<br>");

	$.ajax({
		url : sUrlP + "LtxtEnviados",
		type : "POST",
		dataType : "json",
		success : function(oEsq) {
			Grid = new TGrid(oEsq, 'Respuesta', '');
			Grid.SetXls(true);
			Grid.SetNumeracion(true);
			Grid.SetName("Respuesta");
			Grid.SetDetalle();
			Grid.Generar();			
		}
	});
}






