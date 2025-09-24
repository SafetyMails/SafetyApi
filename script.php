function SafetyApi($email) {
$apiKey = '<APIKEY INFORMED IN THE SAFETYMAILS PANEL>';
$tkOrigem = '<TK_ORIGEM INFORMED IN THE SAFETYMAILS PANEL>';
$timeout = 10; // Connection timeout with Safetymails
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

# HTTPCODE 400 - Invalid parameters
# HTTPCODE 401 - Invalid API Key
# HTTPCODE 402 - Insufficient credits for the consultation
# HTTPCODE 403 - Access from a source other than the one registered
# HTTPCODE 406 - Invalid or inactive source ticket
# HTTPCODE 408 - Timeout
# HTTPCODE 429 - Hourly or minute query limit exceeded

return ['Status'=>$httpCode, 'Result'=>$result];
}

$emailTest = "test@email.com";
print_r(SafetyApi($emailTest));
