# SafetyAPI

Integration API for real-time email validation

## Installation

To start the integration, you will need access keys, which can be obtained from the SafetyMails admin panel by accessing the **Form Validation** menu.  

Create a new **Source** so that your access keys are generated.  

Once the source is created, you will have access to your **API_KEY** and **TICKET_SOURCE**.  

See more in [our documentation](https://docs.safetymails.com/pt-br/article/como-customizar-a-api-real-time).

### Query Syntax

```
https://<TICKET_SOURCE>.safetymails.com/api/<CODE_TICKET>
```

Where CODE_TICKET = SHA1(<TICKET_SOURCE>).  

Send the request with the `email` field in the POST body.

### HMAC Authentication

To ensure request security, it is necessary to authenticate API calls using HMAC (Hash-based Message Authentication Code). This mechanism ensures that the data has not been tampered with during transmission, as long as both the sender and receiver share a secret key.  

The hash function used must follow the HMAC-SHA256 standard, combining two elements:

- **VALUE**: the email to be verified  
- **KEY**: your API Key provided by the platform  

The formula to generate the hash is:

```
hash = HMAC_SHA256(VALUE, KEY);
```

This hash must be sent in the request header:

```
Sf-Hmac: (Hash Content)
```

**Example:**

```
Sf-Hmac: afc5382171c3745890b56********************4aa43506326b4e1fc993cb
```

### Sandbox

The integration provides a Sandbox environment so that tests can be performed without consuming credits from a real query.  

To use Sandbox, simply activate this feature when registering your source in the SafetyMails panel.  

The Sandbox only verifies whether all possible status returns are being properly received by your integration. No real validations are performed, and no credits are consumed while this option is active for your source.  

## Response

The query response is returned in JSON format and will indicate if any error occurred.  

| Field | Description |
| ----------- | ----------- |
| Success | Boolean (true or false). Indicates if the request was successfully executed. If false, it means the query was not performed due to issues like: incorrect API key, invalid or inactive ticket, malformed parameters, exceeded request limit, or lack of credits. |
| DomainStatus | Status of the queried email’s domain. |
| Status | Email validation result (if successful). Possible statuses: Valid, Role-based, Low deliverability, Disposable, Uncertain, Junk, Invalid, Invalid domain, Syntax error, and Pending. |
| Email | Queried email. |
| Limited | Indicates if the queried email belongs to a limited provider, i.e., one that only accepts a limited number of requests. |
| Public | Indicates if the queried email belongs to a ‘Corporate’ domain (private domains and/or those with private receiving rules) or an ‘Email provider’ (domains with public receiving rules). |
| Advice | A classification suggested by SafetyMails for the queried email status (Valid, Invalid, Risky, or Unknown) to simplify analysis. |
| Balance | Number of available query credits in your account. |
| Msg | Error message regarding a failed request (only when an error occurs). |

**Success Response**
```javascript
Object {
	"Success":true,
	"Email":"testeemail@safetymails.com",
	"Referer":"www.safetymails.com",
	"DomainStatus":"VALID",
	"Status":"VALID",
	"Advice":"Valid",
	"Public":null,
	"Limited":null,
	"Balance":4112343
}
```

**Error Response**
```javascript
Object {
	"Success":false,
	"Email":"testeemail@safetymails.com",
	"Referer":"www.safetymails.com",
	"Status":"PENDING",
	"Advice":"Unknown",
	"Msg":"Invalid referer"
}
```

### Email Status Descriptions

**Valid** - Email address whose existence has been confirmed  

**Invalid** - Email classified as non-existent or deactivated within a domain  

**Syntax Error** - Email that does not meet syntax rules established by email providers and RFC standards  

**Invalid Domain** - The domain does not exist or has multiple failures  

**Scraped** - Emails in this category may pose various risks, including blocks. Providers admit that these emails were created by scraper services (hence the name), which automatically generate addresses like "contact, admin, sales, etc." for multiple domains, characterizing spam. They may also belong to call centers or accounts without an individual responsible.  

**Pending** - Emails for which our database does not yet have information. After further analysis, these may still lack details. Credits for these queries are refunded to your account.  

**Uncertain** - Also known as "Accept All" or "Deny All." They either accept all messages or reject all messages, regardless of content. Results cannot be confirmed.  

**Disposable** - Emails from temporary address services. They are valid only for a short time (hours or minutes). After that, they become invalid and harm deliverability.  

**Unknown** - Emails whose servers are configured not to provide any user information. Therefore, confirmations cannot be obtained. This occurs more often with corporate emails.  

**Junk** - Email addresses not invalid by syntax but containing elements that providers will identify as junk, spam, or direct to a junk/spam folder. Examples include repeated characters, offensive words, or numeric sequences.  

**Limited** - Email addresses known to enforce volume-based filtering rules that may cause delivery issues, such as blocks.  

### Error Messages

| HTTP Code | Error | Description |
| ----------- | ----------- | ----------- |
| 400 | Invalid parameters | Incorrect or missing access keys. |
| 401 | Invalid API Key | Incorrect or missing access keys. |
| 402 | Invalid or inactive Source Ticket | You are trying to query an inactive API source. Go to your panel and activate the source correctly. |
| 403 | Source different from the registered one (%s)<>(%s) | You are trying to query an API source different from the one registered in your account. Check the source and try again. |
| 406 | Query limit per hour, minute, or day exceeded – Contact Support | Safetymails protects your form from misuse, allowing you to limit queries from the same IP. Additionally, all plans have hourly and per-minute query limits to prevent loops. To perform more queries than allowed, contact support (support@safetymails.com). |
| 429 | No credits available | Your account has no credits left for queries. You need to purchase more credits. |

For further assistance in configuring your API, contact our support team:  
📧 support@safetymails.com
