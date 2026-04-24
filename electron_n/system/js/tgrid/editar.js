function Editar_Tabla(nombre_aux, funcion, parametro) {
	var arreglo_filas= new Array();
	var fecha = new Date();
	if (parametro == null || parametro == '') {
		$("#tabla_tgrid" + nombre_aux + " tbody tr").each(function(index) {
			var ubica_radio = 0;
			arr_id = this.id.split('_');
			if (arr_id[arr_id.length-1] != 'Detalle' && arr_id[arr_id.length-1] != '' && arr_id[arr_id.length-1] == nombre_aux) {
				var arreglo_columnas= new Array();
				$(this).children("td").each(function(index2) {
					if (this.id.charAt(0) == 'R') {//PARA OBTENER EL VALOR EN CASO DE OPTION BUTTON
						if (ubica_radio == 0) {
							ubica_radio = 1;
							var rad = 'radio_' + this.id.substring(1);
							var cont = $("input[name='" + rad + "']:checked").val();
							arreglo_columnas.push(cont);
						}
					} else {//OBTENER EL VALOR TEXT DE LA CELDA
						if (this.childNodes.length > 1) {
							var algo = this.childNodes.item(1).innerHTML;
							arreglo_columnas.push(algo);
						} else {
							var hijo_unico = document.getElementById(id).childNodes.item(0);
							//alert(hijo_unico.type);
							if(hijo_unico.type == 'select-one'){
								//alert(hijo_unico.value);
								arreglo_columnas.push(hijo_unico.value);
							}else{
								var texto2 = document.getElementById(id).childNodes.item(0).value;
								if(texto2 != undefined)  arreglo_columnas.push(texto2);
								else arreglo_columnas.push($(this).text());
							}
						}
					}
				});
				arreglo_filas.push(arreglo_columnas);
			}
		});
	}else {//ENVIAR UN ARREGLO DE VALORES ESPECIFICADOS EN EL OBJETO BOTON
		var valores = parametro.split(',');
		var ubica_radio = 0;
		$("#tabla_tgrid" + nombre_aux + " tbody tr").each(function(index) {
			var arreglo_columnas= new Array();
			for ( var j = 0; j < valores.length; j++) {// VERIFICAR QUE COLUMNAS VAN A ENVIAR A LA FUNCION A EJECUTAR
				fila_r = document.getElementById(this.id).getElementsByTagName('td');
				var pos = parseInt(valores[j]) - 1;
				var id = fila_r[pos].id;
				if (id.charAt(0) == 'R') {
					if (ubica_radio == 0) {// PARA OBTENER EL VALOR EN CASO DE OPTION BUTTON
						ubica_radio = 1;
						var rad = 'radio_' + id.substring(1);
						var cont = $("input[name='" + rad+ "']:checked").val();
						arreglo_columnas.push(cont);
						// reacomoda los valores del objeto	parametro
						if (valores[j + 1] != null) {
							if (valores[j + 1] > valores[j]) {
								valores[j + 1]++;
							}
						}
					}
				} else {// OBTENER EL VALOR TEXT DE LA CELDA
					var hijos = document.getElementById(id).childNodes.length;
					if (hijos > 1) {
						var texto = document.getElementById(id).childNodes.item(1).innerHTML;// alert(texto);
						arreglo_columnas.push(texto);
					} else {
						var hijo_unico = document.getElementById(id).childNodes.item(0);
						//alert(hijo_unico.type);
						if(hijo_unico.type == 'select-one'){
							//alert(hijo_unico.value);
							arreglo_columnas.push(hijo_unico.value);
						}else{
							var texto2 = document.getElementById(id).childNodes.item(0).value;
							if(texto2 != undefined)	arreglo_columnas.push(texto2);
							else arreglo_columnas.push($("#"+id).text());	
						}
					}
				}
			}
			arreglo_filas.push(arreglo_columnas);
		});
	}
	var arreglo_json2 = JSON.stringify(arreglo_filas);
	//alert(arreglo_json2);
	$.ajax({
		url : sUrlP + funcion,
		type : "POST",
		data : "objeto=" + arreglo_json2,
		success : function(data_r) {
			$("#msj_alertas").html(data_r);
			$("#msj_alertas").dialog('open');
		}
	});
}