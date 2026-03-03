{{-- /*
* Script: details.tpl
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
		<h3 class="card-title">{{ $LANG['customer'] ?? '' }} {{ $LANG['details'] ?? 'Details' }}</h3>
		<div class="card-actions">
			<a href="./index.php?module=customers&amp;view=details&amp;id={{ urlencode($customer['id'] ?? '') }}&amp;action=edit" class="btn btn-primary"><i class="ti ti-edit me-1"></i>{{ $LANG['edit'] ?? '' }}</a>
		</div>
	</div>
	<div class="card-body">
		<div class="si_cust_info">
			<table class="table table-vcenter table-wrap">
				<tr>
					<th>{{ $LANG['customer_name'] ?? '' }}</th>
					<td>{{ $customer['name'] }}</td>
					<td class="td_sep"></td>
					<th>{{ $LANG['customer_department'] ?? '' }}</th>
					<td>{{ $customer['department'] ?? '' }}</td>
				</tr>
				<tr>
					<th>{{ $LANG['attention_short'] ?? '' }}</th>
					<td>{{ $customer['attention'] ?? '' }}</td>
					<td class="td_sep"></td>
					<th>{{ $LANG['phone'] ?? '' }}</th>
					<td>{{ $customer['phone'] ?? '' }}</td>
				</tr>
				<tr>
					<th>{{ $LANG['street'] ?? '' }}</th>
					<td>{{ $customer['street_address'] ?? '' }}</td>
					<td class="td_sep"></td>
					<th>{{ $LANG['mobile_phone'] ?? '' }}</th>
					<td>{{ $customer['mobile_phone'] ?? '' }}</td>
				</tr>
				<tr>
					<th>{{ $LANG['street2'] ?? '' }}</th>
					<td>{{ $customer['street_address2'] ?? '' }}</td>
					<td class="td_sep"></td>
					<th>{{ $LANG['fax'] ?? '' }}</th>
					<td>{{ $customer['fax'] ?? '' }}</td>
				</tr>
				<tr>
					<th>{{ $LANG['city'] ?? '' }}</th>
					<td>{{ $customer['city'] ?? '' }}</td>
					<td class="td_sep"></td>
					<th>{{ $LANG['email'] ?? '' }}</th>
					<td><a href="mailto:{{ $customer['email'] ?? '' }}">{{ $customer['email'] ?? '' }}</a></td>
				</tr>
				<tr>
					<th>{{ $LANG['zip'] ?? '' }}</th>
					<td>{{ $customer['zip_code'] ?? '' }}</td>
					<td class="td_sep"></td>
					<th>{{ $customFieldLabel['customer_cf1'] }}</th>
					<td>{{ $customer['custom_field1'] ?? '' }}</td>
				</tr>
				<tr>
					<th>{{ $LANG['state'] ?? '' }}</th>
					<td>{{ $customer['state'] ?? '' }}</td>
					<td class="td_sep"></td>
					<th>{{ $customFieldLabel['customer_cf2'] }}</th>
					<td>{{ $customer['custom_field2'] ?? '' }}</td>
				</tr>
				<tr>
					<th>{{ $LANG['country'] ?? '' }}</th>
					<td>{{ $customer['country'] ?? '' }}</td>
					<td class="td_sep"></td>
					<th>{{ $customFieldLabel['customer_cf3'] }}</th>
					<td>{{ $customer['custom_field3'] ?? '' }}</td>
				</tr>
				<tr>
					<th>{{ $LANG['enabled'] ?? '' }}</th>
					<td>{{ $customer['wording_for_enabled'] ?? '' }}</td>
					<td class="td_sep"></td>
					<th>{{ $customFieldLabel['customer_cf4'] }}</th>
					<td>{{ $customer['custom_field4'] ?? '' }}</td>
				</tr>
			</table>
		</div>

		<div id="tabs_customer">
			<ul class="nav nav-tabs nav-fill mb-3" role="tablist">
				<li class="nav-item"><a class="nav-link active" href="#section-1" data-bs-toggle="tab" role="tab">{{ $LANG['summary_of_accounts'] ?? '' }}</a></li>
				<li class="nav-item"><a class="nav-link" href="#section-2" data-bs-toggle="tab" role="tab">{{ $LANG['unpaid_invoices'] ?? '' }}</a></li>
				<li class="nav-item"><a class="nav-link" href="#section-3" data-bs-toggle="tab" role="tab">{{ $LANG['customer'] ?? '' }} {{ $LANG['invoice_listings'] ?? '' }}</a></li>
				<li class="nav-item"><a class="nav-link" href="#section-4" data-bs-toggle="tab" role="tab">{{ $LANG['notes'] ?? '' }}</a></li>
			</ul>
			<div class="tab-content">
				<div id="section-1" class="tab-pane active" role="tabpanel">
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

				<div id="section-2" class="tab-pane" role="tabpanel">
					<div class="si_cust_invoices">
						<table class="table table-vcenter table-striped">
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
										<a title="{{ $LANG['process_payment_for'] ?? '' }} {{ $invoice['preference'] }} {{ $invoice['id'] }}" href='index.php?module=payments&view=process&id={{ $invoice['id'] }}&op=pay_selected_invoice' class="btn btn-icon btn-sm btn-outline-success"><i class="ti ti-currency-dollar"></i></a>
										<a href="index.php?module=invoices&amp;view=quick_view&id={{ urlencode($invoice['id'] ?? '') }}" class="btn btn-icon btn-sm btn-outline-primary"><i class="ti ti-eye"></i></a>
									</td>
									<td><a href="index.php?module=invoices&amp;view=quick_view&id={{ urlencode($invoice['id'] ?? '') }}">{{ $invoice['pref_inv_wording'] ?? '' }} {{ $invoice['index_id'] ?? '' }}</a></td>
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
				<div id="section-3" class="tab-pane" role="tabpanel">
					<div class="si_cust_invoices">
						<table class="table table-vcenter table-striped">
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
									<td class="first"><a href="index.php?module=invoices&amp;view=quick_view&id={{ urlencode($invoice['id'] ?? '') }}">{{ $invoice['pref_inv_wording'] ?? '' }} {{ $invoice['index_id'] ?? '' }}</a></td>
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
				<div id="section-4" class="tab-pane" role="tabpanel">
					<div class="si_cust_notes">
						{!! outhtml($customer['notes'] ?? '') !!}
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endif


{{-- ##################################################################################################### --}}



@if(get('action') == 'edit' )

<form name="frmpost" action="index.php?module=customers&amp;view=save&amp;id={{ urlencode($customer['id'] ?? '') }}" method="post" id="frmpost" onsubmit="return checkForm(this);">
<div class="card" id="si_form_cust_edit">
	<div class="card-header">
		<h3 class="card-title">{{ $LANG['edit'] ?? '' }} {{ $LANG['customer'] ?? '' }}</h3>
	</div>
	<div class="card-body">
		<table class="table table-vcenter table-wrap">
			<tr>
				<th>{{ $LANG['customer_name'] ?? '' }}
					<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_required_field" title="{{ $LANG['required_field'] ?? '' }}">
						<i class="ti ti-alert-circle text-danger"></i>
					</a>
				</th>
				<td><input type="text" name="name" value="{{ $customer['name'] ?? '' }}" size="50" id="name" class="form-control validate[required]" /></td>
			</tr>
			<tr>
				<th>{{ $LANG['customer_department'] ?? '' }}</th>
				<td><input type="text" name="department" value="{{ $customer['department'] ?? '' }}" size="50" id="department" class="form-control" /></td>
			</tr>
			<tr>
				<th>{{ $LANG['attention_short'] ?? '' }}
					<a rel="index.php?module=documentation&amp;view=view&amp;page=help_customer_contact" href="#" class="cluetip" title="{{ $LANG['customer_contact'] ?? '' }}">
						<i class="ti ti-help"></i>
					</a>
				</th>
				<td><input type="text" name="attention" value="{{ $customer['attention'] ?? '' }}" size="50" class="form-control" /></td>
			</tr>
			<tr>
				<th>{{ $LANG['street'] ?? '' }}</th>
				<td><input type="text" name="street_address" value="{{ $customer['street_address'] ?? '' }}" size="50" class="form-control" /></td>
			</tr>
			<tr>
				<th>{{ $LANG['street2'] ?? '' }}
					<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_street2" title="{{ $LANG['street2'] ?? '' }}">
						<i class="ti ti-help"></i>
					</a>
				</th>
				<td><input type="text" name="street_address2" value="{{ $customer['street_address2'] ?? '' }}" size="50" class="form-control" /></td>
			</tr>
			<tr>
				<th>{{ $LANG['city'] ?? '' }}</th>
				<td><input type="text" name="city" value="{{ $customer['city'] ?? '' }}" size="50" class="form-control" /></td>
			</tr>
			<tr>
				<th>{{ $LANG['zip'] ?? '' }}</th>
				<td><input type="text" name="zip_code" value="{{ $customer['zip_code'] ?? '' }}" size="50" class="form-control" /></td>
			</tr>
			<tr>
				<th>{{ $LANG['state'] ?? '' }}</th>
				<td><input type="text" name="state" value="{{ $customer['state'] ?? '' }}" size="50" class="form-control" /></td>
			</tr>
			<tr>
				<th>{{ $LANG['country'] ?? '' }}</th>
				<td><input type="text" name="country" value="{{ $customer['country'] ?? '' }}" size="50" class="form-control" /></td>
			</tr>
			<tr>
				<th>{{ $LANG['phone'] ?? '' }}</th>
				<td><input type="text" name="phone" value="{{ $customer['phone'] ?? '' }}" size="50" class="form-control" /></td>
			</tr>
			<tr>
				<th>{{ $LANG['mobile_phone'] ?? '' }}</th>
				<td><input type="text" name="mobile_phone" value="{{ $customer['mobile_phone'] ?? '' }}" size="50" class="form-control" /></td>
			</tr>
			<tr>
				<th>{{ $LANG['fax'] ?? '' }}</th>
				<td><input type="text" name="fax" value="{{ $customer['fax'] ?? '' }}" size="50" class="form-control" /></td>
			</tr>
			<tr>
				<th>{{ $LANG['email'] ?? '' }}</th>
				<td>
					<input type="text" name="email" value="{{ $customer['email'] ?? '' }}" size="50" class="form-control" /></td
			></tr>
			<tr>
				<th>{{ $customFieldLabel['customer_cf1'] ?? '' }}
					<a
						class="cluetip"
						href="#"
						rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields"
						title="{{ $LANG['custom_fields'] ?? '' }}"
					>
			 <i class="ti ti-help"></i></a>
			</th>
			<td>
				<input type="text" name="custom_field1" value="{{ $customer['custom_field1'] ?? '' }}" size="50" class="form-control" />
			</td>
		</tr>
		<tr>
			<th>{{ $customFieldLabel['customer_cf2'] ?? '' }}
				<a
					class="cluetip"
					href="#"
					rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields"
					title="{{ $LANG['custom_fields'] ?? '' }}"
				>
			 <i class="ti ti-help"></i></a>
			</th>
			<td>
				<input type="text" name="custom_field2" value="{{ $customer['custom_field2'] ?? '' }}" size="50" class="form-control" />
			</td>
		</tr>
		<tr>
			<th>{{ $customFieldLabel['customer_cf3'] ?? '' }} 
				<a
					class="cluetip"
					href="#"
					rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields"
					title="{{ $LANG['custom_fields'] ?? '' }}"
				> 
			<i class="ti ti-help"></i></a>
			</th>
			<td>
				<input type="text" name="custom_field3" value="{{ $customer['custom_field3'] ?? '' }}" size="50" class="form-control" />
			</td>
		</tr>
		<tr>
			<th>{{ $customFieldLabel['customer_cf4'] ?? '' }}
				<a
					class="cluetip"
					href="#"
					rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields"
					title="{{ $LANG['custom_fields'] ?? '' }}"
				>
			 <i class="ti ti-help"></i></a>
			</th>
			<td>
				<input type="text" name="custom_field4" value="{{ $customer['custom_field4'] ?? '' }}" size="50" class="form-control" />
			</td>
		</tr>
		<tr>
			<th>{{ $LANG['notes'] ?? '' }}</th>
			<td><textarea name="notes" class="form-control editor" rows="8" cols="50">{!! outhtml($customer['notes'] ?? '') !!}</textarea></td>
		</tr>
		@showCustomFields(2, get('customer'))
		<tr>
			<th>{{ $LANG['enabled'] ?? '' }}</th>
			<td>
				{html_options name=enabled options=$enabled selected=$customer['enabled']}
			</td>
		</tr>
	</table>

	<div class="card-footer text-end">
		<button type="submit" class="btn btn-primary" name="save_customer" value="{{ $LANG['save_customer'] ?? '' }}"><i class="ti ti-check me-1"></i>{{ $LANG['save'] ?? '' }}</button>
		<a href="./index.php?module=customers&amp;view=manage" class="btn btn-secondary"><i class="ti ti-x me-1"></i>{{ $LANG['cancel'] ?? '' }}</a>
	</div>
</div>

<input type="hidden" name="op" value="edit_customer">
</form>
@endif
