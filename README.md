# SafetyAPI

API de integração para validação de email em tempo Real

## Instalação

Para iniciar a integração serão necessárias as chaves de acesso, que podem se obtidas a partir do painel administrativo da SafetyMails, acessando o menu **Validação de Formulários**

Crie uma nova **Origem** para que as suas chaves de acesso sejam geradas

Assim que a origem for criada terá acesso a sua **API_KEY** e **TICKET_ORIGEM**

Veja mais em [nossa documentação](https://docs.safetymails.com/pt-br/article/como-customizar-a-api-real-time).

### Sintaxe da Consulta

https://<**TICKET_ORIGEM**>.safetymails.com/api/<**CODE_TICKET**>

Onde CODE_TICKET = SHA1(<TICKET_ORIGEM>).
  
Envie a requisição com o campo email no corpo do POST.

### Sandbox

A integração oferece um ambiente de Sandbox para que possam ser feitos testes sem consumir seus créditos de uma consulta real.

Para utilizar a Sandbox basta ativar este recurso no cadastro de sua origem no painel da SafetyMails.

O Sandbox apenas verifica se todos os retornos de status possíveis estão chegando corretamente em sua integração. Não são feitas validações reais, não são consumidos créditos enquanto esta opção estiver ativa em sua origem.

## Retorno

O retorno da consulta é no formato JSON, e informará caso a consulta retorne algum tipo de erro.

| Campo | Descrição |
| ----------- | ----------- |
| Success | Retorno do tipo bool (true ou false). Indica se a requisição foi executada com sucesso. Se retornar false, significa que a consulta não foi realizada, podendo haver falhas como: chave de API incorreta, ticket inválido ou inativo, parâmetros malformados, limite de requisições excedido ou falta de créditos. |
| DomainStatus | Status do domínio do e-mail consultado. |
| Status | Resultado da validação do e-mail, em caso de sucesso. Os status podem ser: Válidos, Role-based, baixa entregabilidade, descartável, incertos, junk, inválidos, domínio inválido, erro de sintaxe e pendentes. |
| Email | Email consultado. |
| Limited | Informa se o e-mail consultado é de um provedor limitado, ou seja, que recebe um número limitado de solicitações. |
| Public | Informa se o e-mail consultado é de domínio ‘Corporate’ (domínios privados e/ou que possuem regras privadas para recebimento) ou ‘Email provider’ (domínios que possuem regras públicas de recebimento). |
| Advice | É uma classificação sugerida pela SafetyMails para o status do e-mail consultado (Valid, Invalid, Risky ou Unknown) para facilitar a análise. |
| Balance | Quantidade de créditos para consulta disponíveis em sua conta. |
| Msg | Retorna a mensagem de erro referente a falha na chamada (apenas quando a chamada apresenta erro). |

**Retorno de Sucesso**
```javascript
Object {
	"Success":true,
	"Email":"testeemail@safetymails.com",
	"Referer":"www.safetymails.com",
	"DomainStatus":"VALIDO",
	"Status":"VALIDO",
	"Advice":"Valid",
	"Public":null,
	"Limited":null,
	"Balance":4112343
}
```
**Retorno de Erro**
```javascript
Object {
	"Success":false,
	"Email":"testeemail@safetymails.com",
	"Referer":"www.safetymails.com",
	"Status":"PENDENTE",
	"Advice":"Unknown",
	"Msg":"Referer inválido"
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

| HTTP Code | Erro | Descrição |
| ----------- | ----------- | ----------- |
| 400 | Parâmetros inválidos | Chaves de acesso incorretas ou não existentes. |
| 401 | API Key inválida | Chaves de acesso incorretas ou não existentes. |
| 402 | Ticket Origem inválido ou inativo | Você está tentando realizar consultas para uma origem API inativa. Vá ao seu painel e ative a origem corretamente. |
| 403 | Origem diferente da cadastrada (%s)<>(%s) | Você está tentando realizar consultas para uma origem API diferente da cadastrada em sua conta. Verifique a origem e tente novamente |
| 406 | Limite de consultas por hora ou minuto ou diário ultrapassado – Contacte o Suporte | A Safetymails oferece uma proteção ao seu formulário de uso indevido, permitindo que você limite as consultas vindas de um mesmo IP, além disso todos os planos possuem limites de consultas por hora e minuto, protegendo de erros que possam gerar loop. Para realizar mais consultas do que o previsto, entre em contato com o suporte (support@safetymails.com) |
| 429 | Sem créditos para realizar a pesquisa | Sua conta não possui créditos para realizar a consulta. É preciso adquirir créditos. |
