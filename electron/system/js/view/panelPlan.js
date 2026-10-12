$(function() {
    Crear();
    Listar();
});
function Crear() {
    vis = new GVista(Esq_planCorporativo, 'formulario', 'Usuarios');
    vis.AsignarNombre("Usuarios");
    vis.AsignarCeldas(3);
    vis.AsignarBotones(1);
    vis.Generar();
    return 0;
}

function soloNumeros(e) {
    key = e.keyCode || e.which;
    tecla = String.fromCharCode(key).toLowerCase();
    numeros = "0123456789";
    especiales = [8, 37, 39, 46];

    tecla_especial = false
    for (var i in especiales) {
        if (key == especiales[i]) {
            tecla_especial = true;
            break;
        }
    }

    if (numeros.indexOf(tecla) == -1 && !tecla_especial) {
        return false;
    }
}

function Listar() {
    $.ajax({
        url : sUrlP + "listarPlanesModificar",
        type : "POST",
        dataType : "json",
        success : function(oEsq) {
            if (oEsq.msj != "NO") {
                Grid = new TGrid(oEsq, 'lista', 'Lista De Planes');
                Grid.SetXls(true);
                Grid.SetNumeracion(true);
                Grid.SetName("plan");
                Grid.Generar();
            }else{
                alert("No se pudo generar el grid");
            }
        }
    });
}
