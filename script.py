import requests
import hashlib
import hmac

def safety_api(email):
    api_key = '<APIKEY INFORMED IN THE SAFETYMAILS PANEL>'
    tk_origem = '<SOURCE TICKET INFORMED IN THE SAFETYMAILS PANEL>'
    timeout = 10 # Timeout da conexão com a Safetymails

    url = "https://{tk_origem}.safetymails.com/api/{hashlib.sha1(tk_origem.encode()).hexdigest()}"

    sf_hmac = hmac.new(api_key.encode(), email.encode(), hashlib.sha256).hexdigest()
    headers = {"Sf-Hmac": sf_hmac}
    data = {"email": email}
    try:
        response = requests.post(url, headers=headers, data=data, timeout=timeout)
        http_code = response.status_code
        result = response.json() if http_code == 200 else None
    except requests.exceptions.RequestException as e:
        return {"Status": "400", "Result": str(e)}

    return {"Status": http_code, "Result": result}

# Exemplo de uso
if __name__ == "__main__":
    email_test = "test@email.com"
    resposta = safety_api(email_test)
    print(resposta)
