<?php
require_once('../classes/tcpdf/tcpdf.php');
require_once('../classes/viagem.class.php');
$viagem = new viagem();

class ici extends TCPDF {
	public function Header() {
		$this->Sety(10);
		$this->SetFont('helvetica', 'B', 20);
		$this->Cell(0, 10, 'AGÊNCIA 5M DE IRAJÁ LTDA', 0, 1, 'C', 0, '', 0, false, 'M', 'M');
		$this->SetFont('helvetica', 'B', 14);
		$this->Cell(0, 0, 'Contatos dos passageiros - '.$_POST['viagem'], 0, 1, 'C', 0, '', 1);
	
		if ($this->transporte == '') {
			$this->transporte = '1';
		}

		$this->Ln(5);
		$this->SetFont('helvetica', 'B', 11);
		$this->Cell(200, 7, 'Transporte '.$this->transporte, 'B', 1, 'C', 0, '', 1);
		$this->Cell(165, 0, 'Nome dos passageiros', 'B', 0, 'C', 0, '', 1);
		$this->Cell(35, 0, 'Telefone(s)', 'B', 0, 'C', 0, '', 1);
	}
}

$pdf = new ici(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

//margens
$pdf->SetMargins(5, 39, 5);

//quebra de pagina
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

$lista_passageiros = $viagem -> contatos_passageiros($_POST['id_viagem']);
if (count($lista_passageiros) > 0) {
	$contatos = array();
	foreach ($lista_passageiros as $l) {
		$contatos[$l['numero_transporte']][] = $l;
	}

	foreach($contatos as $transporte => $c) {
		$pdf->transporte = $transporte;
		$pdf->addPage();
		foreach($c as $l) {
			$pdf->SetFont('helvetica', '', 11);
			$pdf->Cell(120, 0, $l['cliente'], 'B', 0, 'L', 0, '', 1);
			$pdf->Cell(80, 0, $l['telefones'], 'B', 1, 'R', 0, '', 1);
		}
	}

	$pdf->Ln(5);
	$pdf->Cell(200, 0, count($lista_passageiros).' Passageiros', 0, 1, 'R', 0, '', 1);
}
else {
	$pdf->Cell(0, 0, 'Nenhum passageiro na viagem', 0, 1, 'C', 0, '', 1);
}

//Close and output PDF document
$pdf->Output($_POST['viagem'].' - Contatos dos passageiros.pdf',$_POST['output']);