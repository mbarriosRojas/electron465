jQuery(document).ready(function() {

});

function buscar(i){
    var fact = jQuery("#fact"+i).val();
    if(fact != '' && fact != 0){
        jQuery.ajax({
            url : urltriangulo + "get/",
            type : 'POST',
            data: "fact="+fact,
            dataType : "json",
            success : function(json) {//alert(JSON.stringify(json));
                if(json.filas != 0){
                    llenar(json.datos[0],i);
                }else{
                    alert(json.datos);
                    jQuery("#fact"+i).val('');
                }

            }
        });
    }
}

function llenar(dat,i){
    jQuery("#ced"+i).val(dat.ced);
    jQuery("#afiliado"+i).html("CEDULA:"+dat.ced+"<br>NOMBRE:"+dat.primer_nombre+" "+dat.primer_apellido+"<br>Nomina:"+dat.nomina_procedencia+"<br>Banco1:"+dat.banco_1+"<br>Cuenta1:"+dat.cuenta_1);

}

function limpiar(){
    jQuery("#afiliado1").html('');
    jQuery("#afiliado2").html('');
    jQuery("#afiliado3").html('');
}

function guardar(){
    datos = jQuery("form").serialize();

    jQuery.ajax({
        url : urltriangulo + "guardar",
        data: datos,
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
