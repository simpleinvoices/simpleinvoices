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

<form name="frmpost" action="index.php?module=invoices&view=email&stage=2&id={$smarty.get.id}" method="post">
<div id="top"><h3>Email Invoice to Customer as PDF</h3></div>

<table align="center">
	<tr>
		<td class="details_screen">From
		<a class="cluetip" href="#"	rel="docs.php?t=help&p=email_from" title="{$LANG.email_from}"><img src="./images/common/help-small.png" /></a>
		</td>
		<td><input type="text" name="email_from" size="50" value="{$biller.email}" /></td>
	</tr>
	<tr>
		<td class="details_screen">To
		<a class="cluetip" href="#"	rel="docs.php?t=help&p=email_to" title="{$LANG.email_to}"><img src="./images/common/help-small.png" /></a>
		</td>
		<td><input type="text" name="email_to" size="50" value="{$customer.email}" /></td>
	</tr>
	<tr>
	<td class="details_screen">BCC
		<a class="cluetip" href="#"	rel="docs.php?t=help&p=email_bcc" title="{$LANG.email_bcc}"><img src="./images/common/help-small.png" /></a>
		</td>
	<td><input type="text" name="email_bcc" size="50" value="{$biller.email}" /></td>
	</tr>
	<tr>
	<td class="details_screen">Subject</td>
	<td><input type="text" name="email_subject" size="50" value="{$preferences.pref_inv_wording} {$invoice.id} from {$biller.name} is attached"></td>
	</tr>
	<tr>
		<td class="details_screen">Message</td>
		<td><textarea name='email_notes' class="editor" rows="8" cols="50"></textarea></td>
	</tr>
</table>

<br>
	<table class="buttons" align="center">
	    <tr>
	        <td>
	            <button type="submit" class="invoice_save positive" name="submit" value="{$LANG.email}">
	                <img class="button_img" src="./images/common/tick.png" alt=""/> 
	                {$LANG.email}
	            </button>
	            <input type="hidden" name="op" value="insert_customer">
			</td>
		    </tr>
	 </table>


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
