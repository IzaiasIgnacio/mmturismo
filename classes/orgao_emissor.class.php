<?php
class orgao_emissor {
	
	function cadastrar($valores) {
		require_once('conexao.class.php');
		$conexao = new conexao();
		require_once('../controles/funcoes.php');
		
		$sql = "insert into orgao_emissor (sigla, orgao_emissor) values ";
		$sql .= "('".campo_sql($valores['sigla'])."',
				'".campo_sql($valores['orgao_emissor'])."')";
		$r = $conexao -> query($sql);
		
		return $r;
	}

	function alterar($valores) {
		require_once('conexao.class.php');
		$conexao = new conexao();
		require_once('../controles/funcoes.php');
		
		$sql = "update orgao_emissor set
					sigla = '".campo_sql($valores['sigla'])."',
					orgao_emissor = '".campo_sql($valores['orgao_emissor'])."'
						where id = ".$valores['id_orgao_emissor'];
		$conexao -> query($sql);
	}
	
	function verificar($orgao_emissor) {
		require_once('conexao.class.php');
		$conexao = new conexao();
		
		$sql = "select id
					from cliente
						where id_orgao_emissor = ".$orgao_emissor;
		$r = $conexao -> query($sql);
		
		return mysql_num_rows($r);
	}
	
	function excluir($orgao_emissor) {
		require_once('conexao.class.php');
		$conexao = new conexao();
		
		$sql = "delete from orgao_emissor where id = ".$orgao_emissor;
		$r = $conexao -> query($sql);
		
		return $r;
	}
	
	function listar() {
		require_once('conexao.class.php');
		$conexao = new conexao();
		
		$sql = "select id, sigla, orgao_emissor
					from orgao_emissor
						order by sigla";
		$r = $conexao -> query($sql);
		
		return $r;
	}
	
}
?>