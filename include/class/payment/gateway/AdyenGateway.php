<?php

class AdyenGateway extends AbstractGateway {

    private function apiBase(): string {
        if ($this->isTestMode()) {
            return 'https://checkout-test.adyen.com/v71';
        }
        $prefix = $this->config['live_prefix'] ?? '';
        if (!$prefix) {
            throw new RuntimeException('adyen_live_prefix must be set for live mode');
        }
        return 'https://' . $prefix . '-checkout-live.adyenpayments.com/v71';
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
        // Encode routing info in merchantReference so webhooks can look up the invoice
        $body = [
            'merchantAccount' => $this->config['merchant_account'],
            'reference'       => $domainId . '|' . $billerId . '|' . $invoiceRef,
            'amount'          => [
                'value'    => (int) round($amount * 100),
                'currency' => strtoupper($currencyCode),
            ],
            'description'     => $description ?: 'Invoice ' . $invoiceRef,
            'returnUrl'       => $successUrl,
        ];

        $ch = curl_init($this->apiBase() . '/paymentLinks');
        curl_setopt_array($ch, [
            CURLOPT_POST           => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER     => [
                'X-API-Key: ' . $this->config['api_key'],
                'Content-Type: application/json',
            ],
            CURLOPT_POSTFIELDS => json_encode($body),
        ]);
        $result = json_decode((string) curl_exec($ch), true);
        curl_close($ch);

        if (empty($result['url'])) {
            throw new RuntimeException('Adyen paymentLinks failed: ' . json_encode($result));
        }
        return $result['url'];
    }

    public function verifyWebhookHmac(array $item, string $hmacSignature): bool {
        $hmacKey = $this->config['hmac_key'] ?? '';
        if (!$hmacKey) {
            return true;
        }
        $fields = [
            $item['pspReference'] ?? '',
            $item['originalReference'] ?? '',
            $item['merchantAccountCode'] ?? '',
            $item['merchantReference'] ?? '',
            (string) ($item['amount']['value'] ?? ''),
            $item['amount']['currency'] ?? '',
            $item['eventCode'] ?? '',
            $item['success'] ?? '',
        ];
        $expected = base64_encode(hash_hmac('sha256', implode(':', $fields), hex2bin($hmacKey), true));
        return hash_equals($expected, $hmacSignature);
    }
}
