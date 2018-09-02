<?php
function controle_cliente($acao) {
	switch($acao) {
		case 'cadastrar':
			if ($_POST) {
				require_once('classes/cliente.class.php');
				$cliente = new cliente();

				$cliente -> cadastrar($_POST);
				echo "<script>caixa_mensagem('MMTurismo','Cliente cadastrado com sucesso.');</script>";
			}
			
			require_once('classes/cliente_situacao.class.php');
			$cliente_situacao = new cliente_situacao();
			require_once('classes/estado.class.php');
			$estado = new estado();
			require_once('classes/tipo_telefone.class.php');
			$tipo_telefone = new tipo_telefone();
			require_once('classes/orgao_emissor.class.php');
			$orgao_emissor = new orgao_emissor();
			
			global $lista_situacao;
			global $lista_estado;
			global $lista_tipo_telefone;
			global $lista_orgao_emissor;
			
			$lista_situacao = $cliente_situacao -> listar();
			$lista_estado = $estado -> listar();
			$lista_tipo_telefone = $tipo_telefone -> listar();
			$lista_orgao_emissor = $orgao_emissor -> listar();
		break;
		case 'alterar':
			if (($_POST) && ($_POST['acao'] != 'lista')) {
				require_once('classes/cliente.class.php');
				$cliente = new cliente();
		
				$cliente -> alterar($_POST);
				echo "<script>caixa_mensagem('MMTurismo','Cliente alterado com sucesso.');</script>";
			}
				
			require_once('classes/cliente_situacao.class.php');
			$cliente_situacao = new cliente_situacao();
			require_once('classes/estado.class.php');
			$estado = new estado();
			require_once('classes/tipo_telefone.class.php');
			$tipo_telefone = new tipo_telefone();
			require_once('classes/orgao_emissor.class.php');
			$orgao_emissor = new orgao_emissor();
				
			global $lista_situacao;
			global $lista_estado;
			global $lista_tipo_telefone;
			global $lista_orgao_emissor;
				
			$lista_situacao = $cliente_situacao -> listar();
			$lista_estado = $estado -> listar();
			$lista_tipo_telefone = $tipo_telefone -> listar();
			$lista_orgao_emissor = $orgao_emissor -> listar();
		break;
		case 'lista':
			require_once('classes/cliente.class.php');
			$cliente = new cliente();

			global $lista;

			$lista = $cliente -> lista_geral();
		break;
	}
}
?>