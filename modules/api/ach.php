<?php

require_once __DIR__ . '/_payment_gateway_helpers.php';

$logger->log('ACH/PaymentsGateway return called', LegacyLogger::INFO);

if (($_POST['pg_response_code'] ?? '') !== 'A01') {
    $logger->log('ACH validate failed', LegacyLogger::INFO);
    echo 'PaymentsGateway.com payment validation failed.';
    return;
}

$invoice_id = (int) ($_POST['pg_consumerorderid'] ?? $_GET['invoice_id'] ?? 0);
$domain_id  = (int) ($_GET['domain_id'] ?? 0);
$biller_id  = (int) ($_GET['biller_id'] ?? 0);
$amount     = (float) ($_POST['pg_total_amount'] ?? 0);
$trans_id   = (string) ($_POST['pg_transaction_id'] ?? (string) $invoice_id);

if (!$invoice_id) {
    http_response_code(400);
    return;
}

if (!$domain_id) {
    $row = dbQuery(
        "SELECT domain_id FROM " . TB_PREFIX . "invoices WHERE id = :id LIMIT 1",
        ':id', $invoice_id
    )->fetch();
    $domain_id = (int) ($row['domain_id'] ?? 0);
    if (!$domain_id) {
        $logger->log('ACH: cannot resolve domain for invoice_id=' . $invoice_id, LegacyLogger::ERROR);
        return;
    }
}

$data = '';
foreach ($_POST as $key => $value) {
    $data .= "\n$key: $value";
}
$logger->log('ACH Data:' . $data, LegacyLogger::INFO);

gatewayRecordPayment(
    $invoice_id,
    $amount,
    'PaymentsGateway Transaction: ' . $trans_id . $data,
    $trans_id,
    $domain_id,
    'PaymentsGateway'
);

echo 'Payment recorded. Thank you.';
