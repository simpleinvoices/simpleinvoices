# Email Settings

Configure Simple Invoices to send emails directly to your customers.

## SMTP Configuration

Go to **Settings → System Preferences → Email Settings**:

| Setting | Description | Example |
|---------|-------------|---------|
| **SMTP Host** | Your email server | `smtp.gmail.com` |
| **SMTP Port** | Server port number | `587` (TLS) or `465` (SSL) |
| **Use SMTP Authentication** | Enable if server requires login | `true` |
| **SMTP Username** | Your email address | `invoices@yourcompany.com` |
| **SMTP Password** | Your email password or app password |: |
| **Security** | Encryption method | `tls`, `ssl`, or `none` |
| **Use Local Sendmail** | Use server's built-in mail | `false` |

## Common SMTP Providers

| Provider | Host | Port | Security |
|----------|------|------|----------|
| **Gmail** | `smtp.gmail.com` | 587 | TLS |
| **Outlook/Office 365** | `smtp.office365.com` | 587 | TLS |
| **Yahoo** | `smtp.mail.yahoo.com` | 587 | TLS |
| **Zoho** | `smtp.zoho.com` | 587 | TLS |
| **SendGrid** | `smtp.sendgrid.net` | 587 | TLS |
| **Amazon SES** | `email-smtp.us-east-1.amazonaws.com` | 587 | TLS |
| **Mailgun** | `smtp.mailgun.org` | 587 | TLS |

> **Gmail Note:** You may need to use an [App Password](https://support.google.com/accounts/answer/185833) instead of your regular password if 2-factor authentication is enabled.

## Testing Email

After configuring, test email delivery:

1. Go to any invoice
2. Use the **Export** button to send via email
3. Check if the customer receives the email
4. Review `tmp/log/si.log` for any email errors

## Email Templates

Emails include:

- The invoice as a PDF attachment
- A message body with invoice summary
- Your company branding and contact details

## Troubleshooting

| Issue | Solution |
|-------|----------|
| **Connection refused** | Check host and port are correct |
| **Authentication failed** | Verify username and password |
| **Certificate error** | Try different security setting (TLS vs SSL) |
| **Email not received** | Check spam folder, verify recipient address |
| **Timeout** | Firewall may be blocking the SMTP port |
