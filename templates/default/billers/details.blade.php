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
				<div class="accordion" id="viewPaymentAccordion">

					{{-- Stripe --}}
					<div class="accordion-item">
						<h2 class="accordion-header" id="vph-stripe">
							<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#vpc-stripe" aria-expanded="false" aria-controls="vpc-stripe">
								<i class="ti ti-brand-stripe me-2"></i>Stripe
								@if(!empty($biller['stripe_secret_key']))<span class="badge bg-success ms-2">{{ $LANG['configured'] ?? 'Configured' }}</span>@endif
							</button>
						</h2>
						<div id="vpc-stripe" class="accordion-collapse collapse" aria-labelledby="vph-stripe">
							<div class="accordion-body p-0">
								<table class="table table-vcenter table-wrap mb-0">
									<tr><th>{{ $LANG['stripe_secret_key'] ?? 'Stripe Secret Key' }}</th><td>{{ !empty($biller['stripe_secret_key']) ? '••••••••' . substr($biller['stripe_secret_key'], -4) : '' }}</td></tr>
									<tr><th>{{ $LANG['stripe_webhook_secret'] ?? 'Stripe Webhook Secret' }}</th><td>{{ !empty($biller['stripe_webhook_secret']) ? '••••' : '' }}</td></tr>
									<tr><th>{{ $LANG['stripe_test_mode'] ?? 'Stripe Test Mode' }}</th><td>{{ ($biller['stripe_test_mode'] ?? 1) ? ($LANG['yes'] ?? 'Yes') : ($LANG['no'] ?? 'No') }}</td></tr>
								</table>
							</div>
						</div>
					</div>

					{{-- PayPal --}}
					<div class="accordion-item">
						<h2 class="accordion-header" id="vph-paypal">
							<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#vpc-paypal" aria-expanded="false" aria-controls="vpc-paypal">
								<i class="ti ti-brand-paypal me-2"></i>PayPal Commerce
								@if(!empty($biller['paypal_client_id']))<span class="badge bg-success ms-2">{{ $LANG['configured'] ?? 'Configured' }}</span>@endif
							</button>
						</h2>
						<div id="vpc-paypal" class="accordion-collapse collapse" aria-labelledby="vph-paypal">
							<div class="accordion-body p-0">
								<table class="table table-vcenter table-wrap mb-0">
									<tr><th>{{ $LANG['paypal_client_id'] ?? 'PayPal Client ID' }}</th><td>{{ $biller['paypal_client_id'] ?? '' }}</td></tr>
									<tr><th>{{ $LANG['paypal_client_secret'] ?? 'PayPal Client Secret' }}</th><td>{{ !empty($biller['paypal_client_secret']) ? '••••' : '' }}</td></tr>
									<tr><th>{{ $LANG['paypal_test_mode'] ?? 'PayPal Sandbox Mode' }}</th><td>{{ ($biller['paypal_test_mode'] ?? 1) ? ($LANG['yes'] ?? 'Yes') : ($LANG['no'] ?? 'No') }}</td></tr>
								</table>
							</div>
						</div>
					</div>

					{{-- Mollie --}}
					<div class="accordion-item">
						<h2 class="accordion-header" id="vph-mollie">
							<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#vpc-mollie" aria-expanded="false" aria-controls="vpc-mollie">
								<i class="ti ti-credit-card me-2"></i>Mollie
								@if(!empty($biller['mollie_api_key']))<span class="badge bg-success ms-2">{{ $LANG['configured'] ?? 'Configured' }}</span>@endif
							</button>
						</h2>
						<div id="vpc-mollie" class="accordion-collapse collapse" aria-labelledby="vph-mollie">
							<div class="accordion-body p-0">
								<table class="table table-vcenter table-wrap mb-0">
									<tr><th>{{ $LANG['mollie_api_key'] ?? 'Mollie API Key' }}</th><td>{{ !empty($biller['mollie_api_key']) ? '••••' . substr($biller['mollie_api_key'], -4) : '' }}</td></tr>
								</table>
							</div>
						</div>
					</div>

					{{-- Authorize.net --}}
					<div class="accordion-item">
						<h2 class="accordion-header" id="vph-authnet">
							<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#vpc-authnet" aria-expanded="false" aria-controls="vpc-authnet">
								<i class="ti ti-credit-card me-2"></i>Authorize.net
								@if(!empty($biller['authorizenet_login_id']))<span class="badge bg-success ms-2">{{ $LANG['configured'] ?? 'Configured' }}</span>@endif
							</button>
						</h2>
						<div id="vpc-authnet" class="accordion-collapse collapse" aria-labelledby="vph-authnet">
							<div class="accordion-body p-0">
								<table class="table table-vcenter table-wrap mb-0">
									<tr><th>{{ $LANG['authorizenet_login_id'] ?? 'API Login ID' }}</th><td>{{ $biller['authorizenet_login_id'] ?? '' }}</td></tr>
									<tr><th>{{ $LANG['authorizenet_transaction_key'] ?? 'Transaction Key' }}</th><td>{{ !empty($biller['authorizenet_transaction_key']) ? '••••' : '' }}</td></tr>
									<tr><th>{{ $LANG['authorizenet_signature_key'] ?? 'Signature Key' }}</th><td>{{ !empty($biller['authorizenet_signature_key']) ? '••••' : '' }}</td></tr>
									<tr><th>{{ $LANG['authorizenet_test_mode'] ?? 'Sandbox Mode' }}</th><td>{{ ($biller['authorizenet_test_mode'] ?? 1) ? ($LANG['yes'] ?? 'Yes') : ($LANG['no'] ?? 'No') }}</td></tr>
								</table>
							</div>
						</div>
					</div>

					{{-- eWay --}}
					<div class="accordion-item">
						<h2 class="accordion-header" id="vph-eway">
							<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#vpc-eway" aria-expanded="false" aria-controls="vpc-eway">
								<i class="ti ti-credit-card me-2"></i>{{ $LANG['eway_rapid'] ?? 'eWay Rapid' }}
								@if(!empty($biller['eway_api_key']))<span class="badge bg-success ms-2">{{ $LANG['configured'] ?? 'Configured' }}</span>@endif
							</button>
						</h2>
						<div id="vpc-eway" class="accordion-collapse collapse" aria-labelledby="vph-eway">
							<div class="accordion-body p-0">
								<table class="table table-vcenter table-wrap mb-0">
									<tr><th>{{ $LANG['eway_api_key'] ?? 'eWay API Key' }}</th><td>{{ !empty($biller['eway_api_key']) ? '••••' . substr($biller['eway_api_key'], -4) : '' }}</td></tr>
									<tr><th>{{ $LANG['eway_api_password'] ?? 'eWay API Password' }}</th><td>{{ !empty($biller['eway_api_password']) ? '••••' : '' }}</td></tr>
									<tr><th>{{ $LANG['eway_test_mode'] ?? 'eWay Sandbox Mode' }}</th><td>{{ ($biller['eway_test_mode'] ?? 1) ? ($LANG['yes'] ?? 'Yes') : ($LANG['no'] ?? 'No') }}</td></tr>
								</table>
							</div>
						</div>
					</div>

					{{-- Ko-fi --}}
					<div class="accordion-item">
						<h2 class="accordion-header" id="vph-kofi">
							<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#vpc-kofi" aria-expanded="false" aria-controls="vpc-kofi">
								<i class="ti ti-coffee me-2"></i>Ko-fi
								@if(!empty($biller['kofi_username']))<span class="badge bg-success ms-2">{{ $LANG['configured'] ?? 'Configured' }}</span>@endif
							</button>
						</h2>
						<div id="vpc-kofi" class="accordion-collapse collapse" aria-labelledby="vph-kofi">
							<div class="accordion-body p-0">
								<table class="table table-vcenter table-wrap mb-0">
									<tr><th>{{ $LANG['kofi_username'] ?? 'Ko-fi Username' }}</th><td>{{ $biller['kofi_username'] ?? '' }}</td></tr>
								</table>
							</div>
						</div>
					</div>

					{{-- Coinbase --}}
					<div class="accordion-item">
						<h2 class="accordion-header" id="vph-coinbase">
							<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#vpc-coinbase" aria-expanded="false" aria-controls="vpc-coinbase">
								<i class="ti ti-currency-bitcoin me-2"></i>{{ $LANG['coinbase_commerce'] ?? 'Coinbase Commerce' }}
								@if(!empty($biller['coinbase_api_key']))<span class="badge bg-success ms-2">{{ $LANG['configured'] ?? 'Configured' }}</span>@endif
							</button>
						</h2>
						<div id="vpc-coinbase" class="accordion-collapse collapse" aria-labelledby="vph-coinbase">
							<div class="accordion-body p-0">
								<table class="table table-vcenter table-wrap mb-0">
									<tr><th>{{ $LANG['coinbase_api_key'] ?? 'Coinbase API Key' }}</th><td>{{ !empty($biller['coinbase_api_key']) ? '••••' . substr($biller['coinbase_api_key'], -4) : '' }}</td></tr>
									<tr><th>{{ $LANG['coinbase_webhook_secret'] ?? 'Webhook Secret' }}</th><td>{{ !empty($biller['coinbase_webhook_secret']) ? '••••' : '' }}</td></tr>
								</table>
							</div>
						</div>
					</div>

					{{-- Adyen --}}
					<div class="accordion-item">
						<h2 class="accordion-header" id="vph-adyen">
							<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#vpc-adyen" aria-expanded="false" aria-controls="vpc-adyen">
								<i class="ti ti-credit-card me-2"></i>Adyen
								@if(!empty($biller['adyen_api_key']))<span class="badge bg-success ms-2">{{ $LANG['configured'] ?? 'Configured' }}</span>@endif
							</button>
						</h2>
						<div id="vpc-adyen" class="accordion-collapse collapse" aria-labelledby="vph-adyen">
							<div class="accordion-body p-0">
								<table class="table table-vcenter table-wrap mb-0">
									<tr><th>{{ $LANG['adyen_api_key'] ?? 'Adyen API Key' }}</th><td>{{ !empty($biller['adyen_api_key']) ? '••••' . substr($biller['adyen_api_key'], -4) : '' }}</td></tr>
									<tr><th>{{ $LANG['adyen_merchant_account'] ?? 'Merchant Account' }}</th><td>{{ $biller['adyen_merchant_account'] ?? '' }}</td></tr>
									<tr><th>{{ $LANG['adyen_hmac_key'] ?? 'HMAC Key' }}</th><td>{{ !empty($biller['adyen_hmac_key']) ? '••••' : '' }}</td></tr>
									<tr><th>{{ $LANG['adyen_live_prefix'] ?? 'Live Endpoint Prefix' }}</th><td>{{ $biller['adyen_live_prefix'] ?? '' }}</td></tr>
									<tr><th>{{ $LANG['adyen_test_mode'] ?? 'Adyen Test Mode' }}</th><td>{{ ($biller['adyen_test_mode'] ?? 1) ? ($LANG['yes'] ?? 'Yes') : ($LANG['no'] ?? 'No') }}</td></tr>
								</table>
							</div>
						</div>
					</div>

					{{-- Payments Gateway --}}
					<div class="accordion-item">
						<h2 class="accordion-header" id="vph-pgw">
							<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#vpc-pgw" aria-expanded="false" aria-controls="vpc-pgw">
								<i class="ti ti-credit-card me-2"></i>{{ $LANG['paymentsgateway_modern'] ?? 'Payments Gateway' }}
								@if(!empty($biller['paymentsgateway_api_id']))<span class="badge bg-success ms-2">{{ $LANG['configured'] ?? 'Configured' }}</span>@endif
							</button>
						</h2>
						<div id="vpc-pgw" class="accordion-collapse collapse" aria-labelledby="vph-pgw">
							<div class="accordion-body p-0">
								<table class="table table-vcenter table-wrap mb-0">
									<tr><th>{{ $LANG['paymentsgateway_api_id'] ?? 'API Login ID' }}</th><td>{{ $biller['paymentsgateway_api_id'] ?? '' }}</td></tr>
								</table>
							</div>
						</div>
					</div>

				</div>
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
				<div class="accordion" id="editPaymentAccordion">

					{{-- Stripe --}}
					<div class="accordion-item">
						<h2 class="accordion-header" id="eph-stripe">
							<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#epc-stripe" aria-expanded="false" aria-controls="epc-stripe">
								<i class="ti ti-brand-stripe me-2"></i>Stripe
								@if(!empty($biller['stripe_secret_key']))<span class="badge bg-success ms-2">{{ $LANG['configured'] ?? 'Configured' }}</span>@endif
							</button>
						</h2>
						<div id="epc-stripe" class="accordion-collapse collapse" aria-labelledby="eph-stripe">
							<div class="accordion-body">
								<div class="mb-3">
									<label class="form-label">{{ $LANG['stripe_secret_key'] ?? 'Stripe Secret Key' }}</label>
									<input type="password" name="stripe_secret_key" value="{{ $biller['stripe_secret_key'] ?? '' }}" class="form-control" autocomplete="new-password" />
									<small class="form-hint">{{ $LANG['stripe_secret_key_hint'] ?? 'From Stripe Dashboard → Developers → API keys (sk_live_… or sk_test_…)' }}</small>
								</div>
								<div class="mb-3">
									<label class="form-label">{{ $LANG['stripe_webhook_secret'] ?? 'Stripe Webhook Secret' }}</label>
									<input type="password" name="stripe_webhook_secret" value="{{ $biller['stripe_webhook_secret'] ?? '' }}" class="form-control" autocomplete="new-password" />
									<small class="form-hint">{{ $LANG['stripe_webhook_secret_hint'] ?? 'From Stripe Dashboard → Developers → Webhooks → your endpoint (whsec_…). Webhook URL: ' }}{{ $siUrl ?? '' }}/index.php?module=api&amp;view=stripe_webhook&amp;biller_id={{ $biller['id'] ?? '' }}&amp;domain_id={{ $biller['domain_id'] ?? '' }}</small>
								</div>
								<div class="mb-0">
									<label class="form-label">{{ $LANG['stripe_test_mode'] ?? 'Stripe Test Mode' }}</label>
									<select name="stripe_test_mode" class="form-select">
										<option value="1" @if(($biller['stripe_test_mode'] ?? 1) == 1) selected @endif>{{ $LANG['yes'] ?? 'Yes' }} (test)</option>
										<option value="0" @if(($biller['stripe_test_mode'] ?? 1) == 0) selected @endif>{{ $LANG['no'] ?? 'No' }} (live)</option>
									</select>
								</div>
							</div>
						</div>
					</div>

					{{-- PayPal --}}
					<div class="accordion-item">
						<h2 class="accordion-header" id="eph-paypal">
							<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#epc-paypal" aria-expanded="false" aria-controls="epc-paypal">
								<i class="ti ti-brand-paypal me-2"></i>PayPal Commerce
								@if(!empty($biller['paypal_client_id']))<span class="badge bg-success ms-2">{{ $LANG['configured'] ?? 'Configured' }}</span>@endif
							</button>
						</h2>
						<div id="epc-paypal" class="accordion-collapse collapse" aria-labelledby="eph-paypal">
							<div class="accordion-body">
								<div class="mb-3">
									<label class="form-label">{{ $LANG['paypal_client_id'] ?? 'PayPal Client ID' }}</label>
									<input type="text" name="paypal_client_id" value="{{ $biller['paypal_client_id'] ?? '' }}" class="form-control" />
									<small class="form-hint">{{ $LANG['paypal_client_id_hint'] ?? 'From PayPal Developer Dashboard → My Apps → your app → Client ID' }}</small>
								</div>
								<div class="mb-3">
									<label class="form-label">{{ $LANG['paypal_client_secret'] ?? 'PayPal Client Secret' }}</label>
									<input type="password" name="paypal_client_secret" value="{{ $biller['paypal_client_secret'] ?? '' }}" class="form-control" autocomplete="new-password" />
								</div>
								<div class="mb-0">
									<label class="form-label">{{ $LANG['paypal_test_mode'] ?? 'PayPal Sandbox Mode' }}</label>
									<select name="paypal_test_mode" class="form-select">
										<option value="1" @if(($biller['paypal_test_mode'] ?? 1) == 1) selected @endif>{{ $LANG['yes'] ?? 'Yes' }} (sandbox)</option>
										<option value="0" @if(($biller['paypal_test_mode'] ?? 1) == 0) selected @endif>{{ $LANG['no'] ?? 'No' }} (live)</option>
									</select>
								</div>
							</div>
						</div>
					</div>

					{{-- Mollie --}}
					<div class="accordion-item">
						<h2 class="accordion-header" id="eph-mollie">
							<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#epc-mollie" aria-expanded="false" aria-controls="epc-mollie">
								<i class="ti ti-credit-card me-2"></i>Mollie
								@if(!empty($biller['mollie_api_key']))<span class="badge bg-success ms-2">{{ $LANG['configured'] ?? 'Configured' }}</span>@endif
							</button>
						</h2>
						<div id="epc-mollie" class="accordion-collapse collapse" aria-labelledby="eph-mollie">
							<div class="accordion-body">
								<div class="mb-0">
									<label class="form-label">{{ $LANG['mollie_api_key'] ?? 'Mollie API Key' }}</label>
									<input type="password" name="mollie_api_key" value="{{ $biller['mollie_api_key'] ?? '' }}" class="form-control" autocomplete="new-password" />
									<small class="form-hint">{{ $LANG['mollie_api_key_hint'] ?? 'From Mollie Dashboard → Developers → API keys (test_… or live_…). Webhook URL: ' }}{{ $siUrl ?? '' }}/index.php?module=api&amp;view=mollie_webhook</small>
								</div>
							</div>
						</div>
					</div>

					{{-- Authorize.net --}}
					<div class="accordion-item">
						<h2 class="accordion-header" id="eph-authnet">
							<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#epc-authnet" aria-expanded="false" aria-controls="epc-authnet">
								<i class="ti ti-credit-card me-2"></i>Authorize.net
								@if(!empty($biller['authorizenet_login_id']))<span class="badge bg-success ms-2">{{ $LANG['configured'] ?? 'Configured' }}</span>@endif
							</button>
						</h2>
						<div id="epc-authnet" class="accordion-collapse collapse" aria-labelledby="eph-authnet">
							<div class="accordion-body">
								<div class="mb-3">
									<label class="form-label">{{ $LANG['authorizenet_login_id'] ?? 'API Login ID' }}</label>
									<input type="text" name="authorizenet_login_id" value="{{ $biller['authorizenet_login_id'] ?? '' }}" class="form-control" />
								</div>
								<div class="mb-3">
									<label class="form-label">{{ $LANG['authorizenet_transaction_key'] ?? 'Transaction Key' }}</label>
									<input type="password" name="authorizenet_transaction_key" value="{{ $biller['authorizenet_transaction_key'] ?? '' }}" class="form-control" autocomplete="new-password" />
								</div>
								<div class="mb-3">
									<label class="form-label">{{ $LANG['authorizenet_signature_key'] ?? 'Signature Key' }}
										<small class="text-muted">({{ $LANG['optional'] ?? 'optional' }})</small>
									</label>
									<input type="password" name="authorizenet_signature_key" value="{{ $biller['authorizenet_signature_key'] ?? '' }}" class="form-control" autocomplete="new-password" />
									<small class="form-hint">{{ $LANG['authorizenet_signature_key_hint'] ?? 'Used to verify webhook notifications. From Authorize.net Merchant Interface → Account → API Credentials & Keys.' }}
									{{ $LANG['authorizenet_webhook_url_hint'] ?? 'Webhook URL: ' }}{{ $siUrl ?? '' }}/index.php?module=api&amp;view=authorizenet_webhook&amp;biller_id={{ $biller['id'] ?? '' }}&amp;domain_id={{ $biller['domain_id'] ?? '' }}</small>
								</div>
								<div class="mb-0">
									<label class="form-label">{{ $LANG['authorizenet_test_mode'] ?? 'Authorize.net Sandbox Mode' }}</label>
									<select name="authorizenet_test_mode" class="form-select">
										<option value="1" @if(($biller['authorizenet_test_mode'] ?? 1) == 1) selected @endif>{{ $LANG['yes'] ?? 'Yes' }} (sandbox)</option>
										<option value="0" @if(($biller['authorizenet_test_mode'] ?? 1) == 0) selected @endif>{{ $LANG['no'] ?? 'No' }} (live)</option>
									</select>
								</div>
							</div>
						</div>
					</div>

					{{-- eWay --}}
					<div class="accordion-item">
						<h2 class="accordion-header" id="eph-eway">
							<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#epc-eway" aria-expanded="false" aria-controls="epc-eway">
								<i class="ti ti-credit-card me-2"></i>{{ $LANG['eway_rapid'] ?? 'eWay Rapid' }}
								@if(!empty($biller['eway_api_key']))<span class="badge bg-success ms-2">{{ $LANG['configured'] ?? 'Configured' }}</span>@endif
							</button>
						</h2>
						<div id="epc-eway" class="accordion-collapse collapse" aria-labelledby="eph-eway">
							<div class="accordion-body">
								<div class="mb-3">
									<label class="form-label">{{ $LANG['eway_api_key'] ?? 'eWay API Key' }}</label>
									<input type="password" name="eway_api_key" value="{{ $biller['eway_api_key'] ?? '' }}" class="form-control" autocomplete="new-password" />
									<small class="form-hint">{{ $LANG['eway_api_key_hint'] ?? 'From eWay My.eWay → API Keys (Rapid API Key)' }}</small>
								</div>
								<div class="mb-3">
									<label class="form-label">{{ $LANG['eway_api_password'] ?? 'eWay API Password' }}</label>
									<input type="password" name="eway_api_password" value="{{ $biller['eway_api_password'] ?? '' }}" class="form-control" autocomplete="new-password" />
								</div>
								<div class="mb-0">
									<label class="form-label">{{ $LANG['eway_test_mode'] ?? 'eWay Sandbox Mode' }}</label>
									<select name="eway_test_mode" class="form-select">
										<option value="1" @if(($biller['eway_test_mode'] ?? 1) == 1) selected @endif>{{ $LANG['yes'] ?? 'Yes' }} (sandbox)</option>
										<option value="0" @if(($biller['eway_test_mode'] ?? 1) == 0) selected @endif>{{ $LANG['no'] ?? 'No' }} (live)</option>
									</select>
								</div>
							</div>
						</div>
					</div>

					{{-- Ko-fi --}}
					<div class="accordion-item">
						<h2 class="accordion-header" id="eph-kofi">
							<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#epc-kofi" aria-expanded="false" aria-controls="epc-kofi">
								<i class="ti ti-coffee me-2"></i>Ko-fi
								@if(!empty($biller['kofi_username']))<span class="badge bg-success ms-2">{{ $LANG['configured'] ?? 'Configured' }}</span>@endif
							</button>
						</h2>
						<div id="epc-kofi" class="accordion-collapse collapse" aria-labelledby="eph-kofi">
							<div class="accordion-body">
								<div class="mb-0">
									<label class="form-label">{{ $LANG['kofi_username'] ?? 'Ko-fi Username' }}</label>
									<input type="text" name="kofi_username" value="{{ $biller['kofi_username'] ?? '' }}" class="form-control" />
									<small class="form-hint">{{ $LANG['kofi_username_hint'] ?? 'Your Ko-fi page username (e.g. yourname from ko-fi.com/yourname). Customers will be sent to your Ko-fi tip page.' }}</small>
								</div>
							</div>
						</div>
					</div>

					{{-- Coinbase --}}
					<div class="accordion-item">
						<h2 class="accordion-header" id="eph-coinbase">
							<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#epc-coinbase" aria-expanded="false" aria-controls="epc-coinbase">
								<i class="ti ti-currency-bitcoin me-2"></i>{{ $LANG['coinbase_commerce'] ?? 'Coinbase Commerce' }}
								@if(!empty($biller['coinbase_api_key']))<span class="badge bg-success ms-2">{{ $LANG['configured'] ?? 'Configured' }}</span>@endif
							</button>
						</h2>
						<div id="epc-coinbase" class="accordion-collapse collapse" aria-labelledby="eph-coinbase">
							<div class="accordion-body">
								<div class="mb-3">
									<label class="form-label">{{ $LANG['coinbase_api_key'] ?? 'Coinbase Commerce API Key' }}</label>
									<input type="password" name="coinbase_api_key" value="{{ $biller['coinbase_api_key'] ?? '' }}" class="form-control" autocomplete="new-password" />
									<small class="form-hint">{{ $LANG['coinbase_api_key_hint'] ?? 'From Coinbase Commerce → Settings → API keys' }}</small>
								</div>
								<div class="mb-0">
									<label class="form-label">{{ $LANG['coinbase_webhook_secret'] ?? 'Webhook Shared Secret' }}</label>
									<input type="password" name="coinbase_webhook_secret" value="{{ $biller['coinbase_webhook_secret'] ?? '' }}" class="form-control" autocomplete="new-password" />
									<small class="form-hint">{{ $LANG['coinbase_webhook_secret_hint'] ?? 'From Coinbase Commerce → Settings → Webhook subscriptions. Webhook URL: ' }}{{ $siUrl ?? '' }}/index.php?module=api&amp;view=coinbase_webhook&amp;biller_id={{ $biller['id'] ?? '' }}&amp;domain_id={{ $biller['domain_id'] ?? '' }}</small>
								</div>
							</div>
						</div>
					</div>

					{{-- Adyen --}}
					<div class="accordion-item">
						<h2 class="accordion-header" id="eph-adyen">
							<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#epc-adyen" aria-expanded="false" aria-controls="epc-adyen">
								<i class="ti ti-credit-card me-2"></i>Adyen
								@if(!empty($biller['adyen_api_key']))<span class="badge bg-success ms-2">{{ $LANG['configured'] ?? 'Configured' }}</span>@endif
							</button>
						</h2>
						<div id="epc-adyen" class="accordion-collapse collapse" aria-labelledby="eph-adyen">
							<div class="accordion-body">
								<div class="mb-3">
									<label class="form-label">{{ $LANG['adyen_api_key'] ?? 'Adyen API Key' }}</label>
									<input type="password" name="adyen_api_key" value="{{ $biller['adyen_api_key'] ?? '' }}" class="form-control" autocomplete="new-password" />
									<small class="form-hint">{{ $LANG['adyen_api_key_hint'] ?? 'From Adyen Customer Area → Developers → API credentials' }}</small>
								</div>
								<div class="mb-3">
									<label class="form-label">{{ $LANG['adyen_merchant_account'] ?? 'Merchant Account' }}</label>
									<input type="text" name="adyen_merchant_account" value="{{ $biller['adyen_merchant_account'] ?? '' }}" class="form-control" />
								</div>
								<div class="mb-3">
									<label class="form-label">{{ $LANG['adyen_hmac_key'] ?? 'Webhook HMAC Key' }}</label>
									<input type="password" name="adyen_hmac_key" value="{{ $biller['adyen_hmac_key'] ?? '' }}" class="form-control" autocomplete="new-password" />
									<small class="form-hint">{{ $LANG['adyen_hmac_key_hint'] ?? 'From Adyen Customer Area → Developers → Webhooks → your endpoint → HMAC key. Webhook URL: ' }}{{ $siUrl ?? '' }}/index.php?module=api&amp;view=adyen_webhook</small>
								</div>
								<div class="mb-3">
									<label class="form-label">{{ $LANG['adyen_live_prefix'] ?? 'Live Endpoint Prefix' }}</label>
									<input type="text" name="adyen_live_prefix" value="{{ $biller['adyen_live_prefix'] ?? '' }}" class="form-control" />
									<small class="form-hint">{{ $LANG['adyen_live_prefix_hint'] ?? 'Required for live mode only. Found in Adyen Customer Area → Developers → API URLs (e.g. 1797a841fbb37ca7).' }}</small>
								</div>
								<div class="mb-0">
									<label class="form-label">{{ $LANG['adyen_test_mode'] ?? 'Adyen Test Mode' }}</label>
									<select name="adyen_test_mode" class="form-select">
										<option value="1" @if(($biller['adyen_test_mode'] ?? 1) == 1) selected @endif>{{ $LANG['yes'] ?? 'Yes' }} (test)</option>
										<option value="0" @if(($biller['adyen_test_mode'] ?? 1) == 0) selected @endif>{{ $LANG['no'] ?? 'No' }} (live)</option>
									</select>
								</div>
							</div>
						</div>
					</div>

					{{-- Payments Gateway --}}
					<div class="accordion-item">
						<h2 class="accordion-header" id="eph-pgw">
							<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#epc-pgw" aria-expanded="false" aria-controls="epc-pgw">
								<i class="ti ti-credit-card me-2"></i>{{ $LANG['paymentsgateway_modern'] ?? 'Payments Gateway' }}
								@if(!empty($biller['paymentsgateway_api_id']))<span class="badge bg-success ms-2">{{ $LANG['configured'] ?? 'Configured' }}</span>@endif
							</button>
						</h2>
						<div id="epc-pgw" class="accordion-collapse collapse" aria-labelledby="eph-pgw">
							<div class="accordion-body">
								<div class="mb-0">
									<label class="form-label">{{ $LANG['paymentsgateway_api_id'] ?? 'API Login ID' }}</label>
									<input type="text" name="paymentsgateway_api_id" value="{{ $biller['paymentsgateway_api_id'] ?? '' }}" class="form-control" />
									<small class="form-hint">{{ $LANG['paymentsgateway_api_id_hint'] ?? 'Your PaymentsGateway.net API Login ID. Return URL will be configured automatically.' }}</small>
								</div>
							</div>
						</div>
					</div>

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
