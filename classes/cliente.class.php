<?php
class cliente {

	//excluir cliente
	function excluir($cliente) {
		require_once('conexao.class.php');
		$conexao = new conexao();
		
		$conexao -> begin();
		
		//cliente_telefones
		$sql = "delete from cliente_telefones where id_cliente = ".$cliente;
		$conexao -> query($sql);
		
		//cliente_dependentes
		$sql = "delete from cliente_dependentes where id_titular = ".$cliente." or id_dependente = ".$cliente;
		$conexao -> query($sql);
		
		//buscar ids viagem_cliente
		$sql = "select id from viagem_cliente where id_cliente = ".$cliente;
		$resultado = $conexao -> query($sql);
		if (mysql_num_rows($resultado) > 0) {
			$id_viagem_cliente = mysql_result($resultado,0,'id');
			
			//viagem_cliente_rooming
			$sql = "delete from viagem_cliente_rooming where id_viagem_cliente = ".$id_viagem_cliente;
			$conexao -> query($sql);
			
			//viagem_cliente
			$sql = "delete from viagem_cliente where id_cliente = ".$cliente;
			$conexao -> query($sql);
		}
		
		//cliente
		$sql = "delete from cliente where id = ".$cliente;
		$conexao -> query($sql);
		
		$conexao -> commit();
	}
	
	//buscar dados de um cliente
	function buscar_dados($cliente) {
		require_once('conexao.class.php');
		$conexao = new conexao();
		
		$sql = "select cliente.*, date_format(data_nascimento,'%d/%m/%Y') as data_nascimento,
				date_format(data_casamento,'%d/%m/%Y') as data_casamento, cidade.id_estado, cidade.id as cidade
					from cliente
						left join cidade on cidade.id = cliente.id_cidade
							where cliente.id = ".$cliente;
		$r = $conexao -> query($sql);
		return $r;
	}
	
	//buscar historico de viagens de um cliente
	function buscar_historico($valores) {
		require_once('conexao.class.php');
		$conexao = new conexao();
		require_once('../controles/funcoes.php');
		
		$comp = '';
		if ($valores['data_inicial'] != '') {
			$comp .= " and viagem.data_saida >= '".data_sql($valores['data_inicial'])."'";
		}
		if ($valores['data_final'] != '') {
			$comp .= " and viagem.data_saida <= '".data_sql($valores['data_final'])."'";
		}
		if ($valores['viagem'] != '') {
			$comp .= " and viagem.viagem like '%".campo_sql($valores['viagem'])."%'";
		}
		
		$sql = "select sql_calc_found_rows viagem.*, replace(viagem.valor,'.',',') as valor,
				date_format(viagem.data_saida,'%d/%m/%Y') as data_saida
  					from viagem
    					inner join viagem_cliente on viagem_cliente.id_viagem = viagem.id
      						where viagem_cliente.id_cliente = ".$valores['cliente']."
							".$comp."
								-- order by data_saida desc
      								limit ".$valores['inicio'].",10";
		$this->lista = $conexao -> query($sql);
		
		$sql = "select found_rows() as total";
		$this->total = $conexao -> query($sql);
	}
	
	//buscar titular de um cliente
	function buscar_titular($cliente) {
		require_once('conexao.class.php');
		$conexao = new conexao();
		
		$sql = "select titular.id, titular.cliente as titular, cliente_telefones.telefone
					from cliente
						inner join cliente_dependentes on cliente_dependentes.id_dependente = cliente.id
    					inner join cliente titular on titular.id = cliente_dependentes.id_titular
						left join cliente_telefones on titular.id = cliente_telefones.id_cliente and cliente_telefones.principal = 1
							where cliente.id = ".$cliente;
		$r = $conexao -> query($sql);
		return $r;
	}

	//buscar dependentes de um cliente
	function buscar_dependentes($cliente) {
		require_once('conexao.class.php');
		$conexao = new conexao();
		
		$sql = "select cliente_dependentes.id, dependente.cliente, cliente_telefones.telefone
					from cliente_dependentes
            			inner join cliente dependente on dependente.id = cliente_dependentes.id_dependente
            			left join cliente_telefones on cliente_telefones.id_cliente = dependente.id and cliente_telefones.principal = 1
							where id_titular = ".$cliente;
		$r = $conexao -> query($sql);
		return $r;
	}
	
	//buscar cliente por nome
	function buscar($nome) {
		require_once('conexao.class.php');
		$conexao = new conexao();
		
		$sql = "select cliente.id, cliente.cliente, cliente_telefones.telefone
					from cliente
						left join cliente_telefones on cliente_telefones.id_cliente = cliente.id and cliente_telefones.principal = 1
							where cliente like '".utf8_decode($nome)."%'
								-- order by cliente
									limit 15";
		$r = $conexao -> query($sql);
		return $r;
	}
	
	//buscar passageiros por nome
	function buscar_passageiros($nome) {
		require_once('conexao.class.php');
		$conexao = new conexao();
		
		$sql = "select cliente.id, cliente.cliente, cliente_telefones.telefone
					from cliente
						left join cliente_telefones on cliente_telefones.id_cliente = cliente.id and cliente_telefones.principal = 1
							where cliente like '".$nome."%'
							and cliente.id_situacao = 1
								-- order by cliente
									limit 10";
		$r = $conexao -> query($sql);
		return $r;
	}
	
	//buscar telefones de um cliente
	function buscar_telefones($cliente) {
		require_once('conexao.class.php');
		$conexao = new conexao();
		
		$sql = "select cliente_telefones.id, cliente_telefones.telefone, cliente_telefones.id_tipo,
				cliente_telefones.principal, tipo_telefone.tipo_telefone
					from cliente_telefones
						inner join tipo_telefone on cliente_telefones.id_tipo = tipo_telefone.id
							where cliente_telefones.id_cliente = ".$cliente."
								-- order by principal desc, tipo_telefone";
		$r = $conexao -> query($sql);
		return $r;
	}
	
	//cadastrar cliente
	function cadastrar($valores) {
		require_once('conexao.class.php');
		$conexao = new conexao();
		require_once('controles/funcoes.php');
		
		$conexao -> begin();

		$nascimento = '';
		$data_nascimento = data_sql($valores['data_nascimento']);
		if (empty($data_nascimento)) {
			$nascimento = "null";
		}
		else {
			$nascimento = "'".$data_nascimento."'";
		}

		$casamento = '';
		$data_casamento = data_sql($valores['data_casamento']);
		if (empty($data_casamento)) {
			$casamento = "null";
		}
		else {
			$casamento = "'".$data_casamento."'";
		}

		$sql = "insert into cliente (cliente, sexo, cpf, rg, passaporte, data_nascimento, data_casamento,
				endereco, numero, complemento, bairro, cep, email, id_cidade, id_orgao_emissor, id_situacao) values 
				('".campo_sql($valores['cliente'])."', '".campo_sql($valores['sexo'])."', '".campo_sql($valores['cpf'])."',
				'".campo_sql($valores['rg'])."', '".campo_sql($valores['passaporte'])."', ".$nascimento.",
				".$casamento.", '".campo_sql($valores['endereco'])."', '".campo_sql($valores['numero'])."',
				'".campo_sql($valores['complemento'])."', '".campo_sql($valores['bairro'])."', '".campo_sql($valores['cep'])."',
				'".email_sql($valores['email'])."', nao_obrigatorio('".$valores['cidade']."'), nao_obrigatorio('".$valores['orgao_emissor']."'),
				".campo_sql($valores['situacao']).")";
		$r = $conexao -> query($sql);
		$id_cliente = mysql_insert_id();
		
		if ($valores['check_dependente'] == 1) {
			$dependentes = explode("|", $valores['lista_dependentes']);
			foreach ($dependentes as $dep) {
				if ($dep != '') {
					$sql = "insert into cliente_dependentes (id_titular, id_dependente) values ";
					$sql .= "(".$id_cliente.",".$dep.")";
					$conexao -> query($sql);
				}
			}
			
			//copiar endereço para os dependentes
			$sql = "update cliente
						inner join cliente_dependentes on cliente_dependentes.id_dependente = cliente.id
							set
								cliente.endereco = '".campo_sql($valores['endereco'])."',
								cliente.numero = '".campo_sql($valores['numero'])."',
								cliente.complemento = '".campo_sql($valores['complemento'])."',
								cliente.bairro = '".campo_sql($valores['bairro'])."',
								cliente.cep = '".campo_sql($valores['cep'])."',
								cliente.id_cidade = nao_obrigatorio('".$valores['cidade']."')
									where cliente_dependentes.id_titular = ".$id_cliente;
			$conexao -> query($sql);
		}
		
		if ($valores['check_titular'] == 1) {
			$sql = "insert into cliente_dependentes (id_titular, id_dependente) values ";
			$sql .= "(".$valores['titular'].",".$id_cliente.")";
			$conexao -> query($sql);
			
			//copiar endereço do titular
			$sql = "update cliente
						inner join cliente_dependentes on cliente_dependentes.id_dependente = cliente.id
						inner join cliente titular on titular.id = cliente_dependentes.id_titular
							set
								cliente.endereco = titular.endereco,
								cliente.numero = titular.numero,
								cliente.complemento = titular.complemento,
								cliente.bairro = titular.bairro,
								cliente.cep = titular.cep,
								cliente.id_cidade = titular.id_cidade
									where cliente_dependentes.id_dependente = ".$id_cliente;
			$conexao -> query($sql);
		}

		if ($valores['lista_telefones'] != '') {
			$telefones = explode("|", $valores['lista_telefones']);
			$c = 0;
			foreach ($telefones as $tel) {
				$principal = 0;
				if ($c == $_POST['telefone_principal']) {
					$principal = 1;
				}
				$t = explode(",", $tel);
				if (count($t) > 1) {
					$sql = "insert into cliente_telefones (telefone, principal, id_tipo, id_cliente) values ";
					$sql .= "('".campo_sql($t[0])."',".$principal.",".$t[1].",".$id_cliente.")";
					$conexao -> query($sql);
				}
				$c++;
			}
		}
		
		$conexao -> commit();
	}
	
	//alterar cliente
	function alterar($valores) {
		require_once('conexao.class.php');
		$conexao = new conexao();
		require_once('controles/funcoes.php');
		
		$conexao -> begin();

		$nascimento = '';
		$data_nascimento = data_sql($valores['data_nascimento']);
		if (empty($data_nascimento)) {
			$nascimento = "data_nascimento = null,";
		}
		else {
			$nascimento = "data_nascimento = '".$data_nascimento."',";
		}

		$casamento = '';
		$data_casamento = data_sql($valores['data_casamento']);
		if (empty($data_casamento)) {
			$casamento = "data_casamento = null,";
		}
		else {
			$casamento = "data_casamento = '".$data_casamento."',";
		}
		
		$sql = "update cliente set
					cliente = '".campo_sql($valores['cliente'])."',
					sexo = '".campo_sql($valores['sexo'])."',
					cpf = '".campo_sql($valores['cpf'])."',
					rg = '".campo_sql($valores['rg'])."',
					passaporte = '".campo_sql($valores['passaporte'])."',
					".$nascimento."
					".$casamento."
					endereco = '".campo_sql($valores['endereco'])."',
					numero = '".campo_sql($valores['numero'])."',
					complemento = '".campo_sql($valores['complemento'])."',
					bairro = '".campo_sql($valores['bairro'])."',
					cep = '".campo_sql($valores['cep'])."',
					email = '".email_sql($valores['email'])."',
					id_cidade = nao_obrigatorio('".$valores['cidade']."'),
					id_orgao_emissor = nao_obrigatorio('".$valores['orgao_emissor']."'),
					id_situacao = ".campo_sql($valores['situacao'])."
						where id = ".$valores['id_cliente'];
		$conexao -> query($sql);
		
		//possui titular
		if ($valores['check_titular'] == 1) {
			$sql = "insert into cliente_dependentes (id_titular, id_dependente) values ";
			$sql .= "(".$valores['titular'].",".$valores['id_cliente'].")";
			$sql .= " on duplicate key update id_titular = ".$valores['titular'];
			$conexao -> query($sql);
			
			//copiar endereço do titular
			$sql = "update cliente
						inner join cliente_dependentes on cliente_dependentes.id_dependente = cliente.id
						inner join cliente titular on titular.id = cliente_dependentes.id_titular
							set
								cliente.endereco = titular.endereco,
								cliente.numero = titular.numero,
								cliente.complemento = titular.complemento,
								cliente.bairro = titular.bairro,
								cliente.cep = titular.cep,
								cliente.id_cidade = titular.id_cidade
									where cliente_dependentes.id_dependente = ".$valores['id_cliente'];
			$conexao -> query($sql);
		}
		//nao possui titular
		else {
			$sql = "delete from cliente_dependentes where id_dependente = ".$valores['id_cliente'];
			$conexao -> query($sql);
		}
		
		//possui dependente
		if ($valores['check_dependente'] == 1) {
			//apagar
			if ($valores['excluir_dependentes'] != '') {
				$excluir = explode("|",$valores['excluir_dependentes']);
				$sql = "delete from cliente_dependentes where id in(";
				foreach ($excluir as $e) {
					$sql .= $e.",";
				}
				$sql = substr($sql,0,-1).")";
				$conexao -> query($sql);
			}
			
			//incluir
			$dependentes = explode("|", $valores['lista_dependentes']);
			foreach ($dependentes as $dep) {
				if ($dep != '') {
					$sql = "insert into cliente_dependentes (id_titular, id_dependente) values ";
					$sql .= "(".$valores['id_cliente'].",".$dep.")";
					$sql .= " on duplicate key update id_titular = ".$valores['id_cliente'];
					$conexao -> query($sql);
				}
			}
		
			//copiar endereço para os dependentes
			$sql = "update cliente
						inner join cliente_dependentes on cliente_dependentes.id_dependente = cliente.id
							set
								cliente.endereco = '".campo_sql($valores['endereco'])."',
								cliente.numero = '".campo_sql($valores['numero'])."',
								cliente.complemento = '".campo_sql($valores['complemento'])."',
								cliente.bairro = '".campo_sql($valores['bairro'])."',
								cliente.cep = '".campo_sql($valores['cep'])."',
								cliente.id_cidade = nao_obrigatorio('".$valores['cidade']."')
									where cliente_dependentes.id_titular = ".$valores['id_cliente'];
			$conexao -> query($sql);
		}
		//nao possui dependente
		else {
			$sql = "delete from cliente_dependentes where id_titular = ".$valores['id_cliente'];
			$conexao -> query($sql);
		}
		
		if ($valores['excluir_telefones'] != '') {
			$excluir = explode("|",$valores['excluir_telefones']);
			$sql = "delete from cliente_telefones where id in(";
			foreach ($excluir as $e) {
				$sql .= $e.",";
			}
			$sql = substr($sql,0,-1).")";
			$conexao -> query($sql);
		}
		
		$c = 0;
		if ($valores['lista_telefones'] != '') {
			$telefones = explode("|", $valores['lista_telefones']);
			foreach ($telefones as $tel) {
				$principal = 0;
				if ($c == $_POST['telefone_principal']) {
					$principal = 1;
				}
				$t = explode(",", $tel);
				if (count($t) > 1) {
					//telefone nao cadastrado
					if ($_POST['telefone_'.$c] == '') {
						$sql = "insert into cliente_telefones (telefone, principal, id_tipo, id_cliente) values ";
						$sql .= "('".campo_sql($t[0])."',".$principal.",".$t[1].",".$valores['id_cliente'].")";
						$conexao -> query($sql);
					}
					//telefone ja cadastrado
					else {
						$sql = "update cliente_telefones set principal = ".$principal." where id = ".$_POST['telefone_'.$c];
						$conexao -> query($sql);
					}
				}
				$c++;
			}
		}
		
		$conexao -> commit();
	}
	
	function lista_etiquetas() {
		require_once('conexao.class.php');
		$conexao = new conexao();
		
		$sql = "select cliente, endereco, numero, complemento, bairro, cep, cidade.cidade, estado.sigla
					from cliente
						left join cliente_dependentes on cliente_dependentes.id_dependente = cliente.id
						inner join cidade on cidade.id = cliente.id_cidade
						inner join estado on cidade.id_estado = estado.id
							where id_situacao = 1
							and cliente_dependentes.id_dependente is null
								-- order by cliente.cliente";
		$r = $conexao -> query($sql);
		return $r;
	}

	function lista_geral() {
		require_once('conexao.class.php');
		$conexao = new conexao();
		
		$sql = "select cliente.id, cliente.cliente, count(viagem_cliente.id) as viagens
					from cliente
				    	left join viagem_cliente on viagem_cliente.id_cliente = cliente.id
				      		group by cliente.id
								-- order by cliente.cliente";
		$r = $conexao -> query($sql);
		return $r;
	}

	function lista_contatos() {
		require_once('conexao.class.php');
		$conexao = new conexao();
		
		$sql = "select cliente, group_concat(cliente_telefones.telefone separator ', ') as telefones, email
					from cliente
						left join cliente_telefones on cliente.id = id_cliente
							group by cliente.id
								-- order by cliente";
		$r = $conexao -> query($sql);
		return $r;
	}
	
}
?>