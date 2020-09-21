<?php
/*error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);*/
date_default_timezone_set('America/Sao_Paulo');

define('EOM',(PHP_SAPI == 'cli') ? PHP_EOF : '<br />');

require_once ('../classes/PHPExcel.php');
require_once('../classes/cliente.class.php');
require_once('funcoes.php');
$cliente = new cliente();

$contatos = $cliente -> lista_contatos();

$objPHPExcel = new PHPExcel();

$objPHPExcel->getDefaultStyle()
    ->getNumberFormat()
    ->setFormatCode(
        PHPExcel_Style_NumberFormat::FORMAT_TEXT
    );

$outline = array(
	'borders' => array(
    	'outline' => array(
      		'style' => PHPExcel_Style_Border::BORDER_THIN
    	)
  	)
);

$all = array(
	'borders' => array(
    	'allborders' => array(
      		'style' => PHPExcel_Style_Border::BORDER_THIN
    	)
  	)
);

$center = array(
    'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
    )
);

$vcenter = array(
    'alignment' => array(
        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
    )
);

$fonte = array(
	'font'  => array(
    	'size' => 10,
    	'name' => 'calibri'
	)
);

$menor = array(
	'font'  => array(
    	'size' => 8,
    	'name' => 'calibri'
	)
);

$negrito = array(
	'font' => array(
    	'bold' => true,
    	'size' => 10
	)
);

$objPHPExcel->getProperties()->setCreator("NMMTurismo")
							 ->setLastModifiedBy("MMTurismo")
							 ->setTitle('Lista de Contatos');

$objPHPExcel->setActiveSheetIndex(0);

$planilha = $objPHPExcel->getActiveSheet();
$planilha->getDefaultStyle()->applyFromArray($fonte);
$planilha->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
$planilha->getPageSetup()->setFitToPage(true);
$planilha->getPageSetup()->setFitToWidth(1);
$planilha->getPageSetup()->setFitToHeight(0);
$planilha->getColumnDimension('A')->setWidth(4);
$planilha->getColumnDimension('B')->setWidth(8);
$planilha->getColumnDimension('K')->setWidth(5);
$planilha->getColumnDimension('M')->setWidth(5);
$planilha->getColumnDimension('P')->setWidth(5);

$planilha->mergeCells('B1'.':H1');
$planilha->mergeCells('I1'.':J1');
$planilha->mergeCells('K1'.':L1');
$planilha->getStyle('B1')->applyFromArray($negrito);
$planilha->getStyle('I1')->applyFromArray($negrito);
$planilha->getStyle('K1')->applyFromArray($negrito);
$planilha->setCellValue('B1', 'Cliente');
$planilha->setCellValue('I1', 'Telefones');
$planilha->setCellValue('K1', 'E-mail');

$atual = 2;
while ($contato = mysql_fetch_assoc($contatos)) {
    $planilha->mergeCells('B'.$atual.':H'.$atual);
    $planilha->mergeCells('I'.$atual.':J'.$atual);
    $planilha->mergeCells('K'.$atual.':L'.$atual);
    $planilha->setCellValue('B'.$atual, utf8_encode($contato['cliente']));
    $planilha->setCellValue('I'.$atual, $contato['telefones']);
    $planilha->setCellValue('K'.$atual, $contato['email']);
    $atual++;
}

$planilha->setTitle('Lista de Contatos');

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Contatos.xls"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');