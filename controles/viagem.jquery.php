<?php
if (get_magic_quotes_gpc()) {
    $process = array(&$_GET, &$_POST, &$_COOKIE, &$_REQUEST);
    while (list($key, $val) = each($process)) {
        foreach ($val as $k => $v) {
            unset($process[$key][$k]);
            if (is_array($v)) {
                $process[$key][stripslashes($k)] = $v;
                $process[] = &$process[$key][stripslashes($k)];
            } else {
                $process[$key][stripslashes($k)] = stripslashes($v);
            }
        }
    }
    unset($process);
}

switch($_POST['acao']) {
	//excluir viagem
	case 'excluir_viagem':
		require_once('../classes/viagem.class.php');
		$viagem = new viagem();
		
		$viagem -> excluir($_POST['id']);
	break;
	//buscar dados da viagem
	case 'buscar_dados':
		require_once('../classes/viagem.class.php');
		$viagem = new viagem();
	
		$dados = $viagem -> buscar_dados($_POST['viagem']);
	
		//retornar como json
		echo json_encode($dados);
	break;
	//autocomplete viagens
	case 'buscar_viagens':
		require_once('../classes/viagem.class.php');
		$viagem = new viagem();
	
		$lista_viagem = $viagem -> buscar($_POST['viagem']);
	
		if (count($lista_viagem) > 0) {
			foreach ($lista_viagem as $l) {
				//adicionar ao array
				$viagens[] = $l;
			}
		}
		else {
			//nenhuma viagem encontrado
			$viagens[] = array('id' => 0,'viagem' => "NENHUMA VIAGEM ENCONTRADA",'data_saida' => '');
		}
		//retornar como json
		echo json_encode($viagens);
	break;
	//exibir destinos de uma viagem
	case 'exibir_destinos':
		require_once('../classes/viagem.class.php');
		$viagem = new viagem();
	
		$lista_destinos = $viagem -> buscar_destinos($_POST['id_viagem']);
	
		if (count($lista_destinos) > 0) {
			echo "<tr class='linha' style='display:none'>";
			echo "	<td colspan='3' class='vazio'>Nenhum destino informado</td>";
			echo "</tr>";
			$c = 0;
			foreach ($lista_destinos as $l) {
				echo "	<tr class='linha'>";
				echo "		<td>".$l['sigla']."</td>";
				echo "		<td>".$l['cidade']."</td>";
				echo "		<td><div class='btn_remover' name='destino_".$c."'></div></td>";
				echo "		<input type='hidden' name='valor_cidade_destino' value='".$l['id_cidade']."'>";
				echo "		<input type='hidden' name='destino_".$c."' id='destino_".$c."' value='".$l['id']."'>";
				echo "	</tr>";
				$c++;
			}
		}
		else {
			echo "<tr class='linha'>";
			echo "	<td class='vazio' colspan='3'>Nenhum destino informado</td>";
			echo "</tr>";
		}
	break;
	//exibir transportes de uma viagem
	case 'exibir_transportes':
		require_once('../classes/viagem.class.php');
		$viagem = new viagem();
	
		$lista_transportes = $viagem -> buscar_transportes($_POST['id_viagem']);
		
		if (count($lista_transportes) > 0) {
			echo "<tr style='display:none'>";
			echo "	<td colspan='6' class='vazio'>Nenhum transporte informado</td>";
			echo "</tr>";
			$c = 0;
			foreach ($lista_transportes as $l) {
				$lista_sinais = $viagem -> buscar_sinais('transporte', $l['id']);
				echo  "<tr class='linha'>";
				echo  "		<td>".$l['tipo_transporte']."</td>";
				echo  "		<td>".$l['empresa']."</td>";
				echo  "		<td><input type='text' value='".$l['quantidade']."' name='quantidade_transporte_".$c."' id='quantidade_transporte_".$c."' value='1' class='quantidade'></td>";
				echo  "		<td><input type='text' value='".$l['contato']."' name='contato_transporte_".$c."' id='contato_transporte_".$c."' class='contato'></td>";
				echo  "		<td><input type='text' value='".$l['valor']."' name='valor_transporte_".$c."' id='valor_transporte_".$c."' class='valor'></td>";
				echo  "		<td><div class='btn_remover' name='transporte_".$c."'></div></td>";
				echo "		<input type='hidden' name='valor_transporte' value='".$l['id_empresa_tipo_transporte']."'>";
				echo "		<input type='hidden' name='transporte_".$c."' id='transporte_".$c."' value='".$l['id']."'>";
				echo  "</tr>";
				echo  "<tr>";
				echo  "		<td colspan='6'>";
				echo  "			<div class='sinais' id='sinais_transportes_".$c."'><h3>Sinais</h3>";
				echo  "				<div>";
				echo  "					<table class='lista sinais_transportes' id='tabela_sinais_transportes_".$c."'>";
				echo  "						<thead>";
				echo  "							<tr>";
				echo  "								<td>Data</td>";
				echo  "								<td>Valor</td>";
				echo  "								<td>Remover</td>";
				echo  "							</tr>";
				echo  "						</thead>";
				echo  "						<tbody>";
				if (count($lista_sinais) > 0) {
					echo  "						<tr class='linha' style='display:none'>";
					echo  "							<td colspan='3' class='vazio'>Nenhum sinal informado</td>";
					echo  "						</tr>";
					$i = 0;
					foreach ($lista_sinais as $l) {
						echo  "					<tr class='linha'>";
						echo  "						<td>".$ls['data']."</td>";
						echo  "						<td>".$ls['valor']."</td>";
						echo  "						<td><div class='btn_remover_sinal' name='sinais_transportes_".$c."_".$i."'></div></td>";
						echo "						<input type='hidden' class='sinal_transporte' name='sinal_transporte_".$i."' value='".$ls['id']."'>";
						echo  "					</tr>";
						$i++;
					}
				}
				else {
					echo  "						<tr class='linha'>";
					echo  "							<td colspan='3' class='vazio'>Nenhum sinal informado</td>";
					echo  "						</tr>";
				}
				echo  "						</tbody>";
				echo  "					</table>";
				echo  "					<span class='btn_adicionar' name='add_sinal' id='add_sinal_transportes_".$c."'>Adicionar Sinal</span>";
				echo  "				</div>";
				echo  "			</div>";
				echo  "		</td>";
				echo  "</tr>";
				$c++;
			}
		}
		else {
			echo "<tr class='linha'>";
			echo "	<td colspan='6' class='vazio'>Nenhum transporte informado</td>";
			echo "</tr>";
		}
	break;
	//exibir restaurantes de uma viagem
	case 'exibir_restaurantes':
		require_once('../classes/viagem.class.php');
		$viagem = new viagem();
	
		$lista_restaurantes = $viagem -> buscar_restaurantes($_POST['id_viagem']);
	
		if (count($lista_restaurantes) > 0) {
			echo "<tr class='linha' style='display:none'>";
			echo "	<td colspan='8' class='vazio'>Nenhum restaurante informado</td>";
			echo "</tr>";
			$c = 0;
			foreach ($lista_restaurantes as $l) {
				$lista_sinais = $viagem -> buscar_sinais('restaurante', $l['id']);
				echo "<tr class='linha'>";
				echo "		<td>".$l['sigla']."</td>";
				echo "		<td>".$l['cidade']."</td>";
				echo "		<td>".$l['restaurante']."</td>";
				echo "		<td><input type='text' value='".$l['data']."' name='data_restaurante_".$c."' id='data_restaurante_".$c."' size='8' class='data'></td>";
				echo "		<td><input type='text' value='".$l['hora']."' name='hora_restaurante_".$c."' id='hora_restaurante_".$c."' size='4' class='hora'></td>";
				echo "		<td><input type='text' value='".$l['contato']."' name='contato_restaurante_".$c."' id='contato_restaurante_".$c."' class='contato'></td>";
				echo "		<td><input type='text' value='".$l['valor']."' name='valor_restaurante_".$c."' id='valor_restaurante_".$c."' class='valor'></td>";
				echo "		<td><div class='btn_remover' name='restaurante_".$c."'></div></td>";
				echo "		<input type='hidden' name='valor_restaurante' value='".$l['id_restaurante']."'>";
				echo "		<input type='hidden' name='restaurante_".$c."' id='restaurante_".$c."' value='".$l['id']."'>";
				echo  "</tr>";
				echo  "<tr>";
				echo  "		<td colspan='8'>";
				echo  "			<div class='sinais' id='sinais_restaurantes_".$c."'><h3>Sinais</h3>";
				echo  "				<div>";
				echo  "					<table class='lista sinais_restaurantes' id='tabela_sinais_restaurantes_".$c."'>";
				echo  "						<thead>";
				echo  "							<tr>";
				echo  "								<td>Data</td>";
				echo  "								<td>Valor</td>";
				echo  "								<td>Remover</td>";
				echo  "							</tr>";
				echo  "						</thead>";
				echo  "						<tbody>";
				if (count($lista_sinais) > 0) {
					echo  "						<tr class='linha' style='display:none'>";
					echo  "							<td colspan='3' class='vazio'>Nenhum sinal informado</td>";
					echo  "						</tr>";
					$i = 0;
					foreach ($lista_sinais as $l) {
						echo  "					<tr class='linha'>";
						echo  "						<td>".$ls['data']."</td>";
						echo  "						<td>".$ls['valor']."</td>";
						echo  "						<td><div class='btn_remover_sinal' name='sinais_restaurantes_".$c."'></div></td>";
						echo "						<input type='hidden' class='sinal_restaurante' name='sinal_restaurante_".$i."' value='".$ls['id']."'>";
						echo  "					</tr>";
						$i++;
					}
				}
				else {

					echo  "						<tr class='linha'>";
					echo  "							<td colspan='3' class='vazio'>Nenhum sinal informado</td>";
					echo  "						</tr>";
				}
				echo  "						</tbody>";
				echo  "					</table>";
				echo  "					<span class='btn_adicionar' name='add_sinal' id='add_sinal_restaurantes_".$c."'>Adicionar Sinal</span>";
				echo  "				</div>";
				echo  "			</div>";
				echo  "		</td>";
				echo  "</tr>";
				$c++;
			}
		}
		else {
			echo "<tr class='linha'>";
			echo "	<td colspan='8' class='vazio'>Nenhum restaurante informado</td>";
			echo "</tr>";
		}
	break;
	//exibir hoteis de uma viagem
	case 'exibir_hoteis':
		require_once('../classes/viagem.class.php');
		$viagem = new viagem();
	
		$lista_hoteis = $viagem -> buscar_hoteis($_POST['id_viagem']);
	
		if (count($lista_hoteis) > 0) {
			echo "<tr class='linha' style='display:none'>";
			echo "	<td colspan='9' class='vazio'>Nenhum hotel informado</td>";
			echo "</tr>";
			$c = 0;
			foreach ($lista_hoteis as $l) {
				$lista_sinais = $viagem -> buscar_sinais('hotel', $l['id']);
				echo "<tr class='linha'>";
				echo "		<td>".$l['sigla']."</td>";
				echo "		<td>".$l['cidade']."</td>";
				echo "		<td>".$l['hotel']."</td>";
				echo "		<td><input type='text' value='".$l['data_chegada']."' name='chegada_hotel_".$c."' size='8' id='chegada_hotel_".$c."' class='data'></td>";
				echo "		<td><input type='text' value='".$l['hora']."' name='hora_hotel_".$c."' size='4' id='hora_hotel_".$c."' class='hora'></td>";
				echo "		<td><input type='text' value='".$l['data_saida']."' name='saida_hotel_".$c."' size='8' id='saida_hotel_".$c."' class='data'></td>";
				echo "		<td><input type='text' value='".$l['contato']."' name='contato_hotel_".$c."' id='contato_hotel_".$c."' class='contato'></td>";
				echo "		<td><input type='text' value='".$l['valor']."' name='valor_hotel_".$c."' id='valor_hotel_".$c."' class='valor'></td>";
				echo "		<td><div class='btn_remover' name='hotel_".$c."'></div></td>";
				echo "		<input type='hidden' name='valor_hotel' value='".$l['id_hotel']."'>";
				echo "		<input type='hidden' name='hotel_".$c."' id='hotel_".$c."' value='".$l['id']."'>";
				echo  "</tr>";
				echo  "<tr>";
				echo  "		<td colspan='9'>";
				echo  "			<div class='sinais' id='sinais_hoteis_".$c."'><h3>Sinais</h3>";
				echo  "				<div>";
				echo  "					<table class='lista sinais_hoteis' id='tabela_sinais_hoteis_".$c."'>";
				echo  "						<thead>";
				echo  "							<tr>";
				echo  "								<td>Data</td>";
				echo  "								<td>Valor</td>";
				echo  "								<td>Remover</td>";
				echo  "							</tr>";
				echo  "						</thead>";
				echo  "						<tbody>";
				if (count($lista_sinais) > 0) {
					echo  "						<tr class='linha' style='display:none'>";
					echo  "							<td colspan='3' class='vazio'>Nenhum sinal informado</td>";
					echo  "						</tr>";
					$i = 0;
					foreach ($lista_sinais as $l) {
						echo  "					<tr class='linha'>";
						echo  "						<td>".$ls['data']."</td>";
						echo  "						<td>".$ls['valor']."</td>";
						echo  "						<td><div class='btn_remover_sinal' name='sinais_hoteis_".$c."'></div></td>";
						echo "						<input type='hidden' class='sinal_hotel' name='sinal_hotel_".$i."' value='".$ls['id']."'>";
						echo  "					</tr>";
						$i++;
					}
				}
				else {
					echo  "						<tr class='linha'>";
					echo  "							<td colspan='3' class='vazio'>Nenhum sinal informado</td>";
					echo  "						</tr>";
				}
				echo  "						</tbody>";
				echo  "					</table>";
				echo  "					<span class='btn_adicionar' name='add_sinal' id='add_sinal_hoteis_".$c."'>Adicionar Sinal</span>";
				echo  "				</div>";
				echo  "			</div>";
				echo  "		</td>";
				echo  "</tr>";
				$c++;
			}
		}
		else {
			echo "<tr class='linha'>";
			echo "	<td colspan='9' class='vazio'>Nenhum hotel informado</td>";
			echo "</tr>";
		}
	break;
	case 'exibir_clientes':
		require_once('../classes/viagem.class.php');
		$viagem = new viagem();
	
		$lista_clientes = $viagem -> buscar_clientes($_POST['id_viagem']);
	
		if (count($lista_clientes) > 0) {
			echo "<tr class='linha' style='display:none'>";
			echo "	<td colspan='5' class='vazio'>Nenhum cliente informado</td>";
			echo "</tr>";
			echo "<tr class='linha_busca' style='display:none'>";
			echo "	<td colspan='5' class='td_esquerda td_busca'>";
			echo "		<input type='text' name='buscar_cliente' id='buscar_cliente' size='91' maxlength='80'>";
			echo "	</td>";
			echo "</tr>";
			$c = 0;
			foreach ($lista_clientes as $l) {
				echo "<tr class='linha_cliente'>";
				$html_telefones = ($l['telefone'] != '') ? " - ".$l['telefone']."</label> <div class='btn_info_viagem' name='info_".$l['id_cliente']."'></div>" : '</label>';
				echo "	<td colspan='5'><label>".$l['cliente'].$html_telefones."</td>";
				echo "	<input type='hidden' class='valor_cliente' name='valor_cliente_".$c."' value='".$l['id_cliente']."'>";
				echo "	<input type='hidden' id='viagem_cliente_".$c."' name='viagem_cliente_".$c."' value='".$l['id']."'>";
				echo "</tr>";
				echo "<tr class='linha'>";
				echo "	<td class='td_esquerda'>";
				echo "		<select name='transporte_".$l['id']."' id='transporte_".$l['id']."' class='transporte_cliente'>";
				echo  			$_POST['transportes'];
				echo "		<select>";
				echo "		<input type='text' value='".$l['numero_transporte']."' size='1' maxlength='3' name='numero_transporte_".$l['id']."' id='numero_transporte_".$l['id']."' class='numero_transporte'>";
				echo "	</td>";
				echo "	<td>";
				echo "		<input type='text' value='".$l['poltrona']."' size='1' maxlength='3' name='poltrona_".$l['id']."' id='poltrona_".$l['id']."' class='poltrona'>";
				echo "	</td>";
				echo "	<td>";
				echo "		<select name='ponto_".$l['id']."' id='ponto_".$l['id']."'>";
				echo "			<option value=''>Selecione</option>";
				echo 			$_POST['pontos'];
				echo "		<select>";
				echo "	</td>";
				echo "	<td>";
				echo "		<input type='text' value='".$l['hora_embarque']."' size='2' name='embarque_".$l['id']."' id='embarque_".$l['id']."' class='hora'>";
				echo "	</td>";
				/*echo "	<td>";
				echo "		<input type='text' value='".$l['numero_apartamento']."' size='2' name='apto_".$l['id']."' id='apto_".$l['id']."' class='apto'>";
				echo "	</td>";*/
				echo "	<td>";
				echo "		<div class='btn_remover' name='cliente_".$c."'></div>";
				echo "	</td>";
				echo "</tr>";
				echo "<tr>";
				echo "		<td colspan='6' class='linha_espaco'></td>";
				echo "</tr>";
				echo "<script>$('#ponto_".$l['id']."').val(".$l['id_ponto_embarque'].")</script>";
				echo "<script>$('#transporte_".$l['id']."').val(".$l['id_empresa_tipo_transporte'].")</script>";
				$c++;
			}
		}
		else {
			echo "<tr class='linha'>";
			echo "	<td colspan='5' class='vazio'>Nenhum cliente informado</td>";
			echo "</tr>";
			echo "<tr class='linha_busca' style='display:none'>";
			echo "	<td colspan='5' class='td_esquerda'>";
			echo "		<input type='text' name='buscar_cliente' id='buscar_cliente' size='91' maxlength='80'>";
			echo "	</td>";
			echo "</tr>";
		}
	break;
	case 'exibir_rooming':
		require_once('../classes/viagem.class.php');
		$viagem = new viagem();
		
		$lista_rooming = $viagem -> buscar_rooming($_POST['id_viagem']);
		
		if (count($lista_rooming) > 0) {
			foreach ($lista_rooming as $l) {
				$rooming[$l['indice']][] = $l;
			}
			echo "<span class='titulo_dados'>Rooming List</span>";
			foreach ($rooming as $i => $room) {
				echo "<div class='hoteis_rooming' id='hoteis_rooming_".$i."'></div>";
				echo "<table id='rooming_".$i."' class='lista lista_rooming'>";
				echo "	<thead>";
				echo "		<tr>";
				echo "			<td width='47%'>Clientes</td>";
				echo "			<td width='13%'>Acomoda&ccedil;&atilde;o</td>";
				echo "			<td width='14%'>Camas casal</td>";
				echo "			<td width='14%'>Camas solteiro</td>";
				echo "			<td width='5%'>Apto</td>";
				echo "			<td width='7%'>Remover</td>";
				echo "		</tr>";
				echo "	</thead>";
				echo "	<tbody>";
				echo "		<tr class='linha' style='display:none'>";
				echo "			<td colspan='6' class='vazio'>Nenhum dado informado</td>";
				echo "		</tr>";
				foreach ($room as $r => $dados) {
					echo "<tr class='linha'>";
					echo "	<td class='td_esquerda'>";
					$clientes = explode(",",$dados['clientes']);
					foreach ($clientes as $c => $cliente) {
						echo "	<select name='cliente_".$i."_".$r."[]' class='cliente_rooming' id='rooming_".$i."_".$r."_".$c."'>";
						echo 		$_POST['clientes'];
						echo "	</select>";
						echo "	<div class='btn_remover_rooming' name='rooming_".$i."_".$r."_".$c."'></div>";
						echo "	<div class='espaco'></div>";
						echo "	<input type='hidden' name='valor_cliente_rooming_".$i."_".$r."_".$c."' id='valor_cliente_rooming_".$i."_".$r."_".$c."' value='".$cliente."'>";
						echo "	<script>$('#rooming_".$i."_".$r."_".$c."').val(".$cliente.")</script>";
					}
					echo "		<div class='espaco'></div><span class='btn_add' id='add_".$i."_".$r."'></span>";
					echo "	</td>";
					echo "	<td>";
					echo "		<select name='acomodacao_".$i."_".$r."' id='acomodacao_".$i."_".$r."'>";
					echo 			$_POST['acomodacao'];
					echo "		</select>";
					echo "	</td>";
					echo "	<td><input value='".$dados['camas_casal']."' type='text' size='1' name='casal_".$i."_".$r."' id='casal_".$i."_".$r."' class='camas'></td>";
					echo "	<td><input value='".$dados['camas_solteiro']."' type='text' size='1' name='solteiro_".$i."_".$r."' id='solteiro_".$i."_".$r."' class='camas'></td>";
					echo "	<td><input value='".$dados['apto']."' type='text' size='1' name='apto_".$i."_".$r."' id='apto_".$i."_".$r."' class='apto'></td>";
					echo "	<td><div class='btn_remover' name='rooming_".$i."_".$r."'></div></td>";
					echo "	<input type='hidden' class='valor_rooming' name='valor_rooming_".$i."_".$r."' id='valor_rooming_".$i."_".$r."' value='".$dados['id']."'>";
					echo "</tr>";
					echo "<script>$('#acomodacao_".$i."_".$r."').val(".$dados['id_acomodacao'].")</script>";
				}
				echo "	</tbody>";
				echo "</table>";
				echo "<span class='btn_adicionar add_rooming'>Adicionar Acomoda&ccedil;&atilde;o</span>";
				echo "<div class='espaco'></div>";
			}
			echo "<span class='btn_adicionar add_tabela_rooming'>Adicionar Rooming List</span>";
		}
		else {
			echo 0;
		}
	break;
	case 'selecionar_hoteis':
		require_once('../classes/viagem.class.php');
		$viagem = new viagem();
		
		$r = $viagem -> buscar_hoteis_rooming($_POST['id_viagem'],$_POST['indice']);
		echo mysql_result($r,0,'hoteis');
	break;
	
	/* ----------------- relatorios -------------------------- */
	//exibir destinos de uma viagem
	case 'exibir_destinos_relatorios':
		require_once('../classes/viagem.class.php');
		$viagem = new viagem();
	
		$lista_destinos = $viagem -> buscar_destinos($_POST['id_viagem']);
	
		if (count($lista_destinos) > 0) {
			echo "<tr class='linha' style='display:none'>";
			echo "	<td colspan='2' class='vazio'>Nenhum destino informado</td>";
			echo "</tr>";
			$c = 0;
			foreach ($lista_destinos as $l) {
				echo "	<tr class='linha'>";
				echo "		<td>".$l['sigla']."</td>";
				echo "		<td>".$l['cidade']."</td>";
				echo "	</tr>";
				$c++;
			}
		}
		else {
			echo "<tr class='linha'>";
			echo "	<td class='vazio' colspan='2'>Nenhum destino informado</td>";
			echo "</tr>";
		}
	break;
	//exibir transportes de uma viagem
	case 'exibir_transportes_relatorios':
		require_once('../classes/viagem.class.php');
		$viagem = new viagem();
	
		$lista_transportes = $viagem -> buscar_transportes($_POST['id_viagem']);
	
		if (count($lista_transportes) > 0) {
			echo "<tr style='display:none'>";
			echo "	<td colspan='5' class='vazio'>Nenhum transporte informado</td>";
			echo "</tr>";
			foreach ($lista_transportes as $l) {
				$lista_sinais = $viagem -> buscar_sinais('transporte', $l['id']);
				echo  "<tr class='linha'>";
				echo  "		<td>".$l['tipo_transporte']."</td>";
				echo  "		<td>".$l['empresa']."</td>";
				echo  "		<td>".$l['quantidade']."</td>";
				echo  "		<td>".$l['contato']."</td>";
				echo  "		<td>".$l['valor']."</td>";
				echo  "</tr>";
				echo  "<tr>";
				echo  "		<td colspan='5'>";
				echo  "			<div class='sinais'><h3>Sinais</h3>";
				echo  "				<div>";
				echo  "					<table class='lista'>";
				echo  "						<thead>";
				echo  "							<tr>";
				echo  "								<td>Data</td>";
				echo  "								<td>Valor</td>";
				echo  "							</tr>";
				echo  "						</thead>";
				echo  "						<tbody>";
				if (count($lista_sinais) > 0) {
					echo  "						<tr class='linha' style='display:none'>";
					echo  "							<td colspan='2' class='vazio'>Nenhum sinal informado</td>";
					echo  "						</tr>";
					foreach ($lista_sinais as $l) {
						echo  "					<tr class='linha'>";
						echo  "						<td>".$ls['data']."</td>";
						echo  "						<td>".$ls['valor']."</td>";
						echo  "					</tr>";
					}
				}
				else {
					echo  "						<tr class='linha'>";
					echo  "							<td colspan='2' class='vazio'>Nenhum sinal informado</td>";
					echo  "						</tr>";
				}
				echo  "						</tbody>";
				echo  "					</table>";
				echo  "				</div>";
				echo  "			</div>";
				echo  "		</td>";
				echo  "</tr>";
			}
		}
		else {
			echo "<tr class='linha'>";
			echo "	<td colspan='5' class='vazio'>Nenhum transporte informado</td>";
			echo "</tr>";
		}
	break;
	//exibir restaurantes de uma viagem
	case 'exibir_restaurantes_relatorios':
		require_once('../classes/viagem.class.php');
		$viagem = new viagem();
	
		$lista_restaurantes = $viagem -> buscar_restaurantes($_POST['id_viagem']);
	
		if (count($lista_restaurantes) > 0) {
			echo "<tr class='linha' style='display:none'>";
			echo "	<td colspan='7' class='vazio'>Nenhum restaurante informado</td>";
			echo "</tr>";
			foreach ($lista_restaurantes as $l) {
				$lista_sinais = $viagem -> buscar_sinais('restaurante', $l['id']);
				echo "<tr class='linha'>";
				echo "		<td>".$l['sigla']."</td>";
				echo "		<td>".$l['cidade']."</td>";
				echo "		<td>".$l['restaurante']."</td>";
				echo "		<td>".$l['data']."</td>";
				echo "		<td>".$l['hora']."</td>";
				echo "		<td>".$l['contato']."</td>";
				echo "		<td>".$l['valor']."</td>";
				echo  "</tr>";
				echo  "<tr>";
				echo  "		<td colspan='7'>";
				echo  "			<div class='sinais'><h3>Sinais</h3>";
				echo  "				<div>";
				echo  "					<table class='lista'>";
				echo  "						<thead>";
				echo  "							<tr>";
				echo  "								<td>Data</td>";
				echo  "								<td>Valor</td>";
				echo  "							</tr>";
				echo  "						</thead>";
				echo  "						<tbody>";
				if (count($lista_sinais) > 0) {
					echo  "						<tr class='linha' style='display:none'>";
					echo  "							<td colspan='2' class='vazio'>Nenhum sinal informado</td>";
					echo  "						</tr>";
					foreach ($lista_sinais as $l) {
						echo  "					<tr class='linha'>";
						echo  "						<td>".$ls['data']."</td>";
						echo  "						<td>".$ls['valor']."</td>";
						echo  "					</tr>";
					}
				}
				else {
					echo  "						<tr class='linha'>";
					echo  "							<td colspan='2' class='vazio'>Nenhum sinal informado</td>";
					echo  "						</tr>";
				}
				echo  "						</tbody>";
				echo  "					</table>";
				echo  "				</div>";
				echo  "			</div>";
				echo  "		</td>";
				echo  "</tr>";
				$c++;
			}
		}
		else {
			echo "<tr class='linha'>";
			echo "	<td colspan='7' class='vazio'>Nenhum restaurante informado</td>";
			echo "</tr>";
		}
	break;
	//exibir hoteis de uma viagem
	case 'exibir_hoteis_relatorios':
		require_once('../classes/viagem.class.php');
		$viagem = new viagem();
	
		$lista_hoteis = $viagem -> buscar_hoteis($_POST['id_viagem']);
	
		if (count($lista_hoteis) > 0) {
			echo "<tr class='linha' style='display:none'>";
			echo "	<td colspan='8' class='vazio'>Nenhum hotel informado</td>";
			echo "</tr>";
			foreach ($lista_hoteis as $l) {
				$lista_sinais = $viagem -> buscar_sinais('hotel', $l['id']);
				echo "<tr class='linha'>";
				echo "		<td>".$l['sigla']."</td>";
				echo "		<td>".$l['cidade']."</td>";
				echo "		<td>".$l['hotel']."</td>";
				echo "		<td>".$l['data_chegada']."</td>";
				echo "		<td>".$l['hora']."</td>";
				echo "		<td>".$l['data_saida']."</td>";
				echo "		<td>".$l['contato']."</td>";
				echo "		<td>".$l['valor']."</td>";
				echo  "</tr>";
				echo  "<tr>";
				echo  "		<td colspan='8'>";
				echo  "			<div class='sinais'><h3>Sinais</h3>";
				echo  "				<div>";
				echo  "					<table class='lista'>";
				echo  "						<thead>";
				echo  "							<tr>";
				echo  "								<td>Data</td>";
				echo  "								<td>Valor</td>";
				echo  "							</tr>";
				echo  "						</thead>";
				echo  "						<tbody>";
				if (count($lista_sinais) > 0) {
					echo  "						<tr class='linha' style='display:none'>";
					echo  "							<td colspan='2' class='vazio'>Nenhum sinal informado</td>";
					echo  "						</tr>";
					foreach ($lista_sinais as $l) {
						echo  "					<tr class='linha'>";
						echo  "						<td>".$ls['data']."</td>";
						echo  "						<td>".$ls['valor']."</td>";
						echo  "					</tr>";
						$i++;
					}
				}
				else {
					echo  "						<tr class='linha'>";
					echo  "							<td colspan='2' class='vazio'>Nenhum sinal informado</td>";
					echo  "						</tr>";
				}
				echo  "						</tbody>";
				echo  "					</table>";
				echo  "				</div>";
				echo  "			</div>";
				echo  "		</td>";
				echo  "</tr>";
				$c++;
			}
		}
		else {
			echo "<tr class='linha'>";
			echo "	<td colspan='8' class='vazio'>Nenhum hotel informado</td>";
			echo "</tr>";
		}
	break;
	case 'exibir_clientes_relatorios':
		require_once('../classes/viagem.class.php');
		$viagem = new viagem();
	
		$lista_clientes = $viagem -> buscar_clientes($_POST['id_viagem']);
	
		if (count($lista_clientes) > 0) {
			echo "<tr class='linha' style='display:none'>";
			echo "	<td colspan='5' class='vazio'>Nenhum cliente informado</td>";
			echo "</tr>";
			foreach ($lista_clientes as $l) {
				echo "<tr class='linha_cliente'>";
				$html_telefones = ($l['telefone'] != '') ? " - ".$l['telefone']."</label> <div class='btn_info_viagem' name='info_".$l['id_cliente']."'></div>" : '</label>';
				echo "	<td colspan='5'><label>".$l['cliente'].$html_telefones."</td>";
				echo "</tr>";
				echo "<tr class='linha'>";
				echo "	<td class='td_esquerda'>".$l['transporte_cliente']." ".$l['numero_transporte']."</td>";
				echo "	<td>".$l['poltrona']."</td>";
				echo "	<td>".$l['ponto_cliente']."</td>";
				echo "	<td>".$l['hora_embarque']."</td>";
				//echo "	<td>".$l['numero_apartamento']."</td>";
				echo "</tr>";
				echo "<tr>";
				echo "		<td colspan='5' class='linha_espaco'></td>";
				echo "</tr>";
			}
		}
		else {
			echo "<tr class='linha'>";
			echo "	<td colspan='5' class='vazio'>Nenhum cliente informado</td>";
			echo "</tr>";
		}
	break;
	case 'exibir_rooming_relatorios':
		require_once('../classes/viagem.class.php');
		$viagem = new viagem();
	
		$lista_rooming = $viagem -> buscar_rooming($_POST['id_viagem']);
	
		if (count($lista_rooming) > 0) {
			foreach ($lista_rooming as $l) {
				$rooming[$l['indice']][] = $l;
			}
			echo "<span class='titulo_dados'>Rooming List</span>";
			foreach ($rooming as $i => $room) {
				$nome_hoteis = $viagem -> buscar_hoteis_rooming($_POST['id_viagem'], $i);
				echo "<div style='text-align:center;font-weight:bold;'>".$nome_hoteis['nome_hoteis']."</div>";
				echo "<table class='lista'>";
				echo "	<thead>";
				echo "		<tr>";
				echo "			<td width='47%'>Clientes</td>";
				echo "			<td width='13%'>Acomoda&ccedil;&atilde;o</td>";
				echo "			<td width='14%'>Camas casal</td>";
				echo "			<td width='14%'>Camas solteiro</td>";
				echo "			<td width='5%'>Apto</td>";
				echo "		</tr>";
				echo "	</thead>";
				echo "	<tbody>";
				foreach ($room as $r => $dados) {
					echo "<tr class='linha'>";
					echo "	<td class='td_esquerda'>";
					$clientes = explode(",",$dados['nome_clientes']);
					foreach ($clientes as $cliente) {
						echo $cliente."<br>";
				}
					echo "	</td>";
					echo "	<td>".$dados['acomodacao']."</td>";
					echo "	<td>".$dados['camas_casal']."</td>";
					echo "	<td>".$dados['camas_solteiro']."</td>";
					echo "	<td>".$dados['apto']."</td>";
					echo "</tr>";
				}
				echo "	</tbody>";
				echo "</table>";
				echo "<div class='espaco'></div>";
			}
		}
		else {
			echo "<tr class='linha'>";
			echo "	<td colspan='5' class='vazio'>Nenhum dado informado</td>";
			echo "</tr>";
		}
	break;
	case 'buscar_bairros':
		require_once('../classes/viagem.class.php');
		$viagem = new viagem();
	
		$lista_bairro = $viagem -> buscar_bairros($_POST['id_viagem']);
		
		foreach ($lista_bairro as $l) {
			echo "<option value='".$l['bairro']."'>".$l['bairro']."</option>";
		}
	break;
}
?>