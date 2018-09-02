<?php
require_once ('controles/viagem.php');
controle_viagem('alterar');
?>
<script type="text/javascript">
	var destinos = new Array();
	var excluir_destinos = new Array();
	var transportes = new Array();
	var excluir_transportes = new Array();
	var restaurantes = new Array();
	var excluir_restaurantes = new Array();
	var hoteis = new Array();
	var excluir_hoteis = new Array();
	var clientes = new Array();
	var excluir_clientes = new Array();
	var sinais_transportes = new Array();
	var excluir_sinais_transportes = new Array();
	var sinais_restaurantes = new Array();
	var excluir_sinais_restaurantes = new Array();
	var sinais_hoteis = new Array();
	var excluir_sinais_hoteis = new Array();
	var excluir_rooming = new Array();
	var excluir_cliente_rooming = new Array();
	var div_sinal = '';
	var html_transportes = "<option value=''>Selecione</option>";
	var html_clientes = "<option value=''>Selecione</option>";
	$(function() {
		//campos obrigatorios
		$("#form").validate({
			rules: {
				nome_viagem: 'required'
			},
			//nao exibir mensagens de erro de validacao
			errorPlacement: function(error, element) {
				return false;
			}
		});

		//lightbox excluir viagem
		$("#confirmar_exclusao").dialog({
			buttons:{
				//botao adicionar
				Sim: function() {
					$.post('controles/viagem.jquery.php',{acao:'excluir_viagem',id:$("#id_viagem").val()},
					function(resposta) {
						$("#mensagem").html('Viagem Excluída');
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

		//lightbox adicionar destino
		$("#formulario_destino").dialog({
			buttons:{
				//botao adicionar
				Adicionar: function() {
					//estado informado
					var estado = $("#estado_destino").val();
					//cidade informada
					var cidade = $("#cidade_destino").val();
					//flag adicionar
					var add = 1;
					//validar estado
					if (estado == '') {
						//exibir erro de validacao
						$("#estado_destino").addClass('error');
						$("#estado_destino").tooltip({disabled: false});
						$("#estado_destino").tooltip("open");
						//nao adicionar
						add = 0;
					}
					//validar cidade 
					if (cidade == '') {
						//exibir erro de validacao
						$("#cidade_destino").addClass('error');
						$("#cidade_destino").tooltip({disabled: false});
						$("#cidade_destino").tooltip("open");
						//nao adicionar
						add = 0;
					}
					//adicionar
					if (add == 1) {
						//adicionar ao array
						destinos[destinos.length] = cidade;
						//criar linha na tabela de destinos
						var html;
						html = "<tr class='add_linha'>";
						html += "	<td>"+$("#estado_destino :selected").text()+"</td>";
						html += "	<td>"+$("#cidade_destino :selected").text()+"</td>";
						html += "	<td><div class='btn_remover' name='destino_"+(destinos.length-1)+"'></div></td>";
						html += "</tr>";
						adicionar_linha(html,"destinos");
						//fechar lightbox
						$(this).dialog("close");
					}
				},
				//botar cancelar
				Cancelar: function() {
					//fechar mensagens de erro de validacao
					$("#estado_destino").tooltip("close");
					$("#cidade_destino").tooltip("close");
					//fechar lightbox
					$(this).dialog("close");
				}
			}
		});

		//lightbox adicionar sinal
		$("#formulario_sinal").dialog({
			buttons:{
				//botao adicionar
				Adicionar: function() {
					//data informada
					var data = $("#data_sinal").val();
					//valor informado
					var valor = $("#valor_sinal").val();
					//flag adicionar
					var add = 1;
					//validar data
					if (data == '') {
						//exibir erro de validacao
						$("#data_sinal").addClass('error');
						$("#data_sinal").tooltip({disabled: false});
						$("#data_sinal").tooltip("open");
						//nao adicionar
						add = 0;
					}
					//validar valor 
					if (valor == '') {
						//exibir erro de validacao
						$("#valor_sinal").addClass('error');
						$("#valor_sinal").tooltip({disabled: false});
						$("#valor_sinal").tooltip("open");
						//nao adicionar
						add = 0;
					}
					//adicionar
					if (add == 1) {
						var posicao = '';
						var tabela = '';
						switch (div_sinal[1]) {
							case 'transportes':
								posicao = sinais_transportes.length;
								tabela = 'transportes';
								//adicionar ao array
								sinais_transportes[posicao] = new Array(div_sinal[2],data,valor);
							break; 
							case 'restaurantes':
								posicao = sinais_restaurantes.length;
								tabela = 'restaurantes';
								//adicionar ao array
								sinais_restaurantes[posicao] = new Array(div_sinal[2],data,valor);
							break; 
							case 'hoteis':
								posicao = sinais_hoteis.length;
								tabela = 'hoteis';
								//adicionar ao array
								sinais_hoteis[posicao] = new Array(div_sinal[2],data,valor);
							break; 
						}
						
						//criar linha na tabela de sinais
						var html;
						html = "<tr class='add_linha'>";
						html += "	<td>"+$("#data_sinal").val()+"</td>";
						html += "	<td>"+$("#valor_sinal").val()+"</td>";
						html += "	<td><div class='btn_remover_sinal' name='sinais_"+tabela+"_"+posicao+"'></div></td>";
						html += "</tr>";
						adicionar_linha(html,"tabela_sinais_"+tabela+"_"+div_sinal[2]);
						//fechar lightbox
						$(this).dialog("close");
					}
				},
				//botar cancelar
				Cancelar: function() {
					//fechar mensagens de erro de validacao
					$("#data_sinal").tooltip("close");
					$("#valor_sinal").tooltip("close");
					//fechar lightbox
					$(this).dialog("close");
				}
			}
		});
		
		//lightbox adicionar transporte
		$("#formulario_transporte").dialog({
			buttons:{
				//botao adicionar
				Adicionar: function() {
					//tipo informado
					var tipo = $("#tipo_transporte").val();
					//empresa informada
					var empresa = $("#empresa").val();
					//flag adicionar
					var add = 1;
					//validar tipo
					if (tipo == '') {
						//exibir erro de validacao
						$("#tipo_transporte").addClass('error');
						$("#tipo_transporte").tooltip({disabled: false});
						$("#tipo_transporte").tooltip("open");
						//nao adicionar
						add = 0;
					}
					//validar empresa 
					if (empresa == '') {
						//exibir erro de validacao
						$("#empresa").addClass('error');
						$("#empresa").tooltip({disabled: false});
						$("#empresa").tooltip("open");
						//nao adicionar
						add = 0;
					}
					//adicionar
					if (add == 1) {
						//adicionar ao array
						var posicao = transportes.length;
						transportes[posicao] = new Array($("#tipo_transporte :selected").text(),$("#empresa :selected").text(),empresa);
						//criar linha na tabela de transportes
						var html;
						html = "<tr class='add_linha'>";
						html += "	<td>"+$("#tipo_transporte :selected").text()+"</td>";
						html += "	<td>"+$("#empresa :selected").text()+"</td>";
						html += "	<td><input type='text' name='quantidade_transporte_"+posicao+"' id='quantidade_transporte_"+posicao+"' value='1' class='quantidade'></td>";
						html += "	<td><input type='text' name='contato_transporte_"+posicao+"' id='contato_transporte_"+posicao+"' class='contato'></td>";
						html += "	<td><input type='text' name='valor_transporte_"+posicao+"' id='valor_transporte_"+posicao+"' class='valor'></td>";
						html += "	<td><div class='btn_remover' name='transporte_"+posicao+"'></div></td>";
						html += "</tr>";
						html += "<tr>";
						html += "	<td colspan='6'>";
						html += "		<div class='sinais' id='sinais_transportes_"+posicao+"'><h3>Sinais</h3>";
						html += "			<div>";
						html += "				<table class='lista sinais_transportes' id='tabela_sinais_transportes_"+posicao+"'>";
						html += "					<thead>";
						html += "						<tr>";
						html += "							<td>Data</td>";
						html += "							<td>Valor</td>";
						html += "							<td>Remover</td>";
						html += "						</tr>";
						html += "					</thead>";
						html += "					<tbody>";
						html += "						<tr class='linha'>";
						html += "							<td colspan='3' class='vazio'>Nenhum sinal informado</td>";
						html += "						</tr>";
						html += "					</tbody>";
						html += "				</table>";
						html += "				<span class='btn_adicionar' name='add_sinal' id='add_sinal_transportes_"+posicao+"'>Adicionar Sinal</span>";
						html += "			</div>";
						html += "		</div>";
						html += "	</td>";
						html += "</tr>";
						adicionar_linha(html,"transportes");
						atualizar_transportes();
						//fechar lightbox
						$(this).dialog("close");
					}
				},
				//botar cancelar
				Cancelar: function() {
					//fechar mensagens de erro de validacao
					$("#tipo_transporte").tooltip("close");
					$("#empresa").tooltip("close");
					//fechar lightbox
					$(this).dialog("close");
				}
			}
		});

		//lightbox adicionar restaurante
		$("#formulario_restaurante").dialog({
			buttons:{
				//botao adicionar
				Adicionar: function() {
					//estado informado
					var estado = $("#estado_restaurante").val();
					//cidade informado
					var cidade = $("#cidade_restaurante").val();
					//restaurante informado
					var restaurante = $("#restaurante").val();
					//flag adicionar
					var add = 1;
					//validar estado
					if (estado == '') {
						//exibir erro de validacao
						$("#estado_restaurante").addClass('error');
						$("#estado_restaurante").tooltip({disabled: false});
						$("#estado_restaurante").tooltip("open");
						//nao adicionar
						add = 0;
					}
					//validar cidade 
					if (cidade == '') {
						//exibir erro de validacao
						$("#cidade_restaurante").addClass('error');
						$("#cidade_restaurante").tooltip({disabled: false});
						$("#cidade_restaurante").tooltip("open");
						//nao adicionar
						add = 0;
					}
					//validar restaurante 
					if (restaurante == '') {
						//exibir erro de validacao
						$("#restaurante").addClass('error');
						$("#restaurante").tooltip({disabled: false});
						$("#restaurante").tooltip("open");
						//nao adicionar
						add = 0;
					}
					//adicionar
					if (add == 1) {
						//adicionar ao array
						var posicao = restaurantes.length;
						restaurantes[posicao] = restaurante;
						//criar linha na tabela de restaurantes
						var html;
						html = "<tr class='add_linha'>";
						html += "	<td>"+$("#estado_restaurante :selected").text()+"</td>";
						html += "	<td>"+$("#cidade_restaurante :selected").text()+"</td>";
						html += "	<td>"+$("#restaurante :selected").text()+"</td>";
						html += "	<td><input type='text' name='data_restaurante_"+posicao+"' id='data_restaurante_"+posicao+"' size='8' class='data'></td>";
						html += "	<td><input type='text' name='hora_restaurante_"+posicao+"' id='hora_restaurante_"+posicao+"' size='4' class='hora'></td>";
						html += "	<td><input type='text' name='contato_restaurante_"+posicao+"' id='contato_restaurante_"+posicao+"' class='contato'></td>";
						html += "	<td><input type='text' name='valor_restaurante_"+posicao+"' id='valor_restaurante_"+posicao+"' class='valor'></td>";
						html += "	<td><div class='btn_remover' name='restaurante_"+posicao+"'></div></td>";
						html += "</tr>";
						html += "<tr>";
						html += "	<td colspan='8'>";
						html += "		<div class='sinais' id='sinais_restaurantes_"+posicao+"'><h3>Sinais</h3>";
						html += "			<div>";
						html += "				<table class='lista sinais_restaurantes' id='tabela_sinais_restaurantes_"+posicao+"'>";
						html += "					<thead>";
						html += "						<tr>";
						html += "							<td>Data</td>";
						html += "							<td>Valor</td>";
						html += "							<td>Remover</td>";
						html += "						</tr>";
						html += "					</thead>";
						html += "					<tbody>";
						html += "						<tr class='linha'>";
						html += "							<td colspan='3' class='vazio'>Nenhum sinal informado</td>";
						html += "						</tr>";
						html += "					</tbody>";
						html += "				</table>";
						html += "				<span class='btn_adicionar' name='add_sinal' id='add_sinal_restaurantes_"+posicao+"'>Adicionar Sinal</span>";
						html += "			</div>";
						html += "		</div>";
						html += "	</td>";
						html += "</tr>";
						adicionar_linha(html,"restaurantes");
						//fechar lightbox
						$(this).dialog("close");
					}
				},
				//botar cancelar
				Cancelar: function() {
					//fechar mensagens de erro de validacao
					$("#estado_restaurante").tooltip("close");
					$("#cidade_restaurante").tooltip("close");
					$("#restaurante").tooltip("close");
					//fechar lightbox
					$(this).dialog("close");
				}
			}
		});
		
		//lightbox adicionar hotel
		$("#formulario_hotel").dialog({
			buttons:{
				//botao adicionar
				Adicionar: function() {
					//estado informado
					var estado = $("#estado_hotel").val();
					//cidade informado
					var cidade = $("#cidade_hotel").val();
					//hotel informado
					var hotel = $("#hotel").val();
					//flag adicionar
					var add = 1;
					//validar estado
					if (estado == '') {
						//exibir erro de validacao
						$("#estado_hotel").addClass('error');
						$("#estado_hotel").tooltip({disabled: false});
						$("#estado_hotel").tooltip("open");
						//nao adicionar
						add = 0;
					}
					//validar cidade 
					if (cidade == '') {
						//exibir erro de validacao
						$("#cidade_hotel").addClass('error');
						$("#cidade_hotel").tooltip({disabled: false});
						$("#cidade_hotel").tooltip("open");
						//nao adicionar
						add = 0;
					}
					//validar hotel 
					if (hotel == '') {
						//exibir erro de validacao
						$("#hotel").addClass('error');
						$("#hotel").tooltip({disabled: false});
						$("#hotel").tooltip("open");
						//nao adicionar
						add = 0;
					}
					//adicionar
					if (add == 1) {
						//adicionar ao array
						var posicao = hoteis.length;
						hoteis[posicao] = hotel;
						//criar linha na tabela de restaurantes
						var html;
						html = "<tr class='add_linha'>";
						html += "	<td>"+$("#estado_hotel :selected").text()+"</td>";
						html += "	<td>"+$("#cidade_hotel :selected").text()+"</td>";
						html += "	<td>"+$("#hotel :selected").text()+"</td>";
						html += "	<td><input type='text' name='chegada_hotel_"+posicao+"' size='8' id='chegada_hotel_"+posicao+"' class='data'></td>";
						html += "	<td><input type='text' name='hora_hotel_"+posicao+"' size='4' id='hora_hotel_"+posicao+"' class='hora'></td>";
						html += "	<td><input type='text' name='saida_hotel_"+posicao+"' size='8' id='saida_hotel_"+posicao+"' class='data'></td>";
						html += "	<td><input type='text' name='contato_hotel_"+posicao+"' id='contato_hotel_"+posicao+"' class='contato'></td>";
						html += "	<td><input type='text' name='valor_hotel_"+posicao+"' id='valor_hotel_"+posicao+"' class='valor'></td>";
						html += "	<td><div class='btn_remover' name='hotel_"+posicao+"'></div></td>";
						html += "</tr>";
						html += "<tr>";
						html += "	<td colspan='9'>";
						html += "		<div class='sinais' id='sinais_hoteis_"+posicao+"'><h3>Sinais</h3>";
						html += "			<div>";
						html += "				<table class='lista sinais_hoteis' id='tabela_sinais_hoteis_"+posicao+"'>";
						html += "					<thead>";
						html += "						<tr>";
						html += "							<td>Data</td>";
						html += "							<td>Valor</td>";
						html += "							<td>Remover</td>";
						html += "						</tr>";
						html += "					</thead>";
						html += "					<tbody>";
						html += "						<tr class='linha'>";
						html += "							<td colspan='3' class='vazio'>Nenhum sinal informado</td>";
						html += "						</tr>";
						html += "					</tbody>";
						html += "				</table>";
						html += "				<span class='btn_adicionar' name='add_sinal' id='add_sinal_hoteis_"+posicao+"'>Adicionar Sinal</span>";
						html += "			</div>";
						html += "		</div>";
						html += "	</td>";
						html += "</tr>";
						adicionar_linha(html,"hoteis");
						atualizar_hoteis();
						//fechar lightbox
						$(this).dialog("close");
					}
				},
				//botar cancelar
				Cancelar: function() {
					//fechar mensagens de erro de validacao
					$("#estado_hotel").tooltip("close");
					$("#cidade_hotel").tooltip("close");
					$("#hotel").tooltip("close");
					//fechar lightbox
					$(this).dialog("close");
				}
			}
		});

		//autocompletar busca de cliente
		$("#buscar_cliente").livequery(function() {
			$(this).autocomplete({
				source: function(request, response){
					//buscar clientes
					$.post("controles/cliente.jquery.php", {acao:'buscar_clientes_viagem',nome:request.term},
					function(resposta){
						response($.map(resposta,
						function(item) {
							return {
								//valor para exibir na selecao
								label: item.cliente,
								//telefone principal
								telefone: item.telefone,
								//valor para exibir na tabela
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
						//verificar se o cliente já está na viagem
						if(!verificar_cliente(ui.item.value)) {
							//adicionar cliente ao array
							var posicao = clientes.length;
							clientes[posicao] = new Array(ui.item.value,ui.item.nome);
							//atualizar lista de clientes na rooming list
							atualizar_clientes();
							//criar linha na tabela clientes
							var html = '';
							var telefone = ui.item.telefone;
							if (telefone != null) {
								html_telefone = " - "+telefone+"</label> <div class='btn_info_viagem' name='info_"+ui.item.value+"'></div>";
							}
							else {
								html_telefone = "</label>";
							}
							html += "<tr class='linha_cliente'>";
							html += "	<td colspan='5'><label>"+ui.item.nome+html_telefone+"</td>";
							html += "</tr>";
							html += "<tr class='linha'>";
							html += "	<td class='td_esquerda'>";
							html += "		<select name='transporte_"+ui.item.value+"' id='transporte_"+ui.item.value+"' class='transporte_cliente'>";
							html += "			<option value=''>Selecione</option>";
							html += "		<select>";
							html += "		<input type='text' size='1' maxlength='3' name='numero_transporte_"+ui.item.value+"' id='numero_transporte_"+ui.item.value+"' class='numero_transporte'>";
							html += "	</td>";
							html += "	<td>";
							html += "		<input type='text' size='1' maxlength='3' name='poltrona_"+ui.item.value+"' id='poltrona_"+ui.item.value+"' class='poltrona'>";
							html += "	</td>";
							html += "	<td>";
							html += "		<select name='ponto_"+ui.item.value+"' id='ponto_"+ui.item.value+"'>";
							html += "			<option value=''>Selecione</option>";
							html += "			<?php echo $lista_ponto; ?>";
							html += "		<select>";
							html += "	</td>";
							html += "	<td>";
							html += "		<input type='text' size='2' name='embarque_"+ui.item.value+"' id='embarque_"+ui.item.value+"' class='hora'>";
							html += "	</td>";
							/*html += "	<td>";
							html += "		<input type='text' size='2' name='apto_"+ui.item.value+"' id='apto_"+ui.item.value+"' class='apto'>";
							html += "	</td>";*/
							html += "	<td>";
							html += "		<div class='btn_remover' name='cliente_"+posicao+"'></div>";
							html += "	</td>";
							html += "</tr>";
							html += "<tr>";
							html += "	<td colspan='5' class='linha_espaco'></td>";
							html += "</tr>";
							//esconder busca de cliente e exibir nova linha
							$("#clientes .linha_busca").fadeOut('normal',function() {
								adicionar_linha(html,"clientes");
								$("#transporte_"+ui.item.value).html(html_transportes);
							});
						}
						else {
							caixa_mensagem('Aviso','O cliente já está incluído nessa viagem');
						}
					}
				}
			});
		});

		//botao adicionar destino
		$("#add_destino").click(function() {
			//esconder barra de titulo do lightbox
			$(".ui-dialog-titlebar").hide();
			//esconder mensagens de erro
			$("#estado_destino").removeClass('error');
			$("#cidade_destino").removeClass('error');
			//resetar formulario
			$("#estado_destino").val('');
			$("#cidade_destino").html("<option value=''>Selecione o estado</option>");
			//exibir lightbox
			$("#formulario_destino").dialog("open");
		});

		//botao adicionar sinal
		$("#transportes, #restaurantes, #hoteis").on('click',"span[name=add_sinal]",function() {
			div_sinal = $(this).closest("div.sinais").attr('id').split("_");
			//esconder barra de titulo do lightbox
			$(".ui-dialog-titlebar").hide();
			//esconder mensagens de erro
			$("#data_sinal").removeClass('error');
			$("#valor_sinal").removeClass('error');
			//resetar formulario
			$("#data_sinal").val('');
			$("#valor_sinal").val('');
			//exibir lightbox
			$("#formulario_sinal").dialog("open");
		});
		
		//botao adicionar transporte
		$("#add_transporte").click(function() {
			//esconder barra de titulo do lightbox
			$(".ui-dialog-titlebar").hide();
			//esconder mensagens de erro
			$("#tipo_transporte").removeClass('error');
			$("#empresa").removeClass('error');
			//resetar formulario
			$("#tipo_transporte").val('');
			$("#empresa").val('');
			$("#empresa").html("<option value=''>Selecione o tipo de transporte</option>");
			//exibir lightbox
			$("#formulario_transporte").dialog("open");
		});
		
		//botao adicionar restaurante
		$("#add_restaurante").click(function() {
			//esconder barra de titulo do lightbox
			$(".ui-dialog-titlebar").hide();
			//esconder mensagens de erro
			$("#estado_restaurante").removeClass('error');
			$("#cidade_restaurante").removeClass('error');
			$("#restaurante").removeClass('error');
			//resetar formulario
			$("#estado_restaurante").val('');
			$("#cidade_restaurante").html("<option value=''>Selecione o estado</option>");
			$("#restaurante").html("<option value=''>Selecione a cidade</option>");
			//exibir lightbox
			$("#formulario_restaurante").dialog("open");
		});
		
		//botao adicionar hotel
		$("#add_hotel").click(function() {
			//esconder barra de titulo do lightbox
			$(".ui-dialog-titlebar").hide();
			//esconder mensagens de erro
			$("#estado_hotel").removeClass('error');
			$("#cidade_hotel").removeClass('error');
			$("#hotel").removeClass('error');
			//resetar formulario
			$("#estado_hotel").val('');
			$("#cidade_hotel").html("<option value=''>Selecione o estado</option>");
			$("#hotel").html("<option value=''>Selecione a cidade</option>");
			//exibir lightbox
			$("#formulario_hotel").dialog("open");
		});

		//botao adicionar cliente
		$("#add_cliente").click(function() {
			if ($("#clientes .vazio").is(':visible')) {
				$("#clientes .vazio").closest('tr').hide();
			}
			$("#clientes #buscar_cliente").val('');
			$("#clientes .linha_busca").insertAfter("#clientes > tbody > tr :last");
			$("#clientes .linha_busca").fadeIn();
		});

		//botao adicionar acomodacao
		$("#fieldset_rooming").on('click',".add_rooming",function() {
			var posicao = 0;
			var rooming = $(this).prev(".lista_rooming").attr('id').split("_");
			tabela = rooming[1];
			$(this).prev(".lista_rooming").children("tbody").find("tr:visible").each(function() {
				if ($(this).children('td').length > 1) {
					posicao++;
				}
			});
			var linha = '';
			linha = "<tr class='add_linha'>";
			linha += "	<td class='td_esquerda'>";
			linha += "		<select name='cliente_"+tabela+"_"+posicao+"[]' class='cliente_rooming' id='rooming_"+tabela+"_"+posicao+"_0'>";
			linha += 			html_clientes;
			linha += "		</select>";
			linha += "		<div class='btn_remover_rooming' name='rooming_"+tabela+"_"+posicao+"_0'></div>";
			linha += "		<div class='espaco'></div><span class='btn_add' id='add_"+tabela+"_"+posicao+"'></span>";
			linha += "	</td>";
			linha += "	<td>";
			linha += "		<select name='acomodacao_"+tabela+"_"+posicao+"' id='acomodacao_"+tabela+"_"+posicao+"'>";
			linha += "			<option value=''>Selecione</option>";
			linha += "			<?php echo $lista_acomodacao; ?>";
			linha += "		</select>";
			linha += "	</td>";
			linha += "	<td><input type='text' size='1' name='casal_"+tabela+"_"+posicao+"' id='casal_"+tabela+"_"+posicao+"' class='camas'></td>";
			linha += "	<td><input type='text' size='1' name='solteiro_"+tabela+"_"+posicao+"' id='solteiro_"+tabela+"_"+posicao+"' class='camas'></td>";
			linha += "	<td>";
			linha += "		<input type='text' size='2' name='apto_"+tabela+"_"+posicao+"' id='apto_"+tabela+"_"+posicao+"' class='apto'>";
			linha += "	</td>";
			linha += "	<td><div class='btn_remover' name='rooming_"+tabela+"_"+posicao+"'></div></td>";
			linha += "</tr>";
			adicionar_linha(linha,$(this).prev(".lista_rooming").attr('id'));
		});
		
		//botao adicionar rooming
		$("#fieldset_rooming").on('click',".add_tabela_rooming",function() {
			var linha = $("#fieldset_rooming > .hoteis_rooming").length;
			var html = '';
			html += "<div class='hoteis_rooming' id='hoteis_rooming_"+linha+"'></div>";
			html += "<table id='rooming_"+linha+"' class='lista lista_rooming'>";
			html += "	<thead>";
			html += "		<tr>";
			html += "			<td width='47%'>Clientes</td>";
			html += "			<td width='13%'>Acomoda&ccedil;&atilde;o</td>";
			html += "			<td width='14%'>Camas casal</td>";
			html += "			<td width='14%'>Camas solteiro</td>";
			html += "			<td width='5%'>Apto</td>";
			html += "			<td width='7%'>Remover</td>";
			html += "		</tr>";
			html += "	</thead>";
			html += "	<tbody>";
			html += "		<tr class='linha'>";
			html += "			<td colspan='6' class='vazio'>Nenhum dado informado</td>";
			html += "		</tr>";
			html += "	</tbody>";
			html += "</table>";
			html += "<span class='btn_adicionar add_rooming'>Adicionar Acomoda&ccedil;&atilde;o</span>";
			html += "<div class='espaco'></div>";
			$(this).prev('.espaco').after(html);
			atualizar_hoteis();
		});

		//adicionar cliente em uma acomodacao 
		$("#fieldset_rooming").on('click','.btn_add',function() {
			var posicao = $(this).attr('id').split("_");
			var num_cliente = $(this).closest('td').children(".cliente_rooming").length;
			var rooming = $(this).closest(".lista_rooming").attr('id').split("_");
			tabela = rooming[1];
			var linha = '';
			linha += "<select name='cliente_"+tabela+"_"+posicao[2]+"[]' class='cliente_rooming' id='rooming_"+tabela+"_"+posicao[2]+"_"+num_cliente+"'>";
			linha += 	html_clientes;
			linha += "</select>";
			linha += "<div class='btn_remover_rooming' name='rooming_"+tabela+"_"+posicao[2]+"_"+num_cliente+"'></div>";
			linha += "<div class='espaco'></div>";
			$(this).before(linha);
		});

		//remover cliente de uma acomodacao
		$("#fieldset_rooming").on('click','.btn_remover_rooming',function() {
			var id = $(this).attr('name');
			excluir_cliente_rooming[excluir_cliente_rooming.length] = $("#valor_cliente_"+id).val();
			$("#"+id).val('');
			$("#"+id).fadeOut();
			$(this).fadeOut();
		});

		//select de estado
		$("#estado_destino,#estado_restaurante,#estado_hotel").change(function() {
			var select = $(this).attr('id').split("_");
			//estado selecionado
			if ($(this).val() != '') {
				//listar cidades
				$.post('controles/cidade.jquery.php',{acao:'listar_cidades',estado:$(this).val()},
				function(resposta) {
					$("#cidade_"+select[1]).html(resposta);
				});
			}
			//nenhum estado selecionado
			else {
				$("#cidade_"+select[1]).html("<option value=''>Selecione o estado</option>");
			}
		});
		
		//select de tipo de transporte
		$("#tipo_transporte").change(function() {
			//tipo selecionado
			if ($(this).val() != '') {
				//listar empresas
				$.post('controles/empresa.jquery.php',{acao:'listar_empresas',tipo:$(this).val()},
				function(resposta) {
					$("#empresa").html(resposta);
				});
			}
			//nenhum tipo selecionado
			else {
				$("#empresa").html("<option value=''>Selecione o tipo de transporte</option>");
			}
		});

		//transporte do cliente escolhido, atualizar numeros disponiveis
		/*$("#clientes").on('change','.transporte_cliente',function() {
			var id = $(this).attr('id').split("_");
			$("#numero_transporte_"+id[1]).spinner("option","max",$("#quantidade_transporte_"+$(this).val()).val());
		});*/

		//remover item de uma lista
		$("#destinos, #transportes, #restaurantes, #hoteis, #clientes").on('click','.btn_remover',function(event) {
			//posicao a ser removida
			var posicao = $(this).attr('name').split("_");
			//tabela a ser editada
			var tabela = $(this).closest('table').attr('id');
			switch (tabela) {
				case 'destinos':
					//excluir do array
					delete(destinos[posicao[1]]);
					//excluir do banco
					excluir_destinos[excluir_destinos.length] = $("#destino_"+posicao[1]).val();
				break;
				case 'transportes':
					//excluir do array
					delete(transportes[posicao[1]]);
					//excluir do banco
					excluir_transportes[excluir_transportes.length] = $("#transporte_"+posicao[1]).val();
					//esconder linha
					$("#sinais_transportes_"+posicao[1]).closest('tr').fadeOut();
					//excluir todos os sinais
					$(sinais_transportes).each(function(i) {
						if (sinais_transportes[i][0] == posicao[1]) {
							delete(sinais_transportes[i]);
							//excluir do banco
							$("#sinais_transportes_"+posicao[1]+" .sinais_transportes tbody tr").each(function() {
								var id_sinal = $(this).children('.sinal_transporte').val();
								if (typeof (id_sinal) != 'undefined') {
									excluir_sinais_transportes[excluir_sinais_transportes.length] = id_sinal;	
								}
							});
						}
					});
					atualizar_transportes();
				break;
				case 'restaurantes':
					//excluir do array
					delete(restaurantes[posicao[1]]);
					//excluir do banco
					excluir_restaurantes[excluir_restaurantes.length] = $("#restaurante_"+posicao[1]).val();
					//esconder linha
					$("#sinais_restaurantes_"+posicao[1]).closest('tr').fadeOut();
					//excluir todos os sinais
					$(sinais_restaurantes).each(function(i) {
						if (sinais_restaurantes[i][0] == posicao[1]) {
							delete(sinais_restaurantes[i]);
							//excluir do banco
							$("#sinais_restaurantes_"+posicao[1]+" .sinais_restaurantes tbody tr").each(function() {
								var id_sinal = $(this).children('.sinal_restaurantes').val();
								if (typeof (id_sinal) != 'undefined') {
									excluir_sinais_restaurantes[excluir_sinais_restaurantes.length] = id_sinal;	
								}
							});
						}
					});
				break;
				case 'hoteis':
					//excluir do array
					delete(hoteis[posicao[1]]);
					//excluir do banco
					excluir_hoteis[excluir_hoteis.length] = $("#hotel_"+posicao[1]).val();
					//esconder linha
					$("#sinais_hoteis_"+posicao[1]).closest('tr').fadeOut();
					//excluir todos os sinais
					$(sinais_hoteis).each(function(i) {
						if (sinais_hoteis[i][0] == posicao[1]) {
							delete(sinais_hoteis[i]);
							//excluir do banco
							$("#sinais_hoteis_"+posicao[1]+" .sinais_hoteis tbody tr").each(function() {
								var id_sinal = $(this).children('.sinal_hotel').val();
								if (typeof (id_sinal) != 'undefined') {
									excluir_sinais_hoteis[excluir_sinais_hoteis.length] = id_sinal;	
								}
							});
						}
					});
					atualizar_hoteis();
				break;
				case 'clientes':
					//excluir do array
					delete(clientes[posicao[1]]);
					//excluir do banco
					excluir_clientes[excluir_clientes.length] = $("#viagem_cliente_"+posicao[1]).val();
				break;
			}
			if (tabela == 'clientes') {
				$(this).closest('tr').prev().switchClass("linha_cliente","remover_linha",500,function() {
					$(this).fadeOut('normal',function() {
						atualizar_clientes();
					});
				});
				$(this).closest('tr').next().fadeOut();
			}
			//efeito de exclusao
			$(this).closest('tr').switchClass("linha","remover_linha",500,function() {
				//esconder linha da tabela
				$(this).fadeOut('normal',function() {
					//se for a ultima linha, exibir linha de tabela vazia
					if ($("#"+tabela+" > tbody > tr:visible").length == 0) {
						$("#"+tabela+" .vazio").closest('tr').fadeIn();
					}
				});
			});
		});
		
		$("#fieldset_rooming").on('click','.btn_remover',function() {
			//posicao a ser removida
			var posicao = $(this).attr('name').split("_");
			//tabela a ser editada
			var rooming = $(this).closest(".lista_rooming").attr('id').split("_");
			tabela = rooming[1];
			//excluir do banco
			excluir_rooming[excluir_rooming.length] = $("#valor_rooming_"+tabela+"_"+posicao[2]).val();
			//efeito de exclusao
			$(this).closest('tr').switchClass("linha","remover_linha",500,function() {
				//esconder linha da tabela
				$(this).fadeOut('normal',function() {
					//se for a ultima linha, exibir linha de tabela vazia
					if ($(this).closest(".lista_rooming").children("tbody").find("tr:visible").length == 0) {
						$(this).closest(".lista_rooming").children("tbody").find(".vazio").closest('tr').fadeIn();
					}
				});
			});
		});
		
		//remover sinal
		$("#transportes, #restaurantes, #hoteis").on('click','.btn_remover_sinal',function(event) {
			//tabela a ser editada
			var tabela = $(this).attr('name').split("_");
			//id da tabela
			var id = $(this).closest('table').attr('id');
			switch (tabela[1]) {
				case 'transportes':
					//excluir do array
					delete(sinais_transportes[tabela[3]]);
					//excluir do banco
					var id_sinal = $(this).closest('tr').children('.sinal_transporte').val();
					if (typeof (id_sinal) != 'undefined') {
						excluir_sinais_transportes[excluir_sinais_transportes.length] = id_sinal;
					}
				break;
				case 'restaurantes':
					//excluir do array
					delete(sinais_restaurantes[tabela[2]]);
					//excluir do banco
					var id_sinal = $(this).closest('tr').children('.sinal_restaurante').val();
					if (typeof (id_sinal) != 'undefined') {
						excluir_sinais_restaurantes[excluir_sinais_restaurantes.length] = id_sinal;
					}
				break;
				case 'hoteis':
					//excluir do array
					delete(sinais_hoteis[tabela[2]]);
					//excluir do banco
					var id_sinal = $(this).closest('tr').children('.sinal_hotel').val();
					if (typeof (id_sinal) != 'undefined') {
						excluir_sinais_hoteis[excluir_sinais_hoteis.length] = id_sinal;
					}
				break;
			}
			//efeito de exclusao
			$(this).closest('tr').switchClass("linha","remover_linha",500,function() {
				//esconder linha da tabela
				$(this).fadeOut('normal',function() {
					//se for a ultima linha, exibir linha de tabela vazia
					if ($("#"+id+" > tbody > tr:visible").length == 0) {
						$("#"+id+" .vazio").closest('tr').fadeIn();
					}
				});
			});
		});
		
		//select de cidade do restaurante
		$("#cidade_restaurante").change(function() {
			//cidade selecionada
			if ($(this).val() != '') {
				//listar restaurantes
				$.post('controles/restaurante.jquery.php',{acao:'listar_restaurantes',cidade:$(this).val()},
				function(resposta) {
					$("#restaurante").html(resposta);
				});
			}
			//nenhuma cidade selecionada
			else {
				$("#restaurante").html("<option value=''>Selecione a cidade</option>");
			}
		});
		
		//select de cidade do hotel
		$("#cidade_hotel").change(function() {
			//cidade selecionada
			if ($(this).val() != '') {
				//listar restaurantes
				$.post('controles/hotel.jquery.php',{acao:'listar_hoteis',cidade:$(this).val()},
				function(resposta) {
					$("#hotel").html(resposta);
				});
			}
			//nenhuma cidade selecionada
			else {
				$("#hotel").html("<option value=''>Selecione a cidade</option>");
			}
		});

		//data de chegada do hotel alterada
		$("#hoteis").on('change',".data",function() {
			var id = $(this).attr('id').split("_");
			if (id[0] == 'chegada') {
				atualizar_hoteis();
			}
		});
		
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
		$(".numero_transporte, .camas").livequery(function() {
			$(this).spinner({
				max: 999,
				min: 1
			});
		});
		$(".camas").livequery(function() {
			$(this).spinner({
				max: 999,
				min: 0
			});
		});
		//numero de transportes de uma empresa atualizado
		$(".quantidade").livequery(function() {
			$(this).spinner({
				max: 999,
				min: 1/*,
				change: function(event,ui) {
					var id = $(this).attr('id').split("_");
					var valor = $(this).val();
					$(".transporte_cliente").each(function() {
						if ($(this).val() == id[1]) {
							var id_numero = $(this).attr('id').split("_");
							$("#numero_transporte_"+id_numero[1]).spinner("option","max",valor);
						}
					});
				}*/
			});
		});
		$(".valor").livequery(function() {
			$(this).maskMoney({
				thousands:'',
				decimal:','
			});
		});
		$(".data").livequery(function() {
			$(this).mask('99/99/9999');
		});
		$(".hora").livequery(function() {
			$(this).mask('99:99');
		});
		$('.contato').livequery(function() {
			$(this).mask("a?&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&");
		});
		$(".apto").livequery(function() {
			$(this).mask("*?****");
		});
		$(".btn_add").livequery(function() {
			$(this).button({
				icons: {
					primary: "ui-icon-plusthick"
				},
				text: false
			});
		});
		$(".btn_adicionar").livequery(function() {
			$(this).button({
				icons: {
					primary: "ui-icon-plusthick"
				}
			});
		});
		$('.sinais').livequery(function() {
			$(this).accordion({
				collapsible: true,
				active: false,
				heightStyle: "content"
			});
		});
		$(".hotel_rooming").livequery(function() {
			$(this).button();
		});
		$("#fieldset_rooming").on('click','.hotel_rooming',function() {
			if (!$(this).is(':checked')) {
				$("label[for="+$(this).attr('id')+"]").removeClass('ui-state-active ui-state-focus');
			}
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
					carregar_viagem(ui.item.value);
				}
			}
		});

		//mensagens de validacao
		$("#nome_viagem").tooltip("option","content","Informe o nome da viagem");
		$(".estado").tooltip("option","content","Escolha o estado");
		$(".cidade").tooltip("option","content","Escolha a cidade");
		$("#tipo_transporte").tooltip("option","content","Escolha o tipo de transporte");
		$("#empresa").tooltip("option","content","Escolha a empresa");
		$("#restaurante").tooltip("option","content","Escolha o restaurante");
		$("#hotel").tooltip("option","content","Escolha o hotel");
		$("#data_sinal").tooltip("option","content","Informe a data");
		$("#valor_sinal").tooltip("option","content","Informe o valor");

		//máscaras
		//70
		$("#nome_viagem").mask("%?&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&");
		//datas
		$("#data_saida_viagem").mask('99/99/9999');
		$("#data_sinal").mask('99/99/9999');
		//dinheiro
		$("#valor_viagem, #valor_sinal").maskMoney({
			thousands:'',
			decimal:','
		});

		//botao excluir viagem
		$("#excluir_viagem").click(function() {
			//esconder barra de titulo do lightbox
			$(".ui-dialog-titlebar").hide();
			//exibir lightbox
			$("#confirmar_exclusao").dialog("open");
		});
		
		//botao salvar
		$("#salvar").click(function() {
			//esconder mensagens de erro de validacao
			$("#nome_viagem").removeClass('error');

			//formulario invalido
			if (!$("#form").valid()) {
				//exibir mensagens de erro
				$("#nome_viagem").tooltip({disabled: false});
				$("#nome_viagem").tooltip("open");
			}
			//formulario valido
			else {
				//jogar valores do javascript em campos escondidos para o POST
				$("#lista_destinos").val(destinos.join("|"));
				$("#excluir_destinos").val(excluir_destinos.join("|"));
				$("#lista_transportes").val(transportes.join("|"));
				$("#excluir_transportes").val(excluir_transportes.join("|"));
				$("#sinais_transportes").val(sinais_transportes.join("|"));
				$("#excluir_sinais_transportes").val(excluir_sinais_transportes.join("|"));
				$("#lista_restaurantes").val(restaurantes.join("|"));
				$("#excluir_restaurantes").val(excluir_restaurantes.join("|"));
				$("#sinais_restaurantes").val(sinais_restaurantes.join("|"));
				$("#excluir_sinais_restaurantes").val(excluir_sinais_restaurantes.join("|"));
				$("#lista_hoteis").val(hoteis.join("|"));
				$("#excluir_hoteis").val(excluir_hoteis.join("|"));
				$("#sinais_hoteis").val(sinais_hoteis.join("|"));
				$("#excluir_sinais_hoteis").val(excluir_sinais_hoteis.join("|"));
				$("#lista_clientes").val(clientes.join("|"));
				$("#excluir_clientes").val(excluir_clientes.join("|"));
				var lista_rooming = new Array();
				var rooming_hotel = new Array();
				$('#fieldset_rooming .hoteis_rooming').each(function() {
					var hoteis = new Array();
					$(this).children(".hotel_rooming").each(function() {
						if ($(this).is(':checked')) {
							hoteis[hoteis.length] = $(this).val();
						}
					});
					var rooming = new Array();
					$(this).next(".lista_rooming").children("tbody").find("tr").each(function(i) {
						if ($(this).children('td').length > 1 && $(this).hasClass('linha')) {
							rooming[rooming.length] = (i-1);
						}
					});
					lista_rooming[rooming_hotel.length] = rooming;
					rooming_hotel[rooming_hotel.length] = hoteis;
				});
				$("#lista_rooming").val(lista_rooming.join("|"));
				$("#rooming_hotel").val(rooming_hotel.join("|"));
				$("#excluir_rooming").val(excluir_rooming.join("|"));
				$("#excluir_cliente_rooming").val(excluir_cliente_rooming.join("|"));
				//enviar formulario
				$("#form").submit();
			}
		});

		<?php if ($_POST) { ?>
		carregar_viagem(<?php echo $_POST['id_viagem']; ?>);
		<?php } ?>
	});

	//adicionar nova linha em uma tabela
	function adicionar_linha(linha,tabela) {
		if ($("#"+tabela+" .vazio").is(':visible')) {
			$("#"+tabela+" .vazio").closest('tr').hide();
		}
		$("#"+tabela+" > tbody > tr :last").after(linha);
		$("#"+tabela+" .add_linha").switchClass("add_linha","linha",700);
	}

	//atualizar transportes da viagem para cada cliente
	function atualizar_transportes() {
		html_transportes = "<option value=''>Selecione</option>";
		$(transportes).each(function(i) {
			if (typeof (transportes[i]) != 'undefined') {
				html_transportes += "<option value='"+transportes[i][2]+"'>"+transportes[i][1]+" ("+transportes[i][0]+")</option>";
			}
		});
		$(".transporte_cliente").each(function(i) {
			var valor = $(this).val();
			$(this).html(html_transportes);
			$(this).val(valor);
		});
	}
	
	//atualizar lista de cliente para o rooming list
	function atualizar_clientes() {
		html_clientes = "<option value=''>Selecione</option>";
		$(clientes).each(function(i) {
			if (typeof (clientes[i]) != 'undefined') {
				html_clientes += "<option value='"+clientes[i][0]+"'>"+clientes[i][1]+"</option>";
			}
		});
		$(".cliente_rooming").each(function(i) {
			var valor = $(this).val();
			$(this).html(html_clientes);
			$(this).val(valor);
		});
	}

	//atualizar lista de hoteis para rooming
	function atualizar_hoteis() {
		html_hoteis = "";
		$(hoteis).each(function(i) {
			if (typeof (hoteis[i]) != 'undefined') {
				var nome_hotel = $("div[name=hotel_"+i+"]").closest('tr').children('td:eq(2)').html();
				var data_hotel = $("div[name=hotel_"+i+"]").closest('tr').children('td:eq(3)').find('.data').val();
				var data = (data_hotel != '') ? " ("+data_hotel+")" : '';
				html_hoteis += "<input type='checkbox' class='hotel_rooming' value='"+i+"' id='hotel_rooming_"+i+"'>";
				html_hoteis += "<label for='hotel_rooming_"+i+"'>"+nome_hotel+data+"</label>";
			}
		});
		$(".hoteis_rooming").each(function(i) {
			var marcados = new Array();
			$(this).children(".hotel_rooming").each(function(j) {
				if ($(this).is(':checked')) {
					marcados[marcados.length] = $(this).attr('id');
				}
			});
			$(this).html(html_hoteis.replace(/hotel_rooming_/g,"hotel_rooming_"+i+"_"));
			$(marcados).each(function(k) {
				$("#"+marcados[k]).attr('checked',true);
				$("label[for="+marcados[k]+"]").addClass('ui-state-active ui-state-focus');
			});
		});
	}

	function carregar_viagem(id_viagem) {
		//buscar dados da viagem escolhida
		$.post('controles/viagem.jquery.php',{acao:'buscar_dados',viagem:id_viagem},
		function(resposta) {
			destinos = new Array();
			excluir_destinos = new Array();
			transportes = new Array();
			excluir_transportes = new Array();
			restaurantes = new Array();
			excluir_restaurantes = new Array();
			hoteis = new Array();
			excluir_hoteis = new Array();
			clientes = new Array();
			excluir_clientes = new Array();
			sinais_transportes = new Array();
			excluir_sinais_transportes = new Array();
			sinais_restaurantes = new Array();
			excluir_sinais_restaurantes = new Array();
			sinais_hoteis = new Array();
			excluir_sinais_hoteis = new Array();
			excluir_rooming = new Array();
			excluir_cliente_rooming = new Array();
			div_sinal = '';
			html_transportes = "<option value=''>Selecione</option>";
			html_clientes = "<option value=''>Selecione</option>";
			
			var dados = $.parseJSON(resposta);
			//preencher formulario
			$("#buscar_viagem").val(dados['viagem']);
			$("#id_viagem").val(dados['id']);
			$("#nome_viagem").val(dados['viagem']);
			$("#data_saida_viagem").val(dados['data_saida']);
			$("#valor_viagem").val(dados['valor']);

			//destinos
			$.post('controles/viagem.jquery.php',{acao:'exibir_destinos',id_viagem:dados['id']},
			function(resposta) {
				$("#destinos > tbody").html(resposta);
				$("#destinos > tbody > tr.linha:visible").each(function(i) {
					if ($(this).children('td').length > 1) {
						var cidade = $(this).children('input[name=valor_cidade_destino]').val();
						destinos[destinos.length] = cidade;
					}
				});
			});
			
			//restaurantes
			$.post('controles/viagem.jquery.php',{acao:'exibir_restaurantes',id_viagem:dados['id']},
			function(resposta) {
				$("#restaurantes > tbody").html(resposta);
				$("#restaurantes > tbody > tr.linha:visible").each(function(i) {
					if ($(this).children('td').length > 1) {
						var restaurante = $(this).children('input[name=valor_restaurante]').val();
						restaurantes[restaurantes.length] = restaurante;
					}
				});
				
				$("#restaurantes .sinais_restaurantes > tbody > tr.linha:visible").each(function(i) {
					if ($(this).children('td').length > 1) {
						var data = $(this).children('td:eq(0)').html();
						var valor = $(this).children('td:eq(1)').html();
						sinais_restaurantes[sinais_restaurantes.length] = new Array(i,data,valor);
					}
				});
			});
			
			//hoteis
			$.post('controles/viagem.jquery.php',{acao:'exibir_hoteis',id_viagem:dados['id']},
			function(resposta) {
				$("#hoteis > tbody").html(resposta);
				$("#hoteis > tbody > tr.linha:visible").each(function(i) {
					if ($(this).children('td').length > 1) {
						var hotel = $(this).children('input[name=valor_hotel]').val();
						hoteis[hoteis.length] = hotel;
					}
				});
				atualizar_hoteis();
				
				$("#hoteis .sinais_hoteis > tbody > tr.linha:visible").each(function(i) {
						if ($(this).children('td').length > 1) {
						var data = $(this).children('td:eq(0)').html();
						var valor = $(this).children('td:eq(1)').html();
						sinais_hoteis[sinais_hoteis.length] = new Array(i,data,valor);
					}
				});
			});
			
			//transportes
			$.post('controles/viagem.jquery.php',{acao:'exibir_transportes',id_viagem:dados['id']},
			function(resposta) {
				$("#transportes > tbody").html(resposta);
				$("#transportes > tbody > tr.linha:visible").each(function(i) {
					if ($(this).children('td').length > 1) {
						var tipo = $(this).children('td:eq(0)').html();
						var empresa = $(this).children('td:eq(1)').html();
						var id_empresa_tipo = $(this).children('input[name=valor_transporte]').val();
						transportes[transportes.length] = new Array(tipo,empresa,id_empresa_tipo);
					}
				});
				atualizar_transportes();
				
				$("#transportes .sinais_transportes > tbody > tr.linha:visible").each(function(i) {
					if ($(this).children('td').length > 1) {
						var data = $(this).children('td:eq(0)').html();
						var valor = $(this).children('td:eq(1)').html();
						sinais_transportes[sinais_transportes.length] = new Array(i,data,valor);
					}
				});
				
				//clientes
				$.post('controles/viagem.jquery.php',{acao:'exibir_clientes',id_viagem:dados['id'],
				pontos:"<?php echo $lista_ponto; ?>",transportes:html_transportes},
				function(resposta) {
					$("#clientes > tbody").html(resposta);
					$("#clientes > tbody > tr.linha_cliente:visible").each(function(i) {
						var nome = $(this).children('td:eq(0)').find('label').html().split(" - ");
						var id_cliente = $(this).children('.valor_cliente').val();
						clientes[clientes.length] = new Array(id_cliente,nome[0]);
					});
					atualizar_clientes();

					//rooming list
					$.post('controles/viagem.jquery.php',{acao:'exibir_rooming',id_viagem:dados['id'],
					acomodacao:"<?php echo $lista_acomodacao; ?>",clientes:html_clientes},
					function(resposta) {
						if (resposta != 0) {
							$("#fieldset_rooming").html(resposta);
							atualizar_hoteis();
							$(".hoteis_rooming").each(function(i) {
								var hotel_rooming = $(this).attr('id');
								$.post('controles/viagem.jquery.php',{acao:'selecionar_hoteis',
								id_viagem:dados['id'],indice:i},
								function(resposta) {
									if (resposta != '') {
										var hoteis_rooming = resposta.split(",");
										$("#"+hotel_rooming+" .hotel_rooming").each(function(j) {
											if ($.inArray($("#hotel_"+j).val(), hoteis_rooming) != -1) {
												$(this).attr('checked',true);
												$("label[for="+$(this).attr('id')+"]").addClass('ui-state-active ui-state-focus');
											}
										});
									}
								});
							});
						}
						else {
							var html = '';
							html += "<span class='titulo_dados'>Rooming List</span>";
							html += "<div class='hoteis_rooming' id='hoteis_rooming_0'></div>";
							html += "<table id='rooming_0' class='lista lista_rooming'>";
							html += "<thead>";
							html += "		<tr>";
							html += "			<td width='47%'>Clientes</td>";
							html += "			<td width='13%'>Acomoda&ccedil;&atilde;o</td>";
							html += "			<td width='14%'>Camas casal</td>";
							html += "			<td width='14%'>Camas solteiro</td>";
							html += "			<td width='5%'>Apto</td>";
							html += "			<td width='7%'>Remover</td>";
							html += "		</tr>";
							html += "	</thead>";
							html += "	<tbody>";
							html += "		<tr class='linha'>";
							html += "			<td colspan='6' class='vazio'>Nenhum dado informado</td>";
							html += "		</tr>";
							html += "	</tbody>";
							html += "</table>";
							html += "<span class='btn_adicionar add_rooming'>Adicionar Acomoda&ccedil;&atilde;o</span>";
							html += "<div class='espaco'></div>";
							html += "<span class='btn_adicionar add_tabela_rooming'>Adicionar Rooming List</span>";
							$("#fieldset_rooming").html(html);
						}
					});
				});
			});
			
			//exibir formulario
			$("#dados_viagem").fadeIn();
			$(".botoes").fadeIn();
		});
	}
	
	//verificar se um cliente já está na viagem
	function verificar_cliente(cliente) {
		var retorno = false;
		$(clientes).each(function(i) {
			if (clientes[i][0] == cliente) {
				retorno = true;
			}
		});
		return retorno;
	}
</script>
<form id='form' method='post'>
	<input type="hidden" id="lista_destinos" name="lista_destinos">
	<input type="hidden" id="excluir_destinos" name="excluir_destinos">
	<input type="hidden" id="lista_transportes" name="lista_transportes">
	<input type="hidden" id="excluir_transportes" name="excluir_transportes">
	<input type="hidden" id="sinais_transportes" name="sinais_transportes">
	<input type="hidden" id="excluir_sinais_transportes" name="excluir_sinais_transportes">
	<input type="hidden" id="lista_restaurantes" name="lista_restaurantes">
	<input type="hidden" id="excluir_restaurantes" name="excluir_restaurantes">
	<input type="hidden" id="sinais_restaurantes" name="sinais_restaurantes">
	<input type="hidden" id="excluir_sinais_restaurantes" name="excluir_sinais_restaurantes">
	<input type="hidden" id="lista_hoteis" name="lista_hoteis">
	<input type="hidden" id="excluir_hoteis" name="excluir_hoteis">
	<input type="hidden" id="sinais_hoteis" name="sinais_hoteis">
	<input type="hidden" id="excluir_sinais_hoteis" name="excluir_sinais_hoteis">
	<input type="hidden" id="lista_clientes" name="lista_clientes">
	<input type="hidden" id="excluir_clientes" name="excluir_clientes">
	<input type="hidden" id="lista_rooming" name="lista_rooming">
	<input type="hidden" id="rooming_hotel" name="rooming_hotel">
	<input type="hidden" id="excluir_rooming" name="excluir_rooming">
	<input type="hidden" id="excluir_cliente_rooming" name="excluir_cliente_rooming">
	<input type="hidden" id="id_viagem" name="id_viagem">
	<div id='confirmar_exclusao' class='dialogo'>
		<fieldset>
			<span class='titulo_dados'>Excluir Viagem</span>
			<label style='padding-left:200px'><b>Tem certeza que deseja excluir essa viagem?</b></label>
		</fieldset>
	</div>
	<div id='formulario_destino' class='dialogo'>
		<fieldset>
			<span class='titulo_dados'>Adicionar Destino</span>
			<label class='label_campo'>Estado: </label>
			<select name='estado_destino' id='estado_destino' class='estado'>
				<option value=''>Selecione</option>
				<?php echo $lista_estado; ?>
			</select>
			<label class='label_campo'>Cidade: </label>
			<select name='cidade_destino' id='cidade_destino' class='cidade'>
				<option value=''>Selecione o estado</option>
			</select>
		</fieldset>
	</div>
	<div id='formulario_sinal' class='dialogo'>
		<fieldset>
			<span class='titulo_dados'>Adicionar Sinal</span>
			<label class='label_campo'>Data: </label>
			<input type='text' name='data_sinal' id='data_sinal' size='12' maxlength='10'>
			<label class='label_campo'>Valor: </label>
			<input type='text' name='valor_sinal' id='valor_sinal' size='12' maxlength='10'>
		</fieldset>
	</div>
	<div id='formulario_transporte' class='dialogo'>
		<fieldset>
			<span class='titulo_dados'>Adicionar Transporte</span>
			<label class='label_campo'>Tipo: </label>
			<select name='tipo_transporte' id='tipo_transporte'>
				<option value=''>Selecione</option>
				<?php while ($l = mysql_fetch_array($lista_tipo_transporte)) { ?>
				<option value='<?php echo $l['id']; ?>'><?php echo $l['tipo_transporte']; ?></option>
				<?php } ?>
			</select>
			<label class='label_campo'>Empresa: </label>
			<select name='empresa' id='empresa'>
				<option value=''>Selecione o tipo de transporte</option>
			</select>
		</fieldset>
	</div>
	<div id='formulario_restaurante' class='dialogo'>
		<fieldset id='fieldset_restaurante'>
			<span class='titulo_dados'>Adicionar Restaurante</span>
			<label class='label_campo esquerda'>Estado: </label>
			<select name='estado_restaurante' id='estado_restaurante' class='estado'>
				<option value=''>Selecione</option>
				<?php echo $lista_estado; ?>
			</select>
			<label class='label_campo'>Cidade: </label>
			<select name='cidade_restaurante' id='cidade_restaurante' class='cidade'>
				<option value=''>Selecione o estado</option>
			</select>
			<div class='espaco'></div>
			<label class='label_campo esquerda'>Resturante: </label>
			<select name='restaurante' id='restaurante'>
				<option value=''>Selecione a cidade</option>
			</select>
		</fieldset>
	</div>
	<div id='formulario_hotel' class='dialogo'>
		<fieldset id='fieldset_hotel'>
			<span class='titulo_dados'>Adicionar Hotel</span>
			<label class='label_campo esquerda'>Estado: </label>
			<select name='estado_hotel' id='estado_hotel' class='estado'>
				<option value=''>Selecione</option>
				<?php echo $lista_estado; ?>
			</select>
			<label class='label_campo'>Cidade: </label>
			<select name='cidade_hotel' id='cidade_hotel' class='cidade'>
				<option value=''>Selecione o estado</option>
			</select>
			<div class='espaco'></div>
			<label class='label_campo esquerda'>Hotel: </label>
			<select name='hotel' id='hotel'>
				<option value=''>Selecione a cidade</option>
			</select>
		</fieldset>
	</div>
	<div class='titulo_tela'>Consultar / Alterar Viagem</div>
	<div class='botoes' style='display:none'>
		<span class='btn_salvar' id='salvar'>Salvar</span>
		<span class='btn_cancelar' id='cancelar'>Cancelar</span>
		<span class='btn_excluir' id='excluir_viagem' style='margin-left:185px'>Excluir Viagem</span>
	</div>
	<div class='espaco'></div>
	<div class='conteudo'>
		<fieldset id='fieldset_consulta'>
			<span class='titulo_dados'>Buscar Viagem</span>
			<label class='label_campo esquerda'>Nome: </label>
			<input type='text' name='buscar_viagem' id='buscar_viagem' size='80' maxlength='70'>
		</fieldset>
	</div>
	<div class='conteudo' id='dados_viagem' style='display:none'>
		<fieldset id='fieldset_informacoes_viagem'>
			<span class='titulo_dados'>Informa&ccedil;&otilde;es Principais</span>
			<label class='label_campo'>Nome da Viagem: </label>
			<input type='text' name='nome_viagem' id='nome_viagem' size='80' maxlength='70'>
			<label class='label_campo'>Data de Sa&iacute;da: </label>
			<input type='text' name='data_saida_viagem' id='data_saida_viagem' size='8' maxlength='10'>
			<br>
			<label class='label_campo'>Valor: </label>
			<input type='text' name='valor_viagem' id='valor_viagem' size='7' maxlength='10'>
		</fieldset>
		<fieldset id='fieldset_destinos'>
			<span class='titulo_dados'>Destinos</span>
			<table id='destinos' class='lista'>
				<thead>
					<tr>
						<td width='10%'>Estado</td>
						<td width='80%'>Cidade</td>
						<td width='10%'>Remover</td>
					</tr>
				</thead>
				<tbody>
					<tr class='linha'>
						<td colspan='3' class='vazio'>Nenhum destino informado</td>
					</tr>
				</tbody>
			</table>
			<span class='btn_adicionar' id='add_destino'>Adicionar Destino</span>
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
						<td width='10%'>Remover</td>
					</tr>
				</thead>
				<tbody>
					<tr class='linha'>
						<td colspan='7' class='vazio'>Nenhum Transporte informado</td>
					</tr>
				</tbody>
			</table>
			<span class='btn_adicionar' id='add_transporte'>Adicionar Transporte</span>
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
						<td rowspan='2' width='10%'>Remover</td>
					</tr>
					<tr>
						<td width='5%'>Data</td>
						<td width='5%'>Hora</td>
					</tr>
				</thead>
				<tbody>
					<tr class='linha'>
						<td colspan='8' class='vazio'>Nenhum restaurante informado</td>
					</tr>
				</tbody>
			</table>
			<span class='btn_adicionar' id='add_restaurante'>Adicionar Restaurante</span>
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
						<td rowspan='2' width='10%'>Remover</td>
					</tr>
					<tr>
						<td width='10%'>Data</td>
						<td width='10%'>Hora</td>
					</tr>
				</thead>
				<tbody>
					<tr class='linha'>
						<td colspan='9' class='vazio'>Nenhum hotel informado</td>
					</tr>
				</tbody>
			</table>
			<span class='btn_adicionar' id='add_hotel'>Adicionar Hotel</span>
		</fieldset>
		<fieldset id='fieldset_clientes'>
			<span class='titulo_dados'>Clientes</span>
			<table id='clientes' class='lista'>
				<thead>
					<tr>
						<td rowspan='2' width='46%'>Transporte</td>
						<td rowspan='2' width='5%'>Poltrona</td>
						<td colspan='2' width='39%'>Embarque</td>
						<td rowspan='2' width='10%'>Remover</td>
					</tr>
					<tr>
						<td width='28%'>Ponto</td>
						<td width='11%'>Hora</td>
					</tr>
				</thead>
				<tbody>
					<tr class='linha'>
						<td colspan='5' class='vazio'>Nenhum cliente informado</td>
					</tr>
					<tr class='linha_busca' style='display:none'>
						<td colspan='5' class='td_esquerda td_busca'>
							<input type='text' name='buscar_cliente' id='buscar_cliente' size='91' maxlength='80'>
						</td>
					</tr>
				</tbody>
			</table>
			<span class='btn_adicionar' id='add_cliente'>Adicionar Cliente</span>
		</fieldset>
		<fieldset id='fieldset_rooming'>
			<span class='titulo_dados'>Rooming List</span>
			<div class='hoteis_rooming' id='hoteis_rooming_0'></div>
			<table id='rooming_0' class='lista lista_rooming'>
				<thead>
					<tr>
						<td width='50%'>Clientes</td>
						<td width='15%'>Acomoda&ccedil;&atilde;o</td>
						<td width='14%'>Camas casal</td>
						<td width='14%'>Camas solteiro</td>
						<td width='5%'>Apto</td>
						<td width='7%'>Remover</td>
					</tr>
				</thead>
				<tbody>
					<tr class='linha'>
						<td colspan='6' class='vazio'>Nenhum dado informado</td>
					</tr>
				</tbody>
			</table>
			<span class='btn_adicionar add_rooming'>Adicionar Acomoda&ccedil;&atilde;o</span>
			<div class='espaco'></div>
			<span class='btn_adicionar add_tabela_rooming'>Adicionar Rooming List</span>
		</fieldset>
	</div>
</form>