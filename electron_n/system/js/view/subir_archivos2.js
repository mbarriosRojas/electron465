var DG;
var sImg = sUrl + '/system/img/';
var sImgC = sUrl + '/system/repository/expedientes/';
var ele_car=[];
ele_car[0]= ['imgCedula','fecCedula',sImgC+'personales/','Cedula','cedula'];
ele_car[1]= ['imgFacturas','fecFacturas',sImgC+'facturas/','Facturas','factura'];
ele_car[2]= ['imgBancos','fecBancos',sImgC+'bancos/','Bancos','banco'];
ele_car[3]= ['imgCartas','fecCartas',sImgC+'cartas/','Cartas','carta'];
ele_car[4]= ['imgFiador','fecFiador',sImgC+'fiador/','Fiador','fiador'];
ele_car[5]= ['imgRif','fecRif',sImgC+'rif/','Rif','rif'];
ele_car[6]= ['imgGarantia','fecGarantia',sImgC+'garantia/','Garantia','garantia'];
$(function() {
	limpiar();
	$("#id_contenido").hide();
});

function Ver_Imagenes(id){
	nivel = $("#nivel").val();
	usu = $("#usu").val();
	$("#li_"+id).hide();
	var tit = ele_car[id][3];
	var ruta = ele_car[id][2];
	var fec = ele_car[id][1];
	var iden = ele_car[id][0];
	var eli = ele_car[id][4];
	//alert(DG[iden].length);
	if(DG[iden] != ''){
		var div = '<div class="imageRow"><div class="set" ><br><h2 class="etiqueta">'+tit+'</h2><br><br>';
		var comp='';
		var aux = '';
		var i=0;
		if(iden=='imgCedula'){
			img = '<div class="single"><table><tr><td class="etiqueta2">'+DG[fec]+'<br><a href="'+ruta+DG[iden]+'" rel="lightbox[docu4]" title=""><img src="'+ruta+DG[iden]+'" alt="" style="width:150px;height:150px;" /></a></td>';
			if(nivel==0 || nivel==18  || nivel==15 || nivel==8 || nivel==9 || usu == 'Carlos' || usu == 'AlvaroZ'){
				img += '<tr><td><center><img src="'+ sImg +'botones/quitar.png" onclick="Eliminar(\''+eli+'\',\''+ DG[iden] +'\');" ></img></center></td></tr>';
			}
			img += '</table></div>';
			
			div+=img;
		}else{
			$.each(DG[iden], function(k, v) {
				aux=DG[fec][k].split(" ");
				if(i != 0){
					if(aux[0]!=comp ){
						comp=aux[0];
						div+='</div><div class="set" ><br><h2  class="etiqueta2">'+comp+'</h2><br><br>';
					}
				}else{
					comp=aux[0];
					div += '<div class="set" ><br><h2  class="etiqueta2">'+comp+'</h2><br><br>';
					i++;
				}
				cadena = v;
				cadena_aux = cadena.split('.');
				if(cadena_aux[1] == 'pdf'){  
				  img = '<div class="single first"><table><tr><td><center>'+aux[1]+'<br><a href="'+ruta+v+'" title=""><img src="'+sImg+'/pdf.png" alt="" style="width:150px;height:150px;" /></a></center></td>';
				}else{
					img = '<div class="single"><table><tr><td><center>'+aux[1]+'<br><a href="'+ruta+v+'" rel="lightbox[docu4]" title=""><img src="'+ruta+v+'" alt="" style="width:150px;height:150px;" /></a></center></td>';  
				}
				if(nivel==0 || nivel==18  || nivel==15 || nivel==8 || nivel==9 || usu == 'Carlos' || usu == 'AlvaroZ'){
					img += '<tr><td><center><a href="#" onclick="Eliminar(\''+eli+'\',\''+ v +'\');"><span class="ca-icon2">-</span>-</a></center></td></tr>';
				}
				img += '</table></div>';
				div+=img;
				
			});
		}
		div += '</div></div>';
		$("#imagenes").prepend(div);
		$("#imagenes").show();
	}else{
		var msj ='No posee imagenes en el apartado para '+ ele_car[id][3] ; 
		alert(msj);
	}
}
				
function Eliminar(tipo,nombre){
	var resp = confirm("¿Desea eliminar el archivo?");
	if(!resp){
		alert('Se cancelo el proceso');
		return false;
	}
	strUrl_Proceso = sUrlP + "Eliminar_Expediente";
	// alert(tipo+'//'+nombre);
	var id = $("#cedula").val();
	if (id != '') {
		$.ajax({
			url : strUrl_Proceso,
			type : 'POST',
			data : 'id=' + id + '&tipo='+tipo+'&nombre='+nombre,
			// dataType : 'json',
			success : function(html) {
				alert(html);
				consultar_datos()
			}
		});
	}
}

function limpiar(){
	$("#combos").hide();
	$("#bancos").html('');
	$("#cartas").html('');
	$("#facturas").html('');
	$("#cheques").html('');
	$("#id_contenido").hide();
	$("#imagenes0").hide();
	$("#imagenes1").hide();
	$("#imagenes2").hide();
	$("#imagenes3").hide();
	$("#imagenes4").hide();
	$("#imagenes5").hide();
	$("#imagenes6").hide();
	$("#datos_cliente").html('');
}

function Ver(){
	
}

function consultar_datos() {
	strUrl_Proceso = sUrlP + "objCExpediente";
	var id = $("#cedula").val();
	var html = '';
	//alert(sUrlP);
	$("#imagenes").html('');
	if (id != '') {
		$.ajax({
			url : strUrl_Proceso,
			type : 'POST',
			data : 'id=' + id,
			dataType : 'json',
			success : function(json) {
				DG = json;
				//alert(DG['nombre']);
				$("#id_contenido").show();
				$("#datos_cliente").show();
				if (json['cedula'] == "NULL" || json['cedula'] == "0") {
					alert('NO SE ENCONTRO REGISTROS ASOCIADOS A LAS C&Eacute;DULA...');
					limpiar();
				} else {
					nombre = DG['nombre'];
					html += 'Nombre:' + nombre + '<br>';
					//alert(html);
					$("#datos_cliente").html(html);
				}
				for(i=0;i<=6;i++){
					$("#li_"+i).show();
				}
			},
			error : function(err) {
				alert("NO SE PUDO ENCONTRAR LA CEDULA");
				limpiar();
			}
		});
	} else {
		alert('DEBE INGRESAR UNA CEDULA');
	}
	return false;
}