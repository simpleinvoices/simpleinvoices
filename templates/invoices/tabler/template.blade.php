<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<title>{{ $preference['pref_inv_wording'] ?? 'Invoice' }} {{ $LANG['number_short'] ?? '' }}: {{ $invoice['index_id'] ?? '' }}</title>
	<link rel="stylesheet" type="text/css" href="{{ $css|urlsafe }}" media="all" />
</head>
<body>
	<div class="si-tabler-inv-header">
		<div>
			@if(!empty($logo))
			<img src="{{ $logo|urlsafe }}" alt="" class="si-tabler-inv-logo" />
			@endif
		</div>
		<div style="text-align: right;">
			<div class="si-tabler-inv-title">{{ $preference['pref_inv_heading'] ?? '' }}</div>
			{{-- Summary: Invoice #, Date, Custom fields, Total/Paid/Owing --}}
			<div class="si-tabler-inv-summary">
				<table>
					<tr><td class="col1" colspan="2"><b>{{ $preference['pref_inv_wording'] ?? '' }} {{ $LANG['summary'] ?? 'Summary' }}</b></td></tr>
					<tr><td>{{ $preference['pref_inv_wording'] ?? '' }} {{ $LANG['number_short'] ?? '' }}:</td><td>{{ $invoice['index_id'] ?? '' }}</td></tr>
					<tr><td>{{ $preference['pref_inv_wording'] ?? '' }} {{ $LANG['date'] ?? 'Date' }}:</td><td>{{ $invoice['date'] ?? '' }}</td></tr>
					@if(($invoice['custom_field1'] ?? null) != null)
					<tr><td>{{ $customFieldLabels['invoice_cf1'] ?? '' }}:</td><td>{{ $invoice['custom_field1'] ?? '' }}</td></tr>
					@endif
					@if(($invoice['custom_field2'] ?? null) != null)
					<tr><td>{{ $customFieldLabels['invoice_cf2'] ?? '' }}:</td><td>{{ $invoice['custom_field2'] ?? '' }}</td></tr>
					@endif
					@if(($invoice['custom_field3'] ?? null) != null)
					<tr><td>{{ $customFieldLabels['invoice_cf3'] ?? '' }}:</td><td>{{ $invoice['custom_field3'] ?? '' }}</td></tr>
					@endif
					@if(($invoice['custom_field4'] ?? null) != null)
					<tr><td>{{ $customFieldLabels['invoice_cf4'] ?? '' }}:</td><td>{{ $invoice['custom_field4'] ?? '' }}</td></tr>
					@endif
					<tr><td>{{ $LANG['total'] ?? 'Total' }}:</td><td>{{ $preference['pref_currency_sign'] ?? '' }} {{ ($invoice['total'] ?? '')|siLocal_number }}</td></tr>
					<tr><td>{{ $LANG['paid'] ?? 'Paid' }}:</td><td>{{ $preference['pref_currency_sign'] ?? '' }} {{ ($invoice['paid'] ?? '')|siLocal_number }}</td></tr>
					<tr><td>{{ $LANG['owing'] ?? 'Owing' }}:</td><td>{{ $preference['pref_currency_sign'] ?? '' }} {{ ($invoice['owing'] ?? '')|siLocal_number }}</td></tr>
				</table>
			</div>
		</div>
	</div>

	{{-- Company (biller) and Client (customer) – two columns --}}
	<div class="si-tabler-inv-two-col">
		<div>
			<div class="si-tabler-inv-label">{{ $LANG['biller'] ?? 'Company' }}</div>
			<div class="si-tabler-inv-name">{{ $biller['name'] ?? '' }}</div>
			<div class="si-tabler-inv-address">
				@if(!empty($biller['street_address'])){{ $biller['street_address'] ?? '' }}<br />@endif
				@if(!empty($biller['street_address2'])){{ $biller['street_address2'] ?? '' }}<br />@endif
				@if(!empty($biller['city']) || !empty($biller['state']) || !empty($biller['zip_code']))
					{{ $biller['city'] ?? '' }}{{ !empty($biller['city']) && (!empty($biller['state']) || !empty($biller['zip_code'])) ? ', ' : '' }}{{ $biller['state'] ?? '' }}{{ !empty($biller['state']) && !empty($biller['zip_code']) ? ' ' : '' }}{{ $biller['zip_code'] ?? '' }}<br />
				@endif
				@if(!empty($biller['country'])){{ $biller['country'] ?? '' }}<br />@endif
				@if(!empty($biller['phone'])){{ $LANG['phone_short'] ?? 'Phone' }}: {{ $biller['phone'] ?? '' }}<br />@endif
				@if(!empty($biller['fax'])){{ $LANG['fax'] ?? 'Fax' }}: {{ $biller['fax'] ?? '' }}<br />@endif
				@if(!empty($biller['mobile_phone'])){{ $LANG['mobile_short'] ?? 'Mobile' }}: {{ $biller['mobile_phone'] ?? '' }}<br />@endif
				@if(!empty($biller['email'])){{ $biller['email'] ?? '' }}<br />@endif
				@if(!empty($biller['custom_field1'])){{ $customFieldLabels['biller_cf1'] ?? '' }}: {{ $biller['custom_field1'] ?? '' }}<br />@endif
				@if(!empty($biller['custom_field2'])){{ $customFieldLabels['biller_cf2'] ?? '' }}: {{ $biller['custom_field2'] ?? '' }}<br />@endif
				@if(!empty($biller['custom_field3'])){{ $customFieldLabels['biller_cf3'] ?? '' }}: {{ $biller['custom_field3'] ?? '' }}<br />@endif
				@if(!empty($biller['custom_field4'])){{ $customFieldLabels['biller_cf4'] ?? '' }}: {{ $biller['custom_field4'] ?? '' }}<br />@endif
			</div>
		</div>
		<div>
			<div class="si-tabler-inv-label">{{ $LANG['customer'] ?? 'Client' }}</div>
			<div class="si-tabler-inv-name">{{ $customer['name'] ?? '' }}</div>
			<div class="si-tabler-inv-address">
				@if(!empty($customer['department'])){{ $LANG['customer_department'] ?? 'Dept' }}: {{ $customer['department'] ?? '' }}<br />@endif
				@if(!empty($customer['attention'])){{ $LANG['attention_short'] ?? 'Attn' }}: {{ $customer['attention'] ?? '' }}<br />@endif
				@if(!empty($customer['street_address'])){{ $customer['street_address'] ?? '' }}<br />@endif
				@if(!empty($customer['street_address2'])){{ $customer['street_address2'] ?? '' }}<br />@endif
				@if(!empty($customer['city']) || !empty($customer['state']) || !empty($customer['zip_code']))
					{{ $customer['city'] ?? '' }}{{ !empty($customer['city']) && (!empty($customer['state']) || !empty($customer['zip_code'])) ? ', ' : '' }}{{ $customer['state'] ?? '' }}{{ !empty($customer['state']) && !empty($customer['zip_code']) ? ' ' : '' }}{{ $customer['zip_code'] ?? '' }}<br />
				@endif
				@if(!empty($customer['country'])){{ $customer['country'] ?? '' }}<br />@endif
				@if(!empty($customer['phone'])){{ $LANG['phone_short'] ?? 'Phone' }}: {{ $customer['phone'] ?? '' }}<br />@endif
				@if(!empty($customer['fax'])){{ $LANG['fax'] ?? 'Fax' }}: {{ $customer['fax'] ?? '' }}<br />@endif
				@if(!empty($customer['mobile_phone'])){{ $LANG['mobile_short'] ?? 'Mobile' }}: {{ $customer['mobile_phone'] ?? '' }}<br />@endif
				@if(!empty($customer['email'])){{ $customer['email'] ?? '' }}<br />@endif
				@if(!empty($customer['custom_field1'])){{ $customFieldLabels['customer_cf1'] ?? '' }}: {{ $customer['custom_field1'] ?? '' }}<br />@endif
				@if(!empty($customer['custom_field2'])){{ $customFieldLabels['customer_cf2'] ?? '' }}: {{ $customer['custom_field2'] ?? '' }}<br />@endif
				@if(!empty($customer['custom_field3'])){{ $customFieldLabels['customer_cf3'] ?? '' }}: {{ $customer['custom_field3'] ?? '' }}<br />@endif
				@if(!empty($customer['custom_field4'])){{ $customFieldLabels['customer_cf4'] ?? '' }}: {{ $customer['custom_field4'] ?? '' }}<br />@endif
			</div>
		</div>
	</div>

	<h1 class="si-tabler-inv-h1">{{ $preference['pref_inv_wording'] ?? 'Invoice' }} {{ $invoice['index_id'] ?? '' }}</h1>

	{{-- Line items: Type 2 itemised --}}
	@if(($invoice['type_id'] ?? null) == 2)
	<table class="si-tabler-inv-table">
		<thead>
			<tr>
				<th>{{ $LANG['quantity_short'] ?? 'Qty' }}</th>
				<th>{{ $LANG['item'] ?? 'Item' }}</th>
				<th class="text-end">{{ $LANG['unit_cost'] ?? 'Unit' }}</th>
				<th class="text-end">{{ $LANG['price'] ?? 'Amount' }}</th>
			</tr>
		</thead>
		<tbody>
			@foreach(($invoiceItems ?? []) as $invoiceItem)
			<tr>
				<td>{{ ($invoiceItem['quantity'] ?? '')|siLocal_number_trim }}</td>
				<td>{{ $invoiceItem['product']['description'] ?? '' }}</td>
				<td class="text-end">{{ $preference['pref_currency_sign'] ?? '' }} {{ ($invoiceItem['unit_price'] ?? '')|siLocal_number }}</td>
				<td class="text-end">{{ $preference['pref_currency_sign'] ?? '' }} {{ ($invoiceItem['gross_total'] ?? '')|siLocal_number }}</td>
			</tr>
			@if(($invoiceItem['attribute'] ?? null) != null)
			<tr class="si_product_attribute">
				<td></td>
				<td colspan="3">
					@foreach(($invoiceItem['attribute_json'] ?? []) as $k => $v)
						@if(($v['visible'] ?? null) == true)
							@if(($v['type'] ?? null) == 'decimal')
								{{ $v['name'] ?? '' }}: {{ $preference['pref_currency_sign'] ?? '' }} {{ ($v['value'] ?? '')|siLocal_number }};
							@elseif(!empty($v['value']))
								{{ $v['name'] ?? '' }}: {{ $v['value'] }};
							@endif
						@endif
					@endforeach
				</td>
			</tr>
			@endif
			@if(!empty($invoiceItem['description']))
			<tr class="si-tabler-inv-line-desc">
				<td></td>
				<td colspan="3">{{ $LANG['description'] ?? 'Description' }}: {{ $invoiceItem['description'] ?? '' }}</td>
			</tr>
			@endif
			@if(!empty($invoiceItem['product']['custom_field1']) || !empty($invoiceItem['product']['custom_field2']) || !empty($invoiceItem['product']['custom_field3']) || !empty($invoiceItem['product']['custom_field4']))
			<tr class="si-tabler-inv-line-cf">
				<td></td>
				<td colspan="3">
					@if(!empty($invoiceItem['product']['custom_field1']))<span>{{ $customFieldLabels['product_cf1'] ?? '' }}: {{ $invoiceItem['product']['custom_field1'] ?? '' }}</span>@endif
					@if(!empty($invoiceItem['product']['custom_field2']))<span>{{ $customFieldLabels['product_cf2'] ?? '' }}: {{ $invoiceItem['product']['custom_field2'] ?? '' }}</span>@endif
					@if(!empty($invoiceItem['product']['custom_field3']))<span>{{ $customFieldLabels['product_cf3'] ?? '' }}: {{ $invoiceItem['product']['custom_field3'] ?? '' }}</span>@endif
					@if(!empty($invoiceItem['product']['custom_field4']))<span>{{ $customFieldLabels['product_cf4'] ?? '' }}: {{ $invoiceItem['product']['custom_field4'] ?? '' }}</span>@endif
				</td>
			</tr>
			@endif
			@endforeach
		</tbody>
	</table>
	@endif

	{{-- Line items: Type 3 consulting --}}
	@if(($invoice['type_id'] ?? null) == 3)
	<table class="si-tabler-inv-table">
		<thead>
			<tr>
				<th>{{ $LANG['quantity_short'] ?? 'Qty' }}</th>
				<th>{{ $LANG['item'] ?? 'Item' }}</th>
				<th class="text-end">{{ $LANG['unit_cost'] ?? 'Unit' }}</th>
				<th class="text-end">{{ $LANG['price'] ?? 'Amount' }}</th>
			</tr>
		</thead>
		<tbody>
			@foreach(($invoiceItems ?? []) as $invoiceItem)
			<tr>
				<td>{{ ($invoiceItem['quantity'] ?? '')|siLocal_number }}</td>
				<td>{{ $invoiceItem['product']['description'] ?? '' }}</td>
				<td></td>
				<td></td>
			</tr>
			@if(!empty($invoiceItem['product']['custom_field1']) || !empty($invoiceItem['product']['custom_field2']) || !empty($invoiceItem['product']['custom_field3']) || !empty($invoiceItem['product']['custom_field4']))
			<tr class="si-tabler-inv-line-cf">
				<td></td>
				<td colspan="3">
					@if(!empty($invoiceItem['product']['custom_field1']))<span>{{ $customFieldLabels['product_cf1'] ?? '' }}: {{ $invoiceItem['product']['custom_field1'] ?? '' }}</span>@endif
					@if(!empty($invoiceItem['product']['custom_field2']))<span>{{ $customFieldLabels['product_cf2'] ?? '' }}: {{ $invoiceItem['product']['custom_field2'] ?? '' }}</span>@endif
					@if(!empty($invoiceItem['product']['custom_field3']))<span>{{ $customFieldLabels['product_cf3'] ?? '' }}: {{ $invoiceItem['product']['custom_field3'] ?? '' }}</span>@endif
					@if(!empty($invoiceItem['product']['custom_field4']))<span>{{ $customFieldLabels['product_cf4'] ?? '' }}: {{ $invoiceItem['product']['custom_field4'] ?? '' }}</span>@endif
				</td>
			</tr>
			@endif
			@if(!empty($invoiceItem['description']))
			<tr class="si-tabler-inv-line-desc">
				<td></td>
				<td colspan="3"><i>{{ $LANG['description'] ?? 'Description' }}: </i>{{ $invoiceItem['description'] ?? '' }}</td>
			</tr>
			@endif
			<tr>
				<td></td>
				<td></td>
				<td class="text-end">{{ $preference['pref_currency_sign'] ?? '' }} {{ ($invoiceItem['unit_price'] ?? '')|siLocal_number }}</td>
				<td class="text-end">{{ $preference['pref_currency_sign'] ?? '' }} {{ ($invoiceItem['total'] ?? '')|siLocal_number }}</td>
			</tr>
			@endforeach
		</tbody>
	</table>
	@endif

	{{-- Line items: Type 1 total (description only) --}}
	@if(($invoice['type_id'] ?? null) == 1)
	<table class="si-tabler-inv-table">
		<thead>
			<tr><th>{{ $LANG['description'] ?? 'Description' }}</th></tr>
		</thead>
		<tbody>
			@foreach(($invoiceItems ?? []) as $invoiceItem)
			<tr><td>{{ ($invoiceItem['description'] ?? '') | outhtml }}</td></tr>
			@endforeach
		</tbody>
	</table>
	@endif

	@if((($invoice['type_id'] ?? null) == 2 && !empty($invoice['note'])) || (($invoice['type_id'] ?? null) == 3 && !empty($invoice['note'])))
	<div class="si-tabler-inv-notes">
		<b>{{ $LANG['notes'] ?? 'Notes' }}:</b><br />
		{{ ($invoice['note'] ?? '') | outhtml }}
		</div>
	@endif

	{{-- Totals: Subtotal, Tax, Total --}}
	<div class="si-tabler-inv-totals">
		<table>
			@if(($invoice_number_of_taxes ?? 0) > 0)
			<tr><td>{{ $LANG['sub_total'] ?? 'Subtotal' }}</td><td>@if(($invoice_number_of_taxes ?? 0) > 1)<u>@endif{{ $preference['pref_currency_sign'] ?? '' }} {{ ($invoice['gross'] ?? '')|siLocal_number }}@if(($invoice_number_of_taxes ?? 0) > 1)</u>@endif</td></tr>
			@endif
			@foreach(($invoice['tax_grouped'] ?? []) as $line)
				@if(($line['tax_amount'] ?? 0) != "0")
				<tr><td>{{ $line['tax_name'] ?? '' }}</td><td>{{ $preference['pref_currency_sign'] ?? '' }} {{ siLocal::number($line['tax_amount'] ?? 0) }}</td></tr>
				@endif
			@endforeach
			@if(($invoice_number_of_taxes ?? 0) > 1)
			<tr><td>{{ $LANG['tax_total'] ?? 'Tax total' }}</td><td><u>{{ $preference['pref_currency_sign'] ?? '' }} {{ ($invoice['total_tax'] ?? '')|siLocal_number }}</u></td></tr>
			@endif
			<tr class="total-row"><td><b>{{ $preference['pref_inv_wording'] ?? 'Invoice' }} {{ $LANG['amount'] ?? 'Amount' }}</b></td><td><span class="double_underline">{{ $preference['pref_currency_sign'] ?? '' }} {{ ($invoice['total'] ?? '')|siLocal_number }}</span></td></tr>
		</table>
	</div>

	{{-- Invoice details section --}}
	<div class="si-tabler-inv-detail-heading">{{ $preference['pref_inv_detail_heading'] ?? '' }}</div>
	<div class="si-tabler-inv-detail-line"><i>{{ ($preference['pref_inv_detail_line'] ?? '') | outhtml }}</i></div>
	<div class="si-tabler-inv-detail-line">{{ $preference['pref_inv_payment_method'] ?? '' }}</div>
	<div class="si-tabler-inv-detail-line">{{ $preference['pref_inv_payment_line1_name'] ?? '' }} {{ $preference['pref_inv_payment_line1_value'] ?? '' }}</div>
	<div class="si-tabler-inv-detail-line">{{ $preference['pref_inv_payment_line2_name'] ?? '' }} {{ $preference['pref_inv_payment_line2_value'] ?? '' }}</div>

	<div class="si-tabler-inv-footer">{{ ($biller['footer'] ?? '') | outhtml }}</div>

	<div>
		{online_payment_link
			type=$preference['include_online_payment'] business=$biller['paypal_business_name']
			item_name=$invoice['index_name'] invoice=$invoice['id']
			amount=$invoice['owing'] currency_code=$preference['currency_code']
			link_wording=$LANG['paypal_link']
			notify_url=$biller['paypal_notify_url'] return_url=$biller['paypal_return_url']
			domain_id = $invoice['domain_id'] include_image=true
			api_id = $biller['paymentsgateway_api_id']
			customer = $customer
		}
	</div>
</body>
</html>
