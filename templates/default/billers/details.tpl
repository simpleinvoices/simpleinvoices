
<form name="frmpost"
	action="index.php?module=billers&view=save&submit={$smarty.get.submit}"
	method="post">


{if $smarty.get.action== 'view' }

<b>{$LANG.biller} :: <a
	href="index.php?module=billers&view=details&submit={$biller.b_id}&action=edit">{$LANG.edit}</a></b>
<hr></hr>
<table align="center">
	<tr>
		<td class="details_screen">{$LANG.biller_id}</td>
		<td>{$biller.b_id}</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.biller_name}</td>
		<td>{$biller.b_name}</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.street}</td>
		<td>{$biller.b_street_address}</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.street2} <a
			href="./documentation/info_pages/street2.html"
			rel="gb_page_center[450, 450]"><img
			src="./images/common/help-small.png"></img></a></td>
		<td>{$biller.b_street_address2}</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.city}</td>
		<td>{$biller.b_city}</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.zip}</td>
		<td>{$biller.b_zip_code}</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.state}</td>
		<td>{$biller.b_state}</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.country}</td>
		<td>{$biller.b_country}</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.mobile_phone}</td>
		<td>{$biller.b_mobile_phone}</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.phone}</td>
		<td>{$biller.b_phone}</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.fax}</td>
		<td>{$biller.b_fax}</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.email}</td>
		<td>{$biller.b_email}</td>
	</tr>
	<tr>
		<td class="details_screen">{$customFieldLabel.1} <a
			href="./documentation/info_pages/custom_fields.html"
			rel="gb_page_center[450, 450]"><img
			src="./images/common/help-small.png"></img></a></td>
		<td>{$biller.b_custom_field1}</td>
	</tr>
	<tr>
		<td class="details_screen">{$customFieldLabel.2} <a
			href="./documentation/info_pages/custom_fields.html"
			rel="gb_page_center[450, 450]"><img
			src="./images/common/help-small.png"></img></a></td>
		<td>{$biller.b_custom_field2}</td>
	</tr>
	<tr>
		<td class="details_screen">{$customFieldLabel.3} <a
			href="./documentation/info_pages/custom_fields.html"
			rel="gb_page_center[450, 450]"><img
			src="./images/common/help-small.png"></img></a></td>
		<td>{$biller.b_custom_field3}</td>
	</tr>
	<tr>
		<td class="details_screen">{$customFieldLabel.4} <a
			href="./documentation/info_pages/custom_fields.html"
			rel="gb_page_center[450, 450]"><img
			src="./images/common/help-small.png"></img></a></td>
		<td>{$biller.b_custom_field4}</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.logo_file} <a
			href="documentation/info_pages/insert_biller_text.html"
			rel="gb_page_center[450, 450]"><img
			src="./images/common/help-small.png"></img></a></td>
		<td>{$biller.b_co_logo}</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.invoice_footer}</td>
		<td>{$biller.b_co_footer}</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.notes}</td>
		<td>{$biller.b_notes}</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.enabled}</td>
		<td>{$biller.wording_for_enabled}</td>
	</tr>
</table>
{/if}


{if $smarty.get.action== 'view' }
<hr></hr>
<a href="?module=billers&view=details&action=edit&submit={$biller.b_id}">{$LANG.edit}</a>
{/if}


{if $smarty.get.action== 'edit' }

<b>{$LANG.biller_edit}</b>
<hr></hr>
<table align="center">
	<tr>
		<td class="details_screen">{$LANG.biller_id}</td>
		<td>{$biller.b_id}</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.biller_name} <a href="./documentation/info_pages/required_field.html" rel="gb_page_center[350, 150]"><img src="./images/common/required-small.png"></img></a></td>
		<td><input type=text name="b_name" value="{$biller.b_name}"
			size=50 /></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.street}</td>
		<td><input type=text name="b_street_address"
			value="{$biller.b_street_address}" size=50 /></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.street2} <a
			href="./documentation/info_pages/street2.html"
			rel="gb_page_center[450, 450]"><img
			src="./images/common/help-small.png"></img></a></td>
		<td><input type=text name="b_street_address2"
			value="{$biller.b_street_address2}" size=50 /></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.city}</td>
		<td><input type=text name="b_city" value="{$biller.b_city}" size=50 /></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.zip}</td>
		<td><input type=text name="b_zip_code" value="{$biller.b_zip_code}"
			size=50 /></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.state}</td>
		<td><input type=text name="b_state" value="{$biller.b_state}" size=50 /></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.country}</td>
		<td><input type=text name="b_country" value="{$biller.b_coutry}"
			size=50 /></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.mobile_phone}</td>
		<td><input type=text name="b_mobile_phone"
			value="{$biller.b_mobile_phone}" size=50 /></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.phone}</td>
		<td><input type=text name="b_phone" value="{$biller.b_phone}" size=50 /></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.fax}</td>
		<td><input type=text name="b_fax" value="{$biller.b_fax}" size=50 /></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.email}</td>
		<td><input type=text name="b_email" value="{$biller.b_email}" size=50 /></td>
	</tr>
	<tr>
		<td class="details_screen">{$customFieldLabel.1} <a
			href="./documentation/info_pages/custom_fields.html"
			rel="gb_page_center[450, 450]"><img
			src="./images/common/help-small.png"></img></a></td>
		<td><input type=text name="b_custom_field1"
			value="{$biller.b_custom_field1}" size=50</td>
	</tr>
	<tr>
		<td class="details_screen">{$customFieldLabel.2} <a
			href="./documentation/info_pages/custom_fields.html"
			rel="gb_page_center[450, 450]"><img
			src="./images/common/help-small.png"></img></a></td>
		<td><input type=text name="b_custom_field2"
			value="{$biller.b_custom_field2}" size=50</td>
	</tr>
	<tr>
		<td class="details_screen">{$customFieldLabel.3} <a
			href="./documentation/info_pages/custom_fields.html"
			rel="gb_page_center[450, 450]"><img
			src="./images/common/help-small.png"></img></a></td>
		<td><input type=text name="b_custom_field3"
			value="{$biller.b_custom_field3}" size=50</td>
	</tr>
	<tr>
		<td class="details_screen">{$customFieldLabel.4} <a
			href="./documentation/info_pages/custom_fields.html"
			rel="gb_page_center[450, 450]"><img
			src="./images/common/help-small.png"></img></a></td>
		<td><input type=text name="b_custom_field4"
			value="{$biller.b_custom_field4}" size=50</td>
	</tr>
	<tr>
		<td class="details_screen">
		{$LANG.logo_file}
		<a
			href="documentation/info_pages/insert_biller_text.html"
			rel="gb_page_center[450, 450]"><img
			src="./images/common/help-small.png"></img></a></td>
		<td>{* Logo Part *} <select name="b_co_logo">
			<option selected value="{$biller[b_co_logo]}"
				style="font-weight:bold;">{$biller.b_co_logo}</option>
			{foreach from=$files item=file}
			<option>{$file}</option>
			{/foreach}
		</select>
		{*/ Logo Part *}
		</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.invoice_footer}</td>
		<td><textarea name="b_co_footer" rows=4 cols=50>{$biller.b_co_footer}</textarea></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.notes}</td>
		<td><textarea name="b_notes" rows=8 cols=50>{$biller.b_notes}</textarea></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.enabled}</td>
		<td>
		{* enabled block *}
		<select name="b_enabled">
			<option value="$biller[b_enabled]" selected
				style="font-weight: bold;">{$biller.wording_for_enabled}</option>
			<option value="1">{$LANG.enabled}</option>
			<option value="0">{$LANG.disabled}</option>
		</select>
		{* /enabled block*}
		</td>
	</tr>
</table>
{/if} 
{if $smarty.get.action== 'edit' }
	<hr></hr>
		<input type="submit" name="cancel" value="{$LANG.cancel}" /> 
		<input type="submit" name="save_biller" value="{$LANG.save_biller}" /> 
		<input type="hidden" name="op" value="edit_biller" /> 
	{/if}
</form>
