jQuery(document).ready(function() {
    
});

function guardar(){
    var nombre = jQuery("#nombre").val();
    

    jQuery.ajax({
        url : urlsumi + "guardarBien",
        data: "nombre="+nombre,
        type : 'POST',
        success : function(rep) {
            jQuery('form').each (function(){
                this.reset();
            });
            jQuery('#msj').html(rep);
            jQuery('#mensajes').modal('show', {backdrop: 'static'});
            limpiar();
        }
    });
    return false;
}
