
<form name="frmpost"
	action="index.php?module=billers&view=save&submit={$smarty.get.submit}"
	method="post">


{if $smarty.get.action== 'view' }

<b>{$LANG.biller} :: <a
	href="index.php?module=billers&view=details&submit={$biller.id}&action=edit">{$LANG.edit}</a></b>
<hr></hr>
<table align="center">
	<tr>
		<td class="details_screen">{$LANG.biller_id}</td>
		<td>{$biller.id}</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.biller_name}</td>
		<td>{$biller.name}</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.street}</td>
		<td>{$biller.street_address}</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.street2} <a
			href="docs.php?t=help&p=street2"
			rel="gb_page_center[450, 450]"><img
			src="./images/common/help-small.png"></img></a></td>
		<td>{$biller.street_address2}</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.city}</td>
		<td>{$biller.city}</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.zip}</td>
		<td>{$biller.zip_code}</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.state}</td>
		<td>{$biller.state}</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.country}</td>
		<td>{$biller.country}</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.mobile_phone}</td>
		<td>{$biller.mobile_phone}</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.phone}</td>
		<td>{$biller.phone}</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.fax}</td>
		<td>{$biller.fax}</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.email}</td>
		<td>{$biller.email}</td>
	</tr>
	<tr>
		<td class="details_screen">{$customFieldLabel.biller_cf1} <a
			href="docs.php?t=help&p=custom_fields"
			rel="gb_page_center[450, 450]"><img
			src="./images/common/help-small.png"></img></a></td>
		<td>{$biller.custom_field1}</td>
	</tr>
	<tr>
		<td class="details_screen">{$customFieldLabel.biller_cf2} <a
			href="docs.php?t=help&p=custom_fields"
			rel="gb_page_center[450, 450]"><img
			src="./images/common/help-small.png"></img></a></td>
		<td>{$biller.custom_field2}</td>
	</tr>
	<tr>
		<td class="details_screen">{$customFieldLabel.biller_cf3} <a
			href="docs.php?t=help&p=custom_fields"
			rel="gb_page_center[450, 450]"><img
			src="./images/common/help-small.png"></img></a></td>
		<td>{$biller.custom_field3}</td>
	</tr>
	<tr>
		<td class="details_screen">{$customFieldLabel.biller_cf4} <a
			href="docs.php?t=help&p=custom_fields"
			rel="gb_page_center[450, 450]"><img
			src="./images/common/help-small.png"></img></a></td>
		<td>{$biller.custom_field4}</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.logo_file} <a
			href="docs.php?t=help&p=insert_biller_text"
			rel="gb_page_center[450, 450]"><img
			src="./images/common/help-small.png"></img></a></td>
		<td>{$biller.logo}</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.invoice_footer}</td>
		<td>{$biller.footer}</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.notes}</td>
		<td>{$biller.notes}</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.enabled}</td>
		<td>{$biller.wording_for_enabled}</td>
	</tr>
</table>
{/if}


{if $smarty.get.action== 'view' }
<hr></hr>
<a href="?module=billers&view=details&action=edit&submit={$biller.id}">{$LANG.edit}</a>
{/if}


{if $smarty.get.action== 'edit' }

<b>{$LANG.biller_edit}</b>
<hr></hr>
<table align="center">
	<tr>
		<td class="details_screen">{$LANG.biller_id}</td>
		<td>{$biller.id}</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.biller_name} <a href="docs.php?t=help&p=required_field" rel="gb_page_center[350, 150]"><img src="./images/common/required-small.png"></img></a></td>
		<td><input type=text name="name" value="{$biller.name}"
			size=50 /></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.street}</td>
		<td><input type=text name="street_address"
			value="{$biller.street_address}" size=50 /></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.street2} <a
			href="docs.php?t=help&p=street2"
			rel="gb_page_center[450, 450]"><img
			src="./images/common/help-small.png"></img></a></td>
		<td><input type=text name="street_address2"
			value="{$biller.street_address2}" size=50 /></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.city}</td>
		<td><input type=text name="city" value="{$biller.city}" size=50 /></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.zip}</td>
		<td><input type=text name="zip_code" value="{$biller.zip_code}"
			size=50 /></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.state}</td>
		<td><input type=text name="state" value="{$biller.state}" size=50 /></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.country}</td>
		<td><input type=text name="country" value="{$biller.country}"
			size=50 /></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.mobile_phone}</td>
		<td><input type=text name="mobile_phone"
			value="{$biller.mobile_phone}" size=50 /></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.phone}</td>
		<td><input type=text name="phone" value="{$biller.phone}" size=50 /></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.fax}</td>
		<td><input type=text name="fax" value="{$biller.fax}" size=50 /></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.email}</td>
		<td><input type=text name="email" value="{$biller.email}" size=50 /></td>
	</tr>
	<tr>
		<td class="details_screen">{$customFieldLabel.biller_cf1} <a
			href="docs.php?t=help&p=custom_fields"
			rel="gb_page_center[450, 450]"><img
			src="./images/common/help-small.png"></img></a></td>
		<td><input type=text name="custom_field1"
			value="{$biller.custom_field1}" size=50</td>
	</tr>
	<tr>
		<td class="details_screen">{$customFieldLabel.biller_cf2} <a
			href="docs.php?t=help&p=custom_fields"
			rel="gb_page_center[450, 450]"><img
			src="./images/common/help-small.png"></img></a></td>
		<td><input type=text name="custom_field2"
			value="{$biller.custom_field2}" size=50</td>
	</tr>
	<tr>
		<td class="details_screen">{$customFieldLabel.biller_cf3} <a
			href="docs.php?t=help&p=custom_fields"
			rel="gb_page_center[450, 450]"><img
			src="./images/common/help-small.png"></img></a></td>
		<td><input type=text name="custom_field3"
			value="{$biller.custom_field3}" size=50</td>
	</tr>
	<tr>
		<td class="details_screen">{$customFieldLabel.biller_cf4} <a
			href="docs.php?t=help&p=custom_fields"
			rel="gb_page_center[450, 450]"><img
			src="./images/common/help-small.png"></img></a></td>
		<td><input type=text name="custom_field4"
			value="{$biller.custom_field4}" size=50</td>
	</tr>
	<tr>
		<td class="details_screen">
		{$LANG.logo_file}
		<a
			href="docs.php?t=help&p=insert_biller_text"
			rel="gb_page_center[450, 450]"><img
			src="./images/common/help-small.png"></img></a></td>
		<td>
			{html_options name=logo output=$files values=$files selected=$biller.logo }
		</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.invoice_footer}</td>
		<td><textarea name="footer" rows=4 cols=50>{$biller.footer}</textarea></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.notes}</td>
		<td><textarea name="notes" rows=8 cols=50>{$biller.notes}</textarea></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.enabled}</td>
		<td>
		{html_options name=enabled options=$enabled selected=$biller.enabled}
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
