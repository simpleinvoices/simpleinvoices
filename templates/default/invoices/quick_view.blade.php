{{-- Invoice Quick View --}}
@php
    $currency = CurrencySignHelper::forDisplay($invoice['currency_sign'] ?? $preference['pref_currency_sign'] ?? '');
@endphp

<div class="card mb-3">

    <div class="card-body">

    {{-- Action buttons: segmented control --}}
    <div class="mb-4 d-flex justify-content-center">
        <div class="segmented-control segmented-control-btn">
            <label class="segmented-control-item" title="{{ $LANG['print_preview_tooltip'] ?? '' }}" onclick="siPreviewModal('index.php?module=export&amp;view=invoice&amp;id={{ urlencode($invoice['id'] ?? '') }}&amp;format=print','{{ addslashes($LANG['print_preview'] ?? '') }}','index.php?module=export&amp;view=invoice&amp;id={{ urlencode($invoice['id'] ?? '') }}&amp;format=pdf')">
                <input type="radio" class="segmented-control-input">
                <span class="segmented-control-label"><i class="ti ti-printer me-md-1"></i><span class="d-none d-md-inline">{{ $LANG['print_preview'] ?? '' }}</span></span>
            </label>
            <label class="segmented-control-item" title="{{ $LANG['edit'] ?? '' }}" onclick="window.location='index.php?module=invoices&amp;view=details&amp;id={{ urlencode($invoice['id'] ?? '') }}&amp;action=view'">
                <input type="radio" class="segmented-control-input">
                <span class="segmented-control-label"><i class="ti ti-edit me-md-1"></i><span class="d-none d-md-inline">{{ $LANG['edit'] ?? '' }}</span></span>
            </label>
            <label class="segmented-control-item" title="{{ $LANG['process_payment'] ?? '' }}" onclick="window.location='index.php?module=payments&amp;view=process&amp;id={{ urlencode($invoice['id'] ?? '') }}&amp;op=pay_selected_invoice'">
                <input type="radio" class="segmented-control-input">
                <span class="segmented-control-label"><i class="ti ti-cash me-md-1"></i><span class="d-none d-md-inline">{{ $LANG['process_payment'] ?? '' }}</span></span>
            </label>
            @if(($eway_pre_check ?? '') == 'true')
            <label class="segmented-control-item" title="{{ $LANG['process_payment_via_eway'] ?? '' }}" onclick="window.location='index.php?module=payments&amp;view=eway&amp;id={{ urlencode($invoice['id'] ?? '') }}'">
                <input type="radio" class="segmented-control-input">
                <span class="segmented-control-label"><i class="ti ti-cash me-md-1"></i><span class="d-none d-md-inline">{{ $LANG['process_payment_via_eway'] ?? '' }}</span></span>
            </label>
            @endif
            <label class="segmented-control-item" title="{{ $LANG['export_pdf'] ?? '' }}" onclick="window.open('index.php?module=export&amp;view=invoice&amp;id={{ urlencode($invoice['id'] ?? '') }}&amp;format=pdf','_blank')">
                <input type="radio" class="segmented-control-input">
                <span class="segmented-control-label"><i class="ti ti-file-type-pdf me-md-1"></i><span class="d-none d-md-inline">{{ $LANG['pdf'] ?? '' }}</span></span>
            </label>
            <label class="segmented-control-item" title="{{ $LANG['export_as'] ?? '' }} .{{ $spreadsheet ?? 'xls' }}" onclick="window.location='index.php?module=export&amp;view=invoice&amp;id={{ urlencode($invoice['id'] ?? '') }}&amp;format=file&amp;filetype={{ urlencode($spreadsheet ?? 'xls') }}'">
                <input type="radio" class="segmented-control-input">
                <span class="segmented-control-label"><i class="ti ti-file-spreadsheet me-md-1"></i><span class="d-none d-md-inline">.{{ strtoupper($spreadsheet ?? 'xls') }}</span></span>
            </label>
            <label class="segmented-control-item" title="{{ $LANG['export_as'] ?? '' }} .{{ $wordprocessor ?? 'doc' }}" onclick="window.location='index.php?module=export&amp;view=invoice&amp;id={{ urlencode($invoice['id'] ?? '') }}&amp;format=file&amp;filetype={{ urlencode($wordprocessor ?? 'doc') }}'">
                <input type="radio" class="segmented-control-input">
                <span class="segmented-control-label"><i class="ti ti-file-text me-md-1"></i><span class="d-none d-md-inline">.{{ strtoupper($wordprocessor ?? 'doc') }}</span></span>
            </label>
            <label class="segmented-control-item" title="{{ $LANG['email'] ?? '' }}" onclick="window.location='index.php?module=invoices&amp;view=email&amp;stage=1&amp;id={{ urlencode($invoice['id'] ?? '') }}'">
                <input type="radio" class="segmented-control-input">
                <span class="segmented-control-label"><i class="ti ti-mail me-md-1"></i><span class="d-none d-md-inline">{{ $LANG['email'] ?? '' }}</span></span>
            </label>
            @if(isset($defaults->delete) && $defaults->delete == '1')
            <label class="segmented-control-item text-danger" title="{{ $LANG['delete'] ?? '' }}" onclick="window.location='index.php?module=invoices&amp;view=delete&amp;stage=1&amp;id={{ urlencode($invoice['id'] ?? '') }}'">
                <input type="radio" class="segmented-control-input">
                <span class="segmented-control-label"><i class="ti ti-trash me-md-1"></i><span class="d-none d-md-inline">{{ $LANG['delete'] ?? '' }}</span></span>
            </label>
            @endif
        </div>
    </div>

        {{-- Invoice number + date --}}
        <div class="mb-4 pb-3 border-bottom">
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="text-uppercase text-secondary small fw-medium mb-2">{{ $preference['pref_inv_wording'] ?? ($LANG['invoice'] ?? '') }}</div>
                    <div class="fw-bold">{{ $invoice['index_id'] ?? '' }}</div>
                </div>
                <div class="col-md-6">
                    <div class="text-uppercase text-secondary small fw-medium mb-2">{{ $LANG['date_upper'] ?? '' }}</div>
                    <div>{{ $invoice['date'] ?? '' }}</div>
                </div>
                @if(!empty($invoice['payment_term_id']) || !empty($invoice['calc_due_date']))
                <div class="col-md-6">
                    <div class="text-uppercase text-secondary small fw-medium mb-2">{{ $LANG['payment_term_code'] ?? 'Payment term code' }}</div>
                    <div class="fw-medium">{{ !empty($invoice['payment_term_code']) ? $invoice['payment_term_code'] : (!empty($invoice['payment_term_label']) ? $invoice['payment_term_label'] : '-') }}</div>
                    @if(!empty($invoice['payment_term_code']) && !empty($invoice['payment_term_label']))
                    <div class="text-secondary small mt-1">{{ $invoice['payment_term_label'] }}</div>
                    @endif
                </div>
                <div class="col-md-6">
                    <div class="text-uppercase text-secondary small fw-medium mb-2">{{ $LANG['due_date'] ?? 'Due date' }}</div>
                    <div class="fw-medium">{{ !empty($invoice['calc_due_date']) ? ($invoice['due_date'] ?? '') : '-' }}</div>
                </div>
                @endif
            </div>
            @if(!empty($customField['1']) || !empty($customField['2']) || !empty($customField['3']) || !empty($customField['4']))
            <div class="row g-3 mt-1">
                @if(!empty($customField['1']))
                <div class="col-auto">{!! $customField['1'] !!}</div>
                @endif
                @if(!empty($customField['2']))
                <div class="col-auto">{!! $customField['2'] !!}</div>
                @endif
                @if(!empty($customField['3']))
                <div class="col-auto">{!! $customField['3'] !!}</div>
                @endif
                @if(!empty($customField['4']))
                <div class="col-auto">{!! $customField['4'] !!}</div>
                @endif
            </div>
            @endif
        </div>

        {{-- Biller + Customer --}}
        <div class="row g-4 mb-4">
            <div class="col-md-6">
                <div class="text-uppercase text-secondary small fw-medium mb-2">{{ $LANG['biller'] ?? '' }}</div>
                <div class="fw-bold mb-1">{{ $biller['name'] ?? '' }}</div>
                <div class="text-secondary lh-lg">
                    @if(!empty($biller['street_address'])){{ $biller['street_address'] }}<br />@endif
                    @if(!empty($biller['street_address2'])){{ $biller['street_address2'] }}<br />@endif
                    @php
                        $billerCity = trim(($biller['city'] ?? '') . (!empty($biller['city']) && (!empty($biller['state']) || !empty($biller['zip_code'])) ? ', ' : '') . ($biller['state'] ?? '') . (!empty($biller['state']) && !empty($biller['zip_code']) ? ' ' : '') . ($biller['zip_code'] ?? ''));
                    @endphp
                    @if(!empty($billerCity)){{ $billerCity }}<br />@endif
                    @if(!empty($biller['country'])){{ $biller['country'] }}<br />@endif
                    @if(!empty($biller['phone'])){{ $LANG['phone_short'] ?? '' }}: {{ $biller['phone'] }}<br />@endif
                    @if(!empty($biller['mobile_phone'])){{ $LANG['mobile_short'] ?? '' }}: {{ $biller['mobile_phone'] }}<br />@endif
                    @if(!empty($biller['fax'])){{ $LANG['fax'] ?? '' }}: {{ $biller['fax'] }}<br />@endif
                    @if(!empty($biller['email']))<a href="mailto:{{ $biller['email'] }}">{{ $biller['email'] }}</a><br />@endif
                    @if(!empty($biller['custom_field1']) && !empty($customFieldLabels['biller_cf1'])){{ $customFieldLabels['biller_cf1'] }}: {{ $biller['custom_field1'] }}<br />@endif
                    @if(!empty($biller['custom_field2']) && !empty($customFieldLabels['biller_cf2'])){{ $customFieldLabels['biller_cf2'] }}: {{ $biller['custom_field2'] }}<br />@endif
                    @if(!empty($biller['custom_field3']) && !empty($customFieldLabels['biller_cf3'])){{ $customFieldLabels['biller_cf3'] }}: {{ $biller['custom_field3'] }}<br />@endif
                    @if(!empty($biller['custom_field4']) && !empty($customFieldLabels['biller_cf4'])){{ $customFieldLabels['biller_cf4'] }}: {{ $biller['custom_field4'] }}<br />@endif
                    @showCustomFields(1, $biller['id'] ?? '')
                </div>
            </div>
            <div class="col-md-6">
                <div class="text-uppercase text-secondary small fw-medium mb-2">{{ $LANG['customer'] ?? '' }}</div>
                <div class="fw-bold mb-1">
                    <a href="index.php?module=customers&amp;view=details&amp;id={{ urlencode($customer['id'] ?? '') }}&amp;action=view">{{ $customer['name'] ?? '' }}</a>
                </div>
                <div class="text-secondary lh-lg">
                    @if(!empty($customer['department'])){{ $LANG['customer_department'] ?? '' }}: {{ $customer['department'] }}<br />@endif
                    @if(!empty($customer['attention'])){{ $LANG['attention_short'] ?? '' }}: {{ $customer['attention'] }}<br />@endif
                    @if(!empty($customer['street_address'])){{ $customer['street_address'] }}<br />@endif
                    @if(!empty($customer['street_address2'])){{ $customer['street_address2'] }}<br />@endif
                    @php
                        $customerCity = trim(($customer['city'] ?? '') . (!empty($customer['city']) && (!empty($customer['state']) || !empty($customer['zip_code'])) ? ', ' : '') . ($customer['state'] ?? '') . (!empty($customer['state']) && !empty($customer['zip_code']) ? ' ' : '') . ($customer['zip_code'] ?? ''));
                    @endphp
                    @if(!empty($customerCity)){{ $customerCity }}<br />@endif
                    @if(!empty($customer['country'])){{ $customer['country'] }}<br />@endif
                    @if(!empty($customer['phone'])){{ $LANG['phone_short'] ?? '' }}: {{ $customer['phone'] }}<br />@endif
                    @if(!empty($customer['mobile_phone'])){{ $LANG['mobile_short'] ?? '' }}: {{ $customer['mobile_phone'] }}<br />@endif
                    @if(!empty($customer['fax'])){{ $LANG['fax'] ?? '' }}: {{ $customer['fax'] }}<br />@endif
                    @if(!empty($customer['email']))<a href="mailto:{{ $customer['email'] }}">{{ $customer['email'] }}</a><br />@endif
                    @if(!empty($customer['custom_field1']) && !empty($customFieldLabels['customer_cf1'])){{ $customFieldLabels['customer_cf1'] }}: {{ $customer['custom_field1'] }}<br />@endif
                    @if(!empty($customer['custom_field2']) && !empty($customFieldLabels['customer_cf2'])){{ $customFieldLabels['customer_cf2'] }}: {{ $customer['custom_field2'] }}<br />@endif
                    @if(!empty($customer['custom_field3']) && !empty($customFieldLabels['customer_cf3'])){{ $customFieldLabels['customer_cf3'] }}: {{ $customer['custom_field3'] }}<br />@endif
                    @if(!empty($customer['custom_field4']) && !empty($customFieldLabels['customer_cf4'])){{ $customFieldLabels['customer_cf4'] }}: {{ $customer['custom_field4'] }}<br />@endif
                    @showCustomFields(2, $customer['id'] ?? '')
                </div>
            </div>
        </div>

        {{-- Total-only invoice (type 1): description --}}
        @if(($invoice['type_id'] ?? 0) == 1)
        <div class="mb-4">
            <div class="text-uppercase text-secondary small fw-medium mb-2">{{ $LANG['description'] ?? '' }}</div>
            <div>{!! outhtml($invoiceItems[0]['description'] ?? '') !!}</div>
        </div>
        @endif

        {{-- Itemised (type 2) or Consulting (type 3): line items table --}}
        @if(($invoice['type_id'] ?? 0) == 2 || ($invoice['type_id'] ?? 0) == 3)
        <div class="table-responsive mb-2">
            <table class="table table-vcenter table-sm">
                <thead>
                    <tr>
                        <th class="w-1">{{ $LANG['quantity_short'] ?? '' }}</th>
                        <th>{{ $LANG['item'] ?? '' }}</th>
                        <th class="text-end">{{ $LANG['unit_cost'] ?? '' }}</th>
                        <th class="text-end">{{ $LANG['price'] ?? '' }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach(($invoiceItems ?? []) as $invoiceItem)
                    <tr>
                        <td class="text-end">{{ siLocal::number_trim($invoiceItem['quantity'] ?? 0) }}</td>
                        <td>{!! outhtml($invoiceItem['product']['description'] ?? '') !!}</td>
                        <td class="text-end text-nowrap">{{ $currency }}{{ siLocal::number($invoiceItem['unit_price'] ?? 0) }}</td>
                        <td class="text-end text-nowrap">{{ $currency }}{{ siLocal::number($invoiceItem['gross_total'] ?? 0) }}</td>
                    </tr>
                    @if(!empty($invoiceItem['attribute']))
                    <tr class="table-light">
                        <td></td>
                        <td colspan="3" class="text-secondary small">
                            @foreach(($invoiceItem['attribute_json'] ?? []) as $k => $v)
                                @if(($v['type'] ?? '') == 'decimal')
                                    {{ $v['name'] ?? '' }}: {{ $currency }}{{ siLocal::number($v['value'] ?? 0) }};
                                @elseif(!empty($v['value']))
                                    {{ $v['name'] ?? '' }}: {{ $v['value'] }};
                                @endif
                            @endforeach
                        </td>
                    </tr>
                    @endif
                    @if(!empty($invoiceItem['description']))
                    <tr class="table-light">
                        <td></td>
                        <td colspan="3" class="text-secondary small fst-italic">{!! outhtml($invoiceItem['description']) !!}</td>
                    </tr>
                    @endif
                    @php
                        $hasProdCf = !empty($invoiceItem['product']['custom_field1']) || !empty($invoiceItem['product']['custom_field2']) || !empty($invoiceItem['product']['custom_field3']) || !empty($invoiceItem['product']['custom_field4']);
                    @endphp
                    @if($hasProdCf)
                    <tr class="table-light">
                        <td></td>
                        <td colspan="3" class="text-secondary small">
                            @if(!empty($invoiceItem['product']['custom_field1']))<span class="me-2">{{ $customFieldLabels['product_cf1'] ?? '' }}: {{ $invoiceItem['product']['custom_field1'] }}</span>@endif
                            @if(!empty($invoiceItem['product']['custom_field2']))<span class="me-2">{{ $customFieldLabels['product_cf2'] ?? '' }}: {{ $invoiceItem['product']['custom_field2'] }}</span>@endif
                            @if(!empty($invoiceItem['product']['custom_field3']))<span class="me-2">{{ $customFieldLabels['product_cf3'] ?? '' }}: {{ $invoiceItem['product']['custom_field3'] }}</span>@endif
                            @if(!empty($invoiceItem['product']['custom_field4']))<span class="me-2">{{ $customFieldLabels['product_cf4'] ?? '' }}: {{ $invoiceItem['product']['custom_field4'] }}</span>@endif
                        </td>
                    </tr>
                    @endif
                    @endforeach
                </tbody>
            </table>
        </div>

        @if(!empty($invoice['note']))
        <div class="mb-4">
            <div class="text-uppercase text-secondary small fw-medium mb-1">{{ $LANG['notes'] ?? '' }}</div>
            <div class="text-secondary">{!! outhtml($invoice['note']) !!}</div>
        </div>
        @endif
        @endif

        {{-- Totals --}}
        <div class="row justify-content-end">
            <div class="col-auto">
                @if(($invoice_number_of_taxes ?? 0) > 0)
                <div class="d-flex gap-4 justify-content-between mb-1">
                    <span class="text-secondary">{{ $LANG['sub_total'] ?? '' }}</span>
                    <span>{{ $currency }}{{ siLocal::number($invoice['gross'] ?? 0) }}</span>
                </div>
                @endif
                @foreach(($invoice['tax_grouped'] ?? []) as $taxLine)
                @if(($taxLine['tax_amount'] ?? '0') != '0')
                <div class="d-flex gap-4 justify-content-between mb-1">
                    <span class="text-secondary">{{ $taxLine['tax_name'] ?? '' }}</span>
                    <span>{{ $currency }}{{ siLocal::number($taxLine['tax_amount'] ?? 0) }}</span>
                </div>
                @endif
                @endforeach
                @if(($invoice_number_of_taxes ?? 0) > 1)
                <div class="d-flex gap-4 justify-content-between mb-1">
                    <span class="text-secondary">{{ $LANG['tax_total'] ?? '' }}</span>
                    <span>{{ $currency }}{{ siLocal::number($invoice['total_tax'] ?? 0) }}</span>
                </div>
                @endif
                <div class="d-flex gap-4 justify-content-between pt-2 border-top fw-bold fs-4">
                    <span>{{ $preference['pref_inv_wording'] ?? ($LANG['invoice'] ?? '') }} {{ $LANG['amount'] ?? '' }}</span>
                    <span>{{ $currency }}{{ siLocal::number($invoice['total'] ?? 0) }}</span>
                </div>
            </div>
        </div>

        {{-- Invoice details / payment instructions --}}
        @php
            $hasDetail = !empty($preference['pref_inv_detail_heading'])
                      || !empty($preference['pref_inv_detail_line'])
                      || !empty($preference['pref_inv_payment_method'])
                      || !empty($preference['pref_inv_payment_line1_name'])
                      || !empty($preference['pref_inv_payment_line1_value'])
                      || !empty($preference['pref_inv_payment_line2_name'])
                      || !empty($preference['pref_inv_payment_line2_value'])
                      || !empty($biller['footer']);
            $hasPaymentMethod = !empty($preference['pref_inv_payment_method']) || !empty($preference['include_online_payment']);
        @endphp
        @if($hasDetail)
        <div class="mt-4 pt-3 border-top">
            @if(!empty($preference['pref_inv_detail_heading']))
            <div class="fw-semibold mb-1">{{ $preference['pref_inv_detail_heading'] }}</div>
            @endif
            @if(!empty($preference['pref_inv_detail_line']))
            <div class="text-secondary small mb-1"><em>{!! outhtml($preference['pref_inv_detail_line']) !!}</em></div>
            @endif
            @if($hasPaymentMethod)
            <div class="si-payment-section mt-3">
                <div class="text-secondary small fw-medium mb-2">{{ $LANG['payment_method'] ?? 'Payment method' }}</div>
                @include('templates.default.partials.payment_processor_badge', [
                    'methodText' => $preference['pref_inv_payment_method'] ?? '',
                    'onlinePayments' => $preference['include_online_payment'] ?? '',
                    'wrapperClass' => 'si-payment-method-prominent',
                ])
            </div>
            @endif
            @if(!empty($preference['pref_inv_payment_line1_name']) || !empty($preference['pref_inv_payment_line1_value']))
            <div class="text-secondary small mb-1">{{ $preference['pref_inv_payment_line1_name'] ?? '' }} {{ $preference['pref_inv_payment_line1_value'] ?? '' }}</div>
            @endif
            @if(!empty($preference['pref_inv_payment_line2_name']) || !empty($preference['pref_inv_payment_line2_value']))
            <div class="text-secondary small">{{ $preference['pref_inv_payment_line2_name'] ?? '' }} {{ $preference['pref_inv_payment_line2_value'] ?? '' }}</div>
            @endif
            @if(!empty($biller['footer']))
            <div class="text-secondary small mt-2 pt-2 border-top">{!! outhtml($biller['footer']) !!}</div>
            @endif
        </div>
        @endif

    </div>
</div>

{{-- Financial Status --}}
<div class="row row-cards">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <div class="card-title mb-0">{{ $preference['pref_inv_wording'] ?? ($LANG['invoice'] ?? '') }} {{ $invoice['index_id'] ?? '' }}</div>
            </div>
            <div class="card-body">
                <div class="row g-3 text-center">
                    <div class="col-4">
                        <div class="text-secondary small mb-1">{{ $LANG['total'] ?? '' }}</div>
                        <div class="fw-bold">{{ $currency }}{{ siLocal::number($invoice['total'] ?? 0) }}</div>
                    </div>
                    <div class="col-4">
                        <div class="text-secondary small mb-1"><a href="index.php?module=payments&amp;view=manage&amp;id={{ urlencode($invoice['id'] ?? '') }}">{{ $LANG['paid'] ?? '' }}</a></div>
                        <div class="fw-bold">{{ $currency }}{{ siLocal::number($invoice['paid'] ?? 0) }}</div>
                    </div>
                    <div class="col-4">
                        <div class="text-secondary small mb-1">{{ $LANG['owing'] ?? '' }}</div>
                        <div class="fw-bold {{ ($invoice['owing'] ?? 0) > 0 ? 'text-danger' : 'text-success' }}">{{ $currency }}{{ siLocal::number($invoice['owing'] ?? 0) }}</div>
                    </div>
                </div>
                @if(!empty($invoice_age))
                <div class="text-center text-secondary small mt-3">
                    {{ $LANG['age'] ?? '' }}: {{ $invoice_age }}
                    <a class="cluetip ms-1" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_age" title="{{ $LANG['age'] ?? '' }}"><i class="ti ti-help"></i></a>
                </div>
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <div class="card-title mb-0"><a href="index.php?module=customers&amp;view=details&amp;id={{ urlencode($customer['id'] ?? '') }}&amp;action=view">{{ $LANG['customer_account'] ?? '' }}</a></div>
            </div>
            <div class="card-body">
                <div class="row g-3 text-center">
                    <div class="col-4">
                        <div class="text-secondary small mb-1">{{ $LANG['total'] ?? '' }}</div>
                        <div class="fw-bold">{{ $currency }}{{ siLocal::number($customerAccount['total'] ?? 0) }}</div>
                    </div>
                    <div class="col-4">
                        <div class="text-secondary small mb-1"><a href="index.php?module=payments&amp;view=manage&amp;c_id={{ urlencode($customer['id'] ?? '') }}">{{ $LANG['paid'] ?? '' }}</a></div>
                        <div class="fw-bold">{{ $currency }}{{ siLocal::number($customerAccount['paid'] ?? 0) }}</div>
                    </div>
                    <div class="col-4">
                        <div class="text-secondary small mb-1">{{ $LANG['owing'] ?? '' }}</div>
                        <div class="fw-bold {{ ($customerAccount['owing'] ?? 0) > 0 ? 'text-danger' : 'text-success' }}">{{ $currency }}{{ siLocal::number($customerAccount['owing'] ?? 0) }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
