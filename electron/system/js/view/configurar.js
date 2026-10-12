$(function() {
	$("#mbuzon").removeClass('active');
	$("#mconfigurar").addClass('active');

	
});

function Modificar_Usuario() {
	var nombre = $("#txtNombre").val();
	var clave =  $("#txtClave").val();
	var correo = $("#txtCorreo").val();
	$("#txtNombre").val('');
	$("#txtClave").val(''); 
	$("#txtCorreo").val('');
	$.ajax({
		url : sUrlP + 'Modificar_Usuario',
		type : "POST",
		data : "nombre=" + nombre + "&clave=" + clave + "&correo=" + correo,
		success : function(html) {
			$("#msj_alertas").html(html);
			$("#msj_alertas").dialog('open');

		},
		error : function(html) {
			alert(html);
		},
	});
}