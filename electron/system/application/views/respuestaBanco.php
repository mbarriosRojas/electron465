<html>
<head>
    <!-- Esta pantalla vive en /cobranza/, pero el menú comparte enlaces
         relativos (Crear_Txt, Descargas, Configurar, etc.) pensados para
         /cooperativa/. Sin este <base>, esos enlaces resuelven mal
         (ej. /cobranza/Descargas -> 404). -->
    <base href="<?php echo __LOCALWWW__ . '/cooperativa/'; ?>">
    <link href="<?php echo __CSS__ ?>tgrid/TGrid.css" rel="stylesheet" type="text/css" media="screen"/>
    <link href="<?php echo __CSS__ ?>tgrid/TGridPrint.css" rel="stylesheet" type="text/css" media="print"/>
    <?php $this->load->view("incluir/cabezera.php");?>

    <script type="text/javascript" src="<?php echo __JSVIEW__ ?>tgrid/func.js"></script>
    <script type="text/javascript" src="<?php echo __JSVIEW__ ?>tgrid/editar.js"></script>
    <script type="text/javascript" src="<?php echo __JSVIEW__ ?>tgrid/tgrid.js"></script>
    <script type="text/javascript" src="<?php echo __JSVIEW__ ?>tgrid/paginador.js"></script>
    <script type="text/javascript" src="<?php echo __JSVIEW__ ?>tgrid/xls.js"></script>
    <script type="text/javascript" src="<?php echo __JSVIEW__ ?>view/respuestaBanco.js?v=<?php echo time(); ?>"></script>
</head>
<body>
<div id="cabecera"><br>
    <?php $this->load->view("menu/buscar.php");?>
    <?php echo $Menu;?>
</div>

<div id="medio" style="width:900px">
    <div id="msj_alertas"></div>
    <h2>Cargar Respuesta de Banco (Banfan / Bicentenario / Venezuela)</h2><br>
    <h3>Usuario Conectado: <?php echo $this->session->userdata('usuario'); ?> Fecha: <?php echo date("Y/m/d"); ?></h3><br>

    <p style="font-size:12px;color:#666;max-width:820px;">
        Funcionalidad nueva e independiente de "Pendientes Por Procesar" — no afecta el listado ni el botón
        "Generar" existentes. Aquí se sube el archivo que el banco devuelve con el resultado del cobro y se
        aplica a los créditos de los clientes.
    </p>

    <div id="mnu_Reportes">
        <table>
            <tr>
                <td align="center"><img src="<?php echo __IMG__ ?>reportes/ccarga.png" style='width:48px' onClick="MostrarDivRB('filtro_respuesta');"/><br>Cargar Respuesta</td>
                <td style="width: 50px"></td>
                <td align="center"><img src="<?php echo __IMG__ ?>reportes/dpendiente.png" style='width:48px' onClick="MostrarDivRB('frmConsultarRB');"/><br>Archivos Pendientes</td>
                <td style="width: 50px"></td>
                <td align="center"><img src="<?php echo __IMG__ ?>reportes/dpendiente.png" style='width:48px' onClick="MostrarDivRB('frmConsultarVE');"/><br>Archivos Pendientes<br>(Venezuela)</td>
                <td style="width: 50px"></td>
                <td align="center"><img src="<?php echo __IMG__ ?>reportes/lista.png" style='width:48px' onClick="MostrarDivRB('historicoRB');"/><br>Archivos Procesados</td>
                <td style="width: 50px"></td>
                <td align="center"><img src="<?php echo __IMG__ ?>reportes/ncliente.png" style='width:48px' onClick="MostrarDivRB('buscarCedulaRB');"/><br>Buscar Cédula</td>
                <td style="width: 50px"></td>
            </tr>
        </table>
        <div id="filtro_respuesta" class="dialogo" title="Cargar Respuesta de Banco">
            <?php $this->load->view("respuestaBanco/formCargar.php");?>
        </div>
        <div id="frmConsultarRB" class="dialogo" title="Ver Archivos Pendientes">
            <?php $this->load->view("respuestaBanco/formConsultar.php");?>
        </div>
        <div id="frmConsultarVE" class="dialogo" title="Ver Archivos Pendientes (Venezuela)">
            <table>
                <tr>
                    <td>Seleccione Archivo Pendiente:</td>
                    <td align="left" class="formulario">
                        <select name="cmbArchivosVE" id="cmbArchivosVE" style="width:250px;"></select>
                    </td>
                </tr>
            </table>
        </div>
        <div id="historicoRB" title="Histórico de Cargas de Respuesta Bancaria">
            <p style="font-size:12px;color:#666;">
                Solo informativo — lista todas las cargas realizadas (procesadas o no). Clic en el ícono
                "Ver" de una fila para abrir el detalle completo de esa carga en una ventana aparte.
            </p>
            <div id="HistoricoLista"></div>
        </div>
        <div id="historicoDetalleRB" title="Detalle del Archivo">
            <div id="HistoricoDetalleBuscar" style="margin:6px 0 10px;">
                Buscar por nombre o cédula:
                <input type="text" id="txtFiltroDetalleRB" style="width:260px;" autocomplete="off"
                    placeholder="Ej. 12345678 o PEREZ" onkeyup="filtrarDetalleRB();"/>
                <button type="button" onclick="$('#txtFiltroDetalleRB').val('');filtrarDetalleRB();">Limpiar</button>
                <span id="lblFiltroDetalleRB" style="margin-left:10px;color:#666;font-size:12px;"></span>
            </div>
            <div id="HistoricoDetalleAviso"></div>
            <div id="HistoricoDetalle"></div>
            <div id="HistoricoDetalleRechazos" style="margin-top:16px;"></div>
        </div>
        <div id="buscarCedulaRB" title="Buscar Cédula en Todos los Archivos">
            <p>
                Cédula: <input type="text" id="txtBuscarCedulaRB" maxlength="12" style="width:150px;"
                    onkeypress="if(event.keyCode==13){buscarCedulaRB();return false;}"/>
                <button type="button" onclick="buscarCedulaRB();">Buscar</button>
            </p>
            <div id="BusquedaCedula"></div>
        </div>
    </div>

    <div id="Respuesta"></div>
    <div id="RespuestaRechazos" style="margin-top:16px;"></div>

    <div id="asignarContratoRB">
        <?php $this->load->view("respuestaBanco/asignarContrato.php");?>
    </div>
    <div id="asignarContrato">
        <?php $this->load->view("venezuela/asignarContrato.php");?>
    </div>
</div>
</body>
</html>
