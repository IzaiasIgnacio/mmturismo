<?php
class tipo_telefone {
	
	function listar() {
		require_once('conexao.class.php');
		$conexao = new conexao();
		
		$sql = "select *
					from tipo_telefone";
		$r = $conexao -> query($sql);
		
		return $r;
	}
	
}
?>