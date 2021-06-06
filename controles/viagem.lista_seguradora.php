<?php
require_once('../classes/tcpdf/tcpdf.php');
require_once('../classes/viagem.class.php');
$viagem = new viagem();

class ici extends TCPDF {
	
	public function Header() {
		$this->Sety(10);
		$this->SetFont('helvetica', 'B', 20);
		$this->Cell(0, 10, utf8_encode('AGÊNCIA 5M DE IRAJÁ LTDA'), 0, 1, 'C', 0, '', 0, false, 'M', 'M');
		$this->SetFont('helvetica', '', 11);
		$this->SetFont('helvetica', 'B', 14);
		$this->Cell(0, 0, 'Listagem para Seguro Viagem', 0, 1, 'C', 0, '', 1);
		
		$this->Ln(3);
		$this->SetFont('helvetica', 'B', 11);
		$this->Cell(125, 0, 'Nome dos Passageiros', 'B', 0, 'C', 0, '', 1);
		$this->Cell(35, 0, 'CPF', 'B', 0, 'C', 0, '', 1);
		$this->Cell(40, 0, 'Data de Nascimento', 'B', 1, 'C', 0, '', 1);
		
		$this->SetFont('helvetica', '', 11);
	}
}

$pdf = new ici(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

//margens
$pdf->SetMargins(5, 29, 5);

//quebra de pagina
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

$lista_passageiros = $viagem -> lista_seguro($_POST['id_viagem']);
if (count($lista_passageiros) > 0) {
	$passageiros = array();
	foreach ($lista_passageiros as $l) {
		$passageiros[$l['empresa']][] = array($l['cliente'],$l['cpf'],$l['data_nascimento']);
	}
	
	foreach ($passageiros as $empresa => $pessoa) {
		$pdf->empresa = utf8_encode($empresa);
		$pdf->AddPage();
		$c = 0;
		foreach ($pessoa as $p) {
			$pdf->Cell(125, 0, utf8_encode($p[0]), 'B', 0, 'L', 0, '', 1);
			$pdf->Cell(35, 0, $p[1], 'B', 0, 'C', 0, '', 1);
			$pdf->Cell(40, 0, $p[2], 'B', 1, 'C', 0, '', 1);
			$c++;
		}
		$pdf->Ln(2);
		$pdf->Cell(200, 0, $c.' Passageiros', 0, 1, 'R', 0, '', 1);
	}
}
else {
	$pdf->Cell(0, 0, 'Nenhum passageiro na viagem', 0, 1, 'C', 0, '', 1);
}

//Close and output PDF document
$pdf->Output($_POST['viagem'].' - Lista para seguro.pdf',$_POST['output']);