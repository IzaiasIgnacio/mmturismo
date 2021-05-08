<?php
class tipo_transporte {
	
	function listar() {
		require_once('conexao.class.php');
		$conexao = new conexao();
		
		$sql = "select id, tipo_transporte
					from tipo_transporte
						-- order by tipo_transporte";
		$r = $conexao -> query($sql);
		
		return $r;
	}
	
}
?>