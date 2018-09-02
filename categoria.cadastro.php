<?php
require_once('controles/categoria.php');
controle_categoria('cadastro');
?>
<script>
	var id_categoria = '';
	var acao = '';
	$().ready(function() {
		//configuracao de exibicao do formulario de categoria
		$("#formulario_categoria").dialog({
			autoOpen: false,
			closeOnEscape: false,
			dialogClass: "caixa",
			title:'Categoria',
			draggable: false,
			resizable: false,
			modal: true,
			minWidth: 600,
			buttons: {
				Salvar: function() {
					var enviar = 1;
					//formulario invalido
					if ($("#categoria").val() == '') {
						//exibir mensagens de erro
						$("#categoria").addClass('error');
						$("#categoria").tooltip({disabled: false});
						$("#categoria").tooltip("open");
						//nao enviar formulario
						enviar = 0;
					}
					if ($("#situacao").val() == '') {
						//exibir mensagens de erro
						$("#situacao").addClass('error');
						$("#situacao").tooltip({disabled: false});
						$("#situacao").tooltip("open");
						//nao enviar formulario
						enviar = 0;
					}

					if (enviar == 1) {
						//esconder mensagem de validacao
						$("#categoria").removeClass('error');
						$("#categoria").tooltip("close");
						$("#situacao").removeClass('error');
						$("#situacao").tooltip("close");
						if (acao == 'adicionar') {
							$.post('controles/categoria.jquery.php',{acao:'cadastrar_categoria',categoria:$("#categoria").val(),
							situacao:$("#situacao").val(),diretoria:<?php echo $_SESSION['categoria_pai']; ?>,
							caminho:'<?php echo $_SESSION['caminho_diretoria']; ?>',usuario:'<?php echo $_SESSION['id_usuario']; ?>'},
							function(resposta) {
								if (resposta != 0) {
									var sit = ($("#situacao").val() == 1) ? 'Publicado' : 'Despublicado';	
									var linha = "<tr class='add_linha'>";
									linha += "	<td class='td_esquerda'>"+$("#categoria").val()+"</td>";
									linha += "	<td>"+sit+"</td>";
									linha += "	<td><?php echo utf8_encode($_SESSION['usuario']); ?></td>";
									linha += "	<td><span id='editar_"+resposta+"' class='editar'></td>";
									linha += "	<td><div class='btn_remover' id='remover_"+resposta+"'></div></td>";
									linha += "</tr>";
									adicionar_linha(linha,'categorias');
									$("#formulario_categoria").dialog("close");
								}
								else {
									caixa_mensagem('Aviso','Categoria j&aacute; cadastrada');
								}
							});
						}
						//editar
						else {
							$.post('controles/categoria.jquery.php',{acao:'editar_categoria',categoria:$("#categoria").val(),
							situacao:$("#situacao").val(),diretoria:<?php echo $_SESSION['categoria_pai']; ?>,id_categoria:id_categoria,
							caminho:'<?php echo $_SESSION['caminho_diretoria']; ?>',usuario:'<?php echo $_SESSION['id_usuario']; ?>'},
							function(resposta) {
								if (resposta != 0) {
									var sit = ($("#situacao").val() == 1) ? 'Publicado' : 'Despublicado';
									$("#editar_"+id_categoria).closest('tr').children('td:eq(0)').html($("#categoria").val());
									$("#editar_"+id_categoria).closest('tr').children('td:eq(1)').html(sit);
									$("#formulario_categoria").dialog("close");
								}
								else {
									caixa_mensagem('Aviso','Categoria j&aacute; cadastrada');
								}
							});
						}
					}
				},
				Cancelar: function() {
					//esconder mensagem de validacao
					$("#categoria").removeClass('error');
					$("#categoria").tooltip("close");
					$("#situacao").removeClass('error');
					$("#situacao").tooltip("close");
					$(this).dialog("close");
				}
			}
		});
		
		//adicionar categoria
		$("#adicionar_categoria").click(function() {
			acao = 'adicionar';
			$("#categoria").val('');
			$("#situacao").val('');
			$("#formulario_categoria").dialog("open");
		});
		
		//editar categoria
		$("#categorias").on('click','.editar',function() {
			acao = 'editar';
			$("#categoria").val($(this).closest('tr').children('td:eq(0)').html());
			var situacao = ($(this).closest('tr').children('td:eq(1)').html() == 'Publicado') ? '1' : '0';
			$("#situacao").val(situacao);
			var id = $(this).attr('id').split("_");
			id_categoria = id[1];
			$("#formulario_categoria").dialog("open");
		});

		//remover categoria
		$("#categorias").on('click','.btn_remover',function() {
			var id = $(this).attr('id').split('_'); 
			id_categoria = id[1];
			$("#confirmar_exclusao").dialog("open");
		});

		//configuracao de exibicao de confirmacao de exclusao
		$("#confirmar_exclusao").dialog({
			autoOpen: false,
			closeOnEscape: false,
			dialogClass: "caixa",
			title:'Excluir',
			draggable: false,
			resizable: false,
			modal: true,
			minWidth: 600,
			buttons: {
				Ok: function() {
					$.post('controles/categoria.jquery.php',{acao:'excluir_categoria',categoria:id_categoria,
					usuario:'<?php echo $_SESSION['id_usuario']; ?>'},
					function(resposta) {
						if (resposta != 0) {
							$("#confirmar_exclusao").dialog("close");
							//efeito de exclusao
							$("#remover_"+id_categoria).closest('tr').switchClass('linha','remover_linha',500,function() {
								//esconder linha da tabela
								$(this).fadeOut('normal',function() {
									//se for a ultima linha, exibir linha de tabela vazia
									if ($("#categorias tbody tr:visible").length == 0) {
										$(".vazio").closest('tr').fadeIn();
									}
								});
							});
						}
						else {
							caixa_mensagem('Aviso','Existe artigo na categoria');
						}
					});
				},
				Cancelar: function() {
					$("#confirmar_exclusao").dialog("close");
				}
			}
		});

		//mensagens de validacao
		$("#categoria").tooltip("option","content","Selecione a categoria");
		$("#situacao").tooltip("option","content","Selecione a situa&ccedil;&atilde;o do menu");
	});
</script>
<form id='form' name='form' method='post'>
	<div id='formulario_categoria' class='dialogo'>
		<label class='label_formulario'>Categoria:</label>
		<input type='text' name='categoria' id='categoria' maxlength='70'>
		<br>
		<label class='label_formulario'>Situa&ccedil;&atilde;o:</label>
		<select name='situacao' id='situacao'>
			<option value=''>Selecione</option>
			<option value='0'>Despublicado</option>
			<option value='1'>Publicado</option>
		</select>
	</div>
	<div id='confirmar_exclusao' class='dialogo'>Excluir?</div>
	<br>
	<br>
	<table id='categorias' class='lista'>
		<thead>
			<tr>
				<td>Categoria</td>
				<td>Situa&ccedil;&atilde;o</td>
				<td>Criado por</td>
				<td>Editar</td>
				<td>Remover</td>
			</tr>
		</thead>
		<tbody>
			<?php if (mysql_num_rows($lista_categorias) > 0) { ?>
				<tr style='display:none' class='linha'>
					<td colspan='6' class='vazio'>Nenhuma categoria cadastrada</td>
				</tr>
				<?php while ($l = mysql_fetch_array($lista_categorias)) { ?>
					<tr class='linha'>
						<td class='td_esquerda'><?php echo utf8_encode($l['categoria']); ?></td>
						<td><?php echo $l['situacao']; ?></td>
						<td><?php echo utf8_encode($l['nome']); ?></td>
						<td><span name='editar' id='editar_<?php echo $l['id']; ?>' class='editar'></td>
						<td><div class='btn_remover' id='remover_<?php echo $l['id']; ?>'></div></td>
					</tr>	
				<?php } ?>	
			<?php } else { ?>
				<tr class='linha'>
					<td colspan='6' class='vazio'>Nenhuma categoria cadastrada</td>
				</tr>
			<?php } ?>
		</tbody>
	</table>
	<br>
	<div style='width:100%;'>
		<span style='float:right' class='adicionar' name='adicionar_categoria' id='adicionar_categoria'>Adicionar Categoria</span>
	</div>
</form>