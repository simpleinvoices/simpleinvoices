<?php

/**
 * Shared helpers for payment gateway checkout and webhook modules.
 */

/**
 * Validate a checkout request: invoice exists, is published, and has an outstanding balance.
 * Redirects and exits on failure.
 *
 * @return array Validated invoice array
 */
function gatewayValidateInvoice(int $invoiceId, string $siUrl): array {
    if ($invoiceId <= 0) {
        header('Location: ' . $siUrl . '/index.php?module=invoices&view=manage&payment_error=invalid');
        exit;
    }

    $invoiceObj = new invoice();
    $inv = $invoiceObj->select($invoiceId);

    if (!$inv || (float) ($inv['owing'] ?? 0) <= 0 || (int) ($inv['status'] ?? 0) !== 1) {
        header('Location: ' . $siUrl . '/index.php?module=invoices&view=manage&payment_error=invalid');
        exit;
    }

    return $inv;
}

/**
 * Record an online payment and notify the biller by email.
 * Silently skips if the payment has already been recorded (idempotent).
 */
function gatewayRecordPayment(
    int    $invoiceId,
    float  $amount,
    string $notes,
    string $onlinePaymentId,
    int    $domainId,
    string $paymentTypeName
): void {
    global $logger;

    $check = new payment();
    $check->filter = 'online_payment_id';
    $check->online_payment_id = $onlinePaymentId;
    $check->domain_id = $domainId;
    if ($check->count() > 0) {
        return;
    }

    $pt = new payment_type();
    $pt->type = $paymentTypeName;
    $pt->domain_id = $domainId;

    $pay = new payment();
    $pay->ac_inv_id         = $invoiceId;
    $pay->ac_amount         = $amount;
    $pay->ac_notes          = $notes;
    $pay->ac_date           = date('Y-m-d');
    $pay->online_payment_id = $onlinePaymentId;
    $pay->domain_id         = $domainId;
    $pay->ac_payment_type   = $pt->select_or_insert_where();
    $pay->insert();

    $logger->log($paymentTypeName . ' payment recorded: invoice=' . $invoiceId . ' id=' . $onlinePaymentId, LegacyLogger::INFO);

    // Notify biller
    try {
        $invoiceObj = new invoice();
        $invoice    = $invoiceObj->select($invoiceId);
        if ($invoice) {
            $biller = getBiller($invoice['biller_id'], $domainId);
            if ($biller && !empty($biller['email'])) {
                $email = new email();
                $email->to      = $biller['email'];
                $email->from    = 'simpleinvoices@localhost.localdomain';
                $email->subject = $paymentTypeName . ' Payment Received - Invoice #' . ($invoice['index_id'] ?? $invoiceId);
                $email->notes   = "A {$paymentTypeName} payment was recorded in Simple Invoices.\n\n"
                    . "Invoice: " . ($invoice['index_id'] ?? $invoiceId) . "\n"
                    . "Amount: " . number_format($amount, 2) . "\n"
                    . "Reference: " . $onlinePaymentId . "\n"
                    . "Date: " . date('Y-m-d');
                $email->send();
            }
        }
    } catch (Exception $e) {
        $logger->log('Payment email notification failed: ' . $e->getMessage(), LegacyLogger::ERROR);
    }
}
