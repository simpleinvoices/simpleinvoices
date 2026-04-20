<?php

class KofiGateway extends AbstractGateway {

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
        $username = $this->config['username'] ?? '';
        if (!$username) {
            throw new RuntimeException('Ko-fi username not configured');
        }
        return 'https://ko-fi.com/' . urlencode($username);
    }
}
