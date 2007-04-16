
<form name="frmpost" ACTION="index.php?module=customers&view=save"
	METHOD=POST onsubmit="return frmpost_Validator(this)">
<div id="top"><b>{#customer_add#}</b></div>
<hr></hr>
<table align=center>
	<tr>
		<td class="details_screen">{#customer_name#}</td>
		<td><input type=text name="c_name" size=25></td>
	</tr>
	</tr>
	<td class="details_screen">{#customer_contact#} <a
		href="./documentation/info_pages/customer_contact.html"
		rel="gb_page_center[450, 450]"><img
		src="./images/common/help-small.png"></img></a></td>
	<td><input type=text name="c_attention" size=25></td>
	</tr>
	<tr>
		<td class="details_screen">{#street#}</td>
		<td><input type=text name="c_street_address" size=25></td>
	</tr>
	<tr>
		<td class="details_screen">{#street2#} <a
			href="./documentation/info_pages/street2.html"
			rel="gb_page_center[450, 450]"><img
			src="./images/common/help-small.png"></img></a></td>
		<td><input type=text name="c_street_address2" size=25></td>
	</tr>
	<tr>
		<td class="details_screen">{#city#}</td>
		<td><input type=text name="c_city" size=25></td>
	</tr>
	<tr>
		<td class="details_screen">{#state#}</td>
		<td><input type=text name="c_state" size=25></td>
	</tr>
	<tr>
		<td class="details_screen">{#zip#}</td>
		<td><input type=text name="c_zip_code" size=25></td>
	</tr>
	<tr>
		<td class="details_screen">{#country#} ({#optional#})</td>
		<td><input type=text name="c_country" size=50></td>
	</tr>
	<tr>
		<td class="details_screen">{#phone#}</td>
		<td><input type=text name="c_phone" size=25></td>
	</tr>
	<tr>
		<td class="details_screen">{#mobile_phone#}</td>
		<td><input type=text name="c_mobile_phone" size=25></td>
	</tr>
	<tr>
		<td class="details_screen">{#fax#}</td>
		<td><input type=text name="c_fax" size=25></td>
	</tr>
	<tr>
		<td class="details_screen">{#email#}</td>
		<td><input type=text name="c_email" size=25></td>
	</tr>
	<tr>
		<td class="details_screen">{$customFieldLabel.1} <a
			href="./documentation/info_pages/custom_fields.html"
			rel="gb_page_center[450, 450]"><img
			src="./images/common/help-small.png"></img></a></td>
		<td><input type=text name="c_custom_field1" size=25></td>
	</tr>
	<tr>
		<td class="details_screen">{$customFieldLabel.2} <a
			href="./documentation/info_pages/custom_fields.html"
			rel="gb_page_center[450, 450]"><img
			src="./images/common/help-small.png"></img></a></td>
		<td><input type=text name="c_custom_field2" size=25></td>
	</tr>
	<tr>
		<td class="details_screen">{$customFieldLabel.3} <a
			href="./documentation/info_pages/custom_fields.html"
			rel="gb_page_center[450, 450]"><img
			src="./images/common/help-small.png"></img></a></td>
		<td><input type=text name="c_custom_field3" size=25></td>
	</tr>
	<tr>
		<td class="details_screen">{$customFieldLabel.4} <a
			href="./documentation/info_pages/custom_fields.html"
			rel="gb_page_center[450, 450]"><img
			src="./images/common/help-small.png"></img></a></td>
		<td><input type=text name="c_custom_field4" size=25></td>
	</tr>
	<tr>
		<td class="details_screen">{#notes#}</td>
		<td><textarea name='c_notes' rows=8 cols=50></textarea></td>
	</tr>
	<tr>
		<td class="details_screen">{#wording_for_enabledField#}</td>
		<td>
			<select name="c_enabled">
			<option value="1" selected>{#wording_for_enabledField#}</option>
			<option value="0">{#wording_for_disabledField#}</option>
			</select>
		</td>
	</tr>
</table>
<hr></hr>
<input type=submit name="submit" value="{#insert_customer#}">
<input type=hidden name="op" value="insert_customer">
</form>
