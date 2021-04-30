<?php
class ponto_embarque {
	
	function cadastrar($valores) {
		require_once('database.class.php');
		$conexao = new database();
		require_once('../controles/funcoes.php');
		
		$sql = "insert into ponto_embarque (bairro, local, id_cidade) values ";
		$sql .= "('".campo_sql($valores['bairro'])."','".campo_sql($valores['local'])."',
				'".$valores['cidade']."')";
		return $conexao -> query($sql);
	}

	function alterar($valores) {
		require_once('database.class.php');
		$conexao = new database();
		require_once('../controles/funcoes.php');

		$dados = [
			'bairro' => campo_sql($valores['bairro']),
			'local' => campo_sql($valores['local']),
			'id_cidade' => campo_sql($valores['cidade']),
			'id_ponto' => $valores['id_ponto']
		];
		
		$sql = "update ponto_embarque set
					bairro = :bairro,
					local = :local,
					id_cidade = :id_cidade
						where id = :id_ponto";
		$conexao -> execute($sql, $dados);
	}
	
	function verificar($ponto) {
		require_once('database.class.php');
		$conexao = new database();
		
		$sql = "select id
					from viagem_cliente
						where id_ponto_embarque = ".$ponto;
		$r = $conexao -> query($sql);
		return count($r);
	}
	
	function excluir($ponto) {
		require_once('database.class.php');
		$conexao = new database();
		
		$sql = "delete from ponto_embarque where id = ".$ponto;
		return $conexao -> execute($sql);
	}
	
	function listar() {
		require_once('database.class.php');
		$conexao = new database();
		
		$sql = "select ponto_embarque.*, cidade.cidade, concat(bairro,' - ',local) as ponto
					from ponto_embarque
						inner join cidade on cidade.id = ponto_embarque.id_cidade
							order by cidade, bairro, local";
		return $conexao -> query($sql);
	}
	
}
?>