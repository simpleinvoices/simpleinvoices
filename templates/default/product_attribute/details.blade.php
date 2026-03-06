<!--Modified code to display apostrophes in text box output 05/02/2008-Gates-->
<form name="frmpost"
	action="index.php?module=product_attribute&amp;view=save&amp;id={{ get('id') }}"
	method="post">


@if(get('action')== 'view' )
<div class="card">
	<div class="card-header">
		<div class="card-actions">
			<a href="index.php?module=product_attribute&amp;view=details&amp;id={{ $product_attribute['id'] }}&amp;action=edit" class="btn btn-primary"><i class="ti ti-edit me-1"></i>{{ $LANG['edit'] ?? '' }}</a>
		</div>
	</div>
	<div class="card-body">
	<table class="table table-vcenter">
		<tr>
  			<td class="details_screen">{{ $LANG['id'] ?? '' }}</td><td>{{ $product_attribute['id'] }}</td>
                </tr>
		<tr>	
			<td class="details_screen">{{ $LANG['name'] ?? '' }}</td><td>{{ $product_attribute['name'] }}</td>
        </tr>
		<tr>
			<th>{{ $LANG['type'] ?? '' }}</th>
			<td>{{ $product_attribute['type'] ?? '' | capitalize }}</td>
		</tr>
		<tr>
			<th>{{ $LANG['enabled'] ?? '' }}</th>
			<td>{{ $product_attribute['wording_for_enabled'] ?? '' }}</td>
		</tr>
		<tr>
			<th>{{ $LANG['visible'] ?? '' }}</th>
			<td>{{ $product_attribute['wording_for_visible'] ?? '' }}</td>
		</tr>
		</table>
	</div>
</div>

@endif

@if(get('action')== 'edit' )

<div class="card">
	<div class="card-body">
        <table class="table table-vcenter">
                <tr>
                        <td class="details_screen">{{ $LANG['id'] ?? '' }}</td><td>{{ $product_attribute['id'] }}</td>
                </tr>
                <tr>
                        <td class="details_screen">{{ $LANG['name'] ?? '' }}</td><td><input type="text" name="name" value="{{ $product_attribute['name'] }}" size="50" class="form-control" /></td>
                </tr>
		<tr>
			<th>{{ $LANG['type'] ?? '' }}</th>
			<td>
                <select name="type_id" class="form-select">
                    @foreach(($types ?? []) as $k => $v)
        				<option value="{{ $v['id'] }}" @if($v['id'] == $product_attribute['type_id']) selected @endif>{{ $LANG[$v['id']] ?? '' }}</option>
                    @endforeach
                </select>
			</td>
		</tr>
                <tr>
		<th>{{ $LANG['enabled'] ?? '' }}</th>
		<td>
			{html_options name=enabled options=$enabled selected=$product_attribute['enabled'] class="form-select"}
		</td>
                </tr>
                <tr>
		<th>{{ $LANG['visible'] ?? '' }}</th>
		<td>
			{html_options name=visible options=$enabled selected=$product_attribute['visible'] class="form-select"}
		</td>
                </tr>
                </table>
	</div>
	<div class="card-footer text-end">
		<button type="submit" class="btn btn-primary" name="save_product_attribute" value="{{ $LANG['save'] ?? '' }}"><i class="ti ti-check me-1"></i>{{ $LANG['save'] ?? '' }}</button>
		<input type="hidden" name="op" value="edit_product_attribute" />
	</div>
</div>
@endif
</form>
