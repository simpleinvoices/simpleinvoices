<!--Modified code to display apostrophes in text box output 05/02/2008-Gates-->
<form name="frmpost" action="index.php?module=preferences&amp;view=save&amp;id={{ get('id') }}" method="post">


@if(get('action')== 'view' )

<div class="card">
	<div class="card-header">
		<ul class="nav nav-tabs card-header-tabs" role="tablist">
			<li class="nav-item" role="presentation">
				<a class="nav-link active" href="#pref-view-details" data-bs-toggle="tab" role="tab">{{ $LANG['details'] ?? '' }}</a>
			</li>
			<li class="nav-item" role="presentation">
				<a class="nav-link" href="#pref-view-currency" data-bs-toggle="tab" role="tab">{{ $LANG['currency_sign'] ?? 'Currency' }}</a>
			</li>
			<li class="nav-item" role="presentation">
				<a class="nav-link" href="#pref-view-wording" data-bs-toggle="tab" role="tab">{{ $LANG['invoice_heading'] ?? 'Invoice Wording' }}</a>
			</li>
			<li class="nav-item" role="presentation">
				<a class="nav-link" href="#pref-view-payment" data-bs-toggle="tab" role="tab">{{ $LANG['payment'] ?? 'Payment' }}</a>
			</li>
		</ul>
		<div class="card-actions">
			<a href="./index.php?module=preferences&amp;view=details&amp;id={{ $preference['pref_id'] }}&amp;action=edit" class="btn btn-primary"><i class="ti ti-edit me-1"></i>{{ $LANG['edit'] ?? '' }}</a>
			<a href="./index.php?module=preferences&amp;view=manage" class="btn btn-outline-secondary"><i class="ti ti-x me-1"></i>{{ $LANG['cancel'] ?? '' }}</a>
		</div>
	</div>
	<div class="card-body">
		<div class="tab-content">
			<div id="pref-view-details" class="tab-pane active" role="tabpanel">
				<table class="table table-vcenter">
		<tr>
			<th>{{ $LANG['description'] ?? '' }} 
				<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_description" title="{{ $LANG['description'] ?? '' }}">
				<i class="ti ti-help"></i> </a>
			</th>
			<td>{{ $preference['pref_description'] }}</td>
		</tr>
		<tr><th>{{ $LANG['enabled'] ?? '' }} <a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_invoice_enabled" title="{{ $LANG['enabled'] ?? '' }}"><i class="ti ti-help"></i></a></th><td>{{ $preference['enabled'] }}</td></tr>
		<tr><th>{{ $LANG['status'] ?? '' }} <a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_status" title="{{ $LANG['status'] ?? '' }}"><i class="ti ti-help"></i></a></th><td>{{ $preference['status_wording'] }}</td></tr>
		<tr><th>{{ $LANG['invoice_numbering_group'] ?? '' }} <a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_invoice_numbering_group" title="{{ $LANG['invoice_numbering_group'] ?? '' }}"><i class="ti ti-help"></i></a></th><td>{{ $index_group['pref_description'] ?? '' }}</td></tr>
		<tr><th>{{ $LANG['language'] ?? '' }} <a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_language" title="{{ $LANG['language'] ?? '' }}"><i class="ti ti-help"></i></a></th><td>{{ $preference['language'] }}</td></tr>
		<tr><th>{{ $LANG['locale'] ?? '' }} <a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_locale" title="{{ $LANG['locale'] ?? '' }}"><i class="ti ti-help"></i></a></th><td>{{ $preference['locale'] }}</td></tr>
				</table>
			</div>
			<div id="pref-view-currency" class="tab-pane" role="tabpanel">
				<table class="table table-vcenter">
					<tr><th>{{ $LANG['currency_sign'] ?? '' }} <a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_currency_sign" title="{{ $LANG['currency_sign'] ?? '' }}"><i class="ti ti-help"></i></a></th><td>{{ $preference['pref_currency_sign'] }}</td></tr>
					<tr><th>{{ $LANG['currency_code'] ?? '' }} <a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_currency_code" title="{{ $LANG['currency_code'] ?? '' }}"><i class="ti ti-help"></i></a></th><td>{{ $preference['currency_code'] ?? '' }}</td></tr>
				</table>
			</div>
			<div id="pref-view-wording" class="tab-pane" role="tabpanel">
				<table class="table table-vcenter">
					<tr><th>{{ $LANG['invoice_heading'] ?? '' }} <a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_invoice_heading" title="{{ $LANG['invoice_heading'] ?? '' }}"><i class="ti ti-help"></i></a></th><td>{{ $preference['pref_inv_heading'] ?? '' }}</td></tr>
					<tr><th>{{ $LANG['invoice_wording'] ?? '' }} <a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_invoice_wording" title="{{ $LANG['invoice_wording'] ?? '' }}"><i class="ti ti-help"></i></a></th><td>{{ $preference['pref_inv_wording'] ?? '' }}</td></tr>
					<tr><th>{{ $LANG['invoice_detail_heading'] ?? '' }} <a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_invoice_detail_heading" title="{{ $LANG['invoice_detail_heading'] ?? '' }}"><i class="ti ti-help"></i></a></th><td>{{ $preference['pref_inv_detail_heading'] ?? '' }}</td></tr>
				</table>
			</div>
			<div id="pref-view-payment" class="tab-pane" role="tabpanel">
				<table class="table table-vcenter">
					<tr>
						<th>{{ $LANG['include_online_payment'] ?? '' }} <a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_invoice_detail_line" title="{{ $LANG['invoice_detail_line'] ?? '' }}"><i class="ti ti-help"></i></a></th>
						<td>
							<div class="form-check form-check-inline"><input type="checkbox" class="form-check-input" disabled @if(in_array("paypal", explode(",", $preference['include_online_payment'] ?? ''))) checked @endif /><label class="form-check-label">{{ $LANG['paypal'] ?? '' }}</label></div>
							<div class="form-check form-check-inline"><input type="checkbox" class="form-check-input" disabled @if(in_array("eway_merchant_xml", explode(",", $preference['include_online_payment'] ?? ''))) checked @endif /><label class="form-check-label">{{ $LANG['eway_merchant_xml'] ?? '' }}</label></div>
							<div class="form-check form-check-inline"><input type="checkbox" class="form-check-input" disabled @if(in_array("paymentsgateway", explode(",", $preference['include_online_payment'] ?? ''))) checked @endif /><label class="form-check-label">{{ $LANG['paymentsgateway'] ?? '' }}</label></div>
						</td>
					</tr>
					<tr><th>{{ $LANG['invoice_payment_method'] ?? '' }} <a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_invoice_payment_method" title="{{ $LANG['invoice_payment_method'] ?? '' }}"><i class="ti ti-help"></i></a></th><td>{{ $preference['pref_inv_payment_method'] ?? '' }}</td></tr>
					<tr><th>{{ $LANG['invoice_payment_line_1_name'] ?? '' }} <a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_payment_line1_name" title="{{ $LANG['invoice_payment_line_1_name'] ?? '' }}"><i class="ti ti-help"></i></a></th><td>{{ $preference['pref_inv_payment_line1_name'] ?? '' }}</td></tr>
					<tr><th>{{ $LANG['invoice_payment_line_1_value'] ?? '' }} <a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_payment_line1_value" title="{{ $LANG['invoice_payment_line_1_value'] ?? '' }}"><i class="ti ti-help"></i></a></th><td>{{ $preference['pref_inv_payment_line1_value'] ?? '' }}</td></tr>
					<tr><th>{{ $LANG['invoice_payment_line_2_name'] ?? '' }} <a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_payment_line2_name" title="{{ $LANG['invoice_payment_line_2_name'] ?? '' }}"><i class="ti ti-help"></i></a></th><td>{{ $preference['pref_inv_payment_line2_name'] ?? '' }}</td></tr>
					<tr><th>{{ $LANG['invoice_payment_line_2_value'] ?? '' }} <a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_payment_line2_value" title="{{ $LANG['invoice_payment_line_2_value'] ?? '' }}"><i class="ti ti-help"></i></a></th><td>{{ $preference['pref_inv_payment_line2_value'] ?? '' }}</td></tr>
				</table>
			</div>
		</div>
	</div>

	<div class="card-footer">
			<a class="cluetip btn btn-outline-secondary btn-sm" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_what_the" title="{{ $LANG['whats_all_this_inv_pref'] ?? '' }}"><i class="ti ti-help me-1"></i>{{ $LANG['whats_all_this_inv_pref'] ?? '' }}</a>
	</div>
</div>
@endif





@if(get('action')== 'edit' )

<div class="card">
	<div class="card-header">
		<ul class="nav nav-tabs card-header-tabs" role="tablist">
			<li class="nav-item" role="presentation">
				<a class="nav-link active" href="#pref-edit-details" data-bs-toggle="tab" role="tab">{{ $LANG['details'] ?? '' }}</a>
			</li>
			<li class="nav-item" role="presentation">
				<a class="nav-link" href="#pref-edit-currency" data-bs-toggle="tab" role="tab">{{ $LANG['currency_sign'] ?? 'Currency' }}</a>
			</li>
			<li class="nav-item" role="presentation">
				<a class="nav-link" href="#pref-edit-wording" data-bs-toggle="tab" role="tab">{{ $LANG['invoice_heading'] ?? 'Invoice Wording' }}</a>
			</li>
			<li class="nav-item" role="presentation">
				<a class="nav-link" href="#pref-edit-payment" data-bs-toggle="tab" role="tab">{{ $LANG['payment'] ?? 'Payment' }}</a>
			</li>
		</ul>
	</div>
	<div class="card-body">
		<div class="tab-content">
			<div id="pref-edit-details" class="tab-pane active" role="tabpanel">
				<div class="mb-3">
					<label class="form-label">{{ $LANG['description'] ?? '' }}
						<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_required_field" title="{{ $LANG['required_field'] ?? '' }}"><i class="ti ti-asterisk text-danger"></i></a>
						<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_description" title="{{ $LANG['description'] ?? '' }}"><i class="ti ti-help"></i></a>
					</label>
					<input type="text" class="form-control validate[required]" name="pref_description" value="{{ $preference['pref_description'] ?? '' }}" />
				</div>
				<div class="mb-3">
					<label class="form-label">{{ $LANG['status'] ?? '' }} <a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_status" title="{{ $LANG['status'] ?? '' }}"><i class="ti ti-help"></i></a></label>
					<select name="status" class="form-select">
						@foreach(($status ?? []) as $s)
							<option @if($s['id'] == ($preference['status'] ?? null)) selected @endif value="{{ $s['id'] }}">{{ $s['status'] }}</option>
						@endforeach
					</select>
				</div>
				<div class="mb-3">
					<label class="form-label">{{ $LANG['invoice_numbering_group'] ?? '' }} <a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_invoice_numbering_group" title="{{ $LANG['invoice_numbering_group'] ?? '' }}"><i class="ti ti-help"></i></a></label>
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
					<label class="form-label">{{ $LANG['enabled'] ?? '' }} <a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_invoice_enabled" title="{{ $LANG['enabled'] ?? '' }}"><i class="ti ti-help"></i></a></label>
					<select name="pref_enabled" class="form-select">
						<option value="1" @if(($preference['pref_enabled'] ?? '') == '1') selected @endif>{{ $LANG['enabled'] ?? '' }}</option>
						<option value="0" @if(($preference['pref_enabled'] ?? '') == '0') selected @endif>{{ $LANG['disabled'] ?? '' }}</option>
					</select>
				</div>
				<div class="mb-3">
					<label class="form-label">{{ $LANG['language'] ?? '' }} <a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_language" title="{{ $LANG['language'] ?? '' }}"><i class="ti ti-help"></i></a></label>
					<select name="language" class="form-select">
						@foreach(($localelist ?? []) as $language => $value)
							<option @if($language == ($preference['language'] ?? '')) selected @endif value="{{ $language ?? '' }}">{{ $language ?? '' }}</option>
						@endforeach
					</select>
				</div>
				<div class="mb-3">
					<label class="form-label">{{ $LANG['locale'] ?? '' }} <a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_locale" title="{{ $LANG['locale'] ?? '' }}"><i class="ti ti-help"></i></a></label>
					<select name="locale" class="form-select">
						@foreach(($localelist ?? []) as $locale => $value)
							<option @if($locale == ($preference['locale'] ?? '')) selected @endif value="{{ $locale ?? '' }}">{{ $locale ?? '' }}</option>
						@endforeach
					</select>
				</div>
			</div>
			<div id="pref-edit-currency" class="tab-pane" role="tabpanel">
				<div class="mb-3">
					<label class="form-label">{{ $LANG['currency_sign'] ?? '' }} <a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_currency_sign" title="{{ $LANG['currency_sign'] ?? '' }}"><i class="ti ti-help"></i></a></label>
					<input type="text" name="pref_currency_sign" value="{{ $preference['pref_currency_sign'] ?? '' }}" class="form-control" />
					<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_currency_sign" title="{{ $LANG['currency_sign'] ?? '' }}">{{ $LANG['currency_sign_non_dollar'] ?? '' }} <i class="ti ti-help"></i></a>
				</div>
				<div class="mb-3">
					<label class="form-label">{{ $LANG['currency_code'] ?? '' }} <a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_currency_code" title="{{ $LANG['currency_code'] ?? '' }}"><i class="ti ti-help"></i></a></label>
					<input type="text" name="currency_code" value="{{ $preference['currency_code'] ?? '' }}" class="form-control" />
				</div>
			</div>
			<div id="pref-edit-wording" class="tab-pane" role="tabpanel">
				<div class="mb-3">
					<label class="form-label">{{ $LANG['invoice_heading'] ?? '' }} <a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_invoice_heading" title="{{ $LANG['invoice_heading'] ?? '' }}"><i class="ti ti-help"></i></a></label>
					<input type="text" name="pref_inv_heading" value="{{ $preference['pref_inv_heading'] ?? '' }}" class="form-control" />
				</div>
				<div class="mb-3">
					<label class="form-label">{{ $LANG['invoice_wording'] ?? '' }} <a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_invoice_wording" title="{{ $LANG['invoice_wording'] ?? '' }}"><i class="ti ti-help"></i></a></label>
					<input type="text" name="pref_inv_wording" value="{{ $preference['pref_inv_wording'] ?? '' }}" class="form-control" />
				</div>
				<div class="mb-3">
					<label class="form-label">{{ $LANG['invoice_detail_heading'] ?? '' }} <a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_invoice_detail_heading" title="{{ $LANG['invoice_detail_heading'] ?? '' }}"><i class="ti ti-help"></i></a></label>
					<input type="text" name="pref_inv_detail_heading" value="{{ $preference['pref_inv_detail_heading'] ?? '' }}" class="form-control" />
				</div>
				<div class="mb-3">
					<label class="form-label">{{ $LANG['invoice_detail_line'] ?? '' }} <a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_invoice_detail_line" title="{{ $LANG['invoice_detail_line'] ?? '' }}"><i class="ti ti-help"></i></a></label>
					<input type="text" name="pref_inv_detail_line" value="{{ $preference['pref_inv_detail_line'] ?? '' }}" class="form-control" />
				</div>
			</div>
			<div id="pref-edit-payment" class="tab-pane" role="tabpanel">
				<div class="mb-3">
					<label class="form-label">{{ $LANG['include_online_payment'] ?? '' }} <a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_invoice_detail_line" title="{{ $LANG['invoice_detail_line'] ?? '' }}"><i class="ti ti-help"></i></a></label>
					<div>
						<div class="form-check form-check-inline">
							<input type="checkbox" class="form-check-input" name="include_online_payment[]" value="paypal" id="pref_edit_iop_paypal" @if(in_array("paypal", explode(",", $preference['include_online_payment'] ?? ''))) checked @endif />
							<label class="form-check-label" for="pref_edit_iop_paypal">{{ $LANG['paypal'] ?? '' }}</label>
						</div>
						<div class="form-check form-check-inline">
							<input type="checkbox" class="form-check-input" name="include_online_payment[]" value="eway_merchant_xml" id="pref_edit_iop_eway" @if(in_array("eway_merchant_xml", explode(",", $preference['include_online_payment'] ?? ''))) checked @endif />
							<label class="form-check-label" for="pref_edit_iop_eway">{{ $LANG['eway_merchant_xml'] ?? '' }}</label>
						</div>
						<div class="form-check form-check-inline">
							<input type="checkbox" class="form-check-input" name="include_online_payment[]" value="paymentsgateway" id="pref_edit_iop_pg" @if(in_array("paymentsgateway", explode(",", $preference['include_online_payment'] ?? ''))) checked @endif />
							<label class="form-check-label" for="pref_edit_iop_pg">{{ $LANG['paymentsgateway'] ?? '' }}</label>
						</div>
					</div>
				</div>
				<div class="mb-3">
					<label class="form-label">{{ $LANG['invoice_payment_method'] ?? '' }} <a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_invoice_payment_method" title="{{ $LANG['invoice_payment_method'] ?? '' }}"><i class="ti ti-help"></i></a></label>
					<input type="text" name="pref_inv_payment_method" value="{{ $preference['pref_inv_payment_method'] ?? '' }}" class="form-control" />
				</div>
				<div class="mb-3">
					<label class="form-label">{{ $LANG['invoice_payment_line_1_name'] ?? '' }} <a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_payment_line1_name" title="{{ $LANG['invoice_payment_line_1_name'] ?? '' }}"><i class="ti ti-help"></i></a></label>
					<input type="text" name="pref_inv_payment_line1_name" value="{{ $preference['pref_inv_payment_line1_name'] ?? '' }}" class="form-control" />
				</div>
				<div class="mb-3">
					<label class="form-label">{{ $LANG['invoice_payment_line_1_value'] ?? '' }} <a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_payment_line1_value" title="{{ $LANG['invoice_payment_line_1_value'] ?? '' }}"><i class="ti ti-help"></i></a></label>
					<input type="text" name="pref_inv_payment_line1_value" value="{{ $preference['pref_inv_payment_line1_value'] ?? '' }}" class="form-control" />
				</div>
				<div class="mb-3">
					<label class="form-label">{{ $LANG['invoice_payment_line_2_name'] ?? '' }} <a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_payment_line2_name" title="{{ $LANG['invoice_payment_line_2_name'] ?? '' }}"><i class="ti ti-help"></i></a></label>
					<input type="text" name="pref_inv_payment_line2_name" value="{{ $preference['pref_inv_payment_line2_name'] ?? '' }}" class="form-control" />
				</div>
				<div class="mb-3">
					<label class="form-label">{{ $LANG['invoice_payment_line_2_value'] ?? '' }} <a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_payment_line2_value" title="{{ $LANG['invoice_payment_line_2_value'] ?? '' }}"><i class="ti ti-help"></i></a></label>
					<input type="text" name="pref_inv_payment_line2_value" value="{{ $preference['pref_inv_payment_line2_value'] ?? '' }}" class="form-control" />
				</div>
			</div>
		</div>
	</div>
	<div class="card-footer text-end">
		<button type="submit" class="btn btn-primary" name="save_preference" value="{{ $LANG['save'] ?? '' }}"><i class="ti ti-check me-1"></i>{{ $LANG['save'] ?? '' }}</button>
		<a href="./index.php?module=preferences&amp;view=details&amp;id={{ $preference['pref_id'] ?? '' }}&amp;action=view" class="btn btn-outline-secondary"><i class="ti ti-x me-1"></i>{{ $LANG['cancel'] ?? '' }}</a>
	</div>
	<div class="card-footer">
		<a class="cluetip btn btn-outline-secondary btn-sm" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_what_the" title="{{ $LANG['whats_all_this_inv_pref'] ?? '' }}"><i class="ti ti-help me-1"></i>{{ $LANG['whats_all_this_inv_pref'] ?? '' }}</a>
	</div>
</div>


<input type="hidden" name="op" value="edit_preference" />
@endif
</form>
