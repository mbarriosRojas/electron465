<table>
    <tr>
        <td style='width:120px;'>Banco: <font color='#B70000'>(*)</font></td>
        <td align="left" colspan="5">
            <select name="txtRBBanco" id="txtRBBanco" style="width:250px;" onchange="cambiarBancoRB();">
                <option value="">Seleccione</option>
                <option value="BANFAN">Banfan</option>
                <option value="BICENTENARIO">Bicentenario</option>
                <option value="VENEZUELA">Venezuela</option>
            </select>
        </td>
    </tr>
    <tr id="filaTipoCobranzaRB">
        <td>Tipo de Cobranza: <font color='#B70000'>(*)</font></td>
        <td align="left" colspan="5">
            <select name="txtRBTipoCobranza" id="txtRBTipoCobranza" style="width:250px;">
                <option value="REGULAR">Regular</option>
                <option value="MOROSO">Moroso</option>
            </select>
            <div style="font-size:11px;color:#555;margin-top:4px;">
                <b>Regular:</b> solo abona la cuota del mes de cobro actual (no busca atrasos).<br>
                <b>Moroso:</b> busca primero cuotas vencidas (meses anteriores al actual, sin pagar) antes de
                abonar la del mes actual.<br>
                En Banfan/Bicentenario, Moroso además fuerza la FECHA CUOTA al día 01 del mes (no aplica a
                Venezuela, que ya usa la fecha real de cobro de cada cliente reportada en su archivo).
            </div>
        </td>
    </tr>
    <tr id="filaArchivoRB">
        <td>Seleccionar Archivo (*):</td>
        <td>
            <input type="file" name="archivoRB" id="archivoRB" style="width: 400px;" accept=".txt" />
            <div style="font-size:11px;color:#555;margin-top:4px;">
                Formato: "Listado de Débito de Nómina" (Banfan/Bicentenario), archivo .txt tal como lo envía el banco.
            </div>
        </td>
    </tr>

    <!-- Bloque específico Venezuela: mismo dialogo "Cargar Respuesta". Sin
         Empresa/Tipo/Fecha Enviado/Fecha Recibido — ninguno se usaba en el
         procesamiento (quedaban guardados sin leerse nunca). La fecha real
         de cobro y el monto se leen del propio XLSX por cliente: cada fila
         resuelve su tasa (BCV o Cobranza, según se haya cobrado o no el
         100% de lo enviado) con SU PROPIA fecha de cobro, no una fecha
         elegida en este formulario.

         Periodicidad SÍ se reincorpora (mismas opciones que Crear_Txt) —
         aquí sí tiene efecto real: filtra qué contratos de una cédula son
         elegibles al buscar/asignar (un cliente puede tener contratos con
         distinta periodicidad; este archivo solo corresponde a la
         periodicidad seleccionada, ej. "MENSUAL (día 15)" para un archivo
         de cobro del día 15). -->
    <tr id="filaVenezuelaPeriodicidadRB" style="display:none;">
        <td>Periodicidad: <font color='#B70000'>(*)</font></td>
        <td align="left" colspan="5">
            <select name="txtVePeriodicidad" id="txtVePeriodicidad" style="width:250px;">
                <option value="4">MENSUAL</option>
                <option value="5">MENSUAL (día 15)</option>
                <option value="9">MENSUAL (día 25)</option>
                <option value="6">MENSUAL (día 30)</option>
                <option value="0">SEMANAL</option>
                <option value="1">CATORCENAL</option>
                <option value="2">QUINCENAL 15 - 30</option>
                <option value="3">QUINCENAL 10 - 25</option>
                <option value="7">ANUAL</option>
                <option value="8">MENSUAL-10</option>
            </select>
            <div style="font-size:11px;color:#555;margin-top:4px;">
                Filtra los contratos elegibles al buscar/asignar por cédula: si un cliente tiene contratos con
                otra periodicidad, no se consideran para este archivo.
            </div>
        </td>
    </tr>
    <tr id="filaVenezuelaArchivoRB" style="display:none;">
        <td>Seleccionar Archivo (*):</td>
        <td>
            <input type="file" name="archivoVeRB" id="archivoVeRB" style="width: 400px;" accept=".xlsx" />
            <div style="font-size:11px;color:#555;margin-top:4px;">
                Solo <b>XLSX</b> (pestaña de detalle "Créditos Domiciliación"). Cada fila convierte
                Bs.&harr;USD con la tasa de <b>su propia fecha de cobro</b> (columna fechaCobro), y usa
                Dólar Cobranza si montoCobrado = montoTotal (se cobró completo) o Dólar BCV si cobró menos.
            </div>
        </td>
    </tr>
</table>
