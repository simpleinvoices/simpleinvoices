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
                <td>
                    <a href="./index.php?module=billers&amp;view=add" class="btn btn-primary"><i class="ti ti-building-store me-1"></i>{{ $LANG['add_new_biller'] ?? '' }}</a>
                </td>
        </tr>
    @endif

    @if($customers == null)
			<tr>
				<th>{{ $LANG['setup_add_customer'] ?? '' }}</th>
                <td>
                    <a href="./index.php?module=customers&amp;view=add" class="btn btn-primary"><i class="ti ti-users me-1"></i>{{ $LANG['customer_add'] ?? '' }}</a>
                </td>
            </tr>
    @endif

    @if($products == null)
			<tr>
				<th>{{ $LANG['setup_add_products'] ?? '' }}</th>
                <td>
                    <a href="./index.php?module=products&amp;view=add" class="btn btn-primary"><i class="ti ti-package me-1"></i>{{ $LANG['add_new_product'] ?? '' }}</a>
                </td>
            </tr>

    @endif

    @if($taxes == null)
			<tr>
				<th>{{ $LANG['setup_add_taxrate'] ?? '' }}</th>
                <td>
                    <a href="index.php?module=tax_rates&amp;view=add" class="btn btn-primary"><i class="ti ti-receipt-tax me-1"></i>{{ $LANG['add_new_tax_rate'] ?? '' }}</a>
                </td>
            </tr>

    @endif

    @if($preferences == null)
            <tr>
				<th>{{ $LANG['setup_add_inv_pref'] ?? '' }}</th>
                <td>
                    <a href="./index.php?module=preferences&amp;view=add" class="btn btn-primary"><i class="ti ti-file-text me-1"></i>{{ $LANG['add_new_preference'] ?? '' }}</a>
                </td>
            </tr>
    @endif
		</table>
				</div>
			</div>
		</div>

@else


<div class="card">
	<div class="card-body">
<div class="si_invoice_form">

	@include(str_replace('/', '.', rtrim($path ?? '', '/')) . '.header')


	<table id="itemtable" class="table table-vcenter si_invoice_items">
		<thead>
			<tr>
				<td class=""></td>
				<td class="">{{ $LANG['quantity'] ?? '' }}</td>
				<td class="">{{ $LANG['item'] ?? '' }}</td>
			@for($tax_header = 0; $tax_header < (int)($defaults['tax_per_line_item'] ?? 0); $tax_header++)
				<td class="">{{ $LANG['tax'] ?? '' }} @if(($defaults['tax_per_line_item'] ?? 0) > 1){{ ($tax_header + 1) }}@endif </td>
			@endfor
				<td class="">{{ $LANG['unit_price'] ?? '' }}</td>
			</tr>
		</thead>

		@foreach(($dynamic_line_items ?? []) as $line)
		<tbody class="line_item" id="row{{ $line }}"> 
			<tr>
				<td>
					@if($line == "0")
					<a href="#" class="trash_link" id="trash_link{{ $line }}" title="{{ $LANG['cannot_delete_first_row'] ?? '' }}" >
						<img id="trash_image{{ $line }}" src="./images/common/blank.gif" height="16px" width="16px" title="{{ $LANG['cannot_delete_first_row'] ?? '' }}" alt="" />
					</a>
					@endif

					@if($line != 0)
					{{-- can't delete line 0 --}}
					<!-- onclick="delete_row({{ $line }});" --> 
					<a 
						id="trash_link{{ $line }}"
						class="trash_link btn btn-icon btn-sm btn-outline-danger"
						title="{{ $LANG['delete_row'] ?? '' }}" 
						rel="{{ $line }}"
						href="#" 
						style="display: inline;"
					>
						<i class="ti ti-trash"></i>
					</a>
					@endif
				</td>
				<td>
					<input
						type="text"
						name="quantity{{ $line }}"
						id="quantity{{ $line }}"
						size="5"
						class="form-control form-control-sm text-end si_right @if($line == '0')validate[required]@endif"
						@if(get('quantity' . $line))
							value="{{ get('quantity' . $line) }}"
						@endif
					/>
				</td>
				<td>
								
			@if($products == null )
				<p><em>{{ $LANG['no_products'] ?? '' }}</em></p>
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
						@if($product['id'] == ((get())['product'][$line] ?? null))
							value="{{ (get())['product'][$line] ?? '' }}"
							selected
						@else
							value="{{ $product['id'] ?? '' }}"
						@endif
					>
						{{ $product['description'] ?? '' }}
					</option>
				@endforeach
				</select>
			@endif
				</td>
				@for($taxIdx = 0; $taxIdx < (int)($defaults['tax_per_line_item'] ?? 0); $taxIdx++)
				<td>
					<select 
						id="tax_id[{{ $line }}][{{ $taxIdx }}]"
						name="tax_id[{{ $line }}][{{ $taxIdx }}]"
						class="form-select form-select-sm"
					>
					<option value=""></option>
					@foreach(($taxes ?? []) as $taxOption)
						<option 
							@if(($taxOption['tax_id'] ?? '') == ((get())['tax'][$line][$taxIdx] ?? null))
							value="{{ $taxOption['tax_id'] ?? '' }}"
							selected
							@else
								value="{{ $taxOption['tax_id'] ?? '' }}"
							@endif
						>
							{{ $taxOption['tax_description'] ?? '' }}
						</option>
					@endforeach
				</select>
				</td>
				@endfor

				<td>
					<input
						id="unit_price{{ $line }}"
						name="unit_price{{ $line }}"
						size="7"
						class="form-control form-control-sm text-end si_right @if($line == '0')validate[required]@endif"
						@if(get('unit_price' . $line))
							value="{{ get('unit_price' . $line) }}"
						@else
						   value=""
						@endif
					/>
				</td>	

			</tr>
					
			<tr class="details si_hide">
				<td></td>
				<td colspan="4">
					<textarea class="form-control form-control-sm detail" name="description{{ $line }}" id="description{{ $line }}" rows="3" cols="3" wrap="nowrap"></textarea>
				</td>
			</tr>
		</tbody>
		@endforeach
		
	</table>

	<div class="btn-list mb-3">
		{{-- onclick="add_line_item();" --}}
		<a href="#" class="add_line_item btn btn-outline-primary btn-sm"><i class="ti ti-plus me-1"></i>{{ $LANG['add_new_row'] ?? '' }}</a>
		<a href='#' class="show-details btn btn-outline-secondary btn-sm" onclick="javascript: $('.details').addClass('si_show').removeClass('si_hide');$('.show-details').addClass('si_hide').removeClass('si_show');"><i class="ti ti-plus me-1"></i>{{ $LANG['show_details'] ?? '' }}</a>
		<a href='#' class="details si_hide btn btn-outline-secondary btn-sm" onclick="javascript: $('.details').removeClass('si_show').addClass('si_hide');$('.show-details').addClass('si_show').removeClass('si_hide');"><i class="ti ti-minus me-1"></i>{{ $LANG['hide_details'] ?? '' }}</a>
	</div>

	<table class="si_invoice_bot">

		{{ $show_custom_field['1'] }}
		{{ $show_custom_field['2'] }}
		{{ $show_custom_field['3'] }}
		{{ $show_custom_field['4'] }}
		@showCustomFields(4, '')

		<tr>
			<td class='si_invoice_notes' colspan="2">
				<H5>{{ $LANG['notes'] ?? '' }}</H5>
				<textarea class="form-control editor" name="note" rows="5" cols="50" wrap="nowrap">
						{{ get('note') }}
				</textarea>
			</td>
		</tr>
			
		<tr>
			<th>
				{{ $LANG['inv_pref'] ?? '' }}
			</th>
			<td>
			@if($preferences == null )
				<em>{{ $LANG['no_preferences'] ?? '' }}</em>
			@else
				<select name="preference_id" class="form-select">
				@foreach(($preferences ?? []) as $preference)
					<option @if($preference['pref_id'] == $defaults->preference) selected @endif value="{{ $preference['pref_id'] ?? '' }}">{{ $preference['pref_description'] ?? '' }}</option>
				@endforeach
				</select>
			@endif
			</td>
		</tr>	
	</table>
 
	<input type="hidden" id="max_items" name="max_items" value="{{ $line }}" />
	<input type="hidden" name="type" value="2" />

	<div class="si_toolbar si_toolbar_form">
		<button type="submit" class="invoice_save btn btn-primary" name="submit" value="{{ $LANG['save'] ?? '' }}"><i class="ti ti-check me-1"></i>{{ $LANG['save'] ?? '' }}</button>
    	<a href="./index.php?module=invoices&amp;view=manage" class="negative"><img src="./images/common/cross.png" alt="" />{{ $LANG['cancel'] ?? '' }}</a>
	</div>

	<div class="si_help_div">
		<a class="cluetip" href="#"	rel="index.php?module=documentation&amp;view=view&amp;page=help_invoice_custom_fields" title="{{ $LANG['want_more_fields'] ?? '' }}"><img src="./images/common/help-small.png" alt="" /> {{ $LANG['want_more_fields'] ?? '' }}</a>
	</div>

</div>

</form>

@endif
