<?php

require_once __DIR__ . '/_payment_gateway_helpers.php';
require_once __DIR__ . '/../../include/class/payment/gateway/AbstractGateway.php';
require_once __DIR__ . '/../../include/class/payment/gateway/StripeGateway.php';

$invoice_id = (int) ($_GET['invoice_id'] ?? 0);
$inv        = gatewayValidateInvoice($invoice_id, $siUrl);
$biller     = getBiller($inv['biller_id'], $inv['domain_id']);

if (empty($biller['stripe_secret_key'])) {
    header('Location: ' . $siUrl . '/index.php?module=invoices&view=manage&payment_error=config');
    exit;
}

$pref       = getPreference($inv['preference_id'], $inv['domain_id']);
$successUrl = $siUrl . '/index.php?module=payment&view=success&gateway=stripe&invoice_id=' . $invoice_id;
$cancelUrl  = $siUrl . '/index.php?module=payment&view=cancel&gateway=stripe&invoice_id=' . $invoice_id;
$webhookUrl = $siUrl . '/index.php?module=api&view=stripe_webhook&biller_id=' . (int) $biller['id'] . '&domain_id=' . (int) $inv['domain_id'];

try {
    $gateway = new StripeGateway([
        'secret_key'     => $biller['stripe_secret_key'],
        'webhook_secret' => $biller['stripe_webhook_secret'],
        'test_mode'      => !empty($biller['stripe_test_mode']),
    ]);

    $checkoutUrl = $gateway->createCheckoutSession(
        $invoice_id,
        (string) $inv['index_id'],
        (float) $inv['owing'],
        (string) ($pref['currency_code'] ?? 'USD'),
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
    $logger->log('Stripe checkout error: ' . $e->getMessage(), LegacyLogger::ERROR);
    header('Location: ' . $siUrl . '/index.php?module=payment&view=cancel&gateway=stripe&invoice_id=' . $invoice_id);
    exit;
}
