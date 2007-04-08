<?php


$display_block_view = <<<EOD
<b>{$LANG_customer} :: <a href="index.php?module=customers&view=details&submit={$customer[c_id]}&action=edit">{$LANG_edit}</a>
</b>
<hr></hr>
<table align="center">
	<tr>
		<td colspan="7" align="center"> </td>
	</tr>
	<tr>
		<td colspan="4" align="center" class="align_center"><i>{$LANG_customer_details}</i></td>
		<td width="10%"></td>
		<td colspan="2" align="center" class="align_center"><i>{$LANG_summary_of_accounts}</i></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG_customer} {$LANG_id}</td>
		<td>{$customer[c_id]}</td>
		<td colspan="2"></td>
		<td></td>
		<td class="details_screen">{$LANG_total_invoices}</td>
		<td>{$invoice_total_Field_formatted}</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG_customer_name}</td>
		<td colspan="2">{$customer[c_name]}</td>
		<td colspan="2"></td>
		<td class="details_screen">{$LANG_total_paid}</td>
		<td>{$invoice_paid_Field_formatted}</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG_attention_short} <a href="documentation/info_pages/customer_contact.html" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img></a>
		</td>
		<td colspan="2">{$customer[c_attention]}</td>
		<td colspan=2></td>
		<td class="details_screen">{$LANG_total_owing}</td>
		<td><u>{$invoice_owing_Field}</u></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG_street}</td>
		<td>{$customer[c_street_address]}</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG_street2} <a href="./documentation/info_pages/street2.html" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img></a>
		</td>
		<td>{$customer[c_street_address2]}</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG_city}</td>
		<td>{$customer[c_city]}</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG_zip}</td>
		<td>{$customer[c_zip_code]}</td>
		<td class="details_screen">{$LANG_phone}</td>
		<td>{$customer[c_phone]}</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG_state}</td>
		<td>{$customer[c_state]}</td>
		<td class="details_screen">{$LANG_mobile_phone}</td>
		<td>{$customer[c_mobile_phone]}</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG_country}</td>
		<td>{$customer[c_country]}</td>
		<td class="details_screen">{$LANG_fax}</td>
		<td>{$customer[c_fax]}</td>
	</tr>
	<tr>
		<td class="details_screen">{$wording_for_enabledField}</td>
		<td>{$wording_for_enabled}</td>
		<td class="details_screen">{$LANG_email}</td>
		<td>{$customer[c_email]}</td>
	</tr>
</table>
<br />
<div id="container-1">
	<ul class="anchors">
		<li><a href="#section-1">{$LANG_custom_fields}</a></li>
		<li><a href="#section-2">{$LANG_customer} {$LANG_invoice_listings}</a></li>
		<li><a href="#section-3">{$LANG_notes}</a></li>
	</ul>
	<div id="section-1" class="fragment">
		<h4><u>{$LANG_customer} {$LANG_custom_fields}</u></h4>
		<p>
			<table>
				<tr>
					<td class="details_screen">{$customFieldLabel['1']} <a href="./documentation/info_pages/custom_fields.html" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img></a>
					</td>
					<td>{$customer[c_custom_field1]}</td>
				</tr>
				<tr>
					<td class="details_screen">{$customFieldLabel['2']} <a href="./documentation/info_pages/custom_fields.html" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img></a>
					</td>
					<td>{$customer[c_custom_field2]}</td>
				</tr>
				<tr>
					<td class="details_screen">{$customFieldLabel['3']} <a href="./documentation/info_pages/custom_fields.html" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img></a>
					</td>
					<td>{$customer[c_custom_field3]}</td>
				</tr>
				<tr>
					<td class="details_screen">{$customFieldLabel['4']} <a href="./documentation/info_pages/custom_fields.html" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img></a>
					</td>
					<td>{$customer[c_custom_field4]}</td>
				</tr>
			</table>
		</p>
	</div>
	<div id="section-2" class="fragment">
		<h4><u>{$LANG_invoice_listings}</u></h4>
		<p>
			EOD;
			$display_block_view2 = <<<EOD
		</p>
	</div>
	<div id="section-3" class="fragment">
		<h4><u>{$LANG_customer} {$LANG_notes}</u></h4>
		<p>
			<div id="left">
				{$customer[c_notes]}
			</div>
		</p>
	</div>
</div>
EOD;


$footer_view = <<<EOD
<hr></hr>
<a href="index.php?module=customers&view=details&submit={$customer[c_id]}&action=edit">{$LANG_edit}</a>
EOD;


#do the product enabled/disblaed drop down
$display_block_enabled = <<<EOD
<select name="c_enabled">
	<option value="$c_enabledField" selected style="font-weight: bold">$wording_for_enabled</option>
	<option value="1">$wording_for_enabledField</option>
	<option value="0">$wording_for_disabledField</option>
</select>
EOD;


$display_block_edit = <<<EOD
<div id="top"><b>{$LANG_customer_edit}</b></div>
<hr></hr>
<table align="center">
	<tr>
		<td class="details_screen">{$LANG_customer} {$LANG_id}</td>
		<td>{$customer[c_id]}</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG_customer_name}</td>
		<td><input type="text" name="c_name" value="{$customer[c_name]}" size="50" /></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG_attention_short} <a href="documentation/info_pages/customer_contact.html" rel="gb_page_center[450, 450]" ><img src="./images/common/help-small.png"></img></a>
		</td>
		<td><input type="text" name="c_attention" value="{$customer[c_attention]}" size="50" /></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG_street}</td>
		<td><input type="text" name="c_street_address" value="{$customer[c_street_address]}" size="50" /></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG_street2} <a href="./documentation/info_pages/street2.html" rel="gb_page_center[450, 450]" ><img src="./images/common/help-small.png"></img></a>
		</td>
		<td><input type="text" name="c_street_address2" value="{$customer[c_street_address2]}" size="50" /></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG_city}</td>
		<td><input type="text" name="c_city" value="{$customer[c_city]}" size="50" /></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG_zip}</td>
		<td><input type="text" name="c_zip_code" value="{$customer[c_zip_code]}" size="50" /></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG_state}</td>
		<td><input type="text" name="c_state" value="{$customer[c_state]}" size="50" /></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG_country}</td>
		<td><input type="text" name="c_country" value="{$customer[c_country]}" size="50" /></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG_phone}</td>
		<td><input type="text" name="c_phone" value="{$customer[c_phone]}" size="50" /></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG_mobile_phone}</td>
		<td><input type="text" name="c_mobile_phone" value="{$customer[c_mobile_phone]}" size="50" /></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG_fax}</td>
		<td><input type="text" name="c_fax" value="{$customer[c_fax]}" size="50" /></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG_email}</td>
		<td>
			<input type="text" name="c_email" value="{$customer[c_email]}" size="50" /></td
	</tr>
	<tr>
		<td class="details_screen">{$customer_custom_field_label1} <a href="./documentation/info_pages/custom_fields.html" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img></a>
		</td>
		<td>
			<input type="text" name="c_custom_field1" value="{$customer[c_custom_field1]}" size="50" /></td
	</tr>
	<tr>
		<td class="details_screen">{$customer_custom_field_label2} <a href="./documentation/info_pages/custom_fields.html" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img></a>
		</td>
		<td>
			<input type="text" name="c_custom_field2" value="{$customer[c_custom_field2]}" size="50" /></td
	</tr>
	<tr>
		<td class="details_screen">{$customer_custom_field_label3} <a href="./documentation/info_pages/custom_fields.html" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img></a>
		</td>
		<td>
			<input type="text" name="c_custom_field3" value="{$customer[c_custom_field3]}" size="50" /></td
	</tr>
	<tr>
		<td class="details_screen">{$customer_custom_field_label4} <a href="./documentation/info_pages/custom_fields.html" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img></a>
		</td>
		<td>
			<input type="text" name="c_custom_field4" value="{$customer[c_custom_field4]}" size="50" /></td
	</tr>
	<tr>
		<td class="details_screen">{$LANG_notes}</td>
		<td><textarea name="c_notes" rows="8" cols="50">{$customer[c_notes]}</textarea></td>
	</tr>
	<tr>
		<td class="details_screen">{$wording_for_enabledField}</td>
		<td>{$display_block_enabled}</td>
	</tr>
</table>
EOD;


$footer_edit = <<<EOD
<hr></hr>
<input type="submit" name="cancel" value="{$LANG_cancel}" />
<input type="submit" name="save_customer" value="{$LANG_save_customer}" />
<input type="hidden" name="op" value="edit_customer" />
EOD;


$block = <<<EOD
<form name="frmpost" action="index.php?module=customers&view=save&submit={$_GET['submit']}" method="post" onsubmit="return frmpost_Validator(this)">
	{$display_block}
	{$footer}
</form>
EOD;

?>