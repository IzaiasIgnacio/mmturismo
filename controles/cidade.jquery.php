<?php
switch($_POST['acao']) {
	case 'listar_cidades':
		require_once('../classes/cidade.class.php');
		$cidade = new cidade();
		
		$lista_cidade = $cidade -> listar($_POST['estado']);
		
		$html = "<option value=''>Selecione a cidade</option>";
		foreach ($lista_cidade as $l) {
			$html .= "<option value='".$l['id']."'>".$l['cidade']."</option>";
		}
		echo $html;
	break;
}
?>