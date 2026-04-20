<?php

class PaymentsGatewayGateway extends AbstractGateway {

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
        $customer = $this->config['customer'] ?? [];

        return 'https://swp.paymentsgateway.net/co/default.aspx'
            . '?pg_api_login_id='         . urlencode($this->config['api_id'])
            . '&pg_version_number=1.0'
            . '&pg_total_amount='         . urlencode(number_format($amount, 2, '.', ''))
            . '&pg_transaction_order_number=' . urlencode($invoiceRef)
            . '&pg_billto_postal_name_company=' . urlencode($customer['name'] ?? '')
            . '&pg_billto_postal_name_first='   . urlencode($customer['attention'] ?? '')
            . '&pg_billto_postal_name_last=-'
            . '&pg_billto_postal_street_line1=' . urlencode($customer['street_address'] ?? '')
            . '&pg_billto_postal_street_line2=' . urlencode($customer['street_address2'] ?? '')
            . '&pg_billto_postal_city='         . urlencode($customer['city'] ?? '')
            . '&pg_billto_postal_stateprov='    . urlencode($customer['state'] ?? '')
            . '&pg_billto_postal_postalcode='   . urlencode($customer['zip_code'] ?? '')
            . '&pg_billto_telecom_phone_number=' . urlencode($customer['phone'] ?? '')
            . '&pg_billto_online_email='        . urlencode($customer['email'] ?? '')
            . '&pg_consumerorderid='            . urlencode((string) $invoiceId)
            . '&pg_return_url='                 . urlencode($successUrl)
            . '&pg_save_client=2';
    }
}
