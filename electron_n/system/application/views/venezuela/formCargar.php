<table>
    <tr>
        <td style='width:80px;'>Empresa: <font color='#B70000'>(*)</td>
        <td style="width: 100px;" align="left" colspan="5">
            <select name="txtEmpresa"	id="txtEmpresa"style="">
                <OPTION VALUE =0 >COOPERATIVA ELECTRON 465 RL.</option>
                <OPTION VALUE =1 >GRUPO ELECTRON 465 C.A</option>
                <OPTION VALUE =2 >AMBAS</option>
            </select>
        </td>
    </tr>
    <tr>
        <td >Tipo:</td>
        <td align="left" class="formulario">
            <select name="txtFormaContrato"	id="txtFormaContrato" style="width: 140px;" >
                <option value=0>UNICO</option>
                <option value=1>AGUINALDOS - CUOTA ESPECIAL</option>
                <option value=2>VACACIONES - CUOTA ESPECIAL</option>
                <option value=3>EXTRA</option>
                <option value=4>UNICO - EXTRA</option>
                <option value=5>ESPECIAL - EXTRA</option>
                <option value=6>UNICO - PRONTO PAGO</option>
            </select>
        </td>
    </tr>
    <tr>
        <td >Periodicidad:</td>
        <td align="left" class="formulario">
            <select name="txtPeriodicidad"	id="txtPeriodicidad" style="width: 140px;" >
                <option value=4>MENSUAL</option>
                <option value=0>SEMANAL</option>
                <option value=1>CATORCENAL</option>
                <option value=2>QUINCENAL 15 - 30</option>
                <option value=3>QUINCENAL 10 - 25</option>
                <option value=5>TRIMESTRAL</option>
                <option value=6>SEMESTRAL</option>
                <option value=7>ANUAL</option>
                <option value=8>MENSUAL-10</option>
                <option value=9>MENSUAL-25</option>
            </select>
        </td>
    </tr>
    <tr>
        <td style="width:150px">Fecha Enviado<font color='#B70000'>(*)</font> :</td>
        <td align="left"  style="width:210px" colspan=3>
            <input type="text" id="fecha_envio" name="fecha_envio">
        </td>
    </tr>
    <tr>
        <td style="width:150px">Fecha Recibido<font color='#B70000'>(*)</font> :</td>
        <td align="left"  style="width:210px" colspan=3>
            <input type="text" id="fecha_recibido" name="fecha_recibido">
        </td>
    </tr>
    <tr>
        <td >Seleccionar Archivo (*):</td>
        <td>
            <input type="file" name="archivo" id="archivo" style="width: 400px;" />
        </td>
    </tr>
</table>

