<table style="background-color: #B6B6B6">
    <tr>
        <td><h2> QUITAR SUSPENCI&Oacute;N AL CLIENTE...</h2>
            <br>
            <table style="width:300px">
                <tr>
                    <td class="formulario">&nbsp;N&uacute;mero de C&eacute;dula (*)</td>
                    <td class="formulario">&nbsp;</td>
                    <td></td>
                </tr>
                <tr>
                    <td class="formulario">
                        <input type="text" class="inputxt" style="width:200px" name="txtCedulaA" id="txtCedulaA">
                    </td>
                    <td class="formulario">
                        <button onclick="Respaldo_Alta();">Activar</button>
                    </td>
                    <td><img src='<?php echo __IMG__ ?>cargando_tab.gif'
                             style="margin: 0px 0 0px 0; padding: 0px 0px 0px 0px;" id='img_activar_cliente'></td>

                </tr>
            </table>
    </tr>
</table>
<br/>
<br/>
<table style="background-color: #C8E6C9">
    <tr>
        <td><h2> AUTORIZAR POR DEUDA...</h2>
            <br>
            <table style="width:300px">
                <tr>
                    <td class="formulario">&nbsp;N&uacute;mero de C&eacute;dula (*)</td>
                    <td class="formulario">&nbsp;</td>
                    <td></td>
                </tr>
                <tr>
                    <td class="formulario">
                        <input type="text" class="inputxt" style="width:200px" name="txtCedulaAU" id="txtCedulaAU">
                    </td>
                    <td class="formulario">
                        <button onclick="Respaldo_Autorizacion();">Autorizar</button>
                    </td>
                    <td><img src='<?php echo __IMG__ ?>cargando_tab.gif'
                             style="margin: 0px 0 0px 0; padding: 0px 0px 0px 0px;" id='img_activar_clienteAU'></td>

                </tr>
            </table>
    </tr>
</table>
<br/>
<br/>
<table style="background-color: #B6B6B6">
    <tr>
        <td><h2> EXONERACION DE CHEQUE POR CLIENTE...</h2>
            <br>
            <table style="width:300px">
                <tr>
                    <td class="formulario">&nbsp;N&uacute;mero de C&eacute;dula (*)</td>
                    <td class="formulario">&nbsp;N&uacute;mero de Factura (*)</td>
                    <td class="formulario">&nbsp;</td>
                    <td></td>
                </tr>
                <tr>
                    <td class="formulario">
                        <input type="text" class="inputxt" style="width:200px" name="txtCedulaEXO" id="txtCedulaEXO">
                    </td>
                    <td class="formulario">
                        <input type="text" class="inputxt" style="width:200px" name="txtFacturaEXO" id="txtFacturaEXO">
                    </td>
                    <td class="formulario">
                        <button onclick="Exoneracion_Cheque();">Exonerar</button>
                    </td>
                    <td><img src='<?php echo __IMG__ ?>cargando_tab.gif'
                             style="margin: 0px 0 0px 0; padding: 0px 0px 0px 0px;" id='img_Exoneracion_Cheque'></td>

                </tr>
            </table>
    </tr>
</table>
<br/>
<br/>
<?php
if ($Nivel == 0 || $Nivel == 9 || $this->session->userdata('usuario') == 'Carlos') {
    ?>
    <table style="background-color: #C8E6C9">
        <tr>
            <td><h2>MODIFICAR N&Uacute;MERO DE C&Eacute;DULA </h2><BR>Recuerde que debe estar seguro que la cedula por
                el cual va a cambiar no esta asignado a otro cliente.<BR>
                <br>
                <br>
                <table style="width:500px">
                    <tr>
                        <td class="formulario">&nbsp;N&uacute;mero de Cedula (*)</td>
                        <td class="formulario">&nbsp;Modificaci&oacute;n de Cedula</td>
                        <td class="formulario">&nbsp;</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="formulario">
                            <input type="text" class="inputxt" style="width:200px" name="txtCedula_A" id="txtCedula_A">
                        </td>
                        <td class="formulario">
                            <input type="text" class="inputxt" style="width:200px" name="txtCedula_N" id="txtCedula_N">
                        </td>
                        <td class="formulario">
                            <button onclick="Respaldo_Modificar_Cedula();">Modificar</button>
                        </td>
                        <td>
                            <img src='<?php echo __IMG__?>cargando_tab.gif'
                                 style="margin: 0px 0 0px 0; padding: 0px 0px 0px 0px; " id="img_modificar_cedula">
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <br/>
    <br/>
    <br/>
    <table style="background-color: #B6B6B6">
        <tr>
            <td><h2>ELIMINAR CLIENTE...</h2>Debe estar seguro que el n&uacute;mero de c&eacute;dula no esté asignado a
                ningun contrato ya que seran eliminados del sistema.<br>
                <br>
                <table>
                    <tr>
                        <td class="formulario">&nbsp;N&uacute;mero de C&eacute;dula (*)</td>
                    </tr>
                    <tr>
                        <td class="formulario">
                            <input type="text" class="inputxt" style="width:200px" name="txtCedula" id="txtCedula">
                        </td>
                        <td class="formulario">
                            <button onclick="Respaldo_Eliminar_Cedula();">Eliminar</button>
                        </td>
                        <td><img id='img_eliminar_cedula' src='<?php echo __IMG__?>cargando_tab.gif'
                                 style="margin: 0px 0 0px 0; padding: 0px 0px 0px 0px;"></td>
                    </tr>
                </table>
                <div id="divEliminarCedula"></div>
            </td>
        </tr>
    </table>
<?php
}
?>