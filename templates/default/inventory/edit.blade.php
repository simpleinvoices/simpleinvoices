@if($saved == 'true' )

	<meta http-equiv="refresh" content="2;URL=index.php?module=inventory&amp;view=manage" />
	<div class="si_message_ok">{{ $LANG['save_inventory_success'] ?? '' }}</div>

@endif

@if($saved == 'false' )

	<meta http-equiv="refresh" content="2;URL=index.php?module=inventory&amp;view=manage" />
	<div class="si_message_error">{{ $LANG['save_inventory_failure'] ?? '' }}</div>

@endif

@if($saved ==false)
	@if(post('op') == 'add' AND post('product_id') == '') 
		<div class="validation_alert"><img src="./images/common/important.png" alt="" />
		You must select a product</div>
		<hr />
	@endif


{{-- is this still needed ? --}}
<div id="gmail_loading" class="gmailLoader" style="float:right; display: none;"><img src="images/common/gmail-loader.gif" alt="{{ $LANG['loading'] ?? '' }} ..." /> {{ $LANG['loading'] ?? '' }} ...</div>


<form name="frmpost" action="index.php?module=inventory&view=edit&id={{ urlencode($inventory['id'] ?? '') }}" method="POST" id="frmpost">

<div class="card">
	<div class="card-body">
		<table class="table table-vcenter">
			<tr>
				<th>{{ $LANG['date_upper'] ?? '' }}</th>
				<td>
					<input type="text" name="date" id="date" size="10" value="{{ $inventory['date'] ?? '' }}" class="form-control validate[required,custom[date],length[0,10]] date-picker" />
				</td>
			</tr>
			<tr>
				<th>{{ $LANG['product'] ?? '' }}</th>
				<td>
					<select name="product_id" class="form-select validate[required] product_inventory_change">
						<option value=""></option>
						@foreach(($product_all ?? []) as $product)
							<option value="{{ $product['id'] ?? '' }}" @if(($product['id'] ?? '') == ($inventory['product_id'] ?? '')) selected @endif>{{ $product['description'] ?? '' }}</option>
						@endforeach
					</select>
				</td>
			</tr>
			<tr>
				<th>{{ $LANG['quantity'] ?? '' }}</th>
				<td>
					<input name="quantity" size="10" value="{{ siLocal::number($inventory['quantity'] ?? '') }}" class="form-control validate[required]" />
				</td>
			</tr>
			<tr>
				<th>{{ $LANG['cost'] ?? '' }}</th>
				<td>
					<input id="cost" name="cost" size="10" value="{{ siLocal::number($inventory['cost'] ?? '') }}" class="form-control validate[required]" />
				</td>
			</tr>
			<tr>
				<th>{{ $LANG['notes'] ?? '' }}</th>
				<td><textarea name="note" class="form-control editor" rows="8" cols="50">{!! outhtml($inventory['note'] ?? '') !!}</textarea></td>
			</tr>
		</table>
	</div>
	<div class="card-footer">
		<div class="d-flex">
			<a href="./index.php?module=inventory&view=manage" class="btn btn-link">{{ $LANG['cancel'] ?? '' }}</a>
			<button type="submit" class="btn btn-primary ms-auto" name="id" value="{{ $LANG['save'] ?? '' }}"><i class="ti ti-check me-1"></i>{{ $LANG['save'] ?? '' }}</button>
		</div>
	</div>
</div>

<input type="hidden" name="op" value="edit" />
</form>
@endif
