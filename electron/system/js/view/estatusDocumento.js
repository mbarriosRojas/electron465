$(function() {
    var dates = $("#desde, #hasta").datepicker({
        showOn : "button",
        buttonImage : sImg + "calendar.gif",
        buttonImageOnly : true,
        onSelect : function(selectedDate) {
            var option = this.id == "desde" ? "minDate" : "maxDate", instance = $(this).data("datepicker"), date = $.datepicker.parseDate(instance.settings.dateFormat || $.datepicker._defaults.dateFormat, selectedDate, instance.settings);
            dates.not(this).datepicker("option", option, date);
        }
    });

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
        dateFormat : 'dd/mm/yy',
        firstDay : 1,
        isRTL : false,
        showMonthAfterYear : false,
        yearSuffix : ''
    };
    $.datepicker.setDefaults($.datepicker.regional['es']);
    $("#desde").datepicker("option", "dateFormat", "yy-mm-dd");
});

function listar(){
    var estatus = $("#estatus option:selected").val();
    var tipo = $("#tipo option:selected").val();
    var desde = $("#desde").val();
    var hasta = $("#hasta").val();
    strUrl_Proceso = sUrlP + "listarRecepcionDocu";
    //alert(strUrl_Proceso);
    $.ajax({
        url : strUrl_Proceso,
        type : "POST",
        data:"estatus="+estatus+"&desde="+desde+"&hasta="+hasta+"&tipo="+tipo,
        dataType : "json",
        success : function(oBj) {//alert(oBj);
            if(oBj.msj){
                Grid = new TGrid(oBj, 'lista', 'Lista de Documentos');
                Grid.SetXls(true);
                Grid.SetNumeracion(true);
                Grid.SetName("lista");
                Grid.Generar();
            }else{
                $("#lista").html('');
                alert("No existen Documentos para la busqueda seleccionada...");
            }
        }
    });
    return 0;
}