<?php
require_once('controles/acesso.php');
controle_acesso('alterar_dados');
?>
<script type="text/javascript">
	$(function() {
		//campos obrigatorios
		$("#form").validate({
			rules: {
				senha_atual: 'required',
				confirmacao: {
					equalTo: nova_senha
				}
			},
			//nao exibir mensagens de erro de validacao
			errorPlacement: function(error, element) {
				return false;
			}
		});
		
		//mensagens de validacao
		$("#senha_atual").tooltip("option","content","Informe a senha atual");
		$("#confirmacao").tooltip("option","content","Confirma&ccedil;&atilde;o inv&aacute;lida");

		//botao salvar
		$("#salvar").click(function() {
			//formulario invalida
			if (!$("#form").valid()) {
				$("#form .error").each(function() {
					$(this).tooltip({disabled: false});
					$(this).tooltip("open");
				});
			}
			else {
				//enviar formulario
				$("#form").submit();
			}
		});
	});
</script>
<form id='form' method='post'>
	<div class='titulo_tela'>Alterar Dados de Acesso</div>
	<div class='botoes'>
		<span class='btn_salvar' id='salvar'>Salvar</span>
		<span class='btn_cancelar' id='cancelar'>Cancelar</span>
	</div>
	<div class='espaco'></div>
	<div class='conteudo'>
		<fieldset id='fieldset_acesso'>
			<span class='titulo_dados'>Dados de acesso</span>
			<label class='label_campo esquerda'>Nome: </label>
			<input type='text' name='nome' id='nome' size='25' maxlength='20' value='<?php echo $_SESSION['usuario']; ?>'>
			<div class='espaco'></div>
			<label class='label_campo esquerda'>Login: </label>
			<input type='text' name='login' id='login' size='25' maxlength='20' value='<?php echo $_SESSION['login']; ?>'>
			<div class='espaco'></div>
			<label class='label_campo esquerda'>Senha atual: </label>
			<input type='password' name='senha_atual' id='senha_atual' size='12' maxlength='10'>
			<div class='espaco'></div>
			<label class='label_campo esquerda'>Nova senha: </label>
			<input type='password' name='nova_senha' id='nova_senha' size='12' maxlength='10'>
			<div class='espaco'></div>
			<label class='label_campo esquerda'>Confirma&ccedil;&atilde;o: </label>
			<input type='password' name='confirmacao' id='confirmacao' size='12' maxlength='10'>
		</fieldset>
	</div>
</form>