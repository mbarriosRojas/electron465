<head>
	<?php $this -> load -> view("incluir/cabezera.php"); ?>
	<script type="text/javascript" src="<?php echo __JSVIEW__ ?>view/configurar.js"></script>
</head>
<body class="yui-skin-sam" style="background-color: #FFFACD;">
	<div id="cabecera">
		<br>
		<?php $this -> load -> view("menu/buscar.php"); ?>
		<?php echo $Menu; ?>
	</div>
	<div id="lateral_i">
		<center>
			<br>
			<?php $this -> load -> view("menu/lateral.php"); ?>
	</div>
	<div id="medio" >
		<div id="msj_alertas"></div>
		<table style="width: 800px" border=0>
			<tr>
				<td style="width: 20px"></td>
				<td>
				<table border=0  >
					<tr >
						<td style="width :700px" align="left">
						<br>
						<br>
						<h2>Generar Archivos de Cobranzas (Nominas, Bancos, Otros...)</h2>
						<br>
						<br>
						<a href="<?php echo base_url() . "system/repository/UNOM000000201020111.txt"; ?>"> > Generar Archivos Del Banco Fondo Com&uacute;n ( .TXT )</a>
						<br>
						<a href="<?php echo base_url() . "system/repository/ANOM000000201020111.txt"; ?>"> > Generar Archivos Del Banco Fondo Com&uacute;n Aguinaldos ( .TXT )</a></td>
					</tr>
				</table></td>
			</tr>
		</table>
	</div>
</body>
</html>

