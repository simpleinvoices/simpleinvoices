{{-- Invoice Quick View --}}

<div class="card mb-3">
    <div class="card-header">
        <div class="card-title mb-0">{{ $preference['pref_inv_wording'] ?? 'Invoice' }} {{ $invoice['index_id'] ?? '' }}</div>
    </div>
    <div class="card-body">
        <div class="btn-list mb-3">
            <a title="{{ $LANG['print_preview_tooltip'] ?? '' }}" href="index.php?module=export&amp;view=invoice&amp;id={{ urlencode($invoice['id'] ?? '') }}&amp;format=print" class="btn btn-outline-secondary btn-sm">
                <i class="ti ti-printer me-1"></i>{{ $LANG['print_preview'] ?? 'Print' }}
            </a>
            <a title="{{ $LANG['edit'] ?? 'Edit' }}" href="index.php?module=invoices&amp;view=details&amp;id={{ urlencode($invoice['id'] ?? '') }}&amp;action=view" class="btn btn-outline-secondary btn-sm">
                <i class="ti ti-edit me-1"></i>{{ $LANG['edit'] ?? 'Edit' }}
            </a>
            <a title="{{ $LANG['process_payment'] ?? '' }}" href="index.php?module=payments&amp;view=process&amp;id={{ urlencode($invoice['id'] ?? '') }}&amp;op=pay_selected_invoice" class="btn btn-outline-secondary btn-sm">
                <i class="ti ti-cash me-1"></i>{{ $LANG['process_payment'] ?? 'Payment' }}
            </a>
            @if(($eway_pre_check ?? '') == 'true')
            <a title="{{ $LANG['process_payment_via_eway'] ?? '' }}" href="index.php?module=payments&amp;view=eway&amp;id={{ urlencode($invoice['id'] ?? '') }}" class="btn btn-outline-secondary btn-sm">
                <i class="ti ti-cash me-1"></i>{{ $LANG['process_payment_via_eway'] ?? 'Pay via eWay' }}
            </a>
            @endif
            <a title="{{ $LANG['export_pdf'] ?? '' }}" href="index.php?module=export&amp;view=invoice&amp;id={{ urlencode($invoice['id'] ?? '') }}&amp;format=pdf" class="btn btn-outline-secondary btn-sm">
                <i class="ti ti-file-type-pdf me-1"></i>{{ $LANG['export_pdf'] ?? 'PDF' }}
            </a>
            <a title="{{ $LANG['export_as'] ?? '' }} .{{ $spreadsheet ?? 'xls' }}" href="index.php?module=export&amp;view=invoice&amp;id={{ urlencode($invoice['id'] ?? '') }}&amp;format=file&amp;filetype={{ urlencode($spreadsheet ?? 'xls') }}" class="btn btn-outline-secondary btn-sm">
                <i class="ti ti-file-spreadsheet me-1"></i>.{{ $spreadsheet ?? 'xls' }}
            </a>
            <a title="{{ $LANG['export_as'] ?? '' }} .{{ $wordprocessor ?? 'doc' }}" href="index.php?module=export&amp;view=invoice&amp;id={{ urlencode($invoice['id'] ?? '') }}&amp;format=file&amp;filetype={{ urlencode($wordprocessor ?? 'doc') }}" class="btn btn-outline-secondary btn-sm">
                <i class="ti ti-file-text me-1"></i>.{{ $wordprocessor ?? 'doc' }}
            </a>
            <a title="{{ $LANG['email'] ?? '' }}" href="index.php?module=invoices&amp;view=email&amp;stage=1&amp;id={{ urlencode($invoice['id'] ?? '') }}" class="btn btn-outline-secondary btn-sm">
                <i class="ti ti-mail me-1"></i>{{ $LANG['email'] ?? 'Email' }}
            </a>
            @if(isset($defaults->delete) && $defaults->delete == '1')
            <a title="{{ $LANG['delete'] ?? '' }}" href="index.php?module=invoices&amp;view=delete&amp;stage=1&amp;id={{ urlencode($invoice['id'] ?? '') }}" class="btn btn-outline-danger btn-sm">
                <i class="ti ti-trash me-1"></i>{{ $LANG['delete'] ?? 'Delete' }}
            </a>
            @endif
        </div>

        <table class="table table-vcenter si_invoice_view">
            {{-- Invoice Summary --}}
            <tr class="tr_head">
                <th>{{ $preference['pref_inv_wording'] ?? '' }} {{ $LANG['number_short'] ?? '' }}:</th>
                <td colspan="4">{{ $invoice['index_id'] ?? '' }}</td>
                <td class="si_switch">
                    <a href="#" class="show-summary btn btn-icon btn-sm" onclick="document.querySelectorAll('.summary').forEach(function(e){e.style.display='';}); document.querySelectorAll('.show-summary').forEach(function(e){e.style.display='none'}); return false;"><i class="ti ti-zoom-in" title="{{ $LANG['show_details'] ?? '' }}"></i></a>
                    <a href="#" class="summary btn btn-icon btn-sm" onclick="document.querySelectorAll('.summary').forEach(function(e){e.style.display='none'}); document.querySelectorAll('.show-summary').forEach(function(e){e.style.display=''}); return false;"><i class="ti ti-zoom-out" title="{{ $LANG['hide_details'] ?? '' }}"></i></a>
                </td>
            </tr>
            <tr class="summary">
                <th>{{ $LANG['date_upper'] ?? '' }}:</th>
                <td colspan="5">{{ $invoice['date'] ?? '' }}</td>
            </tr>

            {!! $customField['1'] ?? '' !!}
            {!! $customField['2'] ?? '' !!}
            {!! $customField['3'] ?? '' !!}
            {!! $customField['4'] ?? '' !!}

            {{-- Biller section --}}
            <tr class="tr_head">
                <th>{{ $LANG['biller'] ?? '' }}:</th>
                <td colspan="4">{{ $biller['name'] ?? '' }}</td>
                <td class="si_switch">
                    <a href="#" class="show-biller btn btn-icon btn-sm" onclick="document.querySelectorAll('.biller').forEach(function(e){e.style.display='';}); document.querySelectorAll('.show-biller').forEach(function(e){e.style.display='none'}); return false;"><i class="ti ti-zoom-in" title="{{ $LANG['show_details'] ?? '' }}"></i></a>
                    <a href="#" class="biller btn btn-icon btn-sm" onclick="document.querySelectorAll('.biller').forEach(function(e){e.style.display='none'}); document.querySelectorAll('.show-biller').forEach(function(e){e.style.display=''}); return false;"><i class="ti ti-zoom-out" title="{{ $LANG['hide_details'] ?? '' }}"></i></a>
                </td>
            </tr>
            <tr class="biller">
                <th>{{ $LANG['street'] ?? '' }}:</th>
                <td colspan="5">{{ $biller['street_address'] ?? '' }}</td>
            </tr>
            <tr class="biller">
                <th>{{ $LANG['street2'] ?? '' }}:</th>
                <td colspan="5">{{ $biller['street_address2'] ?? '' }}</td>
            </tr>
            <tr class="biller">
                <th>{{ $LANG['city'] ?? '' }}:</th>
                <td colspan="3">{{ $biller['city'] ?? '' }}</td>
                <th>{{ $LANG['phone_short'] ?? '' }}:</th>
                <td>{{ $biller['phone'] ?? '' }}</td>
            </tr>
            <tr class="biller">
                <th>{{ $LANG['state'] ?? '' }}, {{ $LANG['zip'] ?? '' }}:</th>
                <td colspan="3">{{ $biller['state'] ?? '' }}, {{ $biller['zip_code'] ?? '' }}</td>
                <th>{{ $LANG['mobile_short'] ?? '' }}:</th>
                <td>{{ $biller['mobile_phone'] ?? '' }}</td>
            </tr>
            <tr class="biller">
                <th>{{ $LANG['country'] ?? '' }}:</th>
                <td colspan="3">{{ $biller['country'] ?? '' }}</td>
                <th>{{ $LANG['fax'] ?? '' }}:</th>
                <td>{{ $biller['fax'] ?? '' }}</td>
            </tr>
            <tr class="biller">
                <th>{{ $LANG['email'] ?? '' }}:</th>
                <td colspan="5">{{ $biller['email'] ?? '' }}</td>
            </tr>
            <tr class="biller">
                <th>{{ $customFieldLabels['biller_cf1'] ?? '' }}:</th>
                <td colspan="5">{{ $biller['custom_field1'] ?? '' }}</td>
            </tr>
            <tr class="biller">
                <th>{{ $customFieldLabels['biller_cf2'] ?? '' }}:</th>
                <td colspan="5">{{ $biller['custom_field2'] ?? '' }}</td>
            </tr>
            <tr class="biller">
                <th>{{ $customFieldLabels['biller_cf3'] ?? '' }}:</th>
                <td colspan="5">{{ $biller['custom_field3'] ?? '' }}</td>
            </tr>
            <tr class="biller">
                <th>{{ $customFieldLabels['biller_cf4'] ?? '' }}:</th>
                <td colspan="5">{{ $biller['custom_field4'] ?? '' }}</td>
            </tr>
            @showCustomFields(1, $biller['id'] ?? '')

            {{-- Customer section --}}
            <tr class="tr_head">
                <th>{{ $LANG['customer'] ?? '' }}:</th>
                <td colspan="4">{{ $customer['name'] ?? '' }}</td>
                <td class="si_switch">
                    <a href="#" class="show-customer btn btn-icon btn-sm" onclick="document.querySelectorAll('.customer').forEach(function(e){e.style.display='';}); document.querySelectorAll('.show-customer').forEach(function(e){e.style.display='none'}); return false;"><i class="ti ti-zoom-in" title="{{ $LANG['show_details'] ?? '' }}"></i></a>
                    <a href="#" class="customer btn btn-icon btn-sm" onclick="document.querySelectorAll('.customer').forEach(function(e){e.style.display='none'}); document.querySelectorAll('.show-customer').forEach(function(e){e.style.display=''}); return false;"><i class="ti ti-zoom-out" title="{{ $LANG['hide_details'] ?? '' }}"></i></a>
                </td>
            </tr>
            <tr class="customer">
                <th>{{ $LANG['customer_department'] ?? '' }}:</th>
                <td colspan="5">{{ $customer['department'] ?? '' }}</td>
            </tr>
            <tr class="customer">
                <th>{{ $LANG['attention_short'] ?? '' }}:</th>
                <td colspan="5">{{ $customer['attention'] ?? '' }}</td>
            </tr>
            <tr class="customer">
                <th>{{ $LANG['street'] ?? '' }}:</th>
                <td colspan="5">{{ $customer['street_address'] ?? '' }}</td>
            </tr>
            <tr class="customer">
                <th>{{ $LANG['street2'] ?? '' }}:</th>
                <td colspan="5">{{ $customer['street_address2'] ?? '' }}</td>
            </tr>
            <tr class="customer">
                <th>{{ $LANG['city'] ?? '' }}:</th>
                <td colspan="3">{{ $customer['city'] ?? '' }}</td>
                <th>{{ $LANG['phone_short'] ?? '' }}:</th>
                <td>{{ $customer['phone'] ?? '' }}</td>
            </tr>
            <tr class="customer">
                <th>{{ $LANG['state'] ?? '' }}, {{ $LANG['zip'] ?? '' }}:</th>
                <td colspan="3">{{ $customer['state'] ?? '' }}, {{ $customer['zip_code'] ?? '' }}</td>
                <th>{{ $LANG['mobile_short'] ?? '' }}:</th>
                <td>{{ $customer['mobile_phone'] ?? '' }}</td>
            </tr>
            <tr class="customer">
                <th>{{ $LANG['country'] ?? '' }}:</th>
                <td colspan="3">{{ $customer['country'] ?? '' }}</td>
                <th>{{ $LANG['fax'] ?? '' }}:</th>
                <td>{{ $customer['fax'] ?? '' }}</td>
            </tr>
            <tr class="customer">
                <th>{{ $LANG['email'] ?? '' }}:</th>
                <td colspan="5">{{ $customer['email'] ?? '' }}</td>
            </tr>
            <tr class="customer">
                <th>{{ $customFieldLabels['customer_cf1'] ?? '' }}:</th>
                <td colspan="5">{{ $customer['custom_field1'] ?? '' }}</td>
            </tr>
            <tr class="customer">
                <th>{{ $customFieldLabels['customer_cf2'] ?? '' }}:</th>
                <td colspan="5">{{ $customer['custom_field2'] ?? '' }}</td>
            </tr>
            <tr class="customer">
                <th>{{ $customFieldLabels['customer_cf3'] ?? '' }}:</th>
                <td colspan="5">{{ $customer['custom_field3'] ?? '' }}</td>
            </tr>
            <tr class="customer">
                <th>{{ $customFieldLabels['customer_cf4'] ?? '' }}:</th>
                <td colspan="5">{{ $customer['custom_field4'] ?? '' }}</td>
            </tr>
            @showCustomFields(2, $customer['id'] ?? '')

            {{-- Total-only invoice (type 1) --}}
            @if(($invoice['type_id'] ?? 0) == 1)
            <tr class="tr_head">
                <th colspan="6">{{ $LANG['description'] ?? '' }}</th>
            </tr>
            <tr>
                <td colspan="6">{{ $invoiceItems[0]['description'] ?? '' }}</td>
            </tr>
            @endif

            {{-- Itemised (type 2) or Consulting (type 3) --}}
            @if(($invoice['type_id'] ?? 0) == 2 || ($invoice['type_id'] ?? 0) == 3)
            <tr class="tr_head">
                <th colspan="5"></th>
                <td class="si_switch">
                    @if(($invoice['type_id'] ?? 0) == 2)
                    <a href="#" class="show-itemised btn btn-icon btn-sm" onclick="document.querySelectorAll('.itemised').forEach(function(e){e.style.display='';}); document.querySelectorAll('.show-itemised').forEach(function(e){e.style.display='none'}); return false;"><i class="ti ti-zoom-in" title="{{ $LANG['show_details'] ?? '' }}"></i></a>
                    <a href="#" class="itemised btn btn-icon btn-sm" onclick="document.querySelectorAll('.itemised').forEach(function(e){e.style.display='none'}); document.querySelectorAll('.show-itemised').forEach(function(e){e.style.display=''}); return false;"><i class="ti ti-zoom-out" title="{{ $LANG['hide_details'] ?? '' }}"></i></a>
                    @endif
                    @if(($invoice['type_id'] ?? 0) == 3)
                    <a href="#" class="show-consulting btn btn-icon btn-sm" onclick="document.querySelectorAll('.consulting').forEach(function(e){e.style.display='';}); document.querySelectorAll('.show-consulting').forEach(function(e){e.style.display='none'}); return false;"><i class="ti ti-zoom-in" title="{{ $LANG['show_details'] ?? '' }}"></i></a>
                    <a href="#" class="consulting btn btn-icon btn-sm" onclick="document.querySelectorAll('.consulting').forEach(function(e){e.style.display='none'}); document.querySelectorAll('.show-consulting').forEach(function(e){e.style.display=''}); return false;"><i class="ti ti-zoom-out" title="{{ $LANG['hide_details'] ?? '' }}"></i></a>
                    @endif
                </td>
            </tr>
            <tr>
                <td colspan="6">
                    <table class="si_invoice_view_items">
                        <tr class="tr_head_items">
                            <th class="si_quantity">{{ $LANG['quantity_short'] ?? '' }}</th>
                            <th colspan="2">{{ $LANG['item'] ?? '' }}</th>
                            <th class="si_right">{{ $LANG['unit_cost'] ?? '' }}</th>
                            <th class="si_right">{{ $LANG['price'] ?? '' }}</th>
                        </tr>

                        @foreach(($invoiceItems ?? []) as $invoiceItem)

                        @if(($invoice['type_id'] ?? 0) == 2)
                        <tr>
                            <td class="si_quantity">{{ siLocal::number_trim($invoiceItem['quantity'] ?? '') }}</td>
                            <td class="td_product" colspan="2">{{ $invoiceItem['product']['description'] ?? '' }}</td>
                            <td class="si_right">{{ $preference['pref_currency_sign'] ?? '' }}{{ siLocal::number($invoiceItem['unit_price'] ?? 0) }}</td>
                            <td class="si_right">{{ $preference['pref_currency_sign'] ?? '' }}{{ siLocal::number($invoiceItem['gross_total'] ?? 0) }}</td>
                        </tr>
                        @if(!empty($invoiceItem['attribute']))
                        <tr class="si_product_attribute">
                            <td></td>
                            <td colspan="4">
                                <table>
                                    <tr class="si_product_attribute">
                                    @foreach(($invoiceItem['attribute_json'] ?? []) as $k => $v)
                                        <td class="si_product_attribute">
                                            @if(($v['type'] ?? '') == 'decimal')
                                            {{ $v['name'] ?? '' }}: {{ $preference['pref_currency_sign'] ?? '' }}{{ siLocal::number($v['value'] ?? 0) }} ;
                                            @else
                                            {{ $v['name'] ?? '' }}: {{ $v['value'] ?? '' }} ;
                                            @endif
                                        </td>
                                    @endforeach
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        @endif

                        @if(!empty($invoiceItem['description']))
                        <tr class="show-itemised tr_desc">
                            <td></td>
                            <td colspan="5">
                                {{ mb_strimwidth($invoiceItem['description'] ?? '', 0, 80, '...') }}
                            </td>
                        </tr>
                        <tr class="itemised tr_desc">
                            <td></td>
                            <td colspan="5">{{ $invoiceItem['description'] ?? '' }}</td>
                        </tr>
                        @endif

                        <tr class="itemised tr_custom">
                            <td></td>
                            <td colspan="5">
                                <table class="si_invoice_view_custom_items">
                                    <tr>
                                        <th>{{ $customFieldLabels['product_cf1'] ?? '' }}:</th>
                                        <td>{{ $invoiceItem['product']['custom_field1'] ?? '' }}</td>
                                        <th>{{ $customFieldLabels['product_cf2'] ?? '' }}:</th>
                                        <td>{{ $invoiceItem['product']['custom_field2'] ?? '' }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ $customFieldLabels['product_cf3'] ?? '' }}:</th>
                                        <td>{{ $invoiceItem['product']['custom_field3'] ?? '' }}</td>
                                        <th>{{ $customFieldLabels['product_cf4'] ?? '' }}:</th>
                                        <td>{{ $invoiceItem['product']['custom_field4'] ?? '' }}</td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        @showCustomFields(3, $invoiceItem['productId'] ?? '')
                        @endif

                        @if(($invoice['type_id'] ?? 0) == 3)
                        <tr>
                            <td class="si_quantity">{{ siLocal::number($invoiceItem['quantity'] ?? 0) }}</td>
                            <td class="td_product" colspan="2">{{ $invoiceItem['product']['description'] ?? '' }}</td>
                            <td class="si_right">{{ $preference['pref_currency_sign'] ?? '' }}{{ siLocal::number($invoiceItem['unit_price'] ?? 0) }}</td>
                            <td class="si_right">{{ $preference['pref_currency_sign'] ?? '' }}{{ siLocal::number($invoiceItem['gross_total'] ?? 0) }}</td>
                        </tr>
                        <tr class="consulting tr_custom">
                            <td></td>
                            <td colspan="5">
                                <table class="si_invoice_view_custom_items">
                                    <tr>
                                        <th>{{ $customFieldLabels['product_cf1'] ?? '' }}:</th>
                                        <td>{{ $invoiceItem['product']['custom_field1'] ?? '' }}</td>
                                        <th>{{ $customFieldLabels['product_cf2'] ?? '' }}:</th>
                                        <td>{{ $invoiceItem['product']['custom_field2'] ?? '' }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ $customFieldLabels['product_cf3'] ?? '' }}:</th>
                                        <td>{{ $invoiceItem['product']['custom_field3'] ?? '' }}</td>
                                        <th>{{ $customFieldLabels['product_cf4'] ?? '' }}:</th>
                                        <td>{{ $invoiceItem['product']['custom_field4'] ?? '' }}</td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        @endif

                        @endforeach
                    </table>
                </td>
            </tr>

            @if(!empty($invoice['note']))
            <tr class="tr_head">
                <th>{{ $LANG['notes'] ?? '' }}:</th>
                <td colspan="4"></td>
                <td class="si_switch">
                    @if(strlen($invoice['note'] ?? '') > 25)
                    <a href="#" class="show-notes btn btn-icon btn-sm" onclick="document.querySelectorAll('.notes').forEach(function(e){e.style.display='';}); document.querySelectorAll('.show-notes').forEach(function(e){e.style.display='none'}); return false;"><i class="ti ti-zoom-in" title="{{ $LANG['show_details'] ?? '' }}"></i></a>
                    <a href="#" class="notes si_hide btn btn-icon btn-sm" onclick="document.querySelectorAll('.notes').forEach(function(e){e.style.display='none'}); document.querySelectorAll('.show-notes').forEach(function(e){e.style.display=''}); return false;"><i class="ti ti-zoom-out" title="{{ $LANG['hide_details'] ?? '' }}"></i></a>
                    @endif
                </td>
            </tr>
            <tr class="show-notes tr_notes">
                <td colspan="6">{!! outhtml(mb_strimwidth($invoice['note'] ?? '', 0, 25, '...')) !!}</td>
            </tr>
            <tr class="notes tr_notes">
                <td colspan="6">{!! outhtml($invoice['note'] ?? '') !!}</td>
            </tr>
            @endif
            @endif

            {{-- Tax section --}}
            @if(($invoice_number_of_taxes ?? 0) > 0)
            <tr class="tr_tax">
                <td colspan="4"></td>
                <th>{{ $LANG['sub_total'] ?? '' }}</th>
                <td class="si_right">
                    @if(($invoice_number_of_taxes ?? 0) > 1)<u>@endif
                    {{ $preference['pref_currency_sign'] ?? '' }}{{ siLocal::number($invoice['gross'] ?? 0) }}
                    @if(($invoice_number_of_taxes ?? 0) > 1)</u>@endif
                </td>
            </tr>
            @endif

            @foreach(($invoice['tax_grouped'] ?? []) as $taxLine)
            @if(($taxLine['tax_amount'] ?? '0') != '0')
            <tr class="tr_tax">
                <td colspan="4"></td>
                <th>{{ $taxLine['tax_name'] ?? '' }}</th>
                <td class="si_right">{{ $preference['pref_currency_sign'] ?? '' }}{{ siLocal::number($taxLine['tax_amount'] ?? 0) }}</td>
            </tr>
            @endif
            @endforeach

            @if(($invoice_number_of_taxes ?? 0) > 1)
            <tr class="tr_tax">
                <td colspan="4"></td>
                <th>{{ $LANG['tax_total'] ?? '' }}</th>
                <td class="si_right"><u>{{ $preference['pref_currency_sign'] ?? '' }}{{ siLocal::number($invoice['total_tax'] ?? 0) }}</u></td>
            </tr>
            @endif

            <tr class="tr_total">
                <td colspan="4"></td>
                <th>{{ $preference['pref_inv_wording'] ?? '' }} {{ $LANG['amount'] ?? '' }}</th>
                <td class="si_right">{{ $preference['pref_currency_sign'] ?? '' }}{{ siLocal::number($invoice['total'] ?? 0) }}</td>
            </tr>
        </table>
    </div>
</div>

<div class="card mt-3">
    <div class="card-header">
        <h4 class="card-title mb-0">{{ $LANG['financial_status'] ?? 'Financial Status' }}</h4>
    </div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-6">
                <h5>{{ $preference['pref_inv_wording'] ?? '' }} {{ $invoice['index_id'] ?? '' }}</h5>
                <table class="table table-sm table-vcenter">
                    <tr>
                        <th>{{ $LANG['total'] ?? '' }}</th>
                        <th><a href="index.php?module=payments&amp;view=manage&amp;id={{ urlencode($invoice['id'] ?? '') }}">{{ $LANG['paid'] ?? '' }}</a></th>
                        <th>{{ $LANG['owing'] ?? '' }}</th>
                        <th>{{ $LANG['age'] ?? '' }}
                            <a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_age" title="{{ $LANG['age'] ?? '' }}"><i class="ti ti-help"></i></a>
                        </th>
                    </tr>
                    <tr>
                        <td>{{ $preference['pref_currency_sign'] ?? '' }}{{ siLocal::number($invoice['total'] ?? 0) }}</td>
                        <td>{{ $preference['pref_currency_sign'] ?? '' }}{{ siLocal::number($invoice['paid'] ?? 0) }}</td>
                        <td>{{ $preference['pref_currency_sign'] ?? '' }}{{ siLocal::number($invoice['owing'] ?? 0) }}</td>
                        <td>{{ $invoice_age ?? '' }}</td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                <h5><a href="index.php?module=customers&amp;view=details&amp;id={{ urlencode($customer['id'] ?? '') }}&amp;action=view">{{ $LANG['customer_account'] ?? 'Customer Account' }}</a></h5>
                <table class="table table-sm table-vcenter">
                    <tr>
                        <th>{{ $LANG['total'] ?? '' }}</th>
                        <th><a href="index.php?module=payments&amp;view=manage&amp;c_id={{ urlencode($customer['id'] ?? '') }}">{{ $LANG['paid'] ?? '' }}</a></th>
                        <th>{{ $LANG['owing'] ?? '' }}</th>
                    </tr>
                    <tr>
                        <td>{{ $preference['pref_currency_sign'] ?? '' }}{{ siLocal::number($customerAccount['total'] ?? 0) }}</td>
                        <td>{{ $preference['pref_currency_sign'] ?? '' }}{{ siLocal::number($customerAccount['paid'] ?? 0) }}</td>
                        <td>{{ $preference['pref_currency_sign'] ?? '' }}{{ siLocal::number($customerAccount['owing'] ?? 0) }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
