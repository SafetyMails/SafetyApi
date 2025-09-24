function SafetyApi($email) {
$apiKey = '<APIKEY INFORMADA NO PAINEL SAFETYMAILS>';
$tkOrigem = '<TK ORIGEM INFORMADA NO PAINEL SAFETYMAILS>';
$timeout = 10; // Timeout da conexão com a Safetymails
$url = "https://{$tkOrigem}.safetymails.com/api/".sha1($tkOrigem);
$header[] = "Sf-Hmac: " . hash_hmac('sha256', $email, $apiKey);
$post['email'] = $email;

$process = curl_init($url);
curl_setopt($process, CURLOPT_HEADER, 0);
curl_setopt($process, CURLOPT_FRESH_CONNECT, 1);
curl_setopt($process, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($process, CURLOPT_FORBID_REUSE, 1);
curl_setopt($process, CURLOPT_TIMEOUT, $timeout);
curl_setopt($process, CURLOPT_ENCODING, '');
curl_setopt($process, CURLOPT_POST, 1);
curl_setopt($process, CURLOPT_HTTPHEADER, $header);
curl_setopt($process, CURLOPT_POSTFIELDS, http_build_query($post));

$ret = curl_exec($process);
curl_close($process);

$httpCode = curl_getinfo($process, CURLINFO_HTTP_CODE);

$result = NULL;

if ($ret === false && curl_errno($process) === CURLE_OPERATION_TIMEOUTED)
$httpCode = 408; // Timeout
elseif ($httpCode == 200)
$result = json_decode($ret, true);

# HTTPCODE 400 - Parâmetros inválidos
# HTTPCODE 401 - API Key inválida
# HTTPCODE 402 - Créditos insuficientes para a consulta
# HTTPCODE 403 - Acesso de uma origem diferente da cadastrada
# HTTPCODE 406 - Ticket Origem inválido ou inativo
# HTTPCODE 408 - Timeout
# HTTPCODE 429 - Limite de consultas por hora ou minuto ultrapassado

return ['Status'=>$httpCode, 'Result'=>$result];
}

$emailTeste = "teste@email.com";
print_r(SafetyApi($emailTeste));
