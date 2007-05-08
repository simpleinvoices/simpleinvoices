{* if customer is updated or saved.*} 

{if $smarty.post.name != "" && $smarty.post.submit != null } 
{$refresh_total}

<br />
<br>
{$display_block} 
<br />
<br />

{else}
{* if  name was inserted *} 
	{if $smarty.post.submit !=null} 
		<div class="validation_alert"><img src="./images/common/important.png"</img>
		You must enter a Customer name</div>
		<hr></hr>
	{/if}
<form name="frmpost" ACTION="index.php?module=customers&view=add" METHOD="post"><b>{$LANG.customer_add}</b>
<hr></hr>
<table align=center>
	<tr>
		<td class="details_screen">{$LANG.customer_name} <a href="docs.php?t=help&p=required_field" rel="gb_page_center[350, 150]"><img src="./images/common/required-small.png"></img></a></td>
		<td><input type=text name="name" value="{$smarty.post.name}" size=25></td>
	</tr>
	</tr>
	<td class="details_screen">{$LANG.customer_contact} <a
		href="docs.php?t=help&p=customer_contact"
		rel="gb_page_center[450, 450]"><img
		src="./images/common/help-small.png"></img></a></td>
	<td><input type=text name="attention" size=25 value="{$smarty.post.attention}"></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.street}</td>
		<td><input type=text name="street_address" value="{$smarty.post.street_address}" size=25></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.street2} <a
			href="docs.php?t=help&p=street2"
			rel="gb_page_center[450, 450]"><img
			src="./images/common/help-small.png"></img></a></td>
		<td><input type=text name="street_address2" value="{$smarty.post.street_address2}" size=25></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.city}</td>
		<td><input type=text name="city" value="{$smarty.post.city}" size=25></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.state}</td>
		<td><input type=text name="state" value="{$smarty.post.state}" size=25></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.zip}</td>
		<td><input type=text name="zip_code" value="{$smarty.post.zip_code}" size=25></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.country}</td>
		<td><input type=text name="country" value="{$smarty.post.country}" size=50></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.phone}</td>
		<td><input type=text name="phone" value="{$smarty.post.phone}" size=25></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.mobile_phone}</td>
		<td><input type=text name="mobile_phone" value="{$smarty.post.mobile_phone}" size=25></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.fax}</td>
		<td><input type=text name="fax" value="{$smarty.post.fax}" size=25></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.email}</td>
		<td><input type=text name="email" value="{$smarty.post.email}" size=25></td>
	</tr>
	<tr>
		<td class="details_screen">{$customFieldLabel.1} <a
			href="docs.php?t=help&p=custom_fields"
			rel="gb_page_center[450, 450]"><img
			src="./images/common/help-small.png"></img></a></td>
		<td><input type=text name="custom_field1" value="{$smarty.post.custom_field1}" size=25></td>
	</tr>
	<tr>
		<td class="details_screen">{$customFieldLabel.2} <a
			href="docs.php?t=help&p=custom_fields"
			rel="gb_page_center[450, 450]"><img
			src="./images/common/help-small.png"></img></a></td>
		<td><input type=text name="custom_field2" value="{$smarty.post.custom_field2}" size=25></td>
	</tr>
	<tr>
		<td class="details_screen">{$customFieldLabel.3} <a
			href="docs.php?t=help&p=custom_fields"
			rel="gb_page_center[450, 450]"><img
			src="./images/common/help-small.png"></img></a></td>
		<td><input type=text name="custom_field3" value="{$smarty.post.custom_field3}" size=25></td>
	</tr>
	<tr>
		<td class="details_screen">{$customFieldLabel.4} <a
			href="docs.php?t=help&p=custom_fields"
			rel="gb_page_center[450, 450]"><img
			src="./images/common/help-small.png"></img></a></td>
		<td><input type=text name="custom_field4" value="{$smarty.post.custom_field4}" size=25></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.notes}</td>
		<td><textarea name='notes' rows=8 cols=50>{$smarty.post.notes}</textarea></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.enabled}</td>
		<td>
			<select name="enabled">
			<option value="1" selected>{$LANG.enabled}</option>
			<option value="0">{$LANG.disabled}</option>
			</select>
		</td>
	</tr>
</table>
<hr></hr>
<input type=submit name="submit" value="{$LANG.insert_customer}">
<input type=hidden name="op" value="insert_customer">
</form>
{/if}
