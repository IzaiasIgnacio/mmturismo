<?php
class cliente_situacao {
	
	function listar() {
		require_once('conexao.class.php');
		$conexao = new conexao();
		
		$sql = "select id, situacao
					from cliente_situacao";
		$r = $conexao -> query($sql);
		
		return $r;
	}
	
}
?>