@if(post('p_description') == "" AND form_submitted() )
	<div class="alert alert-warning"><i class="ti ti-alert-circle"></i>
		{{ $LANG['preference_description_required'] ?? '' }}</div>
@endif
<form name="frmpost" action="index.php?module=preferences&amp;view=save" method="post" class="needs-validation" novalidate>

<div class="card">
	<div class="card-header">
		<ul class="nav nav-tabs card-header-tabs" role="tablist">
			<li class="nav-item" role="presentation">
				<a class="nav-link active" href="#pref-add-details" data-bs-toggle="tab" role="tab"><i class="ti ti-info-circle me-1"></i>{{ $LANG['details'] ?? '' }}</a>
			</li>
			<li class="nav-item" role="presentation">
				<a class="nav-link" href="#pref-add-currency" data-bs-toggle="tab" role="tab"><i class="ti ti-currency-dollar me-1"></i>{{ $LANG['currency_sign'] ?? '' }}</a>
			</li>
			<li class="nav-item" role="presentation">
				<a class="nav-link" href="#pref-add-wording" data-bs-toggle="tab" role="tab"><i class="ti ti-pencil me-1"></i>{{ $LANG['invoice_heading'] ?? '' }}</a>
			</li>
			<li class="nav-item" role="presentation">
				<a class="nav-link" href="#pref-add-payment" data-bs-toggle="tab" role="tab"><i class="ti ti-credit-card me-1"></i>{{ $LANG['payment'] ?? '' }}</a>
			</li>
		</ul>
	</div>
	<div class="card-body">
		<div class="tab-content">
			<div id="pref-add-details" class="tab-pane active" role="tabpanel">
				<div class="mb-3">
					<label class="form-label">{{ $LANG['description'] ?? '' }}
						<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_required_field" title="{{ $LANG['required_field'] ?? '' }}"><i class="ti ti-asterisk text-danger"></i></a>
						<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_description" title="{{ $LANG['description'] ?? '' }}"><i class="ti ti-help"></i></a>
					</label>
					<input type="text" class="form-control" name="p_description" value="{{ post('p_description') }}" required />
					<div class="invalid-feedback">{{ $LANG['required_field'] ?? 'Required' }}</div>
				</div>
				<div class="mb-3">
					<label class="form-label">{{ $LANG['status'] ?? '' }}
						<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_status" title="{{ $LANG['status'] ?? '' }}"><i class="ti ti-help"></i></a>
					</label>
					<select name="status" class="form-select">
						<option value="1" selected>{{ $LANG['real'] ?? '' }}</option>
						<option value="0">{{ $LANG['draft'] ?? '' }}</option>
					</select>
				</div>
				<div class="mb-3">
					<label class="form-label">{{ $LANG['invoice_numbering_group'] ?? '' }}
						<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_invoice_numbering_group" title="{{ $LANG['invoice_numbering_group'] ?? '' }}"><i class="ti ti-help"></i></a>
					</label>
					@if($preferences == null)
						<p class="text-muted"><em>{{ $LANG['no_preferences'] ?? '' }}</em></p>
					@else
						<select name="index_group" class="form-select">
							<option value="">{{ $LANG['invoice_preference_to_add'] ?? '' }}</option>
							@foreach(($preferences ?? []) as $preference)
								<option @if($LANG['real'] == $defaults->preference) selected @endif value="{{ $preference['pref_id'] ?? '' }}">{{ $preference['pref_description'] ?? '' }}</option>
							@endforeach
						</select>
					@endif
				</div>
				<div class="mb-3">
					<label class="form-label">{{ $LANG['locale'] ?? '' }}
						<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_locale" title="{{ $LANG['locale'] ?? '' }}"><i class="ti ti-help"></i></a>
					</label>
				<select name="locale" class="form-select">
					@foreach(($localelist ?? []) as $localeCode => $localeLabel)
						<option @if((string) $localeCode === (string) ($defaultSystemLocale ?? 'en_GB')) selected @endif value="{{ $localeCode }}">{{ $localeLabel }}</option>
					@endforeach
				</select>
				</div>
				<div class="mb-3">
					<label class="form-label">{{ $LANG['enabled'] ?? '' }}
						<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_invoice_enabled" title="{{ $LANG['enabled'] ?? '' }}"><i class="ti ti-help"></i></a>
					</label>
					<select name="pref_enabled" class="form-select">
						<option value="1" selected>{{ $LANG['enabled'] ?? '' }}</option>
						<option value="0">{{ $LANG['disabled'] ?? '' }}</option>
					</select>
				</div>
			</div>
			<div id="pref-add-currency" class="tab-pane" role="tabpanel">
				@include('templates.default.partials.currency_sign_field', [
					'currencySignFieldName'        => 'p_currency_sign',
					'currencySignCurrentValue'     => post('p_currency_sign'),
					'currencyCodeFieldName'        => 'currency_code',
					'currencyCodeCurrentValue'     => post('currency_code'),
					'currencyPositionFieldName'    => 'currency_position',
					'currencyPositionCurrentValue' => $preference['currency_position'] ?? '',
					'currencyIdFieldName'          => 'currency_id',
					'currencyIdCurrentValue'       => post('currency_id'),
				])
				<div class="mb-3 mt-3">
					<div class="form-check form-switch">
						<input type="hidden" name="show_currency_code" value="0" />
						<input class="form-check-input" type="checkbox" name="show_currency_code" id="si_show_currency_code" value="1" @if(post('show_currency_code')) checked @endif />
						<label class="form-check-label" for="si_show_currency_code">{{ $LANG['show_currency_code'] ?? 'Show currency code on invoices' }}</label>
					</div>
				</div>
				<div class="mb-3 mt-3">
					<label class="form-label">{{ $LANG['payment_terms'] ?? 'Payment terms' }}</label>
					<select name="payment_term_id" class="form-select">
						<option value="">{{ $LANG['payment_term_none'] ?? '-' }}</option>
						@foreach(($paymentTerms ?? []) as $pt)
							<option value="{{ $pt['term_id'] ?? '' }}" @if((string) post('payment_term_id') === (string)($pt['term_id'] ?? '')) selected @endif>{{ $pt['term_label'] ?? '' }}</option>
						@endforeach
					</select>
				</div>
				<div class="mb-3">
					<label class="form-label">{{ $LANG['payment_bank_name'] ?? 'Bank name' }}</label>
					<input type="text" name="payment_bank_name" value="{{ post('payment_bank_name') }}" class="form-control" placeholder="e.g. First National Bank" />
				</div>
				<div class="mb-3">
					<label class="form-label">{{ $LANG['payment_reference'] ?? 'Payment reference' }}</label>
					<input type="text" name="payment_reference" value="{{ post('payment_reference') }}" class="form-control" placeholder="e.g. Invoice #{invoice.number}" />
				</div>
			</div>
			<div id="pref-add-wording" class="tab-pane" role="tabpanel">
				<div class="mb-3">
					<label class="form-label">{{ $LANG['invoice_heading'] ?? '' }}
						<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_invoice_heading" title="{{ $LANG['invoice_heading'] ?? '' }}"><i class="ti ti-help"></i></a>
					</label>
					<input type="text" name="p_inv_heading" value="{{ post('p_inv_heading') }}" class="form-control" />
				</div>
				<div class="mb-3">
					<label class="form-label">{{ $LANG['invoice_wording'] ?? '' }}
						<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_invoice_wording" title="{{ $LANG['invoice_wording'] ?? '' }}"><i class="ti ti-help"></i></a>
					</label>
					<input type="text" name="p_inv_wording" value="{{ post('p_inv_wording') }}" class="form-control" />
				</div>
				<div class="mb-3">
					<label class="form-label">{{ $LANG['invoice_detail_heading'] ?? '' }}
						<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_invoice_detail_heading" title="{{ $LANG['invoice_detail_heading'] ?? '' }}"><i class="ti ti-help"></i></a>
					</label>
					<input type="text" name="p_inv_detail_heading" value="{{ post('p_inv_detail_heading') }}" class="form-control" />
				</div>
				<div class="mb-3">
					<label class="form-label">{{ $LANG['invoice_detail_line'] ?? '' }}
						<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_invoice_detail_line" title="{{ $LANG['invoice_detail_line'] ?? '' }}"><i class="ti ti-help"></i></a>
					</label>
					<input type="text" name="p_inv_detail_line" value="{{ post('p_inv_detail_line') }}" class="form-control" />
				</div>
			</div>
			<div id="pref-add-payment" class="tab-pane" role="tabpanel">
				<p class="text-muted small mb-3">{{ $LANG['payment_token_hint'] ?? 'Tokens are replaced with live values when the invoice is rendered. Biller bank details: {biller.bank_account_name}, {biller.bank_name}, {biller.bank_swift_bic}, {biller.bank_account_number}, {biller.bank_routing_sort_code}. Other tokens: {biller.name}, {biller.email}, {biller.phone}, {customer.name}, {invoice.total}, {invoice.owing}.' }}</p>
				<div class="mb-3">
					<label class="form-label">{{ $LANG['include_online_payment'] ?? '' }}
						<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_invoice_detail_line" title="{{ $LANG['invoice_detail_line'] ?? '' }}"><i class="ti ti-help"></i></a>
					</label>
					<div>
						<div class="form-check form-check-inline">
							<input type="checkbox" class="form-check-input" name="include_online_payment[]" value="stripe" id="iop_stripe" />
							<label class="form-check-label" for="iop_stripe">Stripe</label>
						</div>
						<div class="form-check form-check-inline">
							<input type="checkbox" class="form-check-input" name="include_online_payment[]" value="paypal_commerce" id="iop_paypal_commerce" />
							<label class="form-check-label" for="iop_paypal_commerce">PayPal Commerce</label>
						</div>
						<div class="form-check form-check-inline">
							<input type="checkbox" class="form-check-input" name="include_online_payment[]" value="mollie" id="iop_mollie" />
							<label class="form-check-label" for="iop_mollie">Mollie</label>
						</div>
						<div class="form-check form-check-inline">
							<input type="checkbox" class="form-check-input" name="include_online_payment[]" value="authorizenet" id="iop_authorizenet" />
							<label class="form-check-label" for="iop_authorizenet">Authorize.net</label>
						</div>
						<div class="form-check form-check-inline">
							<input type="checkbox" class="form-check-input" name="include_online_payment[]" value="eway_rapid" id="iop_eway_rapid" />
							<label class="form-check-label" for="iop_eway_rapid">{{ $LANG['eway_rapid'] ?? 'eWay Rapid' }}</label>
						</div>
						<div class="form-check form-check-inline">
							<input type="checkbox" class="form-check-input" name="include_online_payment[]" value="paymentsgateway_modern" id="iop_pg_modern" />
							<label class="form-check-label" for="iop_pg_modern">{{ $LANG['paymentsgateway_modern'] ?? 'Payments Gateway' }}</label>
						</div>
						<div class="form-check form-check-inline">
							<input type="checkbox" class="form-check-input" name="include_online_payment[]" value="kofi" id="iop_kofi" />
							<label class="form-check-label" for="iop_kofi">Ko-fi</label>
						</div>
						<div class="form-check form-check-inline">
							<input type="checkbox" class="form-check-input" name="include_online_payment[]" value="coinbase" id="iop_coinbase" />
							<label class="form-check-label" for="iop_coinbase">{{ $LANG['coinbase_commerce'] ?? 'Coinbase Commerce' }}</label>
						</div>
						<div class="form-check form-check-inline">
							<input type="checkbox" class="form-check-input" name="include_online_payment[]" value="adyen" id="iop_adyen" />
							<label class="form-check-label" for="iop_adyen">Adyen</label>
						</div>
					</div>
				</div>
				<div class="mb-3">
					<label class="form-label">{{ $LANG['invoice_payment_method'] ?? '' }}
						<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_invoice_payment_method" title="{{ $LANG['invoice_payment_method'] ?? '' }}"><i class="ti ti-help"></i></a>
					</label>
					<input type="text" name="p_inv_payment_method" value="{{ post('p_inv_payment_method') }}" class="form-control" />
				</div>
				<div class="row g-3">
					<div class="col-md-6">
						<div class="mb-3">
							<label class="form-label">{{ $LANG['invoice_payment_line_1_name'] ?? '' }}
								<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_payment_line1_name" title="{{ $LANG['invoice_payment_line_1_name'] ?? '' }}"><i class="ti ti-help"></i></a>
							</label>
							<input type="text" name="p_inv_payment_line1_name" value="{{ post('p_inv_payment_line1_name') }}" class="form-control" />
						</div>
					</div>
					<div class="col-md-6">
						<div class="mb-3">
							<label class="form-label">{{ $LANG['invoice_payment_line_1_value'] ?? '' }}
								<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_payment_line1_value" title="{{ $LANG['invoice_payment_line_1_value'] ?? '' }}"><i class="ti ti-help"></i></a>
							</label>
							<input type="text" name="p_inv_payment_line1_value" value="{{ post('p_inv_payment_line1_value') }}" class="form-control" />
						</div>
					</div>
				</div>
				<div class="row g-3">
					<div class="col-md-6">
						<div class="mb-3">
							<label class="form-label">{{ $LANG['invoice_payment_line_2_name'] ?? '' }}
								<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_payment_line2_name" title="{{ $LANG['invoice_payment_line_2_name'] ?? '' }}"><i class="ti ti-help"></i></a>
							</label>
							<input type="text" name="p_inv_payment_line2_name" value="{{ post('p_inv_payment_line2_name') }}" class="form-control" />
						</div>
					</div>
					<div class="col-md-6">
						<div class="mb-3">
							<label class="form-label">{{ $LANG['invoice_payment_line_2_value'] ?? '' }}
								<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_payment_line2_value" title="{{ $LANG['invoice_payment_line_2_value'] ?? '' }}"><i class="ti ti-help"></i></a>
							</label>
							<input type="text" name="p_inv_payment_line2_value" value="{{ post('p_inv_payment_line2_value') }}" class="form-control" />
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="card-footer">
		<div class="d-flex">
			<a href="./index.php?module=preferences&amp;view=manage" class="btn btn-link">{{ $LANG['cancel'] ?? '' }}</a>
			<button type="submit" class="btn btn-primary ms-auto" name="insert_preference" value="{{ $LANG['save'] ?? '' }}"><i class="ti ti-check me-1"></i>{{ $LANG['save'] ?? '' }}
			</button>
		</div>
	</div>
</div>

<input type="hidden" name="op" value="insert_preference" />
</form>
