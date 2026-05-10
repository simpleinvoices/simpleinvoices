<?php

/**
 * Invoice preference token substitution.
 *
 * Tokens use single-brace syntax: {invoice.total}, {biller.name}, etc.
 * They are stored verbatim in the database and expanded only at render time,
 * so the stored value is never affected.
 *
 * Supported fields (Invoice Preferences):
 *   pref_inv_detail_heading, pref_inv_detail_line, pref_inv_payment_method,
 *   pref_inv_payment_line0_name, pref_inv_payment_line0_value,
 *   pref_inv_payment_line1_name, pref_inv_payment_line1_value,
 *   pref_inv_payment_line2_name, pref_inv_payment_line2_value,
 *   pref_inv_payment_line3_name, pref_inv_payment_line3_value,
 *   pref_inv_payment_line4_name, pref_inv_payment_line4_value,
 *   pref_inv_payment_line5_name, pref_inv_payment_line5_value
 *
 * Also expanded via expandString():
 *   si_biller.footer
 */
class InvoiceTokens
{
    /** Preference fields that support token expansion. */
    private const EXPANDABLE = [
        'pref_inv_detail_heading',
        'pref_inv_detail_line',
        'pref_inv_payment_method',
        'pref_inv_payment_line0_name',
        'pref_inv_payment_line0_value',
        'pref_inv_payment_line1_name',
        'pref_inv_payment_line1_value',
        'pref_inv_payment_line2_name',
        'pref_inv_payment_line2_value',
        'pref_inv_payment_line3_name',
        'pref_inv_payment_line3_value',
        'pref_inv_payment_line4_name',
        'pref_inv_payment_line4_value',
        'pref_inv_payment_line5_name',
        'pref_inv_payment_line5_value',
    ];

    /**
     * Build the token -> value map from invoice, biller, customer, and preference data.
     */
    public static function buildMap(array $invoice, array $biller, array $customer, array $preference, array $lang = []): array
    {
        require_once __DIR__ . '/../class/CurrencySignHelper.php';

        $sign = $invoice['currency_sign'] ?? $preference['pref_currency_sign'] ?? '';
        $code = $invoice['denorm_currency_code'] ?? $invoice['currency_code'] ?? $preference['currency_code'] ?? '';
        $position = $invoice['currency_position'] ?? $preference['currency_position'] ?? '';
        $currSign = CurrencySignHelper::forDisplay($sign);
        $fmt = static fn($v): string => CurrencySignHelper::format((float) ($v ?? 0), $sign, $position, $code);

        $dueDate = '';
        if (!empty($invoice['calc_due_date'])) {
            $dueDate = siLocal::date($invoice['calc_due_date']);
        } elseif (!empty($invoice['due_date']) && $invoice['due_date'] !== '0000-00-00') {
            $dueDate = siLocal::date($invoice['due_date']);
        }

        return [
            '{invoice.number}'            => (string) ($invoice['index_id'] ?? ''),
            '{invoice.date}'              => siLocal::date($invoice['date'] ?? ''),
            '{invoice.due_date}'          => $dueDate,
            '{invoice.total}'             => $fmt($invoice['total'] ?? 0),
            '{invoice.subtotal}'          => $fmt($invoice['gross'] ?? 0),
            '{invoice.tax}'               => $fmt($invoice['total_tax'] ?? 0),
            '{invoice.paid}'              => $fmt($invoice['paid'] ?? 0),
            '{invoice.owing}'             => $fmt($invoice['owing'] ?? 0),
            '{invoice.currency}'          => $currSign,
            '{invoice.currency_code}'     => (string) ($invoice['currency_code'] ?? $preference['currency_code'] ?? ''),
            '{invoice.note}'              => (string) ($invoice['note'] ?? ''),
            '{invoice.payment_term}'      => (string) ($invoice['payment_term_label'] ?? ''),
            '{invoice.payment_term_label}'=> (string) ($invoice['payment_term_label'] ?? ''),
            '{preference.pref_inv_payment_line0_name}'=> (string) ($preference['pref_inv_payment_line0_name'] ?? ''),
            '{preference.pref_inv_payment_line0_value}'=> (string) ($preference['pref_inv_payment_line0_value'] ?? ''),
            '{preference.pref_inv_payment_line3_name}'=> (string) ($preference['pref_inv_payment_line3_name'] ?? ''),
            '{preference.pref_inv_payment_line3_value}'=> (string) ($preference['pref_inv_payment_line3_value'] ?? ''),
            '{preference.pref_inv_payment_line4_name}'=> (string) ($preference['pref_inv_payment_line4_name'] ?? ''),
            '{preference.pref_inv_payment_line4_value}'=> (string) ($preference['pref_inv_payment_line4_value'] ?? ''),
            '{preference.pref_inv_payment_line5_name}'=> (string) ($preference['pref_inv_payment_line5_name'] ?? ''),
            '{preference.pref_inv_payment_line5_value}'=> (string) ($preference['pref_inv_payment_line5_value'] ?? ''),
            '{biller.name}'               => (string) ($biller['name'] ?? ''),
            '{biller.email}'              => (string) ($biller['email'] ?? ''),
            '{biller.phone}'              => (string) ($biller['phone'] ?? ''),
            '{biller.address}'            => (string) ($biller['street_address'] ?? ''),
            '{biller.city}'               => (string) ($biller['city'] ?? ''),
            '{biller.state}'              => (string) ($biller['state'] ?? ''),
            '{biller.zip}'                => (string) ($biller['zip_code'] ?? ''),
            '{biller.country}'            => (string) ($biller['country'] ?? ''),
            '{biller.bank_account_name}'  => (string) ($biller['bank_account_name'] ?? ''),
            '{biller.bank_name}'          => (string) ($biller['bank_name'] ?? ''),
            '{biller.bank_swift_bic}'     => (string) ($biller['bank_swift_bic'] ?? ''),
            '{biller.bank_account_number}'=> (string) ($biller['bank_account_number'] ?? ''),
            '{biller.bank_routing_sort_code}' => (string) ($biller['bank_routing_sort_code'] ?? ''),
            '{customer.name}'             => (string) ($customer['name'] ?? ''),
            '{customer.email}'            => (string) ($customer['email'] ?? ''),
            '{customer.phone}'            => (string) ($customer['phone'] ?? ''),
            '{lang.account_name}'               => (string) ($lang['account_name'] ?? ''),
            '{lang.account_number}'             => (string) ($lang['account_number'] ?? ''),
            '{lang.payment_terms}'              => (string) ($lang['payment_terms'] ?? ''),
            '{lang.details}'                    => (string) ($lang['details'] ?? ''),
            '{lang.electronic_funds_transfer}'  => (string) ($lang['electronic_funds_transfer'] ?? ''),
        ];
    }

    /**
     * Expand tokens in an arbitrary string value.
     */
    public static function expandString(string $value, array $invoice, array $biller, array $customer, array $preference, array $lang = []): string
    {
        $map = static::buildMap($invoice, $biller, $customer, $preference, $lang);
        return str_replace(array_keys($map), array_values($map), $value);
    }

    /**
     * Return a copy of $preference with all expandable text fields resolved.
     */
    public static function expandPreference(array $preference, array $invoice, array $biller, array $customer, array $lang = []): array
    {
        $map = static::buildMap($invoice, $biller, $customer, $preference, $lang);

        foreach (self::EXPANDABLE as $field) {
            if (isset($preference[$field]) && $preference[$field] !== '') {
                $preference[$field] = str_replace(array_keys($map), array_values($map), $preference[$field]);
            }
        }

        return $preference;
    }
}
