<?php

class EwayGateway extends AbstractGateway {

    private function apiBase(): string {
        return $this->isTestMode()
            ? 'https://api.sandbox.ewaypayments.com'
            : 'https://api.ewaypayments.com';
    }

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
        $body = [
            'Payment' => [
                'TotalAmount'        => (int) round($amount * 100),
                'InvoiceNumber'      => substr($invoiceRef, 0, 12),
                'InvoiceDescription' => $description ?: 'Invoice ' . $invoiceRef,
                'CurrencyCode'       => strtoupper($currencyCode),
            ],
            'RedirectUrl'     => $successUrl,
            'CancelUrl'       => $cancelUrl,
            'Method'          => 'ProcessPayment',
            'TransactionType' => 'Purchase',
            'Options'         => [
                ['Value' => 'domain_id:' . $domainId . ';invoice_id:' . $invoiceId . ';biller_id:' . $billerId],
            ],
        ];

        $ch = curl_init($this->apiBase() . '/AccessCodesShared.json');
        curl_setopt_array($ch, [
            CURLOPT_POST           => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_USERPWD        => $this->config['api_key'] . ':' . $this->config['api_password'],
            CURLOPT_HTTPHEADER     => ['Content-Type: application/json'],
            CURLOPT_POSTFIELDS     => json_encode($body),
        ]);
        $result = json_decode((string) curl_exec($ch), true);
        curl_close($ch);

        if (empty($result['SharedPaymentUrl'])) {
            throw new RuntimeException('eWay AccessCode request failed: ' . json_encode($result));
        }
        return $result['SharedPaymentUrl'];
    }

    /**
     * Verify a completed access code and return the transaction details.
     */
    public function verifyAccessCode(string $accessCode): array {
        $ch = curl_init($this->apiBase() . '/AccessCode/' . urlencode($accessCode) . '.json');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_USERPWD        => $this->config['api_key'] . ':' . $this->config['api_password'],
        ]);
        $result = json_decode((string) curl_exec($ch), true);
        curl_close($ch);
        return $result ?? [];
    }
}
