<?php

require_once __DIR__ . '/_payment_gateway_helpers.php';
require_once __DIR__ . '/../../include/class/payment/gateway/AbstractGateway.php';
require_once __DIR__ . '/../../include/class/payment/gateway/MollieGateway.php';

$payment_id = $_POST['id'] ?? '';
$biller_id  = (int) ($_GET['biller_id'] ?? 0);
$domain_id  = (int) ($_GET['domain_id'] ?? 0);

if (!$payment_id) {
    http_response_code(200);
    exit('OK');
}

$logger->log('Mollie webhook called for payment: ' . $payment_id, LegacyLogger::INFO);

if ($biller_id <= 0 || $domain_id <= 0) {
    http_response_code(400);
    exit('Missing biller_id or domain_id');
}

$biller = getBiller($biller_id, $domain_id);
if (!$biller || empty($biller['mollie_api_key'])) {
    http_response_code(400);
    exit('Biller not found or Mollie not configured');
}

try {
    $gateway       = new MollieGateway(['api_key' => $biller['mollie_api_key']]);
    $molliePayment = $gateway->getPayment($payment_id);
} catch (Exception $e) {
    $logger->log('Mollie webhook fetch error: ' . $e->getMessage(), LegacyLogger::ERROR);
    http_response_code(200);
    exit('OK');
}

if (!$molliePayment->isPaid()) {
    http_response_code(200);
    exit('OK');
}

$meta       = $molliePayment->metadata ?? null;
$invoice_id = (int) ($meta->invoice_id ?? 0);

if ($invoice_id > 0) {
    gatewayRecordPayment(
        $invoice_id,
        (float) $molliePayment->amount->value,
        'Mollie Payment: ' . $molliePayment->id . ' (' . $molliePayment->method . ')',
        $molliePayment->id,
        $domain_id,
        'Mollie'
    );
}

http_response_code(200);
echo 'OK';
