<?php

function smarty_function_online_payment_link($params, $_unused = null) {
    global $LANG;
    global $siUrl;
    global $config;

    $domain_id = domain_id::get($params['domain_id']);
    $url       = getURL();
    $types     = array_map('trim', explode(',', (string) ($params['type'] ?? '')));
    $invoice_id = (int) ($params['invoice'] ?? 0);

    // ── PayPal Commerce Platform (v2 Orders) ───────────────────────────────────
    if (in_array('paypal_commerce', $types) && $invoice_id > 0 && (float) ($params['amount'] ?? 0) > 0) {
        $link  = '<a href="' . htmlspecialchars($siUrl, ENT_QUOTES) . '/index.php?module=api&view=paypal_checkout&invoice_id=' . $invoice_id . '" class="btn btn-sm btn-outline-primary me-1">';
        $link .= '<i class="ti ti-brand-paypal me-1"></i>' . htmlspecialchars($LANG['pay_with_paypal'] ?? 'Pay with PayPal', ENT_QUOTES, 'UTF-8');
        $link .= '</a>';
        echo $link;
    }

    // ── Stripe ─────────────────────────────────────────────────────────────────
    if (in_array('stripe', $types) && $invoice_id > 0 && (float) ($params['amount'] ?? 0) > 0) {
        $link  = '<a href="' . htmlspecialchars($siUrl, ENT_QUOTES) . '/index.php?module=api&view=stripe_checkout&invoice_id=' . $invoice_id . '" class="btn btn-sm btn-outline-primary me-1">';
        $link .= '<i class="ti ti-credit-card me-1"></i>' . htmlspecialchars($LANG['pay_with_stripe'] ?? 'Pay with Stripe', ENT_QUOTES, 'UTF-8');
        $link .= '</a>';
        echo $link;
    }

    // ── Mollie ─────────────────────────────────────────────────────────────────
    if (in_array('mollie', $types) && $invoice_id > 0 && (float) ($params['amount'] ?? 0) > 0) {
        $link  = '<a href="' . htmlspecialchars($siUrl, ENT_QUOTES) . '/index.php?module=api&view=mollie_checkout&invoice_id=' . $invoice_id . '" class="btn btn-sm btn-outline-primary me-1">';
        $link .= '<i class="ti ti-credit-card me-1"></i>' . htmlspecialchars($LANG['pay_with_mollie'] ?? 'Pay with Mollie', ENT_QUOTES, 'UTF-8');
        $link .= '</a>';
        echo $link;
    }

    // ── Authorize.net ──────────────────────────────────────────────────────────
    if (in_array('authorizenet', $types) && $invoice_id > 0 && (float) ($params['amount'] ?? 0) > 0) {
        $link  = '<a href="' . htmlspecialchars($siUrl, ENT_QUOTES) . '/index.php?module=api&view=authorizenet_checkout&invoice_id=' . $invoice_id . '" class="btn btn-sm btn-outline-primary me-1">';
        $link .= '<i class="ti ti-credit-card me-1"></i>' . htmlspecialchars($LANG['pay_with_authorizenet'] ?? 'Pay with Authorize.net', ENT_QUOTES, 'UTF-8');
        $link .= '</a>';
        echo $link;
    }

    // ── Ko-fi (tip / donate) ──────────────────────────────────────────────────
    if (in_array('kofi', $types) && $invoice_id > 0) {
        $link  = '<a href="' . htmlspecialchars($siUrl, ENT_QUOTES) . '/index.php?module=api&view=kofi_checkout&invoice_id=' . $invoice_id . '" class="btn btn-sm btn-outline-warning me-1" target="_blank">';
        $link .= '<i class="ti ti-coffee me-1"></i>' . htmlspecialchars($LANG['pay_with_kofi'] ?? 'Support on Ko-fi', ENT_QUOTES, 'UTF-8');
        $link .= '</a>';
        echo $link;
    }

    // ── Coinbase Commerce ─────────────────────────────────────────────────────
    if (in_array('coinbase', $types) && $invoice_id > 0 && (float) ($params['amount'] ?? 0) > 0) {
        $link  = '<a href="' . htmlspecialchars($siUrl, ENT_QUOTES) . '/index.php?module=api&view=coinbase_checkout&invoice_id=' . $invoice_id . '" class="btn btn-sm btn-outline-primary me-1">';
        $link .= '<i class="ti ti-currency-bitcoin me-1"></i>' . htmlspecialchars($LANG['pay_with_coinbase'] ?? 'Pay with Crypto', ENT_QUOTES, 'UTF-8');
        $link .= '</a>';
        echo $link;
    }

    // ── Adyen ─────────────────────────────────────────────────────────────────
    if (in_array('adyen', $types) && $invoice_id > 0 && (float) ($params['amount'] ?? 0) > 0) {
        $link  = '<a href="' . htmlspecialchars($siUrl, ENT_QUOTES) . '/index.php?module=api&view=adyen_checkout&invoice_id=' . $invoice_id . '" class="btn btn-sm btn-outline-primary me-1">';
        $link .= '<i class="ti ti-credit-card me-1"></i>' . htmlspecialchars($LANG['pay_with_adyen'] ?? 'Pay with Adyen', ENT_QUOTES, 'UTF-8');
        $link .= '</a>';
        echo $link;
    }

    // ── eWay Rapid ────────────────────────────────────────────────────────────
    if (in_array('eway_rapid', $types) && $invoice_id > 0 && (float) ($params['amount'] ?? 0) > 0) {
        $link  = '<a href="' . htmlspecialchars($siUrl, ENT_QUOTES) . '/index.php?module=api&view=eway_checkout&invoice_id=' . $invoice_id . '" class="btn btn-sm btn-outline-primary me-1">';
        $link .= '<i class="ti ti-credit-card me-1"></i>' . htmlspecialchars($LANG['pay_with_eway'] ?? 'Pay with eWay', ENT_QUOTES, 'UTF-8');
        $link .= '</a>';
        echo $link;
    }

    // ── Payments Gateway (modern redirect) ────────────────────────────────────
    if (in_array('paymentsgateway_modern', $types) && $invoice_id > 0 && (float) ($params['amount'] ?? 0) > 0) {
        $link  = '<a href="' . htmlspecialchars($siUrl, ENT_QUOTES) . '/index.php?module=api&view=paymentsgateway_checkout&invoice_id=' . $invoice_id . '" class="btn btn-sm btn-outline-primary me-1">';
        $link .= '<i class="ti ti-credit-card me-1"></i>' . htmlspecialchars($LANG['pay_with_paymentsgateway'] ?? 'Pay Online', ENT_QUOTES, 'UTF-8');
        $link .= '</a>';
        echo $link;
    }

}
