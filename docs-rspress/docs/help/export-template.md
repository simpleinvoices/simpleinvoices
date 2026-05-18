# Default Export Template

The **Default Export Template** determines which invoice template is used when exporting invoices to office file formats (XLSX, DOCX, ODS, ODT).

## What It Controls

When you export an invoice as a spreadsheet (XLSX/ODS) or word processor document (DOCX/ODT), the system uses the export template: **not** the print template. This keeps your styled, print-ready PDF template separate from the data-focused export template.

| Export Action | Template Used |
|---------------|--------------|
| PDF export | Print template (set via **Default Invoice Template**) |
| Spreadsheet (XLSX/ODS) | **Default Export Template** |
| Word Processor (DOCX/ODT) | **Default Export Template** |
| Print View (HTML) | Print template |

## Default Value: `export`

The default export template is `export`, which provides a clean, unstyled layout optimised for office applications:

- **No custom styling**: spreadsheet and word processor apps apply their own formatting
- **Full data**: includes all invoice fields, line items, totals, biller and customer details
- **Editable**: recipients can modify the exported document in their office suite

The `export` template lives at `templates/invoices/export/template.blade.php`.

## Changing the Export Template

Go to **Settings → System Defaults → Default Export Template** and click **Edit**. The dropdown lists all available template folders under `templates/invoices/`.

### Available Templates

Template folders are scanned from the `templates/invoices/` directory. Any folder containing a valid `template.blade.php` can be selected:

| Template | Purpose |
|----------|---------|
| `default` | Print template: styled for PDF and HTML |
| `export` | Office export: clean, data-focused layout |
| Custom folder | Any custom template you add to `templates/invoices/` |

### When to Use a Custom Export Template

- **Branded exports**: apply your own styling for clients who open the file in Excel/Word
- **Minimal exports**: strip out decorative elements for cleaner spreadsheet data
- **Compliance**: match a specific format required by your accounting system

### Template Not Found Fallback

If the configured template folder doesn't exist (e.g., after deleting a custom template), the system falls back to `export` automatically. No error is shown.

## How Templates Work

Templates are Blade files at `templates/invoices/<template_name>/template.blade.php`. They have access to all invoice data including:

- Invoice (`$invoice`): dates, totals, status
- Biller (`$biller`): company name, address, tax IDs, bank details
- Customer (`$customer`): name, address, contact info
- Line items (`$invoiceItems`): products, quantities, prices, taxes
- Preferences (`$preference`): invoice preference settings
- Custom fields (`$customFieldLabels`)

## Where to Configure

1. Go to **Settings** (admin menu) → **System Defaults**
2. Find **Default Export Template**
3. Click **Edit** and choose from the dropdown
4. Click **Save**
