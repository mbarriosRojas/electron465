
<html>
<head>
    <link href="<?php echo __CSS__ ?>tgrid/TGrid.css" rel="stylesheet" type="text/css" media="screen"/>
    <link href="<?php echo __CSS__ ?>tgrid/TGridPrint.css" rel="stylesheet" type="text/css" media="print"/>
    <link href="<?php echo __CSS__ ?>lightbox/lightbox.css" rel="stylesheet"  type="text/css">
    <?php $this->load->view("incluir/cabezera.php");?>

    <script type="text/javascript" src="<?php echo __JSVIEW__ ?>tgrid/func.js"></script>
    <script type="text/javascript" src="<?php echo __JSVIEW__ ?>tgrid/editar.js"></script>
    <script type="text/javascript" src="<?php echo __JSVIEW__ ?>tgrid/tgrid.js"></script>
    <script type="text/javascript" src="<?php echo __JSVIEW__ ?>tgrid/paginador.js"></script>
    <script type="text/javascript" src="<?php echo __JSVIEW__ ?>tgrid/xls.js"></script>
    <script type="text/javascript" src="<?php echo __JSVIEW__ ?>view/cargaVenezuela.js"></script>
</head>
<body>
<div id="cabecera"><br>
    <?php $this->load->view("menu/buscar.php");?>
    <?php echo $Menu;?>
</div>

<div id="medio"  style="width:800px">
    <div id="msj_alertas"></div>
    <h2>Cargar Archivo De Cobranza Del Banco Venezuela</h2><br>
    <h3>Usuario Conectado: <?php echo $this->session->userdata('usuario'); ?> Fecha: <?php echo date("Y/m/d"); ?></h3><br>

    <br>
    <div id="mnu_Reportes" title="Detalles del credito">
        <table >
            <tr>
                <?php if($Nivel == 3 || $Nivel == 0 || $Nivel == 9 || $Nivel == 2 || $Nivel == 8){?>
                    <td align="center"><img src="<?php echo __IMG__ ?>reportes/clientes.png" style='width:48px' onClick="MostrarDiv('filtro_txt');"/><br>Cargar TXT</td>
                    <td style="width: 50px"></td>
                    <td align="center"><img src="<?php echo __IMG__ ?>reportes/clientes.png" style='width:48px' onClick="MostrarDiv('frmConsultar');"/><br>Archivos Pendientes</td>
                    <td style="width: 50px"></td>
                <?php }?>
            </tr>

        </table>
        <div id="filtro_txt" class="dialogo" title="Opciones de Archivo">

            <?php $this->load->view("venezuela/formCargar.php");?>
        </div>
        <div id="frmConsultar" class="dialogo" title="Ver Archivos Pendientes">

            <?php $this->load->view("venezuela/formConsultar.php");?>
        </div>

    </div>
    <div id="Respuesta"></div>
    <div id="asignarContrato">
        <?php $this->load->view("venezuela/asignarContrato.php");?>
    </div>
</div>
</body>
</html>
