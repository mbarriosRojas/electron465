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

    $("#fecha_envio").change(function () {
        consultarTasaEnvio();
    });

    $(".dialogo").dialog({
        modal: true,
        autoOpen: false,
        position: 'top',
        hide: 'explode',
        show: 'slide',
        width: 620,
        height: 380
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
        appendTo: 'body',
        hide: 'fade',
        title: 'Asignar Contrato',
        buttons: {
        "Guardar": function() {
            var dlg = $(this);
            guardarCambio(function() {
                dlg.dialog("close");
            });
        },
        "Cerrar": function() {
            $(this).dialog("close");
        }
    }
    });
   var oidaRecordado = sessionStorage.getItem('cargaVenezuela_oida');
   sessionStorage.removeItem('cargaVenezuela_oida');
   listaArchivos(oidaRecordado);
   consultarTasaEnvio();

   window.onGridGuardadoOk = function(funcion) {
       if (funcion === 'Guardar_Lote_Venezuela') {
           reiniciarPantallaVenezuela();
       }
   };
});

function apiCobranza() {
    return (typeof sUrlCobranza !== 'undefined') ? sUrlCobranza : sUrlP;
}

function consultarTasaEnvio() {
    var fecha = $("#fecha_envio").val();
    if (!fecha) {
        $("#info_tasa_envio").html('');
        return;
    }
    $.ajax({
        url: apiCobranza() + "getTasaPorFecha",
        type: "POST",
        data: "fecha=" + encodeURIComponent(fecha),
        dataType: "json",
        success: function (data) {
            if (data.ok) {
                $("#info_tasa_envio").html(
                    '<span style="color:#1964B5;">Tasa para conversi&oacute;n USD: <strong>'
                    + parseFloat(data.precio).toLocaleString('es-VE', { minimumFractionDigits: 4, maximumFractionDigits: 4 })
                    + ' Bs./USD</strong> (fecha ' + data.fecha + ')</span>'
                );
            } else {
                $("#info_tasa_envio").html(
                    '<span style="color:#b70000;">Sin tasa registrada para esa fecha. Reg&iacute;strela en Panel &rarr; Precio del D&oacute;lar.</span>'
                );
            }
        }
    });
}

function listaArchivos(seleccionar){
    $("#cmbArchivos").html('');
    $.ajax({
        url : apiCobranza() + "cmbArchivosVenezuela",
        dataType : "json",
        success : function(data) {
            $.each(data, function(item, valor) {
                $("#cmbArchivos").append(new Option(valor,item));
            });
            $("#cmbArchivos").append(new Option("Seleccione Archivo", "0"));
            if (seleccionar !== undefined && seleccionar !== null) {
                $("#cmbArchivos").val(String(seleccionar));
                consultarTxt();
            }
        }
    });
}

function reiniciarPantallaVenezuela() {
    $("#Respuesta").html('');
    listaArchivos(0);
}

function MostrarDiv(div) {
    $("#"+div).dialog('open');
    if (div === 'filtro_txt') {
        consultarTasaEnvio();
    }
}

function cargarTxt(){
    var fechaE = $("#fecha_envio").val();
    var fechaR = $("#fecha_recibido").val();
    if (!fechaE || !fechaR) {
        alert("Indique fecha enviado y fecha recibido.");
        return;
    }

    $("#carga_busqueda").dialog('open');
    var empresa = $("#txtEmpresa option:selected").val();
    var banco = "venezuela";
    var fcontrato = $("#txtFormaContrato option:selected").val();
    var periodicidad = $("#txtPeriodicidad option:selected").val();

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
            url : apiCobranza() + url,
            type : "POST",
            data : datos,
            contentType : false,
            processData : false,
            cache : false,
            dataType : "json",
            success : function(respuesta) {
                $("#carga_busqueda").dialog('close');
                if(respuesta.ok != 'si'){
                    $("#msj_alertas").html("<h2>" + respuesta.msg + "</h2>");
                    $("#msj_alertas").dialog('open');
                } else{
                    var extra = '';
                    if (respuesta.tasa && respuesta.tasa.precio) {
                        extra = '<p>Tasa aplicada: <b>' + respuesta.tasa.precio + ' Bs./USD</b></p>';
                    }
                    leerTxt(respuesta.msg.id, respuesta.msg.archivo, extra);
                }
            }
        });
    } else {
        $("#carga_busqueda").dialog('close');
        $("#msj_alertas").html("<h2>" + pasa + "</h2>");
        $("#msj_alertas").dialog('open');
    }
}

function leerTxt(id, arch, extraHtml){
    $.ajax({
        url : apiCobranza() + 'leerArchivoVenezuela',
        type : "POST",
        data : 'id='+id+'&archivo='+encodeURIComponent(arch),
        success : function(respuesta) {
            $("#msj_alertas").html("<h2>" + respuesta + "</h2>" + (extraHtml || ''));
            $("#msj_alertas").dialog('open');
            listaArchivos();
        }
    });
}

function Validar(archivo, banco) {
    mensaje = 'OK';
    if(archivo && banco != '') {
        nombre = $("#archivo").val();
        extension = (nombre.substring(nombre.lastIndexOf("."))).toLowerCase();
        switch(extension) {
            case ".dvr":
            case ".DVR":
                if(banco != "PROVINCIAL") {
                    mensaje = "Formato de Archivo Invalido Para El Banco provincial";
                }
                break;
            case ".csv":
            case ".txt":
            case ".xlsx":
            case ".xls":
                if(banco == "PROVINCIAL") {
                    mensaje = "Formato de Archivo Invalido Para El Banco provincial";
                } else if(banco != "venezuela") {
                    mensaje = "Formato de Archivo Invalido Para El Banco Venezuela";
                }
                break;
            default:
                mensaje = "Formatos permitidos para Venezuela: TXT, XLSX o CSV (antiguo)";
        }
    } else {
        mensaje = "DEBE INGRESAR TODOS LOS CAMPOS";
    }
    return mensaje;
}

function consultarTxt(){
    var oida = $("#cmbArchivos").val();
    var arch = $("#cmbArchivos option:selected").text();
    if(oida == 0){
        alert('Debe seleccionar un archivo');
        return false;
    }
    $("#Respuesta").html('');
    $.ajax({
        url : apiCobranza() + "listarArchivoVenezuela",
        type : "POST",
        data : "oida=" + oida,
        dataType : "json",
        success : function(oEsq) {
            Grid = new TGrid(oEsq, 'Respuesta', 'Archivo:'+arch);
            Grid.SetName("Archivo");
            Grid.SetNumeracion(true);
            Grid.Generar();
        }
    });
}

function cargarCuota(id,cedula){
    $("#idcuota").val(id);
    $("#cedulacuota").val(cedula);
    cargarContratos(cedula);
}

function cargarContratos(ced){
    $("#cmbContratos").html('');
    $.ajax({
        url : apiCobranza() + "cmbContratosVenezuela",
        type : "post",
        data : "cedula="+ced,
        dataType : "json",
        success : function(data) {
            $.each(data, function(item, valor) {
                $("#cmbContratos").append(new Option(valor,item));
            });
            $("#cmbContratos").append(new Option("Seleccione Contrato", "0"));
            $("#asignarContrato").dialog('open');
        }
    });
}

function guardarCambio(onSuccess){
    var oid = $("#idcuota").val();
    var contrato = $("#cmbContratos").val();
    var frec = $("#mesPagar").val();
    var obser = $("#obser").val();
    if(contrato == 0){
        alert("Debe seleccionar un contrato");
        return false;
    }
    $.ajax({
        url : apiCobranza() + 'modCuotaVenezuela',
        type : "POST",
        data : 'oid='+oid+'&contrato='+contrato+"&frec="+encodeURIComponent(frec)+"&obser="+encodeURIComponent(obser),
        success : function(respuesta) {
            alert(respuesta);
            sessionStorage.setItem('cargaVenezuela_oida', $("#cmbArchivos").val());
            if (typeof onSuccess === 'function') {
                onSuccess();
            }
            location.reload();
        }
    });
    return true;
}
