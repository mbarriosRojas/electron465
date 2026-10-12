var instancia='';
jQuery(document).ready(function() {
    jQuery("#btn-buscar").on("click",function(){
        consultar();
    });
});

(function() {
    numeral.register('locale', 'es-es', {
        delimiters: {
            thousands: '.',
            decimal: ','
        },
        abbreviations: {
            thousand: 'k',
            million: 'mm',
            billion: 'b',
            trillion: 't'
        },
        ordinal: function (number) {
            var b = number % 10;
            return (b === 1 || b === 3) ? 'er' :
                (b === 2) ? 'do' :
                    (b === 7 || b === 0) ? 'mo' :
                        (b === 8) ? 'vo' :
                            (b === 9) ? 'no' : 'to';
        },
        currency: {
            symbol: 'BS'
        }
    });
    numeral.locale('es-es');
})();

function consultar(){
    $ = jQuery;
    var tipo = $("#contratos").val();
    var tableContainer

    tableContainer = $("#table-1");
    instancia = tableContainer.dataTable({
        destroy: true,
        "sPaginationType": "bootstrap",

        "sDom": "<'row'<'col-xs-6 col-left'l><'col-xs-6 col-right'<'export-data'T>f>r>t<'row'<'col-xs-6 col-left'i><'col-xs-6 col-right'p>>",
        "oTableTools": {
            "aButtons": [
                "csv",
                "xls",
                {
                    "sExtends": "pdf",
                    "sPdfOrientation": "landscape"
                },
                {
                    "sExtends":"print",
                    "sButtonText":"Imprimir"
                }

            ]
        },
        "aLengthMenu": [[10, 25, 5, -1], [10, 25, 5, "Todo"]],
        "bStateSave": true,
        "language": {
            "lengthMenu": "Mostar _MENU_ filas por pagina",
            "zeroRecords": "Nada que mostrar",
            "info": "Mostrando _PAGE_ de _PAGES_",
            "infoEmpty": "No se encontro nada",
            "infoFiltered": "(filtered from _MAX_ total records)",
            "search": "Buscar"
        },
        ajax: {
            "url":urlsumi + 'listarep/'+tipo,
            "type":"POST",
        },
        "columns": [

            {"data": "factura"},
            {"data": "monto_factura"},
            {"data": "monto_limite"},
            {"data": "monto_pagado"},
            {"data": "estatus"},
            {"data": "observacion"},
        ],
        "createdRow": function ( row, data, index ) {
            var val2 = numeral(parseFloat(data.monto_factura)).format('0,0.00')
            var val3 = numeral(parseFloat(data.monto_limite)).format('0,0.00')
            var val4 = numeral(parseFloat(data.monto_pagado)).format('0,0.00')

            $('td', row).eq(1).html(val2);
            $('td', row).eq(2).html(val3);
            $('td', row).eq(3).html(val4);

        },
        "footerCallback": function (row, data, start, end, display) {
            var api = this.api(), data;

            // Remove the formatting to get integer data for summation
            var intVal = function (i) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '') * 1 :
                    typeof i === 'number' ?
                        i : 0;
            };

            // Total over all pages
            total = api
                .column(1)
                .data()
                .reduce(function (a, b) {
                    sun = intVal(a) + intVal(b)
                    return sun;
                }, 0);

            total2 = api
                .column(2)
                .data()
                .reduce(function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0);

            total3 = api
                .column(3)
                .data()
                .reduce(function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0);


            $(api.column(1).footer()).html(
                numeral(total).format('0,0[.]00 $')
            );
            $(api.column(2).footer()).html(
                numeral(total2).format('0,0[.]00 $')
            );
            $(api.column(3).footer()).html(
                numeral(total3).format('0,0[.]00 $')
            );
        }

    });
}

function entregar(fact){
    jQuery('#fact_entregar').val(fact);
    jQuery('#mdl-entrega').modal('show', {backdrop: 'static'});
}

function guardarEntrega(){
    jQuery('#mdl-entrega').modal('hide');
    jQuery('#msj').html('Cargando...');
    jQuery('#mensajes').modal('show', {backdrop: 'static'});
    fact = jQuery("#fact_entregar").val();
    obs = jQuery("#det_entrega").val();

    jQuery.ajax({
        url : urlsumi + "guardarEntrega",
        type : 'POST',
        data : "factura="+fact+"&obs="+obs,
        success : function(resp) {
            jQuery("#msj").html(resp);
            consultar();
            //jQuery('#mensajes').modal('hide');
        }
    });

}

function detalle(fact){
    alert(fact);
}

