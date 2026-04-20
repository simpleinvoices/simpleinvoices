<?php

class CoinbaseCommerceGateway extends AbstractGateway {

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
            'name'         => 'Invoice ' . $invoiceRef,
            'description'  => $description ?: 'Invoice ' . $invoiceRef,
            'pricing_type' => 'fixed_price',
            'local_price'  => [
                'amount'   => number_format($amount, 2, '.', ''),
                'currency' => strtoupper($currencyCode),
            ],
            'metadata'     => [
                'invoice_id' => (string) $invoiceId,
                'domain_id'  => (string) $domainId,
                'biller_id'  => (string) $billerId,
            ],
            'redirect_url' => $successUrl,
            'cancel_url'   => $cancelUrl,
        ];

        $ch = curl_init('https://api.commerce.coinbase.com/charges');
        curl_setopt_array($ch, [
            CURLOPT_POST           => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER     => [
                'X-CC-Api-Key: ' . $this->config['api_key'],
                'Content-Type: application/json',
                'Accept: application/json',
            ],
            CURLOPT_POSTFIELDS => json_encode($body),
        ]);
        $result = json_decode((string) curl_exec($ch), true);
        curl_close($ch);

        if (empty($result['data']['hosted_url'])) {
            throw new RuntimeException('Coinbase Commerce charge creation failed: ' . json_encode($result));
        }
        return $result['data']['hosted_url'];
    }

    public function verifyWebhookSignature(string $body, string $signature): bool {
        $secret = $this->config['webhook_secret'] ?? '';
        if (!$secret) {
            return true;
        }
        return hash_equals(hash_hmac('sha256', $body, $secret), $signature);
    }
}
