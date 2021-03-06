<?php
class hotel {
	
	//buscar dados de um hotel
	function buscar_dados($hotel) {
		require_once('conexao.class.php');
		$conexao = new conexao();
	
		$sql = "select hotel.*, cidade.id_estado, cidade.id as cidade
					from hotel
						left join cidade on cidade.id = hotel.id_cidade
							where hotel.id = ".$hotel;
	
		$r = $conexao -> query($sql);
		return $r;
	}
	
	//buscar historico de reservas de um hotel
	function buscar_historico($valores) {
		require_once('conexao.class.php');
		$conexao = new conexao();
		require_once('../controles/funcoes.php');
	
		$comp = '';
		if ($valores['data_inicial'] != '') {
			$comp .= " and viagem_hotel.data_chegada >= '".data_sql($valores['data_inicial'])."'";
		}
		if ($valores['data_final'] != '') {
			$comp .= " and viagem_hotel.data_chegada <= '".data_sql($valores['data_final'])."'";
		}
		if ($valores['viagem'] != '') {
			$comp .= " and viagem.viagem like '%".campo_sql($valores['viagem'])."%'";
		}
	
		$sql = "select sql_calc_found_rows viagem_hotel.*, viagem.viagem,
				replace(viagem_hotel.valor,'.',',') as valor,
				date_format(viagem_hotel.data_chegada,'%d/%m/%Y') as data_chegada
  					from viagem_hotel
    					inner join viagem on viagem_hotel.id_viagem = viagem.id
      						where viagem_hotel.id_hotel = ".$valores['hotel']."
							".$comp."
								-- order by data_chegada desc
      								limit ".$valores['inicio'].",10";
		$this->lista = $conexao -> query($sql);
	
		$sql = "select found_rows() as total";
		$this->total = $conexao -> query($sql);
	}
	
	//listar hoteis de uma cidade
	function listar($cidade) {
		require_once('conexao.class.php');
		$conexao = new conexao();
	
		$sql = "select hotel.id, hotel.hotel
					from hotel
						where id_cidade = ".$cidade."
							-- order by hotel";
		$r = $conexao -> query($sql);
		return $r;
	}
	
	//buscar hotel por nome
	function buscar($nome) {
		require_once('conexao.class.php');
		$conexao = new conexao();
	
		$sql = "select hotel.id, hotel.hotel
					from hotel
						where hotel like '%".utf8_decode($nome)."%'
							-- order by hotel
								limit 10";
		$r = $conexao -> query($sql);
		return $r;
	}
	
	//buscar telefones de um hotel
	function buscar_telefones($hotel) {
		require_once('conexao.class.php');
		$conexao = new conexao();
	
		$sql = "select hotel_telefones.id, hotel_telefones.telefone
					from hotel_telefones
						where hotel_telefones.id_hotel = ".$hotel;
		$r = $conexao -> query($sql);
		return $r;
	}
	
	//buscar bancos de um hotel
	function buscar_bancos($hotel) {
		require_once('conexao.class.php');
		$conexao = new conexao();
	
		$sql = "select hotel_bancos.*
					from hotel_bancos
						where hotel_bancos.id_hotel = ".$hotel;
		$r = $conexao -> query($sql);
		return $r;
	}
	
	//alterar hotel
	function alterar($valores) {
		require_once('conexao.class.php');
		$conexao = new conexao();
		require_once('controles/funcoes.php');
		
		$conexao -> begin();
		
		$sql = "update hotel set
					hotel = '".campo_sql($valores['hotel'])."',
					endereco = '".campo_sql($valores['endereco'])."',
					numero = '".campo_sql($valores['numero'])."',
					complemento = '".campo_sql($valores['complemento'])."',
					bairro = '".campo_sql($valores['bairro'])."',
					cep = '".campo_sql($valores['cep'])."',
					cnpj = '".campo_sql($valores['cnpj'])."',
					site = '".campo_sql($valores['site'])."',
					email = '".email_sql($valores['email'])."',
					id_cidade = ".campo_sql($valores['cidade'])."
						where id = ".$valores['id_hotel'];
		$conexao -> query($sql);
		
		if ($valores['excluir_telefones'] != '') {
			$excluir = explode("|",$valores['excluir_telefones']);
			$sql = "delete from hotel_telefones where id in(";
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
						$sql = "insert into hotel_telefones (telefone, id_hotel) values ";
						$sql .= "('".campo_sql($tel)."',".$valores['id_hotel'].")";
						$conexao -> query($sql);
					}
				}
				$c++;
			}
		}
		
		if ($valores['excluir_bancos'] != '') {
			$excluir = explode("|",$valores['excluir_bancos']);
			$sql = "delete from hotel_bancos where id in(";
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
						$sql = "insert into hotel_bancos (banco, agencia, conta, titular, cpf_cnpj, id_hotel) values ";
						$sql .= "('".campo_sql($b[0])."','".campo_sql($b[1])."','".campo_sql($b[2])."','".campo_sql($b[3])."',
								'".campo_sql($b[4])."',".$valores['id_hotel'].")";
					$conexao -> query($sql);
					}
				}
				$c++;
			}
		}
		
		$conexao -> commit();
	}
	
	//cadastrar hotel
	function cadastrar($valores) {
		require_once('conexao.class.php');
		$conexao = new conexao();
		require_once('controles/funcoes.php');
		
		$conexao -> begin();

		$sql = "insert into hotel (hotel, endereco, numero, complemento, bairro, cep, cnpj, site, email, id_cidade) values 
				('".campo_sql($valores['hotel'])."', '".campo_sql($valores['endereco'])."', '".campo_sql($valores['numero'])."',
				'".campo_sql($valores['complemento'])."', '".campo_sql($valores['bairro'])."', '".campo_sql($valores['cep'])."',
				'".campo_sql($valores['cnpj'])."', '".campo_sql($valores['site'])."', '".email_sql($valores['email'])."',
				".$valores['cidade'].")";
		$r = $conexao -> query($sql);
		$id_hotel = mysql_insert_id();
		
		if ($valores['lista_telefones'] != '') {
			$telefones = explode("|", $valores['lista_telefones']);
			foreach ($telefones as $tel) {
				if ($tel != '') {
					$sql = "insert into hotel_telefones (telefone, id_hotel) values ";
					$sql .= "('".campo_sql($tel)."',".$id_hotel.")";
					$conexao -> query($sql);
				}
			}
		}
		
		if ($valores['lista_bancos'] != '') {
			$bancos = explode("|", $valores['lista_bancos']);
			foreach ($bancos as $ban) {
				$b = explode(",", $ban);
				if (count($b) > 1) {
					$sql = "insert into hotel_bancos (banco, agencia, conta, titular, cpf_cnpj, id_hotel) values ";
					$sql .= "('".campo_sql($b[0])."','".campo_sql($b[1])."','".campo_sql($b[2])."','".campo_sql($b[3])."',
							'".campo_sql($b[4])."',".$id_hotel.")";
					$conexao -> query($sql);
				}
			}
		}
		
		$conexao -> commit();
	}
	
}
?>