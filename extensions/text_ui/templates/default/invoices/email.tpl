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

<form name="frmpost" ACTION="index.php?module=invoices&view=email&stage=2&invoice={$smarty.get.invoice}" METHOD="post">
<div id="top"><b>Email Invoice to Customer as PDF</b></div>
<hr />
<table align=center>
	<tr>
		<td class="details_screen">From
		</td>
		<td><input type=text name="email_from" size=50 value="{$biller.email}" ></td>
	</tr>
	<tr>
		<td class="details_screen">To
		</td>
		<td><input type=text name="email_to" size=50 value="{$customer.email}" ></td>
	</tr>
	<tr>
	<td class="details_screen">BCC
		</td>
	<td><input type=text name="email_bcc" size=50 value="{$biller.email}"></td>
	</tr>
	<tr>
	<td class="details_screen">Subject</td>
	<td><input type=text name="email_subject" size=50 value="{$preferences.pref_inv_wording} {$invoice.id} from {$biller.name} is attached"></td>
	</tr>
	<tr>
		<td class="details_screen">Message</td>
		<td><textarea name='email_notes' rows=3 cols=20></textarea></td>
	</tr>
</table>
<hr></hr>
<input type="submit" name="submit" value="{$LANG.email}">
<input type="hidden" name="op" value="insert_customer">
</form>
{/if}

{if $smarty.get.stage == 2}

<div id="top"></b></div>

<table align=center>
	<tr>
		<td>{$message}</td>
	</tr>
</table>

{/if}
