<?php

class AuthorizeNetGateway extends AbstractGateway {

    private function apiUrl(): string {
        return $this->isTestMode()
            ? 'https://apitest.authorize.net/xml/v1/request.api'
            : 'https://api.authorize.net/xml/v1/request.api';
    }

    private function hostedPageUrl(): string {
        return $this->isTestMode()
            ? 'https://test.authorize.net/payment/payment'
            : 'https://accept.authorize.net/payment/payment';
    }

    public function getHostedPageToken(
        int    $invoiceId,
        string $invoiceRef,
        float  $amount,
        string $successUrl,
        string $cancelUrl
    ): string {
        $request = [
            'getHostedPaymentPageRequest' => [
                'merchantAuthentication' => [
                    'name'           => $this->config['login_id'],
                    'transactionKey' => $this->config['transaction_key'],
                ],
                'transactionRequest' => [
                    'transactionType' => 'authCaptureTransaction',
                    'amount'          => number_format($amount, 2, '.', ''),
                    'order'           => [
                        'invoiceNumber' => substr($invoiceRef, 0, 20),
                        'description'   => 'Invoice ' . $invoiceRef,
                    ],
                ],
                'hostedPaymentSettings' => [
                    'setting' => [
                        [
                            'settingName'  => 'hostedPaymentReturnOptions',
                            'settingValue' => json_encode([
                                'showReceipt' => false,
                                'url'         => $successUrl,
                                'urlText'     => 'Continue',
                                'cancelUrl'   => $cancelUrl,
                                'cancelUrlText' => 'Cancel',
                            ]),
                        ],
                        [
                            'settingName'  => 'hostedPaymentOrderOptions',
                            'settingValue' => json_encode([
                                'show' => false,
                            ]),
                        ],
                        [
                            'settingName'  => 'hostedPaymentCustomerOptions',
                            'settingValue' => json_encode([
                                'showEmail'    => false,
                                'requiredEmail' => false,
                            ]),
                        ],
                        [
                            'settingName'  => 'hostedPaymentStyleOptions',
                            'settingValue' => json_encode([
                                'bgColor' => 'white',
                            ]),
                        ],
                    ],
                ],
            ],
        ];

        $ch = curl_init($this->apiUrl());
        curl_setopt_array($ch, [
            CURLOPT_POST           => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER     => ['Content-Type: application/json'],
            CURLOPT_POSTFIELDS     => json_encode($request),
        ]);
        $result = curl_exec($ch);
        curl_close($ch);

        // Strip UTF-8 BOM that Authorize.net sometimes prepends
        $result = ltrim($result, "\xEF\xBB\xBF");
        $data = json_decode($result, true);

        if (empty($data['token'])) {
            throw new RuntimeException('Authorize.net token request failed: ' . $result);
        }
        return $data['token'];
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
        return $this->hostedPageUrl();
    }

    public function getHostedPageBaseUrl(): string {
        return $this->hostedPageUrl();
    }

    public function verifyWebhookSignature(string $body, string $signatureHeader): bool {
        if (empty($this->config['signature_key'])) {
            return true;
        }
        $expected = strtoupper(hash_hmac('sha512', $body, $this->config['signature_key']));
        $received = strtoupper(str_replace('sha512=', '', $signatureHeader));
        return hash_equals($expected, $received);
    }
}
