<script type="text/javascript">
	$(function() {
		//campos obrigatorios
		$("#form").validate({
			//nao exibir mensagens de erro de validacao
			errorPlacement: function(error, element) {
				return false;
			}
		});
		$("#bairros").tooltip("option","content","Escolha o bairro");		
		//icone de telefones
		$("#clientes").on('click','.btn_info_viagem',function() {
			//nome do cliente a ser exibido no lightbox
			var nome_cliente = $(this).closest('tr').children('td:eq(0)').find('label').html().split(" - ");
			//id do cliente
			var id_cliente = $(this).attr('name').split("_");
			//buscar telefones
			$.post('controles/cliente.jquery.php',{acao:'exibir_telefones',id_cliente:id_cliente[1]},
			function(resposta) {
				//exibir lightbox com resultado
				caixa_mensagem('Lista de telefones de '+nome_cliente[0],resposta);
			});
		});

		//campos gerados dinamicamente
		$('.sinais').livequery(function() {
			$(this).accordion({
				collapsible: true,
				active: false,
				heightStyle: "content"
			});
		});
		
		//autocompletar busca de viagem
		$("#buscar_viagem").autocomplete({
			source: function(request, response){
				//buscar viagens
				$.post("controles/viagem.jquery.php", {acao:'buscar_viagens',viagem:request.term},
				function(resposta){
					response($.map(resposta,
					function(item) {
						return {
							//valor para exibir na selecao
							label: item.viagem+" "+item.data_saida,
							//id da viagem
							value: item.id
						};
					}));
				}, "json");
			},
			dataType: "json",
			delay: 1000,
			cache: false,
			minLength: 3,
			//viagem selecionada
			select: function(event, ui) {
				//prevenir acoes padroes do plugin
				event.preventDefault();
				//viagem encontrada
				if (ui.item.value != 0) {
					//buscar dados da viagem escolhida
					$.post('controles/viagem.jquery.php',{acao:'buscar_dados',viagem:ui.item.value},
					function(resposta) {
						var dados = $.parseJSON(resposta);
						//preencher formulario
						$("#buscar_viagem").val(dados['viagem']);
						$("#id_viagem").val(dados['id']);
						$("#viagem").val(dados['viagem']);
						$("#nome_viagem").html(dados['viagem']);
						$("#data").val(dados['data_saida']);
						$("#data_saida_viagem").html(dados['data_saida']);
						$("#valor_viagem").html(dados['valor']);

						//destinos
						$.post('controles/viagem.jquery.php',{acao:'exibir_destinos_relatorios',id_viagem:dados['id']},
						function(resposta) {
							$("#destinos > tbody").html(resposta);
						});
						
						//restaurantes
						$.post('controles/viagem.jquery.php',{acao:'exibir_restaurantes_relatorios',id_viagem:dados['id']},
						function(resposta) {
							$("#restaurantes > tbody").html(resposta);
						});
						
						//hoteis
						$.post('controles/viagem.jquery.php',{acao:'exibir_hoteis_relatorios',id_viagem:dados['id']},
						function(resposta) {
							$("#hoteis > tbody").html(resposta);
						});
						
						//transportes
						$.post('controles/viagem.jquery.php',{acao:'exibir_transportes_relatorios',id_viagem:dados['id']},
						function(resposta) {
							$("#transportes > tbody").html(resposta);
						});
						
						//clientes
						$.post('controles/viagem.jquery.php',{acao:'exibir_clientes_relatorios',id_viagem:dados['id']},
						function(resposta) {
							$("#clientes > tbody").html(resposta);
						});
						
						//bairros
						$.post('controles/viagem.jquery.php',{acao:'buscar_bairros',id_viagem:dados['id']},
						function(resposta) {
							$("#bairros").html(resposta);
						});
						
						//rooming list
						$.post('controles/viagem.jquery.php',{acao:'exibir_rooming_relatorios',id_viagem:dados['id']},
						function(resposta) {
							$("#fieldset_rooming").html(resposta);
						});
						
						//exibir formulario
						$("#dados_viagem").fadeIn();
					});
				}
			}
		});

		$(".btn_relatorio").button();
		$(".visualizar").button({
			icons: {
				primary: 'ui-icon-note'
			}
		});

		$(".btn_relatorio").click(function() {
			$("#fieldset_relatorios_viagem").children('.btn_relatorio').removeClass('ui-state-focus');
			$(this).addClass('ui-state-focus');
			$("#cabecalho").val('');
			$("#bairros").val('');
			$("#bairros").tooltip("close");
			switch ($(this).attr('id')) {
				case 'etiquetas':
					$("#parametros").slideDown('normal',function() {
						$("#bairros").rules('add','required');
						$("#cabecalho").fadeOut();
						$("#bairros").fadeIn();
						$("#label_bairros").fadeIn();
						$("#label_cabecalho").fadeOut();
						$("#form").attr('action','controles/viagem.etiquetas.php');
					});
				break;
				case 'seguradora':
					$("#parametros").slideDown('normal',function() {
						$("#bairros").rules('remove');
						$("#cabecalho").fadeOut();
						$("#bairros").fadeOut();
						$("#label_bairros").fadeOut();
						$("#label_cabecalho").fadeOut();
						$("#form").attr('action','controles/viagem.xls_seguradora.php');
					});
				break;
				case 'transporte':
					$("#parametros").slideDown('normal',function() {
						$("#bairros").rules('remove');
						$("#cabecalho").fadeIn();
						$("#bairros").fadeOut();
						$("#label_bairros").fadeOut();
						$("#label_cabecalho").fadeIn();
						$("#form").attr('action','controles/viagem.lista_transporte.php');
					});
				break;
				case 'xls_transporte':
					$("#parametros").slideDown('normal',function() {
						$("#bairros").rules('remove');
						$("#cabecalho").fadeOut();
						$("#bairros").fadeOut();
						$("#label_bairros").fadeOut();
						$("#label_cabecalho").fadeOut();
						$("#form").attr('action','controles/viagem.xls_transporte.php');
					});
				break;
				case 'rooming':
					$("#parametros").slideDown('normal',function() {
						$("#bairros").rules('remove');
						$("#cabecalho").fadeIn();
						$("#bairros").fadeOut();
						$("#label_bairros").fadeOut();
						$("#label_cabecalho").fadeIn();
						$("#form").attr('action','controles/viagem.rooming_list.php');
					});
				break;
				case 'pontos':
					$("#parametros").slideDown('normal',function() {
						$("#bairros").rules('remove');
						$("#cabecalho").fadeOut();
						$("#bairros").fadeOut();
						$("#label_bairros").fadeOut();
						$("#label_cabecalho").fadeOut();
						$("#form").attr('action','controles/viagem.embarque.php');
					});
				break;
				case 'contatos':
					$("#parametros").slideDown('normal',function() {
						$("#bairros").rules('remove');
						$("#cabecalho").fadeOut();
						$("#bairros").fadeOut();
						$("#label_bairros").fadeOut();
						$("#label_cabecalho").fadeOut();
						$("#form").attr('action','controles/viagem.contatos.php');
					});
				break;
			}
		});
		
		$(".visualizar").click(function() {
			if ($("#form").valid()) {
				$("#output").val('I');
				$("#form").submit();
			}
			else {		
				$("#bairros").tooltip({disabled: false});
				$("#bairros").tooltip("open");
			}
			
		});
		$(".btn_salvar").click(function() {
			if ($("#form").valid()) {
				$("#output").val('D');
				$("#form").submit();
			}
			else {		
				$("#bairros").tooltip({disabled: false});
				$("#bairros").tooltip("open");
			}
			
		});
	});
</script>
<form id='form' method='post' target='_blank'>
	<input type="hidden" id="id_viagem" name="id_viagem">
	<input type="hidden" id="viagem" name="viagem">
	<input type="hidden" id="data" name="data">
	<input type="hidden" id="output" name="output">
	<div class='titulo_tela'>Relat&oacute;rios de Viagem</div>
	<div class='conteudo'>
		<fieldset id='fieldset_consulta'>
			<span class='titulo_dados'>Buscar Viagem</span>
			<label class='label_campo esquerda'>Nome: </label>
			<input type='text' name='buscar_viagem' id='buscar_viagem' size='80' maxlength='70'>
		</fieldset>
	</div>
	<div class='conteudo' id='dados_viagem' style='display:none'>
		<fieldset id='fieldset_relatorios_viagem'>
			<span class='titulo_dados'>Relat&oacute;rios</span>
			<span class='btn_relatorio' id='seguradora'>Lista para seguradora</span>
			<span class='btn_relatorio' id='transporte'>Lista para empresa de transporte</span>
			<span class='btn_relatorio' id='xls_transporte'>Lista de passageiros .xls</span>
			<div class='espaco'></div>
			<span class='btn_relatorio' id='rooming'>Rooming list</span>
			<span class='btn_relatorio' id='etiquetas'>Etiquetas de bagagem</span>
			<span class='btn_relatorio' id='pontos'>Pontos de embarque</span>
			<span class='btn_relatorio' id='contatos'>Contato dos passageiros</span>
			<div id='parametros' style='display:none'>
				<label id='label_cabecalho' style='display:none'>Cabe&ccedil;alho:<br></label>
				<textarea name='cabecalho' id='cabecalho' style='display:none;width:700px;height:100px'></textarea>
				<label id='label_bairros' style='display:none'>Bairro:<br></label>
				<select name='bairros' id='bairros' style='display:none'>
				</select>
				<div class='espaco'></div>
				<span class='visualizar'>Visualizar</span>
				<span class='btn_salvar'>Salvar Relat&oacute;rio</span>
			</div>
		</fieldset>
		<fieldset id='fieldset_informacoes_viagem'>
			<span class='titulo_dados'>Informa&ccedil;&otilde;es Principais</span>
			<label class='label_campo'>Nome da Viagem: </label>
			<label class='valores' id='nome_viagem'></label>
			<label class='label_campo'>Data de Sa&iacute;da: </label>
			<label class='valores' id='data_saida_viagem'></label>
			<label class='label_campo'>Valor: </label>
			<label class='valores' id='valor_viagem'></label>
		</fieldset>
		<fieldset id='fieldset_destinos'>
			<span class='titulo_dados'>Destinos</span>
			<table id='destinos' class='lista'>
				<thead>
					<tr>
						<td width='10%'>Estado</td>
						<td width='80%'>Cidade</td>
					</tr>
				</thead>
				<tbody>
					<tr class='linha'>
						<td colspan='2' class='vazio'>Nenhum destino informado</td>
					</tr>
				</tbody>
			</table>
		</fieldset>
		<fieldset id='fieldset_transportes'>
			<span class='titulo_dados'>Transportes</span>
			<table id='transportes' class='lista'>
				<thead>
					<tr>
						<td width='10%'>Tipo</td>
						<td width='50%'>Empresa</td>
						<td width='10%'>Quantidade</td>
						<td width='10%'>Contato</td>
						<td width='10%'>Valor</td>	
					</tr>
				</thead>
				<tbody>
					<tr class='linha'>
						<td colspan='6' class='vazio'>Nenhum Transporte informado</td>
					</tr>
				</tbody>
			</table>
		</fieldset>
		<fieldset id='fieldset_restaurantes'>
			<span class='titulo_dados'>Restaurantes</span>
			<table id='restaurantes' class='lista'>
				<thead>
					<tr>
						<td rowspan='2' width='10%'>Estado</td>
						<td rowspan='2' width='10%'>Cidade</td>
						<td rowspan='2' width='40%'>Restaurante</td>
						<td colspan='2' width='10%'>Chegada</td>
						<td rowspan='2' width='10%'>Contato</td>
						<td rowspan='2' width='10%'>Valor</td>
					</tr>
					<tr>
						<td width='5%'>Data</td>
						<td width='5%'>Hora</td>
					</tr>
				</thead>
				<tbody>
					<tr class='linha'>
						<td colspan='7' class='vazio'>Nenhum restaurante informado</td>
					</tr>
				</tbody>
			</table>
		</fieldset>
		<fieldset id='fieldset_hoteis'>
			<span class='titulo_dados'>Hot&eacute;is</span>
			<table id='hoteis' class='lista'>
				<thead>
					<tr>
						<td rowspan='2' width='10%'>Estado</td>
						<td rowspan='2' width='10%'>Cidade</td>
						<td rowspan='2' width='20%'>Hotel</td>
						<td colspan='2' width='20%'>Chegada</td>
						<td rowspan='2' width='10%'>Data de Sa&iacute;da</td>
						<td rowspan='2' width='10%'>Contato</td>
						<td rowspan='2' width='10%'>Valor</td>
					</tr>
					<tr>
						<td width='10%'>Data</td>
						<td width='10%'>Hora</td>
					</tr>
				</thead>
				<tbody>
					<tr class='linha'>
						<td colspan='8' class='vazio'>Nenhum hotel informado</td>
					</tr>
				</tbody>
			</table>
		</fieldset>
		<fieldset id='fieldset_clientes'>
			<span class='titulo_dados'>Clientes</span>
			<table id='clientes' class='lista'>
				<thead>
					<tr>
						<td rowspan='2' width='46%'>Transporte</td>
						<td rowspan='2' width='5%'>Poltrona</td>
						<td colspan='2' width='35%'>Embarque</td>
					</tr>
					<tr>
						<td width='28%'>Ponto</td>
						<td width='11%'>Hora</td>
					</tr>
				</thead>
				<tbody>
					<tr class='linha'>
						<td colspan='4' class='vazio'>Nenhum cliente informado</td>
					</tr>
				</tbody>
			</table>
		</fieldset>
		<fieldset id='fieldset_rooming'>
			<span class='titulo_dados'>Rooming  List</span>
			<table id='rooming' class='lista'>
				<thead>
					<tr>
						<td width='47%'>Clientes</td>
						<td width='13%'>Acomoda&ccedil;&atilde;o</td>
						<td width='14%'>Camas casal</td>
						<td width='14%'>Camas solteiro</td>
						<td width='5%'>Apto</td>
					</tr>
				</thead>
				<tbody>
					<tr class='linha'>
						<td colspan='5' class='vazio'>Nenhum dado informado</td>
					</tr>
				</tbody>
			</table>
		</fieldset>
	</div>
</form>
