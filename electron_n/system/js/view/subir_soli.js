$(function() {
	Crear(); //funcion que crea la vista para asignar imagenes a expedientes subidos desde ftp a la carpeta de solicitus
	Filtro();
	var dates = $("#desde, #hasta").datepicker({
		showOn : "button",
		buttonImage : sImg + "calendar.gif",
		buttonImageOnly : true,
		onSelect : function(selectedDate) {
			var option = this.id == "desde" ? "minDate" : "maxDate", instance = $(this).data("datepicker"), date = $.datepicker.parseDate(instance.settings.dateFormat || $.datepicker._defaults.dateFormat, selectedDate, instance.settings);
			dates.not(this).datepicker("option", option, date);
		}
	});
	$("#desde").datepicker("option", "dateFormat", "yy-mm-dd");
	$("#hasta").datepicker("option", "dateFormat", "yy-mm-dd");
	$("#msj_alertas").dialog({
		modal : true,
		autoOpen : false,
		position : 'top',
		hide : 'explode',
		show : 'slide',
		width : 600,
		height : 400,
		buttons : {
			"Cerrar" : function() {
				$(this).html('');
				$(this).dialog("close");
			}
		}
	});
});

function cargar(pos,nombre,ced,cod,ubi){
	var strUrl_Proceso = sUrlP + "Mueve_Solicitud";
	var carp = $("#"+pos+"combo").val();
	//alert(carp);
	var datos = 'carpeta=' + carp + '&nombre=' + nombre + '&cedula=' + ced + '&cod='+cod+"&ubi="+ubi;
	$.ajax({
		url : strUrl_Proceso,
		type : 'POST',
		data : datos,
		// dataType : 'json',
		success : function(html) {
			alert(html);
			consultar_imagenes();
		}
	});
	
}

function Crear() {
	strUrl_Proceso = sUrlP + "GV_Bimg_Soli";
	vis = new GVista(strUrl_Proceso, 'vista', 'Buscar Imagenes de Solicitud','');
	vis.Obtener_Json();
    vis.AsignarCeldas(4);
    vis.Generar();
}

function Filtro(){
	strUrl_Proceso = sUrlP + "GV_FSoli2";
	vis = new GVista(strUrl_Proceso, 'filtro', 'Filtro de Solicitud','');
	vis.Obtener_Json();
    vis.AsignarCeldas(2);
    vis.AsignarBotones(1);
    vis.Generar();
}

function consultar_imagenes() {
	var ced = $("#ced").val();
	var cod = $("#cod").val();
	var ubi = $("#ubic option:selected").val();
	if(ced == '' || cod == ''){
		alert("Debe ingresar Cedula, Codigo Y Ubicacion");
		return 0; 
	}
	
	strUrl_Proceso = sUrlP + "Busca_Imagenes_Soli";
	sImgR = sUrl + '/system/repository/solicitud/'+ubi+'/';
	var datos = "ced="+ced+"&cod="+cod+"&ubi="+ubi;
	var html = '';
	$("#imagenes0").html('');
	if (id != '') {
		$.ajax({
			url : strUrl_Proceso,
			type : 'POST',
			data : datos,
			dataType : 'json',
			success : function(json) {
				//alert(json);
				//alert(sImgR+json['ruta']);
				var img = '<div class="single first"><br>CEDULA:'+json['cedula']+'<br>Nombre:'+json['nombre']+'<br>';
				var i=0;
				$.each(json['imagenes'], function(k, v) {
					i++;
					img += '<table><tr><td><a href="'+sImgR+json['ruta']+"/"+v+'" rel="lightbox[docu1]" title="">';
					img += '<img src="'+sImgR+json['ruta']+"/"+v+'" alt="" style="width:100px;height:100px;" /></a></td>';
					img += '<td><SELECT id="'+i+'combo"><option value="t_expcedula|personales">CEDULA</option><option value="t_expcartas|cartas">CARTAS</option>';
					img += '<option value="t_expbanco|bancos">BANCOS</option>';
					img += '<option value="t_expfiador|fiador">FIADOR</option><option value="t_exprif|rif">RESPALDO RIF</option></select></td>';
					img += '<td><center><img src="'+ sImg +'botones/aceptar1.png" onclick="cargar('+ i +',\''+ v +'\',\''+ ced +'\',\''+ cod +'\',\''+ ubi +'\');" ></img></center></td></tr></table>';
				});
				img += '</div>';
				//alert(img);
				$("#imagenes0").append(img);
			
			},
			error : function(err) {
				alert("NO SE PUDO ENCONTRAR LA CEDULA O EL DIRECCTORIO DEL CERTIFICADO");
				/*alert(err['responseText']);
				$.each(err, function(k, v) {
					alert(k+":"+v);
				});*/
			}
		});
	} else {
		alert('DEBE INGRESAR UNA CERTIFICADO');
	}
}

function buscar(){
	var ruta = sUrlP + "Listar_Soli_Ubi";
	var desde = $("#desde").val();
	var hasta = $("#hasta").val();
	if(desde == '' || hasta == ''){
		alert("Debe ingresar fecha desde hasta");
		return 0;
	}
	var datos = "banco="+$("#fbanco option:selected").val() + "&nomina=" +$("#fnomina option:selected").val();
	datos += "&desde="+desde+"&hasta="+hasta+"&estatus="+$("#estatus option:selected").val()+"&cubica="+$("#cubica option:selected").val(); 
	$("#reporte").html('');
	$.ajax({
		url : ruta,
		type : 'POST',
		data : datos,
		dataType : 'json',
		success : function(json) {//alert(json);
			//alert(json['sql']);
			if(json['resp']==1){
				Grid = new TGrid(json, 'reporte', 'Lista de Solicitud');
				Grid.SetXls(true);
				Grid.SetNumeracion(true);
				Grid.SetName("Reportes");
				Grid.SetDetalle();
				Grid.Generar();
			}else{
				alert("la busqueda no dio resultados..");
			}
		}
	});
}