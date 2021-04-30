<?php
class acomodacao {
	
	function listar() {
		require_once('database.class.php');
		$conexao = new database();
		
		$sql = "select id, acomodacao
					from acomodacao";
		return $conexao -> query($sql);
	}
	
}