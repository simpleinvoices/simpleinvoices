<?php

abstract class AbstractGateway {

    protected array $config;

    public function __construct(array $config) {
        $this->config = $config;
    }

    /**
     * Create a hosted checkout session and return the redirect URL.
     *
     * @return string URL to redirect the customer to
     */
    abstract public function createCheckoutSession(
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
    ): string;

    protected function isTestMode(): bool {
        return !empty($this->config['test_mode']);
    }
}
