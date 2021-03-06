<?php
class viagem {

	//excluir viagem
	function excluir($viagem) {
		require_once('conexao.class.php');
		$conexao = new conexao();
		
		$conexao -> begin();

		//buscar ids viagem_restaurante
		$sql = "select id from viagem_restaurante where id_viagem = ".$viagem;
		$resultado = $conexao -> query($sql);
		if (mysql_num_rows($resultado) > 0) {
			while ($restaurante = mysql_fetch_assoc($resultado)) {
				$id_viagem_restaurante = $restaurante['id'];
				
				//viagem_restaurante_sinal
				$sql = "delete from viagem_restaurante_sinal where id_viagem_restaurante = ".$id_viagem_restaurante;
				$conexao -> query($sql);
			}
		}

		//viagem_restaurante
		$sql = "delete from viagem_restaurante where id_viagem = ".$viagem;
		$conexao -> query($sql);

		//buscar ids viagem_cliente
		$sql = "select id from viagem_cliente where id_viagem = ".$viagem;
		$resultado = $conexao -> query($sql);
		if (mysql_num_rows($resultado) > 0) {
			while ($viagem_cliente = mysql_fetch_assoc($resultado)) {
				$id_viagem_cliente = $viagem_cliente['id'];
				
				//viagem_cliente_rooming
				$sql = "delete from viagem_cliente_rooming where id_viagem_cliente = ".$id_viagem_cliente;
				$conexao -> query($sql);
			}
		}

		//viagem_rooming_list
		$sql = "delete from viagem_rooming_list where id_viagem = ".$viagem;
		$conexao -> query($sql);

		//viagem_cliente
		$sql = "delete from viagem_cliente where id_viagem = ".$viagem;
		$conexao -> query($sql);
		
		//buscar ids viagem_transporte
		$sql = "select id from viagem_transporte where id_viagem = ".$viagem;
		$resultado = $conexao -> query($sql);
		if (mysql_num_rows($resultado) > 0) {
			while ($transporte = mysql_fetch_assoc($resultado)) {
				$id_viagem_transporte = $transporte['id'];
				
				//viagem_transporte_sinal
				$sql = "delete from viagem_transporte_sinal where id_viagem_transporte = ".$id_viagem_transporte;
				$conexao -> query($sql);
			}
		}
		
		//viagem_transporte
		$sql = "delete from viagem_transporte where id_viagem = ".$viagem;
		$conexao -> query($sql);

		//buscar ids viagem_hotel
		$sql = "select id from viagem_hotel where id_viagem = ".$viagem;
		$resultado = $conexao -> query($sql);
		if (mysql_num_rows($resultado) > 0) {
			while ($hotel = mysql_fetch_assoc($resultado)) {
				$id_viagem_hotel = $hotel['id'];
				
				//viagem_rooming_hotel
				$sql = "delete from viagem_rooming_hotel where id_hotel_viagem = ".$id_viagem_hotel;
				$conexao -> query($sql);
				
				//viagem_hotel_sinal
				$sql = "delete from viagem_hotel_sinal where id_viagem_hotel = ".$id_viagem_hotel;
				$conexao -> query($sql);
			}
		}

		//viagem_hotel
		$sql = "delete from viagem_hotel where id_viagem = ".$viagem;
		$conexao -> query($sql);

		//destinos
		$sql = "delete from viagem_destinos where id_viagem = ".$viagem;
		$conexao -> query($sql);

		//viagem
		$sql = "delete from viagem where id = ".$viagem;
		$conexao -> query($sql);
		
		$conexao -> commit();
	}
	
	//buscar dados de uma viagem
	function buscar_dados($viagem) {
		require_once('conexao.class.php');
		$conexao = new conexao();
	
		$sql = "select viagem.*, date_format(data_saida,'%d/%m/%Y') as data_saida,
				replace(valor,'.',',') as valor
					from viagem
						where viagem.id = ".$viagem;
		$r = $conexao -> query($sql);
		return $r;
	}
	
	//buscar viagem por nome
	function buscar($nome) {
		require_once('conexao.class.php');
		$conexao = new conexao();
	
		$sql = "select viagem.id, viagem.viagem, date_format(data_saida,'%d/%m/%Y') as data_saida
					from viagem
						where viagem like '%".utf8_decode($nome)."%'
							-- order by viagem
								limit 10";
		$r = $conexao -> query($sql);
		return $r;
	}
	
	//buscar sinais
	function buscar_sinais($tipo,$id) {
		require_once('conexao.class.php');
		$conexao = new conexao();
		
		$sql = "select *
					from viagem_".$tipo."_sinal
						where id_viagem_".$tipo." = ".$id."
							-- order by data desc";
		$r = $conexao -> query($sql);
		return $r;
	}
	
	//buscar destinos de uma viagem
	function buscar_destinos($viagem) {
		require_once('conexao.class.php');
		$conexao = new conexao();
	
		$sql = "select viagem_destinos.id, cidade.id as id_cidade, cidade.cidade, estado.sigla
					from viagem_destinos
						inner join cidade on cidade.id = viagem_destinos.id_cidade
						inner join estado on estado.id = cidade.id_estado
							where viagem_destinos.id_viagem = ".$viagem;
		$r = $conexao -> query($sql);
		return $r;
	}
	
	//buscar transportes de uma viagem
	function buscar_transportes($viagem) {
		require_once('conexao.class.php');
		$conexao = new conexao();
	
		$sql = "select viagem_transporte.id, empresa.empresa, tipo_transporte.tipo_transporte,
				viagem_transporte.quantidade, viagem_transporte.contato, viagem_transporte.valor,
				viagem_transporte.id_empresa_tipo_transporte
					from viagem_transporte
						inner join empresa_tipo_transporte on empresa_tipo_transporte.id = viagem_transporte.id_empresa_tipo_transporte
						inner join tipo_transporte on tipo_transporte.id = empresa_tipo_transporte.id_tipo_transporte
						inner join empresa on empresa.id = empresa_tipo_transporte.id_empresa
							where viagem_transporte.id_viagem = ".$viagem;
		$r = $conexao -> query($sql);
		return $r;
	}
	
	//buscar bairros de uma viagem
	function buscar_bairros($viagem) {
		require_once('conexao.class.php');
		$conexao = new conexao();
		
		$sql = "select ponto_embarque.id, ponto_embarque.bairro
					from ponto_embarque
						inner join viagem_cliente on viagem_cliente.id_ponto_embarque = ponto_embarque.id
							where viagem_cliente.id_viagem = ".$viagem."
								group by ponto_embarque.id";
		$r = $conexao -> query($sql);
		return $r;
	}
	
	//buscar restaurantes de uma viagem
	function buscar_restaurantes($viagem) {
		require_once('conexao.class.php');
		$conexao = new conexao();
	
		$sql = "select viagem_restaurante.*, restaurante.restaurante, cidade.cidade, estado.sigla
					from viagem_restaurante
						inner join restaurante on restaurante.id = viagem_restaurante.id_restaurante
						inner join cidade on cidade.id = restaurante.id_cidade
						inner join estado on estado.id = cidade.id_estado
							where viagem_restaurante.id_viagem = ".$viagem;
		$r = $conexao -> query($sql);
		return $r;
	}
	
	//buscar hoteis de uma viagem
	function buscar_hoteis($viagem) {
		require_once('conexao.class.php');
		$conexao = new conexao();
	
		$sql = "select viagem_hotel.*, hotel.hotel, cidade.cidade, estado.sigla,
				date_format(data_chegada,'%d/%m/%Y') as data_chegada,
				date_format(data_saida,'%d/%m/%Y') as data_saida
					from viagem_hotel
						inner join hotel on hotel.id = viagem_hotel.id_hotel
						inner join cidade on cidade.id = hotel.id_cidade
						inner join estado on estado.id = cidade.id_estado
							where viagem_hotel.id_viagem = ".$viagem;
		$r = $conexao -> query($sql);
		return $r;
	}
	
	//buscar clientes de uma viagem
	function buscar_clientes($viagem) {
		require_once('conexao.class.php');
		$conexao = new conexao();
	
		$sql = "select viagem_cliente.*, cliente.id as id_cliente, cliente.cliente,
				date_format(cliente.data_nascimento,'%d/%m/%Y') as data_nascimento,
				cliente.rg, orgao_emissor.sigla,
				cliente_telefones.telefone, viagem_transporte.id_empresa_tipo_transporte,
				concat(ponto_embarque.bairro,' - ',ponto_embarque.local) as ponto_cliente,
				concat(empresa.empresa,' (',tipo_transporte.tipo_transporte,')') as transporte_cliente
					from viagem_cliente
						inner join cliente on cliente.id = viagem_cliente.id_cliente
						left join orgao_emissor on orgao_emissor.id = cliente.id_orgao_emissor
						left join cliente_telefones on cliente_telefones.id_cliente = cliente.id and cliente_telefones.principal = 1
						left join ponto_embarque on ponto_embarque.id = viagem_cliente.id_ponto_embarque
						left join viagem_transporte on viagem_transporte.id = viagem_cliente.id_transporte_viagem
						left join empresa_tipo_transporte on empresa_tipo_transporte.id = viagem_transporte.id_empresa_tipo_transporte
						left join empresa on empresa.id = empresa_tipo_transporte.id_empresa
						left join tipo_transporte on tipo_transporte.id = empresa_tipo_transporte.id_tipo_transporte
							where viagem_cliente.id_viagem = ".$viagem."
								-- order by numero_transporte, cliente.cliente";
		$r = $conexao -> query($sql);
		return $r;
	}
	
	//buscar rooming list de uma viagem
	function buscar_rooming($viagem) {
		require_once('conexao.class.php');
		$conexao = new conexao();
	
		$sql = "select viagem_rooming_list.*, group_concat(viagem_cliente.id_cliente) as clientes,
				group_concat(cliente.cliente) as nome_clientes, acomodacao.acomodacao
					from viagem_rooming_list
						left join viagem_cliente_rooming on viagem_cliente_rooming.id_viagem_rooming = viagem_rooming_list.id
						left join viagem_cliente on viagem_cliente.id = viagem_cliente_rooming.id_viagem_cliente
						left join cliente on cliente.id = viagem_cliente.id_cliente
						left join acomodacao on acomodacao.id = viagem_rooming_list.id_acomodacao
							where viagem_rooming_list.id_viagem = ".$viagem."
								group by viagem_rooming_list.id";
		$r = $conexao -> query($sql);
		return $r;
	}
	
	//buscar rooming list de um hotel
	function buscar_rooming_hotel($hotel) {
		require_once('conexao.class.php');
		$conexao = new conexao();
	
		$sql = "select viagem_rooming_list.*, group_concat(viagem_cliente.id_cliente) as clientes,
				group_concat(cliente.cliente) as nome_clientes, acomodacao.acomodacao
					from viagem_rooming_list
						left join viagem_cliente_rooming on viagem_cliente_rooming.id_viagem_rooming = viagem_rooming_list.id
						left join viagem_cliente on viagem_cliente.id = viagem_cliente_rooming.id_viagem_cliente
						left join cliente on cliente.id = viagem_cliente.id_cliente
						left join acomodacao on acomodacao.id = viagem_rooming_list.id_acomodacao
            			inner join viagem_rooming_hotel on viagem_rooming_list.indice = viagem_rooming_hotel.indice_rooming_list
						inner join viagem_hotel on viagem_hotel.id = viagem_rooming_hotel.id_hotel_viagem
            			and viagem_hotel.id_viagem = viagem_rooming_list.id_viagem
							where viagem_rooming_hotel.id_hotel_viagem = ".$hotel."
								group by viagem_rooming_list.id";
		$r = $conexao -> query($sql);
		return $r;
	}
	
	//buscar hoteis de uma rooming list
	function buscar_hoteis_rooming($viagem,$indice_rooming) {
		require_once('conexao.class.php');
		$conexao = new conexao();
		
		$sql = "select group_concat(viagem_rooming_hotel.id_hotel_viagem) as hoteis,
				group_concat(concat(hotel.hotel,' (',date_format(viagem_hotel.data_chegada,'%d/%m/%Y'),')') separator ', ') as nome_hoteis
					from viagem_rooming_hotel
						inner join viagem_hotel on viagem_hotel.id = viagem_rooming_hotel.id_hotel_viagem
						inner join hotel on hotel.id = viagem_hotel.id_hotel
							where viagem_hotel.id_viagem = ".$viagem."
							and viagem_rooming_hotel.indice_rooming_list = ".$indice_rooming;
		$r = $conexao -> query($sql);
		return $r;
	}
	
	function alterar($valores) {
		require_once('conexao.class.php');
		$conexao = new conexao();
		require_once('controles/funcoes.php');
	
		$empresas_clientes = array();
		$empresas_viagem = array();
		$restaurantes_viagem = array();
		$hoteis_viagem = array();
		$clientes_viagem = array();
				
		$conexao -> begin();

		//informacoes principais
		$sql = "update viagem set
				viagem = '".campo_sql($valores['nome_viagem'])."',
				data_saida = '".data_sql($valores['data_saida_viagem'])."',
				valor = '".str_replace(",",".",campo_sql($valores['valor_viagem']))."'
					where id = ".$valores['id_viagem'];
		$conexao -> query($sql);
		$id_viagem = $valores['id_viagem'];
		
		//excluir destinos
		if ($valores['excluir_destinos'] != '') {
			$sql = "delete from viagem_destinos where id in (";
			$excluir_destinos = explode("|",$valores['excluir_destinos']);
			foreach ($excluir_destinos as $exc) {
				if ($exc != '') {
					$sql .= $exc.",";
				}
			}
			$conexao -> query(substr($sql,0,-1).")");
		}
		
		//atualizar destinos
		if ($valores['lista_destinos'] != '') {
			$destinos = explode("|",$valores['lista_destinos']);
			foreach ($destinos as $i => $destino) {
				if ($destino != '') {
					//incluir se o destino nao existir no banco
					if ($valores['destino_'.$i] == '') {
						$sql = "insert into viagem_destinos (id_viagem, id_cidade) values ";
						$sql .= "(".$id_viagem.", ".$destino.")";
						$conexao -> query($sql);
					}
				}
			}
			
		}
		
		//excluir transportes
		if ($valores['excluir_transportes'] != '') {
			$sql_clientes = "update viagem_cliente set id_transporte_viagem = null where id_transporte_viagem in (";
			$sql_sinais = "delete from viagem_transporte_sinal where id_viagem_transporte in (";
			$sql_transporte = "delete from viagem_transporte where id in (";
			$excluir_transportes = explode("|",$valores['excluir_transportes']);
			$ids = '';
			foreach ($excluir_transportes as $exc) {
				if ($exc != '') {
					$ids .= $exc.",";
				}
			}
			$conexao -> query($sql_clientes.substr($ids,0,-1).")");
			$conexao -> query($sql_sinais.substr($ids,0,-1).")");
			$conexao -> query($sql_transporte.substr($ids,0,-1).")");
		}
		
		//atualizar transportes
		if ($valores['lista_transportes'] != '') {
			$transportes = explode("|",$valores['lista_transportes']);
			foreach ($transportes as $i => $transporte) {
				if ($transporte != '') {
					$t = explode(",",$transporte);
					//incluir se o transporte nao existir no banco
					if ($valores['transporte_'.$i] == '') {
						$sql = "insert into viagem_transporte (quantidade, contato, valor, id_viagem, id_empresa_tipo_transporte) values ";
						$sql .= "('".campo_sql($valores['quantidade_transporte_'.$i])."', '".campo_sql($valores['contato_transporte_'.$i])."',
								'".campo_sql($valores['valor_transporte_'.$i])."',".$id_viagem.", ".$t[2].")";
						$conexao -> query($sql);
						$empresas_clientes[$t[2]] = mysql_insert_id();
						$empresas_viagem[$i] = mysql_insert_id();
					}
					//atualizar se o transporte existir no banco
					else {
						$sql = "update viagem_transporte set
									quantidade = '".campo_sql($valores['quantidade_transporte_'.$i])."',
									contato = '".campo_sql($valores['contato_transporte_'.$i])."',
									valor = '".campo_sql($valores['valor_transporte_'.$i])."',
									id_viagem = ".$id_viagem.",
									id_empresa_tipo_transporte = ".$t[2]."
										where id = ".$valores['transporte_'.$i];
						$conexao -> query($sql);
						$empresas_clientes[$t[2]] = $valores['transporte_'.$i];
						$empresas_viagem[$i] = $valores['transporte_'.$i];
					}
				}
			}
		}
		
		//excluir sinais transportes
		if ($valores['excluir_sinais_transportes'] != '') {
			$excluir_sinais_transportes = explode("|",$valores['excluir_sinais_transportes']);
			$sql = "delete from viagem_transporte_sinal where id in (";
			foreach ($excluir_sinais_transportes as $excluir_sinal) {
				if ($excluir_sinal != '') {
					$sql .= $excluir_sinal.",";
				}
			}
			$conexao -> query(substr($sql,0,-1).")");
			echo substr($sql,0,-1).")";
		}
		
		//incluir sinais transportes
		if ($valores['sinais_transportes'] != '') {
			$sinais_transportes = explode("|",$valores['sinais_transportes']);
			foreach ($sinais_transportes as $i => $sinal_transporte) {
				if ($sinal_transporte != '') {
					$s = explode(",",$sinal_transporte);
					if (count($s) > 1) {
						//incluir se o sinal nao existir no banco
						if ($valores['sinal_transporte_'.$i] == '') {
							$sql = "insert into viagem_transporte_sinal (data, valor, id_viagem_transporte) values ";
							$sql .= "('".data_sql($s[1])."', '".campo_sql($s[2])."', ".$empresas_viagem[$s[0]].")";
							$conexao -> query($sql);
						}
					}
				}
			}
		}
		
		//excluir restaurantes
		if ($valores['excluir_restaurantes'] != '') {
			$sql_sinais = "delete from viagem_restaurante_sinal where id_viagem_restaurante in (";
			$sql_restaurante = "delete from viagem_restaurante where id in (";
			$excluir_restaurantes = explode("|",$valores['excluir_restaurantes']);
			$ids = '';
			foreach ($excluir_restaurantes as $exc) {
				if ($exc != '') {
					$ids .= $exc.",";
				}
			}
			$conexao -> query($sql_sinais.substr($ids,0,-1).")");
			$conexao -> query($sql_restaurante.substr($ids,0,-1).")");
		}
		
		//atualizar restaurantes
		if ($valores['lista_transportes'] != '') {
			$restaurantes = explode("|",$valores['lista_restaurantes']);
			foreach ($restaurantes as $i => $restaurante) {
				if ($restaurante != '') {
					//incluir se o restaurante nao existir no banco
					if ($valores['restaurante_'.$i] == '') {
						$sql = "insert into viagem_restaurante (data, hora, contato, valor, id_viagem, id_restaurante) values ";
						$sql .= "('".data_sql($valores['data_restaurante_'.$i])."', '".campo_sql($valores['hora_restaurante_'.$i])."',
								'".campo_sql($valores['contato_restaurante_'.$i])."','".campo_sql($valores['valor_restaurante_'.$i])."',
								".$id_viagem.", ".$restaurante.")";					
						$conexao -> query($sql);
						$restaurantes_viagem[$i] = mysql_insert_id();
					}
					//atualizar se o restaurante existir no banco
					else {
						$sql = "update viagem_restaurante set
									data = '".data_sql($valores['data_restaurante_'.$i])."',
									hora = '".campo_sql($valores['hora_restaurante_'.$i])."',
									contato = '".campo_sql($valores['contato_restaurante_'.$i])."',
									valor = '".campo_sql($valores['valor_restaurante_'.$i])."',
									id_viagem = ".$id_viagem."
										where id = ".$valores['restaurante_'.$i];
						$conexao -> query($sql);
						$restaurantes_viagem[$i] = $valores['restaurante_'.$i];
					}
				}
			}
		}
		
		//excluir sinais restaurantes
		if ($valores['excluir_sinais_restaurantes'] != '') {
			$excluir_sinais_restaurantes = explode("|",$valores['excluir_sinais_restaurantes']);
			$sql = "delete from viagem_restaurante_sinal where id in (";
			foreach ($excluir_sinais_restaurantes as $excluir_sinal) {
				if ($excluir_sinal != '') {
					$sql .= $excluir_sinal.",";
				}
			}
			$conexao -> query(substr($sql,0,-1).")");
			echo substr($sql,0,-1).")";
		}
		
		//incluir sinais restaurante
		if ($valores['sinais_restaurantes'] != '') {
			$sinais_restaurantes = explode("|",$valores['sinais_restaurantes']);
			foreach ($sinais_restaurantes as $i => $sinal_restaurante) {
				if ($sinal_restaurante != '') {
					$s = explode(",",$sinal_restaurante);
					if (count($s) > 1) {
						//incluir se o sinal nao existir no banco
						if ($valores['sinal_restaurante_'.$i] == '') {
							$sql = "insert into viagem_restaurante_sinal (data, valor, id_viagem_restaurante) values ";
							$sql .= "('".data_sql($s[1])."', '".campo_sql($s[2])."', ".$restaurantes_viagem[$s[0]].")";
							$conexao -> query($sql);
						}
					}
				}
			}
		}
		
		//excluir hoteis
		if ($valores['excluir_hoteis'] != '') {
			$sql_sinais = "delete from viagem_hotel_sinal where id_viagem_hotel in (";
			$sql_hotel = "delete from viagem_hotel where id in (";
			$excluir_hoteis = explode("|",$valores['excluir_hoteis']);
			$ids = '';
			foreach ($excluir_hoteis as $exc) {
				if ($exc != '') {
					$ids .= $exc.",";
				}
			}
			$conexao -> query($sql_sinais.substr($ids,0,-1).")");
			$conexao -> query($sql_hotel.substr($ids,0,-1).")");
		}
		
		//atualizar hoteis
		if ($valores['lista_hoteis'] != '') {
			$hoteis = explode("|",$valores['lista_hoteis']);
			foreach ($hoteis as $i => $hotel) {
				if ($hotel != '') {
					//incluir se o hotel nao existir no banco
					if ($valores['hotel_'.$i] == '') {
						$sql = "insert into viagem_hotel (data_chegada, hora, data_saida, contato, valor, id_viagem, id_hotel) values ";
						$sql .= "('".data_sql($valores['chegada_hotel_'.$i])."', '".campo_sql($valores['hora_hotel_'.$i])."',
								'".data_sql($valores['saida_hotel_'.$i])."','".campo_sql($valores['contato_hotel_'.$i])."',
								'".campo_sql($valores['valor_hotel_'.$i])."',".$id_viagem.", ".$hotel.")";					
						$conexao -> query($sql);
						$hoteis_viagem[$i] = mysql_insert_id();
					}
					//atualizar se o hotel existir no banco
					else {
						$sql = "update viagem_hotel set
									data_chegada = '".data_sql($valores['chegada_hotel_'.$i])."',
									hora = '".campo_sql($valores['hora_hotel_'.$i])."',
									data_saida = '".data_sql($valores['saida_hotel_'.$i])."',
									contato = '".campo_sql($valores['contato_hotel_'.$i])."',
									valor = '".campo_sql($valores['valor_hotel_'.$i])."',
									id_viagem = ".$id_viagem."
										where id = ".$valores['hotel_'.$i];
						$conexao -> query($sql);
						$hoteis_viagem[$i] = $valores['hotel_'.$i];
					}
				}
			}
		}
		
		//excluir sinais hoteis
		if ($valores['excluir_sinais_hoteis'] != '') {
			$excluir_sinais_hoteis = explode("|",$valores['excluir_sinais_hoteis']);
			$sql = "delete from viagem_hotel_sinal where id in (";
			foreach ($excluir_sinais_hoteis as $excluir_sinal) {
				if ($excluir_sinal != '') {
					$sql .= $excluir_sinal.",";
				}
			}
			$conexao -> query(substr($sql,0,-1).")");
			echo substr($sql,0,-1).")";
		}
		
		//incluir sinais hoteis
		if ($valores['sinais_hoteis'] != '') {
			$sinais_hoteis = explode("|",$valores['sinais_hoteis']);
			foreach ($sinais_hoteis as $i => $sinal_hotel) {
				if ($sinal_hotel != '') {
					$s = explode(",",$sinal_hotel);
					if (count($s) > 1) {
						//incluir se o sinal nao existir no banco
						if ($valores['sinal_hotel_'.$i] == '') {
							$sql = "insert into viagem_hotel_sinal (data, valor, id_viagem_hotel) values ";
							$sql .= "('".data_sql($s[1])."', '".campo_sql($s[2])."', ".$hoteis_viagem[$s[0]].")";
							$conexao -> query($sql);
						}
					}
				}
			}
		}
		
		//excluir clientes
		if ($valores['excluir_clientes'] != '') {
			$sql_rooming = "delete from viagem_cliente_rooming where id_viagem_cliente in (";
			$sql_cliente = "delete from viagem_cliente where id in (";
			$excluir_clientes = explode("|",$valores['excluir_clientes']);
			$ids = '';
			foreach ($excluir_clientes as $exc) {
				if ($exc != '') {
					$ids .= $exc.",";
				}
			}
			$conexao -> query($sql_rooming.substr($ids,0,-1).")");
			$conexao -> query($sql_cliente.substr($ids,0,-1).")");
		}
		
		//incluir clientes
		if ($valores['lista_clientes'] != '') {
			$clientes = explode("|",$valores['lista_clientes']);
			foreach ($clientes as $i => $cliente) {
				if ($cliente != '') {
					//incluir se o cliente nao existir no banco
					if ($valores['valor_cliente_'.$i] == '') {
						$c = explode(",",$cliente);
						$sql = "insert into viagem_cliente (hora_embarque, numero_transporte, poltrona,
								id_viagem, id_cliente, id_transporte_viagem, id_ponto_embarque) values ";
						$sql .= "('".campo_sql($valores['embarque_'.$c[0]])."', '".campo_sql($valores['numero_transporte_'.$c[0]])."',
								'".campo_sql($valores['poltrona_'.$c[0]])."', ".$id_viagem.",
								".$c[0].",nao_obrigatorio('".$empresas_clientes[$valores['transporte_'.$c[0]]]."'),
								nao_obrigatorio('".$valores['ponto_'.$c[0]]."'))";
						$conexao -> query($sql);
						$clientes_viagem[$c[0]] = mysql_insert_id();
					}
					//atualizar se o cliente existir no banco
					else {
						$viagem_cliente = $valores['viagem_cliente_'.$i];
						$sql = "update viagem_cliente set
								hora_embarque = '".campo_sql($valores['embarque_'.$viagem_cliente])."',
								numero_transporte = '".campo_sql($valores['numero_transporte_'.$viagem_cliente])."',
								poltrona = '".campo_sql($valores['poltrona_'.$viagem_cliente])."',
								id_viagem = ".$id_viagem.",
								id_cliente = ".$valores['valor_cliente_'.$i].",
								id_transporte_viagem = nao_obrigatorio('".$empresas_clientes[$valores['transporte_'.$viagem_cliente]]."'),
								id_ponto_embarque = nao_obrigatorio('".$valores['ponto_'.$viagem_cliente]."')
									where id = ".$valores['viagem_cliente_'.$i];
						$conexao -> query($sql);
						$clientes_viagem[$valores['valor_cliente_'.$i]] = $valores['viagem_cliente_'.$i];
					}
				}
			}
		}
		
		//excluir clientes de rooming list
		if ($valores['excluir_cliente_rooming'] != '') {
			$sql = "delete from viagem_cliente_rooming where id_viagem_cliente in (";
			$excluir_cliente_rooming = explode("|",$valores['excluir_cliente_rooming']);
			$ids = '';
			foreach ($excluir_cliente_rooming as $exc) {
				if ($exc != '') {
					$ids .= $clientes_viagem[$exc].",";
				}
			}
			$conexao -> query($sql.substr($ids,0,-1).")");
		}
		
		//excluir rooming list
		if ($valores['excluir_rooming'] != '') {
			$sql_cliente_rooming = "delete from viagem_cliente_rooming where id_viagem_rooming in (";
			$sql_rooming = "delete from viagem_rooming_list where id in (";
			$excluir_rooming = explode("|",$valores['excluir_rooming']);
			$ids = '';
			foreach ($excluir_rooming as $exc) {
				if ($exc != '') {
					$ids .= $exc.",";
				}
			}
			$conexao -> query($sql_cliente_rooming.substr($ids,0,-1).")");
			$conexao -> query($sql_rooming.substr($ids,0,-1).")");
		}
		
		//excluir hoteis da rooming list
		$sql = "delete viagem_rooming_hotel
					from viagem_rooming_hotel
						inner join viagem_hotel on viagem_hotel.id = viagem_rooming_hotel.id_hotel_viagem
							where viagem_hotel.id_viagem = ".$id_viagem;
		$conexao -> query($sql);
		
		//rooming list
		if ($valores['lista_rooming'] != '') {
			//tabelas
			$hoteis_rooming = explode("|",$valores['rooming_hotel']);
			//linhas
			$linhas = explode("|",$valores['lista_rooming']);
			//percorrer tabelas
			foreach ($hoteis_rooming as $i => $hotel_rooming) {
				//se acomodacao foi preenchida
				if ($linhas[$i] != '') {
					$linha = explode(",",$linhas[$i]);
					//linhas da tabela
					foreach ($linha as $l) {
						//inserir acomodacao se nao existir no banco
						if ($valores['valor_rooming_'.$i.'_'.$l] == '') {
							$sql_rooming = "insert into viagem_rooming_list (camas_solteiro, camas_casal, apto, indice, id_acomodacao, id_viagem) values ";
							$sql_rooming .= "('".campo_sql($valores['solteiro_'.$i."_".$l])."', '".campo_sql($valores['casal_'.$i."_".$l])."',
											'".campo_sql($valores['apto_'.$i."_".$l])."', ".$i.",
											nao_obrigatorio('".$valores['acomodacao_'.$i."_".$l]."'), ".$id_viagem.")";
							$conexao -> query($sql_rooming);
							$id_rooming = mysql_insert_id();
						}
						//se ja existe no banco, guardar id
						else {
							$id_rooming = $valores['valor_rooming_'.$i.'_'.$l];
						}
						
						//clientes da acomodacao
						if (count($valores['cliente_'.$i."_".$l]) > 0) {
							foreach ($valores['cliente_'.$i."_".$l] as $c => $r) {
								//cliente escolhido nessa posicao
								if ($r != '') {
									//se o cliente esta na viagem
									if ($clientes_viagem[$r] != '') {
										//incluir se nao estiver no banco
										if ($valores['valor_cliente_rooming_'.$i.'_'.$l.'_'.$c] == '') {
											$sql_cliente = "insert into viagem_cliente_rooming (id_viagem_cliente, id_viagem_rooming) values ";
											$sql_cliente .= "(".$clientes_viagem[$r].", ".$id_rooming.")";
											$conexao -> query($sql_cliente);
										}
									}
								}
							}
						}
					}
					
					//hoteis da tabela
					$hoteis = explode(",",$hotel_rooming);
					foreach ($hoteis as $h) {
						if ($hoteis_viagem[$h] != '') {
							$sql_hotel_rooming = "insert into viagem_rooming_hotel (id_hotel_viagem, indice_rooming_list) values ";
							$sql_hotel_rooming .= "(".$hoteis_viagem[$h].", ".$i.") ";
							$sql_hotel_rooming .= "on duplicate key update id = id";
							$conexao -> query($sql_hotel_rooming);
						}
					}
				}
			}
		}
		
		$conexao -> commit();
	}
	
	function cadastrar($valores) {
		require_once('conexao.class.php');
		$conexao = new conexao();
		require_once('controles/funcoes.php');
		
		$empresas_clientes = array();
		$empresas_viagem = array();
		$restaurantes_viagem = array();
		$hoteis_viagem = array();
		$clientes_viagem = array();
		
		$conexao -> begin();
		
		//informacoes principais
		$sql = "insert into viagem (viagem, data_saida, valor) values ";
		$sql .= "('".campo_sql($valores['nome_viagem'])."','".data_sql($valores['data_saida_viagem'])."','".campo_sql($valores['valor_viagem'])."')";
		$conexao -> query($sql);
		$id_viagem = mysql_insert_id();
		
		//destinos
		if ($valores['lista_destinos'] != '') {
			$sql = "insert into viagem_destinos (id_viagem, id_cidade) values ";
			$destinos = explode("|",$valores['lista_destinos']);
			foreach ($destinos as $d) {
				if ($d != '') {
					$sql .= "(".$id_viagem.", ".$d."),";					
				}
			}
			$conexao -> query(substr($sql,0,-1));
		}
		
		//transportes
		if ($valores['lista_transportes'] != '') {
			$transportes = explode("|",$valores['lista_transportes']);
			foreach ($transportes as $i => $transporte) {
				if ($transporte != '') {
					$t = explode(",",$transporte);
					$sql = "insert into viagem_transporte (quantidade, contato, valor, id_viagem, id_empresa_tipo_transporte) values ";
					$sql .= "('".campo_sql($valores['quantidade_transporte_'.$i])."', '".campo_sql($valores['contato_transporte_'.$i])."',
							'".campo_sql($valores['valor_transporte_'.$i])."',".$id_viagem.", ".$t[2].")";
					$conexao -> query($sql);
					$empresas_clientes[$t[2]] = mysql_insert_id();
					$empresas_viagem[$i] = mysql_insert_id();
				}
			}
		}
		
		//sinais transportes
		if ($valores['sinais_transportes'] != '') {
			$sinais_transportes = explode("|",$valores['sinais_transportes']);
			$sql = "insert into viagem_transporte_sinal (data, valor, id_viagem_transporte) values ";
			foreach ($sinais_transportes as $sinal_transporte) {
				if ($sinal_transporte != '') {
					$s = explode(",",$sinal_transporte);
					$sql .= "('".data_sql($s[1])."', '".campo_sql($s[2])."', ".$empresas_viagem[$s[0]]."),";
				}
			}
			$conexao -> query(substr($sql,0,-1));
		}
		
		//restaurantes
		if ($valores['lista_restaurantes'] != '') {
			$restaurantes = explode("|",$valores['lista_restaurantes']);
			foreach ($restaurantes as $i => $restaurante) {
				if ($restaurante != '') {
					$sql = "insert into viagem_restaurante (data, hora, contato, valor, id_viagem, id_restaurante) values ";
					$sql .= "('".data_sql($valores['data_restaurante_'.$i])."', '".campo_sql($valores['hora_restaurante_'.$i])."',
							'".campo_sql($valores['contato_restaurante_'.$i])."','".campo_sql($valores['valor_restaurante_'.$i])."',
							".$id_viagem.", ".$restaurante.")";					
					$conexao -> query($sql);
					$restaurantes_viagem[$i] = mysql_insert_id();
				}
			}
		}
		
		//sinais restaurantes
		if ($valores['sinais_restaurantes'] != '') {
			$sinais_restaurantes = explode("|",$valores['sinais_restaurantes']);
			$sql = "insert into viagem_restaurante_sinal (data, valor, id_viagem_restaurante) values ";
			foreach ($sinais_restaurantes as $sinal_restaurante) {
				if ($sinal_restaurante != '') {
					$r = explode(",",$sinal_restaurante);
					$sql .= "('".data_sql($r[1])."', '".campo_sql($r[2])."', ".$restaurantes_viagem[$r[0]]."),";
				}
			}
			$conexao -> query(substr($sql,0,-1));
		}

		//hoteis
		if ($valores['lista_hoteis'] != '') {
			$hoteis = explode("|",$valores['lista_hoteis']);
			foreach ($hoteis as $i => $hotel) {
				if ($hotel != '') {
					$sql = "insert into viagem_hotel (data_chegada, hora, data_saida, contato, valor, id_viagem, id_hotel) values ";
					$sql .= "('".data_sql($valores['chegada_hotel_'.$i])."', '".campo_sql($valores['hora_hotel_'.$i])."',
							'".data_sql($valores['saida_hotel_'.$i])."','".campo_sql($valores['contato_hotel_'.$i])."',
							'".campo_sql($valores['valor_hotel_'.$i])."',".$id_viagem.", ".$hotel.")";					
					$conexao -> query($sql);
					$hoteis_viagem[$i] = mysql_insert_id();
				}
			}
		}
		
		//sinais hoteis
		if ($valores['sinais_hoteis'] != '') {
			$sinais_hoteis = explode("|",$valores['sinais_hoteis']);
			$sql = "insert into viagem_hotel_sinal (data, valor, id_viagem_hotel) values ";
			foreach ($sinais_hoteis as $sinal_hotel) {
				if ($sinal_hotel != '') {
					$h = explode(",",$sinal_hotel);
					$sql .= "('".data_sql($h[1])."', '".campo_sql($h[2])."', ".$hoteis_viagem[$h[0]]."),";
				}
			}
			$conexao -> query(substr($sql,0,-1));
		}
		
		//clientes
		if ($valores['lista_clientes'] != '') {
			$clientes = explode("|",$valores['lista_clientes']);
			foreach ($clientes as $i => $cliente) {
				if ($cliente != '') {
					$c = explode(",",$cliente);
					$sql = "insert into viagem_cliente (hora_embarque, numero_transporte, poltrona,
							id_viagem, id_cliente, id_transporte_viagem, id_ponto_embarque) values ";
					$sql .= "('".campo_sql($valores['embarque_'.$c[0]])."', '".campo_sql($valores['numero_transporte_'.$c[0]])."',
							'".campo_sql($valores['poltrona_'.$c[0]])."', ".$id_viagem.",
							".$c[0].",nao_obrigatorio('".$empresas_clientes[$valores['transporte_'.$c[0]]]."'),nao_obrigatorio('".$valores['ponto_'.$c[0]]."'))";
					$conexao -> query($sql);
					$clientes_viagem[$c[0]] = mysql_insert_id();
				}
			}
		}
		
		//rooming list
		if ($valores['lista_rooming'] != '') {
			//tabelas
			$hoteis_rooming = explode("|",$valores['rooming_hotel']);
			//linhas
			$linhas = explode("|",$valores['lista_rooming']);
			//percorrer tabelas
			foreach ($hoteis_rooming as $i => $hotel_rooming) {
				//se acomodacao foi preenchida 
				if ($linhas[$i] != '') {
					$linha = explode(",",$linhas[$i]);
					//linhas da tabela
					foreach ($linha as $l) {
						$sql_rooming = "insert into viagem_rooming_list (camas_solteiro, camas_casal, apto, indice, id_acomodacao, id_viagem) values ";
						$sql_rooming .= "('".campo_sql($valores['solteiro_'.$i."_".$l])."', '".campo_sql($valores['casal_'.$i."_".$l])."',
										'".campo_sql($valores['apto_'.$i."_".$l])."', ".$i.",
										nao_obrigatorio('".$valores['acomodacao_'.$i."_".$l]."'), ".$id_viagem.")";
						$conexao -> query($sql_rooming);
						$id_rooming = mysql_insert_id();
						
						//clientes da acomodacao
						if (count($valores['cliente_'.$i."_".$l]) > 0) {
							foreach ($valores['cliente_'.$i."_".$l] as $c => $r) {
								//cliente escolhido nessa posicao
								if ($r != '') {
									//se o cliente esta na viagem
									if ($clientes_viagem[$r] != '') {
										$sql_cliente = "insert into viagem_cliente_rooming (id_viagem_cliente, id_viagem_rooming) values ";
										$sql_cliente .= "(".$clientes_viagem[$r].", ".$id_rooming.")";
										$conexao -> query($sql_cliente);
									}
								}
							}
						}
					}
					
					//hoteis da tabela
					$hoteis = explode(",",$hotel_rooming);
					foreach ($hoteis as $h) {
						if ($hoteis_viagem[$h] != '') {
							$sql_hotel_rooming = "insert into viagem_rooming_hotel (id_hotel_viagem, indice_rooming_list) values ";
							$sql_hotel_rooming .= "(".$hoteis_viagem[$h].", ".$i.") ";
							$sql_hotel_rooming .= "on duplicate key update id = id";
							$conexao -> query($sql_hotel_rooming);
						}
					}
				}
			}
		}
		
		$conexao -> commit();
	}
	
	function lista_etiquetas($valores) {
		require_once ('conexao.class.php');
		$conexao = new conexao();
		
		$sql = "select cliente.cliente, viagem_rooming_list.apto,
				hotel.hotel, cidade.cidade, estado.sigla,
				substring_index(group_concat(hotel_telefones.telefone),',',2) as telefones
					from viagem_cliente
						inner join cliente on cliente.id = viagem_cliente.id_cliente
						inner join viagem on viagem.id = viagem_cliente.id_viagem
						inner join viagem_hotel on viagem.id = viagem_hotel.id_viagem
			            inner join viagem_rooming_list on viagem_rooming_list.id_viagem = viagem.id
			            inner join viagem_cliente_rooming on viagem_cliente_rooming.id_viagem_cliente = viagem_cliente.id
						inner join hotel on hotel.id = viagem_hotel.id_hotel
						inner join cidade on cidade.id = hotel.id_cidade
						inner join estado on estado.id = cidade.id_estado
						inner join ponto_embarque on ponto_embarque.id = viagem_cliente.id_ponto_embarque
						left join hotel_telefones on hotel_telefones.id_hotel = hotel.id
							where viagem_cliente.id_viagem = ".$valores['id_viagem']."
							and ponto_embarque.bairro = '".$valores['bairros']."'
								group by viagem_cliente.id";
		return $conexao -> query($sql);
	}
	
	function lista_seguro($viagem) {
		require_once ('conexao.class.php');
		$conexao = new conexao();
		
		$sql = "select cliente.cliente, if (cliente.cpf='',if(titular.cpf='','',concat(titular.cpf,' (Resp.)')),cliente.cpf) as cpf,
				date_format(cliente.data_nascimento,'%d/%m/%Y') as data_nascimento, empresa.empresa,
				viagem_cliente.numero_transporte, tipo_transporte.tipo_transporte
					from cliente
						inner join viagem_cliente on viagem_cliente.id_cliente = cliente.id
						inner join viagem_transporte on viagem_transporte.id = viagem_cliente.id_transporte_viagem
			            inner join empresa_tipo_transporte on empresa_tipo_transporte.id = viagem_transporte.id_empresa_tipo_transporte
			            inner join empresa on empresa.id = empresa_tipo_transporte.id_empresa
			            inner join tipo_transporte on tipo_transporte.id = empresa_tipo_transporte.id_tipo_transporte
						left join cliente_dependentes on cliente.id = cliente_dependentes.id_dependente
    					left join cliente titular on titular.id = cliente_dependentes.id_titular
							where viagem_cliente.id_viagem = ".$viagem."
								-- order by cliente.cliente";
		return $conexao -> query($sql);
	}
	
	function lista_transportes($viagem) {
		require_once ('conexao.class.php');
		$conexao = new conexao();
		
		$sql = "select viagem_transporte.id, viagem_transporte.quantidade, empresa.empresa
					from viagem_transporte
						inner join empresa_tipo_transporte on empresa_tipo_transporte.id = viagem_transporte.id_empresa_tipo_transporte
						inner join empresa on empresa.id = empresa_tipo_transporte.id_empresa
							where id_viagem = ".$viagem."
							and empresa_tipo_transporte.id_tipo_transporte = 1";
		return $conexao -> query($sql);
	}
	
	function passageiros_pontos($transporte,$numero) {
		require_once ('conexao.class.php');
		$conexao = new conexao();
		
		$sql = "select viagem_cliente.*, group_concat(concat(cliente.cliente,'|',poltrona)) as clientes,
				concat(ponto_embarque.bairro,' - ',ponto_embarque.local) as ponto
					from viagem_cliente
						inner join cliente on cliente.id = viagem_cliente.id_cliente
						inner join ponto_embarque on ponto_embarque.id = viagem_cliente.id_ponto_embarque
							where id_transporte_viagem = ".$transporte."
							and numero_transporte = ".$numero."
								group by hora_embarque";
		return $conexao -> query($sql);
	}
	
	function rooming_list($viagem) {
		require_once ('conexao.class.php');
		$conexao = new conexao();
		
		$sql = "select viagem_rooming_list.*, group_concat(cliente.cliente separator ' / ')  as clientes,
				acomodacao.acomodacao
					from viagem_rooming_list
						inner join viagem_cliente_rooming on viagem_rooming_list.id = viagem_cliente_rooming.id_viagem_rooming
						inner join viagem_cliente on viagem_cliente.id = viagem_cliente_rooming.id_viagem_cliente
						inner join cliente on cliente.id = viagem_cliente.id_cliente
						inner join acomodacao on acomodacao.id = viagem_rooming_list.id_acomodacao
							where viagem_rooming_list.id_viagem = ".$viagem."
								group by viagem_rooming_list.id";
		return $conexao -> query($sql);
	}

	function lista_passageiros($viagem) {
		require_once ('conexao.class.php');
		$conexao = new conexao();
		
		$sql = "select cliente, rg, sigla, empresa.empresa, viagem_cliente.numero_transporte,
				tipo_transporte.tipo_transporte
					from cliente
						inner join viagem_cliente on viagem_cliente.id_cliente = cliente.id
						inner join viagem_transporte on viagem_transporte.id = viagem_cliente.id_transporte_viagem
			            inner join empresa_tipo_transporte on empresa_tipo_transporte.id = viagem_transporte.id_empresa_tipo_transporte
			            inner join empresa on empresa.id = empresa_tipo_transporte.id_empresa
			            inner join tipo_transporte on tipo_transporte.id = empresa_tipo_transporte.id_tipo_transporte
						left join orgao_emissor on orgao_emissor.id = cliente.id_orgao_emissor
							where viagem_cliente.id_viagem = ".$viagem."
								-- order by cliente.cliente";
		return $conexao -> query($sql);
	}
	
	function contatos_passageiros($viagem) {
		require_once ('conexao.class.php');
		$conexao = new conexao();
		
		$sql = "select cliente, group_concat(cliente_telefones.telefone separator ', ') as telefones, numero_transporte
					from cliente
						inner join viagem_cliente on cliente.id = viagem_cliente.id_cliente
						left join cliente_telefones on cliente_telefones.id_cliente = cliente.id
							where viagem_cliente.id_viagem = ".$viagem."
								group by cliente.id
									-- order by numero_transporte, cliente";
		
		return $conexao -> query($sql);
	}
	
}