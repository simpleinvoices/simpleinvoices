@php
    $siPaymentMethodText = trim((string) ($methodText ?? ''));
    $siOnlinePayments = array_values(array_filter(array_map('trim', explode(',', (string) ($onlinePayments ?? '')))));
    $siProvider = null;
    $siAdditionalProviders = [];

    $siOnlinePaymentMap = [
        'stripe' => 'stripe',
        'paypal_commerce' => 'paypal',
        'authorizenet' => 'authorize',
        'eway_rapid' => 'eway',
        'adyen' => 'adyen',
    ];

    $siNeedleMap = [
        'paypal' => ['paypal'],
        'stripe' => ['stripe'],
        'adyen' => ['adyen'],
        'authorize' => ['authorize.net', 'authorize net', 'authorizenet', 'authorize'],
        'eway' => ['eway rapid', 'eway', 'e-way'],
        'visa' => ['visa'],
        'mastercard' => ['mastercard', 'master card'],
        'americanexpress' => ['american express', 'amex', 'americanexpress'],
        'applepay' => ['apple pay', 'applepay'],
        'google-pay' => ['google pay', 'gpay', 'googlepay'],
        'shop-pay' => ['shop pay', 'shoppay'],
        'cash-app' => ['cash app', 'cashapp', 'kofi', 'ko-fi'],
        'bitcoin' => ['bitcoin', 'btc', 'coinbase', 'crypto'],
        'ethereum' => ['ethereum', 'eth'],
        'litecoin' => ['litecoin', 'ltc'],
        'klarna' => ['klarna'],
        'skrill' => ['skrill'],
        'braintree' => ['braintree'],
        'square' => ['square'],
        'shopify' => ['shopify'],
        'sage' => ['sage', 'payments gateway'],
        'worldpay' => ['worldpay'],
        'dinersclub' => ['diners club', 'diners'],
        'discover' => ['discover'],
        'jcb' => ['jcb'],
        'unionpay' => ['union pay', 'unionpay'],
        'sepa' => ['sepa'],
        'ideal' => ['ideal', 'mollie'],
        'giropay' => ['giropay'],
        'bancontact' => ['bancontact'],
        'alipay' => ['alipay'],
        'we-chat-pay' => ['wechat', 'we-chat', 'wechatpay'],
        'bitpay' => ['bitpay'],
        'monero' => ['monero', 'xmr'],
        'tether' => ['tether', 'usdt'],
        'solana' => ['solana', 'sol'],
        'ripple' => ['ripple', 'xrp'],
        'metamask' => ['metamask'],
        'binance-usd' => ['binance', 'busd'],
        'venmo' => ['venmo'],
        'zelle' => ['zelle'],
        'wise' => ['wise', 'transferwise'],
        'westernunion' => ['western union', 'westernunion', 'wu'],
        'moneygram' => ['moneygram'],
        'maestro' => ['maestro'],
        'mir' => ['mir'],
        'samsung-pay' => ['samsung pay', 'samsungpay'],
        'verifone' => ['verifone'],
        'revolut-pay' => ['revolut', 'revolutpay'],
        'affirm' => ['affirm', 'afterpay'],
        'paysafe' => ['paysafe', 'paysafecard'],
        'neteller' => ['neteller'],
        'payoneer' => ['payoneer'],
        'payu' => ['payu'],
        'payza' => ['payza'],
        'webmoney' => ['webmoney'],
        'dwolla' => ['dwolla'],
        'paymill' => ['paymill'],
        'payone' => ['payone'],
        'stax' => ['stax'],
        'amazon-pay' => ['amazon pay', 'amazonpay'],
        'googlewallet' => ['google wallet', 'googlewallet'],
    ];

    $siSearchHaystack = strtolower($siPaymentMethodText);
    foreach ($siNeedleMap as $candidate => $needles) {
        foreach ($needles as $needle) {
            if ($needle !== '' && strpos($siSearchHaystack, $needle) !== false) {
                if ($siProvider === null) {
                    $siProvider = $candidate;
                } elseif (!in_array($candidate, $siAdditionalProviders)) {
                    $siAdditionalProviders[] = $candidate;
                }
                break;
            }
        }
    }

    foreach ($siOnlinePayments as $siGateway) {
        if (isset($siOnlinePaymentMap[$siGateway])) {
            $mappedProvider = $siOnlinePaymentMap[$siGateway];
            if ($siProvider === null) {
                $siProvider = $mappedProvider;
            } elseif ($mappedProvider !== $siProvider && !in_array($mappedProvider, $siAdditionalProviders)) {
                $siAdditionalProviders[] = $mappedProvider;
            }
        } elseif ($siGateway !== '' && !in_array($siGateway, $siAdditionalProviders) && $siGateway !== $siProvider) {
            if (isset($siNeedleMap[$siGateway])) {
                $siAdditionalProviders[] = $siGateway;
            }
        }
    }

    $siMethodClasses = trim('si-payment-method ' . ($wrapperClass ?? ''));
    $siShowMultiple = ($showMultiple ?? true);
    $siIsProminent = ($wrapperClass ?? '') === 'si-payment-method-prominent';
    $siMaxAdditional = $siIsProminent ? 10 : 3;
    $siProviderLabels = [
        'paypal' => 'PayPal', 'stripe' => 'Stripe', 'adyen' => 'Adyen',
        'authorize' => 'Authorize.net', 'eway' => 'eWay', 'ideal' => 'iDEAL',
        'sage' => 'Sage', 'cash-app' => 'Cash App', 'bitcoin' => 'Bitcoin',
        'visa' => 'Visa', 'mastercard' => 'Mastercard', 'applepay' => 'Apple Pay',
        'google-pay' => 'Google Pay', 'shop-pay' => 'Shop Pay',
    ];
    $siIconBaseUrl = rtrim(getURL(), '/') . '/templates/invoices/img/payments';
@endphp

@if($siProvider !== null || $siPaymentMethodText !== '')
<span class="{{ $siMethodClasses }}">
    @if($siProvider !== null)
    @if($siIsProminent)
    <span class="si-payment-prominent-item" title="{{ $siPaymentMethodText ?: ($siProviderLabels[$siProvider] ?? $siProvider) }}">
        <img src="{{ $siIconBaseUrl }}/{{ $siProvider }}.svg" alt="{{ $siProviderLabels[$siProvider] ?? ucfirst(str_replace(['-', '_'], ' ', $siProvider)) }}" class="payment si-payment-icon si-payment-provider-{{ $siProvider }}" />
        <span class="si-payment-prominent-label">{{ $siProviderLabels[$siProvider] ?? ucfirst(str_replace(['-', '_'], ' ', $siProvider)) }}</span>
    </span>
    @else
    <img src="{{ $siIconBaseUrl }}/{{ $siProvider }}.svg" alt="{{ $siProviderLabels[$siProvider] ?? $siProvider }}" class="payment si-payment-icon si-payment-provider-{{ $siProvider }}" title="{{ $siPaymentMethodText ?: $siProvider }}" />
    @endif
    @endif
    @if($siShowMultiple && !empty($siAdditionalProviders))
        @foreach(array_slice($siAdditionalProviders, 0, $siMaxAdditional) as $additionalProvider)
        @if($siIsProminent)
        <span class="si-payment-prominent-item" title="{{ $siProviderLabels[$additionalProvider] ?? ucfirst(str_replace(['-', '_'], ' ', $additionalProvider)) }}">
            <img src="{{ $siIconBaseUrl }}/{{ $additionalProvider }}.svg" alt="{{ $siProviderLabels[$additionalProvider] ?? ucfirst(str_replace(['-', '_'], ' ', $additionalProvider)) }}" class="payment si-payment-icon si-payment-provider-{{ $additionalProvider }}" />
            <span class="si-payment-prominent-label">{{ $siProviderLabels[$additionalProvider] ?? ucfirst(str_replace(['-', '_'], ' ', $additionalProvider)) }}</span>
        </span>
        @else
        <img src="{{ $siIconBaseUrl }}/{{ $additionalProvider }}.svg" alt="{{ $siProviderLabels[$additionalProvider] ?? $additionalProvider }}" class="payment si-payment-icon si-payment-provider-{{ $additionalProvider }}" />
        @endif
        @endforeach
    @endif
    @if($siPaymentMethodText !== '' && !$siIsProminent)
    <span>{{ $siPaymentMethodText }}</span>
    @endif
</span>
@endif
