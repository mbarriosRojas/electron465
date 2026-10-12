<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Quita ONLY_FULL_GROUP_BY de la sesión MySQL para compatibilidad
 * con consultas legacy que usan GROUP BY con columnas no agrupadas (Error 1055).
 */
class Sql_mode_hook {

	public function set_sql_mode() {
		$CI =& get_instance();
		$CI->load->database();
		@$CI->db->query("SET SESSION sql_mode = (SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''))");
	}
}
