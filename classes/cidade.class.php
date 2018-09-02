<?php
class cidade {
	
	function listar($estado) {
		require_once('conexao.class.php');
		$conexao = new conexao();
		
		$sql = "select id, cidade
					from cidade
						where id_estado = ".$estado."
							order by cidade";
		$r = $conexao -> query($sql);
		
		return $r;
	}
	
}
?>