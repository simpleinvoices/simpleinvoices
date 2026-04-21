{{-- /*
* View: itemised (Blade)
* 	 Itemised invoice template
*
* License:
*	 GPL v3 or above
*
* Website:
*	http://www.simpleinvoices.org
*/ --}}

<form name="frmpost" action="index.php?module=invoices&amp;view=save" method="post" class="needs-validation" novalidate onsubmit="return frmpost_Validator(this)">

	<div id="gmail_loading" class="gmailLoader" style="float:right; display: none;"><i class="ti ti-loader spinner me-1"></i> {{ $LANG['loading'] ?? '' }} ...</div>


@php
    $inv_hasBillers   = !empty($billers);
    $inv_hasCustomers = !empty($customers);
    $inv_hasProducts  = !empty($products);
@endphp

@if($first_run_wizard == true)

<div class="card mb-3">
    <div class="card-status-top bg-primary"></div>
    <div class="card-header">
        <h3 class="card-title"><i class="ti ti-rocket me-2"></i>{{ $LANG['getting_started'] ?? '' }}</h3>
    </div>
    <div class="card-body">
        <p class="text-secondary">{{ $LANG['first_run_intro'] ?? '' }}</p>
        <div class="row g-3">
            <div class="col-sm-6 col-lg-3">
                <div class="card @if($inv_hasBillers) border-success @endif">
                    <div class="card-body d-flex align-items-center gap-3">
                        <span class="avatar @if($inv_hasBillers) bg-success-lt @else bg-primary-lt @endif rounded">
                            <i class="ti ti-building-store"></i>
                        </span>
                        <div>
                            <div class="fw-bold">{{ $LANG['biller'] ?? '' }}</div>
                            @if($inv_hasBillers)
                                <span class="text-success"><i class="ti ti-check"></i> {{ $LANG['done'] ?? '' }}</span>
                            @else
                                <a href="index.php?module=billers&amp;view=add" class="text-primary">{{ $LANG['add_new_biller'] ?? '' }}</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card @if($inv_hasCustomers) border-success @endif">
                    <div class="card-body d-flex align-items-center gap-3">
                        <span class="avatar @if($inv_hasCustomers) bg-success-lt @else bg-primary-lt @endif rounded">
                            <i class="ti ti-users"></i>
                        </span>
                        <div>
                            <div class="fw-bold">{{ $LANG['customer'] ?? '' }}</div>
                            @if($inv_hasCustomers)
                                <span class="text-success"><i class="ti ti-check"></i> {{ $LANG['done'] ?? '' }}</span>
                            @else
                                <a href="index.php?module=customers&amp;view=add" class="text-primary">{{ $LANG['customer_add'] ?? '' }}</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card @if($inv_hasProducts) border-success @endif">
                    <div class="card-body d-flex align-items-center gap-3">
                        <span class="avatar @if($inv_hasProducts) bg-success-lt @else bg-primary-lt @endif rounded">
                            <i class="ti ti-package"></i>
                        </span>
                        <div>
                            <div class="fw-bold">{{ $LANG['product'] ?? '' }}</div>
                            @if($inv_hasProducts)
                                <span class="text-success"><i class="ti ti-check"></i> {{ $LANG['done'] ?? '' }}</span>
                            @else
                                <a href="index.php?module=products&amp;view=add" class="text-primary">{{ $LANG['add_new_product'] ?? '' }}</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card @if(!empty($inv_hasInvoices)) border-success @endif">
                    <div class="card-body d-flex align-items-center gap-3">
                        <span class="avatar @if(!empty($inv_hasInvoices)) bg-success-lt @else bg-primary-lt @endif rounded">
                            <i class="ti ti-file-invoice"></i>
                        </span>
                        <div>
                            <div class="fw-bold">{{ $LANG['invoice'] ?? '' }}</div>
                            @if(!empty($inv_hasInvoices))
                                <span class="text-success"><i class="ti ti-check"></i> {{ $LANG['done'] ?? '' }}</span>
                            @else
                                <a href="index.php?module=invoices&amp;view=itemised" class="text-primary">{{ $LANG['create_invoice'] ?? '' }}</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@else

<div class="card">
	<div class="card-body">

		@include(str_replace('/', '.', rtrim($path ?? '', '/')) . '.header')

		{{-- Line items --}}
		{{-- Desktop column header --}}
		<div class="row g-2 d-none d-lg-flex mb-1">
			<div class="col-3 col-lg-1 small fw-medium text-secondary">{{ $LANG['quantity'] ?? '' }}</div>
			<div class="col col-lg small fw-medium text-secondary">{{ $LANG['item'] ?? '' }}</div>
			@for($tax_header = 0; $tax_header < (int)($defaults['tax_per_line_item'] ?? 0); $tax_header++)
				<div class="col col-lg-2 small fw-medium text-secondary">{{ $LANG['tax'] ?? '' }}@if(($defaults['tax_per_line_item'] ?? 0) > 1) {{ ($tax_header + 1) }}@endif</div>
			@endfor
			<div class="col col-lg-2 small fw-medium text-secondary">{{ $LANG['unit_price'] ?? '' }}</div>
			<div class="col-auto d-flex align-items-end">
				<div class="segmented-control invisible" aria-hidden="true">
					<span class="segmented-control-item"><span class="segmented-control-label"><i class="ti ti-chevron-down"></i></span></span>
					<span class="segmented-control-item"><span class="segmented-control-label"><i class="ti ti-chevrons-down"></i></span></span>
				</div>
			</div>
		</div>

		<div id="itemtable" class="mb-2">
			@foreach(($dynamic_line_items ?? []) as $line)
			<div class="line_item si-line-item" id="row{{ $line }}">
				<div class="row g-2 align-items-end">
					<div class="col-3 col-lg-1">
						<label class="form-label d-lg-none small text-secondary mb-1">{{ $LANG['quantity'] ?? '' }}</label>
						<input
							type="text"
							name="quantity{{ $line }}"
							id="quantity{{ $line }}"
							class="form-control"
							@if($line == '0') required @endif
							@if(get('quantity' . $line)) value="{{ get('quantity' . $line) }}" @endif
						/>
					</div>
					<div class="col col-lg">
						<label class="form-label d-lg-none small text-secondary mb-1">{{ $LANG['item'] ?? '' }}</label>
						@if($products == null)
							<p class="text-muted mb-0"><em>{{ $LANG['no_products'] ?? '' }}</em></p>
						@else
							<div class="input-group si-product-input-group">
								<select
									id="products{{ $line }}"
									name="products{{ $line }}"
									rel="{{ $line }}"
									class="form-select product_change"
									@if($line == '0') required @endif
								>
									<option value=""></option>
									@foreach(($products ?? []) as $product)
										@if($product['id'] == ((get())['product'][$line] ?? null))
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
								<option
									value="{{ $taxOption['tax_id'] ?? '' }}"
									@if(($taxOption['tax_id'] ?? '') == ((get())['tax'][$line][$taxIdx] ?? null)) selected @endif
								>{{ $taxOption['tax_description'] ?? '' }}</option>
							@endforeach
						</select>
					</div>
					@endfor
					<div class="col col-lg-2 si-unit-price-col">
						<label class="form-label d-lg-none small text-secondary mb-1">{{ $LANG['unit_price'] ?? '' }}</label>
						<input
							id="unit_price{{ $line }}"
							name="unit_price{{ $line }}"
							class="form-control"
							@if($line == '0') required @endif
							@if(get('unit_price' . $line)) value="{{ get('unit_price' . $line) }}" @else value="" @endif
						/>
					</div>
					<div class="col-auto d-flex align-items-end">
						<div class="segmented-control">
							<label class="segmented-control-item si-expand-desc" title="{{ $LANG['description'] ?? '' }}">
								<input type="checkbox" class="segmented-control-input">
								<span class="segmented-control-label"><i class="ti ti-chevron-down"></i></span>
							</label>
							@if($line == 0)
							<label class="segmented-control-item si-toggle-all-desc" title="{{ $LANG['show_all'] ?? '' }}">
								<input type="radio" class="segmented-control-input">
								<span class="segmented-control-label"><i class="ti ti-chevrons-down"></i></span>
							</label>
							@else
							<label class="segmented-control-item trash_link" id="trash_link{{ $line }}" rel="{{ $line }}" title="{{ $LANG['delete_row'] ?? '' }}">
								<input type="radio" class="segmented-control-input">
								<span class="segmented-control-label"><i class="ti ti-trash"></i></span>
							</label>
							@endif
						</div>
					</div>
				</div>
				<div class="row g-2 details si_hide mt-1">
					<div class="col-12 col-lg">
						<textarea class="form-control detail-editor" name="description{{ $line }}" id="description{{ $line }}" rows="1"></textarea>
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
		{!! $show_custom_field['1'] !!}
		{!! $show_custom_field['2'] !!}
		{!! $show_custom_field['3'] !!}
		{!! $show_custom_field['4'] !!}
		@showCustomFields(4, '')

		{{-- Notes and preference --}}
		<div class="row g-3">
			<div class="col-12">
				<label class="form-label">{{ $LANG['notes'] ?? '' }}</label>
				<textarea class="form-control editor" name="note" rows="4">{{ get('note') }}</textarea>
			</div>
			<div class="col-12">
				@include('templates.default.partials.invoice_preference_field', [
					'selectedPrefId' => $defaults['preference'] ?? '',
					'selectedTermId' => '',
					'calcDueDate'    => '',
				])
				<a class="cluetip text-secondary small mt-1 d-inline-block" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_invoice_custom_fields" title="{{ $LANG['want_more_fields'] ?? '' }}"><i class="ti ti-help"></i></a>
			</div>
		</div>

		<input type="hidden" id="max_items" name="max_items" value="{{ $line }}" />
		<input type="hidden" name="type" value="2" />

	</div>
	<div class="card-footer">
		<div class="d-flex">
			<a href="./index.php?module=invoices&amp;view=manage" class="btn btn-link">{{ $LANG['cancel'] ?? '' }}</a>
			<button type="submit" class="btn btn-primary ms-auto invoice_save" name="submit" value="{{ $LANG['save'] ?? '' }}"><i class="ti ti-check me-1"></i>{{ $LANG['save'] ?? '' }}</button>
		</div>
	</div>
</div>

</form>

@include('templates.default.invoices.modal_add_product')
@include('templates.default.invoices.modal_add_customer')
@include('templates.default.invoices.modal_add_biller')

@endif
