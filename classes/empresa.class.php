<?php
class empresa {
	
	//buscar dados de uma empresa
	function buscar_dados($empresa) {
		require_once('database.class.php');
		$conexao = new database();
	
		$sql = "select empresa.*, cidade.id_estado, cidade.id as cidade,
				group_concat(empresa_tipo_transporte.id_tipo_transporte) as tipos
					from empresa
						left join empresa_tipo_transporte on empresa_tipo_transporte.id_empresa = empresa.id
						left join cidade on cidade.id = empresa.id_cidade
							where empresa.id = ".$empresa;
	
		return $conexao -> query($sql)[0];
	}
	
	//buscar historico de reservas de uma empresa
	function buscar_historico($valores) {
		require_once('database.class.php');
		$conexao = new database();
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
	
		$sql = "select sql_calc_found_rows viagem_transporte.*, viagem.viagem,
				replace(viagem_transporte.valor,'.',',') as valor,
				date_format(viagem.data_saida,'%d/%m/%Y') as data_saida
  					from viagem_transporte
    					inner join viagem on viagem_transporte.id_viagem = viagem.id
						inner join empresa_tipo_transporte on empresa_tipo_transporte.id = viagem_transporte.id_empresa_tipo_transporte
      						where empresa_tipo_transporte.id_empresa = ".$valores['empresa']."
							".$comp."
      							order by data_saida desc
      								limit ".$valores['inicio'].",10";
		$this->lista = $conexao -> query($sql);
	
		$sql = "select found_rows() as total";
		$this->total = $conexao -> query($sql);
	}
	
	//buscar empresa por nome
	function buscar($nome) {
		require_once('database.class.php');
		$conexao = new database();
	
		$sql = "select empresa.id, empresa.empresa
					from empresa
						where empresa like '%".utf8_decode($nome)."%'
							order by empresa
								limit 10";
		return $conexao -> query($sql);
	}
	
	//listar empresas de um tipo de transporte
	function listar($tipo) {
		require_once('database.class.php');
		$conexao = new database();
	
		$sql = "select empresa_tipo_transporte.id, empresa.empresa
					from empresa
						inner join empresa_tipo_transporte on empresa_tipo_transporte.id_empresa = empresa.id
							where empresa_tipo_transporte.id_tipo_transporte = ".$tipo."
									order by empresa";
		return $conexao -> query($sql);
	}
	
	//buscar telefones de uma empresa
	function buscar_telefones($empresa) {
		require_once('database.class.php');
		$conexao = new database();
	
		$sql = "select empresa_telefones.id, empresa_telefones.telefone
					from empresa_telefones
						where empresa_telefones.id_empresa = ".$empresa;
		return $conexao -> query($sql);
	}
	
	//buscar bancos de uma empresa
	function buscar_bancos($empresa) {
		require_once('database.class.php');
		$conexao = new database();
	
		$sql = "select empresa_bancos.*
					from empresa_bancos
						where empresa_bancos.id_empresa = ".$empresa;
		return $conexao -> query($sql);
	}
	
	//alterar empresa
	function alterar($valores) {
		require_once('database.class.php');
		$conexao = new database();
		require_once('controles/funcoes.php');
	
		$conexao -> begin();

		$dados = [
			'empresa' => campo_sql($valores['empresa']),
			'endereco' => campo_sql($valores['endereco']),
			'numero' => campo_sql($valores['numero']),
			'complemento' => campo_sql($valores['complemento']),
			'bairro' => campo_sql($valores['bairro']),
			'cep' => campo_sql($valores['cep']),
			'cnpj' => campo_sql($valores['cnpj']),
			'site' => campo_sql($valores['site']),
			'email' => email_sql($valores['email']),
			'id_cidade' => campo_sql($valores['cidade']),
			'id_empresa' => $valores['id_empresa']
		];
	
		$sql = "update empresa set
					empresa = :empresa,
					endereco = :endereco,
					numero = :numero,
					complemento = :complemento,
					bairro = :bairro,
					cep = :cep,
					cnpj = :cnpj,
					site = :site,
					email = :email,
					id_cidade = :id_cidade
						where id = :id_empresa";
		$conexao -> execute($sql, $dados);
	
		if ($valores['excluir_tipos'] != '') {
			$excluir = explode("|",$valores['excluir_tipos']);
			$sql = "delete from empresa_tipo_transporte where id_empresa = ".$valores['id_empresa']." and id_tipo_transporte in(";
			foreach ($excluir as $e) {
				$sql .= $e.",";
			}
			$sql = substr($sql,0,-1).")";
			$conexao -> execute($sql);
		}
		
		if (count($valores['tipo_transporte']) > 0) {
			foreach ($valores['tipo_transporte'] as $e) {
				$sql = "insert into empresa_tipo_transporte (id_tipo_transporte, id_empresa) values ";
				$sql .= "('".campo_sql($e)."',".$valores['id_empresa'].") on duplicate key update id = id";
				$conexao -> execute($sql);
			}
		}
		
		if ($valores['excluir_telefones'] != '') {
			$excluir = explode("|",$valores['excluir_telefones']);
			$sql = "delete from empresa_telefones where id in(";
			foreach ($excluir as $e) {
				$sql .= $e.",";
			}
			$sql = substr($sql,0,-1).")";
			$conexao -> execute($sql);
		}
	
		$c = 0;
		if ($valores['lista_telefones'] != '') {
			$telefones = explode("|", $valores['lista_telefones']);
			foreach ($telefones as $tel) {
				if ($tel != '') {
					//telefone nao cadastrado
					if ($_POST['telefone_'.$c] == '') {
						$sql = "insert into empresa_telefones (telefone, id_empresa) values ";
						$sql .= "('".campo_sql($tel)."',".$valores['id_empresa'].")";
						$conexao -> execute($sql);
					}
				}
				$c++;
			}
		}
	
		if ($valores['excluir_bancos'] != '') {
			$excluir = explode("|",$valores['excluir_bancos']);
			$sql = "delete from empresa_bancos where id in(";
			foreach ($excluir as $e) {
				$sql .= $e.",";
			}
			$sql = substr($sql,0,-1).")";
			$conexao -> execute($sql);
		}
	
		$c = 0;
		if ($valores['lista_bancos'] != '') {
			$bancos = explode("|", $valores['lista_bancos']);
			foreach ($bancos as $ban) {
				$b = explode(",", $ban);
				if (count($b) > 1) {
					//banco nao cadastrado
					if ($_POST['banco_'.$c] == '') {
						$sql = "insert into empresa_bancos (banco, agencia, conta, titular, cpf_cnpj, id_empresa) values ";
						$sql .= "('".campo_sql($b[0])."','".campo_sql($b[1])."','".campo_sql($b[2])."','".campo_sql($b[3])."',
								'".campo_sql($b[4])."',".$valores['id_empresa'].")";
						$conexao -> execute($sql);
					}
				}
				$c++;
			}
		}
	
		$conexao -> commit();
	}
	
	//cadastrar empresa de transporte
	function cadastrar($valores) {
		require_once('database.class.php');
		$conexao = new database();
		require_once('controles/funcoes.php');
		
		$conexao -> begin();

		$dados = [
			'empresa' => campo_sql($valores['empresa']),
			'endereco' => campo_sql($valores['endereco']),
			'numero' => campo_sql($valores['numero']),
			'complemento' => campo_sql($valores['complemento']),
			'bairro' => campo_sql($valores['bairro']),
			'cep' => campo_sql($valores['cep']),
			'cnpj' => campo_sql($valores['cnpj']),
			'site' => campo_sql($valores['site']),
			'email' => email_sql($valores['email']),
			'id_cidade' => $valores['cidade']
		];

		$sql = "insert into empresa (empresa, endereco, numero, complemento, bairro, cep, cnpj, site, email, id_cidade) values 
				(:empresa, :endereco, :numero, :complemento, :bairro, :cep, :cnpj, :site, :email, :id_cidade)";
		$conexao -> execute($sql, $dados);
		$id_empresa = $conexao->lastInsertId();
		
		if (is_array($valores['tipo_transporte']) && count($valores['tipo_transporte']) > 0) {
			foreach ($valores['tipo_transporte'] as $e) {
				$sql = "insert into empresa_tipo_transporte (id_tipo_transporte, id_empresa) values ";
				$sql .= "('".campo_sql($e)."',".$id_empresa.")";
				$conexao -> execute($sql);
			}
		}
		
		if ($valores['lista_telefones'] != '') {
			$telefones = explode("|", $valores['lista_telefones']);
			foreach ($telefones as $tel) {
				if ($tel != '') {
					$sql = "insert into empresa_telefones (telefone, id_empresa) values ";
					$sql .= "('".campo_sql($tel)."',".$id_empresa.")";
					$conexao -> execute($sql);
				}
			}
		}
		
		if ($valores['lista_bancos'] != '') {
			$bancos = explode("|", $valores['lista_bancos']);
			foreach ($bancos as $ban) {
				$b = explode(",", $ban);
				if (count($b) > 1) {
					$sql = "insert into empresa_bancos (banco, agencia, conta, titular, cpf_cnpj, id_empresa) values ";
					$sql .= "('".campo_sql($b[0])."','".campo_sql($b[1])."','".campo_sql($b[2])."','".campo_sql($b[3])."','".campo_sql($b[4])."',".$id_empresa.")";
					$conexao -> execute($sql);
				}
			}
		}
		
		$conexao -> commit();
	}
	
}
?>