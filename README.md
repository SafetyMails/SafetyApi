# SafetyAPI-Javascript
API de integração via Javascript para seu formulário

<h2>Instalação</h2>

<p>Para iniciar a integração serão necessárias as chaves de acesso, que podem se obtidas a partir do painel administrativo da SafetyMails, acessando o menu <b>Validação de Formulários</b></p>

<p>Crie uma nova <b>Origem</b> para que as suas chaves de acesso sejam geradas</p>

<p>Assim que a origem for criada terá acesso a sua <b>API_KEY</b> e <b>TICKET_ORIGEM</b></p>

<h3>Sintaxe da Consulta</h3>

<p>https://optin.safetymails.com/main/safetyOptin/<<b>API_KEY</b>>/<<b>TICKET_ORIGEM</b>>/<<b>EMAIL_CODIFICADO</b>></p>
  
<p><b><EMAIL_CODIFICADO></b>: O e-mail deve ser codificado utilizando o protocolo “base64”, caso contrário o sistema não receberá com perfeição os dados.</p>
