<?php
class restaurante {
	
	//buscar dados de um restaurante
	function buscar_dados($restaurante) {
		require_once('conexao.class.php');
		$conexao = new conexao();
	
		$sql = "select restaurante.*, cidade.id_estado, cidade.id as cidade
					from restaurante
						left join cidade on cidade.id = restaurante.id_cidade
							where restaurante.id = ".$restaurante;
	
		$r = $conexao -> query($sql);
		return $r;
	}
	
	//buscar historico de reservas de um restaurante
	function buscar_historico($valores) {
		require_once('conexao.class.php');
		$conexao = new conexao();
		require_once('../controles/funcoes.php');
	
		$comp = '';
		if ($valores['data_inicial'] != '') {
			$comp .= " and viagem_restaurante.data >= '".data_sql($valores['data_inicial'])."'";
		}
		if ($valores['data_final'] != '') {
			$comp .= " and viagem_restaurante.data <= '".data_sql($valores['data_final'])."'";
		}
		if ($valores['viagem'] != '') {
			$comp .= " and viagem.viagem like '%".campo_sql($valores['viagem'])."%'";
		}
	
		$sql = "select sql_calc_found_rows viagem_restaurante.*, viagem.viagem,
				replace(viagem_restaurante.valor,'.',',') as valor,
				date_format(viagem_restaurante.data,'%d/%m/%Y') as data
  					from viagem_restaurante
    					inner join viagem on viagem_restaurante.id_viagem = viagem.id
      						where viagem_restaurante.id_restaurante = ".$valores['restaurante']."
							".$comp."
      							order by data desc
      								limit ".$valores['inicio'].",10";
		$this->lista = $conexao -> query($sql);
	
		$sql = "select found_rows() as total";
		$this->total = $conexao -> query($sql);
	}
	
	//listar restaurantes de uma cidade
	function listar($cidade) {
		require_once('conexao.class.php');
		$conexao = new conexao();
	
		$sql = "select restaurante.id, restaurante.restaurante
					from restaurante
						where id_cidade = ".$cidade."
							order by restaurante";
		$r = $conexao -> query($sql);
		return $r;
	}
	
	//buscar restaurante por nome
	function buscar($nome) {
		require_once('conexao.class.php');
		$conexao = new conexao();
	
		$sql = "select restaurante.id, restaurante.restaurante
					from restaurante
						where restaurante like '%".utf8_decode($nome)."%'
							order by restaurante
								limit 10";
		$r = $conexao -> query($sql);
		return $r;
	}
	
	//buscar telefones de um restaurante
	function buscar_telefones($restaurante) {
		require_once('conexao.class.php');
		$conexao = new conexao();
	
		$sql = "select restaurante_telefones.id, restaurante_telefones.telefone
					from restaurante_telefones
						where restaurante_telefones.id_restaurante = ".$restaurante;
		$r = $conexao -> query($sql);
		return $r;
	}
	
	//buscar bancos de um restaurante
	function buscar_bancos($restaurante) {
		require_once('conexao.class.php');
		$conexao = new conexao();
	
		$sql = "select restaurante_bancos.*
					from restaurante_bancos
						where restaurante_bancos.id_restaurante = ".$restaurante;
		$r = $conexao -> query($sql);
		return $r;
	}
	
	//alterar restaurante
	function alterar($valores) {
		require_once('conexao.class.php');
		$conexao = new conexao();
		require_once('controles/funcoes.php');
	
		$conexao -> begin();
	
		$sql = "update restaurante set
					restaurante = '".campo_sql($valores['restaurante'])."',
					endereco = '".campo_sql($valores['endereco'])."',
					numero = '".campo_sql($valores['numero'])."',
					complemento = '".campo_sql($valores['complemento'])."',
					bairro = '".campo_sql($valores['bairro'])."',
					cep = '".campo_sql($valores['cep'])."',
					cnpj = '".campo_sql($valores['cnpj'])."',
					site = '".campo_sql($valores['site'])."',
					email = '".email_sql($valores['email'])."',
					id_cidade = ".campo_sql($valores['cidade'])."
						where id = ".$valores['id_restaurante'];
		$conexao -> query($sql);
	
		if ($valores['excluir_telefones'] != '') {
			$excluir = explode("|",$valores['excluir_telefones']);
			$sql = "delete from restaurante_telefones where id in(";
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
				if ($tel != '') {
					//telefone nao cadastrado
					if ($_POST['telefone_'.$c] == '') {
						$sql = "insert into restaurante_telefones (telefone, id_restaurante) values ";
						$sql .= "('".campo_sql($tel)."',".$valores['id_restaurante'].")";
						$conexao -> query($sql);
					}
				}
				$c++;
			}
		}
	
		if ($valores['excluir_bancos'] != '') {
			$excluir = explode("|",$valores['excluir_bancos']);
			$sql = "delete from restaurante_bancos where id in(";
			foreach ($excluir as $e) {
				$sql .= $e.",";
			}
			$sql = substr($sql,0,-1).")";
			$conexao -> query($sql);
		}
	
		$c = 0;
		if ($valores['lista_bancos'] != '') {
			$bancos = explode("|", $valores['lista_bancos']);
			foreach ($bancos as $ban) {
				$b = explode(",", $ban);
				if (count($b) > 1) {
					//banco nao cadastrado
					if ($_POST['banco_'.$c] == '') {
						$sql = "insert into restaurante_bancos (banco, agencia, conta, titular, cpf_cnpj, id_restaurante) values ";
						$sql .= "('".campo_sql($b[0])."','".campo_sql($b[1])."','".campo_sql($b[2])."','".campo_sql($b[3])."',
								'".campo_sql($b[4])."',".$valores['id_restaurante'].")";
						$conexao -> query($sql);
					}
				}
				$c++;
			}
		}
	
		$conexao -> commit();
	}
	
	//cadastrar restaurante
	function cadastrar($valores) {
		require_once('conexao.class.php');
		$conexao = new conexao();
		require_once('controles/funcoes.php');
		
		$conexao -> begin();

		$sql = "insert into restaurante (restaurante, endereco, numero, complemento, bairro, cep, cnpj, site, email, id_cidade) values 
				('".campo_sql($valores['restaurante'])."', '".campo_sql($valores['endereco'])."', '".campo_sql($valores['numero'])."',
				'".campo_sql($valores['complemento'])."', '".campo_sql($valores['bairro'])."', '".campo_sql($valores['cep'])."',
				'".campo_sql($valores['cnpj'])."', '".campo_sql($valores['site'])."', '".email_sql($valores['email'])."',
				".$valores['cidade'].")";
		$r = $conexao -> query($sql);
		$id_restaurante = mysql_insert_id();
		
		if ($valores['lista_telefones'] != '') {
			$telefones = explode("|", $valores['lista_telefones']);
			foreach ($telefones as $tel) {
				if ($tel != '') {
					$sql = "insert into restaurante_telefones (telefone, id_restaurante) values ";
					$sql .= "('".campo_sql($tel)."',".$id_restaurante.")";
					$conexao -> query($sql);
				}
			}
		}
		
		if ($valores['lista_bancos'] != '') {
			$bancos = explode("|", $valores['lista_bancos']);
			foreach ($bancos as $ban) {
				$b = explode(",", $ban);
				if (count($b) > 1) {
					$sql = "insert into restaurante_bancos (banco, agencia, conta, titular, cpf_cnpj, id_restaurante) values ";
					$sql .= "('".campo_sql($b[0])."','".campo_sql($b[1])."','".campo_sql($b[2])."','".campo_sql($b[3])."','".campo_sql($b[4])."',".$id_restaurante.")";
					$conexao -> query($sql);
				}
			}
		}
		
		$conexao -> commit();
	}
	
}
?>