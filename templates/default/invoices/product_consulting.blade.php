{{-- /*
* Script: consulting.tpl
* 	 Consulting invoice type template (product-based line items)
*
* License:
*	 GPL v2 or above
*/ --}}
<form name="frmpost" action="index.php?module=invoices&amp;view=save" method="post" onsubmit="return frmpost_Validator(this);">

<div class="card">
	<div class="card-header">
		<h3 class="card-title mb-0">{{ $LANG['inv'] ?? '' }} {{ $LANG['inv_consulting'] ?? '' }}</h3>
	</div>
	<div class="card-body">

@include(str_replace('/', '.', rtrim($path ?? '', '/')) . '.header')

<table class="table table-vcenter">
<tr>
<td class="details_screen">{{ $LANG['quantity'] ?? '' }}</td>
<td class="details_screen">{{ $LANG['description'] ?? '' }}</td>
<td class="details_screen">{{ $LANG['price'] ?? '' }}</td>

</tr>

		@foreach(($dynamic_line_items ?? []) as $line)
			<tr>
				<td><input type="text" name="quantity{{ $line }}" size="5" class="form-control form-control-sm" /></td>
				<td><input type="text" name="description{{ $line }}" size="50" class="form-control form-control-sm" /></td>
				<td><input type="text" name="price{{ $line }}" size="50" class="form-control form-control-sm" /></td>
            </tr>
			<tr class="text{{ $line }} hide">
        		<td colspan="3"><textarea class="form-control form-control-sm editor" name="notes{{ $line }}" rows="3" cols="80" wrap="nowrap"></textarea></td>
			</tr>

		@endforeach
        
	{{ $show_custom_field['1'] }}
	{{ $show_custom_field['2'] }}
	{{ $show_custom_field['3'] }}
	{{ $show_custom_field['4'] }}
	@showCustomFields(4, '')



<tr>
        <td colspan="2" class="details_screen">{{ $LANG['notes'] ?? '' }}</td>
</tr>

<tr>
        <td colspan="2"><textarea class="form-control editor" name="note" rows="5" cols="70" wrap="nowrap"></textarea></td>
</tr>

<tr><td class="details_screen">{{ $LANG['tax'] ?? '' }}</td><td>
@if($taxes == null )
	<p><em>{{ $LANG['no_taxes'] ?? '' }}</em></p>
@else
	<select name="tax_id" class="form-select">
	@foreach(($taxes ?? []) as $tax)
		<option @if($tax['tax_id'] == $defaults->tax) selected @endif value="{{ $tax['tax_id'] ?? '' }}">{{ $tax['tax_description'] ?? '' }}</option>
	@endforeach
	</select>
@endif
</td>
</tr>

<tr>
<td class="details_screen">{{ $LANG['inv_pref'] ?? '' }}</td><td>
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
<tr>
	<td align="left">
		<a class="cluetip btn btn-outline-secondary btn-sm" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields" title="{{ $LANG['custom_fields'] ?? '' }}"><i class="ti ti-help me-1"></i>{{ $LANG['want_more_fields'] ?? '' }}</a>
	</td>
</tr>
</table>
	<input type="hidden" name="max_items" value="{{ $line }}" />
	<input type="hidden" name="type" value="4" />
	</div>
	<div class="card-footer">
		<div class="d-flex">
			<a href="./index.php?module=invoices&amp;view=manage" class="btn btn-link">{{ $LANG['cancel'] ?? '' }}</a>
			<button type="submit" class="btn btn-primary ms-auto" name="submit" value="{{ $LANG['save_invoice'] ?? '' }}"><i class="ti ti-check me-1"></i>{{ $LANG['save'] ?? '' }}</button>
		</div>
	</div>
</div>
</form>
