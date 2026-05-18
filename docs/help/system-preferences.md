# System Preferences

System Preferences control the global behavior of Simple Invoices.

## Access

Go to **Settings → System Preferences** to view and modify settings.

## General Settings

| Setting | Description |
|---------|-------------|
| **Company Name** | Your business name — displayed in headers, footers, and invoice templates |
| **Company Address** | Full address — appears on invoices and reports |
| **Default Language** | Interface language for all users (can be overridden per user) |
| **Default Currency** | Primary currency symbol and code for new invoices |
| **Date Format** | e.g., DD/MM/YYYY, MM/DD/YYYY, YYYY-MM-DD |
| **Currency Symbol Position** | Before or after the amount (e.g., $100 vs 100€) |

## Invoice Defaults

| Setting | Description |
|---------|-------------|
| **Default Invoice Type** | Total or Itemised |
| **Default Payment Type** | Pre-selected on new invoices |
| **Invoice Number Prefix** | e.g., "INV-" → INV-00123 |
| **Invoice Number Suffix** | Appended after the number |
| **Next Invoice Number** | Starting/next number in sequence |
| **Invoice Number Digits** | Pad to this many digits (e.g., 5 → 00123) |

## PDF Settings

| Setting | Description |
|---------|-------------|
| **Page Size** | A4, Letter, Legal |
| **Top/Bottom Margin** | Space above and below content |
| **Left/Right Margin** | Side margins |
| **Paper Orientation** | Portrait or Landscape |

## Email Settings

| Setting | Description |
|---------|-------------|
| **SMTP Host** | Your email server address |
| **SMTP Port** | Usually 587 (TLS) or 465 (SSL) |
| **SMTP Authentication** | Enable if your server requires login |
| **SMTP Username** | Email account username |
| **SMTP Password** | Email account password |
| **Security** | TLS, SSL, or none |
| **Use Local Sendmail** | Use server's sendmail instead of SMTP |

## Debug & Logging

| Setting | Description |
|---------|-------------|
| **Logging** | Enable/disable application logging |
| **Debug Level** | ALL, DEBUG, INFO, WARN, ERROR, FATAL, OFF |
| **Error Reporting** | PHP error reporting level |
| **Display Errors** | Show errors in browser (disable in production) |
| **Log File** | Path to the application log file (default: `tmp/log/si.log`) |
