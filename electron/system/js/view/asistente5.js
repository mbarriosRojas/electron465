$(function() {
	/**
	 * Lista de Modelos
	 */

	Listar($("#txtFact").val());

});

function Listar(fac){
	strUrl_Proceso = sUrlP + "ListarContratosPendientes/" + fac ;
	$.ajax({
		url : strUrl_Proceso,
		dataType : "json",
		success : function(oEsq) {
			Grid = new TGrid(oEsq,'listar','');
			Grid.SetName("listar");			
			Grid.SetNumeracion(true);
			Grid.Generar();
			
		}
	});
}
