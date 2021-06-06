<?php
function controle_viagem($acao) {
	switch($acao) {
		case 'cadastrar':
			if ($_POST) {
				require_once('classes/viagem.class.php');
				$viagem = new viagem();
				
				$viagem -> cadastrar($_POST);
				echo "<script>caixa_mensagem('MMTurismo','Viagem cadastrada com sucesso.');</script>";
			}
			require_once('classes/estado.class.php');
			$estado = new estado();
			require_once('classes/tipo_transporte.class.php');
			$tipo_transporte = new tipo_transporte();
			require_once('classes/acomodacao.class.php');
			$acomodacao = new acomodacao();
			require_once('classes/ponto_embarque.class.php');
			$ponto_embarque = new ponto_embarque();
			
			global $lista_estado;
			global $lista_tipo_transporte;
			global $lista_acomodacao;
			global $lista_ponto;
			
			$estados = $estado -> listar();
			foreach ($estados as $l) {
				$lista_estado .= "<option value='".$l['id']."'>".$l['sigla']."</option>";
			}
			$acomodacoes = $acomodacao -> listar();
			foreach ($acomodacoes as $l) {
				$lista_acomodacao .= "<option value='".$l['id']."'>".$l['acomodacao']."</option>";
			}
			$pontos = $ponto_embarque -> listar();
			foreach ($pontos as $l) {
				$lista_ponto .= "<option value='".$l['id']."'>".$l['ponto']."</option>";
			}
			$lista_tipo_transporte = $tipo_transporte -> listar();
		break;
		case 'alterar':
			if ($_POST) {
				require_once('classes/viagem.class.php');
				$viagem = new viagem();
				
				$viagem -> alterar($_POST);
				echo "<script>caixa_mensagem('MMTurismo','Viagem alterada com sucesso.');</script>";
			}
			require_once('classes/estado.class.php');
			$estado = new estado();
			require_once('classes/tipo_transporte.class.php');
			$tipo_transporte = new tipo_transporte();
			require_once('classes/acomodacao.class.php');
			$acomodacao = new acomodacao();
			require_once('classes/ponto_embarque.class.php');
			$ponto_embarque = new ponto_embarque();
			
			global $lista_estado;
			global $lista_tipo_transporte;
			global $lista_acomodacao;
			global $lista_ponto;
			
			$estados = $estado -> listar();
			foreach ($estados as $l) {
				$lista_estado .= "<option value='".$l['id']."'>".$l['sigla']."</option>";
			}
			$acomodacoes = $acomodacao -> listar();
			foreach ($acomodacoes as $l) {
				$lista_acomodacao .= "<option value='".$l['id']."'>".$l['acomodacao']."</option>";
			}
			$pontos = $ponto_embarque -> listar();
			foreach ($pontos as $l) {
				$lista_ponto .= "<option value='".$l['id']."'>".$l['ponto']."</option>";
			}
			$lista_tipo_transporte = $tipo_transporte -> listar();
		break;
	}
}
?>