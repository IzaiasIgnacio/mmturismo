<?php
class acesso {
	
	function login($valores) {
		require_once('database.class.php');
		$conexao = new database();
		require_once('controles/funcoes.php');

		$dados = [
			'login' => campo_sql($valores['login']),
			'senha' => campo_sql(sha1($valores['senha']))
		];
		
		$sql = "select *
					from usuario
						where login = :login
						and senha = :senha";
		return $conexao -> query($sql, $dados);
	}
	
	function alterar_dados($valores) {
		require_once('database.class.php');
		$conexao = new database();
		require_once('controles/funcoes.php');
		
		$dados = [
			'id' => $_SESSION['id_usuario'],
			'senha' => campo_sql(sha1($valores['senha_atual']))
		];

		$sql = "select *
					from usuario
						where id = :id
						and senha = :senha";

		$r = $conexao -> query($sql, $dados);
		if (count($r) == 0) {
			return 0;
		}
		else {
			$comp = '';
			$dados['login'] = campo_sql($valores['login']);
			$dados['nome'] = campo_sql($valores['nome']);

			if ($valores['nova_senha'] != '') {
				$comp = ",senha = :senha";
				$dados['senha'] = campo_sql(sha1($valores['nova_senha']));
			}
			
			$sql = "update usuario set
						login = :login,
						nome = :nome".$comp."
							where id = :id";
						
			$conexao -> execute($sql, $dados);
			return 1;
		}
	}
	
	function menu() {
		require_once('database.class.php');
		$conexao = new database();
		
		$sql = "select menu.menu, group_concat(pagina.pagina order by pagina.ordem) as paginas,
				group_concat(pagina.endereco order by pagina.ordem) as enderecos
					from menu
						inner join pagina on pagina.id_menu = menu.id
						group by menu.id
							order by menu.ordem, pagina.ordem";
		return $conexao -> query($sql);
	}
	
}