@if(post('value') != "" && form_submitted() )
{{ $refresh_total }}

<br />
<br />
{{ $display_block }}
<br />
<br />

@else

<form name="frmpost" action="index.php?module=product_value&amp;view=add" method="post" class="needs-validation" novalidate>

<div class="card">
	<div class="card-body">
		<div class="mb-3">
			<label class="form-label">{{ $LANG['attribute'] ?? '' }}</label>
			<select name="attribute_id" class="form-select">
				@foreach(($product_attributes ?? []) as $product_attribute)
					<option value="{{ $product_attribute['id'] }}">{{ $product_attribute['name'] }}</option>
				@endforeach
			</select>
		</div>
		<div class="mb-3">
			<label class="form-label">{{ $LANG['value'] ?? '' }}</label>
			<input type="text" name="value" value="{{ post('value') }}" size="25" class="form-control" required />
			<div class="invalid-feedback">{{ $LANG['required_field'] ?? 'Required' }}</div>
		</div>
		<div class="mb-3">
			<label class="form-label">{{ $LANG['enabled'] ?? '' }}</label>
			{html_options name=enabled options=$enabled selected=1 class="form-select"}
		</div>
	</div>
	<div class="card-footer">
		<div class="d-flex">
			<a href="./index.php?module=product_value&amp;view=manage" class="btn btn-link">{{ $LANG['cancel'] ?? '' }}</a>
			<input type="hidden" name="op" value="insert_product_value" />
			<button type="submit" class="btn btn-primary ms-auto" name="submit" value="{{ $LANG['insert_product_value'] ?? '' }}"><i class="ti ti-check me-1"></i>{{ $LANG['save'] ?? '' }}</button>
		</div>
	</div>
</div>
</form>

@endif
