<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 *	Generar las descripciones de la nominas en excel
 *
 *  @author Carlos Enrique Penaa Albarran
 *  @package
 *  @version 1.0.0
 */

require_once(BASEPATH . "application/libraries/include.xls.inc.php");

class CNominaXls extends Model{

	/**
	 * @var PHPExcel
	 */
	private $PHPExcel;

	/**
	 * @var string
	 */
	private $Ruta;


	/**
	 * Estructura del Excel en Campos.
	 *
	 * @var arrray
	 */
	private $_DTD_NOMINA = array(
	'NULL','A','B','C','D','E','F','G','H','I','J','K','L',
	'M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z',
	'AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL',
	'AM','AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ',
	'BA','BB','BC','BD','BE','BF','BG','BH','BI','BJ','BK','BL',
	'BM','BN','BO','BP','BQ','BR','BS','BT','BU','BV','BW','BX','BY','BZ');

	/**
	 * Define los campos que se muestran en la nomina
	 *
	 * @var arrray
	 */
	private $Cabezera_Nomina = array('NULL',
	'CEDULA','APELLIDOS Y NOMBRES','CONTRATO', 'CUENTA','BANCO','NOMINA','FECHA DE INICIO COBRO','CUOTA');

	function __construct(){
		parent::__construct();
		$this->PHPExcel = new PHPExcel();
	}
	public function _Cabezera(){
		$intCabezera = count($this->Cabezera_Nomina);


		$this->PHPExcel->getDefaultStyle()->getFont()->setName('DejaVu Sans Mono');
		$this->PHPExcel->getDefaultStyle()->getFont()->setSize(9);


		/* ------------------------------------
		 * Hoja Totales
		 * ------------------------------------
		 */
		for($i=1; $i<count($this->Cabezera_Nomina); $i++){
			$this->PHPExcel->setActiveSheetIndex(0)->setCellValue($this->_DTD_NOMINA[$i] . "1", $this->Cabezera_Nomina[$i]);
			//Auto 	Dimesion de Columnas
			$this->PHPExcel->getActiveSheet()->getColumnDimension($this->_DTD_NOMINA[$i] )->setAutoSize(true);
		}
		
		//Estableciendo el color de fondo de la primera Fila
		$this->PHPExcel->getActiveSheet(0)->getStyle('A1:' . $this->_DTD_NOMINA[count($this->Cabezera_Nomina)-1] . '1')
		->getFill()
		->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
		->getStartColor()->setARGB('BEBEBEBE');

	}

	/**
	 * @param $strTipo | 1 : AGUINALDOS 2: VACACIONES
	 */
	public function _Cuerpo($strAno = "", $strMes = "", $strTipo = ""){
		if($strTipo == 1){
			$strQuery = "SELECT * FROM t_personas INNER JOIN t_clientes_creditos ON t_personas.documento_id=t_clientes_creditos.documento_id
			WHERE forma_contrato=" . $strTipo ." AND fecha_inicio_cobro like '" . $strAno . "-%" . $strMes . "%'";
		}else{
			$strQuery = "SELECT * FROM t_personas INNER JOIN t_clientes_creditos ON t_personas.documento_id=t_clientes_creditos.documento_id
			WHERE forma_contrato=" . $strTipo ." AND fecha_inicio_cobro like '" . $strAno . "-%" . $strMes . "%'";
		}
		$Consulta = $this->db->query($strQuery);

		//'CEDULA','APELLIDOS Y NOMBRES','CONTRATO', 'CUENTA','BANCO','NOMINA','TOTAL');
		$i = 1;
		if ($Consulta->num_rows() > 0)
		{
			{
				foreach ($Consulta->result() as $row)
				{
					$i++;
					$this->PHPExcel->setActiveSheetIndex(0)
					->setCellValue('A' . $i, $row->documento_id)
					->setCellValue('B' . $i, $row->primer_apellido . " " . $row->segundo_apellido . " " . $row->primer_nombre . " " . $row->segundo_nombre)
					->setCellValue('C' . $i, "N-" . $row->contrato_id)
					->setCellValue('D' . $i, "C-" . $row->cuenta_1)
					->setCellValue('E' . $i, $row->cobrado_en)
					->setCellValue('F' . $i, $row->nomina_procedencia)
					->setCellValue('G' . $i, $row->fecha_inicio_cobro)
					->setCellValue('H' . $i, $row->monto_cuota);					
					$this->PHPExcel->getActiveSheet(0)->getStyle('G' . $i)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);					
				}
			}
		}
		
		
	}


	public function _Generar($strNomina = null){
		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$this->PHPExcel->setActiveSheetIndex(0);

		$objWriter = PHPExcel_IOFactory::createWriter($this->PHPExcel, 'Excel5');

		$strRuta = BASEPATH . "repository/xls/" .  $strNomina . ".xls";
		$objWriter->save($strRuta );
	}

	function __destruct(){
		$this->PHPExcel = null;
	}
}