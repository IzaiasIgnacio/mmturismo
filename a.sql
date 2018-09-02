insert into cliente (cliente,sexo,cpf,rg,passaporte,data_nascimento,data_casamento,endereco,numero,complemento,bairro,
cep,email,id_cidade,id_orgao_emissor,id_situacao);
select Nm_Cliente, Sexo, replace(replace(CPF,'.',''),'-',''), Nr_Registro, Nr_Passaporte, Dt_Nascimento, Dt_Aniv_Casal, Nm_Endereco, Nr_Endereco,
Compl_Endereco, Nm_Bairro, Nr_CEP, De_Email, Nm_Cidade, Nm_Orgao_Emissor, Lg_Inativo
from bkp_clientes