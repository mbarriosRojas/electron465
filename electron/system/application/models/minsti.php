<?php

/**
 *  @author Judelvis Antonio Rivas Perdomo
 *  @package SaGem.system.application
 *  @version 1.0.0
 */

class MInsti extends Model {

    /**
     * @var integer
     */
    var $id ;

    /**
     * @var string
     */
    var $nombre;

    /**
     * @var string
     */
    var $direccion;

    /**
     * @var string
     */
    var $telefono;

    public function __construct() {
        parent::__construct();
    }

    public function Combo() {
        $strCombo='';
        $strQuery = $this->db->query("SELECT * FROM t_institucion order by nombre");
        if ($strQuery->num_rows() > 0)	{
            foreach ($strQuery->result() as $row){
                $strCombo .= '<option value="' . $row->id.'">'.$row->nombre.'</option>';
            }
        }
        return $strCombo;

    }
    public function Eliminar($strNombre) {
        $this -> db -> where("nombre", trim($strNombre));
        $this -> db -> delete("t_institucion");

    }

}
?>