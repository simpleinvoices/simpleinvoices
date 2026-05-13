{{-- /*
* View: details (Blade)
* 	 Customer details template
*
* License:
*	 GPL v3 or above
*
* Website:
*	http://www.simpleinvoices.org
*/ --}}

@if(get('action') == 'view' )
<div class="card" id="si_form_cust">
	<div class="card-header">
		<ul class="nav nav-tabs card-header-tabs" role="tablist">
			<li class="nav-item" role="presentation">
				<a class="nav-link active" href="#cust-view-details" data-bs-toggle="tab" role="tab"><i class="ti ti-info-circle me-1"></i><span class="d-none d-md-inline">{{ $LANG['details'] ?? '' }}</span></a>
			</li>
			<li class="nav-item" role="presentation">
				<a class="nav-link" href="#cust-view-address" data-bs-toggle="tab" role="tab"><i class="ti ti-map-pin me-1"></i><span class="d-none d-md-inline">{{ $LANG['address'] ?? '' }}</span></a>
			</li>
			<li class="nav-item" role="presentation">
				<a class="nav-link" href="#cust-view-contact" data-bs-toggle="tab" role="tab"><i class="ti ti-phone me-1"></i><span class="d-none d-md-inline">{{ $LANG['contacts'] ?? '' }}</span></a>
			</li>
			<li class="nav-item" role="presentation">
				<a class="nav-link" href="#cust-view-custom" data-bs-toggle="tab" role="tab"><i class="ti ti-adjustments me-1"></i><span class="d-none d-md-inline">{{ $LANG['custom_fields'] ?? '' }}</span></a>
			</li>
			<li class="nav-item" role="presentation">
				<a class="nav-link" href="#cust-view-taxids" data-bs-toggle="tab" role="tab"><i class="ti ti-id me-1"></i><span class="d-none d-md-inline">{{ $LANG['tax_id_tab_label'] ?? 'Tax IDs' }}</span></a>
			</li>
			<li class="nav-item" role="presentation">
				<a class="nav-link" href="#cust-view-notes" data-bs-toggle="tab" role="tab"><i class="ti ti-notes me-1"></i><span class="d-none d-md-inline">{{ $LANG['notes'] ?? '' }}</span></a>
			</li>
			<li class="nav-item" role="presentation">
				<a class="nav-link" href="#cust-view-summary" data-bs-toggle="tab" role="tab"><i class="ti ti-report-money me-1"></i><span class="d-none d-md-inline">{{ $LANG['summary_of_accounts'] ?? '' }}</span></a>
			</li>
			<li class="nav-item" role="presentation">
				<a class="nav-link" href="#cust-view-unpaid" data-bs-toggle="tab" role="tab"><i class="ti ti-receipt-off me-1"></i><span class="d-none d-md-inline">{{ $LANG['unpaid_invoices'] ?? '' }}</span></a>
			</li>
			<li class="nav-item" role="presentation">
				<a class="nav-link" href="#cust-view-invoices" data-bs-toggle="tab" role="tab"><i class="ti ti-list me-1"></i><span class="d-none d-md-inline">{{ $LANG['customer'] ?? '' }} {{ $LANG['invoice_listings'] ?? '' }}</span></a>
			</li>
		</ul>
	</div>
	<div class="card-body">
		<div class="tab-content">
			<div id="cust-view-details" class="tab-pane active" role="tabpanel">
				<table class="table table-vcenter table-wrap">
					<tr><th>{{ $LANG['customer_name'] ?? '' }}</th><td>{{ $customer['name'] }}</td></tr>
					<tr><th>{{ $LANG['customer_department'] ?? '' }}</th><td>{{ $customer['department'] ?? '' }}</td></tr>
					<tr><th>{{ $LANG['attention_short'] ?? '' }}</th><td>{{ $customer['attention'] ?? '' }}</td></tr>
					<tr><th>{{ $LANG['enabled'] ?? '' }}</th><td>{{ $customer['wording_for_enabled'] ?? '' }}</td></tr>
				</table>
			</div>
			<div id="cust-view-address" class="tab-pane" role="tabpanel">
				<table class="table table-vcenter table-wrap">
					<tr><th>{{ $LANG['street'] ?? '' }}</th><td>{{ $customer['street_address'] ?? '' }}</td></tr>
					<tr><th>{{ $LANG['street2'] ?? '' }}</th><td>{{ $customer['street_address2'] ?? '' }}</td></tr>
					<tr><th>{{ $LANG['city'] ?? '' }}</th><td>{{ $customer['city'] ?? '' }}</td></tr>
					<tr><th>{{ $LANG['state'] ?? '' }}</th><td>{{ $customer['state'] ?? '' }}</td></tr>
					<tr><th>{{ $LANG['zip'] ?? '' }}</th><td>{{ $customer['zip_code'] ?? '' }}</td></tr>
					<tr><th>{{ $LANG['country'] ?? '' }}</th><td>{{ $customer['country'] ?? '' }}</td></tr>
				</table>
			</div>
			<div id="cust-view-contact" class="tab-pane" role="tabpanel">
				<table class="table table-vcenter table-wrap">
					<tr><th>{{ $LANG['phone'] ?? '' }}</th><td>{{ $customer['phone'] ?? '' }}</td></tr>
					<tr><th>{{ $LANG['mobile_phone'] ?? '' }}</th><td>{{ $customer['mobile_phone'] ?? '' }}</td></tr>
					<tr><th>{{ $LANG['fax'] ?? '' }}</th><td>{{ $customer['fax'] ?? '' }}</td></tr>
					<tr><th>{{ $LANG['email'] ?? '' }}</th><td><a href="mailto:{{ $customer['email'] ?? '' }}">{{ $customer['email'] ?? '' }}</a></td></tr>
				</table>
			</div>
			<div id="cust-view-custom" class="tab-pane" role="tabpanel">
				<table class="table table-vcenter table-wrap">
					<tr><th>{{ $customFieldLabel['customer_cf1'] ?? '' }}</th><td>{{ $customer['custom_field1'] ?? '' }}</td></tr>
					<tr><th>{{ $customFieldLabel['customer_cf2'] ?? '' }}</th><td>{{ $customer['custom_field2'] ?? '' }}</td></tr>
					<tr><th>{{ $customFieldLabel['customer_cf3'] ?? '' }}</th><td>{{ $customer['custom_field3'] ?? '' }}</td></tr>
					<tr><th>{{ $customFieldLabel['customer_cf4'] ?? '' }}</th><td>{{ $customer['custom_field4'] ?? '' }}</td></tr>
				</table>
			</div>
			<div id="cust-view-taxids" class="tab-pane" role="tabpanel">
				<table class="table table-vcenter table-wrap">
					<tr><th>{{ $LANG['tax_id_label_1'] ?? 'Tax ID Type 1' }}</th><td>{{ $customer['tax_id_label_1'] ?? '' }}</td></tr>
					<tr><th>{{ $LANG['tax_id_name_1'] ?? 'Tax ID 1' }}</th><td>{{ $customer['tax_id_name_1'] ?? '' }}</td></tr>
					<tr><th>{{ $LANG['tax_id_label_2'] ?? 'Tax ID Type 2' }}</th><td>{{ $customer['tax_id_label_2'] ?? '' }}</td></tr>
					<tr><th>{{ $LANG['tax_id_name_2'] ?? 'Tax ID 2' }}</th><td>{{ $customer['tax_id_name_2'] ?? '' }}</td></tr>
				</table>
			</div>
			<div id="cust-view-notes" class="tab-pane" role="tabpanel">
				<div class="si_cust_notes">{!! outhtml($customer['notes'] ?? '') !!}</div>
			</div>
			<div id="cust-view-summary" class="tab-pane" role="tabpanel">
					<div class="si_cust_account">
						<table class="table table-vcenter">
							<tr>
								<th>{{ $LANG['total_invoices'] ?? '' }}</th>
								<td class="si_right">{{ number_format($stuff['total'] ?? '', 2) }}</td>
							</tr>
							<tr>
								<th><a href="index.php?module=payments&view=manage&c_id={{ urlencode($customer['id'] ?? '') }}">{{ $LANG['total_paid'] ?? '' }}</a></th>
								<td class="si_right">{{ number_format($stuff['paid'] ?? '', 2) }}</td>
							</tr>
							<tr>
								<th>{{ $LANG['total_owing'] ?? '' }}</th>
								<td class="si_right"><u>{{ number_format($stuff['owing'] ?? '', 2) }}</u></td>
							</tr>
						</table>
					</div>
			</div>

			<div id="cust-view-unpaid" class="tab-pane" role="tabpanel">
					<div class="si_cust_invoices">
						<table class="table table-vcenter">
							<thead>
								<tr class="tr_head">
									<th class="first">{{ $LANG['actions'] ?? '' }}</th>
									<th>{{ $LANG['id'] ?? '' }}</th>
									<th>{{ $LANG['date_created'] ?? '' }}</th>
									<th>{{ $LANG['total'] ?? '' }}</th>
									<th>{{ $LANG['paid'] ?? '' }}</th>
									<th>{{ $LANG['owing'] ?? '' }}</th>
								</tr>
							</thead>
							<tbody>
							@foreach(($invoices ?? []) as $invoice)
@if($invoice['status'] > 0)
	@if($invoice['owing'] != 0)
								<tr class="index_table">
									<td class="first">
										<a title="{{ $LANG['process_payment_for'] ?? '' }} {{ $invoice['preference'] }} {{ $invoice['id'] }}" href='index.php?module=payments&view=process&id={{ $invoice['id'] }}&op=pay_selected_invoice' class="btn btn-icon btn-outline-success"><i class="ti ti-currency-dollar"></i></a>
										<a href="index.php?module=invoices&amp;view=quick_view&id={{ urlencode($invoice['id'] ?? '') }}" class="btn btn-icon btn-outline-primary"><i class="ti ti-eye"></i></a>
									</td>
									<td><a href="index.php?module=invoices&amp;view=quick_view&id={{ urlencode($invoice['id'] ?? '') }}">{{ $invoice['pref_inv_wording'] ?? ($LANG['invoice'] ?? '') }} {{ $invoice['index_id'] ?? '' }}</a></td>
									<td>{{ $invoice['date'] ?? '' }}</td>
									<td>{{ number_format($invoice['total'] ?? '', 2) }}</td>
									<td>{{ number_format($invoice['paid'] ?? '', 2) }}</td>
									<td>{{ number_format($invoice['owing'] ?? '', 2) }}</td>
								</tr>
	@endif
@endif
							@endforeach
							</tbody>
						</table>
					</div>
			</div>
			<div id="cust-view-invoices" class="tab-pane" role="tabpanel">
					<div class="si_cust_invoices">
						<table class="table table-vcenter">
							<thead>
								<tr class="tr_head">
									<th class="first">{{ $LANG['id'] ?? '' }}</th>
									<th>{{ $LANG['date_created'] ?? '' }}</th>
									<th>{{ $LANG['total'] ?? '' }}</th>
									<th>{{ $LANG['paid'] ?? '' }}</th>
									<th>{{ $LANG['owing'] ?? '' }}</th>
								</tr>
							</thead>
							<tbody>
							@foreach(($invoices ?? []) as $invoice)
								<tr class="index_table">
									<td class="first"><a href="index.php?module=invoices&amp;view=quick_view&id={{ urlencode($invoice['id'] ?? '') }}">{{ $invoice['pref_inv_wording'] ?? ($LANG['invoice'] ?? '') }} {{ $invoice['index_id'] ?? '' }}</a></td>
									<td>{{ $invoice['date'] ?? '' }}</td>
									<td>{{ number_format($invoice['total'] ?? '', 2) }}</td>
@if($invoice['status'] > 0)
									<td>{{ number_format($invoice['paid'] ?? '', 2) }}</td>
	@if($invoice['owing'] != 0)
									<td>{{ number_format($invoice['owing'] ?? '', 2) }}</td>
	@else
									<td>&nbsp;</td>
	@endif
@else
									<td colspan="2">&nbsp;</td>
@endif
								</tr>
							@endforeach
							</tbody>
						</table>
					</div>
			</div>
		</div>
	</div>
	@php $isCustomerPortal = (($_SESSION['SI_Auth']['role_name'] ?? '') === 'customer'); @endphp
	<div class="card-footer">
		<div class="d-flex align-items-center flex-wrap gap-2">
			@if(!$isCustomerPortal)
			<a href="./index.php?module=customers&amp;view=manage" class="btn btn-link">{{ $LANG['cancel'] ?? '' }}</a>
			@endif
			<div class="ms-auto d-flex flex-wrap gap-2">
				@if(!empty($showCustomerPortalLink) && !empty($customerPortalUrl) && !$isCustomerPortal)
				<a href="{{ $customerPortalUrl }}" target="_blank" rel="noopener" class="btn btn-outline-secondary">
					<i class="ti ti-login me-1"></i>Customer portal
				</a>
				@endif
				@if(!$isCustomerPortal)
				<a href="./index.php?module=customers&amp;view=details&amp;id={{ urlencode($customer['id'] ?? '') }}&amp;action=edit" class="btn btn-primary"><i class="ti ti-edit me-1"></i>{{ $LANG['edit'] ?? '' }}</a>
				@endif
			</div>
		</div>
	</div>
</div>
@endif


{{-- ##################################################################################################### --}}



@if(get('action') == 'edit' )

<form name="frmpost" action="index.php?module=customers&amp;view=save&amp;id={{ urlencode($customer['id'] ?? '') }}" method="post" id="frmpost" class="needs-validation" novalidate>
<div class="card" id="si_form_cust_edit">
	<div class="card-header">
		<ul class="nav nav-tabs card-header-tabs" role="tablist">
			<li class="nav-item" role="presentation">
				<a class="nav-link active" href="#cust-edit-details" data-bs-toggle="tab" role="tab"><i class="ti ti-info-circle me-1"></i><span class="d-none d-md-inline">{{ $LANG['details'] ?? '' }}</span></a>
			</li>
			<li class="nav-item" role="presentation">
				<a class="nav-link" href="#cust-edit-address" data-bs-toggle="tab" role="tab"><i class="ti ti-map-pin me-1"></i><span class="d-none d-md-inline">{{ $LANG['address'] ?? '' }}</span></a>
			</li>
			<li class="nav-item" role="presentation">
				<a class="nav-link" href="#cust-edit-contact" data-bs-toggle="tab" role="tab"><i class="ti ti-phone me-1"></i><span class="d-none d-md-inline">{{ $LANG['contacts'] ?? '' }}</span></a>
			</li>
			<li class="nav-item" role="presentation">
				<a class="nav-link" href="#cust-edit-custom" data-bs-toggle="tab" role="tab"><i class="ti ti-adjustments me-1"></i><span class="d-none d-md-inline">{{ $LANG['custom_fields'] ?? '' }}</span></a>
			</li>
			<li class="nav-item" role="presentation">
				<a class="nav-link" href="#cust-edit-taxids" data-bs-toggle="tab" role="tab"><i class="ti ti-id me-1"></i><span class="d-none d-md-inline">{{ $LANG['tax_id_tab_label'] ?? 'Tax IDs' }}</span></a>
			</li>
			<li class="nav-item" role="presentation">
				<a class="nav-link" href="#cust-edit-notes" data-bs-toggle="tab" role="tab"><i class="ti ti-notes me-1"></i><span class="d-none d-md-inline">{{ $LANG['notes'] ?? '' }}</span></a>
			</li>
		</ul>
	</div>
	<div class="card-body">
		<div class="tab-content">
			<div id="cust-edit-details" class="tab-pane active" role="tabpanel">
				<div class="mb-3">
					<label class="form-label">{{ $LANG['customer_name'] ?? '' }}
						<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_required_field" title="{{ $LANG['required_field'] ?? '' }}"><i class="ti ti-asterisk text-danger"></i></a>
					</label>
					<input type="text" name="name" value="{{ $customer['name'] ?? '' }}" id="name" class="form-control" required />
					<div class="invalid-feedback">{{ $LANG['required_field'] ?? 'Required' }}</div>
				</div>
				<div class="mb-3">
					<label class="form-label">{{ $LANG['customer_department'] ?? '' }}</label>
					<input type="text" name="department" value="{{ $customer['department'] ?? '' }}" id="department" class="form-control" />
				</div>
				<div class="mb-3">
					<label class="form-label">{{ $LANG['attention_short'] ?? '' }}
						<a rel="index.php?module=documentation&amp;view=view&amp;page=help_customer_contact" href="#" class="cluetip" title="{{ $LANG['customer_contact'] ?? '' }}"><i class="ti ti-help"></i></a>
					</label>
					<input type="text" name="attention" value="{{ $customer['attention'] ?? '' }}" class="form-control" />
				</div>
				<div class="mb-3">
					<label class="form-label">{{ $LANG['email'] ?? '' }}</label>
					<input type="text" name="email" value="{{ $customer['email'] ?? '' }}" class="form-control" />
				</div>
				<div class="mb-3">
					<label class="form-label">{{ $LANG['enabled'] ?? '' }}</label>
					{html_options name=enabled options=$enabled selected=$customer['enabled'] class="form-select"}
				</div>
			</div>
			<div id="cust-edit-address" class="tab-pane" role="tabpanel">
				<div class="mb-3">
					<label class="form-label">{{ $LANG['street'] ?? '' }}</label>
					<input type="text" name="street_address" value="{{ $customer['street_address'] ?? '' }}" class="form-control" />
				</div>
				<div class="mb-3">
					<label class="form-label">{{ $LANG['street2'] ?? '' }}
						<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_street2" title="{{ $LANG['street2'] ?? '' }}"><i class="ti ti-help"></i></a>
					</label>
					<input type="text" name="street_address2" value="{{ $customer['street_address2'] ?? '' }}" class="form-control" />
				</div>
				<div class="mb-3">
					<label class="form-label">{{ $LANG['city'] ?? '' }}</label>
					<input type="text" name="city" value="{{ $customer['city'] ?? '' }}" class="form-control" />
				</div>
				<div class="mb-3">
					<label class="form-label">{{ $LANG['state'] ?? '' }}</label>
					<input type="text" name="state" value="{{ $customer['state'] ?? '' }}" class="form-control" />
				</div>
				<div class="mb-3">
					<label class="form-label">{{ $LANG['zip'] ?? '' }}</label>
					<input type="text" name="zip_code" value="{{ $customer['zip_code'] ?? '' }}" class="form-control" />
				</div>
				<div class="mb-3">
					<label class="form-label">{{ $LANG['country'] ?? '' }}</label>
					<input type="text" name="country" value="{{ $customer['country'] ?? '' }}" class="form-control" />
				</div>
			</div>
			<div id="cust-edit-contact" class="tab-pane" role="tabpanel">
				<div class="mb-3">
					<label class="form-label">{{ $LANG['phone'] ?? '' }}</label>
					<input type="text" name="phone" value="{{ $customer['phone'] ?? '' }}" class="form-control" />
				</div>
				<div class="mb-3">
					<label class="form-label">{{ $LANG['mobile_phone'] ?? '' }}</label>
					<input type="text" name="mobile_phone" value="{{ $customer['mobile_phone'] ?? '' }}" class="form-control" />
				</div>
				<div class="mb-3">
					<label class="form-label">{{ $LANG['fax'] ?? '' }}</label>
					<input type="text" name="fax" value="{{ $customer['fax'] ?? '' }}" class="form-control" />
				</div>
			</div>
			<div id="cust-edit-custom" class="tab-pane" role="tabpanel">
				<div class="mb-3">
					<label class="form-label">{{ $customFieldLabel['customer_cf1'] ?? '' }}
						<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields" title="{{ $LANG['custom_fields'] ?? '' }}"><i class="ti ti-help"></i></a>
					</label>
					<input type="text" name="custom_field1" value="{{ $customer['custom_field1'] ?? '' }}" class="form-control" />
				</div>
				<div class="mb-3">
					<label class="form-label">{{ $customFieldLabel['customer_cf2'] ?? '' }}
						<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields" title="{{ $LANG['custom_fields'] ?? '' }}"><i class="ti ti-help"></i></a>
					</label>
					<input type="text" name="custom_field2" value="{{ $customer['custom_field2'] ?? '' }}" class="form-control" />
				</div>
				<div class="mb-3">
					<label class="form-label">{{ $customFieldLabel['customer_cf3'] ?? '' }}
						<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields" title="{{ $LANG['custom_fields'] ?? '' }}"><i class="ti ti-help"></i></a>
					</label>
					<input type="text" name="custom_field3" value="{{ $customer['custom_field3'] ?? '' }}" class="form-control" />
				</div>
				<div class="mb-3">
					<label class="form-label">{{ $customFieldLabel['customer_cf4'] ?? '' }}
						<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields" title="{{ $LANG['custom_fields'] ?? '' }}"><i class="ti ti-help"></i></a>
					</label>
					<input type="text" name="custom_field4" value="{{ $customer['custom_field4'] ?? '' }}" class="form-control" />
				</div>
				@showCustomFields(2, get('customer'))
			</div>
			<div id="cust-edit-taxids" class="tab-pane" role="tabpanel">
				<div class="row g-3">
					<div class="col-md-6">
						<div class="mb-3">
							<label class="form-label">{{ $LANG['tax_id_label_1'] ?? 'Tax ID Type 1' }}</label>
							<input type="text" name="tax_id_label_1" value="{{ $customer['tax_id_label_1'] ?? '' }}" class="form-control" placeholder="{{ $LANG['tax_id_label_placeholder'] ?? 'e.g. EIN, VAT, ABN' }}" />
						</div>
					</div>
					<div class="col-md-6">
						<div class="mb-3">
							<label class="form-label">{{ $LANG['tax_id_name_1'] ?? 'Tax ID 1' }}</label>
							<input type="text" name="tax_id_name_1" value="{{ $customer['tax_id_name_1'] ?? '' }}" class="form-control" placeholder="{{ $LANG['tax_id_name_placeholder'] ?? 'Enter tax ID number' }}" />
						</div>
					</div>
					<div class="col-md-6">
						<div class="mb-3">
							<label class="form-label">{{ $LANG['tax_id_label_2'] ?? 'Tax ID Type 2' }}</label>
							<input type="text" name="tax_id_label_2" value="{{ $customer['tax_id_label_2'] ?? '' }}" class="form-control" placeholder="{{ $LANG['tax_id_label_placeholder'] ?? 'e.g. State Tax ID, ACN' }}" />
						</div>
					</div>
					<div class="col-md-6">
						<div class="mb-3">
							<label class="form-label">{{ $LANG['tax_id_name_2'] ?? 'Tax ID 2' }}</label>
							<input type="text" name="tax_id_name_2" value="{{ $customer['tax_id_name_2'] ?? '' }}" class="form-control" placeholder="{{ $LANG['tax_id_name_placeholder'] ?? 'Enter tax ID number' }}" />
						</div>
					</div>
				</div>
			</div>
			<div id="cust-edit-notes" class="tab-pane" role="tabpanel">
				<div class="mb-3">
					<label class="form-label">{{ $LANG['notes'] ?? '' }}</label>
					<textarea name="notes" class="form-control editor" rows="8">{!! outhtml($customer['notes'] ?? '') !!}</textarea>
				</div>
			</div>
		</div>
	</div>
	<div class="card-footer">
		<div class="d-flex">
			<a href="./index.php?module=customers&amp;view=manage" class="btn btn-link">{{ $LANG['cancel'] ?? '' }}</a>
			<button type="submit" class="btn btn-primary ms-auto" name="save_customer" value="{{ $LANG['save_customer'] ?? '' }}"><i class="ti ti-check me-1"></i>{{ $LANG['save'] ?? '' }}</button>
		</div>
	</div>
</div>

<input type="hidden" name="op" value="edit_customer">
</form>
@endif
