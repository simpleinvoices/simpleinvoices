<form name="frmpost" action="index.php?module=products&view=save&id={{ urlencode(get('id')) }}" method="post" id="frmpost" onsubmit="return checkForm(this);">

@if(get('action')== 'view' )
<div class="card">
	<div class="card-header">
		<ul class="nav nav-tabs card-header-tabs" role="tablist">
			<li class="nav-item" role="presentation">
				<a class="nav-link active" href="#view-section-1" data-bs-toggle="tab" role="tab"><i class="ti ti-info-circle me-1"></i>{{ $LANG['details'] ?? '' }}</a>
			</li>
			<li class="nav-item" role="presentation">
				<a class="nav-link" href="#view-section-2" data-bs-toggle="tab" role="tab"><i class="ti ti-adjustments me-1"></i>{{ $LANG['custom_fields'] ?? '' }}</a>
			</li>
			<li class="nav-item" role="presentation">
				<a class="nav-link" href="#view-section-3" data-bs-toggle="tab" role="tab"><i class="ti ti-notes me-1"></i>{{ $LANG['notes'] ?? '' }}</a>
			</li>
		</ul>
	</div>
	<div class="card-body">
		<div class="tab-content">
			<div id="view-section-1" class="tab-pane active" role="tabpanel">
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
							<th>{{ $LANG['cost'] ?? '' }}</th>
							<td>{{ siLocal::number($product['cost'] ?? '') }}</td>
						</tr>
						<tr>
							<th>{{ $LANG['reorder_level'] ?? '' }}</th>
							<td>{{ $product['reorder_level'] }}</td>
						</tr>
					@endif
					<tr>
						<th>{{ $LANG['default_tax'] ?? '' }}</th>
						<td>{{ $tax_selected['tax_description'] ?? '' }} {{ $tax_selected['type'] ?? '' }}</td>
					</tr>
					<tr>
						<th>{{ $LANG['product_enabled'] ?? '' }}</th>
						<td>{{ $product['wording_for_enabled'] ?? '' }}</td>
					</tr>
				</table>
			</div>
			<div id="view-section-2" class="tab-pane" role="tabpanel">
				<table class="table table-vcenter table-wrap">
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
					@showCustomFields(3, get('id'))
					@if($defaults->product_attributes)
						<tr>
							<th class="details_screen">{{ $LANG['product_attributes'] ?? '' }}</th>
							<td></td>
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
				</table>
			</div>
			<div id="view-section-3" class="tab-pane" role="tabpanel">
				<table class="table table-vcenter table-wrap">
					<tr>
						<th>{{ $LANG['notes'] ?? '' }}</th>
						<td>{{ $product['notes'] ?? '' | unescape }}</td>
					</tr>
					<tr>
						<th class="details_screen">{{ $LANG['note_attributes'] ?? '' }}</th>
						<td></td>
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
				</table>
			</div>
		</div>
	</div>
	<div class="card-footer">
		<div class="d-flex">
			<a href="./index.php?module=products&amp;view=manage" class="btn btn-link">{{ $LANG['cancel'] ?? '' }}</a>
			<a href="./index.php?module=products&amp;view=details&amp;id={{ $product['id'] ?? '' }}&amp;action=edit" class="btn btn-primary ms-auto"><i class="ti ti-edit me-1"></i>{{ $LANG['edit'] ?? '' }}</a>
		</div>
	</div>
</div>
@endif


@if(get('action')== 'edit' )
<div class="card">
	<div class="card-header">
		<ul class="nav nav-tabs card-header-tabs" role="tablist">
			<li class="nav-item" role="presentation">
				<a class="nav-link active" href="#edit-section-1" data-bs-toggle="tab" role="tab"><i class="ti ti-info-circle me-1"></i>{{ $LANG['details'] ?? '' }}</a>
			</li>
			<li class="nav-item" role="presentation">
				<a class="nav-link" href="#edit-section-2" data-bs-toggle="tab" role="tab"><i class="ti ti-adjustments me-1"></i>{{ $LANG['custom_fields'] ?? '' }}</a>
			</li>
			<li class="nav-item" role="presentation">
				<a class="nav-link" href="#edit-section-3" data-bs-toggle="tab" role="tab"><i class="ti ti-notes me-1"></i>{{ $LANG['notes'] ?? '' }}</a>
			</li>
		</ul>
	</div>
	<div class="card-body">
		<div class="tab-content">
			<div id="edit-section-1" class="tab-pane active" role="tabpanel">
				<div class="mb-3">
					<label class="form-label">{{ $LANG['product_description'] ?? '' }}
						<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_required_field" title="{{ $LANG['required_field'] ?? '' }}"><i class="ti ti-asterisk text-danger"></i></a>
					</label>
					<input type="text" name="description" value="{{ $product['description'] ?? '' }}" id="description" class="form-control validate[required]" />
				</div>
				<div class="mb-3">
					<label class="form-label">{{ $LANG['product_unit_price'] ?? '' }}</label>
					<input type="text" name="unit_price" value="{{ siLocal::number_clean($product['unit_price'] ?? '') }}" class="form-control" />
				</div>
				@if($defaults->inventory == '1')
					<div class="mb-3">
						<label class="form-label">{{ $LANG['cost'] ?? '' }}
							<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_cost" title="{{ $LANG['cost'] ?? '' }}"><i class="ti ti-help"></i></a>
						</label>
						<input type="text" class="form-control" name="cost" value="{{ siLocal::number($product['cost'] ?? '') }}" />
					</div>
					<div class="mb-3">
						<label class="form-label">{{ $LANG['reorder_level'] ?? '' }}</label>
						<input type="text" class="form-control" name="reorder_level" value="{{ $product['reorder_level'] ?? '' }}" />
					</div>
				@endif
				<div class="mb-3">
					<label class="form-label">{{ $LANG['default_tax'] ?? '' }}</label>
					<select name="default_tax_id" class="form-select">
						<option value=''></option>
						@foreach(($taxes ?? []) as $tax)
							<option value="{{ $tax['tax_id'] ?? '' }}" @if(($product['default_tax_id'] ?? '') == ($tax['tax_id'] ?? '')) selected @endif>{{ $tax['tax_description'] ?? '' }}</option>
						@endforeach
					</select>
				</div>
				<div class="mb-3">
					<label class="form-label">{{ $LANG['product_enabled'] ?? '' }}</label>
					{html_options class="form-select" name=enabled options=$enabled selected=$product['enabled']}
				</div>
			</div>
			<div id="edit-section-2" class="tab-pane" role="tabpanel">
				<div class="mb-3">
					<label class="form-label">{{ $customFieldLabel['product_cf1'] ?? '' }}
						<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields" title="{{ $LANG['custom_fields'] ?? '' }}"><i class="ti ti-help"></i></a>
					</label>
					<input type="text" class="form-control" name="custom_field1" value="{{ $product['custom_field1'] ?? '' }}" />
				</div>
				<div class="mb-3">
					<label class="form-label">{{ $customFieldLabel['product_cf2'] ?? '' }}
						<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields" title="{{ $LANG['custom_fields'] ?? '' }}"><i class="ti ti-help"></i></a>
					</label>
					<input type="text" class="form-control" name="custom_field2" value="{{ $product['custom_field2'] ?? '' }}" />
				</div>
				<div class="mb-3">
					<label class="form-label">{{ $customFieldLabel['product_cf3'] ?? '' }}
						<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields" title="{{ $LANG['custom_fields'] ?? '' }}"><i class="ti ti-help"></i></a>
					</label>
					<input type="text" class="form-control" name="custom_field3" value="{{ $product['custom_field3'] ?? '' }}" />
				</div>
				<div class="mb-3">
					<label class="form-label">{{ $customFieldLabel['product_cf4'] ?? '' }}
						<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields" title="{{ $LANG['custom_fields'] ?? '' }}"><i class="ti ti-help"></i></a>
					</label>
					<input type="text" class="form-control" name="custom_field4" value="{{ $product['custom_field4'] ?? '' }}" />
				</div>
				@if($defaults->product_attributes)
					<div class="mb-3">
						<label class="form-label">{{ $LANG['product_attributes'] ?? '' }}</label>
						<div>
							@foreach(($attributes ?? []) as $attribute)
								{assign var="i" value=$attribute['id']}
								<div class="form-check">
									<input type="checkbox" class="form-check-input" name="attribute{{ $i }}" value="true" id="edit_attr_{{ $i }}" @if(($product['attribute_decode'][$i] ?? '') == 'true') checked @endif />
									<label class="form-check-label" for="edit_attr_{{ $i }}">{{ $attribute['name'] }}</label>
								</div>
							@endforeach
						</div>
					</div>
				@endif
			</div>
			<div id="edit-section-3" class="tab-pane" role="tabpanel">
				<div class="mb-3">
					<label class="form-label">{{ $LANG['notes'] ?? '' }}</label>
					<textarea class="form-control editor" name="notes" rows="8">{{ $product['notes'] ?? '' | unescape }}</textarea>
				</div>
				<div class="mb-3">
					<label class="form-label">{{ $LANG['note_attributes'] ?? '' }}</label>
					<div class="form-check">
						<input type="checkbox" class="form-check-input" name="notes_as_description" value='true' id="edit_notes_as_desc" @if($product['notes_as_description'] == 'Y') checked @endif />
						<label class="form-check-label" for="edit_notes_as_desc">{{ $LANG['note_as_description'] ?? '' }}</label>
					</div>
					<div class="form-check">
						<input type="checkbox" class="form-check-input" name="show_description" value='true' id="edit_show_desc" @if($product['show_description'] == 'Y') checked @endif />
						<label class="form-check-label" for="edit_show_desc">{{ $LANG['note_expand'] ?? '' }}</label>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="card-footer">
		<div class="d-flex">
			<a href="./index.php?module=products&amp;view=manage" class="btn btn-link">{{ $LANG['cancel'] ?? '' }}</a>
			<button type="submit" class="btn btn-primary ms-auto" name="save_product" value="{{ $LANG['save'] ?? '' }}"><i class="ti ti-check me-1"></i>{{ $LANG['save'] ?? '' }}</button>
		</div>
	</div>
</div>
<input type="hidden" name="op" value="edit_product">
@endif
</form>
