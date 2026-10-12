<html>
<head>
    <link href="<?php echo __CSS__ ?>tgrid/TGrid.css" rel="stylesheet" type="text/css" media="screen"/>
    <link href="<?php echo __CSS__ ?>tgrid/TGridPrint.css" rel="stylesheet" type="text/css" media="print"/>

    <?php $this -> load -> view("incluir/cabezera.php"); ?>

    <script type="text/javascript" src="<?php echo __JSVIEW__ ?>tgrid/func.js"></script>
    <script type="text/javascript" src="<?php echo __JSVIEW__ ?>tgrid/tgrid.js"></script>
    <script type="text/javascript" src="<?php echo __JSVIEW__ ?>tgrid/paginador.js"></script>
    <script type="text/javascript" src="<?php echo __JSVIEW__ ?>tgrid/xls.js"></script>
    <script type="text/javascript" src="<?php echo __JSVIEW__ ?>gvistas/gvista2.js"></script>
    <script type="text/javascript" src="<?php echo __JSVIEW__ ?>view/estatusDocumento.js"></script>

</head>
<body>
<div id="cabecera"><br>
    <?php $this -> load -> view("menu/buscar.php"); ?>
    <?php echo $Menu; ?>
</div>
<div id="medio" >
    <div id="msj_alertas"></div>
    <h2 class="demoHeaders">Recepcion de Documentos</h2><br>
    <h3>Usuario Conectado: <?php echo $this -> session -> userdata('usuario'); ?> Fecha: <?php echo date("Y/m/d"); ?></h3><br><br>
    <br><br>
    <table>
        <tr>
            <td style="width: 140px;">Estatus:</td>
            <td>
                <select name="estatus" id="estatus"  style="width: 400px;" >
                    <option value=9>TODOS</option>
                    <option value=0>Recibido</option>
                    <option value=1>Entregado a Analista</option>
                    <option value=2>Procesado/Entregados</option>
                    <option value=3>Rechazado</option>
                    <option value=4>Anulados</option>
                    <option value=5>Publicidad</option>
                </select></td>
        </tr>
        <tr>
            <td style="width: 140px;">Tipo Documentos:</td>
            <td>
                <select name="tipo" id="tipo"  style="width: 400px;" >
                    <option value=9>TODOS</option>
                    <option value=2>SOLICITUD</option>
                    <option value=1>LIQUIDADOS</option>
                </select></td>
        </tr>
        <tr>
            <td><label for="from">Desde: </label></td><td colspan="3">
                <div class="demo">
                    <input type="text" id="desde" name="desde" />
                    &nbsp;&nbsp;<label for="to">Hasta: </label>&nbsp;&nbsp;
                    <input type="text" id="hasta" name="hasta" />
                </div></td>
        </tr>
        <tr>
            <td colspan="4">
                <div class="demo">
                    <button onclick="listar();">Buscar</button>
                </div>
            </td>
        </tr>

    </table>
    <div id="lista" style="width: 100%">
    </div>

</div>
</body>
</html>