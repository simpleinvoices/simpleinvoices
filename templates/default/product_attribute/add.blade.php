
{{-- if customer is updated or saved. --}}

@if(post('name') != "" && form_submitted() )
{{ $refresh_total }}

<br />
<br />
{{ $display_block }}
<br />
<br />

@else

<form name="frmpost" action="index.php?module=product_attribute&amp;view=add" method="post" class="needs-validation" novalidate>

<div class="card">
	<div class="card-body">
		<div class="mb-3">
			<label class="form-label">{{ $LANG['name'] ?? '' }}</label>
			<input type="text" name="name" value="{{ post('name') }}" size="25" class="form-control" required />
			<div class="invalid-feedback">{{ $LANG['required_field'] ?? 'Required' }}</div>
		</div>
		<div class="mb-3">
			<label class="form-label">{{ $LANG['type'] ?? '' }}</label>
			<select name="type_id" class="form-select">
				@foreach(($types ?? []) as $k => $v)
					<option value="{{ $v['id'] }}">{{ $LANG[$v['id']] ?? '' }}</option>
				@endforeach
			</select>
		</div>
		<div class="mb-3">
			<label class="form-label">{{ $LANG['enabled'] ?? '' }}</label>
			{html_options name=enabled options=$enabled selected=1 class="form-select"}
		</div>
		<div class="mb-3">
			<label class="form-label">{{ $LANG['visible'] ?? '' }}</label>
			{html_options name=visible options=$enabled selected=1 class="form-select"}
		</div>
	</div>
	<div class="card-footer">
		<div class="d-flex">
			<a href="./index.php?module=product_attribute&amp;view=manage" class="btn btn-link">{{ $LANG['cancel'] ?? '' }}</a>
			<input type="hidden" name="op" value="insert_product_attribute" />
			<button type="submit" class="btn btn-primary ms-auto" name="submit" value="{{ $LANG['insert_product_attribute'] ?? '' }}"><i class="ti ti-check me-1"></i>{{ $LANG['save'] ?? '' }}</button>
		</div>
	</div>
</div>
</form>

@endif
