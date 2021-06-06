<?php
require_once('controles/empresa.php');
controle_empresa('cadastrar');
?>
<script type="text/javascript">
	//telefones da empresa
	var telefones = new Array();
	//bancos da empresa
	var bancos = new Array();
	$(function() {
		//campos obnrigatorios
		$("#form").validate({
			rules: {
				empresa: 'required',
				estado: 'required',
				cidade: 'required'/*,
				'tipo_transporte[]': 'required',
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
					var numero = $("#numero_telefone").val();
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
						$("#tipo").addClass('error');
						$("#tipo").tooltip({disabled: false});
						$("#tipo").tooltip("open");
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
					$(this).dialog("close");
				},
				Cancelar: function() {
					//fechar mensagens de erro de validacao
					$("#banco").tooltip("close");
					$("#agencia").tooltip("close");
					$("#conta").tooltip("close");
					$("#titular").tooltip("close");
					$("#cpf_cnpj").tooltip("close");
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
			$("#cpf_cnpj").removeClass('error');
			//resetar formulario
			$("#banco").val('');
			$("#agencia").val('');
			$("#conta").val('');
			$("#titular").val('');
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
				break;
				//remover banco do array
				case 'bancos':
					delete(bancos[posicao[1]]);
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
		
		$("input[type=checkbox]").click(function() {
			if (!$(this).is(':checked')) {
				$("label[for="+$(this).attr('id')+"]").removeClass('ui-state-active ui-state-focus');
			}
		});
		
		//mensagens de validacao
		$("#empresa").tooltip("option","content","Informe o nome da empresa");
		$("#estado").tooltip("option","content","Escolha o estado da empresa");
		$("#cidade").tooltip("option","content","Escolha a cidade da empresa");
		$("input[name=tipo_transporte\\[\\]]").tooltip("option","content","Escolha o tipo de transporte");
		$("#cnpj").tooltip("option","content","CNPJ inv&aacute;lido");
		$("#banco").tooltip("option","content","Informe o nome do banco");
		$("#agencia").tooltip("option","content","Informe o n&uacute;mero da ag&ecirc;ncia");
		$("#conta").tooltip("option","content","Informe o n&uacute;mero da conta");
		$("#titular").tooltip("option","content","Informe o nome do titular");
		$("#cpf_cnpj").tooltip("option","content","Informe o CPF ou CNPJ");
		$("#tipo_documento").tooltip("option","content","Informe o n&uacute;mero do telefone");
		$("input[type=checkbox]").tooltip({
			items: "input[type=checkbox]",
			disabled: true,
			position: {
				at: "right top+40"
			},
			close: function() {
				$(this).tooltip("disable");
			}
		});
		
		//máscaras
		//80
		$("#endereco").mask("a?+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++");
		//70
		$("#empresa").mask("a?+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++");
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
		
		
		$("#titular").mask();
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
				$("#lista_bancos").val(bancos.join("|"));
				//enviar formulario
				$("#form").submit();
			}
		});
	});

	//adiconar nova linha em uma tabela
	function adicionar_linha(linha,tabela) {
		if ($("#"+tabela+" .vazio").is(':visible')) {
			$("#"+tabela+" .vazio").closest('tr').hide();
		}
		$("#"+tabela+" tr :last").after(linha);
		$("#"+tabela+" .add_linha").switchClass("add_linha","linha",700);
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
	<input type="hidden" id="lista_bancos" name="lista_bancos">
	<div id='formulario_telefone' class='dialogo'>
		<fieldset>
			<span class='titulo_dados'>Adicionar Telefone</span>
			<label class='label_campo'>N&uacute;mero: </label>
			<input type='text' name='numero_telefone' id='numero_telefone' size='20' maxlength='14'>
		</fieldset>
	</div>
	<div id='formulario_banco' class='dialogo'>
		<fieldset>
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
	<div class='titulo_tela'>Cadastro de Empresa de Transporte</div>
	<div class='botoes'>
		<span class='btn_salvar' id='salvar'>Salvar</span>
		<span class='btn_cancelar' id='cancelar'>Cancelar</span>
	</div>
	<div class='espaco'></div>
	<div class='conteudo'>
		<fieldset>
			<span class='titulo_dados'>Dados da empresa</span>
			<label class='label_campo esquerda'>Nome: </label>
			<input type='text' name='empresa' id='empresa' size='80' maxlength='70'>
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
		<fieldset style='text-align:center'>
			<span class='titulo_dados'>Tipos de transporte</span>
			<?php foreach ($lista_tipo_transporte as $lt) { ?>
			<input type='checkbox' name='tipo_transporte[]' id='tipo_<?php echo $lt['id']; ?>' value='<?php echo $lt['id']; ?>'>
			<label for='tipo_<?php echo $lt['id']; ?>'><?php echo $lt['tipo_transporte']; ?></label>
			<?php } ?>
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
	</div>
</form>
