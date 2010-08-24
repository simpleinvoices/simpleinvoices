{*
/*
* Script: add.tpl
* 	 Customers add template
*
* Authors:
*	 Justin Kelly, Nicolas Ruflin
*
* Last edited:
* 	 2007-07-18
*
* License:
*	 GPL v2 or above
*/
*}

{* if customer is updated or saved.*} 

{if $smarty.post.name != "" && $smarty.post.customer != null } 
	{include file="../templates/default/customers/save.tpl"}

{else}
{* if  name was inserted *} 
	{if $smarty.post.customer !=null} 
		<div class="validation_alert"><img src="./images/common/important.png" alt="" />
		You must enter a Customer name</div>
		<hr />
	{/if}
<form name="frmpost" action="index.php?module=customers&view=add" method="post" id="frmpost" onSubmit="return checkForm(this);">
<h3>{$LANG.customer_add}</h3>
<hr />
<table align="center">
	<tr>
		<td class="details_screen">{$LANG.customer_name}</td>
		<td><input type="text" name="name" value="{$smarty.post.name}" size="15" id="name" class="required" onblur="checkField(this);" /></td>
	</tr>
	</tr>
		<td class="details_screen">{$LANG.customer_contact}</td>
		<td><input type="text" name="attention" value="{$smarty.post.attention}" size="15" /></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.street}</td>
		<td><input type="text" name="street_address" value="{$smarty.post.street_address}" size="15" /></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.street2}</td>
		<td><input type="text" name="street_address2" value="{$smarty.post.street_address2}" size="15" /></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.city}</td>
		<td><input type="text" name="city" value="{$smarty.post.city}" size="15" /></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.state}</td>
		<td><input type="text" name="state" value="{$smarty.post.state}" size="15" /></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.zip}</td>
		<td><input type="text" name="zip_code" value="{$smarty.post.zip_code}" size="15" /></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.country}</td>
		<td><input type="text" name="country" value="{$smarty.post.country}" size="15" /></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.phone}</td>
		<td><input type="text" name="phone" value="{$smarty.post.phone}" size="15" /></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.mobile_phone}</td>
		<td><input type="text" name="mobile_phone" value="{$smarty.post.mobile_phone}" size="15" /></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.fax}</td>
		<td><input type="text" name="fax" value="{$smarty.post.fax}" size="15" /></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.email}</td>
		<td><input type="text" name="email" value="{$smarty.post.email}" size="15" /></td>
	</tr>
	<tr>
		<td class="details_screen">{$customFieldLabel.customer_cf1}</td>
		<td><input type="text" name="custom_field1" value="{$smarty.post.custom_field1}" size="15" /></td>
	</tr>
	<tr>
		<td class="details_screen">{$customFieldLabel.customer_cf2}</td>
		<td><input type="text" name="custom_field2" value="{$smarty.post.custom_field2}" size="15" /></td>
	</tr>
	<tr>
		<td class="details_screen">{$customFieldLabel.customer_cf3}</td>
		<td><input type="text" name="custom_field3" value="{$smarty.post.custom_field3}" size="15" /></td>
	</tr>
	<tr>
		<td class="details_screen">{$customFieldLabel.customer_cf4}</td>
		<td><input type="text" name="custom_field4" value="{$smarty.post.custom_field4}" size="15" /></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.enabled}</td>
		<td>
			{html_options name=enabled options=$enabled selected=1}
		</td>
	</tr>
	
</table>
<hr />
<div style="text-align:center;">
	<input type="submit" name="customer" value="{$LANG.insert_customer}" />
	<input type="hidden" name="op" value="insert_customer" />
</div>
</form>
{/if}
