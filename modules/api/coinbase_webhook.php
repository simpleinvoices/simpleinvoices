<?php

require_once __DIR__ . '/_payment_gateway_helpers.php';
require_once __DIR__ . '/../../include/class/payment/gateway/AbstractGateway.php';
require_once __DIR__ . '/../../include/class/payment/gateway/CoinbaseCommerceGateway.php';

$biller_id = (int) ($_GET['biller_id'] ?? 0);
$domain_id = (int) ($_GET['domain_id'] ?? 0);
$payload   = file_get_contents('php://input');
$signature = $_SERVER['HTTP_X_CC_WEBHOOK_SIGNATURE'] ?? '';

if (!$payload) {
    http_response_code(200);
    exit('OK');
}

$event = json_decode($payload, true);
if (!$event) {
    http_response_code(400);
    exit;
}

$logger->log('Coinbase Commerce webhook event: ' . ($event['event']['type'] ?? 'unknown'), LegacyLogger::INFO);

if ($biller_id > 0 && $domain_id > 0) {
    $biller = getBiller($biller_id, $domain_id);
    if ($biller && !empty($biller['coinbase_webhook_secret'])) {
        $gw = new CoinbaseCommerceGateway([
            'api_key'        => $biller['coinbase_api_key'],
            'webhook_secret' => $biller['coinbase_webhook_secret'],
        ]);
        if (!$gw->verifyWebhookSignature($payload, $signature)) {
            $logger->log('Coinbase Commerce webhook signature invalid', LegacyLogger::ERROR);
            http_response_code(401);
            exit;
        }
    }
}

$accepted_events = ['charge:confirmed', 'charge:completed'];
$event_type = $event['event']['type'] ?? '';

if (in_array($event_type, $accepted_events, true)) {
    $charge   = $event['event']['data'] ?? [];
    $meta     = $charge['metadata'] ?? [];
    $inv_id   = (int) ($meta['invoice_id'] ?? 0);
    $d_id     = (int) ($meta['domain_id'] ?? $domain_id);
    $charge_id = $charge['code'] ?? '';

    if ($inv_id > 0 && $d_id > 0) {
        $crypto  = $charge['payments'][0] ?? [];
        $amount  = (float) ($crypto['value']['local']['amount'] ?? 0);
        if (!$amount) {
            $amount = (float) ($charge['pricing']['local']['amount'] ?? 0);
        }

        gatewayRecordPayment(
            $inv_id,
            $amount,
            'Coinbase Commerce Charge: ' . $charge_id,
            $charge_id,
            $d_id,
            'Coinbase Commerce'
        );
    } else {
        $logger->log('Coinbase Commerce webhook: missing invoice_id or domain_id in metadata', LegacyLogger::ERROR);
    }
}

http_response_code(200);
echo 'OK';
