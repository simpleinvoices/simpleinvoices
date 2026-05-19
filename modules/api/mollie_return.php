<?php

require_once __DIR__ . '/_payment_gateway_helpers.php';
require_once __DIR__ . '/../../include/class/payment/gateway/AbstractGateway.php';
require_once __DIR__ . '/../../include/class/payment/gateway/MollieGateway.php';

$invoice_id = (int) ($_GET['invoice_id'] ?? 0);
$domain_id  = (int) ($_GET['domain_id'] ?? 0);
$payment_id = $_GET['id'] ?? '';

if ($invoice_id <= 0) {
    header('Location: ' . $siUrl . '/index.php?module=invoices&view=manage');
    exit;
}

$invoiceObj = new invoice();
$inv = $invoiceObj->select($invoice_id);
if (!$inv) {
    header('Location: ' . $siUrl . '/index.php?module=invoices&view=manage');
    exit;
}

$biller = getBiller($inv['biller_id'], $inv['domain_id']);

if ($payment_id && !empty($biller['mollie_api_key'])) {
    try {
        $gateway       = new MollieGateway(['api_key' => $biller['mollie_api_key']]);
        $molliePayment = $gateway->getPayment($payment_id);

        if ($molliePayment->isPaid()) {
            gatewayRecordPayment(
                $invoice_id,
                (float) $molliePayment->amount->value,
                'Mollie Payment: ' . $molliePayment->id,
                $molliePayment->id,
                (int) $inv['domain_id'],
                'Mollie'
            );

            header('Location: ' . $siUrl . '/index.php?module=payment&view=success&gateway=mollie&invoice_id=' . $invoice_id);
            exit;
        }
    } catch (Exception $e) {
        $logger->log('Mollie return check error: ' . $e->getMessage(), LegacyLogger::ERROR);
    }
}

header('Location: ' . $siUrl . '/index.php?module=payment&view=cancel&gateway=mollie&invoice_id=' . $invoice_id);
exit;
