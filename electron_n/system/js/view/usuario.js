$(function() {
	Crear();
    usuariosActivos();
	//alert(sUrlP);
	/*$("#documento_id").autocomplete({
		source : function(request, response) {
			$.ajax({
				type : "POST",
				url : sUrlP + 'c_usuario',
				data : "documento_id=" + $("#documento_id").val(),
				dataType : "json",
				async : false,
				success : function(data) {
					response($.map(data.respuesta, function(item) {
						//alert(item);
						return {
							label : item,
							value : item
						}
					}));
				},
			});
		}
	});*/
});
function Crear() {
	/*strUrl_Proceso = sUrlP + "GV_Usuario";
	$.ajax({
		url : strUrl_Proceso,
		dataType : "json",
		success : function(oBj) {//alert(oBj);
			vis = new GVista(oBj, 'formulario', 'Usuarios');
			vis.AsignarNombre("Usuarios");
			vis.AsignarCeldas(3);
			vis.AsignarBotones(1);
			vis.Generar();
		}
	});*/
	vis = new GVista(Esq_gv_usuario, 'formulario', 'Usuarios');
	vis.AsignarNombre("Usuarios");
	vis.AsignarCeldas(3);
	vis.AsignarBotones(1);
	vis.Generar();
	return 0;
}

function Crear2() {
	/*strUrl_Proceso = sUrlP + "GV_Usuario";
	 $.ajax({
	 url : strUrl_Proceso,
	 dataType : "json",
	 success : function(oBj) {//alert(oBj);
	 vis = new GVista(oBj, 'formulario', 'Usuarios');
	 vis.AsignarNombre("Usuarios");
	 vis.AsignarCeldas(3);
	 vis.AsignarBotones(1);
	 vis.Generar();
	 }
	 });*/
    vis = new GVista(Esq_gv_usuario, 'formulario', 'Usuarios');
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

function busca_ciu(){
	var ciu =$("#ciu").val();
	$.ajax({
		url : sUrlP + 'busca_ciu',
		data : 'id=' + ciu,
		type : 'POST',
		success : function(cid) {
			$('#estado option[value="'+cid+'"]').attr("selected", true);
			$('#estado').change();
		},
		error : function(error) {
			var er = JSON.stringify(error);
			alert(er);
			
		}
	});
}

function usuariosActivos(){
    $.ajax({
        url : sUrlP + 'usuariosActivos',
        type : 'GET',
        success : function(usu) {
            $('#usuinac').html(usu);
        },
        error : function(error) {
            var er = JSON.stringify(error);
            alert(er);

        }
    });
}

function inactivarUsuario(){
	var id = $("#usuinac option:selected").val();
	if(id == ""){
		alert("Debe seleccionar el usuario a inactivar");
	}else{
        $.ajax({
            url : sUrlP + 'inactivarUsuario',
			data : "id="+id,
            type : 'POST',
            success : function(msj) {
                alert(msj);
                if(msj == "Se inactivo el usuario con exito") usuariosActivos();
            },
            error : function(error) {
                var er = JSON.stringify(error);
                alert(er);

            }
        });
	}
}