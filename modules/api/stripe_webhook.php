<?php

require_once __DIR__ . '/_payment_gateway_helpers.php';
require_once __DIR__ . '/../../include/class/payment/gateway/AbstractGateway.php';
require_once __DIR__ . '/../../include/class/payment/gateway/StripeGateway.php';

$biller_id = (int) ($_GET['biller_id'] ?? 0);
$domain_id = (int) ($_GET['domain_id'] ?? 0);
$payload   = file_get_contents('php://input');
$sig       = $_SERVER['HTTP_STRIPE_SIGNATURE'] ?? '';

if ($biller_id <= 0 || $domain_id <= 0) {
    http_response_code(400);
    exit('Missing biller_id or domain_id');
}

$biller = getBiller($biller_id, $domain_id);
if (!$biller || (int) ($biller['domain_id'] ?? 0) !== $domain_id || empty($biller['stripe_secret_key'])) {
    http_response_code(400);
    exit('Invalid biller or Stripe not configured');
}

try {
    \Stripe\Stripe::setApiKey($biller['stripe_secret_key']);
    $event = \Stripe\Webhook::constructEvent($payload, $sig, (string) ($biller['stripe_webhook_secret'] ?? ''));
} catch (\Stripe\Exception\SignatureVerificationException $e) {
    $logger->log('Stripe webhook signature failed: ' . $e->getMessage(), LegacyLogger::ERROR);
    http_response_code(400);
    exit('Invalid signature');
} catch (Exception $e) {
    $logger->log('Stripe webhook error: ' . $e->getMessage(), LegacyLogger::ERROR);
    http_response_code(400);
    exit;
}

if ($event->type === 'checkout.session.completed') {
    $session        = $event->data->object;
    $invoice_id     = (int) ($session->metadata->invoice_id ?? 0);
    $meta_domain    = (int) ($session->metadata->domain_id ?? $domain_id);
    $payment_intent = (string) ($session->payment_intent ?? $session->id);

    if ($invoice_id > 0) {
        gatewayRecordPayment(
            $invoice_id,
            $session->amount_total / 100,
            'Stripe Payment Intent: ' . $payment_intent,
            $payment_intent,
            $meta_domain,
            'Stripe'
        );
    }
}

http_response_code(200);
echo 'OK';
