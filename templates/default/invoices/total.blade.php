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

<form name="frmpost" action="index.php?module=invoices&amp;view=save" method="POST">
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
        <h3 class="card-title"><i class="ti ti-rocket me-2"></i>{{ $LANG['getting_started'] ?? 'Getting Started' }}</h3>
    </div>
    <div class="card-body">
        <p class="text-secondary">{{ $LANG['first_run_intro'] ?? 'Welcome! Complete these steps to start invoicing:' }}</p>
        <div class="row g-3">
            <div class="col-sm-6 col-lg-3">
                <div class="card @if($inv_hasBillers) border-success @endif">
                    <div class="card-body d-flex align-items-center gap-3">
                        <span class="avatar @if($inv_hasBillers) bg-success-lt @else bg-primary-lt @endif rounded">
                            <i class="ti ti-building-store"></i>
                        </span>
                        <div>
                            <div class="fw-bold">{{ $LANG['biller'] ?? 'Biller' }}</div>
                            @if($inv_hasBillers)
                                <span class="text-success"><i class="ti ti-check"></i> {{ $LANG['done'] ?? 'Done' }}</span>
                            @else
                                <a href="index.php?module=billers&amp;view=add" class="text-primary">{{ $LANG['add_new_biller'] ?? 'Add Biller' }}</a>
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
                            <div class="fw-bold">{{ $LANG['customer'] ?? 'Customer' }}</div>
                            @if($inv_hasCustomers)
                                <span class="text-success"><i class="ti ti-check"></i> {{ $LANG['done'] ?? 'Done' }}</span>
                            @else
                                <a href="index.php?module=customers&amp;view=add" class="text-primary">{{ $LANG['customer_add'] ?? 'Add Customer' }}</a>
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
                            <div class="fw-bold">{{ $LANG['product'] ?? 'Product' }}</div>
                            @if($inv_hasProducts)
                                <span class="text-success"><i class="ti ti-check"></i> {{ $LANG['done'] ?? 'Done' }}</span>
                            @else
                                <a href="index.php?module=products&amp;view=add" class="text-primary">{{ $LANG['add_new_product'] ?? 'Add Product' }}</a>
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
                            <div class="fw-bold">{{ $LANG['invoice'] ?? 'Invoice' }}</div>
                            @if(!empty($inv_hasInvoices))
                                <span class="text-success"><i class="ti ti-check"></i> {{ $LANG['done'] ?? 'Done' }}</span>
                            @else
                                <a href="index.php?module=invoices&amp;view=itemised" class="text-primary">{{ $LANG['create_invoice'] ?? 'Create Invoice' }}</a>
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

	<table class="si_invoice_bot">

		<tr class="si_invoice_total">
			<th class="">{{ $LANG['gross_total'] ?? '' }}</th>
			@for($tax_header = 0; $tax_header < (int)($defaults['tax_per_line_item'] ?? 0); $tax_header++)
				<th class="">{{ $LANG['tax'] ?? '' }} @if(($defaults['tax_per_line_item'] ?? 0) > 1){{ ($tax_header + 1) }}@endif </th>
			@endfor
			<th class="">{{ $LANG['inv_pref'] ?? '' }}</th>
		</tr>

		<tr class="si_invoice_total">
			<td><input type="text" name="unit_price" size="15" class="form-control validate[required]" /></td>
		@if($taxes == null )
			<td><p><em>{{ $LANG['no_taxes'] ?? '' }}</em></p></td>
		@else
			@for($taxIdx = 0; $taxIdx < (int)($defaults['tax_per_line_item'] ?? 0); $taxIdx++)
			<td>
				<select id="tax_id[0][{{ $taxIdx }}]" name="tax_id[0][{{ $taxIdx }}]" class="form-select">
					<option value=""></option>
				@foreach(($taxes ?? []) as $taxOption)
					<option @if(($taxOption['tax_id'] ?? '') == ($defaults['tax'] ?? '') && $taxIdx == 0) selected @endif value="{{ $taxOption['tax_id'] ?? '' }}">{{ $taxOption['tax_description'] ?? '' }}</option>
				@endforeach
				</select>
			</td>
			@endfor
		@endif
		
			<td>
		@if($preferences == null )
				<p><em>{{ $LANG['no_preferences'] ?? '' }}</em></p>
		@else
				<select name="preference_id" class="form-select">
			@foreach(($preferences ?? []) as $preference)
					<option @if(($preference['pref_id'] ?? '') == ($defaults['preference'] ?? '')) selected @endif value="{{ $preference['pref_id'] ?? '' }}">{{ $preference['pref_description'] ?? '' }}</option>
			@endforeach
				</select>
		@endif
			</td>		
		</tr>

	{!! $show_custom_field['1'] !!}
	{!! $show_custom_field['2'] !!}
	{!! $show_custom_field['3'] !!}
	{!! $show_custom_field['4'] !!}


	</table>

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
