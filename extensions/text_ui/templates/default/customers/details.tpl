{*
/*
* Script: details.tpl
* 	 Customer details template
*
* Authors:
*	 Justin Kelly, Nicolas Ruflin, Ap.Muthu
*
* Last edited:
* 	 2008-01-03
*
* License:
*	 GPL v2 or above
*
* Website:
*	http://www.simpleinvoices.org
*/
*}

{if $smarty.get.action == 'view' }
<b>{$LANG.customer} :: <a href="index.php?module=customers&amp;view=details&amp;id={$customer.id}&amp;action=edit">{$LANG.edit}</a>
</b>
<hr />
<table >
	<tr>
		<td colspan="2" align="center" class="align_center"><i>{$LANG.customer_details}</i></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.customer} {$LANG.id}</td>
		<td>{$customer.id}</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.customer_name}</td>
		<td colspan="2">{$customer.name}</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.attention_short}</td>
		<td colspan="2">{$customer.attention}</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.street}</td>
		<td>{$customer.street_address}</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.street2}</td>
		<td>{$customer.street_address2}</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.city}</td>
		<td>{$customer.city}</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.zip}</td>
		<td>{$customer.zip_code}</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.state}</td>
		<td>{$customer.state}</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.fax}</td>
		<td>{$customer.fax}</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.enabled}</td>
		<td>{$customer.wording_for_enabled}</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.phone}</td>
		<td>{$customer.phone}</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.mobile_phone}</td>
		<td>{$customer.mobile_phone}</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.fax}</td>
		<td>{$customer.fax}</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.email}</td>
		<td>{$customer.email}</td>
	</tr>
</table>

			<table>
				<tr>
					<td class="details_screen">{$customFieldLabel.customer_cf1}</td>
					<td>{$customer.custom_field1}</td>
				</tr>
				<tr>
					<td class="details_screen">{$customFieldLabel.customer_cf2}</td>
					<td>{$customer.custom_field2}</td>
				</tr>
				<tr>
					<td class="details_screen">{$customFieldLabel.customer_cf3}</td>
					<td>{$customer.custom_field3}</td>
				</tr>
				<tr>
					<td class="details_screen">{$customFieldLabel.customer_cf4}</td>
					<td>{$customer.custom_field4}</td>
				</tr>

			</table>
	<i>{$LANG.summary_of_accounts}</i>
	<table>
	<tr>
		<td class="details_screen">{$LANG.total_invoices}</td>
		<td style="text-align:right">{$stuff.total|number_format:2}</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.total_paid}</td>
		<td style="text-align:right">{$stuff.paid|number_format:2}</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.total_owing}</td>
		<td style="text-align:right"><u>{$stuff.owing|number_format:2}</u></td>
	</tr>	
	</table>
<br />
		<b>{$LANG.invoice_listings}</b>
		<table width="100%" align="center">
			<tr class="sortHeader">

				<th class="sortable">{$LANG.id}</th>
				<th class="sortable_rt">{$LANG.total}</th>
				<th class="sortable_rt">{$LANG.paid}</th>
				<th class="sortable_rt">{$LANG.owing}</th>

			</tr>
		
			{foreach from=$invoices item=invoice}
	
			<tr class="index_table">
				<td class="details_screen"><a href="index.php?module=invoices&amp;view=quick_view&amp;invoice={$invoice.id}">{$invoice.id}</a></td>
				<td style="text-align:right" class="details_screen">{$invoice.total|number_format:2}</td>
				<td style="text-align:right" class="details_screen">{$invoice.paid|number_format:2}</td>
				<td style="text-align:right" class="details_screen">{$invoice.owing|number_format:2}</td>
			</tr>

			{/foreach}
		</table>	


<hr />
<a href="index.php?module=customers&amp;view=details&amp;id={$customer.id}&amp;action=edit">{$LANG.edit}</a>
{/if}

{if $smarty.get.action == 'edit' }

<form name="frmpost"
	action="index.php?module=customers&amp;view=save&amp;id={$customer.id}"
	method="post">

<div id="top"><h3>{$LANG.customer_edit}</h3></div>
<hr />
<table >
	<tr>
		<td class="details_screen">{$LANG.customer} {$LANG.id}</td>
		<td>{$customer.id}</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.customer_name}</td>
		<td><input type="text" name="name" value="{$customer.name|regex_replace:"/[\\\]/":""}" size="25" /></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.attention_short}</td>
		<td><input type="text" name="attention" value="{$customer.attention}" size="25" /></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.street}</td>
		<td><input type="text" name="street_address" value="{$customer.street_address}" size="25" /></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.street2}</td>
		<td><input type="text" name="street_address2" value="{$customer.street_address2}" size="25" /></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.city}</td>
		<td><input type="text" name="city" value="{$customer.city}" size="25" /></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.zip}</td>
		<td><input type="text" name="zip_code" value="{$customer.zip_code}" size="25" /></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.state}</td>
		<td><input type="text" name="state" value="{$customer.state}" size="25" /></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.country}</td>
		<td><input type="text" name="country" value="{$customer.country}" size="25" /></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.phone}</td>
		<td><input type="text" name="phone" value="{$customer.phone}" size="25" /></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.mobile_phone}</td>
		<td><input type="text" name="mobile_phone" value="{$customer.mobile_phone}" size="25" /></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.fax}</td>
		<td><input type="text" name="fax" value="{$customer.fax}" size="25" /></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.email}</td>
		<td><input type="text" name="email" value="{$customer.email}" size="25" /></td>
	</tr>
	<tr>
		<td class="details_screen">{$customFieldLabel.customer_cf1}</td>
		<td><input type="text" name="custom_field1" value="{$customer.custom_field1}" size="25" /></td>
	</tr>
	<tr>
		<td class="details_screen">{$customFieldLabel.customer_cf2}</td>
		<td><input type="text" name="custom_field2" value="{$customer.custom_field2}" size="25" /></td>
	</tr>
	<tr>
		<td class="details_screen">{$customFieldLabel.customer_cf3}</td>
		<td><input type="text" name="custom_field3" value="{$customer.custom_field3}" size="25" /></td>
	</tr>
	<tr>
		<td class="details_screen">{$customFieldLabel.customer_cf4}</td>
		<td><input type="text" name="custom_field4" value="{$customer.custom_field4}" size="25" /></td>
	</tr>
</table>


<hr />
<input type="submit" name="cancel" value="{$LANG.cancel}" />
<input type="submit" name="save_customer" value="{$LANG.save_customer}" />
<input type="hidden" name="op" value="edit_customer" />


</form>
{/if}
