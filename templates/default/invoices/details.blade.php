{{-- /*
* View: details (Blade)
* 	 Invoice details template
*
* License:
*	 GPL v3 or above
*
* Website:
*	http://www.simpleinvoices.org
*/ --}}

<div id="gmail_loading" class="gmailLoader" style="float:right; display: none;"><i class="ti ti-loader spinner me-1"></i> {{ $LANG['loading'] ?? '' }} ...</div>

<form name="frmpost" action="index.php?module=invoices&amp;view=save" method="post" class="needs-validation" novalidate>

<div class="card">
	<div class="card-body">

		{{-- Invoice header: number, date, biller, customer --}}
		<div class="row g-3 mb-3">
			<div class="col-md-2">
				<label class="form-label">{{ $preference['pref_inv_wording'] ?? ($LANG['invoice'] ?? '') }} {{ $LANG['number_short'] ?? '' }}</label>
				<div class="form-control-plaintext fw-bold">{{ $invoice['index_name'] ?? '' }}</div>
			</div>
			<div class="col-md-2">
				<label class="form-label">{{ $LANG['date_formatted'] ?? '' }}</label>
				<div class="input-icon">
					<span class="input-icon-addon"><i class="ti ti-calendar"></i></span>
					@if($invoice['id'] == null)
					<input type="text" class="form-control date-picker" name="date" id="date1"
						required pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}"
						value="{{ date('Y-m-d') }}" />
				@else
					<input type="text" class="form-control date-picker" name="date" id="date1"
							required pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}"
							value="{{ $invoice['calc_date'] ?? '' }}" />
					@endif
					<div class="invalid-feedback">{{ $LANG['required_field'] ?? 'Required' }}</div>
				</div>
			</div>
			<div class="col-md-4">
				<label class="form-label">{{ $LANG['biller'] ?? '' }}</label>
				@if($billers == null)
					<p class="text-muted mb-0"><em>{{ $LANG['no_billers'] ?? '' }}</em></p>
				@else
					<div class="input-group">
						<select name="biller_id" class="form-select" required>
							<option value=""></option>
							@foreach(($billers ?? []) as $biller)
								<option @if($biller['id'] == $invoice['biller_id']) selected @endif value="{{ $biller['id'] ?? '' }}" data-biller-invoice-prefix="{{ $biller['biller_invoice_prefix'] ?? '' }}">{{ $biller['name'] ?? '' }}</option>
							@endforeach
						</select>
						<button type="button" class="btn btn-outline-secondary si-add-biller-btn" title="{{ $LANG['add_biller'] ?? 'Add new biller' }}">
							<i class="ti ti-building-store"></i>
						</button>
					</div>
				@endif
			</div>
			<div class="col-md-4">
				<label class="form-label">{{ $LANG['customer'] ?? '' }}</label>
				@if($customers == null)
					<p class="text-muted mb-0"><em>{{ $LANG['no_customers'] ?? '' }}</em></p>
				@else
					<div class="input-group">
						<select name="customer_id" class="form-select" required>
							<option value=""></option>
							@foreach(($customers ?? []) as $customer)
								<option @if($customer['id'] == $invoice['customer_id']) selected @endif value="{{ $customer['id'] ?? '' }}">{{ $customer['name'] ?? '' }}</option>
							@endforeach
						</select>
						<button type="button" class="btn btn-outline-secondary si-add-customer-btn" title="{{ $LANG['add_customer'] ?? 'Add new customer' }}">
							<i class="ti ti-user-plus"></i>
						</button>
					</div>
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
									<option @if(($invoiceItems[0]['tax'][$taxIdx] ?? '') === ($taxOption['tax_id'] ?? '')) selected @endif value="{{ $taxOption['tax_id'] ?? '' }}">{{ $taxOption['tax_description'] ?? '' }} ({{ ($taxOption['type'] ?? '') === '$' ? '$' : '' }}{{ (float)($taxOption['tax_percentage'] ?? 0) }}{{ ($taxOption['type'] ?? '') !== '$' ? '%' : '' }})</option>
								@endforeach
							</select>
						@endfor
					</div>
				</div>
				@endif
			</div>

			{!! $customFields['1'] !!}
			{!! $customFields['2'] !!}
			{!! $customFields['3'] !!}
			{!! $customFields['4'] !!}
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
			{{-- Desktop column header --}}
			<div class="row g-2 d-none d-lg-flex mb-1">
				<div class="col-3 col-lg-1 small fw-medium text-secondary">{{ $LANG['quantity_short'] ?? '' }}</div>
				<div class="col col-lg small fw-medium text-secondary">{{ $LANG['description'] ?? '' }}</div>
				@for($tax_header = 0; $tax_header < (int)($defaults['tax_per_line_item'] ?? 0); $tax_header++)
					<div class="col col-lg-2 small fw-medium text-secondary">{{ $LANG['tax'] ?? '' }}@if(($defaults['tax_per_line_item'] ?? 0) > 1) {{ ($tax_header + 1) }}@endif</div>
				@endfor
				<div class="col col-lg-2 small fw-medium text-secondary">{{ $LANG['unit_price'] ?? '' }}</div>
				<div class="col-auto d-flex align-items-end">
					<div class="segmented-control segmented-control-sm invisible" aria-hidden="true">
						<span class="segmented-control-item"><span class="segmented-control-label"><i class="ti ti-chevron-down"></i></span></span>
						<span class="segmented-control-item"><span class="segmented-control-label"><i class="ti ti-chevrons-down"></i></span></span>
					</div>
				</div>
			</div>

			<div id="itemtable" class="mb-2">
				@foreach(($invoiceItems ?? []) as $line => $invoiceItem)
				@php $itemHasDesc = !empty($invoiceItem['description']); @endphp
				<div class="line_item si-line-item" id="row{{ $line }}">
					<div class="row g-2 align-items-end">
						<div class="col-3 col-lg-1">
							<label class="form-label d-lg-none small text-secondary mb-1">{{ $LANG['quantity_short'] ?? '' }}</label>
							<input type="hidden" id="delete{{ $line }}" name="delete{{ $line }}" />
							<input
								type="text"
								name="quantity{{ $line }}"
								id="quantity{{ $line }}"
								value="{{ siLocal::number_trim($invoiceItem['quantity'] ?? 0) }}"
								class="form-control text-end"
							/>
							<input type="hidden" name="line_item{{ $line }}" id="line_item{{ $line }}" value="{{ $invoiceItem['id'] ?? '' }}" />
						</div>
						<div class="col col-lg">
							<label class="form-label d-lg-none small text-secondary mb-1">{{ $LANG['description'] ?? '' }}</label>
							@if($products == null)
								<p class="text-muted mb-0"><em>{{ $LANG['no_products'] ?? '' }}</em></p>
							@else
								<div class="input-group si-product-input-group">
									<select
										name="products{{ $line }}"
										id="products{{ $line }}"
										rel="{{ $line }}"
										class="form-select product_change"
									>
										@foreach(($products ?? []) as $product)
											@if($product['id'] == $invoiceItem['product_id'])
												<option value="{{ $product['id'] }}" selected>{{ $product['description'] }}</option>
												@break
											@endif
										@endforeach
									</select>
									<button type="button" class="btn btn-outline-secondary si-add-product-row-btn" title="{{ $LANG['add_product'] ?? 'Add new product' }}"><i class="ti ti-plus"></i></button>
								</div>
							@endif
						</div>
						{{-- Mobile line break: taxes + price wrap to a second line below qty+product --}}
						<div class="w-100 d-lg-none si-mobile-row-break"></div>
						@for($taxIdx = 0; $taxIdx < (int)($defaults['tax_per_line_item'] ?? 0); $taxIdx++)
						<div class="col col-lg-2">
							<label class="form-label d-lg-none small text-secondary mb-1">{{ $LANG['tax'] ?? '' }}@if(($defaults['tax_per_line_item'] ?? 0) > 1) {{ ($taxIdx + 1) }}@endif</label>
							<select
								id="tax_id[{{ $line }}][{{ $taxIdx }}]"
								name="tax_id[{{ $line }}][{{ $taxIdx }}]"
								class="form-select"
							>
								<option value=""></option>
								@foreach(($taxes ?? []) as $taxOption)
									<option @if(($invoiceItem['tax'][$taxIdx] ?? '') === ($taxOption['tax_id'] ?? '')) selected @endif value="{{ $taxOption['tax_id'] ?? '' }}">{{ $taxOption['tax_description'] ?? '' }} ({{ ($taxOption['type'] ?? '') === '$' ? '$' : '' }}{{ (float)($taxOption['tax_percentage'] ?? 0) }}{{ ($taxOption['type'] ?? '') !== '$' ? '%' : '' }})</option>
								@endforeach
							</select>
						</div>
						@endfor
						<div class="col col-lg-2 si-unit-price-col">
							<label class="form-label d-lg-none small text-secondary mb-1">{{ $LANG['unit_price'] ?? '' }}</label>
							<input
								id="unit_price{{ $line }}"
								name="unit_price{{ $line }}"
								value="{{ siLocal::number_formatted($invoiceItem['unit_price'] ?? 0) }}"
								class="form-control text-end"
							/>
						</div>
						<div class="col-auto d-flex align-items-end">
							<div class="segmented-control segmented-control-sm">
								<label class="segmented-control-item si-expand-desc" title="{{ $LANG['description'] ?? '' }}">
									<input type="checkbox" class="segmented-control-input" {{ $itemHasDesc ? 'checked' : '' }}>
									<span class="segmented-control-label"><i class="ti {{ $itemHasDesc ? 'ti-chevron-up' : 'ti-chevron-down' }}"></i></span>
								</label>
								@if($line == 0)
								<label class="segmented-control-item si-toggle-all-desc" title="{{ $LANG['show_all'] ?? '' }}">
									<input type="radio" class="segmented-control-input" {{ $showDetailsInitially ? 'checked' : '' }}>
									<span class="segmented-control-label"><i class="ti {{ $showDetailsInitially ? 'ti-chevrons-up' : 'ti-chevrons-down' }}"></i></span>
								</label>
								@else
								<label class="segmented-control-item trash_link_edit" id="trash_link_edit{{ $line }}" rel="{{ $line }}" title="{{ $LANG['delete_line_item'] ?? '' }}">
									<input type="radio" class="segmented-control-input">
									<span class="segmented-control-label"><i id="delete_image{{ $line }}" class="ti ti-trash"></i></span>
								</label>
								@endif
							</div>
						</div>
					</div>
					{!! $invoiceItem['html'] !!}
					<div class="row g-2 details {{ $itemHasDesc ? '' : 'si_hide' }} mt-1">
						<div class="col-12 col-lg">
							<textarea class="form-control form-control-sm detail-editor" name="description{{ $line }}" id="description{{ $line }}" rows="2">{!! $invoiceItem['description'] !!}</textarea>
						</div>
					</div>
				</div>
				@endforeach
			</div>

			<div class="mb-4">
				<div class="segmented-control">
					<label class="segmented-control-item add_line_item">
						<input type="radio" class="segmented-control-input">
						<span class="segmented-control-label"><i class="ti ti-plus me-1"></i>{{ $LANG['add_new_row'] ?? '' }}</span>
					</label>
				</div>
			</div>

			{{-- Custom fields --}}
			{!! $customFields['1'] !!}
			{!! $customFields['2'] !!}
			{!! $customFields['3'] !!}
			{!! $customFields['4'] !!}
			@showCustomFields(4, get('invoice'))

			{{-- Notes --}}
			<div class="mb-3">
				<label class="form-label">{{ $LANG['notes'] ?? '' }}</label>
				<textarea class="form-control editor" name="note" rows="4">{{ $invoice['note'] }}</textarea>
			</div>

		@endif

		{{-- Preference, currency, payment terms, due date --}}
		<div class="row g-3">
			<div class="col-12">
				@include('templates.default.partials.invoice_preference_field', [
					'selectedPrefId'      => $invoice['preference_id'] ?? $defaults['preference'] ?? '',
					'currentCurrencySign' => $invoice['currency_sign'] ?? '',
					'currentCurrencyCode' => $invoice['denorm_currency_code'] ?? $invoice['currency_code'] ?? '',
					'currentCurrencyId'   => $invoice['currency_id'] ?? '',
					'selectedTermId'      => $invoice['payment_term_id'] ?? '',
					'calcDueDate'         => $invoice['calc_due_date'] ?? '',
				])
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

@include('templates.default.invoices.modal_add_product')
@include('templates.default.invoices.modal_add_customer')
@include('templates.default.invoices.modal_add_biller')
