<?php
require_once('controles/cliente.php');
controle_cliente('lista');
?>
<script type="text/javascript">
	$(function() {
		$(".btn_alterar").button({
			icons: {
				primary: "ui-icon-gear"
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

		//botao excluir cliente
		$(".btn_excluir").click(function() {
			$("#id_cliente").val($(this).closest('.linha').find('.id').val());
			//esconder barra de titulo do lightbox
			$(".ui-dialog-titlebar").hide();
			//exibir lightbox
			$("#confirmar_exclusao").dialog("open");
		});

		//botao alterar cliente
		$(".btn_alterar").click(function() {
			$("#id_cliente").val($(this).closest('.linha').find('.id').val());
			$("#form").submit();
		});

		$(".loading").fadeOut('fast',function() {
			$(".lista").fadeIn('fast');
		});
	});
</script>
<form id='form' method='post' action="?pag=views/cliente.alterar.php">
	<input type="hidden" id="id_cliente" name="id_cliente">
	<input type="hidden" id="acao" name="acao" value="lista">
	<div id='confirmar_exclusao' class='dialogo' style="display: none">
		<fieldset>
			<span class='titulo_dados'>Excluir Cliente</span>
			<label style='padding-left:200px'><b>Tem certeza que deseja excluir esse cliente?</b></label>
		</fieldset>
	</div>
	<div class='titulo_tela'>Lista de Clientes</div>
	<div class='espaco'></div>
	<div class='conteudo' id='lista_clientes'>
		<fieldset>
			<span class='titulo_dados'>Clientes</span>
			<label class='loading'><strong>&nbsp;&nbsp;Aguarde, carregando lista de clientes...</strong></label>
			<table id='tabela_clientes' class='lista' style="display: none">
				<thead>
					<tr>
						<td width='80%'>Nome</td>
						<td width='10%'>N&ordm; de viagens</td>
						<td width='10%' colspan="2">A&ccedil;&otilde;es</td>
					</tr>
				</thead>
				<tbody>
					<?php while ($l = mysql_fetch_assoc($lista)) { ?>
					<tr class='linha'>
						<input type="hidden" class="id" value="<?php echo $l['id']; ?>">
						<td align="left" style="padding-left: 5px"><?php echo utf8_encode($l['cliente']); ?></td>
						<td><?php echo $l['viagens']; ?></td>
						<td><span class='btn_alterar'>Alterar</span></td>
						<td><span class='btn_excluir'>Excluir</span></td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
		</fieldset>
	</div>
</form>
