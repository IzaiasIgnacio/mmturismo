<?php
require_once('../classes/tcpdf/tcpdf.php');
require_once('../classes/viagem.class.php');
$viagem = new viagem();

class ici extends TCPDF {
	public function Header() {
		
	}
	
	function cabecalho($empresa,$numero) {
		$this->SetFont('helvetica', 'B', 20);
		$this->Cell(0, 5, 'AGÊNCIA 5M DE IRAJÁ LTDA', 0, 1, 'C', 0, '', 0, false, 'M', 'M');
		$this->SetFont('helvetica', '', 11);
		$this->MultiCell(0, 2, $_POST['cabecalho'], 0, 'C', 0, 1, '', '', true);
		
		$this->SetFont('helvetica', 'B', 14);
		$this->Cell(0, 0, 'Listagem para Empresa de Transporte', 0, 1, 'C', 0, '', 1);
	
		$this->SetFont('helvetica', 'B', 11);
		$this->Cell(0, 0, $empresa." ".$numero, 0, 1, 'C', 0, '', 1);
		$this->Cell(140, 0, 'Nome dos passageiros', 'B', 0, 'C', 0, '', 1);
		$this->Cell(25, 0, 'Registro', 'B', 0, 'C', 0, '', 1);
		$this->Cell(35, 0, 'Órgão Emissor', 'B', 1, 'C', 0, '', 1);
	
		$this->SetFont('helvetica', '', 11);
	}
}

$pdf = new ici(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

//margens
$pdf->SetMargins(5, 10, 5);

//quebra de pagina
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

$lista_passageiros = $viagem -> lista_passageiros($_POST['id_viagem']);
if (count($lista_passageiros) > 0) {
	$passageiros = array();
	foreach ($lista_passageiros as $l) {
		$passageiros[$l['empresa']." - ".$l['tipo_transporte']][$l['numero_transporte']][] = array($l['cliente'],$l['rg'],$l['sigla']);
	}
	
	foreach ($passageiros as $empresa => $transporte) {
		$num = count($transporte);
		for ($i=1;$i<=$num;$i++) {
			$c = 0;
			$pdf->AddPage();
			$pdf->cabecalho($empresa,$i);
			foreach ($transporte[$i] as $pessoa) {
				$pdf->Cell(125, 0, $pessoa[0], 'B', 0, 'L', 0, '', 1);
				$pdf->Cell(35, 0, $pessoa[1], 'B', 0, 'C', 0, '', 1);
				$pdf->Cell(40, 0, $pessoa[2], 'B', 1, 'C', 0, '', 1);
				$c++;
			}
			$pdf->Ln(2);
			$pdf->Cell(190, 0, $c.' Passageiros', 0, 1, 'R', 0, '', 1);
		}
	}
}
else {
	$pdf->Cell(0, 0, 'Nenhum passageiro na viagem', 0, 1, 'C', 0, '', 1);
}

//Close and output PDF document
$pdf->Output($_POST['viagem'].' - Lista para Transporte.pdf',$_POST['output']);