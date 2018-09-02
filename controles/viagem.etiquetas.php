<?php
require_once('../classes/tcpdf/tcpdf.php');
require_once('../classes/viagem.class.php');
$viagem = new viagem();

class ici extends TCPDF {
	public function Header() {
	}
	public function Footer() {
	}
	function exibir($dados,$x=15) {
		$this->SetX($x);
		$this->Cell(90,2,'','LTR',2,'C',false,'',0,true);
		$this->Cell(90,5,utf8_encode("AGÊNCIA 5M DE IRAJÁ"),'LR',2,'C',false,'',0,true);
		$this->SetFont('helvetica', 'B', 10);
		$this->Cell(90,7,utf8_encode($dados['hotel'].' - '.$dados['cidade'].' - '.$dados['sigla']),'LR',2,'C',false,'',1,true);
		$this->SetFont('helvetica', 'B', 11);
		$this->Cell(90,6,'Tel. '.str_replace(',',' / ',$dados['telefones']),'LR',2,'C',false,'',0,true);
		$this->SetFont('helvetica', '', 25);
		if ($dados['apto'] != '') {
			$this->Cell(90,12,'Apto. '.$dados['apto'],'LR',2,'C',false,'',0,true);
		}
		else {
			$this->Cell(90,12,'','LR',2,'C',false,'',0,true);
		}
		$this->SetFont('helvetica', '', 15);
		$this->Cell(90,9,'Nome:','LR',2,'C',false,'',0,true,'T','B');
		$this->SetFont('helvetica', '', 20);
		$this->Cell(90,10,utf8_encode($dados['cliente']),'LBR',2,'C',false,'',1,true);
	}
}

$pdf = new ici(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
$pdf->SetMargins(15, 10, 15);

$pdf->AddPage();

$lista_passageiros = $viagem -> lista_etiquetas($_POST);
if (mysql_num_rows($lista_passageiros) > 0) {
	$proxima = 0;
	$num = 0;
	$posicao = '';
	$y = '';
	$y_direita = '';
	while ($l = mysql_fetch_array($lista_passageiros)) {
		if ($num > 9) {
			$pdf->AddPage();
			$y = 10;
			$num = 0;
		}
		$pdf->SetFont('helvetica', '', 11);
		if ($posicao == '') {
			if ($y != '') {
				$pdf->setY($y);
			}
			$y_direita = $pdf->getY();
			$pdf->exibir($l);
			$y = $pdf->getY();
			$posicao = 1;
		}
		else {
			$pdf->setXY(105,$y_direita);
			$pdf->exibir($l,105);
			$posicao = '';
		}
		$proxima = ($proxima == 1) ? 0 : 1;
		$num++;
	}
}
else {
	$pdf->Cell(0, 0, 'Nenhum passageiro na viagem', 0, 1, 'C', 0, '', 1);
}

$pdf->Output($_POST['viagem'].' - Etiquetas.pdf',$_POST['output']);