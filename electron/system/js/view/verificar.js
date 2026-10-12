function enviar(){
	var correo = $("#correo").val();
	strUrl_Proceso = sUrlP + "Enviar_Certificacion";
	if (correo != '') {
		$.ajax({
			url : strUrl_Proceso,
			type : 'POST',
			data : "correo="+correo,
			success : function(resp) {
				$("#correo").val('');
				alert(resp);
			},
			error : function(err) {
				alert("NO SE PUDO ENCONTRAR LA CEDULA O correo no exite;");
				alert(err['responseText']);
				/*$.each(err, function(k, v) {
					alert(k+":"+v);
				});*/
			}
		});
	}else{
		alert('Debe ingresar un correo electronico');
		return 0;
	}
}
