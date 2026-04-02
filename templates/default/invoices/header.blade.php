{{-- /*
	* Script: header.tpl
	* 	 Header file for invoice template
	*
	* License:
	*	 GPL v3 or above
	*
	* Website:
	*	http://www.simpleinvoices.org
	*/ --}}

	<input type="hidden" name="action" value="insert" />

	<div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
		<ul class="nav nav-tabs" role="tablist">
			<li class="nav-item" role="presentation">
				<a class="nav-link @if(($view ?? '') == 'itemised') active @endif" href="index.php?module=invoices&amp;view=itemised" role="tab"><i class="ti ti-list-details me-1"></i>{{ $LANG['itemised_style'] ?? '' }}</a>
			</li>
			<li class="nav-item" role="presentation">
				<a class="nav-link @if(($view ?? '') == 'total') active @endif" href="index.php?module=invoices&amp;view=total" role="tab"><i class="ti ti-receipt me-1"></i>{{ $LANG['total_style'] ?? '' }}</a>
			</li>
		</ul>
		<a class="cluetip nav-link nav-link-icon" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_invoice_types" title="{{ $LANG['invoice_type'] ?? '' }}"><i class="ti ti-help"></i></a>
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
				<input type="text" class="form-control validate[required,custom[date],length[0,10]] date-picker" name="date" id="date1"
					@if(get('date'))
						value="{{ get('date') }}"
					@else
						value="{{ date('Y-m-d') }}"
					@endif
				/>
			</div>
		</div>
	</div>
