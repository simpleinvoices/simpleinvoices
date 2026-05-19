<?php

require_once __DIR__ . '/_payment_gateway_helpers.php';
require_once __DIR__ . '/../../include/class/payment/gateway/AbstractGateway.php';
require_once __DIR__ . '/../../include/class/payment/gateway/AuthorizeNetGateway.php';

$biller_id = (int) ($_GET['biller_id'] ?? 0);
$domain_id = (int) ($_GET['domain_id'] ?? 0);
$payload   = file_get_contents('php://input');
$sig       = $_SERVER['HTTP_X_ANET_SIGNATURE'] ?? '';

if (!$payload) {
    http_response_code(200);
    exit('OK');
}

$event = json_decode($payload, true);
if (!$event) {
    http_response_code(400);
    exit;
}

$logger->log('Authorize.net webhook event: ' . ($event['eventType'] ?? 'unknown'), LegacyLogger::INFO);

if ($biller_id > 0 && $domain_id > 0) {
    $biller = getBiller($biller_id, $domain_id);
    if ($biller && !empty($biller['authorizenet_signature_key'])) {
        $gw = new AuthorizeNetGateway([
            'login_id'        => $biller['authorizenet_login_id'],
            'transaction_key' => $biller['authorizenet_transaction_key'],
            'signature_key'   => $biller['authorizenet_signature_key'],
            'test_mode'       => !empty($biller['authorizenet_test_mode']),
        ]);
        if (!$gw->verifyWebhookSignature($payload, $sig)) {
            $logger->log('Authorize.net webhook signature invalid', LegacyLogger::ERROR);
            http_response_code(401);
            exit;
        }
    }
}

$accepted_events = ['net.authorize.payment.authcapture.created', 'net.authorize.payment.capture.created'];
if (in_array($event['eventType'] ?? '', $accepted_events, true)) {
    $payload_data = $event['payload'] ?? [];
    $trans_id     = (string) ($payload_data['id'] ?? '');
    $amount       = (float) ($payload_data['authAmount'] ?? 0);
    $invoice_ref  = (string) ($payload_data['invoiceNumber'] ?? '');

    if (!$trans_id || !$invoice_ref || $domain_id <= 0) {
        http_response_code(200);
        exit('OK');
    }

    $sql = "SELECT id FROM " . TB_PREFIX . "invoices WHERE index_id = :ref AND domain_id = :domain_id LIMIT 1";
    $sth = dbQuery($sql, ':ref', $invoice_ref, ':domain_id', $domain_id);
    $inv_row    = $sth->fetch();
    $invoice_id = (int) ($inv_row['id'] ?? 0);

    if ($invoice_id > 0) {
        gatewayRecordPayment(
            $invoice_id,
            $amount,
            'Authorize.net Transaction: ' . $trans_id,
            $trans_id,
            $domain_id,
            'Authorize.net'
        );
    } else {
        $logger->log('Authorize.net webhook: cannot find invoice for ref=' . $invoice_ref, LegacyLogger::ERROR);
    }
}

http_response_code(200);
echo 'OK';
