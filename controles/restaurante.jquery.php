<?php
switch($_POST[acao]) {
	//listar restaurantes de uma cidade
	case 'listar_restaurantes':
		require_once('../classes/restaurante.class.php');
		$restaurante = new restaurante();
	
		$lista_restaurante = $restaurante -> listar($_POST['cidade']);
		
		if (mysql_num_rows($lista_restaurante) > 0) {
			$html = "<option value=''>Selecione o restaurante</option>";
			while ($l = mysql_fetch_array($lista_restaurante)) {
				$html .= "<option value='".$l['id']."'>".utf8_encode($l['restaurante'])."</option>";
			}
		}
		else {
			$html = "<option value=''>nenhum restaurante encontrado</option>";
		}
		echo $html;
	break;
	//historico de reservas de um restaurante
	case 'buscar_historico':
		require_once('../classes/restaurante.class.php');
		$restaurante = new restaurante();
		require_once('../controles/funcoes.php');
	
		$restaurante -> buscar_historico($_POST);
		if (mysql_num_rows($restaurante->lista) > 0) {
			while ($l = mysql_fetch_array($restaurante->lista)) {
				$l = array_map(utf8_encode, $l);
				//adicionar ao array
				$historico[] = $l;
			}
			$historico[]['total'] = mysql_result($restaurante->total,0,'total');
			echo json_encode($historico);
		}
		else {
			echo 0;
		}
	break;
	//buscar dados do restaurante
	case 'buscar_dados':
		require_once('../classes/restaurante.class.php');
		$restaurante = new restaurante();
	
		$dados = $restaurante -> buscar_dados($_POST['restaurante']);
	
		$d = mysql_fetch_array($dados);
		
		//retornar como json
		$d = array_map(utf8_encode, $d);
		echo json_encode($d);
	break;
	//autocomplete restaurante
	case 'buscar_restaurante':
		require_once('../classes/restaurante.class.php');
		$restaurante = new restaurante();
		
		$lista_restaurante = $restaurante -> buscar($_POST['nome']);
		
		if (mysql_num_rows($lista_restaurante) > 0) {
			while($l = mysql_fetch_array($lista_restaurante)) {
				$l = array_map(utf8_encode, $l);
				//adicionar ao array
				$hoteis[] = $l;
			}
		}
		else {
			//nenhum restaurante encontrado
			$hoteis[] = array('id' => 0,'restaurante' => "NENHUM RESTAURANTE ENCONTRADO");
		}
		//retornar como json
		echo json_encode($hoteis);
	break;
	//exibir telefones de um restaurante (consulta)
	case 'exibir_telefones_consulta':
		require_once('../classes/restaurante.class.php');
		$restaurante = new restaurante();
	
		$lista_telefones = $restaurante -> buscar_telefones($_POST['id_restaurante']);
	
		if (mysql_num_rows($lista_telefones) > 0) {
			echo "<tr class='linha' style='display:none'>";
			echo "	<td colspan='2' class='vazio'>Nenhum telefone informado</td>";
			echo "</tr>";
			$c = 0;
			while ($l = mysql_fetch_array($lista_telefones)) {
				echo "	<tr class='linha'>";
				echo "		<td>".$l['telefone']."</td>";
				echo "		<td><div class='btn_remover' name='telefone_".$c."'></div></td>";
				echo "		<input type='hidden' name='telefone_".$c."' id='telefone_".$c."' value='".$l['id']."'>";
				echo "	</tr>";
				$c++;
			}
		}
		else {
			echo "<tr class='linha'>";
			echo "	<td class='vazio' colspan='2'>Nenhum telefone informado</td>";
			echo "</tr>";
		}
	break;
	//exibir bancos de um restaurante (consulta)
	case 'exibir_bancos_consulta':
		require_once('../classes/restaurante.class.php');
		$restaurante = new restaurante();
	
		$lista_bancos = $restaurante -> buscar_bancos($_POST['id_restaurante']);
	
		if (mysql_num_rows($lista_bancos) > 0) {
			echo "<tr class='linha' style='display:none'>";
			echo "	<td colspan='6' class='vazio'>Nenhum banco informado</td>";
			echo "</tr>";
			$c = 0;
			while ($l = mysql_fetch_array($lista_bancos)) {
				echo "	<tr class='linha'>";
				echo "		<td>".$l['banco']."</td>";
				echo "		<td>".$l['agencia']."</td>";
				echo "		<td>".$l['conta']."</td>";
				echo "		<td>".$l['titular']."</td>";
				echo "		<td>".$l['cpf_cnpj']."</td>";
				echo "		<td><div class='btn_remover' name='banco_".$c."'></div></td>";
				echo "		<input type='hidden' name='banco_".$c."' id='banco_".$c."' value='".$l['id']."'>";
				echo "	</tr>";
				$c++;
			}
		}
		else {
			echo "<tr class='linha'>";
			echo "	<td class='vazio' colspan='6'>Nenhum banco informado</td>";
			echo "</tr>";
		}
	break;
}
?>