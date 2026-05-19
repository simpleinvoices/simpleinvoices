# Tax Rates

Tax rates define the taxes that can be applied to invoice line items.

## Managing Tax Rates

1. Go to **Settings → Tax Rates**
2. View existing tax rates or add new ones

## Creating a Tax Rate

| Field | Description |
|-------|-------------|
| **Tax Description** | Display name (e.g., "VAT", "GST", "Sales Tax") |
| **Tax Percentage** | The tax rate as a percentage (e.g., 20 for 20%) |
| **Default** | Set as the default tax rate for new invoices |
| **Enabled** | Enable or disable this tax rate |

## How Taxes Work

- **Per Line Item**: Each line item on an invoice can have its own tax rate
- **Tax Calculation**: Tax is calculated as: `(quantity × unit price) × tax rate`
- **Tax Display**: Tax amounts appear in the invoice totals section
- **Multiple Tax Rates**: Configure as many rates as needed for your jurisdiction

## Tax Rate Sign

The tax rate sign determines how the tax amount is displayed:

- **Positive**: Tax is added to the subtotal (standard for most taxes)
- **Negative**: Tax is subtracted (rare, used for credits or reverse-charge scenarios)

## Common Tax Configurations

| Jurisdiction | Tax Name | Typical Rate |
|-------------|----------|--------------|
| European Union | VAT | 17-27% |
| United Kingdom | VAT | 20% |
| Australia | GST | 10% |
| New Zealand | GST | 15% |
| Canada | GST/HST | 5-15% |
| United States | Sales Tax | Varies by state (0-10%) |
| Singapore | GST | 9% |
| India | GST | 5-28% |

## Best Practices

- Keep tax rates up to date with current legislation
- Use clear, recognizable names for each tax
- Set the most common rate as default
- Review tax calculations on invoices before sending to customers
- Consult your accountant for proper tax setup
