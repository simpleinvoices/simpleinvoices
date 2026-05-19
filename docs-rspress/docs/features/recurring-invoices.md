# Recurring Invoices

Automate repeat billing with **recurring invoices**. Set up an invoice once and have it generated and emailed automatically on schedule.

## How It Works

1. Create an invoice as usual
2. Go to **Invoices → Recurring** and click **Add**
3. Select the invoice to recur
4. Set the schedule (see below)
5. Enable email delivery to customer and/or biller

The cron system checks daily for invoices due to recur. When a match is found, it duplicates the invoice with a new date and invoice ID, then optionally emails it.

## Recurrence Types

| Type | Description | Example |
|------|-------------|---------|
| **Day** | Every N days | Every 7 days = weekly |
| **Week** | Every N weeks | Every 2 weeks = fortnightly |
| **Month** | Every N months | Every 1 month = monthly |
| **Year** | Every N years | Every 1 year = annual |

## Schedule Example

| Scenario | Recurrence | Type | Start Date | Behaviour |
|----------|-----------|------|------------|-----------|
| Monthly rent | 1 | month | 2025-01-01 | New invoice on 1st of each month |
| Weekly timesheet | 1 | week | 2025-03-03 | New invoice every Monday |
| Quarterly SaaS | 3 | month | 2025-01-15 | New invoice every 3 months |
| Annual membership | 1 | year | 2025-06-01 | New invoice each year |

## Email Automation

When a recurring invoice is generated, it can automatically:

- **Email the customer** with the new invoice PDF attached
- **Email the biller** as a notification/confirmation

Configure this per recurring entry.

## Setting Up the Cron Job

The recurring invoice engine runs via a system cron job that calls the API endpoint:

```bash
# Run every day at 1 AM
0 1 * * * /usr/bin/wget -q -O - http://your-server/api-cron >/dev/null 2>&1
```

Or via the web API directly:
```
GET /index.php?module=api&view=cron
```

## Cron Log

All recurring invoice activity is logged. View the log under **Settings → Cron Log** to see:

- Which invoices were generated
- Which were skipped (and why)
- Start date, end date, recurrence pattern for each entry
- Email delivery status

## Managing Recurring Invoices

- **View** all recurring entries under **Invoices → Recurring**
- **Edit** the schedule, recurrence type, or email settings
- **Delete** entries to stop further recurrences
- Each entry has a start date and optional end date

The cron system only processes entries where today's date falls between the start and end dates (if end date is set).
