/**
 * Desarrollado por: Judelvis Antonio Rivas Perdomo
 * Fecha Creacion: 02 de Junio de 2013
 */

/**
 * @author JUDELVIS ANTONIO RIVAS PERDOMO AND CARLOS ENRIQUE PEÑA ALBARRAN
 * @returns {Elemeto} Arreglo de elementos contendor de valores
 * @class
 * @classdesc Clase para almacenar valores usados al generar la vista
 */
var Elemento = function() {
	/**
	 * @description Id del objeto
	 */
	this.id = '';
	/**
	 * @description Tipo del objeto
	 */
	this.tipo = '';
	/**
	 * @description Contenido del objeto
	 */
	this.valor = '';
	/**
	 * @description Almacea si el elemento es index o no
	 */
	this.index = '';

	/**
	 * @description Obtiene valor id de la clase Elemento
	 * @returns {id}
	 */
	this.ObtenerId = function() {
		return this.id;
	};
	/**
	 * @description Asigna valor id de la clase Elemento
	 * @param {id}
	 *            sValor Id a asignar al objeto
	 */
	this.AsignarId = function(sValor) {
		this.id = sValor;
	};
	/**
	 * @description Asigna valor tipo de la clase Elemento
	 * @returns {tipo}
	 */
	this.ObtenerTipo = function() {
		return this.tipo;
	};
	/**
	 * @description Asigna valor tipo de la clase Elemento
	 * @param {tipo}
	 *            sValor Tipo a asignar al objeto
	 */
	this.AsignarTipo = function(sValor) {
		this.tipo = sValor;
	};
	/**
	 * @description Obtiene valor id de la clase Elemento
	 * @returns {valor}
	 */
	this.ObtnerValor = function() {
		return this.valor;
	};
	/**
	 * @description Asigna valor contenido de la clase Elemento
	 * @param {valor}
	 *            sValor Contenido a asignar al objeto
	 */
	this.AsiganrValor = function(sValor) {
		this.valor = sValor;
	};
	/**
	 * @description Obtiene valor id de la clase Elemento
	 * @returns {boolean}
	 */
	this.ObtenerIndex = function() {
		return this.index;
	};
	/**
	 * @description Asigna valor index de la clase Elemento
	 * @param {index}
	 *            sValor IndeX a asignar al objeto
	 */
	this.AsignarIndex = function(sValor) {
		this.index = sValor;
	};
	/**
	 * @param {Elemento}
	 *            Ele Elemento a asignar
	 * @description Asigna valores a los componentes de la Clase
	 * @returns {Elemento}
	 */
	this.Asignar = function(Ele) {
		this.id = Ele.id;
		this.tipo = Ele.tipo;
		this.valor = Ele.valor;
		this.index = Ele.index;
	};

};

/**
 * @author JUDELVIS ANTONIO RIVAS PERDOMO AND CARLOS ENRIQUE PEÑA ALBARRAN
 * @param {json}
 *            oEsq Esquema json para generar la vista
 * @param {string}
 *            div Id del div html donde se va a generar la vista
 * @param {string}
 *            sTitulo Titulo de la vista
 * @returns {GVista} Vista html generada
 * @class
 * @classdesc Clase para generar vista a traves de objeto json.
 */
var GVista = function(oEsq, div, sTitulo, sParametros) {
	/**
	 * @description Variable clon del objeto this usado en los procesos de
	 *              ciclos de repeticion
	 */
	aux = this;
	/**
	 * Obtener Objero Json desde ruta
	 * 
	 * @param {string}
	 *            sRuta
	 * @returns {boolean}
	 */
	this.Obtener_Json = function() {
		var result = '';
		$.ajax({
			url : oEsq,
			data : sParametros,
			type : 'POST',
			dataType : "json",
			async : false,
			success : function(oJson) {
				// alert(oJson);
				result = oJson;
			},
			error : function(error) {
				if (TODO_ERROR != undefined && TODO_ERROR == true) {
					var er = JSON.stringify(error);
					$("#msj_alertas").html(er);
					$("#msj_alertas").dialog('open');
				}
			}
		});
		return result;

	};

	/**
	 * @description Almacena si contenido de objeto es un nuevo registro
	 */
	this.NRegistro = false;
	/**
	 * @description Objeto esquema json
	 */

	if (typeof oEsq == 'object') {
		this.Esq = oEsq;
	} else {
		this.Esq = this.Obtener_Json();
	}
	/**
	 * @description Creacion del formulario de datos
	 */
	this.Formulario = document.createElement('form');
	this.Formulario.setAttribute("method", "POST");
	this.Formulario.setAttribute("action", "#");
	this.Formulario.id = "GP_vista_form";
	this.Formulario.onsubmit = function() {
		return aux.Guardar();
	};
	/**
	 * @description Creacion de div donde se genera la vista
	 */
	this.DivPrincipal = document.getElementById(div);
	//this.DivPrincipal.id = "GP_vista_div";
	this.DivCampos = document.createElement('div');
	this.DivCampos.id = "DivCampos_" + sTitulo;
	this.DivBotones = document.createElement('div');
	this.DivBotones.id = "DivBotones_" + sTitulo;

	this.Formulario2 = document.createElement('div');
	this.Formulario2.style.cssText = 'backround-color:#000;';
	/**
	 * @description Titulo de la vista
	 */
	this.Titulo = sTitulo;
	/**
	 * @description Nombre del formulario de la vista
	 */
	this.Nombre = '';
	/**
	 * @description Especifica si se debe limpiar los campos
	 */
	this.Limpiar = 0;
	/**
	 * @description Especifica numero de celdas para los elementos del
	 *              formulario
	 */
	this.CFilas = 1;
	/**
	 * @description Especifica numero de celdas para los Botones del formulario
	 */
	this.BFilas = 1;
	/**
	 * @description Tabla donde se generan los elementos del formulario
	 */
	this.TCampos = document.createElement("table");
	/**
	 * @description Tabla donde se generan los botones del formulario
	 */
	this.TBotones = document.createElement("table");
	/**
	 * @description Arreglo tipo Elemento para almacenar los elementos de la
	 *              vista
	 * @type Elemento
	 */
	this.Arr_Campos = new Array();
	/**
	 * @description Arreglo tipo Elemento para almacenar los elementos de la
	 *              consulta
	 * @type Elemento
	 */
	this.Arr_Busca = new Array();
	/**
	 * @description Especifica que se debe limpiar los campos
	 */
	this.AsignarLimpiar = function() {
		this.Limpiar = 1;
	};
	/**
	 * @description Asigna nombre al formulario
	 * @param {string}
	 *            sNombre Nombre a asignar
	 */
	this.AsignarNombre = function(sNombre) {
		this.Nombre = sNombre;
	};
	/**
	 * @description Obtiene nombre del formulario
	 */
	this.ObtenerNombre = function() {
		return this.Nombre;
	};
	/**
	 * @description Asigna Numero de celdas para los objetos de formulario
	 * @param {int}
	 *            iCelda Numero de celdas
	 */
	this.AsignarCeldas = function(iCelda) {
		this.CFilas = iCelda;
	};
	/**
	 * @description Asigna Numero de celdas para los botones de formulario
	 * @param {int}
	 *            iBtn Numero de celdas
	 */
	this.AsignarBotones = function(iBtn) {
		this.BFilas = iBtn;
	};
	/**
	 * obtiene arreglo de elementos creados en la vista
	 * 
	 * @returns {array}
	 */
	this.ObtnerCampos = function() {
		return this.Arr_Campos;
	};
	/**
	 * funcion de guardado generico ruta: se especifica en el parametro fguardar
	 * del objeto json
	 * 
	 * @returns {false}
	 */
	this.Guardar = function() {
		// variables auxiliares para asignacion de objetos a enviar
		var datos = new Object();
		var datosM = new Object();
		var envia = new Object();
		/**
		 * ciclo de repeticion para obtener valores actuales del formulario y
		 * comparacion con el objeto de los valores de la consulta en caso de
		 * existir
		 */
		for ( var j = 0; j < this.Arr_Campos.length; j++) {
			// id del elemento a evaluar
			id = this.Arr_Campos[j].id;
			// asignacion del valor al arreglo de campos dependiendo del tipo de
			// objeto
			switch (this.Arr_Campos[j].tipo) {
			case 'combo':
				this.Arr_Campos[j].valor = $('#' + id + ' option:selected')
						.val();
				break;
			default:
				this.Arr_Campos[j].valor = $('#' + id).val();
				break;
			}

			datos[id] = this.Arr_Campos[j].valor;
			/**
			 * evaluar si campo es index o primary y si es distinto al del valor
			 * obtenido en la consulta para los casos de modificacion no
			 * insercion
			 */
			if (this.Arr_Campos[j].valor != this.Arr_Busca[j].valor
					|| this.Arr_Campos[j].index == 'index'
					|| this.Arr_Campos[j].index == 'primary') {
				datosM[id] = this.Arr_Campos[j].valor;
			}
		}
		/**
		 * conversion de arreglos de elementos a json
		 * 
		 * @type
		 * @exp;JSON@call;stringify
		 */
		var json_campos = JSON.stringify(this.Arr_Campos);
		var json_busca = JSON.stringify(this.Arr_Busca);
		var json_datos = JSON.stringify(datos);
		var json_datosM = JSON.stringify(datosM);
		// verifica si es un caso de insercion o de modificacion de registros
		if (this.NRegistro == true) {
			envia = json_datos;
		} else {
			if (json_campos == json_busca) {
				alert("No se realizaron cambios");
				return false;
			}
			envia = json_datosM;
		}
		// alert(envia);
		$.ajax({
			async : true,
			url : sUrlP + aux.Esq.fguardar,
			data : "datos=" + envia,
			type : 'POST',
			success : function(msj) {
				$("#msj_alertas").html(msj);
				$("#msj_alertas").dialog('open');
				aux.Limpiar("");
			},
			error : function(error) {
				if (TODO_ERROR != undefined && TODO_ERROR == true) {
					$("#msj_alertas").html(error);
					$("#msj_alertas").dialog('open');
				}
			}
		});
		return false;

	};
	/**
	 * @desc Funcion de consulta generica <br>
	 *       Ruta: Se especifica en el parametro fcosulta del objeto json
	 * @param {string}
	 *            sClave Valor a consultar
	 * @param {string}
	 *            sIden Campo donde consultar
	 * @returns {Boolean}
	 */
	this.Consultar = function(sClave, sIden) {
		$.ajax({
			url : sUrlP + aux.Esq.fconsulta,
			data : sIden + '=' + sClave + '&ref=' + sTitulo,
			type : 'POST',
			dataType : "json",
			async : false,
			success : function(con) {
				if (con['error'] == 0) {
					for ( var j = 0; j < aux.Arr_Busca.length; j++) {
						id = aux.Arr_Busca[j].id;
						switch (aux.Arr_Busca[j].tipo) {
						case 'combo':

							/*alert($('#' + id + ' option:selected').val() + '//'
									+ id + "//" + con[id]);*/
							$('#' + id + ' option:selected').removeAttr('selected');
							$('#' + id + ' option[value="' + con[id] + '"]').attr("selected", true);
							//alert($('#' + id + ' option:selected').val() + '//'	+ id);
							aux.Arr_Busca[j].valor = $('#' + id + ' option:selected').val();
							break;
						default:
							$("#" + id).val(con[id]);
							aux.Arr_Busca[j].valor = $('#' + id).val();
							break;
						}
					}
					aux.NRegistro = false;
				} else {
					aux.Limpiar(sIden);
					aux.NRegistro = true;
				}

			},
			error : function(error) {
				if (TODO_ERROR != undefined && TODO_ERROR == true) {
					var er = JSON.stringify(error);
					$("#msj_alertas").html(er);
					$("#msj_alertas").dialog('open');
				}
			}
		});
		return false;

	};

	/**
	 * limpiar los objetos de la vista
	 * 
	 * @param {string}
	 *            sClave
	 * @returns {boolean}
	 */
	this.Limpiar = function(sClave) {
		for ( var j = 0; j < this.Arr_Campos.length; j++) {
			id = this.Arr_Campos[j].id;
			if (sClave != id) {
				switch (this.Arr_Campos[j].tipo) {
				case 'combo':
					$('#' + id + ' option[value=""]').attr("selected", true);
					break;
				default:
					$("#" + id).val('');
					break;
				}
			}
		}
	};
	
	/**
	 * @desc Funcion de combo dependiente generica <br>
	 *       Ruta: Se especifica en el parametro fcosulta del objeto json
	 * @param {string}
	 *            sClave Valor a consultar
	 * @param {string}
	 *            sIden Campo donde consultar
	 * @returns {Boolean}
	 */
	this.Dependiente = function(sHijo, sFuncion,sPadre) {
		$.ajax({
			url : sUrlP + sFuncion,
			data : 'id=' + $("#"+sPadre+" option:selected").val(),
			type : 'POST',
			dataType : "json",
			async : false,
			success : function(resp) {
				$('#'+sHijo).html('');
				$('#'+sHijo).append(new Option('SELECCIONE', '', true, true));
				$.each(resp, function(id, texto) {
					$('#'+sHijo).append(new Option(texto, id, true, true));
				});
				$('#' + sHijo + ' option[value=""]').change();
				$('#' + sHijo + ' option[value=""]').attr("selected", true);
				
			},
			error : function(error) {
				if (TODO_ERROR != undefined && TODO_ERROR == true) {
					var er = JSON.stringify(error);
					$("#msj_alertas").html(er);
					$("#msj_alertas").dialog('open');
				}
			}
		});
		return false;

	};
	
	this.Completar = function(request, response,sRta) {
		$.ajax({
			type : "POST",
			url : sUrlP+sRta,
			data: "id="+request.term,
			dataType : "json",
			async : false,
			success : function(data) {
				response($.map(data.respuesta, function(item) {
					alert(item);
					return {
						label : item,
						value : item
					}
				}));
			},
		});
	};

	/**
	 * @description Genera la vista con los valores configurados
	 * @returns {Gvista}
	 */
	this.Generar = function() {
		var cmp = this.Arr_Campos;
		var bus = this.Arr_Busca;
		var oEsquema = this.Esq;
		var completar = new Array();
		var completar2 = new Array();

		// inicializando variables

		if (this.Limpiar === 0) {
			$("#" + this.DivPrincipal.id).html('');
		}
		var titulo_principal = document.createElement('h2');
		if(oEsquema.titulo != '' || oEsquema.titulo != null || oEsquema.titulo != undefined){
			titulo_principal.innerHTML = oEsquema.titulo;
		}else{
			titulo_principal.innerHTML = this.Titulo;
		}
		
		this.DivPrincipal.appendChild(titulo_principal);
		this.DivPrincipal.appendChild(this.Formulario);
		// asignado el div en el cual se va a construir el formulario
		var div_campos = this.DivCampos;
		// asignado el div en el cual se va a construir los botones
		var div_botones = this.DivBotones;
		this.Formulario.appendChild(div_campos);
		this.Formulario.appendChild(div_botones);
		div_campos.appendChild(this.TCampos);
		div_botones.appendChild(this.TBotones);
		this.TCampos.id = "TCampos" + this.ObtenerNombre();
		this.TBotones.id = "TBotones" + this.ObtenerNombre();
		// De los Cuerpo
		var c_tabla_campos = document.createElement('tBody');
		c_tabla_campos.id = this.TCampos.id + 'tbody';
		this.TCampos.appendChild(c_tabla_campos);
		var c_tabla_botones = document.createElement('tBody');
		c_tabla_botones.id = this.TBotones.id + 'tbody';
		this.TBotones.appendChild(c_tabla_botones);
		// Lectura de la Campos
		var contador_celdas = 0;
		var nombre_aux = this.ObtenerNombre();
		var total = this.CFilas;
		var evaluar = new Array();
		var ceva = 0;
		var i = 0;
		var self = this;
		$.each(oEsquema.campos, function(sId_Campo, sArreglo) {
			var elecmp = new Elemento();
			var elecmp2 = new Elemento();
			elecmp.AsignarId(sArreglo.id);
			elecmp.AsignarTipo(sArreglo.tipo);
			elecmp2.AsignarId(sArreglo.id);
			elecmp2.AsignarTipo(sArreglo.tipo);
			// alert(sArreglo.index);
			if (sArreglo.clave == 'index' || sArreglo.clave == 'primary') {
				elecmp.AsignarIndex(sArreglo.clave);
				elecmp2.AsignarIndex(sArreglo.clave);
			}
			cmp.push(elecmp);
			bus.push(elecmp2);
			i++;
			if (contador_celdas === 0 || contador_celdas > total
					|| sArreglo.tipo == 'textarea') {
				contador_celdas = 1;
				c_tabla_campos.insertRow(c_tabla_campos.rows.length);
			}
			var td = document.createElement('td');
			td.id = 'cel' + sId_Campo + nombre_aux;
			// aumenta contador para celdas
			var cadena_tipo = sArreglo.tipo;
			if (cadena_tipo != null) {
				var obj_celda = null;
				switch (cadena_tipo) {
				case 'texto':
					obj_celda = document.createElement('input');
					obj_celda.type = 'text';
					obj_celda.style.cssText = sArreglo.estilo;
					obj_celda.setAttribute('placeholder', sArreglo.etiqueta);
					break;
				case 'textarea':
					obj_celda = document.createElement('textarea');
					obj_celda.style.cssText = sArreglo.estilo;
					obj_celda.setAttribute('placeholder', sArreglo.etiqueta);
					sArreglo.multicelda = 1;
					break;
				case 'combo':
					obj_celda = document.createElement('select');
					obj_celda.setAttribute('placeholder', sArreglo.etiqueta);

					obj_celda.style.cssText = sArreglo.estilo;
					var contador = 0;
					$.each(sArreglo.elementos,
							function(id, contenido) {

								obj_celda.options[contador] = new Option(
										contenido, id);
								contador++;
							});

					break;
				case 'lista':
					obj_celda = document.createElement('select');
					obj_celda.setAttribute('placeholder', sArreglo.etiqueta);
					obj_celda.setAttribute('multiple', 'multiple');
					obj_celda.style.cssText = sArreglo.estilo;
					break;
				case 'numero':
					obj_celda = document.createElement('input');
					obj_celda.type = 'text';
					obj_celda.style.cssText = sArreglo.estilo;
					obj_celda.setAttribute('placeholder', sArreglo.etiqueta);
					obj_celda.onkeypress = function(event) {
						return soloNumeros(event);
					};

					break;
				case 'calendario':
					obj_celda = document.createElement('input');
					obj_celda.type = 'text';
					obj_celda.style.cssText = sArreglo.estilo;
					obj_celda.setAttribute('placeholder', sArreglo.etiqueta);
					obj_celda.id = sArreglo.id;
					var formato = 'yy-mm-dd';
					evaluar[ceva] = '$("#' + obj_celda.id
							+ '").datepicker({	dateFormat : "yy-mm-dd",});';
					ceva++;
					break;
				case 'button':
					obj_celda = document.createElement('input');
					obj_celda.type = 'button';
					obj_celda.style.cssText = sArreglo.estilo;
					obj_celda.value = sArreglo.etiqueta;
					break;
				case 'submit':
					obj_celda = document.createElement('input');
					obj_celda.type = 'submit';
					obj_celda.style.cssText = sArreglo.estilo;
					obj_celda.value = sArreglo.etiqueta;
					break;
				case 'boton':
					obj_celda = document.createElement('button');
					obj_celda.innerHTML = sArreglo.etiqueta;
					obj_celda.style.cssText = sArreglo.estilo;
					break;

				default:
					alert('aja1');
					break;
				}
			}// FIN DEL MAPA DE OBJETOS
			
			if (sArreglo.label != null) {
				var obj_label = document.createElement('label');
				obj_label.innerHTML = sArreglo.label;

				td.appendChild(obj_label);
			}
			
			td.appendChild(obj_celda);
			obj_celda.id = sArreglo.id;
			obj_celda.name = sArreglo.id;
			
			if (sArreglo.clase != null) {
				obj_celda.className = sArreglo.clase;
			}

			if (sArreglo.onclick != null) {
				obj_celda.onclick = function() {
					eval(sArreglo.onclick);
				};
			}
			if (sArreglo.ondblclick != null) {
				obj_celda.ondblclick = function() {
					eval(sArreglo.ondblclick);
				};
			}
			if (sArreglo.onkeypress != null) {
				obj_celda.onkeypress = function() {
					eval(sArreglo.onkeypress);
				};
			}
			if (sArreglo.onkeyup != null) {
				obj_celda.onkeyup = function() {
					eval(sArreglo.onkeyus);
				};
			}
			if (sArreglo.onblur != null) {
				obj_celda.onblur = function() {
					eval(sArreglo.onblur);
				};
			}
			if (sArreglo.onchange != null) {
				obj_celda.onchange = function() {
					eval(sArreglo.onchange);
				};
			}
			if (sArreglo.onfocus != null) {
				obj_celda.onfocus = function() {
					eval(sArreglo.onfocus);
				};
			}
			if (sArreglo.accion == 'consulta') {
				obj_celda.onblur = function() {
					self.Consultar(obj_celda.value, sArreglo.id);
				};
			}
			if (sArreglo.completar != null) {
				completar2.push(sArreglo.id);
				var ek = '';
				$.ajax({
					type : "POST",
					url : sUrlP + sArreglo.completar,
					async : false,
					success : function(data) {
						ek = data;
					},
				});
				completar.push(ek);
			}
			if (sArreglo.multicelda != null) {
				td.colSpan = total - contador_celdas + 1;
				contador_celdas = total;
				obj_celda.style.cssText = 'width:100%';
			}
			if (sArreglo.requerido != null) {
				obj_celda.setAttribute('required', 'required');
			}
			if (sArreglo.desabilitado != null) {
				obj_celda.setAttribute('disabled', 'disabled');
			}
			if (sArreglo.lectura != null) {
				obj_celda.setAttribute('readonly', 'true');
			}
			if (sArreglo.dependiente != null) {
				obj_celda.onchange = function() {
					self.Dependiente(sArreglo.dependiente[0], sArreglo.dependiente[1],sArreglo.id);
				};
				
			}
			
			if (sArreglo.oculto != null) {
				obj_celda.style.display = 'none';
				c_tabla_campos.rows[c_tabla_campos.rows.length - 1].appendChild(obj_celda);
			} else {
				c_tabla_campos.rows[c_tabla_campos.rows.length - 1].appendChild(td);
				contador_celdas++;
			}

		});
		var tam_eva = evaluar.length;
		for ( var j = 0; j < tam_eva; j++) {
			eval(evaluar[j]);
		}

		total = this.BFilas;
		contador_celdas = 0;
		if (oEsquema.botones != null) {
			$.each(oEsquema.botones, function(sId_Boton, sArreglo) {
				if (contador_celdas == 0) {
					contador_celdas++;
					var fila = c_tabla_botones
							.insertRow(c_tabla_botones.rows.length);
				}
				if (contador_celdas > total) {
					var fila = c_tabla_botones
							.insertRow(c_tabla_botones.rows.length);
					contador_celdas = 1;
				}
				contador_celdas++;
				var td = document.createElement('td');
				td.id = 'cel' + sId_Boton + nombre_aux;
				c_tabla_botones.rows[c_tabla_botones.rows.length - 1]
						.appendChild(td);
				cadena_tipo = sArreglo.tipo;
				if (cadena_tipo != null) {
					var obj_celda = null;
					switch (cadena_tipo) {
					case 'button':
						obj_celda = document.createElement('input');
						obj_celda.type = 'button';
						obj_celda.value = sArreglo.etiqueta;
						break;
					case 'submit':
						obj_celda = document.createElement('input');
						obj_celda.type = 'submit';
						obj_celda.value = sArreglo.etiqueta;
						break;
					case 'boton':
						obj_celda = document.createElement('button');
						obj_celda.innerHTML = sArreglo.etiqueta;
						break;

					default:
						alert('aja');
						break;
					}
				}// FIN DEL MAPA DE OBJETOS

				td.appendChild(obj_celda);
				obj_celda.id = sArreglo.id;
				obj_celda.name = sArreglo.id;
				if (sArreglo.requerido != null) {
					obj_celda.setAttribute('required', 'required');
				}
				if (sArreglo.oculto != null) {
					obj_celda.style.display = 'none';
				}
				if (sArreglo.clase != null) {
					obj_celda.className = sArreglo.clase;
				}
				if (sArreglo.onclick != null) {
					obj_celda.onclick = function() {
						eval(sArreglo.onclick);
					};
				}
				if (sArreglo.onkeypress != null) {
					obj_celda.onkeypress = function() {
						eval(sArreglo.onkeypress);
					};
				}
				if (sArreglo.onkeyup != null) {
					obj_celda.onkeyup = function() {
						eval(sArreglo.onkeyus);
					};
				}
				if (sArreglo.onblur != null) {
					obj_celda.onblur = function() {
						eval(sArreglo.onblur);
					};
				}
				if (sArreglo.onsubmit != null) {
					obj_celda.onblur = function() {
						eval(sArreglo.submit);
					};
				}

			});
		}
		for(var i = 0; i< completar.length ; i++){
			$( "#"+completar2[i]).autocomplete({
				source: eval(completar[i])
			});
		}

	};// Fin de Generar formulario
	function Crear_Evento(elemento, evento, funcion) {
		if (elemento.addEventListener) {
			elemento.addEventListener(evento, funcion, false);
		} else {
			elemento.attachEvent("on" + evento, funcion);
		}
	}

};
/*******************************************************************************
 * 
 * fin
 ******************************************************************************/
