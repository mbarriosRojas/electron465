<?php

/**
 * Desarrollado por: Judelvis Antonio Rivas Perdomo
 * Fecha Creacion: 30 de Octubre de 2013
 */

/**
 * @author JUDELVIS ANTONIO RIVAS PERDOMO
 * @class
 * @classdesc Clase para crear esquema para la construccion de vista html
 */
class GPesquema {
	/**
     * @description Titulo de la vista.
     * @var string sTitulo del objeto.
     */
    var $sTitulo = null;
    /**
     * @description Id del objeto.
     * @var string ID del objeto.
     */
    var $id = null;
    /**
     * @description Tipo del objeto. 
     * @var string Valores permitidos(texto,textarea,combo,lista,numero,calendario,button,submit,boton).
     */
    var $tipo = null;
    /**
     * @description Determina si el objeto es campo clave para los metodos de guardar y consultar. 
     * @var string Valores permitidos(index,primary).
     */
    var $clave = null;
    /**
     * @description Asigna un estilo as objeto.
     * @var string Valores permitidos(Cadena de codigo css).
     */
    var $estilo = null;
    /**
     * @description Asigna clase css al objeto.
     * @var string Valores permitidos(Clase css).
     */
    var $clase = null;
    /**
     * @description Determina etiqueta a mostar en el atributo placeholder del objeto.
     * @var string Nombre a mostrar del objeto.
     */
    var $etiqueta = null;
    /**
     * @description Determina etiqueta a mostar en lable del objeto.
     * @var string Nombre a mostrar del objeto.
     */
    var $label = null;
    /**
     * @description Asigna un arreglo de valores para los objetos tipo combo.
     * @var array Valores posibles(array(valor,texto)).
     */
    var $elementos = null;
    /**
     * @description Asigna una accion al objeto.
     * @var string Valores posibles(consulta).
     */
    var $accion = null;
    /**
     * @description Asigna propiedades al objeto.
     * @var array Valores posibles(multicelda,oculto,requerido,desabilitado,lectura).
     */
    var $propiedades = null;
    /**
     * @description Asigna funciones javascript a ejecutarse en el evento onclick de objeto.
     * @var string Valores posibles(Cadena javascript).
     */
    var $onclick = null;
    /**
     * @description Asigna funciones javascript a ejecutarse en el evento ondblclick de objeto.
     * @var string Valores posibles(Cadena javascript).
     */
    var $ondblclick = null;
    /**
     * @description Asigna funciones javascript a ejecutarse en el evento onkeypress de objeto.
     * @var string Valores posibles(Cadena javascript).
     */
    var $onkeypress = null;
    /**
     * @description Asigna funciones javascript a ejecutarse en el evento onkeyup de objeto.
     * @var string Valores posibles(Cadena javascript).
     */
    var $onkeyup = null;
    /**
     * @description Asigna funciones javascript a ejecutarse en el evento onblur de objeto.
     * @var string Valores posibles(Cadena javascript).
     */
    var $onblur = null;
    /**
     * @description Asigna funciones javascript a ejecutarse en el evento onchange de objeto.
     * @var string Valores posibles(Cadena javascript).
     */
    var $onchange = null;
    /**
     * @description Asigna funciones javascript a ejecutarse en el evento onfocus de objeto.
     * @var string Valores posibles(Cadena javascript).
     */
    var $onfocus = null;
    /**
     * @description Asigna funcion php a ejecutar en el submit del formulario.
     * @var string Valores posibles(Funcion php).
     */
    var $sGuardar = '';
    /**
     * @description Asigna funcion php a ejecutar en el objeto con accion de consulta.
     * @var string Valores posibles(Funcion php).
     */
    var $sConsulta = '';
    /**
     * @description Asigna funcion php a ejecutar en el objeto con autocompletar de jquery.
     * @var string Valores posibles(Funcion php).
     */
    var $completar = null;
    /**
     * @description Asigna funcion php a ejecutar en el objeto con autocompletar de jquery.
     * @var array Valores posibles(ID obejo,Funcion php).
     */
    var $dependiente = null;
    /**
     * @description Arreglo donde se almacenan todos los objetos del formulario.
     * @var array Valores posibles(Arreglo).
     */
    private $aCampos = array();
    /**
     * @description Arreglo donde se almacenan todos los botones del formulario.
     * @var array Valores posibles(Arreglo).
     */
    private $aBotones = array();
    
    /**
     * Agrega arreglo de campos al objeto.
     * @param {array} aValores
     */
    public function Agregar_Campos($aValores) {
        $this->aCampos = $aValores;
    }
    /**
     * Agrega arreglo de botones al objeto.
     * @param {array} aValores
     */
    public function Agregar_Botones($aValores) {
        $this->aBotones[] = $aValores;
    }
    /**
     * Agrega elemento al arreglo de campos al objeto. A partir del objeto Esquema
     */
    public function Asignar_Campo() {
        $agregar = array();
        $identificadores = array('id', 'tipo', 'clave', 'estilo', 'clase', 'etiqueta', 'label', 'elementos', 'accion', 'onclick', 'ondblclick', 'onkeypress', 'onkeyup', 'onblur', 'onchange', 'onfocus','completar','dependiente');
        foreach ($identificadores as $key) {
            if ($this->$key != null) {
                $agregar[$key] = $this->$key;
            }
        }
        if ($this->propiedades != null) {
            foreach ($this->propiedades as $key) {
                $agregar[$key] = TRUE;
            }
        }
        $this->aCampos[] = $agregar;
        $this->Limpiar();
    }
    /**
     * Agrega elemento al arreglo de botones al objeto. A partir del objeto Esquema
     */
    public function Asignar_Boton() {
        $agregar = array();
        $identificadores = array('id', 'tipo', 'clave', 'estilo', 'clase', 'etiqueta', 'label', 'elementos', 'accion', 'onclick', 'ondblclick', 'onkeypress', 'onkeyup', 'onblur', 'onchange', 'onfocus');
        foreach ($identificadores as $key) {
            if ($this->$key != null) {
                $agregar[$key] = $this->$key;
            }
        }
        if ($this->propiedades != null) {
            foreach ($this->propiedades as $key) {
                $agregar[$key] = TRUE;
            }
        }
        $this->aBotones[] = $agregar;
        $this->Limpiar();
    }
    /**
     * Genera esquema json
     * @return json Objeto para construir vista.
     */
    public function Generar() {
        $objeto = array('campos' => $this->aCampos, 'botones' => $this->aBotones, 'fconsulta' => $this->sConsulta, "fguardar" => $this->sGuardar, "titulo" => $this -> sTitulo);
        return json_encode($objeto);
    }
    /**
     * Limpia el objeto Esquema.
     */
    public function Limpiar() {
        $this->id = null;
        $this->tipo = null;
        $this->clave = null;
        $this->estilo = null;
        $this->clase = null;
        $this->etiqueta = null;
        $this->label = null;
        $this->elementos = null;
        $this->accion = null;
        $this->multicelda = null;
        $this->oculto = null;
        $this->requerido = null;
        $this->desabilitado = null;
        $this->lectura = null;
        $this->onclick = null;
        $this->ondlbclick = null;
        $this->onkeypress = null;
        $this->onkeyup = null;
        $this->onblur = null;
        $this->onchange = null;
        $this->onfocus = null;
        $this->completar = null;
        $this->dependiente = null;
		$this->propiedades = null;
    }
    /**
     * Imprimer los arreglos del objeto.
     */
    public function Ver() {
        print('<pre>');
        print_R($this->aCampos);
        print_R($this->aBotones);
    }

}

?>