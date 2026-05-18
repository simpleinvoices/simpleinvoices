# Invoice List Cache

The Invoice List Cache (denormalised data) page lets you manage cached totals on invoices for fast list loading.

## What It Does

To make the invoice list load quickly, computed totals and labels are copied onto each invoice row:

| Cached Field | Source |
|-------------|--------|
| Invoice total | Sum of line items × quantities + taxes |
| Amount paid | Sum of all payments applied to the invoice |
| Amount owing | Invoice total - amount paid |
| Biller name | Billers table |
| Customer name | Customers table |
| Currency code | Currencies table |

This avoids recalculating from line items and payments every time the invoice list loads.

## Verify

Compares the cached (denormalised) values against live calculations from line items and payments. Reports:

- Number of invoices where cached values **match** calculated values
- Number of invoices where cached values **do not match**

Use Verify periodically to detect data drift.

## Rebuild

Recalculates all denormalised fields for every invoice in the domain.

**Run rebuild after:**

- Data imports or manual database edits
- SQL patch upgrades (especially patches 338-340)
- When Verify reports mismatches
- After restoring from a backup

## Access

1. Go to **Settings → Options**
2. Click the **Invoice List Cache** tab

## Common Issues

| Issue | Solution |
|-------|----------|
| Mismatched totals after bulk data changes | Run Rebuild |
| Mismatched totals after SQL patches | Run Rebuild (especially after patches 338-340) |
| Slow rebuild on very large databases | Rebuild processes all invoices in one pass; allow time for large datasets |
| Unsure if data is correct | Run Verify first, then Rebuild if mismatches are found |
