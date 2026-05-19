# Invoice ID

The Invoice ID is **automatically generated** by Simple Invoices when you save an invoice. It is made up of three parts combined together:

```
[Biller Prefix] + [Preference Prefix] + [Auto-incrementing Number]
```

## How It Works

### 1. Biller Invoice Prefix
Set on each Biller under **Settings → Billers** → edit → **Invoice Prefix**.

This is an optional prefix that identifies which office, branch, or entity issued the invoice.

**Examples:** `B1-`, `NY-`, `CA-`, or leave blank for no prefix.

### 2. Invoice Preference Prefix
Set on each Invoice Preference under **Settings → Invoice Preferences** → edit → **Invoice ID Prefix**.

This prefix typically indicates the document type or category.

**Examples:** `INV-` for invoices, `QTE-` for quotes, `REC-` for receipts.

### 3. Auto-incrementing Number
A sequential counter managed in the `si_index` table. Each time an invoice is saved, the counter for that invoice's **numbering group** is incremented.

The number can be formatted using PHP `sprintf` format codes:
- `%06d` → 6-digit zero-padded (000042)
- `%08d` → 8-digit zero-padded (00000042)
- `%04d` → 4-digit zero-padded (0042)
- Leave empty → no padding (42)

## Full ID Examples

| Biller Prefix | Preference Prefix | Format | Counter | Result |
|:---|:---|:---|:---|:---|
| `B1-` | `INV-` | `%06d` | 42 | **B1-INV-000042** |
| `NY-` | `` | `%04d` | 105 | **NY-0105** |
| (none) | `QTE-` | `%06d` | 7 | **QTE-000007** |
| (none) | (none) | `%06d` | 123 | **000123** |

## Numbering Groups

See **[Invoice Numbering Groups](/help/invoice-numbering-groups)** for a detailed explanation of how preferences can share or separate their auto-increment counters.

---

## Where to Configure

| Setting | Location |
|:---|:---|
| **Biller Prefix** | Settings → Billers → Edit Biller → Invoice Prefix |
| **Preference Prefix** | Settings → Invoice Preferences → Edit Preference → Invoice ID Prefix |
| **Number Format** | Settings → Invoice Preferences → Edit Preference → Invoice Number Format |
| **Numbering Group** | Settings → Invoice Preferences → Edit Preference → Numbering Group (tab) |

---

## Important Notes

- The Invoice ID shown on the **new invoice form** is a **preview** only. The actual number is committed when you click **Save**.
- If multiple users create invoices simultaneously, the final ID may differ from the preview.
- After changing prefixes or formats on an existing preference, existing invoices are NOT retroactively updated. Use **Settings → Invoice List Cache → Rebuild normalised fields** to update them.
- The Invoice ID is separate from the database `id` column — it is the `index_id` field and is what appears on printed/emailed invoices.
