<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Ver Expediente Digital</title>
        <meta charset="UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"> 
        <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
        <meta name="keywords" content="menu, navigation, animation, transition, transform, rotate, css3, web design, component, icon, slide" />
        <link rel="stylesheet" type="text/css" href="<?php echo __CSS__ ?>subir_archivo/demo.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo __CSS__ ?>subir_archivo/style8.css" />
        
        <link href="<?php echo __CSS__ ?>lightbox/lightbox.css" rel="stylesheet"  type="text/css">
        <link rel="stylesheet" href="<?php echo __CSS__ ?>lightbox/screen1.css" type="text/css" media="screen" />
        

    </head>
    <body>
    	<input type="hidden" id='nivel' value='<?php echo $this -> session -> userdata('nivel');?>'></input>
    	<input type="hidden" id='usu' value='<?php echo $this -> session -> userdata('usuario');?>'></input>
        <div class="container">
        	<center><br><br>
      		<div id='frm_buscar' class="formulario">
      			<form onsubmit="return consultar_datos();">
				<label>INGRESE C&Eacute;DULA&nbsp;</label>
				<input type="text" id = 'cedula' name='cedula' />
				<br />
				<input type="submit" value="Buscar" class=""></input>
				</form>
			</div>
			<div id='datos_cliente' >EPALE</div>
			</center>
			<div id='id_contenido'>
				<div class="imageRow">
  					<div class="single">
  					
                <ul class="ca-menu">
                    <li id='li_0'>
                        <a href="#" onclick="Ver_Imagenes(0);">
                            <span class="ca-icon">&#85</span>
                            <div class="ca-content">
                                <h2 class="ca-main"></h2>
                                <h3 class="ca-sub">CEDULA</h3>
                            </div>
                        </a>
                    </li>
                    <li id='li_2'>
                        <a href="#" onclick="Ver_Imagenes(2);">
                            <span class="ca-icon">&#118</span>
                            <div class="ca-content">
                                <h2 class="ca-main"></h2>
                                <h3 class="ca-sub">BANCOS Y CHEQUES</h3>
                            </div>
                        </a>
                    </li>
                    <li id='li_1'>
                        <a href="#" onclick="Ver_Imagenes(1);">
                            <span class="ca-icon">&#97</span>
                            <div class="ca-content">
                                <h2 class="ca-main"></h2>
                                <h3 class="ca-sub">FACTURAS</h3>
                            </div>
                        </a>
                    </li>
                    <li id='li_3'>
                        <a href="#" onclick="Ver_Imagenes(3);">
                            <span class="ca-icon">&#64</span>
                            <div class="ca-content">
                                <h2 class="ca-main"></h2>
                                <h3 class="ca-sub">CARTAS</h3>
                            </div>
                        </a>
                    </li>
                    <li id='li_6'>
                        <a href="#" onclick="Ver_Imagenes(6);" disabled="disabled">
                            <span class="ca-icon">&#98</span>
                            <div class="ca-content">
                                <h2 class="ca-main"></h2>
                                <h3 class="ca-sub">CHEQUE GARANTIA</h3>
                            </div>
                        </a>
                    </li>
                    <li id='li_4'>
                        <a href="#" onclick="Ver_Imagenes(4);">
                            <span class="ca-icon">&#102</span>
                            <div class="ca-content">
                                <h2 class="ca-main"></h2>
                                <h3 class="ca-sub">FIADOR</h3>
                            </div>
                        </a>
                    </li>
                    <li id='li_5'>
                        <a href="#" onclick="Ver_Imagenes(5);">
                            <span class="ca-icon">&#68</span>
                            <div class="ca-content">
                                <h2 class="ca-main"></h2>
                                <h3 class="ca-sub">RESPALDOS RIF</h3>
                            </div>
                        </a>
                    </li>
                </ul>
                </div>
               </div>
            </div><!-- content -->
            <div id='imagenes'></div>
        </div>
        <link href="<?php echo __CSS__ ?>humanity/jquery-ui-1.8.20.custom.css" rel="stylesheet"  type="text/css">
        <link href="<?php echo __CSS__ ?>lightbox/lightbox.css" rel="stylesheet"  type="text/css">
        <script type="text/javascript" src="<?php echo __JSVIEW__ ?>jquery/jquery-1.7.2.min.js"></script>
		<script type="text/javascript" src="<?php echo __JSVIEW__ ?>jquery/jquery-ui-1.8.20.custom.min.js"></script>	
		<script type="text/javascript" src="<?php echo __JSVIEW__ ?>lightbox/lightbox.js"></script>
		<script type="text/javascript" src="<?php echo __JSVIEW__ ?>general/Global.js"></script>
		<script type="text/javascript" src="<?php echo __JSVIEW__ ?>view/subir_archivos2.js"></script>
    </body>
</html>