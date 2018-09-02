<?php
switch($_POST[acao]) {
	case 'listar_cidades':
		require_once('../classes/cidade.class.php');
		$cidade = new cidade();
		
		$lista_cidade = $cidade -> listar($_POST['estado']);
		
		$html = "<option value=''>Selecione a cidade</option>";
		while ($l = mysql_fetch_array($lista_cidade)) {
			$html .= "<option value='".$l['id']."'>".$l['cidade']."</option>";
		}
		echo $html;
	break;
}
?>