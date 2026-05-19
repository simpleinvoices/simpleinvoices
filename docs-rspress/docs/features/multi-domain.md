# Multi-Domain (SaaS)

Simple Invoices is built with a **multi-domain architecture** from the ground up. Run a full invoice SaaS with as many distinct domains, clients, or tenants as you want: all from a single installation.

## What is Multi-Domain?

Each **domain** in Simple Invoices is a completely isolated workspace with its own:

| Resource | Per-Domain |
|----------|-----------|
| **Database records** | All data (invoices, customers, billers, products, payments) is scoped by `domain_id` |
| **Settings** | System preferences, invoice preferences, tax rates, payment types |
| **Currencies** | Each domain has its own currency table with custom currencies |
| **Users** | Separate user accounts per domain |
| **Themes** | Custom branding per domain |
| **Email config** | SMTP settings per domain |

## SaaS Use Cases

### Use Case 1: Multi-Tenant Invoicing Platform

Run an invoicing platform where each client gets their own isolated workspace:

```
yoursaas.com → Client A's workspace (domain_id=1)
             → Client B's workspace (domain_id=2)
             → Client C's workspace (domain_id=3)
```

Each client sees only their own data. They can configure their own preferences, currencies, and users.

### Use Case 2: Multi-Brand Organisation

A company with multiple brands can run separate domains:

```
invoices.mycompany.com → Brand A (domain_id=1)
                      → Brand B (domain_id=2)
                      → Brand C (domain_id=3)
```

Each brand has its own invoice templates, biller details, and logo.

### Use Case 3: Regional Offices

International organisations can have per-country domains:

```
invoices.company.com/eu → European office, EUR, European tax rates
invoices.company.com/us → US office, USD, US tax rates
invoices.company.com/au → Australian office, AUD, GST
```

## Domain Isolation

Data isolation is enforced at the database level:

- Every table includes a `domain_id` column
- All queries automatically scope to the current domain
- Users cannot see or access data from other domains
- Each domain maintains its own independent invoice numbering

The `auth_session->domain_id` determines which domain's data is loaded.

## Setting Up a New Domain

1. Run the database setup for the new domain
2. Configure the domain's system preferences
3. Add users for the domain
4. Each domain builds from the same core tables, keeping maintenance simple

This architecture has been powering Simple Invoices installations since 2005.
