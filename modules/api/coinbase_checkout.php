<?php

require_once __DIR__ . '/_payment_gateway_helpers.php';
require_once __DIR__ . '/../../include/class/payment/gateway/AbstractGateway.php';
require_once __DIR__ . '/../../include/class/payment/gateway/CoinbaseCommerceGateway.php';

$invoice_id = (int) ($_GET['invoice_id'] ?? 0);
$inv        = gatewayValidateInvoice($invoice_id, $siUrl);
$biller     = getBiller($inv['biller_id'], $inv['domain_id']);

if (empty($biller['coinbase_api_key'])) {
    header('Location: ' . $siUrl . '/index.php?module=invoices&view=manage&payment_error=config');
    exit;
}

$pref       = getPreference($inv['preference_id'], $inv['domain_id']);
$successUrl = $siUrl . '/index.php?module=payment&view=success&gateway=coinbase&invoice_id=' . $invoice_id;
$cancelUrl  = $siUrl . '/index.php?module=payment&view=cancel&gateway=coinbase&invoice_id=' . $invoice_id;

try {
    $gateway = new CoinbaseCommerceGateway([
        'api_key'        => $biller['coinbase_api_key'],
        'webhook_secret' => $biller['coinbase_webhook_secret'] ?? '',
    ]);

    $checkoutUrl = $gateway->createCheckoutSession(
        $invoice_id,
        (string) $inv['index_id'],
        (float) $inv['owing'],
        (string) ($inv['currency_code'] ?? $pref['currency_code'] ?? 'USD'),
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
    $logger->log('Coinbase Commerce checkout error: ' . $e->getMessage(), LegacyLogger::ERROR);
    header('Location: ' . $siUrl . '/index.php?module=payment&view=cancel&gateway=coinbase&invoice_id=' . $invoice_id);
    exit;
}
