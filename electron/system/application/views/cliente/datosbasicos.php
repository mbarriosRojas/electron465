<?php
/**
 * Desarrollado por Yasmin Vicuna de Pena
 */
?>

<table style="width:630px" border="0" cellspacing="3" cellpadding="0">
    <tr>
        <td>C&eacute;dula (*):</td>
        <td colspan="2" valign="bottom">
            <select name="txtNacionalidad" id="txtNacionalidad" style="width: 50px;">
                <option>V-</option>
                <option>E-</option>
                <option>G-</option>
                <option>J-</option>
            </select>
            <input name="txtCedula" type="text" style="width: 230px;" id="txtCedula" maxlength="10"
                   onblur="consultar_clientes();">
        </td>
        <td rowspan="4" style="height: 100px;width: 110px;" align="middle">
            <a href='#' Onclick="N_Ventana2()"><img id='foto' name='foto' src="<?php echo __IMG__ ?>sinfoto.png"
                                                    style="height: 100px;width: 110px;"/></a>
        </td>
    </tr>
    <tr>
        <td>Estado Civil:</td>
        <td colspan="2" valign="bottom">

            <select name="txtEdocivil" id="txtEdocivil" style="width: 280px;">
                <option value=1>SOLTERO (A)</option>
                <option value=2>CASADO (A)</option>
                <option value=3>DIVORCIADO (A)</option>
                <option value=4>VIUDO (A)</option>
            </select></td>
    </tr>

    <tr>
        <td width="50px"> Nombres:</td>
        <td colspan="2">
            <input name="txtNombre1" type="text" id="txtNombre1" style="width: 140px;">
            <input name="txtNombre2" type="text" id="txtNombre2" style="width: 140px;">
        </td>
    </tr>
    <tr>
        <td>Apellidos:</td>
        <td colspan="2">
            <input name="txtApellido1" type="text" id="txtApellido1" style="width: 140px;">
            <input name="txtApellido2" type="text" id="txtApellido2" style="width: 140px;">
        </td>
    </tr>
    <tr>
        <td>Fecha nacimiento:</td>
        <td align="left"><select name="txtDiaNacimiento" id="txtDiaNacimiento" style="width: 55px;">
                <option>Dia:</option>
                <?php
                for ($i = 1; $i <= 31; $i++) {
                    ?>
                    <option value='<?php echo $i ?>'><?php echo $i ?></option>
                    <?php
                }
                ?>
            </select>
            <select name="txtMesNacimiento" id="txtMesNacimiento" style="width: 55px;">
                <option>Mes:</option>
                <?php
                for ($i = 1; $i <= 12; $i++) {
                    ?>
                    <option value='<?php echo $i ?>'><?php echo $i ?></option>
                    <?php
                }
                ?>
            </select>
            <select name="txtAnoNacimiento" id="txtAnoNacimiento" style="width: 55px;">
                <option>A&ntilde;o:</option>
                <?php
                for ($i = 1900; $i <= 2014; $i++) {
                    ?>
                    <option value='<?php echo $i ?>'><?php echo $i ?></option>
                    <?php
                }
                ?>
            </select></td>
        <td>Identificaci&oacute;n del Sexo:</td>
        <td align="right"><select name="txtSexo" id="txtSexo" style="width: 150px;">
                <option selected="selected">MASCULINO</option>
                <option>FEMENINO</option>
            </select></td>
    </tr>

    <tr>
        <td>Tel&eacute;fono Movil:</td>
        <td align="left">
            <input name="txtTelefono" id="txtTelefono" type="text" style="width: 180px;">
        </td>
        <td>Tel&eacute;fono Habitacion:</td>
        <td align="right">
            <input name="txtTelefonoh" id="txtTelefonoh" type="text" style="width: 150px;">
        </td>
    </tr>
    <tr>
        <td>Ciudad:</td>
        <td align="left">
            <input name="txtCiudad2" type="text" id="txtCiudad2" style="width: 180px;">
        </td>
        <td>Estado:</td>
        <td align="right">
            <input name="txtUbicacion2" id="txtUbicacion2" type="text" style="width: 150px;">
        </td>
    </tr>
    <tr>
        <td>Municipio:</td>
        <td align="left">
            <input name="txtMunicipio" type="text" id="txtMunicipio" style="width: 180px;">
        </td>
        <td>Edificio:</td>
        <td align="right">
            <input name="txtParroquia" id="txtParroquia" type="text" style="width: 150px;">
        </td>
    </tr>

    <tr>
        <td>Ubicaci&oacute;n Sector :</td>
        <td align="left">
            <input name="txtSector" type="text" id="txtSector" style="width: 180px;">
        </td>
        <td>Avenida.:</td>
        <td align="right">
            <input name="txtAvenida" id="txtAvenida" type="text" style="width: 150px;">
        </td>
    </tr>

    <tr>
        <td>Urbanizaci&oacute;n:</td>
        <td align="left">
            <input name="txtUrbanizacion" type="text" id="txtUrbanizacion" style="width: 180px;">
        </td>
        <td>Calle.:</td>
        <td align="right">
            <input name="txtCalles" id="txtCalles" type="text" style="width: 150px;">
        </td>
    </tr>

    <tr>
        <td>Casa y/o Apto N&uacute;m.:</td>
        <td align="left">
            <input name="txtDireccionH" id="txtDireccionH" type="text" style="width: 180px;">

        </td>

        <td>Zona Postal.:</td>
        <td align="right">
            <input name="txtZonaPostal" id="txtZonaPostal" type="text" style="width: 150px;"
                   ondblclick="muestra_zona_postal()">
            <div id="divZonaPostal" title="SELECCIONE UN CODIGO POSTAL">
                <table>
                    <tr>
                        <td><label>ESTADO</label></td>
                        <td>
                            <select id="cmbEstados" onChange="busca_zona_postal();">
                                <?php echo $estados; ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td><label>ZONA POSTAL</label></td>
                        <td><select id="cmbZonas"></select></td>
                    </tr>
                </table>
            </div>
        </td>
    </tr>
    <tr>
        <td>Instucion al cual pertenece:</td>
        <td align="left" colspan="3">
            <select id="insti" name="insti" style="width: 100%" onchange="buscaDir()">
                <option value="0">SELECCIONE</option>
                <?=$insti?>
            </select>

        </td>
    </tr>
    <tr>
        <td>Direcci&oacute;n Trabajo:</td>
        <td align="left" colspan="3">
            <input name="txtDireccionT" id="txtDireccionT" type="text" style="width: 490px;">

        </td>
    </tr>
    <tr>
        <td>Cargo:</td>
        <td align="left" colspan="6">
            <input name="txtCargo" type="text" style="width: 490px;" id="txtCargo">
        </td>
    </tr>
    <tr>
        <td>Ciudad:</td>
        <td align="left">
            <input name="txtCiudad" type="text" id="txtCiudad" style="width: 180px;">
        </td>
        <td>Estado:</td>
        <td align="right">
            <input name="txtUbicacion" id="txtUbicacion" type="text" style="width: 150px;">
        </td>
    </tr>
    <tr>
        <td>Banco (Nomina)</td>
        <td align="left" style="width: 165px;">
            <select name="txtbanco_1" id="txtbanco_1" style="width: 180px;" onchange="verifica_banco(this);">
                <option>----------</option>
                <option>SOFITASA</option>
                <option>BICENTENARIO</option>
                <option>BOD</option>
                <option>PROVINCIAL</option>
                <option>VENEZUELA</option>
                <option>BANESCO</option>
                <option>INDUSTRIAL</option>
                <option>MERCANTIL</option>
                <option>FONDO COMUN</option>
                <option>DEL SUR</option>
                <option>CARONI</option>
                <option>BANFAN</option>
                <option>BANCARIBE</option>
                <option disabled="true">OCCIDENTAL DE DESCUENTO</option>
                <option disabled="true">100% BANCO COMERCIAL</option>
                <option disabled="true">BANCORO</option>
                <option disabled="true">CAMARA MERCANTIL</option>
                <option>EL EXTERIOR</option>
                <option disabled="true">FEDERAL</option>
                <option disabled="true">CANARIAS</option>
                <option>CARIBE</option>
                <option disabled="true">PLAZA</option>
                <option disabled="true">CENTRAL</option>
                <option disabled="true">NACIONAL DE CREDITO</option>
                <option disabled="true">COMERCIO EXTERIOR</option>
                <option disabled="true">CORPBANCA</option>
            </select>
        </td>

        <td style="width: 110px;">Tipo de Cuenta:</td>
        <td align="right">
            <select
                name="txtTipo_1" id="txtTipo_1" style="width: 150px;">
                <option>----------</option>
                <option>AHORRO</option>
                <option>CORRIENTE</option>
                <option>AHORRO NOMINA</option>
                <option>CORRIENTE NOMINA</option>
            </select></td>
    </tr>
    <tr>
        <td>N&uacute;m. de Cuenta:</td>
        <td colspan="3" align="left">
            <input name="txtcuenta_1" type="text" style="width: 490px;" id="txtcuenta_1" maxlength="20"
                   onblur="Verificar_Cuenta(this.value);">
        </td>
    </tr>

    <tr>
        <td>2.- Banco :</td>
        <td align="left">
            <select
                name="txtbanco_2" id="txtbanco_2" style="width: 180px;" onchange="verifica_banco(this);">
                <option>----------</option>
                <option>SOFITASA</option>
                <option>BICENTENARIO</option>
                <option>BOD</option>
                <option>PROVINCIAL</option>
                <option>VENEZUELA</option>
                <option>BANESCO</option>
                <option>INDUSTRIAL</option>
                <option>MERCANTIL</option>
                <option>FONDO COMUN</option>
                <option>DEL SUR</option>
                <option>CARONI</option>
                <option>BANFAN</option>
                <option>BANCARIBE</option>
                <option disabled="true">OCCIDENTAL DE DESCUENTO</option>
                <option disabled="true">100% BANCO COMERCIAL</option>
                <option disabled="true">BANCORO</option>
                <option disabled="true">CAMARA MERCANTIL</option>
                <option disabled="true">EL EXTERIOR</option>
                <option disabled="true">FEDERAL</option>
                <option disabled="true">CANARIAS</option>
                <option>CARIBE</option>
                <option disabled="true">PLAZA</option>
                <option disabled="true">CENTRAL</option>
                <option disabled="true">NACIONAL DE CREDITO</option>
                <option disabled="true">COMERCIO EXTERIOR</option>
                <option disabled="true">CORPBANCA</option>
            </select>
        </td>
        <td>Tipo de Cuenta:</td>
        <td align="right">
            <select
                name="txtTipo_2" id="txtTipo_2" style="width: 150px;">
                <option>----------</option>
                <option>AHORRO</option>
                <option>CORRIENTE</option>
                <option>AHORRO NOMINA</option>
                <option>CORRIENTE NOMINA</option>
            </select></td>
    </tr>
    <tr>
        <td>N&uacute;m. de Cuenta:</td>
        <td colspan="3">
            <input name="txtcuenta_2" type="text" style="width: 490px;" id="txtcuenta_2" maxlength="20"/>
        </td>
    </tr>
    <tr>
        <td>Otra Instucion al cual pertenece:</td>
        <td align="left" colspan="3">
            <select id="insti2" name="insti2" style="width: 100%" onchange="buscaDir2()">
                <option value="0">SELECCIONE</option>
                <?=$insti?>
            </select>

        </td>
    </tr>
    <tr>
        <td>Direcci&oacute;n Trabajo 2:</td>
        <td align="left" colspan="3">
            <input name="txtDireccionT2" id="txtDireccionT2" type="text" style="width: 490px;">

        </td>
    </tr>
    <tr>
        <td>N&uacute;m. de tarjeta</td>
        <td colspan="3">
            <input name="txtnumero_tarjeta" type="text" style="width: 490px;" id="txtnumero_tarjeta" maxlength="20"/>
        </td>
    </tr>
    <tr>
        <td>Correo:</td>
        <td align="left">
            <input name="txtCorreo" type="text" id="txtCorreo" style="width: 180px;">
        </td>
        <td>PIN (Celular):</td>
        <td align="right">
            <input name="txtPin" id="txtPin" type="text" style="width: 150px;">
        </td>
    </tr>


    <tr>
        <td>F. Ingreso:</td>
        <td align="left">

            <select name="txtDiaI" id="txtDiaI" style="width: 55px;">
                <option value='0'>Dia:</option>
                <?php
                for ($i = 1; $i <= 31; $i++) {
                    ?>
                    <option value='<?php echo $i ?>'><?php echo $i ?></option>
                    <?php
                }
                ?>
            </select>
            <select name="txtMesI" id="txtMesI" style="width: 55px;">
                <option value='0'>Mes:</option>
                <?php
                for ($i = 1; $i <= 12; $i++) {
                    ?>
                    <option value='<?php echo $i ?>'><?php echo $i ?></option>
                    <?php
                }
                ?>
            </select>
            <select name="txtAnoI" id="txtAnoI" style="width: 60px;">
                <option value='0'>A&ntilde;o:</option>
                <?php
                for ($i = 1950; $i <= 2015; $i++) {
                    ?>
                    <option value='<?php echo $i ?>'><?php echo $i ?></option>
                    <?php
                }
                ?>
            </select>


        </td>
        <td>Mes. Vacaciones:</td>
        <td align="right">

            <select name="txtMesA" id="txtMesA" style="width: 150px;">
                <option value=0>----------</option>
                <option value=1>ENERO</option>
                <option value=2>FEBRERO</option>
                <option value=3>MARZO</option>
                <option value=4>ABRIL</option>
                <option value=5>MAYO</option>
                <option value=6>JUNIO</option>
                <option value=7>JULIO</option>
                <option value=8>AGOSTO</option>
                <option value=9>SEPTIEMBRE</option>
                <option value=10>OCTUBRE</option>
                <option value=11>NOVIEMBRE</option>
                <option value=12>DICIEMBRE</option>
            </select>

        </td>
    </tr>

    <tr>
        <td>Monto Vac. 2012:</td>
        <td align="left">
            <input name="txtVacaciones" type="text" id="txtVacaciones" style="width: 180px;">
        </td>
        <td>Monto Agu. 2012:</td>
        <td align="right">
            <input name="txtAguinaldos" id="txtAguinaldos" type="text" style="width: 150px;">
        </td>
    </tr>
    <tr>
        <td>Tipo Personal</td>
        <td align="left">
            <select name="txtPersonal" id="txtPersonal" style="width: 180px;">
                <option value='0'>ACTIVO</option>
                <option value='1'>JUBILADO</option>
                <option value='2'>ACTIVO - INCAPACITADO</option>
            </select>
        </td>
        <td>Afiliado:</td>
        <td align="right">
            <select name="txtAfiliado" id="txtAfiliado" style="width: 180px;">
                <option value='0'>NO (FIRMO)</option>
                <option value='1'>SI (ACEPTO Y FIRMO)</option>
            </select>
        </td>
    </tr>
    <tr>
        <td>Domiciliado por Grupo</td>
        <td align="left">
            <select name="txtDomiciliacionG" id="txtDomiciliacionG" style="width: 180px;">
                <option value='0'>NO</option>
                <option value='1'>SI</option>
                <option value='NULL'>NO</option>
            </select>
        </td>
        <td>Domiciliado por Cooperativa:</td>
        <td align="right">
            <select name="txtDomiciliacionC" id="txtDomiciliacionC" style="width: 180px;">
                <option value='0'>NO</option>
                <option value='1'>SI</option>
                <option value='NULL'>NO</option>
            </select>
        </td>
    </tr>
    <tr>
        <td>Domiciliado por Inter-Bancario</td>
        <td align="left">
            <select name="txtDomiciliacionI" id="txtDomiciliacionI" style="width: 180px;">
                <option value='0'>NO</option>
                <option value='1'>SI</option>
                <option value='NULL'>NO</option>
            </select>
        </td>
        <td>Expediente:</td>
        <td align="right">
            <input name="nexpediente" id="nexpediente" type="text" style="width: 150px;" readonly=true>
        </td>
    </tr>

</table>
<div id='modal' style='display: none;'>
    <div id='tomar_foto' style='display: none;' class='contenedor'>
        <?php $this->load->view('cliente/foto2'); ?>
    </div>
</div>
