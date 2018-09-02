<?php
switch($_POST[acao]) {
	//listar hoteis de uma cidade
	case 'listar_hoteis':
		require_once('../classes/hotel.class.php');
		$hotel = new hotel();
	
		$lista_hotel = $hotel -> listar($_POST['cidade']);
	
		if (mysql_num_rows($lista_hotel) > 0) {
			$html = "<option value=''>Selecione o hotel</option>";
			while ($l = mysql_fetch_array($lista_hotel)) {
				$html .= "<option value='".$l['id']."'>".utf8_encode($l['hotel'])."</option>";
			}
		}
		else {
			$html = "<option value=''>nenhum hotel encontrado</option>";
		}
		echo $html;
	break;
	//historico de reservas de um hotel
	case 'buscar_historico':
		require_once('../classes/hotel.class.php');
		$hotel = new hotel();
		require_once('../controles/funcoes.php');
	
		$hotel -> buscar_historico($_POST);
		if (mysql_num_rows($hotel->lista) > 0) {
			while ($l = mysql_fetch_array($hotel->lista)) {
				$l = array_map(utf8_encode, $l);
				//adicionar ao array
				$historico[] = $l;
			}
			$historico[]['total'] = mysql_result($hotel->total,0,'total');
			echo json_encode($historico);
		}
		else {
			echo 0;
		}
	break;
	//buscar dados do hotel
	case 'buscar_dados':
		require_once('../classes/hotel.class.php');
		$hotel = new hotel();
	
		$dados = $hotel -> buscar_dados($_POST['hotel']);
	
		$d = mysql_fetch_array($dados);
		
		//retornar como json
		$d = array_map(utf8_encode, $d);
		echo json_encode($d);
	break;
	//autocomplete hotel
	case 'buscar_hotel':
		require_once('../classes/hotel.class.php');
		$hotel = new hotel();
		
		$lista_hotel = $hotel -> buscar($_POST['nome']);
		
		if (mysql_num_rows($lista_hotel) > 0) {
			while($l = mysql_fetch_array($lista_hotel)) {
				$l = array_map(utf8_encode, $l);
				//adicionar ao array
				$hoteis[] = $l;
			}
		}
		else {
			//nenhum hotel encontrado
			$hoteis[] = array('id' => 0,'hotel' => "NENHUM HOTEL ENCONTRADO");
		}
		
		//retornar como json
		echo json_encode($hoteis);
	break;
	//exibir telefones de um hotel (consulta)
	case 'exibir_telefones_consulta':
		require_once('../classes/hotel.class.php');
		$hotel = new hotel();
	
		$lista_telefones = $hotel -> buscar_telefones($_POST['id_hotel']);
	
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
	//exibir bancos de um hotel (consulta)
	case 'exibir_bancos_consulta':
		require_once('../classes/hotel.class.php');
		$hotel = new hotel();
	
		$lista_bancos = $hotel -> buscar_bancos($_POST['id_hotel']);
	
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