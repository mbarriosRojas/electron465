/**
 * @author Carlos Peña
 *
 */

$(function() {
	$("input:button").button();
	$("#mcliente").removeClass('active');
	
	/**
	 * Lista de Proveedores
	 */
		$('#msj_alertas').dialog({
		modal : true,
		autoOpen : false,
		width : 250,
		height : 150,
	});

});
function Verifica() {
	var cedula = $("#txtCedula").val();
	var factura = $("#txtFactura").val();
	if(cedula == '' || factura == ''){
		alert("Debe ingresar cedula y factura para comprobar");
		return 0;
	}
	$.ajax({
		url : sUrlP + "Verifica_Pronto_Pago",
		type : "POST",
		data : "cedula=" + cedula + "&factura=" + factura,
		success : function(data) {
			$("#txtCedula").val('');
			$("#txtFactura").val('');
			$("#msj_alertas").html(data);
			$("#msj_alertas").dialog('open');
		}
	});
}
