{*
/*
* Script: /simple/extensions/matts_luxury_pack/templates/default/customers/details.tpl
* 	 Customer details template
*
* License:
*	 GPL v3 or above
*
* Website:
*	http://www.simpleinvoices.org
*/
*}

{if $smarty.get.action == 'view' }
<div class="si_form si_form_view" id="si_form_cust">

	<div class="si_cust_info">
		<table>
			<tr>
				<th>{$LANG.customer_name}</th>
				<td>{$customer.name}</td>
				<td class="td_sep"></td>
				<th>{$LANG.phone}</th>
				<td>{$customer.phone|htmlsafe}</td>
			</tr>
			<tr>
				<th>{$LANG.attention_short}</th>
				<td>{$customer.attention|htmlsafe}</td>
				<td class="td_sep"></td>
				<th>{$LANG.mobile_phone}</th>
				<td>{$customer.mobile_phone|htmlsafe}</td>
			</tr>
			<tr>
				<th>{$LANG.street}</th>
				<td>{$customer.street_address|htmlsafe}</td>
				<td class="td_sep"></td>
				<th>{$LANG.fax}</th>
				<td>{$customer.fax|htmlsafe}</td>
			</tr>
			<tr>
				<th>{$LANG.street2}</th>
				<td>{$customer.street_address2|htmlsafe}</td>
				<td class="td_sep"></td>
				<th>{$LANG.email}</th>
				<td><a href="mailto:{$customer.email|htmlsafe}">{$customer.email|htmlsafe}</a></td>
			</tr>
			<tr>
				<th>{$LANG.city}</th>
				<td>{$customer.city|htmlsafe}</td>
				<td colspan="3"></td>
			</tr>
			<tr>
				<th>{$LANG.zip}</th>
				<td>{$customer.zip_code|htmlsafe}</td>
				<td class="td_sep"></td>
				<th>{$customFieldLabel.customer_cf1}</th>
				<td>{$customer.custom_field1|htmlsafe}</td>
			</tr>
			<tr>
				<th>{$LANG.state}</th>
				<td>{$customer.state|htmlsafe}</td>
				<td class="td_sep"></td>
				<th>{$customFieldLabel.customer_cf2}</th>
				<td>{$customer.custom_field2|htmlsafe}</td>
			</tr>
			<tr>
				<th>{$LANG.country}</th>
				<td>{$customer.country|htmlsafe}</td>
				<td class="td_sep"></td>
				<th>{$customFieldLabel.customer_cf3}</th>
				<td>{$customer.custom_field3|htmlsafe}</td>
			</tr>
	{if $defaults.price_list}
		<tr>
			<th>{$LANG.price_list}</th>
			<td>{if $customer.price_list==0}default (1){else}{$customer.price_list|htmlsafe}{/if}</td>
		</tr>
	{/if}
		<tr>
				<th>{$LANG.enabled}</th>
				<td>{$customer.wording_for_enabled|htmlsafe}</td>
				<td class="td_sep"></td>
				<th>{$customFieldLabel.customer_cf4}</th>
				<td>{$customer.custom_field4|htmlsafe}</td>
			</tr>
		</table>
	</div>

<div id="tabs_customer">
	<ul class="anchors">
		<li><a href="#section-1" target="_top">{$LANG.summary_of_accounts}</a></li>
		<li><a href="#section-2" target="_top">{$LANG.credit_card_details}</a></li>
		<li><a href="#section-3" target="_top">{$LANG.unpaid_invoices}</a></li>
		<li><a href="#section-4" target="_top">{$LANG.customer} {$LANG.invoice_listings}</a></li>
		<li><a href="#section-5" target="_top">{$LANG.notes}</a></li>
	</ul>
	<div id="section-1" class="fragment">
		<div class="si_cust_account">
		    <table>
			<tr>
			    <th>{$LANG.total_invoices}</th>
			    <td class="si_right">{$stuff.total|number_format:2}</td>
			</tr>
			<tr>
			    <th><a href="index.php?module=payments&view=manage&c_id={$customer.id|urlencode}">{$LANG.total_paid}</a></th>
			    <td class="si_right">{$stuff.paid|number_format:2}</td>
			</tr>
			<tr>
			    <th>{$LANG.total_owing}</th>
			    <td class="si_right"><u>{$stuff.owing|number_format:2}</u></td>
			</tr>
		    </table>
		</div>
	</div>

	<div id="section-2" class="fragment">

		<div class="si_cust_card">
		    <table>
			<tr>
			    <th>{$LANG.credit_card_holder_name}</th>
			    <td>{$customer.credit_card_holder_name|htmlsafe}</td>
			</tr>
			<tr>
			    <th>{$LANG.credit_card_number}</th>
			    <td>{$customer.credit_card_number|regex_replace:'/^............/':"************"|htmlsafe}</td>
			</tr>
			<tr>
			    <th>{$LANG.credit_card_expiry_month}</th>
			    <td>{$customer.credit_card_expiry_month|htmlsafe}</td>
			</tr>
			<tr>
			    <th>{$LANG.credit_card_expiry_year}</th>
			    <td>{$customer.credit_card_expiry_year|htmlsafe}</td>
			</tr>
		    </table>
		</div>
        </div>
	
	<div id="section-3" class="fragment">
		<div class="si_cust_invoices">
		    <table>
			<thead>
			    <tr class="tr_head">
				<th class="first">
			<!--6 Payment -->{$LANG.actions}
				</th>
				<th>{$LANG.id}</th>
				<th>{$LANG.date_created}</th>
				<th>{$LANG.total}</th>
				<th>{$LANG.paid}</th>
				<th>{$LANG.owing}</th>
			    </tr>
			</thead>
			<tbody>
			{foreach from=$invoices item=invoice}
{if $invoice.status > 0}
	{if $invoice.owing != 0}
			    <tr class="index_table">
				<td class="first">
			<!--6 Payment --><a title="{$LANG.process_payment_for} {$invoice.preference} {$invoice.id}"  href='index.php?module=payments&view=process&id={$invoice.id}&op=pay_selected_invoice'><img src='images/common/money_dollar.png' class='action' /></a>
				<a href="index.php?module=invoices&amp;view=quick_view&id={$invoice.id|urlencode}"><img src='images/common/view.png' class='action' /></a>
				</td>
				<td ><a href="index.php?module=invoices&amp;view=quick_view&id={$invoice.id|urlencode}">{$invoice.pref_inv_wording|htmlsafe} {$invoice.index_id|htmlsafe}</a></td>
				<td>{$invoice.date|htmlsafe}</td>
				<td>{$invoice.total|number_format:2}</td>
				<td>{$invoice.paid|number_format:2}</td>
				<td>{$invoice.owing|number_format:2}</td>
			    </tr>
	{/if}
{/if}
			{/foreach}
			</tbody>
		    </table>
		</div>
	</div>
	<div id="section-4" class="fragment">
		<div class="si_cust_invoices">
		    <table>
			<thead>
			    <tr class="tr_head">
				<th class="first">{$LANG.id}</th>
				<th>{$LANG.date_created}</th>
				<th>{$LANG.total}</th>
				<th>{$LANG.paid}</th>
				<th>{$LANG.owing}</th>
			    </tr>
			</thead>
			<tbody>
			{foreach from=$invoices item=invoice}
			    <tr class="index_table">
				<td class="first"><a href="index.php?module=invoices&amp;view=quick_view&id={$invoice.id|urlencode}">{$invoice.pref_inv_wording|htmlsafe} {$invoice.index_id|htmlsafe}</a></td>
				<td>{$invoice.date|htmlsafe}</td>
				<td>{$invoice.total|number_format:2}</td>
{if $invoice.status > 0}
				<td>{$invoice.paid|number_format:2}</td>
	{if $invoice.owing != 0}
				<td>{$invoice.owing|number_format:2}</td>
	{else}
				<td>&nbsp;</td>
	{/if}
{else}
				<td colspan="2">&nbsp;</td>
{/if}
			    </tr>
			{/foreach}
			</tbody>
		    </table>
		</div>
	</div>
	<div id="section-5" class="fragment">

		<div class="si_cust_notes">
		    {$customer.notes|outhtml}
		</div>
    	</div>
</div>

<div class="si_toolbar si_toolbar_form">
	<a href="./index.php?module=customers&amp;view=details&amp;id={$customer.id|urlencode}&amp;action=edit" class="positive"><img src="./images/common/tick.png" alt="tick" />{$LANG.edit}</a>
</div>
</div>
{/if}


{* ##################################################################################################### *}



{if $smarty.get.action == 'edit' }

<form name="frmpost" action="index.php?module=customers&amp;view=save&amp;id={$customer.id|urlencode}" method="post" id="frmpost" onsubmit="return checkForm(this);">
<div class="si_form" id="si_form_cust_edit">

	<table align="center">
		<tr>
			<th>{$LANG.customer_name}
				<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_required_field" title="{$LANG.required_field}">
					<img src="./images/common/required-small.png" alt="required" />
				</a>
			</th>
			<td><input type="text" name="name" value="{$customer.name|htmlsafe}" size="50" id="name" class="validate[required]" /></td>
		</tr>
		<tr>
			<th>{$LANG.attention_short}
				<a rel="index.php?module=documentation&amp;view=view&amp;page=help_customer_contact" href="#" class="cluetip" title="{$LANG.customer_contact}">
					<img src="./images/common/help-small.png" alt="help" />
				</a>
			</th>
			<td><input type="text" name="attention" value="{$customer.attention|htmlsafe}" size="50" /></td>
		</tr>
		<tr>
			<th>{$LANG.street}</th>
			<td><input type="text" name="street_address" value="{$customer.street_address|htmlsafe}" size="50" /></td>
		</tr>
		<tr>
			<th>{$LANG.street2}
				<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_street2" title="{$LANG.street2}"> 
					<img src="./images/common/help-small.png" alt="help" />
				</a>
			</th>
			<td><input type="text" name="street_address2" value="{$customer.street_address2|htmlsafe}" size="50" /></td>
		</tr>
		<tr>
			<th>{$LANG.city}</th>
			<td><input type="text" name="city" value="{$customer.city|htmlsafe}" size="50" /></td>
		</tr>
		<tr>
			<th>{$LANG.zip}</th>
			<td><input type="text" name="zip_code" value="{$customer.zip_code|htmlsafe}" size="50" /></td>
		</tr>
		<tr>
			<th>{$LANG.state}</th>
			<td><input type="text" name="state" value="{$customer.state|htmlsafe}" size="50" /></td>
		</tr>
		<tr>
			<th>{$LANG.country}</th>
			<td><input type="text" name="country" value="{$customer.country|htmlsafe}" size="50" /></td>
		</tr>
		<tr>
			<th>{$LANG.phone}</th>
			<td><input type="text" name="phone" value="{$customer.phone|htmlsafe}" size="50" /></td>
		</tr>
		<tr>
			<th>{$LANG.mobile_phone}</th>
			<td><input type="text" name="mobile_phone" value="{$customer.mobile_phone|htmlsafe}" size="50" /></td>
		</tr>
		<tr>
			<th>{$LANG.fax}</th>
			<td><input type="text" name="fax" value="{$customer.fax|htmlsafe}" size="50" /></td>
		</tr>
		<tr>
			<th>{$LANG.email}</th>
			<td>
				<input type="text" name="email" value="{$customer.email|htmlsafe}" size="50" /></td
		></tr>
		<tr>
			<th>{$LANG.credit_card_holder_name}</th>
			<td>
				<input
					type="text" name="credit_card_holder_name"
					value="{$customer.credit_card_holder_name|htmlsafe}" size="25"
				 />
			</td>
		</tr>
		<tr>
			<th>{$LANG.credit_card_number}</th>
			<td>
						{$LANG.credit_card_number_encrypted}
			</td>
		</tr>
		<tr>
			<th>{$LANG.credit_card_number_new}</th>
			<td>
				<input
					type="text" name="credit_card_number_new"
					value="{$customer.credit_card_holder_name_new|htmlsafe}" size="25"
				 />
			</td>
		</tr>
		<tr>
			<th>{$LANG.credit_card_expiry_month}</th>
			<td>
				<input
					type="text" name="credit_card_expiry_month"
					value="{$customer.credit_card_expiry_month|htmlsafe}" size="5"
				 />
			</td>
		</tr>
		<tr>
			<th>{$LANG.credit_card_expiry_year}</th>
			<td>
				<input
					type="text" name="credit_card_expiry_year"
					value="{$customer.credit_card_expiry_year|htmlsafe}" size="5"
				 />
			</td>
		</tr>
		<tr>
			<th>{$customFieldLabel.customer_cf1|htmlsafe}
				<a
					class="cluetip"
					href="#"
					rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields"
					title="{$LANG.custom_fields}"
				> 
			 <img src="./images/common/help-small.png" alt="help" /></a>
			</th>
			<td>
				<input type="text" name="custom_field1" value="{$customer.custom_field1|htmlsafe}" size="50" />
			</td>
		</tr>
		<tr>
			<th>{$customFieldLabel.customer_cf2|htmlsafe}
				<a
					class="cluetip"
					href="#"
					rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields"
					title="{$LANG.custom_fields}"
				> 
			 <img src="./images/common/help-small.png" alt="help" /></a>
			</th>
			<td>
				<input type="text" name="custom_field2" value="{$customer.custom_field2|htmlsafe}" size="50" />
			</td>
		</tr>
		<tr>
			<th>{$customFieldLabel.customer_cf3|htmlsafe} 
				<a
					class="cluetip"
					href="#"
					rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields"
					title="{$LANG.custom_fields}"
				> 
			<img src="./images/common/help-small.png" alt="help" /></a>
			</th>
			<td>
				<input type="text" name="custom_field3" value="{$customer.custom_field3|htmlsafe}" size="50" />
			</td>
		</tr>
		<tr>
			<th>{$customFieldLabel.customer_cf4|htmlsafe}
				<a
					class="cluetip"
					href="#"
					rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields"
					title="{$LANG.custom_fields}"
				> 
			 <img src="./images/common/help-small.png" alt="help" /></a>
			</th>
			<td>
				<input type="text" name="custom_field4" value="{$customer.custom_field4|htmlsafe}" size="50" />
			</td>
		</tr>
	{if $defaults.price_list}
		<tr>
			<th>{$LANG.price_list}</th>
			<td>
				<select name="price_list">
					<option value="1"{if $customer.price_list<2 || !$customer.price_list} selected="selected"{/if}>1</option>
					<option value="2"{if $customer.price_list==2} selected="selected"{/if}>2</option>
					<option value="3"{if $customer.price_list==3} selected="selected"{/if}>3</option>
					<option value="4"{if $customer.price_list==4} selected="selected"{/if}>4</option>
				</select>
			</td>
		</tr>
	{/if}
		<tr>
			<th>{$LANG.notes}</th>
			<td><textarea  name="notes"  class="editor" rows="8" cols="50">{$customer.notes|outhtml}</textarea></td>
		</tr>
		{*
			{showCustomFields categorieId="2" itemId=$smarty.get.customer }
		*}
		<tr>
			<th>{$LANG.enabled}</th>
			<td>
				{html_options name=enabled options=$enabled selected=$customer.enabled}
			</td>
		</tr>
	</table>
		
	<div class="si_toolbar si_toolbar_form">
		<button type="submit" class="positive" name="save_customer" value="{$LANG.save_customer}"><img class="button_img" src="./images/common/tick.png" alt="tick" />{$LANG.save}</button>
		<a id="cancelEditCustomer" href="./index.php?module=customers&amp;view=manage" class="negative"><img src="./images/common/cross.png" alt="cross" />{$LANG.cancel}</a>
	</div>

</div>

<input type="hidden" name="op" value="edit_customer">
</form>
{/if}
