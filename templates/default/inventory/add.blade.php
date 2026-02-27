@if($saved == 'true' )

	<meta http-equiv="refresh" content="2;URL=index.php?module=inventory&amp;view=manage" />
	<div class="alert alert-success">{{ $LANG['save_inventory_success'] ?? '' }}</div>

@endif

@if($saved == 'false' )

	<meta http-equiv="refresh" content="2;URL=index.php?module=inventory&amp;view=manage" />
	<div class="alert alert-danger">{{ $LANG['save_inventory_failure'] ?? '' }}</div>

@endif

@if($saved ==false)
	@if($smarty->post->op == 'add' AND $smarty->post->product_id == '') 
		<div class="alert alert-warning"><i class="ti ti-alert-circle"></i>
		You must select an product</div>
	@endif


{{-- is this still needed ? --}}
<div id="gmail_loading" class="gmailLoader" style="float:right; display: none;"><i class="ti ti-loader"></i> {{ $LANG['loading'] ?? '' }} ...</div>



<form name="frmpost" action="index.php?module=inventory&view=add" method="POST" id="frmpost">
<div class="card">
	<div class="card-header">
		<h3 class="card-title">{{ $LANG['add_inventory'] ?? $LANG['product'] ?? '' }}</h3>
	</div>
	<div class="card-body">
		<div class="mb-3">
			<label class="form-label">{{ $LANG['product'] ?? '' }}</label>
			<select name="product_id" class="form-select validate[required] product_inventory_change">
				<option value=''></option>
				@foreach(($product_all ?? []) as $product)
					<option value="{{ $product['id'] ?? '' }}">{{ $product['description'] ?? '' }}</option>
				@endforeach
			</select>
		</div>
		<div class="mb-3">
			<label class="form-label">{{ $LANG['quantity'] ?? '' }}</label>
			<input class="form-control validate[required]" name="quantity" size="10" />
		</div>
		<div class="mb-3">
			<label class="form-label">{{ $LANG['date_upper'] ?? '' }}</label>
			<input type="text" class="form-control validate[required,custom[date],length[0,10]] date-picker" size="10" name="date" id="date" value='{{ date('Y-m-d') }}' />
		</div>
		<div class="mb-3">
			<label class="form-label">{{ $LANG['cost'] ?? '' }}</label>
			<input class="form-control validate[required]" name="cost" id="cost" size="10" />
		</div>
		<div class="mb-3">
			<label class="form-label">{{ $LANG['notes'] ?? '' }}</label>
			<textarea name="note" class="form-control editor" rows="8">{!! outhtml($customer['notes'] ?? '') !!}</textarea>
		</div>
	</div>
	<div class="card-footer text-end">
		<button type="submit" class="btn btn-primary" name="id" value="{{ $LANG['save'] ?? '' }}">
			<i class="ti ti-check"></i>
			{{ $LANG['save'] ?? '' }}
		</button>
		<a href="./index.php?module=cron&view=manage" class="btn btn-outline-secondary">
			<i class="ti ti-x"></i>
			{{ $LANG['cancel'] ?? '' }}
		</a>
	</div>
</div>

<input type="hidden" name="op" value="add" />
</form>
@endif
