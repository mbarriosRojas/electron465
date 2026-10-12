$(function() {
	ubicacion();
});

function ubicacion(){
	$.ajax({
		url : sUrlP + "Listar_ubica",
		dataType : "json",
		success : function(data) {
			//alert(data);
			$.each(data, function(item, valor) {
				$("#ubica").append(new Option(valor, valor));
			});
		}
	});
	
}

function Modificar_Ubicacion() {
	var factura = $("#factura").val();
	var ubica =  $("#ubica").val();
	$("#factura").val('');
	if(factura == ''){
		alert('Debe ingresar factura...');
		return false;
	}
	$.ajax({
		url : sUrlP + 'Mod_Ubi_Factura',
		type : "POST",
		data : "factura=" + factura + "&ubica=" + ubica,
		success : function(html) {
			$("#msj_alertas").html(html);
			$("#msj_alertas").dialog('open');

		},
		error : function(html) {
			alert(html);
		},
	});
}