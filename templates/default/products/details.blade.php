<form name="frmpost" action="index.php?module=products&view=save&id={{ urlencode($smarty->get->id ?? '') }}" method="post" id="frmpost" onsubmit="return checkForm(this);">

@if($smarty->get->action== 'view' )
<div class="card">
	<div class="card-header">
		<h3 class="card-title">{{ $LANG['product'] ?? '' }} {{ $LANG['details'] ?? 'Details' }}</h3>
		<div class="card-actions">
			<a href="./index.php?module=products&view=details&id={{ $product['id'] ?? '' }}&action=edit" class="btn btn-primary"><i class="ti ti-edit me-1"></i>{{ $LANG['edit'] ?? '' }}</a>
		</div>
	</div>
	<div class="card-body">
		<table class="table table-vcenter table-wrap">
			<tr>
				<th>{{ $LANG['product_description'] ?? '' }}</th>
				<td>{{ $product['description'] ?? '' }}</td>
			</tr>
			<tr>
				<th>{{ $LANG['product_unit_price'] ?? '' }}</th>
				<td>{{ siLocal::number_clean($product['unit_price'] ?? '') }}</td>
			</tr>
			@if($defaults->inventory == '1')
				<tr>
					<th>
						{{ $LANG['cost'] ?? '' }}
					</th>
					<td>{{ siLocal::number($product['cost'] ?? '') }}</td>
				</tr>
				<tr>
					<th>{{ $LANG['reorder_level'] ?? '' }}</th>
					<td>{{ $product['reorder_level'] }}</td>
				</tr>
			@endif
			<tr>
				<th>{{ $LANG['default_tax'] ?? '' }}</th>
				<td>
					{{ $tax_selected['tax_description'] ?? '' }} {{ $tax_selected['type'] ?? '' }}
				</td>
			</tr>
			<tr>
				<th>{{ $customFieldLabel['product_cf1'] ?? '' }}</th>
				<td>{{ $product['custom_field1'] ?? '' }}</td>
			</tr>
			<tr>
				<th>{{ $customFieldLabel['product_cf2'] ?? '' }}</th>
				<td>{{ $product['custom_field2'] ?? '' }}</td>
			</tr>
			<tr>
				<th>{{ $customFieldLabel['product_cf3'] ?? '' }}</th>
				<td>{{ $product['custom_field3'] ?? '' }}</td>
			</tr>
			<tr>
				<th>{{ $customFieldLabel['product_cf4'] ?? '' }}</th>
				<td>{{ $product['custom_field4'] ?? '' }}</td>
			</tr>
				@showCustomFields(3, $smarty->get->id ?? '')
			@if($defaults->product_attributes)
				<tr>
					<th class="details_screen">{{ $LANG['product_attributes'] ?? '' }}</th>
					<td>
					</td>
				</tr>
				@foreach(($attributes ?? []) as $attribute)
					{assign var="i" value=$attribute['id']}

					@if(($product['attribute_decode'] ?? '') == '1' OR ($product['attribute_decode'][$i] ?? '') == 'true')
					<tr>
						<td></td>
						<th class="details_screen product_attribute"> 
						<input type="checkbox" disabled="disabled" name="attribute{{ $i }}" @if(($product['attribute_decode'][$i] ?? '') == 'true') checked @endif value="true"/>
						{{ $attribute['name'] }}
						</th>
					</tr>
					@endif
				@endforeach
			@endif
			<tr>
				<th>{{ $LANG['notes'] ?? '' }}</th>
				<td>{{ $product['notes'] ?? '' | unescape }}</td>
			</tr>
				<tr>
					<th class="details_screen">{{ $LANG['note_attributes'] ?? '' }}</th>
					<td>
					</td>
				</tr>
					<tr>
						<td></td>
						<th class="details_screen product_attribute">
						<input type="checkbox" disabled="disabled" name="notes_as_description" @if($product['notes_as_description']== 'Y') checked @endif value='true'/>
						{{ $LANG['note_as_description'] ?? '' }}
						</th>
					</tr>
					<tr>
						<td></td>
						<th class="details_screen product_attribute">
						<input type="checkbox" disabled="disabled" name="show_description" @if($product['show_description'] == 'Y') checked @endif value='true'/>
						{{ $LANG['note_expand'] ?? '' }}
						</th>
					</tr>
			<tr>
				<th>{{ $LANG['product_enabled'] ?? '' }}</th>
				<td>{{ $product['wording_for_enabled'] ?? '' }}</td>
			</tr>
		</table>
	</div>
</div>
@endif


@if($smarty->get->action== 'edit' )
<div class="card">
	<div class="card-header">
		<h3 class="card-title">{{ $LANG['edit'] ?? '' }} {{ $LANG['product'] ?? '' }}</h3>
	</div>
	<div class="card-body">
		<table class="table table-vcenter table-wrap">
		<tr>
			<th>{{ $LANG['product_description'] ?? '' }}</th>
			<td><input type="text" name="description" size="50" value="{{ $product['description'] ?? '' }}" id="description" class="form-control validate[required]" /></td>
		</tr>
		<tr>
			<th>{{ $LANG['product_unit_price'] ?? '' }}</th>
			<td><input type="text" name="unit_price" size="25" value="{{ siLocal::number_clean($product['unit_price'] ?? '') }}" class="form-control" /></td>
		</tr>

		@if($defaults->inventory == '1')
			<tr>
				<th>
					{{ $LANG['cost'] ?? '' }}
					<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_cost" title="{{ $LANG['cost'] ?? '' }}">
						<i class="ti ti-help"></i>
					</a>
				</th>
				<td><input type="text" class="form-control edit" name="cost" value="{{ siLocal::number($product['cost'] ?? '') }}" size="25" /></td>
			</tr>
			<tr>
				<th>{{ $LANG['reorder_level'] ?? '' }}</th>
				<td><input type="text" class="form-control edit" name="reorder_level" value="{{ $product['reorder_level'] ?? '' }}" size="25" /></td>
			</tr>
		@endif

		<tr>
			<th>{{ $LANG['default_tax'] ?? '' }}</th>
			<td>
			<select name="default_tax_id" class="form-select">
				@foreach(($taxes ?? []) as $tax)
					<option value="{{ $tax['tax_id'] ?? '' }}" @if($tax['tax_id'] == $tax['tax_id'])selected@endif>{{ $tax['tax_description'] ?? '' }}</option>
				@endforeach
			</select>
			</td>
		</tr>
		<tr>
			<th>{{ $customFieldLabel['product_cf1'] ?? '' }} 
				<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields" title="{{ $LANG['custom_fields'] ?? '' }}"><i class="ti ti-help"></i></a>
			</th>
			<td><input type="text" name="custom_field1" size="50" value="{{ $product['custom_field1'] ?? '' }}" class="form-control" /></td>
		</tr>
		<tr>
			<th>{{ $customFieldLabel['product_cf2'] ?? '' }} 
				<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields" title="{{ $LANG['custom_fields'] ?? '' }}"><i class="ti ti-help"></i></a>
			</th>
			<td><input type="text" name="custom_field2" size="50" value="{{ $product['custom_field2'] ?? '' }}" class="form-control" /></td>
		</tr>
		<tr>
			<th>{{ $customFieldLabel['product_cf3'] ?? '' }} 
				<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields" title="{{ $LANG['custom_fields'] ?? '' }}"><i class="ti ti-help"></i></a>
			</th>
			<td><input type="text" name="custom_field3" size="50" value="{{ $product['custom_field3'] ?? '' }}" class="form-control" /></td>
		</tr>
		<tr>
			<th>{{ $customFieldLabel['product_cf4'] ?? '' }} 
				<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields" title="{{ $LANG['custom_fields'] ?? '' }}"><i class="ti ti-help"></i></a>
			</th>
			<td><input type="text" name="custom_field4" size="50" value="{{ $product['custom_field4'] ?? '' }}" class="form-control" /></td>
		</tr>
		@if($defaults->product_attributes)
			<tr>
				<th class="details_screen">{{ $LANG['product_attributes'] ?? '' }}</th>
				<td>
				</td>
			</tr>
			@foreach(($attributes ?? []) as $attribute)
				{assign var="i" value=$attribute['id']}
				@if(($product['attribute_decode'] ?? '') == '1' OR ($product['attribute_decode'][$i] ?? '') == 'true')
				<tr>
					<td></td>
					<th class="details_screen product_attribute">
					<input type="checkbox" name="attribute{{ $i }}" @if(($product['attribute_decode'][$i] ?? '') == 'true') checked @endif value="true"/>
					{{ $attribute['name'] }}
					</th>
				</tr>
				@endif
			@endforeach
		@endif
		<tr>
			<th>{{ $LANG['notes'] ?? '' }}</th>
			<td><textarea name="notes" class="form-control editor" rows="8" cols="50">{{ $product['notes'] ?? '' | unescape }}</textarea></td>
		</tr>
		<tr>
				<tr>
					<th class="details_screen">{{ $LANG['note_attributes'] ?? '' }}</th>
					<td>
					</td>
				</tr>
					<tr>
						<td></td>
						<th class="details_screen product_attribute">
						<input type="checkbox" name="notes_as_description" @if($product['notes_as_description']== 'Y') checked @endif value='true'/>
						{{ $LANG['note_as_description'] ?? '' }}
						</th>
					</tr>
					<tr>
						<td></td>
						<th class="details_screen product_attribute">
						<input type="checkbox" name="show_description" @if($product['show_description'] == 'Y') checked @endif value='true'/>
						{{ $LANG['note_expand'] ?? '' }}
						</th>
					</tr>
			<th>{{ $LANG['product_enabled'] ?? '' }}</th>
			<td>
				{html_options name=enabled options=$enabled selected=$product['enabled']}
			</td>
		</tr>
		</table>

		<div class="card-footer text-end">
			<button type="submit" class="btn btn-primary" name="save_product" value="{{ $LANG['save'] ?? '' }}">
				<i class="ti ti-check me-1"></i> 
				{{ $LANG['save'] ?? '' }}
			</button>	
			<a href="./index.php?module=products&view=manage" class="btn btn-secondary">
				<i class="ti ti-x me-1"></i>
				{{ $LANG['cancel'] ?? '' }}
			</a>
		</div>
	</div>
</div>
<input type="hidden" name="op" value="edit_product">	
@endif
</form>
