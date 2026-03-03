{{-- /*
* Script: consulting.tpl
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
		<h3 class="card-title mb-0">{{ $LANG['inv'] ?? '' }} {{ $LANG['inv_consulting'] ?? '' }}</h3>
		<div id="gmail_loading" class="gmailLoader" style="display: none;"><i class="ti ti-loader spinner me-1"></i> {{ $LANG['loading'] ?? '' }} ...</div>
	</div>
	<div class="card-body">

@include(str_replace('/', '.', rtrim($path ?? '', '/')) . '.header')

<tr>
<td class="details_screen">{{ $LANG['quantity'] ?? '' }}</td>
<td class="details_screen">{{ $LANG['description'] ?? '' }}</td>
<td class="details_screen">{{ $LANG['unit_price'] ?? '' }}</td>
</tr>


        (($dynamic_line_items ?? []) as $line)

			<tr>
				<td><input type="text" id="quantity{{ $line }}" name="quantity{{ $line }}" size="5" /></td>
				</td><td><input type="text" name="description{{ $line }}" size="50" />
				                
			@if($products == null )
				<p><em>{{ $LANG['no_products'] ?? '' }}</em></p>
			@else
				<select name="products{{ $line }}" class="product_change" rel="{{ $line }}">
				
					<option value=""></option>
				@foreach(($products ?? []) as $product)
					<option @if($product['id'] == $defaults->product) selected @endif value="{{ $product['id'] ?? '' }}">{{ $product['description'] ?? '' }}</option>
				@endforeach
				</select>
			@endif
				                				                
                </td>
                <td>
					<input id="unit_price{{ $line }}" name="unit_price{{ $line }}" size="7" value="" />
				</td>	
             </tr>
                
                <tr class="text{{ $line }} hide">
      				<td colspan="3"><textarea input type="text" class="editor" name='description{{ $line }}' rows="3" cols="80" wrap="nowrap"></textarea></td>
</tr>

        
	{{ $show_custom_field['1'] }}
	{{ $show_custom_field['2'] }}
	{{ $show_custom_field['3'] }}
	{{ $show_custom_field['4'] }}
	@showCustomFields(4, '')



<tr>
        <td colspan="3" class="details_screen">{{ $LANG['notes'] ?? '' }}</td>
</tr>

<tr>
        <td colspan="3"><textarea input type="text" class="editor" height="60px" name="note" rows="5" cols="70" wrap="nowrap"></textarea></td>
</tr>

<tr><td class="details_screen">{{ $LANG['tax'] ?? '' }}</td><td><input type="text" name="tax" size="15" />

@if($taxes == null )
	<p><em>{{ $LANG['no_taxes'] ?? '' }}</em></p>
@else
	<select name="tax_id">
	@foreach(($taxes ?? []) as $tax)
		<option @if($product['id'] == $defaults->tax) selected @endif value="{{ $tax['tax_id'] ?? '' }}">{{ $tax['tax_description'] ?? '' }}</option>
	@endforeach
	</select>
@endif

</td>
</tr>

<tr>
<td class="details_screen">{{ $LANG['inv_pref'] ?? '' }}</td><td><input type="text" name="preference_id" />

@if($preferences == null )
	<p><em>{{ $LANG['no_preferences'] ?? '' }}</em></p>
@else
	<select name="preference_id">
	@foreach(($preferences ?? []) as $preference)
		<option @if($product['id'] == $defaults->preference) selected @endif value="{{ $preference['pref_id'] ?? '' }}">{{ $preference['pref_description'] ?? '' }}</option>
	@endforeach
	</select>
@endif

</td>
</tr>	
<tr>
	<td align="left">
		<a class="cluetip btn btn-outline-secondary btn-sm" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_invoice_custom_fields" title="{{ $LANG['want_more_fields'] ?? '' }}"><i class="ti ti-help me-1"></i>{{ $LANG['want_more_fields'] ?? '' }}</a>
	</td>
</tr>
<!--Add more line items while in an itemeised invoice - Get style - has problems- wipes the current values of the existing rows - not good
<tr>
<td>
<a href="?get_num_line_items=10">Add 5 more line items</a>
</tr>
-->
</table>
	<div class="card-footer text-end">
		<button type="submit" class="btn btn-primary" name="submit" value="{{ $LANG['save'] ?? '' }}"><i class="ti ti-check me-1"></i>{{ $LANG['save'] ?? '' }}</button>
		<input type="hidden" name="max_items" value="{{ $line }}" />
		<input type="hidden" name="type" value="3" />
		<a href="./index.php?module=invoices&amp;view=manage" class="btn btn-secondary"><i class="ti ti-x me-1"></i>{{ $LANG['cancel'] ?? '' }}</a>
	</div>
	</div>
</div>
</form>
