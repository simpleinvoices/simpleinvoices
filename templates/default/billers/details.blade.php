{{-- n Script: details.tpl
* 	Biller details template
*
* Last edited:
* 	 2008-08-25
*
* License:
*	 GPL v3 or above --}}
<form name="frmpost" action="index.php?module=billers&amp;view=save&amp;id={{ $smarty->get->id }}" method="post" id="frmpost" onsubmit="return checkForm(this);">

@if($smarty->get->action== 'view' )

<div class="card">
	<div class="card-header">
		<h3 class="card-title">{{ $LANG['biller'] ?? '' }} {{ $LANG['details'] ?? 'Details' }}</h3>
		<div class="card-actions">
			<a href="./index.php?module=billers&amp;view=details&amp;action=edit&amp;id={{ $biller['id'] }}" class="btn btn-primary"><i class="ti ti-edit me-1"></i>{{ $LANG['edit'] ?? '' }}</a>
		</div>
	</div>
	<div class="card-body">
		<table class="table table-vcenter table-wrap">
			<tr>
				<th>{{ $LANG['biller_name'] ?? '' }}</th>
				<td>{{ $biller['name'] }}</td>
			</tr>
			<tr>
				<th>{{ $LANG['street'] ?? '' }}</th>
				<td>{{ $biller['street_address'] }}</td>
			</tr>
			<tr>
				<th>{{ $LANG['street2'] ?? '' }}</th>
				<td>{{ $biller['street_address2'] }}</td>
			</tr>
			<tr>
				<th>{{ $LANG['city'] ?? '' }}</th>
				<td>{{ $biller['city'] }}</td>
			</tr>
			<tr>
				<th>{{ $LANG['zip'] ?? '' }}</th>
				<td>{{ $biller['zip_code'] }}</td>
			</tr>
			<tr>
				<th>{{ $LANG['state'] ?? '' }}</th>
				<td>{{ $biller['state'] }}</td>
			</tr>
			<tr>
				<th>{{ $LANG['country'] ?? '' }}</th>
				<td>{{ $biller['country'] }}</td>
			</tr>
			<tr>
				<th>{{ $LANG['mobile_phone'] ?? '' }}</th>
				<td>{{ $biller['mobile_phone'] }}</td>
			</tr>
			<tr>
				<th>{{ $LANG['phone'] ?? '' }}</th>
				<td>{{ $biller['phone'] }}</td>
			</tr>
			<tr>
				<th>{{ $LANG['fax'] ?? '' }}</th>
				<td>{{ $biller['fax'] }}</td>
			</tr>
			<tr>
				<th>{{ $LANG['email'] ?? '' }}</th>
				<td>{{ $biller['email'] }}</td>
			</tr>
			<tr>
				<th>{{ $LANG['paypal_business_name'] ?? '' }}</th>
				<td>{{ $biller['paypal_business_name'] }}</td>
			</tr>
			<tr>
				<th>{{ $LANG['paypal_notify_url'] ?? '' }}</th>
				<td>{{ $biller['paypal_notify_url'] }}</td>
			</tr>
			<tr>
				<th>{{ $LANG['paypal_return_url'] ?? '' }}</th>
				<td>{{ $biller['paypal_return_url'] }}</td>
			</tr>
			<tr>
				<th>{{ $LANG['eway_customer_id'] ?? '' }}</th>
				<td>{{ $biller['eway_customer_id'] }}</td>
			</tr>
			<tr>
				<th>{{ $LANG['paymentsgateway_api_id'] ?? '' }}</th>
				<td>{{ $biller['paymentsgateway_api_id'] }}</td>
			</tr>
			<tr>
				<th>{{ $customFieldLabel['biller_cf1'] }}</th>
				<td>{{ $biller['custom_field1'] }}</td>
			</tr>
			<tr>
				<th>{{ $customFieldLabel['biller_cf2'] }}</th>
				<td>{{ $biller['custom_field2'] }}</td>
			</tr>
			<tr>
				<th>{{ $customFieldLabel['biller_cf3'] }}</th>
				<td>{{ $biller['custom_field3'] }}</td>
			</tr>
			<tr>
				<th class="details_screen">{{ $customFieldLabel['biller_cf4'] }}</th>
				<td>{{ $biller['custom_field4'] }}</td>
			</tr>
			<tr>
				<th>{{ $LANG['logo_file'] ?? '' }}</th>
				<td>
					@if(!empty($biller['logo']))
						<img src="templates/invoices/logos/{{ $biller['logo'] }}" alt="{{ $biller['logo'] }}" class="img-fluid"><br>{{ $biller['logo'] }}
					@endif
				</td>
			</tr>
			<tr>
				<th>{{ $LANG['invoice_footer'] ?? '' }}</th>
				<td>{{ $biller['footer'] }}</td>
			</tr>
			<tr>
				<th>{{ $LANG['notes'] ?? '' }}</th>
				<td>{{ $biller['notes'] }}</td>
			</tr>
			@showCustomFields(1, $smarty->get->id ?? '')
			<tr>
				<th>{{ $LANG['enabled'] ?? '' }}</th>
				<td>{{ $biller['wording_for_enabled'] }}</td>
			</tr>
		</table>
	</div>
</div>

@endif


{{-- ######################################################################################### --}}


@if($smarty->get->action== 'edit' )
<div class="card">
	<div class="card-header">
		<h3 class="card-title">{{ $LANG['edit'] ?? '' }} {{ $LANG['biller'] ?? '' }}</h3>
	</div>
	<div class="card-body">
		<table class="table table-vcenter table-wrap">
			<tr>
				<th>{{ $LANG['biller_name'] ?? '' }} 
				<a 
					class="cluetip"
					href="#"
					rel="index.php?module=documentation&amp;view=view&amp;page=help_required_field"
					title="{{ $LANG['required_field'] ?? '' }}"
				>
				<i class="ti ti-alert-circle text-danger"></i>
				</a>
				</th>
				<td><input type="text" name="name" value="{{ $biller['name'] ?? '' }}" size="50" id="name" class="form-control validate[required]" /></td>
			</tr>
			<tr>
				<th>{{ $LANG['street'] ?? '' }}</th>
				<td><input type="text" name="street_address" value="{{ $biller['street_address'] ?? '' }}" size="50" class="form-control" /></td>
			</tr>
			<tr>
				<th>{{ $LANG['street2'] ?? '' }}
				<a
					class="cluetip"
					href="#"
					rel="index.php?module=documentation&amp;view=view&amp;page=help_street2"
					title="{{ $LANG['street2'] ?? '' }}"
				> 
				<i class="ti ti-help"></i>
				</a>
				</th>
				<td><input type="text" name="street_address2" value="{{ $biller['street_address2'] ?? '' }}" size="50" class="form-control" /></td>
			</tr>
			<tr>
				<th>{{ $LANG['city'] ?? '' }}</th>
				<td><input type="text" name="city" value="{{ $biller['city'] ?? '' }}" size="50" class="form-control" /></td>
			</tr>
			<tr>
				<th>{{ $LANG['zip'] ?? '' }}</th>
				<td><input type="text" name="zip_code" value="{{ $biller['zip_code'] ?? '' }}" size="50" class="form-control" /></td>
			</tr>
			<tr>
				<th>{{ $LANG['state'] ?? '' }}</th>
				<td><input type="text" name="state" value="{{ $biller['state'] ?? '' }}" size="50" class="form-control" /></td>
			</tr>
			<tr>
				<th>{{ $LANG['country'] ?? '' }}</th>
				<td><input type="text" name="country" value="{{ $biller['country'] ?? '' }}" size="50" class="form-control" /></td>
			</tr>
			<tr>
				<th>{{ $LANG['mobile_phone'] ?? '' }}</th>
				<td><input type="text" name="mobile_phone" value="{{ $biller['mobile_phone'] ?? '' }}" size="50" class="form-control" /></td>
			</tr>
			<tr>
				<th>{{ $LANG['phone'] ?? '' }}</th>
				<td><input type="text" name="phone" value="{{ $biller['phone'] ?? '' }}" size="50" class="form-control" /></td>
			</tr>
			<tr>
				<th>{{ $LANG['fax'] ?? '' }}</th>
				<td><input type="text" name="fax" value="{{ $biller['fax'] ?? '' }}" size="50" class="form-control" /></td>
			</tr>
			<tr>
				<th>{{ $LANG['email'] ?? '' }}</th>
				<td><input type="text" name="email" value="{{ $biller['email'] ?? '' }}" size="50" class="form-control" /></td>
			</tr>
			<tr>
				<th>{{ $LANG['paypal_business_name'] ?? '' }}</th>
				<td><input type="text" name="paypal_business_name" value="{{ $biller['paypal_business_name'] ?? '' }}" size="25" class="form-control" /></td>
			</tr>
			<tr>
				<th>{{ $LANG['paypal_notify_url'] ?? '' }}</th>
				<td><input type="text" name="paypal_notify_url" value="{{ $biller['paypal_notify_url'] ?? '' }}" size="50" class="form-control" /></td>
			</tr>
			<tr>
				<th>{{ $LANG['paypal_return_url'] ?? '' }}</th>
				<td><input type="text" name="paypal_return_url" value="{{ $biller['paypal_return_url'] ?? '' }}" size="50" class="form-control" /></td>
			</tr>
			<tr>
				<th>{{ $LANG['eway_customer_id'] ?? '' }}</th>
				<td><input type="text" name="eway_customer_id" value="{{ $biller['eway_customer_id'] ?? '' }}" size="50" class="form-control" /></td>
			</tr>
			<tr>
				<th>{{ $LANG['paymentsgateway_api_id'] ?? '' }}</th>
				<td><input type="text" name="paymentsgateway_api_id" value="{{ $biller['paymentsgateway_api_id'] ?? '' }}" size="50" class="form-control" /></td>
			</tr>
			<tr>
				<th>{{ $customFieldLabel['biller_cf1'] ?? '' }}
				<a
					class="cluetip"
					href="#"
					rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields"
					title="{{ $LANG['custom_fields'] ?? '' }}"
				> 
				<i class="ti ti-help"></i>
				</a>
				</th>
				<td><input type="text" name="custom_field1" value="{{ $biller['custom_field1'] ?? '' }}" size="50" class="form-control" /></td>
			</tr>
			<tr>
				<th>{{ $customFieldLabel['biller_cf2'] }}
				<a
					class="cluetip"
					href="#"
					rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields"
					title="{{ $LANG['custom_fields'] ?? '' }}"
				> 
				<i class="ti ti-help"></i>
				</a>
				</th>
				<td><input type="text" name="custom_field2" value="{{ $biller['custom_field2'] }}" size="50" class="form-control" /></td>
			</tr>
			<tr>
				<th>{{ $customFieldLabel['biller_cf3'] ?? '' }}
				<a
					class="cluetip"
					href="#"
					rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields"
					title="{{ $LANG['custom_fields'] ?? '' }}"
				> 
				<i class="ti ti-help"></i>
				</a>
				</th>
				<td><input type="text" name="custom_field3" value="{{ $biller['custom_field3'] ?? '' }}" size="50" class="form-control" /></td>
			</tr>
			<tr>
				<th>{{ $customFieldLabel['biller_cf4'] ?? '' }}
				<a
					class="cluetip"
					href="#"
					rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields"
					title="{{ $LANG['custom_fields'] ?? '' }}"
				> 
				<i class="ti ti-help"></i>
				</a>
				</th>
				<td><input type="text" name="custom_field4" value="{{ $biller['custom_field4'] ?? '' }}" size="50" class="form-control" /></td>
			</tr>
			<tr>
				<th>
				{{ $LANG['logo_file'] ?? '' }}
				<a
					class="cluetip"
					href="#"
					rel="index.php?module=documentation&amp;view=view&amp;page=help_insert_biller_text"
					title="{{ $LANG['logo_file'] ?? '' }}"
				>
				<i class="ti ti-help"></i>
				</a>
				</th>
				<td>
					{html_options name=logo output=$files values=$files selected=$biller['logo'] }
				</td>
			</tr>
			<tr>
				<th>{{ $LANG['invoice_footer'] ?? '' }}</th>
				<td><textarea name="footer" class="form-control editor" rows="4" cols="50">{{ $biller['footer'] ?? '' }}</textarea></td>
			</tr>
			<tr>
				<th>{{ $LANG['notes'] ?? '' }}</th>
				<td><textarea name="notes" class="form-control editor" rows="8" cols="50">{{ $biller['notes'] ?? '' }}</textarea></td>
			</tr>
			<tr>
				<th>{{ $LANG['enabled'] ?? '' }}</th>
				<td>
				{html_options name=enabled options=$enabled selected=$biller['enabled']}
				</td>
			</tr>
			@showCustomFields(1, $smarty->get->id ?? '')
	
		</table>

		<div class="card-footer text-end">
			<button type="submit" class="btn btn-primary" name="save_biller" value="{{ $LANG['save_biller'] ?? '' }}">
				<i class="ti ti-check me-1"></i> 
				{{ $LANG['save'] ?? '' }}
			</button>
			<a href="./index.php?module=billers&amp;view=manage" class="btn btn-secondary">
				<i class="ti ti-x me-1"></i>
				{{ $LANG['cancel'] ?? '' }}
			</a>
		</div>
	</div>
</div>

<input type="hidden" name="op" value="edit_biller">
<input type="hidden" name="categorie" value="1" />
@endif

</form>
