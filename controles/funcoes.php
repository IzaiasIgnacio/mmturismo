<?php
function campo_sql($valor) {
	return mb_strtoupper(str_replace("_","",$valor),'utf8');
}

function email_sql($valor) {
	return mb_strtoupper($valor,'utf8');
}

function data_sql($data) {
	if (!empty($data)) {
		$d = explode("/",$data);
		return $d[2]."-".$d[1]."-".$d[0];
	}
	else {
		return null;
	}
}

function nao_obrigatorio_sql($valor) {
	if (empty($valor)) {
		return null;
	}

	return $valor;
}

function idade($nascimento) {
	list($dia, $mes, $ano) = explode('/', $nascimento);
	if ($ano == '0000') {
		return true;
	}
	$hoje = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
	$nascimento = mktime( 0, 0, 0, $mes, $dia, $ano);
	$idade = floor((((($hoje - $nascimento) / 60) / 60) / 24) / 365.25);

	return ($idade > 5);
}