package main

import (
    "crypto/hmac"
    "crypto/sha1"
    "crypto/sha256"
    "encoding/hex"
    "fmt"
    "io/ioutil"
    "net/http"
    "net/url"
    "strings"
    "time"
)

const (
    apiKey = "<APIKEY INFORMADA NO PAINEL SAFETYMAILS>"
    tkOrigem = "<TK ORIGEM INFORMADA NO PAINEL SAFETYMAILS>"
    timeout = 10 * time.Second // Timeout da conexão
)

// Função para gerar SHA1
func generateSHA1(input string) string {
    h := sha1.New()
    h.Write([]byte(input))
    return hex.EncodeToString(h.Sum(nil))
}

// Função para gerar HMAC-SHA256
func generateHMACSHA256(key, data string) string {
    h := hmac.New(sha256.New, []byte(key))
    h.Write([]byte(data))
    return hex.EncodeToString(h.Sum(nil))
}

// Function that makes the request to the API
func safetyAPI(email string) (map[string]interface{}, error) {
    urlStr := fmt.Sprintf("https://%s.safetymails.com/api/%s", tkOrigem, generateSHA1(tkOrigem))
    hmacSignature := generateHMACSHA256(apiKey, email)
    
    // Creating data in form-data format
    formData := url.Values{}
    formData.Set("email", email)
    formEncoded := formData.Encode() // "email=teste@email.com"
    
    // Creating the POST request
    client := http.Client{Timeout: timeout}
    req, err := http.NewRequest("POST", urlStr, strings.NewReader(formEncoded))
    if err != nil {
        return nil, err
    }
    
    // Defining headers
    req.Header.Set("Sf-Hmac", hmacSignature)
    req.Header.Set("Content-Type", "application/x-www-form-urlencoded") // Envio como form-data

    // Sending the request
    resp, err := client.Do(req)
    if err != nil {
        return nil, err
    }
    defer resp.Body.Close()
    
    // Read reply
    body, err := ioutil.ReadAll(resp.Body)
    if err != nil {
        return nil, err
    }
    
    // Creating the return
    result := map[string]interface{}{
        "Status": resp.StatusCode,
        "Result": string(body), // Retorna o JSON da API como string
    }
    
    return result, nil
}

func main() {
    email := "teste@email.com"
    resposta, err := safetyAPI(email)
    if err != nil {
        fmt.Println("Erro:", err)
    } else {
        fmt.Println(resposta)
    }
}
