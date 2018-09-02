<?php
switch($_POST[acao]) {
	case 'cadastrar_ponto':
		require_once ('../classes/ponto_embarque.class.php');
		$ponto_embarque = new ponto_embarque();
		
		echo $ponto_embarque -> cadastrar($_POST);
	break;
	case 'editar_ponto':
		require_once ('../classes/ponto_embarque.class.php');
		$ponto_embarque = new ponto_embarque();
		
		$ponto_embarque -> alterar($_POST);
	break;
	case 'verificar_ponto':
		require_once ('../classes/ponto_embarque.class.php');
		$ponto_embarque = new ponto_embarque();
		
		echo $ponto_embarque -> verificar($_POST['ponto']);
	break;
	case 'excluir_ponto':
		require_once ('../classes/ponto_embarque.class.php');
		$ponto_embarque = new ponto_embarque();
		
		echo $ponto_embarque -> excluir($_POST['ponto']);
	break;
}