{*
/*
* Script: details.tpl
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
<br />
<table align="center">
	<tr>
		<td colspan="7" align="center"> </td>
	</tr>
	<tr>
		<td colspan="4" align="center" class="align_center"><i>{$LANG.customer_details}</i></td>
		<td width="10%"></td>
		<td colspan="2" align="center" class="align_center"><i>{$LANG.summary_of_accounts}</i></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.customer_name}</td>
		<td colspan="2">{$customer.name}</td>
		<td colspan="2"></td>
		<td class="details_screen">{$LANG.total_invoices}</td>
		<td style="text-align:right">{$stuff.total|number_format:2}</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.attention_short}
		<a
			rel="index.php?module=documentation&amp;view=view&amp;page=help_customer_contact"
			href="#"
			class="cluetip"
			title="{$LANG.customer_contact}"
		>
		<img src="./images/common/help-small.png" alt="" /></a>
		
		</td>
		<td colspan="2">{$customer.attention|htmlsafe}</td>
		<td colspan="2"></td>
		<td class="details_screen"><a href="index.php?module=payments&view=manage&c_id={$customer.id|urlencode}">{$LANG.total_paid}</a></td>
		<td style="text-align:right">{$stuff.paid|number_format:2}</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.street}</td>
		<td colspan="2">{$customer.street_address|htmlsafe}</td>
		<td colspan="2"></td>
		<td class="details_screen">{$LANG.total_owing}</td>
		<td style="text-align:right"><u>{$stuff.owing|number_format:2}</u></td>
	</tr>
	<tr>
		<td class="details_screen" NOWRAP>{$LANG.street2}
			<a
				class="cluetip"
				href="#"
				rel="index.php?module=documentation&amp;view=view&amp;page=help_street2"
				title="{$LANG.street2}"
			> <img src="./images/common/help-small.png" alt="" /></a>
		</td>
		<td>{$customer.street_address2|htmlsafe}</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.city}</td>
		<td>{$customer.city|htmlsafe}</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.zip}</td>
		<td>{$customer.zip_code|htmlsafe}</td>
		<td class="details_screen">{$LANG.phone}</td>
		<td>{$customer.phone|htmlsafe}</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.state}</td>
		<td>{$customer.state|htmlsafe}</td>
		<td class="details_screen" NOWRAP>{$LANG.mobile_phone}</td>
		<td>{$customer.mobile_phone|htmlsafe}</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.country}</td>
		<td>{$customer.country|htmlsafe}</td>
		<td class="details_screen">{$LANG.fax}</td>
		<td>{$customer.fax|htmlsafe}</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.enabled}</td>
		<td>{$customer.wording_for_enabled|htmlsafe}</td>
		<td class="details_screen">{$LANG.email}</td>
		<td>{$customer.email|htmlsafe}</td>
	</tr>
</table>
<br />
<div id="tabs_customer">
	<ul class="anchors">
		<li><a href="#section-1" target="_top">{$LANG.custom_fields}</a></li>
		<li><a href="#section-2" target="_top">{$LANG.credit_card_details}</a></li>
		<li><a href="#section-3" target="_top">{$LANG.customer} {$LANG.invoice_listings}</a></li>
		<li><a href="#section-4" target="_top">{$LANG.notes}</a></li>
	</ul>
	<div id="section-1" class="fragment">
		<p>
			<table>
				<tr>
					<td class="details_screen">{$customFieldLabel.customer_cf1|htmlsafe}
 						<a
							class="cluetip"
							href="#"
							rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields"
							title="{$LANG.Custom_Fields}"
						> 
						<img src="./images/common/help-small.png" alt="" /></a>
					</td>
					<td>{$customer.custom_field1|htmlsafe}</td>
				</tr>
				<tr>
					<td class="details_screen">{$customFieldLabel.customer_cf2|htmlsafe}
					 	<a
							class="cluetip"
							href="#"
							rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields"
							title="{$LANG.Custom_Fields}"
						> 
						<img src="./images/common/help-small.png" alt="" /></a>
					</td>
					<td>{$customer.custom_field2|htmlsafe}</td>
				</tr>
				<tr>
					<td class="details_screen">{$customFieldLabel.customer_cf3|htmlsafe}
					 	<a
							class="cluetip"
							href="#"
							rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields"
							title="{$LANG.Custom_Fields}"
						> 
						<img src="./images/common/help-small.png" alt="" /></a>
					</td>
					<td>{$customer.custom_field3|htmlsafe}</td>
				</tr>
				<tr>
					<td class="details_screen">{$customFieldLabel.customer_cf4|htmlsafe}
					 	<a
							class="cluetip"
							href="#"
							rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields"
							title="{$LANG.Custom_Fields}"
						> 
						<img src="./images/common/help-small.png" alt="" /></a>
 					</td>
					<td>{$customer.custom_field4|htmlsafe}</td>
				</tr>
				{*
					{showCustomFields categorieId="2" itemId=$smarty.get.customer }
				*}
			</table>
		</p>
	</div>

	<div id="section-2" class="fragment">
		<p>
			<table>

			<tr>
				<td class="details_screen">{$LANG.credit_card_holder_name}</td>
				<td>
					{$customer.credit_card_holder_name|htmlsafe}
				</td>
			</tr>
			<tr>
				<td class="details_screen">{$LANG.credit_card_number}</td>
				<td>
					{$LANG.credit_card_number_encrypted|htmlsafe}
					{* $customer.credit_card_number *}
				</td>
			</tr>
			<tr>
				<td class="details_screen">{$LANG.credit_card_expiry_month}</td>
				<td>
					{$customer.credit_card_expiry_month|htmlsafe}
				</td>
			</tr>
			<tr>
				<td class="details_screen">{$LANG.credit_card_expiry_year}</td>
				<td>
					{$customer.credit_card_expiry_year|htmlsafe}
				</td>
			</tr>
		</table>
		</p>
	</div>
	<div id="section-3" class="fragment">
		<p >
		<table width="100%" align="center">
			<tr class="sortHeader">

				<th class="sortable">{$LANG.id}</th>
				<th class="sortable_rt">{$LANG.total}</th>
				<th class="sortable_rt">{$LANG.paid}</th>
				<th class="sortable_rt">{$LANG.owing}</th>
				<th class="sortable_rt">{$LANG.date_created}</th>

			</tr>
		
			{foreach from=$invoices item=invoice}
	
			<tr class="index_table">
				<td class="details_screen"><a href="index.php?module=invoices&amp;view=quick_view&id={$invoice.id|urlencode}">{$invoice.id|htmlsafe}</a></td>
				<td style="text-align:right" class="details_screen">{$invoice.total|number_format:2}</td>
				<td style="text-align:right" class="details_screen">{$invoice.paid|number_format:2}</td>
				<td style="text-align:right" class="details_screen">{$invoice.owing|number_format:2}</td>
				<td style="text-align:right" class="details_screen">{$invoice.date|htmlsafe}</td>
			</tr>

			{/foreach}
		</table>	
		</p>
	</div>
	<div id="section-4" class="fragment">

		<p>
			<div id="left">
				{$customer.notes|outhtml}
			</div>
		</p>
	</div>
</div>


<br />
<table class="buttons" align="center">
    <tr>
        <td>
            <a href="./index.php?module=customers&amp;view=details&amp;id={$customer.id|urlencode}&amp;action=edit" class="positive">
                <img src="./images/common/tick.png" alt="" />
                {$LANG.edit}
            </a>
    
        </td>
    </tr>
</table>
{/if}

{if $smarty.get.action == 'edit' }

<form name="frmpost" action="index.php?module=customers&amp;view=save&amp;id={$customer.id|urlencode}" method="post" id="frmpost" onsubmit="return checkForm(this);">
<br />
<table align="center">
	<tr>
		<td class="details_screen">{$LANG.customer_name}
		<a 
				class="cluetip"
				href="#"
				rel="index.php?module=documentation&amp;view=view&amp;page=help_required_field"
				title="{$LANG.Required_Field}"
		>
		<img src="./images/common/required-small.png" alt="" /></a>
		</td>
		<td><input type="text" name="name" value="{$customer.name|htmlsafe}" size="50" id="name" class="validate[required]" /></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.attention_short}
		<a
			rel="index.php?module=documentation&amp;view=view&amp;page=help_customer_contact"
			href="#"
			class="cluetip"
			title="{$LANG.customer_contact}"
		>
		 <img src="./images/common/help-small.png" alt="" /></a>
		</td>
		<td><input type="text" name="attention" value="{$customer.attention|htmlsafe}" size="50" /></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.street}</td>
		<td><input type="text" name="street_address" value="{$customer.street_address|htmlsafe}" size="50" /></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.street2}
			<a
				class="cluetip"
				href="#"
				rel="index.php?module=documentation&amp;view=view&amp;page=help_street2"
				title="{$LANG.street2}"
			> 
				<img src="./images/common/help-small.png" alt="" />
			</a>
		</td>
		<td><input type="text" name="street_address2" value="{$customer.street_address2|htmlsafe}" size="50" /></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.city}</td>
		<td><input type="text" name="city" value="{$customer.city|htmlsafe}" size="50" /></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.zip}</td>
		<td><input type="text" name="zip_code" value="{$customer.zip_code|htmlsafe}" size="50" /></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.state}</td>
		<td><input type="text" name="state" value="{$customer.state|htmlsafe}" size="50" /></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.country}</td>
		<td><input type="text" name="country" value="{$customer.country|htmlsafe}" size="50" /></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.phone}</td>
		<td><input type="text" name="phone" value="{$customer.phone|htmlsafe}" size="50" /></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.mobile_phone}</td>
		<td><input type="text" name="mobile_phone" value="{$customer.mobile_phone|htmlsafe}" size="50" /></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.fax}</td>
		<td><input type="text" name="fax" value="{$customer.fax|htmlsafe}" size="50" /></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.email}</td>
		<td>
			<input type="text" name="email" value="{$customer.email|htmlsafe}" size="50" /></td
	></tr>
	<tr>
		<td class="details_screen">{$LANG.credit_card_holder_name}</td>
		<td>
			<input
				type="text" name="credit_card_holder_name"
			 	value="{$customer.credit_card_holder_name|htmlsafe}" size="25"
			 />
		</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.credit_card_number}</td>
		<td>
					{$LANG.credit_card_number_encrypted}
		</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.credit_card_number_new}</td>
		<td>
			<input
				type="text" name="credit_card_number_new"
			 	value="{$customer.credit_card_holder_name_new|htmlsafe}" size="25"
			 />
		</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.credit_card_expiry_month}</td>
		<td>
			<input
				type="text" name="credit_card_expiry_month"
			 	value="{$customer.credit_card_expiry_month|htmlsafe}" size="5"
			 />
		</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.credit_card_expiry_year}</td>
		<td>
			<input
				type="text" name="credit_card_expiry_year"
			 	value="{$customer.credit_card_expiry_year|htmlsafe}" size="5"
			 />
		</td>
	</tr>
	<tr>
		<td class="details_screen">{$customFieldLabel.customer_cf1|htmlsafe}
 			<a
				class="cluetip"
				href="#"
				rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields"
				title="{$LANG.Custom_Fields}"
			> 
		 <img src="./images/common/help-small.png" alt="" /></a>
		</td>
		<td>
			<input type="text" name="custom_field1" value="{$customer.custom_field1|htmlsafe}" size="50" /></td
	></tr>
	<tr>
		<td class="details_screen">{$customFieldLabel.customer_cf2|htmlsafe}
 			<a
				class="cluetip"
				href="#"
				rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields"
				title="{$LANG.Custom_Fields}"
			> 
		 <img src="./images/common/help-small.png" alt="" /></a>
		</td>
		<td>
			<input type="text" name="custom_field2" value="{$customer.custom_field2|htmlsafe}" size="50" /></td
	></tr>
	<tr>
		<td class="details_screen">{$customFieldLabel.customer_cf3|htmlsafe} 
 			<a
				class="cluetip"
				href="#"
				rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields"
				title="{$LANG.Custom_Fields}"
			> 
		<img src="./images/common/help-small.png" alt="" /></a>
		</td>
		<td>
			<input type="text" name="custom_field3" value="{$customer.custom_field3|htmlsafe}" size="50" /></td
	></tr>
	<tr>
		<td class="details_screen">{$customFieldLabel.customer_cf4|htmlsafe}
 			<a
				class="cluetip"
				href="#"
				rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields"
				title="{$LANG.Custom_Fields}"
			> 
		 <img src="./images/common/help-small.png" alt="" /></a>
		</td>
		<td>
			<input type="text" name="custom_field4" value="{$customer.custom_field4|htmlsafe}" size="50" /></td
	></tr>
	<tr>
		<td class="details_screen">{$LANG.notes}</td>
		<td><textarea  name="notes"  class="editor" rows="8" cols="50">{$customer.notes|outhtml}</textarea></td>
	</tr>
	{*
		{showCustomFields categorieId="2" itemId=$smarty.get.customer }
	*}
	<tr>
		<td class="details_screen">{$LANG.enabled}</td>
		<td>
			{html_options name=enabled options=$enabled selected=$customer.enabled}
		</td>
	</tr>
</table>


<br />

<table class="buttons" align="center">
    <tr>
        <td>
            <button type="submit" class="positive" name="save_customer" value="{$LANG.save_customer}">
                <img class="button_img" src="./images/common/tick.png" alt="" /> 
                {$LANG.save}
            </button>

            <input type="hidden" name="op" value="edit_customer">
        
            <a href="./index.php?module=customers&amp;view=manage" class="negative">
                <img src="./images/common/cross.png" alt="" />
                {$LANG.cancel}
            </a>
    
        </td>
    </tr>
</table>

</form>
{/if}
