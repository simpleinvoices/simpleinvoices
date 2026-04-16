@if($saved == 'true' )
<meta http-equiv="refresh" content="2;URL=index.php?module=payments&amp;view=manage" />
<br />
{{ $LANG['save_eway_success'] ?? '' }}
<br />
<br />
@endif
@if($saved == 'check_failed' )
<meta http-equiv="refresh" content="2;URL=index.php?module=payments&amp;view=manage" />
<br />
{{ $LANG['save_eway_check_failed'] ?? '' }}
<br />
<br />
@endif
@if($saved == 'false' )
<meta http-equiv="refresh" content="2;URL=index.php?module=payments&amp;view=manage" />
<br />
{{ $LANG['save_eway_failure'] ?? '' }}
<br />
<br />
@endif

@if($saved == false)

<form name="frmFpost" action="index.php?module=payments&view=eway" method="POST" id="frmpost" class="needs-validation" novalidate>
<div class="card">
	<div class="card-body">
		<div class="mb-3">
			<label class="form-label">{{ $LANG['invoice'] ?? '' }}</label>
			<select name="invoice_id" class="form-select" required>
				<option value=''></option>
				@foreach(($invoice_all ?? []) as $invoice)
					<option value="{{ $invoice['id'] ?? '' }}" @if(get('id') == $invoice['id']) selected @endif>{{ $invoice['index_name'] ?? '' }}</option>
				@endforeach
			</select>
			<div class="invalid-feedback">{{ $LANG['required_field'] ?? 'Required' }}</div>
		</div>
		<div class="mb-3">
			<p class="text-secondary">{{ $LANG['warning_eway'] ?? '' }}</p>
		</div>
	</div>
	<div class="card-footer">
		<div class="d-flex">
			<a href="./index.php?module=payments&view=manage" class="btn btn-link">{{ $LANG['cancel'] ?? '' }}</a>
			<input type="hidden" name="op" value="add" />
			<button type="submit" class="btn btn-primary ms-auto" name="id" value="{{ $LANG['save'] ?? '' }}"><i class="ti ti-check me-1"></i>{{ $LANG['save'] ?? '' }}</button>
		</div>
	</div>
</div>
</form>
@endif
