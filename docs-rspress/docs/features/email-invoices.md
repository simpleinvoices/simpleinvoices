# Email Invoices

Send invoices directly to customers via email: with PDF attachments, customisable templates, and support for CC/BCC.

## Email Configuration

Configure SMTP settings under **Settings → System Preferences**:

| Setting | Description |
|---------|-------------|
| **SMTP Host** | Your email server (e.g., `smtp.gmail.com`) |
| **SMTP Port** | Port number (587 for TLS, 465 for SSL) |
| **Authentication** | Username and password |
| **Security** | TLS or SSL encryption |

## Sending an Invoice

From any invoice's Quick View or Manage screen:

1. Click **Email Invoice**
2. The **To** field auto-fills from the customer's email
3. The **From** field auto-fills from the biller's email
4. Add CC/BCC recipients if needed
5. Customise the subject and message body
6. The invoice PDF is attached automatically
7. Click **Send**

## Email Engine

Powered by **PHPMailer 6.10**: a modern, well-maintained email library:

- Full SMTP authentication support
- TLS 1.2/1.3 encryption
- HTML email with plain-text fallback
- PDF attachment handling
- Multiple recipients with comma/semicolon separation

## Email Templates

Email bodies use Blade templates stored in `templates/emails/`. Customise the subject line, greeting, message body, and footer per your branding.
