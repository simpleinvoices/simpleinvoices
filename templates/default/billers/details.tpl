<?php

$save = <<<EOD
{$refresh_total}
<br>
<br>
{$display_block}
<br><br>
{$display_block_items}
EOD;

$display_block_enabled = <<<EOD
<select name="b_enabled">
<option value="1" selected>$wording_for_enabledField</option>
<option value="0">$wording_for_disabledField</option>
</select>
EOD;


$display_block_logo = <<<EOD
<select name="b_co_logo">
	<option selected value="$biller[b_co_logo]" style="font-weight:bold;">$biller[b_co_logo]</option>
$display_block_logo_list
</select>
EOD;

$display_block_logo_line = <<<EOD
<option>$file</option>
EOD;

$display_block_view = <<<EOD
	<b>{$LANG_biller} :: <a href="index.php?module=billers&view=details&submit=$biller[b_id]&action=edit">{$LANG_edit}</a></b>
 <hr></hr>

	<table align="center">
	<tr>
		<td class="details_screen">{$LANG_biller_id}</td><td>$biller[b_id]</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG_biller_name}</td><td>$biller[b_name]</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG_street}</td><td>$biller[b_street_address]</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG_street2} <a href="./documentation/info_pages/street2.html" rel="ibox&height=400"><img src="./images/common/help-small.png"></img></a></td><td>$biller[b_street_address2]</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG_city}</td><td>$biller[b_city]</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG_zip}</td><td>$biller[b_zip_code]</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG_state}</td><td>$biller[b_state]</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG_country}</td><td>$biller[b_country]</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG_mobile_phone}</td><td>$biller[b_mobile_phone]</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG_phone}</td><td>$biller[b_phone]</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG_fax}</td><td>$biller[b_fax]</td>
	</tr>	
	<tr>
		<td class="details_screen">{$LANG_email}</td><td>$biller[b_email]</td>
	</tr>	
	<tr>
		<td class="details_screen">{$customFieldLabel['1']} <a href="./documentation/info_pages/custom_fields.html" rel="ibox&height=400"><img src="./images/common/help-small.png"></img></a></td>
		<td>$biller[b_custom_field1]</td>
	</tr>
	<tr>
		<td class="details_screen">{$customFieldLabel['2']} <a href="./documentation/info_pages/custom_fields.html" rel="ibox&height=400"><img src="./images/common/help-small.png"></img></a></td>
		<td>$biller[b_custom_field2]</td>
	</tr>
	<tr>
		<td class="details_screen">{$customFieldLabel['3']} <a href="./documentation/info_pages/custom_fields.html" rel="ibox&height=400"><img src="./images/common/help-small.png"></img></a></td>
		<td>$biller[b_custom_field3]</td>
	</tr>
	<tr>
		<td class="details_screen">{$customFieldLabel['4']} <a href="./documentation/info_pages/custom_fields.html" rel="ibox&height=400"><img src="./images/common/help-small.png"></img></a></td>
		<td>$biller[b_custom_field1]</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG_logo_file} <a href="documentation/info_pages/insert_biller_text.html" rel="ibox&height=400"><img src="./images/common/help-small.png"></img></a></td><td>$biller[b_co_logo]</td>
	</tr>	
	<tr>
		<td class="details_screen">{$LANG_invoice_footer}</td><td>$biller[b_co_footer]</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG_notes}</td><td>$biller[b_notes]</td>
	</tr>
	<tr>
		<td class="details_screen">{$wording_for_enabledField}</td><td>$biller[wording_for_enabled]</td>
	</tr>
	</table>

EOD;

$footer_view = <<<EOD
<hr></hr>
<a href="?module=billers&view=details&action=edit&submit=$biller[b_id]">{$LANG_edit}</a>
EOD;



#do the product enabled/disblaed drop down
$display_block_enabled = <<<EOD
<select name="b_enabled">
<option value="$biller[b_enabled]" selected style="font-weight: bold;">$biller[wording_for_enabled]</option>
<option value="1">$wording_for_enabledField</option>
<option value="0">$wording_for_disabledField</option>
</select>
EOD;

$display_block_edit = <<<EOD

	<b>{$LANG_biller_edit}</b>
 <hr></hr>
	<table align="center">
	<tr>
		<td class="details_screen">{$LANG_biller_id}</td><td>$biller[b_id]</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG_biller_name}</td>
		<td><input type=text name="b_name" value="$biller[b_name]" size=50 /></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG_street}</td>
		<td><input type=text name="b_street_address" value="$biller[b_street_address]" size=50 /></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG_street2} <a href="./documentation/info_pages/street2.html" rel="ibox&height=400"><img src="./images/common/help-small.png"></img></a></td>
		<td><input type=text name="b_street_address2" value="$biller[b_street_address2]" size=50 /></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG_city}</td>
		<td><input type=text name="b_city" value="$biller[b_city]" size=50 /></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG_zip}</td>
		<td><input type=text name="b_zip_code" value="$biller[b_zip_code]" size=50 /></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG_state}</td>
		<td><input type=text name="b_state" value="$biller[b_state]" size=50 /></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG_country}</td>
		<td><input type=text name="b_country" value="$biller[b_coutry]" size=50 /></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG_mobile_phone}</td>
		<td><input type=text name="b_mobile_phone" value="$biller[b_mobile_phone]" size=50 /></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG_phone}</td>
		<td><input type=text name="b_phone" value="$biller[b_phone]" size=50 /></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG_fax}</td>
		<td><input type=text name="b_fax" value="$biller[b_fax]" size=50 /></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG_email}</td>
		<td><input type=text name="b_email" value="$biller[b_email]" size=50 /></td>
	</tr>
	<tr>
		<td class="details_screen">{$customFieldLabel['1']} <a href="./documentation/info_pages/custom_fields.html" rel="ibox&height=400"><img src="./images/common/help-small.png"></img></a></td>
		<td><input type=text name="b_custom_field1" value="$biller[b_custom_field1]" size=50 </td>
	</tr>
	<tr>
		<td class="details_screen">{$customFieldLabel['2']} <a href="./documentation/info_pages/custom_fields.html" rel="ibox&height=400"><img src="./images/common/help-small.png"></img></a></td>
		<td><input type=text name="b_custom_field2" value="$biller[b_custom_field2]" size=50 </td>
	</tr>
	<tr>
		<td class="details_screen">{$customFieldLabel['3']} <a href="./documentation/info_pages/custom_fields.html" rel="ibox&height=400"><img src="./images/common/help-small.png"></img></a></td>
		<td><input type=text name="b_custom_field3" value="$biller[b_custom_field3]" size=50 </td>
	</tr>
	<tr>
		<td class="details_screen">{$customFieldLabel['4']} <a href="./documentation/info_pages/custom_fields.html" rel="ibox&height=400"><img src="./images/common/help-small.png"></img></a></td>
		<td><input type=text name="b_custom_field4" value="$biller[b_custom_field4]" size=50 </td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG_logo_file}
		<a href="documentation/info_pages/insert_biller_text.html" rel="ibox&height=400"><img src="./images/common/help-small.png"></img></a></td>
		<td>{$display_block_logo}</td> 
	</tr>
	<tr>
		<td class="details_screen">{$LANG_invoice_footer}</td>
		<td><textarea name="b_co_footer" rows=4 cols=50>$biller[b_co_footer]</textarea></td>
	</tr>
	<tr>		
		<td class="details_screen">{$LANG_notes}</td>
		<td><textarea name="b_notes" rows=8 cols=50>$biller[b_notes]</textarea></td>
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
<input type="submit" name="save_biller" value="{$LANG_save_biller}" />
<input type="hidden" name="op" value="edit_biller" />

EOD;



$block = <<<EOD
<form name="frmpost" action="index.php?module=billers&view=add&submit={$_GET['submit']}" method="post">
{$display_block}
{$footer}
</form>
EOD;
?>
