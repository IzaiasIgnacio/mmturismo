<?php
class cidade {
	
	function listar($estado) {
		require_once('database.class.php');
		$conexao = new database();
		
		$sql = "select id, cidade
					from cidade
						where id_estado = :estado
							order by cidade";
		return $conexao -> query($sql, ['estado' => $estado]);
	}
	
}