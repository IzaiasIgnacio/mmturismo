<?php
require_once('../classes/tcpdf/tcpdf.php');
require_once('../classes/cliente.class.php');
$cliente = new cliente();

class ici extends TCPDF {
	public function Header() {
	}
	public function Footer() {
	}
	//exibir uma etiqueta
	//$x = margem esquerda
	function exibir($dados,$x=3) {
		$this->SetX($x);
		//largura da etiqueta = 101.6
		//altura da etiqueta = 25.4
		$this->Cell(101.6,0.7,'','',2,'L',false,'',0,true);
		$this->Cell(101.6,6,mb_convert_case(utf8_encode($dados['cliente']),MB_CASE_TITLE,'UTF-8'),'LTR',2,'L',false,'',0,true);
		$complemento = ($dados['complemento'] == '') ? '' : ' - '.$dados['complemento'];
		$this->Cell(101.6,6,mb_convert_case(utf8_encode($dados['endereco']),MB_CASE_TITLE,'UTF-8').', '.$dados['numero'].mb_convert_case(utf8_encode($complemento),MB_CASE_TITLE,'UTF-8'),'LR',2,'L',false,'',1,true);
		$this->Cell(101.6,6,'Bairro: '.mb_convert_case(utf8_encode($dados['bairro']),MB_CASE_TITLE,'UTF-8'),'LR',2,'L',false,'',0,true);
		$this->Cell(101.6,6,mb_convert_case(utf8_encode($dados['cidade']),MB_CASE_TITLE,'UTF-8').' - '.$dados['sigla'].' CEP: '.$dados['cep'],'LBR',2,'L',false,'',1,true);
		$this->Cell(101.6,1.4,'','',2,'L',false,'',0,true);
	}
}

$pdf = new ici(PDF_PAGE_ORIENTATION, PDF_UNIT, 'A4', true, 'UTF-8', false);
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
$pdf->SetAutoPageBreak(false);
//margem esquerda, margem superior
$pdf->SetMargins(3, 12);

//posicao vertical da etiqueta
$posicao_vertical[0] = 12;
$posicao_vertical[1] = 12;
$posicao_vertical[2] = 37.4;
$posicao_vertical[3] = 37.4;
$posicao_vertical[4] = 62.8;
$posicao_vertical[5] = 62.8;
$posicao_vertical[6] = 88.2;
$posicao_vertical[7] = 88.2;
$posicao_vertical[8] = 113.6;
$posicao_vertical[9] = 113.6;
$posicao_vertical[10] = 139;
$posicao_vertical[11] = 139;
$posicao_vertical[12] = 164.4;
$posicao_vertical[13] = 164.4;
$posicao_vertical[14] = 189.8;
$posicao_vertical[15] = 189.8;
$posicao_vertical[16] = 215.2;
$posicao_vertical[17] = 215.2;
$posicao_vertical[18] = 240.6;
$posicao_vertical[19] = 240.6;
$posicao_vertical[20] = 266;
$posicao_vertical[21] = 266;

$pdf->AddPage();

$lista_cliente = $cliente -> lista_etiquetas($_POST);
if (mysql_num_rows($lista_cliente) > 0) {
	$num = 0;
	$posicao = '';
	$y = '';
	$y_direita = '';
	$espaco_vertical = 0;
	while ($l = mysql_fetch_array($lista_cliente)) {
		//22 etiquetas por pagina
		if ($num > 21) {
			//nova pagina
			$pdf->AddPage();
			//margem superior
			$y = 11;
			$num = 0;
		}
		$pdf->SetFont('helvetica', '', 11);
		//coluna da esquerda
		if ($posicao == '') {
			//posicao vertical da etiqueta
			$pdf->setY($posicao_vertical[$num]);
			$pdf->exibir($l);
			//exibir proxima etiqueta do lado direito
			$posicao = 1;
		}
		//coluna da direita
		else {
			//105.6 = margem da esquerda (margem inicial + largura da etiqueta + espaco entre etiquetas)
			//3 + 101.6 + 2
			$pdf->setY($posicao_vertical[$num]);
			$pdf->exibir($l,106.6);
			//exibir proxima etiqueta do lado esquerdo
			$posicao = '';
		}
		$num++;
	}
}
else {
	$pdf->Cell(0, 0, 'Nenhum cliente encontrado', 0, 1, 'C', 0, '', 1);
}

$pdf->Output('Etiquetas_clientes.pdf','I');