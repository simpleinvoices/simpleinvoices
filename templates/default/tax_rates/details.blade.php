<form name="frmpost" action="index.php?module=tax_rates&amp;view=save&amp;id={{ urlencode(get('id')) }}" method="post" onsubmit="return frmpost_Validator(this)">

@if(get('action') === 'view' )

<div class="card">
	<div class="card-body">
	<table class="table table-vcenter">
		<tr>
			<th>{{ $LANG['description'] ?? '' }}</th>
			<td>{{ $tax['tax_description'] ?? '' }}</td>
		</tr>
		<tr>
			<th>{{ $LANG['rate'] ?? '' }}
				<a 
					class="cluetip"
					href="#"
					rel="index.php?module=documentation&amp;view=view&amp;page=help_tax_rate_sign"
					title="{{ $LANG['tax_rate'] ?? '' }}"
				>
				<i class="ti ti-help"></i>
				</a>
			</th>
			<td>
				{{ siLocal::number($tax['tax_percentage'] ?? '') }} {{ $tax['type'] ?? '' }}
			</td>
		</tr>
		<tr>
			<th>{{ $LANG['enabled'] ?? '' }}</th>
			<td>{{ $tax['enabled'] ?? '' }}</td>
		</tr>
	</table>
	</div>
	<div class="card-footer">
		<div class="d-flex">
			<a href="./index.php?module=tax_rates&amp;view=manage" class="btn btn-link">{{ $LANG['cancel'] ?? '' }}</a>
			<a href="./index.php?module=tax_rates&amp;view=details&amp;id={{ urlencode($tax['tax_id'] ?? '') }}&amp;action=edit" class="btn btn-primary ms-auto"><i class="ti ti-edit me-1"></i>{{ $LANG['edit'] ?? '' }}</a>
		</div>
	</div>
</div>
@endif




@if(get('action') === 'edit')

<div class="card">
	<div class="card-body">
	<table class="table table-vcenter">
		<tr>
			<th>{{ $LANG['description'] ?? '' }}</th>
			<td><input type="text" name="tax_description" value="{{ $tax['tax_description'] ?? '' }}" class="form-control validate[required]" size="25" /></td>
		</tr>
		<tr>
			<th>{{ $LANG['rate'] ?? '' }}
			<a 
				class="cluetip"
				href="#"
				rel="index.php?module=documentation&amp;view=view&amp;page=help_tax_rate_sign"
				title="{{ $LANG['tax_rate'] ?? '' }}"
			>
				<i class="ti ti-help"></i>
			</a>
			</th>
			<td>
				<input type="text" name="tax_percentage" value="{{ siLocal::number($tax['tax_percentage'] ?? '') }}" size="10" class="form-control" />
				{html_options name=type options=$types selected=$tax['type'] class="form-select"}
			</td>
		</tr>
		<tr>
			<th>{{ $LANG['enabled'] ?? '' }} </th>
			<td>
				<select name="tax_enabled" class="form-select">
					<option value="{{ $tax['tax_enabled'] ?? '' }}" selected style="font-weight: bold">{{ $tax['enabled'] ?? '' }}</option>
					<option value="1">{{ $LANG['enabled'] ?? '' }}</option>
					<option value="0">{{ $LANG['disabled'] ?? '' }}</option>
				</select>
			</td>
		</tr>
	</table>

	<div class="card-footer">
		<div class="d-flex">
			<a href="./index.php?module=tax_rates&view=manage" class="btn btn-link">{{ $LANG['cancel'] ?? '' }}</a>
			<button type="submit" class="btn btn-primary ms-auto" name="save_tax_rate" value="{{ $LANG['save_tax_rate'] ?? '' }}"><i class="ti ti-check me-1"></i>{{ $LANG['save'] ?? '' }}</button>
		</div>
	</div>
	</div>
</div>

<input type="hidden" name="op" value="edit_tax_rate" />
@endif
</form>

