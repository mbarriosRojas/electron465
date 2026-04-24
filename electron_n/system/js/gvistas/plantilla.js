var sUrl = 'http://' + window.location.hostname + '/cooperativaelectron';
var sUrlP = sUrl + '/index.php/cooperativa/';
var sImg = sUrl + '/system/img/';

$(function() {
	
});

function Generar(){
	var funcion = $("#txtFuncion").val();
	var archivo = $("#txtArch").val();
	$.ajax({
		url : sUrlP + "Genera_Plantilla",
		data : "funcion=" + funcion + "&archivo=" + archivo,
		type : 'POST',
		success : function(cadena) {//alert(oBj);
			alert(cadena);
		}
	});
}
