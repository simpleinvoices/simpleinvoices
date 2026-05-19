# Due Date

The due date is calculated automatically from the invoice date and the selected payment term. The calculation occurs client-side whenever the invoice date or payment term changes.

## How the Due Date Is Calculated

| Payment Term Kind | Calculation Rule | Example (Invoice: March 5) |
|-------------------|-----------------|-----------------------------|
| **NET_DAYS** | Invoice date + parameter days | Parameter=30 → April 4 |
| **EOM** | Last calendar day of the invoice month | March 31 |
| **EOM_PLUS_DAYS** | Last day of invoice month + parameter days | Parameter=15 → April 15 |
| **MFI_DAY** | Parameter-th day of the following month | Parameter=15 → April 15 |

### MFI_DAY Clamping

If the parameter exceeds the number of days in the target month, the day is clamped to 28.

Example: Parameter=31 on an invoice dated January 15 → February has 28 days → due February 28.

## Display Rules

| Scenario | Due Date Display |
|----------|-----------------|
| Payment term selected | Calculated due date (e.g., "2025-04-04") |
| No payment term selected | "-" |

## Where the Due Date Appears

- Invoice form (read-only calculated field)
- Invoice list view
- Invoice detail view
- Invoice PDF
- Customer portal

## Notes

- The due date field on the invoice form is **read-only** — it is always calculated, never manually entered
- To change a due date, change either the invoice date or the payment term
- Payment terms are configured under **Settings → Payment Terms**
