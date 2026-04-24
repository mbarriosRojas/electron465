var nombre;
var cedula;
$(function() {
	var tipo = $("#tipo").val();
	var id = $("#id").val();
	var ced = $("#ced").val();
	consulta_cliente(ced);
	Imprimir(tipo, id);
	
});

function consulta_cliente(id) {
	if (id == '') {
		alert('Debe ingresar una cedula');
		return 0;
	}
	strUrl_Proceso = sUrlP + "ProcesarDatosBasicos";
	$.ajax({
		url : strUrl_Proceso,
		type : 'POST',
		data : 'id=' + id,
		dataType : 'json',
		success : function(json) {
			if (json['primer_nombre'] != null) {
				cedula = json["documento_id"];
				pnom = json["primer_nombre"];
				snom = json["segundo_nombre"];
				pape = json["primer_apellido"];
				sape = json["segundo_apellido"];
				nombre = pnom+' '+snom+' '+pape+' '+sape;
				foto = json['fotoc'];
				naci = json["nacionalidad"];$("#corre_est").val(json['correo']);
				var htmlDatos = '<center><table class=""><thead>';
				htmlDatos += '<th>CEDULA</th><td>'+naci+cedula+'</td><th>TELEFONOS</th><td>'+json['telefono']+'</td><th>EMAIL</th><td>'+json['correo']+'</td></thead>';
				htmlDatos += '<thead><th>NOMBRE Y APELLIDOS</th><td colspan=3>'+nombre+'</td><th>CODIGO CLIENTE</th><td>'+json['nro_documento']+'</td></thead></table></center><br><br>';
				$('#Datos_Basicos').html(htmlDatos);
				
				disponibilidad = json["disponibilidad"];
				if (disponibilidad != 0 && disponibilidad != undefined) {
					if (disponibilidad == 1) mensaje = "El cliente esta actualmente suspendido:";
					if (disponibilidad == 2) mensaje = "El cliente esta actualmente BLOQUEADO del sistema<br>No se le consedera ningun credito<br>Para mayor informacion Comunicarse con la oficina principal :";
					alert(mensaje);
				}
				verificar_deuda(id);
				lista_asociados(id);
				historial(id);
			}else{
				$('#Datos_Basicos').html("El cliente debe estar registrado..");
				alert("El cliente debe estar registrado..");
			}
		}
	});	
	return true;
}

function Imprimir(tipo, id) {	
	$.ajax({
		url : sUrlP + 'GPNEstado_Cuenta/'+tipo+'/'+id,
		type : "POST",
		dataType : "json",
		success : function(oEsq) {
			if (oEsq.compuesto != '' && oEsq.compuesto != null) {
				var i=0;
				var div_origen = document.getElementById('estado_cuenta');
				$.each(oEsq.objetos, function(sId_Objeto, sObjeto) {
					i++;
					var div = document.createElement('div');
					div.id = 'estado_cuenta_'+i;
					div_origen.appendChild(div);
					
					eval("GridDetalle_"+i+" = new TGrid(sObjeto.grid,div.id, '')");
					eval("GridDetalle_"+i+".SetName('contrato"+i+"');");
					eval("GridDetalle_"+i+".SetEstilo('');");
					eval("GridDetalle_"+i+".Generar();");
					
				});
				//$("#"+oEsqD,fila+"_Detalle0").html('');
			} else {
				GridDetalle = new TGrid(oEsq,"estado_cuenta", 'epale');
				GridDetalle.SetName("esta_cuent");
				GridDetalle.SetNumeracion(true);
				GridDetalle.SetEstilo('');
				GridDetalle.Generar();
			}
		}
	});
}

function pdf(){
	
	//var sUrl = 'http://www.cooperativaelectron.com.ve/';
	//var sUrlP = sUrl + '/index.php/cooperativa/';
	var sUrl = 'http://' + window.location.hostname;
	var sUrlP = sUrl + '/cooperativa-electron/index.php/cooperativa/';
	var cuerpo = $('#medio').html();
	var estado = cuerpo;
	var correo = $("#corre_est").val();
	//alert(sUrlP);
	//alert(nombre+' '+cedula);
	if(correo!=''){
		$.ajax({
			url : sUrlP + 'Envia_Estado_Cuenta',
			type : "POST",
			data: "estado="+estado+"&correo="+correo+"&nombre="+nombre+"&ced="+cedula,
			success : function(resp) {
				alert('Se proceso');
				$("#descarga").html(resp);
			}
		});
	}else{
		alert("No posee Correo electronico...");
	}
	return false;
	
}
