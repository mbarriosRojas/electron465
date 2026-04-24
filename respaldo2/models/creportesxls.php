<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

/**
 *	Generar excel
 *
 *  @author JUDELVIS RIVAS
 *  @package
 *  @version 1.0.0
 */

require_once (BASEPATH . "application/libraries/include.xls.inc.php");

class CReportesXls extends Model {

	/**
	 * @var PHPExcel
	 */
	private $PHPExcel;

	/**
	 * @var string
	 */
	private $Ruta;
	
	/**
	 * @var string
	 */
	private $Nombre;

	/**
	 * Estructura del Excel en Campos.
	 *
	 * @var arrray
	 * 
	 */
	private $_DTD_NOMINA = array('NULL', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN', 'AO', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV', 'AW', 'AX', 'AY', 'AZ', 'BA', 'BB', 'BC', 'BD', 'BE', 'BF', 'BG', 'BH', 'BI', 'BJ', 'BK', 'BL', 'BM', 'BN', 'BO', 'BP', 'BQ', 'BR', 'BS', 'BT', 'BU', 'BV', 'BW', 'BX', 'BY', 'BZ');

	/**
	 * Define los campos que se muestran en la nomina
	 *
	 * @var arrray
	 */
	private $Cabezera_Excel = array();
	private $Cuerpo_Excel = array();

	function __construct() {
		parent::__construct();uct();
		$this -> PHPExcel = new PHPExcel();
	}
	

	public function _Cabezera() {
		$intCabezera = count($this -> Cabezera_Excel);

		$this -> PHPExcel -> getDefaultStyle() -> getFont() -> setName('DejaVu Sans Mono');
		$this -> PHPExcel -> getDefaultStyle() -> getFont() -> setSize(9);

		/* ------------------------------------
		 * Hoja Totales
		 * ------------------------------------
		 */
		$i=1;
		foreach ($this -> Cabezera_Excel as $clave => $valor) {
			$this -> PHPExcel -> setActiveSheetIndex(0) -> setCellValue($this->_DTD_NOMINA[$i] . "1", $valor);
			$this->PHPExcel->getActiveSheet()->getColumnDimension($this->_DTD_NOMINA[$i] )->setAutoSize(true);
			$i++;
		}
		$this -> PHPExcel -> getActiveSheet(0) -> getStyle('A1:' . $this -> _DTD_NOMINA[count($this -> Cabezera_Excel) ] . '1') -> getFill() -> setFillType(PHPExcel_Style_Fill::FILL_SOLID) -> getStartColor() -> setARGB('BEBEBEBE');

	}

	/**
	 * @param $strTipo | 1 : AGUINALDOS 2: VACACIONES
	 */
	public function _Cuerpo() {
		$i = 2;
		if (count($this->Cuerpo_Excel) > 0) {
			foreach ($this->Cuerpo_Excel as $fila=>$columnas) {
				$j=1;
				foreach ($columnas as $clave => $valor) {
					$pos = $this->_DTD_NOMINA[$j];
					$this -> PHPExcel -> setActiveSheetIndex(0) -> setCellValueExplicit($pos.$i,$valor, PHPExcel_Cell_DataType::TYPE_STRING);
					//$this -> PHPExcel -> setActiveSheetIndex(0) -> setCellValue($this->_DTD_NOMINA[$j] . $i, $valor);
					$j++;
				}
				$i++;
			}

		}

	}

	public function _Generar($cabeza , $cuerpo, $nombre) {
		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$this->Cabezera_Excel = $cabeza;
		$this->Cuerpo_Excel = $cuerpo;
		$this->Nombre = $nombre;
		$this->_Cabezera();
		$this->_Cuerpo();
		$this -> PHPExcel -> setActiveSheetIndex(0);

		$objWriter = PHPExcel_IOFactory::createWriter($this -> PHPExcel, 'Excel5');

		$strRuta = BASEPATH . "repository/xls/".$this->Nombre.".xls";
		$objWriter -> save($strRuta);
	}

	function __destruct() {
		$this -> PHPExcel = null;
	}

}
