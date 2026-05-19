# Delete Confirmation

The **Delete** setting in System Defaults controls whether a confirmation dialog appears before deleting invoices and other records.

## Settings

Two delete-related options are available under **Settings → System Defaults**:

### Delete (system-wide)

When **Enabled**, a confirmation dialog pops up whenever you click a delete button anywhere in the application. This prevents accidental deletions.

When **Disabled**, delete actions happen immediately without confirmation. Use with caution.

This setting applies to:
- Deleting invoices
- Deleting payments
- Deleting customers, billers, products
- Deleting tax rates, payment types, payment terms
- All other record deletions

### Confirm Delete Line Item

Controls whether a confirmation dialog appears before removing a line item from an invoice form.

When **Enabled**: Clicking the delete icon on an invoice line item row shows a modal asking "Delete this line item?" before removing it. The line item is only visually hidden until you save the invoice.

When **Disabled**: Line items are removed immediately with no confirmation.

## How the Confirmation Dialog Works

The confirmation uses a Bootstrap 5 modal. The modal shows:
- A title describing what's being deleted
- Cancel and Delete buttons
- On Delete, the action proceeds; on Cancel, nothing happens

## Why Use Delete Confirmation

- **Prevent data loss** — especially important for invoices with payment history
- **Accidental clicks** — the extra step saves you from mis-clicks
- **Team safety** — if multiple users access the system, confirmations reduce mistakes

## Where to Configure

1. Go to **Settings** (admin menu) → **System Defaults**
2. Find **Delete** and **Confirm Delete Line Item**
3. Click **Edit** on each to set Enabled or Disabled
4. Click **Save**

Changes take effect immediately — no page reload needed to see the new behavior.
