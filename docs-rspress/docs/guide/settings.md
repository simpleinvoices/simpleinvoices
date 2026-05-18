# Settings & Configuration

Configure Simple Invoices to match your business needs.

## System Preferences

Access via **Settings → System Preferences**:

| Setting | Description |
|---------|-------------|
| **Company Name** | Your business name (appears on invoices) |
| **Company Address** | Street, city, state, ZIP |
| **Default Language** | Interface language (41+ available) |
| **Default Currency** | Primary currency for invoices |
| **Date Format** | How dates are displayed |
| **Invoice Numbering** | Prefix, suffix, and next number |
| **Tax Settings** | Default tax rate, tax display options |
| **PDF Settings** | Page size, margins, paper orientation |
| **Email Settings** | SMTP host, port, authentication, security |
| **Payment Gateways** | Enable and configure online payment processors |
| **Logging** | Debug and error logging levels |
| **Large Dataset Mode** | Optimizations for large data volumes |

## Custom Fields

See [Custom Fields](/help/custom-fields) for details on extending your data.

## Tax Rates

Configure tax rates under **Settings → Tax Rates**:

- Add multiple tax rates (e.g., GST, VAT, Sales Tax)
- Set the percentage and a display label
- Mark a tax as **default** to pre-select it on new invoices
- Tax rates can be overridden per invoice line item

## Invoice Preferences

Under **Settings → Invoice Preferences**:

- **Default Invoice Type**: Total or Itemised
- **Default Payment Type**: Preselected payment method
- **Invoice Footer**: Custom text or legal disclaimers
- **PDF Template**: Choose the invoice PDF layout

## Payment Types & Terms

- **Payment Types**: Define methods (Cash, Check, Card, Online, etc.)
- **Payment Terms**: Define due date rules (Net 30, Due on Receipt, etc.)

## Currencies

Manage currencies under **Settings → Currencies**:

- Enable/disable currencies
- Set exchange rates (for multi-currency reporting)
- The default currency is set in System Preferences

## Database Backup

Go to **Settings → Backup Database** to create a SQL dump of your data. Backups are saved in `tmp/database_backups/`.

## Options

Under **Settings → Options**:

- **Database Patches**: View and manage schema updates
- **Import/Export**: Bulk data import from JSON
- **System Info**: PHP and server configuration details
