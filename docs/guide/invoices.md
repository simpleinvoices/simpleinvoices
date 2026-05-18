# Managing Invoices

Simple Invoices supports three invoice styles, full line-item management, and PDF/email delivery.

## Invoice Types

There are three types of invoices:

| Type | Description |
|------|-------------|
| **Total Invoice** | Single line item with a total amount — useful for simple billing |
| **Itemised Invoice** | Multiple line items with individual quantities, prices, and taxes |

## Creating an Invoice

1. Go to **Invoices → New Invoice**
2. Select the **Biller** (who is sending the invoice)
3. Select the **Customer**
4. Choose the **Invoice Type**
5. Add **line items** — products, descriptions, quantities, prices
6. Set the **payment type**, **preference**, and any **notes**
7. Click **Save**

## Invoice Statuses

| Status | Description |
|--------|-------------|
| **Draft** | Invoice is being prepared, not yet sent |
| **Sent** | Invoice has been emailed or delivered to the customer |
| **Paid** | Payment has been received in full |
| **Partially Paid** | Partial payment received |
| **Overdue** | Payment not received by the due date |

## Exporting Invoices

Each invoice can be exported in multiple formats:

- **PDF** — Professional invoice document for printing or emailing
- **Spreadsheet** (XLSX) — For accounting or data processing
- **Word Processor** (DOCX) — For editing in Word
- **Print View** — Clean HTML view optimized for browser printing

## Recurring Invoices

Set up invoices that automatically repeat:

1. Go to **Invoices → Recurrence**
2. Create a recurring profile with the invoice details
3. Set the frequency (daily, weekly, monthly, yearly)
4. The system will generate invoices on schedule

## Email Delivery

To email an invoice directly:

1. Open the invoice
2. Use the **Export** button and select the email option
3. The customer's email from their profile will be used automatically
4. Configure SMTP settings under Settings → System Preferences to enable sending
