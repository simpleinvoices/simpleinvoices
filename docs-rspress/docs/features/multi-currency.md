# Multi-Currency

Simple Invoices supports **40+ preset currencies** plus custom currencies: each invoice can use a different currency.

## Preset Currencies

Over 40 currencies are pre-seeded, grouped by region:

| Region | Currencies |
|--------|-----------|
| **Americas & Pacific** | USD ($), CAD (C$), AUD (A$), NZD (NZ$), MXN (MX$), BRL (R$), SGD (S$) |
| **Europe & UK** | EUR (€), GBP (£), CHF, SEK (kr), DKK (kr), NOK (kr), PLN (zł), CZK (Kč), HUF (Ft), RON (lei), TRY (₺) |
| **Asia, Africa & Middle East** | CNY (¥), JPY (¥), KRW (₩), INR (₹), HKD (HK$), ILS (₪), ZAR (R) |
| **Cryptocurrency** | BTC (₿), ETH (Ξ), LTC (Ł), USDT, USDC, DOGE |

## Custom Currencies

Add any currency not in the presets:
1. Go to **Settings → Currencies → Add Currency**
2. Enter the ISO 4217 currency code (e.g., `GHS`)
3. Enter the symbol (e.g., `GH₵`)
4. Set symbol position (left or right)
5. Enable and optionally set as default

## Per-Invoice Currency

- Each invoice uses one currency, set when creating the invoice
- The currency is auto-selected from the invoice preference
- You can override it per invoice from the currency dropdown
- Tax rates work independently of currency

## How Locale Affects Display

| Locale | USD $1,234.56 | EUR €1.234,56 |
|--------|---------------|----------------|
| `en_US` | $1,234.56 | €1,234.56 |
| `en_GB` | US$1,234.56 | €1,234.56 |
| `de_DE` | 1.234,56 $ | 1.234,56 € |
| `fr_FR` | 1 234,56 $US | 1 234,56 € |

Configure locale per preference under **Settings → Invoice Preferences → Localization**.
