<?php ?>
<html>
	<head>
		<TITLE></TITLE>
		<style>
			body {
				margin: auto;
			}
			table {
				cellspacing: 0;
				margin: auto;
				width: 800px;
				border: 0px solid black;
				border-spacing: 0px;
			}

			table tr {

				font-family: verdana, monospace;
				color: black;
				font-size: 12px;
			}

			.bordes {
				font-weight: bold;
				background-color: #AAAAAA;
				text-align: center;
				border-top: 1px solid #313739;
				border-bottom: 1px solid #313739;
				border-left: 1px solid #313739;
				border-right: 1px solid #313739
			}

			.bordeabajo {
				border-bottom: 1px solid #313739;
			}

			.bor {

				border-bottom: 1px solid #313739;
				border-left: 1px solid #313739;
				border-right: 1px solid #313739
			}

			.bor2 {
				text-align: center;
				border-top: 1px solid #313739;
				border-left: 1px solid #313739;
				border-right: 1px solid #313739
			}

			.bor3 {
				text-align: center;
				border-top: 1px solid #313739;
			}

			.n {
				font-weight: bold;
			}

			.b {
				font-weight: bold;
				border-top: 1px solid #313739;
				border-bottom: 1px solid #313739;
				border-left: 1px solid #313739;
				border-right: 1px solid #313739
			}

			}

		</style>
	</head>
	<body>
		<table>

			<TR>
				<TD class="bor2" rowspan="2" colspan="1" width="200px"><img src="<?php echo __IMG__; ?>logocoopera.jpg" width=196 height=102 hspace=9 vspace=6></TD><TD class="bor3" rowspan="2" width="400px" colspan="2" align="center"><font  size="+2 ">NOTA DE ENTREGA</font></TD><TD class="bor2" width="20px" colspan="2"><?php echo $codigo; ?></TD>
			</TR>
			<TR>
				<TD class="bor2" colspan="2"><?php echo $fecha; ?></TD>
			</TR>
			<TR>
				<TD colspan="6" width="100%" align="center" class="bordes">DATOS DE QUIEN RECIBE</TD>
			</TR>
			<tr>
				<TD width="20%" class="bor"><font class="n">NOMINA</font></TD><TD class="bor"><?php echo $nomina; ?></TD><TD class="bordeabajo" width="20%"><font class="n">BANCO</font></TD><TD class="bor" width="20%" colspan="2"><?php echo $banco; ?></TD>
			</tr>
			<tr>
				<TD colspan="7">&nbsp;</TD>
			</tr>
			<TR>
				<td colspan="6">
				<p align="justify">

					YO, <font class="n"><?php echo $a_quien; ?></font>, TITULAR DE LA CEDULA DE IDENTIDAD <font class="n"><?php echo $cedula; ?></font>, DECLARO QUE HE RECIBIDO Y VERIFICADO, A MI ENTERA SATISFACCION DE LA EMPRESA COOPERATIVA ELECTRON 465, RIF J &#045; 31207419 &#045; 1 EL ARTICULO QUE SE DESCRIBE A CONTINUACION:
				</p>
				<p>
					&nbsp;
				</p></TD>
			</TR>
			<TR>
				<TD colspan="6" class="bordes" align="center">CARACTERISTICAS</TD>
			</TR>
			<TR>
				<TD colspan="6">&nbsp;</TD>
			</TR>
			<TR>
				<TD colspan="6"></TD>
			</TR>
			<TR>
				<TD>MODELO:</TD><TD colspan="4"><?php echo $modelo; ?></TD>
			</TR>
			<TR>
				<TD colspan="6"></TD>
			</TR>
			<?php $i=0;foreach ($serial as $ser) {?>
				<TR>
					<TD>DESCRIPCI&Oacute;N:</TD><TD><?php echo $ser; ?></TD><TD colspan="2"><?php echo $descrip[$i]; ?></TD>
				</TR>	
			<?php $i++;} ?>
			
			
			<TR>
				<TD colspan="6">&nbsp;</TD>
			</TR>
			<TR>
				<TD colspan="6" class="bordes">DESCRIPCION DE PARTES</TD>
			</TR>
			<TR>
				<TD colspan="6" class="bor">&nbsp;</TD>
			</TR>
			<TR>
				<TD colspan="6" class="bor">&nbsp;</TD>
			</TR>
			<TR>
				<TD colspan="6" class="bor">&nbsp;</TD>
			</TR>
			<TR>
				<TD colspan="6" class="bor">&nbsp;</TD>
			</TR>
			<TR>
				<TD colspan="6" class="bor">&nbsp;</TD>
			</TR>
			<TR>
				<TD colspan="6" class="bor">&nbsp;</TD>
			</TR>
			<TR>
				<TD colspan="6">&nbsp;</TD>
			</TR>
			<?php if($motivo == 7){?>
			<TR>
				<TD class="q">BATERIA:</TD><TD>&nbsp;____</TD><TD class="q">TAPAS LATERALES:</TD><TD>&nbsp;____</TD><TD></TD>
			</TR>
			<TR>
				<TD colspan="6"></TD>
			</TR>
			<TR>
				<TD class="q">CORNETA:</TD><TD>&nbsp;____</TD><TD class="q">CABALLETE CENTRAL:</TD><TD>&nbsp;____</TD><TD></TD>
			</TR>
			<TR>
				<TD colspan="6"></TD>
			</TR>
			<TR>
				<TD class="q">ENCENDIDO:</TD><TD>&nbsp;____</TD><TD class="q">PATA LATERAL:</TD><TD>&nbsp;____</TD><TD></TD>
			</TR>
			<TR>
				<TD colspan="6"></TD>
			</TR>
			<TR>
				<TD class="q">LUCES DE CRUCE:</TD><TD>&nbsp;____</TD><TD class="q">RETROVISORES:</TD><TD>&nbsp;____</TD><TD></TD>
			</TR>
			<?php }?>
			<TR>
				<TD colspan="6">&nbsp;</TD>
			</TR>
			<TR>
				<TD colspan="6" class="bordes">DATOS DE QUIEN REALIZA LA ENTREGA</TD>
			</TR>
			<TR>
				<TD colspan="3" class="bor"><?php echo "USUARIO CONECTADO:".$usuario; ?></TD><TD colspan="3" class="bor"><?php echo "UBICACION:".$ciudad; ?></TD>
			</TR>
			<TR>
				<TD colspan="6" class="bor">&nbsp;</TD>
			</TR>
			<TR>
				<TD colspan="6" class="bor">&nbsp;</TD>
			</TR>
			<TR>
				<TD colspan="6" class="bor">&nbsp;</TD>
			</TR>
			<TR>
				<TD colspan="6" class="bor">&nbsp;</TD>
			</TR>
			<TR>
				<TD colspan="6" class="bor">&nbsp;</TD>
			</TR>
			<TR>
				<TD colspan="6">&nbsp;</TD>
			</TR>
			<TR>
				<TD colspan="6" class="bordes">OBSERVACIONES</TD>
			</TR>
			<TR>
				<TD colspan="6" class="bor">&nbsp;</TD>
			</TR>
			<TR>
				<TD colspan="6" class="bor">&nbsp;</TD>
			</TR>
			<TR>
				<TD colspan="6" class="bor">&nbsp;</TD>
			</TR>
			<TR>
				<TD colspan="6" class="bor">&nbsp;</TD>
			</TR>
			<TR>
				<TD colspan="6" class="bor">&nbsp;</TD>
			</TR>
			<TR>
				<TD colspan="6" class="bor">&nbsp;</TD>
			</TR>
			<TR>
				<TD height="80"></TD><TD>
			</TR>
			<TR>
				<TD class="bordeabajo">&nbsp;</TD><TD></TD><TD></TD><TD class="bordeabajo">&nbsp;</TD><TD></TD>
			</TR>
			<TR>
				<TD align="center">Firma del responsable que entrega</TD><TD></TD><TD></TD><TD align="center">Firma de quien recibe</TD><TD></TD>
			</TR>
			<TR>
				<TD></TD><TD></TD><TD></TD><TD></TD><TD></TD>
			</TR>
		</table>
	</body>
</html>