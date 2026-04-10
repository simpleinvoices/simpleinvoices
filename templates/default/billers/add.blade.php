{{-- * View: add (Blade)
* 	Biller add template
*
* Last edited:
* 	 2008-08-25
*
* License:
*	 GPL v3 or above --}}

{{-- if bill is updated or saved. --}}

@if(post('name') != "" && form_submitted())

	@include('billers.save')

@else

{{-- if no biller name was inserted --}}
<form name="frmpost" action="index.php?module=billers&amp;view=add" method="post" id="frmpost">

<div class="card">
	<div class="card-header">
		<ul class="nav nav-tabs card-header-tabs" role="tablist">
			<li class="nav-item" role="presentation">
				<a class="nav-link active" href="#bill-add-details" data-bs-toggle="tab" role="tab"><i class="ti ti-info-circle me-1"></i>{{ $LANG['details'] ?? '' }}</a>
			</li>
			<li class="nav-item" role="presentation">
				<a class="nav-link" href="#bill-add-address" data-bs-toggle="tab" role="tab"><i class="ti ti-map-pin me-1"></i>{{ $LANG['address'] ?? '' }}</a>
			</li>
			<li class="nav-item" role="presentation">
				<a class="nav-link" href="#bill-add-contact" data-bs-toggle="tab" role="tab"><i class="ti ti-phone me-1"></i>{{ $LANG['contacts'] ?? '' }}</a>
			</li>
			<li class="nav-item" role="presentation">
				<a class="nav-link" href="#bill-add-payment" data-bs-toggle="tab" role="tab"><i class="ti ti-credit-card me-1"></i>{{ $LANG['payment'] ?? '' }}</a>
			</li>
			<li class="nav-item" role="presentation">
				<a class="nav-link" href="#bill-add-custom" data-bs-toggle="tab" role="tab"><i class="ti ti-adjustments me-1"></i>{{ $LANG['custom_fields'] ?? '' }}</a>
			</li>
			<li class="nav-item" role="presentation">
				<a class="nav-link" href="#bill-add-invoice" data-bs-toggle="tab" role="tab"><i class="ti ti-file-invoice me-1"></i>{{ $LANG['invoice'] ?? '' }}</a>
			</li>
		</ul>
	</div>
	<div class="card-body">
		<div class="tab-content">
			<div id="bill-add-details" class="tab-pane active" role="tabpanel">
				<div class="mb-3">
					<label class="form-label">{{ $LANG['biller_name'] ?? '' }}
						<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_required_field" title="{{ $LANG['required_field'] ?? '' }}"><i class="ti ti-asterisk text-danger"></i></a>
					</label>
					<input type="text" name="name" value="{{ post('name') }}" id="name" class="form-control validate[required]" />
				</div>
				<div class="mb-3">
					<label class="form-label">{{ $LANG['email'] ?? '' }}</label>
					<input type="text" name="email" value="{{ post('email') }}" class="form-control" />
				</div>
				<div class="mb-3">
					<label class="form-label">{{ $LANG['enabled'] ?? '' }}</label>
					{html_options name=enabled options=$enabled selected=1 class="form-select"}
				</div>
			</div>
			<div id="bill-add-address" class="tab-pane" role="tabpanel">
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
			<div id="bill-add-contact" class="tab-pane" role="tabpanel">
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
			</div>
			<div id="bill-add-payment" class="tab-pane" role="tabpanel">
				<div class="mb-3">
					<label class="form-label">{{ $LANG['paypal_business_name'] ?? '' }}</label>
					<input type="text" name="paypal_business_name" value="{{ post('paypal_business_name') }}" class="form-control" />
				</div>
				<div class="mb-3">
					<label class="form-label">{{ $LANG['paypal_notify_url'] ?? '' }}</label>
					<input type="text" name="paypal_notify_url" value="{{ post('paypal_notify_url') }}" class="form-control" />
				</div>
				<div class="mb-3">
					<label class="form-label">{{ $LANG['paypal_return_url'] ?? '' }}</label>
					<input type="text" name="paypal_return_url" value="{{ post('paypal_return_url') }}" class="form-control" />
				</div>
				<div class="mb-3">
					<label class="form-label">{{ $LANG['eway_customer_id'] ?? '' }}</label>
					<input type="text" name="eway_customer_id" value="{{ post('eway_customer_id') }}" class="form-control" />
				</div>
				<div class="mb-3">
					<label class="form-label">{{ $LANG['paymentsgateway_api_id'] ?? '' }}</label>
					<input type="text" name="paymentsgateway_api_id" value="{{ post('paymentsgateway_api_id') }}" class="form-control" />
				</div>
			</div>
			<div id="bill-add-custom" class="tab-pane" role="tabpanel">
				<div class="mb-3">
					<label class="form-label">{{ $customFieldLabel['biller_cf1'] ?? '' }}
						<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields" title="{{ $LANG['custom_fields'] ?? '' }}"><i class="ti ti-help"></i></a>
					</label>
					<input type="text" name="custom_field1" value="{{ post('custom_field1') }}" class="form-control" />
				</div>
				<div class="mb-3">
					<label class="form-label">{{ $customFieldLabel['biller_cf2'] ?? '' }}
						<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields" title="{{ $LANG['custom_fields'] ?? '' }}"><i class="ti ti-help"></i></a>
					</label>
					<input type="text" name="custom_field2" value="{{ post('custom_field2') }}" class="form-control" />
				</div>
				<div class="mb-3">
					<label class="form-label">{{ $customFieldLabel['biller_cf3'] ?? '' }}
						<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields" title="{{ $LANG['custom_fields'] ?? '' }}"><i class="ti ti-help"></i></a>
					</label>
					<input type="text" name="custom_field3" value="{{ post('custom_field3') }}" class="form-control" />
				</div>
				<div class="mb-3">
					<label class="form-label">{{ $customFieldLabel['biller_cf4'] ?? '' }}
						<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields" title="{{ $LANG['custom_fields'] ?? '' }}"><i class="ti ti-help"></i></a>
					</label>
					<input type="text" name="custom_field4" value="{{ post('custom_field4') }}" class="form-control" />
				</div>
				@showCustomFields(1, '')
			</div>
			<div id="bill-add-invoice" class="tab-pane" role="tabpanel">
				<div class="mb-3">
					<label class="form-label">{{ $LANG['logo_file'] ?? '' }}
						<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_insert_biller_text" title="{{ $LANG['logo_file'] ?? '' }}"><i class="ti ti-help"></i></a>
					</label>
					{html_options name=logo output=$files values=$files selected=$files[0] class="form-select"}
				</div>
				<div class="mb-3">
					<label class="form-label">{{ $LANG['invoice_footer'] ?? '' }}</label>
					<textarea class="form-control editor" name="footer" rows="4">{{ post('footer') }}</textarea>
				</div>
				<div class="mb-3">
					<label class="form-label">{{ $LANG['notes'] ?? '' }}</label>
					<textarea class="form-control editor" name="notes" rows="8">{{ post('notes') }}</textarea>
				</div>
			</div>
		</div>
	</div>
	<div class="card-footer">
		<div class="d-flex">
			<a href="./index.php?module=billers&amp;view=manage" class="btn btn-link">{{ $LANG['cancel'] ?? '' }}</a>
			<button type="submit" class="btn btn-primary ms-auto" name="submit" value="{{ $LANG['insert_biller'] ?? '' }}"><i class="ti ti-check me-1"></i>{{ $LANG['save'] ?? '' }}
			</button>
		</div>
	</div>
</div>

<input type="hidden" name="op" value="insert_biller" />
</form>
@endif
