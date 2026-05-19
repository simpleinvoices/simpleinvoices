# Template Tokens

Template tokens let you embed live values from invoices, billers, customers, and preferences into text fields. They use a single-brace syntax like `{biller.name}` and are replaced with the actual value when the invoice is rendered.

## How They Work

Tokens are stored verbatim in the database and expanded only at render time — the stored value is never overwritten. This means you can update a biller's bank details and all invoices will automatically pick up the new values.

Tokens can be used in:
- **Invoice Preferences** — detail headings, payment method text, payment line names/values
- **Biller Footer** — displayed at the bottom of invoices and PDF exports

## Invoice Tokens

| Token | Description | Example |
|-------|-------------|---------|
| `{invoice.number}` | Invoice number | `INV-00123` |
| `{invoice.date}` | Invoice date (localised) | `15 Mar 2026` |
| `{invoice.due_date}` | Calculated due date | `15 Apr 2026` |
| `{invoice.total}` | Invoice total amount | `$1,250.00` |
| `{invoice.subtotal}` | Subtotal before tax | `$1,100.00` |
| `{invoice.tax}` | Total tax amount | `$150.00` |
| `{invoice.paid}` | Amount paid so far | `$500.00` |
| `{invoice.owing}` | Outstanding balance | `$750.00` |
| `{invoice.currency}` | Currency symbol | `$` |
| `{invoice.currency_code}` | ISO currency code | `USD` |
| `{invoice.note}` | Invoice notes | `Thank you for your business` |
| `{invoice.payment_term}` | Selected payment term label | `Net 30 Days` |
| `{invoice.payment_term_label}` | Same as above | `Net 30 Days` |

## Biller Tokens

| Token | Description | Example |
|-------|-------------|---------|
| `{biller.name}` | Biller company name | `Acme Corp` |
| `{biller.email}` | Biller email address | `accounts@acme.com` |
| `{biller.phone}` | Biller phone number | `+1 555-0123` |
| `{biller.address}` | Biller street address | `123 Main St` |
| `{biller.city}` | Biller city | `New York` |
| `{biller.state}` | Biller state/province | `NY` |
| `{biller.zip}` | Biller ZIP/postal code | `10001` |
| `{biller.country}` | Biller country | `United States` |

### Bank Detail Tokens

| Token | Description | Example |
|-------|-------------|---------|
| `{biller.bank_account_name}` | Legal name on the bank account | `Acme Corp Pty Ltd` |
| `{biller.bank_name}` | Bank or financial institution name | `National Australia Bank` |
| `{biller.bank_account_number}` | Account number or IBAN | `GB29NWBK60161331926819` |
| `{biller.bank_swift_bic}` | SWIFT/BIC code | `NATAAU3303M` |
| `{biller.bank_routing_sort_code}` | Routing number (US), Sort Code (UK), BSB (AU) | `082-902` |

## Customer Tokens

| Token | Description | Example |
|-------|-------------|---------|
| `{customer.name}` | Customer/company name | `Widgets Inc` |
| `{customer.email}` | Customer email address | `billing@widgets.com` |
| `{customer.phone}` | Customer phone number | `+1 555-9876` |

## Preference Tokens

References to individual invoice preference fields — useful for exposing payment line data in the detail section.

| Token | Description |
|-------|-------------|
| `{preference.pref_inv_payment_line0_name}` | Payment line 1 name |
| `{preference.pref_inv_payment_line0_value}` | Payment line 1 value |
| `{preference.pref_inv_payment_line3_name}` | Payment line 4 name |
| `{preference.pref_inv_payment_line3_value}` | Payment line 4 value |
| `{preference.pref_inv_payment_line4_name}` | Payment line 5 name |
| `{preference.pref_inv_payment_line4_value}` | Payment line 5 value |
| `{preference.pref_inv_payment_line5_name}` | Payment line 6 name |
| `{preference.pref_inv_payment_line5_value}` | Payment line 6 value |

## Language Tokens

Localised labels that adapt to the user's selected language.

| Token | Description |
|-------|-------------|
| `{lang.account_name}` | "Account Name" label |
| `{lang.account_number}` | "Account Number" label |
| `{lang.payment_terms}` | "Payment terms" label |
| `{lang.details}` | "Details" label |
| `{lang.electronic_funds_transfer}` | "Electronic Funds Transfer" label |
| `{lang.bank_name}` | "Bank Name" label |
| `{lang.bank_account_name}` | "Bank Account Name" label |
| `{lang.bank_account_number}` | "Bank Account Number" label |
| `{lang.bank_swift_bic}` | "SWIFT / BIC" label |
| `{lang.bank_routing_sort_code}` | "Routing / Sort Code" label |
| `{lang.swift_bic}` | "SWIFT/BIC" (short) label |
| `{lang.invoice_reference}` | "Invoice Reference" label |

## Where Tokens Are Expanded

Tokens are expanded in these Invoice Preference fields:

- **pref_inv_detail_heading** — Heading above the invoice detail section
- **pref_inv_detail_line** — Single line format in the detail section
- **pref_inv_payment_method** — Payment method description
- **pref_inv_payment_line0_name** through **pref_inv_payment_line5_name** — Payment detail labels
- **pref_inv_payment_line0_value** through **pref_inv_payment_line5_value** — Payment detail values

Tokens are also expanded in the **Biller Footer** field (`si_biller.footer`), which appears at the bottom of invoices.

## Examples

### Bank Transfer Details on Invoices

Configure invoice preferences to show bank transfer details using tokens. Example values for payment lines:

| Field | Value |
|-------|-------|
| **Payment Line 1 Name** | `{lang.account_name}` |
| **Payment Line 1 Value** | `{biller.bank_account_name}` |
| **Payment Line 2 Name** | `{lang.bank_name}` |
| **Payment Line 2 Value** | `{biller.bank_name}` |
| **Payment Line 3 Name** | `{lang.account_number}` |
| **Payment Line 3 Value** | `{biller.bank_account_number}` |
| **Payment Line 4 Name** | `{lang.bank_routing_sort_code}` |
| **Payment Line 4 Value** | `{biller.bank_routing_sort_code}` |
| **Payment Line 5 Name** | `{lang.swift_bic}` |
| **Payment Line 5 Value** | `{biller.bank_swift_bic}` |

This automatically renders the current biller's bank details on every invoice using that preference. If you change billers, the details update automatically.

### Custom Invoice Footer

In the biller footer text (paid invoices appear to show the same payment lines):

```
Thank you for your business, {customer.name}.
Payment reference: {invoice.number}
Please remit payment by {invoice.due_date} to:

{lang.bank_name}: {biller.bank_name}
{lang.account_name}: {biller.bank_account_name}
{lang.account_number}: {biller.bank_account_number}
```

### Invoice Detail Heading

```
Invoice {invoice.number} — {invoice.date}
Customer: {customer.name}
Due: {invoice.due_date} | Total: {invoice.total}
```

## Notes

- Tokens only work in the specific fields listed above. They are not expanded in product descriptions, customer notes, or other free-text fields.
- Unmatched tokens are left as-is in the output — there is no error or fallback.
- Token values are not HTML-escaped. If a biller name contains special characters, they will appear literally.
