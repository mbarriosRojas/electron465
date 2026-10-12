/**
 * Desarrollado por: Judelvis Antonio Rivas Perdomo
 * Fecha Creacion: 29 de Marzo de 2012
 * Fecha Modificacion: 26 de Febrero de 2014
 */

/**
 * Clase Grid
 *
 * TODO Componente Rejilla
 * Depende: TPaginador
 *
 * oEsq : Grid (Privado)
 * oTabla: Tabla (html)
 * oPag : Paginador (Div)
 * return TGrid
 */

/**
 *Construcion del Modelo para tgrid
 * 1)	Cabezera:
 * 		array(
 * 			-titulo{
 * 				tipo_dato   : texto,
 * 				descripcion : el valor del indice indica el tiulo de las colummnas en el tgrid
 * 			},
 * 			-atributos{
 * 				tipo_dato   : texto,
 * 				descripcion : el valor del indice es una cadenas que se ejecurara en la etiqueta style de las celdas de la columna a la que corresponde
 * 			},
 *			-buscar{
 * 				tipo_dato   : cualquiera. solo debe ser declarado,
 * 				descripcion : si es declaro la columna tendra la funcion de filtro de busqueda
 * 			},
 *  		-oculto{
 * 				tipo_dato   : cualquiera. solo debe ser declarado,
 * 				descripcion : si es declaro la columna tendra la propiedad de oculto
 * 			},
 * 			-total{
 * 				tipo_dato   : cualquiera. solo debe ser declarado,
 * 				descripcion : si es declaro se calculara el total de la suma de los valores en la columna (solo datos numericos)
 * 			}
 * 			-mantine{
 * 				tipo_dato   : cualquiera. solo debe ser declarado,
 * 				descripcion : se aplica a los objetos boton y bimagen, de ser declaro despues de ejecutar la funcion sobre la fila, esta NO se eliminara 
 * 								(Por defecto la columna es eliminada al correr la funcion ejecutar)
 * 			},
 * 			-ruta{
 * 				tipo_dato   : texto,
 * 				descripcion : el valor del indice contine la ruta de la imagen a mostrar en los objetos de tipo: bimagen y  enlace
 * 			},
 * 			-funcion{
 * 				tipo_dato   : texto,
 * 				descripcion : nombre de la funcion a ejecutarse en el controller o en javascript de modulo en ejecucion(esta funcion debe estar declarada),
 * 								se aplica a los objetos: boton, bimagen, enlace, detallePost
 * 			},
 * 			-parametro{
 * 				tipo_dato   : texto,
 * 				descripcion : es la cadena compuesta por los numero de posicion de los parametros que desean se pasados a la funcion a ejcutar
 * 								Se usa en conjunto con el indice funcion, de ser declarada como una cadena vacia, retorna todos los elementos de la fila como parametro de la
 * 								funcion a ejecutar. Se aplica a los objetos: boton, bimagen, enlace, detallePost 
 * 			},
 * 			-metodo{
 * 				tipo_dato   : entero,
 * 				descripcion : este indice se aplica solo al tipo de objeto enlace, tiene 2 valores posibles( 1 o 2)
 * 								Si es 1: el enlace ejecutara la funcion javascript definida en el indice funcion con los parametros selecionados
 * 											a traves del atributo onClick de la etiquea <a> y el atributo href = #
 * 								Si es 2: el enlace ejecutara la funcion en el controler definida en el indice funcion con los parametros selecionados
 * 											a traves del atributo atributo href 
 * 			},
 *  		-target{
 * 				tipo_dato   : texto,
 * 				descripcion : este indice se aplica solo al tipo de objeto enlace en el metodo 2, este indica como se ejecuta la funcion del enlace
 * 								si en la misma ventana o en una nueva, por defecto es _blank, recibe los valores posibles del atributo target de la
 * 								etiqueta <a>
 * 			},
 * 			-tipo{
 * 				tipo_dato   : texto,
 * 				descripcion : este indice indica el comportamiento de las celdas de dicha columna, se presentan los siguentes casos
 * 								-De no ser declarado las celdas de dicha columna seran estaticas solo muestran datos
 * 								-De ser declaros solo se admitiran los siguiente valores:
 * 									CON DOBLE CLICK
 * 									1) texto : Al hacer doble click en la celda aparece un input text para la edicion de la celda, 
 * 										el valor por defecto es el ingresado en el Objeto_Cuerpo en la posicion de la celda.
 * 									2) textArea : Al hacer doble click en la celda aparece un textArea para la edicion de la celda, 
 * 										el valor por defecto es el ingresado en el Objeto_Cuerpo en la posicion de la celda.
 * 									3) combo : Al hacer doble click en la celda aparece un input select para la edicion de la celda, 
 * 										el valor por defecto es el ingresado en el elemento Objeto del esquema en la posicion de la celda.
 * 									4) fecha : Al hacer doble click en la celda aparece 3 input select para la edicion de la fecha
 * 									5) calendario : Al hacer doble click en la celda aparece un calendario para la edicion de la fecha
 * 									DESDE LA CREACION DEL TGRID
 * 									6) boton: crea un boton en la celda que ejecutara la funcion en el controller especificado en el indice funcion
 * 										con los parametros especificados en el indice parametro
 * 									7) bimagen: crea un boton en la celda que ejecutara la funcion en el controller especificado en el indice funcion
 * 										con los parametros especificados en el indice parametro
 * 									8) checkbox: crea un elemento checkbox, el valor por defecto es el ingresado en el elemento Objeto del esquema en la posicion de la celda.	
 * 									9) radio: crea un elemento radio, el valor por defecto es el ingresado en el elemento Objeto del esquema en la posicion de la celda.
 * 									10) detallePre: crea un boton del cual se crea otra fila asociada a la cargada, en la cual se muestra los detalles de dicha fila,
 * 										este detalle se crea en el modelo y se carga a traves del objeto Cuerpo del esquema
 * 									11) detallePost: crea un boton del cual se crea otra fila asociada a la cargada, en la cual se muestra los detalles de dicha fila,
 * 										este detalle genera a traves de una funcion en el controller, dicha funcion es especificada el el indice funcion del objeto Cabezera, 
 * 										y los parametros en el indice parametro del mismo objeto, tambien se le puede pasar el parametro tipo_detalle el cual al ser declarado
 * 										como html genera un resultado html y no un grid de detalle
 * 									12) enlace: crea enlaces que ejecutan una funcion en el controller o en el javascript segun sea el metodo selecionado,
 * 										la funcion  dicha funcion es especificada el el indice funcion del objeto Cabezera, 
 * 										y los parametros en el indice parametro del mismo objeto
 * 			},
 * 
 * 		 
 */
var TGrid = function(oEsq, div, strTitulo) {
	this.Esq = oEsq; //oEsq; //Objeto Grid
	this.Div = div;// Div donde se escribe la tabla
	this.Titulo = strTitulo;
	this.Exel = false;
	this.Exel_D = false;
	this.Exel_Detalle = false;
	this.Enu = false; //Elemento para enumerar Filas
	this.Paginador = 0; //Objeto para el Paginador
	this.Tipo_Origen = '';
	this.GetName = null;
	this.Detalle = 0;
	this.Detalle2 = 0;
	this.TipoDetalle = '';
	this.Estilo = '';
	this.Limpiar = 0;
	this.la_tabla = document.createElement("table");
	this.div_inferior = document.createElement("div");
	this.P = null;
	this.xls = document.createElement("button");
	this.editar = document.createElement("button");
	
	this.SetLimpiar = function(){
		this.Limpiar = 1;	
	}
	
	this.SetEstilo = function(estilo){
		this.Estilo = estilo;	
	}
	
	this.SetEstiloC = function(estilo){
		this.EstiloC = estilo;	
	}
	
	this.SetDetalle = function(tipo){
		this.TipoDetalle = tipo;
	}
	
	this.SetXls = function(valor_xls){
		this.Exel = valor_xls;
	}
	
	this.SetXls_D = function(valor_xls_d){
		this.Exel_D = valor_xls_d;
	}
	
	this.SetXls_Detalle = function(valor_xls_detalle){
		this.Exel_Detalle = valor_xls_detalle;
	}
	
	this.GetXls = function(){
		alert(this.Exel);
	}
	
	this.SetNumeracion = function(valor_numera){
		this.Enu = valor_numera;
	}
	
	this.GetNumeracion = function(){
		alert(this.Enu);
	}
	
	this.SetName = function(Name){
		this.GetName = Name;
	}
	
	this.SetPaginador = function(Pag) {
		this.Paginador = Pag;
	}
	this.GetPaginador = function() {
		alert(this.Pagainador);
	}
	
	this.Cargar = function() {
		return false;
	}
	
	this.Generar = function(){
		this.Origen();
	}
	
	//Origen de Datos
	this.Origen = function() {
		if(this.Esq.Origen == 'json'){
			this.Tipo_Origen = 'json';
			this.Generar_Json();
		}else{
			this.Tipo_Origen = 'mysql';
			this.Generar_Mysql();
		}
	}
	
	//Construye el Grid
	this.Generar_Json = function() {
		var boton_i=0;
		var elimina_fila_boton = 1;							//variable para determinar si elimina fila al momento de ejecutar alguna accion
		var tipo_detalle = this.TipoDetalle; 				//variable que determita de de detalle a mostra en el grid(post=funcion, pre=definida en el modelo como html)
		var _exel = this.Exel;
		var _exel_d = this.Exel_D;
		var _exel_detalle = this.Exel_Detalle;								//variable verificacion de mostrar boton de ecxel
		var _enumera = this.Enu;							//determina si el grid lleva numero de filas, se declara en el js
		var oEsquema = this.Esq;							//variable auxiliar del obejto para construir el tgrid
				
		//inicializando variables
		var iFil = 0		
		var iCol = 0;
		var fecha_activa = "";
		
		if(this.Limpiar == 0)$("#"+this.Div).html('');		//condicion para limpiar del div en el cual se va a mostrar el tgrid
		var div_origen = document.getElementById(this.Div); //asignado el div en el cual se va a construir el tgrid
		var div_principal = document.createElement('div');  //div donde se construira el objeto tgrid, este esta dentro del div seleccionado
		div_origen.appendChild(div_principal);
		
		var div_superior = document.createElement("div");  	//div perteneciente al div principal, en este div va el encabezado del grid o titulo en caso de existir
		div_superior.id = "div_superior" + this.GetName;
		div_principal.appendChild(div_superior);
		
		var div_medio = document.createElement("div");		//div en el cual se construye la tabla html, se ubica dentro de principal
		div_medio.id = "div_medio" + this.GetName;
		div_medio.style.cssText = "OVERFLOW: auto;height:auto;";
		div_principal.appendChild(div_medio);
		
		var div_totales=document.createElement("div"); 		//div ubicado a continuacion del div medio en el cual se arrojan los totales de las columnas seleccionada 
		div_principal.appendChild(div_totales);
		
		//Se crea div en caso de existir una leyenda
		if(oEsquema.leyenda != '' && oEsquema.leyenda != null){
			var div_leyenda=document.createElement("div");
			div_leyenda.innerHTML = oEsquema.leyenda;
			div_principal.appendChild(div_leyenda);	
		}
		
		this.div_inferior.id = "div_inferior" + this.GetName;//var div_inferior = document.createElement("div");
		div_principal.appendChild(this.div_inferior);
		
		this.la_tabla.id = "tabla_tgrid" + this.GetName;	//var la_tabla = document.createElement("table");
		this.la_tabla.setAttribute("cellPadding","0");
		this.la_tabla.setAttribute("cellSpacing","0");
		if(this.Estilo != ''){
			this.la_tabla.className = this.Estilo;
		}else{
			this.la_tabla.className = "TGrid";	
		}
		
		this.la_tabla.style.cssText = "width:100%;";
		div_medio.appendChild(this.la_tabla);
		var nombre_aux = this.GetName;
		//Verifica si se crea el boton excel
		if(_exel == true){
			this.xls.innerHTML = 'Generar XLS';
			this.xls.className = 'boton_adelante';
			this.xls.onclick = function() {
				Genera_Xls_Json(nombre_aux,false);
			}	
			
		}
		if(_exel_d == true){
			this.xls.innerHTML = 'Generar XLS';
			this.xls.className = 'boton_adelante';
			this.xls.onclick = function() {
				Genera_Xls_Json(nombre_aux,true);
			}	
			
		}
		//verifica si se guarda toda la tabla funcion creada en el archivo editar.js
		if(oEsquema.Editable != '' && oEsquema.Editable != null){
			this.editar.innerHTML = 'Guardar';
			this.editar.className = 'boton_adelante';
			this.editar.onclick = function() {
				Editar_Tabla(nombre_aux,oEsquema.Editable,oEsquema.Parametros);
				div_origen.innerHTML = '';
			}	
		}
		
		var columnas_ocultas = new Array(); //ARREGLO PARA GUARDAR QUE COLUMNAS DE LA TABLA SE VAN A OCULTAR
		var arreglo_elementos = new Array(); //ARREGLO PARA GUARDAR QUE ELEMENTOS SE VAN A MOSTRAR EN EL CUERPO DE LA TABLA
		var columnas_totales = new Array();
		var montos_totales = new Array();
		var columnas_opera = new Array();
		var montos_opera = new Array();
		var arreglo_estilo = new Array();
		// De la Cabezera
		var numero_col = 0; //VARIABLE DE CONTROL PARA USAR LA FUNCIONES MUESTRA_FILTRO Y OCULTA_COLUMNA
		var cadena_tipo = ""; // Identificar el valor del Objeto para las Columnas
		var la_cabezera = this.la_tabla.createTHead();
		
		var la_fila_cab = la_cabezera.insertRow(0);
		la_fila_cab.id = this.la_tabla.id + 'thead';
		var th_enumera = document.createElement('th');
		
		var el_pie = this.la_tabla.createTFoot();
		var la_fila_pie = el_pie.insertRow(0);
	
		// Del Cuerpo
		var iFilas = 0;
		var cuerpo_tabla = document.createElement('tBody');
		cuerpo_tabla.id = this.la_tabla.id + 'tbody';
		var identificador = "";
		
		/******************INICA CABEZERA*****************/
		
		if(_enumera == true) {
			this.la_tabla.style.emptyCells = "inherit";
			th_enumera.id = 'T_enumera' + nombre_aux;
			la_fila_cab.appendChild(th_enumera);
			var th_aux = document.createElement('th');
			th_aux.style.cssText = "width:20px;";
			celdap = document.createElement('th');
			//celdap.innerHTML = "aqui";
			la_fila_pie.appendChild(celdap);
		}
		
		var totalizar = false;
		var opera = false;
		
		var self = this;
		// Lectura de la Cabezera
		$.each(oEsquema.Cabezera, function(sId_Columnas, sArreglo) {
			var th = document.createElement('th');
			th.id = 'T' + sId_Columnas + nombre_aux;
			th.style.cssText = sArreglo.atributos;
			la_fila_cab.appendChild(th);
			th.innerHTML = sArreglo.titulo;
			
			thp = document.createElement('th');
			thp.style.cssText = sArreglo.atributos;
			//thp.innerHTML = sId_Columnas;
			la_fila_pie.appendChild(thp);
			
			if(sArreglo.atributos){
				arreglo_estilo[sId_Columnas] = sArreglo.atributos; 
			}
			if(sArreglo.tipo_detalle != null){
				tipo_detalle = sArreglo.tipo_detalle;
			}
			
			if(sArreglo.buscar != null) { //VERIFICA SI EN LA COLUMNA LLEVA FILTRO DE BUSQUEDA
				var filtro = document.createElement('input');
				var pos = numero_col + 1 - 1;
				filtro.type = "text";
				filtro.id = "F" + sId_Columnas + nombre_aux;
				filtro.style.display = 'none';
				th.onclick = function() { 
					Muestra_Filtro(filtro);
					$('#' + filtro.id).focus();
				}
				
				filtro.onkeyup = function() {
					self.filtra(this.value,self.la_tabla, pos);
				};
				filtro.onblur = function() {
					Oculta_Filtro(filtro);
				}
				th.appendChild(filtro);
			}
			
			
			if(sArreglo.oculto != null) { //Define las columnas ocultas
				var pos = numero_col * 1;
				columnas_ocultas.push(pos);
				th.style.display = "none";
				thp.style.display = "none";
			}
			
			if(sArreglo.total != null) { //Define las columnas ocultas
				var pos_total = numero_col + 1;
				columnas_totales.push(pos_total);
				montos_totales[pos_total]=0;
				totalizar = true;
			}
			if(sArreglo.operacion != null) { //Define las columnas ocultas
				var pos_opera = numero_col + 1;
				columnas_opera.push(pos_opera);
				montos_opera[pos_opera]=sArreglo.operacion;
				opera = true;
			}
			numero_col++;
			//CREA EL MAPA DE OBJETOS PARA LAS CELDAS
			arreglo_elementos[sId_Columnas] = '';
			cadena_tipo = sArreglo.tipo;
			if(sArreglo.mantiene != null){
				elimina_fila_boton = 0;
			}
			if(cadena_tipo != null) {
				switch(cadena_tipo) {
					case 'texto_fijo':
						var objeto_celda = document.createElement('input');
						objeto_celda.type = "text";
						objeto_celda.style.width = "100%";
						arreglo_elementos[sId_Columnas] = objeto_celda;
						break;
					case 'combo_fijo':
						var objeto_celda = document.createElement('select');
						objeto_celda.style.width = "100%";
						arreglo_elementos[sId_Columnas] = objeto_celda;
						break;
					case 'boton':
						var objeto_celda = document.createElement('input');
						objeto_celda.type = "button";
						objeto_celda.style.width = "100%";
						arreglo_elementos[sId_Columnas] = objeto_celda;
						break;
					case 'bimagen':
						var objeto_celda = document.createElement('input');
						objeto_celda.type = "image";
						objeto_celda.src = sArreglo.ruta;
						arreglo_elementos[sId_Columnas] = objeto_celda;
						
						boton_i = 1;
						break;
					case 'detallePre':
						var objeto_celda = document.createElement('input');
						objeto_celda.type = "image";
						//objeto_celda.src = sArreglo.ruta;
						objeto_celda.src = sImg + "botones/abrir.png";
						arreglo_elementos[sId_Columnas] = objeto_celda;
						self.Detalle = 1;
						break;
					case 'detallePost':
						var objeto_celda = document.createElement('input');
						objeto_celda.type = "image";
						//objeto_celda.src = sArreglo.ruta;
						objeto_celda.src = sImg + "botones/abrir.png";
						arreglo_elementos[sId_Columnas] = objeto_celda;
						self.Detalle2 = 1;
						break;
					case 'checkbox':
						var objeto_celda = document.createElement('input');
						objeto_celda.type = "checkbox";
						objeto_celda.value = "true";
						arreglo_elementos[sId_Columnas] = objeto_celda;
						break;
					case 'enlace':
						var objeto_celda = document.createElement('a');
						arreglo_elementos[sId_Columnas] = objeto_celda;
						arreglo_elementos[sId_Columnas]['enlace'] = sArreglo.metodo;
						break;
					case 'radio':
						var th2 = document.createElement('th');
						th2.id = 'T' + sId_Columnas + '_';
						th2.innerHTML = sArreglo.titulo1;
						th2.style.cssText = sArreglo.atributos;
						la_fila_cab.appendChild(th2);
						var objeto_celda = document.createElement('input');
						objeto_celda.type = "radio";
						objeto_celda.value = 1;
						arreglo_elementos[sId_Columnas] = objeto_celda;
						break;
					default:
						arreglo_elementos[sId_Columnas] = cadena_tipo;
						break;
				}
			}	//FIN DEL MAPA DE OBJETOS 
		}); //FIN DE CABEZERA
		/******************INICIO DEL CUERPO *****************/
		this.la_tabla.appendChild(cuerpo_tabla);
		self = this;
		
		$.each(oEsquema.Cuerpo, function(sFila, sColumnas) {
			var fila = cuerpo_tabla.insertRow(cuerpo_tabla.rows.length);
			fila.id = sFila +'_' + nombre_aux;
			iFilas++;
			if(oEsquema.enlace != undefined && oEsquema != null){
				fila.onclick = function() {
					EjecutarEnlace(oEsquema.enlace.funcion, fila.id, oEsquema.enlace.parametro, self.la_tabla.id,oEsquema.enlace.metodo,"enlace_fila",oEsquema.enlace.target);
				}
			}
			
			if(self.Detalle == 1){
				var fila2 = cuerpo_tabla.insertRow(cuerpo_tabla.rows.length);
				fila2.id = sFila +'_' + nombre_aux + '_Detalle';
				var columna_vacia = fila2.insertCell(fila2.cells.length);
				var columna_detalle = fila2.insertCell(fila2.cells.length);
				columna_detalle.id = fila2.id + '0';
				columna_detalle.innerHTML = "<center><img src='"+sImg + "cargando.gif' id='"+columna_detalle.id+"_img'></img></center>";
				columna_detalle.colSpan = la_fila_cab.cells.length -1;
				columna_detalle.style.display = "none";
			}
			if(self.Detalle2 == 1){
				var fila2 = cuerpo_tabla.insertRow(cuerpo_tabla.rows.length);
				fila2.id = sFila +'_' + nombre_aux + '_Detalle';
				var columna_vacia = fila2.insertCell(fila2.cells.length);
				var columna_detalle = fila2.insertCell(fila2.cells.length);
				columna_detalle.id = fila2.id + '0';
				columna_detalle.innerHTML = "<center><img src='"+sImg + "cargando.gif' id='"+columna_detalle.id+"_img'></img></center>";
				columna_detalle.colSpan = la_fila_cab.cells.length -1;
				columna_detalle.style.display = "none";
			}
			if(_enumera == true) {//MUESTRA LOS NUMEROS DE LAS COLUMNAS, ESTA ASOCIADO A COMO VIENEN LOS IDENTIFICADORES EN EL OBJETO. DEBEN INICIAR EN 1
				th_nuevo = th_aux.cloneNode(true);
				th_nuevo.innerHTML = sFila;
				fila.appendChild(th_nuevo);
			}
			$.each(sColumnas, function(sCol, sValor) {
				identificador = sFila + '_' + sCol+ '_' + nombre_aux;
				var columna_fila = fila.insertCell(fila.cells.length);
				columna_fila.style.cssText = arreglo_estilo[sCol];
				
				if(totalizar == true){
					for(var kt = 0; kt < columnas_totales.length; kt++) {
						if(columnas_totales[kt] == sCol){
							pos_Total = columnas_totales[kt];
							montos_totales[pos_Total]+=(sValor*1);	
						}
					}
				}
				columna_fila.id = identificador;
				if(arreglo_elementos[sCol] != "") {
					if( typeof arreglo_elementos[sCol] != "string") {
						var nuevo = arreglo_elementos[sCol].cloneNode(true);
						nuevo.id = identificador + '_0';
						
						switch(nuevo.type) {
							case 'select-one':
								var contador = 0;
								var id_sep = nuevo.id.split('_');
								$.each(oEsquema.Objetos[id_sep[1]], function(contenido,ido) {
									nuevo.options[contador] = new Option(ido,contenido);
									//alert(sValor+"//"+contenido+"//"+ido);
									if (contenido == sValor) {
										nuevo[contador].selected = 'true';
									}
									contador++;
								});
								nuevo.onkeypress = function(e){
									aux = nuevo.id.split('_');
									aux_sig = parseInt(aux[0]) + 1; ;
									id_siguiente = aux_sig+'_' + sCol+ '_' + nombre_aux + '_0';
									key = e.keyCode || e.which;
									if(key == 13){
										$("#"+id_siguiente).focus();	
									}
								}
								//var oculto_div = 
								columna_fila.appendChild(nuevo);
								break;
							case 'text':
								nuevo.value = sValor;
								nuevo.onkeypress = function(e){
									aux = nuevo.id.split('_');
									aux_sig = parseInt(aux[0]) + 1; ;
									id_siguiente = aux_sig+'_' + sCol+ '_' + nombre_aux + '_0';
									key = e.keyCode || e.which;
									if(key == 13){
										$("#"+id_siguiente).focus();	
									}
								}
								columna_fila.appendChild(nuevo);
								break;
							case 'button':
								nuevo.value = sValor;
								nuevo.onclick = function() {
									Ejecutar(oEsquema.Cabezera[sCol].funcion, fila.id, oEsquema.Cabezera[sCol].parametro, self.la_tabla.id,elimina_fila_boton);
								}
								var div_texto = document.createElement('div');
								div_texto.id = "div_texto_boton";
								div_texto.style.cssText = "display:none";
								div_texto.innerHTML = sValor;
								nuevo.appendChild(div_texto);
								columna_fila.appendChild(nuevo);
								break;
							case 'image':
								if(self.Detalle == 1 && oEsquema.Cabezera[sCol].tipo == 'detallePre'){
									columna_detalle.innerHTML = sValor;
									nuevo.onclick = function() {
										muestra_detalle(fila2.id, self.la_tabla.id,nuevo.id);
									}
								}
								if(self.Detalle2 == 1 && oEsquema.Cabezera[sCol].tipo == 'detallePost'){
									nuevo.onclick = function() {
										muestra_detalle(fila2.id, self.la_tabla.id,nuevo.id);
										muestra_detalle_post(oEsquema.Cabezera[sCol].funcion, fila.id, oEsquema.Cabezera[sCol].parametro, self.la_tabla.id, tipo_detalle,_exel_detalle);
									}
								}
								if(boton_i==1 && oEsquema.Cabezera[sCol].tipo == 'bimagen'){
									nuevo.onclick = function() {
										Ejecutar(oEsquema.Cabezera[sCol].funcion, fila.id, oEsquema.Cabezera[sCol].parametro, self.la_tabla.id,elimina_fila_boton);
									}	
								}
								
								var div_texto = document.createElement('div');
								div_texto.id = "div_texto_img";
								div_texto.style.cssText = "display:none";
								div_texto.innerHTML = '';
								nuevo.appendChild(div_texto);
								columna_fila.appendChild(nuevo);
								
								break;
							case 'checkbox':
								nuevo.value = true;
								nuevo.onclick = function() {
									Revisa_Casilla(sFila + '_' + sCol+ '_' + nombre_aux);
								}
								var div_texto = document.createElement('div');
								div_texto.id = "div_" + sFila + '_' + sCol+ '_' + nombre_aux;
								div_texto.style.cssText = "display:none";
								div_texto.innerHTML = '';
								nuevo.appendChild(div_texto);
								columna_fila.appendChild(nuevo);
								break;
							case 'radio':
								nuevo.name = 'radio_' + identificador;
								var radio = document.createElement('input');
								radio.type = 'radio';
								radio.id = identificador + '_1';
								radio.name = 'radio_' + identificador;
								radio.value = 0;
								columna_fila.appendChild(nuevo);
								var columna_fila2 = fila.insertCell(fila.cells.length);
								columna_fila.id = 'R' + identificador;
								columna_fila2.id = 'R' + identificador + '_';
								columna_fila2.appendChild(radio);
								if(sValor == '1') {
									nuevo.checked = true;

								} else {
									radio.checked = true;

								}
								break;
						}
						
					} else {
						if(arreglo_elementos[sCol] == 'calendario'){
							columna_fila.onclick = function() {
								Crea_Objeto(this, arreglo_elementos[sCol],oEsquema.Objetos);
							}
						}else{
							columna_fila.ondblclick = function() {
								Crea_Objeto(this, arreglo_elementos[sCol],oEsquema.Objetos);
							}
						}
						columna_fila.innerHTML = sValor;
					}
				} else {
					if(arreglo_elementos[sCol]['enlace'] != undefined){
						var nuevo = arreglo_elementos[sCol].cloneNode(true);
						nuevo.id = identificador + '_0';
						if(arreglo_elementos[sCol]['enlace'] == 1){
							if(oEsquema.Cabezera[sCol].ruta != null && oEsquema.Cabezera[sCol].ruta != ''){
								nuevo.innerHTML	= "<center><img src='"+ oEsquema.Cabezera[sCol].ruta + "' id='"+nuevo.id+"_img'></img></center>";
							}else{
								nuevo.innerHTML = sValor;	
							}
							nuevo.onclick = function() {
								EjecutarEnlace(oEsquema.Cabezera[sCol].funcion, fila.id, oEsquema.Cabezera[sCol].parametro, self.la_tabla.id,oEsquema.Cabezera[sCol].metodo,nuevo,'',elimina_fila_boton);
							}
							var div_texto = document.createElement('div');
							div_texto.id = "div_texto_boton";
							div_texto.style.cssText = "display:none";
							nuevo.appendChild(div_texto);
							columna_fila.appendChild(nuevo);
						}
						if(arreglo_elementos[sCol]['enlace'] == 2){
							if(oEsquema.Cabezera[sCol].ruta != null && oEsquema.Cabezera[sCol].ruta != ''){
								nuevo.innerHTML	= "<center><img src='"+ oEsquema.Cabezera[sCol].ruta + "' id='"+nuevo.id+"_img'></img></center>";
							}else{
								nuevo.innerHTML = sValor;	
							}
							nuevo.setAttribute('target' , oEsquema.Cabezera[sCol].target);
							nuevo.onclick = function() {
								EjecutarEnlace(oEsquema.Cabezera[sCol].funcion, fila.id, oEsquema.Cabezera[sCol].parametro, self.la_tabla.id,oEsquema.Cabezera[sCol].metodo,nuevo,elimina_fila_boton);
							}
							var div_texto = document.createElement('div');
							div_texto.id = "div_texto_boton";
							div_texto.style.cssText = "display:none";
							nuevo.appendChild(div_texto);
							columna_fila.appendChild(nuevo);
						}
					}else{
						columna_fila.innerHTML = sValor;	
					}
					
				}
				
			});
			
		});
		/****************** FIN DEL CUERPO   *****************/
		for(var k = 0; k < columnas_ocultas.length; k++) {
			Oculta_Columna(columnas_ocultas[k], this.la_tabla.id);

		}
		/***************** FIN OCULTA COLUMNAS *****************/
		/******************PAGINADOR          *****************/
		if(oEsquema.Paginador != null && oEsquema.Paginador != 0) {
			$("#div_inferior"+ nombre_aux).html('');
			if(this.Detalle2 > this.Detalle){
				this.P = new Paginador(this.div_inferior, this.la_tabla, oEsq.Paginador,this.Detalle2);
			}else{
				this.P = new Paginador(this.div_inferior, this.la_tabla, oEsq.Paginador,this.Detalle);	
			}
			this.P.Mostrar();
		}
		
		
		if(_exel == true || _exel_d == true){
			this.div_inferior.appendChild(this.xls);	
		}
		
		if(oEsquema.Editable != '' && oEsquema.Editable != null){
			this.div_inferior.appendChild(this.editar);
		}
		
		var div_titulo = document.createElement("div");
		div_titulo.id = "div_titulo" + nombre_aux;
		div_titulo.className = 'fondo_titulo';
		var titulo = document.createElement('h1');
		titulo.className = "titulo";
		if(oEsquema.titulo != '' && oEsquema.titulo != null){
			titulo.innerHTML = oEsquema.titulo;	
		}else{
			titulo.innerHTML = this.Titulo;	
		}
		
		titulo.style.cssText = "text-align:left;";
		div_titulo.appendChild(titulo);
		var respuesta = document.createElement('div');
     	respuesta.id = "dialog_pie" + nombre_aux;
		div_superior.appendChild(div_titulo);
		div_superior.appendChild(respuesta);
		
		if(totalizar == true){
			var tabla_totales = document.createElement("table");
			tabla_totales.style.cssText="width:100%;";
			var fila_total = tabla_totales.insertRow(tabla_totales.rows.length);
			
			for(var kt = 0; kt < columnas_totales.length; kt++) {
				pos_Total = columnas_totales[kt];
				var columnas_de_totales = fila_total.insertCell(fila_total.cells.length);
				var moneda = this.Formato(montos_totales[pos_Total], " Bs.");
				columnas_de_totales.innerHTML = "El Resultado Total de la columna ( "+oEsquema.Cabezera[pos_Total].titulo+" ) es ( <b>" + moneda + "</b> )";
				la_fila_pie.cells[pos_Total].innerHTML = moneda;	
			}
			div_totales.className = "totales";
			div_totales.appendChild(tabla_totales);
		}
		if(opera == true){
			var tabla_opera = document.createElement("table");
			tabla_opera.style.cssText="width:100%;";
			var fila_ope = tabla_opera.insertRow(tabla_opera.rows.length);
			var c = montos_totales;
			
			for(var kt = 0; kt < columnas_opera.length; kt++) {
				pos_op = columnas_opera[kt];
				var resul = eval(montos_opera[pos_op]);
				la_fila_pie.cells[pos_op].innerHTML = this.Formato(resul, " ");;			
			}
		}

	} // Fin de Generar Grid
	

	/**
	 * INICIO DE LA FUNCION PARA CONSTRUIR MYSQL TABLA
	 * **/
	this.Generar_Mysql = function() {
		var _enumera = this.Enu;
		var _exel = this.Exel;
		oEsquema2 = this.Esq;
		if(this.Limpiar == 0)$("#"+this.Div).html('');
		
		var div_origen = document.getElementById(this.Div);
		var div_principal = document.createElement('div');
		//div_principal.style.cssText = "WIDTH: 100%;height:80%;";
		div_origen.appendChild(div_principal);
		
		var div_superior = document.createElement("div");
		div_superior.id = "div_superior" + this.GetName;
		div_principal.appendChild(div_superior);
		
		var div_medio = document.createElement("div");
		div_medio.id = "div_tabla" + this.GetName;
		div_medio.style.cssText = "height:auto;WIDTH: 100%;overflow: auto;";
		div_principal.appendChild(div_medio);
		
		if(oEsquema2.leyenda != '' && oEsquema2.leyenda != null){
			var div_leyenda=document.createElement("div");
			div_leyenda.innerHTML = oEsquema2.leyenda;
			div_principal.appendChild(div_leyenda);	
		}
		
		var div_inferior = document.createElement("div");
		div_inferior.id = "div_inferior" + this.GetName;
		div_inferior.style.cssText = "width:100%;";
		div_principal.appendChild(div_inferior);
		
		var la_tabla = document.createElement("table");
		la_tabla.id = "tabla_tgrid" + this.GetName;
		la_tabla.style.width = "100%";
		if(this.Estilo != ''){
			la_tabla.className = this.Estilo;
		}else{
			la_tabla.className = "TGrid";	
		}
		div_medio.appendChild(la_tabla);
		
		
		var iFilas = 0;
		var cuerpo_tabla = document.createElement('tBody');
		la_tabla.appendChild(cuerpo_tabla);
		
		var identificador = "";
		
		var la_cabezera = la_tabla.createTHead();
		
		var la_fila_cab = la_cabezera.insertRow(0);
		//la_fila_cab.style.cssText = "position:fixed;";
		nombre_aux = this.GetName;
		if(_exel == true){
			var xls = document.createElement("button");
			xls.innerHTML = 'Generar XLS';
			xls.className = 'boton_adelante';
			var objaux = this;
			xls.onclick = function() {
				Genera_Xls_Json(nombre_aux,false);
			}
			div_inferior.appendChild(xls);
		}
		
		/******************INICA CABEZERA*****************/
		
		if(_enumera != false) {
			la_tabla.style.emptyCells = "inherit";
			var th_enumera = document.createElement('th');
			la_fila_cab.appendChild(th_enumera);
			var th_aux = document.createElement('th');
			th_aux.style.cssText = "width:20px;";
		}
		
		//cabezera
		var num_cols = 1;
		$.each(oEsquema2.Cabezera, function(sId_Columnas, sArreglo) {
			var th = document.createElement('th');
			th.id = 'T' + sId_Columnas;
			th.style.cssText = "position:relative;";
			la_fila_cab.appendChild(th);
			th.innerHTML = sArreglo;
			num_cols++;
		});

		$.each(oEsquema2.Cuerpo, function(sFila, sColumnas) {
			var fila = cuerpo_tabla.insertRow(cuerpo_tabla.rows.length);
			aul = parseInt(sFila) + 1;
			fila.id = aul+"_"+nombre_aux;
			iFilas++;
			if(_enumera != false) {//MUESTRA LOS NUMEROS DE LAS COLUMNAS, ESTA ASOCIADO A COMO VIENEN LOS IDENTIFICADORES EN EL OBJETO. DEBEN INICIAR EN 1
				th_nuevo = th_aux.cloneNode(true);
				th_nuevo.innerHTML = iFilas;
				fila.appendChild(th_nuevo);
			}
			$.each(sColumnas, function(sCol, sValor) {
				identificador = iFilas + '_' + sCol;
				var columna_fila = fila.insertCell(fila.cells.length);
				columna_fila.id = identificador;
				columna_fila.innerHTML = sValor;
			});
		});
		
		var div_titulo = document.createElement("div");
		div_titulo.id = "div_titulo";
		div_titulo.style.cssText = "color:#000;";
		var titulo = document.createElement('h1');
		titulo.className = "titulo";
		if(oEsquema2.titulo != '' && oEsquema2.titulo != null){
			titulo.innerHTML = oEsquema2.titulo;	
		}else{
			titulo.innerHTML = this.Titulo;	
		}
		titulo.style.cssText = "text-align:center;";
		div_titulo.appendChild(titulo);
		if(oEsquema2.Paginador != null && oEsquema2.Paginador != 0) {
			if(la_tabla.rows.length > oEsquema2.Paginador){
				p1 = new Paginador(div_inferior, la_tabla, oEsquema2.Paginador);
				p1.Mostrar();
			}
		}
		
		var respuesta = document.createElement('div');
     	respuesta.id = "dialog_pie"+nombre_aux;
		div_superior.appendChild(div_titulo);
		div_superior.appendChild(respuesta);
		
	}
	
	/** Formato de Tipo Moneda **/
	this.Formato = function(num,prefix){
		num = Math.round(parseFloat(num)*Math.pow(10,2))/Math.pow(10,2)
		prefix = prefix || '';
		num += '';
		var splitStr = num.split('.');
		var splitLeft = splitStr[0];
		var splitRight = splitStr.length > 1 ? '.' + splitStr[1] : '.00';
		splitRight = splitRight + '00';
		splitRight = splitRight.substr(0,3);
		var regx = /(\d+)(\d{3})/;
		while (regx.test(splitLeft)) {
			splitLeft = splitLeft.replace(regx, '$1' + ',' + '$2');
		}
		return splitLeft + splitRight + prefix;
	}
	
	function Crear_Evento(elemento, evento, funcion) {
		if(elemento.addEventListener) {
			elemento.addEventListener(evento, funcion, false);
		} else {
			elemento.attachEvent("on" + evento, funcion);
		}
	}
	//FUNCION PARA FILTRAR DATOS EN TABLA
	this.filtra = function(txt, t, pos) {
		if (txt != '') {
			for(i = 1;i < t.rows.length; i++){
				sufijo = t.rows[i].id.split('_');
				if (sufijo[sufijo.length - 1] != "Detalle") {
					if (t.rows[i].getElementsByTagName('td')[pos] && t.rows[i].getElementsByTagName('td')[pos].id != '') {
						texto = t.rows[i].getElementsByTagName('td')[pos].innerHTML.toUpperCase();
						//alert(texto);
						posi = (texto.indexOf(txt.toUpperCase()) != -1);
						//alert(posi);
						t.rows[i].style.display = (posi) ? '' : 'none';
					}
				} else {
					t.rows[i].style.display = 'none';
				}
			}
		}else {
			this.P.SetPagina(this.P.pagActual);
		}
	}
	
}


	/**
	* 
	* 	fin del grid
	* **/