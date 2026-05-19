<?php

require_once __DIR__ . '/_payment_gateway_helpers.php';
require_once __DIR__ . '/../../include/class/payment/gateway/AbstractGateway.php';
require_once __DIR__ . '/../../include/class/payment/gateway/AdyenGateway.php';

$payload = file_get_contents('php://input');

if (!$payload) {
    echo '[accepted]';
    exit;
}

$data = json_decode($payload, true);
if (!$data) {
    http_response_code(400);
    exit;
}

foreach ($data['notificationItems'] ?? [] as $wrapper) {
    $item      = $wrapper['NotificationRequestItem'] ?? [];
    $eventCode = $item['eventCode'] ?? '';
    $success   = ($item['success'] ?? '') === 'true';
    $ref       = $item['merchantReference'] ?? '';

    $logger->log('Adyen webhook event: ' . $eventCode . ' ref=' . $ref, LegacyLogger::INFO);

    if ($eventCode !== 'AUTHORISATION' || !$success) {
        continue;
    }

    // merchantReference is encoded as "{domain_id}|{biller_id}|{invoice_ref}"
    $parts      = explode('|', $ref, 3);
    $domain_id  = (int) ($parts[0] ?? 0);
    $biller_id  = (int) ($parts[1] ?? 0);
    $invoice_ref = $parts[2] ?? '';

    if (!$domain_id || !$invoice_ref) {
        $logger->log('Adyen webhook: cannot parse merchantReference: ' . $ref, LegacyLogger::ERROR);
        continue;
    }

    // Optional HMAC verification
    if ($biller_id > 0) {
        $biller = getBiller($biller_id, $domain_id);
        if ($biller && !empty($biller['adyen_hmac_key'])) {
            $gw = new AdyenGateway([
                'api_key'          => $biller['adyen_api_key'],
                'merchant_account' => $biller['adyen_merchant_account'],
                'hmac_key'         => $biller['adyen_hmac_key'],
                'live_prefix'      => $biller['adyen_live_prefix'] ?? '',
                'test_mode'        => !empty($biller['adyen_test_mode']),
            ]);
            $hmacSig = $item['additionalData']['hmacSignature'] ?? '';
            if ($hmacSig && !$gw->verifyWebhookHmac($item, $hmacSig)) {
                $logger->log('Adyen webhook HMAC invalid for ref=' . $ref, LegacyLogger::ERROR);
                continue;
            }
        }
    }

    $sql     = "SELECT id FROM " . TB_PREFIX . "invoices WHERE index_id = :ref AND domain_id = :domain_id LIMIT 1";
    $sth     = dbQuery($sql, ':ref', $invoice_ref, ':domain_id', $domain_id);
    $inv_row = $sth->fetch();
    $inv_id  = (int) ($inv_row['id'] ?? 0);

    if ($inv_id > 0) {
        $amount   = (float) (($item['amount']['value'] ?? 0) / 100);
        $psp_ref  = $item['pspReference'] ?? $ref;

        gatewayRecordPayment(
            $inv_id,
            $amount,
            'Adyen PSP: ' . $psp_ref,
            $psp_ref,
            $domain_id,
            'Adyen'
        );
    } else {
        $logger->log('Adyen webhook: cannot find invoice for ref=' . $invoice_ref . ' domain=' . $domain_id, LegacyLogger::ERROR);
    }
}

echo '[accepted]';
