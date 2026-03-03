{{-- * Script: add.tpl
* 	 Customers add template
*
* Last edited:
* 	 2008-08-25
*
* License:
*	 GPL v3 or above --}}

{{-- if customer is updated or saved. --}}

@if(post('name') != "" && post('name') != null )
	@include('customers.save')

@else
{{-- if  name was inserted --}}
@if(post('id') !=null)
{{-- <div class="validation_alert"><i class="ti ti-alert-circle"></i>
		You must enter a description for the Customer</div>
		<hr /> --}}
	@endif
<form name="frmpost" action="index.php?module=customers&amp;view=add" method="post" id="frmpost" onsubmit="return checkForm(this);">
<div class="card">
	<div class="card-header">
		<ul class="nav nav-tabs card-header-tabs" role="tablist">
			<li class="nav-item" role="presentation">
				<a class="nav-link active" href="#cust-add-details" data-bs-toggle="tab" role="tab">{{ $LANG['details'] ?? '' }}</a>
			</li>
			<li class="nav-item" role="presentation">
				<a class="nav-link" href="#cust-add-address" data-bs-toggle="tab" role="tab">{{ $LANG['street'] ?? 'Address' }}</a>
			</li>
			<li class="nav-item" role="presentation">
				<a class="nav-link" href="#cust-add-contact" data-bs-toggle="tab" role="tab">{{ $LANG['phone'] ?? 'Contact' }}</a>
			</li>
			<li class="nav-item" role="presentation">
				<a class="nav-link" href="#cust-add-custom" data-bs-toggle="tab" role="tab">{{ $LANG['custom_fields'] ?? '' }}</a>
			</li>
			<li class="nav-item" role="presentation">
				<a class="nav-link" href="#cust-add-notes" data-bs-toggle="tab" role="tab">{{ $LANG['notes'] ?? '' }}</a>
			</li>
		</ul>
	</div>
	<div class="card-body">
		<div class="tab-content">
			<div id="cust-add-details" class="tab-pane active" role="tabpanel">
				<div class="mb-3">
					<label class="form-label">{{ $LANG['customer_name'] ?? '' }}
						<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_required_field" title="{{ $LANG['required_field'] ?? '' }}"><i class="ti ti-asterisk text-danger"></i></a>
					</label>
					<input type="text" name="name" id="name" value="{{ post('name') }}" class="form-control validate[required]" />
				</div>
				<div class="mb-3">
					<label class="form-label">{{ $LANG['customer_department'] ?? '' }}</label>
					<input type="text" name="department" id="department" value="{{ post('department') }}" class="form-control" />
				</div>
				<div class="mb-3">
					<label class="form-label">{{ $LANG['customer_contact'] ?? '' }}
						<a rel="index.php?module=documentation&amp;view=view&amp;page=help_customer_contact" href="#" class="cluetip" title="{{ $LANG['customer_contact'] ?? '' }}"><i class="ti ti-help"></i></a>
					</label>
					<input type="text" name="attention" value="{{ post('attention') }}" class="form-control" />
				</div>
				<div class="mb-3">
					<label class="form-label">{{ $LANG['enabled'] ?? '' }}</label>
					{html_options name=enabled options=$enabled selected=1 class="form-select"}
				</div>
			</div>
			<div id="cust-add-address" class="tab-pane" role="tabpanel">
				<div class="mb-3">
					<label class="form-label">{{ $LANG['street'] ?? '' }}</label>
					<input type="text" name="street_address" value="{{ post('street_address') }}" class="form-control" />
				</div>
				<div class="mb-3">
					<label class="form-label">{{ $LANG['street2'] ?? '' }}
						<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_street2" title="{{ $LANG['street2'] ?? '' }}"><i class="ti ti-help"></i></a>
					</label>
					<input type="text" name="street_address2" value="{{ post('street_address2') }}" class="form-control" />
				</div>
				<div class="mb-3">
					<label class="form-label">{{ $LANG['city'] ?? '' }}</label>
					<input type="text" name="city" value="{{ post('city') }}" class="form-control" />
				</div>
				<div class="mb-3">
					<label class="form-label">{{ $LANG['state'] ?? '' }}</label>
					<input type="text" name="state" value="{{ post('state') }}" class="form-control" />
				</div>
				<div class="mb-3">
					<label class="form-label">{{ $LANG['zip'] ?? '' }}</label>
					<input type="text" name="zip_code" value="{{ post('zip_code') }}" class="form-control" />
				</div>
				<div class="mb-3">
					<label class="form-label">{{ $LANG['country'] ?? '' }}</label>
					<input type="text" name="country" value="{{ post('country') }}" class="form-control" />
				</div>
			</div>
			<div id="cust-add-contact" class="tab-pane" role="tabpanel">
				<div class="mb-3">
					<label class="form-label">{{ $LANG['phone'] ?? '' }}</label>
					<input type="text" name="phone" value="{{ post('phone') }}" class="form-control" />
				</div>
				<div class="mb-3">
					<label class="form-label">{{ $LANG['mobile_phone'] ?? '' }}</label>
					<input type="text" name="mobile_phone" value="{{ post('mobile_phone') }}" class="form-control" />
				</div>
				<div class="mb-3">
					<label class="form-label">{{ $LANG['fax'] ?? '' }}</label>
					<input type="text" name="fax" value="{{ post('fax') }}" class="form-control" />
				</div>
				<div class="mb-3">
					<label class="form-label">{{ $LANG['email'] ?? '' }}</label>
					<input type="text" name="email" value="{{ post('email') }}" class="form-control" />
				</div>
			</div>
			<div id="cust-add-custom" class="tab-pane" role="tabpanel">
				<div class="mb-3">
					<label class="form-label">{{ $customFieldLabel['customer_cf1'] ?? '' }}
						<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields" title="{{ $LANG['custom_fields'] ?? '' }}"><i class="ti ti-help"></i></a>
					</label>
					<input type="text" name="custom_field1" value="{{ post('custom_field1') }}" class="form-control" />
				</div>
				<div class="mb-3">
					<label class="form-label">{{ $customFieldLabel['customer_cf2'] ?? '' }}
						<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields" title="{{ $LANG['custom_fields'] ?? '' }}"><i class="ti ti-help"></i></a>
					</label>
					<input type="text" name="custom_field2" value="{{ post('custom_field2') }}" class="form-control" />
				</div>
				<div class="mb-3">
					<label class="form-label">{{ $customFieldLabel['customer_cf3'] ?? '' }}
						<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields" title="{{ $LANG['custom_fields'] ?? '' }}"><i class="ti ti-help"></i></a>
					</label>
					<input type="text" name="custom_field3" value="{{ post('custom_field3') }}" class="form-control" />
				</div>
				<div class="mb-3">
					<label class="form-label">{{ $customFieldLabel['customer_cf4'] ?? '' }}
						<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields" title="{{ $LANG['custom_fields'] ?? '' }}"><i class="ti ti-help"></i></a>
					</label>
					<input type="text" name="custom_field4" value="{{ post('custom_field4') }}" class="form-control" />
				</div>
				@showCustomFields(2, '')
			</div>
			<div id="cust-add-notes" class="tab-pane" role="tabpanel">
				<div class="mb-3">
					<label class="form-label">{{ $LANG['notes'] ?? '' }}</label>
					<textarea name="notes" class="form-control editor" rows="8">{!! outhtml(post('notes')) !!}</textarea>
				</div>
			</div>
		</div>
	</div>
	<div class="card-footer text-end">
		<button type="submit" class="btn btn-primary" name="id" value="{{ $LANG['save'] ?? '' }}">
			<i class="ti ti-check"></i>
			{{ $LANG['save'] ?? '' }}
		</button>
		<a href="./index.php?module=customers&amp;view=manage" class="btn btn-outline-secondary">
			<i class="ti ti-x"></i>
			{{ $LANG['cancel'] ?? '' }}
		</a>
	</div>
</div>

<input type="hidden" name="op" value="insert_customer" />
</form>
@endif
