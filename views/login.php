<?php
require_once('controles/acesso.php');
controle_acesso('login');
?>
<script>
	$(function() {
		//campos obnrigatorios
		$("#form").validate({
			rules: {
				login: 'required',
				senha: 'required',
			},
			//nao exibir mensagens de erro de validacao
			errorPlacement: function(error, element) {
				return false;
			}
		});
		
		//mensagens de validacao
		$("#login").tooltip("option","content","Informe o login");
		$("#senha").tooltip("option","content","Informe a senha");
		
		//botao entrar
		$(".btn_entrar").click(function() {
			//formulario invalido
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
<div id='login_topo'><label>SISTEMA MMTURISMO</label></div>
<div id='login_form'>
	<form id='form' method='post'>
		<div>
			<label>LOGIN: </label>
			<input type='text' name='login' id='login' size='30' maxlength='15'>
			<div class='espaco'></div>
			<label>SENHA: </label>
			<input type='password' name='senha' id='senha' size='30' maxlength='15'>
			<div class='espaco'></div>
			<span class='btn_entrar'>Entrar</span>
		</div>
	</form>
</div>