function Crea_Objeto(elemento, tipo,objeto) {
	var identifica = elemento.id;
	var celda = document.getElementById(identifica);
	var trae_valor = $('#' + identifica).text();
	var id_separados = identifica.split('_');
	var div_oculto = document.createElement('div');
	div_oculto.style.display = "none";
	div_oculto.innerHTML = trae_valor;
	elemento.innerHTML = "<div style='display:none;'>" + trae_valor + "</div>";
	switch(tipo) {
		case 'textArea':
			var area = document.createElement('textarea');
			area.id = identifica + '_0';
			area.innerHTML = trae_valor;
			area.onblur = function() {
				Restaura_Objeto(this, identifica);
			}
			elemento.appendChild(area);
			break;
		case 'combo':
			var combo = document.createElement('select');
			combo.id = identifica + "_0";
			combo.style.width = "100%";
			combo.onblur = function() {
				Restaura_Objeto(this, identifica,1);
			}
			var contador = 0;
			$.each(objeto[id_separados[1]], function(contenido,id) {
				//combo.options[contador] = new Option(contenido.etiqueta, contenido.valor);
				combo.options[contador] = new Option(id,contenido);
				if (contenido.valor == trae_valor) {
					combo[contador].selected = 'true';
				}
				contador++;
			});
			elemento.appendChild(combo);
			break;
		case 'fecha':
			var trae_fecha = trae_valor.split("-");
			var dia = 0;
			var ano = 0;
			var formato = 0;
			if (eval(trae_fecha[0]) > 2000) {
				dia = trae_fecha[2];
				ano = trae_fecha[0];
				formato = 1;
			} else {
				dia = trae_fecha[0];
				ano = trae_fecha[2];
			}
			//COMBO PARA EL DIA
			var div_fecha = document.createElement('div');
			div_fecha.id = "div_" + identifica;
			div_fecha.style.cssText = "padding: 0 0 0 0; width:100%; height:100%; float:right;";
			var selec_dia = document.createElement('select');
			selec_dia.id = identifica + "_0";
			selec_dia.style.width = '40px';
			var contador = 1;
			for ( contador = 1; contador < 32; contador++) {
				selec_dia.options[contador - 1] = new Option(contador, contador);
				if (eval(dia) == contador) {
					selec_dia[contador - 1].selected = 'true';
				}
			}
			div_fecha.appendChild(selec_dia);
			//COMBO PARA EL MES
			var selec_mes = document.createElement('select');
			selec_mes.id = identifica + "_1";
			selec_mes.style.width = '40px';
			for ( contador = 1; contador < 13; contador++) {
				selec_mes.options[contador - 1] = new Option(contador, contador);
				if (eval(trae_fecha[1]) == contador) {
					selec_mes[contador - 1].selected = 'true';
				}
			}
			div_fecha.appendChild(selec_mes);
			//COMBO PARA EL AÑO
			var selec_ano = document.createElement('select');
			selec_ano.id = identifica + "_2";
			selec_ano.style.width = '60px';
			var contador = 1;
			for ( contador = 1; contador < 12; contador++) {
				selec_ano.options[contador - 1] = new Option(contador + 2004, contador + 2004);
				if (eval(ano) == contador + 2004) {
					selec_ano[contador - 1].selected = 'true';
				}
			}
			div_fecha.appendChild(selec_ano);
			//BOTON DE ACEPTAR NUEVA FECHA
			var btn_a = document.createElement('input');
			btn_a.type = "button";
			btn_a.value = 'A';
			btn_a.onclick = function() {
				Restaura_Fecha(this, identifica, formato);
			}
			div_fecha.appendChild(btn_a);
			elemento.appendChild(div_fecha);

			break;
		case 'texto':
			var input_text = document.createElement('input');
			input_text.type = "text";
			input_text.id = identifica + "_0";
			input_text.value = trae_valor;
			input_text.style.width = "100%";
			input_text.onblur = function() {
				Restaura_Objeto(this, identifica);
			}
			input_text.onkeypress = function(e){
				key = e.keyCode || e.which;
				if(key == 13){
					alert(1);	
				}
			}
			elemento.appendChild(input_text);
			break;
		case 'calendario':
			var trae_fecha = trae_valor.split("-");
			var dia = 0;
			var ano = 0;
			var formato = 'yy-mm-dd';
			if (eval(trae_fecha[0]) > 2000) {
				dia = trae_fecha[2];
				ano = trae_fecha[0];
				formato = 'yy-mm-dd';
			} else {
				dia = trae_fecha[0];
				ano = trae_fecha[2];
			}
			var calendario = document.createElement('input');
			calendario.type = 'text';
			calendario.val = trae_fecha;
			calendario.id = identifica + '_0';
			calendario.change = function(){
				Restaura_Objeto(calendario, identifica);
			}
			elemento.appendChild(calendario);
			/*var btn_a = document.createElement('input');
			btn_a.type = "button";
			btn_a.value = 'A';
			btn_a.onclick = function() {
				Restaura_Objeto(calendario, identifica);
			}
			elemento.appendChild(btn_a);*/
			$("#" + identifica + "_0").datepicker({
				changeMonth : true,
				changeYear : true,
				dateFormat : formato
			});
			//$( "#"+identifica+"_0" ).datepicker( "option", "dateFormat", formato );
			$("#" + identifica + "_0").datepicker({
				currentText : trae_valor
			});
			break;
		default:
			break;
	}
	$('#' + identifica + '_0').focus();
}

function muestra_detalle(fila, tabla,boton) {
	fila_tr = document.getElementById(fila);
	filaO = document.getElementById(fila + '0');
	//divO = document.getElementById('div_'+fila+'0');
	if (filaO.style.display != "none") {
		filaO.style.display = "none";
		$("#"+boton).attr("src",sImg + "botones/abrir.png");

	} else {
		filaO.style.display = "";
		fila_tr.style.display = "";
		$("#"+boton).attr("src",sImg + "botones/cerrar.png");
	}
}

function muestra_detalle_post(funcion, fila, parametro, id_tabla, tipo,detalle){
	cont=$('#'+fila+'_Detalle0_img').val();
	if(cont != undefined){
		var arreglo_json= new Array();
		if (parametro == null || parametro == '') {//ENVIAR TODAS LAS COLUMNAS A LA FUNCION A EJECUTA
			$("#" + fila).each(function(index) {
				var i = 0;
				var ubica_radio = 0;
				$(this).children("td").each(function(index2) {
					if (this.id.charAt(0) == 'R') {//PARA OBTENER EL VALOR EN CASO DE OPTION BUTTON
						if (ubica_radio == 0) {
							ubica_radio = 1;
							var rad = 'radio_' + this.id.substring(1);
							var cont = $("input[name='" + rad + "']:checked").val();
							arreglo_json[i]=  cont;
							i++;
						}
					} else {//OBTENER EL VALOR TEXT DE LA CELDA
						if (this.childNodes.length > 1) {
							var algo = this.childNodes.item(1).innerHTML;
							arreglo_json[i] = algo;
							i++;
						} else {
							var hijo_unico = document.getElementById(id).childNodes.item(0);
							//alert(hijo_unico.type);
							if(hijo_unico.type == 'select-one'){
								//alert(hijo_unico.value);
								arreglo_json[i]=hijo_unico.value;
							}else{
								var texto2 = document.getElementById(id).childNodes.item(0).value;
								if(texto2 != undefined)  arreglo_json[i] = texto2;
								else arreglo_json[i] = $(this).text();
								i++;
							}
							
						}
					}

				});

			});

		} else {//ENVIAR UN ARREGLO DE VALORES ESPECIFICADOS EN EL OBJETO BOTON
			var valores = parametro.split(',');
			var ubica_radio = 0;
			for (var j = 0; j < valores.length; j++) {//VERIFICAR QUE COLUMNAS SE VAN A ENVIAR A LA FUNCION A EJECUTAR
				fila_r = document.getElementById(fila).getElementsByTagName('td');
				var pos = parseInt(valores[j]) - 1;
				var id = fila_r[pos].id;
				if (id.charAt(0) == 'R') {
					if (ubica_radio == 0) {//PARA OBTENER EL VALOR EN CASO DE OPTION BUTTON
						ubica_radio = 1;
						var rad = 'radio_' + id.substring(1);
						var cont = $("input[name='" + rad + "']:checked").val();
						arreglo_json[j] = cont;
						//reacomoda los valores del objeto parametro
						if (valores[j + 1] != null) {
							if (valores[j + 1] > valores[j]) {
								valores[j + 1]++;
							}
						}
					}
				} else {//OBTENER EL VALOR TEXT DE LA CELDA
					var hijos = document.getElementById(id).childNodes.length;
					if (hijos > 1) {
						var texto = document.getElementById(id).childNodes.item(1).innerHTML;//alert(texto);
						arreglo_json[j] = texto;
					} else {
						var hijo_unico = document.getElementById(id).childNodes.item(0);
						//alert(hijo_unico.type);
						if(hijo_unico.type == 'select-one'){
							//alert(hijo_unico.value);
							arreglo_json[j]=hijo_unico.value;
						}else{
							var texto2 = document.getElementById(id).childNodes.item(0).value;
							if(texto2 != undefined) arreglo_json[j]=texto2;
							else arreglo_json[j]=$("#" + id).text();
						}
					}
				}
			}
		}
		var arreglo_json2 = JSON.stringify(arreglo_json);
		
		if(tipo == 'html'){
			$.ajax({
				url : sUrlP + funcion,
				type : "POST",
				data : "objeto=" + arreglo_json2,
				success : function(html) {
					$("#"+fila+"_Detalle0").html(html);	
				}
			});	
		}else{
			$.ajax({
				url : sUrlP + funcion,
				type : "POST",
				data : "objeto=" + arreglo_json2,
				dataType : "json",
				success : function(oEsqD) {
					if(oEsqD.compuesto != '' && oEsqD.compuesto != null){
						$("#"+fila+"_Detalle0").html('');
						$.each(oEsqD.objetos, function(sId_Objeto, sObjeto) {
							GridDetalle = new TGrid(sObjeto,fila+"_Detalle0",'');
							GridDetalle.SetName(fila+"_Detalle_"+sId_Objeto);
							GridDetalle.SetEstilo('detalle');
							GridDetalle.SetLimpiar();
							GridDetalle.SetNumeracion(true);
							GridDetalle.SetXls(detalle);
							GridDetalle.Generar();
						});	
						//$("#"+oEsqD,fila+"_Detalle0").html('');
					}else{
						GridDetalle = new TGrid(oEsqD,fila+"_Detalle0",'');
						GridDetalle.SetName(fila+"_Detalle");
						GridDetalle.SetEstilo('detalle');
						GridDetalle.SetXls_D(detalle);
						GridDetalle.SetNumeracion(true);
						GridDetalle.Generar();
					}
				}
			});
		}
	}
	return 0;
}

function Ejecutar(funcion, fila, parametro, id_tabla,eliminaFila) {
	var arreglo_json= new Array();
	
	if (parametro == null || parametro == '') {//ENVIAR TODAS LAS COLUMNAS A LA FUNCION A EJECUTA
		$("#" + fila).each(function(index) {
			var i = 0;
			var ubica_radio = 0;
			$(this).children("td").each(function(index2) {
				if (this.id.charAt(0) == 'R') {//PARA OBTENER EL VALOR EN CASO DE OPTION BUTTON
					if (ubica_radio == 0) {
						ubica_radio = 1;
						var rad = 'radio_' + this.id.substring(1);
						var cont = $("input[name='" + rad + "']:checked").val();
						arreglo_json[i]=  cont;
						i++;
					}
				} else {//OBTENER EL VALOR TEXT DE LA CELDA
					if (this.childNodes.length > 1) {
						var algo = this.childNodes.item(1).innerHTML;
						arreglo_json[i] = algo;
						i++;
					} else {
						var hijo_unico = document.getElementById(id).childNodes.item(0);
						//alert(hijo_unico.type);
						if(hijo_unico.type == 'select-one'){
							//alert(hijo_unico.value);
							arreglo_json[i]=hijo_unico.value;
						}else{
							var texto2 = document.getElementById(id).childNodes.item(0).value;
							if(texto2 != undefined)  arreglo_json[i] = texto2;
							else arreglo_json[i] = $(this).text();
							i++;
						}
						
					}
				}

			});

		});

	} else {//ENVIAR UN ARREGLO DE VALORES ESPECIFICADOS EN EL OBJETO BOTON
		var valores = parametro.split(',');
		var ubica_radio = 0;
		for (var j = 0; j < valores.length; j++) {//VERIFICAR QUE COLUMNAS SE VAN A ENVIAR A LA FUNCION A EJECUTAR
			fila_r = document.getElementById(fila).getElementsByTagName('td');
			var pos = parseInt(valores[j]) - 1;
			var id = fila_r[pos].id;
			if (id.charAt(0) == 'R') {
				if (ubica_radio == 0) {//PARA OBTENER EL VALOR EN CASO DE OPTION BUTTON
					ubica_radio = 1;
					var rad = 'radio_' + id.substring(1);
					var cont = $("input[name='" + rad + "']:checked").val();
					arreglo_json[j] = cont;
					//reacomoda los valores del objeto parametro
					if (valores[j + 1] != null) {
						if (valores[j + 1] > valores[j]) {
							valores[j + 1]++;
						}
					}
				}
			} else {//OBTENER EL VALOR TEXT DE LA CELDA
				var hijos = document.getElementById(id).childNodes.length;
				if (hijos > 1) {
					var texto = document.getElementById(id).childNodes.item(1).innerHTML;//alert(texto);
					arreglo_json[j] = texto;
				} else {
					var hijo_unico = document.getElementById(id).childNodes.item(0);
					//alert(hijo_unico.type);
					if(hijo_unico.type == 'select-one'){
						//alert(hijo_unico.value);
						arreglo_json[j]=hijo_unico.value;
					}else{
						var texto2 = document.getElementById(id).childNodes.item(0).value;
						if(texto2 != undefined) arreglo_json[j]=texto2;
						else arreglo_json[j]=$("#" + id).text();
					}
				}
			}
		}
	}
	
	var arreglo_json2 = JSON.stringify(arreglo_json);
	//alert(arreglo_json2);
	//return 0;
	$.ajax({
		url : sUrlP + funcion,
		type : "POST",
		data : "objeto=" + arreglo_json2,
		success : function(html) {
			$('#msj_alertas').html(html);
			$('#msj_alertas').dialog({
				title : 'Proceso Exitoso'
			});
			$('#msj_alertas').dialog('open');
		}
	});
	if(eliminaFila == 1){
		elimina_fila(fila, id_tabla);	
	}
	
	return 0;
}

function EjecutarEnlace(funcion, fila, parametro, id_tabla,metodo,enlace,ventana,eliminaFila) {
	//alert(funcion+'//'+fila+'//'+parametro+'//'+id_tabla+'//'+metodo+'//'+enlace);
	var arreglo_json= new Array();
	if (parametro == null || parametro == '') {//ENVIAR TODAS LAS COLUMNAS A LA FUNCION A EJECUTA
		$("#" + fila).each(function(index) {
			var i = 0;
			var ubica_radio = 0;
			$(this).children("td").each(function(index2) {
				if (this.id.charAt(0) == 'R') {//PARA OBTENER EL VALOR EN CASO DE OPTION BUTTON
					if (ubica_radio == 0) {
						ubica_radio = 1;
						var rad = 'radio_' + this.id.substring(1);
						var cont = $("input[name='" + rad + "']:checked").val();
						arreglo_json[i]=  cont;
						i++;
					}
				} else {//OBTENER EL VALOR TEXT DE LA CELDA
					if (this.childNodes.length > 1) {
						var algo = this.childNodes.item(1).innerHTML;
						arreglo_json[i] = algo;
						i++;
					} else {
						var hijo_unico = document.getElementById(id).childNodes.item(0);
						//alert(hijo_unico.type);
						if(hijo_unico.type == 'select-one'){
							//alert(hijo_unico.value);
							arreglo_json[i]=hijo_unico.value;
						}else{
							var texto2 = document.getElementById(id).childNodes.item(0).value;
							if(texto2 != undefined)  arreglo_json[i] = texto2;
							else arreglo_json[i] = $(this).text();
							i++;
						}
						
					}
				}

			});

		});

	} else {//ENVIAR UN ARREGLO DE VALORES ESPECIFICADOS EN EL OBJETO BOTON
		var valores = parametro.split(',');
		var ubica_radio = 0;
		for (var j = 0; j < valores.length; j++) {//VERIFICAR QUE COLUMNAS SE VAN A ENVIAR A LA FUNCION A EJECUTAR
			fila_r = document.getElementById(fila).getElementsByTagName('td');
			var pos = parseInt(valores[j]) - 1;
			var id = fila_r[pos].id;
			if (id.charAt(0) == 'R') {
				if (ubica_radio == 0) {//PARA OBTENER EL VALOR EN CASO DE OPTION BUTTON
					ubica_radio = 1;
					var rad = 'radio_' + id.substring(1);
					var cont = $("input[name='" + rad + "']:checked").val();
					arreglo_json[j] = cont;
					//reacomoda los valores del objeto parametro
					if (valores[j + 1] != null) {
						if (valores[j + 1] > valores[j]) {
							valores[j + 1]++;
						}
					}
				}
			} else {//OBTENER EL VALOR TEXT DE LA CELDA
				var hijos = document.getElementById(id).childNodes.length;
				if (hijos > 1) {
					var texto = document.getElementById(id).childNodes.item(1).innerHTML;//alert(texto);
					arreglo_json[j] = texto;
				} else {
					var hijo_unico = document.getElementById(id).childNodes.item(0);
					//alert(hijo_unico.type);
					if(hijo_unico.type == 'select-one'){
						//alert(hijo_unico.value);
						arreglo_json[j]=hijo_unico.value;
					}else{
						var texto2 = document.getElementById(id).childNodes.item(0).value;
						if(texto2 != undefined) arreglo_json[j]=texto2;
						else arreglo_json[j]=$("#" + id).text();
					}
				}
			}
		}
	}
	if(metodo == 1){
		var parametro_ejecuta='';
		for(k=0;k<arreglo_json.length;k++){
			if(k==0){
				parametro_ejecuta += '(';
			}else{
				parametro_ejecuta += ' , ';
			}
			parametro_ejecuta += '\'' + arreglo_json[k] + '\'';
		}
		parametro_ejecuta += ');';
		
		var cadena = funcion+ parametro_ejecuta;
		eval(cadena);	
	}
	if(metodo == 2){
		var parametro_ejecuta='';
		for(k=0;k<arreglo_json.length;k++){
			parametro_ejecuta += '/'+arreglo_json[k];
		}
		if(enlace != 'enlace_fila'){
			enlace.setAttribute('href' , sUrlP+funcion+parametro_ejecuta);
		}else{
			if(ventana == ""){
				location.href=sUrlP+funcion+parametro_ejecuta;
			}else{
				var ejecruta = sUrlP+funcion+parametro_ejecuta;
				window.open(ejecruta,"ventana1","toolbar=0,location=1,menubar=0,scrollbars=1,resizable=1")
			}
		}
		//
	}
    //alert(eliminaFila);
    if(eliminaFila == 1){
        elimina_fila(fila, id_tabla);
    }
	
	
	return 0;
}

function elimina_fila(fila, tablaP) {
	var tabla = document.getElementById(tablaP + 'tbody');
	var pos = fila.split('_');
	//alert(pos[0] + '/' + fila);
	fila_borrar = document.getElementById(fila);
	tabla.removeChild(fila_borrar);
	if (document.getElementById(fila + '_Detalle')) {
		var fila2_borrar = document.getElementById(fila + '_Detalle');	
		tabla.removeChild(fila2_borrar);
	}

	//tabla.deleteRow(fila);3000000
}

function Restaura_Objeto(elemento, id_td, tip) {
	var id_objeto = elemento.id;
	
	if(tip != 1){
		var trae_valor = $('#' + id_objeto).val();
		$('#' + id_td).html(trae_valor);	
	}else{
		var trae_valor = $('#' + id_objeto + " option:selected").val();
		var trae_oculto = $('#' + id_objeto + " option:selected").text();
		$('#' + id_td).html("<div>"+trae_oculto+"</div><div style='display:none;'>"+trae_valor+"</div>");
	}
	
}

function Restaura_Fecha(elemento, id_td, forma) {
	var id_objeto = elemento.id;
	var dia = $('#' + id_td + '_0').val();
	var mes = $('#' + id_td + '_1').val();
	var ano = $('#' + id_td + '_2').val();
	if (dia < 10)
		dia = '0' + dia;
	if (mes < 10)
		mes = '0' + mes;
	var regresa_valor = '';
	if (forma == 0)
		regresa_valor = dia + '-' + mes + '-' + ano;
	else
		regresa_valor = ano + '-' + mes + '-' + dia;
	$('#' + id_td).html(regresa_valor);
}

function Revisa_Casilla(celda) {
	var check = '#' + celda + '_0';
	var div = '#div_' + celda;
	if ($(check).is(':checked')) {
		$(div).html("true");
	} else {
		$(div).html("false");
	}
}

function Revisa_Radio(texto, celda) {
	var lbl = '#lbl_' + celda;
	$(lbl).html(texto);
}

function Muestra_Filtro(filtro) {
	$("#" + filtro.id).show();
}

function Oculta_Filtro(filtro) {
	$("#" + filtro.id).hide();
}

function Oculta_Columna(col, tabla) {
	fila = document.getElementById(tabla).getElementsByTagName('tr');
	for ( i = 1; i < fila.length; i++) {
		if (fila[i].getElementsByTagName('td')[col] && fila[i].getElementsByTagName('td')[col].id !='') {
			fila[i].getElementsByTagName('td')[col].style.display = 'none';
		}

	}

}

function filtra(txt, t, pos) {
	/*filas = t.getElementsByTagName('tr');
	if (txt != '') {
		for ( i = 1; ele = filas[i]; i++) {
			identificador = ele.id;
			sufijo = identificador.split('_');
			//alert(sufijo[sufijo.length - 1]);
			if (sufijo[sufijo.length - 1] != "Detalle") {
				if (ele.getElementsByTagName('td')[pos] && ele.getElementsByTagName('td')[pos].id != '') {
					texto = ele.getElementsByTagName('td')[pos].innerHTML.toUpperCase();
					posi = (texto.indexOf(txt.toUpperCase()) == 0);
					ele.style.display = (posi) ? '' : 'none';
				}
			} else {
				ele.style.display = 'none';
			}
		}
	} else {
		p.SetPagina(p.pagActual);
	}*/
	if (txt != '') {
		for(i = 1;i < t.rows.length; i++){
			sufijo = t.rows[i].id.split('_');
			if (sufijo[sufijo.length - 1] != "Detalle") {
				if (t.rows[i].getElementsByTagName('td')[pos] && t.rows[i].getElementsByTagName('td')[pos].id != '') {
					texto = t.rows[i].getElementsByTagName('td')[pos].innerHTML.toUpperCase();
					posi = (texto.indexOf(txt.toUpperCase()) == 0);
					t.rows[i].style.display = (posi) ? '' : 'none';
				}
			} else {
				t.rows[i].style.display = 'none';
			}
		}
	}else {
		P.SetPagina(P.pagActual);
	}
}
