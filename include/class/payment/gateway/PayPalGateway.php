<?php

class PayPalGateway extends AbstractGateway {

    private ?string $cachedToken = null;

    private function apiBase(): string {
        return $this->isTestMode()
            ? 'https://api-m.sandbox.paypal.com'
            : 'https://api-m.paypal.com';
    }

    private function getAccessToken(): string {
        if ($this->cachedToken !== null) {
            return $this->cachedToken;
        }
        $ch = curl_init($this->apiBase() . '/v1/oauth2/token');
        curl_setopt_array($ch, [
            CURLOPT_POST           => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_USERPWD        => $this->config['client_id'] . ':' . $this->config['client_secret'],
            CURLOPT_POSTFIELDS     => 'grant_type=client_credentials',
            CURLOPT_HTTPHEADER     => ['Accept: application/json'],
        ]);
        $result = curl_exec($ch);
        curl_close($ch);
        $data = json_decode((string) $result, true);
        if (empty($data['access_token'])) {
            throw new RuntimeException('PayPal OAuth failed: ' . $result);
        }
        $this->cachedToken = $data['access_token'];
        return $this->cachedToken;
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
        $token = $this->getAccessToken();

        $order = [
            'intent'           => 'CAPTURE',
            'purchase_units'   => [[
                'reference_id' => (string) $invoiceId,
                'description'  => $description ?: 'Invoice ' . $invoiceRef,
                'custom_id'    => "domain_id:{$domainId};biller_id:{$billerId}",
                'amount'       => [
                    'currency_code' => strtoupper($currencyCode),
                    'value'         => number_format($amount, 2, '.', ''),
                ],
            ]],
            'application_context' => [
                'return_url' => $successUrl,
                'cancel_url' => $cancelUrl,
                'brand_name' => 'Simple Invoices',
                'user_action' => 'PAY_NOW',
            ],
        ];

        $ch = curl_init($this->apiBase() . '/v2/checkout/orders');
        curl_setopt_array($ch, [
            CURLOPT_POST           => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER     => [
                'Authorization: Bearer ' . $token,
                'Content-Type: application/json',
            ],
            CURLOPT_POSTFIELDS => json_encode($order),
        ]);
        $result = json_decode((string) curl_exec($ch), true);
        curl_close($ch);

        foreach ($result['links'] ?? [] as $link) {
            if ($link['rel'] === 'approve') {
                return $link['href'];
            }
        }
        throw new RuntimeException('PayPal order creation failed: ' . json_encode($result));
    }

    public function captureOrder(string $orderId): array {
        $token = $this->getAccessToken();
        $ch = curl_init($this->apiBase() . '/v2/checkout/orders/' . $orderId . '/capture');
        curl_setopt_array($ch, [
            CURLOPT_POST           => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER     => [
                'Authorization: Bearer ' . $token,
                'Content-Type: application/json',
            ],
            CURLOPT_POSTFIELDS => '{}',
        ]);
        $result = json_decode(curl_exec($ch), true);
        curl_close($ch);
        return $result;
    }

    public function verifyWebhookSignature(string $webhookId, array $headers, string $body): bool {
        if (empty($webhookId)) {
            return true;
        }
        $token = $this->getAccessToken();
        $payload = json_encode([
            'auth_algo'         => $headers['PAYPAL-AUTH-ALGO'] ?? '',
            'cert_url'          => $headers['PAYPAL-CERT-URL'] ?? '',
            'transmission_id'   => $headers['PAYPAL-TRANSMISSION-ID'] ?? '',
            'transmission_sig'  => $headers['PAYPAL-TRANSMISSION-SIG'] ?? '',
            'transmission_time' => $headers['PAYPAL-TRANSMISSION-TIME'] ?? '',
            'webhook_id'        => $webhookId,
            'webhook_event'     => json_decode($body, true),
        ]);
        $ch = curl_init($this->apiBase() . '/v1/notifications/verify-webhook-signature');
        curl_setopt_array($ch, [
            CURLOPT_POST           => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER     => [
                'Authorization: Bearer ' . $token,
                'Content-Type: application/json',
            ],
            CURLOPT_POSTFIELDS => $payload,
        ]);
        $result = json_decode(curl_exec($ch), true);
        curl_close($ch);
        return ($result['verification_status'] ?? '') === 'SUCCESS';
    }
}
