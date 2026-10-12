function cargar(pos,nombre,ced,cert){
	var strUrl_Proceso = sUrlP + "Mueve_Expediente";
	var carp = $("#"+pos+"combo").val();
	//alert(carp);
	var datos = 'carpeta=' + carp + '&nombre=' + nombre + '&cedula=' + ced + '&cert='+cert;
	$.ajax({
		url : strUrl_Proceso,
		type : 'POST',
		data : datos,
		// dataType : 'json',
		success : function(html) {
			alert(html);
			consultar_imagenes();
		}
	});
	
}

function consultar_imagenes() {
	
	strUrl_Proceso = sUrlP + "Busca_Imagenes";
	sImgR = sUrl + '/system/repository/revision/';
	var id = $("#lugar").val();
	var ced = $("#ced").val();
	var html = '';
	$("#imagenes0").html('');
	if (id != '') {
		$.ajax({
			url : strUrl_Proceso,
			type : 'POST',
			data : 'lugar=' + id + '&ced=' + ced ,
			dataType : 'json',
			success : function(json) {
				var img = '<div class="single first"><br>CEDULA:'+json['cedula']+'<br>Nombre:'+json['nombre']+'<br>';
				 var i=0;
				$.each(json['imagenes'], function(k, v) {
					i++;
					img += '<table><tr><td><a href="'+sImgR+json['ruta']+"/"+v+'" rel="lightbox[docu1]" title="">';
					img += '<img src="'+sImgR+json['ruta']+"/"+v+'" alt="" style="width:100px;height:100px;" /></a></td>';
					img += '<td><SELECT id="'+i+'combo"><option value="t_expcedula|personales">CEDULA</option><option value="t_expcartas|cartas">CARTAS</option>';
					img += '<option value="t_expbanco|bancos">BANCOS</option>';
					img += '<option value="t_expfiador|fiador">FIADOR</option><option value="t_exprif|rif">RESPALDO RIF</option></select></td>';
					img += '<td><center><img src="'+ sImg +'botones/aceptar1.png" onclick="cargar('+ i +',\''+ v +'\',\''+ ced +'\',\''+ id +'\');" ></img></center></td></tr></table>';
				});
				img += '</div>';
				$("#imagenes0").append(img);
			},
			error : function(err) {
				alert("NO SE PUDO ENCONTRAR LA CEDULA O EL DIRECCTORIO DEL CERTIFICADO");
				limpiar();
			}
		});
	} else {
		alert('DEBE INGRESAR UNA CERTIFICADO');
	}
}