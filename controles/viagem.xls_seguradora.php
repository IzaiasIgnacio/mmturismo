<?php
/*error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);*/
date_default_timezone_set('America/Sao_Paulo');

require_once ('../classes/PHPExcel.php');
require_once('../classes/viagem.class.php');
require_once('funcoes.php');
$viagem = new viagem();

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

$titulo = array(
	'font' => array(
    	'bold' => true,
    	'size' => 14
	)
);

$objPHPExcel->getProperties()->setCreator("NMMTurismo")
							 ->setLastModifiedBy("MMTurismo")
							 ->setTitle('Listagem para Seguro Viagem');

function cabecalho($criar = false) {
	global $fonte;
	global $center;
	global $titulo;
	global $negrito;
	global $objPHPExcel;
	
	if ($criar) {
		$planilha = $objPHPExcel->createSheet();
	}
	else {
		$planilha = $objPHPExcel->getActiveSheet();
	}
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

	$planilha->mergeCells('B1'.':L2');
	$planilha->getStyle('B1')->applyFromArray($center);
	$planilha->getStyle('B1')->applyFromArray($titulo);
	$planilha->setCellValue('B1', 'AGÊNCIA 5M DE IRAJÁ');
	$planilha->mergeCells('B3'.':L3');
	$planilha->getStyle('B3')->applyFromArray($center);
	$planilha->getStyle('B3')->applyFromArray($negrito);
	$planilha->setCellValue('B3', 'Listagem para Seguro Viagem');

	$planilha->mergeCells('B4'.':H4');
	$planilha->mergeCells('I4'.':J4');
	$planilha->mergeCells('K4'.':L4');
	$planilha->getStyle('B4')->applyFromArray($negrito);
	$planilha->getStyle('I4')->applyFromArray($negrito);
	$planilha->getStyle('K4')->applyFromArray($negrito);
	$planilha->setCellValue('B4', 'Cliente');
	$planilha->setCellValue('I4', 'CPF');
	$planilha->setCellValue('K4', 'Data de Nascimento');

	return $planilha;
}

$lista_passageiros = $viagem -> lista_seguro($_POST['id_viagem']);

if (count($lista_passageiros) > 0) {
	$passageiros = array();
	foreach ($lista_passageiros as $l) {
		$passageiros[$l['empresa']." ".$l['numero_transporte']][] = array($l['cliente'],$l['cpf'],$l['data_nascimento']);
	}
	
	$criar = false;
	foreach ($passageiros as $transporte => $pessoa) {
		$planilha = cabecalho($criar);
		$atual = 5;
		$planilha->setTitle($transporte);
		foreach ($pessoa as $p) {
			$planilha->mergeCells('B'.$atual.':H'.$atual);
			$planilha->mergeCells('I'.$atual.':J'.$atual);
			$planilha->mergeCells('K'.$atual.':L'.$atual);
			$planilha->setCellValue('B'.$atual, $p[0]);
			$planilha->setCellValueExplicit('I'.$atual, $p[1], PHPExcel_Cell_DataType::TYPE_STRING);
			$planilha->setCellValue('K'.$atual, $p[2]);
			$atual++;
		}
		$planilha->mergeCells('J'.$atual.':L'.$atual);
		$planilha->setCellValue('J'.$atual, count($pessoa).' Passageiros');
		$criar = true;
	}
}

$objPHPExcel->setActiveSheetIndex(0);

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="'.$_POST['viagem'].' - Lista para seguro.xls"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');