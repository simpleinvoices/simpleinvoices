{{-- /*
* View: add (Blade)
*   Add new currency template
*
* License:
*   GPL v3 or above
*/ --}}

@include('shared.save_alert', [
	'success' => ($saved == true),
	'title' => ($saved == true) ? 'Currency saved' : 'Currency not saved',
	'message' => outhtml(($saved == true) ? 'Currency has been created successfully.' : 'Failed to create currency. Please check all required fields.')
])
@if($saved == true)
<meta http-equiv="refresh" content="2;URL=index.php?module=currencies&amp;view=manage" />
@endif

<form name="frmpost" action="index.php?module=currencies&amp;view=save" method="post" class="needs-validation" novalidate>

<div class="card">
	<div class="card-body">
		<div class="mb-3">
			<label class="form-label">{{ $LANG['currency_code'] ?? 'Code' }}
				<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_currency_code" title="{{ $LANG['currency_code'] ?? '' }}"><i class="ti ti-asterisk text-danger"></i></a>
			</label>
			<input type="text" class="form-control" name="currency_code" value="{{ post('currency_code') }}" maxlength="10" required autocomplete="off" />
			<div class="invalid-feedback">{{ $LANG['required_field'] ?? 'Required' }}</div>
		</div>

		<div class="mb-3">
			<label class="form-label">{{ $LANG['currency_sign'] ?? 'Symbol' }}
				<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_currency_sign" title="{{ $LANG['currency_sign'] ?? '' }}"><i class="ti ti-asterisk text-danger"></i></a>
			</label>
			<input type="text" class="form-control" name="currency_sign" value="{{ post('currency_sign') }}" required autocomplete="off" />
			<div class="invalid-feedback">{{ $LANG['required_field'] ?? 'Required' }}</div>
		</div>

		<div class="mb-3">
			<label class="form-label">{{ $LANG['currency_position'] ?? 'Position' }}</label>
			<select name="currency_position" class="form-select">
				<option value="left" @if(post('currency_position') == 'left' || post('currency_position') == '') selected @endif>{{ $LANG['currency_position_left'] ?? 'Before number' }}</option>
				<option value="right" @if(post('currency_position') == 'right') selected @endif>{{ $LANG['currency_position_right'] ?? 'After number' }}</option>
			</select>
		</div>
	</div>

	<div class="card-footer">
		<div class="d-flex">
			<a href="./index.php?module=currencies&amp;view=manage" class="btn btn-link">{{ $LANG['cancel'] ?? '' }}</a>
			<button type="submit" class="btn btn-primary ms-auto"><i class="ti ti-check me-1"></i>{{ $LANG['save'] ?? '' }}</button>
		</div>
	</div>
</div>

<input type="hidden" name="op" value="insert_currency" />

</form>
