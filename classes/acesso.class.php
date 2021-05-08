<?php
class acesso {
	
	function login($valores) {
		require_once('conexao.class.php');
		$conexao = new conexao();
		require_once('controles/funcoes.php');
		
		$sql = "select *
					from usuario
						where login = '".campo_sql($valores['login'])."'
						and senha = '".campo_sql(sha1($valores['senha']))."'";
		$r = $conexao -> query($sql);
		
		return $r;
	}
	
	function alterar_dados($valores) {
		require_once('conexao.class.php');
		$conexao = new conexao();
		require_once('controles/funcoes.php');
		
		$sql = "select *
					from usuario
						where id = ".$_SESSION['id_usuario']."
						and senha = '".campo_sql(sha1($valores['senha_atual']))."'";
		$r = $conexao -> query($sql);
		if (mysql_num_rows($r) == 0) {
			return 0;
		}
		else {
			$comp = '';
			if ($valores['nova_senha'] != '') {
				$comp = ",senha = '".campo_sql(sha1($valores['nova_senha']))."'";
			}
			$sql = "update usuario set
						login = '".campo_sql($valores['login'])."',
						nome = '".campo_sql($valores['nome'])."'".$comp;
						
			$conexao -> query($sql);
			return 1;
		}
	}
	
	function menu() {
		require_once('conexao.class.php');
		$conexao = new conexao();
		
		$sql = "select menu.menu, group_concat(pagina.pagina order by pagina.ordem) as paginas,
				group_concat(pagina.endereco order by pagina.ordem) as enderecos
					from menu
						inner join pagina on pagina.id_menu = menu.id
						group by menu.id
							-- order by menu.ordem, pagina.ordem";
		$r = $conexao -> query($sql);
		
		return $r;
	}
	
}
?>