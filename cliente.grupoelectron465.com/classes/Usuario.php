<?php
/**
 * Clase para manejo de datos de usuario
 */

class Usuario {
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
    }
    
    /**
     * Obtener datos básicos del cliente para sesión
     */
    public function getDatosBasicosSession($documentoId) {
        try {
            $documentoIdEscaped = $this->db->escape($documentoId);
            
            // Obtener datos del cliente desde t_personas
            $query = "SELECT documento_id, nacionalidad, primer_nombre, segundo_nombre, 
                             primer_apellido, segundo_apellido, telefono, correo, cargo_actual,
                             banco_1, cuenta_1, tipo_cuenta_1, banco_2, cuenta_2, tipo_cuenta_2,
                             direccion_trabajo
                      FROM t_personas 
                      WHERE documento_id = '$documentoIdEscaped' 
                      LIMIT 1";
            
            $result = $this->db->query($query);
            
            if ($result->num_rows === 0) {
                return null;
            }
            
            $cliente = $result->fetch_assoc();
            
            // Construir nombre completo
            $nombreCompleto = trim(
                $cliente['primer_nombre'] . ' ' . 
                ($cliente['segundo_nombre'] ? $cliente['segundo_nombre'] . ' ' : '') .
                $cliente['primer_apellido'] . ' ' .
                ($cliente['segundo_apellido'] ? $cliente['segundo_apellido'] : '')
            );
            
            return [
                'documento_id' => $cliente['documento_id'],
                'nacionalidad' => $cliente['nacionalidad'],
                'nombre_completo' => $nombreCompleto,
                'primer_nombre' => $cliente['primer_nombre'],
                'segundo_nombre' => $cliente['segundo_nombre'],
                'primer_apellido' => $cliente['primer_apellido'],
                'segundo_apellido' => $cliente['segundo_apellido'],
                'telefono' => $cliente['telefono'],
                'correo' => $cliente['correo'],
                'cargo_actual' => $cliente['cargo_actual']
            ];
            
        } catch (Exception $e) {
            return null;
        }
    }
}

