{*
/*
* Script: email.tpl
* 	 Send invoice via email page template
*
* Authors:
*	 Justin Kelly, Nicolas Ruflin
*
* Last edited:
* 	 2007-07-18
*
* License:
*	 GPL v2 or above
*
* Website:
*	http://www.simpleinvoices.org
*/
*}
{if $smarty.get.stage == 1 }

<form name="frmpost" action="index.php?module=invoices&amp;view=email&amp;stage=2&amp;invoice={$smarty.get.invoice}" method="post">
<div id="top"><h3>Email Invoice to Customer as PDF</h3></div>
<hr />
<table align="center">
	<tr>
		<td class="details_screen">{$LANG.from}</td>
		<td><input type="text" name="email_from" size="50" value="{$biller.email}" /></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.to}</td>
		<td><input type="text" name="email_to" size="50" value="{$customer.email}" /></td>
	</tr>
	<tr>
		<td class="details_screen">BCC</td>
		<td><input type="text" name="email_bcc" size="50" value="{$biller.email}" /></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.subject}</td>
		<td><input type="text" name="email_subject" size="50" value="{$preferences.pref_inv_wording} {$invoice.id} from {$biller.name} is attached" /></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.message}</td>
		<td><textarea name="email_notes" rows="3" cols="20"></textarea></td>
	</tr>
</table>
<hr />
<input type="submit" name="submit" value="{$LANG.email}" />
<input type="hidden" name="op" value="insert_customer" />
</form>
{/if}

{if $smarty.get.stage == 2}

<div id="top"></b></div>

<table align="center">
	<tr>
		<td>{$message}</td>
	</tr>
</table>

{/if}
