<?php

require_once __DIR__ . '/_payment_gateway_helpers.php';
require_once __DIR__ . '/../../include/class/payment/gateway/AbstractGateway.php';
require_once __DIR__ . '/../../include/class/payment/gateway/KofiGateway.php';

$invoice_id = (int) ($_GET['invoice_id'] ?? 0);

$invoiceObj = new invoice();
$inv = $invoice_id > 0 ? $invoiceObj->select($invoice_id) : null;
if (!$inv) {
    header('Location: ' . $siUrl . '/index.php?module=invoices&view=manage');
    exit;
}

$biller = getBiller((int) $inv['biller_id'], (int) $inv['domain_id']);

if (empty($biller['kofi_username'])) {
    header('Location: ' . $siUrl . '/index.php?module=invoices&view=manage&payment_error=config');
    exit;
}

try {
    $gateway = new KofiGateway(['username' => $biller['kofi_username']]);
    header('Location: ' . $gateway->createCheckoutSession(
        $invoice_id, '', 0, '', '', '', '', (int) $inv['domain_id'], (int) $biller['id']
    ));
    exit;
} catch (Exception $e) {
    $logger->log('Ko-fi redirect error: ' . $e->getMessage(), LegacyLogger::ERROR);
    header('Location: ' . $siUrl . '/index.php?module=invoices&view=manage');
    exit;
}
