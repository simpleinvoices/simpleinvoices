{{-- /*
* View: details (Blade)
*   Currency details template
*
* License:
*   GPL v3 or above
*/ --}}

@if(($currency['id'] ?? null) === null)
	<div class="alert alert-warning">{{ $LANG['currency_not_found'] ?? 'Currency not found.' }}</div>
@elseif(($detailsAction ?? 'view') === 'view')

<div class="card">
	<div class="card-body">
		<table class="table table-vcenter">
			<tr>
				<th>{{ $LANG['currency_code'] ?? 'Code' }}</th>
				<td>{{ $currency['currency_code'] ?? '' }}</td>
			</tr>
			<tr>
				<th>{{ $LANG['currency_sign'] ?? 'Symbol' }}</th>
				<td>{{ $currency['currency_sign'] ?? '' }}</td>
			</tr>
			<tr>
				<th>{{ $LANG['currency_position'] ?? 'Position' }}</th>
				<td>{{ $currency['currency_position'] ?? '' === 'left' ? ($LANG['currency_position_left'] ?? 'Before number') : ($LANG['currency_position_right'] ?? 'After number') }}</td>
			</tr>
		</table>
	</div>
	<div class="card-footer">
		<div class="d-flex gap-2">
			<a href="./index.php?module=currencies&amp;view=manage" class="btn btn-link">{{ $LANG['cancel'] ?? '' }}</a>
			<a href="./index.php?module=currencies&amp;view=details&amp;id={{ urlencode($currency['id'] ?? '') }}&amp;action=edit" class="btn btn-primary"><i class="ti ti-edit me-1"></i>{{ $LANG['edit'] ?? '' }}</a>
			<form action="index.php?module=currencies&amp;view=save" method="post" style="display:inline;" onsubmit="return confirm('{{ $LANG['delete_currency_confirm'] ?? 'Are you sure you want to delete this currency?' }}');">
				<input type="hidden" name="op" value="delete_currency" />
				<input type="hidden" name="currency_id" value="{{ $currency['id'] ?? '' }}" />
				<button type="submit" class="btn btn-danger"><i class="ti ti-trash me-1"></i>{{ $LANG['delete'] ?? '' }}</button>
			</form>
		</div>
	</div>
</div>

@include('templates.default.currencies.save', ['saved' => $saved ?? null])

@elseif(($detailsAction ?? '') === 'edit')

@include('shared.save_alert', [
	'success' => ($saved == true),
	'title' => ($saved == true) ? 'Currency updated' : 'Currency not updated',
	'message' => outhtml(($saved == true) ? 'Currency has been updated successfully.' : 'Failed to update currency. Please check all required fields.')
])
@if($saved == true)
<meta http-equiv="refresh" content="2;URL=index.php?module=currencies&amp;view=manage" />
@endif

<form name="frmpost" action="index.php?module=currencies&amp;view=save&amp;id={{ urlencode($currency['id'] ?? '') }}" method="post" class="needs-validation" novalidate>

<div class="card">
	<div class="card-body">
		<div class="mb-3">
			<label class="form-label">{{ $LANG['currency_code'] ?? 'Code' }}
				<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_currency_code" title="{{ $LANG['currency_code'] ?? '' }}"><i class="ti ti-asterisk text-danger"></i></a>
			</label>
			<input type="text" class="form-control" name="currency_code" value="{{ $currency['currency_code'] ?? '' }}" maxlength="10" required autocomplete="off" />
			<div class="invalid-feedback">{{ $LANG['required_field'] ?? 'Required' }}</div>
		</div>

		<div class="mb-3">
			<label class="form-label">{{ $LANG['currency_sign'] ?? 'Symbol' }}
				<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_currency_sign" title="{{ $LANG['currency_sign'] ?? '' }}"><i class="ti ti-asterisk text-danger"></i></a>
			</label>
			<input type="text" class="form-control" name="currency_sign" value="{{ $currency['currency_sign'] ?? '' }}" required autocomplete="off" />
			<div class="invalid-feedback">{{ $LANG['required_field'] ?? 'Required' }}</div>
		</div>

		<div class="mb-3">
			<label class="form-label">{{ $LANG['currency_position'] ?? 'Position' }}</label>
			<select name="currency_position" class="form-select">
				<option value="left" @if(($currency['currency_position'] ?? '') === 'left') selected @endif>{{ $LANG['currency_position_left'] ?? 'Before number' }}</option>
				<option value="right" @if(($currency['currency_position'] ?? '') === 'right') selected @endif>{{ $LANG['currency_position_right'] ?? 'After number' }}</option>
			</select>
		</div>
	</div>

	<div class="card-footer">
		<div class="d-flex">
			<a href="./index.php?module=currencies&amp;view=manage" class="btn btn-link">{{ $LANG['cancel'] ?? '' }}</a>
			<button type="submit" class="btn btn-primary ms-auto" name="save_currency" value="1"><i class="ti ti-check me-1"></i>{{ $LANG['save'] ?? '' }}</button>
		</div>
	</div>
</div>

<input type="hidden" name="op" value="edit_currency" />

</form>

@endif
