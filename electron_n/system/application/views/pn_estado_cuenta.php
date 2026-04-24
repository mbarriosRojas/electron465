<html>
	<head>
		<script type="text/javascript" src="<?php echo __JSVIEW__ ?>jquery/jquery-1.7.2.min.js"></script>
		<script type="text/javascript" src="<?php echo __JSVIEW__ ?>jquery/jquery-ui-1.8.20.custom.min.js"></script>
		<script type="text/javascript" src="<?php echo __JSVIEW__ ?>general/Global.js"></script>
		<script type="text/javascript" src="<?php echo __JSVIEW__ ?>tgrid/func.js"></script>
		<script type="text/javascript" src="<?php echo __JSVIEW__ ?>tgrid/tgrid.js"></script>
		<script type="text/javascript" src="<?php echo __JSVIEW__ ?>tgrid/paginador.js"></script>
		<script type="text/javascript" src="<?php echo __JSVIEW__ ?>view/pn_estado_cuenta.js"></script>
		<script type="text/javascript" src="<?php echo __JSVIEW__ ?>tgrid/func.js"></script>
		<script type="text/javascript" src="<?php echo __JSVIEW__ ?>tgrid/tgrid.js"></script>
		<script type="text/javascript" src="<?php echo __JSVIEW__ ?>tgrid/paginador.js"></script>
		</script>
	</head>
	<body id='cuerpo'>
		
		<input type="hidden" value="<?php echo $tipo?>" id='tipo' name='tipo'  />
		<input type="hidden" value="<?php echo $id?>" id='id'  name='id'/>
		<input type="hidden" value="<?php echo $ced?>" id='ced' name='ced'  />
		<input type="hidden" value="" id='corre_est' name='corre_est'  />
		<br>

		<div id='medio'>
				<style>
table {
	width:90%;
	background-color: #C8DFFB;
	padding-bottom: 0px;
	}
	
caption {
	color: #9ba9b4;
	font-size:.94em;
		letter-spacing:.1em;
		margin:1em 0 0 0;
		padding:0;
		caption-side:top;
		text-align:center;
	}	

td {
	color:#678197;
	text-align:center;
	background-color: #fff;
	border-bottom:1px solid #e5eff8;
	}
h1{
	font-size: 10px;
}			
th {
	text-align:center;
	color: #678197;
	text-align:left;
	}
	

.titulo table {
	width:90%;
	background-color: #71A5E4;
	padding-bottom: 0px;
	}
.titulo caption {
	color: #9ba9b4;
	font-size:.94em;
		letter-spacing:.1em;
		margin:1em 0 0 0;
		padding:0;
		caption-side:top;
		text-align:center;
	}	

.titulo td {
	color:#678197;
	text-align:center;
	background-color: #fff;
	border-bottom:1px solid #e5eff8;
	}
.titulo h1{
	font-size: 10px;
}			
.titulo th {
	text-align:center;
	color: #294B67;
	text-align:left;
	}							
		</style>
		<br>
		<br>
				<Caption><h2><center>ESTADO DE CUENTA<br>
				Al <?php echo date("d-m-Y");?></center></h2></Caption>
				<br>
				<div id="Datos_Basicos" style="width:80%;"></div>
				<div id='estado_cuenta' style="width:80%;"></div>
		</div>
		<br>
		<center><button onclick='pdf();'>Enviar A Correo</button></center>
		<br>
		<div id='descarga'></div>
	</body>
</html>
