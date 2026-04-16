{{-- * View: details (Blade)
* 	Biller details template
*
* Last edited:
* 	 2008-08-25
*
* License:
*	 GPL v3 or above --}}
<form name="frmpost" action="index.php?module=billers&amp;view=save&amp;id={{ get('id') }}" method="post" id="frmpost" class="needs-validation" novalidate>

@if(get('action')== 'view' )

<div class="card">
	<div class="card-header">
		<ul class="nav nav-tabs card-header-tabs" role="tablist">
			<li class="nav-item" role="presentation">
				<a class="nav-link active" href="#bill-view-details" data-bs-toggle="tab" role="tab"><i class="ti ti-info-circle me-1"></i>{{ $LANG['details'] ?? '' }}</a>
			</li>
			<li class="nav-item" role="presentation">
				<a class="nav-link" href="#bill-view-address" data-bs-toggle="tab" role="tab"><i class="ti ti-map-pin me-1"></i>{{ $LANG['address'] ?? '' }}</a>
			</li>
			<li class="nav-item" role="presentation">
				<a class="nav-link" href="#bill-view-contact" data-bs-toggle="tab" role="tab"><i class="ti ti-phone me-1"></i>{{ $LANG['contacts'] ?? '' }}</a>
			</li>
			<li class="nav-item" role="presentation">
				<a class="nav-link" href="#bill-view-payment" data-bs-toggle="tab" role="tab"><i class="ti ti-credit-card me-1"></i>{{ $LANG['payment'] ?? '' }}</a>
			</li>
			<li class="nav-item" role="presentation">
				<a class="nav-link" href="#bill-view-custom" data-bs-toggle="tab" role="tab"><i class="ti ti-adjustments me-1"></i>{{ $LANG['custom_fields'] ?? '' }}</a>
			</li>
			<li class="nav-item" role="presentation">
				<a class="nav-link" href="#bill-view-invoice" data-bs-toggle="tab" role="tab"><i class="ti ti-file-invoice me-1"></i>{{ $LANG['invoice'] ?? '' }}</a>
			</li>
		</ul>
	</div>
	<div class="card-body">
		<div class="tab-content">
			<div id="bill-view-details" class="tab-pane active" role="tabpanel">
				<table class="table table-vcenter table-wrap">
					<tr><th>{{ $LANG['biller_name'] ?? '' }}</th><td>{{ $biller['name'] }}</td></tr>
					<tr><th>{{ $LANG['enabled'] ?? '' }}</th><td>{{ $biller['wording_for_enabled'] }}</td></tr>
				</table>
			</div>
			<div id="bill-view-address" class="tab-pane" role="tabpanel">
				<table class="table table-vcenter table-wrap">
					<tr><th>{{ $LANG['street'] ?? '' }}</th><td>{{ $biller['street_address'] }}</td></tr>
					<tr><th>{{ $LANG['street2'] ?? '' }}</th><td>{{ $biller['street_address2'] }}</td></tr>
					<tr><th>{{ $LANG['city'] ?? '' }}</th><td>{{ $biller['city'] }}</td></tr>
					<tr><th>{{ $LANG['state'] ?? '' }}</th><td>{{ $biller['state'] }}</td></tr>
					<tr><th>{{ $LANG['zip'] ?? '' }}</th><td>{{ $biller['zip_code'] }}</td></tr>
					<tr><th>{{ $LANG['country'] ?? '' }}</th><td>{{ $biller['country'] }}</td></tr>
				</table>
			</div>
			<div id="bill-view-contact" class="tab-pane" role="tabpanel">
				<table class="table table-vcenter table-wrap">
					<tr><th>{{ $LANG['phone'] ?? '' }}</th><td>{{ $biller['phone'] }}</td></tr>
					<tr><th>{{ $LANG['mobile_phone'] ?? '' }}</th><td>{{ $biller['mobile_phone'] }}</td></tr>
					<tr><th>{{ $LANG['fax'] ?? '' }}</th><td>{{ $biller['fax'] }}</td></tr>
					<tr><th>{{ $LANG['email'] ?? '' }}</th><td>{{ $biller['email'] }}</td></tr>
				</table>
			</div>
			<div id="bill-view-payment" class="tab-pane" role="tabpanel">
				<table class="table table-vcenter table-wrap">
					<tr><th>{{ $LANG['paypal_business_name'] ?? '' }}</th><td>{{ $biller['paypal_business_name'] }}</td></tr>
					<tr><th>{{ $LANG['paypal_notify_url'] ?? '' }}</th><td>{{ $biller['paypal_notify_url'] }}</td></tr>
					<tr><th>{{ $LANG['paypal_return_url'] ?? '' }}</th><td>{{ $biller['paypal_return_url'] }}</td></tr>
					<tr><th>{{ $LANG['eway_customer_id'] ?? '' }}</th><td>{{ $biller['eway_customer_id'] }}</td></tr>
					<tr><th>{{ $LANG['paymentsgateway_api_id'] ?? '' }}</th><td>{{ $biller['paymentsgateway_api_id'] }}</td></tr>
				</table>
			</div>
			<div id="bill-view-custom" class="tab-pane" role="tabpanel">
				<table class="table table-vcenter table-wrap">
					<tr><th>{{ $customFieldLabel['biller_cf1'] ?? '' }}</th><td>{{ $biller['custom_field1'] }}</td></tr>
					<tr><th>{{ $customFieldLabel['biller_cf2'] ?? '' }}</th><td>{{ $biller['custom_field2'] }}</td></tr>
					<tr><th>{{ $customFieldLabel['biller_cf3'] ?? '' }}</th><td>{{ $biller['custom_field3'] }}</td></tr>
					<tr><th>{{ $customFieldLabel['biller_cf4'] ?? '' }}</th><td>{{ $biller['custom_field4'] }}</td></tr>
				</table>
				@showCustomFields(1, get('id'))
			</div>
			<div id="bill-view-invoice" class="tab-pane" role="tabpanel">
				<table class="table table-vcenter table-wrap">
					<tr>
						<th>{{ $LANG['logo_file'] ?? '' }}</th>
						<td>
							@if(!empty($biller['logo']))
								<img src="templates/invoices/logos/{{ $biller['logo'] }}" alt="{{ $biller['logo'] }}" class="img-fluid"><br>{{ $biller['logo'] }}
							@endif
						</td>
					</tr>
					<tr><th>{{ $LANG['invoice_footer'] ?? '' }}</th><td>{{ $biller['footer'] }}</td></tr>
					<tr><th>{{ $LANG['notes'] ?? '' }}</th><td>{{ $biller['notes'] }}</td></tr>
				</table>
			</div>
		</div>
	</div>
	<div class="card-footer">
		<div class="d-flex">
			<a href="./index.php?module=billers&amp;view=manage" class="btn btn-link">{{ $LANG['cancel'] ?? '' }}</a>
			<a href="./index.php?module=billers&amp;view=details&amp;action=edit&amp;id={{ $biller['id'] ?? '' }}" class="btn btn-primary ms-auto"><i class="ti ti-edit me-1"></i>{{ $LANG['edit'] ?? '' }}</a>
		</div>
	</div>
</div>

@endif


{{-- ######################################################################################### --}}


@if(get('action')== 'edit' )
<div class="card">
	<div class="card-header">
		<ul class="nav nav-tabs card-header-tabs" role="tablist">
			<li class="nav-item" role="presentation">
				<a class="nav-link active" href="#bill-edit-details" data-bs-toggle="tab" role="tab"><i class="ti ti-info-circle me-1"></i>{{ $LANG['details'] ?? '' }}</a>
			</li>
			<li class="nav-item" role="presentation">
				<a class="nav-link" href="#bill-edit-address" data-bs-toggle="tab" role="tab"><i class="ti ti-map-pin me-1"></i>{{ $LANG['address'] ?? '' }}</a>
			</li>
			<li class="nav-item" role="presentation">
				<a class="nav-link" href="#bill-edit-contact" data-bs-toggle="tab" role="tab"><i class="ti ti-phone me-1"></i>{{ $LANG['contacts'] ?? '' }}</a>
			</li>
			<li class="nav-item" role="presentation">
				<a class="nav-link" href="#bill-edit-payment" data-bs-toggle="tab" role="tab"><i class="ti ti-credit-card me-1"></i>{{ $LANG['payment'] ?? '' }}</a>
			</li>
			<li class="nav-item" role="presentation">
				<a class="nav-link" href="#bill-edit-custom" data-bs-toggle="tab" role="tab"><i class="ti ti-adjustments me-1"></i>{{ $LANG['custom_fields'] ?? '' }}</a>
			</li>
			<li class="nav-item" role="presentation">
				<a class="nav-link" href="#bill-edit-invoice" data-bs-toggle="tab" role="tab"><i class="ti ti-file-invoice me-1"></i>{{ $LANG['invoice'] ?? '' }}</a>
			</li>
		</ul>
	</div>
	<div class="card-body">
		<div class="tab-content">
			<div id="bill-edit-details" class="tab-pane active" role="tabpanel">
				<div class="mb-3">
					<label class="form-label">{{ $LANG['biller_name'] ?? '' }}
						<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_required_field" title="{{ $LANG['required_field'] ?? '' }}"><i class="ti ti-asterisk text-danger"></i></a>
					</label>
					<input type="text" name="name" value="{{ $biller['name'] ?? '' }}" id="name" class="form-control" required />
					<div class="invalid-feedback">{{ $LANG['required_field'] ?? 'Required' }}</div>
				</div>
				<div class="mb-3">
					<label class="form-label">{{ $LANG['email'] ?? '' }}</label>
					<input type="text" name="email" value="{{ $biller['email'] ?? '' }}" class="form-control" />
				</div>
				<div class="mb-3">
					<label class="form-label">{{ $LANG['enabled'] ?? '' }}</label>
					{html_options name=enabled options=$enabled selected=$biller['enabled'] class="form-select"}
				</div>
			</div>
			<div id="bill-edit-address" class="tab-pane" role="tabpanel">
				<div class="mb-3">
					<label class="form-label">{{ $LANG['street'] ?? '' }}</label>
					<input type="text" name="street_address" value="{{ $biller['street_address'] ?? '' }}" class="form-control" />
				</div>
				<div class="mb-3">
					<label class="form-label">{{ $LANG['street2'] ?? '' }}
						<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_street2" title="{{ $LANG['street2'] ?? '' }}"><i class="ti ti-help"></i></a>
					</label>
					<input type="text" name="street_address2" value="{{ $biller['street_address2'] ?? '' }}" class="form-control" />
				</div>
				<div class="mb-3">
					<label class="form-label">{{ $LANG['city'] ?? '' }}</label>
					<input type="text" name="city" value="{{ $biller['city'] ?? '' }}" class="form-control" />
				</div>
				<div class="mb-3">
					<label class="form-label">{{ $LANG['state'] ?? '' }}</label>
					<input type="text" name="state" value="{{ $biller['state'] ?? '' }}" class="form-control" />
				</div>
				<div class="mb-3">
					<label class="form-label">{{ $LANG['zip'] ?? '' }}</label>
					<input type="text" name="zip_code" value="{{ $biller['zip_code'] ?? '' }}" class="form-control" />
				</div>
				<div class="mb-3">
					<label class="form-label">{{ $LANG['country'] ?? '' }}</label>
					<input type="text" name="country" value="{{ $biller['country'] ?? '' }}" class="form-control" />
				</div>
			</div>
			<div id="bill-edit-contact" class="tab-pane" role="tabpanel">
				<div class="mb-3">
					<label class="form-label">{{ $LANG['phone'] ?? '' }}</label>
					<input type="text" name="phone" value="{{ $biller['phone'] ?? '' }}" class="form-control" />
				</div>
				<div class="mb-3">
					<label class="form-label">{{ $LANG['mobile_phone'] ?? '' }}</label>
					<input type="text" name="mobile_phone" value="{{ $biller['mobile_phone'] ?? '' }}" class="form-control" />
				</div>
				<div class="mb-3">
					<label class="form-label">{{ $LANG['fax'] ?? '' }}</label>
					<input type="text" name="fax" value="{{ $biller['fax'] ?? '' }}" class="form-control" />
				</div>
			</div>
			<div id="bill-edit-payment" class="tab-pane" role="tabpanel">
				<div class="mb-3">
					<label class="form-label">{{ $LANG['paypal_business_name'] ?? '' }}</label>
					<input type="text" name="paypal_business_name" value="{{ $biller['paypal_business_name'] ?? '' }}" class="form-control" />
				</div>
				<div class="mb-3">
					<label class="form-label">{{ $LANG['paypal_notify_url'] ?? '' }}</label>
					<input type="text" name="paypal_notify_url" value="{{ $biller['paypal_notify_url'] ?? '' }}" class="form-control" />
				</div>
				<div class="mb-3">
					<label class="form-label">{{ $LANG['paypal_return_url'] ?? '' }}</label>
					<input type="text" name="paypal_return_url" value="{{ $biller['paypal_return_url'] ?? '' }}" class="form-control" />
				</div>
				<div class="mb-3">
					<label class="form-label">{{ $LANG['eway_customer_id'] ?? '' }}</label>
					<input type="text" name="eway_customer_id" value="{{ $biller['eway_customer_id'] ?? '' }}" class="form-control" />
				</div>
				<div class="mb-3">
					<label class="form-label">{{ $LANG['paymentsgateway_api_id'] ?? '' }}</label>
					<input type="text" name="paymentsgateway_api_id" value="{{ $biller['paymentsgateway_api_id'] ?? '' }}" class="form-control" />
				</div>
			</div>
			<div id="bill-edit-custom" class="tab-pane" role="tabpanel">
				<div class="mb-3">
					<label class="form-label">{{ $customFieldLabel['biller_cf1'] ?? '' }}
						<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields" title="{{ $LANG['custom_fields'] ?? '' }}"><i class="ti ti-help"></i></a>
					</label>
					<input type="text" name="custom_field1" value="{{ $biller['custom_field1'] ?? '' }}" class="form-control" />
				</div>
				<div class="mb-3">
					<label class="form-label">{{ $customFieldLabel['biller_cf2'] ?? '' }}
						<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields" title="{{ $LANG['custom_fields'] ?? '' }}"><i class="ti ti-help"></i></a>
					</label>
					<input type="text" name="custom_field2" value="{{ $biller['custom_field2'] ?? '' }}" class="form-control" />
				</div>
				<div class="mb-3">
					<label class="form-label">{{ $customFieldLabel['biller_cf3'] ?? '' }}
						<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields" title="{{ $LANG['custom_fields'] ?? '' }}"><i class="ti ti-help"></i></a>
					</label>
					<input type="text" name="custom_field3" value="{{ $biller['custom_field3'] ?? '' }}" class="form-control" />
				</div>
				<div class="mb-3">
					<label class="form-label">{{ $customFieldLabel['biller_cf4'] ?? '' }}
						<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields" title="{{ $LANG['custom_fields'] ?? '' }}"><i class="ti ti-help"></i></a>
					</label>
					<input type="text" name="custom_field4" value="{{ $biller['custom_field4'] ?? '' }}" class="form-control" />
				</div>
				@showCustomFields(1, get('id'))
			</div>
			<div id="bill-edit-invoice" class="tab-pane" role="tabpanel">
				<div class="mb-3">
					<label class="form-label">{{ $LANG['logo_file'] ?? '' }}
						<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_insert_biller_text" title="{{ $LANG['logo_file'] ?? '' }}"><i class="ti ti-help"></i></a>
					</label>
					{html_options name=logo output=$files values=$files selected=$biller['logo'] class="form-select"}
				</div>
				<div class="mb-3">
					<label class="form-label">{{ $LANG['invoice_footer'] ?? '' }}</label>
					<textarea name="footer" class="form-control editor" rows="4">{{ $biller['footer'] ?? '' }}</textarea>
				</div>
				<div class="mb-3">
					<label class="form-label">{{ $LANG['notes'] ?? '' }}</label>
					<textarea name="notes" class="form-control editor" rows="8">{{ $biller['notes'] ?? '' }}</textarea>
				</div>
			</div>
		</div>
	</div>
	<div class="card-footer">
		<div class="d-flex">
			<a href="./index.php?module=billers&amp;view=manage" class="btn btn-link">{{ $LANG['cancel'] ?? '' }}</a>
			<button type="submit" class="btn btn-primary ms-auto" name="save_biller" value="{{ $LANG['save_biller'] ?? '' }}"><i class="ti ti-check me-1"></i>{{ $LANG['save'] ?? '' }}</button>
		</div>
	</div>
</div>

<input type="hidden" name="op" value="edit_biller">
<input type="hidden" name="categorie" value="1" />
@endif

</form>
