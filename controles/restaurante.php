<?php
function controle_restaurante($acao) {
	switch($acao) {
		case 'cadastrar':
			if ($_POST) {
				require_once('classes/restaurante.class.php');
				$restaurante = new restaurante();

				$restaurante -> cadastrar($_POST);
				echo "<script>caixa_mensagem('MMTurismo','Restaurante cadastrado com sucesso.');</script>";
			}
			
			require_once('classes/estado.class.php');
			$estado = new estado();
			
			global $lista_estado;
			
			$lista_estado = $estado -> listar();
		break;
		case 'alterar':
			if ($_POST) {
				require_once('classes/restaurante.class.php');
				$restaurante = new restaurante();
		
				$restaurante -> alterar($_POST);
				echo "<script>caixa_mensagem('MMTurismo','Restaurante alterado com sucesso.');</script>";
			}
				
			require_once('classes/estado.class.php');
			$estado = new estado();
				
			global $lista_estado;
				
			$lista_estado = $estado -> listar();
		break;
	}
}
?>