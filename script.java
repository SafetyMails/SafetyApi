import java.net.HttpURLConnection;
import java.net.URL;
import java.io.OutputStream;
import java.io.BufferedReader;
import java.io.InputStreamReader;
import java.nio.charset.StandardCharsets;
import java.security.MessageDigest;
import java.util.Base64;
import javax.crypto.Mac;
import javax.crypto.spec.SecretKeySpec;

public class SafetyApi {
    public static String generateSha1(String input) throws Exception {
        MessageDigest md = MessageDigest.getInstance("SHA-1");
        byte[] hash = md.digest(input.getBytes(StandardCharsets.UTF_8));
        StringBuilder hexString = new StringBuilder();
        for (byte b : hash) {
            hexString.append(String.format("%02x", b));
        }
        return hexString.toString();
    }

    public static String generateHmacSha256(String key, String data) throws Exception {
        Mac hmac = Mac.getInstance("HmacSHA256");
        SecretKeySpec secretKey = new SecretKeySpec(key.getBytes(StandardCharsets.UTF_8), "HmacSHA256");
        hmac.init(secretKey);
        byte[] hash = hmac.doFinal(data.getBytes(StandardCharsets.UTF_8));
        return Base64.getEncoder().encodeToString(hash);
    }
    
    public static String safetyApi(String email) {
        String apiKey = "<APIKEY INFORMADA NO PAINEL SAFETYMAILS>";
        String tkOrigem = "<TK ORIGEM INFORMADA NO PAINEL SAFETYMAILS>";
        int timeout = 10000; // Timeout em milissegundos
    
        try {
            String sha1TkOrigem = generateSha1(tkOrigem);
            String urlStr = "https://" + tkOrigem + ".safetymails.com/api/" + sha1TkOrigem;
            String sfHmac = generateHmacSha256(apiKey, email);
    
            URL url = new URL(urlStr);
            HttpURLConnection conn = (HttpURLConnection) url.openConnection();
            conn.setRequestMethod("POST");
            conn.setRequestProperty("Sf-Hmac", sfHmac);
            conn.setDoOutput(true);
            conn.setConnectTimeout(timeout);
            conn.setReadTimeout(timeout);
    
            String postData = "email=" + email;
            try (OutputStream os = conn.getOutputStream()) {
                os.write(postData.getBytes(StandardCharsets.UTF_8));
            }
    
            int responseCode = conn.getResponseCode();
            StringBuilder response = new StringBuilder();
    
            try (BufferedReader br = new BufferedReader(new InputStreamReader(conn.getInputStream(), StandardCharsets.UTF_8))) {
                String line;
                while ((line = br.readLine()) != null) {
                    response.append(line);
                }
            }
    
            return "{ \"Status\": " + responseCode + ", \"Result\": " + response.toString() + " }";
        } catch (Exception e) {
            return "{ \"Status\": \"Erro\", \"Result\": \"" + e.getMessage() + "\" }";
        }
    }
    
    public static void main(String[] args) {
        String emailTeste = "teste@email.com";
        System.out.println(safetyApi(emailTeste));
    }
}
