<?php
require_once('controles/orgao_emissor.php');
controle_orgao_emissor('cadastrar');
?>
<script type="text/javascript">
	//adicionar ou editar
	var acao = '';
	//editar
	var id_orgao_emissor = '';
	$().ready(function() {
		//configuracao de exibicao do formulario de orgao emissor
		$("#formulario_orgao_emissor").dialog({
			buttons: {
				Salvar: function() {
					var enviar = 1;
					//formulario invalido
					if ($("#orgao_emissor").val() == '') {
						//exibir mensagens de erro
						$("#orgao_emissor").addClass('error');
						$("#orgao_emissor").tooltip({disabled: false});
						$("#orgao_emissor").tooltip("open");
						//nao enviar formulario
						enviar = 0;
					}
					if ($("#sigla").val() == '') {
						//exibir mensagens de erro
						$("#sigla").addClass('error');
						$("#sigla").tooltip({disabled: false});
						$("#sigla").tooltip("open");
						//nao enviar formulario
						enviar = 0;
					}

					if (enviar == 1) {
						//esconder mensagem de validacao
						$("#orgao_emissor").removeClass('error');
						$("#orgao_emissor").tooltip("close");
						$("#sigla").removeClass('error');
						$("#sigla").tooltip("close");
						if (acao == 'adicionar') {
							$.post('controles/orgao_emissor.jquery.php',{acao:'cadastrar_orgao_emissor',
							orgao_emissor:$("#orgao_emissor").val(),sigla:$("#sigla").val()},
							function(resposta) {
								var linha = "<tr class='add_linha'>";
								linha += "	<td>"+$("#sigla").val().toUpperCase()+"</td>";
								linha += "	<td>"+$("#orgao_emissor").val()+"</td>";
								linha += "	<td><div class='btn_editar' id='editar_"+resposta+"'></td>";
								linha += "	<td><div class='btn_remover' id='remover_"+resposta+"'></div></td>";
								linha += "</tr>";
								adicionar_linha(linha,'orgaos');
								$("#formulario_orgao_emissor").dialog("close");
							});
						}
						//editar
						else {
							$.post('controles/orgao_emissor.jquery.php',{acao:'editar_orgao_emissor',
							orgao_emissor:$("#orgao_emissor").val(),sigla:$("#sigla").val(),id_orgao_emissor:id_orgao_emissor},
							function(resposta) {
								$("#editar_"+id_orgao_emissor).closest('tr').children('td:eq(0)').html($("#sigla").val().toUpperCase());
								$("#editar_"+id_orgao_emissor).closest('tr').children('td:eq(1)').html($("#orgao_emissor").val());
								$("#formulario_orgao_emissor").dialog("close");
							});
						}
					}
				},
				Cancelar: function() {
					//esconder mensagem de validacao
					$("#orgao_emissor").removeClass('error');
					$("#orgao_emissor").tooltip("close");
					$("#sigla").removeClass('error');
					$("#sigla").tooltip("close");
					$(this).dialog("close");
				}
			}
		});
		
		//botao adicionar orgao emissor
		$("#add_orgao_emissor").click(function() {
			acao = 'adicionar';
			//esconder barra de titulo do lightbox
			$(".ui-dialog-titlebar").hide();
			//esconder mensagens de erro
			$("#orgao_emissor").removeClass('error');
			$("#sigla").removeClass('error');
			//resetar formulario
			$("#orgao_emissor").val('');
			$("#sigla").val('');
			$("#formulario_orgao_emissor").dialog("open");
		});

		//botao editar orgao emissor
		$("#orgaos").on('click','.btn_editar',function() {
			acao = 'editar';
			$("#sigla").val($(this).closest('tr').children('td:eq(0)').html());
			$("#orgao_emissor").val($(this).closest('tr').children('td:eq(1)').html());
			var id = $(this).attr('id').split("_");
			id_orgao_emissor = id[1];
			$("#formulario_orgao_emissor").dialog("open");
		});
		
		//botao remover orgao emissor
		$("#orgaos").on('click','.btn_remover',function() {
			var linha = $(this).closest('tr');
			var id = $(this).attr('id').split("_");
			$.post('controles/orgao_emissor.jquery.php',{acao:'verificar_orgao_emissor',orgao:id[1]},
			function(resposta) {
				if (resposta == 0) {
					$.post('controles/orgao_emissor.jquery.php',{acao:'excluir_orgao_emissor',orgao:id[1]},
					function(resposta) {
						if (resposta == 1) {
							//efeito de exclusao
							linha.switchClass("linha","remover_linha",500,function() {
								//esconder linha da tabela
								$(this).fadeOut();
							});
						}
						else {
							caixa_mensagem('Aviso','Erro ao excluir órgão emissor');
						}
					});
				}
				else {
					caixa_mensagem('Aviso','N&atilde;o &eacute; poss&iacute;vel excluir esse &oacute;rg&atilde;o.<br>Ele est&aacute; associado a um ou mais clientes');
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
	<div id='formulario_orgao_emissor' class='dialogo'>
		<fieldset id='fieldset_orgao_emissor'>
			<span class='titulo_dados'>&Oacute;rg&atilde;os Emissores</span>
			<label class='label_campo esquerda'>Sigla: </label>
			<input type='text' name='sigla' id='sigla' size='55' maxlength='50'>
			<div class='espaco'></div>
			<label class='label_campo esquerda'>&Oacute;rg&atilde;o Emissor: </label>
			<input type='text' name='orgao_emissor' id='orgao_emissor' size='55' maxlength='50'>
		</fieldset>
	</div>
	<div class='titulo_tela'>Cadastro de &Oacute;rg&atilde;os Emissores</div>
	<div class='conteudo'>
		<fieldset id='fieldset_orgao_emissor'>
			<span class='titulo_dados'>&Oacute;rg&atilde;os Emissores</span>
			<table id='orgaos' class='lista'>
					<thead>
						<tr>
							<td width='28%'>Sigla</td>
							<td width='28%'>&Oacute;rg&atilde;o Emissor</td>
							<td width='8%'>Editar</td>
							<td width='8%'>Remover</td>
						</tr>
					</thead>
					<?php if (mysql_num_rows($lista_orgao_emissor) > 0) { ?>
						<tr style='display:none' class='linha'>
							<td colspan='5' class='vazio'>Nenhum &oacute;rg&atilde;o informado</td>
						</tr>
						<?php while ($l = mysql_fetch_array($lista_orgao_emissor)) { ?>
							<tr class='linha'>
								<td><?php echo utf8_encode($l['sigla']); ?></td>
								<td><?php echo utf8_encode($l['orgao_emissor']); ?></td>
								<td><div class='btn_editar' id='editar_<?php echo $l['id']; ?>'></div></td>
								<td><div class='btn_remover' id='remover_<?php echo $l['id']; ?>'></div></td>
							</tr>	
						<?php } ?>	
					<?php } else { ?>
						<tr class='linha'>
							<td colspan='5' class='vazio'>Nenhum &oacute;rg&atilde;o emissor informado</td>
						</tr>
					<?php } ?>
				</table>
				<span class='btn_adicionar' id='add_orgao_emissor'>Adicionar &Oacute;rg&atilde;o Emissor</span>
		</fieldset>
	</div>
</form>