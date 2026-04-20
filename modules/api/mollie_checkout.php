<?php

require_once __DIR__ . '/_payment_gateway_helpers.php';
require_once __DIR__ . '/../../include/class/payment/gateway/AbstractGateway.php';
require_once __DIR__ . '/../../include/class/payment/gateway/MollieGateway.php';

$invoice_id = (int) ($_GET['invoice_id'] ?? 0);
$inv        = gatewayValidateInvoice($invoice_id, $siUrl);
$biller     = getBiller($inv['biller_id'], $inv['domain_id']);

if (empty($biller['mollie_api_key'])) {
    header('Location: ' . $siUrl . '/index.php?module=invoices&view=manage&payment_error=config');
    exit;
}

$pref = getPreference($inv['preference_id'], $inv['domain_id']);

// Mollie redirects customer here after checkout (any outcome); mollie_return checks payment status.
$successUrl = $siUrl . '/index.php?module=api&view=mollie_return&invoice_id=' . $invoice_id . '&domain_id=' . (int) $inv['domain_id'];
$cancelUrl  = $siUrl . '/index.php?module=payment&view=cancel&gateway=mollie&invoice_id=' . $invoice_id;
$webhookUrl = $siUrl . '/index.php?module=api&view=mollie_webhook&biller_id=' . (int) $biller['id'] . '&domain_id=' . (int) $inv['domain_id'];

try {
    $gateway = new MollieGateway([
        'api_key'   => $biller['mollie_api_key'],
        'test_mode' => str_starts_with((string) $biller['mollie_api_key'], 'test_'),
    ]);

    $checkoutUrl = $gateway->createCheckoutSession(
        $invoice_id,
        (string) $inv['index_id'],
        (float) $inv['owing'],
        (string) ($pref['currency_code'] ?? 'EUR'),
        $successUrl,
        $cancelUrl,
        $webhookUrl,
        (int) $inv['domain_id'],
        (int) $biller['id'],
        'Invoice ' . $inv['index_id']
    );

    header('Location: ' . $checkoutUrl);
    exit;

} catch (Exception $e) {
    $logger->log('Mollie checkout error: ' . $e->getMessage(), LegacyLogger::ERROR);
    header('Location: ' . $siUrl . '/index.php?module=payment&view=cancel&gateway=mollie&invoice_id=' . $invoice_id);
    exit;
}
