# Managing Customers

Maintain a database of your customers (clients) with contact details and custom fields.

## Adding a Customer

1. Go to **People → Customers**
2. Click **New Customer**
3. Fill in the required fields:
   - **Name** — Customer or company name
   - **Email** — For sending invoices
   - **Address** — Street, city, state, ZIP, country
   - **Phone**, **Mobile**, **Fax** — Contact numbers
4. Optionally add custom fields (see [Custom Fields](help/custom-fields.md))
5. Click **Save**

## Customer Types

Customers can be categorized:

- **Regular** — Standard clients
- **Credit** — Customers with credit terms
- Use the **Notes** field for internal memos about the customer

## Customer Portal

Customers can access a self-service portal to:

- View their invoices
- Download invoice PDFs
- Make online payments
- View payment history

Enable the customer portal by providing the customer with their login credentials (if authentication is enabled).

## Importing Customers

Customer data can be imported from:

- **JSON** — Structured data files
- Use the import tool under **Settings → Options**

## Managing Billers

Billers are the entities that appear as the "From" on invoices:

1. Go to **People → Billers**
2. Add one or more billers with their company details
3. Optionally upload a logo for each biller
4. Select the biller when creating invoices

> **S3 Storage:** Biller logos can be stored on S3-compatible object storage (AWS S3, MinIO, Garage). Configure under Settings → System Preferences.
