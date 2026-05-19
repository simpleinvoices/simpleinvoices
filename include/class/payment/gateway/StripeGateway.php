<?php

use Stripe\Stripe;
use Stripe\Checkout\Session;

class StripeGateway extends AbstractGateway {

    public function createCheckoutSession(
        int    $invoiceId,
        string $invoiceRef,
        float  $amount,
        string $currencyCode,
        string $successUrl,
        string $cancelUrl,
        string $webhookUrl,
        int    $domainId,
        int    $billerId,
        string $description = ''
    ): string {
        Stripe::setApiKey($this->config['secret_key']);

        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency'     => strtolower($currencyCode),
                    'unit_amount'  => (int) round($amount * 100),
                    'product_data' => ['name' => $description ?: 'Invoice ' . $invoiceRef],
                ],
                'quantity' => 1,
            ]],
            'mode'        => 'payment',
            'success_url' => $successUrl . '&session_id={CHECKOUT_SESSION_ID}',
            'cancel_url'  => $cancelUrl,
            'metadata'    => [
                'invoice_id'  => $invoiceId,
                'invoice_ref' => $invoiceRef,
                'domain_id'   => $domainId,
                'biller_id'   => $billerId,
            ],
        ]);

        return $session->url;
    }
}
