# Invoice Preferences

Invoice Preferences define everything about how an invoice looks, reads, and behaves: from the wording and currency to the numbering scheme and payment instructions. You can create as many preferences as you need (e.g., one for Invoices, one for Quotes, one for Receipts) and assign them to individual invoices.

## Access

Go to **Settings → Invoice Preferences** to manage all preferences. Each row shows the description and whether the preference is enabled or disabled. Use the action dropdown to **View** or **Edit**.

Click **Add Preference** to create a new one.

---

## Tab 1: Details

### Description

**Required.** The name of this preference set. This is what you see in the dropdown when creating an invoice and in the Manage list. Make it descriptive: e.g., "Standard Invoice", "Quote", "Receipt".

### Default Currency

The currency that this preference uses. When you select this preference on a new invoice, the invoice's currency is auto-set to this value. You can still override it per invoice.

The currency dropdown includes:
- **Preset groups**: Major currencies organised by region (Americas & Pacific, Europe & UK, Asia/Africa/Middle East, Cryptocurrency)
- **Custom currencies**: Any currencies you've added under Settings → Currencies appear in the "Other" group
- **Custom symbol**: At the bottom of the list, you can enter a custom currency symbol or HTML entity code (e.g., `&#8364;` for €). This option reveals text inputs for the symbol and an optional 3-letter currency code.

The currency sign, code, and database ID are all stored with the preference and copied to invoices that use it.

### Default Payment Terms

The payment term auto-assigned to invoices using this preference. When a new invoice is created with this preference, the payment term is pre-selected and the due date is calculated automatically. Set to **"-"** if you don't want a default term.

Payment terms are configured under **Settings → Payment Terms**.

### Status

| Status | Meaning |
|:---|:---|
| **Real** (default) | The invoice is a real, payable document. Included in sales reports. |
| **Draft** | Similar to a quote. Not included in sales reports. Use for estimates, pro-formas, or work-in-progress invoices. |

### Enabled

| Setting | Effect |
|:---|:---|
| **Enabled** (default) | The preference appears in the dropdown when creating invoices. |
| **Disabled** | The preference is hidden from the invoice creation form. Existing invoices using it are unaffected. |

---

## Tab 2: Numbering

Controls how invoices using this preference are numbered. See also the dedicated pages on **[Invoice Numbering Groups](/help/invoice-numbering-groups)** and **[Invoice ID](/help/invoice-id)**.

### Invoice Numbering Group

Determines which auto-incrementing counter this preference shares. Each preference can belong to one group:

- **Create a new group**: Leave this blank when adding a preference. The new preference gets its own independent counter starting at 1.
- **Join an existing group**: Select another preference. Invoices from both preferences will share the same sequential counter (interleaved numbering).
- **Standalone group**: When editing, keep the preference as its own group (it is listed by its own description).

### Next Invoice Number

A live preview of what the next invoice ID number would be for this preference's numbering group. This updates automatically when you change the numbering group.

**Change starting number**: Click to set a custom starting number. The new number must be greater than the highest existing invoice ID in this group. This updates the `si_index` table counter.

### Invoice ID Prefix

An optional string prepended to the numeric part of every invoice ID created with this preference. Combined with the [Biller's invoice prefix](/help/invoice-id) and the formatted number to form the complete ID.

**Example:** Prefix `INV-` with biller prefix `B1-` and format `%06d` → `B1-INV-000042`

### Invoice Number Format

A PHP `sprintf` format string controlling zero-padding of the numeric counter:

| Format | Result (counter = 42) |
|:---|:---|
| (empty) | `42` |
| `%06d` | `000042` |
| `%08d` | `00000042` |
| `%04d` | `0042` |

**Note:** After changing the prefix or format, existing invoices are NOT retroactively updated. Go to **Settings → Invoice List Cache → Rebuild normalised fields** to update them.

---

## Tab 3: Localization

### Language

The UI language that will be used when rendering invoices with this preference. Defaults to **"Use organisation default"** which inherits the system-wide language setting. Available languages are those installed in the `lang/` directory.

**Note:** The language set here applies only to invoices using this preference: it can differ from the organisation's UI language. For example, your team could use English (`en_GB`) for the admin interface while invoices sent to French customers use French (`fr_FR`) by setting the language on the relevant Invoice Preference. This means the invoice labels (Date, Total, Tax, etc.) will appear in French on the PDF and customer-facing invoice views, while your team continues working in English.

### Locale

Controls date and number formatting on invoices (e.g., `$1,234.56` vs `1.234,56 $`). Uses PHP's ICU `NumberFormatter` when the `intl` extension is available. Falls back to basic formatting otherwise.

The locale is stored with each invoice at creation time (`denorm_currency_locale`), so invoices always render with the formatting that was active when they were created: even if the preference's locale is later changed.

---

## Tab 4: Wording

Customises the text that appears on invoices.

### Invoice Heading

Appears at the top of the invoice. Default: "Invoice". Change to whatever suits your business: e.g., "Moes Tavern - Invoice", "Tax Invoice", "Quote".

### Invoice Wording

Replaces the word "Invoice" throughout the entire document. If set to "Quote", the invoice will show "Quote ID", "Quote Date", etc. everywhere.

This affects both the UI and printed/PDF output.

### Invoice Detail Heading

Heading for the footer/details section of the invoice. Typically contains payment instructions or additional notes.

### Invoice Detail Line

Text displayed under the detail heading. Commonly used for payment terms, legal notices, or thank-you messages.

---

## Tab 5: Payment

Configures payment instructions and online payment options displayed on invoices.

### Token System

Payment line values support **tokens** that are replaced with live data when the invoice is rendered:

| Category | Available Tokens |
|:---|:---|
| **Biller bank** | `{biller.bank_name}`, `{biller.bank_account_number}`, `{biller.bank_swift_bic}`, `{biller.bank_routing_sort_code}`, `{biller.bank_account_name}` |
| **Language labels** | `{lang.bank_name}`, `{lang.account_number}`, `{lang.swift_bic}`, `{lang.invoice_reference}`, `{lang.details}`, `{lang.payment_terms}`, `{lang.account_name}`, `{lang.electronic_funds_transfer}` |
| **Other** | `{biller.name}`, `{biller.email}`, `{biller.phone}`, `{customer.name}`, `{invoice.total}`, `{invoice.owing}`, `{invoice.number}` |

Tokens in **name** fields resolve to language-localised labels. Tokens in **value** fields resolve to actual data from the biller, customer, or invoice.

### Include Online Payment

Check which payment gateways to offer on invoices using this preference. Each checked gateway will display its payment button or link on the rendered invoice.

Available gateways:
- Stripe
- PayPal Commerce
- Mollie
- Authorize.net
- eWay Rapid
- Payments Gateway
- Ko-fi
- Coinbase Commerce
- Adyen

You must configure each gateway separately under **Settings** before it can process payments.

### Invoice Payment Method

Free-text field describing how to pay: e.g., "Cheque", "Electronic funds transfer", "Bank transfer". Appears on the invoice.

### Payment Lines 0–5

Six configurable name/value pairs displayed in the payment section of the invoice. Each pair has:

- **Name**: The label (e.g., "Bank name", "Account number"). Supports `{lang.*}` tokens for automatic translation.
- **Value**: The content (e.g., your bank name, account number). Supports `{biller.*}`, `{customer.*}`, and `{invoice.*}` tokens.

Default placeholders are provided for lines 0, 3, 4, and 5 as sensible starting points.

---

## Default Preference

One preference can be set as the system default under **Settings → System Preferences → Default Invoice Preference**. This preference is auto-selected when creating new invoices and is used during first-run setup.

---

## Related Docs

- **[Invoice Numbering Groups](/help/invoice-numbering-groups)**: How groups control shared vs. separate ID counters
- **[Invoice ID](/help/invoice-id)**: How the complete Invoice ID is assembled from prefixes, formats, and counters
- **[Currency Settings](/help/currency-settings)**: Managing currencies, symbols, and locale formatting
- **[Payment Terms](/help/payment-terms)**: Configuring due date calculation rules
- **[Due Date](/help/due-date)**: How due dates are calculated from payment terms
