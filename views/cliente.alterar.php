<?php
require_once('controles/cliente.php');
controle_cliente('alterar');
?>
<script type="text/javascript">
	//titular do cliente
	var titular = '';
	//dependentes do cliente
	var dependentes = new Array();
	//excluir dependentes
	var excluir_dependentes = new Array();
	//telefones do cliente
	var telefones = new Array();
	//excluir telefones
	var excluir_telefones = new Array();
	//esperar para atualizar historico
	var atraso = '';
	$(function() {
		//campos obrigatorios
		$("#form").validate({
			rules: {
				cliente: 'required',
				situacao: 'required',
				numero_telefone: 'required',
				email: 'email',
				cpf: 'CPF'
			},
			//nao exibir mensagens de erro de validacao
			errorPlacement: function(error, element) {
				return false;
			}
		});

		//lightbox excluir cliente
		$("#confirmar_exclusao").dialog({
			buttons:{
				//botao adicionar
				Sim: function() {
					$.post('controles/cliente.jquery.php',{acao:'excluir_cliente',id:$("#id_cliente").val()},
					function(resposta) {
						$("#mensagem").html('Cliente Excluído');
						$("#mensagem").dialog("open");
						setTimeout(function(){window.location = window.location},1500);
					});
				},
				'Não': function() {
					//fechar lightbox
					$(this).dialog("close");
				}
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
					if (typeof $("#telefone_"+posicao[1]).val() != 'undefined') {
						excluir_telefones[excluir_telefones.length] = $("#telefone_"+posicao[1]).val(); 
					}
				break;
				//remover titular
				case 'tabela_titular':
					titular = '';
				break;
				//remover dependente do array
				case 'tabela_dependentes':
					delete(dependentes[posicao[1]]);
					if (typeof $("#dependente_"+posicao[1]).val() != 'undefined') {
						excluir_dependentes[excluir_dependentes.length] = $("#dependente_"+posicao[1]).val(); 
					}
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
	
		//autocompletar busca de cliente, titular ou dependente
		$("#buscar_titular, #buscar_dependente, #buscar_cliente").autocomplete({
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
						break;
						//dependente
						case 'buscar_dependente':
							//verificar se o cliente escolhido ja e dependente de outro cliente
							$.post('controles/cliente.jquery.php',{acao:'buscar_titular',cliente:ui.item.value},
							function(resposta) {
								//nao e dependente
								if (resposta == 0) {
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
									html += "</tr>";
									adicionar_linha(html,"tabela_dependentes");
									//apagar nome pesquisado
									$("#buscar_dependente").val('');
								}
								//ja e dependente
								else {
									//exibir aviso
									var obj = $.parseJSON(resposta);
									caixa_mensagem("Aviso","O cliente escolhido j&aacute; &eacute; dependente de "+obj['titular']);
									//preencher campo de pesquisa
									$("#buscar_dependente").val(ui.item.nome);
									
								}
							});
						break;
						//cliente
						case 'buscar_cliente':
							carregar_cliente(ui.item.value);
						break;
					}
				}
			}
		});

		//icone de telefones
		$("#tabela_titular, #tabela_dependentes").on('click','.btn_info',function() {
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
		$("#bairro").mask("-?&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&");
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
		$("#inicio_historico").mask('99/99/9999');
		$("#fim_historico").mask('99/99/9999');

		//campos gerados dinamicamente
		$(".pagina").livequery(function() {
			$(this).button();
		});
		$(".pagina_ativa").livequery(function() {
			$(this).button({
				disabled: true
			});
		});

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
		
		//filtros do historico
		$("#buscar_viagem,#inicio_historico,#fim_historico").keyup(function() {
			//cancelar busca iniciada anteriormente
			clearTimeout(atraso);
			//iniciar busca apos 2 segundos
			atraso = setTimeout("exibir_historico(0,1)",2000);
		});

		//paginacao do historico
		$("#tabela_historico").on('click','.pagina',function() {
			//$("#tabela_historico").off('click');
			var click = $(this).children('span').html();
			var paginas = (click-1);
			var registros = paginas*10;
			exibir_historico(registros,click);
		});

		//botao excluir cliente
		$("#excluir_cliente").click(function() {
			//esconder barra de titulo do lightbox
			$(".ui-dialog-titlebar").hide();
			//exibir lightbox
			$("#confirmar_exclusao").dialog("open");
		});
		
		//botao salvar
		$("#salvar").click(function() {
			//esconder mensagens de erro validacao
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
			if ($("#check_dependente").is(':checked') && $("#tabela_dependentes .vazio").is(":visible")) {
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
				$("#excluir_telefones").val(excluir_telefones.join("|"));
				$("#lista_dependentes").val(dependentes.join("|"));
				$("#excluir_dependentes").val(excluir_dependentes.join("|"));
				$("#titular").val(titular);
				//enviar formulario
				$("#form").submit();
			}
		});

		<?php if ($_POST) { ?>
		carregar_cliente(<?php echo $_POST['id_cliente']; ?>);
		<?php } ?>
	});

	//adicionar nova linha em uma tabela
	function adicionar_linha(linha,tabela) {
		if ($("#"+tabela+" .vazio").is(':visible')) {
			$("#"+tabela+" .vazio").closest('tr').hide();
		}
		$("#"+tabela+" tbody tr :last").after(linha);
		$("#"+tabela+" .add_linha").switchClass("add_linha","linha",700);
	}

	function carregar_cliente(id_cliente) {
		//buscar dados do cliente escolhido
		$.post('controles/cliente.jquery.php',{acao:'buscar_dados',cliente:id_cliente},
		function(resposta) {
			//titular do cliente
			titular = '';
			//dependentes do cliente
			dependentes = new Array();
			//telefones do cliente
			telefones = new Array();
			//excluir telefones
			excluir_telefones = new Array();
			
			var dados = $.parseJSON(resposta);
			//preencher formulario
			$("#buscar_cliente").val(dados['cliente']);
			$("#id_cliente").val(dados['id']);
			$("#cliente").val(dados['cliente']);
			$("#situacao").val(dados['id_situacao']);
			$("#sexo").val(dados['sexo']);
			$("#data_nascimento").val(dados['data_nascimento']);
			$("#data_casamento").val(dados['data_casamento']);
			$("#endereco").val(dados['endereco']);
			$("#numero").val(dados['numero']);
			$("#complemento").val(dados['complemento']);
			$("#bairro").val(dados['bairro']);
			$("#estado").val(dados['id_estado']);
			$("#cep").val(dados['cep']);
			$("#email").val(dados['email']);
			$("#cpf").val(dados['cpf']);
			$("#passaporte").val(dados['passaporte']);
			$("#rg").val(dados['rg']);
			$("#orgao_emissor").val(dados['id_orgao_emissor']);
	
			//cidade
			$.post('controles/cidade.jquery.php',{acao:'listar_cidades',estado:dados['id_estado']},
			function(resposta) {
				$("#cidade").html(resposta);
				$("#cidade").val(dados['cidade']);
			});
	
			//telefones
			$.post('controles/cliente.jquery.php',{acao:'exibir_telefones_consulta',id_cliente:dados['id']},
			function(resposta) {
				$("#telefones tbody").html(resposta);
				$("#telefones tbody tr:visible").each(function(i) {
					if ($(this).children('td').length > 1) {
						var numero = $(this).children('td:eq(0)').html();
						var tipo = $(this).children('input[name=valor_tipo]').val();
						telefones[telefones.length] = new Array(numero,tipo);
					}
				});
			});
			
			//titular
			$.post('controles/cliente.jquery.php',{acao:'buscar_titular',cliente:dados['id']},
			function(resposta) {
				var html = '';
				//possui titular
				if (resposta != 0) {
					//limpar tabela de titular
					if ($("#tabela_titular tbody tr").length > 1) {
						$("#tabela_titular tbody tr").not(':first').remove();
					}
					var obj = $.parseJSON(resposta);
					titular = obj['id'];
					html = "<tr class='add_linha'>";
					html += "	<td class='td_esquerda'>"+obj['titular']+"</td>";
					html += "	<td>"+obj['telefone']+" <div class='btn_info'></div></td>";
					html += "<td><div class='btn_remover' name='titular_"+titular+"'></div></td>";
					html += "</tr>";
					$("#check_titular").attr('checked',true);
					$("label[for=check_titular]").addClass('ui-state-active');
					$("#div_titular").show();
					adicionar_linha(html,"tabela_titular");
				}
				//nao possui titular
				else {
					$("#check_titular").attr('checked',false);
					$("label[for=check_titular]").removeClass('ui-state-active');
					$("#div_titular").hide();
					html += "<tr class='linha'>";
					html += "	<td class='vazio' colspan='3'>Titular n&atilde;o escolhido</td>";
					html += "</tr>";
					$("#tabela_titular tbody").html(html);
				}
			});
			
			//dependentes
			$.post('controles/cliente.jquery.php',{acao:'buscar_dependentes',cliente:dados['id']},
			function(resposta) {
				var html = '';
				if (resposta != 0) {
					//limpar tabela de dependentes
					if ($("#tabela_dependentes tbody tr").length > 1) {
						$("#tabela_dependentes tbody tr").not(':first').remove();
					}
					var obj = $.parseJSON(resposta);
					$.each(obj,function(i,valores) {
						html += "<tr class='add_linha'>";
						html += "	<td class='td_esquerda'>"+valores.cliente+"</td>";
						html += "	<td>"+valores.telefone+" <div class='btn_info'></div></td>";
						html += "	<td><div class='btn_remover' name='dependente_"+i+"' ></div></td>";
						html += "	<input type='hidden' value='"+valores.id+"' id='dependente_"+i+"' name='dependente'>";
						html += "</tr>";
					});
					$("#check_dependente").attr('checked',true);
					$("label[for=check_dependente]").addClass('ui-state-active');
					$("#div_dependentes").show();
					adicionar_linha(html,"tabela_dependentes");
				}
				else {
					$("#check_dependente").attr('checked',false);
					$("label[for=check_dependente]").removeClass('ui-state-active');
					$("#div_dependentes").hide();
					html += "<tr class='linha'>";
					html += "	<td class='vazio' colspan='3'>Nenhum dependente escolhido</td>";
					html += "</tr>";
					$("#tabela_dependentes tbody").html(html);
				}
			});

			exibir_historico(0,1);
			
			//exibir formulario
			$("#dados_cliente").fadeIn();
			$(".botoes").fadeIn();
		});
	}

	//historico
	function exibir_historico(inicio,pagina) {
		$.post('controles/cliente.jquery.php',{acao:'buscar_historico',cliente:$("#id_cliente").val(),inicio:inicio,
		data_inicial:$("#inicio_historico").val(),data_final:$("#fim_historico").val(),viagem:$("#buscar_viagem").val()},
		function(resposta) {
			var html = '';
			if (resposta != 0) {
				var obj = $.parseJSON(resposta);
				var num_historico = 0;
				var total = 0;
				$("#tabela_historico tbody").fadeOut('normal',function() {
					$.each(obj,function(i,valores) {
						if (typeof(valores.total) == 'undefined') {
							html += "<tr class='add_linha_historico'>";
							html += "	<td>"+valores.viagem+"</td>";
							html += "	<td>"+valores.data_saida+"</td>";
							html += "	<td>"+valores.valor+"</td>";
							html += "</tr>";
							num_historico++;
						}
						else {
							total = valores.total;
						}
					});
					$("#tabela_historico tbody").html(html);
	
					var paginas = '';
					var num_paginas = Math.ceil(total/10);
					var pagina_inicial = parseInt(pagina)-2;
					var proxima_pagina = parseInt(pagina)+1;
					var pagina_final = parseInt(pagina)+2;
					var sobra = 5 - parseInt(num_paginas);
					if (sobra > 0) {
						pagina_inicial -= parseInt(sobra);
					}
					for (var i=pagina_inicial;i<=pagina;i++) {
						if (i > 0) {
							if (i == pagina) {
								paginas += "<span class='pagina_ativa'>"+i+"</span>";
							}
							else {
								paginas += "<span class='pagina'>"+i+"</span>";
							}
						}
						else {
							pagina_final++;
						}
					}
					
					for (var i=proxima_pagina;i<=pagina_final;i++) {
						if (i <= num_paginas) {
							paginas += "<span class='pagina'>"+i+"</span>";
						}
					}
					
					rodape = "<tr>";
					rodape += "	<td>Exibindo de "+(inicio+1)+" at&eacute; "+(inicio+num_historico)+" (Total: "+(total)+")</td>";
					rodape += "	<td colspan='2'>"+paginas+"</td>";
					rodape += "</tr>";
					$("#tabela_historico tfoot").html(rodape);
					$("#tabela_historico tbody").fadeIn('normal',function() {
						$(this).children('tr:visible').each(function() {
							$(this).switchClass('add_linha_historico','linha',200);
						});
					});
					$("#tabela_historico tfoot").fadeIn();
				});
			}
			else {
				html += "<tr class='linha'>";
				html += "	<td class='vazio' colspan='3'>Nenhuma viagem</td>";
				html += "</tr>";
				$("#tabela_historico tfoot").fadeOut();
				$("#tabela_historico tbody").html(html);
			}
		});
	}
</script>
<form id='form' method='post'>
	<input type="hidden" id="lista_telefones" name="lista_telefones">
	<input type="hidden" id="excluir_telefones" name="excluir_telefones">
	<input type="hidden" id="lista_dependentes" name="lista_dependentes">
	<input type="hidden" id="excluir_dependentes" name="excluir_dependentes">
	<input type="hidden" id="titular" name="titular">
	<input type="hidden" id="id_cliente" name="id_cliente">
	<div id='confirmar_exclusao' class='dialogo'>
		<fieldset>
			<span class='titulo_dados'>Excluir Cliente</span>
			<label style='padding-left:200px'><b>Tem certeza que deseja excluir esse cliente?</b></label>
		</fieldset>
	</div>
	<div id='formulario_telefone' class='dialogo'>
		<fieldset>
			<span class='titulo_dados'>Adicionar Telefone</span>
			<label class='label_campo'>N&uacute;mero: </label>
			<input type='text' name='numero_telefone' id='numero_telefone' size='19' maxlength='14'>
			<label class='label_campo'>Tipo: </label>
			<select name='tipo_telefone' id='tipo_telefone'>
				<option value=''>Selecione</option>
				<?php foreach ($lista_tipo_telefone as $lt) { ?>
				<option value='<?php echo $lt['id']; ?>'><?php echo $lt['tipo_telefone']; ?></option>
				<?php } ?>
			</select>
		</fieldset>
	</div>
	<div class='titulo_tela'>Consultar / Alterar Cliente</div>
	<div class='botoes' style='display:none'>
		<span class='btn_salvar' id='salvar'>Salvar</span>
		<span class='btn_cancelar' id='cancelar'>Cancelar</span>
		<span class='btn_excluir' id='excluir_cliente' style='margin-left:185px'>Excluir Cliente</span>
	</div>
	<div class='espaco'></div>
	<div class='conteudo'>
		<fieldset id='fieldset_consulta'>
			<span class='titulo_dados'>Buscar Cliente</span>
			<label class='label_campo esquerda'>Nome: </label>
			<input type='text' name='buscar_cliente' id='buscar_cliente' size='91' maxlength='80'>
		</fieldset>
	</div>
	<div class='conteudo' id='dados_cliente' style='display: none'>
		<fieldset id='fieldset_informacoes'>
			<span class='titulo_dados'>Informa&ccedil;&otilde;es Pessoais</span>
			<label class='label_campo esquerda'>Nome: </label>
			<input type='text' name='cliente' id='cliente' size='91' maxlength='80'>
			<label class='label_campo'>Situa&ccedil;&atilde;o: </label>
			<select name='situacao' id='situacao'>
				<option value=''>Selecione</option>
				<?php foreach ($lista_situacao as $ls) { ?>
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
				<?php foreach ($lista_estado as $le) { ?>
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
				<?php foreach ($lista_orgao_emissor as $lo) { ?>
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
		<fieldset>
			<span class='titulo_dados'>Hist&oacute;rico de Viagens</span>
			<label class='label_campo'>Viagem: </label>
			<input type='text' name='buscar_viagem' id='buscar_viagem' size='70' maxlength='70'>
			<label class='label_campo'>Data Inicial: </label>
			<input type='text' name='inicio_historico' id='inicio_historico' size='6' maxlength='10'>
			<label class='label_campo'>Data Final: </label>
			<input type='text' name='fim_historico' id='fim_historico' size='6' maxlength='10' value='<?php echo date('d/m/Y'); ?>'>
			<table id='tabela_historico' class='lista'>
				<thead>
					<tr>
						<td width='70%'>Viagem</td>
						<td width='15%'>Data de sa&iacute;da</td>
						<td width='15%'>Valor</td>
					</tr>
				</thead>
				<tbody>
					<tr class='linha'>
						<td colspan='3' class='vazio'>Nenhuma viagem</td>
					</tr>
				</tbody>
				<tfoot></tfoot>
			</table>
		</fieldset>
	</div>
</form>
