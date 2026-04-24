$( function () {
    $("#mbuzon").removeClass('active');
    $("#mreporte").addClass('active');
    $("#fecha_envio").datepicker();
    $("#fecha_recibido").datepicker();
    $("#mesPagar").datepicker();
    $.datepicker.regional['es'] = {
        closeText : 'Cerrar',
        prevText : '&#x3c;Ant',
        nextText : 'Sig&#x3e;',
        currentText : 'Hoy',
        monthNames : ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
        monthNamesShort : ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
        dayNames : ['Domingo', 'Lunes', 'Martes', 'Mi&eacute;rcoles', 'Jueves', 'Viernes', 'S&aacute;bado'],
        dayNamesShort : ['Dom', 'Lun', 'Mar', 'Mi&eacute;', 'Juv', 'Vie', 'S&aacute;b'],
        dayNamesMin : ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'S&aacute;'],
        weekHeader : 'Sm',
        dateFormat : 'yy-mm-dd',
        firstDay : 1,
        isRTL : false,
        showMonthAfterYear : false,
        yearSuffix : ''
    };
    $.datepicker.setDefaults($.datepicker.regional['es']);
    $(".dialogo").dialog({
        modal: true,
        autoOpen: false,
        position: 'top',
        hide: 'explode',
        show: 'slide',
        width: 600,
        height: 340
    });
    $("#msj_alertas").dialog({
        modal: true,
        autoOpen: false,
        position: 'top',
        hide: 'explode',
        show: 'slide',
        width: 600,
        height: 400,
        buttons: {
            "Cerrar": function() {
                $(this).html('');
                $(this).dialog("close");
            }
        }
    });
    $("#filtro_txt").dialog({buttons: {
        "Generar": function() {
            cargarTxt();
            $(this).dialog("close");
        },
        "Cerrar": function() {
            $(this).dialog("close");
        }
    }
    });
    $("#frmConsultar").dialog({buttons: {
        "Ver": function() {
            consultarTxt();
            $(this).dialog("close");
        },
        "Cerrar": function() {
            $(this).dialog("close");
        }
    }
    });
    $("#asignarContrato").dialog({
        modal: true,
        autoOpen: false,
        position: 'top',
        hide: 'explode',
        buttons: {
        "Guardar": function() {
            guardarCambio();
            $(this).dialog("close");
        },
        "Cerrar": function() {
            $(this).dialog("close");
        }
    }
    });
   listaArchivos();
});

function listaArchivos(){
    $("#cmbArchivos").html('');
    $.ajax({
        url : sUrlP + "cmbArchivosVenezuela",
        dataType : "json",
        success : function(data) {
            //alert(JSON.stringify(data));
            $.each(data, function(item, valor) {
                //alert(item+'//'+valor);
                $("#cmbArchivos").append(new Option(valor,item));
            });
            $("#cmbArchivos").append(new Option("Seleccione Archivo", "0"));

        }
    });
}

function MostrarDiv(div) {
    $("#"+div).dialog('open');
}

function cargarTxt(){
    $("#carga_busqueda").dialog('open');
    var empresa = $("#txtEmpresa option:selected").val();
    var banco = "venezuela";
    var fcontrato = $("#txtFormaContrato option:selected").val();
    var periodicidad = $("#txtPeriodicidad option:selected").val();
    var fechaE = $("#fecha_envia").val();
    var fechaR = $("#fecha_recibido").val()

    var inputFileImage = document.getElementById("archivo");
    var file = inputFileImage.files[0];
    var datos = new FormData();
    datos.append('archivo', file);
    datos.append('banco', banco);
    datos.append('fenv', fechaE);
    datos.append('frec', fechaR);
    datos.append('empresa', empresa);
    datos.append('fcontrato', fcontrato);
    datos.append('perio', periodicidad);
    var url = "subirVenezuela";
    var pasa = Validar(file, banco);
    if(pasa == "OK") {
        $.ajax({
            url : sUrlP + url,
            type : "POST",
            data : datos,
            contentType : false,
            processData : false,
            cache : false,
            dataType : "json",
            success : function(respuesta) {//alert(respuesta.msg);
                $("#carga_busqueda").dialog('close');
                if(respuesta.ok != 'si'){
                    $("#msj_alertas").html("<h2>" + respuesta.msg + "</h2>");
                    $("#msj_alertas").dialog('open');
                } else{
                    leerTxt(respuesta.msg.id,respuesta.msg.archivo);
                }


            }
        });
    } else {
        $("#carga_busqueda").dialog('close');
        $("#msj_alertas").html("<h2>" + pasa + "</h2>");
        $("#msj_alertas").dialog('open');

    }
}

function leerTxt(id,arch){
    //alert('id='+id+"//archivo="+arch);
    var msj = "Inicial";
    $.ajax({
        url : sUrlP + 'leerArchivoVenezuela',
        type : "POST",
        data : 'id='+id+'&archivo='+arch,
        success : function(respuesta) {
            //alert(respuesta);
            $("#msj_alertas").html("<h2>" + respuesta + "</h2>");
            $("#msj_alertas").dialog('open');
            listaArchivos();
        }
    });

}

function Validar(archivo, banco) {//alert(banco);
    mensaje = 'OK';
    if(archivo && banco != '') {
        extensiones_permitidas = new Array(".txt", ".csv", ".dvr");
        nombre = $("#archivo").val();
        extension = (nombre.substring(nombre.lastIndexOf("."))).toLowerCase();
        switch(extension) {
            case ".txt":
                if(banco == "PROVINCIAL" || banco == "VENEZUELA") {
                    mensaje = "Formato de Archivo Invalido Para El Banco provincial y venezueka";
                }
                break;
            case ".dvr":
                if(banco != "PROVINCIAL") {
                    mensaje = "Formato de Archivo Invalido Para El Banco provincial";
                }
                break;
            case ".DVR":
                if(banco != "PROVINCIAL") mensaje = "Formato de Archivo Invalido Para El Banco PROVINCIAL2";
                break;
            case ".csv":
                if(banco != "venezuela") {
                    mensaje = "Formato de Archivo Invalido Para El Banco Venezuela";
                }
                break;
            default:
                mensaje = "El formato de archivo seleccionado no se encuentra entre los permitidos";
        }
    } else {
        mensaje = "DEBE INGRESAR TODOS LOS CAMPOS";
    }
    return mensaje;
}

function consultarTxt(){
    var oida = $("#cmbArchivos").val();
    //alert(oida);
    var arch = $("#cmbArchivos option:selected").text();
    if(oida == 0){
        alert('Debe seleccionar un archivo');
        return false;
    }
    $("#Respuesta").html('');
    //alert(sUrlP + "listarArchivoVenezuela");
    $.ajax({
        url : sUrlP + "listarArchivoVenezuela",
        type : "POST",
        data : "oida=" + oida,
        dataType : "json",
        success : function(oEsq) {//alert(oEsq);

            Grid = new TGrid(oEsq, 'Respuesta', 'Archivo:'+arch);
            Grid.SetName("Archivo");
            Grid.SetNumeracion(true);
            //Grid.SetXls(true);
            Grid.Generar();
            $("#carga_busqueda").dialog('close');

        }
    });
}

function cargarCuota(id,cedula){
    //alert(id+'//'+cedula)
    $("#idcuota").val(id);
    $("#cedulacuota").val(cedula);
    cargarContratos(cedula);
}


function cargarContratos(ced){
    $("#cmbContratos").html('');
    //alert(ced);
    $.ajax({
        url : sUrlP + "cmbContratosVenezuela",
        type : "post",
        data : "cedula="+ced,
        dataType : "json",
        success : function(data) {
            //alert(data);
            $.each(data, function(item, valor) {
                //alert(item+'//'+valor);
                $("#cmbContratos").append(new Option(valor,item));
            });
            $("#cmbContratos").append(new Option("Seleccione Contrato", "0"));
            $("#asignarContrato").dialog('open');
        }
    });
}

function guardarCambio(){
    var oid = $("#idcuota").val();
    var contrato = $("#cmbContratos").val();
    var frec = $("#mesPagar").val();
    var obser = $("#obser").val();
    if(contrato == 0){
        alert("Debe seleccionar un contrato");
        return false
    }
    $.ajax({
        url : sUrlP + 'modCuotaVenezuela',
        type : "POST",
        data : 'oid='+oid+'&contrato='+contrato+"&frec="+frec+"&obser="+obser,
        success : function(respuesta) {
            alert(respuesta);

        }
    });


}