<?php
function campo_sql($valor) {
	return mb_strtoupper(mysql_real_escape_string(str_replace("_","",utf8_decode($valor))),'latin1');
}

function email_sql($valor) {
	return mb_strtoupper(mysql_real_escape_string($valor),'latin1');
}

function data_sql($data) {
	if ($data != '') {
		$d = explode("/",$data);
		return mysql_real_escape_string($d[2]."-".$d[1]."-".$d[0]);
	}
	else {
		return 'null';
	}
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
?>