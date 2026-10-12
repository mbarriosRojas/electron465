@CHARSET "UTF-8";

/*
	-----------------------------------------------------------------------
		Hoja de Estilo CSS
		Desarrollado Por: Carlos Peña a PHP
		Cuando defino (*) en el Style llamo a todos los eventos de la páginas
		Reglas generales
	------------------------------------------------------------------------
	*/
* {
	margin: 0;
	padding: 0;
	clear: none;
	float: none;
	height: auto;
	width: auto;
	margin-left: 0px;
	margin-top: 0px;
	
}
body {
	font: 62.5% "Trebuchet MS", sans-serif;
	color: #333333;
	border-radius: 5px;
	font-size: 10px;
	font-family: Arial, Trebuchet MS, Tahoma, Verdana, sans-serif; 
	line-height: normal;
}
:focus {
	outline: 0;
}


img,a {
	border: none;
	color: #996633;
	font-family: Arial, verdana, sans-serif;
	font-size: 11px;
	line-height: normal;	
}


input{
	padding:0px;
	-webkit-border-top-left-radius: 5px;
	-webkit-border-top-right-radius: 5px;
	-moz-border-radius: 5px 5px 5px 5px;
	border-top-left-radius: 5px;
	border-top-right-radius: 5px;
}

select{
	background-color:#fff; 
	padding:0px;
	-webkit-border-top-left-radius: 5px;
	-webkit-border-top-right-radius: 5px;
	-moz-border-radius: 5px 5px 5px 5px;
	border-top-left-radius: 5px;
	border-top-right-radius: 5px;
	
}


p,table,tr, td {
	color: #333333;
	border-radius: 5px;
	font-size: 10px;
	font-family: Arial, Trebuchet MS, Tahoma, Verdana, sans-serif; 
	line-height: normal;
	font-weight: bold;
	border: 1px;
	border-bottom: none;
	padding: 0px;
	
}

h1 {
	font-size: 20px;
	line-height: normal;
	
	font-weight: bold;
	color: #FFFFFF;
	border-bottom: none;
	padding: 0px;
}

h2 {
	
	color: #996633;
	letter-spacing: .1em;
	font-size: 1.5em;
	font-weight: bold;
	line-height: 20px;
	border-bottom:1px solid  #ccc;
	text-shadow: 1px 1px 2px #999;
	text-transform: uppercase;

}
h3 {
	color: #999;
	letter-spacing: .2em;
	text-transform: uppercase;
	font-size: .9em;
	font-weight: bold;
	line-height: .8px;	
}

h4 {
	color: #000;
	letter-spacing: .1em;
	text-transform: uppercase;
	font-size: 1em;
	font-weight: bold;
	line-height: .8px;	
}

/*
--------------------------------------------------------
	Reglas para los DIV que controlan el ambiente general
--------------------------------------------------------
*/
#cabecera {
	background-image: url('/system/img/tira.png');
	text-align: left;
	width: 100%;	
	/**background-color:#FFFACD;**/
	border: 1px  solid #d5d5d5;
	border-top: 0px;
	border-botom: 1px;
	border-left: 0px;
	border-right: 0px;
	float: center;
	postion: relative;	
}

#lateral_i{
	background-image: url('/system/img/fondo.png');
	background-color:#FFFACD;
	float:left; 
	height: 1200px;
	width: 160px;
	border: 1px  solid #d5d5d5;
	border-top: 0px;
	border-botom: 0px;
	border-left: 0px;
	font-family: Trebuchet MS, Tahoma, Verdana, sans-serif;
	box-shadow: 2px 2px 5px #999;
   -webkit-box-shadow: 2px 2px 5px #999;
   -moz-box-shadow: 2px 2px 5px #999;
}
#BrBuscar{
	background-color: #e0e0e0; 
	width: 100%; 
	height: 30px; 
	border: 1px solid; 
	border-color: #d5d5d5;
	padding:5px; 
	border-bottom:0px;
	box-shadow: 2px 2px 5px #999;
  -webkit-box-shadow: 2px 2px 5px #999;
  -moz-box-shadow: 2px 2px 5px #999;
}
#medio {
	float:left;
	background-color: transparent;
	vertical-align: middle;
	text-align: left;
	width: 700px;
	padding-top: 5px;
	padding-left: 15px;	
}

#lateral_d{
	float:right;
	height: 100%; 
	background-color: #e0e0e0; 
	width: 150px;
	border: 1px  solid #fbcb09;
	border-top: 0px;
	border-botom: 0px;
	border-right: 0px;	
}





/*
	--------------------------------------------------------
		Login de Usuario
	--------------------------------------------------------
	*/
div#Login {
	position: absolute;
	text-align: center;
	width: 96px;
	height: 94px;
	overflow: hidden;
	left: 904px;
	top: 264px;
}

/*
	--------------------------------------------------------
		Buscar
	--------------------------------------------------------
	*/


div#buscar {
	right: 0px;
	position: absolute;
}


/*
	--------------------------------------------------------
		Cajas de Buscar
	--------------------------------------------------------
	*/



input.field {

	
	font: 11px Currier, Verdana, Arial, sans-serif;
	color: #0a7285;

}

input.submit {
	font: 10px Currier, Verdana, Arial, sans-serif;
	color: #0a7285;
}

/*
	--------------------------------------------------------
		Caja de Login para iniciar sesion
		Define el estilo del Usuario y Clave
	--------------------------------------------------------
	*/
.inputxt {
	color: #1c94c4;
	font-size: 10px;
	font-family: Arial, Trebuchet MS, Tahoma, Verdana, sans-serif; 
	line-height: normal;
	font-weight: bold;
	border-bottom: none;
	padding: 50%;
	

	border: 1px solid #D4D4D4;
	padding-left: 4px;
	padding-right: 4px;
	padding-top: 2.5px;
	padding-bottom: 0px;
	height: 20px;
	
}

.cabUsuario {
	background-image: url('<?php echo $_GET['url'];?>/system/img/imgUsuario.gif');
	background-repeat: no-repeat;
	background-position: center top;
	margin-bottom: 15px;
	width: 135px;
	height: 20px;
	padding: 4px 2px 0px 25px;
	overflow: hidden;
	border: none;
	color: #646464;
	font-size: 11px;
}


.cabClave {
	background-image: url('<?php echo $_GET['url'];?>/system/img/imgClave.gif');
	background-repeat: no-repeat;
	background-position: center top;
	margin-bottom: 15px;
	width: 135px;
	height: 20px;
	padding: 4px 2px 0px 25px;
	overflow: hidden;
	border: none;
	color: #646464;
	font-size: 11px;
}
.mcabUsuario {
	background-image: url('<?php echo $_GET['url'];?>/system/img/m.input.gif');
	background-repeat: no-repeat;
	background-position: center top;
	margin-bottom: 10px;
	width: 170px;
	height: 20px;
	padding: 2px 2px 0px 25px;
	overflow: hidden;
	border: none;
	color: #646464;
	font-size: 11px;
}
.mcabClave {
	background-image: url('<?php echo $_GET['url'];?>/system/img/m.input.gif');
	background-repeat: no-repeat;
	background-position: center top;
	margin-bottom: 10px;
	width: 170px;
	height: 20px;
	padding: 2px 2px 0px 25px;
	overflow: hidden;
	border: none;
	color: #646464;
	font-size: 11px;
}




/*
	--------------------------------------------------------
		        Login
	--------------------------------------------------------
	*/
#login {
color: #333333;
	font-size: 11px;
	font-family: Currier, Verdana, sans-serif;
	float: left;
	width: 96px;
	height: 56px;
	padding: 0;
	margin: 0;
	background-position: 0px 0px;
	background-repeat: no-repeat;
	cursor: pointer
}

#login:hover {
	background-position: 0px -94px;
	background-repeat: no-repeat;
}

/*
	-----------------------------------------------------------
						Formas de los botones
	-----------------------------------------------------------
	*/

#btnImprimir_f {
	background: url('<?php echo $_GET['url'];?>/system/img/pdf.png') 10% 50% no-repeat;
	font-family: Currier, Verdana, Arial, sans-serif;
	font-size: 11px;
	padding-top: 2px;
	padding-bottom: 2px;
	padding-left: 25px;
	padding-right: 5px;
}

#btnCancelar_f {
	background: url('<?php echo $_GET['url'];?>/system/img/cancel.gif') 10% 50% no-repeat;	
	font-family: Currier, Verdana, Arial, sans-serif;
	font-size: 11px;
	padding-top: 2px;
	padding-bottom: 2px;
	padding-left: 25px;
	padding-right: 5px;
	
}

#btnCancelar {
	background: url('<?php echo $_GET['url'];?>/system/img/botones/cancelar24.png') 5% 50% no-repeat;
	font-family: Currier, Verdana, Arial, sans-serif;
	font-size: 11px;
	padding-top: 2px;
	padding-bottom: 2px;
	padding-left: 25px;
	padding-right: 5px;	
}

#btnGuardar {
	background: url('<?php echo $_GET['url'];?>/system/img/botones/aceptar24.png') 5% 50% no-repeat;
	font-family: Currier, Verdana, Arial, sans-serif;
	font-size: 11px;
	padding-top: 2px;
	padding-bottom: 2px;
	padding-left: 25px;
	padding-right: 5px;	
}