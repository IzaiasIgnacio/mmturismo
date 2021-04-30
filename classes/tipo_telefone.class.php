<?php
class tipo_telefone {
	
	function listar() {
		require_once('database.class.php');
		$conexao = new database();
		
		$sql = "select *
					from tipo_telefone";
		return $conexao -> query($sql);
	}
	
}
?>