{{-- Invoice Quick View --}}
@php
    $currency = $preference['pref_currency_sign'] ?? '';
@endphp

<div class="card mb-3">
    <div class="card-header">
        <div class="card-title mb-0">{{ $preference['pref_inv_wording'] ?? 'Invoice' }} {{ $invoice['index_id'] ?? '' }}</div>
    </div>

    {{-- Action buttons --}}
    <div class="card-body border-bottom">
        <div class="btn-list">
            <a title="{{ $LANG['print_preview_tooltip'] ?? '' }}" href="index.php?module=export&amp;view=invoice&amp;id={{ urlencode($invoice['id'] ?? '') }}&amp;format=print" class="btn btn-outline-secondary btn-sm">
                <i class="ti ti-printer me-1 fs-2"></i>{{ $LANG['print_preview'] ?? 'Print' }}
            </a>
            <a title="{{ $LANG['edit'] ?? 'Edit' }}" href="index.php?module=invoices&amp;view=details&amp;id={{ urlencode($invoice['id'] ?? '') }}&amp;action=view" class="btn btn-outline-secondary btn-sm">
                <i class="ti ti-edit me-1 fs-2"></i>{{ $LANG['edit'] ?? 'Edit' }}
            </a>
            <a title="{{ $LANG['process_payment'] ?? '' }}" href="index.php?module=payments&amp;view=process&amp;id={{ urlencode($invoice['id'] ?? '') }}&amp;op=pay_selected_invoice" class="btn btn-outline-secondary btn-sm">
                <i class="ti ti-cash me-1 fs-2"></i>{{ $LANG['process_payment'] ?? 'Payment' }}
            </a>
            @if(($eway_pre_check ?? '') == 'true')
            <a title="{{ $LANG['process_payment_via_eway'] ?? '' }}" href="index.php?module=payments&amp;view=eway&amp;id={{ urlencode($invoice['id'] ?? '') }}" class="btn btn-outline-secondary btn-sm">
                <i class="ti ti-cash me-1 fs-2"></i>{{ $LANG['process_payment_via_eway'] ?? 'Pay via eWay' }}
            </a>
            @endif
            <a title="{{ $LANG['export_pdf'] ?? '' }}" href="index.php?module=export&amp;view=invoice&amp;id={{ urlencode($invoice['id'] ?? '') }}&amp;format=pdf" class="btn btn-outline-secondary btn-sm">
                <i class="ti ti-file-type-pdf me-1 fs-2"></i>{{ $LANG['export_pdf'] ?? 'PDF' }}
            </a>
            <a title="{{ $LANG['export_as'] ?? '' }} .{{ $spreadsheet ?? 'xls' }}" href="index.php?module=export&amp;view=invoice&amp;id={{ urlencode($invoice['id'] ?? '') }}&amp;format=file&amp;filetype={{ urlencode($spreadsheet ?? 'xls') }}" class="btn btn-outline-secondary btn-sm">
                <i class="ti ti-file-spreadsheet me-1 fs-2"></i>.{{ $spreadsheet ?? 'xls' }}
            </a>
            <a title="{{ $LANG['export_as'] ?? '' }} .{{ $wordprocessor ?? 'doc' }}" href="index.php?module=export&amp;view=invoice&amp;id={{ urlencode($invoice['id'] ?? '') }}&amp;format=file&amp;filetype={{ urlencode($wordprocessor ?? 'doc') }}" class="btn btn-outline-secondary btn-sm">
                <i class="ti ti-file-text me-1 fs-2"></i>.{{ $wordprocessor ?? 'doc' }}
            </a>
            <a title="{{ $LANG['email'] ?? '' }}" href="index.php?module=invoices&amp;view=email&amp;stage=1&amp;id={{ urlencode($invoice['id'] ?? '') }}" class="btn btn-outline-secondary btn-sm">
                <i class="ti ti-mail me-1 fs-2"></i>{{ $LANG['email'] ?? 'Email' }}
            </a>
            @if(isset($defaults->delete) && $defaults->delete == '1')
            <a title="{{ $LANG['delete'] ?? '' }}" href="index.php?module=invoices&amp;view=delete&amp;stage=1&amp;id={{ urlencode($invoice['id'] ?? '') }}" class="btn btn-outline-danger btn-sm">
                <i class="ti ti-trash me-1 fs-2"></i>{{ $LANG['delete'] ?? 'Delete' }}
            </a>
            @endif
        </div>
    </div>

    <div class="card-body">

        {{-- Invoice summary: date + custom fields --}}
        <div class="mb-4 pb-3 border-bottom">
            <div class="row g-3">
                <div class="col-auto">
                    <div class="text-secondary small mb-1">{{ $LANG['date_formatted'] ?? 'Date' }}</div>
                    <div>{{ $invoice['date'] ?? '' }}</div>
                </div>
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
        </div>

        {{-- Biller + Customer --}}
        <div class="row g-4 mb-4">
            <div class="col-md-6">
                <div class="text-uppercase text-secondary small fw-medium mb-2">{{ $LANG['biller'] ?? 'Biller' }}</div>
                <div class="fw-bold mb-1">{{ $biller['name'] ?? '' }}</div>
                <div class="text-secondary lh-lg">
                    @if(!empty($biller['street_address'])){{ $biller['street_address'] }}<br />@endif
                    @if(!empty($biller['street_address2'])){{ $biller['street_address2'] }}<br />@endif
                    @php
                        $billerCity = trim(($biller['city'] ?? '') . (!empty($biller['city']) && (!empty($biller['state']) || !empty($biller['zip_code'])) ? ', ' : '') . ($biller['state'] ?? '') . (!empty($biller['state']) && !empty($biller['zip_code']) ? ' ' : '') . ($biller['zip_code'] ?? ''));
                    @endphp
                    @if(!empty($billerCity)){{ $billerCity }}<br />@endif
                    @if(!empty($biller['country'])){{ $biller['country'] }}<br />@endif
                    @if(!empty($biller['phone'])){{ $LANG['phone_short'] ?? 'Phone' }}: {{ $biller['phone'] }}<br />@endif
                    @if(!empty($biller['mobile_phone'])){{ $LANG['mobile_short'] ?? 'Mobile' }}: {{ $biller['mobile_phone'] }}<br />@endif
                    @if(!empty($biller['fax'])){{ $LANG['fax'] ?? 'Fax' }}: {{ $biller['fax'] }}<br />@endif
                    @if(!empty($biller['email']))<a href="mailto:{{ $biller['email'] }}">{{ $biller['email'] }}</a><br />@endif
                    @if(!empty($biller['custom_field1']) && !empty($customFieldLabels['biller_cf1'])){{ $customFieldLabels['biller_cf1'] }}: {{ $biller['custom_field1'] }}<br />@endif
                    @if(!empty($biller['custom_field2']) && !empty($customFieldLabels['biller_cf2'])){{ $customFieldLabels['biller_cf2'] }}: {{ $biller['custom_field2'] }}<br />@endif
                    @if(!empty($biller['custom_field3']) && !empty($customFieldLabels['biller_cf3'])){{ $customFieldLabels['biller_cf3'] }}: {{ $biller['custom_field3'] }}<br />@endif
                    @if(!empty($biller['custom_field4']) && !empty($customFieldLabels['biller_cf4'])){{ $customFieldLabels['biller_cf4'] }}: {{ $biller['custom_field4'] }}<br />@endif
                    @showCustomFields(1, $biller['id'] ?? '')
                </div>
            </div>
            <div class="col-md-6">
                <div class="text-uppercase text-secondary small fw-medium mb-2">{{ $LANG['customer'] ?? 'Customer' }}</div>
                <div class="fw-bold mb-1">
                    <a href="index.php?module=customers&amp;view=details&amp;id={{ urlencode($customer['id'] ?? '') }}&amp;action=view">{{ $customer['name'] ?? '' }}</a>
                </div>
                <div class="text-secondary lh-lg">
                    @if(!empty($customer['department'])){{ $LANG['customer_department'] ?? 'Dept' }}: {{ $customer['department'] }}<br />@endif
                    @if(!empty($customer['attention'])){{ $LANG['attention_short'] ?? 'Attn' }}: {{ $customer['attention'] }}<br />@endif
                    @if(!empty($customer['street_address'])){{ $customer['street_address'] }}<br />@endif
                    @if(!empty($customer['street_address2'])){{ $customer['street_address2'] }}<br />@endif
                    @php
                        $customerCity = trim(($customer['city'] ?? '') . (!empty($customer['city']) && (!empty($customer['state']) || !empty($customer['zip_code'])) ? ', ' : '') . ($customer['state'] ?? '') . (!empty($customer['state']) && !empty($customer['zip_code']) ? ' ' : '') . ($customer['zip_code'] ?? ''));
                    @endphp
                    @if(!empty($customerCity)){{ $customerCity }}<br />@endif
                    @if(!empty($customer['country'])){{ $customer['country'] }}<br />@endif
                    @if(!empty($customer['phone'])){{ $LANG['phone_short'] ?? 'Phone' }}: {{ $customer['phone'] }}<br />@endif
                    @if(!empty($customer['mobile_phone'])){{ $LANG['mobile_short'] ?? 'Mobile' }}: {{ $customer['mobile_phone'] }}<br />@endif
                    @if(!empty($customer['fax'])){{ $LANG['fax'] ?? 'Fax' }}: {{ $customer['fax'] }}<br />@endif
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
            <div class="text-uppercase text-secondary small fw-medium mb-2">{{ $LANG['description'] ?? 'Description' }}</div>
            <div>{{ $invoiceItems[0]['description'] ?? '' }}</div>
        </div>
        @endif

        {{-- Itemised (type 2) or Consulting (type 3): line items table --}}
        @if(($invoice['type_id'] ?? 0) == 2 || ($invoice['type_id'] ?? 0) == 3)
        <div class="table-responsive mb-2">
            <table class="table table-vcenter table-sm">
                <thead>
                    <tr>
                        <th class="w-1">{{ $LANG['quantity_short'] ?? 'Qty' }}</th>
                        <th>{{ $LANG['item'] ?? 'Item' }}</th>
                        <th class="text-end">{{ $LANG['unit_cost'] ?? 'Unit' }}</th>
                        <th class="text-end">{{ $LANG['price'] ?? 'Amount' }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach(($invoiceItems ?? []) as $invoiceItem)
                    <tr>
                        <td class="text-end">{{ siLocal::number_trim($invoiceItem['quantity'] ?? 0) }}</td>
                        <td>{{ $invoiceItem['product']['description'] ?? '' }}</td>
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
                        <td colspan="3" class="text-secondary small fst-italic">{{ $invoiceItem['description'] }}</td>
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
            <div class="text-uppercase text-secondary small fw-medium mb-1">{{ $LANG['notes'] ?? 'Notes' }}</div>
            <div class="text-secondary">{!! outhtml($invoice['note']) !!}</div>
        </div>
        @endif
        @endif

        {{-- Totals --}}
        <div class="row justify-content-end">
            <div class="col-auto">
                @if(($invoice_number_of_taxes ?? 0) > 0)
                <div class="d-flex gap-4 justify-content-between mb-1">
                    <span class="text-secondary">{{ $LANG['sub_total'] ?? 'Subtotal' }}</span>
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
                    <span class="text-secondary">{{ $LANG['tax_total'] ?? 'Tax Total' }}</span>
                    <span>{{ $currency }}{{ siLocal::number($invoice['total_tax'] ?? 0) }}</span>
                </div>
                @endif
                <div class="d-flex gap-4 justify-content-between pt-2 border-top fw-bold fs-4">
                    <span>{{ $preference['pref_inv_wording'] ?? '' }} {{ $LANG['amount'] ?? 'Total' }}</span>
                    <span>{{ $currency }}{{ siLocal::number($invoice['total'] ?? 0) }}</span>
                </div>
            </div>
        </div>

    </div>
</div>

{{-- Financial Status --}}
<div class="row row-cards">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <div class="card-title mb-0">{{ $preference['pref_inv_wording'] ?? '' }} {{ $invoice['index_id'] ?? '' }}</div>
            </div>
            <div class="card-body">
                <div class="row g-3 text-center">
                    <div class="col-4">
                        <div class="text-secondary small mb-1">{{ $LANG['total'] ?? 'Total' }}</div>
                        <div class="fw-bold">{{ $currency }}{{ siLocal::number($invoice['total'] ?? 0) }}</div>
                    </div>
                    <div class="col-4">
                        <div class="text-secondary small mb-1"><a href="index.php?module=payments&amp;view=manage&amp;id={{ urlencode($invoice['id'] ?? '') }}">{{ $LANG['paid'] ?? 'Paid' }}</a></div>
                        <div class="fw-bold">{{ $currency }}{{ siLocal::number($invoice['paid'] ?? 0) }}</div>
                    </div>
                    <div class="col-4">
                        <div class="text-secondary small mb-1">{{ $LANG['owing'] ?? 'Owing' }}</div>
                        <div class="fw-bold {{ ($invoice['owing'] ?? 0) > 0 ? 'text-danger' : 'text-success' }}">{{ $currency }}{{ siLocal::number($invoice['owing'] ?? 0) }}</div>
                    </div>
                </div>
                @if(!empty($invoice_age))
                <div class="text-center text-secondary small mt-3">
                    {{ $LANG['age'] ?? 'Age' }}: {{ $invoice_age }}
                    <a class="cluetip ms-1" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_age" title="{{ $LANG['age'] ?? 'Age' }}"><i class="ti ti-help"></i></a>
                </div>
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <div class="card-title mb-0"><a href="index.php?module=customers&amp;view=details&amp;id={{ urlencode($customer['id'] ?? '') }}&amp;action=view">{{ $LANG['customer_account'] ?? 'Customer Account' }}</a></div>
            </div>
            <div class="card-body">
                <div class="row g-3 text-center">
                    <div class="col-4">
                        <div class="text-secondary small mb-1">{{ $LANG['total'] ?? 'Total' }}</div>
                        <div class="fw-bold">{{ $currency }}{{ siLocal::number($customerAccount['total'] ?? 0) }}</div>
                    </div>
                    <div class="col-4">
                        <div class="text-secondary small mb-1"><a href="index.php?module=payments&amp;view=manage&amp;c_id={{ urlencode($customer['id'] ?? '') }}">{{ $LANG['paid'] ?? 'Paid' }}</a></div>
                        <div class="fw-bold">{{ $currency }}{{ siLocal::number($customerAccount['paid'] ?? 0) }}</div>
                    </div>
                    <div class="col-4">
                        <div class="text-secondary small mb-1">{{ $LANG['owing'] ?? 'Owing' }}</div>
                        <div class="fw-bold {{ ($customerAccount['owing'] ?? 0) > 0 ? 'text-danger' : 'text-success' }}">{{ $currency }}{{ siLocal::number($customerAccount['owing'] ?? 0) }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
