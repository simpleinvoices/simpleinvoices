<form name="frmpost" action="index.php?module=payments&amp;view=save" method="post" onsubmit="return frmpost_Validator(this)">

<div class="card">
	<div class="card-header">
		<h3 class="card-title">{{ $LANG['process_payment'] ?? '' }}</h3>
	</div>
	<div class="card-body">
	<table class="table table-vcenter">	
	
	@if(get('op') === "pay_selected_invoice")
	
		<tr>
			<th>{{ $invoice['preference'] ?? '' }}</th>
			<td>{{ $invoice['index_id'] ?? '' }}</td>
			<th class="details_screen">{{ $LANG['total'] ?? '' }}</th>
			<td>{{ number_format($invoice['total'] ?? 0, 2) }}</td>
		</tr>
		<tr>
			<th>{{ $LANG['biller'] ?? '' }}</th>
			<td>{{ $biller['name'] ?? '' }}</td>
			<th>{{ $LANG['paid'] ?? '' }}</th>
			<td>{{ number_format($invoice['paid'] ?? 0, 2) }}</td>
		</tr>
		<tr>
			<th>{{ $LANG['customer'] ?? '' }}</th>
			<td>{{ $customer['name'] ?? '' }}</td>
			<th>{{ $LANG['owing'] ?? '' }}</th>
			<td><u>{{ number_format($invoice['owing'] ?? 0, 2) }}</u></td>
		</tr>
		<tr>
			<th>{{ $LANG['amount'] ?? '' }}</th>
			<td colspan="5">
				<input type="text" name="ac_amount" size="25" value="{{ $invoice['owing'] ?? '' }}" class="form-control" />
				<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_process_payment_auto_amount" title="{{ $LANG['process_payment_auto_amount'] ?? '' }}"><i class="ti ti-help"></i></a>
			</td>
		</tr>
		<tr>
			<th>{{ $LANG['date_formatted'] ?? '' }}</th>
			<td><input type="text" class="form-control date-picker" name="ac_date" id="date1" value="{{ $today ?? '' }}" /></td>
		</tr>
		
	@endif
		
	@if(get('op') === "pay_invoice")

		<tr>
			<th>{{ $LANG['invoice'] ?? '' }}</th>
			<td>
				<select name="invoice_id" class="form-select validate[required]">
					<option value=''></option>
			@foreach(($invoice_all ?? []) as $invoice)
					<option value="{{ siLocal::number($invoice['id'] ?? '' }}">{{ $invoice['index_name'] ?? '' }} ({{ $invoice['biller'] ?? '' }}, {{ $invoice['customer'] ?? '' }}, {{ $LANG['total'] ?? '' }} {{ $invoice['invoice_total'] ?? '' }} : {{ siLocal::number($LANG['owing'] ?? '' }} {{ $invoice['owing'] ?? '' }})</option>
			@endforeach
				</select>
		
			</td>
		</tr>
		<tr>
			<th>{{ $LANG['amount'] ?? '' }}</th>
			<td colspan="5"><input type="text" name="ac_amount" size="25" class="form-control" /></td>
		</tr>
		<tr>
			<div class="demo-holder">
				<th class="details_screen">{{ $LANG['date_formatted'] ?? '' }}</th>
				<td><input type="text" class="form-control date-picker" name="ac_date" id="date1" value="{{ $today ?? '' }}" /></td>
			</div>
		</tr>	
	@endif
		
		
		<tr>
			<th>{{ $LANG['payment_type_method'] ?? '' }}</th>
			<td>
		
		@if($paymentTypes == null)
				<p><em>{{ $LANG['no_payment_types'] ?? '' }}</em></p>
		@else
				<select name="ac_payment_type" class="form-select">
				@foreach(($paymentTypes ?? []) as $paymentType)
					<option value="{{ $paymentType['pt_id'] ?? '' }}" @if($paymentType['pt_id'] == $defaults->payment_type)selected@endif>
						{{ $paymentType['pt_description'] ?? '' }}
					</option>
				@endforeach
				</select>
		@endif
			
			</td>
		</tr>
		<tr>
			<th>{{ $LANG['note'] ?? '' }}</th>
			<td colspan="5"><textarea class="form-control editor" name="ac_notes" rows="5" cols="50"></textarea></td>
		</tr>
	</table>
	

		<div class="card-footer text-end">
			<button type="submit" class="btn btn-primary" name="process_payment" value="{{ $LANG['save'] ?? '' }}"><i class="ti ti-check me-1"></i>{{ $LANG['save'] ?? '' }}</button>        
			<a href="./index.php?module=payments&amp;view=manage" class="btn btn-secondary"><i class="ti ti-x me-1"></i>{{ $LANG['cancel'] ?? '' }}</a>
		</div>

		@if(get('op') == 'pay_selected_invoice')
			<input type="hidden" name="invoice_id" value="{{ $invoice['id'] ?? '' }}" />
		@endif
	</div>
</div>

</form>
