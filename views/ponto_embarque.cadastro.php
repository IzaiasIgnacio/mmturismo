<?php
require_once('controles/ponto_embarque.php');
controle_ponto('cadastrar');
?>
<script type="text/javascript">
	//adicionar ou editar
	var acao = '';
	//editar
	var id_ponto = '';
	$().ready(function() {
		//configuracao de exibicao do formulario de ponto
		$("#formulario_ponto").dialog({
			buttons: {
				Salvar: function() {
					var enviar = 1;
					//formulario invalido
					if ($("#cidade").val() == '') {
						//exibir mensagens de erro
						$("#cidade").addClass('error');
						$("#cidade").tooltip({disabled: false});
						$("#cidade").tooltip("open");
						//nao enviar formulario
						enviar = 0;
					}
					if ($("#bairro").val() == '') {
						//exibir mensagens de erro
						$("#bairro").addClass('error');
						$("#bairro").tooltip({disabled: false});
						$("#bairro").tooltip("open");
						//nao enviar formulario
						enviar = 0;
					}
					if ($("#local").val() == '') {
						//exibir mensagens de erro
						$("#local").addClass('error');
						$("#local").tooltip({disabled: false});
						$("#local").tooltip("open");
						//nao enviar formulario
						enviar = 0;
					}

					if (enviar == 1) {
						//esconder mensagem de validacao
						$("#cidade").removeClass('error');
						$("#cidade").tooltip("close");
						$("#bairro").removeClass('error');
						$("#bairro").tooltip("close");
						$("#local").removeClass('error');
						$("#local").tooltip("close");
						if (acao == 'adicionar') {
							$.post('controles/ponto_embarque.jquery.php',{acao:'cadastrar_ponto',cidade:$("#cidade").val(),
							bairro:$("#bairro").val(),local:$("#local").val()},
							function(resposta) {
								var linha = "<tr class='add_linha'>";
								linha += "	<td>"+$("#cidade :selected").text()+"</td>";
								linha += "	<td>"+$("#bairro").val().toUpperCase()+"</td>";
								linha += "	<td>"+$("#local").val().toUpperCase()+"</td>";
								linha += "	<td><div class='btn_editar' id='editar_"+resposta+"'></td>";
								linha += "	<td><div class='btn_remover' id='remover_"+resposta+"'></div></td>";
								linha += "</tr>";
								adicionar_linha(linha,'pontos');
								$("#formulario_ponto").dialog("close");
							});
						}
						//editar
						else {
							$.post('controles/ponto_embarque.jquery.php',{acao:'editar_ponto',cidade:$("#cidade").val(),
							bairro:$("#bairro").val(),local:$("#local").val(),id_ponto:id_ponto},
							function(resposta) {
								$("#editar_"+id_ponto).closest('tr').children('td:eq(0)').html($("#cidade :selected").text());
								$("#editar_"+id_ponto).closest('tr').children('td:eq(1)').html($("#bairro").val());
								$("#editar_"+id_ponto).closest('tr').children('td:eq(2)').html($("#local").val());
								$("#formulario_ponto").dialog("close");
							});
						}
					}
				},
				Cancelar: function() {
					//esconder mensagem de validacao
					$("#cidade").removeClass('error');
					$("#cidade").tooltip("close");
					$("#bairro").removeClass('error');
					$("#bairro").tooltip("close");
					$("#local").removeClass('error');
					$("#local").tooltip("close");
					$(this).dialog("close");
				}
			}
		});
		
		//botao adicionar ponto
		$("#add_ponto").click(function() {
			acao = 'adicionar';
			//esconder barra de titulo do lightbox
			$(".ui-dialog-titlebar").hide();
			//esconder mensagens de erro
			$("#cidade").removeClass('error');
			$("#bairro").removeClass('error');
			$("#local").removeClass('error');
			//resetar formulario
			$("#cidade").val('');
			$("#bairro").val('');
			$("#local").val('');
			$("#formulario_ponto").dialog("open");
		});

		//botao editar ponto
		$("#pontos").on('click','.btn_editar',function() {
			acao = 'editar';
			var cidade = ($(this).closest('tr').children('td:eq(0)').html());
			$("#cidade").val(
				$('#cidade option').filter(function(){
					return $(this).html() == cidade;
				}).val()
			);
			$("#bairro").val($(this).closest('tr').children('td:eq(1)').html());
			$("#local").val($(this).closest('tr').children('td:eq(2)').html());
			var id = $(this).attr('id').split("_");
			id_ponto = id[1];
			$("#formulario_ponto").dialog("open");
		});
		
		//botao remover ponto
		$("#pontos").on('click','.btn_remover',function() {
			var linha = $(this).closest('tr');
			var id = $(this).attr('id').split("_");
			$.post('controles/ponto_embarque.jquery.php',{acao:'verificar_ponto',ponto:id[1]},
			function(resposta) {
				if (resposta == 0) {
					$.post('controles/ponto_embarque.jquery.php',{acao:'excluir_ponto',ponto:id[1]},
					function(resposta) {
						if (resposta == 1) {
							//efeito de exclusao
							linha.switchClass("linha","remover_linha",500,function() {
								//esconder linha da tabela
								$(this).fadeOut();
							});
						}
						else {
							caixa_mensagem('Aviso','Erro ao excluir ponto');
						}
					});
				}
				else {
					caixa_mensagem('Aviso','N&atilde;o &eacute; poss&iacute;vel excluir esse ponto.<br>Ele est&aacute; sendo usado em uma ou v&aacute;rias viagens');
				}
			});
		});
	});

	//adicionar nova linha em uma tabela
	function adicionar_linha(linha,tabela) {
		if ($("#"+tabela+" .vazio").is(':visible')) {
			$("#"+tabela+" .vazio").closest('tr').hide();
		}
		$("#"+tabela+" tr :last").after(linha);
		$("#"+tabela+" .add_linha").switchClass("add_linha","linha",700);
	}
</script>
<form id='form' method='post'>
	<div id='formulario_ponto' class='dialogo'>
		<fieldset id='fieldset_ponto'>
			<span class='titulo_dados'>Ponto de Embarque</span>
			<label class='label_campo esquerda'>Cidade: </label>
			<select name='cidade' id='cidade'>
				<option value=''>Selecione</option>
				<?php while ($lc = mysql_fetch_array($lista_cidade)) { ?>
				<option value='<?php echo $lc['id']; ?>'><?php echo $lc['cidade']; ?></option>
				<?php } ?>
			</select>
			<div class='espaco'></div>
			<label class='label_campo esquerda'>Bairro: </label>
			<input type='text' name='bairro' id='bairro' size='55' maxlength='50'>
			<div class='espaco'></div>
			<label class='label_campo esquerda'>Local: </label>
			<input type='text' name='local' id='local' size='55' maxlength='50'>
		</fieldset>
	</div>
	<div class='titulo_tela'>Cadastro de Ponto de Embarque</div>
	<div class='conteudo'>
		<fieldset id='fieldset_ponto'>
			<span class='titulo_dados'>Pontos de Embarque</span>
			<table id='pontos' class='lista'>
					<thead>
						<tr>
							<td width='28%'>Cidade</td>
							<td width='28%'>Bairro</td>
							<td width='28%'>Local</td>
							<td width='8%'>Editar</td>
							<td width='8%'>Remover</td>
						</tr>
					</thead>
					<?php if (mysql_num_rows($lista_ponto) > 0) { ?>
						<tr style='display:none' class='linha'>
							<td colspan='5' class='vazio'>Nenhum ponto informado</td>
						</tr>
						<?php while ($l = mysql_fetch_array($lista_ponto)) { ?>
							<tr class='linha'>
								<td><?php echo $l['cidade']; ?></td>
								<td><?php echo $l['bairro']; ?></td>
								<td><?php echo $l['local']; ?></td>
								<td><div class='btn_editar' id='editar_<?php echo $l['id']; ?>'></div></td>
								<td><div class='btn_remover' id='remover_<?php echo $l['id']; ?>'></div></td>
							</tr>	
						<?php } ?>	
					<?php } else { ?>
						<tr class='linha'>
							<td colspan='5' class='vazio'>Nenhum ponto informado</td>
						</tr>
					<?php } ?>
				</table>
				<span class='btn_adicionar' id='add_ponto'>Adicionar Ponto de Embarque</span>
		</fieldset>
	</div>
</form>