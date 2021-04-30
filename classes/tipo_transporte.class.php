<?php
class tipo_transporte {
	
	function listar() {
		require_once('database.class.php');
		$conexao = new database();
		
		$sql = "select id, tipo_transporte
					from tipo_transporte
						order by tipo_transporte";
		return $conexao -> query($sql);
	}
	
}
?>