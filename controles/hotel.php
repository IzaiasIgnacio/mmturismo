<?php
function controle_hotel($acao) {
	switch($acao) {
		case 'cadastrar':
			if ($_POST) {
				require_once('classes/hotel.class.php');
				$hotel = new hotel();

				$hotel -> cadastrar($_POST);
				echo "<script>caixa_mensagem('MMTurismo','Hotel cadastrado com sucesso.');</script>";
			}
			
			require_once('classes/estado.class.php');
			$estado = new estado();
			
			global $lista_estado;
			
			$lista_estado = $estado -> listar();
		break;
		case 'alterar':
			if ($_POST) {
				require_once('classes/hotel.class.php');
				$hotel = new hotel();

				$hotel -> alterar($_POST);
				echo "<script>caixa_mensagem('MMTurismo','Hotel alterado com sucesso.');</script>";
			}
			
			require_once('classes/estado.class.php');
			$estado = new estado();
			
			global $lista_estado;
			
			$lista_estado = $estado -> listar();
		break;
	}
}
?>