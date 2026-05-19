# Managing Payments

Track payments received against invoices and process online payments.

## Recording a Payment

1. Go to **Payments** in the navigation
2. Click **New Payment** (or use the button on an invoice page)
3. Select the **Customer** and the **Invoice**
4. Enter the **Amount** received
5. Choose the **Payment Type** (cash, check, credit card, online gateway, etc.)
6. Add any **Notes** or reference numbers
7. Click **Save**

## Payment Types

Configure payment types under **Settings → Payment Types**:

| Type | Use Case |
|------|----------|
| **Cash** | Manual cash payments |
| **Check** | Physical check deposits |
| **Credit Card** | Card payments processed externally |
| **Online Payment** | Gateway-processed payments (PayPal, Stripe, etc.) |

## Online Payment Gateways

Simple Invoices integrates with several payment gateways:

- **PayPal** — Standard PayPal checkout
- **Stripe** — Credit/debit card processing
- **Authorize.net** — US-based payment gateway
- **Adyen** — International payment platform
- **Coinbase Commerce** — Cryptocurrency payments
- **eWay** — Australian/NZ payment gateway
- **Ko-fi** — Simple donation/payment platform
- **PaymentsGateway** — Generic gateway connector

### Setting Up a Gateway

1. Go to **Settings → System Preferences**
2. Scroll to the Payment Gateway section
3. Enable your chosen gateway
4. Enter the API credentials from your gateway provider
5. Save changes

> **Note:** Payment gateway secrets are encrypted using the `SI_GATEWAY_SECRETS_KEY` configuration value.

## Payment Reports

View payment history under **Reports**:
- Total payments by period
- Payments by customer
- Outstanding (unpaid) invoices
- Debtor aging analysis

## Refunds & Credits

To issue a credit or refund:

1. Open the payment record
2. Use the **Refund** option if available
3. Record the refund as a negative payment against the same invoice
