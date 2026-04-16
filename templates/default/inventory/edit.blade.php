@if($saved == 'true' )

	<meta http-equiv="refresh" content="2;URL=index.php?module=inventory&amp;view=manage" />
	<div class="alert alert-success">{{ $LANG['save_inventory_success'] ?? '' }}</div>

@endif

@if($saved == 'false' )

	<meta http-equiv="refresh" content="2;URL=index.php?module=inventory&amp;view=manage" />
	<div class="alert alert-danger">{{ $LANG['save_inventory_failure'] ?? '' }}</div>

@endif

@if($saved ==false)

<div id="gmail_loading" class="gmailLoader" style="float:right; display: none;"><i class="ti ti-loader"></i> {{ $LANG['loading'] ?? '' }} ...</div>

<form name="frmpost" action="index.php?module=inventory&view=edit&id={{ urlencode($inventory['id'] ?? '') }}" method="POST" id="frmpost" class="needs-validation" novalidate>

<div class="card">
	<div class="card-body">
		<div class="mb-3">
			<label class="form-label">{{ $LANG['date_upper'] ?? '' }}</label>
			<div class="input-icon">
				<span class="input-icon-addon"><i class="ti ti-calendar"></i></span>
				<input type="text" name="date" id="date" size="10" value="{{ $inventory['date'] ?? '' }}"
					class="form-control date-picker"
					required pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}" />
				<div class="invalid-feedback">{{ $LANG['required_field'] ?? 'Required' }}</div>
			</div>
		</div>
		<div class="mb-3">
			<label class="form-label">{{ $LANG['product'] ?? '' }}</label>
			<select name="product_id" class="form-select product_inventory_change" required>
				<option value=""></option>
				@foreach(($product_all ?? []) as $product)
					<option value="{{ $product['id'] ?? '' }}" @if(($product['id'] ?? '') == ($inventory['product_id'] ?? '')) selected @endif>{{ $product['description'] ?? '' }}</option>
				@endforeach
			</select>
			<div class="invalid-feedback">{{ $LANG['required_field'] ?? 'Required' }}</div>
		</div>
		<div class="mb-3">
			<label class="form-label">{{ $LANG['quantity'] ?? '' }}</label>
			<input name="quantity" size="10" value="{{ siLocal::number($inventory['quantity'] ?? '') }}" class="form-control" required />
			<div class="invalid-feedback">{{ $LANG['required_field'] ?? 'Required' }}</div>
		</div>
		<div class="mb-3">
			<label class="form-label">{{ $LANG['cost'] ?? '' }}</label>
			<input id="cost" name="cost" size="10" value="{{ siLocal::number($inventory['cost'] ?? '') }}" class="form-control" required />
			<div class="invalid-feedback">{{ $LANG['required_field'] ?? 'Required' }}</div>
		</div>
		<div class="mb-3">
			<label class="form-label">{{ $LANG['notes'] ?? '' }}</label>
			<textarea name="note" class="form-control editor" rows="8">{!! outhtml($inventory['note'] ?? '') !!}</textarea>
		</div>
	</div>
	<div class="card-footer">
		<div class="d-flex">
			<a href="./index.php?module=inventory&view=manage" class="btn btn-link">{{ $LANG['cancel'] ?? '' }}</a>
			<button type="submit" class="btn btn-primary ms-auto" name="id" value="{{ $LANG['save'] ?? '' }}"><i class="ti ti-check me-1"></i>{{ $LANG['save'] ?? '' }}
			</button>
		</div>
	</div>
</div>

<input type="hidden" name="op" value="edit" />
</form>
@endif
