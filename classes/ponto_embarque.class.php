<?php
class ponto_embarque {
	
	function cadastrar($valores) {
		require_once('conexao.class.php');
		$conexao = new conexao();
		require_once('../controles/funcoes.php');
		
		$sql = "insert into ponto_embarque (bairro, local, id_cidade) values ";
		$sql .= "('".campo_sql($valores['bairro'])."','".campo_sql($valores['local'])."',
				'".$valores['cidade']."')";
		$r = $conexao -> query($sql);
		
		return $r;
	}

	function alterar($valores) {
		require_once('conexao.class.php');
		$conexao = new conexao();
		require_once('../controles/funcoes.php');
		
		$sql = "update ponto_embarque set
					bairro = '".campo_sql($valores['bairro'])."',
					local = '".campo_sql($valores['local'])."',
					id_cidade = ".campo_sql($valores['cidade'])."
						where id = ".$valores['id_ponto'];
		$conexao -> query($sql);
	}
	
	function verificar($ponto) {
		require_once('conexao.class.php');
		$conexao = new conexao();
		
		$sql = "select id
					from viagem_cliente
						where id_ponto_embarque = ".$ponto;
		$r = $conexao -> query($sql);
		return mysql_num_rows($r);
	}
	
	function excluir($ponto) {
		require_once('conexao.class.php');
		$conexao = new conexao();
		
		$sql = "delete from ponto_embarque where id = ".$ponto;
		$r = $conexao -> query($sql);
		
		return $r;
	}
	
	function listar() {
		require_once('conexao.class.php');
		$conexao = new conexao();
		
		$sql = "select ponto_embarque.*, cidade.cidade, concat(bairro,' - ',local) as ponto
					from ponto_embarque
						inner join cidade on cidade.id = ponto_embarque.id_cidade
							-- order by cidade, bairro, local";
		$r = $conexao -> query($sql);
		return $r;
	}
	
}
?>