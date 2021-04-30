<?php
class orgao_emissor {
	
	function cadastrar($valores) {
		require_once('database.class.php');
		$conexao = new database();
		require_once('../controles/funcoes.php');
		
		$sql = "insert into orgao_emissor (sigla, orgao_emissor) values ";
		$sql .= "('".campo_sql($valores['sigla'])."',
				'".campo_sql($valores['orgao_emissor'])."')";
		return $conexao -> execute($sql);
	}

	function alterar($valores) {
		require_once('database.class.php');
		$conexao = new database();
		require_once('../controles/funcoes.php');
		
		$sql = "update orgao_emissor set
					sigla = '".campo_sql($valores['sigla'])."',
					orgao_emissor = '".campo_sql($valores['orgao_emissor'])."'
						where id = ".$valores['id_orgao_emissor'];
		$conexao -> execute($sql);
	}
	
	function verificar($orgao_emissor) {
		require_once('database.class.php');
		$conexao = new database();
		
		$sql = "select id
					from cliente
						where id_orgao_emissor = ".$orgao_emissor;
		$r = $conexao -> query($sql);
		
		return count($r);
	}
	
	function excluir($orgao_emissor) {
		require_once('database.class.php');
		$conexao = new database();
		
		$sql = "delete from orgao_emissor where id = ".$orgao_emissor;
		return $conexao -> execute($sql);
	}
	
	function listar() {
		require_once('database.class.php');
		$conexao = new database();
		
		$sql = "select id, sigla, orgao_emissor
					from orgao_emissor
						order by sigla";
		return $conexao -> query($sql);
	}
	
}
?>