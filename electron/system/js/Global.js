/**
 * ENTORNO GLOBAL DE ACCIONES DEL SISTEMA
 *
 * DESARROLLADO POR: CARLOS PEÑA
 * FECHA DE ELABORACION: 27/05/2012
 */

var sUrl = window.location.origin + '/';
var sUrlP = sUrl + 'index.php/cooperativa/';
var sUrlPanel = sUrl + 'index.php/panel/';
var sUrlCobranza = sUrl + 'index.php/cobranza/';
var sImg = sUrl + 'system/img/';
var dblTime;

//setInterval( function () {_getUsrConect();}, 1000); //Funcion de Cargar Usuarios

function detener(){
  //alert("Su sesión ha expirado...");
  clearTimeout(dblTime);
  //if($("#lbl_usu").text() != 'alvaro') dblTime = setTimeout('alert(\'¡Su sesión ha expirado!\');location="' + sUrlP + 'logout"',600000);
}


$( function () {
    //Funciones del Chat Controlan la salida...
	//if($("#lbl_usu").text() != 'alvaro') dblTime = setTimeout('alert(\'¡Su sesión ha expirado!\');location="' + sUrlP + 'logout"',600000);
	
    $("body").on( "click", function() {
      detener();
    });
    originalTitle = document.title;
    //startChatSession(sUrl);
    $([window, document]).blur( function() {
        windowFocus = false;
    }).focus( function() {
        
        windowFocus = true;        
        document.title = originalTitle;
    });
    $("button").button();
    //Control del Boton Buscar en general Estilo
    $('#btnTodo').hover( function() {
        $(this).addClass('ui-state-hover');
    }, function() {
        $(this).removeClass('ui-state-hover');
    }
    );
    $("#buscando" ).accordion({collapsible: false});
    $("#general" ).accordion({collapsible: false, active: 0, autoHeight: false, navigation: true});
    $("button").button();
    $("#buscar").button({ icons: {primary:'ui-icon-circle-zoomin',} });
    $('#tabs').tabs();
    $("input").keyup( function() {
        var value = $(this).val();
        $(this).val(value.toUpperCase());
    });
    $("textarea").keyup( function() {
        var pos_act = $(this).scrollTop();
        var value = $(this).val();
        $(this).val(value.toUpperCase());
        $(this).scrollTop(pos_act);
    });
    $('#msj_alertas').dialog({
        modal: true,
        autoOpen: false,
        width: 260,
        height: 160,
        buttons: {
            "Cerrar": function() {
                $(this).dialog("close");
            },
        }
    });
    $('#carga_busqueda').dialog({
            modal: true,
            autoOpen: false,
            width: 260,
            height: 160,
            
    }); 
    

});
/**
 * Control de Usuarios Conectados al Sistema.
 */
function _getUsrConect() {
    $.ajax({
        url : sUrlP + 'getUsrConnect',
        dataType : 'json',
        success : function(json) {            
            $('#iContador').html('Lo Nuevo (' + json["cant"] + ')');
        }
    });
}

    function N_Ventana (URL){   window.open(URL,"ventana1","toolbar=0,location=1,menubar=0,scrollbars=1,resizable=1,width=800,height=800")}
