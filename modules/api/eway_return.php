<?php

require_once __DIR__ . '/_payment_gateway_helpers.php';
require_once __DIR__ . '/../../include/class/payment/gateway/AbstractGateway.php';
require_once __DIR__ . '/../../include/class/payment/gateway/EwayGateway.php';

$invoice_id  = (int) ($_GET['invoice_id'] ?? 0);
$biller_id   = (int) ($_GET['biller_id'] ?? 0);
$domain_id   = (int) ($_GET['domain_id'] ?? 0);
$access_code = $_GET['AccessCode'] ?? '';

if ($invoice_id <= 0 || !$access_code) {
    header('Location: ' . $siUrl . '/index.php?module=invoices&view=manage');
    exit;
}

$biller = getBiller($biller_id, $domain_id);

if (empty($biller['eway_api_key']) || empty($biller['eway_api_password'])) {
    header('Location: ' . $siUrl . '/index.php?module=payment&view=cancel&gateway=eway&invoice_id=' . $invoice_id);
    exit;
}

try {
    $gateway = new EwayGateway([
        'api_key'      => $biller['eway_api_key'],
        'api_password' => $biller['eway_api_password'],
        'test_mode'    => !empty($biller['eway_test_mode']),
    ]);

    $result = $gateway->verifyAccessCode($access_code);

    if (!empty($result['TransactionStatus']) && $result['TransactionStatus'] === true) {
        $trans_id = (string) ($result['TransactionID'] ?? $access_code);
        $amount   = (float) (($result['Payment']['TotalAmount'] ?? 0) / 100);

        gatewayRecordPayment(
            $invoice_id,
            $amount,
            'eWay Transaction: ' . $trans_id,
            $trans_id,
            $domain_id,
            'eWay'
        );

        header('Location: ' . $siUrl . '/index.php?module=payment&view=success&gateway=eway&invoice_id=' . $invoice_id);
        exit;
    }
} catch (Exception $e) {
    $logger->log('eWay return error: ' . $e->getMessage(), LegacyLogger::ERROR);
}

header('Location: ' . $siUrl . '/index.php?module=payment&view=cancel&gateway=eway&invoice_id=' . $invoice_id);
exit;
