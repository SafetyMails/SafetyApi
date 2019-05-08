# SafetyAPI-Javascript
API de integração via Javascript para seu formulário

<h2>Instalação</h2>

<p>Para iniciar a integração serão necessárias as chaves de acesso, que podem se obtidas a partir do painel administrativo da SafetyMails, acessando o menu <b>Validação de Formulários</b></p>

<p>Crie uma nova <b>Origem</b> para que as suas chaves de acesso sejam geradas</p>

<p>Assim que a origem for criada terá acesso a sua <b>API_KEY</b> e <b>TICKET_ORIGEM</b></p>

<h3>Sintaxe da Consulta</h3>

<p>https://optin.safetymails.com/main/safetyOptin/<<b>API_KEY</b>>/<<b>TICKET_ORIGEM</b>>/<<b>EMAIL_CODIFICADO</b>></p>
  
<p><b>&lt;EMAIL_CODIFICADO></b>: O e-mail deve ser codificado utilizando o protocolo “base64”, caso contrário o sistema não receberá com perfeição os dados.</p>

<h3>Sandbox</h3>

<p>A integração oferece um ambiente de Sandbox para que possam ser feitos testes sem consumir seus créditos de uma consulta real.</p>

<p>Para utilizar a Sandbox basta mudar o comando da Sintaxe da URL de consulta como no exemplo abaixo:</p>

<p>http://optin.safetymails.com/main/sandbox/<<b>API_KEY</b>>/<<b>TICKET_ORIGEM</b>>/<<b>EMAIL_CODIFICADO</b>></p>

<p>O Sandbox apenas verifica se todos os retornos de status possíveis estão chegando corretamente em sua integração. Não são feitas validações reais.</p>

<h2>Retorno</h2>

<code>
Object {
	IP/Dia:"192.168.2.2"
	StatusCal:"OK"
	StatusEmail:"INVALIDO"
	email:"teste-safetyoptin@safetymails.com"
	public:false
	referer:"https://www.safetymails.com"
}
</code>
