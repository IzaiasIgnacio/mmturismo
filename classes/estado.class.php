<?php
class estado {
	
	function listar() {
		require_once('conexao.class.php');
		$conexao = new conexao();
		
		$sql = "select id, sigla
					from estado
						-- order by sigla";
		$r = $conexao -> query($sql);
		
		return $r;
	}
	
}
?>