<?php
require_once('../classes/tcpdf/tcpdf.php');
require_once('../classes/viagem.class.php');
$viagem = new viagem();

class ici extends TCPDF {
	public function Header() {
	}
	public function Footer() {
	}
}

$pdf = new ici(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
$pdf->SetMargins(5, 5, 5);
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

$lista_hoteis = $viagem -> buscar_hoteis($_POST['id_viagem']);
if (mysql_num_rows($lista_hoteis) > 0) {
	while ($lh = mysql_fetch_array($lista_hoteis)) {
		$pdf->AddPage();
		$pdf->SetFont('helvetica', 'B', 14);
		$pdf->Cell(0, 0, utf8_encode($lh['hotel']." - ".$lh['cidade']." - ".$lh['sigla']), 0, 1, 'C', 0, '', 1);
		$pdf->SetFont('helvetica', '', 11);
		$pdf->Cell(0, 0, "Hospedagem de ".$lh['data_chegada']." a ".$lh['data_saida'], 0, 1, 'C', 0, '', 1);
		$pdf->MultiCell(0, 10, $_POST['cabecalho'], 0, 'C', 0, 1, '', '', true);
	
		$pdf->Ln(5);
		$pdf->SetFont('helvetica', 'B', 11);
		$pdf->Cell(135, 0, utf8_encode('Hóspedes'), 'B', 0, 'C', 0, '', 1);
		$pdf->Cell(40, 0, utf8_encode('Acomodações'), 'B', 0, 'C', 0, '', 1);
		$pdf->Cell(0, 0, 'APTO', 'B', 1, 'C', 0, '', 1);
		
		$pdf->SetFont('helvetica', '', 11);
		$rooming = $viagem -> buscar_rooming_hotel($lh['id'],$lh['id_viagem']);
		if (mysql_num_rows($rooming) > 0) {
			while ($l = mysql_fetch_array($rooming)) {
				$y = $pdf->getY();
				$pdf->MultiCell(135, 0, utf8_encode(str_replace(',',"\n",$l['nome_clientes'])),'B','L',false,1);
				$tamanho = $pdf->getY()-$y;
				$pdf->MultiCell(40, $tamanho, utf8_encode($l['acomodacao']),'B','C',false,1,140,$y,true,1,false,true,$tamanho,'M');
				$pdf->MultiCell(0, $tamanho, utf8_encode($l['apto']),'B','C',false,1,180,$y,true,1,false,true,$tamanho,'M');
			}
		}
		else {
			$pdf->Cell(0, 0, utf8_encode('Nenhum hóspede na viagem'), 0, 1, 'C', 0, '', 1);
		}
	}
}
else {
	$pdf->Cell(0, 0, utf8_encode('Nenhum hotel na viagem'), 0, 1, 'C', 0, '', 1);
}

//Close and output PDF document
$pdf->Output($_POST['viagem'].' - Rooming List.pdf',$_POST['output']);