<?php
function controle_orgao_emissor($acao) {
	switch($acao) {
		case 'cadastrar':
			require_once('classes/orgao_emissor.class.php');
			$orgao_emissor = new orgao_emissor();
			
			global $lista_orgao_emissor;
			
			$lista_orgao_emissor= $orgao_emissor -> listar();
		break;
	}
}			