<form name="frmpost"
	action="index.php?module=product_value&amp;view=save&amp;id={{ get('id') }}"
	method="post">


@if(get('action')== 'view' )
<div class="card">
	<div class="card-body">
	<table class="table table-vcenter">
		<tr>
  			<td class="details_screen">{{ $LANG['id'] ?? '' }}</td><td>{{ $product_value['id'] }}</td>
                </tr>
		<tr>	
			<td class="details_screen">{{ $LANG['attribute'] ?? '' }}</td><td>{{ $product_attribute }}</td>
		</tr>
		<tr>	
			<td class="details_screen">{{ $LANG['value'] ?? '' }}</td><td>{{ $product_value['value'] }}</td>
		</tr>
		<tr>
			<th>{{ $LANG['enabled'] ?? '' }}</th>
			<td>{{ $product['wording_for_enabled'] ?? '' }}</td>
		</tr>
		</table>
	</div>
	<div class="card-footer">
		<div class="d-flex">
			<a href="./index.php?module=product_value&amp;view=manage" class="btn btn-link">{{ $LANG['cancel'] ?? '' }}</a>
			<a href="./index.php?module=product_value&amp;view=details&amp;id={{ $product_value['id'] ?? '' }}&amp;action=edit" class="btn btn-primary ms-auto"><i class="ti ti-edit me-1"></i>{{ $LANG['edit'] ?? '' }}</a>
		</div>
	</div>
</div>

@endif

@if(get('action')== 'edit' )

<div class="card">
	<div class="card-body">
	<table class="table table-vcenter">
		<tr>
			<td class="details_screen">{{ $LANG['id'] ?? '' }}</td><td>{{ $product_value['id'] }}</td>
		</tr>
		<tr>
			<td class="details_screen">{{ $LANG['attribute'] ?? '' }}</td>
			<td>
		            <select name="attribute_id" class="form-select">
			            @foreach(($product_attributes ?? []) as $product_attribute)
			                <option @if($product_attributes == $product_value['attribute_id']) selected @endif value="{{ $product_attribute['id'] }}">{{ $product_attribute['name'] }}</option>
			            @endforeach
		            </select>
			</td>
		</tr>
		<tr>
			<td class="details_screen">{{ $LANG['value'] ?? '' }}</td><td><input type="text" name="value" value="{{ $product_value['value'] }}" size="50" class="form-control" /></td>
		</tr>
		<tr>
			<th>{{ $LANG['enabled'] ?? '' }}</th>
			<td>
				{html_options name=enabled options=$enabled selected=$product_attribute['enabled'] class="form-select"}
			</td>
		</tr>
	</table>
	</div>
	<div class="card-footer">
		<div class="d-flex">
			<a href="./index.php?module=product_value&amp;view=manage" class="btn btn-link">{{ $LANG['cancel'] ?? '' }}</a>
			<input type="hidden" name="op" value="edit_product_value" />
			<button type="submit" class="btn btn-primary ms-auto" name="save_product_value" value="{{ $LANG['save'] ?? '' }}"><i class="ti ti-check me-1"></i>{{ $LANG['save'] ?? '' }}</button>
		</div>
	</div>
</div>
@endif
</form>
