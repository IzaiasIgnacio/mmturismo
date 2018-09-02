<?php
switch ($_GET['acao']) {
	case 'realizar':
		$files = glob('c:\wamp\www\mmturismo\backup\*');
		foreach($files as $file){
			if(is_file($file)) {
				unlink($file);
			}
		}
		$data = date('d-m-Y');
		$arquivo = "c:\wamp\www\mmturismo\backup\mmturismo.".$data.".sql";
		exec("cd c:\wamp\bin\mysql\mysql5.6.17\bin && mysqldump -u root mmturismo > ".$arquivo);
		echo "<script>window.open('backup/mmturismo.".$data.".sql');</script>";
		echo "<script>caixa_mensagem('MMTurismo','Backup Completo. Arquivo: mmturismo.".$data."');</script>";
	break;
	case 'restaurar':
		$files = glob('c:\wamp\www\mmturismo\backup\*');
		foreach($files as $file){
			if(is_file($file)) {
				exec("cd c:\wamp\bin\mysql\mysql5.6.17\bin && mysql -u root mmturismo < ".$file);
				$data = explode(".",$file);
				$data = explode("-",$data[1]);
				echo "<script>caixa_mensagem('MMTurismo','Backup com data de ".$data[0]."/".$data[1]."/".$data[2]." restaurado');</script>";
			}
		}
	break;
}