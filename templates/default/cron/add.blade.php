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

<form name="frmpost" action="index.php?module=cron&view=add" method="POST" id="frmpost" class="needs-validation" novalidate>

<div class="card" id="si_form_cron">
	<div class="card-body">
		<div class="mb-3">
			<label class="form-label">{{ $LANG['invoice'] ?? '' }}</label>
			<select name="invoice_id" class="form-select" required>
				<option value=''></option>
				@foreach(($invoice_all ?? []) as $invoice)
					<option value="{{ siLocal::number($invoice['id'] ?? '') }}">{{ $invoice['index_name'] ?? '' }} ({{ $invoice['biller'] ?? '' }}, {{ $invoice['customer'] ?? '' }}, {{ $invoice['invoice_total'] ?? '' }})</option>
				@endforeach
			</select>
			<div class="invalid-feedback">{{ $LANG['required_field'] ?? 'Required' }}</div>
		</div>
		<div class="mb-3">
			<label class="form-label">{{ $LANG['start_date'] ?? '' }}</label>
			<div class="input-icon">
				<span class="input-icon-addon"><i class="ti ti-calendar"></i></span>
				<input type="text" class="form-control date-picker" size="10" name="start_date" id="date"
					value='{{ date('Y-m-d', strtotime('+1 day')) }}'
					required pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}" />
				<div class="invalid-feedback">{{ $LANG['required_field'] ?? 'Required' }}</div>
			</div>
		</div>
		<div class="mb-3">
			<label class="form-label">{{ $LANG['end_date'] ?? '' }}</label>
			<div class="input-icon">
				<span class="input-icon-addon"><i class="ti ti-calendar"></i></span>
				<input type="text" class="form-control date-picker" size="10" name="end_date" value='' />
			</div>
		</div>
		<div class="mb-3">
			<label class="form-label">{{ $LANG['recur_each'] ?? '' }}</label>
			<div class="input-group">
				<input name="recurrence" size="10" class="form-control" required />
				<select name="recurrence_type" class="form-select">
					<option value="day">{{ $LANG['days'] ?? '' }}</option>
					<option value="week">{{ $LANG['weeks'] ?? '' }}</option>
					<option value="month">{{ $LANG['months'] ?? '' }}</option>
					<option value="year">{{ $LANG['years'] ?? '' }}</option>
				</select>
			</div>
			<div class="invalid-feedback">{{ $LANG['required_field'] ?? 'Required' }}</div>
		</div>
		<div class="mb-3">
			<label class="form-label">{{ $LANG['email_biller_after_cron'] ?? '' }}</label>
			<select name="email_biller" class="form-select">
				<option value="1">{{ $LANG['yes'] ?? '' }}</option>
				<option value="0">{{ $LANG['no'] ?? '' }}</option>
			</select>
		</div>
		<div class="mb-3">
			<label class="form-label">{{ $LANG['email_customer_after_cron'] ?? '' }}</label>
			<select name="email_customer" class="form-select">
				<option value="1">{{ $LANG['yes'] ?? '' }}</option>
				<option value="0">{{ $LANG['no'] ?? '' }}</option>
			</select>
		</div>
	</div>
	<div class="card-footer">
		<div class="d-flex">
			<a href="./index.php?module=cron&view=manage" class="btn btn-link">{{ $LANG['cancel'] ?? '' }}</a>
			<button type="submit" class="btn btn-primary ms-auto" name="id" value="{{ $LANG['save'] ?? '' }}"><i class="ti ti-check me-1"></i>{{ $LANG['save'] ?? '' }}</button>
		</div>
	</div>
</div>

<input type="hidden" name="op" value="add" />
</form>
@endif
