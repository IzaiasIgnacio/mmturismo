<?php
switch($_POST[acao]) {
	//excluir cliente
	case 'excluir_cliente':
		require_once('../classes/cliente.class.php');
		$cliente = new cliente();
		
		$cliente -> excluir($_POST['id']);
	break;
	//buscar dados do cliente
	case 'buscar_dados':
		require_once('../classes/cliente.class.php');
		$cliente = new cliente();
	
		$dados = $cliente -> buscar_dados($_POST['cliente']);
	
		$d = mysql_fetch_array($dados);
		
		//retornar como json
		$d = array_map(utf8_encode, $d);
		echo json_encode($d);
	break;
	//historico de viagens de um cliente
	case 'buscar_historico':
		require_once('../classes/cliente.class.php');
		$cliente = new cliente();
		require_once('../controles/funcoes.php');
		
		$cliente -> buscar_historico($_POST);
		if (mysql_num_rows($cliente->lista) > 0) {
			while ($l = mysql_fetch_array($cliente->lista)) {
				$l = array_map(utf8_encode, $l);
				//adicionar ao array
				$historico[] = $l;
			}
			$historico[]['total'] = mysql_result($cliente->total,0,'total');
			echo json_encode($historico);
		}
		else {
			echo 0;
		}
	break;
	//autocomplete clientes
	case 'buscar_clientes':
		require_once('../classes/cliente.class.php');
		$cliente = new cliente();
		
		$lista_cliente = $cliente -> buscar($_POST['nome']);
		
		if (mysql_num_rows($lista_cliente) > 0) {
			while ($l = mysql_fetch_array($lista_cliente)) {
				$l = array_map(utf8_encode, $l);
				//adicionar ao array
				$clientes[] = $l;
			}
		}
		else {
			//nenhum cliente encontrado
			$clientes[] = array('id' => 0,'cliente' => "NENHUM CLIENTE ENCONTRADO");
		}
		//retornar como json
		echo json_encode($clientes);
	break;
	//autocomplete clientes para viagem
	case 'buscar_clientes_viagem':
		require_once('../classes/cliente.class.php');
		$cliente = new cliente();
		
		$lista_cliente = $cliente -> buscar_passageiros($_POST['nome']);
		
		if (mysql_num_rows($lista_cliente) > 0) {
			while ($l = mysql_fetch_array($lista_cliente)) {
				$l = array_map(utf8_encode, $l);
				//adicionar ao array
				$clientes[] = $l;
			}
		}
		else {
			//nenhum cliente encontrado
			$clientes[] = array('id' => 0,'cliente' => "NENHUM CLIENTE ENCONTRADO");
		}
		//retornar como json
		echo json_encode($clientes);
	break;
	//exibir telefones de um cliente (lightbox)
	case 'exibir_telefones':
		require_once('../classes/cliente.class.php');
		$cliente = new cliente();
		
		$lista_telefones = $cliente -> buscar_telefones($_POST['id_cliente']);
		
		echo "<table class='lista'>";
		echo "	<thead>";
		echo "		<tr>";
		echo "			<td>N&uacute;mero</td>";
		echo "			<td>Tipo</td>";
		echo "		</tr>";
		echo "	</thead>";
		echo "	<tbody>";
		if (mysql_num_rows($lista_telefones) > 0) {
			while ($l = mysql_fetch_array($lista_telefones)) {
				echo "	<tr class='linha'>";
				echo "		<td class='td_esquerda'>".$l['telefone']."</td>";
				echo "		<td>".utf8_encode($l['tipo_telefone'])."</td>";
				echo "	</tr>";
			}
		}
		else {
			echo "<tr class='linha'>";
			echo "	<td class='vazio' colspan='2'>Nenhum telefone informado</td>";
			echo "</tr>";
		}
		echo "	</tbody>";
		echo "</table>";
	break;
	//exibir telefones de um cliente (consulta)
	case 'exibir_telefones_consulta':
		require_once('../classes/cliente.class.php');
		$cliente = new cliente();
	
		$lista_telefones = $cliente -> buscar_telefones($_POST['id_cliente']);
	
		if (mysql_num_rows($lista_telefones) > 0) {
			echo "<tr class='linha' style='display:none'>";
			echo "	<td colspan='4' class='vazio'>Nenhum telefone informado</td>";
			echo "</tr>";
			$c = 0;
			while ($l = mysql_fetch_array($lista_telefones)) {
				echo "	<tr class='linha'>";
				echo "		<td>".$l['telefone']."</td>";
				echo "		<td>".utf8_encode($l['tipo_telefone'])."</td>";
				$check = ($l['principal'] == 1) ? 'checked' : '';
				echo "		<td><input type='radio' name='telefone_principal' ".$check." value='".$c."'></td>";
				echo "		<td><div class='btn_remover' name='telefone_".$c."'></div></td>";
				echo "		<input type='hidden' name='valor_tipo' value='".$l['id_tipo']."'>";
				echo "		<input type='hidden' name='telefone_".$c."' id='telefone_".$c."' value='".$l['id']."'>";
				echo "	</tr>";
				$c++;
			}		
		}
		else {
			echo "<tr class='linha'>";
			echo "	<td class='vazio' colspan='4'>Nenhum telefone informado</td>";
			echo "</tr>";
		}
	break;
	//buscar titular de um cliente 
	case 'buscar_titular':
		require_once('../classes/cliente.class.php');
		$cliente = new cliente();
		
		$titular = $cliente -> buscar_titular($_POST['cliente']);
		if (mysql_num_rows($titular) > 0) {
			$l = mysql_fetch_array($titular);
			echo json_encode(array_map(utf8_encode, $l));
		}
		else {
			echo 0;
		}
	break;
	//buscar dependetes de um cliente 
	case 'buscar_dependentes':
		require_once('../classes/cliente.class.php');
		$cliente = new cliente();
		
		$dependentes = $cliente -> buscar_dependentes($_POST['cliente']);
		if (mysql_num_rows($dependentes) > 0) {
			while ($l = mysql_fetch_array($dependentes)) {
				$l = array_map(utf8_encode, $l);
				//adicionar ao array
				$clientes[] = $l;
			}
			echo json_encode($clientes);
		}
		else {
			echo 0;
		}
	break;
}
?>