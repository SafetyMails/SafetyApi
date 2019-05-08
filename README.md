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
