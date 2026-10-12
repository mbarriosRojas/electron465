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
    $("#hasta").datepicker("option", "dateFormat", "yy-mm-dd");

    $("#btn-buscar").on("click",function(){
        buscar();
    });

});

function buscar(){
    if($("#desde").val() == '' || $("#hasta").val() == ''){
        alert("Debe ingresar periodo a consultar");
        return false;
    }
    sData = "desde="+$("#desde").val()+"&hasta="+$("#hasta").val()+"&tipo="+$("#tipo").val();
    $.ajax({
        url : sUrlP + "listaModificaciones",
        type : "POST",
        data : sData,
        dataType : "json",
        success : function(oEsq) {
            //alert(oEsq);
            Grid = new TGrid(oEsq, 'Reportes', "Modificaciones");
            Grid.SetXls(true);
            Grid.SetNumeracion(true);
            Grid.SetName("Reportes");
            Grid.Generar();
            $("#carga_busqueda").dialog('close');
        }
    });
}