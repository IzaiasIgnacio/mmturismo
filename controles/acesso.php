<?php
function controle_acesso($acao) {
	switch($acao) {
		case 'login':
			if ($_POST) {
				require_once('classes/acesso.class.php');
				$acesso = new acesso();
				
				$r = $acesso -> login($_POST);
				if (mysql_num_rows($r) > 0) {
					$nome = mysql_result($r,0,'nome');
					$login = mysql_result($r,0,'login');
					$id = mysql_result($r,0,'id');
					$_SESSION['usuario'] = $nome;
					$_SESSION['login'] = $login;
					$_SESSION['id_usuario'] = $id;
					setcookie("usuario",$nome,0);
					setcookie("login",$login,0);
					setcookie("id_usuario",$id,0);
					echo "<script>window.location='index.php'</script>";
				}
				else {
					echo "<script>caixa_mensagem('Aviso','Usu&aacute;rio n&atilde;o encontrado');</script>";
				}
			}
		break;
		case 'sessao':
			//pagina restrita e sessao encerrada
			if ($_SESSION['usuario'] == '' && ($_GET['pag'] != '' && $_GET['pag'] != 'views/login.php')) {
				//cookie vazio
				if ($_COOKIE['usuario'] == '') {
					echo utf8_decode("<script>alert('Sessão encerrada');</script>");
					echo "<script>window.location='?pag=views/login.php'</script>";
				}
				//restaurar sessao
				else {
					$_SESSION['usuario'] = $_COOKIE['usuario'];
					$_SESSION['login'] = $_COOKIE['login'];
					$_SESSION['id_usuario'] = $_COOKIE['id_usuario'];
				}
			}
		break;
		case 'alterar_dados':
			if ($_POST) {
				require_once('classes/acesso.class.php');
				$acesso = new acesso();
				
				$r = $acesso -> alterar_dados($_POST);
				if ($r == 0) {
					echo "<script>caixa_mensagem('Aviso','Senha atual inv&aacute;lida');</script>";
				}
				else {
					$_SESSION['usuario'] = $_POST['nome'];
					setcookie("usuario",$_POST['nome'],0);
					$_SESSION['login'] = $_POST['login'];
					setcookie("login",$_POST['login'],0);
					echo "<script>caixa_mensagem('Aviso','Dados atualizados');</script>";
				}
			}
		break;
		case 'menu':
			require_once('classes/acesso.class.php');
			$acesso = new acesso();
			
			global $menu;
			
			$menu = $acesso -> menu();
		break;
	}
}
?>