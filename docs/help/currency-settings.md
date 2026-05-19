# Currency Settings

Currency support is fully database-driven. Each domain has an independent currency table, preset currencies are seeded automatically, and locale-aware formatting is handled by PHP's ICU `NumberFormatter`.

## The Currencies Table

Go to **Settings → Currencies** to manage all currencies for your domain.

### Preset Currencies

Over 40 currencies are pre-seeded into every new domain. They're grouped into four categories:

**Americas & Pacific dollars** — USD ($), CAD (C$), AUD (A$), NZD (NZ$), MXN (MX$), BRL (R$), SGD (S$)

**Europe & UK** — EUR (€), GBP (£), CHF, SEK (kr), DKK (kr), NOK (kr), PLN (zł), CZK (Kč), HUF (Ft), RON (lei), BGN (лв), TRY (₺), RSD (дин.), RUB (₽)

**Asia, Africa & Middle East** — CNY (¥), KRW (₩), JPY (¥), TWD (NT$), HKD (HK$), INR (₹), IDR (Rp), VND (₫), ILS (₪), SAR (﷼), ZAR (R)

**Cryptocurrency** — BTC (₿), ETH (Ξ), LTC (Ł), ADA (₳), XRP, SOL, BNB, USDT, USDC, DOGE

Every preset includes the ISO 4217 currency code, the display symbol, and a smart default for symbol position (left or right). Currencies are seeded once per domain — they won't duplicate on subsequent page loads.

### Adding a Custom Currency

1. Go to **Settings → Currencies**
2. Click **Add Currency**
3. Enter the **currency code** (ISO 4217, e.g. `GHS` for Ghana Cedi)
4. Enter the **currency sign/symbol** (e.g. `GH₵`)
5. Set the **symbol position** — `left` (symbol before amount) or `right` (symbol after)
6. Enable or disable the currency
7. Set as default if desired

Custom currencies appear in the "Other" group in the currency dropdown on invoice forms.

### Default Currency

One currency is marked as the default for your domain. It's used as the default for:
- New invoice preferences
- The wizard during first-run setup
- Fallback when no currency is explicitly selected

Set a default by editing any currency and checking "Default". The previous default is automatically cleared.

## How Locale Affects Currency Display

Simple Invoices uses PHP's `intl` extension (`NumberFormatter`) when both a locale and a currency code are available. This produces locale-appropriate formatting for the amounts shown on printed invoices and PDFs.

### Same Currency, Different Locales

Example: an invoice for `$1,234.56` with currency code `USD`:

| Locale | Rendered On Invoice |
|--------|---------------------|
| `en_US` (English, US) | **$1,234.56** |
| `en_GB` (English, UK) | **US$1,234.56** |
| `de_DE` (German, Germany) | **1.234,56 $** |
| `fr_FR` (French, France) | **1 234,56 $US** |
| `ja_JP` (Japanese, Japan) | **$1,235** (yen rounds) |

Example: €1.234,56 with currency code `EUR`, symbol position `right`:

| Locale | Rendered On Invoice |
|--------|---------------------|
| `en_IE` (English, Ireland) | **€1,234.56** |
| `de_DE` (German, Germany) | **1.234,56 €** |
| `nl_NL` (Dutch, Netherlands) | **€ 1.234,56** |
| `fr_FR` (French, France) | **1 234,56 €** |
| `es_ES` (Spanish, Spain) | **1.234,56 €** |

### When Locale Is Not Set

If no locale is available (or the `intl` extension is missing), formatting falls back to a simple implementation:
- Number formatting from the system's language pack (`siLocal::number`)
- Symbol placed before or after based on the currency's position setting
- No locale-specific grouping, decimal separators, or spacing

### How Locale Flows Through the System

| Source | Path |
|--------|------|
| Preference | Each invoice preference has a `locale` field (e.g. `en_GB`). The locale is copied into `si_invoices.denorm_currency_locale` when the invoice is created. |
| Invoice | The stored `denorm_currency_locale` value is used when rendering the invoice. |

This ensures invoices always render with the locale that was active when they were created, even if the preference changes later.

## Symbol Position

Each currency has a symbol position — `left` (symbol before amount, e.g. `$100.00`) or `right` (symbol after amount, e.g. `100.00 €`).

### Smart Defaults

When you create a currency, Simple Invoices assigns a sensible default position based on the region:

| Position | Typical Currencies |
|----------|--------------------|
| **Left** | Dollar-family ($, C$, A$, NZ$, MX$, R$, S$, NT$, HK$), Pound (£), Yen (¥), Rupee (₹), Shekel (₪), Riyal (﷼), Lira (₺), all crypto |
| **Right** | Euro (€), Swiss Franc (CHF), Nordic krone (SEK/DKK/NOK), Złoty (zł), Koruna (Kč), Forint (Ft), Leu (lei), Lev (лв), Dinar (дин.), Ruble (₽), Rand (R), Dong (₫), Rupiah (Rp) |

You can override the position for any currency manually.

## HTML Entities for PDF Compatibility

Some currency symbols use HTML entities (e.g. `&#8364;` for €, `&#163;` for £) for compatibility with legacy PDF rendering libraries. The system normalises these automatically:

- **Storage** — Symbols may be stored as entities or literal Unicode
- **Display** — `CurrencySignHelper::forDisplay()` decodes entities to Unicode for UI and HTML output
- **PDF** — The PDF renderer receives the symbol as stored; entities work reliably across PDF engines

When adding a custom currency, you can use literal symbols (€, £, ¥) or HTML entities (`&#8364;`, `&#163;`, `&#165;`). Both work correctly.

## Currency on Invoices

### Creating an Invoice

When creating a new invoice, the currency is set in the inline currency dropdown on the invoice form (grouped under "Americas & Pacific dollars", "Europe & UK", etc.):

1. Select an **invoice preference** — the currency from that preference is auto-selected
2. Override the currency if needed by picking from the dropdown
3. The selected currency's sign and code are stored with the invoice

### Invoice Preferences

Each invoice preference references a currency. When a preference is selected on a new invoice:
- The preference's currency is pre-selected
- The preference's locale is copied to the invoice for locale-aware formatting
- The payment term from the preference is also pre-selected (if set)

### Multi-Currency Invoicing

- Each invoice uses exactly one currency
- You can switch currencies per invoice — no domain-wide lock
- Tax rates and amounts are independent of currency
- Reports group and sum by currency code

### Currency Code Display

By default, currency codes are shown in the currency dropdown (e.g. "USD - $"). On invoices, you control whether the code appears alongside the amount via the invoice preference settings.
