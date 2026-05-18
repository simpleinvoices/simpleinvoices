<!--Modified code to display apostrophes in text box output 05/02/2008-Gates-->
<form name="frmpost" action="index.php?module=preferences&amp;view=save&amp;id={{ get('id') }}" method="post" class="needs-validation" novalidate>


@if(get('action')== 'view' )

<div class="card">
	<div class="card-header">
		<ul class="nav nav-tabs card-header-tabs" role="tablist">
			<li class="nav-item" role="presentation">
				<a class="nav-link active" href="#pref-view-details" data-bs-toggle="tab" role="tab"><i class="ti ti-info-circle me-1"></i>{{ $LANG['details'] ?? '' }}</a>
			</li>
			<li class="nav-item" role="presentation">
				<a class="nav-link" href="#pref-view-numbering" data-bs-toggle="tab" role="tab"><i class="ti ti-hash me-1"></i>{{ $LANG['numbering'] ?? 'Numbering' }}</a>
			</li>
			<li class="nav-item" role="presentation">
				<a class="nav-link" href="#pref-view-localization" data-bs-toggle="tab" role="tab"><i class="ti ti-world me-1"></i>{{ $LANG['localization'] ?? 'Localization' }}</a>
			</li>
			<li class="nav-item" role="presentation">
				<a class="nav-link" href="#pref-view-wording" data-bs-toggle="tab" role="tab"><i class="ti ti-pencil me-1"></i>{{ $LANG['invoice_heading'] ?? '' }}</a>
			</li>
			<li class="nav-item" role="presentation">
				<a class="nav-link" href="#pref-view-payment" data-bs-toggle="tab" role="tab"><i class="ti ti-credit-card me-1"></i>{{ $LANG['payment'] ?? '' }}</a>
			</li>
		</ul>
	</div>
	<div class="card-body">
		<div class="tab-content">
			<div id="pref-view-details" class="tab-pane active" role="tabpanel">
				<table class="table table-vcenter">
					<tr>
						<th class="col-4">{{ $LANG['description'] ?? '' }}
							<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_description" title="{{ $LANG['description'] ?? '' }}"><i class="ti ti-help"></i></a>
						</th>
						<td colspan="3">{{ $preference['pref_description'] }}</td>
					</tr>
					<tr>
						<th class="col-4">{{ $LANG['default_currency'] ?? 'Default currency' }}
							<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_currency_sign" title="{{ $LANG['default_currency'] ?? 'Default currency' }}"><i class="ti ti-help"></i></a>
						</th>
						<td>{{ ($preference['currency_sign'] ?? '')|si_currency_display }}
							@if(!empty($preference['currency_code']))
								<span class="text-secondary ms-1">({{ $preference['currency_code'] }})</span>
							@endif
						</td>
						<th class="col-4">{{ $LANG['default_payment_terms'] ?? 'Default payment terms' }}</th>
						<td>{{ $prefPaymentTermLabel ?? '' }}</td>
					</tr>
					<tr>
						<th>{{ $LANG['status'] ?? '' }}
							<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_status" title="{{ $LANG['status'] ?? '' }}"><i class="ti ti-help"></i></a>
						</th>
						<td>{{ $preference['status_wording'] }}</td>
						<th>{{ $LANG['enabled'] ?? '' }}
							<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_invoice_enabled" title="{{ $LANG['enabled'] ?? '' }}"><i class="ti ti-help"></i></a>
						</th>
						<td>{{ $preference['enabled'] }}</td>
					</tr>
				</table>
			</div>
			<div id="pref-view-numbering" class="tab-pane" role="tabpanel">
				<table class="table table-vcenter">
					<tr>
						<th class="col-4">{{ $LANG['invoice_numbering_group'] ?? '' }}
							<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_invoice_numbering_group" title="{{ $LANG['invoice_numbering_group'] ?? '' }}"><i class="ti ti-help"></i></a>
						</th>
						<td>{{ $index_group['pref_description'] ?? '' }}</td>
						<th class="col-4">{{ $LANG['next_invoice_number'] ?? 'Next invoice number' }}</th>
						<td><strong>{{ $next_invoice_number }}</strong></td>
					</tr>
					<tr>
						<th>{{ $LANG['invoice_id_prefix'] ?? 'Invoice ID Prefix' }}</th>
						<td>{{ $preference['pref_invoice_id_prefix'] ?? '' }}</td>
						<th>{{ $LANG['invoice_id_format'] ?? 'Invoice Number Format' }}</th>
						<td>{{ $preference['pref_invoice_id_format'] ?? '' }}</td>
					</tr>
				</table>
			</div>
			<div id="pref-view-localization" class="tab-pane" role="tabpanel">
				<table class="table table-vcenter">
					<tr>
						<th class="col-4">{{ $LANG['language'] ?? '' }}
							<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_language" title="{{ $LANG['language'] ?? '' }}"><i class="ti ti-help"></i></a>
						</th>
						<td>{{ $preference['language'] }}</td>
					</tr>
					<tr>
						<th class="col-4">{{ $LANG['locale'] ?? '' }}
							<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_locale" title="{{ $LANG['locale'] ?? '' }}"><i class="ti ti-help"></i></a>
						</th>
						<td>{{ $localelist[$preference['locale'] ?? ''] ?? $preference['locale'] ?? '' }}</td>
					</tr>
				</table>
			</div>
			<div id="pref-view-wording" class="tab-pane" role="tabpanel">
				<table class="table table-vcenter">
					<tr>
						<th class="col-4">{{ $LANG['invoice_heading'] ?? '' }}
							<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_invoice_heading" title="{{ $LANG['invoice_heading'] ?? '' }}"><i class="ti ti-help"></i></a>
						</th>
						<td>{{ $preference['pref_inv_heading'] ?? '' }}</td>
					</tr>
					<tr>
						<th class="col-4">{{ $LANG['invoice_wording'] ?? '' }}
							<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_invoice_wording" title="{{ $LANG['invoice_wording'] ?? '' }}"><i class="ti ti-help"></i></a>
						</th>
						<td>{{ $preference['pref_inv_wording'] ?? '' }}</td>
					</tr>
					<tr>
						<th class="col-4">{{ $LANG['invoice_detail_heading'] ?? '' }}
							<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_invoice_detail_heading" title="{{ $LANG['invoice_detail_heading'] ?? '' }}"><i class="ti ti-help"></i></a>
						</th>
						<td>{{ $preference['pref_inv_detail_heading'] ?? '' }}</td>
					</tr>
					<tr>
						<th class="col-4">{{ $LANG['invoice_detail_line'] ?? '' }}
							<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_invoice_detail_line" title="{{ $LANG['invoice_detail_line'] ?? '' }}"><i class="ti ti-help"></i></a>
						</th>
						<td>{{ $preference['pref_inv_detail_line'] ?? '' }}</td>
					</tr>
				</table>
			</div>
			<div id="pref-view-payment" class="tab-pane" role="tabpanel">
				<table class="table table-vcenter">
					<tr>
						<th class="col-4">{{ $LANG['include_online_payment'] ?? '' }}
							<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_invoice_detail_line" title="{{ $LANG['invoice_detail_line'] ?? '' }}"><i class="ti ti-help"></i></a>
						</th>
						<td colspan="3">
							<div class="form-check form-check-inline"><input type="checkbox" class="form-check-input" disabled @if(in_array("stripe", explode(",", $preference['include_online_payment'] ?? ''))) checked @endif /><label class="form-check-label">Stripe</label></div>
							<div class="form-check form-check-inline"><input type="checkbox" class="form-check-input" disabled @if(in_array("paypal_commerce", explode(",", $preference['include_online_payment'] ?? ''))) checked @endif /><label class="form-check-label">PayPal Commerce</label></div>
							<div class="form-check form-check-inline"><input type="checkbox" class="form-check-input" disabled @if(in_array("mollie", explode(",", $preference['include_online_payment'] ?? ''))) checked @endif /><label class="form-check-label">Mollie</label></div>
							<div class="form-check form-check-inline"><input type="checkbox" class="form-check-input" disabled @if(in_array("authorizenet", explode(",", $preference['include_online_payment'] ?? ''))) checked @endif /><label class="form-check-label">Authorize.net</label></div>
							<div class="form-check form-check-inline"><input type="checkbox" class="form-check-input" disabled @if(in_array("eway_rapid", explode(",", $preference['include_online_payment'] ?? ''))) checked @endif /><label class="form-check-label">{{ $LANG['eway_rapid'] ?? 'eWay Rapid' }}</label></div>
							<div class="form-check form-check-inline"><input type="checkbox" class="form-check-input" disabled @if(in_array("paymentsgateway_modern", explode(",", $preference['include_online_payment'] ?? ''))) checked @endif /><label class="form-check-label">{{ $LANG['paymentsgateway_modern'] ?? 'Payments Gateway' }}</label></div>
							<div class="form-check form-check-inline"><input type="checkbox" class="form-check-input" disabled @if(in_array("kofi", explode(",", $preference['include_online_payment'] ?? ''))) checked @endif /><label class="form-check-label">Ko-fi</label></div>
							<div class="form-check form-check-inline"><input type="checkbox" class="form-check-input" disabled @if(in_array("coinbase", explode(",", $preference['include_online_payment'] ?? ''))) checked @endif /><label class="form-check-label">{{ $LANG['coinbase_commerce'] ?? 'Coinbase Commerce' }}</label></div>
							<div class="form-check form-check-inline"><input type="checkbox" class="form-check-input" disabled @if(in_array("adyen", explode(",", $preference['include_online_payment'] ?? ''))) checked @endif /><label class="form-check-label">Adyen</label></div>
						</td>
					</tr>
					<tr>
						<th class="col-4">{{ $LANG['invoice_payment_method'] ?? '' }}
							<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_invoice_payment_method" title="{{ $LANG['invoice_payment_method'] ?? '' }}"><i class="ti ti-help"></i></a>
						</th>
						<td colspan="3">{{ $preference['pref_inv_payment_method'] ?? '' }}</td>
					</tr>
					<tr>
						<th>{{ $LANG['invoice_payment_line_0_name'] ?? '' }}</th>
						<td>{{ $preference['pref_inv_payment_line0_name'] ?? '' }}</td>
						<th>{{ $LANG['invoice_payment_line_0_value'] ?? '' }}</th>
						<td>{{ $preference['pref_inv_payment_line0_value'] ?? '' }}</td>
					</tr>
					<tr>
						<th>{{ $LANG['invoice_payment_line_1_name'] ?? '' }}
							<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_payment_line1_name" title="{{ $LANG['invoice_payment_line_1_name'] ?? '' }}"><i class="ti ti-help"></i></a>
						</th>
						<td>{{ $preference['pref_inv_payment_line1_name'] ?? '' }}</td>
						<th>{{ $LANG['invoice_payment_line_1_value'] ?? '' }}
							<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_payment_line1_value" title="{{ $LANG['invoice_payment_line_1_value'] ?? '' }}"><i class="ti ti-help"></i></a>
						</th>
						<td>{{ $preference['pref_inv_payment_line1_value'] ?? '' }}</td>
					</tr>
					<tr>
						<th>{{ $LANG['invoice_payment_line_2_name'] ?? '' }}
							<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_payment_line2_name" title="{{ $LANG['invoice_payment_line_2_name'] ?? '' }}"><i class="ti ti-help"></i></a>
						</th>
						<td>{{ $preference['pref_inv_payment_line2_name'] ?? '' }}</td>
						<th>{{ $LANG['invoice_payment_line_2_value'] ?? '' }}
							<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_payment_line2_value" title="{{ $LANG['invoice_payment_line_2_value'] ?? '' }}"><i class="ti ti-help"></i></a>
						</th>
						<td>{{ $preference['pref_inv_payment_line2_value'] ?? '' }}</td>
					</tr>
					<tr>
						<th>{{ $LANG['invoice_payment_line_3_name'] ?? '' }}</th>
						<td>{{ $preference['pref_inv_payment_line3_name'] ?? '' }}</td>
						<th>{{ $LANG['invoice_payment_line_3_value'] ?? '' }}</th>
						<td>{{ $preference['pref_inv_payment_line3_value'] ?? '' }}</td>
					</tr>
					<tr>
						<th>{{ $LANG['invoice_payment_line_4_name'] ?? '' }}</th>
						<td>{{ $preference['pref_inv_payment_line4_name'] ?? '' }}</td>
						<th>{{ $LANG['invoice_payment_line_4_value'] ?? '' }}</th>
						<td>{{ $preference['pref_inv_payment_line4_value'] ?? '' }}</td>
					</tr>
					<tr>
						<th>{{ $LANG['invoice_payment_line_5_name'] ?? '' }}</th>
						<td>{{ $preference['pref_inv_payment_line5_name'] ?? '' }}</td>
						<th>{{ $LANG['invoice_payment_line_5_value'] ?? '' }}</th>
						<td>{{ $preference['pref_inv_payment_line5_value'] ?? '' }}</td>
					</tr>
				</table>
			</div>
		</div>
	</div>

	<div class="card-footer">
		<div class="d-flex">
			<a href="./index.php?module=preferences&amp;view=manage" class="btn btn-link">{{ $LANG['cancel'] ?? '' }}</a>
			<a href="./index.php?module=preferences&amp;view=details&amp;id={{ $preference['pref_id'] ?? '' }}&amp;action=edit" class="btn btn-primary ms-auto"><i class="ti ti-edit me-1"></i>{{ $LANG['edit'] ?? '' }}</a>
		</div>
		<div class="mt-2">
			<a class="cluetip btn btn-outline-secondary" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_what_the" title="{{ $LANG['whats_all_this_inv_pref'] ?? '' }}"><i class="ti ti-help me-1"></i>{{ $LANG['whats_all_this_inv_pref'] ?? '' }}</a>
		</div>
	</div>
</div>
@endif





@if(get('action')== 'edit' )

<div class="card">
	<div class="card-header">
		<ul class="nav nav-tabs card-header-tabs" role="tablist">
			<li class="nav-item" role="presentation">
				<a class="nav-link active" href="#pref-edit-details" data-bs-toggle="tab" role="tab"><i class="ti ti-info-circle me-1"></i>{{ $LANG['details'] ?? '' }}</a>
			</li>
			<li class="nav-item" role="presentation">
				<a class="nav-link" href="#pref-edit-numbering" data-bs-toggle="tab" role="tab"><i class="ti ti-hash me-1"></i>{{ $LANG['numbering'] ?? 'Numbering' }}</a>
			</li>
			<li class="nav-item" role="presentation">
				<a class="nav-link" href="#pref-edit-localization" data-bs-toggle="tab" role="tab"><i class="ti ti-world me-1"></i>{{ $LANG['localization'] ?? 'Localization' }}</a>
			</li>
			<li class="nav-item" role="presentation">
				<a class="nav-link" href="#pref-edit-wording" data-bs-toggle="tab" role="tab"><i class="ti ti-pencil me-1"></i>{{ $LANG['invoice_heading'] ?? '' }}</a>
			</li>
			<li class="nav-item" role="presentation">
				<a class="nav-link" href="#pref-edit-payment" data-bs-toggle="tab" role="tab"><i class="ti ti-credit-card me-1"></i>{{ $LANG['payment'] ?? '' }}</a>
			</li>
		</ul>
	</div>
	<div class="card-body">
		@if($saved_flag)
			<div class="alert alert-success alert-dismissible"><button type="button" class="btn-close" data-bs-dismiss="alert"></button>{{ $LANG['save_preference_success'] ?? 'Preference saved successfully.' }}</div>
		@endif
		@if(!empty($starting_number_error))
			<div class="alert alert-warning alert-dismissible"><button type="button" class="btn-close" data-bs-dismiss="alert"></button>{!! htmlspecialchars($starting_number_error) !!}</div>
		@endif
		<div class="tab-content">
			<div id="pref-edit-details" class="tab-pane active" role="tabpanel">
				<div class="mb-3">
					<label class="form-label">{{ $LANG['description'] ?? '' }}
						<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_required_field" title="{{ $LANG['required_field'] ?? '' }}"><i class="ti ti-asterisk text-danger"></i></a>
						<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_description" title="{{ $LANG['description'] ?? '' }}"><i class="ti ti-help"></i></a>
					</label>
					<input type="text" class="form-control" name="pref_description" value="{{ $preference['pref_description'] ?? '' }}" required />
					<div class="invalid-feedback">{{ $LANG['required_field'] ?? 'Required' }}</div>
				</div>
				<div class="row g-3">
					<div class="col-md-6">
						@include('templates.default.partials.currency_sign_field', [
							'currencySignFieldName'        => 'currency_sign_value',
							'currencySignCurrentValue'     => $preference['currency_sign'] ?? '',
							'currencyCodeFieldName'        => 'currency_code',
							'currencyCodeCurrentValue'     => $preference['currency_code'] ?? '',
							'currencyIdFieldName'          => 'currency_id',
							'currencyIdCurrentValue'       => $preference['currency_id'] ?? '',
						])
					</div>
					<div class="col-md-6">
						<div class="mb-3">
							<label class="form-label">{{ $LANG['default_payment_terms'] ?? 'Default payment terms' }}</label>
							<select name="payment_term_id" class="form-select">
								<option value="">{{ $LANG['payment_term_none'] ?? '-' }}</option>
								@foreach(($paymentTerms ?? []) as $pt)
									<option value="{{ $pt['term_id'] ?? '' }}" @if((string)($preference['payment_term_id'] ?? '') === (string)($pt['term_id'] ?? '')) selected @endif>{{ $pt['term_label'] ?? '' }}</option>
								@endforeach
							</select>
						</div>
					</div>
				</div>
				<div class="row g-3">
					<div class="col-md-6">
						<div class="mb-3">
							<label class="form-label">{{ $LANG['status'] ?? '' }}
								<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_status" title="{{ $LANG['status'] ?? '' }}"><i class="ti ti-help"></i></a>
							</label>
							<select name="status" class="form-select">
								@foreach(($status ?? []) as $s)
									<option @if($s['id'] == ($preference['status'] ?? null)) selected @endif value="{{ $s['id'] }}">{{ $s['status'] }}</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="col-md-6">
						<div class="mb-3">
							<label class="form-label">{{ $LANG['enabled'] ?? '' }}
								<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_invoice_enabled" title="{{ $LANG['enabled'] ?? '' }}"><i class="ti ti-help"></i></a>
							</label>
							<select name="pref_enabled" class="form-select">
								<option value="1" @if(($preference['pref_enabled'] ?? '') == '1') selected @endif>{{ $LANG['enabled'] ?? '' }}</option>
								<option value="0" @if(($preference['pref_enabled'] ?? '') == '0') selected @endif>{{ $LANG['disabled'] ?? '' }}</option>
							</select>
						</div>
					</div>
				</div>
			</div>
			<div id="pref-edit-numbering" class="tab-pane" role="tabpanel">
				<div class="mb-3">
					<label class="form-label">{{ $LANG['invoice_numbering_group'] ?? '' }}
						<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_invoice_numbering_group" title="{{ $LANG['invoice_numbering_group'] ?? '' }}"><i class="ti ti-help"></i></a>
					</label>
					@if($preferences == null)
						<p class="text-muted"><em>{{ $LANG['no_preferences'] ?? '' }}</em></p>
					@else
						<select name="index_group" class="form-select">
							@foreach(($preferences ?? []) as $p)
								<option @if(($p['pref_id'] ?? '') == ($preference['index_group'] ?? '')) selected @endif value="{{ $p['pref_id'] ?? '' }}">{{ $p['pref_description'] ?? '' }}</option>
							@endforeach
						</select>
					@endif
				</div>
				<div class="mb-3">
					<label class="form-label">{{ $LANG['next_invoice_number'] ?? 'Next invoice number' }}</label>
					<p class="form-control-plaintext mb-0"><strong id="next_invoice_number_display">{{ $next_invoice_number }}</strong></p>
					<div class="mt-2" id="starting_number_input_wrapper" style="display:none">
						<div class="input-group">
							<span class="input-group-text">Set starting number from</span>
							<input type="number" class="form-control" name="set_starting_invoice_number"
								id="set_starting_invoice_number"
								min="{{ max(($max_existing_index_id ?? 0) + 1, 1) }}"
								placeholder="{{ $next_invoice_number }}">
							<button type="button" class="btn btn-outline-secondary" id="cancel_starting_input">
								<i class="ti ti-x"></i> Cancel
							</button>
						</div>
						<div class="form-text">Next number will be set to this value. Must be greater than <strong id="starting_number_max_ref">{{ $max_existing_index_id ?? 0 }}</strong> (highest existing invoice number in this group).</div>
					</div>
					<button type="button" class="btn btn-outline-secondary btn-sm mt-2" id="show_starting_input">
						<i class="ti ti-pencil me-1"></i>Change starting number
					</button>
				</div>
				<div class="mb-3">
					<label class="form-label">{{ $LANG['invoice_id_prefix'] ?? 'Invoice ID Prefix' }}</label>
					<input type="text" name="pref_invoice_id_prefix" value="{{ $preference['pref_invoice_id_prefix'] ?? '' }}" class="form-control" placeholder="e.g. DGN-" />
					<div class="form-text">Optional prefix prepended to invoice number (e.g. DGN- → DGN-000345).</div>
				</div>
				<div class="mb-3">
					<label class="form-label">{{ $LANG['invoice_id_format'] ?? 'Invoice Number Format' }}</label>
					<input type="text" name="pref_invoice_id_format" value="{{ $preference['pref_invoice_id_format'] ?? '' }}" class="form-control" placeholder="e.g. %06d" />
					<div class="form-text">{{ $LANG['invoice_id_format_help'] ?? 'PHP sprintf format for the numeric part of the invoice ID. Use %06d for 6-digit zero-padded numbers (000345), %08d for 8-digit, etc. Leave empty for no padding.' }}</div>
					<div class="form-text text-warning">After changing the invoice prefix or format, go to <a href="index.php?module=options&amp;view=invoice_denorm">Invoice List Cache</a> and run <strong>Rebuild normalised fields</strong> to update existing invoices.</div>
				</div>
			</div>
			<div id="pref-edit-localization" class="tab-pane" role="tabpanel">
				<div class="row g-3">
					<div class="col-md-6">
						<div class="mb-3">
							<label class="form-label">{{ $LANG['language'] ?? '' }}
								<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_language" title="{{ $LANG['language'] ?? '' }}"><i class="ti ti-help"></i></a>
							</label>
							<select name="language" class="form-select">
								<option value="">{{ $LANG['ui_language_domain_default'] ?? 'Use organisation default' }}</option>
								@foreach(($languageList ?? []) as $lng)
									<option value="{{ $lng->shortname ?? '' }}" @if((string) ($preference['language'] ?? '') === (string) ($lng->shortname ?? '')) selected @endif>{{ $lng->name ?? '' }} ({{ $lng->shortname ?? '' }})</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="col-md-6">
						<div class="mb-3">
							<label class="form-label">{{ $LANG['locale'] ?? '' }}
								<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_locale" title="{{ $LANG['locale'] ?? '' }}"><i class="ti ti-help"></i></a>
							</label>
							<select name="locale" class="form-select">
								@foreach(($localelist ?? []) as $localeCode => $localeLabel)
									<option @if((string) $localeCode === (string) ($preference['locale'] ?? '')) selected @endif value="{{ $localeCode }}">{{ $localeLabel }}</option>
								@endforeach
							</select>
						</div>
					</div>
				</div>
			</div>
			<div id="pref-edit-wording" class="tab-pane" role="tabpanel">
				<div class="row g-3">
					<div class="col-md-6">
						<div class="mb-3">
							<label class="form-label">{{ $LANG['invoice_heading'] ?? '' }}
								<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_invoice_heading" title="{{ $LANG['invoice_heading'] ?? '' }}"><i class="ti ti-help"></i></a>
							</label>
							<input type="text" name="pref_inv_heading" value="{{ $preference['pref_inv_heading'] ?? '' }}" class="form-control" />
						</div>
					</div>
					<div class="col-md-6">
						<div class="mb-3">
							<label class="form-label">{{ $LANG['invoice_wording'] ?? '' }}
								<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_invoice_wording" title="{{ $LANG['invoice_wording'] ?? '' }}"><i class="ti ti-help"></i></a>
							</label>
							<input type="text" name="pref_inv_wording" value="{{ $preference['pref_inv_wording'] ?? '' }}" class="form-control" />
						</div>
					</div>
				</div>
				<div class="row g-3">
					<div class="col-md-6">
						<div class="mb-3">
							<label class="form-label">{{ $LANG['invoice_detail_heading'] ?? '' }}
								<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_invoice_detail_heading" title="{{ $LANG['invoice_detail_heading'] ?? '' }}"><i class="ti ti-help"></i></a>
							</label>
							<input type="text" name="pref_inv_detail_heading" value="{{ $preference['pref_inv_detail_heading'] ?? '' }}" class="form-control" />
						</div>
					</div>
					<div class="col-md-6">
						<div class="mb-3">
							<label class="form-label">{{ $LANG['invoice_detail_line'] ?? '' }}
								<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_invoice_detail_line" title="{{ $LANG['invoice_detail_line'] ?? '' }}"><i class="ti ti-help"></i></a>
							</label>
							<input type="text" name="pref_inv_detail_line" value="{{ $preference['pref_inv_detail_line'] ?? '' }}" class="form-control" />
						</div>
					</div>
				</div>
			</div>
			<div id="pref-edit-payment" class="tab-pane" role="tabpanel">
				<p class="text-muted small mb-3">{{ $LANG['payment_token_hint'] ?? 'Tokens are replaced with live values when the invoice is rendered. Biller bank tokens: {biller.bank_name}, {biller.bank_account_number}, {biller.bank_swift_bic}, {biller.bank_routing_sort_code}, {biller.bank_account_name}. Language labels: {lang.bank_name}, {lang.account_number}, {lang.swift_bic}, {lang.invoice_reference}, {lang.details}, {lang.payment_terms}, {lang.account_name}, {lang.electronic_funds_transfer}. Other tokens: {biller.name}, {biller.email}, {biller.phone}, {customer.name}, {invoice.total}, {invoice.owing}, {invoice.number}.' }}</p>
				<div class="mb-3">
					<label class="form-label">{{ $LANG['include_online_payment'] ?? '' }}
						<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_invoice_detail_line" title="{{ $LANG['invoice_detail_line'] ?? '' }}"><i class="ti ti-help"></i></a>
					</label>
					<div>
						<div class="form-check form-check-inline">
							<input type="checkbox" class="form-check-input" name="include_online_payment[]" value="stripe" id="pref_edit_iop_stripe" @if(in_array("stripe", explode(",", $preference['include_online_payment'] ?? ''))) checked @endif />
							<label class="form-check-label" for="pref_edit_iop_stripe">Stripe</label>
						</div>
						<div class="form-check form-check-inline">
							<input type="checkbox" class="form-check-input" name="include_online_payment[]" value="paypal_commerce" id="pref_edit_iop_paypal_commerce" @if(in_array("paypal_commerce", explode(",", $preference['include_online_payment'] ?? ''))) checked @endif />
							<label class="form-check-label" for="pref_edit_iop_paypal_commerce">PayPal Commerce</label>
						</div>
						<div class="form-check form-check-inline">
							<input type="checkbox" class="form-check-input" name="include_online_payment[]" value="mollie" id="pref_edit_iop_mollie" @if(in_array("mollie", explode(",", $preference['include_online_payment'] ?? ''))) checked @endif />
							<label class="form-check-label" for="pref_edit_iop_mollie">Mollie</label>
						</div>
						<div class="form-check form-check-inline">
							<input type="checkbox" class="form-check-input" name="include_online_payment[]" value="authorizenet" id="pref_edit_iop_authorizenet" @if(in_array("authorizenet", explode(",", $preference['include_online_payment'] ?? ''))) checked @endif />
							<label class="form-check-label" for="pref_edit_iop_authorizenet">Authorize.net</label>
						</div>
						<div class="form-check form-check-inline">
							<input type="checkbox" class="form-check-input" name="include_online_payment[]" value="eway_rapid" id="pref_edit_iop_eway_rapid" @if(in_array("eway_rapid", explode(",", $preference['include_online_payment'] ?? ''))) checked @endif />
							<label class="form-check-label" for="pref_edit_iop_eway_rapid">{{ $LANG['eway_rapid'] ?? 'eWay Rapid' }}</label>
						</div>
						<div class="form-check form-check-inline">
							<input type="checkbox" class="form-check-input" name="include_online_payment[]" value="paymentsgateway_modern" id="pref_edit_iop_pg_modern" @if(in_array("paymentsgateway_modern", explode(",", $preference['include_online_payment'] ?? ''))) checked @endif />
							<label class="form-check-label" for="pref_edit_iop_pg_modern">{{ $LANG['paymentsgateway_modern'] ?? 'Payments Gateway' }}</label>
						</div>
						<div class="form-check form-check-inline">
							<input type="checkbox" class="form-check-input" name="include_online_payment[]" value="kofi" id="pref_edit_iop_kofi" @if(in_array("kofi", explode(",", $preference['include_online_payment'] ?? ''))) checked @endif />
							<label class="form-check-label" for="pref_edit_iop_kofi">Ko-fi</label>
						</div>
						<div class="form-check form-check-inline">
							<input type="checkbox" class="form-check-input" name="include_online_payment[]" value="coinbase" id="pref_edit_iop_coinbase" @if(in_array("coinbase", explode(",", $preference['include_online_payment'] ?? ''))) checked @endif />
							<label class="form-check-label" for="pref_edit_iop_coinbase">{{ $LANG['coinbase_commerce'] ?? 'Coinbase Commerce' }}</label>
						</div>
						<div class="form-check form-check-inline">
							<input type="checkbox" class="form-check-input" name="include_online_payment[]" value="adyen" id="pref_edit_iop_adyen" @if(in_array("adyen", explode(",", $preference['include_online_payment'] ?? ''))) checked @endif />
							<label class="form-check-label" for="pref_edit_iop_adyen">Adyen</label>
						</div>
					</div>
				</div>
				<div class="mb-3">
					<label class="form-label">{{ $LANG['invoice_payment_method'] ?? '' }}
						<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_invoice_payment_method" title="{{ $LANG['invoice_payment_method'] ?? '' }}"><i class="ti ti-help"></i></a>
					</label>
					<input type="text" name="pref_inv_payment_method" value="{{ $preference['pref_inv_payment_method'] ?? '' }}" class="form-control" />
				</div>
				<div class="row g-3">
					<div class="col-md-6">
						<div class="mb-3">
							<label class="form-label">{{ $LANG['invoice_payment_line_0_name'] ?? '' }}</label>
							<input type="text" name="pref_inv_payment_line0_name" value="{{ $preference['pref_inv_payment_line0_name'] ?? '' }}" class="form-control" placeholder="{lang.bank_name}" />
						</div>
					</div>
					<div class="col-md-6">
						<div class="mb-3">
							<label class="form-label">{{ $LANG['invoice_payment_line_0_value'] ?? '' }}</label>
							<input type="text" name="pref_inv_payment_line0_value" value="{{ $preference['pref_inv_payment_line0_value'] ?? '' }}" class="form-control" placeholder="{biller.bank_name}" />
						</div>
					</div>
				</div>
				<div class="row g-3">
					<div class="col-md-6">
						<div class="mb-3">
							<label class="form-label">{{ $LANG['invoice_payment_line_1_name'] ?? '' }}
								<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_payment_line1_name" title="{{ $LANG['invoice_payment_line_1_name'] ?? '' }}"><i class="ti ti-help"></i></a>
							</label>
							<input type="text" name="pref_inv_payment_line1_name" value="{{ $preference['pref_inv_payment_line1_name'] ?? '' }}" class="form-control" />
						</div>
					</div>
					<div class="col-md-6">
						<div class="mb-3">
							<label class="form-label">{{ $LANG['invoice_payment_line_1_value'] ?? '' }}
								<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_payment_line1_value" title="{{ $LANG['invoice_payment_line_1_value'] ?? '' }}"><i class="ti ti-help"></i></a>
							</label>
							<input type="text" name="pref_inv_payment_line1_value" value="{{ $preference['pref_inv_payment_line1_value'] ?? '' }}" class="form-control" />
						</div>
					</div>
				</div>
				<div class="row g-3">
					<div class="col-md-6">
						<div class="mb-3">
							<label class="form-label">{{ $LANG['invoice_payment_line_2_name'] ?? '' }}
								<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_payment_line2_name" title="{{ $LANG['invoice_payment_line_2_name'] ?? '' }}"><i class="ti ti-help"></i></a>
							</label>
							<input type="text" name="pref_inv_payment_line2_name" value="{{ $preference['pref_inv_payment_line2_name'] ?? '' }}" class="form-control" />
						</div>
					</div>
					<div class="col-md-6">
						<div class="mb-3">
							<label class="form-label">{{ $LANG['invoice_payment_line_2_value'] ?? '' }}
								<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_payment_line2_value" title="{{ $LANG['invoice_payment_line_2_value'] ?? '' }}"><i class="ti ti-help"></i></a>
							</label>
							<input type="text" name="pref_inv_payment_line2_value" value="{{ $preference['pref_inv_payment_line2_value'] ?? '' }}" class="form-control" />
						</div>
					</div>
				</div>
				<div class="row g-3">
					<div class="col-md-6">
						<div class="mb-3">
							<label class="form-label">{{ $LANG['invoice_payment_line_3_name'] ?? '' }}</label>
							<input type="text" name="pref_inv_payment_line3_name" value="{{ $preference['pref_inv_payment_line3_name'] ?? '' }}" class="form-control" placeholder="{lang.account_number}" />
						</div>
					</div>
					<div class="col-md-6">
						<div class="mb-3">
							<label class="form-label">{{ $LANG['invoice_payment_line_3_value'] ?? '' }}</label>
							<input type="text" name="pref_inv_payment_line3_value" value="{{ $preference['pref_inv_payment_line3_value'] ?? '' }}" class="form-control" placeholder="{biller.bank_account_number}" />
						</div>
					</div>
				</div>
				<div class="row g-3">
					<div class="col-md-6">
						<div class="mb-3">
							<label class="form-label">{{ $LANG['invoice_payment_line_4_name'] ?? '' }}</label>
							<input type="text" name="pref_inv_payment_line4_name" value="{{ $preference['pref_inv_payment_line4_name'] ?? '' }}" class="form-control" placeholder="{lang.swift_bic}" />
						</div>
					</div>
					<div class="col-md-6">
						<div class="mb-3">
							<label class="form-label">{{ $LANG['invoice_payment_line_4_value'] ?? '' }}</label>
							<input type="text" name="pref_inv_payment_line4_value" value="{{ $preference['pref_inv_payment_line4_value'] ?? '' }}" class="form-control" placeholder="{biller.bank_swift_bic}" />
						</div>
					</div>
				</div>
				<div class="row g-3">
					<div class="col-md-6">
						<div class="mb-3">
							<label class="form-label">{{ $LANG['invoice_payment_line_5_name'] ?? '' }}</label>
							<input type="text" name="pref_inv_payment_line5_name" value="{{ $preference['pref_inv_payment_line5_name'] ?? '' }}" class="form-control" placeholder="{lang.invoice_reference}" />
						</div>
					</div>
					<div class="col-md-6">
						<div class="mb-3">
							<label class="form-label">{{ $LANG['invoice_payment_line_5_value'] ?? '' }}</label>
							<input type="text" name="pref_inv_payment_line5_value" value="{{ $preference['pref_inv_payment_line5_value'] ?? '' }}" class="form-control" placeholder="{{ ($preference['pref_inv_wording'] ?? $LANG['invoice'] ?? 'Invoice') }} #{invoice.number}" />
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="px-3 pb-3 border-top pt-3">
		<a class="cluetip btn btn-outline-secondary" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_what_the" title="{{ $LANG['whats_all_this_inv_pref'] ?? '' }}"><i class="ti ti-help me-1"></i>{{ $LANG['whats_all_this_inv_pref'] ?? '' }}</a>
	</div>
	<div class="card-footer">
		<div class="d-flex">
			<a href="./index.php?module=preferences&amp;view=manage" class="btn btn-link">{{ $LANG['cancel'] ?? '' }}</a>
			<button type="submit" class="btn btn-primary ms-auto" name="save_preference" value="{{ $LANG['save'] ?? '' }}"><i class="ti ti-check me-1"></i>{{ $LANG['save'] ?? '' }}</button>
		</div>
	</div>
</div>


<input type="hidden" name="op" value="edit_preference" />
@endif

<script>
(function() {
'use strict';
var indexNextMap = {!! $index_next_map ?? '{}' !!};
var displayEl = document.getElementById('next_invoice_number_display');
var inputWrapper = document.getElementById('starting_number_input_wrapper');
var showBtn = document.getElementById('show_starting_input');
var cancelBtn = document.getElementById('cancel_starting_input');
var inputEl = document.getElementById('set_starting_invoice_number');
var maxRefEl = document.getElementById('starting_number_max_ref');
var indexGroupSel = document.querySelector('select[name="index_group"]');

function updateDisplay(ngid) {
	var gid = parseInt(ngid, 10) || 0;
	if (gid > 0 && indexNextMap[gid] !== undefined) {
		displayEl.textContent = indexNextMap[gid];
		inputEl.min = Math.max(1, (indexNextMap[gid]));
		return;
	}
	if (gid <= 0) return;
	fetch('./index.php?module=preferences&view=index_lookup_ajax&index_group=' + encodeURIComponent(gid))
		.then(function(r) { return r.json(); })
		.then(function(data) {
			indexNextMap[gid] = data.next;
			displayEl.textContent = data.next;
			inputEl.min = Math.max((data.max_existing || 0) + 1, 1);
			inputEl.placeholder = data.next;
			if (maxRefEl) maxRefEl.textContent = data.max_existing || 0;
		})
		.catch(function() {});
}

if (indexGroupSel) {
	indexGroupSel.addEventListener('change', function() {
		updateDisplay(this.value);
	});
}

if (showBtn) {
	showBtn.addEventListener('click', function() {
		this.style.display = 'none';
		inputWrapper.style.display = 'block';
		inputEl.focus();
		if (!inputEl.value) inputEl.value = displayEl.textContent;
	});
}

if (cancelBtn) {
	cancelBtn.addEventListener('click', function() {
		inputWrapper.style.display = 'none';
		showBtn.style.display = '';
		inputEl.value = '';
	});
}
})();
</script>
</form>
