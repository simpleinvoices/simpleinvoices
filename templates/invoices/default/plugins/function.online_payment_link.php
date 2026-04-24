<?php

function smarty_function_online_payment_link($params, $_unused = null) {
    global $LANG;
    global $siUrl;
    global $config;

    $domain_id = domain_id::get($params['domain_id']);
    $url        = getURL();
    $types      = array_map('trim', explode(',', (string) ($params['type'] ?? '')));
    $invoice_id = (int) ($params['invoice'] ?? 0);

    $items = [];

    $gateways = [
        'paypal_commerce' => [
            'url_key'   => 'paypal_checkout',
            'icon'      => 'paypal',
            'label_key' => 'pay_with_paypal',
            'label_def' => 'PayPal',
        ],
        'stripe' => [
            'url_key'   => 'stripe_checkout',
            'icon'      => 'stripe',
            'label_key' => 'pay_with_stripe',
            'label_def' => 'Stripe',
        ],
        'mollie' => [
            'url_key'   => 'mollie_checkout',
            'icon'      => 'ideal',
            'label_key' => 'pay_with_mollie',
            'label_def' => 'Mollie',
        ],
        'authorizenet' => [
            'url_key'   => 'authorizenet_checkout',
            'icon'      => 'authorize',
            'label_key' => 'pay_with_authorizenet',
            'label_def' => 'Authorize.net',
        ],
        'kofi' => [
            'url_key'   => 'kofi_checkout',
            'icon'      => 'cash-app',
            'label_key' => 'pay_with_kofi',
            'label_def' => 'Ko-fi',
            'target'    => '_blank',
        ],
        'coinbase' => [
            'url_key'   => 'coinbase_checkout',
            'icon'      => 'bitcoin',
            'label_key' => 'pay_with_coinbase',
            'label_def' => 'Crypto',
        ],
        'adyen' => [
            'url_key'   => 'adyen_checkout',
            'icon'      => 'adyen',
            'label_key' => 'pay_with_adyen',
            'label_def' => 'Adyen',
        ],
        'eway_rapid' => [
            'url_key'   => 'eway_checkout',
            'icon'      => 'eway',
            'label_key' => 'pay_with_eway',
            'label_def' => 'eWay',
        ],
        'paymentsgateway_modern' => [
            'url_key'   => 'paymentsgateway_checkout',
            'icon'      => 'sage',
            'label_key' => 'pay_with_paymentsgateway',
            'label_def' => 'Pay Online',
        ],
    ];

    foreach ($gateways as $key => $gw) {
        if (!in_array($key, $types)) {
            continue;
        }
        $amount = (float) ($params['amount'] ?? 0);
        if ($key !== 'kofi' && ($invoice_id <= 0 || $amount <= 0)) {
            continue;
        }
        $href = htmlspecialchars($siUrl, ENT_QUOTES) . '/index.php?module=api&view=' . urlencode($gw['url_key']) . '&invoice_id=' . $invoice_id;
        $label = htmlspecialchars($LANG[$gw['label_key']] ?? $gw['label_def'], ENT_QUOTES, 'UTF-8');
        $target = isset($gw['target']) ? ' target="' . htmlspecialchars($gw['target'], ENT_QUOTES) . '"' : '';
        $iconUrl = htmlspecialchars($url, ENT_QUOTES) . '/templates/invoices/img/payments/' . urlencode($gw['icon']) . '.svg';
        $items[] = ['href' => $href, 'label' => $label, 'target' => $target, 'iconUrl' => $iconUrl];
    }

    if (empty($items)) {
        return;
    }

    $cells = [];
    $n = count($items);
    foreach ($items as $i => $it) {
        $isLast = ($i === $n - 1);
        $borderRight = $isLast ? 'border-right: none;' : 'border-right: 1px solid #e2e8f0;';
        // Spans + CSS table display (not <table> inside <a>) so mPDF emits a single clickable link region.
        $inner = '<span class="si-payment-pay-pair">'
            . '<span class="si-payment-pay-icon-wrap">'
            . '<img src="' . $it['iconUrl'] . '" alt="' . $it['label'] . '" width="40" height="25" class="si-payment-pay-btn-img" />'
            . '</span>'
            . '<span class="si-payment-pay-text-col">'
            . '<span class="si-payment-pay-label">' . $it['label'] . '</span>'
            . '</span>'
            . '</span>';

        $cells[] = '<td class="si-payment-links-cell" style="vertical-align: top; text-align: left; padding: 10px 14px; '
            . $borderRight . '">'
            . '<a href="' . $it['href'] . '" class="si-payment-pay-btn"' . $it['target'] . '>'
            . $inner
            . '</a></td>';
    }

    echo '<div class="si-payment-links">'
        . '<div class="si-payment-links-heading">' . htmlspecialchars($LANG['pay_with'] ?? 'Pay with', ENT_QUOTES, 'UTF-8') . '</div>'
        . '<table class="si-payment-links-table" width="100%" cellspacing="0" cellpadding="0" '
        . 'style="width: 100%; border-collapse: collapse; border: 1px solid #e2e8f0; table-layout: fixed;"><tr>'
        . implode('', $cells)
        . '</tr></table>'
        . '</div>';
}
