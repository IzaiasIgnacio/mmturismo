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

$pdf->AddPage();

$data = explode("/",$_POST['data']);

$dias[0] = 'Domingo';
$dias[1] = 'Segunda Feira';
$dias[2] = utf8_encode('Terça Feira');
$dias[3] = 'Quarta Feira';
$dias[4] = 'Quinta Feira';
$dias[5] = 'Sexta Feira';
$dias[6] = utf8_encode('Sábado');
$dia_semana = $dias[jddayofweek(gregoriantojd(intval($data[1]),intval($data[0]),intval($data[2])))];

//lista de onibus
$lista_transportes = $viagem -> lista_transportes($_POST['id_viagem']);
if (mysql_num_rows($lista_transportes) > 0) {
	while ($l = mysql_fetch_array($lista_transportes)) {
		$t = 0;
		//onibus de cada empresa
		for ($i=1;$i<=$l['quantidade'];$i++) {
			$pdf->SetFont('helvetica', 'B', 11);
			$pdf->Cell(0, 0, utf8_encode("Embarque ".$_POST['viagem']." ".$_POST['data']." (".utf8_decode($dia_semana).") ".$l['empresa']." - Ônibus ".$i), 1, 1, 'C', 0, '', 1);
			$transportes[$t] = utf8_encode("Embarque ".$_POST['viagem']." ".$_POST['data']." (".utf8_decode($dia_semana).") ".$l['empresa']." - Ônibus ".$i);
			$pdf->Ln(1);
			$lista_pontos = $viagem -> passageiros_pontos($l['id'],$i);
			$c = 0;
			//pontos de embarque de cada onibus
			if (mysql_num_rows($lista_pontos) > 0) {
				$pdf->Cell(20, 0, utf8_encode('Horário'), 0, 0, 'L', 0, '', 1);
				$pdf->Cell(0, 0, 'Ponto de embarque', 0, 1, 'L', 0, '', 1);
				$pdf->SetFont('helvetica', '', 11);
				while ($lp = mysql_fetch_array($lista_pontos)) {
					$pdf->Cell(20, 0, $lp['hora_embarque'], 'T', 0, 'L', 0, '', 1);
					$pdf->Cell(0, 0, utf8_encode($lp['ponto']), 'T', 1, 'L', 0, '', 1);
					$pontos[$t][$c] = utf8_encode($lp['hora_embarque']." - ".$lp['ponto']);
					$clientes = explode(",",$lp['clientes']);
					//passageiros
					foreach ($clientes as $cli) {
						$passageiros[$t][$c][] = $cli;
					}
					$c++;
				}
				$pdf->Ln(2);
				$t++;
			}
			else {
				$pdf->Cell(0, 0, 'Nenhum ponto de embarque nessa viagem', 0, 1, 'L', 0, '', 1);
			}
		}
	}
	
	if (count($pontos) > 0) {
		$pdf->AddPage();
		//lista detalhada
		$pdf->Ln(10);
		foreach ($transportes as $t => $trans) {
			$pdf->SetFont('helvetica', 'B', 11);
			$pdf->Cell(0, 0, $trans, 1, 1, 'C', 0, '', 1);
			$pdf->Ln(2);
			if (count($pontos[$t]) > 0) {
				foreach ($pontos[$t] as $p => $po) {
					$pdf->SetFont('helvetica', 'B', 11);
					$pdf->Cell(0, 0, $po, 0, 1, 'C', 0, '', 1);
					$pdf->Ln(1);
					$pdf->Cell(20, 0, 'Poltrona', 0, 0, 'L', 0, '', 1);
					$pdf->Cell(0, 0, 'Passageiro', 0, 1, 'L', 0, '', 1);
					$pdf->SetFont('helvetica', '', 11);
					foreach ($passageiros[$t][$p] as $pa) {
						$c = explode("|",$pa);
						$pdf->Cell(20, 0, $c[1], 'T', 0, 'C', 0, '', 1);
						$pdf->Cell(0, 0, utf8_encode($c[0]), 'T', 1, 'L', 0, '', 1);
					}
					$pdf->Ln(2);
				}
			}
		}
	}
}
else {
	$pdf->Cell(0, 0, utf8_encode('Nenhum ônibus na viagem'), 0, 1, 'C', 0, '', 1);
}

//Close and output PDF document
$pdf->Output($_POST['viagem'].' - Embarque.pdf',$_POST['output']);