<?php
require_once('controles/hotel.php');
controle_hotel('alterar');
?>
<script type="text/javascript">
	//telefones do hotel
	var telefones = new Array();
	//excluir telefones
	var excluir_telefones = new Array();
	//bancos do hotel
	var bancos = new Array();
	//excluir bancos
	var excluir_bancos = new Array();
	//esperar para atualizar historico
	var atraso = '';
	$(function() {
		//campos obrigatorios
		$("#form").validate({
			rules: {
				hotel: 'required',
				estado: 'required',
				cidade: 'required'/*,
				cnpj: 'cnpj'*/
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
					//adicionar
					if (add == 1) {
						//adicionar ao array
						telefones[telefones.length] = new Array(numero);
						//criar linha na tabela de telefones
						var html;
						html = "<tr class='add_linha'>";
						html += "	<td>"+numero+"</td>";
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
					//fechar lightbox
					$(this).dialog("close");
				}
			}
		});
		
		//lightbox adicionar banco
		$("#formulario_banco").dialog({
			buttons:{
				Adicionar: function() {
					//valores
					var banco = $("#banco").val();
					var agencia = $("#agencia").val();
					var conta = $("#conta").val();
					var titular = $("#titular").val();
					var tipo = $("#tipo_documento").val();
					var cpf_cnpj = $("#cpf_cnpj").val();
					//flag adicionar
					var add = 1;
					//validar campos
					if (banco == '') {
						//exibir erro de validacao
						$("#banco").addClass('error');
						$("#banco").tooltip({disabled: false});
						$("#banco").tooltip("open");
						//nao adicionar
						add = 0;
					}
					if (agencia == '') {
						//exibir erro de validacao
						$("#agencia").addClass('error');
						$("#agencia").tooltip({disabled: false});
						$("#agencia").tooltip("open");
						//nao adicionar
						add = 0;
					}
					if (conta == '') {
						//exibir erro de validacao
						$("#conta").addClass('error');
						$("#conta").tooltip({disabled: false});
						$("#conta").tooltip("open");
						//nao adicionar
						add = 0;
					}
					if (titular == '') {
						//exibir erro de validacao
						$("#titular").addClass('error');
						$("#titular").tooltip({disabled: false});
						$("#titular").tooltip("open");
						//nao adicionar
						add = 0;
					}
					if (tipo == '') {
						//exibir erro de validacao
						$("#tipo_documento").addClass('error');
						$("#tipo_documento").tooltip({disabled: false});
						$("#tipo_documento").tooltip("open");
						//nao adicionar
						add = 0;
					}
					if (cpf_cnpj == '') {
						//exibir erro de validacao
						$("#cpf_cnpj").addClass('error');
						$("#cpf_cnpj").tooltip({disabled: false});
						$("#cpf_cnpj").tooltip("open");
						//nao adicionar
						add = 0;
					}
					//adicionar
					if (add == 1) {
						//adicionar ao array
						bancos[bancos.length] = new Array(banco,agencia,conta,titular,cpf_cnpj);
						//criar linha na tabela de bancos
						var html;
						html = "<tr class='add_linha'>";
						html += "	<td>"+banco+"</td>";
						html += "	<td>"+agencia+"</td>";
						html += "	<td>"+conta+"</td>";
						html += "	<td>"+titular+"</td>";
						html += "	<td>"+cpf_cnpj+"</td>";
						html += "	<td><div class='btn_remover' name='banco_"+(bancos.length-1)+"'></div></td>";
						html += "</tr>";
						adicionar_linha(html,"bancos");
						//fechar lightbox
						$(this).dialog("close");
					}
				},
				Cancelar: function() {
					//fechar mensagens de erro de validacao
					$("#banco").tooltip("close");
					$("#agencia").tooltip("close");
					$("#conta").tooltip("close");
					$("#titular").tooltip("close");
					$("#cpf_cnpj").tooltip("close");
					$("#tipo_documento").tooltip("close");
					//fechar lightbox
					$(this).dialog( "close" );
				}
			}
		});

		//botao adicionar telefone
		$("#add_telefone").click(function() {
			//esconder barra de titulo do lightbox
			$(".ui-dialog-titlebar").hide();
			//esconder mensagens de erro
			$("#numero_telefone").removeClass('error');
			//resetar formulario
			$("#numero_telefone").val('');
			//exibir lightbox
			$("#formulario_telefone").dialog("open");
		});
		//botao adicionar banco
		$("#add_banco").click(function() {
			//esconder barra de titulo do lightbox
			$(".ui-dialog-titlebar").hide();
			//esconder mensagens de erro
			$("#banco").removeClass('error');
			$("#agencia").removeClass('error');
			$("#conta").removeClass('error');
			$("#titular").removeClass('error');
			$("#tipo_documento").removeClass('error');
			$("#cpf_cnpj").removeClass('error');
			//resetar formulario
			$("#banco").val('');
			$("#agencia").val('');
			$("#conta").val('');
			$("#titular").val('');
			$("#tipo_documento").val('');
			$("#cpf_cnpj").val('');
			//exibir lightbox
			$("#formulario_banco").dialog("open");
		});

		//remover item de uma lista
		$("#telefones, #bancos").on('click','.btn_remover',function(event) {
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
				//remover banco do array
				case 'bancos':
					delete(bancos[posicao[1]]);
					if (typeof $("#banco_"+posicao[1]).val() != 'undefined') {
						excluir_bancos[excluir_bancos.length] = $("#banco_"+posicao[1]).val(); 
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

		//mensagens de validacao
		$("#hotel").tooltip("option","content","Informe o nome do hotel");
		$("#estado").tooltip("option","content","Escolha o estado do hotel");
		$("#cidade").tooltip("option","content","Escolha a cidade do hotel");
		$("#cnpj").tooltip("option","content","CNPJ inv&aacute;lido");
		$("#banco").tooltip("option","content","Informe o nome do banco");
		$("#agencia").tooltip("option","content","Informe o n&uacute;mero da ag&ecirc;ncia");
		$("#conta").tooltip("option","content","Informe o n&uacute;mero da conta");
		$("#titular").tooltip("option","content","Informe o nome do titular");
		$("#cpf_cnpj").tooltip("option","content","Informe o CPF ou CNPJ");
		$("#tipo_documento").tooltip("option","content","Informe o n&uacute;mero do telefone");
		$("#tipo_documento").tooltip("option","content","Informe o tipo de documento");
		$("#numero_telefone").tooltip("option","content","Informe o n&uacute;mero do telefone");

		//máscaras
		//80
		$("#endereco").mask("a?+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++");
		//70
		$("#hotel").mask("a?+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++");
		$("#titular").mask("a?+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++");
		//50
		$("#bairro").mask("a?+++++++++++++++++++++++++++++++++++++++++++++++++");
		$("#banco").mask("a?+++++++++++++++++++++++++++++++++++++++++++++++++");
		$("#email").mask("*?@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@",{placeholder:""});
		//30
		$("#complemento").mask("a?+++++++++++++++++++++++++++++");
		//18
		$("#cnpj").mask("99.999.999/9999-99");
		//15
		$("#agencia").mask("&?&&&&&&&&&&&&&&");
		$("#conta").mask("&?&&&&&&&&&&&&&&");
		//10
		$("#numero").mask("%?&&&&&&&&&");
		//8
		$("#cep").mask("99999999");
		//telefone
		$("#numero_telefone").mask('(99) 99999999?9');

		//autocompletar busca de hotel
		$("#buscar_hotel").autocomplete({
			source: function(request, response){
				//buscar hotel
				$.post("controles/hotel.jquery.php", {acao:'buscar_hotel',nome:request.term},
				function(resposta){
					response($.map(resposta,
					function(item) {
						return {
							//valor para exibir na selecao
							label: item.hotel,
							//valor para exibir na tabela e campo de busca
							nome: item.hotel,
							//id do hotel
							value: item.id
						};
					}));
				}, "json");
			},
			dataType: "json",
			delay: 1000,
			cache: false,
			minLength: 3,
			//hotel selecionado
			select: function(event, ui) {
				//prevenir acoes padroes do plugin
				event.preventDefault();
				//hotel encontrado
				if (ui.item.value != 0) {
					carregar_hotel(ui.item.value);
				}
			}
		});
		
		$("#tipo_documento").change(function() {
			$("#cpf_cnpj").val('');
			if ($(this).val() != '') {
				if ($(this).val() == 'cpf') {
					$("#cpf_cnpj").mask('99999999999');
				}
				else {
					$("#cpf_cnpj").mask("99.999.999/9999-99");
				}
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
		
		//botao salvar
		$("#salvar").click(function() {
			if (!$("#form").valid()) {
				$("#form .error").each(function() {
					$(this).tooltip({disabled: false});
					$(this).tooltip("open");
				});
			}
			else {
				//jogar valores do javascript em campos escondidos para o POST
				$("#lista_telefones").val(telefones.join("|"));
				$("#excluir_telefones").val(excluir_telefones.join("|"));
				$("#lista_bancos").val(bancos.join("|"));
				$("#excluir_bancos").val(excluir_bancos.join("|"));
				//enviar formulario
				$("#form").submit();
			}
		});
		
		<?php if ($_POST) { ?>
		carregar_hotel(<?php echo $_POST['id_hotel']; ?>);
		<?php } ?>
	});

	//adicionar nova linha em uma tabela
	function adicionar_linha(linha,tabela) {
		if ($("#"+tabela+" .vazio").is(':visible')) {
			$("#"+tabela+" .vazio").closest('tr').hide();
		}
		$("#"+tabela+" tr :last").after(linha);
		$("#"+tabela+" .add_linha").switchClass("add_linha","linha",700);
	}

	function carregar_hotel(id_hotel) {
		//buscar dados do hotel escolhido
		$.post('controles/hotel.jquery.php',{acao:'buscar_dados',hotel:id_hotel},
		function(resposta) {
			//telefones do hotel
			telefones = new Array();
			//excluir telefones
			excluir_telefones = new Array();
			//excluir bancos
			excluir_bancos = new Array();
			
			var dados = $.parseJSON(resposta);
			//preencher formulario
			$("#buscar_hotel").val(dados['hotel']);
			$("#id_hotel").val(dados['id']);
			$("#hotel").val(dados['hotel']);
			$("#cnpj").val(dados['cnpj']);
			$("#endereco").val(dados['endereco']);
			$("#numero").val(dados['numero']);
			$("#complemento").val(dados['complemento']);
			$("#bairro").val(dados['bairro']);
			$("#estado").val(dados['id_estado']);
			$("#cep").val(dados['cep']);
			$("#email").val(dados['email']);
			$("#site").val(dados['site']);
			
			//cidade
			$.post('controles/cidade.jquery.php',{acao:'listar_cidades',estado:dados['id_estado']},
			function(resposta) {
				$("#cidade").html(resposta);
				$("#cidade").val(dados['cidade']);
			});
	
			//telefones
			$.post('controles/hotel.jquery.php',{acao:'exibir_telefones_consulta',id_hotel:dados['id']},
			function(resposta) {
				$("#telefones tbody").html(resposta);
				$("#telefones tbody tr:visible").each(function(i) {
					//se existir apenas uma coluna, nao adicionar valor ao array (linha vazia)
					if ($(this).children('td').length > 1) {
						var numero = $(this).children('td:eq(0)').html();
						telefones[telefones.length] = new Array(numero);
					}
				});
			});
			
			//bancos
			$.post('controles/hotel.jquery.php',{acao:'exibir_bancos_consulta',id_hotel:dados['id']},
			function(resposta) {
				$("#bancos tbody").html(resposta);
				$("#bancos tbody tr:visible").each(function(i) {
					//se existir apenas uma coluna, nao adicionar valor ao array (linha vazia)
					if ($(this).children('td').length > 1) {
						var banco = $(this).children('td:eq(0)').html();
						var agencia = $(this).children('td:eq(1)').html();
						var conta = $(this).children('td:eq(2)').html();
						var titular = $(this).children('td:eq(3)').html();
						var cpf_cnpj = $(this).children('td:eq(4)').val();
						bancos[bancos.length] = new Array(banco,agencia,conta,titular,cpf_cnpj);
					}
				});
			});

			exibir_historico(0,1);
			
			//exibir formulario
			$("#dados_hotel").fadeIn();
			$(".botoes").fadeIn();
		});
	}

	//historico
	function exibir_historico(inicio,pagina) {
		$.post('controles/hotel.jquery.php',{acao:'buscar_historico',hotel:$("#id_hotel").val(),inicio:inicio,
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
							html += "	<td>"+valores.data_chegada+"</td>";
							html += "	<td>"+valores.contato+"</td>";
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
					rodape += "	<td colspan='2'>Exibindo de "+(inicio+1)+" at&eacute; "+(inicio+num_historico)+" (Total: "+(total)+")</td>";
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
				html += "	<td class='vazio' colspan='4'>Nenhuma viagem</td>";
				html += "</tr>";
				$("#tabela_historico tfoot").fadeOut();
				$("#tabela_historico tbody").html(html);
			}
		});
	}
</script>
<style type="text/css">
	.esquerda{
		display: block;
		float: left;
		width: 102px;
	}
</style>
<form id='form' method='post'>
	<input type="hidden" id="lista_telefones" name="lista_telefones">
	<input type="hidden" id="excluir_telefones" name="excluir_telefones">
	<input type="hidden" id="lista_bancos" name="lista_bancos">
	<input type="hidden" id="excluir_bancos" name="excluir_bancos">
	<input type="hidden" id="id_hotel" name="id_hotel">
	<div id='formulario_telefone' class='dialogo'>
		<fieldset>
			<span class='titulo_dados'>Adicionar Telefone</span>
			<label class='label_campo'>N&uacute;mero: </label>
			<input type='text' name='numero_telefone' id='numero_telefone' size='20' maxlength='14'>
		</fieldset>
	</div>
	<div id='formulario_banco' class='dialogo'>
		<fieldset id='fieldset_banco'>
			<span class='titulo_dados'>Adicionar Banco</span>
			<label class='label_campo esquerda'>Banco: </label>
			<input type='text' name='banco' id='banco' size='60' maxlength='50'>
			<div class='espaco'></div>
			<label class='label_campo esquerda'>Ag&ecirc;ncia: </label>
			<input type='text' name='agencia' id='agencia' size='20' maxlength='15'>
			<label class='label_campo'>Conta: </label>
			<input type='text' name='conta' id='conta' size='20' maxlength='15'>
			<div class='espaco'></div>
			<label class='label_campo esquerda'>Titular: </label>
			<input type='text' name='titular' id='titular' size='60' maxlength='70'>
			<div class='espaco'></div>
			<label class='label_campo esquerda'>CPF / CNPJ: </label>
			<select name='tipo_documento' id='tipo_documento'>
				<option value=''>Selecione</option>
				<option value='cpf'>CPF</option>
				<option value='cnpj'>CNPJ</option>
			</select>
			<input type='text' name='cpf_cnpj' id='cpf_cnpj' size='25' maxlength='18'>
		</fieldset>
	</div>
	<div class='titulo_tela'>Consultar / Alterar Hotel</div>
	<div class='botoes' style='display:none'>
		<span class='btn_salvar' id='salvar'>Salvar</span>
		<span class='btn_cancelar' id='cancelar'>Cancelar</span>
	</div>
	<div class='espaco'></div>
	<div class='conteudo'>
		<fieldset id='fieldset_consulta'>
			<span class='titulo_dados'>Buscar Hotel</span>
			<label class='label_campo esquerda'>Nome: </label>
			<input type='text' name='buscar_hotel' id='buscar_hotel' size='81' maxlength='70'>
		</fieldset>
	</div>
	<div class='conteudo' id='dados_hotel' style='display:none'>
		<fieldset>
			<span class='titulo_dados'>Dados do hotel</span>
			<label class='label_campo esquerda'>Nome: </label>
			<input type='text' name='hotel' id='hotel' size='80' maxlength='70'>
			<label class='label_campo'>CNPJ: </label>
			<input type='text' name='cnpj' id='cnpj' size='20' maxlength='18'>
			<div class='espaco'></div>
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
				<option value=''>Selecione</option>
				<?php foreach ($lista_cidade as $lc) { ?>
				<option value='<?php echo $lc['id']; ?>'><?php echo $lc['cidade']; ?></option>
				<?php } ?>
			</select>
			<label class='label_campo'>CEP: </label>
			<input type='text' name='cep' id='cep' size='15' maxlength='8'>
			<div class='espaco'></div>
			<label class='label_campo esquerda'>Site: </label>
			<input type='text' name='site' id='site' size='90' maxlength='80'>
			<div class='espaco'></div>
			<label class='label_campo esquerda'>E-mail: </label>
			<input type='text' name='email' id='email' size='60' maxlength='50'>
			<div class='espaco'></div>
			<div class='div_telefones'>
				<table id='telefones' class='lista'>
					<thead>
						<tr>
							<td colspan='3'>TELEFONES</td>
						</tr>
						<tr>
							<td width='80%'>N&uacute;mero</td>
							<td width='20%'>Remover</td>
						</tr>
					</thead>
					<tbody>
						<tr class='linha'>
							<td colspan='3' class='vazio'>Nenhum telefone informado</td>
						</tr>
					</tbody>
				</table>
				<span class='btn_adicionar' id='add_telefone'>Adicionar Telefone</span>
			</div>
		</fieldset>
		<fieldset>
			<span class='titulo_dados'>Dados banc&aacute;rios</span>
			<div style='width:980px;margin-left:auto;margin-right:auto;'>
				<table id='bancos' class='lista'>
					<thead>
						<tr>
							<td width='5%'>Banco</td>
							<td width='8%'>Ag&ecirc;ncia</td>
							<td width='8%'>Conta</td>
							<td width='50%'>Titular</td>
							<td width='20%'>CPF / CNPJ</td>
							<td width='9%'>Remover</td>
						</tr>
					</thead>
					<tbody>
						<tr class='linha'>
							<td colspan='6' class='vazio'>Nenhum banco informado</td>
						</tr>
					</tbody>
				</table>
				<span class='btn_adicionar' id='add_banco'>Adicionar Banco</span>
			</div>
		</fieldset>
		<fieldset>
			<span class='titulo_dados'>Hist&oacute;rico de Reservas</span>
			<label class='label_campo'>Viagem: </label>
			<input type='text' name='buscar_viagem' id='buscar_viagem' size='70' maxlength='70'>
			<label class='label_campo'>Data Inicial: </label>
			<input type='text' name='inicio_historico' id='inicio_historico' size='6' maxlength='10'>
			<label class='label_campo'>Data Final: </label>
			<input type='text' name='fim_historico' id='fim_historico' size='6' maxlength='10' value='<?php echo date('d/m/Y'); ?>'>
			<table id='tabela_historico' class='lista'>
				<thead>
					<tr>
						<td width='60%'>Viagem</td>
						<td width='10%'>Data</td>
						<td width='20%'>Contato</td>
						<td width='10%'>Valor</td>
					</tr>
				</thead>
				<tbody>
					<tr class='linha'>
						<td colspan='4' class='vazio'>Nenhuma viagem</td>
					</tr>
				</tbody>
				<tfoot></tfoot>
			</table>
		</fieldset>
	</div>
</form>
