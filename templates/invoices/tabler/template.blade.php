<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<title>{{ $preference['pref_inv_wording'] ?? 'Invoice' }} {{ $LANG['number_short'] ?? '' }}: {{ $invoice['index_id'] ?? '' }}</title>
	@if(!empty($css_inline))
	<style type="text/css">{{ $css_inline }}</style>
	@else
	<link rel="stylesheet" type="text/css" href="{{ $css|urlsafe }}" media="all" />
	@endif
</head>
<body class="si-tabler-inv">
	<div class="si-tabler-inv-card">
		<table class="si-tabler-inv-header-table">
			<tr>
				<td class="si-tabler-inv-header-brand">
					@if(!empty($logo))
					<img src="{{ $logo|urlsafe }}" alt="" class="si-tabler-inv-logo" />
					@else
					<span class="si-tabler-inv-title">{{ $preference['pref_inv_heading'] ?? '' }}</span>
					@endif
				</td>
				<td class="si-tabler-inv-header-summary">
					<div class="si-tabler-inv-kicker">{{ $preference['pref_inv_heading'] ?? ($preference['pref_inv_wording'] ?? 'Invoice') }}</div>
					<h1 class="si-tabler-inv-h1"># {{ $preference['pref_inv_wording'] ?? 'Invoice' }} {{ $invoice['index_id'] ?? '' }}</h1>
					<table class="si-tabler-inv-summary-table">
						<tr>
							<td>{{ $preference['pref_inv_wording'] ?? 'Invoice' }} {{ $LANG['number_short'] ?? 'No.' }}</td>
							<td class="text-end">{{ $invoice['index_id'] ?? '' }}</td>
						</tr>
						<tr>
							<td>{{ $preference['pref_inv_wording'] ?? 'Invoice' }} {{ $LANG['date'] ?? 'Date' }}</td>
							<td class="text-end">{{ $invoice['date'] ?? '' }}</td>
						</tr>
						@if(!empty($invoice['custom_field1']))
						<tr>
							<td>{{ $customFieldLabels['invoice_cf1'] ?? '' }}</td>
							<td class="text-end">{{ $invoice['custom_field1'] ?? '' }}</td>
						</tr>
						@endif
						@if(!empty($invoice['custom_field2']))
						<tr>
							<td>{{ $customFieldLabels['invoice_cf2'] ?? '' }}</td>
							<td class="text-end">{{ $invoice['custom_field2'] ?? '' }}</td>
						</tr>
						@endif
						@if(!empty($invoice['custom_field3']))
						<tr>
							<td>{{ $customFieldLabels['invoice_cf3'] ?? '' }}</td>
							<td class="text-end">{{ $invoice['custom_field3'] ?? '' }}</td>
						</tr>
						@endif
						@if(!empty($invoice['custom_field4']))
						<tr>
							<td>{{ $customFieldLabels['invoice_cf4'] ?? '' }}</td>
							<td class="text-end">{{ $invoice['custom_field4'] ?? '' }}</td>
						</tr>
						@endif
					</table>
				</td>
			</tr>
		</table>

		<table class="si-tabler-inv-party-table">
			<tr>
				<td class="si-tabler-inv-party-cell si-tabler-inv-party-left" bgcolor="#f8fafc">
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
				</td>
				<td class="si-tabler-inv-party-spacer"></td>
				<td class="si-tabler-inv-party-cell" bgcolor="#f8fafc">
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
				</td>
			</tr>
		</table>

		{{-- Line items: Type 2 itemised – Tabler table: Product, Qnt, Unit, Amount --}}
		@if(($invoice['type_id'] ?? null) == 2)
		<table class="si-tabler-inv-table table-card">
			<colgroup>
				<col class="si-tabler-col-qty" />
				<col class="si-tabler-col-item" />
				<col class="si-tabler-col-unit" />
				<col class="si-tabler-col-amount" />
			</colgroup>
			<thead>
				<tr>
					<th class="si-tabler-col-qty" bgcolor="#f8fafc">{{ $LANG['quantity_short'] ?? 'Qnt' }}</th>
					<th bgcolor="#f8fafc">{{ $LANG['item'] ?? 'Product' }}</th>
					<th class="text-end si-tabler-col-unit" bgcolor="#f8fafc">{{ $LANG['unit_cost'] ?? 'Unit' }}</th>
					<th class="text-end si-tabler-col-amount" bgcolor="#f8fafc">{{ $LANG['price'] ?? 'Amount' }}</th>
				</tr>
			</thead>
		<tbody>
			@foreach(($invoiceItems ?? []) as $invoiceItem)
			<tr>
				<td class="si-tabler-qty-cell">{{ ($invoiceItem['quantity'] ?? '')|siLocal_number_trim }}</td>
				<td>{!! outhtml($invoiceItem['product']['description'] ?? '') !!}</td>
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
				<td colspan="3">{{ $LANG['description'] ?? 'Description' }}: {!! outhtml($invoiceItem['description'] ?? '') !!}</td>
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
		<table class="si-tabler-inv-table table-card">
			<colgroup>
				<col class="si-tabler-col-qty" />
				<col class="si-tabler-col-item" />
				<col class="si-tabler-col-unit" />
				<col class="si-tabler-col-amount" />
			</colgroup>
			<thead>
				<tr>
					<th class="si-tabler-col-qty" bgcolor="#f8fafc">{{ $LANG['quantity_short'] ?? 'Qnt' }}</th>
					<th bgcolor="#f8fafc">{{ $LANG['item'] ?? 'Product' }}</th>
					<th class="text-end si-tabler-col-unit" bgcolor="#f8fafc">{{ $LANG['unit_cost'] ?? 'Unit' }}</th>
					<th class="text-end si-tabler-col-amount" bgcolor="#f8fafc">{{ $LANG['price'] ?? 'Amount' }}</th>
				</tr>
			</thead>
		<tbody>
			@foreach(($invoiceItems ?? []) as $invoiceItem)
			<tr>
				<td class="si-tabler-qty-cell">{{ ($invoiceItem['quantity'] ?? '')|siLocal_number }}</td>
				<td>{!! outhtml($invoiceItem['product']['description'] ?? '') !!}</td>
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
				<td colspan="3"><i>{{ $LANG['description'] ?? 'Description' }}: </i>{!! outhtml($invoiceItem['description'] ?? '') !!}</td>
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
		<table class="si-tabler-inv-table table-card">
			<thead>
				<tr><th bgcolor="#f8fafc">{{ $LANG['description'] ?? 'Description' }}</th></tr>
			</thead>
			<tbody>
				@foreach(($invoiceItems ?? []) as $invoiceItem)
				<tr><td>{!! outhtml($invoiceItem['description'] ?? '') !!}</td></tr>
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

		{{-- Totals: Subtotal, Tax, Total Due – Tabler style table --}}
		<table class="si-tabler-inv-totals-layout">
			<tr>
				<td class="si-tabler-inv-totals-spacer"></td>
				<td class="si-tabler-inv-totals-wrap">
					<table class="si-tabler-inv-totals">
						@if(($invoice_number_of_taxes ?? 0) > 0)
						<tr><td>{{ $LANG['sub_total'] ?? 'Subtotal' }}</td><td class="text-end">@if(($invoice_number_of_taxes ?? 0) > 1)<u>@endif{{ $preference['pref_currency_sign'] ?? '' }} {{ ($invoice['gross'] ?? '')|siLocal_number }}@if(($invoice_number_of_taxes ?? 0) > 1)</u>@endif</td></tr>
						@endif
						@foreach(($invoice['tax_grouped'] ?? []) as $line)
							@if(($line['tax_amount'] ?? 0) != "0")
							<tr><td>{{ $line['tax_name'] ?? '' }}</td><td class="text-end">{{ $preference['pref_currency_sign'] ?? '' }} {{ siLocal::number($line['tax_amount'] ?? 0) }}</td></tr>
							@endif
						@endforeach
						@if(($invoice_number_of_taxes ?? 0) > 1)
						<tr><td>{{ $LANG['tax_total'] ?? 'Tax total' }}</td><td class="text-end"><u>{{ $preference['pref_currency_sign'] ?? '' }} {{ ($invoice['total_tax'] ?? '')|siLocal_number }}</u></td></tr>
						@endif
						<tr class="si-tabler-inv-total-due">
							<td>{{ $LANG['total'] ?? 'Total Due' }}</td>
							<td class="text-end">{{ $preference['pref_currency_sign'] ?? '' }} {{ ($invoice['total'] ?? '')|siLocal_number }}</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>

		{{-- Thank you message (Tabler style) --}}
		<div class="si-tabler-inv-thanks">
			Thank you very much for doing business with us. We look forward to working with you again!
		</div>

		{{-- Invoice details / payment --}}
		<div class="si-tabler-inv-detail-card" style="background-color:#f8fafc;border:1px solid #e2e8f0;">
			<div class="si-tabler-inv-detail-heading">{{ $preference['pref_inv_detail_heading'] ?? '' }}</div>
			<div class="si-tabler-inv-detail-line"><i>{{ ($preference['pref_inv_detail_line'] ?? '') | outhtml }}</i></div>
			<div class="si-tabler-inv-detail-line">{{ $preference['pref_inv_payment_method'] ?? '' }}</div>
			<div class="si-tabler-inv-detail-line">{{ $preference['pref_inv_payment_line1_name'] ?? '' }} {{ $preference['pref_inv_payment_line1_value'] ?? '' }}</div>
			<div class="si-tabler-inv-detail-line">{{ $preference['pref_inv_payment_line2_name'] ?? '' }} {{ $preference['pref_inv_payment_line2_value'] ?? '' }}</div>
		</div>

		<div class="si-tabler-inv-footer">{{ ($biller['footer'] ?? '') | outhtml }}</div>

		<div class="si-tabler-inv-payment-link">
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
	</div>
</body>
</html>
