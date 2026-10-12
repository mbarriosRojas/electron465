function Consultar(cedula){
	var sUrlE = sUrl + '/index.php/electron/';
	
	$.ajax({
		url : sUrlE + "Consultar_Cliente",
		type : "POST",
		data : "cedula="+cedula,
		dataType : "json",
		success : function(oEsq) {
			//alert(oEsq);
			Grid = new TGrid(oEsq,'tabla',"");
			Grid.SetNumeracion(true);
			//Grid.SetEstiloC("OVERFLOW: auto;height:200px;");
			Grid.SetName("Reportes");
			Grid.SetDetalle();
			Grid.Generar();
			
		}
	});
}
