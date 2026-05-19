# Tax IDs

Customers and billers can store up to two tax IDs each. These appear on invoices, PDFs, and in the customer portal.

## Tax ID 1: Primary Tax Identifier

| Country | Label | Format Example |
|---------|-------|----------------|
| USA | EIN | 12-3456789 |
| EU | VAT | DE123456789 |
| UK | VAT | GB123456789 |
| Australia | ABN | 12 345 678 901 |
| New Zealand | GST | 123-456-789 |
| Canada | GST/HST | 123456789RT0001 |
| India | GSTIN | 22AAAAA0000A1Z5 |
| Singapore | GST Reg No | M12345678A |
| South Africa | VAT | 4123456789 |

## Tax ID 2: Additional Identifier

| Country | Label | Format Example |
|---------|-------|----------------|
| USA | State Tax ID | NY-12345678 |
| France | SIRET | 123 456 789 00012 |
| UK | CRN (Company Reg) | 12345678 |
| Spain | CIF | A12345678 |
| Netherlands | KVK | 12345678 |
| Germany | Steuernummer | 123/456/78901 |
| Japan | Corporate Number | 1234567890123 |
| Italy | Partita IVA | IT12345678901 |
| Brazil | CNPJ | 12.345.678/0001-90 |

## How to Set Tax IDs

1. Edit a customer or biller
2. Go to the **Tax IDs** tab
3. Fill in the **label** (e.g., "VAT", "EIN") and the **number**
4. Save

Both fields are free-text so any format works. The label and number are independent: use whatever labelling convention your jurisdiction requires.

## Where Tax IDs Appear

| Location | Whose Tax IDs Are Shown |
|----------|------------------------|
| Invoice PDF header | Biller's tax IDs |
| Invoice PDF detail | Customer's tax IDs |
| Customer portal | Customer's own tax IDs |
| Printed invoices | Both biller and customer tax IDs |
