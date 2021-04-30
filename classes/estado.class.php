<?php
class estado {
	
	function listar() {
		require_once('database.class.php');
		$conexao = new database();
		
		$sql = "select id, sigla
					from estado
						order by sigla";
		return $conexao -> query($sql);
	}
	
}
?>