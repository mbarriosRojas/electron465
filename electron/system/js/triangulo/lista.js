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
            url : urltriangulo + "BuscaLista/",
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