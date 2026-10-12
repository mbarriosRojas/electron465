<?php
/**
 * Clase para manejo de conexión a la base de datos
 */

class Database {
    private $connection;
    
    public function __construct() {
        $this->connect();
    }
    
    private function connect() {
        $this->connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        
        if ($this->connection->connect_error) {
            throw new Exception('Error de conexión: ' . $this->connection->connect_error);
        }
        
        $this->connection->set_charset(DB_CHARSET);
    }
    
    public function getConnection() {
        return $this->connection;
    }
    
    public function query($sql) {
        $result = $this->connection->query($sql);
        if (!$result) {
            throw new Exception('Error en consulta: ' . $this->connection->error);
        }
        return $result;
    }
    
    public function escape($string) {
        return $this->connection->real_escape_string($string);
    }
    
    public function getLastInsertId() {
        return $this->connection->insert_id;
    }
    
    public function close() {
        if ($this->connection) {
            $this->connection->close();
        }
    }
    
    public function __destruct() {
        $this->close();
    }
}

