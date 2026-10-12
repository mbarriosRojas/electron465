<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Cuentas matriz y códigos de domiciliación por linaje/empresa.
 * Fuente: reportes.php (cuentas corporativas) y t_afiliar_txt (códigos 006/403).
 */
$config['gtxt_cuenta_matriz'] = array(
    // BANFAN (Banfoandes): mismo formato 51/54 caracteres que BICENTENARIO
    // (confirmado contra docs/fanban.doc y contra un archivo real de envío
    // aportado por el usuario). Cuenta de GRUPO (empresa=1) confirmada real.
    // ⚠️ Cuenta de COOPERATIVA (empresa=0) sigue TEMPORAL/INVENTADA —
    // pendiente de que el negocio la aporte; no usar para empresa=0 real.
    'BANFAN' => array(
        0 => '65078815731922156813',
        1 => '01770038381102432424',
    ),
    'BICENTENARIO' => array(
        0 => '01750011250000034702',
        1 => '01750541650071150460',
    ),
    // COTERO: sin cuenta matriz real todavía — placeholder de puros ceros
    // (a pedido del usuario, solo para poder probar la generación del TXT).
    // ⚠️ TEMPORAL/INVENTADA — reemplazar por la cuenta real antes de usar en producción.
    'COTERO' => array(
        0 => '00000000000000000000',
        1 => '00000000000000000000',
    ),
    'PROVINCIAL' => array(
        0 => '01080372150100025942',
        1 => '01080372160100063534',
    ),
    'FONDO COMUN' => array(
        0 => '01510138528138022782',
        1 => '01510138528138022782',
    ),
    'FONDO COMÚN' => array(
        0 => '01510138528138022782',
        1 => '01510138528138022782',
    ),
    'MERCANTIL' => array(
        0 => '01050092351092060936',
        1 => '01050092351092060936',
    ),
    'BANESCO' => array(
        0 => '01340209472091008389',
        1 => '01340209472091008389',
    ),
    'BOD' => array(
        0 => '01160183950006365779',
        1 => '01160183950006365779',
    ),
    'INDUSTRIAL' => array(
        0 => '00030064120001042759',
        1 => '00030064170001058979',
    ),
    'SOFITASA' => array(
        0 => '01370021440000099741',
        1 => '01370021440000099741',
    ),
    'DEL SUR' => array(
        0 => '01570076963776200986',
        1 => '01570076963776200986',
    ),
    'CARONI' => array(
        0 => '01280072047200668106',
        1 => '01280072047200668106',
    ),
);

$config['gtxt_codigo_empresa_default'] = array(
    0 => '0006',
    1 => '0403',
);

$config['gtxt_codigo_referencia'] = '01';

/**
 * Overrides por banco (tienen prioridad sobre los defaults genéricos de
 * arriba). Confirmado con un archivo real enviado a BANFAN: código de
 * empresa 0147 y código de referencia 21 — distintos a los genéricos
 * (0006/0403 y '01') que sí aplican para los demás bancos.
 */
$config['gtxt_codigo_empresa_por_banco'] = array(
    'BANFAN' => '0147',
    // Confirmado con archivo real enviado a BICENTENARIO (empresa Grupo);
    // el usuario confirmó que aplica igual para Cooperativa.
    'BICENTENARIO' => '3275',
    // Regla de oro (confirmada por el usuario): TXT Bolívares, código fijo por banco.
    'VENEZUELA' => '0000',
);

$config['gtxt_codigo_referencia_por_banco'] = array(
    'BANFAN' => '21',
);

$config['gtxt_formato_bancario'] = array(
    'BANFAN',
    'BICENTENARIO',
    'PROVINCIAL',
    'FONDO COMUN',
    'FONDO COMÚN',
    'MERCANTIL',
    'BANESCO',
    'BOD',
    'INDUSTRIAL',
    'SOFITASA',
    'DEL SUR',
    'CARONI',
);
