{{-- /*
* Script: itemised.tpl
* 	 Itemised invoice template
*
* License:
*	 GPL v3 or above
*
* Website:
*	http://www.simpleinvoices.org
*/ --}}

<form name="frmpost" action="index.php?module=invoices&amp;view=save" method="post" onsubmit="return frmpost_Validator(this)">

	<div id="gmail_loading" class="gmailLoader" style="float:right; display: none;"><i class="ti ti-loader spinner me-1"></i> {{ $LANG['loading'] ?? '' }} ...</div>


@if($first_run_wizard == true)

	<div class="si_message">
		{{ $LANG['before_starting'] ?? '' }}
	</div>

	<div class="card">
		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-vcenter">
					@if($billers == null)
						<tr>
							<th>{{ $LANG['setup_as_biller'] ?? '' }}</th>
							<td><a href="./index.php?module=billers&amp;view=add" class="btn btn-primary"><i class="ti ti-building-store me-1"></i>{{ $LANG['add_new_biller'] ?? '' }}</a></td>
						</tr>
					@endif
					@if($customers == null)
						<tr>
							<th>{{ $LANG['setup_add_customer'] ?? '' }}</th>
							<td><a href="./index.php?module=customers&amp;view=add" class="btn btn-primary"><i class="ti ti-users me-1"></i>{{ $LANG['customer_add'] ?? '' }}</a></td>
						</tr>
					@endif
					@if($products == null)
						<tr>
							<th>{{ $LANG['setup_add_products'] ?? '' }}</th>
							<td><a href="./index.php?module=products&amp;view=add" class="btn btn-primary"><i class="ti ti-package me-1"></i>{{ $LANG['add_new_product'] ?? '' }}</a></td>
						</tr>
					@endif
					@if($taxes == null)
						<tr>
							<th>{{ $LANG['setup_add_taxrate'] ?? '' }}</th>
							<td><a href="index.php?module=tax_rates&amp;view=add" class="btn btn-primary"><i class="ti ti-receipt-tax me-1"></i>{{ $LANG['add_new_tax_rate'] ?? '' }}</a></td>
						</tr>
					@endif
					@if($preferences == null)
						<tr>
							<th>{{ $LANG['setup_add_inv_pref'] ?? '' }}</th>
							<td><a href="./index.php?module=preferences&amp;view=add" class="btn btn-primary"><i class="ti ti-file-text me-1"></i>{{ $LANG['add_new_preference'] ?? '' }}</a></td>
						</tr>
					@endif
				</table>
			</div>
		</div>
	</div>

@else

<div class="card">
	<div class="card-body">

		@include(str_replace('/', '.', rtrim($path ?? '', '/')) . '.header')

		{{-- Line items --}}
		{{-- Desktop column header --}}
		<div class="row g-2 d-none d-lg-flex mb-1 px-1">
			<div class="col-auto si-del-col-hdr"></div>
			<div class="col-3 col-lg-1 small fw-medium text-secondary">{{ $LANG['quantity'] ?? '' }}</div>
			<div class="col col-lg small fw-medium text-secondary">{{ $LANG['item'] ?? '' }}</div>
			@for($tax_header = 0; $tax_header < (int)($defaults['tax_per_line_item'] ?? 0); $tax_header++)
				<div class="col col-lg-2 small fw-medium text-secondary">{{ $LANG['tax'] ?? '' }}@if(($defaults['tax_per_line_item'] ?? 0) > 1) {{ ($tax_header + 1) }}@endif</div>
			@endfor
			<div class="col col-lg-2 small fw-medium text-secondary text-end">{{ $LANG['unit_price'] ?? '' }}</div>
		</div>

		<div id="itemtable" class="mb-2">
			@foreach(($dynamic_line_items ?? []) as $line)
			<div class="line_item si-line-item" id="row{{ $line }}">
				<div class="row g-2 align-items-end">
					<div class="col-auto si-del-col">
						@if($line == 0)
							<span class="text-muted" style="display:inline-block;width:1.75rem;"></span>
						@else
							<a
								id="trash_link{{ $line }}"
								class="trash_link btn btn-icon btn-sm btn-outline-danger"
								title="{{ $LANG['delete_row'] ?? '' }}"
								rel="{{ $line }}"
								href="#"
							><i class="ti ti-trash"></i></a>
						@endif
					</div>
					<div class="col-3 col-lg-1">
						<label class="form-label d-lg-none small text-secondary mb-1">{{ $LANG['quantity'] ?? '' }}</label>
						<input
							type="text"
							name="quantity{{ $line }}"
							id="quantity{{ $line }}"
							class="form-control form-control-sm text-end @if($line == '0')validate[required]@endif"
							@if(get('quantity' . $line)) value="{{ get('quantity' . $line) }}" @endif
						/>
					</div>
					<div class="col col-lg">
						<label class="form-label d-lg-none small text-secondary mb-1">{{ $LANG['item'] ?? '' }}</label>
						@if($products == null)
							<p class="text-muted mb-0"><em>{{ $LANG['no_products'] ?? '' }}</em></p>
						@else
							<select
								id="products{{ $line }}"
								name="products{{ $line }}"
								rel="{{ $line }}"
								class="form-select form-select-sm @if($line == '0')validate[required]@endif product_change"
							>
								<option value=""></option>
								@foreach(($products ?? []) as $product)
									<option
										@if($product['id'] == ((get())['product'][$line] ?? null)) selected @endif
										value="{{ $product['id'] ?? '' }}"
									>{{ $product['description'] ?? '' }}</option>
								@endforeach
							</select>
						@endif
					</div>
					@for($taxIdx = 0; $taxIdx < (int)($defaults['tax_per_line_item'] ?? 0); $taxIdx++)
					<div class="col col-lg-2">
						<label class="form-label d-lg-none small text-secondary mb-1">{{ $LANG['tax'] ?? '' }}@if(($defaults['tax_per_line_item'] ?? 0) > 1) {{ ($taxIdx + 1) }}@endif</label>
						<select
							id="tax_id[{{ $line }}][{{ $taxIdx }}]"
							name="tax_id[{{ $line }}][{{ $taxIdx }}]"
							class="form-select form-select-sm"
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
					<div class="col col-lg-2">
						<label class="form-label d-lg-none small text-secondary mb-1">{{ $LANG['unit_price'] ?? '' }}</label>
						<input
							id="unit_price{{ $line }}"
							name="unit_price{{ $line }}"
							class="form-control form-control-sm text-end @if($line == '0')validate[required]@endif"
							@if(get('unit_price' . $line)) value="{{ get('unit_price' . $line) }}" @else value="" @endif
						/>
					</div>
				</div>
				<div class="row g-2 details si_hide mt-1">
					<div class="col-auto si-del-col d-none d-lg-block"></div>
					<div class="col-12 col-lg">
						<textarea class="form-control form-control-sm detail" name="description{{ $line }}" id="description{{ $line }}" rows="2"></textarea>
					</div>
				</div>
			</div>
			@endforeach
		</div>

		<div class="btn-list mb-4">
			<a href="#" class="add_line_item btn btn-outline-primary btn-sm"><i class="ti ti-plus me-1"></i>{{ $LANG['add_new_row'] ?? '' }}</a>
			<a href="#" class="show-details btn btn-outline-secondary btn-sm" onclick="document.querySelectorAll('.details').forEach(function(e){e.classList.remove('si_hide');});document.querySelectorAll('.show-details').forEach(function(e){e.classList.add('si_hide');});return false;"><i class="ti ti-eye me-1"></i>{{ $LANG['show_details'] ?? '' }}</a>
			<a href="#" class="details si_hide btn btn-outline-secondary btn-sm" onclick="document.querySelectorAll('.details').forEach(function(e){e.classList.add('si_hide');});document.querySelectorAll('.show-details').forEach(function(e){e.classList.remove('si_hide');});return false;"><i class="ti ti-eye-off me-1"></i>{{ $LANG['hide_details'] ?? '' }}</a>
		</div>

		{{-- Custom fields --}}
		{{ $show_custom_field['1'] }}
		{{ $show_custom_field['2'] }}
		{{ $show_custom_field['3'] }}
		{{ $show_custom_field['4'] }}
		@showCustomFields(4, '')

		{{-- Notes and preference --}}
		<div class="row g-3">
			<div class="col-12">
				<label class="form-label">{{ $LANG['notes'] ?? '' }}</label>
				<textarea class="form-control editor" name="note" rows="4">{{ get('note') }}</textarea>
			</div>
			<div class="col-md-6">
				<label class="form-label">{{ $LANG['inv_pref'] ?? '' }}
					<a class="cluetip ms-1" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_invoice_custom_fields" title="{{ $LANG['want_more_fields'] ?? '' }}"><i class="ti ti-help"></i></a>
				</label>
				@if($preferences == null)
					<p class="text-muted mb-0"><em>{{ $LANG['no_preferences'] ?? '' }}</em></p>
				@else
					<select name="preference_id" class="form-select">
						@foreach(($preferences ?? []) as $preference)
							<option @if(($preference['pref_id'] ?? '') == ($defaults['preference'] ?? '')) selected @endif value="{{ $preference['pref_id'] ?? '' }}">{{ $preference['pref_description'] ?? '' }}</option>
						@endforeach
					</select>
				@endif
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

@endif
