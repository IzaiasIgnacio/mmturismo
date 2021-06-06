<?php
switch($_POST['acao']) {
	//buscar dados da empresa
	case 'buscar_dados':
		require_once('../classes/empresa.class.php');
		$empresa = new empresa();
	
		$dados = $empresa -> buscar_dados($_POST['empresa']);
	
		//retornar como json
		echo json_encode($dados);
	break;
	//historico de reservas de uma empresa
	case 'buscar_historico':
		require_once('../classes/empresa.class.php');
		$empresa = new empresa();
		require_once('../controles/funcoes.php');
	
		$empresa -> buscar_historico($_POST);
		if (count($empresa->lista) > 0) {
			foreach ($empresa->lista as $l) {
				//adicionar ao array
				$historico[] = $l;
			}
			$historico[]['total'] = mysql_result($empresa->total,0,'total');
			echo json_encode($historico);
		}
		else {
			echo 0;
		}
	break;
	//autocomplete empresa
	case 'buscar_empresa':
		require_once('../classes/empresa.class.php');
		$empresa = new empresa();
		
		$lista_empresa = $empresa -> buscar($_POST['nome']);
		
		if (count($lista_empresa) > 0) {
			foreach ($lista_empresa as $l) {
				//adicionar ao array
				$hoteis[] = $l;
			}
		}
		else {
			//nenhuma empresa encontrado
			$hoteis[] = array('id' => 0,'empresa' => "NENHUMA EMPRESA ENCONTRADA");
		}
		//retornar como json
		echo json_encode($hoteis);
	break;
	//exibir telefones de uma empresa (consulta)
	case 'exibir_telefones_consulta':
		require_once('../classes/empresa.class.php');
		$empresa = new empresa();
	
		$lista_telefones = $empresa -> buscar_telefones($_POST['id_empresa']);
	
		if (count($lista_telefones) > 0) {
			echo "<tr class='linha' style='display:none'>";
			echo "	<td colspan='2' class='vazio'>Nenhum telefone informado</td>";
			echo "</tr>";
			$c = 0;
			foreach ($lista_telefones as $l) {
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
	//exibir bancos de uma empresa (consulta)
	case 'exibir_bancos_consulta':
		require_once('../classes/empresa.class.php');
		$empresa = new empresa();
	
		$lista_bancos = $empresa -> buscar_bancos($_POST['id_empresa']);
	
		if (count($lista_bancos) > 0) {
			echo "<tr class='linha' style='display:none'>";
			echo "	<td colspan='6' class='vazio'>Nenhum banco informado</td>";
			echo "</tr>";
			$c = 0;
			foreach ($lista_bancos as $l) {
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
	case 'listar_empresas':
		require_once('../classes/empresa.class.php');
		$empresa = new empresa();
		
		$lista_empresa = $empresa -> listar($_POST['tipo']);
		
		if (count($lista_empresa) > 0) {
			$html = "<option value=''>Selecione a empresa</option>";
			foreach ($lista_empresa as $l) {
				$html .= "<option value='".$l['id']."'>".$l['empresa']."</option>";
			}
		}
		else {
			$html = "<option value=''>Nenhuma empresa encontrada</option>";
		}
		echo $html;
	break;
}
?>