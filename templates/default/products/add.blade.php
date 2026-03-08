{{-- if bill is updated or saved. --}}

@if(post('description') != "" && post('id') != null ) 

	@include('products.save')

@else
{{-- if  name was inserted --}} 

	@if(post('id') !=null) 
		<div class="alert alert-warning"><i class="ti ti-alert-circle"></i>
		{{ $LANG['product_description_prompt'] ?? '' }}</div>
	@endif

<form name="frmpost" action="index.php?module=products&view=add" method="POST" id="frmpost" onsubmit="return checkForm(this);">
<div class="card">
	<div class="card-header">
		<ul class="nav nav-tabs card-header-tabs" role="tablist">
			<li class="nav-item" role="presentation">
				<a class="nav-link active" href="#section-1" data-bs-toggle="tab" role="tab">{{ $LANG['details'] ?? '' }}</a>
			</li>
			<li class="nav-item" role="presentation">
				<a class="nav-link" href="#section-2" data-bs-toggle="tab" role="tab">{{ $LANG['custom_fields'] ?? '' }}</a>
			</li>
			<li class="nav-item" role="presentation">
				<a class="nav-link" href="#section-3" data-bs-toggle="tab" role="tab">{{ $LANG['notes'] ?? '' }}</a>
			</li>
		</ul>
	</div>
	<div class="card-body">
		<div class="tab-content">
			<div id="section-1" class="tab-pane active" role="tabpanel">
				<div class="mb-3">
					<label class="form-label">{{ $LANG['description'] ?? '' }}
					<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_required_field" title="{{ $LANG['required_field'] ?? '' }}"><i class="ti ti-asterisk text-danger"></i></a>
					</label>
					<input type="text" name="description" value="{{ post('description') }}" id="description" class="form-control validate[required]" />
				</div>
				<div class="mb-3">
					<label class="form-label">{{ $LANG['unit_price'] ?? '' }}</label>
					<input type="text" class="form-control" name="unit_price" value="{{ post('unit_price') }}" />
				</div>
				@if($defaults->inventory == '1')
					<div class="mb-3">
						<label class="form-label">
							{{ $LANG['cost'] ?? '' }} <a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_cost" title="{{ $LANG['cost'] ?? '' }}"><i class="ti ti-help"></i></a>
						</label>
						<input type="text" class="form-control" name="cost" value="{{ post('cost') }}" />
					</div>
					<div class="mb-3">
						<label class="form-label">{{ $LANG['reorder_level'] ?? '' }}</label>
						<input type="text" class="form-control" name="reorder_level" value="{{ post('reorder_level') }}" />
					</div>
				@endif
				<div class="mb-3">
					<label class="form-label">{{ $LANG['default_tax'] ?? '' }}</label>
					<select name="default_tax_id" class="form-select">
						<option value=''></option>
						@foreach(($taxes ?? []) as $tax)
							<option value="{{ $tax['tax_id'] ?? '' }}">{{ $tax['tax_description'] ?? '' }}</option>
						@endforeach
					</select>
				</div>
				<div class="mb-3">
					<label class="form-label">{{ $LANG['enabled'] ?? '' }}</label>
					{html_options class="form-select" name=enabled options=$enabled selected=1}
				</div>
			</div>
			<div id="section-2" class="tab-pane" role="tabpanel">
				<div class="mb-3">
					<label class="form-label">{{ $customFieldLabel['product_cf1'] ?? '' }}
					<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields" title="{{ $LANG['custom_fields'] ?? '' }}"><i class="ti ti-help"></i></a>
					</label>
					<input type="text" class="form-control" name="custom_field1" value="{{ post('custom_field1') }}" />
				</div>
				<div class="mb-3">
					<label class="form-label">{{ $customFieldLabel['product_cf2'] ?? '' }}
					<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields" title="{{ $LANG['custom_fields'] ?? '' }}"><i class="ti ti-help"></i></a>
					</label>
					<input type="text" class="form-control" name="custom_field2" value="{{ post('custom_field2') }}" />
				</div>
				<div class="mb-3">
					<label class="form-label">{{ $customFieldLabel['product_cf3'] ?? '' }}
					<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields" title="{{ $LANG['custom_fields'] ?? '' }}"><i class="ti ti-help"></i></a>
					</label>
					<input type="text" class="form-control" name="custom_field3" value="{{ post('custom_field3') }}" />
				</div>
				<div class="mb-3">
					<label class="form-label">{{ $customFieldLabel['product_cf4'] ?? '' }}
					<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields" title="{{ $LANG['custom_fields'] ?? '' }}"><i class="ti ti-help"></i></a>
					</label>
					<input type="text" class="form-control" name="custom_field4" value="{{ post('custom_field4') }}" />
				</div>
				@if($defaults->product_attributes)
					<div class="mb-3">
						<label class="form-label">{{ $LANG['product_attributes'] ?? '' }}</label>
						<div>
							@foreach(($attributes ?? []) as $attribute)
								<div class="form-check">
									<input type="checkbox" class="form-check-input" name="attribute{{ $attribute['id'] }}" value="true" id="attr_{{ $attribute['id'] }}" />
									<label class="form-check-label" for="attr_{{ $attribute['id'] }}">{{ $attribute['name'] }}</label>
								</div>
							@endforeach
						</div>
					</div>
				@endif
			</div>
			<div id="section-3" class="tab-pane" role="tabpanel">
				<div class="mb-3">
					<label class="form-label">{{ $LANG['notes'] ?? '' }}</label>
					<textarea class="form-control editor" name='notes' rows="8">{{ post('notes') | unescape }}</textarea>
				</div>
				<div class="mb-3">
					<label class="form-label">{{ $LANG['note_attributes'] ?? '' }}</label>
					<div class="form-check">
						<input type="checkbox" class="form-check-input" name="notes_as_description" value='true' id="notes_as_desc" />
						<label class="form-check-label" for="notes_as_desc">{{ $LANG['note_as_description'] ?? '' }}</label>
					</div>
					<div class="form-check">
						<input type="checkbox" class="form-check-input" name="show_description" value='true' id="show_desc" />
						<label class="form-check-label" for="show_desc">{{ $LANG['note_expand'] ?? '' }}</label>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="card-footer">
		<div class="d-flex">
			<a href="./index.php?module=products&view=manage" class="btn btn-link">{{ $LANG['cancel'] ?? '' }}</a>
			<button type="submit" class="btn btn-primary ms-auto" name="id" value="{{ $LANG['save'] ?? '' }}"><i class="ti ti-check me-1"></i>{{ $LANG['save'] ?? '' }}
			</button>
		</div>
	</div>
</div>

<input type="hidden" name="op" value="insert_product" />
</form>
@endif
