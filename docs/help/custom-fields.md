# Custom Fields

Custom fields allow you to add your own data fields to invoices and other entities beyond the standard fields provided by Simple Invoices.

## Overview

Custom fields can be added to:

- **Invoices** — Extra fields on your invoice forms (e.g., Purchase Order number, Project code)
- **Customers** — Additional client information (e.g., VAT number, Account manager)
- **Products** — Extra product metadata (e.g., SKU, Supplier code)
- **Billers** — Additional company details

## Creating Custom Fields

1. Go to **Settings → Custom Fields**
2. Select which entity the field applies to
3. Enter the **Field Label** (displayed on forms)
4. Choose the **Field Type**:
   - Text input
   - Text area
   - Dropdown/select
   - Date picker
5. Set any validation or default values
6. Click **Save**

## Using Custom Fields

Once created, custom fields appear automatically:

- **On forms** — When creating or editing the entity
- **In templates** — When viewing or printing
- **In exports** — Included in PDF, spreadsheet, and document exports

## Managing Custom Fields

- **Enable/Disable** — Toggle fields on or off without deleting them
- **Reorder** — Drag to rearrange the display order on forms
- **Delete** — Remove fields (data remains in database records)

## Best Practices

- Use clear, descriptive labels that your team and clients will understand
- Don't create too many fields — keep forms manageable
- Group related fields logically
- Test that fields appear correctly on PDF exports before sending to clients
- Custom field data is searchable in the database but not from the UI search
