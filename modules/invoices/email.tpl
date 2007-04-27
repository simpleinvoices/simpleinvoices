<?php


$block_stage1 = <<<EOD

<form name="frmpost" ACTION="index.php?module=invoices&view=email&stage=2&submit={$invoice_id}" METHOD=POST onsubmit="return frmpost_Validator(this)">
<div id="top"><b>Email Invoice to Customer as PDF</b></div>
<hr></hr>
<table align=center>
	<tr>
		<td class="details_screen">From<a
		href="./documentation/info_pages/email_from.html"
		rel="gb_page_center[450, 450]"><img
		src="./images/common/help-small.png"></img></a></td>
		<td><input type=text name="email_from" size=50 value="{$biller[b_email]}" ></td>
	</tr>
	<tr>
		<td class="details_screen">To<a
		href="./documentation/info_pages/email_to.html"
		rel="gb_page_center[450, 450]"><img
		src="./images/common/help-small.png"></img></a></td>
		<td><input type=text name="email_to" size=50 value="{$customer[c_email]}" ></td>
	</tr>
	</tr>
	<td class="details_screen">BCC<a
		href="./documentation/info_pages/email_bcc.html"
		rel="gb_page_center[450, 450]"><img
		src="./images/common/help-small.png"></img></a></td>
	<td><input type=text name="email_bcc" size=50 value="{$biller[b_email]}"></td>
	</tr>
	<tr>
		<td class="details_screen">Message</td>
		<td><textarea name='email_notes' rows=8 cols=50></textarea></td>
	</tr>
</table>
<hr></hr>
<input type=submit name="submit" value="{$LANG['email']}">
<input type=hidden name="op" value="insert_customer">
</form>
EOD;



$block_stage2 = <<<EOD

<div id="top"></b></div>

<table align=center>
</table>
EOD;



?>
