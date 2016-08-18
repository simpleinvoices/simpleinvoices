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

<div class="si_center">
<h3>Email {$invoice.index_name|htmlsafe} to Customer as PDF</h3>
</div>

<form name="frmpost" action="index.php?module=invoices&amp;view=email&amp;stage=2&amp;id={$smarty.get.id|urlencode}" method="post">

<div class="si_form">
	<table>
		<tr>
			<th>{$LANG.email_from}
				<a class="cluetip" href="#"	rel="index.php?module=documentation&amp;view=view&amp;page=help_email_from" title="{$LANG.email_from}"><img src="./images/common/help-small.png" alt="help" /></a>
			</th>
			<td><input type="text" name="email_from" size="50" value="{$biller.email|htmlsafe}" /></td>
		</tr>
		<tr>
			<th>{$LANG.email_to}
				<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_email_to" title="{$LANG.email_to}"><img src="./images/common/help-small.png" alt="help" /></a>
			</th>
			<td><input type="text" name="email_to" size="50" value="{$customer.email|htmlsafe}" /></td>
		</tr>
		<tr>
			<th>{$LANG.email_bcc}
				<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_email_bcc" title="{$LANG.email_bcc}"><img src="./images/common/help-small.png" alt="help" /></a>
			</th>
			<td><input type="text" name="email_bcc" size="50" value="{$biller.email|htmlsafe}" /></td>
		</tr>
		<tr>
			<th>{$LANG.subject}</th>
			<td><input type="text" name="email_subject" size="70" value="{$invoice.index_name|htmlsafe} from {$biller.name|htmlsafe} is attached" /></td>
		</tr>
		<tr>
			<th>{$LANG.message}</th>
			<td><textarea name="email_notes" class="editor" rows="16" cols="70"></textarea></td>
		</tr>
	</table>
</div>

	<div class="si_toolbar si_toolbar_form">
	            <button type="submit" class="invoice_save positive" name="submit" value="{$LANG.email}">
	                <img class="button_img" src="./images/common/tick.png" alt="tick" /> 
	                {$LANG.email}
	            </button>
	</div>

<input type="hidden" name="op" value="insert_customer" />
</form>
{/if}




{if $smarty.get.stage == 2}
<meta http-equiv="refresh" content="2;URL=index.php?module=invoices&amp;view=manage" />

<div class="si_message">
	{$message|outhtml}
</div>


{/if}
