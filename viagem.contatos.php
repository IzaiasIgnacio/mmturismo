<?php
require_once('../classes/tcpdf/tcpdf.php');
require_once('../classes/viagem.class.php');
$viagem = new viagem();

class ici extends TCPDF {
	public function Header() {
		$this->Sety(10);
		$this->SetFont('helvetica', 'B', 20);
		$this->Cell(0, 10, utf8_encode('AGÊNCIA 5M DE IRAJÁ LTDA'), 0, 1, 'C', 0, '', 0, false, 'M', 'M');
		$this->SetFont('helvetica', 'B', 14);
		$this->Cell(0, 0, 'Contatos dos passageiros - '.$_POST['viagem'], 0, 1, 'C', 0, '', 1);
	
		$this->Ln(5);
		$this->SetFont('helvetica', 'B', 11);
		$this->Cell(165, 0, 'Nome dos passageiros', 'B', 0, 'C', 0, '', 1);
		$this->Cell(35, 0, 'Telefone(s)', 'B', 0, 'C', 0, '', 1);
	}
}

$pdf = new ici(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

//margens
$pdf->SetMargins(5, 31.5, 5);

//quebra de pagina
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
$pdf->addPage();
$pdf->SetFont('helvetica', '', 11);

$lista_passageiros = $viagem -> contatos_passageiros($_POST['id_viagem']);
if (mysql_num_rows($lista_passageiros) > 0) {
	while ($l = mysql_fetch_array($lista_passageiros)) {
		$pdf->Cell(120, 0, utf8_encode($l['cliente']), 'B', 0, 'L', 0, '', 1);
		$pdf->Cell(80, 0, $l['telefones'], 'B', 1, 'R', 0, '', 1);
	}
	$pdf->Cell(200, 0, mysql_num_rows($lista_passageiros).' Passageiros', 0, 1, 'R', 0, '', 1);
}
else {
	$pdf->Cell(0, 0, 'Nenhum passageiro na viagem', 0, 1, 'C', 0, '', 1);
}

//Close and output PDF document
$pdf->Output($_POST['viagem'].' - Contatos dos passageiros.pdf',$_POST['output']);