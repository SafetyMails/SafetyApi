# SafetyAPI-Javascript
API de integração via Javascript para seu formulário

## Instalação

Para iniciar a integração serão necessárias as chaves de acesso, que podem se obtidas a partir do painel administrativo da SafetyMails, acessando o menu **Validação de Formulários**

Crie uma nova **Origem** para que as suas chaves de acesso sejam geradas

Assim que a origem for criada terá acesso a sua **API_KEY** e **TICKET_ORIGEM**

### Sintaxe da Consulta

https://optin.safetymails.com/main/safetyOptin/<**API_KEY**>/<**TICKET_ORIGEM**>/<**EMAIL_CODIFICADO**>
  
> &lt;**EMAIL_CODIFICADO**>: O e-mail deve ser codificado utilizando o protocolo “base64”, caso contrário o sistema não receberá com perfeição os dados.

### Sandbox

A integração oferece um ambiente de Sandbox para que possam ser feitos testes sem consumir seus créditos de uma consulta real.

Para utilizar a Sandbox basta mudar o comando da Sintaxe da URL de consulta como no exemplo abaixo:

http://optin.safetymails.com/main/sandbox/<**API_KEY**>/<**TICKET_ORIGEM**>/<**EMAIL_CODIFICADO**>

O Sandbox apenas verifica se todos os retornos de status possíveis estão chegando corretamente em sua integração. Não são feitas validações reais.

## Retorno

O retorno da consulta é no formato JSON, e informará caso a consulta retorne algum tipo de erro.

**IP/Dia**: Informa o IP origem da consulta.
**StatusCal**: Status da chamada: OK ou Failed.
**StatusEmail**: Resultado da validação do e-mail, em caso de sucesso.
**email**: E-mail consultado.
**public**: Informa se o e-mail consultado é de domínio público ou privado.
**referer**: Informa a origem da consulta em caso de chamada via javascript.
**MsgErro**: Retorna a mensagem de erro referente a falha na chamada.

**Retorno de Sucesso**
```javascript
Object {
	IP/Dia:"192.168.2.2"
	StatusCal:"OK"
	StatusEmail:"INVALIDO"
	email:"teste-safetyoptin@safetymails.com"
	public:false
	referer:"https://www.safetymails.com"
}
```
**Retorno de Erro**
```javascript
Object {
	IP/Dia:"192.168.2.2"
	StatusCal: "Failed",
	MsgErro : "Mensagem de erro"
	email:"teste-safetyoptin@safetymails.com"
	public:false
	referer:"https://www.safetymails.com"
}
```

### Descrição de Status de E-mail

**Válido** - Endereço de e-mail cuja existência foi confirmada

**Inválido** - E-mail classificado como inexistente ou desativado em um domínio

**Erro de Sintaxe** - E-mail que não atende às regras de sintaxe estipuladas pelos provedores de e-mails e RFCs de mercado

**Domínio Inválido** - O domínio não existe ou apresentou falhas diversas

**Scraped** - E-mails inseridos nesta categoria podem representar ameaças diversas, inclusive de bloqueios. Os provedores admitem que estes e-mails foram criados a partir de serviços de scrapers (daí seu nome), que são geradores automáticos de endereços "contato, adm, vendas, etc" para diversos domínios, caracterizando spam. Também podem ser e-mails de callcenters ou outros que não possuam um indivíduo responsável

**Pendente** - E-mails dos quais ainda não possuímos informações em nosso banco de dados. Após um período de nova análise, específica para estes endereços, podemos ainda não possuir todas as informações. Estes e-mails têm seus créditos estornados para sua conta.

**Incerto** - Também são conhecidos como "Accept All" e "Deny All". Ou seja, recebem todas as mensagens ou negam todas as mensagens, independentemente de seu conteúdo. O resultado não pode ser confirmado.

**Descartável** - E-mails provenientes de serviços de endereços temporários. Eles são válidos, mas apenas por algum tempo (horas ou minutos). Após este período, tornam-se inválidos e prejudicam sua entregabilidade.

**Desconhecido** - E-mails cujos servidores são configurados para não fornecer quaisquer informações de seus usuários. Desta forma, não é possível obter confirmações que os identifiquem. Ocorre mais em e-mails corporativos.

**Junk** - Estes são endereços de e-mail cuja sintaxa não os invalida, mas possuem elementos que serão identificados pelos provedores e direcionados para pasta junk, lixo eletrônico ou spam. E-mails com caracteres repetidos, palavrões, sequências numéricas e etc.

**Limitado** - Endereços de e-mails que, sabidamente, possuem regras de filtragem de mensagens por volume e poderão causar problemas na entrega, como bloqueios.

### Mensagens de Erro

**Sintaxe incorreta! Entre em contato com o Suporte**
Há um erro de sintaxe na url da chamada, verifique o tópico de sintaxe, aqui

**Acesso ao safetyCheck de um IP Inválido [%s]<>[%s]**
Um IP diferente do cadastrado para consultas pelo comando 'SafetyCheck'

**Excedido o limite de consultas do IP %s (%s)<>(%s)**
Para consultas com comando 'SafetyOptin' via JavaScript há um limite de tentativas/dia pelo mesmo IP de usuário, padrão: 20

### HTTP Erros

`HTTP CODE 422` Referer ou ApiKey Inválidos

`HTTP CODE 423` Acesso ao SafetyOptin de um Referer Inativo %

`HTTP CODE 424` Acesso por um SafetyOptin Inativo

`HTTP CODE 427` Sem créditos para realizar a pesquisa

