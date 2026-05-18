{{-- /*
* View: consulting (Blade)
* 	 Consulting invoice type template
*
* License:
*	 GPL v3 or above
*
* Website:
*	http://www.simpleinvoices.org
*/ --}}
<form name="frmpost" action="index.php?module=invoices&amp;view=save" method="post" onsubmit="return frmpost_Validator(this);">

<div class="card">
	<div class="card-header">
		<div id="gmail_loading" class="gmailLoader ms-auto" style="display: none;"><i class="ti ti-loader spinner me-1"></i> {{ $LANG['loading'] ?? '' }} ...</div>
	</div>
	<div class="card-body">

@include(str_replace('/', '.', rtrim($path ?? '', '/')) . '.header')

<table class="table table-vcenter">
<tr>
<td class="details_screen">{{ $LANG['quantity'] ?? '' }}</td>
<td class="details_screen">{{ $LANG['description'] ?? '' }}</td>
<td class="details_screen">{{ $LANG['unit_price'] ?? '' }}</td>
</tr>


		@foreach(($dynamic_line_items ?? []) as $line)
			<tr>
				<td><input type="text" id="quantity{{ $line }}" name="quantity{{ $line }}" size="5" class="form-control form-control-sm" /></td>
				<td><input type="text" name="description{{ $line }}" size="50" class="form-control form-control-sm" />
			@if($products == null )
				<p><em>{{ $LANG['no_products'] ?? '' }}</em></p>
			@else
				<select name="products{{ $line }}" class="form-select form-select-sm product_change" rel="{{ $line }}">
					<option value=""></option>
				@foreach(($products ?? []) as $product)
					@if($product['id'] == ($defaults->product ?? null))
						<option value="{{ $product['id'] }}" selected>{{ $product['description'] }}</option>
						@break
					@endif
				@endforeach
				</select>
			@endif
                </td>
                <td>
					<input id="unit_price{{ $line }}" name="unit_price{{ $line }}" size="7" value="" class="form-control form-control-sm" />
				</td>
             </tr>
                <tr class="text{{ $line }} hide">
      				<td colspan="3"><textarea class="form-control form-control-sm editor" name="description{{ $line }}" rows="3" cols="80" wrap="nowrap"></textarea></td>
				</tr>

		@endforeach
        
	{!! $show_custom_field['1'] !!}
	{!! $show_custom_field['2'] !!}
	{!! $show_custom_field['3'] !!}
	{!! $show_custom_field['4'] !!}
	@showCustomFields(4, '')



<tr>
        <td colspan="3" class="details_screen">{{ $LANG['notes'] ?? '' }}</td>
</tr>

<tr>
        <td colspan="3"><textarea class="form-control editor" name="note" rows="5" cols="70" wrap="nowrap"></textarea></td>
</tr>

<tr><td class="details_screen">{{ $LANG['tax'] ?? '' }}</td><td>
@if($taxes == null )
	<p><em>{{ $LANG['no_taxes'] ?? '' }}</em></p>
@else
	<select name="tax_id" class="form-select">
	@foreach(($taxes ?? []) as $tax)
		<option @if($tax['tax_id'] == $defaults->tax) selected @endif value="{{ $tax['tax_id'] ?? '' }}">{{ $tax['tax_description'] ?? '' }} ({{ ($tax['type'] ?? '') === '$' ? '$' : '' }}{{ (float)($tax['tax_percentage'] ?? 0) }}{{ ($tax['type'] ?? '') !== '$' ? '%' : '' }})</option>
	@endforeach
	</select>
@endif
</td>
</tr>

<tr>
<td class="details_screen align-top">{{ $LANG['inv_pref'] ?? '' }}</td><td>
@include('templates.default.partials.invoice_preference_field', [
	'selectedPrefId' => $defaults['preference'] ?? '',
	'selectedTermId' => '',
	'calcDueDate'    => '',
	'isNewInvoice'   => true,
])
</td>
</tr>	
<tr>
	<td align="left">
		<a class="cluetip btn btn-outline-secondary" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_invoice_custom_fields" title="{{ $LANG['want_more_fields'] ?? '' }}"><i class="ti ti-help me-1"></i>{{ $LANG['want_more_fields'] ?? '' }}</a>
	</td>
</tr>
<!--Add more line items while in an itemeised invoice - Get style - has problems- wipes the current values of the existing rows - not good
<tr>
<td>
<a href="?get_num_line_items=10">Add 5 more line items</a>
</tr>
-->
</table>
	<input type="hidden" name="max_items" value="{{ $line }}" />
	<input type="hidden" name="type" value="3" />
	</div>
	<div class="card-footer">
		<div class="d-flex">
			<a href="./index.php?module=invoices&amp;view=manage" class="btn btn-link">{{ $LANG['cancel'] ?? '' }}</a>
			<button type="submit" class="btn btn-primary ms-auto" name="submit" value="{{ $LANG['save'] ?? '' }}"><i class="ti ti-check me-1"></i>{{ $LANG['save'] ?? '' }}</button>
		</div>
	</div>
</div>
</form>
