<?php
function controle_empresa($acao) {
	switch($acao) {
		case 'cadastrar':
			if ($_POST) {
				require_once('classes/empresa.class.php');
				$empresa = new empresa();
				
				$empresa -> cadastrar($_POST);
				echo "<script>caixa_mensagem('MMTurismo','Empresa cadastrada com sucesso.');</script>";
			}
			
			require_once('classes/estado.class.php');
			$estado = new estado();
			require_once('classes/tipo_transporte.class.php');
			$tipo_transporte = new tipo_transporte();
			
			global $lista_estado;
			global $lista_tipo_transporte;
			
			$lista_estado = $estado -> listar();
			$lista_tipo_transporte = $tipo_transporte -> listar();
		break;
		case 'alterar':
			if ($_POST) {
				require_once('classes/empresa.class.php');
				$empresa = new empresa();
				
				$empresa -> alterar($_POST);
				echo "<script>caixa_mensagem('MMTurismo','Empresa alterada com sucesso.');</script>";
			}
			
			require_once('classes/estado.class.php');
			$estado = new estado();
			require_once('classes/tipo_transporte.class.php');
			$tipo_transporte = new tipo_transporte();
			
			global $lista_estado;
			global $lista_tipo_transporte;
			
			$lista_estado = $estado -> listar();
			$lista_tipo_transporte = $tipo_transporte -> listar();
		break;
	}
}
?>