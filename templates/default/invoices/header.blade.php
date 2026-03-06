{{-- /*
	* Script: header.tpl
	* 	 Header file for invoice template
	*
	* License:
	*	 GPL v3 or above
	*
	* Website:
	*	http://www.simpleinvoices.org
	*/
#$Id$ --}}

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



	<table class='si_invoice_top'>
	   <tr>
		  <th>{{ $LANG['biller'] ?? '' }}</th>
		   <td>
			   @if($billers == null )
				  <p><em>{{ $LANG['no_billers'] ?? '' }}</em></p>
			   @else
					<select name="biller_id" class="form-select">
					@foreach(($billers ?? []) as $biller)
						<option @if($biller['id'] == $defaults->biller) selected @endif value="{{ $biller['id'] ?? '' }}">{{ $biller['name'] ?? '' }}</option>
					@endforeach
					</select>
				@endif
			</td>
		</tr>
		<tr>
			<th>{{ $LANG['customer'] ?? '' }}</th>
			<td>
				@if($customers == null )
				<em>{{ $LANG['no_customers'] ?? '' }}</em>
				@else
					<select name="customer_id" class="form-select">
					@foreach(($customers ?? []) as $customer)
						<option @if($customer['id'] == $defaults->customer) selected @endif value="{{ $customer['id'] ?? '' }}">{{ $customer['name'] ?? '' }}</option>
					@endforeach
					</select>
				@endif
			</td>
		</tr>
		<tr wrap="nowrap">
			<th >{{ $LANG['date_formatted'] ?? '' }}</th>
			<td wrap="nowrap">
				<input type="text" class="form-control validate[required,custom[date],length[0,10]] date-picker" size="10" name="date" id="date1"
				@if(get('date'))
					value="{{ get('date') }}"
				@else
					value="{{ date('Y-m-d') }}"
				@endif
				/>
			</td>
		</tr>
	</table>

