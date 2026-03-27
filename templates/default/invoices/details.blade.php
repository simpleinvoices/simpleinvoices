{{-- /*
* Script: details.tpl
* 	 Invoice details template
*	 Modified for 'default_invoices' by Marcel van Dorp. Version 20090208
*	 if no invoice_id set, the date will be today, and the action will be 'insert' instead of 'edit'
*
* License:
*	 GPL v3 or above
*
* Website:
*	http://www.simpleinvoices.org
*/ --}}

{{-- Steel needed ? --}}
<div id="gmail_loading" class="gmailLoader" style="float:right; display: none;"><i class="ti ti-loader spinner me-1"></i> {{ $LANG['loading'] ?? '' }} ...</div>


<form name="frmpost" action="index.php?module=invoices&amp;view=save" method="post">

<div class="card">
	<div class="card-body">
<div class="si_invoice_form">
	<table class="table table-vcenter si_invoice_top">
		<tr>
			<th>{{ $preference['pref_inv_wording'] ?? '' }} {{ $LANG['number_short'] ?? '' }}</th>
			<td> {{ $invoice['index_id'] ?? '' }} </td>
		</tr>
		<tr>
				<th>{{ $LANG['date_formatted'] ?? '' }}</th>
		@if($invoice['id'] == null) 
				<td><input type="text" size="10" class="form-control date-picker" name="date" id="date1" value="{{ date('Y-m-d') }}" /></td>
		@else
				<td><input type="text" size="10" class="form-control date-picker" name="date" id="date1" value="{{ $invoice['calc_date'] ?? '' }}" /></td>
		@endif
		</tr>
		<tr>
			<th>{{ $LANG['biller'] ?? '' }}</th>
			<td>
				
			@if($billers == null )
				<p><em>{{ $LANG['no_billers'] ?? '' }}</em></p>
			@else
				<select name="biller_id" class="form-select">
				@foreach(($billers ?? []) as $biller)
					<option @if($biller['id'] == $invoice['biller_id']) selected @endif value="{{ $biller['id'] ?? '' }}">{{ $biller['name'] ?? '' }}</option>
				@endforeach
				</select>
			@endif
						
			</td>
		</tr>
		<tr>
			<th>{{ $LANG['customer'] ?? '' }}</th>
			<td>
		@if($customers == null)
				<em>{{ $LANG['no_customers'] ?? '' }}</em>
		@else	
				<select name="customer_id" class="form-select">
					@foreach(($customers ?? []) as $customer)
					<option @if($customer['id'] == $invoice['customer_id']) selected @endif value="{{ $customer['id'] ?? '' }}">{{ $customer['name'] ?? '' }}</option>
					@endforeach
				</select>
		@endif
			</td>
		</tr>
	
		{{-- TODO: implement status 
		<tr>
			<th>Invoice Status</th>
			<td>
				<select name="status_id" class="form-select">
					<option value="0">New</option>
					<option @if($invoice['status_id'] == 1) selected@endif value="1">Sent</option>
					<option @if($invoice['status_id'] == 2) selected@endif value="1">Paid</option>
				</select>
			</td>
		</tr> --}}
	</table>


@if($invoice['type_id'] == 1 )

	<table id="itemtable" class="table table-vcenter si_invoice_items">
		<tr>
			<td class='si_invoice_notes' colspan="2">
				<H5>{{ $LANG['description'] ?? '' }}</H5>
				<textarea class="form-control editor" name="description0" rows="10" cols="70" wrap="nowrap">{{ $invoiceItems[0]['description'] ?? '' }}</textarea>
			</td>
		</tr>		
	</table>


	<table class="si_invoice_bot">
		<tr>       	         
			<th>{{ $LANG['gross_total'] ?? '' }}</th>
			<td>
				<input type="text" name="unit_price0" value="{{ siLocal::number_formatted($invoiceItems[0]['unit_price'] ?? 0) }}" size="10" class="form-control text-end" />
				<input type="hidden" name="quantity0" value="1" />
				<input type="hidden" name="id0" value="{{ $invoiceItems[0]['id']?? '' }}" />
				<input type="hidden" name="products0" value="{{ $invoiceItems[0]['product_id']?? '' }}" />
			</td>
		</tr>
		<tr>
			<th>{{ $LANG['tax'] ?? '' }}</th>
			<td>
				<table class="si_invoice_taxes">
					<tr>
					@for($taxIdx = 0; $taxIdx < (int)($defaults['tax_per_line_item'] ?? 0); $taxIdx++)
						<td>				                				                
							<select 
								id="tax_id[0][{{ $taxIdx }}]"
								name="tax_id[0][{{ $taxIdx }}]"
								class="form-select form-select-sm"
							>
							<option value=""></option>
							@foreach(($taxes ?? []) as $taxOption)
								<option @if(($invoiceItems[0]['tax'][$taxIdx] ?? '') === ($taxOption['tax_id'] ?? '')) selected @endif value="{{ $taxOption['tax_id'] ?? '' }}">{{ $taxOption['tax_description'] ?? '' }}</option>
							@endforeach
						</select>
						</td>
					
					@endfor
					</tr>
				</table>
			<td>
		</tr>

		 {{ $customFields['1'] }}
		 {{ $customFields['2'] }}
		 {{ $customFields['3'] }}
		 {{ $customFields['4'] }}
		 @showCustomFields(4, get('invoice'))

@endif

@if($invoice['type_id'] == 2 || $invoice['type_id'] == 3 )
	<table id="itemtable" class="table table-vcenter si_invoice_items">
		<thead>
		<tr>
			<td></td>
			<td>{{ $LANG['quantity_short'] ?? '' }}</td>
			<td>{{ $LANG['description'] ?? '' }}</td>
		@for($tax_header = 0; $tax_header < (int)($defaults['tax_per_line_item'] ?? 0); $tax_header++)
			<td>{{ $LANG['tax'] ?? '' }} @if(($defaults['tax_per_line_item'] ?? 0) > 1){{ ($tax_header + 1) }}@endif </td>
		@endfor
			<td>{{ $LANG['unit_price'] ?? '' }}</td>
		</tr>
		</thead>

		@foreach(($invoiceItems ?? []) as $line => $invoiceItem)
			<tbody class="line_item" id="row{{ $line ?? '' }}">
				<tr class="tr_cycle" name="rows" values="A,B">
					<td>
					@if($line != "0")
						<a 
							id="trash_link_edit{{ $line ?? '' }}"
							class="trash_link_edit btn btn-icon btn-sm btn-outline-danger"
							title="{{ $LANG['delete_line_item'] ?? '' }}" 
							href="#" 
							style="display: inline;"
							rel="{{ $line ?? '' }}"
						>
							<i id="delete_image{{ $line ?? '' }}" class="ti ti-trash"></i>
						</a>
					@endif
					@if($line == "0")
						<a 
							id="trash_link_edit{{ $line ?? '' }}"
							class="trash_link_edit btn btn-icon btn-sm btn-ghost-secondary"
							title="{{ $LANG['delete_line_item'] ?? '' }}"
							href="#"
							style="display: inline;"
							rel="{{ $line ?? '' }}"
						>
							<i id="delete_image{{ $line ?? '' }}" class="ti ti-minus" style="visibility:hidden"></i>
						</a>
					@endif
					</td>
					<td>
						<input type="hidden" id="delete{{ $line ?? '' }}" name="delete{{ $line ?? '' }}" size="3" />
						<input
							type="text"
							name="quantity{{ $line ?? '' }}"
							id="quantity{{ $line ?? '' }}"
							value="{{ siLocal::number($invoiceItem['quantity'] ?? '') }}"
							size="10"
							class="form-control form-control-sm text-end si_right"
						/>
						<input type="hidden" name='line_item{{ $line ?? '' }}' id='line_item{{ $line ?? '' }}' value='{{ $invoiceItem['id'] ?? '' }}' /> 
					</td>
					<td>
								
						@if($products == null )
							<em>{{ $LANG['no_products'] ?? '' }}</em>
						@else
							{{-- onchange="invoice_product_change_price($(this).val(), {{ $line ?? '' }}, jQuery('#quantity{{ $line ?? '' }}').val() );" --}}
							<select 
								name="products{{ $line ?? '' }}"
								id="products{{ $line ?? '' }}"
								rel="{{ $line ?? '' }}"
								class="form-select form-select-sm product_change"
							>
							@foreach(($products ?? []) as $product)
								<option @if($biller['id'] == $invoice['biller_id']) selected @endif value="{{ $product['id'] ?? '' }}">{{ $product['description'] ?? '' }}</option>
							@endforeach
							</select>
						@endif
					</td>
					@for($taxIdx = 0; $taxIdx < (int)($defaults['tax_per_line_item'] ?? 0); $taxIdx++)
						<td>				                				                
							<select 
								id="tax_id[{{ $line ?? '' }}][{{ $taxIdx }}]"
								name="tax_id[{{ $line ?? '' }}][{{ $taxIdx }}]"
								class="form-select form-select-sm"
							>
							<option value=""></option>
							@foreach(($taxes ?? []) as $taxOption)
								<option @if(($invoiceItem['tax'][$taxIdx] ?? '') === ($taxOption['tax_id'] ?? '')) selected @endif value="{{ $taxOption['tax_id'] ?? '' }}">{{ $taxOption['tax_description'] ?? '' }}</option>
							@endforeach
						</select>
						</td>
					@endfor
					<td>
						<input id="unit_price{{ siLocal::number_clean($line) }}" name="unit_price{{ $line }}" size="7" value="{{ $invoiceItem['unit_price'] ?? '' }}" class="form-control form-control-sm text-end si_right" />
					</td>
				</tr>
					{{ $invoiceItem['html'] }}
				<tr class="details si_hide">
					<td>
					</td>
					<td colspan="4">
						<textarea class="form-control form-control-sm detail" name="description{{ $line }}" id="description{{ $line }}" rows="3" cols="3" wrap="nowrap">{{ $invoiceItem['description'] }}</textarea>
					</td>
				</tr>
				</tbody>
		@endforeach
	</table>

	<div class="btn-list mb-3">
		{{-- onclick="add_line_item();" --}}
		<a href="#" class="add_line_item btn btn-outline-primary btn-sm">
			<i class="ti ti-plus me-1"></i>{{ $LANG['add_new_row'] ?? '' }}
		</a>
		<a href='#' class="show-details btn btn-outline-secondary btn-sm" onclick="document.querySelectorAll('.details').forEach(function(e){e.style.display='';}); document.querySelectorAll('.show-details').forEach(function(e){e.style.display='none'}); return false;"><i class="ti ti-plus me-1"></i>{{ $LANG['show_details'] ?? '' }}</a>
		<a href='#' class="details btn btn-outline-secondary btn-sm" onclick="document.querySelectorAll('.details').forEach(function(e){e.style.display='none'}); document.querySelectorAll('.show-details').forEach(function(e){e.style.display=''}); return false;" style="display:none"><i class="ti ti-minus me-1"></i>{{ $LANG['hide_details'] ?? '' }}</a>
	</div>


	<table class="si_invoice_bot">
	 {{ $customFields['1'] }}
	 {{ $customFields['2'] }}
	 {{ $customFields['3'] }}
	 {{ $customFields['4'] }}
	 @showCustomFields(4, get('invoice'))
		<tr>
			<td class='si_invoice_notes' colspan="2">
				<H5>{{ $LANG['notes'] ?? '' }}</H5>
				<textarea class="form-control editor" name="note" rows="10" cols="70" wrap="nowrap">{{  $invoice['note'] }}</textarea>
			</td>
		</tr>		
@endif



		<tr>
			<th>{{ $LANG['inv_pref'] ?? '' }}</th>
			<td>
		@if($preferences == null )
				<em>{{ $LANG['no_preferences'] ?? '' }}</em>
		@else
				<select name="preference_id" class="form-select">
				@foreach(($preferences ?? []) as $preference)
					<option @if(($preference['pref_id'] ?? '') == ($invoice['preference_id'] ?? $defaults['preference'] ?? '')) selected @endif value="{{ $preference['pref_id']  }}">{{ $preference['pref_description']  }}</option>
				@endforeach
				</select>
		@endif								 
			</td>
		</tr>
    </table>
	</div>
	</div>
	<div class="card-footer">
		<div class="d-flex">
			<a href="./index.php?module=invoices&amp;view=manage" class="btn btn-link">{{ $LANG['cancel'] ?? '' }}</a>
			<button type="submit" class="btn btn-primary ms-auto invoice_save" name="submit" value="{{ $LANG['save'] ?? '' }}"><i class="ti ti-check me-1"></i>{{ $LANG['save'] ?? '' }}</button>
		</div>
	</div>
</div>

@if($invoice['id'] == null) 
	<input type="hidden" name="action" value="insert" />
@else
	<input type="hidden" name="id" value="{{ $invoice['id'] ?? '' }}" />
	<input type="hidden" name="action" value="edit" />
@endif
@if($invoice['type_id'] == 1 )
	<input id="quantity0" type="hidden" size="10" value="1.00" name="quantity0"/>
	<input id="line_item0" type="hidden" value="{{ $invoiceItems[0]['id'] }}" name="line_item0"/>
@endif
<input type="hidden" name="type" value="{{ $invoice['type_id'] }}" />
<input type="hidden" name="op" value="insert_preference" />
<input type="hidden" id="max_items" name="max_items" value="{{ $lines }}" />

</form>