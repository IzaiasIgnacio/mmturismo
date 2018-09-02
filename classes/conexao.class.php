<?php
class conexao {
	
	function __construct() {
		mysql_connect("localhost","cincommt_db","mmturismo42*");
		mysql_select_db("cincommt_db");
	}
	
	function query($sql) {
		$r = mysql_query($sql) or die(mysql_error());
		return $r;
	}
	
	function begin() {
		mysql_query("BEGIN") or die(mysql_error());
	}

	function commit() {
		mysql_query("COMMIT") or die(mysql_error());
	}
	
}
?>