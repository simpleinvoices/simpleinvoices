{* if customer is updated or saved.*} 

{if $smarty.post.c_name != "" && $smarty.post.submit != null } 
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
		<td class="details_screen">{$LANG.customer_name} <a href="./modules/documentation/info_pages/required_field.html" rel="gb_page_center[350, 150]"><img src="./images/common/required-small.png"></img></a></td>
		<td><input type=text name="c_name" value="{$smarty.post.c_name}" size=25></td>
	</tr>
	</tr>
	<td class="details_screen">{$LANG.customer_contact} <a
		href="./modules/documentation/info_pages/customer_contact.html"
		rel="gb_page_center[450, 450]"><img
		src="./images/common/help-small.png"></img></a></td>
	<td><input type=text name="c_attention" size=25 value="{$smarty.post.c_attention}"></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.street}</td>
		<td><input type=text name="c_street_address" value="{$smarty.post.c_street_address}" size=25></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.street2} <a
			href="./modules/documentation/info_pages/street2.html"
			rel="gb_page_center[450, 450]"><img
			src="./images/common/help-small.png"></img></a></td>
		<td><input type=text name="c_street_address2" value="{$smarty.post.c_street_address2}" size=25></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.city}</td>
		<td><input type=text name="c_city" value="{$smarty.post.c_city}" size=25></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.state}</td>
		<td><input type=text name="c_state" value="{$smarty.post.c_state}" size=25></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.zip}</td>
		<td><input type=text name="c_zip_code" value="{$smarty.post.c_zip_code}" size=25></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.country}</td>
		<td><input type=text name="c_country" value="{$smarty.post.c_country}" size=50></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.phone}</td>
		<td><input type=text name="c_phone" value="{$smarty.post.c_phone}" size=25></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.mobile_phone}</td>
		<td><input type=text name="c_mobile_phone" value="{$smarty.post.c_mobile_phone}" size=25></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.fax}</td>
		<td><input type=text name="c_fax" value="{$smarty.post.c_fax}" size=25></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.email}</td>
		<td><input type=text name="c_email" value="{$smarty.post.c_email}" size=25></td>
	</tr>
	<tr>
		<td class="details_screen">{$customFieldLabel.1} <a
			href="./modules/documentation/info_pages/custom_fields.html"
			rel="gb_page_center[450, 450]"><img
			src="./images/common/help-small.png"></img></a></td>
		<td><input type=text name="c_custom_field1" value="{$smarty.post.c_custom_field1}" size=25></td>
	</tr>
	<tr>
		<td class="details_screen">{$customFieldLabel.2} <a
			href="./modules/documentation/info_pages/custom_fields.html"
			rel="gb_page_center[450, 450]"><img
			src="./images/common/help-small.png"></img></a></td>
		<td><input type=text name="c_custom_field2" value="{$smarty.post.c_custom_field2}" size=25></td>
	</tr>
	<tr>
		<td class="details_screen">{$customFieldLabel.3} <a
			href="./modules/documentation/info_pages/custom_fields.html"
			rel="gb_page_center[450, 450]"><img
			src="./images/common/help-small.png"></img></a></td>
		<td><input type=text name="c_custom_field3" value="{$smarty.post.c_custom_field3}" size=25></td>
	</tr>
	<tr>
		<td class="details_screen">{$customFieldLabel.4} <a
			href="./modules/documentation/info_pages/custom_fields.html"
			rel="gb_page_center[450, 450]"><img
			src="./images/common/help-small.png"></img></a></td>
		<td><input type=text name="c_custom_field4" value="{$smarty.post.c_custom_field4}" size=25></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.notes}</td>
		<td><textarea name='c_notes' rows=8 cols=50>{$smarty.post.c_notes}</textarea></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.enabled}</td>
		<td>
			<select name="c_enabled">
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
