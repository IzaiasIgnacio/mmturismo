<?php
/*error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);*/
date_default_timezone_set('America/Sao_Paulo');

define('EOM',(PHP_SAPI == 'cli') ? PHP_EOF : '<br />');

require_once ('../classes/PHPExcel.php');
require_once('../classes/viagem.class.php');
require_once('funcoes.php');
$viagem = new viagem();

$clientes = $viagem -> buscar_clientes($_POST['id_viagem']);

while ($cliente = mysql_fetch_assoc($clientes)) {
	$transportes_viagem[$cliente['numero_transporte']][] = $cliente;
}

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
							 ->setTitle($_POST['viagem']);

$objPHPExcel->setActiveSheetIndex(0);

$planilha = $objPHPExcel->getActiveSheet();
$planilha->getDefaultStyle()->applyFromArray($fonte);
$planilha->setShowGridlines(false);
$planilha->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
$planilha->getPageSetup()->setFitToPage(true);
$planilha->getPageSetup()->setFitToWidth(1);
$planilha->getPageSetup()->setFitToHeight(0);
$planilha->getColumnDimension('A')->setWidth(4);
$planilha->getColumnDimension('B')->setWidth(8);
$planilha->getColumnDimension('K')->setWidth(5);
$planilha->getColumnDimension('M')->setWidth(5);
$planilha->getColumnDimension('P')->setWidth(5);

function cabecalho($inicio, $planilha) {
	global $outline;
	global $all;
	global $center;
	global $vcenter;
	global $fonte;
	global $menor;
	global $negrito;
	if ($inicio > 2) {
		$inicio+=2;
	}
	$linha = array();
	$linha[1] = $inicio;
	$linha[2] = $inicio+1;
	$linha[3] = $inicio+2;
	$linha[4] = $inicio+3;
	$linha[5] = $inicio+4;
	$linha[6] = $inicio+5;
	$linha[7] = $inicio+6;
	$linha[8] = $inicio+7;
	$linha[9] = $inicio+8;
	$planilha->mergeCells('C'.$linha[1].':O'.$linha[1]);
	$planilha->mergeCells('B'.$linha[2].':C'.$linha[2]);
	$planilha->mergeCells('D'.$linha[2].':O'.$linha[2]);
	$planilha->mergeCells('C'.$linha[3].':D'.$linha[3]);
	$planilha->mergeCells('F'.$linha[3].':O'.$linha[3]);
	$planilha->mergeCells('B'.$linha[4].':C'.$linha[4]);
	$planilha->mergeCells('D'.$linha[4].':O'.$linha[4]);
	$planilha->mergeCells('B'.$linha[5].':C'.$linha[5]);
	$planilha->mergeCells('D'.$linha[5].':O'.$linha[5]);
	$planilha->mergeCells('B'.$linha[6].':D'.$linha[6]);
	$planilha->mergeCells('E'.$linha[6].':G'.$linha[6]);
	$planilha->mergeCells('H'.$linha[6].':J'.$linha[6]);
	$planilha->mergeCells('K'.$linha[6].':M'.$linha[6]);
	$planilha->mergeCells('N'.$linha[6].':O'.$linha[6]);
	$planilha->mergeCells('B'.$linha[7].':D'.$linha[7]);
	$planilha->mergeCells('E'.$linha[7].':G'.$linha[7]);
	$planilha->mergeCells('H'.$linha[7].':J'.$linha[7]);
	$planilha->mergeCells('K'.$linha[7].':M'.$linha[7]);
	$planilha->mergeCells('N'.$linha[7].':O'.$linha[7]);
	$planilha->mergeCells('C'.$linha[8].':I'.$linha[8]);
	$planilha->mergeCells('J'.$linha[8].':K'.$linha[8]);
	$planilha->mergeCells('L'.$linha[8].':M'.$linha[8]);
	$planilha->mergeCells('N'.$linha[8].':O'.$linha[8]);

	$planilha->getStyle('B'.$linha[1].':O'.$linha[1])->applyFromArray($outline);
	$planilha->getStyle('B'.$linha[2].':O'.$linha[5])->applyFromArray($outline);
	$planilha->getStyle('B'.$linha[6].':O'.$linha[8])->applyFromArray($all);
	$planilha->getStyle('B'.$linha[8].':O'.$linha[8])->applyFromArray($center);
	$planilha->getStyle('B'.$linha[8].':O'.$linha[8])->applyFromArray($vcenter);
	$planilha->getStyle('B'.$linha[6])->applyFromArray($center);
	$planilha->getStyle('B'.$linha[6])->applyFromArray($menor);

	$planilha->setCellValue('B'.$linha[1], 'CLIENTE');

	$planilha->setCellValue('B'.$linha[2], 'RAZÃO SOCIAL:');

	$planilha->setCellValue('B'.$linha[3], 'BAIRRO:');
	$planilha->setCellValue('E'.$linha[3], 'CIDADE:');
	$planilha->setCellValue('N'.$linha[3], 'UF:');

	$planilha->setCellValue('B'.$linha[4], 'DIA DO SERVIÇO:');

	$planilha->setCellValue('B'.$linha[5], 'LOCAL DE DESTINO:');

	$planilha->setCellValue('B'.$linha[6], 'QUANTIDADE DE PESSOAS');

	$planilha->setCellValue('B'.$linha[8], 'Nº');
	$planilha->setCellValue('C'.$linha[8], 'NOME COMPLETO');
	$planilha->setCellValue('J'.$linha[8], "DATA DE\nNASCIMENTO");
	$planilha->setCellValue('L'.$linha[8], 'Nº IDENTIDADE');
	$planilha->setCellValue('N'.$linha[8], "ÓRGÃO\nEXPEDIDOR");
}

foreach ($transportes_viagem as $numero => $transporte) {
	$inicio = $planilha->getHighestRow()+1;
	cabecalho($inicio, $planilha);
	$inicio = $planilha->getHighestRow()+1;
	$atual = $inicio;
	$i = 1;
	$criancas = array();
	foreach ($transporte as $cliente_viagem => $c) {
		if (idade($c['data_nascimento'])) {
			$planilha->getStyle('B'.$atual)->applyFromArray($center);
			$planilha->mergeCells('C'.$atual.':I'.$atual);
			$planilha->mergeCells('J'.$atual.':K'.$atual);
			$planilha->mergeCells('L'.$atual.':M'.$atual);
			$planilha->mergeCells('N'.$atual.':O'.$atual);
			$planilha->setCellValue('B'.$atual, sprintf("%02d", $i));
			$planilha->setCellValue('C'.$atual, utf8_encode($c['cliente']));
			$planilha->setCellValue('J'.$atual, $c['data_nascimento']);
			$planilha->setCellValueExplicit('L'.$atual, $c['rg'], PHPExcel_Cell_DataType::TYPE_STRING);
			$planilha->setCellValue('N'.$atual, utf8_encode($c['sigla']));
			$atual++;
			$i++;
		}
		else {
			$criancas[] = $c;
		}
	}

	$fim = $planilha->getHighestRow();

	$planilha->getStyle('B'.$inicio.':O'.$fim)->applyFromArray($all);

	if (count($criancas) > 0) {
		$planilha->getRowDimension($atual)->setRowHeight(18);
		$planilha->setCellValue('B'.$atual, '   MENORES DE COLO (0 A 05 ANOS)');
		$atual++;

		$planilha->mergeCells('C'.$atual.':I'.$atual);
		$planilha->mergeCells('J'.$atual.':K'.$atual);
		$planilha->mergeCells('L'.$atual.':M'.$atual);
		$planilha->mergeCells('N'.$atual.':O'.$atual);
		$planilha->setCellValue('B'.$atual, 'Nº');
		$planilha->setCellValue('C'.$atual, 'NOME COMPLETO');
		$planilha->setCellValue('J'.$atual, "DATA DE\nNASCIMENTO");
		$planilha->setCellValue('L'.$atual, "Nº IDENTIDADE\nOU CERT. NASCIMENTO");
		$planilha->setCellValue('N'.$atual, "ÓRGÃO\nEXPEDIDOR");
		$atual++;

		$inicio = $atual;
		$i = 1;
		foreach ($criancas as $crianca) {
			$planilha->getStyle('B'.$atual)->applyFromArray($center);
			$planilha->mergeCells('C'.$atual.':I'.$atual);
			$planilha->mergeCells('J'.$atual.':K'.$atual);
			$planilha->mergeCells('L'.$atual.':M'.$atual);
			$planilha->mergeCells('N'.$atual.':O'.$atual);
			$planilha->setCellValue('B'.$atual, sprintf("%02d", $i));
			$planilha->setCellValue('C'.$atual, utf8_encode($crianca['cliente']));
			$planilha->setCellValue('J'.$atual, $crianca['data_nascimento']);
			$planilha->setCellValue('L'.$atual, $crianca['rg']);
			$planilha->setCellValue('N'.$atual, utf8_encode($crianca['sigla']));
			$atual++;
			$i++;
		}

		$fim = $planilha->getHighestRow();

		$header = $inicio-1;
		$planilha->getStyle('B'.$header.':O'.$header)->applyFromArray($center);
		$planilha->getStyle('B'.$header.':O'.$header)->applyFromArray($vcenter);
		$planilha->getStyle('B'.$header.':O'.$fim)->applyFromArray($all);

		$planilha->getStyle('B'.$inicio.':O'.$fim)->applyFromArray($all);
	}
}

$planilha->setTitle($_POST['viagem']);

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="'.$_POST['viagem'].'.xls"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');