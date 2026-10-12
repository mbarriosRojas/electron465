<?php


header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="01simple.xlsx"');
header('Cache-Control: max-age=0');




// Redirect output to a client’s web browser (Excel5)
$PHPXls = new PHPExcel();
//$PHPXls->Generar();

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
$this->PHPXls->getActiveSheet()->setTitle('Simple');
//
//
//		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$this->PHPXls->setActiveSheetIndex(0);
?>