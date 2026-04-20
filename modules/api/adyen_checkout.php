<?php

require_once __DIR__ . '/_payment_gateway_helpers.php';
require_once __DIR__ . '/../../include/class/payment/gateway/AbstractGateway.php';
require_once __DIR__ . '/../../include/class/payment/gateway/AdyenGateway.php';

$invoice_id = (int) ($_GET['invoice_id'] ?? 0);
$inv        = gatewayValidateInvoice($invoice_id, $siUrl);
$biller     = getBiller($inv['biller_id'], $inv['domain_id']);

if (empty($biller['adyen_api_key']) || empty($biller['adyen_merchant_account'])) {
    header('Location: ' . $siUrl . '/index.php?module=invoices&view=manage&payment_error=config');
    exit;
}

$pref       = getPreference($inv['preference_id'], $inv['domain_id']);
$successUrl = $siUrl . '/index.php?module=payment&view=success&gateway=adyen&invoice_id=' . $invoice_id;

try {
    $gateway = new AdyenGateway([
        'api_key'          => $biller['adyen_api_key'],
        'merchant_account' => $biller['adyen_merchant_account'],
        'hmac_key'         => $biller['adyen_hmac_key'] ?? '',
        'live_prefix'      => $biller['adyen_live_prefix'] ?? '',
        'test_mode'        => !empty($biller['adyen_test_mode']),
    ]);

    $checkoutUrl = $gateway->createCheckoutSession(
        $invoice_id,
        (string) $inv['index_id'],
        (float) $inv['owing'],
        (string) ($pref['currency_code'] ?? 'USD'),
        $successUrl,
        '',
        '',
        (int) $inv['domain_id'],
        (int) $biller['id'],
        'Invoice ' . $inv['index_id']
    );

    header('Location: ' . $checkoutUrl);
    exit;

} catch (Exception $e) {
    $logger->log('Adyen checkout error: ' . $e->getMessage(), LegacyLogger::ERROR);
    header('Location: ' . $siUrl . '/index.php?module=payment&view=cancel&gateway=adyen&invoice_id=' . $invoice_id);
    exit;
}
