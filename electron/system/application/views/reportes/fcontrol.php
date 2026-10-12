<table cellpadding="3" cellspacing="3" border=0>
    <?php if($Nivel != 5 ){ ?>
        <tr>
            <td style="width: 140px;">Ubicación: </td>
            <td colspan="2" ><select name="txtDependencia_fcontrol"	id="txtDependencia_fcontrol" style="width: 400px;">

                </select></td>
        </tr>
    <?php } ?>
    <?php if($this -> session -> userdata('usuario') == 'AlvaroZ'){ ?>
        <tr>
            <td style="width: 140px;">Ubicación: </td>
            <td colspan="2" ><select name="txtDependencia_fcontrol"	id="txtDependencia_fcontrol" style="width: 400px;">

                </select></td>
        </tr>
    <?php } ?>
    <tr>
        <td><label for="from">Desde: </label></td><td colspan="3">
            <div class="demo">
                <input type="text" id="desde_fcontrol" name="desde_fcontrol" />
                &nbsp;&nbsp;<label for="to">Hasta: </label>&nbsp;&nbsp;
                <input type="text" id="hasta_fcontrol" name="hasta_fcontrol" />
            </div></td>
    </tr>
</table>