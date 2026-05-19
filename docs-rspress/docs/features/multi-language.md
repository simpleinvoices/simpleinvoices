# Multi-Language Support

Simple Invoices is available in **41+ languages**, making it truly international. Every label, button, and message can appear in your customer's native language.

## How It Works

Languages are stored in the `lang/` directory as PHP translation files. Each language provides translations for every text string in the application.

## Supported Languages

| Language | Code |
|----------|------|
| English (UK) | `en_GB` |
| English (US) | `en_US` |
| French | `fr_FR` |
| German | `de_DE` |
| Spanish | `es_ES` |
| Italian | `it_IT` |
| Portuguese (Brazil) | `pt_BR` |
| Dutch | `nl_NL` |
| Polish | `pl_PL` |
| Russian | `ru_RU` |
| Turkish | `tr_TR` |
| Chinese (Simplified) | `zh_CN` |
| Chinese (Traditional) | `zh_TW` |
| Japanese | `ja_JP` |
| Korean | `ko_KR` |
| Arabic | `ar_SA` |
| Hindi | `hi_IN` |
| Tamil | `ta_IN` |
| ...and 23 more | |

## Per-Invoice Language

You can set a different language for each **Invoice Preference**. This means:

- Your admin team can work in English
- Invoices sent to French customers appear in French
- Invoice labels, dates, and currency are localised automatically

Configure this under **Settings → Invoice Preferences → Localization tab**.

## Locale-Aware Formatting

Invoice language is paired with a **locale** setting that controls:

- Number formatting (e.g., `1,234.56` vs `1.234,56`)
- Date formatting (e.g., `DD/MM/YYYY` vs `MM/DD/YYYY`)
- Currency position (e.g., `$100` vs `100 €`)

Uses PHP's ICU `NumberFormatter` for professional-grade locale formatting.
