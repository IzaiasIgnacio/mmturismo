<?php
switch($_POST[acao]) {
	case 'cadastrar_orgao_emissor':
		require_once ('../classes/orgao_emissor.class.php');
		$orgao_emissor = new orgao_emissor();
		
		echo $orgao_emissor -> cadastrar($_POST);
	break;
	case 'editar_orgao_emissor':
		require_once ('../classes/orgao_emissor.class.php');
		$orgao_emissor = new orgao_emissor();
		
		$orgao_emissor -> alterar($_POST);
	break;
	case 'verificar_orgao_emissor':
		require_once ('../classes/orgao_emissor.class.php');
		$orgao_emissor = new orgao_emissor();
		
		echo $orgao_emissor -> verificar($_POST['orgao']);
	break;
	case 'excluir_orgao_emissor':
		require_once ('../classes/orgao_emissor.class.php');
		$orgao_emissor = new orgao_emissor();
		
		echo $orgao_emissor -> excluir($_POST['orgao']);
	break;
}