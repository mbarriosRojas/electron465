<?php
/**
 *  @author Judelvis Antonio Rivas PErdomo
 */
class CCatalogo extends Model {
	function __construct() {
		parent::__construct();
		//$this -> load -> library('pdf');

	}

	function Genera_Catalogo() {
		$this -> load -> library('pdf');
		$catalogo = new $this -> pdf();
		$direc = K_PATH_IMAGES . 'catalogo/';
		$font = 'times';
		$catalogo -> SetHeaderMargin(0);
		$catalogo -> SetFooterMargin(0);
		$catalogo -> SetAutoPageBreak(false, 0);
		$catalogo -> setPrintHeader(FALSE);
		$catalogo -> setPrintFooter(FALSE);
		$catalogo -> setJPEGQuality(100);
		$catalogo -> AddPage('L', 'A6');
		$img_file = $direc . 'portada.jpg';
		$catalogo -> Image($img_file, 0, 0,148,105,'', '', '', false, 300, '', false, false, 0);
		$consulta = "SELECT * FROM t_artefactos
		 JOIN t_inventario ON t_inventario.artefacto = artefacto_id
		 WHERE catalogo = 1 AND  foto != ''
		 ";
		$datos = $this -> db -> query($consulta);
		foreach ($datos->result() as $row) {
			$catalogo -> AddPage('L', 'A6');
			$catalogo -> Image($direc . 'interno2.jpg', 0, 0,148,105,'', '', '', false, 300, '', false, false, 0);
			$catalogo -> setXY(15, 25);
			$catalogo -> Cell(0, 4, $row -> nombre . ' Modelo: ' . $row -> modelo, 0, 0, 'C', 0);
			if($row -> detalle != ''){
				//$catalogo -> MultiCell();
			}
			$catalogo -> Image($direc . $row -> foto, 20, 30, 0, 55, '', '', 'C', FALSE, 300, '', false, false, 0, '', FALSE, FALSE);
		}
		$catalogo -> Output('catalogo.pdf', 'D');
	}

}
?>
