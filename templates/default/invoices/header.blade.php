{{-- /*
	* View: header (Blade)
	* 	 Header file for invoice template
	*
	* License:
	*	 GPL v3 or above
	*
	* Website:
	*	http://www.simpleinvoices.org
	*/ --}}

	<input type="hidden" name="action" value="insert" />

	@php
		$siInvoiceSegItemised = (($subPageActive ?? '') === 'invoice_new_itemised') || (($view ?? '') === 'itemised');
		$siInvoiceSegTotal = (($subPageActive ?? '') === 'invoice_new_total') || (($view ?? '') === 'total');
	@endphp
	<div class="d-flex align-items-center flex-wrap gap-2 mb-3" role="group" @if(!empty($LANG['invoice_type'])) aria-label="{{ $LANG['invoice_type'] }}" @endif>
		<div class="segmented-control segmented-control-btn">
			<a class="segmented-control-item @if($siInvoiceSegItemised) active @endif" href="index.php?module=invoices&amp;view=itemised" @if($siInvoiceSegItemised) aria-current="page" @endif>
				<span class="segmented-control-label"><i class="ti ti-list-details me-1"></i>{{ $LANG['itemised_style'] ?? '' }}</span>
			</a>
			<a class="segmented-control-item @if($siInvoiceSegTotal) active @endif" href="index.php?module=invoices&amp;view=total" @if($siInvoiceSegTotal) aria-current="page" @endif>
				<span class="segmented-control-label"><i class="ti ti-receipt me-1"></i>{{ $LANG['total_style'] ?? '' }}</span>
			</a>
		</div>
		<a class="cluetip text-secondary" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_invoice_types" title="{{ $LANG['invoice_type'] ?? '' }}" aria-label="{{ $LANG['invoice_type'] ?? '' }}"><i class="ti ti-help"></i></a>
	</div>

	<div class="row g-3 mb-3">
		<div class="col-md-4">
			<label class="form-label">{{ $LANG['biller'] ?? '' }}</label>
			@if($billers == null)
				<p class="text-muted mb-0"><em>{{ $LANG['no_billers'] ?? '' }}</em></p>
			@else
				<select name="biller_id" class="form-select">
					@foreach(($billers ?? []) as $biller)
						<option @if($biller['id'] == $defaults->biller) selected @endif value="{{ $biller['id'] ?? '' }}">{{ $biller['name'] ?? '' }}</option>
					@endforeach
				</select>
			@endif
		</div>
		<div class="col-md-5">
			<label class="form-label">{{ $LANG['customer'] ?? '' }}</label>
			@if($customers == null)
				<p class="text-muted mb-0"><em>{{ $LANG['no_customers'] ?? '' }}</em></p>
			@else
				<select name="customer_id" class="form-select">
					@foreach(($customers ?? []) as $customer)
						<option @if($customer['id'] == $defaults->customer) selected @endif value="{{ $customer['id'] ?? '' }}">{{ $customer['name'] ?? '' }}</option>
					@endforeach
				</select>
			@endif
		</div>
		<div class="col-md-3">
			<label class="form-label">{{ $LANG['date_formatted'] ?? '' }}</label>
			<div class="input-icon">
				<span class="input-icon-addon"><i class="ti ti-calendar"></i></span>
				<input type="text" class="form-control date-picker" name="date" id="date1"
					required pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}"
					@if(get('date'))
						value="{{ get('date') }}"
					@else
						value="{{ date('Y-m-d') }}"
					@endif
				/>
				<div class="invalid-feedback">{{ $LANG['required_field'] ?? 'Required' }}</div>
			</div>
		</div>
	</div>
