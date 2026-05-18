# Recurring Invoices

Set up invoices that automatically repeat on a schedule.

## Overview

Recurring invoices are useful for:

- **Subscription billing** — Monthly or annual service fees
- **Retainer agreements** — Regular service charges
- **Rental/lease payments** — Fixed recurring amounts
- **Maintenance contracts** — Ongoing support fees

## Creating a Recurring Invoice

1. Go to **Invoices → Recurrence**
2. Click **New Recurring Invoice**
3. Fill in the invoice details:
   - **Biller** and **Customer**
   - **Invoice type** (Total, Itemised)
   - **Line items** with products, quantities, and prices
4. Set the **Frequency**:
   - Daily
   - Weekly
   - Monthly (e.g., every 1st of the month)
   - Yearly
5. Set the **Start Date** — when the first invoice should be generated
6. Optionally set an **End Date** or leave open-ended
7. Click **Save**

## How It Works

1. The system periodically checks for due recurring invoices
2. A cron job or manual trigger generates the next invoice
3. The generated invoice appears in the regular invoice list
4. Each generated invoice uses the next sequential invoice number

## Managing Recurring Invoices

- **View** — See all recurring profiles and their status
- **Edit** — Modify the schedule, amounts, or details
- **Pause** — Temporarily stop generation without deleting
- **Delete** — Remove the recurring profile (existing invoices remain)

## Frequency Options

| Frequency | Example | Generated |
|-----------|---------|-----------|
| **Daily** | Every day | One invoice per day |
| **Weekly** | Every Monday | One invoice per week |
| **Monthly** | 1st of each month | One invoice per month |
| **Yearly** | January 1st | One invoice per year |

## Best Practices

- Set a clear end date for fixed-term contracts
- Review recurring amounts periodically (especially for variable-rate services)
- Monitor generated invoices to catch any errors
- Use descriptive invoice notes to indicate the billing period
- Consider separate recurring profiles for different services with different frequencies
