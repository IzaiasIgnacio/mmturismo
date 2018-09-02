<?php
require_once('controles/cliente.php');
controle_cliente('cadastrar');
?>
<script type="text/javascript">
	//titular do cliente
	var titular = '';
	//dependentes do cliente
	var dependentes = new Array();
	//telefones do cliente
	var telefones = new Array();
	$(function() {
		//campos obrigatorios
		$("#form").validate({
			rules: {
				cliente: 'required',
				situacao: 'required',
				numero_telefone: 'required',
				email: {
					email: true,
				},
				cpf: 'CPF'
			},
			//nao exibir mensagens de erro de validacao
			errorPlacement: function(error, element) {
				return false;
			}
		});

		//lightbox adicionar telefone
		$("#formulario_telefone").dialog({
			buttons:{
				//botao adicionar
				Adicionar: function() {
					//numero informado
					var numero = $("#numero_telefone").val();
					//tipo informado
					var tipo_telefone = $("#tipo_telefone").val();
					//flag adicionar
					var add = 1;
					//validar numero
					if (numero == '') {
						//exibir erro de validacao
						$("#numero_telefone").addClass('error');
						$("#numero_telefone").tooltip({disabled: false});
						$("#numero_telefone").tooltip("open");
						//nao adicionar
						add = 0;
					}
					//validar tipo 
					if (tipo_telefone == '') {
						//exibir erro de validacao
						$("#tipo_telefone").addClass('error');
						$("#tipo_telefone").tooltip({disabled: false});
						$("#tipo_telefone").tooltip("open");
						//nao adicionar
						add = 0;
					}
					//adicionar
					if (add == 1) {
						//adicionar ao array
						telefones[telefones.length] = new Array(numero,tipo_telefone);
						//criar linha na tabela de telefones
						var html;
						html = "<tr class='add_linha'>";
						html += "	<td>"+numero+"</td>";
						html += "	<td>"+$("#tipo_telefone :selected").text()+"</td>";
						html += "	<td><input type='radio' name='telefone_principal' value='"+(telefones.length-1)+"'></td>";
						html += "	<td><div class='btn_remover' name='telefone_"+(telefones.length-1)+"'></div></td>";
						html += "</tr>";
						adicionar_linha(html,"telefones");
						//fechar lightbox
						$(this).dialog("close");
					}
				},
				//botar cancelar
				Cancelar: function() {
					//fechar mensagens de erro de validacao
					$("#numero_telefone").tooltip("close");
					$("#tipo_telefone").tooltip("close");
					//fechar lightbox
					$(this).dialog("close");
				}
			}
		});

		//botao adicionar telefone
		$("#add_telefone").click(function() {
			//esconder barra de titulo do lightbox
			$(".ui-dialog-titlebar").hide();
			//esconder mensagens de erro
			$("#numero_telefone").removeClass('error');
			$("#tipo_telefone").removeClass('error');
			//resetar formulario
			$("#numero_telefone").val('');
			$("#tipo_telefone").val('');
			//exibir lightbox
			$("#formulario_telefone").dialog("open");
		});

		//remover item de uma lista
		$("#telefones, #tabela_titular, #tabela_dependentes").on('click','.btn_remover',function(event) {
			//posicao a ser removida
			var posicao = $(this).attr('name').split("_");
			//tabela a ser editada
			var tabela = $(this).closest('table').attr('id');
			switch (tabela) {
				//remover telefone do array
				case 'telefones':
					delete(telefones[posicao[1]]);
				break;
				//remover titular
				case 'tabela_titular':
					titular = '';
				break;
				//remover deendente do array
				case 'tabela_dependentes':
					delete(dependentes[posicao[1]]);
				break;
			}
			//efeito de exclusao
			$(this).closest('tr').switchClass("linha","remover_linha",500,function() {
				//esconder linha da tabela
				$(this).fadeOut('normal',function() {
					//se for a ultima linha, exibir linha de tabela vazia
					if ($("#"+tabela+" tbody tr:visible").length == 0) {
						$("#"+tabela+" .vazio").closest('tr').fadeIn();
					}
				});
			});
		});

		//checkbox possui titular
		$("#check_titular").click(function() {
			//esconder mensagens de erro de validacao
			$("#buscar_titular").tooltip("close");
			$("#buscar_dependente").tooltip("close");
			//cliente possui titular
			if ($(this).is(':checked')) {
				//desmarcar checkbox possui dependentes
				$("#check_dependente").attr('checked',false);
				$("label[for=check_dependente]").removeClass('ui-state-active');
				//esconder tabela de dependentes
				$("#div_dependentes").fadeOut('normal',function() {
					//exibir tabela de titular
					$("#div_titular").fadeIn();
				});
			}
			//cliente nao possui titular
			else {
				//desmarcar checkbox possui titular
				$("label[for=check_titular]").removeClass('ui-state-active ui-state-focus');
				//esconder tabela de titular
				$("#div_titular").fadeOut();
			}
		});
		//checkbox possui dependente
		$("#check_dependente").click(function() {
			//esconder mensagens de erro de validacao
			$("#buscar_dependente").tooltip("close");
			$("#buscar_titular").tooltip("close");
			//cliente possui dependente
			if ($(this).is(':checked')) {
				//desmarcar checkbox possui titular
				$("#check_titular").attr('checked',false);
				$("label[for=check_titular]").removeClass('ui-state-active');
				//esconder tabela de titular
				$("#div_titular").fadeOut('normal',function() {
					//exibir tabela de dependentes
					$("#div_dependentes").fadeIn();
				});
			}
			//cliente nao possui dependente
			else {
				//desmarcar checkbox possui dependente
				$("label[for=check_dependente]").removeClass('ui-state-active ui-state-focus');
				//esconder tabela de dependentes
				$("#div_dependentes").fadeOut();
			}
		});

		//select de estado
		$("#estado").change(function() {
			//estado selecionado
			if ($(this).val() != '') {
				//listar cidades
				$.post('controles/cidade.jquery.php',{acao:'listar_cidades',estado:$(this).val()},
				function(resposta) {
					$("#cidade").html(resposta);
				});
			}
			//nenhum estado selecionado
			else {
				$("#cidade").html("<option value=''>Selecione o estado</option>");
			}
		});

		//autocompletar busca de titular ou dependente
		$("#buscar_titular, #buscar_dependente").autocomplete({
			source: function(request, response){
				//buscar clientes
				$.post("controles/cliente.jquery.php", {acao:'buscar_clientes',nome:request.term},
				function(resposta){
					response($.map(resposta,
					function(item) {
						return {
							//valor para exibir na selecao
							label: item.cliente,
							//telefone principal
							telefone: item.telefone,
							//valor para exibir na tabela e campo de busca
							nome: item.cliente,
							//id do cliente
							value: item.id
						};
					}));
				}, "json");
			},
			dataType: "json",
			delay: 1000,
			cache: false,
			minLength: 3,
			//cliente selecionado
			select: function(event, ui) {
				//prevenir acoes padroes do plugin
				event.preventDefault();
				//cliente encontrado
				if (ui.item.value != 0) {
					switch ($(this).attr('id')) {
						//titular
						case 'buscar_titular':
							//verificar se o cliente escolhido e dependente de outro cliente
							$.post('controles/cliente.jquery.php',{acao:'buscar_titular',cliente:ui.item.value},
							function(resposta) {
								//nao e dependente
								if (resposta == '0') {
									//titular escolhido
									titular = ui.item.value;
									//esconder titular anterior
									$("#tabela_titular tbody tr:visible").hide();
									//criar linha na tabela titular
									var html;
									var telefone = ui.item.telefone;
									html = "<tr class='add_linha'>";
									html += "	<td class='td_esquerda'>"+ui.item.nome+"</td>";
									if (telefone == null) {
										telefone = '';
									}
									html += "	<td>"+telefone+" <div class='btn_info'></div></td>";
									html += "<td><div class='btn_remover' name='titular_"+titular+"'></div></td>";
									html += "</tr>";
									adicionar_linha(html,"tabela_titular");
									//apagar nome pesquisado
									$("#buscar_titular").val('');
								}
								else {
									var obj = $.parseJSON(resposta);
									//exibir aviso
									caixa_mensagem("Aviso","O cliente escolhido &eacute; dependente de "+obj['titular']);
									//preencher campo de pesquisa
									$("#buscar_titular").val(ui.item.nome);
								}
							});
						break;
						//dependente
						case 'buscar_dependente':
							//verificar se o cliente escolhido ja e dependente de outro cliente
							$.post('controles/cliente.jquery.php',{acao:'buscar_titular',cliente:ui.item.value},
							function(resposta) {
								//nao e dependente
								if (resposta == '0') {
									//verificar se o cliente escolhido ja e titular de outro cliente
									$.post('controles/cliente.jquery.php',{acao:'buscar_dependentes',cliente:ui.item.value},
									function(resposta) {
										//nao e titular
										if (resposta == '0') {
											//adicionar dependente ao array
											dependentes[dependentes.length] = ui.item.value;
											//criar linha na tabela dependentes
											var html;
											var telefone = ui.item.telefone;
											html = "<tr class='add_linha'>";
											html += "	<td class='td_esquerda'>"+ui.item.nome+"</td>";
											if (telefone == null) {
												telefone = '';
											}
											html += "	<td>"+telefone+" <div class='btn_info'></div></td>";
											html += "	<td><div class='btn_remover' name='dependente_"+(dependentes.length)+"'></div></td>";
											html += "	<input type='hidden' name='dependente' value='"+ui.item.value+"'>";
											html += "</tr>";
											adicionar_linha(html,"tabela_dependentes");
											//apagar nome pesquisado
											$("#buscar_dependente").val('');
										}
										else {
											//exibir aviso
											caixa_mensagem("Aviso","O cliente escolhido &eacute; titular de outros clientes");
											//preencher campo de pesquisa
											$("#buscar_dependente").val(ui.item.nome);
										}
									});
								}
								//ja e dependente
								else {
									var obj = $.parseJSON(resposta);
									//exibir aviso
									caixa_mensagem("Aviso","O cliente escolhido &eacute; dependente de "+obj['titular']);
									//preencher campo de pesquisa
									$("#buscar_dependente").val(ui.item.nome);
									
								}
							});
						break;
						//cliente
						case 'buscar_cliente':
							$("#buscar_cliente").val(ui.item.nome);
							$("#dados_cliente").fadeIn();
						break;
					}
				}
			}
		});

		//icone de telefones
		$("#tabela_titular, #tabela_dependentes").on('click','.btn_info',function() {
			//exibir barra de titulo do lightbox
			$(".ui-dialog-titlebar").show();
			//nome do cliente a ser exibido no lightbox
			var nome_cliente = $(this).closest('tr').children('td:eq(0)').html();
			//id do cliente
			var id_cliente;
			//titular
			if ($(this).closest('table').attr('id') == 'tabela_titular') {
				id_cliente = titular;
			} 
			//dependente
			else {
				//buscar id do dependente desejado
				id_cliente = $(this).closest('tr').find('input[name=dependente]').val();
			}
			//buscar telefones
			$.post('controles/cliente.jquery.php',{acao:'exibir_telefones',id_cliente:id_cliente},
			function(resposta) {
				//exibir lightbox com resultado
				caixa_mensagem('Lista de telefones de '+nome_cliente,resposta);
			});
		});

		//mensagens de validacao
		$("#cliente").tooltip("option","content","Informe o nome do cliente");
		$("#situacao").tooltip("option","content","Escolha a situa&ccedil;&atilde;o do cliente");
		$("#email").tooltip("option","content","E-mail inv&aacute;lido");
		$("#buscar_titular").tooltip("option","content","Informe o titular");
		$("#buscar_dependente").tooltip("option","content","Informe os dependentes");
		$("#cpf").tooltip("option","content","CPF inv&aacute;lido");
		$("#numero_telefone").tooltip("option","content","Informe o n&uacute;mero");
		$("#tipo_telefone").tooltip("option","content","Escolha o tipo");
		
		//máscaras
		//80
		$("#cliente").mask("a?+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++");
		$("#endereco").mask("%?&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&");
		//50
		$("#bairro").mask("%?&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&");
		$("#email").mask("*?@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@",{placeholder:""});
		//30
		$("#complemento").mask("%?&&&&&&&&&&&&&&&&&&&&&&&&&&&&&");
		//11
		$("#cpf").mask("99999999999");
		//10
		$("#numero").mask("%?&&&&&&&&&");
		//8
		$("#cep").mask("99999999");
		//telefone
		$("#numero_telefone").mask('(99) 99999999?9');
		//datas
		$("#data_nascimento").mask('99/99/9999');
		$("#data_casamento").mask('99/99/9999');

		//mensagem de validacao de escolha do telefone principal
		$("#telefones").tooltip({
			items: "table",
			disabled: true,
			content: "Escolha o telefone principal",
			position: {
				my: "left top"
			},
			close: function() {
				$(this).tooltip("disable");
			}
		});

		//botao salvar
		$("#salvar").click(function() {
			//esconder mensagens de erro de validacao
			$("#buscar_titular").removeClass('error');
			$("#buscar_dependente").removeClass('error');
			
			//flag enviar formulario
			var enviar = 1;

			//formulario invalido
			if (!$("#form").valid()) {
				//exibir mensagens de erro
				$("#form .error").each(function() {
					$(this).tooltip({disabled: false});
					$(this).tooltip("open");
				});
				//nao enviar formulario
				enviar = 0;
			}

			//validar telefone principal
			//verificar se existe telefone visivel
			if ($("#telefones input[type=radio]:visible").length > 0) {
				//verificar se um telefone principal foi escolhido
				if ($("#telefones input[type=radio]:visible:checked").length == 0) {
					//exibir mensagem de erro de validacao
					$("#telefones").tooltip({disabled: false});
					$("#telefones").tooltip("open");
					//nao enviar formulario
					enviar = 0;
				}
			}

			//validar titular
			//cliente tem titular mas nao foi escolhido
			if ($("#check_titular").is(':checked') && titular == '') {
				//exibir mensagem de erro de validacao
				$("#buscar_titular").addClass('error');
				$("#buscar_titular").tooltip({disabled: false});
				$("#buscar_titular").tooltip("open");
				//nao enviar formulario
				enviar = 0;
			}

			//validar dependentes
			//cliente tem dependente mas nao foi escolhido
			if ($("#check_dependente").is(':checked') && dependentes == '') {
				//exibir mensagem de erro de validacao
				$("#buscar_dependente").addClass('error');
				$("#buscar_dependente").tooltip({disabled: false});
				$("#buscar_dependente").tooltip("open");
				//nao enviar formulario
				enviar = 0;
			}
			
			//validado, enviar formulario
			if (enviar == 1) {
				//jogar valores do javascript em campos escondidos para o POST
				$("#lista_telefones").val(telefones.join("|"));
				$("#lista_dependentes").val(dependentes.join("|"));
				$("#titular").val(titular);
				//enviar formulario
				$("#form").submit();
			}
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
	<input type="hidden" id="lista_telefones" name="lista_telefones">
	<input type="hidden" id="lista_dependentes" name="lista_dependentes">
	<input type="hidden" id="titular" name="titular">
	<div id='formulario_telefone' class='dialogo'>
		<fieldset>
			<span class='titulo_dados'>Adicionar Telefone</span>
			<label class='label_campo'>N&uacute;mero: </label>
			<input type='text' name='numero_telefone' id='numero_telefone' size='19' maxlength='14'>
			<label class='label_campo'>Tipo: </label>
			<select name='tipo_telefone' id='tipo_telefone'>
				<option value=''>Selecione</option>
				<?php while ($lt = mysql_fetch_array($lista_tipo_telefone)) { ?>
				<option value='<?php echo $lt['id']; ?>'><?php echo $lt['tipo_telefone']; ?></option>
				<?php } ?>
			</select>
		</fieldset>
	</div>
	<div class='titulo_tela'>Cadastro de Cliente</div>
	<div class='botoes'>
		<span class='btn_salvar' id='salvar'>Salvar</span>
		<span class='btn_cancelar' id='cancelar'>Cancelar</span>
	</div>
	<div class='espaco'></div>
	<div class='conteudo'>
		<fieldset id='fieldset_informacoes'>
			<span class='titulo_dados'>Informa&ccedil;&otilde;es Pessoais</span>
			<label class='label_campo esquerda'>Nome: </label>
			<input type='text' name='cliente' id='cliente' size='91' maxlength='80'>
			<label class='label_campo'>Situa&ccedil;&atilde;o: </label>
			<select name='situacao' id='situacao'>
				<option value=''>Selecione</option>
				<?php while ($ls = mysql_fetch_array($lista_situacao)) { ?>
				<option value='<?php echo $ls['id']; ?>'><?php echo $ls['situacao']; ?></option>
				<?php } ?>
			</select>
			<div class='espaco'></div>
			<label class='label_campo esquerda'>Sexo: </label>
			<select name='sexo' id='sexo'>
				<option value=''>Selecione</option>
				<option value='M'>Masculino</option>
				<option value='F'>Feminino</option>
			</select>
			<label class='label_campo'>Data de Nascimento: </label>
			<input type='text' name='data_nascimento' id='data_nascimento' size='12' maxlength='10'>
			<label class='label_campo'>Data de Casamento: </label>
			<input type='text' name='data_casamento' id='data_casamento' size='12' maxlength='10'>
		</fieldset>
		<fieldset id='fieldset_endereco'>
			<span class='titulo_dados'>Endere&ccedil;o / Contato</span>
			<label class='label_campo esquerda'>Endere&ccedil;o: </label>
			<input type='text' name='endereco' id='endereco' size='80' maxlength='80'>
			<label class='label_campo'>N&uacute;mero: </label>
			<input type='text' name='numero' id='numero' size='15' maxlength='10'>
			<div class='espaco'></div>
			<label class='label_campo esquerda'>Complemento: </label>
			<input type='text' name='complemento' id='complemento' size='40' maxlength='30'>
			<label class='label_campo'>Bairro: </label>
			<input type='text' name='bairro' id='bairro' size='60' maxlength='50'>
			<div class='espaco'></div>
			<label class='label_campo esquerda'>Estado: </label>
			<select name='estado' id='estado'>
				<option value=''>Selecione</option>
				<?php while ($le = mysql_fetch_array($lista_estado)) { ?>
				<option value='<?php echo $le['id']; ?>'><?php echo $le['sigla']; ?></option>
				<?php } ?>
			</select>
			<label class='label_campo'>Cidade: </label>
			<select name='cidade' id='cidade'>
				<option value=''>Selecione o estado</option>
			</select>
			<label class='label_campo'>CEP: </label>
			<input type='text' name='cep' id='cep' size='15' maxlength='8'>
			<div class='espaco'></div>
			<label class='label_campo esquerda'>E-mail: </label>
			<input type='text' name='email' id='email' size='60' maxlength='50'>
			<div class='espaco'></div>
			<div class='div_telefones'>
				<table id='telefones' class='lista'>
					<thead>
						<tr>
							<td colspan='4'>TELEFONES</td>
						</tr>
						<tr>
							<td width='47%'>N&uacute;mero</td>
							<td width='23%'>Tipo</td>
							<td width='15%'>Principal</td>
							<td width='15%'>Remover</td>
						</tr>
					</thead>
					<tbody>
						<tr class='linha'>
							<td colspan='4' class='vazio'>Nenhum telefone informado</td>
						</tr>
					</tbody>
				</table>
				<span class='btn_adicionar' id='add_telefone'>Adicionar Telefone</span>
			</div>
		</fieldset>
		<fieldset id='fieldset_documentacao'>
			<span class='titulo_dados'>Documenta&ccedil;&atilde;o</span>
			<label class='label_campo esquerda'>CPF: </label>
			<input type='text' name='cpf' id='cpf' size='15' maxlength='11'>
			<label class='label_campo'>Passaporte: </label>
			<input type='text' name='passaporte' id='passaporte' size='40' maxlength='30'>
			<div id='espaco'></div>
			<label class='label_campo esquerda'>RG: </label>
			<input type='text' name='rg' id='rg' size='40' maxlength='30'>
			<label class='label_campo'>&Oacute;rg&atilde;o Emissor: </label>
			<select name='orgao_emissor' id='orgao_emissor'>
				<option value=''>Selecione</option>
				<?php while ($lo = mysql_fetch_array($lista_orgao_emissor)) { ?>
				<option value='<?php echo $lo['id']; ?>'><?php echo utf8_encode($lo['sigla']); ?></option>
				<?php } ?>
			</select>
		</fieldset>
		<fieldset>
			<span class='titulo_dados'>Titulares / Dependentes</span>
			<div style='width:220px;float:left;margin-left:10px;'>
				<input type='checkbox' name='check_titular' id='check_titular' value='1'><label for='check_titular'>Possui Titular</label>
				<br>
				<input type='checkbox' name='check_dependente' id='check_dependente' value='1'><label for='check_dependente'>Possui Dependente(s)</label>
			</div>
			<div style='float:left;margin-right:5px;'>
				<div id='div_titular'>
					<label class='label_campo'>Buscar titular: </label>
					<input type='text' name='buscar_titular' id='buscar_titular' size='90' maxlength='80'>
					<table id='tabela_titular' class='lista'>
						<thead>
							<tr>
								<td colspan='3'>Titular</td>
							</tr>
							<tr>
								<td width='70%'>Nome</td>
								<td width='20%'>Telefone</td>
								<td width='10%'>Remover</td>
							</tr>
						</thead>
						<tbody>
							<tr class='linha'>
								<td colspan='3' class='vazio'>Titular n&atilde;o escolhido</td>
							</tr>
						</tbody>
					</table>
				</div>
				<div id='div_dependentes'>
					<label class='label_campo'>Buscar Dependente: </label>
					<input type='text' name='buscar_dependente' id='buscar_dependente' size='90' maxlength='80'>
					<table id='tabela_dependentes' class='lista'>
						<thead>
							<tr>
								<td colspan='3'>DEPENDENTES</td>
							</tr>
							<tr>
								<td width='70%'>Nome</td>
								<td width='20%'>Telefone</td>
								<td width='10%'>Remover</td>
							</tr>
						</thead>
						<tbody>
							<tr class='linha'>
								<td colspan='3' class='vazio'>Nenhum dependente escolhido</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</fieldset>
	</div>
</form>
