jQuery(document).ready(function() {
    jQuery("#btn-buscar").on("click",function(){
        consultar();
    });
});

function consultar(){

    var tipo = jQuery("#contratos").val();
    if(tipo != ''){
        jQuery('#msj').html('Cargando...');
        jQuery('#mensajes').modal('show', {backdrop: 'static'});
        jQuery.ajax({
            url : urlsumi + "BuscaContratos/",
            type : 'POST',
            data : "tipo="+tipo,
            dataType:"json",
            success : function(datos) {
                //alert(datos);
                jQuery("#respuesta").html(datos.datos);
                jQuery('#mensajes').modal('hide');
            }
        });
    }
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

