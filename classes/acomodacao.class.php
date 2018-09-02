<?php
class acomodacao {
	
	function listar() {
		require_once('conexao.class.php');
		$conexao = new conexao();
		
		$sql = "select id, acomodacao
					from acomodacao";
		$r = $conexao -> query($sql);
		
		return $r;
	}
	
}
?>