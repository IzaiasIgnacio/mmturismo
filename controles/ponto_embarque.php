<?php
function controle_ponto($acao) {
	switch($acao) {
		case 'cadastrar':
			require_once('classes/ponto_embarque.class.php');
			$ponto_embarque = new ponto_embarque();
			require_once('classes/cidade.class.php');
			$cidade = new cidade();
			
			global $lista_cidade;
			global $lista_ponto;
			
			$lista_cidade = $cidade -> listar(19);
			$lista_ponto = $ponto_embarque -> listar();
		break;
	}
}			