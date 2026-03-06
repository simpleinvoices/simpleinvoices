{{-- /*
* Script: total.tpl
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
			@for($tax_header = 0; $tax_header < ($defaults->tax_per_line_item ?? 0); $tax_header++)
				<th class="">{{ $LANG['tax'] ?? '' }} @if($defaults->tax_per_line_item > 1){{ ($tax_header + 1) }}@endif </th>
			@endfor
			<th class="">{{ $LANG['inv_pref'] ?? '' }}</th>
		</tr>

		<tr class="si_invoice_total">
			<td><input type="text" name="unit_price" size="15" class="form-control validate[required]" /></td>
		@if($taxes == null )
			<td><p><em>{{ $LANG['no_taxes'] ?? '' }}</em></p></td>
		@else
			@for($tax = 0; $tax < ($defaults->tax_per_line_item ?? 0); $tax++)
			<td>
				<select id="tax_id[0][{{ $tax }}]" name="tax_id[0][{{ $tax }}]" class="form-select">
					<option value=""></option>
				@foreach(($taxes ?? []) as $tax)
					<option @if($tax['tax_id'] == $defaults->tax AND $tax == 0) selected @endif   value="{{ $tax['tax_id'] ?? '' }}">{{ $tax['tax_description'] ?? '' }}</option>
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
					<option @if($tax['tax_id'] == $defaults->preference) selected @endif value="{{ $preference['pref_id'] ?? '' }}">{{ $preference['pref_description'] ?? '' }}</option>
			@endforeach
				</select>
		@endif
			</td>		
		</tr>

	{{ $show_custom_field['1'] }}
	{{ $show_custom_field['2'] }}
	{{ $show_custom_field['3'] }}
	{{ $show_custom_field['4'] }}


	</table>



	<div class="card-footer text-end">
		<button type="submit" class="btn btn-primary" name="submit" value="{{ $LANG['save'] ?? '' }}"><i class="ti ti-check me-1"></i>{{ $LANG['save'] ?? '' }}</button>
		<a href="./index.php?module=invoices&amp;view=manage" class="btn btn-secondary"><i class="ti ti-x me-1"></i>{{ $LANG['cancel'] ?? '' }}</a>
	</div>

	<div class="mt-2">
		<a class="cluetip btn btn-outline-secondary btn-sm" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_invoice_custom_fields" title="{{ $LANG['want_more_fields'] ?? '' }}"><i class="ti ti-help me-1"></i>{{ $LANG['want_more_fields'] ?? '' }}</a>
	</div>

</div>
	</div>
</div>
<input type="hidden" name="max_items" value="{{ $line }}" />
<input type="hidden" name="type" value="1" />

</form>
