# Payment Types

Payment types define the methods by which customers can pay invoices.

## Managing Payment Types

1. Go to **Settings → Payment Types**
2. View, add, edit, or delete payment types

## Creating a Payment Type

| Field | Description |
|-------|-------------|
| **Type Name** | Display name (e.g., "Credit Card", "Bank Transfer", "Cash") |
| **Enabled** | Enable or disable this payment type |
| **Default** | Set as the default for new invoices |

## Built-in Payment Types

| Type | Use Case |
|------|----------|
| **Cash** | Physical currency payments |
| **Check** | Paper check deposits |
| **Credit Card** | Card payments processed manually or via gateway |
| **Online Payment** | Payment processed through an integrated gateway |
| **Bank Transfer** | Direct deposit or wire transfer |
| **Money Order** | Postal money orders |
| **Other** | Any custom payment method |

## Online Payment Types

When using online payment gateways (PayPal, Stripe, etc.):

1. Enable the payment type that matches your gateway
2. Configure the gateway under Settings → System Preferences
3. The online payment link will appear on invoices viewed by customers
4. Payments are automatically recorded when processed through the gateway

## Payment Type on Invoices

- The payment type is selected when creating an invoice
- It can be changed at any time before recording payment
- The payment type appears on invoice PDFs and in the customer portal
- Use descriptive payment type names for clarity
