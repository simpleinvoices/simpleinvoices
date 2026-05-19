<?php

use Mollie\Api\MollieApiClient;

class MollieGateway extends AbstractGateway {

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
        $mollie = new MollieApiClient();
        $mollie->setApiKey($this->config['api_key']);

        $params = [
            'amount'      => [
                'currency' => strtoupper($currencyCode),
                'value'    => number_format($amount, 2, '.', ''),
            ],
            'description' => $description ?: 'Invoice ' . $invoiceRef,
            'redirectUrl' => $successUrl,
            'metadata'    => [
                'invoice_id'  => $invoiceId,
                'invoice_ref' => $invoiceRef,
                'domain_id'   => $domainId,
                'biller_id'   => $billerId,
            ],
        ];

        if (!empty($webhookUrl)) {
            $params['webhookUrl'] = $webhookUrl;
        }

        $payment = $mollie->payments->create($params);
        return $payment->getCheckoutUrl();
    }

    public function getPayment(string $paymentId): ?\Mollie\Api\Resources\Payment {
        $mollie = new MollieApiClient();
        $mollie->setApiKey($this->config['api_key']);
        return $mollie->payments->get($paymentId);
    }
}
