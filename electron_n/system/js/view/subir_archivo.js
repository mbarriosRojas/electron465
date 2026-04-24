var sImg = sUrl + '/system/img/';
var sImgC = sUrl + '/system/repository/expedientes/';
$(function() {
	$("#id_contenido").hide();
});


function limpiar() {
	for(i=1;i<=9;i++){
		input = $("#d"+i);
		input.replaceWith(input.val('').clone(true));
	}
}

function consultar_datos() {
	strUrl_Proceso = sUrlP + "objCExpediente";
	var id = $("#cedula").val();
	var html = '';
	if (id != '') {
		$.ajax({	
			url : strUrl_Proceso,
			type : 'POST',
			data : 'id=' + id,
			dataType : 'json',
			success : function(json) {
				$("#id_contenido").show();
				dcumento_id = json["cedula"];
				nombre = json['nombre'];
				banco = json["banco_1"];
				banco2 = json["banco_2"];

				imgCedula = json["imgCedula"];
				fecCedula = json["fecCedula"];
				// alert(imgCedula);
				if (json['cedula'] == "NULL" || json['cedula'] == "0") {
					alert(json['cedula']);
					alert('NO SE ENCONTRO REGISTROS ASOCIADOS A LAS C&Eacute;DULA...');
					limpiar();
				} else {
					html += 'Nombre:' + nombre + '<br>';
					$("#bancos").html('');
					$("#facturas").html('');
					$("#cartas").html('');
					$("#cheques").html('');
					$("#bancos").append(new Option(banco, banco));
					$("#bancos").append(new Option(banco2, banco2));
					$.each(json['factura'], function(k, v) {
						$("#facturas").append(new Option(v, v));
						$("#cheques").append(new Option(v, v));
						$("#garantia").append(new Option(v, v));
					});
					$.each(json['nomina_procedencia'], function(k, v) {
						$("#cartas").append(new Option(v, v));
					});

				}
				$("#combos").show();
				$("#datos_cliente").html(html);
				// $("#datos_cliente").show();
			},error : function(err) {
				alert("NO SE PUDO ENCONTRAR LA CEDULA");
				limpiar();
			}
		});
	} else {
		alert('DEBE INGRESAR UNA CEDULA');
	}
	return false;
}

function subir(){
	var cadena = new FormData();
	var elementos = ['cedula','cartas','bancos','facturas','cheques','garantia','revision','fiador','rrif'];
	$.each(elementos, function( index, value ) {
		cadena.append(value,$("#"+value).val());
	});
	for(i=1;i<=9;i++){
		var archivoImagen = document.getElementById("d"+i);
		var imagen = archivoImagen.files[0];
		if(imagen){
			var peso = imagen.size;
			if(peso > 500000){
				alert("El peso de la imagen "+imagen.name+" excede el limite de 500 KBYTES permitidos en el servido. Por favor optimisar la imagen siguiendo la guia publicada en la comunidad.");
				return 0;
			}
		}
		
		cadena.append("d"+i, imagen);
	}
	$('#carga').show();
	$('#boton').hide();
	//return 0;
	$.ajax({
		url : sUrlP + "subir_archivo_ejecuta",
		type : 'POST',
		data : cadena,
		contentType : false,
		processData : false,
		cache : false,
		success : function(msj) {
			limpiar();
			$('#carga').hide();
			$("#boton").show();
			alert(msj);
		}
	});
}