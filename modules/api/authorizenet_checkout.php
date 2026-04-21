<?php

require_once __DIR__ . '/_payment_gateway_helpers.php';
require_once __DIR__ . '/../../include/class/payment/gateway/AbstractGateway.php';
require_once __DIR__ . '/../../include/class/payment/gateway/AuthorizeNetGateway.php';

$invoice_id = (int) ($_GET['invoice_id'] ?? 0);
$inv        = gatewayValidateInvoice($invoice_id, $siUrl);
$biller     = getBiller($inv['biller_id'], $inv['domain_id']);

if (empty($biller['authorizenet_login_id']) || empty($biller['authorizenet_transaction_key'])) {
    header('Location: ' . $siUrl . '/index.php?module=invoices&view=manage&payment_error=config');
    exit;
}

$successUrl = $siUrl . '/index.php?module=payment&view=success&gateway=authorizenet&invoice_id=' . $invoice_id;
$cancelUrl  = $siUrl . '/index.php?module=payment&view=cancel&gateway=authorizenet&invoice_id=' . $invoice_id;

try {
    $gateway = new AuthorizeNetGateway([
        'login_id'        => $biller['authorizenet_login_id'],
        'transaction_key' => $biller['authorizenet_transaction_key'],
        'signature_key'   => $biller['authorizenet_signature_key'] ?? '',
        'test_mode'       => !empty($biller['authorizenet_test_mode']),
    ]);

    $token     = $gateway->getHostedPageToken($invoice_id, (string) $inv['index_id'], (float) $inv['owing'], $successUrl, $cancelUrl);
    $hostedUrl = $gateway->getHostedPageBaseUrl();

    // Authorize.net Accept Hosted requires a form POST - output an auto-submit page.
    echo '<!DOCTYPE html><html><head><title>Redirecting to payment...</title></head><body>';
    echo '<form id="anet_form" method="POST" action="' . htmlspecialchars($hostedUrl, ENT_QUOTES) . '">';
    echo '<input type="hidden" name="token" value="' . htmlspecialchars($token, ENT_QUOTES) . '" />';
    echo '</form>';
    echo '<script>document.getElementById("anet_form").submit();</script>';
    echo '<noscript><p>Please <a href="#" onclick="document.getElementById(\'anet_form\').submit();">click here</a> to continue to payment.</p></noscript>';
    echo '</body></html>';
    exit;

} catch (Exception $e) {
    $logger->log('Authorize.net checkout error: ' . $e->getMessage(), LegacyLogger::ERROR);
    header('Location: ' . $siUrl . '/index.php?module=payment&view=cancel&gateway=authorizenet&invoice_id=' . $invoice_id);
    exit;
}
