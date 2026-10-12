<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Adaptador mysqli minimo para ejecutar GtxtGenerator desde CLI sin bootstrap CodeIgniter.
 */
class GtxtCliDb {

    private $mysqli;

    public function __construct($cfg) {
        $this->mysqli = new mysqli(
            $cfg['hostname'],
            $cfg['username'],
            $cfg['password'],
            $cfg['database']
        );
        if ($this->mysqli->connect_error) {
            throw new RuntimeException('Error de conexion MySQL: ' . $this->mysqli->connect_error);
        }
        $this->mysqli->set_charset(isset($cfg['char_set']) ? $cfg['char_set'] : 'latin1');
    }

    public function escape($str) {
        return "'" . $this->mysqli->real_escape_string($str) . "'";
    }

    public function query($sql) {
        $rs = $this->mysqli->query($sql);
        if ($rs === false) {
            return false;
        }
        if ($rs === true) {
            return new GtxtCliDbResult(array());
        }
        $rows = array();
        while ($row = $rs->fetch_object()) {
            $rows[] = $row;
        }
        $rs->free();
        return new GtxtCliDbResult($rows);
    }
}

class GtxtCliDbResult {

    private $rows;

    public function __construct($rows) {
        $this->rows = $rows;
    }

    public function num_rows() {
        return count($this->rows);
    }

    public function result() {
        return $this->rows;
    }
}
