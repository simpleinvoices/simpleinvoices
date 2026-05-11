{{-- /*
* View: total (Blade)
* 	 Total style invoice template
*
* License:
*	 GPL v3 or above
*
* Website:
*	http://www.simpleinvoices.org
*/ --}}

<form name="frmpost" action="index.php?module=invoices&amp;view=save" method="POST" class="needs-validation" novalidate>
<!--
<h3>{{ $LANG['inv'] ?? '' }} {{ $LANG['inv_total'] ?? '' }}</h3>
-->

@php
    $inv_hasBillers   = !empty($billers);
    $inv_hasCustomers = !empty($customers);
    $inv_hasProducts  = !empty($products);
@endphp

@if(isset($first_run_wizard) && $first_run_wizard == true)

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
<div class="si_invoice_form">

@include(str_replace('/', '.', rtrim($path ?? '', '/')) . '.header')

	<table id="itemtable" class="table table-vcenter si_invoice_items">
		<tr>
			<td class="si_invoice_notes" colspan="5">
				<h5>{{ $LANG['description'] ?? '' }}</h5>
				<textarea class="form-control editor" name="description" rows="10" cols="100" wrap="nowrap"></textarea>
			</td>
		</tr>
	</table>

@php
	$inv_tax_per_line = (int)($defaults['tax_per_line_item'] ?? 0);
	$inv_tax_field_count = ($inv_tax_per_line > 0) ? 1 : 0;
	if ($inv_tax_per_line > 0 && $taxes != null) {
		$inv_tax_field_count = $inv_tax_per_line;
	}
	$inv_totals_match_header = ($inv_tax_field_count === 1);
	$inv_totals_lg_cols = min(4, max(2, 2 + $inv_tax_field_count));
@endphp
	<div class="row g-3 mb-3 @if(!$inv_totals_match_header && $inv_tax_field_count !== 0) row-cols-1 row-cols-md-2 row-cols-lg-{{ $inv_totals_lg_cols }} @endif">
		<div class="col-12 @if($inv_totals_match_header) col-md-4 @elseif($inv_tax_field_count === 0) col-md-6 @else col @endif">
			<label class="form-label">{{ $LANG['gross_total'] ?? '' }}</label>
			<input type="text" name="unit_price" class="form-control" required />
			<div class="invalid-feedback">{{ $LANG['required_field'] ?? 'Required' }}</div>
		</div>
		@if($inv_tax_per_line > 0)
			@if($taxes == null)
				<div class="col-12 @if($inv_totals_match_header) col-md-5 @else col @endif">
					<label class="form-label">{{ $LANG['tax'] ?? '' }}</label>
					<p class="text-muted mb-0"><em>{{ $LANG['no_taxes'] ?? '' }}</em></p>
				</div>
			@else
				@for($taxIdx = 0; $taxIdx < $inv_tax_per_line; $taxIdx++)
				<div class="col-12 @if($inv_totals_match_header) col-md-5 @else col @endif">
					<label class="form-label">{{ $LANG['tax'] ?? '' }}@if($inv_tax_per_line > 1) {{ $taxIdx + 1 }}@endif</label>
					<select id="tax_id[0][{{ $taxIdx }}]" name="tax_id[0][{{ $taxIdx }}]" class="form-select">
						<option value=""></option>
					@foreach(($taxes ?? []) as $taxOption)
						<option @if(($taxOption['tax_id'] ?? '') == ($defaults['tax'] ?? '') && $taxIdx == 0) selected @endif value="{{ $taxOption['tax_id'] ?? '' }}">{{ $taxOption['tax_description'] ?? '' }}</option>
					@endforeach
					</select>
				</div>
				@endfor
			@endif
		@endif
	</div>

	<div class="row g-3 mb-3">
		<div class="col-12">
			@include('templates.default.partials.invoice_preference_field', [
				'selectedPrefId' => $defaults['preference'] ?? '',
				'selectedTermId' => '',
				'calcDueDate'    => '',
				'isNewInvoice'   => true,
			])
		</div>
	</div>

	{!! $show_custom_field['1'] !!}
	{!! $show_custom_field['2'] !!}
	{!! $show_custom_field['3'] !!}
	{!! $show_custom_field['4'] !!}

	<div class="mt-2">
		<a class="cluetip btn btn-outline-secondary" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_invoice_custom_fields" title="{{ $LANG['want_more_fields'] ?? '' }}"><i class="ti ti-help me-1"></i>{{ $LANG['want_more_fields'] ?? '' }}</a>
	</div>

	</div>
	</div>
	<div class="card-footer">
		<div class="d-flex">
			<a href="./index.php?module=invoices&amp;view=manage" class="btn btn-link">{{ $LANG['cancel'] ?? '' }}</a>
			<button type="submit" class="btn btn-primary ms-auto" name="submit" value="{{ $LANG['save'] ?? '' }}"><i class="ti ti-check me-1"></i>{{ $LANG['save'] ?? '' }}</button>
		</div>
	</div>
</div>

@endif

<input type="hidden" name="max_items" value="{{ $line }}" />
<input type="hidden" name="type" value="1" />

</form>

@include('templates.default.invoices.modal_add_customer')
@include('templates.default.invoices.modal_add_biller')
