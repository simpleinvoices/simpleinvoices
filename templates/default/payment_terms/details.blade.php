@if(($term['term_id'] ?? null) === null)
	<div class="alert alert-warning">{{ $LANG['save_payment_term_failure'] ?? 'Not found.' }}</div>
@elseif(($detailsAction ?? 'view') === 'view')

<div class="card">
	<div class="card-body">
		<table class="table table-vcenter">
			<tr>
				<th>{{ $LANG['payment_term_code'] ?? 'Code' }}</th>
				<td>{{ $term['term_code'] ?? '' }}</td>
			</tr>
			<tr>
				<th>{{ $LANG['payment_term_label'] ?? 'Label' }}</th>
				<td>{{ $term['term_label'] ?? '' }}</td>
			</tr>
			<tr>
				<th>{{ $LANG['payment_term_calc_kind'] ?? 'Calculation' }}</th>
				<td>{{ $LANG['payment_term_kind_'.($term['calc_kind'] ?? '')] ?? ($term['calc_kind'] ?? '') }}</td>
			</tr>
			<tr>
				<th>{{ $LANG['payment_term_param'] ?? 'Parameter' }}</th>
				<td>@if(($term['calc_kind'] ?? '') === 'EOM') - @else {{ $term['param_int'] ?? '' }} @endif</td>
			</tr>
			<tr>
				<th>{{ $LANG['payment_term_sort_order'] ?? 'Sort order' }}</th>
				<td>{{ (int)($term['sort_order'] ?? 0) }}</td>
			</tr>
		</table>
	</div>
	<div class="card-footer">
		<div class="d-flex">
			<a href="./index.php?module=payment_terms&amp;view=manage" class="btn btn-link">{{ $LANG['cancel'] ?? '' }}</a>
			<a href="./index.php?module=payment_terms&amp;view=details&amp;id={{ urlencode($term['term_id'] ?? '') }}&amp;action=edit" class="btn btn-primary ms-auto"><i class="ti ti-edit me-1"></i>{{ $LANG['edit'] ?? '' }}</a>
		</div>
	</div>
</div>

@elseif(($detailsAction ?? '') === 'edit')

<form name="frmpost" action="index.php?module=payment_terms&amp;view=save&amp;id={{ urlencode($term['term_id'] ?? '') }}" method="post" class="needs-validation" novalidate>

<div class="card">
	<div class="card-body">
		<table class="table table-vcenter">
			<tr>
				<th>{{ $LANG['payment_term_code'] ?? 'Code' }}</th>
				<td>
					<input type="text" name="term_code" value="{{ $term['term_code'] ?? '' }}" class="form-control" maxlength="32" required autocomplete="off" />
					<div class="invalid-feedback">{{ $LANG['required_field'] ?? 'Required' }}</div>
				</td>
			</tr>
			<tr>
				<th>{{ $LANG['payment_term_label'] ?? 'Label' }}</th>
				<td>
					<input type="text" name="term_label" value="{{ $term['term_label'] ?? '' }}" class="form-control" maxlength="120" required autocomplete="off" />
				</td>
			</tr>
			<tr>
				<th>{{ $LANG['payment_term_calc_kind'] ?? 'Calculation' }}</th>
				<td>
					<select name="calc_kind" class="form-select" required>
						@foreach(($calcKinds ?? []) as $k)
							<option value="{{ $k }}" @if(($term['calc_kind'] ?? '') === $k) selected @endif>{{ $LANG['payment_term_kind_'.$k] ?? $k }}</option>
						@endforeach
					</select>
				</td>
			</tr>
			<tr>
				<th>{{ $LANG['payment_term_param'] ?? 'Parameter' }}</th>
				<td>
					<input type="text" name="param_int" value="{{ ($term['calc_kind'] ?? '') === 'EOM' ? '' : ($term['param_int'] ?? '') }}" class="form-control" inputmode="numeric" autocomplete="off" />
				</td>
			</tr>
			<tr>
				<th>{{ $LANG['payment_term_sort_order'] ?? 'Sort order' }}</th>
				<td>
					<input type="number" name="sort_order" value="{{ (int)($term['sort_order'] ?? 0) }}" class="form-control" step="1" />
				</td>
			</tr>
		</table>
	</div>
	<div class="card-footer">
		<div class="d-flex">
			<a href="./index.php?module=payment_terms&amp;view=manage" class="btn btn-link">{{ $LANG['cancel'] ?? '' }}</a>
			<button type="submit" class="btn btn-primary ms-auto" name="save_payment_term" value="1"><i class="ti ti-check me-1"></i>{{ $LANG['save'] ?? '' }}</button>
		</div>
	</div>
</div>

<input type="hidden" name="op" value="edit_payment_term" />
</form>

@endif
