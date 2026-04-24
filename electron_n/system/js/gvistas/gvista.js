/**
 * Desarrollado por: Judelvis Antonio Rivas Perdomo
 * Fecha Creacion: 02 de Junio de 2013
 */

/**
 * Clase GVista
 *
 * TODO Componente Generador de Vistas
 * Depende: GVista
 *
 * oEsq : Formulario (Privado)
 * return Formulario HTML
 */

var GVista = function(oEsq, campos, strTitulo, divbotones) {
    this.Esq = oEsq;
    //oEsq; //Objeto Grid
    this.DivCampos = campos;
    // Div donde se escribe la tabla
    this.DivBotones = divbotones;
    this.Titulo = strTitulo;
    this.Nombre = '';
    this.Limpiar = 0;
    this.CFilas = 1;
    this.BFilas = 1;
    this.tabla_campos = document.createElement("table");
    this.tabla_botones = document.createElement("table");

    this.SetLimpiar = function() {
        this.Limpiar = 1;
    }

    this.SetNombre = function(Nombre) {
        this.Nombre = Nombre;
    }

    this.GetNombre = function(Nombre) {
        return this.Nombre;
    }

    this.SetCeldas = function(celda) {
        this.CFilas = celda;
    }

    this.SetBotones = function(botones) {
        this.BFilas = botones;
    }
    //Construye el Grid
    this.Generar = function() {
        //variable auxiliar del obejto para construir el tgrid
        var oEsquema = this.Esq;
        //inicializando variables

        if (this.Limpiar == 0)
            $("#" + this.Div).html('');
        //asignado el div en el cual se va a construir el formulario
        var div_campos = document.getElementById(this.DivCampos);
        //asignado el div en el cual se va a construir los botones
        var div_botones = document.getElementById(this.DivBotones);

        div_campos.appendChild(this.tabla_campos);
        div_botones.appendChild(this.tabla_botones);

        this.tabla_campos.id = "tabla_campos" + this.GetNombre();
        this.tabla_botones.id = "tabla_botones" + this.GetNombre();
        // De los Cuerpo
        var c_tabla_campos = document.createElement('tBody');
        c_tabla_campos.id = this.tabla_campos.id + 'tbody';
        var identificador1 = "";
        this.tabla_campos.appendChild(c_tabla_campos);

        var c_tabla_botones = document.createElement('tBody');
        c_tabla_botones.id = this.tabla_botones.id + 'tbody';
        this.tabla_botones.appendChild(c_tabla_botones);
        var identificador2 = "";

        // Lectura de la Campos
        var contador_celdas = 0;
        var nombre_aux = this.GetNombre();
        var total = this.CFilas;
        var evaluar = new Array();
        var ceva = 0;
        
        $.each(oEsquema.campos, function(sId_Campo, sArreglo) {
            if (contador_celdas == 0) {
                contador_celdas++;
                var fila = c_tabla_campos.insertRow(c_tabla_campos.rows.length);
            }
            if (contador_celdas > total) {
                var fila = c_tabla_campos.insertRow(c_tabla_campos.rows.length);
                contador_celdas = 1;
            }
            
            var td = document.createElement('td');
            td.id = 'cel' + sId_Campo + nombre_aux;
            if(sArreglo.multicelda != null){
                td.colSpan = total - contador_celdas+1;
                contador_celdas = total;
            }
            c_tabla_campos.rows[c_tabla_campos.rows.length - 1].appendChild(td);
            //aumenta contador para celdas
            contador_celdas++;
            cadena_tipo = sArreglo.tipo;
            if (cadena_tipo != null) {
                var obj_celda = null;
                switch(cadena_tipo) {
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
                        break;
                    case 'combo':
                        obj_celda = document.createElement('select');
                        obj_celda.setAttribute('placeholder', sArreglo.etiqueta);
                                                 
                        obj_celda.style.cssText = sArreglo.estilo;
                        var contador = 0;
                        $.each(sArreglo.elementos, function(id, contenido) {
                            
                            obj_celda.options[contador] = new Option(contenido, id);
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
                        //obj_celda.setAttribute('placeholder', sArreglo.etiqueta);
                        obj_celda.type = 'number';
                        obj_celda.style.cssText = sArreglo.estilo;
                        obj_celda.setAttribute('min', sArreglo.min);
                        obj_celda.setAttribute('max', sArreglo.max);
                        break;
                    case 'calendario':
                        obj_celda = document.createElement('input');
                        obj_celda.type = 'text';
                        obj_celda.style.cssText = sArreglo.estilo;
                        obj_celda.setAttribute('placeholder', sArreglo.etiqueta);
                        obj_celda.id = sArreglo.id;
                        var formato = 'yy-mm-dd';
                        evaluar[ceva] = '$("#' + obj_celda.id + '").datepicker({    dateFormat : "yy-mm-dd",});';
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
            }//FIN DEL MAPA DE OBJETOS
            if(sArreglo.label != null){
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
            if (sArreglo.requerido != null) {
                obj_celda.setAttribute('required', 'required');
            }
            if (sArreglo.desabilitado != null) {
                obj_celda.setAttribute('disabled', 'disabled');
            }
            if (sArreglo.lectura != null) {
                obj_celda.setAttribute('readonly', 'true');
            }
            if (sArreglo.oculto != null) {
                obj_celda.style.display = 'none';
            }
            if (sArreglo.onclick != null) {
                obj_celda.onclick = function() {
                    eval(sArreglo.onclick);
                }
            }
            if (sArreglo.ondblclick != null) {
                obj_celda.ondblclick = function() {
                    eval(sArreglo.ondblclick);
                }
            }
            if (sArreglo.onkeypress != null) {
                obj_celda.onkeypress = function() {
                    eval(sArreglo.onkeypress);
                }
            }
            if (sArreglo.onkeyup != null) {
                obj_celda.onkeyup = function() {
                    eval(sArreglo.onkeyus);
                }
            }
            if (sArreglo.onblur != null) {
                obj_celda.onblur = function() {
                    eval(sArreglo.onblur);
                }
            }
            if (sArreglo.onchange != null) {
                obj_celda.onchange = function() {
                    eval(sArreglo.onchange);
                }
            }
            if (sArreglo.onfocus != null) {
                obj_celda.onfocus = function() {
                    eval(sArreglo.onfocus);
                }
            }

        });
        //alert(evaluar);
        eval(evaluar);
        //FIN
        
        total = this.BFilas;
        contador_celdas =0;
        if (oEsquema.botones != null) {
            $.each(oEsquema.botones, function(sId_Boton, sArreglo) {
                if (contador_celdas == 0) {
                    contador_celdas++;
                    var fila = c_tabla_botones.insertRow(c_tabla_botones.rows.length);
                }
                if (contador_celdas > total) {
                    var fila = c_tabla_botones.insertRow(c_tabla_botones.rows.length);
                    contador_celdas = 1;
                }
                contador_celdas++;
                var td = document.createElement('td');
                td.id = 'cel' + sId_Boton + nombre_aux;
                c_tabla_botones.rows[c_tabla_botones.rows.length - 1].appendChild(td);
                cadena_tipo = sArreglo.tipo;
                if (cadena_tipo != null) {
                    var obj_celda = null;
                    switch(cadena_tipo) {
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
                }//FIN DEL MAPA DE OBJETOS

                td.appendChild(obj_celda);
                obj_celda.id = sArreglo.id;
                obj_celda.name = sArreglo.id;

                if (sArreglo.clase != null) {
                    obj_celda.className = sArreglo.clase;
                }
                if (sArreglo.requerido != null) {
                    obj_celda.setAttribute('required', 'required');
                }
                if (sArreglo.oculto != null) {
                    obj_celda.style.display = 'none';
                }
                if (sArreglo.onclick != null) {
                    obj_celda.onclick = function() {
                        eval(sArreglo.onclick);
                    }
                }
                if (sArreglo.onkeypress != null) {
                    obj_celda.onkeypress = function() {
                        eval(sArreglo.onkeypress);
                    }
                }
                if (sArreglo.onkeyup != null) {
                    obj_celda.onkeyup = function() {
                        eval(sArreglo.onkeyus);
                    }
                }
                if (sArreglo.onblur != null) {
                    obj_celda.onblur = function() {
                        eval(sArreglo.onblur);
                    }
                }
                if (sArreglo.onsubmit != null) {
                    obj_celda.onblur = function() {
                        eval(sArreglo.submit);
                    }
                }

            });
        }

    }// Fin de Generar formulario
    function Crear_Evento(elemento, evento, funcion) {
        if (elemento.addEventListener) {
            elemento.addEventListener(evento, funcion, false);
        } else {
            elemento.attachEvent("on" + evento, funcion);
        }
    }

}
/**
 *
 *  fin
 * **/