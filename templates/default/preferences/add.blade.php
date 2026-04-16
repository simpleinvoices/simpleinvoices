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
						@foreach(($localelist ?? []) as $locale => $value)
							<option @if($locale == $config->local->locale) selected @endif value="{{ $locale ?? '' }}">{{ $locale ?? '' }}</option>
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
				<div class="mb-3">
					<label class="form-label">{{ $LANG['currency_sign'] ?? '' }}
						<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_currency_sign" title="{{ $LANG['currency_sign'] ?? '' }}"><i class="ti ti-help"></i></a>
					</label>
					<input type="text" name="p_currency_sign" value="{{ post('p_currency_sign') }}" class="form-control" />
					<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_currency_sign" title="{{ $LANG['currency_sign'] ?? '' }}">{{ $LANG['currency_sign_non_dollar'] ?? '' }} <i class="ti ti-help"></i></a>
				</div>
				<div class="mb-3">
					<label class="form-label">{{ $LANG['currency_code'] ?? '' }}
						<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_currency_code" title="{{ $LANG['currency_code'] ?? '' }}"><i class="ti ti-help"></i></a>
					</label>
					<input type="text" name="currency_code" value="{{ post('currency_code') }}" class="form-control" />
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
				<div class="mb-3">
					<label class="form-label">{{ $LANG['include_online_payment'] ?? '' }}
						<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_invoice_detail_line" title="{{ $LANG['invoice_detail_line'] ?? '' }}"><i class="ti ti-help"></i></a>
					</label>
					<div>
						<div class="form-check form-check-inline">
							<input type="checkbox" class="form-check-input" name="include_online_payment[]" value='paypal' id="iop_paypal" />
							<label class="form-check-label" for="iop_paypal">{{ $LANG['paypal'] ?? '' }}</label>
						</div>
						<div class="form-check form-check-inline">
							<input type="checkbox" class="form-check-input" name="include_online_payment[]" value='eway_merchant_xml' id="iop_eway" />
							<label class="form-check-label" for="iop_eway">{{ $LANG['eway_merchant_xml'] ?? '' }}</label>
						</div>
						<div class="form-check form-check-inline">
							<input type="checkbox" class="form-check-input" name="include_online_payment[]" value='paymentsgateway' id="iop_pg" />
							<label class="form-check-label" for="iop_pg">{{ $LANG['paymentsgateway'] ?? '' }}</label>
						</div>
					</div>
				</div>
				<div class="mb-3">
					<label class="form-label">{{ $LANG['invoice_payment_method'] ?? '' }}
						<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_invoice_payment_method" title="{{ $LANG['invoice_payment_method'] ?? '' }}"><i class="ti ti-help"></i></a>
					</label>
					<input type="text" name="p_inv_payment_method" value="{{ post('p_inv_payment_method') }}" class="form-control" />
				</div>
				<div class="mb-3">
					<label class="form-label">{{ $LANG['invoice_payment_line_1_name'] ?? '' }}
						<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_payment_line1_name" title="{{ $LANG['invoice_payment_line_1_name'] ?? '' }}"><i class="ti ti-help"></i></a>
					</label>
					<input type="text" name="p_inv_payment_line1_name" value="{{ post('p_inv_payment_line1_name') }}" class="form-control" />
				</div>
				<div class="mb-3">
					<label class="form-label">{{ $LANG['invoice_payment_line_1_value'] ?? '' }}
						<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_payment_line1_value" title="{{ $LANG['invoice_payment_line_1_value'] ?? '' }}"><i class="ti ti-help"></i></a>
					</label>
					<input type="text" name="p_inv_payment_line1_value" value="{{ post('p_inv_payment_line1_value') }}" class="form-control" />
				</div>
				<div class="mb-3">
					<label class="form-label">{{ $LANG['invoice_payment_line_2_name'] ?? '' }}
						<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_payment_line2_name" title="{{ $LANG['invoice_payment_line_2_name'] ?? '' }}"><i class="ti ti-help"></i></a>
					</label>
					<input type="text" name="p_inv_payment_line2_name" value="{{ post('p_inv_payment_line2_name') }}" class="form-control" />
				</div>
				<div class="mb-3">
					<label class="form-label">{{ $LANG['invoice_payment_line_2_value'] ?? '' }}
						<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_payment_line2_value" title="{{ $LANG['invoice_payment_line_2_value'] ?? '' }}"><i class="ti ti-help"></i></a>
					</label>
					<input type="text" name="p_inv_payment_line2_value" value="{{ post('p_inv_payment_line2_value') }}" class="form-control" />
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
