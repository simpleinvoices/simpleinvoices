<?php

require_once __DIR__ . '/_payment_gateway_helpers.php';
require_once __DIR__ . '/../../include/class/payment/gateway/AbstractGateway.php';
require_once __DIR__ . '/../../include/class/payment/gateway/PaymentsGatewayGateway.php';

$invoice_id = (int) ($_GET['invoice_id'] ?? 0);
$inv        = gatewayValidateInvoice($invoice_id, $siUrl);
$biller     = getBiller($inv['biller_id'], $inv['domain_id']);

if (empty($biller['paymentsgateway_api_id'])) {
    header('Location: ' . $siUrl . '/index.php?module=invoices&view=manage&payment_error=config');
    exit;
}

$invoiceObj = new invoice();
$inv_full   = $invoiceObj->select($invoice_id);
$customer   = [];
if (!empty($inv_full['customer_id'])) {
    $customerObj = new customer();
    $c = $customerObj->select((int) $inv_full['customer_id']);
    if ($c) {
        $customer = [
            'name'            => $c['name'] ?? '',
            'attention'       => $c['attention'] ?? '',
            'street_address'  => $c['street_address'] ?? '',
            'street_address2' => $c['street_address2'] ?? '',
            'city'            => $c['city'] ?? '',
            'state'           => $c['state'] ?? '',
            'zip_code'        => $c['zip_code'] ?? '',
            'phone'           => $c['phone'] ?? '',
            'email'           => $c['email'] ?? '',
        ];
    }
}

$successUrl = $siUrl . '/index.php?module=api&view=ach&invoice_id=' . $invoice_id
    . '&biller_id=' . (int) $biller['id'] . '&domain_id=' . (int) $inv['domain_id'];

$pref = getPreference($inv['preference_id'], $inv['domain_id']);

try {
    $gateway = new PaymentsGatewayGateway([
        'api_id'   => $biller['paymentsgateway_api_id'],
        'customer' => $customer,
    ]);

    $checkoutUrl = $gateway->createCheckoutSession(
        $invoice_id,
        (string) $inv['index_id'],
        (float) $inv['owing'],
        (string) ($inv['currency_code'] ?? $pref['currency_code'] ?? 'USD'),
        $successUrl,
        '',
        '',
        (int) $inv['domain_id'],
        (int) $biller['id']
    );

    header('Location: ' . $checkoutUrl);
    exit;

} catch (Exception $e) {
    $logger->log('PaymentsGateway checkout error: ' . $e->getMessage(), LegacyLogger::ERROR);
    header('Location: ' . $siUrl . '/index.php?module=payment&view=cancel&gateway=paymentsgateway&invoice_id=' . $invoice_id);
    exit;
}
