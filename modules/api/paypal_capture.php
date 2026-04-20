<?php

require_once __DIR__ . '/_payment_gateway_helpers.php';
require_once __DIR__ . '/../../include/class/payment/gateway/AbstractGateway.php';
require_once __DIR__ . '/../../include/class/payment/gateway/PayPalGateway.php';

// PayPal redirects here after customer approves the order (?token=ORDER_ID&PayerID=xxx)
$order_id   = $_GET['token'] ?? '';
$invoice_id = (int) ($_GET['invoice_id'] ?? 0);
$biller_id  = (int) ($_GET['biller_id'] ?? 0);
$domain_id  = (int) ($_GET['domain_id'] ?? 0);

if (!$order_id || $invoice_id <= 0 || $biller_id <= 0 || $domain_id <= 0) {
    header('Location: ' . $siUrl . '/index.php?module=payment&view=cancel&gateway=paypal&invoice_id=' . $invoice_id);
    exit;
}

$biller = getBiller($biller_id, $domain_id);
if (!$biller) {
    header('Location: ' . $siUrl . '/index.php?module=payment&view=cancel&gateway=paypal&invoice_id=' . $invoice_id);
    exit;
}

try {
    $gateway = new PayPalGateway([
        'client_id'     => $biller['paypal_client_id'],
        'client_secret' => $biller['paypal_client_secret'],
        'test_mode'     => !empty($biller['paypal_test_mode']),
    ]);

    $capture = $gateway->captureOrder($order_id);

    if (($capture['status'] ?? '') !== 'COMPLETED') {
        $logger->log('PayPal capture not completed: ' . json_encode($capture), LegacyLogger::ERROR);
        header('Location: ' . $siUrl . '/index.php?module=payment&view=cancel&gateway=paypal&invoice_id=' . $invoice_id);
        exit;
    }

    $capture_id = $capture['purchase_units'][0]['payments']['captures'][0]['id'] ?? $order_id;
    $amount     = (float) ($capture['purchase_units'][0]['payments']['captures'][0]['amount']['value'] ?? 0);

    gatewayRecordPayment(
        $invoice_id,
        $amount,
        'PayPal Order: ' . $order_id . ', Capture: ' . $capture_id,
        $capture_id,
        $domain_id,
        'PayPal'
    );

    header('Location: ' . $siUrl . '/index.php?module=payment&view=success&gateway=paypal&invoice_id=' . $invoice_id);
    exit;

} catch (Exception $e) {
    $logger->log('PayPal capture error: ' . $e->getMessage(), LegacyLogger::ERROR);
    header('Location: ' . $siUrl . '/index.php?module=payment&view=cancel&gateway=paypal&invoice_id=' . $invoice_id);
    exit;
}
