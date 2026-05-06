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
<form name="frmpost" action="index.php?module=billers&amp;view=add" method="post" id="frmpost" class="needs-validation" novalidate>

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
				<a class="nav-link" href="#bill-add-bank" data-bs-toggle="tab" role="tab"><i class="ti ti-building-bank me-1"></i>{{ $LANG['bank_details'] ?? 'Bank Details' }}</a>
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
					<input type="text" name="name" value="{{ post('name') }}" id="name" class="form-control" required />
					<div class="invalid-feedback">{{ $LANG['required_field'] ?? 'Required' }}</div>
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
				<p class="text-muted small mb-3">{{ $LANG['payment_processors_add_note'] ?? 'Webhook URLs that include a Biller ID are completed automatically on the Edit Biller screen after you save.' }}</p>
				<div class="accordion" id="addPaymentAccordion">

					{{-- Stripe --}}
					<div class="accordion-item">
						<h2 class="accordion-header" id="aph-stripe">
							<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#apc-stripe" aria-expanded="false" aria-controls="apc-stripe">
								<i class="ti ti-brand-stripe me-2"></i>Stripe
								@if(!empty(post('stripe_secret_key')))<span class="badge bg-success ms-2">{{ $LANG['configured'] ?? 'Configured' }}</span>@endif
							</button>
						</h2>
						<div id="apc-stripe" class="accordion-collapse collapse" aria-labelledby="aph-stripe">
							<div class="accordion-body">
								<div class="mb-3">
									<label class="form-label">{{ $LANG['stripe_secret_key'] ?? 'Stripe Secret Key' }}</label>
									<input type="password" name="stripe_secret_key" value="{{ post('stripe_secret_key') }}" class="form-control" autocomplete="new-password" />
									<small class="form-hint">{{ $LANG['stripe_secret_key_hint'] ?? 'From Stripe Dashboard → Developers → API keys (sk_live_… or sk_test_…)' }}</small>
								</div>
								<div class="mb-3">
									<label class="form-label">{{ $LANG['stripe_webhook_secret'] ?? 'Stripe Webhook Secret' }}</label>
									<input type="password" name="stripe_webhook_secret" value="{{ post('stripe_webhook_secret') }}" class="form-control" autocomplete="new-password" />
									<small class="form-hint">{{ $LANG['stripe_webhook_secret_hint'] ?? 'From Stripe Dashboard → Developers → Webhooks → your endpoint (whsec_…). Webhook URL: ' }}{{ $siUrl ?? '' }}/index.php?module=api&amp;view=stripe_webhook&amp;biller_id=&lt;ID&gt;&amp;domain_id={{ (int)($currentDomainId ?? 0) }}</small>
								</div>
								<div class="mb-0">
									<label class="form-label">{{ $LANG['stripe_test_mode'] ?? 'Stripe Test Mode' }}</label>
									<select name="stripe_test_mode" class="form-select">
										<option value="1" @if((int) post('stripe_test_mode', 1) === 1) selected @endif>{{ $LANG['yes'] ?? 'Yes' }} (test)</option>
										<option value="0" @if((int) post('stripe_test_mode', 1) === 0) selected @endif>{{ $LANG['no'] ?? 'No' }} (live)</option>
									</select>
								</div>
							</div>
						</div>
					</div>

					{{-- PayPal --}}
					<div class="accordion-item">
						<h2 class="accordion-header" id="aph-paypal">
							<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#apc-paypal" aria-expanded="false" aria-controls="apc-paypal">
								<i class="ti ti-brand-paypal me-2"></i>PayPal Commerce
								@if(!empty(post('paypal_client_id')))<span class="badge bg-success ms-2">{{ $LANG['configured'] ?? 'Configured' }}</span>@endif
							</button>
						</h2>
						<div id="apc-paypal" class="accordion-collapse collapse" aria-labelledby="aph-paypal">
							<div class="accordion-body">
								<div class="mb-3">
									<label class="form-label">{{ $LANG['paypal_client_id'] ?? 'PayPal Client ID' }}</label>
									<input type="text" name="paypal_client_id" value="{{ post('paypal_client_id') }}" class="form-control" />
									<small class="form-hint">{{ $LANG['paypal_client_id_hint'] ?? 'From PayPal Developer Dashboard → My Apps → your app → Client ID' }}</small>
								</div>
								<div class="mb-3">
									<label class="form-label">{{ $LANG['paypal_client_secret'] ?? 'PayPal Client Secret' }}</label>
									<input type="password" name="paypal_client_secret" value="{{ post('paypal_client_secret') }}" class="form-control" autocomplete="new-password" />
								</div>
								<div class="mb-0">
									<label class="form-label">{{ $LANG['paypal_test_mode'] ?? 'PayPal Sandbox Mode' }}</label>
									<select name="paypal_test_mode" class="form-select">
										<option value="1" @if((int) post('paypal_test_mode', 1) === 1) selected @endif>{{ $LANG['yes'] ?? 'Yes' }} (sandbox)</option>
										<option value="0" @if((int) post('paypal_test_mode', 1) === 0) selected @endif>{{ $LANG['no'] ?? 'No' }} (live)</option>
									</select>
								</div>
							</div>
						</div>
					</div>

					{{-- Mollie --}}
					<div class="accordion-item">
						<h2 class="accordion-header" id="aph-mollie">
							<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#apc-mollie" aria-expanded="false" aria-controls="apc-mollie">
								<i class="ti ti-credit-card me-2"></i>Mollie
								@if(!empty(post('mollie_api_key')))<span class="badge bg-success ms-2">{{ $LANG['configured'] ?? 'Configured' }}</span>@endif
							</button>
						</h2>
						<div id="apc-mollie" class="accordion-collapse collapse" aria-labelledby="aph-mollie">
							<div class="accordion-body">
								<div class="mb-0">
									<label class="form-label">{{ $LANG['mollie_api_key'] ?? 'Mollie API Key' }}</label>
									<input type="password" name="mollie_api_key" value="{{ post('mollie_api_key') }}" class="form-control" autocomplete="new-password" />
									<small class="form-hint">{{ $LANG['mollie_api_key_hint'] ?? 'From Mollie Dashboard → Developers → API keys (test_… or live_…). Webhook URL: ' }}{{ $siUrl ?? '' }}/index.php?module=api&amp;view=mollie_webhook</small>
								</div>
							</div>
						</div>
					</div>

					{{-- Authorize.net --}}
					<div class="accordion-item">
						<h2 class="accordion-header" id="aph-authnet">
							<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#apc-authnet" aria-expanded="false" aria-controls="apc-authnet">
								<i class="ti ti-credit-card me-2"></i>Authorize.net
								@if(!empty(post('authorizenet_login_id')))<span class="badge bg-success ms-2">{{ $LANG['configured'] ?? 'Configured' }}</span>@endif
							</button>
						</h2>
						<div id="apc-authnet" class="accordion-collapse collapse" aria-labelledby="aph-authnet">
							<div class="accordion-body">
								<div class="mb-3">
									<label class="form-label">{{ $LANG['authorizenet_login_id'] ?? 'API Login ID' }}</label>
									<input type="text" name="authorizenet_login_id" value="{{ post('authorizenet_login_id') }}" class="form-control" />
								</div>
								<div class="mb-3">
									<label class="form-label">{{ $LANG['authorizenet_transaction_key'] ?? 'Transaction Key' }}</label>
									<input type="password" name="authorizenet_transaction_key" value="{{ post('authorizenet_transaction_key') }}" class="form-control" autocomplete="new-password" />
								</div>
								<div class="mb-3">
									<label class="form-label">{{ $LANG['authorizenet_signature_key'] ?? 'Signature Key' }}
										<small class="text-muted">({{ $LANG['optional'] ?? 'optional' }})</small>
									</label>
									<input type="password" name="authorizenet_signature_key" value="{{ post('authorizenet_signature_key') }}" class="form-control" autocomplete="new-password" />
									<small class="form-hint">{{ $LANG['authorizenet_signature_key_hint'] ?? 'Used to verify webhook notifications. From Authorize.net Merchant Interface → Account → API Credentials & Keys.' }}
									{{ $LANG['authorizenet_webhook_url_hint'] ?? 'Webhook URL: ' }}{{ $siUrl ?? '' }}/index.php?module=api&amp;view=authorizenet_webhook&amp;biller_id=&lt;ID&gt;&amp;domain_id={{ (int)($currentDomainId ?? 0) }}</small>
								</div>
								<div class="mb-0">
									<label class="form-label">{{ $LANG['authorizenet_test_mode'] ?? 'Authorize.net Sandbox Mode' }}</label>
									<select name="authorizenet_test_mode" class="form-select">
										<option value="1" @if((int) post('authorizenet_test_mode', 1) === 1) selected @endif>{{ $LANG['yes'] ?? 'Yes' }} (sandbox)</option>
										<option value="0" @if((int) post('authorizenet_test_mode', 1) === 0) selected @endif>{{ $LANG['no'] ?? 'No' }} (live)</option>
									</select>
								</div>
							</div>
						</div>
					</div>

					{{-- eWay --}}
					<div class="accordion-item">
						<h2 class="accordion-header" id="aph-eway">
							<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#apc-eway" aria-expanded="false" aria-controls="apc-eway">
								<i class="ti ti-credit-card me-2"></i>{{ $LANG['eway_rapid'] ?? 'eWay Rapid' }}
								@if(!empty(post('eway_api_key')))<span class="badge bg-success ms-2">{{ $LANG['configured'] ?? 'Configured' }}</span>@endif
							</button>
						</h2>
						<div id="apc-eway" class="accordion-collapse collapse" aria-labelledby="aph-eway">
							<div class="accordion-body">
								<div class="mb-3">
									<label class="form-label">{{ $LANG['eway_api_key'] ?? 'eWay API Key' }}</label>
									<input type="password" name="eway_api_key" value="{{ post('eway_api_key') }}" class="form-control" autocomplete="new-password" />
									<small class="form-hint">{{ $LANG['eway_api_key_hint'] ?? 'From eWay My.eWay → API Keys (Rapid API Key)' }}</small>
								</div>
								<div class="mb-3">
									<label class="form-label">{{ $LANG['eway_api_password'] ?? 'eWay API Password' }}</label>
									<input type="password" name="eway_api_password" value="{{ post('eway_api_password') }}" class="form-control" autocomplete="new-password" />
								</div>
								<div class="mb-0">
									<label class="form-label">{{ $LANG['eway_test_mode'] ?? 'eWay Sandbox Mode' }}</label>
									<select name="eway_test_mode" class="form-select">
										<option value="1" @if((int) post('eway_test_mode', 1) === 1) selected @endif>{{ $LANG['yes'] ?? 'Yes' }} (sandbox)</option>
										<option value="0" @if((int) post('eway_test_mode', 1) === 0) selected @endif>{{ $LANG['no'] ?? 'No' }} (live)</option>
									</select>
								</div>
							</div>
						</div>
					</div>

					{{-- Ko-fi --}}
					<div class="accordion-item">
						<h2 class="accordion-header" id="aph-kofi">
							<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#apc-kofi" aria-expanded="false" aria-controls="apc-kofi">
								<i class="ti ti-coffee me-2"></i>Ko-fi
								@if(!empty(post('kofi_username')))<span class="badge bg-success ms-2">{{ $LANG['configured'] ?? 'Configured' }}</span>@endif
							</button>
						</h2>
						<div id="apc-kofi" class="accordion-collapse collapse" aria-labelledby="aph-kofi">
							<div class="accordion-body">
								<div class="mb-0">
									<label class="form-label">{{ $LANG['kofi_username'] ?? 'Ko-fi Username' }}</label>
									<input type="text" name="kofi_username" value="{{ post('kofi_username') }}" class="form-control" />
									<small class="form-hint">{{ $LANG['kofi_username_hint'] ?? 'Your Ko-fi page username (e.g. yourname from ko-fi.com/yourname). Customers will be sent to your Ko-fi tip page.' }}</small>
								</div>
							</div>
						</div>
					</div>

					{{-- Coinbase --}}
					<div class="accordion-item">
						<h2 class="accordion-header" id="aph-coinbase">
							<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#apc-coinbase" aria-expanded="false" aria-controls="apc-coinbase">
								<i class="ti ti-currency-bitcoin me-2"></i>{{ $LANG['coinbase_commerce'] ?? 'Coinbase Commerce' }}
								@if(!empty(post('coinbase_api_key')))<span class="badge bg-success ms-2">{{ $LANG['configured'] ?? 'Configured' }}</span>@endif
							</button>
						</h2>
						<div id="apc-coinbase" class="accordion-collapse collapse" aria-labelledby="aph-coinbase">
							<div class="accordion-body">
								<div class="mb-3">
									<label class="form-label">{{ $LANG['coinbase_api_key'] ?? 'Coinbase Commerce API Key' }}</label>
									<input type="password" name="coinbase_api_key" value="{{ post('coinbase_api_key') }}" class="form-control" autocomplete="new-password" />
									<small class="form-hint">{{ $LANG['coinbase_api_key_hint'] ?? 'From Coinbase Commerce → Settings → API keys' }}</small>
								</div>
								<div class="mb-0">
									<label class="form-label">{{ $LANG['coinbase_webhook_secret'] ?? 'Webhook Shared Secret' }}</label>
									<input type="password" name="coinbase_webhook_secret" value="{{ post('coinbase_webhook_secret') }}" class="form-control" autocomplete="new-password" />
									<small class="form-hint">{{ $LANG['coinbase_webhook_secret_hint'] ?? 'From Coinbase Commerce → Settings → Webhook subscriptions. Webhook URL: ' }}{{ $siUrl ?? '' }}/index.php?module=api&amp;view=coinbase_webhook&amp;biller_id=&lt;ID&gt;&amp;domain_id={{ (int)($currentDomainId ?? 0) }}</small>
								</div>
							</div>
						</div>
					</div>

					{{-- Adyen --}}
					<div class="accordion-item">
						<h2 class="accordion-header" id="aph-adyen">
							<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#apc-adyen" aria-expanded="false" aria-controls="apc-adyen">
								<i class="ti ti-credit-card me-2"></i>Adyen
								@if(!empty(post('adyen_api_key')))<span class="badge bg-success ms-2">{{ $LANG['configured'] ?? 'Configured' }}</span>@endif
							</button>
						</h2>
						<div id="apc-adyen" class="accordion-collapse collapse" aria-labelledby="aph-adyen">
							<div class="accordion-body">
								<div class="mb-3">
									<label class="form-label">{{ $LANG['adyen_api_key'] ?? 'Adyen API Key' }}</label>
									<input type="password" name="adyen_api_key" value="{{ post('adyen_api_key') }}" class="form-control" autocomplete="new-password" />
									<small class="form-hint">{{ $LANG['adyen_api_key_hint'] ?? 'From Adyen Customer Area → Developers → API credentials' }}</small>
								</div>
								<div class="mb-3">
									<label class="form-label">{{ $LANG['adyen_merchant_account'] ?? 'Merchant Account' }}</label>
									<input type="text" name="adyen_merchant_account" value="{{ post('adyen_merchant_account') }}" class="form-control" />
								</div>
								<div class="mb-3">
									<label class="form-label">{{ $LANG['adyen_hmac_key'] ?? 'Webhook HMAC Key' }}</label>
									<input type="password" name="adyen_hmac_key" value="{{ post('adyen_hmac_key') }}" class="form-control" autocomplete="new-password" />
									<small class="form-hint">{{ $LANG['adyen_hmac_key_hint'] ?? 'From Adyen Customer Area → Developers → Webhooks → your endpoint → HMAC key. Webhook URL: ' }}{{ $siUrl ?? '' }}/index.php?module=api&amp;view=adyen_webhook</small>
								</div>
								<div class="mb-3">
									<label class="form-label">{{ $LANG['adyen_live_prefix'] ?? 'Live Endpoint Prefix' }}</label>
									<input type="text" name="adyen_live_prefix" value="{{ post('adyen_live_prefix') }}" class="form-control" />
									<small class="form-hint">{{ $LANG['adyen_live_prefix_hint'] ?? 'Required for live mode only. Found in Adyen Customer Area → Developers → API URLs (e.g. 1797a841fbb37ca7).' }}</small>
								</div>
								<div class="mb-0">
									<label class="form-label">{{ $LANG['adyen_test_mode'] ?? 'Adyen Test Mode' }}</label>
									<select name="adyen_test_mode" class="form-select">
										<option value="1" @if((int) post('adyen_test_mode', 1) === 1) selected @endif>{{ $LANG['yes'] ?? 'Yes' }} (test)</option>
										<option value="0" @if((int) post('adyen_test_mode', 1) === 0) selected @endif>{{ $LANG['no'] ?? 'No' }} (live)</option>
									</select>
								</div>
							</div>
						</div>
					</div>

					{{-- Payments Gateway --}}
					<div class="accordion-item">
						<h2 class="accordion-header" id="aph-pgw">
							<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#apc-pgw" aria-expanded="false" aria-controls="apc-pgw">
								<i class="ti ti-credit-card me-2"></i>{{ $LANG['paymentsgateway_modern'] ?? 'Payments Gateway' }}
								@if(!empty(post('paymentsgateway_api_id')))<span class="badge bg-success ms-2">{{ $LANG['configured'] ?? 'Configured' }}</span>@endif
							</button>
						</h2>
						<div id="apc-pgw" class="accordion-collapse collapse" aria-labelledby="aph-pgw">
							<div class="accordion-body">
								<div class="mb-0">
									<label class="form-label">{{ $LANG['paymentsgateway_api_id'] ?? 'API Login ID' }}</label>
									<input type="text" name="paymentsgateway_api_id" value="{{ post('paymentsgateway_api_id') }}" class="form-control" />
									<small class="form-hint">{{ $LANG['paymentsgateway_api_id_hint'] ?? 'Your PaymentsGateway.net API Login ID. Return URL will be configured automatically.' }}</small>
								</div>
							</div>
						</div>
					</div>

				</div>
			</div>
			<div id="bill-add-bank" class="tab-pane" role="tabpanel">
				<p class="text-muted small mb-3">{{ $LANG['bank_details_hint'] ?? 'Bank account details for electronic transfers. Each field has a corresponding token (shown below) that can be used in invoice preferences and the invoice footer to auto-fill values at render time.' }}</p>
				<div class="mb-3">
					<label class="form-label">{{ $LANG['bank_account_name'] ?? 'Account Name' }}</label>
					<input type="text" name="bank_account_name" value="{{ post('bank_account_name') }}" class="form-control" />
					<small class="form-hint">{{ $LANG['bank_account_name_hint'] ?? 'Legal name on the account' }} &mdash; Token: <code>{biller.bank_account_name}</code></small>
				</div>
				<div class="mb-3">
					<label class="form-label">{{ $LANG['bank_name'] ?? 'Bank Name' }}</label>
					<input type="text" name="bank_name" value="{{ post('bank_name') }}" class="form-control" />
					<small class="form-hint">{{ $LANG['bank_name_hint'] ?? 'Name of the bank or financial institution' }} &mdash; Token: <code>{biller.bank_name}</code></small>
				</div>
				<div class="mb-3">
					<label class="form-label">{{ $LANG['bank_swift_bic'] ?? 'SWIFT / BIC' }}</label>
					<input type="text" name="bank_swift_bic" value="{{ post('bank_swift_bic') }}" class="form-control" />
					<small class="form-hint">{{ $LANG['bank_swift_bic_hint'] ?? 'Bank identifier — universal for international transfers' }} &mdash; Token: <code>{biller.bank_swift_bic}</code></small>
				</div>
				<div class="mb-3">
					<label class="form-label">{{ $LANG['bank_account_number'] ?? 'Account Number / IBAN' }}</label>
					<input type="text" name="bank_account_number" value="{{ post('bank_account_number') }}" class="form-control" />
					<small class="form-hint">{{ $LANG['bank_account_number_hint'] ?? 'IBAN (EU) or local account number' }} &mdash; Token: <code>{biller.bank_account_number}</code></small>
				</div>
				<div class="mb-3">
					<label class="form-label">{{ $LANG['bank_routing_sort_code'] ?? 'Routing / Sort Code' }}</label>
					<input type="text" name="bank_routing_sort_code" value="{{ post('bank_routing_sort_code') }}" class="form-control" />
					<small class="form-hint">{{ $LANG['bank_routing_sort_code_hint'] ?? 'BSB (AU), ABA (US), Sort Code (UK), Transit (CA) — leave blank if using IBAN' }} &mdash; Token: <code>{biller.bank_routing_sort_code}</code></small>
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
					<small class="form-hint">{{ $LANG['footer_token_hint'] ?? 'Tokens like {biller.bank_account_name}, {biller.bank_name}, {invoice.total}, and {customer.name} are replaced with live values when the invoice is rendered.' }}</small>
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
