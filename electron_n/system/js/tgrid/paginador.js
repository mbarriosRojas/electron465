/**
 * Desarrollado por: Judelvis Antonio Rivas Perdomo
 * Modificado por: Carlos Enrique Pena
 * Fecha Creacion: 29 de Marzo de 2012
 * Fecha Modificacion: 15 de Abril de 2012
 */

/*
 * Clase usada para la paginacion de elementos de una tabla
 * Es utilizada en conjunto con la clase Tgrid
 * La clase es instancia por la clase Tgrid, con los parametros necesarios 
 */

var Paginador = function(divPaginador, tabla, tamPagina,detalle) {
	this.miDiv = divPaginador;
	//un DIV donde irÃ¡n controles de paginaciÃ³n
	this.divPaginadorAux = document.createElement('div');
	//
	this.tabla = tabla;
	//la tabla a paginar
	this.tamPagina = tamPagina;
	//el tamaÃ±o de la pÃ¡gina (filas por pÃ¡gina)
	this.pagActual = 1;
	//contador
	this.muestra = 0;
	//asumiendo que se parte en pÃ¡gina 1
	this.paginas = Math.floor((this.tabla.rows.length - 2 ) / this.tamPagina) + 1;
	//Â¿?
	this.registros = this.tabla.rows.length - 1;

	this.SetPagina = function(num) {
		num = parseInt(num);
		if(num < 0 || num > this.paginas) {
			return;
		}
		//marca pagina actual
		id_li = tabla.id +'li_' + num;
		id_li2 = tabla.id +'li_' + this.pagActual;
		var li_actual = document.getElementById(id_li);
		var li_ant = document.getElementById(id_li2);
		if(num != this.pagActual) {
			li_actual.className = "seleccionado";
			li_ant.className = "deseleccionado";

		} else {
			li_actual.className = "seleccionado";
		}
		this.pagActual = num;
		var registro = 0;
		var min = 1 + (this.pagActual - 1) * this.tamPagina;
		var max = min + this.tamPagina - 1;
		for(var i = 1; i < this.registros+1; i++) {
			if(i < min || i > max)this.tabla.rows[i].style.display = 'none';
			else{
				this.tabla.rows[i].style.display = '';
				registro = i;
			}
		}

		if(this.muestra == 1) {
			this.muestra_item(num);
		}
		
		//mostrar botones
		var total_lineas = this.tamPagina * this.paginas;
		var a = registro;
		var de = this.registros;
		if(detalle == 1){
			min = 1 + (this.pagActual - 1) * this.tamPagina/2;
			a = a/2;
			de = de/2;
		}
		if(num == 1) {
			//this.miDiv.firstChild.rows[0].cells[1].style.display = "none";
			this.divPaginadorAux.firstChild.rows[0].cells[0].style.visibility = "hidden";
			this.divPaginadorAux.firstChild.rows[0].cells[3].innerHTML = 'Mostrando ' + min + ' a ' + a + ' de ' + de + ' | Pagina ' + this.pagActual + ' De ' + this.paginas;
		} else {
			//this.miDiv.firstChild.rows[0].cells[1].style.display = "table-cell";
			this.divPaginadorAux.firstChild.rows[0].cells[0].style.visibility = "visible";
			this.divPaginadorAux.firstChild.rows[0].cells[3].innerHTML = 'Mostrando ' + min + ' a ' + a + ' de ' + de + ' | Pagina ' + this.pagActual + ' De ' + this.paginas;
		}

		if(num == this.paginas) {
			//this.miDiv.firstChild.rows[0].cells[3].style.display = "none";
			this.divPaginadorAux.firstChild.rows[0].cells[2].style.visibility = "hidden";
			this.divPaginadorAux.firstChild.rows[0].cells[3].innerHTML = 'Mostrando ' + min + ' a ' + a + ' de ' + de + ' | Pagina ' + this.pagActual + ' De ' + this.paginas;
		} else {
			//this.miDiv.firstChild.rows[0].cells[3].style.display = "table-cell";
			this.divPaginadorAux.firstChild.rows[0].cells[2].style.visibility = "visible";
			this.divPaginadorAux.firstChild.rows[0].cells[3].innerHTML = 'Mostrando ' + min + ' a ' + a + ' de ' + de + ' | Pagina ' + this.pagActual + ' De ' + this.paginas;
		}

		//MUESTRA PAGINAS
		var desde = 0;
		var hasta = 0;
	
		
	}

	this.Mostrar = function() {
		//alert(this.paginas + "--" +this.tabla.rows.length);
		if(this.paginas > 3) {
			this.muestra = 1;
		}
		
		this.divPaginadorAux.id = this.miDiv.id+"_pag";
		this.divPaginadorAux.style.cssText = "width:100%;";
		var tblPaginador = document.createElement('table');
		//CREA TABLA DEL PAGINADOR
		
		var fil = tblPaginador.insertRow(tblPaginador.rows.length);
		//INSERTA PRIMERA FILA DE LA TABLA
		//MUESTRA PAGINA ACTUAL Y TOTALES DEL PAGINADOR
		
		//BOTON ANTERIOR DEL PAGINADOR

		//var ant = fil.insertCell(fil.cells.length);
		var ant = fil.insertCell(fil.cells.length);
		ant.innerHTML = 'Anterior';
		ant.className = 'boton_anterior';
		var self = this;
		ant.onclick = function() {
			if(self.pagActual == 1)
				return;
			self.SetPagina(self.pagActual - 1);
		}
		//LISTA DE PAGINAS DEL PAGINADOR
		var lista_paginas = fil.insertCell(fil.cells.length);
		lista_paginas.className = "celdas";
		var ul = document.createElement('ul');
		ul.id = "lista_paginas";
		//BOTON SIGUIENTE DEL PAGINADOR
		var sig = fil.insertCell(fil.cells.length);
		sig.innerHTML = 'Siguiente';
		sig.className = 'boton_adelante';
		sig.onclick = function() {
			if(self.pagActual == self.paginas)
				return;
			self.SetPagina(self.pagActual + 1);
		}
		var numero_pagina = fil.insertCell(fil.cells.length);
		numero_pagina.innerHTML = '';
		numero_pagina.className = 'celdas';
		
		//SE AGREGA LA TABLA DEL PAGINADOR AL DIV ESPECIFICO DEL PAGINADOR
		this.divPaginadorAux.appendChild(tblPaginador);
		this.miDiv.appendChild(this.divPaginadorAux);
		this.miDiv.className = 'paginador';
		//ASIGNA LA CLASS PARA EL ESTILO DEL PAGINADOR

		//LLENA LA LISTA DE PAGINAS
		lista_paginas.appendChild(ul);
		var inicio = document.createElement("li");
		inicio.id = "li_inicio"+tabla.id;
		inicio.innerHTML = "<<";
		/*inicio.onclick = function() {
			self.SetPagina(1);
		}*/
		Crear_Evento(inicio, "click", function() {
			self.SetPagina(1);
		});
		if(this.muestra == 1) {
			ul.appendChild(inicio);
		}
		for(var i = 0; i < this.paginas; i++) {
			var ele = document.createElement('li');
			ele.innerHTML = i + 1;
			id_ele = i + 1;
			ele.id = tabla.id + "li_" + id_ele;
			Crear_Evento(ele, "click", function() {
				self.SetPagina(this.innerHTML);
			});
			ul.appendChild(ele);
		}
		var fin = document.createElement("li");
		fin.id = "li_fin"+tabla.id;
		fin.innerHTML = ">>";
		/*fin.onclick = function() {
			self.SetPagina(self.paginas);
		}*/
		Crear_Evento(fin, "click", function() {
			self.SetPagina(self.paginas);
		});
		if(this.muestra == 1) {
			ul.appendChild(fin);
		}
		//BOTON GENERA EXEL

		//ME LLEVA A LA PRIMERA PAGINA
		if(this.tabla.rows.length - 1 > this.paginas * this.tamPagina)
			this.paginas = this.paginas + 1;

		this.SetPagina(this.pagActual);
		
		//this.miDiv.style.cssText = 'align: right;';
	}
	this.muestra_item = function(actual) {
		var self = this;
		var hasta = actual + 3;
		if(hasta <= self.paginas) {
			for(var i = 1; i < actual; i++) {
				id_ele = i * 1;
				identificador = tabla.id +"li_" + id_ele;
				$("#" + identificador).hide();
			}
			for(var i = actual; i < hasta; i++) {
				id_ele = i * 1;
				identificador = tabla.id +"li_" + id_ele;
				$("#" + identificador).show();
			}
			for(var i = hasta; i <= self.paginas; i++) {
				id_ele = i * 1;
				identificador = tabla.id +"li_" + id_ele;
				$("#" + identificador).hide();
			}
		} else {
			var desde = self.paginas - 3;
			for(var i = 1; i <= desde; i++) {
				id_ele = i * 1;
				identificador = tabla.id +"li_" + id_ele;
				$("#" + identificador).hide();
			}
			for(var i = desde + 1; i <= self.paginas; i++) {
				id_ele = i * 1;
				identificador = tabla.id +"li_" + id_ele;
				$("#" + identificador).show();
			}
		}
	}

	function Crear_Evento(elemento, evento, funcion) {
		if(elemento.addEventListener) {
			elemento.addEventListener(evento, funcion, false);
		} else {
			elemento.attachEvent("on" + evento, funcion);
		}
	}

}