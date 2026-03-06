@if($saved == 'true' )
	<meta http-equiv="refresh" content="2;URL=index.php?module=cron&amp;view=manage" />
	<br />
	 {{ $LANG['save_cron_success'] ?? '' }}
	<br />
	<br />
@endif
@if($saved == 'false' )
	<meta http-equiv="refresh" content="2;URL=index.php?module=cron&amp;view=manage" />
	<br />
	 {{ $LANG['save_cron_failure'] ?? '' }}
	<br />
	<br />
@endif

@if($saved ==false)
	@if(post('op') == 'add' AND post('invoice_id') == '') 
		<div class="alert alert-warning"><i class="ti ti-alert-circle"></i>
		You must select an invoice</div>
	@endif


<form name="frmpost" action="index.php?module=cron&view=edit&id={{ urlencode($cron['id'] ?? '') }}" method="POST" id="frmpost">

<div class="card">
	<div class="card-body">
		<div class="mb-3">
			<label class="form-label">{{ $LANG['invoice'] ?? '' }}</label>
			<select name="invoice_id" class="form-select validate[required]">
				<option value=''></option>
				@foreach(($invoice_all ?? []) as $invoice)
					<option value="{{ $invoice['id'] ?? '' }}" @if(($invoice['id'] ?? '') == ($cron['invoice_id'] ?? ''))selected@endif>
						{{ $invoice['index_name'] ?? '' }} ({{ $invoice['biller'] ?? '' }}, {{ $invoice['customer'] ?? '' }}, {{ $invoice['invoice_total'] ?? '' }})
					</option>
				@endforeach
			</select>
		</div>
		<div class="mb-3">
			<label class="form-label">{{ $LANG['start_date'] ?? '' }}</label>
			<input type="text" class="form-control validate[required,custom[date],length[0,10]] date-picker" size="10" name="start_date" id="date" value='{{ $cron['start_date'] ?? '' }}' />
		</div>
		<div class="mb-3">
			<label class="form-label">{{ $LANG['end_date'] ?? '' }}</label>
			<input type="text" class="form-control date-picker" size="10" name="end_date" id="date" value='{{ $cron['end_date'] ?? '' }}' />
		</div>
		<div class="mb-3">
			<label class="form-label">{{ $LANG['recur_each'] ?? '' }}</label>
			<div class="input-group">
				<input name="recurrence" size="10" class="form-control validate[required]" value='{{ $cron['recurrence'] ?? '' }}' />
				<select name="recurrence_type" class="form-select validate[required]">
					<option value="day" @if(($cron['recurrence_type'] ?? '') == 'day')selected@endif>{{ $LANG['days'] ?? '' }}</option>
					<option value="week" @if(($cron['recurrence_type'] ?? '') == 'week')selected@endif>{{ $LANG['weeks'] ?? '' }}</option>
					<option value="month" @if(($cron['recurrence_type'] ?? '') == 'month')selected@endif>{{ $LANG['months'] ?? '' }}</option>
					<option value="year" @if(($cron['recurrence_type'] ?? '') == 'year')selected@endif>{{ $LANG['years'] ?? '' }}</option>
				</select>
			</div>
		</div>
		<div class="mb-3">
			<label class="form-label">{{ $LANG['email_biller_after_cron'] ?? '' }}</label>
			<select name="email_biller" class="form-select validate[required]">
				<option value="1" @if(($cron['email_biller'] ?? '') == '1')selected@endif>{{ $LANG['yes'] ?? '' }}</option>
				<option value="0" @if(($cron['email_biller'] ?? '') == '0')selected@endif>{{ $LANG['no'] ?? '' }}</option>
			</select>
		</div>
		<div class="mb-3">
			<label class="form-label">{{ $LANG['email_customer_after_cron'] ?? '' }}</label>
			<select name="email_customer" class="form-select validate[required]">
				<option value="1" @if(($cron['email_customer'] ?? '') == '1')selected@endif>{{ $LANG['yes'] ?? '' }}</option>
				<option value="0" @if(($cron['email_customer'] ?? '') == '0')selected@endif>{{ $LANG['no'] ?? '' }}</option>
			</select>
		</div>
	</div>
	<div class="card-footer text-end">
		<button type="submit" class="btn btn-primary" name="id" value="{{ $LANG['save'] ?? '' }}">
			<i class="ti ti-check"></i>
			{{ $LANG['save'] ?? '' }}
		</button>
		<input type="hidden" name="op" value="edit" />
		<a href="./index.php?module=cron&view=manage" class="btn btn-outline-secondary">
			<i class="ti ti-x"></i>
			{{ $LANG['cancel'] ?? '' }}
		</a>
	</div>
</div>

</form>
@endif
