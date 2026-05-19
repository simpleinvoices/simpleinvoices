<?php

require_once __DIR__ . '/_payment_gateway_helpers.php';
require_once __DIR__ . '/../../include/class/payment/gateway/AbstractGateway.php';
require_once __DIR__ . '/../../include/class/payment/gateway/PayPalGateway.php';

$biller_id = (int) ($_GET['biller_id'] ?? 0);
$domain_id = (int) ($_GET['domain_id'] ?? 0);
$payload   = file_get_contents('php://input');

if ($biller_id <= 0 || $domain_id <= 0 || !$payload) {
    http_response_code(400);
    exit;
}

$biller = getBiller($biller_id, $domain_id);
if (!$biller || empty($biller['paypal_client_id'])) {
    http_response_code(400);
    exit;
}

$event = json_decode($payload, true);
if (!$event) {
    http_response_code(400);
    exit;
}

$logger->log('PayPal webhook event: ' . ($event['event_type'] ?? 'unknown'), LegacyLogger::INFO);

if (($event['event_type'] ?? '') === 'PAYMENT.CAPTURE.COMPLETED') {
    $resource   = $event['resource'] ?? [];
    $capture_id = $resource['id'] ?? '';
    $amount     = (float) ($resource['amount']['value'] ?? 0);
    $custom_id  = $resource['custom_id'] ?? '';

    $invoice_id = 0;
    if (preg_match('/invoice_id:(\d+)/', $custom_id, $m)) {
        $invoice_id = (int) $m[1];
    } elseif (!empty($resource['invoice_id'])) {
        $invoice_id = (int) $resource['invoice_id'];
    }

    if ($invoice_id > 0 && $capture_id) {
        gatewayRecordPayment(
            $invoice_id,
            $amount,
            'PayPal Capture: ' . $capture_id,
            $capture_id,
            $domain_id,
            'PayPal'
        );
    }
}

http_response_code(200);
echo 'OK';
