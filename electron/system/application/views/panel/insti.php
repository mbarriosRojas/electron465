<table><tr><td><h2> Agregar nueva institucion.</h2></td></tr></table>
<br>
<br>
<table width="600" border="0" cellspacing="3" cellpadding="0"class="formulario" >
    <tr>
        <td align="left" colspan="3">
            <select id="insti" name="insti" style="width: 100%" onchange="consulInsti()">
                <option value="0">SELECCIONE</option>
                <?=$insti?>
            </select>

        </td>
    </tr>
    <tr>
        <td style="width: 140px;" class="formulario">Nombre (*) :</td>
        <td style="width: 120px;" align="left" class="formulario">
            <input name="txtNombreInsti" type="text" class="inputxt" style="width: 370px;" id="txtNombreInsti" />
        </td>
    </tr>
    <tr>
        <td style='width: 140px;' class='formulario'>Direcci&oacute;n:</td>
        <td style='width: 120px;' align='left' class='formulario'>
            <input name='txtDirecInsti' type='text' class='inputxt' style='width: 370px;' id='txtDirecInsti' />
        </td>
    </tr>
    <tr>
        <td style='width: 140px;' class='formulario'>Telefonos:</td>
        <td style='width: 120px;' align='left' class='formulario'>
            <input name='txtTelInsti' type='text' class='inputxt' style='width: 370px;' id='txtTelInsti' />
        </td>
    </tr>
    <tr>
        <td style='width: 140px;' class='formulario'>Pertenece al Ministerio de Educacion:</td>
        <td style='width: 120px;' align='left' class='formulario'>
            <select id="cmbMinisterio" name="cmbMinisterio">
                <option value="0">NO</option>
                <option value="1">SI</option>
            </select>
        </td>
    </tr>
</table>
<table cellpadding='0' cellspacing='0' border='0' style='width:100%'>
    <tr>
        <td>
            <button name='btnCrear' id='btnCrear' onclick="Crear_Insti();">Guardar</button>
        </td>
    </tr>
</table>