<?php
class cliente_situacao {
	
	function listar() {
		require_once('database.class.php');
		$conexao = new database();
		
		$sql = "select id, situacao
					from cliente_situacao";
		return $conexao -> query($sql);
	}
	
}