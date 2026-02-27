<!--Modified code to display apostrophes in text box output 05/02/2008-Gates-->
<form name="frmpost" action="index.php?module=preferences&amp;view=save&amp;id={{ $smarty->get->id }}" method="post">


@if($smarty->get->action== 'view' )

<div class="card">
	<div class="card-header">
		<h3 class="card-title">{{ $LANG['invoice_preferences'] ?? 'Preference' }} {{ $LANG['details'] ?? 'Details' }}</h3>
		<div class="card-actions">
			<a href="./index.php?module=preferences&amp;view=details&amp;id={{ $preference['pref_id'] }}&amp;action=edit" class="btn btn-primary"><i class="ti ti-edit me-1"></i>{{ $LANG['edit'] ?? '' }}</a>
			<a href="./index.php?module=preferences&amp;view=manage" class="btn btn-secondary"><i class="ti ti-x me-1"></i>{{ $LANG['cancel'] ?? '' }}</a>
		</div>
	</div>
	<div class="card-body">
	<table class="table table-vcenter">
		<tr>	
			<th>Description 
				<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_description" title="{{ $LANG['description'] ?? '' }}">
				<i class="ti ti-help"></i> </a>
			</th>
			<td>{{ $preference['pref_description'] }}</td>
		</tr>
		<tr>
			<th>Currency sign 
				<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_currency_sign" title="{{ $LANG['currency_sign'] ?? '' }}">
				<i class="ti ti-help"></i> </a>
			</th>
			<td>{{ $preference['pref_currency_sign'] }}</td>
		</tr>
		<tr>
			<th>{{ $LANG['currency_code'] ?? '' }}
				<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_currency_code" title="{{ $LANG['currency_code'] ?? '' }}">
				<i class="ti ti-help"></i> </a>
			</th>
			<td>{{ $preference['currency_code'] ?? '' }}</td>
		</tr>
		<tr>
			<th>Invoice heading 
				<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_invoice_heading" title="{{ $LANG['invoice_heading'] ?? '' }}">
				<i class="ti ti-help"></i> </a> 
			</th>
			<td>{{ $preference['pref_inv_heading'] ?? '' }}</td>
		</tr>
		<tr>
			<th>Invoice wording 
				<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_invoice_wording" title="{{ $LANG['invoice_wording'] ?? '' }}">
				<i class="ti ti-help"></i> </a>
			</th>
			<td>{{ $preference['pref_inv_wording'] ?? '' }}</td>
		</tr>
		<tr>
			<th>Invoice detail heading 
				<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_invoice_detail_heading" title="{{ $LANG['invoice_detail_heading'] ?? '' }}">
				<i class="ti ti-help"></i> </a>
			</th>
			<td>{{ $preference['pref_inv_detail_heading'] ?? '' }}</td>
		</tr>
		<tr>
			<th>{{ $LANG['include_online_payment'] ?? '' }}
				<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_invoice_detail_line" title="{{ $LANG['invoice_detail_line'] ?? '' }}">
				<i class="ti ti-help"></i></a>
			</th>
			<td>
				<input type=checkbox name=include_online_payment[] @if(in_array("paypal",explode(",", $preference['include_online_payment'])) )checked@endif value='paypal' DISABLED>{{ $LANG['paypal'] ?? '' }} 
				<input type=checkbox name=include_online_payment[] @if(in_array("eway_merchant_xml",explode(",", $preference['include_online_payment'])) )checked@endif value='eway_merchant_xml' DISABLED>{{ $LANG['eway_merchant_xml'] ?? '' }} 
				<input type=checkbox name=include_online_payment[] @if(in_array("paymentsgateway",explode(",", $preference['include_online_payment'])) )checked@endif value='paymentsgateway' DISABLED>{{ $LANG['paymentsgateway'] ?? '' }} 
			</td>
		</tr>
		<tr>
			<th>Invoice payment method 
				<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_invoice_payment_method" title="{{ $LANG['invoice_payment_method'] ?? '' }}">
				<i class="ti ti-help"></i></a>
			</th>
			<td>{{ $preference['pref_inv_payment_method'] ?? '' }}</td>
		</tr>
		<tr>
			<th>Invoice payment line1 name 
				<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_payment_line1_name" title="{{ $LANG['invoice_payment_line_1_name'] ?? '' }}">
				<i class="ti ti-help"></i></a>
			</th>
			<td>{{ $preference['pref_inv_payment_line1_name'] ?? '' }}</td>
		</tr>
		<tr>
			<th>Invoice payment line1 value 
				<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_payment_line1_value" title="{{ $LANG['invoice_payment_line_1_value'] ?? '' }}">
				<i class="ti ti-help"></i></a>
			</th>
			<td>{{ $preference['pref_inv_payment_line1_value'] ?? '' }}</td>
		</tr>
		<tr>
			<th>Invoice payment line2 name 
				<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_payment_line2_name" title="{{ $LANG['invoice_payment_line_2_name'] ?? '' }}">
				<i class="ti ti-help"></i></a>
			</th>
			<td>{{ $preference['pref_inv_payment_line2_name'] ?? '' }}</td>
		</tr>
		<tr>
			<th>Invoice payment line2 value 
				<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_payment_line2_value" title="{{ $LANG['invoice_payment_line_2_value'] ?? '' }}">
				<i class="ti ti-help"></i></a>
			</th>
			<td>{{ $preference['pref_inv_payment_line2_value'] ?? '' }}</td>
		</tr>
		
        <tr>
        	<th>{{ $LANG['enabled'] ?? '' }} 
				<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_invoice_enabled" title="{{ $LANG['enabled'] ?? '' }}">
				<i class="ti ti-help"></i></a>
			</th>
			<td>{{ $preference['enabled'] }}</td>
		</tr>	
        <tr>
        	<th>{{ $LANG['status'] ?? '' }} 
				<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_status" title="{{ $LANG['status'] ?? '' }}">
				<i class="ti ti-help"></i></a>
			</th>
			<td>{{ $preference['status_wording'] }}</td>
		</tr>	
        <tr>
        	<th>{{ $LANG['invoice_numbering_group'] ?? '' }} 
				<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_invoice_numbering_group" title="{{ $LANG['invoice_numbering_group'] ?? '' }}">
				<i class="ti ti-help"></i></a>
			</th>
			<td>{{ $index_group['pref_description'] }}</td>
		</tr>
		<tr>
			<th>{{ $LANG['language'] ?? '' }} 
				<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_language" title="{{ $LANG['language'] ?? '' }}">
				<i class="ti ti-help"></i></a>
			</th>
			<td>{{ $preference['language'] }}</td>
		</tr>
		<tr>
			<th>{{ $LANG['locale'] ?? '' }} 
				<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_locale" title="{{ $LANG['locale'] ?? '' }}">
				<i class="ti ti-help"></i></a>
			</th>
			<td>{{ $preference['locale'] }}</td>
		</tr>

	</table>
	</div>

	<div class="card-footer">
			<a class="cluetip btn btn-outline-secondary btn-sm" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_what_the" title="{{ $LANG['whats_all_this_inv_pref'] ?? '' }}"><i class="ti ti-help me-1"></i>{{ $LANG['whats_all_this_inv_pref'] ?? '' }}</a>
	</div>
</div>
@endif





@if($smarty->get->action== 'edit' )

<div class="card">
	<div class="card-header">
		<h3 class="card-title">{{ $LANG['edit'] ?? '' }} {{ $LANG['invoice_preferences'] ?? 'Preference' }}</h3>
	</div>
	<div class="card-body">
	<table class="table table-vcenter">
		<tr>
			<th>Description 
				<a 
					class="cluetip"
					href="#"
					rel="index.php?module=documentation&amp;view=view&amp;page=help_required_field"
					title="{{ $LANG['required_field'] ?? '' }}"
				>
					<i class="ti ti-alert-circle text-danger"></i>
				</a>	
				<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_description" title="{{ $LANG['description'] ?? '' }}">
					<i class="ti ti-help"></i>
				</a>
			</th>
			<td><input type="text" class="validate[required]" name='pref_description' value="{{ $preference['pref_description'] ?? '' }}" size="50" /></td>
		</tr>
		<tr>
			<th>Currency sign 
				<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_currency_sign" title="{{ $LANG['currency_sign'] ?? '' }}">
				<i class="ti ti-help"></i> </a>
			</th>
			<td>
                <input type="text" name='pref_currency_sign' value="{{ $preference['pref_currency_sign'] }}" size="15" />
                <a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_currency_sign" title="{{ $LANG['currency_sign'] ?? '' }}">
                   {{ $LANG['currency_sign_non_dollar'] ?? '' }}
                    <img src="./images/common/help-small.png" alt="" /> 
                </a>
            </td>
		</tr>
		<tr>
			<th>{{ $LANG['currency_code'] ?? '' }} 
				<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_currency_code" title="{{ $LANG['currency_code'] ?? '' }}">
				<i class="ti ti-help"></i> </a>
			</th>
			<td>
                <input type="text" name='currency_code' value="{{ $preference['currency_code'] }}" size="15" />
            </td>
		</tr>
		<tr>
			<th>Invoice heading 
				<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_invoice_heading" title="{{ $LANG['invoice_heading'] ?? '' }}">
				<i class="ti ti-help"></i> </a>
			</th>
			<td><input type="text" name='pref_inv_heading' value="{{ $preference['pref_inv_heading'] ?? '' }}" size="50" /></td>
		</tr>
		<tr>
			<th>Invoice wording 
				<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_invoice_wording" title="{{ $LANG['invoice_wording'] ?? '' }}">
				<i class="ti ti-help"></i> </a> 
			</th>
			<td><input type="text" name='pref_inv_wording' value="{{ $preference['pref_inv_wording'] ?? '' }}" size="50" /></td>
		</tr>
		<tr>
			<th>Invoice detail heading 
				<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_invoice_detail_heading" title="{{ $LANG['invoice_detail_heading'] ?? '' }}">
				<i class="ti ti-help"></i> </a>
			</th>
			<td><input type="text" name='pref_inv_detail_heading' value="{{ $preference['pref_inv_detail_heading'] ?? '' }}" size="50" /></td>
		</tr>
		<tr>
			<th>Invoice detail line 
				<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_invoice_detail_line" title="{{ $LANG['invoice_detail_line'] ?? '' }}">
				<i class="ti ti-help"></i></a>
			</th>
			<td><input type="text" name='pref_inv_detail_line' value="{{ $preference['pref_inv_detail_line'] ?? '' }}" size="75" /></td>
		</tr>
		<tr>
			<th>{{ $LANG['include_online_payment'] ?? '' }}
				<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_invoice_detail_line" title="{{ $LANG['invoice_detail_line'] ?? '' }}">
				<i class="ti ti-help"></i></a>
			</th>
			<td>
				<input type=checkbox name=include_online_payment[] @if(in_array("paypal",explode(",", $preference['include_online_payment'])) )checked@endif value='paypal'>{{ $LANG['paypal'] ?? '' }}
				<input type=checkbox name=include_online_payment[] @if(in_array("eway_merchant_xml",explode(",", $preference['include_online_payment'])) )checked@endif value='eway_merchant_xml'>{{ $LANG['eway_merchant_xml'] ?? '' }}
				<input type=checkbox name=include_online_payment[] @if(in_array("paymentsgateway",explode(",", $preference['include_online_payment'])) )checked@endif value='paymentsgateway'>{{ $LANG['paymentsgateway'] ?? '' }}
			</td>
		</tr>
		<tr>
			<th>Invoice payment method 
				<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_invoice_payment_method" title="{{ $LANG['invoice_payment_method'] ?? '' }}">
				<i class="ti ti-help"></i></a>
			</th>
			<td><input type="text" name='pref_inv_payment_method' value="{{ $preference['pref_inv_payment_method'] ?? '' }}" size="50" /></td>
		</tr>
		<tr>
			<th>Invoice payment line1 name 
				<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_payment_line1_name" title="{{ $LANG['invoice_payment_line_1_name'] ?? '' }}">
				<i class="ti ti-help"></i></a>
			</th>
			<td><input type="text" name='pref_inv_payment_line1_name' value="{{ $preference['pref_inv_payment_line1_name'] ?? '' }}" size="50" /></td>
		</tr>
		<tr>
			<th>Invoice payment line1 value 
				<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_payment_line1_value" title="{{ $LANG['invoice_payment_line_1_value'] ?? '' }}">
				<i class="ti ti-help"></i></a>
			</th>
			<td><input type="text" name='pref_inv_payment_line1_value' value="{{ $preference['pref_inv_payment_line1_value'] ?? '' }}" size="50" /></td>
		</tr>
		<tr>
			<th>Invoice payment line2 name 
				<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_payment_line2_name" title="{{ $LANG['invoice_payment_line_2_name'] ?? '' }}">
				<i class="ti ti-help"></i></a>
			</th>
			<td><input type="text" name='pref_inv_payment_line2_name' value="{{ $preference['pref_inv_payment_line2_name'] ?? '' }}" size="50" /></td>
		</tr>
		<tr>
			<th>Invoice payment line2 value 
				<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_payment_line2_value" title="{{ $LANG['invoice_payment_line_2_value'] ?? '' }}">
				<i class="ti ti-help"></i></a>
			</th>
			<td><input type="text" name='pref_inv_payment_line2_value' value="{{ $preference['pref_inv_payment_line2_value'] ?? '' }}" size="50" /></td>
		</tr>
		<tr>
			<th>{{ $LANG['status'] ?? '' }} 
				<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_status" title="{{ $LANG['status'] ?? '' }}">
				<i class="ti ti-help"></i></a>
			</th>
			<td>
				<select name="status">
                @foreach(($status ?? []) as $s)
                    <option @if($s['id'] == ($preference['status'] ?? null)) selected @endif value="{{ $s['id'] }}">{{ $s['status'] }}</option>
                @endforeach
				</select>
			</td>
		</tr>
		<tr>
			<th>{{ $LANG['invoice_numbering_group'] ?? '' }} 
				<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_invoice_numbering_group" title="{{ $LANG['invoice_numbering_group'] ?? '' }}">
				<i class="ti ti-help"></i></a>
			</th>
            <td class="details_screen">
            @if($preferences == null )
                <p><em>{{ $LANG['no_preferences'] ?? '' }}</em></p>
            @else
                <select name="index_group">
                @foreach(($preferences ?? []) as $p)
                    <option @if($s['id'] == $preference['status']) selected @endif value="{{ $p['pref_id'] ?? '' }}">{{ $p['pref_description'] ?? '' }}</option>
                @endforeach
                </select>
            @endif
            
            </td>
    	</tr>	
		<tr>
			<th>{{ $LANG['enabled'] ?? '' }}
				<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_invoice_enabled" title="{{ $LANG['enabled'] ?? '' }}">
				<i class="ti ti-help"></i></a>
			</th>
			<td>
				<select name="pref_enabled">
				<option value="{{ $preference['pref_enabled'] ?? '' }}" selected
				style="font-weight: bold;">{{ $preference['enabled'] ?? '' }}</option>
				<option value="1">{{ $LANG['enabled'] ?? '' }}</option>
				<option value="0">{{ $LANG['disabled'] ?? '' }}</option>
				</select>
			</td>
		</tr>
		<tr>
			<th>{{ $LANG['language'] ?? '' }}  
				<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_language" title="{{ $LANG['language'] ?? '' }}">
				<i class="ti ti-help"></i></a>
			</th>
			<td>
                <select name="language">
                @foreach(($localelist ?? []) as $language => $value)
                    <option @if($language == $s['id']) selected @endif value="{{ $language ?? '' }}">{{ $language ?? '' }}</option>
                @endforeach
                </select>
			</td>
		</tr>
		<tr>
			<th>{{ $LANG['locale'] ?? '' }}
				<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_locale" title="{{ $LANG['locale'] ?? '' }}">
				<i class="ti ti-help"></i></a>
			</th>
			<td>
                <select name="locale">
                @foreach(($localelist ?? []) as $locale => $value)
                    <option @if($locale == $s['id']) selected @endif value="{{ $locale ?? '' }}">{{ $locale ?? '' }}</option>
                @endforeach
                </select>
			</td>
		</tr>
	</table>

	<div class="card-footer text-end">
				<button type="submit" class="btn btn-primary" name="save_preference" value="{{ $LANG['save'] ?? '' }}"><i class="ti ti-check me-1"></i>{{ $LANG['save'] ?? '' }}</button>
				<a href="./index.php?module=preferences&amp;view=manage" class="btn btn-secondary"><i class="ti ti-x me-1"></i>{{ $LANG['cancel'] ?? '' }}</a>
	</div>

	<div class="card-footer">
		<a class="cluetip btn btn-outline-secondary btn-sm" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_what_the" title="{{ $LANG['whats_all_this_inv_pref'] ?? '' }}"><i class="ti ti-help me-1"></i>{{ $LANG['whats_all_this_inv_pref'] ?? '' }}</a>
	</div>
	</div>
</div>


<input type="hidden" name="op" value="edit_preference" />
@endif
</form>
