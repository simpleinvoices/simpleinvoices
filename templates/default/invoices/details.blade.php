{{-- /*
* Script: details.tpl
* 	 Invoice details template
*
* License:
*	 GPL v3 or above
*
* Website:
*	http://www.simpleinvoices.org
*/ --}}

<div id="gmail_loading" class="gmailLoader" style="float:right; display: none;"><i class="ti ti-loader spinner me-1"></i> {{ $LANG['loading'] ?? '' }} ...</div>

<form name="frmpost" action="index.php?module=invoices&amp;view=save" method="post">

<div class="card">
	<div class="card-body">

		{{-- Invoice header: number, date, biller, customer --}}
		<div class="row g-3 mb-3">
			<div class="col-md-2">
				<label class="form-label">{{ $preference['pref_inv_wording'] ?? '' }} {{ $LANG['number_short'] ?? '' }}</label>
				<div class="form-control-plaintext fw-bold">{{ $invoice['index_id'] ?? '' }}</div>
			</div>
			<div class="col-md-2">
				<label class="form-label">{{ $LANG['date_formatted'] ?? '' }}</label>
				@if($invoice['id'] == null)
					<input type="text" class="form-control validate[required,custom[date],length[0,10]] date-picker" name="date" id="date1" value="{{ date('Y-m-d') }}" />
				@else
					<input type="text" class="form-control validate[required,custom[date],length[0,10]] date-picker" name="date" id="date1" value="{{ $invoice['calc_date'] ?? '' }}" />
				@endif
			</div>
			<div class="col-md-4">
				<label class="form-label">{{ $LANG['biller'] ?? '' }}</label>
				@if($billers == null)
					<p class="text-muted mb-0"><em>{{ $LANG['no_billers'] ?? '' }}</em></p>
				@else
					<select name="biller_id" class="form-select">
						@foreach(($billers ?? []) as $biller)
							<option @if($biller['id'] == $invoice['biller_id']) selected @endif value="{{ $biller['id'] ?? '' }}">{{ $biller['name'] ?? '' }}</option>
						@endforeach
					</select>
				@endif
			</div>
			<div class="col-md-4">
				<label class="form-label">{{ $LANG['customer'] ?? '' }}</label>
				@if($customers == null)
					<p class="text-muted mb-0"><em>{{ $LANG['no_customers'] ?? '' }}</em></p>
				@else
					<select name="customer_id" class="form-select">
						@foreach(($customers ?? []) as $customer)
							<option @if($customer['id'] == $invoice['customer_id']) selected @endif value="{{ $customer['id'] ?? '' }}">{{ $customer['name'] ?? '' }}</option>
						@endforeach
					</select>
				@endif
			</div>
		</div>

		@if($invoice['type_id'] == 1)

			{{-- Total invoice: description + gross total + tax --}}
			<div class="mb-3">
				<label class="form-label">{{ $LANG['description'] ?? '' }}</label>
				<textarea class="form-control editor" name="description0" rows="6">{{ $invoiceItems[0]['description'] ?? '' }}</textarea>
			</div>

			<div class="row g-3 mb-3">
				<div class="col-md-4">
					<label class="form-label">{{ $LANG['gross_total'] ?? '' }}</label>
					<input type="text" name="unit_price0" value="{{ siLocal::number_formatted($invoiceItems[0]['unit_price'] ?? 0) }}" class="form-control text-end" />
					<input type="hidden" name="id0" value="{{ $invoiceItems[0]['id'] ?? '' }}" />
					<input type="hidden" name="products0" value="{{ $invoiceItems[0]['product_id'] ?? '' }}" />
				</div>
				@if((int)($defaults['tax_per_line_item'] ?? 0) > 0)
				<div class="col-md-8">
					<label class="form-label">{{ $LANG['tax'] ?? '' }}</label>
					<div class="d-flex gap-2">
						@for($taxIdx = 0; $taxIdx < (int)($defaults['tax_per_line_item'] ?? 0); $taxIdx++)
							<select
								id="tax_id[0][{{ $taxIdx }}]"
								name="tax_id[0][{{ $taxIdx }}]"
								class="form-select"
							>
								<option value=""></option>
								@foreach(($taxes ?? []) as $taxOption)
									<option @if(($invoiceItems[0]['tax'][$taxIdx] ?? '') === ($taxOption['tax_id'] ?? '')) selected @endif value="{{ $taxOption['tax_id'] ?? '' }}">{{ $taxOption['tax_description'] ?? '' }}</option>
								@endforeach
							</select>
						@endfor
					</div>
				</div>
				@endif
			</div>

			{{ $customFields['1'] }}
			{{ $customFields['2'] }}
			{{ $customFields['3'] }}
			{{ $customFields['4'] }}
			@showCustomFields(4, get('invoice'))

		@endif

		@if($invoice['type_id'] == 2 || $invoice['type_id'] == 3)

			@php
				$showDetailsInitially = false;
				foreach (($invoiceItems ?? []) as $_item) {
					if (!empty($_item['description'])) { $showDetailsInitially = true; break; }
				}
			@endphp

			{{-- Line items --}}
			<div class="table-responsive mb-2">
				<table id="itemtable" class="table table-vcenter table-sm si_invoice_items">
					<thead>
						<tr class="table-light">
							<th style="width:2rem"></th>
							<th style="width:6rem">{{ $LANG['quantity_short'] ?? '' }}</th>
							<th>{{ $LANG['description'] ?? '' }}</th>
							@for($tax_header = 0; $tax_header < (int)($defaults['tax_per_line_item'] ?? 0); $tax_header++)
								<th style="width:10rem">{{ $LANG['tax'] ?? '' }} @if(($defaults['tax_per_line_item'] ?? 0) > 1){{ ($tax_header + 1) }}@endif</th>
							@endfor
							<th style="width:9rem" class="text-end">{{ $LANG['unit_price'] ?? '' }}</th>
						</tr>
					</thead>

					@foreach(($invoiceItems ?? []) as $line => $invoiceItem)
					<tbody class="line_item" id="row{{ $line }}">
						<tr>
							<td>
								@if($line == 0)
									<span class="text-muted" style="display:inline-block;width:1.75rem;"></span>
								@else
									<a
										id="trash_link_edit{{ $line }}"
										class="trash_link_edit btn btn-icon btn-sm btn-outline-danger"
										title="{{ $LANG['delete_line_item'] ?? '' }}"
										href="#"
										rel="{{ $line }}"
									><i id="delete_image{{ $line }}" class="ti ti-trash"></i></a>
								@endif
							</td>
							<td>
								<input type="hidden" id="delete{{ $line }}" name="delete{{ $line }}" />
								<input
									type="text"
									name="quantity{{ $line }}"
									id="quantity{{ $line }}"
									value="{{ siLocal::number($invoiceItem['quantity'] ?? '') }}"
									class="form-control form-control-sm text-end"
								/>
								<input type="hidden" name="line_item{{ $line }}" id="line_item{{ $line }}" value="{{ $invoiceItem['id'] ?? '' }}" />
							</td>
							<td>
								@if($products == null)
									<p class="text-muted mb-0"><em>{{ $LANG['no_products'] ?? '' }}</em></p>
								@else
									<select
										name="products{{ $line }}"
										id="products{{ $line }}"
										rel="{{ $line }}"
										class="form-select form-select-sm product_change"
									>
										@foreach(($products ?? []) as $product)
											<option @if($product['id'] == $invoiceItem['product_id']) selected @endif value="{{ $product['id'] ?? '' }}">{{ $product['description'] ?? '' }}</option>
										@endforeach
									</select>
								@endif
							</td>
							@for($taxIdx = 0; $taxIdx < (int)($defaults['tax_per_line_item'] ?? 0); $taxIdx++)
							<td>
								<select
									id="tax_id[{{ $line }}][{{ $taxIdx }}]"
									name="tax_id[{{ $line }}][{{ $taxIdx }}]"
									class="form-select form-select-sm"
								>
									<option value=""></option>
									@foreach(($taxes ?? []) as $taxOption)
										<option @if(($invoiceItem['tax'][$taxIdx] ?? '') === ($taxOption['tax_id'] ?? '')) selected @endif value="{{ $taxOption['tax_id'] ?? '' }}">{{ $taxOption['tax_description'] ?? '' }}</option>
									@endforeach
								</select>
							</td>
							@endfor
							<td>
								<input
									id="unit_price{{ siLocal::number_clean($line) }}"
									name="unit_price{{ $line }}"
									value="{{ $invoiceItem['unit_price'] ?? '' }}"
									class="form-control form-control-sm text-end"
								/>
							</td>
						</tr>
						{!! $invoiceItem['html'] !!}
						<tr class="details {{ $showDetailsInitially ? '' : 'si_hide' }}">
							<td></td>
							<td colspan="{{ 3 + (int)($defaults['tax_per_line_item'] ?? 0) }}">
								<textarea class="form-control form-control-sm detail" name="description{{ $line }}" id="description{{ $line }}" rows="2">{{ $invoiceItem['description'] }}</textarea>
							</td>
						</tr>
					</tbody>
					@endforeach
				</table>
			</div>

			<div class="btn-list mb-4">
				<a href="#" class="add_line_item btn btn-outline-primary btn-sm"><i class="ti ti-plus me-1"></i>{{ $LANG['add_new_row'] ?? '' }}</a>
				<a href="#" class="show-details {{ $showDetailsInitially ? 'si_hide' : '' }} btn btn-outline-secondary btn-sm" onclick="document.querySelectorAll('.details').forEach(function(e){e.classList.remove('si_hide');});document.querySelectorAll('.show-details').forEach(function(e){e.classList.add('si_hide');});return false;"><i class="ti ti-eye me-1"></i>{{ $LANG['show_details'] ?? '' }}</a>
				<a href="#" class="details {{ $showDetailsInitially ? '' : 'si_hide' }} btn btn-outline-secondary btn-sm" onclick="document.querySelectorAll('.details').forEach(function(e){e.classList.add('si_hide');});document.querySelectorAll('.show-details').forEach(function(e){e.classList.remove('si_hide');});return false;"><i class="ti ti-eye-off me-1"></i>{{ $LANG['hide_details'] ?? '' }}</a>
			</div>

			{{-- Custom fields --}}
			{{ $customFields['1'] }}
			{{ $customFields['2'] }}
			{{ $customFields['3'] }}
			{{ $customFields['4'] }}
			@showCustomFields(4, get('invoice'))

			{{-- Notes --}}
			<div class="mb-3">
				<label class="form-label">{{ $LANG['notes'] ?? '' }}</label>
				<textarea class="form-control editor" name="note" rows="4">{{ $invoice['note'] }}</textarea>
			</div>

		@endif

		{{-- Preference (shared) --}}
		<div class="row g-3">
			<div class="col-md-6">
				<label class="form-label">{{ $LANG['inv_pref'] ?? '' }}</label>
				@if($preferences == null)
					<p class="text-muted mb-0"><em>{{ $LANG['no_preferences'] ?? '' }}</em></p>
				@else
					<select name="preference_id" class="form-select">
						@foreach(($preferences ?? []) as $preference)
							<option @if(($preference['pref_id'] ?? '') == ($invoice['preference_id'] ?? $defaults['preference'] ?? '')) selected @endif value="{{ $preference['pref_id'] }}">{{ $preference['pref_description'] }}</option>
						@endforeach
					</select>
				@endif
			</div>
		</div>

	</div>
	<div class="card-footer">
		<div class="d-flex">
			<a href="./index.php?module=invoices&amp;view=manage" class="btn btn-link">{{ $LANG['cancel'] ?? '' }}</a>
			<button type="submit" class="btn btn-primary ms-auto invoice_save" name="submit" value="{{ $LANG['save'] ?? '' }}"><i class="ti ti-check me-1"></i>{{ $LANG['save'] ?? '' }}</button>
		</div>
	</div>
</div>

@if($invoice['id'] == null)
	<input type="hidden" name="action" value="insert" />
@else
	<input type="hidden" name="id" value="{{ $invoice['id'] ?? '' }}" />
	<input type="hidden" name="action" value="edit" />
@endif
@if($invoice['type_id'] == 1)
	<input id="quantity0" type="hidden" value="1.00" name="quantity0" />
	<input id="line_item0" type="hidden" value="{{ $invoiceItems[0]['id'] }}" name="line_item0" />
@endif
<input type="hidden" name="type" value="{{ $invoice['type_id'] }}" />
<input type="hidden" name="op" value="insert_preference" />
<input type="hidden" id="max_items" name="max_items" value="{{ $lines }}" />

</form>
