<?php

require_once __DIR__ . '/_payment_gateway_helpers.php';
require_once __DIR__ . '/../../include/class/payment/gateway/AbstractGateway.php';
require_once __DIR__ . '/../../include/class/payment/gateway/EwayGateway.php';

$invoice_id = (int) ($_GET['invoice_id'] ?? 0);
$inv        = gatewayValidateInvoice($invoice_id, $siUrl);
$biller     = getBiller($inv['biller_id'], $inv['domain_id']);

if (empty($biller['eway_api_key']) || empty($biller['eway_api_password'])) {
    header('Location: ' . $siUrl . '/index.php?module=invoices&view=manage&payment_error=config');
    exit;
}

$pref       = getPreference($inv['preference_id'], $inv['domain_id']);
$successUrl = $siUrl . '/index.php?module=api&view=eway_return&invoice_id=' . $invoice_id
    . '&biller_id=' . (int) $biller['id'] . '&domain_id=' . (int) $inv['domain_id'];
$cancelUrl  = $siUrl . '/index.php?module=payment&view=cancel&gateway=eway&invoice_id=' . $invoice_id;

try {
    $gateway = new EwayGateway([
        'api_key'      => $biller['eway_api_key'],
        'api_password' => $biller['eway_api_password'],
        'test_mode'    => !empty($biller['eway_test_mode']),
    ]);

    $checkoutUrl = $gateway->createCheckoutSession(
        $invoice_id,
        (string) $inv['index_id'],
        (float) $inv['owing'],
        (string) ($pref['currency_code'] ?? 'AUD'),
        $successUrl,
        $cancelUrl,
        '',
        (int) $inv['domain_id'],
        (int) $biller['id'],
        'Invoice ' . $inv['index_id']
    );

    header('Location: ' . $checkoutUrl);
    exit;

} catch (Exception $e) {
    $logger->log('eWay checkout error: ' . $e->getMessage(), LegacyLogger::ERROR);
    header('Location: ' . $siUrl . '/index.php?module=payment&view=cancel&gateway=eway&invoice_id=' . $invoice_id);
    exit;
}
