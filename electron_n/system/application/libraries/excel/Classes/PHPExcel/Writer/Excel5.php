<?php
/**
 * PHPExcel
 *
 * Copyright (c) 2006 - 2010 PHPExcel
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @category   PHPExcel
 * @package    PHPExcel_Writer_Excel5
 * @copyright  Copyright (c) 2006 - 2010 PHPExcel (http://www.codeplex.com/PHPExcel)
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt	LGPL
 * @version    1.7.2, 2010-01-11
 */


/** PHPExcel root directory */
if (!defined('PHPEXCEL_ROOT')) {
	/**
	 * @ignore
	 */
	define('PHPEXCEL_ROOT', dirname(__FILE__) . '/../../');
}

/** PHPExcel */
require_once PHPEXCEL_ROOT . 'PHPExcel.php';

/** PHPExcel_Writer_IWriter */
require_once PHPEXCEL_ROOT . 'PHPExcel/Writer/IWriter.php';

/** PHPExcel_Writer_Excel5_Parser */
require_once PHPEXCEL_ROOT . 'PHPExcel/Writer/Excel5/Parser.php';

/** PHPExcel_Writer_Excel5_BIFFwriter */
require_once PHPEXCEL_ROOT . 'PHPExcel/Writer/Excel5/BIFFwriter.php';

/** PHPExcel_Writer_Excel5_Workbook */
require_once PHPEXCEL_ROOT . 'PHPExcel/Writer/Excel5/Workbook.php';

/** PHPExcel_Writer_Excel5_Worksheet */
require_once PHPEXCEL_ROOT . 'PHPExcel/Writer/Excel5/Worksheet.php';

/** PHPExcel_Writer_Excel5_Font */
require_once PHPEXCEL_ROOT . 'PHPExcel/Writer/Excel5/Font.php';

/** PHPExcel_Shared_OLE */
require_once PHPEXCEL_ROOT . 'PHPExcel/Shared/OLE.php';

/** PHPExcel_Shared_OLE_PPS_Root */
require_once PHPEXCEL_ROOT . 'PHPExcel/Shared/OLE/OLE_Root.php';

/** PHPExcel_Shared_OLE_PPS_File */
require_once PHPEXCEL_ROOT . 'PHPExcel/Shared/OLE/OLE_File.php';


/**
 * PHPExcel_Writer_Excel5
 *
 * @category   PHPExcel
 * @package    PHPExcel_Writer_Excel5
 * @copyright  Copyright (c) 2006 - 2010 PHPExcel (http://www.codeplex.com/PHPExcel)
 */
class PHPExcel_Writer_Excel5 implements PHPExcel_Writer_IWriter
{
	/**
	 * PHPExcel object
	 *
	 * @var PHPExcel
	 */
	private $_phpExcel;

	/**
	 * Pre-calculate formulas
	 *
	 * @var boolean
	 */
	private $_preCalculateFormulas = true;

	/**
	 * Create a new PHPExcel_Writer_Excel5
	 *
	 * @param   PHPExcel    $phpExcel   PHPExcel object
	 */
	public function __construct(PHPExcel $phpExcel)
	{
		$this->_phpExcel = $phpExcel;
	}

	/**
	 * Save PHPExcel to file
	 *
	 * @param   string      $pFilename      Filename to write to
	 * @throws  Exception
	 */
	public function save($pFilename = null)
	{
		// garbage collect
		$this->_phpExcel->garbageCollect();

		$saveDateReturnType = PHPExcel_Calculation_Functions::getReturnDateType();
		PHPExcel_Calculation_Functions::setReturnDateType(PHPExcel_Calculation_Functions::RETURNDATE_EXCEL);

		// initialize colors array
		$colors          = array();

		// Initialise shared string variables
		$str_total       = 0;
		$str_unique      = 0;
		$str_table       = array();

		// Create parser
		$parser = new PHPExcel_Writer_Excel5_Parser(0x0600);

		// Initialise workbook writer
		$writerWorkbook = new PHPExcel_Writer_Excel5_Workbook($this->_phpExcel,
		                                                      0x0600,
		                                                      $str_total,
		                                                      $str_unique,
		                                                      $str_table,
		                                                      $colors,
		                                                      $parser);

		// Add cell XF writers to workbook
		foreach ($this->_phpExcel->getCellXfCollection() as $style) {
			$writerWorkbook->addXfWriter($style, false);
		}

		// Add cell style XF writers to workbook
		foreach ($this->_phpExcel->getCellStyleXfCollection() as $style) {
			$writerWorkbook->addXfWriter($style, true);
		}

		// Initialise worksheet writers
		$writerWorksheets = array();
		for ($i = 0; $i < $this->_phpExcel->getSheetCount(); ++$i) {
			$writerWorksheets[$i] = new PHPExcel_Writer_Excel5_Worksheet(0x0600,
			                                                             $str_total,
			                                                             $str_unique,
			                                                             $str_table,
			                                                             $colors,
			                                                             $parser,
			                                                             '',
			                                                             $this->_preCalculateFormulas,
			                                                             $this->_phpExcel->getSheet($i));
			$writerWorksheets[$i]->_initialize();
		}

		// Finalize each worksheet and collect sizes
		$worksheetSizes = array();
		for ($i = 0; $i < $this->_phpExcel->getSheetCount(); ++$i) {
			$writerWorksheets[$i]->close();
			$worksheetSizes[] = $writerWorksheets[$i]->_datasize;
		}

		// Generate workbook binary data
		$workbookData = $writerWorkbook->writeWorkbook($worksheetSizes);

		// Build OLE container
		$OLE = new PHPExcel_Shared_OLE_PPS_File(PHPExcel_Shared_OLE::Asc2Ucs('Workbook'));
		$OLE->init();
		$OLE->append($workbookData);

		// Append worksheet data streams
		for ($i = 0; $i < $this->_phpExcel->getSheetCount(); ++$i) {
			while ($data = $writerWorksheets[$i]->getData()) {
				$OLE->append($data);
			}
		}

		// Save OLE container to file
		$root = new PHPExcel_Shared_OLE_PPS_Root(time(), time(), array($OLE));
		$root->save($pFilename);

		// Cleanup temporary files
		foreach ($writerWorksheets as $writerWorksheet) {
			$writerWorksheet->cleanup();
		}

		PHPExcel_Calculation_Functions::setReturnDateType($saveDateReturnType);
	}

}
