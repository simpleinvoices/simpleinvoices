# Payment Terms

Payment terms define when an invoice is due. The system calculates the due date automatically based on the term selected when creating an invoice.

## Managing Payment Terms

1. Go to **Settings → Payment Terms**
2. View, add, edit, or delete payment terms
3. Assign a default term for each invoice preference

## Creating a Payment Term

| Field | Description |
|-------|-------------|
| **Code** | Unique uppercase identifier (e.g., `NET_30`, `EOM`) |
| **Label** | Display name shown on invoices (e.g., "Net 30 Days") |
| **Calculation Kind** | How the due date is computed (see below) |
| **Parameter** | Number of days or day-of-month (depends on calculation kind) |
| **Sort Order** | Order in which terms appear in dropdown lists |

## Calculation Kinds

| Kind | Description | Parameter |
|------|-------------|-----------|
| **NET_DAYS** | Invoice date + N days | Number of days (e.g., 30) |
| **EOM** | Last calendar day of the invoice month | Not used |
| **EOM_PLUS_DAYS** | Last day of invoice month + N days | Number of days (e.g., 15) |
| **MFI_DAY** | Nth day of the month *following* the invoice month | Day of month (e.g., 15) |

### Examples

- **NET_DAYS** with parameter 30: Invoice dated March 5 → due April 4
- **EOM**: Invoice dated March 5 → due March 31
- **EOM_PLUS_DAYS** with parameter 15: Invoice dated March 5 → due April 15
- **MFI_DAY** with parameter 15: Invoice dated March 5 → due April 15

## Built-in Payment Terms

| Code | Calculation Kind | Parameter | Description |
|------|-----------------|-----------|-------------|
| **DueOnReceipt** | NET_DAYS | 0 | Payment due immediately |
| **NET_15** | NET_DAYS | 15 | Payment due in 15 days |
| **NET_30** | NET_DAYS | 30 | Payment due in 30 days |
| **EOM** | EOM |: | End of invoice month |
| **EOM_PLUS_15** | EOM_PLUS_DAYS | 15 | 15 days after month end |
| **MFI_DAY_15** | MFI_DAY | 15 | 15th of following month |

## How Payment Terms Affect Invoices

- When a payment term is selected on an invoice, the due date is calculated automatically
- The due date is stored with the invoice and shown in invoice views, PDFs, and the customer portal
- If no payment term is selected, the due date displays as "-"
- The due date field is read-only on the invoice form
