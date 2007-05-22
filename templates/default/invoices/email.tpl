{if $smarty.get.stage == 1 }

<form name="frmpost" ACTION="index.php?module=invoices&view=email&stage=2&submit={$smarty.get.submit}" METHOD="post">
<div id="top"><b>Email Invoice to Customer as PDF</b></div>
<hr />
<table align=center>
	<tr>
		<td class="details_screen">From<a
		href="docs.php?p=email_from&t=help"
		rel="gb_page_center[450, 450]"><img
		src="./images/common/help-small.png"></img></a></td>
		<td><input type=text name="email_from" size=50 value="{$biller.email}" ></td>
	</tr>
	<tr>
		<td class="details_screen">To<a
		href="docs.php?t=help&p=email_to"
		rel="gb_page_center[450, 450]"><img
		src="./images/common/help-small.png"></img></a></td>
		<td><input type=text name="email_to" size=50 value="{$customer.email}" ></td>
	</tr>
	<tr>
	<td class="details_screen">BCC<a
		href="docs.php?t=help&p=email_bcc"
		rel="gb_page_center[450, 450]"><img
		src="./images/common/help-small.png"></img></a></td>
	<td><input type=text name="email_bcc" size=50 value="{$biller.email}"></td>
	</tr>
	<tr>
	<td class="details_screen">Subject</td>
	<td><input type=text name="email_subject" size=50 value="{$preferences.pref_inv_wording} {$invoice.id} from {$biller.name} is attached"></td>
	</tr>
	<tr>
		<td class="details_screen">Message</td>
		<td><textarea name='email_notes' rows=8 cols=50></textarea></td>
	</tr>
</table>
<hr></hr>
<input type=submit name="submit" value="{$LANG.email}">
<input type=hidden name="op" value="insert_customer">
</form>
{/if}

{if $smarty.get.stage == 2}

<div id="top"></b></div>

<table align=center>
</table>

{/if}
