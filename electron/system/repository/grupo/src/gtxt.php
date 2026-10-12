#!/usr/bin/env php
<?php
/**
 * CLI compatible con el binario Gtxt (C++).
 *
 * Uso:
 *   php gtxt.php "\"BICENTENARIO\" 0 2015-02-01 4 0 20150218 \"VARIAS\"" md5hash C 100
 *
 * Imprime en stdout: OK;cantidad;monto
 */
if (php_sapi_name() !== 'cli') {
    fwrite(STDERR, "Solo CLI.\n");
    exit(1);
}

if ($_SERVER['argc'] < 5) {
    fwrite(STDERR, "Uso: php gtxt.php PARAMS MD5 TIPO PICAR\n");
    exit(1);
}

$sPar = $_SERVER['argv'][1];
$md5Nom = $_SERVER['argv'][2];
$tipoArchivo = strtoupper($_SERVER['argv'][3]);
$picar = isset($_SERVER['argv'][4]) ? $_SERVER['argv'][4] : '100';

if (!preg_match('/^"([^"]+)"\s+(\d+)\s+(\S+)\s+(\d+)\s+(\d+)\s+(\S+)\s+"([^"]*)"$/', $sPar, $m)) {
    fwrite(STDERR, "Formato de parametros invalido.\n");
    exit(1);
}

$param = array(
    'banco' => $m[1],
    'empresa' => (int) $m[2],
    'fecha' => $m[3],
    'periodicidad' => $m[4],
    'fcontrato' => (int) $m[5],
    'fact' => $m[6],
    'nomina' => $m[7],
    'tipo_archivo' => $tipoArchivo,
    'picar' => $picar,
    'md5_nom' => $md5Nom,
);

$root = realpath(dirname(__FILE__) . '/../../../..');
if ($root === false) {
    fwrite(STDERR, "No se encontro la raiz de electron.\n");
    exit(1);
}

chdir($root);
$param['base_dir'] = $root . '/';

define('BASEPATH', $root . '/system/');
define('APPPATH', $root . '/system/application/');
define('EXT', '.php');

require_once APPPATH . 'config/database.php';
require_once APPPATH . 'models/cobranza/gtxt_cli_db.php';
require_once APPPATH . 'models/cobranza/gtxt_generator.php';

$dbConn = new GtxtCliDb($db['default']);
$gen = new GtxtGenerator($dbConn);
$result = $gen->generar($param);

if (empty($result['ok'])) {
    fwrite(STDERR, (isset($result['error']) ? $result['error'] : 'Error') . "\n");
    exit(1);
}

echo $result['stdout'];
exit(0);
