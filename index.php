<?php
session_start();
require_once('controles/acesso.php');
controle_acesso('sessao');
?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html;charset=utf8">
		<title>MMTurismo</title>
		<link rel='stylesheet' href='css/jquery-ui.css'>
		<link rel='stylesheet' href='css/estilo.css'>
		<link rel="stylesheet" href="css/menu.css">
		<script type="text/javascript" src='js/jquery.js'></script>
		<script type="text/javascript" src='js/jquery-ui.js'></script>
		<script type="text/javascript" src='js/jquery.maskedinput.js'></script>
		<script type="text/javascript" src='js/jquery.livequery.min.js'></script>
		<script type="text/javascript" src='js/jquery.validate.js'></script>
		<script type="text/javascript" src='js/jquery.validate.add.js'></script>
		<script type="text/javascript" src='js/jquery.maskmoney.js'></script>
		<script type="text/javascript">
			$(function() {
				//mensagens de validacao
				$("input, select").tooltip({
					items: "input, select",
					disabled: true,
					position: {
						my: "left top+2"
					},
					close: function() {
						$(this).tooltip("disable");
					}
				});

				//lightbox padrao
				$(".dialogo").dialog({
					modal: true,
					autoOpen: false,
					dialogClass: "caixa",
					minWidth: 800,
					resizable: false,
					draggable: false,
					closeOnEscape: false,
					position: {
						my: "top",
						at: "top+100",
						of: window
					},
					open: function(event,ui) {
						$("#form .error").each(function() {
							$(this).tooltip({disabled: true});
							$(this).tooltip("close");
						});
					},
					close: function(event,ui) {
						$("#form .error").each(function() {
							$(this).tooltip({disabled: true});
							$(this).tooltip("close");
						});
					}
				});

				//lightbox restaurar backup
				$("#confirmar_backup").dialog({
					buttons:{
						Sim: function() {
							window.location='?pag=controles/backup.php&acao=restaurar';
						},
						Não: function() {
							//fechar lightbox
							$(this).dialog("close");
						}
					}
				});

				//mascara letras, espaco e alguns carateres especiais
				$.mask.definitions['+'] = '[-A-Za-z áÁéÉíÍóÓúÚâÂêÊôÔãÃõÕçÇ]';
				//mascara letras, numeros, e alguns carateres especiais
				$.mask.definitions['%'] = '[-A-Za-z0-9áÁéÉíÍóÓúÚâÂêÊôÔãÃõÕçÇ]'
				//mascara letras, numeros, espaco e alguns carateres especiais;
				$.mask.definitions['&'] = '[-A-Za-z0-9 áÁéÉíÍóÓúÚâÂêÊôÔãÃõÕçÇ]';
				//mascara email letras, numeros, . e @
				$.mask.definitions['@'] = '[-A-Za-z0-9@.-_]';

				//botoes estilizado jqueryui
				$(".btn_salvar").button({
					icons: {
						primary: "ui-icon-disk"
					}
				});
				$(".btn_cancelar").button({
					icons: {
						primary: "ui-icon-cancel"
					}
				});
				$(".btn_excluir").button({
					icons: {
						primary: "ui-icon-trash"
					}
				});
				$(".btn_adicionar").button({
					icons: {
						primary: "ui-icon-plusthick"
					}
				});
				$(".btn_entrar").button({
					icons: {
						primary: "ui-icon-person"
					}
				});
				//checkboxes estilizado jqueryui
				$("input[type=checkbox]").button();

				//retirar foco dos botoes estilizados ao clicar
				$(".ui-button-text").click(function() {
					$(this).blur();
				});

				//desabilitar selecao de texto nos botoes estilizados jqueryui
				$(".ui-button-text").disableSelection();

				//botao cancelar
				$("#cancelar").click(function() {
					window.location='index.php';
				});
			});

			function restaurar_backup() {
				//esconder barra de titulo do lightbox
				$(".ui-dialog-titlebar").hide();
				$("#confirmar_backup").dialog("open");
			}

			//lightbox padrao para mensagens
			function caixa_mensagem(titulo,texto) {
				$("#mensagem").html(texto);
				$("#mensagem").dialog({
					modal: true,
					title: titulo,
					buttons: {
						OK: function() {
							$(this).dialog("close");
						}
					}
				});
				$(".ui-dialog-buttonset button").removeClass('ui-state-focus');
				$("#mensagem").dialog("open");
			}
		</script>
	</head>
	<body>
		<!--<div id='confirmar_backup' class='dialogo'>
			<fieldset>
				<span class='titulo_dados'>Restaurar Backup</span>
				<label style='padding-left:200px'><b>Tem certeza que deseja resturar o backup?</b></label>
			</fieldset>
		</div>-->
		<div id='mensagem' class="dialogo"></div>
		<?php
		if (isset($_SESSION['usuario']) && $_SESSION['usuario'] != '') {
			include('views/menu.php');
		}
		else {
			$_GET['pag'] = 'views/login.php';
		}
		if (isset($_GET['pag']) && $_GET['pag'] != '') {
			include($_GET['pag']);
		}
		?>
	</body>
</html>