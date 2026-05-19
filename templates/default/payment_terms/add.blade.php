{{-- Add payment term --}}
<form name="frmpost" action="index.php?module=payment_terms&amp;view=save" method="post" class="needs-validation" novalidate>

<div class="card">
	<div class="card-body">
		<div class="mb-3">
			<label class="form-label" for="term_code">{{ $LANG['payment_term_code'] ?? 'Code' }}</label>
			<input type="text" class="form-control" name="term_code" id="term_code" maxlength="32" required
				placeholder="NET_30" autocomplete="off" />
			<div class="form-text">{{ $LANG['payment_term_code_help'] ?? 'Letters, numbers, and underscores. Stored in uppercase.' }}</div>
			<div class="invalid-feedback">{{ $LANG['required_field'] ?? 'Required' }}</div>
		</div>
		<div class="mb-3">
			<label class="form-label" for="term_label">{{ $LANG['payment_term_label'] ?? 'Label' }}</label>
			<input type="text" class="form-control" name="term_label" id="term_label" maxlength="120" required autocomplete="off" />
			<div class="invalid-feedback">{{ $LANG['required_field'] ?? 'Required' }}</div>
		</div>
		<div class="mb-3">
			<label class="form-label" for="calc_kind">{{ $LANG['payment_term_calc_kind'] ?? 'Calculation' }}</label>
			<select name="calc_kind" id="calc_kind" class="form-select" required>
				@foreach(($calcKinds ?? []) as $k)
					<option value="{{ $k }}" @if($k === 'NET_DAYS') selected @endif>{{ $LANG['payment_term_kind_'.$k] ?? $k }}</option>
				@endforeach
			</select>
		</div>
		<div class="mb-3">
			<label class="form-label" for="param_int">{{ $LANG['payment_term_param'] ?? 'Parameter' }}</label>
			<input type="text" class="form-control" name="param_int" id="param_int" inputmode="numeric" placeholder="30" autocomplete="off" />
			<div class="form-text">{{ $LANG['payment_term_param_help'] ?? 'Days for Net / EOM+N, or calendar day (1-31) for month-following.' }}</div>
		</div>
		<div class="mb-3">
			<label class="form-label" for="sort_order">{{ $LANG['payment_term_sort_order'] ?? 'Sort order' }}</label>
			<input type="number" class="form-control" name="sort_order" id="sort_order" value="100" step="1" />
		</div>
	</div>
	<div class="card-footer">
		<div class="d-flex">
			<a href="./index.php?module=payment_terms&amp;view=manage" class="btn btn-link">{{ $LANG['cancel'] ?? '' }}</a>
			<button type="submit" class="btn btn-primary ms-auto" name="insert_payment_term_btn" value="1"><i class="ti ti-check me-1"></i>{{ $LANG['save'] ?? '' }}</button>
		</div>
	</div>
</div>

<input type="hidden" name="op" value="insert_payment_term" />
</form>
