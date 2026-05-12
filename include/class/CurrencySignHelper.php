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
     * @return list<array{label: string, presets: list<array{value: string, label: string, code: string, aliases?: list<string>}>}>
     */
    public static function getPresetGroups(): array
    {
        return [
            [
                'label' => 'Americas & Pacific dollars',
                'presets' => [
                    ['value' => '$',   'code' => 'USD', 'label' => 'US Dollar - USD ($)',             'aliases' => ['&#36;', 'US$', 'USD']],
                    ['value' => 'C$',  'code' => 'CAD', 'label' => 'Canadian Dollar - CAD (C$)',      'aliases' => ['CAD', 'CA$']],
                    ['value' => 'A$',  'code' => 'AUD', 'label' => 'Australian Dollar - AUD (A$)',    'aliases' => ['AUD']],
                    ['value' => 'NZ$', 'code' => 'NZD', 'label' => 'New Zealand Dollar - NZD (NZ$)', 'aliases' => ['NZD']],
                    ['value' => 'MX$', 'code' => 'MXN', 'label' => 'Mexican Peso - MXN (MX$)',       'aliases' => ['MXN']],
                    ['value' => 'R$',  'code' => 'BRL', 'label' => 'Brazilian Real - BRL (R$)',       'aliases' => ['BRL'],                   'position' => 'right'],
                    ['value' => 'S$',  'code' => 'SGD', 'label' => 'Singapore Dollar - SGD (S$)',     'aliases' => ['SGD']],
                ],
            ],
            [
                'label' => 'Europe & UK',
                'presets' => [
                    ['value' => '&#8364;', 'code' => 'EUR', 'label' => 'Euro - EUR (€)',                      'aliases' => ['€', '&euro;', 'EUR'],    'position' => 'right'],
                    ['value' => '&#163;',  'code' => 'GBP', 'label' => 'Pound sterling - GBP (£)',            'aliases' => ['£', '&pound;', 'GBP']],
                    ['value' => 'CHF',     'code' => 'CHF', 'label' => 'Swiss Franc - CHF',                   'aliases' => ['Fr.', 'SFr.'],           'position' => 'right'],
                    ['value' => 'kr',      'code' => 'SEK', 'label' => 'Swedish Krona - SEK (kr)',            'aliases' => ['SEK', 'kr.'],            'position' => 'right'],
                    ['value' => 'kr',      'code' => 'DKK', 'label' => 'Danish Krone - DKK (kr)',             'aliases' => ['DKK'],                   'position' => 'right'],
                    ['value' => 'kr',      'code' => 'NOK', 'label' => 'Norwegian Krone - NOK (kr)',          'aliases' => ['NOK'],                   'position' => 'right'],
                    ['value' => 'zł',      'code' => 'PLN', 'label' => 'Polish Zloty - PLN (zł)',             'aliases' => ['PLN'],                   'position' => 'right'],
                    ['value' => 'Kč',      'code' => 'CZK', 'label' => 'Czech Koruna - CZK (Kč)',            'aliases' => ['CZK'],                   'position' => 'right'],
                    ['value' => 'Ft',      'code' => 'HUF', 'label' => 'Hungarian Forint - HUF (Ft)',         'aliases' => ['HUF'],                   'position' => 'right'],
                    ['value' => 'lei',     'code' => 'RON', 'label' => 'Romanian Leu - RON (lei)',            'aliases' => ['RON'],                   'position' => 'right'],
                    ['value' => 'лв',      'code' => 'BGN', 'label' => 'Bulgarian Lev - BGN (лв)',            'aliases' => ['BGN', 'лв.'],            'position' => 'right'],
                    ['value' => '₺',       'code' => 'TRY', 'label' => 'Turkish Lira - TRY (₺)',              'aliases' => ['TRY', 'TL']],
                    ['value' => 'дин.',    'code' => 'RSD', 'label' => 'Serbian Dinar - RSD (дин.)',          'aliases' => ['RSD', 'дин'],            'position' => 'right'],
                    ['value' => '₽',       'code' => 'RUB', 'label' => 'Russian Ruble - RUB (₽)',             'aliases' => ['RUB', 'руб.'],           'position' => 'right'],
                ],
            ],
            [
                'label' => 'Asia, Africa & Middle East',
                'presets' => [
                    ['value' => '¥',  'code' => 'CNY', 'label' => 'Chinese Yuan - CNY (¥)',          'aliases' => ['CNY', '元', 'RMB']],
                    ['value' => '¥',  'code' => 'JPY', 'label' => 'Japanese Yen - JPY (¥)',          'aliases' => ['JPY']],
                    ['value' => 'NT$','code' => 'TWD', 'label' => 'New Taiwan Dollar - TWD (NT$)',   'aliases' => ['TWD']],
                    ['value' => 'HK$','code' => 'HKD', 'label' => 'Hong Kong Dollar - HKD (HK$)',   'aliases' => ['HKD']],
                    ['value' => '₹',  'code' => 'INR', 'label' => 'Indian Rupee - INR (₹)',          'aliases' => ['&#8377;', 'INR', 'Rs']],
                    ['value' => 'Rp', 'code' => 'IDR', 'label' => 'Indonesian Rupiah - IDR (Rp)',   'aliases' => ['IDR'],                   'position' => 'right'],
                    ['value' => '₫',  'code' => 'VND', 'label' => 'Vietnamese Dong - VND (₫)',      'aliases' => ['VND'],                   'position' => 'right'],
                    ['value' => '₪',  'code' => 'ILS', 'label' => 'Israeli Shekel - ILS (₪)',       'aliases' => ['ILS', 'NIS']],
                    ['value' => '﷼',  'code' => 'SAR', 'label' => 'Saudi Riyal - SAR (﷼)',           'aliases' => ['SAR', 'ر.س']],
                    ['value' => 'R',  'code' => 'ZAR', 'label' => 'South African Rand - ZAR (R)',   'aliases' => ['ZAR'],                   'position' => 'right'],
                ],
            ],
            [
                'label' => 'Cryptocurrency',
                'presets' => [
                    ['value' => '₿',   'code' => 'BTC',  'label' => 'Bitcoin (BTC)',       'aliases' => ['BTC']],
                    ['value' => 'Ξ',   'code' => 'ETH',  'label' => 'Ethereum (ETH)',      'aliases' => ['ETH']],
                    ['value' => 'Ł',   'code' => 'LTC',  'label' => 'Litecoin (LTC)',      'aliases' => ['LTC']],
                    ['value' => '₳',   'code' => 'ADA',  'label' => 'Cardano (ADA)',       'aliases' => ['ADA']],
                    ['value' => 'XRP', 'code' => 'XRP',  'label' => 'Ripple (XRP)'],
                    ['value' => 'SOL', 'code' => 'SOL',  'label' => 'Solana (SOL)'],
                    ['value' => 'BNB', 'code' => 'BNB',  'label' => 'Binance Coin (BNB)'],
                    ['value' => 'USDT','code' => 'USDT', 'label' => 'Tether (USDT)'],
                    ['value' => 'USDC','code' => 'USDC', 'label' => 'USD Coin (USDC)'],
                    ['value' => 'DOGE','code' => 'DOGE', 'label' => 'Dogecoin (DOGE)',     'aliases' => ['Ð']],
                ],
            ],
        ];
    }

    /**
     * Return the ISO code for a given stored sign value, or '' if no preset match.
     * Uses the first matching preset (e.g. 'kr' → 'SEK').
     */
    public static function codeForSign(string $sign): string
    {
        $preset = self::findPresetForStored($sign);
        return $preset['code'] ?? '';
    }

    /**
     * Find a preset matching the stored sign value and optional ISO code.
     * When code is provided, prefers a preset that also matches the code
     * (useful for currencies sharing a symbol, e.g. kr = SEK/DKK/NOK).
     *
     * @return array{value: string, label: string, code: string, aliases?: list<string>}|null
     */
    public static function findPresetForStored(string $stored, string $code = ''): ?array
    {
        $stored = trim($stored);
        if ($stored === '') {
            return null;
        }

        // Normalize to decoded form so raw entities (&#8364;) and Unicode (€) both match
        $storedDecoded = html_entity_decode($stored, ENT_QUOTES | ENT_HTML5, 'UTF-8');

        $firstMatch = null;
        foreach (self::getPresetGroups() as $group) {
            foreach ($group['presets'] as $p) {
                $presetDecoded = html_entity_decode($p['value'], ENT_QUOTES | ENT_HTML5, 'UTF-8');
                $signMatch = ($stored === $p['value']) || ($storedDecoded === $presetDecoded);
                if (!$signMatch) {
                    foreach ($p['aliases'] ?? [] as $a) {
                        $aDecoded = html_entity_decode($a, ENT_QUOTES | ENT_HTML5, 'UTF-8');
                        if ($stored === $a || $storedDecoded === $aDecoded) { $signMatch = true; break; }
                    }
                }
                if (!$signMatch) { continue; }
                // Sign matched - if caller gave a code and it also matches, return immediately
                if ($code !== '' && ($p['code'] ?? '') === $code) {
                    return $p;
                }
                if ($firstMatch === null) { $firstMatch = $p; }
            }
        }

        return $firstMatch;
    }

    private static ?array $positionMap = null;

    private static function getPositionMap(): array
    {
        if (self::$positionMap !== null) {
            return self::$positionMap;
        }
        return self::$positionMap = [
            'right' => [
                '€', '&#8364;', '&euro;',
                'Ft',
                'Kč',
                'zł',
                'kr', 'kr.',
                'lei',
                'лв', 'лв.',
                'дин.', 'дин',
                '₽', 'руб.',
                'R',
                'Fr.', 'SFr.',
                '₫',
                'Rp',
            ],
            'left' => [
                '$', 'C$', 'A$', 'NZ$', 'MX$', 'R$', 'S$', 'NT$', 'HK$',
                '£', '&#163;', '&pound;',
                '¥',
                '₹', '&#8377;', 'Rs',
                '₪',
                '﷼', 'ر.س',
                '₿', 'Ξ', 'Ł', '₳',
                'XRP', 'SOL', 'BNB', 'USDT', 'USDC', 'DOGE', 'Ð',
                'CHF',
                '₺', 'TL',
            ],
        ];
    }

    public static function defaultPositionForSign(string $sign, string $code = ''): string
    {
        if ($sign === '') {
            return 'left';
        }
        $decoded = html_entity_decode(trim($sign), ENT_QUOTES | ENT_HTML5, 'UTF-8');

        $preset = self::findPresetForStored($sign, $code);
        if ($preset !== null && isset($preset['position'])) {
            return $preset['position'];
        }

        if ($code !== '') {
            $rightCodes = ['EUR','HUF','CZK','PLN','SEK','DKK','NOK','RON','BGN','RSD','RUB','ZAR','VND','IDR','CHF'];
            if (in_array(strtoupper($code), $rightCodes, true)) {
                return 'right';
            }
        }

        foreach (self::getPositionMap()['right'] as $rightSign) {
            $rightDecoded = html_entity_decode($rightSign, ENT_QUOTES | ENT_HTML5, 'UTF-8');
            if ($decoded === $rightDecoded || trim($sign) === $rightSign) {
                return 'right';
            }
        }

        return 'left';
    }

    public static function resolvePosition(string $sign, string $code = '', string $explicitPosition = ''): string
    {
        $pos = strtolower(trim($explicitPosition));
        if ($pos === 'left' || $pos === 'right') {
            return $pos;
        }
        return self::defaultPositionForSign($sign, $code);
    }

    public static function format($amount, string $sign, string $position = '', string $code = '', bool $showCode = false, string $locale = ''): string
    {
        $locale = trim($locale);
        $code = trim($code);

        // Use ICU NumberFormatter when both locale and currency code are available
        if ($locale !== '' && $code !== '' && extension_loaded('intl')) {
            $formatter = new \NumberFormatter($locale, \NumberFormatter::CURRENCY);
            $result = $formatter->formatCurrency((float) $amount, $code);
            if ($result === false) {
                return self::fallbackFormat($amount, $sign, $position, $code, $showCode);
            }
            if ($showCode) {
                $normalized = preg_replace('/\s+/', '', $result);
                if ($normalized !== null && stripos($normalized, $code) === false) {
                    $result .= ' ' . htmlspecialchars($code, ENT_QUOTES, 'UTF-8');
                }
            }
            return $result;
        }

        return self::fallbackFormat($amount, $sign, $position, $code, $showCode);
    }

    private static function fallbackFormat($amount, string $sign, string $position, string $code, bool $showCode): string
    {
        $resolved = self::resolvePosition($sign, $code, $position);
        $formatted = siLocal::number((float) $amount);
        $displaySign = self::forDisplay($sign);
        $displayCode = trim($code);
        if ($displaySign === '') {
            $result = $formatted;
        } elseif ($resolved === 'right') {
            $result = $formatted . ' ' . $displaySign;
        } else {
            $result = $displaySign . $formatted;
        }
        if ($showCode && $displayCode !== '') {
            $result .= ' ' . htmlspecialchars($displayCode, ENT_QUOTES, 'UTF-8');
        }
        return $result;
    }

    /**
     * Convenience wrapper that extracts currency fields from invoice and preference arrays.
     * Uses the preference locale with ICU \NumberFormatter when available.
     */
    public static function formatInvoice($amount, array $invoice, array $preference = []): string
    {
        $sign = $invoice['currency_sign'] ?? $preference['currency_sign'] ?? '';
        $position = $invoice['currency_position'] ?? $preference['currency_position'] ?? '';
        $code = $invoice['denorm_currency_code'] ?? $invoice['currency_code'] ?? $preference['currency_code'] ?? '';
        $locale = $invoice['denorm_currency_locale'] ?? $preference['locale'] ?? '';
        return self::format($amount, $sign, $position, $code, false, $locale);
    }
}
