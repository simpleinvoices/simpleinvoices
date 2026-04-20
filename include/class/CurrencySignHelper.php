<?php

/**
 * Preset currency signs for invoice preferences (fiat aligned with shipped lang packs + common crypto).
 * Values may be literal symbols or HTML entities for PDF compatibility (see help_inv_pref_currency_sign).
 */
class CurrencySignHelper
{
    /**
     * Decode HTML entities in stored preference for UI output.
     * Values may be literal symbols or entities (e.g. &#8364;) for legacy PDF/HTML compatibility;
     * Blade {{ }} escapes ampersands, which would show the entity as plain text without this step.
     */
    public static function forDisplay(?string $stored): string
    {
        if ($stored === null || $stored === '') {
            return '';
        }

        return html_entity_decode(trim($stored), ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }

    /**
     * @return list<array{label: string, presets: list<array{value: string, label: string, aliases?: list<string>}>}>
     */
    public static function getPresetGroups(): array
    {
        return [
            [
                'label' => 'Americas & Pacific dollars',
                'presets' => [
                    ['value' => '$', 'label' => 'US Dollar — USD ($)', 'aliases' => ['&#36;', 'US$', 'USD']],
                    ['value' => 'C$', 'label' => 'Canadian Dollar — CAD (C$)', 'aliases' => ['CAD', 'CA$']],
                    ['value' => 'A$', 'label' => 'Australian Dollar — AUD (A$)', 'aliases' => ['AUD']],
                    ['value' => 'NZ$', 'label' => 'New Zealand Dollar — NZD (NZ$)', 'aliases' => ['NZD']],
                    ['value' => 'MX$', 'label' => 'Mexican Peso — MXN (MX$)', 'aliases' => ['MXN']],
                    ['value' => 'R$', 'label' => 'Brazilian Real — BRL (R$)', 'aliases' => ['BRL']],
                    ['value' => 'S$', 'label' => 'Singapore Dollar — SGD (S$)', 'aliases' => ['SGD']],
                ],
            ],
            [
                'label' => 'Europe & UK',
                'presets' => [
                    ['value' => '&#8364;', 'label' => 'Euro — EUR (€)', 'aliases' => ['€', '&euro;', 'EUR']],
                    ['value' => '&#163;', 'label' => 'Pound sterling — GBP (£)', 'aliases' => ['£', '&pound;', 'GBP']],
                    ['value' => 'CHF', 'label' => 'Swiss Franc — CHF', 'aliases' => ['Fr.', 'SFr.']],
                    ['value' => 'kr', 'label' => 'Nordic krona — SEK / DKK / NOK (kr)', 'aliases' => ['SEK', 'DKK', 'NOK', 'kr.']],
                    ['value' => 'zł', 'label' => 'Polish Zloty — PLN (zł)', 'aliases' => ['PLN']],
                    ['value' => 'Kč', 'label' => 'Czech Koruna — CZK (Kč)', 'aliases' => ['CZK']],
                    ['value' => 'Ft', 'label' => 'Hungarian Forint — HUF (Ft)', 'aliases' => ['HUF']],
                    ['value' => 'lei', 'label' => 'Romanian Leu — RON (lei)', 'aliases' => ['RON']],
                    ['value' => 'лв', 'label' => 'Bulgarian Lev — BGN (лв)', 'aliases' => ['BGN', 'лв.']],
                    ['value' => '₺', 'label' => 'Turkish Lira — TRY (₺)', 'aliases' => ['TRY', 'TL']],
                    ['value' => 'дин.', 'label' => 'Serbian Dinar — RSD (дин.)', 'aliases' => ['RSD', 'дин']],
                    ['value' => '₽', 'label' => 'Russian Ruble — RUB (₽)', 'aliases' => ['RUB', 'руб.']],
                ],
            ],
            [
                'label' => 'Asia, Africa & Middle East',
                'presets' => [
                    ['value' => '¥', 'label' => 'Chinese Yuan — CNY (¥)', 'aliases' => ['CNY', '元', 'RMB']],
                    ['value' => 'NT$', 'label' => 'New Taiwan Dollar — TWD (NT$)', 'aliases' => ['TWD']],
                    ['value' => 'HK$', 'label' => 'Hong Kong Dollar — HKD (HK$)', 'aliases' => ['HKD']],
                    ['value' => '₹', 'label' => 'Indian Rupee — INR (₹)', 'aliases' => ['&#8377;', 'INR', 'Rs']],
                    ['value' => 'Rp', 'label' => 'Indonesian Rupiah — IDR (Rp)', 'aliases' => ['IDR']],
                    ['value' => '₫', 'label' => 'Vietnamese Dong — VND (₫)', 'aliases' => ['VND']],
                    ['value' => '₪', 'label' => 'Israeli Shekel — ILS (₪)', 'aliases' => ['ILS', 'NIS']],
                    ['value' => '﷼', 'label' => 'Saudi Riyal — SAR (﷼)', 'aliases' => ['SAR', 'ر.س']],
                    ['value' => 'R', 'label' => 'South African Rand — ZAR (R)', 'aliases' => ['ZAR']],
                ],
            ],
            [
                'label' => 'Cryptocurrency',
                'presets' => [
                    ['value' => '₿', 'label' => 'Bitcoin (BTC)', 'aliases' => ['BTC']],
                    ['value' => 'Ξ', 'label' => 'Ethereum (ETH)', 'aliases' => ['ETH']],
                    ['value' => 'Ł', 'label' => 'Litecoin (LTC)', 'aliases' => ['LTC']],
                    ['value' => '₳', 'label' => 'Cardano (ADA)', 'aliases' => ['ADA']],
                    ['value' => 'XRP', 'label' => 'Ripple (XRP)'],
                    ['value' => 'SOL', 'label' => 'Solana (SOL)'],
                    ['value' => 'BNB', 'label' => 'Binance Coin (BNB)'],
                    ['value' => 'USDT', 'label' => 'Tether (USDT)'],
                    ['value' => 'USDC', 'label' => 'USD Coin (USDC)'],
                    ['value' => 'DOGE', 'label' => 'Dogecoin (DOGE)', 'aliases' => ['Ð']],
                ],
            ],
        ];
    }

    /**
     * @return array{value: string, label: string, aliases?: list<string>}|null
     */
    public static function findPresetForStored(string $stored): ?array
    {
        $stored = trim($stored);
        if ($stored === '') {
            return null;
        }

        foreach (self::getPresetGroups() as $group) {
            foreach ($group['presets'] as $p) {
                if ($stored === $p['value']) {
                    return $p;
                }
                foreach ($p['aliases'] ?? [] as $a) {
                    if ($stored === $a) {
                        return $p;
                    }
                }
            }
        }

        return null;
    }
}
