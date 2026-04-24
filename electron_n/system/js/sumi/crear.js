jQuery(document).ready(function() {
    jQuery("#factura").on("blur",function(){
        consultar();
    });
});

function consultar(){
    var fact = jQuery("#factura").val();
    if(fact != '' && fact != 0){
        jQuery.ajax({
            url : urlsumi + "get/",
            type : 'POST',
            data: "fact="+fact,
            dataType : "json",
            success : function(json) {//alert(JSON.stringify(json));
                if(json.filas != 0){
                    llenar(json);
                }else{
                    alert("No se Encontro Factura");
                    jQuery("#factura").val('');
                }

            }
        });
    }
}

function llenar(dat){
    jQuery("#contratos").val(dat.contratos);
    jQuery("#MFactura").val(dat.monto);

    jQuery("#LMFactura").val(dat.monto*0.3);
}

function limpiar(dat){
    jQuery("#contratos").val("");
    jQuery("#MFactura").val("");
    jQuery("#LMFactura").val("");
    jQuery("#producto").val("");
    jQuery("#factura").val("");

}
function guardar(){
    var contratos = jQuery("#contratos").val();
    var monto_factura = jQuery("#MFactura").val();
    var monto_l = jQuery("#LMFactura").val();
    var producto = jQuery("#producto").val();
    var fact = jQuery("#factura").val();

    jQuery.ajax({
        url : urlsumi + "guardar",
        data: "contratos="+contratos+"&monto_factura="+monto_factura+"&producto="+producto+"&monto_limite="+monto_l+"&factura="+fact,
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
