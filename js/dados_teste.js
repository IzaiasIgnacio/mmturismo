var clientes = [
    {
    	id: "1",
    	value: "Izaias Carmo Ign&aacute;cio",
    	cpf:"111.111.111-11",
    	rg: "222.222.222-2"
    },
    {
    	id: "2",
    	value: "Maura Gomes",
    	cpf: "333.333.333-33",
    	rg: "555.555.55-5"
    },
    {
    	id: "3",
    	value: "Marcelo Andrades",
    	cpf: "444.444.444-44",
    	rg: "777.777.777-7"
    },
    {
    	id: "4",
    	value: "Cliente 1",
    	cpf: "325.697.245-14",
    	rg: "XXX"
    },
    {
    	id: "5",
    	value: "Cliente 2",
    	cpf: "852.987.751-87",
    	rg: "YYY"
    },
    {
    	id: "6",
    	value: "Cliente 3",
    	cpf: "897.976.258-12",
    	rg: "ZZZ"
    }
];
var empresas_onibus = [
	"Empresa &Ocirc;nibus 1",
	"Empresa &Ocirc;nibus 2"
];
var empresas_aviao = [
	"Empresa Avi&atilde;o 1",
	"Empresa Avi&atilde;o 2"
];
var explicacao_principal = "Informar um t&iacute;tulo que identifique a viagem (Ex: Visita ao Pantanal) e a data de partida.";
var explicacao_destinos = "Informar Estado, Cidade e Local e/ou evento da viagem.<br>";
explicacao_destinos += "Podem ser informados m&uacute;ltiplos destinos.<br><br>";
explicacao_destinos += "Ex:<br>Santa Catarina - Penha - Beto Carrero World<br>";
explicacao_destinos += "Rio de Janeiro - Rio de Janeiro - Carnaval";
var explicacao_transportes = "Informar os transportes a serem utilizados na viagem.<br>";
explicacao_transportes += "Os transportes escolhidos poder&atilde;o ser associados ao clientes mais abaixo.";
var explicacao_clientes = "Informar a lista de clientes que participar&atilde;o da viagem.<br>";
explicacao_clientes += "Ao digitar o nome do cliente no campo correspondente uma lista ser&aacute; exibida de acordo com o que foi digitado.<br>";
explicacao_clientes += "Ser&aacute; poss&iacute;vel escolher em qual transporte cada cliente viajar&aacute; na coluna \"Transporte\".<br><br>";
explicacao_clientes += "Clientes \"cadastrados\" como exemplo: Izaias, Maura, Marcelo, Cliente 1, Cliente 2, Cliente 3.";
var explicacao_restaurantes = "Informar os restaurantes a serem visitados na viagem com data e hora de chegada.";
var explicacao_hoteis = "Informar os hot&eacute;is a serem visitados na viagem com data e hora de chegada.";